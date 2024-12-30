<?php
//print_r($_REQUEST);
//exit();


require "common/conn.php";
include_once('rak_framework/fetch.php');
session_start();
$usr = $_SESSION["user"];
//echo $usr;die;

//ini_set('display_errors', 1);


if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {

	
$doId = $_GET["do"];
$doId = "DO-000009";

$qryInfo="SELECT org.name, so.orderdate, org.orgcode, org.contactno, so.remarks, d.order_id FROM delivery_order d LEFT JOIN `qa` q ON d.order_id=q.order_id 
            LEFT JOIN `soitem` so ON q.order_id=so.socode LEFT JOIN `organization` org ON org.id=so.organization 
            WHERE d.do_id = '".$doId."' LIMIT 1;";
$resultInfo = $conn->query($qryInfo);
while ($rowinfo = $resultInfo->fetch_assoc()) {
    $customerName = $rowinfo["name"];
    $customerId = $rowinfo["orgcode"];
    $orderDate = $rowinfo["orderdate"];
    $customerContact = $rowinfo["contactno"];
    $deliveryAddress = $rowinfo["remarks"];
    $orderId = $rowinfo["order_id"];
}
	
	
	
    $currSection = 'resourceplan';
    $currPage    = basename($_SERVER['PHP_SELF']);
	
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	
include_once 'common_header.php';
    ?>
<!-- Select2 CSS -->
<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />

<!--
Select 2 Custom CSS

-->
    
<style>
.select2-container--default .select2-selection--multiple {
  background-color: white;
  border: 1px solid #aaa!important;
  border-radius: 0px;
  cursor: text;
}

.select2-container .select2-selection--multiple {
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  min-height: 38px;
  user-select: none;
  -webkit-user-select: none;
}


.select2-container--default .select2-selection--multiple .select2-selection__choice {
  background-color: #e4e4e4;
  border: 1px solid #dbdbdb;
  border-radius: 2px;

  padding: 3px;
  padding-left: 0px;
  padding-left: 30px;
  font-size: 14px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
  padding: 3px 8px;
}
    
    
.select2-container{
  width:100%!important;
}    
</style>    
    
    
<body class="form scm scm-resource-plan-form">

<?php
	
	
include_once 'common_top_body.php';
	
	
	
    ?>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Resource Plan</span>
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
                       <form method="post" action="common/scm_resource_plan_post.php"  id="ResourcePlanForm"  enctype="multipart/form-data">
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">




			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                           
                            
                            
                           
                            
                                <?php
                                    $mode = 1;
                                ?>

                                   <div class="row form-header">

	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>Supply Chain Management <i class="fa fa-angle-right"></i> Delivery <i class="fa fa-angle-right"></i>  <?=($mode == 1)?"Create a Resource Plan":"Edit Resource Plan"?>  </h6>
      		                            </div>

      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked <span class="redstar">*</span>
 are required)</span></h6>
      		                            </div>


                                   </div>



                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->

                                <div class="row">
                            	    <div class="col-sm-12">
                                            &nbsp;
    	                            </div>
                                    <div class="row no-mg">



                                    </div>
                                    
<!--
                                    <div class="col-sm-12">
                                        <h4>Order Information  </h4>
                                        <hr class="form-hr">
                                    </div>                                    
-->
                                     <div class="col-sm-12 col-lg-6">
                                            <style>
                                                .table-header{
                                                    padding: 15px 25px;
                                                }        
                                            </style>
                                           <div class="well table-header">
                                               
                                               <div class="row">
                                                   <div class="col-sm-12">
                                                        <div class="row">
                                                           <div class="col-md-6">
                                                                   <b>ORDER ID: </b> <?= $orderId ?>  <br>
                                                                    <b>CUSTOMER ID:   </b>  <?= $customerId ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                    <b>CUSTOMER NAME: </b> <?= $customerName ?> <br>
                                                                    <b>CUSTOMER CELL:   </b> <?= $customerContact ?>
                                                            </div>
                                                       </div>

                                                   </div>

                                                   <div class="col-sm-12">

                                                        <div class="row">
                                                           <div class="col-md-6">
                                                                   <b>ORDER DATE:  </b>  <?= $orderDate ?> <br>
                                                                    <b>DELIVERY DATE:     </b>   
                                                            </div>
                                                            <div class="col-md-6">
                                                                    <b>DELIVERY ADDRESS:</b>
                                                                    <?= $deliveryAddress ?>
                                                            </div>
                                                       </div>


                                                   </div>


                                               </div>

                                            </div>  
                                    
                                    
                                    
                                    
                                    </div> 
                                    
                                    <div class="col-md-12">
                                    </div> 
                                    
                                   <div class="col-md-12 col-lg-6">
                                        <h4>Transporation </h4>
                                        <hr class="form-hr">
                                    </div>  
                                    
                                    <div class="col-sm-12">
                                    </div>                        
                                    
                                      <div class="col-md-12 col-lg-6">
                                          
                                        <div class="form-group">
                                            <label class="control-label" for="inputGroupPassword">Resource Plan / Transporation plan for</label>
                                              <ul class="icheck-ul list-horz">
                                                <li>
                                                  <input tabindex="1" type="radio" name="plantype" id="do"> &nbsp;
                                                  <label for="do"> Item Deliver Order </span></label>
                                                </li>
                                                <li>
                                                  <input tabindex="2" type="radio" name="plantype"  id="ch" > &nbsp;
                                                  <label for="ch"> Item Unload/Challan</span></label>
                                                </li>
                                                <li>
                                                  <input tabindex="3" type="radio" name="plantype"  id="ww" > &nbsp;
                                                  <label for="ww"> Move Item Warehouse to Warehouse</span></label>
                                                </li>                            
                            
                                              </ul>

                                        </div>  
                                    </div>                                    
                                    
                                   <div class="col-md-12 ">
                                    </div>
            
            
            
                                    
                                      <div class="col-md-12 col-lg-6">
                                       <hr class="form-hr">
                                        <div class="form-group">
                                            <label class="control-label" for="inputGroupPassword">Transportation/Device Needed</label>
                                            
                                            
                                              <ul class="icheck-ul list-horz row liborder-bottom">
                                                <li class="col-lg-6 col-md-4 col-sm-12">
                                                  <div class="col1">
                                                      <input tabindex="1" type="checkbox" name="pickupvan" id="pickupvan"> &nbsp;
                                                      <label for="pickupvan"> Pickup Van </span></label>
                                                  </div>
                                                  <div class="col1">
                                                      <div class="form-group">
														<input type="number" min="0" max="5" class="numonly "  placeholder="0" name="qty_pickupvan">
													  </div>
                                                  </div>
                                                </li>
                                                <li class="col-lg-6 col-md-4 col-sm-12">
                                                  <div class="col1">
                                                      <input tabindex="1" type="checkbox" name="coveredvan" id="coveredvan"> &nbsp;
                                                      <label for="coveredvan"> Covered Van </span></label>
                                                  </div>
                                                  <div class="col1">
                                                      <div class="form-group">
														<input type="number" min="0" max="5" class="numonly "  placeholder="0" name="qty_coveredvan">
													  </div>
                                                  </div>
                                                </li>
                                                <li class="col-lg-6 col-md-4 col-sm-12">
                                                  <div class="col1">
                                                      <input tabindex="1" type="checkbox" name="highace" id="highace"> &nbsp;
                                                      <label for="highace"> High Ace </span></label>
                                                  </div>
                                                  <div class="col1">
                                                      <div class="form-group">
														<input type="number" min="0" max="5" class="numonly "  placeholder="0" name="qty_highace">
													  </div>
                                                  </div>
                                                </li>
    
                                                <li class="col-lg-6 col-md-4 col-sm-12">
                                                  <div class="col1">
                                                      <input tabindex="1" type="checkbox" name="trolley" id="trolley"> &nbsp;
                                                      <label for="trolley"> Trolley </span></label>
                                                  </div>
                                                  <div class="col1">
                                                      <div class="form-group">
														<input type="number" min="0" max="5" class="numonly "  placeholder="0" name="qty_trolley">
													  </div>
                                                  </div>
                                                </li>   
                            
                                              </ul>
                                            

                                        </div>  
                                    </div> 

                                   <div class="col-sm-12">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">

                                        <div class="form-group">

                                            <label for="machinary">Machinary</label>

                                            <textarea class="form-control" id="machinary" name="machinary" rows="2"></textarea>

                                        </div>

                                    </div>



                                    <div class="col-lg-3 col-md-6 col-sm-12">

                                        <div class="form-group">

                                            <label for="equipment">Special Equipment</label>

                                            <textarea class="form-control" id="equipment" name="equipment" rows="2"></textarea>

                                        </div>

                                    </div>

                                   <div class="col-sm-12">
                                    </div>


                                    <div class="col-md-12 col-lg-6">
                                      <h4>Human Resources</h4>
                                      <hr class="form-hr">
                                    </div>



                                   <div class="col-sm-12">
                                    </div>




                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="supervisor">Supervisor<span class="redstar">*</span></label>
                                              <div class="form-group styled-select">
                                                <select name="cmbsupnm" id="supervisor" placeholder="dfd" class="cmd-child form-control" required="">
                                                        <option value="">Select Name</option>
                                                        <option value="1391">Abdul Kuddus (Shahed)</option>
                                                        <option value="1285">Abdul Mottalive Mia</option>
                                                        <option value="1482">Abrar</option>
                                                        <option value="1394">Abu Saleh Abdul Muiz</option>
                                                        <option value="1406">Abul Kalam Azad</option>
                                                        <option value="1240">Ador</option>
                                                        <option value="1260">Agora Account Manager</option>
                                                        <option value="1483">Ahmad Huq</option>
                                                        <option value="1434">akash</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsupnm">Logistic Team<span class="redstar">*</span></label>
                                          
                                                <select name="cmbsupnm" id="logisticTeam" placeholder="dfd" class="cmd-child form-control" required="" multiple>
                                                        <option value="">Select Team</option>
                                                        <option value="1391">Team A</option>
                                                        <option value="1285">Team B</option>
                                                        <option value="1482">Team C</option>

                                                </select>

                                        </div>

                                    </div>

                                    <div class="col-lg-1 col-md-4 col-sm-6">
                                        <label for="supervisor">Labor Qty<span class="redstar">*</span></label>
                                          <div class="form-group">
                                                <input type="number" min="0" max="5" class="numonly form-control"  placeholder="0" name="qty_trolley">
                                          </div>
                                    </div>




                                   <div class="col-sm-12">
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="supervisor">Technical Team Member<span class="redstar">*</span></label>
                                              
                                                <select name="techTeam" id="techTeam" placeholder="dfd" class="cmd-child form-control" required="" multiple>
                                                        <option value="">Select Name</option>
                                                        <option value="1391">Abdul Kuddus (Shahed)</option>
                                                        <option value="1285">Abdul Mottalive Mia</option>
                                                        <option value="1482">Abrar</option>
                                                        <option value="1394">Abu Saleh Abdul Muiz</option>
                                                        <option value="1406">Abul Kalam Azad</option>
                                                        <option value="1240">Ador</option>
                                                        <option value="1260">Agora Account Manager</option>
                                                        <option value="1483">Ahmad Huq</option>
                                                        <option value="1434">akash</option>
                                                </select>
                                            
                                        </div>

                                    </div>


                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="QATeam">QA Team Member<span class="redstar">*</span></label>
                                              
                                                <select name="QATeam" id="QATeam" placeholder="dfd" class="cmd-child form-control" required="" multiple>
                                                        <option value="">Select Name</option>
                                                        <option value="1391">Abdul Kuddus (Shahed)</option>
                                                        <option value="1285">Abdul Mottalive Mia</option>
                                                        <option value="1482">Abrar</option>
                                                        <option value="1394">Abu Saleh Abdul Muiz</option>
                                                        <option value="1406">Abul Kalam Azad</option>
                                                        <option value="1240">Ador</option>
                                                        <option value="1260">Agora Account Manager</option>
                                                        <option value="1483">Ahmad Huq</option>
                                                        <option value="1434">akash</option>
                                                </select>
                                            
                                        </div>

                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="otherTeam">Other Team Member<span class="redstar">*</span></label>
                                              
                                                <select name="otherteam" id="otherTeam" placeholder="dfd" class="cmd-child form-control" required="" multiple>
                                                        <option value="">Select Name</option>
                                                        <option value="1391">Abdul Kuddus (Shahed)</option>
                                                        <option value="1285">Abdul Mottalive Mia</option>
                                                        <option value="1482">Abrar</option>
                                                        <option value="1394">Abu Saleh Abdul Muiz</option>
                                                        <option value="1406">Abul Kalam Azad</option>
                                                        <option value="1240">Ador</option>
                                                        <option value="1260">Agora Account Manager</option>
                                                        <option value="1483">Ahmad Huq</option>
                                                        <option value="1434">akash</option>
                                                </select>
                                            
                                        </div>

                                    </div>

                                   <div class="col-sm-12">
                                    </div>

                                    <div class="col-sm-6">
                                      <h4>Schedule</h4>
                                      <hr class="form-hr">
                                    </div>


                                   <div class="col-sm-12">
                                    </div>                
                                        
                                    

      	                            
                            	    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="po_dt">Delivery Start Date & Time<span class="redstar">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                            	    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="po_dt">Delivery End Date & Time<span class="redstar">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>


                                   <div class="col-sm-12">
                                    </div>
                                    
                                    
                                    <div class="col-sm-12"> 
											<input type="hidden" name="mode" value="1">
											<input type="hidden" name="id" value="">
                                            

										
										
													<input class="btn btn-lg btn-default top" type="submit" name="postaction" value="Create" id="save"> 
    												<input class="btn btn-lg btn-warning top" type="button" name="postaction" value="Cancel" id="cancel" onclick="location.href = 'scm_delivery_list.php?pg=1&amp;mod=2'">

                                    </div>
                                   

                                    


                                    

                                </div>

							

								
                                       

                                            


		
												
							

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

<!-- Select2 JS -->
<script src="js/plugins/select2/select2.min.js"></script>
<script>
  $(document).ready(function() {
  
    $('#logisticTeam').select2();
    $('#techTeam').select2();
    $('#QATeam').select2();
    $('#otherTeam').select2();
  });
</script>



<script>

	
    $(document).ready(function(){
		
		
		
		
		
		//input number only validateion
		//put class .numonly to apply this. alpha will no take, only number and float
		
		$('.numonlyx').change(function(e){
			var xxxx = $(this).val();
			//alert(typeof(parseFloat(xxxx)));
		});
		
		
		
        //$('.numonly').keyup(function(e){
        $(document).on("keyup",".numonly", function(e){

			
		  if(/[^0-9.]/g.test(this.value))
		  {
			// Filter non-digits from input value.
			this.value = this.value.replace(/[^0-9.]/g, '');
			  
		  }
		});		

		
		
//hide warehouse quantity on clicking on anywhare;
$(document).on('click',".pagetop", function(event) {
     var div2 = $(event.target);
     var div = $('.qtycounter'); 
    //if (!target.is('.qtycounter') && !target.is('.c-qty')) {
     if (!div.is(event.target) && !div2.is('.c-qty') && !div.has(event.target).length) {
      //$('.qtycounter').css('visibility','hidden'); 
        div.css('visibility','hidden');
    }
  });        
        

    
      
        

})
    
    




function updateSum(rt) {
  var sum = 0;
  rt.find('.quantity').each(function() {
    var quantity = parseInt($(this).val());
    if (!isNaN(quantity)) {
      sum += quantity;
    }
  });
  return sum;
}
  
  
  
 });     
    

    
//handle samedate in quantity by warehouse
$(document).on('ifChanged','.samedate', function(event) {
  
  var checkbox = event.target;

  // Get the checkbox value and checked status
  var value = checkbox.value;
  var isChecked = checkbox.checked;
  
  var root = $(this).closest('.toClone');
  
	if(isChecked) {
    console.log("Checkbox with value '" + value + "' is checked.");
    // Additional actions for checked checkboxes
    
    var dd = root.find(".delivery-date").val();
    if(dd){
     root.find(".delivery-date").val(dd);
    }else{
    	alert('Enter a Date');
    	root.find(".delivery-date:first").focus();
    }
  }
  
  });    
    
	

	

	
	
	


</script>

<script language="javascript">


<?php
if ($res == 4) {
        ?>

//alert($(".cmb-parent").children("option:selected").val());

var selectedValue = $(".cmb-parent").children("option:selected").val();

	 $.ajax({
            type: "POST",
            url: "cmb/so_item_customer_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
			beforeSend: function(){
					$(".cmd-child").html("<option>Loading...</option>");
				},

        }).done(function(data){
            //root.find(".measure-unit").html(data);

			$(".cmd-child").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child").append(data);

			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });



    $.ajax({
            type: "POST",
            url: "cmb/so_item_poc_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
			beforeSend: function(){
					$(".cmd-child1").html("<option>Loading...</option>");
				},

        }).done(function(data){
            //root.find(".measure-unit").html(data);

			$(".cmd-child1").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child1").append(data);

			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });

<?php
}
 ?>








	




	





</script>

<script>
    //Searchable dropdown
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
        $('#cmborg').val(id);
        //alert(id);


        //Change Contact Name
        $.ajax({
            type: "POST",
            url: "cmb/get_data.php",
            data: { key : id, type: 'orgtocontact' },
			beforeSend: function(){
					$("#cmbsupnm").html("<option>Loading...</option>");
				},

        }).done(function(data){
			$("#cmbsupnm").empty();
			$("#cmbsupnm").append(data);
			//alert(data);
        });


	});
</script>


	
	
<script>

// new calculation code;
$(document).on("focus", ".calc", function() {
  $(this).select();
});








</script>

	
	
	
	
<script>
	//Footer Fields width same as discounted field;
	
function footerfldwdth(){
	ftrfldwdth = $(".c-discounted-ttl").width();
	$(".grid-sum-footer input").width(ftrfldwdth);
}
setTimeout(footerfldwdth,300);

window.addEventListener("resize", () => {
		footerfldwdth();
});	
	
	

var classes = ".grid-sum-footer input, .c-discounted-ttl"

$( "<span>৳</span>" ).insertAfter(classes);
$(classes).parent().addClass("ipspan");

</script>	
	


</body>
</html>
<?php } ?>