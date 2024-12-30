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
    $id= $_GET['id'];
    $serno= $_GET['id'];
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
    $qry="SELECT `id`, `poid`, `supid`, `orderdt`, `currency`, `tot_amount`, `invoice_amount`, `vat`, `tax`, `delivery_dt`, `hrid` FROM `po`  where  id= ".$id; 
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
                            $uid=$row["id"];$poid=$row["poid"]; $supid=$row["supid"]; $orderdt=date("Y-m-d", strtotime($row["orderdt"]));  $currency=$row["currency"];
                            $tot_amount=$row["tot_amount"]; $invoice_amount=$row["invoice_amount"];$vat=$row["vat"]; $tax=$row["tax"]; $delivery_dt=date("Y-m-d", strtotime($row["delivery_dt"]));
                           $hrid='1';
                        }
                }
            }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    else
    {
                            $uid='';$poid=''; $supid=''; $orderdt=date("Y-m-d");  $currency='0';
                            $tot_amount='0'; $invoice_amount='0'; $vat='0';$tax='0'; $delivery_dt=date("Y-m-d");$hrid='1';
                            
    $mode=1;//Insert mode
                        
    }
    
    $currSection = 'po';
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
            <span>Purchase Order</span>
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
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
                       <form method="post" action="common/addpo.php" id="form1" enctype="multipart/form-data">  
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">
      		            <div class="panel-heading"><h1>Add New PO</h1></div>
			            <div class="panel-body">
                            <span class="alertmsg"></span>
                            <br>
                          	<p>(Field Marked * are required) </p>
     	                   
                                <div class="row">
                            		<div class="col-sm-12">
	                                    <h4>PO Information</h4>
		                                <hr class="form-hr">
		                                 <input type="hidden"  name="pid" id="pid" value="<?php echo $uid;?>"> 
    	                            </div> 
	                                
	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="po_id">PO ID*</label>
                                            <input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $poid;?>" required>
                                        </div>        
                                    </div>
      
      	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsupnm">Supplier Name*</label>
                                            <div class="form-group styled-select">
                                            
                                            <select name="cmbsupnm" id="cmbsupnm" class="form-control" >
                                            <option value="">Select Supplier Name</option>
    <?php 
    $qry1="SELECT `id`, `name`  FROM `vedor` order by name";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
              $tid= $row1["id"];  $nm=$row1["name"];
    ?>          
                                                <option value="<?php echo $tid; ?>" <?php if ($supid == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php }}?>                    
                                            </select>
                                            </div>
                                        </div>        
                                    </div>
                            	    
                            	    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="po_dt">Order Date*</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="<?php echo $orderdt;?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>          
        
      	                      <!--      <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Currency</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbcur" id="cmbcur" class="form-control">
                                            <option value="">Select Currency</option>
    <?php  $qrycur="SELECT `id`, `name`, `shnm` FROM `currency`  order by name"; $resultcur = $conn->query($qrycur); if ($resultcur->num_rows > 0){while($rowcur = $resultcur->fetch_assoc()) 
          { 
              $cid= $rowcur["id"]; $cnm=$rowcur["shnm"];
    ?>          
                                                <option value="<?php echo $cid; ?>" <?php if ($currency == $cid) { echo "selected"; } ?>><?php echo $cnm; ?></option>
    <?php  }} ?>
                                            </select>
                                            </div>
                                        </div>        
                                    </div>-->
                                    <br>
                                    <div class="po-product-wrapper"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Product Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if($mode==1){?> 	                                        
	                                        <div class="toClone">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                        <select name="itemName[]" i d="itemName" class="item-name form-control">
                                                        <option value="">Select Product</option>
    <?php $qryitm="SELECT `id`, `name` FROM `product`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                            <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                        </select> 
                                                        </div>
                                                    </div>        
                                                </div>
          	                                  <!--  <div class="col-lg-2 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                      <select name="measureUnit[]" id="measureUnit" class="measure-unit form-control">
                                                      <option value="">Select Unit</option>
 <?php $qrymu="SELECT `id`, `name`  FROM `mu`  order by name"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["id"];  $mnm=$rowmu["name"];
    ?>                                                          
                                                        <option value="<?php echo $mid; ?>" <?php if ($itmmnm == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
     <?php  }}?>                                                       
                                                      </select>
                                                      </div>
                                                  </div>        
                                                </div> -->
                                                
                                                

                                                
                                                
                                                
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                <div class="row qtnrows">
                                                    <div class="col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control quantity" id="quantity" placeholder="Quantity" name="quantity[]">
                                                      </div>
                                                  </div>
                                                    <div class="col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitPrice" id="unitpriceotc" placeholder="OTC" name="unitpriceotc[]">
                                                      </div>
                                                  </div>
                                                  </div>
                                              </div>







         	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                <div class="row qtnrows">
                                                    <div class="col-sm-6 col-xs-6">
                                                        <div class="form-group">
                                                        <input type="text" class="form-control unitPrice2" id="unitpricemrc" placeholder="MRC" name="unitpricemrc[]">
                                                      </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" name="unittotal[]">
                                                      </div>
                                                  </div>
                                                  </div>
                                              </div>
                                                                                      
      	                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  placeholder="Description" name="description[]">
                                                    </div>        
                                                </div>        
                                            </div>
<?php } else {?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                       <div class="form-group styled-select">
                                                       <select name="itemName[]" id="itemName" class="form-control">
                                                       <option value="">Select Item</option>
    <?php $qry1="SELECT `id`, `name` FROM `item`  product by name"; $result1 = $conn->query($qry1); if ($result1->num_rows > 0) {while($row1 = $result1->fetch_assoc()) 
              { 
                  $tid= $row1["id"];  $nm=$row1["name"];
    ?>
                                                            <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                        </select> 
                                                        </div>
                                                  </div>        
                                                </div>
                                              <!--  <div class="col-lg-2 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                      <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                      <option value="">Select Unit</option>
   <?php $qrymu="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["id"];  $mnm=$rowmu["name"];
    ?>                                                          
                                                        <option value="<?php echo $mid; ?>" <?php if ($itmmnm == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
     <?php  }}?> 
                                                      </select>
                                                      </div>
                                                  </div>        
                                                </div> -->
                                                
                                                
                                                
                                                
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                <div class="row qtnrows">
                                                    <div class="col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control quantity" id="quantity" placeholder="Quantity" name="quantity[]">
                                                      </div>
                                                  </div>
                                                    <div class="col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitPrice" id="unitpriceotc" placeholder="OTC" name="unitpriceotc[]">
                                                      </div>
                                                  </div>
                                                  </div>
                                              </div>

         	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                <div class="row qtnrows">
                                                    <div class="col-sm-6 col-xs-6">
                                                        <div class="form-group">
                                                        <input type="text" class="form-control unitPrice" id="unitpricemrc" placeholder="MRC" name="unitpricemrc[]">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" name="unittotal[]">
                                                      </div>
                                                  </div>
                                                  </div>
                                              </div> 
                                                
                                                
                                                
                                                <div class="col-lg-3 col-md-12 col-sm-12">
                                                  <div class="form-group">
                                                    <input type="text" class="form-control" id="description" placeholder="Description" name="description[]">
                                                  </div>        
                                                </div>  
                                                      
                                    	 		<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                    		</div>
<?php } ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        
<div class="well no-padding top-bottom-border grandTotalWrapper">

    <div class="row total-row">

        <div class="col-xs-offset-6 col-xs-6 col-sm-offset-8 col-sm-4  col-md-offset-8 col-md-4 col-lg-offset-8 col-lg-1">

        	<div class="form-group grandTotalWrapper">

                <label>Total:</label>

                <input type="text" class="form-control" id="grandTotal" disabled>

            </div>

            

        	</div>

    	</div>

    

	</div>                                        
                                        
                                        
                                    </div>      
                                    &nbsp;
                                    <div class="col-sm-12">
        	                            <a href="#" class="link-add-po" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
                                    <br><br>&nbsp;<br><br>
        
	                                <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="email">Delivery Date</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="delivery_dt" name="delivery_dt" value="<?php echo $delivery_dt;?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>        
        
	                                <div class="col-lg-3 col-md-6 col-sm-12">
                                        <strong>Attach vouchers</strong>
                                        <div class="input-group">
                                            <label class="input-group-btn">
                                                <span class="btn btn-primary btn-file btn-file">
                                                    <i class="fa fa-upload"></i> <input type="file" name="attachment1[]" id="attachment1" style="display: none;" multiple>
                                                </span>
                                            </label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                        <span class="help-block form-text text-muted"> Try selecting one or more files and watch the feedback </span>
                                    </div>
        
	                               
                                    
                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbhr" >Purchase By</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbhr" id="cmbhr" class="form-control">
                                                <option value="">Select Purchase By</option>
<?php $qryhr="SELECT `id`, `emp_id` FROM `hr`  order by emp_id"; $resulthr = $conn->query($qryhr); if ($resulthr->num_rows > 0) {while($rowhr = $resulthr->fetch_assoc()) 
      { 
          $hrid= $rowhr["id"];  $hrnm=$rowhr["emp_id"];
?>                                                          
                                                    <option value="<?php echo $hrid; ?>" <?php if ($deliveryby == $hrid) { echo "selected"; } ?>><?php echo $hrnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>                               
                                    <br><br>&nbsp;<br><br>&nbsp;<br><br><br>
                                </div>
                           
                        </div>
                    </div> 
        <!-- /#end of panel -->      
                    <div class="button-bar">
                            <?php if($mode==2) { ?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update PO" id="update" >
                          <?php } else {?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add PO" id="add" >
                          <?php } ?>           
                          <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Cancel"  id="cancel" >
                    </div>        
          <!-- START PLACING YOUR CONTENT HERE --> 
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


</body>
</html>
<?php }?>