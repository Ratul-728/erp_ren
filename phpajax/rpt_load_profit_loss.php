 
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
     border: 0px solid #efefef;
    padding: 0px;
}

.tbl-bhbs td:first-child{}
.tbl-bhbs td:nth-child(2),.tbl-bhbs td:nth-child(3){width: 100px; text-align: right;}

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

.tbl-bhbs td:nth-child(1),.tbl-bhbs td:nth-child(2), .tbl-bhbs th{
    border-right:1px solid #efefef;
}

/* gaps */
.tbl-bhbs td.gp-1{padding-left: 30px;}
.tbl-bhbs td.gp-2{padding-left: 60px;}
.tbl-bhbs td.gp-3{padding-left: 90px;}
.tbl-bhbs td.gp-4{padding-left: 120px;}


.total-title, .total-amount{font-weight: bold;}
.total-amountX{border-bottom: 0px solid #000!important;}
    

    
.last-amount{border-bottom: 1px solid #000!important;}	

.tbl-bhbs .end-parent{
    background-color: #f4e7e7;
    font-size: 16px;
}

.tbl-bhbs .end-parent .total-amount{
    border-bottom: 0px solid #000!important;
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
	
.tbl-bhbs td.bordertop{
    border-top: 2px solid #000!important;
}

    
    
.tbl-header{ background-color: #efefef;}
    
.tbl-footer{ background-color: #efefef;}    
    
.tbl-header td:first-child{}
    
.tbl-header td:nth-child(2),
.tbl-footer td:nth-child(2){
    width: 100px; 
    text-align: right;
}
 
    
.tbl-header td,.tbl-footer td{
    padding: 8px;
}   
    
.tr-parent{
    border:1px solid rgb(199,199,199);
}
.tr-parent > td:first-child{
    
    border-right:1px solid rgb(199,199,199);
}    
    
.tbl-bhbs .end-parent span {
  display: block;
  margin-bottom: 2px !important;
  border-bottom: 2px solid #111;
  padding: 5px;
}

.tbl-bhbs .end-parent .total-amount {
  border-bottom: 3px solid #000 !important;
}

.tbl-bhbs{
    border: 1px solid rgb(199,199,199);
}

.tbl-header{border-bottom: 1px solid #a7a7a7!important;}
	 
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
 
 
 
 <div class="panel panel-info">
      		            
			            <div class="panel-body">
							
							<div id="printSinglePage">
							
                            	<div class="row">
                        		

                        	    <div class="col-lg-12 col-md-12 col-sm-12">
									<div class="report-header">
										<div class="col1">
											<img src="../assets/images/site_setting_logo/logo_letterhead.png" alt="">
										</div>
										<div class="col2">
											<h1>Statement of Profit or Loss <br>or other comprehensive income</h1>
										</div>
										<hr>
									</div>
       
                                </div> 
                                <div class="col-lg-12">
      
                                </div>
                                <div class="po-product-wrapper"> 
                                    <div class="color-block">
 		                                
                                        <div class="col-lg-12 col-md-12">
                                            





 											<div class="tbl-bhbs-wrapper">
                                				<table class="tbl-bhbs" width="100%" border="0" cellspacing="0" cellpadding="0">
                                				  <tbody>
                                					<tr>
                                					  <td class="tbl-header total-title">In Taka <?php $prevyr=$pyr-1; $prevyrfrm=$prevyr-1;?></td>
                                					  <td class="tbl-header total-title"><?php echo '30/06/'.$pyr;?></td>
                                					  <td class="tbl-header total-title"><?php echo '30/06/'.$prevyr?></td>
                                					  
                                					</tr>
                                					
                                					
                                        			<tr>
                                        			    <?php //turnover
                                        			    $turnover=0;$turnoverprev=0;
                                					$lvl1_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=1";
                                					//echo $lvl1_t;die;
                                					$result1_t = $conn->query($lvl1_t);
                                					 if ($result1_t->num_rows > 0)
                                                        {
                                                            $row1_t = $result1_t->fetch_assoc();
                                                            $title1= $row1_t["title"];
                                                             $gl1= $row1_t["gl"];
                                                        }
                                					
                                					$lvl2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl1) and yr='$pyr' and mn=6";
                                					//echo $lvl2;die;
                                					$result2 = $conn->query($lvl2);
                                					 if ($result2->num_rows > 0)
                                                        {
                                                            $row2 = $result2->fetch_assoc();
                                                            $closingbal2= $row2["closingbal"];
                                                            
                                                        }
                                                    $lvl2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl1) and yr='$prevyr' and mn=6";
                                					//echo $lvl2prv;die;
                                					$result2prv = $conn->query($lvl2prv);    
                                                    if ($result2prv->num_rows > 0)
                                                        {
                                                            $row2prv = $result2prv->fetch_assoc();
                                                            $closingbal2prv= $row2prv["closingbal"];
                                                        } 
                                                        
                                					$turnover=$closingbal2;$turnoverprev=$closingbal2prv;
                                					?>
                                					  <td><?php echo $title1;?></td>
                                					  <td><span><?php echo number_format($turnover,2);?></span></td>
                                					  <td><span><?php echo number_format($turnoverprev,2);?></span></td>
                                					</tr> 
                                					
                                					<tr>
                                					    <?php //cogs
                                					    $cogs=0;$cogsprev=0;
                                					$lvl2_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=2";
                                				//	echo $lvl2_t;die;
                                					$result2_t = $conn->query($lvl2_t);
                                					 if ($result2_t->num_rows > 0)
                                                        {
                                                            $row2_t = $result2_t->fetch_assoc();
                                                            $title2= $row2_t["title"];
                                                             $gl2= $row2_t["gl"];
                                                        }
                                					
                                					$lvl2_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl2)and yr='$pyr' and mn=6";
                                				//	echo $lvl2_2;die;
                                					$result2_2 = $conn->query($lvl2_2);
                                					 if ($result2_2->num_rows > 0)
                                                        {
                                                            $row2_2 = $result2_2->fetch_assoc();
                                                            $closingbal2_2= $row2_2["closingbal"];
                                                        }
                                                        
                                                    $lvl2_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl2) and yr='$prevyr' and mn=6";
                                					$result2_2prv = $conn->query($lvl2_2prv);    
                                                    if ($result2_2prv->num_rows > 0)
                                                        {
                                                            $row2_2prv = $result2_2prv->fetch_assoc();
                                                            $closingbal2_2prv= $row2_2prv["closingbal"];
                                                        }  
                                                            
                                                    //closingstock for curr yr   
                                                    $qryclosingstock="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$pyr' and mn=6";
                                					$resultclosingstock = $conn->query($qryclosingstock);
                                					 if ($resultclosingstock->num_rows > 0)
                                                        {
                                                            $rowclosingstock = $resultclosingstock->fetch_assoc();
                                                            $closingstock= $rowclosingstock["closingbal"];
                                                        }    
                                                        
                                                    // opening stock for curr yr
                                                    $qtyopeningstock="SELECT sum(opbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$prevyr' and mn=7";
                                					//echo $lvl2_2prv_1;die;
                                					$result_openingstock = $conn->query($qtyopeningstock);
                                					 if ($result_openingstock->num_rows > 0)
                                                        {
                                                            $rowopeningstock = $result_openingstock->fetch_assoc();
                                                            $openingstock= $rowopeningstock["closingbal"];
                                                        }    
                                                        
                                                     //closingstock for prev yr   
                                                    $qryclosingstock_prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$prevyr' and mn=6";
                                					$resultclosingstock_prv= $conn->query($qryclosingstock_prv);
                                					 if ($resultclosingstock_prv->num_rows > 0)
                                                        {
                                                            $rowclosingstock_prv = $resultclosingstock_prv->fetch_assoc();
                                                            $closingstock_prv= $rowclosingstock_prv["closingbal"];
                                                        }    
                                                        
                                                    // opening stock for prev yr
                                                    $prevyrop=$prevyr-1;
                                                    $qry_openingstoc_prv="SELECT sum(opbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$prevyrop' and mn=7";
                                					//echo $lvl2_2prv_1;die;
                                					$result_openingstock_prv = $conn->query($qry_openingstoc_prv);
                                					 if ($result_openingstock_prv->num_rows > 0)
                                                        {
                                                            $rowopeningstovkprv = $result_openingstock_prv->fetch_assoc();
                                                            $openingstoc_prv= $rowopeningstovkprv["closingbal"];
                                                        }      
                                                            
                                                      $cogs=$closingbal2_2-$closingstock+$openingstock;$cogsprev=$closingbal2_2prv-$closingstock_prv+$openingstoc_prv;   
                                					?>
                                					  <td><?php echo $title2;?></td>
                                					  <td><span><?php echo number_format($cogs,2);?></span></td>
                                					  <td><span><?php echo number_format($cogsprev,2);?></span></td>
                                					</tr>
                                					
                                					<tr>
                                					     <td class="bordertop total-title">Gross Profit</td>
                                					    <td class="bordertop total-amount"><span><?php echo number_format(($turnover-$cogs),2);?></span></td>
                                					    <td class="bordertop total-amount"><span><?php echo number_format(($turnoverprev-$cogsprev),2);?></span></td>
                                					 </tr>
                                					 
                                					<tr><td colspan="3">&nbsp;</td></tr>
                                					 
                                					<tr>
                                					    <?php //administritive expense
                                					    $adminexp=0;$adminexpprv=0;
                                					$lvl3_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=3";
                                					//echo $lvl2_t;die;
                                					$result3_t = $conn->query($lvl3_t);
                                					 if ($result3_t->num_rows > 0)
                                                        {
                                                            $row3_t = $result3_t->fetch_assoc();
                                                            $title3= $row3_t["title"];
                                                             $gl3= $row3_t["gl"];
                                                        }
                                					
                                					$lvl3_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl3) and yr='$pyr' and mn=6";
                                					$result3_2 = $conn->query($lvl3_2);
                                					 if ($result3_2->num_rows > 0)
                                                        {
                                                            $row3_2 = $result3_2->fetch_assoc();
                                                            $closingbal3_2= $row3_2["closingbal"];
                                                        }
                                                    $lvl3_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl3) and yr='$prevyr' and mn=6";
                                					$result3_2prv = $conn->query($lvl3_2prv);    
                                                    if ($result3_2prv->num_rows > 0)
                                                        {
                                                            $row3_2prv = $result3_2prv->fetch_assoc();
                                                            $closingbal3_2prv= $row3_2prv["closingbal"];
                                                        } 
                                                      $adminexp= $closingbal3_2; $adminexpprv=$closingbal3_2prv;
                                					?>
                                					  <td><?php echo $title3;?></td>
                                					  <td><span><?php echo number_format($adminexp,2);?></span></td>
                                					  <td><span><?php echo number_format($adminexpprv,2);?></span></td>
                                					</tr> 
                                					
                                					<tr>
                                					    <?php //selling and marketing
                                					    $sellexp=0; $sellexpprv=0;
                                					$lvl4_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=4";
                                					//echo $lvl2_t;die;
                                					$result4_t = $conn->query($lvl4_t);
                                					 if ($result4_t->num_rows > 0)
                                                        {
                                                            $row4_t = $result4_t->fetch_assoc();
                                                            $title4= $row4_t["title"];
                                                             $gl4= $row4_t["gl"];
                                                        } 
                                					
                                					$lvl4_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl4) and yr='$pyr' and mn=6";
                                					$result4_2 = $conn->query($lvl4_2);
                                					 if ($result4_2->num_rows > 0)
                                                        {
                                                            $row4_2 = $result4_2->fetch_assoc();
                                                            $closingbal4_2= $row4_2["closingbal"];
                                                        }
                                                    $lvl4_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl4) and yr='$prevyr' and mn=6";
                                					$result4_2prv = $conn->query($lvl4_2prv);    
                                                    if ($result4_2prv->num_rows > 0)
                                                        {
                                                            $row4_2prv = $result4_2prv->fetch_assoc();
                                                            $closingbal4_2prv= $row4_2prv["closingbal"];
                                                        } 
                                                        
                                                        $sellexp=$closingbal4_2;$sellexpprv=$closingbal4_2prv;
                                					?>
                                					  <td ><?php echo $title4;?></td>
                                					  <td><span><?php echo number_format($sellexp,2);?></span></td>
                                					  <td><span><?php echo number_format($sellexpprv,2);?></span></td>
                                					</tr> 
                                					
                                					<tr>
                                					     <td  class="bordertop total-title">Operating Profit</td>
                                					    <td class="bordertop total-amount"><span><?php echo number_format(($turnover-$cogs-$adminexp-$sellexp),2);?></span></td>
                                					    <td class="bordertop total-amount"><span><?php echo number_format(($turnoverprev-$cogsprev-$adminexpprv-$sellexpprv),2);?></span></td>
                                					 </tr>
                                					<tr><td colspan="3">&nbsp;</td></tr>
                                					
                                					<tr>
                                					    <?php //financial expense
                                					    $finansexp=0;$finansexpprv=0;
                                					$lvl5_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=5";
                                					//echo $lvl2_t;die;
                                					$result5_t = $conn->query($lvl5_t);
                                					 if ($result5_t->num_rows > 0)
                                                        {
                                                            $row5_t = $result5_t->fetch_assoc();
                                                            $title5= $row5_t["title"];
                                                             $gl5= $row5_t["gl"];
                                                        }
                                					
                                					$lvl5_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl5) and yr='$pyr' and mn=6";
                                					$result5_2 = $conn->query($lvl5_2);
                                					 if ($result5_2->num_rows > 0)
                                                        {
                                                            $row5_2 = $result5_2->fetch_assoc();
                                                            $closingbal5_2= $row5_2["closingbal"];
                                                        }
                                                    $lvl5_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl5) and yr='$prevyr' and mn=6";
                                					$result5_2prv = $conn->query($lvl5_2prv);    
                                                    if ($result5_2prv->num_rows > 0)
                                                        {
                                                            $row5_2prv = $result5_2prv->fetch_assoc();
                                                            $closingbal5_2prv= $row5_2prv["closingbal"];
                                                        }  
                                                         $finansexp=$closingbal5_2;$finansexpprv=$closingbal5_2prv;
                                					?>
                                					  <td ><?php echo $title5;?></td>
                                					  <td><span><?php echo number_format($finansexp,2);?></span></td>
                                					  <td><span><?php echo number_format($finansexpprv,2);?></span></td>
                                					</tr> 
                                					
                                					<tr>
                                					     <td  class="bordertop total-title">Profit Before Tax</td>
                                					    <td class="bordertop total-amount"><span><?php echo number_format(($turnover-$cogs-$adminexp-$sellexp-$finansexp),2);?></span></td>
                                					    <td class="bordertop total-amount"><span><?php echo number_format(($turnoverprev-$cogsprev-$adminexpprv-$sellexpprv-$finansexpprv),2);?></span></td>
                                					 </tr>
                                					<tr><td colspan="3">&nbsp;</td></tr>
                                					 
                            					 	<tr>
                                					    <?php //tax expense
                                					    $taxexp=0; $taxexpprv=0;
                                					$lvl6_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=6;";
                                					//echo $lvl2_t;die;
                                					$result6_t = $conn->query($lvl6_t);
                                					 if ($result6_t->num_rows > 0)
                                                        {
                                                            $row6_t = $result6_t->fetch_assoc();
                                                            $title6= $row6_t["title"];
                                                             $gl6= $row6_t["gl"];
                                                        }
                                					
                                					$lvl6_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl6) and yr='$pyr' and mn=6";
                                					$result6_2 = $conn->query($lvl6_2);
                                					 if ($result6_2->num_rows > 0)
                                                        {
                                                            $row6_2 = $result6_2->fetch_assoc();
                                                            $closingbal6_2= $row6_2["closingbal"];
                                                        }
                                                    $lvl6_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl6) and yr='$prevyr' and mn=6";
                                					$result6_2prv = $conn->query($lvl6_2prv);    
                                                    if ($result6_2prv->num_rows > 0)
                                                        {
                                                            $row6_2prv = $result6_2prv->fetch_assoc();
                                                            $closingbal6_2prv= $row6_2prv["closingbal"];
                                                        }  
                                                         $taxexp=$closingbal6_2; $taxexpprv=$closingbal6_2prv;
                                					?>
                                					  <td ><?php echo $title6;?></td>
                                					  <td><span><?php echo number_format($taxexp,2);?></span></td>
                                					  <td><span><?php echo number_format($taxexpprv,2);?></span></td>
                                					</tr>
                                					
                                					<tr>
                                					    <?php 
                                					    // income tax
                                					    $inctax=0; $inctaxprv=0;
                                					$lvl7_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=7;";
                                					//echo $lvl2_t;die;
                                					$result7_t = $conn->query($lvl7_t);
                                					 if ($result7_t->num_rows > 0)
                                                        {
                                                            $row7_t = $result7_t->fetch_assoc();
                                                            $title7= $row7_t["title"];
                                                             $gl7= $row7_t["gl"];
                                                        }
                                					
                                					$lvl7_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl7) and yr='$pyr' and mn=6";
                                					$result7_2 = $conn->query($lvl7_2);
                                					 if ($result7_2->num_rows > 0)
                                                        {
                                                            $row7_2 = $result7_2->fetch_assoc();
                                                            $closingbal7_2= $row7_2["closingbal"];
                                                        }
                                                    $lvl7_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl7) and yr='$prevyr' and mn=6";
                                					$result7_2prv = $conn->query($lvl7_2prv);    
                                                    if ($result7_2prv->num_rows > 0)
                                                        {
                                                            $row7_2prv = $result7_2prv->fetch_assoc();
                                                            $closingbal7_2prv= $row7_2prv["closingbal"];
                                                        } 
                                                       $inctax=$closingbal7_2; $inctaxprv=$closingbal7_2prv;
                                                        
                                					?>
                                					  <td ><?php echo $title7;?></td>
                                					  <td><span><?php echo number_format($inctax,2);?></span></td>
                                					  <td><span><?php echo number_format($inctaxprv,2);?></span></td>
                                					</tr>
                                					
                                					<tr>
                                					    <td  class="title">Total Income Tax Expense </td>
                                					    <td class="total-amount"><span><?php echo number_format(($taxexp+$inctax),2);?></span></td>
                                					    <td class="total-amount"><span><?php echo number_format(($taxexpprv+$inctaxprv),2);?></span></td>
                                					 </tr>
                                					
                                					<tr><? //php echo $turnover.'-'.$cogs.'-'.$adminexp.'-'.$sellexp.'-'.$finansexp.'-'.$taxexp.'-'.$inctax;?>
                                					     <td  class="bordertop total-title">Profit/(loss) for the year</td>
                                					    <td class="bordertop total-amount"><span><?php echo number_format(($turnover-$cogs-$adminexp-$sellexp-$finansexp-$taxexp-$inctax),2);?></span></td>
                                					    <td class="bordertop total-amount"><span><?php echo number_format(($turnoverprev-$cogsprev-$adminexpprv-$sellexpprv-$finansexpprv-$taxexpprv-$inctaxprv),2);?></span></td>
                                					 </tr> 
                                					<tr><td colspan="3">&nbsp;</td></tr>
                            						<tr>
                                					    <?php //other comprehensive
                                					    $otherinc=0; $otherincprv=0;
                                					$lvl8_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=8;";
                                					//echo $lvl2_t;die;
                                					$result8_t = $conn->query($lvl8_t);
                                					 if ($result8_t->num_rows > 0)
                                                        {
                                                            $row8_t = $result8_t->fetch_assoc(); 
                                                            $title8= $row8_t["title"];
                                                             $gl8= $row8_t["gl"];
                                                        }
                                					
                                					$lvl8_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl8) and yr='$pyr' and mn=6";
                                					$result8_2 = $conn->query($lvl8_2);
                                					 if ($result8_2->num_rows > 0)
                                                        {
                                                            $row8_2 = $result8_2->fetch_assoc();
                                                            $closingbal8_2= $row8_2["closingbal"];
                                                        }
                                                    $lvl8_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl8) and yr='$prevyr' and mn=6";
                                					$result8_2prv = $conn->query($lvl8_2prv);    
                                                    if ($result8_2prv->num_rows > 0)
                                                        {
                                                            $row8_2prv = $result8_2prv->fetch_assoc();
                                                            $closingbal8_2prv= $row8_2prv["closingbal"];
                                                        } 
                                                        
                                                        $otherinc=$closingbal8_2;$otherincprv=$closingbal8_2prv;
                                					?>
                                					  <td ><?php echo $title8;?></td>
                                					  <td><span><?php echo number_format($otherinc,2);?></span></td>
                                					  <td><span><?php echo number_format($otherincprv,2);?></span></td>
                                					</tr>
                                					<tr><td colspan="3">&nbsp;</td></tr>
                                					<tr class="end-parent assets">
                                					     <td  class="total-title">Total Comprehensive Income</td>
                                					    <td class="bordertop total-amount"><span><?php echo number_format(($turnover-$cogs-$adminexp-$sellexp-$finansexp-$taxexp-$inctax+$otherinc),2);?></span></td>
                                					    <td class="bordertop total-amount"><span><?php echo number_format(($turnoverprev-$cogsprev-$adminexpprv-$sellexpprv-$finansexpprv-$taxexpprv-$inctaxprv+$otherincprv),2);?></span></td>
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
                    </div> 
