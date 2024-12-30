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

$fdt= $_GET['dt_f'];

$tdt= $_GET['dt_t'];
      
if($fdt != '' && $fdt != 'undefined-undefined-'){
    $date_qry = " and pl.`gnr_date` between DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d') ";
}else{
    $date_qry = "";
}


$qry="SELECT pl.poid, pl.`voucher_no`, DATE_FORMAT(pl.`voucher_date`,'%d/%b/%Y') voucher_date, pl.pi_no, DATE_FORMAT(pl.`pi_date`,'%d/%b/%Y') pi_date, pl.lc_tt_no,DATE_FORMAT(pl.`lc_tt_date`,'%d/%b/%Y') lc_tt_date, pl.at,pl.ait, pl.gnr_no,DATE_FORMAT(pl.`gnr_date`,'%d/%b/%Y') gnr_date,
                    concat(emp.firstname, ' ', emp.lastname) nm, b.name warehouse, pos.dclass, pl.st, pos.name stnm, pl.id, pl.payment_amount
                    ,(SELECT sum(`tot_landed_cost`) landedcost FROM purchase_landing_item i where i.`pu_id`=pl.id) landedcost
                    ,pl.containerno
                    FROM `purchase_landing` pl LEFT JOIN employee emp ON pl.received_by=emp.id LEFT JOIN suplier b ON b.id=pl.warehouse LEFT JOIN purchase_st pos ON pos.id=pl.st
                    WHERE 1=1 $date_qry order by pl.`id` desc";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Purchase Order', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'PO ID', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Voucher No', 1, 0, 'C', true);  
$pdf->Cell(30, 10, 'Voucher Date', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'LC/TT Date', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Payment Amount', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Total Landed Cost', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Container No', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'GRN No', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'GRN Date', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Supplier', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Received By', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Status', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1; $totamount = 0; $totlan = 0;
if ($resultinv->num_rows > 0) {
    while ($row = $resultinv->fetch_assoc()) {
        
        $pdf->Cell(10, 10, $sl, 1); 
        $pdf->Cell(20, 10, $row["poid"], 1);
        $pdf->Cell(30, 10, $row["voucher_no"], 1);
        $pdf->Cell(30, 10, $row["voucher_date"], 1);
        $pdf->Cell(30, 10, $row["lc_tt_date"], 1);
        $pdf->Cell(40, 10, number_format($row["payment_amount"], 2), 1);
        $pdf->Cell(40, 10, number_format($row["landedcost"], 2), 1);
        $pdf->Cell(30, 10, $row["containerno"], 1);
        $pdf->Cell(20, 10, $row["gnr_no"], 1);
        $pdf->Cell(30, 10, $row["gnr_date"], 1);
        $pdf->Cell(60, 10, $row["warehouse"], 1);
        $pdf->Cell(30, 10, $row["nm"], 1);
        $pdf->Cell(30, 10, $row["stnm"], 1);
        $pdf->Ln(); // Move to the next line
        $sl++;
        
        $totamount += $row["payment_amount"]; $totlan += $row["landedcost"];
    }
    
    $pdf->Cell(10, 10, "", 1); 
        $pdf->Cell(20, 10, "", 1);
        $pdf->Cell(30, 10, "", 1);
        $pdf->Cell(30, 10, "", 1);
        $pdf->Cell(30, 10, "Total", 1);
        $pdf->Cell(40, 10, number_format($totamount, 2), 1);
        $pdf->Cell(40, 10, number_format($totlan, 2), 1);
        $pdf->Cell(30, 10, "", 1);
        $pdf->Cell(20, 10, "", 1);
        $pdf->Cell(30, 10, "", 1);
        $pdf->Cell(60, 10, "", 1);
        $pdf->Cell(30, 10, "", 1);
        $pdf->Cell(30, 10, "", 1);
        $pdf->Ln(); // Move to the next line
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

// Footer
$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, $_SESSION["comname"]." - Page ".$pdf->PageNo().'/{nb}', 0, 0, 'C');

$pdf->Output("I", "Purchase_Order.pdf");
?>
