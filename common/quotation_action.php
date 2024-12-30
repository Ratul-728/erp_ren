<?php
require "conn.php";
session_start();

$res = $_GET["res"];
$id  = $_GET["id"];

if($res == 1){
    $qry = "UPDATE rfq_vendor SET st = 1 WHERE id = ".$id;
    $err = "Successfully Accepted";
}else{
    $qry = "UPDATE rfq_vendor SET st = 3 WHERE id = ".$id;
    $err = "Successfully Declined";
}

//echo $qry;die;


if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
}
    
if ($conn->query($qry) == TRUE) {
    header("Location: ".$hostpath."/pr_qoutationList.php?res=1&msg=".$err."&id=".$poid."&mod=14");
} else {
     $err="Error: " . $qry . "<br>" . $conn->error;
      header("Location: ".$hostpath."/pr_qoutationList.php?res=2&msg=".$err."&id=''&mod=14");
}
    


?>