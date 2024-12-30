<?php
require "common/conn.php";
ini_set('display_errors',0);

session_start();
$usr = $_SESSION["user"];
$mod = $_REQUEST['mod'];
$invid = $_REQUEST['invid'];

//print_r($_REQUEST);
//die;
if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {
	
	
	
	//update status on confirm;
	if($_POST['updateconfirm'] == 'Confirm'){
		
		
		if($_POST['invid']){
			
			require_once("rak_framework/fetch.php");
			require_once("rak_framework/edit.php");
			$invid = $_POST['invid'];
			$soid  = fetchByID('invoice','invoiceno',"$invid",'soid');	
		
			$stsUdtQry = 'socode="'.$soid.'"';
			
			if(updateByID('soitem','orderstatus',2,$stsUdtQry)){
				$msg = "Order confirmed successfully";
				//print_r($_REQUEST);
				//die;
			}
		}
	}
	if($_POST['updatebook'] == 'Book'){
		
		
		if($_POST['invid']){
			
			require_once("rak_framework/fetch.php");
			require_once("rak_framework/edit.php");
			$invid = $_POST['invid'];
			$soid  = fetchByID('invoice','invoiceno',"$invid",'soid');	
		
			$stsUdtQry = 'socode="'.$soid.'"';
			
			if(updateByID('soitem','orderstatus',9,$stsUdtQry)){
				$msg = "Order booked successfully";
				//print_r($_REQUEST);
				//die;
			}
		}
	}		
	
	
	

    $qry = "select  inv.invoiceno,inv.invoicedt,inv.invoicemonth,inv.soid,inv.organization,o.name orgnm,soitm.deliveryamt
,o.street,o.area,area.name arnm,o.district,ds.name dsnm,o.state,st.name stnm,o.zip,o.country,cn.name cnnm,
o.contactno,o.email,o.website,ofc.Name ofcnm,ofc.street ofcst,ofc.area ofcar,ofc.email ofceml,ofc.web ofcweb, soitm.remarks delivto,inv.adjustment,soitm.orderstatus
from invoice inv
left join organization o on inv.organization=o.id
left join area on o.area=area.id left JOIN district ds on o.district=ds.id
left join state st on o.state=st.id left join country cn on o.country=cn.id
left join soitem soitm on soitm.socode = inv.soid
,companyoffice ofc
where inv.invoiceno='" . $invid . "'";
	
/*	
    $qry="select  inv.invoiceno,inv.invoicedt,inv.invoicemonth,inv.soid,inv.organization,o.name orgnm
,o.street,o.area,area.name arnm,o.district,ds.name dsnm,o.state,st.name stnm,o.zip,o.country,cn.name cnnm,
o.contactno,o.email,o.website,ofc.Name ofcnm,ofc.street ofcst,ofc.area ofcar,ofc.email ofceml,ofc.web ofcweb
from invoice inv
left join organization o on inv.organization=o.id
left join area on o.area=area.id left JOIN district ds on o.district=ds.id
left join state st on o.state=st.id left join country cn on o.country=cn.id
,companyoffice ofc
where inv.invoiceno='".$inv."'";	
	*/
	
	
    //echo  $qry;die;
    $resultinv = $conn->query($qry);
    if ($resultinv->num_rows > 0) {
        while ($rowinv = $resultinv->fetch_assoc()) {
            $invno   = $rowinv["invoiceno"];
            $invdt   = $rowinv["invoicedt"];
            $invmnth = $rowinv["invoicemonth"];
            $sof     = $rowinv["soid"];
            $orgnm   = $rowinv["orgnm"];
            $strt    = $rowinv["street"];
            $arnm    = $rowinv["arnm"];
            $dsnm    = $rowinv["dsnm"];
            $stnm    = $rowinv["stnm"];
            $zip     = $rowinv["zip"];
            $cnnm    = $rowinv["cnnm"];
            $invcnt  = $rowinv["contactno"];
            $inveml  = $rowinv["email"];
            $invweb  = $rowinv["website"];
            $ofcnm   = $rowinv["ofcnm"];
            $ofcst   = $rowinv["ofcst"];
            $ofcar   = $rowinv["ofcar"];
            $ofceml  = $rowinv["ofceml"];
            $ofcweb  = $rowinv["ofcweb"];
            $deliveryto  = $rowinv["delivto"];
			$deliveryamt  = $rowinv["deliveryamt"];
		    $discount  = $rowinv["adjustment"];
		    $ordst  = $rowinv["orderstatus"];
        }
    }
}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
include_once 'common_header.php';
?>
<body class="dashboard">

<?php
include_once 'common_top_body.php';
?>

<div id="wrapper">
  <!-- Sidebar -->
  <div id="sidebar-wrapper" class="mCustomScrollbar">

  <div class="section">
  	<i class="fa fa-group  icon"></i>
    <span>Buiesness POS</span>
  </div>
  <?php
include_once 'menu.php';

?>
	<div style="height:54px;">
	</div>


  </div>
  <!-- /#sidebar-wrapper -->
  <!-- Page Content -->
  <div id="page-content-wrapper">
    <div class="container-fluid xyz">
      <div class="row">
        <div class="col-lg-12">
        <p>&nbsp;</p><p>&nbsp;</p>
          <p>
          <!-- START PLACING YOUR CONTENT HERE -->

        	<div id="printableArea" style="-webkit-print-color-adjust: exact !important;" >
		  		<div class="invoice-wrapper"  style="width:800px;border:2px solid #c0c0c0;padding: 50px; ">
			<table width="100%" align="center" border="0" class="tbl_lbl1 tbl1" cellspacing="0" cellpadding="0">
				  <tr>
					  <td width="70%"><img class="img-width-control img-small" src="./assets/images/site_setting_logo/<?=$comlogo ?>" alt=""></td>
					  <td align="right"><h1>INVOICE</h1></td>
				  </tr>
				  <tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td>
						<div>
						  <?php echo $comname; ?> <br> <?php echo $comaddress; ?> <br>
					    <?php echo $comcontact; ?> <br> <?php echo $comemail; ?> <br> <?php echo $comweb; ?>
						</div>
					  </td>
					  <td>
					    <table cellspacing="0" class="tbl_lbl2" cellpadding="0" align="right">
					    <tr>
					      <td>Date</td>
					      <td>: <?php echo $invdt; ?></td>
				        </tr>
					    <tr>
					      <td>Invoice #</td>
					      <td>: <?php echo $invno; ?></td>
				        </tr>
					    <tr>
					      <td>Order Number</td>
					      <td>: <?php echo $sof; ?></td>
				        </tr>
					    <tr>
					      <!--td>For Month</td>
					      <td>: <?php echo date('F', mktime(0, 0, 0, $invmnth - 1, 10)); ?></td-->
				        </tr>
				      </table>

					  </td>
			    </tr>
		    </table>
			  <br>

			<table width="100%" border="0"  class="tbl_lbl1  tbl2" cellspacing="0" cellpadding="0">
				  <tbody>
				    <tr>
				      <th width="50%">Bill To:</th>

				      <th width="50%" style="padding-left:20px;">Delivery Address: </th>
			        </tr>
				    <tr>
				      <td valign="top">
						  
    						 <?php echo $orgnm; ?> <br>
    						 <?php echo $strt; ?> <br>
    					<?php if($arnm != ""){ ?>
    					    <?php echo $arnm . " , " . $dsnm . " , " . $stnm . "-" . $zip; ?> <br>
    					<?php } ?>
    					    <?php echo $cnnm; ?> <br>
    					    Phone: <?php echo $invcnt; ?> <br>
    				        <?php echo $inveml; ?> <br>
    				        <?php if ($invweb != "NULL") {echo $invweb;}; ?>
				    </td>
				    <td valign="top" style="padding-left:20px;">
    					    <?php if ($deliveryto=='') {?>
    					        <?php echo $orgnm; ?> <br>
    						 <?php echo $strt; ?> <br>
    					<?php if($arnm != ""){ ?>
    					    <?php echo $arnm . " , " . $dsnm . " , " . $stnm . "-" . $zip; ?> <br>
    					<?php } ?>
    					    <?php echo $cnnm; ?> <br>
    					    Phone: <?php echo $invcnt; ?> <br>
    				        <?php echo $inveml; ?> <br>
    				        <?php if ($invweb != "NULL") {echo $invweb;}; ?>
    				        <?php } else { ?>
    					    <?= $deliveryto ?>
    					    <?php } ?>

					  </td>
			        </tr>
			      </tbody>
		    </table>
		    <br>&nbsp;
<style>

.tbl-invoice {
    display: block;
}


    .tbl-invoice td,.tbl-invoice th{
    border:1px solid #000;
    
}

@media print {
    .tbl-invoice td,.tbl-invoice th{
    border:1px solid #000;
    
}
}
</style>
			<table width="100%" border="0"  class="tbl_lbl1 tbl-invoice  tbl3" cellspacing="10" cellpadding="10">
				<?php
$padding2x5 = 'style="padding: 2px 5px;"';

?>
							<tbody>

<!--						Start Loop here		-->

							  <tr>
								<th width="5%" align="center" nowrap <?=$padding2x5 ?>>Sl</th>
								<th align="center" nowrap <?=$padding2x5 ?>>Photo</th>
								<th align="center" nowrap <?=$padding2x5 ?>>Items</th>
								<!--th width="10%" align="center" style="text-align: center" nowrap <?=$padding2x5 ?>>Bill type</th-->
								<!--th width="10%" align="center" style="text-align: center" nowrap <?=$padding2x5 ?>>Currency</th-->
								<th width="5%" align="center" style="text-align: center" nowrap <?=$padding2x5 ?>> Qty</th>
								<th width="10%" align="center" nowrap <?=$padding2x5 ?>>Unit price</th>
								<th width="10%" nowrap <?=$padding2x5 ?>>Amount</th>
									<th width="10%" nowrap <?=$padding2x5 ?>>Discount rate</th>
										<th width="10%" nowrap <?=$padding2x5 ?>>Discounted Total</th>
								<!--th width="10%" align="center" style="text-align: center" nowrap <?=$padding2x5 ?>>VAT</th>
								<th width="10%" align="center" style="text-align: center" nowrap <?=$padding2x5 ?>>AIT</th>
								<th width="10%" nowrap <?=$padding2x5 ?>>Total </th-->
							  </tr>

<?php
$tot    = 0;
$totvat = 0;
$totait = 0;
$dqry   = "select d.sosl,d.socode,d.product,i.name pnm,d.vat,d.ait
,(case when d.billtype= 1 then 'OTC' else 'MRC' END ) bltp
, d.qty
,d.amount
,cr.shnm
,d.discountrate,d.discounttot,i.`image`
from invoice s left join invoicedetails d on s.invoiceno=d.invoiceno
left join item i on d.product=i.id
left join currency cr on d.currency=cr.id
where s.invoiceno='" . $invno . "' order by d.sosl";

//echo $dqry;die;
$resultd = $conn->query($dqry);
if ($resultd->num_rows > 0) {
    while ($rowsd = $resultd->fetch_assoc()) {$sosl = $rowsd["sosl"];
        $prod                             = $rowsd["product"];
        $pnm                             = $rowsd["pnm"];
        $tp                              = $rowsd["bltp"];
        $q                               = $rowsd["qty"];
        $crr                             = $rowsd["shnm"];
        $itmvat                          = $rowsd["vat"] ;
        $amt                             = (($rowsd["discounttot"]+$itmvat)/$q)*100/(100-$rowsd["discountrate"]);
        $totamt                          = ($q * $amt);
        $itmait                          = $rowsd["ait"] ;
        $discrate                        = $rowsd["discountrate"] ;
        $disctot                          = $rowsd["discounttot"] +$itmvat;
        $net                             = $totamt;// + $itmvat + $itmait;
        $tot                             = $tot + $disctot;
        $totvat                          = $totvat + $itmvat;
        $totait                          = $totait + $itmait;
        
                 
                if ( strlen($rowsd["image"]) > 0){
        		    $photo= "assets/images/products/300_300/".$rowsd["image"];
        		}else{
        			$photo= "assets/images/products/placeholder.png";
        		}
        ?>

							  <tr>
								<td align="center" <?=$padding2x5 ?>><?php echo $sosl; ?></td>
								<td align="center" width="10"><img src="<?php echo $photo?>"  height="90"></td>
								
								<td <?=$padding2x5 ?>> <?php echo $pnm; ?></td>
								<!--td <?=$padding2x5 ?>> <?php echo $tp; ?></td-->
								<!--td <?=$padding2x5 ?>> <?php echo $crr; ?></td-->
								<td <?=$padding2x5 ?> align="center"><?php echo number_format($q, 0, ".", ","); ?></td>
								<td align="right" <?=$padding2x5 ?>><?php echo number_format($amt, 2, ".", ","); ?></td>
								<td align="right" <?=$padding2x5 ?>><?php echo number_format($totamt, 2, ".", ","); ?></td>
								<td align="right" <?=$padding2x5 ?>><?php echo number_format($discrate, 2, ".", ","); ?></td>
								<td align="right" <?=$padding2x5 ?>><?php echo number_format($disctot, 2, ".", ","); ?></td>
								<!--td <?=$padding2x5 ?>><?php echo number_format($itmvat, 2, ".", ","); ?></td>
								<td <?=$padding2x5 ?>><?php echo number_format($itmait, 2, ".", ","); ?></td>
								<td align="right" <?=$padding2x5 ?>><?php echo number_format($net, 2, ".", ","); ?></td-->
							  </tr>
<?php }
//$vat=$tot*0.0;
    //$sc=$tot*0.0;
    $amount = $tot + $deliveryamt-$discount;
} ?>
							<!--
							<tr>
								<td align="center" <?=$padding2x5 ?>>1</td>
								<td <?=$padding2x5 ?>> Item Number 1</td>
								<td <?=$padding2x5 ?> align="center">2</td>
								<td <?=$padding2x5 ?>>$2.00</td>
								<td <?=$padding2x5 ?>>256.00</td>
								<td <?=$padding2x5 ?>>OTC</td>
							  </tr>
							  <tr>
								<td align="center" <?=$padding2x5 ?>>3</td>
								<td <?=$padding2x5 ?>> Item Number 1</td>
								<td <?=$padding2x5 ?> align="center">2</td>
								<td <?=$padding2x5 ?>>$2.00</td>
								<td <?=$padding2x5 ?>>256.00</td>
								<td <?=$padding2x5 ?>>OTC</td>
							  </tr>							  -->

<!--						Ens Loop here		-->


							<!--  <tr>
								<td colspan="3" rowspan="5">&nbsp;</td>
								<td align="right" <?=$padding2x5 ?>><strong>Subtotal</strong></td>
								<td <?=$padding2x5 ?>>256.00</td>
								<td rowspan="5" <?=$padding2x5 ?>>&nbsp;</td>
							  </tr>			-->
                               
							  <tr>
							      <td colspan="6" rowspan="6" style="border-left:0;border-bottom:0;">&nbsp;</td>
								<td align="right" <?=$padding2x5 ?>><strong>Total</strong></td>
								<td align="right" <?=$padding2x5 ?>><b><?php echo number_format($tot, 2, ".", ",") ?></b></td>
							  </tr>
							  <tr>
								<td align="right" <?=$padding2x5 ?>>VAT(Included)</td>
								<td align="right" <?=$padding2x5 ?>><span style="font-size:11px;color: #c0c0c0">(<?php echo number_format($totvat, 2, ".", ","); ?>)</span></td>
							  </tr>
							  <!--tr>
								<td align="right" <?=$padding2x5 ?>>AIT</td>
								<td align="right" <?=$padding2x5 ?>><?php echo number_format($totait, 2, ".", ","); ?></td>
							  </tr-->
							  <tr>
								<td align="right" <?=$padding2x5 ?>>Delivery</td>
								<td align="right" <?=$padding2x5 ?>><?php echo number_format($deliveryamt, 2, ".", ","); ?></td>
							  </tr>
							  <tr>
								<td align="right" <?=$padding2x5 ?>>Adjustment(-)</td>
								<td align="right" <?=$padding2x5 ?>><?php echo number_format($discount, 2, ".", ","); ?></td>
							  </tr>
							  <tr>
								<td  <?=$padding2x5 ?> align="right"><strong>Amount</strong></td>
								<td align="right" <?=$padding2x5 ?>><b><?php echo number_format($amount, 2, ".", ","); ?></b></td>
							  </tr>
							</tbody>
						  </table>
					<br>
<div style="width:100%; font-size: 12px;"class="text-center">
Make all checks payable to <strong><?php echo $comname; ?></strong>.<br>
"If you have any questions concerning this invoice, then contact Support at <?php echo $comemail; ?> <br>
<br>
<strong style="width:100%; font-size: 16px;" class="text-center">Thank you for your business!</strong>
</div>


		  </div>

             </div>

<br>
		
			<br>

<?php
			
//echo "STATUS:". $ordst;

if($ordst == 3 || $ordst == 11 ){ ?>
<div style="">
<input type="button" class="btn btn-lg btn-info" onclick="printDiv('printableArea')" value="&nbsp;&nbsp;&nbsp;Print&nbsp;&nbsp;&nbsp;" />
</div>
<?php
}else{?>
<!--div style="">
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<?php if($ordst == 9){ ?>
		<input type="button" class="btn btn-lg btn-info" name="updatebook" value="Booked" style="background-color:red" />
		<?php }else{ ?>
		<input type="submit" class="btn btn-lg btn-info" name="updatebook" value="Book" />
	<?php
} ?>
	<input type="submit" class="btn btn-lg btn-info" name="updateconfirm" value="Confirm" />
	<input type="hidden" name="invid" value="<?=$_REQUEST['invid']?>">
	<input type="hidden" name="mod" value="<?=$_REQUEST['mod']?>">
</form>
</div-->
<?php }?>

          <!-- START PLACING YOUR CONTENT HERE -->
          </p>








        </div>
      </div>
    </div>
  </div>
</div>

<!-- /#page-content-wrapper -->



<!-- #page-footer -->
<div class="container-fluid">
  <div class="page_footer">
    <div class="row">
      <div class="col-xs-2"><a class="" href="http://www.bithut.biz/" target="_blank" bo><img src="images/logo_bithut_sm.png" height="30" border="0"></a></div>
      <div class="col-xs-10  copyright">Copyright © <a class="" href="http://www.bithut.biz/" target="_blank">Bithut Ltd.</a></div>
    </div>
  </div>
</div>
<!-- /#page-footer -->



<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/sidebar_menu.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>
<!-- Bootstrap core JavaScript
    ================================================== -->
<script src="js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="js/custom.js"></script>

<!-- SWEET ALERT -->
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>


<?php
if($msg){
	
?>
	<script>
	
	swal("<?=$msg?>"); 
		
		
	</script>
	
<?php
		}
?>
  <!-- END FLOT CHART-->

<script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
</body></html>
