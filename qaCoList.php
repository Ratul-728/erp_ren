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
    $currSection = 'qaco';
	// load session privilege;
	include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/quotationEntry.php?res=0&msg='Insert Data'&mod=2");
    }
   if ( isset( $_POST['export'] ) ) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'ORDER ID')
                ->setCellValue('C1', 'INVOICE ID')
                ->setCellValue('D1', 'ORDER DATE')
    			->setCellValue('E1', 'EXPECTED DELIVERY DATE')
                ->setCellValue('F1', 'NO. OF ITEMS IN QC')
                ->setCellValue('G1', 'TOTAL QUANTITY')
                ->setCellValue('H1', 'STATUS')
                ->setCellValue('I1', 'RESULT'); 

        $firststyle='A2';

        $qry="SELECT qa.order_id,DATE_FORMAT(so.orderdate, '%d/%m/%Y') orderdate, DATE_FORMAT(qa.delivery_date, '%d/%m/%Y %H:%i:%s') delivery_date, COUNT(DISTINCT qa.id) AS noitems, qastatus.name status, qastatus.dclass,inv.invoiceno, 
                    SUM(qa_warehouse.ordered_qty) AS total_qty,
                    SUM(qa_warehouse.pass_qty) AS total_pass_qty, 
                    SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty 
                    FROM qa 
                    LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status LEFT JOIN invoice inv ON qa.order_id = inv.soid
                    WHERE qa.type=8  GROUP BY qa.order_id  order by qa.id desc";
//WHERE  s.`status`<>6 order by s.`socode` asc"; 

        //echo  $qry;die;

        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			    $defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			    $damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

                $st= $passQty.' Passed | '.$defactQty.' Defact | '.$damagedQty.' Damaged';
                
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut; $col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;$col12='L'.$urut;$col13='M'.$urut; $col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;$col17='Q'.$urut;$col18='R'.$urut;$col19='S'.$urut;$col20='T'.$urut;$col21='U'.$urut;$col22='V'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['order_id'])
    						->setCellValue($col3, $row['invoiceno'])
    					    ->setCellValue($col4, $row['orderdate'])
					        ->setCellValue($col5, $row['delivery_date'])
					        ->setCellValue($col6, $row["noitems"])
				            ->setCellValue($col7, $row["total_qty"])
    						->setCellValue($col8, $row['status'])
    					    ->setCellValue($col9, $st);	/* */

    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('SO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'SOLD_QC_'.$today.'.xls'; 

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
        <span>QA CO</span>
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
                            <h6>QA <i class="fa fa-angle-right"></i> CO </h6>
                       </div>
                      
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
							
                                <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>
							                                    <div class="form-group">
                                                                   <!--input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"-->
                                                                    <div class="form-group styled-select">
                                                                        <input type="text" list="itemName"  autocomplete = "off" name="itmnm[]"  class="dl-itemName datalist" placeholder="Select Customer" required>
            															<input type="hidden" class = "barcode" name="barcode[]" value="" class="itemName">
                                                                        <datalist  id="itemName" class="list-itemName form-control"  >
                                                                            <option value="">Select Customer</option>
                <?php 
            			$qryitm = "SELECT i.id, i.name
            						FROM organization i
            						order by i.name";								 
            									 
                    $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                        $tid  = $rowitm["id"];
            			
                        $nm   = $rowitm["name"];
                
                        ?>
                                                                            
                        																<option class="option-<?=$tid?>" data-value="<?=$tid?>" value="<?=$nm?>"></option>																
                            <?php }} ?>
                                                                                    </datalist>
                                                                                </div>
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
                            <!--div class="form-group">
                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            </div-->
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
                            <th>Order ID</th>
                            <!--th>Invoice ID</th-->
                            <th>Customer</th>
                            <!--th>Delivery Date</th-->
                            <!--th>Expected Delivery Date</th-->
                            <th>No. Items to QC</th>
                            <th>Total Quantity</th>
                            <th>Status</th>
                            <th>Result</th>
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
					{ data: 'order_id' },
                    // { data: 'invoiceno' },
                    { data: 'customer' },
				// 	{ data: 'expted_deliverey_date' },	//Order Status
                    //{ data: 'delivery_date', 'orderable': false  },
                	{ data: 'quantity', 'orderable': false  },	//Order Amount
            		{ data: 'totqty', 'orderable': false  },
            		{ data: 'status' , 'orderable': false },	//accoutn manager
            		{ data: 'result', 'orderable': false  },
            		{ data: 'action', 'orderable': false  },
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
    url = 'phpajax/datagrid_qa.php?action=qaco';
	table_with_filter(url);	
	

        //Status
        $("#cmbstatus, .dl-itemName").on("change", function() {
            
            var inputValue = $(".dl-itemName").val();
            var selectedOption = $("#itemName option[value='" + inputValue + "']"); 
        
            var dataValue = selectedOption.data("value");
        
            var status = $("#cmbstatus").val();
			url = 'phpajax/datagrid_qa.php?action=qatest&customer='+dataValue+'&cmbstatus='+status;
			
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
		
  	mylink = $(this).attr('href')+"?socode="+$(this).data('socode')+"&qtype=quotation";
	
   //alert(mylink);
  
  
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: 'QUOTATION ID #'+$(this).data('socode'),
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
