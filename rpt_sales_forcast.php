<?php
require "common/conn.php";

$fromdt = '2015-01-01';
$todt   = '2020-12-31';
$smn    = date("m", strtotime($fromdt));
$lmn    = date("m", strtotime($todt));
$lyr    = date("Y", strtotime($todt));
//echo $lyr;die;
session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'salesforcast';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/rpt_sales_forcast.php?res=0&msg='Insert Data'");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Month.')
            ->setCellValue('B1', 'POC')
            ->setCellValue('C1', 'Customer')
            ->setCellValue('D1', 'Account Manager')
            ->setCellValue('E1', 'Item')
            ->setCellValue('F1', 'Item Catagory')
            ->setCellValue('G1', 'Company Type')
            ->setCellValue('H1', 'License Type')
            ->setCellValue('I1', 'Organization')
            ->setCellValue('J1', 'SO Code')
            ->setCellValue('K1', 'Effective Date')
            ->setCellValue('L1', 'Currency')

            ->setCellValue('M1', 'MRC')
            ->setCellValue('N1', 'OTC')
            ->setCellValue('O1', 'Stage')
            ->setCellValue('P1', 'Probability')
            ->setCellValue('Q1', 'Status')
            ->setCellValue('R1', 'Forcast')
        ; /*->setCellValue('S1', 'POC') */

        $firststyle = 'A2';
        $qry        = "SELECT s.`socode`, s.`contType`, s.`cus_id`, s.`cus_nm`, s.`orderdate`, s.`yr`, s.`mnth`, s.`da`, s.`hrid`, s.`hrName`, s.`itmid`, s.`itmnm`, s.`otc`, s.`mrc`, s.`stage`, s.`prob`, s.`itm_cat`, s.`size`, s.`pattern`, s.`orgn`,r.yr,r.month,r.dy,r.`dt`
,(case when r.yr=s.yr and r.month=s.mnth then 'New' Else 'Existing' end ) stat
,(case when r.yr=s.yr and r.month=s.mnth then round((s.`mrc`*(r.dy-s.`da`+1))/r.dy,2) Else s.`mrc` end ) pmrc
,(case when r.yr=s.yr and r.month=s.mnth then round(s.`otc`,2) Else 0 end ) otcvalue
,(case when r.`dt`>sysdate() then 'Forcast' else 'Actual' end) frcst
,s.poc,s.currnm
FROM  `rpt_sales_so` s  ,`reportmanth` r
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr))";
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
                $col18 = 'R' . $urut; //$col19='S'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $row['dt'])
                    ->setCellValue($col2, $row['poc'])
                    ->setCellValue($col3, $row['cus_nm'])
                    ->setCellValue($col4, $row['hrName'])
                    ->setCellValue($col5, $row['itmnm'])
                    ->setCellValue($col6, $row['itm_cat'])
                    ->setCellValue($col7, $row['size'])
                    ->setCellValue($col8, $row['pattern'])
                    ->setCellValue($col9, $row['orgn'])
                    ->setCellValue($col10, $row['socode'])
                    ->setCellValue($col11, $row['orderdate'])
                    ->setCellValue($col12, $row['currnm'])
                    ->setCellValue($col13, $row['pmrc'])
                    ->setCellValue($col14, $row['otcvalue'])
                    ->setCellValue($col15, $row['stage'])
                    ->setCellValue($col16, $row['Probability'])
                    ->setCellValue($col17, $row['stat'])
                    ->setCellValue($col18, $row['frcst'])
                ; /*->setCellValue($col19, $row['poc'])   */
                $laststyle = $title;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('sales_forcast');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'sales_forcast_' . $today . '.xls';
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

                	<form method="post" action="rpt_sales_forcast.php" id="form1">

                     <div class="well list-top-controls">
                    
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>CRM <i class="fa fa-angle-right"></i> Sales Forcast Report </h6>
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

                <div cl ass="table-responsive">
                    <!-- Table -->
                    <table id='crmActivityTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th>Month</th>
                            <th>Contact Type</th>
                            <th>Customer</th>
                            <th>Account Manager</th>
                            <th>POC</th>
                            <th>Item</th>
                            <th>Item Catagory</th>
                            <th>Company Type</th>
                            <th>Linecse Type</th>
                            <th>Organization</th>
                            <th>SO Code</th>
                            <th>Effective Date</th>
                            <th>Currency</th>
                            <th>MRC</th>
                            <th>OTC</th>
                            <th>Stage</th>
                            <th>Probability</th>
                            <th>Status</th>
                            <th>Forcast</th>
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
                    { data: 'dt' },
                    { data: 'contType' },
                    { data: 'cus_nm' },
                    { data: 'hrName' },
                    { data: 'poc' },
                    { data: 'itmnm' },
                    { data: 'itm_cat' },
					{ data: 'size' },
				    { data: 'pattern' },
                    { data: 'orgn' },
                    { data: 'socode' },
                    { data: 'orderdate' },
                    { data: 'currnm' },
                    { data: 'pmrc' },
                    { data: 'otcvalue' },
					{ data: 'stage' },
				    { data: 'prob' },
                    { data: 'stat' },
					{ data: 'frcst' }
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
	url = 'phpajax/datagrid_report.php?action=sales_forcast';
	table_with_filter(url);	
			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        

        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				var pdfurl = "pdf_sales_forcast.php";
				location.href=pdfurl;
				
			});
			
		</script>

    </body></html>
  <?php } ?>