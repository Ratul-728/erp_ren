<?php
/**
 * ----------------------------------------------
 * RAK FRAMEWORK
 * Version 2
 * Last Updated: 25th December, 2013
 * Developer: Md. Abul Kashem (Raihan)
 * Email: raihan@rakplanet.com
 * ----------------------------------------------
 */
 //print_r($_SESSION);
if($_SESSION['USER'] && $_SESSION['LOGIN_TYPE'] == 'ADMIN')
{
			global $dbname, $link;
			 
			 
			 
			$str = 'SELECT * FROM adm_user WHERE '.$_SESSION['USER_KEY'].'="'.$_SESSION['USER'].'" AND '.$_SESSION['PASSWORD_KEY'].'="'.$_SESSION['PASSWORD'].'"';
			
			 //echo $str;
			 
			 $result = mysqli_query($link,  $str)
			 or die(mysqli_error($link));

			
			 if(mysqli_num_rows($result) < 1 && $_SESSION['FIRST_LOGIN'] != 1)
			 {
				session_unset();
				header('location: index.php?'.$_SESSION['FIRST_LOGIN'].'');
			 }

}
else
{
header("location: index.php");
}?>