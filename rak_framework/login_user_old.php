<?php
$link = mysql_connect($dbhost, $dbuser, $dbpassword) ;
function usrLogin($UserName,$Password,&$msg)
{

			 global $dbname, $link;
			 $Password2 = $Password;
			 $Password =  $Password;
			 

			 $cntStr = 'SELECT count(*) FROM cv';

			 $cntResult = mysql_query($cntStr);
			 $cntResult = mysqli_fetch_array($cntResult);
			// $cntResult[0];

			 
			 
			 
			 $str	= 'SELECT * FROM `cv` WHERE userid="'.$UserName.'" AND password="'.$Password.'"';
			 //echo $str;
			 $result = mysqli_query($link,  $str);
			 //or die(mysqli_error($link));
			 $record = mysqli_num_rows($result);
			 
		   			 
			 if($record > 0)
			 {
						   session_register("USER");
						   session_register("PASSWORD"); 
						   session_register("LOGGEDIN"); 
						   $_SESSION['USER'] = $UserName;
						   $_SESSION['PASSWORD'] = $Password; 
						   $_SESSION['LOGGEDIN'] = 1;
						   header('Location: userpanel.php');
			  }	
			else
			{
			$msg = '<B><span  class="message"><font color=red>User name "'.$UserName.'" not found</font></span></b>';
			}

}
?>