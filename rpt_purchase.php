<?php
require "common/conn.php";
require "rak_framework/misfuncs.php";
require "common/user_btn_access.php";

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
     $currSection = 'rpt_purchase';
     include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    $fd1=$_POST['from_dt'];
    $td1=$_POST['to_dt'];
    
    if ( isset( $_POST['export'] ) ) {
        //echo $fd1; die;
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D1', 'BitFlow')
                ->setCellValue('D2', 'Purchase Report')
                ->setCellValue('D3', '')
                ->setCellValue('A4', 'Sl')
                ->setCellValue('B4', 'Voucher No')
                ->setCellValue('C4', 'Voucher Date')
                ->setCellValue('D4', 'PI No.')
                ->setCellValue('E4', 'PI Date')
                ->setCellValue('F4', 'Supplier')
                ->setCellValue('G4', 'LC/TT No')
                ->setCellValue('H4', 'LC/TT Date')
                ->setCellValue('I4', 'Invoice Value (USD)')
                ->setCellValue('J4', 'Exchange Rate ')
                ->setCellValue('K4', 'Invoice Value (BDT)')
                ->setCellValue('L4', 'Freight Charges')
                ->setCellValue('M4', 'Global Taxes')
                ->setCellValue('N4', 'CD.')
                ->setCellValue('O4', 'RD')
                ->setCellValue('P4', 'SD')
                ->setCellValue('Q4', 'VAT')
                ->setCellValue('R4', 'Total Landed Cost')
                ->setCellValue('S4', 'AT')
                ->setCellValue('T4', 'AIT')
                ->setCellValue('U4', 'Received Location')
                ->setCellValue('V4', 'Received BY')
                ->setCellValue('W4', 'GNR No')
                ->setCellValue('X4', 'GNR Date')
                ->setCellValue('Y4', 'Product')
                ->setCellValue('Z4', 'Description')
                ->setCellValue('AA4', 'Barcode')
                ->setCellValue('AB4', 'QTY')
                ->setCellValue('AC4', 'Total Value')
                ->setCellValue('AD4', 'Bank Name')
                ->setCellValue('AE4', 'Bank Date')
                ->setCellValue('AF4', 'Payment Amount')
                ->setCellValue('AG4', 'Remarks'); 
                
        $firststyle='A7';
        if($fd1!=''){
            $dateqry = " and l.makedt BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $qry="SELECT l.id puid,l.`voucher_no`, DATE_FORMAT(l.`voucher_date`,'%d/%b/%Y') `voucher_date`,l.`pi_no`, DATE_FORMAT(l.`pi_date`,'%d/%b/%Y') `pi_date`,
                                sup.name `supplier`,l.`lc_tt_no`, DATE_FORMAT(l.`lc_tt_date`,'%d/%b/%Y') `lc_tt_date` ,i.`com_invoice_val_usd` ,
                                l.`exchange_rate` ,i.`com_invoice_val_bdt`,i.`freight_charges`,i.`global_taxes`,i.`cd`,i.`rd`,i.`sd`,i.`vat`,i.tot_landed_cost, l.`at`,l.`ait`,b.`name` received_location ,
                                h.hrName received_by,l.`gnr_no`, DATE_FORMAT(l.`gnr_date`,'%d/%b/%Y') `gnr_date` ,pr.name prod,pr.description,pr.barcode,i.`qty`,i.`tot_value`, 
                                bn.name banknm, DATE_FORMAT(l.`bank_dt`,'%d/%b/%Y') `bank_dt`,l.`payment_amount`, l.`remark` 
                                from purchase_landing l left join suplier sup ON sup.id=l.warehouse left join purchase_landing_item i on l.id=i.pu_id left join item pr on i.productId=pr.id
                                left join branch b on l.warehouse=b.id left join bank bn on l.`bank_name`=bn.id left join hr h on l.`received_by`=h.id
                                where 1=1  $dateqry
                                order by l.id desc"; 
        //echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $discounttot=0;$gtotal=0;$vat=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $col12='L'.$urut; $col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;$col17='Q'.$urut;$col18='R'.$urut;$col19='S'.$urut;$col20='T'.$urut;$col21='U'.$urut;
                $col22='V'.$urut;$col23='W'.$urut;$col24='X'.$urut;$col25='Y'.$urut;$col26='Z'.$urut;$col27='AA'.$urut;$col28='AB'.$urut;$col29='AC'.$urut;$col30='AD'.$urut;$col31='AE'.$urut;$col32='AF'.$urut;$col33='AG'.$urut;
                
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row["voucher_no"])
    						->setCellValue($col3, $row["voucher_date"])
    						->setCellValue($col4, $row["pi_no"])
    						->setCellValue($col5, $row["pi_date"])
    						->setCellValue($col6, $row["supplier"])
    						->setCellValue($col7, $row["lc_tt_no"])
    						->setCellValue($col8, $row["lc_tt_date"])
    						->setCellValue($col9, $row["com_invoice_val_usd"])
    						->setCellValue($col10, $row["exchange_rate"])
    						->setCellValue($col11, $row["com_invoice_val_bdt"])
    						->setCellValue($col12, $row["freight_charges"])
    						->setCellValue($col13, $row["global_taxes"])
    						->setCellValue($col14, $row["cd"])
    						->setCellValue($col15, $row["rd"])
    						->setCellValue($col16, $row["sd"])
    						->setCellValue($col17, $row["vat"])
    						->setCellValue($col18, $row["tot_landed_cost"])
    						->setCellValue($col19, $row["at"])
    						->setCellValue($col20, $row["ait"])
    						->setCellValue($col21, $row["received_location"])
    						->setCellValue($col22, $row["received_by"])
    						->setCellValue($col23, $row["gnr_no"])
    						->setCellValue($col24, $row["gnr_date"])
    						->setCellValue($col25, $row["prod"])
    						->setCellValue($col26, $row["description"])
    						->setCellValue($col27, $row["barcode"])
    						->setCellValue($col28, $row["qty"])
    						->setCellValue($col29, $row["tot_value"])
    						->setCellValue($col30, $row["banknm"])
    						->setCellValue($col31, $row["bank_dt"])
    						->setCellValue($col32, $row["payment_amount"])
    						->setCellValue($col33, $row["remark"]);	/* */
    			$laststyle=$title;	
            }
            $urut=$i+5;	$col3='I'.$urut;$col4='J'.$urut;
            $objPHPExcel->setActiveSheetIndex(0)
					
				    ->setCellValue($col3, 'Total')
					->setCellValue($col4, number_format($gt,2));
			
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Purchase Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'purchase_report'.$today.'.xls'; 
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
      			<!-- <div class="panel-heading"><h1>All Product</h1></div>  -->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
  
                	<form method="post" action="rpt_purchase.php?mod=12" id="form1" enctype="multipart/form-data">  
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
                            <h6>Inventory <i class="fa fa-angle-right"></i> Purchase Report </h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">

                            <input type="hidden" name="from_dt" id = "from_dt">
                            <input type="hidden" name="to_dt" id = "to_dt">

                             <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div> 
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            <!--div class="form-group">
                            
                            <span id="pdfsource" url="pdf_purchase.php"></span>
								<?=getBtn('export')?>
							</div-->
							
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
								<th>Voucher No</th>
                                <th>Voucher Date</th>
                                <th>PI NO</th>
                                <th>PI Date</th>
                                <th>Supplier</th>
                                <th>LC/TT No</th>
                                <th>LC/TT Date</th>
                                <th>Invoice Value (USD)</th>
                                <th>Exchange Rate</th>
                                <th>Invocie Value (BDT)</th>
                                <th>Freight Charges</th>
                                <th>Global Taxes</th>
                                <th>CD</th>
                                <th>RD</th>
                                <th>SD</th>
                                <th>Vat</th>
                                <th>Total Landed Cost</th>
                                <th>AT</th>
                                <th>AIT</th>
                                <th>Received Location</th>
                                <th>Received By</th>
                                <th>GNR No</th>
                                <th>GNR Date</th>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Description</th>
                                <th>Barcode</th>
                                <th>QTY</th>
                                <th>Total Value</th>
                                <th>Bank Name</th>
                                <th>Bank Date</th>
                                <th>Payment Amount</th>
                                <th>Remarks</th>
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
                    { data: 'voucher_no' },
                    { data: 'voucher_date' },
                    { data: 'pi_no'},
                    { data: 'pi_date' },
                    { data: 'supplier' },
                    { data: 'lc_tt_no' },
                    { data: 'lc_tt_date' },
                    { data: 'com_invoice_val_usd' },
                    { data: 'exchange_rate' },
                    { data: 'com_invoice_val_bdt' },
                    { data: 'freight_charges'},
                    { data: 'global_taxes' },
                    { data: 'cd' },
                    { data: 'rd'},
                    { data: 'sd' },
                    { data: 'vat' },
                    { data: 'tlc' },
                    { data: 'at' },
                    { data: 'ait' },
                    { data: 'received_location' },
                    { data: 'received_by', orderable: false  },
                    { data: 'gnr_no' },
                    { data: 'gnr_date'},
                    { data: 'image', orderable: false  },
                    { data: 'prod' },
                    { data: 'description' },
                    { data: 'barcode'},
                    { data: 'qty' },
                    { data: 'tot_value' },
                    { data: 'banknm' },
                    { data: 'bank_dt' },
                    { data: 'payment_amount' },
                    { data: 'remark', orderable: false }
					
                ],

                /*drawCallback:function(settings)
                {
                    
                        setTimeout(function(){
                        
                        var tot1 = settings.json.total[0];
                        
                        
                        var tf = '<tr><td colspan = "9"></td> <td style="color: #00abe3; font-weight:bold" >Total</td> <td style="color: #00abe3; font-weight:bold">'+tot1+
                        ' </td>';
                        
                        $("#listTable").append(
                            $('<tfoot/>').append( tf )
                        );
                        
                        
                        
                    },500);
                    
                    
     
                }*/
				 
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
	url = 'phpajax/datagrid_list_all.php?action=rpt_purchase';
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_purchase&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	$('#from_dt').val(start.format('YYYY-MM-DD'));
        	$('#to_dt').val(end.format('YYYY-MM-DD'));
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rpt_purchase&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_purchase';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	

			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        
        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
			    var sdt = $('#from_dt').val();
        	    var edt = $('#to_dt').val();
        	    //alert("sdt: "+sdt+" edt: "+edt);
				var pdfurl = "pdf_purchase.php?filter_date_from="+sdt+"&filter_date_to="+edt;
				location.href=pdfurl;
				
			});
			
		</script>

        
    </body></html>
  <?php }?>    
