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

$qry="select i.code,i.name product,i.barcode,c.name catagory ,s.freeqty from stock s,item i,itmCat c
                where i.id=s.product and i.catagory=c.id ORDER BY i.name";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Inventory Status Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Product Code', 1, 0, 'C', true);
$pdf->Cell($col_w_prod, 10, 'Product', 1, 0, 'C', true);  
$pdf->Cell(40, 10, 'Barcode', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Category', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Free QTY', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        
        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(40, 10, $row2["code"], 1); // Category
        $pdf->Cell($col_w_prod, 10, $row2["product"], 1); // Product
        $pdf->Cell(40, 10, $row2["barcode"], 1); // Barcode
        $pdf->Cell(60, 10, $row2["catagory"], 1); // Store Type
        $pdf->Cell(40, 10, $row2["freeqty"], 1);
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

$pdf->Output("I", "Inventory_Status_Report.pdf");
?>
