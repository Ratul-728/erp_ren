<!DOCTYPE html>
<html>
<body>

<!--h1>Attendance Data</h1-->

<?php
    // echo "Load Attendance data </br>";
?>

<?php
 
require "common/conn.php";
session_start();

$usr=$_SESSION["user"];

///**************
$last_id = 51709;  
//$last_id has to load from bitflow table

$qryInfo = "SELECT `Userid`, DATE(`CheckTime`) AS date_part,TIME(`CheckTime`) AS time_part FROM `attendance_from_device` WHERE id > $last_id";
// echo $qryInfo;die;
$resultInfo = $conn->query($qryInfo);
if ($resultInfo->num_rows > 0){
    while($rowInfo = $resultInfo->fetch_assoc()) {
                $userId = $rowInfo["Userid"];
                $date   = $rowInfo["date_part"];
                $time   = $rowInfo["time_part"];
                
                
    	        //Get Shift if exist
    			$assignshift = false;
    			
    			$qryShift = "SELECT ot.latetime, ot.absent, asl.shift FROM `assignshifthist` asl LEFT JOIN employee emp ON asl.empid=emp.id LEFT JOIN hr h ON emp.employeecode=h.emp_id 
    			            LEFT JOIN Shifting s ON asl.shift=s.id LEFT JOIN OfficeTime ot ON ot.shift=s.id 
    			            
    			            WHERE h.attendance_id = '".$userId."' and asl.effectivedt = '".$date."'";
    			
    			$resultShift = $conn->query($qryShift); 
    			 while($rowShift = $resultShift->fetch_assoc()) {
    			     $assignshift = true;
    			     $shiftLate = strtotime($rowShift["latetime"]);
    			     $shiftAbsent = strtotime($rowShift["absent"]);
    			     
    			     $shift = $rowShift["shift"];
    			     if($shift == 6){
    			         $assignshift = false;
    			     }
    			     
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
    			
    			//Check already Insert data for this day
    			$qryExist = "SELECT * FROM `attendance_test` WHERE hrid = '".$userId."' and date = '".$date."'";
                $resultExist = $conn->query($qryExist); 
                if ($resultExist->num_rows > 0){
                    while($rowExist = $resultExist->fetch_assoc()) {
                        $time1 = strtotime($rowExist["intime"]);
                        $time2 = strtotime($time);
                        
                        $st = 1;
                        
                        if ($time1 >= $time2) {
                            
                            if($assignshift){
                                if($time2 >= $shiftAbsent){
                                    $st = 0;
                                }
                                else if($time2 >= $shiftLate){
                                    $st = 2;
                                }else{
                                    $st = 1;
                                }
                            }
                        
                            $qry = "UPDATE `attendance_test` SET `intime`='" . $time . "', `outtime`='" . $rowExist["intime"] . "', `attendance_type` = '$st' 
                                    WHERE id = " . $rowExist["id"];
                        } else {
                            if($assignshift){
                                if($time1 >= $shiftAbsent){
                                    $st = 0;
                                }
                                else if($time1 >= $shiftLate){
                                    $st = 2;
                                }else{
                                    $st = 1;
                                }
                            }
                            $qry = "UPDATE `attendance_test` SET `intime`='" . $rowExist["intime"] . "', `outtime`='" . $time . "', `attendance_type` = '$st'
                                    WHERE id = " . $rowExist["id"];
                        }
                        
                        if ($conn->query($qry) === TRUE) {
                            echo $msg . "<br>";
                            
                        } else {
                            $err = "Error: " . $msg . "<br>" . $conn->error;
                            echo $err; // Output the error for debugging
                        }

                    }
                }else{
                    $st = 1;
                    if($assignshift){
                        $time2 = strtotime($time);
                        if($time2 >= $shiftAbsent){
                            $st = 0;
                        }
                        else if($time2 >= $shiftLate){
                            $st = 2;
                        }else{
                            $st = 1;
                        }
                    }
                    $qry="INSERT INTO `attendance_test`(`hrid`, `date`, `intime`,  `attendance_type`) 
    			                           VALUES ('".$userId."','".$date."','".$time."', '$st')" ;
    			    $msg = "[Insert] user: ".$userId." and date: ".$date."<br>";
    			    if ($conn->query($qry) == TRUE) {
        				echo $msg."<br>";
        			} else {
        			    $err="Error: " . $qry . "<br>" . $conn->error;
        			}
                }
}}

  $conn->close();
  


  //write log when it run;
  
    $file = 'attendance_load2_log.txt';
    $text = "Executed on: " . date("d/m/Y h:i:s A") . "\n";
    
    file_put_contents($file, $text, FILE_APPEND);

  
?>
</body>
</html> 