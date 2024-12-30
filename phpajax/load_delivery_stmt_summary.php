<?php 
//$id=13;
 $qry="select `name`,`address`,`contactno`,`email` from deveryagent where `id`=".$agnt; 
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
                            $name=$row["name"];$address=$row["address"];$contactno=$row["contactno"]; $email=$row["email"]; 
                           $hrid='1';
                        }
                }
            }

?>
 <div class="panel panel-info" id="printableArea">
     
                        <h1 style="text-align:center;margin-bottom:0">Almas Super Shop</h1>
						<h6 style="text-align:center;margin-top:0">Green Taj Center,8/A,Dhanmondi-15, Dhaka 1209</h6>
                        <h5 style="text-align:center" id="order_review_heading">Delivery Statement</h5>
      		            
			            <div class="panel-body">
                            <div class="row">
                        		<div class="col-sm-12">
	                                <hr class="form-hr">
	                                 <input type="hidden"  name="ordid" id="ordid" value="<?php echo $ordid;?>"> 
	                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>" >
	                                 
	                            </div> 
                                <div class="col-sm-12">
                                    <h4>Agent Information  </h4>
                                    <hr class="form-hr"></hr> 
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="po_id">Agent Name&nbsp;&nbsp;&nbsp;&nbsp;#&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $name;?></label></br>
                                        <label for="po_dt">Contact&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $contactno;?></label></br>
                                        <label for="po_dt">Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $email;?></label></br>
                                        <label for="po_dt">Address&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $address;?></label>
                                    </div>        
                                </div>
                        	           
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="input-group">
                                        <label for="email">Date&nbsp;&nbsp;&nbsp;&nbsp;#&nbsp;&nbsp;</label>
                                        <label for="email"><?php echo date('d/m/Y');?></label>
                                    </div>     
                                </div> 
                                <div class="po-product-wrapper"> 
                                    <div class="color-block">
 		                                <div class="col-sm-12">
                                            <h4>Order Information  </h4>
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
                                                    <th>SL </th>
                                                    <th>Order No</th>
                                                    <th>Invoice No.</th>
                                                    <th>Order Date</th>
                                                    <th>Customer </th>
                                                    <th>Address</th>
                                                    <th>Phone</th>
                                                    <th>Payment Mode </th>
                                                    <th>Amount</th>
                                                </tr>
												  </thead>
												<tbody>
                                               
<?php 
$sl=1;$gtotal=0;$vat=0;$vatt=0;
$qry2="SELECT o.id,o.name cus,o.order_id,o.invoiceno,o.phone,concat(o.`address`,',',d.name,',',a.name) adrss,o.payment_mood
,o.amount,o.vat_amount,o.shipping_charge,DATE_FORMAT(o.`order_date`,'%e/%c/%Y %T') order_date
FROM orders o left join districts d on o.district=d.id
left join areas a on o.area=a.id 
where  o.orderstatus=4 and `deleveryagent`='".$agnt."'"; 
$result2 = $conn->query($qry2); if ($result2->num_rows > 0) {while($row2 = $result2->fetch_assoc()) 
{$tid= $row2["id"];  $order_id=$row2["order_id"];$invoiceno=$row2["invoiceno"];
$order_date=$row2["order_date"];$cus=$row2["cus"];$adrss=$row2["adrss"];$phone=$row2["phone"];$amount=$row2["amount"];$payment_mood=$row2["payment_mood"];
$sl++;$gtotal=$gtotal+$amount;

?>                                                  <tr> 
                                                    <td><?php echo $sl;?> </td>
                                                    <td><?php echo $order_id;?> </td>
                                                    <td><?php echo $invoiceno;?> </td>
                                                    <td><?php echo $order_date;?> </td>
                                                    <td> <?php echo $cus;?></td>
                                                    <td><?php echo $adrss;?></td>
                                                    <td><?php echo $phone;?></td>
                                                    <td><?php echo $payment_mood;?> </td>
                                                    <td><?php echo number_format($amount,2);?> </td>
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
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <th class="text-right">Total</th>
                                                    <th><?php echo number_format($gtotal,2);?> </th>
                                                    
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
                                                    <td class="noborder">&nbsp;</td>
                                                    
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
                                                    <td class="noborder">&nbsp;</td>
                                                    
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
                                                    <td class="noborder">&nbsp;</td>
                                                    
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
                                                    <td class="noborder">&nbsp;</td>
                                                    
                                                </tr>
                                                <tr> 
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">-------------------</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">-------------------</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    
                                                </tr>
                                                <tr> 
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">Received By</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <th class="noborder">&nbsp;</th>
                                                    <th class="noborder">Issued By </th>
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