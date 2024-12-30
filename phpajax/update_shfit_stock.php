<?php
require "../common/conn.php";
session_start();
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
include_once('../rak_framework/connection.php');

//error_reporting(E_ALL);
//ini_set('display_errors', 1);


//print_r($_REQUEST);

extract($_REQUEST);
//echo $store;
//die;
$prd = fetchByID('item','barcode',$barcode,'id');

$usr = $_SESSION["user"];
$msg="";
$action = $store;
    if($action == "back")
    {
        $futurestock="";
        if($boqty==0){$futurestock="  ,forstock=2";}
        $qryUpdate = "UPDATE `item` SET `backorderqty`=backorderqty $futurestock WHERE barcode = ".$barcode;
        if($conn->query($qryUpdate) == true)
        {
            $msg = "Successfully item Shifted";
        }
        else
        {
            $msg = "Something went wrong";
        }
        
        $qryUpdatestock = "update stock set backorderqty=backorderqty-$foqty,futureqty=futureqty+$foqty where product= $prd";
        if($conn->query($qryUpdatestock) == true)
        {
            $msg = "Successfully stock shifted!";
        }
        else
        {
            $msg = "Something went wrong";
        }
        
        $qryisstock="select  freeqty from chalanstock where product=$prd and storerome=7 ";
        $isstockresult = $conn->query($qryisstock); 
        //echo $isstockresult;
            if ($isstockresult->num_rows == 0)
            { 
            $insertstockchalan = " INSERT INTO `chalanstock`( `product`, `freeqty`, `orderedqty`, `grsqcqty`, `costprice`, `prevprice`, `barcode`, `expirydt`, `storerome`) 
select `product`, $foqty, `orderedqty`, `grsqcqty`, `costprice`, `prevprice`, `barcode`, `expirydt`, 7 from chalanstock
where storerome=8 and product=$prd";
            }
            else 
            {
                $insertstockchalan="update  chalanstock set freeqty=freeqty+$foqty where  storerome=7 and product=$prd";
            }
                if($conn->query($insertstockchalan) == true)
                {
                    $msg = "Successfully Shifted to Future";
                }
                else
                {
                    $msg = "Something went wrong";
                }
                
                $updatestockchalan="update  chalanstock set freeqty=freeqty-$foqty where  storerome=8 and product=$prd";
                if($conn->query($updatestockchalan) == true)
                {
                    $msg = "Successfully Shifted from Backorder";
                }
                else
                {
                    $msg = "Something went wrong";
                }
        
    }
    else
    {
        $msg = "Something went wrong";
    }
    echo $msg;die;
   // header("Location: ".$hostpath."/backorder_shift.php?res=1&msg=$msg&mod=24");


?>