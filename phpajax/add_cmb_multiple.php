<?php
require_once("../common/conn.php");
require_once("../rak_framework/insert.php");

session_start();
$usr=$_SESSION["user"];
if($usr==''){ 
	header("Location: ".$hostpath."/hr.php"); 
}else{

extract($_REQUEST);
/*
 add items in mu table with:
name varchar(50)
description varchar(200)
st tinyint(4)
*/
//print_r($_REQUEST);
//exit();
/*
	$name = 'Test 1';
	$description = 'Test Description';
	$st = 5;
*/

if($_REQUEST['column']['name']){
	
	switch($cmbname){
		case 'cmbdpt':
		$table = 'department';
		break;

		case 'cmbdsg':
		$table = 'designation';
		break;

		case 'cmbcontype':
		$table = 'contacttype';
		break;	
		
			
		case 'district':
		$table = 'district';
		break;			


		case 'country':
		$table = 'country';
		break;	
		
		case 'state':
		$table = 'state';
		break;
		
		case 'cmbindtype':
		$table = 'businessindustry';
		break;
		
		case 'area':
		$table = 'area';
		break;
				
		case 'cmbopttype':
		$table = 'operationstatus';
		break;

		case 'cmbstat':
		$table = 'dealstatus';
		break;
		
		case 'cmblost':
		$table = 'deallostreason';
		break;
		
		case 'itemcat':
		$table = 'itmCat';
		break;
		
		/*multiple cols tables*/
		
		case 'cmbcur':
		$table = 'currency';
		break;	
		
		case 'size':
		$table = 'sizetype';
		break;				

		case 'cmbcolor':
		$table = 'color';
		break;	
}

	
	foreach($_REQUEST['column'] as $key => $value)
	{
		$InsertKeys .= '`'.$key.'` ,' ;
		$InsertValues .= '"'.$value.'",';
	}
	$InsertKeys = substr($InsertKeys, 0, -1);
	$InsertValues = substr($InsertValues, 0, -1);
	
	$query = 'INSERT INTO '.$table.' ( 
							'.$InsertKeys.'
							) 
					VALUES (
							'.$InsertValues.'
						 )';	
	




		
if(checkExistingData($table,'name',$column['name'])){
	

	
	if($conn->query($query) == TRUE){ 
			$msg = "$cmbtitle  added successfully";
			$last_id = mysqli_insert_id($conn);
		}else{
			$msg = "Error" . mysqli_error($conn);
			//$msg = $query;
		}
		
		
		
			## Response
			$response = array(
				"msg" => $msg,
				"success" => 1,
				"value" => $column['name'],
				"id" =>  $last_id,
				"cmbname" =>  $cmbname,
			);
			
				echo json_encode($response);
		
				
	}else{//if(checkExistingData('tbl_applied_companies','trading_code',$_REQUEST['trading_code'])){				
		
		
		$response = array(
			"msg" => $column['name']." already exists",
		);
		echo json_encode($response);
		
	}


				

				
				
	}else{

	$response = array(
    "msg" => "Name is empty",
	);
	echo json_encode($response);
}
	



}
?>