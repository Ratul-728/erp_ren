<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";

    $currSection = 'rpt_user_sales';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Sl.')
            ->setCellValue('B1', 'Invoice No')
            ->setCellValue('C1', 'Invoice Date')
            ->setCellValue('D1', 'Customer Code')
            ->setCellValue('E1', 'Customer')
            ->setCellValue('F1', 'Total Amount')
            ->setCellValue('G1', 'Paid Amount')
            ->setCellValue('H1', 'Due Amount')
            ->setCellValue('I1', 'Sales Person');

        $firststyle = 'A2';
        $qry = "select i.invoiceno, DATE_FORMAT(i.`invoicedt`,'%d/%b/%Y') invoicedt,o.orgcode,o.name customer,i.amount_bdt ,i.paidamount,i.dueamount,h.hrName slperson
                              from invoice i join quotation q on i.soid=q.socode left join organization o on  i.organization=o.id left join hr h on  i.makeby=h.id AND q.orderstatus IN(7,8)";
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
                    ->setCellValue($col2, $row['invoiceno'])
                    ->setCellValue($col3, $row['invoicedt'])
                    ->setCellValue($col4, $row['orgcode'])
                    ->setCellValue($col5, $row['customer'])
                    ->setCellValue($col6, $row['amount_bdt'])
                    ->setCellValue($col7, $row['paidamount'])
                    ->setCellValue($col8, $row['dueamount'])
                    ->setCellValue($col9, $row['slperson']);
                $laststyle = $title;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('User Sales Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'rpt_user_sales' . $today . '.xls';
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
        <span>User Sales Report</span>
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

                	<form method="post" action="rpt_user_sales.php?mod=3" id="form1">
                         <div class="well list-top-controls">
                                  <div class="row border">

                                      <!--div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div-->
                                      <div class="col-sm-4">
                                          <div class="col-lg-12 text-nowrap">
                            <h6>Sales <i class="fa fa-angle-right"></i> User Sales Report</h6>
                       </div>
                                      </div>
                                      <div class="col-sm-7 col-lg-8 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbemp" id="cmbemp" class="form-control" >
                                            <option value="0">All Employee</option>
    <?php
$qry1    = "SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id`, e.id eid FROM `hr` h,`employee` e where h.`emp_id`=e.`employeecode` and h.id != 1 order by emp_id";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["emp_id"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($ibrand == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div> 
                          	
                            
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control">
                            </div>
                            <div class="form-group">

                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >

                            </div>
                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_user_sales.php">
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
                            <th>Invoice No</th>
                            <th>Invoice Date</th>
                            <th>Customer Code</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                            <th>Sales Person</th>
                            
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
                    { data: 'invoiceno'},
                    { data: 'invoicedt' },
                    { data: 'orgcode' },
                    { data: 'customer' },
                    { data: 'amount_bdt' },
                    { data: 'paidamount' },
                    { data: 'dueamount' },
                    { data: 'slperson' },
                    
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
	url = 'phpajax/datagrid_rpt_user_sales.php?action=rpt_user_sales&emp=0';
	table_with_filter(url);	
	
	//Status
        $("#cmbemp").on("change", function() {

            
            var emp = $('#cmbemp').val();
            
            url = 'phpajax/datagrid_rpt_user_sales.php?action=rpt_user_sales&emp='+emp;
			
			//alert(status);
			
            
			
            setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
	    var emp = $('#cmbemp').val();
	    var empquery = ($('#cmbemp').val())?'&emp='+emp:'&emp=0';
	
	
	
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

        	url = 'phpajax/datagrid_rpt_user_sales.php?action=rpt_user_sales&fgno=<?= $fglno ?>&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD')+empquery;
            $('#from_dt').val(start.format('YYYY-MM-DD'));
        	$('#to_dt').val(end.format('YYYY-MM-DD'));
        	}

        	else

        	{

        	url = 'phpajax/datagrid_rpt_user_sales.php?action=rpt_user_sales&fgno=<?= $fglno ?>&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD')+empquery;
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

        	url = 'phpajax/datagrid_rpt_user_sales.php?action=rpt_user_sales&fgno=<?= $fglno ?>';

        	table_with_filter(url);

        });

        	

        //ENDS DATE FILTER START	


	
	
	
	
	
	
});
		
</script> 
        
        
    <script>
		//convert pdf trigger;
			
			$("#exportpdfx").on("click",function(){
				var emp = $('#cmbemp').val();
                
				var pdfurl = 'pdf_user_sales.php?emp='+emp;
				location.href=pdfurl;
				
			});
			
		</script>

    </body></html>
  <?php } ?>
