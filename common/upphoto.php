<?php
require "conn.php";
session_start();

$id = $_REQUEST["ppid"];
$photogalary = $_REQUEST["ppcode"];

$photogalary .= ".jpg";

$filename1 = $_FILES['avatar']['tmp_name'];

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
            imagejpeg($resized, "./upload/hc/".$photogalary);
            break;
        case 'image/jpeg':
            $original = imagecreatefromjpeg($filename1);
            $resized = imagecreatetruecolor(800, 600);
            imagecopyresampled($resized, $original, 0, 0, 0, 0, 800, 600, $imageWidth, $imageHeight);
            imagejpeg($resized, "./upload/hc/".$photogalary);
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
            imagejpeg($resized, "./upload/hc/".$photogalary);
            break;
    }
    
}

 header("Location: ".$hostpath."/employee_hr.php?res=4&msg=Update Data8&mod=4&id=".$id);

?>