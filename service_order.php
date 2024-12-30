<?php
//print_r($_REQUEST);
//exit();


require "common/conn.php";
include_once('rak_framework/fetch.php');
session_start();
$usr = $_SESSION["user"];
//echo $usr;die;

//ini_set('display_errors', 1);


if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {
    $res       = $_GET['res'];
    $msg       = $_GET['msg'];
    $id        = $_GET['id'];
    $serno     = $_GET['id'];
    $totamount = 0;
   $itdgt=0;
   $discttot=0;

	
    if ($res == 4) { //update mode
		
        
        $qry = "SELECT so.id soid, so.code,DATE_FORMAT(so.`orderdate`,'%e/%c/%Y') `orderdate`,so.totalamount,so.totalvat,so.totaltax, org.name, org.id orgid, soi.id invid,
                so.transport, so.service_charge, so.details
                FROM `service_order` so LEFT JOIN organization org ON org.id=so.customer LEFT JOIN service_invoice soi ON soi.serviceorder=so.code
                WHERE so.id = " . $id;
		
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
				
				
                while ($row = $result->fetch_assoc()) {
                    $soid              = $row["soid"];
                    $invid              = $row["invid"];
                    $orderdt          = $row["orderdate"];
					$totalamount      = $row["totalamount"];
                    $totalvat         = $row["totalvat"];
                    $totaltax          = $row["totaltax"];
                    $orgname          = $row["name"];
                    $org                = $row["orgid"];
                    
                    $transport       = $row["transport"];
                    $service_charge  = $row["service_charge"];
                    $details         = $row["details"];
                }
            }
        }
        $mode = 2; //update mode
		
        
    } else {
		
        $uid              = '';
        $orderdt          = date("d/m/Y");
        $orgname           = '';
        $totalamount   = '0';
        $subamount              = '0';
        $total              = '0';

        $org               = '';
        $mode = 1; //Insert mode
        

    }

	
	
	
    $currSection = 'serviceorder';
    $currPage    = basename($_SERVER['PHP_SELF']);
	
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
	
</style>
<?php
	
	
include_once 'common_top_body.php';
	
	
	
    ?>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Service</span>
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
                       <form method="post" action="common/addservice_order.php"  id="Quotationform"  enctype="multipart/form-data">
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">




			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                           
                            
                            
                           
                            
                            

                                   <div class="row form-header">

	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>Service <i class="fa fa-angle-right"></i> Service Order</h6>
      		                            </div>

      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked <span class="redstar">*</span>
 are required)</span></h6>
      		                            </div>


                                   </div>



                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->

                                <div class="row">
                            	    <div class="col-sm-12">
	                                    <!-- <h4>SO Information</h4>
		                                <hr class="form-hr"> -->
										
		          
    	                            </div>
                                    <div class="row no-mg">



                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <h4>Order Information  </h4>
                                        <hr class="form-hr">
                                    </div>                                    

                                    <input type = "hidden" name = "uid" value = "<?= $soid ?>">
                                    <input type = "hidden" name = "invid" value = "<?= $invid ?>">
                                    <div class="col-lg-3 col-md-6 col-sm-6"> 
                                        <div class="form-group">
                                            <label for="cmbcontype">Customer<span class="redstar">*</span></label>
                                                <div class="ds-divselect-wrapper cat-name">
                                                    <div class="ds-input">
                                                        <input type="hidden" name="dest" value="">
                                                        <input type="hidden" name="org_id" id = "org_id" value = "<?= $org ?>">
                                                        <input type="text" name="org_name" required autocomplete="off"  class="input-box form-control" value = "<?= $orgname ?>">
                                                    </div>
                                                    <div class="list-wrapper">
                                                        <div class="ds-list">
                                                            <ul class="input-ul" id="inpUl">
                                                                <li class="addnew">+ Add new</li>
                                                            <?php $qryitm = "SELECT o.id, concat(o.name, '(', o.contactno, ')') orgname ,concat(o.street,',',a.name,',',d.name,'-',o.zip,',',c.name) addr
FROM organization o left join area a on o.area=a.id left join district d on o.district=d.id left join country c on o.country=c.id 
order by o.name";
                                                            $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                            $tid = $rowitm["id"]; $nm  = $rowitm["orgname"]; $addr = $rowitm["orgname"];?>
                                                                <li class="pp1" data-addr="<?php echo $addr; ?>" value = "<?=$tid ?>"><?=$nm ?></li>
                                                        <?php }} ?>
                                                        </ul>
                                                    </div>
                                                    <div class="ds-add-list">
                                                        <h3>Add new Item</h3>
                                                        <hr>
                                                        <label for="">Name</label> <br>
                                                        <input type="text" name="" autocomplete="off" class="Name addinpBox form-control" id="">
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-lg-6 add-more-col">
                                                                <button type="button" class="more-info">+add more info</button>
                                
                                                            </div>
                                                            <div class="col-lg-6">
                                                                 <button type = "button" class="primary ds-add-list-btn ">Save</button>
                                                            </div>
                                                        </div>
                                
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                    </div>

                            	    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="po_dt">Order Date<span class="redstar">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>



                            	    <br>
                                    <div class="po-product-wrapper withlebel">
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if($mode == 1) { //insert ?>
                                            
											
										<div class="row form-grid-bls  hidden-md hidden-sm hidden-xs">
											
											
                                                <div class="col-lg-2 col-md-5 col-sm-6">
                                                	<h6 class="chalan-header mgl10"> Select Item <span class="redstar">*</span></h6>
                                                </div>

												<div class="col-lg-2 col-sm-2 col-xs-6">
													<h6 class="chalan-header"> Description <span class="redstar">*</span></h6>
												</div>
												<div class="col-lg-2 col-sm-2 col-xs-6">
													<h6 class="chalan-header"> Unit </h6>
												</div>											
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">QTY</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">Rate</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">RDL</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">Vendor</h6>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">Unit Total % </h6>
                                                </div>
                                        </div>
											
											
	                                        <div class="toClone">
          	                                    <div class="col-lg-2 col-md-5 col-sm-6">
													<label class="hidden-lg">Item Name</label>
                                                    <div class="form-group">
                                                       <!--input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"-->
                                                        <div class="form-group styled-select">
                                                            <input type="text" list="itemName"  autocomplete = "off" name="itmnm[]"  class="dl-itemName datalist" placeholder="Select Item" required>
															<input type="hidden" name="itemName[]" value="" class="itemName">
                                                            <datalist  id="itemName" class="list-itemName form-control"  >
                                                                <option value="">Select Item</option>
    <?php 
			//$qryitm = "SELECT `id`, `name`, round(`vat`, 2) vat, round(`ait`, 2) ait, round(`rate`, 2) rate, round(`cost`, 2) cost  FROM `item`  order by name";
				
			$qryitm = "SELECT * FROM `serviceitem` order by name";								 
									 
        $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
            $tid  = $rowitm["id"];
			$code  = $rowitm["code"];
            $nm   = $rowitm["name"];
            $vat  = $rowitm["vat"];
            $tax  = $rowitm["tax"];
            ?>
                                                                
																<option class="option-<?=$tid?>" data-value="<?=$tid?>" data-vat="<?php echo $vat; ?>" data-tax="<?php echo $tax; ?>" value="<?=$nm?>-[Cd: <?=$code; ?>]"><?=$nm?>-[Cd: <?=$code; ?>]</option>																
    <?php }} ?>
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for itemName-->
												
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <input type="hidden" name="itmvat[]" id="itmvat" value ="<?= $vat ?>"  value="" class="itmvat">
                                                        <input type="text" class="form-control" id="description" placeholder="Description"  name="descriptions[]">
                                                      
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="unit" placeholder="Unit"  name="units[]">
                                                      
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-8">
													<div class="form-group">
														<input type="number" class="numonly form-control qty" id="qty" value="" placeholder="QTY" name="qtys[]" required>
														
													</div>
												</div>
												
												<div class="col-lg-1 col-md-2 col-sm-2 col-xs-8">
													<div class="form-group">
														<input type="text" class="form-control rate" id="rate" value="" placeholder="Rate" name="rates[]" required>
														
													</div> 
												</div>												
												<div class="col-lg-1 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <select name="rdls[]" id="rdl" class="form-control" planceholder="Select RDL">
                                                            <option value="0"> No </option>
                                                            <option value="1"> Yes </option>
                                                        </select>
                                                    </div>
                                                </div>
												
                                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <select name="vendors[]" id="vendor" class="form-control" planceholder="Select Vendor">
                                                            <option value="0"> No </option>
                                                            <option value="1"> Yes </option>
                                                        </select>
                                                      
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <input type="number" class="form-control total" id="total" placeholder="Unit Total"  name="totals[]" readonly>
                                                      
                                                    </div>
                                                </div> 

                       
                                            </div>
<?php } else { // edit
		

		?>
											<style>
												@media (min-width: 1199px){
													.withlebel .remove-icon {
/*													  bottom: 23px;*/
												
													}
												}
											</style>
											
 
											
											
											<div class="row form-grid-bls  hidden-md hidden-sm hidden-xs">
											
											
                                                <div class="col-lg-2 col-md-5 col-sm-6">
                                                	<h6 class="chalan-header mgl10"> Select Item <span class="redstar">*</span></h6>
                                                </div>

												<div class="col-lg-2 col-sm-2 col-xs-6">
													<h6 class="chalan-header"> Description <span class="redstar">*</span></h6>
												</div>
												<div class="col-lg-2 col-sm-2 col-xs-6">
													<h6 class="chalan-header"> Unit </h6>
												</div>											
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">QTY</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">Rate</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">RDL</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">Vendor</h6>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">Unit Total % </h6>
                                                </div>
                                        </div>
																			
											
	<?php
		
        $rCountLoop  = 0;
        $itdgt       = 0;
		$netamount=0;
		
        $itmdtqry    = "SELECT sod.amount,sod.totalamount,sod.vat,sod.tax, sod.product, i.code,i.name, sod.description,sod.unit, sod.rdl, sod.vendor,sod.qty, sod.rate 
                        FROM `service_orderdetails` sod LEFT JOIN service_order so ON so.id = sod.serviceid LEFT JOIN serviceitem i ON i.id=sod.product 
                        WHERE so.id = ".$soid. " ORDER BY sod.sosl ASC";
       //echo $itmdtqry;die;
        $resultitmdt = $conn->query($itmdtqry);
		
		if ($resultitmdt->num_rows > 0) {
			
			while ($rowitmdt = $resultitmdt->fetch_assoc()) {
            $itmdtid  = $rowitmdt["product"];
            $itvat    = $rowitmdt["vat"];
            $ittax    = $rowitmdt["tax"];
            $itmname  = $rowitmdt["name"];
			$itcode  = $rowitmdt["code"];
            $itcost  = $rowitmdt["amount"];
            $totalcost  = $rowitmdt["totalamount"];
            
            $description  = $rowitmdt["description"];
            $qty  = $rowitmdt["qty"];
            $unit  = $rowitmdt["unit"];
            $rdl  = $rowitmdt["rdl"];
            $vendor  = $rowitmdt["vendor"];
            $rate    = $rowitmdt["rate"]; 
            $netamount += $totalcost;
            
            ?>
                                            <!-- this block is for php loop, please place below code your loop  -->
                                            
                                            
  											<!-- edit mode -->
                                            
                                            <div class="toClone">
          	                                    <div class="col-lg-2 col-md-5 col-sm-6">
													<label class="hidden-lg">Item Name</label>
                                                    <div class="form-group">
                                                       <!--input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"-->
                                                        <div class="form-group styled-select">
                                                            <input type="text" list="itemName"  autocomplete = "off"  value ="<?=$itmname?>-[Cd: <?=$itcode; ?>]" name="itmnm[]"  class="dl-itemName datalist" placeholder="Select Item" required>
															<input type="hidden" name="itemName[]" value ="<?= $itmdtid ?>" value="" class="itemName">
                                                            <datalist  id="itemName" class="list-itemName form-control"  >
                                                                <option value="">Select Item</option>
    <?php 
			//$qryitm = "SELECT `id`, `name`, round(`vat`, 2) vat, round(`ait`, 2) ait, round(`rate`, 2) rate, round(`cost`, 2) cost  FROM `item`  order by name";
				
			$qryitm = "SELECT * FROM `serviceitem` order by name";								 
									 
        $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
            $tid  = $rowitm["id"];
			$code  = $rowitm["code"];
            $nm   = $rowitm["name"];
            $vat  = $rowitm["vat"];
            $tax  = $rowitm["tax"];
            ?>
                                                                
																<option class="option-<?=$tid?>" data-value="<?=$tid?>" data-vat="<?php echo $vat; ?>" data-tax="<?php echo $tax; ?>" value="<?=$nm?>-[Cd: <?=$code; ?>]"><?=$nm?>-[Cd: <?=$code; ?>]</option>																
    <?php }} ?>
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for itemName-->
												
												
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
												    
			
                                                    <div class="form-group">
                                                        <input type="hidden" name="itmvat[]" id="itmvat" value ="<?= $vat ?>"  value="" class="itmvat">
                                                        <input type="text" class="form-control" id="description" placeholder="Description"  name="descriptions[]" value = "<?= $description ?>">
                                                      
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="unit" placeholder="Unit"  name="units[]" value = "<?= $unit ?>">
                                                      
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-8">
													<div class="form-group">
														<input type="number" class="numonly form-control qty" id="qty"  placeholder="QTY" name="qtys[]" required value = "<?= $qty ?>">
														
													</div>
												</div>
												
												<div class="col-lg-1 col-md-2 col-sm-2 col-xs-8">
													<div class="form-group">
														<input type="number" class="form-control rate" id="rate" placeholder="Rate" name="rates[]" required value = "<?= $rate ?>">
														
													</div>
												</div>												
												<div class="col-lg-1 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <select name="rdls[]" id="rdl" class="form-control" planceholder="Select RDL">
                                                            <option value="0" <?php if($rdl == 0) echo "selected" ?>> No </option>
                                                            <option value="1" <?php if($rdl == 1) echo "selected" ?>> Yes </option>
                                                        </select>
                                                    </div>
                                                </div>
												
                                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <select name="vendors[]" id="vendor" class="form-control" planceholder="Select Vendor">
                                                            <option value="0" <?php if($vendor == 0) echo "selected" ?>> No </option>
                                                            <option value="1" <?php if($vendor == 1) echo "selected" ?>> Yes </option>
                                                        </select>
                                                      
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <input type="number" class="form-control total" id="total" placeholder="Unit Total"  name="totals[]" value = "<?= $totalcost ?>" readonly>
                                                      
                                                    </div>
                                                </div>
                                                
                                                <?php if ($rCountLoop > 0) { ?>
                                           		<div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                            <?php } $rCountLoop++; ?>

                       
                                            </div>
<?php }}} ?>
                                    		<!-- this block is for php loop, please place below code your loop  -->
											
											
										
											
											
                                        </div>


										<div class="row add-btn-wrapper">
											<div class="col-sm-12">
											<?php
												//echo $mode;
													$addClassName = ($mode == "1") ? 'link-add-po' : 'link-add-po-2';
													?>
												<a href="#" title="Add Item" class="link-service-order" ><span class="glyphicon glyphicon-plus"></span> </a>
											</div>	
										</div>
										

                                        <div class="well no-padding  top-bottom-border grandTotalWrapperx grid-sum-footer">
                                            <div class="row total-row border">
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper label-flex pull-right">
                                                        <label>Total </label>
                                                        <input type="text" class="form-control f-total" id="totalamt" name = "totalamt" value="<?php echo str_replace(",","",$netamount); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label> Transport Cost</label>
                                                        <input type="text" class="numonly form-control" id="transport_cost" value="<?php echo  number_format($transport,2, '.', ''); ?>"  name="transport_cost" >
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label> RDL Service Fee</label>
                                                        <input type="text" class="numonly form-control" id="service_fee" value="<?php echo  number_format($service_charge,2, '.', ''); ?>"  name="service_fee" >
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper label-flex pull-right">
                                                        <label>Sub Total </label>
                                                        <input type="text" class="form-control f-subtotal" id="subtotal" name = "subtotal" value="<?php echo str_replace(",","",($netamount + $transport + $service_charge)); ?>" readonly>
                                                    </div>
                                                </div>
											    <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper label-flex pull-right">
                                                        <label>VAT </label>
                                                        <input type="text" class="form-control" id="vat" name = "vat" value="<?php echo str_replace(",","",$totalvat); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper label-flex pull-right">
                                                        <label>Total Amount</label>
                                                        <input type="text" class="form-control" id="totalamount" name = "totalamount" value="<?php echo str_replace(",","",$totalamount); ?>" readonly>
                                                    </div>
                                                </div>
                                                
                                            </div>                                           
                                            
                                        </div>
                                        
                                        <div class="col-lg-12 col-md-12 col-sm-12">
        
                                                <div class="form-group">
        
                                                    <label for="details">Contact & Delivery Address </label>
        
                                                    <textarea class="form-control" id="details" name="details" rows="4" ><?php echo $details; ?></textarea>
        
                                                </div>
        
                                            </div>
                        
                                    </div>
                                    

                                  


                                    <div class="col-sm-12"> 
											
                                            <?php if ($mode == 1) { 
                                             ?>
                                        			
													<input class="btn btn-lg btn-default top" type="submit" name="add" value="Create Order"  id="add" > 
										
                                          <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="copy" value="Copy SO" id="Copy"-->
                                          <?php } else { // new insert ?>

											  	
													<input class="btn btn-lg btn-default" type="submit" name="update" value="Update Order"  id="update"  > 										
										
                                          <?php } ?>

												<input  class="btn btn-lg btn-warning top" type="button" name="postaction" value="Cancel" id="cancel"  onClick="location.href = 'service_orderList.php?pg=1&mod=3'" >

                                    </div>

                                </div>
							<br>
<br>

							

								
                                       

                                

                        </div><!-- end panel body -->
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

<script>
    $(document).on("input", ".qty, .rate", function() {
        
      val = $(this).val();
      var root = $(this).closest('.toClone');
      
      
      totcalculation(root);
      
    });
    
    $(document).on("input", "#transport_cost, #service_fee", function() {
        totalvalue();
        
    });
    
    function totcalculation(root){
          
          var qty = parseFloat(root.find('.qty').val()) || 1;
          var rate = parseFloat(root.find('.rate').val()) || 0;
          
          var totunit = (qty * rate);
          
          var a = root.find('.total').val(totunit); 
         
         // var vat = root.find('.itmvat').val()
         // alert (vat);
          totalvalue();
    };
    
    function totalvalue() {
    // Initialize variables
    var total = 0;
    var transport_cost = 0;
    var service_fee = 0;
    var subtotal = 0;
    var vat = 0;
    var totalamount = 0;

    // Calculate the subtotal from elements with the class "toClone"
    $(".toClone").each(function() {
        var thisval = $(this).find(".total").val();
       var vatval=$(this).find(".itmvat").val();
       //var vat = $('#itemName option[value="' + val +'"]').attr('data-vat'); 
      // alert(vatval);
        if (!isNaN(thisval) && thisval.trim() !== "") {
            total += parseFloat(thisval);
            vat +=parseFloat(thisval)*parseFloat(vatval)/100;
        }
    });

    // Ensure total is a fixed number
    total = parseFloat(total).toFixed(2);

    // Get and parse transport cost
    transport_cost = $("#transport_cost").val();
    if (!isNaN(transport_cost) && transport_cost.trim() !== "") {
        transport_cost = parseFloat(transport_cost).toFixed(2);
    } else {
        transport_cost = 0.00;
    }

    // Get and parse service fee
    service_fee = $("#service_fee").val();
    if (!isNaN(service_fee) && service_fee.trim() !== "") {
        service_fee = parseFloat(service_fee).toFixed(2);
    } else {
        service_fee = 0.00;
    }

    // Calculate subtotal
    subtotal = (parseFloat(total) + parseFloat(transport_cost) + parseFloat(service_fee)).toFixed(2);

    // Calculate VAT (15%)
   // alert(vat);
    //vat = (subtotal * 0.15).toFixed(2);

    // Calculate total amount
    totalamount = (parseFloat(subtotal) + parseFloat(vat)).toFixed(2);

    // Update the input fields with calculated values
    $("#totalamt").val(total);
    $("#subtotal").val(subtotal);
    $("#vat").val(vat);
    $("#totalamount").val(totalamount);
}

$(document).on("input", ".dl-itemName", function() {
  
      val = $(this).val();
      
      var root = $(this).closest('.toClone');
      
      var item = $('#itemName option[value="' + val +'"]').attr('data-value');
      	root.find('.itemName').val(item);
      
      var vat = $('#itemName option[value="' + val +'"]').attr('data-vat'); 
      root.find('.itmvat').val(vat);
      //alert(vat);
        
    });	

    
</script>


<script>
	


	
	
    $(document).ready(function(){
		
		
		
		
		
		//input number only validateion
		//put class .numonly to apply this. alpha will no take, only number and float
		
		$('.numonlyx').change(function(e){
			var xxxx = $(this).val();
			//alert(typeof(parseFloat(xxxx)));
		});
		
		
		
        //$('.numonly').keyup(function(e){
        $(document).on("keyup",".numonly", function(e){

			
		  if(/[^0-9.]/g.test(this.value))
		  {
			// Filter non-digits from input value.
			this.value = this.value.replace(/[^0-9.]/g, '');
			  
		  }
		});	
    });	

		
 
    
    
    
    
    
</script>

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
//$(document).on("change", ".dl-itemName", function() {

	

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
					
					//get current customer address
					getAddress(lival);

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
	

	
	function getAddress(orgid){

                    $.ajax({
                        type: "POST",
                        url: "cmb/get_address.php",
                        data: { orgid : orgid},
                        beforeSend: function(){
                        	$("#billaddress").val("Loading...");
							$("#details").val("Loading...");
                        },
                        
                        }).done(function(data){
                            $("#billaddress").val(data);
                        	 $("#details").val(data);
                            //alert(data);
                        });		
		
	}
	
	
	
            // New input box display


	
	
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
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item " + " (" + $(this).val() + ")");
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
					
					
					
					//addNewOrg();
					
					
					
                });

			});	
	
            $('.input-ul .addnew').click(function(){
               // $(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
				
				var inputVal = $(this).closest(".ds-divselect-wrapper").find(".input-box").val();
				//alert(inputVal);
				addNewOrg(inputVal);
                $(this).closest('.ds-list').attr('style','display:none');
            });	
	
	

	function addNewOrg(inputVal){
		
				BootstrapDialog.show({

											title: 'Add New Organization',
											//message: '<div id="printableArea">'+data+'</div>',
											message: $('<div></div>').load(encodeURI('addselect_modal_org_tab.php?name='+inputVal)),
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
                    										}else if(!ajxdata[5].value){
                    										    msg = "Please Enter Address!"; $("#ind_address").focus();
                    										}else if(!ajxdata[6].value){
                    										    msg = "Please Enter District!"; $("#district").focus();
                    										}else if(!ajxdata[8].value){
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
															  getAddress(res.id);
															  $('.input-box').attr('value',res.name+"("+res.contact+")");
															  $("#inpUl").append("<li class='pp1' value = '"+res.id+"'>"+res.name+" ("+res.contact+")"+"</li>");
															  
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
	//Footer Fields width same as discounted field;
	
function footerfldwdth(){
	ftrfldwdth = $(".c-discounted-ttl").width();
	$(".grid-sum-footer input").width(ftrfldwdth);
}
setTimeout(footerfldwdth,300);

window.addEventListener("resize", () => {
		footerfldwdth();
});	
	
	

var classes = ".grid-sum-footer input, .c-discounted-ttl"

$( "<span>৳</span>" ).insertAfter(classes);
$(classes).parent().addClass("ipspan");

</script>	
	
<script>
$(document).ready(function(){
	
//show INVOICE
	
	$(".revision-tbl").on("click",".show-invoice.btn",function(){
		
  	mylink = $(this).attr('href')+"?qrid="+$(this).data('qrid')+"&socode="+$(this).data('socode')+"&qtype=revision";
	
   //alert(mylink);
  
  
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: 'QUOTATION ID #'+$(this).data('socode'),
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: false, // <-- Default value is false
							cssClass: 'show-invoice',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Cancel',
								action: function(dialog) {
									dialog.close();	
									/*
									$("#printableArea2").printThis({
										importCSS: true, 
										importStyle: true,
									});
									*/
									
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Print',
								action: function(dialog) {
									
									$("#printableArea2").printThis({
										importCSS: false, 
										importStyle: true,
									});
		
									
									dialog.close();	
									
									},
								
							}],
							onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});		
		
	
});
    
    
   
    
</script>



<script>
    
    
    
  $(document).ready(function() {
 // $(document).on("submit", "#Quotationform", function(event) {
    $('#saverevision_no').click(function(e){
    e.preventDefault();
    // Your code here
       var isValid = true;
        
        $(".toClone .qtnqrapper").each(function(){
           alert("im here");
            var grandQty = $(this).find('.c-qty');
            
            $(this).find(".row").each(function(){
                
                 
                 var quantityInput = $(this).find('.quantity');
                 var quantityValue = parseInt(quantityInput.val(), 10);
                 var deliveryDateValue = $(this).find('.delivery-date').val();
                if (quantityValue > 0 && deliveryDateValue.trim() === '') {
                    alert('Invalid quantity or Delivery date. Please fix first');
                    grandQty.trigger("click");
                    return false;
                    isValid = false;
                }
            });
        });
        
        if(isValid){
        $('#Quotationform').submit();
        }
      
  });
});   
    

   
    
    
//show delivery date required if not entered on submit;	

            
            
 $(document).on("submit","#Quotationformx", function(event) {
     
     
     event.stopPropagation();
        //event.preventDefault();
    
        var isValid = true;

       alert(1);
      
    $(this).find('.quantity').each(function() {
        
      var quantityInput = $(this);
          
          var deliveryDateInput = quantityInput.closest('.row').find('.delivery-date');
          var grandQty = quantityInput.closest('.toClone').find('.c-qty');

          var quantityValue = parseInt(quantityInput.val(), 10);
          var deliveryDateValue = deliveryDateInput.val();

          if ((quantityValue > 0 && deliveryDateValue.trim() === '')) {

            grandQty.trigger("click");

            isValid = false;
            return false; // Exit the loop early
          }

        
    });

    if (!isValid) {
      event.preventDefault(); // Prevent form submission
      alert('Invalid quantity or Delivery date. Please fix first');
       
    }

  
  });

//});
</script>


<script>

//COPIER
	
$(document).ready(function() {
    var max_fields      = 500; //maximum input boxes allowed
    var wrapper         = $(".color-block"); //Fields wrapper
    var add_button      = $(".link-service-order"); //Add button ID

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
        
        
        
        
        
        
        
    });

    $(wrapper).on("click",".remove-order", function(e){ //user click on remove text
        e.preventDefault();
		$(this).closest(".toClone").remove();
		var root = $(this).closest('.toClone');
        totalvalue()
		x--;
		
    })
});	
	
</script>

</body>
</html>
<?php } ?>