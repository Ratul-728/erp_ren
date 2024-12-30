<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
        $cdid = $_POST['cdid'];
      header("Location: ".$hostpath."/contactDetail.php.php?res=01&msg='New Entry'&id='".$cdid."'&mod=2");
}
else
{
    if($_REQUEST['action'] == 'addcomndetails')
    {
        $required='';$er=0;
        if ( isset( $_POST['addmeet'] ) ) 
        {

          $cdid = $_POST['cdid'];
          $meet_note= $_REQUEST['meet_note'];
          $meet_dt = $_POST['meet_dt'];
          $meeting_place = $_POST['meeting_place'];
          //$cmbstatus = $_POST['cmbstatus'];
          $cmbstatus = 3; //echo $cmbstatus;die;
          $hrid = $_POST['usrid'];
          
          $amt = 0;  $comntp=1; 
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($meet_note==''){$required=$required.'Meeting Note Required!!';$er=1;}
          if($meet_dt==''){$required=$required.'Meeting Date Required!!';$er=1;}
          if($meeting_place==''){$meeting_place='NULL';}
          //{$required=$required.'Meeting Place Required!!';$er=1;}
          if($cmbstatus==''){$required=$required.'Meeting Status Required!!';$er=1;} 
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %h:%i %p');
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$meet_dt."', '%d/%m/%Y %H:%i:%s'),'".$meet_note."','".$meeting_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            $err="A Meeting created successfully";
            //echo $qry;die;
             //if ($conn->query($cusqry) == TRUE) {
               //  $err="contatct updared successfully";  
                 
             //}
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addcall'] ) ) 
        {
          $cdid = $_POST['cdid'];
          $comm_note= $_REQUEST['call_note'];
          $comm_dt = $_POST['call_dt'];
          $comm_place = '';
          $cmbstatus = '0';
         
          $amt = 0;  $comntp=2;   $hrid = $_POST['usrid'];
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;} 
          if($comm_note==''){$required=$required.'Call Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'Call Date Required!!';$er=1;}
         
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %H:%i:%s');
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %H:%i:%s'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
            // if ($conn->query($cusqry) == TRUE) {
                 $err="A Call record created successfully"; 
           //      }
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addsms'] ) ) 
        {
          $cdid = $_POST['cdid'];
          $comm_note= $_REQUEST['sms_note'];
          $comm_dt = $_POST['sms_dt'];
          $comm_place = '';
          $cmbstatus = '0';
         
          $amt = 0;  $comntp=3;   $hrid = $_POST['usrid'];
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.'SMS Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'SMS Date Required!!';$er=1;}
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %H:%i:%s');
          
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %H:%i:%s'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
            // if ($conn->query($cusqry) == TRUE) {
                 $err="A SMS record created successfully"; 
             //    }
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addemail'] ) ) 
        {
          $cdid = $_POST['cdid'];
          $comm_note= $_REQUEST['email_note'];
          $comm_dt = $_POST['email_dt'];
          $comm_place = '';
          $cmbstatus = '0';
         
          $amt = 0;  $comntp=4;   $hrid = $_POST['usrid'];
           
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %H:%i:%s');
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.'Email Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'Email Date Required!!';$er=1;}
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %H:%i:%s'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
            // if ($conn->query($cusqry) == TRUE) { 
                 $err="A email record created successfully";  
                 
            // }
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addorder'] ) ) 
        {
          $cdid = $_POST['cdid'];
          $comm_note= $_REQUEST['order_note'];
          $comm_dt = $_POST['order_dt'];
          $comm_place = '';
          $cmbstatus = '0';
         
          $amt = 0;  $comntp=5;   $hrid = $_POST['usrid'];
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.'Order Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'Order Date Required!!';$er=1;}
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %H:%i:%s');
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %H:%i:%s'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
            // if ($conn->query($cusqry) == TRUE) {
                 $err="A order record created successfully"; 
            //     }
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addpayment'] ) ) 
        {
           $cdid = $_POST['cdid'];
          $comm_note= $_REQUEST['payment_note'];
          $comm_dt = $_POST['payment_dt'];
          $comm_place = '';
          $cmbstatus = '0';
          $amt =  $_REQUEST['amount'];
          
          $comntp=6;   $hrid = $_POST['usrid'];
           
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %H:%i:%s');
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.'Payment Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'Pament Date Required!!';$er=1;}
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %H:%i:%s'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
          //   if ($conn->query($cusqry) == TRUE) { 
                 $err="A payment record created successfully"; 
           //      }
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addcomment'] ) ) 
        {
          $cdid = $_POST['cdid'];
          $comm_note= $_REQUEST['comment_note'];
          $comm_dt = $_POST['comment_dt'];
          $comm_place = '';
          $cmbstatus = '0';
          $amt =  0;
          
          $comntp=7;   $hrid = $_POST['usrid'];
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.'Comment Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'Comment Date Required!!';$er=1;}
           
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %H:%i:%s');
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %H:%i:%s'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
             //if ($conn->query($cusqry) == TRUE) {
                 $err="A comment record created successfully";  
                 
            // }
         //echo $qry; die;
        }
        
         else if ( isset( $_POST['addother'] ) ) 
        {
          $cdid = $_POST['cdid'];
          $comm_note= $_REQUEST['other_note'];
          $comm_dt = $_POST['other_dt'];
          $comm_place = '';
          $cmbstatus = '0';
          $amt =  0;
          
          $comntp=8;   $hrid = $_POST['usrid'];
           
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %h:%i %p');
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.' Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.' Date Required!!';$er=1;}
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %H:%i:%s'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
            // if ($conn->query($cusqry) == TRUE) {
                 $err="An other record created successfully"; 
               //  }
         //echo $qry; die;
        }
        
        else{}
    }
    if($_REQUEST['action'] == 'addcomndetails_org')
    {
        $required='';$er=0;
        if ( isset( $_POST['addmeet'] ) ) 
        {
          $cdid = $_POST['meet_cdid'];
          $meet_note= $_REQUEST['meet_note'];
          $meet_dt = $_POST['meet_dt'];
          $meeting_place = $_POST['meeting_place'];
          $cmbstatus = $_POST['cmbstatus'];
          $hrid = $_POST['usrid'];
          $cmbstatus = 3;
          $amt = 0;  $comntp=1; 
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($meet_note==''){$required=$required.'Meeting Note Required!!';$er=1;}
          if($meet_dt==''){$required=$required.'Meeting Date Required!!';$er=1;}
            if($meeting_place==''){$meeting_place='NULL';}
          //if($meeting_place==''){$required=$required.'Meeting Place Required!!';$er=1;}
          if($cmbstatus==''){$required=$required.'Meeting Status Required!!';$er=1;} 
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %h:%i %p');
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$meet_dt."', '%d/%m/%Y %h:%i %p'),'".$meet_note."','".$meeting_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            $err="A Meeting created successfully";
            //echo $qry;die;
             //if ($conn->query($cusqry) == TRUE) {
               //  $err="contatct updared successfully";  
                 
             //}
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addcall'] ) ) 
        {
          $cdid = $_POST['call_cdid'];
          $comm_note= $_REQUEST['call_note'];
          $comm_dt = $_POST['call_dt'];
          $comm_place = '';
          $cmbstatus = '0';
         
          $amt = 0;  $comntp=2;   $hrid = $_POST['usrid'];
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;} 
          if($comm_note==''){$required=$required.'Call Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'Call Date Required!!';$er=1;}
         
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %h:%i %p');
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %h:%i %p'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
            // if ($conn->query($cusqry) == TRUE) {
                 $err="A Call record created successfully"; 
           //      }
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addsms'] ) ) 
        {
          $cdid = $_POST['sms_cdid'];
          $comm_note= $_REQUEST['sms_note'];
          $comm_dt = $_POST['sms_dt'];
          $comm_place = '';
          $cmbstatus = '0';
         
          $amt = 0;  $comntp=3;   $hrid = $_POST['usrid'];
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.'SMS Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'SMS Date Required!!';$er=1;}
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %h:%i %p');
          
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %h:%i %p'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
            // if ($conn->query($cusqry) == TRUE) {
                 $err="A SMS record created successfully"; 
             //    }
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addemail'] ) ) 
        {
          $cdid = $_POST['email_cdid'];
          $comm_note= $_REQUEST['email_note'];
          $comm_dt = $_POST['email_dt'];
          $comm_place = '';
          $cmbstatus = '0';
         
          $amt = 0;  $comntp=4;   $hrid = $_POST['usrid'];
           
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %h:%i %p');
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.'Email Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'Email Date Required!!';$er=1;}
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %h:%i %p'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
            // if ($conn->query($cusqry) == TRUE) { 
                 $err="A email record created successfully";  
                 
            // }
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addorder'] ) ) 
        {
          $cdid = $_POST['order_cdid'];
          $comm_note= $_REQUEST['order_note'];
          $comm_dt = $_POST['order_dt'];
          $comm_place = '';
          $cmbstatus = '0';
         
          $amt = 0;  $comntp=5;   $hrid = $_POST['usrid'];
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.'Order Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'Order Date Required!!';$er=1;}
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %h:%i %p');
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %h:%i %p'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
            // if ($conn->query($cusqry) == TRUE) {
                 $err="A order record created successfully"; 
            //     }
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addpayment'] ) ) 
        {
           $cdid = $_POST['payment_cdid'];
          $comm_note= $_REQUEST['payment_note'];
          $comm_dt = $_POST['payment_dt'];
          $comm_place = '';
          $cmbstatus = '0';
          $amt =  $_REQUEST['amount'];
          
          $comntp=6;   $hrid = $_POST['usrid'];
           
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %h:%i %p');
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.'Payment Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'Pament Date Required!!';$er=1;}
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %h:%i %p'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
          //   if ($conn->query($cusqry) == TRUE) { 
                 $err="A payment record created successfully"; 
           //      }
         //echo $qry; die;
        }
        
        else if ( isset( $_POST['addcomment'] ) ) 
        {
          $cdid = $_POST['comment_cdid'];
          $comm_note= $_REQUEST['comment_note'];
          $comm_dt = $_POST['comment_dt'];
          $comm_place = '';
          $cmbstatus = '0';
          $amt =  0;
          
          $comntp=7;   $hrid = $_POST['usrid'];
          
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.'Comment Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.'Comment Date Required!!';$er=1;}
           
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %h:%i %p');
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %h:%i %p'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
             //if ($conn->query($cusqry) == TRUE) {
                 $err="A comment record created successfully";  
                 
            // }
         //echo $qry; die;
        }
        
         else if ( isset( $_POST['addother'] ) ) 
        {
          $cdid = $_POST['other_cdid'];
          $comm_note= $_REQUEST['other_note'];
          $comm_dt = $_POST['other_dt'];
          $comm_place = '';
          $cmbstatus = '0';
          $amt =  0;
          
          $comntp=8;   $hrid = $_POST['usrid'];
           
          //$cmndt=STR_TO_DATE($meet_dt, '%d/%m/%Y %h:%i %p');
          if($cdid==''){$required=$required.' Contact Required!!';$er=1;}
          if($comm_note==''){$required=$required.' Note Required!!';$er=1;}
          if($comm_dt==''){$required=$required.' Date Required!!';$er=1;}
          
            $qry="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$cdid.",".$comntp.",STR_TO_DATE('".$comm_dt."', '%d/%m/%Y %h:%i %p'),'".$comm_note."','".$comm_place."',".$cmbstatus.",".$amt.",".$hrid.",sysdate())" ;
            
           // echo $qry;die;
            // if ($conn->query($cusqry) == TRUE) {
                 $err="An other record created successfully"; 
               //  }
         //echo $qry; die;
        }
        
        else{}
    }
   
    if($er==1)
    {
        $err=$required;
        //header("Location: ".$hostpath."/contactDetail.php?res=2&msg=".$err."&id=".$cdid."&mod=2");
         echo $err;
    }
    else
    {
        if ($conn->connect_error) {
           echo "Connection failed: " . $conn->connect_error;
        }
        
        if ($conn->query($qry) == TRUE) {
                    //header("Location: ".$hostpath."/contactDetail.php?res=1&msg=".$err."&id=".$cdid."&mod=2");
                     echo $err;
        } else {
             $err="Error: " . $qry . "<br>" . $conn->error;
              //header("Location: ".$hostpath."/contactDetail.php?res=2&msg=".$err."&id=".$cdid."&mod=2");
               echo $err;
        }
    }
        
    $conn->close();
}
?>