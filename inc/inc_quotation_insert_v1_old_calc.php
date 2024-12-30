                                                <div class="row form-grid-bls  hidden-md hidden-sm hidden-xs">
        											
        											
                                                        <div class="col-lg-4 col-md-5 col-sm-6">
                                                        	<h6 class="chalan-header mgl10"> Select Item <span class="redstar">*</span></h6>
                                                        </div>
        
        												<div class="col-lg-1 col-sm-1 col-xs-6">
        													<h6 class="chalan-header"> Price <span class="redstar">*</span></h6>
        												</div>
        												<div class="col-lg-1 col-sm-1 col-xs-6">
        													<h6 class="chalan-header"> Quantity <span class="redstar">*</span></h6>
        												</div>											
        
        
        
                                                        <div class="col-lg-1 col-md-1 col-sm-6">
                                                            <h6 class="chalan-header">Unit Total </h6>
                                                        </div>
                                                         <div class="col-lg-1 col-md-1 col-sm-6">
                                                            <h6 class="chalan-header">Discount Rate %</h6>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-sm-6">
                                                            <h6 class="chalan-header">Discount Taka</h6>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-sm-6">
                                                            <h6 class="chalan-header">VAT %</h6>
                                                        </div>											
                                                        <div class="col-lg-2 col-md-2 col-sm-6">
                                                            <h6 class="chalan-header">Discounted Total </h6>
                                                        </div>
                                                </div>
        										

        											<div class="clonewrapper">
        	                                        <div class="toClone">
                  	                                    <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
        													<label class="hidden-lg">Item Name</label>
                                                            <div class="form-group">
                                                               <!--input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"-->

                                                               




       
                                                                <div class="form-group styled-select">
                                                                    <!--input type="text" list="itemName"  autocomplete = "off" name="itmnm[]"  class="dl-itemName datalist" placeholder="Select Item" required-->
        															<input type="hidden" name="itemName[]" value="" class="itemName">



                                                                   <!-- <datalist  id="itemName" class="list-itemName form-control"  > -->
                                                                   <!--select  id="itemName1"   class="list-itemNamex productname form-control dl-itemName" --->
                                                                   <select   class="productname form-control">
                                                                        <option value="">Type Item Name</option>
                                                                        <?php 
                                                                        /*
                                                                            //$qryitm = "SELECT `id`, `name`, round(`vat`, 2) vat, round(`ait`, 2) ait, round(`rate`, 2) rate, round(`cost`, 2) cost  FROM `item`  order by name";
                                                                                
                                                                            $qryitm = "SELECT i.id, i.image, i.name,i.barcode, round(i.vat, 2) vat, round(i.ait, 2) ait, round(i.rate, 2) rate, round(i.cost, 2) cost , (COALESCE(s.freeqty,0) + COALESCE(s.futureqty,0) + COALESCE(s.backorderqty,0)) freeqty
                                                                                        FROM item i
                                                                                        left  JOIN stock s ON i.id = s.product 
                                                                                        WHERE (COALESCE(s.freeqty,0) + COALESCE(s.futureqty,0) + COALESCE(s.backorderqty,0))> 0
                                                                                        
                                                                                        order by i.name";								 
                                                                                                    
                                                                        $resultitm  = $conn->query($qryitm);
                                                                        
                                                                        if ($resultitm->num_rows > 0) {
                                                                        while ($rowitm = $resultitm->fetch_assoc()) 
                                                                        {
                                                                            $img  = $rowitm["image"];
                                                                            $tid  = $rowitm["id"];
                                                                            $code  = $rowitm["barcode"];
                                                                            $nm   = $rowitm["name"];
                                                                            $cost =$rowitm["rate"];
                                                                            $up = $rowitm["rate"];
                                                                            $vat  = $rowitm["vat"];
                                                                            $ait  = $rowitm["ait"];
                                                                            $prdcost=$rowitm["cost"];
                                                                            $stock = $rowitm["freeqty"];
                                                                            ?>
                														<option class="option-<?=$tid?>" data-image="<?=$hostpath?>/assets/images/products/300_300/<?=$img?>" data-value="<?=$tid?>" data-stock="<?=$stock?>" data-prdcost="<?php echo $prdcost; ?>" data-up="<?php echo $up; ?>" data-cost="<?php echo $cost; ?>" data-vat="<?php echo $vat; ?>" data-ait="<?php echo $ait; ?>" value="<?=$nm?>-[Cd: <?=$code; ?> | St: <?=$stock?>]"><?=$nm?>-[Cd: <?=$code; ?> | St: <?=$stock?>]</option>																
                                                                        <?php   
                                                                                }
                                                                        }
                                                                        */
                                                                        ?>
                                                                    </select>
                                                                    <!-- </datalist> -->
                                                                </div>
                                                            </div>
                                                        </div> <!-- this block is for itemName-->
        												
        												
        												<div class="col-lg-1 col-md-2 col-sm-2 col-xs-8">
        												<label class="hidden-lg">Price*</label>
        													<div class="form-group">
        														<input type="text" class="numonly form-control unitprice_otc1_ unitPriceV2_ calc c-price" id="unitprice_otc1_" value="0.00" placeholder="Price" name="unitprice_otc[]" readonly>
        														<!--input type="hidden" class="form-control unitprice_otc_ unitPriceV2_" id="unitprice_otc_" name="unitprice_otc[]"-->
        
        													</div>
        												</div>												
        												<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
        													<label class="hidden-lg">Qty</label>
        													<div class="form-group">
        														<input  type="text"  autocomplete="off" required class="numonly calc c-qty form-control quantity_otc_ qty-chkstk" id="quantity_otc_" placeholder="Qty" name="quantity_otc[]">
        													</div>
        												</div>
        												
                                                        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-5">
        												<label class="hidden-lg">Unit Total</label>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control TotalAmount_ c-price-utt unitTotal"  placeholder="Unit Total" readonly  name="total[]">
                                                              
                                                            </div>
                                                        </div> 
        
                                                        <!-- this block is for discount-->
                                                         <div class="col-lg-1 col-md-1 col-sm-1  col-xs-2">
                                                           
                                                             <label class="hidden-lg">Dis%</label>   
        													<div class="form-group">
        														<input type="number" min="0" max="100" step="any"  class="numonly calc c-discount form-control discnt_ discountRate"     placeholder="Discount %" name="discnt[]">
        													</div>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-sm-1  col-xs-2">
                                                           
                                                             <label class="hidden-lg">Dis Amt.</label>   
        													<div class="form-group">
        														<input type="text" step="any"  class="numonly calc c-discount-amount form-control discnt_ discountAmount"    placeholder="Discount Taka" name="discntamnt[]">
        													</div>
                                                        </div>
        												
                                                        <!-- this block is for vat-->
                                                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
        												<label class="hidden-lg">VAT</label>
                                                                    <div class="form-group">
                                                                        <input type="text"  class="numonly form-control vat_ calc c-vat" id="vat_" placeholder="VAT%" name="vat[]" readonly>
                                                                    </div>
        
                                                        </div>
        
        
        
        
        
        
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-5">
        													<label class="hidden-lg">Disc. Total</label>
                                                            <div class="form-group">
                                                                <input type="text" st yle="width: 200px;"  class="form-control unitTotalAmount1_ c-discounted-ttl" id="unittotal1_ " placeholder="Discounted Total " readonly  name="unittotal1[]">
        														
                                                                <input type="hidden"  class="form-control unitTotalAmount_" name="unittotal[]" id="unittotal">
                                                                <input type="hidden" class="form-control prodprice1_" id="prodprice" name="prodprice[]" >
        														
                                                                <input type="hidden" class="form-control rowid" id="rowid"  value="0" name="rowid[]" >
        														
        														
        														<input type="hidden" class="c-h-discount-amt" style="width:100px;">
        														<input type="hidden" class="c-h-vat-amt" style="width:100px;">
        														
                                                            </div>
                                                        </div> 
                               
                                                    </div><!-- clone is ended -->

                                                </div><!-- clonewrapper ended -->