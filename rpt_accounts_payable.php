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
     $currSection = 'rpt_accounts_payable';
     include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    $fd1=$_POST['from_dt'];
    $td1=$_POST['to_dt'];
    
    if ( isset( $_POST['export'] ) ) {
        $fdt = $_POST["from_dt"];
        $td1 = $_POST["to_dt"];
        
        if($fdt == ''){
            $dateqry = "";
        }else{
            $dateqry = " and w.transdt <= STR_TO_DATE('$fdt', '%d/%m/%Y')";
        }
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Customer Code')
                ->setCellValue('C1', 'Customer Name')
                ->setCellValue('D1', 'Payable Amounts');
    			
        $firststyle='A2';
        
         $qry= "SELECT 
                                    customercode,
                                    customernm,
                                    balance
                                FROM (
                                    SELECT 
                                        o.orgcode AS customercode,
                                        o.name AS customernm,
                                        (
                                            (SELECT COALESCE(SUM(w.amount), 0) 
                                             FROM organizationwallet w 
                                             WHERE w.dr_cr = 'D' AND w.orgid = o.id $dateqry) 
                                            -
                                            (SELECT COALESCE(SUM(w.amount), 0) 
                                             FROM organizationwallet w 
                                             WHERE w.dr_cr = 'C' AND w.orgid = o.id $dateqry)
                                        ) AS balance
                                    FROM 
                                        organization o
                                ) r 
                                WHERE 
                                    r.balance > 0"; 
            
            
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
    			            ->setCellValue($col2, $row["customercode"])
    						->setCellValue($col3, $row['customernm'])
    					    ->setCellValue($col4, $row['balance']);	/* */
                
    			$laststyle=$title;
            }
            
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Accounts Payable Report ');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'accounts_payable_report'.$today.'.xls'; 
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
        <span>Accounts Payable Report</span>
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
                            <h6>Accounting <i class="fa fa-angle-right"></i> Accounts Payable Report </h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">

                            <div class ="form-group">
                                <div class="  pull-right col-lg-9 col-md-4 col-sm-4">
            					<div class="input-group">
            						<input type="text" class="form-control datepicker_history_filter" placeholder="Date" name="from_dt" id="from_dt" value="<?php echo $fdt;?>"  >
            						<div class="input-group-addon">
            							<span class="glyphicon glyphicon-th"></span>
            						</div>
            					</div>     
            				</div>
            				
                            </div> 
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_rpt_accounts_payable.php">
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
								<th>Customer Code</th>
                                <th>Customer Name</th>
                                <th>Payable Amounts</th>
							</tr>
                        </thead>
                        
                        <tfoot>
                            <tr class="total" style="background-color: #f5f5f5; color: #094446; font-size: 15px; padding: 10px; font-weight:bold" >
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
                    { data: 'customercode' },
                    { data: 'customernm' },
                    { data: 'balance'},
					
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    $(api.column(2).footer()).html('Total: ');
                    var columnsToTotal = [3]; // Indexes of the columns to total
                
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
	url = 'phpajax/datagrid_list_all.php?action=rpt_accounts_payable';
	table_with_filter(url);	

	
	
	
	
        $('#from_dt').datetimepicker({
            format: 'YYYY-MM-DD' // or your preferred format
        }).on('dp.change', function(e) {
            var fdt = e.date.format('YYYY-MM-DD'); // format as needed
            url = 'phpajax/datagrid_list_all.php?action=rpt_accounts_payable&dt_f=' + fdt;
            table_with_filter(url);
        });
			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script>
        

        
    </body></html>
  <?php }?>    
