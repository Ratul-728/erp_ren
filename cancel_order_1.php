<?php
//print_r($_REQUEST);
//exit();
session_start();

require "common/conn.php";
include_once('rak_framework/fetch.php');

$usr = $_SESSION["user"];
//echo $usr;die; 

//ini_set('display_errors', 1);

if (!$_SESSION["user"]) 
{
    header("Location: " . $hostpath . "/hr.php");
}
else 
{
    $res       = $_GET['res'];
    $msg       = $_GET['msg'];
    
    $totamount = 0;
    $itdgt=0;
    $discttot=0;
   
$orderID = $_POST["orderid"];

   	$soid = $_REQUEST['socode'];
	$rid = $_REQUEST['rid'];
        
    $qry = "SELECT s.`id`,s.orderstatus,s.`socode`,	s.`organization`, s.project,p.name projnm,s.srctype,s.`customer`,DATE_FORMAT(s.`orderdate`,'%e/%c/%Y') `orderdate`,
			s.`deliveryamt`,s.`accmanager`, s.`vat`,s.`tax`,s.`invoiceamount`, s.`makeby`,s.`makedt`,s.`status`,s.`remarks`,s.`poc`,
			s.`oldsocode`,DATE_FORMAT(s.mrcdt,'%e/%c/%Y') mrcdt, o.name orgname,adjustment,s.note 
            FROM `quotation` s 
			left join organization o ON o.id = s.organization 
			left join project p on s.project=p.id
			where  s.socode= " . $orderID;
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
    
    $currSection = 'cancelorder';
    $currPage    = basename($_SERVER['PHP_SELF']);
	
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>
<body class="quotation form soitem order-form edit-mode">
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


/*
select2 with picture css
*/
.order-form .toClone .styled-select{
 height: 35px;
}
.order-form .select2-selection__arrow{
   border:0px solid red;
    width: 34px!important;
    height: 34px!important;
    background-color: #efefef;
}
.order-form .select2-selection__rendered{
    background-color: transparent;
    border-radius: 0;
    padding: 2px;
    
}

/* Style the Select2 container */
.order-form .select2-container {
    background-color: transparent;
    width: 100%!important;
    height: 35px;
}

.order-form .select2-container .select2-selection img{
    width: 34px;
    height: 34px;
    margin-right: 10px; 
    margin-left: -8px; 
    margin-top: -2px;
    border: 1px solid rgb(255,255,255);
}

/* Set the border and height for the selection area */
.order-form .select2-selection {
    border: 0px solid #efefef!important;;
    height: 35px!important;;
}

/* Set the border for the dropdown container */
.order-form .select2-dropdown {
    border: 1px solid #efefef;
}





/* option */
.order-form .select2-results__option {
    height: 60px; /* Set your desired height */
}
.order-form .select2-results__option .img-wapper img{
   display: block;
    width:60px;
    height: 60px;
    margin-left: -5px;
    
        padding: 5px!important;
    
}
/* select-img */

.order-form .select2-results__option span{
    padding-left: 0px;
}
.order-form .select2-results__option {
    height: 60px;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #efefef;

}

.order-form .select2-results__option img {
    width: 40px;
    height: 40px;
    margin-right: 10px; 
}

.order-form .select2-results__option span {
    display: flex;
    flex-grow: 1;
    align-items: center;
}
.order-form .select2-search{
    background-color: #fff!important;
}
.order-form .select2-search__field{
    border-bottom: 1px solid #efefef!important;
    height: 40px;
    font-size: 18px;
    padding: 10px!important;
}


.order-form .select2-search__field:focus {
    outline: none;
    border: 1px solid #ccc!important; /* Add a border color to replace the default focus border */
}

.order-form .select2-results__message{
    padding-left: 20px;
    color: red;
}

.order-form .select2-results__option:hover {
    background-color: #094446!important;
    color: #fff!important;
}

.order-form .select2-results__option:hover span {
    color: #fff!important;
}



/*
end select2 with picker 
*/
.saletype{
    font-family: arial;
}

</style>

<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />

<?php include_once 'common_top_body.php';    ?>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Cancel Order</span>
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
                        <!--form method="post" action="common/cancelorder_post.php"  id="Cancelform"  enctype="multipart/form-data"-->
                        <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->
                        <!-- START PLACING YOUR CONTENT HERE -->
                            <div class="panel panel-info">
			                    <div class="panel-body panel-body-padding">
                                    <span class="alertmsg"></span>
                                        <div class="row form-header">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
          		                                <h6>Quotations <i class="fa fa-angle-right"></i> Cancel  Quotation </h6>
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
                                                <h4>Cancel Information  </h4>
                                                <hr class="form-hr">
                                            </div> 
                                            <div class="col-sm-12">
                                                <div class="col-lg-3 col-md-6 col-sm-6">
            	                                    <label for="po_dt">Order ID</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="cmbempnm" id="cmbempnm" value="<?= $orderID ?>"  >
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                      <label for="email"> </label>
                                                    <div class="form-group">
                                                        <input class="btn btn-lg btn-default" type="button" name="find" value="Get"  id="find" > 
                                                    </div>
                                                </div>
                                            </div>
                                            
                                    	    <div class="col-lg-2 col-md-6 col-sm-6">
        	                                    <label for="po_dt">Order Date</label>
                                                <div class="input-group">
                                                    <input readonly type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>" disabled>
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <?php
                                                        $srctype = ($srctype)?$srctype:1;
                                                    ?>
                                                    <label for="saletype">Sales Type </label>
                                                    <select name="saletype" id="saletype" class="form-control saletype" disabled>
                                                        <option value="1" <?php if($srctype==1){echo "selected";} ?>> Retail </option>
                                                        <option value="2" <?php if($srctype==2){echo "selected";} ?>> Project </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6" id="projdiv" style="display: none;"> 
                                                <div class="form-group">
                                                    <label for="cmbcontype">Project </label>
                                                    <div class="ds-divselect-wrapper cat-name">
                                                        <select name="saletype" id="saletype" class="form-control saletype" disabled>
                                                            <option value="1" > <?php echo $proj ?> </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12"></div>
                                                
                                            <div class="col-lg-3 col-md-6 col-sm-6"> 
                                                <div class="form-group">
                                                    <label for="cmbcontype">Customer</label>
                                                    <div class="ds-divselect-wrapper cat-name">
                                                        <select name="saletype" id="saletype" class="form-control saletype" disabled>
                                                            <option value="1" > <?php echo $orgname ?> </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
              	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="cmbsupnm">Contact Name</label>
                                                    <div class="form-group styled-select">
                                                        <select name="cmbsupnm" id="cmbsupnm" class="cmd-child form-control" disabled>
                                                            <option value="">Select Name</option>
                                                            <?php $qrycont = "SELECT `id`, `name`  FROM `contact`  WHERE `contacttype`=1 and id=$cusid order by name";
                                                            $resultcont = $conn->query($qrycont);
                                                            if ($resultcont->num_rows > 0)
                                                            {
                                                                while ($rowcont = $resultcont->fetch_assoc()) 
                                                                {
                                                                    $tid = $rowcont["id"]; $nm  = $rowcont["name"];
                                                            ?>
                                                                    <option value="<?php echo $tid; ?>"><?php echo $nm; ?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                    	    <br>
                                            <div id = "loadhere" class="po-product-wrapper withlebel">
                                                <div class="color-block">
             		                                <div class="col-sm-12">
        	                                            <h4>Item Information  </h4>
        		                                        <hr class="form-hr">
        	                                        </div>
                                                </div>
        
                                                <div class="row form-grid-bls hidden-md hidden-sm hidden-xs">
                                                    <div class="col-lg-5 col-md-5 col-sm-6">
                                                    	<h6 class="chalan-header mgl10"> Select Item</h6>
                                                    </div>
    												<div class="col-lg-2 col-sm-2 col-xs-6">
    													<h6 class="chalan-header"> Price</h6>
    												</div>
    												<div class="col-lg-2 col-sm-2 col-xs-6">
    													<h6 class="chalan-header"> Order Quantity</h6>
    												</div>
    												<div class="col-lg-2 col-sm-2 col-xs-6">
    													<h6 class="chalan-header"> Cancel Quantity</h6>
    												</div>
                                                </div>											
    											<div class="clonewrapper">
<?php
$itmdtqry= "SELECT a.`id`, a.`socode`, a.`sosl`, a.`productid`, a.`mu`, round(a.`qty`,0) qty
            ,round(a.`qtymrc`,0)qtymrc, round(a.`otc`,2) otc, round(a.`mrc`,2)mrc,
                    a.`remarks`, a.`makeby`, a.`makedt`,a.`currency`,a.vatrate vat
                    ,a.aitrate ait, b.name itmname,b.barcode,COALESCE(s.freeqty,0)freeqty
                    ,a.discountrate,a.discounttot 
                    FROM `quotation_detail` a 
                    LEFT JOIN item b ON a.`productid` = b.id 
                    LEFT JOIN stock s ON a.productid = s.product
                    WHERE `socode`='" . $soid . "' ORDER BY a.sosl ASC";
    $resultitmdt = $conn->query($itmdtqry);
    if ($resultitmdt->num_rows > 0) 
    {
        while ($rowitmdt = $resultitmdt->fetch_assoc())
        { 
        $order_detail_id  = $rowitmdt["id"];
        $itmdtid  = $rowitmdt["productid"];
        $itdmu    = $rowitmdt["mu"];
        $itdqu    = $rowitmdt["qty"];
        $itdqumrc = $rowitmdt["qtymrc"];
        $itdotc   = $rowitmdt["otc"];
        $itdmrc   = $rowitmdt["mrc"];
        $itdrem   = $rowitmdt["remarks"];
        $currency = $rowitmdt["currency"];
        $itvat    = $rowitmdt["vat"];
        $itait    = $rowitmdt["ait"];
        $itmname  = $rowitmdt["itmname"];
        $code  = $rowitmdt["barcode"];
        $freeqty  = $rowitmdt["freeqty"];
        $discountrate  = $rowitmdt["discountrate"];
        $discounttot  = $rowitmdt["discounttot"];
        $cost  = $rowitmdt["cost"];
        $itdtot   = number_format(($itdqu * $itdotc) + ($itdqumrc * $itdmrc),2);
        $itdup   = ($itdqu * $itdotc) + ($itdqumrc * $itdmrc);
        $itdgt    = $itdgt + $discounttot;
        $discttot=$itdgt-$adj;
        
        $totalcost=$totalcost+($itdqu*$itdotc);
        $netamount=$itdgt;
        
        //new code, rak, vat amount;
        $orVATRate = $itvat;
        $orPrice = $itdotc;
        $orQty = $itdqu;
        $orDicntRate = $discountrate;
        
        $OrUnitTotal = $orPrice*$orQty;
        $OrDiscountAmout = ($OrUnitTotal*$orDicntRate)/100;
        $OrAmountWithDiscount = $OrUnitTotal - $OrDiscountAmout;
        $OrVATAmout = ($OrAmountWithDiscount*$orVATRate)/100;
        $OrSubtotal =  $OrSubtotal+ $OrAmountWithDiscount;	
?>
                                                    <div class="toClone" >
                                                        <div class="col-lg-5 col-md-5 col-sm-3 col-xs-12"> 
                                                            <label class="hidden-lg">Item Name</label>
                                                            <div class="form-group">
                                                                <div class="form-group styled-select">
                                                                    <input type="hidden" name="itemName[]" value="<?php echo $itmdtid; ?>" class="itemName">
                                                                    <select   class="productname form-control" disabled>
                                                                        <option value="<?php echo $itmdtid; ?>"><?php echo $itmname; ?></option>
                                                                    </select> 
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        
                                                        <div class="col-lg-2 col-md-2 col-sm-7 col-xs-8">
                                                            <label class="hidden-lg">Price</label>
                                                            <div class="form-group">
                                                                <input  type="text" class="calc  c-price form-control unitprice_otc1_ unitPriceV2_" placeholder="Price" id_="unitprice_otc1" value="<?=$itdotc?>" name="unitprice_otc1[]" readonly>
                                                            </div>
                                                        </div>	
                                                        
                                                        <div class="col-lg-2 col-md-2 col-sm-5 col-xs-4">
                                                            <label class="hidden-lg">Order Qty</label>
                                                            <div class="form-group">
                                                                <input type="text"  autocomplete="off"  class="calc c-qty form-control quantity_otc_"  id_="quantity_otc" value="<?php echo $itdqu; ?>" name="quantity_otc[]" readonly>
                                                            </div>
                                                        </div>
                                                        V<div class="col-lg-2 col-md-2 col-sm-5 col-xs-4">
                                                            <label class="hidden-lg">Cancel Qty<span class="redstar">*</span></label>
                                                            <div class="form-group">
                                                                <input type="text"  autocomplete="off"  required class="cancelqty"  id="cancelqty"  name="quantity_cancel[]">
                                                            </div>
                                                        </div>
                                                        
                                                            <?php if ($rCountLoop > 0) { ?>
                                                            <!--div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div-->
                                                            <?php } $rCountLoop++; ?>
                                                    </div>
<?php 
        }
    }
?>
                                                </div>
                                                
                                            </div>
                                        </div>
							            <br>
                                        <br>
                                </div>
                            </div>
                        <!--/form-->
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

<script src="js/plugins/select2/select2.min.js"></script>

<script>
    $(document).ready(function() {
      // Add a click event listener to the button
      $('#find').click(function() {
        // Retrieve the value of the button
        var order = $('#cmbempnm').val();
        
        //$('#actionportion').empty();
        $('#loadhere').empty();
        
        $.ajax({
            url:"phpajax/returnorderajax.php",
            method:"POST",
            data:{orderid: order},
            //dataType: 'JSON',
            success:function(res)
            {
                
				$("#loadhere").append(res);
                            

            }
        });
        
      });
      
    });
</script>

</body>
</html>
<?php } ?>