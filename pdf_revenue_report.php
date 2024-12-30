<?php
session_start();

require "common/conn.php";
require "rak_framework/misfuncs.php";
require "rak_framework/fetch.php";


$usr=$_SESSION["user"];
$mod= $_GET['mod'];

$fd1 = $_GET["dt_f"];
$td1 = $_GET["dt_t"];

$fdt = $fd1;
$tdt = $td1;
        
if($fd1 == ''){
    $dateqry = "";
}else{
    $dateqry = " and q.orderdate BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
}

$datef = str_replace('/', '-', $fdt);
$datet = str_replace('/', '-', $fdt);

$datef =  date("d-m-Y", strtotime($datef));
$datet =  date("d-m-Y", strtotime($datet));


//settings;
$reportTitle = "Revenue Report";


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
    $qry="SELECT DATE_FORMAT(q.orderdate,'%d/%b/%Y') AS date, q.socode order_id, o.name AS customer, 
                                FORMAT(SUM(qd.otc), 2) AS amount, FORMAT(SUM(qd.vat), 2) AS vat, FORMAT(SUM(q.adjustment), 2) AS adjustment_amount,
                                FORMAT(SUM(q.deliveryamt), 2) AS delivery_amount, FORMAT(SUM(qd.discounttot), 2) AS discounted_total,
                                FORMAT(SUM(q.invoiceamount), 2) AS revenue, FORMAT(SUM(qd.cost), 2) AS cost, FORMAT(SUM(q.invoiceamount - qd.cost), 2) AS margin
                                FROM quotation AS q JOIN quotation_detail AS qd ON q.socode = qd.socode JOIN organization AS o ON q.organization = o.id
                                WHERE 1=1 $dateqry
                                GROUP BY q.orderdate, q.socode, o.name 
        ";
    	  //echo  $qry;die;
		//dumpTxt($qry);
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["date"].'</td>
		<td '.$tdStyle.'>'.$rowinv["order_id"].'</td>
		<td '.$tdStyle.'>'.$rowinv["customer"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['amount'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['vat'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['adjustment_amount'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['delivery_amount'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['discounted_total'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['revenue'],2).'</td>
		
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
								<td '.$thStyle.'>Date</td>
                                <td '.$thStyle.'>Order ID</td>
                                <td '.$thStyle.'>Customer</td>
                                <td '.$thStyle.'>Amount</td>
                                <td '.$thStyle.'>Vat</td>
                                <td '.$thStyle.'>Adjustment Amount</td>
                                <td '.$thStyle.'>Delivery Amount</td>
                                <td '.$thStyle.'>Discounted Total</td>
                                <td '.$thStyle.'>Revenue</td>
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

