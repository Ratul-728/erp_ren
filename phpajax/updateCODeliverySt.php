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
    $qryProduct = "SELECT cd.id cdid, c.co_id 
                FROM `delivery_order_detail` dod LEFT JOIN co_details cd ON cd.id=dod.qa_id LEFT JOIN co c ON c.id=cd.coid
                WHERE dod.id = ".$id;
    $resultProduct = $conn->query($qryProduct);
    while ($rowProduct = $resultProduct->fetch_assoc()) {
        $co_details_id = $rowProduct["cdid"];
        $co_id = $rowProduct["co_id"];
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
                    WHERE d.order_id = '$co_id'";
        $resultOrder = $conn->query($qryorder);
        while ($rowOrder = $resultOrder->fetch_assoc()) {
            $totaldelivered = $rowOrder["total_delivered_qty"];
        }
        
        $qryorder = "SELECT SUM(co_qty) AS total_co_qty 
                     FROM co_details
                     WHERE coid = (SELECT coid FROM co_details WHERE id = $co_details_id);";
        $resultOrder = $conn->query($qryorder);
        while ($rowOrder = $resultOrder->fetch_assoc()) {
            $totalco = $rowOrder["total_co_qty"];
        }
        
        if($totaldelivered >= $totalco){
            $qryUpCo = "UPDATE `co` SET `delivery_status`='1' WHERE `co_id` = '$co_id'";
            $conn->query($qryUpCo);
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