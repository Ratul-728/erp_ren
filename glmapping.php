<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    $res  = $_GET['res'];
    $msg  = $_GET['msg'];
    $itid = $_GET['id'];

    if ($res == 1) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
    }
    if ($res == 2) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
    }

    if ($res == 4) {

        
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    } else {
        $trdt  = '';
        $vouch = '';
        $ref   = '';
        $desc  = '';
        $mode  = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'glmapping';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>

<body class="form">
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>GL Mapping</span>
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
                        <form method="post" action="common/addglmapping.php"  id="form1"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
                                <div class="panel-heading"><h1>GL Mapping</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span>

                                    <!-- <br> <p>(Field Marked * are required) </p> -->

                                    <div class="row">
                                    
<?php if ($mode == 1) { ?>
                                    <div class="po-product-wrapper">
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>GL Mapping Information  </h4>
		                                        <hr class="form-hr">
		                                        <div class="row">
                                            <div class="col-lg-5 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10">Business </h6>
                                                </div>
                                                 <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">  GL Account</h6>
                                                </div>
                                                


                                        </div>
	                                        </div>
	                                        <div class="toClone">


                                                
                                                <div class="col-lg-5 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                            <div class="form-group styled-select">
                                                            <select name="business[]" id="business" class="form-control">

                                    <?php $qrymu="SELECT `id`, title FROM `glbusiness` order by title"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) { 
                                            $mid= $rowmu["id"];  $mnm=$rowmu["title"]; ?>                                                          
            
                                                                <option value="<?php echo $mid; ?>" <?php if ($mu == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
                                    <?php  }}?>                                                       
            
                                                              </select>
            
                                                             </div>
            
                                                    </div>
                                                </div> <!-- this block is for probability-->
                                                <div class="col-lg-5 col-md-6 col-sm-6">
													<!--<lebel>Unit</lebel>-->
                                                    <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                      <select name="glno[]" id="measureUnit" class="form-control">
                                                      <option value="">Select GL Account</option>
 <?php $qryunit = "SELECT `glnm`, `glno`, concat(`glnm`, '(', `glno`, ')') cnt FROM `coa` where isposted='P' and status='A' order by glno";
        $resultunit     = $conn->query($qryunit);if ($resultunit->num_rows > 0) {while ($rowunit = $resultunit->fetch_assoc()) {
            $unitid = $rowunit["glno"];
            $unitnm = $rowunit["cnt"];
            ?>
                                                        <option value="<?php echo $unitid; ?>"><?php echo $unitnm; ?></option>
     <?php }} ?>
                                                      </select>
                                                      </div>
                                                  </div>
                                                </div> <!-- this block is for measureUnit-->


                                            </div>
                                    </div>




                                    </div>
<?php } else {
        
        $itmdtqry    = "SELECT * FROM `glmapping` WHERE id = " . $itid;
        $resultitmdt = $conn->query($itmdtqry);if ($resultitmdt->num_rows > 0) {while ($rowitmdt = $resultitmdt->fetch_assoc()) {
            $glno    = $rowitmdt["mappedgl"];
            $business   = $rowitmdt["buisness"];
            
            ?>
                                    <div class="po-product-wrapper"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            
		                                        <div class="row">
                                        
                                        <div class="col-lg-5 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                        <input type = "hidden" name = "itid" value = "<?= $itid ?>">
                                                    <div class="form-group">
                                                            <label for="code">Business</label>
                                                            <div class="form-group styled-select">
                                                            <select name="business" id="business" class="form-control">

                                    <?php $qrymu="SELECT `id`, title FROM `glbusiness` order by title"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) { 
                                            $mid= $rowmu["id"];  $mnm=$rowmu["title"]; ?>                                                          
            
                                                                <option value="<?php echo $mid; ?>" <?php if ($business == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
                                    <?php  }}?>                                                       
            
                                                              </select>
            
                                                             </div>
            
                                                    </div>
                                                </div> <!-- this block is for probability-->
                                        <div class="col-lg-5 col-md-6 col-sm-6"> <!-- this block is for unit-->
                                                    <div class="form-group">
                                                        <label for="code">GL Account</label>
                                                        <div class="form-group styled-select">
                                                            <select name="glno" id="glno" class="form-control">
                                                     
 <?php //and `id`=".$itdmu."
 $qrymu="SELECT `glnm`, `glno`, concat(`glnm`, '(', `glno`, ')') cnt FROM `coa` where isposted='P' and status='A' order by glnm"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["glno"];  $mnm=$rowmu["cnt"];
    ?>                                                          
                                                                <option value="<?php echo $mid; ?>" <?php if ($glno == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
     <?php  }}?>                                                   
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
<?php }}}?>
                                    		<!-- this block is for php loop, please place below code your loop  -->

                                    <br>
                            <?php if($mode == 1) { ?>
                                    <div class="col-sm-12">
                                    <?php
//echo $mode;
    $addClassName = ($mode == "1") ? 'link-add-po' : 'link-add-po-2';
    ?>
        	                            <a href="#" class="<?=$addClassName ?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
                                         
    	                            </div>
    	                   <?php } ?>


                                    </div>
                                </div>
                            </div>
                            <!-- /#end of panel -->
                            <div class="button-bar">
                                <?php if ($mode == 2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update GL Mapping"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add GL Mapping"  id="add" >
                                <?php } ?>
                            <a href = "https://bithut.biz/BitFlow/glmappingList.php?pg=1&mod=7">
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

<!--Number formatting-->

<script>

 function numberFormat() {
   //  alert("dd");
  let number = document.getElementById('d_amount').value;
  console.log(number);
  let formatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'BDT',
  minimumFractionDigits: 0,

  // These options are needed to round to whole numbers if that's what you want.
  //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
  //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
});

console.log(formatter.format(number)); /* $2,500.00 */
}
</script>

</body>
</html>
<?php } ?>