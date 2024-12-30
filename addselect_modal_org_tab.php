<?php
session_start();
require "common/conn.php";

//ini_set('display_errors', 1);
//echo 'test';
//exit();
extract($_REQUEST);

?>



<?php
/*
?>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome4.0.7.css" rel="stylesheet">
<link href="css/fonts.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/style_extended.css" rel="stylesheet">
<link href="css/simple-sidebar.css" rel="stylesheet">

<?php
 */
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
		width: 100%;
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

<div class="row">
	<div class="tabs-nav">

		<ul class="col-lg-6 col-md-6 col-sm-6 active tabname">
			<li id="tabLi1" class="orgga">Organization</li>
		</ul>




		<ul class="col-lg-6 col-md-6 col-sm-6 tabname">
			<li id="tabLi2" class="indiv"> Individual </li>
		</ul>


	</div>

	<section class="tabs-content">
		<form id="org-type">
			<input type="hidden" name="orgtype" id="orgtype" value="1">
		</form>
		<div id="tab1">
			<form id="form-org">
				<div class="row">




					<div class="col-sm-12">
						<h5 class="sub-title">Required Informations<span class="redstar">*</span></h5>
					</div>

					<div class="col-sm-12">
						<div class="form-group">
							<label for="cnnm">Name<span class="redstar">*</span></label>
							<input type="text" class="form-control" id="cnnm" name="cnnm" value="<?php echo $name; ?>" required>
						</div>
					</div>
					<!-- Name -->




					<div class="col-lg-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="cmbindtype">Industry Type<span class="redstar">*</span></label>
							<div class="form-group styled-select">
								<select name="cmbindtype" id="cmbindtype" class="form-control">
									<option value="">Select Type</option>

									<?php
$qrycntp    = "SELECT `id`, `name` FROM `businessindustry`  order by name";
$resultcntp = $conn->query($qrycntp);if ($resultcntp->num_rows > 0) {while ($rowcntp = $resultcntp->fetch_assoc()) {
    $tid = $rowcntp["id"];
    $nm  = $rowcntp["name"];
    ?>
									<option value="<?php echo $tid; ?>" <?php if ($industry == $tid) {echo "selected";} ?>>
										<?php echo $nm; ?>
									</option>
									<?php }} ?>
								</select>
							</div>
						</div>
					</div>
					<!--Industry -->

					<!--div class="col-lg-3 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="empsz">Employee Size</label>
							<input type="text" class="form-control" id="empsz" name="empsz" value="<?php echo $employeesize; ?>">
						</div>
					</div-->
					<!--Employee size -->


					<div class=" col-sm-12">
						<div class="form-group">
							<label for="empsz">Company Address<span class="redstar">*</span></label>
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
							<input type="text" class="form-control" id="contactname" name="contactname" value="<?php echo $contactname; ?>">
						</div>
					</div>
					<!--Contact Name -->



					<div class="col-lg-6 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="web">Email*</label>
							<input type="text" class="form-control" id="contactemail" name="contactemail" value="<?php echo $contactemail; ?>">
						</div>
					</div>
					<!--Email -->


					<div class="col-lg-6 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="web">Phone<span class="redstar">*</span></label>
							<input type="text" class="form-control" id="contactphone" name="contactphone" maxlength="17" value="<?php echo $contactphone; ?>">
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


			</form>

		</div>


		<div id="tab2">
			<form id="form-indi">
				<div class="row">
					<h5 class=" mgl20px sub-title">Contact Informations<span class="redstar">*</span></h5>

					<div class="col-sm-12">
						<div class="form-group">
							<label for="">Name<span class="redstar">*</span></label>
							<input type="text" class="form-control" id="indv_name" placeholder="Name" name="contname">
						</div>
					</div>


					<div class="col-lg-6 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="">Email<span class="redstar">*</span></label>
							<input type="text" class="form-control" id="contemail" placeholder="Email" name="contemail">
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="">Phone<span class="redstar">*</span></label>
							<input type="text" class="form-control" id="contphone" maxlength="17" placeholder="Phone" name="contphone">
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
								<select name="cmbdsg" id="cmbdsg" class="form-control" required>
									<option value="">Select Gender</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>

								</select>
							</div>
						</div>
					</div>
					<!-- Gender -->
					<div class=" col-sm-12">
						<div class="form-group">
							<label for="ind_address">Address<span class="redstar">*</span></label>
							<textarea rows="2" class="form-control" id="ind_address" name="ind_address"><?php echo $ind_address; ?></textarea>
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


									<option value="<?php echo $tid; ?>" <?php if ($district == $tid) {echo "selected";} ?>>
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
							<input type="text" class="form-control" id="zip" name="zip" value="<?php echo $zip; ?>">
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

									<option value="<?php echo $tid; ?>" <?php if ($country == $tid) {echo "selected";} ?>>
										<?php echo $nm; ?>
									</option>
									<?php }} ?>
								</select>
							</div>
						</div>
					</div>


					<!--div class="col-sm-12">
						<div class="form-group">
							<label for="indv_organization">Organization</label>
							<input type="text" class="form-control" id="indv_organization" placeholder="Organization" name="indv_organization">
						</div>
					</div-->

					<!--div class="col-lg-6 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="cmbdsg">Designation *</label>
							<div class="form-group styled-select">
								<select name="cmbdsg" id="cmbdsg" class="form-control" required>
									<option value="">Select Designation</option>
									<?php

$qrydsg    = "SELECT `id`, `name` FROM `crm_designation` order by name";
$resultdsg = $conn->query($qrydsg);

if ($resultdsg->num_rows > 0) {
    while ($rowdsg = $resultdsg->fetch_assoc()) {
        $did = $rowdsg["id"];
        $dnm = $rowdsg["name"];
        ?>
									<option value="<?php echo $did; ?>" <?php if ($designation == $did) {echo "selected";} ?>>
										<?php echo $dnm; ?>
									</option>

									<?php }} ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="cmbdpt">Department </label>
							<div class="form-group styled-select">
								<select name="cmbdpt" id="cmbdpt" class="form-control">
									<option value="">Select Department</option>
									<?php $qrydpt = "SELECT `id`, `name` FROM `crm_department` order by name";

$resultdpt = $conn->query($qrydpt);

if ($resultdpt->num_rows > 0) {
    while ($rowdpt = $resultdpt->fetch_assoc()) {
        $tid = $rowdpt["id"];
        $nm  = $rowdpt["name"];
        ?>

											<option value="<?php echo $tid; ?>" <?php if ($department == $tid) {echo "selected";} ?>>
												<?php echo $nm; ?>
											</option>

									<?php }} ?>
								</select>
							</div>
						</div>
					</div-->




				</div>
			</form>
		</div>
	</section>



	<script>
		$( function () {

			$( '#tab1' ).attr( 'style', 'display: block' );
			$( '#tab2' ).attr( 'style', 'display: none' );

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