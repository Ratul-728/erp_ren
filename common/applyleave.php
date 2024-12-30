<?php
require "conn.php";
require "../rak_framework/fetch.php";
include_once('./email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

session_start();

//  ini_set('display_errors', 1);
//  error_reporting(E_ALL);

include_once('./email_config.php');
include_once('../email_messages/email_user_message.php');

require_once('phpmailer/PHPMailerAutoload.php');

$user = $_SESSION["user"];
$usr = $_SESSION["user"];
$type = $_GET["type"];

if($type == 2){
    $user = $_POST["cmbempnm"];
}

$leavetype = $_REQUEST["leavetype"];  
if($leavetype == 0){
    $err = 'Please select a leave type';
    if($type == 2){
        header("Location: ".$hostpath."/leave_hr.php?mod=4&msg=".$err."");
    }else{
        header("Location: ".$hostpath."/hrqv.php?res=4&msg=".$err."");
    }
    die;
}
$reliver = $_REQUEST["reliver"];
if($reliver == 0){
    $err = 'Please select a Reliver';
    if($type == 2){
        header("Location: ".$hostpath."/leave_hr.php?mod=4&msg=".$err."");
    }else{
        header("Location: ".$hostpath."/hrqv.php?res=4&msg=".$err."");
    }
    die;
}
$startdt = $_REQUEST["startdt"];  $startdt = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$startdt);
$enddt = $_REQUEST["enddt"];  $enddt = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$enddt);

$address = $_REQUEST["address"];
$contact = $_REQUEST["contactno"];
$details = $_REQUEST["w3review"];
$sttime = $startdt;

$qrych = '(1 = 0 ';

while($sttime <= $enddt){
    $qrych .= " or '$sttime' BETWEEN `startday` AND COALESCE(`endday`, NOW())";
    $sttime = date('Y-m-d', strtotime($sttime . ' +1 day'));
}
$qrych .= " )";

$qryvalidation = "SELECT `id` FROM `leave` WHERE `st` = 4 and hrid = $user and ".$qrych;
$resultvalidation = $conn->query($qryvalidation);
if ($resultvalidation->num_rows > 0) {
    $err = "Duplicate date found in your applied date";
    if($type == 2){
        header("Location: ".$hostpath."/leave_hr.php?mod=4&msg=".$err."");
    }else{
        header("Location: ".$hostpath."/hrqv.php?res=4&msg=".$err."");
    }
    die;
}

//Total days available or not
$start_timestamp = strtotime($startdt);
$end_timestamp = strtotime($enddt);

$difference = $end_timestamp - $start_timestamp;

$total_days = round($difference / (60 * 60 * 24)) + 1;

if ($total_days === 0 && $start_timestamp !== $end_timestamp) {
    $total_days = 1;
}

$dayavail = 0;
$qrygettotal = "SELECT `remaining_days` FROM `leave_available` WHERE `hrid` = $user AND YEAR(`year`) = YEAR(CURDATE()) AND leave_type = $leavetype";
$resultgettotal = $conn->query($qrygettotal);
while($rowmgt = $resultgettotal->fetch_assoc()){
    $dayavail = $rowmgt["remaining_days"];
}


if($total_days > $dayavail){
    $err = "You do not have enough leave";
    if($type == 2){
        header("Location: ".$hostpath."/leave_hr.php?mod=4&msg=".$err."");
    }else{
        header("Location: ".$hostpath."/hrqv.php?res=4&msg=".$err."");
    }
    die;
}

$qryGetReportto= "SELECT h2.id FROM employee emp LEFT JOIN hr h ON h.emp_id=emp.employeecode 
                    LEFT JOIN department dept ON dept.id=emp.department Left join employee e on dept.head=e.id left join hr h2 on h2.emp_id=e.employeecode 
                    WHERE h.id= ".$user;
$resultGetRpoertto = $conn->query($qryGetReportto);
while($rowGetReportTo = $resultGetRpoertto->fetch_assoc()){
    $leavereportto = $rowGetReportTo["id"];
}


$qry="INSERT INTO `leave`(`hrid`, `leavetype`, `applieddate`, `startday`, `endday`, `details`, `reliver`,`approver`, `contact`,`address`) 
                            VALUES (".$user.",".$leavetype.",sysdate(),'".$startdt."','".$enddt."','".$details."',".$reliver.",".$leavereportto.",'".$contact."','".$address."')";
if ($conn->query($qry) == TRUE) {
    $leaveid = $conn->insert_id;
    /*Announcemnet
    $qryapp = "SELECT `hrName` FROM `hr` where id = ".$user;
    $resultapp = $conn->query($qryapp);
    $rowpp = $resultapp->fetch_assoc();
    $appliedname = $rowapp["hrName"];
    
    $anntitle = $appliedname." applied for leave";
    $announceno = date("Ymdhis");
    $announce = $appliedname." applied for leave"; */
    
    //Upload Image
    
    $file_count = count($_FILES['uploaddocument']['name']);
    $uploaded_files_count = 0;
    
    for ($i = 0; $i < $file_count; $i++) {
        if ($_FILES['uploaddocument']['error'][$i] === UPLOAD_ERR_OK) {
            // File was uploaded successfully
            $uploaded_files_count++;
        }
    }

        for ($i = 0; $i < $uploaded_files_count; $i++) {
                $fileType = $_FILES['uploaddocument']['type'][$i];
                $fileSize = $_FILES['uploaddocument']['size'][$i];
                $allowedTypes = array('application/pdf', 'image/jpeg', 'image/png');
                $maxSize = 5 * 1024 * 1024; // 5MB
                $uploadDir = 'upload/leave_documents/';
                
                // Check file type
                if (!in_array($fileType, $allowedTypes)) {
                    $err = 'Error: Only PDF or JPG or PNG files are allowed.';
                    if($type == 2){
                        header("Location: ".$hostpath."/leave_hr.php?mod=4&msg=".$err."");
                    }else{
                        header("Location: ".$hostpath."/hrqv.php?res=4&msg=".$err."");
                    }
                    die;
                }
        
                // Check file size
                if ($fileSize > $maxSize) {
                    $err = 'Error: File size exceeds the limit (5MB).';
                    if($type == 2){
                        header("Location: ".$hostpath."/leave_hr.php?mod=4&msg=".$err."");
                    }else{
                        header("Location: ".$hostpath."/hrqv.php?res=4&msg=".$err."");
                    }
                    die;
                }
                // Using pathinfo to get the file extension
                $file_name_with_extension = $_FILES['uploaddocument']['name'][$i];
                
                $date = date('Ymd');
                $filename = $user . $date.'.'.$file_name_with_extension;
        
                // Move the uploaded file to the destination folder
                $uploadedFile = $_FILES['uploaddocument']['tmp_name'][$i];
                $destination = $uploadDir . $filename;
        
                if (move_uploaded_file($uploadedFile, $destination)) {
                    $qryupload = "INSERT INTO `leave_documents`( `leaveid`, `image`) VALUES ('$leaveid','$filename')";
                    $conn->query($qryupload);
                    //return $destination;
                    
                } else {
                    $err = 'Error: Failed to upload the file.';
                    if($type == 2){
                        header("Location: ".$hostpath."/leave_hr.php?mod=4&msg=".$err."");
                    }else{
                        header("Location: ".$hostpath."/hrqv.php?res=4&msg=".$err."");
                    }
                    die;
                }
            }
    
    //Announcement
    $empname = $_SESSION["empname"];
    $msg = $empname." applied for a leave from $startdt to $enddt";
    
    //To reliver
    $qryreliverann = "INSERT INTO `announce`( `announcedt`, `title`, `catagory`, `employee`, `announce`, `makeby`, `makedt`)
                                VALUES (sysdate(),'$msg','3','$reliver','$msg','$usr',sysdate())";
    $conn->query($qryreliverann);
    
    //To Approver
    $qryapproverann = "INSERT INTO `announce`( `announcedt`, `title`, `catagory`, `employee`, `announce`, `makeby`, `makedt`)
                                VALUES (sysdate(),'$msg','3','$leavereportto','$msg','$usr',sysdate())";
    $conn->query($qryapproverann);
    //Send Mail
    
    $qrymailinfo = "SELECT concat(emp1.`firstname`, ' ', emp1.`lastname`) alname, concat(emp.`firstname`, ' ', emp.`lastname`) repname, emp.`office_email` 
                    FROM `hr` a LEFT JOIN `employee` emp ON a.`emp_id` = emp.`employeecode`, `hr` b  
                    LEFT JOIN `employee` emp1 ON b.`emp_id` = emp1.`employeecode` WHERE a.`id` = ".$leavereportto." AND b.`id` = ".$user;
    $resultmailinfo = $conn->query($qrymailinfo);
    while($rowmi = $resultmailinfo->fetch_assoc()){
        $name_to = $rowmi["repname"];
        $name_from = $rowmi["alname"];
    	$email_to = $rowmi["office_email"];
    	$message = "Dear $name_to,<br>
    	            $name_from applied a leave request to you.(DEMO TEXT)";
    	$subject = 'Request for leave';
    	//sendBitFlowMail($name_to,$email_to, $subject,$message);
    }
    
    
    //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = 53";
                    $resultmail = $conn->query($qrymail);
                    while($rowmail = $resultmail->fetch_assoc())
                    {
                        $active = $rowmail["active"];
                        $emailid = $rowmail["id"];
                        if($active == 1)
                        {
                            //Reliever Mail
                            $qrymailinfo = "SELECT concat(emp.firstname, ' ', emp.lastname) empnp, emp.office_email 
                                            FROM hr h LEFT JOIN employee emp ON emp.employeecode=h.emp_id WHERE h.id = ".$reliver;
                            $resultmailinfo = $conn->query($qrymailinfo);
                            while($rowmi = $resultmailinfo->fetch_assoc()){
                                $name_to = $rowmi["empnp"];
                            	$email_to = $rowmi["office_email"];
                            }
                            
                            $recipientNames = array();
                            $recipientEmails = array();
                            $ccEmails = array();
                            $recipientEmails[] = $email_to;
                            $recipientNames[] = $name_to;
                            
                            $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                                    FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                            $resultSendTo = $conn->query($qrySendTo);
                            while($rowst = $resultSendTo->fetch_assoc())
                            {
                                if($rowst["type"] == 2 && $rowst["office_email"] != "")
                                {
                                    $ccEmails[] = $rowst["office_email"];
                                }
                            }
                            $mailsubject = "Leave request Received";
                            $message = "$empname has submitted a leave request. Please proceed with the approval process.";
                                    
                            sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                        }
                    }
    
    $err = "Successfully Apply your leave request";
    if($type == 2){
        header("Location: ".$hostpath."/leave_hr.php?mod=4&msg=".$err."");
    }
    else{
        header("Location: ".$hostpath."/hrqv.php?res=2&msg=".$err."");
    }
}else{
    $err = "Something went wrong";
    if($type == 2){
        header("Location: ".$hostpath."/leave_hr.php?mod=4&msg=".$err."");
    } else{
        header("Location: ".$hostpath."/hrqv.php?res=2&msg=".$err."");
    }
}

?>