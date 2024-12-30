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
      		        
    
                        <div class="row">
                            <div class="col-lg-8 col-md-12">
                                <div class="bhbs-wrapper">
                            		<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="report-header">
										<div class="col1">
											<img src="../assets/images/site_setting_logo/logo_letterhead.png" alt="">
										</div>
										<div class="col2">
											<h1>Profit Loss</h1>
										</div>
										<div class="col2">
											<h1>From <?php echo $fd;?> to <?php echo $td;?> </h1>
										</div>
										<hr>
									</div>
       
                                </div> 
                                <div class="col-lg-12">
      
                                </div>       
                                	<div class="tbl-bhbs-wrapper">
                                        <table width="100%" class="tbl-parent"  border="0" cellspacing="0" cellpadding="0">
                                            <tr class="tr-parent">
                                                <!--first column title -->
                                                <td class="tbl-header" width="50%">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td  class="total-title">Expense</td>
                                                            <td class="total-amount" nowrap><span class="top-border"> </span></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <?php 
                                                $cogs="SELECT a.`glnm`,a.`opbal` opcst,b.`opbal` clcst FROM coa_mon a,coa_mon b where a.glno=b.glno and a.glno='102010100' and a.yr='2023' and a.mn=7 and b.yr='2024' and b.mn=7 ";
                                                $rescogs = $conn->query($cogs);
                                                if ($rescogs->num_rows > 0) 
                                                {
                                                    while ($rowcogs = $rescogs->fetch_assoc())
                                                    {
                                                        $opcost= $rowcogs["opcst"]; $clcost= $rowcogs["clcst"]*(-1);
                                                    }
                                                }
                                                ?>
                                                <!--second  column title -->
                                                <td class="tbl-header" width="50%">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td  class="total-title">Income</td>
                                                            <td class="total-amount" nowrap><span class="top-border"> </span></td>
                                                        </tr>
                                                    </table>                            
                                                </td>
                                            </tr>
                                            <tr class="tr-parent">
                                                <!--first table-->
                                                <td valign="top">
                                                    <table class="tbl-bhbs" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody> 
                                                            <tr>
                                                              <td class="gp-1">&nbsp;</td>
                                                              <td><strong>ACCOUNT</strong></td>
                                                              <td><strong>Amount</strong></td>
                                                            </tr>
<?php $gt=0;
$lvl1="SELECT c.glno,c.glnm FROM `coa` c  where substring(c.glno,1,1) in('4') and c.lvl=2 and c.oflag in('N')";
$result1 = $conn->query($lvl1);
if ($result1->num_rows > 0) 
{
    while ($row1 = $result1->fetch_assoc())
    { 
        $gl1= $row1["glno"]; $glnm1= $row1["glnm"]; $cl1=str_replace(' ','_',$glnm1);$cl=str_replace('&','_',$cl1);$totex=0;
        
?>
                                                            <tr class="<?php echo $cl;?>">
                                                              <td class="gp-0"><strong><?php echo $glnm1;?> </strong></td>
                                                              <td>&nbsp;</td>
                                                              <td>&nbsp;</td>
                                                            </tr>
<?php 
 if($gl1=="401000000")
 {?>
                                                            <tr>
                                                                <td class="gp-1">Opening Stock</td>
                                                                <td> <?php echo number_format($opcost,2);?> </td>
                                                                <td>&nbsp;</td>
                                                            </tr>
 <?php                                                           
 }
        $net=0;
        $lvl2="SELECT c.glno,c.glnm ,c.closingbal
FROM `coa` c  where c.ctlgl='$gl1' and c.oflag in('N')"; 
//echo $lvl2;die; 
/*
,(select sum(d.amount) from glmst m,gldlt d where  m.VouchNo=d.VouchNo and  d.glac =c.glno and d.dr_cr='C' and
   m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y')) cramount
,(select sum(d.amount) from glmst m,gldlt d where  m.VouchNo=d.VouchNo and  d.glac =c.glno and d.dr_cr='D' and
   m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y')) dramount  
*/
        $result2 = $conn->query($lvl2);
        if ($result2->num_rows > 0) 
        {
            while ($row2 = $result2->fetch_assoc())
            { 
                $gl2= $row2["glno"]; $glnm2= $row2["glnm"];$bal= $row2["closingbal"];$gl2amt=0;
                
                /*$amtqry="select sum(amt) amt from
                            (
                            select COALESCE(sum(amount),0) damt,0 camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='D'  and   d.glac=$gl2
                            union all
                            select COALESCE(sum(amount),0) damt,0 camt from gldlt,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='D' where glac in(select glno from coa where ctlgl=$gl2)
                            ) u"; 
                echo   $amtqry;die;*/           
                            
                $amtqry="select (sum(damt)-sum(camt)) amt from
                            (
                            select COALESCE(sum(d.amount),0) damt,0 camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='D'  and   d.glac='$gl2' and m.isfinancial in ('0','A') and
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select COALESCE(sum(d.amount),0) damt,0 camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='D' and d.glac in (select glno from coa where ctlgl='$gl2') and m.isfinancial in ('0','A') and
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
   
                            union all
                            select COALESCE(sum(d.amount),0) damt,0 camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='D' and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl2')) and m.isfinancial in ('0','A') and
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select 0 damt,COALESCE(sum(d.amount),0) camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='C'  and   d.glac='$gl2' and m.isfinancial in ('0','A') and 
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select 0 damt,COALESCE(sum(d.amount),0) camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='C' and d.glac in(select glno from coa where ctlgl='$gl2') and m.isfinancial in ('0','A') and 
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select 0 damt,COALESCE(sum(d.amount),0) camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='C' and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl2'))  and m.isfinancial in ('0','A') and 
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                           
                            ) u";
                    //echo  $amtqry;die;  
                    /*
                     union all
                            select 0 damt,COALESCE(sum(d.amount),0) camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='C'  and   d.glac='$gl2' and m.isfinancial in ('0','A') and 
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select 0 damt,COALESCE(sum(d.amount),0) camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='C' and d.glac in(select glno from coa where ctlgl='$gl2') and m.isfinancial in ('0','A') and 
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select 0 damt,COALESCE(sum(d.amount),0) camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='C' and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl2'))  and m.isfinancial in ('0','A') and 
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                    */
                $resultamt = $conn->query($amtqry);   
                while ($rowamt = $resultamt->fetch_assoc())
                {
                    $examt=$rowamt["amt"];
                }
               
                $totex=$totex+$examt+$gl2amt;$gt=$gt+$examt+$gl2amt;
                
                ?>
                                                            <tr>
                                                              <td class="gp-1"><?php echo $glnm2;?></td>
                                                              <td> <?php echo number_format($examt,2);?> </td>
                                                                <td>&nbsp;</td>
                                                            </tr>
<?php       }
        }
  if( $gl1=='401000000')
                {
               ?>
                        <!--tr>
                            <td class="gp-2">Opening Stock</td>
                            <td> <?php echo number_format($opcost,2);?> </td>
                            <td>&nbsp;</td>
                        </tr-->
                         <tr>
                            <td class="gp-1">Closing Stock</td>
                            <td> <?php echo number_format($clcost,2);?> </td>
                            <td>&nbsp;</td>
                        </tr>
               <?php
                 $gl2amt=$gl2amt+$opcost+$clcost;
                 $totex=$totex+$opcost+$clcost;
                 $gt=$gt+$opcost+$clcost;
                }
  ?>
 
                                                            <tr class="<?php echo $cl;?>">
                                                              <td class="gp-0  total-title">Total <?php echo $glnm1;?></td>
                                                              <td class="bordertop">&nbsp;</td>
                                                              <td class="bordertop total-amount"><?php echo number_format($totex,2);?> </td>
                                                            </tr>
<?php   
    }
}
?>                                                                
                                                       
                                                            
                                                        </tbody>
                                                    </table>   
                                                </td>
                                                    
                                                <!--second table-->
                                                <td valign="top">
                                                    <table class="tbl-bhbs" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                              <td class="gp-1">&nbsp;</td>
                                                              <td><strong>Detail</strong></td>
                                                              <td><strong>Total</strong></td>
                                                            </tr>
<?php
$gtinc=0;
$lvl1inc="SELECT c.glno,c.glnm FROM `coa` c  where substring(c.glno,1,1) in('3') and c.lvl=2 and c.oflag in('N')";
$result1inc = $conn->query($lvl1inc);
if ($result1inc->num_rows > 0) 
{
    while ($row1inc = $result1inc->fetch_assoc())
    {
        $gl1inc= $row1inc["glno"]; $glnm1inc= $row1["glnm"]; $cl1inc=str_replace(' ','_',$glnm1);$clinc=str_replace('&','_',$cl1);//$totex=0;
?>
                                                            <tr class="<?php echo $clinc;?>">
                                                              <td class="gp-0"><strong><?php echo $glnm1inc;?> </strong></td>
                                                              <td>&nbsp;</td>
                                                              <td>&nbsp;</td>
                                                            </tr>
<?php 
        $net=0;$totinc=0;
        $lvl2inc="SELECT c.glno,c.glnm ,c.closingbal FROM `coa` c  where c.ctlgl=$gl1inc "; 

/*
,(select sum(d.amount) from glmst m,gldlt d where  m.VouchNo=d.VouchNo and  d.glac =c.glno and d.dr_cr='C' and
   m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y')) cramount
,(select sum(d.amount) from glmst m,gldlt d where  m.VouchNo=d.VouchNo and  d.glac =c.glno and d.dr_cr='D' and
   m.transdt Between DATE_FORMAT('$fd', '%d/%m/%Y') and DATE_FORMAT('$td', '%d/%m/%Y')) dramount   
*/
        $result2inc = $conn->query($lvl2inc);
        if ($result2inc->num_rows > 0) 
        { 
            while ($row2inc = $result2inc->fetch_assoc())
            {
                $gl2inc= $row2inc["glno"]; $glnm2inc= $row2inc["glnm"];$bal= $row2["closingbal"];
                
                
                $amtqryi="select (sum(camt)-sum(damt)) amt from
                            (
                            select COALESCE(sum(d.amount),0) damt,0 camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='D'  and   d.glac='$gl2inc' and m.isfinancial in ('0','A') and
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select COALESCE(sum(d.amount),0) damt,0 camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='D' and d.glac in (select glno from coa where ctlgl='$gl2inc') and m.isfinancial in ('0','A') and
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select COALESCE(sum(d.amount),0) damt,0 camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='D' and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl2inc')) and m.isfinancial in ('0','A') and
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select 0 damt,COALESCE(sum(d.amount),0) camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='C'  and   d.glac='$gl2inc' and m.isfinancial in ('0','A') and
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select 0 damt,COALESCE(sum(d.amount),0) camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='C' and d.glac in(select glno from coa where ctlgl='$gl2inc') and m.isfinancial in ('0','A') and
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            union all
                            select 0 damt,COALESCE(sum(d.amount),0) camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='C' and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl2inc')) and m.isfinancial in ('0','A') and
   (m.transdt Between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y'))
                            ) u";
                    //echo  $amtqry;die;          
                $resultamti = $conn->query($amtqryi);   
                while ($rowamti = $resultamti->fetch_assoc())
                {
                    $incamt=$rowamti["amt"];
                }
                $totinc=$totinc+$incamt;$gtinc=$gtinc+$incamt;
                ?>
                                                            <tr>
                                                              <td class="gp-1"><?php echo $glnm2inc;?></td>
                                                              <td> <?php echo number_format($incamt,2);?> </td>
                                                                <td>&nbsp;</td>
                                                            </tr>
<?php       }
        }
?>        
                                                            <tr class="<?php echo $clinc;?>">
                                                              <td class="gp-0  total-title">Total <?php echo $glnm1inc;?></td>
                                                              <td class="bordertop">&nbsp;</td>
                                                              <td class="bordertop total-amount"><?php echo number_format($totinc,2);?> </td>
                                                            </tr>
<?php   
    }
}
$profit=$gtinc-$gt; if($profit>0){$gt=$gt+$profit;$porf=$profit;$loss=0;} else {$gtinc=$gtinc-$profit;$porf=0;$loss=0-$profit;}
?> 
                                                            
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="tr-parent">
                                                <!--first total row-->
                                                <td class="tbl-footer"> 
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td  class="total-title">Net Profit</td>
                                                          <td class="total-amount"><span class="top-border"> <?php echo number_format($porf,2);?> </span></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                
                                                <!--second total row-->
                                                <td class="tbl-footer">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr class="end-parent assets">
                                                          <td  class="total-title">Net Loss</td>
                                                          <td class="total-amount"><span class="top-border"> <?php echo number_format($loss,2);?> </span></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            
                                            <tr class="tr-parent">
                                                <!--first total row-->
                                                <td class="tbl-footer"> 
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td  class="total-title">TOTAL</td>
                                                          <td class="total-amount"><span class="top-border"> <?php echo number_format($gt,2);?> </span></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                
                                                <!--second total row-->
                                                <td class="tbl-footer">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr class="end-parent assets">
                                                          <td  class="total-title">TOTAL</td>
                                                          <td class="total-amount"><span class="top-border"> <?php echo number_format($gtinc,2);?> </span></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr> 
                                        </table>
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