<?php
require "common/conn.php";
require "common/user_btn_access.php";
session_start();

unset($_SESSION['treatfrom']);
$_SESSION['treatfrom'] = 'acc';


//Filter
$fdt = $_POST['filter_date_from'];
$tdt = $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("01/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}
$yeardt = $_POST["yeardt"];if ($yeardt == "") {
    $yeardt = date("Y");
}

$filterorg = $_POST['filterorg'];
$filterst  = $_POST['filterst'];

if ($filterorg != '') {
    $qrychorg    = "SELECT `name` FROM `organization` where id = " . $filterorg;
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
    $currSection = 'returndeliveryreport';
    $currPage    = basename($_SERVER['PHP_SELF']);
	// load session privilege;
	include_once('common/inc_session_privilege.php');
    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/cancelorder.php?res=0&msg='Insert Data'&mod=3");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'Invoice ')
            ->setCellValue('C1', 'Year')
            ->setCellValue('D1', 'Month')
            ->setCellValue('E1', 'Order Number')
            ->setCellValue('F1', 'Company')
            ->setCellValue('G1', 'Amount (invoiced Currency)')
            ->setCellValue('H1', 'Amount (BDT)')
            ->setCellValue('I1', 'Paid ')
            ->setCellValue('J1', 'Due ')
            ->setCellValue('K1', 'Due Date')
            ->setCellValue('L1', 'Invoice Status')
            ->setCellValue('M1', ' Status');

        $firststyle = 'A2';

        $qry = "SELECT  1 sl,i.`invoiceno`, i.`invyr`, i.`invoicemonth`, i.`soid`, o.name `organization`, i.`invoiceamt` invoiceamt,i.amount_bdt amount_bdt, format(i.`paidamount`,0)paidamount, format(i.`dueamount`,0)dueamount, i.`duedt`, s.`name`,s.`dclass` `invoiceSt`,p.`name` paySt,p.`dclass` `paymentSt`,o.balance orgbal,o.id orgid FROM `invoice` i  left join invoicestatus s  on i.invoiceSt=s.id left join invoicepaystatus p on i.paymentSt=p.id  left join organization o on i.organization=o.id where 1=1    order by i.`invoiceno` asc";
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
                    ->setCellValue($col2, $row['invoiceno'])
                    ->setCellValue($col3, $row['invyr'])
                    ->setCellValue($col4, date('F', mktime(0, 0, 0, $row['invoicemonth'], 10)))
                    ->setCellValue($col5, $row['soid'])
                    ->setCellValue($col6, $row['organization'])
                    ->setCellValue($col7, number_format($row['invoiceamt'], 0))
                    ->setCellValue($col8, number_format($row['amount_bdt'], 0))
                    ->setCellValue($col9, number_format($row['paidamount'], 0))
                    ->setCellValue($col10, number_format($row['dueamount'], 0))
                    ->setCellValue($col11, $row['duedt'])
                    ->setCellValue($col12, $row['name'])
                    ->setCellValue($col13, $row['paySt']); /* */

                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Invoice');
        $objPHPExcel->setActiveSheetIndex(0);
        $today  = date("YmdHis");
        $fileNm = "data/" . 'invoicelist_' . $today . '.xls';

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

<!-- Select2 CSS -->
<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />
<style>


.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 34px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b{
    border: 0;
}

.select2-container--default .select2-selection {
  background-color: transparent;
  border: 0px solid #aaa!important;
  border-radius: 0px;
  cursor: text;
}

.select2-container .select2-selection {
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  min-height: 38px;
  user-select: none;
  -webkit-user-select: none;
}


.select2-container--default .select2-selection .select2-selection__choice {
  background-color: #e4e4e4;
  border: 1px solid #dbdbdb;
  border-radius: 2px;

  padding: 3px;
  padding-left: 0px;
  padding-left: 30px;
  font-size: 14px;
}

.select2-container--default .select2-selection .select2-selection__choice__remove {
  padding: 3px 8px;
}
    
    
.select2-container{
  width:102%!important;
    padding: 0;margin: 0;
}

.select2-container {
    z-index: 99999!important; /* Set a higher z-index */
}



</style>
<!-- end Select2 CSS -->



    <body class="list">

    <?php
include_once 'common_top_body.php';
    ?>
    <div id="wrapper">

      <!-- Sidebar -->

      <div id="sidebar-wrapper" class="mCustomScrollbar">

      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>Return Delivery</span>
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

                	<form method="post" action="#" id="form1">
                         <div class="well list-top-controls">
                                  <div class="row border">

                                        <div class="col-sm-3  text-nowrap">
                                                <h6>Sales <i class="fa fa-angle-right"></i>Return Delivery</h6>

            							</div>



            			<div class="col-sm-9 text-nowrap"> 

                        <div class="pull-right grid-panel form-inline">
							
                                <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>
							
                                                            

                            <!--div class="form-group">
                                <input type="text" class="form-control invdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Invoice Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div-->
                            <div class="form-group">
                                <input type="search" id="search-dttable"  placeholder="Search Keywords" class="form-control">
                            </div>
                                <div class="form-group">
  									|
                                </div>							
                            
                            <!--div class="form-group">
                                <?=getBtn('export')?>
                            </div-->
                        </div>

                        </div>

                                  </div>
                                </div>
    				</form>


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >

                   	<table id='listTable' class='display actionbtn no-footer dataTable' width="100%">

					    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Return ID</th>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Barcode</th>
                            <th>Return Qty</th>
							 
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
  width: 330px;
	height: 800px;

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
      <div class="modal-header inv-modal-headerx">
        <h5>Cancel Order</h5>
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


 <!-- Select2 JS -->
<script src="js/plugins/select2/select2.min.js"></script>

<!-- Script -->
<?php
	    if ($_GET["res"]){
        echo "<script type='text/javascript'>messageAlert('".$_GET["msg"]."')</script>"; 
    }
	?>
<script>
$(document).ready(function(){
			
			
		function table_with_filter(url){			
			
		   var table1 =  $('#listTable').DataTable().destroy();
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
			   "order": [[ 0, "desc" ]],
				"dom": "rtiplf",
                'ajax': {
					'url':url,
                },
                'columns': [
                    { data: 'id', "orderable": false },
                    { data: 'do_id', "orderable": false },
                    { data: 'order_id', "orderable": false },
                    { data: 'orderdate' },
                    { data: 'customer', "orderable": false },
                    { data: 'product', "orderable": false },
                    { data: 'barcode', "orderable": false },
                    { data: 'returned_qty', "orderable": false }
					//,
					//{ data: 'action', "orderable": false , "class":"actiona" }
                ]
            });
			
			
            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
			
             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
			
	
	
	
	
	}//function table_with_filter(url){	
			

	//general call on page load
	url = 'phpajax/datagrid_report.php?action=return_delivery_report';
	
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
	url = 'phpajax/datagrid_list_all.php?action=cancelorder&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
	}
	else
	{
	url = 'phpajax/datagrid_list_all.php?action=cancelorder&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
	url = 'phpajax/datagrid_list_all.php?action=cancelorder';
	table_with_filter(url);
});
	
//ENDS DATE FILTER START
				
	
	


function dateRangePopup(){
	
        $(document).on('focus','.datepicker-popup', function(){
			
            $(this).datetimepicker({
       
                 //minDate: moment().startOf('day').add(1, 'days').toDate() ,
				 format: "DD/MM/YYYY",
				 //format: 'LT',
                 //debug:true,
				 //keepOpen:true,
                 showClear:true,
                 useCurrent:false,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });    
    });	
	
}
	

function initSelect2(){

  
    
}
//show INVOICE
	
	$(".dataTable").on("click",".show-invoice.btn",function(){
		 
  	mylink = $(this).attr('href');
		mylink = $(this).attr('href');
   //alert(mylink);
  
  		BootstrapDialog.show({
							
							title: 'Return Order',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: true, // <-- Default value is false
							cssClass: 'show-invoice',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Cancel',
								action: function(dialog) {
									dialog.close();	
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Print',
								hotkey: 13, // Enter.
								action: function(dialog) {
									
									$("#printableArea2").printThis({
										importCSS: false, 
										importStyle: true,
									});
		
									
									dialog.close();	
									
									},
								
							}],
							//onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});		



    
	
}); //$(document).ready(function(){	
	
</script>	

    </body>

    </html>
  <?php } ?>
