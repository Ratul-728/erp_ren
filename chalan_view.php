<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $cid= $_GET['id'];
   // $serno= $_GET['id'];
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
    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
    $qry="select p.id,p.poid,p.adviceno,p.supid,DATE_FORMAT(p.orderdt,'%e/%c/%Y') orderdt,DATE_FORMAT(p.delivery_dt,'%e/%c/%Y') delivery_dt ,s.Name 
from po p left join suplier s on p.supid=s.id  where p.id= ".$cid; 
   // echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            }
        else
            {
                $result = $conn->query($qry); 
                if ($result->num_rows > 0)
                {
                    while($row = $result->fetch_assoc()) 
                        { 
                            $chalanidid=$row["id"];$order_id=$row["poid"];$adviceno=$row["adviceno"]; $cusnm=$row["Name"]; $orderdt=$row["orderdt"]; 
                            $delivery_dt=$row["delivery_dt"];
                           $hrid='1';
                        }
                }
            }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    else
    {
                           $chalanidid='';$order_id='';$adviceno=''; $cusnm=''; $orderdt=$row["orderdt"]; 
                             $delivery_dt=date("Y-m-d");$hrid='1';
                            
    $mode=1;//Insert mode
                        
    }
    
    $currSection = 'cusorder';
    $currPage = basename($_SERVER['PHP_SELF']);
?>

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
            <span>Challan Order</span>
        </div>
        <?php include_once('menu.php'); ?>
       
        <div style="height:54px;"></div>
    </div>
    <!-- END #sidebar-wrapper --> 
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12" >
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
                       
                     <form method="post" action="common/updatecusorder.php" id="form1" enctype="multipart/form-data">  
                    <!-- START PLACING YOUR CONTENT HERE -->
                  
                    <?php include_once('phpajax/load_chalan_view.php'); ?> 
        <!-- /#end of panel -->    
                     <div class="button-bar">
                        <!--<div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <div class="form-group styled-select">
                                    <select name="cmbsupnm" id="cmbsupnm" class="form-control" required>
                                        <option value="">Select Delivery Agent </option>
<?php 
$qry1="SELECT `id`, `name`  FROM `deveryagent` order by name";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
{ 
  $tid= $row1["id"];  $nm=$row1["name"]; 
?>          
                                        <option value="<?php echo $tid; ?>" <?php if ($deleveryagent == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php }}?>                    
                                    </select>
                                </div>
                            </div>          
                        </div>  
                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Send To Delivery" id="update"  > -->
                        <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')">
                    </div>    
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
?>


<script>
    
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}


</script>

</body>
</html>



<?php }?>