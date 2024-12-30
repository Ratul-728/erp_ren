<?php
require "conn.php";
include_once('../rak_framework/fetch.php');
session_start();

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
  // print_r($_POST);die;
    if ( isset( $_POST['add'] ) ) {
        
        $code = getFormatedUniqueID('serviceitem','id','SI-',6,"0");
      
        $name= $_POST['name'];             //if($title==''){$code='NULL';}
        
        $vat = $_POST['vat'];           
        
        $tax = $_POST["tax"];
    
        $hrid= $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `serviceitem`(`code`, `name`, `vat`, `tax`, `makedt`, `makeby`) 
                VALUES ('$code','$name','$vat','$tax','$make_date','$hrid')";
        $err="Service Item created successfully";
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['iid'];
        $name= $_POST['name'];             //if($title==''){$code='NULL';}
        
        $vat = $_POST['vat'];           
        
        $tax = $_POST["tax"];
        
        
        $qry="UPDATE `serviceitem` SET `name`='$name',`vat`='$vat',`tax`='$tax' WHERE id =".$tid;
       // echo $qry;die;
        $err="Service Item updated successfully";

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/service_itemList.php?res=1&msg=".$err."&id=".$poid."&mod=22");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/service_itemList.php?res=2&msg=".$err."&id=''&mod=22");
    }
    
    $conn->close();
}
?>