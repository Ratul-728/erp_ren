<?php

	//rak upload 
	function uploadFile($filearray,$file_name,$width,$height,&$msg){
		//print_r($filearray);
		//echo '<hr>';
		if(move_uploaded_file($filearray['tmp_name'], $file_name)) 
		{
			$msg .= '<span style="color:green"> '.$filearray['name']." uploaded successfully </span><br />";
			$err = 'true';
			$inputData['file'] = $file_name;
			$file = $file_name;
			
			//include resizer file
			include_once('save_thumbnail.php');
			
			$imgDimension = GetImageSize($file_name);
			
			if(intval($imgDimension[1]) > $height){
				saveThumbnail($file, $file, $width, $height);
			}
			
			return true;
		}
		else
		{
			$err = 'false';
			$msg .= '<span style="color:red">Error uploading '.$filearray['name']." </span><br />";
			return false;
		}		
			
	}

?>