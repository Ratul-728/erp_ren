<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

$fdt = $_POST['filter_date_from'];
$tdt = $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("d/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}


if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_revenue_detail';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/hraction.php?res=0&msg='Insert Data'&mod=3");
    }
	//Excel Export
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'Date')
            ->setCellValue('C1', 'Oorder Number')
            ->setCellValue('D1', 'Revenue')
             ->setCellValue('E1', 'Cost')
              ->setCellValue('F1', 'Vat')
               ->setCellValue('G1', 'Ait')
               ->setCellValue('H1', 'Delivery Cost')
                ->setCellValue('I1', 'Margin');

        $firststyle = 'A2';
        $qry        = "select a.invoicedt,a.soid,sum(b.qty*b.otc) revenue,sum(b.cost*b.qty) cost,sum(b.vat)vat,sum(b.ait) ait,c.deliveryamt delivarycost,
                                        sum(COALESCE(((b.qty*b.otc)-(b.cost*b.qty)),0)) margin
                                        from invoice a left join soitem c on a.soid=c.socode left join soitemdetails b on b.socode=c.socode
                                        where  1=1
                                          group by a.invoicedt,a.soid,c.deliveryamt  order by a.id desc";
        // echo  $qry;die;
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {$i = 0;
            while ($row = $result->fetch_assoc()) {
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
                $col10 = 'J' . $urut;
                $col11 = 'K' . $urut;
                $i++;
                
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['invoicedt'])
                    ->setCellValue($col3, $row['soid'])
                    ->setCellValue($col4, $row["revenue"])
                    ->setCellValue($col5, $row["cost"])
                    ->setCellValue($col6, $row["vat"])
                    ->setCellValue($col7, $row["ait"])
                    ->setCellValue($col8, $row["delivarycost"])
                    ->setCellValue($col9, $row["margin"]);

                //$laststyle=$title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Revenue Detail Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'revenue_detail_report' . $today . '.xls';
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
        <span>Revenue Detail Report</span>
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

                	<form method="post" action="rpt_revenue_detail.php?pg=1&mod=3" id="form1">

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
                            <h6>Account <i class="fa fa-angle-right"></i>Revenue Detail Report</h6>
                       </div>



                        <div class="col-sm-9">

                        <div class="pull-right grid-panel form-inline">

                            <!--<div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">
                            </div>


                            <!-- GL Account -->


                            <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div> 
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">
                            </div>
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="form-group exp-wrapper">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
								</ul>								
                            </div>

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
                            <th>Date</th>
                            <th>Order Number</th>
                            <th>Revenue</th>
                            <th>Cost</th>
                            <th>Vat</th>
                            <th>Ait</th>
                            <th>Delivery Cost</th>
                            <th>Margin</th>

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

$(document).ready(function(){			
			
function table_with_filter(url){
	       var ch = 1;
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
                    { data: 'invoicedt' },
                    { data: 'soid' },
					{ data: 'revenue' },
					{ data: 'cost' },
					{ data: 'vat' },
					{ data: 'ait' },
					{ data: 'deliverycost' },
					{ data: 'margin' },
                ],
                
                drawCallback:function(settings)
                {
                    //console.log(settings.json.total);
                    if(ch == 1){
                        setTimeout(function(){
                            
                            var tot1 = settings.json.total[0];
                            var tot2 = settings.json.total[1];
                            var tot3 = settings.json.total[2];
                            var tot4 = settings.json.total[3];
                            var tot5 = settings.json.total[4];
                            var tot6 = settings.json.total[5];


                            var tf = '<tr> <td colspan="2"></td> <td style="color: #00abe3; font-weight:bold" align="right">Total</td> <td style="color: #00abe3; font-weight:bold">'
                            +tot1+' </td><td style="color: #00abe3; font-weight:bold">'+tot2+' </td><td style="color: #00abe3; font-weight:bold">'
                            +tot3+' </td><td style="color: #00abe3; font-weight:bold">'+tot4+' </td><td style="color: #00abe3; font-weight:bold">'
                            +tot5+' </td><td style="color: #00abe3; font-weight:bold">'+tot6+' </td>';

                            $("#listTable").append(
                                $('<tfoot/>').append( tf )
                            );

                        },500);
                        ch++;
                    }


                }
				 
            });
	
			
	
            
            //new $.fn.dataTable.FixedHeader( table1 );
            setTimeout(function(){
			    //table1.columns.adjust().draw();
            }, 350);
            
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })            
            
		}
	
	
	
	//general call on page load
	url = 'phpajax/datagrid_list_all.php?action=rpt_revenue_detail';
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_revenue_detail&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rpt_revenue_detail&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_revenue_detail';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	

			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script>
        
		
		
		<script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
				var fdate = $("#filter_date_from").val();
				var tdate = $("#filter_date_to").val();
				var pdfurl = "pdf_revenue_report.php";
				location.href=pdfurl;
				
			});
			
		</script>


    </body></html>
  <?php } ?>
