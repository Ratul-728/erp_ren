<?php
require "common/conn.php";
require "rak_framework/misfuncs.php";
require "common/user_btn_access.php"; 

session_start();
$usr = $_SESSION["user"];
if ($usr == '') 
{
    header("Location: " . $hostpath . "/hr.php");
} 
else 
{
    $pyr=$_POST['cmbyr'];
    if($pyr==''){$pyr=date("Y");}    
    /*
    SELECT c.glno,c.glnm,m.yr,m.mn,m.closingbal
FROM `coa` c,`coa_mon` m where c.glno=m.glno and  substr(c.`glno`,1,1)=4 and c.lvl=3 and (( m.yr='2022' and  m.mn>'06') or (m.yr='2023' and m.mn<'07'))
order by c.`glno`,m.yr,m.mn;
    */
    require_once "common/PHPExcel.php";
    //common codes need to place every page. Just change the section name according to section
    //these 2 variables required to detecting current section and current page to use in menu.

    $currSection = 'rpt_expense_rdl_fin';
    include_once('common/inc_session_privilege.php');
    $currPage    = basename($_SERVER['PHP_SELF']);

    // if ( isset( $_POST['view'] ) ) {
    //header("Location: ".$hostpath."/rpt_invoice_payment.php?res=0&msg='Insert Data'");
   // $tdt = $_POST['tdt'];if ($tdt == '') {$tdt = date('d/m/Y');}
   // $fdt = $_POST['fdt'];if ($fdt == '') {$fdt = date('d/m/Y', strtotime('-1 month'));}
    $vctgl = $_GET['ctrgl'];if ($vctgl == '') {$vctgl ='';}
    $vlvl = $_GET['gllvl'];if ($vlvl == '') {$vlvl = '3';}
    //echo $vlvl;die;
    //}
    $ajxurl = "phpajax/datagrid_list_all.php?action=rpt_expense_rdl_fin&gllvl='.$vlvl.'&ctrgl=".$vctgl;
    //echo $ajxurl;
    if ( isset( $_POST['export'] ) ) {
        $gllvl= $_POST['flvl'];
        $glctrl= $_POST['fctrgl'];
        $sessyrf= $pyr;//$_GET['pyr'];
        $sessyrt=$sessyrf+1;
        $ctrlcond='';
        if($glctrl!='')
        {
            $ctrlcond=" and c.ctlgl='$glctrl' ";
        }
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Gl No')
                ->setCellValue('C1', 'Account Name')
                ->setCellValue('D1', 'Total')
    			->setCellValue('E1', 'July')
    			->setCellValue('F1', 'Aug')
                ->setCellValue('G1', 'Sep')
                ->setCellValue('H1', 'Oct')
                ->setCellValue('I1', 'Nov')
    			->setCellValue('J1', 'Dec')
    			->setCellValue('K1', 'Jan')
                ->setCellValue('L1', 'Fev')
                ->setCellValue('M1', 'Mar')
                ->setCellValue('N1', 'Apr')
    			->setCellValue('O1', 'May')
    			->setCellValue('P1', 'Jun');
    			
        $firststyle='A2';
        $jultot=0;$augtot=0;$septot=0;$octtot=0;$novtot=0;$dectot=0;$jantot=0;$febtot=0;$martot=0;$aprtot=0;$maytot=0;$juntot=0;
        $qry="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece 
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4  and c.lvl=$gllvl  $ctrlcond
                order by c.`glno`";
        
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;$tcp=0;$tmp=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $col12='L'.$urut; $col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;
                $i++;
                $jultot=$jultot+$row['jul'];$augtot=$augtot+$row['aug'];$septot=$septot+$row['sep'];$octtot=$octtot+$row['oct'];$novtot=$novtot+$row['nov'];$dectot=$dectot+$row['dece'];
                $jantot=$jantot+$row['jan'];$febtot=$febtot+$row['feb'];$martot=$martot+$row['mar'];$aprtot=$aprtot+$row['apr'];$maytot=$maytot+$row['may'];$juntot=$juntot+$row['jun'];
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['glno'])
    						->setCellValue($col3, $row['glnm'])
    						->setCellValue($col4, number_format($row['jun'],2))
    					    ->setCellValue($col5, number_format($row['jul'],2))
    					    //->setCellValue($col5, number_format($row['jul'],2))
    					    ->setCellValue($col6, number_format(($row['aug'] -$row['jul']),2))
    					    ->setCellValue($col7, number_format(($row['sep']-$row['aug']),2))
    					    ->setCellValue($col8, number_format(($row['oct']-$row['sep']),2))
    					    ->setCellValue($col9, number_format(($row['nov']-$row['oct']),2))
    					    ->setCellValue($col10, number_format(($row['dece']-$row['nov']),2))
    					    ->setCellValue($col11, number_format(($row['jan']-$row['dece']),2))
    					    ->setCellValue($col12, number_format(($row['feb']-$row['jan']),2))
    					    ->setCellValue($col13, number_format(($row['mar']-$row['feb']),2))
    					    ->setCellValue($col14, number_format(($row['apr']-$row['mar']),2))
    					    ->setCellValue($col15, number_format(($row['may']-$row['apr']),2))
    					    ->setCellValue($col16, number_format(($row['jun']-$row['may']),2))
    					    ;	/* */
    			$laststyle=$title;	
            }
        }
                $i++;
                $col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $col12='L'.$urut; $col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;
                /*$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col3, 'TOTAL')
                    ->setCellValue($col4, number_format($jultot,2))
				    //->setCellValue($col5, number_format($row['jul'],2))
    			    ->setCellValue($col5, number_format($augtot,2))
    			    ->setCellValue($col6, number_format($septot,2))
    			    ->setCellValue($col7, number_format($octtot,2))
    			    ->setCellValue($col8, number_format($novtot,2))
    			    ->setCellValue($col9, number_format($dectot,2))
    			    ->setCellValue($col10, number_format($jantot,2))
    			    ->setCellValue($col11, number_format($febtot,2))
    			    ->setCellValue($col12, number_format($martot,2))
    			    ->setCellValue($col13, number_format($aprtot,2))
    			    ->setCellValue($col14, number_format($maytot,2))
    			    ->setCellValue($col15, number_format($juntot,2));*/
        
        $objPHPExcel->getActiveSheet()->setTitle('Expense  Report ');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'expense_report_'.$today.'.xls'; 
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
        <span>Invoice</span>
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
            <div class="col-lg-12 col-xs-11">

            <p>&nbsp;</p>
            <p>&nbsp;</p>

              <!--h1 class="page-title">Customers</a></h1-->
              <p>
              <!-- START PLACING YOUR CONTENT HERE -->


              <div class="panel panel-info">
      			<!-- <div class="panel-heading"><h1 class="left-align">All Expenses </h1></div> -->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="#" id="form1">
                            <div class="well list-top-controls">
                                <div class="row border">
                                    <!--div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div-->
                                    <div class="col-sm-4">
                                        <div class="col-lg-12 text-nowrap">
                                            <h6>Accounting <i class="fa fa-angle-right"></i> Expense</h6>
                                        </div>
                                    </div>
                                    <div class="col-sm-7 col-lg-8 text-nowrap">
                                        <div class="pull-right grid-panel form-inline">
                                            <!--div class="form-group">
                                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                                            </div-->
                                            <div class="col-lg-1 col-md-6 col-sm-6">
                                                <div class="input-group">
                                                    <div >Year </div>
                                                </div>     
                                            </div> 
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <div class="input-group">
                                                    <select name="cmbyr" id="cmbyr" name "cmbyr" class="form-control" required>
                                                    <?php $yr=date("Y");?> 
                                                        <option value="<? echo $yr; ?>" <? if ($pyr == $yr) { echo "selected"; } ?>><? echo $yr.'-'.($yr+1); ?></option>
                                                        <option value="<? echo $yr-1; ?>" <? if ($pyr == $yr-1) { echo "selected"; } ?>><? echo ($yr-1).'-'.$yr; ?></option>
                                                        <option value="<? echo $yr+1; ?>" <? if ($pyr == $yr+1) { echo "selected"; } ?>><? echo ($yr+1).'-'.($yr+2); ?></option>
                                                    </select>
                                                </div>     
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden"  name="flvl" id="flvl" value="<?php echo $vlvl; ?>">
                                                <input type="hidden"  name="fctrgl" id="fctrgl" value="<?php echo $vctgl; ?>">
                                                <input type="search" id="search-dttable" class="form-control">
                                            </div>
                                
                                            <div class="form-group">
                                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  >
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


					<table id="listTable" class="table display dataTable no-footer actionbtns" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;">

					<!--table id="listTable" class="display dataTable no-footer actio nbtn" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;"-->
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Gl NO</th>
                            <th>Account Name</th>
                            <th>Total</th>
                            <th>July</th>
                            <th>Aug</th>
                            <th>Sep</th>
                            <th>Oct</th>
                            <th>Nov</th>
                            <th>Dec</th>
                            <th>Jan</th>
                            <th>Feb</th>
                            <th>Mar</th>
                            <th>Apr</th>
                            <th>May</th>
                            <th>Jun</th>
                            
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

    <div id = "divBackground" style="position: fixed; z-index: 999; height: 100%; width: 100%; top: 0; left:0; background-color: Black; filter: alpha(opacity=60); opacity: 0.6; -moz-opacity: 0.8;display:none">

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
                   { data: 'id' },
                    { data: 'glno' },
                    { data: 'glnm' },
                    { data: 'tot' },
                    { data: 'jul' },
                    { data: 'aug' },
                    { data: 'sep' },
                    { data: 'oct' },
                    { data: 'nov' },
                    { data: 'dec' },
                    { data: 'jan' },
                    { data: 'feb' },
                    { data: 'mar' },
                    { data: 'apr' },
                    { data: 'may' },
                    { data: 'jun' },
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    $(api.column(2).footer()).html('Total: ');
                    var columnsToTotal = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]; // Indexes of the columns to total
                
                    columnsToTotal.forEach(function (colIndex) {
                        var colData = api.column(colIndex).data();
                        var total = colData.reduce(function (a, b) {
                            return a + parseFloat(b.replace(/,/g, ''));
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
	var plvl=$("#flvl").val();
	var pctrl=$("#fctrgl").val();
	var yr=$("#cmbyr").val();
//	alert(lvl);
	url = 'phpajax/datagrid_list_all.php?action=rpt_expense_rdl_fin&gllvl='+plvl+'&ctrgl='+pctrl+'&pyr='+yr;
	//	alert(url);
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_expense_rdl_fin&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rpt_expense_rdl_fin&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_expense_rdl_fin';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	

			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        
        <script type="text/javascript">

    function openpopup(popurl){
       var popUpObj;
    popUpObj=window.open(popurl,"ModalPopUp","toolbar=no," +"scrollbars=no," + "location=no," + "statusbar=no," + "menubar=no," + "resizable=0," + "modal=yes,"+
    "width=400," +"height=310," + "left = 290," +"top=200"  );
    popUpObj.focus();
    //LoadModalDiv();


    }
    </script>
    
    <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				var plvl=$("#flvl").val();
	            var pctrl=$("#fctrgl").val();
            
				var pdfurl = 'pdf_expense_rdl_fin.php?gllvl='+plvl+'&ctrgl='+pctrl;
				location.href=pdfurl;
				
			});
			
			
		</script>


    </body></html>
  <?php } ?>
