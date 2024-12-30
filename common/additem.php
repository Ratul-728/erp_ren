<?php
require "conn.php";



if ( isset( $_POST['cancel'] ) ) {
     header("Location: ".$hostpath."/item.php?res=0&msg='New Entry'&id=''"); 
}
else
{

if ( isset( $_POST['submit'] ) ) {
    $cd= $_REQUEST['cd'];
    $nm= $_REQUEST['nm']; 
    $descr= $_REQUEST['descr'];
    $make_date=date('Y-m-d H:i:s');
    $st='1';
    $hr=1;
    
   // echo $passw;die;
    
    $qry="insert into item( `name`, `description`, `st`, `code`, `makeby`, `make_dt`)
values( '".$nm."','".$descr."','".$st."','".$cd."','".$hr."','".$make_date."')";
 $err="New Item created successfully";
 }
 

 if ( isset( $_POST['update'] ) ) {
    $id= $_REQUEST['iid'];
    $cd= $_REQUEST['cd'];
    $nm= $_REQUEST['nm']; 
    $descr= $_REQUEST['descr'];
    
    
   // echo $passw;die;
    
    $qry="update  item set  `name`='".$nm."', `description`='".$descr."', `code`='".$cd."' where `id`=".$id."";
  $err="Item update successfully";
 
    // echo $qry;die;
 }
 
 

//echo $qry;die;
if ($conn->connect_error) {
   echo "Connection failed: " . $conn->connect_error;
}

if ($conn->query($qry) === TRUE) {
   
   // mail("kzmamunrsd@gmail.com","My subject","Test");
   // mail($empEmail,"Password For Actionaid",$passw);
     header("Location: ".$hostpath."/item.php?res=1&msg=".$err."&id=''");
    //echo "New record created successfully";
} else {
     $err="Error: " . $qry . "<br>" . $conn->error;
      header("Location: ".$hostpath."/item.php?res=2&msg=".$err."&id=''");
     //echo "Error: " . $qry . "<br>" . $conn->error;
}

// header("Location: http://bithut.biz/actionBd/dummy/dashboard.php");
   
//$conn->query($qry);
$conn->close();
}
?>