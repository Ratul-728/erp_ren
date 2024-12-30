<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();
$usr=$_SESSION["user"];
$res= $_GET['res'];
$msg= $_GET['msg'];

$cmbassign=$_POST['cmbassign'];
if($cmbassign==''){$cmbassign='0';}

$fdt= $_POST['filter_date_from']; 
$tdt= $_POST['filter_date_to']; 
if($fdt==''){$fdt=date("1/m/Y");}
if($tdt==''){
    $tdt=date("d/m/Y");
}

//echo $fdt;echo "<br>";
//echo $tdt; die;

//$today =substr($tdt,6,4)."-".substr($tdt,3,2)."-".substr($tdt,0,2);  ;//date('d/m/Y'); //date_format($td1,"Y-m-d"); 
//$fdt = date('d/m/Y', strtotime('-15 day', strtotime($today)));


if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'assignshift';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/assignshift.php?res=0&msg='Insert Data'&mod=4");
    }
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Year')
                ->setCellValue('C1', 'Month')
                ->setCellValue('D1', 'Employee Code')
                ->setCellValue('E1', 'Employee Name')
                ->setCellValue('F1', 'Department')
                ->setCellValue('G1', 'Designation')
    			->setCellValue('H1', 'Basic')
    		    ->setCellValue('I1', 'House Rent')
                ->setCellValue('J1', 'Medical')
                ->setCellValue('K1', 'Transport')
                ->setCellValue('L1', 'Mobile Allowance')
                ->setCellValue('M1', 'Gross');
                  
    			
        $firststyle='A2';
        $qry="SELECT s.salaryyear,MONTHNAME(STR_TO_DATE(s.salarymonth, '%m')) mnth,s.hrid,concat(e.firstname,e.lastname) emp, e.employeecode empcode

                            ,s.benft_1 basic,s.benft_2 house,s.benft_3 medical,s.benft_4 transport,s.benft_5 mobile, dept.name deptname, desi.name desiname
                            
                            FROM monthlysalary s LEFT JOIN employee e ON s.hrid=e.id LEFT JOIN hraction hra ON hra.hrid = s.hrid LEFT JOIN department dept ON dept.id = hra.postingdepartment 
                            
                            LEFT JOIN designation desi ON desi.id = hra.designation
                            
                            where s.salaryyear=$fdt and s.salarymonth=$tdt and ($cmbassign = hra.postingdepartment or $cmbassign = 0) and ($cmbdesg = hra.designation or $cmbdesg = 0)"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $tot=$row['basic']+$row['house']+$row['medical']+$row['transport']+$row['mobile'];
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='j'.$urut;
                $col11='k'.$urut; $col12='l'.$urut; $col13='m'.$urut;
                $i++;$tot=$row['basic']+$row['house']+$row['medical']+$row['transport']+$row['mobile'];
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['salaryyear'])
    						->setCellValue($col3, $row['mnth'])
    						->setCellValue($col4, $row['empcode'])
    					    ->setCellValue($col5, $row['emp'])
    					    ->setCellValue($col6, $row['deptname'])
    					    ->setCellValue($col7, $row['desiname'])
    					     ->setCellValue($col8, $row['basic'])
    					     ->setCellValue($col9, $row['house'])
    					    ->setCellValue($col10, $row['medical'])
    					    ->setCellValue($col11, $row['transport'])
    					    ->setCellValue($col12, $row['mobile'])
    					    ->setCellValue($col13, $tot);
    					    	/* */
    			//$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Sallarysheet');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'sallarysheet'.$today.'.xls'; 
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
                <span>Assign Shift</span>
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
                        <p>
                            <div class="panel panel-info">
  		    				    <div class="panel-body">
  		    				        <span class="alertmsg"></span>
                	                <form method="post" action="assignshiftList.php?pg=1&mod=4" id="form1">
                                        <div class="well list-top-controls"> 
                                            <div class="row border">
                                                <div class="col-sm-3 text-nowrap">
                                                    <h6>HRM <i class="fa fa-angle-right"></i>Assign Shift</h6>
                                                </div>
                                                <div class="col-sm-9 text-nowrap">
                                                <div class="pull-right grid-panel form-inline">
                                                    
                                                    
                                                        <div class="form-group">
                                                            <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">     
                                                        </div>
                                                        
                                                        <div class="form-group">
                                    					    <div class="form-group styled-select">
                                    							<select name="cmbassign" id="cmbassign" class="cmd-child form-control" >
                                    							    <option value="0">Select Shift </option>
                                        <?php 
                                        $qry1="SELECT `id`, `title` FROM `Shifting`";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
                                        { 
                                          $tid= $row1["id"];  $nm=$row1["title"]; 
                                        ?>          
                                                                        <option value="<?php echo $tid; ?>" <?php if ($cmbassign == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                        <?php }}?>                              
                                                                </select>
                                    					    </div>
                                    				    </div>
                                    				
                                    				    
                                                        <div class="form-group">
                                                            <input type="text" class="form-control datepicker_history_filter" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt;?>" >
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control datepicker_history_filter" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt;?>"  >
                                                        </div>
                                                    
                                                        <div class="form-group">
                                                            <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                                                        </div>
                                                       
                                                        <div class="form-group">
                                                            <?= getBtn('export') ?>
                                                        </div>
                                                     
                                                    
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
                            <th>SL</th>
                            <th>Employee ID</th>
                            <th>Employee</th>
                            <th>Shift</th>
                            <th>Effective Date</th>
                            <th></th>
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
            var table1 = $('#listTable').DataTable({
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 25,
				scrollX: true,   
				bScrollInfinite: true,
				bScrollCollapse: true,
				scrollY: 550,
				deferRender: true,
				scroller: true,				
				/*'searching': true,*/
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=assignshift&fdt=<?php echo $fdt;?>&tdt=<?php echo $tdt;?>&assign=<?= $cmbassign?>'
                },
                'columns': [
                    { data: 'id' },
                    { data: 'employeecode' },
                    { data: 'empname' },
                    { data: 'shift' },
                    { data: 'effectivedt' },
					{ data: 'action' }
					
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
			
				var pdfurl = "pdf_salary_sheet_report.php";
				location.href=pdfurl;
				
			});
			
		</script>
		<?php

                if ($_REQUEST['msg']) {
                    echo "<script type='text/javascript'>messageAlert('" . $_REQUEST['msg'] . "')</script>";
                }

        ?>	
       
    
    </body></html>
  <?php }?>    
