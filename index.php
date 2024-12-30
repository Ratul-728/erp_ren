<?php
    require "common/conn.php";
	

	
    session_start();
    $usr=$_SESSION["user"];
    if($usr){ 
		header("Location: ".$hostpath."/hrqv.php"); 
    }else{
		header("Location: ".$hostpath."/hr.php"); 
	}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="refresh" content="0;url=hr.php" />
<meta charset="utf-8">
<link rel="icon" href="images/favicon.png">
<title>BitFlow v1</title>
</head>

<body>
</body>
</html>
