<?php
session_start();

require "common/conn.php";
require "rak_framework/misfuncs.php";
require "rak_framework/fetch.php";


$usr=$_SESSION["user"];
$mod= $_GET['mod'];

$fdt = $_GET['dt_f'];
$tdt = $_GET['dt_t'];
if($fdt == ''){
    $fdt = date("Y-m-01");
    $tdt = date("Y-m-d");
}

$date_qry = " and invoicedt between DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d') ";
$date_qry2 = "where invoicedt < STR_TO_DATE('".$fdt."','%Y-%m-%d')";
$date_qry3 = "and trdt between DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d')";
$date_qry4 = "where trdt < STR_TO_DATE('".$fdt."','%Y-%m-%d')";


//echo $fdt;

$datef = str_replace('/', '-', $fdt);
$datet = str_replace('/', '-', $fdt);

$datef =  date("d-m-Y", strtotime($datef));
$datet =  date("d-m-Y", strtotime($datet));


//settings;
$reportTitle = "Cash Flow";


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

        $bal=0;$bf=0;$totdr=0;$totcr=0;$net=0;
        //echo $fd;die;
        $qry0="select sum(paidamount) dra from invoice $date_qry2";
        $qry1="select sum(amount) cra from expense $date_qry4";
        //echo $qry1;die;
        $result0 = $conn->query($qry0);
        $row0 = $result0->fetch_assoc();
        $d=$row0["dra"];
        //echo $d;die;
        $result1 = $conn->query($qry1);
        $row1 = $result1->fetch_assoc();
        $c=$row1["cra"];
        $bal=$d-$c;

$sl = 1;
$loop = "";
$totdr=0; $totcr=0; $totbal=0;
    $qry="select date_format(trdt,'%d/%b/%Y') trdt,narr,incm dr,expns cr
                                FROM
                                (
                                    SELECT `invoicedt` trdt,`paidamount` incm,0 expns,concat(soid,'-',invoiceno) narr 
                                    FROM invoice where 1=1 $date_qry
                                    union all 
                                    select trdt  trdt,0 incm,amount expns,naration narr from expense where 1=1 $date_qry3
                                ) u";
    	  //echo  $qry;die;
		//dumpTxt($qry);
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					        $trdt=$rowinv["trdt"];$narr=$rowinv["narr"]; $dr=$rowinv["dr"]; $cr=$rowinv["cr"];  
                            $bal=$bal+$dr-$cr;$i++;$totdr=$totdr+$dr;$totcr=$totcr+$cr;
                            $totbal += $bal;
					        
	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["trdt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["narr"].'</td>
		<td '.$tdStyle.'>'.number_format($dr, 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($cr, 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($bal, 2, ".", ",").'</td>

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
		<td '.$thStyle.'>Date</td>
		<td '.$thStyle.'>Narration</td>
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
            <th '.$tfStyle.'>Total</th>
            <th '.$tfStyle.'>'.number_format($totdr, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totcr, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totbal, 2, ".", ",").'</th>
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

