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
    
        $bal=0;
        //echo $fd;die;
        $qry0="select sum(paidamount) dra from invoice where invoicedt < STR_TO_DATE('".$fdt."','%d/%m/%Y')";
        $qry1="select sum(amount) cra from expense where trdt < STR_TO_DATE('".$fdt."','%d/%m/%Y')";
        //echo $qry0;die;
        $result0 = $conn->query($qry0);
        $row0 = $result0->fetch_assoc();
        $d=$row0["dra"];
        //echo $d;die;
        $result1 = $conn->query($qry1);
        $row1 = $result1->fetch_assoc();
        $c=$row1["cra"];
        $bal=$d-$c;
	
$tdStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; border:1px solid #efefef; white-space:nowrap;"';

$sl = 1;
$loop = "";
    $qry="select date_format(trdt,'%d/%m/%Y') trdt,narr,incm dr,expns cr
          FROM
          (
             SELECT `invoicedt` trdt,`paidamount` incm,0 expns,concat(soid,'-',invoiceno) narr 
             FROM invoice where invoicedt between STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y')
             union all 
             select trdt  trdt,0 incm,amount expns,naration narr from expense where trdt between STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y')
          ) u";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
    					     $bal=$bal+$rowinv["dr"]-$rowinv["cr"];
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["trdt"].'</td>
		<td '.$tdStyle.'>'.$rowinv["narr"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["dr"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["cr"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($bal, 2, ".", ",").'</td>

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
$obj_pdf->SetTitle("Cash Flow Report");
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
		<td width="50%" align="right"><h1>Cash Flow Report</h1></td>
	</tr>
	</thead>		
</table>		
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
</table>					
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$fdt = date("d/m/Y");
$tdt = date("d/m/Y");
	
$obj_pdf->OutPut("Cash_Flow_Report_".$datef."_to_".$datet,"I");
//echo $content;
}
?>
