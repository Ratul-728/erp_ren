<?php
// Database connection details

ini_set('display_errors',1);
//print_r($_POST);die;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../common/conn.php";
    
    
    $servername = $server_name;
    $username = $mysql_username;
    $password = $mysql_password;
    $dbname = $db_name;
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Get form data
    $is_shutdown = ($_POST['maintenanceSwitchValue'] == 1) ? 1 : 0;
    $maintenance_message = $conn->real_escape_string($_POST['maintenanceMessage']);
    
    // Update the database
    $sql = "UPDATE shutdown SET is_shutdown = '$is_shutdown', maintenance_message = '$maintenance_message' WHERE id = 1";
    
    //echo $sql;die;
    
    if ($conn->query($sql) === TRUE) {
        echo "Maintenance mode updated successfully.";
        header('location:index.php?success=1');
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    // Close the connection
    $conn->close();
}
?>
