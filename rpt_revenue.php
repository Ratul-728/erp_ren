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
        
        
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D1', 'Bithut.com.bd.')
                ->setCellValue('D2', 'Revenue Report')
               
                ->setCellValue('A4', 'Sl')
                ->setCellValue('B4', 'SO')
                ->setCellValue('C4', 'Customer')
                ->setCellValue('D4', 'OTC')
                ->setCellValue('E4', 'MRC')
                ->setCellValue('F4', 'Revenue')
                ->setCellValue('G4', 'Income ')
                ->setCellValue('H4', 'Expense')
                ->setCellValue('I4', 'Margin'); 
    			
        $firststyle='A7';
$mart=0;$otct=0;$mrct=0;$revt=0;$inct=0;$costt=0;$revt=0;$i=0;
$qry2="SELECT s.id,s.socode,o.name organization 
,sum(d.qty*d.otc) otc,sum(d.qtymrc*d.mrc) mrc
,(select  sum(`invoiceamt`) inv from invoice where soid=s.socode) rev
,(select  sum(`paidamount`) inv from invoice where soid=s.socode) inc
,(select  sum(`amount`)  from expense where soid=s.id) cost
FROM soitem s left join organization o on s.organization=o.id
 left join soitemdetails d on s.socode=d.socode group by s.id,s.socode,o.name ";
$result2 = $conn->query($qry2); if ($result2->num_rows > 0) {while($row2 = $result2->fetch_assoc()) 
{
    $socode=$row2["socode"];$org=$row2["organization"];  $otc=$row2["otc"];$mrc=$row2["mrc"]; $rev=$row2["rev"]; $inc=$row2["inc"];$cost=$row2["cost"];
    $mar=$inc-$cost;$otct=$otct+$otc;$mrct=$mrct+$mrc;$inct=$inct+$inc;$costt=$costt+$cost;$mart=$mart+$mar;$revt=$revt+$rev;$i++;
                
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;
                
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $socode)
    						->setCellValue($col3, $org)
    						->setCellValue($col4, $otc)
    						->setCellValue($col5, $mrc)
    						->setCellValue($col6, $rev)
    						->setCellValue($col7, $inc)
    						->setCellValue($col8, $cost)
    						->setCellValue($col9, $mar);	/* */
    			$laststyle=$title;	
            }
            $urut=$i+6;	$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;
            $objPHPExcel->setActiveSheetIndex(0)
				    ->setCellValue($col3, 'Total')
					->setCellValue($col4, $otct)
					->setCellValue($col5, $mrct)
					->setCellValue($col6, $revt)
					->setCellValue($col7, $inct)
					->setCellValue($col8, $costt)
					->setCellValue($col9, $mart);
			
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('REvenue');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'revenue'.$today.'.xls'; 
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
    
    $currSection = 'rpt_revenue';
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
                       
                     <form method="post" action="rpt_revenue.php" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="button-bar">
                            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label for="po_dt">Order Date*</label>
                                </div>     
                            </div> -->
                            <!--div class="col-lg-1 col-md-6 col-sm-6">
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
                            </div -->
                             
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  >
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="export" value="Export Revenue" id="export">
                            <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')">
                        </div>
                  
                    <?php include_once('phpajax/rpt_load_revenue.php'); ?> 
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