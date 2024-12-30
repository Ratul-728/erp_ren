<?php
require "../common/conn.php";

session_start();

$user = $_SESSION["user"];

$doid = $_POST["doid"];

$qryUpperInfo = "SELECT deo.id did, org.name orgname, org.id orgid, org.contactno, org.street, d.name district, s.name state, org.zip,DATE_FORMAT( q.orderdate,'%d/%b/%Y') orderdate 
                FROM `delivery_order` deo LEFT JOIN quotation q ON deo.order_id=q.socode LEFT JOIN organization org ON q.organization=org.id 
                LEFT JOIN district d ON org.district=d.id LEFT JOIN state s ON org.state=s.id 
                WHERE deo.do_id = '$doid'";
        
$resultitmdt = $conn->query($qryUpperInfo);
if ($resultitmdt->num_rows > 0) {	
	while ($rowUpperInfo = $resultitmdt->fetch_assoc()) {
	    $did = $rowUpperInfo["did"];
	    $orgname = $rowUpperInfo["orgname"];
	    $orgid = $rowUpperInfo["orgid"];
	    $contactno = $rowUpperInfo["contactno"];
	    $orderdt = $rowUpperInfo["orderdate"];
	    $addresss = $rowUpperInfo["street"] + $rowUpperInfo["district"] + $rowUpperInfo["state"] + $rowUpperInfo["zip"];
	}
    	
}
$formString = "";
$formString = ' 
                                            <div class="row">
												<div class="col-sm-12">
											

		
												</div>
												<div class="row no-mg">



												</div>

                                                <div class="col-lg-3 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbcontype">Maintenance Reason<span class="redstar">*</span></label>
														<div class="form-group styled-select">
															
															<select name="reason" id="reason" class="cmd-child form-control" required>';
							$qryRepair = "SELECT * FROM `maintenance_type`";
							$resultRepair = $conn->query($qryRepair);
	                        while ($rowr = $resultRepair->fetch_assoc()) {
	                            $rid = $rowr["id"]; $rname = $rowr["name"];
														$formString  .= '<option value="'.$rid.'">'.$rname.'</option>';
	                        }
$formString .= '															</select>
														</div>
													</div>
												</div>
												
												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbcontype">Order Date<span class="redstar">*</span></label>
														<input type="text" class="form-control datepicker" name="date" id="date" value="" placeholder="Date">
													</div>
												</div>												
												

												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbcontype">Organization<span class="redstar">*</span></label>
														<input type="hidden" class="form-control phone" name="orgid" id="orgid" value="'.$orgid.'" disabled>
														<input type="text" class="form-control phone" name="org" id="org" value="'.$orgname.'" disabled>	
													</div>
												</div>
												
												<div class="col-lg-3 col-md-6 col-sm-6">
													<div class="form-group">
														<label for="cmbcontype">Phone<span class="redstar">*</span></label>
														<input type="text" class="form-control phone" name="phone" id="phone" value="'.$contactno.'" disabled>	
													</div>
												</div>
												<br>
												<div class="col-sm-12">
														<h4>Products Information</h4>
														
													</div>												
												<div class="col-sm-12">';
							$qryProduct = "SELECT i.id productid, i.name productname, i.image FROM `delivery_order_detail` dod LEFT JOIN item i ON i.id=dod.item WHERE `do_id` = ".$did;
							$resultProduct = $conn->query($qryProduct);
	                        while ($rowpro = $resultProduct->fetch_assoc()) {
	                            $productid = $rowpro["productid"]; $productname = $rowpro["productname"]; $image = $rowpro["image"];					
													
													
									$formString .= '<hr class="form-hr" style="margin-bottom: 10px">
													<table width="100%" cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td width="120"><img src="../assets/images/products/300_300/'.$image.'" height="100"></td>
															<td><b>Item:</b> '.$productname.'</td>
															<td width="50">
															
																<div class="icheck-primary">
																	<div class="icheckbox_square-blue"><input type="checkbox" name="product[]" value="'.$productid.'" id="item" ></div>
																	<label for="product" class=""> &nbsp;</label>
																</div>
																	

																
															
															</td>
														</tr>
													</table>';
	                        }
					$formString .= '</div>
									<div class="col-lg-12 col-md-12 col-sm-12">

													<div class="form-group">

														<label for="details">Issue Detail:</label>

														<textarea class="form-control" id="details" name="details" rows="4"></textarea>

													</div>

												</div>
												<div class="col-lg-12 col-md-12 col-sm-12">
													<div class="form-group">
														<div class="icheckbox_square-blue"><input type="checkbox" name="inspection" value="1" id="physicalInspection"></div>
														<label for="physicalInspection"> &nbsp;Required Physical Inspection?</label>
													</div>
												</div>
												
												<div class="col-xs-3">
													<div class="form-group">
														<label for="physicalInspection">Clients Preferred Inspection Date and Time: </label>
														<input type="text" class="form-control datepicker" name="inspectiondate" id="inspectiondate" value="" placeholder="Date"><br>
														<input type="text" class="form-control timeonly" name="time" id="time" value="" placeholder="Time">
													</div>
												</div>
												
												<div class="col-xs-9">
													<div class="form-group">
														<label for="physicalInspection">Customer Address:</label>
														<textarea class="form-control" id="address" name="address" rows="3">'.$addresss.'</textarea>
													</div>
												</div>													
												

												<div class="col-lg-12 col-md-12 col-sm-12">

													<div class="form-group">

														<label for="details">Remarks</label>

														<textarea class="form-control" id="note" name="note" rows="2"></textarea>

													</div>

												</div>


													<div class="col-sm-12">
														<h4>Charges </h4>
														<hr class="form-hr">
													</div>												
												
												
												<div class="col-lg-2 col-md-6 col-sm-6">
													<label for="fee">Service Fee<span class="redstar">*</span></label>
													<div class="input-group">
														<input type="text" class="form-control " name="fee" id="fee" value="" required>
													</div>
												</div>												
												
												
												<div class="col-lg-1 col-md-6 col-sm-6">
													<label for="tds">TDS<span class="redstar">*</span></label>
													<div class="input-group">
														<input type="text" class="form-control " name="tds" id="tds" value="" required>
													</div>
												</div>	
												
												
												<div class="col-lg-1 col-md-6 col-sm-6">
													<label for="vds">VDS<span class="redstar">*</span></label>
													<div class="input-group">
														<input type="text" class="form-control " name="vds" id="vds" value="" required>
													</div>
												</div>	
												
												
												<div class="col-lg-2 col-md-6 col-sm-6">
													<label for="total">Total<span class="redstar">*</span></label>
													<div class="input-group">
														<input type="text" class="form-control " name="total" id="total" value="" required>
													</div>
													
												</div>													
												<div class="col-sm-12">
													<hr class="form-hr">
												</div>	
												
												<div class="col-sm-12">
													<input class="btn btn-lg btn-default top" type="submit" name="add" value="Submit" id="save">
													<input class="btn btn-lg btn-warning top" type="button" name="postaction" value="Cancel" id="cancel" onClick="location.href = \'maintenanceList.php?pg=1&mod=3\'">

												</div>

											</div>
									










										



										</div>
										<!-- end panel body -->
									</div>
									<!-- /#end of panel -->

									<!-- START PLACING YOUR CONTENT HERE -->
								</form>

<script>
//datetime definer
function callTime(){
         $(".timeonly").datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "HH:mm",
					//format: "LT",
					keepOpen: true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-chevron-up",
                 down: "fa fa-chevron-down"
                }
            });
			//$(".timeonly").data("DateTimePicker").show();
}
callTime();

$(".datepicker, .datepicker_history_filter").datetimepicker({
					//inline:true,
					//sideBySide: true,
				format: "DD/MM/YYYY",
			 	
					
				 //keepOpen:true,
			 	//inline: true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });

</script>

';

echo $formString;
?>