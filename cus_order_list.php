<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    $fd1    = $_POST['from_dt'];
    $td1    = $_POST['to_dt'];
    $dagent = $_POST['cmbsupnm'];
    $ost    = $_POST['cmbordstatus'];

    if ($fd1 == '') {$fd1 = date("1/m/Y");}
    if ($td1 == '') {$td1 = date("d/m/Y");}
    if ($dagent != '') {$pqry = " and o.`deleveryagent` =" . $dagent;} else { $pqry = '';}
    if ($ost != '') {$osqry = " and o.`orderstatus` =" . $ost;} else { $osqry = '';}
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'cus_order_list';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/custorder.php?res=0&msg='Insert Data'&mod=13");
    }
    if (isset($_POST['export'])) {
        //echo "yes"; die;
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL')
            ->setCellValue('B1', 'ORDER NO')
            ->setCellValue('C1', 'CUSTOMER NAME')
            ->setCellValue('D1', 'CELL NO')
            ->setCellValue('E1', 'ORDER DATE')
            ->setCellValue('F1', 'ORDER STATUS')
            ->setCellValue('G1', 'AMOUNT')
            ->setCellValue('H1', 'PAYMENT MODE')
            ->setCellValue('I1', 'DELIVERY AGENT');

        $firststyle = 'A2';
         
        $qry        = "SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
                        ,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr
                        ,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent,concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno,org.email
                        FROM  soitem o left join orderstatus s on o.orderstatus=s.id
                         left join organization org on o.organization=org.id
                        left join district d on org.district=d.id
                        left join area a on org.area=a.id
                        left join contact c on o.customer=c.id
                        left join district d1 on c.district=d1.id
                        left join area a1 on c.area=a1.id
                    	left join deveryagent da on o.deliveryby=da.id
                        where 1=1 and o.orderdate BETWEEN STR_TO_DATE('".$fd1."','%d/%m/%Y') and  STR_TO_DATE('".$td1."','%d/%m/%Y') ".$pqry.$osqry;
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
                    ->setCellValue($col3, $row['name'])
                    ->setCellValue($col4, $row['phone'])
                    ->setCellValue($col5, $row['order_date'])
                    ->setCellValue($col6, $row['ost'])
                    ->setCellValue($col7, $row['amount'])
                    ->setCellValue($col8, $row['payment_mood'])
                    ->setCellValue($col9, $row['payst'])
                    ->setCellValue($col10, $row['agentname']); /* */
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
             <span class="alertmsg"></span>
              <!--h1 class="page-title">Customers</a></h1-->
              <p>
              <!-- START PLACING YOUR CONTENT HERE -->
                  <div class="panel panel-info">
          			   <div class="panel-heading"><h1>All Orders</h1></div>
        				<div class="panel-body">

                        	<form method="post" action="cus_order_list.php?mod=13" id="form1">
								<div class="topreportbar">


                        	    <div class="row">

                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label >Date From</label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">

                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="from_dt" id="from_dt" value="<?php echo $fd1; ?>"  required>


                                </div>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label >Date To</label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">

                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="to_dt" name="to_dt"  value="<?php echo $td1; ?>" required>

                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbsupnm" id="cmbsupnm" class="form-control" >
                                            <option value="">Delivery Agent </option>
<?php
$qry1    = "SELECT `id`, `name`  FROM `deveryagent` order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($dagent == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
<?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbordstatus" id="cmbordstatus" class="form-control" >
                                            <option value="">Order Status </option>
<?php
$qry1    = "SELECT `id`, `name`  FROM `orderstatus` order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($ost == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
<?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
							<div class="col-lg-2 col-md-6 col-sm-6">
                                <input  dat a-to="pagetop" class="btn btn-md btn-default top" type="submit" name="view" value="Load" id="view"  >
                                <input  dat a-to="pagetop" class="btn btn-md btn-default top" type="submit" name="export" value="Export" id="export">
                            <!--<input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')"> -->
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
                                <th>Order Number</th>
                                <th>Customer Name</th>
                                <!--<th>Shipping Address </th>
                                <th>Email</th>-->
                                <th>Cell No </th>
                                <th>Order Date</th>
                                <th>Order Status</th>
                                <th>Amount </th>
                                <th>Payment Mode</th>
                                <th>Delivery Agent</th>
                                <th></th>
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
            $('#listTable').on( 'draw.dt',  function () { putClass(); } )
				.DataTable({
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
                    'url':'phpajax/datagrid_list_all.php?action=orderlist&lvl=2&fdt=<?php echo $fd1; ?>&tdt=<?php echo $td1; ?>&dagnt=<?php echo $dagent; ?>&odst=<?php echo $ost; ?>'
                },
                'columns': [
                    { data: 'order_id' },
                    { data: 'name' },
                   // { data: 'addrs' },
                    //{ data: 'email' },
                    { data: 'phone' },
                    { data: 'order_date' },
                    { data: 'status' },
                    { data: 'amount' },
                    { data: 'paymd' },
                    { data: 'agent' },
                    { data: 'edit' , orderable: false, className: "btncol"}
                ]
            });

			setTimeout(function(){
				$('#listTable').DataTable().draw();
			},300);

        });

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

setTimeout(function(){ putClass(); }, 1000);

		</script>
    </body></html>
  <?php } ?>
