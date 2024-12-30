<?php
require "common/conn.php";
session_start();

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$fdt= $_GET['dt_f'];

$tdt= $_GET['dt_t'];
      
if($fdt != '' && $fdt != 'undefined-undefined-'){
    $date_qry = " and qt.orderdate between DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d') ";
}else{
    $date_qry = "";
}

$col_w_prod = 140;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$qry="select DATE_FORMAT(qt.orderdate,'%d/%b/%Y') AS date, qt.socode order_id, o.name AS customer,  FORMAT(SUM((qd.discounttot+qd.discount_amount)/qd.qty), 2) AS amount, FORMAT(SUM(qd.vat), 2) AS vat,FORMAT(SUM(qd.discounttot/qd.qty), 2) AS adjustment_amount,FORMAT((SUM(qd.discounttot/qd.qty)*dd.`delivered_qty`), 2) delivery_amount
                       ,FORMAT(SUM(qd.discounttot), 2) AS discounted_total,FORMAT(SUM(qd.discounttot/qd.qty)*dd.`delivered_qty`, 2) revenue,FORMAT(SUM(qd.cost), 2) AS cost
                       , FORMAT((SUM(qd.discounttot/qd.qty)*dd.`delivered_qty`) - SUM(qd.cost), 2) AS margin
                              from delivery_order_detail dd ,qa q,quotation_detail qd,quotation qt,organization o where dd.qa_id=q.id  and q.order_id=qd.socode and dd.item=qd.productid and qd.socode=qt.socode 
                              and qt.organization = o.id  and dd.st=2 AND q.type=1 $date_qry
                                GROUP BY qt.orderdate, qt.socode, o.name
                                ORDER BY qt.orderdate DESC";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Revenue Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Date', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Order Number', 1, 0, 'C', true);  
$pdf->Cell(60, 10, 'Customer', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Amount', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Vat', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Adjustment Amount', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Delivery Amount', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Discounted Total', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Revenue', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
$totamount = 0; $totvat = 0; $totadamt = 0;
$totdeliamt = 0; $totdisamt = 0; $totrev = 0;
if ($resultinv->num_rows > 0) {
    while ($row = $resultinv->fetch_assoc()) {

        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(30, 10, $row['date'], 1); // Category
        $pdf->Cell(30, 10, $row['order_id'], 1); // Product
        $pdf->Cell(60, 10, $row['customer'], 1); // Barcode
        $pdf->Cell(60, 10, $row['amount'], 1); // Store Type
        $pdf->Cell(40, 10, $row['vat'], 1); // Store
        $pdf->Cell(40, 10, $row['adjustment_amount'], 1); 
        $pdf->Cell(40, 10, $row['delivery_amount'], 1);
        $pdf->Cell(40, 10, $row['discounted_total'], 1);
        $pdf->Cell(60, 10, $row['revenue'], 1); 
        $pdf->Ln(); // Move to the next line
        $sl++;
        
        // Remove commas and convert to float before adding
        $totamount += (float)str_replace(',', '', $row['amount']);
        $totvat += (float)str_replace(',', '', $row['vat']);
        $totadamt += (float)str_replace(',', '', $row['adjustment_amount']);
        $totdeliamt += (float)str_replace(',', '', $row['delivery_amount']);
        $totdisamt += (float)str_replace(',', '', $row['discounted_total']);
        $totrev += (float)str_replace(',', '', $row['revenue']);
    }
        
        $pdf->Cell(10, 10, "", 1); // Serial number
        $pdf->Cell(30, 10, "", 1); // Category
        $pdf->Cell(30, 10, "", 1); // Product
        $pdf->Cell(60, 10, "Total", 1); // Barcode
        $pdf->Cell(60, 10, number_format($totamount, 2), 1); // Store Type
        $pdf->Cell(40, 10, number_format($totvat, 2), 1); // Store
        $pdf->Cell(40, 10, number_format($totadamt, 2), 1); 
        $pdf->Cell(40, 10, number_format($totdeliamt, 2), 1);
        $pdf->Cell(40, 10, number_format($totdisamt, 2), 1);
        $pdf->Cell(60, 10, number_format($totrev, 2), 1); 
    
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

// Footer
$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, $_SESSION["comname"]." - Page ".$pdf->PageNo().'/{nb}', 0, 0, 'C');

$pdf->Output("I", "Revenue_Report.pdf");
?>
