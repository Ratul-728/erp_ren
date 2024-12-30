<?php

session_start();
//ini_set('display_errors',0);
include_once('conn.php');
//include_once('email_config.php');
include_once('../rak_framework/connection.php');
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');

//include_once('../email_messages/email_user_message.php');
//echo 'hi';die;
//require_once('phpmailer/PHPMailerAutoload.php');
//include_once('insert_gl.php');

//echo 'hi';die;
require_once('insert_gl.php');

//echo "<pre>";print_r($_REQUEST);echo "</pre>";die;
//echo 'hiu';die;
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/collection.php?res=01&msg='New Entry'&id=''&mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) 
    {
     
      $trdt= $_REQUEST['trdt']; 
      
      $cmbservtp = $_POST['cmbservtp'];
      $cmbmode = $_POST['cmbmode'];     //if($cmbmode==''){$cmbmode=NULL;}
      $ref_quot = $_POST['ref'];             //if($ref==''){$ref=NULL;}
      $ref_service = $_POST['ref_service'];             //if($ref==''){$ref=NULL;}
      $ref_maintanance = $_POST['ref_maintanance'];             //if($ref==''){$ref=NULL;}
      $ref_other = $_POST['ref_other'];             //if($ref==''){$ref=NULL;}
     // $chqdt = $_POST['chqdt'];         //if($chqdt==''){$chqdt=NULL;}
      $cmbsupnm = $_POST['org_id'];   //if($cmbsupnm==''){$cmbsupnm=NULL;}
      $amt = $_POST['amt'];             if($amt==''){$amt='0';}
      //$cmbinv = $_POST['cmbinv'];       if($cmbinv==''){$cmbinv=NULL;}
      $descr = $_POST['descr'];         //if($descr==''){$descr=NULL;}
      $curr = 1;//$_POST['curr'];         //if($curr==''){$curr=NULL;}
      $glac = $_POST["glac"];
      $crglac = $_POST["crglac"];
      $ref='';
      
      $hrid = $_POST['usrid']; 
      
        $cmbdrcr='C';
	    $checkno =  ($data["checkno"]) ? $data["checkno"]:""; 
	    $transaction_number = ($data["transaction_number"]) ? $data["transaction_number"]:"";
	    
		$checkdate = ($data['chqdt'])?$data['chqdt']:"0000-00-00";
		$bank = ($data['bank'])?$data['bank']:"";
		$depbank = ($data['depbank'])?$data['depbank']:"";
		
		if($cmbservtp=='q'){$ref=$ref_quot;} else if($cmbservtp=='s'){$ref=$ref_service;}else if($cmbservtp=='M'){$ref=$ref_maintanance;}else {$ref=$ref_other;}
        //$glac = fetchByID('glmapping','buisness',3,'mappedgl');
       //echo $cmbsupnm;die;
      
      $chqclearst=0;$st=0; //$hrid= '1';
      
      /*$qrycoll="insert into collection(  `treat_from`, `trdt`,`transmode`, `transref`,`checkno`,`chequedt`, `bank`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`,`document`, `transaction_number`) 
        values(1,'".date("Y-m-d H:i:s")."','".$cmbmode."','".$ref."','".$checkno."' ,'".$checkdate."' ,'".$bank."' ,'".$cmbsupnm."','".$descr."',".$amt.",".$chqclearst.",".$st.",'".$curr."','".$hrid."','".date("Y-m-d H:i:s")."' ,'".$glac."','".$destination."', '".$transaction_number."')" ;
        $err="A receive created successfully";
        */
       
        $qry="insert into collection(  `trdt`,`transmode`, `transref`,`checkno`, `chequedt`, `bank`,`depositbank`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`,`crglac`,`document`) 
        values(STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'".$cmbmode."','".$ref."','".$checkno."','".$chqdt."','".$bank."','".$depbank."','".$cmbsupnm."','".$descr."',".$amt.",".$chqclearst.",".$st.",'".$curr."','".$hrid."','".date('Y-m-d H:i:s')."' ,'".$glac."','".$crglac."','".$destination."')" ;
        $err="A receive created successfully"; 
          // echo $qry;die;
         $cusqry="update contact set currbal=currbal+".$amt." where id=".$cmbsupnm." and status=1";
            //echo $itqry;die;
          if ($conn->query($cusqry) == TRUE) { $err="contatct updared successfully";   }
        
        
         $orgbalqry="update organization set balance=balance+".$amt." where id=".$cmbsupnm;
            //echo $itqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
          
          $orgupdbalqry="select balance from organization where id=".$cmbsupnm;
           $resultbl = $conn->query($orgupdbalqry);
            if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
          
          $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values(STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'$cmbsupnm','$cmbmode','C','$ref',$amt,$curbal,'Fund Receive',$hrid,'".date('Y-m-d H:i:s')."')";
            //echo $itqry;die;
          if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
        
        
         $note="Customer Payment: Payment Amount ".$amt." received ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$cmbsupnm."',6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amt.",'".$hrid."','".date('Y-m-d H:i:s')."')" ;
         if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  } 
         
         
        /* Accounnting */
		
		  /* Accounnting */
                 
        $cashgl = fetchByID('glmapping','buisness',3,'mappedgl');	
        $pblbankgl = fetchByID('glmapping','buisness',4,'mappedgl');//pbl//22
        $mtblbankgl = fetchByID('glmapping','buisness',7,'mappedgl');//mtbl//15
        $cblbankgl = fetchByID('glmapping','buisness',8,'mappedgl');//mtbl//14
        $advancefromcustomer = fetchByID('glmapping','buisness',6,'mappedgl');
        $bkashcashgl = fetchByID('glmapping','buisness',23,'mappedgl');
        $nagadgl = fetchByID('glmapping','buisness',24,'mappedgl');
        $chequegl = fetchByID('glmapping','buisness',25,'mappedgl');
        $payordergl = fetchByID('glmapping','buisness',26,'mappedgl');
        $roketgl = fetchByID('glmapping','buisness',27,'mappedgl');
        $cardgl = fetchByID('glmapping','buisness',28,'mappedgl');
        $reservcashgl = fetchByID('glmapping','buisness',14,'mappedgl');
         
        $gl=$cashgl;
       // $cmbmode = $data['cmbmode'];
        if($cmbmode=='1')
        {
            $gl=$reservcashgl;
        }
        else if($cmbmode=='2')
        {
            $gl=$chequegl;
        }
        else if($cmbmode=='3')
        {
        $bank = ($data['bank']);
        if($bank=='14'){$gl=$cblbankgl;} else if($bank=='15'){$gl=$mtblbankgl;} else if($bank=='22'){$gl=$pblbankgl;}else{$gl=$cashgl;}
        }
        else if($cmbmode=='4')
        {
            $gl=$payordergl;
        }
        else if($cmbmode=='5')
        {
            $gl=$cardgl;
        }
         else if($cmbmode=='10') //AIT challan
        {
            $gl='102040106';
        }
        else if($cmbmode=='11') //VAT challan
        {
            $gl='101060100';
        }
        else
        {
        $gl=$cashgl;
        }
        
  //      if($whichTab==1) 
//    	{
	        $descr="Voucher againts sale -".$ref; 
            $refno=$ref;
            $vouchdt= date("d/m/Y");
               
            $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr,
            	'entryby' => $hrid,
                );
    	    
    	    $gldetailArr[] = array(
    		'sl'	 =>	1,
            'glac'	 =>	$glac,//$gl,	//glno
    		'dr_cr' 	=>	'D',
    		'amount' 	=>	$amt,
    		'remarks' 	=>	'Cash collection for payment anignst invoice',
    		'entryby' 	=>	$hrid,
    		'entrydate' 	=>	$vouchdt
            );
            
            
        	$gldetailArr[] = array(
        		'sl'	 =>	2,
                'glac'	 =>$crglac,//$advancefromcustomer,	//glno
        		'dr_cr' 	=>	'C',
        		'amount' 	=>	$amt,
        		'remarks' 	=>	'Cash collection for payment anignst invoice',
        		'entryby' 	=>	$hrid,
        		'entrydate' 	=>	$vouchdt
            );
            
            insertGlfin($glmstArr,$gldetailArr);
        /* Accounting*/ 
		
		
	/*	
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id=6 ";// Recevable from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
         
         //call gl function;
		/*
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'".$cmbsupnm."-".$cmbinv."','".$descr."','".$hrid."','".date('Y-m-d H:i:s')."')";
        if($glac==""){$glac='101010202';}                
                        
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',1,'".$glac."','D',".$amt.",'Fund receive from Client','".$hrid."','".date('Y-m-d H:i:s')."')";
               //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',2,'".$glno."','C',".$amt.",'Fund receive from Client','".$hrid."','".date('Y-m-d H:i:s')."')";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
        */
		
/*		//$glmstArr = array(
		//`transdt`  => $trdt,
        //`refno` => $cmbsupnm,
        //`remarks` => $descr,
        //`entryby` => $hrid
	    //);
	    $cashgl = fetchByID('glmapping','buisness',3,'mappedgl');
	    $advancefromcustomer = fetchByID('glmapping','buisness',6,'mappedgl');
	    $glmstArr = array(
	'transdt' => $trdt,
	'refno' => $cmbsupnm,
	'remarks' => $descr,
	'entryby' => $hrid,
);
	
	
	$gldetailArr[] = array(
		'sl'	 =>	1,
        'glac'	 =>	$cashgl,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$amt,
		'remarks' 	=>	'Cash receive from Client',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>	formatDateReverse($trdt)
);


	$gldetailArr[] = array(
		'sl'	 =>	2,
        'glac'	 =>	$advancefromcustomer,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$amt,
		'remarks' 	=>	'Cash receive from Client',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>	formatDateReverse($trdt)
);
	*/
	/*
		$gldetailArr[] =   array(
                        		`sl`  => 1,
                                `glac` => '101010202',
                                `dr_cr` => 'D',
                                `amount` => $amt,
                                 `remarks` => 'Cash receive from Client'
                        	    );
        $gldetailArr[] =  array(
                        		`sl`  => 2,
                                `glac` => $glno,
                                `dr_cr` => 'C',
                                `amount` => $amt,
                                 `remarks` => 'Fund receive from Client'
                        	    )   ;            	    
         */               	    
	   
	//	insertGl($glmstArr,$gldetailArr);
		
		/*
        //Mail to Organization email 
                $qrymailinfo = "SELECT `name`,`email` FROM `organization` WHERE id = ".$cmbsupnm;
                $resultmailinfo = $conn->query($qrymailinfo);
                while ($rowmailinfo = $resultmailinfo->fetch_assoc()) {
                    $orgnm =$rowmailinfo["name"];
                    $orgmail = $rowmailinfo["email"];
                }
                
                $name_to = $orgnm;
        		$email_to = $orgmail;
        		$message = "Dear $orgnm, <br><br>
        		
        		            Payment recieved  Successfully.<br>
        		            Payment Amount ".$amt." received<br><br>
        		            ";
        		$subject = 'Payment Recieved';
        		sendBitFlowMail($name_to,$email_to, $subject,$message);
                
                //Mail to Admin email
                $qrymailinfo = "SELECT `companynm`,`email` FROM `sitesettings` WHERE id = 1";
                $resultmailinfo = $conn->query($qrymailinfo);
                while ($rowmailinfo = $resultmailinfo->fetch_assoc()) {
                    $adminnm =$rowmailinfo["companynm"];
                    $adminmail = $rowmailinfo["email"];
                }
                
                $name_to = $adminnm; 
        		$email_to = $adminmail;
        		$message = "Dear $adminnm, <br><br>
        		
        		            One customer payment recieved Successfully.<br>
        		            $note.<br><br>
        		            ";
        		$subject = 'Payment Recieved';
        		sendBitFlowMail($name_to,$email_to, $subject,$message);
        */
               /* Accounnting */ 
     //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
       $fcid= $_REQUEST['fcid'];
      $trdt= $_REQUEST['trdt']; 
      $cmbmode = $_POST['cmbmode'];     //if($cmbmode==''){$cmbmode=NULL;} 
      $ref = $_POST['ref'];             //if($ref==''){$ref=NULL;}
      $chqdt = $_POST['chqdt'];         //if($chqdt==''){$chqdt=NULL;}
      $cmbsupnm = $_POST['org_id'];   //if($cmbsupnm==''){$cmbsupnm=NULL;}
      $amt = $_POST['amt'];             //if($amt==''){$amt=NULL;}
     // $cmbcc = $_POST['cmbcc'];
       //$cmbinv = $_POST['cmbinv'];      if($cmbinv==''){$cmbinv=NULL;}
      $descr = $_POST['descr'];         //if($descr==''){$descr=NULL;}
      $glac = $_POST["glac"];
      
      $hrid = $_POST['usrid']; 
      
      $getO="SELECT customerOrg,amount,glac FROM collection where id=".$fcid."";// Existing Aount
         $resultO = $conn->query($getO);
            if ($resultO->num_rows > 0) {while ($rowO = $resultO->fetch_assoc()) { $Oglno = $rowO["glac"];$Oamount = $rowO["amount"];$OcustomerOrg = $rowO["customerOrg"];}}
         
      //echo $getO;die;
      
        //$qry="update collection set `trdt`=STR_TO_DATE('".$trdt."', '%d/%m/%Y'),`transmode`='".$cmbmode."',`transref`='".$ref."',`chequedt`=STR_TO_DATE('".$chqdt."', '%d/%m/%Y'),`customerOrg`='".$cmbsupnm."',`naration`='".$descr."',`amount`=".$amt.",`glac`='".$glac."' where `id`=".$fcid."";
        $qry="update collection set `transmode`='".$cmbmode."' where `id`=".$fcid."";
        
        $err="Received Voucher updated successfully"; $thisid = $fcid;  
        //echo $qry; die;
         $note="Customer Payment: Payment Amount ".$amt." has been updated ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$cmbsupnm."',6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amt.",'".$hrid."','".date('Y-m-d H:i:s')."')" ;
         if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  }
         
         $orgbalqry="update organization set balance=balance+$amt-$Oamount where id=".$cmbsupnm;
            //echo $itqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
          
           $orgupdbalqry="select balance from organization where id=".$cmbsupnm;
           $resultbl = $conn->query($orgupdbalqry);
            if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
          
          
         
         $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values(STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'$cmbsupnm','$cmbmode','D','$ref','$Oamount','$curbal','Receive reverse of id='.$fcid,$hrid,'".date('Y-m-d H:i:s')."')
          ,(STR_TO_DATE('".$trdt."', '%d/%m/%Y'),$cmbsupnm,$cmbmode,'C',$ref,$amt,'Receive Updated',$hrid,'".date('Y-m-d H:i:s')."')";
            //echo $itqry;die;
          if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
         
         
         /* Accounnting */
         //$vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id=8 ";// Recevable from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
         /*
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'".$cmbsupnm."-".$cmbinv."','Collection Update Entry','".$hrid."','".date('Y-m-d H:i:s')."')";
        if($glac==""){$glac='101010202';}                
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',1,'".$glac."','C','".$Oamount."','Reversal for Collection update',".$hrid.",'".date('Y-m-d H:i:s')."')";
               //echo  $glqry1;die;             
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',2,'".$Oglno."','D','".$Oamount."','Reversal for collection update','".$hrid."','".date('Y-m-d H:i:s')."')";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry3="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',3,'".$glac."','D',".$amt.",'Collection Update',".$hrid.",'".date('Y-m-d H:i:s')."')";
                             
            if ($conn->query($glqry3) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry4="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',4,'".$glno."','C',".$amt.",'Collection Update',".$hrid.",'".date('Y-m-d H:i:s')."')";
                             
            if ($conn->query($glqry4) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
         
         
         */
        	
	     $glmstArr = array(
	'transdt' => $trdt,
	'refno' => $cmbsupnm,
	'remarks' => $descr,
	'entryby' => $hrid,
);
	 
	 	$gldetailArr[] = array(
		'sl'	 =>	1,
        'glac'	 =>	$glac,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$Oamount,
		'remarks' 	=>	'Reversal for collection update',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>	formatDateReverse($trdt)
);
	$gldetailArr[] = array(
		'sl'	 =>	2,
        'glac'	 =>	$Oglno,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$Oamount,
		'remarks' 	=>	'Reversal for collection update',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>	formatDateReverse($trdt)
);
	    
$gldetailArr[] = array(
		'sl'	 =>	3,
        'glac'	 =>	$glac,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$amt,
		'remarks' 	=>	'Collection Update',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>	formatDateReverse($trdt)
);
$gldetailArr[] = array(
		'sl'	 =>	4,
        'glac'	 =>	$glno,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$amt,
		'remarks' 	=>	'Collection Update',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>	formatDateReverse($trdt)
); 
                    	    
         insertGl($glmstArr,$gldetailArr);
               /* Accounnting */ 
         
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
		  		if(isset($_POST['add'])){
					$thisid =  $conn->insert_id;	
				}
				
                header("Location: ".$hostpath."/acc_collectionList.php?res=1&msg=".$err."&mod=7&changedid=".$thisid);
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/acc_collectionList.php?res=2&msg=".$err."&mod=7");
    }
    
    $conn->close();
}
?>