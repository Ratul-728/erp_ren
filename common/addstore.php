<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/sms.php?res=01&msg='New Entry'&id=''&mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) {
     
         //echo $dt; die; 
       //$_REQUEST['tcktid']; 
        $name = $_POST['name'];               $name = addslashes($name);
        $contact_name = $_POST['contact_name']; $contact_name = addslashes($contact_name);
        $contact_number = $_POST['contact_number']; $contact_number = addslashes($contact_number);
        
        $address = $_POST['address']; $address = addslashes($address);
      
       
        $qry="INSERT INTO `branch`(`name`, `contact_name`, `contact_number`, `address`) 
                            VALUES ('".$name."','".$contact_name."','".$contact_number."','".$address."')" ;
        $err="As Store created successfully";
        // echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
       $isid= $_REQUEST['cmbkt'];
      
       $name = $_POST['name'];               $name = addslashes($name);
        $contact_name = $_POST['contact_name']; $contact_name = addslashes($contact_name);
        $contact_number = $_POST['contact_number']; $contact_number = addslashes($contact_number);
        
        $address = $_POST['address']; $address = addslashes($address);      
       
      
        $qry="UPDATE `branch` SET `name`='".$name."',`contact_name`='".$contact_name."',`contact_number`='".$contact_number."',`address`='".$address."' WHERE `id` = ".$isid."";
        $err="Store updated successfully";
         // echo $qry; die;
       //  $note="Customer Payment: Payment Amount ".$amt." has been updated ";
        //$qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        //values(".$cmbsupnm.",6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amt.",".$hrid.",sysdate())" ;
         //if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  }
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/storeList.php?res=1&msg=".$err."&mod=13");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/storeList.php?res=2&msg=".$err."&mod=13");
    }
    
    $conn->close();
}
?>