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
    $rfq = $_GET["rfqid"];

    if ($res==1){echo "<script type='text/javascript'>alert('".$msg."')</script>";}
    if ($res==2){echo "<script type='text/javascript'>alert('".$msg."')</script>";}
    if ($res==4)
    {
        $qry = "SELECT rfq FROM `rfq` WHERE id = ".$id;
        //echo $qry; die;
        if ($conn->connect_error){ echo "Connection failed: " . $conn->connect_error; }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        
                        $iid=$id;$rfq=$row["rfq"];
            
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
        $iid=''; $date='';  $rfq_by=''; $note='';  $valdate='';$security_deposite='';$rfq=''; //Insert mode
        $mode = 1;
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'proc_qoutation';
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
</style>	
<body class="form deal-entry">
<?php  include_once('common_top_body.php');?>
<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Pending Qoutation</span>
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
                        <form method="post" action="common/addproc_qoutation.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Pending Qoutation </h1></div>
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
	                                    
	                                    <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">RFQ Date*</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="rfq_date" name="rfq_date" value="<?php echo $date;?>" required>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div>-->
                                       
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">RFQ</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="vendor" name="vendor" value="<?php echo $rfq;?>" disabled>
                                            </div>        
                                        </div>
            	                       
      	                                
                                        
                                         <br>
                                    <div class="po-product-wrapper-req"> 
                                        <div class="color-block-req">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
		                                        <div class="row">
		                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Vendor </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10"> Select Product </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Request Spec </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Vendor Spec </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Request Quantity </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Vendor Quantity* </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Market Price  </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Quotated Price* </h6>
                                                </div>
                                                
                                                
                                        </div>
	                                        </div>
<?php if($mode==1){?> 	                     
                                            
	                                        <div class="toCloneReq">
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemName-->  
													<div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
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
                                                
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="rfq_qty" placeholder="Quantity"  name="rfq_qty[]">
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
$itmdtqry="SELECT  a.`product`, a.`spec`,a.`order_qty`, it.name itnm, a.vendor, a.market_price, a.id FROM `rfq_details` a LEFT JOIN item it ON a.`product` = it.id WHERE a.vendor != '' and a.rfq = '$rfq'";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) 
    {   while($rowitmdt = $resultitmdt->fetch_assoc()) 
              {
                  $itmdtid= $rowitmdt["product"]; $itmnm=$rowitmdt["itnm"];  $qty=$rowitmdt["order_qty"]; $spec=$rowitmdt["spec"]; $market_price = $rowitmdt["market_price"]; $rfqDId = $rowitmdt["id"];
                  $vendor = substr($rowitmdt["vendor"], 0, -1);
                  $ven = explode(",", $vendor);
                  for($i = 0; $i < count($ven); $i++){
                      $qryVen = "SELECT name from organization where id = ".$ven[$i];
                      $resultVen = $conn->query($qryVen);
                      while($rowVen = $resultVen->fetch_assoc()){
                          
                        $venName = $rowVen["name"];
                  
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toCloneReq">
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="vendor" placeholder="Vendor"  name="vendor[]" value="<?= $venName ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="product" placeholder="Product"  name="product[]" value="<?= $itmnm ?>" disabled>
                                                    </div>
                                                </div>
                                                <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName" value="<?php echo $rfqDId; ?>">
                                                <input type="hidden" placeholder="ITEM" name="vendorId[]" value="<?php echo $ven[$i]; ?>">
          	                                     
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="spec" placeholder="Spec"  name="spec[]" value="<?= $spec ?>"disabled>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="vendor_spec" placeholder="Vendor Spec"  name="vendor_spec[]">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="qty" placeholder="Quantity"  name="qty[]" value = "<?= $qty ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="vendor_qty" placeholder="Vendor Quantity" min ="1"  name="vendor_qty[]" value = "<?= $qty ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="market_price" placeholder="Market Price"  name="market_price[]" value="<?= $market_price ?>"disabled>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="qoutate_price" placeholder="Quotated Price"  name="qoutate_price[]" required>
                                                    </div>
                                                </div>
                                                
                                                 
                                            </div>
<?php  } }}} else {?>
                                            <div class="toCloneReq">
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemName-->  
													<div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
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
                                                        <input type="number" class="form-control" id="rfq_qty" placeholder="Quantity"  name="rfq_qty[]">
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
        	                            <!--a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a-->
    	                            </div>
                                    <br><br>&nbsp;<br><br>
                                    </div>
                                </div>
                            </div> 
                            
                            <!-- /#end of panel --> 
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update RFQ"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                         
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add RFQ"  id="submit" >
                                <?php } ?>  
                            <a href = "./proc_qoutationList.php?pg=1&mod=14">
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
		$( ".po-product-wrapper-req .toCloneReq:last-child").clone().appendTo(wrapper);
    
    	$( ".po-product-wrapper-req .toCloneReq:last-child input").val("");
			
		$( ".po-product-wrapper-req .toCloneReq:last-child .cmb-attr").html("");
  

		if(x==2){
			$( ".po-product-wrapper-req .toCloneReq:last-child").append('<div class="remove-icon"><a href="#" class="remove-po-req" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
        }
    });

    $(wrapper).on("click",".remove-po-req", function(e){ //user click on remove text
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
		$( ".po-product-wrapper-req .toCloneReq:last-child").clone().appendTo(wrapper);
    
    	$( ".po-product-wrapper-req .toCloneReq:last-child input").val("");
			
		$( ".po-product-wrapper-req .toCloneReq:last-child .datalist").attr("placeholder","Select Item");
	
	
  

	//alert($('.toClone').length);
		if($('.toClone').length > 1){
			$( ".po-product-wrapper-req .toCloneReq:last-child").append('<div class="remove-icon"><a href="#" class="remove-po-req" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
		sumPrice();
		sumPriceV2();
        }
    });

    $(wrapper).on("click",".remove-po-req", function(e){ //user click on remove text
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
		$( ".po-product-wrapper-req .toCloneReq:last-child").clone().appendTo(wrapper);
    
    $( ".po-product-wrapper-req .toCloneReq:last-child input").val("");
	$( ".po-product-wrapper-req .toCloneReq:last-child .datalist").attr("placeholder","Select Item");
	
	
  

	//alert($('.toCloneReq').length);
		if($('.toCloneReq').length > 1){
			$( ".po-product-wrapper-req .toCloneReq:last-child").append('<div class="remove-icon-req"><a href="#" class="remove-po-req" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
		sumPrice();
		sumPriceV2();
        }
    });

    $(wrapper).on("click",".remove-po-req", function(e){ //user click on remove text
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