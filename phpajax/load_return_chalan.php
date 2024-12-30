<?php 

//$id=13;

 $qry="select id, `chalanno`,DATE_FORMAT( `returndt`,'%e/%c/%Y') `returndt`,format(`totalamount`,2) totalamount from returnpo  where id= ".$oid; 

   // echo $qry; die;

        if ($conn->connect_error) {

            echo "Connection failed: " . $conn->connect_error;

            }

        else

            {

                $result = $conn->query($qry); 

                if ($result->num_rows > 0)

                {

                    while($row = $result->fetch_assoc()) 

                        { 

                            $ordid=$row["id"];$chalanno=$row["chalanno"];$returndt=$row["returndt"]; $totalamount=$row["totalamount"]; 

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
								  <td width="50%" scope="col"><img src="../assets/images/almas_logo.png" width="205" height="48" alt=""/></td>
								  <td width="50%" scope="col" align="right"><div style="font-family:Stencil, impact;font-size:40px;">RETURN CHALAN</div></td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								</tr>								  
								<tr>
								  <td><b> </b><br>
								  <?php echo '';?>	<br>
									Phone: <?php echo '';?>
									<br>
								  <?php echo '';?>
								  <br>
								  <?php echo '';?>
								</td>
								  <td align="right"> <b>Return Chalan No:</b> <?php if($chalanno!=''){ ?><?php echo $chalanno;?><?php }?> 
							<br>
							<b>Return Date:</b> <?php echo $returndt;?><br>
							<b>Total Amount:</b> <?php echo $totalamount;?></br>
									
									</td>
								</tr>
							  </tbody>
							</table>
	 
								</div>
							<br>


							
														
	                                 <input type="hidden"  name="ordid" id="ordid" value="<?php echo $ordid;?>"> 

	                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>" >							
							

                               

                                

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
                                    <th class="item_name"  style="min-width: 250px">Oginal Chalan No</th>
									<th class="item_name"  style="min-width: 250px">Product Name</th>
									<th class="product_code text-nowrap">Product Code</th>
									<th class="product_code text-nowrap">Bar Code</th>
									<th class="rate text-center">Rate</th>
									<th class="quantity" style="min-width: 80px!important;">Quantity</th>
									<th class="order_amount text-center">Amount </th>
									<th class="total text-center">Remarks</th>

								</tr>

								  </thead>

								<tbody>



								<?php
								
								$discounttot=0;$gtotal=0;$vat=0;$vatt=0;

								$qry2="select r.`chalanno`,r.`orginalchalanno`,r.`barcode`,r.`product`,p.name product_name,p.image,r.`qty`,r.`cp`,r.`unittotal`,r.`remarks`,c.almasode,lpad(p.id,8,0) prcd
from returnpoitem r left join product p on r.product=p.id left join catagorygrouping cg on p.catagory=cg.itemtype	left join  catagory c on cg.itemcatagory=c.id
								where r.chalanno='".$chalanno."'"; 

								$result2 = $conn->query($qry2); if ($result2->num_rows > 0) {while($row2 = $result2->fetch_assoc()) 

								{$tid= $row2["id"];  $nm=$row2["product_name"];$rate=$row2["cp"];$quantity=$row2["qty"];$total=$row2["unittotal"];$prod=$row2["almasode"].$row2["prcd"];

								$barcode=$row2["barcode"];$rem=$row2["remarks"];$orginalchalan=$row2["orginalchalanno"];

								$photo="../assets/images/product/70_75/".$row2['image'];

							     $gtotal=$gtotal+$total;

								?> 
							    <tr> 

									<td><?php echo $orginalchalan;?> </td>
									<td><?php echo $nm;?> </td>
									<td><?php echo $prod;?> </td>
									<td><?php echo $barcode;?> </td>
									<td align="center"><?php echo number_format($rate,2);?></td>
									<td> <?php echo number_format($quantity,0);?></td>
									<td class="number"><?php echo number_format($total,2);?> </td>
									<td class="number"><?php echo $rem;?> </td>
								</tr>



								<?php }}?>

								</tbody>

								<tfooter>

								<tr> 

									<td class="noborder">&nbsp;</td>
                                    <td class="noborder">&nbsp;</td>
									<td class="noborder">&nbsp;</td> 
                                    <td class="noborder">&nbsp;</td> 
									<td class="noborder">&nbsp;</td>
									<th class="number text-right">Total</th>
									<th class="number"><?php echo number_format($gtotal,2);?> </th>

								</tr>



							

								</tfooter>

								</table>													

                                <?php if($orderstatus==4){ ?>                

<br>
&nbsp;
										<table border="0" width="80%">
                                                <tr> 
                                                    <td width="80%" class="text-nowrap">
														<b>Delivery Agent: </b> <?=$agentname?> 
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
                                  <?php }?>

											
											<br>
<br>
<br>
											<div align="center"><b>Thank you for choosing Almas Supershop!<br>
almas.com.bd</b><br>
orders@almas.com.bd<br>

											
											<hr>
												
											<i>Green Taj Center,8/A,Dhanmondi-15, Dhaka 1209,  
												Contact: +880 1746 426547</i>		
											</div>
											
											

                                           

											

                                        </div> 

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div> 