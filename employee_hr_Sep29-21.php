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
    //echo $res;die;
    
    $acttype = array();
        $actdt = array();
        $postdept = array();
        $jbnm = array();
        $desnm = array();
        $reportto = array();
        $jtnm = array();
        $actiondt = array();
        $reporttoph = array();
    
    
    

    if ($res==4)
    {
        $qry="select e.`id`, e.`employeecode`, e.`firstname`, e.`lastname`,DATE_FORMAT(e.`dob`,'%e/%c/%Y') `dob`, e.`gender`, e.`maritialstatus`, e.`nid`, e.`tin`
        , e.`bloodgroup`, e.`pp`, e.`drivinglicense`, e.`presentaddress`, e.`area`, e.`district`,d.name disnm, e.`postal`, e.`country`,c.name connm
        , e.`office_contact`, e.`ext_contact`, e.`pers_contact`, e.`alt_contact`, e.`office_email`, e.`pers_email`, e.`alt_email`, e.`emergency_poc1`
        , e.`poc1_relation`, e.`poc1_contact`, e.`poc1_address`, e.`emergency_poc2`, e.`poc2_relation`, e.`poc2_contact`, e.`poc2_address`
        , e.`emergency_poc3`, e.`poc3_relation`, e.`poc3_contact`, e.`poc3_address`, e.`photo`, e.`signature` 
        FROM `employee` e left join district d on e.district=d.id
        left join country c on e.country=c.id
        where e.id=".$aid;
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
                        $photo=$row["photo"];$signature=$row["signature"]; $zip = $row["postal"]; $disnm = $row["disnm"];$connm = $row["connm"];
                    }
            }
        }
    $mode=2;//update mode
    
    //get data
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
        
        $stst = array();
        $stdt = array();
        
        for($i = 0; $i < count($jtnm); $i++){
            if (in_array($jtnm[$i], $stst)){
                //Do nothing
            }else{
                array_push($stst,$jtnm[$i]);
                array_push($stdt,$actiondt[$i]);
            }
        }
        
        
    }
    else
    {
        $acid='';$empcode='';$firstname='';  $lastname='';$dob='';
        $gender='';  $maritialstatus='';$nid='';  $tin='';$bg=''; $pp='';
        $drivinglicense='';  $presentaddress='';$area='';$district='';  $photo='';$signature='';  
        $country='';$disnm='';$connm='';
 
        $office_contact='';  $ext_contact='';$pers_contact='';  $alt_contact='';
        $office_email=''; $pers_email=''; $alt_email='';
        $emergency_poc1='';$poc1_relation='';$poc1_contact='';$poc1_address='';
        $emergency_poc2='';$poc2_relation='';$poc2_contact='';$poc2_address='';  
        $emergency_poc3='';$poc3_relation='';$poc3_contact='';$poc3_address=''; 
        $mode=1;//Insert mode
    }
 
    $currSection = 'hc';
    $currPage = basename($_SERVER['PHP_SELF']);
?> 

<?php  include_once('common_header.php');?>

<style>
 
table tr th{
    white-space: nowrap;
}

.pro-tab {
    margin-top: -1px !important;
    background: #00abe3;
    padding: 5px;
      padding-top: 20px;
}

.pro-tab a{
   
    color: white;
    
    
}
.pro-tab a{
    margin: 0px !important;
}
.active-tab {
    background:white;
    font-weight:bold;
    color:#00abe3 !important;
    padding-bottom: 10px !important;
    
    
}
    </style>
<link rel="stylesheet" href="./css/ak-bit.css">
<link href="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.css" rel="stylesheet" type="text/css"/>
 <link href="js/plugins/datepicker/datepicker-0.5.2/datepicker_style.css" rel="stylesheet" type="text/css"/>


    <body class="list">
    <?php  include_once('common_top_body.php');?>
        <div id="wrapper">
      <!-- Sidebar -->
            <div id="sidebar-wrapper" class="mCustomScrollbar">
                <div class="section">
  	                <i class="fa fa-group  icon"></i>
                    <span>Buiesness POS</span>
                </div>
                <?php  include_once('menu.php');?>
	            <div style="height:54px;">	</div>
            </div>
  <!-- /#sidebar-wrapper --> 
  <!-- Page Content -->
            <div id="page-content-wrapper">
                <div class="container-fluid xyz">
                    <div class="row">
                        <div class="col-lg-12">
                        <p>&nbsp;</p>   <p>&nbsp;</p>
                        <p>
          <!-- START PLACING YOUR CONTENT HERE -->
                            <div class="row pro-header">
<?php if(file_exists("common/upload/hc/$empcode.jpg")){$photoFilePath = "common/upload/hc/$photo.jpg"; if($photo == '') {$photoFilePath = "images/bitavatar.png";}}else{$photoFilePath = "images/bitavatar.png";} ?>  
                                <div class="col-md-2 img-col">
                                    <form enctype="multipart/form-data" action="./common/upphoto.php" method="POST" class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" name="avatar" class="{{ $errors->has('email') ? 'alert alert-danger' : '' }}" />
                                            <label for="imageUpload"></label>
                                        </div>
                                        <div class="avatar-preview container2">
                                            <div id="dp-imagePreview" style="background-image: url(<?= $photoFilePath ?>);">
                                                <input type="hidden" name="_token" value="">
                                                <input type = "hidden" name ="ppid" value = "<?= $aid ?>">
                                                <input type = "hidden" name ="ppcode" value = "<?= $empcode ?>">
                                                <button class="dp-btn" type="submit" style="display:none" >Submit </button>
                                            </div>
                                        </div>
                                    </form>
                                     <div class="pro-content-side">
                                        <div class="cont-location-lab">Contact Details</div>
                                        <div class="cont-no"><?php echo $pers_contact;?></div>
                                        <div class="cont-mail"><?php echo $pers_email;?></div>
                                        <div class="cont-location-lab">Location</div>
                                        <div class="cont-location"><?php echo "$disnm, $connm";?></div>
                                        <div class="cont-hire-date-lab">Hire Date:</div>
                                        <div class="cont-hire-date">02-Sept-14</div>
                                        <div class="cont-rep-per-lab">Reports to:</div>
<?php  //if($mode == 2) { $rep = array_unique($reportto);for($i = 0; $i < count($rep); $i++){ ?>
                                        <div class="row cont-report-person">
                                            <div class="cols-2">
                                                <img class="rep-per-img img-fluid" src="common/upload/hc/<?= $reporttoph[0] ?>.jpg" alt="">
                                            </div>
                                            <div class="cols-10 rep-per-name-col">
                                                <span class="row rep-per-name"><?= $reportto[0] ?></span>
                                            </div>
                                        </div>
<?php //} }?>
                                    </div>
                                </div>
                                <div class="col pro-name-col">
                                    <div class="row">
                                        <h3 class="pro-name"><?php echo $firstname." ".$lastname ?></h3>
                                        <h5 class="pro-position"><?= $desnm[0] ?>, <?= $postdept[0] ?> </h5>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col pro-tab">
                                    <a myclass="personal-block" class="active-tab pro-tab-name" href="javascript:void(0)">Personal</a>
                                <?php if($res == 4){ ?>
                                    <a myclass="job-block" class=" pro-tab-name" href="javascript:void(0)">Job</a>
                                    <a myclass="beni-block" class=" pro-tab-name" href="javascript:void(0)">Benifits</a>
                                    <a myclass="doc-block" class=" pro-tab-name" href="javascript:void(0)">Documents</a>
                                    <a myclass="atten-block" class=" pro-tab-name" href="javascript:void(0)">Attendance</a>
                                    <a myclass="leave-block" class=" pro-tab-name" href="javascript:void(0)">Leave</a>
                                    <a myclass="asset-block" class=" pro-tab-name" href="javascript:void(0)">Assets</a>
                                    <a myclass="train-block" class=" pro-tab-name" href="javascript:void(0)">Training</a>
                                    <a myclass="pay-block" class=" pro-tab-name" href="javascript:void(0)">Payroll</a>
                                    <a myclass="des-block" class=" pro-tab-name" href="javascript:void(0)">Job Description</a>
                                    <a myclass="kpi-block" class=" pro-tab-name" href="javascript:void(0)">KPI</a>
                                    <a myclass="skill-block" class=" pro-tab-name" href="javascript:void(0)">Special Skill</a>
                                <?php } ?>
                                
                                    <span class="alertmsg"></span>
                                </div>
                                <div class="col-md-9 pro-content-body container">
                                        <div class="pro-content">
                                            <form method="post" action="common/addhc.php"  id="form1" enctype="multipart/form-data">
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
                                                            <label for="dob">Date of Birth</label>
                                                                <input type="text" class=" ed-info-box datepicker" id="dob" name="dob" value="<?php echo $dob;?>">
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
                                                                <label for="alt_email">Alternate Email</label>
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
                                            </form>
                                    <?php if($res == 4){ ?>
                                            <span class="job-block">
                                                <h5 class="table-status-title">Status History</h5>
                                                <table class="table table-hover em-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Effective Date</th>
                                                            <th>Status</th>
                                                            <th>Comment</th>
                                                        </tr>
                                                       
                                                    </thead>
    <?php for($i = 0; $i < count($stst); $i++){ ?>
                                                   <tbody>
                                                       <tr>
                                                           
                                                      
                                                        <td data-label="Job Id"><?= $stdt[$i] ?></td>
                                                        <td  data-label="Status"><?= $stst[$i] ?></td>
                                                        <td data-label="Status"></td>
                                                        
                                                         </tr>
                                                   </tbody>
    <?php } ?>
                                                    
                                                </table>
                                                <li id="yyyy" class="add-btn li-none">+Add</li><!--button id="yyy">
                                                    Try
                                                </button-->
                                                <h5 class="table-status-title">Position History</h5>
                                                 <table class="table table-hover em-table"> 
                                                    <thead>
                                                        <tr>
                                                        <th >Status</th>
                                                        <th >Effective Date</th>
                                                        <th >Department</th>
                                                        <th >Designation</th>
                                                       <th >Job Area</th>
                                                        <th>Reports To</th>
                                                        <!--<div class="col ">Remarks</div> -->
                                                        </tr>
                                                    </thead>
    <?php for($i = 0; $i < count($jtnm); $i++){ ?>
                                                     <tbody>
                                                         <tr>
                                                            <td data-label="Job Id"><?= $acttype[$i] ?> </td>
                                                            <td data-label="Job Id"><?= $actiondt[$i] ?></td>
                                                            <td data-label="Customer Name"><?= $postdept[$i] ?></td>
                                                            <td data-label="Amount"><?= $desnm[$i] ?></td>
                                                            <td data-label="Payment Status"><?= $jbnm[$i] ?></td>
                                                            <td data-label=""><?= $reportto[$i] ?></td>
                                                            <!--<div class="col-2" data-label="Payment Status"></div>-->
                                                        </tr>
                                                    </tbody>
     <?php } ?>
                                                    </table>
   
                                                    <li class="add-btn1 li-none">+Add</li>
                                               
                                                <div id="my-modal" class="modal emod">
                                                  <!-- Modal content -->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="close eclose">×</div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class=" col-sm-6">
                                                                    <label for="cmdt">Start Date*</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control datepicker" id="startdt" name="startdt" value="" required>
                                                                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                                                    </div>        
                                                                </div>
                                                                <div class="col-sm-6">
                                                                     <label for="status" class="mod-lab">Status:</label>
                                                                     <select class="form-select form-select-sm form-control" aria-label=".form-select-sm example" name = "reliver">
                                                                        <option selected>Full Time</option>
                                                                        <option value="">Part Time</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                              <h6 class="mod-lab">Add Comment </h6>  
                                                                <textarea class="st-area pad-text-area"  rows="3" placeholder="Add comment"> </textarea>
                                                            </div>
                                                            <div class="row">
                                                                <button type="button" class="mod-btn">Submit</button>
                                                                <!-- <button type="button" class="mod-btn mod-close">Close</button> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="my-modal2" class="modal emod1">
                                              <!-- Modal content -->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="close eclose1">×</div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                               <div class="col-lg-4 col-md-4 col-sm-4  column">
                                            						<div class="form-group">
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                            							        <option value="">Action Type </option>
                                                							</select>
                                                						  </div>
                                            						</div>				
                                                				</div>
                                            				    <div class="col-lg-4 col-md-4 col-sm-4  column">
                                                					<div class="input-group">
                                                						<input type="text" class="form-control datepicker dt-input" id="" name="" value="">
                                                						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
                                                					</div>				
                                                				</div>
                                            				    <div class="col-lg-4 col-md-4 col-sm-4  column">
                                            						<div class="form-group">
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                            							        <option value="">Department </option>
                                            							    </select>
                                            						    </div>
                                            						</div>				
                                            				    </div>
                                            				    <div class="col-lg-4 col-md-4 col-sm-4  column">
                                        						    <div class="form-group">
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                                							  <option value="">Job area </option>
                                                							</select>
                                            						    </div>
                                            						</div>				
                                                				</div>
                                                				<div class="col-lg-4 col-md-4 col-sm-4  column">
                                            						<div class="form-group">
                                            						    <div class="form-group styled-select">
                                                							<select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                                							  <option value="">Job Type</option>
                                                							</select>
                                            						    </div>
                                            						</div>				
                                                				</div>
                                                				<div class="col-lg-4 col-md-4 col-sm-4  column">
                                            						<div class="form-group">
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                                							  <option value="">Designation</option>
                                                							</select>
                                            						    </div>
                                            						</div>				
                                                				</div>
                                                				<div class="col-lg-4 col-md-4 col-sm-4  column">
                                            						<div class="form-group">
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                            							        <option value="">Reports to</option>
                                                							</select>
                                            						    </div>
                                            						</div>				
                                                				</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </span>
                    <!-- END JOB BLOCK -->
                                            <span class="beni-block">
                                                <h5 class="table-status-title">Employee Benifits Status</h5>
                                                <table class="table table-hover em-table">
                                                    
                                                    <thead class="table-header">
                                                        <tr>
                                                        <th>Benifits</th>
                                                        <!--th>Privilage Fund</th>
                                                        <th>Increment</th-->
                                                        <th>Effective Date</th>
                                                        <th>Condition</th>
                                                        </tr>
                                                    </thead>
                                        <?php   $qrybensts = "SELECT a.`id`, p.`title` package, b.`increment`, b.`privilagedfund`,date_format(b.`effectivedate`,'%d/%m/%Y') effectivedate, b.`conditions` FROM `hrcompansation` a 
                                                            LEFT JOIN hrcompansationdetails b ON a.companCode = b.hrcompCode 
                                                            LEFT JOIN `pakage` p ON p.`id` = b.pakage WHERE a.hrid = ".$aid;
                                                $resultbensts = $conn->query($qrybensts);
                                                //echo $qrybensts;die;
                                                while($rowbensts = $resultbensts->fetch_assoc()){
                                        
                                        ?>
                                                    <tbody class="table-row">
                                                        <tr>
                                                        <td><?= $rowbensts["package"] ?></td>
                                                        <!--td><?= $rowbensts["privilagedfund"] ?></td>
                                                        <td><?= $rowbensts["increment"] ?></td-->
                                                        <td><?= $rowbensts["effectivedate"] ?></td>
                                                        <td><?= $rowbensts["conditions"] ?></td>
                                                        </tr>
                                                    </tbody>
                                                      
                                        <?php } ?>
                                        </table>
                                                    <!-- Add button>
                                                    <button type="button" id="my-btn4" class="add-btn2">+Add</button-->
                                              
                            
                                                <h5 class="table-status-title">Employee Benifits History</h5>
                                                <table class="table table-hover em-table">
                                                    <thead class="table-header">
                                                        <th>Year</th>
                                                        <th>Month</th>
                                                        <th>Basic</th>
                                                        <th>House Rent</th>
                                                        <th>Medical</th>
                                                        <th>Transport</th>
                                                        <th>Mobile</th>
                                                        <th>Gross</th>
                                                    </thead>
                                            <?php   $qryslhis = "SELECT `id`, `salaryyear`, `salarymonth`, `hrid`, `benft_1`, `benft_2`, `benft_3`, `benft_4`, `benft_5` FROM `monthlysalary` WHERE hrid = ".$aid." Order BY id desc";
                                                    $resultslhis = $conn->query($qryslhis);
                                                    while($rowslhis = $resultslhis->fetch_assoc()){
                                                        if($rowslhis["salarymonth"] == 1) $slmonth = "January";
                                                        else if($rowslhis["salarymonth"] == 2) $slmonth = "February";
                                                        else if($rowslhis["salarymonth"] == 3) $slmonth = "March";
                                                        else if($rowslhis["salarymonth"] == 4) $slmonth = "April";
                                                        else if($rowslhis["salarymonth"] == 5) $slmonth = "May";
                                                        else if($rowslhis["salarymonth"] == 6) $slmonth = "June";
                                                        else if($rowslhis["salarymonth"] == 7) $slmonth = "July";
                                                        else if($rowslhis["salarymonth"] == 8) $slmonth = "August";
                                                        else if($rowslhis["salarymonth"] == 9) $slmonth = "September";
                                                        else if($rowslhis["salarymonth"] == 10) $slmonth = "October";
                                                        else if($rowslhis["salarymonth"] == 11) $slmonth = "November";
                                                        else $slmonth = "December";
                                                        
                                                        $sltotal = $rowslhis["benft_1"] + $rowslhis["benft_2"] + $rowslhis["benft_3"] + $rowslhis["benft_4"] + $rowslhis["benft_5"];
                                            ?>
                                                    <tbody class="table-row">
                                                        <th><?= $rowslhis["salaryyear"] ?></th>
                                                        <th><?= $slmonth ?></th>
                                                        <th><?= $rowslhis["benft_1"] ?></th>
                                                        <th><?= $rowslhis["benft_2"] ?></th>
                                                        <th><?= $rowslhis["benft_3"] ?></th>
                                                        <th><?= $rowslhis["benft_4"] ?></th>
                                                        <th><?= $rowslhis["benft_5"] ?></th>
                                                        <th><?= $sltotal ?></th>
                                                    </tbody>
                                            <?php } ?>
                                                   </table>
                                                    <!-- Add button>
                                                    <button type="button" i d="my-btn" class="add-btn3">+Add</button--> 
                                                
                                                <div id="my-modal4" class="modal emod2">
                                                  <!-- Modal content -->
                                                   <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="close eclose2">×</div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-4 col-sm-4 column">
                                                                    <div class="form-group">
                                                                        <label for="ben-ent" class="mod-lab">Benifits:</label>
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                            							        <option value="">Promoted</option>
                                                                                <option value="">Demoted</option>
                                            							    </select>
                                            						    </div>
                                            						</div>
                                        						</div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4 column">
                                                                    <label for="ben-ent" class="mod-lab">Effective Date:</label>
                                                                    <div class="input-group">
                                                						<input type="text" class="form-control datepicker dt-input" id="chqdt" name="chqdt1" value="">
                                                						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
                                                					</div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4 column">
                                                                    <label for="ben-ent" class="mod-lab">Entitled:</label>
                                                                    <div class="form-group">
                                        						        <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                            							        <option value="">Yes</option>
                                                                                <option value="">No</option>
                                            							    </select>
                                            						    </div>
                                        						    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <h6 class="mod-lab">Remarks </h6> 
                                                                <textarea class="st-area pad-text-area"  rows="3">  </textarea>
                                                            </div>
                                                            <div class="row">
                                                                <button type="button" class="mod-btn">Submit</button>
                                                             <!-- <button type="button" class="mod-btn mod-close">Close</button> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="my-modal5" class="modal emod3">
                                                  <!-- Modal content -->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="close eclose3">×</div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                                  <div class="form-group">
                                                                    <label for="">Year</label>
                                                        			  <div class="form-group styled-select">
                                                        				  <select name="select1" id="select1" class="form-control">
                                                        					<option value="1">2021</option>
                                                        					<option value="2">2020</option>
                                                        					
                                                        				  </select>
                                                        			  </div>
                                                                  </div>        
                                                                </div>
                                                               <!-- <div class="col-3 mod-col">
                                                                    <label for="ben-dt" class="mod-lab">Year</label>
                                                                    <select name="ben-dt" class="modal2-field" >
                                                                        <option value="">2021</option>
                                                                        <option value="">2020</option>
                                                                    </select>
                                                                </div> -->
                                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                        	        <label for="email">Date </label>
                                                                  <div class="input-group">
                                                                    <input type="text" class="form-control datepicker">
                                                                    <div class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-th"></span>
                                                                    </div>
                                                                  </div>     
                                                                </div>
                                                                <!-- <div class="col-3 mod-col">
                                                                    <label for="ef-date" class="mod-lab">Date</label>
                                                                    <input type="date" class="modal2-field ef-date">
                                                                </div> -->
                                                                  <div class="col-lg-3 col-md-6 col-sm-6">
                                                                  <div class="form-group">
                                                                    <label for="">Amount</label>
                                                                    <input type="number" class="form-control" id="">
                                                                  </div>        
                                                                </div>
                                                                <!-- <div class="col-3 mod-col">
                                                                    <label for="ben-ent" class="mod-lab">Amount</label>
                                                                    <input type="number" class="modal2-field">
                                                                </div>  -->
                                                                 <div class="col-lg-3 col-md-6 col-sm-6">
                                                                  <div class="form-group">
                                                                    <label for="">Type</label>
                                                        			  <div class="form-group styled-select">
                                                        				  <select name="select1" id="select1" class="form-control">
                                                        				<option value="">Monthly</option>
                                                                        <option value="">Weekly</option>
                                                        					
                                                        				  </select>
                                                        			  </div>
                                                                  </div>        
                                                                </div>
                                                                <!--  <div class="col-3 mod-col">
                                                                    <label for="ben-sl" class="mod-lab">Type</label>
                                                                    <select name="ben-sl" class="modal2-field" >
                                                                        <option value="">Monthly</option>
                                                                        <option value="">Weekly</option>
                                                                    </select>
                                                                </div>  -->
                                                            </div>
                                                            <div class="row">
                                                               <h6 class="mod-lab">Remarks </h6> 
                                                                <textarea class="st-area"  rows="3">  </textarea>
                                                            </div>
                                                            <div class="row">
                                                                <button type="button" class="mod-btn">Submit</button>
                                                                <!-- <button type="button" class="mod-btn mod-close">Close</button> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </span>
                                            <span class="asset-block">
                                                 <h5 class="table-status-title">Assets</h5>
                                                <table class="table table-hover em-table">
                            
                                                    <thead class="table-header">
                                                        <tr>
                                                        <th>Benifits</th>
                                                        <th>Effective Date</th>
                                                        <th>Code/Serial</th>
                                                        <th>Details</th>
                                                        </tr>
                                                    </thead>
                            
                                                    <tbody class="table-row">
                                                <?php 
                                                    $qryassetde = "SELECT b.`title`, a.`effectivedt`, a.`serial`, a.`details` FROM `assets` a LEFT JOIN `benifitype` b ON a.`benefittype` = b.`id` WHERE a.`empid` = ".$aid;
                                                    $resultassetde = $conn->query($qryassetde); 
                                                    while($rowatde = $resultassetde->fetch_assoc()) {
                                                ?>
                                                        <tr>
                                                        <td><?= $rowatde["title"] ?></td>
                                                        <td><?= $rowatde["effectivedt"] ?></td>
                                                        <td><?= $rowatde["serial"] ?></td>
                                                        <td><?= $rowatde["details"] ?></td>
                                                        </tr>
                                                <?php } ?>
                                                    </tbody>
                                                    
                            
                            
                                                </table>
                                                <!--button type="button" i d="my-btn" class="add-btn4"
                                                       >+Add</button-->
                                                
                                                <div id="my-modal6" class="modal emod4">
                             
                              <!-- Modal content -->
                               <div class="modal-content">
                                <div class="modal-header">
                                  <div class="close eclose4">×</div>
                             
                                </div>
                            
                                <div class="modal-body">
                            <form method="post" action="common/addasset.php?empid=<?= $aid ?>"  id="form1" enctype="multipart/form-data">
                                
                            
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                              <div class="form-group">
                                                <label for="ben">Benifits</label>
                                    			  <div class="form-group styled-select">
                                                <select name="assetbentype" class="form-control">
                                                   <?php 
                                $qryassetstab = "SELECT `id`,`title` FROM `benifitype` ORDER BY title ASC";
                                $resultassetstab = $conn->query($qryassetstab); 
                                while($rowatb = $resultassetstab->fetch_assoc()) {
                                
                             ?>
                                                     <option value="<?= $rowatb["id"] ?>"><?= $rowatb["title"] ?></option>
                                                 
                                                 <?php } ?>
                                                </select>
                                    				  </div> 
                                              </div>        
                                            </div>
                                          <!--  <div class="col-4 mod-col">
                                         <label for="ben" class="mod-lab">Benifits:</label>
                              <select name="assetbentype" class="modal2-field" >
                             <?php 
                                $qryassetstab = "SELECT `id`,`title` FROM `benifitype` ORDER BY title ASC";
                                $resultassetstab = $conn->query($qryassetstab); 
                                while($rowatb = $resultassetstab->fetch_assoc()) {
                                
                             ?>
                                <option value="<?= $rowatb["id"] ?>"><?= $rowatb["title"] ?></option>
                            
                            <?php } ?>
                              </select>
                                     </div> -->
                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                	        <label for=""> Effective Date</label>
                                          <div class="input-group">
                                            <input type="text name = "assetedt"" class="form-control datepicker">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                          </div>     
                                        </div>
                                    <!-- <div class="col-4 mod-col">
                                         <label for="ef-date" class="mod-lab"> Effective Date</label>
                                         <input type="date" name = "assetedt" value = "" class="modal2-field ef-date">
                                     </div> -->
                                    
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                          <div class="form-group">
                                            <label for="">Code/Serial</label>
                                            <input type="text"  name = "assetserial" class="form-control" >
                                          </div>        
                                        </div>
        
                                     <!-- <div class="col-4 mod-col">
                                         <label for="ef-date" class="mod-lab">Code/Serial</label>
                                         <input type="text" name = "assetserial" value = "" class="modal2-field ef-date">
                                     </div> -->
                             
                             
                             
                                 </div>
                                  <div class="row">
                                       <h6 class="mod-lab">Details</h6> 
                                    <textarea class="st-area"  rows="3" name = "assetdetails" value = "">  </textarea>
                                    </div>
                                    <div class="row">
                                        <button type="submit" class="mod-btn">Submit</button>
                                         <!-- <button type="button" class="mod-btn mod-close">Close</button> -->
                                    </div>
                             </form>
                                </div>
                             
                              </div>
                              </div>
                                            </span>
                                            
                                              <span class="atten-block">
                                                
                                                            
                                                             <h5 class="table-status-title">Attendance</h5>
                                                <table class="table table-hover em-table">
                                                 <thead>
                            
                                                    <tr>
                                                      
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Office Time</th>
                                                        <th scope="col">Shift Type</th>
                                                        <th scope="col">Designation</th>
                                                        <th scope="col">Department</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Entry Time</th>
                                                        <th scope="col">Exit Time</th>
                                                        <th scope="col">Total Hrs</th>
                                                        <!--<div class="col">Special Remarks</div>
                                                         <div class="col">Details</div> -->
                            
                                                    </tr>
                                                    
                                                    </thead>
                            
                                                
                                                <?php 
                                                    $qryatt = "select u.id id,u.dt ,DATE_FORMAT(u.dt,'%e/%c/%Y') trdt,u.hrName,u.ofctime,u.shift
                                                            ,(select name from designation where id= ha.`designation`) desig
                                                            ,(select name from department  where ID= ha.`postingdepartment`) dept
                                                            ,(case when entrytm is null then (case when u.lv is null then 'Absent' else u.lv end)  else 'Present' end ) sttus
                                                            ,u.entrytm,u.exittime,TIMEDIFF(IFNULL(exittime,entrytm),entrytm) durtn from
                                                            (
                                                            select d.dt,h.id,h.hrName,h.emp_id,e.id eid
                                                            ,(select min(intime) from attendance where hrid=h.id and date=d.dt) entrytm
                                                            ,(select (case when max(outtime) is null then max(intime) when max(intime)>max(outtime) then  max(intime) else max(outtime) end)  from attendance where hrid=h.id and date=d.dt) exittime
                                                            ,(select title from Shifting where id=(select shift from assignshifthist where empid=e.id 
                                                            and effectivedt =(select max(effectivedt) from assignshifthist where `empid`=e.id and `effectivedt`<=d.dt)))shift
                                                            ,(select concat(`start`,' to ',`end`)  from OfficeTime where shift=(select shift from assignshifthist where empid=e.id 
                                                            and effectivedt =(select max(effectivedt) from assignshifthist where `empid`=e.id and `effectivedt`<=d.dt)))ofctime
                                                            ,(SELECT lt.title FROM  `leave` l, leaveType lt where l.leavetype=lt.id and  hrid=h.id 
                                                            and d.dt BETWEEN l.startday and l.endday) lv    
                                                            from loggday d,hr h ,employee e
                                                            where 
                                                            d.dt BETWEEN DATE_SUB(sysdate(), INTERVAL 15 DAY) and  sysdate()
                                                            and h.emp_id=e.employeecode and e.id = ".$aid."
                                                            ) u,hraction ha where u.eid=ha.hrid ORDER BY u.dt DESC";
                                                                //echo $qryatt;die;
                                                    $resultatt = $conn->query($qryatt); 
                                                    while($rowatt = $resultatt->fetch_assoc()) { 
                                                       
                                                ?>
                                                    <tr class="table-row">
                                                        <th><?= $rowatt["dt"] ?></th>
                                                        <th><?= $rowatt["ofctime"] ?></th>
                                                         <th><?= $rowatt["shift"] ?></th>
                                                       <th><?= $rowatt["desig"] ?></th>
                                                         <th><?= $rowatt["dept"] ?></th>
                                                         <th><?= $rowatt["sttus"] ?></th>
                                                         <th><?= $rowatt["entrytm"] ?></th>
                                                         <th><?= $rowatt["exittime"] ?></th>
                                                         <th><?= $rowatt["durtn"] ?></th>
                                                    </tr>
                                                <?php } ?>
                            
                                                   
                                                </table>
                                                
                                                 <!-- Add button>
                                                <button type="button" i d="my-btn" class="add-btn5" >+Add</button-->
                                                 <!-- Attendance Modal-->
                              <div id="my-modalat" class="modal emod5">
                             
                              <!-- Modal content -->
                               <div class="modal-content">
                                <div class="modal-header">
                                  <div class="close eclose5">×</div>
                             
                                </div>
                                <div class="modal-body">
                                        
                                        <div class="row">
                                     
                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                                        	        <label for="email">Date </label>
                                                                  <div class="input-group">
                                                                    <input type="text" class="form-control datepicker">
                                                                    <div class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-th"></span>
                                                                    </div>
                                                                  </div>     
                                                                </div>
                                       <!-- <div class="col-3 mod-col">
                                         <label for="ef-date" class="mod-lab"> Office Time </label>
                                         <input type="text" class="modal2-field ">
                                     </div>  -->
                                      <div class="col-lg-3 col-md-6 col-sm-6">
                                      <div class="form-group">
                                        <label for="">Office time</label>
                                        <input type="text" class="form-control" >
                                      </div>        
                                    </div>
                                     
                                     
                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="">Shift Type</label>
                                            <div class="form-group styled-select">
                                                <select name="select1" id="select1" class="form-control">
                                                    <option value="">Casual</option>
                                                    <option value="">Medical</option>
                                                    <option value="">Annual</option>
                                                </select>
                                            </div>
                                        </div>        
                                    </div>
                               <!--<div class="col-3 mod-col">
                                         <label for="ben" class="mod-lab">Shift Type</label>
                              <select name="ben" class="modal2-field modal-field" >
                             
                             
                                <option value="">Casual</option>
                                <option value="">Medical</option>
                                <option value="">Annual</option>
                              </select>
                                </div>   -->
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            <div class="form-group styled-select">
                                                <select name="select1" id="select1" class="form-control">
                                                    <option value="">Absent</option>
                                                    <option value="">Present</option>
                                                </select>
                                            </div>
                                        </div>        
                                    </div>
                                    <!-- <div class="col-3 mod-col">
                                         <label for="ben" class="mod-lab">Status</label>
                              <select name="ben" class="modal2-field modal-field" >
                             
                             
                                <option value="">Absent</option>
                                <option value="">Present</option>
                               
                              </select>
                                     </div>   -->
                                      <!--  <div class="col-3 mod-col">
                                         <label for="ef-date" class="mod-lab"> Entry Time </label>
                                         <input type="text" class="modal2-field ">
                                     </div>  -->
                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                      <div class="form-group">
                                        <label for="">Entry time</label>
                                        <input type="text" class="form-control" >
                                      </div>        
                                    </div>
                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                      <div class="form-group">
                                        <label for="">Exit time</label>
                                        <input type="text" class="form-control" >
                                      </div>        
                                    </div>
                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                      <div class="form-group">
                                        <label for="">Total hours</label>
                                        <input type="text" class="form-control" >
                                      </div>        
                                    </div>
                                      <!--<div class="col-3 mod-col">
                                         <label for="ef-date" class="mod-lab">Exit Time </label>
                                         <input type="text" class="modal2-field ">
                                     </div>
                                      <div class="col-3 mod-col">
                                         <label for="ef-date" class="mod-lab">Total Hours </label>
                                         <input type="number" class="modal2-field ">
                                     </div> -->
                                    
                             
                                     
                             
                             
                                 </div>
                                 <div class="row">
                                       <h6 class="mod-lab">Special Remarks</h6> 
                                    <textarea class="st-area"  rows="3">  </textarea>
                                    </div>
                                  <div class="row">
                                       <h6 class="mod-lab">Details</h6> 
                                    <textarea class="st-area"  rows="3">  </textarea>
                                    </div>
                                    <div class="row">
                                        <button type="button" class="mod-btn">Submit</button>
                                        
                                    </div>
                             
                                </div>
                             
                              </div>
                              </div>
                                                
                                                
                                                
                                            </span>
                                            
                                            
                                            <span class="leave-block">
                                                
                                                            
                                                             <h5 class="table-status-title">Leave</h5>
                                                <table class="table table-hover em-table">
                            
                                                    <thead class="table-header">
                                                        <tr>
                                                        <th>Leave Type</th>
                                                        <th>Applied Date</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Total Days</th>
                                                        <th>Details</th>
                                                        <th>Approver</th>
                                                        <th>Approved Action</th>
                                                        <th>Approver Comment</th>
                                                        </tr>
                                                    </thead>
                            
                                                    
                                        <?php $qryleaveap = "SELECT l.hrid,h1.hrName
                                                            ,(select name from designation where id= ha.`designation`) desig
                                                            ,(select name from department  where ID= ha.`postingdepartment`) dept
                                                            ,DATE_FORMAT(l.applieddate,'%d/%c/%Y') applydt, lt.title,DATEDIFF(l.endday,l.startday)+1 days,DATE_FORMAT(l.startday,'%d/%c/%Y') startday,DATE_FORMAT(l.endday,'%d/%c/%Y') endday,h.hrName approver
                                                            ,DATE_FORMAT(l.approvedate,'%d/%c/%Y') approvedate
                                                            ,l.details, l.approvercoments, l.approveraction
                                                            FROM  `leave` l, leaveType lt,hr h ,hr h1,hraction ha ,employee e
                                                            where l.leavetype=lt.id 
                                                            and l.approver=h.id
                                                            and l.hrid=h1.id
                                                            and h1.emp_id=e.employeecode
                                                            and e.id=ha.hrid
                                                            and l.applieddate BETWEEN DATE_SUB(sysdate(), INTERVAL 1 YEAR) and  sysdate()
                                                            and e.id = ".$aid."
                                                            ORDER BY applydt DESC"; 
                                                 $resultleaveap = $conn->query($qryleaveap); 
                                                //echo $qrydoc;die;
                                                while($rowleaveap = $resultleaveap->fetch_assoc()) {
                                                    
                                                    if($rowleaveap["approveraction"] == ""){
                                                        $leaveapst = "Pending";
                                                    }else if($rowleaveap["approveraction"] == 1){
                                                        $leaveapst = "Accepted";
                                                    }else{
                                                        $leaveapst = "Declined";
                                                    }
                                        
                                        ?>
                                                <tbody class="table-row">
                                                    <tr>
                                                        <td><?= $rowleaveap["title"] ?></td>
                                                         <td><?= $rowleaveap["applydt"] ?></td>
                                                        <td><?= $rowleaveap["startday"] ?></td>
                                                         <td><?= $rowleaveap["endday"] ?></td>
                                                          <td><?= $rowleaveap["days"] ?></td>
                                                        <td><?= $rowleaveap["details"] ?></td>
                                                        <td><?= $rowleaveap["approver"] ?></td>
                                                        <td><?= $leaveapst ?></td>
                                                        <td><?= $rowleaveap["approvercoments"] ?></td>
                                                    </tr>
                                                    </tbody>
                                        <?php } ?>
                                                    
                                                </table>
                                                
                                                <!--li class="add-btn6 li-none">+Add</li-->
                                                
                                                <div id="my-modal7" class="modal emod6">
                             
                              <!-- Modal content -->
                               <div class="modal-content">
                                <div class="modal-header">
                                  <div class="close eclose6">×</div>
                             
                                </div>
                                <div class="modal-body">
                                        
                                        <div class="row">
                                     
                                <div class="col-lg-4 col-md-6 col-sm-6">
                        	        <label for="">Effective Date</label>
                                  <div class="input-group">
                                    <input type="text" class="form-control datepicker">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                  </div>     
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                  <div class="form-group">
                                    <label for="">Leave Type</label>
                        			  <div class="form-group styled-select">
                        				  <select name="select1" id="select1" class="form-control">
                        					<option value="1">Casual</option>
                        					<option value="2">Medical</option>
                        					<option value="3">Annual</option>
                        				  </select>
                        			  </div>
                                  </div>        
                                </div>
                                                             
                                   <!--  <div class="col-4 mod-col">
                                         <label for="ben" class="mod-lab">Leave Type</label>
                              <select name="ben" class="modal2-field modal-field" >
                             
                             
                                <option value="">Casual</option>
                                <option value="">Medical</option>
                                <option value="">Annual</option>
                              </select>
                                     </div> -->
                                    
                                      <div class="col-lg-4 col-md-6 col-sm-6">
                            	        <label for="">Start Date</label>
                                      <div class="input-group">
                                        <input type="text" class="form-control datepicker">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                      </div>     
                                    </div>
                             
                                      <!-- <div class="col-4 mod-col">
                                         <label  class="mod-lab">Days</label>
                                         <input type="number" class="modal2-field ef-date">
                                     </div>  -->
                                     <!--<div class="col-4 mod-col">
                                         <label class="mod-lab"> Start Date</label>
                                         <input type="date" class="modal2-field ef-date">
                                     </div>
                                     <div class="col-4 mod-col">
                                         <label  class="mod-lab">End Date</label>
                                         <input type="date" class="modal2-field ef-date">
                                     </div> -->
                                      <div class="col-lg-4 col-md-6 col-sm-6">
                            	        <label for="">End Date</label>
                                      <div class="input-group">
                                        <input type="text" class="form-control datepicker">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                      </div>     
                                    </div>
                                    <!-- <div class="col-4 mod-col">
                                         <label for="ben" class="mod-lab ">Approved By</label>
                              <select name="ben" class="modal2-field modal-field" >
                             
                             
                                <option value="">Adam</option>
                                <option value="">Fran</option>
                              </select>
                                     </div> -->
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                  <div class="form-group">
                                    <label for="">Approved By</label>
                        			  <div class="form-group styled-select">
                        				  <select name="select1" id="select1" class="form-control">
                        					<option value="1">Adam</option>
                        					<option value="2">Sylvia</option>
                        					<option value="3">Anderson</option>
                        				  </select>
                        			  </div>
                                  </div>        
                                </div>
                             
                             
                                 </div>
                                  <div class="row">
                                       <h6 class="mod-lab">Details</h6> 
                                    <textarea class="st-area"  rows="3">  </textarea>
                                    </div>
                                    <div class="row">
                                        <button type="button" class="mod-btn">Submit</button>
                                        
                                    </div>
                             
                                </div>
                             
                              </div>
                              </div>
                                                
                                                
                                            </span>
                                             <span class="doc-block">
                            
                                                <div class="row doc-head">
                                                    <h5 class="doc-head-title">
                                                        <li style="list-style: none;">
                                                            <i class="fa fa-file doc-head-icon" aria-hidden="true"></i><span
                                                                class="show-span">Documents</span>
                                                        </li>
                                                    </h5>
                                                </div>
                                                <div class="row doc-sub-head">
                                                    <!-- <li style="list-style: none;">
                                                        <i class="fa fa-upload doc-subhead-icon" aria-hidden="true"></i><span
                                                            class="show-span">upload</span>
                                                    </li> -->
                                                   <!-- <input type="file" name="doc-up" id="doc-up">
                                                    <label id="doc-up-lab" for="doc-up"><i class="fa fa-upload doc-subhead-icon"
                                                            aria-hidden="true"></i><span class="show-span">upload</span></label> -->
                                                     <button class="doc-up-btn add-btn7" type="button"><i class="fa fa-upload doc-subhead-icon"
                                                            aria-hidden="true"></i><span class="show-span">upload</span></button>       
                                                    <button class="doc-dw" id = "down-file" type="button"><i class="fas fa-download"></i> Download </button>
                                                   <!-- <button class="doc-btn" type="button"> <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                    </button> -->
                                                </div>
                                                <div class="row doc-row doc-row-1 ">
                            
                                                    <li class="li-no-style main-li">
                                                        <h6><i class="fa fa-folder doc-icon" aria-hidden="true"></i><span
                                                                class="show-span">Resume</span>
                                                        </h6>
                                                    </li>
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 1 ORDER BY id DESC";
                                    $resultdoc = $conn->query($qrydoc); 
                                    //echo $qrydoc;die;
                                    while($rowdoc = $resultdoc->fetch_assoc()) {
                                         $file_past = "./images/upload/documents/".$rowdoc["filename"];
                            
                            ?>
                                                    <div class="row doc-sub-row">
                                                        <li class="li-no-style sub-li">
                                                            <input type="checkbox" name="resume" id="resume" value = "<?= $file_past ?>">
                                                            <label for="resume">
                                                                <h6><i class="fa fa-file sub-doc-icon" aria-hidden="true"></i>
                                                                <span class="show-span"><?= $rowdoc["filename"] ?></span>
                                                                <span class="show-span span-del"> <button type= "button" onclick="delobj(this.value)" value="<?= $rowdoc["id"] ?>" class="del-btn"> <i class="fa fa-trash" aria-hidden="true"></i> </button> </span>
                                                                </h6>
                                                            </label>
                                                        </li>
                                                    </div>
                            <?php } ?>
                                                </div>
                            
                                                <div class="row doc-row doc-row-2 ">
                            
                                                    <li class="li-no-style main-li">
                                                        <h6><i class="fa fa-folder doc-icon" aria-hidden="true"></i><span class="show-span">Joining
                                                                Letter</span>
                                                        </h6>
                                                    </li>
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 2 ORDER BY id DESC";
                                    $resultdoc = $conn->query($qrydoc); 
                                    //echo $qrydoc;die;
                                    while($rowdoc = $resultdoc->fetch_assoc()) {
                                         $file_past = "./images/upload/documents/".$rowdoc["filename"];
                            
                            ?>
                            
                                                    <div class="row doc-sub-row">
                                                        <li class="li-no-style sub-li">
                            
                                                            <input type="checkbox" name="jl" id="jl" value = "<?= $file_past ?>">
                                                            <label for="jl">
                                                                <h6><i class="fa fa-file-pdf sub-doc-icon" aria-hidden="true"></i><span
                                                                        class="show-span"><?= $rowdoc["filename"] ?></span>
                                                                         <span class="show-span span-del"> <button type= "button" onclick="delobj(this.value)" value="<?= $rowdoc["id"] ?>" class="del-btn"> <i class="fa fa-trash" aria-hidden="true"></i> </button> </span>
                                                                </h6>
                                                            </label>
                                                        </li>
                                                    </div>
                            <?php } ?>
                                                </div>
                            
                                                <div class="row doc-row doc-row-3 ">
                            
                                                    <li class="li-no-style main-li">
                                                        <h6><i class="fa fa-folder doc-icon" aria-hidden="true"></i><span class="show-span">Tax
                                                                Documents</span>
                                                        </h6>
                                                    </li>
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 3 ORDER BY id DESC";
                                    $resultdoc = $conn->query($qrydoc); 
                                    
                                    while($rowdoc = $resultdoc->fetch_assoc()) {
                                         $file_past = "./images/upload/documents/".$rowdoc["filename"];
                            
                            ?>
                                                    <div class="row doc-sub-row">
                                                        <li class="li-no-style sub-li">
                            
                                                            <input type="checkbox" name="tax" id="tax" value = "<?= $file_past ?>">
                                                            <label for="tax">
                                                                <h6><i class="fa fa-file-pdf sub-doc-icon" aria-hidden="true"></i><span
                                                                        class="show-span"><?= $rowdoc["filename"] ?></span>
                                                                         <span class="show-span span-del"> <button type= "button" onclick="delobj(this.value)" value="<?= $rowdoc["id"] ?>" class="del-btn"> <i class="fa fa-trash" aria-hidden="true"></i> </button> </span>
                                                                </h6>
                                                            </label>
                                                        </li>
                                                    </div>
                            <?php } ?>
                                                </div>
                            
                                                <div class="row doc-row doc-row-4 ">
                            
                                                    <li class="li-no-style main-li">
                                                        <h6><i class="fa fa-folder doc-icon" aria-hidden="true"></i><span
                                                                class="show-span">Promotion Letter</span>
                                                        </h6>
                                                    </li>
                            
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 4 ORDER BY id DESC";
                                    $resultdoc = $conn->query($qrydoc); 
                                    while($rowdoc = $resultdoc->fetch_assoc()) {
                                         $file_past = "./images/upload/documents/".$rowdoc["filename"];
                            
                            ?>
                                                    <div class="row doc-sub-row">
                                                        <li class="li-no-style sub-li">
                            
                                                            <input type="checkbox" name="pl" id="pl" value = "<?= $file_past ?>">
                                                            <label for="pl">
                                                                <h6><i class="fa fa-file-pdf sub-doc-icon" aria-hidden="true"></i><span
                                                                        class="show-span"><?= $rowdoc["filename"] ?></span>
                                                                         <span class="show-span span-del"> <button type= "button" onclick="delobj(this.value)" value="<?= $rowdoc["id"] ?>" class="del-btn"> <i class="fa fa-trash" aria-hidden="true"></i> </button> </span>
                                                                </h6>
                                                            </label>
                                                        </li>
                                                    </div>
                            <?php } ?>
                                                </div>
                            
                                                <div class="row doc-row doc-row-5 ">
                            
                                                    <li class="li-no-style main-li">
                                                        <h6><i class="fa fa-folder doc-icon" aria-hidden="true"></i><span
                                                                class="show-span">Registration or Terination</span>
                                                        </h6>
                                                    </li>
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 5 ORDER BY id DESC";
                                    $resultdoc = $conn->query($qrydoc); 
                                    while($rowdoc = $resultdoc->fetch_assoc()) {
                                         $file_past = "./images/upload/documents/".$rowdoc["filename"];
                            
                            ?>
                                                    <div class="row doc-sub-row">
                                                        <li class="li-no-style sub-li">
                            
                                                            <input type="checkbox" name="rl" id="rl" value = "<?= $file_past ?>">
                                                            <label for="pl">
                                                                <h6><i class="fa fa-file-pdf sub-doc-icon" aria-hidden="true"></i><span
                                                                        class="show-span"><?= $rowdoc["filename"] ?></span>
                                                                         <span class="show-span span-del"> <button type= "button" onclick="delobj(this.value)" value="<?= $rowdoc["id"] ?>" class="del-btn"> <i class="fa fa-trash" aria-hidden="true"></i> </button> </span>
                                                                </h6>
                                                            </label>
                                                        </li>
                                                    </div>
                            <?php } ?>
                                                </div>
                            
                                                <div class="row doc-row doc-row-6 ">
                            
                                                    <li class="li-no-style main-li">
                                                        <h6><i class="fa fa-folder doc-icon" aria-hidden="true"></i><span class="show-span">Show
                                                                Cause</span>
                                                        </h6>
                                                    </li>
                            
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 6 ORDER BY id DESC";
                                    $resultdoc = $conn->query($qrydoc); 
                                    while($rowdoc = $resultdoc->fetch_assoc()) {
                                         $file_past = "./images/upload/documents/".$rowdoc["filename"];
                            
                            ?>
                                                    <div class="row doc-sub-row">
                                                        <li class="li-no-style sub-li">
                            
                                                            <input type="checkbox" name="sc" id="sc" value = "<?= $file_past ?>">
                                                            <label for="pl">
                                                                <h6><i class="fa fa-file-pdf sub-doc-icon" aria-hidden="true"></i><span
                                                                        class="show-span">promotion.pdf</span>
                                                                         <span class="show-span span-del"> <button type= "button" onclick="delobj(this.value)" value="<?= $rowdoc["id"] ?>" class="del-btn"> <i class="fa fa-trash" aria-hidden="true"></i> </button> </span>
                                                                </h6>
                                                            </label>
                                                        </li>
                                                    </div>
                            <?php } ?>
                                                </div>
                            
                                                <div class="row doc-row doc-row-7 ">
                            
                                                    <li class="li-no-style main-li">
                                                        <h6><i class="fa fa-folder doc-icon" aria-hidden="true"></i><span
                                                                class="show-span">Application and Achievement</span>
                                                        </h6>
                                                    </li>
                            
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 7 ORDER BY id DESC";
                                    $resultdoc = $conn->query($qrydoc); 
                                    while($rowdoc = $resultdoc->fetch_assoc()) {
                                         $file_past = "./images/upload/documents/".$rowdoc["filename"];
                            
                            ?>
                                                    <div class="row doc-sub-row">
                                                        <li class="li-no-style sub-li">
                            
                                                            <input type="checkbox" name="aa" id="aa" value = "<?= $file_past ?>">
                                                            <label for="pl">
                                                                <h6><i class="fa fa-file-pdf sub-doc-icon" aria-hidden="true"></i><span
                                                                        class="show-span"><?= $rowdoc["filename"] ?></span>
                                                                         <span class="show-span span-del"> <button type= "button" onclick="delobj(this.value)" value="<?= $rowdoc["id"] ?>" class="del-btn"> <i class="fa fa-trash" aria-hidden="true"></i> </button> </span>
                                                                </h6>
                                                            </label>
                                                        </li>
                                                    </div>
                            <?php } ?>
                                                </div>
                                                
                                            
                             <div id="my-modal9" class="modal emod7">
                             
                              <!-- Modal content -->
                               <div class="modal-content">
                                <div class="modal-header">
                                  <div class="close eclose7">×</div>
                             
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="common/addfilehr.php"  id="docmod" enctype="multipart/form-data">
                                         <h6> Select document type </h6>
                                        <div class="form-group styled-select">
                                            
                            				  <select name="doc-up-mod"  class="form-control">
                            				                <option value="1">Resume</option>
                                                            <option value="2">Joining Letter</option>
                                                            <option value="3">Tax Documents</option>
                                                            <option value="4">Promotion Letter</option>
                                                            <option value="5">Registration</option>
                                                            <option value="6">Show Cause</option>
                                                            <option value="7">Application</option>
                            				  </select>
		    	  </div>
                                      <!--  <div class="row">
                                     
                                     <div class="col-4 mod-col">
                                         <h6> Upload Files </h6>
                                        <input type="file" name="doc-up" id="doc-up">
                                                    <label id="doc-up-lab" for="doc-up"><i class="fa fa-upload doc-subhead-icon"
                                                            aria-hidden="true"></i><span class="show-span">upload</span>
                                                            </label>
                                     </div>
                                     <div class="col-8 mod-col">
                                        <div id="doc-up-name"> </div>
                                     </div>
                                     </div> -->
                                    
                                <div class="row">
                                    <div class="file-loading">
    <input id="input-ficons-1" name="input-ficons-1[]" multiple type="file">
</div>
                                    <!-- <h6> Select document type </h6>
                                     <div class="col-4 mod-col">
                                      
                           <select name="doc-up-mod" class="modal2-field">
                             
                             
                                <option value="1">Resume</option>
                                <option value="2">Joining Letter</option>
                                <option value="3">Tax Documents</option>
                                <option value="4">Promotion Letter</option>
                                <option value="5">Registration</option>
                                <option value="6">Show Cause</option>
                                <option value="7">Application</option>
                              </select>
                                     </div> -->
                                     
                                <input type = "hidden" name = "empid" value = "<?= $aid ?>">
                                     
                               <button type="submit" class="doc-mod-btn">Submit</button>
                                    
                                 </div>
                                </form>
                             
                                </div>
                             
                              </div>
                              </div>
                                            </span>
                                            
                                            <span class="train-block"></span>
                                            <span class="skill-block"></span>
                                           
                                            <span class="kpi-block"></span>
                                            <span class="des-block"></span>
                                            <span class="pay-block"></span>
                                    <?php } ?>
                                        </div>
                                    </div> 
                            </div>
 
                            <div class=row>
                                <div class=" pro-content">
                                   
                                    
                                </div> <!-- PRO CONTENT -->
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
    

<!-- Delete Document -->
<script>

function delobj(docid){
    window.location.href = "./common/delobj.php?retid=<?= $aid ?>&obj=documents&ret=employee_hr&res=4&mod=4&id=".concat(docid);
}
</script>


 <!-- FLOT CHART-->  
<script src="js/plugins/Flot/jquery.flot.js"></script>
<script src="js/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
<script src="js/plugins/Flot/jquery.flot.resize.js"></script>
<script src="js/plugins/Flot/jquery.flot.pie.js"></script>
<script src="js/plugins/Flot/jquery.flot.time.js"></script>
<script src="js/plugins/Flot/jquery.flot.categories.js"></script>
<script src="js/plugins/flot-spline/js/jquery.flot.spline.min.js"></script>
<script src="js/demo-flot.js"></script>
<script src="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.js"></script>
<script src="js/app.js"></script>   
<script src="./assets/js/jquery-3.4.1.js"></script>
<script src="./assets/js/bootstrap.min.js"></script>
<!--<script src="./assets/js/fontawesome.js"></script>-->
<script src="js/plugins/datetimepicker/js/moment-with-locales.js"></script>
<script src="js/plugins/datetimepicker/js/bootstrap-datetimepicker.js"></script>  

<!-- JQUERY TEMPO TIME PICKER PLUGIN -->
<link rel="stylesheet" href="js/plugins/timepicker-jq/dist/wickedpicker.min.js">
 <script src="js/plugins/Flot/jquery.flot.valuelabels.min.js"></script>

<script language="javascript">
$(document).ready(function(){
   	$( ".datepicker" ).datepicker({
	  format: 'yyyy-mm-dd',
	  startDate: '-3d'
	});
 });  
</script>
<!-- end Date Picker  ==================================== -->
 <script>
    $(document).ready(function(){
        $("#hract").click(function(){
            
            $( "#form2" ).submit();
        });
    });
</script>


<!-- Document -->
 <script>
    $(document).ready(function(){
        $("#doc-mod-btn").click(function(){
            
            $( "#docmod" ).submit();
        });
    });
</script>

<script>
    $(document).ready(function(){
        $("#hract").click(function(){
            
            $( "#form2" ).submit();
        });
    });
</script>

<?php  if ($_GET["ss"] != '') {echo "<script type='text/javascript'>messageAlert(".$_GET["msg"].");</script>"; }  ?>

<script>
    $(document).ready(function () {
        $(".pro-content span").attr("style", "display:none");
        $(".pro-content .personal-block").attr("style", "display:block");
        
        $(".pro-tab a").click(function () {
             $(this).addClass('active-tab').siblings().removeClass('active-tab');
            $(".pro-content span").attr("style", "display:none");
            var mclass = $(this).attr("myclass");
            //alert(mclass);
            $(".pro-content ." + mclass).attr("style", "display:block");
        });
    });
</script>

<script>
$('.eclose').click(function(){
   $('.emod').attr('style', 'display:none') ;
});
$('.eclose1').click(function(){
   $('.emod1').attr('style', 'display:none') ;
});
$('.eclose2').click(function(){
   $('.emod2').attr('style', 'display:none') ;
});
$('.eclose3').click(function(){
   $('.emod3').attr('style', 'display:none') ;
});
$('.eclose4').click(function(){
   $('.emod4').attr('style', 'display:none') ;
});
$('.eclose5').click(function(){
   $('.emod5').attr('style', 'display:none') ;
});
$('.eclose6').click(function(){
   $('.emod6').attr('style', 'display:none') ;
});
$('.eclose7').click(function(){
   $('.emod7').attr('style', 'display:none') ;
});
     $('.add-btn').click(function(){
        //alert("el");
       // emod.style.display="block";
        $('.emod').attr('style', 'display:block;');
    });
    $('.add-btn1').click(function(){
        //alert("el");
       // emod.style.display="block";
        $('.emod1').attr('style', 'display:block;');
    });
    $('.add-btn2').click(function(){
        //alert("el");
       // emod.style.display="block";
        $('.emod2').attr('style', 'display:block;');
    });
    $('.add-btn3').click(function(){
        //alert("el");
       // emod.style.display="block";
        $('.emod3').attr('style', 'display:block;');
    });
    $('.add-btn4').click(function(){
        //alert("el");
       // emod.style.display="block";
        $('.emod4').attr('style', 'display:block;');
    });
    $('.add-btn5').click(function(){
        //alert("el");
       // emod.style.display="block";
        $('.emod5').attr('style', 'display:block;');
    });
    $('.add-btn6').click(function(){
        //alert("el");
       // emod.style.display="block";
        $('.emod6').attr('style', 'display:block;');
    });
    $('.add-btn7').click(function(){
        //alert("el");
       // emod.style.display="block";
        $('.emod7').attr('style', 'display:block;');
    });
</script>
    
<!-- MODAL SCRIPT PROBLEM 

<script>
 $(document).ready(function(){
    
   
var modal = document.getElementsByClassName('modal');
var btn = document.getElementsByClassName("add-btn");
var span = document.getElementsByClassName("close");



   // btn.click(function(){
    //  alert("dd"); 
  //     modal[0].style.display = "block";
//    });
//const i=0;

/*for (i=0; i<=modal.length; i++) {
  btn[i].onclick= function() {
    modal[i].style.display = "block";
  //  alert("success");
    
    }; 
    
/*    btn.onclick =function (){
        alert("kk");
    }  
} */
btn[0].click(function() {
    modal[0].style.display = "block";
});
$('add-btn').click(function(){
   modal[0].style.display="block"; 
   alert("hh");
});
$('add-btn1').click(function(){
   modal[1].style.display="block"; 
});
/*
btn[0].onclick = function() {
    modal[0].style.display = "block";
}
 
btn[1].onclick = function() {
    modal[1].style.display = "block";
}
btn[2].onclick = function() {
    modal[2].style.display = "block";
}
btn[3].onclick = function() {
    modal[3].style.display = "block";
}
btn[4].onclick = function() {
    modal[4].style.display = "block";
}
btn[5].onclick = function() {
    modal[5].style.display = "block";
}
 btn[6].click = function() {
    modal[6].style.display = "block";
}
btn[7].onclick = function() {
    modal[7].style.display = "block";
}

*/
 
span[0].onclick = function() {
    modal[0].style.display = "none";
}
 
span[1].onclick = function() {
    modal[1].style.display = "none";
}
span[2].onclick = function() {
    modal[2].style.display = "none";
}
span[3].onclick = function() {
    modal[3].style.display = "none";
}
span[4].onclick = function() {
    modal[4].style.display = "none";
}
span[5].onclick = function() {
    modal[5].style.display = "none";
}
span[6].onclick = function() {
    modal[6].style.display = "none";
}

span[7].onclick = function() {
    modal[7].style.display = "none";
}
  
 
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
});
</script>   -->
    
<!-- FILE NAME SHOW -->

<script>
    updateList = function() {
  var input = document.getElementById('doc-up');
  var output = document.getElementById('doc-up-name');

  output.innerHTML = '<ul>';
  for (var i = 0; i < input.files.length; ++i) {
    output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
  }
  output.innerHTML += '</ul>';
}
</script> 

<script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    console.log(e.target.result);
                   // $('#dp-imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    //document.getElementById("dp-imagePreview").src = "e.target.result";
                    $('#dp-imagePreview').hide();
                    $('#dp-imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function () {
      //      readURL(this);
           
        });

    </script>

<!-- Download -->
<script>
    $(document).ready(function () {
        $("#down-file").click(function() {
            $('input[type="checkbox"][name="resume"]').each(function(){
              if($(this).is(":checked")){
                window.open($(this).val());
            }
        });
        
        $('input[type="checkbox"][name="pl"]').each(function(){
              if($(this).is(":checked")){
                window.open($(this).val());
            }
        });
    });
})
</script>
<script src="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.js"></script>
<!-- Date Picker  ==================================== 

<script language="javascript">
$(document).ready(function(){
   	$( ".datepicker" ).datepicker({
	  format: 'yyyy-mm-dd'
	});
 });  
</script>
<!-- end Date Picker  ==================================== -->

<!-- Date Time Picker  ==================================== -->

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
  
</body>

<!-- === Profile Pic Upload ======= -->
<script>
window.onload = function(){ 
    $('#imageUpload').click(function(){
        $('#dp-btn').trigger('click'); 
        
    });
 };
</script>

<!-- === Profile Pic Upload End ======= -->

<!-- IMAGE UPLOAD -->
<script>
	
$(document).ready(function(){



    $(document).on('click', '#ajax-img-up li u', function() { 
	
		    var imgToDeletePath = $(this).parent().find("img").attr('src');
		    var thisLi = $(this).parent();
		    
		    
		
		    //alert(imgToDeletePath);
		
           $.ajax({
              url: 'phpajax/deletepicajx.php',
              type: 'post',
              data: {action: 'deletepic', pictodelete: imgToDeletePath},


              success: function(response){
                 if(response != 0){
                     
					
					alert(response);
					thisLi.remove();
					 
                 }else{
                   alert('Error deleting picture');
                }
              },
           });
 
		
		
	});
	
	var picid = 1;
	
    $("#imageUpload").change(function(){

        var fd = new FormData();
        var files = $('#imageUpload')[0].files;
		
	//	alert(files.length);
        
        // Check file selected or not
        if(files.length > 0 ){
           fd.append('file',files[0]);
			
			
			
           $.ajax({
              url: 'phpajax/uploadimageajx.php',
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){
                  
                  if(response == 2){
                         swal("Error!", "Please select at least (500 X 500) size picture", "error");
                  }else if(response != 0){

					 alert(response);
					  $('#dp-imagePreview').attr("style","background-image: url(' + response + ') !important");
					 //$('#ajax-img-up li:last').before('<li class="picbox"><u class="fa fa-trash"></u><label class="custom-radio"><input checked type="radio" id="picid_'+picid+'" name="default-pic" value="'+response+'"><div class="radio-btn"><i class="fas fa-check" aria-hidden="true"></i><img src="'+response+'"><input type="hidden" name="imgfiles[]" value="'+response+'"></div><label></li>');

					 picid++;
					 
					 alert(response);
                 }else{
                    alert('file not uploaded');
                 }
              },
           });
        }else{
              swal("Error!", "Please select a file", "error");
        }
   });

	
});	
	
	
</script>	
<!--link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous"-->
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script-->
 <!-- the fileinput plugin styling CSS file -->
<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<!--link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" crossorigin="anonymous"-->

 

 
<!-- the main fileinput plugin script JS file -->
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.2/js/fileinput.min.js"></script>
 
 
<script>
    $("#input-ficons-").fileinput({
   
    previewFileIcon: '<i class="fas fa-file"></i>',
    allowedPreviewTypes: null, // set to empty, null or false to disable preview for all types
    previewFileIconSettings: {
        'docx': '<i class=" fa-file-word text-primary"></i>',
        'xlsx': '<i class=" fa-file-excel text-success"></i>',
        'pptx': '<i class="fa-file-powerpoint text-danger"></i>',
        'jpg': '<i class=" fa-file-image text-warning"></i>',
        'pdf': '<i class=" fa-file-pdf text-danger"></i>',
        'zip': '<i class=" fa-file-archive text-muted"></i>',
    }
});
</script>
<script>
$("#input-ficons-1").fileinput({
    
    uploadAsync: false,
    previewFileIcon: '<i class="fas fa-file"></i>',
    allowedPreviewTypes: null, // set to empty, null or false to disable preview for all types
    previewFileIconSettings: {
        'doc': '<i class="fas fa-file-word text-primary"></i>',
        'xls': '<i class="fas fa-file-excel text-success"></i>',
        'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
        'jpg': '<i class="fas fa-file-image text-warning"></i>',
        'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
        'zip': '<i class="fas fa-file-archive text-muted"></i>',
        'htm': '<i class="fas fa-file-code text-info"></i>',
        'txt': '<i class="fas fa-file-text text-info"></i>',
        'mov': '<i class="fas fa-file-movie-o text-warning"></i>',
        'mp3': '<i class="fas fa-file-audio text-warning"></i>',
    },
    previewFileExtSettings: {
        'doc': function(ext) {
            return ext.match(/(doc|docx)$/i);
        },
        'xls': function(ext) {
            return ext.match(/(xls|xlsx)$/i);
        },
        'ppt': function(ext) {
            return ext.match(/(ppt|pptx)$/i);
        },
        'zip': function(ext) {
            return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
        },
        'htm': function(ext) {
            return ext.match(/(php|js|css|htm|html)$/i);
        },
        'txt': function(ext) {
            return ext.match(/(txt|ini|md)$/i);
        },
        'mov': function(ext) {
            return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
        },
        'mp3': function(ext) {
            return ext.match(/(mp3|wav)$/i);
        },
    }
});
</script>
 
</html>
<?php } ?>