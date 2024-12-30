<?php
require "../common/conn.php";

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('../common/phpmailer/PHPMailerAutoload.php');

session_start();

$user = $_SESSION["user"];

foreach($_POST["ajxdata"] as $key => $val)
    {
	    $data[$key] = $val;
    }
    
$amount = $data[1]["value"];
$note  = $data[2]["value"];
$orgid  = $data[3]["value"];

$qryCh = "SELECT `balance` FROM `organization` WHERE id = ".$orgid;
$resultCh = $conn->query($qryCh);
while($rowCh = $resultCh->fetch_assoc()){
    if($rowCh["balance"] < $amount){
        $msg = "Insufficient Wallet Balance!";
        echo $msg;die;
        //header("Location: ".$hostpath."/organizationwalletList.php?res=2&msg=$msg&mod=7");
    }
}
    $qryInsert = "INSERT INTO `approval_withdrawal`(`orgid`, `amount`,`note` ,`makeby`, `makedt`)
                    VALUES ('$orgid','$amount','$note','$user',sysdate())";
    if($conn->query($qryInsert) == true){
        $msg = "Approval successfully send for Withdrawal";
        
        //Mail to Management
            $qrymail = "SELECT id,active FROM `email` WHERE id = 39";
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
                    $mailsubject = "Approval Required for Withdrawal";
                    $message = "An approval request for withdrawal for $orgnm organization was received.";
                            
                    sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                }
            }
    }else{
        $msg = "Something went wrong";
    }
    echo $msg;die;
    //header("Location: ".$hostpath."/organizationwalletList.php?res=1&msg=$msg&mod=7");

?>