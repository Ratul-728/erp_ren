<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET["res"];
$msg = $_GET["msg"];

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'challan';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/challan.php?res=0&msg='Insert Data'&mod=12");
    }
    if (isset($_POST['export'])) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'ID')
            ->setCellValue('C1', 'CHALLAN NO')
            ->setCellValue('D1', 'ADVICE NO')
            ->setCellValue('E1', 'ORDER DATE ')
            ->setCellValue('F1', 'ORDERED AMOUNT')
            ->setCellValue('G1', 'INVOICEAMOUNT')
            ->setCellValue('H1', 'DELIVERY DATE');
        $firststyle = 'A2';
        $qry        = "SELECT  p.`id`,p.`poid`,p.`adviceno`, DATE_FORMAT( p.`orderdt`,'%e/%c/%Y') `orderdt`, p.`tot_amount`, p.`invoice_amount`
        ,DATE_FORMAT( p.`delivery_dt`,'%e/%c/%Y') `delivery_dt` FROM `po` p order by p.`id`";
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
                $col8 = 'H' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['id'])
                    ->setCellValue($col3, $row['poid'])
                    ->setCellValue($col4, $row['adviceno'])
                    ->setCellValue($col5, $row['orderdt'])
                    ->setCellValue($col6, $row['tot_amount'])
                    ->setCellValue($col7, $row['invoice_amount'])
                    ->setCellValue($col8, $row['delivery_dt']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('CHALLAN');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'challan_' . $today . '.xls';
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
        <span>All PO</span>
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

    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="challanList.php" id="form1">

                     <div class="well list-top-controls">
                     <div class="row border">
                           <div class="col-sm-1 text-nowrap lg-text">
                            <h6> Inventory <i class="fa fa-angle-right"></i> All Stock Order </h6>
                       </div>

                        <div class="col-sm-11 text-nowrap">
                        <div class="pull-right grid-panel form-inline">
                                <div class="form-group">
                            <input type="search " id="search-dttable" class="form-control mini-issue-search">
                            </div>

                            <div class="form-group">

                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default" ><i class="fa fa-plus"></i></button>
                            </div>
                             <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                             <button type="submit" title="export" name="export" id="export" class="form-control btn btn-default" ><i class="fa fa-download"></i></button>
                            </div>




                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                        </div>
                          <!--   <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div> -->
                      </div>
                    </div>
                    </div>


    				</form>


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div>
                    <!-- Table -->
                    <table id="listTable" class="display dataTable actionbtn" width="100%">
                        <thead>
                        <tr>
                            <th>Created</th>
                            <th>Ref NO</th>
                            <th>Stock In No</th>
							<th style="width: 50px;" align="center">Total Item</th>
<!--                            <th>Order Date</th>-->
<!--                            <th>Total Amount</th>-->
                            <th>Received Date </th>
							<th class="actioncol"><div><span>View</span> <span class="action-divider">|</span> <span>Edit</span> <span class="action-divider">|</span>  <span>Barcode</span></div></th>

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
				"dom": "rtiplf",
				"order": [[ 0, "desc" ]],
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=challan'
                },
			 columnDefs: [
				{
					targets: 3,
					className: 'dt-body-center'
				}],				
                'columns': [
                    { data: 'makedt','bVisible': false },
                    { data: 'adviceno' },
//                    { data: 'poid' },
                    { data: 'poid',
					'render': function (poid) {
						return '<span class="rowid_'+ poid +'">' + poid +'</span>'
						}
					},						
					 { data: 'noi' , 'orderable':false},
                    //{ data: 'orderdt' },
                    //{ data: 'tot_amount' },
                    { data: 'delivery_dt' },
					//{ data: 'edit', "orderable": false  },
					//{ data: 'cedit', "orderable": false  },
					//{ data: 'cret', "orderable": false  },
					//{ data: 'bc', "orderable": false  }
					{ data: 'action_buttons', 'orderable':false},
                ]
            });
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
            setTimeout(function(){
				$('#listTable').DataTable().draw();
			},300);
        });


			function confirmationDelete(anchor)
            {
               swal({
                   title: "Are you sure?",
          text: "You will not be able to recover this file!",
          icon: "warning",
          buttons: [
            'No, cancel it!',
            'Yes, I am sure!'
          ],

        }).then(function(isConfirm) {
          if (isConfirm) {

             swal("Deleted", "Successfully deleted!", "success");
              window.location= anchor.attr("href");

          } else {
            swal("Cancelled", "Product isn't deleted!", "error");
          }
        });

            }

        </script>
        </script>

        <!-- Alert -->
        <?php
if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>

    </body></html>
  <?php } ?>
