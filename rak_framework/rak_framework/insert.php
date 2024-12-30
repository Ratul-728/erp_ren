<?php
//checkExistingData('tbl_applied_companies','trading_code',$_REQUEST['trading_code'])
//if data found it returns false otherwise it returns true;
function checkExistingData($TableName,$KeyToCheck,$KeyValue)
{
	 global $dbname, $link;
	 
	 
	 
	 $str	= 'SELECT * FROM '.$TableName.' WHERE '.$KeyToCheck.'="'.$KeyValue.'"';
	 //echo $str;
	 $result = mysqli_query($link,  $str)
	 or die("Invalid query: " . mysqli_error($link));

				 
	 if(mysqli_num_rows($result) > 0)
	 {
		return false;
	  }	
	else
	{
		return true;
	}
}


function checkExistingDataByCondition($TableName,$condition)
{
	 global $dbname, $link;
	 
	 
	 
	 $str	= 'SELECT * FROM '.$TableName.' WHERE '.$condition;
	 //echo $str;
	 $result = mysqli_query($link,  $str)
	 or die("Invalid query: " . mysqli_error($link));

				 
	 if(mysqli_num_rows($result) > 0)
	 {
		return false;
	  }	
	else
	{
		return true;
	}
}



function insertData($inputData,&$msg,&$success,&$insertId)
{
	global $date,$dbname,$link;
	
	
	$TableName = $inputData[TableName];
	$inputData = array_slice($inputData, 3); //removed 1st element 'TableName'  from the array;

	
	foreach($inputData as $key => $value)
	{
		$InsertKeys .= '`'.$key.'` ,' ;
		$InsertValues .= '"'.$value.'",';
	}
	$InsertKeys = substr($InsertKeys, 0, -1);
	$InsertValues = substr($InsertValues, 0, -1);
	
	$str = 'INSERT INTO '.$TableName.' ( 
							'.$InsertKeys.'
							) 
					VALUES (
							'.$InsertValues.'
						 )';
	//echo $str.'<br>';

	$result = mysqli_query($link,  $str)
	or die("insertData():  Invalid query = : " . mysqli_error($link));
	$insertId = mysqli_insert_id($link);
		if($result)
		{
		$msg = '<span class="msg_success">Data added successfully</span>';
		$success = 1;
		}
		else
		{
		$msg = '<span class="msg_err">Error: '.mysqli_error($link).'</span>';
		}
	
	//mysqli_close($link);
	unset($link);
}


// E.G: uploadFile('',$_FILES,&$uploadMsg,&$uploadSuccess);

/*
function uploadFile($fileName,$_FILES,&$uploadMsg,&$uploadSuccess)
{
	//Store image data in string;
	//Store image data in string;
	global $tempUploadPath;
	//check file type. only jpg allowed.

	//echo $_FILES['picture']['type'];
	
	//extention handler;
	$pictureName = strtolower($_FILES['picture']['name']);

	$extentionArray = explode(".", $pictureName);

	$extentionArray = array_reverse($extentionArray);
	//print_r($extentionArray);
	$ext = $extentionArray[0];
	
	//echo 'dd '.$ext;
	
	$supportedImages = array('jpeg','jpg','gif','png','pdf','doc','docx','xls','xlsx','zip','rar');
	


	

	if(in_array($ext,$supportedImages))
	{
		//echo $fileName;
		//exit();
				if($_FILES['picture']['tmp_name'])
				{//$uploadPath.'/'.
					if(move_uploaded_file($_FILES['picture']['tmp_name'],$fileName))
					{
					  $uploadMsg = '<b><font face="arial" size=2 color=green>File upload successfull</font></b>';
					  $uploadSuccess = 1;
					}
					else
					{
					 $uploadMsg = '<b><font face="arial" size=2 color=red>Error occured uploading file';
					 $uploadSuccess = 0;
					}//if(move_uploaded_file($_FILES['resume']['tmp_name'],$uploadPath.'/'.$insertId.'.doc'))
				}//if($_FILES['resume']['tmp_name'])
	
		//End image storing;
		}
		else
		{
			$uploadMsg = '<span style="color:#ff0000;">Pleaes upload a supported file</span>';
		}
		
}
*/

function insertTableLink($tableName,$key1, $value1,$key2,$value2,&$msg)
{
	 global $dbname, $link;
	 
	 
		//insert
		$str = 'INSERT INTO '.$tableName.' ('.$key1.','.$key2.') VALUES ("'.$value1.'", "'.$value2.'")';
	 	
		$result = mysqli_query($link,  $str)
		
		or die("insertLinkTable error: " . mysqli_error($link));
		
		if($result)
		{
			$msg = '<span class="message"><b><font face="arial" size=2 color=green>Data successfully added</b></font></span>';
		}
		else
		{
			$msg = '<span class="message"><b><font face="arial" size=2 color=red>Could not add data</b></font></span>';
		}

}

?>