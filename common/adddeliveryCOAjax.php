<?php
require "conn.php";

include_once('../rak_framework/fetch.php');

include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

// print_r($_POST);die;
$usr = $_SESSION["user"];
$type = $_GET["type"]; if($type == "") $type = 1;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/deal.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['update'] ) ) {
        
        $deliveryDt = $_POST['deli_dt'];
        $startTime = $_POST['starttime']; //if($cmbstage==''){$cmbstage='NULL';}
        $endTime = $_POST['endtime'];        //if($org==''){$org='NULL';}
        $deliverableQty = $_POST['deliverableQty'];           //if($ddt==''){$ddt='NULL';}
        $qaWarehouseId = $_POST["qwa"];
        $co_id = $_POST["co_id"];
        $orderid = $_POST["orderid"];
        $orderQtys = $_POST["orderQtyPer"];
        $productIds = $_POST["productid"];
        
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
            $qryDeliveryMain = "INSERT INTO `delivery_order`(`do_id`, `order_id`, `do_date`, `start_time`, `end_time`, `makeby`, `type`, `delivery_type`) 
                                                    VALUES ('".$docode."','".$co_id."',STR_TO_DATE('".$deliveryDt."', '%d/%m/%Y'),'".$startTime."','".$endTime."','".$usr."', '$type', '2')";
            if ($conn->query($qryDeliveryMain) == TRUE) {
                $doId = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/deliveryCOList.php?res=2&mod=3&msg=".$err);
            } 
        }
        else{
            $errorFlag++;
            $err = 'Deliverable Item not given';
            header("Location: ".$hostpath."/deliveryCOList.php?res=2&mod=3&msg=".$err);
        }  
        
        
         if (is_array($deliverableQty))
            {
                for ($i=0;$i<count($deliverableQty);$i++)
                    {
                        $deliQty = $deliverableQty[$i]; $qwa = $qaWarehouseId[$i]; $productId=$productIds[$i];$orderQty = $orderQtys[$i];
                        
                        if($deliQty > 0){
                            $itqry="INSERT INTO `delivery_order_detail`(`do_id`, `qa_id`, `qty`, `do_qty`, `item`, pending_qty) 
                                    VALUES ('".$doId."','".$qwa."','".$orderQty."','".$deliQty."','".$productId."', '".$deliQty."')";
                             //echo $itqry;die;
                             if ($conn->query($itqry) == TRUE) { $err="Delivery added successfully";  }
                             else{ $errorFlag++;}
                             
                        }
                         
                    }
            }

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errorFlag == 0) {
                      
        $err = "Create a delivery order successfully";
        header("Location: ".$hostpath."/deliveryCOList.php?res=1&mod=3&msg=".$err);
            
    } else {
        
        $err="Error:" . $conn->error;
        header("Location: ".$hostpath."/deliveryCOList.php?res=2&mod=3&msg=".$err);
    }
    
    $conn->close();
}
?>