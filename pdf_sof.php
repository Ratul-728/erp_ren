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
    $qry="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, a.`effectivedate` orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,cr.shnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
,round((IFNULL(b.`mrc`,0)*IFNULL(`qtymrc`,0)),2) mrc,'Order Placed' stage,'100%' prob ,f.`name` itm_cat
,c.size,g.`name` pattern,org.`name`  orgn , concat(e1.firstname,'',e1.lastname) `poc` FROM `soitem` a left join `soitemdetails` b on a.`socode`=b.`socode` left join `item` c on b.`productid`=c.`id` left join `contact` d on a.`customer`=d.`id`   left join `itmCat` f  on c.`catagory`=f.`id`
left join `pattern` g on c.`pattern`=g.`id`left join organization org on a.`organization`=org.`id`
left join `hr` e on org.`salesperson`=e.`id`  left join employee em on e.`emp_id`=em.`employeecode`
left join `hr` h1 on a.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
left join currency cr on b.currency=cr.id
where  (a.terminationDate>sysdate() or a.terminationDate is null)";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($row2 = $resultinv->fetch_assoc())
    					 {
					        $acvp=$row2['acv']/$row2['target']*100;

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.'>'.$row2["contType"].'</td>
		<td '.$tdStyle.'>'.$row2["cus_nm"].'</td>
		<td '.$tdStyle.'>'.$row2["hrName"].'</td>
		<td '.$tdStyle.'>'.$row2["itmnm"].'</td>
		<td '.$tdStyle.'>'.$row2["itm_cat"].'</td>
		<td '.$tdStyle.'>'.$row2["size"].'</td>
		<td '.$tdStyle.'>'.$row2["pattern"].'</td>
		<td '.$tdStyle.'>'.$row2["orgn"].'</td>
		<td '.$tdStyle.'>'.$row2["socode"].'</td>
		<td '.$tdStyle.'>'.$row2["orderdate"].'</td>
		<td '.$tdStyle.'>'.$row2["shnm"].'</td>
		<td '.$tdStyle.'>'.$row2["mrc"].'</td>
		<td '.$tdStyle.'>'.$row2["otc"].'</td>
		<td '.$tdStyle.'>'.$row2["stage"].'</td>
		<td '.$tdStyle.'>'.$row2["Probability"].'</td>
		<td '.$tdStyle.'>'.$row2["stat"].'</td>
		<td '.$tdStyle.'>'.$row2["poc"].'</td>
		
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
$obj_pdf->SetTitle("Sales Report");
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
		<td width="50%" align="right"><h1>Sales Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Contact Type</td>
		<td '.$thStyle.'>Customer</td>
		<td '.$thStyle.'>Account Manager</td>
		<td '.$thStyle.'>Item</td>
		<td '.$thStyle.'>Item Category</td>
		<td '.$thStyle.'>Company Type</td>
		<td '.$thStyle.'>License Type</td>
		<td '.$thStyle.'>Organization</td>
		<td '.$thStyle.'>SO Code</td>
		<td '.$thStyle.'>Effective Date</td>
		<td '.$thStyle.'>Currency</td>
		<td '.$thStyle.'>MRC</td>
		<td '.$thStyle.'>OTC</td>
		<td '.$thStyle.'>Satge</td>
		<td '.$thStyle.'>Probability</td>
		<td '.$thStyle.'>Status</td>
		<td '.$thStyle.'>POC</td>
		
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
</table>					
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$obj_pdf->OutPut("Sales_Report","I");
//echo $content;
}
?>
