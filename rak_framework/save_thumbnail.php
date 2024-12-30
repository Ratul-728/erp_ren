<?php
/*

Smart Image Thumbnail Creator
By AJ Quick
AJ@AJQuick.com

Version 1.2W

There will be some minor alterations that you will need to do with this to make it work to your liking. This is ment to be used in addition to some type of image upload script. You will need to do some changes to the variable to make it stick, and make thumbnails to your liking. This is not exact and it will not work every time, but it is very successful. It will resize your image, and crop it at the same time so you don't have stretching, or black areas in the thumbnail. Included in this is the function of good resizing, by John Jensen.

*/

//VARIABLES

$nw=110; //The Width Of The Thumbnails
$nh=100; //The Height Of The Thumbnails

$ipath = "200158750-001.jpg"; //Path To Place Where Images Are Uploaded. No Trailing Slash
$tpath = "200158750-001_.jpg";//Path To Place Where Thumbnails Are Uploaded. No Trailing Slash

/*
You will need to go down in the code below, and change the image names. Currently it is set as "$img".
The outputted thumbnail's name is: "$thumb".
*/

function saveThumbnail($source, $destination, $nw, $nh)
{
	$img = $source;
	
	
	$dimensions = GetImageSize($img);
	
	$thname = $destination;
	//$thname = "$tpath/$img_name";
	
	$w=$dimensions[0];
	$h=$dimensions[1];
	
	$img2 = ImageCreateFromJpeg($img);
	$thumb=ImageCreateTrueColor($nw,$nh);
		
	$wm = $w/$nw;
	$hm = $h/$nh;
		
	$h_height = $nh/2;
	$w_height = $nw/2;
		
	if($w > $h){
		
		$adjusted_width = $w / $hm;
		$half_width = $adjusted_width / 2;
		$int_width = $half_width - $w_height;
		
		ImageCopyResampled($thumb,$img2,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h); 
		
		ImageJPEG($thumb,$thname,95); 
		
	}elseif(($w < $h) || ($w == $h)){
		
		$adjusted_height = $h / $wm;
		$half_height = $adjusted_height / 2;
		$int_height = $half_height - $h_height;
		
		ImageCopyResampled($thumb,$img2,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h); 
	
		ImageJPEG($thumb,$thname,95); 
		
		
	}else{
		ImageCopyResampled($thumb,$img2,0,0,0,0,$nw,$nh,$w,$h);
		ImageJPEG($thumb,$thname,95); 
	}
	
	imagedestroy($img2);
}
?>	