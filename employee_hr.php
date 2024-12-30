 <?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}

else
{
    
	//print_r($_SESSION);
	//die;
	
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
    
    

    if ($res==4)
    {
        $qry="select e.`id`, e.`employeecode`, e.`firstname`, e.`lastname`,DATE_FORMAT(e.`dob`,'%e/%c/%Y') `dob`, e.`gender`, e.`maritialstatus`, e.`nid`, e.`tin`
        , e.`bloodgroup`, e.`pp`, e.`drivinglicense`, e.`presentaddress`, e.`area`, e.`district`,d.name disnm, e.`postal`, e.`country`,c.name connm
        , e.`office_contact`, e.`ext_contact`, e.`pers_contact`, e.`alt_contact`, e.`office_email`, e.`pers_email`, e.`alt_email`, e.`emergency_poc1`
        , e.`poc1_relation`, e.`poc1_contact`, e.`poc1_address`, e.`emergency_poc2`, e.`poc2_relation`, e.`poc2_contact`, e.`poc2_address`
        , e.`emergency_poc3`, e.`poc3_relation`, e.`poc3_contact`, e.`poc3_address`, e.`photo`, e.`signature`,
        e.`permanentaddress`, e.`permanentarea`, e.`permanentdistrict`, e.`permanentpostal`, e.`permanentcountry` 
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
                        
                        $permanentaddress=$row["permanentaddress"];$permanentarea=$row["permanentarea"];$permanentdistrict=$row["permanentdistrict"];$permanentzip=$row["permanentpostal"];
                        $permanentcountry=$row["permanentcountry"];
                        
                    }
            }
        }
    $mode=2;//update mode
    $ishire = false; $hiredt = "";
    //get data
    $qryhis = "SELECT a.`id`, a.`hrid`, act.Title actnm, a.`actiondt`, d.name deptnm, jb.Title jbnm, des.name desnm ,concat(emp.firstname, ' ', emp.lastname) empnm,
                    jt.Title jtnm,emp.photo reporttoph, a.actiontype
                    FROM `hraction` a 
                    LEFT JOIN employee emp ON a.`reportto` = emp.id 
                    LEFT JOIN department d ON a.postingdepartment = d.id
                    LEFT JOIN ActionType act ON a.`actiontype` = act.ID 
                    LEFT JOIN designation des ON a.`designation` = des.id 
                    LEFT JOIN JobArea jb ON a.`jobarea` = jb.ID 
                    LEFT JOIN JobType jt ON jt.ID = a.`jobtype`
                    WHERE a.st = 1 and a.type=1 and a.hrid =".$aid." order by a.id DESC";
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
            if($reporttoph == ""){
                $reporttoph = $rowhis["reporttoph"];
            }
            
            if($rowhis["actiontype"] == 1){
                $ishire = true;
                $hiredt = $rowhis["actiondt"];
            }
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
    background: var(--theme);
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
    font-weight:normal;
    color:var(--theme) !important;
    padding-bottom: 10px !important;
    
    
}
.add-btn{
	background-color: var(--theme)!important;
	color: #fff!important;
}

.modal {

    z-index: 1;

}

select,input[type="text"]{
    font-family: roboto;
    font-size: 13px;
    padding: 5px 5px!important;
    height: 30px!important;
}




/* Add this CSS to make the modal smaller and more beautiful */

.small-modal {
  max-width: 700px; /* Adjust the width as needed */
  height: 50px; /* Adjust the height as needed */
  margin: 0 auto;
  border: 1px solid #ccc;
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
  padding: 15px;
}

.modal-content {
  padding: 10px;
}

.modal-header {
  /*display: flex;*/
  justify-content: space-between;
  align-items: center;
  background-color: #f2f2f2;
  padding: 10px;
  border-radius: 5px 5px 0 0;
}

.modal-body {
  padding: 10px;
}
.btn.cus-btn {
  /* Increase height */
  height: 38px;

  /* Increase width */
  width: 150px;
  padding: 6px 16px;
  border: 0;
  border-radius: 1px;
  font-size: 16px;
  line-height: 1.3333333;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
}

/* You can further style the buttons, fonts, and colors to make it beautiful */

    </style>
<link rel="stylesheet" href="./css/ak-bit.css">
<!--<link href="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.css" rel="stylesheet" type="text/css"/>
 <link href="js/plugins/datepicker/datepicker-0.5.2/datepicker_style.css" rel="stylesheet" type="text/css"/>
-->


    <body class="list">
    <?php  include_once('common_top_body.php');?>
        <div id="wrapper">
      <!-- Sidebar -->
            <div id="sidebar-wrapper" class="mCustomScrollbar">
                <div class="section">
  	                <i class="fa fa-group  icon"></i>
                    <span>Employee</span>
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
<?php if(file_exists("common/upload/hc/$photo")){$photoFilePath = "common/upload/hc/$photo"; if($photo == '') {$photoFilePath = "images/bitavatar.png";}}else{$photoFilePath = "images/bitavatar.png";} ?>  
                                <div class="col-md-2 img-col">
                                    <form enctype="multipart/form-data" action="./common/upphoto.php" method="POST" class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' data-oldpic="<?=$photo?>" data-empcode="<?=$empcode?>" id="imageUpload" accept=".png, .jpg, .jpeg" name="avatar" style="display:none;" cl ass="{{ $errors->has('email') ? 'alert alert-danger' : '' }}" />
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
                                         
                        <?php if($mode == 2) { ?>
                                        <div class="cont-location-lab">Contact Details</div>
                                        <div class="cont-no"><?php echo $pers_contact;?></div>
                                        <div class="cont-mail"><?php echo $pers_email;?></div>
                                        <div class="cont-location-lab">Location</div>
                                        <div class="cont-location"><?php echo "$disnm, $connm";?></div>
                            
<?php  } if($mode == 2) {
    if($ishire){
?>
                                    
                                        <div class="cont-hire-date-lab">Hire Date:</div>
                                        <div class="cont-hire-date"><?= $hiredt ?></div>
    <?php } if($reportto[0] != ''){ ?>
                                        <div class="cont-rep-per-lab">Reports to:</div>
                                        <div class="row cont-report-person">
                                            <div class="cols-2">
                                                <img class="rep-per-img img-fluid" src="common/upload/hc/<?= $reporttoph ?>" alt="">
                                            </div>
                                            <div class="cols-10 rep-per-name-col">
                                                <span class="row rep-per-name"><?= $reportto[0] ?></span>
                                            </div>
                                        </div>
<?php }} ?>
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
                                    <!--a myclass="pay-block" class=" pro-tab-name" href="javascript:void(0)">Payroll</a-->
                                    <a myclass="des-block" class=" pro-tab-name" href="javascript:void(0)">Job Description</a>
                                    <!--a myclass="kpi-block" class=" pro-tab-name" href="javascript:void(0)">KPI</a-->
                                    <a myclass="skill-block" class=" pro-tab-name" href="javascript:void(0)">Special Skill</a>
                                <?php } ?>
                                
                                    <span class="alertmsg"></span>
                                </div>
                                <div class="col-md-9 pro-content-body container">
                                        <div class="pro-content">
                                        
                                        <span class="personal-block">

											<?php
                                                include_once('inc/inc_emp_personalinfo.php');
                                            ?>

                                         </span>
                                    <?php if($res == 4){ ?>
                                            <span class="job-block">
												<?php
                                                    include_once('inc/inc_emp_job.php');
                                                ?> 
                                                </span>
                                                <span class="ap-block">
												<?php
                                                    include_once('inc/inc_emp_job.php');
                                                ?> 
                                                </span>
                    <!-- END JOB BLOCK -->
                                            <span class="beni-block">

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
                                                
                                                            

                                                
                                                
                                                
                                            </span>
                                            
                                            
                                            <span class="leave-block">
                                                
                                                            
    
                                                
                                                
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
                                                        <h6><i class="fa fa-folder doc-icon" aria-hidden="true"></i><span class="show-span">
                                                                Posting</span>
                                                        </h6>
                                                    </li>
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 10 ORDER BY id DESC";
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
                                                        <h6><i class="fa fa-folder doc-icon" aria-hidden="true"></i><span class="show-span">
                                                                Appreciation</span>
                                                        </h6>
                                                    </li>
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 12 ORDER BY id DESC";
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
                                                                class="show-span">Punishment</span>
                                                        </h6>
                                                    </li>
                            
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 11 ORDER BY id DESC";
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
                                                                class="show-span">Others</span>
                                                        </h6>
                                                    </li>
                            <?php $qrydoc = "SELECT id, `filename`, `ftype` FROM `documents` WHERE st = 1 and empid = $aid and ftype = 13 ORDER BY id DESC";
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
                            
                                                <!--div class="row doc-row doc-row-2 ">
                            
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
                                                </div-->
                            
                                                <!--div class="row doc-row doc-row-3 ">
                            
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
                                                </div-->
                            
                                                <!--div class="row doc-row doc-row-4 ">
                            
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
                                                </div-->
                            
                                                <!--div class="row doc-row doc-row-5 ">
                            
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
                                                </div-->
                            
                                                <!--div class="row doc-row doc-row-6 ">
                            
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
                                                </div-->
                            
                                                <!--div class="row doc-row doc-row-7 ">
                            
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
                                                </div-->
                                                
<style>
#my-modal9{
    top: 80px;
    height: 600px;
    padding-top: 0px;
    padding: 0;
}

.modal-header .eclose7{
    border:0px solid #fff;
    text-align: right!important;
    padding-right:3px;
}
</style>

<div id="my-modal9" class="modal emod7 small-modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <div class="close eclose7">×</div>
    </div>
    <div class="modal-body">
      <form method="post" action="common/addfilehr.php" id="docmod" enctype="multipart/form-data">
        <h6>Select document type</h6>
        <div class="form-group styled-select">
          <select name="doc-up-mod" class="form-control">
            <option value="1">Resume</option>
            <option value="10">Posting</option>
            <option value="12">Appreciation</option>
            <option value="11">Punishment</option>
            <option value="13">Others</option>
          </select>
        </div>
        
          <div class="file-loading">
            <input id="input-ficons-1" name="input-ficons-1[]" multiple type="file">
         
        </div>
        <input type="hidden" name="empid" value="<?= $aid ?>">
        <button type="submit" class="btn btn-lg btn-default  doc-mod-btn">Submit</button>
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
        <!--div class="container-fluid">
            <div class="page_footer">
                <div class="row">
                    <div class="col-xs-2"><a class="" href="http://www.bithut.biz/" target="_blank" bo><img src="images/logo_bithut_sm.png" height="30" border="0"></a></div>
                    <div class="col-xs-10  copyright">Copyright © <a class="" href="http://www.bithut.biz/" target="_blank">Bithut Ltd.</a></div>
                </div>
            </div>
        </div-->      
        <?php
        include_once('common_footer.php');
    ?>
<!-- /#page-footer -->

    

<!-- Delete Document -->
<script>

// function delobj(docid){
//     window.location.href = "./common/delobj.php?retid=<?= $aid ?>&obj=documents&ret=employee_hr&res=4&mod=4&id=".concat(docid);
// }
function delobj(docid) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this document!",
        icon: "warning",
        buttons: {
            cancel: "No",
            confirm: "Yes",
        },
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            // User clicked "Yes", proceed with deletion
            window.location.href = "./common/delobj.php?retid=<?= $aid ?>&obj=documents&ret=employee_hr&res=4&mod=4&id=" + docid;
        } else {
            // User clicked "No", do nothing
            return false;
        }
    });
}

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
			
			switch(mclass){
				
					case 'beni-block':
					$(".beni-block").html("Loading...");
					$(".beni-block").load("phpajax/emp/benefits.php?id=<?=$aid?>");
					break;				
				
					case 'atten-block':
					$(".atten-block").html("Loading...");
					$(".atten-block").load("phpajax/emp/attendance.php?id=<?=$aid?>");
					break;
					case 'leave-block':
					$(".leave-block").html("Loading...");
					$(".leave-block").load("phpajax/emp/leave.php?id=<?=$aid?>");
					break;
					
					

				}
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
       // $("#imageUpload").change(function () {
      //      readURL(this);
           
       // });

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
        $('input[type="checkbox"][name="tax"]').each(function(){
              if($(this).is(":checked")){
                window.open($(this).val());
            }
        });
        $('input[type="checkbox"][name="jl"]').each(function(){
              if($(this).is(":checked")){
                window.open($(this).val());
            }
        });
        $('input[type="checkbox"][name="rl"]').each(function(){
              if($(this).is(":checked")){
                window.open($(this).val());
            }
        });
        $('input[type="checkbox"][name="sc"]').each(function(){
              if($(this).is(":checked")){
                window.open($(this).val());
            }
        });
        $('input[type="checkbox"][name="aa"]').each(function(){
              if($(this).is(":checked")){
                window.open($(this).val());
            }
        });
    });
    
   
    $("#filter_date_from, #filter_date_to").change(function () {
            var dtfrom = $( "#filter_date_from" ).val();
            var dtto = $( "#filter_date_to" ).val();
            $(".atten-block").html("Loading...");
			$(".atten-block").load("phpajax/emp/attendance.php?id=<?=$aid?>&fd="+dtfrom+"&td="+dtto);
           
    });

})
</script>
<!--<script src="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.js"></script>
--><!-- Date Picker  ==================================== 

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
/*window.onload = function(){ 
    $('#imageUpload').click(function(){
        $('#dp-btn').trigger('click'); 
        
    });
 };*/
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

        var fd = 	new FormData();
        var files = $('#imageUpload')[0].files;
		var oldpic = $('#imageUpload').data('oldpic');
		var empcode = $('#imageUpload').data('empcode');
		
		//alert(oldpic);
        
        // Check file selected or not
        if(files.length > 0 ){
           fd.append('file',files[0]);
			
			
			
           $.ajax({
              url: 'phpajax/uploadimagehc.php?oldpic='+oldpic+'&empcode='+empcode,
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){
                  
				  //alert(response);
				  
                  if(response == 2){
                         swal("Error!", "Please select at least (500 X 500) size picture", "error");
                  }else if(response != 0){

					 //alert(response);
					  $('#dp-imagePreview').attr("style","background-image: url("+response+") !important");
					 //$('#ajax-img-up li:last').before('<li class="picbox"><u class="fa fa-trash"></u><label class="custom-radio"><input checked type="radio" id="picid_'+picid+'" name="default-pic" value="'+response+'"><div class="radio-btn"><i class="fas fa-check" aria-hidden="true"></i><img src="'+response+'"><input type="hidden" name="imgfiles[]" value="'+response+'"></div><label></li>');
					swal("Success!", "Profile picture updated", "success");
					 picid++;
					 
					 //alert(response);
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


<!-- SWEET ALERT -->
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>


</html>
<?php } ?>