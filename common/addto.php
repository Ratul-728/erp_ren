<?php
require "conn.php";
session_start();
include_once('../rak_framework/fetch.php');
include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

$usr = $_SESSION["user"];

extract($_POST);

if ( $postaction == 'Transfer Stock') {
        
        $toid = getFormatedUniqueID('transfer_stock','id','TO-',6,"0");
        
        $qryso = "INSERT INTO `transfer_stock`( `toid`, `tansferdt`, `makeby`, `makedt`) 
                    VALUES ('$toid',STR_TO_DATE('".$to_dt."', '%d/%m/%Y'),'$usr',sysdate())";
            
            //echo $qryDeliveryMain;die;
            if ($conn->query($qryso) == TRUE) {
                $to = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/toList.php?res=2&mod=12&msg=".$err);
                die;
            } 
            
         if (is_array($itemid))
            {
                for ($i=0;$i<count($itemid);$i++)
                    {
                        $product = $itemid[$i]; $from = $fromstore[$i]; $tostr = $tostore[$i]; $qty = $quantity_otc[$i];
                     
                        $inqrysod="INSERT INTO `transfer_stock_details`(`toid`, `product`, `from_store`, `to_store`, `qty`) 
                                                            VALUES ('$to','$product','$from','$tostr','$qty')";
                        $conn->query($inqrysod);
                        
                         
                    }
            }
            
        //Mail to Management
        $qrymail = "SELECT id,active FROM `email` WHERE id = 34";
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
                $mailsubject = "Approval Required for Transfer Order";
                $message = "An approval request for Transfer Order $toid was received.";
                            
                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
            }
        }
        
        $err = "Record created successfully";
    }
    
    header("Location: ".$hostpath."/toList.php?res=1&mod=12&msg=".$err);
?>