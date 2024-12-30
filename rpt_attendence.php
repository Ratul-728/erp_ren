<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

$fdt = $_POST['filter_date_from'];
$tdt = $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("d/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}

$fdept = $_POST['stdept'];
$fst   = $_POST['sstatus'];

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_attendence';
    include_once('common/inc_session_privilege.php');
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/hraction.php?res=0&msg='Insert Data'&mod=4");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'Date')
            ->setCellValue('C1', 'Employee Name')
            ->setCellValue('D1', 'Designation')
            ->setCellValue('E1', 'Department')
            ->setCellValue('F1', 'Shift')
            ->setCellValue('G1', 'Office Time')
            ->setCellValue('H1', 'Status')
            ->setCellValue('I1', 'Entry Time')
            ->setCellValue('J1', 'Exit Time')
            ->setCellValue('K1', 'Duration');

        $firststyle = 'A2';
        $qry        = "select u.id id,u.dt ,DATE_FORMAT(u.dt,'%e/%c/%Y') trdt,u.hrName,u.ofctime,u.shift
,(select name from designation where id= ha.`designation`) desig
,(select name from department  where ID= ha.`postingdepartment`) dept
,(case when entrytm is null then (case when u.lv is null then (case when u.holiday is null then 'Absent' else u.holiday end)  else u.lv end)  else 'Present' end ) sttus
,u.entrytm,u.exittime,TIMEDIFF(IFNULL(exittime,entrytm),entrytm) durtn from
(
select d.dt,h.id,h.hrName,h.emp_id,e.id eid
,(select min(intime) from attendance where hrid=h.id and date=d.dt) entrytm
,(select (case when max(outtime) is null then max(intime) when max(intime)>max(outtime) then  max(intime) else max(outtime) end)  from attendance where hrid=h.id and date=d.dt) exittime
,(select title from Shifting where id=(select shift from assignshifthist where empid=e.id
and effectivedt =(select max(effectivedt) from assignshifthist where `empid`=e.id and `effectivedt`<=d.dt)))shift
,(select concat(`start`,' to ',`end`)  from OfficeTime where shift=(select shift from assignshifthist where empid=e.id
and effectivedt =(select max(effectivedt) from assignshifthist where `empid`=e.id and `effectivedt`<=d.dt)))ofctime
,(SELECT lt.title FROM  `leave` l, leaveType lt where l.leavetype=lt.id and  hrid=h.id
and d.dt BETWEEN l.startday and l.endday) lv
,(SELECT ht.title FROM  Holiday h,holidayType ht where h.holidaytype=ht.id and h.`date`=d.dt) holiday
from loggday d,hr h ,employee e
where
d.dt BETWEEN STR_TO_DATE('" . $fdt . "','%d/%m/%Y') and  STR_TO_DATE('" . $tdt . "','%d/%m/%Y')
and h.emp_id=e.employeecode
) u,hraction ha where u.eid=ha.hrid
order by u.dt";
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
                    ->setCellValue($col2, $row['trdt'])
                    ->setCellValue($col3, $row['hrName'])
                    ->setCellValue($col4, $row['desig'])
                    ->setCellValue($col5, $row['dept'])
                    ->setCellValue($col6, $row['shift'])
                    ->setCellValue($col7, $row['ofctime'])
                    ->setCellValue($col8, $row['sttus'])
                    ->setCellValue($col9, $row['entrytm'])
                    ->setCellValue($col10, $row['exittime'])
                    ->setCellValue($col11, $row['durtn']);
                /* */
                //$laststyle=$title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('attendence');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'attendence' . $today . '.xls';
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
        <span>Attendence Sheet</span>
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

                	<form method="post" action="rpt_attendence.php?pg=1&mod=4" id="form1">

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
                            <h6>HRM <i class="fa fa-angle-right"></i>Attendence Sheet</h6>
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
                            <!--<div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">
                            </div>


                            <!-- Department -->

						<div class="form-group">
						  <!--div class="form-group styled-select"-->
							<select name="stdept" id="stdept" class="form-control">
							  <option value="">Department</option>
	<?php
$qry8    = "SELECT * FROM `department` order by name ";
    $result8 = $conn->query($qry8);if ($result8->num_rows > 0) {while ($row8 = $result8->fetch_assoc()) {
        $tid8 = $row8["id"];
        $nm8  = $row8["name"];
        ?>
                                <option value="<?echo $tid8; ?>" <?php if ($tid8 == $fdept) {echo "selected";} ?>><?echo $nm8; ?></option>
    <?php }} ?>

							</select>
						  <!--/div-->
						</div>

				            <!-- Status -->

						<div class="form-group">
						  <!--div class="form-group styled-select"-->
							<select name="sstatus" id="sstatus" class="form-control" >
							  <option value="">Status</option>
                                <option value="Present" <?php if ($fst == 'Present') {echo "selected";} ?>>Present</option>
                                <option value="Absent" <?php if ($fst == 'Absent') {echo "selected";} ?>>Absent</option>

							</select>
						  <!--/div-->
						</div>

                            <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div>
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="form-group">
                                <?= getBtn('export') ?>
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
                            <th>Date</th>
                            <th>Day</th>
                            <th>Employee ID</th>
                            <th>Employee</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Shift</th>
                            <th>Office Time</th>
                            <th>Status</th>
                            <th>Entry Time</th>
                            <th>Exit Time</th>
                            <th>Office Duration</th>

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
				"order": [[ 0, "desc" ]],
				"dom": "rtiplf",
                'ajax': {
                    
					'url':url,
                },
                
				'columns': [
                   { data: 'sl' },
                    { data: 'dt' },
                    { data: 'day' },
                    { data: 'emp_id' },
                    { data: 'hrName' },
                    { data: 'desig' },
					{ data: 'dept' },
					{ data: 'shift' },
					{ data: 'ofctime' },
					{ data: 'sttus' },
					{ data: 'entrytm' },
					{ data: 'exittime' },
					{ data: 'durtn' }
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
	url = 'phpajax/datagrid_list_all.php?action=rptattendence&fdept=<?=$fdept ?>&fst=<?=$fst ?>&empid=<?= $empid ?>';
	table_with_filter(url);	

	
	
	
	
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
        	url = 'phpajax/datagrid_list_all.php?action=rptattendence&fdept=<?=$fdept ?>&fst=<?=$fst ?>&empid=<?= $empid ?>&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rptattendence&fdept=<?=$fdept ?>&fst=<?=$fst ?>&empid=<?= $empid ?>&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
        	url = 'phpajax/datagrid_list_all.php?action=rptattendence&fdept=<?=$fdept ?>&fst=<?=$fst ?>&empid=<?= $empid ?>';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	

			
			
			
        }); //$(document).ready(function(){	
		
		
		
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
