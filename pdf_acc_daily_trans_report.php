<?php
require "common/conn.php";
session_start();

//print_r($_REQUEST);
//die;

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$fdt= $_GET['dt_f'];

$tdt= $_GET['dt_t'];
$fglno= $_GET['fglno'];

$strFglno = ($fglno)?"and (a.glac = '".$fglno."' or '".$fglno."' = '0')":"";

$strDateRange = ($fdt && $tdt)? '('.date("d/M/Y", strtotime($fdt)).' to '.date("d/M/Y", strtotime($tdt)).')':"";
//echo $strDateRange; die;
      
if($fdt != '' && $fdt != 'undefined-undefined-'){
    //$date_qry = " and m.`transdt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
    $date_qry = " and m.`transdt` between '$fdt' AND '$tdt'";
}else{
    $date_qry = "";
}

$col_w_prod = 140;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}


$qry = "SELECT a.id,DATE_FORMAT( m.`transdt`,'%d/%b/%Y') `entrydate`, a.`remarks`, a.`vouchno`, concat(c.`glnm`, '(', c.`glno`, ')') glnm, a.`dr_cr`, a.`amount` 
,h.hrName makeusr,h1.hrName checkusr,h2.hrName apprvusr,m.remarks narr
FROM glmst m join `gldlt` a on m.vouchno=a.vouchno  LEFT JOIN coa c ON a.`glac` = c.glno 
left join hr h on m.entryby=h.id left join hr h1 on m.checkby=h1.id left join hr h2 on m.approvedby=h2.id  
                                        where m.isfinancial in('0','A') $strFglno  $date_qry  order by m.`transdt` asc";
/*
$qry = "SELECT a.id,DATE_FORMAT( m.`transdt`,'%d/%b/%Y') `entrydate`, a.`remarks`, a.`vouchno`, concat(c.`glnm`, '(', c.`glno`, ')') glnm, a.`dr_cr`, a.`amount`  
                                        FROM glmst m join `gldlt` a on m.vouchno=a.vouchno  LEFT JOIN coa c ON a.`glac` = c.glno 
                                        where m.isfinancial in('0','A') and (a.glac = '".$fglno."' or '".$fglno."' = '0') $date_qry ";
*/                                        
                                        
//echo $qry;die; 
$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Daily Transaction Report '.$strDateRange, 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Date', 1, 0, 'C', true);
$pdf->Cell(100, 10, 'Ref.', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Vouch No', 1, 0, 'C', true);  
$pdf->Cell(100, 10, 'GL Account', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Debit', 1, 0, 'C', true);
$pdf->Cell(70, 10, 'Credit', 1, 0, 'C', true);
$pdf->Cell(70, 10, 'Narration', 1, 0, 'C', true);
$pdf->Cell(70, 10, 'Maker', 1, 0, 'C', true);
$pdf->Cell(70, 10, 'Checker', 1, 0, 'C', true);
$pdf->Cell(70, 10, 'Approver', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        
        if($row2["dr_cr"] == 'C'){
                $dr = $row2["amount"];
                $cr = '';
                $drtot += $dr;
            }else{
                $cr = $row2["amount"];
                $dr = '';
                $crtot += $cr;
            }
            
            if($dr == 0) $dr = '';
            if($cr == 0) $cr = '';

        // Data rows with word wrapping using MultiCell() 
        $pdf->Cell(10, 10, $sl, 1); // 10:width, 10: height, $ls: text, 1: border, C: center align, , 
        $pdf->Cell(40, 10, $row2["entrydate"], 1); // Category with word wrap
        $pdf->Cell(100, 10, $row2["remarks"], 1); // Brand with word wrap
        $pdf->Cell(40, 10, $row2["vouchno"], 1,'C', true);  // Product with word wrap 
        $pdf->Cell(100, 10, $row2["glnm"], 1); 
        $pdf->Cell(30, 10, number_format($dr,2), 1); // Rate Including VAT 
        $pdf->Cell(70, 10, number_format($cr, 2), 1); // Total
         $pdf->Cell(70, 10, $row2["narr"], 1); // Total  narr
          $pdf->Cell(70, 10, $row2["makeusr"], 1); // Total
           $pdf->Cell(70, 10, $row2["checkusr"], 1); // Total
            $pdf->Cell(70, 10, $row2["apprvusr"], 1); // Total
        $pdf->Ln(); // Move to the next line
        $sl++;
    }
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

// Footer
$pdf->SetY(-20);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(330, 10, $_SESSION["comname"]." - Page ".$pdf->PageNo().'/{nb}', 0, 0, 'C'); 
 
$pdf->Output("I", "Daily_Transaction_Report.pdf");
?>