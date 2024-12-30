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
    $qry="select t.yr,monthname(str_to_date(t.mnth,'%m')) mnth,t.mnth mnt
         ,h.`hrName` accmgr,i.`id` itid,i.name itmcatagory,ifnull(u.shnm,'BDT') crnc,t.target target
        ,round((ifnull(u.acv,0)-ifnull(u.p_acv,0)),2)acv
        from salestarget t left join (
        SELECT 
        o.salesperson acm,i.catagory icat,DATE_FORMAT(si.`effectivedate`, '%Y') syr,DATE_FORMAT(si.`effectivedate`, '%m') smn
        ,sum((ifnull(d.qty,0)*ifnull(d.otc,0))+(ifnull(d.qtymrc,0)*ifnull(d.mrc,0))) acv
        ,sum(ifnull((select ((ifnull(d1.qty,0)*ifnull(d1.otc,0))+(ifnull(d1.qtymrc,0)*ifnull(d1.mrc,0))) from soitemdetails d1 where d1.socode=si.oldsocode and d1.productid=d.productid),0))p_acv
        ,cr.shnm    
        FROM soitem si join organization o on o.id=si.organization
         join soitemdetails d on si.socode=d.socode
         join item i on i.id=d.productid left join currency cr on d.currency=cr.id
          WHERE DATE_FORMAT(si.`effectivedate`, '%Y')>='2020' 
        group by o.salesperson,i.catagory,syr,smn,cr.shnm
        
        )u on t.yr=u.syr and t.mnth=CONVERT(u.smn,UNSIGNED) and t.accmgr=u.acm and t.itmcatagory=u.icat
        join `hr` h  on t.accmgr=h.id
        join `itmCat` i on t.itmcatagory=i.`id` 
        where t.yr ='2020' ";
    	  //echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($row2 = $resultinv->fetch_assoc())
    					 {
					        $acvp=$row2['acv']/$row2['target']*100;

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$row2["yr"].'</td>
		<td '.$tdStyle.'>'.$row2["mnth"].'</td>
		<td '.$tdStyle.'>'.$row2["accmgr"].'</td>
		<td '.$tdStyle.'>'.$row2["itmcatagory"].'</td>
		<td '.$tdStyle.'>'.$row2["crnc"].'</td>
		<td '.$tdStyle.'>'.$row2["target"].'</td>
		<td '.$tdStyle.'>'.$row2["acv"].'</td>
		<td '.$tdStyle.'>'.number_format($acvp, 2, ".", ",").'</td>
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
$obj_pdf->SetTitle("Target VS Achievement Report");
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
		<td width="50%" align="right"><h1>Target VS Achievement Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Year</td>
		<td '.$thStyle.'>Month</td>
		<td '.$thStyle.'>Account Manager</td>
		<td '.$thStyle.'>Item Category</td>
		<td '.$thStyle.'>Currency</td>
		<td '.$thStyle.'>Target</td>
		<td '.$thStyle.'>Achievement</td>
		<td '.$thStyle.'>% Achievement</td>
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
</table>					
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$obj_pdf->OutPut("Target_VS_Achievement_Report","I");
//echo $content;
}
?>
