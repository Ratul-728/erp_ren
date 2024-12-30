<?php

session_start();
require "../common/conn.php";

$do_id = $_GET["doid"];

$qryUpdate = "UPDATE `delivery_order` SET `type`='0' WHERE do_id = '".$do_id."'";
if($conn->query($qryUpdate) == true)
{
    $msg = "Successfully Cancel DO!";
    header("Location: ".$hostpath."/deliveryQAList.php?res=1&msg=$msg&mod=3");
    die;
}
else
{
    $msg = "Something went wrong";
    header("Location: ".$hostpath."/deliveryQAList.php?res=2&msg=$msg&mod=3");
    die;
}

?>