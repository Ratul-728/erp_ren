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
        $title= $_REQUEST['btitle'];            //if($title==''){$code='NULL';}
        $bnature = $_POST['bnature'];                     //if($bnature==''){$bnature='NULL';}
        $details = $_POST['bdetails'];           //if($details==''){$details='NULL';}
        $btype = $_POST['btype']; if($btype == '') $btype = 2;
         
    
        $hrid= $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `benifitype`( `title`, `benifitnature`,`benifittype`, `Description`, `makedt`, `makeby`) VALUES ('".$title."','".$bnature."','".$btype."','".$details."','".$make_date."',".$hrid.")" ;
        $err="Item created successfully";
        
        
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid = $_REQUEST['itid'];
        $title= $_REQUEST['btitle'];            //if($title==''){$title='NULL';}
        $bnature = $_POST['bnature'];                     //if($bnature==''){$bnature='NULL';}
        $details = $_POST['bdetails'];           //if($details==''){$details='NULL';}
        $btype = $_POST['btype'];                   if($btype == '') $btype = 2;
        
        $qry="UPDATE `benifitype` SET `title`='".$title."',`benifitnature`='".$bnature."',`benifittype`='".$btype."',`Description`='".$details."' WHERE id = ".$tid."";
        $err="item updated successfully";
      
      // echo $qry;die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/benifittypeList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/benifittypeList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>