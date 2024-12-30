<?php
require "common/conn.php";
require "rak_framework/misfuncs.php";
require "common/user_btn_access.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

$fdt = $_POST['filter_date_from'];
//$tdt= $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("d/m/Y");}
//if($tdt==''){$tdt=date("d/m/Y");}

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_sales';
    include_once('common/inc_session_privilege.php');
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/hraction.php?res=0&msg='Insert Data'&mod=4");
    }
    if (isset($_POST['export'])) {
        
        $fdt = $_POST["from_dt"];
        $tdt = $_POST["to_dt"];
        if($fdt != ""){
          $date_qry = "AND q.orderdate BETWEEN '$fdt' AND '$tdt'";
        }else{
          $date_qry = "";
        }
        

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'Order ID')
            ->setCellValue('C1', 'Order Date')
            ->setCellValue('D1', 'Customer ID')
            ->setCellValue('E1', 'Customer Name')
            ->setCellValue('F1', 'Sales Amount');

        $firststyle = 'A2';
        $qry        = "select DATE_FORMAT( q.orderdate,'%m/%d/%Y') orderdate, q.socode,o.orgcode,o.name orgname,sum(qd.discounttot) salesamount
                                from quotation q join quotation_detail qd on q.socode=qd.socode left join organization o on q.organization=o.id
                                where 1=1 $date_qry group by q.orderdate,q.socode,o.orgcode,o.name";
        // echo  $qry;die;
         //and (a.transdt   between   date_format(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'01/%m/%y')  and STR_TO_DATE('".$tdt."','%d/%m/%Y'))
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {$i = 0; $optot = 0;
            while ($row = $result->fetch_assoc()) 
            {
                $optot += $row['salesamount'];
                $urut  = $i + 2;
                $col1  = 'A' . $urut;
                $col2  = 'B' . $urut;
                $col3  = 'C' . $urut;
                $col4  = 'D' . $urut;
                $col5  = 'E' . $urut;
                $col6  = 'F' . $urut;
                $col7  = 'G' . $urut;
                $col8  = 'H' . $urut;
                $col9  = 'I' . $urut;
               
                $i++;
                
                // Format date as Excel date value
                $dateValue = PHPExcel_Shared_Date::PHPToExcel(strtotime($row['orderdate']));
                
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $dateValue)
                    ->setCellValue($col3, $row['socode'])
                    ->setCellValue($col4, $row['orgcode'])
                    ->setCellValue($col5, $row['orgname'])
                    ->setCellValue($col6, $row['salesamount'])
                    ;
                    
                // Apply date format to column B
                $objPHPExcel->getActiveSheet()
                ->getStyle($col2)
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);

                //$laststyle=$title;
            }
               // $cltot=$optot+$drtot-$crtot;
                $urut  = $i + 2;
                $col2  = 'B' . $urut;
                $col3  = 'C' . $urut;
                $col4  = 'D' . $urut;
                $col5  = 'E' . $urut;
                $col6  = 'F' . $urut;
            
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($col5, 'Total')
            ->setCellValue($col6, $optot); 
        }
        $objPHPExcel->getActiveSheet()->setTitle('Sales Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'rpt_sales' . $today . '.xls';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileNm);
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
include_once 'common_header.php';
    ?>

    <body class="list">

    <?php
include_once 'common_top_body.php';
    ?>
    <div id="wrapper">

      <!-- Sidebar -->

      <div id="sidebar-wrapper" class="mCustomScrollbar">

      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>ACCOUNTING</span>
      </div>

    <?php
include_once 'menu.php';
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
      			<!--<div class="panel-heading"><h1>All Hr Action</h1></div>-->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="rpt_sales.php?pg=1&mod=7" id="form1">

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
                            <h6>Accounting <i class="fa fa-angle-right"></i>Sales Report</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">

                            <input type="hidden" name="from_dt" id = "from_dt">
                            <input type="hidden" name="to_dt" id = "to_dt">

                            <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div>
                            <!--div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt; ?>"  >
                            </div-->
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">
                            </div>
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_rpt_sales.php">
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


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable actionbtn firstcolpad0' width="100%">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Customer Id</th>
                            <th>Customer Name</th>
                            <th>Sales Amount</th>
                            
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
include_once 'common_footer.php';
    ?>
    <?php

    if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }

    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>

     <!-- Datatable JS -->
		<script src="js/plugins/datagrid/datatables.min.js"></script>

        <!-- Script -->
        <script>
        
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
                    { data: 'socode' },
                    { data: 'orderdate' },
                    { data: 'orgcode' },
                    { data: 'orgname' },
                    { data: 'salesamount' },
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    $(api.column(4).footer()).html('Total: ');
                    var columnsToTotal = [5]; // Indexes of the columns to total
                
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
	url = 'phpajax/datagrid_list_all.php?action=rpt_sales';
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_sales&dt_f='+start.format('DD/MM/YYYY')+'&dt_t='+end.format('DD/MM/YYYY');
        	$('#from_dt').val(start.format('YYYY-MM-DD'));
        	$('#to_dt').val(end.format('YYYY-MM-DD'));
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rpt_sales&dt_f='+end.format('DD/MM/YYYY')+'&dt_t='+start.format('DD/MM/YYYY');
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_sales&dt_f=<?php echo $fdt; ?>&dt_f=<?php echo $fdt; ?>';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START

        </script>
        
        

    </body></html>
  <?php } ?>
