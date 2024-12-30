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
                                         <label for="cmbsupnm">   Cash Flow  </label><br>
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
                                                    <th>Sl</th>
                                                    <th>Date</th>
                                                    <th>Narration</th>
                                                    <th>Debit </th>
                                                    <th>Credit </th>
                                                    <th>Balance</th>
                                                </tr>
												  </thead>
												<tbody>
                                               
<?php 
$bal=0;$i=0;$bf=0;$totdr=0;$totcr=0;$net=0;
//echo $fd;die;
$qry0="select sum(paidamount) dra from invoice where invoicedt < STR_TO_DATE('".$fd."','%d/%m/%Y')";
$qry1="select sum(amount) cra from expense where trdt < STR_TO_DATE('".$fd."','%d/%m/%Y')";
//echo $qry0;die;
$result0 = $conn->query($qry0);
$row0 = $result0->fetch_assoc();
$d=$row0["dra"];
//echo $d;die;
$result1 = $conn->query($qry1);
$row1 = $result1->fetch_assoc();
$c=$row1["cra"];
$bal=$d-$c;
?>

                                                    <tr> 
                                                        <td class="text-right"></td>
                                                        <td class="text-right"> </td>
                                                        <td class="text-right">BF </td>
                                                        <td class="text-right"></td>
                                                        <td class="text-right"></td>
                                                        <td class="text-right"><?php echo number_format($bal,2);?> </td>
                                                    </tr>
<?php
$qry2="select date_format(trdt,'%d/%m/%Y') trdt,narr,incm dr,expns cr
FROM
(
SELECT `invoicedt` trdt,`paidamount` incm,0 expns,concat(soid,'-',invoiceno) narr 
    FROM invoice where invoicedt between STR_TO_DATE('".$fd."','%d/%m/%Y') and  STR_TO_DATE('".$td."','%d/%m/%Y')
union all 
select trdt  trdt,0 incm,amount expns,naration narr from expense where trdt between STR_TO_DATE('".$fd."','%d/%m/%Y') and  STR_TO_DATE('".$td."','%d/%m/%Y')
) u
order by trdt";
$result2 = $conn->query($qry2); if ($result2->num_rows > 0) {while($row2 = $result2->fetch_assoc()) 
{
    $trdt=$row2["trdt"];$narr=$row2["narr"]; $dr=$row2["dr"]; $cr=$row2["cr"];  
    $bal=$bal+$dr-$cr;$i++;$totdr=$totdr+$dr;$totcr=$totcr+$cr;
   
?>                                                  <tr> 
                                                        <td class="text-right"><?php echo $i;?></td>
                                                        <td class="text-right"><?php echo $trdt;?> </td>
                                                        <td class="text-right"><?php echo $narr?> </td>
                                                        <td class="text-right"><?php echo number_format($dr,2)?></td>
                                                        <td class="text-right"><?php echo number_format($cr,2);?></td>
                                                        <td class="text-right"><?php echo number_format($bal,2);?> </td>
                                                    </tr>

<?php }}?>
                                                </tbody>
												<tfooter>
												<tr>
                                                    <th class="noborder">&nbsp;</th>
                                                    <th class="noborder">&nbsp;</th>
                                                    <th class="noborder">Total</th>
                                                    <th class="text-right"><?php echo number_format($totdr,2);?> </th>
                                                    <th class="text-right"><?php echo number_format($totcr,2);?> </th>
                                                    <th class="text-right"><?php echo number_format($bal,2);?> </th>
                                                </tr>
												</tfooter>
                                            </table>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 