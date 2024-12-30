<?php
require "../common/conn.php";

session_start();

$user = $_SESSION["user"];

$orderID = $_POST["orderid"];

//print_r($_POST);
//echo "order id: ".$orderID;die;

//Get Info
$qaIds = [];
$paymentSt = 0;
$st = 0;
$qryUpperInfo = "SELECT  org.name, so.orderdate, qa.id, inv.paymentSt, qa.status st, qa.approval, qa.type    
                FROM `qa` qa LEFT JOIN soitem so ON qa.order_id = so.socode LEFT JOIN organization org ON org.id = so.organization 
                LEFT JOIN invoice inv ON inv.soid=qa.order_id
                WHERE qa.type != 8 AND qa.type != 3 AND qa.order_id = '".$orderID."'";
        
$resultitmdt = $conn->query($qryUpperInfo);
if ($resultitmdt->num_rows > 0) {	
	while ($rowUpperInfo = $resultitmdt->fetch_assoc()) {
	    $orgName = $rowUpperInfo["name"];
	    $orderDate = $rowUpperInfo["orderdate"];
	    $qaIds[] = $rowUpperInfo["id"];
	    
	    if($paymentSt < $rowUpperInfo["paymentSt"]){
	        $paymentSt = $rowUpperInfo["paymentSt"];
	    }
	    if($st < $rowUpperInfo["st"]){
	        $st = $rowUpperInfo["st"];
	    }
	    $approval = $rowUpperInfo["approval"];
	    $type = $rowUpperInfo["type"];
	    
	}
    	
}

if (($st > 1) || $approval == 1) {
//print_r($qaIds);die;

$formString = '
<div class="row">
    <form method="post" action="common/adddeliveryCO.php" id="form1" enctype="multipart/form-data">
                
                <input type="hidden" class="form-control" name="order_id" id="order_id" value="'.$orderID.'">
                <input type="hidden" class="form-control" name="qa_id" id="qa_id" value="'.$qaId.'">
                <input type="hidden" class="form-control" name="order" id="order" value="'.$orderID.'">
                
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <label for="po_dt">Customer</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="customer" id="customer" value="'.$orgName.'" disabled>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <label for="po_dt">Order Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="order_dt" id="order_dt" value="'.$orderDate.'" disabled>
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
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
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <h6 class="chalan-header mgl10">Item</h6>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <h6 class="chalan-header mgl10">Warehouse</h6>
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
                                <h6 class="chalan-header">CO Quantity</h6>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <h6 class="chalan-header">DO Generated</h6>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <h6 class="chalan-header">TO Quantity <span class="redstart"></span></h6>
                            </div>
                        </div>';

for ($i = 0; $i < count($qaIds); $i++) {
    $qaId = $qaIds[$i];
    $itmdtqry = "SELECT i.name productnm,i.id productid, qaw.ordered_qty, b.name warehouse, qaw.`pass_qty`, qaw.id, b.id warehouseId
            
    FROM `qa_warehouse` qaw LEFT JOIN branch b ON qaw.warehouse_id = b.id LEFT JOIN qa qa ON qa.id = qaw.qa_id LEFT JOIN item i ON i.id = qa.product_id 
            
    WHERE qaw.pass_qty > 0 AND qaw.qa_id = ".$qaId;
    $resultitmdt = $conn->query($itmdtqry);
    
    if ($resultitmdt->num_rows > 0) {
        while ($rowitmdt = $resultitmdt->fetch_assoc()) {
            $productName = $rowitmdt["productnm"];
            $productId = $rowitmdt["productid"];
            $warehouse = $rowitmdt["warehouse"];
            $warehouseId = $rowitmdt["warehouseId"];
            $orderQty = $rowitmdt["ordered_qty"];
            $passQty = $rowitmdt["pass_qty"];
            $qwa = $rowitmdt["id"];
            $qrych = "SELECT sum(dod.`do_qty`) deliveredqty FROM `delivery_order_detail` dod LEFT JOIN delivery_order d ON d.id = dod.do_id 
                    WHERE d.type != 0 and dod.qa_id = ".$qwa;
            $resultch = $conn->query($qrych);
            $deliveredQty = 0;
            while ($rowch = $resultch->fetch_assoc()) {
                $deliveredQty = $rowch["deliveredqty"];
                if ($deliveredQty == null) {
                    $deliveredQty = 0;
                }
            }
            
            //CO qty
            $coQty = 0;
            $qryCo = "SELECT cd.`co_qty` FROM `co_details` cd
                     WHERE cd.order_id = '$orderID' AND cd.product_id = '$productId' AND cd.before_warehouse = '$warehouseId'";
            $resultCo = $conn->query($qryCo);
            while ($rowCo = $resultCo->fetch_assoc()) {
                $coQty = $rowCo["co_qty"];
                if ($coQty == null) {
                    $coQty = 0;
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
                $deliverableQuantity = $passQty - $cancelQty;
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
            $deliverableQuantity -= $coQty;
            
            if ($deliverableQuantity <= 0) {
                $formString .= '<div class="toClone">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="" id="" value="'.$productName.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="" id="" value="'.$warehouse.'" disabled>
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
                            <input type="text" class="form-control" name="" id="" value="'.$coQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="" id="" value="'.$deliveredQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
                        <div class="form-group">
                            <input type="text" class="form-control deliverableQty" name="" id="" value="Already Scheduled" disabled>
                        </div>
                    </div>
                </div>';
            }else{
                $formString .= '<div class="toClone">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="productnm[]" id="productnm[]" value="'.$productName.'" disabled>
                            <input type="hidden" class="form-control" name="qwa[]" id="qwa[]" value="'.$qwa.'">
                            <input type="hidden" class="form-control" name="productid[]" id="productid[]" value="'.$productId.'">
                            <input type="hidden" class="form-control" name="before_warehouses[]" id="before_warehouses[]" value="'.$warehouseId.'">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="input-group">
                            <select name="warehouseIds[]" id="warehouseIds[]" class="form-control">';
                                $qryBranch = "SELECT * FROM branch";
                                $resultBranch = $conn->query($qryBranch);
                	            while ($rowB = $resultBranch->fetch_assoc()) {
                	                $sel = '';
                	                if($rowB["id"] == $warehouseId) { $sel = "selected"; }
                                        $formString .= '<option value="'.$rowB["id"].'" '.$sel.'>'.$rowB["name"].'</option>';
                	            }
                            $formString .= '</select>
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
                            <input type="text" class="form-control" name="$coQtys[]" id="$coQtys[]" value="'.$coQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="deliveredQty[]" id="deliveredQty[]" value="'.$deliveredQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
                        <div class="form-group">
                            <input type="number" class="form-control deliverableQty" name="deliverableQty[]" id="deliverableQty[]" max="'.($deliverableQuantity).'"  value="" validateQuantity(this)>
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
        <div class="col-sm-12">';
if($coQty == 0){
            $formString .= '<input class="btn btn-lg btn-default top" type="submit" name="update" value="Update" id="update">';
}else{
     $formString .= '<input class="btn btn-lg btn-default top" type="button" name="nothing" value="Already submitted" id="nothing" disabled>';
}
            
            $formString .= '<input class="btn btn-lg btn-warning top" type="button" name="cancel" value="Back" id="cancel" onClick="location.href = \'deliveryQAList.php?pg=1&mod=3\'">
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
    echo "<script> alert('Please send for approval') </script>";die;
}

?>