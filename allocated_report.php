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
    $currSection = 'allocated_report';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if ( isset( $_POST['export'] ) ) {
        $fd1 = $_POST["from_dt"];
        $td1 = $_POST["to_dt"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and p.makedt BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $branch = $_POST["cmbbranch"]; if($branch == '') $branch = 0;
        $cat = $_POST["cmbcat"]; if($cat == '') $cat = 0;
        $bc1 = $_POST["bc"];
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Quotation')
                ->setCellValue('C1', 'Type')
                ->setCellValue('D1', 'Customer')
    			->setCellValue('E1', 'Category')
    			->setCellValue('F1', 'Product')
                ->setCellValue('G1', 'Order Date')
                ->setCellValue('H1', 'Delivery Date')
                ->setCellValue('I1', 'Order Status')
    			->setCellValue('J1', 'Allocated Qty')
    			->setCellValue('K1', 'QA Status')
                ->setCellValue('L1', 'Order Qty')
                ->setCellValue('M1', 'Passed Qty')
                ->setCellValue('N1', 'Delivered Qty');
    			
        $firststyle='A2';
        
        $qry="SELECT i.image,q.id,q.socode,q.srctype,q.project,(case when q.srctype=2 then q.project else 'Retail' end ) 'type', cat.name cat
                                ,o.name customer,q.organization cusid,a.product_id ,i.name,DATE_FORMAT(q.orderdate, '%d/%b/%Y') orderdate,(SELECT MAX(DATE_FORMAT(quow.expted_deliverey_date, '%d/%b/%Y'))
                                FROM quotation_warehouse quow
                                WHERE a.order_id = quow.socode) AS deliverydt,q.orderstatus,qs.name orderst
                                ,a.product_id,(a.quantity -COALESCE((SELECT sum(delivered_qty) FROM delivery_order_detail where qa_id=a.id),0)) quantity
                                ,a.status ,qas.name qastatus,qw.qa_type,qw.warehouse_id,qw.ordered_qty,COALESCE(qw.pass_qty,0) pass_qty
                                ,COALESCE((SELECT sum(delivered_qty) FROM delivery_order_detail where qa_id=a.id),0) deliverdqty
                                FROM quotation q left join qa a on q.socode=a.order_id left join quotation_status qs on q.orderstatus=qs.id left join qastatus qas on a.status=qas.id
                                left join qa_warehouse qw on a.id=qw.qa_id left join organization o on q.organization=o.id 
                                left join branch b on qw.warehouse_id=b.id left join item i on a.product_id=i.id LEFT JOIN itmCat cat ON cat.id=i.catagory
                                WHERE (i.barcode='".$bc1."' or '".$bc1."'='') 
                                and ( cat.id = ".$cat." or ".$cat." = 0 ) and q.orderstatus in(4,5,7) and qw.ordered_qty>COALESCE((SELECT sum(delivered_qty) FROM delivery_order_detail where qa_id=a.id),0) ";
        
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;$tcp=0;$tmp=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;
                $col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $col12='L'.$urut;$col13='M'.$urut;$col14='N'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['socode'])
    						->setCellValue($col3, $row['type'])
    					    ->setCellValue($col4, $row['customer'])
    					    ->setCellValue($col5, $row['cat'])
    					    ->setCellValue($col6, $row["name"])
    			            ->setCellValue($col7, $row['orderdate'])
    						->setCellValue($col8, $row['deliverydt'])
    						->setCellValue($col9, $row['orderst'])
    						->setCellValue($col10, $row['quantity'])
    					    ->setCellValue($col11, $row['qastatus'])
    					    ->setCellValue($col12, $row['ordered_qty'])
    					    ->setCellValue($col13, $row["pass_qty"])
    			            ->setCellValue($col14, $row['deliverdqty']);	/* */
    			$laststyle=$title;	
            }
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Allocated Report ');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'allocated_'.$today.'.xls'; 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);
        
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$fileNm);
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

                	<form method="post" action="#" id="form1" enctype="multipart/form-data">
                        <!-- START PLACING YOUR CONTENT HERE -->

                        <div class="well list-top-controls">
                
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Inventory <i class="fa fa-angle-right"></i> Allocated Report </h6>
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
                            
                            <!--div class="form-group">
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
                                </div-->
                            
                            <div class="form-group">
                                    <input type="text" class="no-mg-btn form-control" id="bc" name="bc" placeholder="Bar Code" value="<?php echo $barcode; ?>"  >

                                </div>
                                
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_allocated.php">
                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
								</ul>
							</div>
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
								<th>Quotation</th>
                                <th>Type</th>
                                <th>Customer</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Barcode</th>
                                <th>Order Date</th>
                                <th>Delivery Date</th>
                                <th>Order Status</th>
                                <th>Allocated Qty</th>
                                <th>QA Status</th>
                                <th>Order Qty</th>
                                <th>Passed Qty</th>
                                <th>Delivered Qty</th>
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
                    { data: 'id'},
                    { data: 'socode'},
                    { data: 'type' },
                    { data: 'customer'},
                    { data: 'cat'},
                    { data: 'photo'},
                    { data: 'product'},
                    { data: 'barcode'},
                    { data: 'orderdate'},
                    { data: 'deliverydt' },
                    { data: 'orderst'},
                    { data: 'quantity', orderable: false },
                    { data: 'qastatus'},
                    { data: 'ordered_qty', orderable: false },
                    { data: 'pass_qty'},
                    { data: 'deliverdqty', orderable: false }

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
	url = 'phpajax/datagrid_list_all.php?action=allocated';
	table_with_filter(url);	
    
    //Status
    $("#cmbbranch,#cmbcat,#bc").on("change input", function() {

            
            //var branch = $('#cmbbranch').val();
            var bc = $('#bc').val();
            var cat = $('#cmbcat').val();
            
            var url = 'phpajax/datagrid_list_all.php?action=allocated&barcode='+bc+'&cat='+cat;
			
			 setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        


    </body></html>
  <?php } ?>
