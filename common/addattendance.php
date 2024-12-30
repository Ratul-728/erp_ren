<?php
require "conn.php";
session_start();


if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       
        $hrid = $_REQUEST['emp'];
        
        $atdt = $_POST['at_dt'];
        $atdt = implode("-", array_reverse(explode("/", $atdt)));
        
        $starttime = $_REQUEST["stime"];
        $endtime = $_REQUEST["etime"];
        
        $starttime = date("H:i:s", strtotime($starttime));
        $endtime = date("H:i:s", strtotime($endtime));
        
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `attendance`(`hrid`, `date`, `intime`, `outtime`) VALUES (".$hrid.",'".$atdt."','".$starttime."','".$endtime."')";
        $err="Attendance created successfully";
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['iid'];
        $hrid = $_REQUEST['emp'];
        
        $atdt = $_POST['at_dt'];
        $atdt = implode("-", array_reverse(explode("/", $atdt)));
        
        $starttime = $_REQUEST["stime"];
        $endtime = $_REQUEST["etime"];
        
        $starttime = date("H:i:s", strtotime($starttime));
        $endtime = date("H:i:s", strtotime($endtime));
        
        $qry="UPDATE `attendance` SET `hrid`=".$hrid.",`date`='".$atdt."',`intime`='".$starttime."',`outtime`='".$endtime."' WHERE ID = ".$tid;
        //echo $qry;die;
        $err="Attendance updated successfully";

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/attList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/attList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>