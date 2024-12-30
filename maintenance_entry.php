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
    $orderID = $_POST["donumber"]; if($orderID == null){ $orderID = $_GET["donumber"];}
    
    $currSection = 'maintenance';
    $currPage    = basename($_SERVER['PHP_SELF']);
    
    if($orderID == null){
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
            <span>Maintenance</span>
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
                        <form method="post" action="common/maintenance_post.php" id="maintenanceForm" enctype="multipart/form-data">
						<span class="alertmsg"></span>
                        <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                            
                            <div class="row form-header">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h6>Maintenance <i class="fa fa-angle-right"></i> Add  Maintenance Service Order </h6>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h6><span class="note"> (Field Marked * are required)</span></h6>
                                </div>
                            </div>
    
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-6">
    	                                    <label for="po_dt">DO Number</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="donumber" id="donumber" value="<?= $orderID ?>"  >
                                                
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
													<div class="form-group">
														<label for="cmbcontype">Maintenance Reason<span class="redstar">*</span></label>
														<div class="form-group styled-select">
															
															<select name="cmbsupnm" id="cmbsupnm" class="cmd-child form-control" disabled>
																<option value="">Repaire</option>
															</select>
														</div>
													</div>
												</div>
                                            <div class="col-lg-3 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbcontype">Date<span class="redstar">*</span></label>
														<input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" disabled>	
													</div>
												</div>												
												

												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbsupnm">Customer<span class="redstar">*</span></label>
														<div class="form-group styled-select">
															<select name="cmbsupnm" id="cmbsupnm" class="cmd-child form-control" disabled>
																<option value="">Select Name</option>
													
															</select>
														</div>
													</div>
												</div>
												
												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbcontype">Phone<span class="redstar">*</span></label>
														<input type="text" class="form-control phone" name="phone" id="phone" value="<?php echo $phone; ?>" disabled>	
													</div>
												</div>
                                            
                                            <div class="po-product-wrapper withlebel">
                                                <div class="color-block">
                                                    <div class="col-sm-12">
                                                        <h4>Prdouct Information</h4>
                                                        <hr class="form-hr">
                                                    </div>
                                                    <style>
                                                        @media (min-width: 1199px){
                                                            .withlebel .remove-icon {
                                                                /* bottom: 23px; */
                                                            }
                                                        }
                                                    </style>
                                                    
                                                    <div class="col-sm-12">
													
													<hr class="form-hr" style="margin-bottom: 10px">
													<table width="100%" cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td width="120"><img src="assets/images/products/300_300/placeholder.jpg" height="100"></td>
															<td><b>Item: Item Name</b></td>
															<td width="50">
															
																<div class="icheck-primary">
																	<div class="icheckbox_square-blue"><input type="checkbox" name="create_1" value="1" id="item1" disabled ></div>
																	<label for="create1" class=""> &nbsp;</label>
																</div>
																	

																
															
															</td>
														</tr>
													</table>
												</div>
												
												<div class="col-lg-12 col-md-12 col-sm-12">

													<div class="form-group">

														<label for="details">Issue Detail:</label>

														<textarea class="form-control" id="details" name="details" rows="4" disabled></textarea>

													</div>

												</div>
												
												
												
												<div class="col-lg-12 col-md-12 col-sm-12">
													<div class="form-group">
														<div class="icheckbox_square-blue"><input type="checkbox" name="item[]" value="1" id="physicalInspection" disabled></div>
														<label for="physicalInspection"> &nbsp;Required Physical Inspection?</label>
													</div>
												</div>
												
												<div class="col-xs-3">
													<div class="form-group">
														<label for="physicalInspection">Clients Preferred Inspection Date and Time: </label>
														<input type="text" class="form-control datepicker" name="date" id="date" value="" placeholder="Date" disabled ><br>
														<input type="text" class="form-control timepicker-hour" name="time" id="time" value="" placeholder="Time" disabled>
													</div>
												</div>
												
												<div class="col-xs-9">
													<div class="form-group">
														<label for="physicalInspection">Customer Address:</label>
														<textarea class="form-control" id="note" name="note" rows="3" disabled></textarea>
													</div>
												</div>													
												

												<div class="col-lg-12 col-md-12 col-sm-12">

													<div class="form-group">

														<label for="details">Remarks</label>

														<textarea class="form-control" id="note" name="note" rows="2" disabled></textarea>

													</div>

												</div>


													<div class="col-sm-12">
														<h4>Charges </h4>
														<hr class="form-hr">
													</div>												
												
												
												<div class="col-lg-2 col-md-6 col-sm-6">
													<label for="po_dt">Service Fee</label>
													<div class="input-group">
														<input type="text" class="form-control " name="po_dt" id="po_dt" value="" disabled>
													</div>
												</div>												
												
												
												<div class="col-lg-1 col-md-6 col-sm-6">
													<label for="po_dt">TDS</label>
													<div class="input-group">
														<input type="text" class="form-control " name="po_dt" id="po_dt" value="" disabled>
													</div>
												</div>	
												
												
												<div class="col-lg-1 col-md-6 col-sm-6">
													<label for="po_dt">VDS</label>
													<div class="input-group">
														<input type="text" class="form-control " name="po_dt" id="po_dt" value="" disabled>
													</div>
												</div>	
												
												
												<div class="col-lg-2 col-md-6 col-sm-6">
													<label for="po_dt">Total</label>
													<div class="input-group">
														<input type="text" class="form-control " name="po_dt" id="po_dt" value="" disabled>
													</div>
													
												</div>													
												<div class="col-sm-12">
													<hr class="form-hr">
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
      // Add a click event listener to the button
      $('#find').click(function() {
        // Retrieve the value of the button
        var doid = $('#donumber').val();
        
        //$('#actionportion').empty();
        $('#loadhere').empty();
        
        $.ajax({
            url:"phpajax/maintenanceajax.php",
            method:"POST",
            data:{doid: doid},
            //dataType: 'JSON',
            success:function(res)
            {
                
				$("#loadhere").append(res);
                            

            }
        });
        
      });
      
      <?php if($mode == 2){ ?>
            var doid = $('#donumber').val();
        
            //$('#actionportion').empty();
            $('#loadhere').empty();
            
            $.ajax({
                url:"phpajax/maintenanceajax.php",
                method:"POST",
                data:{doid: doid},
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
