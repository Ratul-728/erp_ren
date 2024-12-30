<?php 

$id = $_GET["id"];

$qry="SELECT `id`, `dealtype`, `name`, `lead`, `leadcompany`, `value`, `curr`, `stage`, `status`, `remarks`, `lostreason`, `makeby`, `makedate`, DATE_FORMAT(`dealdate`, '%d/%m/%Y') `dealdate`,`accmgr`, DATE_FORMAT(`comercialdate`, '%d/%m/%Y') `comercialdate`, DATE_FORMAT(`nextfollowupdate`, '%d/%m/%Y') fldt FROM `deal` where id= ".$id; 
    

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

                            $iid=$row["id"];$name=$row["name"]; $lead=$row["lead"];$leadcompany=$row["leadcompany"];$value=$row["value"]; $currency=$row["curr"];
                            $stage=$row["stage"]; $status=$row["status"];  $details=$row["remarks"];  $lostreason=$row["lostreason"]; $dealdate=$row["dealdate"];
                            $accmgr=$row["accmgr"]; $comercialdatee=$row["comercialdate"];  $flupdt=$row["fldt"]; 
                            
                            //ORganization name
                            $qryOrgNm = "SELECT name FROM `organization` where id = ".$leadcompany;
                            $resultOrgNm = $conn->query($qryOrgNm);
                            while($rowOrgNm = $resultOrgNm->fetch_assoc()){
                                $leadOrgNm = $rowOrgNm["name"];
                            }
                            
                            //Contact Name
                            
                            $qryConNm = "SELECT `id`, `name` , `phone` , `email`  FROM `contact`  WHERE id = ".$lead;
                            $resultConNm = $conn->query($qryConNm);
                            while($rowConNm = $resultConNm->fetch_assoc()){
                                $ConNm = $rowConNm["name"];
                                $conphone = $rowConNm["phone"];
                                $conemail = $rowConNm["email"];
                                
                            }
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
								  <td width="50%" scope="col"><img src="images/logo-bitcables.png" width="205" height="48" alt=""/></td>
								  <td width="50%" scope="col" align="right"><div style="font-family:Stencil, impact;font-size:40px;">INVOICE</div></td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								</tr>								  
								<tr>
								  <td><b>Deal Name: <?php echo $name;?>	</b><br>
								    Organization: <?php echo $leadOrgNm;?>
								    Deal Date: <?= $dealdate ?>
									<br>
								</td>
								  <td align="right"> <b>Contact Name:</b> <?= $ConNm ?> 
							<br>
							<b >Contact Phone:</b> <?php echo $conphone;?><br>
							<b>Contact Email:</b> <?php echo $conemail;?><br>
							
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

                                            <h4>Deal Information  </h4>

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





							<table class="tbl-bill color-table" width="100%" border="1" cellspacing="5" cellpadding="5" style="font-size: 12px;">

								<thead>

								<tr style="background-color:#E7E7E7">

									<th class="item_name"  style="min-width: 250px">Item Name</th>

									<th class="product_code text-nowrap">Quantity</th>

									<th class="rate text-center">OTC</th>

									<th class="quantity" style="min-width: 80px!important;">Quantity</th>

									<th class="order_amount text-center">MRC </th>

									<!--th class="vat text-center">Currency</th-->

									<th class="total text-center">Unit Total</th>

								</tr>

								  </thead>

								<tbody>



								<?php

								$itmdtqry="SELECT a.`id`, a.`socode`, a.`sosl`, a.`productid`,b.`name` itnm, a.`mu`, round(a.`qty`,0) qty,round(a.`qtymrc`,0)qtymrc, round(a.`otc`,2) otc, round(a.`mrc`,2)mrc,a.`scale`,a.`probability` ,a.`currency` FROM `dealitem` a,`item` b WHERE a.`productid`=b.`id` and   `socode`='".$iid."'";
                                $resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) 
                                    {   while($rowitmdt = $resultitmdt->fetch_assoc()) 
                                              { 
                                                  $itmdtid= $rowitmdt["productid"]; $itmnm=$rowitmdt["itnm"];  $itdmu=$rowitmdt["mu"]; $itdqu=$rowitmdt["qty"];$itdqumrc=$rowitmdt["qtymrc"]; $itdotc=$rowitmdt["otc"]; $itdmrc=$rowitmdt["mrc"]; $itdscale=$rowitmdt["scale"];$itdprob=$rowitmdt["probability"];$currency=$rowitmdt["currency"];
                                                  $itdtot=($itdqu*$itdotc)+($itdqumrc*$itdmrc); $itdgt=$itdgt+$itdtot;
								?>                                             <tr> 

									<td><?php echo $itmnm;?> </td>

									<td><?php echo $itdqu;?> </td>

									<td align="center"><?= $itdotc ?></td>

									<td> <?php echo $itdqumrc;?></td>

									<td class="number"><?php echo $itdmrc;?> </td>

									<td class="number"><?php echo $itdtot;?> </td>

									<!--td align="right"><?php echo number_format(($total+$vat),2);?> </td-->



								</tr>



								<?php }}?>

								</tbody>

								<tfooter>

								<tr> 


									<td class="noborder">&nbsp;</td>

									<td class="noborder">&nbsp;</td> 

									<td class="noborder">&nbsp;</td>

									<td class="noborder">&nbsp;</td>

									<th class="number text-right">Total</th>

									<th class="number"><?php echo $itdgt;?> </th>


								</tr>

								</tfooter>

								</table>													


											
											<br>
<br>
<br>
											<div align="center"><b>Thank you for choosing BitFlow!<br>
bithut.com.bd</b><br>
orders@bithut.com.bd<br>

											
											<hr>
												
											<i>House #116 Max Lakeview, Gulshan-Badda Link Road Dhaka 1212,  
												Contact: +880 1746 426547</i>		
											</div>
											
											

                                           

											

                                        </div> 

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div> 