<?php
require "conn.php";
require "image_resize.php";

ini_set('display_errors', 0);

session_start();

if ($_SESSION["user"] == '') {
    header("Location: " . $hostpath . "/hr.php");
}
$usr = $_SESSION["user"];
//print_r($_REQUEST);die;

include_once('../rak_framework/fetch.php');
include_once('../rak_framework/edit.php');
require_once('insert_gl.php');
if ( isset( $_POST['cancel'] ) )
{
      header("Location: ".$hostpath."/rawitemAprv.php?res=01&msg='New Entry'&id=''&mod=1");
}
else
{
	$barcode=0;
    if ( isset( $_POST['update'] ) ) 
    {
        $tid= $_REQUEST['itid'];
        $code= $_REQUEST['code'];               //if($code==''){$code='NULL';}
        $stocktp = $_POST["stocktp"];
        $bkqty = $_POST["bkqty"];
        
        
        $cal_unitprice = $_POST["aprvunitprice"]; 
        $cal_transferrate = $_POST["transerrate"];
        $cal_exfactoryprice = $_POST["exfactory"];
        $cal_dutyfreight = $_POST["dutyFreight"];
        $cal_miscelanious = $_POST["miscellaneous"];
        $cal_ldp = $_POST["ldp"];
        $cal_margin = $_POST["margin"];
        $cal_totpriceexcldvat = $_POST["exvat"];
        $cal_vat = $_POST["calvat"];
        $cal_totpriceincldvat = $_POST["envat"];
        $approvedst = $_POST["isapproved"];
        
      
       // echo $stocktp;die;
        
        $qry="update item set rate=$cal_totpriceincldvat ,`cal_unitprice`=$cal_unitprice,`cal_transferrate`=$cal_transferrate,`cal_exfactoryprice`=$cal_exfactoryprice,
        `cal_dutyfreight`=$cal_dutyfreight,`cal_miscelanious`=$cal_miscelanious,`cal_ldp`=$cal_ldp,`cal_margin`=$cal_margin
        ,`cal_totpriceexcldvat`=$cal_totpriceexcldvat,`cal_vat`=$cal_vat,`cal_totpriceincldvat`=$cal_totpriceincldvat
        ,`approvedst`='$approvedst' where `id`=$tid";
        $err="item updated successfully";
      //echo $qry;die;
      
        if ($conn->connect_error) 
        {
           echo "Connection failed: " . $conn->connect_error;
        }
        else
        {
            if ($conn->query($qry) == TRUE) 
            {
        		if ( isset( $_POST['update'] )&& $approvedst==1 ) 
        		{
                    $bc = fetchByID('item',id,$tid,'barcode');
                    if($stocktp=='2')
                    {
                        $BranchID = fetchByID('branch','name',"Future",'id');
        			    $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome)
        			    VALUES($tid,$bkqty,$cal_totpriceexcldvat,'$bc',$BranchID)";
        			    //echo $strQryChalanstock;die;
        			    if ($conn->query($strQryChalanstock) == TRUE) { $err="0 qtn added in chalanstock in Future store";  }
        			   
        			    $strQryStock = " update stock set futureqty =$bkqty,costprice=$cal_totpriceexcldvat where product=$tid";
        			    //INSERT INTO stock( `product`, `freeqty`,futureqty,backorderqty ,`bookqty`, `orderedqty`, `deliveredqty`, repairedqty, `costprice`, `prevprice`) 
        			     // VALUES($tid,0,$bkqty,0,0,0,0,0,$cal_totpriceexcldvat,0)";
        			    if ($conn->query($strQryStock) == TRUE) { $err="$bkqty qtn added in future stock";  }
                    }
                    else if($stocktp=='3')
                    {
                       $BranchID = fetchByID('branch','name',"Backorder",'id'); 
                       $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome)
        			    VALUES($tid,$bkqty,$cal_totpriceexcldvat,'$bc',$BranchID)";
        			    //echo $strQryChalanstock;die;
        			    if ($conn->query($strQryChalanstock) == TRUE) { $err="$bkqty qtn added in chalanstock in Backorder store";  }
        			   
        			    $strQryStock = " update stock set backorderqty =$bkqty,costprice=$cal_totpriceexcldvat where product=$tid";  
        			    //INSERT INTO stock( `product`, `freeqty`,futureqty,backorderqty ,`bookqty`, `orderedqty`, `deliveredqty`, repairedqty, `costprice`, `prevprice`) 
        			     // VALUES($tid,0,0,$bkqty,0,0,0,0,$cal_totpriceexcldvat,0)";
        			    if ($conn->query($strQryStock) == TRUE) { $err="$bkqty qtn added in backorder";  }
                    
                    }
                    else
                    {
                        
                    }
        		
        		//	echo $strQryStock;die;
        		}
                header("Location: ".$hostpath."/rawitemAprvList.php?res=1&msg=".$err."&id=".$tid."&mod=24&changedid=".$code);
            
                /* accounting*/
                $ldpReservegl="102010300";
                $reservecash="102050300";
                $vouchdt=date("d/m/Y");
                 $descr="Approve Item with ex factory price"; 
             $refno=$vouchno;
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $code,
            	'remarks' => $descr,
            	'entryby' => $usr,
            );
            	
            //$tlandingcost=0;
            	
            	$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$ldpReservegl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$cal_ldp,
            		'remarks' 	=>	'Exfactory Inventory From Reserve cash',
            		'entryby' 	=>	$usr,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$reservecash,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$cal_ldp,
            		'remarks' 	=>	'Exfactory Inventory From Reserve cash',
            		'entryby' 	=>	$usr,
            		'entrydate' 	=>	$vouchdt
            );
		    insertGl($glmstArr,$gldetailArr);
                /*accounting*/
            } 
            else 
            {
                 $err="Error: " . $qry . "<br>" . $conn->error;
                 header("Location: ".$hostpath."/rawitemAprvList.php?res=2&msg=".$err."&mod=24&id=".$tid."&changedid=".$code);
            }
        }
        $conn->close();
    }
}
?>