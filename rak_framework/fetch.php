<?php

/**

 * ----------------------------------------------

 * RAK FRAMEWORK

 * Version 2

 * Last Updated: March 25-16

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

	

	

	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) 

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


//fetchComboHTML('paywith','paywith','form-control','transmode','col-name','col-id');


function fetchComboHTML($cmbname,$cmbid,$cmbclass,$table,$name,$id,$selected){

    /*
	$cmbname = name of select combo
    $cmbid = #id name of this control
    $cmbclass css .class names
    $table = table name from where data will be fetched
    $name = column name the name which will be displayed in combo
    $id = column name the id associated with name 
    $selected = selected id value
    $defaultOptionTxt = initial selected option like: "Select City" etc. with blank value;
    */
	global $dbname, $link;

	
	$query = 'SELECT '.$name.', '.$id.' FROM '.$table;
	//echo $query.'<br>';
	$result = mysqli_query($link, $query)
	or die("Invalid query: " . mysqli_error($link));

	$cmbstr = '<select id="'.$cmbid.'" name="'.$cmbname.'" class="'.$cmbclass.'">';
    
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) 
	{ 
		$isSelected = ($row[$id] == $selected)?"selected":"";
		$cmbstr .= '<option value="'.$row[$id].'" '.$isSelected.'>'.$row[$name].'</option>';
	}

	$cmbstr .= '</select>';
	
	echo $cmbstr;

}

//New version
//fetchComboHTML('paywith','paywith','form-control','transmode','col-name','col-id',' City');

function fetchComboHTMLv2($cmbname,$cmbid,$cmbclass,$table,$name,$id,$selected,$defaultOptionTxt){

    /*
	$cmbname = name of select combo
    $cmbid = #id name of this control
    $cmbclass css .class names
    $table = table name from where data will be fetched
    $name = column name the name which will be displayed in combo
    $id = column name the id associated with name 
    $selected = selected id value
    $defaultOptionTxt = initial selected option like: "Select City" etc. with blank value;
    */
	global $dbname, $link;

	
	$query = 'SELECT '.$name.', '.$id.' FROM '.$table;
	//echo $query.'<br>';
	$result = mysqli_query($link, $query)
	or die("Invalid query: " . mysqli_error($link));

	$cmbstr = '<select id="'.$cmbid.'" name="'.$cmbname.'" class="'.$cmbclass.'">';
    $cmbstr .= '<option value="">Select '.$defaultOptionTxt.'</option>';
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
	{ 
		$isSelected = ($row[$id] == $selected)?"selected":"";
		$cmbstr .= '<option value="'.$row[$id].'" '.$isSelected.'>'.$row[$name].'</option>';
	}

	$cmbstr .= '</select>';
	
	echo $cmbstr;

}
function fetchComboHTMLv2withcondition($cmbname,$cmbid,$cmbclass,$table,$name,$id,$selected,$defaultOptionTxt,$cond){

    /*
	$cmbname = name of select combo
    $cmbid = #id name of this control
    $cmbclass css .class names
    $table = table name from where data will be fetched
    $name = column name the name which will be displayed in combo
    $id = column name the id associated with name 
    $selected = selected id value
    $defaultOptionTxt = initial selected option like: "Select City" etc. with blank value;
    */
	global $dbname, $link;

	
	$query = 'SELECT '.$name.', '.$id.' FROM '.$table.' where '.$cond;
	//echo $query.'<br>';
	$result = mysqli_query($link, $query)
	or die("Invalid query: " . mysqli_error($link));

	$cmbstr = '<select id="'.$cmbid.'" name="'.$cmbname.'" class="'.$cmbclass.'">';
    $cmbstr .= '<option value="">Select '.$defaultOptionTxt.'</option>';
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
	{ 
		$isSelected = ($row[$id] == $selected)?"selected":"";
		$cmbstr .= '<option value="'.$row[$id].'" '.$isSelected.'>'.$row[$name].'</option>';
	}

	$cmbstr .= '</select>';
	
	echo $cmbstr;

}

function fetchComboHTMLwidthCondition($cmbname,$cmbid,$cmbclass,$table,$name,$id,$selected,$defaultOptionTxt,$condition){

    /*
    fetchComboHTMLwidthCondition('paywith','paywith','form-control','transmode','name','id','','','id=1')
	$cmbname = name of select combo
    $cmbid = #id name of this control
    $cmbclass css .class names
    $table = table name from where data will be fetched
    $name = column name the name which will be displayed in combo
    $id = column name the id associated with name 
    $selected = selected id value
    $defaultOptionTxt = initial selected option like: "Select City" etc. with blank value;
    */
	global $dbname, $link;

	
	$query = 'SELECT '.$name.', '.$id.' FROM '.$table.' WHERE '.$condition;
	//echo $query.'<br>';
	$result = mysqli_query($link, $query)
	or die("Invalid query: " . mysqli_error($link));

	$cmbstr = '<select id="'.$cmbid.'" name="'.$cmbname.'" class="'.$cmbclass.'">';
    $cmbstr .= '<option value="">Select '.$defaultOptionTxt.'</option>';
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) 
	{ 
		$isSelected = ($row[$id] == $selected)?"selected":"";
		$cmbstr .= '<option value="'.$row[$id].'" '.$isSelected.'>'.$row[$name].'</option>';
	}

	$cmbstr .= '</select>';
	
	echo $cmbstr;

}




//fetchByID('occupation_list','occupation_id',$randomData[0]['present_proffession'],'name')

function fetchByID($tblName,$QueriedByID,$QueriedByIDValue,$ReturnValue)

{

    

	global $dbname, $link, $debug;

	



	$str = 'SELECT '.$ReturnValue.' FROM '.$tblName.' WHERE '.$QueriedByID.'="'.$QueriedByIDValue.'"';
    
    //file_put_contents('query1110.txt',$str);
    
    if($debug == 1)	echo "<hr>$str<hr>";

	$result = mysqli_query($link,  $str);



	$outputData = mysqli_fetch_array($result);

	

	

	

	return $outputData[0];

}





//$fetchValues = array('company_id' => $_SESSION['USER_ID'],'tender_id' => $tender_id);

//fetchSingleDataByArray('submitted_rfp',$fetchValues,'id');

function fetchSingleDataByArray($tblName,$fetchValues,$ReturnValue){



	global $link, $debug;

	

	foreach ($fetchValues as $key => $value){

		$where .=' '.$key .'="'.$value.'" AND';

	}

	

	$where = substr($where, 0,-3);

	

	$str = 'SELECT '.$ReturnValue.' FROM '.$tblName.' WHERE '.$where;
    
   // echo $str;
	
    if($debug == 1){
    	$_SESSION['query'][$ReturnValue][] = $str;
    }

	

	$result = mysqli_query($link,  $str);

	$outputData = mysqli_fetch_array($result);

	

	

	

	return $outputData[0];	

}





// it will return only single column data array

function fetchByCondition($tblName, $condition, $WantedField) {
    global $link;

    // Check if $WantedField is an array; if so, implode it to a string
    if (is_array($WantedField)) {
        $fields = implode(',', $WantedField);
    } else {
        $fields = $WantedField;
    }

    // Build the SQL query
    $str = 'SELECT ' . $fields . ' FROM ' . $tblName . ' WHERE ' . $condition;
    
    //file_put_contents('query1110.txt',$str);
    
    //echo '<hr>'.$str.'<hr>';
    // Execute the query
    $result = mysqli_query($link, $str);

    if (!$result) {
        return []; // Return an empty array if the query fails
    }

    // Initialize outputData as an array
    $outputData = [];

    // Fetch the data
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        // If only one field is requested, store it directly, otherwise store the full row
        if (is_array($WantedField)) {
            $outputData[] = $row; // For multiple fields
        } else {
            $outputData = $row[$WantedField]; // For a single field
        }
    }

    return $outputData; // Return the result array
}


function fetchByCondition_old($tblName,$codition,$WantedField){

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


//usage getFormatedUniqueID('soitem','id','OI-',6,"0");
function getFormatedUniqueID($table,$idcolumn,$prefix,$numberlen,$padding){
		$LastID = fetchMaxRecord($table,$idcolumn);
		$newid = $prefix.str_pad($LastID, $numberlen, "$padding", STR_PAD_LEFT);
	    return $newid;
}

function strToNumber($var){
		//echo $var."<br>";
	if(strstr($var,".")){
		$var = floatval(preg_replace('/[^\d.]/', '', $var));
		
	}else{
		$var = intval(preg_replace('/[^\d.]/', '', $var));
	}
		return $var;
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

	
    $WantedField = ($WantedField)?$WantedField:'*';
	 

	$str = 'SELECT  '.$WantedField.' FROM '.$tblName.' WHERE '.$codition;

	//file_put_contents('query1110.txt',$str);

	$result = mysqli_query($link,  $str)

	or die("fetchTotalRecordByCondition: " . mysqli_error($link));

	$outputData[0] = mysqli_num_rows($result);

	

	return $outputData[0];



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


function writeErrorLog($message) {
    $logFilePath = 'error_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logFilePath, $logMessage, FILE_APPEND);
}


?>