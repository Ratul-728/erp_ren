<?php
require "conn.php";

include_once('../rak_framework/fetch.php');

include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

// print_r($_POST);die;
$usr = $_SESSION["user"];

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/deal.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['update'] ) ) {
        
        $qaId = $_POST['qa_id'];
        $cmbld = $_POST['cmbsupnm'];       //if($cmbld==''){$cmbld='NULL';}
        $deliverableQty = $_POST['deliverableQty'];           //if($ddt==''){$ddt='NULL';}
        $qaWarehouseId = $_POST["qwa"];
        $orderId = $_POST["order"];
        $orderQtys = $_POST["orderQtyPer"];
        $productIds = $_POST["productid"];
        $warehouseIds = $_POST["warehouseIds"];
        $before_warehouses = $_POST["before_warehouses"];
        
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
            
        
        if($flag){
            // $qryDeliveryMain = "UPDATE `soitem` SET co_store='$store', `co_st`='1' WHERE `socode` = '$orderId'";
            // if ($conn->query($qryDeliveryMain) == TRUE) {
            //     $doId = $conn->insert_id;
            // }else{
            //     $err = 'Something went wrong!';
            //     header("Location: ".$hostpath."/deliveryCOList.php?res=2&mod=3&msg=".$err);
            // }
            
            $coId = getFormatedUniqueID('co_approval','id','CO-',6,"0");
            
            $qryCo = "INSERT INTO `co_approval` (`co_id`, `order_id`, `makeby`, `makedt`) VALUES ('$coId','$orderId','$usr',sysdate())";
            if ($conn->query($qryCo) == TRUE) {
                $coid = $conn->insert_id;
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
                        $deliQty = $deliverableQty[$i]; $warehouseId = $warehouseIds[$i]; $productId=$productIds[$i];$orderQty = $orderQtys[$i];
                        $before_warehouse = $before_warehouses[$i];
                        
                        if($deliQty > 0){
                            // $itqry="UPDATE `soitemdetails` SET `co_qty`='$deliQty', `co_assign`='$deliQty' WHERE `socode` = '$orderId' AND `productid` = '$productId'";
                            //  //echo $itqry;die;
                            //  if ($conn->query($itqry) == TRUE) { $err="Delivery added successfully";  }
                            //  else{ $errorFlag++;}
                             
                             $qryCoDetails = "INSERT INTO `co_approval_details`(`coid`, `order_id`, `product_id`, `warehouse_id`,`before_warehouse`, `order_qty`, `co_qty`) 
                                                        VALUES ('$coid','$orderId','$productId','$warehouseId','$before_warehouse','$orderQty','$deliQty')";
                             if ($conn->query($qryCoDetails) == TRUE) { $err="Delivery added successfully";  }
                             else{ $errorFlag++;}
                             
                        }
                    }
            }

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errorFlag == 0) {
        
        //Mail to Management
        $qrymail = "SELECT id,active FROM `email` WHERE id = 44";
        $resultmail = $conn->query($qrymail);
        while($rowmail = $resultmail->fetch_assoc())
        {
            $active = $rowmail["active"];
            $emailid = $rowmail["id"];
            if($active == 1)
            {
                $recipientNames = array();
                $recipientEmails = array();
                $ccEmails = array();
                $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                            FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                $resultSendTo = $conn->query($qrySendTo);
                while($rowst = $resultSendTo->fetch_assoc())
                {
                    $recipientNames[] = $rowst["empname"];
                    if($rowst["type"] == 1 && $rowst["office_email"] != "")
                    {
                        $recipientEmails[] = $rowst["office_email"];
                    }
                    else if($rowst["type"] == 2 && $rowst["office_email"] != "")
                    {
                        $ccEmails[] = $rowst["office_email"];
                    }
                }
                $mailsubject = "Approval Required for Customer Order";
                $message = "An approval request for Customer Order $coId was received.";
                            
                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
            }
        }
                      
        $err = "Record created successfully";
        header("Location: ".$hostpath."/deliveryCOList.php?res=1&mod=3&msg=".$err);
            
    } else {
        
        $err="Error:" . $conn->error;
        header("Location: ".$hostpath."/deliveryCOList.php?res=2&mod=3&msg=".$err);
    }
    
    $conn->close();
}
?>