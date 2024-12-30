<?php
//print_r($_REQUEST);
//exit();
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
//echo $usr;die;
if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {
    $res       = $_GET['res'];
    $msg       = $_GET['msg'];
    $id        = $_GET['id'];
    $serno     = $_GET['id'];
    $totamount = 0;

    if ($res == 1) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
        $mode = 1;
    } elseif ($res == 2) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
        $mode = 1;
    } elseif ($res == 4) {
        //echo "<script type='text/javascript'>alert('".$id."')</script>";
        $qry = "SELECT p.`id`, p.`poid`,p.`adviceno`, p.`supid`, DATE_FORMAT(p.`orderdt`,'%e/%c/%Y') `orderdt`, p.`currency`, p.`tot_amount`, p.`invoice_amount`, p.`vat`, p.`tax`, DATE_FORMAT(p.`delivery_dt`,'%e/%c/%Y') `delivery_dt`, p.`hrid`, o.name FROM `po` p left join organization o ON p.supid = o.id  where  p.id= " . $id;
       
       //$qry = "SELECT p.`id`, p.`poid`,p.`adviceno`, p.`supid`, DATE_FORMAT(`p.orderdt`,'%e/%c/%Y') `orderdt`, p.`currency`, p.`tot_amount`, p.`invoice_amount`, p.`vat`, p.`tax`, DATE_FORMAT(`p.delivery_dt`,'%e/%c/%Y') `delivery_dt`, p.`hrid`,o.name org FROM `po` p left join o on p.supid=o.id  where  p.id= " . $id;
 //echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $uid            = $row["id"];
                    $poid           = $row["poid"];
                    $adv            = $row["adviceno"];
                    $supid          = $row["supid"];
                    $orderdt        = $row["orderdt"];
                    $currency       = $row["currency"];
                    $tot_amount     = $row["tot_amount"];
                    $invoice_amount = $row["invoice_amount"];
                    $vat            = $row["vat"];
                    $tax            = $row["tax"];
                    $delivery_dt    = $row["delivery_dt"];
                    $hrid           = '1';
                    $cname          = $row["name"];
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$orderdt."')</script>";
    } else {
        $uid            = '';
        $poid           = '';
        $supid          = '';
        $orderdt        = date("d/m/Y");
        $currency       = '0';
        $adv            = '';
        $tot_amount     = '0';
        $invoice_amount = '0';
        $vat            = '0';
        $tax            = '0';
        $delivery_dt    = date("Y-m-d");
        $hrid           = '1';

        $mode = 1; //Insert mode

    }

    $currSection = 'challan';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
include_once 'common_header.php';
    ?>
<body class="form soitem">

<?php
include_once 'common_top_body.php';
    ?>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Challan Order</span>
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
                        <form method="post" action="common/addchallan.php" id="form1" enctype="multipart/form-data">
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">




			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>

                                   <div class="row form-header">

	                                     <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h4>Inventory <i class="fa fa-angle-right"></i> Add Purchase Data</h4>
      		                            </div>

      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>


                                   </div>



                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->

                                <div class="row">
                                     <hr class="form-hr">
									<div class="col-sm-12">
										<div class="row">
										
                                            
		                                    <div class="col-sm-12">
	                                            <h4>Purchase Data</h4>
		                                        <hr class="form-hr">
	                                        </div>                                            
                                            
											<div class="col-lg-1">

												 <input type="hidden"  name="pid" id="pid" value="<?php echo $uid; ?>">
												 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>" >
												  <div class="form-group">
													 <label for="po_id">POID<span class="redstar">*</span></label>
													 <input type="text" class="form-control"  name="challanno" id="challanno" value="<?php echo $poid; ?>" readonly>

													</div>
											</div>

											<div class="col-lg-2">
												<div class="form-group">
													<label for="po_id">Voucher No </label>
													<input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $adv ?>" >
												</div>
											</div>
                                            
												<div class="col-lg-2">
													<label for="email">Voucher Date<font color="red">*</font></label>
													<div class="input-group">
														<input type="text" class="form-control datepicker" id="delivery_dt" name="delivery_dt" value="<?php echo $delivery_dt; ?>" required>
														<div class="input-group-addon">
															<span class="glyphicon glyphicon-th"></span>
														</div>
													</div>
												</div>	                                            
											
											<div class="col-lg-1">
												<div class="form-group">
													<label for="po_id">PI No.</label>
													<input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $adv ?>" >
												</div>
											</div>
                                            
												<div class="col-lg-2">
													<label for="email">PI Date<font color="red">*</font></label>
													<div class="input-group">
														<input type="text" class="form-control datepicker" id="delivery_dt" name="delivery_dt" value="<?php echo $delivery_dt; ?>" required>
														<div class="input-group-addon">
															<span class="glyphicon glyphicon-th"></span>
														</div>
													</div>
												</div>												
                                            
											<div class="col-lg-2">
												<div class="form-group">
													<label for="po_id">LC/TT No.</label>
													<input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $adv ?>" >
												</div>
											</div>
                                            
												<div class="col-lg-2">
													<label for="email">LC/TT Date<font color="red">*</font></label>
													<div class="input-group">
														<input type="text" class="form-control datepicker" id="delivery_dt" name="delivery_dt" value="<?php echo $delivery_dt; ?>" required>
														<div class="input-group-addon">
															<span class="glyphicon glyphicon-th"></span>
														</div>
													</div>
												</div>	                                            
                                            
                                            
<!--                                            break-->
                                            
                                        </div>
                                        <div class="row">
                                            
											<div class="col-lg-1">
												<div class="form-group">
													<label for="po_id">AT.</label>
													<input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $adv ?>" >
												</div>
											</div>                                            
                                            
                                  
											<div class="col-lg-1">
												<div class="form-group">
													<label for="po_id">AIT.</label>
													<input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $adv ?>" >
												</div>
											</div>                                                
                                            
                                  
											<div class="col-lg-1">
												<div class="form-group">
													<label for="po_id">GRN No.</label>
													<input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $adv ?>" >
												</div>
											</div> 
                                            
                                            <div class="col-lg-2">
                                                <label for="email">GRN Date<font color="red">*</font></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" id="delivery_dt" name="delivery_dt" value="<?php echo $delivery_dt; ?>" required>
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>                                            
                                            
                                            
											<div class="col-lg-1">
												<div class="form-group">
													<label for="po_id">Ex. Rate</label>
													<input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $adv ?>" >
											  </div>
											</div>   
                                            
                                            
                                            <div class="col-lg-3">
													
                                                <div class="form-group">
                                                   
                                                        <b>Warehouse</b>
                                                        <div class="styled-select">
                                                            
                                                                <select name="storeName[]"   class="storeNamex form-control" required>
                                                                <option value="">Select Warehouse</option>
                                                                <?php $qryitm = "SELECT s.`id`, s.`name` FROM `branch` s order by s.name";

                                                                    $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {
                                                                        while ($rowitm = $resultitm->fetch_assoc()) {
                                                                        $tid = $rowitm["id"];
                                                                        $nm  = $rowitm["name"];
                                                                        ?>
                                                                        <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                                <?php }} ?>
                                                                </select>
                                                            </div>        
                                                        </div>
                                                    
                                            </div>                                            
										
                                            <div class="col-lg-3">
													

                                                    <div class="form-group">
                                                        <b>Received By</b>
                                                        <div class="styled-select">
                                                            <select name="storeName[]" i d="storeName" class="storeName form-control" required>
                                                            <option value="">Select Receiver</option>
                                                            <?php $qryitm = "SELECT s.`id`, s.`name` FROM `branch` s order by s.name";

                                                                $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                    $tid = $rowitm["id"];
                                                                    $nm  = $rowitm["name"];
                                                                    ?>
                                                                    <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                            <?php }} ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>										
                                            
                                            
										</div>
                                        
                                        
                                        <div class="row">
                                        
                                        
     		                                <div class="col-sm-12">
	                                            <h4>Payment Details</h4>
		                                        <hr class="form-hr">
	                                        </div>  
                                            
                                            
                                            <div class="col-lg-3">
													

                                                    <div class="form-group">
                                                        <b>Bank Name</b>
                                                        <div class="styled-select">
                                                            <select name="storeName[]" i d="storeName" class="storeName form-control" required>
                                                            <option value="">Select Receiver</option>
                                                            <?php $qryitm = "SELECT s.`id`, s.`name` FROM `branch` s order by s.name";

                                                                $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                    $tid = $rowitm["id"];
                                                                    $nm  = $rowitm["name"];
                                                                    ?>
                                                                    <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                            <?php }} ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            
                                            
                                            <div class="col-lg-2">
                                                <label for="email">Bank Date</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" id="delivery_dt" name="delivery_dt" value="<?php echo $delivery_dt; ?>" required>
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>                                             
                                            
                                            
											<div class="col-lg-2">
												<div class="form-group">
													<label for="po_id">Payment Amount</label>
													<input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $adv ?>" >
												</div>
											</div> 
                                            
											<div class="col-lg-5">
												<div class="form-group">
													<label for="po_id">Remarks</label>
													<input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $adv ?>" >
												</div>
											</div>                                             
                                            
                                        </div>
                                        
                                        
                                        
									</div>
									


                                    <div class="col-sm-12 wellblock">
                                    
      	



                            	    <br>
                                        <style>
                                        
                                        </style>
                                    
                                            <div class="wellwrap">
                                                <div class="toClone2 pdetail">
                                                <div class="row">
                                                    
                                                    
                                                           
                                                    
                                                    <div class="col-lg-1">
                                                        
                                                        <label>Model No./ Barcode No.</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="Barcode" name="description[]">
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="col-lg-1">
                                                        <label>Com Inv Val (USD/EURO)</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="Commercial Invoice Value (USD/EURO)" name="description[]">
                                                        </div>
                                                    </div> 
                                                    
                                                    
                                                    <div class="col-lg-1">
                                                        <label>Com Inv Val (BDT)</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="Commercial Invoice Value (BDT)" name="description[]">
                                                        </div>
                                                    </div>                                                     
                                                    
                                                    <div class="col-lg-1">
                                                        <label>Freight Charges</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="Freight" name="description[]">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-1">
                                                        <label>Global Taxes</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="Global Taxes" name="description[]">
                                                        </div>
                                                    </div> 
                                                    
                                                    
                                                    <div class="col-lg-1">
                                                        <label>CD</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="CD" name="description[]">
                                                        </div>
                                                    </div>  
                                                    <div class="col-lg-1">
                                                        <label>RD</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="RD" name="description[]">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-1">
                                                        <label>SD</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="SD" name="description[]">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-1">
                                                        <label>VAT</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="VAT" name="description[]">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <label>Quantity</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="Quantity" name="description[]">
                                                        </div>
                                                    </div> 
                                                    
                                                    <div class="col-lg-1">
                                                        <label>Total Landed  Cost</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="Total Landed  Cost" name="description[]">
                                                        </div>
                                                    </div> 
                                                    
                                                    <div class="col-lg-1">
                                                        <label>Total Value</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"  placeholder="Total Value" name="description[]">
                                                        </div>
                                                    </div>                                                     
                                                    
                                                  </div>  
                                                </div>
                                            </div>                                    
                                    
                                            <div class="wellwrap">
                                                        <div class="toClone2 pdetail">
                                                        <div class="row">




                                                            <div class="col-lg-1">

                                                                <label>Model No./ Barcode No.</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="Barcode" name="description[]">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                                <label>Com Inv Val (USD/EURO)</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="Commercial Invoice Value (USD/EURO)" name="description[]">
                                                                </div>
                                                            </div> 


                                                            <div class="col-lg-1">
                                                                <label>Com Inv Val (BDT)</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="Commercial Invoice Value (BDT)" name="description[]">
                                                                </div>
                                                            </div>                                                     

                                                            <div class="col-lg-1">
                                                                <label>Freight Charges</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="Freight" name="description[]">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                                <label>Global Taxes</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="Global Taxes" name="description[]">
                                                                </div>
                                                            </div> 


                                                            <div class="col-lg-1">
                                                                <label>CD</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="CD" name="description[]">
                                                                </div>
                                                            </div>  
                                                            <div class="col-lg-1">
                                                                <label>RD</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="RD" name="description[]">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                                <label>SD</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="SD" name="description[]">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                                <label>VAT</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="VAT" name="description[]">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <label>Quantity</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="Quantity" name="description[]">
                                                                </div>
                                                            </div> 

                                                            <div class="col-lg-1">
                                                                <label>Total Landed  Cost</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="Total Landed  Cost" name="description[]">
                                                                </div>
                                                            </div> 

                                                            <div class="col-lg-1">
                                                                <label>Total Value</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"  placeholder="Total Value" name="description[]">
                                                                </div>
                                                            </div>                                                     

                                                          </div>  
                                                        </div>
                                                    </div>
                                   
                                        
                                        
                                        
                                        <div class="po-product-wrapper">
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Item Detail  </h4>
		                                        <hr class="form-hr">
	                                        </div>
                                           
                                            <div class="clear"><hr></div>
                                             
                                            
	                                        <div class="toClone">
          	                                    <div class="col-lg-4 col-md-6 col-sm-6">
													<lebel class="hidden-lg">Item Name</lebel>
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm = "SELECT `id`, code,`name`, `vat`, `ait`, `cost`,rate  FROM `item`  order by name";
        $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
            $tid  = $rowitm["id"];
            $nm   = $rowitm["name"].'-('.$rowitm["code"].')';
            $cost = $rowitm["cost"];
            $up   = number_format($rowitm["cost"], 2);
            $vat  = number_format($rowitm["vat"], 2);
            $ait  = number_format($rowitm["ait"], 2);
            $rate = number_format($rowitm["rate"], 2);
            ?>
                                                                <option data-value="<?php echo $tid; ?>" data-up="<?php echo $up; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" data-mrp="<?php echo $rate; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php }} ?>
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for itemName-->
                                                <div class="col-lg-2 col-md-6 col-sm-6 col-xs-4">

                                                    <div class="row qtnrows">
                                                        <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                        <div class="col-lg-12 col-sm-6 col-xs-12">
															<lebel class="hidden-lg">Quantity</lebel>
                                                            <div class="form-group">
                                                                <input type="number" min="1" class="form-control quantity_otc_po" id="quantity_otc_po" placeholder="Quantity" name="quantity_otc_po[]" value="1" required>
                                                                <input type="hidden" class="form-control quantity_otc" id="quantity_otc" placeholder="Quantity" name="quantity_otc[]" required>
                                                            </div>
                                                        </div>
                                                        <!--<div class="col-lg-6 col-sm-6 col-xs-6">
															<lebel>OTC</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc_po unitPriceV2" id="unitprice_otc_po" placeholder="OTC" name="unitprice_otc_po[]" required>
                                                                <input type="hidden" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" name="unitprice_otc[]" class="unitprice_otc">
                                                                <input type="hidden" name="unitTotalAmount_otc" class="unitTotalAmount_otc">
                                                            </div>
                                                        </div>-->
                                                        <input type="hidden" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" name="unitprice_otc[]" class="unitprice_otc" value="0">
                                                        <input type="hidden" name="unitTotalAmount_otc" class="unitTotalAmount_otc" value="0">
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc-->
                                                <!--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
													<lebel>Unit Total</lebel>
                                                    <div class="col-sm-6 col-xs-6">
                                                        <div class="form-group">
                                                             <input type="text" class="form-control unitTotalAmount1" id="unittotal1" placeholder="Unit Total" readonly  name="unittotal1[]">
                                                            <input type="hidden"  class="form-control unitTotalAmount" name="unittotal[]" id="unittotal">
                                                        </div>
                                                   </div>
                                                    <div class="col-sm-6 col-xs-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control unitpricemrc" id="unitpricemrc" placeholder="MRP" name="unitpricemrc[]"  value="<?php echo $mrp; ?>" required>
                                                        </div>
                                                    </div>
                                                </div>--> <!-- this block is for unit total, MRP-->
                                                
                                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-8">
													<lebel class="hidden-lg">Warehouse</lebel>
                                                        <input type="hidden" class="form-control unitTotalAmount1" id="unittotal1" placeholder="Unit Total"   name="unittotal1[]">
                                                        <input type="hidden"  class="form-control unitTotalAmount" name="unittotal[]" id="unittotal" value="0">
                                                        <input type="hidden" class="form-control unitpricemrc" id="unitpricemrc" placeholder="MRP" name="unitpricemrc[]"  value="0" >
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                        <select name="storeName[]" i d="storeName" class="storeName form-control" required>
                                                        <option value="">Select Warehouse</option>
    <?php $qryitm = "SELECT s.`id`, s.`name` FROM `branch` s order by s.name";

        $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
            $tid = $rowitm["id"];
            $nm  = $rowitm["name"];
            ?>
                                                            <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
    <?php }} ?>
                                                        </select>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for Store-->
                                                <!--div class="col-lg-2 col-md-6 col-sm-6">
                                                     <div class="form-group">
                                                        <input type="text" class="form-control datepicker"  placeholder="Expirydt" name="expdt[]">
                                                  </div>
                                                </div--><!-- this block is for Expiry date-->
          	                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
													<lebel class="hidden-lg">Description</lebel>
												    <div class="form-group">
                                                        <input type="text" class="form-control"  placeholder="Description" name="description[]">
                                                    </div>
                                                </div> <!-- this block is for remarks-->



                                                 <!-- this block is for unittotal-->
                                                <!--div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div -->  <!-- this block is for remarks-->
                                            </div>

                                    		<!-- this block is for php loop, please place below code your loop  -->
                                        </div>



                                        <div class="well no-padding top-bottom-border grandTotalWrapper">
                                        <!--div class="row total-row">
                                            <div class="col-xs-offset-7 col-xs-6 col-sm-offset-9 col-sm-4  col-md-offset-9 col-md-4 col-lg-offset-9 col-lg-1">
                                            <div class="form-group grandTotalWrapper">
                                                <label>Total:*</label>
                                                <input type="text" class="form-control" id="grandTotal" value="<?php echo $itdgt; ?>" disabled required>
                                              </div>
                                          </div>
                                          </div-->
                                          <input type="hidden" class="form-control" id="grandTotal" value="<?php echo $itdgt; ?>">
                                      </div>


                                    </div>
                                    
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
                                    <?php
//echo $mode;
    $addClassName = ($mode == "1") ? 'link-add-po' : 'link-add-po-2';
    ?>
        	                            <a href="#" class="<?=$addClassName ?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
                                    <br><br>&nbsp;<br><br>

                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        <div class="form-group">

                                            <label for="details">Details </label>

                                            <textarea class="form-control" id="details" name="details" rows="4" ><?php echo $details; ?></textarea>

                                        </div>

                                    </div>



                                    <div class="col-sm-12">

                                            <?php if ($mode == 2) { ?>
                                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Order" id="update" >
                                          <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="copy" value="Copy SO" id="Copy"-->
                                          <?php } else { ?>
                                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Stock" id="add" >
                                          <?php } ?>
                                        <a href = "./inv_soitemList.php?pg=1&mod=12">
                                          <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                                        </a>


                                    </div>

                                </div>

                        </div>
                    </div>
        <!-- /#end of panel -->

          <!-- START PLACING YOUR CONTENT HERE -->
           </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
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
    $(document).ready(function(){

        $(document).on("change", ".dl-itemName", function() {

            var val = $(this).val();

            var cost = $('#itemName option[value="' + val +'"]').attr('data-cost');
            var untprc = $('#itemName option[value="' + val +'"]').attr('data-up');
            //var untprc=cost.toFixed(2);
           
             $(this).closest('.toClone').find('.unitprice_otc_po').val(untprc);
            $(this).closest('.toClone').find('.unitprice_otc').val(cost);
            //$(this).closest('.toClone').find('.unitprice_otc1').val(untprc);
			$(this).closest('.toClone').find('.quantity_otc_po').val(1);
			$(this).closest('.toClone').find('.quantity_otc').val(1);
			
            $(this).closest('.toClone').find('.unitTotalAmount1').val(untprc);
            $(this).closest('.toClone').find('.unitTotalAmount').val(cost);


            var vat = $('#itemName option[value="' + val +'"]').attr('data-vat');
            $(this).closest('.toClone').find('.vat').val(vat);
            var ait = $('#itemName option[value="' + val +'"]').attr('data-ait');
            $(this).closest('.toClone').find('.ait').val(ait);
            
             var  mrpunit = $('#itemName option[value="' + val +'"]').attr('data-mrp');
            $(this).closest('.toClone').find('.unitpricemrc').val(mrpunit);


	//alert(222);
    var sum = 0;
    $(".unitTotalAmount").each(function(){

		sum += +$(this).val();
	   sum1=sum.toFixed(2);
	   //alert(sum1);
         $("#grandTotal").val(sum1);
        });

    });


})
</script>

<!--script>
    $(document).ready(function(){

        $(document).on("change", ".dl-itemName", function() {

            var val = $(this).val();

            var cost = $('#itemName option[value="' + val +'"]').attr('data-cost');

            $(this).closest('.toClone').find('.unitprice_otc').val(cost);
			$(this).closest('.toClone').find('.quantity_otc').val(1);
            $(this).closest('.toClone').find('.unitTotalAmount').val(cost);


            var vat = $('#itemName option[value="' + val +'"]').attr('data-vat');
            $(this).closest('.toClone').find('.vat').val(vat);

            var ait = $('#itemName option[value="' + val +'"]').attr('data-ait');

            $(this).closest('.toClone').find('.ait').val(ait);

             var mrp = $('#itemName option[value="' + val +'"]').attr('data-mrp');
            $(this).closest('.toClone').find('.unitpricemrc').val(mrp);




	//alert(222);
    var sum = 0;
    $(".unitTotalAmount").each(function(){

		sum += +$(this).val();
	   sum1=sum.toFixed(2);
         $("#grandTotal").val(sum1);
  });





    });

})
</script -->

<script language="javascript">


<?php
if ($res == 4) {
        ?>

//alert($(".cmb-parent").children("option:selected").val());

var selectedValue = $(".cmb-parent").children("option:selected").val();

	 $.ajax({
            type: "POST",
            url: "cmb/so_item_customer_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
			beforeSend: function(){
					$(".cmd-child").html("<option>Loading...</option>");
				},

        }).done(function(data){
            //root.find(".measure-unit").html(data);

			$(".cmd-child").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child").append(data);

			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });



    $.ajax({
            type: "POST",
            url: "cmb/so_item_poc_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
			beforeSend: function(){
					$(".cmd-child1").html("<option>Loading...</option>");
				},

        }).done(function(data){
            //root.find(".measure-unit").html(data);

			$(".cmd-child1").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child1").append(data);

			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });

<?php
}
    ?>

$(document).on("change", ".cmb-parent", function() {

	//alert($(this).children("option:selected").val());
	//var root = $(this).parent().parent().parent().parent();	// root means .toClone
	var selectedValue = $(this).children("option:selected").val();

	 $.ajax({
            type: "POST",
            url: "cmb/so_item_customer_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
			beforeSend: function(){
					$(".cmd-child").html("<option>Loading...</option>");
				},

        }).done(function(data){
            //root.find(".measure-unit").html(data);

			$(".cmd-child").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child").append(data);

			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });

        $.ajax({
            type: "POST",
            url: "cmb/so_item_poc_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
			beforeSend: function(){
					$(".cmd-child1").html("<option>Loading...</option>");
				},

        }).done(function(data){
            //root.find(".measure-unit").html(data);

			$(".cmd-child1").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child1").append(data);

			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });

});

/*  autofill combo  */

 var dataList=[];
$(".list-itemName").find("option").each(function(){dataList.push($(this).val())})

/*
//print dataList array
 $.each(dataList, function(index, value){
           $(".alertmsg").append(index + ": " + value + '<br>');
});
*/

/* Check wrong category */
var catlavel;
var flag;

//$(".dl-itemName").change(function(){
$(document).on("change", ".dl-itemName", function() {


	//alert($(this).val());
	var root = $(this).parent().parent().parent().parent();
	root.find(".itemName").attr('style','border:1px solid red!important;');




	for(var i in dataList) {
		userinput = $(this).val();
	 	catlavel = dataList[i];

		//$(".alertmsg").append(dataList[i]+ '<br>');

		if(userinput === catlavel){
			flag = 1;

			//root.find(".itemName").val($(this).val());
			//alert($(this).attr("thisval"));

				var g = $(this).val();
				var id = $('#itemName option[value="' + g +'"]').attr('data-value');
			  //alert(id);
			root.find(".itemName").val(id);
			break;
		}else{
			flag = 0;
		}
	}
	if(flag == 0){
		$(this).val("");
		}

	});
/* end Check wrong category */

/* end autofill combo  */



</script>

<script>
    //Searchable dropdown
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
        $('#cmbsupnm').val(id);
        //alert(id);


        //Change Contact Name
        $.ajax({
            type: "POST",
            url: "cmb/get_data.php",
            data: { key : id, type: 'orgtocontact' },
			beforeSend: function(){
					$("#cmbsupnm").html("<option>Loading...</option>");
				},

        }).done(function(data){
			$("#cmbsupnm").empty();
			$("#cmbsupnm").append(data);
			//alert(data);
        });


	});
</script>

<script>
    //alert("s");
$(document).ready(function(){

	
	
			//existing item list
             $('.ds-list').attr('style','display:none');
			
			//one entry input box div
             $('.ds-add-list').attr('style','display:none');

             //Input Click

            $('.input-box').click(function(){
                $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
            });

            //Option's value shows on input box

            $('.input-ul').on("click","li", function(){
               // console.log(this);

                if(!$(this).hasClass("addnew")){

                    let litxt= $(this).text();
                    let lival= $(this).val();

                    $("#org_id").val(lival);
                    $.ajax({
                        type: "POST",
                        url: "cmb/get_data.php",
                        data: { key : lival, type: 'orgtocontact' },
                        beforeSend: function(){
                        	$("#cmbsupnm").html("<option>Loading...</option>");
                        },
                        
                        }).done(function(data){
                            $("#cmbsupnm").empty();
                        	$("#cmbsupnm").append(data);
                            //alert(data);
                        });
					$(this).closest('.ds-divselect-wrapper').find('.input-box').val(litxt);
					$(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value',litxt);

                    // $(this).closest('.ds-add-list').attr('style','display:none');
                    $(this).closest('.ds-list').attr('style','display:none');
                }

            });
	

	
            // New input box display


	
	
	
	/* no need for now
	
            // New-Input box's value display on old-input box

            $('.ds-add-list-btn').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
                $(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value', x);
				$(this).closest('.ds-divselect-wrapper').find('.input-box').val(x);
                $(this).closest('.ds-add-list').attr('style','display:none');
                //$(this).closest('.ds-add-list').find('.addinpBox').val('');
                console.log($(this).closest('.ds-add-list').find('.addinpBox').val(""));
                // alert(x);
                // }
                action(x);
                function action(x){
                    $.ajax({
                        url:"phpajax/divSelectOrg.php",
                        method:"POST",
                        data:{newItem: x},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#org_id").val(res.id);
                                $('.display-msg').html(res.name);
                                messageAlertLong(res,'alert-success');

                            }
                    });
	             }


            });
	
	
	*/
	
	
            $(document).mouseup(function (e) {
                if ($(e.target).closest(".ds-list").length === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length  === 0) {
                    $(".ds-add-list").hide();
                }
            });	
	
	
            $('.input-box').on("keyup", function() {
			    //alert($(this).val());
			    var searchKey = $(this).val().toLowerCase();
                $(this).closest('.ds-divselect-wrapper').find(".input-ul li ").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchKey)>-1);
                });
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			    //$(this).closest('.ds-divselect-wrapper').find('.input-ul li').click(function(){
				$(this).closest('.ds-divselect-wrapper').find('.input-ul').on("click","li", function(){
                     //
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
						//alert(x);
                        $(this).closest('.ds-divselect-wrapper').val(x);
                        $(this).closest('.ds-list').attr('style','display:none');
                    }
                })

                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){
					
                   // $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                   // $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
					
					
                });

			});	
	
            $('.input-ul .addnew').click(function(){
               // $(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
				addNewOrg();
                $(this).closest('.ds-list').attr('style','display:none');
            });	
	
	

	function addNewOrg(){
		
				BootstrapDialog.show({

											title: 'Add New Organization',
											//message: '<div id="printableArea">'+data+'</div>',
											message: $('<div></div>').load('addselect_modal_org_tab.php'),
											type: BootstrapDialog.TYPE_PRIMARY,
											closable: false, // <-- Default value is false
											draggable: true, // <-- Default value is false
											buttons: [{
												//icon: 'glyphicon glyphicon-print',
												cssClass: 'btn-primary',
												id: 'btn-1',
												label: 'Save',
												action: function(dialog) {

													var $button = this;
													$button.hide();

													dialog.setClosable(false);

													var orgtype = $('#org-type').serializeArray();
													//alert($("#orgtype").val());

													if(orgtype[0].value == 1){
														var ajxdata = $('#form-org').serializeArray();
														
														if(!ajxdata[0].value || !ajxdata[1].value || !ajxdata[3].value || !ajxdata[4].value || !ajxdata[5].value || !ajxdata[6].value){
                    										
                    										var msg ="";
															//alert(msg.length);
                    										if(!ajxdata[0].value){
                    										    msg = "Please Enter Name!*"; $("#cnnm").focus(); 
                    										}else if(!ajxdata[1].value){
                    										    msg = "Please Enter Industry Type!"; $("#cmbindtype").focus();
                    										}else if(!ajxdata[3].value){
                    										    msg = "Please Enter Address!"; $("#address").focus();
                    										}else if(!ajxdata[4].value){
                    										    msg = "Please Enter Contact Name!"; $("#contactname").focus();
                    										}else if(!ajxdata[5].value){
                    										    msg = "Please Enter Contact Email!"; $("#contactemail").focus();
                    										}else if(!ajxdata[6].value){
                    										    msg = "Please Enter Cotact Phone Number!"; $("#contactphone").focus();
                    										}
															
															if(msg.length>0){
															  $.alert({
																title: "Warning",
																escapeKey: true,
																content: msg,
																backgroundDismiss: true,
																confirmButton: 'OK',
																buttons: {
																OK: {
																	keys: ["enter"],
																},
															   },
															}); //alert('Please enter name'); 
															$button.show();
																return false;
															}
                    									
                    									
                    									}
													}else{
														var ajxdata = $('#form-indi').serializeArray();
														
														if(!ajxdata[0].value || !ajxdata[1].value || !ajxdata[3].value || !ajxdata[4].value || !ajxdata[5].value || !ajxdata[6].value){
                    										
                    										var msg ="";
                    										if(!ajxdata[0].value){
                    										    msg = "Please Enter Name!"; // $("#indv_name").focus();
                    										}else if(!ajxdata[1].value){
                    										    msg = "Please Enter Email!"; $("#contemail").focus();
                    										}else if(!ajxdata[2].value){
                    										    msg = "Please Enter Phone Number!"; $("#contphone").focus();
                    										}else if(!ajxdata[4].value){
                    										    msg = "Please Enter Address!"; $("#ind_address").focus();
                    										}else if(!ajxdata[5].value){
                    										    msg = "Please Enter District!"; $("#district").focus();
                    										}else if(!ajxdata[7].value){
                    										    msg = "Please Enter Country!"; $("#country").focus();
                    										}

															if(msg.length>0){
																$.alert({
																title: "Warning",
																escapeKey: true,
																content: msg,
																backgroundDismiss: true,
																buttons: {
																OK: {
																	keys: ["enter"],
																},
															   },
															}); //alert('Please enter name'); 
															$button.show();

															return false;
															}
                    									}
													}
													
											//alert(ajxdata[0].value);
													//return false;
											
									
													$.ajax({
														  type: "POST",
														  url: 'phpajax/divSelectOrg.php',
														  data: {data: ajxdata, type: orgtype[0].value},
														  type: 'POST',
														  dataType:"json",
														  success: function(res){

															  //dialog.setMessage("Success");


															  $("#org_id").val(res.id);
															  
															  $('.input-box').attr('value',res.name+"("+res.contact+")");
															  $("#inpUl").append("<li class='pp1' value = '"+res.id+"'>"+res.name+"("+res.contact+")"+"</li>");
															  
															  $.ajax({
                                                                    type: "POST",
                                                                    url: "cmb/get_data.php",
                                                                    data: { key : res.id, type: 'orgtocontact' },
                                                        			beforeSend: function(){
                                                        					$("#cmbsupnm").html("<option>Loading...</option>");
                                                        				},
                                                        		 
                                                                }).done(function(data){
                                                        			$("#cmbsupnm").empty();
                                                        			$("#cmbsupnm").append(data);
                                                        			//alert(data);
                                                                });

														        dialog.close();
				//                                           
														  }
														});


												/*var $button = this;
												//$button.hide();
												//dialogItself.close();
												//$button.spin();
												dialog.setClosable(false);



												var obj = [];

												var cdata = {};


												 cdata.name = $("#new-cat-field").val();



												//check user data;
												  if(!$("#new-cat-field").val()){alert('Please enter category name'); $button.show(); return false;}


												 obj.push(cdata);

												var dataString = JSON.stringify(obj);



												/*alert(dataString);

												$.ajax({
												   url: 'phpajax/cmb_add_category.php',
												   data: {posData: dataString},
												   type: 'POST',
												   dataType:"json",
												   success: function(res) {

													   if(res != 0){
															// dialog.setMessage(res.query);
														   //$("#new-cat-field").val(res.name);
														   $("#old-prod-cart-field").val(res.name);
														   $("#catID").val(res.id);
														   $("#catID").attr('data-name',res.name);
														   //document.title = res.name;
														  // dialogItself.close();
														  dialog.setMessage(res.msg);
														  setTimeout(function(){
																dialog.close();
															  },2000);

													   }else{
														   alert("Something went wrong!!!");
													   }

												   }
												});  */




												},
											}, {
												label: 'Close',
												action: function(dialogItself) {
													dialogItself.close();
												}
											}]
										});			
		
	}
	
});

                                   


</script>

</body>
</html>
<?php } ?>