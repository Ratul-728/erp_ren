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
    $fd1=$_POST['from_dt'];
    $td2=$_POST['to_dt'];
    if($fd1==''){$fd1=date("d/m/Y");}
    if($td1==''){$td1=date("d/m/Y");}
    
  if ( isset( $_POST['export'] ) ) {
        //echo $fd1; die;
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D1', 'Almas.com.bd.')
                ->setCellValue('D2', 'Invoice Based Sales Report')
                ->setCellValue('D3', 'Date   '.$fd1.'   To   '.$td1.'')
                ->setCellValue('A4', 'Sl')
                ->setCellValue('B4', 'Order No')
                ->setCellValue('C4', 'Order Date')
                ->setCellValue('D4', 'Amount')
                ->setCellValue('E4', 'Discount')
                ->setCellValue('F4', 'VAT')
                ->setCellValue('G4', 'Total')
                ->setCellValue('H4', 'Payment Mode')
                ->setCellValue('I4', 'Order Status')
                ->setCellValue('J4', 'Delivery Date'); 
    			
        $firststyle='A7';
        $qry="SELECT o.order_id,DATE_FORMAT(o.order_date,'%e/%c/%Y') order_date,o.customer_id,o.amount,o.discount_total,o.vat_amount,(o.amount+o.vat_amount) tot,o.payment_mood
,o.orderstatus,o.status,s.name ost, o.orderstatus,DATE_FORMAT(o.deliverydt,'%e/%c/%Y') deliverydt
FROM orders o left join orderstatus s on o.orderstatus=s.id where o.order_date BETWEEN STR_TO_DATE('".$fd1."','%d/%m/%Y') and  STR_TO_DATE('".$td1."','%d/%m/%Y')"; 
        //echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;$gam=0;$gd=0;$gv=0;$gt=0;$urut=0;
            while($row = $result->fetch_assoc()) 
            { 
                $amount=$row["amount"]; $discount_total=$row["discount_total"]; $vat_amount=$row["vat_amount"];
                $tot=$row["tot"];$orderstid=$row["orderstatus"];
                $gam=$gam+$amount;$gd=$gd+$discount_total;$gv=$gv+$vat_amount;$gt=$gt+$tot;
                if($orderstid==7)
                {
                $rgam=$rgam+$amount;$rgd=$rgd+$discount_total;$rgv=$rgv+$vat_amount;$rgt=$rgt+$tot;
                }
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['order_id'])
    						->setCellValue($col3, $row['order_date'])
    						->setCellValue($col4, $row['amount'])
    						->setCellValue($col5, $row['discount_total'])
    						->setCellValue($col6, $row['vat_amount'])
    						->setCellValue($col7, $row['tot'])
    						->setCellValue($col8, $row['payment_mood'])
    						->setCellValue($col9, $row['ost'])
    						->setCellValue($col10, $row['deliverydt']);	/* */
    			$laststyle=$title;	
            }
            $urut=$i+6;	$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;
            $objPHPExcel->setActiveSheetIndex(0)
					
				    ->setCellValue($col3, 'Total')
					->setCellValue($col4, $gam)
					->setCellValue($col5, $gd)
					->setCellValue($col6, $gv)
					->setCellValue($col7, $gt);
			$urut=$i+7;	$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col3, 'Return')
					->setCellValue($col4, $rgam)
					->setCellValue($col5, $rgd)
					->setCellValue($col6, $rgv)
					->setCellValue($col7, $rgt);
			$urut=$i+8;	$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col3, 'Net')
					->setCellValue($col4, $gam-$rgam)
					->setCellValue($col5, $gd-$rgd)
					->setCellValue($col6, $gv-$rgv)
					->setCellValue($col7, $gt-$rgt);
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Daily Sales Summery');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'sales'.$today.'.xls'; 
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
    
    $currSection = 'rpt_order_delivery';
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
            <span>Order Delivery</span>
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
                       
                     <form method="post" action="rpt_order_delivery.php" id="form1" enctype="multipart/form-data">  
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
                                    <input type="text" class="form-control datepicker" name="from_dt" id="from_dt" value="<?php echo $fd1;?>"  required>
                                    
                                   
                                </div>     
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Date To</div>
                                </div>     
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="to_dt" name="to_dt"  value="<?php echo $td1;?>" required>
                                    
                                </div>     
                            </div>
                             
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  >
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="export" value="Export" id="export">
                            <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')">
                        </div>
                  
                    <?php include_once('phpajax/rpt_load_delivery.php'); ?> 
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