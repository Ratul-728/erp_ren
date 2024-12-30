<?php
ini_set('display_errors',0);

include_once "../common/conn.php"; // Include your database connection
include_once "../rak_framework/fetch.php";


     
    //if($_REQUEST['new_price'] && $_REQUEST['item_id']){
    if($_POST['new_price'] && $_POST['item_id']){
        
        $order_id = $_REQUEST['order_id'];
        $serial = $_REQUEST['serial'];
        $new_price = $_REQUEST['new_price'];
        $item_id = $_REQUEST['item_id'];
        $existing_price = $_REQUEST['existing_price'];
        $customer_id = $_REQUEST['customer_id'];
        $reason = $_REQUEST['reason'];
        $makeby = $_REQUEST['makeby'];
        $makedt = $date;
        $reason = 'Customer Request';
        
        
        
    
        // Ensure values are sanitized
        $new_price = mysqli_real_escape_string($conn, $new_price);
        $item_id = intval($item_id);
    
        // Check if the item_id already exists in the table
        $checkQuery = "SELECT id FROM approval_quotation_price_change WHERE item_id = $item_id and order_id='$order_id'";
        $checkResult = mysqli_query($conn, $checkQuery);
    
        if (mysqli_num_rows($checkResult) > 0) {
            // If the record exists, update it
            $sql = "UPDATE approval_quotation_price_change 
                    SET new_price = '$new_price', 
                    order_id = '$order_id',
                    serial = '$serial',
                    existing_price = '$existing_price',
                    customer_id = '$customer_id',
                    reason = '$reason',
                    makeby = '$makeby',
                    state = '0'
                    WHERE item_id = '$item_id' and order_id='$order_id'";
        } else {
            // If the record doesn't exist, insert a new one
            $sql = "INSERT INTO approval_quotation_price_change (
                        order_id,
                        item_id, 
                        existing_price,
                        new_price,
                        serial,
                        customer_id,
                        reason,
                        makeby,
                        makedt
                        )VALUES (
                        '$order_id', 
                        '$item_id', 
                        '$existing_price',
                        '$new_price',
                        '$serial',
                        '$customer_id',
                        '$reason',
                        '$makeby',
                        '$makedt'
                        )";
        }
    
        // Execute the query
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['status' => 'success', 'message' => 'Price change processed.','query' => $checkQuery.'  '.$sql]);
        }else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to process the price change.','query' => $checkQuery.'  '.$sql]);
        }
    
    }else{
        echo json_encode(['status' => 'error', 'message' => 'new_price or item_id missing.','query' => print_r($_REQUEST)]);
    }

?>
