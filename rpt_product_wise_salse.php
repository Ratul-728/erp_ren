<?php
require "common/conn.php";

session_start();

//Filter
$fdt = $_POST['filter_date_from'];
$tdt = $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("01/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}


$filterorg = $_POST['filterorg'];

if ($filterorg != '') {
    $qrychorg    = "SELECT `name` FROM `item` where id = " . $filterorg;
    $resultchorg = $conn->query($qrychorg);
    while ($rowchorg = $resultchorg->fetch_assoc()) {
        $filterorgnm = $rowchorg["name"];

    }
} else {
    $filterorgnm = '';
}

$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_product_wise_salse';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/invoice.php?res=0&msg='Insert Data'&mod=2");
    }
    if (isset($_POST['export'])) {
        $product = $filterorg;
        if($product == '') $product = 0;
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'SO ID ')
            ->setCellValue('C1', 'Organization')
            ->setCellValue('D1', 'Order Date')
            ->setCellValue('E1', 'Status')
            ->setCellValue('F1', 'Amount')
            ->setCellValue('G1', 'Product')
            ->setCellValue('H1', 'Quantity')
            ->setCellValue('I1', 'Unit Price')
            ->setCellValue('J1', 'Discount Price');

        $firststyle = 'A2';

                                    $qry = "SELECT s.`id`, s.`socode`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`,st.name stnm,s.invoiceamount `amount`
                                        ,i.name product,d.qty,(d.otc+d.vat) unitprice,d.discounttot
                                        FROM `soitem` s left join `organization` o on s.organization=o.id left join soitemdetails d on s.socode=d.socode left join item i on d.productid=i.id
                                        left join orderstatus st on s.orderstatus=st.id 
                                        WHERE  (d.productid=$product or $product = 0)";
//WHERE  s.`status`<>6 order by s.`socode` asc";

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
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['socode'])
                    ->setCellValue($col3, $row['organization'])
                    ->setCellValue($col4, $row['orderdate'])
                    ->setCellValue($col5, $row['stnm'])
                    ->setCellValue($col6, number_format($row['amount'], 2))
                     ->setCellValue($col7, $row['product'])
                    ->setCellValue($col8, $row['qty'])
                    ->setCellValue($col9, $row['unitprice'])
                    ->setCellValue($col10, $row['discounttot']); /* */

                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Product Wise Salse Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today  = date("YmdHis");
        $fileNm = "data/" . 'product_wise_salse_report' . $today . '.xls';

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

    if (isset($_POST['pdf'])) {

    }

    ?>
    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php
include_once 'common_header.php';
    ?>
    <style>
        .left-mg-col{
    padding: 0px;
    margin: 0px;
    transform: translatex(30px);
}
    </style>
    <body class="list">

    <?php
include_once 'common_top_body.php';
    ?>
    <div id="wrapper">

      <!-- Sidebar -->

      <div id="sidebar-wrapper" class="mCustomScrollbar">

      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>REPORT</span>
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

    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="rpt_product_wise_salse.php?pg=1&mod=12" id="form1">
                         <div class="well list-top-controls">
                                  <div class="row border">

                                        <div class="col-sm-3 col-lg-2 text-nowrap">
                                                <h6>Billing <i class="fa fa-angle-right"></i>Product Wise Salse Report</h6>

            							</div>




                                    

            			<div class="col-sm-7 col-lg-8 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                          	<div class=" col-lg-2 col-md-6 col-sm-6 ">


                                                <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                <div class="form-group styled-select mgt5">
                                                    <input list="cmbassign1" name ="cmbassign2" value = "<?=$filterorgnm ?>" autocomplete="Search From list"  class="dl-cmborg datalist" placeholder="Select Product">
                                                    <datalist  id="cmbassign1" name = "cmbsupnm1" class="list-cmbassign form-control" >
                                                        <option value="">Select Product</option>
    <?php $qryitm = "SELECT id, name FROM `item` order by name";
    $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
        $tid = $rowitm["id"];
        $nm  = $rowitm["name"]; ?>
                                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
    <?php }} ?>
                                                     </datalist>
                                                     <input type = "hidden" name = "filterorg" id = "filterorg" value = "<?=$filterorg ?>">
                                                </div>
                                        </div>

                            
						
            		

                            <!--div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt; ?>" >
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt; ?>"  >
                            </div-->
                            <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div> 
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_product_wise_salse_report.php">
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


					<table id="listTable" class="table display dataTable no-footer actionbtns" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;">

					<!--table id="listTable" class="display dataTable no-footer actio nbtn" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;"-->
                        <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>SO ID</th>
                            <th>Organization </th>
                            <th>Order Date </th>
                            <th>Status</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Discount Total</th>
                            <th>Net Amount</th>
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

  <style>

.invpay-form{
  /*width: 330px;*/

}
.modal-body.inv-modal-body {
    margin: 0;
    padding: 0;
}

  </style>

     <!--inv Modal view-->
<div class="autoModal modal fade text-center" id="invpay-modal">
  <div class="modal-dialog invpay-form" role="document">
    <div class="modal-content bg-gray">
      <div class="modal-header inv-modal-header">
        <h5>Invoice</h5>
      </div>

      <div class="modal-body inv-modal-body">

        Loading...

      </div>
      <!--model body-->
    </div>
  </div>
</div>
 <!--end inv Modal view-->
  <script>
  window.closeModal = function(){
    $('#invpay-modal').modal('hide');
};




  </script>





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
                   { data: 'id', "orderable": false , "class":"action"  },
                    { data: 'socode' },
                    { data: 'organization' },
                    { data: 'orderdate' },
					{ data: 'stnm' },
                    { data: 'product' },
                    { data: 'qty' },
                    { data: 'unitprice' },
                    { data: 'discounttot' },
                    { data: 'amount' },
				
					
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
                        


                        var tf = '<tr><td></td><td></td><td></td><td></td> <td style="color: #00abe3; font-weight:bold">Total</td> <td style="color: #00abe3; font-weight:bold">'+tot1+ '</td>';

                        $("#listTable").append(
                            $('<tfoot/>').append( tf )
                        );



                    },500);


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
	url = 'phpajax/datagrid_list_all.php?action=rpt_product_wise_salse&filterorg=<?=$filterorg ?>';
	table_with_filter(url);	

	
	
	
	
        //DATE FILTER STARTS	
        $('#filter_date_from').daterangepicker({
            "autoApply": false,
            autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Clear',
        		"fromLabel": "From",
        		"toLabel": "To",		
            },	
        	
             "ranges": {
                "Today": [
        			
                    "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
                    "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z"
                ],
                "Yesterday": [
        			
                    "<?=date("d/m/Y", strtotime("-1 days")); ?>T20:12:21.910Z",
                    "<?=date("d/m/Y", strtotime("-1 days")); ?>T20:12:21.910Z"
                ],
                "Last 7 Days": [
                    "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
                    "<?=date("d/m/Y", strtotime("-7 days")); ?>T20:12:21.910Z"
                ],
                "Last 30 Days": [
                    "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
                    "<?=date("d/m/Y", strtotime("-30 days")); ?>T20:12:21.910Z"
                ],
        		 <?php
        		 //$query_date = date("d/m/Y");
        		 //$firstdayofmonth = date('01/m/Y', strtotime($query_date));
        		 //$lastdayofmonth = date('t/m/Y', strtotime($query_date));
        	
        		 $firstdayofmonth = date('01/m/Y');
        		 $lastdayofmonth = date('t/m/Y');	
        		 ?>
                "This Month": [
                    "<?=$firstdayofmonth?>T18:00:00.000Z",
                    "<?=$lastdayofmonth?>T17:59:59.999Z"
                ],
        		 <?php
        		 
        		 $firstdayoflastmonth = date('d/m/Y', strtotime("first day of previous month"));
        		 $lastdayoflastmonth = date('d/m/Y', strtotime("last day of previous month"));
        		 ?>		 
                "Last Month": [
                    "<?=$firstdayoflastmonth?>T18:00:00.000Z",
                    "<?=$lastdayoflastmonth?>T17:59:59.999Z"
                ]
            },
            "linkedCalendars": false,
            "alwaysShowCalendars": true,
            "startDate": "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
            "endDate": "<?=date("d/m/Y", strtotime("-1 months")); ?>T20:12:21.910Z",
        	maxDate: moment()
        }, function(start, end, label) {
          console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
        	
        	//alert(start.format('YYYY-MM-DD'));
        	if(start<end){
        	url = 'phpajax/datagrid_list_all.php?action=rpt_product_wise_salse&filterorg=<?=$filterorg ?>&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rpt_product_wise_salse&filterorg=<?=$filterorg ?>&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
        	}
        	//alert(url);
        	//setTimeout(function(){
        		table_with_filter(url);
        
        	//}, 350);	
        });
        
        $('#filter_date_from').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });	
        	
        $(".cancelBtn").click(function(){
        	$('#filter_date_from').val("");
        	url = 'phpajax/datagrid_list_all.php?action=rpt_product_wise_salse&filterorg=<?=$filterorg ?>';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	

			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script>
        <!--<script>
        $(document).ready(function(){
           var table1 = $('#listTable').DataTable({
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
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=rpt_product_wise_salse&filterorg=<?=$filterorg ?>'
                },
                'columns': [
                    { data: 'id', "orderable": false , "class":"action"  },
                    { data: 'socode' },
                    { data: 'organization' },
                    { data: 'orderdate' },
					{ data: 'stnm' },
                    { data: 'amount' },
                    { data: 'product' },
                    { data: 'qty' },
                    { data: 'unitprice' },
                    { data: 'discounttot' },
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
                        


                        var tf = '<tr><td></td><td></td><td></td><td></td> <td style="color: #00abe3; font-weight:bold">Total</td> <td style="color: #00abe3; font-weight:bold">'+tot1+ '</td>';

                        $("#listTable").append(
                            $('<tfoot/>').append( tf )
                        );



                    },500);


                }
            });
             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
        });



        </script>-->
        <script type="text/javascript">

    function openpopup(popurl){
       var popUpObj;
    popUpObj=window.open(popurl,"ModalPopUp","toolbar=no," +"scrollbars=no," + "location=no," + "statusbar=no," + "menubar=no," + "resizable=0," + "modal=yes,"+
    "width=400," +"height=310," + "left = 290," +"top=200"  );
    popUpObj.focus();
    //LoadModalDiv();


    }
    </script>

<script>
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        var id = $('#cmbassign1 option[value="' + g +'"]').attr('data-value');
        $('#filterorg').val(id);
        //alert(id);


	});
</script>
    <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
				var filterorg   = $("#filterorg").val();
				var pdfurl = "pdf_product_wise_salse_report.php?filterorg="+filterorg+"";
				location.href=pdfurl;
				
			});
			
		</script>

    </body>



    </html>
  <?php } ?>