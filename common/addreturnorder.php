<?php
require "conn.php";
session_start();

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include_once('../rak_framework/fetch.php');

include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

//print_r($_POST);die;
$usr = $_SESSION["user"];

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
        $returnqty = $_POST['returnqty'];           //if($ddt==''){$ddt='NULL';}
        $qaWarehouseId = $_POST["qwa"];
        $orderId = $_POST["order"];
        $orderQtys = $_POST["orderQtyPer"];
        $productIds = $_POST["productid"];
        $note = $_POST["remarks"];
        $towarehouses = $_POST["towarehouse"];
        
        $errorFlag = 0;
        $flag = false;
        
        if (is_array($returnqty))
            {
                for ($i=0;$i<count($returnqty);$i++)
                    {
                        if($returnqty[$i] > 0){
                            $flag = true;
                            break;
                        }
                    }
            }
            
            
        $rocode = getFormatedUniqueID('return_order','id','RO-',6,"0");
        
        
        if($flag){
            $qryReturnMain = "INSERT INTO `return_order`(`ro_id`, `order_id`, `note`, `makeby`, `makedt`) 
                                                VALUES ('".$rocode."','".$orderId."','$note','".$usr."', sysdate())";
            if ($conn->query($qryReturnMain) == TRUE) {
                $roId = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/returnorderList.php?res=2&mod=3&msg=".$err);
            } 
        }
        else{
            $errorFlag++;
            $err = 'Return Item not given';
            header("Location: ".$hostpath."/returnorderList.php?res=2&mod=3&msg=".$err);
        }  
        
        
         if (is_array($returnqty))
            {
                for ($i=0;$i<count($returnqty);$i++)
                    {
                        $deliQty = $returnqty[$i]; $qwa = $qaWarehouseId[$i]; $productId=$productIds[$i];$orderQty = $orderQtys[$i]; $towarehouse = $towarehouses[$i];
                        
                        if($deliQty > 0){
                            $itqry="INSERT INTO `return_order_details`(`ro_id`, `qaw_id`,`warehouse`, `return_qty`)
                                                            VALUES ('".$roId."','".$qwa."','$towarehouse','".$deliQty."')";
                            //  echo $itqry;die;
                             if ($conn->query($itqry) == TRUE) { $err="Return added successfully";  }
                             else{ $errorFlag++;}
                        }
                         
                    }
            }

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errorFlag == 0) {
    
        $err = "Record created successfully";
        header("Location: ".$hostpath."/returnorderList.php?res=1&mod=3&msg=".$err);
            
    } else {
        
        $err="Error:" . $conn->error;
        header("Location: ".$hostpath."/returnorderList.php?res=2&mod=3&msg=".$err);
    }
    
    $conn->close();
}
?>