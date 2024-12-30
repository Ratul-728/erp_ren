<?php
require "conn.php";

include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/paymentList.php?mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) {
     
      $trdt= $_REQUEST['trdt'];
      $trdt = str_replace('/', '-', $trdt);
      $trdt = date('Y-m-d', strtotime($trdt));
        
        
      $cmbmode = $_POST['cmbmode'];
      $ref = $_POST['ref'];
      $chqdt = $_POST['chqdt'];
      $chqdt = str_replace('/', '-', $chqdt);
      $chqdt = date('Y-m-d', strtotime($chqdt));
      
      $cmbsupnm = $_POST['org_id']; 
      $amt = $_POST['amt'];
      $cmbcc = $_POST['cmbcc'];
      $descr = $_POST['descr'];
      $hrid = $_POST['usrid'];
       
      
      $chqclearst=0;$st=1; //$hrid= '1';
       
        $qry="insert into allpayment( `trdt`, `transmode`, `transref`, `chequedt`, `customer`, `naration`, `amount`, `costcenter`, `chqclearst`, `st`, `makeby`, `makedt`) 
        values('".$trdt."','".$cmbmode."','".$ref."','".$chqdt."','".$cmbsupnm."','".$descr."','".$amt."','".$cmbcc."','".$chqclearst."','".$st."','".$hrid."',sysdate())" ;
        $err="A Payment created successfully";
         
         $cusqry="update contact set currbal=currbal-".$amt." where id=".$cmbsupnm." and status=1";
            //echo $itqry;die;
          if ($conn->query($cusqry) == TRUE) { $err="contatct updated successfully";  }
        
         $note="Vendor Payment: Payment Amount ".$amt." received ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$cmbsupnm."',6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,'".$amt."','".$hrid."',sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
        
     //echo $qry; die;
     
                //Mail to Organization email
                $qrymailinfo = "SELECT `name`,`email` FROM `organization` WHERE id = ".$cmbsupnm;
                $resultmailinfo = $conn->query($qrymailinfo);
                while ($rowmailinfo = $resultmailinfo->fetch_assoc()) {
                    $orgnm =$rowmailinfo["name"];
                    $orgmail = $rowmailinfo["email"];
                }
                
                $name_to = $orgnm;
        		$email_to = $orgmail;
        		$message = "Dear $orgnm, <br><br>
        		
        		            Payment recieved successfully.<br>
        		            Payment Amount: $amt.<br>
        		            Description   : $descr.<br>
        		            ";
        		$subject = 'PAYMENT RECIEVED SUCCESSFULLY';
        		sendBitFlowMail($name_to,$email_to, $subject,$message);
        		
        		//Mail to Admin email
                $qrymailinfo = "SELECT `companynm`,`email` FROM `sitesettings` WHERE id = 1";
                $resultmailinfo = $conn->query($qrymailinfo);
                while ($rowmailinfo = $resultmailinfo->fetch_assoc()) {
                    $adminnm =$rowmailinfo["companynm"];
                    $adminmail = $rowmailinfo["email"];
                }
                
                $name_to = $adminnm;
        		$email_to = $adminmail;
        		$message = "Dear $adminnm, <br><br>
        		
        		            Payment recieved successfully.<br>
        		            Payment Amount: $amt.<br>
        		            Description   : $descr.<br>
        		            ";
        		$subject = 'PAYMENT RECIEVED SUCCESSFULLY';
        		sendBitFlowMail($name_to,$email_to, $subject,$message);
        		
    }
    if ( isset( $_POST['update'] ) ) {
       $fpid= $_REQUEST['fpid'];
      $trdt= $_REQUEST['trdt'];
      $cmbmode = $_POST['cmbmode'];
      $ref = $_POST['ref'];
      $chqdt = $_POST['chqdt'];
      $cmbsupnm = $_POST['org_id']; 
      $amt = $_POST['amt'];
      $cmbcc = $_POST['cmbcc'];
      $descr = $_POST['descr'];
       $hrid = $_POST['usrid'];
      
        $qry="update allpayment set `trdt`=STR_TO_DATE('".$trdt."', '%d/%m/%Y'),`transmode`='".$cmbmode."',`transref`='".$ref."',`chequedt`=STR_TO_DATE('".$chqdt."', '%d/%m/%Y'),`customer`='".$cmbsupnm."',`naration`='".$descr."',`amount`='".$amt."',`costcenter`='".$cmbcc."' where `id`=".$fpid."";
        $err="Payment Voucher updated successfully";
        //echo $qry; die;
         
          $note="Vendor Payment: Payment Amount ".$amt." has been updated ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$cmbsupnm."',6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,'".$amt."','".$hrid."',sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  }
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/paymentList.php?res=1&msg=".$err."&mod=3");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/paymentList.php?res=2&msg=".$err."&mod=3");
    }
    
    $conn->close();
}
?>