<?php
require "conn.php";
session_start();
// error_reporting(E_ALL); ini_set('display_errors', 1);

$res = $_GET["res"];
$type = $_GET["type"];
$usr = $_SESSION["user"];
// print_r($_POST);die;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
      die;
}
else
{
    if ( isset( $_POST['add'] ) || $res == 2 ) {
        
        //Appreciation
        if($type == 3){
            $empid= $_POST['empid'];
            $remarks = $_POST["remarks1"];
            $action_dt = $_POST['action_dt'];
            $effective_to = $_POST["effective_to"];
            
            $qry = "INSERT INTO `hraction`(`hrid`, `type`, `actiondt`, `effective_to`, `remarks`, `makeby`, `makedt`) 
                                VALUES ('$empid','$type',STR_TO_DATE('".$action_dt."', '%d/%m/%Y'),STR_TO_DATE('".$effective_to."', '%d/%m/%Y'),'$remarks', '$usr', sysdate())";
             $err="Hr Action successful";
        }
        //Punishment
        if($type == 2){
            $empid= $_POST['empid'];
            $remarks = $_POST["remarks1"];
            $action_dt = $_POST['action_dt'];
            $effective_to = $_POST["effective_to"];
            
            $qry = "INSERT INTO `hraction`(`hrid`, `type`, `actiondt`,`effective_to`, `remarks`, `makeby`, `makedt`) 
                                VALUES ('$empid','$type',STR_TO_DATE('".$action_dt."', '%d/%m/%Y'),STR_TO_DATE('".$effective_to."', '%d/%m/%Y'),'$remarks', '$usr', sysdate())";
             $err="Hr Action successful";
        }
        //Posting
        if($type == 1){
            $empid= $_POST['empid'];
            $acty = $_POST["acty"];
            $remarks = $_POST["remarks1"];
            $action_dt = $_POST['action_dt'];
            $effective_to = $_POST["effective_to"];
            $dept = $_POST['dept']; 
            $desig = $_POST['desig'];
            $jobarea = $_POST['jobarea']; 
            $jobtype = $_POST['jobtype'];
            $repto = $_POST['repto'];              
            
            $qry="INSERT INTO `hraction`(`hrid`, `type`, `actiontype`, `actiondt`,`effective_to`, `postingdepartment`, `jobarea`, `designation`, `reportto`, `jobtype`,`remarks`, `makedt`, `makeby`) 
              VALUES ('".$empid."','".$type."','".$acty."',STR_TO_DATE('".$action_dt."', '%d/%m/%Y'),STR_TO_DATE('".$effective_to."', '%d/%m/%Y'),'".$dept."','".$jobarea."','".$desig."','".$repto."', '".$jobtype."','$remarks', sysdate(), '".$usr."')" ;
            $err="Hr Action successful";
        
        }
        
    }
    if ( isset( $_POST['update'] ) ) {
        $iid = $_REQUEST["iid"];
        if($type == 1){
            $empid= $_POST['empid'];
            $acty = $_POST["acty"];
            $remarks = $_POST["remarks1"];
            $action_dt = $_POST['action_dt'];
            $effective_to = $_POST["effective_to"];
            $dept = $_POST['dept']; 
            $desig = $_POST['desig'];
            $jobarea = $_POST['jobarea']; 
            $jobtype = $_POST['jobtype'];
            $repto = $_POST['repto']; 
            
            $qry="UPDATE `hraction` SET `hrid`='".$empid."',`actiontype`='".$acty."',`actiondt`=STR_TO_DATE('".$action_dt."', '%d/%m/%Y'),
                 `effective_to` = STR_TO_DATE('".$effective_to."', '%d/%m/%Y'),`postingdepartment`='".$dept."',`jobarea`='".$jobarea."',
                `designation`='".$desig."',`reportto`='".$repto."',`jobtype`='".$jobtype."', `remarks`='$remarks' WHERE id = ".$iid;
                $err="Hr Action updated successfully";
        }
        
        //Punishment & Appreciation 
        if($type == 2 || $type == 3){
            $empid= $_POST['empid'];
            $remarks = $_POST["remarks1"];
            $action_dt = $_POST['action_dt'];
            $effective_to = $_POST["effective_to"];
            
             $qry="UPDATE `hraction` SET `hrid`='".$empid."', `actiondt`=STR_TO_DATE('".$action_dt."', '%d/%m/%Y'),
                 `effective_to` = STR_TO_DATE('".$effective_to."', '%d/%m/%Y'), `remarks`='$remarks' WHERE id = ".$iid;
                $err="Hr Action updated successfully";
        }
    
        
        
      
        //echo $qry;die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    // echo $qry;die;
    if ($conn->query($qry) == TRUE) {
        
        if ( isset( $_POST['add'] )){
            $last_id = $conn->insert_id;
            
            //Upload Image
    
            $file_count = count($_FILES['attachment1']['name']);
            $uploaded_files_count = $file_count;
            
            for ($i = 0; $i < $uploaded_files_count; $i++) {
                    $fileType = $_FILES['attachment1']['type'][$i];
                    $fileSize = $_FILES['attachment1']['size'][$i];
                    $allowedTypes = array('application/pdf', 'image/jpeg', 'image/png');
                    $maxSize = 5 * 1024 * 1024; // 5MB
                    $uploadDir = '../images/upload/documents/';
                    
                    // Check file type
                    if (!in_array($fileType, $allowedTypes)) {
                        $err = 'Error: Only PDF or JPG or PNG files are allowed.';
                        break;
                    }
            
                    // Check file size
                    if ($fileSize > $maxSize) {
                        $err = 'Error: File size exceeds the limit (5MB).';
                        break;
                    }
                    // Using pathinfo to get the file extension
                    $file_name_with_extension = $_FILES['attachment1']['name'][$i];
                    
                    $date = date('Ymd');
                    $filename = $user . $date.'.'.$file_name_with_extension;
            
                    // Move the uploaded file to the destination folder
                    $uploadedFile = $_FILES['attachment1']['tmp_name'][$i];
                    $destination = $uploadDir . $filename;
                   
                    $ftype = $type+9;
                    
                    if (move_uploaded_file($uploadedFile, $destination)) {
                        $qryupload = "INSERT INTO `documents`(`hraction_id`, `empid`, `filename`, `ftype`, `makeby`, `makedt`) 
                                                    VALUES ('$last_id','$empid','$filename','$ftype','$usr',sysdate())";
                        $conn->query($qryupload);
                        //return $destination;
                        
                    } else {
                        $err = 'Error: Failed to upload the file.';
                        break;
                    }
            }
        }else{
            $last_id = $iid;
            
            //Upload Image
    
            $file_count = count($_FILES['attachment1']['name']);
            $uploaded_files_count = $file_count;
            
            for ($i = 0; $i < $uploaded_files_count; $i++) {
                    $fileType = $_FILES['attachment1']['type'][$i];
                    
                    //Check is file upload
                    if($fileType == ""){
                        continue;
                    }
                    $fileSize = $_FILES['attachment1']['size'][$i];
                    $allowedTypes = array('application/pdf', 'image/jpeg', 'image/png');
                    $maxSize = 5 * 1024 * 1024; // 5MB
                    $uploadDir = '../images/upload/documents/';
                    
                    // Check file type
                    if (!in_array($fileType, $allowedTypes)) {
                        $err = 'Error: Only PDF or JPG or PNG files are allowed.';
                        break;
                    }
            
                    // Check file size
                    if ($fileSize > $maxSize) {
                        $err = 'Error: File size exceeds the limit (5MB).';
                        break;
                    }
                    // Using pathinfo to get the file extension
                    $file_name_with_extension = $_FILES['attachment1']['name'][$i];
                    
                    $date = date('Ymd');
                    $filename = $user . $date.'.'.$file_name_with_extension;
            
                    // Move the uploaded file to the destination folder
                    $uploadedFile = $_FILES['attachment1']['tmp_name'][$i];
                    $destination = $uploadDir . $filename;
                   
                    $ftype = $type+9;
                    
                    if (move_uploaded_file($uploadedFile, $destination)) {
                        $qryupload = "INSERT INTO `documents`(`hraction_id`, `empid`, `filename`, `ftype`, `makeby`, `makedt`) 
                                                    VALUES ('$last_id','$empid','$filename','$ftype','$usr',sysdate())";
                        $conn->query($qryupload);
                        //return $destination;
                        
                    } else {
                        $err = 'Error: Failed to upload the file.';
                        break;
                    }
            }
        }
        
        if($type == 1){
            $qryUpdateEmp = "UPDATE `employee` SET `department`='$dept',`designation`='$desig' WHERE id = ".$empid;
            $conn->query($qryUpdateEmp);
            
            $qryGetAT = "SELECT active FROM `ActionType` WHERE id = ".$acty;
            $resultGetAT = $conn->query($qryGetAT);
            while ($rowGetAT = $resultGetAT->fetch_assoc()) {
                $activeSt = $rowGetAT["active"];
            }
            //Hr ID
            $qryHr = "SELECT a.id From hr a LEFT JOIN employee emp ON emp.employeecode = a.emp_id where emp.id = ".$empid;
            $resultHr = $conn->query($qryHr);
            while ($rowHr = $resultHr->fetch_assoc()) {
                $hrId =$rowHr["id"];
            }
            //Update Hr table
            $qryUpdate = "UPDATE `hr` SET `active_st`= $activeSt WHERE id = ".$hrId;
            $conn->query($qryUpdate);
            
            if($acty == 1){
                $qrygetleave = "SELECT * FROM `leave_available` WHERE hrid = $hrId AND leave_type = 5 AND `year` = YEAR(CURDATE())";
                $resultGetLeave = $conn->query($qrygetleave);
                while ($rowGL = $resultGetLeave->fetch_assoc()) {
                    $totLeavedays = $rowGL["total_days"];
                    $totremainingday = $rowGL["remaining_days"];
                }
                
                if($totLeavedays == ""){
                    $qryInsertLeave = "INSERT INTO `leave_available`( `hrid`, `year`, `leave_type`, `total_days`, `remaining_days`, `makedt`) 
                                                            VALUES ('$hrId',YEAR(CURDATE()),'5','14','14',sysdate())";
                    $conn->query($qryInsertLeave);
                }
            }
            if($acty == 6){
                $qrygetleave = "SELECT * FROM `leave_available` WHERE hrid = $hrId AND leave_type = 2 AND `year` = YEAR(CURDATE())";
                $resultGetLeave = $conn->query($qrygetleave);
                while ($rowGL = $resultGetLeave->fetch_assoc()) {
                    $totLeavedays = $rowGL["total_days"];
                    $totremainingday = $rowGL["remaining_days"];
                }
                
                if($totLeavedays == ""){
                    $qryInsertLeave = "INSERT INTO `leave_available`( `hrid`, `year`, `leave_type`, `total_days`, `remaining_days`, `makedt`) 
                                                            VALUES ('$hrId',YEAR(CURDATE()),'2','10','10',sysdate())";
                    $conn->query($qryInsertLeave);
                }
            }
        }
        $qryhis = "INSERT INTO `hractionHist`( `hsid`, `hsdt`, `hrid`, `type`,`actiontype`, `actiondt`, `postingdepartment`, `jobarea`, `designation`, `reportto`, `jobtype`, `makedt`, `makeby`, `st`) 
                    VALUES ('$last_id',sysdate(),'$empid','$type','$acty',STR_TO_DATE('".$action_dt."', '%d/%m/%Y'),'$dept','$jobarea', '$desig', '$repto', '$jobtype', sysdate(), '$usr', '1')";
        $conn->query($qryhis);
        if($res == 2){
            header("Location: ".$hostpath."/employee_hr.php?res=4&msg='Successfully Updated'&id=".$empid."&mod=4&ss=1");
            die;
        }
        else{
                header("Location: ".$hostpath."/hractionList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
                die;
        }
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
         if($res == 2){
            header("Location: ".$hostpath."/employee_hr.php?res=4&msg=".$err."&id=".$empid."&mod=4&ss=1");
            die;
        }
        else{
            
          header("Location: ".$hostpath."/hractionList.php?res=2&msg=".$err."&id=''&mod=4");
          die;
        }
    }
    
    $conn->close();
}
?>