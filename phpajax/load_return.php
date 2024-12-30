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
    where o.id= " . $rid;
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
            $deleveryagent   = $row["agentname"];
            $hrid            = '1';
            
            $deliveryaddress = $row["remarks"];
        }
    }
}

?>
 <div class="panel panel-info" id="printableArea">
                       <h1 style="text-align:center;margin-bottom:0"><?= $comname ?></h1>
						<h6 style="text-align:center;margin-top:0"><?= $comaddress ?></h6>
                        <h5 style="text-align:center" id="order_review_heading">Order Invoice</h5>
      		            <div class="panel-heading"><h1>Order #<?php echo $order_id; ?> details</h1>
      		            <p><h3>Payment via <?php echo $payment_mood; ?> </h3></p>
      		            </div>

			            <div class="panel-body">
                            <div class="row">
                        		<div class="col-sm-12">
	                                <hr class="form-hr">
	                                 <input type="hidden"  name="ordid" id="ordid" value="<?php echo $ordid; ?>">
	                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>" >

	                            </div>
                                <div class="col-sm-12">
                                    <h4>Customer Information  </h4>
                                    <hr class="form-hr"></hr>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="po_id">Customer Name    #<?php echo $cusnm; ?></label></br>
                                        <label for="po_dt">Order Date       #<?php echo $orderdt; ?></label></br>
                                        <label for="po_dt">Phone            #<?php echo $phone; ?></label>
                                    </div>
                                </div>
                        	    <div class="col-lg-3 col-md-6 col-sm-6">
                                     <div class="form-group">
                                        <label for="cmbsupnm">Status # </label></br>
                                         <label for="cmbsupnm"><?php echo $ost; ?> </label>
                                       <!-- <div class="form-group styled-select">
                                            <select name="cmbsupnm" id="cmbsupnm" class="form-control" >
<?php
$qry1    = "SELECT `id`, `name`  FROM `orderstatus` order by name";
$result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
    $tid = $row1["id"];
    $nm  = $row1["name"];
    ?>
                                                <option value="<?php echo $tid; ?>" <?php if ($orderstatus == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
<?php }} ?>
                                            </select>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="input-group">
                                        <label for="email">Billing Address  #</label></br>
                                        <label for="email"><?php echo $cusaddr; ?></label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="input-group">
                                        <label for="email">Delivery Address  #</label></br>
                                        <label for="email"><?php echo $deliveryaddress; ?></label>
                                    </div>
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

.tbl-bill  .return-head {
    width: 10px!important;
}
</style>


 											<table class="tbl-bill" width="100%" border="1" cellspacing="5" cellpadding="5">
                                             <thead>
                                                <tr style="background-color:#E7E7E7">
                                                    <th>Photo </th>
                                                    <th>Item Name</th>
                                                    <th>Orderd </th>
                                                    <th>Delivered </th>
                                                    <th>Returned</th>
                                                    <th>Return Qty <span class="redstar">*</span></th>
                                                    <th>Return Warehouse<span class="redstar">*</span></th>
                                                    <th class="return-head">Return </th>
                                                </tr>
												  </thead>
												<tbody>

<?php
$discounttot = 0;
$gtotal      = 0;
$vat         = 0;
$vatt        = 0;
$islock=0;$maxretqty=0;

$qry2 = "SELECT distinct d.socode,d.id,d.sosl, d.productid product_id,p.name product_name,p.image,d.vat,d.qty quantity,d.deliveredqty,d.dueqty,d.otc rate,p.cost,p.barcode
,0 discount,(d.qty*d.otc) total,d.barcode,'' almasode,lpad(p.id,8,0) prcd
,ifnull((SELECT freeqty FROM `stock` where product=p.id),0) stock,d.return_qty
FROM soitemdetails d left join item p on d.productid=p.id
where d.socode='" . $order_id . "'";
//echo $qry2;die;
$result2 = $conn->query($qry2);if ($result2->num_rows > 0) {while ($row2 = $result2->fetch_assoc()) {$dtlsid = $row2["id"];
    
    $product                        = $row2["product_id"];
    $nm                             = $row2["product_name"];
    $rate                           = $row2["rate"];
    $cost                           = $row2["cost"];
    $bc                             = $row2["barcode"];
    $quantity                       = $row2["quantity"];
    $delquantity                    = $row2["deliveredqty"];
    $duequantity                    = $row2["dueqty"];
    $return_qty                     = $row2["return_qty"];
    $total                          = $row2["total"];
    $prod                           = $row2["almasode"] . $row2["prcd"];
    $sl = $row2["sosl"];
    $barcode = $row2["barcode"];
    $stock   = $row2["stock"];

   // $photo = "./common/upload/item/" . $row2['image'].".jpg"; //"../assets/images/product/70_75/" . $row2['image'];
	$photo		= (strlen($row2['image'])>0)?"assets/images/products/300_300/".$row2["image"]:"assets/images/products/placeholder.png";
																									 

    $discounttot    = $discounttot + $row2["discount"] * $quantity;
    $gtotal         = $gtotal + $total;
    $vatt           = $vatt + ($total * $row2["vat"] * .01);
    $deliverycharge = 100;
    $vat            = ($total * $row2["vat"] * .01);
    if($delquantity>$return_qty){$islock=0;$maxretqty=$delquantity-$return_qty;} else {$islock=1;$maxretqty=0;}

?>                                              <tr class="itm_ret" <?=($islock==1)?"style='background-color:grey'":"" ?>>
                                                    <td><img src='<?php echo $photo; ?>' width="50" height="50"></td>
                                                    <td><?php echo $nm; ?> </td>
                                                    <td><?php echo number_format($quantity, 0); ?></td>
                                                    <td><?php echo number_format($delquantity, 0); ?></td>
                                                    <td><?php echo number_format($return_qty, 0); ?></td>
                                                    <td style="width: 100px">
														<input type="number" min="0" max="<?=$maxretqty ?>" class="form-control return_qty" <?=($islock==1)?"disabled":"" ?>    value="0" id="return_qty" name="return_qty[]">															
													</td>
                                                    <td>
                                                        <div class="form-group styled-select">
                                                            <select name="cmbbranch[]" id="cmbbranch" class="form-control store" <?=($islock==1)?"disabled":"" ?>>
                                                                <option value="">Warehouse</option>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `branch` where status = 'A' order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                                                <option value="<?php echo $tid; ?>" <?php if ($branch == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td><input type="button" data-rate="<?=$rate ?>" data-cost="<?=$cost ?>" data-bc="<?=$bc ?>" data-oid="<?=$order_id ?>" data-tid="<?=$dtlsid ?>" data-pid="<?=$product ?>" data-sosl="<?=$sl ?>" class="btn btn-danger btn-return" i d="btnReturn<?=$dtlsid ?>" value="Return" /> </td>
                                                </tr>

<?php }} ?>
                                                </tbody>
												<!--tfooter>
												<tr>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <th class="text-right">Total</th>
                                                    <th><?php echo number_format($gtotal, 2); ?> </th>


                                                </tr>

                                                <tr>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <th class="text-right"> Delivery Charge </th>
                                                    <th> <?php echo number_format($deliverycharge, 2); ?> </th>

                                                </tr>
                                                <tr>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
													<th class="text-right">Billing Amount</th>
                                                    <th><?php echo number_format(($deliverycharge + $gtotal), 2); ?> </th>

                                                </tr>
													</tfooter -->
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>