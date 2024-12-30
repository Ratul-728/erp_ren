<?php
//print_r($_REQUEST);
//exit();

require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
//echo $usr;die;
if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {
    $res       = $_GET['res'];
    $msg       = $_GET['msg'];
    $aid       = $_GET['id'];
    $serno     = $_GET['id'];
    $totamount = 0;

    if ($res == 4) {
        //echo "<script type='text/javascript'>alert('".$id."')</script>";
        $qry = "SELECT h.`hrid`,h.`compansation`,h.`increment`,h.`privilagedfund`, DATE_FORMAT( h.`effectivedate`,'%d/%m/%Y') `effectivedate`,h.`Description`,  concat(emp.`firstname`, ' ', emp.`lastname`) empname, emp.employeecode 
                FROM `hrcompansation` h left join employee emp on h.hrid=emp.id WHERE h.id = " . $aid . " LIMIT 1";
        //echo $qry; die;
        if ($conn->connect_error) {echo "Connection failed: " . $conn->connect_error;} else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $hrid = $row["hrid"];
                    $hrnm                            = $row["empname"]."-[Code: ".$row["employeecode"]."]";
                    $coms = $row["compansation"];
                    $incr = $row["increment"];
                    $bamount = $row["privilagedfund"];
                    $action_dt = $row["effectivedate"];
                    $condition = $row["Description"];
                    
                }
            }
        }
        $mode = 2; //update mode
    } else {
        $hrid       = '';
        $coms       = '';
        $companCode = '';
        $mode       = 1; //Insert mode
    }

    $currSection = 'hrcompansation';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>
<style>
    .toClone.h90px {
   height: 90px;
}
</style>
    <body class="form soitem">
    <?php include_once 'common_top_body.php'; ?>
        <div id="wrapper">
    <!-- Sidebar -->
            <div id="sidebar-wrapper" class="mCustomScrollbar">
                <div class="section">
  	                <i class="fa fa-group  icon"></i>
                    <span>HR Compansation</span>
                </div>
                <?php include_once 'menu.php'; ?>
                <div style="height:54px;"></div>
            </div>
    <!-- END #sidebar-wrapper -->
    <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid pagetop">
                <div class="row">
                    <div class="col-lg-12">
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <!--h1 class="page-title">Customers</a></h1-->
                        <p>
                           <form method="post" action="common/addhrcompansation.php" id="form1" enctype="multipart/form-data">
                        <!-- START PLACING YOUR CONTENT HERE -->
                                <div class="panel panel-info">
            			            <div class="panel-body panel-body-padding">
                                        <span class="alertmsg"></span>
                                        <div class="row form-header">
	                                        <div class="col-lg-6 col-md-6 col-sm-6">
          		                                <h6>HRM <i class="fa fa-angle-right"></i> Assign Package and Benifit</h6>
          		                            </div>
      		                                <div class="col-lg-6 col-md-6 col-sm-6">
          		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
          		                            </div>
                                        </div>
                                        <div class="row">
                                	        <div class="col-sm-12">
        		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno; ?>">
        		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
            	                            </div>

        	                                <!--div class="col-lg-3 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="cmbprdtp">Employee </label>
                                                    <div class="form-group styled-select">
                                                        <select name="empid" id="empid" class="form-control">
        <?php $qrymu = "SELECT `id`, concat(`firstname`, ' ', `lastname`) empname FROM `employee` order by empname";
    $resultmu            = $conn->query($qrymu);
    if ($resultmu->num_rows > 0) {while ($rowmu = $resultmu->fetch_assoc()) {$mid = $rowmu["id"];
        $mnm                            = $rowmu["empname"];
        ?>
                                                            <option value="<?php echo $mid; ?>" <?php if ($hrid == $mid) {echo "selected";} ?>><?php echo $mnm; ?></option>
        <?php }} ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div-->
                                            
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="cmbprdtp">Employee </label>
                                                    <div class="form-group styled-select">
                                                <input list="itemName" name="itmnm"  autocomplete="off" value = "<?= $hrnm ?>" class="dl-itemName datalist" placeholder="Select Employee" required>
													<input type="hidden" placeholder="ITEM" value="<?php echo $hrid; ?>" name="empid" id="empid"  class="itemName">
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Employee</option>
    <?php 
				
					$qryitm = 	"SELECT `id`, concat(`firstname`, ' ', `lastname`) empname, employeecode FROM `employee` order by empname";
				
			$resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  
                $tid  = $rowitm["id"];
                $nm   = $rowitm["empname"];
				$code  = $rowitm["employeecode"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?=$nm?>-[Code: <?=$code; ?>]"></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                </div>
                                            </div>
                                            
                                            

                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="cmbitmcat">Select Package*</label>
                                                    <div class="form-group styled-select">
                                                        <select name="coms" id="coms" class="form-control" required>
                                                        <option value =""> Select Package</option>
        <?php $qrycoms = "SELECT `id`, `title` FROM `compansationSetup` order by id";
    $resultcoms            = $conn->query($qrycoms);
    if ($resultcoms->num_rows > 0) {while ($rowcoms = $resultcoms->fetch_assoc()) {$comsid = $rowcoms["id"];
        $comsnm                            = $rowcoms["title"];
        ?>
                                                            <option value="<?php echo $comsid; ?>" <?php if ($coms == $comsid) {echo "selected";} ?>><?php echo $comsnm; ?></option>
        <?php }} ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                           

                          	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="code">Privilage Fund</label>
                                                                <input type="number" step = "any" class="form-control" id="bamount" name="bamount" value="<?php echo $bamount; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="code">Increement</label>
                                                                <input type="number" step = "any" class="form-control" id="incr" name="incr" value="<?php echo $incr; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                	                                    <label for="email">Effective Date</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control datepicker" id="action_dt" name="action_dt" value="<?php echo $action_dt; ?>" required>
                                                                <div class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-th"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="fnm">Remark</label>
                                                                <textarea class="form-control" id="condition" name="condition" rows="4" ><?php echo $condition; ?></textarea>
                                                            </div>
                                                        </div>


                                            <div class="col-sm-12">
                                                <?php if ($mode == 2) { ?>
                                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update HR Compansation" id="update" >
                                                <?php } else { ?>
                                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add HR Compansation" id="add" >
                                                <?php } ?>
                                                <a href = "./hrcompansationList.php?pg=1&mod=4">
                                                    <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <!-- /#end of panel -->
                            </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /#page-content-wrapper -->

<?php
include_once 'common_footer.php';
//$cusid = 3; ?>
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
    $(document).on("input", ".dl-itemName", function() {
        val = $(this).val();
        var pid =  $('#itemName option[value="' + val +'"]').attr('data-value');
        $("#empid").val(pid);

	    
  	});		

</script>

</body>
</html>
<?php } ?>