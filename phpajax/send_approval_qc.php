<?php
require "../common/conn.php";

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('../common/phpmailer/PHPMailerAutoload.php');


session_start();

$user = $_SESSION["user"];

$invid = $_GET['invid'];

$qryCh = "SELECT * FROM `approval_qc` WHERE `invid` = ".$invid;
$resultCh = $conn->query($qryCh);
if ($resultCh->num_rows > 0){
    $msg = "Approval already send for this QC";
    header("Location: ".$hostpath."/invoiceList.php?res=1&msg=$msg&mod=7");
}else{
    $qryInsert = "INSERT INTO `approval_qc`(`invid`, `makeby`, `makedt`) 
                    VALUES ('$invid','$user',sysdate())";
    if($conn->query($qryInsert) == true){
        $msg = "Approval successfully send for QC";
        
        //Mail to Management
            $qrymail = "SELECT id,active FROM `email` WHERE id = 38";
            $resultmail = $conn->query($qrymail);
            while($rowmail = $resultmail->fetch_assoc())
            {
                $active = $rowmail["active"];
                $emailid = $rowmail["id"];
                if($active == 1)
                {
                    $recipientNames = array();
                    $recipientEmails = array();
                    $ccEmails = array();
                    $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                            FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                    $resultSendTo = $conn->query($qrySendTo);
                    while($rowst = $resultSendTo->fetch_assoc())
                    {
                        $recipientNames[] = $rowst["empname"];
                        if($rowst["type"] == 1 && $rowst["office_email"] != "")
                        {
                            $recipientEmails[] = $rowst["office_email"];
                        }
                        else if($rowst["type"] == 2 && $rowst["office_email"] != "")
                        {
                            $ccEmails[] = $rowst["office_email"];
                        }
                    }
                    $mailsubject = "Approval Required for QC without Payment";
                    $message = "An approval request for QC without payment for $quotation was received.";
                            
                    sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                }
            }
    }else{
        $msg = "Something went wrong";
    }
    header("Location: ".$hostpath."/invoiceList.php?res=1&msg=$msg&mod=7");
}
?>