<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];

$prod = $_GET["product"];
$org = $_GET["org"];

if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
	
$tdStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; border:1px solid #efefef; white-space:nowrap;"';

$i = 1;	
$loop = "";
    $qry="select i.invoiceno,i.invoicedt,o.orgcode,o.name customer,p.name product,p.code,p.barcode,d.qty,d.otc,(d.qty*d.otc) amount,d.discountrate,d.discounttot,d.vatrate,d.vat,(d.discounttot+d.vat) total_amount
          from invoice i left join organization o on  i.organization=o.id left join soitemdetails d on i.soid=d.socode left join item p on  d.productid=p.id
          where (d.productid =$prod or $prod = 0 ) and (i.organization =$org or $org = 0)
          ORDER BY  i.id DESC";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					 

	$loop .='						 
	<tr>
	    <td '.$tdStyle.'>'.$i.'</td>
		<td '.$tdStyle.'>'.$rowinv["invoiceno"].'</td>
		<td '.$tdStyle.'>'.$rowinv["invoicedt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["orgcode"].'</td>
		<td '.$tdStyle.'>'.$rowinv["customer"].'</td>
		<td '.$tdStyle.'>'.$rowinv["code"].'</td>
		<td '.$tdStyle.'>'.$rowinv["product"].'</td>
		<td '.$tdStyle.'>'.$rowinv["qty"].'</td>
		<td '.$tdStyle.'>'.$rowinv["otc"].'</td>
		<td '.$tdStyle.'>'.$rowinv["amount"].'</td>
		<td '.$tdStyle.'>'.$rowinv["discountrate"].'</td>
		<td '.$tdStyle.'>'.$rowinv["discounttot"].'</td>
		<td '.$tdStyle.'>'.$rowinv["vatrate"].'</td>
		<td '.$tdStyle.'>'.$rowinv["vat"].'</td>
		<td '.$tdStyle.'>'.$rowinv["total_amount"].'</td>

	</tr>							 
		';					 
							 
            			$i++; }
    				}

$thStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; background-color:#efefef; border:1px solid #c0c0c0;"';







require_once("tcpdf_min/tcpdf.php");
	
	
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {


    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
		$text = "RENAISSANCE DECOR LTD - Page:  ".$this->getAliasNumPage().'/'.$this->getAliasNbPages();
		 $this->Cell(0, 10, $text, 0, false, 'C', 0, '', 0, false, 'T', 'M');		
    }
}
	
	
//$obj_pdf= new MYPDF('P',PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
$obj_pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);	
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetTitle("Total Invoice Report");
$obj_pdf->SetHeaderData('','',PDF_HEADER_TITLE,PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA,'',PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('arial');
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//$obj_pdf->SetMargins(PDF_MARGIN_LEFT,'2',PDF_MARGIN_RIGHT);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT-10, 5, PDF_MARGIN_RIGHT-10);
	
$obj_pdf->SetPrintHeader(false);
$obj_pdf->SetPrintFooter(true);
$obj_pdf->SetAutoPageBreak(TRUE,10);
$obj_pdf->SetFont('helvetica','',6);

$content='';
$content.='
<table width="100%" border="0"  cellspacing="0" cellpadding="0">
   <thead>
	<tr>
		<td width="50%"><img width="100" src="./assets/images/site_setting_logo/'.$_SESSION["comlogo"].'" alt=""></td>
		<td width="50%" align="right"><h1>Total Invoice Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL</td>
		<td '.$thStyle.'>Invoice no</td>
		<td '.$thStyle.'>Invoice Date</td>
		<td '.$thStyle.'>Customer Code</td>
		<td '.$thStyle.'>Customer</td>
		<td '.$thStyle.'>Product Code</td>
		<td '.$thStyle.'>Product</td>
		<td '.$thStyle.'>Quantity</td>
		<td '.$thStyle.'>OTC</td>
		<td '.$thStyle.'>Amount</td>
		<td '.$thStyle.'>Discount Rate</td>
		<td '.$thStyle.'>Total Discount</td>
		<td '.$thStyle.'>Vat Rate</td>
		<td '.$thStyle.'>Vat</td>
		<td '.$thStyle.'>Total Amount</td>
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
</table>					
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$fdt = date("d/m/Y");
$tdt = date("d/m/Y");
	
$obj_pdf->OutPut("total_invoice_Report","I");
//echo $content;
}
?>
