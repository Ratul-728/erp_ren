<?php
require "common/conn.php";
session_start();

$usr=$_SESSION["user"];
$mod= $_GET['mod'];


$fdt = $_REQUEST['filter_date_from'];
$tdt = $_REQUEST['filter_date_to'];
if ($fdt == '') {$fdt = date("1/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}

$pyr=$_REQUEST['fyr'];
$pmn=$_REQUEST['fmn'];

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
        
        //Sub Query
        $assetqry = "select @asset=COALESCE(closingBal,0) asset from coa_mon where glno='100000000' and mn='$pmn' and yr='$pyr'";
        $assetresult = mysqli_query($con, $assetqry);
        while ($assetrow = mysqli_fetch_assoc($assetresult)){
            $asset = $assetrow["asset"];
        }
        
        $liabilityqry = "select @liability =COALESCE(closingBal,0) liability from coa_mon where glno='200000000' and mn='$pmn' and yr='$pyr'";
        $liabilityresult = mysqli_query($con, $liabilityqry);
        while ($liabilityrow = mysqli_fetch_assoc($liabilityresult)){
            $liability = $liabilityrow["liability"];
        }
        
        
$sl = 1;
$loop = "";
    $qry="select
        (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
        (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
        a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
        from coa_mon a 
        where substring(a.glno,1,1) in('1','2') 
        and mn='$pmn' and yr='$pyr' and a.status='A'
        order by a.glno";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["asslib"].'</td>
		<td '.$tdStyle.'>'.$rowinv["lvl"].'</td>
		<td '.$tdStyle.'>'.$rowinv["glno"].'</td>
		<td '.$tdStyle.'>'.$rowinv["glnm"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["closingBal"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.$rowinv["p"].'</td>

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
$obj_pdf->SetTitle("Balance Sheet Report");
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
		<td width="50%" align="right"><h1>Balance Sheet Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Asset/Liability</td>
		<td '.$thStyle.'>Level</td>
		<td '.$thStyle.'>GL Account</td>
		<td '.$thStyle.'>GL Name</td>
		<td '.$thStyle.'>Closing Balance</td>
		<td '.$thStyle.'>P</td>
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
	
$obj_pdf->OutPut("Balance_Sheet_Report","I");
//echo $content;
}
?>
