<?php
require "../common/conn.php";
session_start();

$id = $_REQUEST["key"];
$type = $_REQUEST["type"];

if($type == 'orgtocontact'){
    
    $qry = "SELECT a.`name`, a.`id` FROM `contact` a LEFT JOIN `organization` b ON a.`organization` = b.`orgcode` WHERE b.`id` = ".$id." order by a.`name` ";
    //echo $qry;die;
    $result = $conn->query($qry);
    while($row = $result->fetch_assoc()){ ?>
        <option value="<?= $row["id"] ?>"><?=  $row["name"] ?></option>
    <?php }
    
}


?>