<?php

require "../common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
//if($usr=='')
//{
//  header("Location: ".$hostpath."/hr.php");
//}
//else
//{
 $param= $_GET['key'];
 $qry=" select c.`id` ,c.`name` cat from `itmCat` c ";

//echo $qry;die;
    if ($conn->connect_error) {  echo "Connection failed: " . $conn->connect_error; }
    else
    {
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        {
        while($row = $result->fetch_assoc()) 
            { 
                $fcid=$row["id"];  $catnm=$row["cat"];
               
                echo "  Item Cat Id : ".$fcid;
                echo "  Item Cat Name : ".$catnm;
              
            }
        }
    }
//}
?>