<?php
session_start();
require "common/conn.php";
require "rak_framework/misfuncs.php";
require "common/user_btn_access.php";

//fini_set("display_errors",1);



$usr = $_SESSION["user"];





if ($usr == '') {header("Location: " . $hostpath . "/hr.php");

} else {

    require_once "common/PHPExcel.php";

    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

     */

    $currSection = 'rpt_customer';
	// load session privilege;
	include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);


    $fd1 = $_POST['from_dt'];

    $td1 = $_POST['to_dt'];

    if ($fd1 == '') {$fd1 = date("1/m/Y");}

    if ($td1 == '') {$td1 = date("d/m/Y");}



    $branch = $_POST["cmbsupnm"];



    if (isset($_POST['export'])) {

        //echo $fd1;

        



        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)

            ->setCellValue('D1', 'Bithut.com.bd.')

            ->setCellValue('D2', 'Customer Report')

            ->setCellValue('D3', 'Date   ' . $fd1 . '   To   ' . $td1 . '')

            ->setCellValue('A4', 'Sl')

            ->setCellValue('B4', 'Customer')

            ->setCellValue('C4', 'Revenue')

            ->setCellValue('D4', 'Cost')

            ->setCellValue('E4', 'Vat')

            ->setCellValue('F4', 'Ait')

            ->setCellValue('G4', 'Delivery Cost ')

            ->setCellValue('H4', 'Margin');



        $firststyle = 'A7';

        $sl = 1; $i =0;

        $qry2       = "select o.name,sum(b.qty*b.otc) revenue,sum(b.cost*b.qty) cost,sum(b.vat)vat,sum(b.ait) ait,c.deliveryamt deliverycost,sum(COALESCE(((b.qty*b.otc)-(b.cost*b.qty)),0)) margin

                                from invoice a left join soitem c on a.soid=c.socode left join soitemdetails b on b.socode=c.socode join organization o on  c.organization=o.id

                                WHERE 1=1 

                                group by  o.name";

        $result2 = $conn->query($qry2);if ($result2->num_rows > 0) {while ($row2 = $result2->fetch_assoc()) {

            $name = $row2["name"];

            $revenue = $row2["revenue"];

            $cost   = $row2["cost"];

            $vat   = $row2["vat"];

            $ait   = $row2["ait"];

            $deliverycost   = $row2["deliverycost"];

            $margin   = $row2["margin"];

            

            $urut = $i + 5;

            $col1 = 'A' . $urut;

            $col2 = 'B' . $urut;

            $col3 = 'C' . $urut;

            $col4 = 'D' . $urut;

            $col5 = 'E' . $urut;

            $col6 = 'F' . $urut;

            $col7 = 'G' . $urut;

            $col8 = 'H' . $urut;

            $i++;



            $objPHPExcel->setActiveSheetIndex(0)

                ->setCellValue($col1, $i)

                ->setCellValue($col2, $name)

                ->setCellValue($col3, $revenue)

                ->setCellValue($col4, $cost)

                ->setCellValue($col5, $vat)

                ->setCellValue($col6, $ait)

                ->setCellValue($col7, $deliverycost)

                ->setCellValue($col8, $margin); /* */

            $laststyle = $title;

        }



        }



        $objPHPExcel->getActiveSheet()->setTitle('Customer');

        $objPHPExcel->setActiveSheetIndex(0);

        $today     = date("YmdHis");

        $fileNm    = "data/" . 'rpt_customer' . $today . '.xls';

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

        <span>REPORT</span>

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

      			<!-- <div class="panel-heading"><h1>Cash Flow</h1></div> -->

    				<div class="panel-body">



    <span class="alertmsg">

    </span>



                	<form method="post" action="rpt_customer.php?mod=3&pg=1" id="form1" enctype="multipart/form-data">

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

                            <h6>Sales <i class="fa fa-angle-right"></i> Customer Report </h6>

                       </div>







                        <div class="col-sm-9 text-nowrap">



                        <div class="pull-right grid-panel form-inline">







                             <div class="form-group">

                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >

                            </div> 

                            <div class="form-group">

                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">

                            </div>



                            <div class="form-group">

                            

                            <!--button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>

								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">

									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>

									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>

								</ul-->
								<span id="pdfsource" url="pdf_customer_report.php"></span>
								<?=getBtn('export')?>

							</div>



                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->

                        </div>



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

								<th>Customer</th>

                                <th>Revenue</th>

                                <!--th>Cost</th-->

                                <th>Vat</th>

                                <th>Ait</th>

                                <th>Delivery Cost</th>

                                <th>Selling Amount</th>



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

                    { data: 'id', orderable: false },

                    { data: 'name' },

                    { data: 'revenue' },

                    //{ data: 'cost'},

                    { data: 'vat' },

                    { data: 'ait'},

                    { data: 'deliverycost'},

                    { data: 'total'},

                    



                ],
				footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    $(api.column(1).footer()).html('Total: ');
                    var columnsToTotal = [2,3,4,5,6]; // Indexes of the columns to total
                
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

	url = 'phpajax/datagrid_report.php?action=rpt_customer';

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

        	url = 'phpajax/datagrid_report.php?action=rpt_customer&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');

        	}

        	else

        	{

        	url = 'phpajax/datagrid_report.php?action=rpt_customer&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');

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

        	url = 'phpajax/datagrid_report.php?action=rpt_customer';

        	table_with_filter(url);

        });

        	

        //ENDS DATE FILTER START	



			

			

			

        }); //$(document).ready(function(){	

		

		

		

        </script> 

        



        



    </body></html>

  <?php } ?>

