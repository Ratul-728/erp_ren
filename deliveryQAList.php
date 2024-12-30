<?php
require "common/conn.php";
require "common/user_btn_access.php";

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
    $currSection = 'deliveryqa';
	// load session privilege;
	include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/deliveryQA.php?res=0&msg='Insert Data'&mod=3");
    }
   if ( isset( $_POST['export'] ) ) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'DO NUMBER')
                ->setCellValue('C1', 'ORDER NUMBER')
                ->setCellValue('D1', 'DELIVERY DATE')
    			->setCellValue('E1', 'DELIVERY START TIME')
                ->setCellValue('F1', 'DELIVERY END TIME')
                ->setCellValue('G1', 'TYPE')
                ->setCellValue('H1', 'QUANTITY')
                ->setCellValue('I1', 'DO QUANTITY'); 

        $firststyle='A2';

        $qry="SELECT doi.do_id, doi.do_date, doi.start_time,doi.start_time,doi.end_time,doi.order_id,
                                SUM(dod.qty) AS totqty,
                                SUM(dod.do_qty) AS totdoqty
                                FROM `delivery_order` doi LEFT JOIN delivery_order_detail dod ON dod.do_id=doi.id WHERE 1 = 1  GROUP BY doi.do_id order by doi.`id` desc";
//WHERE  s.`status`<>6 order by s.`socode` asc"; 

       // echo  $qry;die;

        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                if($row["totdoqty"] < $row["totqty"]){
                    $type =  'Partial Delivery';
                }else{
                    $type = 'Full Delivery';
                }
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut; $col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;$col12='L'.$urut;$col13='M'.$urut; $col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;$col17='Q'.$urut;$col18='R'.$urut;$col19='S'.$urut;$col20='T'.$urut;$col21='U'.$urut;$col22='V'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['do_id'])
    						->setCellValue($col3, $row['order_id'])
    					    ->setCellValue($col4, $row['do_date'])
					        ->setCellValue($col5, $row['start_time'])
					        ->setCellValue($col6, $row['end_time'])
    						->setCellValue($col7, $type)
    					    ->setCellValue($col8, $row['totqty'])
					        ->setCellValue($col9, $row['totdoqty']);	/* */

    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('SO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'DELIVERY_'.$today.'.xls'; 

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
        <span>SALES</span>
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
                            <h6> Sales <i class="fa fa-angle-right"></i> Delivery Order List </h6>
                       </div>
                      
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
							
                                <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>
							
                                <!--div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbstatus" id="cmbstatus" class="form-control" >
                                            <option value="0">All Status</option>

                                            <option value="1">Full Delivery</option>
                                            <option value="2">Partial Delivery</option>
                                        </select>
                                    </div>
                                </div--> 
							
             						
							
                            <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
                            </div>
                            <div class="form-group">
                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
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
                            <th>DO-Number</th>
                            <th>Order Number</th>
                            <th>Delivery Date</th>
                            <th>Delivery Start Time</th>
                            <th>Delivery End Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Quantity</th>
                            <th>DO Quantity</th>
                            <th style="width:150px;">Actions</th>
							
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
					{ data: 'do_id' },
					{ data: 'order_id' },
                    { data: 'do_date' },
					{ data: 'start_time', 'orderable': false },	//Order Status
                    { data: 'end_time', 'orderable': false },
                	{ data: 'type', 'orderable': false },	//Order Amount
            		{ data: 'delist', 'orderable': false },
            		{ data: 'qty', 'orderable': false },
            		{ data: 'do_qty', 'orderable': false },
            		//{ data: 'view' },//accoutn manager
					{ data: 'action_buttons', 'orderable':false},
				
					
					
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
    url = 'phpajax/datagrid_delivery.php?action=deliveryqa';
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
			url = 'phpajax/datagrid_delivery.php?action=deliveryqa&cmbstatus='+status;
			
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
			  text: "Once cancel the DO, you will not be able to recover this delivery order!",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			  buttons: ['Cancel', 'Cancel DO'],
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

<script>
function dateRangePopup(){
	
        $(document).on('focus','.datepicker-popup', function(){
			
            $(this).datetimepicker({
       
                 //minDate: moment().startOf('day').add(1, 'days').toDate() ,
				 format: "DD/MM/YYYY",
				 //format: 'LT',
                 //debug:true,
				 //keepOpen:true,
                 showClear:true,
                 useCurrent:false,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });    
    });	
	
}


    $(".dataTable").on("click","a.pop-deliverydate",function(){
        
        var root = $(this);
        var orderid = $(this).data('socode');
    		
      	var mylink = $(this).attr('href')+"?socode="+$(this).data('socode');
    	
      // alert(mylink);
      
      dateRangePopup();
      
      
      
      
      
      BootstrapDialog.show({
    							
    							title: 'Change Delivery Date: DO NUMBER: '+orderid,
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
    								// cssClass: 'btn-primary <?=(!checkBtnAccess('change-delivery-date'))?'disabled':''?>',
    								label: ' Update Delivery Date',
    								action: function(dialog) {
    					
    									//write date update ajax code here;
    									
    									var formData = $("#dateChangeForm").serialize();
    									
                                        $.ajax({
                                                    type: "POST",
                                                    url: "phpajax/update_delivery_date_do.php",
                                                    data: formData,
                                                    success: function (res) {
                                                        // Handle success response
                                                        
                                                        
                                                        
                                                        
                                                        swal({
                                                			  title: "Success!",
                                                			  text: res.msg,
                                                			  icon: "success",
                                                			 
                                                			  
                                                			  closeButton: true,
                                                                                                             			  
                                                			})
                                                			console.log(res.date);
                                                		root.closest("tr").find(".deldatewrap span").html(res.date);
                                                		root.closest("tr").find(".deldatewrap").parent().css('backgroundColor', '#FBC8A3C2');
                                                		setTimeout(function() {root.closest("tr").find(".deldatewrap").parent().css('backgroundColor', 'transparent');},5000);
                                                        
                                                    },
                                                    error: function () {
                                                        // Handle error response
                                                        BootstrapDialog.show({
                                                            title: 'Error',
                                                            message: 'Failed to save data!',
                                                            type: BootstrapDialog.TYPE_DANGER
                                                        });
                                                    }
                                                });    									
    									
    									
    									
    									
    									
    									dialog.close();	
    									
    									},
    								
    							}],
    							onshown: function(dialog){  $('.btn-primary').focus();},
    						});	
      
      
      
      
      
      
      	return false;
    });
</script>
    </body></html>
  <?php }?>    
