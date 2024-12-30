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

$qry="SELECT i.name pnm,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit ,qw.ordered_qty total_qty,(case when d.st=0 then 'Decline' when d.st=2 then 'Approved' else  'Pending' end) st,
                      GROUP_CONCAT(qi.image_url) damge_image,h.hrName approved_by, (case when qw.qa_type=1 then 'Sold' when qw.qa_type=2 then 'Purchase' when qw.qa_type=3 then 'Return' when qw.qa_type=4 then 'Transfer' 
                      when qw.qa_type=5 then 'Issue' when qw.qa_type=6 then 'Return' else 'na' end ) qatype,q.order_id,qw.damaged_qty damaged_qty, qw.id qaw_id
                      FROM approval_damaged d left join qa_warehouse qw on d.qaw_id=qw.id left join qa_images qi on qi.qaw_id=qw.id and qi.type='damaged' left join qa q on qw.qa_id=q.id
                      left join item i on q.product_id=i.id left join hr h on d.approved_by=h.id
                      Where 1=1 $dateqry 
                      group by i.name,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,d.qty,d.st,d.approved_by,
                      q.order_id,qw.defect_qty,h.hrName";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Damage Products Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(80, 10, 'Product', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Barcode', 1, 0, 'C', true);  
$pdf->Cell(20, 10, 'Color Text', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Length', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Length Unit', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Width', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Width Unit', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Height', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Height Unit', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'QA Qty', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Damaged Qty', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Approved By', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'QA Type', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Against ID', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Status', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row = $resultinv->fetch_assoc()) {
        
        $pdf->Cell(10, 10, $sl, 1); 
        $pdf->Cell(80, 10, $row["pnm"], 1);
        $pdf->Cell(30, 10, $row["barcode"], 1);
        $pdf->Cell(20, 10, $row["colortext"], 1);
        $pdf->Cell(20, 10, $row["length"], 1);
        $pdf->Cell(20, 10, $row["lengthunit"], 1);
        $pdf->Cell(20, 10, $row["width"], 1);
        $pdf->Cell(20, 10, $row["widthunit"], 1);
        $pdf->Cell(20, 10, $row["height"], 1);
        $pdf->Cell(20, 10, $row["heightunit"], 1);
        $pdf->Cell(20, 10, $row["total_qty"], 1);
        $pdf->Cell(20, 10, $row["damaged_qty"], 1);
        $pdf->Cell(30, 10, $row["approved_by"], 1);
        $pdf->Cell(20, 10, $row["qatype"], 1);
        $pdf->Cell(30, 10, $row["order_id"], 1);
        $pdf->Cell(20, 10, $row["st"], 1);
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

$pdf->Output("I", "Damage_Products_Report.pdf");
?>
