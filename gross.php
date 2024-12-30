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
    
    if ($res == 4) {
        
        $qry = "SELECT concat(emp.firstname, ' ', emp.lastname) empname, emp.employeecode, gs.gross, DATE_FORMAT(gs.effectivedate, '%d/%m/%Y') effectivedate 
                FROM `employee` emp LEFT JOIN `gross_salary` gs ON emp.id=gs.empid
                WHERE emp.id = " . $aid . " LIMIT 1";
        //echo $qry; die;
        if ($conn->connect_error) {echo "Connection failed: " . $conn->connect_error;} else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $empnm = $row["empname"]." ( ".$row["employeecode"]. " )";
                    $gross = $row["gross"];
                    $effectivedate = $row["effectivedate"];
                }
            }
        }
        $mode = 2; //update mode
    }
    
    $currSection = 'gross';
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
                    <span>Gross Salary</span>
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
                           <form method="post" action="common/addgross.php" id="form1" enctype="multipart/form-data">
                        <!-- START PLACING YOUR CONTENT HERE -->
                                <div class="panel panel-info">
            			            <div class="panel-body panel-body-padding">
                                        <span class="alertmsg"></span>
                                        <div class="row form-header">
	                                        <div class="col-lg-6 col-md-6 col-sm-6">
          		                                <h6>HRM <i class="fa fa-angle-right"></i>HR Operation <i class="fa fa-angle-right"></i> Gross Salary</h6>
          		                            </div>
      		                                <div class="col-lg-6 col-md-6 col-sm-6">
          		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
          		                            </div>
                                        </div>
                                        <div class="row">
                                	        <div class="col-sm-12">
        		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno; ?>">
        		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $aid; ?>">
            	                            </div>

        	   
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="code">Employee Name</label>
                                                                <input type="text" class="form-control" id="empnm" name="empnm" value="<?php echo $empnm; ?>" readonly>
                                                            </div>
                                                        </div>
                                           

                          	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="code">Gross Salary</label>
                                                                <input type="number" step = "any" class="form-control" id="gross" name="gross" value="<?php echo $gross; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                	                                    <label for="email">Effective Date</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control datepicker" id="effectivedate" name="effectivedate" value="<?php echo $effectivedate; ?>" required>
                                                                <div class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-th"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                            <div class="col-sm-12">
                                                <?php if ($mode == 2) { ?>
                                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Add Gross Salary" id="update" >
                                                <?php } ?>
                                                <a href = "./grossList.php?pg=1&mod=4">
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