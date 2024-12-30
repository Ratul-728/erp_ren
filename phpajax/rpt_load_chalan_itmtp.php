<?php 
//$id=13;
 
 $fd=$_POST['from_dt'];
 $td=$_POST['to_dt'];
 if($fd==''){$fd=date("d/m/Y");}
 if($td==''){$td=date("d/m/Y");}
    //$t=date("d/m/Y");)
 //$fdt="01/11/2020";
 //$tdt="08/11/2020";
?>
 <div class="panel panel-info" id="printableArea">
      		            
			            <div class="panel-body">
                            <div class="row">
                        		
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        
                                    </div>        
                                </div>
                        	    <div class="col-lg-3 col-md-6 col-sm-6">
                                     <div class="form-group">
                                        <label>    Almas Super Shop </label><br>
                                        <label >   Green Taj Center,8/A,Dhanmondi-15, Dhaka 1209. </label><br>
                                         <label for="cmbsupnm">   Stock Recipt Summay  </label><br>
                                         <label for="cmbsupnm">Date  From <?php echo $fd;?>   To  <?php echo $td;?> </label>
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

.tbl-bill tr th:last-child{width: 150px;}
.tbl-bill tr th:first-child{width: 150px;}
.tbl-bill tr th:nth-child(3),
.tbl-bill tr th:nth-child(4){width: 150px;}											
</style>


 											<table class="tbl-bill" width="100%" border="1" cellspacing="1" cellpadding="5">
                                             <thead>
                                                <tr style="background-color:#E7E7E7">
                                                    <th>Product Catagory </th>
                                                    <th>Challan Date</th>
                                                    <th>Challan NO</th>
                                                    <th>Advice No</th>
                                                    <th>Receved Date </th>
                                                    <th>Product</th>
                                                    <th>Barcode</th>
                                                    <th>Qty </th>
                                                    <th>Unit price </th>
                                                    <th>Total price </th>
                                                    <th>Expiry Date </th>
                                                </tr>
												  </thead>
												<tbody>
                                               
<?php 
$discounttot=0;$gtotal=0;$vat=0;
$qry2="SELECT p.poid,p.adviceno,DATE_FORMAT(p.orderdt,'%e/%c/%Y') orderdt,DATE_FORMAT(p.delivery_dt,'%e/%c/%Y') received_dt
,t.name cat,i.itemid,pr.name product,i.qty,i.unitprice,i.amount,i.barcode,DATE_FORMAT(i.expirydt,'%e/%c/%Y') expirydt
FROM  po p,poitem i,product pr,itemtype t where p.poid=i.poid and pr.id=i.itemid and pr.catagory=t.id
and p.delivery_dt BETWEEN STR_TO_DATE('".$fd1."','%d/%m/%Y') and  STR_TO_DATE('".$td1."','%d/%m/%Y')  order by t.name,p.delivery_dt, p.poid,i.itemid"; 
//where d.order_id='".$order_id."'"; 
$result2 = $conn->query($qry2); if ($result2->num_rows > 0) {while($row2 = $result2->fetch_assoc()) 
{
    $order_id=$row2["poid"];$adviceno=$row2["adviceno"]; $orderdt=$row2["orderdt"]; $received_dt=$row2["received_dt"];  
    $cat=$row2["cat"]; $product=$row2["product"]; $qty=$row2["qty"];$barcode=$row2["barcode"];$expdt=$row2["expirydt"];
    $unitprice=$row2["unitprice"];$amount=$row2["amount"]; $gt=$gt+$amount;
   
?>                                                  <tr> 
                                                    <td class="text-right"><?php echo $cat;?></td>
                                                     <td class="text-right"><?php echo $orderdt;?> </td>
                                                    <td class="text-right"><?php echo $order_id;?></td>
                                                    <td class="text-right"><?php echo $adviceno;?> </td>
                                                    <td class="text-right"><?php echo $received_dt;?> </td>
                                                    <td class="text-right"><?php echo $product;?> </td>
                                                    <td class="text-right"><?php echo $barcode;?> </td>
                                                    <td class="text-right"><?php echo number_format($qty,0);?> </td>
                                                    <td class="text-right"><?php echo number_format($unitprice,2);?></td>
                                                    <td class="text-right"><?php echo number_format($amount,2);?></td>
                                                    <td class="text-right"><?php echo $expdt;?> </td>
                                                </tr>

<?php }}?>
                                                </tbody>
												<tfooter>
												<tr> 
                                                   <td class="noborder">&nbsp  ;</td>
                                                   <td class="noborder">&nbsp;</td>
                                                   <td class="noborder">&nbsp;</td>
                                                   <td class="noborder">&nbsp;</td>
                                                   <td class="noborder">&nbsp;</td>
                                                   <td class="noborder">&nbsp;</td>
                                                   <td class="noborder">&nbsp;</td>
                                                   <td class="noborder">&nbsp;</td>
                                                    <td class="text-right">Total:</td>
                                                    <th class="text-right"><?php echo number_format($gt,2);?></th>
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