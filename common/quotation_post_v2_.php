<?php
    session_start();
    //quotation_post_v2.php created on Feb10 2024;
    //Version: 2

    $debug = 0;
    if($debug == 1){
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    }   
 





    // Remove keys with values equal to 0 or null from the entire $_POST array
    
    //removeEmptyOrNull($_POST);

    function filterPostValues($postData) {
        foreach ($postData['whqty'] as $key => $qtyArray) {
            foreach ($qtyArray as $index => $qty) {
                // Check if the quantity is zero
                if ($qty == 0) {
                    // Remove the corresponding whid, whqty, and delivery_date entries
                    unset($postData['whid'][$key][$index]);
                    unset($postData['whqty'][$key][$index]);
                    unset($postData['delivery_date'][$key][$index]);
                }
            }
    
            // Reindex the arrays to remove gaps in indices
            $postData['whid'][$key] = array_values($postData['whid'][$key]);
            $postData['whqty'][$key] = array_values($postData['whqty'][$key]);
            $postData['delivery_date'][$key] = array_values($postData['delivery_date'][$key]);
        }
    
        return $postData;
    }

    $_POST = filterPostValues($_POST);


    
    // Output the modified array
    //print_r($_POST);die;
     
    
    
    
    require "conn.php";
    //include_once('email_config.php');
    include_once('../email_messages/email_user_message.php');
    require_once('phpmailer/PHPMailerAutoload.php');
    include_once('../rak_framework/fetch.php');
    require_once("../rak_framework/edit.php");
    require_once("../rak_framework/insert.php");
    require_once("../rak_framework/misfuncs.php");
    require_once("../rak_framework/delete.php");
    

    
    // all posted data received and database post arrays are created here for code clean;
    require_once('inc_quotation_posed_values.php');
    
    //main params
    

        
    //dd($_POST);
    //die;

    
    $postaction = $_REQUEST['postaction'];  //Save, Revision, Order
    $mode = $_REQUEST['mode'];  //insert (put), update (put)
    //echo $mode."<br>";
    //echo $postaction."<br>";  
    //die;
    

   // die("<hr>");

if(!isset($_SESSION['user'])){
    
     header("Location: ".$hostpath."/hr.php");
}
else
{
    
    //all codes starts from here;
    
    
    
    //$postaction = Save;
    
    if($postaction == 'Save' || $postaction == 'Revision' || $postaction == 'Order'){
        
        

        //action 1. insert/update into quotation/quotation_detail/quotation_warehouse table
        
        if($mode == 'insert'){
            
            $_SESSION['debug'] = "";
            
            
            // Quotation ID generated
    		if($gift == 1){
    		    //sample call //$id = getFormatedUniqueID($table,$idcolumn,$prefix,$numberlen,$padding)
    		    $poid = getFormatedUniqueID('quotation','id','GIFT-',6,"0");
    		}else{
    		    $poid = getFormatedUniqueID('quotation','id','QT-',6,"0");
    		}            

            
            
            //### insert quotation data;
            $inputOrderData['TableName'] = 'quotation';
            $inputOrderData['socode'] = $poid;
            $inputOrderData['orderstatus'] = 1; // Save, Save as revision
            
            insertData($inputOrderData,$msg,$success,$insertIdQuatation);
            $quatationID = $insertIdQuatation;
            
            $response['order_result'] = $success;
            $response['order_msg'] = $msg;
            $response['result'][] = $success;
            
            $_SESSION['debug']['log_'.$inputOrderData['TableName']] = $msg;
            
            //Save quotation product data; 
            foreach($inputOrderDetailData as $quotationDetailData){
                
                $quotationDetailData['TableName'] = 'quotation_detail';
                $quotationDetailData['socode'] = $poid;
                
                //print_r($quotationDetailData);die;
                
                insertData($quotationDetailData,$msg,$success,$insertId);
                $response['product_result'] = $success;
                $response['result'][] = $success;
                $response['product_msg'] = $msg;                
                
                $_SESSION['debug']['log_'.$quotationDetailData['TableName']][] = $msg;



                $whqty = $_POST["whqty"][$quotationDetailData['productid']];
                $whid = $_POST["whid"][$quotationDetailData['productid']];
                $delivery_date = $_POST["delivery_date"][$quotationDetailData['productid']];  

                for($m = 0; $m < count($whqty); $m++){
                    //echo 'wh:'.$whid[$m].'qt: '.$whqty[$m].'<br>';
                    $inputWarehouseData[$m] = array('TableName' => 'quotation_warehouse','soitem_detail_id' => $insertId);
                    
                    
                    $inputWarehouseData = array(
                
                        'TableName' => 'quotation_warehouse',
                        'FetchByKey' => 'id',
                        'FetchByValue' =>  '',
                     
                        'socode' => $poid,
                        'pid' => $quotationDetailData['productid'], 
                        'soitem_detail_id' =>  $insertId, // will be sent from $insertIdQuotationDetail
                        'warehouse' => $whid[$m],
                        'qty' => $whqty[$m],
                        'expted_deliverey_date' => mysqlDate($delivery_date[$m])
                     
                    );
                    
                    insertData($inputWarehouseData,$msg,$success,$insertIdWH);
                    $response['result'][] = $success;
                    $response['warehouse_result'] = $success;
                    $response['warehouse_msg'] = $msg;                
                    
                    $_SESSION['debug']['log_'.$inputWarehouseData['TableName']][][] = $msg;
                    
                    $arra[] = $inputWarehouseData;
                }
                
            }  
            
            

            
            


        } // if($mode == 'insert'){ 
        
        
        if($mode == 'update'){
            //orderstatus need to be controlled here;
            
            $inputOrderData['TableName'] = 'quotation';
            $inputOrderData['socode'] = $poid;
            $inputOrderData['orderstatus'] = 1; // Save, Save as revision
            updateData($inputOrderData,$msg,$success);
           // echo $msg; die;
            
            //delete old orderdetail info
            $condition = 'socode = "'.$poid.'"';
            deleteRowByCondition('quotation_detail',$condition,$msg,$retVal);
            deleteRowByCondition('quotation_warehouse',$condition,$msg,$retVal); 
            //die;
            //Save quotation product data; 
            
            
            foreach($inputOrderDetailData as $quotationDetailData){
                

                
                $quotationDetailData['TableName'] = 'quotation_detail';
                $quotationDetailData['socode'] = $poid;
                insertData($quotationDetailData,$msg,$success,$insertId);
                $response['product_result'] = $success;
                $response['result'][] = $success;
                $response['product_msg'] = $msg;                
                
                $_SESSION['debug']['log_'.$quotationDetailData['TableName']][] = $msg;



                $whqty = $_POST["whqty"][$quotationDetailData['productid']];
                $whid = $_POST["whid"][$quotationDetailData['productid']];
                $delivery_date = $_POST["delivery_date"][$quotationDetailData['productid']];  
                
                
                
                for($m = 0; $m < count($whqty); $m++){
                    

                    //$inputWarehouseData[$m] = array('TableName' => 'quotation_warehouse','soitem_detail_id' => $insertId);
                    
                    
                    $inputWarehouseData = array(
                
                        'TableName' => 'quotation_warehouse',
                        'FetchByKey' => 'id',
                        'FetchByValue' =>  '',
                     
                        'socode' => $poid,
                        'pid' => $quotationDetailData['productid'], 
                        'soitem_detail_id' =>  $insertId, // will be sent from $insertIdQuotationDetail
                        'warehouse' => $whid[$m],
                        'qty' => $whqty[$m],
                        'expted_deliverey_date' => mysqlDate($delivery_date[$m])
                     
                    );
                    
                    
                    
                    insertData($inputWarehouseData,$msg,$success,$insertIdWH);
                    $response['result'][] = $success;
                    $response['warehouse_result'] = $success;
                    $response['warehouse_msg'] = $msg;                
                    
                    $_SESSION['debug']['log_'.$inputWarehouseData['TableName']][][] = $msg;
                    
                    $arra[] = $inputWarehouseData;
                }
                
            }  
            

        }//if($mode == 'update'){    
        
        
        
            
    }//if($postaction == 'Save' || $postaction == 'Save as Revision'){
        
 
 

 
 
    if($postaction == 'Revision'){
        
        

        //action 2. insert into quotation_revision/quotation_detail_revision/quotation_warehouse_revision table
        
        if($mode == 'insert'  || $mode == 'update'){
            

            //### insert quotation data;
            $inputOrderData['TableName'] = 'quotation_revisions';
            $inputOrderData['socode'] = $poid;
            $inputOrderData['orderstatus'] = 1; // Save, Save as revision
            
            insertData($inputOrderData,$msg,$success,$insertIdQuatation);
            $response['order_rev_result'] = $success;
            $response['order_rev_msg'] = $msg;
            $response['result'][] = $success;
            
            $_SESSION['debug']['log_rev'.$inputOrderData['TableName']] = $msg;
            
            //Save quotation product data; 
            foreach($inputOrderDetailData as $quotationDetailData){
                
                $quotationDetailData['TableName'] = 'quotation_revisions_detail';
                $quotationDetailData['socode'] = $poid;
                $quotationDetailData['revision_id'] = $insertIdQuatation;
                insertData($quotationDetailData,$msg,$success,$insertId);
                $response['product_rev_result'] = $success;
                $response['product_rev_msg'] = $msg;
                $response['result'][] = $success;
                
                $_SESSION['debug']['log_'.$quotationDetailData['TableName']][] = $msg;



                $whqty = $_POST["whqty"][$quotationDetailData['productid']];
                $whid = $_POST["whid"][$quotationDetailData['productid']];
                $delivery_date = $_POST["delivery_date"][$quotationDetailData['productid']];  

                for($m = 0; $m < count($whqty); $m++){
                    //echo 'wh:'.$whid[$m].'qt: '.$whqty[$m].'<br>';
                   // $inputWarehouseData[$m] = array('TableName' => 'quotation_revisions_warehouse','soitem_detail_id' => $insertId);
                    
                    
                    $inputWarehouseData = array(
                
                        'TableName' => 'quotation_revisions_warehouse',
                        'FetchByKey' => 'id',
                        'FetchByValue' =>  '',
                     
                        'revision_id' => $insertIdQuatation,
                        'socode' => $poid,
                        'pid' => $quotationDetailData['productid'], 
                        'soitem_detail_id' =>  $insertId, // will be sent from $insertIdQuotationDetail
                        'warehouse' => $whid[$m],
                        'qty' => $whqty[$m],
                        'expted_deliverey_date' => mysqlDate($delivery_date[$m])
                     
                    );
                    
                    insertData($inputWarehouseData,$msg,$success,$insertIdWH);
                    $response['result'][] = $success;
                    $response['warehouse_result'] = $success;
                    $response['warehouse_msg'] = $msg;                
                    
                    $_SESSION['debug']['log_'.$inputWarehouseData['TableName']][][] = $msg;
                    
                    $arra[] = $inputWarehouseData;
                }
                
            }  
            
            

            
            


        } // if($mode == 'insert'){ 
        
  
            
    }//if($postaction == 'Save' || $postaction == 'Revision'){
 
 
    
     
 
     if($postaction == 'Order'){
        
        //$inputOrderData = array();
        //print_r($_POST);


        //action 3. insert into soitem/soitemdetails/soitem_warehouse/invoice/invoicedetails table
        
        
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
		        
		        make $ECApproved = 1 here to update by checking permission module;
		    */
		    die;
		    $isNewOrder = 0;
		}else{        
            $isNewOrder = 1;
		}
		
		//echo $isNewOrder; die;
        
        if($isNewOrder == 1){ // pass this condition to insert new order info;
            if($mode == 'insert'  || $mode == 'update'){
                
                //generate invoice no;
                $invoiceno =  getFormatedUniqueID('invoice','id','INV-',6,"0");
    
                //### insert quotation data;
                $inputOrderData['TableName'] = 'soitem';
                $inputOrderData['socode'] = $poid;
                $inputOrderData['orderstatus'] = 1; // Save, Save as revision
                
                insertData($inputOrderData,$msg,$success,$insertIdQuatation);
                $response['order_soitem_result'] = $success;
                $response['order_soitem_msg'] = $msg;
                $response['result'][] = $success;
                
                $_SESSION['debug']['log_rev'.$inputOrderData['TableName']] = $msg;
                
                //insert invoice data;
                $inputInvoiceData['soid'] = $poid;
                $inputInvoiceData['invoiceno'] = $invoiceno;
                insertData($inputInvoiceData,$msg,$success,$insertIdInvoice);
                $response['result'][] = $success;
                
                
                //Save quotation product data; 
                foreach($inputOrderDetailData as $quotationDetailData){
                    
                    $quotationDetailData['TableName'] = 'soitemdetails';
                    $quotationDetailData['socode'] = $poid;
                    insertData($quotationDetailData,$msg,$success,$insertId);
                    $response['product_rev_result'] = $success;
                    $response['product_rev_msg'] = $msg;
                    $response['result'][] = $success;
                    
                    $_SESSION['debug']['log_'.$quotationDetailData['TableName']][] = $msg;
    
                    $whqty = $_POST["whqty"][$quotationDetailData['productid']];
                    $whid = $_POST["whid"][$quotationDetailData['productid']];
                    $delivery_date = $_POST["delivery_date"][$quotationDetailData['productid']];  
    
                    for($m = 0; $m < count($whqty); $m++){
                        //echo 'wh:'.$whid[$m].'qt: '.$whqty[$m].'<br>';
                        $inputWarehouseData[$m] = array('TableName' => 'soitem_warehouse','soitem_detail_id' => $insertId);
                        
                        
                        $inputWarehouseData = array(
                    
                            'TableName' => 'soitem_warehouse',
                            'FetchByKey' => 'id',
                            'FetchByValue' =>  '',
                         
                            'socode' => $poid,
                            'pid' => $quotationDetailData['productid'], 
                            'soitem_detail_id' =>  $insertId, // will be sent from $insertIdQuotationDetail
                            'warehouse' => $whid[$m],
                            'qty' => $whqty[$m],
                            'expted_deliverey_date' => mysqlDate($delivery_date[$m])
                         
                        );
                        
                        insertData($inputWarehouseData,$msg,$success,$insertIdWH);
                        $response['result'][] = $success;
                        $response['warehouse_soitem_result'] = $success;
                        $response['warehouse_soitem_msg'] = $msg;                
                        
                        $_SESSION['debug']['log_'.$inputWarehouseData['TableName']][][] = $msg;
                        
                        $arra[] = $inputWarehouseData;
                        
                        //update stock;
                        //Reduce Quantity from stock
                        $qryReduceStock = "UPDATE `stock` SET `freeqty`= `freeqty` - $whqty[$m],`orderedqty`= `orderedqty` + $whqty[$m] WHERE `product` = ".$quotationDetailData['productid'];
        			    $link->query($qryReduceStock);
        			    
        			    $qryReduceChalan="UPDATE `chalanstock` SET `freeqty`=`freeqty` - $whqty[$m],`orderedqty`=`orderedqty` + $whqty[$m] WHERE `product` = ".$quotationDetailData['productid']." AND `storerome` = ".$whid[$m];
        			    $link->query($qryReduceChalan);
                    }
                    
                }
                
                foreach($inputInvoiceDetailData as $invoiceDetailData){
                    //insert invoice detail data;
                    $invoiceDetailData['socode'] = $poid;
                    $invoiceDetailData['invoiceno'] = $invoiceno;                    
                    insertData($invoiceDetailData,$msg,$success,$insertIdInvoiceDeail);
                    $response['result'][] = $success;
                }
                
                //update orderstatus in quotation table
                $condition = "socode ='$poid'";
                updateByID('quotation','orderstatus',2,$condition);
               
                
               if (in_array(0, $response['result'])) {
                    echo "Error found";
                } else {
                    echo "All data saved successfully";
                }
                
                
    
    
            } // if($mode == 'insert'){ 
        }
        
        if($mode == 'update' && $ECApproved == 1){ // if permission granted by $ECApproved = 1;
            //orderstatus need to be controlled here;
            
            $inputOrderData['TableName'] = 'soitem';
            $inputOrderData['socode'] = $poid;
            $inputOrderData['orderstatus'] = 1; // Save, Save as revision
            updateData($inputOrderData,$msg,$success);
            
            //insert invoie data here:
            updateData($inputInvoiceData,$msg,$success);
           // echo $msg; die;
            
            //delete old orderdetail info
            $conditionsoitemdetail = 'soid = "'.$poid.'"';
            $conditionSoitemWh = 'socode = "'.$poid.'"';
            $conditionInvoiceDetail = 'socode = "'.$poid.'"';
            
            deleteRowByCondition('soitemdetails',$conditionsoitemdetail,$msg,$retVal);
            deleteRowByCondition('soitem_warehouse',$conditionSoitemWh,$msg,$retVal);
            deleteRowByCondition('invoicedetails',$conditionInvoiceDetail,$msg,$retVal);

            //Save quotation product data; 
            foreach($inputOrderDetailData as $quotationDetailData){
                
                $quotationDetailData['TableName'] = 'soitemdetails';
                $quotationDetailData['socode'] = $poid;
                insertData($quotationDetailData,$msg,$success,$insertId);
                $response['product_result'] = $success;
                $response['result'][] = $success;
                $response['product_msg'] = $msg;                
                
                $_SESSION['debug']['log_'.$quotationDetailData['TableName']][] = $msg;



                $whqty = $_POST["whqty"][$quotationDetailData['productid']];
                $whid = $_POST["whid"][$quotationDetailData['productid']];
                $delivery_date = $_POST["delivery_date"][$quotationDetailData['productid']];  

                for($m = 0; $m < count($whqty); $m++){
                    //echo 'wh:'.$whid[$m].'qt: '.$whqty[$m].'<br>';
                    $inputWarehouseData[$m] = array('TableName' => 'soitem_warehouse','soitem_detail_id' => $insertId);
                    
                    
                    $inputWarehouseData = array(
                
                        'TableName' => 'soitem_warehouse',
                        'FetchByKey' => 'id',
                        'FetchByValue' =>  '',
                     
                        'socode' => $poid,
                        'pid' => $quotationDetailData['productid'], 
                        'soitem_detail_id' =>  $insertId, // will be sent from $insertIdQuotationDetail
                        'warehouse' => $whid[$m],
                        'qty' => $whqty[$m],
                        'expted_deliverey_date' => mysqlDate($delivery_date[$m])
                     
                    );
                    
                    insertData($inputWarehouseData,$msg,$success,$insertIdWH);
                    $response['result'][] = $success;
                    $response['warehouse_result'] = $success;
                    $response['warehouse_msg'] = $msg;                
                    
                    $_SESSION['debug']['log_'.$inputWarehouseData['TableName']][][] = $msg;
                    
                    $arra[] = $inputWarehouseData;
                }
                
            } 
            
            
                foreach($inputInvoiceDetailData as $invoiceDetailData){
                    //insert invoice detail data;
                    $invoiceDetailData['socode'] = $poid;
                    $invoiceDetailData['invoiceno'] = $invoiceno;                    
                    insertData($invoiceDetailData,$msg,$success,$insertIdInvoiceDeail);
                    $response['result'][] = $success;
                }            
            
            

        }//if($mode == 'update'){       
        
        
        
            
    }//if($postaction == 'Save' || $postaction == 'Revision'){
 
 
 //dd($arra);
 //die;
 
 
            if (in_array(0, $response['result'])) {
                $result = 0;
                //echo "Error found";
            } else {
                $result = 1;
                //echo "All data saved successfully";
            }
            if(!$q_dataid){
                $q_dataid = $quatationID;
            }
            //echo $postaction;
            if($postaction == 'Save' || $postaction=='Order'){
                header('Location: '.$hostpath.'/quotationList.php?postaction='.$postaction.'&result='.$result.'&id='.$poid.'&mod=3&pg=1&changedid='.$poid);
            }else{
                $mode='update';
                //mode=update&res=4&action=restore&socode=QT-000108&id=108&rid=69&mod=3&changedid=69
                header('Location: '.$hostpath.'/quotationEntry.php?socode='.$poid.'&res=4&mode='.$mode.'&result='.$result.'&id='.$q_dataid.'&mod=3&pg=1&changedid='.$insertIdQuatation);
                //quotationEntry.php?res=4&msg=Revision saved successfully&id=630&mod=3&changedid=198
            }
 
        
}
//if(!isset($_SESSION['user'])){}else{




?>