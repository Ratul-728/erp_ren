<?php
require "../common/conn.php";
include_once('../rak_framework/fetch.php');
require_once("../rak_framework/edit.php");

session_start();

$user = $_SESSION["user"];
//print_r($_POST);die;


$type = $_POST["type"];

if($type == ''){
    $name = $_POST["newItem"];
    /*
    $orcode=0;
    $getcd="SELECT concat(substring('".$name."',1,3),'-',(max(id)+1)) orcd FROM `organization`";
    $resultcd = $conn->query($getcd);
    if ($resultcd->num_rows > 0) 
    {
        while($rowcd = $resultcd->fetch_assoc()){
                $orcode= $rowcd["orcd"]; 
        }
    }*/
	$orcode = getFormatedUniqueID('organization','id','CUS-',6,"0");
    $qry="insert into organization(`name`,`orgcode`) 
            values('".$name."','".$orcode."')" ;
    //echo $qry;die;        
    if ($conn->query($qry) == TRUE) {
        $last_id = $conn->insert_id;
        
        $response = array(
    
            "id" => $last_id,
        
            "name" => $name
        
        );
        
        echo json_encode($response);exit();
    
    }
}


if($type == 1){
    $nm = $_POST["data"][0]["value"];
    $cmbindtype = $_POST["data"][1]["value"];
    $empsz = $_POST["data"][2]["value"]; if($empsz == 0)$empsz = 0;
    $area = $_POST["data"][3]["value"];
    
    $contnm = $_POST["data"][4]["value"];
    $email = $_POST["data"][5]["value"];
    $phoneno = $_POST["data"][6]["value"];
    $note = $_POST["data"][7]["value"];
    
	/*
    $orcode=0;
    $getcd="SELECT concat(substring('".$nm."',1,3),'-',(max(id)+1)) orcd FROM `organization`";
    $resultcd = $conn->query($getcd);
    if ($resultcd->num_rows > 0) 
    {
        while($rowcd = $resultcd->fetch_assoc()){
                $orcode= $rowcd["orcd"]; 
        }
    }
	*/
    $orcode = getFormatedUniqueID('organization','id','CUS-',6,"0");
    
    $qry="insert into organization(`name`,`orgcode`,  `contactno`, `industry`, `employeesize`, `email`, `street`, `note`) 
                            values('".$nm."','".$orcode."','".$phoneno."',".$cmbindtype.",".$empsz.",'".$email."','".$area."','".$note."')" ;
    //echo $qry;die;
        
    if ($conn->query($qry) == TRUE) {
        $last_id = $conn->insert_id;
        
        //Insert Contact
        $getcontact="SELECT concat(YEAR(CURDATE()),(max(`id`)+1)) contcd FROM `contact`";
        $resultcontact = $conn->query($getcontact);
        if ($resultcontact->num_rows > 0) 
        {
            while($rowcontact = $resultcontact->fetch_assoc()) 
            {
              $contactcode= $rowcontact["contcd"]; 
            }
        }
        $contact=substr($contnm,1,3).'-'.$contactcode;
        
        $qry_orgcont="insert into `orgaContact`(`organization`, `contact`,  `name`, `email`, `phone`) 
                                        values('".$orcode."','".$contact."','".$contnm."','".$email."','".$phoneno."')" ;
                                         
        $conn->query($qry_orgcont);
        
        $qrycontact = "INSERT INTO `contact`( `name`, `organization`,`phone`, `email`, `contactcode`,  `area`,`makeby`, `makedt`) 
                                    VALUES ('".$contnm."','".$orcode."','".$phoneno."','".$email."','".$contact."','".$area."','".$user."',sysdate())";
        
        $conn->query($qrycontact);
        
        
        $response = array(
    
            "id" => $last_id,
            
            "contact" => $phoneno,
        
            "name" => $nm
        
        );
        
        echo json_encode($response);exit();
    
    }
    
}else if ($type == 0){
    //Contact Information
    $contnm = $_POST["data"][0]["value"];
    $contemail = $_POST["data"][1]["value"];
    $contphone = $_POST["data"][2]["value"];
    $contdob = $_POST["data"][3]["value"];
	$gender = $_POST["data"][4]["value"];
    $area = $_POST["data"][5]["value"];
    $district = $_POST["data"][6]["value"];
    $zip = $_POST["data"][7]["value"];
    $country = $_POST["data"][8]["value"];
    
    
    
    //$street = $_POST["data"][7]["value"];
    
    
    /*
    $orcode=0;
    $getcd="SELECT concat(substring('".$contnm."',1,3),'-',(max(id)+1)) orcd FROM `organization`";
    $resultcd = $conn->query($getcd);
    if ($resultcd->num_rows > 0) 
    {
        while($rowcd = $resultcd->fetch_assoc()){
                $orcode= $rowcd["orcd"]; 
        }
    }
	*/
	$orcode = getFormatedUniqueID('organization','id','CUS-',6,"0");
    $qry="insert into organization(`name`,`orgcode`,  `contactno`, `email`,  `street`, `district`,  `zip`, `country`, `type`) 
                            values('".$contnm."','".$orcode."','".$contphone."','".$contemail."','".$area."',".$district.",'".$zip."',".$country.", '2')" ;
        
    //echo $qry;die;
    if ($conn->query($qry) == TRUE) {
        $last_id = $conn->insert_id;
        
        //Insert Contact
        $getcontact="SELECT concat(YEAR(CURDATE()),(max(`id`)+1)) contcd FROM `contact`";
        $resultcontact = $conn->query($getcontact);
        if ($resultcontact->num_rows > 0) 
        {
            while($rowcontact = $resultcontact->fetch_assoc()) 
            {
              $contactcode= $rowcontact["contcd"]; 
            }
        }
        $contact=substr($contnm,1,3).'-'.$contactcode;
        
        $qry_orgcont="insert into `orgaContact`(`organization`, `contact`,  `name`, `email`, `phone`) 
                                        values('".$orcode."','".$contact."','".$contnm."','".$contemail."','".$contphone."')" ;
                                         
        $conn->query($qry_orgcont);
        
        $qrycontact = "INSERT INTO `contact`( `name`, `organization`, `dob`,  `phone`, `email`,`gender`, `contactcode`,  `area`,  `district`,  `zip`, `country`,`makeby`, `makedt`) 
                                    VALUES ('".$contnm."','".$orcode."',STR_TO_DATE('".$contdob."', '%d/%m/%Y'),'".$contphone."','".$contemail."','".$gender."','".$contact."','".$area."','".$district."','".$zip."','".$country."','".$user."',sysdate())";
        
		//echo $qrycontact ;die;
		
        $conn->query($qrycontact);
        
        $response = array(
    
            "id" => $last_id,
            
            "contact" => $contphone,
        
            "name" => $contnm,
        
        );
        
        echo json_encode($response);exit();
    
    }
}
        



?>