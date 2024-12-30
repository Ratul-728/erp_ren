<?php
require "conn.php";
include_once('../rak_framework/fetch.php');
require_once("../rak_framework/edit.php");

//ini_set('display_errors', 1);


if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/organization.php?res=01&msg='New Entry'&id=''&mod=2");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
       
        $nm = str_replace("'","''",$_POST['cnnm']);
        //$cntper = $_POST['cntper'];
        $cmbindtype = $_POST['cmbindtype']; //if($cmbindtype==''){$cmbindtype='NULL';}
        $empsz = $_POST['empsz'];           //if($empsz==''){$empsz=0;}
        $cmbopttype = $_POST['cmbopttype']; //if($cmbopttype==''){$cmbopttype=0;}
        $bv = $_POST['bv'];                 //if($bv==''){$bv='NULL';}
        $phoneno = $_POST['phone'];           //if($phone==''){$phone='NULL';}
        $email = $_POST['email'];           //if($email==''){$email='NULL';}
        $web = $_POST['web'];               //if($web==''){$web='NULL';}
        $area= $_POST['area'];              //if($area==''){$area='';}
        $street = $_POST['street'];         //if($street==''){$street='NULL';}
        $district = $_POST['district'];     //if($district==''){$district=0;}
        $state = $_POST['state'];           //if($state==''){$state=0;}
        $zip = $_POST['zip'];               //if($zip==''){$zip='NULL';}
        $country = $_POST['country'];       //if($country==''){$country=0;}
        $descr = $_POST['descr'];           //if($descr==''){$descr='NULL';}
        $acc_mgr=$_REQUEST['cmbhrmgr'];      //if($acc_mgr==''){$acc_mgr=0;}
        $vendor = $_REQUEST["vendor"];       if($vendor == '') $vendor = 0;
        $note =$_REQUEST['note'];
         $hrid = $_POST['usrid'];
       // $billcntper = $_POST['billcntper']; if($billcntper==''){$billcntper='NULL';}
       // $techcntper = $_POST['techcntper']; if($techcntper==''){$techcntper='NULL';}
        
        $contactcode=0;
        $orcode=0;

        $orcode = getFormatedUniqueID('organization','id','CUS-',6,"0");
        
        $qry="insert into organization(`name`,`orgcode`,  `contactno`, `industry`, `employeesize`, `email`, `website`, `area`, `street`, `district`, `state`, `zip`, `country`, `operationstatus`, `bsnsvalue`, `details`,`salesperson`,note,vendor,makedt) 
        values('".$nm."','".$orcode."','".$phoneno."','".$cmbindtype."','".$empsz."','".$email."','".$web."','".$area."','".$street."','".$district."','".$state."','".$zip."','".$country."','".$cmbopttype."','".$bv."','".$descr."','".$acc_mgr."','".$note."','".$vendor."',SYSDATE())" ;
        $err="Organization created successfully";
        //echo $qry;die;
        $conttype = $_POST['conttype'];
        $contname = $_POST['contname'];
        $contemail = $_POST['contemail'];
        $cphone = $_POST['contphone'];
        $cdesig = $_POST['cmbdsg'];
        $cddept = $_POST['cmbdept'];
        
        //print_r($contname);die;
        
        $getcontact="SELECT concat(YEAR(CURDATE()),(max(`id`)+1)) contcd FROM `contact`";
        $resultcontact = $conn->query($getcontact);
        if ($resultcontact->num_rows > 0) 
        {
            while($rowcontact = $resultcontact->fetch_assoc()) 
            {
              $contactcode= $rowcontact["contcd"]; 
            }
        }
        
        if (is_array($contname))
            {
                for ($i=0;$i<count($contname);$i++)
                    {
                        $type=$conttype[$i];$cnm=$contname[$i];$cemail=$contemail[$i];$contactphone=$cphone[$i]; $desg=$cdesig[$i]; $dept = $cddept[$i];
                        //if($type==''){$type='NULL';}  if($cemail==''){$cemail='NULL';} if($contactphone==''){$contactphone='NULL';} if($desg==''){$desg='NULL';} if($dept==''){$dept='NULL';} 
                        $contact=substr($cnm,1,3).'-'.$contactcode;
                        //if($cnm==''){$cnm='NULL';}
                        if($cnm!=''){
                         $qry_orgcont="insert into `orgaContact`(`organization`, `contact`, `conatcttype`, `name`, `email`, `phone`,`designation`,`department`) 
                                        values('".$orcode."','".$contact."','".$type."','".$cnm."','".$cemail."','".$contactphone."','".$desg."', '".$dept."')" ;
                         //echo $qry_orgcont;die;
                         if ($conn->query($qry_orgcont) == TRUE) { $err="Contact added successfully";  }
                         
                            $make_date=date('Y-m-d H:i:s');
                            $op_date =date('Y-m-d');
                            $lead_state=5;
                           // $status=1;
                            $cmbcontyp=1;
                         
                         $qry_contact="insert into contact(`contactcode`,  `contacttype`, `name`, `organization`, `phone`, `email`, `lead_state`, `opendt`, `status`, `makeby`, `makedt`,`currbal`,`designation`,`department`) 
                            values('".$contact."','".$cmbcontyp."','".$cnm."','".$orcode."','".$contactphone."','".$cemail."','".$lead_state."','".$op_date."','1','".$hrid."','".$make_date."',0,'".$desg."','".$dept."')" ;
                            //echo $qry_contact;die;
                         if ($conn->query($qry_contact) == TRUE) { $err="Contact added successfully";  }
                        }
                            
                    }
                
            }
         
        
        
  //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
        
        $aid= $_REQUEST['orid'];
        //$nm = $_POST['cnnm'];
        $nm = str_replace("'","''",$_POST['cnnm']);
        $orcode = $_POST['orcd'];
        $cmbindtype = $_POST['cmbindtype']; //if($cmbindtype==''){$cmbindtype='NULL';}
        $empsz = $_POST['empsz'];           //if($empsz==''){$empsz='NULL';}
        $cmbopttype = $_POST['cmbopttype']; //if($cmbopttype==''){$cmbopttype='NULL';}
        $bv = $_POST['bv'];                 //if($bv==''){$bv='NULL';}
        $phoneno = $_POST['phone'];           //if($phone==''){$phone='NULL';}
        $email = $_POST['email'];           //if($email==''){$email='NULL';}
        $web = $_POST['web'];               //if($web==''){$web='NULL';}
        $area= $_POST['area'];              //if($area==''){$area='NULL';}
        $street = $_POST['street'];         //if($street==''){$street='NULL';}
        $district = $_POST['district'];     //if($district==''){$district='NULL';}
        $state = $_POST['state'];           //if($state==''){$state='NULL';}
        $zip = $_POST['zip'];               //if($zip==''){$zip='NULL';}
        $country = $_POST['country'];       //if($country==''){$country='NULL';}
        $descr = $_POST['descr'];           //if($descr==''){$descr='NULL';}
        $acc_mgr=$_REQUEST['cmbhrmgr'];      //if($acc_mgr==''){$acc_mgr='NULL';}
        $hrid = $_POST['usrid'];
        $note = $_POST['note'];
        $vendor = $_POST["vendor"];       if($vendor == '') $vendor = 0;
        
        $qry="update organization set `note`='".$note."',`vendor`='".$vendor."',`name`='".$nm."', `contactno`='".$phoneno."',`industry`='".$cmbindtype."',`employeesize`='".$empsz."',`email`='".$email."',`website`='".$web."'
        ,`area`='".$area."',`street`='".$street."',`district`='".$district."',`state`='".$state."',`zip`='".$zip."',`country`='".$country."',`operationstatus`='".$cmbopttype."',`bsnsvalue`='".$bv."',`details`='".$descr."',`salesperson`='".$acc_mgr."' where `id`=".$aid."";
        $err="Organization updated successfully";
        
       //echo $qry;die;
        
        $orgconid = $_POST['orgconid'];
        $contid = $_POST['contactid'];
        $conttype = $_POST['conttype'];
        $contname = $_POST['contname'];
        $contemail = $_POST['contemail'];
        $cphone = $_POST['contphone'];
         $cdesig = $_POST['cmbdsg'];
         $cddept = $_POST['cmbdept'];
        //echo $contid[1];die;
         $delqry="delete from orgaContact where organization='".$orcode."'";
        if ($conn->query($delqry) == TRUE) { $err="Contact deleted successfully";  }
        
        if (is_array($contname))
            {
                for ($i=0;$i<count($contname);$i++)
                    {
                        $type=$conttype[$i];$cnm=$contname[$i];$cemail=$contemail[$i];$contactphone=$cphone[$i]; $orgcontactid=$orgconid[$i];$contactid=$contid[$i]; $desg=$cdesig[$i];$dept = $cddept[$i];
                        //if($type==''){$type='NULL';}  if($cemail==''){$cemail='NULL';} if($contactphone==''){$contactphone='NULL';} if($desg==''){$desg='NULL';} if($dept==''){$dept='NULL';} 
                        //echo $contactid;die;
                        //if($cnm==''){$cnm='NULL';}
                       if($cnm!='')
                       {
                        if($contactid=='')
                        {
                            $getcontact="SELECT concat(YEAR(CURDATE()),(max(`id`)+1)) contcd FROM `contact`";
                            $resultcontact = $conn->query($getcontact);
                            if ($resultcontact->num_rows > 0) 
                            {
                                while($rowcontact = $resultcontact->fetch_assoc()) 
                                {
                                  $contactcode= $rowcontact["contcd"]; 
                                }
                            }
                            
                            
                            $make_date=date('Y-m-d H:i:s');
                            $op_date =date('Y-m-d');
                            $lead_state=5;
                           // $status=1;
                            $cmbcontyp=1;
                             $contact=substr($cnm,1,3).'-'.$contactcode;
                         
                            $qry_contact="insert into contact(`contactcode`,  `contacttype`, `name`, `organization`, `phone`, `email`, `lead_state`, `opendt`, `status`, `makeby`, `makedt`,`currbal`,`designation`,`department`) 
                             values('".$contact."','".$cmbcontyp."','".$cnm."','".$orcode."','".$contactphone."','".$cemail."','".$lead_state."','".$op_date."','1','".$hrid."','".$make_date."',0,'".$desg."','".$dept."')" ;
                            //echo $qry_contact;die;
                             if ($conn->query($qry_contact) == TRUE) { $err="Contact added successfully";  }
                             
                              $qry_orgcont="insert into `orgaContact`(`organization`, `contact`, `conatcttype`, `name`, `email`, `phone`,`designation`,`department`) 
                                            values('".$orcode."','".$contact."','".$type."','".$cnm."','".$cemail."','".$contactphone."','".$desg."','".$dept."')" ;
                             //echo $itqry;die;
                             if ($conn->query($qry_orgcont) == TRUE) { $err="Contact added successfully";  }
                            
                        }
                        else
                        {
                            $qry_contact="update contact set   `name`='".$cnm."',  `phone`='".$contactphone."', `email`='".$cemail."',`designation`='".$desg."',`department`= '".$dept."' where contactcode='".$contactid."'"; 
                             if ($conn->query($qry_contact) == TRUE) { $err="Contact updated successfully";  }
                             
                              $qry_orgcont="insert into `orgaContact`(`organization`, `contact`, `conatcttype`, `name`, `email`, `phone`,`designation`,`department`) 
                                            values('".$orcode."','".$contactid."','".$type."','".$cnm."','".$cemail."','".$contactphone."','".$desg."','".$dept."')" ;
                             //echo $itqry;die;
                             if ($conn->query($qry_orgcont) == TRUE) { $err="Contact added successfully";  }
                        
                        }
                         
                       }     
                    }
            }
         
        
        
       // echo $qry;die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/organizationList.php?res=1&msg=".$err."&id=".$aid."&mod=2");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/organizationList.php?res=2&msg=".$err."&id=''&mod=2");
    }
    
    $conn->close();
}
?>