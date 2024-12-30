<?php
require "common/conn.php";
session_start();

$usr=$_SESSION["user"];
$mod= $_GET['mod'];

$branch = $_GET["branch"]; if($branch == '') $branch = 0;
$bc1  = $_GET["barcode"];

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
    $qry="SELECT t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode,DATE_FORMAT(s.expirydt,'%e/%c/%Y') expirydt 
            FROM chalanstock s LEFT JOIN item p ON s.product = p.id 
            LEFT JOIN itemtype t ON p.catagory=t.id 
            LEFT JOIN branch r ON s.storerome=r.id where s.`freeqty`>0 $date_qry and (s.barcode like '%".$bc1."%' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = 0 )
            order by s.id DESC";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($row2 = $resultinv->fetch_assoc())
    					 {
    					    $tnm=$row2["tn"]; $prod=$row2["pn"];$str=$row2["str"];  
                            $freeqty=$row2["freeqty"]; $cup=$row2["costprice"]; $mup=$row2["mrp"]; $bc=$row2["barcode"];
                            $cp=$row['freeqty']*$row['costprice'];
                            $mp=$row['freeqty']*$row['mrp'];
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$tnm.'</td>
		<td '.$tdStyle.'>'.$prod.'</td>
		<td '.$tdStyle.'>'.$bc.'</td>
		<td '.$tdStyle.'>'.$str.'</td>
		<td '.$tdStyle.'>'.$row2["expirydt"].'</td>
		<td '.$tdStyle.'>'.number_format($freeqty, 0, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($cup, 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($cp, 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($mup, 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($mp, 2, ".", ",").'</td>
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
$obj_pdf->SetTitle("Expired Stock Report");
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
		<td width="50%" align="right"><h1>Expired Stock Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Category</td>
		<td '.$thStyle.'>Product</td>
		<td '.$thStyle.'>Barcode</td>
		<td '.$thStyle.'>Store</td>
		<td '.$thStyle.'>Expired Date</td>
		<td '.$thStyle.'>QTY</td>
		<td '.$thStyle.'>Cost Rate</td>
		<td '.$thStyle.'>Cost Price</td>
		<td '.$thStyle.'>MRP</td>
		<td '.$thStyle.'>MRP Total</td>
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
</table>					
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$obj_pdf->OutPut("Expired_Stock_Report","I");
//echo $content;
}
?>
