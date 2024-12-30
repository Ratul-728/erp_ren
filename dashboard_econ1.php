<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<link rel="icon" href="images/favicon.png">
<title>bitCable</title>

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
	
	
	
	
 <link rel="stylesheet" href="css/app.css" id="maincss">

</head>
<body class="dashboard dashboard2">


<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid nav-left-padding">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="#"><img src="images/logo-bitcables.png" alt="bitcables"></a> </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active" > &nbsp;
          <button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="fa fa-navicon" aria-hidden="true"></span></button>
        </li>
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#contact">Contact</a></li>
        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li class="dropdown-header">Nav header</li>
            <li><a href="#">Separated link</a></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right user-menu">
        <li><a href="../navbar/"><span class="fa fa-gear"></span> Setting</a></li>
        
        <li class="dropdown"> <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user-circle-o"></span> <span class="caret"></span> </a>
          <ul class="dropdown-menu">
            <li><a href="#">Account</a></li>
            <li><a href="#">Change Password</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Logout</a></li>
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
    <span>Customers</span>
  </div>
  
  
        <ul class="sidebar-nav nav-pills nav-stacked" id="menu">

      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-dashboard fa-stack-1x "></i></span> Dashboard</a></li>
      
      <li  class="active"> <a href="ChannelList.aspx"><span class="fa-stack fa-lg pull-left"><i class="fa fa-youtube-play fa-stack-1x "></i></span>Channels  <i class="arrow fa fa-angle-down"></i></a>
        <ul class="nav-pills nav-stacked" st yle="list-style-type:none;">
          <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-plus fa-stack-1x "></i></span>Add New</a></li>
          <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-th-list fa-stack-1x "></i></span>All Channels</a></li>
          <li  class="active"><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-upload fa-stack-1x "></i></span>Bulk Upload   <i class="arrow fa fa-angle-down"></i></a>
		  
				<ul class="nav-pills nav-stacked" st yle="list-style-type:none;">
				  <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-plus fa-stack-1x "></i></span>Add New</a></li>
				  <li><a href="#" class="current"><span class="fa-stack fa-lg pull-left"><i class="fa fa-th-list fa-stack-1x "></i></span>All Channels <i class="fa fa-angle-right"></i></a></li>
				  <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-upload fa-stack-1x "></i></span>Bulk Upload</a></li>
				</ul>  		  
		  
		  </li>
        </ul>      
      </li>      
      
      <li> <a href="pakageList.aspx"><span class="fa-stack fa-lg pull-left"><i class="fa fa-dropbox fa-stack-1x "></i></span> Pakages  <i class="arrow fa fa-angle-down"></i></a>
        <ul class="nav-pills nav-stacked" style="list-style-type:none;">
          <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-plus fa-stack-1x "></i></span>Add New    <i class="arrow fa fa-angle-down"></i></a>
		  
				<ul class="nav-pills nav-stacked" st yle="list-style-type:none;">
				  <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-plus fa-stack-1x "></i></span>Add New</a></li>
				  <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-th-list fa-stack-1x "></i></span>All Channels</a></li>
				  <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-upload fa-stack-1x "></i></span>Bulk Upload</a></li>
				</ul>  		  
		  
		  
		  
		  </li>
          <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-th-list fa-stack-1x "></i></span>All Pakages</a></li>
          <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-upload fa-stack-1x "></i></span>Bulk Upload</a></li>
        </ul>       
      </li>

      

      <li><a href="customerList.aspx"><span class="fa-stack fa-lg pull-left"><i class="fa fa-group fa-stack-1x "></i></span> Customers  <i class="arrow fa fa-angle-down"></i></a>
        <ul class="nav-pills nav-stacked" style="list-style-type:none;">
          <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-plus fa-stack-1x "></i></span>Add New</a></li>
          <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-th-list fa-stack-1x "></i></span>All Customers</a></li>
          <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-upload fa-stack-1x "></i></span>Bulk Upload</a></li>
        </ul>
      </li>
      


      <li><a href="productList.aspx"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cart-plus fa-stack-1x "></i></span>Payment   <i class="arrow fa fa-angle-down"></i></a>
      
        <ul class="nav-pills nav-stacked" style="list-style-type:none;">
          <li><a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-plus fa-stack-1x "></i></span>Add New</a></li>
        </ul>
        
              
      </li>
      

      
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-bar-chart fa-stack-1x "></i></span>Report</a> </li>
      
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-support fa-stack-1x "></i></span>Help Guide</a> </li>
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-support fa-stack-1x "></i></span>Help Guide</a> </li>
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-support fa-stack-1x "></i></span>Help Guide</a> </li>
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-support fa-stack-1x "></i></span>Help Guide</a> </li>
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-support fa-stack-1x "></i></span>Help Guide</a> </li>
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-support fa-stack-1x "></i></span>Help Guide</a> </li>
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-support fa-stack-1x "></i></span>Help Guide</a> </li>
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-support fa-stack-1x "></i></span>Help Guide</a> </li>
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-support fa-stack-1x "></i></span>Help Guide</a> </li>
      <li> <a href="#"><span class="fa-stack fa-lg pull-left"><i class="fa fa-support fa-stack-1x "></i></span>Help Guide</a> </li>
 

    </ul>

	<div style="height:54px;">
	</div>
    
    
  </div>
  <!-- /#sidebar-wrapper --> 
  
  
  
  
  
  <!-- Page Content -->
  <div id="page-content-wrapper">
    <div class="container-fluid pagetop">
      <div class="row">
        <div class="col-lg-12">
        
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        
          <!--h1 class="page-title">Customers</a></h1-->
          <p>
			  
          <!-- START PLACING YOUR CONTENT HERE -->

			  
			  
<!--			 filter -->


			  
			  
    <div class="row  dashbaord-filter b">
        <div class="col-lg-7 col-md-7 col-sm-12 ">
            <div class="row  dashbaord-filter">
                <div class="col-lg-4 col-md-4 col-sm-4 column">
						<div class="form-group">
						  <div class="form-group styled-select">
							<select name="cmbindtype" id="cmbindtype" class="form-control" required="">
							  <option value="">Filter 1</option>
							  <option value="8">Airlines</option>
							  <option value="63">Architectural firm</option>
							  <option value="19">Audit</option>
							  <option value="6">Banking</option>
							  <option value="36">BPO and Consultancy</option>
							  <option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>
							</select>
						  </div>
						</div>					
				</div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
						<div class="form-group">
						  <div class="form-group styled-select">
							<select name="cmbindtype" id="cmbindtype" class="form-control" required="">
							  <option value="">Filter 2</option>
							  <option value="8">Airlines</option>
							  <option value="63">Architectural firm</option>
							  <option value="19">Audit</option>
							  <option value="6">Banking</option>
							  <option value="36">BPO and Consultancy</option>
							  <option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>
							</select>
						  </div>
						</div>				
				</div>
				
                <div class="col-lg-4 col-md-4 col-sm-4  column">

						<div class="form-group">
						  <div class="form-group styled-select">
							<select name="cmbindtype" id="cmbindtype" class="form-control" required="">
							  <option value="">Filter 3</option>
							  <option value="8">Airlines</option>
							  <option value="63">Architectural firm</option>
							  <option value="19">Audit</option>
							  <option value="6">Banking</option>
							  <option value="36">BPO and Consultancy</option>
							  <option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>
							</select>
						  </div>
						</div>					
				
				</div>				
				
            </div>
			
			
        </div>
        <div class="col-lg-5 col-md-5  col-sm-12">
            <div class="row  dashbaord-filter">

                <div class="col-lg-5 col-md-6 col-sm-6 column">
				
					<div class="input-group">
						<input type="text" class="form-control datepicker dt-input" id="chqdt" name="chqdt1" value="">
						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
					</div>				
					
				</div>
                <div class="col-lg-5 col-md-6 col-sm-6  column">
					<div class="input-group">
						<input type="text" class="form-control datepicker dt-input" id="chqdt2" name="chqdt" value="">
						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
					</div>	
				</div>
				
                <div class="col-lg-2 col-md-6 col-sm-6  column">
					<div class="input-group">
						<input type="button" class="btn btn-xs form-control"   value="Filter">
						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
					</div>	
				</div>				
				
            </div>
        </div>
    </div>			  
<!--	<hr class="hr-db-filter">	-->
	
			
			
<!--6 widgets box-->
			
<div class="row  dashbaord-filter b">
        <div class="col-lg-6 col-md-12">
            <div class="row  dashbaord-filter">
                <div class="col-lg-4 col-md-4 col-sm-4 column">

                  <!-- START widget-->
                  <div class="panel widget widget2 bg-white shadow">
                     <div class="row row-table">
                      <!--  <div class="col-xs-4 text-center bg-white-dark pv-lg">
                           <em class="fa fa-group fa-3x"></em>
                        </div> -->
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Customers</div>
                           <div class="h2 mt5">200</div>
                       <!--    <div class="text-uppercase"><span class="prtcnt color-green"><i class="fa fa-arrow-up"></i> 20%</span> Since last month</div> -->
                        </div>
                     </div>
                  </div>					
					
				</div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                  <div class="panel widget widget2 bg-white">
                     <div class="row row-table">
                       <!-- <div class="col-xs-4 text-center bg-white-dark pv-lg">
                           <em class="fa fa-shopping-basket fa-3x"></em>
							
                        </div> --> 
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Customers</div>
                           <div class="h2 mt5">200</div>
                         <!--  <div class="text-uppercase"><span class="prtcnt color-green"><i class="fa fa-arrow-up"></i> 20%</span> Since last month</div> -->
                        </div>
                     </div>
                  </div>			
				</div>
				
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                  <div class="panel widget widget2 bg-white">
                     <div class="row row-table">
                       <!-- <div class="col-xs-4 text-center bg-white-dark pv-lg">
                           <em class="fa fa-shopping-basket fa-3x"></em>
							
                        </div> -->
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Customers</div>
                           <div class="h2 mt5">200</div>
                         <!--  <div class="text-uppercase"><span class="prtcnt color-green"><i class="fa fa-arrow-up"></i> 20%</span> Since last month</div> -->
                        </div>
                     </div>
                  </div>			
				</div>				
				
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="row  dashbaord-filter">

                <div class="col-lg-4 col-md-4 col-sm-4  column">
				
                  <!-- START widget-->
                  <div class="panel widget widget2 bg-white">
                     <div class="row row-table">
                       <!-- <div class="col-xs-4 text-center bg-white-dark pv-lg">
                           <em class="fa fa-road fa-3x"></em>
			
                        </div> -->
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Customers</div>
                           <div class="h2 mt5">200</div>
                       <!--    <div class="text-uppercase"><span class="prtcnt color-green"><i class="fa fa-arrow-up"></i> 20%</span> Since last month</div> -->
                        </div>
                     </div>
                  </div>				
					
				</div>
				
                <div class="col-lg-4 col-md-4 col-sm-4  column">

                  <!-- START widget-->
                  <div class="panel widget widget2 bg-white">
                     <div class="row row-table">
                       <!-- <div class="col-xs-4 text-center bg-white-dark pv-lg">
                           <em class="fa fa-building fa-3x"></em>
							
                        </div> -->
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Customers</div>
                           <div class="h2 mt5">200</div>
                         <!--  <div class="text-uppercase"><span class="prtcnt color-green"><i class="fa fa-arrow-up"></i> 20%</span> Since last month</div> -->
                        </div>
                     </div>
                  </div>				
				
				</div>
				
				
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                  <div class="panel widget widget2 bg-white">
                     <div class="row row-table">
                       <!-- <div class="col-xs-4 text-center bg-white-dark pv-lg">
                           <em class="fa fa-users fa-3x"></em>
                        </div> -->
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Customers</div>
                           <div class="h2 mt5">200</div>
                        <!--   <div class="text-uppercase"><span class="prtcnt color-red"><i class="fa fa-arrow-down"></i> 20%</span> Since last month</div> -->
                        </div>
                     </div>
                  </div>	
				</div>
            </div>
        </div>
    </div>
			
<!-- END widgets box-->         
        



              <!-- START chart row-->

               
               
               <!-- START row-->
               <div class="row  dashbaord-filter">
                  <div class="col-lg-4  col-md-6">
                     <div id="panelChart5" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Package wise Customer Count for Current Month</div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-pie flot-chart"></div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart6" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Donut
                              <small>(loaded from json)</small>
                           </div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-donut flot-chart"></div>
                        </div>
                     </div>
                  </div>
                  
					<div class="col-lg-4 col-md-6">
                     <div id="panelChart7" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Bar - Stacked</div>
                        </div>
                        <div class="panel-body">
                           <div class="indicator show">
                              <span class="spinner"></span>
                           </div>
                           <div class="chart-bar-stacked flot-chart"></div>
                        </div>
                     </div>
                  </div> 
				   
					<div class="col-lg-4 col-md-6">
                     <div id="panelChart8" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Bar - Chart</div>
                        </div>
                        <div class="panel-body">
                           <div class="indicator show">
                              <span class="spinner"></span>
                           </div>
                           <div class="chart-bar flot-chart"></div>
                        </div>
                     </div>
                  </div>
				   
					<div class="col-lg-4 col-md-6 col-sm-6">
                     <div id="panelChart3" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Bar - Chart</div>
                        </div>
                        <div class="panel-body">
                           <div class="indicator show">
                              <span class="spinner"></span>
                           </div>
                           <div class="chart-bar-horz flot-chart"></div>
                        </div>
                     </div>
                  </div>
				   
                  <div class="col-lg-4  col-md-6">
                     <div id="panelChart5" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Package wise Customer Count for Current Month</div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-pie2 flot-chart"></div>
                        </div>
                     </div>
                  </div>
				   
                  <div class="col-lg-12  col-md-12">
                     <div id="panelChart5" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Line Chart</div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-line flot-chart"></div>
                        </div>
                     </div>
                  </div>				   
                  
                                 
               </div>
               <!-- END  chart row-->



















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


 <!-- FLOT CHART-->  
 <script src="js/plugins/Flot/jquery.flot.js"></script>
   <script src="js/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
   <script src="js/plugins/Flot/jquery.flot.resize.js"></script>
   <script src="js/plugins/Flot/jquery.flot.pie.js"></script>
   <script src="js/plugins/Flot/jquery.flot.time.js"></script>
   <script src="js/plugins/Flot/jquery.flot.categories.js"></script>
   <script src="js/plugins/flot-spline/js/jquery.flot.spline.min.js"></script>
	
	<script src="js/plugins/Flot/jquery.flot.barlabels.js"></script>
   	<script src="js/demo-flot.js"></script>
<!-- 	<script src="js/app.js"></script>   -->
   
  <!-- END FLOT CHART--> 



</body></html>
