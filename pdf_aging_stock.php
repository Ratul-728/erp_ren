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

$qry="SELECT i.image,t.name tn,i.id,i.name pn,s.freeqty,s.costprice,i.rate mrp,r.name str,s.barcode,DATE_FORMAT(max(p.makedt),'%d/%b/%Y') purchagedt
,DATEDIFF(sysdate(),max(p.makedt)) nosdays 
FROM 
purchase_landing p ,purchase_landing_item pi,
chalanstock s LEFT JOIN item i ON s.product = i.id 
LEFT JOIN itmCat t ON i.catagory=t.id 
LEFT JOIN branch r ON s.storerome=r.id
where   
p.id=pi.pu_id and pi.productId=i.id and
s.`freeqty`>0 
        GROUP by t.name ,i.name ,s.freeqty,s.costprice,i.rate ,r.name ,s.barcode
        ";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Aging Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Category', 1, 0, 'C', true);
$pdf->Cell($col_w_prod, 10, 'Product', 1, 0, 'C', true);  
$pdf->Cell(30, 10, 'Barcode', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Store', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Purchase Date', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'No of Days Old', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'QTY', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'MRP', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'MRP Total', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {

        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(60, 10, $row2["tn"], 1); // Category
        $pdf->Cell($col_w_prod, 10, $row2["pn"], 1); // Product
        $pdf->Cell(30, 10, $row2["barcode"], 1); // Barcode
        $pdf->Cell(30, 10, $row2["str"], 1); // Store Type
        $pdf->Cell(30, 10, $row2["purchagedt"], 1); // Store
        $pdf->Cell(30, 10, $row2["nosdays"], 1); // Quantity
        $pdf->Cell(30, 10, $row2["freeqty"], 1);
        $pdf->Cell(30, 10, number_format($row2['mrp'],2), 1);
        $pdf->Cell(30, 10, number_format($row2['freeqty']*$row2['mrp'],2), 1);
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

$pdf->Output("I", "Aging_Report.pdf");
?>

