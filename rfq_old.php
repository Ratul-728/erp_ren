<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];


if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); }
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $id= $_GET['id'];
    $empid = $_SESSION["empid"];

    if ($res==1){echo "<script type='text/javascript'>alert('".$msg."')</script>";}
    if ($res==2){echo "<script type='text/javascript'>alert('".$msg."')</script>";}
    if ($res==4)
    {
        $qry = "SELECT `rfq`,DATE_FORMAT(`date`, '%d/%m/%Y') date,`rfq_by`,`note`,DATE_FORMAT(`validity_date`, '%d/%m/%Y') valdate,`security_deposite` FROM `rfq` WHERE id = ".$id;
        //echo $qry; die;
        if ($conn->connect_error){ echo "Connection failed: " . $conn->connect_error; }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        
                        $iid=$id;$date=$row["date"]; $rfq_by=$row["rfq_by"];$note=$row["note"];$valdate=$row["valdate"];$security_deposite=$row["security_deposite"];$rfq = $row["rfq"]; 
                        
            
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
        $iid=''; $date=date("d/m/Y");  $rfq_by=$empid; $note='';  $valdate=date("d/m/Y");$security_deposite='';$rfq=''; //Insert mode
        $mode = 1;
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rfq';
    $currPage = basename($_SERVER['PHP_SELF']);
?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<link href="js/plugins/select2/css/select2.min.css" rel="stylesheet" />
	
<style>

.select2{
    position: absolute;
    width: 100%;
    text-align: right;
    border: 0px solid #efefef;
    min-height: 30px;
    padding: 0;
    margin: 0;
}
.select2 li{
    padding: 0!important;
    
}

.select2 li .select2-selection__choice__display{padding: 3px 5px;}
.select2 button{
    padding: 3px 8px;
    border: 0px solid #b7b7b7;
    border-right:1px solid #c0c0c0;
    border-radius: 5px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.select2 textarea{
    resize: none!important;
    height: 0px!important;
    padding: 0!important;
    margin: 0!important;
    
}
.cmb-attr{
    border: 1px solid #bebebe;
    min-height: 33px;
    border-radius: 5px;
}    
    
</style>	
<body class="form deal-entry">
<?php  include_once('common_top_body.php');?>
<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>RFQ  Details</span>
        </div>
        <?php  include_once('menu.php');?>
	    <div style="height:54px;">
	    </div>
    </div>
   <!-- END #sidebar-wrapper --> 
   <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12">
                    <p>&nbsp;</p> <p>&nbsp;</p>
                    <p>
                        <form method="post" action="common/addrfq.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>RFQ Form</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    <div class="row">
      		                            <div class="col-sm-12"> 
	                                       <!--  <h4></h4>
	                                        <hr class="form-hr"> --> 
		                                    <input type="hidden"  name="itid" id="itid" value="<?php echo $iid;?>">
		                                    <input type="hidden"  name="rfq_no" id="rfq_no" value="<?php echo $rfq;?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
	                                    </div>
	                                    
	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">RFQ Date*</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="rfq_date" name="rfq_date" value="<?php echo $date;?>" required>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbhrmgr">RFQ By*</label>
                                                <div class="form-group styled-select">
                                                <select name="rfq_by" id="rfq_by" class="form-control" required>
                                                <option value="">Select Employee</option>
<?php $qryhrm="SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id`, e.id eid FROM `hr` h,`employee` e where h.`emp_id`=e.`employeecode` and h.id != 1 order by emp_id"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
      { 
          $hridm= $rowhrm["eid"];  $hrnmm=$rowhrm["emp_id"];
?>                                                          
                                                    <option value="<?php echo $hridm; ?>" <?php if ($rfq_by == $hridm) { echo "selected"; } ?>><?php echo $hrnmm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">Validity Date*</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="val_date" name="val_date" value="<?php echo $valdate;?>" required>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">Security Deposite</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="seq_depo" name="seq_depo" value="<?php echo $security_deposite;?>">
                                            </div>        
                                        </div>
                                        <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">Note</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="rfq_note" name="rfq_note" value="<?php echo $note;?>">
                                            </div>        
                                        </div-->
            	                       
      	                                
                                        
                                         <br>
                                    <div class="po-product-wrapper"> 
                                        <div class="color-block-req">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
		                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10"> Select Product* </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Select Requisitions* </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Oder Quantity* </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Spec </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Market Rate </h6>
                                                </div>
                                                
                                                
                                        </div>
	                                        </div>
<?php if($mode==1){?> 	                     
                                            
	                                        <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemName-->  
													<div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item" required>
                                                            <datalist  id="itemName" class="list-itemName form-control" required>
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT DISTINCT a.product, itm.name FROM `requision_details` a, item itm WHERE (a.status = 1 or a.status = 2 or a.status = 3) and itm.id = a.`product` order by itm.name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["product"];  $nm=$rowitm["name"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for itemName-->
                                                <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="cmb-attr">
															 
															<!-- select2 -->
															<!--select class="form-control select2" multiple="multiple">
															  <option selected="selected">orange</option>
															  <option>white</option>
															  <option selected="selected">purple</option>
															</select-->
															
															
														</div>            
                                                    </div>
                                                </div>  <!-- this block is for remarks-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="rfq_qty" placeholder="Quantity"  name="rfq_qty[]" required min="1">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="rfq_spec" placeholder="Spec"  name="rfq_spec[]">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="rfq_mrp" placeholder="Market Rate"  name="rfq_mrp[]">
                                                    </div>
                                                </div>
                                                
                                                
                                            </div>
<?php } else {
	$rCountLoop = 0;$itdgt=0;    
$itmdtqry="SELECT a.`product`,a.`requisition_id`,a.`spec`,a.`order_qty`,a.`market_price`, b.name itnm FROM `rfq_details` a LEFT JOIN item b ON a.`product` = b.id WHERE `rfq` = '".$rfq."'";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) 
    {   while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $itmdtid= $rowitmdt["product"]; $itmnm=$rowitmdt["itnm"];  $qty=$rowitmdt["order_qty"]; $spec=$rowitmdt["spec"];$market_price=$rowitmdt["market_price"]; 
                  $req_ids = substr($rowitmdt["requisition_id"], 0, -1);
                  for($i=0; $i<count($req_ids); $i++) $req
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6"><!-- this block is for itemname-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" value ="<?= $itmnm ?>" placeholder="<?php echo $itmnm; ?>" required>
                                                            <datalist id="itemName" class="list-itemName form-control" required> 
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT DISTINCT a.product, itm.name FROM `requision_details` a, item itm WHERE a.status = 1 and itm.id = a.`product` order by itm.name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["product"];  $nm=$rowitm["name"];
    ?>
                                                                <!-- <option  value="<?php echo $tid; ?>" <?php if ($itmdtid == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>-->
                                                                 <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                                     
                                                            </datalist>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for itemname-->
                                                <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName" value="<?php echo $itmdtid; ?>">
          	                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="cmb-attr">
                                                            
															<!-- select2 -->
															<select required class="js-example-basic-multiple  form-control" name="attval[<?php echo $itmdtid;?>][]" multiple="multiple">
												<?php
												    
												        $qryReq = "SELECT reqdet.id, req.`requision_no` FROM `requision` req LEFT JOIN requision_details reqdet ON req.requision_no = reqdet.requision_no WHERE (req.status = 2 or req.status = 3) and reqdet.product = ".$itmdtid;
												        //echo $qryReq;die;
												        $resultReq = $conn->query($qryReq);
												        while($rowReq = $resultReq->fetch_assoc()){
												            $reqName = $rowReq["requision_no"];
												            $reqId = $rowReq["id"];
												            
												        
												?>
															  <option <?php if(strpos($req_ids, $reqId)  !== false){ echo "selected='selected'"; }?>><?= $reqName ?></option>
															  
												<?php } ?>
															</select>
															
															
													      </div>      
                                                    </div>
                                                </div>  <!-- this block is for remarks-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="rfq_qty" placeholder="Quantity"  name="rfq_qty[]" value = "<?= $qty ?>" required min = "1">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="rfq_spec" placeholder="Spec"  name="rfq_spec[]" value="<?= $spec ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="rfq_mrp" placeholder="Market Rate"  name="rfq_mrp[]" value="<?= $market_price ?>">
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
<?php  } } else {?>
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemName-->  
													<div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item" required>
                                                            <datalist  id="itemName" class="list-itemName form-control" required>
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT DISTINCT a.product, itm.name FROM `requision_details` a, item itm WHERE a.status = 1 and itm.id = a.`product` order by itm.name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["product"];  $nm=$rowitm["name"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for itemName-->
                                                <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="cmb-attr">
															 
															<!-- select2 -->
															<!--select class="form-control select2" multiple="multiple">
															  <option selected="selected">orange</option>
															  <option>white</option>
															  <option selected="selected">purple</option>
															</select-->
															
															
														</div>            
                                                    </div>
                                                </div>  <!-- this block is for remarks-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="rfq_qty" placeholder="Quantity"  name="rfq_qty[]" required min = "1">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="rfq_spec" placeholder="Spec"  name="rfq_spec[]">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="rfq_mrp" placeholder="Market Rate"  name="rfq_mrp[]">
                                                    </div>
                                                </div>
                                                
                                                
                                            </div>
<?php }} ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        
                                    </div>      
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
                                    <?php
									//echo $mode;
                                    	$addClassName = ($mode=="1")?'link-add-req':'link-add-req-2';
									?>
        	                            <a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
    	                            
                                    <br><br>&nbsp;<br><br>
                                    <!--<div class="form-group">
                                                <label for="details">Note </label>
                                                <textarea class="form-control" id="rfq_note" name="rfq_note" rows="4" ><?php echo $note;?></textarea>
                                            </div>
                                    </div>-->
                                </div>
                            </div> 
                            
                            <!-- /#end of panel --> 
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update RFQ"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                         
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add RFQ"  id="submit" >
                                <?php } ?>  
                            <a href = "./rfqList.php?pg=1&mod=14">
                                <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                            </a>
                            </div>    
                        </form>       
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /#page-content-wrapper -->

<?php    include_once('common_footer.php');?>
<?php include_once('inc_cmb_loader_js.php');?>

<script src="js/plugins/select2/select2.min.js"></script>

<script>
    $(document).ready(function() {
    var max_fields      = 20; //maximum input boxes allowed
    var wrapper         = $(".color-block-req"); //Fields wrapper
    var add_button      = $(".link-add-req"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper .toClone:last-child").clone().appendTo(wrapper);
    
    	$( ".po-product-wrapper .toClone:last-child input").val("");
			
		$( ".po-product-wrapper .toClone:last-child .cmb-attr").html("");
  

		if(x==2){
			$( ".po-product-wrapper .toClone:last-child").append('<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
        }
    });

    $(wrapper).on("click",".remove-po", function(e){ //user click on remove text
        e.preventDefault();
		//alert('i am active 4');
		$(this).parent().parent().remove(); 
		//$(this).parent().parent().parent().attr('style','border:1px solid #000');
		
		x--;
		
    })
});

$(document).ready(function() {
    var max_fields      = 20; //maximum input boxes allowed
    var wrapper         = $(".color-block-req"); //Fields wrapper
    var add_button      = $(".link-add-req-2"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper .toClone:last-child").clone().appendTo(wrapper);
    
    	$( ".po-product-wrapper .toClone:last-child input").val("");
			
		$( ".po-product-wrapper .toClone:last-child .datalist").attr("placeholder","Select Item");

	//alert($('.toClone').length);
		if($('.toClone').length > 1){
			$( ".po-product-wrapper .toClone:last-child").append('<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
		sumPrice();
		sumPriceV2();
        }
    });

    $(wrapper).on("click",".remove-po", function(e){ //user click on remove text
        e.preventDefault();
		//alert('i am active 2');
		$(this).parent().parent().remove(); 
		//$(this).parent().parent().parent().attr('style','border:1px solid #000');
		
		x--;
		
    })
});



$(document).ready(function() {
    var max_fields      = 20; //maximum input boxes allowed
    var wrapper         = $(".color-block-req"); //Fields wrapper
    var add_button      = $(".link-add-ot-2"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper .toClone:last-child").clone().appendTo(wrapper);
    
    $( ".po-product-wrapper .toClone:last-child input").val("");
	$( ".po-product-wrapper .toClone:last-child .datalist").attr("placeholder","Select Item");
	
	
  

	//alert($('.toClone').length);
		if($('.toClone').length > 1){
			$( ".po-product-wrapper .toClone:last-child").append('<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
		sumPrice();
		sumPriceV2();
        }
    });

    $(wrapper).on("click",".remove-po", function(e){ //user click on remove text
        e.preventDefault();
		//alert('i am active 5');
		$(this).parent().parent().remove(); 
		//$(this).parent().parent().parent().attr('style','border:1px solid #000');
		
		x--;
		
    })
});
</script>
<script language="javascript">

//load attr cmd data
function loadItemTypeAttr(id,src){
	//alert(id+' == '+src.attr('name'));
	
		$.ajax({
			url:"phpajax/load_itemtype_req.php",
			method:"POST",
			data:{nameid:id, postaction:'loadattrval'},
			success:function(data)
			{
				//messageAlert(data)
				src.html(data);
				$(src).find('select').select2();
				

    
			}
		});		
}

<?php
    //for edit mode;
    if($_REQUEST['res'] != 0){
        ?>
        $(".cmb-attr select").select2();
    <?
    }

    
?>	    
	
	
	
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
	
	src = root.find(".cmb-attr");
	
	
	
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
			loadItemTypeAttr(id,src);
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
/*
	$(".select2").select2({
    tags: true,
    tokenSeparators: [',', ' ']
})

*/
	
</script>	
	
	
</body>

</html>

<?php }?>