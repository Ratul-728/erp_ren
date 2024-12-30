<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{
  header("Location: ".$hostpath."/mo.php");
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
    $qry="SELECT `id`, `socode`,`srctype`, `customer`, `orderdate`, `deliverydt`, `deliveryby`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt` FROM `soitem`where  id= ".$id; 
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
                            $uid=$row["id"];$soid=$row["socode"]; $cusid=$row["customer"]; $orderdt=date("Y-m-d", strtotime($row["orderdate"]));  $deliveryby=$row["deliveryby"];
                            $accmgr=$row["accmanager"];
                            $invoice_amount=$row["invoiceamount"];$vat=$row["vat"]; $tax=$row["tax"]; $delivery_dt=date("Y-m-d", strtotime($row["deliverydt"]));
                           $hrid='1';
                        }
                }
            }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    else
    {
                            $uid='';$soid=''; $cusid=''; $orderdt=date("Y-m-d");  $currency='0';$deliveryby='';$accmgr='';
                            $invoice_amount='0'; $vat='0';$tax='0'; $delivery_dt=date("Y-m-d");$hrid='1';
                            
    $mode=1;//Insert mode
                        
    }
    
    $currSection = 'soitem';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
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
            <span>Service Order(Item)</span>
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
                       <form method="post" action="common/addsoitem.php" id="form1" enctype="multipart/form-data">  
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">
      		            <div class="panel-heading"><h1>Add New SO</h1></div>
			            <div class="panel-body">
                            <span class="alertmsg"></span>
                            <br>
                          	<p>(Field Marked * are required) </p>
     	                   
                                <div class="row">
                            	    <div class="col-sm-12">
	                                    <h4>SO Information</h4>
		                                <hr class="form-hr">
		                                 <input type="hidden"  name="sid" id="soid" value="<?php echo $uid;?>"> 
    	                            </div> 
                                    
                                    
                                    
	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="po_id">SO ID*</label>
                                            <input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $soid;?>" required>
                                        </div>        
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcontype">Contact Type</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbcontype" id="cmbcontype" class="cmb-parent form-control">
                                                	<option value="">Select Type</option>
													<?php $qrycntp="SELECT `id`, `name` FROM `contacttype`"; $resultcntp = $conn->query($qrycntp); if ($resultcntp->num_rows > 0) {while($rowcntp = $resultcntp->fetch_assoc()){
                                                    	$tid= $rowcntp["id"];  $nm=$rowcntp["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($contacttype == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                             </div>
                                          </div>         
                                        </div>
                                        
                                        
                                        
      	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsupnm"> Name*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbsupnm" id="cmbsupnm" class="cmd-child form-control" >
                                            <option value="">Select Name</option>
                   
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
      	                          
                            	    <br>
                                    <div class="po-product-wrapper"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if($mode==1){?> 	                                        
	                                        <div class="toClone">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                        <select name="itemName[]" id="itemName" class="form-control">
                                                        <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`,concat(`code`,'-', `name`) name FROM `item`"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                            <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                        </select> 
                                                        </div>
                                                    </div>        
                                                </div>
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                      <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                      <option value="">Select Unit</option>
 <?php $qrymu="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["id"];  $mnm=$rowmu["name"];
    ?>                                                          
                                                        <option value="<?php echo $mid; ?>"><?php echo $mnm; ?></option>
     <?php  }}?>                                                       
                                                      </select>
                                                      </div>
                                                  </div>        
                                                </div>
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                   
                                                  <div class="row qtnrows">
                                                  <div class="col-sm-6 col-xs-6"> 
                                                    <div class="form-group">
                                                        <input type="text" class="form-control quantity" id="quantity" placeholder="Quantity" name="quantity[]">
                                                    </div> 
                                                    </div>
                                                    
                                                    <div class="col-sm-6 col-xs-6">       

                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitPrice" id="unitpriceotc" placeholder="Unit Price(OTC)" name="unitpriceotc[]">
                                                    </div> 
                                                    </div>
                                                  </div>  
                                                           
                                                </div>
                                                
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unitpricemrc" placeholder="Unit Price(MRC)" name="unitpricemrc[]">
                                                    </div>        
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]">
                                                    </div>        
                                                </div>
                                            </div>
<?php } else {?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                        <select name="itemName[]" id="itemName" class="form-control">
                                                        <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`,concat(`code`,'-', `name`) name FROM `item`"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                            <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                        </select> 
                                                        </div>
                                                    </div>        
                                                </div>
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                      <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                      <option value="">Select Unit</option>
 <?php $qrymu="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["id"];  $mnm=$rowmu["name"];
    ?>                                                          
                                                        <option value="<?php echo $mid; ?>"><?php echo $mnm; ?></option>
     <?php  }}?>                                                       
                                                      </select>
                                                      </div>
                                                  </div>        
                                                </div>
          	                                    
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                
                                                 <div class="row qtnrows">
                                                  <div class="col-sm-6 col-xs-6">  
                                                    <div class="form-group">
                                                        <input type="text" class="form-control quantity" id="quantity" placeholder="Quantity" name="quantity[]">
                                                    </div> 
                                                    
                                                    </div>
                                                    <div class="col-sm-6 col-xs-6">       




                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitPrice" id="unitpriceotc" placeholder="Unit Price(OTC)" name="unitpriceotc[]">
                                                    </div> 
                                                    
                                                    </div>       
                                                
                                                </div>
                                                
                                                </div>
                                                
                                                
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unitpricemrc" placeholder="Unit Price(MRC)" name="unitpricemrc[]">
                                                    </div>        
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]">
                                                    </div>        
                                                </div>
                                            </div>
<?php } ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
<div class="well no-padding top-bottom-border grandTotalWrapper">
    <div class="row total-row">
        <div class="col-xs-offset-6 col-xs-6 col-sm-offset-8 col-sm-4  col-md-offset-8 col-md-4 col-lg-offset-7 col-lg-2">
        	<div class="form-group">
                <label>Total:&nbsp;&nbsp;</label>
                <input type="text" class="form-control" id="grandTotal" disabled>
            </div>
            
        	</div>
    	</div>
    
	</div>    
                                        
                                        
                                    </div>      
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
        	                            <a href="#" class="link-add-po" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
                                    <br><br>&nbsp;<br><br>
        
	                                <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="email">Delivery Date</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" placeholder="Delivery Date" id="delivery_dt" name="delivery_dt" value="<?php echo $delivery_dt;?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbitmcat">Delivery By</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbhr" id="cmbhr" class="form-control">
                                                <option value="">Select Delivery By</option>
<?php $qryhr="SELECT `id`,concat(`emp_id`,'-',`hrName`) `emp_id` FROM `hr`"; $resulthr = $conn->query($qryhr); if ($resulthr->num_rows > 0) {while($rowhr = $resulthr->fetch_assoc()) 
      { 
          $hrid= $rowhr["id"];  $hrnm=$rowhr["emp_id"];
?>                                                          
                                                    <option value="<?php echo $hrid; ?>" <?php if ($deliveryby == $hrid) { echo "selected"; } ?>><?php echo $hrnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbitmcat">Account Manager</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbhrmgr" id="cmbhrmgr" class="form-control">
                                                <option value="">Select Account Manager</option>
<?php $qryhrm="SELECT `id`, concat(`emp_id`,'-',`hrName`) `emp_id` FROM `hr`"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
      { 
          $hridm= $rowhrm["id"];  $hrnmm=$rowhrm["emp_id"];
?>                                                          
                                                    <option value="<?php echo $hridm; ?>" <?php if ($accmgr == $hridm) { echo "selected"; } ?>><?php echo $hrnmm; ?></option>
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
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update SO" id="update" >
                          <?php } else {?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add SO" id="add" >
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
//$cusid = 3;
?>

<script language="javascript">


$(document).on("change", ".cmb-parent", function() {
	
	//alert($(this).children("option:selected").val());
	//var root = $(this).parent().parent().parent().parent();	// root means .toClone
	var selectedValue = $(this).children("option:selected").val();
	
	 $.ajax({
            type: "POST",
            url: "cmb/so_item_customer_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
			beforeSend: function(){
					$(".cmd-child").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
            //root.find(".measure-unit").html(data);
			
			$(".cmd-child").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child").append(data);
			
			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });	
	
});	
	

</script>

</body>
</html>
<?php }?>