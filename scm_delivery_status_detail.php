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
	
    $currSection = 'deliverystatus';
    //$currSection = 'scm_delivery_status_detail';
	// load session privilege;
	//include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);
    
    $deliveryId = $_GET["do"];
    //$deliveryId = "DO-000009";
    $orderId = fetchByID('delivery_order','do_id',$deliveryId,'order_id');
    
    //echo $orderId;die;

    $qryInfo="SELECT org.name, so.orderdate, org.orgcode, org.contactno, so.remarks, del.id, rp.delivery_start
                FROM delivery_order del LEFT JOIN `qa` q ON del.order_id = q.order_id LEFT JOIN `soitem` so ON q.order_id=so.socode 
                LEFT JOIN `organization` org ON org.id=so.organization LEFT JOIN `resourceplan` rp ON del.do_id = rp.doid
                WHERE del.do_id = '".$deliveryId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $customerName = $rowinfo["name"];
        $customerId = $rowinfo["orgcode"];
        $orderDate = $rowinfo["orderdate"];
        $customerContact = $rowinfo["contactno"];
        $deliveryAddress = $rowinfo["remarks"];
        $doId = $rowinfo["id"];
        $deli_start=$rowinfo["delivery_start"];
    }
       
    
    
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
    
    
 
                	<form method="post" action="common/scm_delivery_status_update_upload.php" id="doUploadForm" enctype="multipart/form-data">
            
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

                       <div class="row">
                           <div class="col-sm-6">
                                <div class="row">
                                   <div class="col-md-6">
                                           <b>Delivery ID: </b> <?= $deliveryId ?> <br>
                                            <b>CUSTOMER ID:   </b>  <?= $customerId ?>
                                    </div>
                                    <div class="col-md-6">
                                            <b>CUSTOMER NAME: </b> <?= $customerName ?> <br>
                                            <b>CUSTOMER CELL:   </b> <?= $customerContact ?>
                                    </div>
                               </div>

                           </div>

                           <div class="col-sm-6">

                                <div class="row">
                                   <div class="col-md-6">
                                           <b>ORDER DATE:  </b>  <?= $orderDate ?> <br>
                                            <b>DELIVERY DATE:  <?= $deli_start ?>  </b>   
                                    </div>
                                    <div class="col-md-6">
                                            <b>DELIVERY ADDRESS:</b><br>
                                            <?= $deliveryAddress ?>
                                    </div>
                               </div>


                           </div>


                       </div>

                    </div>         

                  <div class="dataTables_scroll qa-grid-wrapper">
                        <!-- Table -->
                        <table id="xxlistTable" class="dataTable actionbtn qadetail-grid" width="100%">
                            <thead>
                            <tr>

                                <th>Barcode</th>
                                <td>Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Number of parts</th>
                                <th>Delivery Status</th>

                            </tr>
                            </thead>
                            <tbody>

                        <?php
                            $qryQa = "SELECT i.barcode,i.name productnm, b.title brandnm, cat.name catnm, i.image, dod.item,
                                        SUM(dod.do_qty) totqty
                                        FROM `delivery_order_detail` dod LEFT JOIN item i ON dod.item =i.id 
                                        LEFT JOIN `brand` b ON i.brand=b.id LEFT JOIN `itmCat` cat ON cat.id = i.catagory
                                        WHERE dod.do_id= ".$doId." GROUP BY dod.item";
                            $resultQa = $conn->query($qryQa);
                            while ($rowQa = $resultQa->fetch_assoc()) {
                                $barcode = $rowQa["barcode"];
                                $productnm = $rowQa["productnm"];
                                $brandnm = $rowQa["brandnm"];
                                $catnm = $rowQa["catnm"];
                                $totQty = $rowQa["totqty"];
                                $image = $rowQa["image"];
                                $itemId = $rowQa["item"];

                        ?>
                                <tr>

                                    <td><?= $barcode ?></td>
                                    <td class="product"><!-- Product -->
                                        <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="qathmb">
                                                    <img src="/assets/images/products/300_300/<?= $image ?>" height="100">
                                                </td>
                                                <td class="text-wrap" style="width: 90%">
                                                    <div class="qaitemdesc">
                                                        <strong>Item</strong> : <?= $productnm ?><br>
                                                        <strong>Category:</strong> <?= $catnm ?> <br>
                                                        <strong>Brand:</strong> <?= $brandnm ?> <br>

                                                    </div>



                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                    <td class="text-center qty"><?= $totQty ?><input type="hidden" name="qty" value="<?=$totQty?>"></td>
                                    <td class="text-center noofparts">-</td>

                                    <td><!-- QA Status by Warehouse -->
                                        <table width="100%" class="qsw-tbl"  cellpadding="5" cellspacing="5">
                                            <tr>
                                                <td class="qsw-1 qsw-head">Warehouse</td>
                                                
                                                <td class="qsw-2 qsw-head qsw-processing">Processing</td>
                                                <td class="qsw-5 qsw-head qsw-pending">Pending</td>
                                                <td class="qsw-3 qsw-head qsw-intransit">In transit</td>
                                                <td class="qsw-4 qsw-head qsw-delivered">Delivered</td>
                                                <td class="qsw-5 qsw-head qsw-returned">Returned</td>

                                            </tr>
                                    <?php 
                                        $qryQaw = "SELECT b.name warehouse, dod.do_qty,dod.id, dod.st, 
                                                    dod.pending_qty, 
                                                    dod.intransit_qty, 
                                                    dod.delivered_qty, 
                                                    dod.due_return_qty,dod.returned_qty
                                                    FROM `delivery_order_detail` dod LEFT JOIN qa_warehouse qaw ON dod.qa_id=qaw.id 
                                                    LEFT JOIN branch b ON b.id=qaw.warehouse_id
                                                    WHERE dod.do_id =".$doId." AND dod.item = ".$itemId; //echo $qryQaw;die;
                                        $resultQaw = $conn->query($qryQaw); $totdq = 0;
                                        while ($rowQaw = $resultQaw->fetch_assoc()) {
                                            $warehouse = $rowQaw["warehouse"];
                                            $doQty = $rowQaw["do_qty"];
                                            $dodId = $rowQaw["id"];
                                            $st =  $rowQaw["st"];
                                            $pq =  $rowQaw["pending_qty"];
                                            $tq =  $rowQaw["intransit_qty"];
                                            $dq =  $rowQaw["delivered_qty"];
                                            //$rq =  $rowQaw["due_return_qty"] + $rowQaw["returned_qty"];
                                            $returned_qty = $rowQaw["returned_qty"];
                                            $rq =  $rowQaw["due_return_qty"];
                                            
                                            $totdq += $dq;
                                            

                                    ?>
                                            <tr class="ds-row-wrapper" id="row_<?=$dodId?>">
                                                <th class="text-right"><?= $warehouse ?> (<?= $doQty ?>) 
												<input type="hidden" id="ordered_qty_<?=$dodId?>" class="ordered_qty" value="<?= $doQty ?>"></th>
                                                <td class="qswr-processing"><input tabindex="1" class="delitype"  id="inprocess_<?=$dodId?>"     type="radio" data-id="<?= $dodId ?>" name="delitype-<?= $dodId ?>" <?php if($st == 1) echo "checked"; ?> value = "1"> &nbsp;</td>
                                                
                                                <td class="qswr-intransit">
												

                                                        <div class="number-input">
                                                          <!--button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown();" class="minus"></button-->
                                                            <?php
                                                               
                                                                $pqty = $doQty-($tq+$dq+$rq);
                                                                $pqty = ($pqty<0)?0:$pqty;
                                                            ?>
                                                          <input class="pending_qty quantity qa-input" data-col="pending_qty" readonly   min="0" max="<?=$doQty?>" name="qty_pending" id="qty_pending_<?=$dodId?>" value="<?=$pqty?>" type="number">
                                                          <!--button type="button"  onclick="this.parentNode.querySelector('input[type=number]').stepUp();" class="plus"></button-->
                                                        </div>
												
												</td>                                                
                                                
                                                <td class="qswr-intransit"><!input tabindex="1" class="delitype"     type="radio" data-id="<?= $dodId ?>" name="delitype-<?= $dodId ?>" <?php if($st == 3) echo "checked"; ?> value = "3"> &nbsp;
												

                                                        <div class="number-input">
                                                          <!--button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown();" class="minus"></button-->
                                                          <input class="transit_qty quantity qa-input" data-col="intransit_qty"   min="0" max="<?=$doQty?>" name="qty_intransit" id="qty_intransit_<?=$dodId?>" value="<?=$tq?>" type="number">
                                                          <button type="button"  class="plus plus_intransit"></button>
                                                        </div>
												
												</td>
                                                <td class="qswr-delivered"><!input tabindex="1" class="delitype"     type="radio" data-id="<?= $dodId ?>" name="delitype-<?= $dodId ?>" <?php if($st == 2) echo "checked"; ?> value = "2"> &nbsp;
                                                
                                                        <div class="number-input">
                                                          <!--button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown();" class="minus"></button-->
                                                          <input class="qty_delivery quantity qa-input" data-col="delivered_qty"  min="0" max="<?=$doQty?>" name="qty_delivered"  id="qty_delivered_<?=$dodId?>" value="<?=$dq?>" type="number">
                                                          <button type="button"  class="plus_delivery plus"></button>
                                                        </div>
                                                
                                                
                                                </td>
                                                <td class="qswr-returned"><!input tabindex="1" class="delitype"     type="radio" data-id="<?= $dodId ?>" name="delitype-<?= $dodId ?>" <?php if($st == 4) echo "checked"; ?> value = "4"> &nbsp;
                                                
                                                        <div class="number-input">
                                                          <!--button  type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown();" class="minus"></button-->
                                                          <input  class="quantity qa-input" data-col="due_return_qty"  min="0" max="<?=$doQty?>" name="qty_returned" id="qty_returned_<?=$dodId?>" value="<?=$returned_qty?>" type="number">
                                                          <button  type="button" data-itemid="<?=$itemId?>" data-deliveryid="<?=$deliveryId ?>" data-orderid="<?=$orderId?>"  class="plus plus_return"></button>
                                                        </div>
                                                
                                                </td>


                                            </tr>
                                    <?php 
                                    $st = "";
                                    } ?>


                                        </table>
                                    </td>


                                </tr>
                            <?php } ?>







                            </tbody>
                        </table>
                    </div>  
                  
                 
                    <?php
                        $debug = 0;
                        $isFileUploaded = fetchByID('soitem','socode',$orderId,'delivey_challan_path');
                        $challanUploadDate = fetchByID('soitem','socode',$orderId,'delivey_challan_upload_date');
                        $condition = "order_id = '$orderId'";
                        $WantedField = array('do_id','delivery_challan_path','upload_date');
                        $ChallanFileArray = fetchByCondition('delivery_order', $condition, $WantedField);
                        //print_r($ChallanFileArray );
                        
                    ?>
                    <style>
                        .challan-doc-list{
                            display: flex;
                            list-style-type: none;
                            padding: 0;
                            margin: 0
                        }
                        
                        .challan-doc-list li {
                            border-right:1px solid #ccc;
                            padding: 15px;
                        }
                        .challan-doc-list li:last-child{border:0;}
                        .challan-doc-list li div{
                            text-align: center;
                            
                        }
                        .challan-doc-list li img{
                            width: 80px!important;
                            padding: 15px; 
                            border: 0px solid #ccc;
                            border-radius: 5px;
                        }
                      
                        .challan-doc-list li img,
                        .challan-doc-list li i{
                            cursor:pointer;    
                        }
                        
                        .upload-challan{
                            padding: 25px 28px;
                            display: block;
                            border: 1px solid #ccc;
                             border-radius: 5px;
                            padding-top: 30px;
                            
                        }
                        
                          .challan-doc-list li i{
                            font-size: 30px;
                            font-weight: normal;
                            
                           
                        }
                        
                        .challan-doc-list li > div{
                        display: flex;
                        justify-content: center;
                    }
                    .challan-doc-list .uploaded{
                        padding: 0;
                        width: 80px;
                    
                    }
                    </style>
            <div  id="uploadColumn">
                  <div class="well">
                      
                      <ul class="challan-doc-list">
                          <?php
                            foreach($ChallanFileArray as $chval){
                               
                               //echo $chval['do_id'];  echo "  |  ";    echo $chval['upload_date'];  echo "  |  ";  echo $chval['delivery_challan_path'];
                              
                                if(strlen($chval['delivery_challan_path']) > 5){ 
                                    echo '<li> 
                                             <div>
                                                  <a class="upload-challan uploaded" href="#"  data-doid="'.$chval['do_id'].'" data-orderid="'.$orderId.'">
                                                     <img src="assets/images/file_icons/doc-file.png" alt="">
                                                  </a>
                                             </div>
                                             <div>
                                               <a target="_blank" href="common/upload/delivery_challan/'.$chval['delivery_challan_path'].'">Download</a>
                                             </div>
                                             <div class="tdate">'.formatDate($chval['upload_date']).'</div>
                                       </li>';
                                }else{
                                    echo '<li>
                                            <div>
                                                <a class="upload-challan" href="#"  data-doid="'.$chval['do_id'].'" data-orderid="'.$orderId.'"><i class="fa fa-plus"></i></a>
                                            </div>
                                            <div class="tdownload">&nbsp;</div>
                                            <div class="tdate">Not uploaded</div>
                                          </li>';
                                }
                            }
                          ?>
                          
                          
                      </ul>
                      <div class="row">
                          
                      
                        <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                            <?php
                            
                           //echo $isFileUploaded;
                            if($isFileUploaded){
                            ?>
                            <div class="file-link">
                                <div class="fileicon">
                                    
                                        <a href="common/<?=$isFileUploaded;?>" target="_blank"><img src="assets/images/file_icons/doc-file.png" alt=""></a>
                                </div>
                                <strong>Upload Date: <?=formatDate2($challanUploadDate)?></strong>
                                <!--div class="remove"><input class="btn btn-sm btn-danger" type="button" value="Remove"></div-->
                            </div>
                            <?php
                            }else{
                                ?>
                            
                            <strong>Upload delivery challan signed by customer</strong>
                            <div class="input-group upload-group">
                                <label class="input-group-btn">
                                    <span class="btn btn-upload btn-primary btn-file btn-file">
                                       <i class="fa fa-paperclip"></i> <input type="file" name="delchallan" style="display: none;" multiple="">
                                    </span>
                                </label>
                                <input type="text" class="form-control" readonly="">
                                
                               
                            </div>
                            <span class="help-block form-text text-muted">
                                Upload PDF or JPG, max 10MB
                            </span>                            
                            
                            <?php
                            }
                            
                            ?>

                        </div>
                          
                          
                       
                      </div>
                  </div>
    
                        <?php
                        if(!$isFileUploaded){
                        ?>
                        <input type="hidden" name="doid" class="doid" value="<?=$deliveryId?>">
                        <input type="hidden" name="orid" class="orid" value="<?=$orderId?>">
                        
                      <!--input class="btn btn-lg btn-default top" type="submit" name="btn_delchalan" value="Upload & Complete Delivery" style="margin: 0;"-->
                        <?php
                        }
                        ?>
            </div>
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

<?php if($totdq == 0 && !$isFileUploaded){ ?>
<script>
$(document).ready(function(){
    var column = document.getElementById("uploadColumn");
    column.style.display = "none";
});
</script>
<?php } else { ?>
<script>
$(document).ready(function(){
    var column = document.getElementById("uploadColumn");
    column.style.display = "block";
});
</script>
<?php } ?>

<script>

$(document).ready(function(){
   
  
  //$(".pending_qty").val(3);
  //$(".ordered_qty").val(3);
   //$(".ordered_qty").val(3);

    var isChanged;
  
  /* PENDING - TRANSIT */
	$(".plus_intransit").on("click",function(){
    
    var root = $(this).closest('.ds-row-wrapper');
    
    var ordered = parseInt(root.find('.ordered_qty').val());
    var transitqty = parseInt(root.find('.transit_qty').val());
    var pendingqty = parseInt(root.find('.pending_qty').val());
    	
    console.log(ordered+'|-|'+transitqty);
    
    if(ordered>transitqty && pendingqty>0){
      	
       var newTransitQty = transitqty + 1;
       var newPendingQty = pendingqty - 1;
       root.find('.transit_qty').val(newTransitQty);
       root.find('.pending_qty').val(newPendingQty);
      //alert(transitqty);
        isChanged = true;
    }else{
      messageAlert("You don't have any pending quantity");
        isChanged = false;
    }
      
});
  
/* TRANSIT - DELIVERY */ 
  
  
	$(".plus_delivery").on("click",function(){
    
    var root = $(this).closest('.ds-row-wrapper');
    
    var ordered = parseInt(root.find('.ordered_qty').val());
    var transitqty = parseInt(root.find('.transit_qty').val());
    var deliveredqty = parseInt(root.find('.qty_delivery').val());
    	
    console.log(transitqty+'|-|'+deliveredqty);
    
    if(transitqty>0){
      	
       var newDeliveryQty = deliveredqty + 1;
       var newTransitQty = transitqty - 1;
       root.find('.qty_delivery').val(newDeliveryQty);
       root.find('.transit_qty').val(newTransitQty);
      
      //alert(transitqty);
        isChanged = true;
    }else{
      messageAlert("You don't have any transit quantity");
        isChanged = false;
    }
      
});  
  
  
$(".plus_intransit, .plus_delivery").on("click",function(){
    

    var inputField = $(this).siblings(".quantity");
 		var rowId = inputField.closest("tr").attr("id");
		var rowId = rowId.split('_')[1];
    var col = inputField.data("col");
    var qty = parseFloat(inputField.val());
    inputField.val(qty);
    
  
  
      var oq = parseInt($("#ordered_qty_"+rowId).val());
      var pendingQty  = parseInt($("#qty_pending_"+rowId).val()) || 0;
      var intransitQty  = parseInt($("#qty_intransit_"+rowId).val()) || 0;
      var deliveredQty  = parseInt($("#qty_delivered_"+rowId).val()) || 0;
      var returnedQty  = parseInt($("#qty_returned_"+rowId).val()) || 0;
      var totalSum = intransitQty + deliveredQty + returnedQty;    
      
      if(deliveredQty > 0){
            var column = document.getElementById("uploadColumn");
            column.style.display = "block";
      }
   //alert( oq +" | "+ pendingQty +" | " + intransitQty + " | "+deliveredQty);
  

    
    //alert( qty+" | "+ rowId +" | " + col + " | "+totalSum);
   
  
        if(totalSum > 0){
          $("#inprocess_"+rowId).iCheck('check');
      }else{
          $("#inprocess_"+rowId).iCheck('uncheck');    
      }
  
  
  
  if(isChanged){
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
  
  }
  
  
  
  });    

  $(".plus_return ").on("click",function(){

      var root = $(this).closest('.ds-row-wrapper');
      var ordered = parseInt(root.find('.ordered_qty').val());
      var delivered = parseInt(root.find('.qty_delivery').val());
      var returnqtyTxt = $(this).siblings(".quantity").val();
      var returnqty = parseInt(returnqtyTxt);
      
      if(returnqty<ordered && delivered > 0){
      
			  swal({
			  title: "Do you want to process a Return?",
			  text: "This will have an effect in inventory",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			  buttons: ['Cancel', 'Process Return'],
			})
			.then((processReturn) => {
			  if (processReturn) {

                  //get data;
               
                  
                  
                  
                  //alert(ordered);
                  

                      var newReturnqty = returnqty + 1;
                      $(this).siblings(".quantity").val(newReturnqty)



                      var itemid = $(this).data("itemid");
                      
                      var deliveryid = $(this).data("deliveryid");
                      var orderid = $(this).data("orderid");


                      //alert(returnqty);

                      setTimeout(function(){
                        //$(this).val("Select Item");

                          $.ajax({
                                url:"phpajax/process_return.php",
                                method:"POST",
                                data:{dodid:'<?=$dodId?>',itemid:itemid, deliveryid:deliveryid, orderid:orderid, qty:1, action:'processreturn'},
                                //dataType: 'JSON',
                                success:function(res){

                                            console.log(res);
                                            messageAlert(res);

                                }
                            });

                      },200);                  
                  
                  
			  } else {

				  
				  return false;
			  }
                  

			});      
      
                  }else{
                   messageAlert("You don't have any delivered quantity");
                  }//if(returnqty<ordered){    
      
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
    
  
});  //$(document).ready(function() {
  

    
</script>

<script>
$(document).on('click', '.upload-challan', function(e) {
    e.preventDefault();
    let root = $(this).closest("ul");
    let orderId = $(this).data('orderid');
    let doId = $(this).data('doid');
    let tthis = $(this);
    
    root.find('.tdownload').text('Uploading...');
    
    
    let fileInput = $('<input type="file" accept=".pdf,.jpg,.png,.jpeg">');
    fileInput.click();

    fileInput.on('change', function() {
        
        tthis.html('<img src="assets/images/file_icons/doc-file.png" alt="">');
        tthis.addClass('uploaded');        
        
        let file = this.files[0];
        if (!file) return;
        
        let formData = new FormData();
        formData.append('file', file);
        formData.append('order_id', orderId);
        formData.append('do_id', doId);

        
        
        
        $.ajax({
            url: 'phpajax/upload_challan_by_do.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
             dataType: 'json', 
            success: function(response) {
                swal(response.message);
                if (response.success) {
                    // You can update the status in the DOM
                    root.find('.tdownload').html('<a target="_blank" href="common/upload/delivery_challan/'+response.path+'">Download</a>');
                    root.find('.tdate').html(response.date);
                }
            },
            error: function() {
                swal('Error occurred during the file upload.');
            }
        });
    });
});
</script>
    
    </body></html>
  <?php }?>    
