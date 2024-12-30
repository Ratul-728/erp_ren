<?php
// Database connection details
$host = 'localhost';
$username = 'bithut_kazi'; // Replace with your database username
$password = 'CWljV4KooFwjnl9'; // Replace with your database password
$database = 'bithut_renestaging'; // Replace with your database name
$output_file = 'database_backup.sql'; // Output SQL file
$zip_file = 'database_backup.zip'; // Output ZIP file

// Export database to .sql file
$command = "mysqldump -h $host -u $username -p$password $database > $output_file";
exec($command, $output, $result);

if ($result === 0) {
    echo "Database exported to $output_file.\n";

    // Create a ZIP file containing the .sql file
    $zip = new ZipArchive();
    if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
        $zip->addFile($output_file);
        $zip->close();
        echo "Database backup compressed into $zip_file.\n";

        // Remove the .sql file after zipping
        if (unlink($output_file)) {
            echo "Temporary SQL file $output_file removed.\n";
        } else {
            echo "Failed to remove temporary SQL file.\n";
        }
    } else {
        echo "Failed to create ZIP file.\n";
    }
} else {
    echo "Failed to export database.\n";
}
?>
