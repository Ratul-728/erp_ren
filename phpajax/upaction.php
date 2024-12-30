<?php
require_once("../common/conn.php");

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('../common/phpmailer/PHPMailerAutoload.php');

session_start();

$user = $_SESSION["user"];

$val = $_POST["val"];
$atid = $_POST["id"];

if($val == 3 ||$val == 4 ||$val == 5 ){
    echo $atid;die;
}

$qrymail = "SELECT fst.stausnm fromnm, tost.stausnm tonm, hr.hrName hrName, reby.hrName rebyname, emp.office_email, isst.tikcketno, isst.sub, isst.issuedetails 
            FROM `issueticket` isst LEFT JOIN `issuestatus` fst ON fst.id = isst.status LEFT JOIN `issuestatus` tost ON tost.id = $val LEFT JOIN `hr` hr ON hr.id = isst.makeby 
            LEFT JOIN `employee` emp ON emp.`employeecode` = hr.emp_id LEFT JOIN hr reby ON reby.id = $user WHERE isst.id = ".$atid;
$resultmail = $conn->query($qrymail);
//echo $qrymail;die;
while($rowmail = $resultmail->fetch_assoc()){
    $name_to = $rowmail["hrName"];
    $email_to = $rowmail["office_email"];
    $tonm = $rowmail["tonm"];
    $tcktid = $rowmail["tikcketno"];
    $subject = $rowmail["sub"];
    $issue = $rowmail["issuedetails"];
              
    $fromnm = $rowmail["fromnm"];
    
    $reby = $rowmail["rebyname"];
              
}

$qry = "UPDATE `issueticket` SET `status`= $val, `probabledate` = sysdate() WHERE id = ".$atid;
if($conn->query($qry) == TRUE){
          
        $mailsubject = "Issue Update $subject: #$tcktid";

        $message = "<b>Dear $name_to,</b><br>
                Status of your task has been changed from $fromnm to $tonm by $reby.<br>
                <br><b>Title: $subject. </b><br><br>
                <b>Description:</b><br> $issue. <br><br>
                Kindly review it from your profile.<br>
                <br><b>Thanks,<br>
                Bitflow System</b><br>
                ";
                
        //echo $email_to;die;        
                
    	
    	sendBitFlowMail($name_to,$email_to, $mailsubject,$message);
    	
    	
        echo "Successfully Update!";
}else{
    echo "Something went Wrong";
}
?>