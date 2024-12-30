<?php
require "common/conn.php";
session_start();

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$fdt= $_GET['dt_f'];
$tdt= $_GET['dt_t'];

$strDateRange = ($fdt && $tdt) ? '(' . date("d/M/Y", strtotime($fdt)) . ' to ' . date("d/M/Y", strtotime($tdt)) . ')' : "";

if ($fdt != '' && $fdt != 'undefined-undefined-') {
    $dateTime = DateTime::createFromFormat('Y-m-d', $fdt);
    $fdt = $dateTime->format('d/m/Y');
    $dateTime = DateTime::createFromFormat('Y-m-d', $tdt);
    $tdt = $dateTime->format('d/m/Y');
} else {
    $fdt = date("Y-m-d");
}

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$qry = "SELECT un.glac, c.glnm, c.dr_cr, 
        COALESCE(SUM(un.D_amount), 0) dr, 
        COALESCE(SUM(un.C_amount), 0) cr, 
        COALESCE(SUM(un.op), 0) op 
        FROM (SELECT d.glac,
                     COALESCE((CASE d.dr_cr WHEN 'D' THEN d.amount ELSE 0 END), 0) D_amount,
                     COALESCE((CASE d.dr_cr WHEN 'C' THEN d.amount ELSE 0 END), 0) C_amount,
                     0 op
              FROM glmst a, gldlt d
              WHERE a.VouchNo = d.VouchNo 
              AND a.isfinancial IN ('0', 'A')
              AND (a.transdt BETWEEN DATE_FORMAT(STR_TO_DATE('$fdt', '%d/%m/%Y'), '01/%m/%y') 
                   AND STR_TO_DATE('$tdt', '%d/%m/%Y'))
              UNION ALL
              SELECT glno, 0 D_amount, 0 C_amount, COALESCE(opbal, 0) op
              FROM coa_mon 
              WHERE isposted = 'P' AND opbal <> 0 
              AND mn = MONTH(STR_TO_DATE('$fdt', '%d/%m/%Y'))
              AND yr = YEAR(STR_TO_DATE('$fdt', '%d/%m/%Y'))
        ) un, coa c 
        WHERE un.glac = c.glno AND c.oflag = 'N' 
        GROUP BY un.glac, c.glnm, c.dr_cr";

$resultinv = $conn->query($qry);

class PDF extends FPDF {
    // Footer implementation
    function Footer() {
        $this->SetY(-20); // Position footer at 20mm from the bottom
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, $_SESSION["comname"] . " - Page " . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Create new PDF instance
$pdf = new PDF('P', 'mm', 'A3');
$pdf->AliasNbPages(); // Handle {nb} alias
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Trial Balance Report ' . $strDateRange, 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'GL Account', 1, 0, 'C', true);
$pdf->Cell(80, 10, 'GL Name', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'OP Balance', 1, 0, 'C', true);  
$pdf->Cell(50, 10, 'Debit Amount', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Credit Amount', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Closing Balance', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
$optot = $drtot = $crtot = $cltot = 0;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        $op = $row2['op'];
        $drcr = $row2['dr_cr'];
        if ($drcr == 'C') $op *= -1;

        $optot += $op;
        $drtot += $row2['dr'];
        $crtot += $row2['cr'];
        $cltot += $op + $row2['dr'] - $row2['cr'];

        $pdf->Cell(10, 10, $sl, 1);
        $pdf->Cell(40, 10, $row2["glac"], 1);
        $pdf->Cell(80, 10, $row2["glnm"], 1);
        $pdf->Cell(40, 10, number_format($op, 2), 1);
        $pdf->Cell(50, 10, number_format($row2["dr"], 2), 1);
        $pdf->Cell(30, 10, number_format($row2["cr"], 2), 1);
        $pdf->Cell(30, 10, number_format($op + $row2['dr'] - $row2['cr'], 2), 1);
        $pdf->Ln();
        $sl++;
    }

    $pdf->Cell(10, 10, '', 1);
    $pdf->Cell(40, 10, '', 1);
    $pdf->Cell(80, 10, 'Total', 1);
    $pdf->Cell(40, 10, number_format($optot, 2), 1);
    $pdf->Cell(50, 10, number_format($drtot, 2), 1);
    $pdf->Cell(30, 10, number_format($crtot, 2), 1);
    $pdf->Cell(30, 10, number_format($optot + $drtot - $crtot, 2), 1);
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

$pdf->Output("I", "Trial_Balance_Report.pdf");
