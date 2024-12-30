<?php
require "conn.php";
session_start();

$id = $_GET["id"];
$res = $_GET["res"];
$amount = $_GET["amount"];
$orgid = $_GET["orgid"];

$qry = "UPDATE `allpayment` SET `st`= $res WHERE `id` = ".$id;

if($conn->query($qry) == true){
    $err = "Successfully updated";
    header("Location: ".$hostpath."/action_paymentList.php?res=1&msg=".$err."&id=".$aid."&mod=7");
}else{
    $err = "Something went wrong";
    header("Location: ".$hostpath."/action_paymentList.php?res=2&msg=".$err."&id=".$aid."&mod=7");
}
?>