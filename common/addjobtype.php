<?php
require "conn.php";
session_start();

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        $title= $_REQUEST['jobtitle'];               //if($title==''){$title='NULL';}
        
        $details = $_POST['details'];          // if($details==''){$details='NULL';}
    
        $hrid= $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `JobType`(`Title`, `Description`, `makedt`, `makeby`, `st`) VALUES ('".$title."','".$details."','".$make_date."','".$hrid."', 1)";
        $err="Job area created successfully";
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['iid'];
        $title= $_REQUEST['jobtitle'];               //if($title==''){$title='NULL';}
        $details = $_POST['details'];           //if($details==''){$details='NULL';}
        
        
        $qry="UPDATE `JobType` SET `Title`='".$title."',`Description`='".$details."' WHERE ID = ".$tid;
        //echo $qry;die;
        $err="item updated successfully";

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/jobtypeList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/jobtypeList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>