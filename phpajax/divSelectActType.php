<?php
require "../common/conn.php";

session_start();

$user = $_SESSION["user"];
//print_r($_POST);die;





$name = $_POST["newItem"];
    
$qry="insert into ActionType(`Title`) 
        values('".$name."')" ;
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