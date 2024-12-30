<?php
require "common/conn.php";
session_start();

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$branch = $_GET["branch"]; if ($branch == '') $branch = 0;
$brand = $_GET["brand"]; if ($brand == '') $brand = 0;
$cat = $_GET["cat"]; if ($cat == '') $cat = 0;
$bc1 = $_GET["barcode"];

$col_w_prod = 140;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$qry = "SELECT s.id,t.name tn,p.name pn,p.image photo, s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode barcode, s.storerome, p.image, b.title brand
        FROM chalanstock s 
        LEFT JOIN item p ON s.product = p.id 
        LEFT JOIN itmCat t ON p.catagory=t.id 
        LEFT JOIN branch r ON s.storerome=r.id  
        LEFT JOIN brand b ON b.id=p.brand
        WHERE (s.barcode='".$bc1."' OR p.barcode='".$bc1."' OR '".$bc1."'='' OR p.name LIKE '%".$bc1."%')
        AND (r.id = ".$branch." OR ".$branch." = 0)
        AND (t.id = ".$cat." OR ".$cat." = 0)
        AND (b.id = ".$brand." OR ".$brand." = 0) 
        AND s.freeqty<>0
        ORDER BY s.id DESC";

$resultinv = $conn->query($qry);



class PDF extends FPDF
{
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Set font for footer
        $this->SetFont('Arial', 'I', 8);
        // Footer text with page number
        $this->Cell(0, 10, $_SESSION["comname"] . " - Page " . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
 
 
// Create new PDF instance
$pdf = new PDF('L', 'mm', 'A3');
$pdf->AliasNbPages();
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Store Wise Stock Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Category', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Brand', 1, 0, 'C', true);
$pdf->Cell($col_w_prod, 10, 'Product', 1, 0, 'C', true);  
$pdf->Cell(30, 10, 'Barcode', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Store Type', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Store', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'QTY', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Rate Inc. VAT', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Total', 1, 1, 'C', true);

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
$totqty = 0; $totwithrat = 0; $totalamount = 0;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        $tnm = $row2["tn"];
        $photo = $row2["photo"];
        $prod = $row2["pn"];
        $str = $row2["str"];
        $br = $row2["brand"];
        $freeqty = $row2["freeqty"];
        $cup = $row2["costprice"];
        $mup = $row2["mrp"];
        $bc = $row2["barcode"];
        $cp = $freeqty * $cup;
        $mp = $freeqty * $mup;
        
        $totqty += $freeqty;
        $totwithrat += $mup;
        $totalamount += $mp;

        if ($row2["storerome"] == 8) {
            $storetype = "Future Stock";
        } elseif ($row2["storerome"] == 8) {
            $storetype = "Back Stock";
        } else {
            $storetype = "In Stock";
        }
        //$lineHeight = $pdf->GetFontSize();  

        // Data rows with word wrapping using MultiCell() 
        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(40, 10, $tnm, 1); // Category with word wrap
        $pdf->Cell(40, 10, $br, 1); // Brand with word wrap
        $pdf->Cell($col_w_prod, 10, $prod, 1); // Product with word wrap 
        $pdf->Cell(30, 10, $bc, 1); // Barcode 
        $pdf->Cell(30, 10, $storetype, 1); // Store Type
        $pdf->Cell(30, 10, $str, 1); // Store Name
        $pdf->Cell(20, 10, number_format($freeqty, 0, ".", ","), 1, 0, 'R'); // Quantity
        $pdf->Cell(30, 10, number_format($mup, 2, ".", ","), 1, 0, 'R'); // Rate Including VAT 
        $pdf->Cell(30, 10, number_format($mp, 2, ".", ","), 1, 1, 'R'); // Total
        $sl++;
    }
    
        $pdf->Cell(10, 10, "", 1); // Serial number
        $pdf->Cell(40, 10, "", 1); // Category with word wrap
        $pdf->Cell(40, 10, "", 1); // Brand with word wrap
        $pdf->Cell($col_w_prod, 10, "", 1); // Product with word wrap 
        $pdf->Cell(30, 10, "", 1); // Barcode 
        $pdf->Cell(30, 10, "", 1); // Store Type
        $pdf->Cell(30, 10, "Total: ", 1); // Store Name
        $pdf->Cell(20, 10, number_format($totqty, 0, ".", ","), 1, 0, 'R'); // Quantity
        $pdf->Cell(30, 10, number_format($totwithrat, 2, ".", ","), 1, 0, 'R'); // Rate Including VAT 
        $pdf->Cell(30, 10, number_format($totalamount, 2, ".", ","), 1, 1, 'R'); // Total
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}
/*
// Footer
$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, $_SESSION["comname"]." - Page ".$pdf->PageNo().'/{nb}', 0, 0, 'C');
*/
$pdf->Output("I", "Store_Wise_Stock_Report.pdf");
?>
