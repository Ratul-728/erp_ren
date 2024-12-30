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
	
$tdStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; border:1px solid #efefef; white-space:nowrap;"';

$sl = 1;
$loop = "";
    $qry="SELECT DATE_FORMAT(s.`terminationDate`, '%d-%m-%Y') tdt,c.name `terminationcause`,h.hrName,i.name itmnm
,ic.name itmcat,p.name comtp,i.size ,o.name ornm,s.socode,DATE_FORMAT(s.effectivedate , '%d-%m-%Y') efdt,cr.shnm,round(d.otc,2) otc,round(d.mrc,2) mrc
FROM soitem s left join soitemdetails d on s.`socode`= d.`socode`
	left join terminationcause c on s.`terminationcause`=c.id
    left join organization o on s.`organization`=o.id
    left join hr h on o.`salesperson`=h.`id`
    left join item i on d.`productid`=i.`id`
    left join itmCat ic on i.`catagory`=ic.id
    left join pattern p on i.`pattern`=p.`id`
    left join currency cr on d.currency=cr.id
WHERE    `terminationDate`<sysdate()
order by s.`socode`";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($row2 = $resultinv->fetch_assoc())
    					 {
					        $acvp=$row2['acv']/$row2['target']*100;

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.'>'.$row2["tdt"].'</td>
		<td '.$tdStyle.'>'.$row2["terminationcause"].'</td>
		<td '.$tdStyle.'>'.$row2["hrName"].'</td>
		<td '.$tdStyle.'>'.$row2["itmnm"].'</td>
		<td '.$tdStyle.'>'.$row2["itmcat"].'</td>
		<td '.$tdStyle.'>'.$row2["size"].'</td>
		<td '.$tdStyle.'>'.$row2["comtp"].'</td>
		<td '.$tdStyle.'>'.$row2["ornm"].'</td>
		<td '.$tdStyle.'>'.$row2["socode"].'</td>
		<td '.$tdStyle.'>'.$row2["efdt"].'</td>
		<td '.$tdStyle.'>'.$row2["shnm"].'</td>
		<td '.$tdStyle.'>'.$row2["mrc"].'</td>
		<td '.$tdStyle.'>'.$row2["otc"].'</td>
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
$obj_pdf->SetTitle("Terminated Sales Report");
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
		<td width="50%" align="right"><h1>Terminated Sales Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Termination Date</td>
		<td '.$thStyle.'>Termination Cause</td>
		<td '.$thStyle.'>Account Manager</td>
		<td '.$thStyle.'>Item</td>
		<td '.$thStyle.'>Item Category</td>
		<td '.$thStyle.'>Company Type</td>
		<td '.$thStyle.'>License Type</td>
		<td '.$thStyle.'>Organization</td>
		<td '.$thStyle.'>SO Code</td>
		<td '.$thStyle.'>Effective Date</td>
		<td '.$thStyle.'>Currency</td>
		<td '.$thStyle.'>MRC</td>
		<td '.$thStyle.'>OTC</td>
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
</table>					
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$obj_pdf->OutPut("Terminated_Sales_Report","I");
//echo $content;
}
?>
