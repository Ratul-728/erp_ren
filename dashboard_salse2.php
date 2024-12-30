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
			  
		
			
			  
			<div class="row dashbaord-filter">
			
				
				
				
				<div class="col-lg-2 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Account manager </div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col1">
								<input class="text-nicelabel" name="mm[]" value="Anayetul Islameee" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Dalouear Hossain" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Eftekhar Alom" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Rayhan Hossain" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Anayetul Islam" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Dalouear Hossain" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Eftekhar Alom" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Rayhan Hossain" type="checkbox" />							
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>
				<div class="col-lg-2 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Account manager </div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col1">
								<input class="text-nicelabel" name="mm[]" value="Anayetul Islameee" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Dalouear Hossain" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Eftekhar Alom" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Rayhan Hossain" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Anayetul Islam" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Dalouear Hossain" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Eftekhar Alom" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Rayhan Hossain" type="checkbox" />							
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>
				<div class="col-lg-2 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Account manager </div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col1">
								<input class="text-nicelabel" name="mm[]" value="Anayetul Islameee" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Dalouear Hossain" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Eftekhar Alom" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Rayhan Hossain" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Anayetul Islam" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Dalouear Hossain" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Eftekhar Alom" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Rayhan Hossain" type="checkbox" />							
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>
				<div class="col-lg-2 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Year C </div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col3">
								<input class="text-nicelabel" name="mm[]" value="2018" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="2019" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="2020" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="2000" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="2001" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="2005" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="2012" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="2010" type="checkbox" />							
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>
				<div class="col-lg-2 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Month C</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col3">
								<input class="text-nicelabel" name="mm[]" value="Jan" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Feb" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Mar" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Apr" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="May" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Jun" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="July" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Aug" type="checkbox" />							
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>
				<div class="col-lg-2 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Company Type</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col2">
								<input class="text-nicelabel" name="mm[]" value="Novotel" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Intercloud" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Novocom" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Brilliant" type="checkbox" />
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>
				<div class="col-lg-2 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Licence Type</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col3">
								<input class="text-nicelabel" name="mm[]" value="IIG" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="ITC" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="ISP" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Blank" type="checkbox" />
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>	
				<div class="col-lg-2 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Item Category</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col2">
								<input class="text-nicelabel" name="mm[]" value="Cloud" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Telephony" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Internet" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Blank" type="checkbox" />
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>	
				<div class="col-lg-2 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Status</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col2">
								<input class="text-nicelabel" name="mm[]" value="Existing" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="New" type="checkbox" />
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>	
				<div class="col-lg-2 col-md-6">
				  <div class="panel panel-default">
					<div class="panel-heading"><span class="icon-filter"><a href="#"><img src="images/icons/icon-filter.png" alt=""></a></span>
					   <div class="panel-title">Forcast</div>
					</div>
					<div class="panel-body">
					   <div class="filter-toggle-wrapper">
							<div class="text-checkbox chk-container col2">
								<input class="text-nicelabel" name="mm[]" value="Actual" type="checkbox" />
								<input class="text-nicelabel" name="mm[]" value="Forcast" type="checkbox" />
							</div>							   
					   </div>
					</div>
				  </div>					
				</div>					
			
			
			
			
			
			
			</div>
			
			<!------------>
             
			
			
			
			
			
	

           <!-- START chart row-->
             	  <div class="row">
                  
                  
                  
                  
                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart4" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Sales Order Timeline</div>
                        </div>
                        <div class="panel-body">
							<div style="margin: 20px;">
								<div id="chartContainer" style="height: 210px; width: 100%; "></div>
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
							<div style="margin: 20px;">
								<div id="accManagerPerformance" style="height: 210px; width: 100%; "></div>
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
							<div style="margin: 20px;">
								<div id="categorywiseVisual" style="height: 210px; width: 100%; "></div>
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
							<div style="margin: 20px;">
								<div id="franchiseWiseVisual" style="height: 210px; width: 100%; "></div>
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
							<div style="margin: 20px;">
								<div id="existingVSNewSales" style="height: 210px; width: 100%; "></div>
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
							<div style="margin: 20px;">
								<div id="productWiseSales" style="height: 210px; width: 100%; "></div>
							</div>
							   
                        </div>
                     </div>
                  </div>
					  
					  



                  
                  
                  
               </div>
          <!-- END  chart row-->
			
			
			
			
			
			
			
			
			
			
			
         		 <!-- START chart table-->
             	  <div class="row table-row">
                  
                  
                  
                  
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
    

	
	
	
<!-- CANVAS JS CHART-->

 <!--script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script-->
<script src="js/plugins/canvasjs/canvasjs.min232.js"></script>
	
  <script type="text/javascript">
  window.onload = function () {

	  
	  
	  
	  
	  

	  //########### CHART 1
	  
    var chart = new CanvasJS.Chart("chartContainer",
    {
		theme: "light2",
      title:{
     // text: "Coal Reserves of Countries"
      },
	legend: {
		reversed: true,
		verticalAlign: "bottom",
		horizontalAlign: "center"
	},      
        data: [
      {
        type: "stackedColumn",
		name: "New",
		showInLegend: true,        
        dataPoints: [
        {  y: 111338 , label: "Jan20"},
        {  y: 49088, label: "Feb20" },
        {  y: 62200, label: "Mar20" },
        {  y: 90085, label: "Apr20" },
        {  y: 38600, label: "May20"},
        {  y: 48750, label: "June20"}

        ]
      },  {
        type: "stackedColumn",
		name: "Existing",
		showInLegend: true,           
         dataPoints: [
        {  y: 135305 , label: "Jan20"},
        {  y: 107922, label: "Feb10" },
        {  y: 52300, label: "Mar20" },
        {  y: 3360, label: "Apr20" },
        {  y: 39900, label: "May20"},
        {  y: 0, label: "June20"}

        ]
      }
      ]
    });
chart.render();
	  
	  
	  
	  
	  
//################## CHART 2
	  
    var chart = new CanvasJS.Chart("accManagerPerformance",
    {
theme: "light2",
      data: [

        {
			
        type: "stackedBar",
        legendText: "OCT",
        showInLegend: "true",
        dataPoints: [
        {y: 50, label: "Farhad Diba" },
         {y: 40, label: "Aninda Barua" },
         {y: 20, label: "Safayet Khan" },
         {y: 10, label: "Tanim Ahmed" },

        ]
      },
        {
        type: "stackedBar",
        legendText: "MRC",
        showInLegend: "true",
        dataPoints: [
         {y: 40, label: "Farhad Diba" },
         {y: 30, label: "Aninda Barua" },
         {y: 20, label: "Safayet Khan" },
         {y: 10, label: "Tanim Ahmed" },          


        ]
      }

      ]
    });	  
	  
	  
	  
    chart.render();
	  
	  
	  
//############# CHART 3
	  
	var chart = new CanvasJS.Chart("categorywiseVisual",
	{
		theme: "light2",
	
		data: [
		{       
			type: "pie",
			showInLegend: true,
			toolTipContent: "{y} - #percent %",
			yValueFormatString: "#,##0,,.## Million",
			legendText: "{indexLabel}",
			dataPoints: [
				{  y: 4181563, indexLabel: "Internet/Data" },
				{  y: 2175498, indexLabel: "Cloud" },
				{  y: 3125844, indexLabel: "Telephony" },
				{  y: 0, indexLabel: "Blank"},
			]
		}
		]
	});
	chart.render();	  
	  
	  
	//############# CHART 4  
	  
	  
	  
   var chart = new CanvasJS.Chart("franchiseWiseVisual",
    {
	   theme: "light2",

        data: [
      {
        type: "stackedColumn",
		showInLegend: true,	
		 legendText: "OTC",
        dataPoints: [
        {  y: 111338 , label: "Intercloud"},
        {  y: 49088, label: "Novotel" },
        {  y: 62200, label: "Novocom" },


        ]
      },  {
        type: "stackedColumn",
		showInLegend: true,
		  legendText: "MRC",
         dataPoints: [
        {  y: 111338 , label: "Intercloud"},
        {  y: 49088, label: "Novotel" },
        {  y: 62200, label: "Novocom" },

        ]
      }
      ]
    });	  
	  
	 chart.render(); 
	  
//############# CHART 5 
	  
   var chart = new CanvasJS.Chart("existingVSNewSales",
    {
	   theme: "light2",

        data: [
      {
        type: "stackedColumn",
		showInLegend: true,	
		 legendText: "OTC",
        dataPoints: [
        {  y: 121338 , label: "Existing"},
        {  y: 49088, label: "New" },



        ]
      },  {
        type: "stackedColumn",
		showInLegend: true,
		  legendText: "MRC",
         dataPoints: [
        {  y: 101338 , label: "Existing"},
        {  y: 60088, label: "New" },
 
        ]
      }
      ]
    });	  
	  
	 chart.render(); 	  
	  
	  
	  
	//############# CHART 6  	  

	  
	  
	  
	  
	  
    var chart = new CanvasJS.Chart("productWiseSales",
    {
      theme: "light2",

      data: [
      {
        type: "bar",
        showInLegend: true,
         legendText: "OTC", 
        dataPoints: [
        { y: 198, label: "Internet(IC)"},
        { y: 201, label: "IAAS"},
        { y: 202, label: "Internet(NC)"},
        { y: 236, label: "BAAS"},
        { y: 395, label: "MAAS"},
        { y: 957, label: "DIA"},
        { y: 957, label: "GGC (NC)"},
        { y: 957, label: "FB"},
        ]
      },
      {
        type: "bar",
		showInLegend: true,	
		 legendText: "MRC",        
        dataPoints: [
        { y: 50, label: "Internet(IC)"},
        { y: 101, label: "IAAS"},
        { y: 102, label: "Internet(NC)"},
        { y: 136, label: "BAAS"},
        { y: 195, label: "MAAS"},
        { y: 857, label: "DIA"},
        { y: 857, label: "GGC (NC)"},
        { y: 757, label: "FB"},
        ]
      },
      
      ]
    });

chart.render();	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
  }
  </script>
	
	
<!-- END CANVAS JS CHART-->
	
	

	
	
	
	
	
	
	
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
