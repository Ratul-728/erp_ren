<?php
require "common/conn.php";
require "rak_framework/misfuncs.php";
require "common/user_btn_access.php";

session_start();

  //if($_POST){     print_r($_POST);die; }


$usr=$_SESSION["user"];
$res= $_GET['res'];
$msg= $_GET['msg'];

$fvouch = $_POST['vouch']; if($fvouch == '') $fvouch = 0;
$fglno = $_POST['cmborg'];
if ($fglno == '') {
    $fglno   = 0;
    $fglname = '';
} else {
    $qrygl    = "SELECT DISTINCT `vouchno` glnm,`vouchno` glno FROM  glmst where vouchno='" . $fglno . "'";
    $resultgl = $conn->query($qrygl);
    while ($rowgl = $resultgl->fetch_assoc()) {
        $fglname = $rowgl["glnm"];
    }

}

$cmbvouch = $_POST['vouch'];
$myArray = explode('-', $_POST['filter_date_from']);

$fdt = $myArray[0];//$_POST['filter_date_from'];
$tdt=$myArray[1];
//echo ($fdt."=".$tdt);die;
//$tdt = $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("1/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}

if($usr=='') 
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rpt_gl_voucher';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/hraction.php?res=0&msg='Insert Data'&mod=4");
    }
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Vouch No')
                ->setCellValue('C1', 'Transfer Date')
                ->setCellValue('D1', 'Reference') 
    			->setCellValue('E1', 'Remarks')
    		    ->setCellValue('F1', 'SL')
                 ->setCellValue('G1', 'GL Acccount')
                ->setCellValue('H1', 'GL Name')
                ->setCellValue('I1', 'Debit')
                ->setCellValue('J1', 'Credit');
    			 
        $firststyle='A2';
        $qry="select a.VouchNo,DATE_FORMAT(a.TransDt, '%m/%d/%Y') TransDt ,a.refno,a.remarks,d.sl,d.glac,g.glnm,org.name customer,
                                        (case d. dr_cr when 'D' then d.amount else 0 End) D_amount,(case d.dr_cr when 'C' then d.amount else 0 End) C_amount  
                                        from glmst a  left join gldlt d on a.VouchNo=d.VouchNo left join coa g on d.glac=g.glno
                                        LEFT JOIN invoice inv ON (inv.invoiceno=a.refno or inv.soid = a.refno) LEFT JOIN organization org ON org.id = inv.organization
                                    	where a.isfinancial in('0','A') and a.VouchNo= '$fglno' or ('$fglno' = '0'  and
                                    	(a.TransDt  between  STR_TO_DATE('$fdt','%d/%m/%Y')  and STR_TO_DATE('$tdt','%d/%m/%Y'))) order by a.TransDt"; 
        //echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $dr=$row['D_amount']; $cr=$row['C_amount'];$trdt=$row['TransDt'];
                $i++;
                // Format date as Excel date value
                $dateValue = PHPExcel_Shared_Date::PHPToExcel(strtotime($row['TransDt']));
        
                $objPHPExcel->setActiveSheetIndex(0) 
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['VouchNo'])
    						->setCellValue($col3, $dateValue)
    					    ->setCellValue($col4, $row['refno'])
    					     ->setCellValue($col5, $row['remarks'])
    					     ->setCellValue($col6, $row['sl'])
    					    ->setCellValue($col7, $row['glac'])
    					    ->setCellValue($col8, $row['glnm'])
    					    ->setCellValue($col9, $dr)
    					    ->setCellValue($col10, $cr);
    		// Apply date format to column B
                $objPHPExcel->getActiveSheet()
                ->getStyle($col3)
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
    					    	/* */ 
    			//$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('GL Voucher');
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('C:C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
        $today=date("YmdHis");
        $fileNm="data/".'gl_vouch'.$today.'.xlsx'; 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
        <span>ACCOUNTING</span>
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
    
                	<form method="post" action="rpt_gl_voucher.php?pg=1&mod=7" id="form1">
            
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
                            <h6>Accounting <i class="fa fa-angle-right"></i>GL Voucher Report</h6>
                       </div>
                       
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
                            
                            
				
				            
                                <div class="form-group styled-select" style="width:300px">
                                    <input list="cmborg1" name ="cmbassign2" value = "<?=$fglname ?>" autocomplete="off"  class="dl-cmborg datalist form-control" placeholder="GL Voucher No">
                                    <datalist  id="cmborg1" name = "cmborg1" class="list-cmbassign form-control" >
                                        <option value="">Select Customer</option>
                            <?php $qryitm = "SELECT DISTINCT `vouchno` glnm,`vouchno` glno FROM  glmst where isfinancial in('0','A') order by vouchno";
                                $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                $tid = $rowitm["glno"];
                                $nm  = $rowitm["glnm"]; ?>
                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
                            <?php }} ?>
                                    </datalist>
                                    <input type="hidden" name="cmborg" id="cmborg" value="<?=$fglno ?>">
                                </div>
                            

                            <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from" value="<?=($_REQUEST['filter_date_from'])?$_REQUEST['filter_date_from']:''?>" >
                            </div>
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">     
                            </div>
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="form-group">
                            <input type="hidden" id="pdfsource" url="pdf_gl_voucher.php">
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
                            <th>Customer</th>
                            <th>Remarks</th>
                            <th>SL</th>
                            <th>GL Account </th>
                            <th>GL Name</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            
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
                    { data: 'VouchNo' },
                    { data: 'TransDt' },
                    { data: 'refno' },
                    { data: 'customer' },
                    { data: 'remarks' },
                    { data: 'sl' },
                    { data: 'glac' },
					{ data: 'glnm' },
					{ data: 'D_amount' },
					{ data: 'C_amount' },
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
            /*
            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
            */ 
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })            
            
		}
	

	
			
	$(document).ready(function(){
		
	$("#voucherinput__").on('input', function () {

		
		

					var fvouch = $(this).val();
					var drange = $("#filter_date_from").val();

		
					//generate urlqeurystring and send it input type hidden urlqeurystring
					var urlqeurystringvalue = 'fvouch=<?= $fglno ?>';
					$("#urlqeurystring").val(urlqeurystringvalue);
	


					if(!drange){
						//alert(fvouch);

						url = 'phpajax/datagrid_list_all.php?action=rpt_gl_vouch&fvouch=<?= $fglno ?>';
						table_with_filter(url);						

					}else{

						//check if it is a daterange;


						const substr = ' - ';

						if(drange.includes(substr)){
							//daterangepicker
							datearr = drange.split(" - ");
							var fdate = datearr[0];
							var tdate = datearr[1];						
						}
						else{
							//manual datepicker
							var fdate = $("#from_dt").val();
							var tdate = $("#to_dt").val();							
						}


						url = 'phpajax/datagrid_list_all.php?action=rpt_gl_vouch&fvouch=<?= $fglno ?>&dt_f='+fdate+'&dt_t='+tdate;
						table_with_filter(url);					

					}
					if(fvouch.length<1){
						url = 'phpajax/datagrid_list_all.php?action=rpt_gl_vouch&dt_f=<?php echo $fdt; ?>&dt_t=<?php echo $tdt; ?>&fvouch=<?= $fglno; ?>';
						//alert(fvouch);
						table_with_filter(url);
					}




		});				
		
		
	});
	
	
			
	//general call on page load
	url = 'phpajax/datagrid_list_all.php?action=rpt_gl_vouch&dt_f=<?php echo $fdt; ?>&dt_t=<?php echo $tdt; ?>&fvouch=<?= $fglno; ?>';
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_gl_vouch&fvouch=<?= $fglno; ?>&dt_f='+start.format('DD/MM/YYYY')+'&dt_t='+end.format('DD/MM/YYYY');
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=rpt_gl_vouch&fvouch=<?= $fglno; ?>&dt_f='+end.format('DD/MM/YYYY')+'&dt_t='+start.format('DD/MM/YYYY');
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
        	url = 'phpajax/datagrid_list_all.php?action=rpt_gl_vouch&fvouch=<?= $fglno; ?>';
        	table_with_filter(url);
        });
		
	
	
	
	

	
		<?php
    if($_POST['filter_date_from']){
        
        $dtarr = explode('-',$_POST['filter_date_from']);
        $starDt = trim($dtarr[0]);
        $endDt = trim($dtarr[1]);
        $glname = $_REQUEST['cmborg'];
     ?>
        setTimeout(function(){ 
            
        
            $('#from_dt').val('<?=$starDt?>');
            $('#to_dt').val('<?=$endDt?>');
            
            
            //daterangepicker
            drange = $("#filter_date_from").val();
			datearr = drange.split(" - ");
			var fdate = datearr[0];
			var tdate = datearr[1];	
			
			//=01/09/2024  2024-09-01
			/*
			fdatearr = fdate.split("/");
			tdatearr = tdate.split("/");
			
			var fdate = fdatearr[2]+'-'+fdatearr[1]+'-'+fdatearr[0];
			var tdate = tdatearr[2]+'-'+tdatearr[1]+'-'+tdatearr[0];            
            */

           	url = 'phpajax/datagrid_list_all.php?action=rpt_gl_vouch&fvouch=<?=$glname?>&dt_f='+fdate+'&dt_t='+tdate;
            table_with_filter(url);
            
             var urlqeurystringvalue = 'fvouch=<?=$glname?>';
            $("#urlqeurystring").val(urlqeurystringvalue);
                
            //alert(url);

             
            
        },1000);
       
    <?php
    }
    ?>
	
	
		<?php
			if($_REQUEST['cmborg']){
			?>
                var urlqeurystringvalue = "fvouch=<?=$_REQUEST['cmborg']?>";
                $("#urlqeurystring").val(urlqeurystringvalue);
			<?php
			}
			?>
		
        </script>  
       <script>
            //Searchable dropdown
           
        	
        	
        	$(document).on("change", ".dl-cmborg", function() {
                var g = $(this).val();
                var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
                $('#cmborg').val(id);
                var urlqeurystringvalue = 'fvouch='+id;
                $("#urlqeurystring").val(urlqeurystringvalue);
               // alert(id);
        	}); 
        	
        	

        	
        	
        </script>
        
            <script>
        $(document).ready(function() {
            $("#export").on("click", function(event) {
                
                var cmborgValue = $("#cmborg").val();
    
                if (cmborgValue === "0" || cmborgValue === "") {
                    //swal("Please select voucher before exporting.");
                    //return false;
                }
            });
        });
    </script>
    
            <script>
$(document).ready(function() {
    
    $(".dl-cmborg").focus(function() {
        var currentValue = $(this).val();
        $(this).attr("data-lastvalue", currentValue);

        $(this).val("");
    }).blur(function() {

        var lastValue = $(this).attr("data-lastvalue");
        if ($(this).val() === "") {
            $(this).val(lastValue);
        }
        $(this).removeAttr("data-lastvalue");
    });
    
    

			
});		
		</script>
		
    
    </body></html>
  <?php }?>    
