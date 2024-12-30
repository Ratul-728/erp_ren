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
											<h1>Statement of Financial Position</h1>
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
                                					  <td class="total-title">Assets </td>
                                					  
                                					  <!--th>&nbsp;</th -->
                                					  <td class="total-amount"></td>
                                					  <td class="total-amount"></td>
                                					</tr>
                                					
                                        			<tr class="assets">
<?php
                                					
$lvl1="
select COALESCE((m.closingbal 
-
 COALESCE((select m1.`closingbal` from coa_mon m1 where glno='203070000' and m1.yr =m.yr and m1.mn=m.mn),0) 
 +
 COALESCE((select m1.`closingbal` from coa_mon m1 where glno='101020000' and m1.yr =m.yr and m1.mn=m.mn),0) 
        ),0) bal
from coa_mon m
where m.glno='101010000'
and m.yr='$pyr' and m.mn=6";

//echo $lvl1;die;
$result1 = $conn->query($lvl1);
if ($result1->num_rows > 0)
    {
        $row1 = $result1->fetch_assoc();
        $closingbal1= $row1["bal"];
    }
    
$lvl1prv="
select COALESCE((m.closingbal 
-
 COALESCE((select m1.`closingbal` from coa_mon m1 where glno='203070000' and m1.yr =m.yr and m1.mn=m.mn),0) 
 +
 COALESCE((select m1.`closingbal` from coa_mon m1 where glno='101020000' and m1.yr =m.yr and m1.mn=m.mn),0) 
        ),0) bal_prev
from coa_mon m
where m.glno='101010000'
and m.yr='$prevyr' and m.mn=6";
                                  
//echo $lvl1prv;die;
$result1prv = $conn->query($lvl1prv);    
if ($result1prv->num_rows > 0)
    {
        $row1prv = $result1prv->fetch_assoc();
        $closingbal1prv= $row1prv["bal_prev"];
    } 
                                					
                                					?>
                                					  <td  class="gp-1">Prperty,plant and equipment</td>
                                					  <td class="gp-2"><span><?php echo number_format($closingbal1,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($closingbal1prv,0);?></span></td>
                                					</tr> 
                                					
                                					<tr class="assets">
                                					  <?php
                                					
$useasset="select COALESCE(m1.`closingbal`),0) useasset from coa_mon m1 where glno='101040000' and m1.yr ='$pyr' and m1.mn=6";

//echo $lvl1;die;
$resuseasset = $conn->query($useasset);
if ($resuseasset->num_rows > 0)
    {
        $rowuseasset = $resuseasset->fetch_assoc();
        $useassetbal= $rowuseasset["useasset"];
    }
    
$useassetprv="select COALESCE(m1.`closingbal`),0) useasset from coa_mon m1 where glno='101040000' and m1.yr ='$prevyr' and m1.mn=6 ";

                                  
//echo $lvl1prv;die;
$resuseassetprv = $conn->query($useassetprv);    
if ($resuseassetprv->num_rows > 0)
    {
        $rowuseassetprv = $resuseassetprv->fetch_assoc();
        $useassetbalprv= $rowuseassetprv["useasset"];
    } 
                                					
                                					?>  
                                					  <td  class="gp-1">Right of use assets</td>
                                					  <td class="gp-2"><span><?php echo number_format($useassetbal,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($useassetbalprv,0);?></span></td>
                                					</tr> 
                                					
                                					<tr class="assets">
                                					     <td  class="gp-1">Deffered tax asset </td>
                                					     
                                					    <?php
                                					    	$qdeftax="SELECT COALESCE(`closingbal`,0) deftax FROM coa_mon m1 where m1.glno='101030200' and m1.yr ='$pyr' and m1.mn=6";
                                					//echo $qdeftax;die;
                                					$resdeftax = $conn->query($qdeftax);
                                					 if ($resdeftax->num_rows > 0)
                                                        {
                                                            $rowdeftax = $resdeftax->fetch_assoc();
                                                            $deftax= $rowdeftax["deftax"];
                                                             
                                                        }
                                                        
                                                    	$qdeftaxprv="SELECT COALESCE(`closingbal`,0) deftaxprv FROM coa_mon m where m.glno='101030200' and m.yr ='$prevyr' and m.mn=6";
                                					//echo $qdeftaxprv;die;
                                					$resdeftaxprv = $conn->query($qdeftaxprv);
                                					 if ($resdeftaxprv->num_rows > 0)
                                                        {
                                                            $rowdeftaxprv = $resdeftaxprv->fetch_assoc();
                                                            $deftaxprv= $rowdeftaxprv["deftaxprv"];
                                                            
                                                        } 
                                					    ?>
                                					     
                                					     
                                					    <td class="gp-2"><span><?php echo number_format($deftax,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($deftaxprv,0);?></span></td>
                                					 </tr>
                                                    
                                                    <tr class="assets">
                                					  <td class="total-title">Non Curent Assets </td>
                                					  <td class="total-amount"><?php echo number_format($closingbal1+$useassetbal+$deftax,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($closingbal1prv+$useassetbalprv+$deftaxprv,0);?></td>
                                					  
                                					</tr>
                                					
                                					<tr class="assets">
<?php 
                                				                                					
$qinv="select COALESCE((m.closingbal),0) bal from coa_mon m where m.glno='102010000' and m.yr='$pyr' and m.mn=6";
$resinv = $conn->query($qinv);
if ($resinv->num_rows > 0){$rowinv = $resinv->fetch_assoc(); $inv= $rowinv["bal"];  }
    
$qinvPrv="select COALESCE((m.closingbal),0) bal from coa_mon m where m.glno='102010000' and m.yr='$prevyr' and m.mn=6";
$resinvPrv = $conn->query($qinvPrv);
if ($resinvPrv->num_rows > 0){$rowinvPrv = $resinvPrv->fetch_assoc(); $invPrv= $rowinvPrv["bal"];  }
 
$qadv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('102040000') and m.yr='$pyr' and m.mn=6";
$resadv = $conn->query($qadv);
if ($resadv->num_rows > 0){$rowadv = $resadv->fetch_assoc(); $adv= $rowadv["bal"];  }
    
$qadvPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('102040000')  and m.yr='$prevyr' and m.mn=6";
$resadvPrv = $conn->query($qadvPrv);
if ($resadvPrv->num_rows > 0){$rowadvPrv = $resadvPrv->fetch_assoc(); $advPrv= $rowadvPrv["bal"];  }

$qait="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('102040106','102040110') and m.yr='$pyr' and m.mn=6";
$resait = $conn->query($qait);
if ($resait->num_rows > 0){$rowait = $resait->fetch_assoc(); $ait= $rowait["bal"];  }
    
$qaitPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('102020205','102040110')  and m.yr='$prevyr' and m.mn=6";
$resaitPrv = $conn->query($qaitPrv);
if ($resaitPrv->num_rows > 0){$rowaitPrv = $resaitPrv->fetch_assoc(); $aitPrv= $rowaitPrv["bal"];  }


$qcsh="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('102050000') and m.yr='$pyr' and m.mn=6";
$rescsh = $conn->query($qcsh);
if ($rescsh->num_rows > 0){$rowcsh = $rescsh->fetch_assoc(); $csh= $rowcsh["bal"];  }
    
$qcshPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('102050000')  and m.yr='$prevyr' and m.mn=6";
$rescshPrv = $conn->query($qcshPrv);
if ($rescshPrv->num_rows > 0){$rowcshPrv = $rescshPrv->fetch_assoc(); $cshPrv= $rowcshPrv["bal"];  }

?>
                                					  <td  class="gp-1"><?php echo "Inventories";?></td>
                                					  <td class="gp-2"><span><?php echo number_format($inv,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($invPrv,0);?></span></td>
                                					</tr> 
                                					
                                					<tr class="assets">
                                					  <td  class="gp-1"><?php echo "Advance, Deposits and Repayments";?></td>
                                					  <td class="gp-2"><span><?php echo number_format($adv-$qait,0);?></span></td>
                                					  <td class="gp-2"><span><?php echo number_format($advPrv-$qaitPrv,0);?></span></td>
                                					</tr> 
                                					
                                					<tr class="assets">
                            					        <td  class="gp-1"><?php echo "Advance Income Tax";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($ait,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($aitPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                            					        <td  class="gp-1"><?php echo "Cash and Cash esquivalant";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($csh,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($cshPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Curent Assets </td>
                                					  <td class="total-amount"><?php echo number_format($inv+$adv+$ait+$csh,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($invPrv+$advPrv+$aitPrv+$cshPrv,0);?></td>
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Total Assets </td>
                                					  <td class="total-amount"><?php echo number_format($closingbal1+$useassetbal+$deftax+$inv+$adv+$ait+$csh,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($closingbal1prv+$useassetbalprv+$deftaxprv+$invPrv+$advPrv+$aitPrv+$cshPrv,0);?></td>
                                					  
                                					</tr>
                                					<tr class="assets">
                                					  <td class="gp-2"> </td>
                                					  <td class="gp-2"></td>
                                					  <td class="gp-2"></td>
                                					  
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Equity </td>
                                					  
                                					  <!--th>&nbsp;</th -->
                                					  <td class="gp-2"></td>
                                					  <td class="gp-2"></td>
<?php 
$qcap="select COALESCE((m.closingbal),0) bal from coa_mon m where m.glno='201010000' and m.yr='$pyr' and m.mn=6";
$rescap = $conn->query($qcap);
if ($rescap->num_rows > 0){$rowcap = $rescap->fetch_assoc(); $cap= $rowcap["bal"];  }
    
$qcapPrv="select COALESCE((m.closingbal),0) bal from coa_mon m where m.glno='201010000' and m.yr='$prevyr' and m.mn=6";
$rescapPrv = $conn->query($qcapPrv);
if ($rescapPrv->num_rows > 0){$rowcapPrv = $rescapPrv->fetch_assoc(); $capPrv= $rowcapPrv["bal"];  }

$qret="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('201040000') and m.yr='$pyr' and m.mn=6";
$resret = $conn->query($qret);
if ($resret->num_rows > 0){$rowret = $resret->fetch_assoc(); $ret= $rowret["bal"];  }
    
$qretPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('201040000')  and m.yr='$prevyr' and m.mn=6";
$resretPrv = $conn->query($qretPrv);
if ($resretPrv->num_rows > 0){$rowretPrv = $resretPrv->fetch_assoc(); $retPrv= $rowretPrv["bal"];  }

$qleasncr="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('202030000') and m.yr='$pyr' and m.mn=6";
$resleasncr = $conn->query($qleasncr); 
if ($resleasncr->num_rows > 0){$rowleasncr = $resleasncr->fetch_assoc(); $leasncr= $rowleasncr["bal"];  } 
  echo $leasncr.'heloo';  
$qleasncrPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('202030000')  and m.yr='$prevyr' and m.mn=6";
$resleasncrPrv = $conn->query($qleasncrPrv);
if ($resleasncrPrv->num_rows > 0){$rowleasncrPrv = $resleasncrPrv->fetch_assoc(); $leasncrPrv= $rowleasncrPrv["bal"];  }

$qleascr="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203020000') and m.yr='$pyr' and m.mn=6";
$resleascr = $conn->query($qleascr);
if ($resleascr->num_rows > 0){$rowleascr = $resleascr->fetch_assoc(); $leascr= $rowleascr["bal"];  }
    
$qleascrPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203020000')  and m.yr='$prevyr' and m.mn=6";
$resleascrPrv = $conn->query($qleascrPrv);
if ($resleascrPrv->num_rows > 0){$rowleascrPrv = $resleascrPrv->fetch_assoc(); $leascrPrv= $rowleascrPrv["bal"];  }

$qadvprt="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203010400') and m.yr='$pyr' and m.mn=6";
$resadvprt = $conn->query($qadvprt);
if ($resadvprt->num_rows > 0){$rowadvprt = $resadvprt->fetch_assoc(); $advprt= $rowadvprt["bal"];  }
    
$qadvprtPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203010400')  and m.yr='$prevyr' and m.mn=6";
$resadvprtPrv = $conn->query($qadvprtPrv);
if ($resadvprtPrv->num_rows > 0){$rowadvprtPrv = $resadvprtPrv->fetch_assoc(); $advprtPrv= $rowadvprtPrv["bal"];  }

$qlndrctr="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203010200') and m.yr='$pyr' and m.mn=6";
$reslndrctr = $conn->query($qlndrctr);
if ($reslndrctr->num_rows > 0){$rowlndrctr = $reslndrctr->fetch_assoc(); $lndrctr= $rowlndrctr["bal"];  }
    
$qlndrctrPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203010200')  and m.yr='$prevyr' and m.mn=6";
$reslndrctrPrv = $conn->query($qlndrctrPrv);
if ($reslndrctrPrv->num_rows > 0){$rowlndrctrPrv = $reslndrctrPrv->fetch_assoc(); $lndrctrPrv= $rowlndrctrPrv["bal"];  }

$qoutliblt="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203030000','203050000') and m.yr='$pyr' and m.mn=6";
$resoutliblt = $conn->query($qoutliblt);
if ($resoutliblt->num_rows > 0){$rowoutliblt = $resoutliblt->fetch_assoc(); $outliblt= $rowoutliblt["bal"];  }
    
$qoutlibltPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203030000','203050000')  and m.yr='$prevyr' and m.mn=6";
$resoutlibltPrv = $conn->query($qoutlibltPrv);
if ($resoutlibltPrv->num_rows > 0){$rowoutlibltPrv = $resoutlibltPrv->fetch_assoc(); $outlibltPrv= $rowoutlibltPrv["bal"];  }

$qprovtax="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203050700') and m.yr='$pyr' and m.mn=6";
$resprovtax = $conn->query($qprovtax);
if ($resprovtax->num_rows > 0){$rowprovtax = $resprovtax->fetch_assoc(); $provtax= $rowprovtax["bal"];  }
    
$qprovtaxPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203050700')  and m.yr='$prevyr' and m.mn=6";
$resprovtaxPrv = $conn->query($qprovtaxPrv);
if ($resprovtaxPrv->num_rows > 0){$rowprovtaxPrv = $resprovtaxPrv->fetch_assoc(); $provtaxPrv= $rowprovtaxPrv["bal"];  }
#vat payble
$qvatpaybl="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203040000') and m.yr='$pyr' and m.mn=6";
$resvatpaybl = $conn->query($qvatpaybl);
if ($resvatpaybl->num_rows > 0){$rowvatpaybl = $resvatpaybl->fetch_assoc(); $vatpaybl= $rowvatpaybl["bal"];  }
    
$qvatpayblPrv="select sum(COALESCE((m.closingbal),0)) bal from coa_mon m where m.glno in ('203040000')  and m.yr='$prevyr' and m.mn=6";
$resvatpayblPrv = $conn->query($qvatpayblPrv);
if ($resvatpayblPrv->num_rows > 0){$rowvatpayblPrv = $resvatpayblPrv->fetch_assoc(); $vatpayblPrv= $rowvatpayblPrv["bal"];  }


$totequity=$cap+$ret;
$totequityprv=$capPrv+$retPrv;
$totnoncurliabilitis=$leasncr;
$totnoncurliabilitisprv=$leasncrPrv;
$totcurliabilitis=$leascr+$advprt+$lndrctr+$outliblt+$vatpaybl;
$totcurliabilitisprv=$leascrPrv+$advprtPrv+$lndrctrPrv+$outlibltPrv+$vatpayblPrv;
$toteqandliab=$totequity+$totnoncurliabilitis+$totcurliabilitis;
$toteqandliabprv=$totequityprv+$totnoncurliabilitisprv+$totcurliabilitisprv;
?> 
                                					</tr>
                                					<tr class="assets">
                            					        <td  class="gp-1"><?php echo "Share Capital";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($cap,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($capPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                            					        <td  class="gp-1"><?php echo "Retain Earning";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($ret,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($retPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Total Equity </td>
                                					  <td class="total-amount"><?php echo number_format($totequity,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($totequityprv,0);?></td>
                                					</tr>
                                					 <tr class="assets">
                            					        <td  class="gp-1"><?php echo "Lease Liabilities";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($leasncr,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($leasncrPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Non-current liabilities </td>
                                					  <td class="total-amount"><?php echo number_format($totnoncurliabilitis,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($totnoncurliabilitisprv,0);?></td>
                                					</tr>
                                					<tr class="assets">
                            					        <td  class="gp-1"><?php echo "Lease Liabilities current portion";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($leascr,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($leascrPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                            					        <td  class="gp-1"><?php echo "Advance received from party";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($advprt,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($advprtPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                            					        <td  class="gp-1"><?php echo "Loan from director";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($lndrctr,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($lndrctrPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                            					        <td  class="gp-1"><?php echo "Outstanding Liability";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($outliblt-$provtax,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($outlibltPrv-$provtaxPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                            					        <td  class="gp-1"><?php echo "Provision for tax expense";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($provtax,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($provtaxPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                            					        <td  class="gp-1"><?php echo "VAT payble";?></td>
                                					    <td class="gp-2"><span><?php echo number_format($vatpaybl,0);?></span></td>
                                					    <td class="gp-2"><span><?php echo number_format($vatpayblPrv,0);?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Current liabilities </td>
                                					  <td class="total-amount"><?php echo number_format($totcurliabilitis,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($totcurliabilitisprv,0);?></td>
                                					</tr>
                                					<tr class="assets">
                                					  <td class="total-title">Total equity and liabilities </td>
                                					  <td class="total-amount"><?php echo number_format($toteqandliab,0);?></td>
                                					  <td class="total-amount"><?php echo number_format($toteqandliabprv,0);?></td>
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
