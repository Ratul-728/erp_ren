<?php
session_start();

require "common/conn.php";
require "rak_framework/misfuncs.php";
require "rak_framework/fetch.php";


$usr=$_SESSION["user"];
$mod= $_GET['mod'];

$gllvl= $_GET['gllvl'];
$glctrl= $_GET['ctrgl'];
      
$ctrlcond='';
if($glctrl!='')
{
    $ctrlcond=" and c.ctlgl='$glctrl' ";
}


$datef = str_replace('/', '-', $fdt);
$datet = str_replace('/', '-', $fdt);

$datef =  date("d-m-Y", strtotime($datef));
$datet =  date("d-m-Y", strtotime($datet));


//settings;
$reportTitle = "Expense Details Report";


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
    $qry="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
            nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='07'),0)jul
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='08'),0)aug
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='09'),0)sep
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='10'),0)oct
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='11'),0)nov
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='12'),0)dece
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='01'),0)jan
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='02'),0)feb
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='03'),0)mar
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='04'),0)apr
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='05'),0)may
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='06'),0)jun
            FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=1
            order by c.`glno`
        ";
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
    					     $pctrl=$rowinv['ctlgl'];
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["glno"].'</td>
		<td '.$tdStyle.'>'.$rowinv["glnm"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['aug'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['sep'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['oct'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['nov'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['dece'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['jan'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['feb'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['mar'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['apr'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['may'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['jun'],2).'</td>
		
	</tr>								 
		';
		$sl++;
		
		$qry2="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
                nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='07'),0)jul
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='08'),0)aug
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='09'),0)sep
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='10'),0)oct
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='11'),0)nov
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='12'),0)dece
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='01'),0)jan
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='02'),0)feb
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='03'),0)mar
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='04'),0)apr
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='05'),0)may
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='06'),0)jun
                FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=2 and c.ctlgl=$pctrl
                order by c.`glno`
        ";
    			$resultinv2= $conn->query($qry2);
    				if ($resultinv2->num_rows > 0){
    					 while($rowinv2 = $resultinv2->fetch_assoc())
    					 {
    					     $pctrl1=$rowinv2['ctlgl'];
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv2["glno"].'</td>
		<td '.$tdStyle.'> &nbsp; &nbsp; &nbsp;'.$rowinv2["glnm"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['aug'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['sep'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['oct'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['nov'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['dece'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['jan'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['feb'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['mar'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['apr'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['may'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv2['jun'],2).'</td>
		
	</tr>								 
		';
		$sl++;
		
		$qry3="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
                nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='07'),0)jul
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='08'),0)aug
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='09'),0)sep
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='10'),0)oct
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='11'),0)nov
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='12'),0)dece
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='01'),0)jan
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='02'),0)feb
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='03'),0)mar
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='04'),0)apr
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='05'),0)may
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='06'),0)jun
                FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=3 and c.ctlgl=$pctrl1
                order by c.`glno` 
        ";
    			$resultinv3= $conn->query($qry3);
    				if ($resultinv3->num_rows > 0){
    					 while($rowinv3 = $resultinv3->fetch_assoc())
    					 {
					        $pctrl2=$rowinv3['ctlgl'];

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv3["glno"].'</td>
		<td '.$tdStyle.'> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'.$rowinv3["glnm"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['aug'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['sep'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['oct'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['nov'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['dece'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['jan'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['feb'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['mar'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['apr'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['may'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv3['jun'],2).'</td>
		
	</tr>								 
		';
		$sl++;
		
		$qry4="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
            nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='07'),0)jul
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='08'),0)aug
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='09'),0)sep
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='10'),0)oct
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='11'),0)nov
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='12'),0)dece
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='01'),0)jan
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='02'),0)feb
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='03'),0)mar
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='04'),0)apr
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='05'),0)may
            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='06'),0)jun
            FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=4 and c.ctlgl=$pctrl2
            order by c.`glno`
        ";
    			$resultinv4= $conn->query($qry4);
    				if ($resultinv4->num_rows > 0){
    					 while($rowinv4 = $resultinv4->fetch_assoc())
    					 {
					        $pctrl3=$rowinv4['ctlgl'];

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv4["glno"].'</td>
		<td '.$tdStyle.'> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'.$rowinv4["glnm"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['aug'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['sep'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['oct'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['nov'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['dece'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['jan'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['feb'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['mar'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['apr'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['may'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv4['jun'],2).'</td>
		
	</tr>								 
		';
		$sl++;
		
		$qry5="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
                nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='07'),0)jul
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='08'),0)aug
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='09'),0)sep
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='10'),0)oct
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='11'),0)nov
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='12'),0)dece
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='01'),0)jan
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='02'),0)feb
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='03'),0)mar
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='04'),0)apr
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='05'),0)may
                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='06'),0)jun
                FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=5 and c.ctlgl=$pctrl3
                order by c.`glno`
        ";
    			$resultinv5= $conn->query($qry5);
    				if ($resultinv5->num_rows > 0){
    					 while($rowinv5 = $resultinv5->fetch_assoc())
    					 {
					        $pctrl4=$rowinv5['ctlgl'];

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv5["glno"].'</td>
		<td '.$tdStyle.'> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'.$rowinv5["glnm"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['aug'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['sep'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['oct'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['nov'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['dece'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['jan'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['feb'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['mar'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['apr'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['may'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv5['jun'],2).'</td>
		
	</tr>								 
		';
		$sl++;
							 
            			 }
    				}
							 
            			 }
    				}
							 
            			 }
    				}
							 
            			 }
    				}

							 
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
								<td '.$thStyle.'>Gl NO</td>
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

