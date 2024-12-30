<?php
//print_r($_REQUEST);
//exit();
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{
  header("Location: ".$hostpath."/mo.php");
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $id= $_GET['id'];
    $acm= $_GET['acm'];
    $yr= $_GET['yr'];
  
   
    $totamount=0;
    
   if ($res==1)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
         $mode=1;
    }
    else if ($res==2)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>";
         $mode=1;
    }
    else if ($res==4)
    {
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    else
    {
    $mode=1;//Insert mode
    }
    
    $currSection = 'target';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
     include_once('common_header.php');
?>
<body class="form">
    
<?php
    include_once('common_top_body.php');
?>

<div id="wrapper"> 
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Sales Target(Item)</span>
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
                    <form method="post" action="common/addtarget.php" id="form1" enctype="multipart/form-data">  
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->  
                    <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="panel panel-info">
      		                <div class="panel-heading"><h1>Add New Target</h1></div>
			                    <div class="panel-body">
                                    <span class="alertmsg"></span>
                            <br>
                          	<p>(Field Marked * are required) </p>
     	                   
                                    <div class="row">
                            	        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbitmcat">Account Manager*</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbhrmgr" id="cmbhrmgr" class="form-control">
                                                    <option value="">Select Account Manager</option>
<?php $qryhrm="SELECT `id`, concat(`emp_id`,'-',`hrName`) `emp_id` FROM `hr`  order by emp_id"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
{ 
  $hridm= $rowhrm["id"];  $hrnmm=$rowhrm["emp_id"];
?>                                                          
                                                    <option value="<?php echo $hridm; ?>" <?php if ($acm == $hridm) { echo "selected"; } ?>><?php echo $hrnmm; ?></option>
<?php  }}?>                                                       
                                                    </select>
                                                </div>
                                            </div>        
                                        </div>
                                        
  	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbyear"> Year*</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbyear" id="cmbyear" class="cmd-child form-control" >
                                                    <option value="">Select Year</option>
                                                <?php 
                                                $earliest_year='2015';$latest_year='2022';
                                                foreach ( range( $latest_year, $earliest_year ) as $i ) {
                                                ?>
                                                    <option value="<?php echo $i; ?>" <?php if ($i == $yr) { echo "selected"; } ?>><?php echo $i; ?></option>
                                                <?php }?>    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div>
      	                          
                            	    <br>
                                        <div class="po-product-wrapper"> 
                                            <div class="color-block">
     		                                    <div class="col-sm-12">
	                                                <h4>Target Information  </h4>
		                                            <hr class="form-hr">
	                                            </div>
<?php if($mode==1){?> 	                                        
	                                            <div class="toClone">
	                                                
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <input type="hidden" placeholder="ITEM" name="targetid[]" class="targetid"> 
                                                            <div class="form-group">
                                                                <div class="form-group styled-select">
                                                                    <select name="mnth[]" id="mnth" class="form-control">
                                                                    <option value="">Select Month</option>
     <?php  for ($i = 1; $i <= 12;   $i++) {
     //$time = strtotime(sprintf('+%d months', $i));
     //$label = date('F ', $time);
     //$value = date('m', $time);
     $value = $i;
     $label = strftime('%B', mktime(0, 0, 0, $i));
        //$date_str = $i.date('M', strtotime("+ $i months"));
        ?>                                                          
                                                                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
         <?php  }?>                                                       
                                                                    </select>
                                                                </div>
                                                            </div>        
                                                        </div>
	                                                
          	                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                    
                                                        <div class="form-group">
                                                            <div class="form-group styled-select">
                                                                <select name="itemcat[]" id="itemcat" class="form-control">
                                                                <option value="">Select Item Catagory</option>
 <?php $qrymu="SELECT `id`, `name` FROM `itmCat`  order by name"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["id"];  $mnm=$rowmu["name"];
    ?>                                                          
                                                                <option value="<?php echo $mid; ?>"><?php echo $mnm; ?></option>
     <?php  }}?>                                                       
                                                                </select>
                                                            </div>
                                                        </div>        
                                                    </div>
          	                                    
          	                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                            <div class="form-group styled-select">
                                                                <select name="itemName[]" id="itemName" class="form-control">
                                                                    <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, name FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                    <option data-value="<?php echo $tid; ?>" value="<?php echo $tid; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                                 </select>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="target" placeholder="Target" name="target[]">
                                                        </div>        
                                                    </div>
                                                </div>
<?php } else {
$itdgt=0;    
$itmdtqry="SELECT `id`, `yr`, `mnth`, `accmgr`, `itmcatagory`, `item`, `target`  FROM `salestarget` where  yr= ".$yr." and accmgr=".$acm;
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) {while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $itmdtid= $rowitmdt["id"];  $itdcat=$rowitmdt["itmcatagory"]; $item=$rowitmdt["item"]; $ittarget=$rowitmdt["target"];
                  $itmmnth=$rowitmdt["mnth"]; 
                  $itmmnthnm = strftime('%B', mktime(0, 0, 0, $itmmnth));
     //$value = date('m', $time);
?>                                            
                                                <!-- this block is for php loop, please place below code your loop  -->   
                                                <div class="toClone">
                                                    
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <input type="hidden" placeholder="ITEM" name="targetid[]" class="targetid"> 
                                                        <div class="form-group">
                                                            <div class="form-group styled-select">
                                                                <select name="mnth[]" id="mnth" class="form-control"> 
                                                                <!--<option value="">Select Month</option>-->
 <?php  //for ($i = 0; $i < 12;   $i++) {    $date_str = date('M', strtotime("+ $i months"));    ?>                                                          
                                                                <option value="<?php echo $itmmnth; ?>"><?php echo $itmmnthnm; ?></option>
     <?php  //}?>                                                       
                                                                </select>
                                                            </div>
                                                        </div>        
                                                    </div>
                                                     
                                                     
          	                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                    
                                                        <div class="form-group">
                                                            <div class="form-group styled-select">
                                                                <select name="itemcat[]" id="itemcat" class="form-control">
                                                                <!--<option value="">Select Item Catagory</option>-->
         <?php $qrymu="SELECT `id`, `name` FROM `itmCat` WHERE id=".$itdcat; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
                      { 
                          $mid= $rowmu["id"];  $mnm=$rowmu["name"];
            ?>                                                          
                                                                <option value="<?php echo $mid; ?>"><?php echo $mnm; ?></option>
             <?php  }}?>                                                       
                                                                </select>
                                                            </div>
                                                        </div>        
                                                    </div>
              	                                    
              	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                           <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                            <div class="form-group styled-select">
                                                                <select name="itemName[]" id="itemName" class="form-control">
                                                                   <!-- <option value="">Select Item</option> -->
        <?php $qryitm="SELECT `id`, name FROM `item` where id=".$item; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
                  { 
                      $tid= $rowitm["id"];  $nm=$rowitm["name"];
        ?>
                                                                    <option data-value="<?php echo $tid; ?>" value="<?php echo $tid; ?>"><?php echo $nm; ?></option>
        <?php  }  }?>                    
                                                                 </select>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                    
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="target" placeholder="Target" name="target[]" value="<?php echo $ittarget; ?>">
                                                        </div>        
                                                    </div>
                                                </div>
<?php }}} ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        
                                        
                                       <!-- <div class="well no-padding top-bottom-border grandTotalWrapper">
                                        <div class="row total-row">
                                            <div class="col-xs-offset-6 col-xs-6 col-sm-offset-8 col-sm-4  col-md-offset-8 col-md-4 col-lg-offset-8 col-lg-1">
                                            <div class="form-group grandTotalWrapper">
                                                <label>Total:</label>
                                                <input type="text" class="form-control" id="grandTotal" value="<?php echo $itdgt;?>" disabled>
                                              </div>
                                          </div>
                                          </div>
                                      </div>    -->
                                        
                                        
                                    </div>      
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
        	                            <a href="#" class="link-add-po" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
                                    <br><br>&nbsp;<br><br>
                                    <br><br>&nbsp;<br><br>&nbsp;<br><br><br>
                                </div>
                           
                        </div>
                    </div> 
        <!-- /#end of panel -->      
                    <div class="button-bar">
                            <?php if($mode==2) { ?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Target" id="update" >
                          <?php } else {?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Target" id="add" >
                          <?php } ?>
                        <a href = "./targetList.php?pg=1&mod=5">
                          <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                        </a>
                    </div>        
          <!-- START PLACING YOUR CONTENT HERE --> 
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
<?php include_once('inc_cmb_loader_js.php');?>
</body>
</html>
<?php }?>