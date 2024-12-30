<?php

require "common/conn.php";

require "rak_framework/misfuncs.php";

require "common/user_btn_access.php";





session_start();

$usr = $_SESSION["user"];



if ($usr == '') {header("Location: " . $hostpath . "/hr.php");

} else {

    require_once "common/PHPExcel.php";

    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

     */

    $currSection = 'rpt_cash_flow';

    include_once('common/inc_session_privilege.php');

    $currPage    = basename($_SERVER['PHP_SELF']);



    $fd1 = $_POST['from_dt'];

    $td1 = $_POST['to_dt'];

    if ($fd1 == '') {$fd1 = date("1/m/Y");}

    if ($td1 == '') {$td1 = date("d/m/Y");}



    $branch = $_POST["cmbsupnm"];



    if ( isset( $_POST['export'] ) ) {
        $fdt = $_POST["from_dt"];
        $tdt = $_POST["to_dt"];
        
        if($fdt != ''){
            $date_qry = " and invoicedt between DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d') ";
            $date_qry2 = "where invoicedt < STR_TO_DATE('".$fdt."','%Y-%m-%d')";
            $date_qry3 = "and trdt between DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d')";
            $date_qry4 = "where trdt < STR_TO_DATE('".$fdt."','%Y-%m-%d')";
        }else{
            $date_qry = "";
            $date_qry2 = "";
            $date_qry3 = "";
            $date_qry4 = "";
        }
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Date')
                ->setCellValue('C1', 'Narration')
                ->setCellValue('D1', 'Debit')
    			->setCellValue('E1', 'Credit')
    			->setCellValue('F1', 'Balance');
    			
        $firststyle='A2';
        
        $bal=0;$i=0;$bf=0;$totdr=0;$totcr=0;$net=0;
        //echo $fd;die;
        $qry0="select sum(paidamount) dra from invoice $date_qry2";
        $qry1="select sum(amount) cra from expense $date_qry4";
        //echo $qry1;die;
        $result0 = $conn->query($qry0);
        $row0 = $result0->fetch_assoc();
        $d=$row0["dra"];
        //echo $d;die;
        $result1 = $conn->query($qry1);
        $row1 = $result1->fetch_assoc();
        $c=$row1["cra"];
        $bal=$d-$c;
        
        $qry="select date_format(trdt, '%m/%d/%Y') trdt,narr,incm dr,expns cr
                                FROM
                                (
                                    SELECT `invoicedt` trdt,`paidamount` incm,0 expns,concat(soid,'-',invoiceno) narr 
                                    FROM invoice where 1=1 $date_qry
                                    union all 
                                    select trdt  trdt,0 incm,amount expns,naration narr from expense where 1=1 $date_qry3
                                ) u";
        
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;$tcp=0;$tmp=0;
            while($row2 = $result->fetch_assoc()) 
            {
                $trdt=$row2["trdt"];$narr=$row2["narr"]; $dr=$row2["dr"]; $cr=$row2["cr"];  
                $bal=$bal+$dr-$cr;
            
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $i++;
                
                // Format date as Excel date value
                $dateValue = PHPExcel_Shared_Date::PHPToExcel(strtotime($row2['trdt']));


                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $dateValue)
    						->setCellValue($col3, $narr)
    					    ->setCellValue($col4, number_format($dr,2))
    					    ->setCellValue($col5, number_format($cr,2))
    					    ->setCellValue($col6, number_format($bal,2));	/* */
    					    
    			// Apply date format to column B
                $objPHPExcel->getActiveSheet()
                ->getStyle($col2)
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
                
    			$laststyle=$title;	
            }
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Expired  Stock Report ');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'cash_flow_report'.$today.'.xls'; 
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

      			<!-- <div class="panel-heading"><h1>Cash Flow</h1></div> -->

    				<div class="panel-body">



    <span class="alertmsg">

    </span>



                	<form method="post" action="#" id="form1" enctype="multipart/form-data">

                        <!-- START PLACING YOUR CONTENT HERE -->

                        <div class="well list-top-controls">

                                  <div class="row border">



                                        <div class="col-sm-3 col-lg-2 text-nowrap">

                                                <h6>Accounting <i class="fa fa-angle-right"></i>Cash Flow</h6>



            							</div>




            			<div class="col-sm-7 col-lg-10 text-nowrap">



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
                            
                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
								</ul>
							</div>



                        </div>



                                  </div>

                                </div>

                    </form>







<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>



                <div >

                    <!-- Table -->

                    <table id='listTable' class='display dataTable productList' width="100%">

                        <thead>



							<tr>

								<th>Sl</th>

								<th>Date </th>

                                <th>Narration</th>

                                <th>Debit</th>

                                <th>Credit</th>

                                <th>Balance</th>



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

                    { data: 'trdt' },

                    { data: 'narr' },

                    { data: 'dr'},

                    { data: 'cr' },

                    { data: 'balance', orderable: false },



                ],



                footerCallback: function (row, data, start, end, display) {

                    var api = this.api();

                    $(api.column(2).footer()).html('Total: ');

                    var columnsToTotal = [3, 4, 5]; // Indexes of the columns to total

                

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

	url = 'phpajax/datagrid_report.php?action=rpt_cash_flow';

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

        	url = 'phpajax/datagrid_report.php?action=rpt_cash_flow&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	$('#from_dt').val(start.format('YYYY-MM-DD'));
        	$('#to_dt').val(end.format('YYYY-MM-DD'));

        	}

        	else

        	{

        	url = 'phpajax/datagrid_report.php?action=rpt_cash_flow&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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

        	url = 'phpajax/datagrid_report.php?action=rpt_cash_flow';

        	table_with_filter(url);

        });

        	

        //ENDS DATE FILTER START	



			

			

			

        }); //$(document).ready(function(){	

		

		

		

        </script> 
        
        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
                var sdt = $('#from_dt').val();
        	    var edt = $('#to_dt').val();
            
				var pdfurl = 'pdf_cash_flow_report_rat.php?dt_f='+sdt+'&dt_t='+edt;
				location.href=pdfurl;
				
			});
			
			
		</script>

        



    </body></html>

  <?php } ?>

