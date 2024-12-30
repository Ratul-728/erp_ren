<?php
require "../common/conn.php";
include_once('../rak_framework/fetch.php');
session_start();

$user = $_SESSION["user"];
//print_r($_POST);die;



$catid = getFormatedUniqueID('itmCat','id','CT-',6,"0");

$name = $_POST["newItem"];
    
$qry="insert into itmCat(`name`,`catid`) 
        values('".$name."','".$catid."')" ;
//echo $qry;die;        
if ($conn->query($qry) == TRUE) {
    $last_id = $conn->insert_id;
    
    $response = array(

        "id" => $last_id,
    
        "name" => $name
        
    );
        
    echo json_encode($response);exit();

}



?>