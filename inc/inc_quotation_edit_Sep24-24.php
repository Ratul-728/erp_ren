
<style>
.qtycounter{
    z-index: 1000;
    position: absolute; 
    background: #fff;
    right:0;
}
</style>
<?php

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
        if($mysqlDate){
            return $formattedDate;
        }else{
            return '';
        }
    }
    
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
    
    
    function loadWarehouse($whParams){
        
        $pid = $whParams['pid'];
        $oid = $whParams['oid'];
        $currstock = $whParams['currstock'];
        $rid = $whParams['revision'];
        $order_detail_id =  $whParams['order_detail_id'];        
        echo '<div class="qtycounter" style="visibility:hidden">';
        
        $whArray = getWarehousesByPID($pid);
        $count = count($whArray);
        
        //echo $count;
        echo '<span class="arrow-up"></span>';
        
        
        
        
if($count>0){
    



foreach($whArray as $wh){
    
        $whname = $wh['name'];
        $whid = $wh['id'];
        
        
        if($_SESSION['pagestate'] == 'quotation'){
            $fetchValues = array('socode' => $oid,'pid' => $pid,'warehouse'=>$whid);    
        }else{
            $fetchValues = array('socode' => $oid,'pid' => $pid,'warehouse'=>$whid, 'revision_id'=>$rid);    
        }
	 
        
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
                                    <button type="button" class="quantity-left-minus btn btn-info btn-number" <?=$isDisabled?>  data-type="minus" data-field="">
                                      <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
								<input type="hidden" name="whid[<?=$pid?>][]" value="<?=$wh['id']?>">

                                <input data-stklimit="<?=$wh['quantity']?>" type="text" id="quantity_<?=$pid?>_<?=$wh['id']?>" name="whqty[<?=$pid?>][]" <?=$isDisabled?> class="form-control input-number numonly quantity"  value="<?=($curQty>0)?$curQty:'0'?>" min="0" max="<?=$wh['quantity']?>">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-right-plus btn btn-info btn-number" <?=$isDisabled?> data-type="plus" data-field="">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <input type="text" <?=$isDisabled?>  class="form-control datetimepicker-wh delivery-date<?=$isDisabled?>" value="<?=formatMySQLDate($deliveryDate)?>"  name="delivery_date[<?=$pid?>][]" id="date_<?=$wh['name']?>_<?=$pid?>" placeholder="Delivery Date" r equired="">
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

        
        
        
        
        echo '</div>';
    }
?>
     
                                                    <div class="row form-grid-bls hidden-md hidden-sm hidden-xs">
        											
        											
                                                        <div class="col-lg-3 col-md-5 col-sm-6">
                                                        	<h6 class="chalan-header mgl10"> Select Item<span class="redstar">*</span></h6>
                                                        </div>
        												<div class="col-lg-1 col-sm-1 col-xs-6">
        													<h6 class="chalan-header"> Price<span class="redstar">*</span></h6>
        												</div>
        												<div class="col-lg-1 col-sm-1 col-xs-6">
        													<h6 class="chalan-header"> Quantity<span class="redstar">*</span></h6>
        												</div>											
        
        
        
                                                        <div class="col-lg-1 col-md-1 col-sm-6">
                                                            <h6 class="chalan-header">Unit Total </h6>
                                                        </div>
                                                         <div class="col-lg-1 col-md-1 col-sm-6">
                                                            <h6 class="chalan-header">VAT %</h6>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-sm-6">
                                                            <h6 class="chalan-header">Including VAT</h6>
                                                        </div>
                                                         <div class="col-lg-1 col-md-1 col-sm-6">
                                                            <h6 class="chalan-header">Discount Rate %</h6>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-sm-6">
                                                            <h6 class="chalan-header">Discount Taka</h6>
                                                        </div>
                                                       
                                                        <div class="col-lg-2 col-md-2 col-sm-6">
                                                            <h6 class="chalan-header">Discounted Total </h6>
                                                        </div>
                                                    </div>											
        											<div class="clonewrapper">
                                                    <?php
                                                        
                                                        $rCountLoop  = 0;
                                                        $itdgt       = 0;
                                                        $totalcost=0;$netamount=0;
                                                        
                                                        if($_REQUEST['action']=='restore'){
                                                            $soid = $_REQUEST['socode'];
                                                            $rid = $_REQUEST['rid'];
                                                        $itmdtqry    = "SELECT a.`id`, a.`socode`, a.`sosl`, a.`productid`, a.`mu`, round(a.`qty`,0) qty,round(a.`qtymrc`,0)qtymrc, round(a.`otc`,2) otc, round(a.`mrc`,2)mrc,
                                                                        a.`remarks`, a.`makeby`, a.`makedt`,a.`currency`,a.vatrate vat,a.aitrate ait, b.name itmname,b.barcode,COALESCE(s.freeqty,0)freeqty,a.discountrate,a.discounttot,a.discount_amount 
                                                                        
                                                                        FROM `quotation_revisions_detail` a 
                                                                        LEFT JOIN item b ON a.`productid` = b.id 
                                                                        LEFT JOIN stock s ON a.productid = s.product
                                                                        WHERE `socode`='" . $soid . "' AND revision_id=".$rid. " 
                                                                        
                                                                         GROUP BY a.`id`, a.`socode`, a.`sosl`, a.`productid`, a.`mu`, 
                                                                         a.`qty`, a.`qtymrc`, a.`otc`, a.`mrc`, a.`remarks`, 
                                                                         a.`makeby`, a.`makedt`, a.`currency`, a.vatrate, a.aitrate, 
                                                                         b.name, b.barcode, a.discountrate, a.discounttot
                                                                        ORDER BY a.sosl ASC";
                                                                        
                                                                        
                                                                        //ORDER BY a.sosl ASC";
                                                            
                                                            //echo $itmdtqry;die;
                                                            $_SESSION['debug']['mode_'.$mode]['quotation_revisions_detail_itemq_uery_1'] = $itmdtqry;
                                                        }else{
                                                        $itmdtqry    = "SELECT a.`id`, a.`socode`, a.`sosl`, a.`productid`, a.`mu`, round(a.`qty`,0) qty,round(a.`qtymrc`,0)qtymrc, round(a.`otc`,2) otc, round(a.`mrc`,2)mrc,
                                                                        a.`remarks`, a.`makeby`, a.`makedt`,a.`currency`,a.vatrate vat,a.aitrate ait, b.name itmname,b.barcode,COALESCE(s.freeqty,0)freeqty,a.discountrate,a.discounttot,a.discount_amount 
                                                                        FROM `quotation_detail` a 
                                                                        LEFT JOIN item b ON a.`productid` = b.id 
                                                                        LEFT JOIN stock s ON a.productid = s.product
                                                                        WHERE `socode`='" . $soid . "' 
                                                                        
                                                                        GROUP BY a.`id`, a.`socode`, a.`sosl`, a.`productid`, a.`mu`, 
                                                                         a.`qty`, a.`qtymrc`, a.`otc`, a.`mrc`, a.`remarks`, 
                                                                         a.`makeby`, a.`makedt`, a.`currency`, a.vatrate, a.aitrate, 
                                                                         b.name, b.barcode, a.discountrate, a.discounttot
                                                                        ORDER BY a.sosl ASC";
                                                                        
                                                                        
                                                                        //ORDER BY a.sosl ASC";
                                                                        
                                                                        
                                                                        $_SESSION['debug']['mode_'.$mode]['quotation_detail_itemq_uery_1'] = $itmdtqry;
                                                            }
                                                            
                                                            

                                                            //echo $itmdtqry;die;
                                                            //file_put_contents("query.txt", $itmdtqry); 
                                                    
                                                        $resultitmdt = $conn->query($itmdtqry);
                                                        
                                                        if ($resultitmdt->num_rows > 0) 
                                                        {
                                                            //print_r($resultitmdt->fetch_assoc());die;
                                                            
                                                            while ($rowitmdt = $resultitmdt->fetch_assoc()) {
                                                            $order_detail_id  = $rowitmdt["id"];
                                                            $itmdtid  = $rowitmdt["productid"];
                                                            $itdmu    = $rowitmdt["mu"];
                                                            $itdqu    = $rowitmdt["qty"];
                                                            $itdqumrc = $rowitmdt["qtymrc"];
                                                            $itdotc   = $rowitmdt["otc"];
                                                            $incvat   = $rowitmdt["otc"]*$itdqu * (1 + ($rowitmdt["vat"] / 100));
                                                            $footerSubtotal = $footerSubtotal+$incvat;
                                                            
                                                            $itdmrc   = $rowitmdt["mrc"];
                                                            $itdrem   = $rowitmdt["remarks"];
                                                            $currency = $rowitmdt["currency"];
                                                            $itvat    = $rowitmdt["vat"];
                                                            $itait    = $rowitmdt["ait"];
                                                            $itmname  = $rowitmdt["itmname"];
                                                            $code  = $rowitmdt["barcode"];
                                                            $freeqty  = $rowitmdt["freeqty"];
                                                                //enable book disable if $freeqty has - minus or 0 qty value
                                                                if($bookDisableFlag == 0 && $freeqty <1){
                                                                    $isDisabled = "disabled";
                                                                    $bookDisableFlag = 1;
                                                                }				
                                                                
                                                            $discountrate  = $rowitmdt["discountrate"];
                                                            //$footerDiscount = $footerDiscount+(($incvat*$discountrate)/100);
                                                            $discountAmount = ($itdotc*$discountrate)/100;
                                                            
                                                            $discounttot  = $rowitmdt["discounttot"];
                                                            $footerPayable = $footerPayable+ $discounttot;
                                                            $cost  = $rowitmdt["cost"];
                                                            $itdtot   = number_format(($itdqu * $itdotc) + ($itdqumrc * $itdmrc),2);
                                                            $itdup   = ($itdqu * $itdotc) + ($itdqumrc * $itdmrc);
                                                            $itdgt    = $itdgt + $discounttot;
                                                            $discttot=$itdgt-$adj;
                                                            $discount_amount = $rowitmdt["discount_amount"];
                                                            $footerDiscount=$footerDiscount+$discount_amount;
                                                            $totalcost=$totalcost+($itdqu*$itdotc);
                                                            $netamount=$itdgt;
                                                                
                                                                
                                                                
                                                                //new code, rak, vat amount;
                                                                $orVATRate = $itvat;
                                                                $orPrice = $itdotc;
                                                                $orQty = $itdqu;
                                                                $orDicntRate = $discountrate;
                                                                
                                                                $OrUnitTotal = $orPrice*$orQty;
                                                                $OrDiscountAmout = ($OrUnitTotal*$orDicntRate)/100;
                                                                $OrAmountWithDiscount = $OrUnitTotal - $OrDiscountAmout;
                                                                $OrVATAmout = ($OrAmountWithDiscount*$orVATRate)/100;
                                                                $OrSubtotal =  $OrSubtotal+ $OrAmountWithDiscount;				
                                                                
                                                    ?>
                                                                  <!-- this block is for php loop, please place below code your loop  -->
                                                                                            
                                                                                            
                                                                        <!-- edit mode -->
                                                                        
                                                                            <div class="toClone" data-order_detail_id="<?=$order_detail_id?>">
                                                                                    <div class="col-lg-3 col-md-5 col-sm-3 col-xs-12"> 
                                                                                        <label class="hidden-lg">Item Name</label>
                                                                                        <div class="form-group">
                                                                                            <div class="form-group styled-select">
                                                                                                <!--input list="itemName" name="itmnm[]"  autocomplete="off" value = "<?=$itmname?>-[Cd: <?=$code; ?> | St: <?=$freeqty?>]" class="dl-itemName datalist" placeholder="Select Item" required-->
                                                                                                <!input type="hidden" placeholder="ITEM" value="<?php echo $itmdtid; ?>" name="itemName[]" class="itemName">
                                                                                                <input type="hidden" name="itemName[]" value="<?php echo $itmdtid; ?>" class="itemName" flag="1">
                                                                                                        
                                                                                                        <select   class="productname form-control">
                                                                                                            <option value="">Select Item </option>
                                                                                                                <?php 
                                                                                                                            
                                                                                                                                $qryitm = 	"SELECT i.id, i.image, i.name,i.barcode, round(i.vat, 2) vat, round(i.ait, 2) ait, round(i.rate, 2) rate, round(i.cost, 2) cost , (COALESCE(s.freeqty,0) + COALESCE(s.futureqty,0) + COALESCE(s.backorderqty,0)) freeqty
                                                                                                                                    FROM item i
                                                                                                                                    INNER JOIN stock s ON i.id = s.product 
                                                                                                                                    WHERE i.id=$itmdtid AND (COALESCE(s.freeqty,0) + COALESCE(s.futureqty,0) + COALESCE(s.backorderqty,0))>= 0
                                                                                                                                    order by i.name";
                                                                                                                            $_SESSION['debug']['mode_'.$mode]['item_uery_1'] = $qryitm;
                                                                                                                            
                                                                                                                        $resultitm = $conn->query($qryitm); 
                                                                                                                        if ($resultitm->num_rows > 0) {
                                                                                                                            while($rowitm = $resultitm->fetch_assoc()) 
                                                                                                                            { 
                                                                                                                            
                                                                                                                                $img  = $rowitm["image"];
                                                                                                                                $tid  = $rowitm["id"];
                                                                                                                                $nm   = $rowitm["name"];
                                                                                                                                $code  = $rowitm["barcode"];
                                                                                                                                $cost = $rowitm["rate"];
                                                                                                                                $up = $rowitm["rate"];
                                                                                                                                $vat  = $rowitm["vat"];
                                                                                                                                $ait  = $rowitm["ait"];
                                                                                                                                $prdcost=$rowitm["cost"];
                                                                                                                                $stock=$rowitm["freeqty"];
                                                                                                                                $chkstk[$itmdtid] = $rowitm["freeqty"];
    
                                                                                                                                $isSelected = ($itmdtid==$tid)?"selected":"";
                                                                                                                            
                                                                                                                            
                                                                                                            ?>
                                                                                                            <option  <?=$isSelected?>  class="option-<?=$tid?>" data-image="<?=$hostpath?>/assets/images/products/300_300/<?=$img?>" data-value="<?php echo $tid; ?>" data-stock="<?=$stock?>" data-prdcost="<?php echo $prdcost; ?>" data-up="<?php echo $up; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" value="<?=$nm?>-[Cd: <?=$code; ?> | St: <?=$stock?>]"><?=$nm?>-[Cd: <?=$code; ?> | St: <?=$stock?>]</option>
                                                                                                            <?php 
                                                                                                            
                                                                                                            }}?>                    
                                                                                                        
                                                                                                        </select> 
                                                                                                    </div>
                                    
                                                                                        </div>
                                                                                    </div> <!-- this block is for itemName-->
                                    
                                                                                    <div class="col-lg-1 col-md-1 col-sm-7 col-xs-8">
                                                                                        <label class="hidden-lg">Price</label>
                                                                                        <div class="form-group">
                                                                                            <input  type="text" class="calc  c-price form-control unitprice_otc1_ unitPriceV2_" placeholder="Price" id_="unitprice_otc1" value="<?=$itdotc?>" name="unitprice_otc[]" readonly>
                                                                                            <!--input type="hidden"  class="form-control unitprice_otc" name="unitprice_otc[]" id="unitprice_otc" value="<?php echo $itdotc; ?>"-->
                                                                                        </div>
                                                                                    </div>												
                                                                                    <div class="col-lg-1 col-md-1 col-sm-5 col-xs-4">
                                                                                        <label class="hidden-lg">Qty</label>
                                                                                        <div class="form-group qtnqrapper">
                                                                                            <input type="text"  data-stock="<?=$stock?>"  autocomplete="off" <?=($stock == 0)?'style="border:1px solid red;"':''?>  required class="calc c-qty form-control quantity_otc_"  id_="quantity_otc" value="<?php echo $itdqu; ?>" name="quantity_otc[]">
                                                                                            <?php
                                                                                                    $whParams = array();
                                                                                                    $whParams['pid'] = $itmdtid;
                                                                                                    $whParams['oid'] = $soid;
                                                                                                    $whParams['currstock'] = $freeqty;
                                                                                                    $whParams['revision'] = $_REQUEST['changedid'];
                                                                                                    $whParams['order_detail_id'] = $order_detail_id;
                                                                                                    loadWarehouse($whParams);
                                                                                            ?>
                                                                                        </div>
                                                                                    </div>	
                                                                                    <div class="col-lg-1 col-md-1 col-sm-2 col-xs-5">
                                                                                    <label class="hidden-lg">Unit Total</label>
                                                                                        <div class="form-group">
                                                                                            <input type="text" class="c-price-utt form-control TotalAmount_ unitTotal" id_="total" placeholder="Unit Total" value="<?php echo $itdtot; ?>" readonly  name="total[]">
                                                                                        
                                                                                        </div>
                                                                                    </div> 
                                                                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3"><!-- this block is for vat-->
                                                                                        <label class="hidden-lg">VAT</label>
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12 col-xs-12">
                                                                                                <div class="form-group">
                                                                                                    <input type="numeric" class="calc c-vat form-control vat_" id_="vat"  value="<?php echo $itvat; ?>" name="vat[]" readonly >
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div><!-- this block is for vat-->
                                                                                    
                                                                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
                                    												    <label class="hidden-lg">Including VAT</label>
                                                                                        <div class="form-group">
                                                                                            <input type="text"  class=" form-control calc inc_vat"  id="vat_amt" value="<?php echo $incvat; ?>" placeholder="Amount Incl VAT" name="vat_amt[]" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <!-- this block is for discount-->
                                                                                    <div class="col-lg-1 col-md-1 col-sm-1  col-xs-2">
                                                                                        <label class="hidden-lg">Dis%</label>
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12 col-xs-12">
                                                                                                <div class="form-group">
                                                                                                    <input type="text"  min="0.00" step="any" readonly class="numonly calc c-discount form-control discnt_ discountRate" id_="discnt"  placeholder="Discount%" value="<?php echo $discountrate; ?>" name="discnt[]" >
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-lg-1 col-md-1 col-sm-1  col-xs-2">
                                                           
                                                                                         <label class="hidden-lg">Dis Taka</label>   
                                    													<div class="form-group">
                                    													    <?php
                                    													        //$discountAmount = ($incvat*$discountrate)/100;
                                    													        $discountAmount = $discount_amount;
                                    													    ?>
                                    														<input type="text" step="any"   class="numonly calc c-discount-amount form-control discnt_ discountAmount" value="<?php echo str_replace(",","",$discountAmount); ?>"    placeholder="Discount Taka" name="discntamnt[]">
                                    													</div>
                                                                                    </div>
                                                                                    
                                    
                                    
                                    
                                                                                    
                                    
                                    
                                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
                                                                                    <label class="hidden-lg">Discounted Total</label>
                                                                                        <div class="form-group">
                                                                                            <input type="text" class="c-discounted-ttl form-control unitTotalAmount1_" id_="unittotal1" placeholder="Unit Total" value="<?php echo $discounttot; ?>"  readonly  name="unittotal1[]">
                                                                                            <input type="hidden"  class="form-control unitTotalAmount" name="unittotal[]" id="unittotal"  value="<?php echo $discounttot; ?>">
                                                                                            <input type="hidden" class="form-control prodprice1" id="prodprice"  value="<?php echo $cost; ?>" name="prodprice[]" >
                                                                                            <input type="hidden" class="form-control rowid" id="rowid"  value="<?php echo $rowid; ?>" name="rowid[]" >
                                                                                            
                                                                                            <input type="hidden" value="<?=$OrDiscountAmout?>" class="c-h-discount-amt" style="width:100px;">
                                                                                            <input type="hidden" value="<?=$OrVATAmout?>" class="c-h-vat-amt" style="width:100px;">
                                                                                            
                                                                                        </div>
                                                                                    </div> 
                                                                                    
                                    
                                    
                                                                                        <?php if ($rCountLoop > 0) { ?>
                                                                                        <div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                                                                        <?php } $rCountLoop++; ?>
                                    
                                                                            </div>
                                                                        
                                                <?php 
                                                                
                                                            }
                                                            
                                                        }else {

                                                            //if edit mode do not have any item saved before
                                                            //system needs at leat one input for item to copy; so blank toClone is placed.
                                                        ?>
                                                    
                                                    
                                                    
                                                        
        
                                                  
                                                    
                                                    <!--  -->
                                                
        											<div class="toClone" >
                  	                                    <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
        													<label class="hidden-lg">Item Name</label>
                                                            <div class="form-group">
                                                               <!--input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"-->
                                                                <div class="form-group styled-select">
                                                                    <!input type="text" list="itemName"  autocomplete="off" name="itmnm[]"  class="dl-itemName datalist" placeholder="Select Item" required>
        															<input type="hidden" name="itemName[]" value="" class="itemName">
                                                                    <select   class="productname form-control">
                                                                        <option value="">Select Item</option>
                                                                        <?php 
                                                                                
                                                                                $qryitm = 	"SELECT i.id, i.image, i.name, round(i.vat, 2) vat, round(i.ait, 2) ait, round(i.rate, 2) rate, round(i.cost, 2) cost , s.freeqty
                                                                                            FROM item i
                                                                                            INNER JOIN stock s ON i.id = s.product
                                                                                            order by i.name";
                                                                    
                                                                    
                                                                                
                                                                                
                                                                            $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                                $img  = $rowitm["image"];
                                                                                $tid  = $rowitm["id"];
                                                                                $nm   = $rowitm["name"];
                                                                                $cost =$rowitm["rate"];
                                                                                $up = $rowitm["rate"];
                                                                                $vat  = $rowitm["vat"];
                                                                                $ait  = $rowitm["ait"];
                                                                                $prdcost=$rowitm["cost"];
                                                                                $stock=$rowitm["freeqty"];
                                                                                
                                                                                
                                                                                ?>
                                                                            <option  class="option-<?=$tid?>" data-image="<?=$hostpath?>/assets/images/products/300_300/<?=$img?>"  data-value="<?php echo $tid; ?>" data-prdcost="<?php echo $prdcost; ?>" data-up="<?php echo $up; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" value="<?php echo $nm; ?>"><?=$nm?> (St: <?=$stock?>)</option>
                                                                        <?php }} ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div> <!-- this block is for itemName-->
                                                        
                                                        <!-- this block is for vat-->
                                                         <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
        												    <label class="hidden-lg">VAT</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control vat" id="vat" placeholder="VAT%" name="vat[]" readonly>
                                                                </div>
        
                                                        </div>
        
        
                  	                                    <div class="col-lg-2 col-md-3 col-sm-3  col-xs-9">
        												
                                                            <div class="row qtnrows">
                                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-4">
        															<label class="hidden-lg">Qty</label>
                                                                    <div class="form-group">
                                                                        <input type="text"  required class="form-control quantity_otc" id="quantity_otc" placeholder="Qty" name="quantity_otc[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-8">
        														<label class="hidden-lg">Price</label>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control unitprice_otc1 unitPriceV2" id="unitprice_otc1" placeholder="Price" name="unitprice_otc[]">
                                                                        <!--input type="hidden" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" name="unitprice_otc[]" class="unitprice_otc"-->
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> <!-- this block is for quantity_otc, unitprice_otc-->
        
        
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
        												<label class="hidden-lg">Unit Total</label>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control TotalAmount" id="total" placeholder="Unit Total" readonly  name="total[]">
                                                              
                                                            </div>
                                                        </div> 
                                                        <!-- this block is for discount-->
                                                         <div class="col-lg-1 col-md-1 col-sm-1  col-xs-2">
                                                           
                                                             <label class="hidden-lg">Dis%</label>   
        													<div class="form-group">
        														<input type="text"   class="form-control discnt" id="discnt"   placeholder="Discount %" name="discnt[]" readonly>
        													</div>
                                                               
                                                            
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
        													<label class="hidden-lg">Total</label>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitTotalAmount1" id="unittotal1" placeholder="Discounted Total " readonly  name="unittotal1[]">
                                                                <input type="hidden"  class="form-control unitTotalAmount" name="unittotal[]" id="unittotal">
                                                                <input type="hidden" class="form-control prodprice1" id="prodprice" name="prodprice[]" >
                                                                 <input type="hidden" class="form-control rowid" id="rowid"  value="0" name="rowid[]" >
                                                            </div>
                                                        </div> 
                                                        <!-- this block is for unittotal-->
                                                        <!--div class="col-lg-2 col-md-6 col-sm-6">
                                                            <div class="row qtnrows">
                                                                <div class="col-sm-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" id="remarks" placeholder="Remarks" name="remarks[]">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div -->  <!-- this block is for remarks-->
                                                    </div>
        										
        											
        											
                            <?php 
                            }
                            ?></div><!-- end of <div class="clonewrapper">  -->