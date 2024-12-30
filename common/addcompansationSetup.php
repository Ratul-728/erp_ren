//<?php
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
        $title= $_REQUEST['ctitle'];            //if($title==''){$title='NULL';}
        $basic = $_POST['basic'];           if($basic==''){$basic=0.0;}
        // $increment = $_POST['increment'];           if($increment==''){$increment=0.0;}
        $maxgross = $_POST['maxgross'];           if($maxgross==''){$maxgross=0.0;}
        $details = $_POST['cdetails'];           //if($details==''){$details='NULL';}
         
    
        $hrid= $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `compansationSetup`( `title`, `basic`, `maxgross`, `Description`, `makeby`, `makedt`) 
        VALUES ('".$title."',".$basic.",".$maxgross.",'".$details."','".$hrid."','".$make_date."')" ;
        $err="Item created successfully";
        
        
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid = $_REQUEST['itid'];
        $title= $_REQUEST['ctitle'];            //if($title==''){$title='NULL';}
        $basic = $_POST['basic'];           if($basic==''){$basic=0.0;}
        // $increment = $_POST['increment'];           if($increment==''){$increment=0.0;}
        $maxgross = $_POST['maxgross'];           if($maxgross==''){$maxgross=0.0;}
        $details = $_POST['cdetails'];           //if($details==''){$details='NULL';}
         
        
        $qry= "UPDATE `compansationSetup` SET `title`='".$title."',`basic`=".$basic.",`maxgross`=".$maxgross.",`Description`='".$details."' WHERE id = ".$tid."";
        $err="item updated successfully";
      
      // echo $qry;die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/compansationSetupList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/compansationSetupList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>