<?php

session_start();
require "./conn.php";
include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');
$usr=$_SESSION["user"];

extract($_POST);
$supervisor = $_POST["cmbempnm"];
//print_r($_POST);die;

if($postaction == "Create"){
$qryMain = "INSERT INTO `resourceplan`(`doid`, `type`, `machinary`, `equipment`, `supervisor`, `labor_qty`, `delivery_start`, `delivery_end`) 
                            VALUES ('".$doId."','".$plantype."','".$machinary."','".$equipment."','".$supervisor."','".$laborQty."',STR_TO_DATE('".$start_dt."', '%d/%m/%Y %h:%i %p'),STR_TO_DATE('".$end_dt."', '%d/%m/%Y %h:%i %p'))";
}else{
    $qryMain ="UPDATE `resourceplan` SET `type`='".$plantype."',`machinary`='".$machinary."',`equipment`='".$equipment."',
                `supervisor`='".$supervisor."',`labor_qty`='".$laborQty."',`delivery_start`= STR_TO_DATE('".$start_dt."', '%d/%m/%Y %h:%i %p'),`delivery_end`=STR_TO_DATE('".$end_dt."', '%d/%m/%Y %h:%i %p'),
                `acknowledgement`='0',`st`='1' WHERE `doid` = '".$doId."'";
}
if ($conn->query($qryMain) == TRUE) {
    if($postaction == "Create"){
        $resourceId = $conn->insert_id;
        
        //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = 45";
                      $resultmail = $conn->query($qrymail);
                      while($rowmail = $resultmail->fetch_assoc()){
                          $active = $rowmail["active"];
                          $emailid = $rowmail["id"];
                        if($active == 1){
                              $recipientNames = array();
                              $recipientEmails = array();
                              //To Supervisor
                              $qrySendToSup = "SELECT emp.office_email, concat(emp.firstname, ' ', emp.lastname) empname 
                                            FROM employee emp WHERE emp.id = ".$emailid;
                              $resultSendToSup = $conn->query($qrySendToSup);
                              while($rowstsup = $resultSendToSup->fetch_assoc()){
                                  $recipientNames[] = $rowst["empname"];
                                  $recipientEmails[] = $rowst["office_email"];
                              }
                              
                              $ccEmails = array();
                              $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                                            FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                              $resultSendTo = $conn->query($qrySendTo);
                              while($rowst = $resultSendTo->fetch_assoc()){
                                  if($rowst["type"] == 2){
                                      $ccEmails[] = $rowst["office_email"];
                                  }
                              }
                              if (!empty($recipientEmails)) {
                                  $mailsubject = "New Task assigned";
                
                                  $message = "A new delivery $doId was assigned to you for $start_dt";
                                            
                                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                              }
                        }
                      } 
    }else{
        $qryInfo="SELECT `id` FROM `resourceplan` WHERE doid = '".$doId."'";
        $resultInfo = $conn->query($qryInfo);
        while ($rowinfo = $resultInfo->fetch_assoc()) {
            $resourceId = $rowinfo["id"];
        }
        
        //Delete Assign Transportation
        $qry = "DELETE FROM `assign_transportation` WHERE `resourceid` = ".$resourceId;
        $conn->query($qry);
        
        //Delete Logistic Team
        $qry = "DELETE FROM `assign_logistic_team` WHERE `resourceid` = ".$resourceId;
        $conn->query($qry);
        
        //Delete Technical Team
        $qry = "DELETE FROM `assign_technical_team` WHERE `resourceid` = ".$resourceId;
        $conn->query($qry);
        
        //Delete QA Team
        $qry = "DELETE FROM `assign_qa_team` WHERE `resourceid` = ".$resourceId;
        $conn->query($qry);
        
        //Delete Technical Team
        $qry = "DELETE FROM `assign_other_team` WHERE `resourceid` = ".$resourceId;
        $conn->query($qry);
    }
    
    if (is_array($transtype)){
        for ($i=0;$i<count($transtype);$i++){
            $transtypeval = $transtype[$i];
            $tqty = $transqty[$transtypeval]; $tid = $transtypeid[$transtypeval];
            
            $qrytransportation = "INSERT INTO `assign_transportation`(`resourceid`, `trid`, `qty`) 
                                                            VALUES ('".$resourceId."','".$tid."','".$tqty."')";
            $conn->query($qrytransportation);
        }
    }
    
    if (is_array($logisticTeam)){
        for ($i=0;$i<count($logisticTeam);$i++){
            $logisticTeamVal = $logisticTeam[$i];
            
            $qryLogistic = "INSERT INTO `assign_logistic_team`(`resourceid`, `logisticteamid`)
                                                        VALUES ('".$resourceId."','".$logisticTeamVal."')";
            $conn->query($qryLogistic);
        }
    }
    
    if (is_array($techTeam)){
        for ($i=0;$i<count($techTeam);$i++){
            $techTeamVal = $techTeam[$i];
            
            $qryTech = "INSERT INTO `assign_technical_team`(`resourceid`, `empid`)
                                                VALUES ('".$resourceId."','".$techTeamVal."')";
            $conn->query($qryTech);
        }
    }
    
    if (is_array($qateam)){
        for ($i=0;$i<count($qateam);$i++){
            $qateamVal = $qateam[$i];
            
            $qryQa = "INSERT INTO `assign_qa_team` (`resourceid`, `empid`)
                                                VALUES ('".$resourceId."','".$qateamVal."')";
            $conn->query($qryQa);
        }
    }
    
    if (is_array($otherteam)){
        for ($i=0;$i<count($otherteam);$i++){
            $otherteamVal = $otherteam[$i];
            
            $qryOther = "INSERT INTO `assign_other_team` (`resourceid`, `empid`)
                                                VALUES ('".$resourceId."','".$otherteamVal."')";
            $conn->query($qryOther);
        }
    }
    
    $qryUpdateDo = "UPDATE `delivery_order` SET `resourceplan`='2' WHERE `do_id` = '".$doId."'";
    $conn->query($qryUpdateDo);
    
    $err = "Resource Plan Successful";
    header("Location: ".$hostpath."/pendingDeliveryList.php?mod=16&res=1&msg=".$err);
    
}else{
    $err = "Something went wrong!";
    header("Location: ".$hostpath."pendingDeliveryList.php?mod=16&res=1&msg=".$err);
}

?>