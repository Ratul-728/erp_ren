<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
opcache_reset();
date_default_timezone_set("Asia/Dhaka");

// Database connection parameters
//$servername = "182.163.119.122";
//$servername = "182.163.119.154";
//$servername = "182.163.119.130";
$servername = "27.147.152.122";

$database = "anviz";
$username = "sa";
$password = "Admin@rdl2022@@#";


//bithut server
    /* connection for MySQL server */

    $host_mysql = "localhost";
    $username_mysql = "u497252501_rdldbusr";
    $password_mysql = "3+KfoVd4N^";
    $database_mysql = "u497252501_rdlproduction";

    // Create a MySQLi connection
    $conn_mysql = new mysqli($host_mysql, $username_mysql, $password_mysql, $database_mysql);

    // Check connection
    if ($conn_mysql->connect_error) {
        die("MySQL Connection failed: " . $conn_mysql->connect_error);
    }
    
    // Find login id
    $getLastIDQry = "SELECT max(Logid) AS maxLogid FROM attendance_from_device";
    $LLIResult = $conn_mysql->query($getLastIDQry);
    
    if ($LLIResult->num_rows > 0) {
        $LLIrow = $LLIResult->fetch_assoc();
        $LastLogID = $LLIrow["maxLogid"]; // Corrected index name
    
        //echo "Last LogID: " . $LastLogID; die;
        //die(); // Exit script
    } else {
        die("Last LogID not found");
    }
    
    
//end bithut server connection




try {
    // Establish a connection to the SQL Server database
    //$conn = new PDO("sqlsrv:server=$servername;database=$database", $username, $password);
    $conn = new PDO("odbc:mssql_odbc", $username, $password);


    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $error =  "Connection successful!";
    
    // SQL query to select data
    $sql = "SELECT * FROM Checkinout where Logid>$LastLogID";
    //$sql = "SELECT * FROM Checkinout ";

    // Execute the query and fetch data
    $result = $conn->query($sql);
    $data = $result->fetchAll(PDO::FETCH_ASSOC);

    //print_r($data);die;

    foreach ($data as $row) {
        // Adjust CheckTime format
        $row['CheckTime'] = substr($row['CheckTime'], 0, -4);

        // Escape and quote values for the MySQL query
        $values = "'" . implode("', '", array_map([$conn_mysql, 'real_escape_string'], $row)) . "'";

        // Column names should not be included in the VALUES clause
        $keys = implode(", ", array_keys($row));

        $sql_mysql = "INSERT INTO attendance_from_device ($keys) VALUES ($values)";

        if ($conn_mysql->query($sql_mysql) === TRUE) {
            //echo $sql_mysql . ";<br>";
        } else {
            echo "Error: " . $sql_mysql . "<br>" . $conn_mysql->error;
        }
    }

} catch (PDOException $e) {
    // Handle SQL Server connection errors
    echo "SQL Server Connection failed: " . $e->getMessage();
    $error = "SQL Server Connection failed: " . $e->getMessage(). "\n";
} catch (Exception $e) {
    // Handle other exceptions
    echo "Exception: " . $e->getMessage();
    $error = "SQL Server Connection failed: " . $e->getMessage(). "\n";
}

// Close the connections
if ($conn) {
    $conn = null;
}

if ($conn_mysql) {
    $conn_mysql->close();
}

  //write log when it run;
  
    $file = 'attnDataFromDevice2_log.txt';
    $text = "Executed on: " . date("d/m/Y h:i:s A");
    $text = $text.' - '.$error . "\n";
    
    
    file_put_contents($file, $text, FILE_APPEND);
    
    
?>
