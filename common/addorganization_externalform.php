<?php
require "conn.php";

ini_set("display_errors",0);


    if ( isset( $_POST['add'] ) ) {
		
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
       
        $nm = str_replace("'","''",$_POST['cnnm']);
        //$cntper = $_POST['cntper'];
        $cmbindtype = $_POST['cmbindtype']; if($cmbindtype==''){$cmbindtype='NULL';}
        $empsz = $_POST['empsz'];           if($empsz==''){$empsz='NULL';}
        $cmbopttype = $_POST['cmbopttype']; if($cmbopttype==''){$cmbopttype='NULL';}
        $bv = $_POST['bv'];                 if($bv==''){$bv='NULL';}
        $phoneno = $_POST['phone'];           if($phone==''){$phone='NULL';}
        $email = $_POST['email'];           if($email==''){$email='NULL';}
        $web = $_POST['web'];               if($web==''){$web='NULL';}
        $area= $_POST['area'];              if($area==''){$area='NULL';}
        $street = $_POST['street'];         if($street==''){$street='NULL';}
        $district = $_POST['district'];     if($district==''){$district='NULL';}
        $state = $_POST['state'];           if($state==''){$state='NULL';}
        $zip = $_POST['zip'];               if($zip==''){$zip='NULL';}
        $country = $_POST['country'];       if($country==''){$country='NULL';}
        $descr = $_POST['descr'];           if($descr==''){$descr='NULL';}
        $acc_mgr=$_REQUEST['cmbhrmgr'];      if($acc_mgr==''){$acc_mgr='NULL';}
        
         $hrid = 1;

		
        
        $contactcode=0;$orcode=0;
        $getcd="SELECT concat(substring('".$nm."',1,3),'-',(max(id)+1)) orcd FROM `organization`";
        
      // echo $getcd;die;
        $resultcd = $conn->query($getcd);
        if ($resultcd->num_rows > 0) 
        {
            while($rowcd = $resultcd->fetch_assoc()) 
            {
              $orcode= $rowcd["orcd"]; 
            }
        }
		
		$conttype = $_POST['conttype'];
        $contname = $_POST['contname'][0];
        $contemail = $_POST['contemail'][0];
        $cphone = $_POST['contphone'][0];
        $cdesig = 1;
        $cddept = 23;
      
	   
		
		
        $qry="insert into organization(`name`,`orgcode`,`contactperson`,  `contactno`, `industry`, `employeesize`, `email`, `website`, `area`, `street`, `district`, `state`, `zip`, `country`, `operationstatus`, `bsnsvalue`, `details`,`salesperson`) 
        values('".$nm."','".$orcode."','".$contname."','".$cphone."',".$cmbindtype.",".$empsz.",'".$contemail."','".$web."',".$area.",'".$street."',".$district.",".$state.",'".$zip."',".$country.",".$cmbopttype.",".$bv.",'".$descr."','".$contname."')" ;
        $err="Thank you for your interest. Your requeset has been received. <br>A team member will contact you shortly";
        //echo $qry;die;
        
		
		
        
        
        
        $getcontact="SELECT concat(YEAR(CURDATE()),(max(`id`)+1)) contcd FROM `contact`";
		
        $resultcontact = $conn->query($getcontact);
        if ($resultcontact->num_rows > 0) 
        {
            while($rowcontact = $resultcontact->fetch_assoc()) 
            {
              $contactcode= $rowcontact["contcd"]; 
			  
            }
        }


                   $contact=substr($contname,1,3).'-'.$contactcode;
                        //if($cnm==''){$cnm='NULL';}
                        
                         $qry_orgcont="insert into `orgaContact`(`organization`, `contact`, `conatcttype`, `name`, `email`, `phone`,`designation`,`department`) 
                                        values('".$orcode."','".$contact."',1,'".$contname."','".$contemail."','".$cphone."',".$cdesig.", ".$cddept.")" ;
                         //echo $qry_orgcont;die;
                         if ($conn->query($qry_orgcont) == TRUE) { }//$err="Contact added successfully";  }
                         
                            $make_date=date('Y-m-d H:i:s');
                            $op_date =date('Y-m-d');
                            $lead_state=5;
                           // $status=1;
                            $cmbcontyp=1;
                         
                         $qry_contact="insert into contact(`contactcode`,  `contacttype`, `name`, `organization`, `phone`, `email`, `lead_state`, `opendt`, `status`, `makeby`, `makedt`,`currbal`,`designation`,`department`) 
                            values('".$contact."',1,'".$contname."','".$orcode."','".$cphone."','".$contemail."',".$lead_state.",'".$op_date."','1',".$hrid.",'".$make_date."',0,".$cdesig.",".$cddept.")" ;
                            //echo $qry_contact;die;
                         if ($conn->query($qry_contact) == TRUE) { }//$err="Contact added successfully";  }
                        
                            
                    
                
         
         
        
        
  //echo $qry; die;
    }
    
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/organization_externalform.php?msg=".urlencode($err));
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/organization_externalform.php?&msg=".urlencode($err));
    }
    
    $conn->close();

?>