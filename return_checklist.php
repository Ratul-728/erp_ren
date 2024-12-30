<?php
require "common/conn.php";
ini_set('display_errors',0);

session_start();

$roid = $_GET["roid"];

$qryInfo = "SELECT i.invoiceno, org.name customer, org.contactno, org.address, DATE_FORMAT(qt.orderdate, '%d/%b/%Y') orderdate, ro.scm, ro.packed, ro.showroom,
            ro.barcode,ro.stock, ds.name district,cn.name country, a.name area,  org.street, org.zip  
            FROM `qa` q LEFT JOIN quotation qt ON q.order_id=qt.socode LEFT JOIN invoice i ON i.soid=q.order_id LEFT JOIN organization org ON org.id=qt.organization 
            LEFT JOIN return_order ro ON ro.order_id=q.order_id
            LEFT JOIN district ds ON org.district = ds.id
    		LEFT JOIN country cn ON org.country = cn.id
    		LEFT JOIN area a ON org.area = a.id
            WHERE ro.ro_id = '$roid'";
$resultInfo = $conn->query($qryInfo);
while ($rowInfo = $resultInfo->fetch_assoc()) {
    $invid = $rowInfo["invoiceno"];
    $customer = $rowInfo["customer"];
    $contactno = $rowInfo["contactno"];
    //$address = $rowInfo["address"];
    $orderdate = $rowInfo["orderdate"];
    
    $scm = $rowInfo["scm"];
    $packed = $rowInfo["packed"];
    $showroom = $rowInfo["showroom"];
    $pastedbarcode = $rowInfo["barcode"];
    $stock = $rowInfo["stock"];
    
    $address = ($rowInfo["street"])?$rowInfo["street"]."<br>":"";
	$address .= ($rowInfo["area"])?$rowInfo["area"].", ":"";
	$address .= ($rowInfo["district"])?$rowInfo["district"]:"";
	$address .= ($rowInfo["zip"])?"-".$rowInfo["zip"].", ":"";
	$address .= ($rowInfo["country"])?$rowInfo["country"]:"";
}

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$invid?></title>
<link rel="icon" href="images/favicon.png">
<link href="css/fonts.css" rel="stylesheet">	
<link rel="stylesheet" href="css/icheck-bootstrap.min.css" />
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
	
	
	
h5,th,h3,h4,h2,strong{
   font-weight: normal;
   font-family: robotobold; 
}

.mgt-approval{
    border: 1px solid #000;
    height: 60px;
    padding: 10px;
    margin-bottom: 20px;
}

h3{
    background-color: #EFEFEF;
    padding: 8px;
    margin-top: 20px!important;
    margin-bottom: 10px!important;
}

.logotable{
    margin-bottom: 0;
}

.product_detail td{
    padding: 10px;
}
	
.product_checklist > tbody > tr > td, 
.tbl-orderinfo td{
   padding: 10px; 
}

kbd.approved
{
  background-color: #3fb06e;
  border: 0 !important;
  box-shadow: none;
}
kbd
{
  font-family: robotoregular;
  padding: 6px 8px!important;
}
kbd
{
  padding: 2px 4px;
  font-size: 90%;
  color: #fff;
  background-color: #333;
  border-radius: 3px;
  -webkit-box-shadow: inset 0 -1px 0 rgba(0,0,0,.25);
  box-shadow: inset 0 -1px 0 rgba(0,0,0,.25);
}
</style>	
</head>

<body>

<div class="print-wrapper">
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-header logotable">
  <tbody>
    <tr>
      <td><img src="<?=$hostpath?>/assets/images/site_setting_logo/logo_letterhead.png" width="100%"></td>
	  <td align="right"><h1>RETURN CHECKLIST</h1></td>
    </tr>
  </tbody>
</table>


<table width="100%" border="1" cellspacing="0" cellpadding="10" class="tbl-orderinfo">
  <tbody>
    <tr>
		<td valign="top">
			<b>INVOICE NO/TISS NO: <?= $invid ?></b><br>
			CLIENT NAME: <?= $customer ?><br>
			ADDRESS: <?= $address ?><br>
			PHONE: <?= $contactno ?>
			
		</td>
		
		<td valign="top" align="right">
            <b>DATE: <?= $orderdate ?></b><br><br>
        <?php if($scm == 1){ ?>
            <b>MANAGEMENT APPROVAL:</b> <kbd class="approved">Approved</kbd>
        <?php } else { ?>
            <b>MANAGEMENT APPROVAL:</b> <kbd class="pending">Pending</kbd>
        <?php } ?>
		</td>			
		
    </tr>
  </tbody>
</table>	



<h3>PRODUCT LIST </h3> 
<table width="100%" border="1" cellspacing="0" cellpadding="10"  class="product_checklist">
  <tbody>
    <tr class="inv-header">
      <th>PRODUCT DETAILS </th>
      <th style="width:50px; text-align:center">QTY</th>
      <th colspan=4>PRODUCT CHECKLIST REPORT [QA TEAM]  </th>
    </tr>
<?php
    $qryDetails = "SELECT i.name, i.barcode, i.description, i.image, qaw.ordered_qty, qaw.pass_qty, qaw.defect_qty, qaw.damaged_qty 
                    FROM `qa` q LEFT JOIN qa_warehouse qaw ON q.id=qaw.qa_id LEFT JOIN item i ON i.id=q.product_id
                    WHERE q.order_id = '$roid'";
    $resultDetails = $conn->query($qryDetails);
    while ($rowDetails = $resultDetails->fetch_assoc()) {
        $pnm = $rowDetails["name"];
        $barcode = $rowDetails["barcode"];
        $description = $rowDetails["description"]; $description = substr($description, 0, 20);
        $image = $rowDetails["image"];
        $ordered_qty = $rowDetails["ordered_qty"];
        $pass_qty = $rowDetails["pass_qty"];  if($pass_qty == "") $pass_qty = 0; 
        $defect_qty = $rowDetails["defect_qty"]; if($defect_qty == "") $defect_qty = 0; 
        $damaged_qty = $rowDetails["damaged_qty"]; if($damaged_qty == "") $damaged_qty = 0; 
?>
    <tr>
      <td>
          <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tr>
              <td width="100"><b>ITEM NAME:</b></td><td><?= $pnm ?></td>
            </tr>
            <tr>
              <td><b>BARCODE:</b></td><td><?= $barcode ?></td>
            </tr>
            <tr>
              <td><b>DESC:</b></td><td> <?= $description ?></td>
              </tr>
          </table>

          
        </td>
        <td align="center"><?=$ordered_qty?></td>
      <td align="center"><?=$pass_qty ?> Passed</td>
      <td align="center"><?= $defect_qty?> defect</td>
      <td align="center"><?= $damaged_qty ?> Damaged</td>
      <td  style="width:50px;">
         <img src="assets/images/products/300_300/<?= $image ?>" width="50" height="50">
      </td>
      
    </tr>

<?php } ?>

</table>	
<br>



<div class="checklist">
    <table border="0" cellpadding="5" cellspacing="2">
        <tr>
            <td style="border-bottom:1px solid #919191;padding-bottom:6px;">
            <b>CHECKLIST FOR LOGISTIC TEAM FOR RETURNING PRODUCT:</b><br>
            Logistic Team will bring the product back to designed warehouse, paste a barcode on the product and place it in general stock under proper category.
    </td>
    </tr>
    <tr>
        <td style="padding-top:6px;">
        <div>
    		<div class="icheck-primary privwrap">
    			<input type="checkbox" name="product_packed" value="1" disabled <?php if($packed == 1) echo "checked" ?> id="product_packed">
    			<label for="product_packed">PRODUCT PACKED AT THE CLIENT’S HOUSE</label>
    		</div>
        </div> 
        </td>
    </tr>
    <tr>
        <td>
    <div>
		<div class="icheck-primary privwrap">
			<input type="checkbox" name="product_takento" value="1" disabled <?php if($showroom == 1) echo "checked" ?> id="product_takento">
			<label for="product_takento">TANEN TO   <b>SHOWROOM</b>   &nbsp; WAREHOUSE</label>
		</div>
    </div>
    </td>
    </tr>
    <tr>
        <td>
    <div>
		<div class="icheck-primary privwrap">
			<input type="checkbox" name="pasted_barcode" value="1" disabled <?php if($pastedbarcode == 1) echo "checked" ?> id="pasted_barcode">
			<label for="pasted_barcode">PASTED BARCODE</label>
		</div>
    </div>  
    </tr>
    <tr>
        <td>
    <div>
		<div class="icheck-primary privwrap">
			<input type="checkbox" name="product_stocked" value="1" disabled <?php if($stock == 1) echo "checked" ?> id="product_stocked">
			<label for="product_stocked">PLACED IN GENRAL STOCK</label>
		</div>
    </div>  
    </td>
    </tr>
</table>

</div>




</body>
</html>

