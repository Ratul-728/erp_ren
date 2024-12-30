<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {

    // $store = $_POST["cmbsupnm"];
    $branch  = $_POST["cmbbranch"]; if($branch == '') $branch = 0;
    $brand  = $_POST["cmbbrand"]; if($brand == '') $brand = 0;
    $cat = $_POST["cmbcat"]; if($cat == '') $cat = 0;
    $barcode = $_POST["bc"];

    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_issue_locwise_stock';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['export'])) {
        //echo $fd1; die;
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D1', 'Bithut.com.bd.')
            ->setCellValue('D2', 'Location Wise  Issue Stock Report')
            ->setCellValue('A4', 'Sl')
            ->setCellValue('B4', 'Category')
            ->setCellValue('C4', 'Brand')
            ->setCellValue('D4', 'Product')
            ->setCellValue('E4', 'Barcode')
            ->setCellValue('F4', 'Stock Type')
            ->setCellValue('G4', 'Store')
            ->setCellValue('H4', 'Quantity')
            ->setCellValue('I4', 'Price Including VAT')
            ->setCellValue('J4', 'Total')
            ->setCellValue('K4', 'Request By')
            ->setCellValue('L4', 'Request Date')
            ->setCellValue('M4', 'Approved By')
            ->setCellValue('N4', 'Approved Date');

//->setCellValue('G4', 'Cost Rate')
//->setCellValue('H4', 'Cost Price')
        $firststyle = 'A7';
        $qry        = "select i.id,t.name catnm,b.title brand,p.image,p.name prod,p.barcode,'Issue' tp,r.name issueloc,id.qty,p.rate, DATE_FORMAT( i.makedt,'%d/%b/%Y') requestdt,
                                concat(emp.firstname, ' ', emp.lastname) requestby,concat(emp1.firstname, ' ', emp1.lastname) approvedby,DATE_FORMAT( i.approvedt,'%d/%b/%Y') approvedt
                            from issue_order i join issue_order_details id on i.id=id.ioid  left join item p on p.id=id.product LEFT JOIN itmCat t ON p.catagory=t.id
                            LEFT JOIN issue_warehouse r ON i.issue_warehouse=r.id  LEFT JOIN brand b ON b.id=p.brand LEFT JOIN hr h ON h.id=i.makeby LEFT JOIN employee emp ON emp.employeecode=h.emp_id
                                LEFT JOIN hr h1 ON h1.id=i.approved_by LEFT JOIN employee emp1 ON emp1.employeecode=h1.emp_id
                                where (p.barcode='".$bc1."'  or '".$bc1."'='' or p.name like '%".$bc1."%' or p.barcode like '%".$bc1."%' ) and ( r.id = ".$branch." or ".$branch." = 0 )
                                and ( t.id = ".$cat." or ".$cat." = 0 ) and ( b.id = ".$brand." or ".$brand." = 0 ) and id.qty<>0 order by p.name asc";
                                
        // echo  $qry;die;
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {
            $i = 0;
            $tcp                          = 0;
            $tmp                          = 0;
            while ($row2 = $result->fetch_assoc()) {
                $tnm=$row2["catnm"]; $prod=$row2["prod"];$str=$row2["issueloc"]; $br=$row2["brand"];  
            $freeqty=$row2["qty"]; $cup=0; $mup=$row2["rate"]; $bc=$row2["barcode"];
            
            //$cp=$freeqty*$cup;
            $mp=$freeqty*$mup; 
            //$tcp=$tcp+$cp;
            $tmp=$tmp+$mp;

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
                
                $col11 = 'K' . $urut;
                $col12 = 'L' . $urut;
                $col13 = 'M' . $urut;
                $col14 = 'N' . $urut;
                
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $tnm)
                    ->setCellValue($col3, $br)
                    ->setCellValue($col4, $prod)
                    ->setCellValue($col5, $bc)
                    ->setCellValue($col6, $storetype)
                    ->setCellValue($col7, $str)
                    ->setCellValue($col8, number_format($freeqty, 0))
                    ->setCellValue($col9, number_format($mup, 2))
                    ->setCellValue($col10, number_format($mp, 2))
                    
                    ->setCellValue($col11, $row2["requestby"])
                    ->setCellValue($col12, $row2["requestdt"])
                    ->setCellValue($col13, $row2["approvedby"])
                    ->setCellValue($col14, $row2["approvedt"]);
                    /*->setCellValue($col9, number_format($mup, 2))
                    ->setCellValue($col10, number_format($mp, 2));  */
                $laststyle = $title;
            }

        }

        $objPHPExcel->getActiveSheet()->setTitle('Issue Location Wise Stock');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'issuelocation_wise_stock_report' . $today . '.xls';
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

                	<form method="post" action="rpt_issue_locwise_stock.php?mod=12" id="form1" enctype="multipart/form-data">
                        <!-- START PLACING YOUR CONTENT HERE -->

                        <div class="well list-top-controls">
                
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Inventory <i class="fa fa-angle-right"></i> Issue Stock Report </h6>
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
                                        <select name="cmbbrand" id="cmbbrand" class="form-control" >
                                            <option value="0">Brand</option>
    <?php
$qry1    = "SELECT `id`, title `name`  FROM `brand` order by title";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($brand == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            
                            <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbbranch" id="cmbbranch" class="form-control" >
                                            <option value="0">Issue Location</option>
    <?php
$qry1    = "SELECT id,name FROM issue_warehouse order by name";
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
                            <div class="form-group" id = "exportTable">
                                
                            </div>
                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_issue_locwise_stock.php">
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

<style>
    .dt-buttons{
        position: absolute;
        border: 1px solid red;
        text-indent: -99999px;
        height: 0;
    }
</style>
<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<!-- DataTables Buttons CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">


                <div >
                    <!-- Table -->
                    <table id='listTable' class=' table table-bordered table-hover dt-responsive' width="100%">
                        <thead>

							<tr>
							    <th>SL.</th>
								<th>Catagory</th>
								<th>Brand</th>
								<th>Image</th>
                                <th>Product </th>
                                <th>Barcode </th>
                                <th>Stock Type</th>
                                <th>Store </th>
                                <th> Qty </th>
                                <!--th>Cost Rate</th>
                                <th>Cost Price</th-->
                                <th>Rate including VAT</th>
                                <th> Total</th>
                                <th>Request By</th>
                                <th>Request Date</th>
                                <th>Approved By</th>
                                <th>Approved Date</th>
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
				"dom": "rtiplf",
                "order": [[ 0, "desc" ]],
                'ajax': {
                    
					'url':url,
                },
                
				'columns': [
                    { data: 'id', orderable: false  },
                    { data: 'tn'},
                    { data: 'brand'},
                    { data: 'image' },
                    { data: 'pn' },
                    { data: 'barcode'},
                    { data: 'storetype'},
                    { data: 'str'},
                    { data: 'freeqty' },
                   // { data: 'costprice'},
                    //{ data: 'totalcp', orderable: false },
                    { data: 'mrp'},
                    { data: 'totalmrp', orderable: false },
                    { data: 'requestby', orderable: false },
                    { data: 'requestdt', orderable: false },
                    { data: 'approvedby', orderable: false },
                    { data: 'approvedt', orderable: false },

                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    $(api.column(8).footer()).html('Total: ');
                    var columnsToTotal = [9, 10]; // Indexes of the columns to total
                
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
            
            // Bind export to Excel button click event
            // $('#exportexcel').click(function(){
            //     table1.buttons.exportData({ format: 'excel' }).download('file', 'xlsx');
            // });
            
            //new $.fn.dataTable.FixedHeader( table1 );
            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
            
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })            
            
		}
	
	
	//general call on page load 
	url = 'phpajax/datagrid_list_all.php?action=rpt_issueloc_stock';
	table_with_filter(url);	
    
    //Status
    $("#cmbbranch,#cmbcat,#bc,#cmbbrand").on("change input", function() {

            
            var branch = $('#cmbbranch').val();
            var brand = $('#cmbbrand').val();
            var bc = $('#bc').val();
            var cat = $('#cmbcat').val();
            
            var url = 'phpajax/datagrid_list_all.php?action=rpt_issueloc_stock&branch='+branch+'&barcode='+bc+'&cat='+cat+'&brand='+brand;
			
			 setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        

    </body></html>
  <?php } ?>
