<?php

require "conn.php";
include_once('rak_framework/fetch.php');
session_start();
$usr=$_SESSION["user"];

$errflag = true;

$invoiceNo = $_POST['invoice'];
$orderId = $_POST['socode'];
$totalDiscount = $_POST['totaldisamt'];
$invoiceAmount = $_POST['invoiceamt'];

$sodetails      = $_POST["sodetails"];
$invids         = $_POST["invid"];

$adjustments    = $_POST["adjustment"];


//print_r($_POST);die;

if (is_array($sodetails)){
	for ($i=0;$i<count($sodetails);$i++){
	    $sodetail = $sodetails[$i];    $invid = $invids[$i];     
	    $adjustment = $adjustments[$i];
	    
	    //Get Info
    	$qryInfo = "SELECT `vat`,`vat_reserved`,`otc_reserved`, `otc`,`discounttot`, `discountrate`, `discounttot_reserved`, `qty` FROM `soitemdetails` WHERE id = '".$sodetail."'";
    	$resultInfo = $conn->query($qryInfo);
    	while ($rowInfo = $resultInfo->fetch_assoc()) {
    	    
    	    $vat = $rowInfo["vat"] + $rowInfo["vat_reserved"];
    	    $otc = $rowInfo["otc"] + $rowInfo["otc_reserved"];
    	    $discounttot = $rowInfo["discounttot"] + $rowInfo["discounttot_reserved"];
    	    $discountrate = $rowInfo["discountrate"];
    	    $qty = $rowInfo["qty"];
    	}
	    
	    if($adjustment > 0){
    		
    		$adjustment_vat           = ($vat * $adjustment) / 100;
    		$adjustment_otc           = ($otc * $adjustment) / 100;
    		
    		/*if($discountrate <= 0){
    		    $adjustment_discount = 0;
    		    $discounttot = ($otc - $adjustment_otc) * $qty;
    		}else{
    		    $adjustment_discount   = ($discounttot * $adjustment) / 100;
    		    $discounttot = $discounttot - $adjustment_discount;
    		}*/
    		
    		$adjustment_discount   = ($discounttot * $adjustment) / 100;
    		$discounttot = $discounttot - $adjustment_discount;
    		
    		//Update Soitemdetails Table
    	    $qryUpdateSo = "UPDATE `soitemdetails` SET `vat`='".($vat - $adjustment_vat)."',`vat_reserved`='".$adjustment_vat."', `otc`='".($otc-$adjustment_otc)."',
                          `otc_reserved`='".$adjustment_otc."', `discounttot`='".$discounttot."',`discounttot_reserved`='".$adjustment_discount."',
                          `adjustment`='".$adjustment."' 
                          WHERE `id` = '".$sodetail."'";
            //echo $qryUpdateSo;die;
            $conn->query($qryUpdateSo);
            
            //Update InvDetails Table
            $qryUpdateInv = "UPDATE `invoicedetails` SET `vat`='".($vat - $adjustment_vat)."',`vat_reserved`='".$adjustment_vat."', `amount`='".($otc-$adjustment_otc)."',
                      `amount_reserved`='".$adjustment_otc."', `discounttot`='".$discounttot."',`discounttot_reserved`='".$adjustment_discount."'
                      ,`adjustment`='".$adjustment."'
                      WHERE `id` = '".$invid."'";
            $conn->query($qryUpdateInv);
    		
	    }
	}
	
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
    
    //accounting  , `dueamount`='".$sumtot."', `due_reservedamt`='".($invoiceamount-$sumtot)."'
     /* Accounnting */
              $amt= $invoiceamount-$sumtot;     
                            
             
            /* */    
            
            $descr="Voucher againts purchase -".$invoiceNo; 
              $refno=$invoiceNo;
             $vouchdt= date("d/m/Y");
               
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr,
            	'entryby' => $hrid,
            );
            	
            
            $cashgl = fetchByID('glmapping','buisness',3,'mappedgl');
            $finanreserv = fetchByID('glmapping','buisness',14,'mappedgl');
           // $wallet = fetchByID('glmapping','buisness',21,'mappedgl');
          // $receivablefromcustomer='102020101';
            
           // $wallet = fetchByID('glmapping','buisness',21,'mappedgl');
          // $receivablefromcustomer='102020101';
          // $receivablefromcustomerFinance='102020103';
            
            //$tlandingcost=0;
            $gl=$cashgl;
           
            if($whichTab==1)
            	{
            	    $gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$gl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Cash collection for payment anignst invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
                    );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$finanreserv,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Cash collection for payment anignst invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            		insertGl($glmstArr,$gldetailArr);
        /* Accounting*/    	
    
    
    
    
    
}
}
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;

}
    
if (true) {
    header("Location: ".$hostpath."/financialCustomizationList.php?res=4&msg='Update Data'&id=$empid&mod=17");
    
} else {
    $err="Error: " . $qry . "<br>" . $conn->error;
    header("Location: ".$hostpath."/financialCustomizationList.php??res=4&msg='Update Data'&id=$empid&mod=17");
}


?>