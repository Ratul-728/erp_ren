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
        
        $qry= "UPDATE `gross_salary_history` SET `gross`='$gross',`effectivedate`= STR_TO_DATE('".$effectivedate."', '%d/%m/%Y'),`makeby`='$usr',`makedt`=sysdate()
                   WHERE id = ".$empid."";
        $err="Gross Salary updated successfully";
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
        
        header("Location: ".$hostpath."/gross_histList.php?res=1&msg=".$err."&mod=4");
    } else {
        $err="Error: " . $qry . "<br>" . $conn->error;
        header("Location: ".$hostpath."/gross_histList.php?res=2&msg=".$err."&mod=4");
    }
    
    $conn->close();
}
?>