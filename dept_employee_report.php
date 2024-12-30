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
    $currSection = 'dept_employee_report';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/att.php?res=0&msg='Insert Data'&mod=4");
    }
   if ( isset( $_POST['export'] ) ) {
       
        $month = $_POST["month"]; if($month != 0) $monthqry = "AND MONTH(h.actiondt) = ".$month;

        $year = $_POST["year"];   if($year != 0) $yearqry = "AND YEAR(h.actiondt) = ".$year;
        
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Employee Name')
                ->setCellValue('C1', 'Employee Code')
                ->setCellValue('D1', 'Department')
    			->setCellValue('E1', 'Designation')
    			->setCellValue('F1', 'Date of Joining')
    			->setCellValue('G1', 'Phone number')
    			->setCellValue('H1', 'Current Address')
    			->setCellValue('I1', 'Blood Group');
                  
    			
        $firststyle='A2';
        $qry="SELECT concat(emp.firstname, ' ', emp.lastname) empnm, emp.employeecode, h.actiondt, dept.name deptnm, des.name desnm, emp.office_contact, emp.office_contact,
                emp.bloodgroup, emp.presentaddress
                FROM `employee` emp LEFT JOIN hraction h ON (h.hrid=emp.id AND h.actiontype=1) LEFT JOIN department dept ON dept.id = emp.department LEFT JOIN designation des ON des.id=emp.designation 
                WHERE 1=1 $monthqry $yearqry"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row["empnm"])
    			            ->setCellValue($col3, $row["employeecode"])
    						->setCellValue($col4, $row["deptnm"])
    					    ->setCellValue($col5, $row["desnm"])
    					    ->setCellValue($col6, $row["actiondt"])
    					    ->setCellValue($col7, $row["office_contact"])
    						->setCellValue($col8, $row["presentaddress"])
    					    ->setCellValue($col9, $row["bloodgroup"]);
    					     
    					    	/* */
    			//$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('DEPARTMENT_WISE_REPORT');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'department_wise_report'.$today.'.xls'; 
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
    
    <body class="list">
        
    <?php
     include_once('common_top_body.php');
    ?>
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>Departmentwise Employee Report</span>
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
      			
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    
                	<form method="post" action="#" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-sm-3 text-nowrap">
                            <h6>HRM <i class="fa fa-angle-right"></i>Departmentwise Employee Report</h6>
                       </div>

  <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
							
                                <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="month" id="month" class="form-control" >
                                            <option value="0">All Month</option>
    <?php 
    for ($m = 1; $m <= 12; ++$m) {
    $month = date('F', mktime(0, 0, 0, $m, 1)); 
    ?>  
													
                                                    <option value="<? echo $m; ?>" <? if ($mnid == $m) { echo "selected"; } ?>><? echo $month; ?></option>
    <?php 
    } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="year" id="year" class="form-control" >
                                            <option value="0">All Year</option>
    <?php 
        $currentYear = date('Y');
        for ($i = -5; $i <= 5; $i++) {
            $year = (int)$currentYear + $i;
        
    ?>          
                                                    <option value="<? echo $year; ?>" <? if ($yearid == $year) { echo "selected"; } ?>><? echo $year; ?></option>
    <?php 
          }
    ?>   
                                        </select>
                                    </div>
                                </div>
							
             						
							
                            <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
                            </div>
                            
                            <div class="form-group">
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
                            <th>SL.</th>
                            <th>Employee Name</th>
                            <th>Employee ID </th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Date of Joinning</th>
                            <th>Phone Number</th>
                            <th>Current Address</th>
                            <th>Blood Group</th>
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
				/*'searching': true,*/
                'ajax': {
                    'url':url,
                },
                'columns': [
                    { data: 'sl' },
                    { data: 'empnm' },
                    { data: 'employeecode' },
                    { data: 'deptnm'},
                    { data: 'desnm'},
                    { data: 'actiondt'},
                    { data: 'office_contact'},
                    { data: 'presentaddress'},
                    { data: 'bloodgroup'},
                ]
            });
            
            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
            
		}
		
		url = 'phpajax/datagrid_list_all.php?action=dept_employee_report';
	    table_with_filter(url);	
        
        //Filter
        $("#month, #year").on("change", function() {

            var month = $("#month").val();
            var year = $("#year").val();
            
			url = 'phpajax/datagrid_list_all.php?action=dept_employee_report&month='+month+'&year='+year;
			
            table_with_filter(url);

        });
            
        });
		
		
        </script>  
       
    
    </body></html>
  <?php }?>    
