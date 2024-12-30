<?php
require "common/conn.php";
session_start();
//print_r($_SESSION);die;
$usr=$_SESSION["user"];
$empid = $_SESSION["empid"];

if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); }
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $id= $_GET['id'];
    $res=4;

    if ($res==1){echo "<script type='text/javascript'>alert('".$msg."')</script>";}
    if ($res==2){echo "<script type='text/javascript'>alert('".$msg."')</script>";}
    if ($res==4)
    {
        $qry="SELECT org.name, rfqpo.vendor, rfqpo.poid 
                FROM rfqpo rfqpo LEFT JOIN organization org ON org.id = rfqpo.`vendor` WHERE rfqpo.id =".$id; 
        //echo $qry; die;
        if ($conn->connect_error){ echo "Connection failed: " . $conn->connect_error; }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        
                        $iid=$id; $vendor = $row["name"]; $vid = $row["vendor"]; $pono = $row["poid"];
                        
            
                    }
            }
        }
    $mode=2;//update mode
    $date=date("d/m/Y");
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
        $iid=''; $vendor='';$pono=''; //Insert mode
        $date=date("d/m/Y");
        $mode = 1;
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'raise_po';
    $currPage = basename($_SERVER['PHP_SELF']);
?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<body class="form deal-entry">
<?php  include_once('common_top_body.php');?>
<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>PO Delivery</span>
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
                        <form method="post" action="common/addrfqpo_delivery.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Raise PO Form</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    <div class="row">
      		                            <div class="col-sm-12"> 
	                                       <!--  <h4></h4>
	                                        <hr class="form-hr"> --> 
		                                    <input type="hidden"  name="itid" id="itid" value="<?php echo $iid;?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
		                                    <input type="hidden"  name="vid" id="vid" value="<?php echo $vid;?>">
		                                    
		                                    
	                                    </div>
	                                    
	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">PO No</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="vendor" name="vendor" value="<?php echo $pono ;?>" disabled>
                                              
                                            </div>        
                                        </div>
      	                                
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">Vendor</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="vendor" name="vendor" value="<?php echo $vendor ;?>" disabled>
                                              
                                            </div>        
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">Recieved Date*</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="rec_date" name="rec_date" value="<?php echo $date;?>" required>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
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
                                                <h6 class="chalan-header mgl10"> Quotation </h6>
                                                </div>
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Product </h6>
                                                </div>
                                    
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Offered Quantity </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Quotated Price </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Branch </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Recieved Quantity </h6>
                                                </div>
                                                
                                        </div>
	                                        </div>
<?php
	   
$itmdtqry="SELECT a.id rfqvid, ra.id rfqaid, a.quotation, rd.rfq, i.name,i.id productid, a.order_qty, a.offered_qty, a.quated_price, rfpo.id rfpoid, rfpo.status, rfpo.qty

FROM rfqpo_details rfpo LEFT JOIN rfq_authorisation ra ON rfpo.rfq_auth = ra.id LEFT JOIN `rfq_vendor` a ON ra.rfq_vendor = a.id LEFT JOIN organization org ON org.id = a.`vendor_id` LEFT JOIN item i ON a.product = i.id LEFT JOIN rfq_details rd ON a.rfq=rd.id

WHERE rfpo.pono = '$pono'";
//echo $itmdtqry;die;            
$resultitmdt = $conn->query($itmdtqry); 
    while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $quotation= $rowitmdt["quotation"]; $rfq=$rowitmdt["rfq"]; $product =$rowitmdt["name"]; $qty=$rowitmdt["order_qty"];$offered_quantity=$rowitmdt["offered_qty"]; 
                  $price=$rowitmdt["quated_price"]; $rfqvid = $rowitmdt["rfqvid"]; $rfqaid = $rowitmdt["rfqaid"]; $rfpoid = $rowitmdt["rfpoid"]; $rfst = $rowitmdt["status"]; $rfqty = $rowitmdt["qty"]; 
                  $productid = $rowitmdt["productid"];
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toCloneReq">
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  id="quotation" placeholder="Quotation"  name="quotation[]" disabled value = <?= $quotation ?>>
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" id="rfqvid"  name="rfqvid[]" value = <?= $rfqvid ?>>
                                                <input type="hidden" id="rfpoid"  name="rfpoid[]" value = <?= $rfpoid ?>>
                                                <input type="hidden" id="productid"  name="productid[]" value = <?= $productid ?>>
                                                <input type="hidden" id="unitprice"  name="unitprice[]" value = <?= $price ?>>
                                                    
                                                    <!--div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="rfq" placeholder="RFQ"  name="rfq[]" disabled value = <?= $rfq ?>>
                                                    </div>
                                                </div-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  id="product" placeholder="Product"  name="product[]" disabled value = <?= $product ?>>
                                                    </div>
                                                </div>
                                            
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  id="offered_qty" placeholder="Offered Quantity"  name="offered_qty[]" disabled value = <?= $offered_quantity ?>>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="offered_quantity"  name="offered_quantity[]" value = <?= $offered_quantity ?>>
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  id="price" placeholder="Quotated Price"  name="price[]" disabled value = <?= $price ?>>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <select name="storeName[]" i d="storeName" class="storeName form-control" required>
                                                        <option value="">Select Store</option>
                                                        <?php $qryitm = "SELECT s.`id`, s.`name` FROM `branch` s order by s.name";
                                                    
                                                                $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                $tid = $rowitm["id"];
                                                                $nm  = $rowitm["name"];
                                                        ?>
                                                                <option value="<?php echo $tid; ?>"><?php echo $nm; ?></option>
                                                        <?php }} ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" max="<?= $offered_quantity ?>" id="qty" placeholder="Quantity"  name="quantity[]" value = "" required>
                                                    </div>
                                                </div>
                                               
                                                 
                                            </div>
<?php  } ?>                                    		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        <div class="form-group">

                                            <label for="details"> Note</label>

                                            <textarea class="form-control" id="note" name="note" rows="2" ></textarea>

                                        </div>

                                    </div>
                                        
                                    </div>      
                                   
                                    <br><br>&nbsp;<br><br>
                                    </div>
                                </div>
                            </div> 
                            
                            <!-- /#end of panel --> 
                            <div class="button-bar">
                                
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Accept"  id="submit" >
                                 
                            <a href = "./raise_poList.php?pg=1&mod=14">
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

<script language="javascript">
<?php
if($res==4){
?>

//alert($(".cmb-parent").children("option:selected").val());

	
<?php
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

</script>

<script>
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        var id = $('#cmbassign1 option[value="' + g +'"]').attr('data-value');
        $('#cmborg').val(id);
        //alert(id);
        
        //Change Lead Name
        $.ajax({
            type: "POST",
            url: "cmb/get_data.php",
            data: { key : id, type: 'orgtocontact' },
			beforeSend: function(){
					$("#cmbld").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
			$("#cmbld").empty();
			$("#cmbld").append(data);
			//alert(data);
        });
        
	
	});
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

</body>

</html>

<?php }?>