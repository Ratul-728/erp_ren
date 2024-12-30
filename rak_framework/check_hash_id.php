<?php
if($_REQUEST["id"]){
	$id = $_REQUEST["id"]; //in this case the value is 12345
	$hash = $_REQUEST["token"];
	if (chkRakHash($id, $hash)) {
	  //no tampering detected, proceed with other processing
		  $_REQUEST['id'] = $_REQUEST['id'];
	} else {
	  //tampering of data detected
		  $_REQUEST['id'] = '';
		  echo 'Unexpected Query String......';
		  exit();
	}
}
?>