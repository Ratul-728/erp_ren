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

//print_r($_POST);die;
/*
if($action == 'state'){
    $qryUpdateQaw = "UPDATE `delivery_order_detail` SET `st`='".$value."' WHERE id = ".$id;
}
*/



    $qty = 1;
    //Get product Id
    $qryProduct = "SELECT trim(d.`item`) item, qaw.warehouse_id, trim(dor.order_id) order_id
    FROM `delivery_order_detail` d LEFT JOIN qa_warehouse qaw ON d.qa_id=qaw.id LEFT JOIN delivery_order dor ON dor.id = d.do_id
    WHERE d.`id` = ".$id;
    $resultProduct = $conn->query($qryProduct);
    while ($rowProduct = $resultProduct->fetch_assoc()) {
        $product = trim($rowProduct["item"]);
        $warehouse = $rowProduct["warehouse_id"];
        $socode = trim($rowProduct["order_id"]);
    }
    if($col == 'intransit_qty'){
        $qryAdd = ", pending_qty = pending_qty - ".$qty;
    }
    if($col == 'delivered_qty'){
        $qryAdd = ", intransit_qty = intransit_qty - ".$qty;
        
        $qryInfo = "SELECT iod.frombranch current_branch, iod.qty transfer_stock, i.barcode, i.name productnm, s.freeqty, iod.product 
            FROM issue_order as io LEFT JOIN issue_order_details iod ON iod.ioid=io.id LEFT JOIN item i ON i.id = iod.product 
            LEFT JOIN chalanstock s ON (s.product=i.id AND s.storerome=iod.frombranch) WHERE io.ioid ='".$socode."' and iod.product=".$product;
        $resultInfo = $conn->query($qryInfo);
        while ($rowInfo = $resultInfo->fetch_assoc()) {
            $curstore = $rowInfo["current_branch"];
            $storeto = 	9;
            
            $curqty = 	$rowInfo["freeqty"];	
            $trqtn = 	$rowInfo["transfer_stock"];
            
            $barcode = 	$rowInfo["barcode"];
            $prdname = 	$rowInfo["productnm"];
            $pid     = 	$rowInfo["product"];
        }
        	
        
        
        
        	//chk insert or edit
        	
        	$qryCkExist = "SELECT COUNT(id) cnt FROM `chalanstock` WHERE product = '$pid' AND storerome = '$storeto'";
            $resultCkExist = $conn->query($qryCkExist);
            while ($rowCkExist = $resultCkExist->fetch_assoc()) {
                if($rowCkExist["cnt"] > 0){
                    $action = 'update';
                }else{
                    $action = 'insert';
                }
            }
        	if($action == 'insert'){
        		$qryInsert = "INSERT INTO `chalanstock`(`product`, `freeqty`, `barcode`, `storerome`) 
        		                            VALUES ('$pid','$qty','$barcode','$storeto')";
        		if($conn->query($qryInsert)){
        		    $msg ='Stock transfer successful';
        		}else{
        		    $msg ='Something went worng';
        		}
        		
        		$qryUpdate = "UPDATE `chalanstock` SET `freeqty`=(freeqty - $qty) WHERE product = '$pid' and storerome = ".$curstore;
        		if($conn->query($qryUpdate)){
        		    $msg ='Stock transfer successful';
        		}else{
        		    $msg ='Something went worng';
        		}
        		
        		
        	}else{
        		$qryUpdateTo = "UPDATE `chalanstock` SET `freeqty`= (freeqty + $qty) WHERE product = '$pid' and storerome = ".$storeto;
        		if($conn->query($qryUpdateTo)){
        		    $msg ='Stock transfer successful';
        		}else{
        		    $msg ='Something went worng';
        		}
        		
        		$qryUpdateFrom = "UPDATE `chalanstock` SET `freeqty`= (freeqty - $qty) WHERE product = '$pid' and storerome = ".$curstore;
        		if($conn->query($qryUpdateFrom)){
        		    $msg ='Stock transfer successful';
        		}else{
        		    $msg ='Something went worng';
        		}
            }
               
        
    }
    $qryUpdateQaw = "UPDATE `delivery_order_detail` SET $col= $col + $qty $qryAdd WHERE id = ".$id;
    $conn->query($qryUpdateQaw);
    
    if($col == 'delivered_qty'){
        //Update Order Status
       $qryorder = "SELECT 
                    COALESCE(SUM(delivered_qty), 0) AS delivered,
                    COALESCE(SUM(pass_qty), 0) AS passqty
                FROM (
                    SELECT 
                        COALESCE(SUM(dod.delivered_qty), 0) AS delivered_qty,
                        0 AS pass_qty
                    FROM delivery_order_detail dod
                    WHERE dod.do_id IN (SELECT id FROM delivery_order WHERE order_id = '$socode')
                    
                    UNION ALL
                    
                    SELECT 
                        0 AS delivered_qty, 
                        COALESCE(SUM(qw.pass_qty), 0) AS pass_qty
                    FROM qa_warehouse qw
                    WHERE qw.qa_id IN (SELECT id FROM qa WHERE order_id = '$socode')
                ) AS subquery";
        $resultOrder = $conn->query($qryorder);
        while ($rowOrder = $resultOrder->fetch_assoc()) {
            $delivered = $rowOrder["delivered"];
            $passqty = $rowOrder["passqty"];
            
            //cancalled qty
            $cancelQty = 0;
            $qrycncl = "SELECT `qty_canceled` FROM `cancel_order` WHERE order_id = '$socode' AND `productid` = '$product' AND st = 2";
            $resultcncl = $conn->query($qrycncl);
            while ($rowcncl = $resultcncl->fetch_assoc()) {
                $cancelQty = $rowcncl["qty_canceled"];
            }
            
            if(($delivered + $cancelQty) >= $passqty){ 
                $qryUpdateSt = "UPDATE `quotation` SET `orderstatus`='8' WHERE `socode` = '$socode'";
                $conn->query($qryUpdateSt);
            }
            else if($delivered > 0){
                $qryUpdateSt = "UPDATE `quotation` SET `orderstatus`='7' WHERE `socode` = '$socode'";
                $conn->query($qryUpdateSt);
            }
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