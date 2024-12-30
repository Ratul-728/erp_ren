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
.tbl-bhbs td.gp-1{padding-left: 30px;text-align: left;}
.tbl-bhbs td.gp-2{padding-left: 60px;text-align: right;}
.tbl-bhbs td.gp-3{padding-left: 90px;text-align: right;}
.tbl-bhbs td.gp-4{padding-left: 120px;text-align: right;}


.total-title, .total-amount{font-weight: bold;}
.total-amount{border-top: 3px solid #000!important;text-align: right;}
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
										<div class="col12">
											<h1>Statement of profit or loss and other comprehensive income</h1>
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
                                					<tr class="assets">
                                					  <td class="total-title">In Taka </td>
                                					  <?php $prevyr=$pyr-1;$bfrprevyr=$prevyr-1?>
                                					  <!--th>&nbsp;</th -->
                                					  <td class="total-amount"><?php echo 'As on 30-jun-'.$pyr;?></td>
                                					  <td class="total-amount"><?php echo 'As on 30-jun-,'.$prevyr;?></td>
                                					  
                                					</tr>
                            						
                                					
                                        			<tr class="assets">
<?php
                                					
$qturover="select `closingbal` bal from coa_mon m where glno='301010000' and  yr='$pyr' and mn=6";
//echo $lvl1;die;
$resturover = $conn->query($qturover);
if ($resturover->num_rows > 0) {$rowturover = $resturover->fetch_assoc();$turover= $rowturover["bal"]; }
    
$qturoverprv="select `closingbal` bal from coa_mon m where glno='301010000' and  yr='$prevyr' and mn=6";
$resturoverprv = $conn->query($qturoverprv);
if ($resturoverprv->num_rows > 0) {$rowturoverprv = $resturoverprv->fetch_assoc();$turoverprv= $rowturoverprv["bal"]; }
?>
                                					  <td  class="gp-1">Turnover</td>
                                					  <td class="gp-2"><span><?php echo number_format($turover,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($turoverprv,0);?></span></td>
                                					</tr> 
                                					
                                					<tr class="assets">
                                					  <?php
$qcogs="select sum(closingbal) bal from coa_mon where yr='$pyr' and mn=6 and glno in ('401000000','402000000')";
$rescogs = $conn->query($qcogs);
if ($rescogs->num_rows > 0) {$rowcogs = $rescogs->fetch_assoc();$cogs= $rowcogs["bal"]; }
$qcogsprv="select sum(closingbal) bal from coa_mon where yr='$prevyr' and mn=6 and glno in ('401000000','402000000')";
$rescogsprv = $conn->query($qcogsprv);
if ($rescogsprv->num_rows > 0) {$rowcogsprv = $rescogsprv->fetch_assoc();$cogsprv= $rowcogsprv["bal"]; }
                     					
                                					?>  
                                					  <td  class="gp-1">Cost of good sold</td>
                                					  <td class="gp-2"><span><?php echo number_format($cogs,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($cogsprv,0);?></span></td>
                                					</tr> 
                                					<tr class="assets">
                                					  <td class="total-title">Gross Profit </td>
                                					  <td class="total-amount"><?php echo number_format($turover-$cogs,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($turoverprv-$cogsprv,0);?></td>
                                					</tr>
                                					<tr class="assets">
                                					  <?php
$qadminexp="select `closingbal` bal from coa_mon m where glno='403010000' and  yr='$pyr' and mn=6";
//echo $lvl1;die;
$resadminexp = $conn->query($qadminexp);
if ($resadminexp->num_rows > 0) {$rowadminexp = $resadminexp->fetch_assoc();$adminexp= $rowadminexp["bal"]; }
    
$qadminexpprv="select `closingbal` bal from coa_mon m where glno='403010000' and  yr='$prevyr' and mn=6";
$resadminexpprv = $conn->query($qadminexpprv);
if ($resadminexpprv->num_rows > 0) {$rowadminexpprv = $resadminexpprv->fetch_assoc();$adminexpprv= $rowadminexpprv["bal"]; }
                     					
                                					?>  
                                					  <td  class="gp-1">Administrative expense</td>
                                					  <td class="gp-2"><span><?php echo number_format($adminexp,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($adminexpprv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					  <?php 
$qsellingexp="select `closingbal` bal from coa_mon m where glno='403020000' and  yr='$pyr' and mn=6";
//echo $lvl1;die;
$ressellingexp = $conn->query($qsellingexp);
if ($ressellingexp->num_rows > 0) {$rowsellingexp = $ressellingexp->fetch_assoc();$sellingexp= $rowsellingexp["bal"]; }
    
$qsellingexpprv="select `closingbal` bal from coa_mon m where glno='403020000' and  yr='$prevyr' and mn=6";
$ressellingexpprv = $conn->query($qsellingexpprv);
if ($ressellingexpprv->num_rows > 0) {$rowsellingexpprv = $ressellingexpprv->fetch_assoc();$sellingexpprv= $rowsellingexpprv["bal"]; }
                     					
                                					?>  
                                					
                                					  <td  class="gp-1">Selling and marketing expense</td>
                                					  <td class="gp-2"><span><?php echo number_format($sellingexp,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($sellingexpprv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Operating Profit </td>
                                					  <td class="total-amount"><?php echo number_format($turover-$cogs-$adminexp-$sellingexp,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($turoverprv-$cogsprv-$adminexpprv-$sellingexpprv,0);?></td>
                                					</tr>
                                					<tr class="assets">
<?php
$qotherincome="select `closingbal` bal from coa_mon  where glno='302010000' and  yr='$pyr' and mn=6";
//echo $lvl1;die;
$resotherincome = $conn->query($qotherincome);
if ($resotherincome->num_rows > 0) {$rowotherincome = $resotherincome->fetch_assoc();$otherincome= $rowotherincome["bal"]; }
    
$qotherincomeprv="select `closingbal` bal from coa_mon  where glno='302010000' and  yr='$prevyr' and mn=6";
$resotherincomeprv = $conn->query($qotherincomeprv);
if ($resotherincomeprv->num_rows > 0) {$rowotherincomeprv = $resotherincomeprv->fetch_assoc();$otherincomeprv= $rowotherincomeprv["bal"]; }
                     					
                                					?>  
                                					
                                					  <td  class="gp-1">Other Income</td>
                                					  <td class="gp-2"><span><?php echo number_format($otherincome,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($otherincomeprv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
<?php
$qfinexp="select `closingbal` bal from coa_mon  where glno='404010000' and  yr='$pyr' and mn=6";
//echo $lvl1;die;
$resfinexp = $conn->query($qfinexp);
if ($resfinexp->num_rows > 0) {$rowfinexp = $resfinexp->fetch_assoc();$finexp= $rowfinexp["bal"]; }
    
$qfinexpprv="select `closingbal` bal from coa_mon  where glno='404010000' and  yr='$prevyr' and mn=6";
$resfinexpprv = $conn->query($qfinexpprv);
if ($resfinexpprv->num_rows > 0) {$rowfinexpprv = $resfinexpprv->fetch_assoc();$finexpprv= $rowfinexpprv["bal"]; }
                     					
                                					?>  
                                					
                                					  <td  class="gp-1">Financial  expense</td>
                                					  <td class="gp-2"><span><?php echo number_format($finexp,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($finexpprv,0);?></span></td>
                                					</tr>
                                					
                                				    <tr class="assets">
                                					  <td class="total-title">Profit before tax </td>
                                					  <td class="total-amount"><?php echo number_format($turover-$cogs-$adminexp-$sellingexp+$otherincome-$finexp,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($turoverprv-$cogsprv-$adminexpprv-$sellingexpprv+$otherincomeprv-$finexpprv,0);?></td>
                                					</tr>
                                					<tr class="assets">
<?php
$qtaxexp="select `closingbal` bal from coa_mon m where glno='404020100' and  yr='$pyr' and mn=6";
//echo $lvl1;die;
$restaxexp = $conn->query($qtaxexp);
if ($restaxexp->num_rows > 0) {$rowtaxexp = $restaxexp->fetch_assoc();$taxexp= $rowtaxexp["bal"]; }
    
$qtaxexpprv="select `closingbal` bal from coa_mon m where glno='404020100' and  yr='$prevyr' and mn=6";
$restaxexpprv = $conn->query($qtaxexpprv);
if ($restaxexpprv->num_rows > 0) {$rowtaxexpprv = $restaxexpprv->fetch_assoc();$taxexpprv= $rowtaxexpprv["bal"]; }
                     					
                                					?>  
                                					
                                					  <td  class="gp-1">Tax  expense</td>
                                					  <td class="gp-2"><span><?php echo number_format($taxexp,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($taxexpprv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
<?php
$qdeftax="select `closingbal` bal from coa_mon  where glno='404020200' and  yr='$pyr' and mn=6";
//echo $lvl1;die;
$resdeftax = $conn->query($qdeftax);
if ($resdeftax->num_rows > 0) {$rowdeftax = $resdeftax->fetch_assoc();$deftax= $rowdeftax["bal"]; }
    
$qdeftaxprv="select `closingbal`  bal from coa_mon  where glno='404020200' and  yr='$prevyr' and mn=6";
$resdeftaxprv = $conn->query($qdeftaxprv);
if ($resdeftaxprv->num_rows > 0) {$rowdeftaxprv = $resdeftaxprv->fetch_assoc();$deftaxprv= $rowdeftaxprv["bal"]; }
                     					
                                					?>  
                                					
                                					  <td  class="gp-1">Defferred tax(income)/ expense</td>
                                					  <td class="gp-2"><span><?php echo number_format($deftax,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($deftaxprv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Total income tax expense </td>
                                					  <td class="total-amount"><?php echo number_format($taxexp+$deftax,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($taxexpprv+$deftaxprv,0);?></td>
                                					</tr>
                                				    <tr class="assets">
                                					  <td class="total-title">Profit/loss for the year </td>
                                					  <td class="total-amount"><?php echo number_format($turover-$cogs-$adminexp-$sellingexp+$otherincome-$finexp-$taxexp-$deftax,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($turoverprv-$cogsprv-$adminexpprv-$sellingexpprv+$otherincomeprv-$finexpprv-$taxexpprv-$deftaxprv,0);?></td>
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Other Comprehensive Income </td>
                                					  <td class="total-amount"><?php echo number_format(0,0);?></td>
                                					  <td class="total-amount"><?php echo number_format(0,0);?></td>
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Total Comprehensive Income </td>
                                					  <td class="total-amount"><?php echo number_format($turover-$cogs-$adminexp-$sellingexp+$otherincome-$finexp-$taxexp-$deftax,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($turoverprv-$cogsprv-$adminexpprv-$sellingexpprv+$otherincomeprv-$finexpprv-$taxexpprv-$deftaxprv,0);?></td>
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
