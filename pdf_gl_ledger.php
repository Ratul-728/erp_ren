<?php
require "common/conn.php";
require "rak_framework/misfuncs.php";
include_once('../rak_framework/fetch.php');
session_start();

require('fpdf/v186/fpdf.php');

$usr = $_SESSION["user"]; 
$mod = $_GET['mod'];

$fdt= $_GET['dt_f'];
$fdt = DateTime::createFromFormat('Y-m-d', $fdt)->format('d/m/Y');

$tdt= $_GET['dt_t'];
$tdt = DateTime::createFromFormat('Y-m-d', $tdt)->format('d/m/Y');

$fdt = $fdt. " - ".$tdt;

$fvouch = $_GET['fvouch'];
//$glnature= fetchByID('coa','glno',$fvouch,'dr_cr');

$strDateRange = ($fdt && $tdt) ? '(' . date("d/M/Y", strtotime($_GET['dt_f'])) . ' to ' . date("d/M/Y", strtotime($_GET['dt_t'])) . ')' : "";

      
if($fdt != '' && $fdt != 'undefined-undefined-'){
    $date_qry = " and m.`transdt`  between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
}else{
    $date_qry = "";
}

$col_w_prod = 140;

if ($usr == '') {
    header("Location: ".$hostpath."/hr.php");
    exit;
}

$glntureq="select dr_cr from coa where glno='$fvouch'";//echo $glntureq;die;
$resultglnature = $conn->query($glntureq); 
while($rowglnature = $resultglnature->fetch_assoc())
{
    $glnature = $rowglnature["dr_cr"];
}

$opbal=0;
        $opbalqry="select (COALESCE(o.opbal,0)+COALESCE(a.amt,0)-COALESCE(b.amt,0)) op
        from
        (select opbal from coa_mon where  glno='$fvouch' 
        and yr=year(STR_TO_DATE('".$fdt."','%d/%m/%Y')) and mn=month(STR_TO_DATE('".$fdt."','%d/%m/%Y'))
        )o
         ,
        (select sum(d.amount) amt from glmst m, gldlt d 
        where m.vouchno=d.vouchno  and d.dr_cr='D' and  d.glac='$fvouch' and m.isfinancial in('0','A')
        and ( m.transdt between DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'%Y-%m-01')  and STR_TO_DATE('".$fdt."','%d/%m/%Y'))
        )a
        ,(select sum(d.amount) amt from glmst m, gldlt d 
        where m.vouchno=d.vouchno  and d.dr_cr='C' and  d.glac='$fvouch' and m.isfinancial in('0','A')
        and (m.transdt between DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'%Y-%m-01')  and STR_TO_DATE('".$fdt."','%d/%m/%Y'))
        )b";
// echo $opbalqry;die;
        $resultopbal = $conn->query($opbalqry); 
        while($rowopbal = $resultopbal->fetch_assoc()) {
            $opbal = $rowopbal["op"];
        }
       
       /* if($opbal>0) 
        {$d_bal=$opbal;$c_bal=0;}
        else {$d_bal=0;$c_bal=$opbal;}
        */
        
        if($glnature=='D')
        {
        if($opbal>0)
        {$d_bal=$opbal;$c_bal=0;}
        else {$d_bal=0;$c_bal=$opbal;}
        }
        else
        {
        if($opbal>0)
        {$d_bal=0;$c_bal=$opbal;}
        else {$d_bal=$opbal;$c_bal=0;}
        }



$qry = "select '' VouchNo,DATE_FORMAT(STR_TO_DATE('".$fdt."', '%d/%m/%Y'), '%d/%b/%Y') AS TransDt,'' refno,'Opening Balance' remarks,'' sl,'' glac,'' glnm,$d_bal D_amount, $c_bal C_amount
                   union all
                   select a.VouchNo,DATE_FORMAT( a.TransDt,'%d/%b/%Y') TransDt ,a.refno,a.remarks,d.sl,d.glac,g.glnm,COALESCE((case d. dr_cr when 'D' then d.amount else 0 End),0) D_amount,COALESCE((case d.dr_cr when 'C' then d.amount else 0 End),0) C_amount  
                                 from glmst a  join gldlt d on a.VouchNo=d.VouchNo and a.isfinancial in('0','A')
                                  join coa g on d.glac=g.glno
                                 where (d.glac='$fvouch'  )
                                 and (a.TransDt  between  STR_TO_DATE('".$fdt."','%d/%m/%Y')  and STR_TO_DATE('".$tdt."','%d/%m/%Y')) order by TransDt,VouchNo";
//echo $qry;die;                                 
$resultinv = $conn->query($qry); // Make sure to execute the query

// Create new PDF instance
$pdf = new FPDF('P', 'mm', 'A3');
$pdf->SetFont('Arial', 'B', 8);
$pdf->AliasNbPages();
$pdf->AddPage();

// Company logo and title
$pdf->Image('./assets/images/site_setting_logo/' . $_SESSION["comlogo"], 10, 10, 30);
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Gl Ledger '.$strDateRange , 0, 1, 'C');

// Column headers
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(10, 10, 'SL', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Vouch No', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Transaction Date', 1, 0, 'C', true); 
$pdf->Cell(30, 10, 'Reference', 1, 0, 'C', true);  
//$pdf->Cell(50, 10, 'Remarks', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'GL Acccount', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'GL Name', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Debit', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Credit', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Ledger', 1, 0, 'C', true);
$pdf->Ln(); // Move to the next line

// Table rows
$pdf->SetFont('Arial', '', 8);
$sl = 1;  $cl=0;
if ($resultinv->num_rows > 0) {
    while ($row2 = $resultinv->fetch_assoc()) {
        
      //$cl=$cl+$row2['D_amount']-$row2['C_amount']; 
      $dr=$row2['D_amount'];$cr=$row2['C_amount'];

            if($glnature=='D')
            {
                $cl=$cl+$row2["D_amount"]-$row2["C_amount"];
            }
            else
            {
                $cl=$cl-$row2["D_amount"]+$row2["C_amount"];
            } 

        // Data rows with word wrapping using MultiCell();
        $pdf->Cell(10, 10, $glnature, 1); // Serial number
        $pdf->Cell(30, 10, $row2["VouchNo"], 1); // Category with word wrap
        $pdf->Cell(30, 10, $row2["TransDt"], 1); // Brand with word wrap
        $pdf->Cell(30, 10, $row2["refno"], 1); // Product with word wrap 
       // $pdf->Cell(50, 10, $row2["remarks"], 1);
        $pdf->Cell(30, 10, $row2["glac"], 1);
        $pdf->Cell(30, 10, $row2["glnm"], 1);
        $pdf->Cell(30, 10, number_format($dr,2), 1); // Rate Including VAT 
        $pdf->Cell(30, 10, number_format($cr, 2), 1); // Total
         $pdf->Cell(30, 10, number_format($cl, 2), 1); // Total
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

$pdf->Output("I", "Gl_ledger.pdf");
?>