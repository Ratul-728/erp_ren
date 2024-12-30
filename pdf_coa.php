<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

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
// Create new PDF instance
$pdf = new FPDF('L', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Trial Balance Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'GL Account', 1, 0, 'C', true);
$pdf->Cell(80, 10, 'GL Name', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Parrent GL', 1, 0, 'C', true);  
$pdf->Cell(20, 10, 'Is Posted?', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Type', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Level', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Openning Balance', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Closing Balance', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;

$qry = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE `status` in( 'A','1')  and `oflag`='N' and lvl = 1 ";
// echo $qry;die;        
$result = $conn->query($qry); // Make sure to execute the query
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        
        $glLvl1 = $row["glno"];
                
        if($row["dr_cr"] == 'D'){
            $type = "Debit";
        }else{
           $type = "Credit";
        }
               
        if($row["isposted"] == 'P'){
           $isposted = "YES";
        }else{
           $isposted = "NO";
        }
               
               
        // Data rows with word wrapping using MultiCell() 
        $pdf->Cell(10, 10, $sl, 1); // Serial number
        $pdf->Cell(40, 10, $row["glno"], 1); // Category with word wrap
        $pdf->Cell(80, 10, $row["glnm"], 1); // Brand with word wrap
        $pdf->Cell(40, 10, $row["ctlgl"], 1); // Product with word wrap 
        $pdf->Cell(20, 10, $isposted, 1);
        $pdf->Cell(20, 10, $type, 1); // Rate Including VAT 
        $pdf->Cell(20, 10, $row["lvl"], 1); // Total
        $pdf->Cell(40, 10, number_format($row['opbal'],2), 1);
        $pdf->Cell(40, 10, number_format($row['closingbal'],2), 1);
        $pdf->Ln(); // Move to the next line
        $sl++;
        
        $qry2 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl1."'   and `oflag`='N'";
        $result2 = $conn->query($qry2); // Make sure to execute the query
        while ($row2 = $result2->fetch_assoc()) {
        
            $glLvl2 = $row2["glno"];
                    
            if($row2["dr_cr"] == 'D'){
                $type = "Debit";
            }else{
               $type = "Credit";
            }
                   
            if($row2["isposted"] == 'P'){
               $isposted = "YES";
            }else{
               $isposted = "NO";
            }
                   
                   
            // Data rows with word wrapping using MultiCell() 
            $pdf->Cell(10, 10, $sl, 1); // Serial number
            $pdf->Cell(40, 10, $row2["glno"], 1); // Category with word wrap
            $pdf->Cell(80, 10, "    ".$row2["glnm"], 1); // Brand with word wrap
            $pdf->Cell(40, 10, $row2["ctlgl"], 1); // Product with word wrap 
            $pdf->Cell(20, 10, $isposted, 1);
            $pdf->Cell(20, 10, $type, 1); // Rate Including VAT 
            $pdf->Cell(20, 10, $row2["lvl"], 1); // Total
            $pdf->Cell(40, 10, number_format($row2['opbal'],2), 1);
            $pdf->Cell(40, 10, number_format($row2['closingbal'],2), 1);
            $pdf->Ln(); // Move to the next line
            $sl++;
            
            $qry3 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl2."' and `oflag`='N'";
            $result3 = $conn->query($qry3); // Make sure to execute the query
            while ($row3 = $result3->fetch_assoc()) {
            
                $glLvl3 = $row3["glno"];
                        
                if($row3["dr_cr"] == 'D'){
                    $type = "Debit";
                }else{
                   $type = "Credit";
                }
                       
                if($row3["isposted"] == 'P'){
                   $isposted = "YES";
                }else{
                   $isposted = "NO";
                }
                       
                       
                // Data rows with word wrapping using MultiCell() 
                $pdf->Cell(10, 10, $sl, 1); // Serial number
                $pdf->Cell(40, 10, $row3["glno"], 1); // Category with word wrap
                $pdf->Cell(80, 10, "        ".$row3["glnm"], 1); // Brand with word wrap
                $pdf->Cell(40, 10, $row3["ctlgl"], 1); // Product with word wrap 
                $pdf->Cell(20, 10, $isposted, 1);
                $pdf->Cell(20, 10, $type, 1); // Rate Including VAT 
                $pdf->Cell(20, 10, $row3["lvl"], 1); // Total
                $pdf->Cell(40, 10, number_format($row3['opbal'],2), 1);
                $pdf->Cell(40, 10, number_format($row3['closingbal'],2), 1);
                $pdf->Ln(); // Move to the next line
                $sl++;
                
                $qry4 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl3."' and `oflag`='N'";
                $result4 = $conn->query($qry4); // Make sure to execute the query
                while ($row4 = $result4->fetch_assoc()) {
                
                    $glLvl4 = $row4["glno"];
                            
                    if($row4["dr_cr"] == 'D'){
                        $type = "Debit";
                    }else{
                       $type = "Credit";
                    }
                           
                    if($row4["isposted"] == 'P'){
                       $isposted = "YES";
                    }else{
                       $isposted = "NO";
                    }
                           
                           
                    // Data rows with word wrapping using MultiCell() 
                    $pdf->Cell(10, 10, $sl, 1); // Serial number
                    $pdf->Cell(40, 10, $row4["glno"], 1); // Category with word wrap
                    $pdf->Cell(80, 10, "            ".$row4["glnm"], 1); // Brand with word wrap
                    $pdf->Cell(40, 10, $row4["ctlgl"], 1); // Product with word wrap 
                    $pdf->Cell(20, 10, $isposted, 1);
                    $pdf->Cell(20, 10, $type, 1); // Rate Including VAT 
                    $pdf->Cell(20, 10, $row4["lvl"], 1); // Total
                    $pdf->Cell(40, 10, number_format($row4['opbal'],2), 1);
                    $pdf->Cell(40, 10, number_format($row4['closingbal'],2), 1);
                    $pdf->Ln(); // Move to the next line
                    $sl++;
                    
                    $qry5 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl4."' and `oflag`='N'";
                    $result5 = $conn->query($qry5); // Make sure to execute the query
                    while ($row5 = $result5->fetch_assoc()) {
                    
                        $glLvl5 = $row5["glno"];
                                
                        if($row5["dr_cr"] == 'D'){
                            $type = "Debit";
                        }else{
                           $type = "Credit";
                        }
                               
                        if($row5["isposted"] == 'P'){
                           $isposted = "YES";
                        }else{
                           $isposted = "NO";
                        }
                               
                               
                        // Data rows with word wrapping using MultiCell() 
                        $pdf->Cell(10, 10, $sl, 1); // Serial number
                        $pdf->Cell(40, 10, $row5["glno"], 1); // Category with word wrap
                        $pdf->Cell(80, 10, "                ".$row5["glnm"], 1); // Brand with word wrap
                        $pdf->Cell(40, 10, $row5["ctlgl"], 1); // Product with word wrap 
                        $pdf->Cell(20, 10, $isposted, 1);
                        $pdf->Cell(20, 10, $type, 1); // Rate Including VAT 
                        $pdf->Cell(20, 10, $row5["lvl"], 1); // Total
                        $pdf->Cell(40, 10, number_format($row5['opbal'],2), 1);
                        $pdf->Cell(40, 10, number_format($row5['closingbal'],2), 1);
                        $pdf->Ln(); // Move to the next line
                        $sl++;
                    }
                }
            }
        }
    }
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

// Footer
$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, $_SESSION["comname"]." - Page ".$pdf->PageNo().'/{nb}', 0, 0, 'C');

$pdf->Output("I", "Chart_of_Accounts.pdf");
?>