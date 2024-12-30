<?php
require "common/conn.php";
session_start();

//print_r($_REQUEST);die; 

$usr=$_SESSION["user"]; 
$mod= $_GET['mod'];

$branch = $_GET["branch"]; if($branch == '') $branch = 0;
$brand = $_GET["brand"]; if($brand == '') $brand = 0;
$cat = $_GET["cat"]; if($cat == '') $cat = 0;
$bc1 = $_GET["barcode"];

$fdt = $_REQUEST['filter_date_from'];
$tdt = $_REQUEST['filter_date_to'];

//echo $fdt;

$datef = str_replace('/', '-', $fdt);
$datet = str_replace('/', '-', $tdt);

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
                          $qry="SELECT s.id,t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode barcode, s.storerome, p.image, b.title brand
                                FROM chalanstock s 
                                LEFT JOIN item p ON s.product = p.id 
                                LEFT JOIN itmCat t ON p.catagory=t.id 
                                LEFT JOIN branch r ON s.storerome=r.id  
                                LEFT JOIN brand b ON b.id=p.brand
                                where (s.barcode='".$bc1."' or p.barcode='".$bc1."' or '".$bc1."'='' or p.name like '%".$bc1."%' ) and ( r.id = ".$branch." or ".$branch." = 0 )
                                and ( t.id = ".$cat." or ".$cat." = 0 ) and ( b.id = ".$brand." or ".$brand." = 0 ) and s.freeqty<>0
                                order by s.id DESC";
    	 // echo  $qry;die;
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($row2 = $resultinv->fetch_assoc())
    					 {
    					    $tnm=$row2["tn"]; $prod=$row2["pn"];$str=$row2["str"];  $br = $row2["brand"];
                            $freeqty=$row2["freeqty"]; $cup=$row2["costprice"]; $mup=$row2["mrp"]; $bc=$row2["barcode"];
                            $cp=$freeqty*$cup;$mp=$freeqty*$mup; 
                            $tcp=$tcp+$cp;$tmp=$tmp+$mp;
                            
                            if($row2["storerome"] == 7){
                                $storetype = "Future Stock";
                            }
                            else if($row2["storerome"] == 8){
                                $storetype = "Back Stock";
                            }else{
                                $storetype = "In Stock";
                            }
					 

	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$tnm.'</td>
		<td '.$tdStyle.'>'.$br.'</td>
		<td '.$tdStyle.'>'.$prod.'</td>
		<td '.$tdStyle.'>'.$bc.'</td>
		<td '.$tdStyle.'>'.$storetype.'</td>
		<td '.$tdStyle.'>'.$str.'</td>
		<td '.$tdStyle.'>'.number_format($freeqty, 0, ".", ",").'</td>
		
		<td '.$tdStyle.'>'.number_format($mup, 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($mp, 2, ".", ",").'</td>
	</tr>							 
		';
		
		/*
		<td '.$tdStyle.'>'.number_format($cup, 2, ".", ",").'</td>
		<td '.$tdStyle.'>'.number_format($cp, 2, ".", ",").'</td>
		*/
		$sl++;
							 
            			 }
    				}

$thStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; background-color:#efefef; border:1px solid #c0c0c0;"';







require_once("tcpdf_min/tcpdf.php");
	
	
// Extend the TCPDF class to create custom Header and Footer
ob_start();

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
$obj_pdf->SetTitle("Store Wise Stock Report");
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
		<td width="50%" align="right"><h1>Store Wise Stock Report</h1></td>
	</tr>
	</thead>		
</table>		
<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Category</td>
		<td '.$thStyle.'>Brand</td>
		<td '.$thStyle.'>Product</td>
		<td '.$thStyle.'>Barcode</td>
		<td '.$thStyle.'>Store Type</td>
		<td '.$thStyle.'>Store</td>
		<td '.$thStyle.'>QTY</td>
		
		<td '.$thStyle.'>Rate Including VAT</td>
		<td '.$thStyle.'> Total</td>
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
$obj_pdf->OutPut("Store_Wise_Stock_Report","I");






ob_end_clean();
//echo $content;
}
?>
