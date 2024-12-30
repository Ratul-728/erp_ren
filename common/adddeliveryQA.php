<?php
require "conn.php";

include_once('../rak_framework/fetch.php');

include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

//print_r($_POST);die;
$usr = $_SESSION["user"];
$type = $_GET["type"]; if($type == "") $type = 1;
$dotype = $_GET["dotype"];

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/deal.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['update'] ) ) {
        
        $qaId = $_POST['qa_id'];
        $deliveryDt = $_POST['deli_dt'];
        $startTime = $_POST['starttime']; //if($cmbstage==''){$cmbstage='NULL';}
        $endTime = $_POST['endtime'];        //if($org==''){$org='NULL';}
        $cmbld = $_POST['cmbsupnm'];       //if($cmbld==''){$cmbld='NULL';}
        $deliverableQty = $_POST['deliverableQty'];           //if($ddt==''){$ddt='NULL';}
        $qaWarehouseId = $_POST["qwa"];
        $orderId = $_POST["order"];
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
            $qryDeliveryMain = "INSERT INTO `delivery_order`(`do_id`, `order_id`, `do_date`, `start_time`, `end_time`, `makeby`, `type`) 
                                                    VALUES ('".$docode."','".$orderId."',STR_TO_DATE('".$deliveryDt."', '%d/%m/%Y'),'".$startTime."','".$endTime."','".$usr."', '$type')";
            if ($conn->query($qryDeliveryMain) == TRUE) {
                $doId = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/deliveryQAList.php?res=2&mod=3&msg=".$err);
            } 
        }
        else{
            $errorFlag++;
            $err = 'Deliverable Item not given';
            header("Location: ".$hostpath."/deliveryQAList.php?res=2&mod=3&msg=".$err);
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
                             
                             //Update Stock
                            
                            if($dotype == "sales"){
                                $qryStock = "UPDATE stock SET orderedqty = orderedqty - $deliQty,  doqty = doqty + $deliQty WHERE product = ".$productId;
                                $conn->query($qryStock);
                                
                                $qrywarehouse = "SELECT warehouse_id FROM `qa_warehouse` WHERE id = ".$qwa;
                                $resultwarehouse = $conn->query($qrywarehouse);
                                while($rowwarehouse = $resultwarehouse->fetch_assoc()){
                                    $warehouse = $rowwarehouse["warehouse_id"];
                                    
                                    $qryChalan = "UPDATE `chalanstock` SET `orderedqty`= orderedqty - $deliQty WHERE storerome = $warehouse and `product` = ".$productId;
                                    $conn->query($qryChalan);
                                }
                            }
                            
                        }
                         
                    }
            }

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errorFlag == 0) {
                    //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = 25";
                      $resultmail = $conn->query($qrymail);
                      while($rowmail = $resultmail->fetch_assoc()){
                          $active = $rowmail["active"];
                          $emailid = $rowmail["id"];
                        if($active == 1){
                              $recipientNames = array();
                              $recipientEmails = array();
                              $ccEmails = array();
                              $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                                            FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                              $resultSendTo = $conn->query($qrySendTo);
                              while($rowst = $resultSendTo->fetch_assoc()){
                                  $recipientNames[] = $rowst["empname"];
                                  if($rowst["type"] == 1){
                                      $recipientEmails[] = $rowst["office_email"];
                                  }else if($rowst["type"] == 2){
                                      $ccEmails[] = $rowst["office_email"];
                                  }
                              }
                              if (!empty($recipientEmails)){
    
                                  $mailsubject = "A new DO is generated";
                
                                  $message = "A new delivery Order ( $docode ) was generated.Please proceed with resource planning";
                                            
                                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                              }
                        }
                      }
                      
        $err = "Record created successfully";
        header("Location: ".$hostpath."/deliveryQAList.php?res=1&mod=3&msg=".$err);
            
    } else {
        
        $err="Error:" . $conn->error;
        header("Location: ".$hostpath."/deliveryQAList.php?res=2&mod=3&msg=".$err);
    }
    
    $conn->close();
}
?>