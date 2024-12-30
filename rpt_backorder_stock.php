<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"]; 



if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {

    // $store = $_POST["cmbsupnm"];
    $branch  = $_POST["cmbbranch"];
    $barcode = $_POST["bc"];

    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_backorder_stock';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['export'])) {
        //echo $fd1; die;
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D1', 'Bithut.com.bd.')
            ->setCellValue('D2', 'Backorder Stock Report')
            ->setCellValue('A4', 'Sl')
            ->setCellValue('B4', 'Type')
            ->setCellValue('C4', 'Product')
            ->setCellValue('D4', 'Barcode')
            ->setCellValue('E4', 'Store')
            ->setCellValue('F4', 'Quantity')
            ->setCellValue('G4', 'Price Including VAT')
            ->setCellValue('H4', 'Total');

//->setCellValue('G4', 'Cost Rate')
//->setCellValue('H4', 'Cost Price')
        $firststyle = 'A7';
        $qry        = "SELECT s.id,t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode barcode 
                                FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id  
                                where s.freeqty<>0 and storerome=8";
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
               // $col9  = 'I' . $urut;
                //$col10 = 'J' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $tnm)
                    ->setCellValue($col3, $prod)
                    ->setCellValue($col4, $bc)
                    ->setCellValue($col5, $str)
                    ->setCellValue($col6, number_format($freeqty, 0))
                    ->setCellValue($col7, number_format($cup, 2))
                    ->setCellValue($col8, number_format($cp, 2));
                    /*->setCellValue($col9, number_format($mup, 2))
                    ->setCellValue($col10, number_format($mp, 2));  */
                $laststyle = $title;
            }
            $urut = $i + 6;
            $col3 = 'G' . $urut;
            $col4 = 'H' . $urut;
           // $col5 = 'J' . $urut;
            $objPHPExcel->setActiveSheetIndex(0)

                ->setCellValue($col3, 'Total')
                ->setCellValue($col4, number_format($tcp, 2));
               // ->setCellValue($col5, number_format($tmp, 2));

        }

        $objPHPExcel->getActiveSheet()->setTitle('Store Wise Stock Report ');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'backorder_stock' . $today . '.xls';
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
        <span>INVENTORY</span>
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

                	<form method="post" action="rpt_backorder_stock.php?mod=12" id="form1" enctype="multipart/form-data">
                        <!-- START PLACING YOUR CONTENT HERE -->

                        <div class="well list-top-controls">
                
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Inventory <i class="fa fa-angle-right"></i> Backorder Report </h6>
                       </div>

                        

                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            
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
                            
                            <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbbranch" id="cmbbranch" class="form-control" >
                                            <option value="0">Store</option>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `branch` where status = 'A' and id=8 order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($branch == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            
                            <div class="form-group">
                                    <input type="text" class="no-mg-btn form-control" id="bc" name="bc" placeholder="Bar Code" value="<?php echo $barcode; ?>"  >

                                </div>
                                
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_backorder_stock.php">
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


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table id='listTable' class=' table table-bordered table-hover dt-responsive' width="100%">
                        <thead>

							<tr>
							    <th>SL.</th>
								<th>Catagory</th>
								<th>Image</th>
                                <th>Product </th>
                                <th>Barcode </th>
                                <th>Stock Type</th>
                                <th>Store </th>
                                <th> Qty </th>
                                <!--th>Cost Rate</th>
                                <th>Cost Price</th-->
                                <th>Price including VAT</th>
                                <th> Total</th>
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
                    { data: 'id', orderable: false  },
                    { data: 'tn'},
                    { data: 'photo'},
                    { data: 'pn' },
                    { data: 'barcode'},
                    { data: 'storetype'},
                    { data: 'str'},
                    { data: 'freeqty' },
                   // { data: 'costprice'},
                    //{ data: 'totalcp', orderable: false },
                    { data: 'mrp'},
                    { data: 'totalmrp', orderable: false }

                ],

                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    $(api.column(6).footer()).html('Total: ');
                    var columnsToTotal = [7, 8, 9]; // Indexes of the columns to total
                
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
	url = 'phpajax/datagrid_list_all.php?action=rpt_backorder_stock';
	table_with_filter(url);	
    
    //Status
    $("#cmbbranch,#cmbcat,#bc").on("change", function() {

            
            var branch = $('#cmbbranch').val();
            var bc = $('#bc').val();
            var cat = $('#cmbcat').val();
            
            var url = 'phpajax/datagrid_list_all.php?action=rpt_backorder_stock&branch='+branch+'&barcode='+bc+'&cat='+cat;
			
			 setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        


    </body></html>
  <?php } ?>
