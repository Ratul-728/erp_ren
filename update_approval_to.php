<?php
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
    $tid       = $_GET["id"];
    
    $currSection = 'approval_transfer_stock';
    $currPage    = basename($_SERVER['PHP_SELF']);
    
    
        //Get Info
        $qryUpperInfo = "SELECT ts.toid, DATE_FORMAT( ts.tansferdt,'%d/%b/%Y') tansferdt, concat(emp.firstname, ' ', emp.lastname) empnm, ts.st 
                        FROM `transfer_stock` ts LEFT JOIN hr h ON h.id=ts.makeby LEFT JOIN employee emp ON emp.employeecode=h.emp_id 
                        WHERE ts.id=".$tid;
        $resultitmdt = $conn->query($qryUpperInfo);
        while ($rowUpperInfo = $resultitmdt->fetch_assoc()) {
        	    $toid = $rowUpperInfo["toid"];
        	    $tansferdt = $rowUpperInfo["tansferdt"];
        	    $empnm = $rowUpperInfo["empnm"];
        	    $st = $rowUpperInfo["st"];
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
            <span>Transfer Orders</span>
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
                        
                    <div id = "actionportion">
                        <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                            <div class="row form-header">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h6>Approval <i class="fa fa-angle-right"></i> Transfer Order</h6>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h6><span class="note"> (Field Marked * are required)</span></h6>
                                </div>
                            </div>
                            <form method="post" action="common/update_to.php" id="form1" enctype="multipart/form-data">
                
                                    <input type="hidden" class="form-control" name="order_id" id="order_id" value="<?= $tid ?>">
          
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-6">
    	                                    <label for="po_dt">Transfer ID</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="toid" id="toid" value="<?= $toid ?>"  disabled>
                                                
                                            </div>
                                        </div>
                                    
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <label for="po_dt">Transfer Date</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="transferdt" id="transferdt" value="<?= $tansferdt ?>" disabled>
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        <div class="col-lg-4 col-md-6 col-sm-6">
    	                                    <label for="po_dt">Request By</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="empnm" id="empnm" value="<?= $empnm ?>"  disabled>
                                                
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
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header mgl10">From</h6>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-xs-6">
                                                            <h6 class="chalan-header">To</h6>
                                                        </div>
                                                        <div class="col-lg-1 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header">Request QTY</h6>
                                                        </div>
                                                        <div class="col-lg-1 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header">Approved QTY<span class="redstart"></span></h6>
                                                        </div>
                                                    </div>
                                        </div>
                                    </div>
<?php
    $qryDetails = "SELECT tsd.qty, fb.name fnm, tb.name tnm, i.name product, i.image photo, tsd.approval_qty,tsd.id,tsd.product pid, tsd.to_store, i.barcode
                   FROM `transfer_stock_details` tsd 
                   LEFT JOIN branch fb ON fb.id=tsd.from_store 
                   LEFT JOIN branch tb ON tb.id=tsd.to_store 
                   LEFT JOIN item i ON i.id=tsd.product 
                   WHERE tsd.toid = ".$tid; 
    $resultDetails = $conn->query($qryDetails);
    while ($rowDetails = $resultDetails->fetch_assoc()) {
        $qty = $rowDetails["qty"];
        $frombr = $rowDetails["fnm"];
        $tobr = $rowDetails["tnm"];
        $barcode = $rowDetails["barcode"];
        $photo = $rowDetails["photo"];
        $product = $rowDetails["product"]." (".$barcode.")";
        $appqty = $rowDetails["approval_qty"];
        $tsdid = $rowDetails["id"];
        $productId = $rowDetails["pid"];
        $tobranch = $rowDetails["to_store"]
        
?>
                                                <div class="toClone">
                                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                                        <div class="input-group d-flex">
                                                            <div>
                                                            <img src="assets/images/products/300_300/<?=$photo?>" style="border:1px solid #b4b2b2" height="35" width="35">
                                                            </div>
                                                            <div style="width:100%">
                                                            <input type="text" class="form-control" name="product[]" id="product" value="<?= $product?>" disabled >
                                                            </div>
                                                            <input type="hidden" class="form-control" name="productid[]" value="<?= $productId?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="frombr[]" id="frombr" value="<?= $frombr ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="tobr[]" id="tobr" value="<?= $tobr ?>" disabled>
                                                            <input type="hidden" class="form-control" name="tobranch[]" value="<?= $tobranch?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="hidden" class="form-control" name="tsd[]" id="tsd" value="<?= $tsdid ?>" >
                                                            <input type="text" class="form-control" name="qty[]" id="qty" value="<?= $qty ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="appqty[]" id="appqty" value="<?= $appqty ?>" <?php if($st != 1) {echo "disabled";}else{echo "required";} ?>>
                                                        </div>
                                                    </div>
                                                </div>
<?php } ?>


                                    
                                        </div>
<?php
    if($st == 1){
?>
                                        <div class="col-sm-12">
                                            <input class="btn btn-lg btn-default top" type="submit" name="update" value="Update" id="update">
                                            <input class="btn btn-lg btn-warning top" type="button" name="cancel" value="Back" id="cancel" onClick="location.href = \'approval_transfer_stock.php?pg=1&mod=24\'">
                                        </div>
<?php } ?>
                            </form>
                            
                    </div>
                    </p>
                </div>
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

</script>	
	
	
</body>
</html>
