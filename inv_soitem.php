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
} else {
    $res       = $_GET['res'];
    $msg       = $_GET['msg'];
    $id        = $_GET['id'];
    $serno     = $_GET['id'];
    $totamount = 0;
   $itdgt=0;
   $discttot=0;
    if ($res == 4) {//update
        /* echo "<script type='text/javascript'>alert('".$id."')</script>";*/
        $qry = "SELECT s.`id`,s.orderstatus orderstatus, s.`socode`,s.`customertp`,s.`organization`,s.`srctype`, s.`customer`,DATE_FORMAT(s.`orderdate`,'%e/%c/%Y') `orderdate`,DATE_FORMAT(s.`deliverydt`,'%e/%c/%Y') `deliverydt`,s.`deliveryamt`,
            s.`deliveryby`, s.`accmanager`, s.`vat`, s.`tax`, s.`invoiceamount`, s.`makeby`, s.`makedt`,DATE_FORMAT(s.`terminationDate`,'%e/%c/%Y') `terminationDate` ,s.terminationcause,s.`status`,
            DATE_FORMAT(s.`effectivedate`,'%e/%c/%Y') `effectivedate`,s.`remarks`,s.`poc`,s.`oldsocode`,DATE_FORMAT(s.mrcdt,'%e/%c/%Y') mrcdt, o.name orgname,adjustment
            FROM `soitem` s 
			left join organization o ON o.id = s.organization where  s.id= " . $id;
        //echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $uid              = $row["id"];
                    $soid             = $row["socode"];
                    $cusype           = $row["customertp"];
                    $org              = $row["organization"];
                    $srctype          = $row["srctype"];
                    $cusid            = $row["customer"];
                    $orderdt          = $row["orderdate"];
                    $deliveryby       = $row["deliveryby"];
                    $accmgr           = $row["accmanager"];
                    $invoice_amount   = number_format($row["invoiceamount"],2);
                    $vat              = number_format($row["vat"],2);
                    $tax              = number_format($row["tax"],2);
                    $delivery_dt      = $row["deliverydt"];
                    $term_dt          = $row["terminationDate"];
                    $terminationcause = $row["terminationcause"];
                    $effectivedate    = $row["effectivedate"];
                    $hrid             = '1';
					$orderstatus    = $row["orderstatus"];
                    $st               = $row["status"];
                    $details          = $row["remarks"];
                    $poc              = $row["poc"];
                    $oldsocode        = $row["oldsocode"];
                    $orgname          = $row["orgname"];
                    $oldsocode        = $row["oldsocode"];
                    $mrcdt            = $row["mrcdt"];
                    $deliveryamt      = $row["deliveryamt"];
                    $adj      = $row["adjustment"];
                    $vatt=$row["vat"];
                      
                }
				
				
				//echo "<pre>";print_r($_REQUEST);echo "</pre>";die;
            }
        }
        $mode = 2; //update mode
        /*// echo "<script type='text/javascript'>alert('".$orderdt."')</script>";*/
    } elseif ($res == 5) {
       /* //echo "<script type='text/javascript'>alert('".$id."')</script>";*/
        $qry = "SELECT `orderstatus`,`id`, `socode`,`customertp`,`organization`,`srctype`, `customer`,DATE_FORMAT(`orderdate`,'%e/%c/%Y') `orderdate`,DATE_FORMAT(`deliverydt`,'%e/%c/%Y') `deliverydt`, `deliveryby`,`deliveryamt`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`,DATE_FORMAT(`terminationDate`,'%e/%c/%Y') `terminationDate` ,terminationcause,`status`,DATE_FORMAT(`effectivedate`,'%e/%c/%Y') `effectivedate`,`remarks`,`poc`,`oldsocode` ,DATE_FORMAT(mrcdt,'%e/%c/%Y') mrcdt,adjustment FROM `soitem` s
           where  id= " . $id;
        //echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $uid              = '';
                    $soid             = '';
                    $cusype           = $row["customertp"];
                    $org              = $row["organization"];
                    $srctype          = $row["srctype"];
                    $cusid            = $row["customer"];
                    $orderdt          = $row["orderdate"];
                    $deliveryby       = $row["deliveryby"];
                    $accmgr           = $row["accmanager"];
                    $invoice_amount   = $row["invoiceamount"];
                    $vat              = $row["vat"];
                    $tax              = $row["tax"];
                    $delivery_dt      = $row["deliverydt"];
                    $term_dt          = $row["terminationDate"];
                    $terminationcause = $row["terminationcause"];
                    $effectivedate    = $row["effectivedate"];
                    $hrid             = '1';
					$orderstatus    = $row["orderstatus"];
                    $st               = $row["status"];
                    $details          = $row["remarks"];
                    $poc              = $row["poc"];
                    $oldsocode        = $row["oldsocode"];
                    $mrcdt            = $row["mrcdt"];
                    $deliveryamt      = $row["deliveryamt"];
                    $adj      = $row["adjustment"];
                     $vatt=$row["vat"];
                }
            }
        }
        $mode = 5; //copy mode
        /* echo "<script type='text/javascript'>alert('".$orderdt."')</script>";*/
    } else { // insert
        $uid              = '';
        $soid             = '';
        $cusype           = 2;
        $srctype          = '';
        $cusid            = '';
        $orderdt          = date("d/m/Y");
        $currency         = '';
        $deliveryby       = '';
        $accmgr           = '';
        $itdmu            = 1;
        $invoice_amount   = '0';
        $vat              = '0';
        $tax              = '0';
        $delivery_dt      = '';
        $hrid             = '';
        $term_dt          = '';
        $terminationcause = '';
        $st               = '';
        $effect_dt        = '';
        $details          = '';
        $poc              = '';
        $mrcdt            = ''; //$term_dt=date("Y-m-d")
    
        $deliveryamt      = 0;
        $adj      = 0;
         $vatt=0;
        $mode = 1; //Insert mode
        
        
       // $soid='OI-'.strpaddate("YmdHis");//date_format(date(),"Y/m/d H:i:s");
		include_once('rak_framework/fetch.php');
		
		//$LastID = fetchMaxRecord('soitem','id');
		//$newoid = str_pad($LastID, 6, "0", STR_PAD_LEFT);
		//soitem
		
		//$soid='OI-'.$newoid;
		

    }

    $currSection = 'inv_soitem';
    $currPage    = basename($_SERVER['PHP_SELF']);
	
	
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
                       <form method="post" action="common/addinv_soitem.php" id="form1" enctype="multipart/form-data">
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">




			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>

                                   <div class="row form-header">

	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>Sales <i class="fa fa-angle-right"></i><?=($mode == 1 || $mode == 5)?"New":"Edit "?>  Order</h6>
      		                            </div>

      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>


                                   </div>



                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->

                                <div class="row">
                            	    <div class="col-sm-12">
	                                    <!-- <h4>SO Information</h4>
		                                <hr class="form-hr"> -->

		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno; ?>">
		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
		                                 <input type="hidden"  name="po_id" id="po_id" value="<?php echo $soid; ?>">
    	                            </div>
                                    <div class="row no-mg">


                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="po_id">Order ID*</label>
                                                <input type="text" class="form-control" placeholder="Auto Generated" name="po_id_vis" id="po_id_vis" value="<?php echo $soid; ?>" disabled>
                                            </div>
                                        </div>
                                    </div>



                                        
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
                                                            <?php 
		
																$qryitm = "SELECT o.id, concat(o.name, ' (', o.contactno, ')') orgname ,concat(o.street,',',a.name,',',d.name,'-',o.zip,',',c.name) addr
																FROM organization o left join area a on o.area=a.id left join district d on o.district=d.id left join country c on o.country=c.id 
																order by o.name";
	
                                                            	$resultitm = $conn->query($qryitm);
																if ($resultitm->num_rows > 0){
																while ($rowitm = $resultitm->fetch_assoc()){
																	
                                                            	$tid = $rowitm["id"]; 
																$nm  = $rowitm["orgname"]; 
																$addr = $rowitm["addr"];
															?>
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
                                        <div class="form-group">
                                            <label for="cmbsupnm">Contact Name<span class="redstar">*</span></label>
                                            <div class="form-group styled-select">
                                            <select name="cmbsupnm" id="cmbsupnm" class="cmd-child form-control" required>
                                            

                                            </select>
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
<?php if ($mode == 1 || $mode == 5) { //insert ?>
                                            
											
										<div class="row form-grid-bls  hidden-md hidden-sm hidden-xs">
											
											
                                                <div class="col-lg-5 col-md-6 col-sm-6">
                                                	<h6 class="chalan-header mgl10"> Select Item<span class="redstar">*</span></h6>
                                                </div>
												<div class="col-lg-1 col-sm-6 col-xs-6">
													<h6 class="chalan-header"> Price<span class="redstar">*</span></h6>
												</div>
												<div class="col-lg-1 col-sm-6 col-xs-6">
													<h6 class="chalan-header"> Quantity<span class="redstar">*</span></h6>
												</div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Unit Total </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Discount % </h6>
                                                </div>											
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">VAT %</h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Discounted Total </h6>
                                                </div>
                                        </div>
											
											
	                                        <div class="toClone">
          	                                    <div class="col-lg-5 col-md-3 col-sm-3 col-xs-12">
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
				
			$qryitm = "SELECT i.id, i.name,i.code, round(i.vat, 2) vat, round(i.ait, 2) ait, round(i.rate, 2) rate, round(i.cost, 2) cost , s.freeqty
						FROM item i
						INNER JOIN stock s ON i.id = s.product
						order by i.name";								 
									 
        $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
            $tid  = $rowitm["id"];
			$code  = $rowitm["code"];
            $nm   = $rowitm["name"];
            $cost =$rowitm["rate"];
            $up = $rowitm["rate"];
            $vat  = $rowitm["vat"];
            $ait  = $rowitm["ait"];
            $prdcost=$rowitm["cost"];
			$stock = $rowitm["freeqty"];
            ?>
                                                                
																<option data-value="<?php echo $tid; ?>" data-stock="<?=$stock?>" data-prdcost="<?php echo $prdcost; ?>" data-up="<?php echo $up; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" value="<?=$nm?>-[Cd: <?=$code; ?> | St: <?=$stock?>]"><?=$nm?>-[Cd: <?=$code; ?> | St: <?=$stock?>]</option>																
    <?php }} ?>
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for itemName-->
												
												
												<div class="col-lg-1 col-md-2 col-sm-2 col-xs-8">
												<label class="hidden-lg">Price*</label>
													<div class="form-group">
														<input type="text" class="numonly form-control unitprice_otc1_ unitPriceV2_ calc c-price" id="unitprice_otc1_" value="0.00" placeholder="Price" name="unitprice_otc[]">
														<input type="hidden" class="form-control unitprice_otc_ unitPriceV2_" id="unitprice_otc_" name="unitprice_otc1[]">

													</div>
												</div>												
												<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
													<label class="hidden-lg">Qty</label>
													<div class="form-group">
														<input  type="number" min="1" autocomplete="off" class="numonly calc c-qty form-control quantity_otc_ qty-chkstk" id="quantity_otc_" placeholder="Qty" name="quantity_otc[]">
													</div>
												</div>
												
                                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-5">
												<label class="hidden-lg">Unit Total</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control  TotalAmount_ c-price-utt" id="total_" placeholder="Unit Total" readonly  name="total[]">
                                                      
                                                    </div>
                                                </div> 

                                                <!-- this block is for discount-->
                                                 <div class="col-lg-1 col-md-1 col-sm-1  col-xs-2">
                                                   
                                                     <label class="hidden-lg">Dis%</label>   
													<div class="form-group">
														<input type="number" min="0" max="100" autocomplete="off"  class="numonly calc c-discount form-control discnt_" id="discnt_"   placeholder="Discount %" name="discnt[]" >
													</div>
                                                </div>												
												
                                                <!-- this block is for vat-->
                                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
												<label class="hidden-lg">VAT</label>
                                                            <div class="form-group">
                                                                <input type="text"  class="numonly form-control vat_ calc c-vat" id="vat_" placeholder="VAT%" name="vat[]" >
                                                            </div>

                                                </div>






                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
													<label class="hidden-lg">Disc. Total</label>
                                                    <div class="form-group">
                                                        <input type="text" st yle="width: 200px;"  class="form-control unitTotalAmount1_ c-discounted-ttl" id="unittotal1_ " placeholder="Discounted Total " readonly  name="unittotal1[]">
														
                                                        <input type="hidden"  class="form-control unitTotalAmount_" name="unittotal[]" id="unittotal">
                                                        <input type="hidden" class="form-control prodprice1_" id="prodprice" name="prodprice[]" >
														
                                                        <input type="hidden" class="form-control rowid" id="rowid"  value="0" name="rowid[]" >
														
														
														<input type="hidden" class="c-h-discount-amt" style="width:100px;">
														<input type="hidden" class="c-h-vat-amt" style="width:100px;">
														
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
											
 
											
											
											<div class="row form-grid-bls hidden-md hidden-sm hidden-xs">
											
											
                                                <div class="col-lg-5 col-md-6 col-sm-6">
                                                	<h6 class="chalan-header mgl10">Select Item<span class="redstar">*</span></h6>
                                                </div>
												<div class="col-lg-1 col-sm-6 col-xs-6">
													<h6 class="chalan-header"> Price<span class="redstar">*</span></h6>
												</div>
												<div class="col-lg-1 col-sm-6 col-xs-6">
													<h6 class="chalan-header"> Quantity<span class="redstar">*</span></h6>
												</div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Unit Total </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Discount % </h6>
                                                </div>												
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">VAT %</h6>
                                                </div>

                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Discounted Total </h6>
                                                </div>
                                        </div>											
											
	<?php
		$bookDisableFlag = 0;
        $rCountLoop  = 0;
        $itdgt       = 0;
        $totalcost=0;$netamount=0;
        $itmdtqry    = "SELECT a.`id`, a.`socode`, a.`sosl`, a.`productid`, a.`mu`, round(a.`qty`,0) qty,round(a.`qtymrc`,0)qtymrc, round(a.`otc`,2) otc, round(a.`mrc`,2)mrc,
                        a.`remarks`, a.`makeby`, a.`makedt`,a.`currency`,a.vatrate vat,a.aitrate ait, b.name itmname,b.code,s.freeqty,a.discountrate,a.discounttot 
						FROM `soitemdetails` a 
						LEFT JOIN item b ON a.`productid` = b.id
						LEFT JOIN stock s ON a.productid = s.product
						WHERE `socode`='" . $soid . "' ORDER BY a.sosl ASC";
       // echo $itmdtqry;die;
        $resultitmdt = $conn->query($itmdtqry);
		
		if ($resultitmdt->num_rows > 0) {
			
			while ($rowitmdt = $resultitmdt->fetch_assoc()) {
            $rowid  = $rowitmdt["id"];
            $itmdtid  = $rowitmdt["productid"];
            $itdmu    = $rowitmdt["mu"];
            $itdqu    = $rowitmdt["qty"];
            $itdqumrc = $rowitmdt["qtymrc"];
            $itdotc   = $rowitmdt["otc"]; //price
            $itdmrc   = $rowitmdt["mrc"];
            $itdrem   = $rowitmdt["remarks"];
            $currency = $rowitmdt["currency"];
            $itvat    = $rowitmdt["vat"];
            $itait    = $rowitmdt["ait"];
            $itmname  = $rowitmdt["itmname"];
			$code  = $rowitmdt["code"];
			$freeqty  = $rowitmdt["freeqty"];
			
				//enable book disable if $freeqty has - minus or 0 qty value
				if($bookDisableFlag == 0 && $freeqty <1){
					$isDisabled = "disabled";
					$bookDisableFlag = 1;
				}
				
            $discountrate  = $rowitmdt["discountrate"];
            $discounttot  = $rowitmdt["discounttot"];
             $cost  = $rowitmdt["cost"];
            $itdtot   = ($itdqu * $itdotc) + ($itdqumrc * $itdmrc);  //unit total
            $itdup   = ($itdqu * $itdotc) + ($itdqumrc * $itdmrc);
            $itdgt    = $itdgt + $discounttot;
            $discttot=$itdgt-$adj+ $vatt;
            $totalcost=$totalcost+($itdqu*$itdotc);
            $netamount=$itdgt;
				
				//new code, rak, vat amount;
				$orVATRate = $itvat;
				$orPrice = $itdotc;
				$orQty = $itdqu;
				$orDicntRate = $discountrate;
				
				$OrUnitTotal = $orPrice*$orQty;
				$OrDiscountAmout = ($OrUnitTotal*$orDicntRate)/100;
				$OrAmountWithDiscount = $OrUnitTotal - $OrDiscountAmout;
				$OrVATAmout = ($OrAmountWithDiscount*$orVATRate)/100;
				$OrSubtotal =  $OrSubtotal+ $OrAmountWithDiscount;
				
				
				
				
				
            ?>
                                            <!-- this block is for php loop, please place below code your loop  -->
                                            
                                            
  											<!-- edit mode -->
                                            
                                            <div class="toClone">
                                                <div class="col-lg-5 col-md-3 col-sm-3 col-xs-12"> 
													<label class="hidden-lg">Item Name</label>
                                                    <div class="form-group">
                                                <div class="form-group styled-select">
                                                            <input list="itemName" name="itmnm[]"  autocomplete="off" value = "<?=$itmname?>-[Cd: <?=$code; ?> | St: <?=$freeqty?>]" class="dl-itemName datalist" placeholder="Select Item" required>
													<input type="hidden" placeholder="ITEM" value="<?php echo $itmdtid; ?>" name="itemName[]" class="itemName">
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php 
				//$qryitm="SELECT `id`, `name`, round(`vat`, 2) vat, round(`ait`, 2) ait, round(`rate`, 2) rate  FROM `item`  order by name"; 
				
					$qryitm = 	"SELECT i.id, i.name, i.code,round(i.vat, 2) vat, round(i.ait, 2) ait, round(i.rate, 2) rate, round(i.cost, 2) cost , s.freeqty
						FROM item i
						INNER JOIN stock s ON i.id = s.product
						order by i.name";
				
			$resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  
                $tid  = $rowitm["id"];
				$code  = $rowitm["code"];
                $nm   = $rowitm["name"];
                $cost = $rowitm["rate"];
                $up = $rowitm["rate"];
                $vat  = $rowitm["vat"];
                $ait  = $rowitm["ait"];
                $prdcost=$rowitm["cost"];
				$stock=$rowitm["freeqty"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" data-stock="<?=$stock?>" data-prdcost="<?php echo $prdcost; ?>" data-up="<?php echo $up; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" value="<?=$nm?>-[Cd: <?=$code; ?> | St: <?=$stock?>]"><?=$nm?>-[Cd: <?=$code; ?> | St: <?=$stock?>]</option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>

                                                    </div>
                                                </div> <!-- this block is for itemName-->
                                                
												<div class="col-lg-1 col-md-2 col-sm-2 col-xs-8">
													<label class="hidden-lg">Price</label>
													<div class="form-group">
														<input  type="text" class="calc c-price form-control unitprice_otc1_ unitPriceV2_" placeholder="Price" id_="unitprice_otc1" value="<?=$itdotc?>" name="unitprice_otc1[]">
														<input type="hidden"  class="form-control unitprice_otc" name="unitprice_otc[]" id="unitprice_otc" value="<?php echo $itdotc; ?>">
													</div>
												</div>												
												<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
													<label class="hidden-lg">Qty</label>
													<div class="form-group">
														<input type="number" autocomplete="off" min="1" class="calc c-qty numonly form-control quantity_otc_ qty-chkstk" id_="quantity_otc" value="<?php echo $itdqu; ?>" name="quantity_otc[]">
													</div>
												</div>
                                        		<div class="col-lg-1 col-md-2 col-sm-2 col-xs-5">
												<label class="hidden-lg">Unit Total</label>
                                                    <div class="form-group">
                                                        <input type="text" class="c-price-utt form-control TotalAmount_" id_="total" placeholder="Unit Total" value="<?php echo $itdtot; ?>" readonly  name="total[]">
                                                      
                                                    </div>
                                                </div>
                                                <!-- this block is for discount-->
                                                 <div class="col-lg-1 col-md-1 col-sm-1  col-xs-2">
													 <label class="hidden-lg">Dis%</label>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="number"  min="0.00" autocomplete="off" max="100" class="calc c-discount form-control discnt_" id_="discnt"  placeholder="Discount%" value="<?php echo $discountrate; ?>" name="discnt[]" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>												
												
                                                 <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3"><!-- this block is for vat-->
													 <label class="hidden-lg">VAT</label>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="text" class="calc c-vat form-control vat_" id_="vat"  value="<?php echo $itvat; ?>" name="vat[]" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- this block is for vat-->

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
												<label class="hidden-lg">Discounted Total</label>
                                                    <div class="form-group">
                                                        <input type="text" class="c-discounted-ttl form-control unitTotalAmount1_" id_="unittotal1" placeholder="Unit Total" value="<?php echo $discounttot; ?>"  readonly  name="unittotal1[]">
                                                        <input type="hidden"  class="form-control unitTotalAmount" name="unittotal[]" id="unittotal"  value="<?php echo $discounttot; ?>">
                                                        <input type="hidden" class="form-control prodprice1" id="prodprice"  value="<?php echo $cost; ?>" name="prodprice[]" >
                                                        <input type="hidden" class="form-control rowid" id="rowid"  value="<?php echo $rowid; ?>" name="rowid[]" >
														
														<input type="hidden" class="c-h-discount-amt" value="<?=$OrDiscountAmout?>" style="width:100px;">
														<input type="hidden" class="c-h-vat-amt" value="<?=$OrVATAmout?>" style="width:100px;">
														<?php
															$OrDiscountAmout = 0;
															$$OrVATAmout = 0;
				
														?>
                                                    </div>
                                                </div> 
                                                


                            				<?php 
												$loopstart = ($orderstatus == 1)?0:-1;
												if($rCountLoop > $loopstart){ 
												if($orderstatus == 3 || $orderstatus == 11){
													?>
												<div class="remove-icon"><a href="#" class="cancel-po" title="Cancel Item"><span class="fa fa-mail-reply"></span></a></div>
												<?php
												}else{
											?>
												
                                           		<div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                            <?php } } $rCountLoop++; ?>

                                            </div>
<?php }} else {
            ?>
                                            
                                            
                                            
                                                

                                          
                                            
                                            <!--  -->
                                            
											<div class="toClone">
          	                                    <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
													<label class="hidden-lg">Item Name</label>
                                                    <div class="form-group">
                                                       <!--input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"-->
                                                        <div class="form-group styled-select">
                                                            <input type="text" list="itemName"  autocomplete="off" name="itmnm[]"  class="dl-itemName datalist" placeholder="Select Item" required>
															<input type="hidden" name="itemName[]" value="" class="itemName">
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php 
			//$qryitm = "SELECT `id`, `name`, round(`vat`, 2) vat, round(`ait`, 2) ait, round(`rate`, 2) rate, round(`cost`, 2) cost  FROM `item`  order by name";

			$qryitm = 	"SELECT i.id, i.name, round(i.vat, 2) vat, round(i.ait, 2) ait, round(i.rate, 2) rate, round(i.cost, 2) cost , s.freeqty
						FROM item i
						INNER JOIN stock s ON i.id = s.product
						order by i.name";


			
			
        $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
            $tid  = $rowitm["id"];
            $nm   = $rowitm["name"];
            $cost =$rowitm["rate"];
            $up = $rowitm["rate"];
            $vat  = $rowitm["vat"];
            $ait  = $rowitm["ait"];
            $prdcost=$rowitm["cost"];
			$stock=$rowitm["freeqty"];
            ?>
                                                                <option data-value="<?php echo $tid; ?>" data-prdcost="<?php echo $prdcost; ?>" data-up="<?php echo $up; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" value="<?php echo $nm; ?>"><?=$nm?> (St: <?=$stock?>)</option>
    <?php }} ?>
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for itemName-->
                                                <!-- this block is for vat-->
                                                 <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
												<label class="hidden-lg">VAT</label>
                                                            <div class="form-group">
                                                                <input type="text"  class="numonly form-control vat" id="vat" placeholder="VAT%" name="vat[]" >
                                                            </div>

                                                </div>


          	                                    <div class="col-lg-2 col-md-3 col-sm-3  col-xs-9">
												
                                                    <div class="row qtnrows">
                                                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-4">
															<label class="hidden-lg">Qty</label>
                                                            <div class="form-group">
                                                                <input type="number" min="1" class="form-control quantity_otc qty-chkstk" id="quantity_otc" placeholder="Qty" name="quantity_otc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-9 col-md-8 col-sm-7 col-xs-8">
														<label class="hidden-lg">Price</label>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc1 unitPriceV2" id="unitprice_otc1" placeholder="Price" name="unitprice_otc1[]">
                                                                <input type="hidden" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" name="unitprice_otc[]" class="unitprice_otc">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc-->


                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
												<label class="hidden-lg">Unit Total</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control TotalAmount" id="total" placeholder="Unit Total" readonly  name="total[]">
                                                      
                                                    </div>
                                                </div> 
                                                <!-- this block is for discount-->
                                                 <div class="col-lg-1 col-md-1 col-sm-1  col-xs-2">
                                                   
                                                     <label class="hidden-lg">Dis%</label>   
													<div class="form-group">
														<input type="number" min="0.00" max="100"   class="numonly form-control discnt" id="discnt"   placeholder="Discount %" name="discnt[]" >
													</div>
                                                       
                                                    
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
													<label class="hidden-lg">Total</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount1" id="unittotal1" placeholder="Discounted Total " readonly  name="unittotal1[]">
                                                        <input type="hidden"  class="form-control unitTotalAmount" name="unittotal[]" id="unittotal">
                                                        <input type="hidden" class="form-control prodprice1" id="prodprice" name="prodprice[]" >
                                                        <input type="hidden" class="form-control rowid" id="rowid"  value="0" name="rowid[]" >
														

														
														
                                                    </div>
                                                </div> 

                                            </div>
											
											
											
<?php }} ?>
                                    		<!-- this block is for php loop, please place below code your loop  -->
											
											
											
											
											
											
                                        </div>


										<div class="row add-btn-wrapper">
											<div class="col-sm-12">
											<?php
												//echo $mode;
													$addClassName = ($mode == "1") ? 'link-add-po' : 'link-add-po-2';
													?>
												<a href="#" title="Add Item" class="link-add-order" ><span class="glyphicon glyphicon-plus"></span> </a>
											</div>	
										</div>
										

                                        <div class="well no-padding  top-bottom-border grandTotalWrapperx grid-sum-footer">
                                            <div class="row total-row border">
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper label-flex pull-right">
                                                        <label>Subtotal </label><?php $itdgt=number_format($itdgt,2)?>
                                                        <input type="text" class="form-control f-subtotal" id="grandTotal" value="<?php echo str_replace(",","",$itdgt); ?>" readonly required>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label>Total VAT :</label>
                                                        <input type="text" class="f-vatttl form-control" id_="vatdis" value="<?php echo  number_format($vatt,2, '.', ''); ?>"  name="vatdis"  readonly>
                                                        <input type="hidden" class="form-control" id_="vatt" value="<?php echo  $vatt; ?>"  name="vatt"  readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label>Total Discount:</label><?php  $netdisc=$totalcost-$netamount;?>
                                                        <input type="text" class="f-disttl form-control" id_="discountdsp" value="<?php echo  number_format($netdisc,2, '.', ''); ?>"  name="discountdsp"  readonly>
                                                        
                                                    </div>
                                                </div>												
												
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label> Adjustment</label>
                                                        <input type="text" class="calc  numonly f-adjmt form-control" id_="discntnt" value="<?php echo  number_format($adj,2, '.', ''); ?>"  name="discntnt" >
                                                    </div>
                                                </div>
												
												
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label> Delivery Charge</label>
                                                        <input type="text" class="calc  numonly f-delcrg form-control" id_="deliveryamt" value="<?php echo  number_format($deliveryamt,2, '.', ''); ?>"  name="deliveryamt" >
                                                    </div>
                                                </div>												
												
                                              <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label>Total </label>
														<?php
															$orGrandTotal =(($OrSubtotal+$vatt)-$adj)+$deliveryamt;
														?>
                                                        <input type="text" class="f-grnd-ttl form-control" id_="grandTotalnet" value="<?php echo number_format($orGrandTotal,2,'.',''); ?>" readonly >
                                                    </div>
                                                </div>
                                                
                                            </div>                                           
                                            
                                        </div>
   
                        
                                    </div>
                                    

                                   

                                    <div class="col-lg-6 col-md-12 col-sm-12">

                                        <div class="form-group">

                                            <label for="details">Billing Address </label>
                                            <?php
                                            //get address;
                                            
                                            if($org){
                                                $street = fetchByID('organization',id,$org,'street');
                                                $area = fetchByID('organization',id,$org,'area');
                                                $district = fetchByID('organization',id,$org,'district');
                                                $zip = fetchByID('organization',id,$org,'zip');
                                                $country = fetchByID('organization',id,$org,'country');
                                                
                                                
                                                $area = ($area)?"\n".fetchByID('area',id,$area,'name').",":"";
                                                $district = ($district)?"\n".fetchByID('district',id,$district,'name')."-":"";
                                                $country =  ($country)?"\n".fetchByID('country',id,$country,'name'):"";

                                                $billaddress =  $street;
                                                $billaddress .=$area;
                                                $billaddress .=$district;
                                                $billaddress .=$zip;
                                                $billaddress .=$country;
                                            }
                                            ?>

                                            <textarea readonly class="form-control" id="billaddress" name="details" rows="4" required><?=$billaddress; ?></textarea>

                                        </div>

                                    </div>
									
                                    <div class="col-lg-6 col-md-12 col-sm-12">

                                        <div class="form-group">

                                            <label for="details">Delivery Address<span class="redstar">*</span> </label>

                                            <textarea class="form-control" id="details" name="delivery_address" rows="4" required><?=$billaddress; ?></textarea>

                                        </div>

                                    </div>
									
									


                                    <div class="col-sm-12"> 
											<input type="hidden" name="mode" value="<?=$mode?>">
										<input type="hidden" name="orsttype" value="<?=$orderstatus?>">
										
										
                                            <?php if ($mode == 2) { //for update ?>
                                        		
													<?php
													$isDisabled = ($orderstatus == 11)?"disabled":"";
													?>
													<input  class="btn btn-lg btn-default top" type="submit" name="postaction" value="Update" id="update"> 
													<input <?=$isDisabled?>  class="btn btn-lg btn-default top" type="submit" name="postaction" value="Book" id="book"> 
													<input <?=$isDisabled?> class="btn btn-lg btn-default" type="submit" name="postaction" value="Confirm"  id="confirm" > 
										
                                          <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="copy" value="Copy SO" id="Copy"-->
                                          <?php } else { // new insert ?>

											  <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Order" id="add" -->
											  <!--input  dat a-to="pagetop" class="btn btn-lg btn-default" type="submit" name="addprint" value="+Add and Print Order" id="add"-->
										
										
													<input  class="btn btn-lg btn-default top" type="submit" name="postaction" value="Save as Draft" id="update"> 
													<input  class="btn btn-lg btn-default top" type="submit" name="postaction" value="Book" id="book"> 
													<input class="btn btn-lg btn-default" type="submit" name="postaction" value="Confirm"  id="confirm" > 										
										
                                          <?php } ?>

												<input  class="btn btn-lg btn-warning top" type="button" name="postaction" value="Back<--" id="cancel"  onClick="location.href = 'inv_soitemList.php?pg=1&mod=3'" >

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
	
//	
//		isNumberKey(e){
//		if (e.charCode === 0 || /\d/.test(String.fromCharCode(e.charCode))) {
//			return true
//		} else {
//			e.preventDefault();
//		}
//	}
	
	
    $(document).ready(function(){
		
		
		
		
		
		//input number only validateion
		//put class .numonly to apply this. alpha will no take, only number and float
		
		$('.numonlyx').change(function(e){
			var xxxx = $(this).val();
			//alert(typeof(parseFloat(xxxx)));
		});
		
		
		$('.numonly').keyup(function(e){

			
		  if(/[^0-9.]/g.test(this.value))
		  {
			// Filter non-digits from input value.
			this.value = this.value.replace(/[^0-9.]/g, '');
			  
		  }
		});		
		
		
$(document).on("input", ".dl-itemName", function() {
  
  val = $(this).val();
  
  var root = $(this).closest('.toClone');
  var vat = $('#itemName option[value="' + val +'"]').attr('data-vat');
  var price = $('#itemName option[value="' + val +'"]').attr('data-up');
  
  	root.find('.c-vat').val(vat);
  	root.find('.c-qty').val(1);
  	root.find('.c-price').val(price);
  	root.find(".c-price").change();
  
});		
		

        //$(document).on("change", ".dl-itemName", function() {
		$(document).on("input", ".dl-itemNamex", function() {

            var val = $(this).val();
			//alert(val);
			
            var cost = $('#itemName option[value="' + val +'"]').attr('data-cost');
            var untprc = $('#itemName option[value="' + val +'"]').attr('data-up');
            var prdprc = $('#itemName option[value="' + val +'"]').attr('data-prdcost');
			
            //var untprc=cost.toFixed(2);
            $(this).closest('.toClone').find('.unitprice_otc').val(cost);
            $(this).closest('.toClone').find('.unitprice_otc1').val(untprc);
			$(this).closest('.toClone').find('.quantity_otc').val(1);
           
            $(this).closest('.toClone').find('.prodprice1').val(prdprc);
		
			var vat = $('#itemName option[value="' + val +'"]').attr('data-vat');
            $(this).closest('.toClone').find('.vat').val(vat);

            var ait = $('#itemName option[value="' + val +'"]').attr('data-ait');
            $(this).closest('.toClone').find('.ait').val(ait);
            
            var disc=0;
            disc = $(this).closest('.toClone').find('.discnt').val();
            var dscntdtotl=0;
            dscntdtotl+=+untprc*(1-disc*0.01);
            //alert(+dscntdtotl);
            $(this).closest('.toClone').find('.TotalAmount').val(untprc);
             $(this).closest('.toClone').find('.unitTotalAmount1').val(dscntdtotl);
            $(this).closest('.toClone').find('.unitTotalAmount').val(dscntdtotl);


	//alert(prdprc);
    var sum = 0; 
    var vatsum=0;
    var vatrate=0;
    var qty=0;
    var rate=0;
    var unitsum=0;
    $(".unitTotalAmount").each(function(){
		sum += +$(this).val(); 
	   sum1=sum.toFixed(2);
	   //alert(sum1);
         $("#grandTotal").val(sum.toLocaleString("en-US"));
         
         vatrate= $(this).closest('.toClone').find('.vat').val();
         vatsum += $(this).val()*vatrate*0.01;
         
         rate= $(this).closest('.toClone').find('.unitprice_otc').val();
         qty= $(this).closest('.toClone').find('.quantity_otc').val();
         
         unitsum+= (rate*qty);//-sum;
         
  }); 
  
  // $(".vat").each(function(){
	//	vatsum += +$(this).val(); 
	  // sum1=sum.toFixed(2);
	   //alert(sum1);
         
  //});

    var adj=0;
    var net=0;
    var dlv=0;
     var vattot=0;
     var discountsum=0;
    dlv+=$("#deliveryamt").val();
    vattot+=$("#vatt").val();
    
    adj+=$("#discntnt").val();
    net+=sum-adj-(-dlv)-(-vatsum);
    discountsum+=unitsum-sum;
    // net+=net+dlv;
    $("#grandTotalnet").val(net.toLocaleString("en-US"));
   $("#vatt").val(vatsum);
   //$("#vatdis").val(vatsum.toLocaleString("en-US"));
   //$("#discountdsp").val(discountsum.toLocaleString("en-US"));
//alert(dlv);

    });
    
    

})
</script>
<script>
    
    $(document).on("change", "#discntnt_,#deliveryamt_", function() {
    //alert("yes");
    var adj=0;
    var net=0;
    var sum1=0;
    var dlv=0;
    var vats=0;
     var discountsum=0;
    
    dlv+=$("#deliveryamt").val();
     adj = $("#discntnt").val();
     vats=$("#vatt").val();
     sum1=$("#grandTotal").val();
     net+=sum1-adj-(-dlv)-(-vats);
     $("#grandTotalnet").val(net.toLocaleString("en-US"));
   // alert(net);
});
    
</script >
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

	

	
	
$(document).on('input','.dl-itemName', function(e) {	

	
	//start for datalist single click event
    var options = $('datalist')[0].options;
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()){
	//end for datalist single click event
		  
		
	

	//alert($(this).val());
	//var root = $(this).parent().parent().parent().parent();
	var root = $(this).closest(".toClone");
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
				var stk = $('#itemName option[value="' + g +'"]').attr('data-stock');
			    //alert(g);
				root.find(".itemName").val(id);
				root.find(".itemName").attr('data-stk',stk);			
			
			

			//check each backorder to release book button if not found;
			
			if(stk <1){
				backorderCheck(stk,root);
				
			}

			//alert(found);
			
			break;
		}else{
			flag = 0;
		}

	
		
		
		
	}
	
			setTimeout(function(){
				enableDisableBookBtn();
			},100);	
	
	
	if(flag == 0){
		$(this).val("");
		}

		
		//start for datalist single click event
 		break;
	   }
    }		   
	//end for datalist single click event
		
		
	});
/* end Check wrong category */

	<?php
	if($orderstatus == 3 || $orderstatus == 11){// confirmed or backorder;
	?>
	$(document).on("click",".cancel-po",function(e){
		
		 e.preventDefault();
		
		
		
			  swal({
			  title: "Do you want to cancel this item from this order?",
			  text: "Your invoice will be regenerated",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			  buttons: ['Cancel', 'Proceed'],
			})
			.then((willDelete) => {
			  if (willDelete) {

			var disc = 0;
			disc = $(this).closest('.toClone').find('.rowid').val();
			//alert(disc);			  
				
				$.ajax({
					type: "POST",
					url: "cmb/so_item_cancel.php",
					data: { soitmdetlsid : disc, ost:<?=$orderstatus?> },
					beforeSend: function(){
							//$("#cmbsupnm").html("<option>Loading...</option>");
						},

				}).done(function(data){

					  swal("Cancel Status", data, "success");
					  $(this).closest('.toClone').remove(); 
					//alert(data);
				});				  
				  
				  
			  } else {
				  return false;
			  }
			});		
		

	});
	
	<?php
	}
	?>

	
	$(document).on("click",".remove-po",function(e){
		var root = $(this).closest(".toClone");
		enableDisableBookBtn();
	});
	


	//check qty for backorder
	
	$(document).on("change",".qty-chkstk",function(){
		
		var qtroot = $(this).closest(".toClone");
		var stk =  qtroot.find(".itemName").data('stk');
		
		var qty = $(this).val();
		//alert(stk);
		
		//console.log("stk:"+stk+" | qty: "+qty);
		if(stk<qty){
			backorderCheck(stk,qtroot);
		}
		enableDisableBookBtn();
	});

var found;
function enableDisableBookBtn(){

		found =0;	
		const elements = document.querySelectorAll('.itemName');
		Array.from(elements).forEach((element, index) => {
		 
			// conditional logic here.. access element
			
			mystk = element.getAttribute("data-stk");
			if(mystk < 0){
				found ++;
			}
			
		});
		if(found>0){
			$("#book").prop('disabled', true);
		}else{
			$("#book").prop('disabled', false);
		}
}	

	

function backorderCheck(stock,root){
	
		var isAlert = root.find(".isBOAlert").val();
		
		console.log("stk:"+stock+" | isAlert: "+isAlert);
		if(isAlert!=1){
			setTimeout(function(){

			 
				 
			

			  swal({
			  title: "Do you want to allow Back Order?",
			  text: "This item is not available in stock",
			  icon: "warning",
			  buttons: true,
			  //dangerMode: true,
			  buttons: ['Cancel', 'Allow Back Order'],
			})
			.then((willDelete) => {
			  if (willDelete) {
				   root.find(".c-qty").focus();
			  } else {
				  setTimeout(function(){
					//$(this).val("Select Item");
					  
					  root.find(".dl-itemName").val("");
					  root.find(".dl-itemName").change();
					  root.find(".quantity_otc").val("");
					  root.find(".quantity_otc").change();
					  
					  root.find(".remove-order").trigger("click");
					  
				  },200);
				  
				  return false;
			  }
			});

				//return false;
				
				//put a flag after itemName field once alert is shown
				root.find(".dl-itemName").after('<input type="hidden" class="isBOAlert" value="1">');
			
			/* end backorder alert  */					 
				 
				 
				 

			},200);	
		}
}	

	
	
/* end autofill combo  */



</script>

<script>
    //Searchable dropdown
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
        $('#cmborg').val(id);
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

// new calculation code;
$(document).on("focus", ".calc", function() {
  $(this).select();
});

$(document).on("change", ".calc", function() {
  
   var tval = $(this).val();
  
	//tval = (isNaN(tval))?parseFloat(0).toFixed(2):parseFloat(tval).toFixed(2);

   if(tval>0){
     tval =  parseFloat(tval).toFixed(2)
   }else{
      tval =  parseFloat(0).toFixed(2)
   }
   
   //variables;
   var cvat = $(this).closest(".toClone").find(".c-vat").val();
   cvat = (cvat)?parseFloat(cvat).toFixed(2):parseFloat(0).toFixed(2);
   
   var cqty =  $(this).closest(".toClone").find(".c-qty").val(); 
   cqty = (cqty)?parseFloat(cqty).toFixed(2):parseFloat(0).toFixed(2);
   
   var cprice =  $(this).closest(".toClone").find(".c-price").val(); 
   cprice = (cprice)?parseFloat(cprice).toFixed(2):parseFloat(0).toFixed(2);
   
   var cdiscount =  $(this).closest(".toClone").find(".c-discount").val(); 
   cdiscount = (cdiscount)?parseFloat(cdiscount).toFixed(2):parseFloat(0).toFixed(2);   
   
  

   //Unit total price
   priceutt = (cprice*cqty).toFixed(2);
   $(this).closest(".toClone").find(".c-price-utt").val(priceutt);
   
 
   //unit discount amt
   discountamt = (priceutt*cdiscount*0.01).toFixed(2);
   $(this).closest(".toClone").find(".c-h-discount-amt").val(discountamt);

  //unit vat 
   vatamt = (((parseFloat(priceutt)-parseFloat(discountamt))*cvat)/100).toFixed(2);
   $(this).closest(".toClone").find(".c-h-vat-amt").val(vatamt);   
   
   //total discounted price with vat
   //discountedttl = ((parseFloat(priceutt)-parseFloat(discountamt))+parseFloat(vatamt)).toFixed(2);
  // $(this).closest(".toClone").find(".c-discounted-ttl").val(discountedttl);
   

   //total discounted price without vat
   discountedttl = (parseFloat(priceutt)-parseFloat(discountamt)).toFixed(2);
   $(this).closest(".toClone").find(".c-discounted-ttl").val(discountedttl);
	
	//put discounted total in hidden value field;
	$(this).closest(".toClone").find('input[name="unittotal[]"]').val(discountedttl);
	
	
   
   tval = parseFloat(tval).toFixed(2);
   $(this).val(tval);
   
   $(this).closest(".toClone").find(".c-discounted-ttl").trigger("change");
});


$(document).on("change",".calc",function(){
   OrderTotal();
  //alert(1);
});

function OrderTotal(){
   
   var subtotal = 0;
   var vattotal = 0;
   var distotal = 0;
   var grndttl = 0;

  //SUBTOTAL
  $(".toClone").each(function(){
      var thisval = $(this).find(".c-discounted-ttl").val();
      if(thisval>0){	subtotal += +thisval; }
   });
   subtotal = subtotal.toFixed(2);
   $(".f-subtotal").val(subtotal);
  
  //VAT TOTAL
    $(".toClone").each(function(){
      var thisvatamt = $(this).find(".c-h-vat-amt").val();
      if(thisvatamt>0){	vattotal += +thisvatamt; }
   });
   vattotal = vattotal.toFixed(2);
   $(".f-vatttl").val(vattotal);
  
  
  //DISCOUNT TOTAL
    $(".toClone").each(function(){
      var thisdiscamt = $(this).find(".c-h-discount-amt").val();
      if(thisdiscamt>0){	distotal += +thisdiscamt; }
   });
  
  
   distotal = distotal.toFixed(2);
   $(".f-disttl").val('('+distotal+')');
  
  var adjmt = $(".f-adjmt").val();
  var delcrg = $(".f-delcrg").val();
	
	
	
   adjmt = (adjmt>0)?parseFloat(adjmt).toFixed(2):parseFloat(0).toFixed(2);
   delcrg = (delcrg>0)?parseFloat(delcrg).toFixed(2):parseFloat(0).toFixed(2);
   
	
	
   grndttl = ((parseFloat(subtotal)+parseFloat(vattotal))-parseFloat(adjmt))+parseFloat(delcrg);
   $(".f-grnd-ttl").val(grndttl.toFixed(2));

  
   console.log(
	   "subtotal:"+subtotal+
	   "\n vattotal:"+vattotal+
	   "\n Adj:"+adjmt+
	   "\n Charge:"+delcrg);
   
   
 
}





</script>

<script>

//COPIER
	
$(document).ready(function() {
    var max_fields      = 20; //maximum input boxes allowed
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
		
		$(this).closest(".po-product-wrapper").find(".toClone:last-child .dl-itemName").focus();
    });

    $(wrapper).on("click",".remove-order", function(e){ //user click on remove text
        e.preventDefault();
		$(this).closest(".toClone").remove();
		 OrderTotal();
		x--;
		
    })
});	

	
//tab focus;
$(document).ready(function(){
	$(".dl-itemName").focus();
});	
$(document).on("blur",".c-vat",function(){
	$(".link-add-order").focus();
});

$(document).on("blur",".c-qty",function(){
	$(".c-discount").focus();
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
	
	
</body>
</html>
<?php } ?>