<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $res= $_GET['res'];  $msg= $_GET['msg']; $atid= $_GET['id'];
    if ($res==1){ echo "<script type='text/javascript'>alert('".$msg."')</script>"; }
    if ($res==2){ echo "<script type='text/javascript'>alert('".$msg."')</script>"; }
    if ($res==4)
    {
        $qry="SELECT a.`id`, a.`code`,a.name, v.`attribute`
, GROUP_CONCAT(v.`attributevalue` ORDER BY v.attributevalue ASC SEPARATOR ', ') atv
   from  attribute a left join attributevalue v on a.code=v.attribute where a.id= ".$atid." GROUP BY a.`id`, a.`code`,a.name, v.`attribute`"; 
       // echo $qry; die;
        if ($conn->connect_error){ echo "Connection failed: " . $conn->connect_error; }
        else
        {  $result = $conn->query($qry); 
            if ($result->num_rows > 0){ while($row = $result->fetch_assoc())  {$aid=$row["id"];$atr=$row["name"]; $cod=$row["code"];$atrv=$row["atv"]; } }
        }
    $mode=2;//update mode
    }
    else
    {$aid='';$atr=''; $cod=''; $mode=1;$atrv='';//Insert mode
    }

    $currSection = 'attr';
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
            <span>Attribute </span>
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
                        <form method="post" action="common/addattribute.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Attribute Information</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    
                                    <div class="row">
      		                            <div class="col-sm-12">
	                                      <!--  <h4></h4>
	                                        <hr class="form-hr"> -->
		                                    <input type="hidden"  name="atid" id="atid" value="<?php echo $aid;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
		                                    <input type="hidden"  name="cd" id="cd" value="<?php echo $cod;?>">
	                                    </div> 
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Attribute*</label>
                                                <input type="text" class="form-control" id="attr" name="attr" value="<?php echo $atr;?>" required>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Attribute Values</label>
                                                <input type="text" class="form-control" id="attrv" name="attrv" value="<?php echo $atrv;?>" required>
                                            </div>        
                                        </div>
                                        <!--<div class="po-product-wrapper withlebel"> 
                                            <div class="color-block">
     		                                    <div class="col-sm-12">
	                                                <h4>Attribute Information  </h4>
		                                            <hr class="form-hr">
	                                            </div>
<?php if($mode==1||$mode==5){?> 	                                        
    	                                        <div class="toClone">
                                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                        <div class="row qtnrows">
                                                            <div class="col-sm-12 col-xs-12">
    															<lebel>Values</lebel>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="attval" placeholder="Attributr Value" name="attval[]">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </div>
<?php } else {
	$rCountLoop = 0;$itdgt=0;    
$itmdtqry="SELECT `id`, `catagory`, `attribute`,`attributevalue`, `sl` FROM catagoryatribute   WHERE catagory='".$code."'";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) {while($rowitmdt = $resultitmdt->fetch_assoc()) { 
                  $itmdtid= $rowitmdt["attribute"]; $atv= $rowitmdt["attributevalue"];
?>                                            
                                            <div class="toClone">
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
															<lebel>Values</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="attval" placeholder="Attributr Value" name="attval[]" value="<?php echo $atv;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                               <?php if($rCountLoop>0){	?>
                                               		<div class="remove-icon"><a href="#" class="remove-po" title="Remove "><span class="glyphicon glyphicon-remove"></span></a></div>
                                                <?php	}$rCountLoop++;	?>
                                            </div>
<?php  }}else {?>
                                            <div class="toClone">
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
															<lebel>Values</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="attval" placeholder="Attributr Value" name="attval[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
<?php }} ?>                                     		
                                    	
                                        </div>
                                        </div>      
                                        <br>&nbsp;<br>
                                        <div class="col-sm-12">
                                    <?php $addClassName = ($mode=="1")?'link-add-po':'link-add-po-2';?>
        	                                <a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another attribute</a>
	                                    </div> -->
                                        <!--<div class="col-lg-3 col-md-6 col-sm-6">

                                        <strong>Attribute Image</strong>

                                        <div class="input-group">

                                            <label class="input-group-btn">

                                                <span class="btn btn-primary btn-file btn-file">

                                                    <i class="fa fa-upload"></i> <input type="file" name="attachment1" id="attachment1" style="display: none;" multiple>

                                                </span>

                                            </label>

                                            <input type="text" class="form-control" readonly>

                                        </div>

                                        <span class="help-block form-text text-muted">

                                            Try selecting one  files and watch the feedback

                                        </span>

                                    </div> -->
                                    </div>
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Atrribute"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Attribute"  id="add" >
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