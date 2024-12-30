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
   // $pmn=$_POST['cmbmonth'];
   // $pyr=$_POST['cmbyr'];
   // if($pyr=='')
  //  {
  //   $pyr=date("Y");
 //   }
  //  if($pmn =='')
  //  {
  //   $pmn=date("m");
  //  }
    //echo $pyr;die;
  if ( isset( $_POST['export'] ) ) 
  {
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
       /*  //assets
        $lvl1tot=0;
		$lvl2="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl='100000000' and c.yr='$pyr' and c.mn=7";
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
		        $lvl3="SELECT c.glno,c.glnm,c.opbal  FROM `coa_mon` c  where c.ctlgl=$gl2 and c.yr='$pyr' and c.mn='7' and c.oflag='N'";
			    $result3 = $conn->query($lvl3);
                if ($result3->num_rows > 0) 
                {
                    while ($row3 = $result3->fetch_assoc())
                    {
                        $gl3= $row3["glno"]; $glnm3= $row3["glnm"];$opbal3= $row3["opbal"];$closingbal3=0;
                        $lvl4="select sum(damt) debit,sum(camt) credit FROM
(
select COALESCE(sum(d.amount),0) damt,0 camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl='$gl3' and oflag='N' ) and d.dr_cr='D' and m.isfinancial in('0','A')  and
(m.transdt Between '$f_dt' and '$t_dt')
union all
select COALESCE(sum(d.amount),0) damt,0 camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl3' and oflag='N')  and oflag='N') and d.dr_cr='D' and m.isfinancial in('0','A')  and
(m.transdt Between '$f_dt' and '$t_dt')
union all
select 0 damt,COALESCE(sum(d.amount),0) camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl='$gl3' and oflag='N') and d.dr_cr='C' and m.isfinancial in('0','A')  and
(m.transdt Between '$f_dt' and '$t_dt')
union all
select  0 damt,COALESCE(sum(d.amount),0) camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$gl3') and oflag='N') and d.dr_cr='C' and m.isfinancial in('0','A')  and
(m.transdt Between '$f_dt' and '$t_dt')
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
                        $i++;
                        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                         $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($col1, '    '.$gl3)
                        ->setCellValue($col2, '    '.$glnm3)
                        ->setCellValue($col3, ($opbal3+$closingbal3)); 
                    }
                }
                $i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                 $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($col1, '')
                ->setCellValue($col2, '    Total '.$glnm2)
                ->setCellValue($col3, ($lvl2tot));         
            }
        }
         $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
         $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($col1, '')
        ->setCellValue($col2, 'Total Asset')
        ->setCellValue($col3, ($lvl1tot));  
               */
         $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
         $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($col1, '200000000')
        ->setCellValue($col2, 'Liability & Equity');  
        $Llvl1tot=0;
		$Llvl2="SELECT c.glno,c.glnm,c.opbal FROM `coa_mon` c  where c.ctlgl='200000000' and c.yr='$pyr' and c.mn=7";
		$Lresult2 = $conn->query($Llvl2);
        if ($Lresult2->num_rows > 0) 
        {
            while ($Lrow2 = $Lresult2->fetch_assoc())
            {
                $Lgl2= $Lrow2["glno"]; $Lglnm2= $Lrow2["glnm"];$Lclosingbal2= $Lrow2["opbal"];
                $i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                 $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($col1,'  '.$Lgl2)
                ->setCellValue($col2, '  '.$Lglnm2);  
                $Llvl2tot=0;  
		        $Llvl3="SELECT c.glno,c.glnm,c.opbal FROM `coa_mon` c  where c.ctlgl=$Lgl2 and c.yr='$pyr' and c.mn=7 and c.oflag='N'";
		        
			    $Lresult3 = $conn->query($Llvl3);
                if ($Lresult3->num_rows > 0) 
                {
                    while ($Lrow3 = $Lresult3->fetch_assoc())
                    {
                        $Lgl3= $Lrow3["glno"]; $Lglnm3= $Lrow3["glnm"];$Lopbal3= $Lrow3["opbal"];$Lclosingbal3=0;
                         
                        $Llvl4="select sum(damt) debit,sum(camt) credit FROM
(
select COALESCE(sum(d.amount),0) damt,0 camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl='$Lgl3') and d.dr_cr='D' and m.isfinancial in('0','A')  and
(m.transdt Between '$f_dt' and '$t_dt')
union all
select COALESCE(sum(d.amount),0) damt,0 camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$Lgl3')) and d.dr_cr='D' and m.isfinancial in('0','A')  and
(m.transdt Between '$f_dt' and '$t_dt')
union all
select 0 damt,COALESCE(sum(d.amount),0) camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl='$Lgl3') and d.dr_cr='C' and m.isfinancial in('0','A')  and
(m.transdt Between '$f_dt' and '$t_dt')
union all
select  0 damt,COALESCE(sum(d.amount),0) camt from glmst m,gldlt d where m.vouchno=d.vouchno and d.glac in (select glno from coa where ctlgl in (select glno from coa where ctlgl='$Lgl3')) and d.dr_cr='C' and m.isfinancial in('0','A')  and
(m.transdt Between '$f_dt' and '$t_dt')
) u";
                       // echo $lvl4;die;
                       //if($Lgl3=='203030000'){echo $Llvl4;die;}
                        $Lresult4 = $conn->query($Llvl4);
                        if ($Lresult4->num_rows > 0) 
                        {
                            while ($Lrow4 = $Lresult4->fetch_assoc())
                            {
                                $Lclosingbal3=$Lclosingbal3-$Lrow4["debit"]+$Lrow4["credit"];
                                
                            }
                        }
                        
                        $pl=0;
                        if($Lgl3=='201040000')
                        {
                            $pfqry="select (sum(u.ca)-sum(u.da)) pl
                                FROM
                                (
                                    select d.dr_cr,
                                     (case when d.dr_cr='D' then sum(d.amount) else 0 end) da,
                                    (case when d.dr_cr='C' then sum(d.amount) else 0 end) ca
                                    from glmst m , gldlt d where m.vouchno=d.vouchno and m.isfinancial in('0','A') and m.transdt between  '$f_dt' and '$t_dt'
                                    and substr(d.glac,1,1) in(3,4) group by d.dr_cr
                                )u";
                            $resultPF = $conn->query($pfqry);
                            if ($resultPF->num_rows > 0) {while ($rowpf = $resultPF->fetch_assoc()){$pl=$rowpf["pl"];}} 
                            $Lclosingbal3=$Lclosingbal3+$pl;
                         }
                        $Llvl2tot=$Llvl2tot+$Lclosingbal3+$Lopbal3;
                        $Llvl1tot=$Llvl1tot+$Lclosingbal3+$Lopbal3;
                        $i++;
                        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                         $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($col1, '    '.$Lgl3)
                        ->setCellValue($col2, '    '.$Lglnm3)
                        ->setCellValue($col3, ($Lopbal3+$Lclosingbal3)); 
                    }
                }
                $i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
                 $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($col1, '')
                ->setCellValue($col2, '  Total'.$Lglnm2)
                ->setCellValue($col3, $Llvl2tot); 
            }
        }
         $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
         $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($col1, '')
        ->setCellValue($col2, 'TOTAL LIABILITIES')
        ->setCellValue($col3, $Llvl1tot); 

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
    
    $currSection = 'rpt_balance_sheet';
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
                       
                     <form method="post" action="rpt_balance_sheet.php?pg=1&mod=7" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="button-bar">
                           
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
                            <?php if($fdt!='') {include_once('phpajax/rpt_load_balance_sheet.php');} ?> 
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