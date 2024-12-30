<?php
    require "../common/conn.php";
 
    require "../rak_framework/fetch.php";
    require "../rak_framework/edit.php";
    require "../rak_framework/misfuncs.php";
   
    
    
    $orderStatus = $_REQUEST['orderStatus'];
    $socode = $_REQUEST['socode'];
    
    $deliverydate = $_REQUEST["deliverydate"];
    
    $dateTime = DateTime::createFromFormat('d/m/Y', $deliverydate);
    $mysqlFormattedDate = $dateTime->format('Y-m-d'). '  00:00:00';
        
    $condition = "do_id = '$socode'";
    updateByID('delivery_order','do_date','"'.$mysqlFormattedDate.'"',$condition);
    
    $response = [
        'msg' => 'Delivery date successfully updated!',
        'date' => $deliverydate
    ];
        
    
?>