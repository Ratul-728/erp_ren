<?php
require "../common/conn.php";
session_start();

$id = $_REQUEST["key"];
$type = $_REQUEST["type"];

if($type == 'return_payment'){
    
    $qry = "SELECT DISTINCT(q.order_id) FROM `qa` q LEFT JOIN quotation so ON q.order_id=so.socode WHERE q.type = 3 and so.organization= ".$id." order by q.order_id ";
    //echo $qry;die;
    $result = $conn->query($qry);
    while($row = $result->fetch_assoc()){ ?>
        <option value="<?= $row["order_id"] ?>"><?=  $row["order_id"] ?></option>
    <?php }
    
}


?>