<?php
require "conn.php";
session_start();

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');

$maxid="SELECT (max(`id`)+1) cd FROM `rfqpo`";
$resultmid = $conn->query($maxid); if ($resultmid->num_rows > 0) {while($rowmid = $resultmid->fetch_assoc()) { $reqid= $rowmid["cd"];}} if($reqid == '') $reqid = 1;
$pono = "PO-".$reqid;


$rpdate = $_POST["rp_date"];
$vendor = $_POST["vid"];
$address = $_POST["daddress"]; $address = addslashes($address);
$note = $_POST["dnote"];  $note = addslashes($note);

$qry = "INSERT INTO `rfqpo`( `poid`, `podate`, `vendor`, `delivery_address`, `note`) 
                    VALUES ('$pono',STR_TO_DATE('$rpdate','%d/%m/%Y'),'$vendor','$address', '$note')";
                    
$rfqaid = $_POST["rfqaid"];
                    
for ($i=0;$i<count($rfqaid);$i++){
    $rfqauthid=$rfqaid[$i];
    
    //Insert Into RFQ PO Detaitls 
    $qrydetails = "INSERT INTO `rfqpo_details`(`pono`, `rfq_auth`) VALUES ('$pono','$rfqauthid')";
    $conn->query($qrydetails);
    
    //Update RFQ Auth
    $qryup = "UPDATE `rfq_authorisation` SET `st`= 3 WHERE id = ".$rfqauthid;
    $conn->query($qryup);
    
}

if($conn->query($qry) == TRUE){
    $err = "Successfully Raise PO";
    header("Location: ".$hostpath."/raise_poList.php?res=1&msg=".$err."&mod=14&pg=1");
}else{
    $err = "Something went wrong";
    header("Location: ".$hostpath."/raise_poList.php?res=2&msg=".$err."&mod=14&pg=1");
}

?>