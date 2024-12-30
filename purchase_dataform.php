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
    $poid      = $_GET["poid"];
    $totamount = 0;

    if ($res == 1) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
        $mode = 1;
    } elseif ($res == 2) {
        $qry = "SELECT `id`, `poid`, `voucher_no`,DATE_FORMAT(`voucher_date`, '%d/%m/%Y') `voucher_date`, `pi_no`, DATE_FORMAT(`pi_date`, '%d/%m/%Y') `pi_date`, 
                `lc_tt_no`, DATE_FORMAT(`lc_tt_date`, '%d/%m/%Y') `lc_tt_date`, `at`, `ait`, `gnr_no`,DATE_FORMAT(`gnr_date`, '%d/%m/%Y') `gnr_date`, `exchange_rate`,branch,
                `warehouse`, `received_by`, `payment_detail`, `payment_amount`, `bank_name`,DATE_FORMAT(`bank_dt`, '%d/%m/%Y') `bank_dt`, `remark`, `st`, currency FROM `purchase_landing` 
                WHERE `poid` = '".$poid."'";
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $pid                = $row["id"];
                    $voucher_no         = $row["voucher_no"];
                    $voucher_date       = $row["voucher_date"];
                    $pi_no              = $row["pi_no"];
                    $pi_date            = $row["pi_date"];
                    $lc_tt_no           = $row["lc_tt_no"];
                    $lc_tt_date         = $row["lc_tt_date"];
                    $at                 = $row["at"];
                    $ait                = $row["ait"];
                    $gnr_no             = $row["gnr_no"];
                    $gnr_date           = $row["gnr_date"];
                    $cntnr_no             = $row["containerno"];
                    $exchange_rate      = $row["exchange_rate"];
                    $warehouse          = $row["warehouse"];
                    $received_by        = $row["received_by"];
                    $payment_amount     = $row["payment_amount"];
                    $bank_name          = $row["bank_name"];
                    $bank_dt            = $row["bank_dt"];
                    $remark             = $row["remark"];
                    $sts                = $row["st"];
                    $currency           = $row["currency"];
                    $branch             = $row["branch"];
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$orderdt."')</script>";
    } else {
        

        $mode = 1; //Insert mode

    }

    $currSection = 'purchasedata';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
include_once 'common_header.php';
    ?>
<body class="form soitem">
<style>
.grandTotalWrapper input{
    padding-right: 35px;
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
            <span>Purchase Order</span>
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
                        <form method="post" action="common/addpurchasedata.php?mod=12" id="form1" enctype="multipart/form-data">
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

												 <input type="hidden"  name="pid" id="pid" value="<?php echo $pid; ?>">
												 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>" >
												  <div class="form-group">
													 <label for="po_id">POID<span class="redstar">*</span></label>
													 <input type="text" class="form-control"  name="poid" id="poid" value="<?php echo $poid; ?>" readonly>

													</div>
											</div>

											<div class="col-lg-2">
												<div class="form-group">
													<label for="po_id">Voucher No </label>
													<input type="text" class="form-control" name="vouchno" id="vouchno" value="<?php echo $voucher_no ?>" >
												</div>
											</div>
                                            
												<div class="col-lg-2">
													<label for="email">Voucher Date<font color="red">*</font></label>
													<div class="input-group">
														<input type="text" class="form-control datepicker" id="vouchdt" name="vouchdt" value="<?php echo $voucher_date; ?>" required>
														<div class="input-group-addon">
															<span class="glyphicon glyphicon-th"></span>
														</div>
													</div>
												</div>	                                            
											
											<div class="col-lg-1">
												<div class="form-group">
													<label for="po_id">PI No.</label>
													<input type="text" class="form-control" name="pino" id="pino" value="<?php echo $pi_no ?>" >
												</div>
											</div>
                                            
												<div class="col-lg-2">
													<label for="email">PI Date<font color="red">*</font></label>
													<div class="input-group">
														<input type="text" class="form-control datepicker" id="pidt" name="pidt" value="<?php echo $pi_date; ?>" required>
														<div class="input-group-addon">
															<span class="glyphicon glyphicon-th"></span>
														</div>
													</div>
												</div>												
                                            
											<div class="col-lg-2">
												<div class="form-group">
													<label for="po_id">LC/TT No.</label>
													<input type="text" class="form-control" name="ttno" id="ttno" value="<?php echo $lc_tt_no ?>" >
												</div>
											</div>
                                            
												<div class="col-lg-2">
													<label for="email">LC/TT Date<font color="red">*</font></label>
													<div class="input-group">
														<input type="text" class="form-control datepicker" id="ttdt" name="ttdt" value="<?php echo $lc_tt_date; ?>" required>
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
													<label for="po_id">AT. <font color="red">*</font></label>
													<input type="text" class="form-control" name="at" id="at" value="<?php echo $at ?>" required>
												</div>
											</div>                                            
                                            
                                  
											<div class="col-lg-1">
												<div class="form-group">
													<label for="po_id">AIT. <font color="red">*</font></label>
													<input type="text" class="form-control" name="ait" id="ait" value="<?php echo $ait ?>" required>
												</div>
											</div>                                                
                                            
                                  
											<div class="col-lg-1">
												<div class="form-group">
													<label for="po_id">GRN No.</label>
													<input type="text" class="form-control" name="grn" id="grn" value="<?php echo $gnr_no ?>" >
												</div>
											</div> 
                                            
                                            <div class="col-lg-2">
                                                <label for="email">GRN Date<font color="red">*</font></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" id="grndt" name="grndt" value="<?php echo $gnr_date; ?>" required>
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div> 
                                            </div>
                                            
                                            <div class="col-lg-1">
												<div class="form-group">
													<label for="container no">Container No <font color="red">*</font></label>
													<input type="text" class="form-control" name="containerno" id="containerno" value="<?php echo $cntnr_no ?>" required>
												</div>
											</div>
											
                                            <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <b>Warehouse<font color="red">*</font></b>
                                                        <div class="styled-select">
                                                            <select name="branch" id="branch" class="storeName form-control" required>
                                                            <option value="">Select Warehouse</option>
                                                            <?php $qryitm = "SELECT s.`id`, s.`name` FROM `branch` s where s.id not in(2,3,5,6,7,8,9,10) order by s.name";

                                                                $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                    $tid = $rowitm["id"];
                                                                    $nm  = $rowitm["name"];
                                                                    ?>
                                                                    <option value="<?php echo $tid; ?>" <?php if ($branch == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                            <?php }} ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            
                                            
                                            <div class="col-lg-2">
													
                                                <div class="form-group">
                                                   
                                                        <b>Supplier</b>
                                                        <div class="styled-select">
                                                            
                                                                <select name="storeName"   class="storeNamex form-control" required>
                                                                <option value="0">Select Supplier</option>
                                                                <?php $qryitm = "SELECT s.`id`, s.`name` FROM `suplier` s order by s.name";

                                                                    $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {
                                                                        while ($rowitm = $resultitm->fetch_assoc()) {
                                                                        $tid = $rowitm["id"];
                                                                        $nm  = $rowitm["name"];
                                                                        ?>
                                                                        <option value="<?php echo $tid; ?>" <?php if ($warehouse == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                                <?php }} ?>
                                                                </select>
                                                            </div>        
                                                        </div>
                                                    
                                            </div>                                            
										
                                            <div class="col-lg-2">
													

                                                    <div class="form-group">
                                                        <b>Received By<font color="red">*</font></b>
                                                        <div class="styled-select">
                                                            <select name="received" id="received" class="storeName form-control" required>
                                                            <option value="">Select Receiver</option>
                                                            <?php $qryitm = "SELECT emp.id, concat(emp.firstname, ' ', emp.lastname) nm FROM employee emp WHERE emp.id != 1 order by emp.firstname";

                                                                $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                    $tid = $rowitm["id"];
                                                                    $nm  = $rowitm["nm"];
                                                                    ?>
                                                                    <option value="<?php echo $tid; ?>" <?php if ($received_by == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
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
                                            
                                            
                                            <div class="col-lg-2">
													

                                                    <div class="form-group">
                                                        <b>Bank Name<font color="red">*</font></b>
                                                        <div class="styled-select">
                                                            <select name="bank" id="bank" class="storeName form-control" required>
                                                            <option value="">Select Bank</option>
                                                            <?php $qryitm = "SELECT s.`id`, s.`name` FROM `bank` s where s.isAccount='y' order by s.name";

                                                                $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                    $tid = $rowitm["id"];
                                                                    $nm  = $rowitm["name"];
                                                                    ?>
                                                                    <option value="<?php echo $tid; ?>" <?php if ($bank_name == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                            <?php }} ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            
                                            
                                            <div class="col-lg-2">
                                                <label for="email">Bank Date<font color="red">*</font></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" id="bankdt" name="bankdt" value="<?php echo $bank_dt; ?>" required>
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>                                             
                                            
                                            
											<div class="col-lg-2">
												<div class="form-group">
													<label for="po_id">Payment Amount<font color="red">*</font></label>
													<input type="text" class="form-control" name="payamount" id="payamount" value="<?php echo $payment_amount ?>" required>
												</div>
											</div> 
											<div class="col-lg-2">
													

                                                    <div class="form-group">
                                                        <b>Currency<font color="red">*</font></b>
                                                        <div class="styled-select">
                                                            <select name="currency" id="currency" class="form-control" required>
                                                            <option value="">Select Currency</option>
                                                            <?php $qryitm = "SELECT s.`id`, s.`name` FROM `currency` s order by s.name asc";

                                                                $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                    $tid = $rowitm["id"];
                                                                    $nm  = $rowitm["name"];
                                                                    ?>
                                                                    <option value="<?php echo $tid; ?>" <?php if ($currency == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                            <?php }} ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
											<div class="col-lg-2">
												<div class="form-group">
													<label for="po_id">Ex. Rate</label>
													<input type="number" class="form-control" name="ex_rate" id="ex_rate" value="<?php echo $exchange_rate ?>" oninput="validateInput(this)">
											  </div>
											</div>   
                                            
											<div class="col-lg-2">
												<div class="form-group">
													<label for="po_id">Remarks</label>
													<input type="text" class="form-control" name="remarks" id="remarks" value="<?php echo $remark ?>" >
												</div>
											</div>                                             
                                            
                                        </div>
                                        
                                        
                                        
									</div>
									


                                    <div class="col-sm-12 wellblock">
                                    
      	



                            	    <br>
                                        <style>
                                        
                                        </style>
                                    
                                        
                                        
                                        <div class="po-product-wrapper withlebel">
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Item Detail  </h4>
		                                        <hr class="form-hr">
	                                        </div>
                                           
                                            <div class="row form-grid-bls  hidden-md hidden-sm hidden-xs">
											
											
                                                <div class="col-lg-1 col-md-5 col-sm-6">
                                                	<h6 class="chalan-header mgl10"> Model No./ Barcode No. <font color="red">*</font></h6>
                                                </div>

												<div class="col-lg-1 col-sm-1 col-xs-6">
													<h6 class="chalan-header"> Com Inv Val (USD/EURO) <font color="red">*</font></h6>
												</div>
												<div class="col-lg-1 col-sm-1 col-xs-6">
													<h6 class="chalan-header"> Com Inv Val (BDT) <font color="red">*</font></h6>
												</div>			
												
												<div class="col-lg-1 col-md-1 col-sm-6">
                                                    <h6 class="chalan-header"> Freight Charges</h6>
                                                </div>

                                                <div class="col-lg-1 col-md-1 col-sm-6">
                                                    <h6 class="chalan-header">Global Taxes </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-6">
                                                    <h6 class="chalan-header"> CD </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-6">
                                                    <h6 class="chalan-header"> RD </h6>
                                                </div>											
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header"> SD </h6>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header"> VAT </h6>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header"> Quantity<font color="red">*</font> </h6>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header"> Total Landed Cost </h6>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header"> Total Value </h6>
                                                </div>
                                                
                                        </div>
                                             
<?php if($mode == 1){ ?>
	                                        <div class="toClone">
          	                                      <div class="row">
                                                            
                                                            <div class="col-lg-1">
													            <label class="hidden-lg">Item Name</label>
                                                                <div class="form-group">
                                                                    <div class="form-group styled-select">
                                                                        <input type="text" list="itemName"  autocomplete = "off" name="itmnm[]"  class="dl-itemName datalist" placeholder="Select Item" required>
            															<input type="hidden" class = "barcode" name="barcode[]" value="" class="itemName">
                                                                        <datalist  id="itemName" class="list-itemName form-control">
                                                                            <option value="">Select Item</option>
                <?php 
            			$qryitm = "SELECT i.id, i.name,i.barcode
            						FROM item i where forstock in(1,2)
            						order by i.name";								 
            									 
                    $resultitm = $conn->query($qryitm);
                    if ($resultitm->num_rows > 0) 
                    {
                        while ($rowitm = $resultitm->fetch_assoc())
                        {
                        $tid  = $rowitm["id"];
            			$code  = $rowitm["barcode"];
                        $nm   = $rowitm["name"];
                        ?>
                                                                            
                        																<option class="option-<?=$tid?>" data-value="<?=$tid?>" data-barcode="<?=$code?>" value="<?=$nm?>-[barcode: <?= $code ?>]"></option>																
                            <?php }} ?>
                                                                                    </datalist>
                                                                                </div>
                                                                            </div>
                                                                        </div> <!-- this block is for itemName-->
												

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control civu"  placeholder="Commercial Invoice Value (USD/EURO)" name="civu[]" id ="civu[]" min = "0">
                                                                </div>
                                                            </div> 


                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control civb"  placeholder="Commercial Invoice Value (BDT)" name="civb[]" id ="civb[]" readonly>
                                                                </div>
                                                            </div>                                                     

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control freight"  placeholder="Freight" name="freight[]" id ="freight[]" min = "0">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control taxes"  placeholder="Global Taxes" name="taxes[]" id ="taxes[]" min = "0">
                                                                </div>
                                                            </div> 


                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control cd"  placeholder="CD" name="cd[]" id ="cd[]" min = "0">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control rd"  placeholder="RD" name="rd[]" id ="rd[]" min = "0">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control sd"  placeholder="SD" name="sd[]" id ="sd[]" min = "0">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control vat"  placeholder="VAT" name="vat[]" id ="vat[]">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control qty"  placeholder="Quantity" name="qty[]" id ="qty[]" value = 1 min = "0">
                                                                </div>
                                                            </div> 

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control tlc"  placeholder="Total Landed  Cost" name="tlc[]" id ="tlc[]" readonly>
                                                                </div>
                                                            </div> 

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control tv"  placeholder="Total Value" name="tv[]" id ="tv[]" readonly>
                                                                </div>
                                                            </div>                                                     

                                                          </div>
                                            </div>
<?php } else if ($mode == 2){ 

$rCountLoop = 0; $totlcost = 0; $totsubval = 0;
$qryInfo = "SELECT i.barcode,i.name prnm, p.`com_invoice_val_usd`, p.`com_invoice_val_bdt`, p.`freight_charges`, p.`global_taxes`, p.`cd`, p.`rd`, p.`sd`, p.`vat`, p.`qty`,
            p.`tot_landed_cost`, p.`tot_value` FROM `purchase_landing_item` p LEFT JOIN item i ON i.id=p.productId WHERE p.pu_id = ".$pid;
$resultInfo = $conn->query($qryInfo);
while ($rowInfo = $resultInfo->fetch_assoc()) {
    $barcode                = $rowInfo["barcode"];                  $com_invoice_val_usd = $rowInfo["com_invoice_val_usd"];
    $com_invoice_val_bdt    = $rowInfo["com_invoice_val_bdt"];      $freight_charges     = $rowInfo["freight_charges"];
    $global_taxes           = $rowInfo["global_taxes"];             $cd                  = $rowInfo["cd"];
    $rd                     = $rowInfo["rd"];                       $sd                  = $rowInfo["sd"];
    $vat                    = $rowInfo["vat"];                      $qty                 = $rowInfo["qty"];
    $tot_landed_cost        = $rowInfo["tot_landed_cost"];          $tot_value           = $rowInfo["tot_value"];
    $prnm                   = $rowInfo["prnm"];
    
    $totlcost += $tot_landed_cost;      $totsubval += $tot_value;
?>
                                            <div class="toClone">
          	                                      <div class="row">
                                                            
                                                            <div class="col-lg-1">
													            <label class="hidden-lg">Item Name</label>
                                                                <div class="form-group">
                                                                   <!--input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"-->
                                                                    <div class="form-group styled-select">
                                                                        <input type="text" list="itemName"  autocomplete = "off" name="itmnm[]" value="<?=$prnm?>-[barcode: <?= $barcode ?>]"  class="dl-itemName datalist" placeholder="Select Item" required>
            															<input type="hidden" class = "barcode" name="barcode[]" value="<?= $barcode ?>" class="itemName">
                                                                        <datalist  id="itemName" class="list-itemName form-control"  >
                                                                            <option value="">Select Item</option>
                <?php 
            			$qryitm = "SELECT i.id, i.name,i.barcode
            						FROM item i where forstock in(1,2)
            						order by i.name";								 
            									 
                    $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                        $tid  = $rowitm["id"];
            			$code  = $rowitm["barcode"];
                        $nm   = $rowitm["name"];
                
                        ?>
                                                                            
                        																<option class="option-<?=$tid?>" data-value="<?=$tid?>" data-barcode="<?=$code?>" value="<?=$nm?>-[barcode: <?= $code ?>]"></option>																
                            <?php }} ?>
                                                                                    </datalist>
                                                                                </div>
                                                                            </div>
                                                                        </div> <!-- this block is for itemName-->
												

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control civu"  placeholder="Commercial Invoice Value (USD/EURO)" name="civu[]" id ="civu[]" value = "<?= $com_invoice_val_usd ?>">
                                                                </div>
                                                            </div> 


                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control civb"  placeholder="Commercial Invoice Value (BDT)" name="civb[]" id ="civb[]" value = "<?= $com_invoice_val_bdt ?>" readonly>
                                                                </div>
                                                            </div>                                                     

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control freight"  placeholder="Freight" name="freight[]" id ="freight[]" value = "<?= $freight_charges ?>" min = "0">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control taxes"  placeholder="Global Taxes" name="taxes[]" id ="taxes[]" value = "<?= $global_taxes ?>" min = "0">
                                                                </div>
                                                            </div> 


                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control cd"  placeholder="CD" name="cd[]" id ="cd[]" value = "<?= $cd ?>" min = "0">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control rd"  placeholder="RD" name="rd[]" id ="rd[]" value = "<?= $rd ?>" min = "0">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control sd"  placeholder="SD" name="sd[]" id ="sd[]" value = "<?= $sd ?>" min = "0">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control vat"  placeholder="VAT" name="vat[]" id ="vat[]" value = "<?= $vat ?>" min = "0">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control qty"  placeholder="Quantity" name="qty[]" id ="qty[]" value = "<?= $qty ?>" min = "0">
                                                                </div>
                                                            </div> 

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control tlc"  placeholder="Total Landed  Cost" name="tlc[]" id ="tlc[]" value = "<?= $tot_landed_cost ?>" readonly>
                                                                </div>
                                                            </div> 

                                                            <div class="col-lg-1">
                                                                
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control tv"  placeholder="Total Value" name="tv[]" id ="tv[]" value = "<?= $tot_value ?>" readonly>
                                                                </div>
                                                            </div>                                                     

                                                          </div>
                                            <?php if ($rCountLoop > 0) { ?>
                                           		<div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                            <?php } $rCountLoop++; ?>   
                                            </div>
                                            
<?php }} ?>

                                    		<!-- this block is for php loop, please place below code your loop  -->
                                        </div>



                        


                                    </div>
                                    
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
                                    <?php
                                        //echo $mode;
                                            $addClassName = ($mode == "1") ? 'link-add-po' : 'link-add-po-2';
                                            ?>
        	                            <!--a href="#" class="<?=$addClassName ?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a-->
                                        
                                        
                                        
                                        
                                        
    	                            </div>
                                    
                                    <div class="row add-btn-wrapper">
                                        <div class="col-sm-12">
                                            <a href="#" title="Add Item" class="<?=$addClassName ?>"><span class="glyphicon glyphicon-plus"></span> </a>
                                        </div>	
                                    </div>



                                    
                                    <div class="well no-padding  top-bottom-border grandTotalWrapperx grid-sum-footer">
                                            <div class="row total-row border">
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper label-flex pull-right ipspan">
                                                        <label style="width: 154px;">Subtotal Landed Cost:</label>
                                                        <input type="text" class="form-control f-subtotal" id="grandTotal" value="<?= $totlcost ?>" readonly required><span>৳</span>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right ipspan">
                                                        <label>Subtotal Value:</label>
                                                        <input type="text" class="f-disttl form-control" id_="discountdsp" value="<?= $totsubval ?>"  name="discountdsp"  readonly><span>৳</span>
                                                        
                                                    </div>
                                                </div>                                                
                                                
                                            </div>                                           
                                            
                                        </div>
                                        
                                        
                                    <div class="col-sm-12">

                                            <?php if ($mode == 2) { 
                                                if($sts == 1){
                                            ?>
                                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Order" id="update" >
                                          <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="copy" value="Copy SO" id="Copy"-->
                                          <?php }} else { ?>
                                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Purchase" id="add" >
                                          <?php } ?>
                                        <a href = "./purchase_dataformList.php?pg=1&mod=12">
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
    function validateInput(input) {
  if (input.value < 0) {
    input.value = input.value*(-1);  // Reset the value to 0 if it's negative
  }
}

</script>
<script>
    $(document).on("input", ".freight, .taxes, .cd, .rd, .sd, .vat, .qty", function() {
        
      val = $(this).val();
      var root = $(this).closest('.toClone');
      
      
      totcalculation(root);
      
    });
    
    function totcalculation(root){
          
          var civb = parseFloat(root.find('.civb').val()) || 0;
          var freight = parseFloat(root.find('.freight').val()) || 0;
          var taxes = parseFloat(root.find('.taxes').val()) || 0;
          var cd = parseFloat(root.find('.cd').val()) || 0;
          var rd = parseFloat(root.find('.rd').val()) || 0;
          var sd = parseFloat(root.find('.sd').val()) || 0;
          var vat = parseFloat(root.find('.vat').val()) || 0;
          var qty = parseFloat(root.find('.qty').val()) || 1;
          
          var totunit = (freight + taxes + cd + rd + sd + vat+civb);
          var tlc = totunit / qty;
          
          var a = root.find('.tlc').val(totunit); 
          var b = root.find('.tv').val(tlc);
          
          //Subtotal
          var subtotal = 0;
          var subunittotal = 0
          $(".toClone").each(function(){
              var thisval = $(this).find(".tlc").val();
              if(thisval>0){	subtotal += +thisval; }
              
              var thisval = $(this).find(".tv").val();
              if(thisval>0){	subunittotal += +thisval; }
           });
           
           subtotal = subtotal.toFixed(2);
           subunittotal = subunittotal.toFixed(2);
           
           $(".f-subtotal").val(subtotal);
           $(".f-disttl").val(subunittotal);
        
    }
    
    $(document).on("input", ".civu", function() {
        
      val = $(this).val();
      var root = $(this).closest('.toClone');
      
      var exrate = $('#ex_rate').val();if (exrate == "") exrate = 1;
      var civu = parseFloat(root.find('.civu').val()) || 0;
      
      var civb = exrate * civu;
      var a = root.find('.civb').val(civb); 
      
      totcalculation(root);
      
    });
</script>

<script>
    $(document).on("change", ".dl-itemName", function() {
  
  val = $(this).val();
  
  var root = $(this).closest('.toClone');
  var barcode = $('#itemName option[value="' + val +'"]').attr('data-barcode');
  if (typeof barcode === 'undefined') {
    alert('Product is undefined');
  } else {
    root.find('.barcode').val(barcode);
  }
               
    
});
</script>

<script language="javascript">


<?php
if ($res == 4) {
        ?>

//alert($(".cmb-parent").children("option:selected").val());
/*
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
*/
<?php
}
    ?>

/*
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
*/
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

<script>

//COPIER
	
$(document).ready(function() {
    var max_fields      = 500; //maximum input boxes allowed
    var wrapper         = $(".color-block"); //Fields wrapper
    var add_button      = $(".link-add-order"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper .toClone:last-child").clone().appendTo(wrapper);
    
    	$( ".po-product-wrapper .toClone:last-child input").val("");
  

		if(x==2){
			$( ".po-product-wrapper .toClone:last-child").append('<div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}

        }
        
        
        
        setTimeout(function(){
            
        //check already selected item and disable them.
        //var valuesArray = []; // Array to store the values

          $('.itemName').each(function() {
            var inputValue = $(this).val();
            //valuesArray.push(inputValue);
              
              //  $('.po-product-wrapper .toClone:last-child .option-'+inputValue).prop('disabled', 'disabled');
              //$('.withlebel .toClone:last-child .option-'+inputValue).prop('disabled', true);
              //$('.po-product-wrapper .toClone:last-child .option-'+inputValue).remove();
              $(document).on('click','.po-product-wrapper .toClone:last-child', function(){
                $(this).find(".option-"+inputValue).remove();
              });
          });  
            
            
        },200);
      
        
        
        
    });

    $(wrapper).on("click",".remove-order", function(e){ //user click on remove text
        e.preventDefault();
		$(this).closest(".toClone").remove();
		 OrderTotal();
		x--;
		
    })
});	
	
</script>

</body>
</html>
<?php } ?>