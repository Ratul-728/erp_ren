<?php
require "common/conn.php";
require_once("rak_framework/fetch.php");
require_once("rak_framework/edit.php");

//ini_set('display_errors',1); 

session_start();
$usr = $_SESSION["user"];
$mod = $_REQUEST['mod'];
$invid = ($_REQUEST['qid'])?$_REQUEST['qid']:$_REQUEST['socode'];

$QuotationID = ($_REQUEST['qid'])?$_REQUEST['qid']:$_REQUEST['socode'];

extract($_REQUEST);
//print_r($_REQUEST);die;

//echo "<pre>";print_r($_REQUEST);echo "</pre>";die;
//die;
if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {
	

	
	
if($qtype == "quotation"){

 $qry = "select inv.note,  inv.socode,inv.orderdate,inv.organization,o.name orgnm,o.orgcode,inv.deliveryamt,orst.name `quotationstatusname`
,o.street,o.area,area.name arnm,o.district,ds.name dsnm,o.state,st.name stnm,o.zip,o.country,cn.name cnnm,concat(emp.firstname, ' ', emp.lastname) accmng,
o.contactno,o.email,o.website,ofc.Name ofcnm,ofc.street ofcst,ofc.area ofcar,ofc.email ofceml,ofc.web ofcweb, 
inv.remarks delivto,inv.makeby,inv.adjustment,inv.orderstatus, concat(e1.firstname,'  ',e1.lastname) `poc`
from quotation  inv
left join `quotation_status` orst on inv.`orderstatus`=orst.`id`
left join organization o on inv.organization=o.id left join `hr` h1 on inv.`poc`=h1.`id`  
left join employee e1 on h1.`emp_id`=e1.`employeecode`
left join area on o.area=area.id left JOIN district ds on o.district=ds.id
left join state st on o.state=st.id left join country cn on o.country=cn.id
LEFT JOIN `hr` h ON h.id = inv.makeby LEFT JOIN employee emp ON emp.employeecode=h.emp_id
, companyoffice ofc
where inv.socode='" . $invid . "'";
	
}else{
 $qry = "select inv.note, inv.socode,inv.orderdate,inv.organization,o.name orgnm,o.orgcode,inv.deliveryamt, orst.name `quotationstatusname`
,o.street,o.area,area.name arnm,o.district,ds.name dsnm,o.state,st.name stnm,o.zip,o.country,cn.name cnnm,concat(emp.firstname, ' ', emp.lastname) accmng,
o.contactno,o.email,o.website,ofc.Name ofcnm,ofc.street ofcst,ofc.area ofcar,ofc.email ofceml,ofc.web ofcweb, 
inv.remarks delivto,inv.makeby,inv.adjustment,inv.orderstatus
from quotation_revisions inv
left join `quotation_status` orst on inv.`orderstatus`=orst.`id`
left join organization o on inv.organization=o.id
left join area on o.area=area.id left JOIN district ds on o.district=ds.id
left join state st on o.state=st.id left join country cn on o.country=cn.id
LEFT JOIN `hr` h ON h.id = inv.makeby LEFT JOIN employee emp ON emp.employeecode=h.emp_id
, companyoffice ofc
where inv.socode='" . $socode . "'";
	
}
	
	
    //echo  $qry;die;
    $resultinv = $conn->query($qry);
    if ($resultinv->num_rows > 0) {
        while ($rowinv = $resultinv->fetch_assoc()) {

            $invdt   = $rowinv["orderdate"];
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
			$orgcode  = $rowinv["orgcode"];
			$makeby  = $rowinv["makeby"];
			$accountmanager = $rowinv["accmng"];
			
			$orderst = $rowinv["quotationstatusname"];
			
			$poc = $rowinv["poc"];
			$note = $rowinv["note"];
			
        }
    }
}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$invid?> <?=($qrid)?"<br> - Revision ID: ".$qrid:""?></title>
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
	  <td align="right"><h1><?= strtoupper($orderst) ?></h1></td>
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
	

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-orderinfo-">
  <tbody>
    <tr>
	
		
		<td valign="top" align="center">
			<br>

			<h2>
			Quotation ID: <?=$invid?>
				<?=($qrid)?"<br>Revision ID: ".$qrid:""?>
			</h2>
		
			<p>
				Date: <?=date_format(date_create($invdt),"jS F, Y")?><br>
				Customer:  <?=$orgnm?> (<?=$orgcode?>)<br>
				Created By: <b>
				<?= $accountmanager ?>
				</b>
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
      <th scope="col">QTY</th>
      <th scope="col" class="number">UNIT PRICE</th>
      <th scope="col" class="number">PRICE</th>
      <th scope="col" class="number">DISCOUNT</th>
      <th scope="col" class="number">AMOUNT</th>
    </tr>
	  
	  
<?php
/*if($status == 1){ *///draft. fetch data from quotatoin_detail table.
        $table ='quotation_detail';
        $col_productid = 'productid';
        $col_amount = 'otc';
        $col_vatrate = 'qd.vatrate';
        $qt_leftjoint = '';
        $i_leftjoint = 'LEFT JOIN item i on qd.productid=i.id';
    /*}else{ //fetch data from invoicedetails table.
        $table ='invoicedetails';
        $col_productid = 'product';
        $col_amount = 'amount';
        $col_vatrate = 'q.vatrate';
        $qt_leftjoint = 'LEFT JOIN quotation_detail q ON (q.socode = qd.socode AND q.sosl=qd.sosl)';
        $i_leftjoint = 'LEFT JOIN item i on qd.product=i.id';
    }*/

if($qtype == "quotation"){	  
    
    
    $quotDetailQry ="
    SELECT 
    qd.sosl,
    qd.$col_productid productid,
    qd.vat,
    qd.qty,
    qd.$col_amount price,
    qd.discountrate,
    qd.discounttot,
    qd.discount_amount,
    $col_vatrate,
    i.name item,
    i.description description,
    i.image,
    i.code,
    i.dimension,
    i.parts,
     i.barcode
    
    FROM $table qd 
    $qt_leftjoint 
    $i_leftjoint
    WHERE qd.socode='" . $QuotationID . "' ORDER BY qd.sosl ASC";
    
    //echo $quotDetailQry;die;
    
// $quotDetailQry =" 
//     SELECT 
//     qd.sosl,
//     qd.product productid,
//     qd.vat,
//     qd.qty,
//     qd.amount price,
//     qd.discountrate,
//     qd.discounttot,
//     q.vatrate,
//     i.name item,
//     i.description description,
//     i.image,
//     i.code,
//     i.dimension,
//     i.parts
    
    
//     FROM invoicedetails qd 
//     LEFT JOIN quotation_detail q ON (q.socode = qd.socode AND q.sosl=qd.sosl)
//     LEFT JOIN item i on qd.product=i.id
//     WHERE qd.socode='" . $QuotationID . "' ORDER BY qd.sosl ASC";

 


}else{

$quotDetailQry ="
    SELECT 
    qd.sosl,
    qd.revision_id,
    qd.productid,
    qd.vat,
    qd.vatrate,
    qd.qty,
    qd.otc price,
    qd.discountrate,
    qd.discounttot,
    i.name item,
    i.description description,
    i.image,
    i.code,
    i.dimension,
    i.parts,
    i.barcode
    FROM quotation_revisions_detail qd
    LEFT JOIN item i on qd.productid=i.id
    WHERE qd.socode='" . $socode. "' AND qd.revision_id='" . $qrid. "' ORDER BY qd.sosl ASC";	
}
	  
//echo $quotDetailQry;die; 
	  
$tot    = 0;
$totvat = 0;
$totait = 0;
$totamtinclvat=0;
$subtotalinclvat=0;
$subtotdiscount=0;

$resultd = $conn->query($quotDetailQry);
if ($resultd->num_rows > 0) {
    while ($rowsd = $resultd->fetch_assoc()) {
		
		$sosl = $rowsd["sosl"];
        $prod                            = $rowsd["product"];
		$code                            = $rowsd["code"];
		$barcode                         = $rowsd["barcode"];
		$dimension                       = $rowsd["dimension"];
		$desc                            = $rowsd["description"];
        $pnm                             = $rowsd["item"];
        $q                               = $rowsd["qty"];
        $price                           = $rowsd["price"]; 
        $itmvat                          = $rowsd["vat"] ;
        $vatrate                          = $rowsd["vatrate"] ;
        $parts                          = $rowsd["parts"] ;
        $amt                             = (($rowsd["discounttot"]+$itmvat)/$q)*100/(100-$rowsd["discountrate"]);
        $totamt                          = ($q * $price);
        $discrate                        = $rowsd["discountrate"] ;
        $discount_amount                = $rowsd["discount_amount"] ;
        $discounttot                 = $rowsd["discounttot"] ;
        $vatincdisc                 =$discounttot/(100+$vatrate)*$vatrate;
        
        $totdis =  ((($rowsd["price"] * $q) * $discrate) / 100);
        $disctot                          = $rowsd["discounttot"] +$itmvat;
        $net                             = $totamt;// + $itmvat + $itmait;
        
       
        $totait                          = $totait + $itmait;
        $vattot                          = $totamt*$vatrate*0.01;//$discounttot/(100+$vatrate)*$vatrate;
        $totamtinclvat                   = $totamt+$vattot;
        $priceincvat                     = $totamtinclvat/$q;
        $discount_amount                = $totamtinclvat-$discounttot;
        
        $tot                             = $tot + $discounttot;
        $totvat                          = $totvat + $vatincdisc;
        $subtotalinclvat                =$subtotalinclvat+$totamtinclvat;
        $subtotdiscount                 =$subtotdiscount+$discount_amount;
                 
                if ( strlen($rowsd["image"]) > 0){
        		    $photo= $hostpath."/assets/images/products/300_300/".$rowsd["image"];
        		}else{
        			$photo= $hostpath."/assets/images/products/placeholder.png";
        		}
        ?>	  
	  
    <tr>
      <td align="center"><?=$sosl?></td>
      <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-item-detail">
		  <tbody>
			<tr>
			  <td style="width: 150px"><img src="<?=$photo?>" width="100%"></td>
			  <td>
				
					<strong><?=$barcode?></strong><br> 
					<strong><?= $pnm ?></strong>
				  <?=(strstr($desc,","))?str_replace(",","<br>",$desc):nl2br($desc)?>
					<br>
					Number of Parts: <?=$parts?><!--G3-DC-13: Study Chair<br>
					KD116-21 Charpie Fabric With
					Stainless Steel leg, Color-Teal<br>
					L-22''W-24''H-31''--><br>
				  
			  </td>
			</tr>
		  </tbody>
		</table>

		</td>
      <td align="center"><?=$q?></td>
      <td class="number"><?=number_format($priceincvat, 2, ".", ",")?></td>
      <td class="number"><?=number_format($totamtinclvat, 2, ".", ",")?></td>
      <td class="number"><?=number_format($discount_amount, 2, ".", ",")?></td>
      <td class="number"><?=number_format($discounttot, 2, ".", ",")?></td>
    </tr>
<?php }
//$vat=$tot*0.0;
    //$sc=$tot*0.0;
	//$TDByRate = $TDByRate+($totamt*$discrate/100);
    $amount = $tot + $deliveryamt-$discount;
} ?>
    <!--tr class="inv-header">
      <th scope="col"></th>
      <th scope="col"></th>
      <th scope="col"></th> 
      <th scope="col"> </th>
      <th scope="col" ></th>
      <th scope="col" ></th>
      <th scope="col" ></th>
    </tr-->
	
	
</tbody>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-items">
  <tbody>
      <tr>
      <!--td colspan="5" rowspan="6" class="no-lb-border">&nbsp;
      
      <?php
        if($note){
            ?>
            
            <h3>Note:</h3>
            <?=$note?>
            <?php
        }
      ?>
      
      </td-->
      <th class="itemtbl-footer">SUB TOTAL</th>
      <td class="number strong"><?php echo number_format($subtotalinclvat, 2, ".", ",") ?></td>
    </tr>
    <tr>
      <th class="itemtbl-footer">TOTAL DISCOUNT </th>
      <td class="number"><?php echo number_format($subtotdiscount, 2, ".", ",") ?></td>
    </tr>
    <tr>
      <th class="itemtbl-footer">TOTAL VAT</th>
      <td class="number" style="font-size:11px;color: #c0c0c0">(<?php echo number_format($totvat, 2, ".", ","); ?>)</td>
    </tr>
    <!--tr>
      <th class="itemtbl-footer">ADJMT(-)</th>
      <td class="number"><?php echo number_format($discount, 2, ".", ","); ?></td>
    </tr>
    <tr class="d-none">
      <th class="itemtbl-footer">DLV. CHARGE</th>
      <td class="number"><?php echo number_format($deliveryamt, 2, ".", ","); ?></td>
    </tr-->	  
    <tr>
      <th class="itemtbl-footer">TOTAL PAYBLE AFTER DISCOUNT</th>
      <td class="number strong"><?php echo number_format($tot, 2, ".", ","); ?></td>
    </tr>	  
	  
	  
  </tbody>
</table>

<?php

    
    $qryiscancel="select qty_canceled from cancel_order where order_id='$socode' and st =2";
    $rescancel = $conn->query($qryiscancel);
    if ($rescancel->num_rows > 0) 
    {
   
?>
<br />

<hr>
	<h2 class="text-center" style="color:#094446;">Cancel Items</h2>
<hr>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-items">
  <tbody>
    <tr class="inv-header">
      <th scope="col" align="center">SN</th>
      <th scope="col" align="left">DESCRIPTION</th>
      <th scope="col">QTY</th>
      <th scope="col" class="number">UNIT PRICE</th>
      <th scope="col" class="number">PRICE INCLUDING VAT</th>
      <th scope="col" class="number">DISCOUNT</th>
      <th scope="col" class="number">AMOUNT</th>
    </tr>
	 <?php 
	 $tot=0;$totvat=0;
	 $qrycancel="SELECT c.co_id,i.image,i.barcode,i.name pnm,i.description,i.height,i.heightunit,i.width,i.widthunit,i.length,i.lengthunit,d.discounttot,d.discount_amount,
        d.otc,c.qty_canceled ,d.discountrate,d.vatrate,i.colortext, i.parts
        FROM `cancel_order` c left join quotation_detail d on c.order_id=d.socode and c.productid=d.productid
        left join item i on c.productid=i.id
        WHERE c.order_id='$socode' and c.st=2";
    $resulcancel = $conn->query($qrycancel);
        if ($resulcancel->num_rows > 0)
        {
            while ($rowcancel = $resulcancel->fetch_assoc())
            {
                $sosl = $rowcancel["co_id"];
		        $photo = $rowcancel["image"];
		        $code = $rowcancel["barcode"];
		        $pnm = $rowcancel["pnm"];
		        $desc = $rowcancel["description"];
		        $qty = $rowcancel["qty_canceled"];
		        $otc = $rowcancel["otc"];
		        $parts = $rowcancel["parts"];
		        $amt=$qty*$otc;
		        
		      //  $discountrate = $rowcancel["discountrate"];
		      //  $vatrate = $rowcancel["vatrate"];
		      //  $itmvat=($amt*$vatrate*0.01);
		      //  $disctot=$amt-($amt*$discountrate*0.01);
		        
		      //  $tot=$tot+$disctot;
		      //  $totvat=$totvat+$itmvat;
		      //  $rateincvat = ($amt+$itmvat)/$qty ;//(($disctot+$itmvat)/$qty)*100/(100-$rowcancel["discountrate"]);
		        
		        
		        $discounttot=$rowcancel["discounttot"]/$qty;
		        $discount_amounttot=$rowcancel["discount_amount"]/$qty;
		        
		        $discountrate = $rowcancel["discountrate"];
		        $vatrate = $rowcancel["vatrate"];
		        $itmamtincvat=$amt+($amt*$vatrate*0.01);
		        
		        $itmvat=$discounttot/(100+$vatrate)*$vatrate;//($amt*$vatrate*0.01);
		        $disctot=$amt-($amt*$discountrate*0.01);
		        
		        $tot=$tot+$discounttot;
		        $totvat=$totvat+$itmvat;
		        $rateincvat = (($disctot+$itmvat)/$qty)*100/(100-$rowcancel["discountrate"]);
		        
		        
		        
		        $length = $rowcancel["length"];$lengthunit = $rowcancel["lengthunit"];
		        $width = $rowcancel["width"];$widthunit = $rowcancel["widthunit"];
		        $height = $rowcancel["height"];$heightunit = $rowcancel["heightunit"];
		        $color = $rowcancel["colortext"];
		        $dimension="L:$length X$lengthunit,W:$width X $widthunit, H: $height X $heightunit,Color: $color";
		        if ( strlen($rowcancel["image"]) > 0){
        		    $photo= $hostpath."/assets/images/products/300_300/".$rowcancel["image"];
        		}else{
        			$photo= $hostpath."/assets/images/products/placeholder.png";
        		}
		        
	 ?> 
	  
    <tr>
      <td align="center"><?=$sosl?></td>
      <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-item-detail">
		  <tbody>
			<tr>
			  <td style="width: 150px"><img src="<?=$photo?>" width="100%"></td>
			  <td>
				
					<strong><?=$code?></strong><br>
					<strong><?= $pnm ?></strong>
					<br>
					Number of Parts: <?=$parts?><br>
				  
			  </td>
			</tr>
		  </tbody>
		</table>

		</td>
      <td align="center"><?=$qty?></td>
      <td class="number"><?=number_format($otc, 2, ".", ",")?></td>
      <td class="number"><?=number_format(($itmamtincvat), 2, ".", ",")?></td>
      <td class="number"><?=number_format($discount_amounttot, 2, ".", ",")?></td>
      <td class="number"><?=number_format($discounttot, 2, ".", ",")?></td>
    </tr>
<?php }
$amount=$tot+$totvat; 
}?>

	<!--tr>
		  <td align="center">1</td>
		  <td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-item-detail">
			  <tbody>
				<tr>
				  <td><img src="assets/images/products/800_800/SAMPLEPRODUCT.jpg" width="100%"></td>
				  <td>

						<strong>1600515</strong><br>
						G3-DC-13: Study Chair<br>
						KD116-21 Charpie Fabric With
						Stainless Steel leg, Color-Teal<br>
						L-22''W-24''H-31''<br>

				  </td>
				</tr>
			  </tbody>
			</table>

			</td>
		  <td align="center">1</td>
		  <td class="number">53,750.00</td>
		  <td class="number">53,750.00</td>
		  <td class="number">0.00</td>
		  <td class="number">53,750.00</td>
		</tr-->	  

	<tr>
      <td colspan="5" rowspan="7" class="no-lb-border">&nbsp;</td>
      <th class="itemtbl-footer">SUB TOTAL</th>
      <td class="number strong"><?php echo number_format($tot, 2, ".", ",") ?></td>
    </tr>
    <!--tr>
      <th class="itemtbl-footer">TOTAL DISC.</th>
      <td class="number">0.00</td>
    </tr-->
    <tr>
      <th class="itemtbl-footer">TOTAL VAT</th>
      <td class="number" style="font-size:11px;color: #c0c0c0">(<?php echo number_format($totvat, 2, ".", ","); ?>)</td>
    </tr>
    <!--tr>
      <th class="itemtbl-footer">ADJMT(-)</th>
      <td class="number"><?php echo number_format($discount, 2, ".", ","); ?></td>
    </tr>
    <tr>
      <th class="itemtbl-footer">DLV. CHARGE</th>
      <td class="number"><?php echo number_format($deliveryamt, 2, ".", ","); ?></td>
    </tr-->
    <tr>
      <th class="itemtbl-footer">TOTAL</th>
      <td class="number strong"><?php echo number_format($tot, 2, ".", ","); ?></td>
    </tr>	  

	  
	  
  </tbody>
</table>

<?php
}//if($isCanceled>0){
?>


<div class="terms-wrapper">	
<hr>
	<h2>Terms and Conditions</h2>
<hr>
<br>
	

<h3>Mode of Payment</h3>	
<ol>
	<li>Payments can be paid by cash, credit/debit cards and account payee cheque in favor of "Renaissance Decor Limited.</li>
</ol>
	
<h3>Delivery terms</h3>	
<ol>
	<li>Payment must be fully paid before delivery.</li> 
	<li>Free delivery within Dhaka city available on Friday, Saturday & Wednesday. Delivery on other days are chargeable Tk:5000/trip. Delivery hours:10am to 8pm</li>
	<li>For outside Dhaka, delivery charges will vary.</li>
</ol>	
	
<h3>Warehousing Service</h3>	 
<ol>
	<li>Free storage for 60 days from invoice date. (Month 1st & 2nd ).</li> 
	<li>Next 60 days storage charges will be @ 1% of the undelivered product value.</li>
	<li>After 120 days, if client does not receive the delivery, then RDL will release the products and will transfer any advance paid as store credit, after deducting applicable warehousing charges as per store policy.</li>
</ol>

<h3>Store credit term</h3>
<ol>
    <li>RDL will give store credit instead of refund, which will be valid following 6 months</li>	
	<li>If any client requires RDL to store products for the 5th month, RDL will charge for storage @3% of the undelivered products and total charges will have to be paid in advance. After 150 days, RDL will not store products any longer.</li>
	<li>After 300 days any deposit made will be forfeited and the sales order will be closed.</li>
</ol>		
</div>
<h4>FREE STORAGE ONLY FOR 60 DAYS.<br>
NO RETURN NO REFUND NO EXCHANGE</h4>

<strong>Issued By (Renaissance Decor Ltd)</strong>

	
	</div>
</body>
</html>
