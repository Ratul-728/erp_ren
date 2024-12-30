<?php
require "common/conn.php";
session_start();

$usr=$_SESSION["user"];
$mod= $_GET['mod'];

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
     $dagent = $_GET["dagnt"];
	 if($dagent !=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	
$tdStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; border:1px solid #efefef; white-space:nowrap;"';

$sl = 1;
$loop = "";
    $qry="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
    ,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr
    ,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent,concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno,org.email
    FROM  soitem o left join orderstatus s on o.orderstatus=s.id
     left join organization org on o.organization=org.id
    left join district d on org.district=d.id
    left join area a on org.area=a.id
    left join contact c on o.customer=c.id
    left join district d1 on c.district=d1.id
    left join area a1 on c.area=a1.id
	left join deveryagent da on o.deliveryby=da.id
    where 1=1 ".$pqry." and o.orderstatus=4";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($row2 = $resultinv->fetch_assoc())
    					 {

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.'>'.$row2["order_id"].'</td>
		<td '.$tdStyle.'>'.$row2["invoiceno"].'</td>
		<td '.$tdStyle.'>'.$row2["order_date"].'</td>
		<td '.$tdStyle.'>'.$row2["cusnm"].'</td>
		<td '.$tdStyle.'>'.$row2["cusaddr"].'</td>
		<td '.$tdStyle.'>'.$row2["phone"].'</td>
		<td '.$tdStyle.'>'.$row2["payment_mood"].'</td>
		<td '.$tdStyle.'>'.number_format($row2['amount'],2).'</td>
		
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
$obj_pdf->SetTitle("Delivery Statement");
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
		<td width="50%" align="right"><h1>Delivery Statement</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Order No</td>
		<td '.$thStyle.'>Invoice No</td>
		<td '.$thStyle.'>Order Date</td>
		<td '.$thStyle.'>Customer Name</td>
		<td '.$thStyle.'>Billing Address</td>
		<td '.$thStyle.'>Cell No</td>
		<td '.$thStyle.'>Payment Mood</td>
		<td '.$thStyle.'>Amount</td>
		
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
</table>					
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$obj_pdf->OutPut("Delivery_Statement","I");
//echo $content;
}
?>
