<?php
require "../common/conn.php";
session_start();

$shiftId = $_POST["shiftId"];
$empId = $_POST["empId"];
$date = $_POST["date"];

//Check Already Exist

$qryCh= "SELECT * FROM `assignshifthist` WHERE empid = '$empId' AND effectivedt = STR_TO_DATE('$date', '%e/%b/%Y')";
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
                                VALUES ('$empId','$shiftId',STR_TO_DATE('$date', '%e/%b/%Y'),sysdate())";
}
$conn->query($qryatt); 
echo "Successfully Assigned";
?>