<?php
require "common/conn.php";
session_start();

$usr=$_SESSION["user"];
$mod= $_GET['mod'];

$mnid = $_GET['month']; if($mnid == '')$mnid = intval(date('n'));
$yearid = $_GET["year"]; if($yearid == '')$yearid = date('Y');
$cmbdept = $_GET["dept"];

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
$looptd = "";
$thStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; background-color:#efefef; border:1px solid #c0c0c0;"';

$startDate = new DateTime("$yearid-$mnid-01");
$endDate = new DateTime("$yearid-$mnid-" . date('t', strtotime("$yearid-$mnid-01")));
                                
$dateInterval = new DateInterval('P1D'); // Create a DateInterval of 1 day
$dateRange = new DatePeriod($startDate, $dateInterval, $endDate->modify('+1 day')); // Generate a range of dates
                            
$formattedDates = [];
foreach ($dateRange as $date) {
    $formattedDates[] = $date->format('j/M/Y'); // Format and store each date in day/month/year format
    
    $looptd .= '<td '.$thStyle.'>'.$date->format('j/M/Y').'</td>';
}

$qryGetDep = "SELECT * FROM `department`";
$resultGetDep = $conn->query($qryGetDep);
while($rowGetDept = $resultGetDep->fetch_assoc()) {
    $qryEmp = "SELECT concat(e.firstname, ' ', e.lastname) empname, e.id empid
                    FROM `hraction` a LEFT JOIN employee e ON a.`hrid` = e.id
                    Where a.`postingdepartment` = ".$rowGetDept["id"];
    $resultEmp = $conn->query($qryEmp); 
    while($rowEmp = $resultEmp->fetch_assoc()) { 
        $empid=$rowEmp["empid"];$empnm=$rowEmp["empname"];
        $loop .='						 
            	<tr>
            		<td '.$tdStyle.'>'.$empnm.'</td>';
            		
        foreach($formattedDates as $todate){
            
            $assignshift = 'No shift assign';
                                
            $qryCh = "SELECT s.title `shift` FROM `assignshifthist` ash left join Shifting s on ash.shift = s.id
                    WHERE ash.`empid` = '$empid' and ash.`effectivedt` = STR_TO_DATE('$todate', '%e/%b/%Y')";
            $resultCh = $conn->query($qryCh); 
            while($rowCh = $resultCh->fetch_assoc()) {
                $assignshift = $rowCh["shift"];
            }
            
            $loop .= '<td '.$tdStyle.'>'.$assignshift.'</td>';
        }
        $loop .= '</tr>';
    }
}

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
$obj_pdf->SetTitle("Assign Shift Report");
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
		<td width="50%" align="right"><h1>Assign Shift Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		'.$looptd.'
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
</table>					
						
';

/*<td '.$thStyle.'>Cost Rate</td>
		<td '.$thStyle.'>Cost Price</td>*/
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$obj_pdf->OutPut("Assign_Shift_Report","I");
//echo $content;
}
?>
