<?php
require "common/conn.php";
require "rak_framework/misfuncs.php";
require "common/user_btn_access.php";

session_start();
$usr=$_SESSION["user"];


if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
     $currSection = 'rpt_revenue_new';
     include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    $fd1=$_POST['from_dt'];
    $td1=$_POST['to_dt'];
    
    if ( isset( $_POST['export'] ) ) {
        $fd1 = $_POST["from_dt"];
        $td1 = $_POST["to_dt"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and qt.orderdate BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Date')
                ->setCellValue('C1', 'Order ID')
                ->setCellValue('D1', 'Customer')
    			->setCellValue('E1', 'Amount')
    			->setCellValue('F1', 'Vat')
                ->setCellValue('G1', 'Adjustment Amount')
                ->setCellValue('H1', 'Delivery Amount')
                ->setCellValue('I1', 'Discounted Total')
    			->setCellValue('J1', 'Revenue');
    			
        $firststyle='A2';
        
        $qry1="SELECT DATE_FORMAT(q.orderdate,'%d/%b/%Y') AS date, q.socode order_id, o.name AS customer, 
                                FORMAT(SUM(qd.otc), 2) AS amount, FORMAT(SUM(qd.vat), 2) AS vat, FORMAT(SUM(q.adjustment), 2) AS adjustment_amount,
                                FORMAT(SUM(q.deliveryamt), 2) AS delivery_amount, FORMAT(SUM(qd.discounttot), 2) AS discounted_total,
                                FORMAT(SUM(q.invoiceamount), 2) AS revenue, FORMAT(SUM(qd.cost), 2) AS cost, FORMAT(SUM(q.invoiceamount - qd.cost), 2) AS margin
                                FROM quotation AS q JOIN quotation_detail AS qd ON q.socode = qd.socode JOIN organization AS o ON q.organization = o.id
                                WHERE q.orderstatus in(5,6) $dateqry
                                GROUP BY q.orderdate, q.socode, o.name
                                ORDER BY q.orderdate DESC
             ";
         $qry= "select DATE_FORMAT(qt.orderdate,'%d/%b/%Y') AS date, qt.socode order_id, o.name AS customer,  FORMAT(SUM((qd.discounttot+qd.discount_amount)/qd.qty), 2) AS amount, FORMAT(SUM(qd.vat), 2) AS vat,FORMAT(SUM(qd.discounttot/qd.qty), 2) AS adjustment_amount,FORMAT((SUM(qd.discounttot/qd.qty)*dd.`delivered_qty`), 2) delivery_amount
                       ,FORMAT(SUM(qd.discounttot), 2) AS discounted_total,FORMAT(SUM(qd.discounttot/qd.qty)*dd.`delivered_qty`, 2) revenue,FORMAT(SUM(qd.cost), 2) AS cost
                       , FORMAT((SUM(qd.discounttot/qd.qty)*dd.`delivered_qty`) - SUM(qd.cost), 2) AS margin
                              from delivery_order_detail dd ,qa q,quotation_detail qd,quotation qt,organization o where dd.qa_id=q.id  and q.order_id=qd.socode and dd.item=qd.productid and qd.socode=qt.socode 
                              and qt.organization = o.id  and dd.st=2 AND q.type=1 $dateqry
                                GROUP BY qt.orderdate, qt.socode, o.name
                                ORDER BY qt.orderdate DESC"; 
            
            
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;$tcp=0;$tmp=0;
            $totamount = 0; $totvat = 0; $totadamt = 0;
            $totdeliamt = 0; $totdisamt = 0; $totrev = 0;

            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $i++;
                
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['date'])
    						->setCellValue($col3, $row['order_id'])
    					    ->setCellValue($col4, $row['customer'])
    					    ->setCellValue($col5, $row['amount'])
    					    ->setCellValue($col6, $row['vat'])
    			            ->setCellValue($col7, $row['adjustment_amount'])
    						->setCellValue($col8, $row['delivery_amount'])
    					    ->setCellValue($col9, $row['discounted_total'])
    					    ->setCellValue($col10,$row['revenue']);	/* */
    			$laststyle=$title;
    			// Remove commas and convert to float before adding
                $totamount += (float)str_replace(',', '', $row['amount']);
                $totvat += (float)str_replace(',', '', $row['vat']);
                $totadamt += (float)str_replace(',', '', $row['adjustment_amount']);
                $totdeliamt += (float)str_replace(',', '', $row['delivery_amount']);
                $totdisamt += (float)str_replace(',', '', $row['discounted_total']);
                $totrev += (float)str_replace(',', '', $row['revenue']);
            }
            
            $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
            $objPHPExcel->setActiveSheetIndex(0)
    		->setCellValue($col1, "")
    		->setCellValue($col2, "")
    		->setCellValue($col3, "")
    		->setCellValue($col4, "Total")
    		->setCellValue($col5, number_format($totamount, 2))
    		->setCellValue($col6, number_format($totvat, 2))
    		->setCellValue($col7, number_format($totadamt, 2))
    		->setCellValue($col8, number_format($totdeliamt, 2))
    		->setCellValue($col9, number_format($totdisamt, 2))
    		->setCellValue($col10,number_format($totrev, 2));
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Revenue Report ');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'revenue_report'.$today.'.xls'; 
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
        <span>Revenue Report</span>
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
      			<!-- <div class="panel-heading"><h1>All Product</h1></div>  -->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
  
                	<form method="post" action="#" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="well list-top-controls">
                    <!--  <div class="row border">

                        <div class="col-xs-6 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>

                        <div class="col-xs-6">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div>-->
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Accounting <i class="fa fa-angle-right"></i> Revenue Report </h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">

                            <input type="hidden" name="from_dt" id = "from_dt">
                            <input type="hidden" name="to_dt" id = "to_dt">

                             <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div> 
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_revenue_report_new.php">
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
                    
                    <!--div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group"> <br><br>
                            <label>Summary </label><br>
                            <label >VAT Asseable | <span id = "vatasse"></span> </label><br>
                            <label >VAT Amount | <span id = "vatam"></span> </label><br>
                            <label >Price Incl VAT| <span id = "prinvat"></span> </label><br>
                        </div>          
                    </div-->
                    

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable productList' width="100%">
                        <thead>
                            
							<tr>
								<th>Sl</th>
								<th>Date</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Vat</th>
                                <th>Adjustment Amount</th>
                                <th>Delivery Amount</th>
                                <th>Discounted Total</th>
                                <th>Revenue</th>
                                <!--th>Cost</th>
                                <th>Margin</th-->
							</tr>
                        </thead>
                        
                        <tfoot>
                            <tr class="total" style="background-color: #f5f5f5; color: #094446; font-size: 15px; padding: 10px; font-weight:bold" >
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <!--th></th>
                                <th></th-->
                            </tr>
                        </tfoot>
                        
                        
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
                    { data: 'id', orderable: false },
                    { data: 'date' },
                    { data: 'order_id' },
                    { data: 'customer'},
                    { data: 'amount' },
                    { data: 'vat' },
                    { data: 'adjustment_amount' },
                    { data: 'delivery_amount' },
                    { data: 'discounted_total' },
                    { data: 'revenue' }
                    // { data: 'cost' },
                    // { data: 'margin'}
					
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    $(api.column(3).footer()).html('Total: ');
                    var columnsToTotal = [4, 5, 6, 7, 8, 9]; // Indexes of the columns to total
                
                    columnsToTotal.forEach(function (colIndex) {
                        var colData = api.column(colIndex).data();
                        var total = colData.reduce(function (a, b) {
                            if (b !== null && b !== "") {
                                return a + parseFloat(b.replace(/,/g, ''));
                            }
                            return a;
                        }, 0);
                
                        var formattedTotal = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                        $(api.column(colIndex).footer()).html(formattedTotal);
                    });
                }
				 
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
	url = 'phpajax/datagrid_list_all.php?action=rpt_revenue_new';
	table_with_filter(url);	

	
	
	
	
        //DATE FILTER STARTS	
        $('#filter_date_from').daterangepicker({
            "autoApply": false,
            autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Clear',
        		"fromLabel": "From",
        		"toLabel": "To",		
            },	
        	
             "ranges": {
                "Today": [
        			
                    "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
                    "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z"
                ],
                "Yesterday": [
        			
                    "<?=date("d/m/Y", strtotime("-1 days")); ?>T20:12:21.910Z",
                    "<?=date("d/m/Y", strtotime("-1 days")); ?>T20:12:21.910Z"
                ],
                "Last 7 Days": [
                    "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
                    "<?=date("d/m/Y", strtotime("-7 days")); ?>T20:12:21.910Z"
                ],
                "Last 30 Days": [
                    "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
                    "<?=date("d/m/Y", strtotime("-30 days")); ?>T20:12:21.910Z"
                ],
        		 <?php
        		 //$query_date = date("d/m/Y");
        		 //$firstdayofmonth = date('01/m/Y', strtotime($query_date));
        		 //$lastdayofmonth = date('t/m/Y', strtotime($query_date));
        	
        		 $firstdayofmonth = date('01/m/Y');
        		 $lastdayofmonth = date('t/m/Y');	
        		 ?>
                "This Month": [
                    "<?=$firstdayofmonth?>T18:00:00.000Z",
                    "<?=$lastdayofmonth?>T17:59:59.999Z"
                ],
        		 <?php
        		 
        		 $firstdayoflastmonth = date('d/m/Y', strtotime("first day of previous month"));
        		 $lastdayoflastmonth = date('d/m/Y', strtotime("last day of previous month"));
        		 ?>		 
                "Last Month": [
                    "<?=$firstdayoflastmonth?>T18:00:00.000Z",
                    "<?=$lastdayoflastmonth?>T17:59:59.999Z"
                ]
            },
            "linkedCalendars": false,
            "alwaysShowCalendars": true,
            "startDate": "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
            "endDate": "<?=date("d/m/Y", strtotime("-1 months")); ?>T20:12:21.910Z",
        	maxDate: moment()
        }, function(start, end, label) {
          console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
        	
        	//alert(start.format('YYYY-MM-DD'));
        	if(start<end){
        	url = 'phpajax/datagrid_list_all.php?action=rpt_revenue_new&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	$('#from_dt').val(start.format('YYYY-MM-DD'));
        	$('#to_dt').val(end.format('YYYY-MM-DD'));
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rpt_revenue_new&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
        	$('#from_dt').val(end.format('YYYY-MM-DD'));
        	$('#to_dt').val(start.format('YYYY-MM-DD'));
        	}
        	//alert(url);
        	//setTimeout(function(){
        		table_with_filter(url);
        
        	//}, 350);	
        });
        
        $('#filter_date_from').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });	
        	
        $(".cancelBtn").click(function(){
        	$('#filter_date_from').val("");
        	url = 'phpajax/datagrid_list_all.php?action=rpt_revenue_new';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	

			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script>
        

        
    </body></html>
  <?php }?>    
