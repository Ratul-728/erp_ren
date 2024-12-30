<?php
require "conn.php";

include_once('../rak_framework/fetch.php');
include_once('../rak_framework/connection.php');
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
require_once('insert_gl.php');

include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

//print_r($_POST);die;
$usr = $_SESSION["user"];

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/purchase_dataformList.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['add'] ) ) {
        
        //$poid = $_POST['poid'];
        $vouchno = $_POST['vouchno'];
        $vouchdt = $_POST['vouchdt']; //if($cmbstage==''){$cmbstage='NULL';}
        $pino = $_POST['pino'];        //if($org==''){$org='NULL';}
        $pidt = $_POST['pidt'];       //if($cmbld==''){$cmbld='NULL';}
        $ttno = $_POST['ttno'];           //if($ddt==''){$ddt='NULL';}
        $ttdt = $_POST["ttdt"];
        $at = $_POST["at"];
        $ait = $_POST["ait"];
        $grn = $_POST["grn"];
        $grndt = $_POST["grndt"];
        $cntnrno = $_POST["containerno"];
        $storeName = $_POST["storeName"];
        $received = $_POST["received"];
        $bank = $_POST["bank"];
        $bankdt = $_POST["bankdt"];
        $payamount = $_POST["payamount"];
        $currency = $_POST["currency"];
        $ex_rate = $_POST["ex_rate"];
        $remarks = $_POST["remarks"];
        $branch = $_POST["branch"];
        
        $errorFlag = 0;
        $flag = true;
        
        $barcodes = $_POST["barcode"];
        $civus = $_POST["civu"];
        $civbs = $_POST["civb"];
        $freights = $_POST["freight"];
        $taxes = $_POST["taxes"];
        $cds = $_POST["cd"];
        $sds = $_POST["sd"];
        $rds = $_POST["rd"];
        $vats = $_POST["vat"];
        $qtys = $_POST["qty"];
        $tlcs = $_POST["tlc"];
        $tvs  = $_POST["tv"];
        
        
        // if (is_array($barcodes))
        //     {
        //         for ($i=0;$i<count($barcodes);$i++)
        //             {
        //                 if($barcodes[$i] > 0){
        //                     $flag = true;
        //                     break;
        //                 }
        //             }
        //     }
            
            
        $poid = getFormatedUniqueID('purchase_landing','id','PO-',6,"0");
        
        
        if($flag){
            $qryDeliveryMain = "INSERT INTO `purchase_landing`( `poid`, `voucher_no`, `voucher_date`, `pi_no`, `pi_date`, `lc_tt_no`, `lc_tt_date`, `at`, `ait`, `gnr_no`, `gnr_date`,`containerno`, `exchange_rate`, 
                                                                 `warehouse`, `received_by`, `payment_amount`, `bank_name`, `bank_dt`, `remark`, `makedt`, `currency`, branch)
                                                    VALUES ('".$poid."','".$vouchno."',STR_TO_DATE('".$vouchdt."', '%d/%m/%Y'),'".$pino."',STR_TO_DATE('".$pidt."', '%d/%m/%Y'),'".$ttno."',STR_TO_DATE('".$ttdt."', '%d/%m/%Y'),'".$at."','".$ait."','".$grn."',STR_TO_DATE('".$grndt."', '%d/%m/%Y'),'".$cntnrno."','".$ex_rate."',
                                                            '".$storeName."','".$received."','".$payamount."','".$bank."',STR_TO_DATE('".$bankdt."', '%d/%m/%Y'),'".$remarks."',sysdate(), '".$currency."', '".$branch."')";
            
            //echo $qryDeliveryMain;die;
            if ($conn->query($qryDeliveryMain) == TRUE) {
                $purchaseId = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/purchase_dataformList.php?res=2&mod=3&msg=".$err);
            } 
        }
        else{
            $errorFlag++;
            $err = 'Item not given';
            header("Location: ".$hostpath."/purchase_dataformList.php?res=2&mod=3&msg=".$err);
        }  
        
        $tlandingcost=0;
        $tcd=0;$trd=0;$tsd=0;$tvat=0;$tgt=0;
         if (is_array($barcodes))
            {
                for ($i=0;$i<count($barcodes);$i++)
                    {
                        $barcode = $barcodes[$i]; $civu = $civus[$i]; $civb=$civbs[$i];$freight = $freights[$i];
                        $taxe = $taxes[$i]; $cd = $cds[$i]; $sd=$sds[$i];$rd = $rds[$i];
                        $vat=$vats[$i];$qty = $qtys[$i]; $tlc = $tlcs[$i]; $tv = $tvs[$i];
                        $tcd=$tcd+$cd;$trd=$trd+$rd;$tsd=$tsd+$sd;$tvat=$tvat+$tv;$tgt=$tgt+$taxe;
                        //get Product id by barcode
                        $getInfo = "SELECT id FROM `item` WHERE barcode = '".$barcode."'";
                        $resultitm = $conn->query($getInfo);
                        if ($resultitm->num_rows > 0) {
                            while ($rowitm = $resultitm->fetch_assoc()) {
                                $productId = $rowitm["id"];
                            }
                        }
                        
                        
                        //Update cost price fr item
                        $qryupdate = "UPDATE `item` SET `cost`='".$tv."' WHERE id = ".$productId;
                        $conn->query($qryupdate);
                        
                            $itqry="INSERT INTO `purchase_landing_item`(`pu_id`, `productId`, `com_invoice_val_usd`, `com_invoice_val_bdt`, `freight_charges`, `global_taxes`, `cd`, `rd`, `sd`, `vat`, `qty`, `tot_landed_cost`, `tot_value`) 
                                                            VALUES ('".$purchaseId."','".$productId."','".$civu."','".$civb."','".$freight."','".$taxe."','".$cd."','".$rd."','".$sd."','".$vat."','".$qty."','".$tlc."','".$tv."')";
                             //echo $itqry;die;
                             
                             $tlandingcost=$tlandingcost+$tlc;
                             if ($conn->query($itqry) == TRUE) { 
                                 $err="Delivery added successfully";  
                                 $qaInsert ="INSERT INTO `qa`(`type`,`product_id`, `quantity`, `date_iniciated`, `status`, `delivery_date`, `order_id`) 
                                                        VALUES ('2','".$productId."','".$qty."','".date("Y-m-d H:i:s")."','1',STR_TO_DATE('".$grndt."', '%d/%m/%Y'),'".$poid."')";
                                
                                 if ($conn->query($qaInsert) == TRUE){
                                     $insertedQaId = $conn->insert_id;
                                     $insertQaWarehouse = "INSERT INTO `qa_warehouse`(`qa_id`, `warehouse_id`, `ordered_qty`) 
                                                                VALUES ('".$insertedQaId."','".$storeName."','".$qty."')";
                                     $conn->query($insertQaWarehouse);
                                 }
                                 
                             }
                             else{ $errorFlag++;}
                         
                    }
            }
        $err = "Record created successfully";

    if ($ex_rate==''){$ex_rate=1;}   
    
  $paid_amt=$payamount*$ex_rate;
  
  /* Accounting*/
  
if($bank==14){$bsns_sl=8;} else if($bank==15){$bsns_sl=7;}else if($bank==22){$bsns_sl=4;}
$vendorgl = fetchByID('glmapping','buisness',5,'mappedgl');	
$bankgl = fetchByID('glmapping','buisness',$bsns_sl,'mappedgl');
$inventory = fetchByID('glmapping','buisness',1,'mappedgl');
  
 $descr="Purchase -".$remarks; 
 $refno=$vouchno;
 $glmstArr = array(
	'transdt' => $vouchdt,
	'refno' => $cmbsupnm,
	'remarks' => $descr,
	'entryby' => $usr,
);
	
//$tlandingcost=0;
	
	$gldetailArr[] = array(
		'sl'	 =>	1,
        'glac'	 =>	$vendorgl,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$paid_amt,
		'remarks' 	=>	'Bank Payment',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);


	$gldetailArr[] = array(
		'sl'	 =>	2,
        'glac'	 =>	$bankgl,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$paid_amt,
		'remarks' 	=>	'Amount paid to vendor',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);
		insertGl($glmstArr,$gldetailArr);


$materialintransit = fetchByID('glmapping','buisness',9,'mappedgl');
  
 $descr="Purchase Order -".$remarks; 
 $refno=$vouchno;
 $glmstArr1 = array(
	'transdt' => $vouchdt,
	'refno' => $cmbsupnm,
	'remarks' => $descr,
	'entryby' => $usr,
);
	
//$tlandingcost=0;
	
	$gldetailArr1[] = array(
		'sl'	 =>	1,
        'glac'	 =>	$materialintransit,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$paid_amt,
		'remarks' 	=>	'vendor received fund',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);


	$gldetailArr1[] = array(
		'sl'	 =>	2,
        'glac'	 =>	$vendorgl,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$paid_amt,
		'remarks' 	=>	'Material in transit from vendor',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);
		insertGl($glmstArr1,$gldetailArr1);
/*
$cd = fetchByID('glmapping','buisness',23,'mappedgl');
$rd = fetchByID('glmapping','buisness',24,'mappedgl');
$sd = fetchByID('glmapping','buisness',25,'mappedgl');
$vat = fetchByID('glmapping','buisness',16,'mappedgl');
$atg = fetchByID('glmapping','buisness',26,'mappedgl');
$aitg = fetchByID('glmapping','buisness',27,'mappedgl');
$global = fetchByID('glmapping','buisness',28,'mappedgl');
  
 $descr="Purchase overhead -".$remarks; 
 $refno=$vouchno;
 $glmstArr2 = array(
	'transdt' => $vouchdt,
	'refno' => $refno,
	'remarks' => $descr,
	'entryby' => $usr,
);

$totalcost=	$cd+$sd+$rd+$vat+$global+$atg+$aitg;
//$tlandingcost=0;
	
	$gldetailArr2[] = array(
		'sl'	 =>	1,
        'glac'	 =>	$cd,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$tcd,
		'remarks' 	=>	'paid from bank',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);


	$gldetailArr2[] = array(
		'sl'	 =>	2,
        'glac'	 =>	$rd,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$trd,
		'remarks' 	=>	'paid from bank',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);
	$gldetailArr2[] = array(
		'sl'	 =>	3,
        'glac'	 =>	$sd,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$tsd,
		'remarks' 	=>	'paid from bank',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);


	$gldetailArr2[] = array(
		'sl'	 =>	4,
        'glac'	 =>	$vat,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$tvat,
		'remarks' 	=>	'paid from bank',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);
	$gldetailArr2[] = array(
		'sl'	 =>	5,
        'glac'	 =>	$atg,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$at,
		'remarks' 	=>	'paid from bank',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);


	$gldetailArr2[] = array(
		'sl'	 =>	6,
        'glac'	 =>	$aitg,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$ait,
		'remarks' 	=>	'paid from bank',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);
	$gldetailArr2[] = array(
		'sl'	 =>	7,
        'glac'	 =>	$global,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$tgt,
		'remarks' 	=>	'paid from bank',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);

	$gldetailArr2[] = array(
		'sl'	 =>	8,
        'glac'	 =>	$bankgl,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$totalcost,
		'remarks' 	=>	'Purcahse Cost',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);
		insertGl($glmstArr2,$gldetailArr2);



$descr="Product Costing -".$remarks; 
 $refno=$vouchno;
 $glmstArr3 = array(
	'transdt' => $vouchdt,
	'refno' => $refno,
	'remarks' => $descr,
	'entryby' => $usr,
);

$totalcost1=$cd+$sd+$rd+$vat+$global;
//$tlandingcost=0;
	
	$gldetailArr3[] = array(
		'sl'	 =>	1,
        'glac'	 =>	$cd,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$tcd,
		'remarks' 	=>	'COGS',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);


	$gldetailArr3[] = array(
		'sl'	 =>	2,
        'glac'	 =>	$rd,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$trd,
		'remarks' 	=>	'COGS',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);
	$gldetailArr3[] = array(
		'sl'	 =>	3,
        'glac'	 =>	$sd,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$tsd,
		'remarks' 	=>	'COGS',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);


	$gldetailArr3[] = array(
		'sl'	 =>	4,
        'glac'	 =>	$vat,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$tvat,
		'remarks' 	=>	'paid from bank',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);
	$gldetailArr3[] = array(
		'sl'	 =>	5,
        'glac'	 =>	$global,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$vat,
		'remarks' 	=>	'paid from bank',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);


	$gldetailArr3[] = array(
		'sl'	 =>	6,
        'glac'	 =>	$materialintransit,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$totalcost1,
		'remarks' 	=>	'Overhead posting',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);
		insertGl($glmstArr3,$gldetailArr3);
		
*/
/*		
 	$gldetailArr[] = array(
		'sl'	 =>	3,
        'glac'	 =>	$inventory,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$tlandingcost,
		'remarks' 	=>	'Total Product received',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);



/*		
 	$gldetailArr[] = array(
		'sl'	 =>	3,
        'glac'	 =>	$inventory,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$tlandingcost,
		'remarks' 	=>	'Total Product received',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);


	$gldetailArr[] = array(
		'sl'	 =>	4,
        'glac'	 =>	$vendorgl,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$tlandingcost,
		'remarks' 	=>	'Vendor payble aginst purchase',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
); 	*/	

    //Mail to QA
    if($errorFlag == 0){
        $qrymail = "SELECT id,active FROM `email` WHERE id = 29";
        $resultmail = $conn->query($qrymail);
        while($rowmail = $resultmail->fetch_assoc()){
                              $active = $rowmail["active"];
                              $emailid = $rowmail["id"];
                            if($active == 1){
                                  $recipientNames = array();
                                  $recipientEmails = array();
                                  $ccEmails = array();
                                  $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                                                FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                                  $resultSendTo = $conn->query($qrySendTo);
                                  while($rowst = $resultSendTo->fetch_assoc()){
                                      $recipientNames[] = $rowst["empname"];
                                      if($rowst["type"] == 1){
                                          $recipientEmails[] = $rowst["office_email"];
                                      }else if($rowst["type"] == 2){
                                          $ccEmails[] = $rowst["office_email"];
                                      }
                                  }
                                  if (!empty($recipientEmails)){
                                      $mailsubject = "New purchase order added";
                    
                                      $message = "New Quality check request  for $poid  was received. ";
                                                
                                    sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                                  }
                            }
                          }
    }
		
		
    }
    if ( isset( $_POST['update'] ) ) {
        
        $poid = $_POST['poid'];
        $pid =  $_POST["pid"];
        $vouchno = $_POST['vouchno'];
        $vouchdt = $_POST['vouchdt']; //if($cmbstage==''){$cmbstage='NULL';}
        $pino = $_POST['pino'];        //if($org==''){$org='NULL';}
        $pidt = $_POST['pidt'];       //if($cmbld==''){$cmbld='NULL';}
        $ttno = $_POST['ttno'];           //if($ddt==''){$ddt='NULL';}
        $ttdt = $_POST["ttdt"];
        $at = $_POST["at"];
        $ait = $_POST["ait"];
        $grn = $_POST["grn"];
        
        $grndt = $_POST["grndt"];
        $storeName = $_POST["storeName"];
        $received = $_POST["received"];
        $bank = $_POST["bank"];
        $bankdt = $_POST["bankdt"];
        $payamount = $_POST["payamount"];
        $currency = $_POST["currency"];
        $ex_rate = $_POST["ex_rate"];
        $remarks = $_POST["remarks"];
        
        $errorFlag = 0;
        $flag = true;
        
        $barcodes = $_POST["barcode"];
        $civus = $_POST["civu"];
        $civbs = $_POST["civb"];
        $freights = $_POST["freight"];
        $taxes = $_POST["taxes"];
        $cds = $_POST["cd"];
        $sds = $_POST["sd"];
        $rds = $_POST["rd"];
        $vats = $_POST["vat"];
        $qtys = $_POST["qty"];
        $tlcs = $_POST["tlc"];
        $tvs  = $_POST["tv"];
        $branch = $_POST["branch"];
        
       
        
       
        //reversee accounts
         $prev_amt = fetchByID('purchase_landing','id',$pid,'payment_amount');	
         $prev_ex = fetchByID('purchase_landing','id',$pid,'exchange_rate');
          $prev_paid_amt=$prev_amt*$prev_ex;
           $prev_landed_cost=0;
              
            $getLandingcost = "select  sum(tot_landed_cost) lc from purchase_landing_item  where `pu_id`=$pid";
                        $resultLcost = $conn->query($getLandingcost);
                        if ($resultLcost->num_rows > 0) {
                            while ($rowLcst = $resultLcost->fetch_assoc()) {
                                $prev_landed_cost = $rowLcst["lc"];
                            }
                        }  
              
              $descr="Voucer reverse againts purchase -".$poid; 
              $refno=$vouchno;
             $glmstArr_r = array(
            	'transdt' => $vouchdt,
            	'refno' => $cmbsupnm,
            	'remarks' => $descr,
            	'entryby' => $usr,
            );
            	
            $vendorgl = fetchByID('glmapping','buisness',5,'mappedgl');	
            $bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
            $inventory = fetchByID('glmapping','buisness',1,'mappedgl');	
            
            //$tlandingcost=0;
            	
            	$gldetailArr_r[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$vendorgl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$prev_paid_amt,
            		'remarks' 	=>	'Amount reverse paid to vendor',
            		'entryby' 	=>	$usr,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr_r[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$bankgl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$prev_paid_amt,
            		'remarks' 	=>	'Amount reverse paid to vendor',
            		'entryby' 	=>	$usr,
            		'entrydate' 	=>	$vouchdt
            );
            	
              	$gldetailArr_r[] = array(
            		'sl'	 =>	3,
                    'glac'	 =>	$inventory,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$prev_landed_cost,
            		'remarks' 	=>	'Total Product received revresed',
            		'entryby' 	=>	$usr,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr_r[] = array(
            		'sl'	 =>	4,
                    'glac'	 =>	$vendorgl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$prev_landed_cost,
            		'remarks' 	=>	'Vendor payble aginst purchase reversed',
            		'entryby' 	=>	$usr,
            		'entrydate' 	=>	$vouchdt
            );           	    
            	   
            		insertGl($glmstArr_r,$gldetailArr_r);
        
        //reverse accounts
        
        
        
        
        
        if($flag){
            $qryDeliveryMain = "UPDATE `purchase_landing` SET `voucher_no`='".$vouchno."',`voucher_date`=STR_TO_DATE('".$vouchdt."', '%d/%m/%Y'),`pi_no`='".$pino."',
                                `pi_date`=STR_TO_DATE('".$pidt."', '%d/%m/%Y'), `lc_tt_no`='".$ttno."',`lc_tt_date`=STR_TO_DATE('".$ttdt."', '%d/%m/%Y'),
                                `at`='".$at."',`ait`='".$ait."',`gnr_no`='".$grn."',`gnr_date`=STR_TO_DATE('".$grndt."', '%d/%m/%Y'),containerno='".$cntnrno."',
                                `exchange_rate`='".$ex_rate."',`warehouse`='".$storeName."',`received_by`='".$received."',
                                `payment_amount`='".$payamount."',`bank_name`='".$bank."',`bank_dt`=STR_TO_DATE('".$bankdt."', '%d/%m/%Y'),
                                `remark`='".$remarks."',`currency`='".$currency."',`branch`='".$branch."' WHERE `id` = ".$pid;
            
            //echo $qryDeliveryMain;die;
            if ($conn->query($qryDeliveryMain) == TRUE) {
                $purchaseId = $pid;
                
                //Delete All sub table data
                $qryDelete = "DELETE FROM `purchase_landing_item` WHERE `pu_id` = ".$pid;
                $conn->query($qryDelete);
                
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/purchase_dataformList.php?res=2&mod=3&msg=".$err);
            } 
        }
        else{
            $errorFlag++;
            $err = 'Item not given';
            header("Location: ".$hostpath."/purchase_dataformList.php?res=2&mod=3&msg=".$err);
        }
        
        //Delete all qa and qa warehouse data
        $qryGetQa = "SELECT id FROM `qa` WHERE `order_id` = '$poid'";
        $resultGetQa = $conn->query($qryGetQa);
        while ($rowGetQa = $resultGetQa->fetch_assoc()) {
            $getQaId = $rowGetQa["id"];
            
            //Delete qa_warehouse
            $qryDelQaw = "DELETE FROM `qa_warehouse` WHERE `qa_id` = ".$getQaId;
            $conn->query($qryDelQaw);
            
            //Delete main qa
            $qryDelQa = "DELETE FROM `qa` WHERE id = ".$getQaId;
            $conn->query($qryDelQa);
        }

        
        
         if (is_array($barcodes))
            {
                for ($i=0;$i<count($barcodes);$i++)
                    {
                        $barcode = $barcodes[$i]; $civu = $civus[$i]; $civb=$civbs[$i];$freight = $freights[$i];
                        $taxe = $taxes[$i]; $cd = $cds[$i]; $sd=$sds[$i];$rd = $rds[$i];
                        $vat=$vats[$i];$qty = $qtys[$i]; $tlc = $tlcs[$i]; $tv = $tvs[$i];
                        
                        //get Product id by barcode
                        $getInfo = "SELECT id FROM `item` WHERE barcode = '".$barcode."'";
                        $resultitm = $conn->query($getInfo);
                        if ($resultitm->num_rows > 0) {
                            while ($rowitm = $resultitm->fetch_assoc()) {
                                $productId = $rowitm["id"];
                            }
                        }
                        
                        
                        //Update cost price fr item
                        $qryupdate = "UPDATE `item` SET `cost`='".$tv."' WHERE id = ".$productId;
                        $conn->query($qryupdate);
                        
                            $itqry="INSERT INTO `purchase_landing_item`(`pu_id`, `productId`, `com_invoice_val_usd`, `com_invoice_val_bdt`, `freight_charges`, `global_taxes`, `cd`, `rd`, `sd`, `vat`, `qty`, `tot_landed_cost`, `tot_value`) 
                                                            VALUES ('".$purchaseId."','".$productId."','".$civu."','".$civb."','".$freight."','".$taxe."','".$cd."','".$rd."','".$sd."','".$vat."','".$qty."','".$tlc."','".$tv."')";
                             //echo $itqry;die;
                             
                             
                             if ($conn->query($itqry) == TRUE) { 
                                 $err="Delivery added successfully"; 
                              
                              $tlandingcost=$tlandingcost+$tlc;
                              
                                 $qaInsert ="INSERT INTO `qa`(`type`,`product_id`, `quantity`, `date_iniciated`, `status`, `delivery_date`, `order_id`) 
                                                        VALUES ('2','".$productId."','".$qty."','".date("Y-m-d H:i:s")."','1',STR_TO_DATE('".$grndt."', '%d/%m/%Y'),'".$poid."')";
                                
                                 if ($conn->query($qaInsert) == TRUE){
                                     $insertedQaId = $conn->insert_id;
                                     $insertQaWarehouse = "INSERT INTO `qa_warehouse`(`qa_id`, `warehouse_id`, `ordered_qty`) 
                                                                VALUES ('".$insertedQaId."','".$storeName."','".$qty."')";
                                     $conn->query($insertQaWarehouse);
                                 }
                                 
                             }
                             else{ $errorFlag++;}
                         
                    }
            }
        $err = "Record updated successfully";
        // accounting
              if ($ex_rate==''){$ex_rate=1;}   
                
              $paid_amt=$payamount*$ex_rate;
              
              $descr="Voucer againts purchase -".$remarks; 
              $refno=$vouchno;
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $cmbsupnm,
            	'remarks' => $descr,
            	'entryby' => $usr,
            );
            	
            $vendorgl = fetchByID('glmapping','buisness',5,'mappedgl');	
            $bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
            $inventory = fetchByID('glmapping','buisness',1,'mappedgl');	
            
            //$tlandingcost=0;
            	
            	$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$vendorgl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$paid_amt,
            		'remarks' 	=>	'Amount paid to vendor',
            		'entryby' 	=>	$usr,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$bankgl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$paid_amt,
            		'remarks' 	=>	'Amount paid to vendor',
            		'entryby' 	=>	$usr,
            		'entrydate' 	=>	$vouchdt
            );
            	
              	$gldetailArr[] = array(
            		'sl'	 =>	3,
                    'glac'	 =>	$inventory,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$tlandingcost,
            		'remarks' 	=>	'Total Product received',
            		'entryby' 	=>	$usr,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	4,
                    'glac'	 =>	$vendorgl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$tlandingcost,
            		'remarks' 	=>	'Vendor payble aginst purchase',
            		'entryby' 	=>	$usr,
            		'entrydate' 	=>	$vouchdt
            );           	    
            	   
            		insertGl($glmstArr,$gldetailArr);
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errorFlag == 0) {
        header("Location: ".$hostpath."/purchase_dataformList.php?res=1&mod=12&msg=".$err);
            
    } else {
        
        $err="Error:" . $conn->error;
        header("Location: ".$hostpath."/purchase_dataformList.php?res=2&mod=12&msg=".$err);
    }
    
    $conn->close();
}
?>