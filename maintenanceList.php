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
    $currSection = 'maintenance';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/maintenance_entry.php?res=0&msg='Insert Data'&mod=22");
    }
   if ( isset( $_POST['export'] ) ) {
        $fd1=$_POST['from_dt'];
        $td1=$_POST['to_dt'];
        if($fd1!=''){
            $dateqry = " and m.date BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Maintenance')
                ->setCellValue('C1', 'DO Number')
                ->setCellValue('D1', 'Order Date')
                ->setCellValue('E1', 'Reason')
                ->setCellValue('F1', 'Inspection')
                ->setCellValue('G1', 'Customer')
                ->setCellValue('H1', 'Fee')
                ->setCellValue('I1', 'TDS')
                ->setCellValue('J1', 'VDS')
                ->setCellValue('K1', 'Total Amount');
                
    			
        $firststyle='A2';
        $qry="SELECT m.id, m.code,m.do_number,DATE_FORMAT(m.date,'%d/%b/%Y') date, m.inspection, mt.name reason,m.fee,m.tds,m.vds,m.total, o.name orgname
                      FROM `maintenance` m LEFT JOIN maintenance_type mt ON m.reason=mt.id LEFT JOIN delivery_order d ON m.do_number=d.do_id LEFT JOIN quotation q ON q.socode=d.order_id 
                      LEFT JOIN organization o on q.organization=o.id
                      WHERE 1=1 $dateqry order by m.date desc"; 
       //echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            {
                if($row["inspection"] == 0) $inspection = 'No';
                else $inspection = 'YES';
                
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;
                $col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['code'])
    			            ->setCellValue($col3, $row['do_number'])
    			            ->setCellValue($col4, $row['date'])
    			            ->setCellValue($col5, $row['reason'])
    			            ->setCellValue($col6, $inspection)
    			            ->setCellValue($col7, $row['orgname'])
    			            ->setCellValue($col8, $row['fee'])
    			            ->setCellValue($col9, $row['tds'])
    			            ->setCellValue($col10, $row['vds'])
    			            ->setCellValue($col11, $row['total']);
    			            
    					    	/* */
    			//$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Maintenance');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'maintenance_item'.$today.'.xls'; 
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
        <span>Service</span>
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
    
                	<form method="post" action="maintenanceList.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                     <div class="col-sm-3 text-nowrap">
                            <h6>Service <i class="fa fa-angle-right"></i>All Maintenance Order</h6>
                       </div>

  <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
                            <input type="hidden" name="from_dt" id = "from_dt">
                            <input type="hidden" name="to_dt" id = "to_dt">
                            <div class="form-group">
                                <input type="text" class="form-control invdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Invoice Date Range" name="filter_date_from" id="filter_date_from"  value="" >
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
                    <table id='listTable' class='display dataTable actionbtn firstcolpad0' width="100%">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Maintenance</th>
                            <th>DO Number</th>
                            <th>Order Date</th>
                            <th>Reason</th>
                            <th>Inspection</th>
                            <th>Customer</th>
                            <th>Fee</th>
                            <th>TDS</th>
                            <th>VDS</th>
                            <th>Total Amount</th>
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
		<script src = "//cdn.datatables.net/plug-ins/1.10.25/api/sum().js"></script>

    
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
				pageLength: 50,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,
			   "order": [[ 0, "desc" ]],
				"dom": "rtiplf",
                'ajax': {
                    'url':url,
                },
                'columns': [
                    { data: 'id' },
                    { data: 'code' },
                    { data: 'do_number' },
                    { data: 'date' },
                    { data: 'reason' },
                    { data: 'inspection' },
                    { data: 'orgname' },
                    { data: 'fee' },
                    { data: 'tds' },
                    { data: 'vds' },
                    { data: 'total' },
					{ data: 'action', "orderable": false },
                ]
            });
			
			
            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
			
             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
			
	
	
	
	
	}

	//general call on page load
	url = 'phpajax/datagrid_service.php?action=maintenance';
	
	table_with_filter(url);        

//DATE FILTER STARTS	
$('#filter_date_from').daterangepicker({
    "autoApply": false,
    autoUpdateInput: false,
    locale: {
        format: 'DD/MM/YYYY',
        cancelLabel: 'Clear',
		"fromLabel": "From",
		"toLabel": "To",		
    },	
	
     "ranges": {
        "Today": [
			
            "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
            "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z"
        ],
        "Yesterday": [
			
            "<?=date("d/m/Y", strtotime("-1 days")); ?>T20:12:21.910Z",
            "<?=date("d/m/Y", strtotime("-1 days")); ?>T20:12:21.910Z"
        ],
        "Last 7 Days": [
            "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
            "<?=date("d/m/Y", strtotime("-7 days")); ?>T20:12:21.910Z"
        ],
        "Last 30 Days": [
            "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
            "<?=date("d/m/Y", strtotime("-30 days")); ?>T20:12:21.910Z"
        ],
		 <?php
		 //$query_date = date("d/m/Y");
		 //$firstdayofmonth = date('01/m/Y', strtotime($query_date));
		 //$lastdayofmonth = date('t/m/Y', strtotime($query_date));
	
		 $firstdayofmonth = date('01/m/Y');
		 $lastdayofmonth = date('t/m/Y');	
		 ?>
        "This Month": [
            "<?=$firstdayofmonth?>T18:00:00.000Z",
            "<?=$lastdayofmonth?>T17:59:59.999Z"
        ],
		 <?php
		 
		 $firstdayoflastmonth = date('d/m/Y', strtotime("first day of previous month"));
		 $lastdayoflastmonth = date('d/m/Y', strtotime("last day of previous month"));
		 ?>		 
        "Last Month": [
            "<?=$firstdayoflastmonth?>T18:00:00.000Z",
            "<?=$lastdayoflastmonth?>T17:59:59.999Z"
        ]
    },
    "linkedCalendars": false,
    "alwaysShowCalendars": true,
    "startDate": "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
    "endDate": "<?=date("d/m/Y", strtotime("-1 months")); ?>T20:12:21.910Z",
	maxDate: moment()
}, function(start, end, label) {
  console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
	
	//alert(start.format('YYYY-MM-DD'));
	if(start<end){
	url = 'phpajax/datagrid_service.php?action=maintenance&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
	}
	else
	{
	url = 'phpajax/datagrid_service.php?action=maintenance&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
	}
	//alert(url);
	//setTimeout(function(){
		table_with_filter(url);

	//}, 350);	
});

$('#filter_date_from').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
});	
	
$(".cancelBtn").click(function(){
	$('#filter_date_from').val("");
	url = 'phpajax/datagrid_service.php?action=maintenance';
	table_with_filter(url);
});
	
//ENDS DATE FILTER START

});
        </script>
        
<script>
$(document).ready(function(){
	
//show INVOICE
	
	$(".dataTable").on("click",".show-invoice",function(){
		
  	mylink = $(this).attr('href')+"?code="+$(this).data('code');
	
   //alert(mylink);
  
  	BootstrapDialog.show({
							
							title: 'Maintenance Invoice #'+$(this).data('code'),
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
