<?php
 require_once("../common/conn.php");
 
 include_once('email_config.php');
 include_once('../email_messages/email_user_message.php');
 require_once('phpmailer/PHPMailerAutoload.php');
 
 session_start();

$usr = $_SESSION["user"];
if ( isset( $_POST['update'] ) ) 
{
   
    $tid= $_POST['order_id'];
    $appqtys = $_POST['appqty'];
    $tsds    = $_POST["tsd"];
    $productIds = $_POST["productid"];
    $tobranchs = $_POST["tobranch"];
    $flag= false;
    $acceptedIds = [];
    $acceptedQtys = [];
    $pids = [];
    $tobrs = [];
    
    if (is_array($appqtys))
        {
            
            $tot=0;
            for ($i=0;$i<count($appqtys);$i++)
            {
                $tsd = $tsds[$i]; $appqty = $appqtys[$i]; $productId = $productIds[$i]; $tobr = $tobrs[$i];
                
                if($appqty == '') $appqty = 0;
                if($appqty > 0){
                    $flag = true;
                    array_push($acceptedIds, $tsd);
                    array_push($acceptedQtys, $appqty);
                    array_push($pids, $productId);
                    array_push($tobrs, $tobr);
                }
                
                $itqry="UPDATE `transfer_stock_details` SET `approval_qty`='$appqty' WHERE id = ".$tsd;
                //echo $itqry;die;
                if ($conn->query($itqry) == TRUE) { $err="SOItem added successfully";  }
            }
           
        }
}

if($flag){
    $qry = "UPDATE `transfer_stock` SET `st`='2', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$tid;
    $conn->query($qry);
    
    //Insert In qa
    for ($i=0;$i<count($acceptedIds);$i++){
        $aid = $acceptedIds[$i]; $aqty = $acceptedQtys[$i]; $pid = $pids[$i]; $tbr = $tobrs[$i];
        
        $qryQa = "INSERT INTO `qa`(`type`, `product_id`, `quantity`, `date_iniciated`, `status`, `order_id`) 
                            VALUES ('4','$pid','$aqty',sysdate(),'1','$tid')";
        $conn->query($qryQa);  $qaid = $conn -> insert_id;
        
        $qryQaw = "INSERT INTO `qa_warehouse`(`qa_id`, `qa_type`, `warehouse_id`, `ordered_qty`) 
                            VALUES ('$qaid','4','$tbr','$aqty')";
        $conn->query($qryQaw);
        $qawid = $conn -> insert_id;
        
        $itqry="UPDATE `transfer_stock_details` SET `qaw_id`='$qawid' WHERE id = ".$aid;
        if ($conn->query($itqry) == TRUE) { $err="SOItem added successfully";  }
    }
    
    //Mail to Management
    $qrymail = "SELECT id,active FROM `email` WHERE id = 30";
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
            $mailsubject = "New Transfer item quality check requested";
            $message = " New Quality check request  for $toid  was received.";
                            
            sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
        }
    }
    
    
    $err="Successfully Accepted";
    header("Location: ".$hostpath."/approval_transfer_stock.php?res=1&msg=".$err."&mod=24");
}else{
    $qry = "UPDATE `transfer_stock` SET `st`='0', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$tid;
    $conn->query($qry);
    $err="Successfully Declined";
    header("Location: ".$hostpath."/approval_transfer_stock.php?res=2&msg=".$err."&mod=24");
}
   
$conn->close();
?>