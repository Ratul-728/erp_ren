<?php
//print_r($_REQUEST);
//exit();
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
//echo $usr;die;
if ($usr == '') 
{
    header("Location: " . $hostpath . "/hr.php");
} 
else
{
    $res       = $_GET['res'];
    $msg       = $_GET['msg'];
    $id        = $_GET['id'];
    $invoiceno = $_GET['id'];
    $totamount = 0;

    if ($res == 1) 
    {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
        $mode = 1;
    } 
    elseif ($res == 2) 
    {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
        $mode = 1;
    } 
    elseif ($res == 4) 
    {
        //echo "<script type='text/javascript'>alert('".$id."')</script>";
        $qry = "SELECT  1 sl,i.`invoiceno`,i.makedt makedt,i.id iid, i.`invyr`, i.`invoicemonth`,i.`invoicedt`, i.`soid`, o.id cid, o.name `organization`, 
                (i.`invoiceamt` + i.`reserved_amount`) totinvoiceamt,format(i.amount_bdt,2) amount_bdt,   format(i.`paidamount`,2)paidamount, i.`dueamount` due, 
                format(i.`dueamount`,2)dueamount, i.`duedt`, s.`name`,s.`dclass` `invoiceSt`,p.`name` paySt,p.`id` paymentstid,p.`dclass` `paymentSt`,
                o.balance orgbal,o.id orgid, i.reserved_amount 
        		FROM `invoice` i  
        		LEFT JOIN invoicestatus s  on i.invoiceSt=s.id 
        		LEFT JOIN invoicepaystatus p on i.paymentSt=p.id  
                LEFT JOIN organization o on i.organization=o.id 
        		
        	 	WHERE  1=1 and i.invoiceno='".$invoiceno."'";
       //$qry = "SELECT p.`id`, p.`poid`,p.`adviceno`, p.`supid`, DATE_FORMAT(`p.orderdt`,'%e/%c/%Y') `orderdt`, p.`currency`, p.`tot_amount`, p.`invoice_amount`, p.`vat`, p.`tax`, DATE_FORMAT(`p.delivery_dt`,'%e/%c/%Y') `delivery_dt`, p.`hrid`,o.name org FROM `po` p left join o on p.supid=o.id  where  p.id= " . $id;
 //echo $qry; die;
        if ($conn->connect_error) 
        {
            echo "Connection failed: " . $conn->connect_error;
        } 
        else 
        {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) 
            {
                while ($row = $result->fetch_assoc())
                {
                    $socode             = $row["soid"];
                    $makedt             = $row["makedt"];
                    $organization       = $row["organization"];
                    $invoiceamt         = $row["totinvoiceamt"];
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$orderdt."')</script>";
    } 
    else 
    {
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

    $currSection = 'backordershifting';
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
            <span>Backorder/Futrure Customization</span>
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
                        <form method="post" action="common/addorderCustomization.php" id="form1" enctype="multipart/form-data">
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">




			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>

                                   <div class="row form-header">

	                                     <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h4>Approval <i class="fa fa-angle-right"></i> Back/Futer Order Shifting </h4>
      		                            </div>

      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>


                                   </div>



                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->

                                <div class="row">
                                     
										
										
											<div class="col-lg-3 col-md-6 col-sm-6">

												  <div class="form-group">
													 <label for="po_id">Invoice No </label>
													 <input type="text" class="form-control"  name="invoice" id="invoice" value="<?php echo $invoiceno; ?>" readonly>

													</div>
											</div>

											<div class="col-lg-3 col-md-6 col-sm-6">
												<div class="form-group">
													<label for="po_id">Order Id </label>
													<input type="text" class="form-control" name="socode" id="socode" value="<?php echo $socode ?>" readonly>
												</div>
											</div>	
											
											

												<div class="col-lg-3 col-md-6 col-sm-6">
													<label for="email">Order Date </label>
													<div class="input-group">
														<input type="text" class="form-control" id="order_dt" name="order_dt" value="<?php echo $makedt; ?>" readonly>
														
													</div>
												</div>		
												
												<div class="col-lg-3 col-md-6 col-sm-6">
													<label for="email">Invoice Amount </label>
													<div class="input-group">
														<input type="text" class="form-control" id="invoiceamt" name="invoiceamt" value="<?php echo $invoiceamt; ?>" readonly>
														
													</div>
												</div>
												
												<div class="po-product-wrapper withlebel">
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>

											<style>
												@media (min-width: 1199px){
													.withlebel .remove-icon {
/*													  bottom: 23px;*/
												
													}
												}
											</style>
											
 
											
											
											<div class="row form-grid-bls hidden-md hidden-sm hidden-xs">
											
											
                                                <div class="col-lg-2 col-md-5 col-sm-6">
                                                	<h6 class="chalan-header mgl10">Item</h6>
                                                </div>
												<div class="col-lg-1 col-sm-1 col-xs-6">
													<h6 class="chalan-header"> Quantity</h6>
												</div>
												<div class="col-lg-1 col-sm-1 col-xs-6">
													<h6 class="chalan-header">Total Vat</h6>
												</div>											

                                                <div class="col-lg-1 col-md-1 col-sm-6">
                                                    <h6 class="chalan-header">Total Discount</h6>
                                                </div>
                                                <div class="col-lg-2 col-md-1 col-sm-6">
                                                    <h6 class="chalan-header">Total Amount</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-6">
                                                    <h6 class="chalan-header">Reserved (%)</h6>
                                                </div>												
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">Adjustment Total Vat </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">Adjustment Total Discount </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">Adjustment Total Amount </h6>
                                                </div>
                                        </div>											
											
<?php
		
        $rCountLoop  = 0;
        $itdgt       = 0;
		$totalcost=0;$netamount=0;
		
		
        $itmdtqry    = "SELECT soi.id, i.name, soi.qty, soi.vat, soi.vat_reserved, soi.discounttot, soi.discounttot_reserved, soi.otc, soi.otc_reserved,
                        invd.id invid,soi.discountrate,soi.adjustment 
                        FROM `soitemdetails` soi LEFT JOIN item i ON i.id = soi.productid 
                        LEFT JOIN invoicedetails invd ON (invd.socode=soi.socode and invd.sosl=soi.sosl) WHERE soi.socode = '".$socode."'";
       
        $resultitmdt = $conn->query($itmdtqry);
		
		if ($resultitmdt->num_rows > 0) {
		    $footertotvat = 0;
            $footertotdis = 0;
            $footertot    = 0;
			
			while ($rowitmdt = $resultitmdt->fetch_assoc()) {
			$sodetialsid            = $rowitmdt["id"];
			$invid                  = $rowitmdt["invid"];
            $itemName               = $rowitmdt["name"];
            $qty                    = $rowitmdt["qty"];
            $vat                    = $rowitmdt["vat"];
            $vat_reserved           = $rowitmdt["vat_reserved"];
            $discountrate           = $rowitmdt["discountrate"];
            $discounttot            = $rowitmdt["discounttot"];
            $discounttot_reserved   = $rowitmdt["discounttot_reserved"]; 
            $otc                    = $rowitmdt["otc"];
            $otc_reserved           = $rowitmdt["otc_reserved"];
            $adjustment             = $rowitmdt["adjustment"];
            
            $tototc    = $otc + $otc_reserved;
            $totdisfinal = (($tototc * $qty) * $discountrate) / 100;
            $totamount = ($qty * $tototc) - $totdisfinal;
            $totdis = $discounttot + $discounttot_reserved;
            $totdisafter = $totamount - $totdis;
            
            $totamountbefore = ($qty * $otc);
            $totdisbefore =  $totamountbefore - $discounttot;
            
            $footertotvat += $vat;
            $footertotdis += $totdisbefore;
            $footertot    += ($totamountbefore-$totdisbefore);
            
            ?>
                                            <!-- this block is for php loop, please place below code your loop  -->
                                            
                                            
  											<!-- edit mode -->
                                            
                                            <div class="toClone">
                                                <div class="col-lg-2 col-md-1 col-sm-7 col-xs-8">
													
													<div class="form-group">
														<input  type="text" class="c-price form-control " placeholder="Product" id_="product" value="<?=$itemName?>" name="product[]" readonly>
														<input type="hidden"  class="form-control" name="sodetails[]" id="sodetails" value="<?php echo $sodetialsid; ?>">
													    <input type="hidden"  class="form-control" name="invid[]" id="invid" value="<?php echo $invid; ?>">
													</div>
												</div>

												<div class="col-lg-1 col-md-1 col-sm-7 col-xs-8">
													
													<div class="form-group">
														<input  type="text" class="c-price form-control qty" placeholder="Quantity" id_="qty[]" value="<?=$qty?>" name="qty[]" >
													</div>
												</div>												
													
                                                 <div class="col-lg-1 col-md-1 col-sm-2 col-xs-5">
												
                                                    <div class="form-group">
                                                        <input type="text" class="form-control vat" id_="vat" placeholder="Total Vat" value="<?php echo ($vat + $vat_reserved); ?>" readonly  name="vat[]">
                                                      
                                                    </div>
                                                </div> 
                                                <!-- this block is for discount-->
                                                 <div class="col-lg-1 col-md-1 col-sm-2 col-xs-5">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control discount" id_="discount" placeholder="Total Discount" value="<?php echo ($totdisfinal); ?>" readonly  name="discount[]">
                                                      
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-1 col-sm-2 col-xs-5">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control total_amount" id_="total_amount" placeholder="Total Amount" value="<?php echo $totamount; ?>" readonly  name="total_amount[]">
                                                      
                                                    </div>
                                                </div>
                                                 <div class="col-lg-1 col-md-1 col-sm-2 col-xs-5">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control adjustment" id_="adjustment" placeholder="Reserved(%)" value="<?= $adjustment ?>"  name="adjustment[]" readonly>
                                                      
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-5">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control adjustment_vat" id_="adjustment_vat" placeholder="Total Adjustment Vat" value="<?= $vat ?>" readonly  name="adjustment_vat[]">
                                                      
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-5">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control adjustment_discount" id_="adjustment_discount" placeholder="Total Adjustment Discount" value="<?= $totdisbefore ?>" readonly  name="adjustment_discount[]">
                                                      
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-2 col-md-1 col-sm-2 col-xs-5">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control adjustment_amount" id_="adjustment_amount" placeholder="Total Adjustment Amount" value="<?= $totamountbefore-$totdisbefore ?>" readonly  name="adjustment_amount[]">
                                                      
                                                    </div>
                                                </div>

                                            </div>
<?php }} ?>
                                    		<!-- this block is for php loop, please place below code your loop  -->
                                    	
                                    		<div class="well no-padding  top-bottom-border grandTotalWrapperx grid-sum-footer">
                                            <div class="row total-row border">
                                                
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label>Total VAT :</label>
                                                        <input type="text" class="f-vatttl form-control" id="vatdis" value="<?php echo  number_format($footertotvat,2, '.', ''); ?>"  name="vatdis"  readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label>Total Discount:</label><?php  $netdisc=$totalcost-$netamount;?>
                                                        <input type="text" class="f-disttl form-control" id="discountdsp" value="<?php echo  number_format($footertotdis,2, '.', ''); ?>"  name="discountdsp"  readonly>
                                                        
                                                    </div>
                                                </div>												
												
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label>Total Adjustment</label>
                                                        <input type="text" class="calc  numonly f-adjmt form-control" id="discntnt" value="<?php echo  number_format($footertot,2, '.', ''); ?>"  name="discntnt" readonly>
                                                    </div>
                                                </div>
                                                
                                            </div>                                           
                                            
                                        </div>
   
										
											
                                        </div>


										
                                    
                                    
										
										
									</div>
									


    

                            	    <br>
                                    
                                    



                                    <div class="col-sm-12">

                                            
                                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Order" id="update" >
                                          <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="copy" value="Copy SO" id="Copy"-->
                                          
                                        <a href = "./backordershiftingList.php?pg=1&mod=17">
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
    $(document).on("input", ".adjustment", function() {
        
      val = $(this).val();
      var root = $(this).closest('.toClone');
      
      
      totcalculation(root);
      
    });
    
    function totcalculation(root){
          
          var qty = parseFloat(root.find('.qty').val()) || 1;
          var vat = parseFloat(root.find('.vat').val()) || 0;
          var discount = parseFloat(root.find('.discount').val()) || 0;
          var totamount = parseFloat(root.find('.total_amount').val()) || 0;
          
          var adjustment = parseFloat(root.find('.adjustment').val()) || 0;
          
          var adjustment_vat        = (vat * adjustment) / 100;
          var adjustment_discount   = (discount * adjustment) / 100;
          var adjustment_totamount  = (totamount * adjustment) / 100; 
          
          if(adjustment > 0) {
              var a = root.find('.adjustment_vat').val(vat - adjustment_vat); 
              var b = root.find('.adjustment_discount').val(discount - adjustment_discount);
              var c = root.find('.adjustment_amount').val(totamount - adjustment_totamount);
          }else{
              var a = root.find('.adjustment_vat').val(0); 
              var b = root.find('.adjustment_discount').val(0);
              var c = root.find('.adjustment_amount').val(0);
          }
          
          //Subtotal
          var svt = 0;//vatdis discountdsp  discntnt
          var svat = 0;
          var sdt = 0;
          var sadt = 0;
          var sta = 0
          var sata = 0;
          
          $(".toClone").each(function(){
              var thisval = $(this).find(".vat").val();
              if(thisval>0){	svt += +thisval; }
              
              var thisval = $(this).find(".adjustment_vat").val();
              if(thisval>0){	svat += +thisval; }
              
              var thisval = $(this).find(".discount").val();
              if(thisval>0){	sdt += +thisval; }
              
              var thisval = $(this).find(".adjustment_discount").val();
              if(thisval>0){	sadt += +thisval; }
              
              var thisval = $(this).find(".total_amount").val();
              if(thisval>0){	sta += +thisval; }
              
              var thisval = $(this).find(".adjustment_amount").val();
              if(thisval>0){	sata += +thisval; }
          });
           
          svt = svt.toFixed(2);     svat = svat.toFixed(2);
          sdt = sdt.toFixed(2);     sadt = sadt.toFixed(2);
          sta = sta.toFixed(2);     sata = sata.toFixed(2);
           
          $("#vatdis").val(svat);
          $("#discountdsp").val(sadt);
          $("#discntnt").val(sata);
        
    }
    
    $(document).on("input", ".civu", function() {
        
      val = $(this).val();
      var root = $(this).closest('.toClone');
      
      var exrate = $('#ex_rate').val(); if (exrate === "") exrate = 1;
      var civu = parseFloat(root.find('.civu').val()) || 0;
      
      var civb = exrate * civu;
      var a = root.find('.civb').val(civb); 
      
      totcalculation(root);
      
    });
</script>
    
<script>
    $(document).ready(function() {
      // Listen for changes in the "Adjustment (%)" input field
      $('#discount').on('input', function() {
        var adjustmentPercentage = parseFloat($(this).val());
        var invoiceAmount = parseFloat($('#invoiceamt').val());
        
        // Calculate the total adjustment
        var totalAdjustment = (invoiceAmount * adjustmentPercentage) / 100;
        
        // Update the "Total Adjustment" input field
        $('#totaldis').val(totalAdjustment);
        
        // Calculate the total discount amount
        var totalDiscount = invoiceAmount - totalAdjustment;
        
        // Update the "Total Discount" input field
        $('#totaldisamt').val(totalDiscount);
      });
    });
</script>

</body>
</html>
<?php } ?>