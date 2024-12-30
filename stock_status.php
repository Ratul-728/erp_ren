<?php

require "common/conn.php";



session_start();

$usr = $_SESSION["user"];





if ($usr == '') {header("Location: " . $hostpath . "/hr.php");

} else {

    require_once "common/PHPExcel.php";

    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

     */

    $currSection = 'inv_stock';

    $currPage    = basename($_SERVER['PHP_SELF']);



    if (isset($_POST['add'])) {$prv = $_GET['up'];

        header("Location: " . $hostpath . "/challan.php?res=0&mod=12");

    }

    if (isset($_POST['export'])) {



        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)

            ->setCellValue('D1', 'Bitflow')

            ->setCellValue('D2', 'Stock Report..')

            ->setCellValue('A4', 'SL.')

            ->setCellValue('B4', 'PRODUCT CODE')

            ->setCellValue('C4', 'PRODUCT')

            ->setCellValue('D4', 'PRODUCT TYPE')

            ->setCellValue('E4', 'FREE QUANTITY')

            ->setCellValue('F4', 'COST PRICE')

            ->setCellValue('G4', 'MRP');



        $firststyle = 'A2';

        $qry        = "SELECT s.id,p.id pid,p.code,p.image, s.product,p.name prod,t.name typ, s.freeqty, s.bookqty, s.costprice,p.rate  FROM stock s left join item p on s.product=p.id

        left join itemtype t on p.catagory=t.id";

        // echo  $qry;die;

        $result = $conn->query($qry);

        if ($result->num_rows > 0) {$i = 3;

            while ($row = $result->fetch_assoc()) {

                $urut = $i + 2;

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

                    ->setCellValue($col2, $row['code'])

                    ->setCellValue($col3, $row['prod'])

                    ->setCellValue($col4, $row['typ'])

                    ->setCellValue($col5, $row['freeqty'])

                    ->setCellValue($col6, $row['costprice'])

                    ->setCellValue($col7, $row['rate']); /* */

                $laststyle = $title;

            }

        }

        $objPHPExcel->getActiveSheet()->setTitle('STOCK REPORT');

        $objPHPExcel->setActiveSheetIndex(0);

        $today     = date("YmdHis");

        $fileNm    = "data/" . 'stock_report' . $today . '.xls';

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

        <span>All Product</span>

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

      			<!--<div class="panel-heading"><h1>All Product Inventory</h1></div> -->

    				<div class="panel-body">



    <span class="alertmsg">

    </span>



                	<form method="post" action="inv_stock.php?mod=12" id="form1">



                     <div class="well list-top-controls">

                      <div class="row border">

                           <div class="col-sm-1 text-nowrap lg-text">

                            <h6>Inventory <i class="fa fa-angle-right"></i> Product wise Stock Status</h6>

                       </div>



                        <div class="col-sm-11 text-nowrap">

                             <div class="pull-right grid-panel form-inline">

                                <div class="form-group">

                            <input type="search " id="search-dttable" class="form-control mini-issue-search">

                            </div>



                            <div class="form-group">



                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>

                            </div>

                             <div class="form-group">

                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->

                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
								</ul>

                            </div>









                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->

                        </div>

                        </div>

                        <!-- <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>

                        <div class="col-sm-1">

                         <!-- <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">-->

                        <!--</div> -->

                      </div>

                    </div>





    				</form>





<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>



                <div >

                    <!-- Table -->
<style>
	.btn{ background-color: auto;}					
</style>
                    <table id="listTable" class="display actionbtn no-footer dataTable" width="100%">

                        <thead>

                        <tr>

                           

                            <th width="10">Picture</th>

                            <th>Code</th>
                            <th>Item Name </th>
							<th width="20">Qty Avail. </th>
                            <th width="20">Ordered Qty</th>
							<th width="20">Backordered Qty</th>
							<th width="20">Booked Qty</th>
							<th width="20">Delivered Qty</th>
							<th width="20">Issued Qty</th>

                            

<!--                            <th>Cost Price</th>-->

<!--                            <th>Price</th>-->

                            <!--th>Discount </th-->

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



     <!-- Datatable JS -->

		<script src="js/plugins/datagrid/datatables.min.js"></script>



        <!-- Script -->

        <script>

        $(document).ready(function(){

            var prv= '<?=$prv ?>';

           var table1= $('#listTable').DataTable({

                processing: true,

				responsive: true,

				fixedHeader: true,

                serverSide: true,

                serverMethod: 'post',

				pageLength: 100,

				scrollX: true,

				bScrollInfinite: true,

				bScrollCollapse: true,

				/*scrollY: 550,*/

				deferRender: true,

				scroller: true,
			   "order": [[ 3, "asc" ]],

				"dom": "rtiplf",

                'ajax': {

                    'url':'phpajax/datagrid_stockstatus.php?action=stock_status'

                },

                'columns': [

                  //   { data: 'id' },

                    { data: 'image',orderable:false },
                    { data: 'productcode' },
                    { data: 'prod' },
					{ data: 'freeqty' },
					{ data: 'orderedqty' },
					{ data: 'backordered' },
					{ data: 'bookqty' },
					{ data: 'deliveredqty' },
					{ data: 'issuedqty' },

                    

                    //{ data: 'costprice' },

                   // { data: 'mrp' },

                    //{ data: 'discount' }

                ],
			columnDefs: [
				{
					targets: [3,4,5,6,7],
					className: 'dt-body-center'
				}
			  ]			   

            });

             $('#search-dttable').keyup(function(){

                  table1.search($(this).val()).draw() ;

            })



			setTimeout(function(){

				$('#listTable').DataTable().draw();

			},300);





        });



        </script>
        
        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
				var pdfurl = "pdf_stock_status.php";
				location.href=pdfurl;
				
			});
			
		</script>



    </body></html>

  <?php } ?>

