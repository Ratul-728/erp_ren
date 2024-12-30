<?php
	//$link = mysql_connect($dbhost, $dbuser, $dbpassword) ;
	//
	 
	 //$link = mysqli_connect('localhost', 'user', 'password', 'dbname');
	 //$link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

	 $link = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);
?>
