//<?php
require "conn.php";
session_start();

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=1");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       
        $title= $_REQUEST['deptname'];               //if($title==''){$code='NULL';}
        $head = $_REQUEST['head']; 
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `department`(`name`,`head`, `makedt`, `makeby`, `st`) VALUES ('".$title."','".$head."','".$make_date."','".$hrid."', 1)";
        $err="Department created successfully";
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['iid'];
        $title= $_REQUEST['deptname'];
        $head = $_REQUEST['head']; 
        
        
        $qry="UPDATE `department` SET `name`='".$title."', `head`='".$head."' WHERE id = ".$tid;
        //echo $qry;die;
        $err="item updated successfully";

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/departmentList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/departmentList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>