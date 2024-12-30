<?php
require "common/conn.php";
session_start();

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$col_w_prod = 140;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$qry="select p.id,p.containerno, p.poid,p.voucher_no,p.pi_no,p.pi_date,p.lc_tt_no,p.lc_tt_date,p.payment_amount ,
                                pi.com_invoice_val_bdt com_invoice_val_bdt, pi.freight_charges freight_charges, pi.global_taxes global_taxes, pi.cd cd, pi.rd rd, 
                                pi.sd sd, pi.vat vat,pi.AT, pi.AIT AIT,  pi.tot_landed_cost tot_landed_cost,pi. tot_value tot_value
                                from purchase_landing p,purchase_landing_item pi 
                                where p.id=pi.pu_id order by p.id desc";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Purchase Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Container No', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Purchase Order', 1, 0, 'C', true);  
$pdf->Cell(30, 10, 'Voucher No', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'PI No', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'PI Date', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'LC/TT No', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'LC/TT Date', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Payment Amount', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'BDT Value', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Freight', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Global Taxes', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'CD', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'RD', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'SD', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'VAT', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Total Landed Cost', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Total Value', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row = $resultinv->fetch_assoc()) {
        
        $pdf->Cell(10, 10, $sl, 1); 
        $pdf->Cell(20, 10, $row["containerno"], 1);
        $pdf->Cell(30, 10, $row["poid"], 1);
        $pdf->Cell(30, 10, $row["voucher_no"], 1);
        $pdf->Cell(30, 10, $row["pi_no"], 1);
        $pdf->Cell(20, 10, $row["pi_date"], 1);
        $pdf->Cell(30, 10, $row["lc_tt_no"], 1);
        $pdf->Cell(20, 10, $row["lc_tt_date"], 1);
        $pdf->Cell(30, 10, $row["payment_amount"], 1);
        $pdf->Cell(20, 10, $row["com_invoice_val_bdt"], 1);
        $pdf->Cell(20, 10, $row["freight_charges"], 1);
        $pdf->Cell(20, 10, $row["global_taxes"], 1);
        $pdf->Cell(20, 10, $row["cd"], 1);
        $pdf->Cell(20, 10, $row["rd"], 1);
        $pdf->Cell(20, 10, $row["sd"], 1);
        $pdf->Cell(20, 10, $row["vat"], 1);
        $pdf->Cell(30, 10, $row["tot_landed_cost"], 1);
        $pdf->Cell(20, 10, $row["tot_value"], 1);
        $pdf->Ln(); // Move to the next line
        $sl++;
    }
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

// Footer
$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, $_SESSION["comname"]." - Page ".$pdf->PageNo().'/{nb}', 0, 0, 'C');

$pdf->Output("I", "Purchase_Report.pdf");
?>
