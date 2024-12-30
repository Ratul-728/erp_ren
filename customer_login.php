<?php

if (isset($_SESSION))
  {
	session_destroy();
    
  }else{
	  session_start();
	  require "common/conn.php";
}


$_SESSION["customer"]='';
$res= $_GET['res'];

?>
<!doctype html>
<html lang="en">
  <head>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->





    <link rel="icon" href="images/favicon.png">

    <title>bitflow</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <link href="css/font-awesome4.0.7.css" rel="stylesheet">
    <link href="css/fonts.css" rel="stylesheet">
    
    <!-- <link href="css/newstyle.css" rel="stylesheet"> -->
   <link href="css/newsignin.css" rel="stylesheet">
   
    <!-- COMMENT OUT 
    
	<link href="css/st yle.css" rel="stylesheet">

    <!-- Custom styles for this template -->
   <!--
    <link href="css/signin.css" rel="stylesheet">
    
    -->

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<link href="js/plugins/icheck/skins/square/blue.css" rel="stylesheet">


  </head>

  <body class="login">

    
   <!-- Fixed navbar -->
   <!--
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid nav-left-padding">
    <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img src="http://almas.bithut.biz/dev/assets/images/logo.png" alt="AlmasOnline"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">


          </ul>
          <ul class="nav navbar-nav navbar-right">
           
            
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
   <!-- Fixed navbar -->




<br>
<br>
<br>
<br>
<br>



   
        

      
<form class="fbox" method="post" action="common/customer_permit.php">
  <div class="upbrand"><img src="./images/bithut-white.png" style="width: 250px; height: 50px margin-left: -12px" alt=""> </div>


        
        <input name="txtnm" type="text" id="txtnm" name="txtnm" class="form-control" placeholder="Username" >
        
        
        <label for="inputPassword" class="sr-only">Password</label>
        <input name="txtcd" name="txtcd" type="password" class="form-control" placeholder="Password" required id="txtcd">
        
        <input type="submit" name="submit"  value="Login">
      </form>

    </div> 


</form>


        

        
        
        
        
       <!-- 
        <div class="container">

      <form class="form-signin" method="post" action="common/user_permit.php" >
		  <span class="alertmsg"></span>
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Email address</label>

        
        <input name="txtnm" type="text" id="txtnm" name="txtnm" class="form-control" placeholder="User Name" required autofocus>
        
        <label for="inputPassword" class="sr-only">Password</label>
        <input name="txtcd" name="txtcd" type="password" class="form-control" placeholder="Password" required id="txtcd">
        <div class="checkbox">
          
            <input type="checkbox" value="remember-me">&nbsp;&nbsp;&nbsp;Remember me
          
        </div>
        <button class="btn btn-lg btn-info btn-block" type="submit" name="submit"> Sign in</button>
      </form>

    </div> <!-- /container -->


<!--

<div class="footer-left-pad">
</div>

<!-- #page-footer 
<div class="container-fluid">
  <div class="page_footer">
    <div class="row">
      <div class="col-xs-2"><a class="" href="http://www.bithut.biz/" target="_blank" bo><img src="images/logo_bithut_sm.png" height="30" border="0"></a></div>
      <div class="col-xs-10  copyright">Copyright © <a class="" href="http://www.almas.bithut.biz" target="_blank">Almas | Online</a></div>
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
    });
    </script>
    <!-- end iCheck code for Checkbox and radio button -->    
	  <script type='text/javascript' src="js/custom.js"></script>
   <?php
   if ($res==3)
		{
		echo "<script type='text/javascript'>messageAlert('Unauthentic Attempt!!!')</script>"; 
		}
   ?>    
  </body>
</html>