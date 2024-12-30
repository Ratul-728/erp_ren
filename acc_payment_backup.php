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
        $qry = "SELECT `id`, `trdt`, `transmode`, `transref`, `chequedt`, `customer`, `naration`, `amount`, `costcenter`, `chqclearst`, `cleardt`, `st`, `makeby`, `makedt`, `glac` FROM `allpayment` where id= " . $itid;
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
      		                            <div class="col-lg-10 col-md-10">
      		                                <div class="form-group">
                                                <!--<label for="ref">Subject*</label> -->
                                                <input type="text" class="form-control com-nar" id="descr" name="descr" value="<?php echo $naration; ?>" autofocus="autofocus"  placeholder="Add a Narration" required>
                                            </div>
	                                   <!--     <h4></h4>
	                                        <hr class="form-hr">  -->

		                                    <input type="hidden"  name="exid" id="exid" value="<?php echo $exid; ?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
		                                    <input type = "hidden" name = "fpid" value = "<?= $fpid ?>" >
	                                    </div>
                                        <div class="col-lg-2 col-md-2 new-layout-amount ">

                                            <div class="form-group">
                                                <label for="amt">Amount </label>
                                                <input type="text" placeholder="Tk 0.00" class="form-control amount-fld" id="amt" name="amt" value="<?php echo $amount; ?>">
                                            </div>

                                        </div>
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
    <?php
$qry1    = "SELECT `id`, `name`  FROM `contact` where contacttype = 1  order by name";
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
                                        <div class="col-lg-3 col-md-6 col-sm-6">
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
                                    </div>
                                    
                                        <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for unit-->
                                                    <div class="form-group">
                                                        <label for="code">GL Account</label>
                                                        <div class="form-group styled-select">
                                                            <select name="glac" id="glac" class="form-control">
                                                     
 <?php // Root Level 
 $qrymu="SELECT `glno`, `glnm`FROM `coa` WHERE status = 'A' and isposted = 'P' and substring(`glno`,1,1)  in (1,2) order by glno"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["glno"];  $mnm=$rowmu["glnm"];
    ?>                                                          
                                                                <option value="<?php echo $mid; ?>" <?php if ($glac == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
                
     <?php  }}?>                                                  
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div>


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