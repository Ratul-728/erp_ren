<?php

require "common/conn.php";
require "common/user_btn_access.php";



session_start();

$usr = $_SESSION["user"];





if ($usr == '') {header("Location: " . $hostpath . "/hr.php");

} else {

    require_once "common/PHPExcel.php";

    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

     */

    $currSection = 'inv_stock';
    include_once('common/inc_session_privilege.php');
    $currPage    = basename($_SERVER['PHP_SELF']);



    if (isset($_POST['add'])) {$prv = $_GET['up'];

        header("Location: " . $hostpath . "/challan.php?res=0&mod=12");

    }

    if (isset($_POST['export'])) {



        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)

            ->setCellValue('D1', 'Bitflow')

            ->setCellValue('D2', 'Stock Positon')

            ->setCellValue('A4', 'SL.')

            ->setCellValue('B4', 'Category')

            ->setCellValue('C4', 'Brand')

            ->setCellValue('D4', 'Product')

            ->setCellValue('E4', 'Barcode')

            ->setCellValue('F4', 'QTY Available');



        $firststyle = 'A2';
        $cat = $_POST["cmbcat"];  $brand = $_POST["cmbbrand"]; $bc1 = $_POST["bc"];
        
        $qry        = "SELECT s.id,p.id pid,p.barcode code,p.image, s.product,p.name prod,t.name typ,b.title brand, s.freeqty, s.bookqty, s.costprice,p.rate 
                        FROM stock s left join item p on s.product=p.id left join itmCat t on p.catagory=t.id left join brand b on p.brand=b.id
                        where  (p.barcode='".$bc1."' or '".$bc1."'='' or p.name like '%".$bc1."%' or p.barcode like '%".$bc1."%' ) and ( t.id = ".$cat." or ".$cat." = 0 ) 
                        and ( b.id = ".$brand." or ".$brand." = 0 ) ORDER BY p.name ASC ";

        // echo  $qry;die;

        $result = $conn->query($qry);

        if ($result->num_rows > 0) {$i = 3;

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

                    ->setCellValue($col2, $row['typ'])

                    ->setCellValue($col3, $row['brand'])

                    ->setCellValue($col4, $row['prod'])

                    ->setCellValue($col5, $row['code'])

                    ->setCellValue($col6, number_format($row['freeqty'],0)); /* */

                $laststyle = $title;

            }

        }

        $objPHPExcel->getActiveSheet()->setTitle('Stock Position');

        $objPHPExcel->setActiveSheetIndex(0);

        $today     = date("YmdHis");

        $fileNm    = "data/" . 'stock_position' . $today . '.xls';

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

      			<!--<div class="panel-heading"><h1>All Product Inventory</h1></div> -->

    				<div class="panel-body">



    <span class="alertmsg">

    </span>



                	<form method="post" action="inv_stock.php?mod=12" id="form1">



                     <div class="well list-top-controls">

                      <div class="row border">

                           <div class="col-sm-1 text-nowrap lg-text">

                            <h6>Inventory <i class="fa fa-angle-right"></i> Stock Position</h6>

                       </div>



                        <div class="col-sm-11 text-nowrap">

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
                                    <input type="text" class="no-mg-btn form-control" id="bc" name="bc" placeholder="Bar Code" value="<?php echo $barcode; ?>"  >

                                </div>

                                <div class="form-group">

                            <input type="search " id="search-dttable" class="form-control mini-issue-search">

                            </div>

                             <div class="form-group">
                                 <?=getBtn('export')?>
                            </div>









                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->

                        </div>

                        </div>

                        <!-- <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>

                        <div class="col-sm-1">

                         <!-- <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">-->

                        <!--</div> -->

                      </div>

                    </div>





    				</form>





<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>



                <div >

                    <!-- Table -->

                    <table id="listTable" class="display actionbtn no-footer dataTable" width="100%">

                        <thead>

                        <tr>

                           <th>SL.</th>
                           
                           <th>Category</th>
                           
                           <th>Brand</th>

                            <th width="10">Picture</th>
                            
                            <th>Product</th>

                            <th>Barcode</th>
							
							<th>Qty Avail. </th>
							
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

                    { data: 'id' },
                    
                    { data: 'typ' },
                    
                    { data: 'brand' },

                    { data: 'image',orderable:false },
                    
                    { data: 'prod' },

                    { data: 'code' },
					
					{ data: 'freeqty' },
							
                ],
			    columnDefs: [
    				{
    					targets: [3],
    					className: 'dt-body-center'
    				}
    			  ]
				 
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
	url = 'phpajax/datagrid_list_all.php?action=inv_stock';
	table_with_filter(url);	

     
        //Status
    $("#cmbcat,#bc, #cmbbrand").on("change input", function() {
            
            var cat = $('#cmbcat').val();
            var brand = $('#cmbbrand').val();
            var bc = $('#bc').val();
            
            var url = 'phpajax/datagrid_list_all.php?action=inv_stock&cat='+cat+'&barcode='+bc+'&brand='+brand;
			
			setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);
            
        });	
	


        });
       


        </script>
        
        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
				var pdfurl = "pdf_inv_stock.php";
				location.href=pdfurl;
				
			});
			
		</script>



    </body></html>

  <?php } ?>

