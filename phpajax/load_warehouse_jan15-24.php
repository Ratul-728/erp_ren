<?php

include_once("../common/mod_sale.php");

$pid = $_REQUEST['pid'];
//$pid = 90;

$whArray = getWarehousesByPID($pid);
$count = count($whArray);
?>
<span class="arrow-up"></span>

<?php
if($count>0){
    

foreach($whArray as $wh){
        $whname = $wh['name'];
        $whid = $wh['id'];
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
                                <input type="text" id="quantity1" name="whqty[<?=$pid?>][]" class="form-control input-number numonly quantity" value="0" min="0" max="<?=$wh['quantity']?>">
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
}
 }else{

    ?>
    
			<div class="row border">
                    
                        <div class="col-xs-4 text-right"><div class="whname">Backorder Quantity</div></div>
                        <div class="col-xs-4">
                              <div class="input-group plusminuswrap">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-left-minus btn btn-info btn-number"  data-type="minus" data-field="">
                                      <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
								<input type="hidden" name="whid[<?=$pid?>][]" value="backorder">
                                <input type="text" id="quantity1" name="whqty[<?=$pid?>][]" class="form-control input-number numonly quantity" value="0" min="0" max="<?=$wh['quantity']?>">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-right-plus btn btn-info btn-number" data-type="plus" data-field="">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <input type="text" class="form-control datetimepicker-wh delivery-date"  name="delivery_date[<?=$pid?>][]" id="date_<?=$wh['name']?>_<?=$pid?>" placeholder="Delivery Date" required="">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>                            
                        </div>
	            </div>

 <?php   
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




