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
 
// print_r($_SESSION);

if(($_SESSION['NUMBER_OF_ADMIN'] == 3 && count($_SESSION['USER']) == 3)){
	
	global $dbname, $link;
	
	
	for($i=1; $i<=3; $i++){
		
		//echo $_SESSION['USER'][$i].'<br>';
		
		/* start multiple user session checking logic*/
		
		$str = 'SELECT * FROM  '.$_SESSION['TABLE'].'  WHERE '.$_SESSION['USER_KEY'].'="'.$_SESSION['USER'][$i].'" AND '.$_SESSION['PASSWORD_KEY'].'="'.$_SESSION['PASSWORD'][$i].'"';
		//echo $str.'<br>';
		
		$result = mysqli_query($link,  $str)
		or die(mysqli_error($link));
		
		 if(mysqli_num_rows($result) < 1)
		 {
			session_unset();
			header('location: index.php?'.$_SESSION['FIRST_LOGIN'].'');
		 }	
	
		/* end multiple user session checking logic*/
	} 



}else{
	//session_unset();
	header("location: index.php?action=login");
}

?>