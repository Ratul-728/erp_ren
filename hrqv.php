<?php
session_start();
$usr = $_SESSION["user"];
$empid = $_SESSION["empid"];
//echo $_SESSION["user"];echo "Done";die;

require "common/conn.php"; 

$mod = $_GET['mod'];
if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {

    $ann    = array();
    $annpic = array();

    $qryann    = "SELECT a.`title` ann, emp.photo pht FROM `announce` a LEFT JOIN hr h ON h.id = a.`makeby` left join employee emp on emp.employeecode = h.emp_id where (`organization` = 660 or employee = $usr) order by a.id desc";
    //echo $qryann;die;
    $resultann = $conn->query($qryann);
    $total_ann = $resultann->num_rows;
    while ($rowann = $resultann->fetch_assoc()) {
        $str = "'" . $rowann["ann"] . "'";
        array_push($ann, $str);
        array_push($annpic, $rowann["pht"]);
    }

    //Issue
    $iss = array();

    $qryiss = "SELECT a.`sub` FROM `issueticket` a
    LEFT JOIN employee emp ON emp.id = a.`assigned`
    LEFT JOIN hr h ON h.emp_id = emp.employeecode
    WHERE h.id = " . $usr . " and a.`status` = 1 ORDER by a.id desc";
    $resultiss = $conn->query($qryiss);
    $total_iss = $resultiss->num_rows;
    while ($rowiss = $resultiss->fetch_assoc()) {
        array_push($iss, $rowiss["sub"]);
    }

    //$qry = "SELECT a.name name, b.name org FROM `contact` a, organization b where a.organization = b.orgcode and a.id = ".$atid;
    $qry = "SELECT concat(emp.firstname, ' ', emp.lastname) pname, emp.photo, dept.name dep, des.name des, dept.id department 
    FROM `hr` a LEFT JOIN employee emp ON a.`emp_id` = emp.employeecode  left join department dept on dept.id=emp.department left join designation des on des.id=emp.designation
    WHERE a.id = " . $usr;
    //echo $qry;die;
    $result = $conn->query($qry);
    $tota   = $result->num_rows;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $p  = $row["photo"];
            $nm = $row["pname"];
            $dep = $row["dep"];
            $des = $row["des"];
            $departmentid = $row["department"];
        }

    }
    
    //Attandence
    $getdate = new DateTime('now', new DateTimeZone('Asia/dhaka'));
    $date = $getdate->format('Y-m-d');
    $qryatt = "SELECT  `intime`, `outtime` FROM `attendance_test` WHERE `hrid` = '$usr' AND `date` = '$date'";
    $resultatt = $conn->query($qryatt);
    while ($rowatt = $resultatt->fetch_assoc()) {
        $intime  = $rowatt["intime"];
        $outtime = $rowatt["outtime"];
    }

    ?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php //include_once('common_header.php'); ?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <link rel="icon" href="assets/images/site_setting_logo/favicon_rdl.png">
    <title>bitFlow</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome4.0.7.css" rel="stylesheet">
    <link href="css/fonts.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/simple-sidebar.css" rel="stylesheet">


    <link href="js/plugins/scrollbar/jquery.mCustomScrollbar.css" rel="stylesheet">
    <link href="js/plugins/icheck/skins/square/blue.css" rel="stylesheet">

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

<!--    Bootstrap datetime picker -->
<link rel="stylesheet" href="https: //cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css
">

    <link rel="stylesheet" href="css/app.css" id="maincss">
    <link rel="stylesheet" href="css/dashboard_blank.css">
    <link rel="stylesheet" href="css/ak-bit.css">

</head>

<style>
.chart-bar-horz{
   width: calc(100% - 20px);
    padding-top: 0px;
}

.dashbaord-filter .panel-body{
    padding: 10px;
}

.dashbaord-filter .panel-title{
    padding-left: 10px;
    padding-top: 3px;
}
* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color:white;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
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
.active-tab{
    background: white;
    color: var(--theme);
    padding-top: 10px;
}
.adminproimage img{
    margin-top: 20px;
}
.dis-pro-tab{
    padding-top: 5px;
}
.admin-profilebar {
    background-color: var(--theme);
    height: 110px;
    position: relative;

}
a.pro-tab-name{
    color: white;
}
.admin-profilebar{
    margin: 0px;
}
.profilebar-tab-row{
    background: var(--theme);
    margin: -2px 0px;
    color: white;
    padding-bottom: 5px;
}
.dis-pro-tab{
    background: var(--theme);
    color: white !important;
}
.active-tab {
    background:white;
    font-weight:normal;
    color:var(--theme) !important;
    padding-bottom: 10px;


}

/* NEW VERSION CSS RESPONSIVE */
@media (max-width:5680px){
    .dashbordcount {
    height: auto;
    }
    .countmain img{
        width: 32px;
    }
    .yourmass1 div:nth-child(1){
       width: 15%;
        margin-left: 15px;
    }

    .yourmass1 div:nth-child(2){
       width: 70%;
    }

}


/*
shech mobile dropdown menu;
*/
/* Footer Navbar*/
.footer-tabs {
    width: 100%;
    position: fixed;
    background-color: #4d4d4d;
    background-color:var(--theme);
    bottom: 0;
    z-index: 50000;
    display: none;
    padding: 10px 0;
    -webkit-box-shadow: -1px 0px 9px 1px rgb(0 0 0 / 19%);
    -moz-box-shadow: -1px 0px 9px 1px rgba(0,0,0,0.19);
    box-shadow: -1px 0px 9px 1px rgb(0 0 0 / 19%);
}
@media (max-width: 769px){
.footer-tabs {
    display: block;
}
    .footer-nav li a div{
        padding: 5px 0;
        font-size: 15px;
    }
}
.footer-nav{
    padding: 0;
    margin: 0;
}
ul.footer-nav li div i{

    margin-right: 10px;
    margin-top: -5px;
}
.footer-tabs ul li {
    flex-grow: 1;
    width: 33%;
    text-align: center;
    margin: 0;
    position: relative;
    border-left: 1px solid #ECECEC;
}
.footer-tabs .footer-nav li i{
    font-size: 20px;
}
.footer-tabs{
    padding: 0px;
    padding-top: 5px;
    margin: 0;
}
.footer-tabs .footer-nav li i, .footer-tabs .footer-nav li div{
    color: white;
}
.footer-nav li a:active{
    background: #efef;
    color: var(--theme);
}
.footer-tabs ul li:first-child {
    flex-grow: 1;
    width: 33%;
    text-align: center;
    margin: 0;
    position: relative;
    border-left: 0px;

}

/*footer tab end */
.footer-tabs ul {
    display: flex;
    flex-wrap: nowrap;
    flex-basis: ;
}
@media(max-width:768px){

    .navbar-toggle{
        top: 4px;
    }

    #sidebar-wrapper{
        display: none;
    }


    #page-content-wrapper{
        padding-left: 0px!important;
    }


    .nav-left-padding {
        margin-left: 10px;
    }


    .navbar-header {
        background: #F8F8F8;
    }





</style>

<!-- Select2 CSS -->
<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />

<!-- Include Toastr CSS -->
<link href="js/plugins/toastr/toastr.min.css" rel="stylesheet">





<style>

.toast-top-right {
  top: 60px !important; /* Adjust this value as needed */
}
#toast-container > div {
  /*opacity: 1 !important;*/
}


/* Override Toastr default styles */
#toast-container {
  right: 10px;
}

/* Animations */
.toast {
  animation: slideInRight 0.5s, fadeOut 1s; /* Use desired durations */
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}


.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 34px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b{
    border: 0;
}

.select2-container--default .select2-selection {
  background-color: transparent;
  border: 0px solid #aaa!important;
  border-radius: 0px;
  cursor: text;
}

.select2-container .select2-selection {
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  min-height: 38px;
  user-select: none;
  -webkit-user-select: none;
}


.select2-container--default .select2-selection .select2-selection__choice {
  background-color: #e4e4e4;
  border: 1px solid #dbdbdb;
  border-radius: 2px;

  padding: 3px;
  padding-left: 0px;
  padding-left: 30px;
  font-size: 14px;
}

.select2-container--default .select2-selection .select2-selection__choice__remove {
  padding: 3px 8px;
}
    
    
.select2-container{
  width:102%!important;
    padding: 0;margin: 0;
}    



.announcement1{
    overflow: hidden;
    height:calc(100vh - 320px);
}
.messages{
    padding: 25px;
    padding-top: 10px;
    overflow: hidden;
    overflow-y: scroll;
    height:calc(100vh - 320px);
    
}

.message{
    border-radius: 5px;
    background-color: #efefef;
    margin-bottom: 5px;
}

.message{
    padding-left: 0!important;
    display: flex;
    align-items: center;
    justify-content: left;
}
.message .avatar{
    width: 40px!important;
    margin-left: 10px!important;    
}

.pro-content{
    margin: 0;
}
.dashbordcount{
    border: 0;
}
.panel-heading{
    background-color: var(--theme)!important;
    color: #fff!important;
    font-size: 24px;
    padding: 4px;
    text-align: center;
}

.panel-body.leave:before{
    padding-top: 20px!important;
}
.panel-footer{
    text-align: center;
    background-color: var(--reverse)!important;
    padding: 4px;
}

.badge {
  background-color: red;
  color: white;
  padding: 4px 8px;
  text-align: center;
  border-radius: 5px;
}

.panel.attendance .checkinoutwrap{
    display: flex;
  align-items: center;
  justify-content: center;    
}

.panel.attendance .checkinoutwrap .attn-btn{
    width: 50%;
    text-align: center;
    
}

.panel.attendance .checkinoutwrap .attn-btn:first-child{
    border-right: 1px solid #e2e2e2;
}

.ocation-table{
    padding: 0;
    margin: 0;
}
.ocation-table .event{
  display: flex;
  align-items: left;
  justify-content: center; 
    padding: 0;
    margin: 0;
    margin-bottom: 5px;
    background-color: #f3f3f3;
    padding: 10px;
    border-radius: 5px!important;
}

.ocation-table .event-avatar img{
    border-radius: 50%!important;
    width: 50px;
    height: 50px;
    border:1px solid #b5b3b3!important;
}

.ocation-table .event-icon img{
    border-radius: 50%!important;
    width: 50px;
    height: 50px;
    border:1px solid #b5b3b3!important;
    opacity: .5;
}

.ocation-table .event-text{
    border: 0px solid #000;
    width: 90%;
    padding: 5px;
    padding-left: 15px;
}

.ocation-table .event-text .event-name{
    font-weight: bold;
    color: #434343;
}

.ocation-table .event-text .event-date{
    font-size: 13px;
    color: #434343;
}
</style>


<body class="dashboard userhome dashboard2">


<!-- Fixed navbar -->
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->

  <div id="sidebar-wrapper" class="mCustomScrollbar">

  <div class="section">
  	<i class="fa fa-group  icon"></i>
    <span>Dashboard</span>
  </div>

   <?php
    //include_once 'menu.php';
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

                        <!-- START PLACING YOUR CONTENT HERE -->

<span class="alertmsg"> </span>
                        <div class="admin-profilebar">

                            <div class="row">
                                <div class="">
                                    <div class="adminproimage">
                                        <?php $photo = $rootpath . "/common/upload/hc/" . $p . "";

    if (file_exists($photo)) {
        $photo = "common/upload/hc/" . $p . "";
    } else {
        $photo = "images/blankuserimage.png";
    } ?>
                                        <img src="<?=$photo ?>" alt="">
                                    </div>
                                </div>
                                <div class="">
                                    <div class="adminproname">
                                        <h3><?=$nm ?></h3>
                                        <h5><?=($des)?$des:"" ?><?=($dep)?", ".$dep:"" ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                                   <div class="row profilebar-tab-row">

                                <div class="col dis-pro-tab">
                                    <a myclass="1block" class="pro-tab-name active-tab" href="javascript:void(0)">Dashboard</a>

                                    <a myclass="2block" class=" pro-tab-name" href="javascript:void(0)">Other's Leave</a>
                                    <a myclass="3block" class=" pro-tab-name" href="javascript:void(0)">My Leaves</a>




                                </div>
                            </div>
                            <div class="pro-content">

                                <span class="1block">
                            <div class= "row" >
                            <div class="col-lg-6 col-md-6  ">
                                <br>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Available Leave </div>
                                    
   
                                
                                    <div class="panel-body leave">
                                    
                                    
                                        <div class="dashbordcount">
                                        <div class="row">
    
                                            <div class="col-md-4 col-sm-4">
                                                <div class="countmain linehelp">
                                                    <img src="images/icons/bcalendar.png" alt="">
                                                <?php
        $amedl =0; $acasl =0; $aannl =0;
        $qrycl = "SELECT `remaining_days`, `leave_type` FROM `leave_available` WHERE YEAR(`year`) = YEAR(CURDATE()) AND hrid = " . $usr;
        $resultcl = $conn->query($qrycl);
        while ($rowcl = $resultcl->fetch_assoc()) {
            if ($rowcl["leave_type"] == 5) {
                $amedl = $rowcl["remaining_days"];
            } elseif ($rowcl["leave_type"] == 2) {
                $acasl = $rowcl["remaining_days"];
            } elseif ($rowcl["leave_type"] == 3) {
                $aannl = $rowcl["remaining_days"];
            }
        }
    
        ?>
           <h1><?php echo $acasl;
           if ($acasl <= 1) {
            echo " Day";
        } else {
            echo " Days";
        }
        ?></h1>
                                                    <h5>Casual Leave</h5>
                                                    <h6></h6>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <div class="countmain">
                                                    <img src="images/icons/first-aid-kit (1).png" alt="">
                                                    <h1><?php echo $amedl;if ($amedl <= 1) {
            echo " Day";
        } else {
            echo " Days";
        }
        ?></h1>
                                                    <h5>Sick Leave</h5>
                                                    <h6></h6>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <div class="countmain">
                                                    <img src="images/icons/sun-umbrella.png" alt="">
                                                    <h1><?php echo $aannl;if ($aannl <= 1) {
            echo " Day";
        } else {
            echo " Days";
        }
        ?></h1>
                                                    <h5>Annual Leave</h5>
                                                    <!-- <h6>Some Text </h6> -->
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                 <div class="row" >
    
                                                <div class="col-sm-12">
                                                     <div class="nweissuebox">
                                                            <div class="rightbtn " >
                                                           <!-- <img src="images/icons/due-amount.png" alt="" >-->
                                                                <!--button type="button" id="myBtn" class="lg-btn mod-add-btn" data-toggle="modal" data-target="#exampleModal">
                                                                 <i class="fa fa-question-circle"></i> Request for Leave
                                                                </button-->
    
                                                            </div>
                                                     </div>
    
                                                </div>
                                                
                                            </div>
                                            </div>
                                        </div>
    
    
                                    </div>
                                    
                                    
                                    </div>
                                    <div class="panel-footer">
                                    
                                   
                                    
                                    
                                         <button type="button" id="myBtn" class="btn btn-lg btn-default " data-toggle="modal" data-target="#exampleModal">
                                                                     <i class="fa fa-question-circle"></i> Request for Leave
                                         </button>
                                    
                                    </div>
                                
                                </div>
                                

                                
                                
                                
                                <!--div class="panel panel-default attendance">
                                  <div class="panel-heading">Office Timing</div>
                                  <div class="panel-body">
                                      
                                    <div class="checkinoutwrap">
                                    
                                    <?php if($intime == ''){ ?>
                                        <div class="attn-btn">
                                            <button type="button"  class="btn btn-lg  btn-default mod-add-btn attendancecheck" value = "intime">Check In</button>
                                        </div>
                                        <div class="attn-btn">
                                            <button type="button"  class="btn btn-lg btn-default mod-add-btn">Check In First!</button>
                                        </div>
                                    <?php } else if ($outtime == ''){ ?>
                                        <div class="attn-btn">
                                            <button type="button"  class="btn btn-lg  btn-default mod-add-btn">Already Checked In!</button>
                                        </div>
                                        <div class="attn-btn">
                                            <button type="button"  class="btn btn-lg btn-default mod-add-btn attendancecheck" value="outtime">Check Out</button>
                                        </div>
                                    <?php } else { ?>
                                        <div class="attn-btn">
                                            <button type="button"  class="btn btn-lg  btn-default mod-add-btn">Already Checked In!</button>
                                        </div>
                                        <div class="attn-btn">
                                            <button type="button"  class="btn btn-lg btn-default mod-add-btn">Already Checked Out!</button>
                                        </div>
                                    <?php } ?>
                                    </div>
                                  </div>
                                </div-->
                                
                                
                                <div class="panel panel-default">
                                  <div class="panel-heading">Special Occations</div>
                                  <div class="panel-body">
                                      
                                    <ul class="ocation-table">
                                    
                                    <?php $qryBirth = "SELECT emp.employeecode, CONCAT(emp.firstname, ' ', emp.lastname) AS full_name, DATE_FORMAT(emp.dob, '%M %d') AS birthdate, photo
                                                       FROM employee emp LEFT JOIN hr h ON h.emp_id=emp.employeecode
                                                       WHERE h.active_st = 1 AND MONTH(emp.dob) = MONTH(CURDATE()) AND DAY(emp.dob) = DAY(CURDATE())";
                                          $resultBirth = $conn->query($qryBirth);
                                          if ($resultBirth->num_rows > 0 ) {
                                            while ($rowBirth = $resultBirth->fetch_assoc()) {  
                                                $fullnm = $rowBirth["full_name"]." ( ".$rowBirth["employeecode"]." )";
                                                $birthdate = $rowBirth["birthdate"];
                                                $birthphoto = $rowBirth["photo"];
                                    ?>
                                        <li class="event">
                                            <div class="event-avatar"><img src="common/upload/hc/<?= $birthphoto ?>"></div>
                                            <div class="event-text">
                                                <div class="event-name"><?= $fullnm ?></div>
                                                <div class="event-date"><?= $birthdate ?>- Happy Birthday</div>
                                            </div>
                                            <div class="event-icon"><img src="images/icons/birthday-icon-10189.png"></div>
                                        </li>
                                    <?php }} else { ?>
                                        <li class="event">
                                            <h3> There is no occation!</h3>
                                        </li>
                                    <?php } ?>
                                        
                                    </ul>
                                  </div>
                                  
                        <!-- Only of sales -->
                        <?php if($departmentid == 5 || 1==1 ) { ?>
                                  <div class="panel-heading">Customer Birthday</div>
                                  <div class="panel-body">
                                      
                                    <ul class="ocation-table">
                                    
                                    <?php $qryBirth = "SELECT c.name customernm, DATE_FORMAT(dob, '%M %d') birthdate, org.name orgnm 
                                                        FROM `contact` c LEFT JOIN organization org ON org.orgcode=c.organization  
                                                       WHERE MONTH(dob) = MONTH(CURDATE()) AND DAY(dob) BETWEEN DAY(CURDATE()) AND DAY(CURDATE()) + 10";
                                          $resultBirth = $conn->query($qryBirth);
                                          if ($resultBirth->num_rows > 0 ) {
                                            while ($rowBirth = $resultBirth->fetch_assoc()) {  
                                                $fullnm = $rowBirth["customernm"];
                                                $orgnm = $rowBirth["orgnm"];
                                                $birthdate = $rowBirth["birthdate"];
                                    ?>
                                        <li class="event">
                                            <div class="event-text">
                                                <div class="event-name"><?= $fullnm ?> (Organization: <?= $orgnm ?>)</div>
                                                <div class="event-date"><?= $birthdate ?>- Happy Birthday</div>
                                            </div>
                                            <div class="event-icon"><img src="images/icons/birthday-icon-10189.png"></div>
                                        </li>
                                    <?php }} else { ?>
                                        <li class="event">
                                            <h3> There is no occation!</h3>
                                        </li>
                                    <?php } ?>
                                        
                                    </ul>
                                  </div>
                                  
                        <?php } ?>
                                </div>                                
                                

                            </div>
                            <div class="col-lg-6 col-md-6 ri-an ">
                                  <div class="row right-annouce">
                                       <div class="col-md-12">
                                           
                                            <div class="announcement1">
                                                <div class="annouce">
                                                <img src="images/icons/announcement.png" alt="">
                                               Annoucement
                                               </div>
                                               <div class="messages">
                                            <?php for ($i = 0; $i < $total_ann; $i++) {
        ?>
                                                <div class="yourmass1 message row">
                                                    <div class="avatar">
                                                        <img src="common/upload/hc/<?=$annpic[$i] ?>" alt="">
                                                    </div>
                                                    <div class="text">
                                                        <span class = "spantext"><?=$ann[$i] ?></span>
                                                    </div>
                                                
                                             </div>
                                            <?php } ?>
                                            </div>
                                            </div>
                                            
                                       </div>

                                  </div>
                            </div>
                        </div>
                 </span>
                                <span class="2block">



                                </span>

                                <span class="3block">

                                </span>
                            </div>



                        <!-- START PLACING YOUR CONTENT HERE -->



                                        <!-- Rquest for leave Modal -->
<div id="myModal" class="modal leave-modal">

  <!-- Modal content -->
  <div class="modal-content">
      <div class="row cus-modal-header-row">
         <span class="display-span">Apply Leave Form <span class="close ">&times;</span></span>
      </div>
<form method="post" action="common/applyleave.php" class="cus-modal-body-row" id="form1" enctype="multipart/form-data">

    <div class="row">
        <div class="col-sm-3">
            <select class="form-select form-select-sm form-control" aria-label=".form-select-sm example" name = "leavetype" id = "leavetype" required>
              <option value="">Leave Type</option>
    <?php
$qry1    = "SELECT `id`, `title` FROM `leaveType` WHERE st = 1 ORDER BY `title` DESC";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["title"];
        ?>
            <option value="<?echo $tid; ?>" <?if ($cusid == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
    <?php }} ?>

            </select>
        </div>

        <div class="col-sm-3">
            <div class="form-group styled-select">
                <select name="reliver" id="reliver" class="select2basic form-control form-select form-select-sm form-control" aria-label=".form-select-sm example" required>
                    <option value="">Select Reliver</option>
    <?php 
    $qry1="SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id` FROM `employee` e LEFT JOIN `hr` h ON h.`emp_id`=e.`employeecode` order by emp_id";
	$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
    {while($row1 = $result1->fetch_assoc()) 
          {   $tid= $row1["id"];  $nm=$row1["emp_id"]; 
    ?>  
													
                    <option value="<? echo $tid; ?>"><? echo $nm; ?></option>
    <?php 
          }
    }      
    ?>   
                </select>
            </div>
                                
        </div>
        
        <div class="col-sm-3">
            <div class="input-group">
                <input type="text" class="form-control" id="contactno" name="contactno" value="" Placeholder = "Contact No" required>
            </div>
        </div>
        
        <div class="col-sm-3">
            <div class="input-group">
                <input type="text" class="form-control" id="address" name="address" value="" Placeholder = "Address" required>
            </div>
        </div>

        <div class="col-sm-3" >
            <label for="cmdt">Start Date*</label>
            <div class="input-group">
                <input type="text" class="form-control datepicker" id="startdt" name="startdt" value=""  required>
                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
            </div>
        </div>
        <div class="col-sm-3">
        <label for="cmdt">End Date*</label>
            <div class="input-group">
                <input type="text" class="form-control datepicker" id="enddt" name="enddt" value="" required>
                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
            </div>
        </div>
        <div class="col-sm-3">
            <label for="cmdt">Total Days</label>
            <div class="input-group">
                <input type="text" class="form-control" id="totdays" name="totdays" value = "0" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <textarea class="form-control" id="w3review" name="w3review" rows="4" cols="50" required></textarea>
        
        <div class="col-lg-4 col-md-6 col-sm-6">
        <label for="address">Upload Documents</label>
            <div class="input-group upload-group">
                                <label class="input-group-btn">
                                    <span class="btn btn-upload btn-primary btn-file btn-file">
                                       <i class="fa fa-paperclip"></i> <input type="file" name="uploaddocument[]" id="fileUpload" style="display: none;" multiple="">
                                    </span>
                                </label>
                                <input type="text" class="form-control" id="filetxt" readonly="">
                                
                               
                            </div>
        </div>
    </div>
    <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Apply Leave"  id="applyleave" >
</form>
  </div>

</div>
<style>
	
.userhome .modal{
    top:20vh;
}


	
.userhome #myModal .close{
    position: absolute;
    margin-top: 40px;
	color: #fff;

}
</style>

                                    <!-- Accept Decline for leave modal -->
<!-- The Modal -->
<div id="myModal1" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
      <div class="row cus-modal-header-row">

          <span class="display-span"> Apply Leave Form  <span class="close ">&times;</span> </span>

      </div>


    <div class="cus-modal-body-row">
      <div class="row req-yn-row">
           <ul class="icheck-ul">

          <input id="lev-yes" type="radio" name="lev-req" value = "1">
          <label for="lev-yes">Yes</label>

          <input id="lev-no" type="radio" name="lev-req" value = "0">
          <label for="lev-no">No</label>
          </ul>
           <!--<div class="form-group">

             <ul class="icheck-ul row">
                    <li>
                      <input tabindex="3" type="radio" id="lev-yes" name="lev-req"> &nbsp;
                      <label for="lev-yes">Yes</span></label>
                    </li>
                    <li>
                      <input tabindex="4" type="radio" id="lev-no" name="lev-req" > &nbsp;
                      <label for="lev-no">No</span></label>
                    </li>
                  </ul>
            </div> -->
      <!--    <label class="custom-radio">
        <input id="lev-yes" type="radio" name="lev-req" value="1">
        <span class="radio-btn">
            <div class="hobbies-icon">
                <h3>Yes</h3>
            </div>
        </span>
    </label>
          <label class="custom-radio">
        <input id="lev-no" type="radio" name="lev-req" value="0">
        <span class="radio-btn">
            <div class="hobbies-icon">
                <h3>No</h3>
            </div>
        </span>
    </label> -->



    </div>
    <div class="row">
        <textarea class="form-control" id="leavecomment" name="leavecomment" value="" rows="4" cols="50" ></textarea>
    </div>
    <br>
    <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="button" name="add" value="Submit"  id="leave-sub" >
    </div>

  </div>

</div>









                    </div>
                </div>
            </div>
        </div>
        <div class="footer-tabs">
            <ul class="footer-nav">
		<li class="active"> <a href="index.php" cl="" ass="search-tigger"><span class="pe-7s-home icon"></span>
				  <i class="fa fa-dashboard "></i>
				  <div>Dashboard</div>
				  </a></li>
		<!--search-->

		<!--              <li> <a href="wishlist.html"> <span class="pe-7s-like icon"></span> <span class="count rounded-crcl">5</span> </a> </li>-->

		<!--wish-->


		<li> <a href="#" class="cart-tigger"> <span class="pe-7s-cart icon"></span>
				  <i class="fa fa-calendar-check-o "></i>
				  <div>Other's Leaves</div>
				  </a>
		</li>
		<li class="mobile-footer-usermenu">
						<a href="#" class="ruser-login" data-toggle="modal" data-target="#ruser-login">

			<span class="pe-7s-user icon"></span>
			<i class="fa fa-calendar"></i>
		<div>My Leaves</div></a>
		</li>
		<!--wish-->

	</ul>
        </div>
</div>
<!-- /#page-content-wrapper -->


<?php include_once 'common_footer.php'; ?>
<?php include_once 'inc_chart_dash.php'; ?>
</body>

 <!-- Select2 JS -->
<script src="js/plugins/select2/select2.min.js"></script>

<!-- Include Toastr JS -->
<script src="js/plugins/toastr/toastr.min.js"></script>


<script>
  $(document).ready(function() {
      // Customized Toastr notification
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-top-right",
  
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
 

 
      
  
    $('.select2basic').select2();
    
    
    

    
    
    
    
  });
  
  
  
  
</script>

<script>
$(document).ready(function(){
      // Initialize datepickers
      $('.datepicker').datetimepicker({
        //format: 'YYYY-MM-DD' // You can set the format as per your requirement
        format: 'DD-MM-YYYY'
      });

      // Calculate days difference when date is changed
      $('#startdt, #enddt').on('dp.change', function(){
        var startDate = $('#startdt').data('DateTimePicker').date();
        var endDate = $('#enddt').data('DateTimePicker').date();

        if(startDate && endDate) {
          var difference = endDate.diff(startDate, 'days')+1;
          
          $("#totdays").val(difference);
          var leavetype = $("#leavetype").val();
          if(difference > 1 && leavetype == 5){
              swal('Warning',"Your leave days is more then 1 days. Its now required to upload the supportive documents for apply the leave!", 'warning')
              $("#fileUpload").prop('required', true);
          }else{
              $("#fileUpload").prop('required', false);
          }
          //alert("Number of days between the dates: " + difference);
        }
      });
    });
</script>


<script>

  $(document).ready(function() {
    $('.attendancecheck').click(function() {
      var btnValue = $(this).val();
      var clickedButton = $(this);
       
      $.ajax({
        url: './phpajax/attendanceajax.php', 
        method: 'POST',
        data: { value: btnValue }, 
        success: function(response) {
            clickedButton.prop('disabled', true);
            swal({
              title: 'Successful',
              text: response,
              icon: 'success',
            }).then(function() {
              location.reload();
            });
        },
        error: function(xhr, status, error) {
          swal('Error','Something went wrong!', 'error');
          clickedButton.prop('disabled', false);
        }
      });
    });
  });
</script>
<script>
var modal = document.getElementsByClassName('modal');

var actleaveid = undefined;

$(".lev-acc").click(function(){
    var span = document.getElementsByClassName("close");
    actleaveid = $(this).data('proid');
    modal[1].style.display = "block";
     span[1].onclick = function() {
    modal[1].style.display = "none";
}
    alert("g");
});
$("#myBtn").click(function(){
    actleaveid = $(this).data('proid');
    modal[0].style.display = "block";
    var span = document.getElementsByClassName("close");
    span[0].onclick = function() {
    modal[0].style.display = "none";
}
    //alert("g");
});




$( "#leave-sub" ).click(function() {
    var dec = $("input[type='radio'][name='lev-req']:checked").val();
    var leavecomment = document.getElementById("leavecomment").value;

    if(actleaveid != undefined) {
        //alert("Boom2");
        $.ajax({
        	url:"phpajax/leaveaction.php",
        	method:"POST",
        	data:{dec:dec,leavecomment:leavecomment, actleaveid:actleaveid},

        	success:function(res)
        	{
        		$('.display-msg').html(res);

        		messageAlertLong(res,'alert-success');

        	}
    	});

    	actleaveid = undefined;
    }
    modal[1].style.display = "none";

    setTimeout(function() {
        location.reload();
    }, 3000);

});

/*var btn = document.getElementsByClassName("mod-add-btn");



var span = document.getElementsByClassName("close");

btn[0].onclick = function() {
    modal[0].style.display = "block";
}

btn[1].onclick = function() {
    modal[1].style.display = "block";
}
btn[2].onclick = function() {
    modal[2].style.display = "block";
}

span[0].onclick = function() {
    modal[0].style.display = "none";
}

span[1].onclick = function() {
    modal[1].style.display = "none";
}
span[2].onclick = function() {
    modal[2].style.display = "none";
} */

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

 <script>/*
    $('#startdt').mobiscroll().datepicker({
    controls: ['calendar', 'time'],
    touchUi: true
});
*/</script> 
<script>
//hhhh
var defaults = {

	allowInputToggle: true,
	useCurrent: false,
	ignoreReadonly: true,
	minDate: new Date(),
	toolbarPlacement: 'top',
	locale: 'en',
	icons: {
		time: 'fa fa-clock-o',
		date: 'fa fa-calendar',
		up: 'fa fa-angle-up',
		down: 'fa fa-angle-down',
		previous: 'fa fa-angle-left',
		next: 'fa fa-angle-right',
		today: 'fa fa-dot-circle-o',
		clear: 'fa fa-trash',
		close: 'fa fa-times'
	}
};

$(function() {
	var optionsDatetime = $.extend({}, defaults, {format:'DD-MM-YYYY HH:mm'});
	var optionsDate = $.extend({}, defaults, {format:'DD-MM-YYYY'});
	var optionsTime = $.extend({}, defaults, {format:'HH:mm'});

	$('#startdt').datetimepicker(optionsDate);
    $('#enddt').datetimepicker(optionsDate);

});

</script>

<!-- Date time picker ends -->
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


<!-- BLOCK SCRIPT -->
  <script>
        $(document).ready(function () {

            $(".pro-content span").attr("style", "display:none");
            $(".pro-content .1block").attr("style", "display:block");


              //$(".pro-content .1block").addClass("active-tab");

            $(".dis-pro-tab a").click(function () {
                $(this).addClass("active-tab").siblings().removeClass('active-tab');
                $(".pro-content span").attr("style", "display:none");
                var mclass = $(this).attr("myclass");
                //alert(mclass);

			switch(mclass){



					case '2block':
					$(".2block").html("Loading...");
					$(".2block").load("phpajax/emp/db_otherleave.php");
					break;
					case '3block':
					$(".3block").html("Loading...");
					$(".3block").load("phpajax/emp/db_myleave.php");
					break;



				}

                $(".pro-content ." + mclass).attr("style", "display:block");



            });




        });
    </script>



<?php

    $msg = $_GET["msg"];
    $res = $_GET["res"];
    if ($msg != '' && $res != 4) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    if ($res == 4) {
        echo "<script>swal('Try Again!', '" . $msg . "', 'error') </script>";
    }
    ?>

</html>

<?php
}
?>
