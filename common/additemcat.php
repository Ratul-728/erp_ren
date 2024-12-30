<?php
require "conn.php";
include_once('../rak_framework/fetch.php');
session_start();
//ini_set('display_errors', 1);
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
	
	$make_date=date('Y-m-d H:i:s');
	$title= $_REQUEST['actiontitle'];               //if($title==''){$title='NULL';}
	
	
	
    if ( isset( $_POST['add'] ) ) {
        
        //Check if already exist
	$qryCh = "SELECT * FROM `itmCat` WHERE LOWER(name) = LOWER('$title');";
	$resultCh = $conn->query($qryCh);
	if ($resultCh->num_rows > 0){
	    //echo "I am here";die;
	     $err="Error: This category already Exist";
         header("Location: ".$hostpath."/itemcatList.php?res=2&msg=".$err."&id=''&mod=12");
         die;
	}
	
        
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        
        $details = $_POST['details'];           //if($details==''){$details='NULL';}
    
        $hrid= $_SESSION["user"];
        
        
		$catid = getFormatedUniqueID('itmCat','id','CT-',6,"0");
		
        $qry="INSERT INTO itmCat(catid, name, makedt,description) VALUES ('".$catid."', '".$title."','".$make_date."', '".$details."')";
        $err="Catagory created successfully";
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['iid'];
        $details = $_POST['details'];           //if($details==''){$details='NULL';}
        
        
        $qry="UPDATE `itmCat` SET `name`='".$title."',`description`='".$details."',`makedt`='".$make_date."' WHERE ID = ".$tid;
        //echo $qry;die;
        $err="item updated successfully";
		$catid = fetchByID('itmCat',id,$tid,'catid');
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/itemcatList.php?res=1&msg=".$err."&id=".$poid."&mod=12&changedid=".$catid);
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/itemcatList.php?res=2&msg=".$err."&id=''&mod=12");
    }
    
    $conn->close();
}
?>