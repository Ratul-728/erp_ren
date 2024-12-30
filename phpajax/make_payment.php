<?php
//  error_reporting(E_ALL);
//  ini_set('display_errors', 1);
session_start();
require "../common/conn.php";

include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
include_once('../rak_framework/connection.php');
require_once('../common/insert_gl.php');

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('../common/phpmailer/PHPMailerAutoload.php');


if(!$_SESSION["user"])
{
	header("Location: ".$hostpath."/hr.php"); 
}
else
{
    
    $response['debug_msg'] = "";
        
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
     //print_r($data); die; 
     
             /* Accounnting */
                 
        $cashgl = fetchByID('glmapping','buisness',3,'mappedgl');	
        $pblbankgl = fetchByID('glmapping','buisness',4,'mappedgl');//pbl//22
        $mtblbankgl = fetchByID('glmapping','buisness',7,'mappedgl');//mtbl//15
        $cblbankgl = fetchByID('glmapping','buisness',8,'mappedgl');//mtbl//14
        $advancefromcustomer = fetchByID('glmapping','buisness',6,'mappedgl');
        $bkashcashgl = fetchByID('glmapping','buisness',23,'mappedgl');
        $nagadgl = fetchByID('glmapping','buisness',16,'mappedgl');
        $chequegl = fetchByID('glmapping','buisness',22,'mappedgl');
        $payordergl = fetchByID('glmapping','buisness',17,'mappedgl');
       // $roketgl = fetchByID('glmapping','buisness',27,'mappedgl');
        $cardgl = fetchByID('glmapping','buisness',18,'mappedgl');
        $reservcashgl = fetchByID('glmapping','buisness',14,'mappedgl');
        $cashintransit='102050500'; 
    
    //upload if check attached;
    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"]))
    {
        $targetDir = "../images/upload/checks/";
        if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }   
    
        $targetFile = $targetDir . basename($_FILES["file"]["name"]);
        
        //echo $targetFile;die;
        $allowedExtensions = array("jpg", "jpeg", "png", "pdf");
        $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $newfilename = uniqid().".".$fileExtension;
        $destination = $targetDir . $newfilename;
         
        if (in_array($fileExtension, $allowedExtensions)) 
        {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $destination)) 
            {
                //echo "File uploaded successfully";
                $response['debug_msg']  .= "<br>move_uploaded_file() success. Destination: ".$destination;
                $response['code'] = 1; //success
                $uploaded = 1;
            } 
            else 
            {
                $uploaded = 0;
                $response['debug_msg']  .= "<br>move_uploaded_file() failed. Destination: ".$destination;
                $response['code'] = 2; //error
                die;
            }
        } 
        else 
        {
            $response['msg'] = "Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.";
            $response['code'] = 2; //error
            //echo "Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.";
            $response['debug_msg']  .= "<br>Invalid file type";
            echo json_encode($response);
            $uploaded = 3;
            die;
        }
    
    }
    //die;
    /* END UPLOAD  */
    
    
    foreach($_POST["ajxdata"] as $key => $val)
    {
        $data[$key] = $val;
    }
    
    //print_r($data); die; 
    
	if($data['checkdate'])
	{
		$date_str = $data['checkdate'];
		$timestamp = strtotime(str_replace('/', '-', $date_str));
		$data['checkdate'] = date('Y-m-d', $timestamp);
	}
	else
	{
		$data['checkdate'] = "0000-00-00";
	}
     
    $whichTab =  $data["paytab"];
    //$mode= $data['paywith'];
	  // echo $mode;die;
	 // echo $whichTab;die;
	if($whichTab==0) // wallet
	{
    	     
		$amt= $data['paidmnt2'];
	    $inv_amt= $data['payable2'];
		$dueamt= $data['duemnt2'];
		$wltamt=$data['walletmnt'];
		$inv_id=$data['invoiceno'];
		$rem= $data['note2'];
		$cmbdrcr='W';
		$checkdate = ($data['checkdate'])?$data['checkdate']:"0000-00-00";
		$bank = ($data['bank'])?$data['bank']:"";
    	     
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
        $ref = $inv_id;
        $descr = $rem;//"Fund Receved from customer for paid against Invoice";
        $chqclearst=0;$st=0; //$hrid= '1';
        $glac = $cashgl;
        $curr = 1;
        $cmbmode = 0;   
          
        if($destination){$destination = $destination;}else{$destination="";}
          
      //	$qrycoll="insert into collection(  `treat_from`, `trdt`,`transmode`, `transref`, `chequedt`, `bank`,`customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`,`document`) 
       //     					values(1,'".date("Y-m-d H:i:s")."','".$cmbmode."','".$ref."','".$checkdate."' ,'".$bank."' ,'".$cmbsupnm."','".$descr."',".$amt.",".$chqclearst.",".$st.",'".$curr."','".$hrid."','".date("Y-m-d H:i:s")."' ,'".$glac."','".$destination."')" ;
        //$err="A receive created successfully";
            
        $note="Customer Payment: Payment Amount ".$amt." adjusted from wallet ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$cmbsupnm."',6,'".date("Y-m-d H:i:s")."','".$note."','',0,".$amt.",'".$hrid."','".date("Y-m-d H:i:s")."')" ;
        if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  } 
            
       // if ($conn->query($qrycoll) == TRUE) { $err=$err."Collection OK";  }else{ $err=$err."GL failed";}
            
        /*--------collection Block ----*/
          
        /*--------Quality Check Block ----*/
        $qryGetInfo = "SELECT sode.productid, sode.qty, so.deliverydt, sode.id sodeid, so.socode, i.paymentSt, i.approval, q.orderstatus 
                        FROM `invoice` i LEFT JOIN soitem so ON i.soid = so.socode 
                        LEFT JOIN soitemdetails sode ON sode.socode = so.socode 
                        LEft JOIN quotation q ON q.socode=i.soid WHERE i.invoiceno = '".$inv_id."'";
        $resultConn = $conn->query($qryGetInfo); 
        while($resultInfo = $resultConn->fetch_assoc()) 
        { 
            $productId = $resultInfo['productid'];
            $productQty = $resultInfo['qty'];
            $deliveryDate = $resultInfo['deliverydt'];
            $soDetailsId = $resultInfo['sodeid'];
            $orderId = $resultInfo['socode'];
            $paymentSt = $resultInfo["paymentSt"];
            $approval = $resultInfo["approval"];
            $orderSt = $resultInfo["orderstatus"];
            
                //Check already exist or not
            if ($paymentSt == 1 && $approval == 0)
            {
                $qaInsert ="INSERT INTO `qa`(`type`,`product_id`, `quantity`, `date_iniciated`, `status`, `delivery_date`, `order_id`) 
                                    VALUES ('1','".$productId."','".$productQty."','".date("Y-m-d H:i:s")."','1','".$deliveryDate."','".$orderId."')";
                                    
                if ($conn->query($qaInsert) == TRUE)
                {
                    $insertedQaId = $conn->insert_id;
                    //Update Quotation Table
                    if($orderSt < 7)
                    {
                        if($amt<$dueamt){$orsts = 5;} else {$orsts = 4; }
                        $qryUpdateQuotation = "UPDATE `quotation` SET `orderstatus` = '".$orsts."' WHERE socode = '".$orderId."'";
                        $conn->query($qryUpdateQuotation);
                    }
                        
                    //Insert into qa warehouse table
                    $sodetailsWarehouseInfo = "SELECT `warehouse`, `qty` FROM `soitem_warehouse` WHERE soitem_detail_id = ".$soDetailsId;
					//echo $sodetailsWarehouseInfo; die;
                    $resultSoDWI = $conn->query($sodetailsWarehouseInfo); 
                    while($resultSoDWInfo = $resultSoDWI->fetch_assoc()) 
                    {
                        $warehouseId = $resultSoDWInfo["warehouse"];
                        $warehouseQty = $resultSoDWInfo["qty"];
                        $insertQaWarehouse = "INSERT INTO `qa_warehouse`(`qa_id`, `warehouse_id`, `ordered_qty`) 
                                                                VALUES ('".$insertedQaId."','".$warehouseId."','".$warehouseQty."')";
                        $conn->query($insertQaWarehouse);
                        //update dueqty to soitem_details
                        $qrySoUpdate = "UPDATE `soitemdetails` SET `dueqty`='".$warehouseQty."' WHERE id = ".$soDetailsId;
                        $conn->query($qrySoUpdate);
                    }
                    
                    //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = 51";
                    $resultmail = $conn->query($qrymail);
                    while($rowmail = $resultmail->fetch_assoc())
                    {
                        $active = $rowmail["active"];
                        $emailid = $rowmail["id"];
                        if($active == 1)
                        {
                            $recipientNames = array();
                            $recipientEmails = array();
                            $ccEmails = array();
                            $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                                    FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                            $resultSendTo = $conn->query($qrySendTo);
                            while($rowst = $resultSendTo->fetch_assoc())
                            {
                                $recipientNames[] = $rowst["empname"];
                                if($rowst["type"] == 1 && $rowst["office_email"] != "")
                                {
                                    $recipientEmails[] = $rowst["office_email"];
                                }
                                else if($rowst["type"] == 2 && $rowst["office_email"] != "")
                                {
                                    $ccEmails[] = $rowst["office_email"];
                                }
                            }
                            $mailsubject = " New Sold Item's Quality check requested";
                            $message = " New Quality check request  for $orderId  was received.";
                                    
                            sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                        }
                    }
                }
            }
        }
	}
	else // other than wallet
	{
        $amt = $data['paidmnt'];
		$inv_amt = $data['payable'];
		$dueamt = $data['duemnt'];
		$wltamt =	$data['walletmnt'];
		$inv_id =	$data['invoiceno'];
		$rem = $data['note'];
		$mode= $data['paywith'];
		$cmbmode = $data['paywith'];
	    $cmbdrcr='C';
	    $checkno = $data['checkno'];
	    $transaction_number = ($data["transaction_number"]) ? $data["transaction_number"]:"";
	    
		$checkdate = ($data['checkdate'])?$data['checkdate']:"0000-00-00";
		$bank = ($data['bank'])?$data['bank']:"";
		
		//Paymnet with check
		if($mode == 2 || $mode == 10 || $mode == 11)
		{
		    if($uploaded ==1){$image = $newfilename;} else { $image = "";}
		    $qry_approval = "INSERT INTO `approval_check`(`invoice`, `checkno`, `checkdt`, `bank`, `amount`, `note`, `image`, `makeby`, `makedt`,transmode) 
		                                        VALUES ('$inv_id','$checkno','$checkdate','$bank','$amt','$rem','$image','$hrid',sysdate(),$mode)";
		    if ($conn->query($qry_approval) == TRUE) 
            {
                //Mail to Management
                $qrymail = "SELECT id,active FROM `email` WHERE id = 42";
                $resultmail = $conn->query($qrymail);
                while($rowmail = $resultmail->fetch_assoc())
                {
                    $active = $rowmail["active"];
                    $emailid = $rowmail["id"];
                    if($active == 1)
                    {
                        $recipientNames = array();
                        $recipientEmails = array();
                        $ccEmails = array();
                        $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                                    FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                        $resultSendTo = $conn->query($qrySendTo);
                        while($rowst = $resultSendTo->fetch_assoc())
                        {
                            $recipientNames[] = $rowst["empname"];
                            if($rowst["type"] == 1 && $rowst["office_email"] != "")
                            {
                                $recipientEmails[] = $rowst["office_email"];
                            }
                            else if($rowst["type"] == 2 && $rowst["office_email"] != "")
                            {
                                $ccEmails[] = $rowst["office_email"];
                            }
                        }
                        $mailsubject = "Approval Required for Checque";
                        $message = "An approval request for $inv_id Cheque was received.";
                                    
                        sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                    }
                }
                
                $response = array("msg" =>"Amount of ".number_format($amt, 2, '.', '')." against  invoice of '".$inv_id."'  has been send to admin for cheque approval","invno"=>$inv_id);
                echo json_encode($response);
                die;
            } 
            else 
            {
                $err="Something went wrong: Payment Failed";
                $response = array("msg" =>$err,);
                echo json_encode($response);
                die;
            }
		}
	    $wltamt=$wltamt+$amt;
        if($mode="Cash"){$mode=1;}  else{$mode=2;}// elseif($mode="Check"){$mode=2;}elseif($mode="Check"){$mode=2;}
        $ref = $inv_id;
        $descr =$rem; //"Fund Receved from customer for paid against Invoice";
        $curr = 1; 
        $glac = $cashgl;
      
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
      
        $qrycoll="insert into collection(  `treat_from`, `trdt`,`transmode`, `transref`,`checkno`,`chequedt`, `bank`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`,`document`, `transaction_number`) 
        values(1,'".date("Y-m-d H:i:s")."','".$cmbmode."','".$ref."','".$checkno."' ,'".$checkdate."' ,'".$bank."' ,'".$cmbsupnm."','".$descr."',".$amt.",".$chqclearst.",".$st.",'".$curr."','".$hrid."','".date("Y-m-d H:i:s")."' ,'".$glac."','".$destination."', '".$transaction_number."')" ;
        $err="A receive created successfully";
         
        $orgbalqry="update organization set balance=balance+".$amt." where id=".$cmbsupnm;
        if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
        
        $curbal = fetchByID('organization','id',$cmbsupnm,'balance');
          
        $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
        values('".date("Y-m-d H:i:s")."','$cmbsupnm','$cmbmode','C','$ref',$amt,$curbal,'Fund Receive',$hrid,'".date("Y-m-d H:i:s")."')";
            //echo $itqry;die;
            
        if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
         
        $note="Customer Payment: Payment Amount ".$amt." received ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$cmbsupnm."',6,'".date("Y-m-d H:i:s")."','".$note."','',0,".$amt.",'".$hrid."','".date("Y-m-d H:i:s")."')" ;
        if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  } 
        
        if ($conn->query($qrycoll) == TRUE) { $err=$err."Collection OK";  }else{ $err=$err."GL failed";}
        
        /*--------collection Block ----*/
      
      /*--------Quality Check Block ----*/
        $qryGetInfo = "SELECT sode.productid, sode.qty, so.deliverydt, sode.id sodeid, so.socode, i.paymentSt, i.approval,q.orderstatus
                        FROM `invoice` i LEFT JOIN soitem so ON i.soid = so.socode 
                        LEFT JOIN soitemdetails sode ON sode.socode = so.socode 
                        LEFT JOIN quotation q ON q.socode=i.soid
                        WHERE i.invoiceno = '".$inv_id."'"; 
                        

        $resultConn = $conn->query($qryGetInfo); 
        while($resultInfo = $resultConn->fetch_assoc()) 
        { 
            $productId = $resultInfo['productid'];
            $productQty = $resultInfo['qty'];
            $deliveryDate = $resultInfo['deliverydt'];
            $soDetailsId = $resultInfo['sodeid'];
            $orderId = $resultInfo['socode'];
            $paymentSt = $resultInfo["paymentSt"];
            $approval = $resultInfo["approval"];
            $orderSt  = $resultInfo["orderstatus"];
            
            //Check already exist or not
            if ($paymentSt == 1 && $approval == 0)
            {
                $qaInsert ="INSERT INTO `qa`(`type`,`product_id`, `quantity`, `date_iniciated`, `status`, `delivery_date`, `order_id`) 
                                VALUES ('1','".$productId."','".$productQty."','".date("Y-m-d H:i:s")."','1','".$deliveryDate."','".$orderId."')";
                                
                if ($conn->query($qaInsert) == TRUE)
                {
                    $insertedQaId = $conn->insert_id;
                    
                    //Update Quotation Table
                    if($orderSt < 7){
                        if($amt<$dueamt){$orsts = 5;}else{$orsts = 4;}
                        $qryUpdateQuotation = "UPDATE `quotation` SET `orderstatus` = '".$orsts."' WHERE socode = '".$orderId."'";
                        $conn->query($qryUpdateQuotation);
                    }
                    
                    //Insert into qa warehouse table
                    $sodetailsWarehouseInfo = "SELECT warehouse, qty FROM soitem_warehouse WHERE soitem_detail_id = ".$soDetailsId;
					
					//dumpTxt($sodetailsWarehouseInfo);
                    //echo $sodetailsWarehouseInfo; die;
                    $resultSoDWI = $conn->query($sodetailsWarehouseInfo);
                    
                    while($resultSoDWInfo = $resultSoDWI->fetch_assoc()) 
                    {
                        $warehouseId = $resultSoDWInfo["warehouse"];
                        $warehouseQty = $resultSoDWInfo["qty"];
                        
                        $insertQaWarehouse = "INSERT INTO `qa_warehouse`(`qa_id`, `warehouse_id`, `ordered_qty`) 
                                                                VALUES ('".$insertedQaId."','".$warehouseId."','".$warehouseQty."')";
                        $conn->query($insertQaWarehouse);
                        
                        //update dueqty to soitem_details
                        $qrySoUpdate = "UPDATE `soitemdetails` SET `dueqty`='".$warehouseQty."' WHERE id = ".$soDetailsId;
                        $conn->query($qrySoUpdate);
                    }
                    
                    //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = 51";
                    $resultmail = $conn->query($qrymail);
                    while($rowmail = $resultmail->fetch_assoc())
                    {
                        $active = $rowmail["active"];
                        $emailid = $rowmail["id"];
                        if($active == 1)
                        {
                            $recipientNames = array();
                            $recipientEmails = array();
                            $ccEmails = array();
                            $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                                    FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                            $resultSendTo = $conn->query($qrySendTo);
                            while($rowst = $resultSendTo->fetch_assoc())
                            {
                                $recipientNames[] = $rowst["empname"];
                                if($rowst["type"] == 1 && $rowst["office_email"] != "")
                                {
                                    $recipientEmails[] = $rowst["office_email"];
                                }
                                else if($rowst["type"] == 2 && $rowst["office_email"] != "")
                                {
                                    $ccEmails[] = $rowst["office_email"];
                                }
                            }
                            $mailsubject = " New Sold Item's Quality check requested";
                            $message = " New Quality check request  for $orderId  was received.";
                                    
                            sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                        }
                    }
                }
            } 
        }
	}
    $paidamount=$amt;
    if($amt>$dueamt){$paidamount=$dueamt;}
     $qry="insert into invoicepayment(  `invoicid`, `transdt`, `transmode`, `amount`, `remarks`, `makeby`, `makedate`) 
            values('".$inv_id."','".date("Y-m-d H:i:s")."','".$cmbdrcr."',".$paidamount.",'".$rem."',".$hrid.",'".date("Y-m-d H:i:s")."')" ;
    
   
    
    if($wltamt<$amt) 
    {
       $err="Error: Wallet Balance is insufficient to pay ";
         $response = array("msg" =>$err,);
    }
    else
    {
        //echo $qry;die;
                
		if($amt==$dueamt)
		{
		    $payst='4';
		    if($orderSt < 7){
    		    $qryUpdateQuotation = "UPDATE `quotation` SET `orderstatus` = '4' WHERE socode = '".$orderId."'";
                $conn->query($qryUpdateQuotation);
		    }
		} 
		else if($amt<$dueamt){$payst=5;} 
		else if($amt>$inv_amount){$payst=3;} 
		else {$payst=1;}  
		$cmbmode = $data['paywith'];if($cmbmode=='1'){$cashamount=$paidamount;}else {$cashamount=0;}
        $invsqry="UPDATE `invoice` set `paidamount`=paidamount + $paidamount ,cashpaid=cashpaid + $cashamount ,`dueamount`=dueamount-$paidamount,`paymentSt`=$payst,makedt='".date("Y-m-d H:i:s")."' where `invoiceno`='$inv_id'";
        // echo $itqry;die;
        if ($conn->query($invsqry) == TRUE) { $err=$err."Invoice Ok,";  } else{$err=$err."Invoice update failed,";}
                 
        $curbal=0;
        $updorgqry="UPDATE `organization` set `balance`=balance-".$paidamount." where `id`=".$orgid;
        if ($conn->query($updorgqry) == TRUE) { $err=$err."Balance Ok,"; }else{$err=$err."Balance Update Failed,";}
                     
        $orgupdbalqry="select balance from organization where id=".$orgid;
        $resultbl = $conn->query($orgupdbalqry);
        if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
                     
        $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
            values('".date("Y-m-d H:i:s")."',$orgid,'Auto','D','$inv_id',$paidamount,$curbal,'Paid against invoice',$hrid,'".date("Y-m-d H:i:s")."')";
        //echo $orgwallet;die;
        if ($conn->query($orgwallet) == TRUE) { $err=$err."Wallet Ok,";  }else{$err=$err."wallet update failed,";}
     
        $note="Customer bill adjustment against purchase: Paid Amount ".$amt." received ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
                    values('".$orgid."',6,'".date("Y-m-d H:i:s")."','".$note."','',0,".$amt.",'".$hrid."','".date("Y-m-d H:i:s")."')" ;
        if ($conn->query($qry_othr) == TRUE) { $err=$err."CRM OK";  }else{$err=$err."History Failed";} 
        
       //Accounting
        $gl=$cashgl;
        $cmbmode = $data['paywith'];
        if($cmbmode=='1')
        {
            $gl=$cashintransit;
        }
        else if($cmbmode=='2')
        {
            $gl=$chequegl;
        }
        else if($cmbmode=='3')
        {
        $dbank = ($data['depbank']);
        if($dbank=='14'){$gl=$cblbankgl;} else if($dbank=='15'){$gl=$mtblbankgl;} else if($dbank=='22'){$gl=$pblbankgl;}else{$gl=$cashgl;}
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
        
        if($whichTab==1) 
    	{
	        $descr="Voucher againts purchase -".$inv_id; 
            $refno=$inv_id;
            $vouchdt= date("d/m/Y");
               
            $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr,
            	'entryby' => $hrid,
                );
    	    
    	    $gldetailArr[] = array(
    		'sl'	 =>	1,
            'glac'	 =>	$gl,	//glno
    		'dr_cr' 	=>	'D',
    		'amount' 	=>	$amt,
    		'remarks' 	=>	'Cash collection for payment anignst invoice',
    		'entryby' 	=>	$hrid,
    		'entrydate' 	=>	$vouchdt
            );
            
            
        	$gldetailArr[] = array(
        		'sl'	 =>	2,
                'glac'	 =>	$advancefromcustomer,	//glno
        		'dr_cr' 	=>	'C',
        		'amount' 	=>	$amt,
        		'remarks' 	=>	'Cash collection for payment anignst invoice',
        		'entryby' 	=>	$hrid,
        		'entrydate' 	=>	$vouchdt
            );
            
            insertGlfin($glmstArr,$gldetailArr);
        /* Accounting*/ 
            
        }
        //is it ok?
        else
        {
            //$response = array("msg" =>"Amount of ".number_format($amt, 2, '.', '')." against  invoice of '".$inv_id."'  has been paid","invno"=>$inv_id);
            //echo json_encode($response);
        }
    }
    
    if ($conn->query($qry) == TRUE) 
    {
                    //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = 9";
                    $resultmail = $conn->query($qrymail);
                    while($rowmail = $resultmail->fetch_assoc())
                    {
                        $active = $rowmail["active"];
                        $emailid = $rowmail["id"];
                        if($active == 1)
                        {
                            $recipientNames = array();
                            $recipientEmails = array();
                            $ccEmails = array();
                            $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                                                FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                            $resultSendTo = $conn->query($qrySendTo);
                            while($rowst = $resultSendTo->fetch_assoc())
                            {
                                $recipientNames[] = $rowst["empname"];
                                if($rowst["type"] == 1 && $rowst["office_email"] != "")
                                {
                                    $recipientEmails[] = $rowst["office_email"];
                                }
                                else if($rowst["type"] == 2 && $rowst["office_email"] != "")
                                {
                                    $ccEmails[] = $rowst["office_email"];
                                }
                            }
                            $mailsubject = "New sold item quality check needed";
                            $message = "New sold item of $inv_id is added in your work queue.";
                            
                            sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                        }
                    }
                    
        $response = array("msg" =>"Amount of ".number_format($amt, 2, '.', '')." against  invoice of '".$inv_id."'  has been paid","invno"=>$inv_id);
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
?>