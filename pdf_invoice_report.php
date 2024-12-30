<?php
require "common/conn.php";
session_start();

$usr=$_SESSION["user"];
$mod= $_GET['mod'];

$fdt = $_REQUEST['filter_date_from'];
$tdt = $_REQUEST['filter_date_to'];
if ($fdt == '') {$fdt = date("1/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}

$fdquery=" and p.transdt >='".$fdt."' ";
$tdquery=" and p.transdt <='".$tdt."' ";

//echo $fdt;

$datef = str_replace('/', '-', $fdt);
$datet = str_replace('/', '-', $fdt);

$datef =  date("d-m-Y", strtotime($datef));
$datet =  date("d-m-Y", strtotime($datet));

if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
	
$tdStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; border:1px solid #efefef; white-space:nowrap;"';

$sl = 1;
$loop = "";
    $qry="select  p.id,i.invoiceno,date_format(i.invoicedt,'%d/%m/%y') invoicedt,i.invyr,i.invoicemonth,i.invoiceamt,i.soid,o.name, date_format(p.transdt,'%d/%m/%y') transdt

                ,(case when p.transmode ='W' then 'Wallet' else 'Cash' end) transmode  

                ,p.amount,p.remarks

from invoice i, organization o, invoicepayment p

where  i.invoiceno=p.invoicid  and i.organization=o.id  ORDER BY p.id DESC";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["invoiceno"].'</td>
		<td '.$tdStyle.'>'.$rowinv["invoicedt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["invyr"].'</td>
		<td '.$tdStyle.'>'.date("F", strtotime($rowinv['invoicemonth'])).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["invoiceamt"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.$rowinv["soid"].'</td>
	    <td '.$tdStyle.'>'.$rowinv["name"].'</td>
		<td '.$tdStyle.'>'.$rowinv["transdt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["transmode"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["amount"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.$rowinv["remarks"].'</td>

	</tr>							 
		';
		$sl++;
							 
            			 }
    				}

$thStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; background-color:#efefef; border:1px solid #c0c0c0;"';







require_once("tcpdf_min/tcpdf.php");
	
	
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {


    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
		$text = $_SESSION["comname"]." - Page:  ".$this->getAliasNumPage().'/'.$this->getAliasNbPages();
		 $this->Cell(0, 10, $text, 0, false, 'C', 0, '', 0, false, 'T', 'M');		
    }
}
	
	
//$obj_pdf= new MYPDF('P',PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
$obj_pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);	
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetTitle("Invoice Report");
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
		<td width="50%" align="right"><h1>Invoice Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Invoice NO</td>
		<td '.$thStyle.'>Invoice Date</td>
		<td '.$thStyle.'>Invoice Year</td>
		<td '.$thStyle.'>Invoice Amount</td>
		<td '.$thStyle.'>SO ID</td>
		<td '.$thStyle.'>Organization</td>
		<td '.$thStyle.'>Paid Date</td>
		<td '.$thStyle.'>Pay Mode</td>
		<td '.$thStyle.'>Pay Amount</td>
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
	
$obj_pdf->OutPut("Invoice_Report_".$datef."_to_".$datet,"I");
//echo $content;
}
?>