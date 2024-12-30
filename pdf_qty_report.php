<?php
require "common/conn.php";
session_start();

$usr=$_SESSION["user"];
$mod= $_GET['mod'];


$fdt = $_REQUEST['filter_date_from'];
$tdt = $_REQUEST['filter_date_to'];
if ($fdt == '') {$fdt = date("1/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}

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
    $qry="select c.socode,date_format(c.`orderdate`,'%d/%m/%y') orderdate,b.productid,a.name,b.qty orderqty,b.otc orderRate,(b.qty*b.otc) sellamount
          ,d.freeqty availableQty,d.costprice CostRate,(case WHEN d.freeqty<b.qty then (b.qty-d.freeqty) else 0 end)requiredQty
          from  soitem c left join soitemdetails b on b.socode=c.socode left join item a on b.productid=a.id left join stock d on a.id=d.product
          where c.orderstatus in(1,4) and  c.orderdate between STR_TO_DATE('".$fdt."','%d/%m/%Y') and STR_TO_DATE('".$tdt."','%d/%m/%Y')  ORDER BY  c.id DESC";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["socode"].'</td>
		<td '.$tdStyle.'>'.$rowinv["orderdate"].'</td>
		<td '.$tdStyle.'>'.$rowinv["name"].'</td>
		<td '.$tdStyle.'>'.$rowinv["orderqty"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["orderRate"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["sellamount"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["availableQty"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["CostRate"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["requiredQty"], 2, ".", ",").'</td>

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
$obj_pdf->SetTitle("Quantity Report");
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
		<td width="50%" align="right"><h1>Quantity Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>SO Code</td>
		<td '.$thStyle.'>Order Date</td>
		<td '.$thStyle.'>Product Name</td>
		<td '.$thStyle.'>Order Quantity</td>
		<td '.$thStyle.'>Order Rate</td>
		<td '.$thStyle.'>Sell Amount</td>
		<td '.$thStyle.'>Available Quantity</td>
		<td '.$thStyle.'>Cost Rate</td>
		<td '.$thStyle.'>Required Rate</td>
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
	
$obj_pdf->OutPut("Quantity_Report_".$datef."_to_".$datet,"I");
//echo $content;
}
?>
