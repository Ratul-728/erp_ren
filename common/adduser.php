<?php
require "conn.php";


if ( isset( $_POST['cancel'] ) ) {
    header("Location: http://bithut.biz/kini-bechi/employee.php?res=0&msg='New Entry'&id=''"); 
}

if ( isset( $_POST['submit'] ) ) {
    $empcd= $_REQUEST['empcd'];
    $empnm= $_REQUEST['empnm']; 
    $usercd= $_REQUEST['usercd'];
    
    $cmbtp= $_REQUEST['cmbtp'];
    $cell= $_REQUEST['cell'];
    $empEmail= $_REQUEST['empEmail'];
    $make_date=date('Y-m-d H:i:s');
    $st='1';
   
    
   $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   $passw =substr(str_shuffle($chars),0,8);
    
    
   // echo $passw;die;
    
    $qry="insert into hr(`emp_id`, `resourse_id`, `hidden_char`, `hrName`, `user_tp`, `email`, `cellNo`, `active_st`, `make_dt`)
values( '".$empcd."','".$usercd."','".$passw."','".$empnm."',".$cmbtp.",'".$empEmail."','".$cell."','".$st."','".$make_date."')";
 $err="New record created successfully";



//echo $qry;die;
if ($conn->connect_error) {
   echo "Connection failed: " . $conn->connect_error;
}

if ($conn->query($qry) === TRUE) {
   
   // mail("kzmamunrsd@gmail.com","My subject","Test");
    mail($empEmail,"Password For Actionaid",$passw);
    header("Location: http://bithut.biz/kini-bechi/employee.php?res=1&msg=".$err."&id=''");
    //echo "New record created successfully";
} else {
     $err="Error: " . $qry . "<br>" . $conn->error;
     header("Location: http://bithut.biz/kini-bechi/employee.php?res=2&msg=".$err."&id=''");
     //echo "Error: " . $qry . "<br>" . $conn->error;
}

// header("Location: http://bithut.biz/actionBd/dummy/dashboard.php");
   
//$conn->query($qry);
$conn->close();
}
?>