<?php
require "common/conn.php";

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
    $currSection = 'packagesetup';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/packageSetup.php?res=0&msg='Insert Data'&mod=4");
    }
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'CODE')
                ->setCellValue('C1', 'ITEM NAME')
                ->setCellValue('D1', 'BUIESNESS TYPE')
    			->setCellValue('E1', 'COMPANY TYPE')
    		    ->setCellValue('F1', 'LICENSE TYPE')
                 ->setCellValue('G1', 'ITEM CATAGORY')
                ->setCellValue('H1', 'DESCRIPTION');
                  
    			
        $firststyle='A2';
        $qry="SELECT i.`id`, i.`code`, i.`name` itnm,c.`name` bt, i.`size` ct, p.`name` lt, ic.`name` ItemCat, i.`dimension`,i.`wight`, i.`image`, i.`description` 
FROM `item` i,`color` c,`pattern` p,`itmCat` ic
where i.`color`=c.`id` and i.`pattern`= p.`id` and i.`catagory`=ic.`id`
order by i.`code`"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['code'])
    						->setCellValue($col3, $row['itnm'])
    					    ->setCellValue($col4, $row['bt'])
    					     ->setCellValue($col5, $row['ct'])
    					     ->setCellValue($col6, $row['lt'])
    					    ->setCellValue($col7, $row['ItemCat'])
    					    ->setCellValue($col8, $row['description']);
    					    	/* */
    			//$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ITEM');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'item'.$today.'.xls'; 
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
        <span>Packages</span>
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
      			<div class="panel-heading"><h1>All Packages</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    
                	<form method="post" action="packageSetupList.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-xs-6 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <!--div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div-->
                        <div class="col-xs-6">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
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
                            <th>Title</th>
                            <th>Package</th>
                            <th>Compansation</th>
                            <th>Benefit Type</th>
                            <th>Benefit Amount</th>
                            <th>Is Percent?</th>
                            <th>Cycle</th>
                            <th>Description</th>
                            <th></th>
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
            $('#listTable').DataTable({
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
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=packageSetup'
                },
                'columns': [
                    { data: 'sl'},
                    { data: 'title' },
                    { data: 'pack' },
                    { data: 'compansation' },
                    { data: 'btype' },
                    { data: 'bamount' },
                    { data: 'per' },
                    { data: 'cycle' },
                    { data: 'details', "orderable": false  },
					{ data: 'edit', "orderable": false },
					{ data: 'del', "orderable": false }
                ]
            });
        });
		
	
		
        </script>  
       
    
    </body></html>
  <?php }?>    
