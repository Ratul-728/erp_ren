<?php
//print_r($_REQUEST);
//exit();
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{
  header("Location: ".$hostpath."/hr.php");
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
    $qry="SELECT `id`, `socode`,`customertp`,`organization`,`srctype`, `customer`,DATE_FORMAT(`orderdate`,'%e/%c/%Y') `orderdate`,DATE_FORMAT(`deliverydt`,'%e/%c/%Y') `deliverydt`, `deliveryby`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`,DATE_FORMAT(`terminationDate`,'%e/%c/%Y') `terminationDate` ,terminationcause,`status`,DATE_FORMAT(`effectivedate`,'%e/%c/%Y') `effectivedate`,`remarks`,`poc`,`oldsocode` FROM `soitem`where  id= ".$id; 
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
                            $uid=$row["id"];$soid=$row["socode"]; $cusype=$row["customertp"];$org=$row["organization"];  $srctype=$row["srctype"];$cusid=$row["customer"]; $orderdt=$row["orderdate"];  $deliveryby=$row["deliveryby"];
                            $accmgr=$row["accmanager"];
                            $invoice_amount=$row["invoiceamount"];$vat=$row["vat"]; $tax=$row["tax"]; $delivery_dt=$row["deliverydt"]; $term_dt=$row["terminationDate"];$terminationcause=$row["terminationcause"];
                            $effectivedate=$row["effectivedate"];  $hrid='1'; $st=$row["status"]; $details=$row["remarks"]; $poc=$row["poc"];$oldsocode=$row["oldsocode"];
                        }
                }
            }
    $mode=2;//update mode
   // echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    else if ($res==5)
    {
    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
    $qry="SELECT `id`, `socode`,`customertp`,`organization`,`srctype`, `customer`,DATE_FORMAT(`orderdate`,'%e/%c/%Y') `orderdate`,DATE_FORMAT(`deliverydt`,'%e/%c/%Y') `deliverydt`, `deliveryby`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`,DATE_FORMAT(`terminationDate`,'%e/%c/%Y') `terminationDate` ,terminationcause,`status`,DATE_FORMAT(`effectivedate`,'%e/%c/%Y') `effectivedate`,`remarks`,`poc`,`oldsocode` FROM `soitem`where  id= ".$id; 
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
                            $uid='';$soid=''; $cusype=$row["customertp"];$org=$row["organization"];  $srctype=$row["srctype"];$cusid=$row["customer"]; $orderdt=$row["orderdate"];  $deliveryby=$row["deliveryby"];
                            $accmgr=$row["accmanager"];
                            $invoice_amount=$row["invoiceamount"];$vat=$row["vat"]; $tax=$row["tax"]; $delivery_dt=$row["deliverydt"]; $term_dt=$row["terminationDate"];$terminationcause=$row["terminationcause"];
                            $effectivedate=$row["effectivedate"];  $hrid='1'; $st=$row["status"]; $details=$row["remarks"]; $poc=$row["poc"];$oldsocode=$row["oldsocode"];
                        }
                }
            }
    $mode=5;//copy mode
   // echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    else
    {
                            $uid='';$soid='';$cusype=2;$srctype=''; $cusid=''; $orderdt='';  $currency='';$deliveryby='';$accmgr='';
                            $invoice_amount='0'; $vat='0';$tax='0'; $delivery_dt='';$hrid='';$term_dt='';$terminationcause='';$st='';$effect_dt='';$details='';$poc='';//$term_dt=date("Y-m-d")
                            
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
<body class="form soitem">
    
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
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->  
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
		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno;?>"> 
		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
    	                            </div> 
                                    
	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="po_id">SO ID*</label>
                                            <input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $soid;?>" required>
                                        </div>        
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcontype">Customer Type*</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbcontype" id="cmbcontype" class="cmb -parent form-control" required>
                                                	<option value="">Select Type</option>
													<?php $qrycntp="SELECT `id`, `name` FROM `customertype`  order by name"; $resultcntp = $conn->query($qrycntp); if ($resultcntp->num_rows > 0) {while($rowcntp = $resultcntp->fetch_assoc()){
                                                    	$tid= $rowcntp["id"];  $nm=$rowcntp["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($cusype == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
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
                                                <label for="cmborg">Organization</label>
                                                <div class="form-group styled-select">
                                                <select name="cmborg" id="cmborg" class="cmb-parent form-control">
                                                	<option value="">Select Type</option>
													<?php $qryorg="SELECT distinct o.`id`,o.`name` FROM `contact` c,`organization` o where c.`organization`=o.`orgcode`  and c.`contacttype`=1  order by o.name"; $resultorg = $conn->query($qryorg); if ($resultorg->num_rows > 0) {while($roworg = $resultorg->fetch_assoc()){
                                                    	$tid= $roworg["id"];  $nm=$roworg["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($org == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
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
                                            <label for="cmbsupnm">Contact Name*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbsupnm" id="cmbsupnm" class="cmd-child form-control" required>
                                            <option value="">Select Name</option>
                                                    <?php $qrycont="SELECT `id`, `name`  FROM `contact`  WHERE `contacttype`=1  order by name"; $resultcont = $conn->query($qrycont); if ($resultcont->num_rows > 0) {while($rowcont = $resultcont->fetch_assoc()){
                                                    	$tid= $rowcont["id"];  $nm=$rowcont["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($cusid == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>     
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
                                  <!--  <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbitmcat">Account Manager*</label>
                                            <div class="form-group styled-select">
                                                <select name="cmbhrmgr" id="cmbhrmgr" class="form-control" required>
                                                <option value="">Select Account Manager</option>
    <?php $qryhrm="SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id` FROM `hr` h,`employee` e where h.`emp_id`=e.`employeecode`  order by emp_id"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
      { 
          $hridm= $rowhrm["id"];  $hrnmm=$rowhrm["emp_id"];
    ?>                                                          
                                                    <option value="<?php echo $hridm; ?>" <?php if ($accmgr == $hridm) { echo "selected"; } ?>><?php echo $hrnmm; ?></option>
    <?php  }}?>                                                       
                                                  </select>
                                              </div>
                                        </div>        
                                    </div> -->
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsostat">SAN* </label>
                                            <div class="form-group styled-select">
                                            <select name="cmbsostat" id="cmbsostat" class="form-control" required>
                                            <option value="">Select SAN</option>
<?php $qryst="SELECT `id`, `name` FROM `sostatus`  order by name"; $resultst = $conn->query($qryst); if ($resultst->num_rows > 0) {while($rowst = $resultst->fetch_assoc()) 
  { 
      $idst= $rowst["id"];  $nmst=$rowst["name"];
?>                                                          
                                                <option value="<?php echo $idst; ?>" <?php if ($st == $idst) { echo "selected"; } ?>><?php echo $nmst; ?></option>
<?php  }}?>                                                       
                                              </select>
                                              </div>
                                        </div>        
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="effect_dt">Effective Date*</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="effect_dt" name="effect_dt" value="<?php echo $effectivedate;?>" required>
                                            <div class="input-group-addon">
                                             <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbpoc">POC</label>
                                            <div class="form-group styled-select">
                                                <select name="cmbpoc" id="cmbpoc" class="cmd-child1 form-control" >
                                                <option value="">Select POC </option> 
    <?php $qryhrm="SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id` FROM `hr` h,`employee` e where h.`emp_id`=e.`employeecode`  order by emp_id"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
      { 
          $hridm= $rowhrm["id"];  $hrnmm=$rowhrm["emp_id"];
    ?>                                                          
                                                    <option value="<?php echo $hridm; ?>" <?php if ($poc == $hridm) { echo "selected"; } ?>><?php echo $hrnmm; ?></option>
    <?php  }}?>                                                       
                                                  </select>
                                              </div>
                                        </div>        
                                    </div> 
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="term_dt">Termination Date</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="term_dt" name="term_dt" value="<?php echo $term_dt;?>" >
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbtermc">Termination Reason </label>
                                            <div class="form-group styled-select">
                                            <select name="cmbtermc" id="cmbtermc" class="form-control" >
                                            <option value="">Select Reason</option>
<?php $qrytc="SELECT `id`, `name` FROM `terminationcause`  order by name"; $resulttc = $conn->query($qrytc); if ($resulttc->num_rows > 0) {while($rowtc = $resulttc->fetch_assoc()) 
  { 
      $idtc= $rowtc["id"];  $nmtc=$rowtc["name"];
?>                                                          
                                                <option value="<?php echo $idtc; ?>" <?php if ($terminationcause == $idtc) { echo "selected"; } ?>><?php echo $nmtc; ?></option>
<?php  }}?>                                                       
                                              </select>
                                              </div>
                                        </div>        
                                    </div>
      	                             <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="oldso_id">OLD SO ID</label>
                                            <input type="text" class="form-control" name="oldso_id" id="oldso_id" value="<?php echo $oldsocode;?>" >
                                        </div>        
                                    </div>
      	                          
                            	    <br>
                                    <div class="po-product-wrapper"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if($mode==1||$mode==5){?> 	                                        
	                                        <div class="toClone">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`  FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for itemName-->  
          	                                    <div class="col-lg-1 col-md-6 col-sm-6">
                                                <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                      <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                      <option value="">Select Unit</option>
 <?php $qryunit="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultunit = $conn->query($qryunit); if ($resultunit->num_rows > 0) {while($rowunit = $resultunit->fetch_assoc()) 
              { 
                  $unitid= $rowunit["id"];  $unitnm=$rowunit["name"];
    ?>                                                          
                                                        <option value="<?php echo $unitid; ?>"><?php echo $unitnm; ?></option>
     <?php  }}?>                                         <!-- <option data-value="<?php echo $unitid; ?>" value="<?php echo $unitnm; ?>"><?php echo $unitnm; ?></option>  -->            
                                                      </select>
                                                      </div>
                                                  </div>        
                                                </div> <!-- this block is for measureUnit--> 
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" placeholder="Quantity" name="quantity_otc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" placeholder="OTC" name="unitprice_otc[]">
                                                                <input type="hidden" name="unitTotalAmount_otc" class="unitTotalAmount_otc">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc-->
     	                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_mrc " id="quantity_mrc" placeholder="Quantity" name="quantity_mrc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_mrc unitPriceV2" id="unitprice_mrc" placeholder="MRC" name="unitprice_mrc[]">
                                                                <input type="hidden" name="unitTotalAmount_mrc" class="unitTotalAmount_mrc">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_mrc, unitprice_mrc-->
                                                <div class="col-lg-1 col-md-3 col-sm-3  col-xs-6">
                                                    <div class="form-group">
                                                          <div class="form-group styled-select">
                                                          <select name="curr[]" id="curr" class="form-control">
                                                          <option value="">Select Currency</option>
     <?php  $qrycur="SELECT `id`, `name`, `shnm` FROM `currency`  order by name"; $resultcur = $conn->query($qrycur); if ($resultcur->num_rows > 0){while($rowcur = $resultcur->fetch_assoc()) 
              { 
                  $crid= $rowcur["id"]; $crnm=$rowcur["shnm"];
        ?>          
                                                     <option value="<?php echo $crid; ?>" <?php if (1 == $crid) { echo "selected"; } ?>><?php echo $crnm; ?></option>
        <?php  }} ?>
                                                          </select>
                                                          </div>
                                                    </div>  
                                                </div> <!-- this block is for Currency-->
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  name="unittotal[]">
                                                    </div>
                                                </div> <!-- this block is for unittotal--> 
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  <!-- this block is for remarks--> 
                                            </div>
<?php } else {
	$rCountLoop = 0;$itdgt=0;    
$itmdtqry="SELECT `id`, `socode`, `sosl`, `productid`, `mu`, round(`qty`,0) qty,round(`qtymrc`,0)qtymrc, round(`otc`,2) otc, round(`mrc`,2)mrc, `remarks`, `makeby`, `makedt`,`currency` FROM `soitemdetails` WHERE `socode`='".$soid."'";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) {while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $itmdtid= $rowitmdt["productid"];  $itdmu=$rowitmdt["mu"]; $itdqu=$rowitmdt["qty"];$itdqumrc=$rowitmdt["qtymrc"]; $itdotc=$rowitmdt["otc"]; $itdmrc=$rowitmdt["mrc"]; $itdrem=$rowitmdt["remarks"];$currency=$rowitmdt["currency"];
                  $itdtot=($itdqu*$itdotc)+($itdqumrc*$itdmrc); $itdgt=$itdgt+$itdtot;
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemName-->  
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="itemName[]" id="itemName" class="form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`  FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <option  value="<?php echo $tid; ?>" <?php if ($itmdtid == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                           </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for itemName-->  
          	                                    <div class="col-lg-1 col-md-6 col-sm-6">  <!-- this block is for measureUnit-->  
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                     
 <?php //and `id`=".$itdmu."
 $qrymu="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["id"];  $mnm=$rowmu["name"];
    ?>                                                          
                                                                <option value="<?php echo $mid; ?>" <?php if ($itdmu == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
     <?php  }}?>                                                     
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for measureUnit-->   
          	                                    <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for quantity_otc, unitprice_otc-->  
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" value="<?php echo $itdqu;?>" name="quantity_otc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" value="<?php echo $itdotc;?>" name="unitprice_otc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc-->  
         	                                    <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for quantity_mrc, unitprice_mrc-->
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_mrc" id="quantity_mrc" value="<?php echo $itdqumrc;?>" name="quantity_mrc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_mrc unitPriceV2" id="unitprice_mrc" value="<?php echo $itdmrc;?>"  name="unitprice_mrc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_mrc, unitprice_mrc-->
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for Currency-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="curr[]" id="curr" class="form-control">
                                                                <option value="">Select Currency</option>
     <?php  $qrycur="SELECT `id`, `name`, `shnm` FROM `currency`  order by name"; $resultcur = $conn->query($qrycur); if ($resultcur->num_rows > 0){while($rowcur = $resultcur->fetch_assoc()) 
              { 
                  $crid= $rowcur["id"]; $crnm=$rowcur["shnm"];
        ?>          
                                                                <option value="<?php echo $crid; ?>" <?php if ($currency == $crid) { echo "selected"; } ?>><?php echo $crnm; ?></option>
        <?php  }} ?>
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div> <!-- this block is for Currency-->
                                                <div class="col-lg-1 col-md-6 col-sm-6"><!-- this block is for unittotal-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  value="<?php echo $itdtot;?>"  name="unittotal[]">
                                                    </div>
                                                </div> <!-- this block is for unittotal--> 
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for remarks-->
                                                    <div class="row qtnrows"> 
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]"  value="<?php echo $itdrem;?>">
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for remarks-->   
                                                
                                               <?php
                                                if($rCountLoop>0){
												?>
                                               		<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                                <?php
													
												}
												$rCountLoop++;
												?>  
                                                
                                            </div>
<?php  } }
else
{
?>
                                            <div class="toClone">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`  FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for itemName--> 
          	                                    <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                                <option value="">Select Unit</option>
 <?php $qryunit="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultunit = $conn->query($qryunit); if ($resultunit->num_rows > 0) {while($rowunit = $resultunit->fetch_assoc()) 
              { 
                  $unitid= $rowunit["id"];  $unitnm=$rowunit["name"];
    ?>                                                          
                                                                <option value="<?php echo $unitid; ?>"><?php echo $unitnm; ?></option>
     <?php  }}?>                                              
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for measureUnit-->   
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" placeholder="Quantity" name="quantity_otc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" placeholder="OTC" name="unitprice_otc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc--> 
     	                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_mrc " id="quantity_mrc" placeholder="Quantity" name="quantity_mrc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_mrc unitPriceV2" id="unitprice_mrc" placeholder="MRC" name="unitprice_mrc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_mrc, unitprice_mrc-->
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="curr[]" id="curr" class="form-control">
                                                                <option value="">Select Currency</option>
     <?php  $qrycur="SELECT `id`, `name`, `shnm` FROM `currency`  order by name"; $resultcur = $conn->query($qrycur); if ($resultcur->num_rows > 0){while($rowcur = $resultcur->fetch_assoc()) 
              { 
                  $crid= $rowcur["id"]; $crnm=$rowcur["shnm"];
        ?>          
                                                                <option value="<?php echo $crid; ?>" <?php if (1 == $crid) { echo "selected"; } ?>><?php echo $crnm; ?></option>
        <?php  }} ?>
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div> <!-- this block is for Currency-->
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  name="unittotal[]">
                                                    </div>
                                                </div> <!-- this block is for unittotal--> 
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for remarks-->
                                            </div>
<?php }} ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        
                                        
                                        <div class="well no-padding top-bottom-border grandTotalWrapper">
                                        <div class="row total-row">
                                            <div class="col-xs-offset-6 col-xs-6 col-sm-offset-8 col-sm-4  col-md-offset-8 col-md-4 col-lg-offset-9 col-lg-1">
                                            <div class="form-group grandTotalWrapper">
                                                <label>Total:*</label>
                                                <input type="text" class="form-control" id="grandTotal" value="<?php echo $itdgt;?>" disabled required>
                                              </div>
                                          </div>
                                          </div>
                                      </div>    
                                        
                                        
                                    </div>      
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
                                    <?php
									//echo $mode;
                                    	$addClassName = ($mode=="1")?'link-add-po':'link-add-po-2';
									?>
        	                            <a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
                                    <br><br>&nbsp;<br><br>
                                    
                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        <div class="form-group">

                                            <label for="details">Details </label>

                                            <textarea class="form-control" id="details" name="details" rows="4" ><?php echo $details;?></textarea>

                                        </div>

                                    </div>
                                </div>
                           
                        </div>
                    </div> 
        <!-- /#end of panel -->      
                    <div class="button-bar">
                            <?php if($mode==2) { ?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update SO" id="update" >
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="copy" value="Copy SO" id="Copy">
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


<?php
if($res==4){
?>

//alert($(".cmb-parent").children("option:selected").val());

var selectedValue = $(".cmb-parent").children("option:selected").val();
	
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
        
        
        
    $.ajax({
            type: "POST",
            url: "cmb/so_item_poc_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
			beforeSend: function(){
					$(".cmd-child1").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
            //root.find(".measure-unit").html(data);
			
			$(".cmd-child1").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child1").append(data);
			
			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });	        
	
<?php
}
?>

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
        
        $.ajax({
            type: "POST",
            url: "cmb/so_item_poc_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
			beforeSend: function(){
					$(".cmd-child1").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
            //root.find(".measure-unit").html(data);
			
			$(".cmd-child1").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child1").append(data);
			
			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });	
	
});	

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

</body>
</html>
<?php }?>