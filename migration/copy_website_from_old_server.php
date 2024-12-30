<?php
// URL of the file to download
$fileUrl = "https://rdlerp.bithut.biz/migration/website_backup.zip";

// Get the current folder where the script is running
$currentFolder = __DIR__;
$saveTo = $currentFolder . "/website_backup.zip";

// Run the wget command to download the file
$wgetCommand = "wget -O $saveTo $fileUrl";
exec($wgetCommand, $output, $returnVar);

// Check if the command was successful
if ($returnVar === 0) {
    echo "File downloaded successfully: $saveTo\n";
} else {
    echo "Failed to download the file. Command output:\n";
    echo implode("\n", $output);
}
?>
