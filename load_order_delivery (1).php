<?php
//$id=13;
$qry = "SELECT o.id oid,o.order_id,o.payment_mood,o.name cusnm,DATE_FORMAT(o.order_date,'%e/%c/%Y %T') order_date,o.phone,o.orderstatus,s.name ost,concat(o.address,',',d.name,',',a.name) deladr
    ,c.name,concat(c.address,',',a1.name,',',a1.name) cusaddr
    ,o.amount,o.discount_total,o.shipping_charge,o.deleveryagent,o.invoiceno
    FROM  orders o left join orderstatus s on o.orderstatus=s.id
    left join districts d on o.district=d.id
    left join areas a on o.area=a.id
    left join customer c on o.customer_id=c.customer_id
    left join districts d1 on c.district=d1.id
    left join areas a1 on c.area=a1.id
    where o.id= " . $did;
//echo $qry; die;
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
} else {
    $result = $conn->query($qry);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ordid           = $row["oid"];
            $order_id        = $row["order_id"];
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
            $invno           = $row["invoiceno"];if ($cusaddr == '') {$cusaddr = $deladr;}if ($deladr == '') {$deladr = $cusaddr;}

            $hrid = '1';
        }
    }
}

?>
 <div class="panel panel-info" id="printableArea">
                        <h1 style="text-align:center;margin-bottom:0">BitCommerce</h1>
						<h6 style="text-align:center;margin-top:0">116 Max Lakeview, Gulshan-Badda Link Road, Dhaka-1212</h6>
                        <h5 style="text-align:center" id="order_review_heading">Confirm Agent</h5>












			            <div class="panel-body">
                            <div class="row">
                        		<div class="col-sm-12">

	                                 <input type="hidden"  name="ordid" id="ordid" value="<?php echo $ordid; ?>">
	                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>" >

	                            </div>



                            	 <div class="col-xs-6">
                                    <h4>Order ID: <?php echo $order_id; ?>  </h4>
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
											<?php echo $deladr; ?>

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
</style>


 										<table class="tbl-bill" width="100%" border="1" cellspacing="5" cellpadding="5">
                                             <thead>
                                                <tr style="background-color:#E7E7E7">
                                                    <th>Photo </th>
                                                    <th style="min-width: 150px">Item Name<!--img src="../../assets/images/pix.png" width="150" height="1"--></th>
                                                    <th>Product Code</th>
                                                    <th>BAR Code</th>
                                                    <th>Available Qty</th>
                                                    <th>Rate</th>
                                                    <th>Quantity</th>
                                                    <th>Amount </th>
                                                    <th>VAT</th>
                                                    <th>Total</th>
                                                </tr>
											</thead>
											<tbody>

<?php
$discounttot = 0;
$gtotal      = 0;
$vat         = 0;
$vatt        = 0;
$qry2        = "SELECT distinct d.id,d.product_id,d.product_name,p.image,p.vat,d.quantity,d.rate,d.discount,d.total,d.barcode,c.almasode,lpad(p.id,8,0) prcd
,ifnull((SELECT freeqty FROM `stock` where product=p.id),0) stock
FROM order_detail d left join product p on d.product_id=p.id left join catagorygrouping cg on p.catagory=cg.itemtype
left join  catagory c on cg.itemcatagory=c.id
where d.order_id='" . $order_id . "'";
$result2 = $conn->query($qry2);if ($result2->num_rows > 0) {while ($row2 = $result2->fetch_assoc()) {$tid = $row2["id"];
    $nm                             = $row2["product_name"];
    $rate                           = $row2["rate"];
    $quantity                       = $row2["quantity"];
    $total                          = $row2["total"];
    $prod                           = $row2["almasode"] . $row2["prcd"];
    $barcode                        = $row2["barcode"];
    $stock                          = $row2["stock"];
    $photo                          = "../assets/images/product/70_75/" . $row2['image'];
    $discounttot                    = $discounttot + $row2["discount"] * $quantity;
    $gtotal                         = $gtotal + $total;
    $vatt                           = $vatt + ($total * $row2["vat"] * .01);
    $deliverycharge                 = 100;
    $vat                            = ($total * $row2["vat"] * .01);
    ?>                                                  <tr>
                                                    <td><img src='<?php echo $photo; ?>' width="50" height="50"></td>
                                                    <td><?php echo $nm; ?> </td>
                                                    <td><?php echo $prod; ?> </td>
                                                    <td><input type="text"   placeholder="Bar Code" class="input-barcode" name="brcd[]">
                                                    <input type="hidden"  name="orddtid[]" id="orddtid" value="<?php echo $tid; ?>" >
                                                    </td>
                                                    <td> <?php echo number_format($stock, 0); ?></td>
                                                    <td><?php echo number_format($rate, 2); ?></td>
                                                    <td>x <?php echo number_format($quantity, 0); ?></td>
                                                    <td><?php echo number_format($total, 2); ?> </td>
                                                    <td><?php echo number_format($vat, 2); ?> </td>
                                                    <td><?php echo number_format(($total + $vat), 2); ?> </td>
                                                </tr>

<?php }} ?>
                                                </tbody>
												<tfooter>
												<tr>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <th class="text-right">Total</th>
                                                    <th><?php echo number_format($gtotal, 2); ?> </th>
                                                    <th><?php echo number_format($vatt, 2); ?> </th>
                                                    <th><?php echo number_format(($gtotal + $vatt), 2); ?> </th>
                                                </tr>

                                                <tr>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <th class="text-right"> Delivery Charge </th>
                                                    <th> <?php echo number_format($shipping_charge, 2); ?> </th>

                                                </tr>
                                                <tr>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
													<th class="text-right">Billing Amount</th>
                                                    <th><?php echo number_format(($shipping_charge + $gtotal + $vatt), 2); ?> </th>
                                                </tr>
											</tfooter>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>