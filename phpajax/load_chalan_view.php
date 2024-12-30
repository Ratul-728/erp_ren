<?php 
//$id=13;
 $qry="select p.id,p.poid,p.adviceno,p.supid,DATE_FORMAT(p.orderdt,'%e/%c/%Y') orderdt,DATE_FORMAT(p.delivery_dt,'%e/%c/%Y') delivery_dt ,s.Name 
from po p left join organization s on p.supid=s.id  where p.id= ".$cid; 
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
                            $chalanid=$row["id"];$chalanno=$row["poid"];$advno=$row["adviceno"]; $supnm=$row["Name"]; $orderdt=$row["orderdt"];  
                            $delivery_dt=$row["delivery_dt"];
                           $hrid='1';
                        }
                }
            }

?>



 <div class="panel panel-info" id="printableArea">
	 <div class="panel-heading"><h1 style="font-size: 24px;"><span onClick="history.back();" style="cursor: pointer;color: #00ABE3">Chalan</span> &rsaquo; Chalan no: <?php  echo $chalanno;?></h1>
      		            <!--<p><h3>Payment via <?php echo $payment_mood;?> </h3></p>-->
      		            </div>
      		            
			            <div class="panel-body">
                            <div class="row">
                        		<div class="col-sm-12">
	                                
	                                 <input type="hidden"  name="chalanid" id="chalanid" value="<?php echo $chalanid;?>"> 
	                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>" >
	                                 
	                            </div> 
                                <div class="col-sm-12">
                                    <h4>Chalan Information  </h4>
                                    <hr class="form-hr"></hr> 
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="po_id">Advice No.       # &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $advno;?></label></br>
                                        <label for="po_id">Organiztion Name    #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $supnm;?></label></br>
                                        <!--label for="po_dt">Order Date       #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $orderdt;?></label></br-->
                                        <label for="po_dt">Received Date    #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $delivery_dt;?></label>
                                    </div>        
                                </div>
                        	    <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                     <div class="form-group">
                                        <label for="cmbsupnm">Status # </label></br>
                                         <label for="cmbsupnm"><?php echo $ost;?> </label>
                                        <div class="form-group styled-select">
                                            <select name="cmbsupnm" id="cmbsupnm" class="form-control" >
<?php 
$qry1="SELECT `id`, `name`  FROM `orderstatus` order by name";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
{ 
          $tid= $row1["id"];  $nm=$row1["name"];
?>          
                                                <option value="<?php echo $tid; ?>" <?php if ($orderstatus == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php }}?>                    
                                            </select>
                                        </div> 
                                    </div>          
                                </div> 
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="input-group">
                                        <label for="email">Billing Address  #</label></br>
                                        <label for="email"><?php echo $cusaddr;?></label>
                                    </div>     
                                </div>       
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="input-group">
                                        <label for="email">Delivery Address  #</label></br>
                                        <label for="email"><?php echo $deladr;?></label>
                                    </div>     
                                </div> -->
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
                                                    <th>Item Type</th>
                                                    <th>Bar Code</th>
                                                    <th>Quantity</th>
                                                    <th>Warehouse </th>
                                                    <th>Remarks</th>
                                                </tr>
												  </thead>
												<tbody>
                                               
<?php 
$gtotal=0;
$qry2="select i.`id`,i.`item_sl`,i.`itemid`,i.`barcode`,DATE_FORMAT(i.`expirydt`,'%e/%c/%Y') `expirydt`,i.`qty`,i.`unitprice`,i.`amount`,i.`mrp`
,i.`description`,p.name prd,t.name catagory ,p.code,b.name storerome 
from  poitem i left join item p on i.itemid=p.id left join itmCat t on p.catagory=t.id left join branch b on i.storerome=b.id where poid='".$chalanno."'";
//echo $qry2;die;
$result2 = $conn->query($qry2); if ($result2->num_rows > 0) {while($row2 = $result2->fetch_assoc()) 
{$tid= $row2["id"];  $nm=$row2["prd"];$rate=$row2["unitprice"];$quantity=$row2["qty"];$total=$row2["amount"];$barcode=$row2["barcode"];
$expirydt=$row2["expirydt"];$cat=$row2["catagory"];$store=$row2["storerome"];$rem=$row2["description"];

 $photo="../common/upload/item/".$row2["code"].".jpg";
               if (file_exists($photo)) {

        		$photo="common/upload/item/".$row2["image"].".jpg";

        		}else{

        			$photo="common/upload/item/placeholder.jpg";

        		}

$gtotal=$gtotal+$total;
?>                                                  <tr> 
                                                    <td><img src='<?php echo $photo;?>' width="50" height="50"></td>
                                                    <td><?php echo $nm;?> </td>
                                                    <td><?php echo $cat;?> </td>
                                                    <td><?php echo $barcode;?> </td>
                                                    <td> <?php echo number_format($quantity,0);?></td>
                                                    <td><?php echo $store;?> </td>
                                                    <td><?php echo $rem;?> </td>
                                                </tr>

<?php }}?>
                                                </tbody>
												<!--tfooter>
												<tr> 
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                    <th class="text-right">Total</th>
                                                    <th><?php echo number_format($gtotal,2);?> </th>
                                                    <td class="noborder">&nbsp;</td>
                                                    <td class="noborder">&nbsp;</td>
                                                </tr>
                                               
                                                
													</tfooter-->
                                            </table>											
											
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 