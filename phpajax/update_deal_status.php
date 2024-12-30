<?php

session_start();

$act= $_GET['action'];

extract($_REQUEST);

require_once("../common/conn.php");
if ($act=='changedealstatus' )
{
//echo $act;die;
    $sql = 'UPDATE deal SET status='.$statusid.' WHERE id='.$dataid;
    
    if ($conn->query($sql) == TRUE) {
        //echo $sql;
        $sql1='update rpt_sales_deal set st=(select name from dealstatus where id='.$statusid.') where deal_id='.$dataid;
        if ($conn->query($sql1) == TRUE) {  echo "Deal status updated successfully"; }
        //	echo $act;
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $conn->close();

}
else if ($act=='changedealstage' )
{
    $qryval = "SELECT a.lead, b.name FROM `deal` a, dealtype b where a.`stage` = b.id and a.id = ".$dataid;
    $resultval = $conn->query($qryval);
    while($rowval = $resultval->fetch_assoc()){
        $contactid = $rowval["lead"];
        $beforeval = $rowval["name"];
    }
    
    $qrystage = "SELECT name FROM dealtype where id = ".$stageid;
    $resultstage = $conn->query($qrystage);
    while($rowstage = $resultstage->fetch_assoc()){
        $afterval = $rowstage["name"];
    }
    
    
    $sql = 'UPDATE deal SET stage='.$stageid.' WHERE id='.$dataid;
   // echo $sql;
    if ($conn->query($sql) == TRUE) {
        //echo $sql;
        $sql1='update rpt_sales_deal set stage=(select name from `dealtype`  where id='.$stageid.') where deal_id='.$dataid;
        if ($conn->query($sql1) == TRUE) {  echo "Deal stage updated successfully"; 
            
        }
        
        //Add comment into crm profile
        $note = "Deal stage updated from $beforeval to $afterval";
        $usr=$_SESSION["user"];
        $qrycmn = "INSERT INTO `comncdetails`(`comntp`, `contactid`, `comndt`, `note`, `status`, `value`, `makeby`, `makedt`) VALUES (8,".$contactid.",sysdate(),'".$note."',3,0.0,".$usr.",sysdate())";
    	$conn->query($qrycmn);
    	die;
    } else {
        //echo "Error updating record: " . $conn->error;
        echo $sql;
    }
    
    $conn->close();

}
else
{
    

}
	
?>