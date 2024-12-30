<?php

//$id=13;
/* 
SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
    ,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr
    ,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent,concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno,org.email
    FROM  soitem o left join orderstatus s on o.orderstatus=s.id
     left join organization org on o.organization=org.id
    left join district d on org.district=d.id
    left join area a on org.area=a.id
    left join contact c on o.customer=c.id
    left join district d1 on c.district=d1.id
    left join area a1 on c.area=a1.id
	left join deveryagent da on o.deliveryby=da.id
	
	*/
$qry = "SELECT cl.`id`, cl.`trdt`, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter` , cl.`chequedt`

                FROM allpayment cl left join organization c on cl.customer=c.id left join costcenter cc on cl.costcenter=cc.id left join transmode tr on cl.transmode=tr.id 
                
                where cl.id = " . $rpid;

// echo $qry; die;

if ($conn->connect_error) {

    echo "Connection failed: " . $conn->connect_error;

} else {

    $result = $conn->query($qry);

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $trdt = $row['trdt'];

            $transmode = $row['transmode'];

            $transref = $row['transref'];

            $customer = $row['customer'];

        	$naration = $row['naration'];

            $amount = $row['amount'];

        	$costcenter = $row['costcenter'];
        	
        	$cqdt = $row["chequedt"];

        }

    }

}

?>

 <div class="panel panel-info">


			            <div class="panel-body">

                    <div id="printableArea">

								<div class="col-sm-12">

									<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;">
							  <tbody>
								<tr>
								  <td width="50%" scope="col"><img src="./assets/images/pro.png" width="205" height="48" alt=""/></td>
								  <td width="50%" scope="col" align="right"><div style="font-family:Stencil, impact;font-size:40px;"><?=($orderstatus == 4) ? 'Payment Receipt' : 'VIEW Payment Receipt' ?></div></td>
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
								  <?php echo $deladr; ?>
								  <br>
								  <?php echo $email; ?>
								</td>
								  <td align="right"> <b>Invoice No:</b> <?php if ($invoiceno != '') { ?><?php echo $invoiceno; ?><?php } ?>
							<br>
							<b>Order No:</b> <?php echo $order_id; ?><br>
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

                                              <div class="row new-layout-header">
      		                            <div class="col-lg-10 col-md-10">
      		                                <div class="form-group">
                                                <!--<label for="ref">Subject*</label> -->
                                                <input type="text" class="form-control com-nar white-bg" id="descr" name="descr" value="<?php echo $naration; ?>" autofocus="autofocus"  placeholder="Add a Narration" readonly>
                                            </div>
	                                   <!--     <h4></h4>
	                                        <hr class="form-hr">  -->

		                                    <input type="hidden"  name="exid" id="exid" value="<?php echo $exid; ?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
	                                    </div>
                                        <div class="col-lg-2 col-md-2 new-layout-amount ">

                                            <div class="form-group">
                                                <label for="amt">Amount </label>
                                                <input type="text" placeholder="Tk 0.00" class="form-control amount-fld white-bg" id="amt" name="amt" value="<?php echo $amount; ?>" readonly>
                                            </div>

                                        </div>
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="cd">Date</label>
                                                <input type="text" class="form-control white-bg" id="" name="" value="<?= $trdt ?>" readonly>
                                                
                                        </div>

                                        

                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmborg">Organization</label>
                                                <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                   <input type="text" class="form-control white-bg" id="" name="" value="<?= $customer ?>" readonly>
                                            </div>
                                        </div>




                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbmode"> Transfer Mode</label>
                                               <input type="text" class="form-control white-bg" id="" name="" value="<?= $transmode ?>" readonly>
                                        </div>
                                    </div>
                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Reference/Cheque No.</label>
                                                   <input type="text" class="form-control white-bg" id="" name="" value="<?= $transref ?>" readonly>
                                            </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="chqdt">Cheque Date </label>
                                          <input type="text" class="form-control white-bg" id="" name="" value="<?= $cqdt ?>"  readonly>
                                    </div>
                                    

                                        <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmborg">Invoice*</label>
                                                
                                              <input type="text" class="form-control white-bg" id="" name="" value="" readonly>
                                            </div>
                                        </div-->

      	                             
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcc"> Cost Center</label>
                                               <input type="text" class="form-control white-bg" id="" name="" value="<?= $costcenter ?>" readonly>
                                            </div>
                                        </div>
                                        
                                        

      	                                <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="descr">Narration</label>
                                                <input type="text" class="form-control" id="d escr" name="descr" value="<?php echo $naration; ?>">
                                            </div>
                                        </div -->

                                    </div>

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






											<br>
<br>
<br>
											<div align="center"><b>Thank you for choosing <?= $comname ?>!<br>
<?= $comweb ?></b><br>
<?= $comemail ?><br>


											<hr>

											<i><?= $comaddress ?>
												Contact: <?= $comcontact ?></i>
											</div>







                                        </div>

                                    </div>

                                </div>
                    </div>
                    
                    <input type="button" class="btn btn-lg btn-info" onclick="printDiv('printableArea')" value="&nbsp;&nbsp;&nbsp;Print&nbsp;&nbsp;&nbsp;" />

                            </div>

                        </div>

                    </div>