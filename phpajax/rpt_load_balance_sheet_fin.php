<?php
 //   $lvl1="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.glno='100000000' and c.yr='$pyr' and c.mn=$pmn";
 //echo $lvl1;die;
   // $result1 = $conn->query($lvl1);
//    if ($result1->num_rows > 0)
  //  {
    //    $row1 = $result1->fetch_assoc();
     //   $closingbal1= $row1["closingbal"];
   
?>
                                            
<style>

.bhbs-wrapper{}
.bhbs-header{
	text-align: center;
}
.bhbs-header{
    border-bottom: 1px solid #efefef;
}
.bhbs-header h2{font-size: 20px;margin-bottom:0;}
.bhbs-header h1{font-size: 30px;margin-top:5px;}
.tbl-bhbs-wrapper{
     border: 1px solid #efefef;
    padding: 15px;
}

.tbl-bhbs td:first-child{}
.tbl-bhbs td:last-child{width: 100px}

.tbl-bhbs td, .tbl-bhbs th{
    padding: 5px;
    border-bottom: 1px solid #efefef;
}

.tbl-bhbs tr {
    -webkit-transition: background-color 010ms linear;
    -ms-transition: background-color 100ms linear;
    transition: background-color 100ms linear;
}	
	
.tbl-bhbs tr:hover{
    background-color: #f8fbff;
}

	
	
.tbl-bhbs th{
    
    background-color: #efefef;
    font-size: 16px;
}

.tbl-bhbs td:first-child, .tbl-bhbs th{
    border-right:1px solid #efefef;
}

/* gaps */
.tbl-bhbs td.gp-1{padding-left: 30px;}
.tbl-bhbs td.gp-2{padding-left: 60px;}
.tbl-bhbs td.gp-3{padding-left: 90px;}
.tbl-bhbs td.gp-4{padding-left: 120px;}


.total-title, .total-amount{font-weight: bold;}
.total-amount{border-bottom: 3px solid #000!important;}
.last-amount{border-bottom: 1px solid #000!important;}	

.tbl-bhbs .end-parent{
    background-color: #f4e7e7;
    font-size: 16px;
}

.tbl-bhbs .end-parent .total-amount{
    border-bottom: 3px solid #000!important;
}

.tbl-bhbs .end-parent .total-amount{
    padding: 0px;
}

.tbl-bhbs .end-parent span{
    display: block;
    margin-bottom:2px!important;
    border-bottom:2px solid #111;
    padding: 5px;
}
	
.tbl-bhbs .end-parent.assets{ background-color: #cadcf8;}
.tbl-bhbs .end-parent.liabilities{ background-color: #f8caca;}
	
/* new print code */	 
@media (min-width: 968px) {
  .modal-dialog {
    width: 960px;
    margin: 30px auto;
  }
}	 
	
.report-header{
    width: 100%;
    display: flex!important;
    margin-bottom: 8px;
    
}

.report-header > div{
    border: 0px solid #000;
}

.report-header > div img{
    width: 100%;
}

.report-header > div.col1{
    width: 25%;
}

.report-header > div.col2{
    width: 74%;
    text-align: right;
    
}
.report-header > div.col2 h1{
    font-size: 20px!important;
    font-family: roboto;
    margin-bottom: 0px;
    padding-top: 10px;
    
}	 
/* end new print code */

</style>
 <div class="panel panel-info" id="printSinglePage">
      		            
			            <div class="panel-body">
                            <div class="row">
                        		
                                <div class="col-lg-12 col-md-12 col-sm-12">
									<div class="report-header">
										<div class="col1">
											<img src="../assets/images/site_setting_logo/logo_letterhead.png" alt="">
										</div>
										<div class="col2">
											<h1>Balance Sheet</h1>
										</div>
										<hr>
									</div>
       
                                </div> 
                                <div class="col-lg-12">
      
                                </div>
                                
                                <div class="po-product-wrapper"> 
                                    <div class="color-block">
 		                                
                                        <div class="col-lg-8 col-md-12">


 											<div class="tbl-bhbs-wrapper">
                                				<table class="tbl-bhbs" width="100%" border="0" cellspacing="0" cellpadding="0">
                                				  <tbody>
                                					<tr>
                                					  <th>ASSETS </th>
                                					  <!--th>&nbsp;</th -->
                                					  <th><?php echo 'As on '.$fd?></th>
                                					</tr>
                                					<?php 
                                					$lvl1tot=0;
                                					$lvl2="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl='100000000' and c.yr='2023' and c.mn=7";
                                					$result2 = $conn->query($lvl2);
                                                    if ($result2->num_rows > 0) 
                                                    {
                                                        while ($row2 = $result2->fetch_assoc())
                                                        {
                                                            $gl2= $row2["glno"]; $glnm2= $row2["glnm"];$closingbal2= $row2["closingbal"];
                                					?>
                                        					<tr class="assets">
                                        					  <td class="gp-1"><strong><?php echo $glnm2;?></strong></td>
                                        					  <td>&nbsp;</td>
                                        					</tr>
                                        			<?php 
                                        			        $lvl2tot=0;
                                        			        $lvl3="SELECT c.glno,c.glnm,(c.opbal+c.op_bal_fin) opbal  FROM `coa_mon` c  where c.ctlgl=$gl2 and c.yr='2023' and c.mn='7' ";
                                    					    $result3 = $conn->query($lvl3);
                                                            if ($result3->num_rows > 0) 
                                                            {
                                                                while ($row3 = $result3->fetch_assoc())
                                                                {
                                                                    $gl3= $row3["glno"]; $glnm3= $row3["glnm"];$opbal3= $row3["opbal"];$closingbal3=0;
                                                                    $lvl4="select sum(damt) debit,sum(camt) credit FROM
(
select COALESCE(sum(d.amount),0) damt,0 camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl='$gl3') and d.dr_cr='D' and m.isfinancial in('0','Y')  and
   (m.transdt Between '2023-07-01' and STR_TO_DATE('$fd', '%d/%m/%Y'))
union all
select COALESCE(sum(d.amount),0) damt,0 camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl3')) and d.dr_cr='D' and m.isfinancial in('0','Y')  and
   (m.transdt Between '2023-07-01' and STR_TO_DATE('$fd', '%d/%m/%Y'))
union all
select 0 damt,COALESCE(sum(d.amount),0) camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl='$gl3') and d.dr_cr='C' and m.isfinancial in('0','Y')  and
   (m.transdt Between '2023-07-01' and STR_TO_DATE('$fd', '%d/%m/%Y'))
union all
select  0 damt,COALESCE(sum(d.amount),0) camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl3')) and d.dr_cr='C' and m.isfinancial in('0','Y')  and
   (m.transdt Between '2023-07-01' and STR_TO_DATE('$fd', '%d/%m/%Y'))
 ) u";
                                                                   // echo $lvl4;die;
                                                                    $result4 = $conn->query($lvl4);
                                                                    if ($result4->num_rows > 0) 
                                                                    {
                                                                        while ($row4 = $result4->fetch_assoc())
                                                                        {
                                                                            $closingbal3=$closingbal3+$row4["debit"]-$row4["credit"];
                                                                        }
                                                                    }
                                                                    $lvl2tot=$lvl2tot+$closingbal3+$opbal3;
                                                                    $lvl1tot=$lvl1tot+$closingbal3+$opbal3;
                                                    ?>  
                                                                    <tr class="cash-bank-balance">
                                            					        <td class="gp-2"><?php echo $glnm3;?></td>
                                                					    <td class="gp-2" align="right"><?php echo number_format(($opbal3+$closingbal3),2);?></td>
                                            					    </tr> 
                                				    <?php /*
                                            			           $lvl4="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$gl3 and c.yr='$pyr' and c.mn='$pmn'";
                                        					        $result4 = $conn->query($lvl4);
                                                                    if ($result4->num_rows > 0) 
                                                                    {
                                                                        while ($row4 = $result4->fetch_assoc())
                                                                        {
                                                                            $gl4= $row4["glno"]; $glnm4= $row4["glnm"];$closingbal4= $row4["closingbal"];
                                                        ?>  
                                                                            <tr class="cash-bank-balance">
                                                					            <td class="gp-3"><?php echo $glnm4;?></td>
                                                    					        <td>&nbsp;</td>
                                                					        </tr>  
                                    		        <?php 
                                                			                $lvl5="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$gl4 and c.yr='$pyr' and c.mn='$pmn'";
                                                					        $result5 = $conn->query($lvl5);
                                                                            if ($result5->num_rows > 0) 
                                                                            {
                                                                                while ($row5 = $result5->fetch_assoc())
                                                                                {
                                                                                    $gl5= $row5["glno"]; $glnm5= $row5["glnm"];$closingbal5= $row5["closingbal"];
                                                    ?>  
                                                                                    <tr class="cash-bank-balance">
                                                        					            <td class="gp-4"><?php echo $glnm5;?></td>
                                                            					        <td><?php echo $closingbal5;?></td>
                                                        					        </tr>  
                                        		    <?php
                                                                                }
                                                                            }
                                                    ?>                        
                                                                            <tr class="govment-authority">
                                					                            <td  class="gp-3 total-title">Total <?php echo $glnm4;?></td>
                                					                            <td class="total-amount"><?php echo $closingbal4;?></td>
                                				                            </tr>
                                					<?php
                                                                        }
                                                                    }*/
                                                    ?>
                                                                    <!--tr class="accounts-payable">
                                				                        <td  class="gp-2 total-title">Total <?php echo $glnm3;?></td>
                                					                    <td class="total-amount"><?php echo number_format($closingbal3,2);?></td>
                                					                </tr-->
                                                    <?php
                                                                }
                                                            }
                                                    ?>
                                                                <tr class="assets">
                                					                <td  class="gp-1 total-title">Total <?php echo $glnm2;?></td>
                                					                <td class="total-amount" align="right"><?php echo number_format($lvl2tot,2);?></td>
                                				                </tr>
                                                    <?php
                                        			    }
                                        			}
                                        			?>
                                        			<tr class="end-parent assets">
                                					  <td  class="total-title">TOTAL ASSET</td>
                                					 
                                					  <td class="total-amount" align="right"><span><?php echo number_format($lvl1tot,2);?></span></td>
                                					</tr>
                                					
                                                    <tr>
                                					  <th>LIABILITIES &amp; EQUITY</th>
                                					  <th>&nbsp;</th>
                                					</tr>
                                					<?php 
                                					$lvl1tot=0;
                                					$lvl2="SELECT c.glno,c.glnm,(c.opbal+c.op_bal_fin) opbal FROM `coa_mon` c  where c.ctlgl='200000000' and c.yr='2023' and c.mn=7";
                                					$result2 = $conn->query($lvl2);
                                                    if ($result2->num_rows > 0) 
                                                    {
                                                        while ($row2 = $result2->fetch_assoc())
                                                        {
                                                            $gl2= $row2["glno"]; $glnm2= $row2["glnm"];$closingbal2= $row2["opbal"];
                                					?>
                                        					<tr class="liabilities"> 
                                        					  <td class="gp-1"><strong><?php echo $glnm2;?></strong></td>
                                        					  <td class="gp-1">&nbsp;</td>
                                        					</tr>
                                        			<?php 
                                        			        $lvl2tot=0;  
                                        			        $lvl3="SELECT c.glno,c.glnm,c.opbal FROM `coa_mon` c  where c.ctlgl=$gl2 and c.yr='2023' and c.mn=7";
                                    					    $result3 = $conn->query($lvl3);
                                                            if ($result3->num_rows > 0) 
                                                            {
                                                                while ($row3 = $result3->fetch_assoc())
                                                                {
                                                                    $gl3= $row3["glno"]; $glnm3= $row3["glnm"];$closingbal3= $row3["opbal"];
                                                                    $lvl4="select sum(damt) debit,sum(camt) credit FROM
(
select COALESCE(sum(d.amount),0) damt,0 camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl='$gl3') and d.dr_cr='D' and m.isfinancial in('0','Y')  and
   (m.transdt Between '2023-07-01' and STR_TO_DATE('$fd', '%d/%m/%Y'))
union all
select COALESCE(sum(d.amount),0) damt,0 camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl3')) and d.dr_cr='D' and m.isfinancial in('0','Y')  and
   (m.transdt Between '2023-07-01' and STR_TO_DATE('$fd', '%d/%m/%Y'))
union all
select 0 damt,COALESCE(sum(d.amount),0) camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl='$gl3') and d.dr_cr='C' and m.isfinancial in('0','Y')  and
   (m.transdt Between '2023-07-01' and STR_TO_DATE('$fd', '%d/%m/%Y'))
union all
select  0 damt,COALESCE(sum(d.amount),0) camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl3')) and d.dr_cr='C' and m.isfinancial in('0','Y')  and
   (m.transdt Between '2023-07-01' and STR_TO_DATE('$fd', '%d/%m/%Y'))
 ) u";
                                                                   // echo $lvl4;die;
                                                                    $result4 = $conn->query($lvl4);
                                                                    if ($result4->num_rows > 0) 
                                                                    {
                                                                        while ($row4 = $result4->fetch_assoc())
                                                                        {
                                                                            $closingbal3=$closingbal3+$row4["debit"]-$row4["credit"];
                                                                        }
                                                                    }
                                                                    $lvl2tot=$lvl2tot+$closingbal3+$opbal3;
                                                                    $lvl1tot=$lvl1tot+$closingbal3+$opbal3;
                                                                    
                                                                    
                                                                    
                                                                    
                                                    ?>  
                                                                    <tr class="cash-bank-balance">
                                            					        <td class="gp-2"><?php echo $glnm3;?></td>
                                                					    <td class="gp-2" align="right"><?php echo number_format(($opbal3+$closingbal3),2);?></td>
                                            					    </tr> 
                                				    <?php /*
                                            			            $lvl4="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$gl3 and c.yr='$pyr' and c.mn='$pmn'";
                                        					        $result4 = $conn->query($lvl4);
                                                                    if ($result4->num_rows > 0) 
                                                                    {
                                                                        while ($row4 = $result4->fetch_assoc())
                                                                        {
                                                                            $gl4= $row4["glno"]; $glnm4= $row4["glnm"];$closingbal4= $row4["closingbal"];
                                                        ?>  
                                                                            <tr class="cash-bank-balance">
                                                					            <td class="gp-3"><?php echo $glnm4;?></td>
                                                    					        <td>&nbsp;</td>
                                                					        </tr>  
                                    		        <?php 
                                                			                $lvl5="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$gl4 and c.yr='$pyr' and c.mn='$pmn'";
                                                					        $result5 = $conn->query($lvl5);
                                                                            if ($result5->num_rows > 0) 
                                                                            {
                                                                                while ($row5 = $result5->fetch_assoc())
                                                                                {
                                                                                    $gl5= $row5["glno"]; $glnm5= $row5["glnm"];$closingbal5= $row5["closingbal"];
                                                    ?>  
                                                                                    <tr class="cash-bank-balance">
                                                        					            <td class="gp-4"><?php echo $glnm5;?></td>
                                                            					        <td><?php echo $closingbal5;?></td>
                                                        					        </tr>  
                                        		    <?php
                                                                                }
                                                                            }
                                                    ?>                        
                                                                            <tr class="govment-authority">
                                					                            <td  class="gp-3 total-title">Total <?php echo $glnm4;?></td>
                                					                            <td class="total-amount"><?php echo $closingbal4;?></td>
                                				                            </tr>
                                					<?php
                                                                        }
                                                                    }*/
                                                    ?>
                                                                    <!--tr class="accounts-payable">
                                				                        <td  class="gp-2 total-title">Total <?php echo $glnm3;?></td>
                                					                    <td class="total-amount"><?php echo number_format($closingbal3,2);?></td>
                                					                </tr-->
                                                    <?php
                                                                }
                                                            }
                                                    ?>
                                                                <tr class="liabilities">
                                					                <td  class="gp-1 total-title">Total <?php echo $glnm2;?></td>
                                					                <td class="total-amount" align="right"><?php echo number_format($lvl2tot,2);?></td>
                                				                </tr>
                                                    <?php
                                        			    }
                                        			}
                                        			?>
                                        			<tr class="end-parent liabilities">
                                					  <td  class="total-title">TOTAL LIABILITIES</td>
                                					  <?php
                                					    //$lvl1="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.glno='200000000' and c.yr='$pyr' and c.mn='$pmn'";
                                					    //$result1 = $conn->query($lvl1);
                                                        //$row1 = $result1->fetch_assoc();
                                                        //$closingbal1= $row1["closingbal"];
                                                            ?>
                                					  <td class="total-amount" align="right"><span><?php echo number_format($lvl1tot,2);?></span></td>
                                					</tr>
                                					<tr>
                                					  <td>&nbsp;</td>
                                					  <td>&nbsp;</td>
                                					</tr>				
                                				  </tbody>
                                				</table>
                                			</div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
<?php
 //}
//else
//{
?>    
                                <!--div class="col-lg-12">
                                     <div class="bhbs-header">
                            			<h1>Balance Sheet</h1>
                            			<h1>Not Yet Generated, Month End Process required!!</h1>
                            		</div>        
                                </div--> 
<?php    
//}
?>