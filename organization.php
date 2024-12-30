<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    $res = $_GET['res'];
    $msg = $_GET['msg'];
    $oid = $_GET['id'];

    if ($res == 4) { // edit;
        $qry = "SELECT `id`,`orgcode`,`name`, `contactperson`, `contactno`, `industry`, `employeesize`, `email`, `website`,address, `area`, `street`, `district`, `state`, `zip`, `country`, `operationstatus`, `bsnsvalue`,`details`,`billingpoc`,`techpoc`,`salesperson`,`note`, vendor FROM `organization` where id= " . $oid;
        // echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $orid            = $row["id"];
                    $orgcode         = $row["orgcode"];
                    $name            = $row["name"];
                    $contactperson   = $row["contactperson"];
                    $phone           = $row["contactno"];
                    $industry        = $row["industry"];
                    $employeesize    = $row["employeesize"];
                    $email           = $row["email"];
                    $website         = $row["website"];
                    $address         = $row["address"];
                    $area            = $row["area"];
                    $street          = $row["street"];
                    $district        = $row["district"];
                    $state           = $row["state"];
                    $zip             = $row["zip"];
                    $country         = $row["country"];
                    $operationstatus = $row["operationstatus"];
                    $bsnsvalue       = $row["bsnsvalue"];
                    $details         = $row["details"];
                    $billingpoc      = $row["billingpoc"];
                    $techpoc         = $row["techpoc"];
                    $salesperson     = $row["salesperson"];
                    $note            = $row["note"];
                    $vendor          = $row["vendor"];
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    } else {
        $orid            = '';
        $name            = '';
        $contactperson   = '';
        $phone           = '';
        $industry        = '';
        $employeesize    = '0';
        $email           = '';
        $website         = '';
        $address         = '';
        $area            = '';
        $street          = '';
        $district        = '';
        $state           = '';
        $zip             = '';
        $country         = '';
        $operationstatus = '';
        $bsnsvalue       = '0';
        $details         = '';
        $salesperson     = '';
        $note            = '';
        $vendor          = '';

        $mode = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'organization';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>

<body class="form org">
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Customer Details</span>
        </div>
        <?php include_once 'menu.php'; ?>
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
                        <form method="post" action="common/addorganization.php"  id="form1" enctype="multipart/form-data">  <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
<!--      			                <div class="panel-heading"><h1>Organization Information</h1></div>-->
				                <div class="panel-body panel-body-padding">
                                    <span class="alertmsg"></span>

										<div class="row form-header">

	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>CRM <i class="fa fa-angle-right"></i> <a href="javascript:history.back();">Customers</a> <i class="fa fa-angle-right"></i> Add Customer</h6>
      		                            </div>

      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> <!--(Field Marked * are required)--></span></h6>
      		                            </div>


                                   </div>


                                    <!-- <p>(Field Marked * are required) </p> -->

                                    <div class="row">
      		                            <div class="col-sm-12">
	                                        <!-- <h4></h4>
	                                        <hr class="form-hr"> -->
		                                    <input type="hidden"  name="orid" id="orid" value="<?php echo $orid; ?>">
		                                    <input type="hidden"  name="orcd" id="orcd" value="<?php echo $orgcode; ?>">
		                                     <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
	                                    </div> <!-- id -->
                                        <div class="col-lg-6 col-md-12 col-sm-12 left-col">
                                            <div class="row">
                                                <h5 class="sub-title">Required Informations*</h5>
                                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cnnm">Name<span class="redstar">*</span></label>
                                                        <input type="text" class="form-control" id="cnnm" name="cnnm" value="<?php echo $name; ?>" required>
                                                    </div>
                                                </div> <!-- Name -->

                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="phone">Organization Phone<span class="redstar">*</span></label>
                                                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" required>
                                                    </div>
                                                </div>  <!--Contact no -->

                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="email">Organization Email<span class="redstar">*</span></label>
                                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                                                    </div>
                                                </div><!--email -->

                                        <!--div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="email">Address*</label>
                                                        <input type="text" class="form-control" id="addr" name="addr" value="<?php echo $address; ?>" required>
                                                    </div>
                                                </div--><!--addr -->
                                         <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="street">Address</label>
                                                <input type="text" class="form-control" id="street" name="street" value="<?php echo $street; ?>" >
                                            </div>
                                        </div> <!--street -->
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="area">Area </label>
                                                <div class="form-group styled-select">
                                                <select name="area" id="area" class="form-control">
                                                <option value="">Select Type</option>
<?php $qrycntp = "SELECT `id`, `name` FROM `area`  order by name";
    $resultcntp    = $conn->query($qrycntp);if ($resultcntp->num_rows > 0) {while ($rowcntp = $resultcntp->fetch_assoc()) {
        $tid = $rowcntp["id"];
        $nm  = $rowcntp["name"];
        ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($area == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
<?php }} ?>
                                                  </select>
                                                  </div>
                                          </div>
                                        </div> <!--area -->
                                   

                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="district">District</label>
                                                <div class="form-group styled-select">
                                                <select name="district" id="district" class="form-control" >
                                                <option value="">Select District</option>
<?php $qrydis = "SELECT `id`, `name` FROM `district`  order by name";
    $resultdis    = $conn->query($qrydis);if ($resultdis->num_rows > 0) {while ($rowdis = $resultdis->fetch_assoc()) {
        $tid = $rowdis["id"];
        $nm  = $rowdis["name"];
        ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($district == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
<?php }} ?>
                                                  </select>
                                                  </div>
                                          </div>
                                        </div> <!--district -->

                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="state">State</label>
                                                <div class="form-group styled-select">
                                                <select name="state" id="state" class="form-control" >
                                                <option value="">Select State</option>
<?php $qrystate = "SELECT `id`, `name` FROM `state`  order by name";
    $resultstate    = $conn->query($qrystate);if ($resultstate->num_rows > 0) {while ($rowstate = $resultstate->fetch_assoc()) {
        $tid = $rowstate["id"];
        $nm  = $rowstate["name"];
        ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($state == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
<?php }} ?>
                                                  </select>
                                                  </div>
                                          </div>
                                        </div><!--state -->

                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="zip">ZIP Code</label>
                                                <input type="text" class="form-control" id="zip" name="zip" value="<?php echo $zip; ?>" >
                                            </div>
                                        </div> <!--Zip -->

                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="country">Country</label>
                                                <div class="form-group styled-select">
                                                <select name="country" id="country" class="form-control" >
                                                <option value="">Select Country</option>
<?php $qrycon = "SELECT `id`, `name` FROM `country`  order by name";
    $resultcon    = $conn->query($qrycon);if ($resultcon->num_rows > 0) {while ($rowcon = $resultcon->fetch_assoc()) {
        $tid = $rowcon["id"];
        $nm  = $rowcon["name"];
        ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($country == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
<?php }} ?>
                                                  </select>
                                                  </div>
                                          </div>
                                        </div> <!--Country -->






                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 right-col">
                                            <div class="row">
                                                <h5 class="sub-title">Additional Information</h5>

                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbindtype">Industry Type</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbindtype" id="cmbindtype" class="form-control" >
                                                <option value="">Select Type</option>
<?php $qrycntp = "SELECT `id`, `name` FROM `businessindustry`  order by name";
    $resultcntp    = $conn->query($qrycntp);if ($resultcntp->num_rows > 0) {while ($rowcntp = $resultcntp->fetch_assoc()) {
        $tid = $rowcntp["id"];
        $nm  = $rowcntp["name"];
        ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($industry == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
<?php }} ?>
                                                  </select>
                                                  </div>
                                          </div>
                                        </div> <!--Industry -->

      	                                <!--div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="empsz">Employee Size</label>
                                                <input type="text" class="form-control" id="empsz" name="empsz" value="<?php echo $employeesize; ?>" >
                                            </div>
                                        </div--> <!--Employee size -->

                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbopttype">Reference</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbopttype" id="cmbopttype" class="form-control" >
                                                <option value="">Select Type</option>
<?php $qrycntp = "SELECT `id`, `name` FROM `operationstatus`  order by name";
    $resultcntp    = $conn->query($qrycntp);if ($resultcntp->num_rows > 0) {while ($rowcntp = $resultcntp->fetch_assoc()) {
        $tid = $rowcntp["id"];
        $nm  = $rowcntp["name"];
        ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($operationstatus == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
<?php }} ?>
                                                  </select>
                                                  </div>
                                          </div>
                                        </div> <!--Operation Status -->

                                         <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="bv">Business value </label>
                                                <input type="text" class="form-control" id="bv" name="bv" value="<?php echo $bsnsvalue; ?>" >
                                            </div>
                                        </div> <!--Buisness Value -->



                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="web">Website</label>
                                                <input type="text" class="form-control" id="web" name="web" value="<?php echo $website; ?>" >
                                            </div>
                                        </div> <!--Website -->

                                        <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="area">Area</label>
                                                <input type="text" class="form-control" id="area" name="area" value="<?php echo $area; ?>">
                                            </div>
                                        </div> -->
                                          <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbitmcat">Account Manager</label>
                                            <div class="form-group styled-select">
                                                <select name="cmbhrmgr" id="cmbhrmgr" class="form-control" >
                                                <option value="">Select Account Manager</option>
    <?php $qryhrm = "SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id` FROM `hr` h,`employee` e where h.`emp_id`=e.`employeecode`  order by emp_id";
    $resulthrm        = $conn->query($qryhrm);if ($resulthrm->num_rows > 0) {while ($rowhrm = $resulthrm->fetch_assoc()) {
        $hridm = $rowhrm["id"];
        $hrnmm = $rowhrm["emp_id"];
        ?>
                                                    <option value="<?php echo $hridm; ?>" <?php if ($salesperson == $hridm) {echo "selected";} ?>><?php echo $hrnmm; ?></option>
    <?php }} ?>
                                                  </select>
                                              </div>
                                        </div>
                                    </div>
                                    
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="zip">Vendor</label>
                                                <input type="checkbox" id="vendor" name="vendor" value="1" <?php if($vendor == 1) echo "checked" ?>>
                                            </div>
                                        </div> <!--Note -->                                    
                                    
                                    
                                    
                                        <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="descr">Detail</label>
                                                <input type="text" class="form-control" id="descr" name="descr" value="<?php echo $details; ?>" >
                                            </div>
                                        </div> <br> -->
                                          <!-- <div class="col-lg-12 col-md-12 col-sm-12"> <!--ORG Details-->
                                            <!-- <div class="form-group">
                                                <label for="ref">Issue Details</label>
                                                <textarea class="form-control"  rows="4" id="descr" name="descr" value="<?php echo $details; ?>"> </textarea>

                                            </div>
                                        </div><!--ORG Details-->
                                        </div>
                                        </div>
                                    <div class="po-product-wrapper">
                                        <div class="color-block">

     		                                <div class="col-sm-12">
	                                            <h4>Contact Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if ($mode == 1) {for ($y = 1; $y <= 1; $y++) { ?>
                                             <div class="row">
                                            <div class="col-lg-2 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10 "> Account Manager </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Name </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Email </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Phone </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Select Designation</h6>
                                                </div>

                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Select Department </h6>
                                                </div>


                                        </div>

	                                        <div class="toClone">
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
          	                                        <input type="hidden"  name="contactid[]" id="contactid">
          	                                        <input type="hidden"  name="orgconid[]" id="orgconid">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                      <select name="conttype[]" id="conttype" class="form-control">
                                                     <!-- <option value="">Select Type</option> -->
 <?php $qryunit = "SELECT `id`, `name` FROM `orgcontacttype` where 1=1 order by name";
        $resultunit     = $conn->query($qryunit);if ($resultunit->num_rows > 0) {while ($rowunit = $resultunit->fetch_assoc()) {
            $unitid = $rowunit["id"];
            $unitnm = $rowunit["name"];
            ?>
                                                        <option value="<?php echo $unitid; ?>"><?php echo $unitnm; ?></option>
     <?php }} ?>
                                                      </select>
                                                      </div>
                                                  </div>
                                                </div>
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="contname" placeholder="Name" name="contname[]">
                                                    </div>
                                                </div>
     	                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="contemail" placeholder="Email" name="contemail[]">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="contphone" placeholder="Phone" name="contphone[]">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                            <select name="cmbdsg[]" id="cmbdsg" class="form-control" >
                                                                <option value="">Select Designation</option>
        <?php $qrydsg = "SELECT `id`, `name` FROM `crm_designation` order by name";
        $resultdsg            = $conn->query($qrydsg);if ($resultdsg->num_rows > 0) {while ($rowdsg = $resultdsg->fetch_assoc()) {
            $did = $rowdsg["id"];
            $dnm = $rowdsg["name"];
            ?>
                                                                <option value="<?php echo $did; ?>" <?php if ($desg == $did) {echo "selected";} ?>><?php echo $dnm; ?></option>
        <?php }} ?>
                                                            </select>
                                                      </div>
                                                  </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                            <select name="cmbdept[]" id="cmbdept" class="form-control" >
                                                                <option value="">Select Department</option>
        <?php $qrydsg = "SELECT `id`, `name` FROM `crm_department` order by name";
        $resultdsg            = $conn->query($qrydsg);if ($resultdsg->num_rows > 0) {while ($rowdsg = $resultdsg->fetch_assoc()) {
            $deptid = $rowdsg["id"];
            $deptnm = $rowdsg["name"];
            ?>
                                                                <option value="<?php echo $deptid; ?>" <?php if ($dept == $deptid) {echo "selected";} ?>><?php echo $deptnm; ?></option>
        <?php }} ?>
                                                            </select>
                                                      </div>
                                                  </div>
                                                </div>
                                            </div>
<?php }} else {
        $rCountLoop = 0;
        $itdgt      = 0;
        $itmdtqry   = "SELECT o.`id`, o.`organization`, o.`contact`, t.id `conatcttype`, o.`name`, o.`email`, o.`phone`,o.`designation`,o.`department` FROM  orgcontacttype t RIGHT JOIN orgaContact o ON t.id=o.conatcttype WHERE o.`organization`= '" . $orgcode . "' and o.id IS NOT NULL order by id";
//echo $itmdtqry; die;
        $resultitmdt = $conn->query($itmdtqry);if ($resultitmdt->num_rows > 0) {while ($rowitmdt = $resultitmdt->fetch_assoc()) {
            $orgcntid    = $rowitmdt["id"];
            $conatcttype = $rowitmdt["conatcttype"];
            $conatctcd   = $rowitmdt["contact"];
            $name        = $rowitmdt["name"];
            $email       = $rowitmdt["email"];
            $phone       = $rowitmdt["phone"];
            $desg        = $rowitmdt["designation"];
            $dept        = $rowitmdt["department"];
            // echo $conatctcd;die; ?>
                                            <!-- this block is for php loop, please place below code your loop  -->
                                            <div class="toClone">

          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
          	                                        <input type="hidden" value="<?php echo $conatctcd; ?>" name="contactid[]" id="contactid">
          	                                        <input type="hidden" value="<?php echo $orgcntid; ?>" name="orgconid[]" id="orgconid">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                      <select name="conttype[]" id="conttype" class="form-control">
 <?php //and `id`=".$itdmu."
            $qrymu    = "SELECT `id`, `name` FROM `orgcontacttype` order by name";
            $resultmu = $conn->query($qrymu);if ($resultmu->num_rows > 0) {while ($rowmu = $resultmu->fetch_assoc()) {
                $mid = $rowmu["id"];
                $mnm = $rowmu["name"];
                ?>
                                                        <option value="<?php echo $mid; ?>" <?php if ($conatcttype == $mid) {echo "selected";} ?>><?php echo $mnm; ?></option>
<?php }} ?>
                                                      </select>
                                                      </div>
                                                  </div>
                                                </div>


          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="contname" value="<?php echo $name; ?>" name="contname[]">
                                                    </div>
                                                </div>

         	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="contemail" value="<?php echo $email; ?>" name="contemail[]">
                                                    </div>
                                                </div>


                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="contphone" value="<?php echo $phone; ?>" name="contphone[]">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                            <select name="cmbdsg[]" id="cmbdsg" class="form-control" >
                                                                <option value="">Select Design</option>
        <?php $qrydsg = "SELECT `id`, `name` FROM `crm_designation` order by name";
            $resultdsg            = $conn->query($qrydsg);if ($resultdsg->num_rows > 0) {while ($rowdsg = $resultdsg->fetch_assoc()) {
                $did = $rowdsg["id"];
                $dnm = $rowdsg["name"];
                ?>
                                                                <option value="<?php echo $did; ?>" <?php if ($desg == $did) {echo "selected";} ?>><?php echo $dnm; ?></option>
        <?php }} ?>
                                                            </select>
                                                      </div>
                                                  </div>
                                                </div>

                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                            <select name="cmbdept[]" id="cmbdept" class="form-control" >
                                                                <option value="">Select Department</option>
        <?php $qrydsg = "SELECT `id`, `name` FROM `crm_department` order by name";
            $resultdsg            = $conn->query($qrydsg);if ($resultdsg->num_rows > 0) {while ($rowdsg = $resultdsg->fetch_assoc()) {
                $deptid = $rowdsg["id"];
                $deptnm = $rowdsg["name"];
                ?>
                                                                <option value="<?php echo $deptid; ?>" <?php if ($dept == $deptid) {echo "selected";} ?>><?php echo $deptnm; ?></option>
        <?php }} ?>
                                                            </select>
                                                      </div>
                                                  </div>
                                                </div>

                                               
<?php if ($rCountLoop > 0) { ?>
                                           		<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                            <?php } $rCountLoop++; ?>

                                            </div>
                                            <!--div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div-->
<?php }} else {
            ?>
                                            <div class="toClone">
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
          	                                        <input type="hidden" value="" name="contactid[]" id="contactid">
          	                                        <input type="hidden"  name="orgconid[]" id="orgconid">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                      <select name="conttype[]" id="conttype" class="form-control">
                                                      <option value="">Select Type</option>
 <?php $qryunit = "SELECT `id`, `name` FROM `orgcontacttype` order by name";
            $resultunit     = $conn->query($qryunit);if ($resultunit->num_rows > 0) {while ($rowunit = $resultunit->fetch_assoc()) {
                $unitid = $rowunit["id"];
                $unitnm = $rowunit["name"];
                ?>
                                                        <option value="<?php echo $unitid; ?>"><?php echo $unitnm; ?></option>
     <?php }} ?>
                                                      </select>
                                                      </div>
                                                  </div>
                                                </div>
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="contname" placeholder="Name" name="contname[]">
                                                    </div>
                                                </div>
     	                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="contemail" placeholder="Email" name="contemail[]">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="contphone" placeholder="Phone" name="contphone[]">
                                                    </div>
                                                </div>

                                                 <div class="col-lg-2 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                            <select name="cmbdsg[]" id="cmbdsg" class="form-control" >
                                                                <option value="">Select Design</option>
        <?php $qrydsg = "SELECT `id`, `name` FROM `crm_designation` order by name";
            $resultdsg            = $conn->query($qrydsg);if ($resultdsg->num_rows > 0) {while ($rowdsg = $resultdsg->fetch_assoc()) {
                $did = $rowdsg["id"];
                $dnm = $rowdsg["name"];
                ?>
                                                                <option value="<?php echo $did; ?>" <?php if ($desg == $did) {echo "selected";} ?>><?php echo $dnm; ?></option>
        <?php }} ?>
                                                            </select>
                                                      </div>
                                                  </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                            <select name="cmbdept[]" id="cmbdept" class="form-control" >
                                                                <option value="">Select Department</option>
        <?php $qrydsg = "SELECT `id`, `name` FROM `crm_department` order by name";
            $resultdsg            = $conn->query($qrydsg);if ($resultdsg->num_rows > 0) {while ($rowdsg = $resultdsg->fetch_assoc()) {
                $deptid = $rowdsg["id"];
                $deptnm = $rowdsg["name"];
                ?>
                                                                <option value="<?php echo $deptid; ?>" <?php if ($dept == $deptid) {echo "selected";} ?>><?php echo $deptnm; ?></option>
        <?php }} ?>
                                                            </select>
                                                      </div>
                                                  </div>
                                                </div>
                                            </div>
<?php }} ?>
                                    		<!-- this block is for php loop, please place below code your loop  -->
                                        </div>

                                    </div>
                                    <br>&nbsp;<br>
                                    <div class="row add-btn-wrapper">
											<div class="col-sm-12">
											<?php
												//echo $mode;
													$addClassName = ($mode == "1") ? 'link-add-po' : 'link-add-po-2';
													?>
												<a href="#" title="Add Item" class="link-service-order" ><span class="glyphicon glyphicon-plus"></span> </a>
											</div>	
										</div>
    	                            
    	                            <div class="form-group">
                                                <label for="details">Note </label>
                                                <textarea class="form-control" id="note" name="note" rows="4" ><?php echo $note;?></textarea>
                                            </div>

                                    </div>
                                </div>
                            </div>
                            <!-- /#end of panel -->
                            <div class="button-bar">
                                <?php if ($mode == 2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Organization"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Organization"  id="add" >
                                <?php } ?>
                            <a href = "./organizationList.php?pg=1&mod=3">
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
<?php include_once 'common_footer.php'; ?>

<?php include_once 'inc_cmb_loader_js.php'; ?>

<?php

    if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }

    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>
    
    <script>

//COPIER
	
$(document).ready(function() {
    var max_fields      = 500; //maximum input boxes allowed
    var wrapper         = $(".color-block"); //Fields wrapper
    var add_button      = $(".link-service-order"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper .toClone:last-child").clone().appendTo(wrapper);
    
    	$( ".po-product-wrapper .toClone:last-child input").val("");
  

		if(x==2){
			$( ".po-product-wrapper .toClone:last-child").append('<div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}

        }
        
        
        
        
        
        
        
    });

    $(wrapper).on("click",".remove-order", function(e){ //user click on remove text
        e.preventDefault();
		$(this).closest(".toClone").remove();
		var root = $(this).closest('.toClone');
        totalvalue()
		x--;
		
    })
});	
	
</script>

</body>
</html>
<?php } ?>