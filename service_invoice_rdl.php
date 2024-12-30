<?php
require "common/conn.php";
require_once("rak_framework/fetch.php");
require_once("rak_framework/edit.php");

//ini_set('display_errors',1);

session_start();
$usr = $_SESSION["user"];
$mod = $_REQUEST['mod'];
$invid = $_REQUEST['invid'];
extract($_REQUEST);
//print_r($_REQUEST);

//echo "<pre>";print_r($_SESSION);echo "</pre>";die;
//die;
if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {
	
	
	

    $qry = "select soitm.transport, soitm.service_charge, inv.invoice,inv.invoicedt,inv.invmonth,inv.`serviceorder`,soitm.customer organization,o.name orgnm,o.orgcode ,o.street,o.area,area.name arnm,
            o.district,ds.name dsnm,o.state,st.name stnm,o.zip,o.country,cn.name cnnm, o.contactno,o.email,o.website,ofc.Name ofcnm,ofc.street ofcst,ofc.area ofcar,
            ofc.email ofceml,ofc.web ofcweb, ips.name payst,ips.dclass paystclass,inv.paidamt,inv.dueamt,soitm.totalvat,soitm.totaltax,soitm.totalamount,soitm.makeby 
            ,(select `hrName` from hr where id=soitm.makeby  ) createby
            from service_invoice inv left join service_order soitm on soitm.code = inv.serviceorder left join organization o on soitm.customer=o.id 
            left join area on o.area=area.id left JOIN district ds on o.district=ds.id left join state st on o.state=st.id left join country cn on o.country=cn.id 
            LEFT JOIN invoicepaystatus ips on inv.paymnetst=ips.id , companyoffice ofc where inv.invoice='" . $invid . "'";
	

	
	
    //echo  $qry;die;
    $resultinv = $conn->query($qry);
    if ($resultinv->num_rows > 0) {
        while ($rowinv = $resultinv->fetch_assoc()) {
            $invno   = $rowinv["invoice"];
            $invdt   = $rowinv["invoicedt"];
            $invmnth = $rowinv["invmonth"];
            $sof     = $rowinv["serviceorder"];
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
            $deliveryto  = $rowinv["street"];
			$deliveryamt  = $rowinv["deliveryamt"];
		    $discount  = $rowinv["adjustment"];
		    $ordst  = $rowinv["orderstatus"];
			$orgcode  = $rowinv["orgcode"];
			$makeby  = $rowinv["makeby"];
			$createby  = $rowinv["createby"];
			$invPaymentSt = $rowinv["payst"];
			$invPaymentStClass = $rowinv["paystclass"];
			
			$paidamount = $rowinv["paidamt"];
		    $dueamount = $rowinv["dueamt"];
		    $totvat                          = $rowinv["totalvat"];
            $tottax                          = $rowinv["totaltax"];
            $totalamount                          = $rowinv["totalamount"];
            
            $transport = $rowinv["transport"];
			$service_charge = $rowinv["service_charge"];
			
        }
    }
}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$invid?></title>
<link rel="icon" href="images/favicon.png">
<link href="css/fonts.css" rel="stylesheet">	
	
<style>
<?php
$fontPath = $hostpath."/fonts/";
?>	
@font-face {
    font-family: 'roboto_condensedbold';
    src: url('<?=$fontPath?>roboto_boldcondensed_macroman/RobotoCondensed-Bold-webfont.eot');
    src: url('<?=$fontPath?>roboto_boldcondensed_macroman/RobotoCondensed-Bold-webfont.eot?#iefix') format('embedded-opentype'),
         url('<?=$fontPath?>roboto_boldcondensed_macroman/RobotoCondensed-Bold-webfont.woff') format('woff'),
         url('<?=$fontPath?>roboto_boldcondensed_macroman/RobotoCondensed-Bold-webfont.ttf') format('truetype'),
         url('<?=$fontPath?>roboto_boldcondensed_macroman/RobotoCondensed-Bold-webfont.svg#roboto_condensedbold') format('svg');
    font-weight: normal;
    font-style: normal;

}
	
@font-face {
    font-family: 'roboto';
    src: url('<?=$fontPath?>roboto-regular/roboto-regular-webfont.woff2') format('woff2'),
         url('<?=$fontPath?>roboto-regular/roboto-regular-webfont.woff') format('woff');
    font-weight: normal;
    font-style: normal;

}	

@font-face {
    font-family: 'robotobold';
    src: url('<?=$fontPath?>roboto_bold_macroman/Roboto-Bold-webfont.eot');
    src: url('<?=$fontPath?>roboto_bold_macroman/Roboto-Bold-webfont.eot?#iefix') format('embedded-opentype'),
         url('<?=$fontPath?>roboto_bold_macroman/Roboto-Bold-webfont.woff') format('woff'),
         url('<?=$fontPath?>roboto_bold_macroman/Roboto-Bold-webfont.ttf') format('truetype'),
         url('<?=$fontPath?>roboto_bold_macroman/Roboto-Bold-webfont.svg#robotobold') format('svg');
    font-weight: normal;
    font-style: normal;

}

	.strong, strong,b{
		font-family: robotobold;
		font-weight: normal;
	}	
.inv-header th{
    text-align: center;
}

.inv-header th:nth-child(2){
    text-align: left;
}
.print-wrapper{
    padding: 5px 0px;
}
body{
    font-family: roboto;
    font-size: 13px;
	padding: 0;
}
h1{
    font-family: roboto_condensedbold;
    font-size: 30px;
    margin: 0;
    border: 0px solid #000;
}
.tbl-header{margin-bottom: 5px;}

.tbl-orderinfo{margin-top: 30px;}

.tbl-orderinfo td{
    width: 33%;
    border: 0px solid #000;
   
}

.tbl-orderinfo h5{
    font-size: 13px;
    margin: 0;
    line-height: 1.2em;
}

.tbl-orderinfo p{
    line-height: 1.2em;
     font-size: 12px;
}

.tbl-header tr td:first-child{
    width: 350px;
}

hr{
    background-color:transparent;
    border: 0;
    border-bottom: 1px solid #6d6d6d;
    margin: 0;
}
.tbl-address{margin:10px 0px; }
.tbl-address td{
    font-size: 11px;
    width: 25%;
    padding:0 10px;
    border-left: 2px solid #ada0a0;
}

.tbl-address td:first-child{
    border-left: 0;
    padding-left: 0;
}


.tbl-items{margin-top: 20px; margin-bottom: 40px;}

.tbl-items th{
    padding: 5px 8px;
    font-size: 11px;
    white-space: nowrap;
}

.tbl-items td{
    padding: 5px;
    font-size: 13px;
}

.tbl-items th.number{width: 80px!important;}

.tbl-items td.number{width: 80px!important; text-align: right;}

.tbl-item-detail td{
    font-family: 13px;
}

.tbl-item-detail td:first-child{
    width: 30%;
}

.tbl-items th{border: 1px solid #7a7a7a;}
.tbl-items td{border: 1px solid #7a7a7a;}

.tbl-items td table td{border: 0}

.no-lb-border{
    border-bottom: 0px!important;
    border-left: 0px!important;
}
.tbl-items th.itemtbl-footer{ text-align: right; white-space: nowrap;}
.tbl-items{background-color: transparent;border-collapse: collapse;}


.terms-wrapper hr{
    margin:0px;    
}

h2{
    font-size: 15px;
    margin: 6px 0;
}

h3{
    font-size: 12px;
    margin: 0px 0;
}

.terms-wrapper ol{
    margin-left: 0;
    padding-left: 0;
    margin-top: 0;

}
.terms-wrapper li{
    font-size: 11px;
    margin-left: 15px;
    
}

h4{font-size: 12px;}	
	
	
@media print {

.print-wrapper{
    padding: 10px 40px;
}
body{
    font-family: roboto;
    font-size: 13px;
}
h1{
    font-family: roboto_condensedbold;
    font-size: 30px;
    margin: 0;
    border: 0px solid #000;
}
.tbl-header{margin-bottom: 5px;}

.tbl-orderinfo{margin-top: 30px;}

.tbl-orderinfo td{
    width: 33%;
    border: 0px solid #000;
   
}

.tbl-orderinfo h5{
    font-size: 13px;
    margin: 0;
    line-height: 1.2em;
}

.tbl-orderinfo p{
    line-height: 1.2em;
     font-size: 12px;
}

.tbl-header tr td:first-child{
    width: 350px;
}

hr{
    background-color:transparent;
    border: 0;
    border-bottom: 1px solid #6d6d6d;
    margin: 0;
}
.tbl-address{margin:10px 0px; }
.tbl-address td{
    font-size: 6pt;
    width: 25%;
    padding:0 10px;
    border-left: 2px solid #ada0a0;
}

.tbl-address td:first-child{
    border-left: 0;
    padding-left: 0;
}


.tbl-items{margin-top: 20px; margin-bottom: 40px;}

.tbl-items th{
    padding: 5px 8px;
    font-size: 13px;
    white-space: nowrap;
}

.tbl-items td{
    padding: 5px;
    font-size: 13px;
}

.tbl-items th.number{width: 80px!important;}

.tbl-items td.number{width: 80px!important; text-align: right;}

.tbl-item-detail td{
    font-family: 13px;
}

.tbl-item-detail td:first-child{
    width: 30%;
}

.tbl-items th{border: 1px solid #7a7a7a;}
.tbl-items td{border: 1px solid #7a7a7a;}

.tbl-items td table td{border: 0}

.no-lb-border{
    border-bottom: 0px!important;
    border-left: 0px!important;
}
.tbl-items th.itemtbl-footer{ text-align: right; white-space: nowrap;}
.tbl-items{background-color: transparent;border-collapse: collapse;}

.tbl-items{ break-after: always;}

.terms-wrapper {
    padding-top:60px;    
}	
.terms-wrapper hr{
    margin:0px;    
}

h2{
    font-size: 10pt;
    margin: 6px 0;
}

h3{
    font-size: 7pt;
    margin: 0px 0;
}

.terms-wrapper ol{
    margin-left: 0;
    padding-left: 0;
    margin-top: 0;

}
.terms-wrapper li{
    font-size: 6pt;
    margin-left: 15px;
    
}

h4{font-size: 8pt;}
	
}
	
	
kbd{font-family:roboto; padding: 4px 8px;}
kbd.due{background-color: #FF0000; border: 0!important; box-shadow: none;}
kbd.over-due{background-color: #cf0404; border: 0!important; box-shadow: none;}
kbd.paid{background-color: #07b100; border: 0!important; box-shadow: none;}
kbd.over-paid{background-color: #056b02; border: 0!important; box-shadow: none;}
	kbd {
  padding: 2px 4px;
  font-size: 90%;
  color: #fff;
  background-color: #333;
  border-radius: 3px;
  -webkit-box-shadow: inset 0 -1px 0 rgba(0,0,0,.25);
  box-shadow: inset 0 -1px 0 rgba(0,0,0,.25);
}
	
	
h5,th,h3,h4,h2,strong{
   font-weight: normal;
   font-family: robotobold; 
}

	
	
</style>	
</head>

<body>

<div class="print-wrapper">
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-header">
  <tbody>
    <tr>
		<?php
			$logo = fetchByID('sitesettings',id,1,'doc_header_logo');
		?>
      <td><img src="<?=$hostpath?>/assets/images/site_setting_logo/<?=(strlen($logo)>0)?$logo:"default/logo_letterhead.png"?>" width="100%"></td>
	  <td align="right"><h1>SERVICE INVOICE</h1></td>
    </tr>
  </tbody>
</table>
<hr>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-address">
  <tbody>
    <tr>
		<td>
<!--
			Ashfia Tower, Level 4, Plot 76<br>
			Road 11, Block - E, Banani<br>
			Dhaka -1213, Bangladesh<br>
-->
			<?=nl2br(fetchByID('sitesettings',id,1,'address'))?>
		</td>
		
		<td>
			Phone: <?=fetchByID('sitesettings',id,1,'contactno')?><br>
			Email: <?=fetchByID('sitesettings',id,1,'email')?><br>
		</td>		
		
		<td>
			Customer Service Hotline<br>
			<?=fetchByID('sitesettings',id,1,'hotline')?>
		</td>
		<td>
			Outlet Operating Hours<br>
			<?=fetchByID('sitesettings',id,1,'officehours')?>
			
		</td>			
		
    </tr>
  </tbody>
</table>	


<hr>
	

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-orderinfo">
  <tbody>
    <tr>
		<td valign="top">
			<h5>Billing Address<br>
			<?=$orgnm?></h5>
			
			<p>
				<?=nl2br($strt);?>
				<!--H-322, R-22, 1st floor<br>
				Mohakhali DOHS<br>
				Dhaka 1212, Bangladesh--><br>
				Phone: <?=$invcnt?><br>
				Email: <?=$inveml?><br>
			</p>
		</td>
		
		<td valign="top">
			<h5>Delivery Address<br>
			<?=$orgnm?></h5>
			
			<p><?=($deliveryto)?nl2br($deliveryto):$strt?>
<!--
				H-322, R-22, 1st floor<br>
				Mohakhali DOHS<br>
				Dhaka 1212, Bangladesh
-->
			<br>
				Phone: <?=$invcnt?><br>
				Email: <?=$inveml?><br>
			</p>
		</td>		
		
		<td valign="top">
			<h5>Order Informaiton<br>
			Invoice ID: <?=$invno?><br>
				Payment Status: <kbd class="<?=$invPaymentStClass?>"><?=$invPaymentSt?></kbd>
			</h5>
		
			<p>
				Date: <?=date_format(date_create($invdt),"jS F, Y")?><br>
				Order ID:  <?=$sof?><br>
				Customer ID:  <?=$orgcode?><br>
			    Create By:  <?=$createby?><br>
				
			</p>
		</td>			
		
    </tr>
  </tbody>
</table>	
	
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-items">
  <tbody>
    <tr class="inv-header">
      <th scope="col" align="center">SN</th>
      <th scope="col" align="left">DESCRIPTION</th>
      <th scope="col" class="number">UNIT</th>
      <th scope="col" class="number">QTY</th>
      <th scope="col" class="number">RATE</th>
      <th scope="col" class="number">AMOUNT</th>
    </tr>
	  
	  
<?php
$dqry   = "SELECT sid.vat,sid.tax,sid.amount,sid.totalamount, i.name,i.code, sid.sosl, sod.description, sod.unit,sod.qty,sod.rate,so.totalvat
            FROM `service_invoicedetails` sid LEFT JOIN service_invoice si ON si.id=sid.invoiceid LEFT JOIN serviceitem i ON i.id = sid.product 
            LEFT JOIN service_order so ON so.code=si.serviceorder LEFT JOIN service_orderdetails sod ON (sod.serviceid=so.id AND sod.sosl=sid.sosl) 
            WHERE si.invoice='" . $invno . "' ORDER BY sid.sosl";

//echo $dqry;die;
$tot = 0;
$resultd = $conn->query($dqry);
if ($resultd->num_rows > 0) {
    while ($rowsd = $resultd->fetch_assoc()) {
		$sosl = $rowsd["sosl"];
        $prod                            = $rowsd["name"];
		$code                            = $rowsd["code"];
        $itmqty                          = $rowsd["qty"] ;
        $itmrate                          = $rowsd["rate"] ;
        $unitprice                       = $rowsd["totalamount"] ;
        $unit                               = $rowsd["unit"];
        $description                         = $rowsd["description"];
         $totalvat                               = $rowsd["totalvat"];
        $tot += $unitprice;
                 
        ?>	  
	  
    <tr>
      <td align="center"><?=$sosl?></td>
      <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-item-detail">
		  <tbody>
			<tr>
			  <td style="width: 150px"><img src="<?=$photo?>" width="100%"></td>
			  <td>
				
					<strong><?=$prod?></strong><br>
					<strong><?= $code ?></strong><br>
					<strong><?= $description ?></strong><br>
				  
			  </td>
			</tr>
		  </tbody>
		</table>

		</td>
      <td class="number"><?=$unit?></td>
      <td class="number"><?=$itmqty?></td>
      <td class="number"><?=number_format($itmrate, 2, ".", ",")?></td>
      <td class="number"><?=number_format($unitprice, 2, ".", ",")?></td>
    </tr>
<?php }
//$vat=$tot*0.0;
    //$sc=$tot*0.0;
	//$TDByRate = $TDByRate+($totamt*$discrate/100);
    $amount = $totalamount - ($totvat+$tottax);
} ?>
	<tr>
      <td colspan="4" rowspan="8" class="no-lb-border">&nbsp;</td>
      <th class="itemtbl-footer">TOTAL</th>
      <td class="number strong"><?php echo number_format($tot, 2, ".", ",") ?></td>
    </tr>
    <tr>
      <th class="itemtbl-footer">TRANSPORT COST</th>
      <td class="number"><?php echo number_format($transport, 2, ".", ","); ?></td>
    </tr>
    <tr>
      <th class="itemtbl-footer">SERVICE FEE</th>
      <td class="number" ><?php echo number_format($service_charge, 2, ".", ","); ?></td>
    </tr>
    <tr>
      <th class="itemtbl-footer">SUBTOTAL</th>
      <td class="number"><?php echo number_format(($tot + $transport + $service_charge), 2, ".", ","); ?></td>
    </tr>
    <tr>
      <th class="itemtbl-footer">TOTAL VAT</th>
      <td class="number"><?php echo number_format($totalvat, 2, ".", ","); ?></td>
    </tr>
    <tr>
      <th class="itemtbl-footer">TOTAL AMOUNT</th>
      <td class="number strong"><?php echo number_format($totalamount, 2, ".", ","); ?></td>
    </tr>	  
	  <?php
	  if($paidamount>0){
		  
		  ?>
    <tr>
      <th class="itemtbl-footer">PAID</th>
      <td class="number strong"><?php echo number_format($paidamount, 2, ".", ","); ?></td>
    </tr>
    <tr style="color: #FF0000">
      <th class="itemtbl-footer">DUE</th>
      <td class="number strong"><?php echo number_format($dueamount, 2, ".", ","); ?></td>
    </tr>	  
	  <?php
	  }
	  ?>
	  
	  
  </tbody>
</table>
<div class="terms-wrapper">	
<hr>
	<h2>Terms and Conditions</h2>
<hr>
<br>
	

<h3>Mode of Payment</h3>	
<ol>
	<li>Payments can be paid by cash, credit/debit cards and account payee cheque in favor of "Renaissance Decor Limited."</li>
</ol>
	
<h3>Delivery terms</h3>	
<ol>
	<li>Payment must be fully paid before delivery.</li> 
	<li>Free delivery within Dhaka city available on Friday and Saturday. Delivery on other days (Mon-Thurs) are chargeable Tk:5000/trip. Delivery hours:10am to 8pm</li>
	<li>For outside Dhaka, delivery charges will vary.	</li>
</ol>	
	
<h3>Warehousing Service</h3>	
<ol>
	<li>Payment must be fully paid before delivery.</li> 
	<li>Free delivery within Dhaka city available on Friday and Saturday. Delivery on other days (Mon-Thurs) are chargeable Tk:5000/trip. Delivery hours:10am to 8pm</li>
	<li>For outside Dhaka, delivery charges will vary.	</li>
</ol>

<h3>Store credit term</h3>	
<ol>	
	<li>RDL will give store credit instead of refund, which will be valid following 6 months.</li>
	<li>If any client requires RDL to store products for the 5th month, RDL will charge for storage @3% of the undelivered products and total charges will have to be paid in advance. After 150 days, RDL will not store products any longer.</li>
	<li>After 300 days any deposit made will be forfeited and the sales order will be closed.	</li>
</ol>		
</div>
<h4>FREE STORAGE ONLY FOR 60 DAYS.<br>
NO RETURN NO REFUND NO EXCHANGE</h4>

<strong>Issued By (Renaissance Decor Ltd)</strong>

	
	</div>
</body>
</html>
