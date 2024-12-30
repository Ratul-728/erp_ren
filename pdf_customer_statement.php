<?php
session_start();

require "common/conn.php";
require "rak_framework/misfuncs.php";
require "rak_framework/fetch.php";


$usr=$_SESSION["user"];
$mod= $_GET['mod'];


$fdt = $_GET['dt_f'];
$tdt = $_GET['dt_t'];

if($fdt != ''){
    $date_qry1 = " and w.`transdt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
    $date_qry2 = " and o.`transdt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
}else{
    $date_qry1 = "";
    $date_qry2 = "";
}

//if ($fdt == '') {$fdt = date("1/m/Y");}
//if ($tdt == '') {$tdt = date("d/m/Y");}

$filterorg = $_GET['filterorg']; if($filterorg == '') $filterorg = 0;

//echo $fdt;

$datef = str_replace('/', '-', $fdt);
$datet = str_replace('/', '-', $fdt);

$datef =  date("d-m-Y", strtotime($datef));
$datet =  date("d-m-Y", strtotime($datet));


//settings;
$reportTitle = "Customer Statement";


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
$totdr=0; $totcr=0; $totamt=0;
    $qry="select o.id, DATE_FORMAT( o.transdt,'%d/%b/%Y %H %i %s') transdt,'' transmode, '' `trans_ref`,'Opening Bal' descr,'' debit,'' credit,o.balance from organizationwallet o 
                                where (o.orgid= ".$filterorg." or ".$filterorg." = 0) $date_qry2 and id=(select max(id) from organizationwallet o1 where o1.orgid=o.orgid and `transdt`=o.transdt)
                                union all select w.id,w.`transdt`,m.name transmode,w.`trans_ref`,w.`remarks`,(case when w.dr_cr='C' then `amount` else 0 end ) cr_amt
                                ,(case when w.dr_cr='D' then `amount` else 0 end ) dr_amt,w.`balance`
                                from `organizationwallet` w left join organization o on w.orgid=o.id left join transmode m on w.`transmode`=m.id
                                where (w.orgid= ".$filterorg." or ".$filterorg." = 0) and w.dr_cr in('C','D') $date_qry1
          ";
    	  //echo  $qry;die;
		//dumpTxt($qry);
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					            $totdr += $rowinv["debit"]; 
					            $totcr += $rowinv["credit"];
					            $totamt += $rowinv["balance"];

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["transdt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["transmode"].'</td>
		<td '.$tdStyle.'>'.$rowinv["trans_ref"].'</td>
		<td '.$tdStyle.'>'.$rowinv["descr"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["debit"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["credit"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["balance"], 2, ".", ",").'</td>

	</tr>								 
		';
		$sl++;
							 
            			 }
    				}

$thStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; background-color:#efefef; border:1px solid #c0c0c0;"';
$tfStyle = 'style="padding:7px ; padding:7px 12px; font-family:arial; font-size:10px; background-color:#efefef; border:1px solid #c0c0c0; font-weight:bold;"';

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
		<td '.$thStyle.'>Transfer Date</td>
		<td '.$thStyle.'>Transfer Mode</td>
		<td '.$thStyle.'>Reference</td>
		<td '.$thStyle.'>Description</td>
		<td '.$thStyle.'>Debit</td>
		<td '.$thStyle.'>Credit</td>
		<td '.$thStyle.'>Balance</td>
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
	<tfoot>
        <tr>
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'>Total</th>
            <th '.$tfStyle.'>'.number_format($totdr, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totcr, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totamt, 2, ".", ",").'</th>
        </tr>
    </tfoot>
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

