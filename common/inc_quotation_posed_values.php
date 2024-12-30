<?php

    //print_r($_POST);
    //die('<hr>');
    
        
    

		
    	
    	
    	if($debug==1){
    		echo "Subtotal:".$TotalAmountWithDiscount."<br>";
    		echo "Total VAT :".$totalVATamount."<br>";
    		echo "Total Discount:".$TotalDiscountAmount."<br>";
    		echo "Adjustment:".$adjustment."<br>";
    		echo " Delivery Charge :".$deliveryamt."<br>";
    		echo "Total:".$newInvoiceAmount."<br>";
    		die;
    	}
	    
        function mysqlDate($sourceDate){
            $dateTime = DateTime::createFromFormat('d/m/Y', $sourceDate);
            if ($dateTime) {
                return $dateTime->format('Y-m-d');
            } else {
                // Handle the invalid date case
                return false; // or you can return an error message
            }
        }

    $user = $_SESSION['user'];
    $q_dataid = ($_REQUEST['q_dataid'])?$_REQUEST['q_dataid']:"";   //existing quotation table id; lets call it dataid;
    
    $formattedDate = mysqlDate($_POST['po_dt']);
    if ($formattedDate) {
        $formattedDate = $formattedDate; // Output: 2024-07-24
    } else {
        $formattedDate =''; // Handle the error case
    } 

    $poid = $_POST['po_id'];
    $orderDate = $formattedDate;
    $saleType =  $_POST['saletype'];
    $projectName =  $_POST['project_name'];
    $projectId =  $_POST['project_id'];
    $companyId = $_POST['org_id'];
    $companyName = $_POST['org_name'];
    $contactId = $_POST['cmbsupnm'];
    $createdDate = date("Y-m-d H:i:s");
    
    //total fields in quotation form
    $totalDiscountForm = $_POST['discountdsp'];
    $adjustmentForm = $_POST['discntnt'];
    $totalVatFrom = $_POST['vatdis'];
    $deliveryChargeForm = $_POST['deliveryamt'];
    $deliveryAddress = $_POST['details'];
    $note = $_POST['note'];
    $orderstatus = 1; //save, save as revision; 
    
    $invoiceMonth = substr($orderDate,3,2);
    $invoiceYear = substr($orderDate,6,4);	
  
  //echo $orderDate;die;

  
  
  
  

    

    //array data for quotation_detail
	$itemIds = $_POST['itemName']; // item array
	$vatRates = $_POST['vat']; // item array    
    $unitPrices = ($_POST['unitprice_otc'])?$_POST['unitprice_otc']:0;
    $quantities = $_POST['quantity_otc']; // total qty, not warehouse wise; not in use
    $unitTotalPrices = $_POST['total'];
    $discountPercentage = $_POST['discnt'];
    $discountedPrice = $_POST['unittotal'];
    $discntamnt = ($_POST['discntamnt'])?$_POST['discntamnt']:0;
    $unittotal1 = $_POST['unittotal1']; 
    //print_r($unittotal1);die; 
      
        $index = 0;
		foreach($vatRates as $vatrate){
		    
			$unitTotal = $quantities[$index]*$unitPrices[$index];
    		$discountAmount = ($unitTotal*$discountPercentage[$index])/100;
    		//$TotalDiscountAmount += $discountAmount[$index];
    		$TotalDiscountAmount += $discntamnt[$index];
    		$AmountWithDiscount = $unitTotal - $discountAmount;
    		$TotalAmountWithDiscount +=$AmountWithDiscount;
    		$totalVATamount +=	 ($vatrate*$AmountWithDiscount)/100;
    		$totalProdcutAmount  += ($quantities[$index]*$unitPrices[$index]);
    		$totalProdcutDiscountAmount  += ($discountPercentage[$index]*($quantities[$index]*$unitPrices[$index]))/100;
    		
    		$totalPayable += $unittotal1[$index];
    		
    		$index++;
    		
    	}
		//echo $totalProdcutAmount;die;
		
		//echo $TotalDiscountAmount;die;
		
		$invoiceamount = (($totalProdcutAmount-$TotalDiscountAmount) + $totalVATamount) + $deliveryChargeForm;
		//echo $totalPayable;die;
		$newInvoiceAmount = $invoiceamount-$adjustmentForm;
      
      
    //create $inputOrderData array
 
    $inputOrderData = array(
         'TableName' => 'quotation',
         'FetchByKey' => 'id',
         'FetchByValue' =>  $q_dataid,
         
        'socode' => $poid,
        'customertp' => 2, //contact type hard coded from previous code;
        'organization' => $companyId,
        'srctype' => $saleType,
        'project' => $projectId,
        'customer' => $contactId,
        'orderdate' => $orderDate,
        'deliveryamt' => $deliveryChargeForm,
        'adjustment' => $adjustmentForm,
        'totalDiscount' => $TotalDiscountAmount,
        'accmanager' => $user, 
        'vat' => $totalVATamount, 
        //'invoiceamount' => $newInvoiceAmount, 
        'invoiceamount' => $totalPayable, 
        'makeby' => $user, 
        'makedt' => date("Y-m-d H:i:s"),
        'remarks' => $deliveryAddress,
        'poc' => $user,
        'note' => $note
    );      
    
    $inputInvoiceData  = array(
         'TableName' => 'invoice',
         'FetchByKey' => 'id',
         'FetchByValue' =>  $q_dataid,
         
        'invoiceno' => $invoiceno,
        'invoicedt' => date("Y-m-d H:i:s"),
        'invyr' => $invoiceYear,
        'invoicemonth' => $invoiceMonth,
        'soid' => $poid,	
        'organization' => $companyId,
        //'invoiceamt' => $newInvoiceAmount,
        'invoiceamt' => $totalPayable,
        'amount_bdt' => $newInvoiceAmount, 
        'adjustment' => $adjustmentForm,	
        'paidamount' => 0,
        'dueamount' => $totalPayable, 	
        'invoiceSt' => 1,	
        'paymentSt' => 1,	
        'makeby' => $user, 
        'makedt' => date("Y-m-d H:i:s")
    );      
      
    
    
     
    //loop product info 
    for($i=0;$i<count($itemIds);$i++){
       
        $itemSl=$i+1;
    
        $inputOrderDetailData[] = array(
    
            'TableName' => 'quotation_detailxxssaa',
            'FetchByKey' => 'id',
            'FetchByValue' =>  $q_dataid,
         
            'socode' => $poid,
            'sosl' => $itemSl, 
            'productid' =>  $itemIds[$i],
            'qty' => $quantities[$i],
            'vatrate' =>  $vatRates[$i],
            //'vat' => $vata = $vatRates[$i]*($discountedPrice[$i])*0.01, 
            'vat' => $vata = $vatRates[$i]*($unitPrices[$i])*0.01, 
            'otc' =>  $unitPrices[$i],
            'aitrate' => 0, //not sure why
            'ait' =>  0, //not sure why
            'cost' =>  fetchByID('item','id',$itemIds[$i],'cost'),
            'discountrate' =>  $discountPercentage[$i],
            'discount_amount' =>  $discntamnt[$i],
            'discounttot' =>  $discountedPrice[$i],
            'makeby' => $user, 
            'makedt' => date("Y-m-d H:i:s")
         
        );
        
        
    $inputInvoiceDetailData[] = array(
    
            'TableName' => 'invoicedetails', //dynamic value
            'FetchByKey' => 'id',
            'FetchByValue' =>  $q_dataid,
         
            'socode' => $poid,
            'invoiceno' => $invoiceno, //dynamic value
            'sosl' => $itemSl,	
            'billtype' => 1,	
            'invoicemoth' => $invoiceMonth,	
            'invoiceyr' => $invoiceYear,		
            'invoicedt' => date("Y-m-d H:i:s"),
            'product' =>  $itemIds[$i],		
            'qty' => $quantities[$i],	
            'amount' =>  $unitPrices[$i],
            'discountrate' =>  $discountPercentage[$i],
            'discount_amount' =>  $discntamnt[$i],
            'discounttot' =>  $discountedPrice[$i],	
            //'vat' => $vata = $vatRates[$i]*($discountedPrice[$i])*0.01,
            'vat' => $vata = $vatRates[$i]*($unitPrices[$i])*0.01,
            'ait' =>  0,	
            'makeby' => $user,
            'makedt' => date("Y-m-d H:i:s"),       

        );
        
        
        
        //warehouse array
        
        $whqty = $_POST["whqty"][$itemIds[$i]];
        $whid = $_POST["whid"][$itemIds[$i]];
        $delivery_date = $_POST["delivery_date"][$itemIds[$i]];
        
        for($m = 0; $m < count($whqty); $m++){
            if($whqty[$m] > 0){
            

		        
                $inputWarehouseData[] = array(
            
                    'TableName' => 'quotation_warehouse',
                    'FetchByKey' => 'id',
                    'FetchByValue' =>  $q_dataid,
                 
                    'socode' => $poid,
                    'pid' => $itemIds[$i], 
                    'soitem_detail_id' =>  '', // will be sent from $insertIdQuotationDetail
                    'warehouse' => $whid[$m],
                    'qty' => $whqty[$m],
                    'expted_deliverey_date' => date('Y-m-d', strtotime($delivery_date[$m]))
                 
                );		        
		        
		        
                
            }
        }
        
        
       
        
      
        
        
    }//for($i=0;$i<count($itemIds);$i++){
        
        
    function getWarehouseArray($inputWarehouseData){
        
       global  $itemIds, $poid, $q_dataid, $_POST;

           $count = 0;
            for($i=0;$i<count($itemIds);$i++){
                
                //warehouse array
                $whqty = $_POST["whqty"][$itemIds[$i]];
                $whid = $_POST["whid"][$itemIds[$i]];
                $delivery_date = $_POST["delivery_date"][$itemIds[$i]];                
                
                for($m = 0; $m < count($whqty); $m++){
                    if($whqty[$m] > 0){
                        //$inputWarehouseData1[$count]['soitem_detail_id'] = $insertIdQuotationDetail;
                        //$inputWarehouseData1[$count]['TableName'] = 'newtable';
                        
                        
                        $inputWarehouseData[] = array(
                    
                            'TableName' => $inputWarehouseData['TableName'],
                            'FetchByKey' => 'id',
                            'FetchByValue' =>  $q_dataid,
                         
                            'socode' => $poid,
                            'pid' => $itemIds[$i], 
                            'soitem_detail_id' =>  $inputWarehouseData['soitem_detail_id'], // will be sent from $insertIdQuotationDetail
                            'warehouse' => $whid[$m],
                            'warehouseQty' => $whqty[$m],
                            'warehouseDeliveryDate' => $delivery_date[$m]
                         
                        );
                        
                        insertData($inputWarehouseData,$msg,$success,$insertIdWH);
                        $_SESSION['debug']['log_'.$inputWarehouseData['TableName']][] = $msg; 
                        
                        $count++;
                    }
                }
            }        
        
        
        
       return $inputWarehouseData;
    }
        
        
        

        
        
        
        
        
        
        
        
        
        
        
?>