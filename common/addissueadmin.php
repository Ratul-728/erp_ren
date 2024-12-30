<?php
require "conn.php";

include_once('./email_config.php');
include_once('../email_messages/email_user_message.php');

require_once('phpmailer/PHPMailerAutoload.php');

session_start();



//print_r($_FILES);die;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/issueadmin.php.php?res=01&msg='New Entry'&id=''&mod=3");
}
else if( isset( $_POST['comment'] ) ) {
    
     $dt=date("Ymdhis"); 
    //echo $dt; die; 
       //$_REQUEST['tcktid']; 
      $isid= $_REQUEST['issid'];
      $tcktid= $_REQUEST['isstkt'];
      $issuecomment = $_POST['issuecomment'];         //if($issuecomment==''){$issuecomment='NULL';}
      $hrid = $_POST['usrid']; 
      
      $qry="insert into isssueactivity( `issueid`,`ticketid`, `activity`, `makeby`, `makedt`) 
        values(".$isid.",'".$tcktid."','".$issuecomment."',".$hrid.",sysdate())" ;
        $err="A Ticket created successfully";
        
        if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/issueadmin.php?res=4&msg='Update Data'&id=".$isid."&mod=6");
                
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/issueadmin.php?res=4&msg='Update Data'&id=".$isid."&mod=6");
    }
    
    $conn->close();
}
else
{
    if ( isset( $_POST['add'] ) ) {
        //print_r($_FILES);die;
     
      $dt=date("Ymdhis"); 
      $subject = $_POST['subject'];         //if($subject==''){$subject='NULL';}
      $cmborg = $_POST['cmborg'];           //if($cmborg==''){$cmborg='NULL';}
      $cmbprod = $_POST['cmbprod'];         //if($cmbprod==''){$cmbprod='NULL';}
      $cmbisstp = $_POST['cmbisstp'];       //if($cmbisstp==''){$cmbisstp='NULL';}
      $cmbisssbtp = $_POST['cmbisssbtp'];   //if($cmbisssbtp==''){$cmbisssbtp='NULL';}
      $cmbsevere = $_POST['cmbsevere'];     //if($cmbsevere==''){$cmbsevere='NULL';}
      $cmbassign = $_POST['cmbassign']; 
      if($cmbassign==''){
          $cmbn = $_REQUEST["cmbassign2"];
          if($cmbn != ''){
              $qryasi="SELECT id, firstname, lastname FROM `employee` WHERE concat_ws(' ',firstname,lastname) = '".$cmbn."'";
              //echo $qryasi;die;
              $resultasi = $conn->query($qryasi);
              while($rowasi = $resultasi->fetch_assoc()){
                  $cmbassign = $rowasi["id"];
                  //echo $cmbassign;
              }
          }else{
             $cmbassign='//';
          }
      }
      $cmbchannel = $_POST['cmbchannel'];   //if($cmbchannel==''){$cmbchannel='NULL';}
      $cmbreporter = $hrid; //if($cmbreporter==''){$cmbreporter='NULL';}
      $cmbram = $_POST['cmbram'];           //if($cmbram==''){$cmbram='NULL';}
      $issue = $_POST['issue'];             //if($issue==''){$issue='NULL';}
      $cmbst = $_POST['cmbst'];             //if($cmbst==''){$cmbst='NULL';}
      $probabledate = $_POST['probabledate'];//if($probabledate==''){$probabledate='NULL';}
      $hrid = $_POST['usrid'];  

      
      
      //Add Slashes function
      $subject = addslashes($subject);
      $issue = addslashes($issue);
       
      $tcktid=$dt;
      $chqclearst=0;$st=0; //$hrid= '1';
      
      if($cmbassign !=''){
          $qrymail = "SELECT emp1.id,concat(emp1.`firstname` , ' ', emp1.`lastname`) name, emp1.`office_email`, hr.hrName FROM `employee` emp1 LEFT JOIN `hr` hr ON hr.id = ".$hrid." where emp1.id = ".$cmbassign;
          $resultmail = $conn->query($qrymail);
          //echo $qrymail;die;
          while($rowmail = $resultmail->fetch_assoc()){
              $name_to = $rowmail["name"];
              $email_to = $rowmail["office_email"];
              $hrname = $rowmail["hrName"];
          }
          
        $mailsubject = "Bitflow Task #$tcktid: $subject";

        $message = "Dear $name_to,<br>
                $hrname has assigned a task for you with bellow details. <br>
                
                <br>Title: $subject. <br>
                Description: $issue. <br><br>
                Kindly review it from your profile.<br>
                
                <br>Thanks,<br>
                Bitflow System<br>
                ";
                
    	
    	sendBitFlowMail($name_to,$email_to, $mailsubject,$message);
      }
       
        $qry="insert into issueticket(`tikcketno`, `sub`, `organization`, `issuetype`, `issuesubtype`, `severity`, `assigned`, `status`, `reporter`, `channel`, `issuedetails`, `issuedate`, `probabledate`, `product`, `accountmanager`, `makeby`) 
        values('".$tcktid."','".$subject."','".$cmborg."','".$cmbisstp."','".$cmbisssbtp."','".$cmbsevere."','".$cmbassign."','".$cmbst."','".$cmbreporter."','".$cmbchannel."','".$issue."',sysdate(),STR_TO_DATE('".$probabledate."', '%d/%m/%Y'),'".$cmbprod."','".$cmbram."',".$hrid.")" ;
        $err="A Ticket created successfully";
        $code=date(dmYHis);
        foreach($_FILES['attachment2']['name'] as $key=>$val)
            { 
            // File upload path 
             $filename1 = $_FILES['attachment2']['tmp_name'][$key];
             $photogalary=$code.$key.".jpg";
             //array_push($images, $photogalary);
             if($filename1!='')
                {
                   
                    $info1 = getimagesize($filename1);
                    $imageWidth = $info1[0];
                    $imageHeight = $info1[1];
                    //print_r($_FILES);die;
                   //echo  $imageWidth;die;
                   //echo $info1['mime'];die;
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
                }
                
                $qryph ="INSERT INTO `issuephoto`(`issueticket`, `photo`, `makedt`, `st`) VALUES ('".$tcktid."','".$photogalary."',sysdate(),1)";
                $conn->query($qryph);
            }
            
        foreach($_FILES['attachment1']['name'] as $key=>$val)
            { 
            // File upload path 
             $filename1 = $_FILES['attachment1']['tmp_name'][$key];
             $photogalary=$code.$key.".jpg";
             //array_push($images, $photogalary);
             if($filename1!='')
                {
                   
                    $info1 = getimagesize($filename1);
                    $imageWidth = $info1[0];
                    $imageHeight = $info1[1];
                    //print_r($_FILES);die;
                   //echo  $imageWidth;die;
                   //echo $info1['mime'];die;
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
                }
                
                $qryph ="INSERT INTO `issuephoto`(`issueticket`, `photo`, `makedt`, `st`) VALUES ('".$tcktid."','".$photogalary."',sysdate(),2)";
                $conn->query($qryph);
            }
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
       $isid= $_REQUEST['issid'];
      
      $tcktid= $_REQUEST['tcktid']; 
      $subject = $_POST['subject'];         //if($subject==''){$subject='NULL';}
      $cmborg = $_POST['cmborg'];           //if($cmborg==''){$cmborg='NULL';}
      $cmbprod = $_POST['cmbprod'];         //if($cmbprod==''){$cmbprod='NULL';}
      $cmbisstp = $_POST['cmbisstp'];       //if($cmbisstp==''){$cmbisstp='NULL';}
      $cmbisssbtp = $_POST['cmbisssbtp'];   //if($cmbisssbtp==''){$cmbisssbtp='NULL';}
      $cmbsevere = $_POST['cmbsevere'];     //if($cmbsevere==''){$cmbsevere='NULL';}
      $cmbassign = $_POST['cmbassign'];     
      
      if($cmbassign==''){
          $cmbn = $_REQUEST["cmbassign2"];
          if($cmbn != ''){
              $qryasi="SELECT id, firstname, lastname FROM `employee` WHERE concat_ws(' ',firstname,lastname) = '".$cmbn."'";
              //echo $qryasi;die;
              $resultasi = $conn->query($qryasi);
              while($rowasi = $resultasi->fetch_assoc()){
                  $cmbassign = $rowasi["id"];
                  echo $cmbassign;
              }
          }else{
             $cmbassign='//';
          }
      }
      $cmbchannel = $_POST['cmbchannel'];   //if($cmbchannel==''){$cmbchannel='NULL';}
      $cmbreporter = $hrid; //if($cmbreporter==''){$cmbreporter='NULL';}
      $cmbram = $_POST['cmbram'];           //if($cmbram==''){$cmbram='NULL';}
      $issue = $_POST['issue'];             //if($issue==''){$issue='NULL';}
      $cmbst = $_POST['cmbst'];             //if($cmbst==''){$cmbst='NULL';}
      $probabledate = $_POST['probabledate'];//if($probabledate==''){$probabledate='NULL';}
      $hrid = $_POST['usrid'];  
      
      $subject = addslashes($subject);
      $issue = addslashes($issue);
       
      
        $qry="update issueticket set `sub`='".$subject."',`organization`='".$cmborg."',`issuetype`='".$cmbisstp."',`issuesubtype`='".$cmbisssbtp."',`severity`='".$cmbsevere."',`assigned`='".$cmbassign."',`status`='".$cmbst."',`reporter`='".$cmbreporter."' ,`channel`='".$cmbchannel."',`issuedetails`='".$issue."',`probabledate`='".$probabledate."' ,`product`='".$cmbprod."',`accountmanager`='".$cmbram."' where `id`=".$isid."";
        $err="Received Voucher updated successfully";
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
                header("Location: ".$hostpath."/issueadminList.php?res=1&msg=".$err."&mod=6");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/issueadminList.php?res=2&msg=".$err."&mod=6");
    }
    
    $conn->close();
}
?>