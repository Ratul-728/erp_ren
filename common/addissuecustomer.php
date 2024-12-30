<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/issuecustomer.php.php?res=01&msg='New Entry'&id=''&mod=7");
}
else if( isset( $_POST['comment'] ) ) {
     $dt=date("Ymdhis"); 
       //$_REQUEST['tcktid']; 
      $isid= $_REQUEST['issid'];
      $tcktid= $_REQUEST['isstkt'];
      $issuecomment = $_POST['issuecomment'];         if($issuecomment==''){$issuecomment='NULL';}
      $hrid = $_POST['usrid']; 
      
      $qry="insert into isssueactivity( `issueid`,`ticketid`, `activity`, `makeby`, `makedt`) 
        values(".$isid.",'".$tcktid."','".$issuecomment."',".$hrid.",sysdate())" ;
        $err="A Ticket created successfully";
        
        if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/issuecustomer.php?res=4&msg='Update Data'&id=".$isid."&mod=7");
                
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/issuecustomer.php?res=4&msg='Update Data'&id=".$isid."&mod=7");
    }
    
    $conn->close();
}
else
{
    if ( isset( $_POST['add'] ) ) {
     
     $dt=date("Ymdhis"); 
       //$_REQUEST['tcktid']; 
      $subject = $_POST['subject'];         if($subject==''){$subject='NULL';}
      $cmbprod = $_POST['cmbprod'];         if($cmbprod==''){$cmbprod='NULL';}
      $cmbsevere = $_POST['cmbsevere'];     if($cmbsevere==''){$cmbsevere='NULL';}
      $cmbtype = $_POST['cmbisstp'];         if($cmbtype == ''){$cmbtype = 'NULL';}
      $cmbchannel = 8;                   //   if($cmbchannel==''){$cmbchannel='NULL';}
      $issue = $_POST['issue'];             if($issue==''){$issue='NULL';}
      $cmbst = 1;//$_POST['cmbst'];             if($cmbst==''){$cmbst='NULL';}
      
      $hrid = $_POST['usrid'];        
       
      $tcktid=$dt.substr($subject,1,3);
      $chqclearst=0;$st=0; //$hrid= '1';
      
      //Photo
      foreach($_FILES['attachment2']['name'] as $key=>$val)
            { 
            // File upload path 
             $filename1 = $_FILES['attachment2']['tmp_name'][$key];
             $photogalary=$tcktid.$key.".jpg";
             if($filename1!='')
                {
                    $info1 = getimagesize($filename1);
                    $imageWidth = $info1[0];
                    $imageHeight = $info1[1];
                    switch ($info1['mime'])
                    {
                        case 'image/gif':
                            $original = imagecreatefromgif($filename1);
                            $resized = imagecreatetruecolor(800, 600);
                            imagecopyresampled($resized, $original, 0, 0, 0, 0, 800, 600, $imageWidth, $imageHeight);
                            imagejpeg($resized, "../images/upload/issue/".$photogalary);
                        break;
                        case 'image/jpeg':
                            $original = imagecreatefromjpeg($filename1);
                            $resized = imagecreatetruecolor(800, 600);
                            imagecopyresampled($resized, $original, 0, 0, 0, 0, 800, 600, $imageWidth, $imageHeight);
                            imagejpeg($resized, "../images/upload/issue/".$photogalary);
                        break;
                        case 'image/png':
                            $original = imagecreatefrompng($filename1);
                            $resized = imagecreatetruecolor(800, 600);
                            $white = imagecolorallocate($resized, 255, 255, 255);
                            imagefill($resized, 0, 0, $white);
                            imagealphablending($resized, true);
                            imagesavealpha($resized, true);
                            //imagecopy($image, $png, 0, 0, 0, 0, $width, $height);
                            //imagedestroy($png);
                            imagecopyresampled($resized, $original, 0, 0, 0, 0, 800, 600, $imageWidth, $imageHeight);
                            imagejpeg($resized, "../images/upload/issue/".$photogalary);
                        break;
                    }
                    
                    $gimgqry= "INSERT INTO `issuephoto`(`issueticket`, `photo`, `makedt`) VALUES ('".$tcktid."','".$photogalary."', sysdate())";
                if ($conn->query($gimgqry) == TRUE) { $err="image added successfully";}
                }
            }
       
        $qry="insert into issueticket( `tikcketno`, `sub`, `severity`, `status`, `channel`, `issuedetails`,`issuetype`, `issuedate`, `organization`, `makeby`) 
        values('".$tcktid."','".$subject."','".$cmbsevere."',".$cmbst.",".$cmbchannel.",'".$issue."',".$cmbtype.",sysdate(),".$cmbprod.",".$hrid.")" ;
        //echo $qry;die;
        $err="A Ticket created successfully";
        
        
    }
    if ( isset( $_POST['update'] ) ) {
       $isid= $_REQUEST['issid'];
      $cmbtype = $_POST['cmbisstp'];         if($cmbtype == ''){$cmbtype = 'NULL';}
      $tcktid= $_REQUEST['tcktid']; 
       $subject = $_POST['subject'];         if($subject==''){$subject='NULL';}
      $cmbprod = $_POST['cmbprod'];         if($cmbprod==''){$cmbprod='NULL';}
      $cmbsevere = $_POST['cmbsevere'];     if($cmbsevere==''){$cmbsevere='NULL';}
      $cmbchannel = 8;                   //   if($cmbchannel==''){$cmbchannel='NULL';}
      $issue = $_POST['issue'];             if($issue==''){$issue='NULL';}
      $cmbst = 1;//$_POST['cmbst'];             if($cmbst==''){$cmbst='NULL';}
      $hrid = $_POST['usrid'];        
       
      
        $qry="update issueticket set `sub`='".$subject."',`issuetype` = ".$cmbtype.",`severity`='".$cmbsevere."',`status`=".$cmbst.",`channel`=".$cmbchannel.",`issuedetails`='".$issue."',`product`=".$cmbprod." where `id`=".$isid."";
        $err="Received Voucher updated successfully";
         
       //  $note="Customer Payment: Payment Amount ".$amt." has been updated ";
        //$qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        //values(".$cmbsupnm.",6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amt.",".$hrid.",sysdate())" ;
         //if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  }
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/issuecustomerList.php?res=1&msg=".$err."&mod=7");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/issuecustomerList.php?res=2&msg=".$err."&mod=7");
    }
    
    $conn->close();
}
?>