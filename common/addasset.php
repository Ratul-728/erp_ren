<?php
require "conn.php";
session_start();
$usr=$_SESSION["user"];

$empid = $_GET["empid"];
$bentype= $_REQUEST["assetbentype"];
$effectivedt = $_REQUEST["assetedt"];
$serial = $_REQUEST["assetserial"];
$details = $_REQUEST["assetdetails"]; $details = addslashes($details);

$qry = "INSERT INTO `assets`(`empid`, `benefittype`, `effectivedt`, `serial`, `details`, `makeby`, `makedt`) 
                    VALUES (".$empid." ,".$bentype.",'".$effectivedt."','".$serial."','".$details."',$usr,sysdate())";
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;

}
    
if ($conn->query($qry) == TRUE) {
    header("Location: ".$hostpath."/employee_hr.php?res=4&msg='Update Data'&id=$empid&mod=4");
    
} else {
    $err="Error: " . $qry . "<br>" . $conn->error;
    header("Location: ".$hostpath."/employee_hr.php??res=4&msg='Update Data'&id=$empid&mod=4");
}



?>