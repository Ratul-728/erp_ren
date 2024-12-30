<?php
require "conn.php";
session_start();
include_once('../rak_framework/fetch.php');
include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

$usr = $_SESSION["user"];

extract($_POST);

if ( $postaction == 'Add') {
        
        $ioid = getFormatedUniqueID('issue_order','id','IO-',6,"0");
        $not=str_replace("'","`",$note);
        $qryso = "INSERT INTO `issue_order`(`ioid`, `iodt`,`deliverydt`,issue_warehouse, `makeby`, `makedt`,  `note`)
                                    VALUES ('$ioid',STR_TO_DATE('".$io_dt."', '%d/%m/%Y'),STR_TO_DATE('".$delivery_dt."', '%d/%m/%Y'),'$war_id','$usr',sysdate(), '$not')";
            
           // echo $qryso;die;
            if ($conn->query($qryso) == TRUE) {
                $io = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/ioList.php?res=2&mod=12&msg=".$err);
                die;
            } 
            
         if (is_array($itemid))
            {
                for ($i=0;$i<count($itemid);$i++)
                    {
                        $product = $itemid[$i]; $from = $fromstore[$i]; $qty = $quantity_otc[$i];
                     
                        $inqrysod="INSERT INTO `issue_order_details`(`ioid`, `product`, `frombranch`, `qty`) 
                                                            VALUES ('$io','$product','$from','$qty')";
                        $conn->query($inqrysod);
                        
                         
                    }
            }
        
        $err = "Record created successfully";
        
        //Mail to Management
        $qrymail = "SELECT id,active FROM `email` WHERE id = 35";
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
                $mailsubject = "Approval Required for Issue Order";
                $message = "An approval request for Issue Order $ioid was received.";
                            
                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
            }
        }
    }
    
    header("Location: ".$hostpath."/ioList.php?res=1&mod=12&msg=".$err);
?>