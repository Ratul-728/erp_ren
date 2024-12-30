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
    $type = 1;
    //Get product Id
    $qryProduct = "SELECT trim(d.`item`) item, qaw.warehouse_id, trim(dor.order_id) order_id,dor.type
    FROM `delivery_order_detail` d LEFT JOIN qa_warehouse qaw ON d.qa_id=qaw.id LEFT JOIN delivery_order dor ON dor.id = d.do_id
    WHERE d.`id` = ".$id;
    $resultProduct = $conn->query($qryProduct);
    while ($rowProduct = $resultProduct->fetch_assoc()) {
        $product = trim($rowProduct["item"]);
        $warehouse = $rowProduct["warehouse_id"];
        $socode = trim($rowProduct["order_id"]);
        $type = $rowProduct["type"];
    }
    $type = (int)$type;
    
    if($col == 'intransit_qty'){
        //echo 'hoi nai';die;
        $qryAdd = ", pending_qty = pending_qty - ".$qty;
        
        //Update Stock
        // $qryStock = "UPDATE stock SET orderedqty = orderedqty - $qty WHERE product = ".$product;
        // $conn->query($qryStock);
        
        // $qryChalan = "UPDATE `chalanstock` SET `orderedqty`= orderedqty - $qty WHERE storerome = $warehouse and `product` = ".$product;
        // $conn->query($qryChalan);
        
        /*accounting*/
       $transitamt=0;
        $Qtransitamt = "select (otc* $qty) amt  FROM soitemdetails WHERE socode ='".$socode."' and productid=".$product;
        //echo $Qtransitamt;die;
         $resTransitAmt = $conn->query($Qtransitamt);
        while ($rowamt = $resTransitAmt->fetch_assoc()) { $transitamt = $rowamt["amt"];  }
        
         $intransitgl = fetchByID('glmapping','buisness',9,'mappedgl');	
            //$bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
         //   $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
        $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
        
        $descr="Voucher againts Intransit -".$inv_id; 
              $refno=$inv_id;
             $vouchdt= date("d/m/Y");
               
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr,
            	'entryby' => $hrid,
            );
            	
            		$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$intransitgl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$transitamt,
            		'remarks' 	=>	'From unearned revenue',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$customergl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$transitamt,
            		'remarks' 	=>	'For matarial in tramsit',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            		insertGl($glmstArr,$gldetailArr);
            //	print_r($gldetailArr);
            	//die;
       /* accounting */
        
    } else if($col == 'delivered_qty'){
        $qryAdd = ", intransit_qty = intransit_qty - ".$qty;
        
        //Update Stock
        $qryStock = "UPDATE stock SET deliveredqty = deliveredqty + $qty, doqty = doqty-$qty WHERE product = ".$product;
        $conn->query($qryStock);
        
        //Update soitemdeitals
        $qrySoDetails = "UPDATE `soitemdetails` SET `deliveredqty`= deliveredqty + $qty,`dueqty`= dueqty - $qty WHERE `socode` = '".$socode."' AND `productid` = ".$product;
        $conn->query($qrySoDetails);
        
        //Update soitem warehouse. TODO: NEED To Done
        //$qrySowarehouse = "UPDATE `soitem_warehouse` SET `delivered_qty`= delivered_qty + $qty WHERE `socode` = '".$socode."' AND `warehouse` = '".$warehouse."'";
        //$conn->query($qrySowarehouse);
        /*accounting*/
       $deliveredamt=0;
        $Qdeliveryamt = "select (otc* $qty) amt  FROM soitemdetails WHERE socode ='".$socode."' and productid=".$product;
        //echo $Qtransitamt;die;
         $resDelivAmt = $conn->query($Qdeliveryamt);
        while ($rowamt = $resDelivAmt->fetch_assoc()) { $deliveredamt = $rowamt["amt"];  }
        
         $intransitgl = fetchByID('glmapping','buisness',9,'mappedgl');	
            //$bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
         //   $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
        $revenuegl = fetchByID('glmapping','buisness',2,'mappedgl');
        
        $descr="Voucher againts delivery -".$inv_id; 
              $refno=$inv_id;
             $vouchdt= date("d/m/Y");
               
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr,
            	'entryby' => $hrid,
            );
            	
            		$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$revenuegl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$deliveredamt,
            		'remarks' 	=>	'From unearned revenue',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$intransitgl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$deliveredamt,
            		'remarks' 	=>	'For matarial in tramsit',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            		insertGl($glmstArr,$gldetailArr);
            	//print_r($gldetailArr);
            	//die;
       /* */
       
        
    } else if($col == 'due_return_qty'){
        //Get chalanstock Id
        $qryChalanStock = "SELECT `id` FROM `chalanstock` WHERE `storerome` = 6 AND `product` = ".$product;
        //echo $qryChalanStock;die;
        $resultChalanStock = $conn->query($qryChalanStock);
        while ($rowCs = $resultChalanStock->fetch_assoc()) {
            $csid = $rowCs["id"];
        }
        
        if($csid == ''){
            $qryInsertCs = "INSERT INTO `chalanstock`(`product`, `freeqty`, `orderedqty`, `grsqcqty`,`storerome`) 
                                            VALUES ('$product','0','0','$qty','6')";
            $conn->query($qryInsertCs);
        }else{
            $qryUpdateCs = "UPDATE `chalanstock` SET `grsqcqty`= `grsqcqty` + $qty  WHERE id = ".$csid;
            $conn->query($qryUpdateCs);
        }
        
        //Update soitemdeitals
        $qrySoDetails = "UPDATE `soitemdetails` SET `deliveredqty`= deliveredqty + $qty,`dueqty`= dueqty - $qty WHERE `socode` = '".$socode."' AND `productid` = ".$product;
        // echo $qrySoDetails;die;
        $conn->query($qrySoDetails);
       
        //Update soitem warehouse. TODO: NEED To Done
        //$qrySowarehouse = "UPDATE `soitem_warehouse` SET `delivered_qty`= delivered_qty + $qty WHERE `socode` = '".$socode."' AND `warehouse` = '".$warehouse."'";
        //$conn->query($qrySowarehouse);
        
         /*accounting*/
       $deliveredamt=0;
        $Qdeliveryamt = "select (otc* $qty) amt  FROM soitemdetails WHERE socode ='".$socode."' and productid=".$product;
       // echo $Qdeliveryamt;die;
         $resDelivAmt = $conn->query($Qdeliveryamt);
        while ($rowamt = $resDelivAmt->fetch_assoc()) { $deliveredamt = $rowamt["amt"];  }
        
         $revenuegl = fetchByID('glmapping','buisness',2,'mappedgl');	
            //$bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
         //   $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
        $walletgl = fetchByID('glmapping','buisness',21,'mappedgl');
        
        $descr="Return againts delivery -".$inv_id; 
              $refno=$inv_id;
             $vouchdt= date("d/m/Y");
               
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr, 
            	'entryby' => $hrid,
            );
            	
            		$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$walletgl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$deliveredamt,
            		'remarks' 	=>	'Cash Reserve aginst return',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$revenuegl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$deliveredamt,
            		'remarks' 	=>	'Sales Return ',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            		insertGl($glmstArr,$gldetailArr);
            //	print_r($gldetailArr);die;
       /* */
        
        
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
                    WHERE qw.qa_id IN (SELECT id FROM qa WHERE type = 1 AND order_id = '$socode')
                ) AS subquery";
        $resultOrder = $conn->query($qryorder);
        while ($rowOrder = $resultOrder->fetch_assoc()) {
            $delivered = $rowOrder["delivered"];
            $passqty = $rowOrder["passqty"];
            
            //cancalled qty
            $cancelQty = 0;
            $qrycncl = "SELECT SUM(`qty_canceled`) qty_canceled FROM `cancel_order` WHERE order_id = '$socode' AND st = 2";
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
    $st = 1;
    while ($rowInfo = $resultInfo->fetch_assoc()) {
        $intransit_qty = $rowInfo["intransit_qty"];     $returned_qty = $rowInfo["returned_qty"];
        $delivered_qty = $rowInfo["delivered_qty"];     $qtyInfo = $rowInfo["qty"];
        $retqty = $rowInfo["retqty"];
        if(($intransit_qty + $delivered_qty + $returned_qty) == $qtyInfo && $returned_qty == 0){
            $st = 2;
        } else if(($intransit_qty + $delivered_qty + $returned_qty) == $qtyInfo && $rowInfo["returned_qty"] > 0){
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
    
    //Check CO
    if($type == 3 && $st == 2){
        $qryUpdateSt = "UPDATE `co_details` SET `st`='2' WHERE `order_id` = '$socode' AND `product_id` = '$product' AND `warehouse_id` = '$warehouse'";
        $conn->query($qryUpdateSt);
        
        //Check complete
        $qryCo = "SELECT st FROM `co_details` WHERE `order_id` = '$socode'";
        $resultCo = $conn->query($qryCo);
        $coSt = true;
        while ($rowCo = $resultCo->fetch_assoc()) {
            if($rowCo["st"] != 2){
               $coSt = false; break; 
            }
        }
        
        if($coSt){
            $qryUpdateSt = "UPDATE `co` SET `st`='3' WHERE `order_id` = '$socode'";
            $conn->query($qryUpdateSt);
        }else{
            $qryUpdateSt = "UPDATE `co` SET `st`='2' WHERE `order_id` = '$socode'";
            $conn->query($qryUpdateSt);
        }
    }else if($type == 3){
        $qryUpdateSt = "UPDATE `co_details` SET `st`='3' WHERE `order_id` = '$socode' AND `product_id` = '$product' AND `warehouse_id` = '$warehouse'";
        $conn->query($qryUpdateSt);
        
        $qryUpdateSt = "UPDATE `co` SET `st`='2' WHERE `order_id` = '$socode'";
        $conn->query($qryUpdateSt);
    }
    
echo "Successfully Updated!";
?>