<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}else{

$qry="select count(id) cid,sum(`tot_family_member`) fm,sum(`tot_infant`)inf,sum(`tot_children`)tc,sum(`tot_boy`)tb,sum(`tot_girl`)tg,sum(`tot_women`)tw,sum(`tot_man`)tm,sum(`tot_sr_women`)tsw,sum(`tot_sr_man`)tsm,sum(case `has_disable_member` when 1 then 1 else 0 end )td,avg(`monthly_income`)ami,sum(case `has_family_latrin` when 1 then 1 else 0 end ) fl,sum(`tot_member_wt_profession`) tmwf,sum(case `secoend_proffession` when 1 then 1 else 0 end)tsf FROM `survey_form` where  `servey_st`=1";

//echo $qry; 

$result = $conn->query($qry); 
 while($row = $result->fetch_assoc()) 
      { 
           $fm=$row["fm"];
           $ami=$row["ami"];
           $tc=$row["tc"];
           $noservey=$row["cid"];
           $inf=$row["inf"];
           $tm=$row["tb"]+$row["tm"]+$row["tsm"];
           $tf=$row["tg"]+$row["tw"]+$row["tsw"];
           $td=$row["td"];
           $fl=$row["fl"];
           $tmwf=$row["tmwf"];
           $tsf=$row["tsf"];
           
      }
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

			  
			  
			  <!------------>
			  
<!-- ############################## -->	
			  
			  <form name="form-dboard-filter" id="form-dboard-filter">
			  
			 <div class="row dashbaord-filter">
				
				 <div class="col-lg-2 left-col">
				 	<div class="row">

						
						

						
						
			<div class="col-lg-12 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Year C </div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper height1">
							<div class="text-checkbox chk-container col2">
								<input class="text-nicelabel" data-name="yearC"  value="2018" type="checkbox" />
								<input class="text-nicelabel" data-name="yearC" value="2019" type="checkbox" />
								<input class="text-nicelabel"  data-name="yearC"  value="2020" type="checkbox" />
								<input class="text-nicelabel"  data-name="yearC"  value="2000" type="checkbox" />
								<input class="text-nicelabel"  data-name="yearC"  value="2001" type="checkbox" />
								<input class="text-nicelabel"  data-name="yearC"  value="2005" type="checkbox" />
								<input class="text-nicelabel"  data-name="yearC"  value="2012" type="checkbox" />
								<input class="text-nicelabel"  data-name="yearC"  value="2010" type="checkbox" />							
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
								<input class="text-nicelabel"  data-name="monthC"  value="Jan" type="checkbox" />
								<input class="text-nicelabel"  data-name="monthC"  value="Feb" type="checkbox" />
								<input class="text-nicelabel"  data-name="monthC"  value="Mar" type="checkbox" />
								<input class="text-nicelabel"  data-name="monthC"  value="Apr" type="checkbox" />
								<input class="text-nicelabel"  data-name="monthC"  value="May" type="checkbox" />
								<input class="text-nicelabel"  data-name="monthC"  value="Jun" type="checkbox" />
								<input class="text-nicelabel"  data-name="monthC"  value="July" type="checkbox" />
								<input class="text-nicelabel"  data-name="monthC"  value="Aug" type="checkbox" />							
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
								<input class="text-nicelabel"  data-name="accmngr"  value="Anayetul Islameee" type="checkbox" />
								<input class="text-nicelabel"  data-name="accmngr"  value="Dalouear Hossain" type="checkbox" />
								<input class="text-nicelabel"  data-name="accmngr"  value="Eftekhar Alom" type="checkbox" />
								<input class="text-nicelabel"  data-name="accmngr"  value="Rayhan Hossain" type="checkbox" />
								<input class="text-nicelabel"  data-name="accmngr"  value="Anayetul Islam" type="checkbox" />
								<input class="text-nicelabel"  data-name="accmngr"  value="Dalouear Hossain" type="checkbox" />
								<input class="text-nicelabel"  data-name="accmngr"  value="Eftekhar Alom" type="checkbox" />
								<input class="text-nicelabel"  data-name="accmngr"  value="Rayhan Hossain" type="checkbox" />							
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
								<input class="text-nicelabel"  data-name="item"  value="Anayetul Islameee" type="checkbox" />
								<input class="text-nicelabel"  data-name="item"  value="Dalouear Hossain" type="checkbox" />
								<input class="text-nicelabel"  data-name="item"  value="Eftekhar Alom" type="checkbox" />
								<input class="text-nicelabel"  data-name="item"  value="Rayhan Hossain" type="checkbox" />
								<input class="text-nicelabel"  data-name="item"  value="Anayetul Islam" type="checkbox" />
								<input class="text-nicelabel"  data-name="item"  value="Dalouear Hossain" type="checkbox" />
								<input class="text-nicelabel"  data-name="item"  value="Eftekhar Alom" type="checkbox" />
								<input class="text-nicelabel"  data-name="item"  value="Rayhan Hossain" type="checkbox" />							
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
								<input class="text-nicelabel" name="or[]" value="Jibondhara Solutions Ltd." type="checkbox" />
								<input class="text-nicelabel" name="or[]" value="Kona Software Lab Limited" type="checkbox" />
								<input class="text-nicelabel" name="or[]" value="LAUGFS Gas Bangladesh Ltd." type="checkbox" />
								<input class="text-nicelabel" name="or[]" value="M&H Telecom Limited" type="checkbox" />
								<input class="text-nicelabel" name="or[]" value="Incredix Bangladesh" type="checkbox" />
								<input class="text-nicelabel" name="or[]" value="Innovative IT" type="checkbox" />
								<input class="text-nicelabel" name="or[]" value="ITLB" type="checkbox" />
								<input class="text-nicelabel" name="or" value="Delta Brac Housing Finance Corporation Ltd (DBH)" type="checkbox" />	
								
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
								<input class="text-nicelabel"  data-name="comtype"  value="Novotel" type="checkbox" />
								<input class="text-nicelabel"  data-name="comtype"  value="Intercloud" type="checkbox" />
								<input class="text-nicelabel"  data-name="comtype"  value="Novocom" type="checkbox" />
								<input class="text-nicelabel"  data-name="comtype"  value="Brilliant" type="checkbox" />
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
					   <div class="filter-toggle-wrapper height1">
							<div class="text-checkbox chk-container col3">
								<input class="text-nicelabel"  data-name="licencetype"  value="IIG" type="checkbox" />
								<input class="text-nicelabel"  data-name="licencetype"  value="ITC" type="checkbox" />
								<input class="text-nicelabel"  data-name="licencetype"  value="ISP" type="checkbox" />
								<input class="text-nicelabel"  data-name="licencetype"  value="Blank" type="checkbox" />
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
								<input class="text-nicelabel"  data-name="itemcat"  value="Cloud" type="checkbox" />
								<input class="text-nicelabel"  data-name="itemcat"  value="Telephony" type="checkbox" />
								<input class="text-nicelabel"  data-name="itemcat"  value="Internet" type="checkbox" />
								<input class="text-nicelabel"  data-name="itemcat"  value="Blank" type="checkbox" />
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
	
					 
					 
           <!-- START chart row-->
             	  <div class="row dashbaord-filter">
                  
                  
                  
                  

					  
                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart4" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Sales Order Timeline</div>
                        </div>
                        <div class="panel-body">
							<div style="margin: 10px;">
								<div id="salesOrderTimeline" style="height: 250px; width: 100%; transform: scale(1); "></div>
							</div>
							   
                        </div>
                     </div>
                  </div>					  

                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart4" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Account Manager Performance</div>
                        </div>
                        <div class="panel-body">
							<div style="margin: 10px;">
								<div id="accManagerPerformance" style="height: 250px; width: 100%; "></div>
							</div>
							   
                        </div>
                     </div>
                  </div>
					  
					  
                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart4" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Categorywise Visual</div>
                        </div>
                        <div class="panel-body">
							<div style="margin: 10px;">
								<div id="categorywiseVisual2" style="height: 250px; width: 100%; "></div>
							</div>
							   
                        </div>
                     </div>
                  </div>					  
						  
                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart4" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Franchise Wise Visual</div>
                        </div>
                        <div class="panel-body">
							<div style="margin: 10px;">
								<div id="franchiseWiseVisual" style="height: 250px; width: 100%; "></div>
							</div>
							   
                        </div>
                     </div>
                  </div>

					  
                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart4" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Existing V SNew Sales</div>
                        </div>
                        <div class="panel-body">
							<div style="margin: 10px;">
								<div id="existingVSNewSales2" style="height: 250px; width: 100%; "></div>
							</div>
							   
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart4" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Product Wise Sales</div>
                        </div>
                        <div class="panel-body">
							<div style="margin: 10px;">
								<div id="productWiseSales" style="height: 250px; width: 100%; "></div>
							</div>
							   
                        </div>
                     </div>
                  </div>					  
					  
					  



                  
                  
                  
               </div>
          <!-- END  chart row-->					 
					 
					 
					 
		         		 <!-- START chart table-->
             	  <div class="row table-row dashbaord-filter">
                  
                  
                  
                  
					  <div class="col-lg-2 col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr>
							  <th scope="col">Month</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
							<tr>
							  <td>Jan 20</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
						</table>

					  </div>




					  <div class="col-lg-2 col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr>
							  <th scope="col">Month</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
							<tr>
							  <td>Jan 20</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
						</table>
					  </div>

					  <div class="col-lg-2  col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr>
							  <th scope="col">Month</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
							<tr>
							  <td>Jan 20</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
						</table>
					  </div>                

					  <div class="col-lg-2 col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr>
							  <th scope="col">Month</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
							<tr>
							  <td>Jan 20</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
						</table>
					  </div> 
					  <div class="col-lg-2 col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr>
							  <th scope="col">Month</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
							<tr>
							  <td>Jan 20</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
						</table>
					  </div>	
					  <div class="col-lg-2 col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr>
							  <th scope="col">Month</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
							<tr>
							  <td>Jan 20</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
						</table>
					  </div> 						  


                  
                  
                  
               </div>
               <!-- END chart table-->				 
					 
					 
				
					 
					 
					 
				 
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
    

	
	
	

	
	
<!-- END CANVAS JS CHART-->
	
	
<!-- morrisjs CHART-->
<script src="js/plugins/morrisjs/morris.js"></script>
<script src="js/plugins/morrisjs/raphael-min.js"></script>	
<script src="js/plugins/morrisjs/prettify.min.js"></script>

	
<script language="javascript">
window.onload = $(function () {	

	
	
Morris.Bar({
  element: 'salesOrderTimeline',
  data: [
    {x: 'Jan', y: 30000, z: 20000},
    {x: 'Feb', y: 30000, z: 20000},
    {x: 'Mar', y: 30000, z: 20000},
    {x: 'Apr', y: 30000, z: 10000},
	{x: 'May', y: 30000, z: 20000},
    {x: 'Apr', y: 30000, z: 20000},
  ],
	xkey: 'x',
	ykeys: ['y', 'z'],
	labels: ['Existing', 'New'],
	horizontal: false,
	stacked: true,
	
	resize: true,
	redraw: true,
	behaveLikeLine: true,
	pointFillColors:['#ffffff'],
	pointStrokeColors: ['black'],
	lineColors:['gray','red']	
});		

	
	
Morris.Bar({
  element: 'franchiseWiseVisual',
  data: [
    {company: 'Intercloud', otc: 300000, mrc: 200000},
    {company: 'Novotel', otc: 300000, mrc: 300000},
    {company: 'Novocom', otc: 100000, mrc: 200000},
  ],
	xkey: 'company',
	ykeys: ['otc', 'mrc'],
	labels: ['OTC', 'MRC'],
	horizontal: false,
	stacked: true,
	
	resize: true,
	redraw: true,
	behaveLikeLine: true,
	pointFillColors:['#ffffff'],
	pointStrokeColors: ['black'],
	lineColors:['gray','red']	
});	


Morris.Bar({
  element: 'categorywiseVisual2',
  data: [
    {service: 'Cloud', otc: 300000, mrc: 200000},
    {service: 'Telephony', otc: 300000, mrc: 300000},
    {service: 'Internet/Data', otc: 100000, mrc: 200000},
	{service: 'PBX', otc: 50000, mrc: 5000},
	  {service: 'BAAS', otc: 20000, mrc: 9000},
  ],
	xkey: 'service',
	ykeys: ['otc', 'mrc'],
	labels: ['OTC', 'MRC'],
	horizontal: true,
	stacked: true,
	
	resize: true,
	redraw: true,
	behaveLikeLine: true,
	pointFillColors:['#ffffff'],
	pointStrokeColors: ['black'],
	lineColors:['gray','red']	
});	
	
	
	

	

	
/*Morris.Pie({
  element: 'categorywiseVisual2',
  data: [
    {value: 4181563, label: 'Cloud'},
    {value: 2175498, label: 'Telephony'},
    {value: 5, label: 'Blank'},
    {value: 3125844, label: 'Internet/Data'},
  ],
  formatter: function (x) { return x + "%"},
	resize: true,
	showLabel: true,
	
});

*/
	
	
	
	
Morris.Bar({
  element: 'accManagerPerformance',
  data: [
    {x: 'Shahid', y: 30, z: 20},
    {x: 'Samiul', y: 20, z: 20},
    {x: 'Mamun', y: 10, z: 20},
    {x: 'Rashed', y: 20, z: 40}
  ],
  xkey: 'x',
  ykeys: ['y', 'z'],
  labels: ['OTC', 'MRC'],
  horizontal: true,
  stacked: true,
resize: true,	
});
	
	


Morris.Bar({
  element: 'existingVSNewSales2',
  data: [
    {x: 'Existing', otc: 40000, mrc: 30000},
    {x: 'New', otc: 20000, mrc: 20000},
  ],
	xkey: 'x',
	ykeys: ['otc', 'mrc'],
	labels: ['OTC', 'MRC'],
	horizontal: false,
	stacked: true,
	
	resize: true,
	redraw: true,
	behaveLikeLine: true,
	pointFillColors:['#ffffff'],
	pointStrokeColors: ['black'],
	lineColors:['gray','red']	
});		
	

	
//event fire;
	

	

// chart 6
var day_data = [
  {"period": "FB", "licensed": 6000, "mrc": 7000},
  {"period": "GGC (NC)", "otc": 5000, "mrc": 629},
  {"period": "DIA", "otc": 3269, "mrc": 618},
  {"period": "MAAS", "otc": 3246, "mrc": 661},
  {"period": "BAAS", "otc": 3257, "mrc": 667},
  {"period": "Intercloud (NC)", "otc": 3248, "mrc": 627},
  {"period": "IAAS", "otc": 3171, "mrc": 660},
  {"period": "Intercloud (IC)", "otc": 3171, "mrc": 676},
];
Morris.Bar({
  element: 'productWiseSales',
  data: day_data,
  xkey: 'period',
  ykeys: ['otc', 'mrc'],
  labels: ['OTC', 'MRC'],
	horizontal: true,
	resize: true,
  xLabelAngle: 60
});	
	
	
	

	
});	
	
</script>
<!-- END morrisjs CHART-->
<script>
    
    function chart_generate(out){
    var chartdata={};    
	//var dealdata = { dataid:id,stageid: stage_id, modulename : 'deal', colname : 'stage', selectedvalue : thisvalue}
	var salesdata = { datastr:out}
	 saveData = $.ajax({
        type: 'POST',
		  url: "phpajax/dashboard/sales_data.php?action=sales",
		  data: salesdata,
		  dataType: "text",
		  success: function(resultData) { messageAlert(resultData) }
	});
	saveData.error(function() { messageAlert("Something went wrong"); });
//alert(out);
}
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
 chart_generate(out);
//alert(out);
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
