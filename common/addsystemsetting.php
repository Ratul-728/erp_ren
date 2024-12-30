<?php

session_start();
//ini_set('display_errors',1);

$imgpass = 1;

require "conn.php";
require "image_resize.php";
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/edit.php');

$imgbasepath = "../assets/images/site_setting_logo/";

$orgname = $_REQUEST["orgname"]; $orgname = addslashes($orgname);
$contact = $_REQUEST["contact"];
$mail = $_REQUEST["mail"];
$website = $_REQUEST["website"]; $website = addslashes($website);
$address = $_REQUEST["address"]; // $address = addslashes($address);

$hotline = $_REQUEST["hotline"];
$officehours = $_REQUEST["officehours"];

$theme = $_REQUEST["theme"];
$reverse = $_REQUEST["reverse"];








		//handle app logo;
	if($_FILES['logo']['name']){
		
		$targetPhotoName = "applogo";
		
		list($width, $height, $type, $attr) = getimagesize($_FILES['logo']['tmp_name']);
		
		//original;
		handleItemImageUpload($_FILES['logo'],$targetPhotoName,$imgbasepath,$width,$height);
		
		$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
		$imgfullname = $targetPhotoName.".".$ext;
			$whereqry3 = 'id=1';
			if(updateByID('sitesettings','logo','"'.$imgfullname.'"',$whereqry3)){$msg = "image data updated";}		
	}	

		//handle doc heading logo;
	if($_FILES['docheaderlogo']['name']){
		
		$targetPhotoName = "logo_letterhead";
		
		list($width, $height, $type, $attr) = getimagesize($_FILES['docheaderlogo']['tmp_name']);
		
		//original;
		handleItemImageUpload($_FILES['docheaderlogo'],$targetPhotoName,$imgbasepath,$width,$height);
		
		$ext = pathinfo($_FILES['docheaderlogo']['name'], PATHINFO_EXTENSION);
		$imgfullname = $targetPhotoName.".".$ext;
			$whereqry3 = 'id=1';
			if(updateByID('sitesettings','doc_header_logo','"'.$imgfullname.'"',$whereqry3)){$msg = "image data updated";}		
	}	


//handle removal

		if($_REQUEST['isremovepicture1'] && $_POST['old_picture']){
			 $oldFilePath = $rootpath."/assets/images/site_setting_logo/".$_POST['old_picture'];
			 if(!@unlink($oldFilePath)){
				 echo "error";die;
			 }else{
				 $whereqry3 = 'id=1';
				if(updateByID('sitesettings','logo','"default/renaissance_applogo.png"',$whereqry3)){$msg = "image data updated";}
			 }
			

			//echo $msg;die;
		}	

		if($_REQUEST['isremovepicture2'] && $_POST['old_picture_header']){
			 $oldFilePath = $rootpath."/assets/images/site_setting_logo/".$_POST['old_picture_header'];
			//echo $oldFilePath ;die;
			 if(!@unlink($oldFilePath)){
				 echo $oldFilePath." error";die;
			 }else{
				$whereqry3 = 'id=1';
				if(updateByID('sitesettings','doc_header_logo','"default/logo_letterhead.png"',$whereqry3)){$msg = "image data updated";}
				//echo $msg;die;				 
			 }
			

		}	


/* end photo upload */

if($imgpass !=0){
$qry = "UPDATE `sitesettings` SET `hotline`='".$hotline."',`officehours`='".$officehours."', `companynm`='".$orgname."',`contactno`='".$contact."',
`email`='".$mail."',`web`='".$website."',`address`='".$address."',`theme`='".$theme."',`reverse`='".$reverse."'
WHERE id = 1";


//echo $qry;die;
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
}
    
if ($conn->query($qry) == TRUE) {
    $err = "Successfully Update";
    $_SESSION["comname"] = $orgname;
    $_SESSION["comemail"] = $mail;
    $_SESSION["comcontact"] = $contact;
    $_SESSION["comaddress"] = $address;
    $_SESSION["comlogo"] = $photoname;
    $_SESSION["comweb"] = $website;
    $_SESSION["theme"] = $theme;
    $_SESSION["reverse"] = $reverse;
        
    header("Location: ".$hostpath."/systemsetting.php?pg=1&mod=5&msgcls=success&msg=$err");
} else {
    $err="Error: " . $qry . "<br>" . $conn->error;
    
    header("Location: ".$hostpath."/systemsetting.php?pg=1&mod=5&msgcls=danger&msg=$err");
}

	$conn->close();
}

?>