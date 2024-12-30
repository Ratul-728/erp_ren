<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//$fd=$_POST['from_dt'];
//$td=$_POST['to_dt'];
//echo $usr;die;
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
    $pyr=$_POST['cmbyr'];
    if($pyr=='')
    {
     $pyr=date("Y");
    }
    //if($pmn =='')
    //{
     //$pmn=date("m");
    //}
    //echo $pyr;die;
  if ( isset( $_POST['export'] ) ) 
  {
       'As on '.date("F", strtotime($pmn)) .','.$pyr;
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B1', 'Bithut.com.bd.')
                ->setCellValue('B2', 'Balance Shhet')
                ->setCellValue('B3', 'For the Moth '.date("F", strtotime($pmn)).' , '.$pyr.'')
                ->setCellValue('A4', '')
                ->setCellValue('B4', 'Amount')
                ->setCellValue('A5', 'ASSTES')
                ->setCellValue('B4', ''); 
    			
        $firststyle='A5';
        //assets
        $elvl2="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl='100000000' and c.yr='$pyr' and c.mn='$pmn'";
		$eresult2 = $conn->query($elvl2);
        if ($eresult2->num_rows > 0) 
        {
            while ($erow2 = $eresult2->fetch_assoc())
            {
                $egl2= $erow2["glno"]; $eglnm2='   '.$erow2["glnm"];$eclosingbal2= $erow2["closingbal"];$i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $eglnm2)
    			            ->setCellValue($col2, '');	/* */
    			
			    $elvl3="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$egl2 and c.yr='$pyr' and c.mn='$pmn'";
		        $eresult3 = $conn->query($elvl3);
                if ($eresult3->num_rows > 0) 
                {
                    while ($erow3 = $eresult3->fetch_assoc())
                    {
                        $egl3= $erow3["glno"]; $eglnm3='      '. $erow3["glnm"];$eclosingbal3= $erow3["closingbal"];	$i++;
                        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;
                        $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $eglnm3)
    			            ->setCellValue($col2, $eclosingbal3);
                    }
                }
                $i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, '   Total '.$eglnm2)
    			            ->setCellValue($col2, $eclosingbal2);
                
            }
        }
        
    $elvl1="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.glno='100000000' and c.yr='$pyr' and c.mn='$pmn'";
    $eresult1 = $conn->query($elvl1);
    if ($eresult1->num_rows > 0)
    {
        $erow1 = $eresult1->fetch_assoc();
        $eclosingbal1= $erow1["closingbal"];
    }
		$i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,'Total ASSETS')
		            ->setCellValue($col2, $eclosingbal1);
		            
	//liability
	
	    $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,'LIABILITIES')
		            ->setCellValue($col2, '');
		            
	 $llvl2="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl='200000000' and c.yr='$pyr' and c.mn='$pmn'";
		$lresult2 = $conn->query($llvl2);
        if ($lresult2->num_rows > 0) 
        {
            while ($lrow2 = $lresult2->fetch_assoc())
            {
                $lgl2= $lrow2["glno"]; $lglnm2='   '.$lrow2["glnm"];$lclosingbal2= $lrow2["closingbal"];$i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $lglnm2)
    			            ->setCellValue($col2, '');	/* */
    			
			    $llvl3="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$lgl2 and c.yr='$pyr' and c.mn='$pmn'";
		        $lresult3 = $conn->query($llvl3);
                if ($lresult3->num_rows > 0) 
                {
                    while ($lrow3 = $lresult3->fetch_assoc())
                    {
                        $lgl3= $lrow3["glno"]; $lglnm3='      '. $lrow3["glnm"];$lclosingbal3= $lrow3["closingbal"];	$i++;
                        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;
                        $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $lglnm3)
    			            ->setCellValue($col2, $lclosingbal3);
                    }
                }
                $i++;
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, '   Total '.$lglnm2)
    			            ->setCellValue($col2, $lclosingbal2);
                
            }
        }
        
    $llvl1="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.glno='200000000' and c.yr='$pyr' and c.mn='$pmn'";
    $lresult1 = $conn->query($llvl1);
    if ($eresult1->num_rows > 0)
    {
        $lrow1 = $lresult1->fetch_assoc();
        $lclosingbal1= $lrow1["closingbal"];
    }
		$i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,'Total LIABILITIES')
		            ->setCellValue($col2, $lclosingbal1);
        
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
    
    $currSection = 'rpt_financial_statement';
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
                       
                     <form method="post" action="rpt_financial_statement.php?pg=1&mod=7" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="button-bar">
                            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label for="po_dt">Order Date*</label>
                                </div>     
                            </div> -->
                            <div class="col-lg-1 col-md-6 col-sm-6">
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
                            <!--div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Month</div> 
                                </div>     
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <select name="cmbmonth" id="cmbmonth" class="form-control" required>
<?php $mon= date('F');for($i=1;$i<=12;$i++){?>          
                                            <option value="<? echo  str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <? if (str_pad($i, 2, "0", STR_PAD_LEFT) == $pmn) { echo "selected"; } ?>><? echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
<?php } ?>                    
                                        </select>
                                </div>     
                            </div-->
                             
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  >
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="export" value="Export " id="export">
                            <input class="btn btn-lg btn-default print-view" type="button" name="cancel" value="Print">
                        </div>
                            <?php include_once('phpajax/rpt_load_financial_statment.php'); ?> 
        <!-- /#end of panel -->
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