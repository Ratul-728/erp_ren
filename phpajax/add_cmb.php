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
/*
	$name = 'Test 1';
	$description = 'Test Description';
	$st = 5;
*/
if($cmbname == "cmborg"){
    if($name != '' && $orgcontact != '' && $managername != '' && $managercontact != ''){
        
        $nm = $_REQUEST['name'];
        //$cntper = $_POST['cntper'];
        $cmbindtype = 'NULL'; 
        $empsz = 'NULL';           
        $cmbopttype = 'NULL'; 
        $bv = 'NULL';                 
        $phoneno = $_REQUEST['orgcontact'];           
        $email = 'NULL';           
        $web = 'NULL';               
        $area= 'NULL';              
        $street = 'NULL';         
        $district = 'NULL';     
        $state = 'NULL';           
        $zip = 'NULL';               
        $country = 'NULL';       
        $descr = 'NULL';           
        $acc_mgr='NULL';      
            
        $hrid = $_SESSION["user"];
        $contactcode=0;$orcode=0;
        $getcd="SELECT concat(substring('".$nm."',1,3),'-',(max(id)+1)) orcd FROM `organization`";
            
        $resultcd = $conn->query($getcd);
        if ($resultcd->num_rows > 0) 
        {
            while($rowcd = $resultcd->fetch_assoc()) 
            {
              $orcode= $rowcd["orcd"]; 
            }
        }
          
        $qry="insert into organization(`name`,`orgcode`,  `contactno`, `industry`, `employeesize`, `email`, `website`, `area`, `street`, `district`, `state`, `zip`, `country`, `operationstatus`, `bsnsvalue`, `details`,`salesperson`) 
        values('".$nm."','".$orcode."','".$phoneno."',".$cmbindtype.",".$empsz.",'".$email."','".$web."',".$area.",'".$street."',".$district.",".$state.",'".$zip."',".$country.",".$cmbopttype.",".$bv.",'".$descr."',".$acc_mgr.")" ;
        $err="Organization created successfully";
        $conn->query($qry);
        $last_id = mysqli_insert_id($conn);
            
        //echo $qry;die;
        //$conttype = "1";
        //$contname = $_REQUEST['manager-name'];
        //$contemail = 'NULL';
        //$cphone = $_REQUEST['manager-contact'];
        //$cdesig = $_POST['cmbdsg'];
            
        $getcontact="SELECT concat(YEAR(CURDATE()),(max(`id`)+1)) contcd FROM `contact`";
        $resultcontact = $conn->query($getcontact);
        if ($resultcontact->num_rows > 0) 
        {
            while($rowcontact = $resultcontact->fetch_assoc()) 
            {
              $contactcode= $rowcontact["contcd"]; 
            }
        }
            
        $type="1";$cnm=$_REQUEST["managername"];$cemail='NULL';$contactphone=$_REQUEST['managercontact']; $desg='';
        if($type==''){$type='NULL';}  if($cemail==''){$cemail='NULL';} if($contactphone==''){$contactphone='NULL';} if($desg==''){$desg='NULL';} 
        $contact=substr($cnm,1,3).'-'.$contactcode;
        //if($cnm==''){$cnm='NULL';}
        if($cnm!=''){
            $qry_orgcont="insert into `orgaContact`(`organization`, `contact`, `conatcttype`, `name`, `email`, `phone`,`designation`) 
                                           values('".$orcode."','".$contact."',".$type.",'".$cnm."','".$cemail."','".$contactphone."',".$desg.")" ;
            //echo $qry_orgcont;die;
            if ($conn->query($qry_orgcont) == TRUE) { $err="Contact added successfully";  }
                         
                  $make_date=date('Y-m-d H:i:s');
                  $op_date =date('Y-m-d');
                  $lead_state=5;
                 // $status=1;
                  $cmbcontyp=1;
                         
        $qry_contact="insert into contact(`contactcode`,  `contacttype`, `name`, `organization`, `phone`, `email`, `lead_state`, `opendt`, `status`, `makeby`, `makedt`,`currbal`,`designation`) 
                                  values('".$contact."',".$cmbcontyp.",'".$cnm."','".$orcode."','".$contactphone."','".$cemail."',".$lead_state.",'".$op_date."','1',".$hrid.",'".$make_date."',0,".$desg.")" ;
                            //echo $qry_contact;die;
            if ($conn->query($qry_contact) == TRUE) { $err="Contact added successfully";  $last_nid = mysqli_insert_id($conn);}
        }
        
        $msg = "Organization added successfully";
        $ncmb = "cmbld";
            
        $response = array(
        		"msg" => $msg,
        		"success" => 1,
        		"value" => $name,
        		"id" =>  $last_id,
        		"cmbname" =>  $cmbname,
        		"nid" =>  $last_nid,
        		"nnm" =>  $cnm,
        		"ncmb" =>  $ncmb,
        		
        	);
        	
        echo json_encode($response);
        die;
            
    }else{
        $response = array(
        "msg" => "Some field is empty",
    	);
    	echo json_encode($response);
    	die;
    }
}

if($name){
	
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
		
		//Trans Type
		case 'cmbtype':
		$table = 'transtype';
		break;


}


	//	echo $table;
	//	exit();
if(checkExistingData($table,'name',$name)){
	
	$query = 'INSERT INTO '.$table.'(`name`)  VALUES("'.$name.'")';
	
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
				"value" => $name,
				"id" =>  $last_id,
				"cmbname" =>  $cmbname,
			);
			
				echo json_encode($response);
		
				
	}else{//if(checkExistingData('tbl_applied_companies','trading_code',$_REQUEST['trading_code'])){				
		
		
		$response = array(
			"msg" => $name." already exists",
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