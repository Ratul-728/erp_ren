<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    $res  = $_GET['res'];
    $msg  = $_GET['msg'];
    $oid  = $_GET['id'];
    $orid = $_GET['orid'];

    if ($res == 1) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
    }
    if ($res == 2) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
    }

    if ($res == 4) {
        $qry = "SELECT `id`, DATE_FORMAT(`transdt`,'%e/%c/%Y')`transdt`, `orgid`, `transmode`, `dr_cr`, `trans_ref`, `amount`, `remarks`, `makeby`, `makedt` FROM `organizationwallet`  where id= " . $oid;
        // echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $wid       = $row["id"];
                    $transdt   = $row["transdt"];
                    $orgid     = $row["orgid"];
                    $transmode = $row["transmode"];
                    $dr_cr     = $row["dr_cr"];
                    $trans_ref = $row["trans_ref"];
                    $amount    = $row["amount"];
                    $remarks   = $row["remarks"];
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";
        $orid = $orgid;
    } else {
        $wid       = '';
        $transdt   = '';
        $orgid     = '';
        $transmode = '';
        $dr_cr     = '';
        $trans_ref = '0';
        $amount    = '';
        $remarks   = '';

        $mode = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'wallet';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>

<body class="form wallet">
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Wallet transaction Details</span>
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
                        <form method="post" action="common/addwallet.php"  id="form1"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1 class="left-align">Wallet Transaction Information</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span>

                                    <!-- <br> <p>(Field Marked * are required) </p> -->

                                    <div class="row">
      		                            <div class="col-sm-12">
	                                      <!--  <h4></h4>
	                                        <hr class="form-hr"> -->

		                                    <input type="hidden"  name="wid" id="wid" value="<?php echo $wid; ?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
		                                    <input type="hidden"  name="prevamt" id="prevamt" value="<?php echo $amount; ?>">
	                                    </div>
            	                       <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="trdt">Date *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="trdt" name="trdt" value="<?php echo $transdt; ?>" required>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>
                                        </div> -->
                                           <div class="col-lg-10 col-md-10">
      		                                <div class="form-group">
                                                <!--<label for="ref">Subject*</label> -->
                                                <input type="text" class="form-control com-nar" id="descr" name="descr" value="<?php echo $remarks; ?>" autofocus="autofocus"  placeholder="Add a Narration" required>
                                            </div>
	                                   <!--     <h4></h4>
	                                        <hr class="form-hr">  -->

		                                    <input type="hidden"  name="exid" id="exid" value="<?php echo $exid; ?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
	                                    </div>
                                        <div class="col-lg-2 col-md-2 new-layout-amount ">

                                            <div class="form-group">
                                                <label class="text-center" for="amt">Amount </label>
                                                <input type="text" placeholder="Tk 0.00" class="form-control amount-fld" id="amt" name="amt" value="<?php echo $amount; ?>">
                                            </div>

                                        </div>

                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmborg">Organization*</label>
                                                <div class="form-group styled-select">
                                                <select name="cmborg" id="cmborg" class="cmb-parent form-control" required>

													<?php $qryorg = "SELECT distinct o.`id`,o.`name` FROM `organization` o where o.`id`=" . $orid;
    $resultorg                 = $conn->query($qryorg);if ($resultorg->num_rows > 0) {while ($roworg = $resultorg->fetch_assoc()) {
        $tid = $roworg["id"];
        $nm  = $roworg["name"];
        ?>
                                                    <option value="<?php echo $tid; ?>"><?php echo $nm; ?></option>
                                                    <?php
}
    }
    ?>
                                                </select>
                                             </div>
                                          </div>
                                        </div>
                                       <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="trdt">Transaction Date*</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="trdt" name="trdt" value="<?php echo $transdt; ?>" required>
                                            <div class="input-group-addon">
                                             <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div> -->
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbdrcr"> Debit/Credit*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbdrcr" id="cmbdrcr" class="form-control" required>
                                                <option value="" >Select</option>
                                                <option value="D" <?if ($dr_cr == 'D') {echo "selected";} ?>>Debit</option>
                                                <option value="C" <?if ($dr_cr == 'C') {echo "selected";} ?>>Credit</option>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                     <div class="form-group">
                                            <label for="cmbmode"> Trans Mode*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbmode" id="cmbmode" class="form-control" required>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `transmode` order by `name` ";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                                <option value="<?echo $tid; ?>" <?if ($transmode == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
    <?php }} ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Reference/Cheque No.</label>
                                                <input type="text" class="form-control" id="ref" name="ref" value="<?php echo $trans_ref; ?>">
                                            </div>
                                    </div>

  	                               <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="amt">Amount *</label>
                                            <input type="text" class="form-control" id="amt" name="amt" value="<?php echo $amount; ?>" required>
                                        </div>
                                    </div> -->

                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                         <label for="trdt">Trans Date*</label>
                                            <div class="input-group">

                                                <input type="text" class="form-control datepicker" id="trdt" name="trdt" value="<?php echo $transdt; ?>" required>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>
                                    </div>

  	                                <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="descr">Narration</label>
                                            <input type="text" class="form-control" id="descr" name="descr" value="<?php echo $remarks; ?>">
                                        </div>
                                    </div>  -->

                                    </div>
                                </div>
                            </div>
                            <!-- /#end of panel -->
                            <div class="button-bar">
                                <?php if ($mode == 2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Wallet"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Wallet"  id="add" >
                                <?php } ?>
                                <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
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
</body>
</html>
<?php } ?>