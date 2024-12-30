<?php 
//$id=13;
 
 //$fd=$_POST['from_dt'];
// $td=$_POST['to_dt'];
 //if($fd==''){$fd=date("d/m/Y");}
 //if($td==''){$td=date("d/m/Y");}
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
                                        <label for="cmbsupnm">    Bitht.com.bd </label><br>
                                         <label for="cmbsupnm">   Revenue   </label><br>
                                        <!-- <label for="cmbsupnm">Date  <?php echo $fd;?>   To  <?php echo $td;?> </label>-->
                                    </div>          
                                </div> 
                                
                                <div class="po-product-wrapper"> 
                                    <div class="color-block">
 		                                
                                        <div class="col-sm-12">
                                            
<!--style>
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
</style -->


 											<table class="tbl-bill" width="100%" border="1" cellspacing="1" cellpadding="5">
                                             <thead>
                                                <tr style="background-color:#E7E7E7">
                                                    <th class="text-center">Sl</th>
                                                    <th class="text-center">Order Number</th>
                                                    <th class="text-center">Customer</th>
                                                    <th class="text-center">OTC </th>
                                                    <th class="text-center">MRC </th>
                                                    <th class="text-center">Revenue</th>
                                                    <th class="text-center">Eraning</th>
                                                    <th class="text-center">Costing</th>
                                                    <th class="text-center">Margin</th>
                                                </tr>
												  </thead>
												<tbody>

<?php
$mart=0;$otct=0;$mrct=0;$revt=0;$inct=0;$costt=0;$revt=0;$i=0;
$qry2="SELECT s.id,s.socode,o.name organization 
,sum(d.qty*d.otc) otc,sum(d.qtymrc*d.mrc) mrc
,(select  sum(`invoiceamt`) inv from invoice where soid=s.socode) rev
,(select  sum(`paidamount`) inv from invoice where soid=s.socode) inc
,(select  sum(`amount`)  from expense where soid=s.id) cost
FROM soitem s left join organization o on s.organization=o.id
 left join soitemdetails d on s.socode=d.socode  group by s.id,s.socode,o.name ";
$result2 = $conn->query($qry2); if ($result2->num_rows > 0) {while($row2 = $result2->fetch_assoc()) 
{
    $socode=$row2["socode"];$org=$row2["organization"];  $otc=$row2["otc"];$mrc=$row2["mrc"]; $rev=$row2["rev"]; $inc=$row2["inc"];$cost=$row2["cost"];
    $mar=$inc-$cost;$otct=$otct+$otc;$mrct=$mrct+$mrc;$inct=$inct+$inc;$costt=$costt+$cost;$mart=$mart+$mar;$revt=$revt+$rev;$i++;
?>                                                  <tr> 
                                                        <td class="text-right"><?php echo $i;?></td>
                                                        <td class="text-right"><?php echo $socode;?> </td>
                                                        <td class="text-right"><?php echo $org;?> </td>
                                                        <td class="text-right"><?php echo number_format($otc,2)?></td>
                                                        <td class="text-right"><?php echo number_format($mrc,2);?></td>
                                                        <td class="text-right"><?php echo number_format($rev,2);?> </td>
                                                        <td class="text-right"><?php echo number_format($inc,2);?> </td>
                                                        <td class="text-right"><?php echo number_format($cost,2);?> </td>
                                                        <td class="text-right"><?php echo number_format($mar,2);?> </td>
                                                    </tr>

<?php }}?>
                                                </tbody>
												<tfooter>
												<tr>
                                                    <th class="noborder">&nbsp;</th>
                                                    <th class="noborder">&nbsp;</th>
                                                    <th class="noborder">Total</th>
                                                    <th class="text-right"><?php echo number_format($otct,2);?> </th>
                                                    <th class="text-right"><?php echo number_format($mrct,2);?> </th>
                                                    <th class="text-right"><?php echo number_format($revt,2);?> </th>
                                                    <th class="text-right"><?php echo number_format($inct,2);?> </th>
                                                    <th class="text-right"><?php echo number_format($costt,2);?> </th>
                                                    <th class="text-right"><?php echo number_format($mart,2);?> </th>
                                                </tr>
												</tfooter>
                                            </table>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 