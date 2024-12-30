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

    $currSection= "deliveryreturn";
    
    $qryInfo="SELECT org.name, so.orderdate, org.orgcode, org.contactno, so.remarks, d.scm, d.`packed`, d.`showroom`, d.`barcode`, d.`stock`
                FROM delivery_order d LEFT JOIN return_order r ON r.ro_id=d.order_id LEFT JOIN `soitem` so ON r.order_id=so.socode
                LEFT JOIN `organization` org ON org.id=so.organization WHERE d.do_id = '".$orderId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) 
    {
        $customerName = $rowinfo["name"];
        $customerId = $rowinfo["orgcode"];
        $orderDate = $rowinfo["orderdate"];
        $customerContact = $rowinfo["contactno"];
        $deliveryAddress = $rowinfo["remarks"];
        $scm  = $rowinfo["scm"];
        
        $packed  = $rowinfo["packed"];
        $showroom  = $rowinfo["showroom"];
        $barcode  = $rowinfo["barcode"];
        $stock  = $rowinfo["stock"];
    }

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
                                	    <form method="post" action="common/scm_ro_submit.php" id="form1">
                                            <div class="well list-top-controls"> 
                                                <div class="row border">
                                                   <div class="col-sm-3 text-nowrap">
                                                        <h6>Quality<i class="fa fa-angle-right"></i> Return Order <i class="fa fa-angle-right"></i> ID: <?= $orderId ?> </h6>
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
                        				
                                        <div class="well table-header">
                
                                            <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                   <div class="col-md-6">
                                                           <b>Delivery ID: </b> <?= $orderId ?> <br>
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
                                                    </div>
                                                    <div class="col-md-6">
                                                            <b>DELIVERY ADDRESS:</b><br>
                                                            <?= $deliveryAddress ?>
                                                    </div>
                                               </div>
                                            </div>
                                       </div>
                                            <br><br>
                                            <div class= "row">
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor">PRODUCT PACKED AT THE CLIENT’S HOUSE</label>
                                                        <div>
                                                            <input type="checkbox" name="packed" id="packed" style="width:20px;float:left;text-align:left" value="1" class="form-control" <?php if($scm == 1) echo "disabled" ?> <?php if ($packed == 1) { ?> Checked <?php }?> >
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor">TANEN TO SHOWROOM WAREHOUSE</label>
                                                        <div>
                                                            <input type="checkbox" name="showroom" id="showroom" style="width:20px;float:left;text-align:left" value="1" class="form-control" <?php if($scm == 1) echo "disabled" ?> <?php if ($showroom == 1) { ?> Checked <?php }?> >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor">PASTED BARCODE</label>
                                                        <div>
                                                            <input type="checkbox" name="barcode" id="barcode" style="width:20px;float:left;text-align:left" value="1" class="form-control" <?php if($scm == 1) echo "disabled" ?> <?php if ($barcode == 1) { ?> Checked <?php }?> >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor">PLACED IN GENRAL STOCK</label>
                                                        <div>
                                                            <input type="checkbox" name="stock" id="stock" style="width:20px;float:left;text-align:left" value="1" class="form-control" <?php if($scm == 1) echo "disabled" ?> <?php if ($stock == 1) { ?> Checked <?php }?> >
                                                        </div>
                                                    </div>
                                                </div>
                                                <p>&nbsp;</p>
                                                <input type="hidden" name ="roid" value="<?= $orderId ?>">
                                                <div class="col-sm-12">
                                                    <?php if($scm != 1){ ?>
                                                        <input class="btn btn-lg btn-default " type="submit" name="add" value="Add"  id="submit" >
                                                    <?php } ?>
                                                    <a href = "./scm_return_order.php?pg=1&mod=16">
                                                        <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                                                    </a>
                                                </div>
                                            </div>
                                        
                                        </div>
                                        </form>
                                    
                                        
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

    </body>
</html>
<?php }?>    
