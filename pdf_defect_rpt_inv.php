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

$qry="SELECT s.id,t.name tn,p.name pn,s.freeqty defectQty,p.rate price_incl_vat,r.name str,s.barcode barcode,p.image
                     FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id  
                     where s.storerome=10 and  s.freeqty<>0 order by p.name asc";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Future Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Category', 1, 0, 'C', true);
$pdf->Cell($col_w_prod, 10, 'Product', 1, 0, 'C', true);  
$pdf->Cell(30, 10, 'Barcode', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Repairable', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Price Including VAT', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Warehouse', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row = $resultinv->fetch_assoc()) {

        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(40, 10, $row['tn'], 1); // Category
        $pdf->Cell($col_w_prod, 10, $row['pn'], 1); // Product
        $pdf->Cell(30, 10, $row['barcode'], 1); // Barcode
        $pdf->Cell(30, 10, $row['defectQty'], 1); // Store Type
        $pdf->Cell(50, 10, $row['price_incl_vat'], 1); // Store
        $pdf->Cell(30, 10, $row['str'], 1); 
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

$pdf->Output("I", "Future_Stock_Report.pdf");
?>
