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
        $qry="SELECT `id`, `contactcode`, `lead_state`, `name`, `organization`, `dob`, `designation`, `department`, `phone`, `email`, `photo`,`location`, `website`, `source`, `sourcename`, `details`, `area`, `street`, `district`, `state`, `zip`, `country`, `opendt`, `status`, `makeby`, `makedt` FROM `contact`  where id= ".$aid; 
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
                        $ldid=$row["id"];$contactcode=$row["contactcode"];$lead_state=$row["lead_state"];  $name=$row["name"];$organization=$row["organization"];  $dob=$row["dob"];
                        $designation=$row["designation"];  $department=$row["department"];$phone=$row["phone"];  $email=$row["email"];$photo=$row["photo"];  $location=$row["location"];
                        $source=$row["source"];  $sourcename=$row["sourcename"];$details=$row["details"];  $area=$row["area"];$street=$row["street"];  $district=$row["district"];
                        $state=$row["state"];$zip=$row["zip"]; $country=$row["country"];$website=$row["website"];
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
      $ldid='';$contactcode='';$lead_state='';  $name='';$organization='';  $dob='';
                        $designation='';  $department='';$phone='';  $email='';$photo='';  $location='';
                        $source='';  $sourcename='';$details='';  $area='';$street='';  $district='';
                        $state=''; $zip=''; $country='';$website='';
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'lead';
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
            <span>Lead Details</span>
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
                        <form method="post" action="common/addlead.php"  id="form1" enctype="multipart/form-data">  <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Lead Information</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>
                                    <div class="row">
      		                            <div class="col-sm-12">
	                                        <h4></h4>
	                                        <hr class="form-hr">
		                                    <input type="hidden"  name="ldid" id="ldid" value="<?php echo $ldid;?>">
		                                    <input type="hidden"  id="cd" name="cd" value="<?php echo $contactcode;?>">
		                                     <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
	                                    </div>      
            	                       <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cd">Lead Code</label>
                                                <input type="hidden"  id="cd" name="cd" value="<?php echo $contactcode;?>">
                                            </div>        
                                        </div>-->
  	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcontype">Lead Status *</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbcontype" id="cmbcontype" class="form-control" required>
                                                <option value="">Select Type</option>
<?php $qrycntp="SELECT `id`, `name` FROM `leadstatus`  order by name"; $resultcntp = $conn->query($qrycntp); if ($resultcntp->num_rows > 0) {while($rowcntp = $resultcntp->fetch_assoc()) 
              { 
                  $tid= $rowcntp["id"];  $nm=$rowcntp["name"];
    ?>                                                         
                                                    <option value="<?php echo $tid; ?>" <?php if ($lead_state == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>         
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cnnm">Name *</label>
                                                <input type="text" class="form-control" id="cnnm" name="cnnm" value="<?php echo $name;?>" required>
                                            </div>        
                                        </div>
                                      <!--  <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="org">Organization</label>
                                                <input type="text" class="form-control" id="org" name="org" value="<?php echo $organization;?>">
                                            </div>        
                                        </div> -->
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="org">Organization*</label>
                                                <div class="form-group styled-select">
                                                <select name="org" id="org" class="form-control" required>
                                                <option value="">Select Organization</option>
<?php $qryorg="SELECT `id`, `name` FROM `organization`  order by name"; $resultorg = $conn->query($qryorg); if ($resultorg->num_rows > 0) {while($roworg = $resultorg->fetch_assoc()) 
              { 
                  $id= $roworg["id"];  $nm=$roworg["name"];
    ?>                                                         
                                                    <option value="<?php echo $id; ?>" <?php if ($organization == $id) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>         
                                        </div>
                                        
                                         <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="dob">Date of Birth</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="dob" name="dob" value="<?php echo $dob;?>">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbdsg">Designation * </label>
                                                <div class="form-group styled-select">
                                                <select name="cmbdsg" id="cmbdsg" class="form-control" required>
                                                <option value="">Select Designation</option>
<?php $qrydsg="SELECT `id`, `name` FROM `designation`  order by name"; $resultdsg = $conn->query($qrydsg); if ($resultdsg->num_rows > 0) {while($rowdsg = $resultdsg->fetch_assoc()) 
              { 
                  $id= $rowdsg["id"];  $nm=$rowdsg["name"];
    ?>                                                         
                                                    <option value="<?php echo $id; ?>" <?php if ($designation == $id) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>         
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbdpt">Department </label>
                                                <div class="form-group styled-select">
                                                <select name="cmbdpt" id="cmbdpt" class="form-control">
                                                <option value="">Select Department</option>
<?php $qrydpt="SELECT `id`, `name` FROM `department`  order by name"; $resultdpt = $conn->query($qrydpt); if ($resultdpt->num_rows > 0) {while($rowdpt = $resultdpt->fetch_assoc()) 
              { 
                  $tid= $rowdpt["id"];  $nm=$rowdpt["name"];
    ?>                                                         
                                                    <option value="<?php echo $tid; ?>" <?php if ($department == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>         
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="phone">Phone *</label>
                                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone;?>" required>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Email *</label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>" required>
                                            </div>        
                                        </div>
                                       <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="loc">Location</label>
                                                <input type="text" class="form-control" id="loc" name="loc" value="<?php echo $location;?>">
                                            </div>        
                                        </div> -->
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="web">Website</label>
                                                <input type="text" class="form-control" id="web" name="web" value="<?php echo $website;?>">
                                            </div>        
                                        </div>
                                         <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbsrc">Source</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbsrc" id="cmbsrc" class="form-control">
                                                <option value="">Select Source</option>
<?php $qrysrc="SELECT `id`, `name` FROM `source`  order by name"; $resultsrc = $conn->query($qrysrc); if ($resultsrc->num_rows > 0) {while($rowsrc = $resultsrc->fetch_assoc()) 
              { 
                  $tid= $rowsrc["id"];  $nm=$rowsrc["name"];
    ?>                                                         
                                                    <option value="<?php echo $tid; ?>" <?php if ($source == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>         
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="srcnm">Source Details</label>
                                                <input type="text" class="form-control" id="srcnm" name="srcnm" value="<?php echo $sourcename;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="descr">Detail</label>
                                                <input type="text" class="form-control" id="descr" name="descr" value="<?php echo $details;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="area">area</label>
                                                <input type="text" class="form-control" id="area" name="area" value="<?php echo $area;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="street">Street</label>
                                                <input type="text" class="form-control" id="street" name="street" value="<?php echo $street;?>">
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
                                                <label for="state">State</label>
                                                <div class="form-group styled-select">
                                                <select name="state" id="state" class="form-control">
                                                <option value="">Select State</option>
<?php $qrystate="SELECT `id`, `name` FROM `state`  order by name"; $resultstate = $conn->query($qrystate); if ($resultstate->num_rows > 0) {while($rowstate = $resultstate->fetch_assoc()) 
              { 
                  $tid= $rowstate["id"];  $nm=$rowstate["name"];
    ?>                                                         
                                                    <option value="<?php echo $tid; ?>" <?php if ($state == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>         
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="zip">ZIP Code</label>
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
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                        <strong>Photo</strong>
                                        <div class="input-group">
                                            <label class="input-group-btn">
                                                <span class="btn btn-primary btn-file btn-file">
                                                    <i class="fa fa-upload"></i> <input type="file" name="attachment1" id="attachment1" style="display: none;" >
                                                </span>
                                            </label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                        <span class="help-block form-text text-muted">
                                            Try selecting one or more files and watch the feedback
                                        </span>
                                    </div>
      	                               
                                    </div>
                                </div> 
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Lead"  id="update" >
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="convert" value="Convert to Customer"  id="convert" ><!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Lead"  id="add" >
                                <?php } ?>  
                                <input class="btn btn-lg btn-default" type="button" name="cancel" value="cancel"  id="cancel" >
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
</body>
</html>
<?php }?>