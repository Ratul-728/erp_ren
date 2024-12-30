<?php
/**
 * ----------------------------------------------
 * RAK FRAMEWORK
 * Version 3
 * Last Updated: October 27-21
 * Developer: Md. Abul Kashem (Raihan)
 * Email: raihan@rakplanet.com
 * ----------------------------------------------
 */



function fetchData($inputData,&$outputData)
{

	global $dbname, $link;
	
	 
	$str = 'SELECT * FROM '.$inputData[TableName].' WHERE '.$inputData[FetchByKey].'='.$inputData[FetchByValue].'';
	//echo $str.'<p>';

	
	$inputData = array_slice($inputData, 3); //removed 1st 2 'TableName','FetchByKey','FetchByValue' elements from the array;
	$result = mysqli_query($link,  $str)
	or die("fetchData(): Invalid query: " . mysqli_error($link));
	
	
	while ($row = mysqli_fetch_array($result)) 
	{ 
		$dbCols = 0;
		foreach($inputData as $key => $value)
		{
			$outputData[$key] = $row[$key];
			$dbCols++;
		}
	 }
}

function fetchDataByQuery($query,&$outputData)
{
	
	global $dbname, $link;
	
	$result = mysql_query($query)
	or die("Invalid query: " . mysqli_error($link));
	
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) 
	{ 
		$outputData[] = $row;
	}
	 
}


//fetchByID('occupation_list','occupation_id',$randomData[0]['present_proffession'],'name')
function fetchByID($tblName,$QueriedByID,$QueriedByIDValue,$ReturnValue)
{

	global $dbname, $link;
	

	$str = 'SELECT '.$ReturnValue.' FROM '.$tblName.' WHERE '.$QueriedByID.'="'.$QueriedByIDValue.'"';
	//echo $str ;
	$result = mysqli_query($link,  $str);

	$outputData = mysqli_fetch_array($result);
	
	
	
	return $outputData[0];
}


//$fetchValues = array('company_id' => $_SESSION['USER_ID'],'tender_id' => $tender_id);
//fetchSingleDataByArray('submitted_rfp',$fetchValues,'id');
function fetchSingleDataByArray($tblName,$fetchValues,$ReturnValue){

	global $link;
	
	foreach ($fetchValues as $key => $value){
		$where .=' '.$key .'="'.$value.'" AND';
	}
	
	$where = substr($where, 0,-3);
	
	$str = 'SELECT '.$ReturnValue.' FROM '.$tblName.' WHERE '.$where;
	
	//echo $str ;
	
	$result = mysqli_query($link,  $str);
	$outputData = mysqli_fetch_array($result);
	
	
	
	return $outputData[0];	
}


// it will return only single column data array
function fetchByCondition($tblName,$codition,$WantedField)
{
	global $dbname, $link;
	
	 
	$str = 'SELECT '.$WantedField.' FROM '.$tblName.' WHERE '.$codition;

	//echo '<hr>'.$str.'<hr>';

	$result = mysqli_query($link,  $str);
	
	//$outputData = mysqli_fetch_array($result,MYSQLI_NUM);
	
	$cnt = 0;
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
	{ 	
		$outputData[$cnt] = $row[$WantedField];
		$cnt++;
	 } 
	 
	return $outputData;
}





function fetchMaxRecord($tblName,$WantedField)
{
	global $dbname, $link;
	
	 
	$str = 'SELECT `'.$WantedField.'` FROM `'.$tblName.'` ORDER BY `'.$WantedField.'` DESC ';
	
	$result = mysqli_query($link,  $str)
	or die("Invalid query: " . mysqli_error($link));
	$outputData = mysqli_fetch_array($result);
	
	return $outputData[0]+1;
	
}

function fetchTimeDiffByDateTime($tblName,$starttimeKey,$now,$type)
{
	global $dbname, $link;
	
	
	//query sample: SELECT TIMESTAMPDIFF(HOUR, starttime, '2021-11-07 17:58:46') AS hours_different FROM starter
	 $str = 'SELECT TIMESTAMPDIFF('.$type.', '.$starttimeKey.', '.$now.') AS hours_different FROM '.$tblName;
	 
	//echo '<hr>'.$str.'<hr>';
	
	$result = mysqli_query($link,  $str)
	or die("Invalid query: " . mysqli_error($link));
	$outputData = mysqli_fetch_array($result);
	
	return $outputData[0];
	
}


function fetchLatestRecord($tblName,$WantedField)
{
	global $dbname, $link;
	
	 
	$str = 'SELECT `'.$WantedField.'` FROM `'.$tblName.'` ORDER BY `'.$WantedField.'` DESC ';
	
	$result = mysqli_query($link,  $str)
	or die("Invalid query: " . mysqli_error($link));
	$outputData = mysqli_fetch_array($result);
	
	return $outputData[0];
	
}


function fetchMaxRecord_old($inputData,&$outputData)
{
	
	global $dbname, $link;
	
	 
	$str = 'SELECT MAX('.$inputData[KeyToBeFetched].')+1 FROM '.$inputData[TableName];
	//echo $str.'<p>';

	
	$inputData = array_slice($inputData, 1); //removed 1st 2 'TableName','FetchByKey','FetchByValue' elements from the array;
	$result = mysqli_query($link,  $str)
	or die("Invalid query: " . mysqli_error($link));
	
	$outputData = mysqli_fetch_array($result);
	$outputData = $outputData[0];
	//$outputData = str_pad($outputData, 4, "0", STR_PAD_LEFT);
}
function fetchTotalRecord($tblName,$QueriedByID,$QueriedByIDValue)
{
	global $dbname, $link;
	

	$str = 'SELECT * FROM '.$tblName.' WHERE '.$QueriedByID.'='.$QueriedByIDValue;
	//echo $str;
	$result = mysqli_query($link,  $str);
	return mysqli_num_rows($result);
	
	
}

//fetchTotalRecordByCondition('tbl_album','UserID = "'.$uid.'" AND show_in_mycontent = 1','show_in_mycontent');
function fetchTotalRecordByCondition($tblName,$codition,$WantedField)
{
	global $dbname, $link;
	
	 
	$str = 'SELECT '.$WantedField.' FROM '.$tblName.' WHERE '.$codition;
	//echo $str;
	$result = mysqli_query($link,  $str)
	or die("fetchTotalRecordByCondition: " . mysqli_error($link));
	


	$outputData[0] = mysqli_num_rows($result);
	
	return $outputData[0];

}



function fetchTotalRecordByConditionV2($tblName,$condition)
{
	global $dbname, $link;

	$str = 'SELECT * FROM '.$tblName.' WHERE '.$condition;
	//echo $str;
	$result = mysqli_query($link,  $str);
	return mysqli_num_rows($result);
	
	
}



function fetchTotalSum($tblName,$colName,$condition)
{
	global $dbname, $link;
	
	 
	//$str = 'SELECT  SUM('.$colName.') AS "total" FROM '.$tblName.' WHERE '.$codition;
	$str = 'SELECT SUM('.$colName.') AS "total" FROM '.$tblName.' WHERE '.$condition; 
	//echo $str.'<br>';
	$result = mysqli_query($link,  $str)
	or die("fetchTotalRecordByCondition: " . mysqli_error($link));

	//return mysql_fetch_row['total'];
	while ($row = mysqli_fetch_assoc($result))
	{
 		return $row["total"];
	}

	
}

function fetchAdminEmails(&$EmailAddresses)
{
			global $dbname, $link;
			 
			 
			 

		 	$str	= 'SELECT Email from tbl_admin';
			 
			  $result = mysqli_query($link,  $str);
			  
			   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) 
			   { 
				$EmailAddresses .= $row["Email"].', ';
			   }
			   $EmailAddresses = substr($EmailAddresses,0, strlen($EmailAddresses)-2);
}
?>