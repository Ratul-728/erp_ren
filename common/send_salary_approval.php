<?php
require_once("conn.php");
include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

session_start();
$usr = $_SESSION["user"];

$month = $_GET["month"];
$year = $_GET["year"];

$qry = "INSERT INTO `approval_salary`( `month`, `year`, `makeby`, `makedt`) 
            VALUES ('$month','$year','$usr',sysdate())";

if ($conn->query($qry) === TRUE) {
    $err="Send for apprval successful";
    
    //Mail to Management
        $qrymail = "SELECT id,active FROM `email` WHERE id = 43";
        $resultmail = $conn->query($qrymail);
        while($rowmail = $resultmail->fetch_assoc())
        {
            $monthName = date('F', mktime(0, 0, 0, $month, 1));
            
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
                $mailsubject = "Approval Required for Salary";
                $message = "An approval request for salary $monthName, $year was received.";
                            
                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
            }
        }
        
     header("Location: ".$hostpath."/rpt_salary_sheet.php?res=1&msg=".$err."&mod=4");
} else {
     $err="Something Went Wrong";
     header("Location: ".$hostpath."/rpt_salary_sheet.php?res=2&msg=".$err."&mod=4");
}

?>