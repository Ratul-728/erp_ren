<?php
session_start();
require "../common/conn.php";
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
include_once('../rak_framework/connection.php');
require_once('../common/insert_gl.php');


if(!$_SESSION["user"]){
	header("Location: ".$hostpath."/hr.php"); 
}else{
    
$hrid = $_SESSION['user'];

$amt= 0;
$inv_amt=  0;
$dueamt=  0;
$wltamt= 0;
$inv_id= 0;
$rem= 0;
$mode= 0;
$cmbdrcr='';
	    
$cmbmode = '' ; 
$ref = '';   
$chqdt = ''; 
$cmbsupnm = '';
$descr = '';        
$curr = 0;        
$glac =0;
$orgid=0;
  //echo $cmbsupnm;die;
  $chqclearst=0;$st=0; //$hrid= '1';
 
// print_r($data); die; 

	foreach($_POST["ajxdata"] as $key => $val){
		$data[$val['name']] = $val['value'];
	}
	
	//print_r($data); die; 
	
	//accounting 
          ///*
            $cashgl = fetchByID('glmapping','buisness',3,'mappedgl');	
            $reservegl = '102050300';
            $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
            $unearnedgl = fetchByID('glmapping','buisness',8,'mappedgl');
             $receivablefromcustomer='102020101';
             $receivablefromcustomerFinance='102020103';
             $cashglamount=0;
          $reserveglamount=0;
         // */
	
	
	//$response = array("msg" => print_r($data));die;
	
	$whichTab =  $_POST["ajxdata"][10]["value"]; //paytab: 0=From Wallet, 1=Cash Receive
	$inv_id=$data['invoiceno'];
	$qryInfo = "SELECT `dueamount`, `due_reservedamt` FROM `invoice` WHERE `invoiceno` = '$inv_id'";
      $resultInfo = $conn->query($qryInfo); 
      while($rowInfo = $resultInfo->fetch_assoc()){
          $invDue = $rowInfo["dueamount"];
          $invDueRes = $rowInfo["due_reservedamt"];
      }
      $totDue = $invDue + $invDueRes;
    
    // $whichTab = 0 means, this request come from payment from wallet tab
	if($whichTab==0)
	{
	    
	    //$amt= $_POST["ajxdata"][7]["value"]; //$data['paidmnt2']
		$amt= $data['paidmnt2'];
		
	    //$inv_amt= $_POST["ajxdata"][6]["value"]; // $data['payable2']
		$inv_amt= $data['payable2'];
		
	    //$dueamt= $_POST["ajxdata"][8]["value"]; //$data['duemnt2'];
		$dueamt= $data['duemnt2'];
			
	   // $wltamt=$_POST["ajxdata"][5]["value"]; //$data['walletmnt'];
		$wltamt=$data['walletmnt'];
			
	    //$inv_id=$_POST["ajxdata"][12]["value"]; //$data['invoiceno'];
		$inv_id=$data['invoiceno'];
		
	    //$rem=$_POST["ajxdata"][9]["value"]; // $data['note2'];
		$rem= $data['note2'];
			
	    $cmbdrcr='W';
	     
	     $orgidqry="SELECT `organization` FROM `invoice` where  `invoiceno`='$inv_id'";
                $orresult = $conn->query($orgidqry); 
                if ($orresult->num_rows > 0)
                { 
                    while($orgrow = $orresult->fetch_assoc()) 
                    { 
                       $orgid= $orgrow['organization'];
                    }
                    
                }
                $cmbsupnm=$orgid;
	    
	}
	//this request come from cash receive tab
	else
	{
	    //$amt= $_POST["ajxdata"][1]["value"]; //$data[paidmnt]
		$amt = $data['paidmnt'];
		
	    //$inv_amt= $_POST["ajxdata"][0]["value"];  //$data[payable]
		$inv_amt = $data['payable'];
		
	    //$dueamt= $_POST["ajxdata"][2]["value"]; //$data[duemnt]
		$dueamt = $data['duemnt'];
		
	    //$wltamt=$_POST["ajxdata"][5]["value"]; //[walletmnt] 
		$wltamt =	$data['walletmnt'];
		
	    //$inv_id=$_POST["ajxdata"][12]["value"]; //[invoiceno] 
		$inv_id =	$data['invoiceno'];
		
	    //$rem=$_POST["ajxdata"][4]["value"];//$data[note]
		$rem = $data['note'];
		
	    //$mode=$_POST["ajxdata"][3]["value"]; //$data[paywith]
		$mode= $data['paywith'];
		
	    $cmbdrcr='C';
	    
	    $wltamt=$wltamt+$amt;
	  
	 // print_r($data);die;
	  
	  
      $cmbmode = $_POST["ajxdata"][3]["value"];   
      if($mode="Cash"){$mode=1;}  else{$mode=2;}// elseif($mode="Check"){$mode=2;}elseif($mode="Check"){$mode=2;}
      
      $ref = $inv_id; 
      //$cmbsupnm = $_POST['org_id'];
      //$amt = $_POST['amt'];             if($amt==''){$amt='0';}
      $descr = "Fund Receved from customer for paid against Invoice";
      $curr = 1; 
      //$glac = '101010202';
      
      $chqclearst=0;$st=0; //$hrid= '1';
      
       $orgidqry="SELECT `organization` FROM `invoice` where  `invoiceno`='$inv_id'";
                $orresult = $conn->query($orgidqry); 
                if ($orresult->num_rows > 0)
                { 
                    while($orgrow = $orresult->fetch_assoc()) 
                    { 
                       $orgid= $orgrow['organization'];
                    }
                    
                }
    $cmbsupnm=$orgid;
      /* -----collection Block -----*/
      
      if($totDue < $amt){
          //I don't know what will happen, mamun vai can decide that. Over Pay scenario.
      }
      //if total due < paid amount and total due is greater than 0. Meaning: We have 2 types of due such as reserve due and normal due. if we paid less then normal due,
      //Then then it will paid normal amount first, then paid the rest to reserved due amount.
      if($invDue < $amt && $invDue > 0){
          
          $resAmt = $amt-$invDue;
          //$cashglamount=$invDue;
          //$reserveglamount=$resAmt;
          
          $qrycoll="insert into collection(`treat_from`,   `trdt`,`transmode`, `transref`, `chequedt`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`) 
                            values(1,'".date("Y-m-d H:i:s")."','".$cmbmode."','".$ref."','".date("Y-m-d H:i:s")."' ,'".$cmbsupnm."','".$descr."',".$invDue.",".$chqclearst.",".$st.",'".$curr."','".$hrid."','".date("Y-m-d H:i:s")."' ,'".$glac."')" ;
        $err="A receive created successfully";
        if ($conn->query($qrycoll) == TRUE) { $err=$err."Collection OK";  }else{ $err=$err."GL failed";}
        
        $qrycoll="insert into collection( `treat_from`, `trdt`,`transmode`, `transref`, `chequedt`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`) 
                            values(2,'".date("Y-m-d H:i:s")."','6','".$ref."','".date("Y-m-d H:i:s")."' ,'".$cmbsupnm."','".$descr."',".$resAmt.",".$chqclearst.",".$st.",'".$curr."','".$hrid."','".date("Y-m-d H:i:s")."' ,'".$glac."')" ;
        $err="A receive created successfully";
        if ($conn->query($qrycoll) == TRUE) { $err=$err."Collection OK";  }else{ $err=$err."GL failed";}
        
         $orgbalqry="update organization set balance=balance+".$amt.", reserve_balance=reserve_balance+".$resAmt." where id=".$cmbsupnm;
            //echo $itqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
          
          $orgupdbalqry="select balance from organization where id=".$cmbsupnm;
           $resultbl = $conn->query($orgupdbalqry);
            if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
          
          $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values('".date("Y-m-d H:i:s")."','$cmbsupnm','$cmbmode','C','$ref',$invDue,$curbal,'Fund Receive',$hrid,'".date("Y-m-d H:i:s")."')";
            //echo $itqry;die;
          if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
          
          $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values('".date("Y-m-d H:i:s")."','$cmbsupnm','6','C','$ref',$resAmt,$curbal,'Fund Receive',$hrid,'".date("Y-m-d H:i:s")."')";
            //echo $itqry;die;
          if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
      }
      //if total normal due >= paid amount. if we paid less then normal due, Then then it will only paid normal amount.
      else if($invDue >= $amt){
          $resAmt = $amt-$invDue;
          //$reserveglamount=$resAmt;
          
          $qrycoll="insert into collection(  `treat_from`,`trdt`,`transmode`, `transref`, `chequedt`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`) 
                            values(1,'".date("Y-m-d H:i:s")."','".$cmbmode."','".$ref."','".date("Y-m-d H:i:s")."' ,'".$cmbsupnm."','".$descr."',".$amt.",".$chqclearst.",".$st.",'".$curr."','".$hrid."','".date("Y-m-d H:i:s")."' ,'".$glac."')" ;
        $err="A receive created successfully";
        if ($conn->query($qrycoll) == TRUE) { $err=$err."Collection OK";  }else{ $err=$err."GL failed";}
        
        
         $orgbalqry="update organization set balance=balance+".$amt." where id=".$cmbsupnm;
            //echo $itqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
          
          $orgupdbalqry="select balance from organization where id=".$cmbsupnm;
           $resultbl = $conn->query($orgupdbalqry);
            if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
          
          $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values('".date("Y-m-d H:i:s")."','$cmbsupnm','$cmbmode','C','$ref',$amt,$curbal,'Fund Receive',$hrid,'".date("Y-m-d H:i:s")."')";
            //echo $itqry;die;
          if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
      }
      
      //if total reserved due <= paid amount and normal due is already paid. then it will only reserved normal amount.
      else if($invDueRes <= $amt && $invDue == 0){
           
          //$reserveglamount=$amt;
          
        $qrycoll="insert into collection(`treat_from`, `trdt`,`transmode`, `transref`, `chequedt`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`) 
                            values(2, '".date("Y-m-d H:i:s")."','6','".$ref."','".date("Y-m-d H:i:s")."' ,'".$cmbsupnm."','".$descr."',".$amt.",".$chqclearst.",".$st.",'".$curr."','".$hrid."','".date("Y-m-d H:i:s")."' ,'".$glac."')" ;
        $err="A receive created successfully";
        if ($conn->query($qrycoll) == TRUE) { $err=$err."Collection OK";  }else{ $err=$err."GL failed";}
        
         $orgbalqry="update organization set reserve_balance=reserve_balance+".$amt." where id=".$cmbsupnm;
            //echo $itqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
          
          $orgupdbalqry="select balance from organization where id=".$cmbsupnm;
           $resultbl = $conn->query($orgupdbalqry);
            if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
          
          $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values('".date("Y-m-d H:i:s")."','$cmbsupnm','6','C','$ref',$amt,$curbal,'Fund Receive',$hrid,'".date("Y-m-d H:i:s")."')";
            //echo $itqry;die;
          if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
      }
      
      
      
      /*--------Quality Check Block ----*/
        $qryGetInfo = "SELECT sode.productid, sode.qty, so.deliverydt, sode.id sodeid, so.socode, i.paymentSt
                        FROM `invoice` i LEFT JOIN soitem so ON i.soid = so.socode 
                        LEFT JOIN soitemdetails sode ON sode.socode = so.socode WHERE i.invoiceno = '".$inv_id."'";
        
        $resultConn = $conn->query($qryGetInfo); 
        while($resultInfo = $resultConn->fetch_assoc()) 
        { 
            $productId = $resultInfo['productid'];
            $productQty = $resultInfo['qty'];
            $deliveryDate = $resultInfo['deliverydt'];
            $soDetailsId = $resultInfo['sodeid'];
            $orderId = $resultInfo['socode'];
            $paymentSt = $resultInfo["paymentSt"];
            
            //Check already exist or not
            if ($paymentSt == 1){
                $qaInsert ="INSERT INTO `qa`(`type`,`product_id`, `quantity`, `date_iniciated`, `status`, `delivery_date`, `order_id`) 
                                VALUES ('1','".$productId."','".$productQty."','".date("Y-m-d H:i:s")."','1','".$deliveryDate."','".$orderId."')";
                                
                if ($conn->query($qaInsert) == TRUE)
                {
                    $insertedQaId = $conn->insert_id;
                    
                    //Update Quotation Table
                    if($amt<$totDue){
                        $orsts = 5;
                    }else{
                        $orsts = 4;
                    }
                    $qryUpdateQuotation = "UPDATE `quotation` SET `orderstatus` = '".$orsts."' WHERE socode = '".$orderId."'";
                    $conn->query($qryUpdateQuotation);
                    
                    
                    //dumpTxt($qryUpdateQuotation); 
                    
                    //Insert into qa warehouse table
                    $sodetailsWarehouseInfo = "SELECT `warehouse`, `qtn` FROM `soitem_warehouse` WHERE soitem_detail_id = ".$soDetailsId;
                    //echo $sodetailsWarehouseInfo; die;
                    $resultSoDWI = $conn->query($sodetailsWarehouseInfo); 
                   
                    while($resultSoDWInfo = $resultSoDWI->fetch_assoc()) 
                    {
                        $warehouseId = $resultSoDWInfo["warehouse"];
                        $warehouseQty = $resultSoDWInfo["qtn"];
                        
                        $insertQaWarehouse = "INSERT INTO `qa_warehouse`(`qa_id`, `warehouse_id`, `ordered_qty`) 
                                              VALUES ('".$insertedQaId."','".$warehouseId."','".$warehouseQty."')";
                        $conn->query($insertQaWarehouse);
                        
                        //update dueqty to soitem_details
                        $qrySoUpdate = "UPDATE `soitemdetails` SET `dueqty`='".$warehouseQty."' WHERE id = ".$soDetailsId;
                        $conn->query($qrySoUpdate);
                    }
                }
            } 
            
        }
        
	}
	
	/*--------wallet block----*/
	 //$cashglamount=$amt;
    //$reserveglamount=0;
	 
      if($invDueRes==0){$cashglamount=$amt;$reserveglamount=0;}
      else if($invDueRes>=$amt){$cashglamount=0;$reserveglamount=$amt;}
      else {$cashglamount=$amt-$invDueRes;$reserveglamount=$invDueRes;}
      
                   
     /* Accounnting */
                            
             
            /* */               
            $descr="Voucher againts purchase -".$inv_id; 
              $refno=$inv_id;
             $vouchdt= date("d/m/Y");
               
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr,
            	'entryby' => $hrid,
            );
            	
          
         // $cashglamount=0;
          // $reserveglamount=0;
          
            //$tlandingcost=0;
            if($whichTab==1)
            	{
            	$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$cashgl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$cashglamount,
            		'remarks' 	=>	'Cash collection for payment anignst invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$customergl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$cashglamount,
            		'remarks' 	=>	'Cash collection for payment anignst invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            	
              	$gldetailArr[] = array(
            		'sl'	 =>	3,
                    'glac'	 =>	$customergl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$cashglamount,
            		'remarks' 	=>	'Customer Paid Against Invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	4,
                    'glac'	 =>	$receivablefromcustomer,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$cashglamount,
            		'remarks' 	=>	'Customer Paid Against Invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );  
            	$gldetailArr[] = array(
            		'sl'	 =>	5,
                    'glac'	 =>	$reservegl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$reserveglamount,
            		'remarks' 	=>	'Reserve for financial adjustement',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	6,
                    'glac'	 =>	$receivablefromcustomerFinance,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$reserveglamount,
            		'remarks' 	=>	'Reserve for financial adjustement',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            	
            	    //print_r($gldetailArr);die;
            	}
            	else
            	{
            		$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$customergl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$cashglamount,
            		'remarks' 	=>	'Customer Paid Against Invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$receivablefromcustomer,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$cashglamount,
            		'remarks' 	=>	'Customer Paid Against Invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
                	$gldetailArr[] = array(
            		'sl'	 =>	3,
                    'glac'	 =>	$reservegl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$reserveglamount,
            		'remarks' 	=>	'Reserve for financial adjustement',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	4,
                    'glac'	 =>	$receivablefromcustomerFinance,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$reserveglamount,
            		'remarks' 	=>	'Reserve for financial adjustement',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            	}
            		// print_r($gldetailArr);die;
            		insertGl($glmstArr,$gldetailArr);
        /* Accounting*/             
             
      
      //$cashglamount=$amt;
                //$reserveglamount=0;
    //  echo $reserveglamount;die;
	
	    if($amt>$totDue)
        {
            $amt=$totDue;
        }
       
        
        if($wltamt<$amt)
        {
           $err="Error: Wallet Balance is insufficient to pay ";
             $response = array("msg" =>$err,);
        }
        else
        {
            if($amt==$totDue){
			    $payst='4';
			    $qryUpdateQuotation = "UPDATE `quotation` SET `orderstatus` = '4' WHERE socode = '".$orderId."'";
                $conn->query($qryUpdateQuotation);
			} else if($amt<$totDue){$payst=5;} else if($amt>$totDue){$payst=3;} else {$payst=1;}  
			
			
			if($invDue < $amt && $invDue > 0){
			    $resAmt = $amt - $invDue;
			   // $cashglamount=$invDue;
              //  $reserveglamount=$resAmt;
          
			    
			    $qry="insert into invoicepayment(  `invoicid`, `transdt`, `transmode`, `amount`, `remarks`, `makeby`, `makedate`) 
                values('".$inv_id."','".date("Y-m-d H:i:s")."','".$cmbdrcr."',".$invDue.",'".$rem."',".$hrid.",'".date("Y-m-d H:i:s")."')" ;
                
                $qryRes="insert into invoicepayment(  `invoicid`, `transdt`, `transmode`, `amount`, `remarks`, `makeby`, `makedate`) 
                values('".$inv_id."','".date("Y-m-d H:i:s")."','6',".$resAmt.",'".$rem."',".$hrid.",'".date("Y-m-d H:i:s")."')" ;
                $conn->query($qryRes);
                
                $invsqry="UPDATE `invoice` set `paidamount`=paidamount+".$invDue." ,`dueamount`=dueamount-".$invDue.",
                                `paid_reservedamt`=paid_reservedamt+".$resAmt." ,`due_reservedamt`=due_reservedamt-".$resAmt.",
                                `paymentSt`=".$payst.",makedt='".date("Y-m-d H:i:s")."' where `invoiceno`='$inv_id'";
                if ($conn->query($invsqry) == TRUE) { $err=$err."Invoice Ok,";  } else{$err=$err."Invoice update failed,";}
             
                    $curbal=0;
                    $updorgqry="UPDATE `organization` set `balance`=balance-".$invDue.", reserve_balance = reserve_balance=".$resAmt." where `id`=".$orgid;
                    if ($conn->query($updorgqry) == TRUE) { $err=$err."Balance Ok,"; }else{$err=$err."Balance Update Failed,";}
                 
                    $orgupdbalqry="select balance from organization where id=".$orgid;
                    $resultbl = $conn->query($orgupdbalqry);
                    if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
                 
                    $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
                        values('".date("Y-m-d H:i:s")."',$orgid,'Auto','D','$inv_id',$invDue,$curbal,'payed against invoice',$hrid,'".date("Y-m-d H:i:s")."')";
                    if ($conn->query($orgwallet) == TRUE) { $err=$err."Wallet Ok,";  }else{$err=$err."wallet update failed,";}
                    
                    $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
                        values('".date("Y-m-d H:i:s")."',$orgid,'6','D','$inv_id',$resAmt,$curbal,'payed against invoice',$hrid,'".date("Y-m-d H:i:s")."')";
                    if ($conn->query($orgwallet) == TRUE) { $err=$err."Wallet Ok,";  }else{$err=$err."wallet update failed,";}
     
			}
			else if($invDue >= $amt){
			    //$cashglamount=$amt;
                //$reserveglamount=0;
          
			    
			    $qry="insert into invoicepayment(  `invoicid`, `transdt`, `transmode`, `amount`, `remarks`, `makeby`, `makedate`) 
                values('".$inv_id."','".date("Y-m-d H:i:s")."','".$cmbdrcr."',".$amt.",'".$rem."',".$hrid.",'".date("Y-m-d H:i:s")."')" ;
            
                $invsqry="UPDATE `invoice` set `paidamount`=paidamount+".$amt." ,`dueamount`=dueamount-".$amt.",`paymentSt`=".$payst.",makedt='".date("Y-m-d H:i:s")."' where `invoiceno`='$inv_id'";
                if ($conn->query($invsqry) == TRUE) { $err=$err."Invoice Ok,";  } else{$err=$err."Invoice update failed,";}
             
                    $curbal=0;
                    $updorgqry="UPDATE `organization` set `balance`=balance-".$amt." where `id`=".$orgid;
                    if ($conn->query($updorgqry) == TRUE) { $err=$err."Balance Ok,"; }else{$err=$err."Balance Update Failed,";}
                 
                    $orgupdbalqry="select balance from organization where id=".$orgid;
                    $resultbl = $conn->query($orgupdbalqry);
                    if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
                 
                    $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
                        values('".date("Y-m-d H:i:s")."',$orgid,'Auto','D','$inv_id',$amt,$curbal,'payed against invoice',$hrid,'".date("Y-m-d H:i:s")."')";
                    if ($conn->query($orgwallet) == TRUE) { $err=$err."Wallet Ok,";  }else{$err=$err."wallet update failed,";}
			}
			else if($invDueRes <= $amt && $invDue == 0){
			   // $cashglamount=0;
                //$reserveglamount=$amt;
          
			    
			    $qry="insert into invoicepayment(  `invoicid`, `transdt`, `transmode`, `amount`, `remarks`, `makeby`, `makedate`) 
                values('".$inv_id."','".date("Y-m-d H:i:s")."','6',".$amt.",'".$rem."',".$hrid.",'".date("Y-m-d H:i:s")."')" ;
            
                $invsqry="UPDATE `invoice` set `paid_reservedamt`=paid_reservedamt+".$amt." ,`due_reservedamt`=due_reservedamt-".$amt.",`paymentSt`=".$payst.",makedt='".date("Y-m-d H:i:s")."' where `invoiceno`='$inv_id'";
                if ($conn->query($invsqry) == TRUE) { $err=$err."Invoice Ok,";  } else{$err=$err."Invoice update failed,";}
             
                    $curbal=0;
                    $updorgqry="UPDATE `organization` set `reserve_balance`=reserve_balance-".$amt." where `id`=".$orgid;
                    if ($conn->query($updorgqry) == TRUE) { $err=$err."Balance Ok,"; }else{$err=$err."Balance Update Failed,";}
                 
                    $orgupdbalqry="select balance from organization where id=".$orgid;
                    $resultbl = $conn->query($orgupdbalqry);
                    if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
                 
                    $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
                        values('".date("Y-m-d H:i:s")."',$orgid,'6','D','$inv_id',$amt,$curbal,'payed against invoice',$hrid,'".date("Y-m-d H:i:s")."')";
                    if ($conn->query($orgwallet) == TRUE) { $err=$err."Wallet Ok,";  }else{$err=$err."wallet update failed,";}
			}
            
            
                            
    
            
            if ($conn->query($qry) == TRUE) 
            {
                $response = array("msg" => "Amount of ".number_format($amt, 2, '.', '')." against  invoice of '".$inv_id."'  has been paid","invno"=>$inv_id);
                echo json_encode($response);
				//echo $response[0];
            } 
            else 
            {
                 $err=$err."Payment Failed";
                 $response = array("msg" =>$err,);
    	            echo json_encode($response);
            }
         
       }
	
}
?>