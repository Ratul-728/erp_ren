<?php
//$id=13;

$fd = $_POST['from_dt'];
$td = $_POST['to_dt'];
if ($fd == '') {$fd = date("d/m/Y");}
if ($td == '') {$td = date("d/m/Y");}
//$t=date("d/m/Y");)
//$fdt="01/11/2020";
//$tdt="08/11/2020"; ?>
 <div class="panel panel-info" id="printableArea">

			            <div class="panel-body">
                            <div class="row">

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-group">

                                    </div>
                                </div>
                        	    <div class="col-lg-3 col-md-6 col-sm-6">
                                     <div class="form-group">
                                        <label for="cmbsupnm">   Bithut Limited </label><br>
                                        <label for="cmbsupnm">   116 Max Lakeview, Gulshan-Badda link road, Dhaka </label><br>

                                         <label for="cmbsupnm">   Order Delivery Data </label><br>
                                         <label for="cmbsupnm">Date  <?php echo $fd; ?>   To  <?php echo $td; ?> </label>
                                    </div>
                                </div>

                                <div class="po-product-wrapper">
                                    <div class="color-block">

                                        <div class="col-sm-12">

<style>
.tbl-bill{
    border: 0px solid #000;
}

.tbl-bill th{padding: 5px 10px; border: 1px solid #000; border-bottom:1px solid black;}
.tbl-bill tbody > tr   td{padding: 0px 10px; border: 1px solid #000; border-bottom:1px solid black;}

.tbl-bill .noborder{border: 0;}

.tbl-bill tr th:last-child{width: 100px;}
.tbl-bill tr th:first-child{width: 50px;}
.tbl-bill tr th:nth-child(2){width: 300px;}
.tbl-bill tr th:nth-child(3){width: 50px;}
.tbl-bill tr th:nth-child(4){width: 100px;}
</style>


 											<table class="tbl-bill" width="100%" border="1" cellspacing="1" cellpadding="5">
                                             <thead>
                                                <tr style="background-color:#E7E7E7">
                                                    <th>Sl.</th>
                                                    <th>Sales Order No</th>
                                                    <th>Order Date</th>
                                                    <th>Product</th>
                                                    <th>Qty.</th>
                                                    <th>Unit Price</th>
                                                    <th>Cost Price</th>
                                                    <th>Discount</th>
                                                    <th>Sale Price</th>
                                                    <th>Vat</th>
                                                    <th>Net Total </th>
                                                    <th>Payment Mode</th>
                                                    <th>Order Status </th>
                                                    <th>Delivery Date </th>
                                                     <th>Delivery Agent </th>
                                                </tr>
												  </thead>
												<tbody>

<?php
$gd     = 0;
$gtotal = 0;
$vat    = 0;
$sl     = 0;
$gcp    = 0;
$gnet   = 0;
$qry2   = "SELECT o.order_id,DATE_FORMAT(o.order_date,'%e/%c/%Y %T') order_date,o.customer_id,o.amount,o.discount_total,o.vat_amount,(o.amount+o.vat_amount) tot
,o.payment_mood,o.orderstatus,o.status,s.name ost, o.orderstatus,DATE_FORMAT(o.deliverydt,'%e/%c/%Y') deliverydt
,d.product_name,d.product_id,d.quantity,d.rate,d.discount,d.vat,d.total,p.mrp,a.name agent
FROM orders o left join orderstatus s on o.orderstatus=s.id
 left join order_detail d on o.order_id=d.order_id
 left join product p on d.product_id=p.id
 left join deveryagent a on o.deleveryagent=a.id
 where o.orderstatus=5 and o.order_date BETWEEN STR_TO_DATE('" . $fd . "','%d/%m/%Y') and  STR_TO_DATE('" . $td . "','%d/%m/%Y')";
//where d.order_id='".$order_id."'";
$result2 = $conn->query($qry2);if ($result2->num_rows > 0) {while ($row2 = $result2->fetch_assoc()) {
    $order_id       = $row2["order_id"];
    $payment_mood   = $row2["payment_mood"];
    $orderdt        = $row2["order_date"];
    $ost            = $row2["ost"];
    $pnm            = $row2["product_name"];
    $qty            = $row2["quantity"];
    $rate           = $row2["rate"];
    $dscnt          = $row2["discount"];
    $vat_u          = $row2["vat_u"];
    $total_u        = $row2["total"];
    $amount         = $row2["amount"];
    $discount_total = $row2["discount_total"];
    $vat_amount     = $row2["vat_amount"];
    $agent          = $row2["agent"];
    $cp             = $qty * $rate;
    $tot            = $row2["tot"];
    $deliverydt     = $row2["deliverydt"];
    $orderstid      = $row2["orderstatus"];
    $net            = $total_u + $vat_u;
    $gam            = $gam + $amount;
    $gd             = $gd + $discount_total;
    $gv             = $gv + $vat_amount;
    $gt             = $gt + $tot;
    $gcp            = $gcp + $cp;
    $gnet           = $gnet + $net;
    $sl             = $sl + 1;

    ?>                                                  <tr>
                                                    <td class="text-right"><?php echo $sl; ?></td>
                                                    <td class="text-right"><?php echo $order_id; ?></td>
                                                    <td class="text-right"><?php echo $orderdt; ?> </td>
                                                    <td class="text-right"><?php echo $pnm; ?> </td>
                                                    <td class="text-right"><?php echo number_format($qty, 0); ?> </td>
                                                    <td class="text-right"><?php echo number_format($rate, 2); ?></td>
                                                    <td class="text-right"><?php echo number_format($cp, 2); ?></td>
                                                    <td class="text-right"><?php echo number_format($dscnt, 2); ?></td>
                                                    <td class="text-right"><?php echo number_format($total_u, 2); ?> </td>
                                                    <td class="text-right"><?php echo number_format($vat_u, 2); ?> </td>
                                                    <td class="text-right"><?php echo number_format($net, 2); ?> </td>
                                                    <td><?php echo $payment_mood; ?> </td>
                                                    <td><?php echo $ost; ?> </td>
                                                    <td><?php echo $deliverydt; ?> </td>
                                                    <td><?php echo $agent; ?> </td>
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
                                                    <td class="text-right">Total:</td>
                                                    <th class="text-right"><?php echo number_format($gcp, 2); ?></th>
                                                    <th class="text-right"><?php echo number_format($gd, 2); ?></th>
                                                    <th class="text-right"><?php echo number_format($gt, 2); ?> </th>
                                                    <th class="text-right"><?php echo number_format($gv, 2); ?> </th>
                                                    <th class="text-right"><?php echo number_format($net, 2); ?> </th>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                </tr>
												</tfooter>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>