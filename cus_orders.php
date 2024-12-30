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
    $oid= $_GET['id'];
    $serno= $_GET['id'];
    $totamount=0;
    
   
    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
    $qry="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
    ,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr,c.street, a1.name arnm,d1.name dist
    ,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent,concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno,org.email
    FROM  soitem o left join orderstatus s on o.orderstatus=s.id
     left join organization org on o.organization=org.id
    left join district d on org.district=d.id
    left join area a on org.area=a.id
    left join contact c on o.customer=c.id
    left join district d1 on c.district=d1.id
    left join area a1 on c.area=a1.id
	left join deveryagent da on o.deliveryby=da.id
    where o.id=".$oid; 
    //echo $qry; die;
        if ($conn->connect_error) { echo "Connection failed: " . $conn->connect_error;  }
        else {  $result = $conn->query($qry); 
                if ($result->num_rows > 0)
                {
                    while($row = $result->fetch_assoc()) 
                        { 
                            $uid=$row["oid"];$order_id=$row["order_id"];$name=$row["cusnm"]; $phone=$row["phone"]; $address=$row["street"];  $district=$row["dist"];
                            $area=$row["arnm"]; $email=$row["email"];$order_date=$row["order_date"];$payment_mood=$row["payment_mood"];
                            $hrid=$usr;
                        }
                }
            }
    $mode=2;//update mode
   
    $currSection = 'cusorder';
    $currPage = basename($_SERVER['PHP_SELF']);
?>

<?php     include_once('common_header.php'); ?>
<body class="form">
<?php    include_once('common_top_body.php'); ?>
<div id="wrapper"> 
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Modify Order</span>
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
                    <p>&nbsp;</p> <p>&nbsp;</p>
                    <p>
                       <form method="post" action="common/updateorder.php" id="form1" enctype="multipart/form-data" onsubmit="return confirm('Do you really want to save the Order?');">  
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">
      		            <div class="panel-heading"><h1>Modify Order</h1></div>
			            <div class="panel-body">
                            <span class="alertmsg"></span>
                          	<p>(Field Marked * are required) </p>
                                <div class="row">
                        		    <div class="col-sm-12">
	                                    <hr class="form-hr">
	                                    <input type="hidden"  name="pid" id="pid" value="<?php echo $oid;?>"> 
	                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>" >
	                                    <input type="hidden"  name="orderno" id="orderno" value="<?php echo $order_id;?>" >
	                                    <input type="hidden"  name="orderdt" id="orderdt" value="<?php echo $order_date;?>" >
	                                    <input type="hidden"  name="ordermod" id="ordermod" value="<?php echo $payment_mood;?>" >
	                                    <input type="hidden"  name="nm" id="nm" value="<?php echo $payment_mood;?>" >
	                                    <div class="form-group">
		                                    <label for="po_id">Order  NO*</label>
		                                    <input type="text"  name="corderno" id="corderno" value="<?php echo $order_id;?>" disabled>
                                        </div>
	                                </div> 
	                                
	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="po_id">Name* </label>
                                            <input type="text" class="form-control" name="nmd" id="nmd" value="<?php echo $name?>" disabled>
                                        </div>        
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="po_id">Address* </label>
                                            <input type="text" class="form-control" name="addr" id="addr" value="<?php echo $address?>" disabled>
                                        </div>        
                                    </div>
      	                            <div class="col-lg-2 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsupnm">District*</label>
                                            <input type="text" class="form-control" name="cmbdis" id="cmbdis" value="<?php echo $district?>" disabled>
                                            <!--div class="form-group styled-select">
                                                <select name="cmbdis" id="cmbdis" class="form-control"  required>
                                                    <option value="">Select District</option>
    <?php 
    $qry1="SELECT `id`, `name`  FROM `districts` order by name";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
              $tid= $row1["id"];  $nm=$row1["name"];
    ?>          
                                                    <option value="<?php echo $tid; ?>" <?php if ($district == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php }}?>                    
                                                </select>
                                            </div-->
                                        </div>        
                                    </div> 
                        	        <div class="col-lg-2 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsupnm">Area*</label>
                                            <input type="text" class="form-control" name="cmbarea" id="cmbarea" value="<?php echo $area?>" disabled>
                                            <!--div class="form-group styled-select">
                                                <select name="cmbarea" id="cmbarea" class="form-control"  required>
                                                    <option value="">Select Area</option>
    <?php 
    $qry1="SELECT `id`, `name`  FROM `areas` order by name";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
              $tid= $row1["id"];  $nm=$row1["name"];
    ?>          
                                                    <option value="<?php echo $tid; ?>" <?php if ($area == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php }}?>                    
                                                </select>
                                            </div-->
                                        </div>        
                                    </div> 
                        	        <div class="col-lg-2 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="po_id">Email* </label>
                                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $email?>" disabled>
                                        </div>        
                                    </div>    
                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="po_id">Phone* </label>
                                            <input type="text" class="form-control" name="phon" id="phon" value="<?php echo $phone?>" disabled>
                                        </div>        
                                    </div> 
                                    <br>
                                    <div class="po-product-wrapper"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Product Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php
$rCountLoop  = 0;
        $itdgt       = 0;
        $itmdtqry    = "SELECT `id`, `socode`, `sosl`, `productid`, `mu`, round(`qty`,0) qty,round(`qtymrc`,0)qtymrc, round(`otc`,2) otc, round(`mrc`,2)mrc, `remarks`, `makeby`, `makedt`,`currency`,vat,ait FROM `soitemdetails` WHERE `socode`='" . $order_id . "'";
       // echo $itmdtqry;
        $resultitmdt = $conn->query($itmdtqry);if ($resultitmdt->num_rows > 0) {while ($rowitmdt = $resultitmdt->fetch_assoc()) {
            $itmdtid  = $rowitmdt["productid"];
            $itdmu    = $rowitmdt["mu"];
            $itdqu    = $rowitmdt["qty"];
            $itdqumrc = $rowitmdt["qtymrc"];
            $itdotc   = $rowitmdt["otc"];
            $itdmrc   = $rowitmdt["mrc"];
            $itdrem   = $rowitmdt["remarks"];
            $currency = $rowitmdt["currency"];
            $itvat    = $rowitmdt["vat"];
            $itait    = $rowitmdt["ait"];
            $itdtot   = (($itdqu * $itdotc) + ($itdqumrc * $itdmrc));
            $itdgt    = $itdgt + $itdtot;
            ?>
                                            <!-- this block is for php loop, please place below code your loop  -->
                                            
                                            
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Item </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">VAT % </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">AIT % </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Select Unit </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Quantity</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Price</h6>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Unit Total </h6>
                                                </div>
                                            </div>    
                                            
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemName-->
													<div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="itemName[]" id="itemName" class="form-control dl-itemName">
                                                                <option value="">Select Item</option>
    <?php $qryitm = "SELECT `id`, `name`, cost, vat, ait  FROM `item`  order by name";
            $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                $tid  = $rowitm["id"];    $nm   = $rowitm["name"]; $cost = $rowitm["cost"];  $vat  = $rowitm["vat"]; $ait  = $rowitm["ait"];
                ?>
                                                                <option data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" value="<?php echo $tid; ?>" <?php if ($itmdtid == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
    <?php }} ?>
                                                           </select>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for itemName-->
                                                <!-- this block is for vat-->
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="numeric" class="form-control vat" id="vat"  value="<?php echo number_format($itvat,2); ?>" name="vat[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- this block is for ait-->
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="numeric" class="form-control ait" id="AIT"  value="<?php echo number_format($itait,2); ?>" name="ait[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
          	                                    <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for measureUnit-->
													
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="measureUnit[]" id="measureUnit" class="form-control">

 <?php //and `id`=".$itdmu."
            $qrymu    = "SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name";
            $resultmu = $conn->query($qrymu);if ($resultmu->num_rows > 0) {while ($rowmu = $resultmu->fetch_assoc()) {
                $mid = $rowmu["id"];
                $mnm = $rowmu["name"];
                ?>
                                                                <option value="<?php echo $mid; ?>" <?php if ($itdmu == $mid) {echo "selected";} ?>><?php echo $mnm; ?></option>
     <?php }} ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for measureUnit-->
          	                                    <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for quantity_otc, unitprice_otc-->
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" value="<?php echo $itdqu; ?>" name="quantity_otc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input readonly type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" value="<?php echo $itdotc; ?>" name="unitprice_otc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc-->
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 text-center"><!-- this block is for unittotal-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" readonly  value="<?php echo $itdtot; ?>"  name="unittotal[]">
                                                    </div>
                                                </div>
            <?php if ($rCountLoop > 0) {?>     	<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
            <?php }$rCountLoop++;?>
                                            </div>
<?php }}?>
                                        </div>
                                        
                                        
<div class="well no-padding top-bottom-border grandTotalWrapper">

    <div class="row total-row">

        <div class="col-xs-offset-6 col-xs-6 col-sm-offset-8 col-sm-4  col-md-offset-8 col-md-4 col-lg-offset-8 col-lg-1">

        	<div class="form-group grandTotalWrapper">

                <label>Total:</label>

                <input type="text" class="form-control" id="grandTotal" value="<?php echo $itdgt;?>" disabled>

            </div>
        	</div>
    	</div>
	</div>    
	</div>                                      &nbsp;
                                    <div class="col-sm-12">
        	                            <a href="#" class="link-add-po" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
                                   
                                    <br><br>&nbsp;<br><br>&nbsp;<br><br><br>
                                </div>
                           
                        </div>
                    </div> 
        <!-- /#end of panel -->      
                    <div class="button-bar">
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Order" id="update">
                    </div>        
          <!-- START PLACING YOUR CONTENT HERE --> 
           </form
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

	
	
/*  autofill combo  */

 var dataList=[];
$(".list-itemName").find("option").each(function(){dataList.push($(this).val())})

/*
//print dataList array
 $.each(dataList, function(index, value){
           $(".alertmsg").append(index + ": " + value + '<br>');
});
*/

/* Check wrong category */
var catlavel;	
var flag;

	

	
//$(".dl-itemName").change(function(){
$(document).on("change", ".dl-itemName", function() {
	
	
	//alert($(this).val());
	var root = $(this).parent().parent().parent().parent();
	root.find(".itemName").attr('style','border:1px solid red!important;');
	
	src = root.find(".cmb-attr");
	
	
	
	for(var i in dataList) {
		userinput = $(this).val();
	 	catlavel = dataList[i];
		
		//$(".alertmsg").append(dataList[i]+ '<br>');
		
		if(userinput === catlavel){
			flag = 1;
			
			//root.find(".itemName").val($(this).val());
			//alert($(this).attr("thisval"));
			
				var g = $(this).val();
				var id = $('#itemName option[value="' + g +'"]').attr('data-value');
			  //alert(id);
			root.find(".itemName").val(id);
			loadItemTypeAttr(id,src);
			break;
		}else{
			flag = 0;
		}
	}
	if(flag == 0){
		$(this).val("");
		}
	
	});
/* end Check wrong category */	
	
/* end autofill combo  */

	
</script>

<script>
    $(document).ready(function(){

        $(document).on("change", ".dl-itemName", function() {

            var val = $(this).val();

            var cost = $('#itemName option[value="' + val +'"]').attr('data-cost');

            $(this).closest('.toClone').find('.unitprice_otc').val(cost);
			$(this).closest('.toClone').find('.quantity_otc').val(1);
            $(this).closest('.toClone').find('.unitTotalAmount').val(cost);
			

            var vat = $('#itemName option[value="' + val +'"]').attr('data-vat');

            $(this).closest('.toClone').find('.vat').val(vat);

            var ait = $('#itemName option[value="' + val +'"]').attr('data-ait');

            $(this).closest('.toClone').find('.ait').val(ait);
           



	//alert(222);
    var sum = 0;
    $(".unitTotalAmount").each(function(){
       
		sum += +$(this).val();
	   sum1=sum.toFixed(2);
         $("#grandTotal").val(sum1);
  });


  


    });

})
</script>
</body>
</html>
<?php }?>