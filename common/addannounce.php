<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/announcement.php?res=01&msg='New Entry'&id=''&mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) {
     
     $dt=date("Ymdhis"); 
    //echo $dt; die; 
       //$_REQUEST['tcktid']; 
      $title = $_POST['title'];             //if($title==''){$title='NULL';}
      $announcedt = $_POST['announcedt'];   //if($announcedt==''){$announcedt='NULL';}
      $cmborg = $_POST['cmborg'];           //if($cmborg==''){$cmborg='NULL';}
      $cmbprod = $_POST['cmbprod'];         //if($cmbprod==''){$cmbprod='NULL';}
      $announce = $_POST['announce'];       //if($announce==''){$announce='NULL';}
      
      $hrid = $_POST['usrid'];        
       
      $announceno=$dt.substr($title,1,3);
      $chqclearst=0;$st=0; //$hrid= '1';
       
        $qry="insert into announce( announceid,announcedt, `title`, `catagory`, `organization`, `announce`, `makedt`, `makeby` ) 
        values('".$announceno."',STR_TO_DATE('".$announcedt."', '%d/%m/%Y'),'".$title."','".$cmbprod."','".$cmborg."','".$announce."',sysdate(),'".$hrid."')" ;
        $err="An announcement created successfully";
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
       $isid= $_REQUEST['anid'];
      
      $title = $_POST['title'];             //if($title==''){$title='NULL';}
      $announcedt = $_POST['announcedt'];   //if($announcedt==''){$announcedt='NULL';}
      $cmborg = $_POST['cmborg'];           //if($cmborg==''){$cmborg='NULL';}
      $cmbprod = $_POST['cmbprod'];         //if($cmbprod==''){$cmbprod='NULL';}
      $announce = $_POST['announce'];       //if($announce==''){$announce='NULL';}
      $hrid = $_POST['usrid'];        
       
      
        $qry="update announce set `announcedt`='".$announcedt."',`title`='".$title."',`catagory`='".$cmbprod."',`organization`='".$cmborg."',`announce`='".$announce."' where `id`=".$isid."";
        $err="Announcement updated successfully";
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
                header("Location: ".$hostpath."/announcementList.php?res=1&msg=".$err."&mod=6");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/announcementList.php?res=1&msg=".$err."&mod=6");
    }
    
    $conn->close();
}
?>