<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
opcache_reset();

// Database connection parameters
$servername = "182.163.119.122";
$database = "anviz";
$username = "sa";
$password = "Admin@rdl2022@@#";

try {
    // Establish a connection to the database
    $conn = new PDO("sqlsrv:server=$servername;database=$database", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL query to select data
    $sql = "SELECT * FROM Checkinout";
    
    // Execute the query and fetch data
    $result = $conn->query($sql);
    $data = $result->fetchAll(PDO::FETCH_ASSOC);

    // Output the data (you can customize this part based on your needs)
    //print_r($data);
    //echo '<pre>';
    
    //$jsonData = json_encode($data, JSON_PRETTY_PRINT);
    /* connection for mysql server */
    
    $host = "localhost";
    $username = "bithut_kazi";
    $password = "asdf1234X";
    $database = "bithut_bitflowstaging";
    
    // Create a MySQLi connection
    $conn2 = new mysqli($host, $username, $password, $database);
    
    // Check connection
    if ($conn2->connect_error) {
        die("Connection failed: " . $conn2->connect_error);
    }
    

    
    foreach($data as $data){
    
    $data['CheckTime'] =  substr($data['CheckTime'], 0, -4); 
    
    $keys = implode(", ", array_keys($data));
    
    
    $values = "'" . implode("', '", $data) . "'";

    $sql = "INSERT INTO attendance_from_device ($keys) VALUES ($values)";

    if ($conn2->query($sql) === TRUE) {
        //echo "Record inserted successfully<br>";
        echo $sql.";<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn2->error;
    }

       // print_r($val); 
    }
    //echo '</pre>';
    
   // echo $jsonData;

} catch (PDOException $e) {
    // Handle database connection errors
    echo "Connection failed: " . $e->getMessage();
}

// Close the connection
$conn = null;

?>