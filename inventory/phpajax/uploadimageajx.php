<?php



if(isset($_FILES['file']['name'])){

	
	

	
   /* Getting file name */
   $filename = $_FILES['file']['name'];

   /* Location */
   //$location = "../post_uploaded_imgs/".$filename;
	
 	//$location = "../core/images/upload/postitem/".$filename;
    
    $location = "../post_picture/original/".$filename;
    $imgrootpath = '../post_picture/';
 	
 	
 	list($width, $height) = getimagesize($_FILES['file']['tmp_name']);
 	
 	$min_size= 500;
 	
 

 	
 	
 	if($height>=$min_size) {
        
        
        $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);
        
        $code=  date(dmYHis).str_replace(' ','',substr($nm,0,8));
        
        $photoname=$code.'.'.$imageFileType;

        $tmpFilePath = $_FILES['file']['tmp_name'];
        

        
        
        
        
 
 
   
    

   /* Valid extensions */
   $valid_extensions = array("jpg","jpeg","png");

   $savelocation = "../post_picture/original/".$photoname;
	
   $response = 0;
   /* Check file extension */
   if(in_array(strtolower($imageFileType), $valid_extensions)) {
      /* Upload file */
      if(move_uploaded_file($_FILES['file']['tmp_name'],$savelocation)){
        
        
                $im = new imagick('../post_picture/original/'.$photoname);
                $imageprops = $im->getImageGeometry();
                $width = $imageprops['width'];
                $height = $imageprops['height'];
                
                
                // for thumbs
                
                if($width > $height){
                    $newWidth = 300;
                    $newHeight = ($newWidth / $height) * $width;
                }else{
                    $newHeight = 300;
                    $newWidth = ($newHeight / $width) * $height;
                }
                $im->resizeImage($newWidth,$newHeight, imagick::FILTER_CATROM, 0, true);
                $im->cropThumbnailImage (300,300);
                $im->writeImage('../post_picture/thumbs/'.$photoname);
                 unset($im);
                 
                 
              // end thumbs
              
              //for slider
              
                $im2 = new imagick('../post_picture/original/'.$photoname);
                $imageprops2 = $im2->getImageGeometry();
                $width2 = $imageprops2['width'];
                $height2 = $imageprops2['height'];             
              
                if($width2 > $height2){
                    $newWidth2 = 1085;
                    $newHeight2 = ($newWidth2 / $height2) * $width2;
                }else{
                    $newHeight2 = 420;
                    $newWidth2 = ($newHeight2 / $width2) * $height2;
                }
                
                //$im2->scaleImage($newWidth, $newHeight, true);
               
                $im2->resizeImage($newWidth2,$newHeight2, imagick::FILTER_CATROM  , 0, true);
                //$im2->resizeImage($newWidth2,$newHeight2,   ,true, true);
                //$im2->cropThumbnailImage(1085,420);
                $im2->writeImage('../post_picture/slider/'.$photoname);
                
                unset($im2);

                
                //end slider
                
                $response = '../post_picture/thumbs/'.$photoname;
         
         
      }
   }
 	    
 	}else {

    $response = 2;	

   
}
echo $response;
   exit;
}

echo 0;

?>