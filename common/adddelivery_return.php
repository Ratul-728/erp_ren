<?php
require "conn.php";

//print_r($_POST);die;
extract($_POST);

for ($i=0;$i<count($warehouseIds);$i++){
    $warehouseId = $warehouseIds[$i];
    $csid = $csids[$i];
    $productId = $productIds[$i];
    $orderId = $orderIds[$i];
    $returned_item = $returned_items[$i];
    
    if($warehouseId == 6){
        continue;
    }

//Update delivery order details table
$qryUpdateDod = "UPDATE `delivery_order_detail` SET `due_return_qty`=`due_return_qty` - $returned_item,`returned_qty`=`returned_qty` + $returned_item WHERE id = ".$csid;
//echo $qryUpdateDod;die; 
$conn->query($qryUpdateDod);


$errflag = 0;
//Added to QA
$qaInsert ="INSERT INTO `qa`(`type`,`product_id`, `quantity`, `date_iniciated`, `status`, `delivery_date`, `order_id`) 
                    VALUES ('3','".$productId."','".$returned_item."','".date("Y-m-d H:i:s")."','1','".date("Y-m-d H:i:s")."','".$orderId."')";
                                
if ($conn->query($qaInsert) == TRUE)
{
    $insertedQaId = $conn->insert_id;
    
    $insertQaWarehouse = "INSERT INTO `qa_warehouse`(`qa_id`, `warehouse_id`, `ordered_qty`) 
                                VALUES ('".$insertedQaId."','".$warehouseId."','".$returned_item."')";
    if($conn->query($insertQaWarehouse) == true){}
    else{
        $errflag++;
    }
}else{
    $errflag ++;
}
                    
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errflag == 0) {
            $err="Sucessfully Updated";
            header("Location: ".$hostpath."/delivery_returnList.php?mod=16&res=1&msg=".$err);
    } else {
         $err="Error: Something went worng<br>";
          header("Location: ".$hostpath."/delivery_returnList.php?mod=16&res=2&msg=".$err);
    }
}
    
    $conn->close();

?>