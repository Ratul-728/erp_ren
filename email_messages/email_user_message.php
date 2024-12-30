<?php

function sendBitFlowMail($name,$email,$subject, $message)
{
	
	global $rootpath, $httpPath, $systemEmailler, $systemEmaillerName, $MailHostAddress, $MailHostUserName, $MailHostPassword, $MailHostPort, $siteTitle ;

	//print_r($concern);
	$path = dirname($_SERVER['PHP_SELF']);
	
	$msg = '';
	$mailsubject = $subject;
	/*
	$message = '
	Dear Admin,<br><br>
	Following message is sent by :'.$name.'
	<br><br>
	------------------------<br>
	Login ID: '.$name.'<br>
	Password: '.$email.'<br>
	Message:<br> '.$message.'<br>	 
	------------------------ 
	<br><br>
	Bimbear System.
';
	*/			





	

	$altMessage = maillerAltMsg($message);
	

	
	//echo $message; die;

	

	
//require_once($hostfolderpath.'/common/phpmailer/PHPMailerAutoload.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

//$body             = file_get_contents('contents.php?name='.$name);
//$body             = eregi_replace("[\]",'',$body);


$mail->IsSMTP(); // telling the class to use SMTP

$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = $MailHostAddress;  // sets the SMTP server
$mail->Port       = $MailHostPort;     // set the SMTP port for the GMAIL server
$mail->Username   = $MailHostUserName; // SMTP account username
$mail->Password   = $MailHostPassword;     // SMTP account password


	
	

$mail->SetFrom($systemEmailler, $systemEmaillerName);

$mail->AddReplyTo($systemEmailler, $systemEmaillerName);

$mail->Subject    = $mailsubject;

$mail->AltBody    = $altMessage; // optional, comment out and test

$mail->MsgHTML($message);

$mail->AddAddress($email, $name);


		
		
		
		
		//echo $value.'<br>';
		
		if(!$mail->Send())
		{
			return false;
		}
		else
		{
			return true;
		}

		
	unset($mail);
	
	


	


}

function sendBitFlowMailArray($name = array(), $emails = array(), $subject, $message, $ccEmails = array())
{
    global $rootpath, $httpPath, $systemEmailler, $systemEmaillerName, $MailHostAddress, $MailHostUserName, $MailHostPassword, $MailHostPort, $siteTitle;

    $path = dirname($_SERVER['PHP_SELF']);
    
    $msg = '';
    $mailsubject = $subject;
    
    $mail = new PHPMailer();
    
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->Host = $MailHostAddress;
    $mail->Port = $MailHostPort;
    $mail->Username = $MailHostUserName;
    $mail->Password = $MailHostPassword;
    
    $mail->SetFrom($systemEmailler, $systemEmaillerName);
    $mail->AddReplyTo($systemEmailler, $systemEmaillerName);
    
    $mail->Subject = $mailsubject;
    
    // Loop through each recipient
    if (is_array($emails)) {
        $count = count($emails);
        for ($i = 0; $i < $count; $i++) {
            // Replace placeholder with recipient name
            $personalizedMessage = makeMailBody($message, $name[$i]);
            $altMessage = maillerAltMsg($personalizedMessage);
    
            // Set the message and AltBody for this recipient
            $mail->MsgHTML($personalizedMessage);
            $mail->AltBody = $altMessage;
            
            $mail->AddAddress($emails[$i], $name[$i]);
            
            // Add CC recipients
            foreach ($ccEmails as $ccEmail) {
                $mail->AddCC($ccEmail);
            }
    
            // Send to each recipient individually
            if (!$mail->Send()) {
                return false; // you can also collect errors here if needed
            }
            $mail->ClearAddresses(); // Clear addresses after each send
        }
    } else {
        // If only one recipient
        $personalizedMessage = makeMailBody($message, $name[$i]);
        $altMessage = maillerAltMsg($personalizedMessage);
    
        $mail->MsgHTML($personalizedMessage);
        $mail->AltBody = $altMessage;
        $mail->AddAddress($emails, $name);
        
        // Add CC recipients
        foreach ($ccEmails as $ccEmail) {
            $mail->AddCC($ccEmail);
        }
    
        if (!$mail->Send()) {
            return false;
        }
    }

    unset($mail);
}



function maillerAltMsg($message){
	$altMessage = str_replace("<br>", "\n", $message);
	$altMessage = str_replace("<b>", "", $altMessage);
	$altMessage = str_replace("</b>", "", $altMessage);
	return $altMessage;
}

function makeMailBody($msg, $name){
    $msg = "Dear $name,<br>
            $msg                        
            <br>Thanks,<br>
            ". $_SESSION["comname"]."<br>
            ";
    
    return $msg;
}
?>