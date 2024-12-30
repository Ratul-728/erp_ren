<?php
// Database connection details
    require "../common/conn.php";
     
    
    $servername = $server_name;
    $username = $mysql_username;
    $password = $mysql_password;
    $dbname = $db_name;
    
     

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the current maintenance mode status and message
$sql = "SELECT is_shutdown, maintenance_message FROM shutdown WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $is_shutdown = $row['is_shutdown'];
    $maintenance_message = $row['maintenance_message'];
} else { 
    $is_shutdown = '0';
    $maintenance_message = "";
}

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turn Maintenance Mode On</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #2196F3;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
        textarea {
             width: calc(100% - 20px);
            height: 100px;
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: none;
        }
        .submit-btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #2196F3;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #0d8bf2;
        }
        footer {
            margin-top: auto;
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #777;
        }
        
        .centered-div {
            position: absolute;
            top: 15%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            color: white;
            text-align: center;
        }
        
        label{
    line-height: 2;
}
    </style>
</head>
<body>

<div class="container">
        <?php
    if($_REQUEST['success'] == 1){
        echo '<div class="centered-div"><strong style="color:green">Maintenance mode updated successfully.</strong></div>';
    } 
    ?>
    
    
    <h2>Manage Maintenance Mode</h2>

    <form action="maintenance_submit.php" method="post">
        <label for="maintenanceSwitch">Maintenance On:</label>
        <label class="switch">
            <input type="checkbox" id="maintenanceSwitch" name="maintenanceSwitch" value = "<?=$is_shutdown?>" <?php if($is_shutdown=='1') echo 'checked'; ?>>
            <span class="slider round"></span>
        </label>
        <input type="hidden" id="hiddenSwitchValue" name="maintenanceSwitchValue" value="0">
        
        <br><br>
        <label for="maintenanceMessage">Maintenance Message:</label>
        
        <textarea id="maintenanceMessage" name="maintenanceMessage" placeholder="Enter maintenance message..."><?php echo htmlspecialchars($maintenance_message); ?></textarea>

        <button type="submit" class="submit-btn">Submit</button>
    </form>
</div>

<footer>
    Powered by: Bithut Limited
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Set the hidden input based on initial state from the server
        var initialIsShutdown = <?php echo $is_shutdown ? 'true' : 'false'; ?>;
        $('#maintenanceSwitch').prop('checked', initialIsShutdown);
        $('#hiddenSwitchValue').val(initialIsShutdown ? '1' : '0');

        // Toggle the hidden input value when the checkbox is toggled
        $('#maintenanceSwitch').change(function() {
            if ($(this).is(':checked')) {
                $('#hiddenSwitchValue').val('1');
            } else {
                $('#hiddenSwitchValue').val('0');
            }
        });
    });
</script>
</body>
</html>
