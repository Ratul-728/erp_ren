<?php
session_start();
require "../common/conn.php";
include_once("../rak_framework/delete.php");



if (!$_SESSION["user"]) 
{
        header("Location: " . $hostpath . "/hr.php");
}else{
    
    
    
    if($_POST['action'] == 'deletepic' && isset($_POST['target']))
    {
        
   
        $orImg      = "../images/upload/qa_images/original/".trim($_POST['target']);
        $thumbImg   = "../images/upload/qa_images/thumb/".trim($_POST['target']);    
        
        
        
        if(@unlink($orImg)){
            $response['message_orimg'] = 'Original picture deleted successfully';
            $response['code'] = 1;
        }else{
            $response['message_orimg'] = 'Error deleting original picture! '.$orImg;
            $response['code'] = 2;
        }
        
        if(@unlink($thumbImg)){
            $response['message_thumb'] = 'Thumbnail picture deleted successfully';
            $response['code'] = 1;
        }else{
            $response['message_thumb'] = 'Error deleting thumb picture! '.$thumbImg;
            $response['code'] = 2;
        }
        
        if($_POST['dataid']){
            
            deleteRow('qa_images','image_url','"'.trim($_POST['target']).'"',$msg, $success);
            
            if($success == 1){
                $response['message_record'] = 'Images successfully deleted!!';
                $response['code'] = 1;
                
            }else{
                $response['message_record'] = 'Error deleting image record from database!';
                $response['code'] = 2;
            }
        }
        

          // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    //print_r($_POST);

    }
    
    
  
    

}
?>