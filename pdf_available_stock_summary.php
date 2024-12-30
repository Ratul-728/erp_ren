<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];

$brand = $_GET["brand"];
$cat = $_GET["cat"];

if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
	
$tdStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; border:1px solid #efefef; white-space:nowrap;"';

$i = 1;	
$loop = "";
    $qry="select p.code,p.name,p.rate,p.barcode,p.image,s.freeqty,r.title brand,i.name catagory
                                from stock s join item p on s.product=p.id 
                                left join brand r on p.brand=r.id left join itmCat i on p.catagory=i.id
                                where  s.freeqty>0
                                and (p.brand=$brand or $brand = 0) and (p.catagory=$cat or $cat = 0)
                                 ORDER BY  p.id DESC";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					 

	$loop .='						 
	<tr>
	    <td '.$tdStyle.'>'.$i.'</td>
		<td '.$tdStyle.'>'.$rowinv["code"].'</td>
		<td '.$tdStyle.'>'.$rowinv["name"].'</td>
		<td '.$tdStyle.'>'.$rowinv["barcode"].'</td>
		<td '.$tdStyle.'>'.$rowinv["brand"].'</td>
		<td '.$tdStyle.'>'.$rowinv["catagory"].'</td>
		<td '.$tdStyle.'>'.$rowinv["rate"].'</td>
		<td '.$tdStyle.'>'.$rowinv["freeqty"].'</td>

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
$obj_pdf->SetTitle("Available Stock Summary Report");
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
		<td width="50%" align="right"><h1>Available Stock Summary Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL</td>
		<td '.$thStyle.'>Product Code</td>
		<td '.$thStyle.'>Product</td>
		<td '.$thStyle.'>Barcode</td>
		<td '.$thStyle.'>Brand</td>
		<td '.$thStyle.'>Category</td>
		<td '.$thStyle.'>Rate</td>
		<td '.$thStyle.'>Free Quantity</td>
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
	
$obj_pdf->OutPut("Available_Stock_Summary_Report","I");
//echo $content;
}
?>