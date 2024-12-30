<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

$month = date('n');


if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'customer_birthdate';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/hraction.php?res=0&msg='Insert Data'&mod=3");
    }
	//Excel Export
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'SO ID')
            ->setCellValue('C1', 'Source Type')
            ->setCellValue('D1', 'Customer')
             ->setCellValue('E1', 'Organization')
              ->setCellValue('F1', 'Order Date')
               ->setCellValue('G1', 'HR Name')
               ->setCellValue('H1', 'POC')
                ->setCellValue('I1', 'Status')
                ->setCellValue('J1', 'Amount');

        $firststyle = 'A2';
        $qry        = "SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`,
                                        concat(e.firstname,'',e.lastname) `hrName` , concat(e1.firstname,'',e1.lastname) `poc`,st.id stid ,st.name stnm,s.invoiceamount `amount` 
                                        FROM `soitem` s left join `contacttype` tp on s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` 
                                        left join `organization` o on o.`orgcode`=c.organization left join `hr` h on o.`salesperson`=h.`id` 
                                        left join employee e on h.`emp_id`=e.`employeecode` left join `hr` h1 on s.`poc`=h1.`id` 
                                        left join employee e1 on h1.`emp_id`=e1.`employeecode` left join orderstatus st on s.orderstatus=st.id 
                                        WHERE s.orderstatus=3 ";
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
                    ->setCellValue($col2, $row['socode'])
                    ->setCellValue($col3, $row['srctype'])
                    ->setCellValue($col4, $row["customer"])
                    ->setCellValue($col5, $row["organization"])
                    ->setCellValue($col6, $row["orderdate"])
                    ->setCellValue($col7, $row["hrName"])
                    ->setCellValue($col8, $row["poc"])
                    ->setCellValue($col9, $row["stnm"])
                    ->setCellValue($col10, $row["amount"]);

                //$laststyle=$title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Sold Stock Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'sold_stock_report' . $today . '.xls';
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
        <span>Customer Birthday Report</span>
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
      			<!--<div class="panel-heading"><h1>All Hr Action</h1></div>-->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="rpt_sold_stock.php?pg=1&mod=3" id="form1">

                     <div class="well list-top-controls">
                    <!--  <div class="row border">

                        <div class="col-xs-6 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>

                        <div class="col-xs-6">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div>-->
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Sales <i class="fa fa-angle-right"></i>Customer Birthday Report</h6>
                       </div>



                        <div class="col-sm-9">

                        <div class="pull-right grid-panel form-inline">

                            <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>
                            
                            <div class="form-group">
                                <div class="form-group styled-select">
                                    <select name="month" id="month" class="form-control">
                                        <option value="1" <?php if($month == 1) echo "selected" ?>>January</option>
                                        <option value="2" <?php if($month == 2) echo "selected" ?>>February</option>
                                        <option value="3" <?php if($month == 3) echo "selected" ?>>March</option>
                                        <option value="4" <?php if($month == 4) echo "selected" ?>>April</option>
                                        <option value="5" <?php if($month == 5) echo "selected" ?>>May</option>
                                        <option value="6" <?php if($month == 6) echo "selected" ?>>June</option>
                                        <option value="7" <?php if($month == 7) echo "selected" ?>>July</option>
                                        <option value="8" <?php if($month == 8) echo "selected" ?>>August</option>
                                        <option value="9" <?php if($month == 9) echo "selected" ?>>September</option>
                                        <option value="10" <?php if($month == 10) echo "selected" ?>>October</option>
                                        <option value="11" <?php if($month == 11) echo "selected" ?>>November</option>
                                        <option value="12" <?php if($month == 12) echo "selected" ?>>December</option>
                                    </select>
                                </div>
                            </div>



                            
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">
                            </div>
                            
                            <!--div class="form-group exp-wrapper">
                            <input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export">
                            </div-->

							</div>

                        </div>


                      </div>
                    </div>


    				</form>


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable actionbtn firstcolpad0' width="100%">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Organization Name</th>
                            <th>Customer Name</th>
                            <th>Birthdate</th>
                            <th>Contact No</th>
                            <th>Email</th>
                            <th>Address</th>

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
    <?php

    if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }

    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>

     <!-- Datatable JS -->
		<script src="js/plugins/datagrid/datatables.min.js"></script>

        <!-- Script -->
        <script>

$(document).ready(function(){			
			
function table_with_filter(url){
	        var ch = 1;
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
				"order": [[ 0, "asc" ]],
				"dom": "rtiplf",
                'ajax': {
                    
					'url':url,
                },
                
				'columns': [
                    { data: 'id' },
                    { data: 'orgname' },
                    { data: 'customernm' },
					{ data: 'dob' },
					{ data: 'phone' },
					{ data: 'email' },
					{ data: 'area' },
                ],
				 
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
	url = 'phpajax/datagrid_list_all.php?action=customer_birthdate&month=<?= $month?>';
	table_with_filter(url);	

	
	
	
	//Month filter
        $("#month").on("change", function() {

            var month = $(this).val();

			url = 'phpajax/datagrid_list_all.php?action=customer_birthdate&month='+month;
			

            
			
            setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
        

			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script>
        
		
	


    </body></html>
  <?php } ?>
