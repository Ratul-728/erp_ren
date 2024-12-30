<?php
require "conn.php";
session_start();

include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

$usr = $_SESSION["user"];
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/paymentList.php?mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) {
      
      $narration = $_POST['descr'];
      $amt = $_POST['amt'];
      $trdt= $_REQUEST['trdt'];
      $trdt = str_replace('/', '-', $trdt);
      $trdt = date('Y-m-d', strtotime($trdt));
      $cmbmode = $_POST['cmbmode'];
      $ref = $_POST['ref'];
      $chqdt = $_POST['chqdt'];
      $chqdt = str_replace('/', '-', $chqdt);
      $chqdt = date('Y-m-d', strtotime($chqdt));
      $customer = $_POST['org_id']; 
      $quotation = $_POST['cmbquo'];
      
       
      $qry="INSERT INTO `return_payment`(`narration`, `amount`, `trdt`, `transmode`, `transref`, `chequedt`, `customer`, `quotation`, `makeby`, `makedt`) 
                            VALUES ('".$narration."','".$amt."','".$trdt."','".$cmbmode."','".$ref."','".$chqdt."','".$customer."','".$quotation."','".$usr."',sysdate())";
      $err="A Return Payment created successfully";
         
    }
    if ( isset( $_POST['update'] ) ) {
       $fpid= $_REQUEST['fpid'];
      $narration = $_POST['descr'];
      $amt = $_POST['amt'];
      $trdt= $_REQUEST['trdt'];
      $trdt = str_replace('/', '-', $trdt);
      $trdt = date('Y-m-d', strtotime($trdt));
      $cmbmode = $_POST['cmbmode'];
      $ref = $_POST['ref'];
      $chqdt = $_POST['chqdt'];
      $chqdt = str_replace('/', '-', $chqdt);
      $chqdt = date('Y-m-d', strtotime($chqdt));
      $customer = $_POST['org_id']; 
      $quotation = $_POST['cmbquo'];
      
        $qry="UPDATE `return_payment` SET `narration`='".$narration."',`amount`='".$amt."',`trdt`='".$trdt."',`transmode`='".$cmbmode."',`transref`='".$ref."',
                `chequedt`='".$chqdt."',`customer`='".$customer."',`quotation`='".$quotation."' 
                WHERE id=".$fpid."";
        $err="Return Payment updated successfully";
        //echo $qry; die;
        
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/return_paymentList.php?res=1&msg=".$err."&mod=7");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/return_paymentList.php?res=2&msg=".$err."&mod=7");
    }
    
    $conn->close();
}
?>