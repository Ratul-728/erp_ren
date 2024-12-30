<?php
require "../common/conn.php";
session_start();
$uid = $_SESSION["user"];
$value = $_POST["value"];
      //Attandence
      $getdate = new DateTime('now', new DateTimeZone('Asia/dhaka'));
      $date = $getdate->format('Y-m-d');
      $time = $getdate->format("H:i:s");
      
      if($value == 'intime'){
                //Get Shift if exist
    			$assignshift = false;
    			
    			$qryShift = "SELECT ot.latetime, ot.absent FROM `assignshifthist` asl LEFT JOIN employee emp ON asl.empid=emp.id LEFT JOIN hr h ON emp.employeecode=h.emp_id 
    			            LEFT JOIN Shifting s ON asl.shift=s.id LEFT JOIN OfficeTime ot ON ot.shift=s.id 
    			            
    			            WHERE h.id = '".$uid."' and asl.effectivedt = '".$date."'";
    			$resultShift = $conn->query($qryShift); 
    			 while($rowShift = $resultShift->fetch_assoc()) {
    			     $assignshift = true;
    			     $shiftLate = strtotime($rowShift["latetime"]);
    			     $shiftAbsent = strtotime($rowShift["absent"]);
    			     
    			 }
    			//General shift
    			if(!$assignshift){
    			    $qryGeneralShift = "SELECT ot.latetime, ot.absent FROM OfficeTime ot WHERE ot.general = 1";
    			    $resultGeneralShift = $conn->query($qryGeneralShift); 
        			 while($rowGeneralShift = $resultGeneralShift->fetch_assoc()) {
        			     $assignshift = true;
        			     $shiftLate = strtotime($rowGeneralShift["latetime"]);
        			     $shiftAbsent = strtotime($rowGeneralShift["absent"]);
        			     
        			 }
    			}
    			$time2 = strtotime($time);
    			
                if($time2 >= $shiftAbsent){
                    $st = 0;
                }
                else if($time2 >= $shiftLate){ 
                    $st = 2;
                }else{
                    $st = 1;
                }
                
        $qryatt = "INSERT INTO `attendance_test`(`hrid`, `date`, `intime`, `attendance_type`) VALUES (".$uid.",'".$date."','".$time."','".$st."')";
      }else{
          $qryatt = "UPDATE `attendance_test` SET `outtime`='$time' WHERE `hrid` = '$uid' AND `date` = '$date'";
      }
      $conn->query($qryatt);
      
      if($value == 'intime'){
          echo "Successfully Checked In!";
      }else {
          echo "Successfully Checked Out!";
      }

?>