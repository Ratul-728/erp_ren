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
    $currSection = 'delagent';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/deliveryagent.php?res=0&msg='Insert Data'&mod=13");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'ID')
            ->setCellValue('C1', 'AGENT NAME')
            ->setCellValue('D1', 'ADDRESS')
            ->setCellValue('E1', 'CONTACT NO')
            ->setCellValue('F1', 'EMAIL')
            ->setCellValue('G1', 'DESCRIPTION');

        $firststyle = 'A2';
        $qry        = "SELECT `id`, `name`, `address`, `contactno`, `email`, `narration` FROM `deveryagent` order by id";
        // echo  $qry;die;
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
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['id'])
                    ->setCellValue($col3, $row['name'])
                    ->setCellValue($col4, $row['address'])
                    ->setCellValue($col5, $row['contactno'])
                    ->setCellValue($col6, $row['email'])
                    ->setCellValue($col7, $row['narration']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('DELIVERY_AGENT');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'agent' . $today . '.xls';
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
        <span>All Agent</span>
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
      			<div class="panel-heading"><h1 class="left-align">Delivery Agents</h1></div>
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="delagentList.php" id="form1">

                     <div class="well list-top-controls">
                      <div class="row border">
                          <div class="col-lg-4"></div>
                       <div class="col-lg-8">
                        <!-- <div class="col-sm-1 col-lg-1 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1 col-lg-2">
                          <input type="submit" name="add" value="+ Create New Agent " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div> -->
                        <div class="pull-right grid-panel form-inline">
                           <!-- <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">
                            </div> -->
                            <div class="form-group">
                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                            <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
                            </div>
                        </div>
                      </div>
                      </div>
                    </div>


    				</form>


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                             <th>Sl</th>
                            <th>Agent</th>
                            <th>Address</th>
                            <th>Contact </th>
                            <th>Email</th>
                            <th>Description</th>
                            <th>Edit</th>
                            <th>Delete</th>
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
             var table1 = $('#listTable').DataTable({
                processing: true,
				responsive: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 50,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,
                'dom': 'rtiplf',
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=delagent'
                },
                'columns': [
                     { data: 'sl' },
                    { data: 'name' },
                    { data: 'address' },
                    { data: 'contactno' },
                    { data: 'email' },
                    { data: 'narration' },
                    { data: 'edit' , orderable: false, className: "btncol"},
                    { data: 'del' , orderable: false, className: "btncol"}
                ]
            });



			setTimeout(function(){
				$('#listTable').DataTable().draw();
			},300);

             $('#search-dttable').keyup(
                 function(){
                     table1.search($(this).val()).draw();
                     //setTimeout(function(){ putClass(); }, 300);

             })

        });


			function confirmationDelete(anchor)
            {
               var conf = confirm('Are you sure want to delete this record?');
               if(conf)
                  window.location=anchor.attr("href");
            }

        </script>

    </body></html>
  <?php } ?>
