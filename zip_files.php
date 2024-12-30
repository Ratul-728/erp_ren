<?php
ini_set('max_execution_time', 300); // Increase execution time (in seconds)
ini_set('memory_limit', '512M');   // Increase memory limit (adjust as needed)


$folder_to_zip = __DIR__; // Current directory
$zip_file = 'website_backup.zip'; // Output file

// List of excluded files and directories
$excluded_files = [
    '/common/conn.php',
    '/error_log',
];

// Function to check if a file is excluded
function isExcluded($file_path, $excluded_files, $base_dir) {
    foreach ($excluded_files as $excluded_file) {
        if (strpos($file_path, $base_dir . $excluded_file) !== false) {
            return true;
        }
    }
    return false;
}

$zip = new ZipArchive();
if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folder_to_zip),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    $count = 0; // Counter to track processed files
    foreach ($files as $file) {
        if (!$file->isDir()) {
            $file_path = $file->getRealPath();
            $relative_path = substr($file_path, strlen($folder_to_zip) + 1);

            // Skip excluded files
            if (isExcluded($file_path, $excluded_files, $folder_to_zip)) {
                echo "Skipping excluded file: $relative_path\n";
                continue;
            }

            // Add file to the zip archive
            $zip->addFile($file_path, $relative_path);
            $count++;

            // Commit after every 100 files to avoid memory overload
            if ($count % 100 === 0) {
                $zip->close();
                $zip->open($zip_file);
                echo "Processed $count files so far...\n";
            }
        }
    }

    $zip->close();
    echo "All files zipped successfully into: $zip_file\n";
} else {
    echo "Failed to create zip file.\n";
}
?>
