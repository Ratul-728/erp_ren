<!DOCTYPE html>
<html>
<body>

<h1>Attendance Data</h1>

<?php echo "Load Attendance data"; ?>
</br>
<?php
 
require "common/conn.php";
session_start();

$usr=$_SESSION["user"];

///**************

$dir    = './data/attendance';

$files1 = scandir($dir);

foreach ($files1 as $result) {
    
    if ($result === '.' or $result === '..') continue;
    
    //Checking already read or not
    $qryCh = "SELECT * FROM `attendance_load` WHERE filename = '$result'";
    $resultCh = $conn->query($qryCh);
    if ($resultCh->num_rows > 0){
        while($rowCh = $resultCh->fetch_assoc()) {
            $flag = $rowCh["loaded"];
            $fileid = $rowCh["id"];
        } 
    }else{
        $qryInsert = "INSERT INTO `attendance_load`(`filename`, `loaded`, `makeby`, `makedt`) 
                                            VALUES ('".$result."','0','$usr',sysdate())";
        $conn->query($qryInsert);
        $fileid = $conn->insert_id;
        $flag = 0;
    }
    
    //If not loaded
    if($flag == 0){
    	$filepath=$dir.'/'.$result;
    		
    	if ($file = fopen($filepath, "r")) {
    		while(!feof($file)) {
    	    	$textperline = fgets($file);
    			$data = preg_split('/\s+/', $textperline, -1, PREG_SPLIT_NO_EMPTY);
    			
    			//Get Shift if exist
    			$assignshift = false;
    			
    			$qryShift = "SELECT ot.latetime, ot.absent FROM `assignshifthist` asl LEFT JOIN employee emp ON asl.empid=emp.id LEFT JOIN hr h ON emp.employeecode=h.emp_id 
    			            LEFT JOIN Shifting s ON asl.shift=s.id LEFT JOIN OfficeTime ot ON ot.shift=s.id 
    			            
    			            WHERE h.attendance_id = '".$data["0"]."' and asl.effectivedt = '".$data["1"]."'";
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
    			
    			//Check already Insert data for this day
    			$qryExist = "SELECT * FROM `attendance_test` WHERE hrid = '".$data["0"]."' and date = '".$data["1"]."'";
                $resultExist = $conn->query($qryExist); 
                if ($resultExist->num_rows > 0){
                    while($rowExist = $resultExist->fetch_assoc()) {
                        $time1 = strtotime($rowExist["intime"]);
                        $time2 = strtotime($data["2"]);
                        
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
                        
                            $qry = "UPDATE `attendance_test` SET `intime`='" . $data["2"] . "', `outtime`='" . $rowExist["intime"] . "', `attendance_type` = '$st' 
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
                            $qry = "UPDATE `attendance_test` SET `intime`='" . $rowExist["intime"] . "', `outtime`='" . $data["2"] . "', `attendance_type` = '$st'
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
                        $time2 = strtotime($data["2"]);
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
    			                           VALUES ('".$data["0"]."','".$data["1"]."','".$data["2"]."', '$st')" ;
    			    $msg = "[Insert] user: ".$data["0"]." and date: ".$data["1"]."<br>";
    			    if ($conn->query($qry) == TRUE) {
        				echo $msg."<br>";
        			} else {
        			    $err="Error: " . $qry . "<br>" . $conn->error;
        			}
                }
    		}
    		fclose($file);
    	}
    	//Update for load the file
    	$qryUpdate = "UPDATE `attendance_load` SET `loaded`='1' WHERE id= ".$fileid;
    	$conn->query($qryUpdate);
    }
}

  $conn->close();
?>
</body>
</html> 