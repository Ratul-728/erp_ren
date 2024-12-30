<?php

require "../common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{
  header("Location: ".$hostpath."/soitem.php");
}
else
{

	extract($_REQUEST);
 	$key = $_REQUEST['key'];
 if($key){
	 
	 //print options like following 
 ?>

<!--<option value="">Select POC</option> -->

<?php 
    //$qry1="SELECT  distinct `id`, `name`  FROM `contact`  WHERE `contacttype`=1 and organization = (SELECT `orgcode` FROM `organization` where `id`=$key)"; 
    $qry1="SELECT  distinct h.`id`,concat(e.`firstname`,' ',e.`lastname`) `name` FROM hr h,employee e,organization o where h.`emp_id`=e.`employeecode` and o.salesperson=h.id and o.id=$key order by emp_id"; 
	
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

<!--<option value="">Select POC</option> -->

<?php 
    $qry1="SELECT  distinct h.`id`,concat(e.`firstname`,' ',e.`lastname`) `name` FROM hr h,employee e,organization o where h.`emp_id`=e.`employeecode` and o.salesperson=h.id order by emp_id";  
	
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