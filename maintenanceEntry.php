<?php

//echo "dd";

//print_r($_REQUEST);

//exit();


require "common/conn.php";
include_once( 'rak_framework/fetch.php' );
session_start();
$usr = $_SESSION[ "user" ];
//echo $usr;die;

ini_set('display_errors', 0);


if ( $usr == '' ) {
	header( "Location: " . $hostpath . "/hr.php" );
} else {




	$currSection = 'maintenance';
	$currPage = basename($_SERVER['PHP_SELF']);

	?>
	<!doctype html>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<?php

	include_once 'common_header.php';
	?>

	<body class="form soitem order-form">
		
		<?php


		include_once 'common_top_body.php';



		?>

		<div id="wrapper">
			<!-- Sidebar -->
			<div id="sidebar-wrapper" class="mCustomScrollbar">
				<div class="section">
					<i class="fa fa-group  icon"></i>
					<span>Quotation</span>
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
								<form method="post" action="common/maintenance_post.php" id="maintenanceForm" enctype="multipart/form-data">
									<!--form method="post" action="" id="form1" enctype="multipart/form-data" -->
									<!-- START PLACING YOUR CONTENT HERE -->
									<div class="panel panel-info">




										<div class="panel-body panel-body-padding">
											<span class="alertmsg"></span>







											<div class="row form-header">

												<div class="col-lg-6 col-md-6 col-sm-6">
													<h6>Maintenance <i class="fa fa-angle-right"></i> Add  Maintenance Service Order </h6>
												</div>

												<div class="col-lg-6 col-md-6 col-sm-6">
													<h6><span class="note"> (Field Marked <span class="redstar">*</span>
 are required)</span></h6>
												</div>


											</div>




											<div class="row">
												<div class="col-sm-12">
											

		
												</div>
												<div class="row no-mg">



												</div>




												<div class="col-lg-4 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbcontype">Maintenance Reason<span class="redstar">*</span></label>
														<div class="form-group styled-select">
															
															<select name="cmbsupnm" id="cmbsupnm" class="cmd-child form-control" required>
																<option value="">Repaire</option>
															</select>
														</div>
													</div>
												</div>

												<div class="col-lg-4 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbcontype">DO Number<span class="redstar">*</span></label>
														<input type="text" class="form-control dono" name="dono" id="dono" value="<?php echo $dono; ?>" required>	
													</div>
												</div>
												
												
												<div class="col-lg-4 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbcontype">Date<span class="redstar">*</span></label>
														<input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" required>	
													</div>
												</div>												
												

												<div class="col-lg-4 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbsupnm">Customer<span class="redstar">*</span></label>
														<div class="form-group styled-select">
															<select name="cmbsupnm" id="cmbsupnm" class="cmd-child form-control" required>
																<option value="">Select Name</option>
													
															</select>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-6">
													<label for="po_dt">Customer ID<span class="redstar">*</span></label>
													<div class="input-group">
														<input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" required>
														<div class="input-group-addon">
															<span class="glyphicon glyphicon-th"></span>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbcontype">Phone<span class="redstar">*</span></label>
														<input type="text" class="form-control phone" name="phone" id="phone" value="<?php echo $phone; ?>" required>	
													</div>
												</div>	


												<br>
												
												
													<div class="col-sm-12">
														<h4>Products </h4>
														
													</div>												
												<div class="col-sm-12">
													
													<hr class="form-hr" style="margin-bottom: 10px">
													<table width="100%" cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td width="120"><img src="assets/images/products/300_300/00000169.jpg" height="100"></td>
															<td><b>Item:</b> AC281 – Chair</td>
															<td width="50">
															
																<div class="icheck-primary">
																	<div class="icheckbox_square-blue"><input type="checkbox" name="create_1" value="1" id="item1" ></div>
																	<label for="create1" class=""> &nbsp;</label>
																</div>
																	

																
															
															</td>
														</tr>
													</table>
													<hr class="form-hr">
													<table width="100%" cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td width="120"><img src="assets/images/products/300_300/00000169.jpg" height="100"></td>
															<td><b>Item:</b> AC281 – Chair</td>
															<td width="50">
															
																<div class="icheck-primary">
																	<div class="icheckbox_square-blue"><input type="checkbox" name="item[]" value="1" id="item2"></div>
																	<label for="create1" class="">&nbsp;</label>
																</div>
															</td>
														</tr>
													</table>
													<hr class="form-hr">													
													
												
												</div>
													




												<div class="col-lg-12 col-md-12 col-sm-12">

													<div class="form-group">

														<label for="details">Issue Detail:</label>

														<textarea class="form-control" id="details" name="details" rows="4">
															<?php echo $details; ?>
														</textarea>

													</div>

												</div>
												
												
												
												<div class="col-lg-12 col-md-12 col-sm-12">
													<div class="form-group">
														<div class="icheckbox_square-blue"><input type="checkbox" name="item[]" value="1" id="physicalInspection"></div>
														<label for="physicalInspection"> &nbsp;Required Physical Inspection?</label>
													</div>
												</div>
												
												<div class="col-xs-3">
													<div class="form-group">
														<label for="physicalInspection">Clients Preferred Inspection Date and Time: </label>
														<input type="text" class="form-control datepicker" name="date" id="date" value="" placeholder="Date" required><br>
														<input type="text" class="form-control timepicker-hour" name="time" id="time" value="" placeholder="Time" required>
													</div>
												</div>
												
												<div class="col-xs-9">
													<div class="form-group">
														<label for="physicalInspection">Customer Address:</label>
														<textarea class="form-control" id="note" name="note" rows="3">
															<?php echo $note; ?>
														</textarea>
													</div>
												</div>													
												

												<div class="col-lg-12 col-md-12 col-sm-12">

													<div class="form-group">

														<label for="details">Remarks</label>

														<textarea class="form-control" id="note" name="note" rows="2">
															<?php echo $note; ?>
														</textarea>

													</div>

												</div>


													<div class="col-sm-12">
														<h4>Charges </h4>
														<hr class="form-hr">
													</div>												
												
												
												<div class="col-lg-2 col-md-6 col-sm-6">
													<label for="po_dt">Service Fee</label>
													<div class="input-group">
														<input type="text" class="form-control " name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" required>
													</div>
												</div>												
												
												
												<div class="col-lg-1 col-md-6 col-sm-6">
													<label for="po_dt">TDS</label>
													<div class="input-group">
														<input type="text" class="form-control " name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" required>
													</div>
												</div>	
												
												
												<div class="col-lg-1 col-md-6 col-sm-6">
													<label for="po_dt">VDS</label>
													<div class="input-group">
														<input type="text" class="form-control " name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" required>
													</div>
												</div>	
												
												
												<div class="col-lg-2 col-md-6 col-sm-6">
													<label for="po_dt">Total</label>
													<div class="input-group">
														<input type="text" class="form-control " name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" required>
													</div>
													
												</div>													
												<div class="col-sm-12">
													<hr class="form-hr">
												</div>	
												
												<div class="col-sm-12">
													<input type="hidden" name="mode" value="<?=$mode?>">
													<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
													


													<input class="btn btn-lg btn-default top" type="submit" name="postaction" value="Submit" id="save">
													<input class="btn btn-lg btn-warning top" type="button" name="postaction" value="Cancel" id="cancel" onClick="location.href = 'maintenanceList.php?pg=1&mod=3'">

												</div>

											</div>
									










										



										</div>
										<!-- end panel body -->
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










			$( document ).on( "click", ".remove-po", function ( e ) {
				var root = $( this ).closest( ".toClone" );
				enableDisableBookBtn();
			} );



			//check qty for backorder

			$( document ).on( "change", ".qty-chkstk", function () {

				var qtroot = $( this ).closest( ".toClone" );
				var stk = qtroot.find( ".itemName" ).data( 'stk' );

				var qty = $( this ).val();
				//alert(stk);

				//console.log("stk:"+stk+" | qty: "+qty);
				if ( stk < qty ) {
					backorderCheck( stk, qtroot );
				}
				enableDisableBookBtn();
			} );

			var found;

			function enableDisableBookBtn() {

				found = 0;
				const elements = document.querySelectorAll( '.itemName' );
				Array.from( elements ).forEach( ( element, index ) => {

					// conditional logic here.. access element

					mystk = element.getAttribute( "data-stk" );
					if ( mystk < 0 ) {
						found++;
					}

				} );
				if ( found > 0 ) {
					$( "#book" ).prop( 'disabled', true );
				} else {
					$( "#book" ).prop( 'disabled', false );
				}
			}



			function backorderCheck( stock, root ) {

				var isAlert = root.find( ".isBOAlert" ).val();

				console.log( "stk:" + stock + " | isAlert: " + isAlert );
				if ( isAlert != 1 ) {
					setTimeout( function () {





						swal( {
								title: "Do you want to allow Back Order?",
								text: "This item is not available in stock",
								icon: "warning",
								buttons: true,
								dangerMode: true,
								buttons: [ 'Cancel', 'Allow Back Order' ],
							} )
							.then( ( willDelete ) => {
								if ( willDelete ) {

								} else {
									setTimeout( function () {
										//$(this).val("Select Item");

										root.find( ".dl-itemName" ).val( "" );
										root.find( ".dl-itemName" ).change();
										root.find( ".quantity_otc" ).val( "" );
										root.find( ".quantity_otc" ).change();

										root.find( ".remove-po" ).trigger( "click" );

									}, 200 );

									return false;
								}
							} );

						//return false;

						//put a flag after itemName field once alert is shown
						root.find( ".dl-itemName" ).after( '<input type="hidden" class="isBOAlert" value="1">' );

						/* end backorder alert  */




					}, 200 );
				}
			}



			/* end autofill combo  */
		</script>






		

		<script>
			//COPIER

			$( document ).ready( function () {
				var max_fields = 500; //maximum input boxes allowed
				var wrapper = $( ".color-block" ); //Fields wrapper
				var add_button = $( ".link-add-order" ); //Add button ID

				var x = 1; //initlal text box count
				$( add_button ).click( function ( e ) { //on add input button click
					e.preventDefault();

					if ( x < max_fields ) { //max input box allowed
						x++;
						//$(wrapper).
						$( ".po-product-wrapper .toClone:last-child" ).clone().appendTo( wrapper );

						$( ".po-product-wrapper .toClone:last-child input" ).val( "" );


						if ( x == 2 ) {
							$( ".po-product-wrapper .toClone:last-child" ).append( '<div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>' );

						}

					}



					setTimeout( function () {

						//check already selected item and disable them.
						//var valuesArray = []; // Array to store the values

						$( '.itemName' ).each( function () {
							var inputValue = $( this ).val();
							//valuesArray.push(inputValue);

							//  $('.po-product-wrapper .toClone:last-child .option-'+inputValue).prop('disabled', 'disabled');
							//$('.withlebel .toClone:last-child .option-'+inputValue).prop('disabled', true);
							//$('.po-product-wrapper .toClone:last-child .option-'+inputValue).remove();
							$( document ).on( 'click', '.po-product-wrapper .toClone:last-child', function () {
								$( this ).find( ".option-" + inputValue ).remove();
							} );
						} );


					}, 200 );




				} );

				$( wrapper ).on( "click", ".remove-order", function ( e ) { //user click on remove text
					e.preventDefault();
					$( this ).closest( ".toClone" ).remove();
					OrderTotal();
					x--;

				} )
			} );
		</script>




		




	</body>

	</html>
	<?php } ?>