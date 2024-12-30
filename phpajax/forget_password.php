<?php
require_once("../common/conn.php");

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');

$frgtmail = $_REQUEST["frgtmail"];

if($frgtmail == ''){
    echo "Please provide your mail address";
    die;
}

$qry = "SELECT b.resourse_id rsi, b.hidden_char hdc, concat(a.`firstname`, ' ', a.`lastname`) as name FROM `employee` a LEFT JOIN `hr` b ON a.`employeecode` = b.emp_id 
                    WHERE ( a.`office_email` = '".$frgtmail."' or a.`pers_email` = '".$frgtmail."' or b.email = '".$frgtmail."' )";
$result = $conn->query($qry);
if ($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $name = $row["name"];
        $username = $row["rsi"];
        $code = $row["hdc"];
        
        
        //Send mail
        $mailsubject = "Forget Password!!!";
        $message = "Dear $name,
                    Your username: $username
                    Your password: $code
                    From Renaissance Team";
        
        mail($frgtmail,$mailsubject,$message);
        //sendBitFlowMail($name,$frgtmail, $mailsubject,$message);
        
        echo "We have sent you an email with your username and password.";die;
    }
}else{
    echo "Email address not found!!!";
}

?>