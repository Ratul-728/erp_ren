<?php
 require_once("../common/conn.php");
 
 include_once('email_config.php');
 include_once('../email_messages/email_user_message.php');
 require_once('phpmailer/PHPMailerAutoload.php');
 
 session_start();
 $usr = $_SESSION["user"];

if ( isset( $_POST['update'] ) ) 
{
   
    $iid= $_POST['order_id'];
    $appqtys = $_POST['appqty'];
    $tsds    = $_POST["tsd"];
    $productIds = $_POST["productid"];
    $tobranchs = $_POST["tobranch"];
    $flag= false;
    
    $acceptedIds = [];
    $acceptedQtys = [];
    $pids = [];
    
    if (is_array($appqtys))
        {
            
            $tot=0;
            for ($i=0;$i<count($appqtys);$i++)
            {
                $tsd = $tsds[$i]; $appqty = $appqtys[$i]; $productId = $productIds[$i];
                
                if($appqty == '') $appqty = 0;
                if($appqty > 0){
                    $flag = true;
                    array_push($acceptedIds, $tsd);
                    array_push($acceptedQtys, $appqty);
                    array_push($pids, $productId);
                }
                
                $itqry="UPDATE `issue_order_details` SET `approval_qty`='$appqty' WHERE id = ".$tsd;
                //echo $itqry;die;
                if ($conn->query($itqry) == TRUE) { $err="SOItem added successfully";  }
            }
           
        }
}

if($flag){
    $qry = "UPDATE `issue_order` SET `st`='2', approved_by = '$usr' WHERE id = ".$iid;
    $conn->query($qry);
    
    //Insert In qa
    for ($i=0;$i<count($acceptedIds);$i++){
        $aid = $acceptedIds[$i]; $aqty = $acceptedQtys[$i]; $pid = $pids[$i];
        
        $qryQa = "INSERT INTO `qa`(`type`, `product_id`, `quantity`, `date_iniciated`, `status`, `order_id`) 
                            VALUES ('5','$pid','$aqty',sysdate(),'1','$iid')";
        $conn->query($qryQa);  $qaid = $conn -> insert_id;
        
        $qryQaw = "INSERT INTO `qa_warehouse`(`qa_id`, `qa_type`, `warehouse_id`, `ordered_qty`) 
                            VALUES ('$qaid','5','9','$aqty')";
        $conn->query($qryQaw);
    }
    
    //Mail to Management
            $qrymail = "SELECT id,active FROM `email` WHERE id = 31";
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
                    $mailsubject = "New Issue items Quality check requested";
                    $message = " New Quality check request  for $ioid  was received.";
                            
                    sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                }
            }
    
    $err="Successfully Accepted";
    
    header("Location: ".$hostpath."/approval_issue_order.php?res=1&msg=".$err."&mod=24");
}else{
    $qry = "UPDATE `issue_order` SET `st`='0', approved_by = '$usr' WHERE id = ".$iid;
    $conn->query($qry);
    $err="Successfully Declined";
    header("Location: ".$hostpath."/approval_issue_order.php?res=2&msg=".$err."&mod=24");
}
   
$conn->close();
?>