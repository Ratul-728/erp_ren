<?php
require "common/conn.php";
session_start();

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$fdt= $_GET['dt_f'];

$tdt= $_GET['dt_t'];
      
if($fdt != '' && $fdt != 'undefined-undefined-'){
    $date_qry = " and w.transdt <= DATE_FORMAT('$fdt', '%Y-%m-%d') ";
}else{
    $date_qry = "";
}

$col_w_prod = 140;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$qry="SELECT 
                                    customercode,
                                    customernm,
                                    balance
                                FROM (
                                    SELECT 
                                        o.orgcode AS customercode,
                                        o.name AS customernm,
                                        (
                                            (SELECT COALESCE(SUM(w.amount), 0) 
                                             FROM organizationwallet w 
                                             WHERE w.dr_cr = 'D' AND w.orgid = o.id $dateqry) 
                                            -
                                            (SELECT COALESCE(SUM(w.amount), 0) 
                                             FROM organizationwallet w 
                                             WHERE w.dr_cr = 'C' AND w.orgid = o.id $dateqry)
                                        ) AS balance
                                    FROM 
                                        organization o
                                ) r 
                                WHERE 
                                    r.balance < 0";

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Accounts Receivable Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Customer Code', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Customer Name', 1, 0, 'C', true);  
$pdf->Cell(60, 10, 'Receivable Amount', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row = $resultinv->fetch_assoc()) {

        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(60, 10, $row['customercode'], 1); // Category
        $pdf->Cell(60, 10, $row['customernm'], 1); // Product
        $pdf->Cell(60, 10, number_format($row['balance'], 2), 1);
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

$pdf->Output("I", "Accounts_Receivable_Report.pdf");
?>
