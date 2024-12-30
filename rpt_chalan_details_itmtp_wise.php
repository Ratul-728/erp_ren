<?php
require "common/conn.php";

session_start();
$usr=$_SESSION["user"];


if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
     $currSection = 'rpt_chalan_details_itmtp_wise';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    $fd1=$_POST['from_dt'];
    $td1=$_POST['to_dt'];
    if($fd1==''){$fd1=date("1/m/Y");}
    if($td1==''){$td1=date("d/m/Y");}
    
    if ( isset( $_POST['export'] ) ) {
        //echo $fd1; die;
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D1', 'BitFlow')
                ->setCellValue('D2', 'Invoice Based Sales Report')
                ->setCellValue('D3', 'Date   '.$fd1.'   To   '.$td1.'')
                ->setCellValue('A4', 'Sl')
                ->setCellValue('B4', 'Catagory')
                ->setCellValue('C4', 'Challan Date')
                ->setCellValue('D4', 'Advice No.')
                ->setCellValue('E4', 'Received Date')
                ->setCellValue('F4', 'Product')
                ->setCellValue('G4', 'Barcode')
                ->setCellValue('H4', 'Quantity')
                ->setCellValue('I4', 'Unit Proice')
                ->setCellValue('J4', 'Total ')
                ->setCellValue('K4', 'Expiry Date '); 
    			
        $firststyle='A7';
        $qry="SELECT p.poid,p.adviceno,DATE_FORMAT(p.orderdt,'%e/%c/%Y') orderdt,DATE_FORMAT(p.delivery_dt,'%e/%c/%Y') received_dt ,t.name cat,i.itemid,
                                pr.name product,i.qty,i.unitprice,i.amount,i.barcode,DATE_FORMAT(i.expirydt,'%e/%c/%Y') expirydt 
                                FROM po p LEFT JOIN poitem i ON p.poid=i.poid LEFT JOIN product pr ON pr.id=i.itemid LEFT JOIN itemtype t ON pr.catagory=t.id 
                                order by p.poid desc"; 
        //echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $discounttot=0;$gtotal=0;$vat=0;
            while($row = $result->fetch_assoc()) 
            { 
                $order_id=$row["poid"];$adviceno=$row["adviceno"]; $orderdt=$row["orderdt"]; $received_dt=$row2["received_dt"];  
    $cat=$row["cat"]; $product=$row["product"]; $qty=$row["qty"];$barcode=$row["barcode"];$expdt=$row["expirydt"];
    $unitprice=$row["unitprice"];$amount=$row["amount"]; $gt=$gt+$amount;
    
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $cat)
    						->setCellValue($col3, $orderdt)
    						->setCellValue($col4, $adviceno)
    						->setCellValue($col5, $received_dt)
    						->setCellValue($col6, $product)
    						->setCellValue($col7, $barcode)
    						->setCellValue($col8, number_format($qty,0))
    						->setCellValue($col9, number_format($unitprice,2))
    						->setCellValue($col10, number_format($amount,2))
    						->setCellValue($col11, $expdt);	/* */
    			$laststyle=$title;	
            }
            $urut=$i+5;	$col3='I'.$urut;$col4='J'.$urut;
            $objPHPExcel->setActiveSheetIndex(0)
					
				    ->setCellValue($col3, 'Total')
					->setCellValue($col4, number_format($gt,2));
			
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Stock Receipt Summery');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'stock'.$today.'.xls'; 
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
        <span>Challan Order</span>
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
      			<!-- <div class="panel-heading"><h1>All Product</h1></div>  -->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
  
                	<form method="post" action="rpt_chalan_details_itmtp_wise.php?mod=12" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
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
                            <h6>Inventory <i class="fa fa-angle-right"></i> Stock Purchase Report </h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">



                             <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div> 
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            <div class="form-group">
                            
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
                    
                    <!--div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group"> <br><br>
                            <label>Summary </label><br>
                            <label >VAT Asseable | <span id = "vatasse"></span> </label><br>
                            <label >VAT Amount | <span id = "vatam"></span> </label><br>
                            <label >Price Incl VAT| <span id = "prinvat"></span> </label><br>
                        </div>          
                    </div-->
                    

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable productList' width="100%">
                        <thead>
                            
							<tr>
								<th>Sl</th>
								<th>Product Catagory </th>
                                <th>Challan Date</th>
                                <th>Challan NO</th>
                                <th>Advice No</th>
                                <th>Receved Date </th>
                                <th>Product</th>
                                <th>Barcode</th>
                                <th>Qty </th>
                                <th>Unit price </th>
                                <th>Total price </th>
                                <th>Expiry Date </th>
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
                    { data: 'id', orderable: false },
                    { data: 'cat' },
                    { data: 'orderdt' },
                    { data: 'poid'},
                    { data: 'adviceno' },
                    { data: 'received_dt' },
                    { data: 'product' },
                    { data: 'barcode' },
                    { data: 'qty' },
                    { data: 'unitprice' },
                    { data: 'amount' },
                    { data: 'expirydt'}
					
                ],

                drawCallback:function(settings)
                {
                    
                        setTimeout(function(){
                        
                        var tot1 = settings.json.total[0];
                        
                        
                        var tf = '<tr><td colspan = "9"></td> <td style="color: #00abe3; font-weight:bold" >Total</td> <td style="color: #00abe3; font-weight:bold">'+tot1+
                        ' </td>';
                        
                        $("#listTable").append(
                            $('<tfoot/>').append( tf )
                        );
                        
                        
                        
                    },500);
                    
                    
     
                }
				 
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
	url = 'phpajax/datagrid_list_all.php?action=rpt_chalan_details';
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_chalan_details&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rpt_chalan_details&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_chalan_details';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	

			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        

        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
				var fdate = $("#from_dt").val();
				var tdate = $("#to_dt").val();
				var pdfurl = "pdf_stock_purchase.php?filter_date_from="+fdate+"&filter_date_to="+tdate;
				location.href=pdfurl;
				
			});
			
		</script>
    
    </body></html>
  <?php }?>    
