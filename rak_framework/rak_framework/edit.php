<?php



// last udpated 29th May 2016;



function updateData($inputData,&$msg,&$success)

{	

	global $date,$dbname,$link;

	



	

	//$Password =  sha1($Password);

	

	$TableName = $inputData[TableName];

	

	$FetchByKey = $inputData[FetchByKey];

	$FetchByValue = $inputData[FetchByValue];

	

	

	//if more than one	where condition required to update data. call it following way:

	/*

			$inputData = array(

				'TableName' => 'quotation_submitted',	//table name

				'FetchByKey' => array('quotation_id','company_id'),

				'FetchByValue' =>  array($item['quotation_id'], $_SESSION['USER_ID']),

			);

	*/	

	

	if(is_array($FetchByKey)){

		$FetchByKey = $inputData[FetchByKey];

		$FetchByValue = $inputData[FetchByValue];

		



		for($i=0; $i<count($FetchByKey); $i++){

			//echo '===='.$i.'<br>';

			$condition .= $FetchByKey[$i] .' = "'.$FetchByValue[$i].'" AND ';

		}

			$condition = substr($condition, 0, -4);

	}else{

			$condition = $FetchByKey .' = "'.$FetchByValue.'"';

		}

	//echo $condition;

	//exit();

	

	//$ImageName = $inputData[ImageName];

	//echo $ImageName;

	$inputData = array_slice($inputData, 3); //removed 1st 3 elements from the array;



	

	foreach($inputData as $key => $value)

	{

		$updateStatement .= '`'.$key.'` = "'.$value.'",';

	}

	

	$updateStatement = substr($updateStatement, 0, -1);

	

	$str = 'UPDATE '.$TableName.' SET 

			'.$updateStatement.'

		   WHERE '.$condition;

		   

 	//echo '<hr>'.$str.'<hr><br>';



		$result = mysqli_query($link,  $str)

		or die("updateData(): I " . mysqli_error($link));

		

		if($result)

		{

		$msg = '<span>Data updated successfully</span>';

		$success = 1;

		}

		else

		{

		$msg = '<span>Could not update data</span>';

		}

}





//updateAllSameData('tbl_applied_shared','is_gained','updatableValue',condition);

function updateAllSameData($tableName,$colToUpdate,$updatableValue,$condition)

{

	global $dbname, $link;

		

	$str = 'UPDATE '.$tableName.' SET '.$colToUpdate.'='.$updatableValue.' WHERE '.$condition;

	//echo '<p>'.$str.'</p>';



	$result = mysqli_query($link,  $str)

	or die("Invalid query: " . mysqli_error($link));

	

	if($result)

	{

	$msg = '<span class="msg_success">Data updated successfully</span>';

		return true;

	}

	else

	{

	$msg = '<span class="msg_err">Could not update data</span>';

		return false;

	}	

}



function updateByID($tableName,$colToUpdate,$updatableValue,$condition)

{

	global $dbname, $link;

		

	$str = 'UPDATE '.$tableName.' SET '.$colToUpdate.'='.$updatableValue.' WHERE '.$condition;

	//echo $str."<p>";



	$result = mysqli_query($link,  $str)

	or die("Invalid query: " . mysqli_error($link));

	

	if($result)

	{

	$msg = '<b><font face="arial" size=2 color=green>Data updated successfully</b></font>';

	$success = 1;

	return true;

	}

	else

	{

	$msg = '<b><font face="arial" size=2 color=red>Could not update data</b></font>';

	return false;

	}	

}

?>