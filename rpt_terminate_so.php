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
    $currSection = 'rptsalestyerminate';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/rpt_terminate_so.php?res=0&msg='Insert Data'");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Termination Date.')
            ->setCellValue('B1', 'Termination Cause')
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
            ->setCellValue('M1', 'OTC');

        $firststyle = 'A2';
        $qry        = "SELECT DATE_FORMAT(s.`terminationDate`, '%d-%m-%Y') tdt,c.name `terminationcause`,h.hrName,i.name itmnm
,ic.name itmcat,p.name comtp,i.size ,o.name ornm,s.socode,DATE_FORMAT(s.effectivedate , '%d-%m-%Y') efdt,cr.shnm,round(d.otc,2) otc,round(d.mrc,2) mrc
FROM soitem s left join soitemdetails d on s.`socode`= d.`socode`
	left join terminationcause c on s.`terminationcause`=c.id
    left join organization o on s.`organization`=o.id
    left join hr h on o.`salesperson`=h.`id`
    left join item i on d.`productid`=i.`id`
    left join itmCat ic on i.`catagory`=ic.id
    left join pattern p on i.`pattern`=p.`id`
    left join currency cr on d.currency=cr.id
WHERE    `terminationDate`<sysdate()
order by s.`socode`";
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
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $row['tdt'])
                    ->setCellValue($col2, $row['terminationcause'])
                    ->setCellValue($col3, $row['hrName'])
                    ->setCellValue($col4, $row['itmnm'])
                    ->setCellValue($col5, $row['itmcat'])
                    ->setCellValue($col6, $row['size'])
                    ->setCellValue($col7, $row['comtp'])
                    ->setCellValue($col8, $row['ornm'])
                    ->setCellValue($col9, $row['socode'])
                    ->setCellValue($col10, $row['efdt'])
                    ->setCellValue($col11, $row['shnm'])
                    ->setCellValue($col12, $row['mrc'])
                    ->setCellValue($col13, $row['otc']); /* */
                $laststyle = $title;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('termination_list');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'termination_list_' . $today . '.xls';
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
        <!-- <span>All Item</span> -->
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
      			<!-- <div class="panel-heading"><h1 class="left-align">All Terminated Sales</h1></div> -->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="rpt_terminate_so.php" id="form1">

                     <div class="well list-top-controls">
                    
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>CRM <i class="fa fa-angle-right"></i> All Terminated Sales Report </h6>
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

                <div cla ss="table-responsive">
                    <!-- Table -->
                    <table id='crmActivityTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th>Termination Date</th>
                            <th>Termination Cause</th>
                            <th>Account Manager</th>
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
                    { data: 'tdt' },
                    { data: 'terminationcause' },
                    { data: 'hrName' },
                    { data: 'itmnm' },
                    { data: 'itmcat' },
                    { data: 'size' },
					{ data: 'comtp' },
				    { data: 'ornm' },
                    { data: 'socode' },
                    { data: 'efdt' },
                    { data: 'shnm' },
                    { data: 'mrc' },
                    { data: 'otc' }
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
	url = 'phpajax/datagrid_report.php?action=terminat_so';
	table_with_filter(url);	

			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        

        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
				var pdfurl = "pdf_terminat_so.php";
				location.href=pdfurl;
				
			});
			
		</script>

    </body></html>
  <?php } ?>