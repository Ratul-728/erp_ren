<?php
require "conn.php";
session_start();

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
    if ( isset( $_POST['add'] ) ) {
        
        $pstype = $_REQUEST["pstype"];
        $slr = $_REQUEST["sl"];  if($slr == '')$slr = 0;
        $title = $_REQUEST["title"];  if($title == '')$title = "NULL"; $title = addslashes($title);
        $kpival = $_REQUEST["kpival"];
        $makeby = $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `KPI`( `PS`, `Sl`, `title`, `kpivalueType`, `makeby`, `makedt`) 
                        VALUES (".$pstype.",".$slr.",'".$title."',".$kpival.",".$makeby.",'".$make_date."')";
        $err="KPI created successfully";
        
        //echo $qry;die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['iid'];
        $pstype = $_REQUEST["pstype"];
        $slr = $_REQUEST["sl"];  if($slr == '')$slr = 0;
        $title = $_REQUEST["title"];  if($title == '')$title = "NULL"; $title = addslashes($title);
        $kpival = $_REQUEST["kpival"];
        $makeby = $_SESSION["user"];
        
        $qry="UPDATE `KPI` SET `PS`=".$pstype.",`Sl`=".$slr.",`title`='".$title."',`kpivalueType`=".$kpival." WHERE `id` = ".$tid;
        //echo $qry;die;
        $err="KPI updated successfully";

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/kpiList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/kpiList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>