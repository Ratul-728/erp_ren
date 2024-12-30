<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require "conn.php";

$stockid = $_GET['id'];

$qryInfo = "SELECT `product`, `freeqty`, `storerome` FROM `chalanstock` WHERE `id` = ".$stockid;
$resultInfo = $conn->query($qryInfo);
while ($rowInfo = $resultInfo->fetch_assoc()) {
    $product = $rowInfo["product"];  $qty = $rowInfo["freeqty"]; $warehouse = $rowInfo["storerome"];
    
    //Insert into qa
    $qryQa = "INSERT INTO `qa`(`type`, `product_id`, `quantity`, `date_iniciated`, `status`) 
                    VALUES ('9','$product','$qty',sysdate(),'1')";
    $conn->query($qryQa);
    $newid = $conn -> insert_id;
        
    $qryWarehouse = "INSERT INTO `qa_warehouse`( `qa_id`, `qa_type`, `warehouse_id`, `ordered_qty`, `date_inspected`) 
                    VALUES ('$newid','9','$warehouse','$qty',sysdate())";
    $conn->query($qryWarehouse);
}

    //Update QC Date
    $qry = "UPDATE `chalanstock` SET `qadt`= sysdate() WHERE `id` = ".$stockid;

    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
        $err = "Successfully sent to QC!";
        header("Location: ".$hostpath."/periodic_qcList.php?res=1&msg=".$err."&mod=3");
    } else {
        $err="Error: " . $qry . "<br>" . $conn->error;
        header("Location: ".$hostpath."/periodic_qcList.php?res=2&msg=".$err."&mod=3");
    }
    
    $conn->close();
?>