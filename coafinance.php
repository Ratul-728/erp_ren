<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
//echo $usr;die; 
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");} else {
    $res = $_GET['res'];
    $msg = $_GET['msg'];
    $id  = $_GET['id'];

    if ($res == 1) {echo "<script type='text/javascript'>alert('" . $msg . "')</script>";}
    if ($res == 2) {echo "<script type='text/javascript'>alert('" . $msg . "')</script>";}
    if ($res == 4) {
        $itmdtqry    = "SELECT  c.`glno`, c.`glnm`, c.`ctlgl`
,(select glnm from coa where glno=c.ctlgl ) parntgl
, c.`isposted`,c.`oflag`, c.`dr_cr`, c.`lvl`, c.`opbal`, c.`closingbal` FROM `coa` c WHERE `id` = " . $_GET["id"];
        $resultitmdt = $conn->query($itmdtqry);
        while ($rowitmdt = $resultitmdt->fetch_assoc()) {
            $glacno    = $rowitmdt["glno"];
            $glnm      = $rowitmdt["glnm"];
            $parent_gl = $rowitmdt["ctlgl"];
            $parent_glnm = $rowitmdt["parntgl"];
            $is_posted = $rowitmdt["isposted"];
            $is_fincaed= $rowitmdt["oflag"];
            $type      = $rowitmdt["dr_cr"];
            $lvl       = $rowitmdt["lvl"];
            $opbal     = $rowitmdt["opbal"];
            $clbal     = $rowitmdt["closingbal"];
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    } else {
        $mode = 1;
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'coafinance';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>
<body class="form deal-entry">
<?php include_once 'common_top_body.php'; ?>
<div id="wrapper">
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Chart of Account</span>
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
                        <form method="post" action="common/addcoafinance.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Chart Of Account Form</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span>

                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    <div class="row">
      		                            <div class="col-sm-12">
	                                       <!--  <h4></h4>
	                                        <hr class="form-hr"> -->
		                                    <input type="hidden"  name="itid" id="itid" value="<?php echo $_GET["id"]; ?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
	                                    </div>
            	                       <!--div class="col-lg-6 col-md-12 col-sm-12"></!--div>
                                        <div class="col-lg-6 col-md-12 col-sm-12"-->

                                        </div>

                                    <div class="po-product-wrapper">
                                        <div class="color-block">
     		                                <div class="col-lg-6 col-md-12 col-sm-12">

		                                        <div class="row">


                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">

                                                <label for="code">GL Name *</label>

                                                <input type="text" class="form-control" id="glnm" name="glnm" value="<?php echo $glnm; ?>" required>

                                            </div>
                                        </div>


                                        <div class="col-lg-4 col-md-6 col-sm-6"> <!-- this block is for unit-->
                                                    <div class="form-group">
                                                        <label for="code">Parent GL</label>
                                                        <div class="form-group styled-select">
                                                            <?php if($mode==2){?>
                                                            <select name="parent_gl" id="parent_gl" class="form-control" readonly>
                                                               <option value="<?php echo $glacno; ?>"><?php echo $parent_glnm; ?></option> 
                                                            </select>  
                                                            <?php } else {?>
                                                            <select name="parent_gl" id="parent_gl" class="form-control">

 <?php // Root Level
    $qrymu    = "SELECT `glno`, `glnm` FROM `coa` WHERE status = 'A' and lvl = 1  order by glnm";
    $resultmu = $conn->query($qrymu);if ($resultmu->num_rows > 0) {while ($rowmu = $resultmu->fetch_assoc()) {
        $mid = $rowmu["glno"];
        $mnm = $rowmu["glnm"];
        ?>
                                                                <option value="<?php echo $mid; ?>" <?php if ($parent_gl == $mid) {echo "selected";} ?>><?php echo $mnm; ?></option>
                <?php // Level 1
        $qrymu1    = "SELECT `glno`, `glnm` FROM `coa` WHERE status = 'A' and isposted != 'P' and ctlgl = '" . $mid . "' and glno != ctlgl order by glnm";
        $resultmu1 = $conn->query($qrymu1);if ($resultmu1->num_rows > 0) {while ($rowmu1 = $resultmu1->fetch_assoc()) {
            $mid1 = $rowmu1["glno"];
            $mnm1 = $rowmu1["glnm"];
            ?>
                                                                 <option value="<?php echo $mid1; ?>" <?php if ($parent_gl == $mid1) {echo "selected";} ?>> &nbsp &nbsp &nbsp <?php echo $mnm1; ?></option>
                <?php // Level 2
            $qrymu2    = "SELECT `glno`, `glnm` FROM `coa` WHERE status = 'A' and isposted != 'P' and ctlgl = '" . $mid1 . "' and glno != ctlgl order by glnm";
            $resultmu2 = $conn->query($qrymu2);if ($resultmu2->num_rows > 0) {while ($rowmu2 = $resultmu2->fetch_assoc()) {
                $mid2 = $rowmu2["glno"];
                $mnm2 = $rowmu2["glnm"];
                ?>
                                                                 <option value="<?php echo $mid2; ?>" <?php if ($parent_gl == $mid2) {echo "selected";} ?>> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <?php echo $mnm2; ?></option>
                <?php // Level 3
                $qrymu3    = "SELECT `glno`, `glnm` FROM `coa` WHERE status = 'A' and isposted != 'P' and ctlgl = '" . $mid2 . "' and glno != ctlgl order by glnm";
                $resultmu3 = $conn->query($qrymu3);if ($resultmu3->num_rows > 0) {while ($rowmu3 = $resultmu3->fetch_assoc()) {
                    $mid3 = $rowmu3["glno"];
                    $mnm3 = $rowmu3["glnm"];
                    ?>
                                                                 <option value="<?php echo $mid3; ?>" <?php if ($parent_gl == $mid3) {echo "selected";} ?>> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <?php echo $mnm3; ?></option>
                <?php // Level 4
                    $qrymu4    = "SELECT `glno`, `glnm` FROM `coa` WHERE status = 'A' and isposted != 'P' and ctlgl = '" . $mid3 . "' and glno != ctlgl order by glnm";
                    $resultmu4 = $conn->query($qrymu4);if ($resultmu4->num_rows > 0) {while ($rowmu4 = $resultmu4->fetch_assoc()) {
                        $mid4 = $rowmu4["glno"];
                        $mnm4 = $rowmu4["glnm"];
                        ?>
                                                                 <option value="<?php echo $mid4; ?>" <?php if ($parent_gl == $mid4) {echo "selected";} ?>> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <?php echo $mnm4; ?></option>


     <?php }}}}}}}}}} ?>
                                                            </select>
                                                            <? }?>
                                                        </div>
                                                    </div>
                                                </div>



                                        <!--div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbprdtp">Type </label>
                                                <div class="form-group styled-select">
                                                    <select name="type" id="type" class="form-control">
                                                                <option value="D" <?php if ($type == 'D') {echo "selected";} ?>>Debit</option>
                                                                <option value="C" <?php if ($type == 'C') {echo "selected";} ?>>Credit</option>

                                                    </select>
                                                </div>
                                          </div>
                                        </div-->


                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">

                                                <label for="code">Openning Balance *</label>

                                                <input type="text" class="form-control" id="opbal" name="opbal" value="<?php echo $opbal; ?>" required  <?php if($mode==2){?> readonly<?php } ?>>

                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">

                                                <label for="code">Closing Balance *</label>

                                                <input type="text" class="form-control" id="clbal" name="clbal" value="<?php echo $clbal; ?>" required <?php if($mode==2){?> readonly<?php } ?> >

                                            </div>
                                        </div>

                                       
                                        <div class="col-lg-4 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Is Posted *</label>

                                                <input type="checkbox" class="form-control" id="is_posted" placeholder="Is Posted?" name="is_posted" value = "P" <?php if ($is_posted == 'P'){ echo "checked";} ?> <?php if($mode==2){?> disabled<?php } ?>>

                                            </div>

                                        </div>
                                         <div class="col-lg-4 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Is Financial Only *</label>

                                                <input type="checkbox" class="form-control" id="is_finance" placeholder="Is Financial?" name="is_finance" value = "Y" <?php if ($is_fincaed == 'Y'){ echo "checked";} ?> <?php if($mode==2){?> disabled<?php } ?>>

                                            </div>

                                        </div>
                                        


                                    </div>
	                                        </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12"></div>


                                        </div>
                                    </div>
                                    <br>


                                    </div>

                                </div>
                            </div>

                            <!-- /#end of panel -->
                            <div class="button-bar coa-button-bar">
                                <?php if ($mode == 2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update COA"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->

                                <?php } else { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add COA"  id="submit" >

                                <?php } ?>
                            <a href = "./coaList.php?pg=1&mod=7">
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

<script language="javascript">

/*  autofill combo  */

 var dataList=[];
$(".list-itemName").find("option").each(function(){dataList.push($(this).val())})

/*
//print dataList array
 $.each(dataList, function(index, value){
           $(".alertmsg").append(index + ": " + value + '<br>');
});
*/

/* Check wrong category */
var catlavel;
var flag;


</script>


</body>

</html>

<?php } ?>