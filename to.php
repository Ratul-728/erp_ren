<?php
//print_r($_REQUEST);
//exit();
session_start();

require "common/conn.php";
include_once('rak_framework/fetch.php');

$usr = $_SESSION["user"];
//echo $usr;die; 

//ini_set('display_errors', 1);

if ($usr == '') 
{
    header("Location: " . $hostpath . "/hr.php");
}
else 
{
    $res       = $_GET['res'];
    $msg       = $_GET['msg'];
    $id        = $_GET['id'];
    $serno     = $_GET['id'];
    $totamount = 0;
    $itdgt=0;
    $discttot=0;

    if($_REQUEST['action']=='restore')
    {
	    $soid = $_REQUEST['socode'];
	    $action = 'restore';
    }
	//restore mode
    if ($res == 4) 
    { //update mode
		
        if($_REQUEST['action']=='restore')
        {
            $_SESSION['pagestate'] = 'revision';
            
			$soid = $_REQUEST['socode'];
			$rid = $_REQUEST['rid'];
        
			$qry = "SELECT 
			s.orderstatus, 
			s.`socode`, 
			s.`id`,
			s.project,
			p.name projnm,
			s.srctype,
			s.`organization`, 
			s.`customer`,
			DATE_FORMAT(s.`orderdate`,'%e/%c/%Y') `orderdate`,
			s.`deliveryamt`,
			s.`accmanager`, 
			s.`vat`, 
			s.`tax`, 
			s.`invoiceamount`, 
			s.`makeby`, 
			s.`makedt`,
			s.`status`,
			s.`remarks`,
			s.`poc`,
			s.`oldsocode`, 
			s.note,
			DATE_FORMAT(s.mrcdt,'%e/%c/%Y') mrcdt, 
			o.name orgname,
			adjustment
			
            FROM `quotation_revisions` s 
			left join organization o ON o.id = s.organization 
			left join project p on s.project=p.id
			WHERE  s.socode= '" . $soid."' AND s.id=".$rid;
			//echo $qry; die;
		}
		else
		{
		    $_SESSION['pagestate'] = 'quotation';
            $qry = "SELECT 
			s.`id`, 
			s.orderstatus, 
			s.`socode`,
			s.`organization`, 
			s.project,
			p.name projnm,
			s.srctype,
			s.`customer`,
			DATE_FORMAT(s.`orderdate`,'%e/%c/%Y') `orderdate`,
			s.`deliveryamt`,
			s.`accmanager`, 
			s.`vat`, 
			s.`tax`, 
			s.`invoiceamount`, 
			s.`makeby`, 
			s.`makedt`,
			s.`status`,
			s.`remarks`,
			s.`poc`,
			s.`oldsocode`,
			DATE_FORMAT(s.mrcdt,'%e/%c/%Y') mrcdt, 
			o.name orgname,
			adjustment,
			s.note 
            FROM `quotation` s 
			left join organization o ON o.id = s.organization 
			left join project p on s.project=p.id
			where  s.id= " . $id;
		}
		
		//echo $qry;die;
        if ($conn->connect_error) 
        {
            echo "Connection failed: " . $conn->connect_error;
        } 
        else 
        {
            $result = $conn->query($qry);
            if ($result->num_rows > 0)
            {
                while ($row = $result->fetch_assoc()) 
                {
                    $uid              = $row["id"];
                    $soid             = $row["socode"];
                    $cusype           = $row["customertp"];
                    $org              = $row["organization"];
                    $srctype          = $row["srctype"];
                    $project          = $row["project"];
                    $proj          = $row["projnm"];
                    
                    $cusid            = $row["customer"];
                    $orderdt          = $row["orderdate"];
					$makedt           = $row["makedt"];
                    $accmgr           = $row["accmanager"];
                    $invoice_amount   = number_format($row["invoiceamount"],2);
                    $vat              = number_format($row["vat"],2);
                    $tax              = number_format($row["tax"],2);

					$orderstatus               = $row["orderstatus"];
                    $st               = $row["status"];
                    $details          = $row["remarks"];
                    $note          = $row["note"];
                    $poc              = $row["poc"];//current user id
                    $oldsocode        = $row["oldsocode"];
                    $orgname          = $row["orgname"];
                    $oldsocode        = $row["oldsocode"];
                    $mrcdt            = $row["mrcdt"];
                    $deliveryamt      = $row["deliveryamt"];
                    $adj      		  = $row["adjustment"];
                    $vatt=$row["vat"];
                    
                }
            }
        }
        $mode = 2; //update mode
    } 
    else 
    {
        $uid              = '';
        $soid             = '';
        $cusid            = '';
        $orderdt          = date("d/m/Y");
        $accmgr           = '';
        $itdmu            = 1;
        $invoice_amount   = '0';
        $vat              = '0';
        $tax              = '0';
        $project        ='';
        $proj           ='';
        $srctype        ='';
        $st               = '';
        $effect_dt        = '';
        $details          = '';
        $poc              = '';
        $mrcdt            = ''; //$term_dt=date("Y-m-d")

        $mode = 1; //Insert mode
        
         $deliveryamt      = 0;
        $adj      = 0;
        $vatt=0;
    }

    $currSection = 'stock-transfer';
    $currPage    = basename($_SERVER['PHP_SELF']);
	
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>
<body class="<?=$_SESSION['pagestate'] ?> form soitem order-form <?=($res == 4)?'edit-mode':''?> <?=($res == 0)?'add-mode':''?>">
<style>

.c-vat{text-align: center;}
.c-qty{text-align: center;}
.c-price{text-align: right;}
.c-price-utt{text-align: right;}
.c-discount{text-align: center;}
.c-discounted-ttl{text-align: right;padding-right: 45px;}	
	
.ipspan{position: relative}
.ipspan span{
    display: block;
    
    
    background-color: rgb(212,218,221);
    position: absolute;
    z-index: 0;
    right: 0;
    top: 0;
    text-align: center;
    height: 35px;
    width: 35px;
    line-height: 35px;
    font-size: 12px;
}



.grid-sum-footer input{
    padding-right: 45px;
}	
	
</style>
<?php include_once 'common_top_body.php';    ?>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Transfer Stock</span>
        </div>
        <?php include_once 'menu.php'; ?>
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
                        <form method="post" action="common/addto.php"   enctype="multipart/form-data">
                        <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->
                        <!-- START PLACING YOUR CONTENT HERE -->
                            <div class="panel panel-info">
			                    <div class="panel-body panel-body-padding">
                                    <span class="alertmsg"></span>
                                        <div class="row form-header">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
          		                                <h6>Trnasfer Stock <i class="fa fa-angle-right"></i> <?=($mode == 1)?"New":"Edit"?></h6>
          		                            </div>
    
          		                            <div class="col-lg-6 col-md-6 col-sm-6">
          		                               <h6><span class="note"> (Field Marked <span class="redstar">*</span> are required)</span></h6>
          		                            </div>
                                        </div>
                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->
                                        <div class="row">
                                    	    <div class="col-sm-12">
        	                                    <!-- <h4>SO Information</h4>
        		                                <hr class="form-hr"> -->
        		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno; ?>">
        		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
        		                                 <input type="hidden"  name="po_id" id="po_id" value="<?php echo $soid; ?>">
            	                            </div>
                                            <div class="row no-mg">
                                            </div>
                                            
                                            <div class="col-sm-12">
                                                <h4>Tansfer Information  </h4>
                                                <hr class="form-hr">
                                            </div> 
                                            <div class="col-lg-2 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="po_id">TO ID</label>
                                                    <input type="text" class="form-control" placeholder="Auto Generated" name="po_id_vis" id="po_id_vis" value="<?php echo $toid; ?>" disabled>
                                                </div>
                                            </div>
                                            
                                    	    <div class="col-lg-3 col-md-6 col-sm-6">
        	                                    <label for="po_dt">Transfer Date<span class="redstar">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" name="to_dt" id="to_dt" value="<?php echo $todt; ?>" required>
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>
        
        
        
                                    	    <br>
                                            <div class="po-product-wrapper withlebel">
                                                <div class="color-block">
             		                                <div class="col-sm-12">
        	                                            <h4>Item Information  </h4>
        		                                        <hr class="form-hr">
        	                                        </div>
        <?php if($mode == 1) { //insert ?>
                                                    
        											
        										<div class="row form-grid-bls  hidden-md hidden-sm hidden-xs">
        											
        											
                                                        <div class="col-lg-4 col-md-5 col-sm-6">
                                                        	<h6 class="chalan-header mgl10"> Select Item <span class="redstar">*</span></h6>
                                                        </div>
        
        												<div class="col-lg-3 col-sm-1 col-xs-6">
        													<h6 class="chalan-header"> From <span class="redstar">*</span></h6>
        												</div>
        												<div class="col-lg-3 col-sm-1 col-xs-6">
        													<h6 class="chalan-header"> To <span class="redstar">*</span></h6>
        												</div>											
        
        
        
                                                        <div class="col-lg-2 col-md-1 col-sm-6">
                                                            <h6 class="chalan-header">QTY </h6>
                                                        </div>
                                                        
                                                </div>
        											
        											
        	                                        <div class="toClone">
                  	                                    <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
        													<label class="hidden-lg">Item Name</label>
                                                            <div class="form-group">
                                                               <!--input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"-->
                                                                <div class="form-group styled-select">
                                                                    <input type="text" list="itemName"  autocomplete = "off" name="itmnm[]"  class="dl-itemName datalist" placeholder="Select Item" required>
        															<input type="hidden" name="itemid[]" value="" class="itemName itemid">
                                                                    <datalist  id="itemName" class="list-itemName form-control"  >
                                                                        <option value="">Select Item</option>
            <?php 
        			//$qryitm = "SELECT `id`, `name`, round(`vat`, 2) vat, round(`ait`, 2) ait, round(`rate`, 2) rate, round(`cost`, 2) cost  FROM `item`  order by name";
        				
        			$qryitm = "SELECT i.id, i.name,i.barcode code, round(i.vat, 2) vat, round(i.ait, 2) ait, round(i.rate, 2) rate, round(i.cost, 2) cost , COALESCE(s.freeqty,0)freeqty
        						FROM item i
        						left  JOIN stock s ON i.id = s.product
        						order by i.name";								 
        									 
                $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                    $tid  = $rowitm["id"];
        			$code  = $rowitm["code"];
                    $nm   = $rowitm["name"];
                    $cost =$rowitm["rate"];
                    $up = $rowitm["rate"];
                    $vat  = $rowitm["vat"];
                    $ait  = $rowitm["ait"];
                    $prdcost=$rowitm["cost"];
        			$stock = $rowitm["freeqty"];
                    ?>
                                                                        
        																<option class="option-<?=$tid?>" data-value="<?=$tid?>" data-stock="<?=$stock?>" data-prdcost="<?php echo $prdcost; ?>" data-up="<?php echo $up; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" value="<?=$nm?>-[Cd: <?=$code; ?> | St: <?=$stock?>]"><?=$nm?>-[Cd: <?=$code; ?> | St: <?=$stock?>]</option>																
            <?php }} ?>
                                                                    </datalist>
                                                                </div>
                                                            </div>
                                                        </div> <!-- this block is for itemName-->
        												
        												
        												<div class="col-lg-3 col-md-2 col-sm-2 col-xs-8">
        												<label class="hidden-lg">Store*</label>
        													<select name="fromstore[]" id="fromstore" class="form-control fromstore" planceholder="Select Store" Required>
                                                                <option value=""> Select Store</option>
                                                            </select>
        												</div>
        												<div class="col-lg-3 col-md-2 col-sm-2 col-xs-8">
        												<label class="hidden-lg">Store*</label>
        													<select name="tostore[]" id="tostore" class="form-control tostore" planceholder="Select Store" Required>
        													<option value=""> Select Store</option>
        											    <?php
        											        $qryto = "select name, id from branch order by name";
        											        $resultto        = $conn->query($qryto);
        											        while ($rowto = $resultto->fetch_assoc()) {
        											            $brnm = $rowto["name"]; $brid = $rowto["id"];
        											    ?>
        											        <option value="<?= $brid ?>"> <?= $brnm ?></option>
        											    <?php } ?>
                                                            </select>
        												</div>
        												<div class="col-lg-2 col-md-1 col-sm-1 col-xs-4">
        													<label class="hidden-lg">Qty</label>
        													<div class="form-group">
        														<input  type="number"  autocomplete="off" required class="numonly calc c-qty form-control quantity_otc_ qty-chkstk" id="quantity_otc_" placeholder="Qty" name="quantity_otc[]" validateQuantity(this)>
        													</div>
        												</div>
        												
                                                    </div>
        <?php } ?>									
        											
        											
                                                </div>
        
        
        										<div class="row add-btn-wrapper">
        											<div class="col-sm-12">
        											<?php
        												//echo $mode;
        													$addClassName = ($mode == "1") ? 'link-add-po' : 'link-add-po-2';
        													?>
        												<a href="#" title="Add Item" class="link-add-order" ><span class="glyphicon glyphicon-plus"></span> </a>
        											</div>	
        										</div>
        										
                                                <br><br><br>
                                                
                                            </div>
        
                                            <div class="col-sm-12"> 
        											<input type="hidden" name="mode" value="<?=$mode?>">
        											<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
                                                    <?php if ($mode == 2) { ?>
                                                			
        													<input class="btn btn-lg btn-default" type="submit" name="postaction" value="Update Transfer Stock"  id="update" > 
        										
                                                  <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="copy" value="Copy SO" id="Copy"-->
                                                  <?php } else { // new insert ?>
    
        													<input class="btn btn-lg btn-default" type="submit" name="postaction" value="Transfer Stock"  id="confirm" > 										
        										
                                                  <?php } ?>
        
        												<input  class="btn btn-lg btn-warning top" type="button" name="postaction" value="Cancel" id="cancel"  onClick="location.href = 'quotationList.php?pg=1&mod=3'" >
        
                                            </div>
        
                                        </div>
							            <br>
                                        <br>
							

                        </div><!-- end panel body -->
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
include_once 'common_footer.php';
//$cusid = 3; ?>
<?php include_once 'inc_cmb_loader_js.php'; ?>



<script>
	
    $(document).ready(function(){
		
		//input number only validateion
		//put class .numonly to apply this. alpha will no take, only number and float
		
		$('.numonlyx').change(function(e){
			var xxxx = $(this).val();
			//alert(typeof(parseFloat(xxxx)));
		});
		
        //$('.numonly').keyup(function(e){
        $(document).on("keyup",".numonly", function(e){
			
		  if(/[^0-9.]/g.test(this.value))
		  {
			// Filter non-digits from input value.
			this.value = this.value.replace(/[^0-9.]/g, '');
			  
		  }
		});		

		
  
       
 
      

		
$(document).on("input", ".dl-itemName", function() {
  
  val = $(this).val();
  
  var root = $(this).closest('.toClone');
  
  var pid =  $('#itemName option[value="' + val +'"]').attr('data-value');

  if(pid){
      root.find('.itemid').val(pid);
      loadWarehouse(root, pid, 1, 0) // 1 means Newly created, 0 means because of its a new order, it does not conation any revision id
  }else{
      //root.find('.tostore').empty();
      root.find('.fromstore').empty();
  }
               
    
});		

$(document).ready(function() {
    // Listen for change event on fromstore select within .toClone
    $(document).on('change', '.toClone .fromstore', function() {
        // Get the selected store value
        var selectedQty = $(this).find('option:selected').data('qty');
        
        // Find the corresponding quantity input field within the same row
        var quantityInput = $(this).closest('.toClone').find('.quantity_otc_');
        
        // Update max attribute of quantity input based on selected store
        quantityInput.attr('max', selectedQty);
    });
});


function validateQuantity(input) {
    if (input.value > input.max) {
        input.value = input.max;
    }
}
		
		
    function loadWarehouse(root, pid, type, revision){
        //root.find('.tostore').empty();
        root.find('.fromstore').empty();

            $.ajax({
                type: "GET",
                url: "phpajax/load_store.php",
                data: { pid : pid},
                beforeSend: function(){
                    //root.find(".tostore").html("<option>Loading...</option>");
                    root.find(".fromstore").html("<option>Loading...</option>");
                },

                }).done(function(data){
                    //root.find(".tostore").html(data);
                    root.find(".fromstore").html(data);
                    //alert(data);
                            
                            
                    // Call initializeiCheck() function when the AJAX content is loaded
                    initializeiCheck();
                           
                });
    }
        
	
        
        //$(document).on("change", ".dl-itemName", function() {
		$(document).on("input", ".dl-itemNamex", function() {

            var val = $(this).val();
			//alert(val);
			
            var cost = $('#itemName option[value="' + val +'"]').attr('data-cost');
            var untprc = $('#itemName option[value="' + val +'"]').attr('data-up');
            var prdprc = $('#itemName option[value="' + val +'"]').attr('data-prdcost');
			
            //var untprc=cost.toFixed(2);
            $(this).closest('.toClone').find('.unitprice_otc').val(cost);
            $(this).closest('.toClone').find('.unitprice_otc1').val(untprc);
			$(this).closest('.toClone').find('.quantity_otc').val(1);
           
            $(this).closest('.toClone').find('.prodprice1').val(prdprc);
		
			var vat = $('#itemName option[value="' + val +'"]').attr('data-vat');
            $(this).closest('.toClone').find('.vat').val(vat);

            var ait = $('#itemName option[value="' + val +'"]').attr('data-ait');
            $(this).closest('.toClone').find('.ait').val(ait);
            
            var disc=0;
            disc = $(this).closest('.toClone').find('.discnt').val();
            var dscntdtotl=0;
            dscntdtotl+=+untprc*(1-disc*0.01);
            //alert(+dscntdtotl);
            $(this).closest('.toClone').find('.TotalAmount').val(untprc);
             $(this).closest('.toClone').find('.unitTotalAmount1').val(dscntdtotl);
            $(this).closest('.toClone').find('.unitTotalAmount').val(dscntdtotl);


	//alert(prdprc);
    var sum = 0; 
    var vatsum=0;
    var vatrate=0;
    var qty=0;
    var rate=0;
    var unitsum=0;
    $(".unitTotalAmount").each(function(){
		sum += +$(this).val(); 
	   sum1=sum.toFixed(2);
	   //alert(sum1);
         $("#grandTotal").val(sum.toLocaleString("en-US"));
         
         vatrate= $(this).closest('.toClone').find('.vat').val();
         vatsum += $(this).val()*vatrate*0.01;
         
         rate= $(this).closest('.toClone').find('.unitprice_otc').val();
         qty= $(this).closest('.toClone').find('.quantity_otc').val();
         
         unitsum+= (rate*qty);//-sum;
         
  }); 
  
  // $(".vat").each(function(){
	//	vatsum += +$(this).val(); 
	  // sum1=sum.toFixed(2);
	   //alert(sum1);
         
  //});

    var adj=0;
    var net=0;
    var dlv=0;
     var vattot=0;
     var discountsum=0;
    dlv+=$("#deliveryamt").val();
    vattot+=$("#vatt").val();
    
    adj+=$("#discntnt").val();
    net+=sum-adj-(-dlv)-(-vatsum);
    discountsum+=unitsum-sum;
    // net+=net+dlv;
    $("#grandTotalnet").val(net.toLocaleString("en-US"));
   $("#vatt").val(vatsum);
   //$("#vatdis").val(vatsum.toLocaleString("en-US"));
   //$("#discountdsp").val(discountsum.toLocaleString("en-US"));
//alert(dlv);

    });
    
    

})
  
    


</script>

<script>

//COPIER
	
$(document).ready(function() {
    var max_fields      = 500; //maximum input boxes allowed
    var wrapper         = $(".color-block"); //Fields wrapper
    var add_button      = $(".link-add-order"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper .toClone:last-child").clone().appendTo(wrapper);
    
    	$( ".po-product-wrapper .toClone:last-child input").val("");
  

		if(x==2){
			$( ".po-product-wrapper .toClone:last-child").append('<div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}

        }
        
        
        
        setTimeout(function(){
            
        //check already selected item and disable them.
        //var valuesArray = []; // Array to store the values

          $('.itemName').each(function() {
            var inputValue = $(this).val();
            //valuesArray.push(inputValue);
              
              //  $('.po-product-wrapper .toClone:last-child .option-'+inputValue).prop('disabled', 'disabled');
              //$('.withlebel .toClone:last-child .option-'+inputValue).prop('disabled', true);
              //$('.po-product-wrapper .toClone:last-child .option-'+inputValue).remove();
              $(document).on('click','.po-product-wrapper .toClone:last-child', function(){
                $(this).find(".option-"+inputValue).remove();
              });
          });  
            
            
        },200);
      
        
        
        
    });

    $(wrapper).on("click",".remove-order", function(e){ //user click on remove text
        e.preventDefault();
		$(this).closest(".toClone").remove();
		 OrderTotal();
		x--;
		
    })
});	
	
</script>	
	
	
	
<script>
	//Footer Fields width same as discounted field;
	
function footerfldwdth(){
	ftrfldwdth = $(".c-discounted-ttl").width();
	$(".grid-sum-footer input").width(ftrfldwdth);
}
setTimeout(footerfldwdth,300);

window.addEventListener("resize", () => {
		footerfldwdth();
});	
	
	

var classes = ".grid-sum-footer input, .c-discounted-ttl"

$( "<span>৳</span>" ).insertAfter(classes);
$(classes).parent().addClass("ipspan");

</script>	
	
<script>
$(document).ready(function(){
	
//show INVOICE
	
	$(".revision-tbl").on("click",".show-invoice.btn",function(){
		
  	mylink = $(this).attr('href')+"?qrid="+$(this).data('qrid')+"&socode="+$(this).data('socode')+"&qtype=revision";
	
   //alert(mylink);
  
  
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: 'QUOTATION ID #'+$(this).data('socode'),
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: false, // <-- Default value is false
							cssClass: 'show-invoice',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Cancel',
								action: function(dialog) {
									dialog.close();	
									/*
									$("#printableArea2").printThis({
										importCSS: true, 
										importStyle: true,
									});
									*/
									
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Print',
								action: function(dialog) {
									
									$("#printableArea2").printThis({
										importCSS: false, 
										importStyle: true,
									});
		
									
									dialog.close();	
									
									},
								
							}],
							onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});		
		
	
});
    
    
   
    
</script>



<script>
    
    
    
  $(document).ready(function() {
 // $(document).on("submit", "#Quotationform", function(event) {
    $('#saverevision_no').click(function(e){
    e.preventDefault();
    // Your code here
       var isValid = true;
        
        $(".toClone .qtnqrapper").each(function(){
           alert("im here");
            var grandQty = $(this).find('.c-qty');
            
            $(this).find(".row").each(function(){
                
                 
                 var quantityInput = $(this).find('.quantity');
                 var quantityValue = parseInt(quantityInput.val(), 10);
                 var deliveryDateValue = $(this).find('.delivery-date').val();
                if (quantityValue > 0 && deliveryDateValue.trim() === '') {
                    alert('Invalid quantity or Delivery date. Please fix first');
                    grandQty.trigger("click");
                    return false;
                    isValid = false;
                }
            });
        });
        
        if(isValid){
        $('#Quotationform').submit();
        }
      
  });
});   
    

   
    
    
//show delivery date required if not entered on submit;	

            
            
 $(document).on("submit","#Quotationformx", function(event) {
     
     
     event.stopPropagation();
        //event.preventDefault();
    
        var isValid = true;

       alert(1);
      
    $(this).find('.quantity').each(function() {
        
      var quantityInput = $(this);
          
          var deliveryDateInput = quantityInput.closest('.row').find('.delivery-date');
          var grandQty = quantityInput.closest('.toClone').find('.c-qty');

          var quantityValue = parseInt(quantityInput.val(), 10);
          var deliveryDateValue = deliveryDateInput.val();

          if ((quantityValue > 0 && deliveryDateValue.trim() === '')) {

            grandQty.trigger("click");

            isValid = false;
            return false; // Exit the loop early
          }

        
    });

    if (!isValid) {
      event.preventDefault(); // Prevent form submission
      alert('Invalid quantity or Delivery date. Please fix first');
       
    }

  
  });

//});

//Designation
$(document).ready(function(){
             //Input Click
  $('.input-box4').focus(function(){
    $(this).select();
  });
  
            $('.input-box4').on("focus click keyup", function(){
                 //console.log("d1");
                 $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
                // $(this).find('.ds-add-list').attr('style','display:none');
            });

            //Option's value shows on input box
  					$('.input-ul4').on("click","li", function(e){
               // console.log(this);
                if(!$(this).hasClass("addnew")){
                        let litxt= $(this).text();
                        let lival= $(this).val();

                        $("#desig").val(lival);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box4').val(litxt);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box4').attr('value',litxt);
                        $(this).closest('.ds-list').attr('style','display:none'); 
                }
            });
			
			function addNew(e){
                $(e).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
                $(e).closest('.ds-list').attr('style','display:none');				
			}
			
            // New input box display

            $('.input-ul4 .addnew').click(function(){
				addNew(this);
                
            });
			
			$(".ds-cancel-list-btn").click(function(){ 
				$(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:none');
			 });

            // New-Input box's value display on old-input box

            $('.ds-add-list-btn-desi').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
				if(x.length>0){
                $(this).closest('.ds-divselect-wrapper').find('.input-box4').attr('value', x);
				$(this).closest('.ds-divselect-wrapper').find('.input-box4').val(x);
                $(this).closest('.ds-add-list').attr('style','display:none');
                //$(this).closest('.ds-add-list').find('.addinpBox').val('');
                console.log($(this).closest('.ds-add-list').find('.addinpBox').val(""));
                // alert(x);
                // }
                action(x);
                function action(x){
                    $.ajax({
                        url:"phpajax/divSelectAll.php",
                        method:"POST",
                        data:{newItem: x, type: 'project'},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#desig").val(res.id);
                                $('.display-msg').html(res.name);
                                $('.input-box4').attr('value',res.name);
								$("#inpUl4").append("<li class='pp1' value = '"+res.id+"'>"+res.name+"</li>");
                            }
                    });
	             }
			}else{ 
				alert('Please enter a Project name');
			}
            });

            $(document).mouseup(function (e) {
				
                if ($(e.target).closest(".ds-list").length === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length  === 0) {
                    $(".ds-add-list").hide();
                }
            });

            $('.input-box4').on("keyup", function(e) {
			   
			    		var searchKey = $(this).val().toLowerCase();
              
             // if(searchKey.length>0){
                
                $("#inpUl4 li").filter(function() {
                	$(this).toggle($(this).text().toLowerCase().indexOf(searchKey) > -1);
                  
                  		if(e.keyCode == 40){
                        $('#inpUl4 li').removeClass('active');
                        $(this).next().focus().addClass('active');
                        return false;
                      } 
                });
                
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			   			 $(this).closest('.ds-divselect-wrapper').find('.input-ul4 li').click(function(){
			    

					// console.log(this)
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
                        $(this).closest('.ds-divselect-wrapper').find(".input-box4").val(x);
                        $(this).closest('.ds-list').attr('style','display:none');
                     
                    }
					
                })
           // }
                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){

                    $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                    $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
                });
				
				
					 if (e.keyCode == 40){  
					 //alert("Enter CLicked");
					 $('#inpUl4 li').first().focus().addClass('active');
				 }
              
	            

			});

	$('#inpUl4').on('focus', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){ 
      
      $this = $(this);
      $('#inpUl4 li').removeClass('active');
			$this.addClass('active');
			$this.closest('#inpUl4').scrollTop($this.index() * $this.outerHeight());
    }
    
    }).on('keydown', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){
      $('#inpUl4 li').removeClass('active');
		$this = $(this);
		if(e.keyCode == 40){
      $('#inpUl4 li').removeClass('active');
			$this.next().focus().addClass('active');
			return false;
		} else if (e.keyCode == 38){        
			$this.prev().focus().addClass('active');
			return false;
		}
    
  }
	}).find('li').first().focus();	

  
  			$('#inpUl4').on("keyup","li", function(e) {
				if (e.keyCode == 13){
          var txt = $(this).text();
					//alert(txt);
          if(!$(this).hasClass("addnew")){

          
          var tval= $(this).val();

          $("#desig").val(tval);              
          $('.input-box4').val(txt);
          $('.input-box4').focus();
          $('.ds-list').attr('style','display:none');
          }
				}
			});	
			
});
</script>
<!--script>
    
    $(document).on("change", "#saletype,", function() {
     tp=$(this).val();
    alert("yes");
    
    /*if(val=='1')
    {
        document.getElementById("projdiv").style.display = "none";
    }
    else
    {
        document.getElementById("projdiv").style.display = "block";
    
    }*/
    
  
    // sum1=$("#grandTotal").val();
    
   // alert(net);
});
    
</script -->

<script>
    
    $(document).on("change", "#saletype", function() {
    tp=$(this).val();
    
    if(tp=='1')
    {
       //alert(tp);
        document.getElementById("projdiv").style.display = "none";
        $("#proj").val("0")
        $("#desig").val("0")
    }
    else
    {
        document.getElementById("projdiv").style.display = "block";
    
    }
    
    /*var adj=0;
    var net=0;
    var sum1=0;
    var dlv=0;
    var vats=0;
     var discountsum=0;
    
    dlv+=$("#deliveryamt").val();
     adj = $("#discntnt").val();
     vats=$("#vatt").val();
     sum1=$("#grandTotal").val();
     net+=sum1-adj-(-dlv)-(-vats);
     $("#grandTotalnet").val(net.toLocaleString("en-US"));
   // alert(net);*/
});
    
</script >
</body>
</html>
<?php } ?>