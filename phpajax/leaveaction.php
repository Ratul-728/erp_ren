<?php
require_once("../common/conn.php");

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('../common/phpmailer/PHPMailerAutoload.php');


$dec = $_REQUEST["dec"];
$leavecomment = $_REQUEST["leavecomment"];
$atid = $_REQUEST["actleaveid"];

$qryGet = "SELECT st FROM `leave` WHERE id = ".$atid;
$resultGet = $conn->query($qryGet);
while($rowGet = $resultGet->fetch_assoc()){
    $getSt = $rowGet["st"];
}
if($getSt == 1){
    $columnaction = "reliveraction";
    $columncomment = "relivercomments";
    $columndate = "releveddate";
    $st = 2;
    
}else if($getSt == 2){
    $columnaction = "approveraction";
    $columncomment = "approvercoments";
    $columndate = "approvedate";
    $st = 3;
}
    
if($dec == 0){
    $st = 0;
}

$qry = "UPDATE `leave` SET $columnaction = ".$dec.", $columncomment='".$leavecomment."', $columndate =sysdate(),`st`= '$st' WHERE  id = ".$atid;
//echo $qry;die;
if($conn->query($qry) == TRUE){
    
    //Mail
    $qrymailinfo = "SELECT concat(emp1.`firstname`, ' ', emp1.`lastname`) alname, concat(emp.`firstname`, ' ', emp.`lastname`) repname, emp1.`office_email` 
                    FROM `hr` a LEFT JOIN `employee` emp ON a.`emp_id` = emp.`employeecode`, `hr` b LEFT JOIN `employee` emp1 ON b.`emp_id` = emp1.`employeecode`, 
                    `leave` lv WHERE a.`id` = lv.`approver` AND b.`id` = lv.`hrid` AND lv.id = ".$atid;
    $resultmailinfo = $conn->query($qrymailinfo);
    while($rowmi = $resultmailinfo->fetch_assoc()){
        $name_from = $rowmi["repname"];
        $name_to = $rowmi["alname"];
    	$email_to = $rowmi["office_email"];
    	
    	if($dec == 1){
            $msg = "accepted";
        }else{
            $msg = "declined";
        }
    	
    	$message = "Dear $name_to,<br>
    	            $name_from ".$msg." your leave request.(DEMO TEXT)";
    	$subject = 'Request for leave';
    	//sendBitFlowMail($name_to,$email_to, $subject,$message);
    }
    
    
    echo "Successfully Update!";
}else{
    echo "Something went Wrong";
}

?>