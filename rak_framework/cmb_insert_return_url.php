<?php
	if($success == 1 && $return != "")
	{
			
		
		$url = explode("||",$return);
		
		
		$url = $url[0].'_manager.php?click_action='.$url[1].'&postaction='.$url[2].'&id='.$url[3].'&'.$module.'='.$insertId;
		header('location:'.$url);
	}	
?>