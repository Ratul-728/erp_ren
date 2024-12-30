<?php
/**
 * ----------------------------------------------
 * RAK FRAMEWORK
 * Version 2
 * Last Updated: Dec09-14
 * Developer: Md. Abul Kashem (Raihan)
 * Email: raihan@rakplanet.com
 * ----------------------------------------------
 */
if($_SESSION['USER'])
{
			global $dbname, $link;
			 
			 
			 


			//$str = 'SELECT * FROM company_info WHERE userid ="'.$_SESSION['USER_ID'].'" AND password="'.$_SESSION['PASSWORD'].'" AND is_validated = "yes" AND is_approved="yes"';
			$str = 'SELECT * FROM '.$_SESSION['TABLE'].' WHERE '.$_SESSION['USER_KEY'].'="'.$_SESSION['USER'].'" AND '.$_SESSION['PASSWORD_KEY'].'="'.$_SESSION['PASSWORD'].'" AND is_validated = "yes" AND is_approved="yes" AND user_type='.$_SESSION['USER_TYPE'].'';
			
			
			

			 //echo $str;
			 $result = mysqli_query($link,  $str)
			 or die(mysqli_error($link));
			
		   	
			 if(mysqli_num_rows($result) < 1)
			 {
				session_unset();
				header('location: '.$httpPath);
			 }

}
else
{
		header("location: $httpPath");
}
?>