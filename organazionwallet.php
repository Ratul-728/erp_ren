<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();
$usr   = $_SESSION["user"];
$orgid = $_GET['orgid'];
//echo $orgid;die;
$wor = $_POST['worgid'];

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'wallet';
    include_once('common/inc_session_privilege.php');
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/wallet.php?res=0&msg='Insert Data'&mod=3&orid=" . $orgid);
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'DATE')
            ->setCellValue('C1', 'CUSTOMER')
            ->setCellValue('D1', 'DEBIT/CREDIT')
            ->setCellValue('E1', 'TRANS MODE')
            ->setCellValue('F1', 'REFERENCE')
            ->setCellValue('G1', 'NARATION')
            ->setCellValue('H1', 'AMOUNT')
            ->setCellValue('I1', 'BALANCE');

        $firststyle = 'A2';

        $qry = "SELECT w.`id`,DATE_FORMAT(w.`transdt`, '%d/%b/%Y') `transdt`,o.name org,m.name `transmode`, (case w.`dr_cr` when 'd' then 'Debit' when 'C' then 'Credit' else ' ' end) drcr, w.`trans_ref`, w.`amount`, w.`remarks` ,w.balance FROM `organizationwallet` w left join organization o on w.`orgid`=o.id left join transmode m on w.`transmode`=m.id
where w.`orgid`='".$orgid."'  order by DATE_FORMAT(w.`transdt`, '%d/%b/%Y'),w.id asc ";
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
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['transdt'])
                    ->setCellValue($col3, $row['org'])
                    ->setCellValue($col4, $row['drcr'])
                    ->setCellValue($col5, $row['transmode'])
                    ->setCellValue($col6, $row['trans_ref'])
                    ->setCellValue($col7, $row['remarks'])
                    ->setCellValue($col8, $row['amount'])
                    ->setCellValue($col9, $row['balance']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('CUSTOMER LEDGER');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'customer_ledger' . $today . '.xls';
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
        <span>ACCOUNTING</span>
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
      			<div class="panel-heading"><h1 class="left-align">Wallet Transaction</h1></div>
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="organazionwallet.php?orgid=<?php echo $orgid ?>" id="form1">

                     <div class="well list-top-controls">
                      <div class="row border">

                        <!-- <div class="col-sm-11 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp;
                            <input type="hidden"  name="worgid" id="worgid" value="<?php echo $orgid; ?>">
                        </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>   -->
                         <div class="pull-right grid-panel form-inline">
                            <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">
                            </div>
                            <div class="form-group">
                                <?= getBtn('export') ?>
                            </div>
                            

                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                        </div>
                      </div>
                    </div>


    				</form>


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable actionbtn' width="100%">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Organization</th>
                            <th>Debit/Crerdit</th>
                            <th>Trans Mode</th>
                            <th>Reference </th>
                            <th>Narration </th>
                            <th>Amount </th>
                             <th>Balance </th>
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
    <!-- /#page-content-wrapper -->

    <?php
include_once 'common_footer.php';
    ?>

     <!-- Datatable JS -->
		<script src="js/plugins/datagrid/datatables.min.js"></script>

        <!-- Script -->
        <script>
        $(document).ready(function(){
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
				"order": [[ 0, "desc" ]],
				scroller: true,
                	"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_report.php?action=orgwall&orgid=<?php echo $orgid ?>'
                },
                'columns': [
                    { data: 'transdt' },
                    { data: 'org' },
                    { data: 'drcr' },
					{ data: 'transmode' },
                    { data: 'trans_ref' },
                    { data: 'remarks' },
                	{ data: 'amount' },
                	{ data: 'balance' },
                	{ data: 'edit', "orderable": false }
                ]
            });
             $('#search-dttable').keyup(
                 function(){
                     table1.search($(this).val()).draw();
                     //setTimeout(function(){ putClass(); }, 300);

             })
        });

$("#listTable").on("click",".slip-print",function(){

		
  	    mylink2 = $(this).attr('href');
		
   //alert(mylink);
  
  		BootstrapDialog.show({
							
							title: 'PAYMENT RECEIPT',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea4"></div>').load(mylink2),
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
								action: function(dialog2) {
									dialog2.close();	
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Print',
								hotkey: 13, // Enter.
								action: function(dialog2) {
									
									$("#printableArea4").printThis({
										importCSS: false, 
										importStyle: true,
									});
		
									
									dialog2.close();	
									
									},
								
							}],
							//onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});

        </script>

    </body></html>
  <?php } ?>
