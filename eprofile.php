<?php
require "common/conn.php";
session_start();
$atid = $_SESSION["user"];
if($atid == ''){
    header("Location: ".$hostpath."/hr.php");
}

//Department
$qrydep = "SELECT des.name des, dep.name dep FROM `hraction` a 
LEFT JOIN employee emp ON emp.id = a.`hrid` 
LEFT JOIN hr h ON emp.employeecode=h.emp_id 
LEFT JOIN designation des ON a.`designation`= des.id 
LEFT JOIN department dep ON dep.id = a.`postingdepartment` where h.id= ".$atid." order BY a.id DESC LIMIT 1";
$resultdep = $conn->query($qrydep);
$rowdep = $resultdep->fetch_assoc();
$des = $rowdep["des"];
$dep = $rowdep["dep"];

//Announcement
$ann = array();
$annpic = array();

$qryann="SELECT a.`title` ann, h.emp_id pht FROM `announce` a LEFT JOIN hr h ON h.id = a.`makeby` where `organization` = 660 order by a.id";
$resultann = $conn->query($qryann);
$total_ann = $resultann->num_rows;
while($rowann = $resultann->fetch_assoc()){
    array_push($ann, $rowann["ann"]);
    array_push($annpic, $rowann["pht"]);
}

//Issue
$iss = array();

$qryiss = "SELECT a.`sub` FROM `issueticket` a 
LEFT JOIN employee emp ON emp.id = a.`assigned` 
LEFT JOIN hr h ON h.emp_id = emp.employeecode 
WHERE h.id = ".$atid." and a.`status` = 1 ORDER by a.id desc";
$resultiss = $conn->query($qryiss);
$total_iss = $resultiss->num_rows;
while($rowiss = $resultiss->fetch_assoc()){
    array_push($iss, $rowiss["sub"]);
}

?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
     include_once('common_header.php');
    ?>

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

    <link rel="stylesheet" href="css/app.css" id="maincss">
    <link rel="stylesheet" href="css/dashboard_blank.css">
    <link rel="stylesheet" href="css/ak-bit.css">

</head>

<body class="dashboard">

    <?php
     include_once('common_top_body.php');
    ?>
    <!-- Fixed navbar -->

    <div id="wrapper">
        <!-- Sidebar -->

         <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>Profile</span>
      </div>
      
    <?php
        include_once('menu.php');
    ?>
      
      	<div style="height:54px;">
    	</div>
      </div>
        <!-- /#sidebar-wrapper -->



<?php 
    //$qry = "SELECT a.name name, b.name org FROM `contact` a, organization b where a.organization = b.orgcode and a.id = ".$atid;
    $qry = "SELECT concat(emp.firstname, ' ', emp.lastname) pname, emp.photo FROM `hr` a LEFT JOIN employee emp ON a.`emp_id` = emp.employeecode  WHERE a.id = ".$atid;
    //echo $qry;die;
    $result = $conn->query($qry); 
    $tota=$result->num_rows;
    if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
?>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid xyz">
                <div class="row">
                    <div class="col-lg-12">

                        <p>&nbsp;</p>
                        <p>&nbsp;</p>

                        <!--h1 class="page-title">Customers</a></h1-->

                        <!-- START PLACING YOUR CONTENT HERE -->


                        <div class="admin-profilebar">
                            <div class="row">
                                <div class="">
                                    <div class="adminproimage">
                                        <?php $photo=$rootpath."/common/upload/hc/".$row["photo"].".jpg";
                                    
                                            if (file_exists($photo)) {
                                    		       $photo="common/upload/hc/".$row["photo"].".jpg";
                                    		}else{
                                			    $photo="images/blankuserimage.png";
                                		    } ?>
                                        <img src="<?= $photo ?>" alt="">
                                    </div>
                                </div>
                                <div class="">
                                    <div class="adminproname">
                                        <h3><?= $row["pname"] ?></h3>
                                        <h5><?= $des ?>, <?= $dep ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="row">
                            <div class="col-md-8">
                                    <div class="dashbord-menu ">
                                    <div class="container-fluid">
                                       
                                        <nav class="navbar-right ">


                                            <ul class="nav navbar-nav admin-nav">
                                                <li class="active"><a href="#" class="activeplus">Dashbord</a></li>
                                                <li><a href="#"></a></li>
                                                <li><a href="#"></a></li>
                                                <li><a href="#"></a></li>
                                                <li><a href="#"></a></li>
                                                <li><a href="#"></a></li> 
                                            </ul>

                                        </nav>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                         
                        </div> -->

                        <div class="row">
                            <div class="col-lg-6 col-md-6  ">
                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="dashbord-box ">
                                            <div class="dash-head-text">
                                                <h3>
                                                    Dashbord
                                                </h3>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="dashbordcount">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4">
                                            <div class="countmain linehelp">
                                                <img src="images/icons/bcalendar.png" alt="">
                                            <?php $qryiss = "SELECT a.`id` FROM `issueticket` a, organization o, contact c where c.organization = o.orgcode and o.id = a.`organization` and c.id = ".$atid;
                                                  $resultiss = $conn->query($qryiss);
                                                  $numiss = $resultiss->num_rows;
                                            ?>
                                                <h1><?= $total_iss ?></h1>
                                                <h5>Issue Counts</h5>
                                                <h6>Active Issue for You </h6>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="countmain">
                                                <img src="images/icons/first-aid-kit (1).png" alt="">
                                                <h1>8</h1>
                                                <h5>Due Amount </h5>
                                                <h6>Some Text </h6>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="countmain">
                                                <img src="images/icons/sun-umbrella.png" alt="">
                                                <h1><?= $total_ann ?></h1>
                                                <h5>Announcement</h5>
                                                <!-- <h6>Some Text </h6> -->
                                            </div>
                                        </div>
                                        <div class="container-fluid">
                                             <div class="row" >
                                       <!-- <a href = "./issuecustomer.php?res=0&msg=%27Insert%20Data%27&mod=6">
                                            <div class="col-md-6 col-sm-6">
                                               <div class="nweissuebox">
                                                      <div class="leftbtn">
                                                      <img src="images/icons/isuue-counts.png" alt="">
                                                <button>Raise New Issue</button>
                                            </div>
                                               </div>
                                             
                                            </div>
                                        </a> -->
                                            <div class="col-md-12 col-sm-12">
                                                 <div class="nweissuebox">
                                                        <div class="rightbtn " >
                                                        <img src="images/icons/due-amount.png" alt="" >
                                                <button class="lg-btn">Request for Leave</button>
                                            </div>
                                                 </div>
                                               
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    
                                  
                                </div>

                                 <!-- <div class="row">
                                        <div class="col-md-12">
                                            <div class="feedsbox">
                                                <div class="feedhead">Feeds</div>
                                                <?php for($i = 0; $i < $total_iss; $i++){ ?>
                                                <div class="yourmass"><?= $iss[$i] ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div> -->

                            </div>
                            <div class="col-lg-6 col-md-6 ri-an ">
                                  <div class="row right-annouce">
                                       <div class="col-md-12">
                                            <div class="announcement1">
                                                <div class="annouce">
                                                <img src="images/icons/announcement.png" alt="">
                                               Annoucement
                                               </div>
                                            <?php for($i = 0; $i < $total_ann; $i++){
                                            ?> 
                                                <div class="yourmass1">
                                                <img src="common/upload/hc/<?= $annpic[$i] ?>.jpg" alt="">
                                                <span class="spantext"><?= $ann[$i] ?></span> 
                                             </div>
                                            <?php } ?>
                                            </div>
                                       </div>
                                       
                                  </div>
                                  <!-- Task Summery
                                  <div class="row">
                                      <div class="col-md-12">
                                           <div class="task">
                                                <div class="feedhead">Task Summary</div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="t-summary linehelp">
                                                        <?php $qryis = "SELECT a.`id` FROM `issueticket` a, organization o, contact c where c.organization = o.orgcode and o.id = a.`organization` and a.`status` = 1 and c.id =".$atid;
                                                              $resultis = $conn->query($qryis);
                                                                $numis = $resultis->num_rows; ?>
                                                            <h1><?= $numis ?></h1>
                                                            <p>#of Open Issues</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                     <div class="t-summary">
                                                            <h1>1</h1>
                                                            <p>#of Notifications</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                         <div class="t-summary">
                                                            <h1><?= $numann ?></h1>
                                                            <p>#of Announcement</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                      </div>
                                  </div> -->
                                 
                            </div>
                        </div>










                        <!-- START PLACING YOUR CONTENT HERE -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->

<?php  }} ?>

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
    <script>
        window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')
    </script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/sidebar_menu.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/custom.js"></script>



    <!-- FLOT CHART-->
    <script src="js/plugins/Flot/jquery.flot.js"></script>
    <script src="js/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/Flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/Flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/Flot/jquery.flot.time.js"></script>
    <script src="js/plugins/Flot/jquery.flot.categories.js"></script>
    <script src="js/plugins/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="js/demo-flot.js"></script>
    <script src="js/app.js"></script>

    <!-- END FLOT CHART-->



</body></html>