<?php
include_once ("common/conn.php");
include_once ("rak_framework/fetch.php");
include_once ("rak_framework/misfuncs.php");

session_start();

//ini_set('display_errors',1);
$usr=$_SESSION["user"];

//echo $usr;die;

$res= $_GET['res'];
$msg= $_GET['msg'];

$csid = $_GET["csid"];



if($usr=='')
{ 	header("Location: ".$hostpath."/hr.php");
}
else
{
	

    $currSection = 'delivery_return';
	// load session privilege;
	//include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);


       
    
    
    ?>
    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php
     include_once('common_header.php');
    ?>
    
    <body class="list">
        
    <?php
     include_once('common_top_body.php');
    ?>
    
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>Return Order</span>
      </div>
      
    <?php
        include_once('menu.php');
    ?>
      
      	<div style="height:54px;">
    	</div>
      </div>
    
      <!-- END #sidebar-wrapper --> 
      
      <!-- Page Content -->
      <div id="page-content-wrapper">
        <div class="container-fluid xyz">
          <div class="row">
            <div class="col-lg-12">
            
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            
              <!--h1 class="page-title">Customers</a></h1-->
              <p>
              <!-- START PLACING YOUR CONTENT HERE -->
    
    
              <div class="panel panel-info">
      			<!--<div class="panel-heading"><h1>All Service Order(Item)</h1></div>-->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    
    
 
                	<form method="post" action="common/adddelivery_return.php" id="form1" enctype="multipart/form-data">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
        <link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                    
                          
                         
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>Delivery  <i class="fa fa-angle-right"></i> Delivery Return </h6>
                       </div>
                      
                      
                        
                        
                      </div>
                    </div>
                    
    
    				
                  <style>
                .table-header{
                        padding: 15px 25px;
                    }        
                </style>
                        
                        
                  <div class="well table-header">

                                

                  <div class="dataTables_scroll qa-grid-wrapper">
                        <!-- Table -->
                        <table id="xxlistTable" class="dataTable actionbtn qadetail-grid" width="100%">
                            <thead>
                            <tr>
                                <th>DO ID</th>
                                <th>Order ID</th>
                                <th>Code</th>
                                <th>Product</th>
                                <th>Return Quantity</th>
                                <!--th>From Warehouse</th-->
                                <th>To Warehouse</th>

                            </tr>
                            </thead>
                            <tbody>

                        <?php
                        
                           //Get Info
                            $qryInfo = "SELECT d.do_id, d.order_id,i.name item, i.code, b.name warehouse, dod.id, dod.due_return_qty,i.image,  br.name fromwarehouse,
                                        cat.name cname, bra.title brand, i.id product
                                        FROM `delivery_order_detail` dod LEFT JOIN delivery_order d ON d.id=dod.do_id LEFT JOIN item i ON dod.item=i.id 
                                        LEFT JOIN branch b ON b.id=6 LEFT JOIN qa_warehouse qaw ON dod.qa_id = qaw.id LEFT JOIN branch br ON br.id = qaw.warehouse_id
                                        LEFT JOIN itmCat cat ON i.catagory=cat.id
                                        LEFT JOIN brand bra ON bra.id=i.brand 
                                        WHERE dod.due_return_qty > 0 and d.do_id = '".$csid."'"; //echo $qryInfo;die;
                            $resultInfo = $conn->query($qryInfo);
                            while ($rowInfo = $resultInfo->fetch_assoc()) {
                                $doId = $rowInfo["do_id"];
                                $orderId = $rowInfo["order_id"];

                                $image = $rowInfo["image"];
                                $product = $rowInfo["item"];
                                $code = $rowInfo["code"];
                                $qty = $rowInfo["due_return_qty"];
                                $brand = $rowInfo["brand"];
                                $cat = $rowInfo["cname"];
                                $warehouse = $rowInfo["warehouse"];
                                $fromwarehouse = $rowInfo["fromwarehouse"];
                                $productId = $rowInfo["product"];
                                
                                $dodId = $rowInfo["id"];
                            
                        ?>
                                <tr>
                                    <td><?= $doId ?></td>
                                    <td><?= $orderId ?></td>
                                    <td><?= $code ?></td>
                                    <td class="product"><!-- Product -->
                                        <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="qathmb">
                                                    <img src="/assets/images/products/300_300/<?= $image ?>" height="100">
                                                </td>
                                                <td class="text-wrap" style="width: 90%">
                                                    <div class="qaitemdesc">
                                                        <strong>Item</strong> : <?= $product ?><br>
                                                        <strong>Category:</strong> <?= $cat ?> <br>
                                                        <strong>Brand:</strong> <?= $brand ?> <br>

                                                    </div>



                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                    <td><?= $qty ?></td>
                                    <!--td><?= $fromwarehouse ?></td-->
                                    <td>
                                        <select class="form-control" name="warehouseIds[]" id="">
                                    <?php
                                        $qryBranch = "SELECT id, name from branch order by name";
                                        $resultBranch = $conn->query($qryBranch);
                                        while ($rowBranch = $resultBranch->fetch_assoc()) {
                                            $bid = $rowBranch["id"];
                                            $bname = $rowBranch["name"];
                                    ?>
                                            <option value="<?= $bid ?>" <?php if($bid == 6)echo "selected"; ?>><?= $bname ?></option>
                                            
                                    <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <input type="hidden" name="csids[]" value="<?=$dodId?>">
                                <input type="hidden" name="productIds[]" value="<?=$productId?>">
                                <input type="hidden" name="orderIds[]" value="<?=$orderId?>">
                                <input type="hidden" name="returned_items[]" value="<?=$qty?>">
                        
                        <?php } ?>
                           







                            </tbody>
                        </table>
                    </div>  
                 
                        
                      <input class="btn btn-lg btn-default top" type="submit" name="btn_delchalan" value="Send to QC" style="margin: 0;">
                        
<br>
<br>
<br>
                        
                        

        </form>         
                    
			
    				
    
                 </div>
            </div> 
            <!-- /#end of panel -->  
    
              <!-- START PLACING YOUR CONTENT HERE -->          
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
		

		
		
    <?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
    



<!--script src="https://code.jquery.com/jquery-3.6.0.min.js"></script-->

<script>
$(document).ready(function() {
    
 $(".delitype").on('ifChecked', function() {
    //$(".delitype").on('change', function() {
    var dataId = $(this).data('id');
    var value = $(this).val();
    //alert(dataId+'||'+value);
    
    console.log("data-id: " + dataId + ", value: " + value);
    
    $.ajax({
        url:"phpajax/updateDeliverySt.php",
        method:"POST",
        data:{id: dataId, value: value, action:'state'},
        //dataType: 'JSON',
        success:function(res)
        {
                
			console.log(res);
            messageAlert(res);
                            

        }
    });
     return false;
  });
    
    
    
    
  $(".plus, .minus").on("click", function() {
    var inputField = $(this).siblings(".quantity");
    var qty = parseFloat(inputField.val());

 		var rowId = inputField.closest("tr").attr("id");
		var rowId = rowId.split('_')[1];
    
    var col = inputField.data("col");
    
    inputField.val(qty);
    
    //alert( qty+" | "+ rowId +" | " + col);
      
     
      var oq = parseInt($("#ordered_qty_"+rowId).val());
      var pendingQty  = parseInt($("#qty_pending_"+rowId).val()) || 0;
      var intransitQty  = parseInt($("#qty_intransit_"+rowId).val()) || 0;
      var deliveredQty  = parseInt($("#qty_delivered_"+rowId).val()) || 0;
      var returnedQty  = parseInt($("#qty_returned_"+rowId).val()) || 0;
      var totalSum = intransitQty + deliveredQty + returnedQty;
      
      
      //alert( rowId +" | " +pendingQty+ " | "+ intransitQty +" | " + oq);
      
      if(totalSum > 0){
          $("#inprocess_"+rowId).iCheck('check');
      }else{
          $("#inprocess_"+rowId).iCheck('uncheck');    
      }
      
      
      
        if (totalSum > oq) {
            var adjustment = totalSum - oq;
            pendingQty -= adjustment;
            pendingQty = Math.max(pendingQty, 0);
            $("#qty_pending_"+rowId).val(pendingQty);
        } else {
            pendingQty = oq - totalSum;
            $("#qty_pending_"+rowId).val(pendingQty);
        }
      
      
    //send ajax;
      //alert(total);
    
  $.ajax({
        url:"phpajax/updateDeliverySt.php",
        method:"POST",
        data:{id: rowId, qty: qty,col:col, action:'qtyupdate'},
        //dataType: 'JSON',
        success:function(res){
                
					console.log(res);
            messageAlert(res);
        }
    });
    
    
  });
   
    
    <?php
        if($_REQUEST['msg']){
        ?>
        messageAlert("<?=$_REQUEST['msg']?>");
        <?php
        }
    ?>    
    
 
    <?php
        if($isFileUploaded){
        ?>
        $(".plus, .minus, .quantity").prop("disabled", true);
        
        <?php
        }
    ?>     
    
    
}); //$(document).ready(function() {
    

</script>

    
    </body></html>
  <?php }?>    
