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
    where o.id=" . $oid;
//echo $qry; die;
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
} else {
    $result = $conn->query($qry);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ordid        = $row["oid"];
            $order_id     = $row["order_id"];
            $payment_mood = $row["payment_mood"];
            $cusnm        = $row["cusnm"];
            $orderdt      = $row["order_date"];
            $phone        = $row["phone"];

            $orderstatus = $row["orderstatus"];
            $ost         = $row["ost"];
            $deladr      = $row["deladr"];
            $name        = $row["name"];
            $cusaddr     = $row["cusaddr"];
            $amount      = $row["amount"];

            $discount_total  = $row["discount_total"];
            $shipping_charge = $row["shipping_charge"];

            if ($freeShipping == 1) {
                if ($amount >= $freeShippingAmountLimit) {
                    $shipping_charge = 0;
                }
            }

            $deleveryagent = $row["deleveryagent"];
            $email         = $row["email"];

            if ($cusaddr == '') {$cusaddr = $deladr;}
            if ($deladr == '') {$deladr = $cusaddr;}

            $invoiceno = $row["invoiceno"];
            $agentname = $row["agentname"];
            $hrid      = '1';
            
            $deliveryaddress = $row["remarks"];
        }
    }
}

?>
 <div class="panel panel-info" id="printableArea">
                      <h1 style="text-align:center;margin-bottom:0"><?= $comname ?></h1>
						<h6 style="text-align:center;margin-top:0"><?= $comaddress ?></h6>
                        <h5 style="text-align:center" id="order_review_heading">Delivery Assign</h5>






			            <div class="panel-body">
                            <div class="row">
                        		<div class="col-sm-12">

	                                 <input type="hidden"  name="ordid" id="ordid" value="<?php echo $ordid; ?>">
	                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>" >

	                            </div>









                            	 <div class="col-xs-6">
                                    <h4>Order Number: <?php echo $order_id; ?>  </h4>
                                    <!--hr class="form-hr"></hr-->
                                </div>

                                <div class="col-xs-6 text-right">
                                    <?php if ($invoiceno != '') { ?>
                                    <h4>Invoice No: <?php echo $invoiceno; ?>  </h4>
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
</style>


	<table class="tbl-bill" width="100%" border="1" cellspacing="5" cellpadding="5">
                                             <thead>
                                                <tr style="background-color:#E7E7E7">
                                                    <th>Photo </th>
                                                    <th style="min-width: 150px">Item Name</th>
                                                    <th>Product Code</th>

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
$qry2        = "SELECT distinct d.productid product_id,p.name product_name,p.image,d.vat,d.qty quantity,d.otc rate
,0 discount,(d.qty*d.otc) total,d.barcode,'' almasode,lpad(p.id,8,0) prcd
,ifnull((SELECT freeqty FROM `stock` where product=p.id),0) stock
FROM soitemdetails d left join item p on d.productid=p.id
where d.socode= '" . $order_id . "'";
$result2 = $conn->query($qry2);if ($result2->num_rows > 0) {while ($row2 = $result2->fetch_assoc()) {$tid = $row2["id"];
    $nm                             = $row2["product_name"];
    $rate                           = $row2["rate"];
    $quantity                       = $row2["quantity"];
    $total                          = $row2["total"];
    $prod                           = $row2["almasode"] . $row2["prcd"];
    $barcode                        = $row2["barcode"];
    $stock                          = $row2["stock"];
    $photo                          = "./common/upload/item/" . $row2['image'].".jpg";
    $discounttot                    = $discounttot + $row2["discount"] * $quantity;
    $gtotal                         = $gtotal + $total;
    $vatt                           = $vatt + ($total * $row2["vat"] * .01);
    $deliverycharge                 = 100;
    $vat                            = ($total * $row2["vat"] * .01);
    ?>                                                  <tr>
                                                    <td><img src='<?php echo $photo; ?>' width="50" height="50"></td>
                                                    <td><?php echo $nm; ?> </td>
                                                    <td><?php echo $prod; ?> </td>

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