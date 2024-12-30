<?php
require "conn.php";
session_start();

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');

$hrid = $_SESSION["user"];

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/deal.php?res=01&msg='New Entry'&id=''");
}
else
{
        
        
        $did= $_REQUEST['itid'];
       // echo $dealid;die;
        $item = $_POST['itemName'];
        $req_approve_qty = $_POST['req_approve_qty'];
        $req_quantity = $_POST["req_quantity"];
        //$action = $_POST["action"];
        //print_r($_POST);die;
        $mainst = 0;
         if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $itmsl=$i+1;$req_det_id=$item[$i];$approve_qty=$req_approve_qty[$i]; 
                        $reqqty = $req_quantity[$i];
                        //echo "Request qty: ".$reqqty; echo "Approved qty: ".$approve_qty; die;
                        if($reqqty == $approve_qty){
                            $st = 2;
                        }else if($approve_qty == 0){
                            $st = 0;
                        }else if($reqqty < $approve_qty){
                            $err = "Approved quantity is greater than request quantity";
                            header("Location: ".$hostpath."/pending_requisitionList.php?mod=14&res=2&msg=".$err."");
                        }else{
                            $st = 3;
                        }
                        
                        $mainst += $st;
                        
                        $itqry="UPDATE `requision_details` SET `approved_qty`= '$approve_qty',`approver`='$hrid', status = '$st' WHERE id = ".$req_det_id;
                         //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="Action successfully";  }else{$errorFlag = false;}
                         
                    }
            }
        
        if($mainst == count($item)*2){
            $status = 2;
        }else if($mainst == 0){
            $status = 0;
        }else{
            $status = 3;
        }
        
        $qry = "UPDATE `requision` SET `status`= '$status' WHERE id = ".$did;
        
        
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
        $last_id = $conn->insert_id;
        /*//Mail
        if ( isset( $_POST['add'] ) ){
            $qrymail = "SELECT concat(emp.firstname, ' ' ,emp.lastname) hrnm, emp.office_email FROM `hraction` hr LEFT JOIN `employee` emp ON hr.`hrid` = emp.id WHERE `postingdepartment` = 23";
            $resultmail = $conn->query($qrymail);
            while($rowmail = $resultmail->fetch_assoc()){
                $name_to = $rowmail["hrnm"];
                $email_to = $rowmail["office_email"];
                
                $mailsubject = "New Lead: $nm";

                $message = "<b>Dear $name_to,</b><br>
                        Good News! New Lead has been created.<br><br>
                        
                        <b>Title: $nm </b>.<br>
                        Description: $details. <br><br>
                        
                        Kindly review it from your profile.<br><br>
                        
                        <b>Thanks,<br>
                        Bitflow System</b><br>
                ";
                
                    //echo $email_to;die;        
                            
                	
                //sendBitFlowMail($name_to,$email_to, $mailsubject,$message);
            }
            
        }*/
            header("Location: ".$hostpath."/pending_requisitionList.php?res=1&msg=".$err."&mod=14&pg=1");
        
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/pending_requisitionList.php?mod=14&res=2&msg=".$err."");
    }
    
    $conn->close();
}
?>