<?php
require "common/conn.php";
session_start();

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$col_w_prod = 80;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$qry="SELECT i.image,q.id,q.socode,q.srctype,q.project,(case when q.srctype=2 then q.project else 'Retail' end ) 'type', cat.name cat
                                ,o.name customer,q.organization cusid,a.product_id ,i.name,DATE_FORMAT(q.orderdate, '%d/%b/%Y') orderdate,(SELECT MAX(DATE_FORMAT(quow.expted_deliverey_date, '%d/%b/%Y'))
                                FROM quotation_warehouse quow
                                WHERE a.order_id = quow.socode) AS deliverydt,q.orderstatus,qs.name orderst
                                ,a.product_id,(a.quantity -COALESCE((SELECT sum(delivered_qty) FROM delivery_order_detail where qa_id=a.id),0)) quantity
                                ,a.status ,qas.name qastatus,qw.qa_type,qw.warehouse_id,qw.ordered_qty,COALESCE(qw.pass_qty,0) pass_qty
                                ,COALESCE((SELECT sum(delivered_qty) FROM delivery_order_detail where qa_id=a.id),0) deliverdqty
                                FROM quotation q left join qa a on q.socode=a.order_id left join quotation_status qs on q.orderstatus=qs.id left join qastatus qas on a.status=qas.id
                                left join qa_warehouse qw on a.id=qw.qa_id left join organization o on q.organization=o.id 
                                left join branch b on qw.warehouse_id=b.id left join item i on a.product_id=i.id LEFT JOIN itmCat cat ON cat.id=i.catagory
                                WHERE  q.orderstatus in(4,5,7) and qw.ordered_qty>COALESCE((SELECT sum(delivered_qty) FROM delivery_order_detail where qa_id=a.id),0) ";


$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Allocated Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Quotation', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Type', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Customer', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Category', 1, 0, 'C', true);
$pdf->Cell($col_w_prod, 10, 'Product', 1, 0, 'C', true);  
$pdf->Cell(30, 10, 'Order Date', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Delivery Date', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Order Status', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Allocated QTY', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'QA Status', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Ordered QTY', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Passed QTY', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Delivered QTY', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        
        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(20, 10, $row2["socode"], 1); // Category
        $pdf->Cell(20, 10, $row2["type"], 1); // Product
        $pdf->Cell(40, 10, $row2["customer"], 1); // Barcode
        $pdf->Cell(40, 10, $row2["cat"], 1); // Store Type
        $pdf->Cell($col_w_prod, 10, $row2["name"], 1); // Store
        $pdf->Cell(30, 10, $row2["orderdate"], 1); // Quantity
        $pdf->Cell(30, 10, $row2["deliverydt"], 1); // Barcode
        $pdf->Cell(30, 10, $row2["orderst"], 1); // Store Type
        $pdf->Cell(20, 10, $row2["quantity"], 1); // Store
        $pdf->Cell(20, 10, $row2["qastatus"], 1); // Quantity
        $pdf->Cell(20, 10, $row2["ordered_qty"], 1); // Barcode
        $pdf->Cell(20, 10, $row2["pass_qty"], 1); // Store Type
        $pdf->Cell(20, 10, $row2["deliverdqty"], 1); 
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

$pdf->Output("I", "Allocated_Report.pdf");
?>