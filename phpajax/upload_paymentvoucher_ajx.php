<?php
	session_start();
	include_once("../common/conn.php");
	//include_once("../rak_framework/edit.php");
	


if(isset($_FILES['file']['name'])){


   /* Getting file name */
   $filename = $_FILES['file']['name'];

   /* Location */
    $location = "../common/upload/hc/".$filename;
    $imgrootpath = '../common/upload/hc/';
 	
 	
 	list($width, $height) = getimagesize($_FILES['file']['tmp_name']);
 	
 	$min_size= 200;
 	
 


 	
 	//if($height>=$min_size) {
        
        
        $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);
        
		$uniqid = uniqid();
		
		
		//$code=  date(dmYHis).str_replace(' ','',substr($nm,0,8));
        $code=  $uniqid.'_'.str_replace(' ','',substr($filename,0,8));
        
        $photoname=$code.'.'.$imageFileType;
		$photoname_mod=$code.'_m.'.$imageFileType;

        $tmpFilePath = $_FILES['file']['tmp_name'];
        
		//echo $photoname;
		//die;


   /* Valid extensions */
   $valid_extensions = array("jpg","jpeg","png","webp","gif");

   //$savelocation = "../employee_picture/original/".$photoname;
   $savelocation = "../common/upload/hc/".$photoname;

   $response = 0;
   
   /* Check file extension */
   if(in_array(strtolower($imageFileType), $valid_extensions)) {
	   
	   
      /* Upload file */
      if(move_uploaded_file($_FILES['file']['tmp_name'],$savelocation)){
        
        
                $im = new imagick($savelocation);
                $imageprops = $im->getImageGeometry();
                $width = $imageprops['width'];
                $height = $imageprops['height'];
                
				
                
                // for thumbs
                
                if($width > $height){
                    $newWidth = 1000;
                    $newHeight = ($newWidth / $height) * $width;
                }else{
                    $newHeight = 1000;
                    $newWidth = ($newHeight / $width) * $height;
                }
				
                $im->resizeImage($newWidth,$newHeight, imagick::FILTER_CATROM, 0, true);
		  		
                //$im->cropThumbnailImage (300,300);
                $im->writeImage('../common/upload/hc/'.$photoname_mod);
                 unset($im);
                 

				
                $response = 'common/upload/hc/'.$photoname_mod;
         
      }
   }else{
	   $response = 2;	
 //  }
 	    
 	//}else {	//if($height>=$min_size) {
   // $response = 2;	

   
}
echo $response;
   exit;
}

echo 0;

?>