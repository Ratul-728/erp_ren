<?php
require "conn.php";
session_start();
//ini_set('display_errors', 1);
$user = $_SESSION["user"];
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
	
	$name= $_POST['name'];               //if($title==''){$title='NULL';}
	$address = $_POST["address"];
	
    if ( isset( $_POST['add'] ) ) {
		
        $qry="INSERT INTO issue_warehouse(name, address, makeby, makedt) VALUES ('".$name."', '".$address."','$user', sysdate())";
        $err="Warehouse created successfully";
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['iid'];
        
        $qry="UPDATE `issue_warehouse` SET `name`='".$name."',`address`='".$address."' WHERE ID = ".$tid;
        //echo $qry;die;
        $err="Warehouse updated successfully";
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/issue_warehouseList.php?res=1&msg=".$err."&mod=12");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/issue_warehouseList.php?res=2&msg=".$err."&id=''&mod=12");
    }
    
    $conn->close();
}
?>