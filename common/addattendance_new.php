<?php
require "conn.php";
$hrid = $_POST['usrid'];
$err = '';
    if ( isset( $_POST['add'] ) ) 
    {
        extract($_POST);
        //Check already Insert data for this day
    	$qryExist = "SELECT * FROM `attendance_test` WHERE hrid = '".$cmbempnm."' and date = STR_TO_DATE('".$attendance_date."', '%d/%m/%Y')";
        $resultExist = $conn->query($qryExist); 
        if ($resultExist->num_rows > 0){
            while($rowExist = $resultExist->fetch_assoc()){
                if($intime == '') $intime = $rowExist["intime"];
                if($outtime == '') $outtime = $rowExist["outtime"];
                $atid = $rowExist["id"];
            }
            
            $qry = "UPDATE `attendance_test` SET `intime`='$intime',`outtime`='$outtime',`attendance_type`='$type',
                `early_leave`='$early', `remarks` = '$remarks1' WHERE `id` = ".$atid;
            $err="Attendance updated successfully";
        }
        else{
            $qry = "INSERT INTO `attendance_test`(`hrid`, `date`, `intime`, `outtime`,`attendance_type`, `early_leave`, `remarks`)
                                        VALUES ('$cmbempnm',STR_TO_DATE('".$attendance_date."', '%d/%m/%Y'),'$intime','$outtime','$type', '$early', '$remarks1')";
                                        
            $err="Attendance added successfully";
        }
        
    }
    if ( isset( $_POST['update'] ) ) {
        
        extract($_REQUEST);
        
        $qry = "UPDATE `attendance_test` SET `hrid`='$cmbempnm',`date`=STR_TO_DATE('".$attendance_date."', '%d/%m/%Y'),`intime`='$intime',`outtime`='$outtime',`attendance_type`='$type',
                `early_leave`='$early' WHERE `id` = ".$atid;
                
        $err="Attendance updated successfully";
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    if($conn->query($qry) == TRUE)
    {
        header("Location: ".$hostpath."/rpt_attendance_all.php?res=1&msg=".$err."&id=".$poid."&mod=4&pg=4");
       
    }
     else
    {
        $err="Error: " . $conn->error;
        header("Location: ".$hostpath."/rpt_attendance_all.php?res=2&msg=".$err."&id=''&mod=4");
       
    }
    
    $conn->close();

?>