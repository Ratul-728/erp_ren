<?php
require "common/conn.php";

$pgcnt   = $_GET['pg'];
$limitst = ($pgcnt - 1) * 150;
$limitnd = 150;
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
    $currSection = 'target';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/target.php?res=0&msg='Insert Data'&mod=5");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'ACCOUNT MANAGER')
            ->setCellValue('C1', 'YEAR')
            ->setCellValue('D1', 'MONTH')
            ->setCellValue('E1', 'CATAGORY')
            ->setCellValue('F1', 'ITEM')
            ->setCellValue('G1', 'TARGET')
            ->setCellValue('H1', 'ACHIEVMENT');

        $firststyle = 'A2';
        $qry        = "SELECT a.`id`, a.`yr`, a.`mnth`, b.hrName `accmgr`, c.name `itmcatagory`, d.name `item`,a.`target`, a.`achivement` FROM `salestarget` a,`hr` b,`itmCat` c,`item` d
            WHERE a.`accmgr`=b.`id` and a.`itmcatagory`=c.`id` and a.`item`=d.`id`";
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
                $col12 = 'L' . $urut;
                $col13 = 'M' . $urut;
                $col14 = 'N' . $urut;
                $col15 = 'O' . $urut;
                $col16 = 'P' . $urut;
                $col17 = 'Q' . $urut;
                $col18 = 'R' . $urut;
                $col19 = 'S' . $urut;
                $col20 = 'T' . $urut;
                $col21 = 'U' . $urut;
                $col22 = 'V' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['accmgr'])
                    ->setCellValue($col3, $row['yr'])
                    ->setCellValue($col4, $row['mnth'])
                    ->setCellValue($col5, $row['itmcatagory'])
                    ->setCellValue($col6, $row['item'])
                    ->setCellValue($col7, $row['target'])
                    ->setCellValue($col8, $row['achivement']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('TARGET');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'target_' . $today . '.xls';
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
        <span>All Target</span>
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
      			<!-- <div class="panel-heading"><h1>All Target</h1></div> -->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="targetList.php" id="form1">

                     <div class="well list-top-controls">
                      <div class="row border">
                         <div class="col-sm-1 text-nowrap lg-text">
                            <h6> Settings <i class="fa fa-angle-right"></i> All Target</h6>
                       </div>
                       <!-- <div class="col-sm-11 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>  -->
                          <div class="pull-right grid-panel form-inline">


                            <div class="form-group">

                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default" ><i class="fa fa-plus"></i></button>
                            </div>
                             <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                             <button type="submit" title="export" name="export" id="export" class="form-control btn btn-default" ><i class="fa fa-download"></i></button>
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
                            <!--<th>Picture</th> -->
                            <th>Account Maneger</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Catagory</th>
                            <th>Item</th>
                            <th>Target</th>
                            <th>Achievement</th>
                            <th></th>
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
            $('#listTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=target'
                },
                "dom": "rtiplf",
                'columns': [
                   // { data: 'photo' },
                    { data: 'accmgr' },
                    { data: 'yr' },
                    { data: 'mnth' },
					{ data: 'itmcatagory' },
					{ data: 'item' },
                    { data: 'target' },
					{ data: 'achivement' },
					{ data: 'edit' }
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

    </body></html>
  <?php } ?>
