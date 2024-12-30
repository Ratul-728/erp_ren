<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $aid= $_GET['id'];
    //$aid = 32;
    //$res =4;                                                                                                    ";
    //echo $aid;die;
 
    if ($res==4)
    {
        $qry="select `id`, `employeecode`, `firstname`, `lastname`, `dob`, `gender`, `maritialstatus`, `nid`, `tin`, `bloodgroup`, `pp`, `drivinglicense`, `presentaddress`, `area`, `district`, `postal`, `country`
        , `office_contact`, `ext_contact`, `pers_contact`, `alt_contact`, `office_email`, `pers_email`, `alt_email`, `emergency_poc1`, `poc1_relation`, `poc1_contact`, `poc1_address`, `emergency_poc2`, `poc2_relation`, `poc2_contact`, `poc2_address`, `emergency_poc3`, `poc3_relation`, `poc3_contact`, `poc3_address`, `photo`, `signature` FROM `employee` where id= ".$aid; 
       // echo $qry; die;
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
                        $acid=$row["id"];$empcode=$row["employeecode"];$firstname=$row["firstname"];  $lastname=$row["lastname"];$dob=$row["dob"];
                        $gender=$row["gender"];  $maritialstatus=$row["maritialstatus"];$nid=$row["nid"];  $tin=$row["tin"];$bg=$row["bloodgroup"]; $pp=$row["pp"];
                        $drivinglicense=$row["drivinglicense"];  $presentaddress=$row["presentaddress"];$area=$row["area"];$district=$row["district"];$country=$row["country"];
 
                        $office_contact=$row["office_contact"];  $ext_contact=$row["ext_contact"];$pers_contact=$row["pers_contact"];  $alt_contact=$row["alt_contact"];
                        $office_email=$row["office_email"]; $pers_email=$row["pers_email"]; $alt_email=$row["alt_email"];
                        $emergency_poc1=$row["emergency_poc1"];$poc1_relation=$row["poc1_relation"];$poc1_contact=$row["poc1_contact"];$poc1_address=$row["poc1_address"];
                        $emergency_poc2=$row["emergency_poc2"];$poc2_relation=$row["poc2_relation"];$poc2_contact=$row["poc2_contact"];$poc2_address=$row["poc2_address"];  
                        $emergency_poc3=$row["emergency_poc3"];$poc3_relation=$row["poc3_relation"];$poc3_contact=$row["poc3_contact"];$poc3_address=$row["poc3_address"];  
                        $photo=$row["photo"];$signature=$row["signature"]; $zip = $row["postal"];
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    //District name
    $qrydis="SELECT `id`, `name` FROM `district` where id = ".$district; $resultdis = $conn->query($qrydis); if ($resultdis->num_rows > 0) {while($rowdis = $resultdis->fetch_assoc()) 
              { $disnm=$rowdis["name"];}}
    //Country name
    $qrycon="SELECT `id`, `name` FROM `country`  where id = ".$country; $resultcon= $conn->query($qrycon); if ($resultcon->num_rows > 0) {while($rowcon = $resultcon->fetch_assoc()) 
              { $connm=$rowcon["name"];}}
    }
    else
    {
        $acid='';$empcode='';$firstname='';  $lastname='';$dob='';
        $gender='';  $maritialstatus='';$nid='';  $tin='';$bg=''; $pp='';
        $drivinglicense='';  $presentaddress='';$area='';$district='';  $photo='';$signature='';  
        $country='';
 
        $office_contact='';  $ext_contact='';$pers_contact='';  $alt_contact='';
        $office_email=''; $pers_email=''; $alt_email='';
        $emergency_poc1='';$poc1_relation='';$poc1_contact='';$poc1_address='';
        $emergency_poc2='';$poc2_relation='';$poc2_contact='';$poc2_address='';  
        $emergency_poc3='';$poc3_relation='';$poc3_contact='';$poc3_address=''; 
    $mode=1;//Insert mode
 
    }
 
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'hc';
    $currPage = basename($_SERVER['PHP_SELF']);
 
    //Job Tab Information
    $qryhis = "SELECT a.`id`, a.`hrid`, act.Title actnm, a.`actiondt`, d.name deptnm, jb.Title jbnm, des.name desnm ,concat(emp.firstname, ' ', emp.lastname) empnm, jt.Title jtnm,emp.photo reporttoph
                    FROM `hraction` a 
                    LEFT JOIN employee emp ON a.`reportto` = emp.id 
                    LEFT JOIN department d ON a.`postingdepartment` = d.id
                    LEFT JOIN ActionType act ON a.`actiontype` = act.ID 
                    LEFT JOIN designation des ON a.`designation` = des.id 
                    LEFT JOIN JobArea jb ON a.`jobarea` = jb.ID 
                    LEFT JOIN JobType jt ON jt.ID = a.`jobtype`
                    WHERE a.st = 1 and a.hrid =".$aid." order by a.id DESC";
                    //echo $qryhis;die;
 
        $resulthis = $conn->query($qryhis); 
        $acttype = array();
        $actdt = array();
        $postdept = array();
        $jbnm = array();
        $desnm = array();
        $reportto = array();
        $jtnm = array();
        $actiondt = array();
        $reporttoph = array();
 
        while($rowhis = $resulthis->fetch_assoc()){
            array_push($acttype,$rowhis["actnm"]);
            array_push($actdt,$rowhis["actiondt"]);
            array_push($postdept,$rowhis["deptnm"]);
            array_push($jbnm,$rowhis["jbnm"]);
            array_push($desnm,$rowhis["desnm"]);
            array_push($reportto,$rowhis["empnm"]);
            array_push($jtnm,$rowhis["jtnm"]);
            array_push($actiondt,$rowhis["actiondt"]);
            array_push($reporttoph,$rowhis["reporttoph"]);
 
        }
?>
 
<!DOCTYPE html>
<html lang="en">
     <?php
     include_once('common_header.php');
    ?>
 
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
 <link href="js/plugins/datepicker/datepicker-0.5.2/datepicker_style.css" rel="stylesheet" type="text/css"/>
 
    
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/ak-bit.css">
 
    <style>
</style>
</head>
<form method="post" action="common/addhc.php"  id="form1" enctype="multipart/form-data">
<body class="list">
    <!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid nav-left-padding">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="dashboard_blank.php"><img src="images/logo-bitcables.png" alt="BizGIent"></a> </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav parent-menu">
        <li class="active" > &nbsp;
          <button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="fa fa-navicon" aria-hidden="true"></span></button>
        </li>
        <?php 
				$qrysb="SELECT `id`, `Name`, `sl`,`landport` FROM `module` where id=1 order by sl"; 
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
 
 
 
 
    <div class="row pro-header">
<?php
//check file exist;
if(file_exists("common/upload/hc/$photo.jpg")){
	$photoFilePath = "common/upload/hc/$photo.jpg";
	}else{
		$photoFilePath = "images/blankuserimage.png";
		}
?>  
        <div class="col-md-2 img-col">
            <img class="pro-pic" src="<?= $photoFilePath ?>" alt="">
        </div>
        <div class="col pro-name-col">
            <div class="row">
                <h3 class="pro-name"><?php echo "$firstname $lastname" ?></h3>
            </div>
            <div class="row">
                <h5 class="pro-position">Manager, BitHut Corp. </h5>
            </div>
        </div>
 
 
    </div>
 
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col pro-tab">
            <a myclass="personal-block" class="pro-tab-name" href="javascript:void(0)">Personal</a>
            <a myclass="job-block" class=" pro-tab-name" href="javascript:void(0)">Job</a>
            <a myclass="beni-block" class=" pro-tab-name" href="javascript:void(0)">Benifits</a>
            <a myclass="doc-block" class=" pro-tab-name" href="javascript:void(0)">Documents</a>
            <a myclass="atten-block" class=" pro-tab-name" href="javascript:void(0)">Attendence</a>
            <a myclass="asset-block" class=" pro-tab-name" href="javascript:void(0)">Assets</a>
            <a myclass="train-block" class=" pro-tab-name" href="javascript:void(0)">Training</a>
            <a myclass="pay-block" class=" pro-tab-name" href="javascript:void(0)">Payroll</a>
            <a myclass="des-block" class=" pro-tab-name" href="javascript:void(0)">Job Description</a>
            <a myclass="kpi-block" class=" pro-tab-name" href="javascript:void(0)">KPI</a>
            <a myclass="skill-block" class=" pro-tab-name" href="javascript:void(0)">Special Skill</a>
 
 
        </div>
    </div>
 
 
    <div class="row pro-content">
 
        <div class="col-sm-2 pro-content-side">
 
            <div class="cont-no"><?php echo $pers_contact;?></div>
            <div class="cont-mail"><?php echo $pers_email;?></div>
            <div class="cont-location-lab">Location</div>
            <div class="cont-location"><?php echo "$disnm, $connm";?></div>
            <div class="cont-hire-date-lab">Hire Date:</div>
 
            <div class="cont-hire-date">02-Sept-14</div>
            <div class="cont-rep-per-lab">Reports to:</div>
 
<?php 
$rep = array_unique($reportto);
for($i = 0; $i < count($rep); $i++){ ?>
            <div class="row cont-report-person">
                <div class="col-3">
                    <img class="rep-per-img img-fluid" src="common/upload/hc/<?= $reporttoph[$i] ?>.jpg" alt="">
                </div>
 
                <div class="col rep-per-name-col">
                    <span class="row rep-per-name"><?= $rep[$i] ?></span>
                </div>
 
            </div>
<?php } ?>
        </div>
 
        <div class="col pro-content-body container">
 
 
            <div class="pro-content">
 
                <span class="personal-block">
                    <div class="basic-info">
                        <h5 class="table-status-title">Basic Information</h5>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="cd">Employee ID*</label>
                                    <input type="text" class=" ed-info-box" id="cd" name="cd" value="<?php echo $empcode;?>" required>
                                </div>
                            </div>
 
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="fnm">First Name*</label>
                                    <input type="text" class=" ed-info-box" id="fnm" name="fnm" value="<?php echo $firstname;?>" required>
                                    <input type = "hidden" name = "acid" value = "<?= $aid ?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                               <div class="lab-form-group">
                                    <label for="lnm">Last Name</label>
                                    <input type="text" class=" ed-info-box" id="lnm" name="lnm" value="<?php echo $lastname;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                <label for="dob">Date of Birth *</label>
 
                                    <input type="date" class=" ed-info-box datepicker" id="dob" name="dob" value="<?php echo $dob;?>" required>
 
 
                                </div>
                            </div>
 
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="cmbdsg">Gender </label>
                                    <div class="lab-form-group pdd">
                                        <select name="cmbdsg"  class="ed-info-box">
                                            <option value="">Select Designation</option>
                                            <option value="M" <?php if ($gender == 'M') { echo "selected"; } ?>>Male</option>
                                            <option value="F" <?php if ($gender == 'F') { echo "selected"; } ?>>FemMale</option>
                                            <option value="O" <?php if ($gender == 'O') { echo "selected"; } ?>>Other</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="cmbmartial">Maritial Status </label>
                                        <div class="lab-form-group pdd">
                                            <select name="cmbmartial"  class=" ed-info-box">
                                                <option value="">Select Marital Status</option>
                                                <option value="S" <?php if ($maritialstatus == 'S') { echo "selected"; } ?>>Single</option>
                                                <option value="M" <?php if ($maritialstatus == 'M') { echo "selected"; } ?>>Married</option>
                                                <option value="D" <?php if ($maritialstatus == 'D') { echo "selected"; } ?>>Divorced</option>
                                                <option value="O" <?php if ($maritialstatus == 'O') { echo "selected"; } ?>>Others</option>
                                            </select>
                                        </div>
                                </div> 
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="phone">Nid</label>
                                    <input type="text" class=" ed-info-box" id="nid" name="nid" value="<?php echo $nid;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="email">TIN</label>
                                    <input type="text" class=" ed-info-box" id="tin" name="tin" value="<?php echo $tin;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="cmbsrc">Blood Group</label>
                                    <div class="lab-form-group pdd">
                                        <select name="cmbbg"  class=" ed-info-box">
                                            <option value="">Select Blood Group</option>
                                            <option value="A+" <?php if ($bg == 'A+') { echo "selected"; } ?>>A(+)ve</option>
                                            <option value="A-" <?php if ($bg == 'A-') { echo "selected"; } ?>>A(-)ve</option>
                                            <option value="B+" <?php if ($bg == 'B+') { echo "selected"; } ?>>B(+)ve</option>
                                            <option value="B+" <?php if ($bg == 'B-') { echo "selected"; } ?>>B(-)ve</option>
                                            <option value="O+" <?php if ($bg == 'O+') { echo "selected"; } ?>>O(+)ve</option>
                                            <option value="O-" <?php if ($bg == 'O-') { echo "selected"; } ?>>O(-)ve</option>
                                            <option value="AB+" <?php if ($bg == 'AB+') { echo "selected"; } ?>>AB(+)ve</option>
                                            <option value="AB-" <?php if ($bg == 'AB-') { echo "selected"; } ?>>AB(-)ve</option>
                                            <option value="O" <?php if ($bg == 'O') { echo "selected"; } ?>>Others</option>
                                        </select>
                                    </div>
                                </div>  
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="pp" >Passport No.</label>
                                    <input type="text" class=" ed-info-box" id="pp" name="pp" value="<?php echo $pp;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="drvid">Driving Licence</label>
                                    <input type="text" class=" ed-info-box" id="drvid" name="drvid" value="<?php echo $drivinglicense;?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="address">
                        <h5 class="table-status-title">Address</h5>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="lab-form-group">
                                    <label for="preaddr">Address</label>
                                    <input type="text" class=" ed-info-box" id="preaddr" name="preaddr" rows="4" value = "<?php echo $presentaddress;?>">
                                </div>
 
                            </div>
 
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="area">Area</label>
                                    <input type="text" class=" ed-info-box" id="area" name="area" value="<?php echo $area;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                <label for="district">District</label>
                                <div class="lab-form-group pdd">
                                    <select name="district" id="district" class=" ed-info-box">
                                        <option value="">Select District</option>
<?php $qrydis="SELECT `id`, `name` FROM `district`  order by name"; $resultdis = $conn->query($qrydis); if ($resultdis->num_rows > 0) {while($rowdis = $resultdis->fetch_assoc()) 
              { 
                  $tid= $rowdis["id"];  $nm=$rowdis["name"];
    ?>                                                         
                                        <option value="<?php echo $tid; ?>" <?php if ($district == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php  }}?>                                                       
                                    </select>
 
                                </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="zip">Postal Code</label>
                                    <input type="text" class=" ed-info-box" id="zip" name="zip" value="<?php echo $zip;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="country">Country</label>
                                    <div class="lab-form-group pdd">
                                        <select name="country" id="country" class=" ed-info-box">
                                        <option value="">Select Country</option>
<?php $qrycon="SELECT `id`, `name` FROM `country`  order by name"; $resultcon= $conn->query($qrycon); if ($resultcon->num_rows > 0) {while($rowcon = $resultcon->fetch_assoc()) 
              { 
                  $tid= $rowcon["id"];  $nm=$rowcon["name"];
    ?>                                                         
                                        <option value="<?php echo $tid; ?>" <?php if ($country == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php  }}?>                                                       
                                        </select>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
 
                    <div class="contact">
                        <h5 class="table-status-title">Contact</h5>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="off_cont">Official No.</label>
                                    <input type="text" class=" ed-info-box" id="off_cont" name="off_cont" value="<?php echo $office_contact;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="per_cont">Personal Contact</label>
                                    <input type="text" class=" ed-info-box" id="per_cont" name="per_cont" value="<?php echo $pers_contact;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="alt_cont">Alternate Contact</label>
                                    <input type="text" class=" ed-info-box" id="alt_cont" name="alt_cont" value="<?php echo $alt_contact;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="ext">Extension</label>
                                    <input type="text" class=" ed-info-box" id="ext" name="ext" value="<?php echo $ext_contact;?>">
                                </div>
                            </div>
 
 
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="ofc_email">Official Email</label>
                                    <input type="text" class=" ed-info-box" id="ofc_email" name="ofc_email" value="<?php echo $office_email;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="per_email">Personal Email</label>
                                    <input type="text" class=" ed-info-box" id="per_email" name="per_email" value="<?php echo $pers_email;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="lab-form-group">
                                    <label for="alt_email">Alternet Email</label>
                                    <input type="text" class=" ed-info-box" id="alt_email" name="alt_email" value="<?php echo $alt_email;?>">
                                </div>
                            </div>
 
                        </div>
                        <div class="contact">
                            <h5 class="table-status-title">Emergency Contact</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc1">1st Emergency Contact</label>
                                        <input type="text" class=" ed-info-box" id="poc1" name="poc1" value="<?php echo $emergency_poc1;?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc1_rel">Relation</label>
                                        <input type="text" class=" ed-info-box" id="poc1_rel" name="poc1_rel" value="<?php echo $poc1_relation;?>">
                                    </div> 
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc1_cont">Contact </label>
                                        <input type="text" class=" ed-info-box" id="poc1_cont" name="poc1_cont" value="<?php echo $poc1_contact;?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc1_addr">Address </label>
                                        <input type="text" class=" ed-info-box" id="poc1_addr" name="poc1_addr" value="<?php echo $poc1_address;?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc2">2nd Emergency Contact</label>
                                        <input type="text" class=" ed-info-box" id="poc2" name="poc2" value="<?php echo $emergency_poc2;?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc2_rel">Relation</label>
                                        <input type="text" class=" ed-info-box" id="poc2_rel" name="poc2_rel" value="<?php echo $poc2_relation;?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc2_cont">Contact </label>
                                        <input type="text" class=" ed-info-box" id="poc2_cont" name="poc2_cont" value="<?php echo $poc2_contact;?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc2_addr">Address </label>
                                        <input type="text" class=" ed-info-box" id="poc2_addr" name="poc2_addr" value="<?php echo $poc2_address;?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc3">3rd Emergency Contact</label>
                                        <input type="text" class=" ed-info-box" id="poc3" name="poc3" value="<?php echo $emergency_poc3;?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc3_rel">Relation</label>
                                        <input type="text" class=" ed-info-box" id="poc3_rel" name="poc3_rel" value="<?php echo $poc3_relation;?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc3_cont">Contact </label>
                                        <input type="text" class=" ed-info-box" id="poc3_cont" name="poc3_cont" value="<?php echo $poc3_contact;?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="lab-form-group">
                                        <label for="poc3_addr">Address </label>
                                        <input type="text" class=" ed-info-box" id="poc3_addr" name="poc3_addr" value="<?php echo $poc3_address;?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cus-button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn cus-btn" type="submit" name="update" value="Update Employee"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn cus-btn" type="submit" name="add" value="Add Employee"  id="add" >
                                <?php } ?>  
 
                                <input class="btn cus-btn" type="submit" name="cancel" value="Cancel"  id="cancel" >
 
                            </div> 
 
                    </div>
                </span>
 
                <span class="job-block">
 
 
 
                    <h5 class="table-status-title">Status History</h5>
 
 
                    <ul class="my-table">
 
                        <li class="table-header">
                            <div class="col ">Effective Date</div>
                            <div class="col ">Status</div>
                            <div class="col ">Comment</div>
 
                        </li>
<?php for($i = 0; $i < count($jtnm); $i++){ ?>
 
                        <li class="table-row">
 
                            <div class="col " data-label="Job Id"><?= $actiondt[$i] ?></div>
 
 
                            <div class="col" data-label="Status"><?= $jtnm[$i] ?></div>
                            <div class="col" data-label="Status"></div>
 
                        </li>
<?php } ?>
                        <button type="button" id="my-btn" class="add-btn" data-toggle="modal" data-target=".bd-example-modal-lg">+Add</button>
 
 
 
                    </ul>
 
 
 
 
 
                    <h5 class="table-status-title">Position History</h5>
 
 
                    <ul class="my-table">
 
                        <li class="table-header">
                            <div class="col ">Status</div>
                            <div class="col ">Effective Date</div>
                            <div class="col ">Department</div>
                            <div class="col ">Designation</div>
                            <div class="col ">Job Area</div>
                            <div class="col ">Reports To</div>
                            <div class="col ">Remarks</div>
                        </li>
 
<?php for($i = 0; $i < count($jtnm); $i++){ ?>
                        <li class="table-row">
                            <div class="col " data-label="Job Id"><?= $acttype[$i] ?></div>
                            <div class="col " data-label="Job Id"><?= $actiondt[$i] ?></div>
                            <div class="col " data-label="Customer Name"><?= $postdept[$i] ?></div>
                            <div class="col " data-label="Amount"><?= $desnm[$i] ?></div>
                            <div class="col" data-label="Payment Status"><?= $jbnm[$i] ?></div>
                            <div class="col" data-label="Payment Status"><?= $reportto[$i] ?></div>
                            <div class="col" data-label="Payment Status"></div>
                        </li>
<?php } ?>
                        <li class="add-btn">+Add</li>
 
                    </ul>
 
 
 
 
                    <!-- <h5 class="table-status-title">Compensation History</h5>
 
 
                    <ul class="my-table">
 
                        <li class="table-header">
                            <div class="col ">Effective Date</div>
                            <div class="col ">Amount</div>
                            <div class="col ">Pay Type</div>
                            <div class="col ">Schedule</div>
 
                            <div class="col ">Remarks</div>
                        </li>
 
                        <li class="table-row">
 
                            <div class="col " data-label="Job Id">02-April-2021</div>
                            <div class="col " data-label="Job Id">$1100</div>
                            <div class="col " data-label="Customer Name">Salary</div>
                            <div class="col " data-label="Amount">Monthly</div>
                            <div class="col" data-label="Payment Status">Increment</div>
 
                        </li>
                        <li class="table-row">
 
                            <div class="col " data-label="Job Id">02-April-2021</div>
                            <div class="col " data-label="Job Id">$1100</div>
                            <div class="col " data-label="Customer Name">Salary</div>
                            <div class="col " data-label="Amount">Monthly</div>
                            <div class="col" data-label="Payment Status">Increment</div>
 
                        </li>
                        <li class="table-row">
 
                            <div class="col " data-label="Job Id">02-April-2021</div>
                            <div class="col " data-label="Job Id">$1100</div>
                            <div class="col " data-label="Customer Name">Salary</div>
                            <div class="col " data-label="Amount">Monthly</div>
                            <div class="col" data-label="Payment Status">Increment</div>
 
                        </li>
                        <li class="table-row">
 
                            <div class="col " data-label="Job Id">02-April-2021</div>
                            <div class="col " data-label="Job Id">$1100</div>
                            <div class="col " data-label="Customer Name">Salary</div>
                            <div class="col " data-label="Amount">Monthly</div>
                            <div class="col" data-label="Payment Status">Increment</div>
 
                        </li>
                        <li class="add-btn">+Add</li>
 
                    </ul> -->
 
<div id="my-modal" class="modal">
 
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <div class="close">×</div>
 
    </div>
    <div class="modal-body">
        <div class="row">
            <label for="ef-date" class="mod-lab"> Effective Date</label>
             <input type="date" class="modal-field ef-date">
             <label for="status" class="mod-lab">Status:</label>
  <select name="status" class="modal-field" >
 
 
    <option value="">Full Time</option>
    <option value="">Part Time</option>
  </select>
        </div>
        <div class="row">
          <h6 class="mod-lab">Add Comment </h6>  
        <textarea class="st-area"  rows="3" placeholder="Add comment"> </textarea>
        </div>
 
    </div>
 
  </div>
  </div>
  <div id="my-modal2" class="modal">
 
  <!-- Modal content -->
   <div class="modal-content">
    <div class="modal-header">
      <div class="close">×</div>
 
    </div>
    <div class="modal-body">
     <div class="row">
         <div class="col-4 mod-col">
             <label for="status" class="mod-lab">Status:</label>
  <select name="status" class="modal2-field" >
 
 
    <option value="">Full Time</option>
    <option value="">Part Time</option>
  </select>
         </div>
         <div class="col-4 mod-col">
             <label for="ef-date" class="mod-lab"> Effective Date</label>
             <input type="date" class="modal2-field ef-date">
         </div>
         <div class="col-4 mod-col">
             <label for="desig" class="mod-lab">Designation:</label>
  <select name="desig" class="modal2-field" >
 
 
    <option value="">Jr. Software Engineer</option>
    <option value="">Sr. Software Engineer </option>
  </select>
         </div>
 
 
 
 
 
     </div>
 
      <div class="row">
         <div class="col-4 mod-col">
             <label for="status" class="mod-lab">Department:</label>
  <select name="status" class="modal2-field" >
 
 
    <option value="">IT</option>
    <option value="">Management</option>
  </select>
         </div>
         <div class="col-4 mod-col">
             <label for="rep" class="mod-lab">Reports to:</label>
  <select name="rep" class="modal2-field" >
 
 
    <option value="">Mr. Francis</option>
    <option value="">Mr. Max </option>
  </select>
         </div>
         <div class="col-4 mod-col">
             <label for="loc" class="mod-lab">Location:</label>
  <select name="desig" class="modal2-field" >
 
 
    <option value="">Dhaka</option>
    <option value="">Chittagong </option>
  </select>
         </div>
 
 
 
 
 
     </div>
      <div class="row">
           <h6 class="mod-lab">Remarks </h6> 
        <textarea class="st-area"  rows="3">  </textarea>
        </div>
 
    </div>
 
  </div>
  </div>
  <div id="my-modal3" class="modal">
 
  <!-- Modal content -->
   <div class="modal-content">
    <div class="modal-header">
      <div class="close">×</div>
 
    </div>
    <div class="modal-body">
    <div class="row">
         <div class="col-3 mod-col">
             <label for="ef-date" class="mod-lab">Date</label>
             <input type="date" class="modal2-field ef-date">
         </div>
         <div class="col-3 mod-col">
             <label for="am" class="mod-lab"> Amount </label>
             <input type="number" id="am" class="modal2-field am">
         </div>
         <div class="col-3 mod-col">
             <label for="am-t" class="mod-lab">Pay Type</label>
  <select name="am-t" id="am-t" class="modal2-field" >
 
 
    <option value="">Salary</option>
    <option value=""> N/A </option>
  </select>
         </div>
         <div class="col-3 mod-col">
             <label for="sal-t" class="mod-lab">Schedule</label>
  <select name="sal-t" id="sal-t" class="modal2-field" >
 
 
    <option value="">Monthly</option>
    <option value="">Daily </option>
  </select>
         </div>
 
   </div>
   <div class="row">
           <h6 class="mod-lab">Remarks </h6> 
        <textarea class="st-area"  rows="3">  </textarea>
        </div>
 
    </div>
 
  </div>
  </div>
 
                </span>
                <span class="atten-block"></span>
                <span class="doc-block"></span>
                <span class="asset-block"></span>
                <span class="train-block"></span>
                <span class="skill-block"></span>
                <span class="beni-block"></span>
                <span class="kpi-block"></span>
                <span class="des-block"></span>
                <span class="pay-block"></span>
            </div>
        </div>
    </div>
    
    
    
    <!-- PAGE CONTENT END HERE -->
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
    


 <!-- FLOT CHART-->  
 <script src="js/plugins/Flot/jquery.flot.js"></script>
   <script src="js/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
   <script src="js/plugins/Flot/jquery.flot.resize.js"></script>
   <script src="js/plugins/Flot/jquery.flot.pie.js"></script>
   <script src="js/plugins/Flot/jquery.flot.time.js"></script>
   <script src="js/plugins/Flot/jquery.flot.categories.js"></script>
   <script src="js/plugins/flot-spline/js/jquery.flot.spline.min.js"></script>
   	<script src="js/demo-flot.js"></script>

  


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



<script src="js/app.js"></script>   
   
  <!-- END FLOT CHART--> 
 
 
 
 
    <script src="./assets/js/jquery-3.4.1.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/fontawesome.js"></script>
 
 
 
    <script>
        $(document).ready(function () {
 
            $(".pro-content span").attr("style", "display:none");
            $(".pro-content .personal-block").attr("style", "display:block");
 
 
 
 
            $(".pro-tab a").click(function () {
                $(".pro-content span").attr("style", "display:none");
                var mclass = $(this).attr("myclass");
                //alert(mclass);
                $(".pro-content ." + mclass).attr("style", "display:block");
 
 
 
            });
 
 
 
 
        });
    </script>
    <script>
window.onload = function(){
 
var modal = document.getElementsByClassName('modal');
 
 
var btn = document.getElementsByClassName("add-btn");
 
 
 
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
}
 
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
};
    </script>
</body>
</form>
 
</html>
<?php } ?>