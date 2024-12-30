<?php
require_once("conn.php");
session_start();
$usr = $_SESSION["user"];

$roid = $_POST["roid"];

$packed = $_POST["packed"];     if($packed == "") $packed = 0;
$showroom = $_POST["showroom"]; if($showroom == "") $showroom = 0;
$barcode = $_POST["barcode"];   if($barcode == "") $barcode = 0;
$stock = $_POST["stock"];       if($stock == "") $stock = 0;

$qry = "UPDATE `delivery_order` SET `scm`='1',`packed`='$packed',`showroom`='$showroom',`barcode`='$barcode',`stock`='$stock' WHERE do_id = '$roid'";

if ($conn->query($qry) === TRUE) {
    $err="Successfully submitted";
     header("Location: ".$hostpath."/deliveryReturnList.php?res=1&msg=".$err."&id=''&mod=16");
} else {
     $err="Something Went Wrong";
     header("Location: ".$hostpath."/deliveryReturnList.php?res=2&msg=".$err."&id=''&mod=16");
}

?>