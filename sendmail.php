
<?php
		ini_set('display_errors', 1);
		error_reporting(E_ALL);

		include_once('common/conn.php');

		
		include_once('common/email_config.php');
		

        
		include_once('email_messages/email_user_message.php');
		
        require_once('common/phpmailer/PHPMailerAutoload.php');

		$name_to = 'Raihan';
		$email_to = 's.m.alam@northsouth.edu';
		$message = 'What ever you put here as html or plain text';
		$subject = 'Test Email';

		if(sendBitFlowMail($name_to,$email_to, $subject,$message)){
			echo 'Mail sent successful';
		}else{
			echo 'Something went wrong!!!';
		}

?>
