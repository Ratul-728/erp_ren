<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{
    header("Location: http://bithut.biz/actionBd/dummy/hr.php");
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $id= $_GET['id'];

    if ($res==1)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }
    if ($res==2)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }

    if ($res==4)
    {
        //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
        $qry="SELECT `id`, `emp_id`, `resourse_id`, `hrName`, `user_tp`, `email`, `cellNo` FROM `hr` where  active_st=1 and id= ".$id; 
        //echo $qry; die;
        if ($conn->connect_error)
        {
            echo "Connection failed: " . $conn->connect_error;
        }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        $uid=$row["id"];$emp_id=$row["emp_id"];
                        $resourse_id=$row["resourse_id"]; 
                        $hrName=$row["hrName"];  $user_tp=$row["user_tp"];
                        $email=$row["email"]; $cellNo=$row["cellNo"];
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
                        $uid='';$emp_id=''; $resourse_id='';  $hrName='';
                        $$user_tp='0'; $email=''; $cellNo='';
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'employee';
    $currPage = basename($_SERVER['PHP_SELF']);

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

<body class="form" >

<!-- Fixed navbar -->
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
<!-- Fixed navbar -->

<div id="wrapper"> 
  <!-- Sidebar -->

  <div id="sidebar-wrapper" class="mCustomScrollbar">
  
  <div class="section">
  	<i class="fa fa-group  icon"></i>
    <span>Employee User Form</span>
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
    <div class="container-fluid pagetop">
      <div class="row">
        <div class="col-lg-12">
        <p>&nbsp;</p>
        <p>&nbsp;</p>
          <!--h1 class="page-title">Customers</a></h1-->
          <p>
          <!-- START PLACING YOUR CONTENT HERE -->
<form method="post" action="common/adduser.php"  id="form1">     
          
    <div class="panel panel-info">
     <div class="panel-heading"><h1>User Information</h1></div>
     <div class="panel-body">
        <span class="alertmsg"></span>
        
        <!-- <br>
      	<p>(Field Marked * are required) </p> -->
      	
        <div class="row">
          
           	<div class="col-lg-3 col-md-6 col-sm-6">
              <div class="form-group">
                 <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>">  
                <label for="Serveyor">Employee ID </label>
                <input type="text" class="form-control" id="empcd" name="empcd" value="<?php echo $emp_id;?>">
              </div>        
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="form-group">
                <label for="Serveyor">Employee Name <span class="redstar">*</span></label>
                <input type="text" class="form-control" id="empnm" name="empnm" value="<?php echo $hrName;?>" required>
              </div>        
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="form-group">
                <label for="Serveyor">User Id</label>
                <input type="text" class="form-control" id="usrcd" name="usercd" value="<?php echo $resourse_id;?>">
              </div>        
            </div>
            
          	<div class="col-lg-3 col-md-6 col-sm-6">
              <div class="form-group">
                <label for="email">User privillaged </label>
                  <select name="cmbtp" id="cmbtp" class="form-control">
                    <option value="0">Select Privilaged</option>
<?php 
$qry1="SELECT id,TypeName FROM `UserType`";
$result1 = $conn->query($qry1); 
if ($result1->num_rows > 0)
{
    while($row1 = $result1->fetch_assoc()) 
      { 
          $tid= $row1["id"];
          $nm=$row1["TypeName"];
   
?>          
                    
                    <option value="<? echo $tid; ?>" <? if ($user_tp == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
<?php 
      }
}      
?>                    
                  </select>
              </div>        
            </div>
          	<div class="col-lg-3 col-md-6 col-sm-6"> 
              <div class="form-group">
                <label for="Head">Cell NO <span class="redstar">*</span></label>
                <input type="text" class="form-control" id="cell" name="cell" value="<?php echo $cellNo;?>" required>
              </div>        
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6"> 
              <div class="form-group">
                <label for="Head">Email IDc<span class="redstar">*</span></label>
                <input type="text" class="form-control" id="empEmail" name="empEmail" value="<?php echo $email;?>" required>
              </div>        
            </div>
            
        </div>
     </div>
    </div> 
        <!-- /#end of panel -->      
    <div class="button-bar">
          <?php if($mode==2) { ?>
          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Add User" id="update" disabled>
          <?php } else {?>
          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="submit" value="Add User" id="list" >
          <?php } ?>           
          <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Cancel"  id="cancel" >
  </div>        
 </form>              
          
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
      <div class="col-xs-10  copyright">Copyright © <a class="" href="http://www.actionaid.org/bangladesh" target="_blank">Bangladesh | ActionAid</a></div>
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

</body>
</html>
<?php }?>