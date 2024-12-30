<?php

/**

 * ----------------------------------------------

 * RAK FRAMEWORK

 * Version 1.4

 * Last Updated: Dec 17, 2014

 * Last Updated: May 05, 2010

 * Last Updated: Feb 24, 2010

 * Last Updated: March 23, 2009



 

 

 * Developer: Md. Abul Kashem (Raihan)

 * Email: raihan@rakplanet.com

 * ----------------------------------------------

 

  Change info on version 1.2;

  1. searchData($inputData,&$outputData); added

  

  

  Change info on version 1.3;

  1. listJointData($inputData,&$outputData); added



  Change info on version 1.4;

  1. listData(): individual column query is implemented instead of select *;

  2. listData(): gropu by feature is implemented;

  3. listData(): running project was: pinnacle executive search;





 

 	Sample Inner Joint query;

	

 	SELECT * 

	FROM tbl_lost_bin

	INNER JOIN cmb_category ON tbl_lost_bin.category_id = cmb_category.id

	ORDER BY  `cmb_category`.`name` DESC 

	LIMIT 0 , 30

	

	sample use of listJointData



	$inputLostData = array(

	'TableName' => 'tbl_lost_bin',

	'OrderBy' => $OrderBy,

	'ASDSOrder' => $ASDSOrder,

	'InnerJoint' => 'cmb_category',

	'On' => 'tbl_lost_bin.category_id = cmb_category.id',

	

	

	'tbl_lost_bin.id' => '',

	'tbl_lost_bin.user_id' => '',

	'tbl_lost_bin.category_id' => 1,

	'cmb_category.name' => '',

	'tbl_lost_bin.title' => '',

	'tbl_lost_bin.picture' => '',

	'tbl_lost_bin.description' => '',

	'tbl_lost_bin.city' => '',

	'tbl_lost_bin.country' => '',

	'tbl_lost_bin.reward_info' => ''

	);



	

	

	

 */

function listData($inputData,&$outputData)

{

	global $dbname, $link, $debug;

	

	

	$conditions = array_slice($inputData, 3);





	foreach($conditions as $key => $value){

		$columnsToSelect .= $key.', ';

	}

	$columnsToSelect = substr($columnsToSelect, 0,-2);

	

	

//	$str = 'SELECT * '.$selectQuery.' FROM '.$inputData[TableName].'';

	if(strstr($columnsToSelect, 'special_select')){

		$str = 'SELECT * FROM '.$inputData[TableName].'';

	}else{

		$str = 'SELECT '.$columnsToSelect.' FROM '.$inputData[TableName].'';

		}

	

	//echo 'First: '.$str.'<p>';



	//Generate Where Statement	

	

	

	foreach($conditions as $key => $value)

	{

		//echo $value."<p>";

		//array query

		if(is_array($value))

		{

			if($value['customQuery'] == 'getByMonthYear')

			{

				if($value['month'] && $value['year'])

				{

					$where .=' YEAR('.$key .')='.$value['year'].' AND MONTH('.$key.')='.$value['month'].' AND';	

				}

			}

			

			if($value['customQuery'] == 'sqlString')

			{

					$where .=' '.$value['qString'].' AND';	

			}

			

		//special select

		}else if($key == 'special_select'){

			$str = str_replace("*","*, ".$value,$str);

		}

		else{

		

		//general query

		

		if(strstr($value, 'NOTNULL'))

		{

			$where .=' '.$key .'!="" AND';	

		}

		else if(strstr($value, 'LIKE'))

		{

			if(strstr($value, 'OR'))	//Usage: $inputData['product_title'] = 'LIKE "%'.$search_key.'%" OR';

			{

				$value = str_replace("OR", "", $value);

				$where .=' '.$key .' '.$value.' OR';

			}

			else

			{

				$where .=' '.$key .' '.$value.' AND';

			}

		}



		else if(strstr($value, '<='))

		{

			$where .=' '.$key .' <= '.substr($value,2).' AND';	

		}		





		else if(strstr($value, '<'))

		{

			$where .=' '.$key .' < '.substr($value,1).' AND';	

		}



		else if(strstr($value, '>=')) //use:  'end_date' => ' >= "'.date("Y-m-d").'"',

		{

			$where .=' '.$key .' >= '.substr($value,2).' AND';	

		}		





		else if(strstr($value, '>'))	//use:  'end_date' => ' > "'.date("Y-m-d").'"',

		{

			$where .=' '.$key .' > '.substr($value,1).' AND';	

		}

		else if(strstr(strtolower($value), strtolower('DISTINCT')))	//use:  'end_date' => ' > "'.date("Y-m-d").'"',

		{

			$groupby .='GROUP BY '.$key;	

		}		

		else if($value)

		{

			if(substr($value,0,4) == 'NOT=')

			{

				$where .=' '.$key .'!='.substr($value,4).' AND';

			}

			else

			{

				 $where .=' '.$key .'="'.$value.'" AND';

			}

		}

		//end general query

		

		}//if(is_array($value))

		

	}//end foreach foreach($conditions as $key => $value)

	//echo $where;

	if($where){

	$str .= ' WHERE ';

	}

	

	//some time whre need to be shown in case like: WHERE name="DISTINCT"  so



	$where = substr($where, 0,-3);

	

	if($groupby){

		$str .= $where. ' '.$groupby;

	}else{

		$str .= $where. ' ORDER BY '.$inputData[OrderBy].' '.$inputData[ASDSOrder].'';

		}

	

	if($debug == 1)	echo "<hr>$str<hr>";



	$inputData = array_slice($inputData, 3); //removed 1st 2 element from the array; on is 'TableName', and the other one is OrderBy.	

	$result = mysqli_query($link,  $str) 

	or die ('Error: '.mysqli_error($link));

	$dbRows = 0;

	



	while ($row = mysqli_fetch_array($result))

	{ 	

		$dbCols = 0;

		foreach($inputData as $key => $value)

		{

			if($key == 'special_select')

			{

/*				$speciakKey = explode("AS ", $value);

				$speciakKey = $speciakKey[1];

				//echo $dbCols.'. '.$speciakKey[1].'<br>';

				

				$outputData[$dbRows][$speciakKey] = $row[$speciakKey];

				

				$dbCols++;*/

			}

			else

			{

				$outputData[$dbRows][$key] = $row[$key];

				//echo $dbCols.'. '.$row[$key].'<br>';

				//echo $row['numberofdays'].'<br>';

				$dbCols++;

			}

		}

		$dbRows++;



		

	 } 

}





/*function listDataBySQL($inputData,&$outputData)

{

	global $dbname, $link;

	

	

	

	$str = $inputData;

	//echo $str.'<p>';



	$result = mysqli_query($link,  $str) 

	or die ('Error: '.mysqli_error($link));

	$dbRows = 0;

	

	$row = mysqli_fetch_array($result, MYSQL_ASSOC);



	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))

	{ 	

		$dbCols = 0;



		$outputData[][$key] = $row[$key];



		$dbCols++;





		$dbRows++;



		

	 }

	 

}*/



/*

	$inputFoundData = array(

	'TableName' => 'tbl_found_bin',

	'OrderBy' => $OrderBy,

	'ASDSOrder' => $ASDSOrder,

	'InnerJoint' => 'cmb_category',

	'On' => 'tbl_found_bin.category_id = cmb_category.id',

	

	

	'tbl_found_bin.id' => '',

	'tbl_found_bin.user_id' => '',

	'tbl_found_bin.category_id' => '',

	'cmb_category.name' => '',

	'tbl_found_bin.title' => '',

	'tbl_found_bin.picture' => '',

	'tbl_found_bin.description' => '',

	'tbl_found_bin.city' => '',

	'tbl_found_bin.country' => '',

	'tbl_found_bin.btn_state' => ''

	);

	

	listJointData($inputFoundData,$data);	

*/

function listJointData($inputData,&$outputData)

{

	global $dbname, $link, $debug;

	

	

	// generate column data

	

	$columnsToList = array_slice($inputData, 4);

	

	foreach($columnsToList as $column => $colValues)

	{

		

		

		if(strstr($colValues, 'AS'))

		{

			//$colNames .=' '.$key .' as '.substr($colValues,2).' AND';

			$colNames .= $column.' as '.substr($colValues,3).', ';

		}

		else

		{

			$colNames .= $column.', ';	

		}	

	}

	

	$colNames = substr($colNames, 0, strlen(trim($colNames))-1);

	

	$str = 'SELECT '.$colNames.' FROM '.$inputData['TableName'].'';

	



	

	//$str = 'SELECT * FROM '.$inputData[TableName].' INNER JOIN '.$inputData[InnerJoint].' ON '.$inputData[On].' ' ;

	

	

	foreach($inputData['InnerJoint'] as $ijValue){

		

		$str = $str.' INNER JOIN '.$ijValue['tableToJoin'].' ON '.$ijValue['On'].' ' ;

	}

	

	//$str = $str.' INNER JOIN '.$inputData[InnerJoint].' ON '.$inputData[On].' ' ;

	

	





	//Generate Where Statement	

	

	$conditions = array_slice($inputData, 4);

	

	/*

	foreach($conditions as $key => $value)

	{

		if(strstr($value, 'NOTNULL'))

		{

			$where .=' '.$key .'!="" AND';	

		}

		else if(strstr($value, 'LIKE'))

		{

			$where .=' '.$key .' '.$value.' AND';	

		}

		else if($value)

		{

			if(substr($value,0,4) == 'NOT=')

			{

				$where .=' '.$key .'!='.substr($value,4).' AND';

			}

			else

			{

				 $where .=' '.$key .'="'.$value.'" AND';

			}

		}

	}

	*/

	

	foreach($conditions as $key => $value)

	{

		if(strstr($value, 'AS'))

		{

			$where .='';	

		}

		else if(strstr($value, 'NOTNULL'))

		{

			$where .=' '.$key .'!="" AND';	

		}









		else if(strstr($value, 'LIKE'))

		{

			$where .=' '.$key .' '.$value.' AND';	

		}



		else if(strstr($value, '<='))

		{

			$where .=' '.$key .' <= '.substr($value,2).' AND';	

		}		





		else if(strstr($value, '<'))

		{

			$where .=' '.$key .' < '.substr($value,1).' AND';	

		}



		else if(strstr($value, '>=')) //use:  'end_date' => ' >= "'.date("Y-m-d").'"',

		{

			$where .=' '.$key .' >= '.substr($value,2).' AND';	

		}		





		else if(strstr($value, '>'))	//use:  'end_date' => ' > "'.date("Y-m-d").'"',

		{

			$where .=' '.$key .' > '.substr($value,1).' AND';	

		}

		else if($value)

		{

			if(substr($value,0,4) == 'NOT=')

			{

				$where .=' '.$key .'!='.substr($value,4).' AND';

			}

			else

			{

				//OR implemented on 26th May 2016, if causes any issue please remove the if statement full and uncomment the its bellow line;

				if(strstr($value, 'OR'))	//use: 'submitted_rfp.company_id' => $_SESSION['USER_ID'].' OR',

				{

					$where .=' '.$key .'="'.substr($value,0,2).'" OR';	

					

				}else{

					$where .=' '.$key .'="'.$value.'" AND';

					}

				

				// $where .=' '.$key .'="'.$value.'" AND';

			}

		}

	}	

	

	//echo $where;

	if($where){

	$str .= ' WHERE ';

	}

	

	$where = substr($where, 0,-3);

	

	$str .= $where. ' ORDER BY '.$inputData[OrderBy].' '.$inputData[ASDSOrder].'';

	

	if($debug == 1){

	echo '<hr><p>'.$str.'</p><hr><p>';

	}

	//sample query: SELECT table_product_subcategory.id, table_product_subcategory.name, table_product_category.name as cname FROM table_product_subcategory INNER JOIN table_product_category ON table_product_subcategory.category_id = table_product_category.id ORDER BY id DESC



	$inputData = array_slice($inputData, 4); //removed 1st 2 element from the array; on is 'TableName', and the other one is OrderBy.	

	$result = mysqli_query($link,  $str);

	$dbRows = 0;

	



	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))

	{ 	

		$dbCols = 0;

		foreach($inputData as $key => $value)

		{

			

			if(strstr($value, 'AS'))

			{

				$key = substr($value,3);	

			}

			else

			{

				$key = explode(".",$key);

				$key = $key[1];			

			}			

			

			



			$outputData[$dbRows][$key] = $row[$key];

			//echo $dbCols.'. '.$row[$key].'<br>';

			$dbCols++;

		}

		$dbRows++;

	 }

}





// backup listJointData() of version 1.4

/*



function listJointData($inputData,&$outputData)

{

	global $dbname, $link;

	

	

	// generate column data

	

	$columnsToList = array_slice($inputData, 5);

	

	foreach($columnsToList as $column => $colValues)

	{

		

		

		if(strstr($colValues, 'AS'))

		{

			//$colNames .=' '.$key .' as '.substr($colValues,2).' AND';

			$colNames .= $column.' as '.substr($colValues,3).', ';

		}

		else

		{

			$colNames .= $column.', ';	

		}	

	}

	

	$colNames = substr($colNames, 0, strlen(trim($colNames))-1);

	

	$str = 'SELECT '.$colNames.' FROM '.$inputData[TableName].'';

	



	

	//$str = 'SELECT * FROM '.$inputData[TableName].' INNER JOIN '.$inputData[InnerJoint].' ON '.$inputData[On].' ' ;

	

	$str = $str.' INNER JOIN '.$inputData[InnerJoint].' ON '.$inputData[On].' ' ;

	

	





	//Generate Where Statement	

	

	$conditions = array_slice($inputData, 5);

	



	

	foreach($conditions as $key => $value)

	{

		if(strstr($value, 'AS'))

		{

			$where .='';	

		}

		else if(strstr($value, 'NOTNULL'))

		{

			$where .=' '.$key .'!="" AND';	

		}

		else if(strstr($value, 'LIKE'))

		{

			$where .=' '.$key .' '.$value.' AND';	

		}



		else if(strstr($value, '<='))

		{

			$where .=' '.$key .' <= '.substr($value,2).' AND';	

		}		





		else if(strstr($value, '<'))

		{

			$where .=' '.$key .' < '.substr($value,1).' AND';	

		}



		else if(strstr($value, '>=')) //use:  'end_date' => ' >= "'.date("Y-m-d").'"',

		{

			$where .=' '.$key .' >= '.substr($value,2).' AND';	

		}		





		else if(strstr($value, '>'))	//use:  'end_date' => ' > "'.date("Y-m-d").'"',

		{

			$where .=' '.$key .' > '.substr($value,1).' AND';	

		}

		else if($value)

		{

			if(substr($value,0,4) == 'NOT=')

			{

				$where .=' '.$key .'!='.substr($value,4).' AND';

			}

			else

			{

				 $where .=' '.$key .'="'.$value.'" AND';

			}

		}

	}	

	

	//echo $where;

	if($where){

	$str .= ' WHERE ';

	}

	

	$where = substr($where, 0,-3);

	

	$str .= $where. ' ORDER BY '.$inputData[OrderBy].' '.$inputData[ASDSOrder].'';

	

	echo '<hr>'.$str.'<hr><p>';

	

	//sample query: SELECT table_product_subcategory.id, table_product_subcategory.name, table_product_category.name as cname FROM table_product_subcategory INNER JOIN table_product_category ON table_product_subcategory.category_id = table_product_category.id ORDER BY id DESC



	$inputData = array_slice($inputData, 5); //removed 1st 2 element from the array; on is 'TableName', and the other one is OrderBy.	

	$result = mysqli_query($link,  $str);

	$dbRows = 0;

	



	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))

	{ 	

		$dbCols = 0;

		foreach($inputData as $key => $value)

		{

			

			if(strstr($value, 'AS'))

			{

				$key = substr($value,3);	

			}

			else

			{

				$key = explode(".",$key);

				$key = $key[1];			

			}			

			

			



			$outputData[$dbRows][$key] = $row[$key];

			//echo $dbCols.'. '.$row[$key].'<br>';

			$dbCols++;

		}

		$dbRows++;

	 } 

}





*/



function searchData($inputData,&$outputData)

{

	global $dbname, $link;

	

	

	$CopiedTableName = $inputData[TableName];

	

	$str = 'SELECT * FROM '.$inputData[TableName].'';

	



	//Generate Where Statement	

	

	$conditions = array_slice($inputData, 3);

	foreach($conditions as $key => $value)

	{



		

		if($value)

		{

			$where .=' '.$key .' LIKE "%'.$value.'%" AND';

		}

	}

	

	if($CopiedTableName == 'people')

	{

		$where .= ' first_name != ""      ';

	}

	

	if($CopiedTableName == 'business')

	{

		$where .= ' company_name  != ""      ';

	}	



	

	//echo $where;

	if($where){

	$str .= ' WHERE ';

	}

	

	

	$where = substr($where, 0,-3);

	

	$str .= $where. ' ORDER BY `'.$inputData[OrderBy].'` '.$inputData[ASDSOrder].'';

	

	//echo $str.'<p>';



	$inputData = array_slice($inputData, 3); //removed 1st 2 element from the array; on is 'TableName', and the other one is OrderBy.	

	$result = mysqli_query($link,  $str);

	$dbRows = 0;

	



	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))

	{ 	

		$dbCols = 0;

		foreach($inputData as $key => $value)

		{

			$outputData[$dbRows][$key] = $row[$key];

			//echo $dbCols.'. '.$row[$key].'<br>';

			$dbCols++;

		}

		$dbRows++;

	 } 

}



function searchDataForAdmin($inputData,&$outputData)

{

	global $dbname, $link;

	

	

	$CopiedTableName = $inputData[TableName];

	

	$str = 'SELECT * FROM '.$inputData[TableName].'';

	



	//Generate Where Statement	

	

	$conditions = array_slice($inputData, 3);

	foreach($conditions as $key => $value)

	{



		

		if($value)

		{

			$where .=' '.$key .' LIKE "%'.$value.'%" AND';

		}

	}

	

	



	

	//echo $where;

	if($where){

	$str .= ' WHERE ';

	}

	

	

	$where = substr($where, 0,-3);

	

	$str .= $where. ' ORDER BY `'.$inputData[OrderBy].'` '.$inputData[ASDSOrder].'';

	

	//echo $str.'<p>';



	$inputData = array_slice($inputData, 3); //removed 1st 2 element from the array; on is 'TableName', and the other one is OrderBy.	

	$result = mysqli_query($link,  $str);

	$dbRows = 0;

	



	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))

	{ 	

		$dbCols = 0;

		foreach($inputData as $key => $value)

		{

			$outputData[$dbRows][$key] = $row[$key];

			//echo $dbCols.'. '.$row[$key].'<br>';

			$dbCols++;

		}

		$dbRows++;

	 } 

}





function listRandomData($inputData,&$outputData)

{

	global $dbname, $link;

	

	

	$str = 'SELECT * FROM '.$inputData[TableName].'';

	



	//Generate Where Statement	

	

	$conditions = array_slice($inputData, 3);

	foreach($conditions as $key => $value)

	{

		if(strstr($value, 'NOTNULL'))

		{

			$where .=' '.$key .'!="" AND';	

		}

		else if($value)

		{

			if(substr($value,0,4) == 'NOT=')

			{

				$where .=' '.$key .'!='.substr($value,4).' AND';

			}

			else

			{

				 $where .=' '.$key .'="'.$value.'" AND';

			}

		}

	}

	//echo $where;

	if($where){

	$str .= ' WHERE ';

	}

	

	$where = substr($where, 0,-3);

	

	$str .= $where. ' ORDER BY '.$inputData[OrderBy].' '.$inputData[ASDSOrder].'';

	

	//echo $str.'<p>';



	$inputData = array_slice($inputData, 3); //removed 1st 3 element from the array; on is 'TableName', and the other one is OrderBy.	

	$result = mysqli_query($link,  $str);

	$dbRows = 0;

	



	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))

	{ 	

		$dbCols = 0;

		foreach($inputData as $key => $value)

		{

			$outputData[$dbRows][$key] = $row[$key];

			//echo $dbCols.'. '.$row[$key].'<br>';

			$dbCols++;

		}

		$dbRows++;

	 } 

}



// list data group by and adding SUM() method

function listDataGroupBy($inputData,&$outputData)

{

	global $dbname, $link;

	

	

	$columnsToList = array_slice($inputData, 4);

	

	foreach($columnsToList as $column => $colValues)

	{

		$colNames .= $column.', ';

	}

	

	$colNames = substr($colNames, 0, strlen(trim($colNames))-1);

	

	$str = 'SELECT '.$colNames.' FROM '.$inputData[TableName].'';

	



	//Generate Where Statement	

	

	$conditions = array_slice($inputData, 4);



	foreach($conditions as $key => $value)

	{

		if(strstr($value, 'NOTNULL'))

		{

			$where .=' '.$key .'!="" AND';	

		}

		else if($value)

		{

			if(substr($value,0,4) == 'NOT=')

			{

				$where .=' '.$key .'!='.substr($value,4).' AND';

			}

			else

			{

				 $where .=' '.$key .'="'.$value.'" AND';

			}

		}

	}

	//echo $where;

	if($where){

	$str .= ' WHERE ';

	}

	

	$where = substr($where, 0,-3);

	

	$str .= $where. 'GROUP BY `'.$inputData[GroupBy].'` ORDER BY `'.$inputData[OrderBy].'` '.$inputData[ASDSOrder].'';

	

	//echo $str.'<p>';



	$inputData = array_slice($inputData, 3); //removed 1st 2 element from the array; on is 'TableName', and the other one is OrderBy.	

	$result = mysqli_query($link,  $str);

	$dbRows = 0;

	



	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))

	{ 	

		$dbCols = 0;

		foreach($inputData as $key => $value)

		{

			if(strstr($key,' ')){

			$key = explode(' ', $key);

			$key = trim($key[2]);

			}

			

			$outputData[$dbRows][$key] = $row[$key];

			//echo $key.'. '.$row[$key].'<br>';

			$dbCols++;

		}

		$dbRows++;

	 } 

}

//Below is the old version o flistData;

function listData_OLD($inputData,&$outputData)

{

	global $dbname, $link;

	

	

	$str = 'SELECT * FROM '.$inputData[TableName].'';

	



	//Generate Where Statement	

	

	$conditions = array_slice($inputData, 3);

	foreach($conditions as $key => $value)

	{

		if($value){

		$where .=' '.$key .'="'.$value.'" AND';

		}

	}

	if($where){

	$str .= ' WHERE ';

	}

	

	$where = substr($where, 0,-3);

	

	$str .= $where. ' ORDER BY `'.$inputData[OrderBy].'` '.$inputData[ASDSOrder].'';

	



	//echo $str.'<p>';



	$inputData = array_slice($inputData, 3); //removed 1st 2 element from the array; on is 'TableName', and the other one is OrderBy.	

	$result = mysqli_query($link,  $str);

	$dbRows = 0;

	



	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))

	{ 	

		$dbCols = 0;

		foreach($inputData as $key => $value)

		{



			$outputData[$dbRows][$dbCols] = $row[$key];

			//echo $dbCols.'. '.$row[$key].'<br>';

			$dbCols++;

		}

		$dbRows++;

	 } 

}



function listDataWithAgeRange($inputData,&$outputData,$ageFrom,$ageTo)

{

	global $dbname, $link;

	

	

	$str = 'SELECT * FROM '.$inputData[TableName].'';

	



	//Generate Where Statement	

	

	$conditions = array_slice($inputData, 3);



	$where .='`dob` <="'.$ageFrom.'-00-00 00:00:00" AND `dob` >= "'.$ageTo.'-00-00 00:00:00"';



	if($where){

	$str .= ' WHERE ';

	}

	



	

	$str .= $where. ' ORDER BY `'.$inputData[OrderBy].'` '.$inputData[ASDSOrder].'';

	



	//echo $str.'<p>';



	$inputData = array_slice($inputData, 3); //removed 1st 2 element from the array; on is 'TableName', and the other one is OrderBy.	

	$result = mysqli_query($link,  $str);

	$dbRows = 0;

	



	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))

	{ 	

		$dbCols = 0;

		foreach($inputData as $key => $value)

		{

			$outputData[$dbRows][$dbCols] = $row[$key];

			//echo $dbCols.'. '.$row[$key].'<br>';

			$dbCols++;

		}

		$dbRows++;

	 } 

}



function listCmbData($inputData,&$outputData,$condition)

{

	global $dbname, $link;

	

	

	//$str = 'SELECT DISTINCT '.$inputData[OrderBy].' FROM '.$inputData[TableName].' '.$condition.' ORDER BY `'.$inputData[OrderBy].'` '.$inputData[ASDSOrder].'';

	$str = 'SELECT DISTINCT '.$inputData[OrderBy].' FROM '.$inputData[TableName].' WHERE  '.$inputData[OrderBy].' != \'\' '.$condition.' ORDER BY `'.$inputData[OrderBy].'` '.$inputData[ASDSOrder].'';







	$inputData = array_slice($inputData, 3); //removed 1st 2 element from the array; on is 'TableName', and the other one is OrderBy.

	





	//echo $str.'<p>';

	$result = mysqli_query($link,  $str)

	or die(mysqli_error($link));

	$dbCols = 0;

	



	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))

	{ 	

		

		foreach($inputData as $key => $value)

		{

			$outputData[$dbCols] = $row[$key];

			//echo $dbCols.'. '.$row[$key].'<br>';

			$dbCols++;

		}

		

	 } 

}

/*

	foreach($arrayUsr as $key => $value)

	{

		echo $key.' : '.$value.'<br>';

	}

*/

?>