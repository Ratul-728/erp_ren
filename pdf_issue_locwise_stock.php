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

$qry = "select i.id,t.name catnm,b.title brand,p.image,p.name prod,p.barcode,'Issue' tp,r.name issueloc,id.qty,p.rate 
                            from issue_order i join issue_order_details id on i.id=id.ioid  left join item p on p.id=id.product LEFT JOIN itmCat t ON p.catagory=t.id
                            LEFT JOIN issue_warehouse r ON i.issue_warehouse=r.id  LEFT JOIN brand b ON b.id=p.brand
                                where id.qty<>0 order by p.name asc";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Issue Location Wise Stock Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Category', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Brand', 1, 0, 'C', true);
$pdf->Cell($col_w_prod, 10, 'Product', 1, 0, 'C', true);  
$pdf->Cell(30, 10, 'Barcode', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Stock Type', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Store', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'QTY', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Price Including VAT', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Total', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        $tnm=$row2["catnm"]; $prod=$row2["prod"];$str=$row2["issueloc"]; $br=$row2["brand"];  
            $freeqty=$row2["qty"]; $cup=0; $mup=$row2["rate"]; $bc=$row2["barcode"];
            //$cp=$freeqty*$cup;
            $mp=$freeqty*$mup; 
            //$tcp=$tcp+$cp;
            $tmp=$tmp+$mp;

        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(40, 10, $tnm, 1); // Category
        $pdf->Cell(40, 10, $br, 1);
        $pdf->Cell($col_w_prod, 10, $prod, 1); // Product
        $pdf->Cell(30, 10, $bc, 1); // Barcode
        $pdf->Cell(30, 10, $storetype, 1); // Store Type
        $pdf->Cell(30, 10, $str, 1); // Store
        $pdf->Cell(20, 10, number_format($freeqty, 0), 1); // Quantity
        $pdf->Cell(40, 10, number_format($mup, 0), 1);
        $pdf->Cell(30, 10, number_format($mp, 0), 1);
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

$pdf->Output("I", "Issue_Location_Wise_Stock_Report.pdf");
?>
