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
//print_r($_REQUEST);die;
	
	extract($_REQUEST);
 	$key = $_REQUEST['key'];
 	$sodetid=$_REQUEST['soitmdetlsid'];
 	$st=$_REQUEST['ost'];
    if($sodetid)
    {
	    $qry1=" insert into soitemdetails_cancel values(`id`, `socode`, `sosl`, `productid`, `vat`, `ait`, `vatrate`, `aitrate`, `mu`, `qty`, `qtymrc`, `otc`, `mrc`, `currency`, `remarks`, `barcode`, `storeroome`, `cost`, `discountrate`, `discounttot`, `deliveredqty`, `dueqty`, `backorderedqty`, `return_qty`, `makeby`, `makedt`) SELECT  `id`, `socode`, `sosl`, `productid`, `vat`, `ait`, `vatrate`, `aitrate`, `mu`, `qty`, `qtymrc`, `otc`, `mrc`, `currency`, `remarks`, `barcode`, `storeroome`, `cost`, `discountrate`, `discounttot`, `deliveredqty`, `dueqty`, `backorderedqty`, `return_qty`, `makeby`, `makedt` from  soitemdetails where id=$sodetid order by emp_id"; 
	    $result1 = $conn->query($qry1);   
	}
 echo "Item Canceled Succesfully";
}
?>