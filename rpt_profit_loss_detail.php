<?php
require "common/conn.php";

require "rak_framework/misfuncs.php";
require "common/user_btn_access.php";

session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
$fd1 = $_POST['from_dt'];
$td1 = $_POST['to_dt'];
 if($fd1==''){$fd1=date("d/m/Y");}
    if($td1==''){$td1=date("d/m/Y");}

$date1 = DateTime::createFromFormat('d/m/Y', $fd1);
$fd = $date1->format('Y-m-d');
$date2 = DateTime::createFromFormat('d/m/Y', $td1);
$td = $date2->format('Y-m-d');
$fyr= $date1->format('Y');
$tyr= $date2->format('Y');
$fmn=$date1->format('n');
$tmn=$date2->format('n');
   
if($usr=='')
{ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
   require_once "common/PHPExcel.php";
   $currSection = 'rpt_profit_loss_detail';
    $currPage    = basename($_SERVER['PHP_SELF']);
    
    if (isset($_POST['export'])) 
    {
       
     // echo $fyr.'-'.$tyr.'-'.$fmn;die;  
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', 'Expense')
            ->setCellValue('E2', 'Income')
            ->setCellValue('C3', 'Detail')
            ->setCellValue('D3', 'Total')
            ->setCellValue('F3', 'Detail')
            ->setCellValue('G3', 'Total');
        $firststyle = 'B2';
        $cogs="SELECT a.`glnm`,a.`opbal` opcst,b.`opbal` clcst FROM coa_mon a,coa_mon b where a.glno=b.glno and a.glno='102010100' and a.yr='$fyr' and a.mn=$fmn and b.yr='$tyr' and b.mn=$tmn+1";
        $rescogs = $conn->query($cogs);
        if ($rescogs->num_rows > 0) 
        {
            while ($rowcogs = $rescogs->fetch_assoc())
            {
                $opcost= $rowcogs["opcst"]; $clcost= $rowcogs["clcst"]*(-1);
            }
        }
        ///expense
        $gt=0;
        $lvl1="SELECT c.glno,c.glnm FROM `coa` c  where substring(c.glno,1,1) in('4') and c.lvl=2 and c.oflag in('N')";
        $result1 = $conn->query($lvl1);
        if ($result1->num_rows > 0) 
        { $i=2;
            while ($row1 = $result1->fetch_assoc())
            {
                $i++;
            $gl1= $row1["glno"]; $glnm1= $row1["glnm"]; $cl1=str_replace(' ','_',$glnm1);$cl=str_replace('&','_',$cl1);$totex=0;
        // echo  $qry;die;
                $urut  = $i + 2;
                $col1  = 'B' . $urut;
               
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $glnm1);
                   
             if( $gl1=='401000000')
                 { $i++; $urut  = $i + 2; 
                      $col1  = 'B' . $urut; $col2  = 'C' . $urut;
                     $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, '  Opening Stock')
                    ->setCellValue($col2, $opcost);
                 }
                 $net=0;
                 $lvl2="SELECT c.glno,c.glnm ,c.closingbal FROM `coa` c  where c.ctlgl='$gl1' and c.oflag in('N') "; 
                 $result2 = $conn->query($lvl2);
                if ($result2->num_rows > 0) 
                {
                    while ($row2 = $result2->fetch_assoc())
                    { $i++; $urut  = $i + 2; $col1  = 'B' . $urut;
                        $gl2= $row2["glno"]; $glnm2= $row2["glnm"];$bal= $row2["closingbal"];
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($col1, '  '.$glnm2);
                        
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
                        
                        /*"select gl.glno,gl.glnm,gl.opbal
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
                          //echo  $amtqry; die;// if($gl2=='402150000'){echo  $amtqry;}       
                        $resultamt = $conn->query($amtqry);   
                        while ($rowamt = $resultamt->fetch_assoc())
                        {   $i++; $urut  = $i + 2; $col1  = 'B' . $urut;$col2  = 'C' . $urut;
                            $glno3=$rowamt["glno"];$glnm3=$rowamt["glnm"];
                            $examt=$rowamt["debit"]-$rowamt["credit"];
                            $gl2amt=$gl2amt+$examt;   
                             $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($col1, '    '.$glnm3)
                            ->setCellValue($col2, $examt); 
                        }
                        $totex=$totex+$gl2amt;$gt=$gt+$gl2amt;
                        $i++; $urut  = $i + 2; $col1  = 'B' . $urut;$col2  = 'C' . $urut;$col3  = 'D' . $urut;
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($col1, '   Total '.$glnm2)
                            ->setCellValue($col3, $gl2amt);
                    }
                    if( $gl1=='401000000')
                    {   $i++; $urut  = $i + 2;$col1  = 'B' . $urut;$col2  = 'C' . $urut;
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($col1, '  Closing Stock')
                            ->setCellValue($col2, $clcost);
                        $totex=$totex+$opcost+$clcost;
                    }
                    $i++; $urut  = $i + 2;$col1  = 'B' . $urut;$col2  = 'D' . $urut;
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($col1, 'Total '.$glnm1)
                            ->setCellValue($col2, $totex);
                }
            }
                                                    
        }
        $j=$i;
        //income
        $gtinc=0;
        $lvl1="SELECT c.glno,c.glnm FROM `coa` c  where substring(c.glno,1,1) in('3') and c.lvl=2 and c.oflag in('N')";
        $result1 = $conn->query($lvl1);
        if ($result1->num_rows > 0) 
        { $i=2;
            while ($row1 = $result1->fetch_assoc())
            {
                $i++;
            $gl1= $row1["glno"]; $glnm1= $row1["glnm"]; $cl1=str_replace(' ','_',$glnm1);$cl=str_replace('&','_',$cl1);$totinc=0;
        // echo  $qry;die;
                $urut  = $i + 2;
                $col1  = 'E' . $urut;
               
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $glnm1);
                   
             /*if( $gl1=='401000000')
                 { $i++; $urut  = $i + 2; 
                      $col1  = 'B' . $urut; $col2  = 'C' . $urut;
                     $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, 'Opening Stock')
                    ->setCellValue($col2, $opcost);
                 }*/
                 $net=0;
                 $lvl2="SELECT c.glno,c.glnm ,c.closingbal FROM `coa` c  where c.ctlgl='$gl1' and c.oflag in('N') "; 
                 $result2 = $conn->query($lvl2);
                if ($result2->num_rows > 0) 
                {
                    while ($row2 = $result2->fetch_assoc())
                    { $i++; $urut  = $i + 2; $col1  = 'E' . $urut;
                        $gl2= $row2["glno"]; $glnm2= $row2["glnm"];$bal= $row2["closingbal"];
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($col1,'  '. $glnm2);
                        
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
        and substr(d.glac,1,1)=3 group by d.glac,d.dr_cr
        )a group by a.glac
    )tr  where gl.glno=tr.glac"; 
                          //echo  $amtqry; die;// if($gl2=='402150000'){echo  $amtqry;}       
                        $resultamt = $conn->query($amtqry);   
                        while ($rowamt = $resultamt->fetch_assoc())
                        {   $i++; $urut  = $i + 2; $col1  = 'E' . $urut;$col2  = 'F' . $urut;
                            $glno3=$rowamt["glno"];$glnm3=$rowamt["glnm"];
                            $examt= $rowamt["credit"]-$rowamt["debit"];
                            $gl2amt=$gl2amt+$examt;   
                             $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($col1, '    '.$glnm3)
                            ->setCellValue($col2, $examt); 
                        }
                        $totinc=$totinc+$gl2amt;$gtinc=$gtinc+$gl2amt;
                        $i++; $urut  = $i + 2; $col1  = 'E' . $urut;$col2  = 'F' . $urut;$col3  = 'G' . $urut;
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($col1, '  Total '.$glnm2)
                            ->setCellValue($col3, $gl2amt);
                    }
                  /*  if( $gl1=='401000000')
                    {   $i++; $urut  = $i + 2;$col1  = 'B' . $urut;$col2  = 'C' . $urut;
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($col1, 'Closing Stock')
                            ->setCellValue($col2, $clcost);
                        $totex=$totex+$opcost+$clcost;
                    } */
                    $i++; $urut  = $i + 2;$col1  = 'E' . $urut;$col2  = 'G' . $urut;
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($col1, 'Total '.$glnm1)
                            ->setCellValue($col2, $totinc);
                }
            }
                                                    
        }
        
        $profit=$gtinc-$gt; if($profit>0){$gt=$gt+$profit;$porf=$profit;$loss=0;} else {$gtinc=$gtinc-$profit;$porf=0;$loss=0-$profit;}
        
        $urut  = $j + 4;
        $col1  = 'B' . $urut;
        $col2  = 'D' . $urut; 
        $col3  = 'E' . $urut;
        $col4  = 'G' . $urut;    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($col1, 'Net Profit')
             ->setCellValue($col2, $porf)
             ->setCellValue($col3, 'Net Loss')
              ->setCellValue($col4, $loss);
              
        $urut  = $j + 6;
        $col1  = 'B' . $urut;
        $col2  = 'D' . $urut; 
        $col3  = 'E' . $urut;
        $col4  = 'G' . $urut;    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($col1, 'Expense Total')
             ->setCellValue($col2, $gt)
             ->setCellValue($col3, 'Income Total')
              ->setCellValue($col4, $gtinc);      
        
         
        $objPHPExcel->getActiveSheet()->setTitle('Profit Loss Statement');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'profit_loss' . $today . '.xls';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileNm);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileNm));
        ob_clean();
        flush();
        readfile($fileNm);
        exit;
    
        
    }
}
 

?>
<?php
     include_once('common_header.php');
?>
<body class="dashboard">
<?php
    include_once('common_top_body.php');
?>
    <div id="wrapper"> 
        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="mCustomScrollbar">
            <div class="section">
          	    <i class="fa fa-group  icon"></i>
                <span>ACCOUNTING</span>
            </div>
<?php
    include_once('menu.php');
?>
	        <div style="height:54px;">
            </div>
        </div>
    <!-- /#sidebar-wrapper --> 
  <style>
      .button-bar {
      
      margin-top: 0px;
    }
  </style>
    <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid xyz">
                <div class="row">
                    <div class="col-lg-12">
                            <div class="panel panel-info">
                                <div class="panel-body">
                                    <span class="alertmsg">
                                    </span>
                                    <p></p>
                                    <p>&nbsp;</p>
                                    <!--h1 class="page-title">Customers</a></h1-->
                                    <p>
                                    <!-- START PLACING YOUR CONTENT HERE -->
                                    <form method="post" action="rpt_profit_loss_detail.php?pg=1&mod=7" id="form1" enctype="multipart/form-data">  
                                        <!-- START PLACING YOUR CONTENT HERE -->
                                          
                                        <p>&nbsp;</p>
                                        
                                        <div class="button-bar"> 
                                            
                                            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                                <div class="input-group">
                                                    <label for="po_dt">Order Date*</label>
                                                </div>     
                                            </div> -->
                                            <div class="col-lg-1 col-md-6 col-sm-6">
                                                <div class="input-group">
                                                    <div >Date From  </div>
                                                </div>     
                                            </div> 
                                            <div class="col-lg-2 col-md-6 col-sm-6">
                                                
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" name="from_dt" id="from_dt" value="<?php echo $fd1;?>"  required> 
                                                </div>     
                                            </div>
                                            <div class="col-lg-1 col-md-6 col-sm-6">
                                                <div class="input-group">
                                                    <div >Date To</div> 
                                                </div>     
                                            </div> 
                                            <div class="col-lg-2 col-md-6 col-sm-6">
                                                
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" id="to_dt" name="to_dt"  value="<?php echo $td1;?>" required> 
                                                    
                                                </div>     
                                            </div>
                                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  >
                                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="export" value="Export" id="export">
                                            <input class="btn btn-lg btn-default print-view" type="button" name="cancel" value="Print">
                                        </div>
                                           
                                                <!-- /#end of panel -->
                                    </form>
                                    
                                     <?php 
                                            include_once('phpajax/rpt_load_profit_loss_detail.php');
                                            ?> 
                             	 </div>
                            </div>
                        <!-- END PLACING YOUR CONTENT HERE -->          
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /#page-content-wrapper -->


<script src="js/plugins/html2pdf/html2pdf.bundle.min.js"></script>
<?php

	include_once('common_footer.php');
?>

<script>
    
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}


</script>    
<script>

$(".tbl-bhbs tr").mouseover(function(){
    var thisClass = $(this).attr("class");
    $("."+thisClass).css("background-color","#E6F0FF");
 	 	//$("."+thisClass).css("font-weight","bold");
  
});

$(".tbl-bhbs tr").mouseleave(function(){
    var thisClass = $(this).attr("class");
    $("."+thisClass).css("background","transparent");
 		// $("."+thisClass).css("font-weight","normal");
});
	
	
</script>

</body>
</html>
