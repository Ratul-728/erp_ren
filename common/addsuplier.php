<?php
require "conn.php";


if ( isset( $_POST['cancel'] ) ) {
     header("Location: ".$hostpath."/supplierList.php?mod=1&up=".$prv);
}

if ( isset( $_POST['add'] ) ) {
    
    $supnm= $_REQUEST['supnm']; //if($supnm==''){$supnm='NULL';}
    $cell= $_REQUEST['cell'];   //if($cell==''){$cell='NULL';}
    $addr= $_REQUEST['addr'];   //if($addr==''){$addr='NULL';}
    $email= $_REQUEST['email']; //if($email==''){$email='NULL';}
    $web= $_REQUEST['web'];     //if($web==''){$web='NULL';}
    $make_date=date('Y-m-d H:i:s');     
    $hrid = $_POST['usrid'];
    $com = $_POST['comid']; 
    $st='1';
   
   //$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   //$passw =substr(str_shuffle($chars),0,8);
       // echo $passw;die;
    
    $qry="insert into suplier(  `name`, `address`, `contact`,email,web, makeby,`makedt`)
values( '".$supnm."','".$addr."','".$cell."','".$email."','".$web."','".$hrid."','".$make_date."')";
 $err="New record created successfully";


}
if ( isset( $_POST['update'] ) )
{
    $supcd= $_POST['auid'];
    //echo $supcd;die;
    $supnm= $_POST['supnm']; //if($supnm==''){$supnm='NULL';}
    $cell= $_POST['cell'];   //if($cell==''){$cell='NULL';}
    $addr= $_POST['addr'];   //if($addr==''){$addr='NULL';}
    $email= $_POST['email']; //if($email==''){$email='NULL';}
    $web= $_POST['web'];     //if($web==''){$web='NULL';}
    $qry="update suplier set name='".$supnm."',address='".$addr."',contact='".$cell."',web='".$web."',email='".$email."' where id=".$supcd;
    $err="Record updated successfully";
}



//echo $qry;die;
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }

if ($conn->query($qry) === TRUE) {
 
   
     header("Location: ".$hostpath."/supplierList.php?res=1&msg=".$err."&id=''&mod=12");
    //echo "New record created successfully";
} else {
     $err="Something Went Wrong";
     header("Location: ".$hostpath."/supplierList.php?res=2&msg=".$err."&id=''&mod=12");
     //echo "Error: " . $qry . "<br>" . $conn->error;
}

// header("Location: http://bithut.biz/actionBd/dummy/dashboard.php");
//$conn->query($qry);
$conn->close();

?>