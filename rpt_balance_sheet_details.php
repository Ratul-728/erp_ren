<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//$fd=$_POST['from_dt'];
//$td=$_POST['to_dt'];
//echo $usr;die;

$fdt = $_POST['from_dt'];
$fd = $_POST['from_dt'];
$td = $_POST['from_dt'];
    if($fd==''){$fd=date("d/m/Y");}
    if($td==''){$td=date("d/m/Y");}
    
$date1 = DateTime::createFromFormat('d/m/Y', $fd);
//$f_dt = $date1->format('Y-m-d');
$date2 = DateTime::createFromFormat('d/m/Y', $td);
$t_dt = $date2->format('Y-m-d');

$tyr= $date2->format('Y');
$pyr=$tyr-1;
$fmn=$date1->format('n');
//$tmn=$date2->format('n'); 
if($fmn>6){$f_dt = $date1->format('Y-07-01');}
else{$f_dt = "$pyr-07-01";}
    

    
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
   require_once "common/PHPExcel.php"; 
    $totamount=0;
    $i=0;
    //$f=date("d/m/Y");
    //$t=date("d/m/Y");
    //$pmn=$_POST['cmbmonth'];
    //$pyr=$_POST['cmbyr'];
   // if($pyr=='')
   // {
  //   $pyr=date("Y");
   // }
  //  if($pmn =='')
   // {
  //   $pmn=date("m");
  //  }
    //echo $pyr;die;
  if ( isset( $_POST['export'] ) ) 
  {
      // echo  $f_dt;   die;
       
       'As on '.$t_dt;
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B1', 'Bithut.com.bd.')
                ->setCellValue('B2', 'Balance Sheet')
                ->setCellValue('B3', ' As On ' .$t_dt)
                ->setCellValue('A4', '')
                ->setCellValue('A5', '100000000')
                ->setCellValue('B5', 'Asset')
                ->setCellValue('C5', ''); 
    			
        $firststyle='A5';
        //assets
        $Alvl1tot=0;$i=0;
		$Alvl2="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl='100000000' and c.yr='$pyr' and c.mn=7";
		$Aresult2 = $conn->query($Alvl2);
        if ($Aresult2->num_rows > 0) 
        {
            while ($Arow2 = $Aresult2->fetch_assoc())
            {
                $Agl2= $Arow2["glno"]; $Aglnm2= $Arow2["glnm"];$Aclosingbal2= $Arow2["closingbal"];
                $i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                 $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($col1, '  '.$Agl2)
                ->setCellValue($col2, '  '.$Aglnm2); 
                $Alvl2tot=0;
		        $Alvl3="SELECT c.glno,c.glnm,c.opbal  FROM `coa_mon` c  where c.ctlgl=$Agl2 and c.yr='$pyr' and c.mn='7' and c.oflag='N'";
			    $Aresult3 = $conn->query($Alvl3);
                if ($Aresult3->num_rows > 0) 
                {
                    while ($Arow3 = $Aresult3->fetch_assoc())
                    {
                        $Agl3= $Arow3["glno"]; $Aglnm3= $Arow3["glnm"];$Aopbal3= $Arow3["opbal"];$Aclosingbal3=0;
                        $i++;
                        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue($col1, '    '.$Agl3)
                                ->setCellValue($col2, '    '.$Aglnm3); 
                                
                        $Alvl4="select gl.glno,gl.glnm,sum(tr.dramt)debit,sum(tr.cramt)credit,gl.opbal
                                FROM
                                (
                                SELECT glno, glnm, opbal  FROM coa_mon  WHERE yr = '$pyr'  AND mn = 7   AND isposted = 'P'   AND oflag = 'N' 
                                AND ctlgl IN ( SELECT glno FROM coa_mon WHERE yr = '$pyr' AND mn = 7 AND ctlgl = '$Agl3' )
                                union all 
                                 select glno,glnm,opbal from coa_mon where yr='$pyr' and mn=7 and ctlgl='$Agl3' and isposted='P' and oflag='N'       
                                ) gl
                                left join 
                                (
                                select  d.glac,d.dr_cr
                                    ,(case when d.dr_cr='D' then sum(d.amount)  else 0 end) dramt
                                    ,(case when d.dr_cr='C' then sum(d.amount)  else 0 end) cramt
                                from glmst m , gldlt d where m.vouchno=d.vouchno and m.transdt between '$f_dt' and '$t_dt' and m.isfinancial in ('0','A')
                                and substr(d.glac,1,1)=1 group by d.glac,d.dr_cr
                                ) tr
                                on gl.glno=tr.glac
                                group by gl.glno,gl.glnm,gl.opbal"; 
                        $Aresult4 = $conn->query($Alvl4);
                        if ($Aresult4->num_rows > 0) 
                        {
                            $Aopbal4=0;
                            while ($Arow4 = $Aresult4->fetch_assoc())
                            { 
                                $Agl4=$Arow4["glno"];$Angl=$Arow4["glnm"]; $Aopbal4=$Arow4["opbal"]; $Anamt=$Arow4["debit"]-$Arow4["credit"];
                                
                                $i++;
                                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                                
                                $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue($col1, '      '.$Agl4)
                                        ->setCellValue($col2, '      '.$Angl)
                                        ->setCellValue($col3,($Aopbal4+$Anamt));
                                $Aclosingbal3=$Aclosingbal3+$Arow4["debit"]-$Arow4["credit"];
                            }
                        }
                        $Alvl2tot=$Alvl2tot+$Aclosingbal3+$Aopbal3;
                        $Alvl1tot=$Alvl1tot+$Aclosingbal3+$Aopbal3;
                        $i++;
                        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue($col1, '')
                                ->setCellValue($col2, '    Total '.$Aglnm3)
                                ->setCellValue($col3,($Aopbal3+$Aclosingbal3));
                    }
                }
                $i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue($col1, '')
                                ->setCellValue($col2, '  Total '.$Aglnm2)
                                ->setCellValue($col3,$Alvl2tot);
            }
        }
        $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, '')
                    ->setCellValue($col2, 'Total ASSET')
                    ->setCellValue($col3,$Alvl1tot);
        
        
        $lvl1tot=0;$j=$i;
        $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
         $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($col1, ' ')
        ->setCellValue($col2, 'Liability and Equity '); 
        
		$lvl2="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl='200000000' and c.yr='2023' and c.mn=7";
		$result2 = $conn->query($lvl2);
        if ($result2->num_rows > 0) 
        {
            while ($row2 = $result2->fetch_assoc())
            {
                $gl2= $row2["glno"]; $glnm2= $row2["glnm"];$closingbal2= $row2["closingbal"];  
                $i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                 $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($col1, '  '.$gl2)
                ->setCellValue($col2, '  '.$glnm2); 
                $lvl2tot=0;
		        $lvl3="SELECT c.glno,c.glnm,c.opbal  FROM `coa_mon` c  where c.ctlgl=$gl2 and c.yr='2023' and c.mn='7' and c.oflag='N'";
			    $result3 = $conn->query($lvl3);
                if ($result3->num_rows > 0) 
                {
                    while ($row3 = $result3->fetch_assoc())
                    {
                        $gl3= $row3["glno"]; $glnm3= $row3["glnm"];$opbal3= $row3["opbal"];$closingbal3=0;
                        $i++;
                        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                         $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($col1, '    '.$gl3)
                        ->setCellValue($col2, '    '.$glnm3); 
                        
                         $lvl4="select gl.glno,gl.glnm,sum(tr.dramt)debit,sum(tr.cramt)credit,gl.opbal
                                FROM
                                (
                                SELECT glno, glnm, opbal  FROM coa_mon  WHERE yr = '$pyr'  AND mn = 7   AND isposted = 'P'   AND oflag = 'N' 
                                AND ctlgl IN ( SELECT glno FROM coa_mon WHERE yr = '$pyr' AND mn = 7 AND ctlgl = '$gl3' )
                                union all 
                                 select glno,glnm,opbal from coa_mon where yr='$pyr' and mn=7 and ctlgl='$gl3' and isposted='P' and oflag='N'       
                                ) gl
                                left join 
                                (
                                select  d.glac,d.dr_cr
                                    ,(case when d.dr_cr='D' then sum(d.amount)  else 0 end) dramt
                                    ,(case when d.dr_cr='C' then sum(d.amount)  else 0 end) cramt
                                from glmst m , gldlt d where m.vouchno=d.vouchno and m.transdt between '$f_dt' and '$t_dt' and m.isfinancial in ('0','A')
                                and substr(d.glac,1,1)=2 group by d.glac,d.dr_cr
                                ) tr
                                on gl.glno=tr.glac
                                group by gl.glno,gl.glnm,gl.opbal";
                        $result4 = $conn->query($lvl4);
                        if ($result4->num_rows > 0) 
                        {
                            $opbal4=0;
                            while ($row4 = $result4->fetch_assoc())
                            { 
                            $gl4=$row4["glno"];$ngl=$row4["glnm"]; $opbal4=$row4["opbal"]; $namt=$row4["credit"]-$row4["debit"];
                            $pl=0;
                            if($gl4=='201040200')
                            {
                                $pfqry="select (sum(u.ca)-sum(u.da)) pl
                                    FROM
                                    (
                                        select d.dr_cr,
                                         (case when d.dr_cr='D' then sum(d.amount) else 0 end) da,
                                        (case when d.dr_cr='C' then sum(d.amount) else 0 end) ca
                                        from glmst m , gldlt d where m.vouchno=d.vouchno and m.isfinancial in('0','A') and m.transdt between '$f_dt' and '$t_dt'  
                                        and substr(d.glac,1,1) in(3,4) group by d.dr_cr
                                    )u";
                                $resultPF = $conn->query($pfqry);
                                if ($resultPF->num_rows > 0) {while ($rowpf = $resultPF->fetch_assoc()){$pl=$rowpf["pl"];}} 
                                $namt=$namt+$pl;
                             }
                            
                            $i++;
                            $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue($col1, '      '.$gl4)
                                        ->setCellValue($col2, '      '.$ngl)
                                        ->setCellValue($col3,($opbal4+$namt)); 
                                        
                            $closingbal3=$closingbal3+$namt;//$row4["credit"]-$row4["debit"];
                            }
                        }
                        $lvl2tot=$lvl2tot+$closingbal3+$opbal3;
                        $lvl1tot=$lvl1tot+$closingbal3+$opbal3;
                        $i++;
                        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue($col1, '')
                                    ->setCellValue($col2, '      Total '.$glnm3)
                                    ->setCellValue($col3,($opbal3+$closingbal3));
                    }
                }
                $i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($col1, '')
                            ->setCellValue($col2, '  Total '.$glnm2)
                            ->setCellValue($col3,$lvl2tot);
            }
        }
        
        $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, '')
                    ->setCellValue($col2, 'Total Liability')
                    ->setCellValue($col3,$lvl1tot);
                            
		$objPHPExcel->getActiveSheet()->setTitle('Balance Sheet');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'Bal_sheet'.$today.'.xls'; 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);
        
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$fileNm);
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
    else
    {
    $mode=1;//Insert mode
    }
    
    $currSection = 'rpt_balance_sheet_details';
    $currPage = basename($_SERVER['PHP_SELF']);
?>

<?php
     include_once('common_header.php');
?>
<body class="form">
    
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
        <?php include_once('menu.php'); ?>
       
        <div style="height:54px;"></div>
    </div>
    <!-- END #sidebar-wrapper --> 
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12" >
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
                       
                     <form method="post" action="rpt_balance_sheet_details.php?pg=1&mod=7" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="button-bar">
                            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label for="po_dt">Order Date*</label>
                                </div>     
                            </div> -->
                            <!--div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Year </div>
                                </div>     
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group"> 
                                    <select name="cmbyr" id="cmbyr" class="form-control" required>
                                    <?php $yr=date("Y");?>          
                                        <option value="<? echo $yr-1; ?>" <? if ($pyr == $yr-1) { echo "selected"; } ?>><? echo $yr-1; ?></option>
                                        <option value="<? echo $yr; ?>" <? if ($pyr == $yr) { echo "selected"; } ?>><? echo $yr; ?></option>
                                        <option value="<? echo $yr+1; ?>" <? if ($pyr == $yr+1) { echo "selected"; } ?>><? echo $yr+1; ?></option>
                                    </select>
                                </div>     
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Month</div> 
                                </div>     
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <select name="cmbmonth" id="cmbmonth" class="form-control" required>
<?php $mon= date('F');for($i=1;$i<=12;$i++){?>          
                                            <option value="<? echo  $i; ?>" <? if ($i == $pmn) { echo "selected"; } ?>><? echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
<?php } ?>                    
                                        </select>
                                </div>     
                            </div-->
                             <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >As On Date </div>
                                </div>      
                            </div> 
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="from_dt" id="from_dt" value="<?php echo $fd;?>"  required> 
                                </div>     
                            </div>
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  >
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="export" value="Export " id="export">
                            <input class="btn btn-lg btn-default print-view" type="button" name="cancel" value="Print">
                        </div>
                            <?php if($fdt!='') {include_once('phpajax/rpt_load_balance_sheet_detail.php');} ?> 
        <!-- /#end of panel  rpt_load_balance_sheet_detailrpt_load_balance_sheet-->
                    </form> 
                    
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->

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



<?php }?>