<?php
	session_start();
	include_once("../common/conn.php");
	include_once("../rak_framework/insert.php");


//print_r($_FILES);die;
$qaw_id = $_POST['reference'];
$type = $_POST['type'];

/* raihan note;
$response['code'] = 1; //successfully uploaded
$response['code'] = 2; //invalid file type
$response['code'] = 3; //upload error
$response['code'] = 4; //image uploaded but data not saved;
*/
// Specify the upload directory
$uploadDir = "../images/upload/qa_images/original/";
$thumbDir = "../images/upload/qa_images/thumb/";
// Specify the allowed file types
$allowedExtensions = ["jpg", "jpeg", "png", "gif"];

// Response array
$response = ['success' => false, 'message' => ''];

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //function createThumb();
    
    function createThumb($thWidth,$thHeight,$sourceFile,$thumbFile){
        
        $im = new imagick($sourceFile);
        $imageprops = $im->getImageGeometry();
        $width = $imageprops['width'];
        $height = $imageprops['height'];
        
		
        
        // for thumbs
        
        if($width > $height){
            $newWidth = $thWidth;
            $newHeight = ($newWidth / $height) * $width;
        }else{
            $newHeight = $thHeight;
            $newWidth = ($newHeight / $width) * $height;
        }
		
        //$im->resizeImage($newWidth,$newHeight, imagick::CATROM, 0, true);
        $im->cropThumbnailImage ($thWidth,$thHeight);
        $im->writeImage($thumbFile);
        unset($im);
        return true;
         
      // end thumbs
    }

    // Check if the file was uploaded without errors
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

        // Get the file information
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Check if the file extension is allowed
        if (in_array(strtolower($fileExtension), $allowedExtensions)) {

            // Generate a unique name for the file
            //$uniqueName = time() . '-' . $fileName;
            $uniqueName = time() . '_' . uniqid().'.'.$fileExtension;
            
            $destination = $uploadDir . $uniqueName;

            // Move the uploaded file to the destination
            if (move_uploaded_file($fileTmpName, $destination)) {
                $response['success'] = true;
                $response['message'] = 'File uploaded successfully!';
                $response['code'] = 1;
                
                //create thumb here;
                
                $sourceFile = $destination;
                $thumbFile = $thumbDir.$uniqueName;
                $thWidth = 300;
                $thHeight = 300;
                if(createThumb($thWidth,$thHeight,$sourceFile,$thumbFile)){
                    $response['imagepath'] = $uniqueName;
                }
                
                //insert in the database
                
                
                 $inputData = array(
                 'TableName' => 'qa_images',
                 'FetchByKey' => 'id',
                 'FetchByValue' =>  '',
                 
                 'qaw_id' => $qaw_id,
                 'image_url' => $uniqueName,
                 'type' => $type
                 
                  );        
                insertData($inputData,$msg,$success,$insertId);
                if($success == 1){
                    $response['dataid'] = $insertId;
                    
                }else{
                    $response['message'] = 'File uploaded but data not saved in the database!';
                    $response['code'] = 4;
                }
                

                
            } else {
                $response['message'] = 'Error moving the file to the destination.'.$destination;
                $response['code'] = 3;
            }
        } else {
            $response['message'] = 'Invalid file type. Allowed types are: ' . implode(', ', $allowedExtensions);
            $response['code'] = 2;
        }
    } else {
        $response['message'] = 'Error uploading file. Please try again.';
        $response['code'] = 3;
    }
} else {
    $response['message'] = 'Invalid request method.';
    $response['code'] = 3;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
//echo $insertId;
