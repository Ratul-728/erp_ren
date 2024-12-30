<?php
require "conn.php";
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
include_once('../rak_framework/connection.php');
require_once('../common/insert_gl.php');

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/expenseList.php?mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) {
        //print_r($_REQUEST);die;
        //print_r($_FILES);die;
     
      $trdt= $_REQUEST['trdt'];                 //if($trdt==''){$trdt=NULL;}
      $cmbmode = $_POST['cmbmode'];             //if($cmbmode==''){$cmbmode=NULL;}
      $ref = $_POST['ref'];                     //if($ref==''){$ref=NULL;}
      $cmbtype = $_POST['cmbtype'];             //if($cmbtype==''){$cmbtype=NULL;}
      $amt = $_POST['amt'];                     //if($amt==''){$amt=NULL;}
      $cmbcc = $_POST['cmbcc'];                 //if($cmbcc==''){$cmbcc=NULL;}
      $cmbso = $_POST['cmbso'];                 //if($cmbso==''){$cmbso=NULL;}
      
      $descr = $_POST['descr'];                 //if($descr==''){$descr=NULL;}
      $hrid = $_POST['usrid'];
      $glac = $_POST["glac"];                   //if($glac==''){$glac=NULL;}
      $crglac = $_POST["crglac"];                   //if($glac==''){$glac=NULL;}
      
      $code=date(dmYHis);
      $totalup = count($_FILES['attachment1']['name']);
      $att1=$code;
      $tmpFilePath = $_FILES['attachment1']['tmp_name'];
      if ($tmpFilePath != ""){ $newFilePath = "upload/expense/".$att1.".jpg";
         $didUpload = move_uploaded_file($tmpFilePath, $newFilePath, $att1); 
      }
       
      
     $st=0; $hrid= '1';
       //echo $trdt;die;
        $qry="insert into expense( `image`, `trdt`, `transmode`, `transref`, `transtype`,  `naration`, `amount`, `crglno`,`soid`, `st`, `makeby`, `makedt`, `glac`) 
        values('".$att1."',STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'".$cmbmode."','".$ref."','".$cmbtype."','".$descr."','".$amt."','".$crglac."','".$cmbso."','".$st."','".$hrid."',sysdate(), '".$glac."')" ;
        $err="A expense created successfully";
         
       // echo  $qry;die;
          /* Accounnting */
        switch($cmbtype)
        {
            case 1 :  $mappid=11; break;
            case 2:   $mappid=13;break;
            case 3 :  $mappid=12; break;
            case 4:   $mappid=10;break;
            default: $mappid=20;
        }
          
         /* Accounnting */
       
         $expensegl = fetchByID('glmapping','buisness',$mappid,'mappedgl');	
            //$bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
         //   $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
        //$unearnedgl = fetchByID('glmapping','buisness',8,'mappedgl');
        //echo $expensegl;die;
        $descr="Expense for-".$cmbmode; 
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
                    'glac'	 =>	$glac,//$expensegl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Expense made ',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$crglac,//$glac,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Expense made',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            		insertGl($glmstArr,$gldetailArr);
            	//print_r($gldetailArr);die;
       /* accounting */
         
         
         /*
          
          
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id =$mappid";// 3-rent,5-salary,10-bills,11-others,12-legal
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc())
            {   $glno = $rowgl["mappedgl"];}}
         
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'".$cmbtype."-".$ref."','".$descr."','".$hrid."',sysdate())";
                        
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',1,'".$glno."','D','".$amt."','Expense Made','".$hrid."',sysdate())";
               //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',2,'".$glac."','C','".$amt."','Expense Made','//',sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
         */
               /* Accounnting */ 
         
        
     //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
       $exid= $_REQUEST['exid'];
      
      $glac = $_POST["glac"];                   //if($glac==''){$glac=NULL;}
      $hrid = $_POST["usrid"];
      
       $trdt= $_REQUEST['trdt'];                //if($trdt==''){$trdt=NULL;}
      $cmbmode = $_POST['cmbmode'];             //if($cmbmode==''){$cmbmode=NULL;}
      $ref = $_POST['ref'];                     //if($ref==''){$ref=NULL;}
      $cmbtype = $_POST['cmbtype'];             //if($cmbtype==''){$cmbtype=NULL;}
      $amt = $_POST['amt'];                     //if($amt==''){$amt=NULL;}
      $cmbcc = $_POST['cmbcc'];                 //if($cmbcc==''){$cmbcc=NULL;}
      $cmbso = $_POST['cmbso'];                 //if($cmbso==''){$cmbso=NULL;}
      
      $descr = $_POST['descr'];                 //if($descr==''){$descr=NULL;}
      
        $qry="update expense set `trdt`=STR_TO_DATE('".$trdt."', '%d/%m/%Y'),`transmode`='".$cmbmode."',`transref`='".$ref."',`transtype`='".$cmbtype."',`naration`='".$descr."',`amount`='".$amt."',`costcenter`='".$cmbcc."',`soid`='".$cmbso."',`glac`='".$glac."'  where `id`=".$exid."";
        $err="Expence Voucher updated successfully";
        
        /* Accounnting */
        switch($cmbtype)
        {
            case 1 :  $mappid=11; break;
            case 2:   $mappid=13;break;
            case 3 :  $mappid=12; break;
            case 4:   $mappid=10;break;
            default: $mappid=20;
        }
            
        
        $getO="SELECT transtype,amount,glac FROM expense where id=".$exid."";// Existing Amount
         $resultO = $conn->query($getO);
            if ($resultO->num_rows > 0) {while ($rowO = $resultO->fetch_assoc()) { $Otrtp = $rowO["transtype"];$Oglno = $rowO["glac"];$Oamount = $rowO["amount"];}}
               switch($Otrtp)
                {
                    case 1 :  $Omappid=11; break;
                    case 2:   $Omappid=13;break;
                    case 3 :  $Omappid=12; break;
                    case 4:   $Omappid=10;break;
                    default: $Omappid=20;
                }
         
         $getglO="SELECT mappedgl FROM glmapping where id =$Omappid";// 3-rent,5-salary,10-bills,11-others,12-legal
         $resultglO = $conn->query($getglO);
            if ($resultglO->num_rows > 0) {while ($rowglO = $resultglO->fetch_assoc())
            {   $mapglnoO = $rowglO["mappedgl"];}}      
              
              
        
         /* Accounnting */
       
         $expensegl = fetchByID('glmapping','buisness',$mappid,'mappedgl');	
            //$bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
         //   $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
        //$unearnedgl = fetchByID('glmapping','buisness',8,'mappedgl');
       // echo "hello";die;
        $descr="Expense revise for expense id -".$exid; 
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
                    'glac'	 =>	$mapglnoO,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Expense reverse ',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$Oglno,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Expense reverse',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            	
            		$gldetailArr[] = array(
            		'sl'	 =>	3,
                    'glac'	 =>	$expensegl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Expense update ',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	4,
                    'glac'	 =>	$glac,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Expense update',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            		insertGl($glmstArr,$gldetailArr);
            	//print_r($gldetailArr);die;
       /* accounting */
        
        /* $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id =$mappid";// 3-rent,5-salary,10-bills,11-others,12-legal
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc())
            {   $glno = $rowgl["mappedgl"];}}
         
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'".$cmbtype."-".$ref."','Expense Update Entry','".$hrid."',sysdate())";
                        
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',1,'".$mapglnoO."','C','".$Oamount."','Reversal for Expense update',".$hrid.",sysdate())";
               //echo  $glqry1;die;             
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',2,'".$Oglno."','D','".$Oamount."','Reversal for expense update','".$hrid."',sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry3="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',3,'".$glac."','C','".$amt."','Expense Update','".$hrid."',sysdate())";
                             
            if ($conn->query($glqry3) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry4="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',4,'".$glno."','D','".$amt."','Expense Update','".$hrid."',sysdate())";
                             
            if ($conn->query($glqry4) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
         */
               /* Accounnting */ 
        
         // echo $qry; die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/acc_expenseList.php?res=1&msg=".$err."&mod=7");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/acc_expenseList.php?res=2&msg=".$err."&mod=7");
    }
    
    $conn->close();
}
?>