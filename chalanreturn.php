<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
//echo $usr;die;
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
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
        $qry = "SELECT `id`, `poid`,`adviceno`, `supid`, DATE_FORMAT(`orderdt`,'%e/%c/%Y') `orderdt`, `currency`, `tot_amount`, `invoice_amount`, `vat`, `tax`, DATE_FORMAT(`delivery_dt`,'%e/%c/%Y') `delivery_dt`, `hrid` FROM `po`  where  id= " . $id;
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
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$orderdt."')</script>";
    } else {
        $uid            = '';
        $poid           = '';
        $supid          = '';
        $orderdt        = date("Y-m-d");
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

    $currSection = 'challaned';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>

<?php
include_once 'common_header.php';
    ?>
<body class="form">

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
                     <form method="post" action="common/addreturnchalan.php" id="form1" enctype="multipart/form-data" onsubmit="return confirm('Do you really want to save the chalan?');">  
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">
      		            <div class="panel-heading"><h1 class="left-align" >Return  Challan</h1></div>
			            <div class="panel-body">
                            <span class="alertmsg"></span>
                          	<p>(Field Marked * are required) </p>
                                <div class="row">
                            		<div class="col-sm-12">
		                                <hr class="form-hr">
		                                 <input type="hidden"  name="pid" id="pid" value="<?php echo $uid; ?>">
		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>" >
		                                 <input type="hidden"  name="challanno" id="challanno" value="<?php echo $poid; ?>" >
		                                 <input type="hidden"  name="cmbsupnm" id="cmbsupnm" value="<?php echo $supid; ?>" >

		                                  <div class="form-group">
    		                                 <label for="po_id">Challan NO*</label>
    		                                 <input type="text"  name="challan" id="challan" value="<?php echo $poid; ?>" disabled>

    		                                </div>
    	                            </div>

	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="po_id">Advice NO*</label>
                                            <input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $adv ?>" required disabled>
                                        </div>
                                    </div>

      	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsupnm">Organization Name*</label>
                                            <div class="form-group styled-select">

                                            <select name="cmbsupnm1" id="cmbsupnm1" class="form-control"  required disabled>
                                            <option value="">Select Organization Name</option>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `organization` order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                                <option value="<?php echo $tid; ?>" <?php if ($supid == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
    <?php }} ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>

                            	    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="po_dt">Order Date*</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" required disabled>

                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="email">Receive Date*</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="delivery_dt" name="delivery_dt" value="<?php echo $delivery_dt; ?>" required disabled>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>

      	                      <!--      <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Currency</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbcur" id="cmbcur" class="form-control">
                                            <option value="">Select Currency</option>
    <?php $qrycur = "SELECT `id`, `name`, `shnm` FROM `currency`  order by name";
    $resultcur        = $conn->query($qrycur);if ($resultcur->num_rows > 0) {while ($rowcur = $resultcur->fetch_assoc()) {
        $cid = $rowcur["id"];
        $cnm = $rowcur["shnm"];
        ?>
                                                <option value="<?php echo $cid; ?>" <?php if ($currency == $cid) {echo "selected";} ?>><?php echo $cnm; ?></option>
    <?php }} ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>-->
                                    <br>
                                    <div class="po-product-wrapper">
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Product Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
	                                        <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Select Item </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Quantity</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Cost Price </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Unit Total</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">MRP </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Select Store </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Expiry Date </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Remarks  </h6>
                                                </div>
                                            </div>
<?php
$rCountLoop = 0;
    $itdgt      = 0;
    $itmdtqry   = "SELECT  a.`poid`, a.`itemid`, a.`description`, a.`qty`, a.`unitprice`,a.mrp, a.`amount`,DATE_FORMAT(a.`expirydt`,'%e/%c/%Y') expirydt,a.storerome, b.name itemnm,a.barcode 
                    FROM `poitem` a LEFT JOIN item b ON b.id = a.itemid WHERE a.poid='" . $poid . "'";
//echo $itmdtqry;die;
    $resultitmdt = $conn->query($itmdtqry);if ($resultitmdt->num_rows > 0) {while ($rowitmdt = $resultitmdt->fetch_assoc()) {
        $itmdtid   = $rowitmdt["itemid"];
        $descr     = $rowitmdt["description"];
        $qty       = $rowitmdt["qty"];
        $unitprice = $rowitmdt["unitprice"];
        $amount    = $rowitmdt["amount"];
        $mrp       = $rowitmdt["mrp"];
        $expirydt  = $rowitmdt["expirydt"];
        $storerome = $rowitmdt["storerome"];
        $itmnm     = $rowitmdt["itemnm"];
        $bc    = $rowitmdt["barcode"];
        
         $itdtot   = number_format($amount,2);
        ?>


                                            <!-- this block is for php loop, please place below code your loop  -->
                                            
                                    		
                                    		 <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6">
													
                                                    <!--div class="form-group">
                                                       
                                                        <div class="form-group styled-select">
                                                            <select name="itemName[]" id="itemName" class="form-control dl-itemName">
                                                                <option value="">Select Item</option>
                                                                
    <?php $qryitm = "SELECT `id`, `name`, `vat`, `ait`, `cost`,rate  FROM `item`  order by name";
        $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
            $tid  = $rowitm["id"];  $nm   = $rowitm["name"]; $cost = $rowitm["cost"];  $vat  = $rowitm["vat"]; $ait  = $rowitm["ait"];$rate=$rowitm["rate"]; $up = number_format($rowitm["cost"],2);
            ?>
                                                                <option data-value="<?php echo $tid; ?>"  data-up="<?php echo $up; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" data-mrp="<?php echo $rate; ?>" value="<?php echo $tid; ?>" <?php if ($itmdtid == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
    <?php }} ?>
                                                            </select>
                                                        </div>
                                                    </div-->
                                                    
                                                    <div class="form-group">
                                                       
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list" value = "<?= $itmnm ?>" name="itmnm[]" class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm = "SELECT `id`, `name`, `vat`, `ait`, `cost`, rate  FROM `item`  order by name";
            $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                $tid  = $rowitm["id"];
                $nm   = $rowitm["name"];
                $cost = $rowitm["cost"]; 
                $up  = number_format($rowitm["cost"],2);
                $vat  = number_format($rowitm["vat"],2);
                $ait  = number_format($rowitm["ait"],2);
                $rate  = number_format($rowitm["rate"],2);
                ?>
                                                                <option data-value="<?php echo $tid; ?>" data-up="<?php echo $up; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" data-mrp="<?php echo $rate; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php }} ?>
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-lg-2 col-md-6 col-sm-6">

                                                    <div class="row qtnrows">
                                                        
                                                        <div class="col-lg-4 col-sm-6 col-xs-6">
															<!--<lebel>Quantity</lebel>-->
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" placeholder="Quantity" name="quantity_otc[]" value="<?php echo $qty; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-sm-6 col-xs-6">
														<!--	<lebel>OTC</lebel>-->
                                                            <div class="form-group">
                                                                <input  type="text" class="form-control unitprice_otc1 unitPriceV2" id="unitprice_otc1" value="<?php echo number_format($unitprice,2); ?>" name="unitprice_otc1[]">
                                                                <input type="hidden"  class="form-control unitprice_otc" name="unitprice_otc[]" id="unitprice_otc" value="<?php echo $unitprice; ?>">
                                                                <!--input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" placeholder="OTC" name="unitprice_otc[]" value="<?php echo $unitprice; ?>" -->
                                                                
                                                                <input type="hidden" name="unitTotalAmount_otc" class="unitTotalAmount_otc">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc-->
                                                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
												<!--	<lebel>Unit Total</lebel>-->
                                                    <div class="col-sm-6 col-xs-6">    
                                                        <div class="form-group">
                                                            <!--input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" readonly  name="unittotal[]" value="<?php echo $amount; ?>"-->
                                                            <input type="text" class="form-control unitTotalAmount1" id="unittotal1" placeholder="Unit Total" readonly  value="<?php echo $itdtot; ?>"  name="unittotal1[]">
                                                            <input type="hidden"  class="form-control unitTotalAmount" name="unittotal[]" id="unittotal" value="<?php echo $amount; ?>">
                                                        </div>
                                                   </div>  
                                                    <div class="col-sm-6 col-xs-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control unitpricemrc" id="unitpricemrc" placeholder="MRP" name="unitpricemrc[]"  value="<?php echo $mrp; ?>">
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for unit total, MRP-->
          	                                     <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                        <select name="storeName[]" i d="storeName" class="storeName form-control" required>
                                                        <option value="">Select Store</option>
    <?php $qryitm = "SELECT s.`id`, s.`name` FROM `branch` s order by s.name";

        $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
            $tid = $rowitm["id"];
            $nm  = $rowitm["name"];
            ?>
                                                            <option value="<?php echo $tid; ?>" <?php if ($storerome == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
    <?php }} ?>
                                                        </select>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for Store-->
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control datepicker"  placeholder="Expirydt" name="expdt[]" value="<?php echo $expirydt; ?>">
                                                    </div>
                                                </div><!-- this block is for Expiry date-->
                                                
          	                                    <div class="col-lg-1 col-md-6 col-sm-6">
												    <div class="form-group">
                                                        <input type="text" class="form-control"  placeholder="Description" name="description[]"  value="<?php echo $descr; ?>">
                                                        <input type="hidden"  name="bar[]" value="<?php echo $bc; ?>">
                                                    </div>
                                                </div> <!-- this block is for remarks-->

                                               <?php
if ($rCountLoop > 0) {
                ?>
                                               		<!--div class="remove-icon"><a href="#" class="remove-po" title="Return Item"><span class="glyphicon glyphicon-remove"></span></a></div-->
                                                <?php

            }
            $rCountLoop++;
            ?>

                                            </div>
<?php }} ?>
                                    	
                                        </div>


<div class="well no-padding top-bottom-border grandTotalWrapper">

    <div class="row total-row">

        <div class="col-xs-offset-6 col-xs-6 col-sm-offset-5 col-sm-4  col-md-offset-5 col-md-4 col-lg-offset-5 col-lg-1">

        	<div class="form-group grandTotalWrapper">

                <label>Total:</label>

                <input type="text" class="form-control" id="grandTotal" value="<?php echo number_format($tot_amount,2); ?>" disabled>

            </div>



        	</div>

    	</div>



	</div>


                                    </div>
                                    &nbsp;
                                    <div class="col-sm-12">
        	                            <a href="#" class="link-add-po" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
                                    <br><br>&nbsp;<br><br>



	                                <!--<div class="col-lg-3 col-md-6 col-sm-12">
                                        <strong>Attach vouchers</strong>
                                        <div class="input-group">
                                            <label class="input-group-btn">
                                                <span class="btn btn-primary btn-file btn-file">
                                                    <i class="fa fa-upload"></i> <input type="file" name="attachment1[]" id="attachment1" style="display: none;" multiple>
                                                </span>
                                            </label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                        <span class="help-block form-text text-muted"> Try selecting one or more files and watch the feedback </span>
                                    </div> --->



                                   <!-- <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbhr" >Purchase By</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbhr" id="cmbhr" class="form-control">
                                                <option value="">Select Purchase By</option>
<?php $qryhr = "SELECT `id`, `emp_id` FROM `hr`  order by emp_id";
    $resulthr    = $conn->query($qryhr);if ($resulthr->num_rows > 0) {while ($rowhr = $resulthr->fetch_assoc()) {
        $hrid = $rowhr["id"];
        $hrnm = $rowhr["emp_id"];
        ?>
                                                    <option value="<?php echo $hrid; ?>" <?php if ($deliveryby == $hrid) {echo "selected";} ?>><?php echo $hrnm; ?></option>
<?php }} ?>
                                                  </select>
                                                  </div>
                                          </div>
                                        </div>     -->
                                    <br><br>&nbsp;<br><br>&nbsp;<br><br><br>
                                </div>

                        </div>
                    </div>
        <!-- /#end of panel -->
                    <div class="button-bar">
                            <?php if ($mode == 2) { ?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Return Challan" id="update" >
                          <?php } else { ?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Challan" id="add" onclick="javascript:confirmationDelete($(this));return true;">
                          <?php } ?>
                          <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Cancel"  id="cancel" >
                    </div>
          <!-- START PLACING YOUR CONTENT HERE -->
           </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->

<?php include_once 'common_footer.php';    ?>
<?php include_once 'inc_cmb_loader_js.php'; ?>
<script>
    $(document).ready(function(){

        $(document).on("change", ".dl-itemName", function() {

            var val = $(this).val();
            //alert(val);
            var cost = $('#itemName option[value="' + val +'"]').attr('data-cost');
            var untprc = $('#itemName option[value="' + val +'"]').attr('data-up');
            var untmrc = $('#itemName option[value="' + val +'"]').attr('data-mrp');
            //var untprc=cost.toFixed(2);
            $(this).closest('.toClone').find('.unitprice_otc').val(cost);
            $(this).closest('.toClone').find('.unitprice_otc1').val(untprc);
			$(this).closest('.toClone').find('.quantity_otc').val(1);
            $(this).closest('.toClone').find('.unitTotalAmount1').val(untprc);
            $(this).closest('.toClone').find('.unitTotalAmount').val(cost);
            $(this).closest('.toClone').find('.unitpricemrc').val(untmrc);
			

            var vat = $('#itemName option[value="' + val +'"]').attr('data-vat');

            $(this).closest('.toClone').find('.vat').val(vat);

            var ait = $('#itemName option[value="' + val +'"]').attr('data-ait');

            $(this).closest('.toClone').find('.ait').val(ait);


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
<script>
function confirmationDelete(anchor)
    {
       var conf = confirm('Are you sure want to Save this record?');
       if(conf)
          window.location=anchor.attr("href");
    }



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

	src = root.find(".cmb-attr");



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
			//loadItemTypeAttr(id,src);
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
</body>
</html>
<?php } ?>