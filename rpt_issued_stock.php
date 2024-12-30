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
    $currSection = 'rpt_issued_stock';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/hraction.php?res=0&msg='Insert Data'&mod=3");
    }
	//Excel Export
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'Category')
            ->setCellValue('C1', 'Product Name')
            ->setCellValue('D1', 'Free Quantity')
             ->setCellValue('E1', 'Cost Price')
              ->setCellValue('F1', 'Mrp')
               ->setCellValue('G1', 'Branch')
               ->setCellValue('H1', 'Barcode');

        $firststyle = 'A2';
        $qry        = "SELECT s.id,t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode barcode 
                                        FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id 
                                        LEFT JOIN branch r ON s.storerome=r.id  
                                        where  r.id = 6 and s.freeqty<>0";
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
                    ->setCellValue($col2, $row['tn'])
                    ->setCellValue($col3, $row['pn'])
                    ->setCellValue($col4, $row["freeqty"])
                    ->setCellValue($col5, $row["costprice"])
                    ->setCellValue($col6, $row["mrp"])
                    ->setCellValue($col7, $row["str"])
                    ->setCellValue($col8, $row["barcode"]);

                //$laststyle=$title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Issued Stock Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'issued_stock_report' . $today . '.xls';
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
        <span>Issued Stock Report</span>
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

                	<form method="post" action="rpt_issued_stock.php?pg=1&mod=12" id="form1">

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
                            <h6>Billing <i class="fa fa-angle-right"></i>Issued Stock Report</h6>
                       </div>



                        <div class="col-sm-9">

                        <div class="pull-right grid-panel form-inline">

                            <!--<div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">
                            </div>


                            <!-- GL Account -->


                            <!--div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter datepicker" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt; ?>" >
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter datepicker" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt; ?>"  >
                            </div-->
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
                            <th>Category</th>
                            <th>Product Name</th>
                            <th>Free Quantity</th>
                            <th>Cost Price</th>
                            <th>MRP</th>
                            <th>Branch</th>
                            <th>Barcode</th>

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
            var ch = 1;
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
				/*'searching': true,*/
				"order": [[ 0, "desc" ]],
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=rpt_issued_stock'
                },
                'columns': [
                    { data: 'id' },
                    { data: 'tn' },
                    { data: 'pn' },
					{ data: 'freeqty' },
					{ data: 'costprice' },
					{ data: 'mrp' },
					{ data: 'str' },
					{ data: 'barcode' },
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


                            var tf = '<tr> <td colspan="8"></td> <td style="color: #00abe3; font-weight:bold" align="right">Total</td> <td style="color: #00abe3; font-weight:bold">'
                            +tot1+' </td><td style="color: #00abe3; font-weight:bold">';

                            $("#listTable").append(
                                //$('<tfoot/>').append( tf )
                            );

                        },500);
                        ch++;
                    }


                }
            });

            setTimeout(function(){
                table1.columns.adjust().draw();
            }, 350);

             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
        });



        </script>
		
		
		<script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
			
				var pdfurl = "pdf_issued_stock_report.php";
				location.href=pdfurl;
				
			});
			
		</script>


    </body></html>
  <?php } ?>
