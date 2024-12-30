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
    $currSection = 'organization';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/organization_rdl.php?res=0&msg='Insert Data'&mod=3");
    }
   if ( isset( $_POST['export'] ) ) {	
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'ORGANIZATION')
                ->setCellValue('C1', 'INDUSTRY TYPE')
                ->setCellValue('D1', 'EMPLOYEE SIZE')
    			->setCellValue('E1', 'OPERATION STATUS')
    		    ->setCellValue('F1', 'BUISINESS VALUE')
                 ->setCellValue('G1', 'ORGANIZATION PHONE')
                ->setCellValue('H1', 'ORGANIZATION EMAIL')
                 ->setCellValue('I1', 'WEBSITE')
                 ->setCellValue('J1', 'ACCOUNT MANAGER')
            	 ->setCellValue('K1', 'DETAILS'); 
    			
        $firststyle='A2';
   
        $qry="SELECT o.`id`,o.`name`,i.`name` `industry`,o.`employeesize`,op.`name` `operationstatus`,o.`bsnsvalue`,o.`contactno`,o.`email`,o.`website`,o.`details`
,concat(e.firstname,'',e.lastname) accmgr
FROM organization o left join businessindustry i  on  o.`industry`=i.`id` left join operationstatus op on o.operationstatus=op.`id`
left join hr h on o.salesperson=h.id  left join employee e on h.`emp_id`=e.`employeecode` order by o.`id`"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['name'])
    						->setCellValue($col3, $row['industry'])
    					    ->setCellValue($col4, $row['employeesize'])
					        ->setCellValue($col5, $row['operationstatus'])
					        ->setCellValue($col6, $row['bsnsvalue'])
    					    ->setCellValue($col7, $row['contactno'])
    					    ->setCellValue($col8, $row['email'])
    					    ->setCellValue($col9, $row['website'])
    					    ->setCellValue($col11, $row['details'])  ;	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ORG');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'organization'.$today.'.xls'; 
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
        <span>CRM</span>
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
      			<!--<div class="panel-heading"><h1>All Organization</h1></div>-->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <!--<br>-->
                	<form method="post" action="organizationList.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                       
                       <div class="col-sm-3 text-nowrap">
                            <h6>CRM <i class="fa fa-angle-right"></i> All Customers</h6>
                       </div>                       
                       

                        <div class="col-sm-9 text-nowrap"> 
                        
                            <div class="pull-right grid-panel form-inline">
                                
                                
                                <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="industry" id="industry" class="form-control" >
                                            <option value="0">All Industry</option>
    <?php
$qry1    = "select id,name from businessindustry order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($icat == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div> 
                                
                                
                                
                                <div class="form-group">
                                    <input type="search" id="search-dttable" class="form-control">     
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
                    <table id="listTable" class="display actionbtn no-footer dataTable dt-responsive" width="100%">
                        <thead>
                        <tr>
                            <th>Created</th>
                            <th>Customer ID</th>
                            <th>Customer Name</th>
                            <th>Type</th>
                            <th>Industry Type</th>
                            <!--<th>Operation Status</th>-->
                            <th>Phone</th>
                            <th>Email</th>
                            <!--th>Address </th-->
                            <th class="sorting_disabled" >Actions</th>
                            <!--<th class="sorting_disabled" ></th>-->
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
                responsive:false,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 100,
				scrollX: true,
				//bScrollInfinite: true,
				//bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,	
				"order": [[ 0, "desc" ]],
				"dom": "rtiplf",
                'ajax': {
                    //'url':'phpajax/datagrid_list_all.php?action=org'
                    'url':url,
                },
                'columns': [
                    { data: 'makedt','bVisible': false },
                    { data: 'customerid' },
                    { data: 'name' },
                    { data: 'type', 'orderable': false },
                    { data: 'industry', 'orderable': false },
                    //{ data: 'operationstatus' },
                    { data: 'contactno', 'orderable': false },
					{ data: 'email', 'orderable': false },
                    //{ data: 'address', 'orderable': false },
					//{ data: 'edit', "orderable": false },
					//{ data: 'del', "orderable": false },
					{ data: 'action_buttons', 'orderable': false },
					
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
	url = 'phpajax/datagrid_customer.php?action=org_rdl';
	table_with_filter(url);			
		
	
	
        //Industry filter
        $("#industry").on("change", function() {

            var industry = $(this).val();

			url = 'phpajax/datagrid_customer.php?action=org_rdl&industry='+industry;
			

            
			
            setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
	
	
	//delete row
			
$("#listTable_wrapper").on("click",".griddelbtn", function() {

			var url = $(this).attr('href');
	  //alert(url);
	  //swal(url);
	//return false;


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
		
             
        });//$(document).ready(function(){
        
        
        
        
        
		
	
		
        </script>  
    
    </body></html>
  <?php }?>    
