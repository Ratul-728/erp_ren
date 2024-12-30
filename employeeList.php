<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{
 header("Location: http://bithut.biz/actionBd/dummy/hr.php");
}

/* common codes need to place every page. Just change the section name according to section
these 2 variables required to detecting current section and current page to use in menu.
*/
$currSection = 'slum';
$currPage = basename($_SERVER['PHP_SELF']);

if ( isset( $_POST['add'] ) ) {
      header("Location: http://bithut.biz/actionBd/dummy/employee.php?res=0&msg='Insert Data'");
}
/*if ( isset( $_POST['filterServey'] ) ) {
     $filter=$_REQUEST['search'];
}
*/
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<link rel="shortcut icon" href="images/actionaid.ico" type="image/x-icon">
<title>ActionAid</title>

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




 <link href="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.css" rel="stylesheet" type="text/css"/>
 <link href="js/plugins/datepicker/datepicker-0.5.2/datepicker_style.css" rel="stylesheet" type="text/css"/>

<link href="js/plugins/icheck/skins/square/blue.css" rel="stylesheet">



</head>

<body class="list">


<!-- Fixed TOP navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid nav-left-padding">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="dashboard.php"><img src="Images/actionaid_logo.svg" alt="ActionAid"></a> </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active" > &nbsp;
          <button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="fa fa-navicon" aria-hidden="true"></span></button>
        </li>
        <li class="active"><a href="dashboard.php">Home</a></li> 
        
      </ul>
      <ul class="nav navbar-nav navbar-right user-menu">
        <li><a href="../navbar/"><span class="fa fa-gear"></span> Setting</a></li>
        
        <li class="dropdown"> <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user-circle-o"></span> <span class="caret"></span> </a>
          <ul class="dropdown-menu">
             <li><a href="#">Change Password</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="hr.php?res=2">Logout</a></li>
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
    <span>All Servey</span>
  </div>
  
  
<?php
    include_once('menu.php');
?>
  
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
  			<div class="panel-heading"><h1>All Servey</h1></div>
				<div class="panel-body">

<span class="alertmsg">
</span>
<br>
            	<form method="post" action="employeeList.php" id="form1">
        
                 <div class="well list-top-controls">
                  <div class="row border">
                    <!--<div class="col-sm-11 text-nowrap"> <span>Servey ID</span>
                      <input name="search" type="text" id="search" class="search" >
                      <button class="btn btn-default" type="submit" name="filterServey" id="addServey" ><i class="glyphicon glyphicon-search"></i></button>
                    </div>-->
                    <div class="col-sm-11 text-nowrap"> 
                         <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export" disabled >
                    </div>
                    <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                    <div class="col-sm-1">
                      <input type="submit" name="add" value="+ Create New User " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                    </div>
                  </div>
                </div>



                <div class="table-responsive">
                    <table  class="table table-striped table-hover">
                        <tr>
                          <th><a href="#">Sl</a></th>
                          <th><a href="#">Employee Id</a></th>
                          <th><a href="#">User Id</a></th>
                          <th><a href="#">Name Of Employee</a></th> 
                          <th><a href="#">Email ID</a></th>
                          <th><a href="#">Mobile</a></th>
                          <th>&nbsp;</th>
                        </tr>
<?php 

$qry="SELECT `id`, `emp_id`, `resourse_id`, `hrName`, `user_tp`, `email`, `cellNo` FROM `hr` where  active_st=1";
/*if($filter!='')
{
$qry=" ".$qry." and id= ".$filter;   
}
*/
$sl=0;
//echo $qry; die;
if ($conn->connect_error) {
   echo "Connection failed: " . $conn->connect_error;
}
else
{
       $inputData = array(
           'id' => '',
           'emp_id' => '',
           'resourse_id' => '',
           'hrName' => '',
            'email' => '',
           'cellNo' => ''
           );      


    $dbRows = 0;
    
   $result = $conn->query($qry); 
   if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) 
      { 
            $uid=$row["id"];
            $sl++;
            $seturl="employee.php?res=4&msg='Update Data'&id=".$uid;
           
 ?>                        
                        
                        <tr>
                          <td><?php echo $sl;?></td>
                          <td><?php echo $row["emp_id"];?></td>
                          <td><?php echo $row["resourse_id"];?></td>
                          <td><?php echo $row["hrName"];?></td>
                          <td><?php echo $row["email"];?></td>
                          <td><?php echo $row["cellNo"];?></td>
                          <td><a class="btn btn-info btn-xs"  href="<?php echo $seturl;?>">Edit</a></td>
                        </tr>
<?php


		$dbCols = 0;
		foreach($inputData as $key => $value)
		{

			$data[$dbRows][$key] = $row[$key];
			$dbCols++;
		}
		$dbRows++;




}
}
else {echo "error";}
}

?>
                   
                    </table>
                </div>


<?php
    include_once('pagination.php');
     $nrows=$result->num_rows;
    if($nrows<10){$maxrows=$nrows;}
    else{$maxrows=10;}
?>
                <div class="pull-left">
                    Showing 1 to <? echo $maxrows ?> of <?=$result->num_rows?> entries
                    
                    <?php
                   
                    
                    
                    
           
                    
                    
                   
                    
                    
      // echo count($data);             
                    
                    
                    
                    
                    $conn->close();
                    ?>
                    
                    
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
      <div class="col-xs-2"><a class="" href="http://www.bithut.biz/" target="_blank" bo><img src="Images/logo_bithut_sm.png" height="30" border="0"></a></div>
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

<!-- iCheck code for Checkbox and radio button --> 
<script src="js/plugins/icheck/icheck.js"></script>
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
    
    
<script src="js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script> 
<script src="js/custom.js"></script> 



</body></html>
