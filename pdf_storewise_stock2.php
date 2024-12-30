<?php
require "common/conn.php";
session_start();
 //ini_set("display_errors",1);
 require('fpdf/v186/fpdf.php');

    $usr = $_SESSION["user"]; 
    $mod = $_GET['mod'];
    
    $branch = $_GET["branch"]; if ($branch == '') $branch = 0;
    $brand = $_GET["brand"]; if ($brand == '') $brand = 0;
    $cat = $_GET["cat"]; if ($cat == '') $cat = 0;
    $bc1 = $_GET["barcode"];
    //$cat = 221;
    $col_w_prod = 140;
    
     $cellHeight = 20;
     
     $cellSerialWidth = 10;
     $cellPhotoWidth = 20;
     $cellProductWidth = 140;
     $cellCatWidth = 40;
     $cellBarcodeWidth = 30;
     $cellStoreTypeWidth = 30;
     $cellStoreWidth = 30;
     $cellQtyWidth = 20;
     $cellRateWidth = 30;
     $cellTotalWidth = 30;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$qry = "SELECT s.id,t.name tn,p.name pn,p.image photo, s.freeqty,s.costprice,p.rate mrp,r.name str,p.barcode barcode, s.storerome, p.image, b.title brand
        FROM chalanstock s 
        LEFT JOIN item p ON s.product = p.id 
        LEFT JOIN itmCat t ON p.catagory=t.id 
        LEFT JOIN branch r ON s.storerome=r.id  
        LEFT JOIN brand b ON b.id=p.brand
        WHERE (s.barcode='".$bc1."' OR p.barcode='".$bc1."' OR '".$bc1."'='' OR p.name LIKE '%".$bc1."%')
        AND (r.id = ".$branch." OR ".$branch." = 0)
        AND (t.id = ".$cat." OR ".$cat." = 0)
        AND (b.id = ".$brand." OR ".$brand." = 0) 
        AND s.freeqty<>0
        ORDER BY s.id DESC";

//echo $qry;die;
$resultinv = $conn->query($qry);



class PDF extends FPDF
{
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Set font for footer
        $this->SetFont('Arial', 'I', 8);
        // Footer text with page number
        $this->Cell(0, 10, $_SESSION["comname"] . " - Page " . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
 

 
// Create new PDF instance
$pdf = new PDF('L', 'mm', 'A3');
$pdf->AliasNbPages();
$pdf->SetFont('Arial', 'B', 8);
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Store Wise Stock Report', 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);

$pdf->Cell($cellSerialWidth, $cellHeight, 'SL', 1, 0, 'C', true);
//$pdf->Cell(40, $cellHeight, 'Category', 1, 0, 'C', true);
//$pdf->Cell(40, $cellHeight, 'Brand', 1, 0, 'C', true);
$pdf->Cell($cellPhotoWidth, $cellHeight, 'Photo', 1, 0, 'C', true);
$pdf->Cell($cellProductWidth, $cellHeight, 'Product', 1, 0, 'C', true);
$pdf->Cell($cellCatWidth, $cellHeight, 'Category', 1, 0, 'C', true);
$pdf->Cell($cellBarcodeWidth, $cellHeight, 'Barcode', 1, 0, 'C', true);
$pdf->Cell($cellStoreTypeWidth, $cellHeight, 'Store Type', 1, 0, 'C', true);
$pdf->Cell($cellStoreWidth, $cellHeight, 'Store', 1, 0, 'C', true);
$pdf->Cell($cellQtyWidth, $cellHeight, 'QTY', 1, 0, 'C', true);
$pdf->Cell($cellRateWidth, $cellHeight, 'Rate Inc. VAT', 1, 0, 'C', true);
$pdf->Cell($cellTotalWidth, $cellHeight, 'Total', 1, 1, 'C', true);

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;
$totqty = 0; $totwithrat = 0; $totalamount = 0;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        $tnm = $row2["tn"];
        $photo = $row2["photo"];
        $prod = $row2["pn"];
        $str = $row2["str"];
        $br = $row2["brand"];
        $freeqty = $row2["freeqty"];
        $cup = $row2["costprice"];
        $mup = $row2["mrp"];
        $bc = $row2["barcode"];
        $cp = $freeqty * $cup;
        $mp = $freeqty * $mup;
        
        $totqty += $freeqty;
        $totwithrat += $mup;
        $totalamount += $mp;
        




        if ($row2["storerome"] == 8) {
            $storetype = "Future Stock";
        } elseif ($row2["storerome"] == 8) {
            $storetype = "Back Stock";
        } else {
            $storetype = "In Stock";
        }
        //$lineHeight = $pdf->GetFontSize();  

        // Data rows with word wrapping using MultiCell() 
        $pdf->Cell($cellSerialWidth, $cellHeight, $sl, 1); // Serial number
        //$pdf->Cell(40, $cellHeight, $tnm, 1); // Category with word wrap
        //$pdf->Cell(40, $cellHeight, $br, 1); // Brand with word wrap
       // $pdf->Cell(40, $cellHeight, $photo, 1);
       //Move cursor to the right for the image
        $x = $pdf->GetX(); // Current X position
        $y = $pdf->GetY(); // Current Y position
        
        if(is_file('assets/images/products/300_300/'.$photo)){
            $ImgFilePath = 'assets/images/products/300_300/'.$photo;
        }else{
             $ImgFilePath = 'assets/images/products/no_image.png';
        }
        
        if (mime_content_type($ImgFilePath) !== 'image/jpeg') {
            //die('Error: File is not a valid JPEG image.');
             $ImgFilePath = 'assets/images/products/no_image.png';
        }
        //echo $ImgFilePath;
        //die;
        
  
        $pdf->Image($ImgFilePath, $x, $y, $cellPhotoWidth,$cellHeight);
        //$pdf->Image('assets/images/products/300_300/'.$photo, $x, $y, $calculated_width, $desired_height);
        $pdf->Cell($cellPhotoWidth, $cellHeight, "", 1); // blank cell for photo width, height, data, 
        $pdf->Cell($cellProductWidth, $cellHeight, $prod, 1); // Product with word wrap 
        $pdf->Cell($cellCatWidth, $cellHeight, $tnm, 1); // Category with word wrap
        $pdf->Cell($cellBarcodeWidth, $cellHeight, $bc, 1); // Barcode 
        $pdf->Cell($cellStoreTypeWidth, $cellHeight, $storetype, 1,0,'C');  // Store Type
        $pdf->Cell($cellStoreWidth, $cellHeight, $str, 1,0,'C'); // Store Name
        $pdf->Cell($cellQtyWidth, $cellHeight, number_format($freeqty, 0, ".", ","), 1, 0, 'C'); // Quantity
        $pdf->Cell($cellRateWidth, $cellHeight, number_format($mup, 2, ".", ","), 1, 0, 'R'); // Rate Including VAT 
        $pdf->Cell($cellTotalWidth, $cellHeight, number_format($mp, 2, ".", ","), 1, 1, 'R'); // Total
        $sl++;
    }
     /*
        $pdf->Cell(10, $cellHeight, "", 1); // Serial number
        //$pdf->Cell($cellCatWidth, $cellHeight, "", 1); // Category with word wrap 
        //$pdf->Cell(40, $cellHeight, "", 1); // Brand with word wrap
        
        $pdf->Cell(40, $cellHeight, "", 1);
        $pdf->Cell($cellProductWidth, $cellHeight, "", 1); // Product with word wrap 
        $pdf->Cell($cellBarcodeWidth, $cellHeight, "", 1); // Barcode 
        $pdf->Cell($cellStoreTypeWidth, $cellHeight, "", 1); // Store Type
        $pdf->Cell($cellStoreWidth, $cellHeight, "Total: ", 1); // Store Name
        $pdf->Cell($cellQtyWidth, $cellHeight, number_format($totqty, 0, ".", ","), 1, 0, 'R'); // Quantity
        $pdf->Cell($cellRateWidth, $cellHeight, number_format($totwithrat, 2, ".", ","), 1, 0, 'R'); // Rate Including VAT 
        $pdf->Cell($cellTotalWidth, $cellHeight, number_format($totalamount, 2, ".", ","), 1, 1, 'R'); // Total
        */
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}
/*
// Footer
$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, $_SESSION["comname"]." - Page ".$pdf->PageNo().'/{nb}', 0, 0, 'C');
*/
$pdf->Output("I", "Store_Wise_Stock_Report.pdf");
?>
