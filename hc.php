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
                        $photo=$row["photo"];$signature=$row["signature"]; 
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
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
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>

<body class="form">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Employee Details</span>
        </div>
        <?php  include_once('menu.php');?>
	    <div style="height:54px;">
	    </div> 
    </div>
   <!-- END #sidebar-wrapper --> 
   <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12">
                    <p>&nbsp;</p> <p>&nbsp;</p>
                    <p>
                        <form method="post" action="common/addhc.php"  id="form1" enctype="multipart/form-data">  <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Employee Information</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    
                                    <div class="row">
                                        <div class="col-sm-12">
	                                        <!-- <h4>Basic Information</h4>
	                                        <hr class="form-hr"> --> 
	                                        
		                                    <input type="hidden"  name="acid" id="acid" value="<?php echo $acid;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
                                        </div>
                                        
                                       
            	                        
                                        
  <?php
//check file exist;
if(file_exists("common/upload/hc/$photo.jpg")){
	$photoFilePath = "common/upload/hc/$photo.jpg";
	}else{
		$photoFilePath = "images/blankuserimage.png";
		}
?>                                      
                                        
                                        
                                        <div class="col-sm-12">
                                        <br>
                                            <div class="well">
                                            	<div class="row">
                                                	<div class="col-sm-12">
                                                        <div class="side-by-side">
                                                            <div> 
                                                        	    <img src="<?=$photoFilePath?>" width="100" height="100" id="pic1">
                                                            </div>
                                                            <div>
                                                        <!--strong>Photo</strong-->
                                                                <div class="input-group">
                                                                    <label class="input-group-btn">
                                                                        <span class="btn btn-primary btn-file btn-file">
                                                                            <i class="fa fa-upload"></i> <input type="file" accept="image/*"  capture="camera" name="attachment1" id="attachment1" style="display: none;" >
                                                                        </span>
                                                                    </label>
                                                                    <input type="text" class="form-control" value="Change picture" readonly>
                                                                </div>
                                                                <span class="help-block form-text text-muted">
                                                                    Try selecting one or more files and watch the feedback
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                         
                                        
                                        
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cd">Employee Code*</label>
                                                <input type="text" class="form-control" id="cd" name="cd" value="<?php echo $empcode;?>" required>
                                            </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="fnm">First Name*</label>
                                                <input type="text" class="form-control" id="fnm" name="fnm" value="<?php echo $firstname;?>" required>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="lnm">Last Name</label>
                                                <input type="text" class="form-control" id="lnm" name="lnm" value="<?php echo $lastname;?>">
                                            </div>        
                                        </div> 
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="dob">Date of Birth *</label>
                                            <div class="input-group">
                                                
                                                <input type="text" class="form-control datepicker" id="dob" name="dob" value="<?php echo $dob;?>" required>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbdsg">Gender </label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbdsg" id="cmbdsg" class="form-control">
                                                        <option value="">Select Designation</option>
                                                        <option value="M" <?php if ($gender == 'M') { echo "selected"; } ?>>Male</option>
                                                        <option value="F" <?php if ($gender == 'F') { echo "selected"; } ?>>FemMale</option>
                                                        <option value="O" <?php if ($gender == 'O') { echo "selected"; } ?>>Other</option>
                                                    </select>
                                                </div>
                                          </div>         
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbmartial">Maritial Status </label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbmartial" id="cmbmartial" class="form-control">
                                                        <option value="">Select Marital Status</option>
                                                        <option value="S" <?php if ($maritialstatus == 'S') { echo "selected"; } ?>>Single</option>
                                                        <option value="M" <?php if ($maritialstatus == 'M') { echo "selected"; } ?>>Married</option>
                                                        <option value="D" <?php if ($maritialstatus == 'D') { echo "selected"; } ?>>Divorced</option>
                                                        <option value="O" <?php if ($maritialstatus == 'O') { echo "selected"; } ?>>Others</option>
                                                  </select>
                                              </div>
                                          </div>         
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="phone">Nid</label>
                                                <input type="text" class="form-control" id="nid" name="nid" value="<?php echo $nid;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="email">TIN</label>
                                                <input type="text" class="form-control" id="tin" name="tin" value="<?php echo $tin;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbsrc">Blood Group</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbbg" id="cmbbg" class="form-control">
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
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="pp">Passport</label>
                                                <input type="text" class="form-control" id="pp" name="pp" value="<?php echo $pp;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="drvid">Driving Licence</label>
                                                <input type="text" class="form-control" id="drvid" name="drvid" value="<?php echo $drivinglicense;?>">
                                            </div>        
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <h4>Address Information  </h4>
	                                        <hr class="form-hr">
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="preaddr">Address</label>
                                                <textarea class="form-control" id="preaddr" name="preaddr" rows="4" ><?php echo $presentaddress;?></textarea>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="area">Area</label>
                                                <input type="text" class="form-control" id="area" name="area" value="<?php echo $area;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="district">District</label>
                                                <div class="form-group styled-select">
                                                    <select name="district" id="district" class="form-control">
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
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="zip">Postal Code</label>
                                                <input type="text" class="form-control" id="zip" name="zip" value="<?php echo $zip;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="country">Country</label>
                                                <div class="form-group styled-select">
                                                <select name="country" id="country" class="form-control">
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
                                        
                                        <div class="col-sm-12">
                                            <h4>Contact Information  </h4>
	                                        <hr class="form-hr">
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="off_cont">Official No.</label>
                                                <input type="text" class="form-control" id="off_cont" name="off_cont" value="<?php echo $office_contact;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ext">Extension</label>
                                                <input type="text" class="form-control" id="ext" name="ext" value="<?php echo $ext_contact;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="per_cont">Personal Contact</label>
                                                <input type="text" class="form-control" id="per_cont" name="per_cont" value="<?php echo $pers_contact;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="alt_cont">Alternate Contact</label>
                                                <input type="text" class="form-control" id="alt_cont" name="alt_cont" value="<?php echo $alt_contact;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ofc_email">Official Email</label>
                                                <input type="text" class="form-control" id="ofc_email" name="ofc_email" value="<?php echo $office_email;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="per_email">Personal Email</label>
                                                <input type="text" class="form-control" id="per_email" name="per_email" value="<?php echo $pers_email;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="alt_email">Alternet Email</label>
                                                <input type="text" class="form-control" id="alt_email" name="alt_email" value="<?php echo $alt_email;?>">
                                            </div>        
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <h4>Emergency Contact  </h4>
	                                        <hr class="form-hr">
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc1">1st Emergency Contact</label>
                                                <input type="text" class="form-control" id="poc1" name="poc1" value="<?php echo $emergency_poc1;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc1_rel">Relation</label>
                                                <input type="text" class="form-control" id="poc1_rel" name="poc1_rel" value="<?php echo $poc1_relation;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc1_cont">Contact </label>
                                                <input type="text" class="form-control" id="poc1_cont" name="poc1_cont" value="<?php echo $poc1_contact;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc1_addr">Address </label>
                                                <input type="text" class="form-control" id="poc1_addr" name="poc1_addr" value="<?php echo $poc1_address;?>">
                                            </div>        
                                        </div>
                                         <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc2">2nd Emergency Contact</label>
                                                <input type="text" class="form-control" id="poc2" name="poc2" value="<?php echo $emergency_poc2;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc2_rel">Relation</label>
                                                <input type="text" class="form-control" id="poc2_rel" name="poc2_rel" value="<?php echo $poc2_relation;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc2_cont">Contact </label>
                                                <input type="text" class="form-control" id="poc2_cont" name="poc2_cont" value="<?php echo $poc2_contact;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc2_addr">Address </label>
                                                <input type="text" class="form-control" id="poc2_addr" name="poc2_addr" value="<?php echo $poc2_address;?>">
                                            </div>        
                                        </div>
                                         <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc3">3rd Emergency Contact</label>
                                                <input type="text" class="form-control" id="poc3" name="poc3" value="<?php echo $emergency_poc3;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc3_rel">Relation</label>
                                                <input type="text" class="form-control" id="poc3_rel" name="poc3_rel" value="<?php echo $poc3_relation;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc3_cont">Contact </label>
                                                <input type="text" class="form-control" id="poc3_cont" name="poc3_cont" value="<?php echo $poc3_contact;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="poc3_addr">Address </label>
                                                <input type="text" class="form-control" id="poc3_addr" name="poc3_addr" value="<?php echo $poc3_address;?>">
                                            </div>        
                                        </div>
 
                                    </div>
                                </div> 
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Employee"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Employee"  id="add" >
                                <?php } ?>  
                            <a href = "http://bithut.biz/BitFlow/hcList.php?mod=4">
                                <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                            </a>
                            </div>        
                        </form>       
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
<?php    include_once('common_footer.php');?>
<?php include_once('inc_cmb_loader_js.php');?>

<?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
<script>
var outImage1 ="pic1";
document.getElementById('attachment1').onchange = function (evt) {
    var tgt = evt.target || window.event.srcElement,
        files = tgt.files;

    // FileReader support
    if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function () {
            document.getElementById(outImage1).src = fr.result;
        }
        fr.readAsDataURL(files[0]);
    }

    // Not supported
    else {
        // fallback -- perhaps submit the input to an iframe and temporarily store
        // them on the server until the user's session ends.
    }
}

</script>
</body>
</html>
<?php }?>