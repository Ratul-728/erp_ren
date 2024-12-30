<?php
require "conn.php";
session_start();

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');

$maxid="SELECT (max(`id`)+1) cd FROM `rfq_invoice`";
$resultmid = $conn->query($maxid); if ($resultmid->num_rows > 0) {while($rowmid = $resultmid->fetch_assoc()) { $reqid= $rowmid["cd"];}} if($reqid == '') $reqid = 1;
$pono = "PO-Invoice".$reqid;

$rfqpo = $_POST["itid"];
$note = $_POST["note"];  $note = addslashes($note);

$qry1 = "INSERT INTO `rfq_invoice`(`invoiceno`, `rfqpo`, `note`, `makedt`) 
                            VALUES ('$pono', '$rfqpo', '$note', sysdate())";
//echo $qry;die;                    
$rfpoid = $_POST["rfpoid"];
$invoice_amount = $_POST["invoiceamt"];

//File Upload
$count = count($_FILES['input-ficons-1']['name']);
for($i = 0; $i < $count; $i++){
        $filename = $_FILES['input-ficons-1']['name'][$i];
        //$filename .= $date;
        // destination of the file on the server
        $destination = '../images/upload/documents/'.$filename;
        // get the file extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $file = $_FILES['input-ficons-1']['tmp_name'][$i];
        
        if (move_uploaded_file($file, $destination)){
            $qry = "INSERT INTO `rfq_documents`( `invoiceno`, `file`) VALUES ('$pono', '$filename')";
            if ($conn->query($qry) == TRUE){
                //header("Location: ".$hostpath."/employee_hr.php?res=4&&mod=4&id=".$empid);
            }else{
                $error++;
            }
        }else{
            echo "File does not upload !!!";
        }
    }
                    
for ($i=0;$i<count($rfpoid);$i++){
    $rfqDe=$rfpoid[$i]; $invoiceamt = $invoice_amount[$i];
    
    //Insert Into RFQ PO Invoice Detaitls 
    $qrydetails = "INSERT INTO `rfq_invoice_details`(`invoiceno`, `rfqpo_details`, `invoice_amount`, `makedt`) 
                                                VALUES ('$pono', '$rfqDe', '$invoiceamt', sysdate())";
    //echo $qrydetails;die;
    $conn->query($qrydetails);
    
    
}

if($conn->query($qry1) == TRUE){
    $err = "Successfully Create Invoice";
    header("Location: ".$hostpath."/rfq_invoiceList.php?res=1&msg=".$err."&mod=14&pg=1");
}else{
    $err = "Something went wrong";
    header("Location: ".$hostpath."/rfq_invoiceList.php?res=2&msg=".$err."&mod=14&pg=1");
}

?>