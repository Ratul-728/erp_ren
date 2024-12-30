<?php
//Send mail to new registered member with memberinfo
function sendRegInfo($Subject,$FromHeaderTxt,$FromEmail,$Recepient,$MailBody,&$success)
{
	//Configured Items
	global $httpPath, $debug;
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	
	// $WLSId comes with the 'WLS' prefix and ID
	
	
	
	$Body = $MailBody;
	//echo $Body;
	
	/* additional headers */
	$headers .= "From: ".$FromHeaderTxt."<".$FromEmail.">\r\n";
	//$headers .= "Bcc: abul.kashem@egeneration.com.bd\r\n";


	/* and now mail it */
	
	// debug
	if($debug == 1)
	{
		echo '<div style="background:#fff; padding:10px;">';
		echo 'Recepient ='.$Recepient.'<br>';
		echo 'Subject ='.$Subject.'<br>';
		echo 'FromHeaderTxt ='.$FromHeaderTxt.'<br>';
		echo 'FromEmail ='.$FromEmail.'<br><hr>';
		echo 'Body =<br>'.$Body.'<hr><br></div>';
	}	
	 
	
	
	if(@mail($Recepient, $Subject, $Body, $headers))
	{
		//$success = 1;
		return true;
	}
	else
	{
		//$success = 0;
		return false;
	}
}
?>