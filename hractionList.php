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
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/hraction_v2.php?res=0&msg='Insert Data'&mod=4");
    }
    
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'hraction';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'EMPLOYEE NAME')
                ->setCellValue('C1', 'ACTION TYPE')
                ->setCellValue('D1', 'ACTION DATE')
    			->setCellValue('E1', 'DEPARTMENT')
    		    ->setCellValue('F1', 'JOB AREA')
                 ->setCellValue('G1', 'JOB TYPE')
                ->setCellValue('H1', 'DESIGNATION')
                ->setCellValue('I1', 'REPORT TO');
                  
    			
        $firststyle='A2';
        $qry="SELECT a.id id, concat(e.firstname, ' ', e.lastname) empname, act.Title acttype,a.`actiondt`, dept.name deptname, ja.Title janame, desi.name designation, jt.Title jtname, concat(emp2.firstname, ' ', emp2.lastname) reportto 

        FROM `hraction` a LEFT JOIN employee e ON a.`hrid` = e.id 

        LEFT JOIN ActionType act ON a.`actiontype` = act.ID 

        LEFT JOIN department dept ON a.`postingdepartment` = dept.id 

        LEFT JOIN JobArea ja ON a.`jobarea` = ja.ID 

        LEFT JOIN designation desi ON a.`designation` = desi.id 

        LEFT JOIN JobType jt ON a.`jobtype` = jt.ID 

        LEFT JOIN employee emp2 ON a.`reportto` = emp2.id WHERE a.st = 1"; 
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
    			            ->setCellValue($col2, $row['empname'])
    						->setCellValue($col3, $row['acttype'])
    					    ->setCellValue($col4, $row['actiondt'])
    					     ->setCellValue($col5, $row['deptname'])
    					     ->setCellValue($col6, $row['janame'])
    					    ->setCellValue($col7, $row['jtname'])
    					    ->setCellValue($col8, $row['designation'])
    					    ->setCellValue($col9, $row['reportto']);
    					    	/* */
    			//$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('HR ACTION');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'hr_action'.$today.'.xls'; 
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
        <span>HR Action</span>
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
      			<!--<div class="panel-heading"><h1>All Hr Action</h1></div>-->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    
                	<form method="post" action="hractionList.php" id="form1">
            
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
                            <h6>HRM <i class="fa fa-angle-right"></i>All HR Action</h6>
                       </div>
                       
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
                            <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
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
                            <th>Sl.</th>
                            <th>Employee Name</th>
                            <th>Employee Code</th>
                            <th>Type</th>
                            <th>Action Type</th>
                            <th>Effective Date</th>
                            <th>Department</th>
                            <th>Job Area </th>
                            <th>Contract Type </th>
                            <th>Designation </th>
                            <th>Report To</th>
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
				/*'searching': true,*/
				"order": [[ 0, "desc" ]],
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=hraction'
                },
                'columns': [
                    { data: 'sl'},
                    { data: 'empname' },
                    { data: 'empcode' },
                    { data: 'type' },
                    { data: 'acttype' },
                    { data: 'actdt' },
                    { data: 'dept' },
					{ data: 'jobarea' },
                    { data: 'jobtype' },
					{ data: 'desig', "orderable": false  },
					{ data: 'reportto', "orderable": false  },
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
		
	    //delete row
			
$("#listTable").on("click",".griddelbtn", function() {

			var url = $(this).attr('href');

			  swal({
			  title: "Are you sure?",
			  text: "Once deleted, you will not be able to recover this order!",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			  buttons: ['Cancel', 'Confirm Delete'],
			})
			.then((willDelete) => {
			  if (willDelete) {
				location.href=url;
				//swal("Order has been deleted!", {
				 // icon: "success",
			   // });
			  } else {
				//swal("Your imaginary file is safe!");
				  return false;
			  }
			});

			return false;

	
	});	
		
     
		
        </script>  
       
    
    </body></html>
  <?php }?>    
