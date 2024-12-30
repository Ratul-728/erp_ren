<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/lead.php?res=01&msg='New Entry'&id=''&mod=2");
}
else if ( isset( $_POST['convert'] ) ) {
     $aid= $_REQUEST['ldid'];
     $qry="update contact set `contacttype`=1,`lead_state`=5 where `id`=".$aid."";
        $err="lead Converted successfully";
     if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/contact.php?res=1&msg=".$err."&id=".$aid."&mod=2");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/lead.php?res=2&msg=".$err."&id=''&mod=2");
    }
    
    $conn->close();
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        
        $getquery="SELECT concat(YEAR(CURDATE()),(max(`id`)+1)) cd FROM `contact`";
        $resultcd = $conn->query($getquery);
        if ($resultcd->num_rows > 0) 
        {
            while($rowcd = $resultcd->fetch_assoc()) 
            {
              $code= $rowcd["cd"]; 
            }
        }
        
        
        //$code= $_REQUEST['cd'];
        $nm = $_POST['cnnm'];
        $cmblead = $_POST['cmbcontype'];    if($cmblead==''){$cmblead='NULL';}
        $org = $_POST['org'];               if($org==''){$org='NULL';}
        $dob = $_POST['dob'];               if($dob==''){$dob='NULL';}
        $cmbdsg = $_POST['cmbdsg'];         if($cmbdsg==''){$cmbdsg='NULL';}
        $cmbdpt = $_POST['cmbdpt'];         if($cmbdpt==''){$cmbdpt='NULL';}
        $phone = $_POST['phone'];           if($phone==''){$phone='NULL';}
        $email = $_POST['email'];           if($email==''){$email='NULL';}
        $loc = 1;
        $web = $_POST['web'];               if($web==''){$web='NULL';}
        $cmbsrc = $_POST['cmbsrc'];         if($cmbsrc==''){$cmbsrc='NULL';}
        $srcnm = $_POST['srcnm'];           if($srcnm==''){$srcnm='NULL';}
         $descr = $_POST['descr'];          if($descr==''){$descr='NULL';}
        $area= $_POST['area'];              if($area==''){$area='NULL';}
        $street = $_POST['street'];         if($street==''){$street='NULL';}
        $district = $_POST['district'];     if($district==''){$district='NULL';}
        $state = $_POST['state'];           if($state==''){$state='NULL';}
        $zip = $_POST['zip'];               if($zip==''){$zip='NULL';}
        $country = $_POST['country'];       if($country==''){$country='NULL';}
        $hrid = $_POST['usrid'];           
      
        $totalup = count($_FILES['attachment1']['name']);
        $att1=$code;
        $tmpFilePath = $_FILES['attachment1']['tmp_name'];
        if ($tmpFilePath != ""){ $newFilePath = "upload/contact/" .$code.".jpg";
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
    
        //$hrid= '1';
        $make_date=date('Y-m-d H:i:s');
        $op_date =date('Y-m-d');
        $contacttype=3;//contacttype 3 mean non contact
        $qry="insert into contact(`contactcode`,  `contacttype`, `name`, `organization`, `dob`, `designation`, `department`, `phone`, `email`, `photo`,`location`, `website`, `source`, `sourcename`, `details`, `area`, `street`, `district`, `state`, `lead_state`,`zip`, `country`, `opendt`, `status`, `makeby`, `makedt`,`currbal`) 
        values('".$code."',".$contacttype.",'".$nm."',".$org.",'".$dob."',".$cmbdsg.",".$cmbdpt.",'".$phone."','".$email."','".$att1."','".$loc."','".$web."',".$cmbsrc.",'".$srcnm."','".$descr."','".$area."','".$street."',".$district.",".$state.",".$cmblead.",'".$zip."',".$country.",'".$op_date."','1',".$hrid.",'".$make_date."',0)" ;
        $err="Lead created successfully";
        
        
        
        
  //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $aid= $_REQUEST['ldid'];
        $code= $_REQUEST['cd'];
        $nm = $_POST['cnnm'];
        $cmblead = $_POST['cmbcontype'];    if($cmblead==''){$cmblead='NULL';}
        $org = $_POST['org'];               if($org==''){$org='NULL';}
        $dob = $_POST['dob'];               if($dob==''){$dob='NULL';}
        $cmbdsg = $_POST['cmbdsg'];         if($cmbdsg==''){$cmbdsg='NULL';}
        $cmbdpt = $_POST['cmbdpt'];         if($cmbdpt==''){$cmbdpt='NULL';}
        $phone = $_POST['phone'];           if($phone==''){$phone='NULL';}
        $email = $_POST['email'];           if($email==''){$email='NULL';}
        $loc = 1;
        $web = $_POST['web'];               if($web==''){$web='NULL';}
        $cmbsrc = $_POST['cmbsrc'];         if($cmbsrc==''){$cmbsrc='NULL';}
        $srcnm = $_POST['srcnm'];           if($srcnm==''){$srcnm='NULL';}
         $descr = $_POST['descr'];          if($descr==''){$descr='NULL';}
        $area= $_POST['area'];              if($area==''){$area='NULL';}
        $street = $_POST['street'];         if($street==''){$street='NULL';}
        $district = $_POST['district'];     if($district==''){$district='NULL';}
        $state = $_POST['state'];           if($state==''){$state='NULL';}
        $zip = $_POST['zip'];               if($zip==''){$zip='NULL';}
        $country = $_POST['country'];       if($country==''){$country='NULL';}
        $hrid = $_POST['usrid'];           
        
         $totalup = count($_FILES['attachment1']['name']);
        $att1=$code;
        $tmpFilePath = $_FILES['attachment1']['tmp_name'];
        if ($tmpFilePath != ""){ $newFilePath = "upload/contact/" .$code.".jpg";
                 $didUpload = move_uploaded_file($tmpFilePath, $newFilePath); }
        
        $qry="update contact set `name`='".$nm."',`lead_state`=".$cmblead.", `organization`=".$org.",`dob`='".$dob."',`designation`=".$cmbdsg.",`department`=".$cmbdpt.",`phone`=".$phone.
        ",`email`='".$email."',`location`='".$loc."',`website`='".$web."',`source`=".$cmbsrc.",`sourcename`='".$srcnm."',`details`='".$descr."',`area`='".$area."',`district`=".$district.",`state`=".$state.",`zip`='".$zip."',`country`=".$country." where `id`=".$aid."";
        $err="lead updated successfully";
        
        //echo $qry;die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/leadList.php?res=1&msg=".$err."&id=".$aid."&mod=2");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/lead.php?res=2&msg=".$err."&id=''&mod=2");
    }
    
    $conn->close();
}
?>