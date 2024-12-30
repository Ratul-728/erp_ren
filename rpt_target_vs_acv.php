<?php
require "common/conn.php";

session_start();
$fdt = $_REQUEST["filter_date_from"];
$tdt = $_REQUEST["filter_date_to"];
if ($fdt == '') {$fdt = date("1/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}


$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpttaracv';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/rawitem.php?res=0&msg='Insert Data'");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Year.')
            ->setCellValue('B1', 'Month.')
            ->setCellValue('C1', 'Account manager')
            ->setCellValue('D1', 'Catagory')
            ->setCellValue('E1', 'Currency')
            ->setCellValue('F1', 'Target')
            ->setCellValue('G1', 'Achievement')
            ->setCellValue('H1', 'Achievement%');

        $firststyle = 'A2';
        $qry        = "select t.yr,t.mnth,h.`hrName` accmgr,i.name itmcatagory,ifnull(u.shnm,'BDT') cr,t.target target
,round((ifnull(u.acv,0)-ifnull(u.p_acv,0)),2)acv
from salestarget t left join (
SELECT
o.salesperson acm,i.catagory icat,DATE_FORMAT(si.`effectivedate`, '%Y') syr,DATE_FORMAT(si.`effectivedate`, '%m') smn
,sum((ifnull(d.qty,0)*ifnull(d.otc,0))+(ifnull(d.qtymrc,0)*ifnull(d.mrc,0))) acv
,sum(ifnull((select ((ifnull(d1.qty,0)*ifnull(d1.otc,0))+(ifnull(d1.qtymrc,0)*ifnull(d1.mrc,0))) from soitemdetails d1 where d1.socode=si.oldsocode and d1.productid=d.productid),0))p_acv
,cr.shnm
FROM soitem si join organization o on o.id=si.organization
 join soitemdetails d on si.socode=d.socode
 join item i on i.id=d.productid left join currency cr on d.currency=cr.id
  WHERE DATE_FORMAT(si.`effectivedate`, '%Y')>='2020'
group by o.salesperson,i.catagory,syr,smn,cr.shnm

)u on t.yr=u.syr and t.mnth=CONVERT(u.smn,UNSIGNED) and t.accmgr=u.acm and t.itmcatagory=u.icat
join `hr` h  on t.accmgr=h.id
join `itmCat` i on t.itmcatagory=i.`id`
where t.yr ='2020'";
        // echo  $qry;die;
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {$i = 0;
            while ($row = $result->fetch_assoc()) {
                $acvp = $row['acv'] / $row['target'] * 100;
                $urut = $i + 2;
                $col1 = 'A' . $urut;
                $col2 = 'B' . $urut;
                $col3 = 'C' . $urut;
                $col4 = 'D' . $urut;
                $col5 = 'E' . $urut;
                $col6 = 'F' . $urut;
                $col7 = 'G' . $urut;
                $col8 = 'H' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $row['yr'])
                    ->setCellValue($col2, $row['mnth'])
                    ->setCellValue($col3, $row['accmgr'])
                    ->setCellValue($col4, $row['itmcatagory'])
                    ->setCellValue($col5, $row['cr'])
                    ->setCellValue($col6, $row['target'])
                    ->setCellValue($col7, $row['acv'])
                    ->setCellValue($col8, $acvp); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Target_Achievment');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'tar_acv' . $today . '.xls';
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

      <!-- <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>All Item</span>
      </div> -->

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
      			<!-- <div class="panel-heading "><h1 class="left-align">All Item</h1></div> -->
    				<div class="panel-body">

                         <span class="alertmsg">
    </span>

                	<form method="post" action="rpt_target_vs_acv.php?mod=2" id="form1">

                     <div class="well list-top-controls">
                    
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>CRM<i class="fa fa-angle-right"></i> Target VS Acheivment Report </h6>
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
                        </div>

                        </div>


                      </div>
                    </div>

    				</form>

 <link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div cl ass="table-responsive">
                    <!-- Table -->
                    <table id='crmActivityTable' class=' table table-bordered table-hover dt-responsive' width="100%">
                        <thead>
                        <tr>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Account Manager</th>
                            <th>Item Catagory</th>
                             <th>Currency</th>
                            <th>Target</th>
                            <th>Achievement</th>
                            <th>% Achievement</th>
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
                    { data: 'yr' },
                    { data: 'mnth' },
                    { data: 'accmgr' },
                    { data: 'itmcatagory' },
                    { data: 'crnc' },
                    { data: 'target' },
                    { data: 'acv' },
					{ data: 'acvper' }
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
	url = 'phpajax/datagrid_report.php?action=tar_acv';
	table_with_filter(url);	
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        

        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				var pdfurl = "pdf_target_vs_acv.php";
				location.href=pdfurl;
				
			});
			
		</script>

    </body></html>
  <?php } ?>
