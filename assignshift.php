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
    $aid= $_GET['id'];
    $strqry = '';

    if ($res==4)
    {
       $strqry = " and id = ".$aid;
    }
    else
    {
        $uid='';$hrid='0'; $menuid='0'; $menu_priv='0'; 
    $mode=1;//Insert mode
                     
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'assignshift';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    /*if ( isset( $_POST['submit'] ) ) {
           header("Location: ".$hostpath."/common/addpriv.php");
    }*/
    $mnhrid = $_POST['cmbempnm'];
    if($mnhrid==''){$mnhrid=$hrid;}
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
            <span>Assign Shift</span>
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
                    <!-- START PLACING YOUR CONTENT HERE 
                        <form method="post" action="priv.php?mod=5"  id="form1">     
                            <div class="panel panel-info">
                                <div class="panel-heading"><h1>User Privillage</h1></div>
                                <div class="panel-body">
                                    <span class="alertmsg"></span>
                                    <br>
      	                            <p>(Field Marked * are required) </p>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>"> 
                                                <label for="email">Employee Name </label>
                                                <div class="form-group styled-select">
                                                <select name="cmbempnm" id="cmbempnm" class="form-control" >
    <?php 
    $qry1="SELECT `id`,concat(`resourse_id`,'-',`hrName`) hrName FROM `hr` ";$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
    {while($row1 = $result1->fetch_assoc()) 
          {   $tid= $row1["id"];  $nm=$row1["hrName"]; 
    ?>          
                                                    <option value="<? echo $tid; ?>" <? if ($mnhrid == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php 
          }
    }      
    ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                              <label for="email"> </label>
                                            <div class="form-group">
                                                <input class="btn btn-lg btn-default" type="submit" name="find" value="Get"  id="find" > 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>  -->  
                        <span class="alertmsg"></span>
                        <form method="post" action="common/assignshift.php"  id="form1">
                            <div class="panel panel-info">
                                <div class="panel-body">
                                    <div class="row">
                                          
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                               
                                                <label for="email">Employee Name </label>
                                               <input type="hidden"  name="husrid" id="husrid" value="<?php echo $mnhrid;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                               
                                                <label for="email">Shifting  </label>
                                               
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                               
                                                <label for="email">Effective Date </label>
                                               
                                            </div>        
                                        </div>
                                    </div>

<?php  $qrymenu="SELECT `id`, `firstname`, `lastname` FROM `employee` WHERE 1=1 ".$strqry;
//echo $qrymenu;die;
$resultmnu = $conn->query($qrymenu); if ($resultmnu->num_rows > 0)
    {while($rowmnu = $resultmnu->fetch_assoc()) 
          {
                $qrych = "SELECT `id`,`shift`,`effectivedt` FROM `assignshift` WHERE empid = ".$rowmnu["id"];
                $resultch = $conn->query($qrych); 
                if ($resultch->num_rows > 0){
                    $rowch = $resultch->fetch_assoc();
                    $shift = $rowch["shift"];
                    $effectivedt = $rowch["effectivedt"];
                    $effectivedt = date("d-m-Y", strtotime($effectivedt));
                }else{
                    $shift = '';
                    $effectivedt = '';
                }
                
                $fullname = $rowmnu["firstname"]." ".$rowmnu["lastname"];
          
          ?>                                    
                                    <div class="row">
                                        <input type="hidden"  name="menuid[]" id="menuid" value="<?php echo $rowmnu["id"] ?>">
                                        <input type="hidden"  name="auth[]" id="auth" value="<?php echo $rowmnu["firstname"] ?>">
                                      	<div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                 <input type="text" class="form-control" id="mn" name="mn" value="<?= $fullname ?>" disabled>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <div class="form-group styled-select">
                                                <select name="cmbpriv[]" id="cmbpriv" class="form-control">
                                                    
                                                <?php   $qryshift = "SELECT `title` ,`id` FROM `Shifting`";
                                                        $resultshift = $conn->query($qryshift); 
                                                        while($rowshift = $resultshift->fetch_assoc()){ 
                                                ?>
                                                    
                                                    <option value="<?= $rowshift["id"] ?>" <?php if($shift == $rowshift["id"]) echo "selected" ?>><?= $rowshift["title"] ?></option>
                                                <?php } ?>
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
	                                    
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="action_dt" name="action_dt[]" value="<?php echo $effectivedt;?>" >
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
                                    </div>
<?php }} ?>                                    
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Add User" id="update" disabled>
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="submit" value="Assign Shift" id="list" >
                                <?php } ?>           
                                
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
if ($_GET["msg"] != ''){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
</body>
</html>
<?php }?>