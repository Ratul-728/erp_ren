<?php
session_start();

require "common/conn.php";
require "rak_framework/misfuncs.php";
require "rak_framework/fetch.php";


$usr=$_SESSION["user"];
$mod= $_GET['mod'];


$fdt = $_REQUEST['filter_date_from'];
$tdt = $_REQUEST['filter_date_to'];
$fvouch = $_REQUEST["vouchno"];
if($fvouch =='') $fvouch = 0;

if ($fdt != '') {$fdquery=" and e.trdt >=STR_TO_DATE('".$fdt."','%d/%m/%Y')";}
if ($tdt != '') {$tdquery=" and e.trdt <=STR_TO_DATE('".$tdt."','%d/%m/%Y')";}
if ($fdt == '') {$fdt = date("1/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}

//echo $fdt;

$datef = str_replace('/', '-', $fdt);
$datet = str_replace('/', '-', $fdt);

$datef =  date("d-m-Y", strtotime($datef));
$datet =  date("d-m-Y", strtotime($datet));


//settings;
$reportTitle = "GL Voucher";


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
$totdr=0; $totcr=0;
    $qry="select a.VouchNo,DATE_FORMAT( a.TransDt,'%d/%b/%Y %H %i %s') TransDt ,a.refno,a.remarks,d.sl,d.glac,g.glnm,(case d. dr_cr when 'D' then d.amount else 0 End) D_amount,(case d.dr_cr when 'C' then d.amount else 0 End) C_amount  
                                        from glmst a  left join gldlt d on a.VouchNo=d.VouchNo left join coa g on d.glac=g.glno
                                    	where  (a.VouchNo= '$fvouch' or '$fvouch' = '0' ) and
                                    	(a.TransDt  between  STR_TO_DATE('$fdt','%d/%m/%Y')  and STR_TO_DATE('$tdt','%d/%m/%Y'))";
    	  //echo  $qry;die;
		//dumpTxt($qry);
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					            $totdr += $rowinv["D_amount"]; 
					            $totcr += $rowinv["C_amount"];

	$loop .='						 
		<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["VouchNo"].'</td>
		<td '.$tdStyle.'>'.$rowinv["TransDt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["refno"].'</td>
		<td '.$tdStyle.'>'.$rowinv["remarks"].'</td>
		<td '.$tdStyle.'>'.$rowinv["sl"].'</td>
		<td '.$tdStyle.'>'.$rowinv["glac"].'</td>
		<td '.$tdStyle.'>'.$rowinv["glnm"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["D_amount"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["C_amount"], 2, ".", ",").'</td>

	</tr>		';
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
		<td '.$thStyle.'>Vouch No</td>
		<td '.$thStyle.'>Invoice Date</td>
		<td '.$thStyle.'>Reference</td>
		<td '.$thStyle.'>Remarks</td>
		<td '.$thStyle.'>SL</td>
		<td '.$thStyle.'>GL Account</td>
		<td '.$thStyle.'>GL Name</td>
		<td '.$thStyle.'>Debit</td>
		<td '.$thStyle.'>Credit</td>
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
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'>Total</th>
            <th '.$tfStyle.'>'.number_format($totdr, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totcr, 2, ".", ",").'</th>
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

