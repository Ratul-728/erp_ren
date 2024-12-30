<?php
require "common/conn.php";

session_start();
$usr=$_SESSION["user"];

if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    $td=$_POST['to_dt']; if($td==''){$td=date('d/m/Y', strtotime('+7 days'));}
    
    $bc1=$_POST['bc'];
    
   // $dagent=$_POST['cmbsupnm']; if($dagent==''){$dagent=0;}
    
    $branch = $_POST["cmbbranch"];
    

    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rpt_aging_stock';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/product.php?res=0&msg='Insert Data'&mod=1");
    }
   if ( isset( $_POST['export'] ) ) {
        $fd1 = $_POST["from_dt"];
        $td1 = $_POST["to_dt"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and p.voucher_date BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $branch = $_POST["cmbbranch"]; if($branch == '') $branch = 0;
        $cat = $_POST["cmbcat"]; if($cat == '') $cat = 0;
        $bc1 = $_POST["bc"];
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Category')
                ->setCellValue('C1', 'Product')
                ->setCellValue('D1', 'Barcode')
    			->setCellValue('E1', 'Store')
    			->setCellValue('F1', 'Purchase Date')
                ->setCellValue('G1', 'No of days old')
                ->setCellValue('H1', 'Qty')
                ->setCellValue('I1', 'Mrp')
    			->setCellValue('J1', 'Mrp Total');
    			
        $firststyle='A2';
        
        $qry="SELECT i.image,t.name tn,i.id,i.name pn,s.freeqty,s.costprice,i.rate mrp,r.name str,s.barcode,DATE_FORMAT(max(p.voucher_date),'%d/%b/%Y') purchagedt
        ,DATEDIFF(sysdate(),max(p.voucher_date)) nosdays 
        FROM 
        purchase_landing p ,purchase_landing_item pi,
        chalanstock s LEFT JOIN item i ON s.product = i.id 
        LEFT JOIN itmCat t ON i.catagory=t.id 
        LEFT JOIN branch r ON s.storerome=r.id
        where   
        p.id=pi.pu_id and pi.productId=i.id and
        s.`freeqty`>0  $dateqry and (s.barcode like '%".$bc1."%' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = 0 ) and ( t.id = ".$cat." or ".$cat." = 0 )
        GROUP by t.name ,i.name ,s.freeqty,s.costprice,i.rate ,r.name ,s.barcode";
        
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;$tcp=0;$tmp=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['tn'])
    						->setCellValue($col3, $row['pn'])
    					    ->setCellValue($col4, $row['barcode'])
    					    ->setCellValue($col5, $row['str'])
    					    ->setCellValue($col6, $row["purchagedt"])
    			            ->setCellValue($col7, number_format($row['freeqty'],0))
    						->setCellValue($col8, $row['nosdays'])
    					    ->setCellValue($col9, number_format($row['mrp'],2))
    					    ->setCellValue($col10, number_format($row['freeqty']*$row['mrp'],2));	/* */
    			$laststyle=$title;	
            }
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Expired  Stock Report ');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'aging_stock'.$today.'.xls'; 
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
  
                	<form method="post" action="#" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                       <div class="well list-top-controls">
                
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Inventory <i class="fa fa-angle-right"></i> Aging Report </h6>
                       </div>

                        

                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            
                            <input type="hidden" name="from_dt" id = "from_dt">
                            <input type="hidden" name="to_dt" id = "to_dt">
                            
                            <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbcat" id="cmbcat" class="form-control" >
                                            <option value="0">Category</option>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `itmCat` order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($cat == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            
                            <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbbranch" id="cmbbranch" class="form-control" >
                                            <option value="0">Store</option>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `branch` where status = 'A' order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($branch == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            
                            <div class="form-group">
                                    <input type="text" class="no-mg-btn form-control" id="bc" name="bc" placeholder="Bar Code" value="<?php echo $barcode; ?>"  >

                                </div>
                            <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Purchase Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div> 
                                
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>
                            <div class="form-group" id = "exportTable">
                                 
                            </div>
                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_aging_stock.php">
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
                    

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable productList' width="100%">
                        <thead>
                            
							<tr>
								<th>Sl</th>
								<th>Catagory</th>
								<th>Image</th>
								<th>Product</th>
								<th>Barcode </th>
								<th>Store</th>
								<th>Purchase Date</th>
								<th>No of days old</th>
								<th>Qty</th>
								<th>MRP</th>
								<th>MRP Total</th>
							</tr>
                        </thead>
                        <tfoot>
                            <tr class="total" style="background-color: #f5f5f5; color: #094446; font-size: 15px; padding: 10px; font-weight:bold" >
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
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
                    { data: 'tn'},
                    { data: 'photo', orderable: false },
                    { data: 'pn' },
                    { data: 'barcode', orderable: false },
                    { data: 'str' },
                    { data: 'makedt' },
                    { data: 'nosdays' },
                    { data: 'freeqty' },
                   // { data: 'costprice' },
                   // { data: 'costpr', orderable: false },
                    { data: 'mrp'},
                    { data: 'mrptotal', orderable: false },
					
                ],
                
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    $(api.column(8).footer()).html('Total: ');
                    var columnsToTotal = [9, 10]; // Indexes of the columns to total
                
                    columnsToTotal.forEach(function (colIndex) {
                        var colData = api.column(colIndex).data();
                        var total = colData.reduce(function (a, b) {
                            if (b !== null && b !== "") {
                                return a + parseFloat(b.replace(/,/g, ''));
                            }
                            return a;
                        }, 0);
                
                        var formattedTotal = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                        $(api.column(colIndex).footer()).html(formattedTotal);
                    });
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
	var dt = $('#to_dt').val();
	url = 'phpajax/datagrid_list_all.php?action=rpt_aging';
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_aging&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	$('#from_dt').val(start.format('YYYY-MM-DD'));
        	$('#to_dt').val(end.format('YYYY-MM-DD'));
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rpt_aging&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_aging';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	
    
    //Status
    $("#cmbbranch,#cmbcat,#bc").on("change input", function() {
            
            var branch = $('#cmbbranch').val();
            var bc = $('#bc').val();
            var cat = $('#cmbcat').val();
            
            var sdt = $('#from_dt').val();
        	var edt = $('#to_dt').val();
            
            var url = 'phpajax/datagrid_list_all.php?action=rpt_aging&dt_f='+sdt+'&dt_t='+edt+'&branch='+branch+'&barcode='+bc+'&cat='+cat;
			
			 setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        
        
    
    </body></html>
  <?php }?>    
