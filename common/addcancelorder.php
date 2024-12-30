<?php
require "conn.php";
session_start();

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
    if ( isset( $_POST['update'] ) )
    {
        
        $returnqty = $_POST['returnqty'];           //if($ddt==''){$ddt='NULL';}
        $orderId = $_POST["order"];
        $productIds = $_POST["productid"];
        $orderqtys = $_POST['orderQtyPer'];  
         
        $errorFlag = 0;
        $flag = 0;
        
        $flag=count($returnqty);    
        
        $rocode = getFormatedUniqueID('cancel_order','id','CO-',6,"0");
        //echo   $rocode;die;  
         if ($flag>0)
            {
                for ($i=0;$i<$flag;$i++)
                    {
                        $cancelQty = $returnqty[$i];  $productId=$productIds[$i];$orderqty=$orderqtys[$i];
                        if($orderqty>=$cancelQty)
                        {
                            if($cancelQty > 0){
                                $itqry="INSERT INTO `cancel_order`( `co_id`, `order_id`, `productid`, `qty_canceled`, `makeby`, `makedt`) 
                                                                VALUES ('$rocode','$orderId',$productId,$cancelQty,$usr,sysdate())";
                                 //echo $itqry;die;
                                 if ($conn->query($itqry) == TRUE) { $err="Return added successfully";  }
                                 else{ $errorFlag++;}
                            }
                        }
                        else
                        {
                            $err="ERROR:cancel qty must less than or equal to order qty"; 
                        }
                         
                    }
            }

    }
   
    
    if ($errorFlag == 0) {
        
        //Mail to Management
        $qrymail = "SELECT id,active FROM `email` WHERE id = 41";
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
                $mailsubject = "Approval Required for Cancel Order";
                $message = "An approval request for Cancel Order $rocode was received.";
                            
                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
            }
        }
    
        $err = "Record created successfully";
        header("Location: ".$hostpath."/cancelorderList.php?res=1&mod=3&msg=".$err);
            
    } else {
        
        $err="Error:" . $conn->error;
        header("Location: ".$hostpath."/cancelorderList.php?res=2&mod=3&msg=".$err);
    }
    
    $conn->close();
}
?>