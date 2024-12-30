<?php
session_start();
include_once('../common/conn.php');



if (!$_SESSION["user"]){
        header("Location: " . $hostpath . "/hr.php");
        exit();
}

$backupDir = 'files/';
$files = glob($backupDir . '*.zip');

// Sort files by modification time in descending order
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

if (isset($_GET['search_date'])) {
    $searchDate = $_GET['search_date'];
    $files = array_filter($files, function($file) use ($searchDate) {
        return strpos($file, $searchDate) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Backups</title>
</head>
<body>
    <h1>Database Backups</h1>
    
    <form method="get">
        <label for="search_date">Search by Date:</label>
        <input type="text" id="search_date" name="search_date" placeholder="YYYY-MM-DD">
        <button type="submit">Search</button>
    </form>
    
    <table border="1">
        <thead>
            <tr>
                <th>Backup File</th>
                <th>Date</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($files)): ?>
                <?php foreach ($files as $file): ?>
                    <tr>
                        <td><?php echo basename($file); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', filemtime($file)); ?></td>
                        <td><a href="<?php echo $file; ?>" download>Download</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No backups found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
