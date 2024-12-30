<?php
require "common/conn.php";
session_start();

//ini_set('display_errors',1);
$usr=$_SESSION["user"];
//echo $usr;die;

$res= $_GET['res'];
$msg= $_GET['msg'];
$type= $_GET["type"];
$orderId = $_GET["id"];
$product = $_GET["product"];


$qryInfo="SELECT i.name product, cbr.name cbranch, iw.name tbranch, ch.freeqty current_stock, iod.qty transfer_stock, q.id, iw.name, iw.address 
          FROM `qa` q LEFT JOIN qa_warehouse qaw ON q.id=qaw.qa_id LEFT JOIN item i ON i.id=q.product_id LEFT JOIN issue_order as io ON io.ioid = q.order_id 
          LEFT JOIN issue_order_details iod ON io.id=iod.ioid LEFT JOIN branch cbr ON cbr.id=iod.frombranch LEFT JOIN issue_warehouse iw ON iw.id=io.issue_warehouse LEFT JOIN qastatus qastatus ON qastatus.id = q.status LEFT JOIN chalanstock ch ON (ch.product=q.product_id AND ch.storerome = iod.frombranch)
          WHERE q.product_id=iod.product and q.product_id = $product and q.type = $type and q.order_id = '".$orderId."'";
$resultInfo = $conn->query($qryInfo);
while ($rowinfo = $resultInfo->fetch_assoc()) 
{
    $currentBranch = $rowinfo["cbranch"];
    $transferBranch = $rowinfo["tbranch"];
    $current_stock = $rowinfo["current_stock"];
    $transfer_stock = $rowinfo["transfer_stock"];
    $pronm = $rowinfo["product"];
    $qaid = $rowinfo["id"];
    $name = $rowinfo["name"];
    $address = $rowinfo["address"];
}

$currSection = 'qa_issue_order';



if($usr=='')
{ 	header("Location: ".$hostpath."/hr.php");
}
else
{
	
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
                    <span>Issue Order</span>
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
                                	    
                                        <link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>  
                                        <style>
                                        .table-header{
                                                padding: 15px 25px;
                                            }        
                                        </style>
                                        <div class="well table-header">
                
                                        <div class="row">
                                           <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                           <b>Product: </b> <?= $pronm ?>
                                                    </div>
                                                    <div class="col-md-3">
                                                            <b> Current Branch: </b> <?= $currentBranch ?> <br>
                                                            <b> Transfer Branch: </b> <?= $transferBranch ?>
                                                    </div>
                                                    <div class="col-md-3">
                                                           <b> Current Stock:  </b>  <?= $current_stock ?> <br>
                                                           <b> Transfer Stock:  </b>  <?= $transfer_stock ?>
                                                    </div>
                                                    <div class="col-md-3">
                                                           <b> Name:  </b>  <?= $name ?> <br>
                                                           <b> Address:  </b>  <?= $address ?>
                                                    </div>
                                               </div>
                                           </div>
                                           
                                           
                                       </div>
                                    
                                    </div>
                                    
                                        <span class="alertmsg"> </span>
                                        <style>
                                        @keyframes blink {
                                          0%, 100% { background-color: transparent; }
                                          50% { background-color: green; }
                                        }
                                        
                                        .blink-green {
                                          animation: blink 1s 3; /* 3 times */
                                        }
                                    </style>
                                        <div class="dataTables_scroll qa-grid-wrapper">
                                        <!-- Table -->
                                        <table id="xxlistTable" class="dataTable actionbtn qadetail-grid" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Sl.</th>
                                                    <th>Barcode</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                        							<th>Last inspection date</th>
                                                    <!--th>Delivery Date</th-->
                                                    <th>QA Status by Warehouse</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                        
                                        <?php
                                            $sl = 0;
                                            $qryQa = "SELECT i.barcode,i.name productnm, b.title brandnm, cat.name catnm, q.quantity, q.date_iniciated, q.id, i.image, q.remarks, i.parts FROM `qa` q LEFT JOIN item i ON q.product_id=i.id 
                                                        LEFT JOIN `brand` b ON i.brand=b.id LEFT JOIN `itmCat` cat ON cat.id = i.catagory 
                                                        WHERE q.product_id = $product and q.order_id = '".$orderId."' and q.type = ".$type;
                                            $resultQa = $conn->query($qryQa);
                                            while ($rowQa = $resultQa->fetch_assoc()) {
                                                $sl +=1;
                                                $qaId = $rowQa["id"];
                                                $barcode = $rowQa["barcode"];
                                                $productnm = $rowQa["productnm"];
                                                $brandnm = $rowQa["brandnm"];
                                                $catnm = $rowQa["catnm"];
                                                $qaQty = $rowQa["quantity"];
                                                $image = $rowQa["image"];
                                                $dateIni = $rowQa["date_iniciated"];
                                                $remakrs = $rowQa["remarks"];
                                                $parts = $rowQa["parts"];
                                                
                                        ?>
                                                    <td><?= $sl ?></td>
                                                    <td><?= $barcode ?></td>
                                                    <td><!-- description -->
                                                        <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td class="qathmb">
                                                                    <img src="/assets/images/products/300_300/<?= $image ?>" height="100">
                                                                </td>
                                                                <td class="text-wrap">
                                                                    <div class="qaitemdesc">
                                                                        <strong>Item</strong> : <?= $productnm ?><br>
                                                                        <strong>Category:</strong> <?= $catnm ?> <br>
                                                                        <strong>Brand:</strong> <?= $brandnm ?> <br>
                                                                        <strong>Number of Parts:</strong> <?= $parts ?> <br>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="text-center"><?= $qaQty ?></td>
                                                    <td class="text-center"><?= $dateIni ?></td>
                                                    <!--td class="text-center"></td-->
                                                    <td><!-- QA Status by Warehouse -->
                                                        <table width="100%" class="qsw-tbl"  cellpadding="5" cellspacing="5">
                                                            <tr>
                                                        <?php if($type ==1) { ?>
                                                                <td class="qsw-1 qsw-head">Warehouse</td>
                                                        <?php } ?>
                                                                <td class="qsw-2 qsw-head">Qty</td>
                                                                <td class="qsw-3 qsw-head qsw-zerodefect">Passed</td>
                                                                <td class="qsw-4 qsw-head qsw-defect">Repairable</td>
                                                                <td class="qsw-5 qsw-head qsw-damaged">Damaged</td>
                                                                <td class="qsw-6 qsw-head qsw-pending">Pending</td>
                                                                <td class="qsw-7 qsw-head">Action</td>
                                                            </tr>
                                                    <?php 
                                                        $qryQaw = "SELECT b.name warehouse, qaw.ordered_qty, qaw.pass_qty, qaw.defect_qty, qaw.damaged_qty, qaw.id FROM `qa_warehouse` qaw LEFT JOIN `branch` b ON b.id=qaw.warehouse_id
                                                                    WHERE qaw.qa_id = ".$qaId;
                                                        //echo $qryQaw;die;            
                                                        $resultQaw = $conn->query($qryQaw);
                                                        while ($rowQaw = $resultQaw->fetch_assoc()) {
                                                            $qawId = $rowQaw["id"];
                                                            $warehouse = $rowQaw["warehouse"];
                                                            $ordered_qty = $rowQaw["ordered_qty"];
                                                            $pass_qty = $rowQaw["pass_qty"]; if($pass_qty == null) $pass_qty = 0;
                                                            $defect_qty = $rowQaw["defect_qty"]; if($defect_qty == null) $defect_qty = 0;
                                                            $damaged_qty = $rowQaw["damaged_qty"]; if($damaged_qty == null) $damaged_qty = 0;
                                                            $pending_qty = $ordered_qty - ($pass_qty + $defect_qty + $damaged_qty);
                                                    ?>
                                                                <tr class ="tablerow qawid_<?= $qawId ?>">
                                                                <?php if($type == 1) { ?>
                                                                    <th class="text-right"><?= $warehouse ?>  </th>
                                                                <?php } ?>
                                                                    <td class="qswr-qty"><?= $ordered_qty ?></td>
                                                                    <td class="qswr-0defect"><?= $pass_qty ?></td>
                                                                    <td class="qswr-defect"><?= $defect_qty ?></td>
                                                                    <td class="qswr-damaged"><?= $damaged_qty ?></td>
                                                                    <td class="qswr-pending"><?= $pending_qty ?></td>
                                                                    <td class="qswr-action"><a href="./phpajax/qa_form.php?qaw=<?= $qawId ?>&type=<?= $type ?>" data-qawid= "<?= $qawId ?>" data-item = "<?= $productnm ?>" data-warehouse = "<?= $warehouse ?>" data-barcode = "<?= $barcode ?>" class="btn btn-info btn-xs show-qa-form">Start</a></td>
                                                                </tr>
                                                    <?php 
                                                        }
                                                    ?>
                                                            </table>
                                                        </td>
                                                    <td>
                                                        <p class="qsw-remark text-wrap" >
                                                            <?= $remakrs ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                        <?php   }    ?>
                                                
                                            </tbody>
                                        </table>
                                    </div> 
                    <br>
                    <br>
                    <br>
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
    
<!-- Datatable JS -->       
<script src="js/plugins/datagrid/datatables.min.js"></script>

<script>
$(document).ready(function(){
	
//show INVOICE
	
	$(".dataTable").on("click",".show-qa-form",function(){
		
  	//mylink = $(this).attr('href')+"?socode="+$(this).data('socode')+"&qtype=quotation";
    mylink = $(this).attr('href');
	
   //alert(mylink);
   
   
	// Get the values of data attributes
    var productnm = $(this).data("item");
    var warehouse = $(this).data("warehouse");
    var barcode = $(this).data("barcode");
    var  qawid = $(this).data("qawid");
  
  
    
  
  
  
  		BootstrapDialog.show({
							
							title: 'QA: Item : '+productnm+'   |  Barcode: '+barcode+' | Target Warehouse: '+warehouse,
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: false, // <-- Default value is false
							cssClass: 'show-qaform',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Cancel',
								action: function(dialog) {
									dialog.close();	
									/*
									$("#printableArea2").printThis({
										importCSS: true, 
										importStyle: true,
									});
									*/
									
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: 'Submit',
								action: function(dialog) {
									
                                    
									//$("#form-org").submit();
									
									
									// Remove blinking class from any previously clicked rows
                                    $(".tablerow").removeClass("blink-green");
                                    
                                    // Get the parent <tr> element of the clicked "Start" button
                                    var root = $(".qawid_"+qawid);
                                    var pass_qtys = $("#pass_qtys").val();
                                    var defacts_qtys = $("#defacts_qtys").val();
                                    var damaged_qtys = $("#damaged_qtys").val();
                                    var tots = parseInt(pass_qtys) + parseInt(defacts_qtys) + parseInt(damaged_qtys);
                                    
                                   // var qtys = root.find(".qswr-qty").html() || 1;
                                    
                                    var qtys = $('.qswr-qty').map(function() { return $(this).text(); }).get();
                                    //alert(qtys);
                                    
                                    root.find('.qswr-0defect').html(pass_qtys);
                                    root.find('.qswr-defect').html(defacts_qtys);
                                    root.find('.qswr-damaged').html(damaged_qtys);
                                    pendingval = parseInt(qtys) - parseInt(tots);
                                    pending = (pendingval<1)?0:pendingval;
                                    //alert(pending);
                                    root.find('.qswr-pending').html(pending);
          
                                    // Add the .blink-green class to make the row blink three times
                                    root.addClass("blink-green");
                                    
                                    var formData = $("#form-org").serialize();
                                    //alert(formData);
                                    
                                    submitQAData(formData);
                                    
                                    // Remove the blinking class after 3 seconds (3 times of 1s animation duration)
                                    setTimeout(function() {
                                      root.removeClass("blink-green");
                                    }, 3000);
                                    
                                    
                                    dialog.close();	
									
									},
								
							}],
							onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});		
		
	
});
</script>
<script>
    //$(document).on('submit', '#form-org', function(event) {
    function submitQAData(formData){
        
   
                    event.preventDefault(); // Prevent default form submission
                    
                    // Serialize the form data to send via AJAX
                    //var formData = $(this).serialize();

                    // Send the AJAX call with the form data
                    $.ajax({
                        type: "POST",
                        url: "phpajax/qa_issue_submit.php", // Replace with the URL to your PHP script
                        data: formData,
                        success: function(response) {
                            // Handle the response from the server if needed
                            console.log(response);
                            //alert(response);
                            messageAlert(response);
                        },
                        error: function(xhr, status, error) {
                            // Handle errors if necessary
                            console.error(error);
                            //alert(error);
                            messageAlert(error);
                        }
                    });

                    // Close the dialog after submitting
    }
                
</script>
    </body>
</html>
<?php }?>    
