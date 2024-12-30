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
    where o.socode= " . $oid;

// echo $qry; die;

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









			            <div class="panel-body">



								<div class="col-sm-12">

									<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;">
							  <tbody>
								<tr>
								  <td width="50%" scope="col"><img src="assets/images/site_setting_logo/<?= $comlogo ?>" width="205" height="48" alt=""/></td>
								  <td width="50%" scope="col" align="right"><div style="font-family:Stencil, impact;font-size:40px;"><?=($orderstatus == 4) ? 'ORDER' : 'ORDER' ?></div></td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								</tr>
								<tr>
								  <td><b>Delivery Address:</b><br>
								  <?php echo $cusnm; ?>	<br>
									Phone: <?php echo $phone; ?>
									<br>
								  <?php echo $deliveryaddress; ?>
								  <br>
								  <?php echo $email; ?>
								</td>
								  <td align="right"> <b>Invoice No:</b> <?php if ($invoiceno != '') { ?><?php echo $invoiceno; ?><?php } ?>
							<br>
							<b>Order Number:</b> <?php echo $order_id; ?><br>
							<b>Order Date:</b> <?php echo $orderdt; ?><br>
							<b>Payment Type:</b> <?php echo $payment_mood; ?></br>
									<b> Status:</b> <?php echo $ost; ?>
									</td>
								</tr>
							  </tbody>
							</table>

								</div>
							<br>




	                                 <input type="hidden"  name="ordid" id="ordid" value="<?php echo $ordid; ?>">

	                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>" >






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


td{ padding: 5px 10px!important; }
.tbl-bill th {padding: 5px 10px; border: 1px solid #000; border-bottom:1px solid black;}

.tbl-bill tbody > tr   td{padding: 0px 10px; border: 1px solid #000; border-bottom:1px solid black;}



.tbl-bill .noborder{border: 0;}



.tbl-bill tr th:last-child{width: 150px;}

.tbl-bill tr th:first-child{width: 50px;}

.tbl-bill tr th:nth-child(3),

.tbl-bill tr th:nth-child(4){width: 150px;}



.tbl-bill  .return-head {

    width: 10px!important;

}

	.number{text-align: right;}

</style>





							<table class="tbl-bill" width="100%" border="1" cellspacing="5" cellpadding="5" style="font-size: 12px;">

								<thead>

								<tr style="background-color:#E7E7E7">

									<th class="item_name"  style="min-width: 250px">Item Name</th>

									<th class="product_code text-nowrap">Product Code</th>

									<th class="rate text-center">Rate</th>

									<th class="quantity" style="min-width: 80px!important;">Quantity</th>

									<th class="order_amount text-center">Amount </th>

									<th class="vat text-center">VAT</th>

									<th class="total text-center">Total</th>

								</tr>

								  </thead>

								<tbody>



								<?php

$tbl = "soitemdetails";

if ($retf == "1") {

    $tbl = "order_returns";

}

$discounttot = 0;
$gtotal      = 0;
$vat         = 0;
$vatt        = 0;

$qry2 = "SELECT distinct d.productid product_id,p.name product_name,p.image,d.vat,d.qty quantity,d.otc rate
,0 discount,(d.qty*d.otc) total,d.barcode,'' almasode,lpad(p.id,8,0) prcd
,ifnull((SELECT freeqty FROM `stock` where product=p.id),0) stock
FROM soitemdetails d left join item p on d.productid=p.id
where d.socode='" . $order_id . "'";

$result2 = $conn->query($qry2);if ($result2->num_rows > 0) {while ($row2 = $result2->fetch_assoc()) {$tid = $row2["id"];
    $nm                             = $row2["product_name"];
    $rate                           = $row2["rate"];
    $quantity                       = $row2["quantity"];
    $total                          = $row2["total"];
    $prod                           = $row2["almasode"] . $row2["prcd"];

    $barcode = $row2["barcode"];
    $stock   = $row2["stock"];

    $photo = "../assets/images/product/70_75/" . $row2['image'];

    $discounttot    = $discounttot + $row2["discount"] * $quantity;
    $gtotal         = $gtotal + $total;
    $vatt           = $vatt + ($total * $row2["vat"] * .01);
    $deliverycharge = 100;
    $vat            = ($total * $row2["vat"] * .01);

    ?>                                             <tr>

									<td><?php echo $nm; ?> </td>

									<td><?php echo $prod; ?> </td>

									<td align="center"><?php echo number_format($rate, 2); ?></td>

									<td> <?php echo number_format($quantity, 0); ?></td>

									<td class="number"><?php echo number_format($total, 2); ?> </td>

									<td class="number"><?php echo number_format($vat, 2); ?> </td>

									<td align="right"><?php echo number_format(($total + $vat), 2); ?> </td>



								</tr>



								<?php }} ?>

								</tbody>

								<tfooter>

								<tr>


									<td class="noborder">&nbsp;</td>

									<td class="noborder">&nbsp;</td>

									<td class="noborder">&nbsp;</td>

									<th class="number text-right">Total</th>

									<th class="number"><?php echo number_format($gtotal, 2); ?> </th>

									<th class="number"><?php echo number_format($vatt, 2); ?> </th>

									<th class="number" align="right"><?php echo number_format(($gtotal + $vatt), 2); ?> </th>

								</tr>



								<tr>

									<td class="noborder">&nbsp;</td>

									<td class="noborder">&nbsp;</td>



									<td class="noborder">&nbsp;</td>

									<td colspan="3" align="right" cl ass="noborder"><b>Delivery Charge</b></td>

									<th class="number" align="right"> <?php echo number_format($shipping_charge, 2); ?> </th>



								</tr>

								<tr>

									<td class="noborder">&nbsp;</td>

									<td class="noborder">&nbsp;</td>



									<td class="noborder">&nbsp;</td>

									<td colspan="3" align="right" cl ass="noborder"><b>Billing Amount</b></td>

									<th class="number" align="right"><?php echo number_format(($shipping_charge + $gtotal + $vatt), 2); ?> </th>

								</tr>

								</tfooter>

								</table>

                                <?php if ($orderstatus == 4) { ?>

<br>
&nbsp;
										<table border="0" width="80%">
                                                <tr>
                                                    <td width="80%" class="text-nowrap">
														<b>Delivery Agent: </b> <?=$agentname ?>
														<br>
<br>
<br>

												  ------------------------------</td>
                                                    <td width="20%" class="text-nowrap">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="noborder" nowrap>Received By</td>
                                                    <td class="noborder" nowrap>&nbsp;</td>
                                                </tr>
											 </table>
                                  <?php } ?>


											<br>
<br>
<br>
											<div align="center"><b>Thank you for choosing <?= $comname ?>!<br>
                                                                    <?= $comweb ?></b><br>
                                                                    <?= $comemail ?><br>


											<hr>

											<i><?= $comaddress ?></i>
											</div>







                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>