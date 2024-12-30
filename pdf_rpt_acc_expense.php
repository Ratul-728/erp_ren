<?php
require "common/conn.php";
session_start();

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$fdt= $_GET['dt_f'];

$tdt= $_GET['dt_t'];

$time1 = strtotime($fdt);
$time2 = strtotime($tdt);

if ($time1 > $time2) {
    $flag = $fdt;
    $fdt = $tdt;
    $tdt =$flag;
}

if($fdt != ""){
    $date_qry = "AND e.trdt BETWEEN '$fdt' AND '$tdt'";
}else{
    $date_qry = "";
}

$col_w_prod = 140;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$qry        = "select t.name exp_ntre,e.amount,m.name modeofpayment
                            from expense e left join transtype t on e.transtype=t.id left join transmode m on e.transmode=m.id
                            where 1=1 $date_qry";
// echo $qry;die;        
$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('P', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Expense Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(80, 10, 'Expense Nature', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Amount', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Mode of Payment', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
$optot=0;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        $optot += $row2['amount'];
        
        // Data rows with word wrapping using MultiCell() 
        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(80, 10, $row2["exp_ntre"], 1); // Category with word wrap
        $pdf->Cell(50, 10, number_format($row2['amount'], 2), 1); // Brand with word wrap
        $pdf->Cell(40, 10, $row2["modeofpayment"], 1);
        $pdf->Ln(); // Move to the next line
        $sl++;
    }
        $pdf->Cell(10, 10,'' , 1); // Serial number
        $pdf->Cell(80, 10, 'Total ', 1); // Brand with word wrap
        $pdf->Cell(50, 10, number_format($optot, 2), 1); // Product with word wrap 
        $pdf->Cell(40, 10, '', 1);
}
else 
{
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

// Footer
$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, $_SESSION["comname"]." - Page ".$pdf->PageNo().'/{nb}', 0, 0, 'C');

$pdf->Output("I", "Expense_Report.pdf");
?>



