<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();
$usr=$_SESSION["user"];
$res= $_GET['res'];
$msg= $_GET['msg'];

$cmbdept=$_POST['cmbdept'];
$cmbdesg=$_POST['cmbdesg'];
if($cmbdept==''){$cmbdept='0';}
if($cmbdesg==''){$cmbdesg='0';}

$fdt= $_POST['cmbyr']; 
$tdt= $_POST['cmbmonth']; 
//if($fdt==''){$fdt=date("d/m/Y");}
if($tdt==''){
    $qrych = "SELECT `salaryyear`, `salarymonth` FROM `monthlysalary` ORDER BY id DESC LIMIT 1";
    $resultch= $conn->query($qrych);
    while($rowch = $resultch->fetch_assoc())
	{
	    $fdt = $rowch["salaryyear"];
	    $tdt = $rowch["salarymonth"];
	}
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
    $currSection = 'rpt_salary_sheet';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/hraction.php?res=0&msg='Insert Data'&mod=4");
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
                ->setCellValue('L1', 'Late Deduction')
                ->setCellValue('M1', 'AIT')
                ->setCellValue('N1', 'Gross')
                 ->setCellValue('O1', 'Note');
    			
        $firststyle='A2';
        $qry="SELECT e.id,s.salaryyear,MONTHNAME(STR_TO_DATE(s.salarymonth, '%m')) mnth,s.hrid,concat(e.firstname,' ',e.lastname) emp, e.employeecode empcode
                            ,s.benft_1 ,s.benft_2 ,s.benft_3 ,s.benft_4 ,s.benft_5 
                            ,s.benft_6 ,s.benft_7 ,s.benft_8 ,s.benft_9 ,s.benft_10,s.benft_11,s.privilage,s.total,s.notes
                            , dept.name deptname, desi.name desiname
                            FROM monthlysalary s left JOIN hr h  ON s.hrid=h.id and h.active_st=1 left join employee e on h.emp_id=e.employeecode 
                            LEFT JOIN department dept ON dept.id = e.department 
                            LEFT JOIN designation desi ON desi.id = e.designation
                            where s.salaryyear='$fdt' and s.salarymonth='$tdt' and ($cmbdept = e.department or $cmbdept = 0) and ($cmbdesg = e.designation or $cmbdesg = 0)"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $tot=$row['total'];//+$row['house']+$row['medical']+$row['transport']+$row['mobile'];
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;
                $col11='K'.$urut; $col12='L'.$urut; $col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;
                $i++;//$tot=$row['basic']+$row['house']+$row['medical']+$row['transport']+$row['mobile'];
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['salaryyear'])
    						->setCellValue($col3, $row['mnth'])
    						->setCellValue($col4, $row['empcode'])
    					    ->setCellValue($col5, $row['emp'])
    					    ->setCellValue($col6, $row['deptname'])
    					    ->setCellValue($col7, $row['desiname'])
    					     ->setCellValue($col8, $row['benft_1'])
    					     ->setCellValue($col9, $row['benft_2'])
    					    ->setCellValue($col10, $row['benft_3'])
    					    ->setCellValue($col11, $row['benft_4'])
    					    ->setCellValue($col12, $row['benft_5'])
    					    ->setCellValue($col13, $row['benft_11'])
    					    ->setCellValue($col14, $row['total'])
    					    ->setCellValue($col15, $row['notes']);
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
    
    //Approval checked
    $approvalSt = 2;
    $qryApproval = "select st from approval_salary where month = '$tdt' and year = '$fdt' and st = 1";
    $resultApproval = $conn->query($qryApproval);
    while($rowApproval = $resultApproval->fetch_assoc()){
        $approvalSt = $rowApproval["st"];
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
                <span>Attendence Sheet</span>
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
                	                <form method="post" action="rpt_salary_sheet.php?pg=1&mod=4" id="form1">
                                        <div class="well list-top-controls"> 
                                            <div class="row border">
                                                <div class="col-sm-3 text-nowrap">
                                                    <h6>HRM <i class="fa fa-angle-right"></i>Sallary Sheet</h6>
                                                </div>
                                                <div class="col-sm-9 text-nowrap">
                                                <div class="pull-right grid-panel form-inline">
                                                    
                                                    
                                                        <div class="form-group">
                                                            <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">     
                                                        </div>
                                                        
                                                        <div class="form-group">
                                    					    <div class="form-group styled-select">
                                    							<select name="cmbdept" id="cmbdept" class="cmd-child form-control" >
                                    							    <option value="0">Department </option>
                                        <?php 
                                        $qry1="SELECT `id`, `name` FROM `department`";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
                                        { 
                                          $tid= $row1["id"];  $nm=$row1["name"]; 
                                        ?>          
                                                                        <option value="<?php echo $tid; ?>" <?php if ($cmbdept == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                        <?php }}?>                              
                                                                </select>
                                    					    </div>
                                    				    </div>
                                    				    
                                    				    <div class="form-group">
                                    					    <div class="form-group styled-select">
                                    							<select name="cmbdesg" id="cmbdesg" class="cmd-child1 form-control" >
                                                                    <option value="0">Designation </option>
                                        <?php 
                                        $qry1="SELECT `id`, `name` FROM `designation`";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
                                        { 
                                          $tid= $row1["id"];  $nm=$row1["name"]; 
                                        ?>          
                                                                        <option value="<?php echo $tid; ?>" <?php if ($cmbdesg == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                        <?php }}?>                              
                                                                
                                                                </select>
                                    				        </div>
                                    				    </div>
                                                        
                                                    
                                                    
                                                        <div class="form-group">
                                                            <div class="form-group styled-select">
                                                                <select name="cmbyr" id="cmbyr" class="form-control" required>
<?php      $yr=date("Y");  ?>          
                                                                    <option value="<? echo $yr-1; ?>" <? if ($fdt == $yr-1) { echo "selected"; } ?>><? echo ($yr-1); ?></option>
                                                                    <option value="<? echo $yr; ?>" <? if ($fdt == $yr) { echo "selected"; } ?>><? echo $yr; ?></option>
                                                                    <option value="<? echo $yr+1; ?>" <? if ($fdt == $yr+1) { echo "selected"; } ?>><? echo $yr+1; ?></option>
                                                                </select>
                                                            </div>
                                                        </div>        
                                                    
                                                    
                                                        <div class="form-group">
                                                            <div class="form-group styled-select">
                                                                <select name="cmbmonth" id="cmbmonth" class="form-control" required>
                        <?php   for($i=1;$i<=12;$i++){ ?>          
                                                                    <option value="<? echo  str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <? if (str_pad($i, 2, "0", STR_PAD_LEFT) == $tdt) { echo "selected"; } ?>><? echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                        <?php } ?>                    
                                                                </select>
                                                            </div>
                                                        </div>        
                                                    
                                
                                <!--div class="form-group">
                                    <input type="text" class="form-control datepicker_history_filter" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt;?>" >
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker_history_filter" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt;?>"  >
                                </div -->
                                                        <div class="form-group">
                                                            <?php 
                                                            if ($approvalSt == 1){ ?>
                                                                <button type="button" class="form-control btn btn-default" disabled>Approved</button>
                                                            <?php } else { ?>
                                                                <a href="common/send_salary_approval.php?month=<?= $tdt ?>&year=<?= $fdt ?>" class="form-control btn btn-default">Send for Approval</a>
                                                            <?php } ?>
                                                            
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
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
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable actionbtn firstcolpad0' width="100%">
                        <thead>
                        <tr>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Employee ID</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Basic </th>
                            <th>House Rent </th>
                            <th>Medical </th>
                            <th>Transport Allownce</th>
                            <th>Late Deduction</th>
                            <!--th>Utility Allowance</th>
                            <th>PF</th>
                            <th>Child Allowance</th>
                            <th>Car Alloewance</th>
                            <th>Office Shuttle</th>
                            <th>Privilaged fund</th-->
                            <th>AIT</th>
                            
                            <th>Advance</th>
                            <th>Loan</th>
                            <th>Others</th>
                            <th>Gross</th>
                            <th>Note</th>
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
				pageLength: 100,
				scrollX: true,   
				bScrollInfinite: true,
				bScrollCollapse: true,
				scrollY: 550,
				deferRender: true,
				scroller: true,				
				/*'searching': true,*/
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=rptsalarysheet&fd=<?php echo $fdt;?>&td=<?php echo $tdt;?>&dept=<?= $cmbdept?>&desi=<?= $cmbdesg ?>'
                },
                'columns': [
                    { data: 'yr' },
                    { data: 'month' },
                    { data: 'empcode' },
                    { data: 'emp' },
                    { data: 'deptname' },
                    { data: 'desiname' },
					{ data: 'benft_1' },
					{ data: 'benft_2' },
					{ data: 'benft_3' },
					{ data: 'benft_4' },
					{ data: 'benft_5' },
				/*	{ data: 'benft_6' },
					{ data: 'benft_7' },
					{ data: 'benft_8' },
					{ data: 'benft_9' },
					{ data: 'benft_10' },
					{ data: 'privilage' },*/
					{ data: 'benft_11' },
					{ data: 'adv' },
					{ data: 'loan' },
					{ data: 'others' },
					{ data: 'tot' },
					{ data: 'notes' },
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
			    var year = <?= $fdt ?>;
        	    var month = <?= $tdt ?>;
        	    var dept = <?= $cmbdept ?>;
        	    var desi = <?= $cmbdesg ?>;
            
				var pdfurl = 'pdf_salary_sheet.php?mod=4&fd='+year+'&td=<?= $tdt ?>&dept='+dept+'&desi='+desi;
				location.href=pdfurl;
				
			});
		
		</script>
       
    
    </body></html>
  <?php }?>    
