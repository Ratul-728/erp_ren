<?php
header("Cache-Control: no-cache");
header("Pragma: no-cache");

require "common/conn.php";

session_start();


// Fetch the current maintenance mode status and message
$sql = "SELECT is_shutdown, maintenance_message FROM shutdown WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $is_shutdown = $row['is_shutdown'];
    $maintenance_message = $row['maintenance_message'];
} 


//echo $is_shutdown;die;


// Get the user's IP address
$user_ip = $_SERVER['REMOTE_ADDR'];

// Prepare and execute the SQL query
$sql = "SELECT * FROM ip_allowed WHERE ip_allowed = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("s", $user_ip);

if (!$stmt->execute()) {
    die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
}

$result = $stmt->get_result();

// Check if the IP address exists in the table
if ($result->num_rows > 0) {
    $isValidIP = true;
} else {
    $isValidIP = false;
}

// Close the connection
$stmt->close();
//$conn->close();



//Attendance
if (isset($_SESSION)) {

  //print_r($_SESSION);

  //die;

  $getdate = new DateTime('now', new DateTimeZone('Asia/dhaka'));
  $date = $getdate->format('Y-m-d');
  $time = $getdate->format("H:i:s");



  $uid = $_SESSION["user"];

  if ($uid) {

    $qryatt = "INSERT INTO `attendance`(`hrid`, `date`, `outtime`, `type`) VALUES (" . $uid . ",'" . $date . "','" . $time . "', '2')";

    //echo $qryatt;die;

    $conn->query($qryatt);



    session_destroy();
  }
}







$_SESSION["user"] = '';

$res = $_GET['res'];



?>

<!doctype html>

<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="images/favicon.png">
  <title>bitflow</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <link href="css/font-awesome4.0.7.css" rel="stylesheet">
  <link href="css/fonts.css" rel="stylesheet">
  <link href="css/newsignin.css" rel="stylesheet">



<style>
  
body {
margin: 0;
	padding: 0;
	font-family: sans-serif;
	background-image: linear-gradient(
		100deg,
		var(--theme),
		var(--reverse)
	); 
	background-size: cover;
}

</style>
</head>



<body class="login" cz-shortcut-listen="true">





<?php

//check IP address
if($isValidIP){


//check shutdown status
if($is_shutdown == 0)
{
  ?>



    <form class="fbox" method="post" action="common/user_permit.php">

      <div class="upbrand"><img src="./images/logo_rdl_home.png" style="width: 250px; height: 50px margin-left: -12px" alt=""><!--img src="./images/bithut-white.png" style="width: 250px; height: 50px margin-left: -12px" alt=""--> </div>

      <div class="alertmsg"></div>



      <div class="login-block">

        <input name="txtnm" type="text" id="txtnm" name="txtnm" class="form-control" placeholder="Username">





        <label for="inputPassword" class="sr-only">Password</label>

        <input name="txtcd" name="txtcd" type="password" class="form-control" placeholder="Password" required id="txtcd">
        <input class="login-btn" type="submit" name="submit" value="Login">
        <div style="text-align:center"> <a href="javascript:void()" class="frgt-pass" id="login-btn">Forgot Password?</a></div>

      </div>

      <div class="frgt-pass-block" style="display:none;">

        <!--<h4 class="frgt-block-lab">Forgot Password?</h4> -->

        <label for="frgtmail" class="sr-only">Email</label>

        <input name="mail" id="frgtmail" type="email" class="form-control input-fld" placeholder="Email">

        <button class="btn input-btn" type="button" id="frgtpass">
          <span id="loadingIcon" style="display: none;">Loading...</span>
          Submit
        </button>
        <div style="text-align:center">
          <a href="javascript:void()" class="frgt-pass" id="signin-btn">Log In?</a>
        </div>

      </div>

    </form>


  <?php
}else{
  ?>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .maintenance-mood {
            padding: 20px;
            background-color: #afafaf;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
            color: #333;
            max-width: 60%;
        }
    </style>
  <div class="maintenance-mood"><?= $maintenance_message?></div>

<?php
}

}else{
  ?>
  <style>
      body, html {
          height: 100%;
          margin: 0;
          display: flex;
          justify-content: center;
          align-items: center;
      }

      .maintenance-mood {
          padding: 20px;
          background-color: #afafaf;
          border-radius: 5px;
          text-align: center;
          font-size: 18px;
          color: #333;
          max-width: 60%;
      }
  </style>
<div class="maintenance-mood">Your IP address does not have permission to access this system</div>

<?php
}
?>





  <!-- Bootstrap core JavaScript

    ================================================== -->

  <!-- Placed at the end of the document so the pages load faster -->

  <script src="js/jquery.min.js"></script>

  <script>
    window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')
  </script>

  <script src="js/bootstrap.min.js"></script>



  <!-- Bootstrap core JavaScript

    ================================================== -->





  <script type='text/javascript' src="js/custom.js"></script>







  <!-- Forget Password -->

  <script>
    $("#frgtpass").click(function() {

      var frgtmail = $("#frgtmail").val();
      $.ajax({
        url: "phpajax/forget_password.php",
        method: "POST",
        data: {
          frgtmail: frgtmail
        },

        success: function(res)
        {
          messageAlert(res);
        }
      });

    });
  </script>

    <script>
      $(document).ready(function() {
        $('.frgt-pass-block').attr('style', 'display:none');
        $('#login-btn').click(function() {
          $('.login-block').hide();
          $('.frgt-pass-block').show();
        });

        $('#signin-btn').click(function() {
          //  alert("h");
          $('.frgt-pass-block').hide();
          $('.login-block').show();
        });
      })
    </script>



    <?php
    if ($res == 3) {
      echo "<script type='text/javascript'>messageAlert('Unauthentic Attempt!!!')</script>";
    }
    ?>

</body>

</html>