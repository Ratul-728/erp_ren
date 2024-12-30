<?php
//print_r($_REQUEST);
//exit();
require "common/conn.php";
include_once('rak_framework/fetch.php');

session_start();
$usr = $_SESSION["user"];
//echo $usr;die;


if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} 
    $res       = $_GET['res'];
    $msg       = $_GET['msg'];
    $returnId      = $_GET["returnid"];
    
    $currSection = 'scm_return_order';
    $currPage    = basename($_SERVER['PHP_SELF']);
    $mode = 2;
    if($returnId == null){
        header("Location: ".$hostpath."/scm_return_order.php?res=1&msg='Please go through process'&id=''&mod=16");
    }else{
        //Get Info
        $qryUpperInfo = "SELECT r.id, org.name orgnm, q.socode, DATE_FORMAT(q.orderdate,'%e/%c/%Y') orderdate 
                        FROM `return_order` r LEFT JOIN quotation q ON r.order_id=q.socode LEFT JOIN organization org ON org.id=q.organization 
                        WHERE r.ro_id = '".$returnId."'";
        $resultitmdt = $conn->query($qryUpperInfo);
    	if ($resultitmdt->num_rows > 0) {	
        	while ($rowUpperInfo = $resultitmdt->fetch_assoc()) {
        	    $orderid = $rowUpperInfo["socode"];
        	    $customer = $rowUpperInfo["orgnm"];
        	    $orderdate = $rowUpperInfo["orderdate"];
        	    $roid = $rowUpperInfo["id"];
        	}
    	}else{
    	    header("Location: ".$hostpath."/scm_return_order.php?res=1&msg='Please give a valid return order number'&id=''&mod=16");
    	}
    	
    	
    }
    
	//echo $orderstatus;die;
	
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
include_once 'common_header.php';
    ?>
<body class="form soitem order-form">
<style>

.c-vat{text-align: center;}
.c-qty{text-align: center;}
.c-price{text-align: right;}
.c-price-utt{text-align: right;}
.c-discount{text-align: center;}
.c-discounted-ttl{text-align: right;padding-right: 45px;}	
	
.ipspan{position: relative}
.ipspan span{
    display: block;
    
    
    background-color: rgb(212,218,221);
    position: absolute;
    z-index: 0;
    right: 0;
    top: 0;
    text-align: center;
    height: 35px;
    width: 35px;
    line-height: 35px;
    font-size: 12px;
}



.grid-sum-footer input{
    padding-right: 45px;
}	

	
.swal-button--confirm{
    background-color: #e64942;
}	
	
</style>
<?php
include_once 'common_top_body.php';
    ?>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Return Order</span>
        </div>
        <?php include_once 'menu.php'; ?>
        <div style="height:54px;"></div>
    </div>
    <!-- END #sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12">
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
<form method="post" action="common/adddeliveryReturnAjax.php" id="form1" enctype="multipart/form-data">
                    <div id = "actionportion">
                        <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                            <div class="row form-header">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h6>Sales <i class="fa fa-angle-right"></i> Return Order Delivery</h6>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h6><span class="note"> (Field Marked * are required)</span></h6>
                                </div>
                            </div>
    
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-6">
    	                                    <label for="po_dt">Return Order Number</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="ro_id" id="ro_id" value="<?= $returnId ?>"  readonly>
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
    	                                    <label for="po_dt">Order Number</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="orderid" id="orderid" value="<?= $orderid ?>"  readonly>
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
    	                                    <label for="po_dt">Customer</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="customer" id="customer" value="<?= $customer ?>"  readonly>
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
    	                                    <label for="po_dt">Order Date</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="orderdate" id="orderdate" value="<?= $orderdate ?>"  readonly>
                                                
                                            </div>
                                        </div>
                                    </div>
                                        
                                        <div class="row">
                                            
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <label for="po_dt">Delivery Date</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" name="deli_dt" id="deli_dt" required>
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <label for="starttime">Delivery Start Time</lebel>
                                                <div class="input-group time-wrapper">
                                                    <input type="text" class="form-control timeonly" id="starttime" name="starttime" required>
                                                </div>        
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <label for="endtime">Delivery End Time</lebel>
                                                <div class="input-group time-wrapper">
                                                    <input type="text" class="form-control timeonly" id="endtime" name="endtime" required>
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
                                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header mgl10">Warehouse</h6>
                                                        </div>
                                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header mgl10">RO Quantity</h6>
                                                        </div>
                                                        <div class="col-lg-1 col-sm-6 col-xs-6">
                                                            <h6 class="chalan-header">Pass Quantity</h6>
                                                        </div>
                                                        <div class="col-lg-1 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header">DO Generated</h6>
                                                        </div>
                                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header">Deliverable Quantity <span class="redstart"></span></h6>
                                                        </div>
                                                    </div>
                                        </div>
                                    </div>
<?php
$isupdate = false;
$itmdtqry = "SELECT rod.return_qty, qaw.pass_qty, concat(i.name, '(', i.barcode, ')') prodnm, b.name warehouse, i.id prodid, qaw.id qwid, rod.id rodid 
            FROM `return_order_details` rod LEFT JOIN return_order r ON r.id=rod.ro_id LEFT JOIN qa_warehouse qaw ON qaw.id=rod.after_qawid 
            LEFT JOIN qa q ON q.id=qaw.qa_id LEFT JOIN item i ON i.id=q.product_id LEFT JOIN branch b ON b.id=qaw.warehouse_id 
            WHERE r.ro_id = '$returnId'";
            // echo $itmdtqry;die;
    $resultitmdt = $conn->query($itmdtqry);
    while ($rowitmdt = $resultitmdt->fetch_assoc()) {
        $productName = $rowitmdt["prodnm"];
        $productId = $rowitmdt["prodid"];
        $qwa = $rowitmdt["qwid"];
        $fromstore = $rowitmdt["warehouse"];
        $passqty = $rowitmdt["pass_qty"];   if($passqty == "") $passqty = 0;
        $return_qty = $rowitmdt["return_qty"];
        $rodid      = $rowitmdt["rodid"];
        
        $qrych = "SELECT sum(`do_qty`) deliveredqty FROM `delivery_order_detail` WHERE qa_id = ".$qwa;
        $resultch = $conn->query($qrych);
        while ($rowch = $resultch->fetch_assoc()) {
            $deliveredQty = $rowch["deliveredqty"];
        }
        if ($deliveredQty == null || $deliveredQty == '') {
            $deliveredQty = 0;
        }
        
        if ($deliveredQty >= $passqty) {
            $deliverableQuantity = 0;
        }else{
            $deliverableQuantity = $passqty - $deliveredQty;
        }

?>
        

                <div class="toClone">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="productnm[]" id="productnm[]" value="<?= $productName ?>" disabled>
                            <input type="hidden" class="form-control" name="qwa[]" id="qwa[]" value="<?= $qwa ?>">
                            <input type="hidden" class="form-control" name="productid[]" id="productid[]" value="<?= $productId ?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="fromstore[]" id="fromstore[]" value="<?=$fromstore ?>" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="orderQty[]" id="orderQty[]" value="<?= $return_qty ?>" disabled>
                            <input type="hidden" class="form-control" name="orderQtyPer[]" id="orderQtyPer[]" value="<?= $return_qty ?>">
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="passQty[]" id="passQty[]" value="<?= $passqty ?>" disabled>
                            
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="deliveredQty[]" id="deliveredQty[]" value="<?= $deliveredQty ?>" disabled>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-1 col-sm-1 col-xs-4">
                        <div class="form-group">
                            <?php if ($deliverableQuantity > 0) { $isupdate = true ?>
                                <input type="number" class="form-control" name="deliverableQty[]" id="deliverableQty[]" max="<?= $deliverableQuantity ?>"  value="" validateQuantity(this)>
                            <?php } else { ?>
                                <?php if ($passqty== 0) { ?>
                                    <input type="text" class="form-control" name="" id="" value="There is  no passed qty" disabled>
                                <?php } else { ?>
                                    <input type="text" class="form-control" name="" id="" value="Already Scheduled" disabled>
                            <?php }} ?>
                        </div>
                    </div>
                </div>
                
        <?php } ?>
                                    
                                        
                                    <br>
                                    <div class="col-sm-12">
                                    <?php if($isupdate) { ?>
                                        <input class="btn btn-lg btn-default top" type="submit" name="update" value="Update" id="update">
                                    <?php } ?>
                                        <input class="btn btn-lg btn-warning top" type="button" name="cancel" value="Back" id="cancel" onClick="location.href = \'scm_return_order.php?pg=1&mod=16\'">
                                    </div>
                            
                    </div>
                    </p>
                </div>
</form>
            </div>
        </div>
    </div>
</div>
	<input type="hidden" class="bkorfound" value="">
<!-- /#page-content-wrapper -->

<?php
include_once 'common_footer.php';
//$cusid = 3; ?>
<?php include_once 'inc_cmb_loader_js.php'; ?>

<?php

    if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }

    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>
    
<script>
//datetime definer
function callTime(){
         $('.timeonly').datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "HH:mm",
					//format: 'LT',
					keepOpen: true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-chevron-up",
                 down: "fa fa-chevron-down"
                }
            });
			//$('.timeonly').data("DateTimePicker").show();
}
callTime();

function validateQuantity(input) {
    if (input.value > input.max) {
        input.value = input.max;
    }
}

</script>	
	
	
</body>
</html>
