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
    $currSection = 'deliverycostatusList';
	// load session privilege;
	include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/pendingDelivery.php?res=0&msg='Insert Data'&mod=2");
    }
   if ( isset( $_POST['export'] ) ) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'DO NUMBER')
                ->setCellValue('C1', 'QUANTITY')
                ->setCellValue('D1', 'DELIVERY DATE')
    		  #	->setCellValue('E1', 'DELIVERY START TIME')
              #  ->setCellValue('F1', 'DELIVERY END TIME')
                ->setCellValue('G1', 'SUPERVISOR')
                ->setCellValue('H1', 'ACKNOWLEDGEMENT')
                ->setCellValue('I1', 'DELIVERY TEAM')
                ->setCellValue('J1', 'RESOURCE PLAN'); 

        $firststyle='A2';

        $qry="SELECT doi.do_id, doi.do_date, doi.start_time,doi.start_time,doi.end_time,doi.order_id,ds.name stnm, ds.dclass,resourceplan,
                                concat(emp.firstname, ' ', emp.lastname) supervisor, GROUP_CONCAT(DISTINCT lt.name) team,
                                SUM(dod.qty) AS totqty,
                                SUM(dod.do_qty) AS totdoqty
                                FROM `delivery_order` doi LEFT JOIN delivery_order_detail dod ON dod.do_id=doi.id LEFT JOIN deliverystatus ds ON ds.id=doi.resourceplan 
                                LEFT JOIN resourceplan r ON doi.do_id=r.doid LEFT JOIN employee emp ON emp.id=r.supervisor
                                LEFT JOIN assign_logistic_team alt ON alt.resourceid=r.id 
                                LEFT JOIN logistic_team lt ON lt.id=alt.logisticteamid
                                WHERE 1 = 1  GROUP BY doi.do_id  order by doi.`id` desc";
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
    			            ->setCellValue($col2, $row['do_id'])
    						->setCellValue($col3, $row['order_id'])
    					    ->setCellValue($col4, $row['totdoqty'])
					        ->setCellValue($col5, $row['do_date'])
					    #    ->setCellValue($col6, $row['start_time'])
				        #    ->setCellValue($col7, $row['end_time'])
    						->setCellValue($col8, $row['supervisor'])
    					    ->setCellValue($col9, $row['team'])
    					    ->setCellValue($col10, $row['stnm']);	/* */

    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('SO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'DELIVERY_STATUS_'.$today.'.xls'; 

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
        <span>Delivery</span>
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
                            <h6> SCM <i class="fa fa-angle-right"></i> Delivery Status </h6>
                       </div>
                      
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
							
                                <div class="form-group">
  									<label for="">Search: </label>
                                </div>
							
                                
							
             						
							
                            <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
                            </div>
                            
                            <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
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
                            <th>DO Number</th>
                            <th>CO Number</th>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Order Date</th>
                            <th>Quantity</th>
                            <th>Delivery Date</th>
                            <th>Supervisor</th>
                            <th>Acknowledgement</th>
                            <th>Delivery Team</th>
                            <th>Status</th>
                            <th>Recource Plan</th>
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
					{ data: 'doid', 'orderable': false },
					{ data: 'co_id', 'orderable': false },
					{ data: 'order_id', 'orderable': false },
					{ data: 'orgnm', 'orderable': false },
					{ data: 'orderdate', 'orderable': false },
            		{ data: 'do_qty' },
                    { data: 'delivery_date' },
			//		{ data: 'delivery_start', 'orderable': false },	//Order Status
            //      { data: 'delivery_end', 'orderable': false },
                	{ data: 'supervisor', 'orderable': false },	//Order Amount
            		{ data: 'acknowledgement', 'orderable': false },
            		{ data: 'delivery_team', 'orderable': false },
            		{ data: 'status', 'orderable': false },
            		{ data: 'resource_plan', 'orderable': false },
            		{ data: 'view', 'orderable': false },//accoutn manager
					//{ data: 'action_buttons', 'orderable':false},
				
					
					
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
    url = 'phpajax/datagrid_scm.php?action=codeliverystatus';
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
			//url = 'phpajax/datagrid_qa.php?action=qatest&cmbstatus='+status;
			
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
		
		
<script>
$(document).ready(function(){
	
//show INVOICE
	
	$(".dataTable").on("click",".show-invoice.btn",function(){
		
  	mylink = $(this).attr('href')+"?doid="+$(this).data('doid')+"&qtype=delivery";
	
   //alert(mylink);
  
  		BootstrapDialog.show({
							
							title: 'DELIVERY ORDER',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: false, // <-- Default value is false
							cssClass: 'show-invoice',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Cancel',
								action: function(dialog) {
									dialog.close();	
									/*
									$("#printableArea2").printThis({
										importCSS: true, 
										importStyle: true,
									});
									*/
									
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Print',
								action: function(dialog) {
									
									$("#printableArea2").printThis({
										importCSS: false, 
										importStyle: true,
									});
		
									
									dialog.close();	
									
									},
								
							}],
							onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});		
		
	
});
</script>		
    
    </body></html>
  <?php }?>    
