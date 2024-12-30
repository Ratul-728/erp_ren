<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    
    require "../common/conn.php";
    require "../rak_framework/misfuncs.php";
    
    // Get order_id and do_id from the POST request
    $order_id = $_POST['order_id'];
    $do_id = $_POST['do_id'];
    
    // Define upload directory
    $upload_dir = '/common/upload/delivery_challan/';
    
    // Extract file extension
    $file_ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    
    // Generate new file name
    $upload_date = date('Ymd');
    $new_file_name = $do_id . '_' . $order_id . '_' . $upload_date . '.' . $file_ext;
    
    // Full upload path
    $upload_path = $upload_dir . $new_file_name;
    
    // Move the uploaded file to the desired folder
    if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $upload_path)) {
        // Update database
        $upload_date_time = date('Y-m-d H:i:s');
        /*
        $conn = new mysqli('localhost', 'username', 'password', 'database');
        
        if ($conn->connect_error) {
            die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
        }
        */
        $sql = "UPDATE delivery_order SET delivery_challan_path = ?, upload_date = ? WHERE order_id = ? AND do_id = ?";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die(json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]));
        }
        
        // Bind the parameters
        $stmt->bind_param('ssss', $new_file_name, $upload_date_time, $order_id, $do_id);
        
        // Execute the statement
        if ($stmt->execute()) {
           echo json_encode(['success' => true, 'message' => 'File uploaded and database updated successfully.', 'path' => $new_file_name ,'date'=> formatDate($upload_date_time)]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
        }
        
        $stmt->close();
        $conn->close();
        
    } else {
        echo json_encode(['success' => false, 'message' => 'File upload failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
