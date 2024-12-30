<?php

// call example: handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."300_300/",300,300);

function handleItemImageUpload($file, $targetPhotoName, $destination, $w, $h){
    //file_put_contents('fileupload.txt', print_r($file, true));
    $file_type = $file['type']; // returns the mimetype

    $allowed = array("image/jpeg", "image/gif", "image/png");
    if(!in_array($file_type, $allowed)) {
        $error_message = 'Only jpg, gif and png files are allowed.';
        header("location:".$_SERVER['HTTP_REFERER']."&err=".$error_message);
        die;
    } else {
        // go ahead
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        $tmpFilePath =  $file['tmp_name'];
        $imageName = $targetPhotoName . "." . $ext;
        $newWidth = $w;
        $newHeight = $h;
        $moveToDir = $destination;

        $result = createThumbnail($imageName, $newWidth, $newHeight, $tmpFilePath, $moveToDir);

        if($result){
            return true;
        } else {
            return false;
        }
    }
}

function createThumbnail($imageName, $newWidth, $newHeight, $uploadDir, $moveToDir) {
    $path = $uploadDir;

    $mime = getimagesize($path);

    // Create image resource based on mime type
    switch ($mime['mime']) {
        case 'image/png':
            $src_img = imagecreatefrompng($path);
            break;
        case 'image/jpeg':
        case 'image/jpg':
        case 'image/pjpeg':
            $src_img = imagecreatefromjpeg($path);
            break;
        case 'image/webp':
            $src_img = imagecreatefromwebp($path);
            $new_thumb_loc = $moveToDir . $imageName;
            if (imagewebp($src_img, $new_thumb_loc, 80)) {
                echo "WebP image saved successfully!";
            } else {
                die("Failed to save WebP image");
            }
            break;
        default:
            return false; // Unsupported image type
    }

    $old_x = imagesx($src_img);
    $old_y = imagesy($src_img);

    if($old_x > $old_y) {
        $thumb_w = $newWidth;
        $thumb_h = $old_y / $old_x * $newWidth;
    } elseif($old_x < $old_y) {
        $thumb_w = $old_x / $old_y * $newHeight;
        $thumb_h = $newHeight;
    } else {
        $thumb_w = $newWidth;
        $thumb_h = $newHeight;
    }

    $dst_img = imagecreatetruecolor($thumb_w, $thumb_h);

    // Preserve transparency for PNG
    if ($mime['mime'] == 'image/png' || $mime['mime'] == 'image/webp') {
        imagealphablending($dst_img, false);
        imagesavealpha($dst_img, true);
    }

    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);

    // New save location
    $new_thumb_loc = $moveToDir . $imageName;

    // Save image based on mime type
    switch ($mime['mime']) {
        case 'image/png':
            $result = imagepng($dst_img, $new_thumb_loc, 1); // Compression 0 (no compression) to 9 (maximum compression)
            break;
        case 'image/jpeg':
        case 'image/jpg':
        case 'image/pjpeg':
            $result = imagejpeg($dst_img, $new_thumb_loc, 80); // Quality 0 (worst) to 100 (best)
            break;
        case 'image/webp':
            $result = imagewebp($dst_img, $new_thumb_loc, 80); // Quality 0 (worst) to 100 (best)
            break;
        default:
            $result = false;
    }

    imagedestroy($dst_img);
    imagedestroy($src_img);

    return $result;
}

/* end new code */
	
	
	

function handleItemImageUpload_old($file,$targetPhotoName,$destination,$w,$h){
		//$objImg
		
		$file_type = $file['type']; //returns the mimetype
		//echo $file_type;die;
		
		$allowed = array("image/jpeg", "image/gif", "image/png","image/webp");
		if(!in_array($file_type, $allowed)) {
		 $error_message = 'Only jpg, gif, png and webp files are allowed.';
		 header("location:".$_SERVER['HTTP_REFERER']."&err=".$error_message);
			die;
		 $error = 'yes';
			//echo $error_message;
		}else{
			//go ahead;
			
				$ext = pathinfo($file["name"], PATHINFO_EXTENSION);
				$tmpFilePath =  $file['tmp_name'];
				$imageName = $targetPhotoName.".".$ext;
				$newWidth = $w;
				$newHeight = $h;
				$moveToDir = $destination;
				//echo $moveToDir;
				$result = createThumbnail($imageName,$newWidth,$newHeight,$tmpFilePath,$moveToDir); //only works with $_FILES['attachment1']['tmp_name']; not with already uploaded  image file;
				
				if($result){ //only works with $_FILES['attachment1']['tmp_name']; not with already uploaded  image file;	
					//echo "success!";
					return true;
				}else{
					//echo "fail!";
					return false;
				}
				//die;
			//echo "go ahead";
			
		}
		
		//die;
		
	}

function createThumbnail_old($imageName,$newWidth,$newHeight,$uploadDir,$moveToDir)
{
    //$path = $uploadDir . '/' . $imageName;
	//$_FILES['attachment1']['tmp_name'];
	$path = $uploadDir;

    $mime = getimagesize($path);

    if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
    if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
    if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
    if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }
	if($mime['mime']=='image/webp'){ $src_img = imagecreatefromjpeg($path); }

    $old_x = imageSX($src_img);
    $old_y = imageSY($src_img);

    if($old_x > $old_y)
    {
        $thumb_w    =   $newWidth;
        $thumb_h    =   $old_y/$old_x*$newWidth;
    }

    if($old_x < $old_y)
    {
        $thumb_w    =   $old_x/$old_y*$newHeight;
        $thumb_h    =   $newHeight;
    }

    if($old_x == $old_y)
    {
        $thumb_w    =   $newWidth;
        $thumb_h    =   $newHeight;
    }

    $dst_img        =   imagecreatetruecolor($thumb_w,$thumb_h);
	
    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	
	if($mime['mime']=='image/png'){
		
		$dimg = $dst_img;
		$background = imagecolorallocate($dimg , 255, 255, 255);
		imagecolortransparent($dimg, $background);
		imagealphablending($dimg, false);
		imagesavealpha($dimg, true);
		$dst_img = $dimg;
	}
	
    // New save location
    $new_thumb_loc = $moveToDir . $imageName;

    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,1); }
    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
	if($mime['mime']=='image/webp'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

    imagedestroy($dst_img);
    imagedestroy($src_img);
    return $result;
}



?>