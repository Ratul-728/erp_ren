<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    $fd1    = $_POST['from_dt'];
    $td2    = $_POST['to_dt'];
    $dagent = $_POST['cmbsupnm'];

    if ($fd1 == '') {$fd1 = date("d/m/Y");}
    if ($td1 == '') {$td1 = date("d/m/Y");}

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'cusorderdelivery';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/custorder.php?res=0&msg='Insert Data'&mod=1");
    }
    if (isset($_POST['export'])) {
        //echo "yes"; die;
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL')
            ->setCellValue('B1', 'ORDER ID')
            ->setCellValue('C1', 'ORDER DATE')
            ->setCellValue('D1', 'CUSTOMER ID')
            ->setCellValue('E1', 'CUSTOMER NAME')
            ->setCellValue('F1', 'CUSTOMER ADDRESS')
            ->setCellValue('G1', 'CUSTOMER EMAIL')
            ->setCellValue('H1', 'CUSTOMER CONTACT')
            ->setCellValue('I1', 'ORDER STATUS')
            ->setCellValue('J1', 'ORDER AMOUNT')
            ->setCellValue('K1', 'PAYMENT MODE')
            ->setCellValue('L1', 'PAYMENT STATUS');

        $firststyle = 'A2';
        $qry        = "SELECT o.`id`,o.`order_id`,o.`customer_id`,o.name,concat(o.`address`,',',o.`district`,',',o.`area`) addrs,o.`email`,o.`phone`,st.name stnm,o.`orderstatus` st
        , DATE_FORMAT(o.`order_date`,'%e/%c/%Y') `order_date`,o.`amount`,o.status payst,o.payment_mood
FROM `orders` o left join orderstatus st on o.orderstatus=st.id order by o.`order_id`";
        //echo  $qry;die;
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
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['order_id'])
                    ->setCellValue($col3, $row['order_date'])
                    ->setCellValue($col4, $row['customer_id'])
                    ->setCellValue($col5, $row['name'])
                    ->setCellValue($col6, $row['addrs'])
                    ->setCellValue($col7, $row['email'])
                    ->setCellValue($col8, $row['phone'])
                    ->setCellValue($col9, $row['stnm'])
                    ->setCellValue($col10, $row['amount'])
                    ->setCellValue($col11, $row['payment_mood'])
                    ->setCellValue($col12, $row['payst']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ORDER');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'order_' . $today . '.xls';
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
        <span>All Order</span>
      </div>

    <?php
include_once 'menu.php';
    ?>
      </div>

      <!-- END #sidebar-wrapper -->

      <!-- Page Content -->
      <div id="page-content-wrapper">
        <div class="container-fluid xyz">
          <div class="row">
            <div class="col-lg-12">
            <p>&nbsp;</p>
            
             
              <!--h1 class="page-title">Customers</a></h1-->
              <p>
              <!-- START PLACING YOUR CONTENT HERE -->
                  <div class="panel panel-info">
          			
        				<div class="panel-body">

<span class="alertmsg"></span>

	<form method="post" action="custorderdelivery.php?mod=4" id="form1">


		<div class="well list-top-controls"> 

 
			
			
                     <div class="row border">

						 
                       <div class="col-sm-3 text-nowrap">
                            <h6>Salse <i class="fa fa-angle-right"></i> Order Delivery</h6>
                       </div>  						 
						 
                            <div class="col-sm-9 text-nowrap"> 
								<div class="pull-right grid-panel form-inline">
									

								


								

									
                                <div class="form-group">
  									<label for="">&nbsp;&nbsp;Filter By: </label>
                                </div>	
                              
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbstatus" id="cmbstatus" class="form-control" >
                                            <option value="0">All Status</option>
    <?php
$qry1    = "SELECT id, name FROM orderstatus where id IN(3,5,10) ORDER BY name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($icat == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <input type="text" placeholder="Delivery Date Range" class="form-control datepicker-delivery" name="from_dt" id="filter_date_from"  autocomplete="off" >
                                </div>                        	

                            <div class="form-group">
                            <input type="search" id="search-dttable"  placeholder="Search Keywords" class="form-control">
<!--								<button id="reset-date"><i class="fa fa-close" ></i></button> -->
                            </div>									
									
							<div class="form-group">
								&nbsp;|&nbsp;
							</div>									
							<div class="form-group">

                            	 <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
							</div>
                            
                        </div>
								
								</div>
			</div>


			</div>

			</form>






    <link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

    <!-- Grid Status Menu -->
    <link href="js/plugins/grid_status_menu/grid_status_menu.css" rel="stylesheet">
    <!-- End Grid Status Menu -->



                    <div >
                        <!-- Table -->
                        <table id='listTable' class='display dataTable' width="100%">
                            <thead>
                            <tr>
								<th>Updated</th>
                                <th>Order No</th>
								<th>Order Date</th>
                                <th>Customer Name</th>
                                <th>Cell No </th>
                                
                                <th>Order Status</th>
                                <th>Amount </th>


                                <th>Process</th>
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
				//$('#listTable').on( 'draw.dt',  function () { putClass(); } )
				var table1 = $('#listTable').DataTable({
					//"dom": 'rtip', // the "r" is for the "processing" message
					/*"language": {
					"processing": "<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span>"
					},*/
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
					deferRender: true,
					scroller: true,
					"order": [[ 0, "desc" ]],
					 "dom": "rtiplf",
					'ajax': {
						//'url':'phpajax/datagrid_delivery.php?action=cusorderdelv&lvl=2&fdt=<?php echo $fd1; ?>&dagnt=<?php echo $dagent; ?>'
						'url':url,
					},
					'columns': [
						
						{ data: 'lastdeliverydt','bVisible': false },
						{ data: 'order_id',
						'render': function (order_id) {
							return '<span class="rowid_'+ order_id +'">' + order_id +'</span>'
							}
						},						
						//{ data: 'order_id' },
						{ data: 'order_date' },
						{ data: 'name' },
					   // { data: 'addrs' },
						//{ data: 'email' },
						{ data: 'phone' },

						{ data: 'status' },
						{ data: 'amount' },


						{ data: 'edit', orderable: false, className: "btncol"  }
					]
				});

				//new $.fn.dataTable.FixedHeader( table1 );
				setTimeout(function(){
					table1.columns.adjust().draw();
				}, 350);


				$('#search-dttable').keyup(function(){
					  table1.search($(this).val()).draw() ;
				}) 				
				

			} //function table_with_filter(url){

			
	//general call on page load
	url = 'phpajax/datagrid_delivery.php?action=cusorderdelv';
	table_with_filter(url);				
	
	//'url':'phpajax/datagrid_delivery.php?action=cusorderdelv&lvl=2&fdt=<?php echo $fd1; ?>&dagnt=<?php echo $dagent; ?>'		
			
			
        //Status
        $("#cmbstatus").on("change", function() {

            var status = $(this).val();
			//status = parseInt(status.trim());
            //var user = $('#filteruser').val();
            //var paidto = $('#filterpaidto').val();
            //var enddt = $('#end_dt').val();
            //var startdt = $('#start_dt').val();
            //var url = 'phpajax/datagrid_saleorder.php?action=inv_soitem&user='+user+'&cmbstatus='+status+'&paidto='+paidto+'&startdt='+startdt+'&enddt='+enddt;
			url = 'phpajax/datagrid_delivery.php?action=cusorderdelv&cmbstatus='+status;
			
			//alert(status);
			
            
			
            setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });
			


			
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
	url = 'phpajax/datagrid_delivery.php?action=cusorderdelv&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
	}
	else
	{
	url = 'phpajax/datagrid_delivery.php?action=cusorderdelv&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
	url = 'phpajax/datagrid_delivery.php?action=cusorderdelv';
	table_with_filter(url);
});
	
//ENDS DATE FILTER START				


			
			
$("#reset-date").click(function(){
    $('#cal_orderdate').val("");
	url = 'phpajax/datagrid_delivery.php?action=cusorderdelv';
	table_with_filter(url);
	return false;
})			
        			
			
			
			

        });// $(document).ready(function(){

        </script>
		
		
        <script>

function update_grid_status_menu(thisvalue,id, status_id){
    //alert(status_id);
	var dealdata = { dataid:id,statusid: status_id, modulename : 'order', colname : 'orderstatus', selectedvalue : thisvalue}
	var saveData = $.ajax({
		  type: 'POST',
		  url: "phpajax/update_order_status.php?action=orderstatus",
		  data: dealdata,
		  dataType: "text",
		  success: function(resultData) { messageAlert(resultData) }
	});
	saveData.error(function() { messageAlert("Something went wrong"); });

}

</script>


<script>

		function putClass(){
		$("#listTable tbody tr").each(function(){

			//clsStage  = $(this).find("input[type=hidden].stage").attr("class");
			clsStatus = $(this).find("input[type=hidden].status").attr("class");
			//$(this).find("input[type=hidden]").attr("class","");

		//	$(this).find("td:nth-child(5)").attr("class",clsStage);
			$(this).find("td:nth-child(5)").attr("class",clsStatus);
			clsStatus = '';
			clsStage = '';
			//alert(cls);
			});









	$(".status .dropdown-menu a").on("click", function(){

		//alert($(this).html());

		myClass = $(this).attr("class");


		root = $(this).parent().parent().parent().parent().parent();
		root.removeClass();
		root.addClass("status "+myClass);
		root.find("a span").html($(this).html()+"<span class=\"caret\"></span>");

		id = root.find("a").data("id");
		status_id = $(this).data("statusid");
		//alert('xx'+status_id);
		//call ajax function for posting data
		update_grid_status_menu($(this).html(),id, status_id);
	});



}

//setTimeout(function(){ putClass(); }, 1000);

		</script>
		
		
		
    <?php

    if ($_REQUEST['msg']){
        echo "<script type='text/javascript'>messageAlert('".$_REQUEST['msg']."')</script>"; 
    }


?>		
		
		
    </body></html>
  <?php } ?>
