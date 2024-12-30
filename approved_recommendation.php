<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];


if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'approved_recommendation';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        //header("Location: " . $hostpath . "/requisition.php?res=0&msg='Insert Data'&mod=4");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'QUOTATION')
            ->setCellValue('C1', 'RFQ')
            ->setCellValue('D1', 'VENDOR')
            ->setCellValue('E1', 'PRODUCT')
            ->setCellValue('F1', 'ORDER QTY')
            ->setCellValue('G1', 'VENDOR QTY')
            ->setCellValue('H1', 'PRICE')
            ->setCellValue('I1', 'RECOMMENDATION BY');

        $firststyle = 'A2';
        $qry        = "SELECT ra.id, a.`quotation`, r.rfq, org.name, i.name product, a.`order_qty`, a.`offered_qty`, a.`item_spec`, a.`quated_price`, ra.recommendation, concat(emp.firstname, ' ', emp.lastname) emp, ra.st
        
                                FROM rfq_authorisation ra LEFT JOIN `rfq_vendor` a ON ra.rfq_vendor = a.id LEFT JOIN rfq_details r ON a.`rfq` = r.id LEFT JOIN  organization org ON org.id = a.`vendor_id` LEFT JOIN rfq rf ON rf.rfq=r.rfq LEFT JOIN employee emp ON ra.`recommender`= emp.id
                                LEFT JOIN item i ON i.id = r.product
                                
                                WHERE ra.st=1 
                                
                                order by ra.`id` desc";
        //echo  $qry;die;
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {$i = 0;
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
                $col9 = 'I' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['quotation'])
                    ->setCellValue($col3, $row['rfq'])
                    ->setCellValue($col4, $row['name'])
                    ->setCellValue($col5, $row['product'])
                    ->setCellValue($col6, $row['order_qty'])
                    ->setCellValue($col7, $row['offered_qty'])
                    ->setCellValue($col8, $row['quated_price'])
                    ->setCellValue($col9, $row['emp']);
                /* */
                //$laststyle=$title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('APPROVED RECOMMENDATION');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'approved_recommendation' . $today . '.xls';
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
        <span>Approved Recommendation</span>
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
      		<!--	<div class="panel-heading"><h1>All Action Type</h1></div> -->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="approved_recommendation.php?mod=14" id="form1">

                     <div class="well list-top-controls">
                      <!--<div class="row border">

                        <div class="col-xs-6 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <!--div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div-->
                        <!--<div class="col-xs-6">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div> -->
                       <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Procurement <i class="fa fa-angle-right"></i>All Approved Recommendation</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">
                            </div>
                            <!--<div class="form-group">
                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            </div>-->
                            <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                            <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
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
                            <th>Quotation</th>
                            <th>RFQ</th>
                            <th>Vendor</th>
                            <th>Product</th>
                            <th>Order Quantity</th>
                            <th>Vendor Quantity</th>
                            <!--th>Vendor Specification</th-->
                            <th>Qoutate Price</th>
                            <!--th>Recommendation</th-->
                            <th>Recommendation By</th>
                            <th>Action</th>

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
				order: [0, 'desc'],
				"dom": "rtiplf",
				/*'searching': true,*/
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=approved_recommendation'
                },
                'columns': [
                    { data: 'sl' },
                    { data: 'quotation' },
                    { data: 'rfq' },
                    { data: 'vendor' },
                    { data: 'product' },
					{ data: 'order_qty'},
					{ data: 'offered_qty'},
					//{ data: 'item_spec'},
					{ data: 'quated_price'},
					//{ data: 'recommendation'},
					{ data: 'emp'},
					{ data: 'actbtn'},
					/*{ data: 'edit', "orderable": false },
					{ data: 'del', "orderable": false },*/
                ]
            });

            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);

             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
        });



        </script>


    </body></html>
  <?php } ?>
