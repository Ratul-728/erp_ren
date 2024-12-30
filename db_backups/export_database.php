<?php

ini_set('display_errors', 1);

include_once('../common/conn.php');



$host = $dbhost;
$username = $dbuser;
$password = $dbpassword;
$database = $dbname;
$backupDir = 'files/';
$date = date('Y-m-d_H-i-s');

$sqlFile = $backupDir . $database . '_' . $date . '.sql';
$zipFile = $backupDir . $database . '_' . $date . '.zip';

if (!file_exists($backupDir)) {
    mkdir($backupDir, 0777, true);
}


// Export the database to a SQL file
$command = "mysqldump --opt -h $host -u $username -p$password $database > $sqlFile";
system($command, $output);

if ($output == 0) {
    // Create a ZIP file from the SQL file
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
        $zip->addFile($sqlFile, basename($sqlFile));
        $zip->close();
        unlink($sqlFile); // Delete the original SQL file after creating the ZIP
        echo "Backup created successfully.<br><hr>";
        echo '<input type="button" value="Back to Backup List" onclick="location.href=\'list_backup.php\'" ><hr>';
    } else {
        echo "Error creating ZIP file.";
    }
} else {
    echo "Error creating backup.";
}
?>