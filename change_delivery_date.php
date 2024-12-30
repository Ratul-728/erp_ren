<?php


require "common/conn.php";
require "rak_framework/listgrabber.php";
require "rak_framework/fetch.php";
require "rak_framework/misfuncs.php";


session_start();
ini_set('display_errors',0);

$usr = $_SESSION["user"];

//print_r($_SESSION);

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {

    $socode = $_REQUEST['socode'];
    $orderStatus = fetchByID('quotation','socode',$socode,'orderstatus');
    //$table = ($orderStatus == 1)?'quotation_warehouse':'soitem_warehouse';
     $inputData = array(
     'TableName' => 'quotation_warehouse',
     'OrderBy' => 'expted_deliverey_date',
     'ASDSOrder' => 'DESC',
         
     'id' => '',
     'socode' => $socode,
     'soitem_detail_id' => '',
     'expted_deliverey_date' => '',
     'warehouse' => ''
      );
      //$debug = 1;
       listData($inputData,$itemArray);
       
       $gridArr = array();
      
      //dd($itemArray);
     

      
    //echo $orderStatus ;die;  
      
}
?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
<title>Transaction List</title>
<style>
   .slip-list-wrap table {
        
         border:1px solid #efefef;
    }
   .slip-list-wrap  table td, .slip-list-wrap table th{
        padding: 5px;
        
    }
    .slip-list-wrap > table > tr th{
        background-color: #efefef;
    }
    .slip-list-wrap table td{border-right:1px solid #efefef;}
    
.slip-list-wrap tr:nth-child(odd) {background: #efefef}
.slip-list-wrap tr:nth-child(even) {background: #FFF}
    
</style>

</head>

<body>
    <form id="dateChangeForm" >
    <?php
      echo '<div class="slip-list-wrap"><table class="table-stripe" border="0" width="100%">';
      echo '<th width="40%">Items</th>';
      echo '<th width="30%">Warehouse</th>';
      echo '<th width="30%">Delivery Date</th>';
      echo '<input type="hidden" name="orderStatus" value="'.$orderStatus.'">';
      echo '<input type="hidden" name="socode" value="'.$socode.'">';
      foreach($itemArray as $val){
          echo "<tr>";
          $itemid   = fetchByID('quotation_detail','id',$val['soitem_detail_id'],'productid');
          $itemname = fetchByID('item','id',$itemid,'name');
            echo '<td>'.$itemname.'</td>';
            $warehousename = fetchByID('branch','id',$val['warehouse'],'name');
            echo '<td>'.$warehousename.'</td>';
            echo '<td>
							<div class="input-group" style="margin-bottom:0">
							    <input type="hidden" name="warehouse[]" value="'.$val['warehouse'].'">
							    <input type="hidden" name="itemid[]" value="'.$itemid.'">
							    <input type="hidden" name="recordid[]" value="'.$val['id'].'">
    							<input type="text" value="'.formatDate2($val['expted_deliverey_date']).'" class="form-control datepicker-popup checkdate" placeholder="Check Date" id="checkdate" name="deliverydate[]" >
    							<div class="input-group-addon">
                                   <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>   
            </td>';
          echo "</tr>";
      }
      echo "</table></div>";    
    ?>
</form>
    <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
       
    <script>
    
</script>
 
    
    
</body>
    
</html>
