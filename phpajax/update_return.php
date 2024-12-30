<?php
require_once("../common/conn.php");
session_start();
$usr = $_SESSION["user"];
$act= $_GET['action'];
extract($_REQUEST);
//echo($tid);die;

$orderid=$oid;//'AS-OID-5FD87737';
$orderdetailsid=$tid;//'372';
$qty=$ret_qty;
$str=$store;
$prod=$pid;
$mrpc=$rate;
$up=$cost;
$barcode=$bc;
   // echo ("hello");die;
   
$updstock="update stock set freeqty=freeqty+$qty,deliveredqty=deliveredqty-$qty where product=".$prod;    
if ($conn->query($updstock) == TRUE) { echo "Stock updated successfully."; } 


$strqry="SELECT  id cnt from  chalanstock  where product=".$prod." and storerome='".$str."'";  
//echo $strqry;die;
$resultstr = $conn->query($strqry);
if ($resultstr->num_rows > 0) 
{
 $updchalanstock="update chalanstock set freeqty=freeqty + $qty where product=$prod and storerome=$str";  
 if ($conn->query($updchalanstock) == TRUE) { echo "Stock updated successfully."; }  
}
else
{
  $updchalanstock="CALL psp_stock('".$prod."','".$qty."','".$up."','I','".$mrpc."','".$barcode."',DATE_FORMAT( sysdate(),'%e/%c/%Y'),'".$str."')"; 
 // echo $updchalanstock;die;
 if ($conn->query($updchalanstock) == TRUE) { echo "Stock updated successfully."; }    
}
  
  
$upddelivery="update soitemdetails set return_qty=return_qty + ".$qty." where socode='".$orderid."' and sosl=".$sosl;    
//echo $upddelivery;
 if ($conn->query($upddelivery) == TRUE) { echo "Stock updated successfully."; }  
 
$returnqry="insert into order_returns ( `socode`, `sosl`, `productid`, `vat`, `ait`, `vatrate`, `aitrate`, `mu`, `qty`, `qtymrc`, `otc`, `mrc`, `currency`, `remarks`, `barcode`, `storeroome`, `cost`, `discountrate`, `discounttot`, `deliveredqty`, `dueqty`, `return_qty`, `return_store`, `makeby`, `makedt`) 
select `socode`, `sosl`, `productid`, `vat`, `ait`, `vatrate`, `aitrate`, `mu`, `qty`, `qtymrc`, `otc`, `mrc`, `currency`, `remarks`, `barcode`, `storeroome`, `cost`, `discountrate`, `discounttot`, `deliveredqty`, `dueqty`,$qty,$str, $usr, sysdate() FROM soitemdetails where id=".$orderdetailsid;
//echo $returnqry;
if ($conn->query($returnqry) == TRUE) {
    $note ="SO: $orderid is returned successfully";
    $qryhis= "SELECT organization FROM `soitem` WHERE socode = '".$orderid."'";
		 $resulthis = $conn->query($qryhis); 
         while($row = $resulthis->fetch_assoc()){
             $org = $row["organization"];
         }
         $hrid = $_SESSION["user"];
		 $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$org."',5,sysdate(),'".$note."','',0,'','".$hrid."',sysdate())" ;
        $conn->query($qry_othr);
    echo "Order return  successfully."; 
    
}
    $conn->close();


?>