<?php
require "conn.php";
include_once('./email_config.php');
include_once('../email_messages/email_user_message.php');

session_start();


if ( isset( $_POST['cancel'] ) ) {
      echo "<script>top.window.location = '../hcList.php?mod=4'</script>";
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        $code= $_REQUEST['cd'];
        //ID generated
        $qryCh = "SELECT MAX(id) maxid FROM `hr` ";
        $resultCh = $conn->query($qryCh); 
        while($rowch = $resultCh->fetch_assoc()) {
            $maxid = $rowch["maxid"];
        }
        $code = sprintf("EMP-%06d", $maxid);
        
        $fnm = $_POST['fnm']; 
        $lnm = $_POST['lnm'];               ////if($lnm==''){$lnm="";} else {$lnm = "'$lnm'"; };
        $dob = $_POST['dob'];
        if($dob !=''){
            $dob = str_replace('/', '-', $dob);
            $dob = date('Y-m-d', strtotime($dob));
        }
        
        // Use regex to match the number part
        preg_match('/\d+$/', $code, $matches);
        
        if (!empty($matches)) {
            $att_id = (int)$matches[0]; // Convert to integer to remove leading zeros
        } else {
            $att_id = 0;
        }
        
        $cmbdsg = $_POST['cmbdsg'];         //if($cmbdsg==''){$cmbdsg='';} else {$cmbdsg = "'$cmbdsg'"; };
        $cmbmartial = $_POST['cmbmartial']; //if($cmbmartial==''){$cmbmartial='';} else {$cmbmartial = "'$cmbmartial'"; };
        $nid = $_POST['nid'];               //if($nid==''){$nid='';} else {$nid = "'$nid'"; };
        $tin = $_POST['tin'];               //if($tin==''){$tin='';} else {$tin = "'$tin'"; };
        $cmbbg = $_POST['cmbbg'];           //if($cmbbg==''){$cmbbg='NULL';} else {$cmbbg = "'$cmbbg'"; };
        $pp = $_POST['pp'];                 //if($pp==''){$pp='NULL';} else {$pp = "'$pp'"; };
        $drvid = $_POST['drvid'];           //if($drvid==''){$drvid='NULL';} else {$drvid = "'$drvid'"; };
        $preaddr = $_POST['preaddr'];       //if($preaddr==''){$preaddr='NULL';} else {$preaddr = "'$preaddr'"; };
        $area= $_POST['area'];              //if($area==''){$area='NULL';} else {$area = "'$area'"; };
        $zip = $_POST['zip'];               //if($zip==''){$zip='NULL';} else {$zip = "'$zip'"; };
        $district = $_POST['district'];     //if($district==''){$district='NULL';} else {$district = "'$district'"; };
        $country = $_POST['country'];       //if($country==''){$country='NULL';} else {$country = "'$country'"; };
        
        $permanentaddress = $_POST['permanentaddr'];       //if($preaddr==''){$preaddr='NULL';} else {$preaddr = "'$preaddr'"; };
        $permanentarea= $_POST['permanentarea'];              //if($area==''){$area='NULL';} else {$area = "'$area'"; };
        $permanentdistrict = $_POST['permanentdistrict'];               //if($zip==''){$zip='NULL';} else {$zip = "'$zip'"; };
        $permanentzip = $_POST['permanentzip'];     //if($district==''){$district='NULL';} else {$district = "'$district'"; };
        $permanentcountry = $_POST['permanentcountry'];       //if($country==''){$country='NULL';} else {$country = "'$country'"; };
        
        $off_cont = $_POST['off_cont'];     //if($off_cont==''){$off_cont='NULL';} else {$off_cont = "'$off_cont'"; };
        $ext = $_POST['ext'];               //if($ext==''){$ext='NULL';}  else {$ext = "'$ext'"; };
        $per_cont = $_POST['per_cont'];     //if($per_cont==''){$per_cont='NULL';} else {$per_cont = "'$per_cont'"; };
        $alt_cont = $_POST['alt_cont'];     //if($alt_cont==''){$alt_cont='NULL';} else {$alt_cont = "'$alt_cont'"; };
        $ofc_email = $_POST['ofc_email'];   //if($ofc_email==''){$ofc_email='NULL';} else {$ofc_email = "'$ofc_email'"; };
        $per_email = $_POST['per_email'];   //if($per_email==''){$per_email='NULL';} else {$per_email = "'$per_email'"; };
        $alt_email = $_POST['alt_email'];   //if($alt_email==''){$alt_email='NULL';} else {$alt_email = "'$alt_email'"; };
        $poc1 = $_POST['poc1'];             //($poc1==''){$poc1='NULL';} else {$poc1 = "'$poc1'"; };
        $poc1_rel = $_POST['poc1_rel'];     //if($poc1_rel==''){$poc1_rel='';} else {$poc1_rel = "poc1_rel"; };
        $poc1_cont = $_POST['poc1_cont'];   //($poc1_cont==''){$poc1_cont='NULL';} else {$poc1_cont = "'$poc1_cont'"; };
        $poc1_addr = $_POST['poc1_addr'];   //if($poc1_addr==''){$poc1_addr='NULL';} else {$poc1_addr = "'$poc1_addr'"; };
        $poc2 = $_POST['poc2'];             //if($poc2==''){$poc2='NULL';} else {$poc2 = "'$poc2'"; };
        $poc2_rel = $_POST['poc2_rel'];     //if($poc2_rel==''){$poc2_rel='NULL';} else {$poc2_rel = "'$poc2_rel'"; };
        $poc2_cont = $_POST['poc2_cont'];   //if($poc2_cont==''){$poc2_cont='NULL';} else {$poc2_cont = "'$poc2_cont'"; };
        $poc2_addr = $_POST['poc2_addr'];   //if($poc2_addr==''){$poc2_addr='NULL';} else {$poc2_addr = "'$poc2_addr'"; };
        $poc3 = $_POST['poc3'];             //if($poc3==''){$poc3='NULL';} else {$poc3 = "'$poc3'"; };
        $poc3_rel = $_POST['poc3_rel'];     //if($poc3_rel==''){$poc3_rel='NULL';} else {$poc3_rel = "'$poc3_rel'"; };
        $poc3_cont = $_POST['poc3_cont'];   //if($poc3_cont==''){$poc3_cont='NULL';} else {$poc3_cont = "'$poc3_cont'"; };
        $poc3_addr = $_POST['poc3_addr'];   //if($poc3_addr==''){$poc3_addr='NULL';} else {$poc3_addr = "'$poc3_addr'"; };
        $hrid = $_SESSION["user"]; 
      
      
        $totalup = count($_FILES['attachment1']['name']);
        $att1=$code;
        $tmpFilePath = $_FILES['attachment1']['tmp_name'];
        if ($tmpFilePath != ""){ $newFilePath = "upload/hc/" .$code.".jpg";
                 $didUpload = move_uploaded_file($tmpFilePath, $newFilePath); }
        /*
        for( $j=0 ; $j < $totalup ; $j++ ) {
             $tmpFilePath = $_FILES['attachment1']['tmp_name'][$j];
             if ($tmpFilePath != ""){ $newFilePath = "upload/item/" .$code. $_FILES['attachment1']['name'][$j];
                 $didUpload = move_uploaded_file($tmpFilePath, $newFilePath);
                 $att1=$att1.",".$_FILES['attachment1']['name'][$j];
             }
        }
        */
    
       // $hrid= '1';
        $make_date=date('Y-m-d H:i:s');
        $op_date =date('Y-m-d');
        
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $passw =substr(str_shuffle($chars),0,8);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
        $qry="insert into employee(`employeecode`, `firstname`, `lastname`, `dob`, `gender`, `maritialstatus`, `nid`, `tin`, `bloodgroup`, `pp`, `drivinglicense`, `presentaddress`, `area`, `district`, `postal`, `country`, `office_contact`, `ext_contact`, `pers_contact`, `alt_contact`, `office_email`, `pers_email`, `alt_email`, `emergency_poc1`, `poc1_relation`, `poc1_contact`, `poc1_address`, `emergency_poc2`, `poc2_relation`, `poc2_contact`, `poc2_address`, `emergency_poc3`, `poc3_relation`, `poc3_contact`, `poc3_address`, `photo`,  `opdate`, `makeby`, `makedt`, `permanentaddress`, `permanentarea`, `permanentdistrict`, `permanentpostal`, `permanentcountry`)  
        values('".$code."','".$fnm."','".$lnm."','".$dob."','".$cmbdsg."','".$cmbmartial."','".$nid."','".$tin."','".$cmbbg."','".$pp."','".$drvid."','".$preaddr."','".$area."','".$district."','".$zip."','".$country."','".$off_cont."','".$ext."','".$per_cont."','".$alt_cont."','".$ofc_email."','".$per_email."','".$alt_email."','".$poc1."','".$poc1_rel."','".$poc1_cont."','".$poc1_addr."','".$poc2."','".$poc2_rel."','".$poc2_cont."','".$poc2_addr."','".$poc3."','".$poc3_rel."','".$poc3_cont."','".$poc3_addr."','".$code."','".$op_date."','".$hrid."','".$make_date."','".$permanentaddress."','".$permanentarea."','".$permanentdistrict."','".$permanentzip."','".$permanentcountry."')" ;
        $err="Employee created successfully";
        //echo $qry;die;
                        //if($ofc_email=='NULL'){$empEmail='huq.sami@hotmail.com';} else{ $empEmail=$ofc_email;}
                        if($per_cont==''){$cell='01687810552';} else{ $cell=$per_cont;}
                        $usrqry="insert into hr(`emp_id`, `resourse_id`, `hidden_char`, `hrName`, `user_tp`, `email`, `cellNo`, `active_st`, `make_dt`, `attendance_id`)
                        values( '".$code."','".$code."','".$passw."','".$fnm."',2,'".$ofc_email."','".$cell."','1','".$make_date."', '".$att_id."')";
                        //echo $itqry;die;
                         if ($conn->query($usrqry) == TRUE) { $err="User added successfully"; }
        //Send mail
        $mailsubject = "New User!!!";
        $message = "Dear $fnm $lnm,
                    Welcome to ".$_SESSION["comname"]." team! We're thrilled to have you on board. 
                    We can't wait to see the incredible contributions we know you'll make.
                    
                    Get ready for an exciting adventure at ".$_SESSION["comname"]."!

                    Your username: $code
                    Your email: $ofc_email
                    Your password: $passw
                    From Renaissance Team";
        if($ofc_email != '') mail($ofc_email,$mailsubject,$message);
        if($per_email != '') mail($per_email,$mailsubject,$message);
  //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $aid= $_REQUEST['acid'];
        $code= $_REQUEST['cd'];
        $qryCh = "SELECT id FROM `employee` WHERE id != $aid and employeecode = '$code'";
        $resultCh = $conn->query($qryCh); 
        if ($resultCh->num_rows > 0) {
            $err="Employee ID already exist!";
            echo "<script>top.window.location = '../hcList.php?res=2&msg=".$err."&mod=4'</script>";
            die;
        }
        $fnm = $_POST['fnm'];
        $lnm = $_POST['lnm']; //if($lnm==''){$lnm="NULL";} else {$lnm = "'$lnm'"; };
        $dob = $_POST['dob']; 
        if($dob !=''){
            $dob = str_replace('/', '-', $dob);
            $dob = date('Y-m-d', strtotime($dob));
        }
        
        $cmbdsg = $_POST['cmbdsg'];  //if($cmbdsg==''){$cmbdsg='NULL';} else {$cmbdsg = "'$cmbdsg'"; };
        $cmbmartial = $_POST['cmbmartial']; //if($cmbmartial==''){$cmbmartial='NULL';} else {$cmbmartial = "'$cmbmartial'"; };
        $nid = $_POST['nid']; //if($nid==''){$nid='NULL';} else {$nid = "'$nid'"; };
        $tin = $_POST['tin']; //if($tin==''){$tin='NULL';} else {$tin = "'$tin'"; };
        $cmbbg = $_POST['cmbbg']; //if($cmbbg==''){$cmbbg='NULL';} else {$cmbbg = "'$cmbbg'"; };
        $pp = $_POST['pp']; //if($pp==''){$pp='NULL';} else {$pp = "'$pp'"; };
        $drvid = $_POST['drvid']; //if($drvid==''){$drvid='NULL';} else {$drvid = "'$drvid'"; };
        $preaddr = $_POST['preaddr']; //if($preaddr==''){$preaddr='NULL';} else {$preaddr = "'$preaddr'"; };
        $area= $_POST['area']; //if($area==''){$area='NULL';} else {$area = "'$area'"; };
        $zip = $_POST['zip']; //if($zip==''){$zip='NULL';} else {$zip = "'$zip'"; };
        $district = $_POST['district']; //if($district==''){$district='NULL';} else {$district = "'$district'"; };
        $country = $_POST['country']; //if($country==''){$country='NULL';} else {$country = "'$country'"; };
        
        $permanentaddress = $_POST['permanentaddr'];       //if($preaddr==''){$preaddr='NULL';} else {$preaddr = "'$preaddr'"; };
        $permanentarea= $_POST['permanentarea'];              //if($area==''){$area='NULL';} else {$area = "'$area'"; };
        $permanentdistrict = $_POST['permanentdistrict'];               //if($zip==''){$zip='NULL';} else {$zip = "'$zip'"; };
        $permanentzip = $_POST['permanentzip'];     //if($district==''){$district='NULL';} else {$district = "'$district'"; };
        $permanentcountry = $_POST['permanentcountry'];       //if($country==''){$country='NULL';} else {$country = "'$country'"; };
        
       
        $off_cont = $_POST['off_cont'];  //if($off_cont==''){$off_cont='NULL';} else {$off_cont = "'$off_cont'"; };
        $ext = $_POST['ext']; //if($ext==''){$ext='NULL';}  else {$ext = "'$ext'"; };
        $per_cont = $_POST['per_cont']; //if($per_cont==''){$per_cont='NULL';} else {$per_cont = "'$per_cont'"; };
        $alt_cont = $_POST['alt_cont']; //if($alt_cont==''){$alt_cont='NULL';} else {$alt_cont = "'$alt_cont'"; };
        $ofc_email = $_POST['ofc_email']; //($ofc_email==''){$ofc_email='NULL';} else {$ofc_email = "'$ofc_email'"; };
        $per_email = $_POST['per_email']; //if($per_email==''){$per_email='NULL';} else {$per_email = "'$per_email'"; };
        $alt_email = $_POST['alt_email']; //if($alt_email==''){$alt_email='NULL';} else {$alt_email = "'$alt_email'"; };
        $poc1 = $_POST['poc1']; //if($poc1==''){$poc1='NULL';} else {$poc1 = "'$poc1'"; };
        $poc1_rel = $_POST['poc1_rel']; //if($poc1_rel==''){$poc1_rel='';} else {$poc1_rel = "poc1_rel"; };
        $poc1_cont = $_POST['poc1_cont']; //if($poc1_cont==''){$poc1_cont='NULL';} else {$poc1_cont = "'$poc1_cont'"; };
        $poc1_addr = $_POST['poc1_addr']; //if($poc1_addr==''){$poc1_addr='NULL';} else {$poc1_addr = "'$poc1_addr'"; };
        $poc2 = $_POST['poc2']; //if($poc2==''){$poc2='NULL';} else {$poc2 = "'$poc2'"; };
        $poc2_rel = $_POST['poc2_rel']; //if($poc2_rel==''){$poc2_rel='NULL';} else {$poc2_rel = "'$poc2_rel'"; };
        $poc2_cont = $_POST['poc2_cont']; //if($poc2_cont==''){$poc2_cont='NULL';} else {$poc2_cont = "'$poc2_cont'"; };
        $poc2_addr = $_POST['poc2_addr']; //if($poc2_addr==''){$poc2_addr='NULL';} else {$poc2_addr = "'$poc2_addr'"; };
        $poc3 = $_POST['poc3']; //if($poc3==''){$poc3='NULL';} else {$poc3 = "'$poc3'"; };
        $poc3_rel = $_POST['poc3_rel']; //if($poc3_rel==''){$poc3_rel='NULL';} else {$poc3_rel = "'$poc3_rel'"; };
        $poc3_cont = $_POST['poc3_cont']; //if($poc3_cont==''){$poc3_cont='NULL';} else {$poc3_cont = "'$poc3_cont'"; };
        $poc3_addr = $_POST['poc3_addr']; //if($poc3_addr==''){$poc3_addr='NULL';} else {$poc3_addr = "'$poc3_addr'"; };
        
        
        //$qry="insert into employee(`employeecode`, `firstname`, `lastname`, `dob`, `gender`, `maritialstatus`, `nid`, `tin`, `bloodgroup`, `pp`, `drivinglicense`, `presentaddress`, `area`, `district`, `postal`, `country`, `office_contact`, `ext_contact`, `pers_contact`, `alt_contact`, `office_email`, `pers_email`, `alt_email`, `emergency_poc1`, `poc1_relation`, `poc1_contact`, `poc1_address`, `emergency_poc2`, `poc2_relation`, `poc2_contact`, `poc2_address`, `emergency_poc3`, `poc3_relation`, `poc3_contact`, `poc3_address`, `photo`,  `opdate`, `makeby`, `makedt`) 
        //values('".$code."','".$fnm."','".$lnm."',STR_TO_DATE('".$dob."', '%Y/%m/%d'),'".$cmbdsg."','".$cmbmartial."','".$nid."','".$tin."','".$cmbbg."','".$pp."','".$drvid."','".$preaddr."','".$area."',".$district.",'".$zip."',".$country.",'".$off_cont."','".$ext."','".$per_cont."','".$alt_cont."','".$ofc_email."','".$per_email."','".$alt_email."','".$poc1."','".$poc1_rel."','".$poc1_cont."','".$poc1_addr."','".$poc2."','".$poc2_rel."','".$poc2_cont."','".$poc2_addr."','".$poc3."','".$poc3_rel."','".$poc3_cont."','".$poc3_addr."','".$code."','".$op_date."','".$hrid."','".$make_date."')" ;
        
        $qry="update employee set `firstname`='".$fnm."',`lastname`='".$lnm."',`dob`='".$dob."',`gender`='".$cmbdsg."',`maritialstatus`='".$cmbmartial."',`nid`='".$nid.
                "',`tin`='".$tin."',`bloodgroup`='".$cmbbg."',`pp`='".$pp."',`drivinglicense`='".$drvid."',`presentaddress`='".$preaddr."',`area`='".$area."',`district`='".$district."',`postal`='".$zip."',`country`='".$country."',`alt_contact` = '".$alt_cont."', `ext_contact` = '".$ext."',
                `office_contact`='".$off_cont."', `pers_email` = '".$per_email."', `pers_contact` ='".$per_cont."' ,`alt_email` = '".$alt_email."', `emergency_poc1` = '".$poc1."',
                `poc1_relation` = '".$poc1_rel."', `poc1_contact` = '".$poc1_cont."', `poc1_address` = '".$poc1_addr."', `emergency_poc2` = '".$poc2."', `poc2_relation` = '".$poc2_rel."', `poc2_contact` = '".$poc2_cont."', `poc2_address` = '".$poc2_addr."',`office_email` = '".$ofc_email."',
                `emergency_poc3` = '".$poc3."', `poc3_relation` = '".$poc3_rel."', `poc3_contact` = '".$poc3_cont."', `poc3_address` = '".$poc3_addr."', `employeecode`='$code',
                `permanentaddress` = '".$permanentaddress."', `permanentarea` = '".$permanentarea."', `permanentdistrict` = '".$permanentdistrict."', `permanentpostal` = '".$permanentzip."', `permanentcountry`='$permanentcountry' where `id`=".$aid."";
        $err="Employee updated successfully";
        //echo $qry;die;
        
        //echo $qry;die;
        $totalup = count($_FILES['attachment1']['name']);
        $att1=$code;
        $tmpFilePath = $_FILES['attachment1']['tmp_name'];
        if ($tmpFilePath != ""){ $newFilePath = "upload/hc/" .$code.".jpg";
                 $didUpload = move_uploaded_file($tmpFilePath, $newFilePath); }
        
        //echo $qry;die;
    }
    
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
        echo "<script>top.window.location = '../hcList.php?res=1&msg=".$err."&mod=4'</script>";
        //header("Location: ".$hostpath."/hcList.php?res=1&msg=".$err."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
         echo "<script>top.window.location = '../hcList.php?res=2&msg=".$err."&mod=4'</script>";
          //header("Location: ".$hostpath."/hcList.php?res=2&msg=".$err."&mod=4");
    }
    
    $conn->close();
}
?>