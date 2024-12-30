<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/wallet.php.php?res=01&msg='New Entry'&id=''&mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) {
     
        $trdt= $_REQUEST['trdt']; 
        $cmbsupnm = $_POST['cmborg'];     if($cmbsupnm==''){$cmbsupnm='NULL';}
        $cmbdrcr = $_POST['cmbdrcr'];      if($cmbdrcr==''){$cmbdrcr='NULL';}
        $cmbmode = $_POST['cmbmode'];     if($cmbmode==''){$cmbmode='NULL';}
        $ref = $_POST['ref'];             if($ref==''){$ref='NULL';}
        $amt = $_POST['amt'];             if($amt==''){$amt='NULL';}
        $descr = $_POST['descr'];         if($descr==''){$descr='NULL';}
       $hrid = $_POST['usrid'];        
       
      
        $qry="insert into organizationwallet(`transdt`, `orgid`, `transmode`, `dr_cr`, `trans_ref`, `amount`, `remarks`, `makeby`, `makedt`) 
        values(STR_TO_DATE('".$trdt."', '%d/%m/%Y'),".$cmbsupnm.",".$cmbmode.",'".$cmbdrcr."','".$ref."',".$amt.",'".$descr."',".$hrid.",sysdate())" ;
        $err="A receive created successfully";
           //echo $qry;die;
           if($cmbdrcr=='D')
           {
            $cusqry="update organization set balance=balance+".$amt." where id=".$cmbsupnm;
           }
           else
           {
               $cusqry="update organization set balance=balance-".$amt." where id=".$cmbsupnm;
           }
            //echo $itqry;die;
          if ($conn->query($cusqry) == TRUE) { $err="balance updated successfully";  }
        
        
         /*$note="Customer Payment: Payment Amount ".$amt." received ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values(".$cmbsupnm.",6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amt.",".$hrid.",sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
        */
     //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $wid= $_POST['wid'];
        $trdt= $_POST['trdt']; 
        $cmbsupnm = $_POST['cmborg'];     if($cmbsupnm==''){$cmbsupnm='NULL';}
        $cmbdrcr = $_POST['cmbdrcr'];      if($cmbdrcr==''){$cmbdrcr='NULL';}
        $cmbmode = $_POST['cmbmode'];     if($cmbmode==''){$cmbmode='NULL';}
        $ref = $_POST['ref'];             if($ref==''){$ref='NULL';}
        $amt = $_POST['amt'];             if($amt==''){$amt='NULL';}
        $descr = $_POST['descr'];         if($descr==''){$descr='NULL';}
        
        $prevamt = $_POST['prevamt'];
       $hrid = $_POST['usrid'];        
        
        $qry="update organizationwallet set `trans_ref`='".$ref."',`remarks`='".$descr."',`amount`=".$amt." where `id`=".$wid;
        $err="Wallet Voucher updated successfully";
        //echo $qry;die;
        if($cmbdrcr=='D')
       {
            $cusqry="update organization set balance=balance-".$prevamt."+".$amt." where id=".$cmbsupnm;
       }
       else
       {
           $cusqry="update organization set balance=balance+".$prevamt."-".$amt." where id=".$cmbsupnm;
       }
            //echo $itqry;die;
          if ($conn->query($cusqry) == TRUE) { $err="balance updated successfully";  }
         // echo $qry; die;
         /*
         $note="Customer Payment: Payment Amount ".$amt." has been updated ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values(".$cmbsupnm.",6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amt.",".$hrid.",sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  }
         */
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/organazionwallet.php?&mod=3&orgid=".$cmbsupnm);
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/organazionwallet.php?&mod=3&orgid=".$cmbsupnm);
    }
    
    $conn->close();
}
?>