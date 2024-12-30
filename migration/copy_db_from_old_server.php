<?php
$remote_zip_url = 'https://rdlerp.bithut.biz/migration/database_backup.sql'; // Replace with the URL of the zip file on the old server
$local_zip_path = __DIR__ . '/db_backup.sql'; // Local path to save the zip file
$extract_to = __DIR__; // Directory to extract files to

// Download the zip file
$file_content = file_get_contents($remote_zip_url);
if ($file_content) {
    file_put_contents($local_zip_path, $file_content);
    echo "Zip file downloaded successfully.\n";

    // Extract the zip file
    $zip = new ZipArchive();
    if ($zip->open($local_zip_path)) {
        $zip->extractTo($extract_to);
        $zip->close();
        echo "Files have been extracted successfully.\n";

        // Clean up
        unlink($local_zip_path);
    } else {
        echo "Failed to extract zip file.\n";
    }
} else {
    echo "Failed to download zip file.\n";
}
?>


<?php
// Database connection details for the new server
$host = 'localhost'; // Database host
$username = 'u497252501_rdldbusr'; // Replace with your database username
$password = '3+KfoVd4N^'; // Replace with your database password
$database = 'u497252501_rdlproduction'; // Replace with your database name

// URL of the ZIP file on the old server
$remote_zip_url = 'https://rdlerp.bithut.biz/migration/database_backup.zip'; // Replace with the actual URL
$local_zip_path = __DIR__ . '/database_backup.zip'; // Local path to save the ZIP file
$sql_file = __DIR__ . '/database_backup.sql'; // Path to the unzipped SQL file

// Step 1: Download the ZIP file
$file_content = file_get_contents($remote_zip_url);
if ($file_content) {
    file_put_contents($local_zip_path, $file_content);
    echo "Database backup downloaded successfully.\n";

    // Step 2: Extract the ZIP file
    $zip = new ZipArchive();
    if ($zip->open($local_zip_path)) {
        $zip->extractTo(__DIR__); // Extract to the current directory
        $zip->close();
        echo "Database backup extracted successfully.\n";

        // Step 3: Import the SQL file into the database
        $command = "mysql -h $host -u $username -p$password $database < $sql_file";
        exec($command, $output, $result);

        if ($result === 0) {
            echo "Database imported successfully into $database.\n";

            // Clean up: Remove the ZIP and SQL files after import
            unlink($local_zip_path);
            unlink($sql_file);
        } else {
            echo "Failed to import database.\n";
        }
    } else {
        echo "Failed to extract ZIP file.\n";
    }
} else {
    echo "Failed to download database backup.\n";
}
?>
