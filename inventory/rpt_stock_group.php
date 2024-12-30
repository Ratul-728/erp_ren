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
        //echo $fd1;
        //die;
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D1', 'Almas.com.bd.')
                ->setCellValue('D2', 'Group Wise Stock  Report')
                ->setCellValue('D3', 'Date   '.$fd1.'   To   '.$td1.'')
                ->setCellValue('A4', 'Sl')
                ->setCellValue('B4', 'Group')
                ->setCellValue('C4', 'Catagory')
                ->setCellValue('D4', 'Type')
                ->setCellValue('E4', 'Product')
                ->setCellValue('F4', 'Free Qty')
                ->setCellValue('G4', 'Cost Rate')
                ->setCellValue('H4', 'Cost Price')
                ->setCellValue('I4', 'MRP')
                ->setCellValue('J4', 'MRP Total'); 
    			
        $firststyle='A7';
        $qry="SELECT g.name gn,c.title cn,t.name tn,p.name pn,s.freeqty,s.bookqty,s.costprice,p.mrp FROM stock s,product p ,catagorygrouping cg,itemgroup g,catagory c, itemtype t 
where s.product = p.id and p.catagory=cg.itemtype and cg.itemgroup=g.id and cg.itemcatagory=c.id and cg.itemtype=t.id
order by g.name,c.title ,t.name,p.name"; 
        //echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;$cptot=0;$mrptotal=0;
            while($row = $result->fetch_assoc()) 
            { 
                $grp=$row["gn"]; $cat=$row["cn"]; $tn=$row["tn"];
                $prd=$row["pn"];$freqty=$row["freeqty"];$costp=$row["costprice"];$cost=$freqty*$costp;$mrp=$row["mrp"];$mrptot=$freqty*$mrp;
                $cptot=$cptot+$cost;$mrptotal=$mrptotal+$mrptot;
                
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $grp)
    						->setCellValue($col3, $cat)
    						->setCellValue($col4, $tn)
    						->setCellValue($col5, $prd)
    						->setCellValue($col6, $freqty)
    						->setCellValue($col7, $costp)
    						->setCellValue($col8, $cost)
    						->setCellValue($col9, $mrp)
    						->setCellValue($col10, $mrptot);	/* */
    			$laststyle=$title;	
            }
            $urut=$i+6;	$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;
            $objPHPExcel->setActiveSheetIndex(0)
					
				    ->setCellValue($col6, 'Total')
					->setCellValue($col8, $cptot)
					->setCellValue($col10, $mrptotal);
			
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Group Wise Stock');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'group_wise_stock'.$today.'.xls'; 
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
    
    $currSection = 'rpt_stock';
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
                       
                     <form method="post" action="rpt_stock_group.php" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="button-bar">
                            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label for="po_dt">Order Date*</label>
                                </div>     
                            </div> -->
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                   <!-- <div >Date From</div> --> 
                                </div>     
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                   <!-- <input type="text" class="form-control datepicker" name="from_dt" id="from_dt" value="<?php echo $fd1;?>"  required> -->
                                </div>     
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                   <!-- <div >Date To</div> -->
                                </div>     
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <!--<input type="text" class="form-control datepicker" id="to_dt" name="to_dt"  value="<?php echo $td1;?>" required> -->
                                    
                                </div>     
                            </div>
                             
                            <!--<input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  > -->
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="export" value="Export Stock" id="export">
                            <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')">
                        </div>
                  
                    <?php include_once('phpajax/rpt_load_stock_group.php'); ?> 
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