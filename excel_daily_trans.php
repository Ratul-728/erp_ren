<?php

    echo "started 2";
    require "common/conn.php";
    require_once "common/PHPExcel.php";
    //exit;
  if($fdt != ''){
        $date_qry = " and m.`transdt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
    }else{
        $date_qry = "";
    }
   date_default_timezone_set('Asia/Dhaka'); // Set timezone to Bangladesh

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'SL.')
    ->setCellValue('B1', 'Date')
    ->setCellValue('C1', 'Ref')
    ->setCellValue('D1', 'Vouch No')
    ->setCellValue('E1', 'GL Account')
    ->setCellValue('F1', 'Debit')
    ->setCellValue('G1', 'Credit')
    ->setCellValue('H1', 'Narration')
    ->setCellValue('I1', 'Maker')
    ->setCellValue('J1', 'Checker')
    ->setCellValue('K1', 'Approver');

// Query
$qry = "SELECT a.id, m.`transdt` `entrydate`, a.`remarks`, a.`vouchno`, CONCAT(c.`glnm`, '(', c.`glno`, ')') glnm, a.`dr_cr`, a.`amount`, h.hrName makeusr, h1.hrName checkusr, h2.hrName apprvusr, m.remarks narr 
FROM glmst m 
JOIN `gldlt` a ON m.vouchno = a.vouchno 
LEFT JOIN coa c ON a.`glac` = c.glno 
LEFT JOIN hr h ON m.entryby = h.id 
LEFT JOIN hr h1 ON m.checkby = h1.id 
LEFT JOIN hr h2 ON m.approvedby = h2.id 
WHERE m.isfinancial IN ('0', 'A') 
AND (a.glac = '0' OR '0' = '0') $date_qry 
ORDER BY m.`transdt` ASC";

$result = $conn->query($qry);
if ($result->num_rows > 0) {
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $urut = $i + 2; // Row number
        $col2 = 'B' . $urut; // Date column
        
        // Convert MySQL datetime to Excel-compatible date (Date only)
        $entrydate = $row['entrydate']; // MySQL datetime format
        $dateValue = PHPExcel_Shared_Date::PHPToExcel(
            strtotime(date('Y-m-d', strtotime($entrydate))) // Strip the time and convert
        );

        // Set the value for the date cell
        $objPHPExcel->getActiveSheet()
            ->setCellValue($col2, $dateValue) // Assign numeric value
            ->getStyle($col2)
            ->getNumberFormat()
            ->setFormatCode('DD/MM/YYYY'); // Date format without time

        // Write other values
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A' . $urut, $i + 1) // SL
            ->setCellValue('C' . $urut, $row['remarks']) // Ref
            ->setCellValue('D' . $urut, $row['vouchno']) // Vouch No
            ->setCellValue('E' . $urut, $row['glnm']) // GL Account
            ->setCellValue('F' . $urut, $row['dr_cr'] == 'C' ? $row['amount'] : 0) // Debit
            ->setCellValue('G' . $urut, $row['dr_cr'] != 'C' ? $row['amount'] : 0) // Credit
            ->setCellValue('H' . $urut, $row['narr']) // Narration
            ->setCellValue('I' . $urut, $row['makeusr']) // Maker
            ->setCellValue('J' . $urut, $row['checkusr']) // Checker
            ->setCellValue('K' . $urut, $row['apprvusr']); // Approver

        $i++;
    }
}

$objPHPExcel->getActiveSheet()->setTitle('Daily Transection');
$objPHPExcel->setActiveSheetIndex(0);

$today = date("YmdHis");
$fileNm = "data/" . 'daily_transection' . $today . '_1.xls';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($fileNm);

// Send file to browser
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $fileNm);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($fileNm));
ob_clean();
flush();
readfile($fileNm);
exit;
?>
