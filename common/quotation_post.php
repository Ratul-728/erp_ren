<?php

//print_r($_REQUEST);die;
session_start();
require "conn.php";
include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');
include_once('../rak_framework/fetch_test.php');
require_once("../rak_framework/edit.php");
require_once("../rak_framework/misfuncs.php");

$hrid = $_SESSION['user'];
$mode = $_REQUEST['mode'];

$futureOrder = false;
$backOrder = false;

if(!isset($_SESSION['user']))
{
     header("Location: ".$hostpath."/hr.php");
}
else
{
	$srid= $_REQUEST['serid'];
	//find order status based on which button clicked on
	switch ($_REQUEST['postaction'])
	{
	    case 'Save':
		$orderstatus = 1;
		break;

		case 'Update':
		$orderstatus = 1;
		break;
			
		case 'Save as Revision':
		$orderstatus = 9;
		break;
			
		case 'Create Order':
		$orderstatus = 2;
		break;
		
		case 'Send Email':
		$orderstatus = 1;
		$sendemail = 1;
		break;	
	}	
    $item = $_POST['itemName']; // item array
	//print_r($_POST['itemName']);die;
	$vatarr = $_POST['vat']; // item array
	$msu = $_POST['measureUnit']; //not in use
	$oqty = $_POST['quantity_otc']; // total qty, not warehouse wise; not in use
	$orQty = $_POST['quantity_otc'];// total qty, not warehouse wise; not in use
	$unpo = $_POST['unitprice_otc'];   //Price
	$price = ($unpo)?$unpo:0;
    //echo $unpo[0];die;
	$prdprice = $_POST['prodprice'];
	$curr_nm = $_POST['curr'];
	$dscnt = $_POST['discnt'];   // discnt[] discount rate;
	$dcntrate = ($dscnt)?$dscnt:0;
	$dscnttot = $_POST['unittotal']; //individual discount + unit total price;
	$deliveryamt = ($_POST["deliveryamt"])?$_POST["deliveryamt"]:0;
	$custp = 2;
	$org = $_POST['org_id']; 
	$srctp=1;
	$sup_id= $_REQUEST['cmbsupnm'];
	$discntnt= ($_REQUEST['discntnt'])?$_REQUEST['discntnt']:0;	//Fixed distount
	$adjustment = $discntnt;
	$poc= $hrid;//$_REQUEST['cmbpoc'];	//Account Manager in the form
	$tax= 0; 
	$st= ($_REQUEST['cmbsostat'])?$_REQUEST['cmbsostat']:0; 
	$det= $_REQUEST['details'];
	$cost=0;
	$cmbstore=1;
	$po_dt= $_REQUEST['po_dt'];
    $invmn= substr($_REQUEST['po_dt'],3,2);
    $invyr= substr($_REQUEST['po_dt'],6,4);	
	$qotationstatus = ($orderstatus == 9)?1:$orderstatus; // 9 means draft in general
    //echo $qotationstatus;die;
	$dscr = $_POST['details'];
	$note = $_POST["note"];
	$projtp = $_POST["saletype"];
	$proj = ($_POST["desig"])?$_POST["desig"]:"";
	
	//Gift
	$gift = $_POST["gift"];
	
	//echo $proj;die;
	//print_r($_POST); die;
 	//find total vat invoice and discount amount
	$index = 0;
	foreach($vatarr as $vatrate)
	{
	    $unitTotal = $oqty[$index]*$unpo[$index];
		$discountAmount = ($unitTotal*$dscnt[$index])/100;
		$TotalDiscountAmount += $discountAmount;
		$AmountWithDiscount = $unitTotal - $discountAmount;
		$TotalAmountWithDiscount +=$AmountWithDiscount;
		$totalVATamount +=	 ($vatrate*$AmountWithDiscount)/100;
		$totalProdcutAmount  += ($oqty[$index]*$unpo[$index]);
		$totalProdcutDiscountAmount  += ($dscnt[$index]*($oqty[$index]*$unpo[$index]))/100;
		$index++;
	}
	//echo $totalProdcutAmount;die;
	$invoiceamount = (($totalProdcutAmount-$TotalDiscountAmount) + $totalVATamount) + $deliveryamt;
	$newInvoiceAmount = $invoiceamount-$adjustment;
		
	$debug = 0;
	
	if($debug==1)
	{
		echo "Subtotal:".$TotalAmountWithDiscount."<br>";
		echo "Total VAT :".$totalVATamount."<br>";
		echo "Total Discount:".$TotalDiscountAmount."<br>";
		echo "Adjustment:".$adjustment."<br>";
		echo " Delivery Charge :".$deliveryamt."<br>";
		echo "Total:".$newInvoiceAmount."<br>";
		die;
	}
	//insert data
	if($_REQUEST['mode'] == 1)
	{ 
    	//getFormatedUniqueID($table,$idcolumn,$prefix,$numberlen,$padding)
		if($gift == 1)
		{
		    $poid = getFormatedUniqueID('quotation','id','GIFT-',6,"0");
		}
		else
		{
		    $poid = getFormatedUniqueID('quotation','id','QT-',6,"0");
		}
		//echo $poid;die;
		//insert in revision table; need revision id to add it in revision detail table during insert;
		//if($_REQUEST['mode'] == 1)
		if($orderstatus == 9)
		{
			$qryQuoRev="insert into quotation_revisions (`orderstatus`, `socode`,`customertp`,`organization`,`srctype`,`project`, `customer`, `orderdate`,`deliveryamt`,`adjustment`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`, `status`,`remarks`,`poc`, note) 
			values($qotationstatus,'".$poid."','".$custp."','".$org."','".$projtp."','".$proj."','".$sup_id."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'), ".$deliveryamt.",".$discntnt.",'".$hrid."','".$totalVATamount."','".$tax."','".$newInvoiceAmount."','".$hrid."','".date("Y-m-d H:i:s")."','".$st."','".$dscr."','".$poc."','".$note."')";
			//echo $qryQuoRev;die;
			$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_revisions_query_1'] = $qryQuoRev;
			
			if ($conn->query($qryQuoRev) == TRUE){ 
				$err="quotation_revisions added successfully";
				$quotation_revision_id = $conn -> insert_id;
				$QtRevInsertSuccess = 1;
				
			}else{ 
				$err = $mysqli -> error;
				$err = 'Error: Quotation revision adding error';
				$QtRevInsertSuccess = 0;
			}
			$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_revisions_result_1'] = $QtRevInsertSuccess;


				//echo $revision_id;
		}

		//add data in quotation
        //if($_REQUEST['mode'] == 1)
		$qryAddQuotation="insert into quotation(`orderstatus`, `socode`,`customertp`,`organization`,`srctype`,project, `customer`, `orderdate`, `deliveryby`, `deliveryamt`,`adjustment`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`, `status`,`remarks`,`poc`, note) 
		values($qotationstatus,'".$poid."','".$custp."','".$org."','".$projtp."','".$proj."' ,'".$sup_id."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'), '".$deliveryby."',".$deliveryamt.",".$discntnt.",'".$hrid."','".$totalVATamount."','".$tax."','".$newInvoiceAmount."','".$hrid."','".date("Y-m-d H:i:s")."','".$st."','".$det."','".$poc."', '".$note."')";
			//echo $qryAddQuotation;die;
			
		$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_query_2'] = $qryAddQuotation;
			
		if ($conn->query($qryAddQuotation) == TRUE)
		{
			if($res == 0)
			{ //get insert id to loading data in edit mode for revision saved button;
				$quotation_data_id = $conn -> insert_id;
				$insertSuccess = 1;
				$err="Quotation created successfully"; 
			}		
		}
		else
		{
				$insertSuccess = 0;
				$err = 'Error: Quotation adding error';
		}
			
		$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_result_2'] = $insertSuccess;
		
		//Confirm Order
	
		if($orderstatus == 2)
		{ 
			//generate invoice no;
			$invoiceno =  getFormatedUniqueID('invoice','id','INV-',6,"0");
			
			$qryAddSoitem="insert into soitem(`orderstatus`, `socode`,`customertp`,`organization`,`srctype`,project, `customer`, `orderdate`, `deliveryby`, `deliveryamt`,`adjustment`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`, `status`,`remarks`,`poc`,`note`) 
			values($qotationstatus,'".$poid."','".$custp."','".$org."','".$projtp."','".$proj."','".$sup_id."','".date("Y-m-d H:i:s")."', '".$deliveryby."',".$deliveryamt.",".$discntnt.",'".$hrid."','".$totalVATamount."','".$tax."','".$newInvoiceAmount."','".$hrid."','".date("Y-m-d H:i:s")."','".$st."','".$det."','".$poc."','".$note."')";
			//echo $qryAddSoitem; die;
			
			$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['soitem_query_3'] = $qryAddSoitem;
			
			if ($conn->query($qryAddSoitem) == TRUE)
			{
				if($res == 0)
				{ //get insert id to loading data in edit mode for revision saved button;
					$insertSuccess = 1;
					$err="Quotation transfered to sale order";
				}		
			}
			else
			{
				$insertSuccess = 0;
				$err = 'Error: Quotation transfering to sale order';
			}
			$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['soitem_result_3'] = $insertSuccess;
			
			//CREATE INVOIE
            $qryInvoice="INSERT INTO `invoice`( `invoiceno`, `invoicedt`,`invyr`, `invoicemonth`, `soid`, `organization`, `invoiceamt`,amount_bdt,adjustment, `paidamount`, `dueamount`,  `invoiceSt`, `paymentSt`, `makeby`,`makedt`) 
            values('".$invoiceno."','".date("Y-m-d H:i:s")."','".$invyr."','".$invmn."','".$poid."','".$org."','".$newInvoiceAmount."','".$newInvoiceAmount."',".$discntnt.",0,'".$newInvoiceAmount."',1,1,'".$hrid."','".date("Y-m-d H:i:s")."')";
            // echo $qryinv;die;
            
            $_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['invoice_query_4'] = $qryInvoice;
            
             if ($conn->query($qryInvoice) == TRUE) { $err="Invoice created successfully"; $inv_success = 1;  }else{ $inv_success = 0;}
             
             $_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['invoice_result_4'] = $inv_success;
			
			
		}
		
		//add data indetail tables
        $cost=0;
           
        if (is_array($item))
        {
            for ($i=0;$i<count($item);$i++)
            {
                $itmsl=$i+1;
				$itmmnm=$item[$i];
				$descr=$dscr;
				$mu=$msu[$i];
				$qty=$oqty[$i];
				$qtym=$oqtym[$i];
				$upo=$unpo[$i]; 
				$upm=$unpm[$i]; 
				$currnm=$curr_nm[$i];
				$itmvat=$vat[$i];//$itmait=$ait[$i];  
                $productprice=$prdprice[$i]; 
				$disc = ($dscnt[$i])?$dscnt[$i]:0; 
				$disctot= ($dscnttot[$i])?$dscnttot[$i]:0; //individual discount + unit total price;
				//if($descr==''){$descr='NULL';} if($mu==''){$mu='NULL';} if($qty==''){$qty='NULL';} if($qtym==''){$qtym='NULL';} if($currnm==''){$currnm='1';}
				if($upo==''){$upo=0;}
				if($upm==''){$upm=0;}
				$amt=($qty*$upo)+($qtym*$upm);
				$tot_amt=$tot_amt+$disctot;
				$tot_otc=$tot_otc+($qty*$upo);
				$vatrt=$vatarr[$i];
				//$aitrt=$aitarr[$i];
				$vata = $vatarr[$i]*($disctot)*0.01;
			   // $aita = $aitarr[$i]*($disctot)*0.01;
				$invamt=$tot_otc+$vata+$aita-$disctot;
				$totvat=$totvat+$vata;
			   // $totait=$totait+$aita;
					
				//new subtotal
				$unitTotal = $price[$i]*$orQty[$i];
				$discountAmount = ($unitTotal*$dcntrate[$i])/100;
				$AmountWithDiscount = $unitTotal - $discountAmount;
				$vatAmount = ($AmountWithDiscount*$vatRate[$i])/100;
				$subtotal = $subtotal+ ($AmountWithDiscount+$vatAmount);
				//end new subtotal					
					
					
				$prdcost = fetchByID('item','id',$itmmnm,'cost');
                       	
				//insert into quotation detail;
				$itqry="INSERT INTO quotation_detail ( `socode`,`sosl`, `productid`,`remarks`, `mu`, `qty`,vatrate,`vat`, `otc`,aitrate, `ait`,cost,discountrate,discounttot,`currency`, `makeby`, `makedt`) 
             	values( '".$poid."','".$itmsl."','".$itmmnm."','".$descr."','".$mu."','".$qty."','".$vatrt."','".$vata."','".$upo."','0','0',".$prdcost.",".$disc.",".$disctot.",'".$currnm."','".$hrid."','".date("Y-m-d H:i:s")."')";
				//echo $itqry;die;
				
				$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_detail_query_5'][] = $itqry;

				if($conn->query($itqry) == TRUE)
				{ 
				    $err="Quotation detail added successfully";
				    $quotation_detail_id = $conn -> insert_id;
				    $qd_success = 1;
				}
				else
				{ 
				    $qd_success = 0;
			    }
				$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_detail_result_5'][] = $qd_success;
				
				if($orderstatus == 2)
				{ //Create Order
					$soitemqry="INSERT INTO soitemdetails ( `socode`,`sosl`, `productid`,`remarks`, `mu`, `qty`,vatrate,`vat`, `otc`,aitrate, `ait`,cost,discountrate,discounttot,`currency`, `makeby`, `makedt`) 
                 	values( '".$poid."','".$itmsl."','".$itmmnm."','".$descr."','".$mu."','".$qty."','".$vatrt."','".$vata."','".$upo."','0','0',".$prdcost.",".$disc.",".$disctot.",'".$currnm."','".$hrid."','".date("Y-m-d H:i:s")."')";
					
					$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['soitemdetails_query_6'][] = $soitemqry;
					
					if ($conn->query($soitemqry) == TRUE) 
					{ 
					    $err="Quotation detail transfered to sale order";
					    $soitem_details_id = $conn -> insert_id;
					    $sid_success = 1;
					}
					else
					{
					    $sid_success = 0;
					    
					}
					$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['soitemdetails_result_6'][] = $sid_success;
					
                    $invQuery="INSERT INTO `invoicedetails`( `socode`,`invoiceno`, `sosl`, `billtype`, `invoicemoth`, `invoiceyr`, `invoicedt`, `product`, `qty`, `amount`,discountrate,discounttot,vat,ait,`currency`, `makeby`, `makedt`) 
                    values('".$poid."','".$invoiceno."','".$itmsl."',1,'".$invmn."','".$invyr."','".date("Y-m-d H:i:s")."','".$itmmnm."','".$qty."','".$upo."',".$disc.",".$disctot.",'".$vata."','0','".$currnm."','".$hrid."','".date("Y-m-d H:i:s")."')";
                    //echo $invQuery;die;
                    
                    $_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['invoicedetails_query_7'][] = $invQuery;
                    
                    if ($conn->query($invQuery) == TRUE) 
                    { 
                        $err="invoice added successfully";
                        $invd_success = 1;
                    }
                    else
                    {
                        $invd_success = 0;
                        
                    }
					$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['invoicedetails_result_7'][] = $invd_success;
				
					//Put data in soitemdetails warehouse
					$whqty = $_POST["whqty"][$itmmnm];
            		for($m = 0; $m < count($whqty); $m++)
            		{       
                		// 0 quantity check which is not selected
                		if($whqty[$m] > 0)
                		{
            		        $whid = $_POST["whid"][$itmmnm][$m];
                        	$whdelivery_date = $_POST["delivery_date"][$itmmnm][$m];
                        	
                        	if($whid == 7)
                        	{
                        	    $futureOrder = true;
                        	}
                        	if($whid == 8)
                        	{
                        	    $backOrder = true;
                        	}
                        	
							$soitemWarehouseQry = "INSERT INTO `soitem_warehouse`(`socode`,pid, `soitem_detail_id`, `warehouse`, `qty`, `expted_deliverey_date`) 
							                                     VALUES ('".$poid."',$itmmnm,'".$soitem_details_id."','".$whid."','".$whqty[$m]."',STR_TO_DATE('".$whdelivery_date."', '%d/%m/%Y'))";
                		    //echo $soitemWarehouseQry;die;
                		    
                		    $_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['soitem_warehouse_query_8'][] = $soitemWarehouseQry;
                		    
                		    if ($conn->query($soitemWarehouseQry) == TRUE)
                		    {
                                $err="Sotiem Warehouse created successfully";
                                $siwh_success = 1;
                    		}
                    		else
                    		{
                    			$err = 'Error: Soitem Warehouse adding error';
                    			$siwh_success = 0;
                    		}
                    		$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['soitem_warehouse_result_8'][] = $siwh_success;
                		}
            		}
				}
				$whqty = $_POST["whqty"][$itmmnm];
				for($m = 0; $m < count($whqty); $m++)
				{  
    		        // 0 quantity check which is not selected
    		        if($whqty[$m] > 0)
    		        {
    		            $whid = $_POST["whid"][$itmmnm][$m];
        		        $whdelivery_date = $_POST["delivery_date"][$itmmnm][$m];
        		        
        		        if($whid == 7)
        		        {
                    	    $futureOrder = true;
                    	} 
                    	if($whid == 8)
                    	{
                    	    $backOrder = true;
                    	}
        		        
        		        $qryAddQuotationWarehouse="INSERT INTO `quotation_warehouse`(`socode`,`pid`, `soitem_detail_id`, `warehouse`, `qty`, `expted_deliverey_date`) 
        		        VALUES ('".$poid."',$itmmnm,'".$quotation_detail_id."','".$whid."','".$whqty[$m]."',STR_TO_DATE('".$whdelivery_date."', '%d/%m/%Y'))";
						
					//	echo $qryAddQuotationWarehouse;die;
						$qryAddQuotationWarehouse1 .= $qryAddQuotationWarehouse;
						$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_warehouse_query_9'][] = $qryAddQuotationWarehouse;
            			if ($conn->query($qryAddQuotationWarehouse) == TRUE)
            			{
                            $err="Quotation Warehouse created successfully";
                            //Reduce Quantity from stock
                            $qryReduceStock = "UPDATE `stock` SET `freeqty`= `freeqty` - $whqty[$m],`orderedqty`= `orderedqty` + $whqty[$m] WHERE `product` = ".$itmmnm;
            			    $conn->query($qryReduceStock);
            			    $qryReduceChalan="UPDATE `chalanstock` SET `freeqty`=`freeqty` - $whqty[$m],`orderedqty`=`orderedqty` + $whqty[$m] WHERE `product` = $itmmnm AND `storerome` = ".$whid;
            			    $conn->query($qryReduceChalan);
            			    $qwh_success = 1;
            			}
            			else
            			{
            				$err = 'Error: Quotation Warehouse adding error';
            				$qwh_success = 0;
            			}
            			
            			$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_warehouse_result_9'][] = $qwh_success;
    		        }
    		    }
				
			    if($orderstatus == 9 && $qty > 0)
			    { /* if saved as revision during insert*/
               	
  					$qryinvdet="insert into quotation_revisions_detail ( `socode`,`revision_id`,`sosl`, `productid`,`remarks`, `mu`, `qty`,vatrate,`vat`, `otc`,aitrate, `ait`,cost,discountrate,discounttot,`currency`, `makeby`, `makedt`) 
                 			                                	values( '".$poid."','".$quotation_revision_id."','".$itmsl."','".$itmmnm."','".$descr."','".$mu."','".$qty."','".$vatrt."','".$vata."','".$upo."','0','0',".$prdcost.",".$disc.",".$disctot.",'".$currnm."','".$hrid."','".date("Y-m-d H:i:s")."')";
                     
                     $_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_revisions_detail_query_10'][] = $qryinvdet;
                     
                     //echo $qryinvdet;die;
                    if ($conn->query($qryinvdet) == TRUE) 
                    { 
                        $err="invoice added successfully";  
                        $quotation_revision_detail_id = $conn -> insert_id;
                        $quotation_revision_detail_success = 1;
                    }
                    else
                    {
                        $quotation_revision_detail_success = 0;
                    }
                     
                    $_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_revisions_detail_result_10'][] = $quotation_revision_detail_success ;
                     
                     //Add Warehouse info into Revision DB
        		    if($QtRevInsertSuccess == 1)
        		    {
            		    $whqty = $_POST["whqty"][$itmmnm];
            		    for($m = 0; $m < count($whqty); $m++)
            		    {
            		        // 0 quantity check which is not selected
            		        if($whqty[$m] > 0)
            		        {
                		        $whid = $_POST["whid"][$itmmnm][$m];
                		        $whdelivery_date = $_POST["delivery_date"][$itmmnm][$m];
                		        
                		        if($whid == 7)
                		        {
                            	    $futureOrder = true;
                            	}
                            	if($whid == 8)
                            	{
                            	    $backOrder = true;
                            	}
                		        
                		        $qryAddQuotationRevisionWarehouse="INSERT INTO `quotation_revisions_warehouse`(`revision_id`,`socode`,pid, `soitem_detail_id`, `qty`, `warehouse`, `expted_deliverey_date`) 
                		                                           VALUES ('".$quotation_revision_id."','".$poid."',$itmmnm,'".$quotation_revision_detail_id."','".$whqty[$m]."','".$whid."',STR_TO_DATE('".$whdelivery_date."', '%d/%m/%Y'))";
                		        
								$qryAddQuotationRevisionWarehouse1 .= $qryAddQuotationRevisionWarehouse;
								
								$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_revisions_warehouse_query_11'][] = $qryAddQuotationRevisionWarehouse;
                		       
                    			if ($conn->query($qryAddQuotationRevisionWarehouse) == TRUE)
                    			{
                                    $err="Quotation Revision Warehouse created successfully";
                                    $quotation_revisions_warehouse_sucess = 1;
                    			}
                    			else
                    			{
                    				$err = 'Error: Quotation Revision Warehouse adding error';
                    				$quotation_revisions_warehouse_sucess = 0;
                    			}
                    			
                    			$_SESSION['mode_'.$mode.'_insert_ordsts_'.$orderstatus]['quotation_revisions_warehouse_result_11'][] = $quotation_revisions_warehouse_sucess;
            		        }
            		    }
						//echo $qryAddQuotationRevisionWarehouse1;die;
            		}
                } 
            } 
        } 

        if($orderstatus == 2)
        {
            $bst = 0;
            if($futureOrder && $backOrder)
            {
                $bst = 5;
            }
            else if($futureOrder)
            {
                    $bst = 3;
            }
            else if($backOrder)
            {
                    $bst = 4;
            }
            $qryStUp = "UPDATE `invoice` SET `backorder`='$bst' WHERE invoiceno = '".$invoiceno."'";
            $conn->query($qryStUp);
        }
    } 
    //Update data
    if($_REQUEST['mode'] == 2)
    { 
        $poid= $_REQUEST['po_id'];
        $quotation_data_id= $_REQUEST['id'];

        //need to delete all items in quotation_detail
        $delqry="delete from quotation_detail  where socode='".$poid."'";
        if ($conn->query($delqry) == TRUE) { $err="Quotation Details deleted successfully";  }
		
        //need to delete all items in quotation_warehouse
         $delqry="delete from quotation_warehouse  where socode='".$poid."'";
        if ($conn->query($delqry) == TRUE) { $err="Quotation Warehouse deleted successfully";  }
		
        //need to delete all items in quotation_warehouse
        if($_SESSION['special_permission'] == 1){ //we have to maintain $_SESSION['special_permission'] session from some point it require;
            $delqry="delete from soitem_warehouse  where socode='".$poid."'";
            if ($conn->query($delqry) == TRUE) { $err="Soitem Warehouse deleted successfully";  }			
        }
        
       

            //Insert Quotation revision
    		//insert in revision table; need revision id to add it in revision detail table during insert;
    		if($orderstatus == 9){
    
    			$qryQuoRev="insert into quotation_revisions (`orderstatus`, `socode`,`customertp`,`organization`,`srctype`,project, `customer`, `orderdate`,`adjustment`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`, `status`,`remarks`,`poc`, note) 
    			values($qotationstatus,'".$poid."','".$custp."','".$org."','".$projtp."','".$proj."','".$sup_id."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'), ".$discntnt.",'".$hrid."','".$totalVATamount."','".$tax."','".$newInvoiceAmount."','".$hrid."','".date("Y-m-d H:i:s")."','".$st."','".$dscr."','".$poc."', '".$note."')";
                
               // $_SESSION['insert_quotation_revisions'] = $qryQuoRev;
                $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_revisions_insert_query_1'] = $qryQuoRev;
               //echo $qryQuoRev;die;
                
    			if ($conn->query($qryQuoRev) == TRUE) { 
    				$err="quotation_revisions added successfully";
    				$quotation_revision_id = $conn -> insert_id;
    				$QtRevInsertSuccess = 1;
    			}else{ 
    				$err = $mysqli -> error;
    				$err = 'Error: Quotation revision adding error';
    				$QtRevInsertSuccess = 0;
    			}
    			//$_SESSION['insert_quotation_revisions_result'] = $QtRevInsertSuccess;
    			$_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_revisions_inert_result_1'] = $QtRevInsertSuccess;
    
    
    				//echo $revision_id;
    		}





            //Udpate quotation table common: for revision and save and for create order
            
            $qryQuotUpdate="UPDATE quotation SET
            `orderstatus`= $qotationstatus, 
            `srctype`='".$projtp."',
            `project`='".$proj."',
            `customertp`='".$custp."',
            `organization`='".$org."',
            `customer`='".$sup_id."',
            `orderdate`=STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),
            `accmanager`='".$hrid."' ,
            `deliveryamt`='".$deliveryamt."',
            `status`='".$st."' ,
            `adjustment`='".$discntnt."',
            `makedt`='".date("Y-m-d H:i:s")."',
            `vat`='".$totalVATamount."' ,
            `invoiceamount`='".$newInvoiceAmount."',
    		`remarks`='".$det."',
    		`note`='".$note."'
             WHERE `socode`='".$poid."'";
    
        	//echo $qryQuotUpdate;die;


            //$_SESSION['update_quotation_query'] = $qryQuotUpdate;
            $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_update_query_2'] = $qryQuotUpdate;
            
			if ($conn->query($qryQuotUpdate) == TRUE){
					$insertSuccess = 1;
					$err = 'Quotation udpated successfully';
			}else{
				$insertSuccess = 0;
				$err = 'Error: Quotation udpate  error';
			}
			
		    //$_SESSION['update_quotation_result'] = $insertSuccess;
		    $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_update_result_2'] = $insertSuccess;
    		//echo $qryQuotUpdate;die;
            //echo count($item);die;
		
		
		
    		//copy data to sale order if quotation is confirmed during insert;
    		if($orderstatus == 2)
    		{ //Create Order
    		
    			//generate invoice no;
    			$invoiceno =  getFormatedUniqueID('invoice','id','INV-',6,"0");
    			
    			/***** VERY IMPORTANT *******/
    			//check here if any data existing in soitem table with $poid; that means, this order is being edited after quotation confirmation; check management permission;
    			//$isAlreadyConfirmedOrder is found, "soitemdetail" , "invoicedetail" and "soitem_warehouse" needed to be delted with this $poid; after passing the permission;
    			
    			$isAlreadyConfirmedOrder = fetchByID('soitem','socode',$poid,'id'); //if found any id, that means order was confirmed before;
    			//echo $isAlreadyConfirmedOrder; die;
    			if($isAlreadyConfirmedOrder){
    			    //edit a confirmed order;
    			    echo "this order was already confirmed";
    			    /*
    			        how to solve this; need a approval list called: "Edit Confirmed Order"
    			        on clicking on any confrimed order edit button a popup with sending request confirmation to excom; yes or no;
    			        if yes it goes to a table "confirmed_order_edit" with column: order_id, user, approved: 0 or 1;
    			        show order number and accept edit or deny;
    			        check "confirmed_order_edit" table before editing executing this portion;
    			    */
    			    die;
    			}else{
    			    
    			    //insert a new confirmed order;
    			    
    			
    			    $qryAddSoitem="insert into soitem(`orderstatus`, `socode`,`customertp`,`organization`,`srctype`,project, `customer`, `orderdate`, `deliveryby`, `deliveryamt`,`adjustment`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`, `status`,`remarks`,`poc`) 
    			    values($qotationstatus,'".$poid."','".$custp."','".$org."','".$projtp."','".$proj."','".$sup_id."','".date("Y-m-d H:i:s")."', '".$deliveryby."',".$deliveryamt.",".$discntnt.",'".$hrid."','".$totalVATamount."','".$tax."','".$newInvoiceAmount."','".$hrid."','".date("Y-m-d H:i:s")."','".$st."','".$det."','".$poc."')";
    
                    $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['soitem_insert_query_3'] = $qryAddSoitem;
                    
        			if ($conn->query($qryAddSoitem) == TRUE){
        				if($res == 0){ //get insert id to loading data in edit mode for revision saved button;
        					$insertSuccess = 1;
        					$err="Quotation transfered to sale order";
        				}		
        			}else{
        				$insertSuccess = 0;
        				$err = 'Error: Quotation transfering to sale order';
        			}
        			$_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['soitem_insert_result_3'] = $insertSuccess;
    			
    			
    			    //CREATE INVOIE
                    $qryInvoice="INSERT INTO `invoice`( `invoiceno`, `invoicedt`,`invyr`, `invoicemonth`, `soid`, `organization`, `invoiceamt`,amount_bdt,adjustment, `paidamount`, `dueamount`,  `invoiceSt`, `paymentSt`, `makeby`,`makedt`) 
                    values('".$invoiceno."','".date("Y-m-d H:i:s")."','".$invyr."','".$invmn."','".$poid."','".$org."','".$newInvoiceAmount."','".$newInvoiceAmount."',".$discntnt.",0,'".$newInvoiceAmount."',1,1,'".$hrid."','".date("Y-m-d H:i:s")."')";
                    // echo $qryinv;die;
                    
                    $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['invoice_insert_query_4'] = $qryInvoice;
                    
                    if ($conn->query($qryInvoice) == TRUE) { $err="Invoice created successfully"; $invoice_insert_success=1;  }else{$invoice_insert_success=0;}
                    
                    $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['invoice_insert_result_4'] = $invoice_insert_success;
    			
    			    
    			} //if($isAlreadyConfirmedOrder)else{
    			
    		}//if($orderstatus == 2)	
		
		    //common order detail table update
            if (is_array($item))
            {
               //echo count($item);die;
                for ($i=0;$i<count($item);$i++)
                {
    				   
    					   $itmsl=$i+1;
    					   $itmmnm=$item[$i];
    					   $descr=$dscr;
    					   $mu=$msu[$i];
    					   $qty=$oqty[$i];
    					   $qtym=$oqtym[$i];
    					   $upo=$unpo[$i]; 
    					   $upm=$unpm[$i]; 
    					   $currnm=$curr_nm[$i];
    					   $itmvat=$vat[$i];
    					   $itmait=0;  
    					   $productprice= $prdprice[$i];
    
    				   		$disc = ($dscnt[$i])?$dscnt[$i]:0; 
    						$disctot= ($dscnttot[$i])?$dscnttot[$i]:0; 				   
    				   
    				   		if($upo==''){$upo=0;}
                            if($upm==''){$upm=0;}
                            
    				   		$amt=($qty*$upo)+($qtym*$upm);
                            $tot_amt=$tot_amt+$disctot;
                            $tot_otc=$tot_otc+($qty*$upo);
                            $vatrt=$vatarr[$i];
                            $aitrt=$aitarr[$i];
                            $vata = $vatarr[$i]*($disctot)*0.01;
                            $aita = $aitarr[$i]*($disctot)*0.01;
                            $invamt=$tot_otc+$vata+$aita-$disctot;
                            $totvat=$totvat+$vata;
                            $totait=$totait+$aita;
           					$prdcost = fetchByID('item','id',$itmmnm,'cost');
                            
                            
                            
                            $itqry="insert into quotation_detail( `socode`,`sosl`, `productid`,`remarks`, `mu`, `qty`,vatrate,`vat`, `otc`,aitrate, `ait`,cost,discountrate,discounttot,`currency`, `makeby`, `makedt`)
                                    values( '".$poid."','".$itmsl."','".$itmmnm."','".$descr."','".$mu."','".$qty."','".$vatrt."','".$vata."','".$upo."','".$aitrt."','".$aita."',".$prdcost.",".$disc.",".$disctot.",'".$currnm."','".$hrid."','".date("Y-m-d H:i:s")."')";
                           // echo $itqry;die;
    				   		$qry2 .=  $itqry."<br>";
    				   		
    				   		//$_SESSION['insert_quotation_detail_query'][] = $itqry;
    				   		$_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_detail_insert_query_5'][] = $itqry;
    
                             if ($conn->query($itqry) == TRUE) { 
                                    $err="Quotation detail added successfully";  
                                    $quotation_details_id = $conn -> insert_id;
                                    $iqdsuccess = 1;
                                 }else{
                                     $iqdsuccess = 0;
                                 }
                                 
                                 //$_SESSION['insert_quotation_detail_result'][] = $iqdsuccess ;
                                 $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_detail_insert_result_5'][] = $iqdsuccess;
                             
     
                            //add revision detail if status is 9
    						if($orderstatus == 9)
    						{ /* if saved as revision during update */
                           	
      							$qryinvdet="insert into quotation_revisions_detail ( `socode`,`revision_id`,`sosl`, `productid`,`remarks`, `mu`, `qty`,vatrate,`vat`, `otc`,aitrate, `ait`,cost,discountrate,discounttot,`currency`, `makeby`, `makedt`) 
                             				values( '".$poid."','".$quotation_revision_id."','".$itmsl."','".$itmmnm."','".$descr."','".$mu."','".$qty."','".$vatrt."','".$vata."','".$upo."','0','0',".$prdcost.",".$disc.",".$disctot.",'".$currnm."','".$hrid."','".date("Y-m-d H:i:s")."')";
                                 $qry1 .=  $qryinvdet."<br>";
                                 
                                 //$_SESSION['insert_quotation_revisions_detail_query'][] = $qryinvdet;
                                 $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_revisions_detail_insert_query_6'][] = $qryinvdet;
                                 
                                 if ($conn->query($qryinvdet) == TRUE) { $err="invoice added successfully"; $quotation_revisions_detail_id = $conn->insert_id; $iqrd_success = 1;}else{$iqrd_success = 0;}
                                  //$_SESSION['insert_quotation_revisions_detail_result'][] = $iqrd_success;
                                  $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_revisions_detail_insert_result_6'][] = $iqrd_success;
                               
                              
                                 //Add Warehouse info into Revision DB
                        		 if($QtRevInsertSuccess == 1)
                        		 {
                        		    $whqty = $_POST["whqty"][$itmmnm];
                        		    
                        		    for($m = 0; $m < count($whqty); $m++)
                        		    {
                        		        
                        		        // 0 quantity check which is not selected
                        		        if($whqty[$m] > 0)
                        		        {
                            		        $whid = $_POST["whid"][$itmmnm][$m];
                            		        $whdelivery_date = $_POST["delivery_date"][$itmmnm][$m];
                            		        
                            		        if($whid == 7){
                                        	    $futureOrder = true;
                                        	}
                                        	if($whid == 8){
                                        	    $backOrder = true;
                                        	}
    									
                            		        $qryAddQuotationRevisionWarehouse="INSERT INTO `quotation_revisions_warehouse`(`revision_id`,`socode`,pid,  `soitem_detail_id`, `qty`, `warehouse`, `expted_deliverey_date`) 
                            		                                           VALUES ('".$quotation_revision_id."','".$poid."',$itmmnm,'".$quotation_revisions_detail_id."','".$whqty[$m]."','".$whid."',STR_TO_DATE('".$whdelivery_date."', '%d/%m/%Y'))";
    										
                            		        //$_SESSION['insert_quotation_revisions_warehouse_query'][] = $qryAddQuotationRevisionWarehouse;
                            		        $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_revisions_warehouse_insert_query_6'][] = $qryAddQuotationRevisionWarehouse;
                            		        
                                			if ($conn->query($qryAddQuotationRevisionWarehouse) == TRUE){
                                                $err="Quotation Revision Warehouse created successfully";
                                                $iqrw_success = 1;
                                			}else{
                                				$err = 'Error: Quotation Revision Warehouse adding error';
                                				$iqrw_success = 0;
                                			}
                                			//$_SESSION['insert_quotation_revisions_warehouse_result'][] = $iqrw_success;
                                			$_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_revisions_warehouse_insert_result_6'][] = $iqrw_success;
                        		        }//if($whqty[$m] > 0)
                        		    }//for($m = 0; $m < count($whqty); $m++)
                        		}//if($QtRevInsertSuccess == 1)
    						
    						
    						
    						} //if($orderstatus == 9)
    				   
    				   
    				   
    				   
    							
    						//Put data in quotation_warehouse, common
    						$whqty = $_POST["whqty"][$itmmnm];
    						
    						//print_r($_POST["whqty"]);die;
    
    						for($m = 0; $m < count($whqty); $m++){
    
    							// 0 quantity check which is not selected
    							if($whqty[$m] > 0){
    
    								$whid = $_POST["whid"][$itmmnm][$m];
    								$whdelivery_date = $_POST["delivery_date"][$itmmnm][$m];
    								
    								if($whid == 7){
                                    	    $futureOrder = true;
                                    	}
                                    	if($whid == 8){
                                    	    $backOrder = true;
                                    	}
                                    	
    								$quotationWarehouseQry = "INSERT INTO `quotation_warehouse`(`socode`, pid, `soitem_detail_id`, `warehouse`, `qty`, `expted_deliverey_date`) 
    																	 VALUES ('".$poid."',$itmmnm,'".$quotation_details_id."','".$whid."','".$whqty[$m]."',STR_TO_DATE('".$whdelivery_date."', '%d/%m/%Y'))";
    							//	echo $quotationWarehouseQry;die;
    								 //$_SESSION['insert_quotation_warehouse_query'][] = $quotationWarehouseQry;
    								 $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_warehouse_insert_query_7'][] = $quotationWarehouseQry;
    								 
    								if ($conn->query($quotationWarehouseQry) == TRUE){
    									$err="Sotiem Warehouse created successfully";
    									$iqw_success = 1;
    								}else{
    									$err = 'Error: Soitem Warehouse adding error';
    									$iqw_success = 0;
    								}
    								//$_SESSION['insert_quotation_warehouse_result'][] = $iqw_success;
    								$_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['quotation_warehouse_insert_query_7'][] = $iqw_success;
    							}
    						}				   
    				   
    				   
    				   
    				   
    						//copy quotatoin data to sale order table;
    						if($orderstatus == 2){//Create Order 
    						
    						 // NOTE: following data needed to be delted first if it is a editing of a "confirmed order" by granted permission;
    							
        							//put data to soitemdetails if confirmed
        							$soitemqry="INSERT INTO soitemdetails ( `socode`,`sosl`, `productid`,`remarks`, `mu`, `qty`,vatrate,`vat`, `otc`,aitrate, `ait`,cost,discountrate,discounttot,`currency`, `makeby`, `makedt`) 
                                 	values( '".$poid."','".$itmsl."','".$itmmnm."','".$descr."','".$mu."','".$qty."','".$vatrt."','".$vata."','".$upo."','0','0',".$prdcost.",".$disc.",".$disctot.",'".$currnm."','".$hrid."','".date("Y-m-d H:i:s")."')";
        							
        							$_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['soitemdetails_insert_query_8'][] = $soitemqry;
        							
        							if ($conn->query($soitemqry) == TRUE) { 
        							    $err="Quotation detail transfered to sale order"; 
        							    $soitem_details_id = $conn -> insert_id;
        							    $sid_success = 1;
        							    
        							}else{$sid_success = 0;}
        							$_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['soitemdetails_insert_result_8'][] = $sid_success;
    							
        							//put data into invoice table if confirmed
        							
        							//put data to invoice detial if confirmed
        
                           
                                    $invQuery="INSERT INTO `invoicedetails`( `socode`,`invoiceno`, `sosl`, `billtype`, `invoicemoth`, `invoiceyr`, `invoicedt`, `product`, `qty`, `amount`,discountrate,discounttot,vat,ait,`currency`, `makeby`, `makedt`) 
                                    values('".$poid."','".$invoiceno."','".$itmsl."',1,'".$invmn."','".$invyr."','".date("Y-m-d H:i:s")."','".$itmmnm."','".$qty."','".$upo."',".$disc.",".$disctot.",'".$vata."','0','".$currnm."','".$hrid."','".date("Y-m-d H:i:s")."')";
                                    // echo $invQuery;die;
                                    
                                    $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['invoicedetails_insert_query_9'][] = $invQuery;
                                    
                                     if ($conn->query($invQuery) == TRUE) { $err="invoice added successfully"; $invdtl_success=1;  }else{$invdtl_success=0;}
                                     
                                     $_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['invoicedetails_insert_result_9'][] = $invdtl_success;
    							
    							
    								//Put data in soitemdetails warehouse
    								$whqty = $_POST["whqty"][$itmmnm];
    
    								for($m = 0; $m < count($whqty); $m++)
    								{
    
    									// 0 quantity check which is not selected
    									if($whqty[$m] > 0){
    
    										$whid = $_POST["whid"][$itmmnm][$m];
    										$whdelivery_date = $_POST["delivery_date"][$itmmnm][$m];
    										
    										if($whid == 7){
                                        	    $futureOrder = true;
                                        	}
                                        	if($whid == 8){
                                        	    $backOrder = true;
                                        	}
                                    	
    										$soitemWarehouseQry = "INSERT INTO `soitem_warehouse`(`socode`, pid, `soitem_detail_id`, `warehouse`, `qty`, `expted_deliverey_date`) 
    																			 VALUES ('".$poid."',$itmmnm,'".$soitem_details_id."','".$whid."','".$whqty[$m]."',STR_TO_DATE('".$whdelivery_date."', '%d/%m/%Y'))";
    										//echo $soitemWarehouseQry;die;
    										
    										$_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['soitem_warehouse_insert_query_10'][] = $soitemWarehouseQry;
    										
    										
    										if ($conn->query($soitemWarehouseQry) == TRUE){
    											$err="Sotiem Warehouse created successfully";
    											$siwh_success = 1;
    										}else{
    											$err = 'Error: Soitem Warehouse adding error';
    											$siwh_success = 0;
    										}
    										$_SESSION['mode_'.$mode.'_edit_ordsts_'.$orderstatus]['soitem_warehouse_insert_result_10'][] = $siwh_success;
    									} //if($whqty[$m] > 0){
    								}//for($m = 0; $m < count($whqty); $m++){							
    							
    							
    						}	//if($orderstatus == 2){			   
    				   
    				   
    				   		
                       
                } // for ($i=0;$i<count($item);$i++)
               
			  	//echo  $qry1."<hr>".$qry2;die;
			  		
			  
               
            } //if (is_array($item))
            
            if($orderstatus == 2)
            {
                    $bst = 0;
                    if($futureOrder && $backOrder){
                        $bst = 5;
                    }else if($futureOrder){
                        $bst = 3;
                    }
                    else if($backOrder){
                        $bst = 4;
                    }
                    $qryStUp = "UPDATE `invoice` SET `backorder`='$bst' WHERE invoiceno = '".$invoiceno."'";
                    $conn->query($qryStUp);
            } //if($orderstatus == 2)
        
        
    } 
			
	if($_REQUEST['postaction']=='Update')
	{
		//1 job: return to list;
		$err = "Quotation updated successfully";
		header("Location: ".$hostpath."/quotationList.php?res=1&msg=".$err."&id=".$poid."&mod=3&pg=1&changedid=".$poid);
	}	
	if($_REQUEST['postaction']=='Save as Revision')
	{
		//1 job: return to list;
		$err = "Revision saved successfully";
		header("Location: ".$hostpath."/quotationEntry.php?res=4&msg=".$err."&id=".$quotation_data_id."&mod=3&changedid=".$quotation_revision_id);
	}
	if($_REQUEST['postaction']=='Save')
	{
            
		$err = "Quotation saved successfully";
		header("Location: ".$hostpath."/quotationList.php?res=1&msg=".$err."&id=".$poid."&mod=3&pg=1&changedid=".$poid);
	}
	if($_REQUEST['postaction']=='Create Order')
	{
        if($gift == 1)
        {
            if($deliveryamt <= 0){
                $paidGift = ", paymentSt='4' ";
            }
            //Update invoice
            $qryGiftUpdate = "UPDATE `invoice` SET `paidamount`= invoiceamt - $deliveryamt,`dueamount`='$deliveryamt',`approval`='3' $paidGift WHERE `invoiceno` = '$invoiceno'";
            $conn->query($qryGiftUpdate);
            
            $err = "Gift Order Created successfully";
            
            //Mail to Management
            $qrymail = "SELECT id,active FROM `email` WHERE id = 36";
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
                    $mailsubject = "Approval Required for Gift Order";
                    $message = "An approval request for Gift Order $poid was received.";
                                
                    sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                }
            }
            
		    header("Location: ".$hostpath."/giftquotationList.php?res=1&msg=".$err."&id=".$poid."&mod=3&pg=1&changedid=".$poid);
        }
        else
        {
            $err = "Order Created successfully";
		    header("Location: ".$hostpath."/quotationList.php?res=1&msg=".$err."&id=".$poid."&mod=3&pg=1&changedid=".$poid);
        }        	
		
	}				
    $conn->close();
}//if(!isset($_SESSION['user'])){
?>