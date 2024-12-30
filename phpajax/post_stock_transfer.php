<?php
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once("../common/conn.php");
require_once("../rak_framework/fetch.php");
require_once("../rak_framework/edit.php");


    $obj = json_decode($_POST["posData"]);
    
    $curstore = $obj[0]->curstore;
    $storeto = 	$obj[0]->storeto;
    
    $curqty = 	$obj[0]->curqty;	
    $trqtn = 	$obj[0]->trqtn;
    
    $barcode = 	$obj[0]->barcode;
    $prdname = 	$obj[0]->prdname;
    $pid = 	$obj[0]->pid;
    
    $qryApproval = "INSERT INTO `approval_transfer_stock`(`product`, `current_branch`, `transfer_branch`, `current_stock`, `transfer_stock`) VALUES ('$pid','$curstore','$storeto','$curqty','$trqtn')";
    //echo $qryApproval;die;
    if(mysqli_query($conn, $qryApproval)){
        $msg = "Transfer request sent to management!
                Wait for your approval";
        $data = array(
        	'success' => 1,
        	'msg' => $msg,
        );
    }else{
        $msg = "Something went wrong";
        $data = array(
        	'success' => 0,
        	'msg' => $msg,
        );
    }
    echo json_encode($data);
    die;


?>