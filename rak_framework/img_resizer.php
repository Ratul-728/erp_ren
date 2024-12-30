<?php
$inputFilename = $_REQUEST['inputFilename'];
$new_side = $_REQUEST['new_side'];

//echo $_REQUEST['inputFilename'];
//function resize_jpg($inputFilename, $new_side){

	header ("Content-type: image/png");

	$imagedata = getimagesize($inputFilename);
	$w = $imagedata[0];
	$h = $imagedata[1];
	
	if ($h > $w) {
		$new_w = ($new_side / $h) * $w;
		$new_h = $new_side;	
	} else {
		$new_h = ($new_side / $w) * $h;
		$new_w = $new_side;
	}
	
	$im2 = ImageCreateTrueColor($new_w, $new_h);
	$image = ImageCreateFromJpeg($inputFilename);
	imagecopyResampled ($im2, $image, 0, 0, 0, 0, $new_w, $new_h, $imagedata[0], $imagedata[1]);
	//return $im2;
	
	imagepng ($im2); 
	imagedestroy ($im2);

//}
//echo resize_jpg($inputFilename, $new_side);
?>