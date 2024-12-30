<?php
require "common/conn.php";
require "rak_framework/misfuncs.php";
include_once('../rak_framework/fetch.php');
require "common/user_btn_access.php";



session_start();
$usr=$_SESSION["user"];
$res= $_GET['res'];
$msg= $_GET['msg'];

$fvouch = $_POST['vouch']; if($fvouch == '') $fvouch = 0;
$cmbvouch = $_POST['vouch'];
$fdt = $_POST['filter_date_from'];
$tdt = $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("d/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}
//echo $tdt;die;
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rpt_gl_ledger';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    
    
    if ( isset( $_POST['add'] ) ) {
            
            
            
           header("Location: ".$hostpath."/hraction.php?res=0&msg='Insert Data'&mod=4");
    }
   if ( isset( $_POST['export'] ) ) {
      // print_r($_POST);die;
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Vouch No')
                ->setCellValue('C1', 'Transaction Date')
                ->setCellValue('D1', 'Reference')
    			->setCellValue('E1', 'Remarks')
    		    ->setCellValue('F1', 'SL')
                 ->setCellValue('G1', 'GL Acccount')
                ->setCellValue('H1', 'GL Name')
                ->setCellValue('I1', 'Debit')
                ->setCellValue('J1', 'Credit')
                ->setCellValue('K1', 'Ledger');
    			
        $firststyle='A2';
        $opbal=0;
        $glnature= fetchByID('coa','glno',$fvouch,'dr_cr');
        
        $opbalqry="select (COALESCE(o.opbal,0)+COALESCE(a.amt,0)-COALESCE(b.amt,0)) op
        from
        (select opbal from coa_mon where  glno='$fvouch' 
        and yr=year(STR_TO_DATE('".$fdt."','%d/%m/%Y')) and mn=month(STR_TO_DATE('".$fdt."','%d/%m/%Y'))
        )o
         ,
        (select sum(d.amount) amt from glmst m, gldlt d 
        where m.vouchno=d.vouchno  and d.dr_cr='D' and  d.glac='$fvouch' and m.isfinancial in('0','A')
        and ( m.transdt between DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'%Y-%m-01')  and STR_TO_DATE('".$fdt."','%d/%m/%Y'))
        )a
        ,(select sum(d.amount) amt from glmst m, gldlt d 
        where m.vouchno=d.vouchno  and d.dr_cr='C' and  d.glac='$fvouch' and m.isfinancial in('0','A')
        and (m.transdt between DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'%Y-%m-01')  and STR_TO_DATE('".$fdt."','%d/%m/%Y'))
        )b";

        $resultopbal = $conn->query($opbalqry); 
        while($rowopbal = $resultopbal->fetch_assoc()) {
            $opbal = $rowopbal["op"];
        }
       
        if($glnature=='D')
        {
        if($opbal>0)
        {$d_bal=$opbal;$c_bal=0;}
        else {$d_bal=0;$c_bal=$opbal;}
        }
        else
        {
        if($opbal>0)
        {$d_bal=0;$c_bal=$opbal;}
        else {$d_bal=$opbal;$c_bal=0;}
        }
       /*
        if($opbal>0) 
        {$d_bal=$opbal;$c_bal=0;}
        else {$d_bal=0;$c_bal=$opbal;}
        */
        
        
        $qry="select '' VouchNo,DATE_FORMAT(STR_TO_DATE('".$fdt."', '%d/%m/%Y'), '%d/%b/%Y') AS TransDt,'' refno,'Opening Balance' remarks,'' sl,'' glac,'' glnm,$d_bal D_amount, $c_bal C_amount
                   union all
                   select a.VouchNo,DATE_FORMAT( a.TransDt,'%d/%b/%Y') TransDt ,a.refno,a.remarks,d.sl,d.glac,g.glnm,COALESCE((case d. dr_cr when 'D' then d.amount else 0 End),0) D_amount,COALESCE((case d.dr_cr when 'C' then d.amount else 0 End),0) C_amount  
                                 from glmst a  join gldlt d on a.VouchNo=d.VouchNo and a.isfinancial in('0','A')
                                  join coa g on d.glac=g.glno
                                 where (d.glac='$fvouch'  )
                                 and (a.TransDt  between  STR_TO_DATE('".$fdt."','%d/%m/%Y')  and STR_TO_DATE('".$tdt."','%d/%m/%Y')) order by TransDt,VouchNo"; 
       //echo  $qry;die;
      $cl=0;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
               // $cl=$cl+$row['D_amount']-$row['C_amount'];
                if($glnature=='D')
                {
                    $cl=$cl+$row["D_amount"]-$row["C_amount"];
                }
                else
                {
                    $cl=$cl-$row["D_amount"]+$row["C_amount"];
                } 
                
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['VouchNo'])
    						->setCellValue($col3, $row['TransDt'])
    					    ->setCellValue($col4, $row['refno'])
    					     ->setCellValue($col5, $row['remarks'])
    					     ->setCellValue($col6, $row['sl'])
    					    ->setCellValue($col7, $row['glac'])
    					    ->setCellValue($col8, $row['glnm'])
    					    ->setCellValue($col9, $row['D_amount'])
    					    ->setCellValue($col10, $row['C_amount'])
    					    ->setCellValue($col11, $cl);
    					    	/* */
    			//$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('glLedger');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'rpt_gl_ledger'.$today.'.xls'; 
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
        <span>GL Voucher Report</span>
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
      			<!--<div class="panel-heading"><h1>All Hr Action</h1></div>-->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    
                	<form method="post" action="rpt_gl_ledger.php?pg=1&mod=7" id="form1">
            
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
                            <h6>Account <i class="fa fa-angle-right"></i>GL Voucher Report</h6>
                       </div>
                       
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
                            
                            <!--<div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">     
                            </div>
                            
                            
                            <!-- GL Account -->
                
							<div class="form-group" style="width: 250px;">
								<div class="styled-select" >
                                                
                                                
                                                    <input list="cmborg1" name ="vouch" value = "<?= $cmbvouch ?>" autocomplete="Search From list"  class="dl-cmborg datalist" placeholder="Select GL">
                                                    <datalist  id="cmborg1" name = "cmborg1" class="list-cmbassign form-control" >
                        <?php $qryitm="SELECT `glno`,`glnm` FROM `coa` WHERE `isposted`='P' order by `glno`"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
                                  {
                                      $tid= $rowitm["glno"];  $nm=$rowitm["glnm"]; ?>
                                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $tid; ?>" ><?php echo $nm; ?></option>
                        <?php  }}?>                    
                                                    </datalist> 
                                                    <input type="hidden"  name ="fvouch" id="fvouch"  value = "<?=($_REQUEST['fvouch'])?$_REQUEST['fvouch']:''?>"> 
                                                
                               </div> 
                            </div> 
				
                            <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="<?=($_REQUEST['filter_date_from'])?$_REQUEST['filter_date_from']:''?>" >
                            </div>
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">     
                            </div>
                            <div class="form-group">
                                <button type="button" title="View data"   id="vew"  name="view"  class="form-control btn btn-default btn-search"><i class="fa fa-search"></i></button>
                            </div>
                            <input type="hidden" id="pdfsource" url="pdf_gl_ledger.php">
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
                    <table id='listTable' class='display dataTable actionbtn firstcolpad0' width="100%">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Vouch No</th>
                            <th>Transaction Date</th>
                            <th>Reference</th>
                            <th>Remarks</th>
                            <th>SL</th>
                            <th>GL Account </th>
                            <th>GL Name</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Ledger Balance</th>
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
		
	    function table_with_filter(url){
	
        	 var table1 =  $('#listTable').DataTable().destroy();
             var table1 = $('#listTable').DataTable({
                
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 20005, 
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				//paging: false,
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
                    { data: 'VouchNo' },
                    { data: 'TransDt' },
                    { data: 'refno' },
                    { data: 'remarks' },
                    { data: 'sl' },
                    { data: 'glac' },
					{ data: 'glnm' },
					{ data: 'D_amount' },
					{ data: 'C_amount' },
					{ data: 'lb' },
					
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    $(api.column(7).footer()).html('Total: ');
                    var columnsToTotal = [8, 9]; // Indexes of the columns to total
                
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
	
	
	url = 'phpajax/datagrid_list_all.php?action=rpt_gl_ledger&fvouch=<?= $fvouch; ?>';
	table_with_filter(url);	
        
        	//alert(start.format('YYYY-MM-DD'));
      
        
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
            //console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
            
        	var fvouch = $('input[name="vouch"]').val();
            var fvouch_query = (fvouch) ? '&fvouch=' + fvouch : '&fvouch=0';              
        	//alert('ppf'+fvouch_query);

        	if(start<end){
        	    //url = 'phpajax/datagrid_list_all.php?action=rpt_gl_ledger&fvouch=<?= $fvouch; ?>&fdt='+start.format('DD/MM/YYYY')+'&tdt='+end.format('DD/MM/YYYY');
        	    url = 'phpajax/datagrid_list_all.php?action=rpt_gl_ledger'+fvouch_query+'&fdt='+start.format('DD/MM/YYYY')+'&tdt='+end.format('DD/MM/YYYY');
        	}
        	else
        	{
        	    //url = 'phpajax/datagrid_list_all.php?action=rpt_gl_ledger&fvouch=<?= $fvouch; ?>&fdt='+end.format('DD/MM/YYYY')+'&tdt='+start.format('DD/MM/YYYY');
        	    url = 'phpajax/datagrid_list_all.php?action=rpt_gl_ledger'+fvouch_query+'&fdt='+end.format('DD/MM/YYYY')+'&tdt='+start.format('DD/MM/YYYY');
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_gl_ledger&fvouch=<?= $fvouch; ?>';
        	table_with_filter(url);
        });
        
        
        
        
    $(document).ready(function() {
        $('input[name="vouch"]').on('input', function() {
            var selectedValue = $(this).val();
            console.log('Selected value: ' + selectedValue);
             //$('#filter_date_from').change();
             $("#fvouch").attr('value',selectedValue);
             
             var urlqeurystringvalue = 'fvouch='+selectedValue;
            $("#urlqeurystring").val(urlqeurystringvalue);
                
            // You can add more actions here
        });
    });        

        $(document).ready(function() {
            $('#form1_').on('submit', function(event) {
               // alert($('#fvouch').attr('value'));
               //alert($('#fvouch').attr('value').length);
               
                if ($('#fvouch').attr('value').length > 0) {
                    console.log("Datalist exists");
                } else {
                    swal('Please select GL first');
                    console.log("Datalist does not exist");
                    return false;
                }
        
                // Proceed with form submission or prevent if needed
                // event.preventDefault();  // Uncomment if you want to stop form submission
            });
        });
        
        
    $(document).ready(function() {
        $('.btn-search').on('click', function() {
            // Get the value of fvouch
            var fvouch = $('#fvouch').val();
            /*
            if(!fvouch){
                swal('Please select GL first');
                $('#fvouch').focus(); 
                return false;
            }
            */
            
            // Get the value of the date range and split it into from and to dates
            var dateRange = $('#filter_date_from').val();
             if(!dateRange){
               swal('Please select a date range');
               $('#filter_date_from').focus();
               return false;
            }
            var dateParts = dateRange.split(' - ');  // Splits '01/07/2024 - 31/07/2024' into ['01/07/2024', '31/07/2024']
            var fromDate = dateParts[0];  // Start date
            var toDate = dateParts[1];    // End date
            
            // Construct the URL
            var url = 'phpajax/datagrid_list_all.php?action=rpt_gl_ledger&fvouch=' + fvouch + '&fdt=' + fromDate + '&tdt=' + toDate;
            
            // Redirect to the generated URL
            table_with_filter(url); 
    
            // Alternatively, if you don't want to redirect but just log or use the URL:
            // console.log(url);
        });
    });        

		
        </script>  
        
<!--script>
    $(document).ready(function() {
        $('#export').on('click', function(e) {
            e.preventDefault(); // Prevent the default button action
    
            // Get the values of the required fields
            var fvouch = $('#fvouch').val();
            if(!fvouch){
                swal('Please select GL first');
                $('#fvouch').focus(); 
                return false;
            }
            
            var dateRange = $('#filter_date_from').val();
             if(!dateRange){
               swal('Please select a date range');
               $('#filter_date_from').focus();
               return false;
            }
            var dateParts = dateRange.split(' - ');  // Splits '01/07/2024 - 31/07/2024' into ['01/07/2024', '31/07/2024']
            var fromDate = dateParts[0];  // Start date
            var toDate = dateParts[1];    // End date            
    
            // Create a dynamic form
            var form = $('<form>', {
                action: 'rpt_gl_ledger.php', // Target PHP file
                method: 'POST'
            });
    
            // Append hidden input fields to the form
            form.append($('<input>', { type: 'hidden', name: 'vouch', value: fvouch }));
            form.append($('<input>', { type: 'hidden', name: 'filter_date_from', value: fromDate }));
            form.append($('<input>', { type: 'hidden', name: 'filter_date_to', value: toDate }));
            form.append($('<input>', { type: 'hidden', name: 'export', value: 'Export' }));
            
            
    
            // Append the form to the body and submit it
            form.appendTo('body').submit();
        });
    });
</script-->
    
        
    
    </body></html>
  <?php }?>    
