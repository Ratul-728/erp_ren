<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<link rel="icon" href="images/favicon.png">
<title>BitFlow</title>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome4.0.7.css" rel="stylesheet">
<link href="css/fonts.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

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

 <link rel="stylesheet" href="css/app.css" id="maincss">
 <link href="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.css" rel="stylesheet" type="text/css"/>

<!-- checkbox button css -->
    <link href="js/plugins/checkbox-button/nicelabel/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
	<link href="js/plugins/checkbox-button/nicelabel/css/jquery-nicelabel.css" rel="stylesheet" type="text/css" />
<!-- end checkbox button css -->
	 
<link href="js/plugins/datepicker/datepicker-0.5.2/datepicker_style.css" rel="stylesheet" type="text/css"/>

<link href="js/plugins/nano-scrollbar/nanoscroller.css" rel="stylesheet" type="text/css"/>
	
<link href="css/style_extended.css" rel="stylesheet">
</head>
<body class="dashboard">

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid nav-left-padding">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="qv.php"><img src="images/logo-bitcables.png" alt="BizGIent"></a> </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav parent-menu">
        <li class="active" > &nbsp;
          <button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="fa fa-navicon" aria-hidden="true"></span></button>
        </li>
        <?php 
				$qrysb="SELECT  distinct d.`id`, d.`Name`, d.`sl`,d.`landport` FROM  `mainMenu` m,hrAuth a,module d  WHERE a.menuid=m.`id` and ifnull(m.isreport,0)<>1 and a.hrid=".$usr." and m.modl=d.id
 order by d.sl"; 
				$resultsb= $conn->query($qrysb);
				if ($resultsb->num_rows > 0){
					 while($rowsb = $resultsb->fetch_assoc()){ $mnsl=$rowsb["sl"]; $slnm=$rowsb["Name"]; $url1=$rowsb["landport"]."?mod=".$rowsb["id"]; ?>
        <li <?php if ($mod==$rowsb["id"]){ ?> class="active" <?php }?>><a href=<?php echo $url1;?>><?php echo $slnm;?> </a></li>
		 <?php 			 }
				}?>
      </ul>
      <ul class="nav navbar-nav navbar-right user-menu">
        <li><a href="../navbar/"><span class="fa fa-gear"></span> Setting</a></li>
        
        <li class="dropdown"> <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user-circle-o"></span> <span class="caret"></span> </a>
          <ul class="dropdown-menu">
             <li><a href="hc_char_modi.php?mod=5">Change Password</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="hr.php?res=2">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!--/.nav-collapse --> 
  </div>
</nav>
<!-- Fixed navbar -->



<div id="wrapper"> 
  <!-- Sidebar -->

  <div id="sidebar-wrapper" class="mCustomScrollbar">
  
  <div class="section">
  	<i class="fa fa-group  icon"></i>
    <span>Buiesness POS</span>
  </div>
  <?php
    include_once('menu.php');
	
?>
	<div style="height:54px;">
	</div>
    
    
  </div>
  <!-- /#sidebar-wrapper --> 
  
  
  <!-- Page Content -->
  <div id="page-content-wrapper">
    <div class="container-fluid xyz">
      <div class="row">
        <div class="col-lg-12">
        
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        
          <!--h1 class="page-title">Customers</a></h1-->
          <p>
          <!-- START PLACING YOUR CONTENT HERE -->

			  
			  <!------------>
			  
<!-- ############################## -->	
			  
			  <form name="form-dboard-filter" id="form-dboard-filter" >
			  
			 <div class="row dashbaord-filter">
				
				 <div class="col-lg-2 left-col">
				 	<div class="row">
						
						
			    <div class="col-lg-12 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Year </div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper height1">
							<div class="text-checkbox chk-container col2">
							    <?php $lyr=date("Y"); for ($x = 0; $x < 10; $x++) {$yr=$lyr-$x; ?>
								<input class="text-nicelabel" data-name="yearC"  value="<?php echo $yr; ?>" type="checkbox" />
								<?php }?>
												
							</div>							   
					   </div> 
					</div>
				  </div>					
				</div>
				<div class="col-lg-12 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Month C</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper height1">
							<div class="text-checkbox chk-container col2">
							    <?php $lmn=date("m"); for ($x = 1; $x <= $lmn; $x++) {$monthName = date("M", mktime(0, 0, 0, $x, 10)); ?>
								<input class="text-nicelabel"  data-name="monthC"  value="<?php echo $monthName; ?>" type="checkbox" />
								<?php }?>
											
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>	
				<div class="col-lg-12 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Account manager </div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col1">
		<?php $qryhrm="SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id` FROM `hr` h,`employee` e where h.`emp_id`=e.`employeecode`  order by emp_id"; 
		$resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()){ $hrnmm=$rowhrm["emp_id"];
    ?> 
								<input class="text-nicelabel"  data-name="accmngr"  value="<?php echo $hrnmm; ?>" type="checkbox" />
		<?php }}?>
												
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>
				<div class="col-lg-12 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Items</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col1">
	<?php $qryitm="SELECT `id`, `name`  FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
								<input class="text-nicelabel"  data-name="item"  value="<?php echo $nm; ?>" type="checkbox" />
		<? }} ?>
										
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>
				<div class="col-lg-12 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Organization</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col1">
			<?php $qryorg="SELECT distinct o.`id`,o.`name` FROM `contact` c,`organization` o where c.`organization`=o.`orgcode`  and c.`contacttype`=1  order by o.name"; $resultorg = $conn->query($qryorg); if ($resultorg->num_rows > 0) {while($roworg = $resultorg->fetch_assoc()){
                                                    	$tid= $roworg["id"];  $nm=$roworg["name"];
                ?>
								<input class="text-nicelabel"  data-name="org" name="or[]" value="<?php echo $nm?>" type="checkbox" />
				<?php }}?>
						
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>	
						
						 
					</div>
				 </div>
				 
				 <div class="col-lg-10">
					 
					 
				<div class="row dashbaord-filter">	 
					 
					 
					 
				
				<div class="col-lg-3 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Company Type</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper height1">
							<div class="text-checkbox chk-container col2">
<?php $qrysz="SELECT `id`, `name` FROM `sizetype`  order by name"; $resultsz = $conn->query($qrysz); if ($resultsz->num_rows > 0) {while($rowsz = $resultsz->fetch_assoc()) 
      {   $szid= $rowsz["id"];  $sznm=$rowsz["name"];
?>
                                <input class="text-nicelabel"  data-name="comtype"  value="<?php echo $sznm; ?>" type="checkbox" />
<?php }}?>                                
						
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>
				<div class="col-lg-3 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Licence Type</div>
					</div>
					<div class="panel-body">
                        <div class="text-checkbox chk-container col3">
					   <div class="filter-toggle-wrapper height1">
<?php $qrypt="SELECT `id`, `name` FROM `pattern`  order by name"; $resultpt = $conn->query($qrypt); if ($resultpt->num_rows > 0) {while($rowpt = $resultpt->fetch_assoc()) 
      { 
          $pid= $rowpt["id"];  $pnm=$rowpt["name"];
?>                             
							<input class="text-nicelabel"  data-name="licencetype"  value="<?php echo $pnm;?>" type="checkbox" />
<?php }} ?>				
						</div>							   
				   </div>
					</div>
				  </div>					
				</div>	
				<div class="col-lg-3 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Item Category</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper height1">
							<div class="text-checkbox chk-container col2">
<?php $qryitmct="SELECT `id`, `name` FROM `itmCat`  order by name"; $resultitmct = $conn->query($qryitmct); if ($resultitmct->num_rows > 0) {while($rowitmct = $resultitmct->fetch_assoc()) 
      { 
          $icid= $rowitmct["id"];  $icnm=$rowitmct["name"];
?> 
								<input class="text-nicelabel"  data-name="itemcat"  value="<?php echo $icnm;?>" type="checkbox" />
<?php }} ?>								
						
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>	
				<div class="col-lg-3 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Status</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper height1">
							<div class="text-checkbox chk-container col2">
								<input class="text-nicelabel"  data-name="status"  value="Existing" type="checkbox" />
								<input class="text-nicelabel"  data-name="status"  value="New" type="checkbox" />
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>	
	
					
				</div>
					
					 
				 <div class="clearfix"></div>
					
					 
					 
					 
<!-- START morrisjs chart css row-->	
<link rel="stylesheet" href="js/plugins/morrisjs/prettify.min.css">
<link rel="stylesheet" href="js/plugins/morrisjs/morris.css">
<!-- END morrisjs chart css row-->					 
	
				<div class="filtered-data-holder">	 
					 
		 
					 
		
				
					 </div> 
					 
					 
				 
				 </div>				 
			
			 </div> 

			</form>
			
<!-- ############################## -->			
			


 			
  
          




          <!-- START PLACING YOUR CONTENT HERE -->          
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /#page-content-wrapper -->



<!-- #page-footer -->
<div class="container-fluid">
  <div class="page_footer">
    <div class="row">
      <div class="col-xs-2"><a class="" href="http://www.bithut.biz/" target="_blank" bo><img src="images/logo_bithut_sm.png" height="30" border="0"></a></div>
      <div class="col-xs-10  copyright">Copyright © <a class="" href="http://www.bithut.biz/" target="_blank">Bithut Ltd.</a></div>
    </div>
  </div>
</div>        
<!-- /#page-footer -->



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
<script src="js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script> 
<script src="js/custom.js"></script> 
    

	
	
<script>
	$('.filtered-data-holder').load('phpajax/load_salse.php');
</script>	

	
	

	
	
<script>
	
	
//fire events of form-dboard-filter form
	
$(document).ready(function(){	


$("#form-dboard-filter input").click(function(){
var favorite = [];
$.each($("input:checked"), function(){
	favorite.push($(this).data('name') +'='+$(this).val());
});	
	
dump(favorite);	
	

function dump(obj) {
 var out = '';
 for (var i in obj) {
 out += obj[i] + "&";
 }
	//alert(out);
	
	
	
	
		var myKeyVals = { param1 : out}



		var saveData = $.ajax({
			  type: 'POST',
			  url: "phpajax/load_salse.php",
			  data: myKeyVals,
			  dataType: "text",
			  success: function(resultData) {
				  //alert("Save Complete");
				 $('.filtered-data-holder').html(resultData);
			  }
		});

	saveData.error(function() { alert("Something went wrong"); });		
	
	
}	
});		

	
});
	
	
	

	
	
</script>	

	
	
	
	
	
<!-- Date Picker  ==================================== -->
<script src="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.js"></script>
<script language="javascript">
$(document).ready(function(){
   	$( ".datepicker" ).datepicker({
	  format: 'yyyy-mm-dd'
	});
 });  
</script>
<!-- end Date Picker  ==================================== -->



   


<!-- checkbox button js -->
	<script src="js/plugins/checkbox-button/nicelabel/js/jquery.nicelabel.js"></script>
	<script>
		$(function(){
			$('.text-checkbox  input').nicelabel();
			
 			$('.icon-filter a').click(function(){
	 		$(this).parent().parent().parent().find(".panel-body input[type=checkbox]").prop('checked', false);
				//$('#text-checkbox').find('input[type=checkbox]').prop('checked', false);
	 
				});
			
		
			//remove text after one space
			
			str = $(".text-checkbox input").attr("value");
			var ret = str.split(" ");
			var str1 = ret[0];
			var str2 = ret[1];
			//alert(str2);
			//$(".text-checkbox .nicelabel-unchecked, .text-checkbox .nicelabel-checked").html(str1);
	});
		

		
		
		
	</script>
<!-- end checkbox button js -->
	

	
	
  <!-- END FLOT CHART--> 
</body></html>
<?php
}
?>
