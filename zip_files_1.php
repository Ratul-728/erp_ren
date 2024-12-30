<style>
body{
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 95vh;
    font-size: 30px;
    padding: 0 20px;
    background-color: #607a80;
    color: #fff;
}
</style>
Please wait...
<?php
$folder_to_zip = __DIR__; // The current directory containing your website files
$zip_file = 'website_backup.zip'; // Name of the zip file to create

// List of files to exclude
$excluded_files = [
    '/zip_files.php',
    '/common/conn.php', // Exclude this file
    '/error_log',       // Exclude this file
];

// Function to check if a file is in the excluded list
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

    foreach ($files as $file) {
        if (!$file->isDir()) {
            $file_path = $file->getRealPath();
            $relative_path = substr($file_path, strlen($folder_to_zip) + 1);

            // Skip excluded files
            if (isExcluded($file_path, $excluded_files, $folder_to_zip)) {
                //echo "Skipping excluded file: $relative_path\n";
                continue;
            }

            $zip->addFile($file_path, $relative_path);
        }
    }

    $zip->close();
    echo 'Files have been zipped into:'. $zip_file.' <br> <br><a href="#" class="btn">Transfer File to New server</a>';
} else {
    echo "Failed to create zip file.\n";
}
?>
