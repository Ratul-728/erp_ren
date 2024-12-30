<?php
//print_r($_REQUEST);
//exit();
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{
  header("Location: ".$hostpath."/hr.php");
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $id= $_GET['id'];
    $atid = $_GET["id"];
    $serno= $_GET['id'];
    $totamount=0;
    
    if ($res==4)
    {
    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
    $qry="SELECT `id`, `shift`, `start`, `end`, `delaytime`, `extendeddelay`, `latetime`, `absent` FROM `OfficeTime` WHERE st = 1 and id = ".$id;
    //echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            }
        else
            {
                $result = $conn->query($qry); 
                if ($result->num_rows > 0)
                {
                    while($row = $result->fetch_assoc()) 
                        {
                            $shift = $row["shift"];
                            $stime = $row["start"];$etime = $row["end"];$dtime = $row["delaytime"];
                            $edtime = $row["extendeddelay"];$ltime = $row["latetime"];$abstime = $row["absent"];
                            
                        }
                }
            }
    $mode=2;//update mode
   // echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    
    else
    {
        $shift = ''; $stime = '';$etime = '';$dtime = '';
        $edtime = '';$ltime = '';$abstime = '';
        $mode=1;//Insert mode
                        
    }
    
    $currSection = 'rpt_attendance_all';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
     include_once('common_header.php');
?>

<!-- Select2 CSS -->
<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />

<!-- Include Toastr CSS -->
<link href="js/plugins/toastr/toastr.min.css" rel="stylesheet">





<style>

.toast-top-right {
  top: 60px !important; /* Adjust this value as needed */
}
#toast-container > div {
  /*opacity: 1 !important;*/
}


/* Override Toastr default styles */
#toast-container {
  right: 10px;
}

/* Animations */
.toast {
  animation: slideInRight 0.5s, fadeOut 1s; /* Use desired durations */
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}


.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 34px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b{
    border: 0;
}

.select2-container--default .select2-selection {
  background-color: transparent;
  border: 0px solid #aaa!important;
  border-radius: 0px;
  cursor: text;
}

.select2-container .select2-selection {
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  min-height: 38px;
  user-select: none;
  -webkit-user-select: none;
}


.select2-container--default .select2-selection .select2-selection__choice {
  background-color: #e4e4e4;
  border: 1px solid #dbdbdb;
  border-radius: 2px;

  padding: 3px;
  padding-left: 0px;
  padding-left: 30px;
  font-size: 14px;
}

.select2-container--default .select2-selection .select2-selection__choice__remove {
  padding: 3px 8px;
}
    
    
.select2-container{
  width:102%!important;
    padding: 0;margin: 0;
}    
</style>

<body class="form soitem">
    
<?php
    include_once('common_top_body.php');
?>

<div id="wrapper"> 
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Attendance</span>
        </div>
        <?php include_once('menu.php'); ?>
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
                       <form method="post" action="common/addattendance_new.php" id="form1" enctype="multipart/form-data">  
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->  
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">
      		           
			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->
                                   <div class="row form-header"> 
                                   
	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>HRM <i class="fa fa-angle-right"></i> Add New Attendance</h6>
      		                            </div>
      		                            
      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>                                   
                                   
                                   
                                   </div>  
                                <div class="row">
                            	    <div class="col-sm-12">
	                                    <!-- <h4>SO Information</h4>
		                                <hr class="form-hr"> -->
		                                
		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno;?>"> 
		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
    	                            </div> 
                                    
	                                 <div class="col-sm-6">
                                        <div class="po-product-wrapper withlebel"> 
                                        <div class="row color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Attendance Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if(true){?> 	                                        
	                                        
          	                                    <div class="col-sm-6">
													<label>Employee<span class="redstar">*</span></label>
                                                    <div class="form-group">
                                                
                                                        <div class="form-group styled-select">
                                                        <select name="cmbempnm" id="cmbempnm" class="select2basic form-control" required>
                                                            <option value="">Select Employee</option>
            <?php 
            $qry1="SELECT concat(emp.firstname, ' ', emp.lastname, '-(', employeecode, ')') empnm, h.attendance_id FROM `employee` emp LEFT JOIN hr h ON h.emp_id=emp.employeecode ORDER by empnm";
        	$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
            {while($row1 = $result1->fetch_assoc()) 
                  {   $tid= $row1["attendance_id"];  $nm=$row1["empnm"]; 
            ?>  
        													
                                                            <option value="<? echo $tid; ?>" <? if ($mnhrid == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
            <?php 
                  }
            }      
            ?>   
                                                        </select>
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
            	                                    <label for="email">Attendance Date<span class="redstar">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control datepicker" id="attendance_date" name="attendance_date" value="<?php echo $attendance_from_string;?>" required>
                                                        <div class="input-group-addon">
                                                            <span class="glyphicon glyphicon-th"></span>
                                                        </div>
                                                    </div>     
                                                </div>
          	                                    <div class="col-lg-6 col-md-6 col-sm-6">
													<label>In Time</label>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="intime" name="intime">
                                                    </div>        
                                                </div> <!-- this block is for Start time-->
                                                
                                                <div class="col-lg-6 col-md-6 col-sm-6">
													<label>Out Time</label>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="outtime" name="outtime">
                                                    </div>    
                                                </div> <!-- this block is for End time-->
                                                
                                                <div class="col-lg-6 col-md-6 col-sm-6">

                                                        <div class="form-group">
            
                                                            <label for="cmbprdtp">Attendance Type<span class="redstar">*</span> </label>
            
                                                            <div class="form-group styled-select">
            
                                                            <select name="type" id="type" class="form-control" required>
                                                                <option value="1" <?php if ($type == 1) { echo "selected"; } ?>>Present</option>
                                                                <option value="0" <?php if ($type == 0) { echo "selected"; } ?>>Absent</option>
                                                                <option value="2" <?php if ($type == 2) { echo "selected"; } ?>>Delay</option>
            
                                                              </select>
            
                                                              </div>
            
                                                      </div>        
        
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">

                                                        <div class="form-group">
            
                                                            <label for="cmbprdtp">Early Leave<span class="redstar">*</span> </label>
            
                                                            <div class="form-group styled-select">
            
                                                            <select name="early" id="early" class="form-control" required>
                                                                <option value="0" <?php if ($early == 0) { echo "selected"; } ?>>No</option>
                                                                <option value="1" <?php if ($early == 1) { echo "selected"; } ?>>Yes</option>
            
                                                              </select>
            
                                                              </div>
            
                                                      </div>        
        
                                                </div>
                                                <div class="col-lg-12 col-md-6 col-sm-6">
                                            	                                    <label for="attachment1">Remarks</label>
                                                                                    <div class="input-group">
                                                                                       <textarea name="remarks1" class="form-control" id="remarks1" rows="4"></textarea>
                                                                                    </div>
                                                          </div>
                                                
                                                
                                        
                                                
                                            

<?php  }} ?>
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        
                                        
                                        
                                    </div>      
                                   </div>
                                </div>
                           
                        </div>
                    </div> 
        <!-- /#end of panel -->      
                    <div class="button-bar">
                            <?php if($mode==2) { ?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Attendance" id="update" >
                          <?php } else {?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Attendance" id="add" >
                          <?php } ?> 
                        <a href = "./officetimeList.php?pg=1&mod=4">
                          <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                        </a>
                    </div>        
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
include_once('common_footer.php');


//$cusid = 3;
?>
<?php // include_once('inc_cmb_loader_js.php');?>

<?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>

 <!-- Select2 JS -->
<script src="js/plugins/select2/select2.min.js"></script>

<!-- Include Toastr JS -->
<script src="js/plugins/toastr/toastr.min.js"></script>


<script>
  $(document).ready(function() {
      // Customized Toastr notification
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-top-right",
  
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
 

 
      
  
    $('.select2basic').select2();
    
    
    

    
    
    
    
  });
  
  
  
  
</script>

<script language="javascript">


<?php
if($res==4){
?>

//alert($(".cmb-parent").children("option:selected").val());

var selectedValue = $(".cmb-parent").children("option:selected").val();
	
	 $.ajax({
            type: "POST",
            url: "cmb/so_item_customer_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
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
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
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

$(document).on("change", ".cmb-parent", function() {
	
	//alert($(this).children("option:selected").val());
	//var root = $(this).parent().parent().parent().parent();	// root means .toClone
	var selectedValue = $(this).children("option:selected").val();
	
	 $.ajax({
            type: "POST",
            url: "cmb/so_item_customer_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
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
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
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
	
});	

/*  autofill combo  */

 var dataList=[];
$(".list-itemName").find("option").each(function(){dataList.push($(this).val())})

/*
//print dataList array
 $.each(dataList, function(index, value){
           $(".alertmsg").append(index + ": " + value + '<br>');
});
*/

/* Check wrong category */
var catlavel;	
var flag;
	
//$(".dl-itemName").change(function(){
$(document).on("change", ".dl-itemName", function() {
	
	
	//alert($(this).val());
	var root = $(this).parent().parent().parent().parent();
	root.find(".itemName").attr('style','border:1px solid red!important;');
	
	
	
	
	for(var i in dataList) {
		userinput = $(this).val();
	 	catlavel = dataList[i];
		
		//$(".alertmsg").append(dataList[i]+ '<br>');
		
		if(userinput === catlavel){
			flag = 1;
			
			//root.find(".itemName").val($(this).val());
			//alert($(this).attr("thisval"));
			
				var g = $(this).val();
				var id = $('#itemName option[value="' + g +'"]').attr('data-value');
			  //alert(id);
			root.find(".itemName").val(id);
			break;
		}else{
			flag = 0;
		}
	}
	if(flag == 0){
		$(this).val("");
		}
	
	});
/* end Check wrong category */	
	
/* end autofill combo  */


//clone rows


    var max_fields      = 20; //maximum input boxes allowed
    var wrapper         = $(".color-block"); //Fields wrapper
    var add_button      = $(".officetime-link-add"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper .toClone:last-child").clone().appendTo(wrapper);
    
    $( ".po-product-wrapper .toClone:last-child input").val("");
  

		if(x==2){
			$( ".po-product-wrapper .toClone:last-child").append('<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
		callTime();
        }
    });

    $(wrapper).on("click",".remove-po", function(e){ //user click on remove text
        e.preventDefault();
		$(this).parent().parent().remove(); 
		//$(this).parent().parent().parent().attr('style','border:1px solid #000');
		
		x--;
		
    })
	
	
	

//datetime definer
function callTime(){
         $('.timeonly').datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "HH:mm",
					//format: 'LT',
					keepOpen: true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-chevron-up",
                 down: "fa fa-chevron-down"
                }
            });
			//$('.timeonly').data("DateTimePicker").show();
}
callTime();
</script>

</body>
</html>
