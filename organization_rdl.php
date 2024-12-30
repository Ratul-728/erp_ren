<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    $res = $_GET['res'];
    $msg = $_GET['msg'];
    $oid = $_GET['id'];

    if ($res == 4) { // edit;
        $qry = " select `id`, `orgcode`, `name`, `type`, `contactperson`, `contactno`, `industry`,(select name from businessindustry where id=industry) indtp, `employeesize`, `email`, `website`, `address`, `area`, `street`, `district`, `state`, `zip`, `country`, `operationstatus`, `bsnsvalue`, `details`, `billingpoc`, `techpoc`, `salesperson`, `balance`, `reserve_balance`, `note`, `vendor`, `makedt` FROM `organization` where id= " . $oid;
         //echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $orid            = $row["id"];
                    $orgcode         = $row["orgcode"];
                    $name            = $row["name"];
                    $type            = $row["type"];
                    $contactperson   = $row["contactperson"];
                    $phone           = $row["contactno"];
                    $industry        = $row["industry"];
                    $employeesize    = $row["employeesize"];
                    $email           = $row["email"];
                    $website         = $row["website"];
                    $address         = $row["address"];
                    $area            = $row["area"];
                    $street          = $row["street"];
                    $district        = $row["district"];
                    $state           = $row["state"];
                    $zip             = $row["zip"];
                    $country         = $row["country"];
                    $operationstatus = $row["operationstatus"];
                    $bsnsvalue       = $row["bsnsvalue"];
                    $details         = $row["details"];
                    $billingpoc      = $row["billingpoc"];
                    $techpoc         = $row["techpoc"];
                    $salesperson     = $row["salesperson"];
                    $note            = $row["note"];
                    $vendor          = $row["vendor"];
                      $indtp        = $row["indtp"];
                    
                    
                    $contquery="SELECT  `id`,`contacttype`, `name`, `organization`, DATE_FORMAT(`dob`,'%e/%c/%Y') dob, `phone`, `email`, `gender`, `contactcode`,`area`, `district`, `zip`,`country`,`lead_state`, `makeby`, `makedt` FROM `contact`  where `organization`='$orgcode'";
                    //echo $contquery;die;
                    $contresult = $conn->query($contquery);
                    if ($contresult->num_rows > 0) 
                    {
                        while ($cntrow = $contresult->fetch_assoc()) 
                        {
                            $contid=$cntrow["id"];
                            $dob=$cntrow["dob"];
                            $contnm=$cntrow["name"];
                            $gender=$cntrow["gender"];
                            $indaddr=$cntrow["area"];
                            $inddist=$cntrow["district"];
                            $indzip=$cntrow["zip"];
                            $indcountry=$cntrow["country"];
                        }
                    }
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    } else {
        $orid            = '';
        $name            = '';
        $type            = '1';
        $contactperson   = '';
        $phone           = '';
        $industry        = '';
        $employeesize    = '0';
        $email           = '';
        $website         = '';
        $address         = '';
        $area            = '';
        $street          = '';
        $district        = '';
        $state           = '';
        $zip             = '';
        $country         = '';
        $operationstatus = '';
        $bsnsvalue       = '0';
        $details         = '';
        $salesperson     = '';
        $note            = '';
        $vendor          = '';

        $mode = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'organization';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
    <style>
	.inc-pos-row {
		padding: 0 10px;
		margin: 0;
	}

	.tabs-nav ul {
		padding: 10px;
		text-align: center;
	}

	.tabs-nav li {
		list-style: none;
		width: 50%;
		text-align: center;
	}

	.tabname {
		color: black;
		background-color: white;
	}

	.active {
		background-color: #00abe3 !important;

	}

	.active li{
		color:#fff;
	}

	.tabs-nav div {
		margin: 0;
		padding: 0;
	}



		.tabs-nav > ul li{
			cursor: pointer;

		}

		.tabs-nav > ul{
			background-color: #eeeded;
		}
		.tabs-nav{
			background-color: #00ABE3!important;
			height: 45px;
		}


</style>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>

<body class="form org">
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Customer Details</span>
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
                        <form method="post" action="common/addorganization_rdl.php"  id="form1" enctype="multipart/form-data">  <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
<!--      			                <div class="panel-heading"><h1>Organization Information</h1></div>-->
				                <div class="panel-body panel-body-padding">
                                    <span class="alertmsg"></span>
									<div class="row form-header">
	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>Salse <i class="fa fa-angle-right"></i> <a href="javascript:history.back();">Customers</a> <i class="fa fa-angle-right"></i> Add Customer</h6>
      		                            </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> <!--(Field Marked * are required)--></span></h6>
      		                            </div>
                                    </div>
    
                                    <!-- <p>(Field Marked * are required) </p> -->
                                    <div class="row">
                                    	<div class="tabs-nav col-sm-6">
                                    		<ul class="col-lg-6 col-md-6 col-sm-6 <?php if($type=='1'){?> active <?php } ?> tabname">
                                    			<li id="tabLi1" class="orgga">Organization</li>
                                    		</ul>
                                    		<ul class="col-lg-6 col-md-6 col-sm-6 <?php if($type=='2'){?> active <?php } ?> tabname">
                                    			<li id="tabLi2" class="indiv"> Individual </li>
                                    		</ul>
                                		</div>
                                        
      		                            <div class="col-sm-12">  
	                                        <!-- <h4></h4>
	                                        <hr class="form-hr"> -->
		                                    <input type="hidden"  name="orid" id="orid" value="<?php echo $orid; ?>">
		                                    <input type="hidden"  name="orcd" id="orcd" value="<?php echo $orgcode; ?>">
		                                     <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
		                                     <input type="hidden"  name="orgtp" id="orgtp" value="<?php echo $type; ?>">
		                                     
	                                    </div> <!-- id -->
                                        <div id="tab1" class="col-sm-6">
                            				<div class="row">
                            					<div class="col-sm-12">
                            						<h5 class="sub-title">Required Informations<span class="redstar">*</span></h5>
                            					</div>
                            					<div class="col-sm-12">
                            						<div class="form-group">
                            							<label for="cnnm">Name<span class="redstar">*</span></label>
                            							<input type="text" class="form-control" id="cnnm" name="cnnm" value="<?php echo $name; ?>" >
                            						</div>
                            					</div>
                            					<!-- Name -->
                            					
                            					
                            					
                                <div class="col-lg-12 col-md-12 col-sm-12"> 
                                        <div class="form-group">
                                            <label for="cmbcontype">Industry Type  <span class="redstar">*</span></label>
                                            <div class="ds-divselect-wrapper cat-name">
                                            <div class="ds-input">
                                                <input type="hidden" name="dest" value="">
                                                <input type="hidden" name="cat_id" id = "cat_id" value = "<?= $ItemCat ?>">
                                                <input type="text" name="cmbindtype"   autocomplete="off" placeholder="Select Industry Type"  class="input-box form-control" value = "<?= $indtp ?>">
                                            </div>
                                                <div class="list-wrapper">
                                                    <div class="ds-list" style="display: none;">
                                
                                                        <ul class="input-ul" tabindex="0" id="inpUl">
                                                            <li tabindex="1" class="addnew">+ Add new</li>
                                
                                
                                                            <?php $qryitm = "SELECT * FROM `businessindustry` order by name";
                                    $resultitm                                = $conn->query($qryitm);if ($resultitm->num_rows > 0) {
										$tabindex = 2;
										while ($rowitm = $resultitm->fetch_assoc()) {
                                        $tid = $rowitm["id"];
                                        $nm  = $rowitm["name"]; 
															
															?>
                                                                        <li  tabindex="<?=$tabindex?>" class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
                                                        <?php
										$tabindex++;						
										}} 
															?>
                                                        </ul>
                                                    </div>
                                                    <div class="ds-add-list" style="display: none;">

                                                        <div class="row">
                                                            <div class="col-lg-12 add-more-col">
                                                                <h3>Add new Type</h3>
                                                                <hr>
                                                                <label for="">Name</label> <br>
                                                                <input type="text"  name="" autocomplete="off" class="Name addinpBox form-control" id="">
                                                                
                                
                                                            </div>
                                                            <div class="col-lg-12">
                                                            	
                                                                 
																
																<button type = "button" class="btn btn-sm btn-default  ds-add-list-btn pull-right" style="margin-left: 5px;">Save</button>
																<button type = "button" class="btn btn-sm btn-default  ds-cancel-list-btn  pull-right">Cancel</button>
                                                            </div>
                                                        </div>
                                
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                    </div>
                            					
                            					<!--Industry -->
                            					<div class=" col-sm-12">
                            						<div class="form-group">
                            							<label for="empsz"> Address<span class="redstar">*</span></label>
                            							<textarea rows="2" class="form-control" id="address" name="address"><?php echo $address; ?></textarea>
                            						</div>
                            					</div>
                            					<!--Company Address -->
                            					<div class="col-sm-12">
                            						<h5 class="sub-title">Additional Information</h5>
                            					</div>
                            					<div class="col-sm-12 col-md-12 col-lg-12">
                            						<div class="form-group">
                            							<label for="bv">Contact Name<span class="redstar">*</span></label>
                            							<input type="text" class="form-control" id="contactname" name="contactname" value="<?php echo $contactperson; ?>">
                            						</div>
                            					</div>
                            					<!--Contact Name -->
                            					<div class="col-lg-6 col-md-6 col-sm-6">
                            						<div class="form-group">
                            							<label for="web">Email*</label>
                            							<input type="text" class="form-control" id="contactemail" name="contactemail" value="<?php echo $email; ?>">
                            						</div>
                            					</div>
                            					<!--Email -->
                            					<div class="col-lg-6 col-md-6 col-sm-6">
                            						<div class="form-group">
                            							<label for="web">Phone<span class="redstar">*</span></label>
                            							<input type="text" class="form-control" id="contactphone" name="contactphone" maxlength="100" value="<?php echo $phone; ?>">
                            						</div>
                            					</div>
                            					<!--Phone -->
                            					<div class=" col-sm-12">
                            						<div class="form-group">
                            							<label for="note">Note</label>
                            							<textarea rows="2" class="form-control" id="note" name="note"><?php echo $note; ?></textarea>
                            						</div>
                            					</div>
                            					<!--note -->
                            				</div>
                                		</div>
	                                    
	                                    <div id="tab2" class="col-sm-6">
                            				<div class="row">
                            					<h5 class=" mgl20px sub-title">Contact Informations<span class="redstar">*</span></h5>
                            					<div class="col-sm-12">
                            						<div class="form-group">
                            							<label for="">Name<span class="redstar">*</span></label>
                            							<input type="text" class="form-control" id="indv_name" placeholder="Name" name="contname" value="<?php echo $contnm; ?>">
                            						</div>
                            					</div>
                            					<div class="col-lg-6 col-md-6 col-sm-6">
                            						<div class="form-group">
                            							<label for="">Email<span class="redstar">*</span></label>
                            							<input type="text" class="form-control" id="contemail" placeholder="Email" name="contemail" value="<?php echo $email; ?>">
                            						</div>
                            					</div>
                            					<div class="col-lg-6 col-md-6 col-sm-6">
                            						<div class="form-group">
                            							<label for="">Phone<span class="redstar">*</span></label>
                            							<input type="text" class="form-control" id="contphone" maxlength="100" placeholder="Phone" name="contphone" value="<?php echo $phone; ?>">
                            						</div>
                            					</div>
                            
                            					<div class="col-lg-4 col-md-6 col-sm-6">
                            						<label for="dob">Date of Birth</label>
                            						<div class="input-group">
                            							<input type="text" class="form-control datepicker datetimepicker" id="dob" name="dob" value="<?php echo $dob; ?>">
                            							<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span>
                            							</div>
                            						</div>
                            					</div>
                            					<div class="col-lg-4 col-md-6 col-sm-6">
                            						<div class="form-group">
                            							<label for="cmbdsg">Gender</label>
                            							<div class="form-group styled-select">
                            								<select name="cmbdsg" id="cmbdsg" class="form-control" >
                            									<option value="">Select Gender</option>
                            									<option value="Male" <?php if ($gender == "Male") {echo "selected";} ?>>Male</option>
                            									<option value="Female" <?php if ($gender == "Female") {echo "selected";} ?>>Female</option>
                            
                            								</select>
                            							</div>
                            						</div>
                            					</div>
                            					<!-- Gender -->
                            					<div class=" col-sm-12">
                            						<div class="form-group">
                            							<label for="ind_address">Address<span class="redstar">*</span></label>
                            							<textarea rows="2" class="form-control" id="ind_address" name="ind_address"><?php echo $address; ?></textarea>
                            						</div>
                            					</div>
                            					<!-- Address -->
                            					
                            					<div class="col-lg-5 col-md-5 col-sm-5">
                            						<div class="form-group">
                            							<label for="district">District</label>
                            							<div class="form-group styled-select">
                            								<select name="district" id="district" class="form-control">
                            									<option value="">Select District</option>
                            									<?php $qrydis = "SELECT `id`, `name` FROM `district` order by name";
                            
                            $resultdis = $conn->query($qrydis);
                            if ($resultdis->num_rows > 0) {
                                while ($rowdis = $resultdis->fetch_assoc()) {
                            
                                    $tid = $rowdis["id"];
                                    $nm  = $rowdis["name"];
                            
                                    ?>
                            
                            
                            									<option value="<?php echo $tid; ?>" <?php if ($inddist == $tid) {echo "selected";} ?>>
                            										<?php echo $nm; ?>
                            									</option>
                            									<?php }} ?>
                            
                            
                            								</select>
                            							</div>
                            						</div>
                            					</div>
                            					<div class="col-lg-2 col-md-2 col-sm-2">
                            						<div class="form-group">
                            							<label for="zip">ZIP Code</label>
                            							<input type="text" class="form-control" id="zip" name="zip" value="<?php echo $indzip; ?>">
                            						</div>
                            					</div>
                            					<div class="col-lg-5 col-md-5 col-sm-5">
                            						<div class="form-group">
                            							<label for="country">Country</label>
                            							<div class="form-group styled-select">
                            								<select name="country" id="country" class="form-control">
                            									<option value="">Select Country</option>
                            									<?php
                            
                            $qrycon    = "SELECT `id`, `name` FROM `country` order by name";
                            $resultcon = $conn->query($qrycon);
                            if ($resultcon->num_rows > 0) {
                                while ($rowcon = $resultcon->fetch_assoc()) {
                                    $tid = $rowcon["id"];
                                    $nm  = $rowcon["name"];
                            
                                    ?>
                            
                            									<option value="<?php echo $tid; ?>" <?php if ($indcountry == $tid) {echo "selected";} ?>>
                            										<?php echo $nm; ?>
                            									</option>
                            									<?php }} ?>
                            								</select>
                            							</div>
                            						</div>
                            					</div>
                            				</div>
                            		</div>
	                                    
                                       <!-- notepad-->
                                       
                                    </div>
                                </div>
                            </div>
                            <!-- /#end of panel -->
                            <div class="button-bar">
                                <?php if ($mode == 2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Customer"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Customer"  id="add" >
                                <?php } ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="convert" value="Convert"  id="convert" >
                                <?php if($type==1){ ?>
                                    <a href = "./organization.php?pg=1&res=4&mod=3&id=<?php echo $orid; ?>">
                                        <input class="btn btn-lg btn-default" type="button" name="details" value="Details"  id="details" >
                                    </a>
                                <?php }?>
                            <a href = "./organizationList.php?pg=1&mod=3">
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

<?php

    if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }

    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>
    
   

<script>
		$( function () {
            <?php if ($type=='1'){ ?>
			$( '#tab1' ).attr( 'style', 'display: block' );
			$( '#tab2' ).attr( 'style', 'display: none' );
            <?php }?>
            <?php if ($type=='2'){ ?>
			$( '#tab1' ).attr( 'style', 'display: none' );
			$( '#tab2' ).attr( 'style', 'display: block' );
            <?php }?>
            
			$( '.tabs-nav ul' ).click( function () {

				// Display active tab
				let currentTab = $( this ).find( 'li' ).attr( 'id' );
                

				if ( currentTab === 'tabLi1' ) {
					$( '#tab1' ).attr( 'style', 'display: block' );

					$( '#tabLi1').closest( 'ul' ).addClass( 'active' );
					$( '#tabLi2').closest( 'ul' ).removeClass( 'active' );

					$( '#tab2' ).attr( 'style', 'display: none' );
					$( '#orgtype' ).val( 1 );

				} else if ( currentTab === 'tabLi2' ) {
					$( '#tabLi2').closest( 'ul' ).addClass( 'active' );
					$( '#tabLi1').closest( 'ul' ).removeClass( 'active' );

					$( '#tab2' ).attr( 'style', 'display: block' );
					$( '#tab1' ).attr( 'style', 'display: none' );
					$( '#orgtype' ).val( 0 );
				}

				return false;
			} );



			//disable tab on key press

			$(document).on("keyup","#cnnm", function(){
				let nmlngt = $("#cnnm").val().length;
				//console.log("org active:" +nmlngt);
				if(nmlngt>0){
					$(".indiv").attr("id","");
					$(".indiv").attr("style","pointer-events: none;color:#c1bebe;");

				}else{
					$(".indiv").attr("id","tabLi2");
					$(".indiv").attr("style","pointer-events: auto;color:auto;");
				}


			});

			$(document).on("keyup","#indv_name", function(){
				let nmlngt = $("#indv_name").val().length;
				//console.log("org active:" +nmlngt);
				if(nmlngt>0){
					$(".orgga").attr("id","");
					$(".orgga").attr("style","pointer-events: none;color:#c1bebe;");

				}else{
					$(".orgga").attr("id","tabLi1");
					$(".orgga").attr("style","pointer-events: auto;color:auto;");
				}


			});

                $('.datetimepicker').datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "DD/MM/YYYY",
					//format: 'LT',
					//keepOpen:true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });

		} );
	</script>
	
	
	
	
	
<script>


$(document).ready(function(){



             //Input Click
					
  
  $('.input-box').focus(function(){
    $(this).select();
  });
  
  $('.input-box').on("focus click keyup", function(){
                 //console.log("d1");
                 $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
                // $(this).find('.ds-add-list').attr('style','display:none');
            });

            //Option's value shows on input box

            //$('.input-ul li').click(function(){
  					$('.input-ul').on("click","li", function(e){
               // console.log(this);


                if(!$(this).hasClass("addnew")){


                        let litxt= $(this).text();
                        let lival= $(this).val();

                        $("#cat_id").val(lival);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box').val(litxt);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value',litxt);
                        $(this).closest('.ds-list').attr('style','display:none');  
                                  
                }

         

            });

			
			function addNew(e){
                $(e).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
                $(e).closest('.ds-list').attr('style','display:none');				
			}
			
            // New input box display

            $('.input-ul .addnew').click(function(){
				addNew(this);
                //$(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
                //$(this).closest('.ds-list').attr('style','display:none');
            });
			
			$(".ds-cancel-list-btn").click(function(){ 
				$(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:none');
			 });

            // New-Input box's value display on old-input box

            $('.ds-add-list-btn').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
				if(x.length>0){
                $(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value', x);
				$(this).closest('.ds-divselect-wrapper').find('.input-box').val(x);
                $(this).closest('.ds-add-list').attr('style','display:none');
                //$(this).closest('.ds-add-list').find('.addinpBox').val('');
                console.log($(this).closest('.ds-add-list').find('.addinpBox').val(""));
                // alert(x);
                // }
                action(x);
                function action(x){
                    
                    //alert('dd'+x);
                    $.ajax({
                        url:"phpajax/divSelectIndustry.php",
                        method:"POST",
                        data:{newItem: x},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#cat_id").val(res.id);
                                $('.display-msg').html(res.name);
                                $('.input-box').attr('value',res.name);
								$("#inpUl").append("<li class='pp1' value = '"+res.id+"'>"+res.name+"</li>");
                                

                            }
                    });
	             }
			}else{ 
				alert('Please enter a category name');
			}

            });


			//hide ds-list ds-add-list on clicking anywhere on the document;

            $(document).mouseup(function (e) {
				
                if ($(e.target).closest(".ds-list").length === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length  === 0) {
                    $(".ds-add-list").hide();
                }
            });




            $('.input-box').on("keyup", function(e) {
			   
			    		var searchKey = $(this).val().toLowerCase();
              
              
             // if(searchKey.length>0){
                
                $("#inpUl li").filter(function() {
                	$(this).toggle($(this).text().toLowerCase().indexOf(searchKey) > -1);
                  
                  		if(e.keyCode == 40){
                        $('#inpUl li').removeClass('active');
                        $(this).next().focus().addClass('active');
                        return false;
                      } 
                });
                
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			   			 $(this).closest('.ds-divselect-wrapper').find('.input-ul li').click(function(){
                //$(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")").click(function(){	
			    

					// console.log(this)
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
                        $(this).closest('.ds-divselect-wrapper').find(".input-box").val(x);
                        $(this).closest('.ds-list').attr('style','display:none');
                      
                      
                     
                    }
					
                })
           // }
                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){

                    $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                    $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
                });
				
				
					 if (e.keyCode == 40){  
					 //alert("Enter CLicked");
					 $('#inpUl li').first().focus().addClass('active');
				 }
              
	            

			});

	$('#inpUl').on('focus', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){ 
      
      $this = $(this);
      $('#inpUl li').removeClass('active');
			$this.addClass('active');
			$this.closest('#inpUl').scrollTop($this.index() * $this.outerHeight());
    }
    
    }).on('keydown', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){
      $('#inpUl li').removeClass('active');
		$this = $(this);
		if(e.keyCode == 40){
      $('#inpUl li').removeClass('active');
			$this.next().focus().addClass('active');
			return false;
		} else if (e.keyCode == 38){        
			$this.prev().focus().addClass('active');
			return false;
		}
    
  }
	}).find('li').first().focus();	

  
  			$('#inpUl').on("keyup","li", function(e) {
				if (e.keyCode == 13){
          var txt = $(this).text();
					//alert(txt);
          if(!$(this).hasClass("addnew")){

          
          var tval= $(this).val();

          $("#cat_id").val(tval);              
          $('.input-box').val(txt);
          $('.input-box').focus();
          $('.ds-list').attr('style','display:none');
          }
				}
			});	
  
  

	
			
}); //$(document).ready(function(){


</script>
	
</body>
</html>
<?php } ?>