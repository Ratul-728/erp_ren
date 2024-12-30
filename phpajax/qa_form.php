<?php

session_start();
require "../common/conn.php";
include_once("../rak_framework/listgrabber.php");


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
<?php
if($debug == 1){
?>
<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/font-awesome4.0.7.css" rel="stylesheet">
<link href="../css/fonts.css" rel="stylesheet">
<link href="../css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style_extended.css" rel="stylesheet">
<link href="../css/simple-sidebar.css" rel="stylesheet">
<link href="../css/icheck-bootstrap.min.css" rel="stylesheet" />
<link href="../js/plugins/icheck/skins/square/blue.css" rel="stylesheet">
<!--end icheck box CSS -->
<?php
}
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



.ajax-img-up{
    border: 1px solid #393939;
}
.ajax-img-up .btn{
    padding: 8px;
}


.ajax-img-up ul{margin: 0; padding: 0; margin-top: -3px;}


.ajax-img-up li:first-child{
  display: none;
}

.ajax-img-up{
    
    border: 0px solid #000;
    display: flex;
}

.ajax-img-up li{
  display: block;
  float: left;
  width: 35px;
  height: 34px;
  border: 0px solid #c0c0c0;
  position: relative;
  margin: 3px;
  box-shadow: 0 0 1px #b5b3b3;
  border-radius: 0px;
}

.ajax-img-up li.addimg-btn label{
  padding: 0px;
  cursor: pointer;
}

.ajax-img-up li img{
  width: 100%;
  height: 100%;
  border-radius: 5px;
}

.ajax-img-up .picbox{
    position: relative;
}
.picbox span{
    position: absolute;
    padding: 5px;
    padding-top: 3px;
    width: 20px;
    height: 20px;
    background-color: red;
    color: #fff;
    border-radius: 2px;
    cursor: pointer;
    
    top: -7px;
  left: -7px;
  z-index: 100;
}


@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.rotate {
  /* Add any additional styles for your icon here */
  transition: transform 0.5s ease-in-out; /* Optional: Add a smooth transition effect */
  /* Apply the rotation animation */
  animation: rotate 2s infinite linear; /* Adjust the duration and other properties as needed */
}


</style>

<div class="containerx">
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
                                <div class="form-group d-flex">
                                    <input style="width:65px;font-weight:bold" type="number" class="form-control qa-input" id="pass_qtys" name="pass_qtys" value="<?= $pass_qty ?>" required>
                                    <div class="ajax-img-up">
                                        <ul class="d-flex defect-img">
                                            <li></li>
                                            <?php
                                            // get existing images here;
                                            	$inputImgData = array(

                                            	'TableName' => 'qa_images',
                                            	'OrderBy' => 'id',
                                            	'ASDSOrder' => 'DESC',
                                            	'id' => '',
                                            	'type' => 'passed',
                                            	'image_url' => '',
                                            	'qaw_id' => $qawId
                                            	);
                                            	listData($inputImgData,$imgData);
                                            	
                                                foreach($imgData as $lidata){
                                                    echo '<li class="picbox"><span class="delete-btn fa fa-close"   data-imagepath="'.$lidata['image_url'].'" data-dataid="'.$lidata['qaw_id'].'"></span><img src="../images/upload/qa_images/thumb/'.$lidata['image_url'].'"></li>';
                                                }
                                            ?>
                                            <li class="addimg-btn">
                                                <label class="input-group-btn">
                                                    <span class="fa fa-plus"></span>
                                                    <span title="Add Defect Pictures"  name="passed" style="width:40px;" data-qawid="<?=$qawId?>"  data-qatype="passed"  class="form-control btn btn-default defect-btn">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="file" tabindex="2" name="file" data-type="passed" id="upfiles" class="upfiles" style="display: none;" i d="gallery-photo-add" multiple >
                                                 </label>
                                            </li>
                                        </ul>   
                                        
                                    </div>

                                </div>
                            </div>
                            <div class="col-xs-5">
                                &nbsp;
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group d-flex"><input type="checkbox" name="chdefect qa-chk" value="1"><label>&nbsp;Repairable</label></div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group d-flex">
                                    <input style="width:65px;font-weight:bold" type="number" class="form-control qa-input" id="defacts_qtys" name="defacts_qtys" value="<?= $defect_qty ?>" required>
                                    <div class="ajax-img-up">
                                        <ul class="d-flex defect-img">
                                            <li></li>
                                            <?php
                                            // get existing images here;
                                            	$inputImgData = array(

                                            	'TableName' => 'qa_images',
                                            	'OrderBy' => 'id',
                                            	'ASDSOrder' => 'DESC',
                                            	'id' => '',
                                            	'type' => 'defect',
                                            	'image_url' => '',
                                            	'qaw_id' => $qawId
                                            	);
                                            	listData($inputImgData,$imgData);
                                            	
                                                foreach($imgData as $lidata){
                                                    echo '<li class="picbox"><span class="delete-btn fa fa-close"   data-imagepath="'.$lidata['image_url'].'" data-dataid="'.$lidata['qaw_id'].'"></span><img src="../images/upload/qa_images/thumb/'.$lidata['image_url'].'"></li>';
                                                }
                                            ?>
                                            <li class="addimg-btn">
                                                <label class="input-group-btn">
                                                    <span class="fa fa-plus"></span>
                                                    <span title="Add Defect Pictures"  name="defect" style="width:40px;" data-qawid="<?=$qawId?>"  data-qatype="defect"  class="form-control btn btn-default defect-btn">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="file" tabindex="2" name="file" data-type="defect" id="upfiles1" class="upfiles" style="display: none;" i d="gallery-photo-add" multiple >
                                                 </label>
                                            </li>
                                        </ul>   
                                        
                                    </div>

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
                                <div class="form-group d-flex">
                                    <input type="number" style="width:65px;font-weight:bold" class="form-control qa-input" id="damaged_qtys" name="damaged_qtys" value="<?= $damaged_qty ?>" min=0 required>
                                    <div class="ajax-img-up">
                                        <ul class="d-flex damaged-img">
                                            <li></li>
                                            <?php
                                            // get existing images here;
                                            	$inputDamagedImgData = array(

                                            	'TableName' => 'qa_images',
                                            	'OrderBy' => 'id',
                                            	'ASDSOrder' => 'DESC',
                                            	'id' => '',
                                            	'type' => 'damaged',
                                            	'image_url' => '',
                                            	'qaw_id' => $qawId
                                            	);
                                            	listData($inputDamagedImgData,$damagedImgData);
                                            	
                                                foreach($damagedImgData as $lidata){
                                                    echo '<li class="picbox"><span class="delete-btn fa fa-close"   data-imagepath="'.$lidata['image_url'].'" data-dataid="'.$lidata['qaw_id'].'"></span><img src="../images/upload/qa_images/thumb/'.$lidata['image_url'].'"></li>';
                                                }
                                            ?>
                                            <li class="addimg-btn">
                                                <label class="input-group-btn">
                                                    <span class="fa fa-plus"></span>
                                                    <span title="Add Damaged Pictures"  name="damaged" style="width:40px;" data-qawid="<?=$qawId?>"  data-qatype="damaged"  class="form-control btn btn-default defect-btn">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                    <input type="file" tabindex="2" name="file" data-type="damaged" id="upfiles2" class="upfiles" style="display: none;"  multiple >
                                                 </label>
                                            </li>
                                        </ul>   
                                        
                                    </div>
                                </div>
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
</div>
<!--temp-->
<?php
if($debug == 1){
?>
<script src="../js/jquery.min.js"></script>

<script src="../js/plugins/sweetalert/sweetalert.min.js"></script>

<script src="../js/custom.js"></script>

<!-- iCheck code for Checkbox and radio button -->
<script src="../js/plugins/icheck/icheck.js"></script>
<script language="javascript">
    
    
$(document).on('ready', function() {
  // Call initializeiCheck() function when the AJAX content is loaded
  //initializeiCheck();
});
    
function initializeiCheck() {
  $('input').iCheck({
  checkboxClass: 'icheckbox_square-blue',
  radioClass: 'iradio_square-blue',
  increaseArea: '20%'
  });
}    

</script>

<!--end temp-->
<?php
}
?>

<script>

$(document).ready(function(){
    
  	var wrapper = ".ajax-img-up";
	//var inputFile = ".upfiles";
	var imageLimit = 5;
	var referenceValue = <?=$qawId?>; 
	var cnt = 0;
    
    //handle qa image delete
    $(document).on('click', wrapper+' .delete-btn', function() {

	    var target = $(this).data('imagepath');
	    var dataid = $(this).data('dataid');
	    var thisLi = $(this).closest('li');


        //alert(target);

	   

       $.ajax({
          url: 'phpajax/qa_image_delete.php',
          type: 'post',
          data: { dataid: dataid, action: 'deletepic', target: target },
         


          success: function(res){
              
              //swal("Success!", res.message , "success");
              
              //return false;
              
              
              swal("Success!", res.message_orimg+"\n"+res.message_thumb+"\n"+res.message_record , "success");
              //return false;
             if(res.code == 1){
                //alert(message_record);
				swal("Success!", res.message_record , "success");
				thisLi.remove();

             }else{
                 //alert(message_record);
               swal("Error!", res.message_record , "error");
            }
          },
       }); //$.ajax({



	}); //$(document).on('click', wrapper+' .delete-btn', function() {

	

    //handle qa image upload
    $("#upfiles1, #upfiles2, #upfiles").change(function(){
        
        var inputFile = '#'+$(this).attr("id");
        
        var type = $(this).data("type");
        var liLength = $(wrapper+'  ul.'+type+'-img li').length;
        
        //alert(wrapper+' ul.'+type+'-img  li:last');
        
        if(liLength < (imageLimit+2)){
            
            var fd = new FormData();
            var files = $(inputFile)[0].files;
        
 
        
            if(files.length > 0 ){
                
                $(wrapper+' ul.'+type+'-img .fa-plus').addClass('rotate');
                
               fd.append('file',files[0]);
               fd.append("reference", referenceValue);
               fd.append("type", type);
    
    
               $.ajax({
                 // url: '/phpajax/uploadimageajx.php',
                  url: '/phpajax/qa_image_upload_post.php',
                  type: 'post',
                  data: fd,
                  contentType: false,
                  processData: false,
                  success: function(res){
                      
                       //swal(res.dataid);
                       
                       
                    if(res.code == 1){ // success

    					 $(wrapper+' ul.'+type+'-img  li:last').before('<li class="picbox"><span data-dataid="'+res.dataid+'" data-imagepath="'+res.imagepath+'" class="delete-btn fa fa-close"></span><img src="../images/upload/qa_images/thumb/'+res.imagepath+'"></li>');
                         $(wrapper+' ul.'+type+'-img .fa-plus').removeClass('rotate');
                         swal("Success!", res.message , "success");

                     }
                      
                     if(res.code == 2){ // invalid file type
                    
                        swal("Error!", res.message , "error");
                        $(wrapper+' ul.'+type+'-img .fa-plus').removeClass('rotate');
                        
                     }
                       
                       
    
 
                  }, //success: function(res){
               }); //$.ajax({
               
               
            }else{ //if(files.length > 0 ){
                  swal("Error!", "Please select a file", "error");
            }
        
        }else{ //if(liLength < (imageLimit+2)){
            swal("Error!", "You can upload "+imageLimit+" pictures maximum!", "error");
        }  
   
   });

 
});//$(document).ready(function(){


</script>










<script>

$(document).ready(function() {
    
    
    
    $(document).on("click",".defect-btnx",function(){
        
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

          }
        });
});
</script>

<!-- iCheck code for Checkbox and radio button -->
<script src="../js/plugins/icheck/icheck.js"></script>
<!--script language="javascript">
    
    
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

   
</script-->
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
	