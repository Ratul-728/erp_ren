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
    
    if ($res==4)
    {
    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
    $qry="SELECT s.`id`, s.`socode`,s.`customertp`,s.`organization`,s.`srctype`, s.`customer`,DATE_FORMAT(s.`orderdate`,'%e/%c/%Y') `orderdate`,DATE_FORMAT(s.`deliverydt`,'%e/%c/%Y') `deliverydt`, s.`deliveryamt`,
            s.`deliveryby`, s.`accmanager`, s.`vat`, s.`tax`, s.`invoiceamount`, s.`makeby`, s.`makedt`,DATE_FORMAT(s.`terminationDate`,'%e/%c/%Y') `terminationDate` ,s.terminationcause,s.`status`,
            DATE_FORMAT(s.`effectivedate`,'%e/%c/%Y') `effectivedate`,s.`remarks`,s.`poc`,s.`oldsocode`,DATE_FORMAT(s.mrcdt,'%e/%c/%Y') mrcdt, o.name orgname  
            FROM `soitem` s left join organization o ON o.id = s.organization where  s.id= ".$id; 
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
                            $accmgr=$row["accmanager"];$deliveryamt = $row["deliveryamt"];
                            $invoice_amount=$row["invoiceamount"];$vat=$row["vat"]; $tax=$row["tax"]; $delivery_dt=$row["deliverydt"]; $term_dt=$row["terminationDate"];$terminationcause=$row["terminationcause"];
                            $effectivedate=$row["effectivedate"];  $hrid='1'; $st=$row["status"]; $details=$row["remarks"]; $poc=$row["poc"];$oldsocode=$row["oldsocode"]; $orgname = $row["orgname"];
                            $oldsocode=$row["oldsocode"];$mrcdt=$row["mrcdt"];
                        }
                }
            }
    $mode=2;//update mode
   // echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    else if ($res==5)
    {
    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
    $qry="SELECT `id`, `socode`,`customertp`,`organization`,`srctype`, `customer`,DATE_FORMAT(`orderdate`,'%e/%c/%Y') `orderdate`,DATE_FORMAT(`deliverydt`,'%e/%c/%Y') `deliverydt`, `deliveryby`,`deliveryamt`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`,DATE_FORMAT(`terminationDate`,'%e/%c/%Y') `terminationDate` ,terminationcause,`status`,DATE_FORMAT(`effectivedate`,'%e/%c/%Y') `effectivedate`,`remarks`,`poc`,`oldsocode` ,DATE_FORMAT(mrcdt,'%e/%c/%Y') mrcdt FROM `soitem` s
           where  id= ".$id; 
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
                            $accmgr=$row["accmanager"];$deliveryamt = $row["deliveryamt"];
                            $invoice_amount=$row["invoiceamount"];$vat=$row["vat"]; $tax=$row["tax"]; $delivery_dt=$row["deliverydt"]; $term_dt=$row["terminationDate"];$terminationcause=$row["terminationcause"];
                            $effectivedate=$row["effectivedate"];  $hrid='1'; $st=$row["status"]; $details=$row["remarks"]; $poc=$row["poc"];$oldsocode=$row["oldsocode"];$mrcdt=$row["mrcdt"]; 
                        }
                }
            }
    $mode=5;//copy mode
   // echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    else
    {
                            $uid='';$soid='';$cusype=2;$srctype=''; $cusid=''; $orderdt=date("d/m/Y");  $currency='';$deliveryby='';$accmgr='';$deliveryamt = '';
                            $invoice_amount='0'; $vat='0';$tax='0'; $delivery_dt='';$hrid='';$term_dt='';$terminationcause='';$st='';$effect_dt='';$details='';$poc='';$mrcdt='';//$term_dt=date("Y-m-d")
                            
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
                        
                        
      		            
      		            
			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                            
                                   <div class="row form-header"> 
                                   
	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>Products <i class="fa fa-angle-right"></i> Add New SO</h6>
      		                            </div>
      		                            
      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>                                   
                                   
                                   
                                   </div>                             
                            
                            
                            
                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->
     	                   
                                <div class="row">
                            	    <div class="col-sm-12">
	                                    <!-- <h4>SO Information</h4>
		                                <hr class="form-hr"> -->
		                                
		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno;?>"> 
		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
    	                            </div> 
                                    
	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="po_id">SO ID*</label>
                                            <input type="text" class="form-control" name="po_id" id="po_id" value="<?php echo $soid;?>" required>
                                        </div>        
                                    </div>
                                    
                                   
                                    <!--div class="col-lg-3 col-md-6 col-sm-6"> 
                                            <div class="form-group">
                                                <label for="cmbcontype">Organization*</label>
                                                
                                                <div class="form-group styled-select">
                                                    <input list="cmborg1" name ="cmbassign2" value = "<?= $orgname ?>" autocomplete="Search From list"  class="dl-cmborg datalist" placeholder="" required>
                                                    <datalist  id="cmborg1" name = "cmborg1" class="list-cmbassign form-control" >
                                                        <option value="">Select Customer</option>
                        <?php $qryitm="SELECT `id`, `name`  FROM `organization` order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
                                  {
                                      $tid= $rowitm["id"];  $nm=$rowitm["name"]; ?>
                                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
                        <?php  }}?>                    
                                                    </datalist> 
                                                    <input type = "hidden" name = "cmborg" id = "cmborg" value = "<?= $org ?>">
                                                </div>
                                            </div>   
                                    </div-->
                                    <div class="col-lg-3 col-md-6 col-sm-6"> 
                                        <div class="form-group">
                                            <label for="cmbcontype">Organization*</label>
                                            <div class="ds-divselect-wrapper cat-name">
                                            <div class="ds-input">
                        <input type="hidden" name="dest" value="">
                        <input type="hidden" name="org_id" id = "org_id" value = "<?= $org ?>">
                         <input type="text" name="org_name" autocomplete="off"  class="input-box form-control" value = "<?= $orgname ?>">
                    </div>
                                                <div class="list-wrapper">
                                                    <div class="ds-list">
                                
                                                        <ul class="input-ul" id="inpUl">
                                                            <li class="addnew">+ Add new</li>
                                
                                
                                                            <?php $qryitm = "SELECT id, concat(name, '(', contactno, ')') orgname FROM `organization` order by name";
                                    $resultitm                                = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                        $tid = $rowitm["id"];
                                        $nm  = $rowitm["orgname"]; ?>
                                                                        <li class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
                                                        <?php }} ?>
                                                        </ul>
                                                    </div>
                                                    <div class="ds-add-list">
                                                        <h3>Add new Item</h3>
                                                        <hr>
                                                        <label for="">Name</label> <br>
                                                        <input type="text" name="" autocomplete="off" class="Name addinpBox form-control" id="">
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-lg-6 add-more-col">
                                                                <button type="button" class="more-info">+add more info</button>
                                
                                                            </div>
                                                            <div class="col-lg-6">
                                                                 <button type = "button" class="primary ds-add-list-btn ">Save</button>
                                                            </div>
                                                        </div>
                                
                                                    </div>
                                                </div>
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
                                            <label for="cmbsostat">Status </label>
                                            <div class="form-group styled-select">
                                            <select name="cmbsostat" id="cmbsostat" class="form-control" >
                                            <option value="">Select</option>
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
                                            <label for="cmbpoc">Account Manager</label>
                                            <div class="form-group styled-select">
                                                <select name="cmbpoc" id="cmbpoc" class="cmd-child1 form-control" >
                                                <option value="">Select POC </option> 
    <?php $qryhrm="SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id` FROM `hr` h left join`employee` e on h.`emp_id`=e.`employeecode` where h.id != 1  order by emp_id"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
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
                                        <label for="mrc_dt">MRC Date</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="mrc_dt" name="mrc_dt" value="<?php echo $mrcdt;?>" >
                                            <div class="input-group-addon">
                                             <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
      	                          <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="oldso_id">OLD SO ID</label>
                                            <input type="text" class="form-control" name="oldso_id" id="oldso_id" value="<?php echo $oldsocode;?>" >
                                        </div>        
                                    </div> -->
      	                           
                            	    <br>
                                    <div class="po-product-wrapper withlebel"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if($mode==1||$mode==5){?> 	            
                                            <div class="row header-row">
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10"> Select Item* </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">VAT % </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">AIT % </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Select Unit </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Quantity</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> OTC </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Quantity</h6>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">MRC </h6>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Currency</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Unit Total </h6>
                                                </div>

                                        </div>
											<!-- INSERT -->
	                                        <div class="toClone">
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
													<!--<lebel>Item Name</lebel>-->
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item" required>
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`, round(`vat`, 2) vat, round(`ait`, 2) ait, round(`cost`, 2) cost  FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
                  $cost = $rowitm["cost"]; $vat = $rowitm["vat"]; $ait = $rowitm["ait"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for itemName--> 
                                                <!-- this block is for vat--> 
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control vat" id="vat" placeholder="VAT%" name="vat[]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- this block is for ait--> 
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control ait" id="ait" placeholder="AIT%" name="ait[]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
          	                                    <div class="col-lg-1 col-md-6 col-sm-6">
													<!--<lebel>Unit</lebel>-->
                                                <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                  <div class="form-group">
                                                      <div class="form-group styled-select">
                                                      <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                      <option value="">Select Unit</option>
 <?php $qryunit="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultunit = $conn->query($qryunit); if ($resultunit->num_rows > 0) {while($rowunit = $resultunit->fetch_assoc()) 
              { 
                  $unitid= $rowunit["id"];  $unitnm=$rowunit["name"];
    ?>                                                          
                                                        <option value="<?php echo $unitid; ?>" <?php if (1 == $unitid) { echo "selected"; } ?>><?php echo $unitnm; ?></option>
     <?php  }}?>                                         <!-- <option data-value="<?php echo $unitid; ?>" value="<?php echo $unitnm; ?>"><?php echo $unitnm; ?></option>  -->            
                                                      </select>
                                                      </div>
                                                  </div>        
                                                </div> <!-- this block is for measureUnit--> 
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
													
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
															<!--<lebel>Quantity</lebel>-->
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" placeholder="Quantity" name="quantity_otc[]" value = 1>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
														<!--	<lebel>OTC</lebel>-->
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" placeholder="PRICE" name="unitprice_otc[]">
                                                                <input type="hidden" name="unitTotalAmount_otc" class="unitTotalAmount_otc">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc-->
     	                                        <div class="col-lg-2 col-md-6 col-sm-6">
													
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
															<!--<lebel>Quantity</lebel>-->
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_mrc " id="quantity_mrc" placeholder="Quantity" name="quantity_mrc[]" value = 1>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
															<!--<lebel>MRC</lebel>-->
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_mrc unitPriceV2" id="unitprice_mrc" placeholder="MRC" name="unitprice_mrc[]">
                                                                <input type="hidden" name="unitTotalAmount_mrc" class="unitTotalAmount_mrc">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_mrc, unitprice_mrc-->
                                                <div class="col-lg-1 col-md-3 col-sm-3  col-xs-6">
													<!--<lebel>Currency</lebel>-->
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
												<!--	<lebel>Unit Total</lebel>-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  name="unittotal[]">
                                                    </div>
                                                </div> <!-- this block is for unittotal--> 
                                                <!--div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div -->  <!-- this block is for remarks--> 
                                            </div>
<?php } else {
														
?>
<div class="row header-row">
		<div class="col-lg-2 col-md-6 col-sm-6">
		<h6 class="chalan-header mgl10"> Select Item* </h6>
		</div>
		<div class="col-lg-1 col-md-6 col-sm-6">
			<h6 class="chalan-header">VAT % </h6>
		</div>
		<div class="col-lg-1 col-md-6 col-sm-6">
			<h6 class="chalan-header">AIT % </h6>
		</div>
		<div class="col-lg-1 col-md-6 col-sm-6">
			<h6 class="chalan-header mgl10"> Select Unit </h6>
		</div>
		<div class="col-lg-1 col-md-6 col-sm-6">
			<h6 class="chalan-header"> Quantity</h6>
		</div>
		<div class="col-lg-1 col-md-6 col-sm-6">
			<h6 class="chalan-header"> OTC </h6>
		</div>
		<div class="col-lg-1 col-md-6 col-sm-6">
			<h6 class="chalan-header">Quantity</h6>
		</div>

		<div class="col-lg-1 col-md-6 col-sm-6">
			<h6 class="chalan-header">MRC </h6>
		</div>

		<div class="col-lg-1 col-md-6 col-sm-6">
			<h6 class="chalan-header">Currency</h6>
		</div>
		<div class="col-lg-1 col-md-6 col-sm-6">
			<h6 class="chalan-header">Unit Total </h6>
		</div>

</div>
<?php
	$rCountLoop = 0;$itdgt=0;
$itmdtqry="SELECT a.`id`, a.`socode`, a.`sosl`, a.`productid`, a.`mu`, round(a.`qty`,0) qty,round(a.`qtymrc`,0)qtymrc, round(a.`otc`,2) otc, round(a.`mrc`,2)mrc, a.`remarks`, a.`makeby`, a.`makedt`,a.`currency`,round(a.`vat`,2) vat,round(a.`ait`,2) ait, b.name itmname FROM `soitemdetails` a LEFT JOIN item b ON a.`productid` = b.id  WHERE a.`socode`='".$soid."'";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) {while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $itmdtid= $rowitmdt["productid"];  $itdmu=$rowitmdt["mu"]; $itdqu=$rowitmdt["qty"];$itdqumrc=$rowitmdt["qtymrc"]; $itdotc=$rowitmdt["otc"]; 
                  $itdmrc=$rowitmdt["mrc"]; $itdrem=$rowitmdt["remarks"];$currency=$rowitmdt["currency"];$itvat=$rowitmdt["vat"];$itait=$rowitmdt["ait"];
                  $itmname = $rowitmdt["itmname"];
                  
                  $itdtot=($itdqu*$itdotc)+($itdqumrc*$itdmrc); $itdgt=$itdgt+$itdtot;
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
											
											<!-- EDIT -->
                                            <div class="toClone">
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for itemName-->  
<!--                                                    <lebel>Item Name</lebel>-->
													<div class="form-group">
                                                <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list" value = "<?= $itmname ?>" class="dl-itemName datalist" placeholder="Select Item" required>
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`, round(`vat`, 2) vat, round(`ait`, 2) ait, round(`cost`, 2) cost  FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
                  $cost = $rowitm["cost"]; $vat = $rowitm["vat"]; $ait = $rowitm["ait"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>

                                                    </div>        
                                                </div> <!-- this block is for itemName-->  
                                                <!-- this block is for vat--> 
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
<!--                                                     <lebel>VAT %</lebel>-->
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="numeric" class="form-control vat" id="vat"  value="<?php echo $itvat;?>" name="vat[]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- this block is for ait--> 
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
<!--                                                     <lebel>AIT %</lebel>-->
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="numeric" class="form-control ait" id="AIT"  value="<?php echo $itait;?>" name="ait[]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
          	                                    <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for measureUnit-->  
<!--													<lebel>Unit</lebel>-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                     
 <?php //and `id`=".$itdmu."
 $qrymu="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["id"];  $mnm=$rowmu["name"];
    ?>                                                          
                                                                <option value="<?php echo $mid; ?>" <?php if (1 == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
     <?php  }}?>                                                     
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for measureUnit-->   
          	                                    <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for quantity_otc, unitprice_otc-->  
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
<!--															<lebel>Quantity</lebel>-->
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" value="<?php echo $itdqu;?>" name="quantity_otc[]" value = 1>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
<!--															<lebel>OTC</lebel>-->
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" value="<?php echo $itdotc;?>" name="unitprice_otc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc-->  
         	                                    <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for quantity_mrc, unitprice_mrc-->
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
<!--															<lebel>Quantity</lebel>-->
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_mrc" id="quantity_mrc" value="<?php echo $itdqumrc;?>" name="quantity_mrc[]" value = 1>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
<!--															<lebel>MRC</lebel>-->
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_mrc unitPriceV2" id="unitprice_mrc" value="<?php echo $itdmrc;?>"  name="unitprice_mrc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_mrc, unitprice_mrc-->
                                                <div class="col-lg-1 col-md-3 col-sm-3  col-xs-6"> <!-- this block is for Currency-->
<!--													<lebel>Currency</lebel>-->
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
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6"><!-- this block is for unittotal-->
<!--													<lebel>Unit Total</lebel>-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  value="<?php echo $itdtot;?>"  name="unittotal[]">
                                                    </div>
                                                </div> <!-- this block is for unittotal--> 
                                                <!--div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows"> 
                                                        <div class="col-sm-12 col-xs-12">
															<lebel>Remarks</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]"  value="<?php echo $itdrem;?>">
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div --> <!-- this block is for remarks-->   
                                                
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
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item" required>
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`, round(`cost`, 2) cost, round(`vat`, 2) vat, round(`ait`, 2) ait  FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
                  $cost = $rowitm["cost"]; $vat = $rowitm["vat"]; $ait = $rowitm["ait"];
    ?>
                                                                <option data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for itemName--> 
                                                
                                                 <!-- this block is for vat--> 
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="number" class="form-control vat" id="vat" placeholder="VAT%" name="vat[]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- this block is for ait--> 
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="number" class="form-control ait" id="ait" placeholder="AIT%" name="ait[]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                                <option value="<?php echo $unitid; ?>" <?php if (1 == $mid) { echo "selected"; } ?> ><?php echo $unitnm; ?></option>
     <?php  }}?>                                              
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for measureUnit-->   
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" placeholder="Quantity" name="quantity_otc[]" value = 1>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" placeholder="PRICE" name="unitprice_otc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc--> 
     	                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_mrc " id="quantity_mrc" placeholder="Quantity" name="quantity_mrc[]" value = 1>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_mrc unitPriceV2" id="unitprice_mrc" placeholder="MRC" name="unitprice_mrc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_mrc, unitprice_mrc-->
                                                <div class="col-lg-1 col-md-6 col-sm-3  col-xs-3">
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
                                                <div class="col-lg-1 col-md-6 col-sm-3  col-xs-3">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  name="unittotal[]">
                                                    </div>
                                                </div>
                                                
                                                <!--div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div--> <!-- this block is for remarks-->
                                            </div>
<?php }} ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        
										
                                        <div class="row total-row">
                                            <div class="col-xs-offset-6 col-xs-6 col-sm-offset-8 col-sm-4  col-md-offset-8 col-md-4 col-lg-offset-9 col-lg-1">
                                            <div class="form-group grandTotalWrapper">
                                                <label>VAT</label>
                                                <input type="text" class="form-control" id="grandTotal" value="<?php echo $itdgt;?>" disabled required>
                                              </div>
                                          </div>
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
                                    
                                   <!--  <div class="po-product-wrapper withlebel"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Billig Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if($mode==1||$mode==5){?> 	                                        
	                                        <div class="toClone">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
													<lebel>Bill Type</lebel>
                                                    <div class="form-group">
                                                       
                                                        <div class="form-group styled-select">
                                                            <select name="billtp[]" id="billtp" class="form-control">
                                                                <option value="">Select Type</option>
                                                                <option value="1">OTC</option>
                                                                <option value="2">MRC</option>
                                                            </select>
                                                        </div>
                                                    </div> 
                                                </div> 
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
													<lebel>Title</lebel>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="title" placeholder="Title" name="title[]">
                                                    </div>      
                                                </div> 
                                                <div class="col-lg-3 col-md-6 col-sm-6">
													<lebel>Amount</lebel>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="amount" placeholder="Amount" name="amount[]">
                                                    </div>      
                                                </div> 
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
													<lebel>Bill Date</lebel>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="bldt" placeholder="Bill Date" name="bldt[]">
                                                    </div>      
                                                </div> 
                                               
                                            </div>
<?php } else {
	$rCountLoop = 0;$itdgt=0;    
$itmdtqry="SELECT `id`, `socode`, `sosl`, `productid`, `mu`, round(`qty`,0) qty,round(`qtymrc`,0)qtymrc, round(`otc`,2) otc, round(`mrc`,2)mrc, `remarks`, `makeby`, `makedt`,`currency` FROM `soitemdetails` WHERE `socode`='".$soid."'";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) {while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $itmdtid= $rowitmdt["productid"];  $itdmu=$rowitmdt["mu"]; $itdqu=$rowitmdt["qty"];$itdqumrc=$rowitmdt["qtymrc"]; $itdotc=$rowitmdt["otc"]; $itdmrc=$rowitmdt["mrc"]; $itdrem=$rowitmdt["remarks"];$currency=$rowitmdt["currency"];
                  $itdtot=($itdqu*$itdotc)+($itdqumrc*$itdmrc); $itdgt=$itdgt+$itdtot;
?>                                            
                                            
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6"> 
                                                    <lebel>Item Name</lebel>
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
                                                </div> 
          	                                    <div class="col-lg-1 col-md-6 col-sm-6"> 
													<lebel>Unit</lebel>
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
                                                </div>   
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">  
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
															<lebel>Quantity</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" value="<?php echo $itdqu;?>" name="quantity_otc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
															<lebel>OTC</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" value="<?php echo $itdotc;?>" name="unitprice_otc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
         	                                    <div class="col-lg-2 col-md-6 col-sm-6"> 
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
															<lebel>Quantity</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_mrc" id="quantity_mrc" value="<?php echo $itdqumrc;?>" name="quantity_mrc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
															<lebel>MRC</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_mrc unitPriceV2" id="unitprice_mrc" value="<?php echo $itdmrc;?>"  name="unitprice_mrc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-lg-1 col-md-3 col-sm-3  col-xs-6"> 
													<lebel>Currency</lebel>
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
                                                </div> 
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
													<lebel>Unit Total</lebel>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  value="<?php echo $itdtot;?>"  name="unittotal[]">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows"> 
                                                        <div class="col-sm-12 col-xs-12">
															<lebel>Remarks</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]"  value="<?php echo $itdrem;?>">
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>   
                                                
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
                                                </div> 
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
                                                </div> 
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
                                                </div> 
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
                                                </div> 
                                                <div class="col-lg-1 col-md-6 col-sm-3  col-xs-3">
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
                                                </div> 
                                                <div class="col-lg-1 col-md-6 col-sm-3  col-xs-3">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  name="unittotal[]">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
<?php }} ?>                                     		
                                    	
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
                                        
                                        
                                    </div>  -->
                                    
                                    
									<div class="col-sm-2">
                                    	<label for="details">Delivery Charge </label>
                                        <input type="text" name="deliveryamt" id="deliveryamt" value="<?php echo $deliveryamt;?>"  class="form-control" >
                                        &nbsp;
                                    </div>
                                    
                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        <div class="form-group">

                                            <label for="details">Delivery Details* </label>

                                            <textarea class="form-control" id="details" name="details" rows="4" required><?php echo $details;?></textarea>

                                        </div>

                                    </div>
                                    
                                    
                                    
                                    
                                    <div class="col-sm-12">

                                            <?php if($mode==2) { ?>
                                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update SO" id="update" >
                                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="copy" value="Copy SO" id="Copy">
                                          <?php } else {?>
                                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add SO" id="add" >
                                          <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="addprint" value="+Add and Print SO" id="add" -->
                                          <?php } ?> 
                                        <a href = "./soitemList.php?pg=1&mod=3">
                                          <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                                        </a>
                                        
                                        
                                    </div>    
                                        
                                </div>
                           
                        </div>
                    </div> 
        <!-- /#end of panel -->      
      
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
<?php include_once('inc_cmb_loader_js.php');?>

<?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>

<script>
    $(document).ready(function(){
        
        $(document).on("change", ".dl-itemName", function() {
            
            var val = $(this).val();
            
            var cost = $('#itemName option[value="' + val +'"]').attr('data-cost'); 
            
            $(this).closest('.toClone').find('.unitprice_otc').val(cost);
            
            var vat = $('#itemName option[value="' + val +'"]').attr('data-vat'); 
            
            $(this).closest('.toClone').find('.vat').val(vat);
            
            var ait = $('#itemName option[value="' + val +'"]').attr('data-ait'); 
            
            $(this).closest('.toClone').find('.ait').val(ait);
            
            
    });

})
</script>

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

<script>
    //Searchable dropdown
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
        $('#cmborg').val(id);
        //alert(id);
        
        
        //Change Contact Name
        $.ajax({
            type: "POST",
            url: "cmb/get_data.php",
            data: { key : id, type: 'orgtocontact' },
			beforeSend: function(){
					$("#cmbsupnm").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
			$("#cmbsupnm").empty();
			$("#cmbsupnm").append(data);
			//alert(data);
        });
        
	
	});
</script>

<script>
    //alert("s");
$(document).ready(function(){

	
	
			//existing item list
             $('.ds-list').attr('style','display:none');
			
			//one entry input box div
             $('.ds-add-list').attr('style','display:none');

             //Input Click

            $('.input-box').click(function(){
                $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
            });

            //Option's value shows on input box

            $('.input-ul').on("click","li", function(){
               // console.log(this);

                if(!$(this).hasClass("addnew")){

                    let litxt= $(this).text();
                    let lival= $(this).val();

                    $("#org_id").val(lival);
                    $.ajax({
                        type: "POST",
                        url: "cmb/get_data.php",
                        data: { key : lival, type: 'orgtocontact' },
                        beforeSend: function(){
                        	$("#cmbsupnm").html("<option>Loading...</option>");
                        },
                        
                        }).done(function(data){
                            $("#cmbsupnm").empty();
                        	$("#cmbsupnm").append(data);
                            //alert(data);
                        });
					$(this).closest('.ds-divselect-wrapper').find('.input-box').val(litxt);
					$(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value',litxt);

                    // $(this).closest('.ds-add-list').attr('style','display:none');
                    $(this).closest('.ds-list').attr('style','display:none');
                }

            });
	

	
            // New input box display


	
	
	
	/* no need for now
	
            // New-Input box's value display on old-input box

            $('.ds-add-list-btn').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
                $(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value', x);
				$(this).closest('.ds-divselect-wrapper').find('.input-box').val(x);
                $(this).closest('.ds-add-list').attr('style','display:none');
                //$(this).closest('.ds-add-list').find('.addinpBox').val('');
                console.log($(this).closest('.ds-add-list').find('.addinpBox').val(""));
                // alert(x);
                // }
                action(x);
                function action(x){
                    $.ajax({
                        url:"phpajax/divSelectOrg.php",
                        method:"POST",
                        data:{newItem: x},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#org_id").val(res.id);
                                $('.display-msg').html(res.name);
                                messageAlertLong(res,'alert-success');

                            }
                    });
	             }


            });
	
	
	*/
	
	
            $(document).mouseup(function (e) {
                if ($(e.target).closest(".ds-list").length === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length  === 0) {
                    $(".ds-add-list").hide();
                }
            });	
	
	
            $('.input-box').on("keyup", function() {
			    //alert($(this).val());
			    var searchKey = $(this).val().toLowerCase();
                $(this).closest('.ds-divselect-wrapper').find(".input-ul li ").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchKey)>-1);
                });
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			    //$(this).closest('.ds-divselect-wrapper').find('.input-ul li').click(function(){
				$(this).closest('.ds-divselect-wrapper').find('.input-ul').on("click","li", function(){
                     //
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
						//alert(x);
                        $(this).closest('.ds-divselect-wrapper').val(x);
                        $(this).closest('.ds-list').attr('style','display:none');
                    }
                })

                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){
					
                   // $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                   // $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
					
					
					
					//addNewOrg();
					
				
					
					
					
				
					
					
					
					
					
					
					
					
					
                });

			});	
	
            $('.input-ul .addnew').click(function(){
               // $(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
				addNewOrg();
                $(this).closest('.ds-list').attr('style','display:none');
            });	
	
	

	function addNewOrg(){
		
				BootstrapDialog.show({

											title: 'Add New Organization',
											//message: '<div id="printableArea">'+data+'</div>',
											message: $('<div></div>').load('addselect_modal_org_tab.php'),
											type: BootstrapDialog.TYPE_PRIMARY,
											closable: false, // <-- Default value is false
											draggable: true, // <-- Default value is false
											buttons: [{
												//icon: 'glyphicon glyphicon-print',
												cssClass: 'btn-primary',
												id: 'btn-1',
												label: 'Save',
												action: function(dialog) {

													var $button = this;
													$button.hide();

													dialog.setClosable(false);

													var orgtype = $('#org-type').serializeArray();
													//alert($("#orgtype").val());

													if(orgtype[0].value == 1){
														var ajxdata = $('#form-org').serializeArray();
														
														if(!ajxdata[0].value || !ajxdata[1].value || !ajxdata[3].value || !ajxdata[4].value || !ajxdata[5].value || !ajxdata[6].value){
                    										
                    										var msg ="";
															//alert(msg.length);
                    										if(!ajxdata[0].value){
                    										    msg = "Please Enter Name!*"; $("#cnnm").focus(); 
                    										}else if(!ajxdata[1].value){
                    										    msg = "Please Enter Industry Type!"; $("#cmbindtype").focus();
                    										}else if(!ajxdata[3].value){
                    										    msg = "Please Enter Address!"; $("#address").focus();
                    										}else if(!ajxdata[4].value){
                    										    msg = "Please Enter Contact Name!"; $("#contactname").focus();
                    										}else if(!ajxdata[5].value){
                    										    msg = "Please Enter Contact Email!"; $("#contactemail").focus();
                    										}else if(!ajxdata[6].value){
                    										    msg = "Please Enter Cotact Phone Number!"; $("#contactphone").focus();
                    										}
															
															if(msg.length>0){
															  $.alert({
																title: "Warning",
																escapeKey: true,
																content: msg,
																backgroundDismiss: true,
																confirmButton: 'OK',
																buttons: {
																OK: {
																	keys: ["enter"],
																},
															   },
															}); //alert('Please enter name'); 
															$button.show();
																return false;
															}
                    									
                    									
                    									}
													}else{
														var ajxdata = $('#form-indi').serializeArray();
														
														if(!ajxdata[0].value || !ajxdata[1].value || !ajxdata[3].value || !ajxdata[4].value || !ajxdata[5].value || !ajxdata[6].value){
                    										
                    										var msg ="";
                    										if(!ajxdata[0].value){
                    										    msg = "Please Enter Name!"; // $("#indv_name").focus();
                    										}else if(!ajxdata[1].value){
                    										    msg = "Please Enter Email!"; $("#contemail").focus();
                    										}else if(!ajxdata[2].value){
                    										    msg = "Please Enter Phone Number!"; $("#contphone").focus();
                    										}else if(!ajxdata[4].value){
                    										    msg = "Please Enter Address!"; $("#ind_address").focus();
                    										}else if(!ajxdata[5].value){
                    										    msg = "Please Enter District!"; $("#district").focus();
                    										}else if(!ajxdata[7].value){
                    										    msg = "Please Enter Country!"; $("#country").focus();
                    										}

															if(msg.length>0){
																$.alert({
																title: "Warning",
																escapeKey: true,
																content: msg,
																backgroundDismiss: true,
																buttons: {
																OK: {
																	keys: ["enter"],
																},
															   },
															}); //alert('Please enter name'); 
															$button.show();

															return false;
															}
                    									}
													}
													
											//alert(ajxdata[0].value);
													//return false;
											
									
											
													
													

													$.ajax({
														  type: "POST",
														  url: 'phpajax/divSelectOrg.php',
														  data: {data: ajxdata, type: orgtype[0].value},
														  type: 'POST',
														  dataType:"json",
														  success: function(res){

															  //dialog.setMessage("Success");


															  $("#org_id").val(res.id);
															  
															  $('.input-box').attr('value',res.name+"("+res.contact+")");
															  $("#inpUl").append("<li class='pp1' value = '"+res.id+"'>"+res.name+"("+res.contact+")"+"</li>");
															  
															  $.ajax({
                                                                    type: "POST",
                                                                    url: "cmb/get_data.php",
                                                                    data: { key : res.id, type: 'orgtocontact' },
                                                        			beforeSend: function(){
                                                        					$("#cmbsupnm").html("<option>Loading...</option>");
                                                        				},
                                                        		 
                                                                }).done(function(data){
                                                        			$("#cmbsupnm").empty();
                                                        			$("#cmbsupnm").append(data);
                                                        			//alert(data);
                                                                });

														        dialog.close();
				//                                           
														  }
														});


												/*var $button = this;
												//$button.hide();
												//dialogItself.close();
												//$button.spin();
												dialog.setClosable(false);



												var obj = [];

												var cdata = {};


												 cdata.name = $("#new-cat-field").val();



												//check user data;
												  if(!$("#new-cat-field").val()){alert('Please enter category name'); $button.show(); return false;}


												 obj.push(cdata);

												var dataString = JSON.stringify(obj);



												/*alert(dataString);

												$.ajax({
												   url: 'phpajax/cmb_add_category.php',
												   data: {posData: dataString},
												   type: 'POST',
												   dataType:"json",
												   success: function(res) {

													   if(res != 0){
															// dialog.setMessage(res.query);
														   //$("#new-cat-field").val(res.name);
														   $("#old-prod-cart-field").val(res.name);
														   $("#catID").val(res.id);
														   $("#catID").attr('data-name',res.name);
														   //document.title = res.name;
														  // dialogItself.close();
														  dialog.setMessage(res.msg);
														  setTimeout(function(){
																dialog.close();
															  },2000);

													   }else{
														   alert("Something went wrong!!!");
													   }

												   }
												});  */




												},
											}, {
												label: 'Close',
												action: function(dialogItself) {
													dialogItself.close();
												}
											}]
										});			
		
	}
	
});

                                   


</script>	
	
</body>
</html>
<?php }?>