<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();
$usr=$_SESSION["user"];
$res= $_GET['res'];
$msg= $_GET['msg'];

if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rpt_attendance_all';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/rpt_attendance_form.php?res=0&msg='Insert Data'&mod=4"); 
    }
   if ( isset( $_POST['export'] ) ) {
        $dt_f = $_POST["from_dt"];
        $dt_t = $_POST["to_dt"];
        
        if($dt_f == '') $dt_f =date('Y-m-d', strtotime('-2 days'));
        if($dt_t == '') $dt_t =date('Y-m-d');
 
        $dt_range_str = ($dt_f && $dt_t)?" and a.date BETWEEN '".$dt_f."' AND '".$dt_t."'":"";
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Employee Code')
                ->setCellValue('C1', 'Employee Name')
                ->setCellValue('D1', 'Date')
    			->setCellValue('E1', 'Shift')
    			->setCellValue('F1', 'Start Time')
                ->setCellValue('G1', 'End Time')
                ->setCellValue('H1', 'In Time')
                ->setCellValue('I1', 'Out Time')
    			->setCellValue('J1', 'Late Time')
    			->setCellValue('K1', 'Early Time')
                ->setCellValue('L1', 'Working Hours')
                ->setCellValue('M1', 'Early Leave')
    			->setCellValue('N1', 'Status');
    			
        $firststyle='A2';
        
        $qry="select e.`employeecode`,concat(e.`firstname`,' ',e.`lastname`) nm
                                ,cl.`day` date,h.`attendance_id` hrid ,st.`title` shift,st.`starttime`,st.`exittime` 
                                ,a.`intime`,a.`outtime`
                                ,(case when a.`intime`>st.`starttime` then TIME_FORMAT(TIMEDIFF(a.`intime`,st.`starttime`), '%H:%i:%s') else 0 end)latetime
                                ,(case when  st.`exittime`>a.`outtime` then TIME_FORMAT(TIMEDIFF(st.`exittime`,a.`outtime`), '%H:%i:%s') else 0 end)earlytime
                                ,(case when a.`date` is null then (case when (SELECT count(`hrid`) hrd FROM `leave` where a.`date` between `startday` and `endday` and `hrid`=h.`id`)>0 then 'Leave' else 'Absent'  end) else (case  a.attendance_type  when 0 then 'Absent' when 1 then 'Present' when 2 then 'Delay' else 'N' end)  end)  stats ,
                                a.`early_leave` 
                                from `calander` cl,`employee` e left join `hr` h on e.`employeecode`=h.`emp_id` and h.`active_st` = 1
                                left join `attendance_test` a on a.`hrid`=h.`attendance_id` $dt_range_str
                                left join `Shifting` st on st.`id`=nvl((SELECT s.`shift` FROM `assignshifthist` s  where s.`st`=1 and s.`empid`=e.`id` and s.`effectivedt`=a.`date`),3) 
                                where  cl.day BETWEEN '2024-04-15' AND '2024-04-15' order by cl`day` desc";
        
       //echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;$tcp=0;$tmp=0;
            while($row = $result->fetch_assoc()) 
            {
               // if($row['date'] == '') continue;
                
                if($row["early_leave"] == 1){
    			    $early_leave = "YES";
    			}else{
    			    $early_leave = "NO";
    			}
			
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $col12='L'.$urut; $col13='M'.$urut; $col14='N'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['employeecode'])
    						->setCellValue($col3, $row['nm'])
    					    ->setCellValue($col4, $row['date'])
    					    ->setCellValue($col5, $row['shift'])
    					    ->setCellValue($col6, $row["starttime"])
    			            ->setCellValue($col7, $row["exittime"])
    						->setCellValue($col8, $row['intime'])
    					    ->setCellValue($col9, $row["outtime"])
    					    ->setCellValue($col10, $row["latetime"])
    					    ->setCellValue($col11, $row["earlytime"])
    					    ->setCellValue($col12, $row["worktime"])
    					    ->setCellValue($col13, $early_leave)
    					    ->setCellValue($col14, $row["stats"]);	/* */
    			$laststyle=$title;	
            }
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('All Employee Attendance Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'all_employee_attendance'.$today.'.xls'; 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);
        
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$fileNm);
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
     include_once('common_header.php');
    ?>
    
    <!-- Select2 CSS -->
<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />

<!-- Include Toastr CSS -->
<link href="js/plugins/toastr/toastr.min.css" rel="stylesheet">


<style>

.toast-top-right {
  top: 60px !important; /* Adjust this value as needed */
}
#toast-container > div {
  /*opacity: 1 !important;*/
}


/* Override Toastr default styles */
#toast-container {
  right: 10px;
}

/* Animations */
.toast {
  animation: slideInRight 0.5s, fadeOut 1s; /* Use desired durations */
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}


.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 34px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b{
    border: 0;
}

.select2-container--default .select2-selection {
  background-color: transparent;
  border: 0px solid #aaa!important;
  border-radius: 0px;
  cursor: text;
}

.select2-container .select2-selection {
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  min-height: 38px;
  user-select: none;
  -webkit-user-select: none;
}


.select2-container--default .select2-selection .select2-selection__choice {
  background-color: #e4e4e4;
  border: 1px solid #dbdbdb;
  border-radius: 2px;

  padding: 3px;
  padding-left: 0px;
  padding-left: 30px;
  font-size: 14px;
}

.select2-container--default .select2-selection .select2-selection__choice__remove {
  padding: 3px 8px;
}
    
    
.select2-container{
  width:102%!important;
    padding: 0;margin: 0;
}    
</style>
    
    <body class="list">
        
    <?php
     include_once('common_top_body.php');
    ?>
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>HRM</span>
      </div>
      
    <?php
        include_once('menu.php');
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
      			<!-- <div class="panel-heading"><h1>All Product</h1></div>  -->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
  
                	<form method="post" action="#" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                       <div class="well list-top-controls">
                
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>HRM <i class="fa fa-angle-right"></i> Attendance for All </h6>
                       </div>

                        

                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            
                            <input type="hidden" name="from_dt" id = "from_dt">
                            <input type="hidden" name="to_dt" id = "to_dt">
                            
                            
                            <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Purchase Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div> 
                                
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>
                            <div class="form-group">
                                <?=getBtn('create')?>
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
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable productList' width="100%">
                        <thead>
                            
							<tr>
								<th>Sl</th>
								<th>Employee Code</th>
								<th>Employee Name</th>
								<th>Date</th>
								<th>Shift </th>
								<th>Start Time</th>
								<th>End Time</th>
								<th>In Time</th>
								<th>Out Time</th>
								<th>Late Time</th>
								<th>Early Time</th>
								<th>Working Hours</th>
								<th>Early Leave</th>
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
        include_once('common_footer.php');
    ?>
    <?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
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
                    { data: 'employeecode'},
                    { data: 'nm', orderable: false },
                    { data: 'date' },
                    { data: 'shift', orderable: false },
                    { data: 'starttime' },
                    { data: 'exittime' },
                    { data: 'intime' },
                    { data: 'outtime' },
                    { data: 'latetime' },
                    { data: 'earlytime', orderable: false },
                    { data: 'worktime'},
                    { data: 'early_leave', orderable: false },
                    { data: 'stats', orderable: false },
					
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
	var dt = $('#to_dt').val();
	url = 'phpajax/datagrid_list_all.php?action=rpt_attendance_all';
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_attendance_all&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	$('#from_dt').val(start.format('YYYY-MM-DD'));
        	$('#to_dt').val(end.format('YYYY-MM-DD'));
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rpt_attendance_all&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
        	$('#from_dt').val(end.format('YYYY-MM-DD'));
        	$('#to_dt').val(start.format('YYYY-MM-DD'));
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_attendance_all'; 
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        

        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
                var sdt = $('#from_dt').val();
        	    var edt = $('#to_dt').val();
            
				var pdfurl = 'pdf_attendance_for_all.php?mod=4&dt_f='+sdt+'&dt_t='+edt;
				location.href=pdfurl;
				
			});
		
		</script>
        
    
    </body></html>
  <?php }?>    
