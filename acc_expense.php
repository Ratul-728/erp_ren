<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    $res  = $_GET['res'];
    $msg  = $_GET['msg'];
    $itid = $_GET['id'];

    if ($res == 4) {
        $qry = "SELECT `id`,DATE_FORMAT(`trdt`,'%e/%c/%Y') `trdt`, `transmode`, `transref`, `transtype`,  `naration`, `amount`, `costcenter`,`soid`, `st`, `makeby`, `makedt`, `glac`,`crglno` FROM `expense` where id= " . $itid;
        // echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $exid      = $row["id"];
                    $trdt      = $row["trdt"];
                    $transmode = $row["transmode"];
                    $transref  = $row["transref"];
                    $transtype = $row["transtype"];

                    $naration   = $row["naration"];
                    $amount     = number_format($row["amount"], 2);
                    $costcenter = $row["costcenter"];
                    $soid       = $row["soid"];
                    $glac = $row["glac"];
                    $crglac = $row["crglno"];
                    
                    //Info for gl searchable
                    $qrycmbinfo = "SELECT `glnm` FROM `coa`  WHERE glno = '$glac'";
                    $resultcmbinfo = $conn->query($qrycmbinfo);
                    while ($rowcmbinfo = $resultcmbinfo->fetch_assoc()) {
                        $glnm = $rowcmbinfo["glnm"];
                    }
                    $qrycmbinfo1 = "SELECT `glnm` FROM `coa`  WHERE glno = '$crglac'";
                    $resultcmbinfo1 = $conn->query($qrycmbinfo1);
                    while ($rowcmbinfo1 = $resultcmbinfo1->fetch_assoc()) {
                        $crglnm = $rowcmbinfo1["glnm"];
                    }
                    
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    } else {
        $exid      = '';
        $trdt      = '';
        $transmode = '';
        $transref  = '';
        $chequedt  = '';
        $customer  = '';

        $naration   = '';
        $amount     = '';
        $costcenter = '';
        $soid       = '';
        $mode       = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'acc_expense';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>

<body class="form expense">
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Other Account Expense Details</span>
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
                        <form method="post" action="common/addacc_expense.php"  id="form1"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			               <!-- <div class="panel-heading"><h1>Other Expense</h1></div>-->
				                <div class="panel-body">
                                    <span class="alertmsg"></span>

                                    <!-- <br> <p>(Field Marked * are required) </p> -->

                                      <div class="row">
                                        <div class="col-sm-3 text-nowrap">
                                                <h6>Account <i class="fa fa-angle-right"></i>Other Account Expense</h6>
                                           </div>

                                    </div>

                                    <div class="row expense-header">
      		                            <div class="col-lg-10 col-md-10">
      		                                <div class="form-group">
                                                <!--<label for="ref">Subject*</label> -->
                                                <input type="text" class="form-control com-nar" id="descr" name="descr" value="<?php echo $naration; ?>" autofocus="autofocus"  placeholder="Add a Narration" required>
                                            </div>
	                                   <!--     <h4></h4>
	                                        <hr class="form-hr">  -->

		                                    <input type="hidden"  name="exid" id="exid" value="<?php echo $exid; ?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
	                                    </div>
                                        <div class="col-lg-2 col-md-2 expense-amount ">

                                            <div class="form-group">
                                                <label for="amt">Amount *</label>
                                                <input type="text" placeholder="Tk 0.00" class="form-control amount-fld" id="amt" name="amt" value="<?php echo $amount; ?>" required>
                                            </div>

                                        </div>
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="cd">Date *</label>
                                            <div class="input-group">

                                                <input type="text" class="form-control datepicker" id="trdt" name="trdt" value="<?php echo $trdt; ?>" required>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbtype"> Trans Type*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbtype" id="cmbtype" class="form-control" required>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `transtype`  order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                                <option value="<?echo $tid; ?>" <?if ($transtype == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
    <?php }} ?>
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
$qry1    = "SELECT `id`, `name`  FROM `transmode`  order by name";
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
                                                <label for="ref">Reference</label>
                                                <input type="text" class="form-control" id="ref" name="ref" value="<?php echo $transref; ?>">
                                            </div>
                                    </div>

                                        <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcc"> costcenter*</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbcc" id="cmbcc" class="form-control" required>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `costcenter`  order by name ";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                                        <option value="<?echo $tid; ?>" <?if ($costcenter == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
    <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div -->

                                        
                                    
                                   
                                                
                                        <div class="col-lg-3 col-md-6 col-sm-6"> 
                                            <div class="form-group">
                                                <label for="cmbcontype">Debit GL *</label>
                                                <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> and substr(glno,1,1)=1-->
                                                <div class="form-group styled-select">
                                                    <input list="cmborg1" name ="cmbassign2" value = "<?= $glnm ?>" autocomplete="Search From list"  class="dl-cmborg datalist" placeholder="" required>
                                                    <datalist  id="cmborg1" name = "cmborg1" class="list-cmbassign form-control" required>

                        <?php $qryitm="SELECT `glno`, `glnm` FROM `coa`  WHERE `status` = 'A' and isposted = 'P' and oflag='N' order by glnm "; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
                                  {
                                      $tid= $rowitm["glno"];  $nm=$rowitm["glnm"]; ?>
                                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
                        <?php  }}?>                    
                                                    </datalist> 
                                                    <input type = "hidden" name = "glac" id = "glac" value = "<?= $glnm ?>">
                                                </div>
                                            </div>   
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6"> 
                                            <div class="form-group">
                                                <label for="cmbcontype">Credit GL *</label>
                                                <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> and substr(glno,1,1)=1-->
                                                <div class="form-group styled-select">
                                                    <input list="cmborg3" name ="cmbassign3" value = "<?= $crglnm ?>" autocomplete="Search From list"  class="dl-cmborg3 datalist" placeholder="" required>
                                                    <datalist  id="cmborg3" name = "cmborg3" class="list-cmbassign form-control" required>

                        <?php $qryitm="SELECT `glno`, `glnm` FROM `coa`  WHERE `status` = 'A' and isposted = 'P' and oflag='N' order by glnm "; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
                                  {
                                      $tid= $rowitm["glno"];  $nm=$rowitm["glnm"]; ?>
                                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
                        <?php  }}?>                    
                                                    </datalist> 
                                                    <input type = "hidden" name = "crglac" id = "crglac" value = "<?= $crglnm ?>">
                                                </div>
                                            </div>   
                                        </div>

      	                                <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="descr">Narration</label>
                                                <input type="text" class="form-control" id="d escr" name="descr" value="<?php echo $naration; ?>">
                                            </div>
                                        </!div -->

                                        <div class="col-lg-3 col-md-6 col-sm-6">

                                        <strong>Image</strong>

                                        <div class="input-group">

                                            <label class="input-group-btn">

                                                <span class="btn btn-primary btn-file btn-file">

                                                    <i class="fa fa-upload"></i> <input type="file" name="attachment1" id="attachment1" style="display: none;" multiple>

                                                </span>

                                            </label>

                                            <input type="text" class="form-control" readonly>

                                        </div>

                                        <span class="help-block form-text text-muted">

                                            Try selecting one  files and watch the feedback

                                        </span>

                                    </div>

                                    </div>
                                </div>
                            </div>
                            <!-- /#end of panel -->
                            <div class="button-bar">
                                <?php if ($mode == 2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Expense"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Expense"  id="add" >
                                <?php } ?>
                                <a href = "./acc_expenseList.php?pg=1&mod=7">
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
        $(document).on("change", ".dl-cmborg", function() {
            var g = $(this).val();
            var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
            $('#glac').val(id);
        });
         $(document).on("change", ".dl-cmborg3", function() {
            var g = $(this).val();
            var id = $('#cmborg3 option[value="' + g +'"]').attr('data-value');
            $('#crglac').val(id);
        });
    </script>
</body>
</html>
<?php } ?>