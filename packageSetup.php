<?php
if($_POST){
print_r($_REQUEST);
exit();
}

require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{
  header("Location: ".$hostpath."/hr.php");
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $aid= $_GET['id'];
    $serno= $_GET['id'];
    $totamount=0;
    
    if ($res==4)
    {
    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
        $qry="SELECT `id`, `Title`, `pakage`, `scale` FROM `pakageSetup` WHERE id = ".$aid; 
    //echo $qry; die;
        if ($conn->connect_error) { echo "Connection failed: " . $conn->connect_error; }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()){$title = $row["Title"]; $grade = $row["scale"]; $package = $row["pakage"];}
            }
        }
        $mode=2;//update mode
    }
    else
    {
        $title = ''; $grade = ''; $package = '';  $mode=1;//Insert mode
    }
    
    $currSection = 'packagesetup';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once('common_header.php'); ?>
    <body class="form">
    <?php  include_once('common_top_body.php');?>
        <div id="wrapper"> 
    <!-- Sidebar -->
            <div id="sidebar-wrapper" class="mCustomScrollbar">
                <div class="section">
  	                <i class="fa fa-group  icon"></i>
                    <span>HR Pakase Setup</span>
                </div>
                <?php include_once('menu.php'); ?>
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
                           <form method="post" action="common/addpackageSetup.php" id="form1" enctype="multipart/form-data"> 
                        <!-- START PLACING YOUR CONTENT HERE -->
                                <div class="panel panel-info">
            			            <div class="panel-body panel-body-padding">
                                        <span class="alertmsg"></span>
                                        <div class="row form-header"> 
	                                        <div class="col-lg-6 col-md-6 col-sm-6">
          		                                <h6>HRM <i class="fa fa-angle-right"></i> Add Pakage Setup</h6>
          		                            </div>
      		                                <div class="col-lg-6 col-md-6 col-sm-6">
          		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
          		                            </div> 
                                        </div> 
                                        <div class="row">
                                	        <div class="col-sm-12">
        		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno;?>"> 
        		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
        		                                  <div class="form-group">
                                                <!--<label for="ref">Subject*</label> -->
                                                <input type="text" class="form-control com-nar" id="title" name="title" value="<?php echo $title;?>" autofocus="autofocus"  placeholder="Add a Title" required>
                                            </div> 
            	                            </div> 
                                            
    	                                   
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="cmbcontype">Grade*</label>
                                                    <div class="form-group styled-select">
                                                        <select name="grade" id="grade" class="form-control" required>
                                                        <option value = "">Select Grade</option>
    											<?php $qrycntp="SELECT `id`, `title` FROM `compansationSetup`  order by id"; $resultcntp = $conn->query($qrycntp); if ($resultcntp->num_rows > 0) {while($rowcntp = $resultcntp->fetch_assoc()){
                                                        $tid= $rowcntp["id"];  $nm=$rowcntp["title"];
                                                ?>
                                                            <option value="<?php echo $tid; ?>" <?php if ($grade == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                        <?php 
    													 }
    													}
    													?>                                                       
                                                        </select>
                                                    </div>
                                                </div>         
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="cmborg">Package</label>
                                                    <div class="form-group styled-select">
                                                        <select name="package" id="package" class="form-control" required>
                                                            <option value = "">Select Package</option>
        													<?php $qryorg="SELECT `id`,`title` FROM `pakage`  order by id"; $resultorg = $conn->query($qryorg); if ($resultorg->num_rows > 0) {while($roworg = $resultorg->fetch_assoc()){
                                                            	$tid= $roworg["id"];  $nm=$roworg["title"];
                                                            ?>
                                                            <option value="<?php echo $tid; ?>" <?php if ($package == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                            <?php 
        													 }
        													}
        													?>                                                       
                                                        </select>
                                                    </div>
                                                </div>         
                                            </div> 
        
                                            <div class="po-product-wrapper withlebel"> 
                                                <div class="color-block">
         		                                    <div class="col-sm-12">
    	                                                <h4>Package Information </h4>
        		                                        <hr class="form-hr">
        	                                        </div>
        	                                        <div class="row form-grid-bls  hidden-md hidden-sm hidden-xs">
											
											
                                                                <div class="col-lg-3 col-md-5 col-sm-6">
                                                                	<h6 class="chalan-header mgl10"> Benefit Type <span class="redstar">*</span></h6>
                                                                </div>
                
                												<div class="col-lg-3 col-sm-1 col-xs-6">
                													<h6 class="chalan-header"> Benefit Amount <span class="redstar">*</span></h6>
                												</div>
                												<div class="col-lg-3 col-sm-1 col-xs-6">
                													<h6 class="chalan-header"> Percentage <span class="redstar">*</span></h6>
                												</div>											
                
                
                
                                                                <div class="col-lg-3 col-md-1 col-sm-6">
                                                                    <h6 class="chalan-header"> Cycle </h6>
                                                                </div>
                                                        </div>
<?php if($mode==1){?>
                                                    <div class ="toLoad">
                      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                      	                                    
                                                            <div class="form-group">
                                                                <div class="form-group styled-select">
                                                                    <select name="ben[]" id="ben" class="form-control" disabled>
                <?php $qryben="SELECT `id`, `title` FROM `benifitype` order by id"; $resultben = $conn->query($qryben);
                if ($resultben->num_rows > 0) {while($rowben = $resultben->fetch_assoc()) { 
                                                $benid= $rowben["id"];  $bennm=$rowben["title"]; ?>
                                                                        <option value="<?php echo $benid; ?>" <?php if ($ben == $benid) { echo "selected"; } ?>><?php echo $bennm; ?></option>
                <?php  }}?>                                         </select>
                                                                </div>
                                                            </div> 
                                                        </div>
                          	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <input type="number" step = "any" class="form-control" id="bamount" name="bamount[]" value="<?php echo $bamount;?>" disabled>
                                                            </div> 
                                                        </div> <!-- this block is for Benefit Amount--> 
                                                       
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-group styled-select">
                                                                    <select name="per[]" id="per" class="form-control" disabled>
                                                                        <option value="1" >No</option>
                                                                        <option value="2" >Yes</option>
                                                                    </select>
                                                                </div>
                                                            </div>        
                                                        </div>
                                                        
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-group styled-select" >
                                                                    <select name="cycle[]" id="cycle" class="form-control" disabled>
                                                                        <option value="1" <?php if ($cycle == 1) { echo "selected"; } ?>>Monthly</option>
                                                                        <option value="2" <?php if ($cycle == 2) { echo "selected"; } ?>>Daily</option>
                                                                        <option value="3" <?php if ($cycle == 3) { echo "selected"; } ?>>Quarterly</option>
                                                                        <option value="4" <?php if ($cycle == 4) { echo "selected"; } ?>>Yearly</option>
                                                                    </select>
                                                                </div>
                                                            </div>        
                                                        </div>
                     	                            </div>
 <?php } else {
 $itmdtqry="SELECT `id`, `benifittp`, `befitamount`, `isPercentage`, `cycle` 
FROM `pakageSetupdetails` WHERE `pakage`=".$package." and `scale`=".$grade;
    $resultitmdt = $conn->query($itmdtqry);
    if ($resultitmdt->num_rows > 0) 
                {while($rowitmdt = $resultitmdt->fetch_assoc()) 
                    { 
                        $itmid= $rowitmdt["id"];  $benifittp=$rowitmdt["benifittp"]; $befitamount=$rowitmdt["befitamount"];$isPercentage=$rowitmdt["isPercentage"]; 
                        $cycle=$rowitmdt["cycle"]; 
 ?>                                                     	     
                                                    <div class ="toLoad">    
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                      	                                    <input type="hidden"  name="detid[]" id="detid" value="<?php echo $itmid; ?>"> 
                                                            <div class="form-group">
                                                                <div class="form-group styled-select">
                                                                    <select name="ben[]" id="ben" class="form-control">
                <?php $qryben="SELECT `id`, `title` FROM `benifitype` order by id"; $resultben = $conn->query($qryben);
                if ($resultben->num_rows > 0) {while($rowben = $resultben->fetch_assoc()) {   $benid= $rowben["id"];  $bennm=$rowben["title"]; ?>
                                                                        <option value="<?php echo $benid; ?>" <?php if ($benifittp == $benid) { echo "selected"; } ?>><?php echo $bennm; ?></option>
                <?php  }}?>                                         </select>
                                                                </div>
                                                            </div> 
                                                        </div>
                          	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <input type="number" step = "any" class="form-control" id="bamount" name="bamount[]" value="<?php echo $befitamount;?>" required>
                                                            </div> 
                                                        </div> <!-- this block is for Benefit Amount--> 
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-group styled-select">
                                                                    <select name="per[]" id="per" class="form-control">
                                                                        <option value="0" <?php if ($isPercentage == 0) { echo "selected"; } ?>>No</option>
                                                                        <option value="1" <?php if ($isPercentage == 1) { echo "selected"; } ?>>Yes</option>
                                                                    </select>
                                                                </div>
                                                            </div>        
                                                        </div>
                                                        
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-group styled-select">
                                                                    <select name="cycle[]" id="cycle" class="form-control">
                                                                        <option value="1" <?php if ($cycle == 1) { echo "selected"; } ?>>Monthly</option>
                                                                        <option value="2" <?php if ($cycle == 2) { echo "selected"; } ?>>Daily</option>
                                                                        <option value="3" <?php if ($cycle == 3) { echo "selected"; } ?>>Quarterly</option>
                                                                        <option value="4" <?php if ($cycle == 4) { echo "selected"; } ?>>Yearly</option>
                                                                    </select>
                                                                </div>
                                                            </div>        
                                                        </div>                                                
                                                    </div>
<?php               }
                }
            } ?>                                        
                         	                    </div>
                                            </div> 
                                        <!-- this block is for php loop, please place below code your loop  --> 
                                            
                                            <br><br>&nbsp;<br><br>
                                            <div class="col-sm-12">
                                                <?php if($mode==2) { ?>
                                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Pakage" id="update" >
                                                <?php } else {?>
                                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Pakage" id="add" >
                                                <?php } ?> 
                                                <a href = "./packageSetupList.php?pg=1&mod=4">
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
include_once('common_footer.php');
//$cusid = 3;
?>

<?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>

<script>
    $(document).ready(function(){
        
        $(document).on("change", "#package", function() {
            
            var val = $(this).val();
            
            loadhere(val);
            
    });
    
    function loadhere(val){
        $('.toLoad').empty();
        
		$.ajax({
			url:"phpajax/load_ps.php",
			method:"POST",
			data:{val:val},
			success:function(res)
			{
				$('.toLoad').append(res);
			}
		});		
    }

})
</script>

<script>



$(document).ready(function(){

    $('input').on('ifChecked', function(event){event.target.value = 1;});
    $('input').on('ifUnchecked', function(event){event.target.value = 2;});
    
});
   
    
    
</script>

</body>
</html>
<?php }?>