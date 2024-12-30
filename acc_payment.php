<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    $res  = $_GET['res'];
    $msg  = $_GET['msg'];
    $itid = $_GET['id'];

    if ($res == 4) {
        $qry = "SELECT pay.`id`, pay.`trdt`, pay.`transmode`, pay.`transref`, pay.`chequedt`, pay.`customer`, pay.`naration`, pay.`amount`, pay.`costcenter`, pay.`chqclearst`, pay.`cleardt`, pay.`st`, 
                    pay.`makeby`, pay.`makedt`, pay.`glac`,pay.`crglno`, t.`value` tds, v.`value` vds, pay.tds paytds, pay.vds payvds
                    FROM `allpayment`  pay LEFT JOIN tds t on t.id = pay.tds LEFT JOIN tds v ON v.id=pay.vds where pay.id=" . $itid;
        // echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $fpid      = $row["id"];
                    $trdt      = $row["trdt"];
                    $transmode = $row["transmode"];
                    $transref  = $row["transref"];
                    $chequedt  = $row["chequedt"];
                    $customer  = $row["customer"];

                    $naration   = $row["naration"];
                    $amount     = $row["amount"];
                    $costcenter = $row["costcenter"];

                    $trdt = str_replace('-', '/', $trdt);
                    $trdt = date('d/m/Y', strtotime($trdt));

                    $chequedt = str_replace('-', '/', $chequedt);
                    $chequedt = date('d/m/Y', strtotime($chequedt));
                    
                    $glac = $row["glac"];
                    $crglno = $row["crglno"];
                    $tds = $row["tds"]; if($tds == '') $tds = 0;
                    $vds = $row["vds"]; if($vds == '') $vds = 0;
                    
                    $totaltds = ($amount * $tds) / 100;
                    $totalvds = ($amount * $vds) / 100;
                    $total = $amount + $totaltds + $totalvds;
                    
                    $paytds = $row["paytds"];
                    $payvds = $row["payvds"];
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    } else {
        $fpid      = '';
        $trdt      = '';
        $transmode = '';
        $transref  = '';
        $chequedt  = '';
        $customer  = '';

        $naration   = '';
        $amount     = '';
        $costcenter = '';
        $mode       = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'acc_payment';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>

<body class="form">
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Account Payment Details</span>
        </div>
        <?php include_once 'menu.php'; ?>
	    <div style="height:54px;">
	    </div>
    </div>
   <!-- END #sidebar-wrapper -->
   <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12">
                    <p>&nbsp;</p> <p>&nbsp;</p>
                    <p>
                        <form method="post" action="common/addacc_payment.php"  id="form1"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			               <!-- <div class="panel-heading"><h1></h1></div>-->
				                <div class="panel-body">
                                    <span class="alertmsg"></span>

                                <!--    <br> <p>(Field Marked * are required) </p> -->


                                     <div class="row">
                                        <div class="col-sm-3 text-nowrap">
                                                <h6>Account <i class="fa fa-angle-right"></i> Account Fund Payment Information</h6>
                                           </div>
                                           <br>
                                           <br>
                                    </div>
                                       <div class="row new-layout-header">
      		                
                                        <input type="hidden"  name="exid" id="exid" value="<?php echo $exid; ?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
		                                    <input type = "hidden" name = "fpid" value = "<?= $fpid ?>" >
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="cd">Date </label>
                                            <div class="input-group">

                                                <input type="text" class="form-control datepicker" id="trdt" name="trdt" value="<?php echo $trdt; ?>">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbmode"> Trans Mode*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbmode" id="cmbmode" class="form-control" >
    <?php
$qry1    = "SELECT `id`, `name`  FROM `transmode`  order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                                <option value="<?echo $tid; ?>" <?if ($transmode == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
    <?php }} ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Reference/Cheque No.</label>
                                                <input type="text" class="form-control" id="ref" name="ref" value="<?php echo $transref; ?>">
                                            </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">

                                            <label for="chqdt">Cheque Date </label>
                                            <div class="input-group">

                                                <input type="text" class="form-control datepicker" id="chqdt" name="chqdt" value="<?php echo $chequedt; ?>">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsupnm">Supplier Name*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbsupnm" id="cmbsupnm" class="form-control" >
                                                <option value="">Select Supplier</option>
    <?php
$qry1    = "SELECT s.`id`, s.`name` FROM `suplier` s order by s.name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                                <option value="<?echo $tid; ?>" <?if ($customer == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
    <?php }} ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
      	                                <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="amt">Amount </label>
                                                <input type="text" class="form-control" id="amt" name="amt" value="<?php echo $amount; ?>">
                                            </div>
                                        </div> -->
                                        <!--div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbcc"> costcenter*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbcc" id="cmbcc" class="form-control" >
    <?php
$qry1    = "SELECT `id`, `name`  FROM `costcenter`  order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                                <option value="<?echo $tid; ?>" <?if ($costcenter == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
    <?php }} ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div -->
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for unit  and substring(`glno`,1,1)  in (1,2)-->
                                        <div class="form-group">
                                            <label for="code">Debit GL </label>
                                            <div class="form-group styled-select">
                                                <select name="glac" id="glac" class="form-control">
                                                    <option value="">Select GL Account</option>
                                         
<?php // Root Level 
$qrymu="SELECT `glno`, `glnm`FROM `coa` WHERE status = 'A' and isposted = 'P' and oflag='N' order by glno"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
  { 
      $mid= $rowmu["glno"];  $mnm=$rowmu["glnm"];
?>                                                          
                                                    <option value="<?php echo $mid; ?>" <?php if ($glac == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
    
<?php  }}?>                                                  
                                                </select>
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for unit-->
                                        <div class="form-group">
                                            <label for="code">Credit GL </label>
                                            <div class="form-group styled-select">
                                                <select name="crglac" id="crglac" class="form-control">
                                                    <option value="">Select GL Account</option>
                                         
<?php // Root Level 
$qrymu="SELECT `glno`, `glnm`FROM `coa` WHERE status = 'A' and isposted = 'P' and oflag='N' order by glno"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
  { 
      $mid= $rowmu["glno"];  $mnm=$rowmu["glnm"];
?>                                                          
                                                    <option value="<?php echo $mid; ?>" <?php if ($crglno == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
    
<?php  }}?>                                                  
                                                </select>
                                            </div>
                                        </div>        
                                    </div>
                                    
                                    </div>
                                        <br>
                                        <div class="po-product-wrapper withlebel">
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Paymnet Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
                                            
											
										<div class="row form-grid-bls  hidden-md hidden-sm hidden-xs">
											
											
                                                <div class="col-lg-4 col-md-5 col-sm-6">
                                                	<h6 class="chalan-header mgl10"> Narration <span class="redstar">*</span></h6>
                                                </div>

												<div class="col-lg-2 col-sm-2 col-xs-6">
													<h6 class="chalan-header"> Amount <span class="redstar">*</span></h6>
												</div>
												<div class="col-lg-2 col-sm-2 col-xs-6">
													<h6 class="chalan-header"> TDS(%)</h6>
												</div>											
                                                <div class="col-lg-2 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">VDS(%)</h6>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-6">
                                                    <h6 class="chalan-header">Total Amount</h6>
                                                </div>
                                        </div>
											
											
	                                        <div class="toClone">
          	                                    <div class="col-lg-4 col-md-5 col-sm-6">
													<div class="form-group">
														<input type="text" class="form-control descr" id="fee" value="<?= $naration ?>" placeholder="Narration" name="descr" required>
														
													</div>
                                                </div> <!-- this block is for itemName-->
												
												
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-8">
													<div class="form-group">
														<input type="number" class="numonly form-control amt" id="amount" value="<?= $amount ?>" placeholder="Amount" name="amt" required>
														
													</div>
												</div>												
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <select name="tds" id="tds" class="form-control tds" >
                                                        <?php
                                                        $qry1    = "SELECT `id`, `name`, `value`  FROM `tds`  order by name";
                                                        $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
                                                            $tid = $row1["id"];
                                                            $nm  = $row1["name"];
                                                            $val = $row1["value"];
                                                            ?>
                                                                        <option value="<?echo $tid; ?>" data-val="<?= $val ?>" <?if ($paytds == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
                                                        <?php }} ?>
                                            </select>
                                                    </div>
                                                </div>
												
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                         <select name="vds" id="vds" class="form-control vds" >
                                                        <?php
                                                        $qry1    = "SELECT `id`, `name`, `value`  FROM `tds`  order by name";
                                                        $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
                                                            $tid = $row1["id"];
                                                            $nm  = $row1["name"];
                                                            $val = $row1["value"];
                                                            ?>
                                                                        <option value="<?echo $tid; ?>" data-val="<?= $val ?>" <?if ($payvds == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
                                                        <?php }} ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
			
                                                    <div class="form-group">
                                                        <input type="number" class="form-control total" id="total" placeholder="Unit Total"  name="total" value = "<?= $total ?>" readonly>
                                                      
                                                    </div>
                                                </div> 

                       
                                            </div>
                               </div>


                                        <div class="well no-padding  top-bottom-border grandTotalWrapperx grid-sum-footer">
                                            <div class="col-sm-12 test-img">
                                					    	<ul id="ajax-img-up">
                                    				    <li>

                                    				    </li>

                                    				    <li class="addimg-btn">
                                    				        <label class="input-group-btn">

                                    				            <span class="fa fa-plus"></span> <input type="file" name="file" id="upfiles" style="display: none;" i d="gallery-photo-add" multiple >

                                    				       </label>
                                    				    </li>
                                    				</ul>
                                    				<div class="clearfix">
                                    				    <p>&nbsp;</p><p>&nbsp;</p><br />
                                    				    </div>
                                					</div>
                                            <div class="row total-row border">
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label>Total TDS :</label>
                                                        <input type="text" class="f-vatttl form-control totaltds" id="totaltds" value="<?php echo  number_format($totaltds,2, '.', ''); ?>"  name="totaltds"  readonly>
                                                        <input type="hidden" class="form-control" id="vatt" value="<?php echo  $vatt; ?>"  name="vatt"  readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label>Total VDS: </label>
                                                        <input type="text" class="totalvds form-control" id="totalvds" value="<?php echo  number_format($totalvds,2, '.', ''); ?>"  name="totalvds"  readonly>
                                                        
                                                    </div>
                                                </div>												
											    <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper label-flex pull-right">
                                                        <label>Total Amount: </label><?php $itdgt=number_format($itdgt,2)?>
                                                        <input type="text" class="form-control f-subtotal" id="grandTotal" name = "grandTotal" value="<?php echo str_replace(",","",$total); ?>" readonly>
                                                    </div>
                                                </div>
                                                
                                            </div>                                           
                                            
                                        </div>
   
                        
                                    </div>
                                </div>
                            </div>
                            <!-- /#end of panel -->
                            <div class="button-bar">
                                <?php if ($mode == 2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update payment"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Payment"  id="add" >
                                <?php } ?>
                            <a href = "./acc_paymentList.php?pg=1&mod=7">
                                <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                            </a>
                            </div>
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
<?php include_once 'common_footer.php'; ?>
<?php include_once 'inc_cmb_loader_js.php'; ?>

<script>
    $(document).on("input", ".amt, .tds, .vds", function() {
        
        var selectTds = $('#tds').find('option:selected');
        var tdsVal = selectTds.data('val');
        
        var selectVds = $('#vds').find('option:selected');
        var vdsVal = selectVds.data('val');
        
        var amount = $('#amount').val();

        totalvalue(tdsVal, vdsVal, amount)
    });
    
    
    function totalvalue(tds,vds,amount){
        //Subtotal
        tds = parseFloat(tds);
    vds = parseFloat(vds);
    amount = parseFloat(amount);
          var totaltds = (amount * tds) / 100;
          var totalvds = (amount * vds) / 100;
          var subtotal = amount + totaltds + totalvds;
           
          subtotal = subtotal.toFixed(2);
          totaltds = totaltds.toFixed(2);
          totalvds = totalvds.toFixed(2);
           
           $(".f-subtotal").val(subtotal);
           $(".total").val(subtotal);
           $(".totaltds").val(totaltds);
           $(".totalvds").val(totalvds);
           //$(".f-disttl").val(subunittotal);
        
    }
</script>

<!-- AJAX PHP IMAGES UP -->


<script>

$(document).ready(function(){



    $(document).on('click', '#ajax-img-up li u', function() {

		    var imgToDeletePath = $(this).parent().find("img").attr('src');
		    var thisLi = $(this).parent();



		    //alert(imgToDeletePath);

           $.ajax({
              url: 'phpajax/deletepicajx.php',
              type: 'post',
              data: {action: 'deletepic', pictodelete: imgToDeletePath},


              success: function(response){
                 if(response != 0){


					alert(response);
					thisLi.remove();

                 }else{
                   alert('Error deleting picture');
                }
              },
           });



	});

	var picid = 1;

    $("#upfiles").change(function(){

        var fd = new FormData();
        var files = $('#upfiles')[0].files;

		//alert(files.length);

        // Check file selected or not
        if(files.length > 0 ){
           fd.append('file',files[0]);



           $.ajax({
              url: 'phpajax/uploadimageajx.php',
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){

                  if(response == 2){
                      alert('Invalid image dimension');
                  }else if(response != 0){

					 //alert(response);
					 $('#ajax-img-up li:last').before('<li class="picbox"><u class="fa fa-trash"></u><label class="custom-radio"><input type="radio" id="picid_'+picid+'" name="default-pic" value="'+response+'"><div class="radio-btn"><i class="fas fa-check" aria-hidden="true"></i><img src="'+response+'"><input type="hidden" name="imgfiles[]" value="'+response+'"></div><label></li>');

					 picid++;

					 //alert(response);
                 }else{
                    alert('file not uploaded');
                 }
              },
           });
        }else{
           alert("Please select a file.");
        }
   });


});


</script>

<?php

    if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }

    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>
</body>
</html>
<?php } ?>