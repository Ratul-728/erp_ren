<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();
$usr=$_SESSION["user"];
$res= $_GET['res'];
$msg= $_GET['msg'];

if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'as_on_report';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/approval_transfer_stock.php?res=0&msg='Insert Data'&mod=7");
    }
   if ( isset( $_POST['export'] ) ) {
       
        $fdt = $_POST["from_dt"];
        $tdt = $_POST["to_dt"];
        
        if($fdt != '')
        {
            
            $date_qry = " and h.stockdate=(select max(stockdate) from stockhist where product=h.product and store=h.store and stockdate<=STR_TO_DATE('$fdt', '%d/%m/%Y') and stockdate >='2024-11-30')";
        }
        else
        {
            $date_qry = "";
        }
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Product')
                ->setCellValue('C1', 'Barcode')
                ->setCellValue('D1', 'Description')
    			->setCellValue('E1', 'QTY')
    			->setCellValue('F1', 'Rate')
                ->setCellValue('G1', 'Cost')
                ->setCellValue('H1', 'Location')
                ->setCellValue('I1', 'Stock Date');
    			
        $firststyle='A2';
        $qry="select i.barcode,i.name product,i.description,h.freeqty qty,h.`costprice` rate,(h.`freeqty`*h.`costprice`) cost,b.name loc,DATE_FORMAT(h.`stockdate`, '%d/%b/%Y') stockdate from stockhist h, item i,branch b  where h.product=i.id and h.store=b.id $date_qry order by i.barcode, b.name"; 
        // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['product'])
    						->setCellValue($col3, $row['barcode'])
    					    ->setCellValue($col4, $row['description'])
    					    ->setCellValue($col5, $row['qty'])
    					    ->setCellValue($col6, number_format($row['rate'], 2))
    					    ->setCellValue($col7, number_format($row['cost'], 2))
    					    ->setCellValue($col8, $row['loc'])
    					    ->setCellValue($col9, $row['stockdate']);
    					     	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Stock As On Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'stock_as_on_report'.$today.'.xls'; 
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
        <span>As On Report</span>
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
      		<!--	<div class="panel-heading"><h1>All Collection</h1></div> -->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>

                	<form method="post" action="#" id="form1">
            
                     <div class="well list-top-controls"> 
                     <!-- <div class="row border">
                       
                        <div class="col-sm-11 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div> -->
                        <div class="row border">
                          
                          
                          
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>Report <i class="fa fa-angle-right"></i>As On Report</h6>
                       </div>
                       
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">

                            <!--<input type="hidden" name="from_dt" id = "from_dt">-->
                            <!--<input type="hidden" name="to_dt" id = "to_dt">-->
                            
                             <div class ="form-group">
                                <div class="  pull-right col-lg-9 col-md-4 col-sm-4">
            					<div class="input-group">
            						<input type="text" class="form-control datepicker_history_filter" placeholder="Date" name="from_dt" id="from_dt" value="<?php echo $fdt;?>"  >
            						<div class="input-group-addon">
            							<span class="glyphicon glyphicon-th"></span>
            						</div>
            					</div>     
            				</div>
            				
                            </div>
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            
							
							<div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_as_on.php">
                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
								</ul>
							</div>

                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                        </div>
                        
                        </div>
                        
                        
                      </div>
                    </div>
                    
    
    				</form>
         <style>
         .ajax-img-up{
            border: 0px solid #000!important;
            display: flex;
            text-align: left;
        }
        .ajax-img-up ul{
          margin-bottom: 0;
          margin-left: 0!important;
            padding-left: 0px;
        }
        
        .ajax-img-up li{
          display: block;
          width: 40px;
          height: 40px;
          border: 1px solid #888787;
          position: relative;
          margin: 3px;
          border-radius: 0px;
          border-radius: 5px;
        }
        
        
        .ajax-img-up li img{
          width: 100%;
          height: 100%;
          border-radius: 5px;
        }

         </style>

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Product</th>
                            <th>Barcode</th>
                            <th>Description</th>
                            <th>QTY</th>
                            <th>Rate</th>
                            <th>Cost</th>
                            <th>Warehouse</th>
                        </tr>
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
    <?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
    
     <!-- Datatable JS -->       
		<script src="js/plugins/datagrid/datatables.min.js"></script>
        
        <!-- Script -->
        <script>
        $(document).ready(function(){
            
            function table_with_filter(url){
	
        	 var table1 =  $('#listTable').DataTable().destroy();
             var table1 = $('#listTable').DataTable({
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 25,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,
				"order": [[ 0, "desc" ]],
				"dom": "rtiplf",
                'ajax': {
                    
					'url':url,
                },
                
				'columns': [
                    { data: 'id' },
                    { data: 'product', "orderable": false },
                    { data: 'barcode', "orderable": false },
                    { data: 'description', "orderable": false},
                    { data: 'qty', "orderable": false },
                    { data: 'rate', "orderable": false },
                    { data: 'cost' },
					{ data: 'loc', "orderable": false },
                ],
				 
            });
	
			
	
            
            //new $.fn.dataTable.FixedHeader( table1 );
            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
            
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })            
            
		}
		
		//general call on page load
	url = 'phpajax/datagrid_report.php?action=ason';
	table_with_filter(url);	
        	
        // $("#from_dt").change(function(){
        // 	var fdt =  $('#from_dt').val();
        // 	url = 'phpajax/datagrid_report.php?action=ason&dt_f='+fdt;
        // 	table_with_filter(url);
        // });
        
        // Use dp.change event for Bootstrap DateTimePicker
        $('#from_dt').datetimepicker({
            format: 'YYYY-MM-DD' // or your preferred format
        }).on('dp.change', function(e) {
            var fdt = e.date.format('YYYY-MM-DD'); // format as needed
            url = 'phpajax/datagrid_report.php?action=ason&dt_f=' + fdt;
            table_with_filter(url);
        });

            
        });
		
</script>  
        
    
    </body></html>
  <?php }?>    
