<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];



if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {

    // $store = $_POST["cmbsupnm"];
    $branch  = $_POST["cmbbranch"];
    $barcode = $_POST["bc"];
    $cat = $_POST["cmbcat"];

    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_store_stock';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['export'])) {
        //echo $fd1; die;
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D1', 'Bithut.com.bd.')
            ->setCellValue('D2', 'Store Wise Stock Report')
            ->setCellValue('A4', 'Sl')
            ->setCellValue('B4', 'Type')
            ->setCellValue('C4', 'Product')
            ->setCellValue('D4', 'Barcode')
            ->setCellValue('E4', 'Store')
            ->setCellValue('F4', 'Quantity')
            ->setCellValue('G4', 'Cost Rate')
            ->setCellValue('H4', 'Cost Price')
            ->setCellValue('I4', 'MRP')
            ->setCellValue('J4', 'MRP Price');

        $firststyle = 'A7';
        $qry        = "SELECT s.id,t.name tn,p.name pn,s.freeqty,s.costprice,p.mrp,r.name str,p.barcode FROM chalanstock s ,product p ,
                                itemtype t ,branch r where s.product = p.id
                                and p.catagory=t.id and s.storerome=r.id
                                 and (p.barcode='" . $bc1 . "' or '" . $bc1 . "'='') and ( r.id = " . $branch . " or " . $branch . " = 0 ) and s.companyid=$com and s.freeqty<>0";
        // echo  $qry;die;
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {$i = 0;
            $tcp                          = 0;
            $tmp                          = 0;
            while ($row2 = $result->fetch_assoc()) {
                $tnm     = $row2["tn"];
                $prod    = $row2["pn"];
                $str     = $row2["str"];
                $freeqty = $row2["freeqty"];
                $cup     = $row2["costprice"];
                $mup     = $row2["mrp"];
                $bc      = $row2["barcode"];
                $cp      = $freeqty * $cup;
                $mp      = $freeqty * $mup;
                $tcp     = $tcp + $cp;
                $tmp     = $tmp + $mp;

                $urut  = $i + 5;
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
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $tnm)
                    ->setCellValue($col3, $prod)
                    ->setCellValue($col4, $bc)
                    ->setCellValue($col5, $str)
                    ->setCellValue($col6, number_format($freeqty, 0))
                    ->setCellValue($col7, number_format($cup, 2))
                    ->setCellValue($col8, number_format($cp, 2))
                    ->setCellValue($col9, number_format($mup, 2))
                    ->setCellValue($col10, number_format($mp, 2)); /* */
                $laststyle = $title;
            }
            $urut = $i + 6;
            $col3 = 'G' . $urut;
            $col4 = 'H' . $urut;
            $col5 = 'J' . $urut;
            $objPHPExcel->setActiveSheetIndex(0)

                ->setCellValue($col3, 'Total')
                ->setCellValue($col4, number_format($tcp, 2))
                ->setCellValue($col5, number_format($tmp, 2));

        }

        $objPHPExcel->getActiveSheet()->setTitle('Store Wise Stock Report ');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'store_stock' . $today . '.xls';
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
        <span>Store Wise Stock Report</span>
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

                	<form method="post" action="rpt_storewise_stock_old.php?mod=12" id="form1" enctype="multipart/form-data">
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="button-bar">
                            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label for="po_dt">Order Date*</label>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Date From</div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">

                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="from_dt" id="from_dt" value="<?php echo $fd1; ?>"  required>


                                </div>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Date To</div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">

                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="to_dt" name="to_dt"  value="<?php echo $td1; ?>" required>

                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbsupnm" id="cmbsupnm" class="form-control" >
                                            <option value="0">Store </option>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `storeroom` order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($store == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbbranch" id="cmbbranch" class="form-control" >
                                            <option value="0">Store</option>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `branch` where status = 'A' order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($branch == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbcat" id="cmbcat" class="form-control" >
                                            <option value="0">Category</option>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `itmCat` order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($cat == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">

                                <div class="no-mg-btn input-group">
                                    <input type="text" class="no-mg-btn form-control" id="bc" name="bc" placeholder="Bar Code" value="<?php echo $barcode; ?>"  >

                                </div>
                            </div>
                            <button dat a-to="pagetop" class="no-mg-btn btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"><i class="fa fa-search"></i></button>
                            <button dat a-to="pagetop" class="no-mg-btn btn btn-lg btn-default top" type="submit" name="export" value="Export" id="export" ><i class="fa fa-download"></i></button>
                            <button class="no-mg-btn btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')"><i class="fa fa-print"></i></button>
                            <!--<input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  >
                           <!-- <div class="form-group">
                            <button type="submit" title="export" name="export" id="export" class="form-control btn btn-default"><svg class="svg-inline--fa fa-download fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="download" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M216 0h80c13.3 0 24 10.7 24 24v168h87.7c17.8 0 26.7 21.5 14.1 34.1L269.7 378.3c-7.5 7.5-19.8 7.5-27.3 0L90.1 226.1c-12.6-12.6-3.7-34.1 14.1-34.1H192V24c0-13.3 10.7-24 24-24zm296 376v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h146.7l49 49c20.1 20.1 52.5 20.1 72.6 0l49-49H488c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z"></path></svg></button>
                            </div> -->
                            <!--<input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="export" value="Export" id="export">
                             <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')">  -->
                        </div>


                    </form>


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table id='listTable' class=' table table-bordered table-hover dt-responsive' width="100%">
                        <thead>

							<tr>
							    <th>SL.</th>
								<th>Category</th>
                                <th>Product </th>
                                <th>Barcode </th>
                                <th>Store </th>
                                <th> Qty </th>
                                <th>Cost Rate</th>
                                <th>Cost Price</th>
                                <th>MRP</th>
                                <th>MRP Total</th>
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


		    var ch = 1;

			var prv= '<?=$prv ?>';
            var table1 = $('#listTable').DataTable({
                processing: true,
				responsive: true,
                deferRender: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 50,
				/*scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,
				scrollX: "100%",
				deferRender: true,
				scroller: true, */

				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=rpt_storewise_stock&barcode=<?=$barcode ?>&cat=<?=$cat ?>&branch=<?=$branch ?>&prv='+prv
                },





                'columns': [
                    { data: 'id', orderable: false  },
                    { data: 'tn'},
                    { data: 'pn' },
                    { data: 'barcode'},
                    { data: 'str'},
                    { data: 'freeqty' },
                    { data: 'costprice'},
                    { data: 'totalcp', orderable: false },
                    { data: 'mrp'},
                    { data: 'totalmrp', orderable: false }

                ],

                drawCallback:function(settings)
                {
                    //var tot = document.getElementById('total_order');

                   // tot.innerHTML= settings.json.total;
                    //console.log(tot);
                    if(ch == 1){
                        setTimeout(function(){
                            //$('#total_cost').html(settings.json.total[0]);
                            //$('#total_mrp').html(settings.json.total[1]);
                            var tot1 = settings.json.total[0];
                            var tot2 = settings.json.total[1];


                            var tf = '<tr> <td colspan="6"></td> <td style="color: #00abe3; font-weight:bold" align="right">Total</td> <td style="color: #00abe3; font-weight:bold">'
                            +tot1+' </td><td></td><td style="color: #00abe3; font-weight:bold">'+tot2+' </td>';

                            $("#listTable").append(
                                $('<tfoot/>').append( tf )
                            );

                        },500);
                        ch++;
                    }


                }



            });

             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })

            setTimeout(function(){
				$('#listTable').DataTable().draw();
			},300);

        });


				//$(window).bind('resize', function () {

				//});


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


    </body></html>
  <?php } ?>
