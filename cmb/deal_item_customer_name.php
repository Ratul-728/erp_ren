<?php

require "../common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{
  header("Location: ".$hostpath."/hr.php");
}
else
{

	extract($_REQUEST);
 	$key = $_REQUEST['key'];
 if($key){
	 
	 //print options like following 
 ?>

<option value="">Select Name</option>

<?php 
    $qry1="SELECT `id`, `name`  FROM `contact`  WHERE `contacttype` in (1,3) and organization = (select orgcode from organization where id='$key')";  
    //$qry="SELECT `id`, `name`  FROM `contact`  WHERE `contacttype` in (1,3) and organization ='$key'";  
    //echo $qry1;die;
	
	$result1 = $conn->query($qry1);   
	if ($result1->num_rows > 0) { 
		while($row1 = $result1->fetch_assoc()){ 
		
         	$tid= $row1["id"];  $nm=$row1["name"];
         	 
			 
    ?>          
            <option value="<?php echo $tid; ?>" <?php if ($cusid == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php 
		}
	}
	?> 

<?php
 }else{
//echo ' <option value="">Select Name</option>';
     ?>

<option value="">Select Name</option>

<?php 
    $qry1="SELECT `id`, `name`  FROM `contact`  WHERE `contacttype` in(1,3)";  
	
	$result1 = $conn->query($qry1);   
	if ($result1->num_rows > 0) { 
		while($row1 = $result1->fetch_assoc()){ 
		
         	$tid= $row1["id"];  $nm=$row1["name"];
         	
			
    ?>          
            <option value="<?php echo $tid; ?>" <?php if ($cusid == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php 
		}
	}
	?> 

<?php
	 }
 
}
?>