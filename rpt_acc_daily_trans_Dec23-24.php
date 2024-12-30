<?php
     header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

//if($_POST)
//print_r($_REQUEST);die; 

require "common/conn.php";

require "rak_framework/misfuncs.php";
require "common/user_btn_access.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

$fdt = $_POST['from_dt'];
$tdt = $_POST['to_dt'];

$fglno = $_POST['cmborg'];
if ($fglno == '') {
    $fglno   = 0;
    $fglname = '';
} else {
    $qrygl    = "SELECT concat(`glnm`, '(', `glno`, ')') glnm, glno FROM `coa` where glno = '" . $fglno . "'";
    $resultgl = $conn->query($qrygl);
    while ($rowgl = $resultgl->fetch_assoc()) {
        $fglname = $rowgl["glnm"];
    }

}

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_acc_daily_trans';
    include_once('common/inc_session_privilege.php');
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/hraction.php?res=0&msg='Insert Data'&mod=4");
    }
    if (isset($_POST['export'])) {
          
          if($fdt != ''){
                $date_qry = " and m.`transdt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
            }else{
                $date_qry = "";
            }

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'Date')
            ->setCellValue('C1', 'Ref')
            ->setCellValue('D1', 'Vouch No')
            ->setCellValue('E1', 'GL Account')
            ->setCellValue('F1', 'Debit')
            ->setCellValue('G1', 'Credit')
            ->setCellValue('H1', 'Narration')
            ->setCellValue('I1', 'Maker')
            ->setCellValue('J1', 'Checker')
            ->setCellValue('K1', 'Approver');

        $firststyle = 'A2';
        $qry        = "SELECT a.id,DATE_FORMAT( m.`transdt`,'%m/%d/%Y') `entrydate`, a.`remarks`, a.`vouchno`, concat(c.`glnm`, '(', c.`glno`, ')') glnm, a.`dr_cr`, a.`amount`,h.hrName makeusr,h1.hrName checkusr,h2.hrName apprvusr,m.remarks narr
FROM glmst m join `gldlt` a on m.vouchno=a.vouchno  LEFT JOIN coa c ON a.`glac` = c.glno 
left join hr h on m.entryby=h.id left join hr h1 on m.checkby=h1.id left join hr h2 on m.approvedby=h2.id  
                                        where m.isfinancial in('0','A') and (a.glac = '".$fglno."' or '".$fglno."' = '0') $date_qry order by m.`transdt` asc";
        // echo  $qry;die;
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {$i = 0;
            while ($row = $result->fetch_assoc()) {
                $urut  = $i + 2;
                $col1  = 'A' . $urut;
                $col2  = 'B' . $urut;
                $col3  = 'C' . $urut;
                $col4  = 'D' . $urut;
                $col5  = 'E' . $urut;
                $col6  = 'F' . $urut;
                $col7  = 'G' . $urut;
                $col8  = 'H' . $urut;
                $col9  = 'I' . $urut;
                $col10 = 'J' . $urut;
                $col11 = 'K' . $urut;
                $i++;
                if ($row["dr_cr"] == 'C') {
                    $dr = $row["amount"];
                    $cr = 0;
                } else {
                    $cr = $row["amount"];
                    $dr = 0;
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['entrydate'])
                    ->setCellValue($col3, $row['remarks'])
                    ->setCellValue($col4, $row['vouchno'])
                    ->setCellValue($col5, $row['glnm'])
                    ->setCellValue($col6, $dr)
                    ->setCellValue($col7, $cr)
                    ->setCellValue($col8, $row['narr'])
                    ->setCellValue($col9, $row['makeusr'])
                    ->setCellValue($col10, $row['checkusr'])
                    ->setCellValue($col11, $row['apprvusr']);

                //$laststyle=$title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Daily Transection');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'daily_transection' . $today . '.xls';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileNm);
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
        <span>ACCOUNTING</span>
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
      
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="#" id="form1">

                     <div class="well list-top-controls">
               
                        <div class="row border">




                           <div class="col-sm-4 text-nowrap">
                                <h6>Accounting <i class="fa fa-angle-right"></i>Daily Transaction</h6>
                           </div>



                            <div class="col-sm-8 text-nowrap">

                                <div class="pull-right grid-panel form-inline">
        
        
                                
        
        
                                    <div class="form-group styled-select" style="width:300px;">
                                        <input list="cmborg1" name ="cmbassign2" value = "<?=$fglname ?>" autocomplete="off"  class="dl-cmborg datalist form-control" placeholder="GL Account">
                                        <datalist  id="cmborg1" name = "cmborg1" class="list-cmbassign form-control" >
                                            <option value="">Select Customer</option>
                                <?php $qryitm = "SELECT concat(`glnm`, '(', `glno`, ')') glnm, glno FROM `coa` where oflag ='N' and isposted in('Y','P') order by glnm ";
                                    $resultitm                            = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                        $tid = $rowitm["glno"];
                                        $nm  = $rowitm["glnm"]; ?>
                                                                    <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
                                    <?php }} ?>
                                        </datalist>
                                        <input class="pdffilter" type = "hidden" name = "cmborg" id = "cmborg" value = "<?=$fglno ?>">
                                    </div> 
        
                                
        
                                    <input type="hidden" name="from_dt" id = "from_dt">
                                    <input type="hidden" name="to_dt" id = "to_dt">
        
                                    <div class="form-group">
                                        <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="<?=($_REQUEST['filter_date_from'])?$_REQUEST['filter_date_from']:''?>" >
                                    </div>
                                    <div class="form-group">
                                        <input type="search" id="search-dttable" class="form-control" placeholder="Search by Key">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                    <div class="form-group">
                                    <input type="hidden" id="pdfsource" url="pdf_acc_daily_trans_report.php">
                                    <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
        								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
        									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
        									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
        								</ul>
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
                            <th>Date</th>
                            <th>Ref.</th>
                            <th>Vouch No</th>
                            <th>GL Account</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Narration</th>
                            <th>Maker</th>
                            <th>Checker</th>
                            <th>Approver</th>

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
include_once 'common_footer.php';
    ?>
    <?php

    if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }

    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
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
                    { data: 'entrydate' },
                    { data: 'remarks' },
                    { data: 'vouchno' },
                    { data: 'glnm' },
                    { data: 'dr' },
					{ data: 'cr' },
					{ data: 'narr' },
					{ data: 'maker' },
					{ data: 'checker' },
					{ data: 'apprvr' },
                ],

                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    $(api.column(4).footer()).html('Total: ');
                    var columnsToTotal = [5, 6]; // Indexes of the columns to total
                
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

	

	

	

	//general call on page load

	url = 'phpajax/datagrid_list_all.php?action=rpt_acc_daily_trans&fglno=<?= $fglno ?>';

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

        	url = 'phpajax/datagrid_list_all.php?action=rpt_acc_daily_trans&fglno=<?= $fglno ?>&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
            $('#from_dt').val(start.format('YYYY-MM-DD'));
        	$('#to_dt').val(end.format('YYYY-MM-DD'));
        	}

        	else

        	{

        	url = 'phpajax/datagrid_list_all.php?action=rpt_acc_daily_trans&fglno=<?= $fglno ?>&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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

        	url = 'phpajax/datagrid_list_all.php?action=rpt_acc_daily_trans&fgno=<?= $fglno ?>';

        	table_with_filter(url);

        });

        	

        //ENDS DATE FILTER START	



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
						fdatearr = fdate.split("/");
						tdatearr = tdate.split("/");
						
						var fdate = fdatearr[2]+'-'+fdatearr[1]+'-'+fdatearr[0];
						var tdate = tdatearr[2]+'-'+tdatearr[1]+'-'+tdatearr[0];            
            
            //url = 'phpajax/datagrid_list_all.php?action=rpt_acc_daily_trans&fgno=301010200&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
            
           	url = 'phpajax/datagrid_list_all.php?action=rpt_acc_daily_trans&fglno=<?=$glname?>&dt_f='+fdate+'&dt_t='+tdate;
            table_with_filter(url);
            
             var urlqeurystringvalue = 'fglno='+<?=$glname?>;
            $("#urlqeurystring").val(urlqeurystringvalue);
                
            //alert(url);

             
            
        },1000);
       
            <?php
            }
    ?>		

			<?php
			if($_REQUEST['cmborg']){
			?>
                var urlqeurystringvalue = 'fglno='+<?=$_REQUEST['cmborg']?>;
                $("#urlqeurystring").val(urlqeurystringvalue);
			<?php
			}
			?>

        }); 



        </script>

        <script>
            //Searchable dropdown
            $(document).on("change", ".dl-cmborg", function() {
                var g = $(this).val();
                var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
                $('#cmborg').val(id);
                var urlqeurystringvalue = 'fglno='+id;
                $("#urlqeurystring").val(urlqeurystringvalue);
                //alert(id);


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
  <?php } ?>
