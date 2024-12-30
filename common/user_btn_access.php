<?php
/*
ini_set('display_errors',0);
require_once("conn.php");
require "../rak_framework/connection.php";
require_once ("../rak_framework/fetch.php");
session_start();
*/
function checkBtnAccess($key){
   
	$targetUser = $_SESSION['user']; //id
	$currSectionID = $_SESSION['currSectionID'];


	$arrPrivQuery = array('hrid' => $_SESSION['user'],'menuid' => $currSectionID);
	$val = fetchSingleDataByArray('hrAuth',$arrPrivQuery,'`'.$key.'`');
    
	if($val == 1){
		//echo 'show btn';
		return true;


	}else{
	   // echo 'no btn';
		return false;
	}

}



function getGridBtns($btnAarray){
	$btnCnt = count($btnAarray);

	$flg = 1;
	foreach($btnAarray as $btn){
		$separator = ($flg < $btnCnt)?' | ':'';
		//echo $btn[2]."<br>";
		$btnType = $btn[0]; $btnLink = $btn[1]; $btnAttr = $btn[2]; 
		
		switch($btnType){
				
			// VIEW BUTTON
			case 'view':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-eye"></i></a>'.$separator;
			}else{
                $btnAttr = str_replace("show-invoice","",$btnAttr);
				$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-eye"></i></a>'.$separator;
			}
			break;
			
				
			// EDIT BUTTON
			case 'edit':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-edit"></i></a>'.$separator;
			}else{
				$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-edit"></i></a>'.$separator;
			}
			break;
				
			// DELETE BUTTON
			case 'delete':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-remove"></i></a>'.$separator;
			}else{
				$btnAttr = str_replace("griddelbtn","",$btnAttr);
				$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-remove"></i></a>'.$separator;
			}
			break;
				
			// GENERATE BUTTON
			case 'generate':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-truck"></i></a>'.$separator;
			}else{
				$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-truck"></i></a>'.$separator;
			}
			break;
			
			
			// ACCEPT BUTTON
			case 'accept':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-check"></i></a>'.$separator;
			}else{
				$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-check"></i></a>'.$separator;
			}
			break;
				
			// STOCK TRANSFER BUTTON
			case 'stock-transfer':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-check"></i></a>'.$separator;
			}else{
				$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-check"></i></a>'.$separator;
			}
			break;
			
			// DOWNLOAD BUTTON
			case 'download':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-download"></i></a>'.$separator;
			}else{
				$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-download"></i></a>'.$separator;
			}
			break;
			
			// PAYMENT BUTTON
			case 'payment':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-dollar"></i></a>'.$separator;
			}else{
                $btnAttr = str_replace("mkpayment","",$btnAttr);
				$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-dollar"></i></a>'.$separator;
			}
			break;
			
			// ACCEPT BUTTON
			case 'block':
				if(checkBtnAccess($btnType)){
					if(strstr($btnAttr,'btnicon')){

						preg_match('/{{([^}]*)}}/', $btnAttr, $matches);

						$lockValue = isset($matches[1]) ? $matches[1] : null;

							$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-'.$lockValue.'"></i></a>'.$separator;
					}else{
						$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-ban"></i></a>'.$separator;
					}
				}else{
					$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-ban"></i></a>'.$separator;
				}
				break;
				
				
				
		}
		
		$flg++;
		
	}
	
	return $btnStr;
}

//test call;
$btns = array(
	//array('delete','quotation_view.php','attrs'),
	array('view','quotation_view.php','class="show-invoice btn btn-info btn-xs"  title="View Quotation"	data-socode="'.$row['socode'].'"  '),
	array('edit','quotationEntry.php?res=4&msg=Update Data&id='.$row['id'].'&mod=2','class="show-invoice btn btn-info btn-xs"  title="Edit"	  '),
	array('delete','common/delobj.php?obj=quotation&ret=quotationList&mod=2&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
);

function getBtn($btnType){
	//echo $btnType;die;
	$btnStr = '';
	switch($btnType){
				
			// ADD BUTTON
			case 'create':
			if(checkBtnAccess($btnType)){
                $btnStr .= '<button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>';
			}else{
				$btnStr .= '<button type="button" disabled   class="form-control btn btn-default"><i class="fa fa-plus"></i></button>';
			}
			break;
			
				
			// EDIT BUTTON
			case 'edit':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-edit"></i></a>'.$separator;
			}else{
				$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-edit"></i></a>'.$separator;
			}
			break;
				
			// DELETE BUTTON
			case 'delete':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<a href="'.$btnLink.'" '.$btnAttr.'><i class="fa fa-remove"></i></a>'.$separator;
			}else{
				$btnAttr = str_replace("griddelbtn","",$btnAttr);
				$btnStr .= '<a disabled '.$btnAttr.'><i class="fa fa-remove"></i></a>'.$separator;
			}
			break;
			
			//EXPORT BUTTON
			case 'export':
			if(checkBtnAccess($btnType)){
				$btnStr .= '<button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>';
			}else{
				$btnStr .= '<button type="button" disabled class="form-control btn btn-default"><i class="fa fa-download"></i></button>';
			}
			break;            
            
				
	
		}	
	   return $btnStr;
}

//getGridBtns($btns);


//			$invViewLink = '<a data-socode="'.$row['socode'].'" href="quotation_view.php" class="show-invoice btn btn-info btn-xs" title="View Quotation" target="_blank"><i class="fa fa-eye"></i></a>';			
//$key =  $_REQUEST['key'];
//checkBtnAccess($key);

?>

