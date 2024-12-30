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
    
    $orderID = $_POST["cmbempnm"];
    
    if($orderID != null){
        header("Location: ".$hostpath."/deliveryQA.php?order=".$orderID); 
    }


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
            <span>Delivery</span>
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
                        <form method="post" action="deliveryQASearch.php?mod=3"  id="form1">     
                            <div class="panel panel-info">
                                <div class="panel-heading"><h1>Delivery</h1></div>
                                <div class="panel-body">
                                    <span class="alertmsg"></span>
                                    <br>
    
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Order ID </label>
                                                <div class="form-group styled-select">
                                                <select name="cmbempnm" id="cmbempnm" class="form-control" >
    <?php 
    $qry1="SELECT `id`,order_id FROM `qa` where status = 1 ";$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
    {while($row1 = $result1->fetch_assoc()) 
          {   $tid= $row1["order_id"];  $nm=$row1["order_id"]; 
    ?>          
                                                    <option value="<? echo $tid; ?>" ><? echo $nm; ?></option>
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
</body>
</html>
<?php }?>