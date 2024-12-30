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
    width: 100%
    }
    
    .report-header  td{
        border: 0px solid #000;
        width: 33%;
        vertical-align: middle!important;
        padding: 20px 0;
         
    }
    
    .report-header  td img{
        width:300px;
    }
    
    .report-header  td.col1{
        
    }
    
    .report-header  td.col2{
        text-align: center;
    }
    
    .report-header  td.col3{
        text-align: right;
    }
    .report-header  td h1{
        font-size: 20px!important;
        font-family: roboto;
        margin-bottom: 0px;
        padding-top: 10px;
        
    }	
 
/* end new print code */	 	 
  
    </style>
 <div c lass="panel panel-info" id="printSinglePage">
      		        
    
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="bhbs-wrapper">
                            		<div class="col-lg-12 col-md-12 col-sm-12">
    									<table class="report-header">
    									    <tr>
    										<td class="col1">
    											<img src="../assets/images/site_setting_logo/logo_letterhead.png" width="200" alt="">
    										</td>
    										<td class="col2">
    											<h1>Profit/Loss Statement</h1>
    										</td>
    										<td class="col3">
    											<h1>From <?php echo $fd;?> to <?php echo $td;?> </h1>
    										</td>
    										
    										<tr>
    									</table>
           
                                    </div> 
                                    <div class="col-lg-12">
                                        <div class="tbl-bhbs-wrapper">
                                        <table width="100%" class="tbl-parent"  border="1" cellspacing="0" cellpadding="0">
                                            <tr class="tr-parent">
                                                <!--first column title -->
                                                <td class="tbl-header" width="50%" style="width:50%">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td  class="total-title">Expense</td>
                                                            <td  class="total-amount" nowrap><span class="top-border"> </span></td>
                                                        </tr>
                                                    </table> 
                                                </td>
                                                <?php 
                                                $cogs="SELECT a.`glnm`,a.`opbal` opcst,b.`opbal` clcst FROM coa_mon a,coa_mon b where a.glno=b.glno and a.glno='102010100' and a.yr='$fyr' and a.mn=$fmn and b.yr='$tyr' and b.mn=$tmn+1";
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
                                                <td class="tbl-header" width="50%" style="width:50%">
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
                                                              <td><strong>Detail</strong></td>
                                                              <td><strong>Total</strong></td>
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
        
       if( $gl1=='401000000')
                 {
                ?>
                <tr>
                    <td class="gp-1">Opening Stock</td>
                    <td> <?php echo number_format($opcost,2);?> </td>
                    <td>&nbsp;</td>
                </tr>
                <?php }
        
        
        $net=0;
        $lvl2="SELECT c.glno,c.glnm ,c.closingbal 
FROM `coa` c  where c.ctlgl='$gl1' and c.oflag in('N') "; 
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
                $gl2= $row2["glno"]; $glnm2= $row2["glnm"];$bal= $row2["closingbal"];
                ?>
                <tr>
                  <td class="gp-1"><?php echo $glnm2;?></td>
                  <td> &nbsp; </td>
                    <td>&nbsp;</td>
                </tr>
                
                  <?php
                //$totex=$totex+$opcost+$clcost;$gt=$gt+$opcost+$clcost;
                //$gl2amt=$gl2amt+$opcost;
                /*$amtqry="select sum(amt) amt from
                            (
                            select COALESCE(sum(amount),0) damt,0 camt from gldlt d,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='D'  and   d.glac=$gl2
                            union all
                            select COALESCE(sum(amount),0) damt,0 camt from gldlt,glmst m where m.VouchNo=d.VouchNo and  d.dr_cr='D' where glac in(select glno from coa where ctlgl=$gl2)
                            ) u"; 
                echo   $amtqry;die;
                ,COALESCE((select  sum(d.amount) from  gldlt d,glmst m where d.vouchno=m.vouchno and d.glac=gl.glno and d.dr_cr='C' and (m.transdt between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y')) and m.isfinancial in('0','A')
 ),0)
                */           
                $gl2amt=0;            
                $amtqry="SELECT     gl.glno,    gl.glnm,    gl.opbal,
    tr.debit AS debit,
    tr.credit AS credit
FROM (   
    SELECT  glno, glnm,  opbal FROM coa_mon WHERE yr ='$fyr'   AND mn =7 AND isposted = 'P'  AND oflag = 'N' 
      AND ( ctlgl IN ( SELECT glno FROM coa_mon WHERE yr = '$fyr' AND mn =7  AND ctlgl = '$gl2'  )
          OR ctlgl = '$gl2'
          OR glno = '$gl2'
      	)
	) gl,
    (
    	select a.glac,sum(da) debit,sum(ca) credit from 
        (
        select d.glac,d.dr_cr ,(case WHEN d.dr_cr='D' then sum(d.amount) else 0 end) da,(case WHEN d.dr_cr='C' then sum(d.amount) else 0 end) ca
        from glmst m, gldlt d where m.vouchno=d.vouchno and m.isfinancial in('0','A') and m.transdt between '$fd' AND '$td'
        and substr(d.glac,1,1)=4 group by d.glac,d.dr_cr
        )a group by a.glac
    )tr  where gl.glno=tr.glac";
                
                /*"SELECT     gl.glno,    gl.glnm,    gl.opbal,
    COALESCE( ( SELECT SUM(d.amount)  FROM gldlt d JOIN glmst m ON d.vouchno = m.vouchno  WHERE d.glac = gl.glno  AND d.dr_cr = 'D'  AND m.transdt BETWEEN '$fd' AND '$td'  AND m.isfinancial IN ('0', 'A')), 0) AS debit,
    0 AS credit
FROM (   
    SELECT  glno, glnm,  opbal FROM coa_mon WHERE yr ='$fyr'   AND mn =$fmn AND isposted = 'P'  AND oflag = 'N' 
      AND ( ctlgl IN ( SELECT glno FROM coa_mon WHERE yr = '$fyr' AND mn =$fmn  AND ctlgl = '$gl2'  )
          OR ctlgl = '$gl2'
          OR glno = '$gl2'
      	)
	) gl";
                
                "select gl.glno,gl.glnm,gl.opbal
,COALESCE((select  sum(d.amount) from  gldlt d,glmst m where d.vouchno=m.vouchno and d.glac=gl.glno and d.dr_cr='D' and (m.transdt between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y')) and m.isfinancial in('0','A')
 ),0) debit
  ,0 credit
 from 
(
select glno,glnm,opbal from coa_mon where yr='2023' and mn=7 and ctlgl in(select glno from coa_mon where yr='2023' and mn=7 and ctlgl='$gl2') and isposted='P' and oflag='N'
 union all 
 select glno,glnm,opbal from coa_mon where yr='2023' and mn=7 and ctlgl='$gl2' and isposted='P' and oflag='N'
 union all 
 select glno,glnm,opbal from coa_mon where yr='2023' and mn=7 and glno='$gl2' and isposted='P' and oflag='N') gl  "; */
                   // if($gl2=='402150000'){echo  $amtqry;}       
                $resultamt = $conn->query($amtqry);   
                while ($rowamt = $resultamt->fetch_assoc())
                {
                    $glno3=$rowamt["glno"];$glnm3=$rowamt["glnm"];
                    $examt=$rowamt["debit"]-$rowamt["credit"];
                    $gl2amt=$gl2amt+$examt;
                    ?>
                        <tr>
                            <td class="gp-2"><?php echo $glnm3;?></td>
                            <td> <?php echo number_format($examt,2);?> </td>
                            <td>&nbsp;</td>
                        </tr>
                    
               <?php
                //$totex=$totex+$opcost+$clcost;$gt=$gt+$opcost+$clcost;
                
                }
                $totex=$totex+$gl2amt;$gt=$gt+$gl2amt;
                ?>
                                                            <tr>
                                                                <td class="gp-1">Total <?php echo $glnm2;?></td>
                                                                <td>&nbsp;</td>
                                                                <td> <?php echo number_format($gl2amt,2);?> </td>
                                                            </tr>
                                                            
<?php       }
                                                    if( $gl1=='401000000')
                                                    {
                                                    ?>
                                                    <tr>
                                                        <td class="gp-1">Closing Stock</td>
                                                        <td> <?php echo number_format($clcost,2);?> </td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <?php
                                                    $totex=$totex+$opcost+$clcost;
                                                    $gt=$gt+$opcost+$clcost;
                                                    }
                                                    
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
        $gl1inc= $row1inc["glno"]; $glnm1inc= $row1inc["glnm"]; $cl1inc=str_replace(' ','_',$glnm1inc);$clinc=str_replace('&','_',$cl1inc);//$totex=0;
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
                $gl2inc= $row2inc["glno"]; $glnm2inc= $row2inc["glnm"];$bal= $row2inc["closingbal"];
                 ?>
                <tr>
                  <td class="gp-1"><?php echo $glnm2inc;?></td>
                  <td> &nbsp; </td>
                    <td>&nbsp;</td>
                </tr>
                <?php
                $gl2amti=0;
                $amtqryi="SELECT     gl.glno,    gl.glnm,    gl.opbal,
    tr.debit AS debit,
    tr.credit AS credit
FROM (   
    SELECT  glno, glnm,  opbal FROM coa_mon WHERE yr ='$fyr'   AND mn =7 AND isposted = 'P'  AND oflag = 'N' 
      AND ( ctlgl IN ( SELECT glno FROM coa_mon WHERE yr = '$fyr' AND mn =7  AND ctlgl = '$gl2inc'  )
          OR ctlgl = '$gl2inc'
          OR glno = '$gl2inc'
      	)
	) gl,
    (
    	select a.glac,sum(da) debit,sum(ca) credit from 
        (
        select d.glac,d.dr_cr ,(case WHEN d.dr_cr='D' then sum(d.amount) else 0 end) da,(case WHEN d.dr_cr='C' then sum(d.amount) else 0 end) ca
        from glmst m, gldlt d where m.vouchno=d.vouchno and m.isfinancial in('0','A') and m.transdt between '$fd' AND '$td'
        and substr(d.glac,1,1)=3 group by d.glac,d.dr_cr
        )a group by a.glac
    )tr  where gl.glno=tr.glac";
                
                /*"SELECT     gl.glno,    gl.glnm,    gl.opbal,
    COALESCE( ( SELECT SUM(d.amount)  FROM gldlt d JOIN glmst m ON d.vouchno = m.vouchno  WHERE d.glac = gl.glno  AND d.dr_cr = 'D'  AND m.transdt BETWEEN '$fd' AND '$td'  AND m.isfinancial IN ('0', 'A')), 0) AS debit,
     COALESCE( ( SELECT SUM(d.amount)  FROM gldlt d JOIN glmst m ON d.vouchno = m.vouchno  WHERE d.glac = gl.glno  AND d.dr_cr = 'C'  AND m.transdt BETWEEN '$fd' AND '$td'  AND m.isfinancial IN ('0', 'A')), 0)  AS credit
FROM (   
    SELECT  glno, glnm,  opbal FROM coa_mon WHERE yr ='$fyr'   AND mn =$fmn AND isposted = 'P'  AND oflag = 'N' 
      AND ( ctlgl IN ( SELECT glno FROM coa_mon WHERE yr = '$fyr' AND mn =$fmn  AND ctlgl = '$gl2inc'  AND isposted = 'P' AND oflag = 'N' )
          OR ctlgl = '$gl2inc'
          OR glno = '$gl2inc'
      	)
	) gl";
                
                "select glno,glnm
,COALESCE((select  sum(d.amount) from  gldlt d,glmst m where d.vouchno=m.vouchno and gl.glno=d.glac and d.dr_cr='D' and (m.transdt between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y')) and m.isfinancial in('0','A')
 ),0) debit
 ,COALESCE((select  sum(d.amount) from  gldlt d,glmst m where d.vouchno=m.vouchno and gl.glno=d.glac and d.dr_cr='C' and (m.transdt between STR_TO_DATE('$fd', '%d/%m/%Y') and STR_TO_DATE('$td', '%d/%m/%Y')) and m.isfinancial in('0','A')
 ),0) credit
from
(
select glno,glnm from coa where ctlgl='$gl2inc' and isposted='P' and oflag='N'
union all 
SELECT glno,glnm from coa where ctlgl in(select glno from coa where ctlgl='$gl2inc' and isposted='P') and isposted='P' and oflag='N'
union all
select glno,glnm from coa where ctlgl in (
	SELECT glno from coa where ctlgl in(select glno from coa where ctlgl='$gl2inc' and isposted='P') and isposted='P' and oflag='N'   ) and isposted='P'
 ) gl ";*/
                    //echo  $amtqry;die;          
                $resultamti = $conn->query($amtqryi);   
                while ($rowamti = $resultamti->fetch_assoc())
                {
                    //$incamt=$rowamti["amt"];
                    $glno3i=$rowamti["glno"];$glnm3i=$rowamti["glnm"];
                    $incamt=$rowamti["credit"]-$rowamti["debit"]; 
                    $gl2amti=$gl2amti+$incamt;
                    ?>
                        <tr>
                            <td class="gp-2"><?php echo $glnm3i;?></td>
                            <td> <?php echo number_format($incamt,2);?> </td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php 
                    
                }
                $totinc=$totinc+$gl2amti;$gtinc=$gtinc+$gl2amti;
                ?>
                                                            <tr>
                                                              <td class="gp-1">Total <?php echo $glnm2inc;?></td>
                                                              <td>&nbsp;</td>
                                                              <td> <?php echo number_format($gl2amti,2);?> </td>
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