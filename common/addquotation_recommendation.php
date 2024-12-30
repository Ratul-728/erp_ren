<?php
require "conn.php";
session_start();
$hrid = $_SESSION["empid"];

//print_r($_POST);

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=14");
}
else
{
    if ( isset( $_POST['update'] ) ) {
        $quotation = $_POST["itid"];
        $recom = addslashes($_POST["recom"]);

        $qry="INSERT INTO `rfq_authorisation`(`rfq_vendor`, `recommender`, `recommendation`, `makedt`) 
                                        VALUES ('$quotation', '$hrid','$recom', sysdate())";
        $err="Quotation Update successfully";
    }
   //echo $qry;die;
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/recommendation_quotList.php?res=1&msg=".$err."&id=".$poid."&mod=14");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/rfqList.php?res=2&msg=".$err."&id=''&mod=14");
    }
    
    $conn->close();
}
?>