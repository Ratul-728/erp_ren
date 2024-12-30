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

<link href="js/plugins/icheck/skins/square/blue.css" rel="stylesheet">

<!-- Grid Status Menu -->
<link href="js/plugins/grid_status_menu/grid_status_menu.css" rel="stylesheet">
<!-- End Grid Status Menu -->

</head>
<body class="list">


<!-- Fixed TOP navbar -->
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
<!-- end of Fixed TOP navbar -->




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

  <!-- END #sidebar-wrapper --> 
  
  
  
  
  
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











          <div class="panel panel-info">
  			<div class="panel-heading"><h1>Customers</h1></div>
				<div class="panel-body">



<span class="alertmsg">
</span>
<br>


                
                
                
                <input type="button" id="showMessage" class="btn" value="Show Message by ID">
                <input type="button" onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" class="btn" value="Show Message on Click">

                <br>
                &nbsp;



            	<form method="post" action="" id="form1">
        
                 <div class="well list-top-controls">
                  <div class="row border">
                    <div class="col-sm-11 text-nowrap"> <span>Customer</span>
                      <input name="search" type="text" id="search" class="search" >
                      <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                    <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                    <div class="col-sm-1">
                      <input type="submit" name="create_customer" value="+ Create New Customer " id="create_customer" class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                    </div>
                  </div>
                </div>






                <div class="table-res ponsive filterable">
                    <table  class="table table-striped table-hover">
                        <thead>
                        <tr class="filters">
                          <th><input type="checkbox"  name="1" class="mycheckbox" value="1"></th>
                          <th><input type="text" class="form-control" placeholder="ID" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Customer Name" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Customer Address" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Contact" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Phone" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Email" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Nos. of familymember" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Area" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Collector" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Bill Day" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Collection Day" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Status" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Remarks" disabled></th>
                          <th><button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button></th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                          <td><input type="checkbox"  class="checkbox" name="1" value="1"></td>
                          <td>1</td>
                          <td>Mr. Samiul Hauqe</td>
                          <td>Haque manjil</td>
                          <td>01716123456</td>
                          <td>&nbsp;</td>
                          <td>sami.huq@gmail.com</td>
                          <td>3</td>
                          <td>Banani</td>
                          <td>Mr.a</td>
                          <td>01</td>
                          <td>15</td>
                          <td class="status status1 dropdown">
                              <div class="">
                      			<a class="bit-btn dropdown-toggle" id="menu1" type="button" data-toggle="dropdown" data-id="1">
                                	<span>
                                        Paid
                                        <span class="caret"></span>
                                    </span>
                            	</a>
                                <div class="dropdown-menu dropdown-menu-mega">
                                    <ul class="row">
                                      <li class="col-xs-6"><a href="#" class="status1">Pending</a></li>
                                      <li class="col-xs-6"><a href="#" class="status2">In Process</a></li>
                                      <li class="col-xs-6"><a href="#" class="status3">In Process</a></li>
                                      <li class="col-xs-6"><a href="#" class="status4">In Process</a></li>
                                      <li class="col-xs-6"><a href="#" class="status5">Status 5</a></li>
                                      <li class="col-xs-6"><a href="#" class="status6">Status 6</a></li>
                                      <li class="col-xs-6"><a href="#" class="status7">Status 7</a></li>
                                      <li class="col-xs-6"><a href="#" class="status8">Status 8</a></li>
                                    </ul>                                    
                                </div>
                                </div>
                                
    
                              </div>                            
                          </td>
                          <td>very good customer</td>
                          <td><a class="btn btn-info btn-xs"  href="javascript:__doPostBack('ctl00$contHld1$gridList','Edit$0')">Edit</a></td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" class="checkbox" name="1" value="1"></td>
                          <td>2</td>
                          <td>Mr. X</td>
                          <td>add1</td>
                          <td>01710000009</td>
                          <td>9377777</td>
                          <td>a@b.com</td>
                          <td>2</td>
                          <td>Gulshan</td>
                          <td>Mr.a</td>
                          <td>02</td>
                          <td>15</td>
                          <td class="status status2 dropdown">
                              <div class="">
                      			<a class="bit-btn dropdown-toggle" id="menu1" type="button" data-toggle="dropdown" data-id="2">
                                	<span>
                                        Paid
                                        <span class="caret"></span>
                                    </span>
                            	</a>
                                <div class="dropdown-menu dropdown-menu-mega">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <ul>
                                              <li><a href="#" class="status1">Pending</a></li>
                                              <li><a href="#" class="status2">In Process</a></li>
                                              <li><a href="#" class="status3">In Process</a></li>
                                              <li><a href="#" class="status4">In Process</a></li>
                                            </ul>                                    
                                        </div>
                                        <div class="col-xs-6">
                                            <ul>
                                              <li><a href="#" class="status5">Status 5</a></li>
                                              <li><a href="#" class="status6">Status 6</a></li>
                                              <li><a href="#" class="status7">Status 7</a></li>
                                              <li><a href="#" class="status8">Status 8</a></li>
                                            </ul>                                    
                                        </div>
                                        
                                    </div>
                                </div>
                                
    
                              </div>                            
                          </td>
                          <td>0</td>
                          <td><a class="btn btn-info btn-xs" href="javascript:__doPostBack('ctl00$contHld1$gridList','Edit$1')">Edit</a></td>
                        </tr>
                     </tbody>   
                    </table>

<br>
<br>
<br>
<br>
<br>

                </div>







                <div class="pull-left">
                    Showing 1 to 10 of 57 entries
                </div>
                <div class="pull-right">
                    <ul class="pagination " style="border: 0px solid #000000; margin-top: 0px;">
                      <li id="datatable3_previous" class="paginate_button previous disabled"><a tabindex="0" data-dt-idx="0" aria-controls="datatable3" href="#">Previous</a></li>
                      <li class="paginate_button active"><a tabindex="0" data-dt-idx="1" aria-controls="datatable3" href="#">1</a></li>
                      <li class="paginate_button "><a tabindex="0" data-dt-idx="2" aria-controls="datatable3" href="#">2</a></li>
                      <li class="paginate_button "><a tabindex="0" data-dt-idx="3" aria-controls="datatable3" href="#">3</a></li>
                      <li class="paginate_button "><a tabindex="0" data-dt-idx="4" aria-controls="datatable3" href="#">4</a></li>
                      <li class="paginate_button "><a tabindex="0" data-dt-idx="5" aria-controls="datatable3" href="#">5</a></li>
                      <li class="paginate_button "><a tabindex="0" data-dt-idx="6" aria-controls="datatable3" href="#">6</a></li>
                      <li id="datatable3_next" class="paginate_button next"><a tabindex="0" data-dt-idx="7" aria-controls="datatable3" href="#">Next</a></li>
                    </ul>	
                </div>


				</form>



             </div>
        </div> 
        <!-- /#end of panel -->  



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
<script language="javascript">

	$(document).ready(function(){
	  $('input').iCheck({
	  checkboxClass: 'icheckbox_square-blue',
	  radioClass: 'iradio_square-blue',
	  increaseArea: '20%'
	});


	$('input.mycheckbox').on('ifChecked', function(event){
	  $('input').iCheck('check'); 
	});
	
	$('input.mycheckbox').on('ifUnchecked', function(event){
	  $('input').iCheck('uncheck'); 
	});
});

</script>

<!-- end iCheck code for Checkbox and radio button -->
<script src="js/custom.js"></script> 

<!-- Grid Status Menu -->
<script src="js/plugins/grid_status_menu/grid_status_menu.js"></script> 
<!-- End Grid Status Menu -->

<script>


function update_grid_status_menu(thisvalue,id){
	var dealdata = { dataid:id,modulename : 'deal', colname : 'status', selectedvalue : thisvalue}
	var saveData = $.ajax({
		  type: 'POST',
		  url: "phpajax/update_grid_status_menu.php?action=changedealstatus",
		  data: dealdata,
		  dataType: "text",
		  success: function(resultData) { alert(resultData) }
	});
	saveData.error(function() { alert("Something went wrong"); });

}
</script>


</body></html>
