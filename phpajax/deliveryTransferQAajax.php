<?php
require "../common/conn.php";

session_start();

$user = $_SESSION["user"];

$orderID = $_POST["orderid"];

//print_r($_POST);
//echo "order id: ".$orderID;die;

//Get Info
$qaIds = [];
$qryUpperInfo = "SELECT ts.tansferdt, qa.id, qa.status st, qa.approval, qa.type    
                FROM `qa` qa LEFT JOIN transfer_stock ts ON ts.id=qa.order_id
                WHERE  ts.toid = '".$orderID."'";
        
$resultitmdt = $conn->query($qryUpperInfo);
if ($resultitmdt->num_rows > 0) {	
	while ($rowUpperInfo = $resultitmdt->fetch_assoc()) {
	    $transferdt = $rowUpperInfo["tansferdt"];
	    $qaIds[] = $rowUpperInfo["id"];
	    
	    $approval = $rowUpperInfo["approval"];
	    $type = $rowUpperInfo["type"];
	    
	}
    	
}

if ($type == 4) {
//print_r($qaIds);die;

$formString = '
<div class="row">
    <form method="post" action="common/adddeliveryQA.php" id="form1" enctype="multipart/form-data">
                
                <input type="hidden" class="form-control" name="order_id" id="order_id" value="'.$orderID.'">
                <input type="hidden" class="form-control" name="qa_id" id="qa_id" value="'.$qaId.'">
                <input type="hidden" class="form-control" name="order" id="order" value="'.$orderID.'">
                
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <label for="po_dt">Transfer Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="order_dt" id="order_dt" value="'.$transferdt.'" disabled>
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <label for="po_dt">Delivery Date<span class="redstar">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" name="deli_dt" id="deli_dt" required>
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <lebel for="po_dt">Delivery Start Time<span class="redstar">*</span></lebel>
                    <div class="input-group time-wrapper">
                        <input type="text" class="form-control timeonly" id="starttime" name="starttime" required>
                    </div>        
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <lebel for="po_dt">Delivery End Time<span class="redstar">*</span></lebel>
                    <div class="input-group time-wrapper">
                        <input type="text" class="form-control timeonly" id="endtime" name="endtime" required>
                    </div>        
                </div>
                
                <div class="po-product-wrapper withlebel">
                    <div class="color-block">
                        <div class="col-sm-12">
                            <h4>Item Information</h4>
                            <hr class="form-hr">
                        </div>
                        <style>
                            @media (min-width: 1199px){
                                .withlebel .remove-icon {
                                    /* bottom: 23px; */
                                }
                            }
                        </style>
                        <div class="row form-grid-bls hidden-md hidden-sm hidden-xs">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <h6 class="chalan-header mgl10">Item</h6>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <h6 class="chalan-header mgl10">From Warehouse</h6>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <h6 class="chalan-header mgl10">To Warehouse</h6>
                            </div>
                            <div class="col-lg-1 col-sm-6 col-xs-6">
                                <h6 class="chalan-header">Order Quantity</h6>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <h6 class="chalan-header">QA Passed</h6>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <h6 class="chalan-header">Cancelled Quantity</h6>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <h6 class="chalan-header">DO Generated</h6>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <h6 class="chalan-header">Deliverable Quantity <span class="redstart"></span></h6>
                            </div>
                        </div>';

for ($i = 0; $i < count($qaIds); $i++) {
    $qaId = $qaIds[$i];
    $itmdtqry = "SELECT i.name productnm,i.id productid, qaw.ordered_qty, qaw.`pass_qty`, qaw.id, fs.name fromstore, tos.name tostore,  tsd.from_store
                FROM `qa_warehouse` qaw LEFT JOIN qa qa ON qa.id = qaw.qa_id LEFT JOIN item i ON i.id = qa.product_id LEFT JOIN transfer_stock ts ON ts.id=qa.order_id 
                LEFT JOIN transfer_stock_details tsd ON ( tsd.toid = ts.id AND tsd.product = qa.product_id) LEFT JOIN branch fs ON fs.id=tsd.from_store 
                LEFT JOIN branch tos ON tos.id=tsd.to_store WHERE qaw.pass_qty > 0 AND qaw.qa_id = ".$qaId;
    $resultitmdt = $conn->query($itmdtqry);
    
    if ($resultitmdt->num_rows > 0) {
        while ($rowitmdt = $resultitmdt->fetch_assoc()) {
            $productName = $rowitmdt["productnm"];
            $productId = $rowitmdt["productid"];
            $fromstore = $rowitmdt["fromstore"];
            $fromstoreId = $rowitmdt["from_store"];
            $tostore = $rowitmdt["tostore"];
            $orderQty = $rowitmdt["ordered_qty"];
            $passQty = $rowitmdt["pass_qty"];
            $qwa = $rowitmdt["id"];
            $qrych = "SELECT sum(`do_qty`) deliveredqty FROM `delivery_order_detail` WHERE qa_id = ".$qwa;
            $resultch = $conn->query($qrych);
            while ($rowch = $resultch->fetch_assoc()) {
                $deliveredQty = $rowch["deliveredqty"];
                if ($deliveredQty == null) {
                    $deliveredQty = 0;
                }
            }
            //Cancelled Order
            $cancelQty = 0;
            $qrycncl = "SELECT `qty_canceled` FROM `cancel_order` WHERE order_id = '$orderID' AND `productid` = '$productId' AND st = 2";
            $resultcncl = $conn->query($qrycncl);
            while ($rowcncl = $resultcncl->fetch_assoc()) {
                $cancelQty = $rowcncl["qty_canceled"];
            }
            
            if ($cancelQty > 0 && $passQty > 0) {
                // If there is a cancelled quantity and some quantity passed by QC team
                $deliverableQuantity = min($passQty, $orderQty - $cancelQty);
            }
            elseif ($passQty >= $orderQty) {
                // If the QC passed quantity is greater than or equal to the ordered quantity
                $deliverableQuantity = $orderQty;
            } elseif ($passQty > 0) {
                // If some quantity passed by QC team but no cancelled quantity
                $deliverableQuantity = $passQty;
            } else {
                // If no quantity passed by QC team
                $deliverableQuantity = 0;
            }
            
            $deliverableQuantity -= $deliveredQty;
            
            if ($deliverableQuantity <= 0 || $fromstoreId == 10) {
                if($fromstoreId == 10){
                    $msg = "From Repaired store!";
                }else{
                    $msg = "Already Scheduled";
                }
                $formString .= '<div class="toClone">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="" id="" value="'.$productName.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="" id="" value="'.$fromstore.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="" id="" value="'.$tostore.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="" id="" value="'.$orderQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="" id="" value="'.$passQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="" id="" value="'.$cancelQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="" id="" value="'.$deliveredQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
                        <div class="form-group">
                            <input type="text" class="form-control" name="" id="" value="'.$msg.'" disabled>
                        </div>
                    </div>
                </div>';
            }else{
                $formString .= '<div class="toClone">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="productnm[]" id="productnm[]" value="'.$productName.'" disabled>
                            <input type="hidden" class="form-control" name="qwa[]" id="qwa[]" value="'.$qwa.'">
                            <input type="hidden" class="form-control" name="productid[]" id="productid[]" value="'.$productId.'">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="fromstore[]" id="fromstore[]" value="'.$fromstore.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="tostore[]" id="tostore[]" value="'.$tostore.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="orderQty[]" id="orderQty[]" value="'.$orderQty.'" disabled>
                            <input type="hidden" class="form-control" name="orderQtyPer[]" id="orderQtyPer[]" value="'.$orderQty.'">
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="passQty[]" id="passQty[]" value="'.$passQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="cancelQty[]" id="cancelQty[]" value="'.$cancelQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="deliveredQty[]" id="deliveredQty[]" value="'.$deliveredQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
                        <div class="form-group">
                            <input type="number" class="form-control" name="deliverableQty[]" id="deliverableQty[]" max="'.($deliverableQuantity).'"  value="" validateQuantity(this)>
                        </div>
                    </div>
                </div>';
            }
            
        }
    }else{
        continue;
    }
}

$formString .= '<br>
        <div class="col-sm-12">
            <input class="btn btn-lg btn-default top" type="submit" name="update" value="Update" id="update">
            <input class="btn btn-lg btn-warning top" type="button" name="cancel" value="Back" id="cancel" onClick="location.href = \'deliveryTransferQAList.php?pg=1&mod=3\'">
        </div>
   
</form>
</div>

<script>
//datetime definer
function callTime(){
         $(".timeonly").datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "HH:mm",
					//format: "LT",
					keepOpen: true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-chevron-up",
                 down: "fa fa-chevron-down"
                }
            });
			//$(".timeonly").data("DateTimePicker").show();
}
callTime();

$(".datepicker, .datepicker_history_filter").datetimepicker({
					//inline:true,
					//sideBySide: true,
				format: "DD/MM/YYYY",
			 	
					
				 //keepOpen:true,
			 	//inline: true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });
            
function validateQuantity(input) {
    if (input.value > input.max) {
        input.value = input.max;
    }
}

</script>


';

echo $formString;

}else{
    echo "<script> alert('Please enter transfer id') </script>";die;
}

?>