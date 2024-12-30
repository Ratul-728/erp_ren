<?php
session_start();

require "common/conn.php";
require "rak_framework/misfuncs.php";
require "rak_framework/fetch.php";


$usr=$_SESSION["user"];
$mod= $_GET['mod'];


$fdt = $_REQUEST['filter_date_from'];
$tdt = $_REQUEST['filter_date_to'];

if($fdt == ''){
    $dateqry = "";
}else{
    $dateqry = " and l.makedt BETWEEN STR_TO_DATE('$fdt','%Y-%m-%d') and STR_TO_DATE('$tdt','%Y-%m-%d')";
}

//echo $fdt;

$datef = str_replace('/', '-', $fdt);
$datet = str_replace('/', '-', $fdt);

$datef =  date("d-m-Y", strtotime($datef));
$datet =  date("d-m-Y", strtotime($datet));


//settings;
$reportTitle = "Purchase";


//end settings;
/*******/
$startDate = $fdt;
$endDate = $tdt;



if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
	
$tdStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; border:1px solid #efefef; white-space:nowrap;"';

$sl = 1;
$loop = "";
    $qry="SELECT l.id puid,l.`voucher_no`, DATE_FORMAT(l.`voucher_date`,'%d/%b/%Y') `voucher_date`,l.`pi_no`, DATE_FORMAT(l.`pi_date`,'%d/%b/%Y') `pi_date`,
                                sup.name `supplier`,l.`lc_tt_no`, DATE_FORMAT(l.`lc_tt_date`,'%d/%b/%Y') `lc_tt_date` ,i.`com_invoice_val_usd` ,
                                l.`exchange_rate` ,i.`com_invoice_val_bdt`,i.`freight_charges`,i.`global_taxes`,i.`cd`,i.`rd`,i.`sd`,i.`vat`,i.tot_landed_cost, l.`at`,l.`ait`,b.`name` received_location ,
                                h.hrName received_by,l.`gnr_no`, DATE_FORMAT(l.`gnr_date`,'%d/%b/%Y') `gnr_date` ,pr.name prod,pr.description,pr.barcode,i.`qty`,i.`tot_value`, bn.name banknm,DATE_FORMAT(l.`bank_dt`,'%d/%b/%Y') `bank_dt`,l.`payment_amount`,
                                l.`remark`, pr.image 
                                from purchase_landing l left join suplier sup ON sup.id=l.warehouse left join purchase_landing_item i on l.id=i.pu_id left join item pr on i.productId=pr.id
                                left join branch b on l.warehouse=b.id left join bank bn on l.`bank_name`=bn.id left join hr h on l.`received_by`=h.id
                                where 1=1 $dateqry";
    	  //echo  $qry;die;
		//dumpTxt($qry);
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["voucher_no"].'</td>
		<td '.$tdStyle.'>'.$rowinv["voucher_date"].'</td>
		<td '.$tdStyle.'>'.$rowinv["pi_no"].'</td>
		<td '.$tdStyle.'>'.$rowinv["pi_date"].'</td>
		<td '.$tdStyle.'>'.$rowinv["supplier"].'</td>
		<td '.$tdStyle.'>'.$rowinv["lc_tt_no"].'</td>
		<td '.$tdStyle.'>'.$rowinv["lc_tt_date"].'</td>
		<td '.$tdStyle.'>'.$rowinv["com_invoice_val_usd"].'</td>
		<td '.$tdStyle.'>'.$rowinv["exchange_rate"].'</td>
		<td '.$tdStyle.'>'.$rowinv["com_invoice_val_bdt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["freight_charges"].'</td>
		<td '.$tdStyle.'>'.$rowinv["global_taxes"].'</td>
		<td '.$tdStyle.'>'.$rowinv["cd"].'</td>
		<td '.$tdStyle.'>'.$rowinv["rd"].'</td>
		<td '.$tdStyle.'>'.$rowinv["sd"].'</td>
		<td '.$tdStyle.'>'.$rowinv["vat"].'</td>
		<td '.$tdStyle.'>'.$rowinv["tot_landed_cost"].'</td>
		<td '.$tdStyle.'>'.$rowinv["at"].'</td>
		<td '.$tdStyle.'>'.$rowinv["ait"].'</td>
		<td '.$tdStyle.'>'.$rowinv["received_location"].'</td>
		<td '.$tdStyle.'>'.$rowinv["received_by"].'</td>
		<td '.$tdStyle.'>'.$rowinv["gnr_no"].'</td>
		<td '.$tdStyle.'>'.$rowinv["gnr_date"].'</td>
		<td '.$tdStyle.'>'.$rowinv["prod"].'</td>
		<td '.$tdStyle.'>'.$rowinv["description"].'</td>
		<td '.$tdStyle.'>'.$rowinv["barcode"].'</td>
		<td '.$tdStyle.'>'.$rowinv["qty"].'</td>
		<td '.$tdStyle.'>'.$rowinv["tot_value"].'</td>
		<td '.$tdStyle.'>'.$rowinv["banknm"].'</td>
		<td '.$tdStyle.'>'.$rowinv["bank_dt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["payment_amount"].'</td>
		<td '.$tdStyle.'>'.$rowinv["remark"].'</td>

	</tr>								 
		';
		$sl++;
							 
            			 }
    				}

$thStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; background-color:#efefef; border:1px solid #c0c0c0;"';

$height5px = '<img src="" height="5">';


$footerContent ='
'.$_SESSION["comname"].'. / '.str_replace(", Bangladesh","",str_replace("\n"," ",fetchByID('sitesettings',id,1,'address'))).' / '.fetchByID('sitesettings',id,1,'hotline').' / '.fetchByID('sitesettings',id,1,'email').' / '.fetchByID('sitesettings',id,1,'web').' / 
';

$headerContent = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <thead>
    <tr>
        <td width="50%"><img width="200" valign="3" src="./assets/images/site_setting_logo/' . $_SESSION["doc_header_logo"] . '" alt=""></td>
        <td width="50%" align="right">' . $height5px . '<h1 style="color:#c0c0c0">' . strtoupper($reportTitle) . '</h1></td>
    </tr>
    </thead>
</table>';

require_once("tcpdf_min/tcpdf.php");
	
	
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    // Page header and footer
    public function Header() {
        //global $headerContent;
        // Set the header content
        $this->SetY(10);
        $this->Cell(0, 0, $headerContent, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function Footer() {
        global $footerContent;
        // Position at 15 mm from the bottom
        $text = "Page: " . $this->getAliasNumPage() . '/' . $this->getAliasNbPages();
        $this->SetY(-15);
        $this->Cell(0, 10, $footerContent . $text, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

	
$obj_pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetTitle($reportTitle);
$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('arial');
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT - 10, 5, PDF_MARGIN_RIGHT - 10);
$obj_pdf->SetPrintHeader(true);
$obj_pdf->SetPrintFooter(true);
$obj_pdf->SetAutoPageBreak(true, 10);
$obj_pdf->SetFont('Helvetica', '', 6);

$content='';
$content.='







<table width="100%" border="0"  cellspacing="0" cellpadding="0">
   <thead>
	<tr>
		<td width="50%"><img width="200" valign="3" src="./assets/images/site_setting_logo/'.$_SESSION["doc_header_logo"].'" alt=""></td>
		<td width="50%" align="right">'.$height5px.'<h1 style="color:#c0c0c0">'.strtoupper($reportTitle).'</h1></td>
	</tr>
	</thead>		
</table>




<div style="font-size:8px;padding-bottom:50px;">
Showing '.$reportTitle.' report from '.$startDate.' to '.$endDate.' 
</div>
<br>

<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
								<td '.$thStyle.'>Voucher No</td>
                                <td '.$thStyle.'>Voucher Date</td>
                                <td '.$thStyle.'>PI NO</td>
                                <td '.$thStyle.'>PI Date</td>
                                <td '.$thStyle.'>Supplier</td>
                                <td '.$thStyle.'>LC/TT No</td>
                                <td '.$thStyle.'>LC/TT Date</td>
                                <td '.$thStyle.'>Invoice Value (USD)</td>
                                <td '.$thStyle.'>Exchange Rate</td>
                                <td '.$thStyle.'>Invocie Value (BDT)</td>
                                <td '.$thStyle.'>Freight Charges</td>
                                <td '.$thStyle.'>Global Taxes</td>
                                <td '.$thStyle.'>CD</td>
                                <td '.$thStyle.'>RD</td>
                                <td '.$thStyle.'>SD</td>
                                <td '.$thStyle.'>Vat</td>
                                <td '.$thStyle.'>Total landed cost</td>
                                <td '.$thStyle.'>AT</td>
                                <td '.$thStyle.'>AIT</td>
                                <td '.$thStyle.'>Received Location</td>
                                <td '.$thStyle.'>Received By</td>
                                <td '.$thStyle.'>GNR No</td>
                                <td '.$thStyle.'>GNR Date</td>
                                <td '.$thStyle.'>Product</td>
                                <td '.$thStyle.'>Description</td>
                                <td '.$thStyle.'>Barcode</td>
                                <td '.$thStyle.'>QTY</td>
                                <td '.$thStyle.'>Total Value</td>
                                <td '.$thStyle.'>Bank Name</td>
                                <td '.$thStyle.'>Bank Date</td>
                                <td '.$thStyle.'>Payment Amount</td>
                                <td '.$thStyle.'>Remarks</td>
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
$fielprefix = str_replace(" ","_",$reportTitle);
$obj_pdf->OutPut($fielprefix."_".$datef."_to_".$datet.".pdf","I");
//echo $content;
}



?>

