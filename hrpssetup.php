<?php

require_once("common/conn.php");
session_start();

$usr=$_SESSION["user"];

if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}

else

{

    $res= $_GET['res'];

    $msg= $_GET['msg'];

    $id= $_GET['id'];
    $atid = $id;

    if ($res==4)

    {

        $qry="SELECT `id`,`hrid`,`psType`,`PS` FROM `hrPSsetup` WHERE  id = ".$id; 

        //echo $qry; die;

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
                        $pstype =$row["psType"];  $hrid = $row["hrid"]; $ps = $row["PS"]; $psid = $row["id"];
                        
                    }

            }

        }

    $mode=2;//update mode

    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 

    }

    else

    {
        $pstype = ''; $sl = ''; $kpi = ''; $hrid = '';  $ps = ''; $ownpoint = '';$lmpoint = ''; $mpoint = ''; $revpoint = ''; $hrpoint = '';
        $mode=1;//Insert mode

                    

    }



    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

    */

    $currSection = 'hrpssetup';

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

            <span>HR PS Setup Details</span>

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

                        <form method="post" action="common/addhrpssetup.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->

                            <div class="panel panel-info">

      			                <div class="panel-heading"><h1>HR PS Setup Information</h1></div>

				                <div class="panel-body">

                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>

                                    <div class="row">
                                        
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbprdtp">Employee </label>
                                                <div class="form-group styled-select">
                                                    <select name="empid" id="empid" class="form-control">
<?php $qrymu="SELECT `id`, concat(`firstname`, ' ', `lastname`) empname FROM `employee` order by empname"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 

      { 
          $mid= $rowmu["id"];  $mnm=$rowmu["empname"];
?>                                                          

                                                    <option value="<?php echo $mid; ?>" <?php if ($hrid == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
<?php  }}?>  
                                                    </select>
                                                </div>
                                          </div>
                                        </div>
                                        <input type = "hidden" name = "tid" value = "<?= $atid ?>" >
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbprdtp">PS Type </label>
                                                <div class="form-group styled-select">
                                                    <select name="pstype"  id="pstype" class="form-control">
<?php $qrymu="SELECT `id`, `title` FROM `psType` order by id"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 

      { 
          $mid= $rowmu["id"];  $mnm=$rowmu["title"];
?>                                                          

                                                    <option value="<?php echo $mid; ?>" <?php if ($pstype == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
<?php  }}?>  
                                                    </select>
                                                </div>
                                          </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbprdtp">Performance Standared</label>
                                                <div class="form-group styled-select">
                                                    <select name="ps" id="ps" class="form-control">
<?php $qrymu="SELECT `id`, `title` FROM `performanceStandared` order by id"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 

      { 
          $mid= $rowmu["id"];  $mnm=$rowmu["title"];
?>                                                          

                                                    <option value="<?php echo $mid; ?>" <?php if ($ps == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
<?php  }}?>  
                                                    </select>
                                                </div>
                                          </div>
                                        </div>
                                        
                                    <div class="po-product-wrapper withlebel"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>KPI Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
	                                         <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10"> SI* </h6>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> KPI </h6>
                                                </div>
                                                
                                        </div>
<?php if($mode==1||$mode==5){?> 	            
                                           
	                                        <div class="toClone">
          	                                  
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
												<!--	<lebel>Unit Total</lebel>-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="si" placeholder="SI"  name="si[]">
                                                    </div>
                                                </div> <!-- this block is for unittotal--> 
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
														<!--	<lebel>Remarks</lebel>-->
                                                            <div class="form-group">
                                                                <!--label for="cmbpoc">Account Manager</label-->
                                                                <div class="form-group styled-select">
                                                                    <select name="cmbkpi[]" id="cmbkpi" class="cmd-child1 form-control" >
                                                                    <option value="">Select KPI </option> 
                        <?php $qryhrm="SELECT `id`,`title` FROM `KPI` ORDER BY `title`"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
                          { 
                              $hridm= $rowhrm["id"];  $hrnmm=$rowhrm["title"];
                        ?>                                                          
                                                                        <option value="<?php echo $hridm; ?>" <?php if ($poc == $hridm) { echo "selected"; } ?>><?php echo $hrnmm; ?></option>
                        <?php  }}?>                                                       
                                                                      </select>
                                                                  </div>
                                                            </div>     
                                                        </div>
                                                    </div>
                                                </div>  <!-- this block is for remarks--> 
                                               
                                            </div>
<?php } else {
	$rCountLoop = 0;$itdgt=0;    
$itmdtqry="SELECT `si`,`kpi` FROM `hrPSsetupKPI` WHERE `psid` = ".$psid."";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) {while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $si = $rowitmdt["si"]; $cmbkpi = $rowitmdt["kpi"]
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toClone">
          	                                  
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
												<!--	<lebel>Unit Total</lebel>-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="si" placeholder="SI"  name="si[]" value = "<?= $si ?>">
                                                    </div>
                                                </div> <!-- this block is for unittotal--> 
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
														<!--	<lebel>Remarks</lebel>-->
                                                            <div class="form-group">
                                                                <!--label for="cmbpoc">Account Manager</label-->
                                                                <div class="form-group styled-select">
                                                                    <select name="cmbkpi[]" id="cmbkpi" class="cmd-child1 form-control" >
                                                                    <option value="">Select KPI </option> 
                        <?php $qryhrm="SELECT `id`,`title` FROM `KPI` ORDER BY `title`"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
                          { 
                              $hridm= $rowhrm["id"];  $hrnmm=$rowhrm["title"];
                        ?>                                                          
                                                                        <option value="<?php echo $hridm; ?>" <?php if ($cmbkpi == $hridm) { echo "selected"; } ?>><?php echo $hrnmm; ?></option>
                        <?php  }}?>                                                       
                                                                      </select>
                                                                  </div>
                                                            </div>     
                                                        </div>
                                                    </div>
                                                </div>  <!-- this block is for remarks--> 
                                                 <?php
                                                if($rCountLoop>0){
												?>
                                               		<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                                <?php
													
												}
												$rCountLoop++;
												?>  
                                            </div>
<?php  } }
else
{
?>
                                            <div class="toClone">
          	                                  
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
												<!--	<lebel>Unit Total</lebel>-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="si" placeholder="SI"  name="si[]" value = "<?= $si ?>">
                                                    </div>
                                                </div> <!-- this block is for unittotal--> 
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
														<!--	<lebel>Remarks</lebel>-->
                                                            <div class="form-group">
                                                                <!--label for="cmbpoc">Account Manager</label-->
                                                                <div class="form-group styled-select">
                                                                    <select name="cmbkpi[]" id="cmbkpi" class="cmd-child1 form-control" >
                                                                    <option value="">Select KPI </option> 
                        <?php $qryhrm="SELECT `id`,`title` FROM `KPI` ORDER BY `title`"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
                          { 
                              $hridm= $rowhrm["id"];  $hrnmm=$rowhrm["title"];
                        ?>                                                          
                                                                        <option value="<?php echo $hridm; ?>" <?php if ($cmbkpi == $hridm) { echo "selected"; } ?>><?php echo $hrnmm; ?></option>
                        <?php  }}?>                                                       
                                                                      </select>
                                                                  </div>
                                                            </div>     
                                                        </div>
                                                    </div>
                                                </div>  <!-- this block is for remarks--> 
                                            </div>
<?php }} ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        <br>&nbsp;<br>
                                    <div class="col-sm-12">
                                    <?php
									//echo $mode;
                                    	$addClassName = ($mode=="1")?'link-add-po':'link-add-po-2';
									?>
        	                            <a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
                                    <br><br>&nbsp;<br><br>
                                    </div>                                        
                                        


                                    </div>

                                </div>

                            </div> 

                            <!-- /#end of panel -->      

                            <div class="button-bar">

                                <?php if($mode==2) { ?>

    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update HR PS Setup"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->

                                <?php } else {?>

                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add HR PS Setup"  id="submit" >

                                <?php } ?>  
                            <a href = "./hrpssetupList.php?pg=1&mod=4">
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

<?php    include_once('common_footer.php');?>
<?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
</body>

</html>

<?php }?>