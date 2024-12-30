<?php

session_start();
require "../common/conn.php";

include_once('../rak_framework/fetch.php');
include_once('../rak_framework/connection.php');
//include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
require_once('../common/insert_gl.php');

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('../common/phpmailer/PHPMailerAutoload.php');

$usr=$_SESSION["user"];
//print_r($_POST);die;
extract($_POST);

$qryDef="SELECT pass_qty, defect_qty, damaged_qty FROM qa_warehouse WHERE id = ".$qawId;
$resultDef = $conn->query($qryDef);
while ($rowDef = $resultDef->fetch_assoc())
{
    if($rowDef["pass_qty"]== null)
    {
        $rowDef["pass_qty"] = 0;
    }
    if($rowDef["defect_qty"]== null)
    {
        $rowDef["defect_qty"] = 0;
    }
    if($rowDef["damaged_qty"]== null)
    {
        $rowDef["damaged_qty"] = 0;
    }
    $defQty = $pass_qtys-$rowDef["pass_qty"];
    $defDefQty = $defacts_qtys-$rowDef["defect_qty"];
    $defDamQty = $damaged_qtys-$rowDef["damaged_qty"];
}
//echo $defQty;die;
$qryUpdateQaw = "UPDATE `qa_warehouse` SET `pass_qty`='".$pass_qtys."',`defect_qty`='".$defacts_qtys."',`damaged_qty`='".$damaged_qtys."',`inspector`='".$usr."',
                `date_inspected`=sysdate() WHERE id = ".$qawId;
// echo $qryUpdateQaw;die;
$conn->query($qryUpdateQaw);

$qryUpdateQa = "UPDATE `qa` SET `status`='2', `remarks`='".$remarks."', date_iniciated = '".date('Y-m-d H:i:s')."' WHERE id = ".$qaId;
$conn->query($qryUpdateQa);

//Approval for Defact and Damaged qty
if($defDefQty > 0){
    $qryCh = "SELECT * FROM `approval_defect` WHERE st = 1 and `qaw_id` = ".$qawId;
    $resultCh = $conn->query($qryCh);
    if ($resultCh->num_rows > 0){
        $qryDef = "UPDATE `approval_defect` SET `qty`= qty+$defDefQty WHERE `qaw_id` = ".$qawId;
    }else{
        $qryDef = "INSERT INTO `approval_defect`(`qaw_id`, `qty`, `makeby`, `makedt`) VALUES ('$qawId','$defDefQty', '$usr', sysdate())";
    }
    $conn->query($qryDef);
    
    //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = 50";
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
                               
                               if($type == 4){
                                   $qrygetts = "SELECT toid FROM transfer_stock WHERE id = ".$orderId;
                                   $resultgetts = $conn->query($qrygetts);
                                    while($rowgetts = $resultgetts->fetch_assoc()){
                                        $trid = $rowgetts["toid"];
                                    }
                               }else{
                                   $trid = $orderId;
                               }
                                $mailsubject = "New Repair product needs approval";
                                $message = "New repair product transfer request ($trid) is waiting for approval.";
                                        
                                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                            }
                        }
                      }
}
if($defDamQty > 0){
    $qryCh = "SELECT * FROM `approval_damaged` WHERE st = 1 and `qaw_id` = ".$qawId;
    $resultCh = $conn->query($qryCh);
    if ($resultCh->num_rows > 0){
        $qryDam = "UPDATE `approval_damaged` SET `qty`= qty+$defDamQty WHERE `qaw_id` = ".$qawId;
    }else{
        $qryDam = "INSERT INTO `approval_damaged`(`qaw_id`, `qty`, `makeby`, `makedt`) VALUES ('$qawId','$defDamQty', '$usr', sysdate())";
    }
    $conn->query($qryDam);
    
    //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = 49";
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
                               
                               if($type == 4){
                                   $qrygetts = "SELECT toid FROM transfer_stock WHERE id = ".$orderId;
                                   $resultgetts = $conn->query($qrygetts);
                                    while($rowgetts = $resultgetts->fetch_assoc()){
                                        $trid = $rowgetts["toid"];
                                    }
                               }else{
                                   $trid = $orderId;
                               }
                                $mailsubject = "Damaged product needs approval";
                                $message = "New damaged product transfer request ($trid) is waiting for approval.";
                                        
                                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                            }
                        }
                      } 
}

//Check all completed or not 
$flag = true;

$qryQa = "SELECT
    CASE
        WHEN qw.ordered_qty = COALESCE(SUM(qw.pass_qty), 0) + COALESCE(SUM(qw.damaged_qty), 0) + COALESCE(SUM(qw.defect_qty), 0) THEN '1'
        ELSE '0'
    END AS qcheck
FROM
    qa_warehouse qw JOIN qa q ON qw.qa_id = q.id WHERE	q.order_id='".$orderId."' GROUP BY q.id, q.order_id, qw.ordered_qty";
$resultQa = $conn->query($qryQa);
while ($rowQa = $resultQa->fetch_assoc()) {
    if($rowQa["qcheck"] == '0'){
        $flag = false;
        break;
    }
}

if($flag){
    $qryUpdatePo = "UPDATE `qa` SET `status`='3' WHERE `order_id`= '".$orderId."'";
    $conn->query($qryUpdatePo);
}

//Periodic
if($type == 9){
    $qryQa = "SELECT
        CASE
            WHEN qw.ordered_qty = COALESCE(SUM(qw.pass_qty), 0) + COALESCE(SUM(qw.damaged_qty), 0) + COALESCE(SUM(qw.defect_qty), 0) THEN '1'
            ELSE '0'
        END AS qcheck
        FROM
        qa_warehouse qw JOIN qa q ON qw.qa_id = q.id WHERE	q.id=".$qaId;
    $resultQa = $conn->query($qryQa);
    while ($rowQa = $resultQa->fetch_assoc()) {
        if($rowQa["qcheck"] == '0'){
            $flag = false;
            break;
        }else{
            $qryUpdatePo = "UPDATE `qa` SET `status`='3' WHERE `id`= ".$qaId;
            $conn->query($qryUpdatePo);
        }
    }
}

//echo   $type;die;                          
//Purchase
if($type == 2)
{
   
    $qryUpdatePo = "UPDATE `purchase_landing` SET `st`='2' WHERE poid = '".$orderId."'";
    $conn->query($qryUpdatePo);
    
    if($pass_qtys > 0)
    {
        //Get info
        $qty=$defQty;$tlc=0;
        $qryInfo="SELECT qa.product_id, pl.branch,i.barcode, pli.tot_value,pli.tot_landed_cost FROM `qa_warehouse` qaw LEFT JOIN qa qa ON qa.id=qaw.qa_id 
                LEFT JOIN purchase_landing pl ON qa.order_id=pl.poid LEFT JOIN purchase_landing_item pli ON pli.pu_id=pl.id LEFT JOIN item i ON qa.product_id=i.id 
                WHERE pli.productId=qa.product_id AND qaw.id=".$qawId;
        $resultInfo = $conn->query($qryInfo);
        while($rowInfo = $resultInfo->fetch_assoc())
        {
            $itmmnm=$rowInfo["product_id"];
            $storerm=$rowInfo["branch"];
            $up = $rowInfo["tot_value"];
            $barcode = $rowInfo["barcode"];
            $lc = $rowInfo["tot_landed_cost"];
            $tlc=$tlc+$lc;
        }
        
        //Update parts in item
        $qryUpPro = "UPDATE item SET parts = $parts WHERE id = ".$itmmnm;
        $conn->query($qryUpPro);
        
        $isstock=0;
        //$isstock = fetchByID('stock','product',$itmmnm,'id');
        $isstockqry="SELECT count(*) cnt FROM stock where product= $itmmnm ";
        //echo $isstockqry;die;
        $resstore = $conn->query($isstockqry);
        while($rowstore = $resstore->fetch_assoc())
        {
            $isstock=$rowstore["cnt"];
        }
        // echo $isstock;die;
        
        //echo $isstock;die; 
        if($isstock==0)
        {
            
            $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES($itmmnm,$qty,$up,$barcode,$storerm)";
			 //echo $strQryChalanstock;die;
	        if ($conn->query($strQryChalanstock) == TRUE) {$err="$qty qtn added in chalanstock in main branch"; }
            
            $strQryStock ="INSERT INTO stock( `product`, `freeqty`, `bookqty`, `orderedqty`, `deliveredqty`, repairedqty, `costprice`, `prevprice`) VALUES($itmmnm,$qty,0,0,0,0,$up,0)";
	            if ($conn->query($strQryStock) == TRUE) { $err="$qty qtn added in stock";  }
           
        }
        else
        {
            $qrystore="SELECT count(*) cnt FROM chalanstock where product= $itmmnm and storerome=$storerm";
            $resstore = $conn->query($qrystore);
            while($rowstore = $resstore->fetch_assoc())
            {
                $isstore=$rowstore["cnt"];
            }
            if($isstore==0)
            {
                $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES($itmmnm,$qty,$up,$barcode,$storerm)";
		      // echo $strQryChalanstock;die;
		        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
		        
            }
            else
            {
                
                $strQryChalanstock = "update chalanstock set freeqty=freeqty+$qty,costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) where product=$itmmnm and storerome=$storerm";
		        //echo $strQryChalanstock;die;
		        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
		        
		        
            }
           
            $strQryStock = "update stock set `freeqty`=freeqty+$qty, costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) , `prevprice`=costprice where product= $itmmnm  ";
            if ($conn->query($strQryStock) == TRUE) { $err="$qty qtn added in stock";  }	
            
            $isfuter = fetchByID('stock','product',$itmmnm,'futureqty');
            $shiftqty=0;
            if($isfuter>0)
            {
                if($isfuter<$qty) 
                {
                    $shiftqty=$isfuter;
                    $strQryFutureitem="update item set forstock=1  where id=$itmmnm";
                    $conn->query($strQryFutureitem);
                }
                else
                {
                    $shiftqty=$qty;
                    
                }
                $strQryFutureStock="update stock set futureqty=futureqty-$shiftqty where product=$itmmnm";
                $conn->query($strQryFutureStock);
                
                $strQryFutureStore="update chalanstock set freeqty=freeqty-$shiftqty where product=$itmmnm and storerome=7";
                $conn->query($strQryFutureStore);
                
                $strQryitem="update item set SET `backorderqty`=backorderqty-$shiftqty   where id=$itmmnm";
                $conn->query($strQryitem);
            }
        }
        if($flag){
            $qryUpdatePo = "UPDATE `purchase_landing` SET `st`='3' WHERE `poid`= '".$orderId."'";
            $conn->query($qryUpdatePo);
        }
    }
    
    ///*	
$finishproduct = fetchByID('glmapping','buisness',10,'mappedgl');
$materialintransit = fetchByID('glmapping','buisness',22,'mappedgl');
$vouchdt=date("d/m/Y");
$finshidamount=$qty*$up;

$descr="Add inventory"; 
 $refno=$orderId;
 $glmstArr4 = array(
	'transdt' => $vouchdt,
	'refno' => $refno,
	'remarks' => 'Qa pass',
	'entryby' => $usr,
);


	$gldetailArr4[] = array(
		'sl'	 =>	1,
        'glac'	 =>	$finishproduct,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$finshidamount,
		'remarks' 	=>	'Inventory By QC',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);


	$gldetailArr4[] = array(
		'sl'	 =>	2,
        'glac'	 =>	$materialintransit,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$finshidamount,
		'remarks' 	=>	'Funish Product with QC',
		'entryby' 	=>	$usr,
		'entrydate' 	=>	$vouchdt
);
insertGl($glmstArr4,$gldetailArr4);
//*/
}

//For Return
else if($type == 3){
    if($pass_qtys > 0){
        
        //Get info
        $qty=$defQty;
        
        $qryInfo="SELECT qa.product_id, qaw.warehouse_id, i.rate, i.barcode, quo.organization, sod.otc
                FROM `qa_warehouse` qaw LEFT JOIN qa qa ON qa.id=qaw.qa_id LEFT JOIN item i ON qa.product_id=i.id LEFT JOIN quotation quo ON quo.socode=qa.order_id
                left join soitemdetails sod on (sod.socode = qa.order_id and qa.product_id=sod.productid)
                WHERE qaw.id=".$qawId;//echo $qryInfo;die;
        $resultInfo = $conn->query($qryInfo);
        while($rowInfo = $resultInfo->fetch_assoc())
        {
            $itmmnm=$rowInfo["product_id"];
            $storerm=$rowInfo["warehouse_id"];
            $up = $rowInfo["rate"];
            $barcode = $rowInfo["barcode"];
            $otc = $rowInfo["otc"];
            $org = $rowInfo["organization"];
        }
        
        $isstock=0;
        $isstockqry="SELECT count(*) cnt FROM stock where product= $itmmnm ";
        //echo $isstockqry;die;
        $resstore = $conn->query($isstockqry);
        while($rowstore = $resstore->fetch_assoc())
        {
            $isstock=$rowstore["cnt"];
        }
        // echo $isstock;die;
        
        //echo $isstock;die; 
        if($isstock==0)
        {
            
            $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES($itmmnm,$qty,$up,$barcode,$storerm)";
			 //echo $strQryChalanstock;die;
	        if ($conn->query($strQryChalanstock) == TRUE) {$err="$qty qtn added in chalanstock in main branch"; }
            
            $strQryStock ="INSERT INTO stock( `product`, `freeqty`, `bookqty`, `orderedqty`, `deliveredqty`, repairedqty, `costprice`, `prevprice`) VALUES($itmmnm,$qty,0,0,0,0,$up,0)";
	            if ($conn->query($strQryStock) == TRUE) { $err="$qty qtn added in stock";  }
           
        }
        else
        {
            $qrystore="SELECT count(*) cnt FROM chalanstock where product= $itmmnm and storerome=$storerm";
            $resstore = $conn->query($qrystore);
            while($rowstore = $resstore->fetch_assoc())
            {
                $isstore=$rowstore["cnt"];
            }
            if($isstore==0)
            {
                $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES($itmmnm,$qty,$up,$barcode,$storerm)";
		      // echo $strQryChalanstock;die;
		        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
            }
            else
            {
                $strQryChalanstock = "update chalanstock set freeqty=freeqty+$qty,costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) where product=$itmmnm and storerome=$storerm";
		        //echo $strQryChalanstock;die;
		        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
            }
           
            $strQryStock = "update stock set `freeqty`=freeqty+$qty, costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) , `prevprice`=costprice where product= $itmmnm  ";
            if ($conn->query($strQryStock) == TRUE) { $err="$qty qtn added in stock";  }	
        }
        //Update GRS Stock
        $updateGrsStock = "update chalanstock set `freeqty`=freeqty-$qty where product= $itmmnm  and storerome=6";
            if ($conn->query($updateGrsStock) == TRUE) { $err="$qty qtn updated in GRS stock";  }	
            
        //update Organization Balance
        // $qryBalUpdate = "UPDATE `organization` SET `balance`= `balance`+($otc * $qty) WHERE id=".$org;
        // $conn->query($qryBalUpdate);
        
    }
}

                    if($type != 3 && $type != 2 && $type != 9 && $flag) {
                    //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = 12";
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
                               $mailsubject = "Quality check completed";
    
                                $message = "The Quality check of $orderId is completed. Please process the Delivery Order";
                                        
                                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                            }
                        }
                      } 
                }
 //echo $shiftqty;                     
//echo $strQryFutureStock;
//echo $strQryFutureStore;
//echo $strQryitem;
echo "Successfully Updated!";
?>