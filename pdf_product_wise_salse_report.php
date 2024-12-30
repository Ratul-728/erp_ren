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

$qry="SELECT s.`id`, s.`socode`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`,st.name stnm,s.invoiceamount `amount`
                                        ,i.name product,d.qty,(d.otc+d.vat) unitprice,d.discounttot
                                        FROM `soitem` s left join `organization` o on s.organization=o.id left join soitemdetails d on s.socode=d.socode left join item i on d.productid=i.id
                                        left join orderstatus st on s.orderstatus=st.id 
                                        WHERE 1=1 ORDER BY  s.id DESC";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Product Wise Sales Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'SO ID', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Organization', 1, 0, 'C', true);  
$pdf->Cell(30, 10, 'Order Date', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Status', 1, 0, 'C', true);
$pdf->Cell($col_w_prod, 10, 'Product', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Unit Price', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Discount Total', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Net', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
                            
        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(30, 10, $row2["socode"], 1); // Category
        $pdf->Cell(30, 10, $row2["organization"], 1); // Product
        $pdf->Cell(30, 10, $row2["orderdate"], 1); // Barcode
        $pdf->Cell(30, 10, $row2["stnm"], 1); // Store Type
        $pdf->Cell($col_w_prod, 10, $row2["product"], 1); // Quantity
        $pdf->Cell(30, 10, $row2["qty"], 1);
        $pdf->Cell(30, 10, number_format($row2["unitprice"], 2, ".", ","), 1);
        $pdf->Cell(30, 10, number_format($row2["discounttot"], 2, ".", ","), 1);
        $pdf->Cell(30, 10, number_format($row2["amount"], 2, ".", ","), 1);
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

$pdf->Output("I", "Product_Wise_Sale_Report.pdf");
?>