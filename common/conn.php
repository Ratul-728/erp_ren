<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

date_default_timezone_set('Asia/Dhaka');

$rootpath = $_SERVER['DOCUMENT_ROOT'];  
$hostpath = 'https://rdlerp1.bithut.biz';

// $db_name="bithut_rdlerp"; 
//$db_name="bithut_bitflowstaging";
$db_name="u497252501_rdlproduction";



 
$mysql_username="u497252501_rdldbusr";
$mysql_password="3+KfoVd4N^";

$server_name="localhost";
//$server_name="143.95.233.87";
$conn=mysqli_connect($server_name,$mysql_username,$mysql_password,$db_name);

$phpdate = date("Y-m-d H:i:s");
$date = date("Y-m-d H:i:s");
$time = date("H:i:s");
/* setting for rak_framework */


		$link = $conn;
		$dbhost	=	$server_name;
		$dbname	=	$db_name;
		$dbuser	=	$mysql_username;
		$dbpassword = $mysql_password;	
		
		
		
		 $colors = array(
	'#00abe3',
	'#27AAE1',
	'#47B7DE',
	'#72C6DC',
	'#97CEDA',
	'#75ACB4',
	'#567F84',
	'#76B6E3',
	'#58AADF',
	'#2E98D5',
	'#00a8a8',
	'#5252ff',
	'#00d100',
	'#d14d4d',
	'#ad9300',
	'#a024ff',
 );





?>