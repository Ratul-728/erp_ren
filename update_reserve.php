<?php
 session_start();
require "common/conn.php";

include_once('rak_framework/fetch.php');
include_once('rak_framework/insert.php');
include_once('rak_framework/edit.php');
include_once('rak_framework/misfuncs.php');
include_once('rak_framework/connection.php');
require_once('common/insert_gl.php');

 
 
 session_start();
 
/*
require "common/conn.php";
require "rak_framework/fetch.php";
require "rak_framework/misfuncs.php";
require_once('common/insert_gl.php');
*/
ini_set('display_errors',0);

$usr = $_SESSION["user"];
//print_r($_POST);
$adjper=$_POST['reserved'];

//$adjamount=$_POST['amount'];
$collid=$_POST['trxnid'];
 
$qryInfo="SELECT id,`transref`,`customerOrg`,`amount`,`financadjstmnt`,`adjustedamount`,`transref` invoice FROM `collection` WHERE id=$collid";
$resultInfo = $conn->query($qryInfo);
while ($rowInfo = $resultInfo->fetch_assoc()) 
{
    $invoiceNo = $rowInfo["invoice"];
    $colamount = $rowInfo["amount"];
}
//echo $colamount;die;

$qryInfo1="select id,invoiceno,soid,invoiceamt  from invoice where `invoiceno`='$invoiceNo'";
//echo $qryInfo1;die;
$resultInfo1 = $conn->query($qryInfo1);
while ($rowInfo1 = $resultInfo1->fetch_assoc()) 
{ 
    $orderId = $rowInfo1["soid"];
    $invoiceAmount= $rowInfo1["invoiceamt"];
    $invids=$rowInfo1["id"];
}
//echo $invoiceAmount;die;

$cashratio=$colamount/$invoiceAmount*100;
//echo $colamount.'-'.$invoiceAmount.'-'.$cashratio;die;
//$totalDiscount      = $_POST['totaldisamt'];
//$sodetails          = $_POST["sodetails"];
//print_r($_POST);die;
//$adjustments        = $_POST["adjustment"];
//echo $adjustments;die;
$adjustments=$adjper+0;
//echo $adjustments;die;
$adjustment=$cashratio*$adjustments*0.01;

 //update sodetail and invoicedetails Info
// echo $adjustments;die;
if($adjustment > 0)
{ 
   //soitem part
    $qrysoInfo = "SELECT id, `vat`,`vat_reserved`,`otc_reserved`, `otc`,`discounttot`, `discountrate`, `discounttot_reserved`, `qty` FROM `soitemdetails` WHERE socode = '$orderId'";
    $resultsoInfo = $conn->query($qrysoInfo);
    while ($rowsoInfo = $resultsoInfo->fetch_assoc())
    {
        $sodetail=$rowsoInfo["id"];
        $vat = $rowsoInfo["vat"] + $rowsoInfo["vat_reserved"];
        $otc = $rowsoInfo["otc"] + $rowsoInfo["otc_reserved"];
        $discounttot = $rowsoInfo["discounttot"] + $rowsoInfo["discounttot_reserved"];
        $discountrate = $rowsoInfo["discountrate"];
        $qty = $rowsoInfo["qty"];
    
    //if($adjustment > 0)
      //  {
    		
    		$adjustment_vat           = ($vat * $adjustment) / 100;
    		$adjustment_otc           = ($otc * $adjustment) / 100;
    		$adjustment_discount   = ($discounttot * $adjustment) / 100;
    		$discounttot = $discounttot - $adjustment_discount;
    		
    		//Update Soitemdetails Table
    	    $qryUpdateSo = "UPDATE `soitemdetails` SET `vat`='".($vat - $adjustment_vat)."',`vat_reserved`='".$adjustment_vat."', `otc`='".($otc-$adjustment_otc)."',
                          `otc_reserved`='".$adjustment_otc."', `discounttot`='".$discounttot."',`discounttot_reserved`='".$adjustment_discount."',
                          `adjustment`='".$adjustment."' 
                          WHERE `id` = '".$sodetail."'";
            //echo $qryUpdateSo;die;
            $conn->query($qryUpdateSo);
    //    }
    
    }
    
    //invoice part
    $qryinvInfo = "SELECT id, `vat`,`vat_reserved`,`amount_reserved` otc_reserved,`amount` otc,`discounttot`, `discountrate`, `discounttot_reserved`, `qty` FROM invoicedetails WHERE socode ='$orderId'";
    $resultinvInfo = $conn->query($qryinvInfo);
    while ($rowinvInfo = $resultinvInfo->fetch_assoc())
    {
        $sodetailinv=$rowinvInfo["id"];
        $vatinv = $rowinvInfo["vat"] + $rowinvInfo["vat_reserved"];
        $otcinv = $rowinvInfo["otc"] + $rowinvInfo["otc_reserved"];
        $discounttotinv = $rowinvInfo["discounttot"] + $rowinvInfo["discounttot_reserved"];
        $discountrateinv = $rowinvInfo["discountrate"];
        $qty = $rowinvInfo["qty"];
    
    //if($adjustment > 0)
      //  {
    		
    		$adjustment_vatinv           = ($vatinv * $adjustment) / 100;
    		$adjustment_otcinv           = ($otcinv * $adjustment) / 100;
    		$adjustment_discountinv   = ($discounttotinv * $adjustment) / 100;
    		$discounttotinv = $discounttotinv - $adjustment_discountinv;
    		
            //Update InvDetails Table
            $qryUpdateInv = "UPDATE `invoicedetails` SET `vat`='".($vatinv - $adjustment_vatinv)."',`vat_reserved`='".$adjustment_vatinv."', `amount`='".($otcinv-$adjustment_otcinv)."',
                      `amount_reserved`='".$adjustment_otcinv."', `discounttot`='".$discounttotinv."',`discounttot_reserved`='".$adjustment_discountinv."'
                      ,`adjustment`='".$adjustment."'
                      WHERE `id` = '".$sodetailinv."'";
            $conn->query($qryUpdateInv);
    		
    //    }
    
    }
    
}
    
 // update so and invoice with summary
 
 //Get Sum Info
$qrySum = "SELECT SUM(`vat`) sumvat, SUM(`discounttot`) sumtot FROM `soitemdetails` WHERE `socode` = '".$orderId."'";
$resultSum = $conn->query($qrySum);
while ($rowSum = $resultSum->fetch_assoc()) {
    $sumvat = $rowSum["sumvat"];
    $sumtot = $rowSum["sumtot"] + $sumvat;
}

//Get Main Info
$qryMainInfo = "SELECT `vat`,`vat_adjustment`,`reserved_amount`, `invoiceamount` FROM `soitem` WHERE `socode` = '".$orderId."'";
$resultMainInfo = $conn->query($qryMainInfo);
while ($rowMainInfo = $resultMainInfo->fetch_assoc()) {
    $totmainvat = $rowMainInfo["vat"]+ $rowMainInfo["vat_adjustment"];
    $invoiceamount = $rowMainInfo["invoiceamount"]+$rowMainInfo["reserved_amount"];
}

$qryUpdateSoMain ="UPDATE `soitem` SET `vat`='".$sumvat."',`vat_adjustment`='".($totmainvat-$sumvat)."', `invoiceamount`='".$sumtot."',
                  `reserved_amount`='".($invoiceamount-$sumtot)."' WHERE `socode` = '".$orderId."'";
$conn->query($qryUpdateSoMain);

$qryUpdateInvMain="UPDATE `invoice` SET `invoiceamt`='".$sumtot."', `reserved_amount`='".($invoiceamount-$sumtot)."'
                    , `amount_bdt`='".$sumtot."', `dueamount`='".$sumtot."', `due_reservedamt`='".($invoiceamount-$sumtot)."'
                    WHERE `invoiceno` = '".$invoiceNo."'";
$conn->query($qryUpdateInvMain);

//update collection    
$qrycolupd="update collection set financadjstmnt=$adjper,adjustedamount=amount*$adjper*.01 where id=$collid";
$conn->query($qrycolupd);

//$colamount hello
 /* Accounnting */

$amt= $colamount*$adjustments*.01;     
                             
             
                 
            
            $cashgl = fetchByID('glmapping','buisness',3,'mappedgl');
            $advancefromcustomer = fetchByID('glmapping','buisness',6,'mappedgl');
            $reservcashgl = fetchByID('glmapping','buisness',14,'mappedgl');
            /* */
            $descr="Financial adjustment Voucher againts sales -".$invoiceNo; 
            $refno=$invoiceNo;
             $vouchdt= date("d/m/Y");
               
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr,
            	'entryby' => $hrid,
            );
          
    	    $gldetailArr[] = array(
    		'sl'	 =>	1,
            'glac'	 =>	$reservcashgl,	//glno
    		'dr_cr' 	=>	'C',
    		'amount' 	=>	$amt,
    		'remarks' 	=>	'Cash collection for payment against invoice',
    		'entryby' 	=>	$hrid,
    		'entrydate' 	=>	$vouchdt
            );
            
            
        	$gldetailArr[] = array(
        		'sl'	 =>	2,
                'glac'	 =>	$advancefromcustomer,	//glno
        		'dr_cr' 	=>	'D',
        		'amount' 	=>	$amt,
        		'remarks' 	=>	'Cash collection for payment against invoice',
        		'entryby' 	=>	$hrid,
        		'entrydate' 	=>	$vouchdt
            );
            
            insertGlfin($glmstArr,$gldetailArr);
        /////    
            $descr1="Voucher of collecetion against sales -".$invoiceNo; 
              
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr1,
            	'entryby' => $hrid,
            );
            
          
    	    $gldetailArr[] = array(
    		'sl'	 =>	1,
            'glac'	 =>	$cashgl,	//glno
    		'dr_cr' 	=>	'D',
    		'amount' 	=>	$amt,
    		'remarks' 	=>	'Cash collection for payment anignst invoice',
    		'entryby' 	=>	$hrid,
    		'entrydate' 	=>	$vouchdt
            );
            
            
        	$gldetailArr[] = array(
        		'sl'	 =>	2,
                'glac'	 =>	$advancefromcustomer,	//glno
        		'dr_cr' 	=>	'C',
        		'amount' 	=>	$amt,
        		'remarks' 	=>	'Cash collection for payment anignst invoice',
        		'entryby' 	=>	$hrid,
        		'entrydate' 	=>	$vouchdt
            );
            
            insertGl($glmstArr,$gldetailArr);
            
        /* Accounting*/    	
    
   // echo $_POST['trxnid']." Reserved amount ".$_POST['reserved']. " taka saved"; 
   echo "Financial adjustment has been made from cash reecipt #'".$_POST['trxnid']."' aganist the sales invoice  #'$invoiceNo'  ";

?>