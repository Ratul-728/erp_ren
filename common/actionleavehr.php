<?php
require "conn.php";
include_once('./email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

session_start();

// ini_set('display_errors', 1);
//  error_reporting(E_ALL);

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
        $action = $_POST['action'];
        
        $comments = $_POST['comments'];
        
        $id = $_POST["itid"];
        
        $hrid= $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
        
        if($action == 0){
            $qry = "UPDATE `leave` SET `hrdaction`='0',`hrdcomments`='$comments',`hrdactiondate`=sysdate(), `st`='0' WHERE id = ".$id;
        }else{
            $qry = "UPDATE `leave` SET `hrdaction`='1',`hrdcomments`='$comments',`hrdactiondate`=sysdate(), `st`='4' WHERE id = ".$id;
        }
         
        $err="Action successfully";
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
        //Early leave
        if($action == 1){
            $qryLeave = "SELECT h.attendance_id, l.`leavetype`, l.`applieddate`, l.`hrid` FROM `leave` l LEFT JOIN hr h ON h.id=l.hrid WHERE l.id = ".$id;
            $resultLeave = $conn->query($qryLeave); 
            while($rowLeave = $resultLeave->fetch_assoc()){
                $applyempid = $rowLeave["attendance_id"];
                $leavetype = $rowLeave["leavetype"];
                $applydt = $rowLeave["applieddate"];
                $empid  = $rowLeave["hrid"];
                
                if($leavetype == 10){
                    $qryAttendance = "UPDATE `attendance_test` SET `early_leave`='1' WHERE hrid = '$applyempid' and date = '$applydt'";
                    $conn->query($qryAttendance);
                }else{
                    $qryGetInfo = "SELECT CASE WHEN startday = endday THEN 1 ELSE DATEDIFF(endday, startday) + 1 END AS total_days, YEAR(startday) yr
                                    FROM `leave` WHERE id = ".$id;
                    $resultInfo = $conn->query($qryGetInfo); 
                    while($rowInfo = $resultInfo->fetch_assoc()){
                        $total_days = $rowInfo["total_days"];
                        $year = $rowInfo["yr"];
                        
                        $updateLeave = "UPDATE `leave_available` SET `remaining_days`= `remaining_days` - $total_days 
                                        WHERE `hrid` = $empid AND `year` = $year AND `leave_type` = $leavetype";
                        $conn->query($updateLeave);
                    }
                }
            }
        }
        
                    //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = 54";
                    $resultmail = $conn->query($qrymail);
                    while($rowmail = $resultmail->fetch_assoc())
                    {
                        $active = $rowmail["active"];
                        $emailid = $rowmail["id"];
                        if($active == 1)
                        {
                            //Employee Mail
                            $qrymailinfo = "SELECT concat(emp.firstname, ' ', emp.lastname) empnp, emp.office_email, lt.title leavetype, 
                                            DATE_FORMAT(l.startday, '%d/%b/%Y') startdate, DATE_FORMAT(l.endday, '%d/%b/%Y') enddate
                                            FROM `leave` l LEFT JOIN hr h ON h.id=l.reliver LEFT JOIN employee emp ON emp.employeecode=h.emp_id 
                                            LEFT JOIN leaveType lt ON lt.id=l.leavetype
                                            WHERE l.id = ".$id;
                            $resultmailinfo = $conn->query($qrymailinfo);
                            while($rowmi = $resultmailinfo->fetch_assoc()){
                                $name_to = $rowmi["empnp"];
                            	$email_to = $rowmi["office_email"];
                            	$leavetype = $rowmi["leavetype"];
                            	$startdate = $rowmi["startdate"];
                            	$enddate = $rowmi["enddate"];  if($enddate == "") $enddate = $startdate;
                            }
                            
                            //Reliver Mail
                            $qrymailinfo = "SELECT concat(emp.firstname, ' ', emp.lastname) empnp, emp.office_email 
                                            FROM `leave` l LEFT JOIN hr h ON h.id=l.reliver LEFT JOIN employee emp ON emp.employeecode=h.emp_id 
                                            WHERE l.id = ".$id;
                            $resultmailinfo = $conn->query($qrymailinfo);
                            while($rowmi = $resultmailinfo->fetch_assoc()){
                                $reliver_to = $rowmi["empnp"];
                            	$reliver_to = $rowmi["office_email"];
                            }
                            
                            $recipientNames = array();
                            $recipientEmails = array();
                            $ccEmails = array();
                            $recipientEmails[] = $email_to;
                            $recipientNames[] = $name_to;
                            
                            $ccEmails[] = $reliver_to;
                            
                            $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                                    FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                            $resultSendTo = $conn->query($qrySendTo);
                            while($rowst = $resultSendTo->fetch_assoc())
                            {
                                if($rowst["type"] == 2 && $rowst["office_email"] != "")
                                {
                                    $ccEmails[] = $rowst["office_email"];
                                }
                            }
                            
                            if($action == 1){
                                $mailsubject = "Leave request Approved";
                                $message = "Your request for $leavetype leave from $startdate to $enddate was accepted. <br>
                                            For any queries, please contact Management Services Dept. ";
                            }else{
                                $mailsubject = "Leave request Denied";
                                $message = "Your request for $leavetype leave from $startdate to $enddate was denied. <br>
                                        For any queries, please contact Management Services Dept. ";
                            }
                                    
                            sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                        }
                    }
                header("Location: ".$hostpath."/leave_hr.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: <br>" . $conn->error;
          header("Location: ".$hostpath."/leave_hr.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>