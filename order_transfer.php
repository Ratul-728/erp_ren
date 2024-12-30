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
    $orderID = $_POST["cmbempnm"]; if($orderID == null){ $orderID = $_GET["cmbempnm"];}
    
    $currSection = 'order_transfer';
    $currPage    = basename($_SERVER['PHP_SELF']);
        //Get Info
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
            <span>Orders Transfer</span>
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
                                    <h6>Approval <i class="fa fa-angle-right"></i>Order Transfer</h6>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h6><span class="note"> (Field Marked * are required)</span></h6>
                                </div>
                            </div>
    
    
                                        <div id = "loadhere">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <label for="po_dt">Order ID</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="orderid" id="orderid" value="<?= $orderID ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <label for="po_dt">Customer</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="customer" id="customer" value="<?= $orgName ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-6">
                                                <label for="po_dt">Order Date</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="order_dt" id="order_dt" value="<?= $orderDate ?>" disabled>
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
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header mgl10">Warehouse</h6>
                                                        </div>
                                                        <div class="col-lg-1 col-sm-6 col-xs-6">
                                                            <h6 class="chalan-header">Order Quantity</h6>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header">Select Warehouse</h6>
                                                        </div>
                                                        <div class="col-lg-1 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header">Qty</h6>
                                                        </div>
                                                    </div>
                                        </div>
                                    </div>
                                    
                                    <?php for ($i = 0; $i < count($qaIds); $i++) {
                                            $qaId = $qaIds[$i];
                                            $itmdtqry = "SELECT qaw.ordered_qty, b.name warehouse, i.name product 
                                                        FROM `qa_warehouse` qaw LEFT JOIN branch b ON qaw.warehouse_id=b.id LEFT JOIN qa q ON q.id=qaw.qa_id 
                                                        LEFT JOIN item i ON q.product_id=i.id WHERE qaw.warehouse_id in (7,8) and qaw.id = ".$qaId; echo $itmdtqry;die;
                                            $resultitmdt = $conn->query($itmdtqry);
                                            
                                            
                                                while ($rowitmdt = $resultitmdt->fetch_assoc()) { 
                                                    $product = $rowitmdt["product"];
                                                    $warehouse  = $rowitmdt["warehouse"];
                                                    $orderqty = $rowitmdt["ordered_qty"];
                                                ?>
                                                <div class="toClone">
                                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="product[]" id="product" value="<?= $product ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="warehouse[]" id="warehouse" value="<?= $warehouse ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="orderqty[]" id="orderqty" value="<?= $orderqty ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <select name="cmbranch[]" id="cmbranch" class="cmd-child form-control" required>
                                                    
                                                            <?php $qrycont = "SELECT `id`, `name`  FROM `branch` order by name";
                                                                  $resultcont = $conn->query($qrycont);
                                                                  while ($rowcont = $resultcont->fetch_assoc()) {
                                                                    $tid = $rowcont["id"];
                                                                    $nm  = $rowcont["name"];
                                                            ?>
                                                            <option value="<?php echo $tid; ?>"><?php echo $nm; ?></option>
                                                            <?php
                                                                    }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="qty[]" id="qty" value="" required>
                                                        </div>
                                                    </div>
        
                                                </div>
                                            <?php }} ?>
                                        </div></div>
                            
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
    $(document).ready(function() {
      // Add a click event listener to the button
      $('#find').click(function() {
        // Retrieve the value of the button
        var order = $('#cmbempnm').val();
        
        //$('#actionportion').empty();
        $('#loadhere').empty();
        
        $.ajax({
            url:"phpajax/deliveryQAajax.php",
            method:"POST",
            data:{orderid: order},
            //dataType: 'JSON',
            success:function(res)
            {
                
				$("#loadhere").append(res);
                            

            }
        });
        
      });
      
      <?php if($mode == 2){ ?>
            var order = $('#cmbempnm').val();
        
            //$('#actionportion').empty();
            $('#loadhere').empty();
            
            $.ajax({
                url:"phpajax/deliveryQAajax.php",
                method:"POST",
                data:{orderid: order},
                //dataType: 'JSON',
                success:function(res)
                {
                    
    				$("#loadhere").append(res);
                                
    
                }
            });
        
      <?php } ?>
    });
</script>
	
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
