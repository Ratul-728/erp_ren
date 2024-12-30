<?php
require "../common/conn.php";

session_start();

$user = $_SESSION["user"];

$orderID = $_POST["orderid"];

//print_r($_POST);
//echo "order id: ".$orderID;die;

//Get Info
$qaIds = [];
$qryUpperInfo = "SELECT  org.name, so.orderdate, qa.id  FROM `qa` qa LEFT JOIN soitem so ON qa.order_id = so.socode 
                LEFT JOIN organization org ON org.id = so.organization WHERE qa.order_id = '".$orderID."'";
        
$resultitmdt = $conn->query($qryUpperInfo);
if ($resultitmdt->num_rows > 0) {	
	while ($rowUpperInfo = $resultitmdt->fetch_assoc()) {
	    $orgName = $rowUpperInfo["name"];
	    $orderDate = $rowUpperInfo["orderdate"];
	    $qaIds[] = $rowUpperInfo["id"];
	}
    	
}
//print_r($qaIds);die;

$formString = '
<div class="row">
    <form method="post" action="common/addreturnorder.php" id="form1" enctype="multipart/form-data">
                
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
                                <h6 class="chalan-header mgl10">From Warehouse</h6>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <h6 class="chalan-header mgl10">To Warehouse</h6>
                            </div>
                            <div class="col-lg-1 col-sm-6 col-xs-6">
                                <h6 class="chalan-header">Order Quantity</h6>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <h6 class="chalan-header">Delivered</h6>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <h6 class="chalan-header">Return Quantity <span class="redstart"></span></h6>
                            </div>
                        </div>';

for ($i = 0; $i < count($qaIds); $i++) {
    $qaId = $qaIds[$i];
    $itmdtqry = "SELECT i.name productnm,i.id productid, qaw.ordered_qty, b.name warehouse, qaw.`pass_qty`, qaw.id, i.barcode 
            
    FROM `qa_warehouse` qaw LEFT JOIN branch b ON qaw.warehouse_id = b.id LEFT JOIN qa qa ON qa.id = qaw.qa_id LEFT JOIN item i ON i.id = qa.product_id 
            
    WHERE qaw.pass_qty > 0 AND qaw.qa_id = ".$qaId;
    $resultitmdt = $conn->query($itmdtqry);
    
    if ($resultitmdt->num_rows > 0) {
        while ($rowitmdt = $resultitmdt->fetch_assoc()) {
            $barcode = $rowitmdt["barcode"];
            $productName = $rowitmdt["productnm"]." [Barcode: ".$barcode."]";
            $productId = $rowitmdt["productid"];
            $warehouse = $rowitmdt["warehouse"];
            $orderQty = $rowitmdt["ordered_qty"];
            $passQty = $rowitmdt["pass_qty"];
            $qwa = $rowitmdt["id"];
            $qrych = "SELECT sum(dod.`do_qty`) deliveredqty 
                        FROM `delivery_order_detail` dod LEFT JOIN delivery_order d ON d.id=dod.do_id 
                        WHERE d.type in (1,2) AND dod.qa_id = ".$qwa;
            $resultch = $conn->query($qrych);
            while ($rowch = $resultch->fetch_assoc()) {
                $deliveredQty = $rowch["deliveredqty"];
                if ($deliveredQty == null) {
                    $deliveredQty = 0;
                }
            }
            if ($deliveredQty > 0) {
                $formString .= '<div class="toClone">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="productnm[]" id="productnm[]" value="'.$productName.'" disabled>
                            <input type="hidden" class="form-control" name="qwa[]" id="qwa[]" value="'.$qwa.'">
                            <input type="hidden" class="form-control" name="productid[]" id="productid[]" value="'.$productId.'">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="warehouse[]" id="warehouse[]" value="'.$warehouse.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="styled-select">
                            <select name="towarehouse[]" id="towarehouse[]" class="form-control" required>
                                <option value="">Select Warehouse</option>';
                                 $qryitm = "SELECT s.`id`, s.`name` FROM `branch` s order by s.name";

                                 $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                 $tid = $rowitm["id"];
                                 $nm  = $rowitm["name"];
                                    $formString .= '<option value="'.$tid.'">'.$nm.'</option>';
                                 }}
                                
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
                            <input type="text" class="form-control" name="deliveredQty[]" id="deliveredQty[]" value="'.$deliveredQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-1 col-xs-4">
                        <div class="form-group">
                            <input type="number" class="form-control" name="returnqty[]" id="returnqty[]" max="'.$deliveredQty.'"  value="" >
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
            <label for="attachment1">Note</label>
            <div class="input-group">
               <textarea name="remarks" class="form-control" id="remarks1" rows="4"></textarea>
            </div>
        </div>
        <div class="col-sm-12">
            <input class="btn btn-lg btn-default top" type="submit" name="update" value="Return Order" id="update">
            <input class="btn btn-lg btn-warning top" type="button" name="cancel" value="Back" id="cancel" onClick="location.href = \'returnorderList.php?pg=1&mod=3\'">
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

</script>


';

echo $formString;


?>