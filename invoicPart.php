<?php
//print_r($_REQUEST);
//exit();
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$date = date("d/m/Y");

//echo $usr;die;
if($usr==''){  header("Location: ".$hostpath."/hr.php");}
else
{
    $res= $_GET['res'];    $msg= $_GET['msg'];    $id= $_GET['id'];    $serno= $_GET['id'];    $totamount=0;
    
    if ($res==4)
    {
    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
    $qry="SELECT inv.`id`, `socode`,`customertp`,`organization`,`srctype`, `customer`,DATE_FORMAT(`orderdate`,'%e/%c/%Y') `orderdate`,DATE_FORMAT(`deliverydt`,'%e/%c/%Y') `deliverydt`, `deliveryby`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`,DATE_FORMAT(`terminationDate`,'%e/%c/%Y') `terminationDate` ,terminationcause,`status`,DATE_FORMAT(`effectivedate`,'%e/%c/%Y') `effectivedate`,`remarks`,`poc`,`oldsocode`,DATE_FORMAT(mrcdt,'%e/%c/%Y') mrcdt
    ,o.name orgnm,o.street,o.area,area.name arnm,o.district,ds.name dsnm,o.state,st.name stnm,o.zip,o.country,cn.name cnnm,o.contactno,o.email,o.website,ofc.Name ofcnm,ofc.street ofcst,ofc.area ofcar,ofc.email ofceml,ofc.web ofcweb
FROM `soitem` inv left join organization o on inv.organization=o.id
left join area on o.area=area.id left JOIN district ds on o.district=ds.id
left join state st on o.state=st.id left join country cn on o.country=cn.id
,companyoffice ofc
where inv.id= ".$id; 
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
                            $oldsocode=$row["oldsocode"];$mrcdt=$row["mrcdt"];
                            
                            $sof=$row["socode"];$orgnm=$row["orgnm"]; $strt=$row["street"]; $arnm=$row["arnm"];  
					 $dsnm=$row["dsnm"]; $stnm=$row["stnm"];$zip=$row["zip"]; $cnnm=$row["cnnm"];
					 $invcnt=$row["contactno"]; $inveml=$row["email"];$invweb=$row["website"]; 
					 $ofcnm=$row["ofcnm"]; $ofcst=$row["ofcst"];$ofcar=$row["ofcar"];  $ofceml=$row["ofceml"];$ofcweb=$row["ofcweb"];
					 $invdt=date("d/m/Y");$invmnth=date("m");
                        }
                }
            }
    $mode=2;//update mode
   // echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
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
                        <form method="post" action="common/addinvoicepart.php" id="form1" enctype="multipart/form-data">  
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->  
                    <!-- START PLACING YOUR CONTENT HERE -->
                            <div class="panel panel-info">
        			            <div class="panel-body panel-body-padding">
                                    <span class="alertmsg"></span>
                                   <div class="row form-header"> 
                                        <div class="col-lg-6 col-md-6 col-sm-6">
          	                                <h6>Products <i class="fa fa-angle-right"></i> Create Invoice</h6>
          	                            </div>
          	                            
          	                            <div class="col-lg-6 col-md-6 col-sm-6">
          	                               <h6><span class="note"> (Field Marked * are required)</span></h6>
          	                            </div>   
                                    </div>  
                                    <!-- <br> -->
                                  	<!-- <p>(Field Marked * are required) </p> -->
             	                        
         	                        <div class="invoice-wrapper"  style="width:800px;border:2px solid #c0c0c0;padding: 10px; ">
                                         <table width="100%" align="center" border="0" class="tbl_lbl1 tbl1" cellspacing="0" cellpadding="0">
                    				  	    <tr>
                        					    <td>
                        						    <div>
                        						        <?php echo $comname; ?> <br> <?php echo $comaddress; ?> <br>
                            					        <?php echo $comcontact;?> <br> <?php echo $comemail; ?> <br> <?php echo $ofcweb; ?>
                            						</div>
                        					    </td>
                        					    <td>
            					                    <table cellspacing="0" class="tbl_lbl2" cellpadding="0" align="right">
                            					        <tbody>
                            				                <tr>
                            				                    <th>Bill To:</th>
                            			                    </tr>
                            				                <tr>
                        				                        <td>
                            						                <div style="height: 5px"></div>
                            						                <?php echo $orgnm; ?> <br>  
                        						                    <?php echo $strt; ?> <br>
                            					                    <?php echo $arnm." , ".$dsnm." , ".$stnm."-".$zip;?> <br>
                            					                    <?php echo $cnnm;?> <br>
                            					                    Phone: <?php echo $invcnt;?> <br>
                            				                        <?php echo $inveml; ?> <br>
                            				                        <?php echo $invweb; ?>
                        				                        </td>
                        			                        </tr>
                            			                </tbody>
                    				                </table>
                        					    </td>
                        			        </tr>
                        			        <tr>
                        			            <td>
                        						    <div>
                        						        Order Number : <?php echo $sof; ?>
                            						</div>
                        					    </td>
                        			        </tr>
                        		        </table>
         	                        </div>
                                    <div class="row">
                                	    <div class="col-sm-12">
                                             <input type="hidden"  name="serid" id="serid" value="<?php echo $serno;?>"> 
        	                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
        	                                 <input type="hidden"  name="po_id" id="po_id" value="<?php echo $soid;?>">
        	                                 <input type="hidden"  name="cmborg" id="cmborg" value="<?php echo $org;?>">
        	                            </div> 
                                	    <br>
                                        <div class="po-product-wrapper withlebel"> 
                                            <div class="color-block">
         		                                <div class="col-sm-12">
                                                    <h4>Invoice Information  </h4>
        	                                        <hr class="form-hr">
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <label for="mrc_dt">Invoice Date*</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control datepicker" id="invoicedt" name="invoicedt" value="<?= $date ?>" required>
                                                        <div class="input-group-addon">
                                                            <span class="glyphicon glyphicon-th"></span>
                                                        </div>
                                                    </div>     
                                                </div>   
                                                
<?php
	$rCountLoop = 0;$itdgt=0;    
$itmdtqry="SELECT `id`, `socode`, `sosl`, `productid`, `mu`, round(`qty`,0) qty,round(`qtymrc`,0)qtymrc, round(`otc`,2) otc, round(`mrc`,2)mrc, `remarks`, `makeby`, `makedt`,`currency`,vat,ait FROM `soitemdetails` WHERE `socode`='".$soid."'";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) {while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $itmdtid= $rowitmdt["productid"];  $itdmu=$rowitmdt["mu"]; $itdqu=$rowitmdt["qty"];$itdqumrc=$rowitmdt["qtymrc"]; $itdotc=$rowitmdt["otc"]; $itdmrc=$rowitmdt["mrc"]; 
                  $itdrem=$rowitmdt["remarks"];$currency=$rowitmdt["currency"];$itvat=$rowitmdt["vat"];$itait=$rowitmdt["ait"];
                  $itdtot=($itdqu*$itdotc)+($itdqumrc*$itdmrc); $itdgt=$itdgt+$itdtot;
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemName-->  
                                                    <lebel>Item Name</lebel>
													<div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="itemName[]" id="itemName" class="form-control" >
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
                                                <!-- this block is for vat--> 
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
                                                     <lebel>VAT %</lebel>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="numeric" class="form-control" id="vat"  value="<?php echo $itvat;?>" name="vat[]" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- this block is for ait--> 
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
                                                     <lebel>AIT %</lebel>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="numeric" class="form-control" id="AIT"  value="<?php echo $itait;?>" name="ait[]" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
          	                                    <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for measureUnit-->  
													<lebel>Unit</lebel>
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="measureUnit[]" id="measureUnit" class="form-control" >
                                                     
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
															<lebel>Quantity</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" value="<?php echo $itdqu;?>" name="quantity_otc[]" >
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
															<lebel>OTC</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" value="<?php echo $itdotc;?>" name="unitprice_otc[]" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc-->  
         	                                    
                                                <div class="col-lg-1 col-md-3 col-sm-3  col-xs-6"> <!-- this block is for Currency-->
													<lebel>Currency</lebel>
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="curr[]" id="curr" class="form-control" >
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
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <lebel>Exchange</lebel>
                                                        <div class="form-group">
                                                            <div class="form-group styled-select">
                                                                <input type="text" class="form-control" id="convrt" placeholder="Exchange Rate" name="convrt[]">
                                                            </div>
                                                        </div>        
                                                    </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6"><!-- this block is for unittotal-->
													<lebel>Unit Total</lebel>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total"   value="<?php echo $itdtot;?>"  name="unittotal[]">
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
                                                
                                                
                                            </div>
<?php  } }
else
{
?>
                                            <div class="toClone">
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item" >
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
                                                
                                                 <!-- this block is for vat--> 
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="number" class="form-control" id="vat" placeholder="VAT%" name="vat[]" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- this block is for ait--> 
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <input type="number" class="form-control" id="ait" placeholder="AIT%" name="ait[]" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
          	                                    <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="measureUnit[]" id="measureUnit" class="form-control" >
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
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" placeholder="Quantity" name="quantity_otc[]" >
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" placeholder="OTC" name="unitprice_otc[]" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for quantity_otc, unitprice_otc--> 
     	                                        
                                                <div class="col-lg-1 col-md-6 col-sm-3  col-xs-3">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="curr[]" id="curr" class="form-control" >
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
                                                    <lebel>Exchange</lebel>
                                                        <div class="form-group">
                                                            <div class="form-group styled-select">
                                                                <input type="text" class="form-control" id="convrt" placeholder="Exchange Rate" name="convrt[]">
                                                            </div>
                                                        </div>        
                                                    </div>
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
<?php } ?> 
                                      		
                                        		<!-- this block is for php loop, please place below code your loop  --> 
                                            </div>
                                            
                                            
                                            
                                            <div class="well no-padding top-bottom-border grandTotalWrapper">
                                            <div class="row total-row">
                                                <div class="col-xs-offset-6 col-xs-6 col-sm-offset-8 col-sm-4  col-md-offset-8 col-md-4 col-lg-offset-10 col-lg-1">
                                                <div class="form-group grandTotalWrapper">
                                                    <label>Total:</label>
                                                    <input type="text" class="form-control" id="grandTotal" value="<?php echo $itdgt;?>"  required>
                                                  </div>
                                              </div>
                                              </div>
                                          </div>
                                        </div> 
                                        
                                        <div class="col-sm-12">
                                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Create Invoice" id="add" >
                                            <a href = "https://bithut.biz/BitFlow/soitemList.php?pg=1&mod=3">
                                                <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                                            </a>
                                        </div> 
                                    </div>
                                </div>
                            </div> 
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
<?php include_once('inc_cmb_loader_js.php');

    if ($res==1){ echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; }

    if ($res==2){ echo "<script type='text/javascript'>messageAlert('".$msg."')</script>";  }
?>

<script language="javascript">

/*  autofill combo  */

 var dataList=[];
$(".list-itemName").find("option").each(function(){dataList.push($(this).val())})


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