<?php
require "common/conn.php";
require "rak_framework/connection.php";
require "rak_framework/fetch.php";
session_start();
//ini_set('display_errors',1);

$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $aid= $_GET['id'];

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'email_event';
    $currPage = basename($_SERVER['PHP_SELF']);
    
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<style>
.privillages{
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 15px;
}
.privillages > div{
    padding: 0px 5px;
    margin-right: 5px;
    margin-bottom: 5px;
    border-bottom: 0px solid #c0c0c0;
    border-radius: 0px;
/*     background-color: #eeeeee; */
}

.privillages  input{
    margin: 0;
    padding: 0;
}  
    
.row.table-bordered div[class*="col-"] {
    padding-top: 15px;
    
}



.icheck-primary{
    margin-bottom: 0!important;
}
    
.row-striped:nth-of-type(odd){
  background-color: #efefef;
}

.row-striped:nth-of-type(even){
  background-color: #ffffff;
}
    .row-striped input[readonly]{
    background-color:#ffffff;
}
</style>

<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />	
<body class="form">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
            <i class="fa fa-group  icon"></i>
            <span>Email Event</span>
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
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
                    <!-- START PLACING YOUR CONTENT HERE -->

                        <form method="post" action="common/addemail_event.php"  id="form1">
                            <div class="panel panel-info">
                                <div class="panel-body">
                        <span class="alertmsg"></span>
<?php  
$qrymenuroot="select * from email_menu order by name asc";
//echo $qrymenu;die;
$resultmnuroot = $conn->query($qrymenuroot); if ($resultmnuroot->num_rows > 0)
    {
	
	while($rowmnuroot = $resultmnuroot->fetch_assoc()){
	    
	    $rootmenuid = $rowmnuroot["id"];
	    $rootmenunm = $rowmnuroot["name"];

?>
<h3 style="font-weight: bold; font-size: 24px; color: #333; font-family: Arial, sans-serif; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
    <?= $rootmenunm ?>
</h3>

                                    <div class="row">
                                          
                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                               
                                                <label for="email">Event </label>
                                            </div>        
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                               
                                                <label for="rcv">Receiver  </label>
                                               
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                               
                                                <label for="to">To</label>
                                               
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                               
                                                <label for="cc">CC</label>
                                               
                                            </div>        
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                               
                                                <label for="email">Active</label>
                                               
                                            </div>        
                                        </div>
                                    </div>
<?php 

$qrymenu="select * from email where parent_id = $rootmenuid order by name asc";
//echo $qrymenu;die;
$resultmnu = $conn->query($qrymenu); if ($resultmnu->num_rows > 0)
    {
	
	while($rowmnu = $resultmnu->fetch_assoc()){
		
			$tid= $rowmnu["id"];  
			$nm=$rowmnu["name"]; 
			$type=$rowmnu["type"]; 
			$active=$rowmnu["active"]; 
			$isChecked = ($active == 1)?'checked':'';
			
			if($type == 1 || $type == 3){
			    $rcv = "Internal";
			}else{
			    $rcv = "External";
			}
		?>                                    
                                    <div class="row table-bordered row-striped">
                                        <input type="hidden"  name="menuid[]" id="menuid" value="<?php echo $tid;?>">
                                      	<div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                 <input type="text" class="form-control" id="mn" name="mn" value="<?php echo $nm;?>" disabled>
                                            </div>        
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                 <input type="text" class="form-control" id="rcv" name="rcv" value="<?php echo $rcv;?>" disabled>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                              
                                                <select name="tos[<?= $tid ?>][]" id="to_<?=$tid?>" placeholder="dfd" class="cmd-child form-control todrop" multiple <?php if($type == 2 || $type == 3) echo "disabled"; ?>>
                                <?php 
                                    // To
                                    $to = array();
                                    $qryInfo="SELECT `employee` FROM `email_to_cc` WHERE type = 1 and `emailid` = '".$tid."'";
                                    $resultInfo = $conn->query($qryInfo);
                                    while ($rowinfo = $resultInfo->fetch_assoc()) {
                                        $to[] = $rowinfo["employee"];
                                    }
                                    
                                    $qrytech = "SELECT concat(emp.firstname, ' ', emp.lastname, '(', emp.employeecode, ')') empnm, emp.id FROM `employee` emp ";
                                                
                                    $resultTech = $conn->query($qrytech);
                                    while ($rowTech = $resultTech->fetch_assoc()) {
                                        $empnm = $rowTech["empnm"];
                                        $hractid = $rowTech["id"];
                                
                                ?>
                                                        <option value="<?= $hractid ?>" <?php if (in_array($hractid, $to)) echo "selected"; ?> ><?= $empnm ?></option>
                                <?php } ?>
                                                </select>
                                            
                                        </div>

                                    </div>
                                    
                                     <div class="col-lg-3 col-md-6 col-sm-6">
										 
										 
									 
										 
                                        <div class="form-group">

                                              
                                                <select name="ccs[<?= $tid ?>][]" id="cc_<?=$tid?>" placeholder="dfd" class="cmd-child form-control ccdrop" multiple <?php if($type == 2) echo "disabled"; ?>>
                                <?php 
                                
                                    // To
                                    $cc = array();
                                    $qryInfo="SELECT `employee` FROM `email_to_cc` WHERE type = 2 and `emailid` = '".$tid."'";
                                    $resultInfo = $conn->query($qryInfo);
                                    while ($rowinfo = $resultInfo->fetch_assoc()) {
                                        $cc[] = $rowinfo["employee"];
                                    }
                                    $qrytech = "SELECT concat(emp.firstname, ' ', emp.lastname, '(', emp.employeecode, ')') empnm, emp.id FROM `employee` emp ";
                                                
                                    $resultTech = $conn->query($qrytech);
                                    while ($rowTech = $resultTech->fetch_assoc()) {
                                        $empnm = $rowTech["empnm"];
                                        $hractid = $rowTech["id"];
                                
                                ?>
                                                        <option value="<?= $hractid ?>" <?php if (in_array($hractid, $cc)) echo "selected"; ?> ><?= $empnm ?></option>
                                <?php } ?>
                                                </select>
                                            
                                        </div>

                                    </div>
                                        <!--div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                 <div class="icheck-primary">
														<input <?= $isChecked ?> type="checkbox" name="active[<?= $tid ?>][]" value="1"  id="" >
														<label for=""></label>
														</div>
                                            </div>        
                                        </div-->
                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <div class="icheck-primary">
                                                    <input <?= $isChecked ?> type="checkbox" name="active[<?= $tid ?>][]" value="1" id="checkbox_<?= $tid ?>">
                                                    <label for="checkbox_<?= $tid ?>"></label>
                                                </div>
                                            </div>        
                                        </div>

                                        
                                    </div>
<?php }}}} ?>                                    
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Add User" id="update" disabled>
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="submit" value="Set Email Event" id="list" >
                                <?php } ?>           
                                <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Cancel"  id="cancel" >
                            </div>        
                        </form> 
                        <!-- START PLACING YOUR CONTENT HERE -->          
                    </p> 
                </div>
            </div>    
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
    
    
<?php    include_once('common_footer.php');
if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
    
<?php
if($_POST['cmbempnm']){
?>
 <script>
$(document).ready(function(){
  
            //$(".icheck-primary  input[type=checkbox]").change(function() {
                
             //$(".icheck-primary  input[type=checkbox]").on('ifChanged',function() {
			 $(".icheck-primary  input[type=checkbox]").on('change',function() {
                
                var isChecked = $(this).is(":checked");
                //var isChecked = this.checked;
                var chkValue;
              	var thisKey = $(this).attr('name');
                 var part = thisKey.split("_");
                 var key = part[0];
                 var mnuId = part[1];

                if (isChecked) {
                    chkValue = 1;
                } else {
                    chkValue = 0;
                }
              
               //alert('key:'+key+' | menuid:'+mnuId+' | val:'+chkValue);
              
              
        $.ajax({
          url: 'phpajax/setpriv.php', 
          method: 'POST',
          data: {
            key: key,
            menuid: mnuId,
            val:chkValue,
            targetuser:<?=$_POST['cmbempnm']?>,
          },
          success: function(response) {
            // Handle the successful response here
            //console.log('Success:', response);
            messageAlert(response);
          },
          error: function(xhr, textStatus, errorThrown) {
            // Handle any errors that occur during the request
            console.error('Error:', errorThrown);
            messageAlert(response);
          }
        });
  
   });
});//$(document).ready(function() {    
</script>   
    <?php
    }
    ?>  
    
    
<!-- Select2 JS -->
<script src="js/plugins/select2/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.todrop').select2();
    $('.ccdrop').select2();
  });
</script>
    
    
</body>
</html>
<?php }?>