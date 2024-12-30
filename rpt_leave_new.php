<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

$empid = $_POST["cmborg"];
if($empid != ''){
    $qryemp= "SELECT `id`, concat(`firstname`, ' ', `lastname`) empname FROM `employee` where id = ".$empid;
    $resultemp = $conn->query($qryemp);
    while ($rowemp = $resultemp->fetch_assoc()) {
        $empname = $rowemp["empname"];
    }
    
}else{
    $empid = 0;
}

$fdt = $_POST['filter_date_from'];
$tdt = $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("01/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_leave';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/hraction.php?res=0&msg='Insert Data'&mod=4");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'Apply Date')
            ->setCellValue('C1', 'Employee NAME')
            ->setCellValue('D1', 'Designation')
            ->setCellValue('E1', 'Department')
            ->setCellValue('F1', 'Leave Type')
            ->setCellValue('G1', 'No of Days')
            ->setCellValue('H1', 'Start Date')
            ->setCellValue('I1', 'End Date')
            ->setCellValue('J1', 'Approver')
            ->setCellValue('K1', 'Approve Date');

        $firststyle = 'A2';
        $qry        = "SELECT l.hrid,h1.hrName
,(select name from designation where id= ha.`designation`) desig
,(select name from department where ID= ha.`postingdepartment`) dept
,DATE_FORMAT(l.applieddate,'%d/%c/%Y') applydt, lt.title,DATEDIFF(l.endday,l.startday)+1 days,DATE_FORMAT(l.startday,'%d/%c/%Y') startday,DATE_FORMAT(l.endday,'%d/%c/%Y') endday,h.hrName approver
,DATE_FORMAT(l.approvedate,'%d/%c/%Y') approvedate
FROM  `leave` l, leaveType lt,hr h ,hr h1,hraction ha ,employee e
where l.leavetype=lt.id
and l.approver=h.id
and l.hrid=h1.id
and h1.emp_id=e.employeecode
and e.id=ha.hrid
and l.applieddate BETWEEN STR_TO_DATE('" . $fdt . "','%d/%m/%Y') and  STR_TO_DATE('" . $tdt . "','%d/%m/%Y')
order by l.applieddate";
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
                    ->setCellValue($col2, $row['applydt'])
                    ->setCellValue($col3, $row['hrName'])
                    ->setCellValue($col4, $row['desig'])
                    ->setCellValue($col5, $row['dept'])
                    ->setCellValue($col6, $row['title'])
                    ->setCellValue($col7, $row['days'])
                    ->setCellValue($col8, $row['startday'])
                    ->setCellValue($col9, $row['endday'])
                    ->setCellValue($col10, $row['approver'])
                    ->setCellValue($col11, $row['approvedate']);
                /* */
                //$laststyle=$title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Leave');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'leave' . $today . '.xls';
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
        <span>Leave Sheet</span>
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

                	<form method="post" action="rpt_leave.php?pg=1&mod=4" id="form1">

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
                            <h6>HRM <i class="fa fa-angle-right"></i>Leave Record</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="form-group">
                                <label for="cmbcontype">Employee Name</label>
                                <div class="form-group styled-select">
                                    <input list="cmborg1" name ="cmbassign2" value = "<?= $empname ?>" autocomplete="Search From list"  class="dl-cmborg datalist" placeholder="" >
                                    <datalist  id="cmborg1" name = "cmborg1" class="list-cmbassign form-control" >
                                        
                        <?php $qryitm="SELECT `id`, concat(`firstname`, ' ', `lastname`) empname FROM `employee` order by empname"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
                                  {
                                      $tid= $rowitm["id"];  $nm=$rowitm["empname"]; ?>
                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
                        <?php  }}?>                    
                                    </datalist> 
                                    <input type = "hidden" name = "cmborg" id = "cmborg" value = "<?= $empid ?>">
                                </div>
                            </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter datepicker" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt; ?>" >
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter datepicker" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt; ?>"  >
                            </div>
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">
                            </div>
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
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
                    <table id='listTable' class='display dataTable actionbtn firstcolpad0' width="100%">
                        <thead>
                        <tr>
                            <th>Apply Date</th>
                            <th>Employee ID</th>
                            <th>Employee</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Leave Type</th>
                            <th>No of Day</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Approved By</th>
                            <th>Approved Date</th>

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
				pageLength: 25,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,
				/*'searching': true,*/
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=rptleave&fd=<?php echo $fdt; ?>&td=<?php echo $tdt; ?>&empid=<?= $empid ?>'
                },
                'columns': [
                    { data: 'applydt' },
                    { data: 'hrid' },
                    { data: 'hrName' },
                    { data: 'desig' },
					{ data: 'dept' },
					{ data: 'title' },
					{ data: 'days' },
					{ data: 'startday' },
					{ data: 'endday' },
					{ data: 'approver' },
					{ data: 'approvedate' }
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
    //Searchable dropdown
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
        $('#cmborg').val(id);
        
	
	});
</script>


    </body></html>
  <?php } ?>
