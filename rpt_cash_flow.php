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
    //$f=date("d/m/Y");
    //$t=date("d/m/Y");
    $fd=$_POST['from_dt'];
    $td=$_POST['to_dt'];
    if($fd==''){$fd=date("d/m/Y");}
    if($td==''){$td=date("d/m/Y");}
    
  if ( isset( $_POST['export'] ) ) {
        //echo $fd1;
        //die;
        $bal=0;$i=0;$bf=0;$totdr=0;$totcr=0;$net=0;
        //echo $fd;die;
        $qry0="select sum(paidamount) dra from invoice where invoicedt < STR_TO_DATE('".$fd."','%d/%m/%Y')";
        $qry1="select sum(amount) cra from expense where trdt < STR_TO_DATE('".$fd."','%d/%m/%Y')";
        //echo $qry0;die;
        $result0 = $conn->query($qry0);
        $row0 = $result0->fetch_assoc();
        $d=$row0["dra"];
        //echo $d;die;
        $result1 = $conn->query($qry1);
        $row1 = $result1->fetch_assoc();
        $c=$row1["cra"];
        $bal=$d-$c;
                
        
        
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D1', 'Bithut.com.bd.')
                ->setCellValue('D2', 'Cash Flow Report')
                ->setCellValue('D3', 'Date   '.$fd.'   To   '.$td.'')
                ->setCellValue('A4', 'Sl')
                ->setCellValue('B4', 'Date')
                ->setCellValue('C4', 'Naration')
                ->setCellValue('D4', 'Debit')
                ->setCellValue('E4', 'Credit')
                ->setCellValue('F4', 'Balance')
                ->setCellValue('C5', 'BF ')
                ->setCellValue('D5', '')
                ->setCellValue('E5', '')
                ->setCellValue('F5', $bal); 
    			
        $firststyle='A7';
        $qry2="select date_format(trdt,'%d/%m/%Y') trdt,narr,incm dr,expns cr
        FROM
        (
        SELECT `invoicedt` trdt,`paidamount` incm,0 expns,concat(soid,'-',invoiceno) narr 
            FROM invoice where invoicedt between STR_TO_DATE('".$fd."','%d/%m/%Y') and  STR_TO_DATE('".$td."','%d/%m/%Y')
        union all 
        select trdt  trdt,0 incm,amount expns,naration narr from expense where trdt between STR_TO_DATE('".$fd."','%d/%m/%Y') and  STR_TO_DATE('".$td."','%d/%m/%Y')
        ) u
        order by trdt";
        $result2 = $conn->query($qry2); if ($result2->num_rows > 0) {while($row2 = $result2->fetch_assoc()) 
        {
                $trdt=$row2["trdt"];$narr=$row2["narr"]; $dr=$row2["dr"]; $cr=$row2["cr"];  
    $bal=$bal+$dr-$cr;$i++;$totdr=$totdr+$dr;$totcr=$totcr+$cr;
                
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;
                
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $trdt)
    						->setCellValue($col3, $narr)
    						->setCellValue($col4, $dr)
    						->setCellValue($col5, $cr)
    						->setCellValue($col6, $bal);	/* */
    			$laststyle=$title;	
            }
            $urut=$i+6;	$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;
            $objPHPExcel->setActiveSheetIndex(0)
				    ->setCellValue($col3, 'Total')
					->setCellValue($col4, $totdr)
					->setCellValue($col5, $totcr)
					->setCellValue($col6, $bal);
			
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Cash Flow');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'cash_flow'.$today.'.xls'; 
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
    
    $currSection = 'rpt_cash_flow';
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
            <span>Stock</span>
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
                       
                     <form method="post" action="rpt_cash_flow.php" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="button-bar">
                            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label for="po_dt">Order Date*</label>
                                </div>     
                            </div> -->
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Date From</div>
                                </div>     
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="from_dt" id="from_dt" value="<?php echo $fd;?>"  required> 
                                </div>     
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Date To</div> 
                                </div>     
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="to_dt" name="to_dt"  value="<?php echo $td;?>" required> 
                                    
                                </div>     
                            </div>
                             
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  >
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="export" value="Export Flow" id="export">
                            <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')">
                        </div>
                  
                    <?php include_once('phpajax/rpt_load_cash_flow.php'); ?> 
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

</body>
</html>



<?php }?>