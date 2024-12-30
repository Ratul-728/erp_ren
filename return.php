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
    $rid= $_GET['id'];
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
    $qry="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
    ,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr
    ,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent,concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno,org.email
    FROM  soitem o left join orderstatus s on o.orderstatus=s.id
     left join organization org on o.organization=org.id
    left join district d on org.district=d.id
    left join area a on org.area=a.id
    left join contact c on o.customer=c.id
    left join district d1 on c.district=d1.id
    left join area a1 on c.area=a1.id
	left join deveryagent da on o.deliveryby=da.id
    where o.id=  ".$id; 
    //echo $qry; die;
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
                            $ordid=$row["oid"];$order_id=$row["order_id"];$payment_mood=$row["payment_mood"]; $cusnm=$row["cusnm"]; $orderdt=$row["order_date"];  $phone=$row["phone"];
                            $orderstatus=$row["orderstatus"]; $ost=$row["ost"];  $deladr=$row["deladr"];$name=$row["name"]; $cusaddr=$row["cusaddr"]; $amount=$row["amount"]; $discount_total=$row["discount_total"];
                            $shipping_charge=$row["shipping_charge"];$deleveryagent=$row["agentname"];
                           $hrid='1';
                        }
                }
            }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    else
    {
                            $orderid='';$poid=''; $supid=''; $orderdt=date("Y-m-d");  $currency='0';$adv='';
                            $tot_amount='0'; $invoice_amount='0'; $vat='0';$tax='0'; $delivery_dt=date("Y-m-d");$hrid='1';
                            
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
                       
                     <form method="post" action="#" id="form1" enctype="multipart/form-data">  
                    <!-- START PLACING YOUR CONTENT HERE -->
                  <div class="alertmsg"></div>
                    <?php include_once('phpajax/load_return.php'); ?> 
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
    $tid = $row1["id"];  
	$nm =  $row1["name"]; 
?>          
                                        <option value="<?php echo $tid; ?>" <?php if ($deleveryagent == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php }}?>                    
                                    </select>
                                </div>
                            </div>          
                        </div>  
                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Send To Delivery" id="update"  > -->
                        <input class="btn btn-lg btn-default" type="submit" name="Print" value="Print"  id="Print"  onclick="printDiv('printableArea')">
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

<script>
  $(document).ready(function() {
    $(".btn-return").click(function(){
		
        //alert($(this).data('oid')+'--'+$(this).data('tid'));
		
		var oid = $(this).data('oid');
		var tid = $(this).data('tid');
		var pid = $(this).data('pid');
		var rate = $(this).data('rate');
		var bc = $(this).data('bc');
		var cost = $(this).data('cost');
		var ret_qty=$(this).closest('.itm_ret').find('.return_qty').val();
        var store = $(this).closest('.itm_ret').find('.store').val();
         var sosl =$(this).data('sosl');
		//alert(disc);
		const that = this;
		
		if (!store)
		{
		 alert("Please Select return Warehouse");
		 return false;
		}
		if (ret_qty<1)
		{
		 alert("Please input return quantity");
		 return false;
		}
		$(this).val('Please wait...');
		
		
		$.ajax({
			url:"phpajax/update_return.php",
			method:"POST",
			data:{oid:oid, tid:tid,ret_qty:ret_qty,store:store,pid:pid,sosl:sosl,rate:rate,bc:bc,cost:cost},
			success:function(res)
			{
				messageAlert(res,'alert-danger');
				
				$(that).val('Done');
				$(that).attr("disabled","disabled");
			
				
			}
		});		
		
		
		
    }); 
});
</script>

</body>
</html>



<?php }?>