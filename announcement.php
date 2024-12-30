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
    $itid= $_GET['id'];

    
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
        $qry="SELECT `id`,announceid,announcedt, `title`, `catagory`, `organization`, `announce`, `makeby`, `makedt` FROM `announce`  where id= ".$itid; 
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
                        $anid=$row["id"];$title=$row["title"];$announceid=$row["announceid"];$announcedt=$row["announcedt"]; $catagory=$row["catagory"];  $organization=$row["organization"];$announce=$row["announce"]; 
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
                       $anid='';$title=''; $catagory='';  $organization='';$announce='';  $announcedt='';$announceid='';
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'announce';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<link rel="stylesheet" href="./css/ak-bit.css">

<body class="form">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Announcement </span>
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
                        <form method="post" action="common/addannounce.php"  id="form1"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <!--<div class="panel-heading"><h1>Announcement </h1></div> -->
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    <div class="row">
                                        <div class="col-sm-3 text-nowrap">
                                                <h6>Issue <i class="fa fa-angle-right"></i> Issue ticket</h6>
                                           </div>
                                           <br>
                                           <br>
                                    </div>
                                    
                                    <div class="row">
                                        
      		                            <div class="col-sm-12">
      		                                 
	                                      <!--  <h4></h4>
	                                        <hr class="form-hr"> -->
	                                        
		                                    <input type="hidden"  name="anid" id="anid" value="<?php echo $anid;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
	                                    </div> 
	                                    <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Announce ID.</label> 
                                                <input type="text" class="form-control" id="announceno" name="announceno" value="<?php echo $announceid;?>" >
                                            </div>        
                                        </div> <!--Ticket-->
                                         <div class="form-group">
                                                
                                                <input type="text" class="form-control" id="title" name="title" value="<?php echo $title;?>" placeholder="Add a title" required>
                                            </div> 
                                        <!--Subject-->
                                        <div class="col-lg-4 col-md-6 col-sm-6"> <!--Probable Resolve Date--> 
                                            <label for="chqdt">Announce Date </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="announcedt" name="announcedt" value="<?php echo $announcedt;?>">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div> <!--Probable Resolve Date--> 
                                        <div class="col-lg-4 col-md-6 col-sm-6"><!--Organization--> 
                                            <div class="form-group">
                                                <label for="cmbinv">Organization*</label>
                                                <div class="form-group styled-select">
                                                <select name="cmborg" id="cmborg" class="cmb-parent form-control" required>
                                                	<option value="">Select Type</option>
													<?php $qryorg="SELECT distinct o.`id`,o.`name` FROM `contact` c,`organization` o where c.`organization`=o.`orgcode`  and c.`contacttype`=1  order by o.name"; $resultorg = $conn->query($qryorg); if ($resultorg->num_rows > 0) {while($roworg = $resultorg->fetch_assoc()){
                                                    	$tid= $roworg["id"];  $nm=$roworg["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($organization == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                             </div>
                                          </div>        
                                        </div><!--Organization--> 
                                        <div class="col-lg-4 col-md-6 col-sm-6"> <!--Product--> 
                                            <div class="form-group">
                                                <label for="cmbmode"> Catagory *</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbprod" id="cmbprod" class="form-control" required>
    <?php 
    $qry1="SELECT `id`,`name` FROM `announcecatagory`  order by `name` ";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
              $tid= $row1["id"];  $nm=$row1["name"];
    ?>          
                                                        <option value="<? echo $tid; ?>" <? if ($catagory == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php }}?>                    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div> <!--Product--> 
                                        
                                        <div class="col-lg-12 col-md-12 col-sm-12"> <!--Issue Details--> 
                                            <div class="form-group">
                                                <label for="ref">Announcement  Details</label>
                                                <input type="text" class="form-control" id="announce" name="announce" value="<?php echo $announce;?>">
                                            </div>        
                                        </div><!--Issue Details-->
                                    </div>
                                </div>
                            </div>  
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Announce"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Announce"  id="add" >
                                <?php } ?>
                            <a href = "./announcementList.php?pg=1&mod=6">
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
</body>
</html>
<?php }?>