<?php
session_start();
require "common/conn.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);



$qry="select `orderdate`,`productid`,`warehouse`,`qty`,tp st  from soldstock order by orderdate"; 
$result = $conn->query($qry);
if ($result->num_rows > 0) 
{ 
    while ($row = $result->fetch_assoc())  
    { 
        $ordt = $row["orderdate"];
        $prod = $row["productid"]; 
        $store = $row["warehouse"];
        $qty = $row["qty"];
        $st = $row["st"];
        $stqty=0;$stcost=0;$stmrp=0;$stexpdt ='';
        $gethist="select h.`freeqty`,h.orderedqty,h.grsqcqty,h.`costprice`,h.`mrp`,h.`expirydt`,h.store from stockhist h where h.product=$prod and h.store=$store and h.stockdate=(select max(stockdate) from stockhist where product=h.product and store=h.store and stockdate<='$ordt')";
       // echo $gethist;die;
        $resulthist=$conn->query($gethist);
        if ($resulthist->num_rows > 0) 
            {
                while ($rowhist = $resulthist->fetch_assoc()) 
                {
                    $stqty=$rowhist["freeqty"];
                    $ordqty=$rowhist["orderedqty"];
                    $grsqty=$rowhist["grsqcqty"];
                    $stcost=$rowhist["costprice"];
                    $stmrp=$rowhist["mrp"];
                    $stexpdt=$rowhist["expirydt"];
                }
            }
      
        if($st=='cancel')
        {
           $freeqty=$stqty+$qty;  $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt; 
        }
        else if($st=='return')
        {
            $freeqty=$stqty;  $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt; $grsqty=$grsqty+$qty;
        }
        else
        {
            $freeqty=$stqty-$qty; if($freeqty<0){$freeqty=0;} $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt;$ordqty=$ordqty+$qty;
        }
        // echo $ordt.'-'.$prod.'-'.$store.'-'.$qty; 
         $histqry="insert into stockhist ( `product`,`freeqty`,`orderedqty`,`grsqcqty`,`costprice`,`mrp`,`expirydt`,`stockdate`,`store`) 
         values($prod,$freeqty,$ordqty,$grsqty,$cost,$mrp,'$expdt','$ordt',$store)";
       // echo $histqry;
         if ($conn->query($histqry) == TRUE) { $err="organization balance updated successfully";  }
         
    }
    
}
echo "success";
 
?>