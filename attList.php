<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();
$usr=$_SESSION["user"];
$res= $_GET['res'];
$msg= $_GET['msg'];

$sdt = $_POST["filter_date_from"];
$edt = $_POST["filter_date_to"];

if($edt == ''){
    $edt = date("d/m/Y");
}

if($sdt == ''){
    $sdt = date("Y-m-d");
    $sdt = date("d/m/Y", strtotime(date('d-m-Y', strtotime(' - 7 days', strtotime($sdt)))));
}

if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'attendance';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/att.php?res=0&msg='Insert Data'&mod=4");
    }
   if ( isset( $_POST['export'] ) ) {
       
        $sdt1 = str_replace('/', '-', $sdt);

        $edt1 = str_replace('/', '-', $edt);

        $sdt1 = date("Y-m-d", strtotime($sdt1) );

        $edt1 = date("Y-m-d", strtotime($edt1) );
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'EMPLOYEE NAME')
                ->setCellValue('C1', 'DATE')
                ->setCellValue('D1', 'IN TIME')
    			->setCellValue('E1', 'OUT TIME');
                  
    			
        $firststyle='A2';
        $qry="SELECT a.id, a.`date`, a.`intime`, a.`outtime`, concat(e.`firstname`, ' ', e.`lastname`) empname FROM `attendance` a 

                                LEFT JOIN `hr` b ON a.`hrid` = b.`id` LEFT JOIN `employee` e ON b.emp_id = e.employeecode where a.date between '".$sdt1."' AND '".$edt1."'"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $empname = $row['empname'];

                if($empname == ''){
    
                    $empname = "Administration";
    
                }
                $intime = $row['intime'];

                $intime = date("g:i a", strtotime($intime));
    
                $outtime = $row['outtime'];
    
                $outtime = date("g:i a", strtotime($outtime));
                
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $empname)
    			            ->setCellValue($col3, $row["date"])
    						->setCellValue($col4, $intime)
    					    ->setCellValue($col5, $outtime);
    					     
    					    	/* */
    			//$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ATTENDANCE');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'attendance'.$today.'.xls'; 
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
        <span>Attendance</span>
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
    
                	<form method="post" action="attList.php?pg=1&mod=4" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-sm-3 text-nowrap">
                            <h6>HRM <i class="fa fa-angle-right"></i>All Attendance</h6>
                       </div>

  <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
    
                            
                            <div class ="form-group">
                                <div class="  pull-right col-lg-4 col-md-4 col-sm-4">
            					<div class="input-group">
            						<input type="text" class="form-control datepicker_history_filter" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $edt;?>"  >
            						<div class="input-group-addon">
            							<span class="glyphicon glyphicon-th"></span>
            						</div>
            					</div>     
            				</div> 
            				<div class="  pull-right col-lg-4 col-md-4  col-sm-4">
            					<div class="input-group">
            						<input type="text" class="form-control datepicker_history_filter" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $sdt;?>" >
            						<div class="input-group-addon">
            							<span class="glyphicon glyphicon-th"></span>
            						</div>
            					</div>     
            				</div>
            				
                            </div>
                            
                            <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
                            </div>
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            
                            <div class="form-group">
                                <?= getBtn('create') ?>
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
                            <th>Employee Name</th>
                            <th>Date </th>
                            <th>In Time</th>
                            <th>Out Time</th>
                            <th>Action</th>
                            
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
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,
				"order": [[ 0, "desc" ]],
				"dom": "rtiplf",
				/*'searching': true,*/
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=attendance&edt=<?= $edt ?>&sdt=<?= $sdt ?>'
                },
                'columns': [
                    { data: 'sl' },
                    { data: 'name' },
                    { data: 'date' },
                    { data: 'intime'},
                    { data: 'outtime'},
					{ data: 'action', "orderable": false }
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
       
    
    </body></html>
  <?php }?>    
