<?php
require "conn.php";
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
include_once('../rak_framework/connection.php');
require_once('../common/insert_gl.php');

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/acc_paymentList.php?mod=7");
}
else
{
    if ( isset( $_POST['add'] ) )
    {
     
      $trdt= $_REQUEST['trdt'];                             
      $trdt = str_replace('/', '-', $trdt);                 
      $trdt = date('Y-m-d', strtotime($trdt));              
        
        
      $cmbmode = $_POST['cmbmode'];                         //if($cmbmode==''){$cmbmode=NULL;}
        $ref = $_POST['ref'];                               //if($ref==''){$ref=NULL;}
      $chqdt = $_POST['chqdt'];                            
      $chqdt = str_replace('/', '-', $chqdt);               
      $chqdt = date('Y-m-d', strtotime($chqdt));            //if($chqdt==''){$chqdt=NULL;}
      
      $cmbsupnm = $_POST['cmbsupnm'];                       //if($cmbsupnm==''){$cmbsupnm=NULL;}
      $amt = $_POST['amt'];                                 //if($amt==''){$amt=NULL;}
      $cmbcc = $_POST['cmbcc'];                             //if($cmbcc==''){$cmbcc=NULL;}
      $descr = $_POST['descr'];                             //if($descr==''){$descr=NULL;}
      $hrid = $_POST['usrid'];                             
      $glac = $_POST["glac"];  
      $crglac = $_POST["crglac"];  //if($glac==''){$glac=NULL;}
      $tds = $_POST["tds"];
      $vds = $_POST["vds"];
      
      $chqclearst=0;$st=0; //$hrid= '1';
       
        $qry="insert into allpayment( `trdt`, `transmode`, `transref`, `chequedt`, `customer`, `naration`, `amount`, `crglno`, `chqclearst`, `st`, `makeby`, `makedt`, `glac`, `tds`, `vds`) 
        values('".$trdt."','".$cmbmode."','".$ref."','".$chqdt."','".$cmbsupnm."','".$descr."','".$amt."','".$crglac."','".$chqclearst."','".$st."','".$hrid."',sysdate(),'".$glac."','".$tds."','".$vds."')" ;
        $err="A Payment created successfully";
         
         $cusqry="update contact set currbal=currbal-".$amt." where id=".$cmbsupnm." and status=1";
            //echo $itqry;die;
          if ($conn->query($cusqry) == TRUE) { $err="contatct updated successfully";  }
        
         
         $orgbalqry="update organization set balance=balance-".$amt." where id=".$cmbsupnm;
            //echo $itqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
          
          $orgupdbalqry="select balance from organization where id=".$cmbsupnm;
           $resultbl = $conn->query($orgupdbalqry);
            if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
          
          $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values(STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'$cmbsupnm','$cmbmode','D','$ref',$amt,$curbal,'Payment made',$hrid,sysdate())";
            //echo $itqry;die;
          if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
         
         $note="Vendor Payment: Payment Amount ".$amt." received ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$cmbsupnm."',6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amt.",'".$hrid."',sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
         
        // echo "hi";die;
         
        /* Accounnting */
       
         $vendorgl = fetchByID('glmapping','buisness',5,'mappedgl');	
            //$bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
         //   $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
        //$unearnedgl = fetchByID('glmapping','buisness',8,'mappedgl');
       // echo "hello";die;
        $descr="Voucher againts vendor payment vendor id-".$cmbsupnm; 
              //$refno=$inv_id;
             $vouchdt= date("Y-m-d");
               
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $ref,
            	'remarks' => $descr,
            	'entryby' => $hrid,
            );
            	
            		$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$glac,//$vendorgl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Payment made ',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$crglac,//$glac,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Vendor payment',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            		insertGl($glmstArr,$gldetailArr);
            	//print_r($gldetailArr);die;
       /* accounting */
        
        /*
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id=9 ";// Recevable from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
         
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."','".$trdt."','".$cmbsupnm."-".$cmbmode."','".$descr."','".$hrid."',sysdate())";
            // echo $glmqry;die;           
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',1,'".$glac."','C','".$amt."','Payment Made','".$hrid."',sysdate())";
               //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',2,'".$glno."','D','".$amt."','Payment Made','".$hrid."',sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
        */
     //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) )
    {
       $fpid= $_REQUEST['fpid'];
      $trdt= $_REQUEST['trdt'];
      $cmbmode = $_POST['cmbmode'];     //if($cmbmode==''){$cmbmode=NULL;}
      $ref = $_POST['ref'];             //if($ref==''){$ref=NULL;}
      $chqdt = $_POST['chqdt'];         //if($chqdt==''){$chqdt=NULL;}
      $cmbsupnm = $_POST['cmbsupnm'];   //if($cmbsupnm==''){$cmbsupnm=NULL;}
      $amt = $_POST['amt'];             //if($amt==''){$amt=NULL;}
      $cmbcc = $_POST['cmbcc'];         //if($cmbcc==''){$cmbcc=NULL;}
      $descr = $_POST['descr'];         //if($descr==''){$descr=NULL;}
       $hrid = $_POST['usrid'];         
       $glac = $_POST["glac"];          //if($glac==''){$glac=NULL;}
       $tds = $_POST["tds"];
       $vds = $_POST["vds"];
       
       $getO="SELECT customer,amount,glac FROM allpayment where id=".$fpid."";// Existing Aount
       //echo $getO;die;
         $resultO = $conn->query($getO);
            if ($resultO->num_rows > 0) {while ($rowO = $resultO->fetch_assoc()) { $Oglno = $rowO["glac"];$Oamount = $rowO["amount"];$OcustomerOrg = $rowO["customer"];}}
      
      
        $qry="update allpayment set `trdt`=STR_TO_DATE('".$trdt."', '%d/%m/%Y'),`transmode`='".$cmbmode."',`transref`='".$ref."',`chequedt`=STR_TO_DATE('".$chqdt."', '%d/%m/%Y'),`customer`='".$cmbsupnm."',
                `naration`='".$descr."',`amount`='".$amt."',`costcenter`='".$cmbcc."',`glac`='".$glac."', `tds`='".$tds."', `vds` = '".$vds."' 
                where `id`=".$fpid."";
        $err="Payment Voucher updated successfully";
         // echo $qry; die;
         
         $cusqry="update contact set currbal=currbal-".$amt."+$Oamount where id=".$cmbsupnm." and status=1";
            //echo $itqry;die;
          if ($conn->query($cusqry) == TRUE) { $err="contatct updated successfully";  }
        
         
        $note="Vendor Payment: Payment Amount ".$amt." has been updated ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$cmbsupnm."',6,STR_TO_DATE('".$trdt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,'".$amt."','".$hrid."',sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  }
         
         
         /* Accounnting */
       // $transitamt=0;
        //$Qtransitamt = "select (otc* $qty) amt  FROM soitemdetails WHERE socode ='".$socode."' and productid=".$product;
        //echo $Qtransitamt;die;
         //$resTransitAmt = $conn->query($Qtransitamt);
        //while ($rowamt = $resTransitAmt->fetch_assoc()) { $transitamt = $rowamt["amt"];  }
        
         $vendorgl = fetchByID('glmapping','buisness',5,'mappedgl');	
            //$bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
         //   $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
        //$unearnedgl = fetchByID('glmapping','buisness',8,'mappedgl');
        
        $descr="Payment Voucher update -".$cmbsupnm; 
              $refno=$inv_id;
             $vouchdt= date("Y-m-d");
               
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $ref,
            	'remarks' => $descr,
            	'entryby' => $hrid,
            );
            	
            		$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$vendorgl,	//reverse
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$Oamount,
            		'remarks' 	=>	'Payment Reverse for update ',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$Oglno,	//reverse 
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$Oamount,
            		'remarks' 	=>	'Reverse for update',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            	$gldetailArr[] = array(
            		'sl'	 =>	3,
                    'glac'	 =>	$vendorgl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Payment update ',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	4,
                    'glac'	 =>	$glac,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Vendor payment update',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            		insertGl($glmstArr,$gldetailArr);
         
         
         /* Accounnting */
        /*
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id=9 ";// Recevable from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
         
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'".$cmbsupnm."-".$cmbmode."','Payment Update Entry','".$hrid."',sysdate())";
                        
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',1,'".$glac."','D','".$Oamount."','Reversal for payment update','".$hrid."',sysdate())";
               //echo  $glqry1;die;             
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',2,'".$Oglno."','C','".$Oamount."','Reversal for payment update','".$hrid."',sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry3="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',3,'".$glac."','C','".$amt."','payment Update','".$hrid."',sysdate())";
                             
            if ($conn->query($glqry3) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry4="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',4,'".$glno."','D','".$amt."','payment Update','".$hrid."',sysdate())";
                             
            if ($conn->query($glqry4) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
        }
        */
         
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/acc_paymentList.php?res=1&msg=".$err."&mod=7");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/acc_paymentList.php?res=2&msg=".$err."&mod=7");
    }
    
    $conn->close();
}
?>