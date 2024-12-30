<?php
require "../common/conn.php";
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Ensure that errors are displayed



$dept = $_POST["dept"];
$year = $_POST["year"];
$month = $_POST["month"];

if($month == 12){
    $nextMonth = 1;
    $nextYear = $year +1;
}else{
    $nextMonth = $month + 1;
    $nextYear = $year;
}

$startDate = new DateTime("$year-$month-01");
$endDate = new DateTime("$year-$month-" . date('t', strtotime("$year-$month-01")));
                        
$dateInterval = new DateInterval('P1D'); // Create a DateInterval of 1 day
$dateRange = new DatePeriod($startDate, $dateInterval, $endDate->modify('+1 day')); // Generate a range of dates
                        
$formattedDates = [];
foreach ($dateRange as $date) {
    $formattedDates[] = $date->format('j/M/Y'); // Format and store each date in day/month/year format
}

//For next Month
$startDate = new DateTime("$nextYear-$nextMonth-01");
$endDate = new DateTime("$nextYear-$nextMonth-" . date('t', strtotime("$nextYear-$nextMonth-01")));
                        
$dateInterval = new DateInterval('P1D'); // Create a DateInterval of 1 day
$dateRange = new DatePeriod($startDate, $dateInterval, $endDate->modify('+1 day')); // Generate a range of dates
                        
$formattedNextDates = [];
foreach ($dateRange as $date) {
    $formattedNextDates[] = $date->format('j/M/Y'); // Format and store each date in day/month/year format
}

foreach($formattedDates as $todate){
    $nextMonthToday = DateTime::createFromFormat('j/M/Y', $todate);
    $nextMonthToday = $nextMonthToday->modify('+1 month');
    $nextMonthToday = $nextMonthToday->format('j/M/Y');
    
    if (in_array($nextMonthToday, $formattedNextDates)) {
        $qryGetAssign = "SELECT `empid`,`shift`, `effectivedt`
                        FROM `assignshifthist` a 
                        LEFT JOIN employee emp ON a.empid = emp.id 
                        WHERE emp.department='$dept' and  a.effectivedt= STR_TO_DATE('$todate', '%e/%b/%Y')";
        // echo $qryGetAssign;die;
        $resultAssign = $conn->query($qryGetAssign); 
        while($rowAssign = $resultAssign->fetch_assoc()){
            $empId = $rowAssign["empid"];
            $shiftId = $rowAssign["shift"];
            //Check Already Exist

            $qryCh= "SELECT * FROM `assignshifthist` WHERE empid = '$empId' AND effectivedt = STR_TO_DATE('$nextMonthToday', '%e/%b/%Y')";
            $resultCh = $conn->query($qryCh); 
            if ($resultCh->num_rows > 0)
            {
                while($rowCh = $resultCh->fetch_assoc()){
                    $update_id = $rowCh["id"];
                }
                
                //Update Shift
                $qryatt = "UPDATE `assignshifthist` SET `shift`='$shiftId',`makedt`=sysdate() WHERE id = ".$update_id;
                
            }else{
                //Add new Shift
                $qryatt = "INSERT INTO `assignshifthist`(`empid`, `shift`, `effectivedt`, `makedt`) 
                                            VALUES ('$empId','$shiftId',STR_TO_DATE('$nextMonthToday', '%e/%b/%Y'),sysdate())";
            }
            $conn->query($qryatt); 
        }

    }
}
echo "Successfully Assigned";
?>