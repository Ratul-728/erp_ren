<?php
require "common/conn.php";
session_start();

require('fpdf/v186/fpdf.php');

// Extend FPDF class to include header and footer
class PDF extends FPDF
{
    // Footer method
    function Footer()
    {
        $this->SetY(-15); // Position at 1.5 cm from the bottom
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, $_SESSION["comname"] . " - Page " . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];
$fvouch = $_GET['fvouch'];
$fdt = $_GET['dt_f'];
$tdt = $_GET['dt_t'];

$strDateRange = ($fdt && $tdt) ? '(' . date("d/M/Y", strtotime($_GET['dt_f'])) . ' to ' . date("d/M/Y", strtotime($_GET['dt_t'])) . ')' : "";

$fvouchQ = "";
$date_qry = "";

if ($fdt != '' && $fdt != 'undefined-undefined-') {
    $date_qry = " and a.`TransDt` between '$fdt' AND '$tdt' ";
    $fvouchQ = "";
} else {
    $date_qry = " 1=1";
}

if (strlen($fvouch) > 1) {
    $fvouchQ = $fvouch;
} else {
    $fvouchQ = '0';
}

if ($fvouchQ == "") $fvouchQ = '0';

$col_w_prod = 140;

if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
    exit;
}

$qry = "SELECT a.VouchNo, DATE_FORMAT(a.TransDt,'%d/%b/%Y %H:%i:%s') TransDt, a.refno, a.remarks, d.sl, d.glac, g.glnm, org.name customer,
        (CASE d.dr_cr WHEN 'D' THEN d.amount ELSE 0 END) D_amount, (CASE d.dr_cr WHEN 'C' THEN d.amount ELSE 0 END) C_amount  
        FROM glmst a
        LEFT JOIN gldlt d ON a.VouchNo = d.VouchNo
        LEFT JOIN coa g ON d.glac = g.glno
        LEFT JOIN invoice inv ON (inv.invoiceno = a.refno OR inv.soid = a.refno)
        LEFT JOIN organization org ON org.id = inv.organization
        WHERE a.isfinancial IN ('0', 'A') 
        AND (a.VouchNo = '$fvouchQ' OR ('$fvouchQ' = '0' AND a.TransDt BETWEEN '$fdt' AND '$tdt'))";

$resultinv = $conn->query($qry);

// Create new PDF instance
$pdf = new PDF('L', 'mm', 'A3');
$pdf->AliasNbPages(); // Add total page count
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'GL Voucher Report '.$strDateRange, 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Vouch No', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Transaction Date', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Reference', 1, 0, 'C', true);  
$pdf->Cell(40, 10, 'Customer', 1, 0, 'C', true);
$pdf->Cell(90, 10, 'Remarks', 1, 0, 'C', true);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'GL Account', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'GL Name', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Debit', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Credit', 1, 1, 'C', true);
//$pdf->Ln(); // Move to the next line 
 
// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(30, 10, $row2["VouchNo"], 1);
        $pdf->Cell(40, 10, $row2["TransDt"], 1);
        $pdf->Cell(40, 10, $row2["refno"], 1);
        $pdf->Cell(40, 10, $row2["customer"], 1);
        $remarks = (strlen($row2["remarks"]) > 60) ? substr($row2["remarks"], 0, 57) . '...' : $row2["remarks"];
        //$pdf->Cell(60, 10, $row2["remarks"], 1); 
        // Handle multi-line remarks using MultiCell with reduced line height
        /*
        $x = $pdf->GetX(); // Current X position
        $y = $pdf->GetY(); // Current Y position
        $width = 90; // Width for the MultiCell
        $lineHeight = 10; // Adjusted line height (smaller value for tighter spacing) 
        
        $pdf->MultiCell($width, $lineHeight, $remarks, 1); // Multi-line remarks
        $pdf->SetXY($x + $width, $y); // Move to the next cell in the row 
        */
        $pdf->Cell(90, 10, $remarks, 1); 
        $pdf->Cell(10, 10, $row2["sl"], 1);
        $pdf->Cell(40, 10, $row2["glac"], 1); 
        $pdf->Cell(40, 10, $row2["glnm"], 1);
        $pdf->Cell(30, 10, number_format($row2["D_amount"], 2), 1);
        $pdf->Cell(30, 10, number_format($row2["C_amount"], 2), 1);
        $pdf->Ln(); // Move to the next line 
        $sl++;
    } 
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

// Output PDF
$pdf->Output("I", "GL_Voucher_Report.pdf");
?>
