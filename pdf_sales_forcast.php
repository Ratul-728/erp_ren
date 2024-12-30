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
    $qry="SELECT s.`socode`, s.`contType`, s.`cus_id`, s.`cus_nm`, s.`orderdate`, s.`yr`, s.`mnth`, s.`da`, s.`hrid`, s.`hrName`, s.`itmid`, s.`itmnm`, s.`otc`, s.`mrc`, s.`stage`, s.`prob`, s.`itm_cat`, s.`size`, s.`pattern`, s.`orgn`,r.yr,r.month,r.dy,r.`dt`
,(case when r.yr=s.yr and r.month=s.mnth then 'New' Else 'Existing' end ) stat
,(case when r.yr=s.yr and r.month=s.mnth then round((s.`mrc`*(r.dy-s.`da`+1))/r.dy,2) Else s.`mrc` end ) pmrc
,(case when r.yr=s.yr and r.month=s.mnth then round(s.`otc`,2) Else 0 end ) otcvalue
,(case when r.`dt`>sysdate() then 'Forcast' else 'Actual' end) frcst
,s.poc,s.currnm
FROM  `rpt_sales_so` s  ,`reportmanth` r
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr))";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($row2 = $resultinv->fetch_assoc())
    					 {
					        

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$row2["dt"].'</td>
		<td '.$tdStyle.'>'.$row2["poc"].'</td>
		<td '.$tdStyle.'>'.$row2["cus_nm"].'</td>
		<td '.$tdStyle.'>'.$row2["hrName"].'</td>
		<td '.$tdStyle.'>'.$row2["itmnm"].'</td>
		<td '.$tdStyle.'>'.$row2["itm_cat"].'</td>
		<td '.$tdStyle.'>'.$row2["size"].'</td>
		<td '.$tdStyle.'>'.$row2["pattern"].'</td>
		<td '.$tdStyle.'>'.$row2["orgn"].'</td>
		<td '.$tdStyle.'>'.$row2["socode"].'</td>
		<td '.$tdStyle.'>'.$row2["orderdate"].'</td>
		<td '.$tdStyle.'>'.$row2["currnm"].'</td>
		<td '.$tdStyle.'>'.$row2["pmrc"].'</td>
		<td '.$tdStyle.'>'.$row2["otcvalue"].'</td>
		<td '.$tdStyle.'>'.$row2["stage"].'</td>
		<td '.$tdStyle.'>'.$row2["Probability"].'</td>
		<td '.$tdStyle.'>'.$row2["stat"].'</td>
		<td '.$tdStyle.'>'.$row2["frcst"].'</td>
	</tr>							 
		';
		$sl++;
				echo $sl."\n";			 
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
$obj_pdf->SetTitle("Sales Forcast Report");
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
		<td width="50%" align="right"><h1>Sales Forcast Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Month</td>
		<td '.$thStyle.'>POC</td>
		<td '.$thStyle.'>Customer</td>
		<td '.$thStyle.'>Account Manager</td>
		<td '.$thStyle.'>Item</td>
		<td '.$thStyle.'>Item Category</td>
		<td '.$thStyle.'>Item Type</td>
		<td '.$thStyle.'>Company Type</td>
		<td '.$thStyle.'>Organization</td>
		<td '.$thStyle.'>SO Code</td>
		<td '.$thStyle.'>Effective Date</td>
		<td '.$thStyle.'>Currency</td>
		<td '.$thStyle.'>MRC</td>
		<td '.$thStyle.'>OTC</td>
		<td '.$thStyle.'>Stage</td>
		<td '.$thStyle.'>Probability</td>
		<td '.$thStyle.'>Status</td>
		<td '.$thStyle.'>Forcast</td>
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
</table>	
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$obj_pdf->OutPut("Sales_Forcast_Report","I");
//echo $content;
}
?>
