<?php
require "common/conn.php";
require "rak_framework/misfuncs.php";
require "common/user_btn_access.php";

session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    
    /*
    SELECT c.glno,c.glnm,m.yr,m.mn,m.closingbal
FROM `coa` c,`coa_mon` m where c.glno=m.glno and  substr(c.`glno`,1,1)=4 and c.lvl=3 and (( m.yr='2022' and  m.mn>'06') or (m.yr='2023' and m.mn<'07'))
order by c.`glno`,m.yr,m.mn;
    */
    require_once "common/PHPExcel.php";
    //common codes need to place every page. Just change the section name according to section
    //these 2 variables required to detecting current section and current page to use in menu.

    $currSection = 'rpt_expense';
    include_once('common/inc_session_privilege.php');
    $currPage    = basename($_SERVER['PHP_SELF']);

    // if ( isset( $_POST['view'] ) ) {
    //header("Location: ".$hostpath."/rpt_invoice_payment.php?res=0&msg='Insert Data'");
    $tdt = $_POST['tdt'];if ($tdt == '') {$tdt = date('d/m/Y');}
    $fdt = $_POST['fdt'];if ($fdt == '') {$fdt = date('d/m/Y', strtotime('-1 month'));}

    //}
    $ajxurl = "phpajax/datagrid_list_all.php?action=rpt_expense&fdt=" . $fdt . "&tdt=" . $tdt;
    //echo $ajxurl;
    if (isset($_POST['export'])) {
        
          $fd= $_POST['from_dt'];

          if($fd!=''){ $fdquery=" and e.trdt >='$fd'";}
    
          $td= $_POST['to_dt'];
    
          if($td!=''){ $tdquery=" and e.trdt <='$td'";}

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Sl.')
            ->setCellValue('B1', 'Trans Date')
            ->setCellValue('C1', 'Trans Mode')
            ->setCellValue('D1', 'Trans Type')
            ->setCellValue('E1', 'Amount')
            ->setCellValue('F1', 'Remarks');

        $firststyle = 'A2';
        $qry        = "SELECT  e.id,date_format(e.trdt,'%d/%b/%Y') trdt,t.name transmode,p.name transtype,e.amount,e.naration 
                                FROM expense e left join transmode t on e.transmode=t.id left join transtype p on e.transtype=p.id where 1=1 ".$fdquery.$tdquery." order by e.trdt desc";
        // echo  $qry;die;
        //s.`socode`='ANTGR003' and

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
                $col12 = 'L' . $urut;
                $i++;
                $mnt = date("F", strtotime($row['invoicemonth']));
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['trdt'])
                    ->setCellValue($col3, $row['transmode'])
                    ->setCellValue($col4, $row['transtype'])
                    ->setCellValue($col5, number_format($row['amount'],2))
                    ->setCellValue($col6, $row['naration']);
                $laststyle = $title;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('Expense Transaction Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'expense_transaction' . $today . '.xls';
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
            <div class="col-lg-12 col-xs-11">

            <p>&nbsp;</p>
            <p>&nbsp;</p>

              <!--h1 class="page-title">Customers</a></h1-->
              <p>
              <!-- START PLACING YOUR CONTENT HERE -->


              <div class="panel panel-info">
      			<!-- <div class="panel-heading"><h1 class="left-align">All Expenses </h1></div> -->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="rpt_expense.php?mod=3" id="form1">
                         <div class="well list-top-controls">
                                  <div class="row border">

                                      <!--div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div-->
                                      <div class="col-sm-4">
                                          <div class="col-lg-12 text-nowrap">
                            <h6>Accounting <i class="fa fa-angle-right"></i> Expense</h6>
                       </div>
                                      </div>
                                      <div class="col-sm-7 col-lg-8 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            <input type="hidden" name="from_dt" id = "from_dt">
                            <input type="hidden" name="to_dt" id = "to_dt">
                          	
                            <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div>
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control">
                            </div>
                            
                                <div class="form-group">
                                    <input type="hidden" id="pdfsource" url="pdf_expense_transection.php">
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


					<table id="listTable" class="table display dataTable no-footer actionbtns" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;">

					<!--table id="listTable" class="display dataTable no-footer actio nbtn" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;"-->
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Trans Date</th>
                            <th>Trans MOde</th>
                            <th>Trans Type</th>
                            <th>Amount</th>
                            <th>Remarks</th>
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

    <div id = "divBackground" style="position: fixed; z-index: 999; height: 100%; width: 100%; top: 0; left:0; background-color: Black; filter: alpha(opacity=60); opacity: 0.6; -moz-opacity: 0.8;display:none">

    </div>
    <!-- /#page-content-wrapper -->

    <?php
include_once 'common_footer.php';
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
                    { data: 'trdt' },
                    { data: 'transmode' },
                    { data: 'transtype' },
                    { data: 'amount' },
                    { data: 'naration' }
                ]
				 
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
	url = 'phpajax/datagrid_list_all.php?action=rpt_expense';
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
            	url = 'phpajax/datagrid_list_all.php?action=rpt_expense&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
            	$('#from_dt').val(start.format('YYYY-MM-DD'));
            	$('#to_dt').val(end.format('YYYY-MM-DD'));
        	}
        	else
        	{
            	url = 'phpajax/datagrid_list_all.php?action=rpt_expense&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_expense';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	

			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        
        <script type="text/javascript">

    function openpopup(popurl){
       var popUpObj;
    popUpObj=window.open(popurl,"ModalPopUp","toolbar=no," +"scrollbars=no," + "location=no," + "statusbar=no," + "menubar=no," + "resizable=0," + "modal=yes,"+
    "width=400," +"height=310," + "left = 290," +"top=200"  );
    popUpObj.focus();
    //LoadModalDiv();


    }
    </script>

    </body></html>
  <?php } ?>
