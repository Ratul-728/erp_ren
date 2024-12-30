<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $aid= $_GET['id'];
   // $serno= $_GET['id'];
    $totamount=0;
     $qry="update orders set `orderstatus`=5,`deliverydt`=sysdate() where `id`=".$aid."";
    //echo $qry;die;
if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/cusorderagentfb.php?&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/cusorderagentfb.php?&mod=4&ms=".$err);
    }
    
    $conn->close();    
}
 ?>