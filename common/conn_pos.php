<?php

//error_reporting(E_ALL);

//ini_set('display_errors', 1);





$rootpath = $_SERVER['DOCUMENT_ROOT'].'/qpos';

$hostpath = 'https://bithut.biz/qpos';

$imgpath = 'https://bithut.biz/qpos/assets/images';


include_once($rootpath.'/rak_framework/misfuncs.php');

	//database info



	$date = date("Y-n-j H:i:s");;



	$time = date("H:i:s");



$db_name="bithut_qpos";

$mysql_username="bithut_usr";

$mysql_password="ZKhpeZ){Bixb";



$server_name="localhost";

//$server_name="143.95.233.87";

$conn=mysqli_connect($server_name,$mysql_username,$mysql_password,$db_name);

$secretkey = 'bitBybit#5897456';



/* setting for rak_framework */





		$link = $conn;

		$dbhost	=	$server_name;

		$dbname	=	$db_name;

		$dbuser	=	$mysql_username;

		$dbpassword = $mysql_password;	

		

		 $colors = array(



	'#d3eef9',

		'#00abe3',

	'#47B7DE',

	'#00abe3',

	'#00abe3',

	'#00abe3',

	'#00abe3',

	'#00abe3',

    '#00abe3',

	'#2E98D5',

	'#00abe3'

 );

?>