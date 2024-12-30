<?php

session_start();
require "../common/conn.php";
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/edit.php');

$usr=$_SESSION["user"];
//print_r($_POST);die;
extract($_POST);

$qryDef="SELECT pass_qty FROM qa_warehouse WHERE id = ".$qawId;
$resultDef = $conn->query($qryDef);
while ($rowDef = $resultDef->fetch_assoc())
{
    if($rowDef["pass_qty"]== null)
    {
        $rowDef["pass_qty"] = 0;
    }
    $defQty = $pass_qtys-$rowDef["pass_qty"];
}
//echo $defQty;die;
$qryUpdateQaw = "UPDATE `qa_warehouse` SET `pass_qty`='".$pass_qtys."',`defect_qty`='".$defacts_qtys."',`damaged_qty`='".$damaged_qtys."',`inspector`='".$usr."',
                `date_inspected`=sysdate() WHERE id = ".$qawId;
// echo $qryUpdateQaw;die;
$conn->query($qryUpdateQaw);

$qryUpdateQa = "UPDATE `qa` SET `status`='2', `remarks`='".$remarks."', date_iniciated = '".date('Y-m-d H:i:s')."' WHERE id = ".$qaId;
$conn->query($qryUpdateQa);

//Check all completed or not 
$flag = true;

$qryQa = "SELECT
    CASE
        WHEN qw.ordered_qty = COALESCE(SUM(qw.pass_qty), 0) + COALESCE(SUM(qw.damaged_qty), 0) + COALESCE(SUM(qw.defect_qty), 0) THEN '1'
        ELSE '0'
    END AS qcheck
FROM
    qa_warehouse qw JOIN qa q ON qw.qa_id = q.id WHERE	q.order_id='".$orderId."' GROUP BY q.id, q.order_id, qw.ordered_qty";
$resultQa = $conn->query($qryQa);
while ($rowQa = $resultQa->fetch_assoc()) {
    if($rowQa["qcheck"] == '0'){
        $flag = false;
        break;
    }
}

if($flag){
    $qryUpdatePo = "UPDATE `qa` SET `status`='3' WHERE `order_id`= '".$orderId."'";
    $conn->query($qryUpdatePo);
}
//echo   $type;die;                          
//Purchase
if($type == 2)
{
   
    $qryUpdatePo = "UPDATE `purchase_landing` SET `st`='2' WHERE poid = '".$orderId."'";
    $conn->query($qryUpdatePo);
    
    if($pass_qtys > 0)
    {
        //Get info
        $qty=$defQty;
        $qryInfo="SELECT qa.product_id, pl.branch,i.barcode, pli.tot_value FROM `qa_warehouse` qaw LEFT JOIN qa qa ON qa.id=qaw.qa_id 
                LEFT JOIN purchase_landing pl ON qa.order_id=pl.poid LEFT JOIN purchase_landing_item pli ON pli.pu_id=pl.id LEFT JOIN item i ON qa.product_id=i.id 
                WHERE pli.productId=qa.product_id AND qaw.id=".$qawId;
        $resultInfo = $conn->query($qryInfo);
        while($rowInfo = $resultInfo->fetch_assoc())
        {
            $itmmnm=$rowInfo["product_id"];
            $storerm=$rowInfo["branch"];
            $up = $rowInfo["tot_value"];
            $barcode = $rowInfo["barcode"];
        }
        $isstock=0;
        //$isstock = fetchByID('stock','product',$itmmnm,'id');
        $isstockqry="SELECT count(*) cnt FROM stock where product= $itmmnm ";
        //echo "n";die;
        $resstore = $conn->query($isstockqry);
        while($rowstore = $resstore->fetch_assoc())
        {
            $isstock=$rowstore["cnt"];
        }
        // echo $isstock;die;
        
        //echo $isstock;die; 
        if($isstock==0)
        {
            
            $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES($itmmnm,$qty,$up,$barcode,$storerm)";
			 //echo $strQryChalanstock;die;
	        if ($conn->query($strQryChalanstock) == TRUE) {$err="$qty qtn added in chalanstock in main branch"; }
            
            $strQryStock ="INSERT INTO stock( `product`, `freeqty`, `bookqty`, `orderedqty`, `deliveredqty`, repairedqty, `costprice`, `prevprice`) VALUES($itmmnm,$qty,0,0,0,0,$up,0)";
	            if ($conn->query($strQryStock) == TRUE) { $err="$qty qtn added in stock";  }
           
        }
        else
        {
            $qrystore="SELECT count(*) cnt FROM chalanstock where product= $itmmnm and storerome=$storerm";
            $resstore = $conn->query($qrystore);
            while($rowstore = $resstore->fetch_assoc())
            {
                $isstore=$rowstore["cnt"];
            }
            if($isstore==0)
            {
                $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES($itmmnm,$qty,$up,$barcode,$storerm)";
		      // echo $strQryChalanstock;die;
		        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
            }
            else
            {
                $strQryChalanstock = "update chalanstock set freeqty=freeqty+$qty,costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) where product=$itmmnm and storerome=$storerm)";
		        // echo $strQryChalanstock;die;
		        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
            }
           
            $strQryStock = "update stock set `freeqty`=freeqty+$qty, costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) , `prevprice`=costprice where product= $itmmnm  ";
            if ($conn->query($strQryStock) == TRUE) { $err="$qty qtn added in stock";  }	
        }
        if($flag){
            $qryUpdatePo = "UPDATE `purchase_landing` SET `status`='3' WHERE `poid`= '".$orderId."'";
            $conn->query($qryUpdatePo);
        }
    }z
}

//For Return
else if($type == 3){
    if($pass_qtys > 0){
        
        //Get info
        $qty=$defQty;
        
        $qryInfo="SELECT qa.product_id, qaw.warehouse_id, i.rate, i.barcode FROM `qa_warehouse` qaw LEFT JOIN qa qa ON qa.id=qaw.qa_id LEFT JOIN item i ON qa.product_id=i.id 
                WHERE qaw.id=".$qawId;
        $resultInfo = $conn->query($qryInfo);
        while($rowInfo = $resultInfo->fetch_assoc())
        {
            $itmmnm=$rowInfo["product_id"];
            $storerm=$rowInfo["warehouse_id"];
            $up = $rowInfo["rate"];
            $barcode = $rowInfo["barcode"];
            
            
        }
        
                    $isstock=0;
                    $isstock = fetchByID('stock','product',$itmmnm,'id');
                    //echo $isstock;die; 
                     if($isstock==0)
                     {
                        $qrystore="SELECT count(*) cnt FROM chalanstock where product= $itmmnm and storerome=$storerm";
                        $resstore = $conn->query($qrystore);
                        while($rowstore = $resstore->fetch_assoc())
                        {
                            $isstore=$rowstore["cnt"];
                        }
                        if($isstore==0)
                        {
                            $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES($itmmnm,$qty,$up,$barcode,$storerm)";
					        //echo $strQryChalanstock;die;
					        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
                        }
                        else
                        {
                            $strQryChalanstock = "update chalanstock set freeqty=freeqty+$qty,costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) where product=$itmmnm and storerome=$storerm)";
					        echo $strQryChalanstock;die;
					        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
                        }
			            $strQryStock ="INSERT INTO stock( `product`, `freeqty`, `bookqty`, `orderedqty`, `deliveredqty`, repairedqty, `costprice`, `prevprice`) VALUES($itmmnm,$qty,0,0,0,0,$up,0)";
			            if ($conn->query($strQryStock) == TRUE) { $err="$qty qtn added in stock";  }	
                     }
                     else
                     {
                        $qrystore="SELECT count(*) cnt FROM chalanstock where product= $itmmnm and storerome=$storerm";
                        $resstore = $conn->query($qrystore);
                        while($rowstore = $resstore->fetch_assoc())
                        {
                            $isstore=$rowstore["cnt"];
                        }
                        if($isstore==0)
                        {
                            $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES($itmmnm,$qty,$up,$barcode,$storerm)";
					      // echo $strQryChalanstock;die;
					        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
                        }
                        else
                        {
                            $strQryChalanstock = "update chalanstock set freeqty=freeqty+$qty,costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) where product=$itmmnm and storerome=$storerm)";
					        // echo $strQryChalanstock;die;
					        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
                        }
                       
                       
                        $strQryStock = "update stock set `freeqty`=freeqty+$qty, costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) , `prevprice`=costprice ";
			            if ($conn->query($strQryStock) == TRUE) { $err="$qty qtn added in stock";  }	
                     }
    }
}

echo "Successfully Updated!";
?>