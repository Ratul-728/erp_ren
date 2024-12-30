<?php

function bitLog($data){
//$data = array('shopid'=>3,'version'=> 1,'value'=>100	);  //here $data is dummy varaible

	$data = date('d-M-Y h:i:sa').": ".$data."\n";
	error_log($data, 3, $_SERVER['DOCUMENT_ROOT']."/BitFlow/bitlog/bitlog.txt");
	//error_log("You messed up!", 3, "/var/tmp/my-errors.log");

//In $data we can mention the error messege and create the log
}



?>