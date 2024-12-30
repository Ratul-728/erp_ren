<?php

session_start();
require "../common/conn.php";
require_once("../rak_framework/fetch.php");
require_once("../rak_framework/edit.php");

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('../common/phpmailer/PHPMailerAutoload.php');

$usr=$_SESSION["user"];
extract($_POST);
//print_r($_POST);die;

/*Array
(
    [chpassed_qa-chk] => 1
    [pass_qtys] => 2
    [defacts_qtys] => 0
    [repair] => 0
    [damaged_qtys] => 0
    [qawId] => 613
    [qaId] => 595
    [type] => 4
    [orderId] => 2
    [remarks] => 
)*/
$qryDef="SELECT qaw.pass_qty, q.product_id, q.type, qaw.defect_qty, qaw.damaged_qty, qaw.`warehouse_id` FROM qa_warehouse qaw LEFT JOIN qa q ON q.id=qaw.qa_id WHERE qaw.id = ".$qawId;
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
    
    $product = $rowDef["product_id"];
    $warehouse = $rowDef["warehouse_id"];
    $type = 6;
}



$qryUpdateQaw = "UPDATE `qa_warehouse` SET `pass_qty`='".$pass_qtys."',`defect_qty`='".$defacts_qtys."',`damaged_qty`='".$damaged_qtys."',`inspector`='".$usr."',
                `date_inspected`=sysdate() WHERE id = ".$qawId;
// echo $qryUpdateQaw;die;
$conn->query($qryUpdateQaw);

$qryUpdateQa = "UPDATE `qa` SET `status`='2', `remarks`='".$remarks."', date_iniciated = '".date('Y-m-d H:i:s')."' WHERE id = ".$qaId;
$conn->query($qryUpdateQa);

//Check all completed or not 
$flag = true;

$qryQa = "SELECT
    CASE
        WHEN qw.ordered_qty = COALESCE(SUM(qw.pass_qty), 0) + COALESCE(SUM(qw.damaged_qty), 0) + COALESCE(SUM(qw.defect_qty), 0) THEN '1'
        ELSE '0'
    END AS qcheck
FROM
    qa_warehouse qw JOIN qa q ON qw.qa_id = q.id WHERE q.product_id = $product and q.type = $type and q.order_id='".$orderId."' GROUP BY q.id, q.order_id, qw.ordered_qty";
$resultQa = $conn->query($qryQa);
while ($rowQa = $resultQa->fetch_assoc()) {
    if($rowQa["qcheck"] == '0'){
        $flag = false;
        break;
    }
}

if($flag){
    $qryUpdatePo = "UPDATE `qa` SET `status`='3' WHERE product_id = $product and type = $type and `order_id`= '".$orderId."'";
    $conn->query($qryUpdatePo);
}

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

// if($defQty > 0){

//         $qryUpdateTo = "UPDATE `chalanstock` SET `freeqty`= (freeqty + $defQty) WHERE product = '$product' and storerome = ".$warehouse;
// 		if($conn->query($qryUpdateTo)){
		    
// 		    $qryUpdateToStock = "UPDATE `stock` SET `freeqty`= (freeqty + $defQty) WHERE `product` = '$product'";
//     		if($conn->query($qryUpdateToStock)){
    		    
//     		    $msg ='Stock transfer successful';
//     		}else{
//     		    $msg ='Something went worng';
//     		}
    		
// 		    $msg ='Stock transfer successful';
// 		}else{
// 		    $msg ='Something went worng';
// 		}
		
// }

echo "Successful";die;
?>