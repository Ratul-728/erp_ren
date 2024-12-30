<?php
require "../common/conn.php";

include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
include_once('../rak_framework/connection.php');
require_once('../common/insert_gl.php');

session_start();

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$usr = $_SESSION["user"];

$action = $_GET["action"];

if($action == "transfer_stock")
{
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0)
    {
        $qryUpdate = "UPDATE `approval_transfer_stock` SET `st`='0' WHERE id = ".$id;
        if($conn->query($qryUpdate) == true)
        {
            $msg = "Successfully declined!";
        }
        else
        {
            $msg = "Something went wrong";
        }
    }
    else
    {
        $qryUpdate = "UPDATE `approval_transfer_stock` SET `st`='2' WHERE id = ".$id;
        if($conn->query($qryUpdate) == true)
        {
            $msg = "Successfully accepted!";
            
            //Get Info
            $qryInfo = "SELECT * FROM `approval_transfer_stock` WHERE id = ".$id;
            $resultInfo = $conn->query($qryInfo);
            while($rowInfo = $resultInfo->fetch_assoc())
            {
                $product = $rowInfo["product"];
                $transfer_branch = $rowInfo["transfer_branch"];
                $transfer_stock = $rowInfo["transfer_stock"];
            }
            
            //Insert into qa
            $qryQa = "INSERT INTO `qa`(`type`, `product_id`, `quantity`, `date_iniciated`, `status`, `order_id`) 
                                VALUES ('4','$product','$transfer_stock',sysdate(),'1','$id')";
            $conn->query($qryQa);
            $newid = $conn -> insert_id;
            
            $qryWarehouse = "INSERT INTO `qa_warehouse`( `qa_id`, `qa_type`, `warehouse_id`, `ordered_qty`, `date_inspected`) 
                                            VALUES ('$newid','4','$transfer_branch','$transfer_stock',sysdate())";
            $conn->query($qryWarehouse);
        }
        else
        {
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_transfer_stock.php?res=1&msg=$msg&mod=24");
}

if($action == "do"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `approval_do` SET `st`='0', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `approval_do` SET `st`='2', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
            
            //Get Info
            $qryInfo = "SELECT * FROM `approval_do` WHERE id = ".$id;
            $resultInfo = $conn->query($qryInfo);
            while($rowInfo = $resultInfo->fetch_assoc()){
                $qaid = $rowInfo["qa_id"];
            }
            
        
            $qryUpdateQa = "UPDATE `qa` SET `approval`='1' WHERE order_id = '".$qaid."'";
            if($conn->query($qryUpdateQa) == false){
                $msg = "Something went wrong";
            }
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_do.php?res=1&msg=$msg&mod=24");
}

if($action == "co"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `co_approval` SET `approval`='0', approved_by = '$usr', actiondt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `co_approval` SET `approval`='2', approved_by = '$usr', actiondt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
            
            //Get Info
            $qryInfo = "SELECT * FROM `co_approval` WHERE id = ".$id;
            $resultInfo = $conn->query($qryInfo);
            while($rowInfo = $resultInfo->fetch_assoc()){
                $orderId = $rowInfo["order_id"];
                $co_id = $rowInfo["co_id"];
                
                $qryCo = "INSERT INTO `co` (`co_id`, `order_id`, `makeby`, `makedt`) VALUES ('$co_id','$orderId','$usr',sysdate())";
                if ($conn->query($qryCo) == TRUE) {
                    $coid = $conn->insert_id;
                    
                    $qryGetCod = "SELECT * FROM `co_approval_details` WHERE coid = ".$id;
                    $resultGetCod = $conn->query($qryGetCod);
                    while($rowgetCod = $resultGetCod->fetch_assoc()){
                        $deliQty = $rowgetCod["co_qty"]; $warehouseId = $rowgetCod["warehouse_id"]; $productId=$rowgetCod["product_id"];$orderQty = $rowgetCod["order_qty"];
                        $before_warehouse = $rowgetCod["before_warehouse"];
                        
                        $qryCoDetails = "INSERT INTO `co_details`(`coid`, `order_id`, `product_id`, `warehouse_id`,`before_warehouse`, `order_qty`, `co_qty`) 
                                                        VALUES ('$coid','$orderId','$productId','$warehouseId','$before_warehouse','$orderQty','$deliQty')";
                        if ($conn->query($qryCoDetails) == TRUE) { 
                            $err="Delivery added successfully";  
                        }
                        else{
                            $msg = 'Something went wrong!';
                            header("Location: ".$hostpath."/approval_co.php?res=1&msg=$msg&mod=24");
                            die;
                        }
                    }
                }else{
                    $msg = 'Something went wrong!';
                    header("Location: ".$hostpath."/approval_co.php?res=1&msg=$msg&mod=24");
                    die;
                }
                
                $qryUpdateCo = "UPDATE `co_approval` SET `approval`='0', approved_by = '$usr', actiondt = sysdate() WHERE id != $id AND order_id = '".$orderId."'";
                if($conn->query($qryUpdateCo) == false){
                    $msg = "Something went wrong";
                }
            }
            
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_co.php?res=1&msg=$msg&mod=24");
}

if($action == "qc"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `approval_qc` SET `st`='0', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `approval_qc` SET `st`='2', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
            
            //Get Info
            $qryInfo = "SELECT * FROM `approval_qc` WHERE id = ".$id;
            $resultInfo = $conn->query($qryInfo);
            while($rowInfo = $resultInfo->fetch_assoc()){
                $invid = $rowInfo["invid"];
            }
            
            /*--------Quality Check Block ----*/
            $qryGetInfo = "SELECT sode.productid, sode.qty, so.deliverydt, sode.id sodeid, so.socode, i.paymentSt, i.approval
                            FROM `invoice` i LEFT JOIN soitem so ON i.soid = so.socode 
                            LEFT JOIN soitemdetails sode ON sode.socode = so.socode WHERE i.id = '".$invid."'"; 
            //echo $qryGetInfo;die;
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
                
                //Check already exist or not
                if ($paymentSt == 1 && $approval == 0){
                    $qaInsert ="INSERT INTO `qa`(`type`,`product_id`, `quantity`, `date_iniciated`, `status`, `delivery_date`, `order_id`) 
                                    VALUES ('1','".$productId."','".$productQty."','".date("Y-m-d H:i:s")."','1','".$deliveryDate."','".$orderId."')";
                                    
                    if ($conn->query($qaInsert) == TRUE)
                    {
                        $insertedQaId = $conn->insert_id;
                        
                        //Insert into qa warehouse table
                        $sodetailsWarehouseInfo = "SELECT `warehouse`, `qty` FROM `soitem_warehouse` WHERE soitem_detail_id = ".$soDetailsId;
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
                        
                    }
                } 
                
            }
            
            $qryUpdateQa = "UPDATE `invoice` SET `approval`='1' WHERE id = ".$invid;
            if($conn->query($qryUpdateQa) == false){
                $msg = "Something went wrong";
            }
            
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_qc.php?res=1&msg=$msg&mod=24");
}

if($action == "withdrawal"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `approval_withdrawal` SET `st`='0', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
            //Get Info
            $qryInfo = "SELECT * FROM `approval_withdrawal` WHERE id = ".$id;
            $resultInfo = $conn->query($qryInfo);
            while($rowInfo = $resultInfo->fetch_assoc()){
                $amount = $rowInfo["amount"];
                $orgid  = $rowInfo["orgid"];
            }
            
            
        $qryCh = "SELECT `balance` FROM `organization` WHERE id = ".$orgid;
        $resultCh = $conn->query($qryCh);
        while($rowCh = $resultCh->fetch_assoc()){
            $balance = $rowCh["balance"];
            if($balance < $amount){
                $msg = "Insufficient Wallet Balance!";
                header("Location: ".$hostpath."/approval_withdrawal.php?res=2&msg=$msg&mod=7");
                die;
            }
        }
        $qryUpdate = "UPDATE `approval_withdrawal` SET `st`='2', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
            
            $qryUpdateBal = "UPDATE `organization` SET `balance`= (`balance` - $amount) WHERE id = ".$orgid;
            if($conn->query($qryUpdateBal) == true){
                $msg = "Successfully accepted!";
            }else{
                $msg = "Something went wrong";
            }
            
            $qryInsertBal = "INSERT INTO `organizationwallet`(`transdt`, `orgid`, `transmode`, `dr_cr`, `amount`, `balance`, `remarks`, `makeby`, `makedt`) 
                                                VALUES (sysdate(),'$orgid','1','D','$amount','$balance','withdrawal','$usr',sysdate())";
            if($conn->query($qryInsertBal) == true){
                $msg = "Successfully accepted!";
            }else{
                $msg = "Something went wrong";
            }
            
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_withdrawal.php?res=1&msg=$msg&mod=24");
}

if($action == "future"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `invoice` SET `backorder`='2', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `invoice` SET `backorder`='1', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/future_order_approval.php?res=1&msg=$msg&mod=24");
}

if($action == "back"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `invoice` SET `backorder`='2', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `invoice` SET `backorder`='1', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/back_order_approval.php?res=1&msg=$msg&mod=24");
}

if($action == "returnorder")
{
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0)
    {
        $qryUpdate = "UPDATE `return_order` SET `st`='0', approved_by = '$usr' WHERE id = ".$id;
        if($conn->query($qryUpdate) == true)
        {
            $msg = "Successfully declined!";
        }
        else
        {
            $msg = "Something went wrong";
        }
    }
    else
    {
        $qryUpdate = "UPDATE `return_order` SET `st`='2', approved_by = '$usr' WHERE id = ".$id;
        if($conn->query($qryUpdate) == true)
        {
           $msg = "Successfully accepted!";
            //Get Info
           //  $msg= "SELECT ro.ro_id,ro.order_id, q.product_id, ror.return_qty, qaw.warehouse_id
            //            FROM `return_order` ro LEFT JOIN return_order_details ror ON ror.ro_id=ro.id LEFT JOIN qa_warehouse qaw ON qaw.id=ror.qaw_id LEFT JOIN qa q ON q.id=qaw.qa_id 
             //           WHERE ro.id = ".$id;
                        
            $qryInfo = "SELECT ro.ro_id,ro.order_id, q.product_id, ror.return_qty, qaw.warehouse_id, ror.id rodid
                        FROM `return_order` ro LEFT JOIN return_order_details ror ON ror.ro_id=ro.id LEFT JOIN qa_warehouse qaw ON qaw.id=ror.qaw_id LEFT JOIN qa q ON q.id=qaw.qa_id 
                        WHERE ro.id = ".$id;
                      
            $resultInfo = $conn->query($qryInfo);
            while($rowInfo = $resultInfo->fetch_assoc()){
                $pid = $rowInfo["product_id"];
                $roid = $rowInfo["ro_id"];
                $qty = $rowInfo["return_qty"];
                $warehouse = $rowInfo["warehouse_id"];
                $orderid = $rowInfo["order_id"];
                $rodid   = $rowInfo["rodid"];
                
              // $msg=$pid."-".$orderid."-".$qty;
                //Qa
                $qryQa = "INSERT INTO `qa`(`type`, `product_id`, `quantity`, `date_iniciated`, `status`,`order_id`) 
                                    VALUES ('6','$pid','$qty',sysdate(),'1','$roid')";
                $conn->query($qryQa);
                
                
                $qaId = $conn -> insert_id;
                
                $qryQaw = "INSERT INTO `qa_warehouse`(`qa_id`, `qa_type`, `warehouse_id`, `ordered_qty`) 
                                            VALUES ('$qaId','6','$warehouse','$qty')";
                $conn->query($qryQaw);
                $qawid = $conn -> insert_id;
                
                $qryUpdateRod = "UPDATE `return_order_details` SET `after_qawid`= $qawid WHERE id = ".$rodid;
                $conn->query($qryUpdateRod);
                
                  //---fund return
                $org = fetchByID('quotation','socode',$orderid,'organization');
                $qryotc="SELECT otc,discounttot,qty FROM quotation_detail  where socode='$orderid' and `productid`=$pid";
                $resultotc = $conn->query($qryotc);
            while($rowotc = $resultotc->fetch_assoc())
            {
                $discounttot = $rowotc["discounttot"];
                $oqty = $rowotc["qty"];
                $otc=($discounttot/$oqty)*$qty;
            }
        
        $qrycol="insert into collection(  `trdt`,`transmode`, `transref`, `chequedt`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`) 
        values(sysdate(),0,'Return order :$roid',sysdate() ,$org,' Amount return to wallet for return',$otc,1,1,1,$usr,sysdate() ,'102050101')" ;
        $err="A receive created successfully"; 
         //echo $qrycol;die;
         if ($conn->query($qrycol) == TRUE) { $err="Collecction updared successfully";   }
          // echo $qry;die;
        // $cusqry="update contact set currbal=currbal+".$amt." where id=".$cmbsupnm." and status=1";
            //echo $itqry;die;
         // if ($conn->query($cusqry) == TRUE) { $err="contatct updared successfully";   }
        
        
         $orgbalqry="update organization set balance=balance+".$otc." where id=".$org;
            //echo $itqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
          
          $orgupdbalqry="select balance from organization where id=".$org;
           $resultbl = $conn->query($orgupdbalqry);
            if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
          
          $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values(sysdate(),'$org','0','C','$orderid',$otc,$curbal,'Refund for return item',$usr,sysdate())";
            //echo $itqry;die;
          if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
        
        
         $note="Customer Payment: return Amount  against $otc received ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('$org',6,sysdate(),'order item return','',1,$otc,$usr',sysdate()" ;
         if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  } 
         
         
        /* Accounnting */
		
		
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id=8 ";// Recevable from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
         
         $trdt=date("d/m/Y");
         
	 $glmstArr = array(
	'transdt' => $trdt,
	'refno' => $org,
	'remarks' => 'Cancel Order',
	'entryby' => $usr,
);
	
	
	$gldetailArr[] = array(
		'sl'	 =>	1,
        'glac'	 =>	'101010202',	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$otc,
		'remarks' 	=>	'Cash receive for order return',
		'entryby' 	=>	$usr,
		'entrydate' 	=>$trdt         //	formatDateReverse($trdt)
);


	$gldetailArr[] = array(
		'sl'	 =>	2,
        'glac'	 =>	'102050101',	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$otc,
		'remarks' 	=>	'Cash receive for order return',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>$trdt         //	formatDateReverse($trdt)
);
	

		insertGl($glmstArr,$gldetailArr);
            
            }
            
        }else{
            $msg = "Something went wrong";
        } 
    }
    
    header("Location: ".$hostpath."/approval_return_order.php?res=1&msg=$msg&mod=24");
}

if($action == "cancelorder"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    $coid = $_GET["coid"];
    $orgid = $_GET["org"];
    $otc = $_GET["otc"];
    
    
    if($st == 0)
    {
        $qryUpdate = "UPDATE `cancel_order` SET `st`='0', approved_by = '$usr' WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }
    else
    {
        $qryUpdate = "UPDATE `cancel_order` SET `st`='2', approved_by = '$usr' WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
            //Get Info
            
        //update store
        $qryInfo = "SELECT co.productid, qaw.warehouse, co.qty_canceled, co.order_id  
                    FROM `cancel_order` co LEFT JOIN quotation q ON q.socode=co.order_id LEFT JOIN quotation_warehouse qaw ON (qaw.socode=q.socode AND qaw.pid=co.productid)
                    WHERE co.id = ".$id;
        $resultInfo = $conn->query($qryInfo);
        while ($rowInfo = $resultInfo->fetch_assoc()) {
            $product = $rowInfo["productid"];
            $qty     = $rowInfo["qty_canceled"];
            $warehouse = $rowInfo["warehouse"];
            $orderid = $rowInfo["order_id"];
        }
        
        $qryUpdateTo = "UPDATE `chalanstock` SET `freeqty`= (freeqty + $qty) WHERE product = '$product' and storerome = ".$warehouse;
		if($conn->query($qryUpdateTo)){
		    
		    $qryUpdateToStock = "UPDATE `stock` SET `freeqty`= (freeqty + $qty) WHERE `product` = '$product'";
    		if($conn->query($qryUpdateToStock)){
    		    
    		    $msg ='Stock transfer successful';
    		}else{
    		    $msg ='Something went worng';
    		}
		    $msg ='Stock transfer successful';
		}else{
		    $msg ='Something went worng';
		}
		
		//Check the invoice
        $qryInvoice = "SELECT i.`dueamount`, i.`paymentSt`, q.orderstatus FROM `invoice` i LEFT JOIN quotation q ON q.socode=i.soid WHERE soid = '$orderid'";
        $resultInvoice = $conn->query($qryInvoice);
        while ($rowInvoice = $resultInvoice->fetch_assoc()) {
            $dueamount = $rowInvoice["dueamount"];
            $paymentSt     = $rowInvoice["paymentSt"];
            $orderSt       = $rowInvoice["orderstatus"];
        }
        //If not paid totally
        if($dueamount > 0){
            
            if($dueamount <= $otc){
                
                $qryUpdateInvoice = "UPDATE `invoice` SET `return_amount`= `return_amount` + $dueamount, `dueamount`= 0, `paymentSt` = '4' 
                                    WHERE `soid` = '$orderid'";
                if ($conn->query($qryUpdateInvoice) == TRUE) { $err="Invoice updared successfully";   }
                
                if($orderSt < 7){
                    $qryUpdateQuotation = "UPDATE `quotation` SET `orderstatus` = '4' WHERE socode = '".$orderid."'";
                    $conn->query($qryUpdateQuotation);
                }
                
                $otc = $otc - $dueamount;
            }else{
                
                $qryUpdateInvoice = "UPDATE `invoice` SET `return_amount`= `return_amount` + '$otc',`dueamount`= `dueamount` - '$otc' WHERE `soid` = '$orderid'";
                if ($conn->query($qryUpdateInvoice) == TRUE) { $err="Invoice updared successfully";   }
                
                $otc = 0;
            }   
        }
        
        if($otc > 0){
            $qrycol="insert into collection(  `trdt`,`transmode`, `transref`, `chequedt`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`) 
            values(sysdate(),0,'cancel order :$coid',sysdate() ,$orgid,' Amount return to wallet for cancel',$otc,1,1,1,$usr,sysdate() ,'102050101')" ;
            $err="A receive created successfully"; 
             //echo $qrycol;die;
             if ($conn->query($qrycol) == TRUE) { $err="Collecction updared successfully";   }
              // echo $qry;die;
            // $cusqry="update contact set currbal=currbal+".$amt." where id=".$cmbsupnm." and status=1";
                //echo $itqry;die;
             // if ($conn->query($cusqry) == TRUE) { $err="contatct updared successfully";   }
            
             $orgbalqry="update organization set balance=balance+".$otc." where id=".$orgid;
                //echo $itqry;die;
              if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
              
              $orgupdbalqry="select balance from organization where id=".$orgid;
               $resultbl = $conn->query($orgupdbalqry);
                if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
              
              $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
              values(sysdate(),'$orgid','0','C','$coid',$otc,$curbal,'Refund for cancelation',$usr,sysdate())";
                //echo $itqry;die;
              if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
            
            
             $note="Customer Payment: Payment Amount $otc received ";
            $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values('$orgid',6,sysdate(),'$note','',1,$otc,$usr',sysdate()" ;
             if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  } 
             
             
            /* Accounnting */
    		
    		
             $vouch = 10000000000; 
             $getgl="SELECT mappedgl FROM glmapping where id=8 ";// Recevable from clients
             $resultgl = $conn->query($getgl);
                if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
             
             $trdt=date("d/m/Y");	
    	    $glmstArr = array(
    	'transdt' => $trdt,
    	'refno' => $orgid,
    	'remarks' => 'Cancel Order',
    	'entryby' => $usr,
    );
    	
    	
    	$gldetailArr[] = array(
    		'sl'	 =>	1,
            'glac'	 =>	'101010202',	//glno
    		'dr_cr' 	=>	'D',
    		'amount' 	=>	$otc, 
    		'remarks' 	=>	'Cash receive from Client',
    		'entryby' 	=>	$usr,
    		'entrydate' 	=>$trdt     //	formatDateReverse($trdt)
    );
    
    
    	$gldetailArr[] = array(
    		'sl'	 =>	2,
            'glac'	 =>	'102050101',	//glno
    		'dr_cr' 	=>	'C',
    		'amount' 	=>	$otc,
    		'remarks' 	=>	'Cash receive from Client',
    		'entryby' 	=>	$hrid,
    		'entrydate' 	=>$trdt     //	formatDateReverse($trdt)
    );
    	
    
    		insertGl($glmstArr,$gldetailArr);   
        }
            
        }
        else
        {
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_cancel_order.php?res=1&msg=$msg&mod=24");
}

if($action == "check"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    $hrid = $_SESSION["user"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `approval_check` SET `st`='0', approved = '$usr' WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `approval_check` SET `st`='2', approved = '$usr' WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
            //Get Info

            $qryIn = "SELECT ac.amount, inv.invoiceamt, inv.dueamount, org.balance, ac.invoice, ac.note,ac.checkno, ac.checkdt,ac.bank,ac.image
                        FROM `approval_check` ac LEFT JOIN invoice inv ON inv.invoiceno=ac.invoice LEFT JOIN organization org ON org.id=inv.organization
                        WHERE ac.id = ".$id;
            $resultIn = $conn->query($qryIn);
            while($rowInfo = $resultIn->fetch_assoc()){
                
                $amt = $rowInfo['amount'];
        		$inv_amt = $rowInfo['invoiceamt'];
        		$dueamt = $rowInfo['dueamount'];
        		$wltamt =	$rowInfo['balance'];
        		$inv_id =	$rowInfo['invoice'];
        		$rem = $rowInfo['note'];
        		$mode= 2;
        		$cmbmode = 2;
        	    $cmbdrcr='C';
        	    $checkno = $rowInfo['checkno'];
        	    
    			$checkdate = ($rowInfo['checkdt'])?$rowInfo['checkdt']:"0000-00-00";
    			$bank = ($rowInfo['bank'])?$rowInfo['bank']:"";
    			
    			if($rowInfo["image"] != ''){
    			    $destination = "../images/upload/checks/".$rowInfo["image"];
    			}else{
    			    $destination = "";
    			}
    			
    			$cashgl = fetchByID('glmapping','buisness',3,'mappedgl');	
                //$bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
                $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
                $wallet = fetchByID('glmapping','buisness',21,'mappedgl');
    			
    			$wltamt=$wltamt+$amt;
    	  
          
          if($mode="Cash"){$mode=1;}  else{$mode=2;}// elseif($mode="Check"){$mode=2;}elseif($mode="Check"){$mode=2;}
          $ref = $inv_id;
          $descr = "Fund Receved from customer for paid against Invoice";
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
          
          $qrycoll="insert into collection(  `treat_from`, `trdt`,`transmode`, `transref`,`checkno`,`chequedt`, `bank`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`,`document`) 
            values(1,'".date("Y-m-d H:i:s")."','".$cmbmode."','".$ref."','".$checkno."' ,'".$checkdate."' ,'".$bank."' ,'".$cmbsupnm."','".$descr."',".$amt.",".$chqclearst.",".$st.",'".$curr."','".$hrid."','".date("Y-m-d H:i:s")."' ,'".$glac."','".$destination."')" ;
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
            $qryGetInfo = "SELECT sode.productid, sode.qty, so.deliverydt, sode.id sodeid, so.socode, i.paymentSt, i.approval
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
                $approval = $resultInfo["approval"];
                
                //Check already exist or not
                if ($paymentSt == 1 && $approval == 0){
                    $qaInsert ="INSERT INTO `qa`(`type`,`product_id`, `quantity`, `date_iniciated`, `status`, `delivery_date`, `order_id`) 
                                    VALUES ('1','".$productId."','".$productQty."','".date("Y-m-d H:i:s")."','1','".$deliveryDate."','".$orderId."')";
                                    
                    if ($conn->query($qaInsert) == TRUE)
                    {
                        $insertedQaId = $conn->insert_id;
                        
                        //Update Quotation Table
                        if($amt<$dueamt){
                            $orsts = 5;
                        }else{
                            $orsts = 4;
                        }
                        $qryUpdateQuotation = "UPDATE `quotation` SET `orderstatus` = '".$orsts."' WHERE socode = '".$orderId."'";
                        $conn->query($qryUpdateQuotation);
                        
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
                        
                    }
                } 
                
            }
           
    	}
    	
    	/*--------wallet block----*/
    	
    	    if($amt>$dueamt)
            {
                $amt=$dueamt;
            }
            
            if($wltamt<$amt)
            {
               $err="Error: Wallet Balance is insufficient to pay ";
                 $response = array("msg" =>$err,);
            }
            else
            {
                $qry="insert into invoicepayment(  `invoicid`, `transdt`, `transmode`, `amount`, `remarks`, `makeby`, `makedate`) 
                    values('".$inv_id."','".date("Y-m-d H:i:s")."','".$cmbdrcr."',".$amt.",'".$rem."',".$hrid.",'".date("Y-m-d H:i:s")."')" ;
                //$err="A receive created successfully";
               //echo $qry;die;
                
    			if($amt==$dueamt){
    			    $payst='4';
    			    $qryUpdateQuotation = "UPDATE `quotation` SET `orderstatus` = '4' WHERE socode = '".$orderId."'";
                    $conn->query($qryUpdateQuotation);
    			} else if($amt<$dueamt){$payst=5;} else if($amt>$inv_amount){$payst=3;} else {$payst=1;}  
    			
                    $invsqry="UPDATE `invoice` set `paidamount`=paidamount+".$amt." ,`dueamount`=dueamount-".$amt.",`paymentSt`=".$payst.",makedt='".date("Y-m-d H:i:s")."' where `invoiceno`='$inv_id'";
                   // echo $itqry;die;
                    if ($conn->query($invsqry) == TRUE) { $err=$err."Invoice Ok,";  } else{$err=$err."Invoice update failed,";}
                 
                        $curbal=0;
                        $updorgqry="UPDATE `organization` set `balance`=balance-".$amt." where `id`=".$orgid;
                        if ($conn->query($updorgqry) == TRUE) { $err=$err."Balance Ok,"; }else{$err=$err."Balance Update Failed,";}
                     
                        $orgupdbalqry="select balance from organization where id=".$orgid;
                        $resultbl = $conn->query($orgupdbalqry);
                        if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
                     
                        $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
                            values('".date("Y-m-d H:i:s")."',$orgid,'Auto','D','$inv_id',$amt,$curbal,'Payed against invoice',$hrid,'".date("Y-m-d H:i:s")."')";
                      //echo $orgwallet;die;
                        if ($conn->query($orgwallet) == TRUE) { $err=$err."Wallet Ok,";  }else{$err=$err."wallet update failed,";}
                     
                        //echo $itqry;die;
                
                
                    $note="Customer bill adjustment against purchase: Paid Amount ".$amt." received ";
                    $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
                        values('".$orgid."',6,'".date("Y-m-d H:i:s")."','".$note."','',0,".$amt.",'".$hrid."','".date("Y-m-d H:i:s")."')" ;
                    if ($conn->query($qry_othr) == TRUE) { $err=$err."CRM OK";  }else{$err=$err."History Failed";} 
                 
			
            }
            $msg = "Successfully accepted!";
            
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_check.php?res=1&msg=$msg&mod=24");
}


if($action == "gift"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `invoice` SET `approval`='4', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `invoice` SET `approval`='0', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
            
            $qryInfo = "SELECT q.socode, t.productid, t.qty 
                        FROM `invoice` inv LEFT JOIN quotation q ON q.socode=inv.soid LEFT JOIN quotation_detail t ON t.socode=q.socode 
                        WHERE inv.id = ".$id;
            $resultInfo = $conn->query($qryInfo);
            while($rowInfo = $resultInfo->fetch_assoc()){
                $product = $rowInfo["productid"];
                $totqty  = $rowInfo["qty"];
                $orderId = $rowInfo["socode"];
                
                //Insert into qa
                $qryQa = "INSERT INTO `qa`(`type`, `product_id`, `quantity`, `date_iniciated`, `status`, `order_id`) 
                                    VALUES ('7','$product','$totqty',sysdate(),'1','$orderId')";
                $conn->query($qryQa);
                $newid = $conn -> insert_id;
                
                $qryW = "SELECT `warehouse`, `qty` FROM `quotation_warehouse` WHERE `socode` = '$orderId' AND `pid` = '$product'";
                $resultW = $conn->query($qryW);
                while($rowW = $resultW->fetch_assoc()){
                    $store = $rowW["warehouse"];
                    $qty   = $rowW["qty"];
                    
                    $qryWarehouse = "INSERT INTO `qa_warehouse`( `qa_id`, `qa_type`, `warehouse_id`, `ordered_qty`, `date_inspected`) 
                                                VALUES ('$newid','7','$store','$qty',sysdate())";
                    $conn->query($qryWarehouse);
                }
            }
            
            
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_gift.php?res=1&msg=$msg&mod=24");
}

if($action == "damage"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `approval_damaged` SET `st`='0', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `approval_damaged` SET `st`='2', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_damage.php?res=1&msg=$msg&mod=24");
}

if($action == "defect"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `approval_defect` SET `st`='0', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `approval_defect` SET `st`='2', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $defect_store = 10;
            
            //Move to defect warehouse
            $qryInfo = "SELECT ad.qty, qaw.qa_type, i.barcode, i.id product, qaw.warehouse_id
                        FROM `approval_defect` ad LEFT JOIN qa_warehouse qaw ON qaw.id=ad.qaw_id LEFT JOIN qa q ON q.id=qaw.qa_id
                        LEFT JOIN item i ON i.id=q.product_id
                        WHERE ad.id = ".$id;
            $resultInfo = $conn->query($qryInfo);
            while($rowInfo = $resultInfo->fetch_assoc()){
                $qty = $rowInfo["qty"];
                $product = $rowInfo["product"];
                $barcode = $rowInfo["barcode"];
                $type = $rowInfo["qa_type"];
                $sold_warehouse = $rowInfo["warehouse_id"];
                
                //Move to defect warehouse
                $qryCheck = "SELECT * FROM `chalanstock` WHERE product = '$product' and storerome = '$defect_store'";
                $resultCheck = $conn->query($qryCheck);
                if ($resultCheck->num_rows > 0){
                    $qryInsert = "UPDATE `chalanstock` SET `freeqty`=freeqty+$qty WHERE `product` = '$product' and `storerome` = '$defect_store'";
                    
                }else{
                    $qryInsert = "INSERT INTO `chalanstock`(`product`, `freeqty`,`barcode`, `storerome`) 
                                                    VALUES ('$product','$qty','$barcode','$defect_store')";
                }
                $conn->query($qryInsert);
                
                //If qa type is sales
                // if($type == 1){
                //     //Check if product available or not
                //     $ch = false;
                //     $qrySoldFrom = "SELECT * FROM `chalanstock` WHERE product = '$product' and storerome = '$sold_warehouse'";
                //     $resultSoldFrom = $conn->query($qrySoldFrom);
                //     if ($resultSoldFrom->num_rows > 0){
                //         while($rowSoldFrom = $resultSoldFrom->fetch_assoc()){
                //             if($rowSoldFrom["freeqty"] < 1){
                                
                //             }
                //         }
                //     }else{
                        
                //     }
                    
                // }
            }
            
            $msg = "Successfully accepted!";
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_defect.php?res=1&msg=$msg&mod=24");
}

if($action == "approval_itm_rate_change"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 1)
    {
        $qryUpdate = "UPDATE `approval_item_price_change` SET `approvst`='1', approveby = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true)
        {
            $qryUpdateitm = "update  item i,approval_item_price_change c set i.rate=c.newrate,i.`approvedst`=1 where i.id=c.product and c.id=$id";
            $t=$conn->query($qryUpdateitm);
           
            $msg = "Successfully Approved!";
        }
        else
        {
            $msg = "Something went wrong";
        }
    }
    else
    {
        $qryUpdate = "UPDATE `approval_item_price_change` SET `approvst`='2', approveby = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true)
        {
           $msg = "Successfully Rejeceted!";
        }
        else
        {
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_itm_rate_change.php?res=1&msg=$msg&mod=24");
}

if($action == "quotation_price_approval"){
    $usr = $_SESSION['user'];
    $st = $_GET["st"];
    $id = $_GET["id"];
    $saleprice = $_GET["saleprice"];
    $order_id = $_GET["order_id"];
    $item_id = $_GET["item_id"];
    
    if($st == 1)
    {
        $qryUpdate = "UPDATE `approval_quotation_price_change` SET `state`='1', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true)
        {

            $msg = "Successfully Approved!";
        }
        else
        {
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `approval_quotation_price_change` SET `state`='2', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        //file_put_contents('checkapprovar.txt',$qryUpdate);
        if($conn->query($qryUpdate) == true)
        {
           $msg = "Successfully Rejeceted!";
        }
        else
        {
            $msg = "Something went wrong";
        }
        
        $qryUpdate = "UPDATE  quotation_detail SET otc='$saleprice',  WHERE socode = '$order_id' AND productid='$item_id'";
        if($conn->query($qryUpdate) == true)
        {
           $msg = "Successfully Rejeceted!";
        }
        else
        {
            $msg = "Something went wrong";
        }
        
    }
    
    header("Location: ".$hostpath."/quotation_price_approval.php?res=1&msg=$msg&mod=24");
}


if($action == "issue_order"){
    $st = $_GET["st"];
    $id = $_GET["id"];
    
    if($st == 0){
        $qryUpdate = "UPDATE `issue_order` SET `st`='0', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully declined!";
        }else{
            $msg = "Something went wrong";
        }
    }else{
        $qryUpdate = "UPDATE `issue_order` SET `st`='2', approved_by = '$usr', approvedt = sysdate() WHERE id = ".$id;
        if($conn->query($qryUpdate) == true){
            $msg = "Successfully accepted!";
            
            $qryInfo = "SELECT iod.frombranch issue_warehouse, iod.product, iod.qty, io.ioid 
            FROM `issue_order` io LEFT JOIN issue_order_details iod ON iod.ioid=io.id
            WHERE io.id= ".$id;
            $resultInfo = $conn->query($qryInfo);
            while($rowInfo = $resultInfo->fetch_assoc()){
            
                $aid = $rowInfo["ioid"]; $aqty = $rowInfo["qty"]; $pid = $rowInfo["product"]; $warehouse = $rowInfo["issue_warehouse"];
            
                $qryQa = "INSERT INTO `qa`(`type`, `product_id`, `quantity`, `date_iniciated`, `status`, `order_id`) 
                                    VALUES ('5','$pid','$aqty',sysdate(),'1','$aid')";
                $conn->query($qryQa);  $qaid = $conn -> insert_id;
                
                $qryQaw = "INSERT INTO `qa_warehouse`(`qa_id`, `qa_type`, `warehouse_id`, `ordered_qty`) 
                                    VALUES ('$qaid','5','$warehouse','$aqty')";
                $conn->query($qryQaw);
            }
        }else{
            $msg = "Something went wrong";
        }
    }
    
    header("Location: ".$hostpath."/approval_issue_order.php?res=1&msg=$msg&mod=24");
}

?>