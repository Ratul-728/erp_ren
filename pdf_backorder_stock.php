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

$qry="SELECT s.id,t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode barcode, s.storerome,p.image
                                FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id  
                                where s.storerome=8 and s.freeqty<>0 
                                order by p.name ASC";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Backorder Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Category', 1, 0, 'C', true);
$pdf->Cell($col_w_prod, 10, 'Product', 1, 0, 'C', true);  
$pdf->Cell(30, 10, 'Barcode', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Stock Type', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Store', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'QTY', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Price including VAT', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Total', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        
        $tnm=$row2["tn"]; $prod=$row2["pn"];$str=$row2["str"];  
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
                            
        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(60, 10, $tnm, 1); // Category
        $pdf->Cell($col_w_prod, 10, $prod, 1); // Product
        $pdf->Cell(30, 10, $bc, 1); // Barcode
        $pdf->Cell(30, 10, $storetype, 1); // Store Type
        $pdf->Cell(30, 10, $str, 1); // Store
        $pdf->Cell(20, 10, number_format($freeqty, 0, ".", ","), 1); // Quantity
        $pdf->Cell(30, 10, number_format($mup, 2, ".", ","), 1);
        $pdf->Cell(20, 10, number_format($mp, 2, ".", ","), 1);
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

$pdf->Output("I", "Backorder_Stock_Report.pdf");
?>