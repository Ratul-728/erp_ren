<?php
require "common/conn.php";

$fromdt = '2015-01-01';
$todt   = '2020-12-31';

//echo $lyr;die;
session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_sof';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/rpt_sof.php?res=0&msg='Insert Data'");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Contact Type')
            ->setCellValue('B1', 'Customer')
            ->setCellValue('C1', 'Account Manager')
            ->setCellValue('D1', 'Item')
            ->setCellValue('E1', 'Item Catagory')
            ->setCellValue('F1', 'Company Type')
            ->setCellValue('G1', 'License Type')
            ->setCellValue('H1', 'Organization')
            ->setCellValue('I1', 'SO Code')
            ->setCellValue('J1', 'Effective Date')
            ->setCellValue('K1', 'Currency')
            ->setCellValue('L1', 'MRC')
            ->setCellValue('M1', 'OTC')
            ->setCellValue('N1', 'Stage')
            ->setCellValue('O1', 'Probability')
            ->setCellValue('P1', 'Status')
            ->setCellValue('Q1', 'POC');

        $firststyle = 'A2';
        $qry        = "SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, a.`effectivedate` orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,cr.shnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
,round((IFNULL(b.`mrc`,0)*IFNULL(`qtymrc`,0)),2) mrc,'Order Placed' stage,'100%' prob ,f.`name` itm_cat
,c.size,g.`name` pattern,org.`name`  orgn , concat(e1.firstname,'',e1.lastname) `poc` FROM `soitem` a left join `soitemdetails` b on a.`socode`=b.`socode` left join `item` c on b.`productid`=c.`id` left join `contact` d on a.`customer`=d.`id`   left join `itmCat` f  on c.`catagory`=f.`id`
left join `pattern` g on c.`pattern`=g.`id`left join organization org on a.`organization`=org.`id`
left join `hr` e on org.`salesperson`=e.`id`  left join employee em on e.`emp_id`=em.`employeecode`
left join `hr` h1 on a.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
left join currency cr on b.currency=cr.id
where  (a.terminationDate>sysdate() or a.terminationDate is null)";
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
                $col13 = 'M' . $urut;
                $col14 = 'N' . $urut;
                $col15 = 'O' . $urut;
                $col16 = 'P' . $urut;
                $col17 = 'Q' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $row['contType'])
                    ->setCellValue($col2, $row['cus_nm'])
                    ->setCellValue($col3, $row['hrName'])
                    ->setCellValue($col4, $row['itmnm'])
                    ->setCellValue($col5, $row['itm_cat'])
                    ->setCellValue($col6, $row['size'])
                    ->setCellValue($col7, $row['pattern'])
                    ->setCellValue($col8, $row['orgn'])
                    ->setCellValue($col9, $row['socode'])
                    ->setCellValue($col10, $row['orderdate'])
                    ->setCellValue($col10, $row['shnm'])
                    ->setCellValue($col11, $row['mrc'])
                    ->setCellValue($col12, $row['otc'])
                    ->setCellValue($col13, $row['stage'])
                    ->setCellValue($col14, $row['Probability'])
                    ->setCellValue($col15, $row['stat'])
                    ->setCellValue($col15, $row['poc']); /* */
                $laststyle = $title;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('Sales_Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'sales_data_' . $today . '.xls';
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
        <span>All Item</span>
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
      			<!-- <div class="panel-heading"><h1 class="left-align">All Sales</h1></div> -->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="rpt_sof.php" id="form1">

                     <div class="well list-top-controls">
                    
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>CRM <i class="fa fa-angle-right"></i> Sales Report </h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">

                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            <div class="form-group">
                            
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

                <div cla ss="table-responsive" >
                    <!-- Table -->
                    <table id='crmActivityTable' class=' table table-bordered table-hover dt-responsive' width="100%">
                        <thead>
                        <tr>
                            <th>Contact Type</th>
                            <th>Customer</th>
                            <th>Account Manager</th>
                            <th>Item</th>
                            <th>Item Catagory</th>
                            <th>Company Type</th>
                            <th>Linecse Type</th>
                            <th>Organization</th>
                            <th>SO Code</th>
                            <th>POC</th>
                            <th>Effective Date</th>
                            <th>Currency</th>
                            <th>MRC</th>
                            <th>OTC</th>
                            <th>Stage</th>
                            <th>Probability</th>
                            <th>Status</th>
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

        
        <script>

$(document).ready(function(){			
			
function table_with_filter(url){
	
        	 var table1 =  $('#crmActivityTable').DataTable().destroy();
             var table1 = $('#crmActivityTable').DataTable({
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
                    { data: 'contType' },
                    { data: 'cus_nm' },
                    { data: 'hrName' },
                    { data: 'itmnm' },
                    { data: 'itm_cat' },
					{ data: 'size' },
				    { data: 'pattern' },
                    { data: 'orgn' },
                    { data: 'socode' },
                    { data: 'poc' },
                    { data: 'orderdate' },
                    { data: 'shnm' },
                    { data: 'pmrc' },
                    { data: 'otcvalue' },
					{ data: 'stage' },
				    { data: 'prob' },
                    { data: 'stat' }
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
	url = 'phpajax/datagrid_report.php?action=rpt_sof';
	table_with_filter(url);	
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        

        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
			    
				var pdfurl = "pdf_sof.php";
				location.href=pdfurl;
				
			});
			
		</script>

    </body></html>
  <?php } ?>