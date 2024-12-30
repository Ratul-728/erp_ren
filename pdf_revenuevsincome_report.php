<?php
require "common/conn.php";
session_start();

$usr=$_SESSION["user"];
$mod= $_GET['mod'];

if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
	
$tdStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; border:1px solid #efefef; white-space:nowrap;"';

$sl = 1;
$loop = "";
    $qry="SELECT s.id,s.socode,o.name organization 
          ,sum(d.qty*d.otc) otc,sum(d.qtymrc*d.mrc) mrc
          ,(select  sum(`invoiceamt`) inv from invoice where soid=s.socode) rev
          ,(select  sum(`paidamount`) inv from invoice where soid=s.socode) inc
          ,(select  sum(`amount`)  from expense where soid=s.id) cost
          FROM soitem s left join organization o on s.organization=o.id
          left join soitemdetails d on s.socode=d.socode  group by s.id,s.socode,o.name 
          ORDER BY  s.id DESC";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["socode"].'</td>
		<td '.$tdStyle.'>'.$rowinv["organization"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["otc"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["mrc"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["rev"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["inc"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["cost"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format(($rowinv["inc"] - $rowinv["cost"]), 2, ".", ",").'</td>

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
$obj_pdf->SetTitle("Revenue Vs Income Report");
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
		<td width="50%" align="right"><h1>Revenue Vs Income Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>SO Code</td>
		<td '.$thStyle.'>Customer</td>
		<td '.$thStyle.'>OTC</td>
		<td '.$thStyle.'>MRC</td>
		<td '.$thStyle.'>Revenue</td>
		<td '.$thStyle.'>Earning</td>
		<td '.$thStyle.'>Costing</td>
		<td '.$thStyle.'>Margin</td>
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
</table>					
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$obj_pdf->OutPut("Revenue_Vs_Income_Report","I");
//echo $content;
}
?>
