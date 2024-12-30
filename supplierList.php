<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();
$usr=$_SESSION["user"];
$com=$_SESSION["company"];

$res = $_GET['res'];
$msg = $_GET['msg'];


if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'supplier';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/supplier.php?res=0&msg='Insert Data'&mod=12");
    }
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'CODE')
                ->setCellValue('C1', 'NAME')
                ->setCellValue('D1', 'ADDRESS')
                ->setCellValue('E1', 'CONTACT')
                ->setCellValue('F1', 'EMAIL')
                ->setCellValue('G1', 'WEB'); 
    			
        $firststyle='A2';
        $qry="SELECT `id`,LPAD(id,4,'0') cd, `name`, `address`, `contact`,email,web FROM `suplier` WHERE status = 'A' "; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['cd'])
    						->setCellValue($col3, $row['name'])
    						->setCellValue($col4, $row['address'])
    						->setCellValue($col5, $row['contact'])
    						->setCellValue($col6, $row['email'])
    						->setCellValue($col7, $row['web']);	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('SUPPLIER');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'supplier'.$today.'.xls'; 
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
        <span>INVENTORY</span>
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
      		<!--	<div class="panel-heading"><h1>All Spplier</h1></div> -->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
  
                	<form method="post" action="supplierList.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       <div class="col-sm-1 text-nowrap">
                           <h6>Inventory <i class="fa fa-angle-right"></i> Supplier</h6>
                       </div>
                        <div class="col-sm-11 text-nowrap"> 
                        
                             <div class="pull-right grid-panel form-inline">
                                <div class="form-group">
                            <input type="search " id="search-dttable" class="form-control mini-issue-search">     
                            </div>
                            
                            <div class="form-group">
                                <?=getBtn('create')?>
                            </div>
                            <div class="form-group">
                                <?=getBtn('export')?>
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
                    <table id='listTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Web</th>
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
				pageLength: 50,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=suppl'
                },
                'columns': [
                    { data: 'id' },
                    { data: 'cd' },
                    { data: 'name' },
                    { data: 'address' },
                    { data: 'contact' },
                    { data: 'email' },
                    { data: 'web' },
                    { data: 'action' }
                ]
            });
            
             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
            
            setTimeout(function(){	
				$('#listTable').DataTable().draw();
			},300);
        });
		
	    	function confirmationDelete(anchor)
            {
               swal({
                   title: "Are you sure?",
                  text: "You will not be able to recover this file!",
                  icon: "warning",
                  buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                  ],
                  
                }).then(function(isConfirm) {
                  if (isConfirm) {
                    
                     swal("Deleted", "Successfully deleted!", "success");
                      window.location= anchor.attr("href");
                  
                  } else {
                    swal("Cancelled", "Product isn't deleted!", "error");
                  }
                });
              
            }
		
        </script> 
		
        </script>  
        
        <!-- Alert -->
        <script>
            <?php 
                if($res == 2){
            ?>
                swal("Cancelled", "<?= $msg ?>", "error");
            <?php }?>
             <?php 
                if($res == 1){
            ?>
                swal("Success", "<?= $msg ?>", "success");
            <?php }?>
        </script>
    
    </body></html>
  <?php }?>    
