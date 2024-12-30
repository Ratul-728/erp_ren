<?php
require "common/conn.php";


session_start();

//ini_set('display_errors',1);
$usr=$_SESSION["user"];

//echo $usr;die;

$res= $_GET['res'];
$msg= $_GET['msg'];





if($usr=='')
{ 	header("Location: ".$hostpath."/hr.php");
}
else
{
	
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the tiontion name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'purchasedatainv';
	// load session privilege;
	include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/purchase_data_inv.php?res=0&msg='Insert Data'&mod=12");
    }
   if ( isset( $_POST['export'] ) ) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
               ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'PO ID')
                ->setCellValue('C1', 'VOUCHER NO')
                ->setCellValue('D1', 'VOUCHER DATE')
    			->setCellValue('E1', 'PI NO')
    			->setCellValue('F1', 'PI DATE')
                ->setCellValue('G1', 'LC/TT NO')
                ->setCellValue('H1', 'LC/TT DATE')
                ->setCellValue('I1', 'GRN NO')
                ->setCellValue('J1', 'GRN DATE')
                ->setCellValue('K1', 'SUPPLIER')
                ->setCellValue('L1', 'RECEIVED BY')
                ->setCellValue('M1', 'STATUS'); 
        $firststyle='A2';

        $qry="SELECT pl.poid, pl.`voucher_no`, pl.voucher_date, pl.pi_no, pl.pi_date, pl.lc_tt_no, pl.lc_tt_date, pl.at,pl.ait, pl.gnr_no, pl.gnr_date,
                    concat(emp.firstname, ' ', emp.lastname) nm, b.name warehouse, pos.name stnm, pos.dclass, pl.st
                    FROM `purchase_landing` pl LEFT JOIN employee emp ON pl.received_by=emp.id LEFT JOIN branch b ON b.id=pl.warehouse LEFT JOIN purchase_st pos ON pos.id=pl.st
                    WHERE 1=1 order by pl.`id` desc";
//WHERE  s.`status`<>6 order by s.`socode` asc"; 

       // echo  $qry;die;

        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut; $col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;$col12='L'.$urut;$col13='M'.$urut; $col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;$col17='Q'.$urut;$col18='R'.$urut;$col19='S'.$urut;$col20='T'.$urut;$col21='U'.$urut;$col22='V'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['poid'])
    						->setCellValue($col3, $row['voucher_no'])
    					    ->setCellValue($col4, $row['voucher_date'])
					        ->setCellValue($col5, $row['pi_no'])
					        ->setCellValue($col6, $row['pi_date'])
					        ->setCellValue($col7, $row['lc_tt_no'])
				            ->setCellValue($col8, $row['lc_tt_date'])
    						->setCellValue($col9, $row['gnr_no'])
    					    ->setCellValue($col10, $row['gnr_date'])
					        ->setCellValue($col11, $row['warehouse'])
					        ->setCellValue($col12, $row['nm'])
				            ->setCellValue($col13, $row['stnm']);	/* */

    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('SO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'PO_INVENTORY_'.$today.'.xls'; 

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
        <span>Inventory</span>
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
      			<!--<div class="panel-heading"><h1>All Service Order(Item)</h1></div>-->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    
    
 
                	<form method="post" action="#" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                          
                          
                          
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>Inventory <i class="fa fa-angle-right"></i> Purchase Order </h6>
                       </div>
                      
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
							
                                <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbstatus" id="cmbstatus" class="form-control" >
                                            <option value="0">All Status</option>
    <?php
$qry1    = "select id,name from qastatus order by id";
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
                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
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
                    <table id='listTable' class='display dataTable actionbtn' width="100%">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>PO ID</th>
                            <th>Voucher No</th>
                            <th>Voucher Date</th>
                            <th>PI NO</th>
                            <th>PI Date</th>
                            <th>LC/TT No.</th>
                            <th>LC/TT Date</th>
                            <!-- <th>AT</th>
                            <th>AIT</th> -->
                            <th>GRN No</th>
                            <th>GRN Date</th>
                           <!-- <th>Warehouse</th> -->
                            <th>Received By</th>
                            <th>Status</th>
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
                    //'url':'phpajax/datagrid_saleorder.php?action=inv_soitem'
					'url':url,
                },
//				'columnDefs': [{
//				'render': function(data,id){ return id},
//				'targets': 0,
//				'className': 'root_'+id,
//			}],

				'columns': [
                    { data: 'sl' },
					{ data: 'poid' },
                    { data: 'voucher_no' },
					{ data: 'voucher_date' },	//Order Status
                    { data: 'pi_no' },
                	{ data: 'pi_date' },	//Order Amount
            		{ data: 'lc_tt_no' },
            		{ data: 'lc_tt_date'},
            	//	{ data: 'at'},
                //  { data: 'ait' },
					{ data: 'gnr_no' },	//Order Status
                    { data: 'gnr_date' },
                //	{ data: 'warehouse' },	//Order Amount
            		{ data: 'nm' },
            		{ data: 'stnm' },
            		{ data: 'action' },
				
					
					
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
	//url = 'phpajax/datagrid_quotation.php?action=quotation&currSection='.$currSection;
    url = 'phpajax/datagrid_purchase.php?action=purchasedatainv';
	table_with_filter(url);	
	

        //Status
        $("#cmbstatus").on("change", function() {

            var status = $(this).val();
			//status = parseInt(status.trim());
            //var user = $('#filteruser').val();
            //var paidto = $('#filterpaidto').val();
            //var enddt = $('#end_dt').val();
            //var startdt = $('#start_dt').val();
            //var url = 'phpajax/datagrid_saleorder.php?action=inv_soitem&user='+user+'&cmbstatus='+status+'&paidto='+paidto+'&startdt='+startdt+'&enddt='+enddt;
			url = 'phpajax/datagrid_purchase.php?action=purchasedata&cmbstatus='+status;
			
			//alert(status);
			
            
			
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
					
	
	
	
	
			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script>  
		
		
		
    
    </body></html>
  <?php }?>    
