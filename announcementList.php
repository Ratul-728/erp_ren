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
    $currSection = 'announce';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/announcement.php?res=0&msg='Insert Data'&mod=6");
    }
    //if ( isset( $_POST['announce'] ) ) {
    //      header("Location: ".$hostpath."/announcement.php?res=0&msg='Insert Data'&mod=6");
    //}
    //if ( isset( $_POST['sms'] ) ) {
    //       header("Location: ".$hostpath."/sms.php?res=0&msg='Insert Data'&mod=6");
    //}
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'ANNOUNCEMENT NO')
            ->setCellValue('C1', 'CATAGORY')
            ->setCellValue('D1', 'DATE')
            ->setCellValue('E1', 'SUBJECT')
            ->setCellValue('F1', 'ANNOUNCEMENT')
            ->setCellValue('G1', 'ORGANIZATION');

        $firststyle = 'A2';
        $qry        = "SELECT an.id, an.announceid,c.name catagory,date_format(an.announcedt,'%d/%m/%y') announcedt,an.title,an.announce,o.name organization FROM announce an left join announcecatagory c on an.catagory=c.id left join organization o on an.organization=o.id order by t.id";
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
                    ->setCellValue($col2, $row['announceid'])
                    ->setCellValue($col3, $row['catagory'])
                    ->setCellValue($col4, $row['announcedt'])
                    ->setCellValue($col5, $row['title'])
                    ->setCellValue($col6, $row['announce'])
                    ->setCellValue($col7, $row['organization']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ISSUE');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'issue_' . $today . '.xls';
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
        <span>All Issue</span>
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
      			<!--<div class="panel-heading"><h1>All Announcement</h1></div>-->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="announcementList.php" id="form1">

                     <div class="well list-top-controls">
                      <!--<div class="row border">

                        <div class="col-sm-11 text-nowrap">
                            <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-default">
                             <!--<input class="btn btn-default" type="submit" name="announce" value=" Announcement" id="announce"  >
                             <input class="btn btn-default" type="submit" name="sms" value=" Send SMS" id="sms"  >-->
                    <!--    </div>

                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input class="btn btn-md btn-info   pull-right responsive-alignment-r2l" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                      </div> -->
                        <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Issues <i class="fa fa-angle-right"></i>All Announcement</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            <!--<div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">
                            </div> -->
                              <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">
                            </div>
                            <div class="form-group">
                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            </div>
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
                    <table id='listTable' class=' table table-bordered table-hover dt-responsive dataTable' width="100%">
                        <thead>
                        <tr>
                            <th>Announcement ID</th>
                            <th>Catagory </th>
                            <th>Date</th>
                            <th>Subject</th>
                            <th>Details</th>
                            <th>Organization </th>
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
            var table1 = $('#listTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                "dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=announce'
                },
                'columns': [
                    { data: 'announceid' },
                    { data: 'catagory' },
                    { data: 'announcedt' },
                    { data: 'title' },
					{ data: 'announce' },
                    { data: 'organization' },
					{ data: 'edit' }
                ]
            });

             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })

        });

        </script>

    </body></html>
  <?php } ?>
