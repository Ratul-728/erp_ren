<?php

session_start();
require "../common/conn.php";

//ini_set('display_errors', 1);
//echo 'test';
//exit();
extract($_REQUEST);
$qawId = $_GET["qaw"];
$type = $_GET["type"];
$qryQaw = "SELECT b.name warehouse, qaw.ordered_qty, qaw.pass_qty, qaw.defect_qty, qaw.damaged_qty, qaw.date_inspected, q.remarks, q.id qaid, q.order_id, i.parts
            FROM `qa_warehouse` qaw LEFT JOIN `branch` b ON b.id=qaw.warehouse_id
            LEFT JOIN qa q ON q.id = qaw.qa_id LEFT JOIN item i ON i.id = q.product_id
            WHERE qaw.id = ".$qawId;
$resultQaw = $conn->query($qryQaw);
while ($rowQaw = $resultQaw->fetch_assoc()) {
    $warehouse = $rowQaw["warehouse"];
    $ordered_qty = $rowQaw["ordered_qty"];
    $pass_qty = $rowQaw["pass_qty"]; if($pass_qty == null) $pass_qty = 0;
    $defect_qty = $rowQaw["defect_qty"]; if($defect_qty == null) $defect_qty = 0;
    $damaged_qty = $rowQaw["damaged_qty"]; if($damaged_qty == null) $damaged_qty = 0;
    $date_inspected = $rowQaw["date_inspected"];
    $remarks = $rowQaw["remarks"];
    $qaId = $rowQaw["qaid"];
    $orderId = $rowQaw["order_id"];
    $parts = $rowQaw["parts"];
}

//echo $qryQaw;die;

?>

<!-- Bootstrap core CSS -->

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
input[type="checkbox"]{
    background-color: red;
    width:18px;
    height:18px;
    margin-right: 5px;
}




.bootstrap-dialog {
    z-index: 1050; /* Adjust as needed */
}

.dropzone {
    z-index: 1060!important; /* Adjust as needed */
}
.show-dropzone{
    height:400px;
}

</style>

<div class="row">


	<section class="tabs-content">

		<div id="tab1">
			<form id="form-org" method="post" enctype="multipart/form-data">
				<div class="row">




					<div class="col-sm-12">
						<h5 class="sub-title"><strong>Inspected Date:</strong> <?= $date_inspected ?></h5>
                        <h5 class="sub-title"><strong>Warehouse:</strong> <?= $warehouse ?>  | <strong>Ordered Qty:</strong> <?= $ordered_qty ?></h5>
					</div>
                    
                    <input type="hidden" id="ordered_qty" value="<?= $ordered_qty ?>">
                    <div class="col-sm-12">
                    
                        <div class="row">
                            <div class="col-xs-3"><strong>Status</strong></div><div class="col-xs-4"><strong>Quantity</strong> </div><div class="col-xs-5">&nbsp;</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group d-flex"><input type="checkbox" name="chpassed qa-chk" value="1"><label>&nbsp;Passed</label></div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group d-flex"><input type="number" style="width:65px;font-weight:bold" class="form-control qa-input" id="pass_qtys" name="pass_qtys" value="<?= $pass_qty ?>" required></div>
                            </div>
                            <div class="col-xs-5">
                                &nbsp;
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group d-flex"><input type="checkbox" name="chdefect qa-chk" value="1"><label>&nbsp;Defect</label></div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group d-flex">
                                    <input style="width:65px;font-weight:bold" type="number" class="form-control qa-input" id="defacts_qtys" name="defacts_qtys" value="<?= $defect_qty ?>" required>

                                            <button type="button" title="Add Defect Pictures"  name="defect" style="width:40px;" data-qawid="<?=$qawId?>"  data-qatype="defect"  class="form-control btn btn-default defect-btn">
                                                <i class="fa fa-plus"></i>
                                            </button>

                                </div>
                            </div>
                            <div class="col-xs-5">
                                &nbsp;
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group d-flex"><input type="checkbox" name="chdamaged qa-chk" value="1"><label>&nbsp;Damaged</label></div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group d-flex"><input type="number" style="width:65px;font-weight:bold" class="form-control qa-input" id="damaged_qtys" name="damaged_qtys" value="<?= $damaged_qty ?>" min=0 required><button style="width:40px;"  type="button" title="Add Damaged Pictures"  name="damaged"  data-qawid="<?=$qawId?>" data-qatype="damaged" class="form-control btn btn-default damaged-btn"><i class="fa fa-plus"></i></button></div>
                            </div>
                            <div class="col-xs-5">
                              &nbsp;
                            </div>
                        </div> 
                        
                        <input type="hidden" name="qawId" value="<?= $qawId ?>">
                        <input type="hidden" name="qaId" value="<?= $qaId ?>">
                        <input type="hidden" name="type" value="<?= $type ?>">
                        <input type="hidden" name="orderId" value="<?= $orderId ?>">
                        
                    <?php if($type == 2){ ?>
                        <div class="row">
                            <div class="col-xs-12"><strong><p> Number of Parts </p> <input type="number" class="form-control qa-input1" id="parts" name="parts" value="<?= $parts ?>" min=0 required></strong> </div><div class="col-xs-7">&nbsp;</div>
                        </div>
                    <?php } ?>
                        <div class="row">
                            <div class=" col-sm-12">
                                <div class="form-group">
                                    <label for="note">Comment</label>
                                    <textarea rows="2" class="form-control" id="remarks" name="remarks"><?php echo $remarks; ?></textarea>
                                </div>
                            </div>
                        </div>                          


				    </div>
            </div>

			</form>

		</div>


		
	</section>

</div>

<script>

$(document).ready(function() {
    
    
    
    $(document).on("click",".defect-btn",function(){
        
        var mylink = 'qa_image_upload.php?value1=sahirrr&value2=sabirr';
        
        
        
        
        
        
	    BootstrapDialog.show({
							
								title: 'QA Picture Upload',
								//message: '<div id="printableArea">'+data.trim()+'</div>',
								//message: $('<div id="printableArea5"></div>').load(mylink),
								message: $('<div id="printableArea4" align="center"><iframe id="myIframe" width="100%" height="400px" src="'+mylink+'"></iframe></div>'),
								type: BootstrapDialog.TYPE_PRIMARY,
								closable: true, // <-- Default value is false
								closeByBackdrop: false,
								draggable: true, // <-- Default value is false
								cssClass: 'show-invoice',
								buttons: [
								
								{
									icon: 'glyphicon glyphicon-chevron-left',
									cssClass: 'btn-default',
									label: ' Close',
									action: function(dialog2) {
									dialog2.close();	
								``	}
								}],
								onshown: function(dialog){  $('.btn-primary').focus();},
							});
        
        
        
   
   
   /*
           BootstrapDialog.show({
            title: 'Dropzone in BootstrapDialog',
            cssClass: 'show-dropzone',
            message: function (dialog) {
                // Create and append Dropzone element to dialog
                var dropzoneElement = document.createElement('div');
                dropzoneElement.id = 'myDropzone';
                dialog.getModalBody().append(dropzoneElement);

                // Initialize Dropzone
                var myDropzone = new Dropzone(dropzoneElement, {
                    paramName: "file",
                    maxFilesize: 5, // MB
                    maxFiles: 5,
                    acceptedFiles: ".jpg, .jpeg, .png, .gif",
                    url: "/phpajax/qa_image_upload_post.php",
                    // Other Dropzone options...
                    init: function () {
                        this.on("success", function (file, response) {
                            console.log("File uploaded:", file);
                            console.log("Server response:", response);
                        });
                    }
                });

                // Additional initialization code...

                // Trigger Dropzone to process any elements that have the dropzone class
                Dropzone.autoDiscover = false;
                myDropzone.processQueue();
                
            },
            buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Close',
								action: function(dialog) {
									dialog.close();	
									
									
								}
							}],
            onhide: function (dialog) {
                // Clean up or perform actions on dialog close
            }
        });     
        */
        
    });
    
    
      
    $(".qa-input").focus(function(){
      $(this).select();
    });      
      
      
        // Attach change event listener to the input field
        $('.qa-input').on('change', function() {
            
            var totalqty = sumNumbersByClassName('qa-input');
            
            // Find the closest checkbox to the changed input field
          var closestCheckbox = $(this).closest('.row').find('input[type="checkbox"]');
          var orderedqty = $("#ordered_qty").val();
          if($(this).val()<0){
              alert("Invalid Quantity");
              $(this).val(0);
          }
          if(totalqty<=orderedqty){  
            
          if($(this).val()>0){
            // Check the closest checkbox if the input value is not empty
            if ($(this).val().trim() !== '') {
                //closestCheckbox.prop('checked', true);
                closestCheckbox.iCheck('check');
            } else {
                //closestCheckbox.prop('checked', false);
                closestCheckbox.iCheck('uncheck');
            }
          }else{
            //closestCheckbox.prop('checked', false);
              closestCheckbox.iCheck('uncheck');
          }
          }else{
              alert("Invalid Quantity");
              var restqty;
              if((orderedqty - totalqty)>0){
                  restqty = orderedqty - totalqty;
                  $(this).val(restqty);
                  closestCheckbox.iCheck('check');
              }else{
                  
                  $(this).val(0);
              }
             // var restqty = (orderedqty - totalqty>0)?orderedqty - totalqty:0;
              //$(this).val(restqty);
              //closestCheckbox.iCheck('check');
          }
        });
    });
</script>

<!-- iCheck code for Checkbox and radio button -->
<script src="js/plugins/icheck/icheck.js"></script>
<script language="javascript">
    
    
$(document).on('ready', function() {
  // Call initializeiCheck() function when the AJAX content is loaded
  initializeiCheck();
});
    
function initializeiCheck() {
  $('input').iCheck({
  checkboxClass: 'icheckbox_square-blue',
  radioClass: 'iradio_square-blue',
  increaseArea: '20%'
  });
}    
    /*
$(document).ready(function(){
    
  $('input').iCheck({
  checkboxClass: 'icheckbox_square-blue',
  radioClass: 'iradio_square-blue',
  increaseArea: '20%'
});
});
*/
   
</script>
<!-- end iCheck code for Checkbox and radio button -->


<script>
    function sumNumbersByClassName(className) {
        var sum = 0;
        $('.' + className).each(function() {
            var num = parseInt($(this).val());
            if (!isNaN(num)) {
                sum += num;
            }
        });

        //alert('Sum of ' + className + ' numbers: ' + sum);
        return sum;
    }

    // Usage: Call the function with the class name you want to sum
    
</script>
	