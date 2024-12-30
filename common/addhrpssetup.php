<?php
require "conn.php";
session_start();

//print_r($_REQUEST);die;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
    if ( isset( $_POST['add'] ) ) {
        
        $empid = $_REQUEST["empid"];
        $pstype = $_REQUEST["pstype"];
        $ps = $_REQUEST["ps"];
        
        $year = date('Y');
        $makeby = $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `hrPSsetup`( `hrid`, `psType`, `PS`, `year`, `makeby`, `makedt`) 
                                    VALUES (".$empid.",".$pstype.",".$ps.",".$year.",".$makeby.",'".$make_date."')";
        $err="HR PS Setup created successfully";
        
        //print_r($_REQUEST);
        //echo $qry;die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid = $_REQUEST["tid"];
        $empid = $_REQUEST["empid"];
        $pstype = $_REQUEST["pstype"];
        $ps = $_REQUEST["ps"];
        
        $year = date('Y');
        $makeby = $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
        
        $qry="UPDATE `hrPSsetup` SET `hrid`= ".$empid.",`psType`= ".$pstype.",`PS`= ".$ps." WHERE id = ".$tid;
        //echo $qry;die;
        $err="HR PS Setup updated successfully";

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
        if ( isset( $_POST['add'] ) ) {
        
            $last = $conn->insert_id;
            $si = $_REQUEST["si"];
            $kpi = $_REQUEST["cmbkpi"];
            
            if (is_array($si))
            {
                for ($i=0;$i<count($si);$i++)
                {
                    $qrykpi = "INSERT INTO `hrPSsetupKPI`(`psid`, `si`, `kpi`) VALUES (".$last.",".$si[$i].",".$kpi[$i].")";
                    $conn->query($qrykpi);
                }
            }
        }
        if ( isset( $_POST['update'] ) ) {
            
            $qrydel = "DELETE FROM `hrPSsetupKPI` WHERE `psid` = ".$tid;
            $conn->query($qrydel);
            
            $si = $_REQUEST["si"];
            $kpi = $_REQUEST["cmbkpi"];
            
            if (is_array($si))
            {
                for ($i=0;$i<count($si);$i++)
                {
                    $qrykpi = "INSERT INTO `hrPSsetupKPI`(`psid`, `si`, `kpi`) VALUES (".$tid.",".$si[$i].",".$kpi[$i].")";
                    $conn->query($qrykpi);
                }
            }
        }
        
                header("Location: ".$hostpath."/hrpssetupList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/hrpssetupList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>