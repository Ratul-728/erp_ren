<?php

require "./common/conn.php";

$order_id = $_GET["orderid"];

$qryGet= "SELECT * FROM `co_details` WHERE `order_id` = '$order_id'";
$resultGet = $conn->query($qryGet); 
if ($resultGet->num_rows > 0)
{ 
    while($row = $resultGet->fetch_assoc()) 
    { 
        $productId = $row["product_id"];  $productQty = $row["co_qty"]; $warehouseId = $row["warehouse_id"]; $warehouseQty = $row["co_qty"];
        
        $qaInsert ="INSERT INTO `qa`(`type`,`product_id`, `quantity`, `date_iniciated`, `status`, `order_id`) 
                                    VALUES ('8','".$productId."','".$productQty."','".date("Y-m-d H:i:s")."','1','".$order_id."')";
        $conn->query($qaInsert);
        
        $insertedQaId = $conn->insert_id;
        
        $insertQaWarehouse = "INSERT INTO `qa_warehouse`(`qa_id`,`qa_type`, `warehouse_id`, `ordered_qty`) 
                                                                    VALUES ('".$insertedQaId."','8','".$warehouseId."','".$warehouseQty."')";
        $conn->query($insertQaWarehouse);
    
    }
    
    $qryUpdateCo="UPDATE `co` SET `qa_status`='1' WHERE `order_id` = '$order_id'";
    if($conn->query($qryUpdateCo) == true){
        $err = "Send to QA successfully";
        header("Location: ".$hostpath."/deliveryCOList.php?res=1&mod=3&msg=".$err);
    }else{
        $err="Something went wrong";
        header("Location: ".$hostpath."/deliveryCOList.php?res=2&mod=3&msg=".$err);
    }
}else{
    $err="No CO fount";
    header("Location: ".$hostpath."/deliveryCOList.php?res=2&mod=3&msg=".$err);
}
?>