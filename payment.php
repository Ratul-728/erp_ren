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
        $qry = "SELECT `id`, `trdt`, `transmode`, `transref`, `chequedt`, `customer`, `naration`, `amount`, `costcenter`, `chqclearst`, `cleardt`, `st`, `makeby`, `makedt` FROM `allpayment` where id= " . $itid;
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
                    $org  = $row["customer"];

                    $naration   = $row["naration"];
                    $amount     = number_format($row["amount"],2);
                    $costcenter = $row["costcenter"];

                    $trdt = str_replace('-', '/', $trdt);
                    $trdt = date('d/m/Y', strtotime($trdt));

                    $chequedt = str_replace('-', '/', $chequedt);
                    $chequedt = date('d/m/Y', strtotime($chequedt));
                    
                    $qrycmbinfo = "SELECT `id`, `name`  FROM `organization` WHERE id = ".$org;
                    $resultcmbinfo = $conn->query($qrycmbinfo);
                    while ($rowcmbinfo = $resultcmbinfo->fetch_assoc()) {
                        $orgname = $rowcmbinfo["name"];
                    }
                    
                    
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    } else {
        $fpid      = '';
        $trdt      = date('d/m/Y');
        $transmode = '';
        $transref  = '';
        $chequedt  = '';
        $org  = '';

        $naration   = '';
        $amount     = '';
        $costcenter = '';
        $mode       = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'payment';
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
            <span>Payment Details</span>
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
                        <form method="post" action="common/addpayment.php"  id="form1"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			               <!-- <div class="panel-heading"><h1></h1></div>-->
				                <div class="panel-body">
                                    <span class="alertmsg"></span>

                                <!--    <br> <p>(Field Marked * are required) </p> -->


                                     <div class="row">
                                        <div class="col-sm-3 text-nowrap">
                                                <h6>Billing <i class="fa fa-angle-right"></i> Fund Payment Information</h6>
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
	                                    </div>
                                        <div class="col-lg-2 col-md-2 new-layout-amount ">

                                            <div class="form-group">
                                                <label for="amt">Amount* </label>
                                                <input type="text" placeholder="Tk 0.00" class="form-control amount-fld" id="amt" name="amt" value="<?php echo $amount; ?>" required>
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
                                            <select name="cmbmode" id="cmbmode" class="form-control" required>
                                                <option value="">Select Payment Method</option>
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
                                    <!--div class="col-lg-3 col-md-6 col-sm-6">
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
                                                <option value="<?echo $tid; ?>" <?if ($org == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
    <?php }} ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div-->
      	                                
      	                                <!--div class="col-lg-3 col-md-6 col-sm-6"> 
                                            <div class="form-group">
                                                <label for="cmbcontype">Organization*</label>
                                               
                                                <div class="form-group styled-select">
                                                    <input list="cmborg1" name ="cmbassign2" value = "<?= $orgname ?>" autocomplete="Search From list"  class="dl-cmborg datalist" placeholder="Select Organization" required>
                                                    <datalist  id="cmborg1" name = "cmborg1" class="list-cmbassign form-control" >
        
                        <?php $qryitm="SELECT DISTINCT p.`supid` id, o.name FROM `po` p LEFT JOIN organization o ON o.id = p.`supid`"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
                                  {
                                      $tid= $rowitm["id"];  $nm=$rowitm["name"]; ?>
                                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
                        <?php  }}?>                    
                                                    </datalist> 
                                                    
                                                    <input type = "hidden" name = "cmbsupnm" id = "cmbsupnm" value = "<?= $org ?>">
                                                </div>
                                            </div>   
                                        </div-->
                                        
                                        <input type = "hidden" name = "fpid" id = "fpid" value = "<?= $fpid ?>">
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6"> 
                                        <div class="form-group">
                                            <label for="cmbcontype">Organization*</label>
                                            <div class="ds-divselect-wrapper cat-name">
                                            <div class="ds-input">
                        <input type="hidden" name="dest" value="">
                        <input type="hidden" name="org_id" id = "org_id" value = "<?= $org ?>">
                         <input type="text" name="org_name" autocomplete="off"  class="input-box form-control" value = "<?= $orgname ?>">
                    </div>
                                                <div class="list-wrapper">
                                                    <div class="ds-list">
                                
                                                        <ul class="input-ul" id="inpUl">
                                                            <li class="addnew">+ Add new</li>
                                
                                
                                                            <?php $qryitm = "SELECT id, concat(name, '(', contactno, ')') orgname FROM `organization` order by name";
                                    $resultitm                                = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                        $tid = $rowitm["id"];
                                        $nm  = $rowitm["orgname"]; ?>
                                                                        <li class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
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
                                            <label for="cmbcc"> Cost center*</label>
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

<style>
/*css for picture upload										   */
	
#ajax-img-up {
  clear: both;
  display: block;
  width: 100%;
  padding-left: 0;
}
#ajax-img-up li:first-child{
    display: none;
}
#ajax-img-up li {
  display: block;
  float: left;
  width: 120px;
  height: 120px;
  border: 1px solid #c0c0c0;
  position: relative;
  padding: 5px;
  margin: 3px;
  box-shadow: 0 0 3px #b5b3b3;
  border-radius: 5px;
}

#ajax-img-up li img, #ajax-img-up-main li img, #ajax-img-up-addnew li img {
  width: 100%;
  height: 100%;
  border-radius: 5px;
}

#ajax-img-up .radio-btn, #ajax-img-up-main .radio-btn, #ajax-img-up-addnew .radio-btn {
  margin: -5px;
  padding: 0;
  box-shadow: none;
  width: 120px;
  height: 120px;
}

#ajax-img-up li u{
    position: absolute;
    padding: 5px;
    background-color: red;
    color:#fff;
    right:5px;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
}

#ajax-img-up  .rotate{
  margin: auto;
  animation: mymove 5s infinite;
	cursor:default;
}

@keyframes mymove {
  100% {transform: rotate(360deg);}
}	
	
</style>
                                            <div class="col-sm-12 test-img">
												<div class="form-group">
													<label for="cmbcc"> Upload Voucher</label>
                                					  <ul id="ajax-img-up">
                                    				    <li style="visibility: hidden">

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
                            </div>
                            <!-- /#end of panel -->
                            <div class="button-bar">
                                <?php if ($mode == 2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update payment"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Payment"  id="add" >
                                <?php } ?>
                            <a href ="./paymentList.php?pg=1&mod=3">
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

			

		   // alert(imgToDeletePath);
		
if (confirm("Want to delete picture?") == true) {
    
	$.ajax({
              url: 'phpajax/delete_pv_ajx.php',
              type: 'post',
              data: {action: 'deletepic', pictodelete: imgToDeletePath},


              success: function(response){
                 if(response != 0){


					//alert(response);
					thisLi.remove();

                 }else{
					 //alert(response);
                   alert('Error deleting picture');
                }
              },
           });
	
  } 	

           



	});

	
	$("#ajax-img-up li").click(function(){
			
		if($(this).find(".fa-plus").hasClass("rotate")){
			
			return false;
			
		}		
		
	});
	
		
	
	
	var picid = 1;

    $("#upfiles").change(function(){
		


        var fd = new FormData();
        var files = $('#upfiles')[0].files;

		//alert(files.length);

        // Check file selected or not
        if(files.length > 0 ){
           fd.append('file',files[0]);
			
			$("#ajax-img-up .fa-plus").addClass("rotate");


           $.ajax({
              url: 'phpajax/upload_paymentvoucher_ajx.php',
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){

                  if(response == 2){
					  $("#ajax-img-up .fa-plus").removeClass("rotate");
                      alert('Invalid image file');
                  }else if(response != 0){
					  $("#ajax-img-up .fa-plus").removeClass("rotate");
					 //alert(response);
					 $('#ajax-img-up li:last').before('<li class="picbox"><u class="fa fa-close"></u><label class="custom-radio"><input type="hidden" name="voucherpic[]" value="'+response+'"><div class="imgdiv"><img src="'+response+'"><input type="hidden" name="imgfiles[]" value="'+response+'"></div><label></li>');

					 picid++;

					 //alert(response);
                 }else{
					 $("#ajax-img-up .fa-plus").removeClass("rotate");
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
					
					
					
					//addNewOrg();
					
				
					
					
					
				
					
					
					
					
					
					
					
					
					
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