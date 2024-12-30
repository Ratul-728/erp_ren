<?php
require "conn.php";

include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');


if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/collection.php?res=01&msg='New Entry'&id=''&mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) {
     
      $trdt= $_REQUEST['trdt']; 
      $cmbmode = $_POST['cmbmode'];     //if($cmbmode==''){$cmbmode='NULL';}
      $ref = $_POST['ref'];             //if($ref==''){$ref='NULL';}
      $chqdt = $_POST['chqdt'];         //if($chqdt==''){$chqdt='NULL';}
      $cmbsupnm = $_POST['org_id'];   //if($cmbsupnm==''){$cmbsupnm='NULL';}
      $amt = $_POST['amt'];             if($amt==''){$amt='0';}
      $cmbinv = $_POST['cmbinv'];       //if($cmbinv==''){$cmbinv='NULL';}
      $descr = $_POST['descr'];         //if($descr==''){$descr='NULL';}
      $curr = $_POST['curr'];         //if($curr==''){$curr='NULL';}
      
       $hrid = $_POST['usrid'];        
       
       //echo $cmbsupnm;die;
      
      $chqclearst=0;$st=0; //$hrid= '1';
       
        $qry="insert into collection(  `trdt`,`invoice`, `transmode`, `transref`, `chequedt`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`) 
        values(STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'".$cmbinv."','".$cmbmode."','".$ref."',STR_TO_DATE('".$chqdt."', '%d/%m/%Y') ,'".$cmbsupnm."','".$descr."','".$amt."','".$chqclearst."','".$st."','".$curr."','".$hrid."',sysdate())" ;
        $err="A receive created successfully";
           //echo $qry;die;
         $cusqry="update contact set currbal=currbal+".$amt." where id=".$cmbsupnm." and status=1";
            //echo $itqry;die;
          if ($conn->query($cusqry) == TRUE) { $err="contatct updared successfully";  }
        
        
        $orgbalqry="update organization set balance=balance+".$amt." where id=".$cmbsupnm;
            //echo $itqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
          
           
           $orgupdbalqry="select balance from organization where id=".$cmbsupnm;
           $resultbl = $conn->query($orgupdbalqry);
            if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
        
         $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values(STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'$cmbsupnm','$cmbmode','C','$ref,$amt','$curbal','Fund Receive','$hrid',sysdate())";
            //echo $itqry;die;
          if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
        
        
        
         $note="Customer Payment: Payment Amount ".$amt." received ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$cmbsupnm."',6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amt.",'".$hrid."',sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  } 
         
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
        		
        		            Payment recieved  Successfully.<br>
        		            Payment Amount ".$amt." received<br><br>
        		            ";
        		$subject = 'Payment Recieved';
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
        		
        		            One customer payment recieved Successfully.<br>
        		            $note.<br><br>
        		            ";
        		$subject = 'Payment Recieved';
        		sendBitFlowMail($name_to,$email_to, $subject,$message);
        
     //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
       $fcid= $_REQUEST['fcid'];
      $trdt= $_REQUEST['trdt']; 
      $cmbmode = $_POST['cmbmode'];     //if($cmbmode==''){$cmbmode='NULL';}
      $ref = $_POST['ref'];             //if($ref==''){$ref='NULL';}
      $chqdt = $_POST['chqdt'];         //if($chqdt==''){$chqdt='NULL';}
      $cmbsupnm = $_POST['org_id'];   //if($cmbsupnm==''){$cmbsupnm='NULL';}
      $amt = $_POST['amt'];             if($amt==''){$amt=0;}
     // $cmbcc = $_POST['cmbcc'];
       $cmbinv = $_POST['cmbinv'];      //if($cmbinv==''){$cmbinv='NULL';}
      $descr = $_POST['descr'];         //if($descr==''){$descr='NULL';}
       $hrid = $_POST['usrid']; 
       
       $getO="SELECT customerOrg,amount,glac FROM collection where id=".$fcid."";// Existing Aount
         $resultO = $conn->query($getO);
            if ($resultO->num_rows > 0) {while ($rowO = $resultO->fetch_assoc()) { $Oglno = $rowO["glac"];$Oamount = $rowO["amount"];$OcustomerOrg = $rowO["customerOrg"];}}
         
      //echo $getO;die;
      
        $qry="update collection set `trdt`=STR_TO_DATE('".$trdt."', '%d/%m/%Y'),`invoice`='".$cmbinv."',`transmode`='".$cmbmode."',`transref`='".$ref."',`chequedt`=STR_TO_DATE('".$chqdt."', '%d/%m/%Y'),`customerOrg`='".$cmbsupnm."',`naration`='".$descr."',`amount`=".$amt." where `id`=".$fcid."";
        $err="Received Voucher updated successfully";
          //echo $qry; die;
          
         $orgbalqry="update organization set balance=balance+$amt-$Oamount where id=".$cmbsupnm;
            //echo $itqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
         
         
           $orgupdbalqry="select balance from organization where id=".$cmbsupnm;
           $resultbl = $conn->query($orgupdbalqry);
            if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
         
         
         $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values(STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'$cmbsupnm','$cmbmode','D','$ref','$Oamount','$curbal','Receive reverse of id='.$fcid,'$hrid',sysdate())
          ,(STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'$cmbsupnm','$cmbmode','C','$ref','$amt','Receive Updated','$hrid',sysdate())";
            //echo $itqry;die;
          if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }  
          
         $note="Customer Payment: Payment Amount ".$amt." has been updated ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$cmbsupnm."',6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,'".$amt."','".$hrid."',sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  }
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
        $last_id = $conn->insert_id;
		$last_id = ($last_id)?$last_id:$_POST['rpid'];
                header("Location: ".$hostpath."/collection_rec.php?mod=3&rpid=".$last_id."&msg=".$err);
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/collectionList.php?res=2&msg=".$err."&mod=3");
    }
    
    $conn->close();
}
?>