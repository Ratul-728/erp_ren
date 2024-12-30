//<?php
require "conn.php";
session_start();

$usr = $_SESSION["user"];

//print_r($_POST);die;
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
    $errflag = 0;
    if ( isset( $_POST['add'] ) )
    {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        $empid= $_REQUEST['empid'];     //if($empid==''){$empid='NULL';}
        $coms = $_POST['coms'];           if($coms==''){$coms=0;}
        $increament = $_POST['incr'];           
        $priamountarr = $_POST['bamount'];          
        $action_dtarr = $_POST['action_dt'];
        $conditionarr = $_POST['condition'];
        //$details = $_POST['details'];
        
        $hrid= $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
        
        $err = "HR Compansation Successfully added";
        
        $qrycom = "SELECT id FROM `hrcompansation` where `hrid` = ".$empid;
        $result = $conn->query($qrycom);
        if ($result->num_rows > 0)
        {
             $err="Already assigned a package for this employee";
            header("Location: ".$hostpath."/hrcompansationList.php?res=2&msg=".$err."&id=''&mod=4");
            die;
        }
        
        //$compact = $row["id"];
        // $hrcompCode=$empid.$coms;
        
        // if($compact == '') $compact = 1;
        // else $compact++;
        
    
    
        $qry= "INSERT INTO `hrcompansation`( `hrid`, `compansation`, `privilagedfund`, `increment`, `effectivedate`, `Description`, `makedt`, `makeby`) 
                                    Values('".$empid."',".$coms.",'".$priamountarr."','".$increament."', STR_TO_DATE('".$action_dtarr."', '%d/%m/%Y'),'".$conditionarr."', sysdate(), '$usr' )";
        //echo $qry;die;
        $err="Compansation Addeed successfully";
        
        if ($conn->query($qry) == TRUE) {  $err = "HR Compansation Successfully added";  }
        else{ $errflag++; }
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid = $_REQUEST['serid'];
        $empid= $_REQUEST['empid'];     //if($empid==''){$empid='NULL';}
        $coms = $_POST['coms'];           if($coms==''){$coms=0;}
        $increament = $_POST['incr'];           
        $priamountarr = $_POST['bamount'];          
        $action_dtarr = $_POST['action_dt'];
        $conditionarr = $_POST['condition'];
        
        $qry= "UPDATE `hrcompansation` SET compansation='".$coms."',privilagedfund='".$coms."', hrid = '$empid', increment = '$increament', effectivedate = STR_TO_DATE('".$action_dtarr."', '%d/%m/%Y'),
                Description = '$conditionarr' WHERE id =".$tid."";
        //echo $qry;die;
        $err="Compansation updated successfully";
      
        if ($conn->query($qry) == TRUE) {  $err = "HR Compansation updated successfully";  }
        else{ $errflag++; }
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errflag == 0) {
        header("Location: ".$hostpath."/hrcompansationList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/hrcompansationList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>