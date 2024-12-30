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
    $atid= $_GET['id'];

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
        $qry="SELECT `id`, `itemgroup`, `itemcatagory`, `itemtype` FROM `catagorygrouping`  WHERE id=".$atid; 
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
                        $aid=$row["id"];$grp=$row["itemgroup"];$cat=$row["itemcatagory"];$type=$row["itemtype"];  
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
                        $aid='';$cat=''; $grp=''; $type='';
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'catgr';
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
            <span>Catagory Grouping </span>
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
                        <form method="post" action="common/addgrouping.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Catagory Grouping</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    
                                    <div class="row">
      		                            <div class="col-sm-12">
	                                      <!--  <h4></h4>
	                                        <hr class="form-hr"> -->
	                                        
		                                    <input type="hidden"  name="atid" id="atid" value="<?php echo $aid;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
		                                    
	                                    </div>      
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbmaj">Item Group*</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbgrp" id="cmbgrp" class="cmb-parent form-control" required>
                                                	<option value="">Select Group</option>
													<?php $qryinv="SELECT `id`,`name` FROM `itemgroup` name"; $resultinv = $conn->query($qryinv); if ($resultinv->num_rows > 0) {while($rowinv = $resultinv->fetch_assoc()){
                                                    	$tid= $rowinv["id"];  $nm=$rowinv["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($grp == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                             </div>
                                          </div>         
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbpar">Item Catagory * </label>
                                                <div class="form-group styled-select">
                                                <select name="cmbcat" id="cmbcat" class="cmd-child form-control" required>
                                                	<option value="">Select Catagory</option>
													<?php $qryinv="SELECT `id`,title  `name` FROM `catagory` order by title"; $resultinv = $conn->query($qryinv); if ($resultinv->num_rows > 0) {while($rowinv = $resultinv->fetch_assoc()){
                                                    	$tid= $rowinv["id"];  $nm=$rowinv["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($cat == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                             </div>
                                          </div>         
                                        </div>    
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbpar">Item Type *</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbtp" id="cmbtp" class="cmd-child form-control" required>
                                                	<option value="">Select Type</option>
													<?php $qryinv="SELECT `id`,`name` FROM `itemtype` order by name"; $resultinv = $conn->query($qryinv); if ($resultinv->num_rows > 0) {while($rowinv = $resultinv->fetch_assoc()){
                                                    	$tid= $rowinv["id"];  $nm=$rowinv["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($type == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                             </div>
                                          </div>         
                                        </div>  
                                         
                                         
                                        <br>
                                    
                                    </div>
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Groupping"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Create Groupping"  id="add" >
                                <?php } ?>  
                                <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Cancel"  id="cancel" >
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