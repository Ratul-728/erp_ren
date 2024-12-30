<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";

    $currSection = 'delivery_return';
    $currPage    = basename($_SERVER['PHP_SELF']);

    $res = $_GET["res"];
    $msg = $_GET["msg"];
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Sl.')
            ->setCellValue('B1', 'Product Code')
            ->setCellValue('C1', 'Product')
            ->setCellValue('D1', 'Barcode')
            ->setCellValue('E1', 'Brand')
            ->setCellValue('F1', 'Category')
            ->setCellValue('G1', 'Rate')
            ->setCellValue('H1', 'Free Quantity')
            ->setCellValue('I1', 'Store');

        $firststyle = 'A2';
        $qry        = "select p.code,p.name,p.rate,p.barcode,p.image,s.freeqty,b.name store,r.title brand, i.name catagory
                                from chalanstock s join item p on s.product=p.id left join branch b on s.storerome=b.id
                                left join brand r on p.brand=r.id left join itmCat i on p.catagory=i.id
                                where  s.freeqty>0
                                ";
        // echo  $qry;die;
        //s.`socode`='ANTGR003' and

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
                $i++;
                $mnt = date("F", strtotime($row['invoicemonth']));
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['code'])
                    ->setCellValue($col3, $row['name'])
                    ->setCellValue($col4, $row['barcode'])
                    ->setCellValue($col5, $row['brand'])
                    ->setCellValue($col6, $row['catagory'])
                    ->setCellValue($col7, $row['rate'])
                    ->setCellValue($col8, $row['freeqty'])
                    ->setCellValue($col9, $row['store']);
                $laststyle = $title;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('Storewise Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'rpt_stwas' . $today . '.xls';
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
        <span>Return To GRS</span>
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
            <div class="col-lg-12 col-xs-11">

            <p>&nbsp;</p>
            <p>&nbsp;</p>

              <!--h1 class="page-title">Customers</a></h1-->
              <p>
              <!-- START PLACING YOUR CONTENT HERE -->


              <div class="panel panel-info">
      			<!-- <div class="panel-heading"><h1 class="left-align">All Expenses </h1></div> -->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="delivery_returnList.php?mod=3" id="form1">
                         <div class="well list-top-controls">
                                  <div class="row border">

                                      <!--div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div-->
                                      <div class="col-sm-4">
                                          <div class="col-lg-12 text-nowrap">
                            <h6>Inventory <i class="fa fa-angle-right"></i> </h6>
                       </div>
                                      </div>
                                      <div class="col-sm-7 col-lg-8 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                          	
                            
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control">
                            </div>
                            
                            <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
									<!--li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li-->
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


					<table id="listTable" class="table display dataTable no-footer actionbtns" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;">

					<!--table id="listTable" class="display dataTable no-footer actio nbtn" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;"-->
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>DO ID</th>
                            <th>Order ID</th>
                            <th>Invoice ID</th>
                            <th>No of items</th>
                            <th>Total Delivery Quantity</th>
                            <th>Total Return Quantity</th>
                            <!--th>From Warehouse</th-->
                            <th>To Warehouse</th>
                            <th>Action</th>
                            
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

    <div id = "divBackground" style="position: fixed; z-index: 999; height: 100%; width: 100%; top: 0; left:0; background-color: Black; filter: alpha(opacity=60); opacity: 0.6; -moz-opacity: 0.8;display:none">

    </div>
    <!-- /#page-content-wrapper -->

    <?php
include_once 'common_footer.php';
    ?>
    
    <?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
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
                   { data: 'do_id' },
                    { data: 'order_id' },
                    { data: 'invoiceno'},
                    { data: 'total_items' },
                    { data: 'total_do_qty' },
                    { data: 'total_return_qty'},
                    //{ data: 'fromwarehouse'},
                    { data: 'warehouse' },
                    { data: 'action' },
                    
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
	url = 'phpajax/datagrid_delivery.php?action=return_delivery';
	table_with_filter(url);	
	
	//Status
        $("#cmbstore,#cmbbrand,#cmbcat").on("change", function() {

            
            var store = $('#cmbstore').val();
            var brand = $('#cmbbrand').val();
            var cat = $('#cmbcat').val();
            //var startdt = $('#start_dt').val();
            //var url = 'phpajax/datagrid_saleorder.php?action=inv_soitem&user='+user+'&cmbstatus='+status+'&paidto='+paidto+'&startdt='+startdt+'&enddt='+enddt;
			url = 'phpajax/datagrid_list_all.php?action=rpt_storeroom_wise_available_stock&brand='+brand+'&cat='+cat+'&store='+store;
			
			//alert(status);
			
            
			
            setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
	
});
		
</script> 
        
        
    <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				var store = $('#cmbstore').val();
                var brand = $('#cmbbrand').val();
                var cat = $('#cmbcat').val();
                
				var pdfurl = 'pdf_storeroom_wise_available_stock.php?brand='+brand+'&cat='+cat+'&store='+store;
				location.href=pdfurl;
				
			});
			
		</script>
		<script>
$(document).ready(function(){
	
//show INVOICE
	
	$(".dataTable").on("click",".show-invoice.btn",function(){
		
  	mylink = $(this).attr('href')+"?doid="+$(this).data('doid')+"&invid="+$(this).data('invid')+"&qtype=delivery";
	
   //alert(mylink);
  
  		BootstrapDialog.show({
							
							title: 'DELIVERY RETURN ORDER',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: false, // <-- Default value is false
							cssClass: 'show-invoice',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Cancel',
								action: function(dialog) {
									dialog.close();	
									/*
									$("#printableArea2").printThis({
										importCSS: true, 
										importStyle: true,
									});
									*/
									
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Print',
								action: function(dialog) {
									
									$("#printableArea2").printThis({
										importCSS: false, 
										importStyle: true,
									});
		
									
									dialog.close();	
									
									},
								
							}],
							onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});		
		
	
});
</script>

    </body></html>
  <?php } ?>
