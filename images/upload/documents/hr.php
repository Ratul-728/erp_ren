<?php
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");	


require "common/conn.php";

session_start();



//Attendance

if(isset($_SESSION)){

    //print_r($_SESSION);

    //die;

    $date=date('Y-m-d');

    $time = date("H:i:s");

    

    $uid = $_SESSION["user"];

          

    $qryatt = "INSERT INTO `attendance`(`hrid`, `date`, `outtime`, `type`) VALUES (".$uid.",'".$date."','".$time."', '2')";

    //echo $qryatt;die;

    $conn->query($qryatt);

    

    session_destroy();

}





/*if (isset($_SESSION))

  {



	session_destroy();

    

  }else{

	  session_start();

	  require "common/conn.php";

} */





$_SESSION["user"]='';

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





<!--style>

            /* Modal Content */

.modal-content {

  background-color:white;

  margin: auto;

  padding-bottom: 10px;

  border: 1px solid #888;

  width: 50%;

}



/* The Close Button */

.close {

  color: white;

  float: right;

  font-size: 28px;

  font-weight: bold;

}



.close:hover,

.close:focus {

  color: #000;

  text-decoration: none;

  cursor: pointer;

}

.cus-modal-header-row {

    background: #00abe3;

    padding: 10px;

    margin: 0.1px 0.2px;

    border-radius: 5px;

}

.display-span {

    text-align: center;

    font-size: 22px;

    color: white;

}

.frgt-pass{

    color: white;

    m argin-left: 200px;

    w idth: 100px;

    m argin-bottom: -10px;

  

}

.modal-row{

    padding: 20px;

    margin: 0px 5px;

}

}



.modal-content form{

    margin: 0px 10px !important;

    padding: 10px;

}

.modal-row input{

  border-radius: 0px !important;

}

.modal-content{

    padding-bottom: : 130px;

}

button.btn.btn-lg.frgt-pass-btn {

    background: #00abe3;

    color: white;

    padding: 5px 10px;

    border-radius: 0px;

   margin-left: 17px;

    

    

}

.display-span {

    text-align: center;

    font-size: 22px;

    color: white;

}

.cus-modal-header-row .display-span{

    text-align: center;

    justify-content: center;

}

.modal-content .row{

    padding: 10px;

    border-radius: 0px;

    

}

.modal-row{

    padding:0px;

}

.input-fld{

        border: 0;

    background: white;

    display: block;

    text-align: center;

    border: 2px solid #ffffff;

    padding: 14px 10px;

    width: 300px;

    height: 55px;

    outline: none;

    color: black;

    border-radius: 24px;

    transition: 0.25s;

}

.input-btn{

        background: none;

    display: block;

    margin: 20px auto;

    width: 300px;

    height: 55px;

    text-align: center;

    border: 2px solid white;

    padding: 14px 40px;

    outline: none;

    color: white;

    border-radius: 24px;

    transition: 0.25s;

    cursor: pointer;

}

 

.frgt-pass-block{

    margin: 20px;

}

.login-block{

    margin-left: -10px;

}

.login-block a, .frgt-pass-block a{

    text-decoration: none;

    color: white;

}

.frgt-pass-block {

    padding: 3px;

    margin-left: -10px;

}

a#login-btn, a#signin-btn {


font-size:12px;

}

.frgt-pass-block {

    padding: 3px;

    margin-left: -10px;

    margin-top: 40px;

}

input#frgtmail {

   

}

</style-->





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





      

<form class="fbox" method="post" action="common/user_permit.php">

  <div class="upbrand"><img src="./images/bithut-white.png" style="width: 250px; height: 50px margin-left: -12px" alt=""> </div>

        <div class = "alertmsg"></div>

        

        <div class="login-block">

        <input name="txtnm" type="text" id="txtnm" name="txtnm" class="form-control" placeholder="Username" >

 

 

        <label for="inputPassword" class="sr-only">Password</label>

        <input name="txtcd" name="txtcd" type="password" class="form-control" placeholder="Password" required id="txtcd">
        <input class="login-btn"  type="submit" name="submit"  value="Login">
        <div style="text-align:center"> <a href="javascript:void()" class="frgt-pass" id="login-btn" >Forgot Password?</a></div>

        </div>

        <div class="frgt-pass-block" style="display:none;"> 

        <!--<h4 class="frgt-block-lab">Forgot Password?</h4> -->

        <label for="fmail" class="sr-only">Email</label>

        <input name="mail" id="frgtmail" type="email" class="form-control input-fld" placeholder="Email" id="fmail">

         <button class="btn input-btn" type="button" id = "frgtpass">Submit</button>
<div style="text-align:center">
          <a href="javascript:void()" class="frgt-pass" id="signin-btn" >Log In?</a></div>

        </div>

      </form>



    </div> 





</form>





        

<!--<div id="myModal" class="modal">



 

  <div class="modal-content">

      <div class="row cus-modal-header-row">

         <span class="display-span">Forgot Password?<span class="close ">&times;</span></span> 

      </div>

    



    <div class="row modal-row">

       <div class="form-group">

            <label for="email">Enter your mail address</label>

            <input type="email" class="form-control" id="frgtmail">

          </div>

       

    </div>

  

    <button class="btn btn-lg frgt-pass-btn" type="button" id = "frgtpass">Submit</button>

   



  </div>



</div>-->

        

        

        

        

      





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

    

   

   <!-- <script>

      $('document').ready(function(){

          console.log('ready');

         var btn = document.getElementById('login-btn');

         var modal = document.getElementsByClassName('modal');

         var close = document.getElementsByClassName('close');

         console.log(close);

      btn.onclick = function(){

        modal[0].style.display="block";

          //alert("ss");

      }

       close.onclick = function(){

        modal[0].style.display="none";

         alert("ss");

      }

      

      window.onclick = function(event) {

  if (event.target == modal) {

    modal[0].style.display = "none";

  }

}

     

      });

  </script>-->

  

  <!-- Forget Password -->

  <script>

        $( "#frgtpass" ).click(function() {

            var frgtmail = $( "#frgtmail" ).val();

            

            $.ajax({

                

				

				url:"phpajax/forget_password.php",

				method:"POST",

				data:{frgtmail:frgtmail},

				

				success:function(res)

				{

				    messageAlert(res);

				}

			});

        });

  </script>

  <script>

      $(document).ready(function(){

          $('.frgt-pass-block').attr('style', 'display:none');

          $('#login-btn').click(function(){

              $('.login-block').hide();

              $('.frgt-pass-block').show();

          });

           $('#signin-btn').click(function(){

             //  alert("h");

              $('.frgt-pass-block').hide();

              $('.login-block').show();

          });

      })

  </script>

  

  <?php

   if ($res==3)

		{

		echo "<script type='text/javascript'>messageAlert('Unauthentic Attempt!!!')</script>"; 

		}

   ?>  

  </body>

</html>