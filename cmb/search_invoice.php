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
 	$invoice = $_REQUEST["invoice"];
 	//echo $invoice;
 if($key){
	 
	 //print options like following 
 ?>

<option data-value="" value="" >Select Invoice</option>

<?php 
    $qry1="SELECT `id`, `invoiceNo` FROM `invoice` WHERE organization = ".$key;  
    //$qry="SELECT `id`, `name`  FROM `contact`  WHERE `contacttype` in (1,3) and organization ='$key'";  
    //echo $qry1;die;
	
	$result1 = $conn->query($qry1);   
	if ($result1->num_rows > 0) { 
		while($row1 = $result1->fetch_assoc()){ 
		
         	$tid= $row1["id"];  $nm=$row1["invoiceNo"];
         	 
			 
    ?>          
            <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
    <?php 
		}
	}
	?> 

<?php
 }else{
//echo ' <option value="">Select Name</option>';
     ?>

<option value="">Select Invoice</option>

<?php 
    $qry1="SELECT `id`, `invoiceNo` FROM `invoice` order by invoiceNo ASC";  
	
	$result1 = $conn->query($qry1);   
	if ($result1->num_rows > 0) { 
		while($row1 = $result1->fetch_assoc()){ 
		
         	$tid= $row1["id"];  $nm=$row1["invoiceNo"];
         	
			
    ?>          
            <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
    <?php 
		}
	}
	?> 

<?php
	 }
 
}
?>