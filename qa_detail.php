<?php
require "common/conn.php";


session_start();

//ini_set('display_errors',1);
$usr=$_SESSION["user"];

//echo $usr;die;

$res= $_GET['res'];
$msg= $_GET['msg'];

$orderId = $_GET["id"];

$qryInfo="SELECT org.name, so.orderdate, org.orgcode, org.contactno, so.remarks,MAX(DATE_FORMAT(quow.expted_deliverey_date, '%d/%m/%Y')) expdt 
            FROM `qa` q LEFT JOIN `soitem` so ON q.order_id=so.socode LEFT JOIN quotation_warehouse quow ON q.order_id=quow.socode
            LEFT JOIN `organization` org ON org.id=so.organization 
            WHERE q.order_id = '".$orderId."'";
$resultInfo = $conn->query($qryInfo);
while ($rowinfo = $resultInfo->fetch_assoc()) {
    $customerName = $rowinfo["name"];
    $customerId = $rowinfo["orgcode"];
    $orderDate = $rowinfo["orderdate"];
    $customerContact = $rowinfo["contactno"];
    $deliveryAddress = $rowinfo["remarks"];
    $expDeliverydt = $rowinfo["expdt"];
}





if($usr=='')
{ 	header("Location: ".$hostpath."/hr.php");
}
else
{
	

    $currSection = 'qaresult';
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
    
    
 
                	<form method="post" action="quotationList.php?mod=2" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                          
                          
                         
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>QA Test <i class="fa fa-angle-right"></i> Sold Items <i class="fa fa-angle-right"></i> Order ID: <?= $orderId ?> </h6>
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

                                <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                            </div>
                        
                        </div>
                        
                        
                      </div>
                    </div>
                    
    
    				</form>
                        
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
                               <div class="col-md-6">
                                       <b>ORDER ID: </b> <?= $orderId ?> <br>
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
                                        <b>EXPECTED DELIVERY DATE:  <?= $expDeliverydt ?>  </b>   
                                </div>
                                <div class="col-md-6">
                                        <b>DELIVERY ADDRESS:</b><br>
                                        <?= $deliveryAddress ?>
                                </div>
                           </div>
                           
                           
                       </div>

                       
                   </div>
                   
                </div>
                        
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
                            <td>Description</th>
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
                        $qryQa = "SELECT i.barcode,i.name productnm, b.title brandnm, cat.name catnm, q.quantity, q.date_iniciated, q.id, i.image FROM `qa` q LEFT JOIN item i ON q.product_id=i.id 
                                    LEFT JOIN `brand` b ON i.brand=b.id LEFT JOIN `itmCat` cat ON cat.id = i.catagory WHERE q.order_id = '".$orderId."'";
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
                                            <td class="qsw-1 qsw-head">Warehouse</td>
                                            <td class="qsw-2 qsw-head">Qty</td>
                                            <td class="qsw-3 qsw-head qsw-zerodefect">Passed</td>
                                            <td class="qsw-4 qsw-head qsw-defect">Defect</td>
                                            <td class="qsw-5 qsw-head qsw-damaged">Damaged</td>
                                            <td class="qsw-6 qsw-head qsw-pending">Pending</td>
                                            
                                        </tr>
                                <?php 
                                    $qryQaw = "SELECT b.name warehouse, qaw.ordered_qty, qaw.pass_qty, qaw.defect_qty, qaw.damaged_qty, qaw.id FROM `qa_warehouse` qaw LEFT JOIN `branch` b ON b.id=qaw.warehouse_id
                                                WHERE qaw.qa_id = ".$qaId;
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
                                        <tr class ="tablerow">
                                            <th class="text-right"><?= $warehouse ?>  </th>
                                            <td class="qswr-qty"><?= $ordered_qty ?></td>
                                            <td class="qswr-0defect"><?= $pass_qty ?></td>
                                            <td class="qswr-defect"><?= $defect_qty ?></td>
                                            <td class="qswr-damaged"><?= $damaged_qty ?></td>
                                            <td class="qswr-pending"><?= $pending_qty ?></td>
                                            
                                        </tr>
                                <?php 
                                    }
                                ?>
                                        
                                    </table>
                                </td>
                                <td>
                                    <p class="qsw-remark text-wrap" >
                                        
                                    </p>
                                </td>
                                
                            </tr>
                    <?php 
                    
                    }
                    ?>
                            
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
		
       
    
    </body></html>
  <?php }?>    
