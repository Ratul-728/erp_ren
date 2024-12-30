<?php
require "../common/conn.php";

session_start();

$user = $_SESSION["user"];

$purId = $_POST["orderid"];

//print_r($_POST);
//echo "order id: ".$orderID;die;

//Get Info
$qaIds = [];
$qryUpperInfo = "SELECT  org.name, pl.gnr_date, qa.id, org.id supid, pl.st  FROM `qa` qa LEFT JOIN purchase_landing pl ON qa.order_id = pl.poid 
                LEFT JOIN suplier org ON org.id = pl.warehouse WHERE qa.order_id = '".$purId."'";
//echo $qryUpperInfo;die;        
$resultitmdt = $conn->query($qryUpperInfo);
if ($resultitmdt->num_rows > 0) {	
	while ($rowUpperInfo = $resultitmdt->fetch_assoc()) {
	    $orgName = $rowUpperInfo["name"];
	    $orderDate = $rowUpperInfo["gnr_date"];
	    $supid = $rowUpperInfo["supid"];
	    $qaIds[] = $rowUpperInfo["id"];
	    $st = $rowUpperInfo["st"];
	}
    	
}
//print_r($qaIds);die;

$formString = '
<div class="row">
    <form method="post" action="common/addchallan.php" id="form1" enctype="multipart/form-data">
                
                <input type="hidden" class="form-control" name="usrid" id="usrid" value="'.$user.'">
                <input type="hidden" name="org_id" id = "org_id" value = "'.$supid.'">
                <input type="hidden" class="form-control" name="type" id="type" value="2">
                <input type="hidden" class="form-control" name="typeId" id="typeId" value="'.$purId.'">
                
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <label for="po_dt">Stock In No</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="stock" id="stock" value="" disabled>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <label for="po_dt">Reference No</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="po_id" id="po_id" value="'.$purId.'" disabled>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <label for="po_dt">Supplier</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="org_id" id="org_id" value="'.$orgName.'" disabled>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <label for="email">Receive Date</label>
					<div class="input-group">
						<input type="text" class="form-control datepicker" id="delivery_dt" name="delivery_dt" value="'.$orderDate.'" disabled>
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
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <h6 class="chalan-header mgl10">Item</h6>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-xs-6">
                                <h6 class="chalan-header">Passed Quantity</h6>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <h6 class="chalan-header">Warehouse <span class="redstart"></span></h6>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <h6 class="chalan-header">Remarks<span class="redstart"></span></h6>
                            </div>
                        </div>';
            
            
                $formString .= '
                <div class="toClone">
                    <input type = "hidden" name="unitprice_otc[]" value="'.$productCost.'">
                    <input type="hidden" name="unitpricemrc[]" value = "'.$supid.'">
                    
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        
                        <div class="form-group">
                            <div class="form-group styled-select">
                                <select name="itemName[]" id="itemName[]" class="form-control" >';
                                    
$qry1    = "SELECT i.name productnm,i.id productid,pli.tot_value
            
    FROM `qa_warehouse` qaw LEFT JOIN qa qa ON qa.id = qaw.qa_id LEFT JOIN item i ON i.id = qa.product_id
    LEFT JOIN purchase_landing pl ON pl.poid=qa.order_id LEFT JOIN purchase_landing_item pli ON (pli.pu_id=pl.id and i.id=pli.productId)
            
    WHERE qaw.pass_qty > 0 AND qa.order_id='$purId'";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["productid"];
        $nm  = $row1["productnm"];
        $productCost = $row1["tot_value"];
                                     $formString .= '<option value="'.$tid.'">'.$nm.'</option>';
    }}
                                 $formString .= '</select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="quantity_otc[]" id="" value="">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="input-group">
                            <select name="storeName[]" i d="storeName" class="storeName form-control" required>
                                <option value="">Select Store</option>';
$qryitm = "SELECT s.`id`, s.`name` FROM `branch` s order by s.name";
$resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
$tid = $rowitm["id"];
$nm  = $rowitm["name"];
                        $formString .=    '<option value="'.$tid.'"> '.$nm.'</option>';
}}
                            $formString .= '</select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="description" id="" value="">
                        </div>
                    </div>
                </div>
                </div>
                <div class="row add-btn-wrapper">
					<div class="col-sm-12">
					    <a href="#" title="Add Item" class="link-add-inventory" ><span class="glyphicon glyphicon-plus"></span> </a>
					</div>	
				</div>
				';
            

$formString .= '<br>';

if($st == 2){
    $formString .= '
        
        <div class="col-sm-12">
            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Stock" id="add" >
            <input class="btn btn-lg btn-warning top" type="button" name="cancel" value="Back" id="cancel" onClick="location.href = \'purchase_data_invList.php?pg=1&mod=12\'">
        </div>
    ';
}

$formString .='  
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

</script>



';

echo $formString;


?>