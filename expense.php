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
        $qry = "SELECT `id`,DATE_FORMAT(`trdt`,'%e/%c/%Y') `trdt`, `transmode`, `transref`, `transtype`,  `naration`, `amount`, `costcenter`,`soid`, `st`, `makeby`, `makedt`, `image` FROM `expense` where id= " . $itid;
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
                    $amount     = number_format($row["amount"],2);
                    $costcenter = $row["costcenter"];
                    $soid       = $row["soid"];
					$oldpic      = $row["image"];
                }
            }
			
			if(!file_exists('common/upload/expense/'.$oldpic)){
				$oldpic = '';
				
			}
			
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    } else {
        $exid      = '';
        $trdt      = date('d/m/Y');
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
    $currSection = 'expense';
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
            <span>Other Expense Details</span>
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
                        <form method="post" action="common/addexpense.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			               <!-- <div class="panel-heading"><h1>Other Expense</h1></div>-->
				                <div class="panel-body">
                                    <span class="alertmsg"></span>

                                    <!-- <br> <p>(Field Marked * are required) </p> -->

                                      <div class="row">
                                        <div class="col-sm-3 text-nowrap">
                                                <h6>Billing <i class="fa fa-angle-right"></i>Other Expense</h6>
                                           </div>

                                    </div>

                                    <div class="row expense-header">
      		                            <div class="col-lg-10 col-md-10">
      		                                <div class="form-group">
                                                <!--<label for="ref">Subject*</label> -->
                                                <input type="text" class="form-control com-nar" id="descr" name="descr" value="<?php echo $naration; ?>" autofocus  placeholder="Add a Narration" required>
                                            </div>
	                                   <!--     <h4></h4>
	                                        <hr class="form-hr">  -->

		                                    <input type="hidden"  name="exid" id="exid" value="<?php echo $exid; ?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
	                                    </div>
                                        <div class="col-lg-2 col-md-2 expense-amount ">

                                            <div class="form-group">
                                                <label for="amt">Amount* </label>
                                                <input required type="text" placeholder="Tk 0.00" class="form-control amount-fld" id="amt" name="amt" value="<?php echo $amount; ?>" required>
                                            </div>

                                        </div>
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="cd">Date </label>
                                            <div class="input-group">

                                                <input type="text" class="form-control datepicker" id="trdt" name="trdt" value="<?php echo $trdt; ?>">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbtype"> Expense Category*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbtype" id="cmbtype" class="form-control" required>
                                                <option value="">Select Category</option>
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
                                            <label for="cmbmode"> Expense Through*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbmode" id="cmbmode" class="form-control" required>
                                                <option value="">Select Payment Method</option>
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

                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcc"> Costcenter*</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbcc" id="cmbcc" class="form-control" >
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
                                        </div>

                                        <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                            <label for="cmbcc"> SO</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbso" id="cmbso" class="form-control" >
    <?php
$qry1    = "SELECT id,socode from soitem where terminationDate is null or terminationDate='0000-00-00' or terminationDate>sysdate()";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["socode"];
        ?>
                                                <option value="<?echo $tid; ?>" <?if ($soid == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
    <?php }} ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div-->

      	                                <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="descr">Narration</label>
                                                <input type="text" class="form-control" id="d escr" name="descr" value="<?php echo $naration; ?>">
                                            </div>
                                        </!div -->

                                        <div class="col-lg-3 col-md-6 col-sm-6">

                                        <b>Picture</b>

                                        <div class="input-group">

                                            <label class="input-group-btn">

                                                <span class="btn btn-primary btn-file btn-file">
													

                                                    <i class="fa fa-upload"></i> <input type="file" name="attachment1" id="attachment1" style="display: none;" multiple>
													<input type="hidden" name="oldpic" value="<?=$oldpic?>">


                                                </span>

                                            </label>

                                            <input type="text" class="form-control" readonly>

                                        </div>

                                        <span class="help-block form-text text-muted">

                                            Try selecting one  files and watch the feedback

                                        </span>

                                    </div>
										<?php if($oldpic){?>
      	                                <div class="col-lg-3 col-md-6 col-sm-6 oldpic">
<style>
	.oldpic .picwrapper{
		display: inline-block;
		position: relative;
		border:1px solid rgb(226,226,226);
	}
	.oldpic .fa-remove{
		position: absolute;
		right:0;
		top: 0;
		padding: 5px 6px;
		cursor: pointer;
		color: red;
		border: 1px solid red;
		z-index: 2;

	}

	.oldpic .fa-ban{
		
		position: absolute;
		z-index: 1;
		left:0;
		top: 0;
		opacity: 0.8;
		color: red;
		width: 100%;
		height: 100%;
		text-align: center;
		padding-top: 50px;
		font-size: calc(3vw + 3vh);
		border:0px solid red;
	}

</style>
                                            <label for="descr">Existing Picture <input type="hidden" id="isremovepicture" name="isremovepicture" value="0"></label>
											<div class="form-group">
												<span class="picwrapper">
													<span class="fa fa-remove"></span>
													<span class="fa fa-ban" style="display: none;"></span>
													<img src="common/upload/expense/<?=$oldpic?>" width="200">
												</span>
                                            </div>
                                        </div>										
										<?php } ?>
                                        										
										
																							

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
                            <a href = "./expenseList.php?pg=1&mod=3">
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
    $(document).ready(function(e) {
        $("#descr").focus();
		
		
		$('.oldpic .fa-remove').on('click', function() {

			var hiddenField = $('#isremovepicture'),
			   val = hiddenField.val();

				if(val == 0){
				hiddenField.val(1);
			  $(".oldpic .fa-ban").show();
			}else{
			  hiddenField.val(0);
			  $(".oldpic .fa-ban").hide();
			}
		});		
		
		
		
    });
    </script>
</body>
</html>
<?php } ?>