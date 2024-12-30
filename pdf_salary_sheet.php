<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];


$dept = $_GET["dept"]; if($dept == '') $dept = 0;
$desi = $_GET["desi"]; if($desi == '') $desi = 0;
$yr = $_GET["fd"];
$mn = $_GET["td"];

if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
	
$tdStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; border:1px solid #efefef; white-space:nowrap;"';

$i = 1;	
$loop = "";
    $qry="SELECT e.id,s.salaryyear,MONTHNAME(STR_TO_DATE(s.salarymonth, '%m')) mnth,s.hrid,concat(e.firstname,' ',e.lastname) emp, e.employeecode empcode
                            ,s.benft_1 ,s.benft_2 ,s.benft_3 ,s.benft_4 ,s.benft_5 
                            ,s.benft_6 ,s.benft_7 ,s.benft_8 ,s.benft_9 ,s.benft_10,s.benft_11,s.privilage,s.total,s.notes
                            , dept.name deptname, desi.name desiname
                            FROM monthlysalary s left JOIN hr h  ON s.hrid=h.id left join employee e on h.emp_id=e.employeecode 
                            LEFT JOIN department dept ON dept.id = e.department 
                            LEFT JOIN designation desi ON desi.id = e.designation
                            where s.salaryyear='$yr' and s.salarymonth='$mn' and ($dept = e.department or $dept = 0) and ($desi = e.designation or $desi = 0)";
    // 	  echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
    					     $tot=$rowinv['basic']+$rowinv['house']+$rowinv['medical']+$rowinv['transport']+$rowinv['mobile'];
					 

	$loop .='						 
	<tr>
	    <td '.$tdStyle.'>'.$i.'</td>
		<td '.$tdStyle.'>'.$rowinv["salaryyear"].'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["mnth"].'</td>
		<td '.$tdStyle.'>'.$rowinv["empcode"].'</td>
		<td '.$tdStyle.'>'.$rowinv["emp"].'</td>
		<td '.$tdStyle.'>'.$rowinv["deptname"].'</td>
		<td '.$tdStyle.'>'.$rowinv["desiname"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["benft_1"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["benft_2"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["benft_3"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["benft_4"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["benft_5"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["benft_11"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["total"], 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.$rowinv["notes"].'</td>


	</tr>							 
		';					 
							 
            			$i++; }
    				}

$thStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; background-color:#efefef; border:1px solid #c0c0c0;"';







require_once("tcpdf_min/tcpdf.php");
	
	
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {


    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
		$text = "RENAISSANCE DECOR LTD - Page:  ".$this->getAliasNumPage().'/'.$this->getAliasNbPages();
		 $this->Cell(0, 10, $text, 0, false, 'C', 0, '', 0, false, 'T', 'M');		
    }
}
	
	
//$obj_pdf= new MYPDF('P',PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
$obj_pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);	
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetTitle("Salary Sheet Report");
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
		<td width="50%" align="right"><h1>Salary Sheet Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL</td>
		<td '.$thStyle.'>Year</td>
		<td '.$thStyle.'>Month</td>
		<td '.$thStyle.'>Employee Code</td>
		<td '.$thStyle.'>Employee Name</td>
		<td '.$thStyle.'>Department</td>
		<td '.$thStyle.'>Designation</td>
		<td '.$thStyle.'>Basic</td>
		<td '.$thStyle.'>House Rent</td>
		<td '.$thStyle.'>Medical</td>
		<td '.$thStyle.'>Transport</td>
		<td '.$thStyle.'>Late Deduction</td>
		<td '.$thStyle.'>AIT</td>
		<td '.$thStyle.'>Gross</td>
		<td '.$thStyle.'>Note</td>
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
	
$obj_pdf->OutPut("Salary_sheet_Report","I");
//echo $content;
}
?>
