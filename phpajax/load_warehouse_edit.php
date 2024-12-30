<?php
session_start();
include_once("../common/conn.php");
include_once("../rak_framework/fetch.php");
include_once("../rak_framework/fisfuncs.php");




function formatMySQLDate($mysqlDate) {
    // If $mysqlDate is null, use the current date
    if ($mysqlDate === null) {
        $timestamp = time();
    } else {
        // Convert MySQL date to Unix timestamp
        $timestamp = strtotime($mysqlDate);
    }

    // Format the Unix timestamp to 'DD/MM/YYYY'
    $formattedDate = date('d/m/Y', $timestamp);

    return $formattedDate;
}


//print_r($_REQUEST);die;

$pid = $_REQUEST['pid'];
$oid = $_REQUEST['oid'];
$currstock = $_REQUEST['currstock'];

$rid = $_REQUEST['revision'];
$order_detail_id =  $_REQUEST['order_detail_id'];
//$pid = 90;

//echo 'Edit '.$oid;
//soitemdetailid
//$qraay = array('socode'=>$oid,'productid'=>$pid);
//$sidi = fetchSingleDataByArray('soitemdetails',$qraay,'id');

//echo $sidi; die;
function getWarehousesByPID($pid){
	global $conn;
	
	$query ="SELECT 
			cs.storerome whid,
			cs.freeqty qty,
			br.name whname
			FROM `chalanstock` cs
			LEFT JOIN branch br ON cs.storerome = br.id 
			 WHERE product=$pid AND cs.freeqty>0 AND br.name <> 'GRS'";
	 
	 
 
	 $_SESSION['debug']['edit']['getWarehousesByPID_edit'] = $query;
	
	//echo $query; die;
	$result = $conn->query($query);
	
    if ($result->num_rows > 0) {
        $dbCols = 0;
        while($row = $result->fetch_assoc()) {
            $array_warehouse[$dbCols]["id"]=$row["whid"];
            $array_warehouse[$dbCols]["name"]=$row["whname"];
			$array_warehouse[$dbCols]["quantity"]=$row["qty"];
			
			$dbCols++;
        }
	}
	
	return $array_warehouse;
}

	
	
$whArray = getWarehousesByPID($pid);
$count = count($whArray);

?>
<span class="arrow-up"></span>

<?php
if($count>0){
    



foreach($whArray as $wh)
{
    
        $whname = $wh['name'];
        $whid = $wh['id'];
        
	 
        $fetchValues = array('socode' => $oid,'pid' => $pid,'warehouse'=>$whid);
        $tablename = ($_SESSION['pagestate'] == 'quotation')?'quotation_warehouse':'quotation_revisions_warehouse';
        $debug=1;
        $curQty = 0;
        
        $curQty = fetchSingleDataByArray($tablename,$fetchValues,'qty');
        //$_SESSION['curQty'][] = $curQty;
        $deliveryDate = fetchSingleDataByArray($tablename,$fetchValues,'expted_deliverey_date');
        //echo $_SESSION['pagestate'];
       //$whquantity = ();
       //echo ">:". $tablename;
       
	    // 5:Main Branch, 6:GRS, 10:Repair, 11:Customer Allocated
	     if($whid==5||$whid==6|| $whid==10 || $whid==11){ 
                $isDisabled = 'disabled';
            }else{
                $isDisabled = '';
            }
	?>
    
			<div class="row border">
                    
                        <div class="col-xs-4 text-right"><div class="whname"><?=$wh['name']?> (<?=$wh['quantity']?>)</div></div>
                        <div class="col-xs-4">
                              <div class="input-group plusminuswrap">
                                <span class="input-group-btn">
                                    <button type="button" <?=$isDisabled?>  class="quantity-left-minus btn btn-info btn-number"  data-type="minus" data-field="">
                                      <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
								<input type="hidden" name="whid[<?=$pid?>][]" value="<?=$wh['id']?>">

                                <input data-stklimit="<?=$wh['quantity']?>" <?=$isDisabled?>  type="text" id="quantity_<?=$pid?>_<?=$wh['id']?>" name="whqty[<?=$pid?>][]" class="form-control input-number numonly quantity"  value="<?=($curQty>0)?$curQty:'0'?>" min="0" max="<?=$wh['quantity']?>" readonly>
                                <span class="input-group-btn">
                                    <button type="button" <?=$isDisabled?>  class="quantity-right-plus btn btn-info btn-number" data-type="plus" data-field="">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <input type="text" <?=$isDisabled?>  class="form-control datetimepicker-wh delivery-date<?=$isDisabled?>" value="<?=(!$isDisabled)?formatMySQLDate($deliveryDate):''?>"  name="delivery_date[<?=$pid?>][]" id="date_<?=$wh['name']?>_<?=$pid?>" placeholder="Delivery Date" r equired="">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>                            
                        </div>
	            </div>
 				

<?php
    $curQty = 0; //reset
        
	}
 }
?>
    <?php
        //echo count($whArray);
    if($currstock>0){
    ?>
        <div class="form-group text-right" style="margin-bottom: 0px">
            
              <ul class="icheck-ul" style="width:100%">
                <li>
                  <input tabindex="1" type="checkbox" class="samedate" id="samedate<?=$pid ?>"> 
                  <label for="samedate<?=$pid ?>" style="font-weight: normal;font-size: 12px"> Same Date for all</span></label>
                </li>
              </ul>


        </div>
<?php
    }else{
?>
<strong style="white-space:nowrap;">No stock available</strong>
<?php
}
?>


