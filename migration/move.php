<?php
// Define the extracted base folder (adjust if different)
//$extractedFolder = __DIR__ . "/home/bithut/rdlerp.bithut.biz";
$extractedFolder = "/home/u497252501/domains/bithut.biz/public_html/rdlerp1/home/bithut/rdlerp.bithut.biz";

// Define the target folder where files should go
$targetFolder = dirname(__DIR__); // Parent folder of the current script

// Function to recursively move files from source to target
function moveFiles($source, $target) {
    if (!is_dir($source)) {
        echo "Source directory does not exist: $source\n";
        return false;
    }

    $files = scandir($source);
    foreach ($files as $file) {
        if ($file === "." || $file === "..") {
            continue; // Skip current and parent directory references
        }

        $sourcePath = $source . "/" . $file;
        $targetPath = $target . "/" . $file;

        if (is_dir($sourcePath)) {
            // Create the target directory if it doesn't exist
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0755, true);
            }
            // Recursively move subdirectory contents
            moveFiles($sourcePath, $targetPath);
        } else {
            // Move file to the target folder
            rename($sourcePath, $targetPath);
        }
    }

    // Remove the empty source directory
    rmdir($source);
    return true;
}

// Run the function to move files
if (moveFiles($extractedFolder, $targetFolder)) {
    echo "Files successfully moved to the target location: $targetFolder\n";
} else {
    echo "Failed to move files. Check the paths and permissions.\n";
}
?>
