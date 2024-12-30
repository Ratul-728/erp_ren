<?php
// Define the zip file and the target extraction path
$zipFile = __DIR__ . "/website_backup.zip"; // Path to the zip file
$extractTo = dirname(__DIR__); // Parent directory of the current folder

// Run the unzip command
$unzipCommand = "unzip $zipFile -d $extractTo";
exec($unzipCommand, $output, $returnVar);

// Check if the command was successful
if ($returnVar === 0) {
    echo "Zip file extracted successfully to: $extractTo\n";
} else {
    echo "Failed to extract the zip file. Command output:\n";
    echo implode("\n", $output);
}
?>
