<?php
require "conn.php";
require "image_resize.php";
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/edit.php');

//echo "<pre>";print_r($_REQUEST);echo "</pre>";die;
//ini_set('display_errors', 1);	
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/brandList.php?mod=1");
}
else
{
	
$imgbasepath = "../assets/images/brands/";

	
	
	
	//Insert
    if ( isset( $_POST['add'] ) ) {
     
      $brand= $_REQUEST['brand']; 
      $origin= $_REQUEST['origin'];
      $hrid = $_POST['usrid']; 
		

		
		$code = getFormatedUniqueID('brand','id','BR',6,"0");
		
		
	//handle upload entry edit both;
	if($_FILES['attachment1']['name']){
		
		
		$targetPhotoName = $code;
		
		
		//thumb 
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."300_300/",300,300);
		//full
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."800_800/",800,800);
		
		list($width, $height, $type, $attr) = getimagesize($_FILES['attachment1']['tmp_name']);
		//original;
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."original/",$width,$height);
		
		$ext = pathinfo($_FILES['attachment1']['name'], PATHINFO_EXTENSION);
		$imgfullname = $code.".".$ext;
	}
				
		
		
		
		
		
        //if ($tmpFilePath != ""){ $newFilePath = "../../assets/images/brand_logos/".$img;
          //       $didUpload = move_uploaded_file($tmpFilePath, $newFilePath); }
    //echo $didUpload;die;
    $qry = "INSERT INTO `brand`(`title`,`code`, `origin`, `image`, `activest`, `displaypos`, `makeby`, `makedt`) 
                        VALUES ('".$brand."','".$code."','".$origin."','".$imgfullname."',1,1,'".$hrid."',sysdate())";
    //echo $qry;die;
    }
	
	
	
	
	
	
	
	
	//Update
	
    if ( isset( $_POST['update'] ) ) {
		
		
		
		$make_date=date('Y-m-d H:i:s');
        $aid= $_REQUEST['atid'];
        $brand= $_REQUEST['brand']; 
        $origin= $_REQUEST['origin'];
        $hrid = $_POST['usrid']; 
		$code = fetchByID('brand','id',$aid,'code');

		if($_REQUEST['isremovepicture'] && $_POST['oldpic']){
			 $oldFilePath300 = $imgbasepath.'300_300/'.$_POST['oldpic'];
			 $oldFilePath800 = $imgbasepath.'800_800/'.$_POST['oldpic'];
			 $oldFilePathOrg = $imgbasepath.'original/'.$_POST['oldpic'];
			 @unlink($oldFilePath300);
			 @unlink($oldFilePath800);
			 @unlink($oldFilePathOrg);
			
			$whereqry3 = 'id='.$aid;
			if(updateByID('brand','image','""',$whereqry3)){$msg = "image data updated";}
			
			//echo $msg;die;
			
		}	
		
		
		
		
		//handle upload entry edit both;
	if($_FILES['attachment1']['name']){
		
		
		
		$targetPhotoName = $code;
		
		
		//thumb 
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."300_300/",300,300);
		//full
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."800_800/",800,800);
		
		list($width, $height, $type, $attr) = getimagesize($_FILES['attachment1']['tmp_name']);
		//original;
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."original/",$width,$height);
		
		$ext = pathinfo($_FILES['attachment1']['name'], PATHINFO_EXTENSION);
		$imgfullname = $code.".".$ext;
		
			$whereqry3 = 'id='.$aid;
			if(updateByID('brand','image','"'.$imgfullname.'"',$whereqry3)){$msg = "image data updated";}		
	}	
		
		
		
		
        //if ($tmpFilePath != ""){ $newFilePath = "../../assets/images/brand_logos/".$img;
        //echo $newFilePath;die;
        // $didUpload = move_uploaded_file($tmpFilePath, $newFilePath); }
        //echo $newFilePath;die;
        //if($didUpload==1){$code=$img;}else{$code='0';}
        $qry = "UPDATE `brand` SET `title`='".$brand."',`origin`='".$origin."',`makedt`='".$make_date."'  WHERE id = ".$aid;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
	//echo $code;die;
	
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/brandList.php?mod=12&changedid=".$code);
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/brandList.php?&mod=12");
    }
    
    $conn->close();
}
?>