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
    $currSection = 'rpt_defect_product';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/approval_transfer_stock.php?res=0&msg='Insert Data'&mod=7");
    }
   if ( isset( $_POST['export'] ) ) {
       
       $fdt = $_POST['from_dt'];
        $tdt = $_POST['to_dt'];
        
        if($fdt == ''){
            $dateqry = "";
        }else{
            $dateqry = " and d.makedt BETWEEN STR_TO_DATE('$fdt','%Y-%m-%d') and STR_TO_DATE('$tdt','%Y-%m-%d')";
        }
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Product')
                ->setCellValue('C1', 'Barcode')
                ->setCellValue('D1', 'Color Text')
    			->setCellValue('E1', 'Length')
    			->setCellValue('F1', 'Length Unit')
                ->setCellValue('G1', 'Width')
                ->setCellValue('H1', 'Width Unit')
                ->setCellValue('I1', 'Height')
    			->setCellValue('J1', 'Height Unit')
    			->setCellValue('K1', 'QA Qty')
                ->setCellValue('L1', 'Repairable Qty')
                ->setCellValue('M1', 'Approved By')
                ->setCellValue('N1', 'QA Type')
    			->setCellValue('O1', 'Against ID')
    			->setCellValue('P1', 'Status');
    			
        $firststyle='A2';
        $qry="SELECT i.name pnm,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit ,qw.ordered_qty total_qty,(case when d.st=0 then 'Decline' when d.st=2 then 'Approved' else  'Pending' end) st,
                      h.hrName approved_by, (case when qw.qa_type=1 then 'Sold' when qw.qa_type=2 then 'Purchase' when qw.qa_type=3 then 'Return' when qw.qa_type=4 then 'Transfer' 
                      when qw.qa_type=5 then 'Issue' when qw.qa_type=6 then 'Return' else 'na' end ) qatype,q.order_id,qw.defect_qty, qw.id qaw_id
                      FROM approval_defect d left join qa_warehouse qw on d.qaw_id=qw.id left join qa q on qw.qa_id=q.id
                      left join item i on q.product_id=i.id left join hr h on d.approved_by=h.id
                      WHERE 1=1 $dateqry
                      group by i.name,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,d.qty,d.st,d.approved_by,
                      q.order_id,qw.defect_qty,h.hrName"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;
                $col10='J'.$urut; $col11='K'.$urut;$col12='L'.$urut;$col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['pnm'])
    						->setCellValue($col3, $row['barcode'])
    					    ->setCellValue($col4, $row['colortext'])
    					     ->setCellValue($col5, $row['length'])
    					     ->setCellValue($col6, $row["lengthunit"])
    			            ->setCellValue($col7, $row['width'])
    						->setCellValue($col8, $row['widthunit'])
    					    ->setCellValue($col9, $row['height'])
    					     ->setCellValue($col10, $row['heightunit'])
    					     ->setCellValue($col11, $row["total_qty"])
    			            ->setCellValue($col12, $row['defect_qty'])
    						->setCellValue($col13, $row['approved_by'])
    					    ->setCellValue($col14, $row['qatype'])
    					     ->setCellValue($col15, $row['order_id'])
    					     ->setCellValue($col16, $row["st"]);
    					     	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('PO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'repairable_product_report_'.$today.'.xls'; 
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
        <span>Defect Product</span>
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
      		<!--	<div class="panel-heading"><h1>All Collection</h1></div> -->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>

                	<form method="post" action="#" id="form1">
            
                     <div class="well list-top-controls"> 
                     <!-- <div class="row border">
                       
                        <div class="col-sm-11 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div> -->
                        <div class="row border">
                          
                          
                          
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>Report <i class="fa fa-angle-right"></i>All Defect Product</h6>
                       </div>
                       
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">

                            <input type="hidden" name="from_dt" id = "from_dt">
                            <input type="hidden" name="to_dt" id = "to_dt">

                             <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Repaire Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div> 
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            <!--div class="form-group">
                            
                            <span id="pdfsource" url="pdf_purchase.php"></span>
								<?=getBtn('export')?>
							</div-->
							
							<div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_defect_product.php">
                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
								</ul>
							</div>

                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                        </div>
                        
                        </div>
                        
                        
                      </div>
                    </div>
                    
    
    				</form>
         <style>
         .ajax-img-up{
            border: 0px solid #000!important;
            display: flex;
            text-align: left;
        }
        .ajax-img-up ul{
          margin-bottom: 0;
          margin-left: 0!important;
            padding-left: 0px;
        }
        
        .ajax-img-up li{
          display: block;
          width: 40px;
          height: 40px;
          border: 1px solid #888787;
          position: relative;
          margin: 3px;
          border-radius: 0px;
          border-radius: 5px;
        }
        
        
        .ajax-img-up li img{
          width: 100%;
          height: 100%;
          border-radius: 5px;
        }

         </style>

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Barcode</th>
                            <th>Color Text</th>
                            <th>Length</th>
                            <th>Length Unit</th>
                            <th>Width</th>
                            <th>Width Unit</th>
                            <th>Height</th>
                            <th>Height Unit</th>
                            <th>QA Qty</th>
                            <th>Repairable Qty</th>
                            <th>Repairable Product Image</th>
                            <th>Approved By</th>
                            <th>QA Type</th>
                            <th>Against ID</th>
                            <th>Status</th>
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
                    
					'url':url,
                },
                
                'columns': [
                    { data: 'id' },
                    { data: 'image', "orderable": false },
                    { data: 'pnm', "orderable": false},
                    { data: 'barcode', "orderable": false },
                    { data: 'colortext', "orderable": false },
                    { data: 'length', "orderable": false },
					{ data: 'lengthunit', "orderable": false },
					{ data: 'width', "orderable": false },
					{ data: 'widthunit', "orderable": false },
					{ data: 'height', "orderable": false },
					{ data: 'heightunit', "orderable": false },
					{ data: 'total_qty', "orderable": false },
                    { data: 'defect_qty', "orderable": false},
                    { data: 'defect_image', "orderable": false },
                    { data: 'approved_by', "orderable": false },
                    { data: 'qatype', "orderable": false },
					{ data: 'order_id', "orderable": false },
					{ data: 'status', "orderable": false }
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
	url = 'phpajax/datagrid_qa.php?action=defect_rpt';
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
        	url = 'phpajax/datagrid_qa.php?action=defect_rpt&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	$('#from_dt').val(start.format('YYYY-MM-DD'));
        	$('#to_dt').val(end.format('YYYY-MM-DD'));
        	}
        	else
        	{
        	url = 'phpajax/datagrid_qa.php?action=defect_rpt&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
        	$('#from_dt').val(end.format('YYYY-MM-DD'));
        	$('#to_dt').val(start.format('YYYY-MM-DD'));
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
        	url = 'phpajax/datagrid_qa.php?action=defect_rpt';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START
            
        });
		
</script>  
<script>     
$(document).ready(function(){      
  $(".dataTables_wrapper").on("click",".picture-preview",function(){

		
  	    mylink = $(this).attr('href');
		
    //alert(mylink);
   // return false;
   

  
  		BootstrapDialog.show({
							
							title: 'Defect Picture',
    						message: $('<div id="printableArea4" align="center"><img src="'+mylink+'" width="100%"></div>'),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: true, // <-- Default value is false
							cssClass: 'picture-preview',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Close',
								action: function(dialog2) {
									dialog2.close();	
									
									
								}
							}],
							//onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
                        return false;
  
  
  
  
      	
    });

});
</script>  
        
    
    </body></html>
  <?php }?>    
