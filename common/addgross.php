<?php
require "conn.php";
session_start();

$usr = $_SESSION["user"];

//print_r($_POST);die;
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
    $errflag = 0;
    if ( isset( $_POST['update'] ) ) {
        $empid= $_REQUEST['usrid'];     //if($empid==''){$empid='NULL';}
        $gross = $_POST['gross'];           if($coms==''){$coms=0;}
        $effectivedate = $_POST['effectivedate'];           
        
        //Check Already Added or not
        $qryCh = "SELECT * FROM `gross_salary` WHERE empid = ".$empid;
        $resultCh = $conn->query($qryCh); 
        if ($resultCh->num_rows > 0){
            $qry= "UPDATE `gross_salary` SET `gross`='$gross',`effectivedate`= STR_TO_DATE('".$effectivedate."', '%d/%m/%Y'),`makeby`='$usr',`makedt`=sysdate()
                   WHERE empid = ".$empid."";
            $err="Gross Salary updated successfully";
        }else{
            $qry= "INSERT INTO `gross_salary`(`empid`, `gross`, `effectivedate`, `makeby`, `makedt`) 
                                    VALUES ('$empid','$gross',STR_TO_DATE('".$effectivedate."', '%d/%m/%Y'),'$usr',sysdate())";
            $err="Gross Salary added successfully";
      
        }
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
        $qryHis = "INSERT INTO `gross_salary_history`(`empid`, `gross`, `effectivedate`, `makeby`, `makedt`) 
                                    VALUES ('$empid','$gross',STR_TO_DATE('".$effectivedate."', '%d/%m/%Y'),'$usr',sysdate())";
        $conn->query($qryHis);
        
        header("Location: ".$hostpath."/grossList.php?res=1&msg=".$err."&mod=4");
    } else {
        $err="Error: " . $qry . "<br>" . $conn->error;
        header("Location: ".$hostpath."/grossList.php?res=2&msg=".$err."&mod=4");
    }
    
    $conn->close();
}
?>