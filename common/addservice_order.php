<?php
require "conn.php";
session_start();
include_once('../rak_framework/fetch.php');

// print_r($_POST);die;
$usr = $_SESSION["user"];

extract($_POST);
$errorFlag = 0;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/deal.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['add'] ) ) {
        
        $invcode = getFormatedUniqueID('service_invoice','id','SINV-',6,"0");
        $servicecode = getFormatedUniqueID('service_order','id','SO-',6,"0");
        
        $invmn= substr($po_dt,3,2);
        $invyr= substr($po_dt,6,4);
    
        $qryso = "INSERT INTO `service_order`(`code`, `customer`, `orderdate`, `transport`, `service_charge`,`details`, `makedt`, `makeby`) 
                                VALUES ('$servicecode','$org_id',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),'$transport_cost','$service_fee','$details',sysdate(),'$usr')";    
            // echo $qryso;die;
            if ($conn->query($qryso) == TRUE) {
                $soId = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/service_orderList.php?res=2&mod=3&msg=".$err);
            } 
            
        $qryinv = "INSERT INTO `service_invoice`( `invoice`, `serviceorder`,`customer`, `invoicedt`, `invyr`, `invmonth`, `paidamt`,`makeby`, `makedt`) 
                                        VALUES ('$invcode','$servicecode','$org_id',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),'$invyr','$invmn','0','$usr',sysdate())";
            
            //echo $qryDeliveryMain;die;
            if ($conn->query($qryinv) == TRUE) {
                $invId = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/service_orderList.php?res=2&mod=3&msg=".$err);
            } 
        $grandTotal = 0;$subTotal=0;$vat = 0;
         if (is_array($itemName))
            {
                for ($i=0;$i<count($itemName);$i++)
                    {
                        $product = $itemName[$i];
                        $description = $descriptions[$i]; $unit = $units[$i]; $qty = $qtys[$i]; $rate = $rates[$i]; $rdl = $rdls[$i]; $vendor = $vendors[$i];
                        $vatperproduct = $itmvat[$i];
                        
                        $totalForProduct = $qty * $rate;
                        $subTotal += $totalForProduct;
                        
                        $vat += ($totalForProduct * $vatperproduct) / 100;
                     
                        $inqrysod ="INSERT INTO `service_orderdetails`(`serviceid`, `sosl`, `product`, `description`, `unit`, `qty`, `rate`, `rdl`, `vendor`, `totalamount`) 
                                                        VALUES ('$soId','$i','$product','$description','$unit','$qty','$rate','$rdl','$vendor','$totalForProduct')";
                        $conn->query($inqrysod);
                        
                        $inqryinv="INSERT INTO `service_invoicedetails`(`invoiceid`, `sosl`, `product`, `totalamount`) 
                                                            VALUES ('$invId','$i','$product','$totalForProduct')";
                        $conn->query($inqryinv);
                         
                    }
            }
        $grandTotal += $subTotal + $transport_cost + $service_fee;
        
        //Vat always 15%
        // $vat = ($grandTotal * 15) / 100;
        $grandTotal += $vat;
            
        //Update service order
        $upsoqry = "UPDATE `service_order` SET `totalamount`='$grandTotal',`totalvat`='$vat' WHERE id = ".$soId;
        $conn->query($upsoqry);
        
        //Update Invoice order
        $upinvqry = "UPDATE `service_invoice` SET `invoiceamt`='$grandTotal', `dueamt`='$grandTotal' WHERE id = ".$invId;
        $conn->query($upinvqry);
        
        
        $err = "Record created successfully";
    }
        
    if ( isset( $_POST['update'] ) ) {
        
        $soId = $_POST['uid'];
        $invId = $_POST["invid"];
        
        $invmn= substr($po_dt,3,2);
        $invyr= substr($po_dt,6,4);
        
        //Delete existing row
        $delsoqry = "DELETE FROM `service_orderdetails` WHERE serviceid = ".$soId;
        $conn->query($delsoqry);
        
        $delinvqry = "DELETE FROM `service_invoicedetails` WHERE `invoiceid` = ".$invId;
        $conn->query($delinvqry);
        
        $grandTotal = 0;$subTotal=0; $vat = 0;
         if (is_array($itemName))
            {
                for ($i=0;$i<count($itemName);$i++)
                    {
                        $product = $itemName[$i];
                        $description = $descriptions[$i]; $unit = $units[$i]; $qty = $qtys[$i]; $rate = $rates[$i]; $rdl = $rdls[$i]; $vendor = $vendors[$i];
                        $totalForProduct = $qty * $rate;
                        $subTotal += $totalForProduct;
                        $vatperproduct = $itmvat[$i];
                        $vat += ($totalForProduct * $vatperproduct) / 100;
                        
                     
                        $inqrysod ="INSERT INTO `service_orderdetails`(`serviceid`, `sosl`, `product`, `description`, `unit`, `qty`, `rate`, `rdl`, `vendor`, `totalamount`) 
                                                        VALUES ('$soId','$i','$product','$description','$unit','$qty','$rate','$rdl','$vendor','$totalForProduct')";
                        $conn->query($inqrysod);
                        
                        $inqryinv="INSERT INTO `service_invoicedetails`(`invoiceid`, `sosl`, `product`, `totalamount`) 
                                                            VALUES ('$invId','$i','$product','$totalForProduct')";
                        $conn->query($inqryinv);
                         
                    }
            }
        $grandTotal += $subTotal + $transport_cost + $service_fee;
        
        //Vat always 15%
        // $vat = ($grandTotal * 15) / 100;
        $grandTotal += $vat;
            
        //Update service order
        $upsoqry = "UPDATE `service_order` SET `customer`='$org_id',`orderdate`=STR_TO_DATE('".$po_dt."', '%d/%m/%Y'), `totalamount`='$grandTotal',
                    `totalvat`='$vat', `transport`='$transport_cost', `service_charge` = '$service_fee', `details` = '$details' 
                    WHERE id = ".$soId;
        $conn->query($upsoqry);
        
        //Update Invoice order
        $upinvqry = "UPDATE `service_invoice` SET `invoicedt` = STR_TO_DATE('".$po_dt."', '%d/%m/%Y'), `customer`='$org_id', `invoiceamt`='$grandTotal', `dueamt`='$grandTotal', 
                     `invyr` = $invyr, `invmonth` = $invmn
                    WHERE id = ".$invId;
        $conn->query($upinvqry);
        
        
        $err = "Record updated successfully";
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errorFlag == 0) {
        header("Location: ".$hostpath."/service_orderList.php?res=1&mod=22&msg=".$err);
            
    } else {
        
        $err="Error:" . $conn->error;
        header("Location: ".$hostpath."/service_orderList.php?res=2&mod=22&msg=".$err);
    }
    
    $conn->close();
}
?>