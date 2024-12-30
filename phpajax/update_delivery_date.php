<?php
    require "../common/conn.php";
 
    require "../rak_framework/fetch.php";
    require "../rak_framework/edit.php";
    require "../rak_framework/misfuncs.php";
   
   
   function getOldestDateFromArray($dateArray){
        $today = new DateTime();
        
        // Function to convert each date in the array to DateTime object
        function convertToDateTime($date)
        {
            return DateTime::createFromFormat("d/m/Y", $date);
        }
        
        // Use array_map to convert the entire date array to DateTime objects
        $dateObjects = array_map("convertToDateTime", $dateArray);
        
        // Find the oldest date
        $oldestDate = min($dateObjects);
        
        // Output the result
        return  $oldestDate->format("d/m/Y");       
   }
    
    $debug = 0;
    //print_r($_REQUEST);die;
    
    //convert array:
    
    $outputArray = array();

    // Assuming both 'recordid' and 'deliverydate' have the same number of elements
    $count = count($_REQUEST['recordid']);
    
    for ($i = 0; $i < $count; $i++) {
        $outputArray[] = array(
            'warehouse' => $_REQUEST['warehouse'][$i],
            'itemid' => $_REQUEST['itemid'][$i],
            'recordid' => $_REQUEST['recordid'][$i],
            'deliverydate' => $_REQUEST['deliverydate'][$i]
        );
    }
    
    //print_r($outputArray); die;
    $orderStatus = $_REQUEST['orderStatus'];
    $socode = $_REQUEST['socode'];
    
    
  
    if(count($outputArray) > 0){
        
        foreach($outputArray as $val)
        {
            // Format the DateTime object to MySQL format
            $dateArray[] = $val['deliverydate'];
            $dateTime = DateTime::createFromFormat('d/m/Y', $val['deliverydate']);
            $mysqlFormattedDate = $dateTime->format('Y-m-d'). '  00:00:00';
            
            $condition = 'id = '.$val['recordid'];
            updateByID('quotation_warehouse','expted_deliverey_date','"'.$mysqlFormattedDate.'"',$condition);
            
            if($orderStatus != 1){
                $condition = 'socode = "'.$socode.'" AND warehouse='. $val['warehouse'];
                updateByID('soitem_warehouse','expted_deliverey_date','"'.$mysqlFormattedDate.'"',$condition);  
            }
        }
        //echo 'Delivery date successfully updated!'. getOldestDateFromArray($dateArray);
        $response = [
            'msg' => 'Delivery date successfully updated!',
            'date' => getOldestDateFromArray($dateArray)
        ];
        
        header('Content-Type: application/json');
        // Send the JSON-encoded response
        echo json_encode($response);
                
    }else{
        echo 'Error: No value returned';
    }
    
?>