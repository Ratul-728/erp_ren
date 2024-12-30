<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$com=$_SESSION["company"];

if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $msg = $_GET["msg"];
    $res = $_GET["res"];
    $id = $_GET["id"];
    
    if ($res==4)
    {
        $qry="SELECT `id`, lpad(id,4,'0') `suplierId`, `name`, `address`, `contact`,email,web FROM `suplier` where id= ".$id; 
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
                        $spid=$row["id"];$suplierId=$row["suplierId"];
                        //echo $uid;die;
                        $Name=$row["name"];  
                        $address=$row["address"];
                        $contact_no=$row["contact"];
                        $email=$row["email"];
                        $web=$row["web"];
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
                        $spid='';$suplierId=''; $Name='';  $address=''; $contact_no='';$email='';$web='';
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'supplier';
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
            <span>Supplier Details</span>
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
                        <form method="post" action="common/addsuplier.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Supplier Information</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>
                                    <div class="row">
      		                            <div class="col-sm-12">
	                                        <h4></h4>
	                                        <hr class="form-hr">
		                                    <input type="hidden"  name="auid" id="auid" value="<?php echo $spid;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
		                  
		                                    
	                                    </div>      
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="supcd">Suplier ID </label>
                                                <input type="text" class="form-control" id="supcd" name="supcd" value="<?php echo $suplierId;?>" disabled>
                                            </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="supnm">Supplier Name *</label>
                                                <input type="text" class="form-control" id="supnm" name="supnm" value="<?php echo $Name;?>" required>
                                            </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="addr">Address</label>
                                                <input type="text" class="form-control" id="addr" name="addr" value="<?php echo $address;?>">
                                            </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cell">Cell NO</label>
                                                <input type="text" class="form-control" id="cell" name="cell" value="<?php echo $contact_no;?>" >
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cell">Email</label>
                                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email;?>" >
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cell">Web</label>
                                                <input type="text" class="form-control" id="web" name="web" value="<?php echo $web;?>" >
                                            </div>        
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                           <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Supplier"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Supplier"  id="add" >
                                <?php } ?>
                            <a href = "./supplierList.php?mod=12&pg=1">
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