<?php

session_start();
require "../common/conn.php";
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
include_once('../rak_framework/connection.php');
require_once('../common/insert_gl.php');


$usr=$_SESSION["user"];

extract($_POST);

// print_r($_POST);die;
/*
if($action == 'state'){
    $qryUpdateQaw = "UPDATE `delivery_order_detail` SET `st`='".$value."' WHERE id = ".$id;
}
*/



    $qty = 1;
    //Get product Id
    $qryProduct = "SELECT  r.ro_id, r.id rid, q.product_id, qaw.warehouse_id
                FROM `delivery_order_detail` dod LEFT JOIN delivery_order d ON d.id=dod.do_id LEFT JOIN 				
                return_order r ON r.ro_id =d.order_id LEFT JOIN
                qa_warehouse qaw ON qaw.id=dod.qa_id LEFT JOIN qa q ON q.id=qaw.qa_id
                WHERE dod.id = ".$id;
    // echo $qryProduct;die;
    $resultProduct = $conn->query($qryProduct);
    while ($rowProduct = $resultProduct->fetch_assoc()) {
        $ro_id = $rowProduct["ro_id"];
        $rid   = $rowProduct["rid"];
        $product = $rowProduct["product_id"];
        $warehouse = $rowProduct["warehouse_id"];
    }
    if($col == 'intransit_qty'){
        
        $qryAdd = ", pending_qty = pending_qty - ".$qty;
        
    }
    else if($col == 'delivered_qty'){
        $qryAdd = ", intransit_qty = intransit_qty - ".$qty;
    }
    $qryUpdateQaw = "UPDATE `delivery_order_detail` SET $col= $col + $qty $qryAdd WHERE id = ".$id;
    $conn->query($qryUpdateQaw);
    
    if($col == 'delivered_qty'){
        //Update Order Status
       $qryorder = "SELECT SUM(delivered_qty) AS total_delivered_qty FROM delivery_order d 
                    LEFT JOIN delivery_order_detail dod ON d.id=dod.do_id 
                    WHERE d.order_id = '$ro_id'";
        // echo $qryorder;die;
        $resultOrder = $conn->query($qryorder);
        while ($rowOrder = $resultOrder->fetch_assoc()) {
            $totaldelivered = $rowOrder["total_delivered_qty"];
        }
        
        $qryorder = "SELECT SUM(`return_qty`) return_qty FROM `return_order_details` WHERE `ro_id` = ".$rid;
        $resultOrder = $conn->query($qryorder);
        while ($rowOrder = $resultOrder->fetch_assoc()) {
            $totalco = $rowOrder["return_qty"];
        }
        
        //Need to work for status
        
        // if($totaldelivered >= $totalco){
        //     $qryUpCo = "UPDATE `return_order` SET `delivered`='1' WHERE `ro_id` = '$ro_id'";
        //     $conn->query($qryUpCo);
        // }
        
        
        //Transfer Stock
        $qryUpdateTo = "UPDATE `chalanstock` SET `freeqty`= (freeqty + $qty) WHERE product = '$product' and storerome = ".$warehouse;
        // echo $qryUpdateTo;die;
		if($conn->query($qryUpdateTo)){
		    
		    $qryUpdateToStock = "UPDATE `stock` SET `freeqty`= (freeqty + $qty) WHERE `product` = '$product'";
    		if($conn->query($qryUpdateToStock)){
    		    
    		    $msg ='Stock transfer successful';
    		}else{
    		    $msg ='Something went worng';
    		}
    		
		    $msg ='Stock transfer successful';
		}else{
		    $msg ='Something went worng';
		}
    }
    
    $qryInfo = "SELECT SUM(`intransit_qty`) intransit_qty, SUM(`delivered_qty`) delivered_qty, SUM(`returned_qty`) retqty, `returned_qty`, `qty` FROM `delivery_order_detail` WHERE `id` = ".$id;
    $resultInfo = $conn->query($qryInfo);
    while ($rowInfo = $resultInfo->fetch_assoc()) {
        $intransit_qty = $rowInfo["intransit_qty"];     $returned_qty = $rowInfo["returned_qty"];
        $delivered_qty = $rowInfo["delivered_qty"];     $qtyInfo = $rowInfo["qty"];
        $retqty = $rowInfo["retqty"];
        if(($intransit_qty + $delivered_qty + $returned_qty) == $qtyInfo && $returned_qty == 0){
            $st = 2;
        } else if(($intransit_qty + $delivered_qty + $returned_qty) == $qtyInfo && $rowInfo["$returned_qty"] > 0){
            $st = 4;
        }else if($intransit_qty == $qtyInfo){
            $st = 3;
        }
        else if($delivered_qty == $qtyInfo){
            $st = 2;
        }else {
            $st = 1;
        }
    }
    
    $qryUpdateQaw = "UPDATE `delivery_order_detail` SET st=$st WHERE id = ".$id;
    $conn->query($qryUpdateQaw);
    

echo "Successfully Updated!";
?>