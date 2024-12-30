<?php
	require "common/conn.php";
	session_start();



	$orid='';$name='';$contactperson='';$phone='';  $industry='';$employeesize='0';$email=''; $website=''; $area='';$street='';  $district=''; $state=''; $zip='';$country='';
	$operationstatus='';  $bsnsvalue='0';   $details='';  $salesperson='';
	
	$mode=1;//Insert mode
                    


    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'organization';
    $currPage = basename($_SERVER['PHP_SELF']);
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="Access-Control-Allow-Origin" CONTENT="https://bithut.com.bd">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<link rel="icon" href="images/favicon.png">
<title>bitBiz-Flow</title>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome4.0.7.css" rel="stylesheet">
<link href="css/fonts.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/style_extended.css" rel="stylesheet">
<link href="css/simple-sidebar.css" rel="stylesheet">



<link href="js/plugins/scrollbar/jquery.mCustomScrollbar.css" rel="stylesheet">


<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
<!--Date Time Picker CSS -->
<link href="js/plugins/datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
<!--end Date Time Picker CSS -->

<!--icheck box CSS -->
<link href="js/plugins/icheck/skins/square/blue.css" rel="stylesheet">
<!--end icheck box CSS -->

<!-- TEMPO TIME PICKER CSS-->
<!--link rel="stylesheet" href="js/plugins/timepicker-jq/dist/wickedpicker.min.css">
<link rel="stylesheet"
    href="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css"-->
 
 
<!-- CUSTOM CSS -->
<link href="css/ak-bit.css" rel="stylesheet">  


</head>

<body class="form" style="background:transparent;">
<?php //  include_once('common_top_body.php');?>

                    
              
                        <form method="post" action="https://bithut.biz/BitFlow/common/addorganization_externalform.php"  id="form1" enctype="multipart/form-data">  <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
   

                                    
<style>
	label,.alertmsg{
		color:#fff;
		font-size:20px;
	}
	input{
		border:0px!important;
		border-radius:3px!important;
	}
	
	.hideOnSubmit{
		display:none;
	}
	.alertmsg{
		text-align:center;
		display: block;
		}
</style>
                                    <div class="row">
                                        <div class="col-xs-12">
                                        	<span class="alertmsg"><?=$_REQUEST['msg']?></span>
                                        </div>
                                    </div>
                                    <div class="row <?=($_REQUEST['msg'])?'hideOnSubmit':''?>">

      		                            <div class="col-xs-12 col-sm-6 col-md-6  col-md-4">

                                        <!--div class="col-xs-12">
                                        	<h4>Contact Information</h4>
                                        </div-->
	                                    
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="cnnm">Organization  Name*</label>
                                                <input type="text" class="form-control" id="cnnm" placeholder="Organization  Name"  name="cnnm" value="<?php echo $name;?>" required>
                                            </div>        
                                        </div> <!-- Name --> 
                                        
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            <label for="cnnm">Contact Person  Name*</label>
                                                <input type="text" class="form-control" id="contname" placeholder="Name" name="contname[]">
                                            </div>
                                        </div>
  	                                    
                                        

                                        
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            <label for="cnnm">Email*</label>
                                                <input type="text" class="form-control" id="contemail" placeholder="Email" name="contemail[]">
                                            </div>
                                        </div> 
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            	<label for="cnnm">Phone*</label>
                                                <input type="text" class="form-control" id="contphone" placeholder="Phone" name="contphone[]">
                                                <input type="hidden" class="form-control" id="cmbopttype"  name="cmbopttype" value="<?=$_REQUEST['cmbopttype']?>">
                                            </div>
                                        </div>
										
										
                                         
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
                                    <?php
									//echo $mode;
                                    	$addClassName = ($mode=="1")?'link-add-po':'link-add-po-2';
									?>
        	                           <!--a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a-->
    	                            </div>
										
									<div class="col-lg-2 col-md-6 col-sm-6" style="text-align:center">					
										

											<input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Send a Meeting Request"  id="add" >

			<!--
										<a href = "http://bithut.biz/BitFlow/organizationList.php?pg=1&mod=2">
											<input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
										</a>
			-->
										 
									</div>
                                    <div class="col-lg-2 col-md-6 col-sm-6" style="text-align:center">	
                                    	<div style="fonts-size:30px; color:#fff;"><br> OR</div>
                                    </div>											
      	                               
                                    </div>
                                </div> 
    
       				
                        </form>       
                 

    





<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery.min.js"></script> 
<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/sidebar_menu.js"></script> 
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug --> 
<script src="js/ie10-viewport-bug-workaround.js"></script> 
<!-- Bootstrap core JavaScript
    ================================================== -->



<!-- Date Time Picker  ==================================== -->
<script src="js/plugins/datetimepicker/js/moment-with-locales.js"></script>
<script src="js/plugins/datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script language="javascript">
$(document).ready(function(){
   	


         $('.datepicker_history_filter').datetimepicker({
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
			
         $('.datepicker_comtype').datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "DD/MM/YYYY LT",
					//format: 'LT',
					//keepOpen:true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });	
			
         $('.datepicker').datetimepicker({
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
			
         $('.datetimepicker').datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "DD/MM/YYYY LT",
					//format: 'LT',
					//keepOpen:true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });								
      



 });  
</script>
<!-- end Date Picker  ==================================== -->


<!-- JQUERY TEMPO TIME PICKER PLUGIN -->
<link rel="stylesheet" href="js/plugins/timepicker-jq/dist/wickedpicker.min.js">
 <!-- FLOT CHART -->
 <script src="js/plugins/Flot/jquery.flot.js"></script>
   <script src="js/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
   <script src="js/plugins/Flot/jquery.flot.resize.js"></script>
   <script src="js/plugins/Flot/jquery.flot.pie.js"></script>
   <script src="js/plugins/Flot/jquery.flot.time.js"></script>
   <script src="js/plugins/Flot/jquery.flot.categories.js"></script>
   <script src="js/plugins/flot-spline/js/jquery.flot.spline.min.js"></script>
	
<!--	<script src="js/plugins/Flot/jquery.flot.barlabels.js"></script>-->
<script src="js/plugins/Flot/jquery.flot.valuelabels.min.js"></script>
<!--   	<script src="js/demo-flot.js"></script>-->
<!-- 	<script src="js/app.js"></script>   -->

<!-- scrollbar  ==================================== -->
<script src="js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script> 
<!-- end scrollbar  ==================================== -->


<!-- iCheck code for Checkbox and radio button -->
<script src="js/plugins/icheck/icheck.js"></script>
<script language="javascript">
$(document).ready(function(){
  $('input').iCheck({
  checkboxClass: 'icheckbox_square-blue',
  radioClass: 'iradio_square-blue',
  increaseArea: '20%'
});
});
</script>
<!-- end iCheck code for Checkbox and radio button -->


<script src="js/custom.js"></script>

<script>
  function CloseModal(frameElement) {
     if (frameElement) {
        var dialog = $(frameElement).closest(".modal");
        if (dialog.length > 0) {
            dialog.modal("hide");
        }
     }
}

  function GetNewCmdItem(id,value,cmbname){
	  //alert(value+" "+id+" "+cmbname);
		$('select[name='+cmbname+']').append('<option value="'+id+'" selected="selected">'+value+'</option>');	  
	}
</script>
<?php include_once('inc_cmb_loader_js.php');?>

<?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>

</body>
</html>
