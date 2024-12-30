<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

$zipFileName = "/home/bithut/rdlerp.bithut.biz/migration/website_backup.zip";
$sourceFolder = "/home/bithut/rdlerp.bithut.biz/";
$exclusions = [
    "/home/bithut/rdlerp.bithut.biz/common/conn.php",
    "/home/bithut/rdlerp.bithut.biz/common/error_log",
    "/home/bithut/rdlerp.bithut.biz/assets/images/products/*",
    "/home/bithut/rdlerp.bithut.biz/migration/*",
    "/home/bithut/rdlerp.bithut.biz/php-mac-address-master/*",
    "/home/bithut/rdlerp.bithut.biz/data/*",
    "*.txt",
    "/home/bithut/rdlerp.bithut.biz/phpajax/error_log"
];

// Build exclusion arguments
$excludeArgs = '';
foreach ($exclusions as $exclude) {
    $excludeArgs .= ' -x "' . $exclude . '"';
}

// Build the full zip command
$command = "zip -r \"$zipFileName\" \"$sourceFolder\" $excludeArgs";

// Execute the command in the background
exec($command . " > /dev/null 2>&1 &");

echo "Zip process started in the background. Check the destination folder for the output.";
?>
