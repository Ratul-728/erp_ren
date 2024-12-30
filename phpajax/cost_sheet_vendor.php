<?php 

require "../common/conn.php";
session_start();
$usr=$_SESSION["user"];

$productId = $_POST["prod"];
$productnm = $_POST["prodnm"];
$rfqId = $_POST["rfq"];
$flag = $_POST["flag"];

$mainStr = '<input type="hidden" id="rv_prid" value="'.$productId.'">
            <label for="">Select All Vendor for  ('.$productnm.' <span id="show_prid"></span>)</label>
            <table class="cost-sheet  table table-striped" width="100%" cellspacing="0" cellpadding="0" border="0">';


$qryVendor = "SELECT v.name, r.id, r.quated_price, r.vendor_id , rd.rfq
                FROM `rfq_vendor` r LEFT JOIN organization v ON r.vendor_id = v.id LEFT JOIN rfq_details rd ON rd.id = r.`rfq`
                WHERE r.rfq = '$rfqId' and r.product = '$productId' Order BY r.quated_price ASC";
//echo $qryVendor;die;
$resultVendor = $conn->query($qryVendor); 
while($rowVendor = $resultVendor->fetch_assoc()){
    $vendorName = $rowVendor["name"];  $vendorId = $rowVendor["vendor_id"];
    $qt_price   = $rowVendor["quated_price"]; $rId = $rowVendor["id"]; $rfq = $rowVendor["rfq"];
    $mainStr .= '<tr>
                    <td>'.$vendorName.'</td>
                    <td class="vendor" id="'.$rId.'">
                        <span>'.$qt_price.'<br>
                         '.$vendorName.'<br> '.$rfq.'</span>
                        <input type="hidden" class="vendorid" value="'.$rId.'" name="vendorid[]">
                    </td>
                </tr>';
}

$mainStr .= "</table>";

echo $mainStr;
?>