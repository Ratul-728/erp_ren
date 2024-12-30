<?php
require "common/conn.php";
session_start();

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$draw = $_POST['draw']; 
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

//print_r($_REQUEST);die;

$col_w_prod = 140;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}


        $emp = $_GET["emp"];
        
        
        
        
      $fdt= $_GET['dt_f'];

      $tdt= $_GET['dt_t'];
      
        if($fdt != ''){
            //$date_qry = " and i.`invoicedt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') "; 
            $date_qry = " and i.`invoicedt` between '$fdt' and '$tdt' "; 
        }else{
            $date_qry = "";
        }   
      

        $qry="select i.invoiceno,i.invoicedt,o.orgcode,o.name customer,i.amount_bdt ,i.paidamount,i.dueamount,h.hrName slperson
                       from invoice i left join organization o on  i.organization=o.id
                       left join hr h on  i.makeby=h.id
                       where 1=1 $date_qry ";
                       
                       
                       
 
    
        


        
        
                              

$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'User Sales Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Invoice No', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Invoice Date', 1, 0, 'C', true);  
$pdf->Cell(30, 10, 'Customer Code', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Customer', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Total Amount', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Paid Amount', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Due Amount', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Sale Person', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
                            
        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(60, 10, $row2["invoiceno"], 1); // Category
        $pdf->Cell(30, 10, $row2["invoicedt"], 1); // Product
        $pdf->Cell(30, 10, $row2["orgcode"], 1); // Barcode
        $pdf->Cell(30, 10, $row2["customer"], 1); // Store Type
        $pdf->Cell(30, 10, number_format($row2["amount_bdt"], 0, ".", ","), 1); // Quantity
        $pdf->Cell(30, 10, number_format($row2["paidamount"], 2, ".", ","), 1);
        $pdf->Cell(30, 10, number_format($row2["dueamount"], 2, ".", ","), 1);
        $pdf->Cell(30, 10, $row2["slperson"], 1); // Store
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

$pdf->Output("I", "User_Sales_Report.pdf");
?>