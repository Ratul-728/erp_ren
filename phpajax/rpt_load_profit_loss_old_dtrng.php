<?php
   /* $lvl1="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.glno='100000000' and c.yr='$pyr' and c.mn='$pmn'";
    $result1 = $conn->query($lvl1);
    if ($result1->num_rows > 0)
    {
        $row1 = $result1->fetch_assoc();
        $closingbal1= $row1["closingbal"];
   */
?>
 <div class="panel panel-info" id="printableArea">
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
        
    </style>
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="bhbs-wrapper">
        		<div class="bhbs-header">
                	<h2>Renaissance Decor</h2>
        			<h1>Profit & Loss A/C tansaction</h1>
        		</div>       
            	<div class="tbl-bhbs-wrapper">
                    <table width="100%" class="tbl-parent"  border="0" cellspacing="0" cellpadding="0">
                        <tr class="tr-parent">
                            <!--first column title -->
                            <td class="tbl-header" width="50%">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td  class="total-title">Expense</td>
                                        <td class="total-amount" nowrap><span class="top-border">From <?php echo $fd;?> To <?php echo $td;?></span></td>
                                        <td  class="total-title"></td>
                                    </tr>
                                </table>
                            </td>
                            <!--second  column title -->
                            <td class="tbl-header" width="50%">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td  class="total-title">Income</td>
                                        <td class="total-amount" nowrap><span class="top-border">From <?php echo $fd;?> To <?php echo $td;?></span></td>
                                        <td  class="total-title"></td>
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
                                          <td><strong>Debit</strong></td>
                                          <td><strong>Credit</strong></td>
                                        </tr>
<?php 
$totdr1=0;$totcr1=0;$gt=0;
$lvl1="SELECT c.glno,c.glnm FROM `coa` c  where substring(c.glno,1,1) in('4') and c.lvl=1";
$result1 = $conn->query($lvl1);
if ($result1->num_rows > 0) 
{
    while ($row1 = $result1->fetch_assoc())
    {
        $gl1= $row1["glno"]; $glnm1= $row1["glnm"]; $cl1=str_replace(' ','_',$glnm1);$cl=str_replace('&','_',$cl1);
    ?>
                                            <tr class="<?php echo $cl;?>">
                                              <td class="gp-0"><strong><?php echo $glnm1;?> </strong></td>
                                              <td><strong><?php echo $gl1;?></strong></td>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                            </tr>
    <?php 
    //  $net=0;
    //$lvl2="SELECT c.glno,c.glnm ,c.closingbal FROM `coa` c  where c.ctlgl=$gl1 "; 
    
        $totdr2=0;$totcr2=0;
        $lvl2="SELECT c.glno,c.glnm,c.isposted FROM `coa` c  where  c.ctlgl='$gl1'";
        $result2 = $conn->query($lvl2);
        if ($result2->num_rows > 0) 
        {
            while ($row2 = $result2->fetch_assoc())
            {
                $gl2= $row2["glno"]; $glnm2= $row2["glnm"]; $isposted2= $row2["isposted"]; $cl21=str_replace(' ','_',$glnm2);$cl2=str_replace('&','_',$cl21);
            ?>
                                                    <tr class="<?php echo $cl2;?>">
                                                      <td class="gp-0">&nbsp;&nbsp;<strong><?php echo $glnm2;?> </strong></td>
                                                      <td><strong><?php echo $gl2;?> </strong></td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                    </tr>
<?php 
    //  $net=0;
    //$lvl2="SELECT c.glno,c.glnm ,c.closingbal FROM `coa` c  where c.ctlgl=$gl1 "; 
          
                    $totdr3=0;$totcr3=0;
                    $lvl3="SELECT c.glno,c.glnm,c.isposted FROM `coa` c  where  c.ctlgl='$gl2'";
                    $result3 = $conn->query($lvl3);
                    if ($result3->num_rows > 0) 
                    {
                        while ($row3 = $result3->fetch_assoc())
                        {
                            $gl3= $row3["glno"]; $glnm3= $row3["glnm"]; $isposted3= $row3["isposted"]; $cl31=str_replace(' ','_',$glnm3);$cl3=str_replace('&','_',$cl31);
?>
                                                        
<?php                       if($isposted3=="P")
                            {
                                $dta="SELECT c.glno,c.glnm
                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='D' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' )) dbt
                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='C' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' ))  crd
                                FROM `coa` c  where c.glno=$gl3 ";
                                
                                $resdta = $conn->query($dta);
                                if ($resdta->num_rows > 0) 
                                {
                                    while ($rowdta = $resdta->fetch_assoc())
                                        {
                                            $bal_dr=0;$bal_cr=0;
                                            $gl3= $rowdta["glno"]; $glnm3= $rowdta["glnm"];$bal_dr= $rowdta["dbt"];$bal_cr= $rowdta["crd"];$totdr2=$totdr2+$bal_dr;$totcr2=$totcr2+$bal_cr;$totdr1=$totdr1+$bal_dr;$totcr1=$totcr1+$bal_cr;
                                            if(($bal_dr+$bal_cr)<>0  ){
?>
                                                            <tr>
                                                                <td class="gp-1">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $glnm3;?></td>
                                                                <td class="gp-1"><?php echo $gl3;?></td>
                                                                <td> <?php echo number_format($bal_dr,2);?> </td>
                                                                <td> <?php echo number_format($bal_cr,2);?> </td>
                                                                <td>&nbsp;</td>
                                                            </tr>
<?php                                                               }
                                                
                                        }
                                }
                            }
                            else
                            {
                                                        ?><tr class="<?php echo $cl3;?>">
                                                          <td class="gp-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $glnm3;?> </strong></td>
                                                          <td><strong><?php echo $gl3;?> </strong></td>
                                                          <td>&nbsp;</td>
                                                          <td>&nbsp;</td>
                                                          <td>&nbsp;</td>
                                                        </tr> 
                                                        <?php
                                $totdr4=0;$totcr4=0;
                                $lvl4="SELECT c.glno,c.glnm,c.isposted FROM `coa` c  where  c.ctlgl='$gl3'";
                                $result4 = $conn->query($lvl4);
                                if ($result4->num_rows > 0) 
                                {
                                    while ($row4 = $result4->fetch_assoc())
                                    {
                                        $gl4= $row4["glno"]; $glnm4= $row4["glnm"]; $isposted4= $row4["isposted"]; $cl41=str_replace(')','_',str_replace('(','_',str_replace(',','_',str_replace(' ','_',$glnm4))));$cl4=str_replace('&','_',$cl41);
                                                
                                            if($isposted4=="P")
                                            {
                                                $dta="SELECT c.glno,c.glnm
                                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='D' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' )) dbt
                                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='C' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' ))  crd
                                                FROM `coa` c  where c.glno=$gl4 ";
                                                
                                                $resdta = $conn->query($dta);
                                                if ($resdta->num_rows > 0) 
                                                {
                                                    while ($rowdta = $resdta->fetch_assoc())
                                                        {
                                                            $bal_dr=0;$bal_cr=0;
                                                            $gl4= $rowdta["glno"]; $glnm4= $rowdta["glnm"];$bal_dr= $rowdta["dbt"];$bal_cr= $rowdta["crd"];$totdr3=$totdr3+$bal_dr;$totcr3=$totcr3+$bal_cr;$totdr2=$totdr2+$bal_dr;$totcr2=$totcr2+$bal_cr;$totdr1=$totdr1+$bal_dr;$totcr1=$totcr1+$bal_cr;
                                                        if(($bal_dr+$bal_cr)<>0  ){
?>
                                                        <tr>
                                                            <td class="gp-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $glnm4;?></td>
                                                            <td class="gp-1"><?php echo $gl4;?></td>
                                                            <td> <?php echo number_format($bal_dr,2);?> </td>
                                                            <td> <?php echo number_format($bal_cr,2);?> </td>
                                                            <td>&nbsp;</td>
                                                        </tr>
<?php                                                                           }
                                                        }
                                                }
                                            }
                                            else
                                            {
                                                        ?><tr class="<?php echo $cl4;?>">
                                                            <td class="gp-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $glnm4;?> </strong></td>
                                                            <td><strong><?php echo $gl4;?> </strong></td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>  <?php
                                                
                                                $dta="SELECT c.glno,c.glnm
                                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='D' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' )) dbt
                                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='C' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' ))  crd
                                                FROM `coa` c  where c.ctlgl=$gl4 ";
                                                
                                                $resdta = $conn->query($dta);
                                                if ($resdta->num_rows > 0) 
                                                {
                                                    while ($rowdta = $resdta->fetch_assoc())
                                                        {
                                                            $bal_dr=0;$bal_cr=0;
                                                            $gl5= $rowdta["glno"]; $glnm5= $rowdta["glnm"];$bal_dr= $rowdta["dbt"];$bal_cr= $rowdta["crd"];$totdr4=$totdr4+$bal_dr;$totcr4=$totcr4+$bal_cr;$totdr3=$totdr3+$bal_dr;$totcr3=$totcr3+$bal_cr;$totdr2=$totdr2+$bal_dr;$totcr2=$totcr2+$bal_cr;$totdr1=$totdr1+$bal_dr;$totcr1=$totcr1+$bal_cr;
                                                        if(($bal_dr+$bal_cr)<>0  ){
?>
                                                        <tr>
                                                            <td class="gp-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $glnm5;?></td>
                                                            <td class="gp-1"><?php echo $gl5;?></td>
                                                            <td> <?php echo number_format($bal_dr,2);?> </td>
                                                            <td> <?php echo number_format($bal_cr,2);?> </td>
                                                            <td>&nbsp;</td>
                                                        </tr>
<?php                                                                           }
                                                        }
                                                }
                                                        ?><tr class="<?php echo $cl4;?>">
                                                            <td class="gp-0  total-title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total <?php echo $glnm4;?></td>
                                                            <td>&nbsp;</td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totdr4,2);?> </td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totcr4,2);?> </td>
                                                            <td class="bordertop total-amount">&nbsp; </td>
                                                        </tr> <?php
                                            }
                                              
                                    }              
                                }
?>                                                          
                                                        <tr class="<?php echo $cl3;?>">
                                                            <td class="gp-0  total-title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total <?php echo $glnm3;?></td>
                                                            <td>&nbsp;</td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totdr3,2);?> </td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totcr3,2);?> </td>
                                                            <td class="bordertop total-amount">&nbsp; </td>
                                                        </tr>                           
                                
<?php                                   
                            }
                        }
                    } 
?>
                                                        <tr class="<?php echo $cl2;?>">
                                                            <td class="gp-0  total-title">&nbsp;&nbsp;Total <?php echo $glnm2;?></td> 
                                                            <td>&nbsp;</td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totdr2,2);?> </td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totdr2,2);?> </td>
                                                            <td class="bordertop total-amount">&nbsp; </td>
                                                        </tr>                                                 
                   
 <?php               
            }
        }
 ?>   
                                                <tr class="<?php echo $cl;?>">
                                                    <td class="gp-0  total-title">Total <?php echo $glnm1;?></td>
                                                    <td>&nbsp;</td>
                                                    <td class="bordertop total-amount"> <?php echo number_format($totdr1,2);?> </td>
                                                    <td class="bordertop total-amount"> <?php echo number_format($totcr1,2);?> </td>
                                                    <td class="bordertop total-amount">&nbsp; </td>
                                                </tr>                                                        
<?php 
    }
?>  
                                                            
<?php   
}

$gt=$totdr1-$totcr1;
?>                                                                
                                    <!--tr class="<?php echo $cl1;?>">
                                      <td class="gp-0  total-title">Total <?php echo $glnm1;?></td>
                                      <td> <?php echo number_format($totdr1,2);?> </td>
                                      <td> <?php echo number_format($totcr1,2);?> </td>
                                      <td class="bordertop total-amount">&nbsp; </td>
                                    </tr-->  
                                    </tbody>
                                </table>   
                            </td>
                                
                            <!--second table upd-->
                            <td valign="top">
                                <table class="tbl-bhbs" width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tbody> 
                                        <tr>
                                          <td class="gp-1">&nbsp;</td>
                                          <td><strong>ACCOUNT</strong></td>
                                          <td><strong>Debit</strong></td>
                                          <td><strong>Credit</strong></td>
                                        </tr>
<?php 
$totdr1=0;$totcr1=0;$gt1=0;
$lvl1="SELECT c.glno,c.glnm FROM `coa` c  where substring(c.glno,1,1) in('3') and c.lvl=1";
$result1 = $conn->query($lvl1);
if ($result1->num_rows > 0) 
{
    while ($row1 = $result1->fetch_assoc())
    {
        $gl1= $row1["glno"]; $glnm1= $row1["glnm"]; $cl1=str_replace(' ','_',$glnm1);$cl=str_replace('&','_',$cl1);
    ?>
                                            <tr class="<?php echo $cl;?>">
                                              <td class="gp-0"><strong><?php echo $glnm1;?> </strong></td>
                                              <td><strong><?php echo $gl1;?></strong></td>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                            </tr>
    <?php 
    //  $net=0;
    //$lvl2="SELECT c.glno,c.glnm ,c.closingbal FROM `coa` c  where c.ctlgl=$gl1 "; 
    
        $totdr2=0;$totcr2=0;
        $lvl2="SELECT c.glno,c.glnm,c.isposted FROM `coa` c  where  c.ctlgl='$gl1'";
        $result2 = $conn->query($lvl2);
        if ($result2->num_rows > 0) 
        {
            while ($row2 = $result2->fetch_assoc())
            {
                $gl2= $row2["glno"]; $glnm2= $row2["glnm"]; $isposted2= $row2["isposted"]; $cl21=str_replace(' ','_',$glnm2);$cl2=str_replace('&','_',$cl21);
            ?>
                                                    <tr class="<?php echo $cl2;?>">
                                                      <td class="gp-0">&nbsp;&nbsp;<strong><?php echo $glnm2;?> </strong></td>
                                                      <td><strong><?php echo $gl2;?> </strong></td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                    </tr>
<?php 
    //  $net=0;
    //$lvl2="SELECT c.glno,c.glnm ,c.closingbal FROM `coa` c  where c.ctlgl=$gl1 "; 
          
                    $totdr3=0;$totcr3=0;
                    $lvl3="SELECT c.glno,c.glnm,c.isposted FROM `coa` c  where  c.ctlgl='$gl2'";
                    $result3 = $conn->query($lvl3);
                    if ($result3->num_rows > 0) 
                    {
                        while ($row3 = $result3->fetch_assoc())
                        {
                            $gl3= $row3["glno"]; $glnm3= $row3["glnm"]; $isposted3= $row3["isposted"]; $cl31=str_replace(' ','_',$glnm3);$cl3=str_replace('&','_',$cl31);
?>
                                                        
<?php                       if($isposted3=="P")
                            {
                                $dta="SELECT c.glno,c.glnm
                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='D' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' )) dbt
                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='C' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' ))  crd
                                FROM `coa` c  where c.glno=$gl3 ";
                                
                                $resdta = $conn->query($dta);
                                if ($resdta->num_rows > 0) 
                                {
                                    while ($rowdta = $resdta->fetch_assoc())
                                        {
                                            $bal_dr=0;$bal_cr=0;
                                            $gl3= $rowdta["glno"]; $glnm3= $rowdta["glnm"];$bal_dr= $rowdta["dbt"];$bal_cr= $rowdta["crd"];$totdr2=$totdr2+$bal_dr;$totcr2=$totcr2+$bal_cr;$totdr1=$totdr1+$bal_dr;$totcr1=$totcr1+$bal_cr;
                                            if(($bal_dr+$bal_cr)<>0  ){
?>
                                                            
                                                            <tr>
                                                                <td class="gp-1">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $glnm3;?></td>
                                                                <td class="gp-1"><?php echo $gl3;?></td>
                                                                <td> <?php echo number_format($bal_dr,2);?> </td>
                                                                <td> <?php echo number_format($bal_cr,2);?> </td>
                                                                <td>&nbsp;</td>
                                                            </tr>
<?php
                                                                    }
                                            }
                                }
                            }
                            else
                            {
                                                        ?><tr class="<?php echo $cl3;?>">
                                                          <td class="gp-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $glnm3;?> </strong></td>
                                                          <td><strong><?php echo $gl3;?> </strong></td>
                                                          <td>&nbsp;</td>
                                                          <td>&nbsp;</td>
                                                          <td>&nbsp;</td>
                                                        </tr> 
                                                        <?php
                                $totdr4=0;$totcr4=0;
                                $lvl4="SELECT c.glno,c.glnm,c.isposted FROM `coa` c  where  c.ctlgl='$gl3'";
                                $result4 = $conn->query($lvl4);
                                if ($result4->num_rows > 0) 
                                {
                                    while ($row4 = $result4->fetch_assoc())
                                    {
                                        $gl4= $row4["glno"]; $glnm4= $row4["glnm"]; $isposted4= $row4["isposted"]; $cl41=str_replace(')','_',str_replace('(','_',str_replace(',','_',str_replace(' ','_',$glnm4))));$cl4=str_replace('&','_',$cl41);
                                                
                                            if($isposted4=="P")
                                            {
                                                $dta="SELECT c.glno,c.glnm
                                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='D' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' )) dbt
                                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='C' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' ))  crd
                                                FROM `coa` c  where c.glno=$gl4 ";
                                                
                                                $resdta = $conn->query($dta);
                                                if ($resdta->num_rows > 0) 
                                                {
                                                    while ($rowdta = $resdta->fetch_assoc())
                                                        {
                                                            $bal_dr=0;$bal_cr=0;
                                                            $gl4= $rowdta["glno"]; $glnm4= $rowdta["glnm"];$bal_dr= $rowdta["dbt"];$bal_cr= $rowdta["crd"];$totdr3=$totdr3+$bal_dr;$totcr3=$totcr3+$bal_cr;$totdr2=$totdr2+$bal_dr;$totcr2=$totcr2+$bal_cr;$totdr1=$totdr1+$bal_dr;$totcr1=$totcr1+$bal_cr;
                                                        if(($bal_dr+$bal_cr)<>0  ){
?>
                                                        <tr>
                                                            <td class="gp-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $glnm4;?></td>
                                                            <td class="gp-1"><?php echo $gl4;?></td>
                                                            <td> <?php echo number_format($bal_dr,2);?> </td>
                                                            <td> <?php echo number_format($bal_cr,2);?> </td>
                                                            <td>&nbsp;</td>
                                                        </tr>
<?php                                                   
                                                                                }
                                                        }
                                                }
                                            }
                                            else
                                            {
                                                        ?><tr class="<?php echo $cl4;?>">
                                                            <td class="gp-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $glnm4;?> </strong></td>
                                                            <td><strong><?php echo $gl4;?> </strong></td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>  <?php
                                                
                                                $dta="SELECT c.glno,c.glnm
                                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='D' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' )) dbt
                                                ,(select ifnull(sum(ifnull(d.amount,0)),0) from gldlt d where  d.dr_cr ='C' and d.glac=c.glno and (d.entrydate between '2023-09-01' and '2023-09-31' ))  crd
                                                FROM `coa` c  where c.ctlgl=$gl4 ";
                                                
                                                $resdta = $conn->query($dta);
                                                if ($resdta->num_rows > 0) 
                                                {
                                                    while ($rowdta = $resdta->fetch_assoc())
                                                        {
                                                            $bal_dr=0;$bal_cr=0;
                                                            $gl5= $rowdta["glno"]; $glnm5= $rowdta["glnm"];$bal_dr= $rowdta["dbt"];$bal_cr= $rowdta["crd"];$totdr4=$totdr4+$bal_dr;$totcr4=$totcr4+$bal_cr;$totdr3=$totdr3+$bal_dr;$totcr3=$totcr3+$bal_cr;$totdr2=$totdr2+$bal_dr;$totcr2=$totcr2+$bal_cr;$totdr1=$totdr1+$bal_dr;$totcr1=$totcr1+$bal_cr;
                                                        if(($bal_dr+$bal_cr)<>0  ){
?>
                                                        <tr>
                                                            <td class="gp-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $glnm5;?></td>
                                                            <td class="gp-1"><?php echo $gl5;?></td>
                                                            <td> <?php echo number_format($bal_dr,2);?> </td>
                                                            <td> <?php echo number_format($bal_cr,2);?> </td>
                                                            <td>&nbsp;</td>
                                                        </tr>
<?php                                                                           }
                                                        }
                                                }
                                                        ?><tr class="<?php echo $cl4;?>">
                                                            <td class="gp-0  total-title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total <?php echo $glnm4;?></td>
                                                            <td>&nbsp;</td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totdr4,2);?> </td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totcr4,2);?> </td>
                                                            <td class="bordertop total-amount">&nbsp; </td>
                                                        </tr> <?php
                                            }
                                              
                                    }              
                                }
?>                                                          
                                                        <tr class="<?php echo $cl3;?>">
                                                            <td class="gp-0  total-title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total <?php echo $glnm3;?></td>
                                                            <td>&nbsp;</td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totdr3,2);?> </td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totcr3,2);?> </td>
                                                            <td class="bordertop total-amount">&nbsp; </td>
                                                        </tr>                           
                                
<?php                                   
                            }
                        }
                    } 
?>
                                                        <tr class="<?php echo $cl2;?>">
                                                            <td class="gp-0  total-title">&nbsp;&nbsp;Total <?php echo $glnm2;?></td> 
                                                            <td>&nbsp;</td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totdr2,2);?> </td>
                                                            <td class="bordertop total-amount"> <?php echo number_format($totdr2,2);?> </td>
                                                            <td class="bordertop total-amount">&nbsp; </td>
                                                        </tr>                                                 
                   
 <?php               
            }
        }
 ?>   
                                                <tr class="<?php echo $cl;?>">
                                                    <td class="gp-0  total-title">Total <?php echo $glnm1;?></td>
                                                    <td>&nbsp;</td>
                                                    <td class="bordertop total-amount"> <?php echo number_format($totdr1,2);?> </td>
                                                    <td class="bordertop total-amount"> <?php echo number_format($totcr1,2);?> </td>
                                                    <td class="bordertop total-amount">&nbsp; </td>
                                                </tr>                                                        
<?php 
    }
?>  
                                                            
<?php   
}

$gt1=$totdr1-$totcr1;
?>                                                                
                                    <!--tr class="<?php echo $cl1;?>">
                                      <td class="gp-0  total-title">Total <?php echo $glnm1;?></td>
                                      <td> <?php echo number_format($totdr1,2);?> </td>
                                      <td> <?php echo number_format($totcr1,2);?> </td>
                                      <td class="bordertop total-amount">&nbsp; </td>
                                    </tr-->  
                                    </tbody>
                                </table>   
                            </td>
                            
                        </tr>
                        <tr class="tr-parent">
                            <?php
                            if($gt1>$gt){$porf=$gt1-$gt;$loss=0;} else {$loss=$gt-$gt1;$porf=0;}
                            ?>
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
                                      <td class="total-amount"><span class="top-border"> <?php echo number_format(($gt+$porf),2);?> </span></td>
                                    </tr>
                                </table>
                            </td>
                            
                            <!--second total row-->
                            <td class="tbl-footer">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr class="end-parent assets">
                                      <td  class="total-title">TOTAL</td>
                                      <td class="total-amount"><span class="top-border"> <?php echo number_format(($gt1+$loss),2);?> </span></td>
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