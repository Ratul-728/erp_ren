<?php
    $servername = "27.147.152.122";
    $database = "anviz";
    $username = "sa";
    $password = "Admin@rdl2022@@#";

    try {
        $dbh = new PDO("odbc:mssql_odbc", $username, $password);
        echo "Connection successful!";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

?>