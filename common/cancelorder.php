<?php
session_start();
if(!$_SESSION["user"]){
    header("Location: " . $hostpath . "/hr.php");
} else {
	
	
	
	
$st= $_GET['st'];
$returl=$_GET['ret'];
$md=$_GET['mod'];
//echo "yes"; die;
//$msg= $_GET['msg'];
$atid= $_GET['id'];
$soid= $_GET['so'];
$hrid = 1;//$_POST['usrid'];
require_once("conn.php");

//print_r($_REQUEST);die;

	//$sql = 'delete from '.$obj.' WHERE id='.$atid;
	//$ordqry="update soitem set orderstatus=6  where id=".$atid;
    //$result = $conn->query($cusqry); 
     
    if($st=="1")
	{
	   $qryitm = "delete `soitemdetails`  where socode= '". $soid . "'";
        $resultitm = $conn->query($qryitm);
		
        $sql = "delete `soitem`  where socode= '". $soid . "'";
	}   
	
	if($st=="9")
	{
	   $qryitm = "SELECT a.`id`, a.`productid`, round(a.`qty`,0) qty 
                        FROM `soitemdetails` a left join soitem b on a.socode=b.socode and b.id'" . $atid . "'";
        $resultitm = $conn->query($qryitm);
		
		if ($resultitm->num_rows > 0) {
			
			while ($rowitmdt = $resultitm->fetch_assoc()) {
       // echo $itmdtqry;die;";
	        $updstock="update stock set bookqty=bookqty-".$rowitmdt["qty"].",freeqty=freeqty+ ".$rowitmdt["qty"]." where product=".$rowitmdt["productid"];  
	        $resultstock = $conn->query($updstock);
			}
		    
		}
		$sql = "update soitem set orderstatus=6  where id=".$atid;
	}
	if($st=="2"||$st=="3"||$st=="4"||$st=="11")
	{
	   $qryitm = "SELECT a.`id`,a.socode, a.`productid`, round(a.`qty`,0) qty 
                        FROM `soitemdetails` a left join soitem b on a.socode=b.socode and b.id'" . $atid . "'";
        $resultitm = $conn->query($qryitm);
		
		if ($resultitm->num_rows > 0) {
			
			while ($rowitmdt = $resultitm->fetch_assoc()) {
       // echo $itmdtqry;die;";
	        $updstock="update stock set orderedqty=orderedqty-".$rowitmdt["qty"].",freeqty=freeqty+ ".$rowitmdt["qty"]." where product=".$rowitmdt["productid"];  
	         $resultstock = $conn->query($updstock);
			}
		}
		$updinvoice="update invoice set invoiceSt=4 where soid=".$rowitmdt["socode"];  
	         $resultinv = $conn->query($updinvoice);
	         
	   $sql = "update soitem set orderstatus=6  where id=".$atid;      
	}
	
	
   // echo $sql;die;
    if ($conn->query($sql) == TRUE) {
		
		//delete picture if found
		
        $orgid = $_GET["orgid"];
        $retid= $_GET['retid'];
        if($retid != ''){
            header("Location: ".$hostpath."/".$returl.".php?id=".$retid."&res=4&mod=".$md);
            die;
        }
        if($orgid == ''){
            $msg = "Successfully Deleted";
            header("Location: ".$hostpath."/".$returl.".php?res=1&msg=$msg&mod=".$md);
        }else{
            $msg = "Successfully Deleted";
            header("Location: ".$hostpath."/".$returl.".php?id=".$orgid."&res=1&msg=$msg&mod=".$md);
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $conn->close();


}//if(!$_SESSION["user"]){
?>