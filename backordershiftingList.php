<?php
require "common/conn.php";
session_start();

unset($_SESSION['treatfrom']);
$_SESSION['treatfrom'] = 'fin';



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
    $currSection = 'backordershifting';
    $currPage    = basename($_SERVER['PHP_SELF']);
	// load session privilege;
	include_once('common/inc_session_privilege.php');
	
    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/invoice.php?res=0&msg='Insert Data'&mod=17");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'INOVICE ID')
            ->setCellValue('C1', 'ORDER ID')
            ->setCellValue('D1', 'DATE')
            ->setCellValue('E1', 'CUSTOMER')
            ->setCellValue('F1', 'PAYABLE AMOUNT')
            ->setCellValue('G1', 'ADJUST AMOUNT')
            ->setCellValue('H1', 'PAID')
            ->setCellValue('I1', 'DUE ')
            ->setCellValue('J1', 'PAYMENT STATUS');

        $firststyle = 'A2';

        $qry = "
		SELECT  1 sl,i.`invoiceno`,i.makedt makedt,i.id iid, i.`invyr`, i.`invoicemonth`,i.`invoicedt`, i.`soid`, o.id cid, o.name `organization`, 
		i.`invoiceamt` invoiceamt,format(i.amount_bdt,2) amount_bdt,   format(i.`paidamount`+paid_reservedamt,2)paidamount, i.`dueamount` due, format(i.`dueamount`+i.due_reservedamt,2)dueamount,
		i.`duedt`, s.`name`,s.`dclass` `invoiceSt`,p.`name` paySt,p.`id` paymentstid,p.`dclass` `paymentSt`,
        o.balance orgbal,o.id orgid, i.reserved_amount, i.due_reservedamt 
		FROM `invoice` i  
		LEFT JOIN invoicestatus s  on i.invoiceSt=s.id 
		LEFT JOIN invoicepaystatus p on i.paymentSt=p.id  
        LEFT JOIN organization o on i.organization=o.id 
		
	 	WHERE  1=1 order by i.`id` desc";
//WHERE  s.`status`<>6 order by s.`socode` asc";

        // echo  $qry;die;

        $result = $conn->query($qry);
        if ($result->num_rows > 0) {$i = 0;
            while ($row = $result->fetch_assoc()) {
                $invoicedt=date_create($row['invoicedt']);
			    $invoicedt =  date_format($invoicedt,"d/m/Y");
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
                    ->setCellValue($col3, $row['soid'])
                    ->setCellValue($col4, $invoicedt)
                    ->setCellValue($col5, $row['organization'])
                    ->setCellValue($col6, $row['amount_bdt']+$row['reserved_amount'])
                    ->setCellValue($col7, number_format($row['reserved_amount'], 0))
                    ->setCellValue($col8, number_format($row['paidamount'], 0))
                    ->setCellValue($col9, number_format($row['dueamount'], 0))
                    ->setCellValue($col10, $row["paySt"]); /* */

                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Invoice');
        $objPHPExcel->setActiveSheetIndex(0);
        $today  = date("YmdHis");
        $fileNm = "data/" . 'FINANCE_CUSTOMIZATION_' . $today . '.xls';

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
        <span>Finance</span>
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
                                                <h6>Approval Order shifting <i class="fa fa-angle-right"></i> All Invoices</h6>

            							</div>



            			<div class="col-sm-9 text-nowrap"> 

                        <div class="pull-right grid-panel form-inline">
							
                                <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>
							
                                <!--div class="form-group">
  									<div class="form-group styled-select" style="width: 200px;">
										<input list="cmbassign1" name ="cmbassign2" value = "<?=$filterorgnm ?>" autocomplete="off"  class="dl-cmborg datalist" placeholder="Select Organization">
										<datalist  id="cmbassign1" name = "cmbsupnm1" class="list-cmbassign form-control" >
											<option value="">Select Organization</option>
												<?php $qryitm = "SELECT DISTINCT inv.`organization`, org.name FROM `invoice` inv LEFT JOIN organization org ON org.id = inv.`organization` order by org.name";
												$resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
													$tid = $rowitm["organization"];
													$nm  = $rowitm["name"]; ?>
																									<option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
												<?php }} ?>
										 </datalist>
                                                     <input type = "hidden" name = "filterorg" id = "filterorg" value = "<?=$filterorg ?>">
                                  </div>
                                </div-->								



                            <div class="form-group">
                                <input type="text" class="form-control invdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Invoice Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div>
<!--
                            <div class="form-group">
                                <input type="text" class="form-control invdtpicker datepicker_history_filterx" placeholder="Date To" name="filter_date_to" id="filter_date_to" value=""  >
                            </div>
-->
                            <div class="form-group">
                                <input type="search" id="search-dttable"  placeholder="Search Keywords" class="form-control">
                            </div>
<!--
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
-->
							
                                <div class="form-group">
  									|
                                </div>							
                            <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                            <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
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


					<!--table id="listTable" class="table display dataTable no-footer actionbtns" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;"-->
						
					<table id='listTable' class='display actionbtn no-footer dataTable' width="100%">

					<!--table id="listTable" class="display dataTable no-footer actio nbtn" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;"-->
                        <thead>
                        <tr>
                            <th>Created</th>
                            <th>Invoice ID</th>
							<th>Order ID</th>
                            <th>Date </th>
<!--                            <th>Month </th>-->
                            
                            <th>Customer</th>
							<th>Payable Amount</th>
							<th>Adjust Balance</th>
                            <th>Paid</th>
                            <th>Due</th>
<!--                            <th>Due Date </th>-->
                            <th>Payment Status </th>
							 <th>Edit  |  Pay</th>
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
                    //'url':'phpajax/datagrid_invoice.php?action=invoice&filterorg=<?=$filterorg ?>&filterst=<?=$filterst ?>&fdt=<?=$fdt ?>&tdt=<?=$tdt ?>&yeardt=<?=$yeardt ?>'
					
					'url':url,
                },
                'columns': [
                    //{ data: 'sl', "orderable": false , "class":"action"  },
					{ data: 'makedt','bVisible': false },
                    { data: 'invoiceno' },
					{ data: 'soid' },
                    { data: 'invoicedt' },
                    //{ data: 'invoicemonth' },
                    
                    { data: 'organization' },
					{ data: 'invoiceamt' },
					{ data: 'adjustment' },
					//{ data: 'amount_bdt' },
                    { data: 'paidamount' },
                    { data: 'dueamount' },
                	//{ data: 'duedt' },
            		{ data: 'paymentSt' },
					{ data: 'edit', "orderable": false , "class":"actiona" }
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
	url = 'phpajax/datagrid_invoice.php?action=ordershifting&fdt=<?=$fdt ?>&tdt=<?=$tdt ?>&yeardt=<?=$yeardt ?>';
	//url = 'phpajax/datagrid_invoice.php?action=invoice';
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
	url = 'phpajax/datagrid_invoice.php?action=ordershifting&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
	}
	else
	{
	url = 'phpajax/datagrid_invoice.php?action=ordershifting&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
	url = 'phpajax/datagrid_invoice.php?action=ordershifting';
	table_with_filter(url);
});
	
//ENDS DATE FILTER START
				
//show payment dialog
	
	$(".dataTable").on("click",".mkpayment",function(){
		
  	mylink = $(this).attr('href');
   //alert(mylink);
  
  
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: 'RECEIVE PAYMENT FOR #'+$(this).data('invid'),
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: false, // <-- Default value is false
							draggable: false, // <-- Default value is false
							cssClass: 'post-posdata',
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
								label: ' Pay Now',
								action: function(dialog) {
									
									
									
									var whichTab = $('#paytab').val();
									ajxdata = $('#payment_form').serializeArray();
									//alert(ajxdata);
									
									if(whichTab == 1){ //Cash Receive
										
										
										if(!$("#paidmnt-cr").val()){
											swal("Alert", "Please enter paid amount", "warning");
											return false;
										}
									}else{  //Wallet
										
										if(!$("#paidmnt-wl").val()){
											swal("Alert", "Please enter paid amount", "warning");
											return false;
										}
										var wlpayment = parseFloat($("#paidmnt-wl").val());
										var walletmnt = parseFloat($("#walletmnt").val());
										if(wlpayment > walletmnt){
											swal("Alert", "Insufficient Wallet Balance!", "warning");
											return false;
										}										
										
									}
									
									
									
									
									
									
									//alert("test");
									
									$.ajax({
										type: "POST",
										dataType: 'json',
										url: "phpajax/make_payment_fin.php",
										data: { ajxdata : ajxdata, whichTab:whichTab },
										beforeSend: function(){
												//$("#cmbsupnm").html("<option>Loading...</option>");
											},

									}).done(function(data){
										
										//alert(data.msg);

										  swal("Payment Status", data.msg, "success");
											
											url = 'phpajax/datagrid_invoice.php?action=ordershifting';
											
											table_with_filter(url);
										
											setTimeout(function(){

												$(".rowid_"+data.invno).closest("tr").addClass("updatedtr");
											setTimeout(function(){
												$(".rowid_"+data.invno).closest("tr").removeClass("updatedtr");
												}, 10050);

										}, 1050);
										
										
										dialog.close();
									});										
									
									
									

									
									
									
									
								},
								
							}],
							onshown: function(dialog){  $('#paidmnt-cr').select();},
						}); //BootstrapDialog.show({	
  
  
  
  
  
  
  	return false;
});

//show INVOICE
	
	$(".dataTable").on("click",".show-invoice.btn",function(){
		
  	mylink = $(this).attr('href');
		mylink = $(this).attr('href');
   //alert(mylink);
  
  		BootstrapDialog.show({
							
							title: 'INVOIE NO: '+$(this).data('invid'),
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

    
$("#listTable").on("click",".paysliplist",function(){
		
  	    mylink = $(this).attr('href');
		
   //alert(mylink);
  
  		BootstrapDialog.show({
							
							title: 'PAYMENT SLIP LIST OF: '+$(this).data('invid'),
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea3"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: true, // <-- Default value is false
							cssClass: 'show-invoice',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Close',
								action: function(dialog) {
									dialog.close();	
									
									
								}
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
