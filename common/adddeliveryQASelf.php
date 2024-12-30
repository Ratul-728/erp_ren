<?php
require "conn.php";
session_start();

include_once('../rak_framework/fetch.php');

// print_r($_POST);die;
$usr = $_SESSION["user"];

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/deal.php?res=01&msg='New Entry'&id=''");
}
else
{
        // print_r($_POST);die;
        $qaId = $_POST['qa_id'];
        $deliveryDt = $_POST['deli_dt'];
        $startTime = $_POST['starttime']; //if($cmbstage==''){$cmbstage='NULL';}
        $endTime = $_POST['endtime'];        //if($org==''){$org='NULL';}
        // $cmbld = $_POST['cmbsupnm'];       //if($cmbld==''){$cmbld='NULL';}
        $deliverableQty = $_POST['deliverableQty'];           //if($ddt==''){$ddt='NULL';}
        $qaWarehouseId = $_POST["qwa"];
        $orderId = $_POST["order"];
        $orderQtys = $_POST["orderQtyPer"];
        $productIds = $_POST["productid"];
        $warehouseIds =$_POST["warehouseIds"];
        $errorFlag = 0;
        $flag = false;
        
        if (is_array($deliverableQty))
            {
                for ($i=0;$i<count($deliverableQty);$i++)
                    {
                        if($deliverableQty[$i] > 0){
                            $flag = true;
                            break;
                        }
                    }
            }
            
            
        $docode = getFormatedUniqueID('delivery_order','id','DO-',6,"0");
        
        
        if($flag){
            $qryDeliveryMain = "INSERT INTO `delivery_order`(`do_id`, `order_id`, `do_date`, `start_time`, `end_time`, `makeby`, `resourceplan`, `type`) 
                                                    VALUES ('".$docode."','".$orderId."',sysdate(),sysdate(),sysdate(),'".$usr."', '2', '2')";
            if ($conn->query($qryDeliveryMain) == TRUE) {
                $doId = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                echo $err; die;
            } 
        }
        else{
            $errorFlag++;
            $err = 'Deliverable Item not given';
            echo $err; die;
        }  
        
        
         if (is_array($deliverableQty))
            {
                for ($i=0;$i<count($deliverableQty);$i++)
                    {
                        $deliQty = $deliverableQty[$i]; $qwa = $qaWarehouseId[$i]; $productId=$productIds[$i];$orderQty = $orderQtys[$i];
                        $warehouseId = $warehouseIds[$i];
                        
                        if($deliQty > 0){
                            $itqry="INSERT INTO `delivery_order_detail`(`do_id`, `qa_id`, `qty`, `do_qty`, `item`, delivered_qty, st) 
                                    VALUES ('".$doId."','".$qwa."','".$orderQty."','".$deliQty."','".$productId."', '".$deliQty."', '2')";
                             //echo $itqry;die;
                             if ($conn->query($itqry) == TRUE) { $err="Delivery added successfully";  }
                             else{ $errorFlag++;}
                             
                            
                            $qryChalan = "UPDATE `chalanstock` SET `orderedqty`= orderedqty - $deliQty WHERE storerome = $warehouseId and `product` = ".$productId;
                            $conn->query($qryChalan);
                            
                            //Update Stock
                            $qryStock = "UPDATE stock SET deliveredqty = deliveredqty + $deliQty, orderedqty = orderedqty - $deliQty WHERE product = ".$productId;
                            $conn->query($qryStock);
                            
                            //Update soitemdeitals
                            $qrySoDetails = "UPDATE `soitemdetails` SET `deliveredqty`= deliveredqty + $deliQty WHERE `socode` = '".$orderId."' AND `productid` = ".$productId;
                            $conn->query($qrySoDetails);
                            
                            //Check CO
                            $qryCo = "SELECT co_st FROM `soitem`  WHERE socode = '$orderId' AND co_st > 0";
                            $resultCo = $conn->query($qryCo);
                            while ($rowCo = $resultCo->fetch_assoc()) {
                                $qryCoupdate = "UPDATE `soitemdetails` SET `co_qty`= `co_qty` - $deliQty WHERE socode = '$orderId' AND productid = '$productId' AND co_qty > 0";
                                $conn->query($qryCoupdate);
                                
                                $qryChCo="SELECT SUM(co_qty) co_qty FROM `soitemdetails` WHERE socode = '$orderId'";
                                $resultChCo = $conn->query($qryChCo);
                                while ($rowChCo = $resultChCo->fetch_assoc()) {
                                    if($rowChCo["co_qty"] != '' and $rowChCo["co_qty"] == 0){
                                        $qryCoUpst = "UPDATE `soitem` SET `co_st`= 0 WHERE socode = '$orderId'";
                                        $conn->query($qryCoUpst);
                                    }else{
                                        $qryCoUpst = "UPDATE `soitem` SET `co_st`= 2 WHERE socode = '$orderId'";
                                        $conn->query($qryCoUpst);
                                    }
                                }
                            }
                        }
                        
                         
                    }
            }
            
            //Add demo resouce plan
            $qryResource = "INSERT INTO `resourceplan`(`doid`, `type`, `supervisor`, `labor_qty`, `delivery_start`, `delivery_end`) 
                                            VALUES ('$docode','1','0','0',sysdate(),sysdate())";
            $conn->query($qryResource);
            
            //Update Order Status
           $qryorder = "SELECT 
                        COALESCE(SUM(delivered_qty), 0) AS delivered,
                        COALESCE(SUM(pass_qty), 0) AS passqty
                    FROM (
                        SELECT 
                            COALESCE(SUM(dod.delivered_qty), 0) AS delivered_qty,
                            0 AS pass_qty
                        FROM delivery_order_detail dod
                        WHERE dod.do_id IN (SELECT id FROM delivery_order WHERE order_id = '$orderId')
                        
                        UNION ALL
                        
                        SELECT 
                            0 AS delivered_qty, 
                            COALESCE(SUM(qw.pass_qty), 0) AS pass_qty
                        FROM qa_warehouse qw
                        WHERE qw.qa_id IN (SELECT id FROM qa WHERE order_id = '$orderId')
                    ) AS subquery";
            $resultOrder = $conn->query($qryorder);
            while ($rowOrder = $resultOrder->fetch_assoc()) {
                $delivered = $rowOrder["delivered"];
                $passqty = $rowOrder["passqty"];
                
                //cancalled qty
                $cancelQty = 0;
                $qrycncl = "SELECT SUM(`qty_canceled`) qty_canceled FROM `cancel_order` WHERE order_id = '$orderId' AND st = 2";
                $resultcncl = $conn->query($qrycncl);
                while ($rowcncl = $resultcncl->fetch_assoc()) {
                    $cancelQty = $rowcncl["qty_canceled"];
                }
                
                if(($delivered + $cancelQty) >= $passqty){ 
                    $qryUpdateSt = "UPDATE `quotation` SET `orderstatus`='8' WHERE `socode` = '$orderId'";
                    $conn->query($qryUpdateSt);
                }
                else if($delivered > 0){
                    $qryUpdateSt = "UPDATE `quotation` SET `orderstatus`='7' WHERE `socode` = '$orderId'";
                    $conn->query($qryUpdateSt);
                }
            }
    
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errorFlag == 0) {
                      
        echo "Deliver successful";
            
    } else {
        
        $err="Something went wrong!";
        echo $err;
    }
    
    $conn->close();
}
?>