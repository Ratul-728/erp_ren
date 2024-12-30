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
											<h1>Cash Flow Yearly</h1>
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
                                					  <td class="total-amount"><?php echo 'As on '.$monthName.','.$pyr;?></td>
                                					  <td class="total-amount"><?php echo 'As on '.$monthName.','.$prevyr;?></td>
                                					  
                                					</tr>
                            						<tr class="assets">
                                					  <td class="total-title">A. Cash Flow from operation activities </td>
                                					  <?php $prevyr=$pyr-1;?>
                                					  <!--th>&nbsp;</th -->
                                					  <td class="total-amount"></td>
                                					  <td class="total-amount"></td>
                                					  
                                					</tr>
                                					
                                        			<tr class="assets">
                                        			    <?php //Profit before tax
                                					
$lvl1="select 
(
(select sum(closingbal)
from coa_mon where glno in(SELECT `gl` FROM `rpt_gl_map` where `Report Menu`=94 and sl=1) and yr='$pyr' and mn=6)
-sum(closingbal)
+(SELECT sum(opbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$pyr' and mn=7)
-(SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$prevyr' and mn=6)
+(select sum(closingbal)
from coa_mon where glno in(SELECT `gl` FROM `rpt_gl_map` where `Report Menu`=94 and sl=8) and yr='$pyr' and mn=6) 
) pbt    
from coa_mon where glno in(SELECT `gl` FROM `rpt_gl_map` where `Report Menu`=94 and sl in(2,3,4,5)) and yr='$pyr' and mn=6";
                                					//echo $lvl1;die;
                                					$result1 = $conn->query($lvl1);
                                					 if ($result1->num_rows > 0)
                                                        {
                                                            $row1 = $result1->fetch_assoc();
                                                            $closingbal1= $row1["pbt"];

                                                    }
$bprevyr=$prevyr-1 ; 
$lvl1prv="select 
(
(select sum(closingbal)
from coa_mon where glno in(SELECT `gl` FROM `rpt_gl_map` where `Report Menu`=94 and sl=1) and yr='$prevyr' and mn=6)
-sum(closingbal)
+(SELECT sum(opbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$prevyr' and mn=7)
-(SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$bprevyr' and mn=6)
+(select sum(closingbal)
from coa_mon where glno in(SELECT `gl` FROM `rpt_gl_map` where `Report Menu`=94 and sl=8) and yr='$prevyr' and mn=6) 
) pbt    
from coa_mon where glno in(SELECT `gl` FROM `rpt_gl_map` where `Report Menu`=94 and sl in(2,3,4,5)) and yr='$prevyr' and mn=6";                                                
                                					//echo $lvl1prv;die;
                                					$result1prv = $conn->query($lvl1prv);    
                                                    if ($result1prv->num_rows > 0)
                                                        {
                                                            $row1prv = $result1prv->fetch_assoc();
                                                            $closingbal1prv= $row1prv["pbt"];
                                                        } 
                                					
                                					?>
                                					  <td  class="gp-1">Profit before Taxation</td>
                                					  <td class="gp-1"><span><?php echo $closingbal1;?></span></td>
                                					  <td class="gp-1"><span><?php echo $closingbal1prv;?></span></td>
                                					</tr> 
                                					
                                					<tr class="assets">
                                					    <?php  //prior period adjustment
                                					$lvl2="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=2;";
                                					//echo $lvl2_t;die;
                                					$result2 = $conn->query($lvl2);
                                					 if ($result2->num_rows > 0)
                                                        {
                                                            $row2 = $result2->fetch_assoc();
                                                            $title2= $row2["title"];
                                                             $gl2= $row2["gl"];
                                                        }
                                					
                                					$lvl2_d="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl2) and yr='$prevyr' and mn=6 ";
                                					$result2_d = $conn->query($lvl2_d);
                                					 if ($result2_d->num_rows > 0)
                                                        {
                                                            $row2_d = $result2_d->fetch_assoc();
                                                            $closingbal2_d= $row2_d["closingbal"];
                                                        }
                                                    $lvl2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl2) and yr='$bfrprevyr' and mon =6 ";
                                					$result2prv = $conn->query($lvl2prv);    
                                                    if ($result2prv->num_rows > 0)
                                                        {
                                                            $row2prv = $result2prv->fetch_assoc();
                                                            $closingbal2prv= $row2prv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-1"><?php echo $title2;?></td>
                                					  <td class="gp-1"><span><?php echo $closingbal2_d;?></span></td>
                                					  <td class="gp-1"><span><?php echo $closingbal2prv;?></span></td>
                                					</tr>
                                					
                                					<tr class="assets">
                                					     <td  class="total-title">Non Cash Adjustment</td>
                                					    <td class="total-amount"><span></span></td>
                                					    <td class="total-amount"><span></span></td>
                                					 </tr>
                                                    
                                					
                                					<tr class="assets">
                                					    <?php //depriciation
                                					$lvl3_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=3;";
                                					//echo $lvl2_t;die;
                                					$result3_t = $conn->query($lvl3_t);
                                					 if ($result3_t->num_rows > 0)
                                                        {
                                                            $row3_t = $result3_t->fetch_assoc();
                                                            $title3= $row3_t["title"];
                                                             $gl3= $row3_t["gl"];
                                                        }
                                					
                                					$lvl3_d="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl3) and yr='$pyr' and mn=6 ";
                                					$result3_d = $conn->query($lvl3_d);
                                					 if ($result3_d->num_rows > 0)
                                                        {
                                                            $row3_d = $result3_d->fetch_assoc();
                                                            $closingbal3_d= $row3_d["closingbal"];
                                                        }
                                                    $lvl3_dprv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl3) and yr='$prevyr' and mn=6";
                                					//echo $lvl3_dprv;die;
                                					$result3_dprv = $conn->query($lvl3_dprv);    
                                                    if ($result3_dprv->num_rows > 0)
                                                        {
                                                            $row3_dprv = $result3_dprv->fetch_assoc();
                                                            $closingbal3_dprv= $row3_dprv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title3;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal3_d;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal3_dprv;?></span></td>
                                					</tr> 
                                					
                                					<tr class="assets">
                                					    <?php  //depriciation on right use asset
                                					$lvl4_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=4;";
                                					//echo $lvl2_t;die;
                                					$result4_t = $conn->query($lvl4_t);
                                					 if ($result4_t->num_rows > 0)
                                                        {
                                                            $row4_t = $result4_t->fetch_assoc();
                                                            $title4= $row4_t["title"];
                                                             $gl4= $row4_t["gl"];
                                                        }
                                					
                                					$lvl4_d="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl4) and yr='$pyr' and mn=6";
                                					$result4_d = $conn->query($lvl4_d);
                                					 if ($result4_d->num_rows > 0)
                                                        {
                                                            $row4_d = $result4_d->fetch_assoc();
                                                            $closingbal4_d= $row4_d["closingbal"];
                                                        }
                                                    $lvl4_dprv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl4) and yr='$prevyr' and mn=6 ";
                                					$result4_dprv = $conn->query($lvl4_dprv);    
                                                    if ($result4_dprv->num_rows > 0)
                                                        {
                                                            $row4_dprv = $result4_dprv->fetch_assoc();
                                                            $closingbal4_dprv= $row4_dprv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title4;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal4_d;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal4_dprv;?></span></td>
                                					</tr> 
                                					
                                					<tr class="assets">
                                					    <?php //int charge on lease obligation
                                					$lvl5_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=5;";
                                					//echo $lvl2_t;die;
                                					$result5_t = $conn->query($lvl5_t);
                                					 if ($result5_t->num_rows > 0)
                                                        {
                                                            $row5_t = $result5_t->fetch_assoc();
                                                            $title5= $row5_t["title"];
                                                             $gl5= $row5_t["gl"];
                                                        }
                                					
                                					$lvl5_d="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl5) and yr='$pyr' and mn=6";
                                					$result5_d = $conn->query($lvl5_d);
                                					 if ($result5_d->num_rows > 0)
                                                        {
                                                            $row5_d = $result5_d->fetch_assoc();
                                                            $closingbal5_d= $row5_d["closingbal"];
                                                        }
                                                    $lvl5_dprv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl5) and yr='$prevyr' and mn=6";
                                					$result5_dprv = $conn->query($lvl5_dprv);    
                                                    if ($result5_dprv->num_rows > 0)
                                                        {
                                                            $row5_dprv = $result5_dprv->fetch_assoc();
                                                            $closingbal5_dprv= $row5_dprv["closingbal"];
                                                        }   
                                                        
                                                        $opprofit=$closingbal1+$closingbal2_d+$closingbal3_d+$closingbal4_d+$closingbal5_d;
                                                        $opprofitprv=$closingbal1prv+$closingbal2prv+$closingbal3_dprv+$closingbal4_dprv+$closingbal5_dprv;
                                					?>
                                					  <td  class="gp-2"><?php echo $title5;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal5_d;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal5_dprv;?></span></td> 
                                					</tr>
                                					
                                					<tr class="assets">
                                					     <td  class="total-title">Operating Profit changes in working capital</td>
                                					    <td class="total-amount"><span><?php echo $opprofit;?></span></td>
                                					    <td class="total-amount"><span><?php echo $opprofitprv;?></span></td>
                                					 </tr>
                                					<tr class="assets">
                                					     <td  class="total-title">Changes in working capital</td>
                                					    <td class="total-amount"><span></span></td>
                                					    <td class="total-amount"><span></span></td>
                                					 </tr>
                                					<tr class="assets">
                                					    <?php //inc_decr inventories
                                					$lvl6_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=6";
                                					//echo $lvld_t;die;
                                					$result6_t = $conn->query($lvl6_t);
                                					 if ($result6_t->num_rows > 0)
                                                        {
                                                            $row6_t = $result6_t->fetch_assoc();
                                                            $title6= $row6_t["title"];
                                                             $gl6= $row6_t["gl"];
                                                        }
                                					
                                					$bprevyr=$prevyr-1;
                                					$lvl6_d="SELECT (sum(opbal)-(SELECT sum(closingbal) FROM `coa_mon`  where glno ='102010100' and yr='$prevyr' and mn=6)) closingbal FROM `coa_mon` where glno ='102010100' and yr='$pyr' and mn=7";
                                					$result6_d = $conn->query($lvl6_d);
                                					 if ($result6_d->num_rows > 0)
                                                        {
                                                            $row6_d = $result6_d->fetch_assoc();
                                                            $closingbal6_d= $row6_d["closingbal"];
                                                        }
                                                    $lvl6_dprv="SELECT (sum(opbal)-(SELECT sum(closingbal) FROM `coa_mon`  where glno ='102010100' and yr='$bprevyr' and mn=6)) closingbal FROM `coa_mon` where glno ='102010100' and yr='$prevyr' and mn=7";
                                					$result6_dprv = $conn->query($lvl6_dprv);    
                                                    if ($result6_dprv->num_rows > 0)
                                                        {
                                                            $row6_dprv = $result6_dprv->fetch_assoc();
                                                            $closingbal6_dprv= $row6_dprv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title6;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal6_d;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal6_dprv;?></span></td>
                                					</tr>  
                                					<tr class="assets">
                                					    <?php //inc_decr  in advance,deposit and prepayment
                                					$lvl7_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=7";
                                					//echo $lvld_t;die;
                                					$result7_t = $conn->query($lvl7_t);
                                					 if ($result7_t->num_rows > 0)
                                                        {
                                                            $row7_t = $result7_t->fetch_assoc();
                                                            $title7= $row7_t["title"];
                                                             $gl7= $row7_t["gl"];
                                                        }
                                					
                                					$lvl7_d="SELECT (sum(closingbal)-(SELECT sum(closingbal) FROM `coa_mon`  where glno in($gl7) and yr='$prevyr' and mn=6)) closingbal FROM `coa_mon` where glno in($gl7) and yr='$pyr' and mn=6";
                                					
                                				//	SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl7) and yr='$pyr' ";
                                					$result7_d = $conn->query($lvl7_d);
                                					 if ($result7_d->num_rows > 0)
                                                        {
                                                            $row7_d = $result7_d->fetch_assoc();
                                                            $closingbal7_d= $row7_d["closingbal"];
                                                        }
                                                    $prevyrd=$prevyr-1;    
                                                    $lvl7_dprv="SELECT (sum(closingbal)-(SELECT sum(closingbal) FROM `coa_mon`  where glno in($gl7) and yr='$prevyrd' and mn=6)) closingbal FROM `coa_mon` where glno in($gl7) and yr='$prevyr' and mn=6";

                                                    
                                                  //  SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl7) and yr='$prevyr' ";
                                					$result7_dprv = $conn->query($lvl7_dprv);    
                                                    if ($result7_dprv->num_rows > 0)
                                                        {
                                                            $row7_dprv = $result7_dprv->fetch_assoc();
                                                            $closingbal7_dprv= $row7_dprv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title7;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal7_d;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal7_dprv;?></span></td>
                                					</tr> 
                                					<tr class="assets">
                                					    <?php //advance rcv frm parties
                                					$lvl8_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=8";
                                					//echo $lvld_t;die;
                                					$result8_t = $conn->query($lvl8_t);
                                					 if ($result8_t->num_rows > 0)
                                                        {
                                                            $row8_t = $result8_t->fetch_assoc();
                                                            $title8= $row8_t["title"];
                                                             $gl8= $row8_t["gl"];
                                                        }
                                					
                                					$lvl8_d="SELECT (sum(closingbal)-(SELECT sum(closingbal) FROM `coa_mon`  where glno in($gl8) and yr='$prevyr' and mn=6)) closingbal FROM `coa_mon` where glno in($gl8) and yr='$pyr' and mn=6";
                                					
                                					//SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl8) and yr='$pyr' ";
                                					$result8_d = $conn->query($lvl8_d);
                                					 if ($result8_d->num_rows > 0)
                                                        {
                                                            $row8_d = $result8_d->fetch_assoc();
                                                            $closingbal8_d= $row8_d["closingbal"];
                                                        }
                                                    $lvl8_dprv="SELECT (sum(closingbal)-(SELECT sum(closingbal) FROM `coa_mon`  where glno in($gl8) and yr='$prevyrd' and mn=6)) closingbal FROM `coa_mon` where glno in($gl8) and yr='$prevyr' and mn=6";
                                                    
                                                   // SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl8) and yr='$prevyr' ";
                                					$result8_dprv = $conn->query($lvl8_dprv);    
                                                    if ($result8_dprv->num_rows > 0)
                                                        {
                                                            $row8_dprv = $result8_dprv->fetch_assoc();
                                                            $closingbal8_dprv= $row8_dprv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title8;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal8_d;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal8_dprv;?></span></td>
                                					</tr> 
                                					<tr class="assets">
                                					    <?php //outstanding liabilities
                                					$lvl9_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=9";
                                					//echo $lvld_t;die;
                                					$result9_t = $conn->query($lvl9_t);
                                					 if ($result9_t->num_rows > 0)
                                                        {
                                                            $row9_t = $result9_t->fetch_assoc();
                                                            $title9= $row9_t["title"];
                                                             $gl9= $row9_t["gl"];
                                                        }
                                					
                                					$lvl9_d="SELECT (sum(closingbal)-(SELECT sum(closingbal) FROM `coa_mon`  where glno in($gl9) and yr='$prevyr' and mn=6)) closingbal FROM `coa_mon` where glno in($gl9) and yr='$pyr' and mn=6";
                                					//SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl9) and yr='$pyr' ";
                                					$result9_d = $conn->query($lvl9_d);
                                					 if ($result9_d->num_rows > 0)
                                                        {
                                                            $row9_d = $result9_d->fetch_assoc();
                                                            $closingbal9_d= $row9_d["closingbal"];
                                                        }
                                                    $lvl9_dprv="SELECT (sum(closingbal)-(SELECT sum(closingbal) FROM `coa_mon`  where glno in($gl9) and yr='$prevyrd' and mn=6)) closingbal FROM `coa_mon` where glno in($gl9) and yr='$prevyr' and mn=6";
                                                    //SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl9) and yr='$prevyr' ";
                                					$result9_dprv = $conn->query($lvl9_dprv);    
                                                    if ($result9_dprv->num_rows > 0)
                                                        {
                                                            $row9_dprv = $result9_dprv->fetch_assoc();
                                                            $closingbal9_dprv= $row9_dprv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title9;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal9_d;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal9_dprv;?></span></td>
                                					</tr> 
                                					<tr class="assets">
                                					    <?php 
                                					$lvl10_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=10";
                                					//echo $lvld_t;die;
                                					$result10_t = $conn->query($lvl10_t);
                                					 if ($result10_t->num_rows > 0)
                                                        {
                                                            $row10_t = $result10_t->fetch_assoc();
                                                            $title10= $row10_t["title"];
                                                             $gl10= $row10_t["gl"];
                                                        }
                                					
                                					$lvl10_d="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl10) and yr='$pyr' and mn=6 ";
                                					$result10_d = $conn->query($lvl10_d);
                                					 if ($result10_d->num_rows > 0)
                                                        {
                                                            $row10_d = $result10_d->fetch_assoc();
                                                            $closingbal10_d= $row10_d["closingbal"];
                                                        }
                                                    $lvl10_dprv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl10) and yr='$prevyr' and mn=6";
                                					$result10_dprv = $conn->query($lvl10_dprv);    
                                                    if ($result10_dprv->num_rows > 0)
                                                        {
                                                            $row10_dprv = $result10_dprv->fetch_assoc();
                                                            $closingbal10_dprv= $row10_dprv["closingbal"];
                                                        }   
                                                        
                                                        
                                                        $workcap= $closingbal6_d+$closingbal7_d+$closingbal8_d+$closingbal9_d+$closingbal10_d;
                                                        $workcapprv=$closingbal6_dprv+$closingbal7_dprv+$closingbal8_dprv+$closingbal9_dprv+$closingbal10_dprv;
                                					?>
                                					  <td  class="gp-2"><?php echo $title10;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal10_d;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal10_dprv;?></span></td>
                                					</tr> 
                                					
                                					
                                					<tr class="assets">
                                					     <td  class="total-title">Net changes in working capital</td>
                                					    <td class="total-amount"><span><?php echo $workcap;?></span></td>
                                					    <td class="total-amount"><span><?php echo $workcapprv;?></span></td>
                                					 </tr>
                                					
                                					 
                            					 	<tr class="assets">
                                					    <?php 
                                					$lvl11_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=11";
                                					//echo $lvl2_t;die;
                                					$result11_t = $conn->query($lvl11_t);
                                					 if ($result11_t->num_rows > 0)
                                                        {
                                                            $row11_t = $result11_t->fetch_assoc();
                                                            $title11= $row11_t["title"];
                                                             $gl11= $row11_t["gl"];
                                                        }
                                					
                                					$lvl11_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl11) and yr='$pyr' and mn=6";
                                					$result11_2 = $conn->query($lvl11_2);
                                					 if ($result11_2->num_rows > 0)
                                                        {
                                                            $row11_2 = $result11_2->fetch_assoc();
                                                            $closingbal11_2= $row11_2["closingbal"];
                                                        }
                                                    $lvl11_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl11) and yr='$prevyr' and mn=6";
                                					$result11_2prv = $conn->query($lvl11_2prv);    
                                                    if ($result11_2prv->num_rows > 0)
                                                        {
                                                            $row11_2prv = $result11_2prv->fetch_assoc();
                                                            $closingbal11_2prv= $row11_2prv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title11;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal11_2;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal11_2prv;?></span></td>
                                					</tr>
                                					
                                					<tr class="assets">
                                					    <?php 
                                					$lvl12_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=12;";
                                					//echo $lvl2_t;die;
                                					$result12_t = $conn->query($lvl12_t);
                                					 if ($result12_t->num_rows > 0)
                                                        {
                                                            $row12_t = $result12_t->fetch_assoc();
                                                            $title12= $row12_t["title"];
                                                             $gl12= $row12_t["gl"];
                                                        }
                                					
                                					$lvl12_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl12) and yr='$pyr' and mn=6";
                                					$result12_2 = $conn->query($lvl12_2);
                                					 if ($result12_2->num_rows > 0)
                                                        {
                                                            $row12_2 = $result12_2->fetch_assoc();
                                                            $closingbal12_2= $row12_2["closingbal"]; 
                                                        }
                                                    $lvl12_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl12) and yr='$prevyr' and mn=6 ";
                                					$result12_2prv = $conn->query($lvl12_2prv);    
                                                    if ($result12_2prv->num_rows > 0)
                                                        {
                                                            $row12_2prv = $result12_2prv->fetch_assoc();
                                                            $closingbal12_2prv= $row12_2prv["closingbal"];
                                                        }  
                                                        
                                                        //$opprofit,$opprofitprv,$workcap,$workcapprv
                                                        $netcshop=$opprofit+$workcap+$closingbal11_2+$closingbal12_2;
                                                        $netcshopprv=$opprofitprv+$workcapprv+$closingbal11_2prv+$closingbal12_2prv;
                                					?>
                                					  <td  class="gp-2"><?php echo $title12;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal12_2;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal12_2prv;?></span></td>
                                					</tr>
                                					
                                					<tr class="assets">
                                					     <td  class="title">Net cash generated from operating activities</td>
                                					    <td class="total-amount"><span><?php echo $netcshop;?></span></td>
                                					    <td class="total-amount"><span><?php echo $netcshopprv;?></span></td>
                                					 </tr>
                                					
                                					<tr class="assets">
                                					     <td  class="total-title">B : Cash flow from investing  activities:</td>
                                					    <td class="total-amount"></td>
                                					    <td class="total-amount"></td>
                                					 </tr> 
                                					
                            						<tr class="assets">
                                					    <?php //purchase
                                					$lvl13_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=13";
                                					//echo $lvl2_t;die;
                                					$result13_t = $conn->query($lvl13_t);
                                					 if ($result13_t->num_rows > 0)
                                                        {
                                                            $row13_t = $result13_t->fetch_assoc();
                                                            $title13= $row13_t["title"];
                                                             $gl13= $row13_t["gl"];
                                                        }
                                					
                                					$lvl13_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl13) and yr='$pyr'  and mn=6";
                                					$result13_2 = $conn->query($lvl13_2);
                                					 if ($result13_2->num_rows > 0)
                                                        {
                                                            $row13_2 = $result13_2->fetch_assoc();
                                                            $closingbal13_2= $row13_2["closingbal"];
                                                        }
                                                    $lvl13_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl13) and yr='$prevyr' and mn=6";
                                					$result13_2prv = $conn->query($lvl13_2prv);    
                                                    if ($result13_2prv->num_rows > 0)
                                                        {
                                                            $row13_2prv = $result13_2prv->fetch_assoc();
                                                            $closingbal13_2prv= $row13_2prv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title13;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal13_2;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal13_2prv;?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					    <?php //intangible asset
                                					$lvl16_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=16";
                                					//echo $lvl2_t;die;
                                					$result16_t = $conn->query($lvl16_t);
                                					 if ($result16_t->num_rows > 0)
                                                        {
                                                            $row16_t = $result16_t->fetch_assoc();
                                                            $title16= $row16_t["title"];
                                                             $gl16= $row16_t["gl"];
                                                        }
                                					
                                					$lvl16_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl16) and yr='$pyr'  and mn=6";
                                					$result16_2 = $conn->query($lvl16_2);
                                					 if ($result16_2->num_rows > 0)
                                                        {
                                                            $row16_2 = $result16_2->fetch_assoc();
                                                            $closingbal16_2= $row16_2["closingbal"];
                                                        }
                                                    $lvl16_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl16) and yr='$prevyr' and mn=6";
                                					$result16_2prv = $conn->query($lvl16_2prv);    
                                                    if ($result16_2prv->num_rows > 0)
                                                        {
                                                            $row16_2prv = $result16_2prv->fetch_assoc();
                                                            $closingbal16_2prv= $row16_2prv["closingbal"];
                                                        }     
                                                        $netcsinvest=$closingbal13+$closingbal16_2;
                                                        $netcsinvestprv=$closingbal13prv+$closingbal16_2prv;
                                					?>
                                					  <td  class="gp-2"><?php echo $title16;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal16_2;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal16_2prv;?></span></td>
                                					</tr>
                                					
                                					<tr class="end-parent assets">
                                					     <td  class="total-title">Net cash used in investing activities </td>
                                					    <td class="total-amount"><span><?php echo $netcsinvest;?></span></td>
                                					    <td class="total-amount"><span><?php echo $netcsinvestprv;?></span></td>
                            					    </tr> 
                                					 <tr class="end-parent assets">
                                					     <td  class="total-title">C: Cash Flow from financing activities:</td>
                                					    <td class="total-amount"></td>
                                					    <td class="total-amount"></td>
                                					 </tr> 
                                					 
                                					 <tr class="assets">
                                					    <?php //loan from director
                                					$lvl14_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=14";
                                					//echo $lvl2_t;die;
                                					$result14_t = $conn->query($lvl14_t);
                                					 if ($result14_t->num_rows > 0)
                                                        {
                                                            $row14_t = $result14_t->fetch_assoc();
                                                            $title14= $row14_t["title"];
                                                             $gl14= $row14_t["gl"];
                                                        }
                                					
                                					$lvl14_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl14) and yr='$pyr' and mn=6";
                                					$result14_2 = $conn->query($lvl14_2);
                                					 if ($result14_2->num_rows > 0)
                                                        {
                                                            $row14_2 = $result14_2->fetch_assoc();
                                                            $closingbal14_2= $row14_2["closingbal"];
                                                        }
                                                    $lvl14_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl14) and yr='$prevyr' and mn=6";
                                					$result14_2prv = $conn->query($lvl14_2prv);    
                                                    if ($result14_2prv->num_rows > 0)
                                                        {
                                                            $row14_2prv = $result14_2prv->fetch_assoc();
                                                            $closingbal14_2prv= $row14_2prv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title14;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal14_2;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal14_2prv;?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					    <?php //lonmg term loan
                                					$lvl15_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=15";
                                					//echo $lvl2_t;die;
                                					$result15_t = $conn->query($lvl15_t);
                                					 if ($result15_t->num_rows > 0)
                                                        {
                                                            $row15_t = $result15_t->fetch_assoc();
                                                            $title15= $row15_t["title"];
                                                             $gl15= $row15_t["gl"];
                                                        }
                                					
                                					$lvl15_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl15) and yr='$pyr' and mn=6 ";
                                					$result15_2 = $conn->query($lvl15_2);
                                					 if ($result15_2->num_rows > 0)
                                                        {
                                                            $row15_2 = $result15_2->fetch_assoc();
                                                            $closingbal15_2= $row15_2["closingbal"];
                                                        }
                                                    $lvl15_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl15) and yr='$prevyr' and mn=6";
                                					$result15_2prv = $conn->query($lvl15_2prv);    
                                                    if ($result15_2prv->num_rows > 0)
                                                        {
                                                            $row15_2prv = $result15_2prv->fetch_assoc();
                                                            $closingbal15_2prv= $row15_2prv["closingbal"];
                                                        }   
                                                        $netcshfinact=$closingbal14+$closingbal15;
                                                        $netcshfinactprv=$closingbal14prv+$closingbal5prv;
                                					?>
                                					  <td  class="gp-2"><?php echo $title15;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal15_2;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal15_2prv;?></span></td>
                                					</tr>
                                        			<tr class="end-parent assets">
                                					     <td  class="total-title">Net cash generated/ (used) in financing activities </td>
                                					    <td class="total-amount"><span><?php echo $netcshfinact;?></span></td>
                                					    <td class="total-amount"><span><?php echo $netcshfinactprv;?></span></td>
                            					    </tr>
                            					    <tr class="end-parent assets">
                                					     <td  class="total-title">D: Net cash flows (A+B+C) </td>
                                					    <td class="total-amount"><span><?php echo $netcshop+$netcsinvest+$netcshfinact;?></span></td>
                                					    <td class="total-amount"><span><?php echo $netcshopprv+$netcsinvestprv+$netcshfinactprv;?></span></td>
                            					    </tr>
                            					    <tr class="end-parent assets">
                                					     <td  class="total-title">Cash and cash equivalents at beginig of the year </td>
                                					     <?php //cash equivalant begining
                                					$lvl19_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=19";
                                					//echo $lvl2_t;die;
                                					$result19_t = $conn->query($lvl19_t);
                                					 if ($result19_t->num_rows > 0)
                                                        {
                                                            $row19_t = $result19_t->fetch_assoc();
                                                            $title19= $row19_t["title"];
                                                             $gl19= $row19_t["gl"];
                                                        }
                                					
                                					$lvl19_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl19) and yr='$pyr' and mn=6 ";
                                					$result19_2 = $conn->query($lvl19_2);
                                					 if ($result19_2->num_rows > 0)
                                                        {
                                                            $row19_2 = $result19_2->fetch_assoc();
                                                            $closingbal19_2= $row19_2["closingbal"];
                                                        }
                                                    $lvl19_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl19) and yr='$prevyr' and mn=6 ";
                                					$result19_2prv = $conn->query($lvl19_2prv);    
                                                    if ($result19_2prv->num_rows > 0)
                                                        {
                                                            $row19_2prv = $result19_2prv->fetch_assoc();
                                                            $closingbal19_2prv= $row19_2prv["closingbal"];
                                                        }     
                                					?>
                                					    <td class="total-amount"><span><?php echo $closingbal19_2;?></span></td>
                                					    <td class="total-amount"><span><?php echo $closingbal19_2prv;?></span></td>
                            					    </tr>
                                        			<tr class="end-parent assets">
                                					     <td  class="total-title">Cash and cash equivalents at end of the year </td>
                                					    <td class="total-amount"><span><?php echo $netcshop+$netcsinvest+$netcshfinact+$closingbal19_2;?></span></td>
                                					    <td class="total-amount"><span><?php echo $netcshopprv+$netcsinvestprv+$netcshfinactprv+$closingbal19_2prv;?></span></td>
                            					    </tr>
                            					    <tr class="end-parent assets">
                                					     <td  class="total-title">E: Cash and cash equivalents at end of the year </td>
                                					    <td class="total-amount"></td>
                                					    <td class="total-amount"></td>
                            					    </tr>
                            					    <tr class="assets">
                                					    <?php //cash in hand
                                					$lvl17_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=17";
                                					//echo $lvl2_t;die;
                                					$result17_t = $conn->query($lvl17_t);
                                					 if ($result17_t->num_rows > 0)
                                                        {
                                                            $row17_t = $result17_t->fetch_assoc();
                                                            $title17= $row17_t["title"];
                                                             $gl17= $row17_t["gl"];
                                                        }
                                					
                                					$lvl17_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl17) and yr='$pyr' and mn=6 ";
                                					$result17_2 = $conn->query($lvl17_2);
                                					 if ($result17_2->num_rows > 0)
                                                        {
                                                            $row17_2 = $result17_2->fetch_assoc();
                                                            $closingbal17_2= $row17_2["closingbal"];
                                                        }
                                                    $lvl17_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl17) and yr='$prevyr' and mn=6 ";
                                					$result17_2prv = $conn->query($lvl17_2prv);    
                                                    if ($result17_2prv->num_rows > 0)
                                                        {
                                                            $row17_2prv = $result17_2prv->fetch_assoc();
                                                            $closingbal17_2prv= $row17_2prv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title17;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal17_2;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal17_2prv;?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					    <?php //cash at bank
                                					$lvl18_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=18";
                                					//echo $lvl2_t;die;
                                					$result18_t = $conn->query($lvl18_t);
                                					 if ($result18_t->num_rows > 0)
                                                        {
                                                            $row18_t = $result18_t->fetch_assoc();
                                                            $title18= $row18_t["title"];
                                                             $gl18= $row18_t["gl"];
                                                        }
                                					
                                					$lvl18_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl18) and yr='$pyr' and mn=6 ";
                                					$result18_2 = $conn->query($lvl18_2);
                                					 if ($result18_2->num_rows > 0)
                                                        {
                                                            $row18_2 = $result18_2->fetch_assoc();
                                                            $closingbal18_2= $row18_2["closingbal"];
                                                        }
                                                    $lvl18_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl18) and yr='$prevyr' and mn=6";
                                					$result18_2prv = $conn->query($lvl18_2prv);    
                                                    if ($result18_2prv->num_rows > 0)
                                                        {
                                                            $row18_2prv = $result18_2prv->fetch_assoc();
                                                            $closingbal18_2prv= $row18_2prv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title18;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal18_2;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal18_2prv;?></span></td>
                                					</tr>
                                					<tr class="assets">
                                					    <?php //cash at bank
                                					$lvl18_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=160 and sl=18";
                                					//echo $lvl2_t;die;
                                					$result18_t = $conn->query($lvl18_t);
                                					 if ($result18_t->num_rows > 0)
                                                        {
                                                            $row18_t = $result18_t->fetch_assoc();
                                                            $title18= $row18_t["title"];
                                                             $gl18= $row18_t["gl"];
                                                        }
                                					
                                					$lvl18_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl18) and yr='$pyr' and mn=6 ";
                                					$result18_2 = $conn->query($lvl18_2);
                                					 if ($result18_2->num_rows > 0)
                                                        {
                                                            $row18_2 = $result18_2->fetch_assoc();
                                                            $closingbal18_2= $row18_2["closingbal"];
                                                        }
                                                    $lvl18_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl18) and yr='$prevyr' and mn=6";
                                					$result18_2prv = $conn->query($lvl18_2prv);    
                                                    if ($result18_2prv->num_rows > 0)
                                                        {
                                                            $row18_2prv = $result18_2prv->fetch_assoc();
                                                            $closingbal18_2prv= $row18_2prv["closingbal"];
                                                        }     
                                					?>
                                					  <td  class="gp-2"><?php echo $title18;?></td>
                                					  <td class="gp-2"><span><?php echo $closingbal18_2;?></span></td>
                                					  <td class="gp-2"><span><?php echo $closingbal18_2prv;?></span></td>
                                					</tr>
                                					<tr class="end-parent assets">
                                					    <td  class="total-title">Cash and cash equivalents at end of the year </td>
                                					    <td class="total-amount"><span><?php echo $closingbal17_2+$closingbal18_2?></span></td>
                                					    <td class="total-amount"><span><?php echo $closingbal17_2prv+$closingbal18_2prv;?></span></td>
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
