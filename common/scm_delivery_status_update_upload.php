<?php
require "conn.php";
require "../rak_framework/fetch.php";
// include_once('email_config.php');
// include_once('../email_messages/email_user_message.php');
// require_once('phpmailer/PHPMailerAutoload.php');


$doid = $_POST['doid'];
$orid = $_POST['orid'];

function getDeliveredQty($doid,$itemid){
    global $conn;
}

function getReturnedQty($doid,$itemid){
    global $conn;
}

function saveToSoItemDetails($deliveredqty, $return_qty, $productid, $orid)
{
    global $conn; // Assuming you have already established the database connection.

    // Sanitize the input data to prevent SQL injection
    $deliveredqty = mysqli_real_escape_string($conn, $deliveredqty);
    $return_qty = mysqli_real_escape_string($conn, $return_qty);
    $productid = mysqli_real_escape_string($conn, $productid);
    $socode = mysqli_real_escape_string($conn, $socode);

    // Check if the record already exists in the table
    $sql = "SELECT * FROM soitemdetails WHERE socode = '$socode' AND productid = '$productid'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // If the record already exists, update the values
        $sql = "UPDATE soitemdetails SET deliveredqty = '$deliveredqty', return_qty = '$return_qty' WHERE socode = '$socode' AND productid = '$productid'";
    }

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Record saved successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}



// Usage

// Sample data to be saved
//$deliveredqty = getDeliveredQty($doid,$itemid);
//$return_qty = getReturnedQty($doid,$itemid);
$productid = 123;
$socode = $orid;

// Call the function to save the data
//saveToSoItemDetails($deliveredqty, $return_qty, $productid, $socode);




function uploadDeliveryChallan()
{
    global $doid,$orid;
    $allowedTypes = array('application/pdf', 'image/jpeg');
    $maxSize = 10 * 1024 * 1024; // 10MB
    $uploadDir = 'upload/delivery_challan/';

    if (isset($_FILES['delchallan']) && $_FILES['delchallan']['error'] === UPLOAD_ERR_OK) {
        $fileType = $_FILES['delchallan']['type'];
        $fileSize = $_FILES['delchallan']['size'];

        // Check file type
        if (!in_array($fileType, $allowedTypes)) {
            return 'Error: Only PDF or JPG files are allowed.';
        }

        // Check file size
        if ($fileSize > $maxSize) {
            return 'Error: File size exceeds the limit (10MB).';
        }
        // Using pathinfo to get the file extension
        $fileInfo = pathinfo($_FILES['delchallan']['name']);

        // Accessing the file extension
        $extension = $fileInfo['extension'];        

        $deliveryid = $doid; // Replace with the actual deliveryid
        $orderid = "$orid"; // Replace with the actual orderid
        $date = date('Ymd');
        $filename = $deliveryid . '_' . $orderid . '_' . $date.'.'.$extension;

        // Move the uploaded file to the destination folder
        $uploadedFile = $_FILES['delchallan']['tmp_name'];
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($uploadedFile, $destination)) {
            //return 'File uploaded successfully.';
            // Return the uploaded file path
            return $destination;
            
        } else {
            return 'Error: Failed to upload the file.';
        }
    } else {
        return 'Error: No file uploaded or an error occurred during upload.';
    }
}


function updateSoItemDeliveryChallanPath($soItemId, $challanPath)
{
    global $conn,$date, $doid; // Assuming you have already established the database connection.

    // Sanitize the input data to prevent SQL injection
    $soItemId = mysqli_real_escape_string($conn, $soItemId);
    $challanPath = mysqli_real_escape_string($conn, $challanPath);

    // Update the "delivey_challan_path" column in the "soitem" table
    $sql = "UPDATE soitem SET delivey_challan_path = '$challanPath', delivey_challan_upload_date='$date' WHERE socode = '$soItemId'";
    //echo $sql;die;
    if ($conn->query($sql) === TRUE) {
        //echo "File path updated successfully.";
        header("location:../scm_delivery_status_detail.php?mod=16&do=".$doid."&msg=Challan file uploaded successfully.");
    } else {
        $msg = "Error updating file path: " . $conn->error;
        header("location:../scm_delivery_status_detail.php?mod=16&do=".$doid."&msg=$msg");
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    // Call the uploadDeliveryChallan function
    $challanPath = uploadDeliveryChallan();

    if (strpos($challanPath, 'Error') === 0) {
        // The function returned an error, display the message
        header("location:../scm_delivery_status_detail.php?mod=16&do=".$doid."&msg=$challanPath");
    } else {
        // Update the "delivey_challan_path" column in the "soitem" table
        $soItemId = $orid; // Replace with the actual SoItem ID
        updateSoItemDeliveryChallanPath($soItemId, $challanPath);
    }    
    
}



?>