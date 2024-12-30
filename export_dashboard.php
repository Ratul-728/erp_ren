<?php
require "common/conn.php";

session_start();
$usr=$_SESSION["user"];
$chart= $_GET['chart'];
$action="export_dashboard.php?chart=".$chart;
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
   if ( isset( $_POST['export'] ) ) 
   {
       if ( $chart=='1' )
       {
            $objPHPExcel = new PHPExcel(); 
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'SL.')
                    ->setCellValue('B1', 'YEAR ')
                    ->setCellValue('C1', 'MONTH')
                    ->setCellValue('D1', 'NEW')
        			->setCellValue('E1', 'EXISTING')
        		    ->setCellValue('F1', 'TOTAL'); 
        			
            $firststyle='A2';
            $qry="SELECT   s.`yr`,DATE_FORMAT(STR_TO_DATE(s.mnth, '%m'), '%b') mn
    ,round(sum((case when r.yr=s.yr and r.month=s.mnth then (((s.`mrc`*(r.dy-s.`da`+1))/r.dy)+s.`otc`) Else 0 end )),2) n
    ,round(sum((case when (r.yr=s.yr and r.month>s.mnth) or (r.yr>s.yr) then (s.`mrc`) Else  0 end )),2) exs
    FROM  `rpt_sales_so` s  ,`reportmanth` r  
    WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr))
    and s.yr>=YEAR(CURDATE())
    group by s.`yr`, s.`mnth` order by s.`yr`, s.`mnth`"; 
           // echo  $qry;die;
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            { $i=0;
                while($row = $result->fetch_assoc()) 
                { 
                    $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;
                    $i++;$tot=$row['n']+$row['exs'];
                    $objPHPExcel->setActiveSheetIndex(0)
        			            ->setCellValue($col1, $i)
        			            ->setCellValue($col2, $row['yr'])
        						->setCellValue($col3, $row['mn'])
        					    ->setCellValue($col4, number_format($row['n'],2))
        					     ->setCellValue($col5, number_format($row['exs'],2))
        					     ->setCellValue($col6, number_format($tot,2));
        					    /* */
        			$laststyle=$title;	
                }
            }
            $objPHPExcel->getActiveSheet()->setTitle('SalseOrderTimeline');
            $objPHPExcel->setActiveSheetIndex(0);
            $today=date("YmdHis");
            $fileNm="data/".'Sales_'.$today.'.xls'; 
       }
       else if ( $chart=='2' )
       {
            $objPHPExcel = new PHPExcel(); 
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'SL.')
                    ->setCellValue('B1', 'ACCOUNT MANAGER ')
                    ->setCellValue('C1', 'OTC')
                    ->setCellValue('D1', 'MRC')
        			->setCellValue('E1', 'TOTAL'); 
        			
            $firststyle='A2';
            $qry="SELECT  s.`hrName`
,format(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,format(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc 
,format((sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end )+sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end)),2) tot
FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE()) group by s.`hrName`"; 
           // echo  $qry;die;
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            { $i=0;
                while($row = $result->fetch_assoc()) 
                { 
                    $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;
                    $i++;
                    $objPHPExcel->setActiveSheetIndex(0)
        			            ->setCellValue($col1, $i)
        			            ->setCellValue($col2, $row['hrName'])
        						->setCellValue($col3, $row['otcvalue'])
        					    ->setCellValue($col4, $row['pmrc'])
        					    ->setCellValue($col5, $row['tot']);
        					    /* */
        			$laststyle=$title;	
                }
            }
            $objPHPExcel->getActiveSheet()->setTitle('accManagerPerformance');
            $objPHPExcel->setActiveSheetIndex(0);
            $today=date("YmdHis");
            $fileNm="data/".'Sales_Acc_Mgr_perf_'.$today.'.xls'; 
       }
       else if ( $chart=='3' )
       {
            $objPHPExcel = new PHPExcel(); 
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'SL.')
                    ->setCellValue('B1', 'CATAGORY')
                    ->setCellValue('C1', 'OTC')
                    ->setCellValue('D1', 'MRC')
        			->setCellValue('E1', 'TOTAL'); 
        			
            $firststyle='A2';
            $qry="SELECT  s.`itm_cat`
,format(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,format(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc 
,format((sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end )+sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end)),2) tot
FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE()) 
group by s.`itm_cat`"; 
           // echo  $qry;die;
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            { $i=0;
                while($row = $result->fetch_assoc()) 
                { 
                    $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;
                    $i++;$tot=$row['otcvalue']+$row['pmrc'];
                    $objPHPExcel->setActiveSheetIndex(0)
        			            ->setCellValue($col1, $i)
        			            ->setCellValue($col2, $row['itm_cat'])
        						->setCellValue($col3, $row['otcvalue'])
        					    ->setCellValue($col4, $row['pmrc'])
        					    ->setCellValue($col5, $row['tot']);
        					    /* */
        			$laststyle=$title;	
                }
            }
            $objPHPExcel->getActiveSheet()->setTitle('Catagory');
            $objPHPExcel->setActiveSheetIndex(0);
            $today=date("YmdHis");
            $fileNm="data/".'Sales_catagory_'.$today.'.xls'; 
       }
       else if ( $chart=='4' )
       {
            $objPHPExcel = new PHPExcel(); 
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'SL.')
                    ->setCellValue('B1', 'FRANCHAISE')
                    ->setCellValue('C1', 'OTC')
                    ->setCellValue('D1', 'MRC')
        			->setCellValue('E1', 'TOTAL'); 
        			
            $firststyle='A2';
            $qry="SELECT  s.`size`
,round(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,round(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE()) 
group by s.`size`"; 
           // echo  $qry;die;
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            { $i=0;
                while($row = $result->fetch_assoc()) 
                { 
                    $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;
                    $i++;$tot=$row['otcvalue']+$row['pmrc'];
                    $objPHPExcel->setActiveSheetIndex(0)
        			            ->setCellValue($col1, $i)
        			            ->setCellValue($col2, $row['size'])
        						->setCellValue($col3, $row['otcvalue'])
        					    ->setCellValue($col4, $row['pmrc'])
        					    ->setCellValue($col5, $row['tot']);
        					    /* */
        			$laststyle=$title;	
                }
            }
            $objPHPExcel->getActiveSheet()->setTitle('Franchaise');
            $objPHPExcel->setActiveSheetIndex(0);
            $today=date("YmdHis");
            $fileNm="data/".'Sales_franchaise_'.$today.'.xls'; 
       }
       else if ( $chart=='6' )
       {
            $objPHPExcel = new PHPExcel(); 
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'SL.')
                    ->setCellValue('B1', 'PRODUCT')
                    ->setCellValue('C1', 'OTC')
                    ->setCellValue('D1', 'MRC')
        			->setCellValue('E1', 'TOTAL'); 
        			
            $firststyle='A2';
            $qry="SELECT  s.`itmnm`
,format(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2) otcvalue
,format(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc 
,format((sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end )+sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end)),2) tot
FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE()) 
group by s.`itmnm`  "; 
           // echo  $qry;die;
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            { $i=0;
                while($row = $result->fetch_assoc()) 
                { 
                    $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;
                    $i++;$tot=$row['otcvalue']+$row['pmrc'];
                    $objPHPExcel->setActiveSheetIndex(0)
        			            ->setCellValue($col1, $i)
        			            ->setCellValue($col2, $row['itmnm'])
        						->setCellValue($col3, $row['otcvalue'])
        					    ->setCellValue($col4, $row['pmrc'])
        					    ->setCellValue($col5, $row['tot']);
        					    /* */
        			$laststyle=$title;	
                }
            }
            $objPHPExcel->getActiveSheet()->setTitle('Product');
            $objPHPExcel->setActiveSheetIndex(0);
            $today=date("YmdHis");
            $fileNm="data/".'Sales_product_'.$today.'.xls'; 
       }
       else if ( $chart=='5' )
       {
            $objPHPExcel = new PHPExcel(); 
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'SL.')
                    ->setCellValue('B1', 'YEAR ')
                    ->setCellValue('C1', 'MONTH')
                    ->setCellValue('D1', 'OTC')
                    ->setCellValue('E1', 'NEW MRC')
        			->setCellValue('F1', 'EXISTING MRC')
        		    ->setCellValue('G1', 'TOTAL'); 
        			
            $firststyle='A2';
            $qry="SELECT  s.yr,DATE_FORMAT(STR_TO_DATE(s.mnth, '%m'), '%b') mn,round(sum((case when r.yr=s.yr and r.month=s.mnth then (((s.`mrc`*(r.dy-s.`da`+1))/r.dy)) Else 0 end )),2) nmrc
,round(sum((case when (r.yr=s.yr and r.month>s.mnth) or (r.yr>s.yr) then (s.`mrc`) Else  0 end )),2) emrc
,round(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2) otcvalue
FROM  `rpt_sales_so` s  ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr))
and s.yr>=YEAR(CURDATE())
group by s.yr,s.mnth order by s.`yr`, s.`mnth`"; 
           // echo  $qry;die;
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            { $i=0;
                while($row = $result->fetch_assoc()) 
                { 
                    $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;
                    $i++;$tot=$row['otcvalue']+$row['nmrc']+$row['emrc'];
                    $objPHPExcel->setActiveSheetIndex(0)
        			            ->setCellValue($col1, $i)
        			            ->setCellValue($col2, $row['yr'])
        						->setCellValue($col3, $row['mn'])
        						->setCellValue($col4, number_format($row['otcvalue'],2))
        					    ->setCellValue($col5, number_format($row['nmrc'],2))
        					     ->setCellValue($col6, number_format($row['emrc'],2))
        					     ->setCellValue($col7, number_format($tot,2));
        					    /* */
        			$laststyle=$title;	
                }
            }
            $objPHPExcel->getActiveSheet()->setTitle('NEWVSEXISTING');
            $objPHPExcel->setActiveSheetIndex(0);
            $today=date("YmdHis");
            $fileNm="data/".'Sales_n_v_e'.$today.'.xls'; 
       }
       else
       {
       
       }
   
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
?>
     <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php
     include_once('common_header.php');
    ?>
    
    <body class="list">
        
    <?php
     include_once('common_top_body.php');
    ?>
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>CRM DashBoard</span>
      </div>
      
    <?php
        include_once('menu.php');
    ?>
      
      	<div style="height:54px;">
    	</div>
      </div>
    
      <!-- END #sidebar-wrapper --> 
      
      <!-- Page Content -->
      <div id="page-content-wrapper">
        <div class="container-fluid xyz">
          <div class="row">
            <div class="col-lg-12">
            
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            
              <!--h1 class="page-title">Customers</a></h1-->
              <p>
              <!-- START PLACING YOUR CONTENT HERE -->
    
    
              <div class="panel panel-info">
      			<div class="panel-heading"><h1>CRM DashBoard</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="<?php echo $action; ?>" id="form1">
                        <div class="well list-top-controls"> 
                            <div class="row border">
                                <div class="col-sm-11 text-nowrap"> 
                                    <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                                </div>
                                <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                            <!--<div class="col-sm-1">
                                    <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                                </div> -->
                            </div>
                        </div>
    				</form>
                    

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable' width="100%">
                        <thead>
<?php if ( $chart=='1' ) { ?>                        
                        <tr>
                            <th>YEAR</th>
                            <th>Month</th>
                            <th>New</th>
                            <th>Existing</th>
                            <th>Total</th>
                        </tr>
<?php } else if( $chart=='2' ) { ?>  
                        <tr>
                            <th>Account manager</th>
                            <th>OTC</th>
                            <th>MRC</th>
                            <th>Total</th>
                        </tr>
<?php } else if( $chart=='3' ) { ?>
                        <tr>
                            <th>Item Catagory</th>
                            <th>OTC</th>
                            <th>MRC</th>
                            <th>Total</th>
                        </tr>
<?php } else if( $chart=='4' ) { ?>
                        <tr>
                            <th>Franchaise </th>
                            <th>OTC</th>
                            <th>MRC</th>
                            <th>Total</th>
                        </tr> 
<?php } else if( $chart=='5' ) { ?>
                        <tr>
                            <th>Year </th>
                            <th>Month</th>
                            <th>OTC</th>
                            <th>New MRC</th>
                            <th>Existing MRC</th>
                            <th>Total</th>
                        </tr>                          
<?php } else if( $chart=='6' ) { ?>
                        <tr>
                            <th>Product </th>
                            <th>OTC</th>
                            <th>MRC</th>
                            <th>Total</th>
                        </tr>                                         
<?php } else {}?>                        
                        </thead>
                        
                    </table>
                </div>  				
    			
    
                 </div>
            </div> 
            <!-- /#end of panel -->  
    
              <!-- START PLACING YOUR CONTENT HERE -->          
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
    
     <!-- Datatable JS -->       
		<script src="js/plugins/datagrid/datatables.min.js"></script>
        
        <!-- Script -->
        <script>
<?php if($chart=='1'){ ?>        
        $(document).ready(function(){
            $('#listTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'phpajax/data_grid_dashboard.php?action=1'
                },
                'columns': [
                    { data: 'yr' },
                    { data: 'mnth' },
                    { data: 'n' },
                    { data: 'exs' },
					{ data: 'total' }
                ]
            });
        });
<?php } else if($chart=='2'){ ?>        
        $(document).ready(function(){
            $('#listTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'phpajax/data_grid_dashboard.php?action=2'
                },
                'columns': [
                    { data: 'hrName' },
                    { data: 'otcvalue' },
                    { data: 'pmrc' },
					{ data: 'tot' }
                ]
            });
        });
<?php } else if($chart=='3'){ ?>        
        $(document).ready(function(){
            $('#listTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'phpajax/data_grid_dashboard.php?action=3'
                },
                'columns': [
                    { data: 'itm_cat' },
                    { data: 'otcvalue' },
                    { data: 'pmrc' },
					{ data: 'tot' }
                ]
            });
        });
<?php } else if($chart=='4'){ ?>        
        $(document).ready(function(){
            $('#listTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'phpajax/data_grid_dashboard.php?action=4'
                },
                'columns': [
                    { data: 'size' },
                    { data: 'otcvalue' },
                    { data: 'pmrc' },
					{ data: 'tot' }
                ]
            });
        }); 
<?php } else if($chart=='5'){ ?>        
        $(document).ready(function(){
            $('#listTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'phpajax/data_grid_dashboard.php?action=5'
                },
                'columns': [
                    { data: 'yr' },
                    { data: 'mn' },
                    { data: 'otcvalue' },
                    { data: 'nmrc' },
                    { data: 'emrc' },
					{ data: 'tot' }
                ]
            });
        });         
<?php } else if($chart=='6'){ ?>        
        $(document).ready(function(){
            $('#listTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'phpajax/data_grid_dashboard.php?action=6'
                },
                'columns': [
                    { data: 'itmnm' },
                    { data: 'otcvalue' },
                    { data: 'pmrc' },
					{ data: 'tot' }
                ]
            });
        });         
<?php }else {}?>			
		
        </script>  
    
    </body></html>
<?php }?>