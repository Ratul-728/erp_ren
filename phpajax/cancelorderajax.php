<?php
require "../common/conn.php";

session_start();

$user = $_SESSION["user"];

$orderID = $_POST["orderid"];

//print_r($_POST);
//echo "order id: ".$orderID;die;

//Get Info
$qaIds = [];
$qryUpperInfo = "SELECT s.`id`,s.orderstatus,s.`socode`,o.name orgname, p.name projnm,
if(s.srctype=1,'Retail','project') srctype,DATE_FORMAT(s.`orderdate`,'%e/%c/%Y') `orderdate`
            FROM `quotation` s 
			left join organization o ON o.id = s.organization 
			left join project p on s.project=p.id
			where  s.orderstatus>=2 and s.socode= '$orderID'";
        
$resultitmdt = $conn->query($qryUpperInfo);
if ($resultitmdt->num_rows > 0) {	
	while ($rowUpperInfo = $resultitmdt->fetch_assoc()) {
	    $orgName = $rowUpperInfo["orgname"];
	    $orderDate = $rowUpperInfo["orderdate"];
	    $salsetp = $rowUpperInfo["srctype"];
	    $project = $rowUpperInfo["projnm"];
	    $qaIds[] = $rowUpperInfo["id"];
	}
    	
    if($salsetp==2)
    {
    $proj='<div class="col-lg-4 col-md-6 col-sm-6">
                <label for="po_dt">Sales Type</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="salsestp" id="salsestp"  value="'.$project.'" disabled>
                </div>
            </div>';
    }  
    $itmdtqry = "SELECT a.`id`, a.`socode`, a.`sosl`, a.`productid`, b.name itmname,b.image,b.barcode,ROUND(a.qty,0) orderqty                    
                    FROM `quotation_detail` a 
                    LEFT JOIN item b ON a.`productid` = b.id                     
                    WHERE `socode`='$orderID'  order by a.`sosl`";
}
//print_r($qaIds);die;
 
               // '.$proj.'

$formString = '
<div class="row">
    <form method="post" action="common/addcancelorder.php" id="form1" enctype="multipart/form-data">
                
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
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <label for="po_dt">Sales Type</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="salsestp" id="salsestp"  value="'.$salsetp.'" disabled>
                    </div>
                </div>
               '.$proj.'
                
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
                            <div class="col-lg-1 col-sm-6 col-xs-6">
                                <h6 class="chalan-header">Order Quantity</h6>
                            </div>
                            <div class="col-lg-1 col-sm-6 col-xs-6">
                                <h6 class="chalan-header">Delivered Quantity</h6>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <h6 class="chalan-header">Cancel Quantity <span class="redstart"></span></h6>
                            </div>
                        </div>';
    
    $resultitmdt = $conn->query($itmdtqry);
    if ($resultitmdt->num_rows > 0)
    {
        while ($rowitmdt = $resultitmdt->fetch_assoc()) 
        {
            $barcode = $rowitmdt["barcode"];
            $productName = $rowitmdt["itmname"]." [Barcode: ".$barcode."]";
            $productId = $rowitmdt["productid"];
            $warehouse = $rowitmdt["warehouse"];
            $orderQty = $rowitmdt["orderqty"];
            
            $deliveredQty = 0;
            $qrych = "SELECT dod.do_qty FROM `delivery_order_detail` dod LEFT JOIN delivery_order d ON d.id=dod.do_id 
                     WHERE d.type IN (1,2) AND d.delivery_type = 1 AND d.order_id = '$orderID' AND dod.item = ".$productId;
            $resultch = $conn->query($qrych);
            while ($rowch = $resultch->fetch_assoc()) {
                $deliveredQty = $rowch["do_qty"];
                if ($deliveredQty == null) {
                    $deliveredQty = 0;
                }
            }
            $isdisabled = '';
            if($deliveredQty >= $orderQty){
                $isdisabled = 'disabled';
            }
            if ($orderQty > 0)
            {
                $formString .= '<div class="toClone">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="productnm[]" id="productnm[]" value="'.$productName.'" disabled>
                            <input type="hidden" class="form-control" name="productid[]" id="productid[]" value="'.$productId.'">
                        </div>
                    </div>
                    
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="orderQty[]" id="orderQty[]" value="'.$orderQty.'" disabled>
                            <input type="hidden" class="form-control" name="orderQtyPer[]" id="orderQtyPer[]" value="'.$orderQty.'">
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
                        <div class="form-group">
                            <input type="number" class="form-control" name="deliveredqty[]" id="deliveredqty[]" value="'.$deliveredQty.'" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-1 col-sm-1 col-xs-4">
                        <div class="form-group">
                            <input type="number" class="form-control" name="returnqty[]" id="returnqty[]" max="'.($orderQty - $deliveredQty).'"  value="" '.$isdisabled.' >
                        </div>
                    </div>
                </div>';
            }
        }
    }

$formString .= '<br>
        <div class="col-sm-12">
            <input class="btn btn-lg btn-default top" type="submit" name="update" value="Cancel Order" id="update">
            <input class="btn btn-lg btn-warning top" type="button" name="cancel" value="Back" id="cancel" onClick="location.href = \'cancelorderList.php?pg=1&mod=3\'">
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