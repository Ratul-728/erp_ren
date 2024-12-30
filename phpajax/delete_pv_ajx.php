<?php
session_start();
$usr = $_SESSION["user"];

if($usr == ''){
	header("Location: " . $hostpath . "/hr.php");
}else{

if($_REQUEST ['action'] == 'deletepic' && isset($_REQUEST['pictodelete'])){

		//$filename = basename($_REQUEST['pictodelete']);
		$filename = $_REQUEST['pictodelete'];

		if(@unlink('../'.$filename)){
			echo 'Picture removed';
			//echo $filename;
		}else{
			echo 0;
		}
	}
}
?>