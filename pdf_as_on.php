<?php
require "common/conn.php";
session_start();

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$fdt= $_GET['dt_f'];

$tdt= $_GET['dt_t'];
      
if($fdt != '' && $fdt != 'undefined-undefined-'){
    $date_qry = " and s.stockdate between DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d') ";
}else{
    $date_qry = "";
}

$col_w_prod = 140;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$qry="select i.barcode,i.name product,i.description,s.`Available Quantity` qty,s.` Cost per unit` rate,s.` Total cost` cost,s.Location loc,DATE_FORMAT(s.stockdate, '%d/%b/%Y') stockdate 
                                from stock_300624_old_2 s,item i 
                                where s.itemid=i.id  $date_qry order by i.barcode, s.Location";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'As On Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(80, 10, 'Product', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Barcode', 1, 0, 'C', true);  
$pdf->Cell($col_w_prod, 10, 'Description', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'QTY', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Rate', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Cost', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Location', 1, 0, 'C', true);
// $pdf->Cell(30, 10, 'Stock Date', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1; $totqty = 0; $totcost = 0; $totrate = 0;
if ($resultinv->num_rows > 0) {
    while ($row = $resultinv->fetch_assoc()) {

        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(80, 10, $row['product'], 1); // Category
        $pdf->Cell(30, 10, $row['barcode'], 1); // Product
        $pdf->Cell($col_w_prod, 10, $row['description'], 1); // Barcode
        $pdf->Cell(30, 10, $row['qty'], 1); // Store Type
        $pdf->Cell(30, 10, number_format($row['rate'], 2), 1); // Store
        $pdf->Cell(30, 10, number_format($row['cost'], 2), 1); 
        $pdf->Cell(40, 10, $row['loc'], 1);
        // $pdf->Cell(30, 10, $row['stockdate'], 1); 
        $pdf->Ln(); // Move to the next line
        $sl++;
        
        $totqty += $row['qty']; $totcost += $row['cost']; $totrate += $row['rate'];
    }
    
    $pdf->Cell(10, 10, "", 1); // Serial number
        $pdf->Cell(80, 10, "", 1); // Category
        $pdf->Cell(30, 10, "", 1); // Product
        $pdf->Cell($col_w_prod, 10, "Total", 1); // Barcode
        $pdf->Cell(30, 10, $totqty, 1); // Store Type
        $pdf->Cell(30, 10, number_format($totrate, 2), 1); // Store
        $pdf->Cell(30, 10, number_format($totcost, 2), 1); 
        $pdf->Cell(40, 10, "", 1);
        // $pdf->Cell(30, 10, "", 1); 
        $pdf->Ln(); // Move to the next line
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

// Footer
$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, $_SESSION["comname"]." - Page ".$pdf->PageNo().'/{nb}', 0, 0, 'C');

$pdf->Output("I", "As_ON_Report.pdf");
?>
