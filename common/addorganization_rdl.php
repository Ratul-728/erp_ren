<?php
require "conn.php";
include_once('../rak_framework/fetch.php');
require_once("../rak_framework/edit.php");

//ini_set('display_errors', 1);
//print_r($_POST); die;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/organization.php?res=01&msg='New Entry'&id=''&mod=2");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
       //organization
        $nm = str_replace("'","''",$_POST['cnnm']);
        $cat_id=$_POST['cat_id'];
        $cmbindtype = $_POST['cmbindtype'];
        $address = str_replace("'","''",$_POST['address']);
        $contactname = $_POST['contactname'];           //if($phone==''){$phone='NULL';}
        $contactemail = $_POST['contactemail'];           //if($email==''){$email='NULL';}
        $contactphone = $_POST['contactphone'];
        $note =str_replace("'","''",$_POST['note']); 
         
         //individual
         $indvnm = str_replace("'","''",$_POST['contname']);
         $contemail = $_POST['contemail'];
         $contphone = $_POST['contphone'];
         $dob = $_POST['dob'];
         $contgender = $_POST['cmbdsg'];
         $ind_address = $_POST['ind_address'];
         $ind_district = $_POST['district'];
         $zip = $_POST['zip'];               //if($zip==''){$zip='NULL';}
         $country = $_POST['country'];
        
        if($dob != ''){
            $dob = str_replace('/', '-', $dob);                 
            $dob = date('Y-m-d', strtotime($dob)); 
        }
     
        
         $hrid = $_POST['usrid'];
       // $billcntper = $_POST['billcntper']; if($billcntper==''){$billcntper='NULL';}
       // $techcntper = $_POST['techcntper']; if($techcntper==''){$techcntper='NULL';}
        
        $contactcode=0;
        $orcode=0;
        $type=1;
        if($indvnm!='')
        {
            $type='2';
            $nm=$indvnm;
            $contactname=$indvnm;
            $contactphone=$contphone;
            $contactemail=$contemail;
            $address=$ind_address;
            
            
        }
        else
        {
            $type='1';
            $indvnm = $contactname;
            $contphone = $contactphone;
            $contemail = $contactemail;
        }

        $orcode = getFormatedUniqueID('organization','id','CUS-',6,"0");
        
        $qry="insert into organization(`name`, `orgcode`, `type`, `contactperson`, `contactno`, `industry`, `email`, `address`, `district`, `country`, `balance`, `reserve_balance`, `note` ,makedt) 
        values('".$nm."','".$orcode."','".$type."','".$contactname."','".$contactphone."','".$cmbindtype."','".$contactemail."','".$address."','".$ind_district."','".$country."','0','0','".$note."',SYSDATE())" ;
        $err="Organization created successfully";
       
        //print_r($contname);die;
        
            $make_date=date('Y-m-d H:i:s');
            $op_date =date('Y-m-d');
            $lead_state=5;
           // $status=1;
            $cmbcontyp=1;
         $contcode = getFormatedUniqueID('contact','id','CON-',6,"0");
         
         $qry_contact="insert into contact(`contacttype`, `name`, `organization`, `dob`, `phone`, `email`, `gender`, `contactcode`,`area`, `district`, `zip`, `country`,`lead_state`, `makeby`, `makedt`) 
            values('1','".$indvnm."','".$orcode."','".$dob."','".$contphone."','".$contemail."','".$contgender."','".$contcode."','".$ind_address."','".$ind_district."','".$zip."','".$country."','".$lead_state."','".$hrid."',SYSDATE())" ;
            // echo $qry_contact;die;
         if ($conn->query($qry_contact) == TRUE) { $err="Contact added successfully";  }
                        
         
        
        
  //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
        
        $aid= $_REQUEST['orid'];
        $nm = str_replace("'","''",$_POST['cnnm']);
        $cmbindtype = $_POST['cmbindtype'];
        $address = str_replace("'","''",$_POST['address']);
        $contactname = $_POST['contactname'];           //if($phone==''){$phone='NULL';}
        $contactemail = $_POST['contactemail'];           //if($email==''){$email='NULL';}
        $contactphone = $_POST['contactphone'];
        $note =str_replace("'","''",$_POST['note']); 
        $cat_id=$_POST['cat_id'];
         //individual
         $indvnm = str_replace("'","''",$_POST['contname']);
         $contemail = $_POST['contemail'];
         $contphone = $_POST['contphone'];
         $dob = $_POST['dob'];
         $contgender = $_POST['cmbdsg'];
         $ind_address = $_POST['ind_address'];
         $ind_district = $_POST['district'];
         $zip = $_POST['zip'];               //if($zip==''){$zip='NULL';}
         $country = $_POST['country'];
         
        if($dob != ''){
            $dob = str_replace('/', '-', $dob);                 
            $dob = date('Y-m-d', strtotime($dob));        
        }
     
        
         $hrid = $_POST['usrid'];
         
         $type=1;
        if($indvnm!='')
        { 
            $type='2';
            $nm=$indvnm;
            if($contactname==''){$contactname=$indvnm;}
            $contactphone=$contphone;
            $contactemail=$contemail;
            $address=$ind_address;
            
            
        }
        else
        {
            $type='1';
            $indvnm = $contactname;
            $contphone = $contactphone;
            $contemail = $contactemail;
        }
 
         
        
        $qry="update organization set `note`='".$note."',`name`='".$nm."', `contactno`='".$contactphone."',`industry`='".$cat_id."',`email`='".$contactemail."',address='$address'
        ,`district`='".$ind_district."',`zip`='".$zip."',`country`='".$country."' where `id`=".$aid."";
        $err="Organization updated successfully";
        
         $orgcode= fetchByID('organization','id',$aid,orgcode);
         $contactid= fetchByID('contact','organization',$orgcode,id);
        if($contactid=='')
        {
            $contcode = getFormatedUniqueID('contact','id','CON-',6,"0");
            $lead_state=5;
            $qry_contact="insert into contact(`contacttype`, `name`, `organization`, `dob`, `phone`, `email`, `gender`, `contactcode`,`area`, `district`, `zip`, `country`,`lead_state`, `makeby`, `makedt`) 
            values('1','".$contactname."','".$orgcode."','".$dob."','".$contphone."','".$contemail."','".$contgender."','".$contcode."','".$ind_address."','".$ind_district."','".$zip."','".$country."','".$lead_state."','".$hrid."',SYSDATE())" ;
        }
        else
        {
            $qry_contact=" update contact set `name`='$indvnm',`dob`='$dob', `phone`='$contphone',`email`='$contemail', `gender`='$contgender',`area`='$ind_address', `district`='$ind_district', `zip`='$zip', `country`='$country'
           where `id`= $contactid "; 
           // where `organization`= (select `orgcode`  from organization where `id`='$aid')"; 
        }
       // echo $qry_contact;die; 
        if ($conn->query($qry_contact) == TRUE) { $err="Contact added successfully";  }
        
      //  echo $qry; echo $qry_contact ;die;
    } 
    if ( isset( $_POST['convert'] ) ) 
    {
        
        $aid= $_REQUEST['orid'];
        $type= $_REQUEST['orgtp'];
         
        $newtype=1;
        if($type=="1"){$newtype=2;}
        
        $qry="update organization set type=$newtype where  `id`=$aid";
        /*
        $qry="update organization set `note`='".$note."',`name`='".$nm."', `contactno`='".$contactphone."',`industry`='".$cmbindtype."',`email`='".$contactemail."'
        ,`district`='".$ind_district."',`zip`='".$zip."',`country`='".$country."' where `id`=".$aid."";
        $err="Organization updated successfully";
        
      $qry_contact=" update contact set `name`='$indvnm',`dob`='$dob', `phone`='$contphone',`email`='$contemail', `gender`='$contgender',`area`='$ind_address', `district`='$ind_district', `zip`='$zip', `country`='$country'
       where `organization`= (select `orgcode`  from organization where `id`='$aid')"; 
       
       //  echo $qry_contact;die;
        if ($conn->query($qry_contact) == TRUE) { $err="Contact added successfully";  }
        
      */
       // echo $type.'-'.$newtype;die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/organizationList.php?res=1&msg=".$err."&id=".$aid."&mod=3");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/organizationList.php?res=2&msg=".$err."&id=''&mod=3");
    }
    
    $conn->close();
}
?>