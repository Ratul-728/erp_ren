<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];



if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {

    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'purchase_report';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if ( isset( $_POST['export'] ) ) {
         $fdt= $_POST['from_dt'];

          $tdt= $_POST['to_dt'];
          
          if($fdt != ''){
                //$date_qry = " and i.`invoicedt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') "; 
                $date_qry = " and p.`makedt` between '$fdt' and '$tdt' "; 
            }else{
                $date_qry = "";
            }
            
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Container No')
                ->setCellValue('C1', 'Purchase Order')
                ->setCellValue('D1', 'Voucher No')
    			->setCellValue('E1', 'PI No')
    			->setCellValue('F1', 'PI Date')
    			->setCellValue('G1', 'LC/TT No')
    			->setCellValue('H1', 'LC/TT Date')
    			->setCellValue('I1', 'Payment Amount')
                ->setCellValue('J1', 'BDT Value')
                ->setCellValue('K1', 'Freight Charges')
    			->setCellValue('L1', 'Global Taxes')
    			->setCellValue('M1', 'CD')
    			->setCellValue('N1', 'RD')
    			->setCellValue('O1', 'SD')
    			->setCellValue('P1', 'VAT')
                ->setCellValue('Q1', 'Total Landed Cost')
                ->setCellValue('R1', 'Total Value');
    			
        $firststyle='A2';
        
        $qry="select p.containerno, p.poid,p.voucher_no,p.pi_no,p.pi_date,p.lc_tt_no
            ,p.lc_tt_date,p.payment_amount ,
             sum(pi.com_invoice_val_bdt)com_invoice_val_bdt, sum(pi.freight_charges)freight_charges, sum(pi.global_taxes)global_taxes, sum(pi.cd)cd, sum(pi.rd)rd, sum(pi.sd)sd, sum(pi.vat)vat , sum(pi.tot_landed_cost)tot_landed_cost,sum(pi. tot_value)tot_value
            from purchase_landing p,purchase_landing_item pi 
            where p.id=pi.pu_id $date_qry
            group by p.containerno, p.poid,p.voucher_no,p.pi_no,p.pi_date,p.lc_tt_no
            ,p.lc_tt_date,p.payment_amount order by p.id desc";
        
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;$tcp=0;$tmp=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;
                $col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $col12='L'.$urut;$col13='M'.$urut;$col14='N'.$urut; $col15='O'.$urut;$col16='P'.$urut;$col17='Q'.$urut;
                $col18='R'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['containerno'])
    						->setCellValue($col3, $row['poid'])
    					    ->setCellValue($col4, $row['voucher_no'])
    					    ->setCellValue($col5, $row['pi_no'])
    					    ->setCellValue($col6, $row["pi_date"])
    					    ->setCellValue($col7, $row["lc_tt_no"])
    					    ->setCellValue($col8, $row["lc_tt_date"])
    					    ->setCellValue($col9, $row['payment_amount'])
    						->setCellValue($col10, $row['com_invoice_val_bdt'])
    					    ->setCellValue($col11, $row['freight_charges'])
    					    ->setCellValue($col12, $row['global_taxes'])
    					    ->setCellValue($col13, $row["cd"])
    					    ->setCellValue($col14, $row["rd"])
    					    ->setCellValue($col15, $row["sd"])
    					    ->setCellValue($col16, $row['vat'])
    						->setCellValue($col17, $row['tot_landed_cost'])
    					    ->setCellValue($col18, $row['tot_value']);	/* */
    			$laststyle=$title;	
            }
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Purchase Report ');
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
include_once 'common_header.php';
    ?>

    <body class="list">

    <?php
include_once 'common_top_body.php';
    ?>
    <div id="wrapper">

      <!-- Sidebar -->

      <div id="sidebar-wrapper" class="mCustomScrollbar">

      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>INVENTORY</span>
      </div>

    <?php
include_once 'menu.php';
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
                            <h6>Inventory <i class="fa fa-angle-right"></i> Purchase Report </h6>
                       </div>

                        

                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            
                            <!--div class="form-group">
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
                                </div-->
                            <div class="form-group">
                                <input type = "hidden" name="from_dt" id = "from_dt">
                                <input type = "hidden" name="to_dt" id = "to_dt">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >

                            </div>
                                
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_puchase_report.php">
                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
								</ul>
							</div>
						</div>

                        </div>


                      </div>
                    </div>
                    </form>


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table id='listTable' class=' table table-bordered table-hover dt-responsive' width="100%">
                        <thead>

							<tr>
							    <th>SL.</th>
								<th>Container No</th>
                                <th>Purchase Order</th>
                                <th>Voucher No</th>
                                <th>PI No</th>
                                <th>PI Date</th>
                                <th>LC/TT No</th>
                                <th>LC//TT Date</th>
                                <th>Payment Amount</th>
                                <th>BDT Value</th>
                                <th>Freight Charges</th>
                                <th>Global Taxes</th>
                                <th>CD</th>
                                <th>RD</th>
                                <th>SD</th>
                                <th>VAT</th>
                                <th>Total Landed Cost</th>
                                <th>Total Value</th>
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
include_once 'common_footer.php';
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
                    { data: 'id'},
                    { data: 'containerno'},
                    { data: 'poid' },
                    { data: 'voucher_no'},
                    { data: 'pi_no'},
                    { data: 'pi_date'},
                    { data: 'lc_tt_no'},
                    { data: 'lc_tt_date'},
                    { data: 'payment_amount'},
                    { data: 'com_invoice_val_bdt' },
                    { data: 'freight_charges'},
                    { data: 'global_taxes'},
                    { data: 'cd'},
                    { data: 'rd'},
                    { data: 'sd'},
                    { data: 'vat'},
                    { data: 'tot_landed_cost' },
                    { data: 'tot_value'}

                ]
				 
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
	url = 'phpajax/datagrid_report.php?action=purchase_report';
	table_with_filter(url);	
    
    //Status
    $("#cmbbranch,#cmbcat,#bc").on("change", function() {

            
            //var branch = $('#cmbbranch').val();
            var bc = $('#bc').val();
            var cat = $('#cmbcat').val();
            
            var url = 'phpajax/datagrid_report.php?action=inventory_status&barcode='+bc+'&cat='+cat;
			
			 setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
			
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

        	url = 'phpajax/datagrid_report.php?action=purchase_report&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
            $('#from_dt').val(start.format('YYYY-MM-DD'));
        	$('#to_dt').val(end.format('YYYY-MM-DD'));
        	}

        	else

        	{

        	url = 'phpajax/datagrid_report.php?action=purchase_report&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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

        	url = 'phpajax/datagrid_report.php?action=purchase_report';

        	table_with_filter(url);

        });

        	

        //ENDS DATE FILTER START	

		
        });
		
        </script> 


    </body></html>
  <?php } ?>
