<?php
  require_once("../common/conn.php");
  require_once("../rak_framework/insert.php");
 //   print_r($_REQUEST); 
    extract($_REQUEST);
    $txtreason=str_ireplace("'","`",$reason);
    $qry="insert into approval_item_price_change( `product`, `existingrate`, `newrate`, `reason`, `makeby`, `makedt`) 
        values($prodid,$currentRate,$newRate,'$txtreason', $usrId,sysdate())" ;
    //echo  $qry; die;   
        $conn->query($qry);
        $err="Request submitted successfully";
        echo $err;

?>