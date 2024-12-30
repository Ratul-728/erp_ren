<?php
require "conn.php";
session_start();

//print_r($_FILES['input-ficons-1']['name']);die;

$empid = $_REQUEST["empid"];
$makeby = $_SESSION["user"];
$ftype = $_REQUEST["doc-up-mod"];
$error = 0;

$date = date(His);
    
$count = count($_FILES['input-ficons-1']['name']);

if($count > 0){
    for($i = 0; $i < $count; $i++){
        $date = date('Ymd');
        $filename = $date.$_FILES['input-ficons-1']['name'][$i];
        //$filename .= $date;
        // destination of the file on the server
        $destination = '../images/upload/documents/'.$filename;
        // get the file extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $file = $_FILES['input-ficons-1']['tmp_name'][$i];
        
        if (move_uploaded_file($file, $destination)){
            $qry = "INSERT INTO `documents`(`empid`, `filename`, `ftype`, `makeby`, `makedt`) VALUES (".$empid.",'".$filename."', ".$ftype.", ".$makeby.", sysdate())";
            if ($conn->query($qry) == TRUE){
                //header("Location: ".$hostpath."/employee_hr.php?res=4&&mod=4&id=".$empid);
            }else{
                $error++;
            }
        }else{
            echo "File does not upload !!!";
        }
    }
}else{
    echo "You didn't upload anything";
}

if($error == 0){
    header("Location: ".$hostpath."/employee_hr.php?res=4&&mod=4&id=".$empid);
}else{
    echo "Something went wrong";
}
    
    
/* the physical file on a temporary uploads directory on the server
$file = $_FILES['myfile']['tmp_name'];
$size = $_FILES['myfile']['size'];

if (!in_array($extension, ['zip', 'pdf', 'docx'])) {
    echo "You file extension must be .zip, .pdf or .docx";
} elseif ($_FILES['myfile']['size'] > 1000000) { // file shouldn't be larger than 1Megabyte
    echo "File too large!";
} else {
    // move the uploaded (temporary) file to the specified destination
    if (move_uploaded_file($file, $destination)) {
        $sql = "INSERT INTO files (name, size, downloads) VALUES ('$filename', $size, 0)";
        if (mysqli_query($conn, $sql)) {
            echo "File uploaded successfully";
        }
    } else {
        echo "Failed to upload file.";
    }
} */


?>