<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}else{


	  

     
}
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
 <link href="js/plugins/datepicker/datepicker-0.5.2/datepicker_style.css" rel="stylesheet" type="text/css"/>



</head>
<body class="dashboard">


<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid nav-left-padding">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="dashboard.php"><img src="images/logo-bitcables.png" alt="BizGIent"></a> </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav parent-menu">
        <li class="active" > &nbsp;
          <button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="fa fa-navicon" aria-hidden="true"></span></button>
        </li>
        <?php 
				$qrysb="SELECT `id`, `Name`, `sl`,`landport` FROM `module` order by sl"; 
				$resultsb= $conn->query($qrysb);
				if ($resultsb->num_rows > 0){
					 while($rowsb = $resultsb->fetch_assoc()){ $mnsl=$rowsb["sl"]; $slnm=$rowsb["Name"]; $url1=$rowsb["landport"]."?mod=".$rowsb["id"]; ?>
        <li <?php if ($mod==$rowsb["id"]){ ?> class="active" <?php }?>><a href=<?php echo $url1;?>><?php echo $slnm;?> </a></li>
		 <?php 			 }
				}?>
        <!--<li class="active"><a href="dashboard.php">Inventory</a></li>
        <li><a href="dashboard.php">POS</a></li> 
        <li><a href="dashboard.php">HR</a></li>
        <li><a href="dashboard.php">CRM</a></li>
        <li><a href="dashboard.php">Payment</a></li> -->

        
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


             	  




        
       
        	<div id="printableArea" style="-webkit-print-color-adjust: exact !important;" >

		  		<div class="invoice-wrapper"  style="width:800px;border:2px solid #c0c0c0;padding: 50px; ">
				
			<table width="100%" align="center" border="0" class="tbl_lbl1 tbl1" cellspacing="0" cellpadding="0">
				  <tr>
					  <td width="70%"><img src="http://thm1/images/logo-bitcables.png" alt=""></td>
					  <td align="right"><h1>INVOICE</h1></td>
				  </tr>
				  <tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>				
					<tr>
					  <td>
						<div>
						 #40, Road-16, Sector-14 <br>
					    Uttara, Dhaka 1230 <br>
					    Phone: 01730311476 <br>
				        info@bithut.biz 
						</div>
					  </td>
					  <td>
					    <table cellspacing="0" class="tbl_lbl2" cellpadding="0" align="right">
					    <tr>
					      <td>Date</td>
					      <td>: 3/23/2020</td>
				        </tr>
					    <tr>
					      <td>Invoice #</td>
					      <td>: 54654645646</td>
				        </tr>
					    <tr>
					      <td>For</td>
					      <td>: SO#123456</td>
				        </tr>
					    
				      </table>
						
					  </td>
			    </tr>
		    </table>
			  <br>

			<table width="100%" border="0"  class="tbl_lbl1  tbl2" cellspacing="0" cellpadding="0">
				  <tbody>
				    <tr>
				      <th>Bill To:</th>
			        </tr>
				    <tr>
				      <td>
						  <div style="height: 5px"></div>
						  InterCloud Limited <br>
				        Company Name <br>
				        Street Address <br>
				        City, ST  ZIP Code <br>
			          (206) 555-1163</td>
			        </tr>
			      </tbody>
		    </table>
		    <br>

			<table width="100%" border="1"  class="tbl_lbl1  tbl3" cellspacing="10" cellpadding="10">
				<?php
				$padding2x5 = 'style="padding: 2px 5px;"';
				
				?>
							<tbody>
								
<!--						Start Loop here		-->
								
							  <tr>
								<th width="5%" align="center" nowrap <?=$padding2x5?>>Sl</th>
								<th  nowrap <?=$padding2x5?>>Products</th>
								<th width="10%" align="center" style="text-align: center" nowrap <?=$padding2x5?>>Qty</th>
								<th width="12%" align="center" nowrap <?=$padding2x5?>>Unit price</th>
								<th width="12%" nowrap <?=$padding2x5?>>Amount</th>
								<th width="12%" nowrap <?=$padding2x5?>>Remarks</th>
							  </tr>
							  <tr>
								<td align="center" <?=$padding2x5?>>2</td>
								<td <?=$padding2x5?>> Item Number 1</td>
								<td <?=$padding2x5?> align="center">2</td>
								<td <?=$padding2x5?>>$2.00</td>
								<td <?=$padding2x5?>>256.00</td>
								<td <?=$padding2x5?>>OTC</td>
							  </tr>
							  <tr>
								<td align="center" <?=$padding2x5?>>1</td>
								<td <?=$padding2x5?>> Item Number 1</td>
								<td <?=$padding2x5?> align="center">2</td>
								<td <?=$padding2x5?>>$2.00</td>
								<td <?=$padding2x5?>>256.00</td>
								<td <?=$padding2x5?>>OTC</td>
							  </tr>
							  <tr>
								<td align="center" <?=$padding2x5?>>3</td>
								<td <?=$padding2x5?>> Item Number 1</td>
								<td <?=$padding2x5?> align="center">2</td>
								<td <?=$padding2x5?>>$2.00</td>
								<td <?=$padding2x5?>>256.00</td>
								<td <?=$padding2x5?>>OTC</td>
							  </tr>							  

<!--						Ens Loop here		-->								
								
								
							  <tr>
								<td colspan="3" rowspan="5">&nbsp;</td>
								<td align="right" <?=$padding2x5?>><strong>Subtotal</strong></td>
								<td <?=$padding2x5?>>256.00</td>
								<td rowspan="5" <?=$padding2x5?>>&nbsp;</td>
							  </tr>								
							  
							  <tr>
								<td align="right" <?=$padding2x5?>><strong>Total</strong></td>
								<td <?=$padding2x5?>>$12.00</td>
							  </tr>
							  <tr>
								<td align="right" <?=$padding2x5?>>VAT</td>
								<td <?=$padding2x5?>>15.00%</td>
							  </tr>
							  <tr>
								<td align="right" <?=$padding2x5?>>SC</td>
								<td <?=$padding2x5?>>10%</td>
							  </tr>
							  <tr>
								<td  <?=$padding2x5?> align="right"><strong>Amount</strong></td>
								<td <?=$padding2x5?>>$15.00</td>
							  </tr>
							</tbody>
						  </table>	
					<br>
<div style="width: 300px; font-size: 12px;">					
Make all checks payable to <strong>Bithut Limited</strong>.<br>
"If you have any questions concerning this invoice, then contact Support at support@bithut.biz. "<br>
<br>
</div>	
					<strong>Thank you for your business!</strong>
	
		  </div>

             </div>	
			
<br>

<div style="padding-left: 0px;">
<input type="button" class="btn btn-lg btn-info" onclick="printDiv('printableArea')" value="&nbsp;&nbsp;&nbsp;Print&nbsp;&nbsp;&nbsp;" />	   
</div>



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
    


 <!-- FLOT CHART-->  
 <script src="js/plugins/Flot/jquery.flot.js"></script>
   <script src="js/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
   <script src="js/plugins/Flot/jquery.flot.resize.js"></script>
   <script src="js/plugins/Flot/jquery.flot.pie.js"></script>
   <script src="js/plugins/Flot/jquery.flot.time.js"></script>
   <script src="js/plugins/Flot/jquery.flot.categories.js"></script>
   <script src="js/plugins/flot-spline/js/jquery.flot.spline.min.js"></script>
   	<script src="js/demo-flot.js"></script>

  


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



<script src="js/app.js"></script>   
   
  <!-- END FLOT CHART--> 
	
<script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}			
</script>	
</body></html>
