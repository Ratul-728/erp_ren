<?php
session_start();

require "common/conn.php";
require "rak_framework/misfuncs.php";
require "rak_framework/fetch.php";


$usr=$_SESSION["user"];
$mod= $_GET['mod'];


$gllvl= $_GET['gllvl'];
$glctrl= $_GET['ctrgl'];
      
$sessyrf= $_GET['pyr'];
$sessyrt=$sessyrf+1;
$ctrlcond='';
if($glctrl!='')
{
    $ctrlcond=" and c.ctlgl='$glctrl' ";
}


//echo $fdt;

$datef = str_replace('/', '-', $fdt);
$datet = str_replace('/', '-', $fdt);

$datef =  date("d-m-Y", strtotime($datef));
$datet =  date("d-m-Y", strtotime($datet));


//settings;
$reportTitle = "Expense";


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
$tfStyle = 'style="padding:7px ; padding:7px 12px; font-family:arial; font-size:10px; background-color:#efefef; border:1px solid #c0c0c0; font-weight:bold;"';

$sl = 1;
$loop = "";
$totjul = 0; $totaug= 0; $totsep = 0; $totoct = 0; $totnov = 0; $totdec = 0; $totjan = 0; $totfeb = 0; $totmar = 0; $totapr = 0; $totmay = 0; $totjun = 0; $totaltot = 0;
    $qry="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece 
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.oflag='N' and c.lvl=$gllvl  $ctrlcond
order by c.`glno`";
    	  //echo  $qry;die;
		//dumpTxt($qry);
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
    					    $tot = $rowinv['jun']; $jul = $rowinv['jul']; $aug =$rowinv['aug']-$rowinv['jul'];  $sep =$rowinv['sep']-$rowinv['aug']; $oct = $rowinv['oct']-$rowinv['sep']; $nov = $rowinv['nov']-$rowinv['oct']; 
    					    $dec = $rowinv['dece']-$rowinv['nov']; $jan = $rowinv['jan']-$rowinv['dece']; $feb = $rowinv['feb']-$rowinv['jan']; $mar = $rowinv['mar']-$rowinv['feb']; 
    					    $apr = $rowinv['apr']-$rowinv['mar']; $may = $rowinv['may']-$rowinv['apr']; $jun = $rowinv['jun']-$rowinv['may'];
    					    
					        $totjul += $jul; $totaug += $aug; $totsep += $sep; $totoct += $otc; $totnov += $nov; $totdec += $dec; 
					        $totjan += $jan; $totfeb += $feb; $totmar += $mar; $totapr += $apr; $totmay += $may; $totjun += $jun; 
					        $totaltot += $tot;


	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["glno"].'</td>
		<td '.$tdStyle.'>'.$rowinv["glnm"].'</td>
		<td '.$tdStyle.'>'.number_format($tot, 2).'</td>
		<td '.$tdStyle.'>'.number_format($jul, 2).'</td>
		<td '.$tdStyle.'>'.number_format($aug, 2).'</td>
		<td '.$tdStyle.'>'.number_format($sep, 2).'</td>
		<td '.$tdStyle.'>'.number_format($oct, 2).'</td>
		<td '.$tdStyle.'>'.number_format($nov, 2).'</td>
		<td '.$tdStyle.'>'.number_format($dec, 2).'</td>
		<td '.$tdStyle.'>'.number_format($jan, 2).'</td>
		<td '.$tdStyle.'>'.number_format($feb, 2).'</td>
		<td '.$tdStyle.'>'.number_format($mar, 2).'</td>
		<td '.$tdStyle.'>'.number_format($apr, 2).'</td>
		<td '.$tdStyle.'>'.number_format($may, 2).'</td>
		<td '.$tdStyle.'>'.number_format($jun, 2).'</td>
		
	</tr>								 
		';
		$sl++;
							 
            			 }
    				}

$thStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; background-color:#efefef; border:1px solid #c0c0c0;"';

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
		<td '.$thStyle.'>Gl No</td>
		<td '.$thStyle.'>Account Name</td>
		<td '.$thStyle.'>Total</td>
		<td '.$thStyle.'>July</td>
		<td '.$thStyle.'>Aug</td>
		<td '.$thStyle.'>Sep</td>
		<td '.$thStyle.'>Oct</td>
		<td '.$thStyle.'>Nov</td>
		<td '.$thStyle.'>Dec</td>
		<td '.$thStyle.'>Jan</td>
		<td '.$thStyle.'>Feb</td>
		<td '.$thStyle.'>Mar</td>
		<td '.$thStyle.'>Apr</td>
		<td '.$thStyle.'>May</td>
		<td '.$thStyle.'>Jun</td>
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
	<tfoot>
        <tr>
        
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'>Total</th>
            <th '.$tfStyle.'>'.number_format($totaltot, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totjul, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totaug, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totsep, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totoct, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totnov, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totdec, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totjan, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totfeb, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totmar, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totapr, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totmay, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totjun, 2, ".", ",").'</th>
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

