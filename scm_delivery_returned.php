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





if($usr=='')
{ 	header("Location: ".$hostpath."/hr.php");
}
else
{
	

    $currSection = 'scm_delivery_status_detail';
	// load session privilege;
	//include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);
    
    $deliveryId = $_GET["do"];
    //$deliveryId = "DO-000009";
    $orderId = fetchByID('delivery_order','do_id',$deliveryId,'order_id');
    

       
    
    
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
        <span>QA Test</span>
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
    
    
 
                	<form method="post" action="common/scm_delivery_status_update_upload.php" id="form1" enctype="multipart/form-data">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
        <link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                    
                          
                         
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>Delivery  <i class="fa fa-angle-right"></i> Delivery Status <i class="fa fa-angle-right"></i> Delivery ID: <?= $deliveryId ?> </h6>
                       </div>
                      
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                            <div class="pull-right grid-panel form-inline d-none">

                                    <div class="form-group">
                                        <label for="">Filter by: </label>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-group styled-select">
                                            <select name="cmbstatus" id="cmbstatus" class="form-control" >
                                                <option value="0">All Status</option>
                                    <?php
                                $qry1    = "select id,name from quotation_status order by name";
                                    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
                                        $tid = $row1["id"];
                                        $nm  = $row1["name"];
                                        ?>
                                                <option value="<?php echo $tid; ?>" <?php if ($icat == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
                                    <?php }} ?>
                                            </select>
                                        </div>
                                    </div> 



                                <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control">     
                                </div>
                                <div class="form-group">
                                <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                                </div>
                                <div class="form-group">
                                <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                                <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
                                </div>

<!--                                <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">-->
                            </div>
                        
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

                                <th>Return ID</th>
                                <th>Order ID</th>
                                <th>DO ID</th>
                                <th>Delivey Date</th>
                                <th>Return Date</th>
                                <th>Customer</th>
                                <th>Barcode</th>
                      
                                  <th>Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Supervisor</th>
                                <th>Product Location</th>

                            </tr>
                            </thead>
                            <tbody>


                                <tr>
                                    <td>Return ID</td>
                                    <td>Order ID</td>
                                    <td>DO ID</td>
                                    <td>25/08/2023</td>
                                    <td>28/08/2023</td>
                                    <td>Jahir Uddin</td>
                                    <td>546465465</td>
                                    <td class="product"><!-- Product -->
                                        <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="qathmb">
                                                    <img src="/assets/images/products/300_300/<?= $image ?>" height="100">
                                                </td>
                                                <td class="text-wrap" style="width: 90%">
                                                    <div class="qaitemdesc">
                                                        <strong>Item</strong> : Chair<br>
                                                        <strong>Category:</strong> Furniture <br>
                                                        <strong>Brand:</strong> ABC <br>

                                                    </div>



                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                    <td class="text-center qty">5</td>
                                    <td class="text-center noofparts">Sayed Shagor</td>

                                    <td>
                                        Current Location: GRS<br>
                                        <select class="form-control" name="returned_item" id="">
                                            <option value="12">Goran Warehosue</option>
                                        </select>
                                    </td>


                                </tr>
                           







                            </tbody>
                        </table>
                    </div>  
                  
                 
                  <?php
                        $debug = 0;
                        $isFileUploaded = fetchByID('soitem','socode',$orderId,'delivey_challan_path');
                        $challanUploadDate = fetchByID('soitem','socode',$orderId,'delivey_challan_upload_date');
                        
                    ?>
                  <div class="well">
          sds
                  </div>
    
                        <?php
                        if(!$isFileUploaded){
                        ?>
                        <input type="hidden" name="doid" value="<?=$deliveryId?>">
                        <input type="hidden" name="orid" value="<?=$orderId?>">
                        
                      <input class="btn btn-lg btn-default top" type="submit" name="btn_delchalan" value="Upload & Complete Delivery" style="margin: 0;">
                        <?php
                        }
                        ?>
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
