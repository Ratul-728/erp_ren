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
    $purchaseId = $_GET["po"];
    
    $currSection = 'purchasedatainv';
    $currPage    = basename($_SERVER['PHP_SELF']);
    
    if($res == 0){
        $mode = 1;
    }else{
        //Get Info
        $qryUpperInfo = "SELECT  org.name, so.orderdate, qa.id  FROM `qa` qa LEFT JOIN soitem so ON qa.order_id = so.socode 
                        LEFT JOIN organization org ON org.id = so.organization WHERE qa.order_id = '".$orderID."'";
        
        $resultitmdt = $conn->query($qryUpperInfo);
    	if ($resultitmdt->num_rows > 0) {	
        	while ($rowUpperInfo = $resultitmdt->fetch_assoc()) {
        	    $orgName = $rowUpperInfo["name"];
        	    $orderDate = $rowUpperInfo["orderdate"];
        	    $qaId = $rowUpperInfo["id"];
        	}
        	
        	$mode = 2;
    	}else{
    	    $mode = 3;
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
            <span>Orders</span>
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
                                    <h6>Inventory <i class="fa fa-angle-right"></i> Add Inventory </h6>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h6><span class="note"> (Field Marked * are required)</span></h6>
                                </div>
                            </div>
    
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-6">
    	                                    <label for="po_dt">Purchase ID</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="cmbempnm" id="cmbempnm" value="<?= $purchaseId ?>" <?php if($mode==2) echo "readonly"; ?> >
                                                <input type="hidden" class="form-control" name="type" id="type" value="2">
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                              <label for="email"> </label>
                                            <div class="form-group">
                                                <input class="btn btn-lg btn-default" type="button" name="find" value="Get"  id="find" > 
                                            </div>
                                        </div>
                                    </div>
                                        <div id = "loadhere">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <label for="po_dt">Stock In No</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="stockno" id="stockno" value="" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <label for="po_dt">Reference No</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="refno" id="refno" value="" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <label for="po_dt">Supplier</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" name="organization" id="organization" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <lebel for="po_dt">Receive Date</lebel>
                                                <div class="input-group time-wrapper">
                                                    <input type="text" class="form-control timeonly" id="rcvdt" name="rcvdt" disabled>
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
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header mgl10">Quantity</h6>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-xs-6">
                                                            <h6 class="chalan-header">Select Store</h6>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <h6 class="chalan-header">Remarks</h6>
                                                        </div>
                                                    </div>
                                        </div>
                                    </div>
                                                <div class="toClone">
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="" id="" value="" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="" id="" value="" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="" id="" value="" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="" id="" value="" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                    
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
     
      function load(order){
          $('#loadhere').empty();
          $.ajax({
            url:"phpajax/purchaseinvajax.php",
            method:"POST",
            data:{orderid: order},
            //dataType: 'JSON',
            success:function(res)
            {
                
				$("#loadhere").append(res);
				
				// Now, you can perform actions on the dynamically loaded content
                var max_fields = 500; //maximum input boxes allowed
                
                var x = 1; //initlal text box count
                
                // Event delegation for dynamically added elements
                $("#loadhere").on("click", ".link-add-inventory", function(e) {
                
                    var wrapper = $("#loadhere .color-block"); //Fields wrapper    
                    
                
                    e.preventDefault();
                    if (x < max_fields) { //max input box allowed
                        x++;
                        //alert($(".toClone").length);
                        // Clone the element and append it to the wrapper
                        if(x==2){
                            $( "#loadhere .po-product-wrapper .toClone").clone().appendTo(wrapper);
                        }else{
                            $( "#loadhere .po-product-wrapper .toClone:last-child").clone().appendTo(wrapper);
                        }
    
                    	$( "#loadhere .po-product-wrapper .toClone:last-child input").val("");
                  
                
                		if(x==2){
                			$( ".po-product-wrapper .toClone:last-child").append('<div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
                			
                		}
                    }
                });
        
            }
        });
      }
      // Add a click event listener to the button
      $('#find').click(function() {
        // Retrieve the value of the button
        var order = $('#cmbempnm').val();
        load(order);
        
      });
      
      //If came fromdatagrid
      <?php if($purchaseId != ''){ ?>
            var order = $('#cmbempnm').val();
            load(order);
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
