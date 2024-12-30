<?php
require "conn.php";
session_start();

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
    if ( isset( $_POST['add'] ) ) {
        
        $year = $_REQUEST["year"]; //if($year == '') $year = "NULL";
        $atype = $_REQUEST["atype"];
        $empid = $_REQUEST["empid"];
        $mnr = $_REQUEST["mnr"]; //if($mnr == '') $mnr = "NULL"; 
        $mnr = addslashes($mnr);
        $hrr = $_REQUEST["hrr"]; //if($hrr == '') $hrr = "NULL"; 
        $hrr = addslashes($hrr);
        $mdr = $_REQUEST["mdr"]; //if($mdr == '') $mdr = "NULL"; 
        $mdr = addslashes($mdr);
        
        $hraction = $_REQUEST["hraction"];
        $effectivedt = $_REQUEST['effective_dt'];
        $effectivedt = implode("-", array_reverse(explode("/", $effectivedt)));
        
        $makeby = $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `appraisal` (`year`, `appraisalType`, `hrid`, `managerrecomandation`, `hrdrecommendation`, `mdrecomendation`, `hraction`, `effectivedt`, `makeby`, `makedt`) 
                                VALUES ('".$year."','".$atype."','".$empid."','".$mnr."','".$hrr."','".$mdr."','".$hraction."','".$effectivedt."','".$makeby."',sysdate())";
        $err="Appraisal created successfully";
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['iid'];
        $year = $_REQUEST["year"]; //if($year == '') $year = "NULL";
        $atype = $_REQUEST["atype"];
        $empid = $_REQUEST["empid"];
        $mnr = $_REQUEST["mnr"]; //if($mnr == '') $mnr = "NULL"; 
        $mnr = addslashes($mnr);
        $hrr = $_REQUEST["hrr"]; //if($hrr == '') $hrr = "NULL"; 
        $hrr = addslashes($hrr);
        $mdr = $_REQUEST["mdr"]; //if($mdr == '') $mdr = "NULL"; 
        $mdr = addslashes($mdr);
        
        $hraction = $_REQUEST["hraction"];
        $effectivedt = $_REQUEST['effective_dt'];
        $effectivedt = implode("-", array_reverse(explode("/", $effectivedt)));
        
        $qry="UPDATE `appraisal` SET `year`= '".$year."',`appraisalType`='".$atype."',`hrid`='".$empid."',`managerrecomandation`='".$mnr."',`hrdrecommendation`='".$hrr."',
                `mdrecomendation`='".$mdr."',`hraction`='".$hraction."',`effectivedt`='".$effectivedt."' WHERE `id` = ".$tid;
        //echo $qry;die;
        $err="Attendance updated successfully";

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/appraisalList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/appraisalList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>