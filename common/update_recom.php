<?php
require "conn.php";
session_start();

$id = $_GET["id"];
$act = $_GET["res"];
$empid = $_SESSION["empid"];

if($act == 1){
    $qrych = "SELECT `rfq_vendor` FROM `rfq_authorisation` WHERE id = ".$id;
    $resultch = $conn->query($qrych); 
    while($rowch = $resultch->fetch_assoc()){
        $rfqVendor = $rowch["rfq_vendor"];
    }
    
    $qryDec = "UPDATE `rfq_authorisation` SET `st`= 2, `approveby`= '$empid',`approvedate`=sysdate() WHERE st = 0 and rfq_vendor = '$rfqVendor' and `id` != ".$id;
    $conn->query($qryDec);
    
}

$qry = "UPDATE `rfq_authorisation` SET `st`= $act, `approveby`= '$empid',`approvedate`=sysdate() WHERE `id` = ".$id;

if($conn->query($qry) == true){
    $err = "Successfully updated";
    header("Location: ".$hostpath."/recommendation_quotList.php?res=1&msg=".$err."&id=".$aid."&mod=14");
}else{
    $err = "Something went wrong";
    header("Location: ".$hostpath."/recommendation_quotList.php?res=2&msg=".$err."&id=".$aid."&mod=14");
}
?>