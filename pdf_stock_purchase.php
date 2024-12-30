<?php
require "common/conn.php";
session_start();

$usr=$_SESSION["user"];
$mod= $_GET['mod'];


$fdt = $_REQUEST['filter_date_from'];
$tdt = $_REQUEST['filter_date_to'];
if ($fdt == 'undefined') {$dateqry = "";}
else{
    $dateqry = "and p.delivery_dt BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
}

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
    $qry="SELECT p.poid,p.adviceno,DATE_FORMAT(p.orderdt,'%e/%c/%Y') orderdt,DATE_FORMAT(p.delivery_dt,'%e/%c/%Y') received_dt ,t.name cat,i.itemid,
                                pr.name product,i.qty,i.unitprice,i.amount,i.barcode,DATE_FORMAT(i.expirydt,'%e/%c/%Y') expirydt 
                                FROM po p LEFT JOIN poitem i ON p.poid=i.poid LEFT JOIN product pr ON pr.id=i.itemid LEFT JOIN itemtype t ON pr.catagory=t.id 
                                where 1=1 $dateqry
                                order by p.poid DESC";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["cat"].'</td>
		<td '.$tdStyle.'>'.$rowinv["cat"].'</td>
		<td '.$tdStyle.'>'.$rowinv["orderdt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["poid"].'</td>
		<td '.$tdStyle.'>'.$rowinv["adviceno"].'</td>
		<td '.$tdStyle.'>'.$rowinv["received_dt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["product"].'</td>
		<td '.$tdStyle.'>'.$rowinv["barcode"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["qty"], 0, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["unitprice"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["amount"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.$rowinv["expirydt"].'</td>

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
$obj_pdf->SetTitle("Stock Purchase Report");
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
		<td width="50%" align="right"><h1>Stock Purchase Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Product Category</td>
		<td '.$thStyle.'>Chalan Date</td>
		<td '.$thStyle.'>Chalan NO</td>
		<td '.$thStyle.'>Advice NO</td>
		<td '.$thStyle.'>Recieved Date</td>
		<td '.$thStyle.'>Product</td>
		<td '.$thStyle.'>Barcode</td>
		<td '.$thStyle.'>QTY</td>
		<td '.$thStyle.'>Unit Price</td>
		<td '.$thStyle.'>Total Price</td>
		<td '.$thStyle.'>Expire Date</td>
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
</table>					
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$obj_pdf->OutPut("Stock_Purchase_Report","I");
//echo $content;
}
?>
