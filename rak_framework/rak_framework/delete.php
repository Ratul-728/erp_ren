<?php
/**
 * ----------------------------------------------
 * DDH Deleting Functionalities.
 * Version 0.1
 * Developer: Md. Abul Kashem (Raihan)
 * Email: raihanak@yahoo.com
 * ----------------------------------------------
 */
function deleteRow($TableName,$RowIDName,$RowIDValue,&$msg,&$retVal)
{
	if($RowIDValue){
			global $dbname, $link;
			 
			 
			 
			 
			 $str	= 'DELETE   FROM `'.$TableName.'` WHERE `'.$RowIDName.'` = '.$RowIDValue.'';
			
			// echo $str;
			
			 $result = mysqli_query($link,  $str)
			 or die(mysqli_error($link));

			 
		if($result)
		{
		$msg = $str. ' <span class="msg_success">1 row deleted successfully!</span>';
		$retVal = 1;
		}
		else
		{
		$msg = '<span class="msg_err">Record could not be deleted!</div>';
		$retVal = 0;
		}
	}
}

function deleteRowByCondition($TableName,$condition,&$msg,&$retVal)
{
	if($condition){
			global $dbname, $link,$link;
			 
			 
			 
			 
			 $str	= 'DELETE  FROM '.$TableName.' WHERE '.$condition;
			
			 //echo $str.'<br>';
			
			 $result = mysqli_query($link,  $str)
			 or die(mysqli_error($link));

			 
		if($result)
		{
		$msg = '<span class="msg_success">1 row deleted successfully!</span>';
		$retVal = 1;
		}
		else
		{
		$msg = '<span class="msg_err">Record could not be deleted!</div>';
		$retVal = 0;
		}
	}
}


?>