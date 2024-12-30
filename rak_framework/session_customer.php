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
if($_SESSION['USER'])
{
			global $dbname, $link;
			 
			 
			 
			$str = 'SELECT * FROM  '.$_SESSION['TABLE'].'  WHERE '.$_SESSION['USER_KEY'].'="'.$_SESSION['USER'].'" AND '.$_SESSION['PASSWORD_KEY'].'="'.$_SESSION['PASSWORD'].'" AND is_email_varified = "1"';
			
			 //echo $str;
			 //exit();
			 $result = mysqli_query($link,  $str)
			 or die(mysqli_error($link));

			
			 if(mysqli_num_rows($result) < 1)
			 {
				session_unset();
				header('location: index.php?'.$_SESSION['FIRST_LOGIN'].'');
			 }
			 
			 //check user activation
			 require_once('fetch.php');
			 $is_active = fetchByID('company','email',$_SESSION['USER'],'is_active');
			// echo 'IS ACTIVE: '.$is_active;
			 if($is_active == 0)
			 {
				 $pageArray = array('profile_manager.php','document_manager.php','password_manager.php','notice.php','delete_prdct_picture.php');
				 
				if(!in_array(basename($_SERVER['PHP_SELF']),$pageArray))
				{
					//echo basename($_SERVER['PHP_SELF']);
					header('location: document_manager.php?postaction=edit'.$_SESSION['FIRST_LOGIN'].'');
				}
			}

}
else
{
header("location: index.php?action=login");
}
?>