<?php
require "common/conn.php";
session_start();
require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$draw = $_POST['draw']; 
$row = $_POST['start'];
$rowperpage = $_POST['length']; 
$columnIndex = $_POST['order'][0]['column']; 
$columnName = $_POST['columns'][$columnIndex]['data']; 
$columnSortOrder = $_POST['order'][0]['dir']; 
$searchValue = $_POST['search']['value']; 

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$emp = $_GET["emp"];
$fdt = $_GET['dt_f'];
$tdt = $_GET['dt_t'];

$strDateRange = ($fdt && $tdt) ? '(' . date("d/M/Y", strtotime($fdt)) . ' to ' . date("d/M/Y", strtotime($tdt)) . ')' : "";

$date_qry = ($fdt != '') ? " and i.`invoicedt` between '$fdt' and '$tdt' " : "";

$qry = "SELECT `id`, `vouchno`, DATE_FORMAT(`transdt`,'%d/%b/%Y') `transdt`, `refno`, substring(`remarks`,1,50) remarks 
        FROM `glmst` 
        WHERE `status` in('1','A')";

$resultinv = $conn->query($qry);

// Extend FPDF class to customize Footer
class PDF extends FPDF {
    function Footer() {
        $this->SetY(-15); // Position the footer at 15mm from the bottom
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, $_SESSION["comname"] . " - Page " . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Create new PDF instance
$pdf = new PDF('P', 'mm', 'A3');
$pdf->AliasNbPages();
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'GL Master ' . $strDateRange, 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Vouch No', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Transfer Date', 1, 0, 'C', true);  
$pdf->Cell(60, 10, 'Reference', 1, 0, 'C', true);
$pdf->Cell(100, 10, 'Remarks', 1, 0, 'C', true);
$pdf->Ln();

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        $pdf->Cell(10, 10, $sl, 1);
        $pdf->Cell(60, 10, $row2["vouchno"], 1);
        $pdf->Cell(40, 10, $row2["transdt"], 1);
        $pdf->Cell(60, 10, $row2["refno"], 1);
        $pdf->Cell(100, 10, $row2["remarks"], 1);
        $pdf->Ln();
        $sl++;
    }
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

// Output the PDF
$pdf->Output("I", "GL_MASTER.pdf");
?>
