<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/sms.php?res=01&msg='New Entry'&id=''&mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) {
     
         //echo $dt; die; 
       //$_REQUEST['tcktid']; 
        $cmborg = $_POST['cmborg'];               //if($cmborg==''){$cmborg='NULL';}
        $contact = $_POST['contact'];           //if($contact==''){$contact='NULL';}
        $msg = $_POST['msg'];                     //if($msg==''){$msg='NULL';}
      
      $hrid = $_POST['usrid'];        
       
        $qry="insert into announcesms(`organization`, `contact`, `msg`, `makedt`, `makeby`  ) 
        values('".$cmborg."','".$contact."','".$msg."',sysdate(),".$hrid.")" ;
        $err="An SMS created successfully";
         //  echo $qry;die;
        // $cusqry="update contact set currbal=currbal+".$amt." where id=".$cmbsupnm." and status=1";
            //echo $itqry;die;
         // if ($conn->query($cusqry) == TRUE) { $err="contatct updared successfully";  }
        
        
        // $note="Customer Payment: Payment Amount ".$amt." received ";
        //$qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        //values(".$cmbsupnm.",6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amt.",".$hrid.",sysdate())" ;
        // if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
        
     //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
       $isid= $_REQUEST['smid'];
      
       $cmborg = $_POST['cmborg'];               //if($cmborg==''){$cmborg='NULL';}
        $contact = $_POST['contact'];           //if($contact==''){$contact='NULL';}
        $msg = $_POST['msg'];                     //if($msg==''){$msg='NULL';}
      $hrid = $_POST['usrid'];        
       
      
        $qry="update announcesms set `organization`='".$cmborg."',`contact`='".$contact."',`msg`='".$msg."' where `id`=".$isid."";
        $err="SMS updated successfully";
         // echo $qry; die;
       //  $note="Customer Payment: Payment Amount ".$amt." has been updated ";
        //$qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        //values(".$cmbsupnm.",6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amt.",".$hrid.",sysdate())" ;
         //if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  }
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/smsList.php?res=1&msg=".$err."&mod=6");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/smsList.php?res=2&msg=".$err."&mod=6");
    }
    
    $conn->close();
}
?>