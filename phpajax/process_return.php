<?php
/*
    RETURN HISTORY:
    1. get GRS branch id ID $getGrsBranchID 
    2. update chalanstock freeqty for grs branch and grsqcqty column
    3. update returned_qty in delivery_order_detail;
*/
session_start();
require "../common/conn.php";
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
include_once('../rak_framework/connection.php');
require_once('../common/insert_gl.php');

$usr = $_SESSION["user"];
if(!$_SESSION["user"]){
	header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $quantity = $_POST['qty'];
//   print_r($_POST);die; //Array ([dodid] => 108  [itemid] => 287 [deliveryid] => DO-000044 [orderid] => QT-000425 [qty] => 0 [action] => processreturn )

    $getGrsBranchID = fetchByID('branch','name','GRS','id');
    //update GRS Stock in chalanstock table;
    
    //check if any GRS qty exists in chalanstock table;
    //function fetchTotalRecordByCondition($tblName,$codition,$WantedField)
    
    $freeqty = fetchTotalRecordByCondition('chalanstock','storerome = "'.$getGrsBranchID.'" AND product ='.$_POST['itemid'],'freeqty');
  // echo $freeqty;die;
    
    if($freeqty>0)
    {
        $debug = 0;
        $condition = "storerome=".$getGrsBranchID.' AND product='.$_POST['itemid'];
        updateByID('chalanstock','freeqty',$_POST['qty'],$condition);
        updateByID('chalanstock','grsqcqty',$_POST['qty'],$condition);
    }
    else
    {
        
        $inputData = array(
            'table' => 'chalanstock',
            'data' => array(
                'product' => $_POST['itemid'],
                'freeqty' => $_POST['qty'],
                'storerome' => $getGrsBranchID,
                'grsqcqty' => $_POST['qty']    
            ),

        );

        $result = insertData2($inputData);
        /*
        echo $result['msg'];
        echo '<br>';
        echo $result['success'];
         echo $result['insertId'];
        */
    }
    
    //update delivery_order_detail col: returned_qty
    //   $condition = "id=".$_POST['dodid'];
    //   updateByID('delivery_order_detail','due_return_qty',$_POST['qty'],$condition);   
    $qryUpdateQaw = "UPDATE `delivery_order_detail` SET due_return_qty= due_return_qty + $quantity WHERE id = ".$_POST['dodid'];
    if ($conn->query($qryUpdateQaw) == TRUE)
    {
        echo "Successfully Updated! <br>";
    }
    
    //get soitemdetail id;
     //copy data from soitemdetails 
    $returnqry='insert into order_returns ( `socode`, `sosl`, `productid`, `vat`, `ait`, `vatrate`, `aitrate`, `mu`, `qty`, `qtymrc`, `otc`, `mrc`, `currency`, `remarks`, `barcode`, `storeroome`, `cost`, `discountrate`, `discounttot`, `deliveredqty`, `dueqty`, `return_qty`, `return_store`, `makeby`, `makedt`) 
    select `socode`, `sosl`, `productid`, `vat`, `ait`, `vatrate`, `aitrate`, `mu`, `qty`, `qtymrc`, `otc`, `mrc`, `currency`, `remarks`, `barcode`, `storeroome`, `cost`, `discountrate`, `discounttot`, `deliveredqty`, `dueqty`,'.$quantity.' ,'.$getGrsBranchID.' , '.$usr.', sysdate() FROM soitemdetails where socode="'.$_POST['orderid'].'"';
    
    //echo $returnqry;die;
    
    if ($conn->query($returnqry) == TRUE) 
    {
        
        echo "Return Initiated";
    }
    
   
    //accounting//
    $returnamt=0;
    $Qdeliveryamt = "select (`discounttot`/`qty`* ".$_POST['qty'].") amt  FROM soitemdetails WHERE socode ='".$_POST['orderid']."' and productid=".$_POST['itemid'];
    // echo $Qdeliveryamt;die;
    $resDelivAmt = $conn->query($Qdeliveryamt);
    while ($rowamt = $resDelivAmt->fetch_assoc()) { $returnamt = $rowamt["amt"];  }
    $ordid=$_POST['orderid'];    
    //===Wallet==//
    $prevreturn= fetchByID('invoice','soid',$ordid,'return_amount');
    $invoicecond="soid= '$ordid'";
    $prevdue= fetchByID('invoice','soid',$ordid,'dueamount');
    $orgid = fetchByID('invoice','soid',$ordid,'organization');
    $orgupdbalop= fetchByID('organization','id',$orgid,'balance');  
     
    $waletcond = "id=".$orgid;
    $netreturn=$prevreturn+$deliveredamt;
    updateByID('invoice','return_amount',$netreturn,$invoicecond);
     if($prevdue > 0)
        {
            if($prevdue > $returnamt)
            {
                $due=$prevdue-$returnamt;
                 updateByID('invoice','dueamount',$due,$invoicecond);
            }
            else
            {
                $wallet=$returnamt-$prevdue;
                $orgbal=$orgupdbalop+$wallet;
                updateByID('invoice','dueamount',0,$invoicecond);
                updateByID('organization','balance',$orgbal,$waletcond);
                
                //$orgupdbalqry= fetchByID('organization','id',$orgid,'balance'); 
                $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
                values(date(sysdate()),$orgid,'C','D','$ordid',$wallet,$orgbal,'Return Sale',$usr,sysdate())";
                if ($conn->query($orgwallet) == TRUE) 
                {
                     echo "Wallet Updated";
                 } 
                
            }
        }
       
       
     
  
   // echo $orgwallet;die;
    //===Wallet==//
        
         $revenuegl = fetchByID('glmapping','buisness',2,'mappedgl');	
            //$bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
         //   $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
        $walletgl = fetchByID('glmapping','buisness',21,'mappedgl');
        
        $descr="Return againts delivery -".$_POST['orderid']; 
              $refno=$_POST['orderid'];
             $vouchdt= date("d/m/Y");
               
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr, 
            	'entryby' => $usr,
            );
            	
            		$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$walletgl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$returnamt,
            		'remarks' 	=>	'Cash Reserve aginst return',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
             
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$revenuegl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$returnamt,
            		'remarks' 	=>	'Sales Return ',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            		insertGl($glmstArr,$gldetailArr);
            //	print_r($gldetailArr);die;
       
    
}//if(!$_SESSION["user"]){







?>