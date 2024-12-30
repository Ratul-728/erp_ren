<?php
require_once("../common/conn.php");

$val = $_POST["val"];
$atid = $_POST["id"];

if($val == 3 ||$val == 4 ||$val == 5 ){
    echo $atid;die;
}

$qry = "UPDATE `issueticket` SET `status`= $val, `probabledate` = sysdate() WHERE id = ".$atid;
if($conn->query($qry) == TRUE){
    echo "Successfully Update!";
}else{
    echo "Something went Wrong";
}
?>