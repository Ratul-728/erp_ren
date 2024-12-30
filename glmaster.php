<?php
//print_r($_REQUEST);
//exit();

require "common/conn.php";
include_once('rak_framework/fetch.php');
session_start();
$usr = $_SESSION["user"];
//echo $usr;die;

//ini_set('display_errors', 1);


if ($usr == '') 
{
    header("Location: " . $hostpath . "/hr.php");
} 
else 
{
    $res  = $_GET['res'];
    $msg  = $_GET['msg'];
    $itid = $_GET['id'];
    if ($res == 1)
    {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
    }
    if ($res == 2) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
    }
	
    if ($res == 4) 
    { //update mode
	 $qry = "SELECT  `VoucherTp`,`vouchno`, DATE_FORMAT(`transdt`,'%e/%c/%Y') trdt, `refno`, `remarks` FROM `glmst` WHERE id = " . $itid;
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
                    $trdt  = $row["trdt"];
                    $vouchtp = $row["VoucherTp"];
                    $vouch = $row["vouchno"];
                    $ref   = $row["refno"];
                    $desc  = $row["remarks"];
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    } 
    else 
    {
	    $trdt  = '';
        $vouch = '';
        $ref   = '';
        $desc  = '';
        $mode  = 1; //Insert mode
        

    }
	
    $currSection = 'glmaster';
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
<?php include_once 'common_top_body.php';    ?>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>GL Master Details</span>
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
                       <form method="post" action="common/addglmaster.php"  id="GLform"  enctype="multipart/form-data">
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="panel panel-info">
			                <div class="panel-body panel-body-padding">
                                <span class="alertmsg"></span>
                                <div class="row form-header">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
  		                                <h6>Accounting <i class="fa fa-angle-right"></i> GL Voucher </h6>
  		                            </div>
  		                            <div class="col-lg-6 col-md-6 col-sm-6">
  	                                    <h6><span class="note"> (Field Marked <span class="redstar">*</span> are required)</span></h6>
  		                            </div>
                                </div>

                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->

                                <div class="row">
                        	        <div class="col-sm-12">
    	                            </div>
                                    <div class="row no-mg">
                                    </div>
                                    <div class="col-sm-12">
                                        <h4>GL Voucher  </h4>
                                        <hr class="form-hr">
                                    </div>                                    

                                    <input type = "hidden" name = "itid" value = "<?= $itid ?>">
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="ref">Vouch Type</label>
                                            <select name="vouchtp" id="vouchtp" class="form-control ">
                                                <option value="JV">Joural Voucher</option>
                                                <option value="PV">Payment Voucher</option>
                                                <option value="RV">Receive Voucher</option>
                                                <option value="OV">Others Voucher</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="ref">Vouch NO</label>
                                            <input type="text" class="form-control" id="vouchno" name="vouchno" value="<?php echo $vouch; ?>" readonly>
                                        </div>
                                    </div>
                            	    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="po_dt">Order Date<span class="redstar">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="trdt" id="trdt" value="<?php echo $trdt; ?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="ref">Reference</label>
                                            <input type="text" class="form-control" id="ref" name="ref" value="<?php echo $ref; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="details">Note</label>
                                            <textarea class="form-control" id="note" name="note" rows="2" ><?php echo $desc; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <strong>Image</strong>
                                        <div class="input-group">
                                            <label class="input-group-btn">
                                                <span class="btn btn-primary btn-file btn-file">
                                                    <i class="fa fa-upload"></i> <input type="file" name="attachment1" id="attachment1" style="display: none;" multiple>
                                                </span>
                                            </label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                        <span class="help-block form-text text-muted">
                                            Try selecting one  files and watch the feedback
                                        </span>
                                    </div>
                            	    <br>
                                    <div class="po-product-wrapper withlebel">
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Gl Details Information </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if($mode == 1) { //insert ?>
										<div class="row form-grid-bls  hidden-md hidden-sm hidden-xs">
										    
                                            <div class="col-lg-6 col-md-5 col-sm-6">
                                            	<h6 class="chalan-header"> GL Account <span class="redstar">*</span></h6>
                                            </div>

											<!--div class="col-lg-4 col-sm-2 col-xs-6">
												<h6 class="chalan-header"> Remarks </h6>
											</div-->
											<div class="col-lg-3 col-sm-2 col-xs-6">
												<h6 class="chalan-header"> Transaction Type <span class="redstar">*</span></h6>
											</div>											
                                            <div class="col-lg-3 col-md-2 col-sm-6">
                                                <h6 class="chalan-header">Amount <span class="redstar">*</span></h6>
                                            </div>
                                        </div> 
											 
                                        <div class="toClone">
                                           <div class="col-lg-6 col-md-5 col-sm-6">
												<label class="hidden-lg">Select GL Account</label> 
                                                <div class="form-group styled-select">
                                                        <input list="cmborg1" name ="cmbassign2" value = "" autocomplete="off"  class="dl-cmborg datalist form-control" placeholder="GL Account">
                                                        <datalist  id="cmborg1" name = "cmborg1" class="list-cmbassign form-control" >
                                                            <option value="">Select GL</option>
                                                                 <?php $qryitm = "SELECT concat(`glnm`, '(', `glno`, ')') glnm, glno FROM `coa` where oflag ='N' and isposted in('Y','P') order by glnm ";
                                                                    $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                    $tid = $rowitm["glno"];
                                                                    $nm  = $rowitm["glnm"]; ?>
                                                            <option data-value="<?php echo $tid; ?>" value="<?php echo $tid; ?>" ><?php echo $nm; ?></option>
                                                                <?php }} ?>
                                                        </datalist>
                                                        <input class="form-group glaccount" type = "hidden" name = "glaccount[]" id_ = "cmborg[]" value = "">
                                                </div> 
                                            </div>
                                            
                                              
      	                                    <!--div class="col-lg-6 col-md-5 col-sm-6">
												<label class="hidden-lg">Select GL Account</label>
                                                <div class="form-group">
                                                    <div class="form-group styled-select">
                                                        <select name="glno[]" id="glno[]" class="form-control">
                                                            <option value="">Select GL Account</option>
 <?php $qryunit = "SELECT `glnm`, `glno`, concat(`glnm`, '(', `glno`, ')') cnt FROM `coa` WHERE isposted = 'P' order by glnm";
        $resultunit     = $conn->query($qryunit);if ($resultunit->num_rows > 0) {while ($rowunit = $resultunit->fetch_assoc()) {
            $unitid = $rowunit["glno"];
            $unitnm = $rowunit["cnt"];
            ?>
                                                            <option value="<?php echo $unitid; ?>"><?php echo $unitnm; ?></option>
     <?php }} ?>
                                                      </select>
                                                    </div>
                                                </div>
                                            </div--> <!-- this block is for Gl Account-->
											<!--div class="col-lg-4 col-sm-2 col-xs-6">
												<div class="form-group">
                                                    <input type="text" class="form-control" id="remarks" placeholder="Remarks"  name="remarks[]">
                                                </div>
											</div-->												
											<div class="col-lg-3 col-sm-2 col-xs-6">
		                                        <div class="form-group">
	                                                <select name="trtp[]" id="trtp[]" class="form-control tp">
		                                                <option value="">Select D/C</option>
		                                                <option value="D">Debit</option>
		                                                <option value="C">Credit</option>
	                                                </select> 
                                                    <!--input type="text" class="form-control d-amount" onke_yup="_numberFormat()" id="d_amount" placeholder="Debit"  name="d_amount[]" value = "<?=$d_amount ?>"-->
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-2 col-sm-6">
		                                        <div class="form-group">
                                                    <input type="text" class="form-control c-amount" id="c_amount" placeholder="Amount"  name="c_amount[]" value = "<?=$c_amount ?>">
                                                </div>
                                            </div>   
                                        </div>
<?php } 
else 
{ // edit
?>
										<style>
											@media (min-width: 1199px){
												.withlebel .remove-icon {
/*													  bottom: 23px;*/
											
												}
											}
										</style>
										<div class="row form-grid-bls  hidden-md hidden-sm hidden-xs">
                                            <div class="col-lg-6 col-md-5 col-sm-6">
                                            	<h6 class="chalan-header"> GL Account <span class="redstar">*</span></h6>
                                            </div>
											<!--div class="col-lg-4 col-sm-2 col-xs-6">
												<h6 class="chalan-header"> Remarks </h6>
											</div-->
											<div class="col-lg-3 col-sm-2 col-xs-6">
												<h6 class="chalan-header"> Transaction Type <span class="redstar">*</span></h6>
											</div>											
                                            <div class="col-lg-3 col-md-2 col-sm-6">
                                                <h6 class="chalan-header">Amount <span class="redstar">*</span></h6>
                                            </div>
                                        </div>
<?php
	$rCountLoop  = 0;
    $itdgt       = 0;
    $totdr=0;$totcr=0;
    $itmdtqry    = "SELECT d.id,d.`glac`,c.glnm,d.`dr_cr`,d.`amount`,d.`remarks`,d.sl FROM `gldlt` d left join coa c on d.glac=c.glno WHERE d.`vouchno` = '$vouch' order by d.sl";
    //echo $itmdtqry;
    $resultitmdt = $conn->query($itmdtqry);if ($resultitmdt->num_rows > 0) {while ($rowitmdt = $resultitmdt->fetch_assoc()) {
    $idno    = $rowitmdt["id"];
    $glsl    = $rowitmdt["sl"];
    $glno    = $rowitmdt["glac"];
    $type    = $rowitmdt["dr_cr"];
    $amount  = $rowitmdt["amount"];
    $remarks = $rowitmdt["remarks"];
    $glnm = $rowitmdt["glnm"];
    if ($type == 'D')
    {
        //$d_amount = $amount;
        //$c_amount = '0';
        $totdr=$totdr+$amount;
    } 
    else 
    {
        //$c_amount = $amount;
        //$d_amount = '0';
        $totcr=$totcr+$amount;
    }
    $c_amount = $amount;
?>
                                            <!-- this block is for php loop, please place below code your loop  -->
  											<!-- edit mode -->
  											<div class="toClone">
                                                <div class="col-lg-6 col-md-5 col-sm-6">
    												<label class="hidden-lg">Select GL Account</label> 
                                                
                                                    <div class="form-group styled-select">
                                                            <input type="hidden" name="itmid" value ="<?= $idno ?>" value="" class="itemName">
                                                            <input type="hidden" name="itsl" value ="<?= $glsl ?>" value="" class="itemName">
                                                            <input list="cmborg1" name ="cmbassign2[]" value = "<?php echo $glnm; ?> <?= $glno ?>" autocomplete="off"  class="dl-cmborg datalist form-control" placeholder="<?= $glnm ?>">
                                                            <!--datalist  id="cmborg1" name = "cmborg1[]" class="list-cmbassign form-control" >
                                                                <option value="">Select GL</option>
                                                                     <?php $qryitm = "SELECT concat(`glnm`, '(', `glno`, ')') glnm, glno FROM `coa` where oflag ='N' and isposted in('Y','P') order by glnm ";
                                                                        $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                        $tid = $rowitm["glno"];
                                                                        $nm  = $rowitm["glnm"]; ?>
                                                                <option data-value="<?php echo $tid; ?>" <?php if ($glno == $tid) {echo "selected";} ?> > <?php echo $nm; ?></option>
                                                                    <?php }} ?>
                                                            </datalist-->
                                                            
                                                            <datalist  id="cmborg1" name = "cmborg1" class="list-cmbassign form-control" >
                                                            <option value="">Select GL</option>
                                                                 <?php $qryitm = "SELECT concat(`glnm`, '(', `glno`, ')') glnm, glno FROM `coa` where oflag ='N' and isposted in('Y','P') order by glnm ";
                                                                    $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                    $tid = $rowitm["glno"];
                                                                    $nm  = $rowitm["glnm"]; ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?php echo $tid; ?>" ><?php echo $nm; ?></option>
                                                                    <?php }} ?>
                                                            </datalist>
                                                        
                                                        
                                                         <input class="form-group glaccount" type = "hidden" name = "glaccount[]" id_ = "cmborg[]" value="<?=$glno?>">
                                                    </div> 
                                                </div>
                                                
                                                <!--div class="toClone">
              	                                    <div class="col-lg-6 col-md-5 col-sm-6">
    													<label class="hidden-lg">GL Account</label>
    													<div class="form-group">
                                                            <div class="form-group styled-select">
                                                                <input type="hidden" name="itmid" value ="<?= $idno ?>" value="" class="itemName">
                                                                <input type="hidden" name="itsl" value ="<?= $glsl ?>" value="" class="itemName">
                                                                <select name="glno[]" id="glno" class="form-control">
                                                                    <option value="">Select GL Account</option>
                                                                    <?php $qryunit = "SELECT `glnm`, `glno`, concat(`glnm`, '(', `glno`, ')') cnt FROM `coa` WHERE isposted = 'P'  order by glnm";
                                                                            $resultunit     = $conn->query($qryunit);if ($resultunit->num_rows > 0) {while ($rowunit = $resultunit->fetch_assoc()) {
                                                                                $unitid = $rowunit["glno"];
                                                                                $unitnm = $rowunit["cnt"];
                                                                                ?>
                                                                                <option value="<?php echo $unitid; ?>" <?php if ($glno == $unitid) {echo "selected";} ?>><?php echo $unitnm; ?></option>
                                                                     <?php }} ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div--> <!-- this block is for GL Account-->
    												<!--div class="col-lg-4 col-sm-2 col-xs-6">
    													<div class="form-group">
                                                            <input type="text" class="form-control" id="remarks" placeholder="Remarks"  name="remarks[]" value = "<?=$remarks ?>">
                                                        </div>
    												</div-->												
    												<div class="col-lg-3 col-sm-2 col-xs-6">
                                                        <div class="form-group">
                                                            <select name="trtp[]" id="trtp[]" class="form-control">
    		                                                <option value="">Select D/C</option>
    		                                                <option value="D" <?php if ($type == "D") {echo "selected";} ?>>Debit</option>
    		                                                <option value="C" <?php if ($type == "C") {echo "selected";} ?>>Credit</option>
    	                                                </select> 
                                                            <!--input type="text" class="form-control d-amount"  data-type="debit"  onkey_up="_numberFormat()" i_d="d_amount" placeholder="Debit"  name="d_amount[]" value = "<?=$d_amount ?>"-->
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-2 col-sm-6">
    			                                        <div class="form-group">
                                                            <input type="text" class="form-control c-amount"  data-type="credit"  i_d="c_amount" placeholder="Credit"  name="c_amount[]" value = "<?=$c_amount ?>">
                                                        </div>
                                                    </div>
                                                    <?php if ($rCountLoop > 0) { ?>
                                               		<div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                                    <?php } $rCountLoop++; ?>
                                                </div>  <!--END OF <div class="toClone">-->  
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
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label>Total Debit :</label>
                                                        <input type="text" class="form-control totaldr" id="totaldr" value="<?php echo  number_format($totdr,2, '.', ''); ?>"  name="totaldr"  readonly>
                                                        <input type="hidden" class="form-control" id="vatt" value="<?php echo  $totdr; ?>"  name="vatt"  readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                        <label>Total Credit: </label>
                                                        <input type="text" class="totalcr form-control" id="totalcr" value="<?php echo  number_format($totcr,2, '.', ''); ?>"  name="totalcr"  readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group grandTotalWrapper label-flex pull-right">
                                                        <label>Net </label><?php $itdgt=$totdr-$totcr ?>
                                                        <input type="text" class="form-control grandTotal" id="grandTotal" name = "grandTotal" value="<?php echo number_format($itdgt,2, '.', ''); ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <div class="col-sm-12"> 
											
                                            <?php if ($mode == 1) { 
                                             ?>
                                        			
													<input class="btn btn-lg btn-default top" type="submit" name="add" value="Create Voucer"  id="add" > 
										
                                          <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="copy" value="Copy SO" id="Copy"-->
                                          <?php } else { // new insert ?>

											  	
													<input class="btn btn-lg btn-default" type="submit" name="update" value="Update Order"  id="update"  > 										
										
                                          <?php } ?>

												<input  class="btn btn-lg btn-warning top" type="button" name="postaction" value="Cancel" id="cancel"  onClick="location.href = 'glmasterList.php?pg=1&mod=7'" >

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


<script language="javascript">

$(document).on("change", ".c-amount,.tp", function() {
	//var selectedValue = $(this).children("option:selected").val();
	var sumdr = 0;
	var sumcr = 0;
		
		$(".tp").each(function() {
			var type = $.trim($(this).val());
		 // alert(val);
			if (type=="D") {
				var amt=  $(this).closest(".toClone").find(".c-amount").val();
				//alert(amt);
				val = parseFloat( amt.replace( /^\$/, "" ) );
		
				sumdr += !isNaN( val ) ? val : 0;
			}
			if (type=="C") {
				var amt=$(this).closest(".toClone").find(".c-amount").val();
				//alert(amt);
				val = parseFloat( amt.replace( /^\$/, "" ) );
		
				sumcr += !isNaN( val ) ? val : 0;
			}
			
		});
		
		var net=sumdr-sumcr
		$("#totaldr").val(sumdr);
		$("#totalcr").val(sumcr);
		$("#grandTotal").val(net);
		//alert(net);
	    //	$(#totaldr).val=sumdr;
		if(sumdr==sumcr)
		{
		  $("#add, #update").removeAttr("disabled");
		  //  alert ("Debit and credit now equal");
		}
		else
		{
		 //$("#add, #update").removeAttr("disabled");
		 $("#add, #update").attr("disabled","disabled");
		 //alert ("Debit and credit must equal");
		}
	
});



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
<script>
    //Searchable dropdown
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        //alert(g);
        var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
        $('#cmborg').val(id);
        var urlqeurystringvalue = 'fglno='+id;
        $("#urlqeurystring").val(urlqeurystringvalue);
        //alert(id);
        $(this).closest(".toClone").find(".glaccount").val(id);


	}); 
</script>

</body>
</html>
<?php } ?>