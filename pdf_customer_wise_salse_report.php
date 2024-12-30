<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];


$fdt = $_REQUEST['filter_date_from'];
$tdt = $_REQUEST['filter_date_to'];
if ($fdt == '') {$fdt = date("d/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}

$organization = $_REQUEST["filterorg"];
if($organization == 'undefined') $organization = 0;

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

$i = 1;	
$loop = "";
                                    $qry="SELECT s.`id`, s.`socode`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`,concat(e.firstname,'',e.lastname) `hrName`
                                        , concat(e1.firstname,'',e1.lastname) `poc`,st.id stid ,st.name stnm,s.invoiceamount `amount`
                                        FROM `soitem` s left join `organization` o on s.organization=o.id left join `hr` h on o.`salesperson`=h.`id`  
                                        left join employee e on h.`emp_id`=e.`employeecode` left join `hr` h1 on s.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
                                        left join orderstatus st on s.orderstatus=st.id 
                                        WHERE  (o.id=$organization or $organization = 0)";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
					 

	$loop .='						 
	<tr>
	    <td '.$tdStyle.'>'.$i.'</td>
		<td '.$tdStyle.'>'.$rowinv["socode"].'</td>
		<td '.$tdStyle.'>'.$rowinv["organization"].'</td>
		<td '.$tdStyle.'>'.$rowinv["orderdate"].'</td>
		<td '.$tdStyle.'>'.$rowinv["hrName"].'</td>
		<td '.$tdStyle.'>'.$rowinv["poc"].'</td>
		<td '.$tdStyle.'>'.$rowinv["stnm"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv["amount"], 2, ".", ",").'</td>

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
$obj_pdf->SetTitle("Customer Wise Salse Report");
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
		<td width="50%" align="right"><h1>Customer Wise Salse Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL</td>
		<td '.$thStyle.'>SO ID</td>
		<td '.$thStyle.'>Organization</td>
		<td '.$thStyle.'>Order Date</td>
		<td '.$thStyle.'>HR Name</td>
		<td '.$thStyle.'>POC</td>
		<td '.$thStyle.'>Status</td>
		<td '.$thStyle.'>Amount</td>
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
	
$obj_pdf->OutPut("Customer_Wise_Salse_Report","I");
//echo $content;
}
?>
