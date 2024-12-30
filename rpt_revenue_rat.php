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
    $currSection = 'rpt_revenue';
    $currPage    = basename($_SERVER['PHP_SELF']);

    $fd1 = $_POST['from_dt'];
    $td1 = $_POST['to_dt'];
    if ($fd1 == '') {$fd1 = date("1/m/Y");}
    if ($td1 == '') {$td1 = date("d/m/Y");}

    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D1', 'Bithut.com.bd.')
            ->setCellValue('D2', 'Revenue Report')

            ->setCellValue('A4', 'Sl')
            ->setCellValue('B4', 'SO')
            ->setCellValue('C4', 'Customer')
            ->setCellValue('D4', 'OTC')
            ->setCellValue('E4', 'MRC')
            ->setCellValue('F4', 'Revenue')
            ->setCellValue('G4', 'Income ')
            ->setCellValue('H4', 'Expense')
            ->setCellValue('I4', 'Margin');

        $firststyle = 'A7';
        $mart       = 0;
        $otct       = 0;
        $mrct       = 0;
        $revt       = 0;
        $inct       = 0;
        $costt      = 0;
        $revt       = 0;
        $i          = 0;
        $qry2       = "SELECT s.id,s.socode,o.name organization
,sum(d.qty*d.otc) otc,sum(d.qtymrc*d.mrc) mrc
,(select  sum(`invoiceamt`) inv from invoice where soid=s.socode) rev
,(select  sum(`paidamount`) inv from invoice where soid=s.socode) inc
,(select  sum(`amount`)  from expense where soid=s.id) cost
FROM soitem s left join organization o on s.organization=o.id
 left join soitemdetails d on s.socode=d.socode group by s.id,s.socode,o.name ";
        $result2 = $conn->query($qry2);if ($result2->num_rows > 0) {while ($row2 = $result2->fetch_assoc()) {
            $socode = $row2["socode"];
            $org    = $row2["organization"];
            $otc    = $row2["otc"];
            $mrc    = $row2["mrc"];
            $rev    = $row2["rev"];
            $inc    = $row2["inc"];
            $cost   = $row2["cost"];
            $mar    = $inc - $cost;
            $otct   = $otct + $otc;
            $mrct   = $mrct + $mrc;
            $inct   = $inct + $inc;
            $costt  = $costt + $cost;
            $mart   = $mart + $mar;
            $revt   = $revt + $rev;
            $i++;

            $urut = $i + 5;
            $col1 = 'A' . $urut;
            $col2 = 'B' . $urut;
            $col3 = 'C' . $urut;
            $col4 = 'D' . $urut;
            $col5 = 'E' . $urut;
            $col6 = 'F' . $urut;
            $col7 = 'G' . $urut;
            $col8 = 'H' . $urut;
            $col9 = 'I' . $urut;

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($col1, $i)
                ->setCellValue($col2, $socode)
                ->setCellValue($col3, $org)
                ->setCellValue($col4, $otc)
                ->setCellValue($col5, $mrc)
                ->setCellValue($col6, $rev)
                ->setCellValue($col7, $inc)
                ->setCellValue($col8, $cost)
                ->setCellValue($col9, $mar); /* */
            $laststyle = $title;
        }
            $urut = $i + 6;
            $col3 = 'C' . $urut;
            $col4 = 'D' . $urut;
            $col5 = 'E' . $urut;
            $col6 = 'F' . $urut;
            $col7 = 'G' . $urut;
            $col8 = 'H' . $urut;
            $col9 = 'I' . $urut;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($col3, 'Total')
                ->setCellValue($col4, $otct)
                ->setCellValue($col5, $mrct)
                ->setCellValue($col6, $revt)
                ->setCellValue($col7, $inct)
                ->setCellValue($col8, $costt)
                ->setCellValue($col9, $mart);

        }

        $objPHPExcel->getActiveSheet()->setTitle('REvenue');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'revenue' . $today . '.xls';
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
        <span>Stock</span>
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
      			<!-- <div class="panel-heading"><h1>All Product</h1></div>  -->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="rpt_revenue_rat.php?mod=3&pg=1" id="form1" enctype="multipart/form-data">
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="well list-top-controls">
                    
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Sales <i class="fa fa-angle-right"></i> Revenue VS Income Report </h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">

                            <div class="form-group">
                            
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
								<th>Order Number</th>
                                <th>Customer</th>
                                <th>OTC</th>
                                <th>MRC</th>
                                <th>Revenue</th>
                                <th>Earning</th>
                                <th>Costing</th>
                                <th>Margin</th>

							</tr>
                        </thead>

                        <!--<tfoot id="dtfoot">
                            <tr>
                                <td id="total_label" colspan="8" align="right">Total</td>
                                <td id="total_cost" colspan="1"  ></td>
                                <td id="total_mrp"></td>
                                <td></td>
                            </tr>
                        </tfoot>

                        <tfoot>
                            <tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th>Total</th>
								<th id="total_cost"></th>
								<th></th>
								<th id="total_mrp"></th>
							</tr>
                        </tfoot> -->

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
				scrollX: "100%",
				deferRender: true,
				scroller: true,

				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_report.php?action=rpt_revenue&fd1=<?=$fd1 ?>&td1=<?=$td1 ?>'
                },





                'columns': [
                    { data: 'id', orderable: false },
                    { data: 'socode' },
                    { data: 'organization' },
                    { data: 'otc'},
                    { data: 'mrc' },
                    { data: 'rev' },
                    { data: 'inc' },
                    { data: 'cost' },
                    { data: 'mar', orderable: false },

                ],

                drawCallback:function(settings)
                {
                    //var tot = document.getElementById('total_order');

                   // tot.innerHTML= settings.json.total;
                    //console.log(tot);
                    setTimeout(function(){
                        //$('#total_cost').html(settings.json.total[0]);
                        //$('#total_mrp').html(settings.json.total[1]);
                        var tot1 = settings.json.total[0];
                        var tot2 = settings.json.total[1];
                        var tot3 = settings.json.total[2];
                        var tot4 = settings.json.total[3];
                        var tot5 = settings.json.total[4];
                        var tot6 = settings.json.total[5];


                        var tf = '<tr><td></td><td></td> <td style="color: #00abe3; font-weight:bold">Total</td> <td style="color: #00abe3; font-weight:bold">'+tot1+
                        '</td><td style="color: #00abe3; font-weight:bold">'+tot2+' </td><td style="color: #00abe3; font-weight:bold">'+tot3+
                        '</td><td style="color: #00abe3; font-weight:bold">'+tot4+' </td><td style="color: #00abe3; font-weight:bold">'+tot5+
                        '</td><td style="color: #00abe3; font-weight:bold">'+tot6+' </td>' ;

                        $("#listTable").append(
                            $('<tfoot/>').append( tf )
                        );



                    },500);


                }



            });

             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })




			setTimeout(function(){
				//$('#listTable').DataTable().draw();
			},300);



        });


				//$(window).bind('resize', function () {

				//});

				//jQuery('.dataTable').wrap('');

		function confirmationDelete(anchor)
            {
               var conf = confirm('Are you sure want to delete this record?');
               if(conf)
                  window.location=anchor.attr("href");
            }

        </script>
        <!-- <script>
            $(document).ready(function() {
	$('#lisTtable').dataTable( {
		"processing": true,
		"serverSide": true,
		//"ajax": "/ssp/server_processing.php",
         drawCallback: function () {
        var sum = $('#listTable').DataTable().column(6).data().sum();
       // $('#salary').html(sum);
        alert(sum);
      }
    } );
} );
        </script>

        <script>
            setTimeout(function(){
                var table = document.getElementById("listTable"), sumVal = 0;

            for(var i = 1; i < table.rows.length; i++)
            {
                sumVal = sumVal + parseInt(table.rows[i].cells[8].innerHTML);
            }

            document.getElementById("hlo").innerHTML = "Sum Value = " + sumVal;
            console.log(sumVal);
            //},5000);



        </script> -->
        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
				var pdfurl = "pdf_revenuevsincome_report.php";
				location.href=pdfurl;
				
			});
			
		</script>


    </body></html>
  <?php } ?>
