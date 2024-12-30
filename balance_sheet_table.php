<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
if($usr=='')
{ 
	header("Location: ".$hostpath."/hr.php");
}
else{

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
      <a class="navbar-brand" href="dashboard_blank.php"><img src="images/logo-bitcables.png" alt="BizGIent"></a> </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav parent-menu">
        <li class="active" > &nbsp;
          <button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="fa fa-navicon" aria-hidden="true"></span></button>
        </li>
        <?php 
				$qrysb="SELECT `id`, `Name`, `sl`,`landport` FROM `module` where id=1 order by sl"; 
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


             	  
<style>

.bhbs-wrapper{}
.bhbs-header{
	text-align: center;
}
.bhbs-header{
    border-bottom: 1px solid #efefef;
}
.bhbs-header h2{font-size: 20px;margin-bottom:0;}
.bhbs-header h1{font-size: 30px;margin-top:5px;}
.tbl-bhbs-wrapper{
     border: 1px solid #efefef;
    padding: 15px;
}

.tbl-bhbs td:first-child{}
.tbl-bhbs td:last-child{width: 100px}

.tbl-bhbs td, .tbl-bhbs th{
    padding: 5px;
    border-bottom: 1px solid #efefef;
}

.tbl-bhbs tr {
    -webkit-transition: background-color 010ms linear;
    -ms-transition: background-color 100ms linear;
    transition: background-color 100ms linear;
}	
	
.tbl-bhbs tr:hover{
    background-color: #f8fbff;
}

	
	
.tbl-bhbs th{
    
    background-color: #efefef;
    font-size: 16px;
}

.tbl-bhbs td:first-child, .tbl-bhbs th{
    border-right:1px solid #efefef;
}

/* gaps */
.tbl-bhbs td.gp-1{padding-left: 30px;}
.tbl-bhbs td.gp-2{padding-left: 60px;}
.tbl-bhbs td.gp-3{padding-left: 90px;}
.tbl-bhbs td.gp-4{padding-left: 120px;}


.total-title, .total-amount{font-weight: bold;}
.total-amount{border-bottom: 3px solid #000!important;}
.last-amount{border-bottom: 1px solid #000!important;}	

.tbl-bhbs .end-parent{
    background-color: #f4e7e7;
    font-size: 16px;
}

.tbl-bhbs .end-parent .total-amount{
    border-bottom: 3px solid #000!important;
}

.tbl-bhbs .end-parent .total-amount{
    padding: 0px;
}

.tbl-bhbs .end-parent span{
    display: block;
    margin-bottom:2px!important;
    border-bottom:2px solid #111;
    padding: 5px;
}
	
.tbl-bhbs .end-parent.assets{ background-color: #cadcf8;}
.tbl-bhbs .end-parent.liabilities{ background-color: #f8caca;}
	
	
</style>

    <div class="row">
      <div class="col-lg-8 col-md-12">			  
			  
<div class="bhbs-wrapper">
		<div class="bhbs-header">
        	<h2>Renaissance Decor</h2>
			<h1>Balance Sheet</h1>
		</div>       
        	<div class="tbl-bhbs-wrapper">
				
				<table class="tbl-bhbs" width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tbody>
					<tr>
					  <th>ASSETS</th>
					  <th>Mar-16-22</th>
					</tr>
					<tr class="cur rent-assets">
					  <td class="gp-1"><strong>Current Assets</strong></td>
					  <td>&nbsp;</td>
					</tr>
					<tr class="cash-bank-balance">
					  <td class="gp-2">Cash &amp; Bank Balances</td>
					  <td>&nbsp;</td>
					</tr>
					<tr class="cash-at-bank">
					  <td class="gp-3">Cash at Bank</td>
					  <td>&nbsp;</td>
					</tr>
					<tr>
					  <td class="gp-4">Bank Name Account</td>
					  <td>&nbsp;</td>
					</tr>
					<tr>
					  <td  class="gp-4">Petty cash</td>
					  <td>2000.00</td>
					</tr>
					<tr>
					  <td  class="gp-4">Cash in Hand</td>
					  <td>5000.00</td>
					</tr>
				  
					<tr>
					  <td  class="gp-4">Check in Hand</td>
					  <td class="last-amount">1500.00</td>
					</tr>
					  
					<tr class="cash-at-bank">
					  <td  class="gp-3 total-title">Total Cash at Bank</td>
					  <td class="total-amount">20000.00</td>
					</tr>
					<tr class="cash-equivalent">
					  <td  class="gp-3">Cash Equivalent</td>
					  <td>&nbsp;</td>
					</tr>
					<tr>
					  <td class="gp-4"> Cash Equivalent</td>
					  <td>150000.00</td>
					</tr>
					<tr>
					  <td class="gp-4">Salable Goods</td>
					  <td class="last-amount">200000.00</td>
					</tr>
					  
					<tr class="cash-equivalent">
					  <td  class="gp-3 total-title">Total Cash Equivalent</td>
					  <td class="total-amount">350000.00</td>
					</tr>					  

					<tr class="cash-bank-balance">
					  <td  class="gp-2 total-title">Total Cash & Bank Balances</td>
					  <td class="total-amount">350000.00</td>
					</tr>
					<tr class="current-assets">
					  <td  class="gp-1 total-title">Total Current Assets</td>
					  <td class="total-amount">350000.00</td>
					</tr>						  
					<tr class="end-parent assets">
					  <td  class="total-title">TOTAL ASSETS</td>
					  <td class="total-amount"><span>350000.00</span></td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					</tr>


					  <th>LIABILITIES &amp; EQUITY</th>
					  <th>&nbsp;</th>
					</tr>
					<tr class="current-liabilities">
					  <td class="gp-1">Current Liabilities</td>
					  <td>&nbsp;</td>
					</tr>
					<tr class="accounts-payable">
					  <td class="gp-2"> Accounts Payable</td>
					  <td>&nbsp;</td>
					</tr>
					<tr class="govment-authority">
					  <td class="gp-3">  Payable to Govment Authority</td>
					  <td>&nbsp;</td>
					</tr>
					<tr>
					  <td class="gp-4"> Payable Suspense</td>
					  <td>2000.00</td>
					</tr>
					<tr>
					  <td  class="gp-4"> VAT Payble</td>
					  <td class="last-amount">2000.00</td>
					</tr>
					<tr class="govment-authority">
					  <td  class="gp-3 total-title">Total Govment Authority</td>
					  <td class="total-amount">40000.00</td>
					</tr>					  
					<tr class="payable-to-clients">
					  <td  class="gp-3"> Payable to Clients</td>
					  <td>&nbsp;</td>
					</tr>
				  
					<tr>
					  <td  class="gp-4"> Clients Deposit Account</td>
					  <td class="last-amount">1500.00</td>
					</tr>
					  
					<tr class="payable-to-clients">
					  <td  class="gp-3 total-title">Total Payable to Clients</td>
					  <td class="total-amount">20000.00</td>
					</tr>
					  
					  
					<tr class="against-trade">
					  <td  class="gp-3"> Payable against Trade</td>
					  <td>&nbsp;</td>
					</tr>
					<tr>
					  <td class="gp-4">  Payable against investment </td>
					  <td class="last-amount">150000.00</td>
					</tr>
					<tr class="against-trade">
					  <td  class="gp-3 total-title">Total against Trade</td>
					  <td class="total-amount">200000.00</td>
					</tr>
					  
					<tr class="accounts-payable">
					  <td  class="gp-2 total-title">Total Accounts Payable</td>
					  <td class="total-amount">350000.00</td>
					</tr>
					<tr class="current-liabilities">
					  <td  class="gp-1 total-title">Total Current Liabilities</td>
					  <td class="total-amount">350000.00</td>
					</tr>
					<tr class="end-parent liabilities">
					  <td  class="total-title">TOTAL LIABILITIES & EQUITY</td>
					  <td class="total-amount"><span>350000.00</span></td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					</tr>				
				  </tbody>
				</table>
				
			</div>

</div>
             
          
    </div>
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
<script src="js/bootstrap.min.js"></script> 
<script src="js/sidebar_menu.js"></script> 
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug --> 
<script src="js/ie10-viewport-bug-workaround.js"></script> 
<!-- Bootstrap core JavaScript
    ================================================== -->
<script src="js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script> 
<script src="js/custom.js"></script> 
    
<script>


$(".tbl-bhbs tr").mouseover(function(){
    var thisClass = $(this).attr("class");
    $("."+thisClass).css("background-color","#E6F0FF");
 	 	//$("."+thisClass).css("font-weight","bold");
  
});

$(".tbl-bhbs tr").mouseleave(function(){
    var thisClass = $(this).attr("class");
    $("."+thisClass).css("background","transparent");
 		// $("."+thisClass).css("font-weight","normal");
});
	
	
</script>

 
  




   
  <!-- END FLOT CHART--> 
</body></html>
