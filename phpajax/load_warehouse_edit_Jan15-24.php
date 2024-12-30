<?php
session_start();
include_once("../common/conn.php");
include_once("../rak_framework/fetch.php");
include_once("../rak_framework/fisfuncs.php");

//print_r($_REQUEST);

$pid = $_REQUEST['pid'];
$oid = $_REQUEST['oid'];

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
    



foreach($whArray as $wh){
    
        $whname = $wh['name'];
        $whid = $wh['id'];
        
	 
        $fetchValues = array('socode' => $oid,'soitem_detail_id' => $order_detail_id,'warehouse'=>$whid);
        $tablename = ($_SESSION['pagestate'] == 'quotation')?'quotation_warehouse':'quotation_revisions_warehouse';
        $curQty = fetchSingleDataByArray($tablename,$fetchValues,'qty');
        //echo $_SESSION['pagestate'];
       //$whquantity = ();
      // echo ">:". $tablename;
       
	
	?>
    
			<div class="row border">
                    
                        <div class="col-xs-4 text-right"><div class="whname"><?=$wh['name']?> (<?=$wh['quantity']?>)</div></div>
                        <div class="col-xs-4">
                              <div class="input-group plusminuswrap">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-left-minus btn btn-info btn-number"  data-type="minus" data-field="">
                                      <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
								<input type="hidden" name="whid[<?=$pid?>][]" value="<?=$wh['id']?>">

                                <input type="text" id="quantity1" name="whqty[<?=$pid?>][]" class="form-control input-number numonly quantity" value="<?=($curQty)?$curQty:'0'?>" min="0" max="<?=$wh['quantity']?>">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-right-plus btn btn-info btn-number" data-type="plus" data-field="">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <input type="text" class="form-control datetimepicker-wh delivery-date"  name="delivery_date[<?=$pid?>][]" id="date_<?=$wh['name']?>_<?=$pid?>" placeholder="Delivery Date" r equired="">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>                            
                        </div>
	            </div>
 				

<?php
    $curQty = ""; //reset
	}
 }
?>
    <?php
        //echo count($whArray);
  //  if(count($whArray)>1){
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
   // }
?>




