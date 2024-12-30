<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

$fdt = $_POST['filter_date_from'];
$pyr=$_POST['cmbyr'];
$pmn=$_POST['cmbmonth']; if($pmn == '') $pmn = date('m');
$pmn = str_pad($pmn, 2, "0", STR_PAD_LEFT);
//echo $pmn;die;
//$tdt= $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("d/m/Y");}
//if($tdt==''){$tdt=date("d/m/Y");}

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_balance_sheet';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/hraction.php?res=0&msg='Insert Data'&mod=4");
    }
    if (isset($_POST['export'])) {

        //Sub Query
        $assetqry    = "select @asset=COALESCE(closingBal,0) asset from coa_mon where glno='100000000' and mn='$pmn' and yr='$pyr'";
        $assetresult = mysqli_query($con, $assetqry);
        while ($assetrow = mysqli_fetch_assoc($assetresult)) {
            $asset = $assetrow["asset"];
        }

        $liabilityqry    = "select @liability =COALESCE(closingBal,0) liability from coa_mon where glno='200000000' and mn='$pmn' and yr='$pyr'";
        $liabilityresult = mysqli_query($con, $liabilityqry);
        while ($liabilityrow = mysqli_fetch_assoc($liabilityresult)) {
            $liability = $liabilityrow["liability"];
        }

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'Asset/Liability')
           // ->setCellValue('C1', 'Amount')
            ->setCellValue('C1', 'Level')
            ->setCellValue('D1', 'GL Account')
            ->setCellValue('E1', 'GL Name')
            ->setCellValue('F1', 'Closing Balance')
            //->setCellValue('G1', 'P')
            ;
        
        $firststyle = 'A2';
        $qry        = "select
                                (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                            	from coa_mon a 
                            	where substring(a.glno,1,1) in('1','2') 
                               and mn='$pmn' and yr='$pyr' and a.status='A'
                            	order by a.glno";
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
               
                $i++;
                if ($row["dr_cr"] == 'C') {
                    $dr = $row["amount"];
                    $cr = 0;
                } else {
                    $cr = $row["amount"];
                    $dr = 0;
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['asslib'])
                    //->setCellValue($col3, $row['assLib_amount'])
                    ->setCellValue($col3, $row['lvl'])
                    ->setCellValue($col4, $row['glno'])
                    ->setCellValue($col5, $row["glnm"])
                    ->setCellValue($col6, $row["closingBal"])
                    //->setCellValue($col8, $row["p"])
                    ;

                //$laststyle=$title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Balance Sheet');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'rpt_balance_sheet' . $today . '.xls';
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
        <span>Balance Sheet Report</span>
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

                	<form method="post" action="rpt_balance_sheet.php?pg=1&mod=7" id="form1">

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
                            <h6>Account <i class="fa fa-angle-right"></i>Report<i class="fa fa-angle-right"></i>Balance Sheet Report</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">

                            
                                <div class="form-group">
                                    <select name="cmbyr" id="cmbyr" class="form-control" required>
<?php $yr=date("Y");?>          
                                        <option value="<? echo $yr-1; ?>" <? if ($pyr == $yr-1) { echo "selected"; } ?>><? echo $yr-1; ?></option>
                                        <option value="<? echo $yr; ?>" <? if ($pyr == $yr) { echo "selected"; } ?>><? echo $yr; ?></option>
                                        <option value="<? echo $yr+1; ?>" <? if ($pyr == $yr+1) { echo "selected"; } ?>><? echo $yr+1; ?></option>
                                    </select>
                                </div>
                               
                                <div class="form-group">
                                        <select name="cmbmonth" id="cmbmonth" class="form-control" required>
<?php $mon= date('F');for($i=1;$i<=12;$i++){?>          
                                            <option value="<? echo  str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <? if (str_pad($i, 2, "0", STR_PAD_LEFT) == $pmn) { echo "selected"; } ?>><? echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
<?php } ?>                    
                                        </select>
                                    </div>
                                

                            <!--div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter datepicker" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt; ?>" >
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt; ?>"  >
                            </div-->
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">
                            </div>
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
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

                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable actionbtn firstcolpad0' width="100%">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Asset/Liability</th>
                            <!--th>Amount</th-->
                            <th>Level</th>
                            <th>GL Account</th>
                            <th>GL Name</th>
                            <th>Closing Balance</th>
                            <!--th>P</th-->
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
            var table1 = $('#listTable').DataTable({
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 100,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,
				/*'searching': true,*/
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=rpt_balance_sheet&fdt=<?php echo $fdt; ?>&fyr=<?php echo $pyr; ?>&fmn=<?php echo $pmn; ?>'
                },
                'columns': [
                    { data: 'id' },
                    { data: 'asslib' },
                  //  { data: 'assLib_amount' },
                    { data: 'lvl' },
                    { data: 'glno' },
                    { data: 'glnm' },
					{ data: 'closingBal' },
				//	{ data: 'p' },
                ]
            });

            setTimeout(function(){
                table1.columns.adjust().draw();
            }, 350);

             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
        });



        </script>
        
        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
				
				var pdfurl = "pdf_balance_sheet_report.php?fdt=<?php echo $fdt; ?>&fyr=<?php echo $pyr; ?>&fmn=<?php echo $pmn; ?>";
				location.href=pdfurl;
				
			});
			
		</script>


    </body></html>
  <?php } ?>
