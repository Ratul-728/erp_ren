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
                                        <label for="cmbsupnm">    Almas Super Shop </label><br>
                                        <label for="cmbsupnm">    Green Taj Center,8/A,Dhanmondi-15, Dhaka 1209. </label><br>
                                         <label for="cmbsupnm">   Group Based Stock Report </label><br>
                                        <!-- <label for="cmbsupnm">Date  <?php echo $fd;?>   To  <?php echo $td;?> </label>-->
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
                                                    <th>Group</th>
                                                    <th>Catagory</th>
                                                    <th>Type</th>
                                                    <th>Product </th>
                                                    <th>Free Qty </th>
                                                    <th>Cost Rate</th>
                                                    <th>Cost Price</th>
                                                    <th>MRP</th>
                                                    <th>MRP Total</th>
                                                </tr>
												  </thead>
												<tbody>
                                               
<?php 
$tcp=0;$tmp=0;
$qry2="SELECT g.name gn,c.title cn,t.name tn,p.name pn,s.freeqty,s.bookqty,s.costprice,p.mrp FROM stock s,product p ,catagorygrouping cg,itemgroup g,catagory c, itemtype t 
where s.product = p.id 
and p.catagory=cg.itemtype and cg.itemgroup=g.id and cg.itemcatagory=c.id and cg.itemtype=t.id
and (g.id=".$dagent." or ".$dagent." =0)
order by g.name,c.title ,t.name,p.name";
//where o.order_date BETWEEN STR_TO_DATE('".$fd."','%d/%m/%Y') and  STR_TO_DATE('".$td."','%d/%m/%Y')";
//where d.order_id='".$order_id."'"; 
$result2 = $conn->query($qry2); if ($result2->num_rows > 0) {while($row2 = $result2->fetch_assoc()) 
{
    $gnm=$row2["gn"];$cnm=$row2["cn"]; $tnm=$row2["tn"]; $prod=$row2["pn"];  
    $freeqty=$row2["freeqty"]; $cup=$row2["costprice"]; $mup=$row2["mrp"];
    $cp=$freeqty*$cup;$mp=$freeqty*$mup; 
    $tcp=$tcp+$cp;$tmp=$tmp+$mp;
   
?>                                                  <tr> 
                                                    <td class="text-right"><?php echo $gnm;?></td>
                                                    <td class="text-right"><?php echo $cnm;?> </td>
                                                    <td class="text-right"><?php echo $tnm?> </td>
                                                    <td class="text-right"><?php echo $prod?></td>
                                                    <td class="text-right"><?php echo number_format($freeqty,0);?></td>
                                                    <td class="text-right"><?php echo number_format($cup,2);?> </td>
                                                    <td><?php echo number_format($cp,2);?> </td>
                                                    <td><?php echo number_format($mup,2);?></td>
                                                    <td><?php echo number_format($mp,2);?></td>
                                                </tr>

<?php }}?>
                                                </tbody>
												<tfooter>
												<tr> 
                                                   <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <th class="noborder">&nbsp;</th>
                                                    <th class="noborder">&nbsp;</th>
                                                    <th class="noborder">&nbsp;</th>
                                                    <th class="noborder">Total</th>
                                                    <th class="text-right"><?php echo number_format($tcp,2);?> </th>
                                                    <td class="noborder">&nbsp;</td>
                                                    <th class="text-right"><?php echo number_format($tmp,2);?> </th>
                                                </tr>
												</tfooter>
                                            </table>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 