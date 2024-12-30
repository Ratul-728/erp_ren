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
 $qry="select i.`id`,i.`code`,i.`name` itm,i.`image`,i.`catagory` ,c.`name` cat,s.qty,s.rate
from `item` i left join `itmCat` c on i.`catagory`=c.`id`
	left join rawstock s on i.`id`=s.`itemid`
where i.`catagory` = '".$param."' or  c.`name` like '%".$param."%'  or i.`name` like '%".$param."%'  or '".$param."'=''";

//echo $qry;die;
    if ($conn->connect_error) {  echo "Connection failed: " . $conn->connect_error; }
    else
    {
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        {
        while($row = $result->fetch_assoc()) 
            { 
                $fcid=$row["id"];$itmnm=$row["itm"]; $catid=$row["catagory"];  $catnm=$row["cat"];$stk=$row["qty"];  $price=$row["rate"];
               $photo="common/upload/item/".$row["code"].".jpg";
                echo "  Item Id : ".$fcid;
                echo "  Item Name : ".$itmnm;
                echo "  Item Cat Id : ".$catid;
                echo "  Item Cat Name : ".$catnm;
                echo "  Stock : ".$stk;
                echo "  Price : ".$price;
            }
        }
    }
//}
?>