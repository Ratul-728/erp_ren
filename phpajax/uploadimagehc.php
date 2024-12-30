<?php
	session_start();
	include_once("../common/conn.php");
	include_once("../rak_framework/edit.php");
	

if(isset($_FILES['file']['name'])){


   /* Getting file name */
   $filename = $_FILES['file']['name'];

   /* Location */
   //$location = "../post_uploaded_imgs/".$filename;
	
 	//$location = "../core/images/upload/postitem/".$filename;
 	//common/upload/hc/
    
    //$location = "../employee_picture/original/".$filename;
    $location = "../common/upload/hc/".$filename;
    $imgrootpath = '../common/upload/hc/';
 	
 	
 	list($width, $height) = getimagesize($_FILES['file']['tmp_name']);
 	
 	$min_size= 400;
 	
 


 	
 	if($height>=$min_size) {
        
        
        $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);
        
        $code=  date(dmYHis).str_replace(' ','',substr($nm,0,8));
        
        $photoname=$code.'.'.$imageFileType;

        $tmpFilePath = $_FILES['file']['tmp_name'];
        


   /* Valid extensions */
   $valid_extensions = array("jpg","jpeg","png");

   //$savelocation = "../employee_picture/original/".$photoname;
   $savelocation = "../common/upload/hc/".$photoname;

   $response = 0;
   
   /* Check file extension */
   if(in_array(strtolower($imageFileType), $valid_extensions)) {
	   
	   
      /* Upload file */
      if(move_uploaded_file($_FILES['file']['tmp_name'],$savelocation)){
        
        
                $im = new imagick('../common/upload/hc/'.$photoname);
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
				
                //$im->resizeImage($newWidth,$newHeight, imagick::CATROM, 0, true);
                $im->cropThumbnailImage (300,300);
                $im->writeImage('../common/upload/hc/'.$photoname);
                 unset($im);
                 
                 
              // end thumbs

              

                
                //end slider
                
				//echo $_REQUEST['oldpic'].'_'.$_REQUEST['empcode'];
				//die;
				
                $response = 'common/upload/hc/'.$photoname;
				//echo $_REQUEST['oldpic'];
				//die;
				$oldpic = $_REQUEST['oldpic'];
                if($oldpic){
					if(unlink('../common/upload/hc/'.$oldpic)){
							//echo '../common/upload/hc/'.$oldpic.' deleted';
							
						}else{
								//echo 'error delete';
							}
				}
                //update img path in db
				$condition = 'employeecode="'.$_REQUEST['empcode'].'"';
				if(updateByID('employee','photo','"'.$photoname.'"',$condition)){
					//echo 'Updated employee table';
				}else{
					//echo 'error occured';
					}
				
         
         
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