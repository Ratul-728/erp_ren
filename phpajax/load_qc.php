<?php

//$id=13;

$qry = "SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr

    ,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr, o.remarks

    ,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent,concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno,org.email

    FROM  soitem o left join orderstatus s on o.orderstatus=s.id

     left join organization org on o.organization=org.id

    left join district d on org.district=d.id

    left join area a on org.area=a.id

    left join contact c on o.customer=c.id

    left join district d1 on c.district=d1.id

    left join area a1 on c.area=a1.id

	left join deveryagent da on o.deliveryby=da.id

    where o.id= " . $did;

//echo $qry; die;

if ($conn->connect_error) {

    echo "Connection failed: " . $conn->connect_error;

} else {

    $result = $conn->query($qry);

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $ordid           = $row["oid"];

            $order_id        = $row["order_id"]; //socode

            $payment_mood    = $row["payment_mood"];

            $cusnm           = $row["cusnm"];

            $orderdt         = $row["order_date"];

            $phone           = $row["phone"];

            $orderstatus     = $row["orderstatus"];

            $ost             = $row["ost"];

            $deladr          = $row["deladr"];

            $name            = $row["name"];

            $cusaddr         = $row["cusaddr"];

            $amount          = $row["amount"];

            $discount_total  = $row["discount_total"];

            $shipping_charge = $row["shipping_charge"];

            $deleveryagent   = $row["deleveryagent"];

            $invno           = fetchByID( 'invoice', 'soid', $row["order_id"], 'invoiceno' ); //$row["invoiceno"];  if ($cusaddr == '') {$cusaddr = $deladr;}if ($deladr == '') {$deladr = $cusaddr;}

            $deliveryaddress = $row["remarks"];



            $hrid = '1';

        }

    }

}



?>

 <div class="panel panel-info" id="printableArea">

                    

                        




			            <div class="panel-body">
                            
                            
                            
                            <span class="alertmsg">    </span>
                            
                            
                            
                            <div class="well list-top-controls"> 
                                
                                <div class="row border">
                                  <div class="col-sm-3 text-nowrap">
                                    <h6>Sales <i class="fa fa-angle-right"></i> Pending Orders for QC</h6>
                                  </div>
                                  <div class="col-sm-9 text-nowrap">  </div>
                                </div>
                                
                            </div>
                            

                            <div class="row">

                        		<div class="col-sm-12">



	                                 <input type="hidden"  name="ordid" id="ordid" value="<?=$ordid?>">

	                                 <input type="hidden"  name="usrid" id="usrid" value="<?=$usr?>" >

									<input type="hidden" name="socode" value="<?php echo $order_id; ?>">

	                            </div>







                            	 <div class="col-xs-6">

                                    <h4>Order Number: <?php echo $order_id; ?>  </h4>
									
                                    <!--hr class="form-hr"></hr-->

                                </div>



                                <div class="col-xs-6 text-right">

                                    <?php if ($invno != '') { ?>

                                    <h4>Invoice No: <?php echo $invno; ?>  </h4>

                                    <?php } ?>

                                </div>



                                <div class="col-xs-12">



									<table class="orderinfo-tbl" width="100%" border="0" cellpadding="10"  cellspacing="10">

										<tr>

											<td width="33%">

												<b> Order Date:</b> <?php echo $orderdt; ?></br>



											</td>

											<td width="33%" align="center">





												<b> Status:</b> <?php echo $ost; ?></br>

											</td>

											<td width="33%" align="right">

												<b> Payment via :</b><?php echo $payment_mood; ?>

											</td>



										</tr>

									</table>

                            	</div>





                                <div class="col-sm-12">

                                    <h4>Customer Information  </h4>

                                    <!--hr class="form-hr"></hr-->

                                </div>





                                <div class="col-xs-12">



									<table class="cusinfo-tbl" width="100%" border="0" cellpadding="10"  cellspacing="10">

										<tr>

											<td width="50%">

												<b>Customer Name:</b> <?php echo $cusnm; ?></br>

												<b>Phone :</b><?php echo $phone; ?>

											</td>

											<td width="50%" align="right">

											<b>Delivery Address:</b></br>

											<?php echo $deliveryaddress; ?>



											</td>

										</tr>

									</table>

                            	</div>





























                                <div class="po-product-wrapper">

                                    <div class="color-block">

 		                                <div class="col-sm-12">

                                            <h4>Product Information  </h4>

                                        </div>

                                        <div class="col-sm-12">



<style>

.tbl-bill{

    border: 0px solid #000;

}



.tbl-bill th{padding: 5px 10px; border: 1px solid #000; border-bottom:1px solid black;}

.tbl-bill tbody > tr   td{padding: 0px 10px; border: 1px solid #000; border-bottom:1px solid black;}



.tbl-bill .noborder{border: 0;}



.tbl-bill tr th:last-child{width: 150px;}

.tbl-bill tr th:first-child{width: 50px;}

.tbl-bill tr th:nth-child(3),

.tbl-bill tr th:nth-child(4){width: 150px;}



	.store-td{
		background-color: #00ABE3;
		color: #fff;
		text-align: center;
	}	
	
.tbl-bill table td{
    padding: 0!important;
}

.tbl-bill td table input{
    background-color: rgba(243,243,241,0.43)
}		
	
.tbl-bill input[readonly]{
    background: transparent;
    border: 0;
}
	
	

 .tbl-bill td table select,.tbl-bill td table input{
/*    border:0!important;*/

    
}

.tbl-bill td table td{
    border-top:0!important;
    
}

.tbl-bill td table td:first-child{
    border-left:0!important;
    
}
.tbl-bill td table td:last-child{
    border-right:0!important;
    
}
	
	
.store-wrapper table td{padding:8px 8px!important;}		
	
</style>



<?php

 $qrybc2="SELECT barcodewithstore FROM `companyoffice` ";

    $resbc2 = $conn->query($qrybc2);

    while($row2bc = $resbc2->fetch_assoc())

    {

        $storewise=$row2bc["barcodewithstore"]; if ($storewise=='Y'){$bctitle='BAR Code';} else {$bctitle='Store Room';}

    }

?>

 										<table class="tbl-bill delivery-table" width="100%" border="1" cellspacing="5" cellpadding="5">

                                             <thead>

                                                <tr style="background-color:#E7E7E7">

                                                    <th>Photo </th>

                                                    <th style="min-width: 150px">Item Name<!--img src="../../assets/images/pix.png" width="150" height="1"--></th>

                                                    <th width="10" nowrap  style="text-align: center;width: 20px;">Product ID</th>

                                                    <th  width="10"  style="text-align: center;width: 10px;">Qty Ordered</th>
													<th  width="10"  style="text-align: center;width: 10px;">Qty Delivered</th>
                                                    <th class="store-td" style="width: 100px;">Qty Due</th>
                                                     

                                                    <th class="store-td" style="width: 212px; background-color: ">Warehouse</th>
													<th class="store-td" style="width: 100px">Stock Qty</th>
                                                    <th class="store-td" style="width: 100px">Send Qty for QC</th>
                                                    <th class="store-td" style="width: 60px">Passed</th>
                                                    <th class="store-td" style="width: 60px">Failed</th>
                                                    <th class="store-td" style="width: 60px">Action</th>
													
                                                 

                                                </tr>

											</thead>

											<tbody>



<?php

$discounttot = 0;

$gtotal      = 0;

$vat         = 0;

$vatt        = 0;

$qry2        = "SELECT distinct d.id, d.productid product_id, d.deliveredqty,d.dueqty, p.name product_name,p.image,d.vat,d.qty quantity,d.otc rate

,0 discount,(d.qty*d.otc) total,d.barcode,d.storeroome,'' almasode,lpad(p.id,8,0) prcd

,ifnull((SELECT freeqty FROM `stock` where product=p.id),0) stock, d.sosl sosl

FROM soitemdetails d left join item p on d.productid=p.id

where d.socode='" . $order_id . "'";

 	$result2 = $conn->query($qry2);if ($result2->num_rows > 0) {
	while ($row2 = $result2->fetch_assoc()) {$tid = $row2["id"];

		$prd           = $row2["product_id"];

		$nm            = $row2["product_name"];
																									 
		$rate          = $row2["rate"];

		$quantity     = $row2["quantity"];
																									 
   		$deliveredqty = $row2["deliveredqty"];
																									 
 		$dueqty =   $row2["quantity"] - $row2["deliveredqty"];

		$total        = $row2["total"];

		$prod         = $row2["almasode"] . $row2["prcd"];

		$barcode     = $row2["barcode"];

		$storcode     = $row2["storeroome"];

		$stock        = $row2["stock"];

		//$photo        = "./common/upload/item/" . $row2['image'].".jpg";
		$photo		= (strlen($row2['image'])>0)?"assets/images/products/300_300/".$row2["image"]:"assets/images/products/placeholder.png";
											 
											 

		$discounttot   = $discounttot + $row2["discount"] * $quantity;

		$gtotal       = $gtotal + $total;

		$vatt         = $vatt + ($total * $row2["vat"] * .01);


		$vat        = ($total * $row2["vat"] * .01);
		 
 		$vat1        = $row2["vat"];
		
		$sosl        = $row2["sosl"];

    ?>  
												
												<input type="hidden" name="product_id[]" value="<?=$prd?>" >
												<input type="hidden" name="sosl[<?=$prd?>]" value="<?=$sosl?>">
											<tr class="prdct-row proid-<?=$prd?>" <?=(number_format($quantity, 0) == $deliveredqty)?'style="background-color:#c2e6b8;"':''?>>

                                                    <td>
														<img src='<?php echo $photo; ?>' width="50" height="50"></td>

                                                    <td><?php echo $nm; ?> </td>

                                                    <td><?php echo $prod; ?> </td>

                                                    <td align="center"><?=number_format($quantity, 0)?></td>

                                                  <td align="center"><?=$deliveredqty?> <input type="hidden" name="deliveredqty[<?=$prd?>]"  class="delivered-qty" value="<?=$deliveredqty?>"></td>  
                                                    <td style="padding: 0"><input name="duedelqty[<?=$prd ?>]" type="text" style="height:100%;" class="form-control due-delivery text-center" readonly value="<?=$dueqty?>"></td>

                                                    <td colspan="6" class="store-wrapper" style="width: 400px; padding:0!important; border: 0!important;">
                                                        
														
														<input type="hidden" name="order_qtn[<?=$prd?>]"  class="order-qty" value="<?=number_format($quantity, 0)?>">
														
														
														<!--Load Stores whhere stock is available-->
														<?php
																			$qryitm = 'SELECT DISTINCT c.storerome sid, br.name, c.freeqty 
																						FROM chalanstock c
																						LEFT JOIN branch br ON c.storerome = br.id
																						WHERE product='.$prd.' AND  c.storerome !=6';
																			
																						



																	$resultitm = $conn->query($qryitm);
																	if ($resultitm->num_rows > 0) {
																		$tabindex = 0;
																	while($rowitm = $resultitm->fetch_assoc()){

																						$stid = $rowitm["sid"];

																						$nm  = $rowitm["name"];
																						
																						$freeqty  = $rowitm["freeqty"];
														
														?>
														
														
														
														<table width="100%" border="0" cellpadding="0" cellspacing="0">
														<tr class="pf-parent">
															<td style="width: 200px ">
																<?php
																	//get storewise delivery qtns;
																	$qryforstorewisedelivery = array('socode' => $order_id, 'productid' => $prd,'storeroome' => $stid);
																	$qtyfromthisstore = fetchSingleDataByArray('delivery',$qryforstorewisedelivery,'deliveredqty');
																?>																
																
															    <input type="hidden" name="	store[]"  class="order-qty" value="<?=$stid?>">
																<input class="form-control" readonly data-storename="<?=$stid?>" type="hidden" name="brcd[<?=$stid?>]" value="<?=$nm?>">
																<?=$nm?> <?=($qtyfromthisstore)?'- <span style="font-size:11px">(Delivered: '.$qtyfromthisstore.')</span>' :''?>
															</td>
															<td style="width: 100px;text-align: center;">
																<input class="form-control text-center" readonly data-available-stk="<?=$freeqty ?>" data-storename="<?=$stid?>" type="hidden" name="stockqtn[<?=$prd ?>][<?=$stid?>]" value="<?=$freeqty?>">
																<?=$freeqty?>
															</td>															
															<td style="width: 60px">
                                                                
                                                                
                                                                <?php
                                                                
                                                                    $fetchQCArrayVal = array('soid' => $order_id,'product' => $prd,'store' => $stid);
                                                                    $qcSentQty = fetchSingleDataByArray('qcsum',$fetchQCArrayVal,'qcqty');
                                                                    $qcSentQty = ($qcSentQty>0)?$qcSentQty:0;
                                                                    
                                                                        if($qcSentQty>0){
                                                                              $qcSentTtlQty++; 
                                                                        }
                                                                        
                                                                        $isStDisblStr = ($qcSentQty < 1)?'disabled':'';
                                                                                                                                          
                                                                ?>                                                                
                                                                
                                                                
																<input type="text" disabled class="delivery-qty form-control  text-center" tabindex="<?=$tabindex?>" name="deliveryqty[<?=$prd ?>][<?=$stid?>]"  value="<?=$qcSentQty?>" >															
															</td>
															<td style="width: 56px">
                                                                <?php
                                                                            $fetchQCArrayForResult = array('soid' => $order_id,'product' => $prd,'store' => $stid);
                                                                            $passQty = fetchSingleDataByArray('qcsum',$fetchQCArrayForResult,'passqty');
                                                                            $failQty = fetchSingleDataByArray('qcsum',$fetchQCArrayForResult,'failqty');
                                                                ?>
                                                                
																<input value="<?=$passQty?>" type="text" <?=$isStDisblStr?> data-sentqty="<?=$qcSentQty?>" data-pid="<?=$prd?>" data-oid="<?=$order_id?>" data-sid="<?=$stid?>" data-ordered="<?=number_format($quantity, 0)?>" data-delivered="<?=$deliveredqty?>" data-duedel="<?=$dueqty?>"  name="passed"  class="numonly passqty delivery-qty form-control  text-center" tabindex="<?=$tabindex?>" >															
															</td>
												            <td style="width: 56px">
																<input value="<?=$failQty?>" type="text" <?=$isStDisblStr?> data-sentqty="<?=$qcSentQty?>" data-pid="<?=$prd?>" data-oid="<?=$order_id?>" data-sid="<?=$stid?>" data-ordered="<?=number_format($quantity, 0)?>" data-delivered="<?=$deliveredqty?>" data-duedel="<?=$dueqty?>"  name="failed"  class="numonly failedqty delivery-qty form-control  text-center" tabindex="<?=$tabindex?>" >															
															</td>
                                                            
												            <td style="width: 56px">
																
                                                                <a class="btn btn-info btn-xs <?=$isStDisblStr?> dopfaction" title="Edit" href="javascript:void(0);"><i class="fa fa-check"></i></a>
															</td>                                                             
														</tr>
															
															
															
																			
															
															
															
														</table>

    																<?php
                                                                    $isStDisblStr = "";
																		$tabindex++;
																		}
																	} ?>

                                                    

                                                    </td>

                                                   	




                                                </tr>



<?php }} ?>

                                                </tbody>

												

                                            </table>



                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>