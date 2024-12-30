<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    $fd1    = $_POST['from_dt'];
    $td2    = $_POST['to_dt'];
    $dagent = $_POST['cmbsupnm'];

    if ($fd1 == '') {$fd1 = date("d/m/Y");}
    if ($td1 == '') {$td1 = date("d/m/Y");}

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'cusorderdeliverystmt';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/custorder.php?res=0&msg='Insert Data'&mod=13");
    }
    if (isset($_POST['export'])) {
        //echo "yes"; die;
        header("Location: " . $hostpath . "/order_delivery_stmt_summary.php?agnt=" . $dagent . "&fd='" . $fd1 . "'&td='" . $td1 . "'&msg='Insert Data'&mod=4");
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
        <span>All Deliverable Order</span>
      </div>

    <?php
include_once 'menu.php';
    ?>
      </div>

      <!-- END #sidebar-wrapper -->

      <!-- Page Content -->
      <div id="page-content-wrapper">
        <div class="container-fluid xyz">
          <div class="row">
            <div class="col-lg-12">
            <p>&nbsp;</p>
             <span class="alertmsg"></span>
              <!--h1 class="page-title">Customers</a></h1-->
              <p>
              <!-- START PLACING YOUR CONTENT HERE -->
                  <div class="panel panel-info">
          			   <div class="panel-heading"><h1>Delivery Statement</h1></div>
        				<div class="panel-body">



                        	<form method="post" action="order_delivery_statement.php?mod=13" id="form1">

                                <div class="well list-top-controls">
                    
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Sales <i class="fa fa-angle-right"></i> Delivery Statement </h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">

                            <div class="form-group">
                                <div class="input-group styled-select">
                                    <select name="cmbsupnm" id="cmbsupnm" class="form-control" >
                                        <option value="">Delivery Agent </option>
<?php
$qry1    = "SELECT `id`, `name`  FROM `deveryagent` order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                        <option value="<?php echo $tid; ?>" ><?php echo $nm; ?></option>
<?php }} ?>
                                    </select>
                                </div>
                            </div>

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





    <link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

    <!-- Grid Status Menu -->
    <link href="js/plugins/grid_status_menu/grid_status_menu.css" rel="stylesheet">
    <!-- End Grid Status Menu -->



                    <div >
                        <!-- Table -->
                        <table id='listTable' class='display dataTable' width="100%">
                            <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Invoice No</th>
                                 <th>Order Date</th>
                                 <th>Customer Name</th>
                                <th>Billing Address </th>
                                <th>Cell No </th>
                                 <th>Payment Mode</th>
                                 <th>Amount </th>
                                <th></th>
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
    var dagnt;
			
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
                    { data: 'order_id' },
                    { data: 'invoiceno' },
                    { data: 'order_date' },
                    { data: 'name' },
                    { data: 'addrs' },
                    { data: 'phone' },
                    { data: 'paymd' },
                    { data: 'amount' },
                    { data: 'edit', orderable: false, className: "btncol" }
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
	url = 'phpajax/datagrid_list_all.php?action=cusorderdelvstmt&lvl=2';
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
        	url = 'phpajax/datagrid_list_all.php?action=cusorderdelvstmt&lvl=2&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD')+'&dagnt='+dagnt;
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=cusorderdelvstmt&lvl=2=dt_f'+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD')+'&dagnt='+dagnt;
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
        	url = 'phpajax/datagrid_list_all.php?action=cusorderdelvstmt&lvl=2&dagnt='+dagnt;
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START
    
    //Status
    $("#cmbsupnm").on("change", function() {

            
            dagnt = $('#cmbsupnm').val();
            
            var url = 'phpajax/datagrid_list_all.php?action=cusorderdelvstmt&lvl=2&dagnt='+dagnt;
			
			 setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
        
        //convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
				var pdfurl = "pdf_order_delivery_statement.php?dagnt="+dagnt;
				location.href=pdfurl;
				
			});
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        

        <script>

function update_grid_status_menu(thisvalue,id, status_id){
    //alert(status_id);
	var dealdata = { dataid:id,statusid: status_id, modulename : 'order', colname : 'orderstatus', selectedvalue : thisvalue}
	var saveData = $.ajax({
		  type: 'POST',
		  url: "phpajax/update_order_status.php?action=orderstatus",
		  data: dealdata,
		  dataType: "text",
		  success: function(resultData) { messageAlert(resultData) }
	});
	saveData.error(function() { messageAlert("Something went wrong"); });

}

</script>


<script>

		function putClass(){
		$("#listTable tbody tr").each(function(){

			//clsStage  = $(this).find("input[type=hidden].stage").attr("class");
			clsStatus = $(this).find("input[type=hidden].status").attr("class");
			//$(this).find("input[type=hidden]").attr("class","");

		//	$(this).find("td:nth-child(5)").attr("class",clsStage);
			$(this).find("td:nth-child(5)").attr("class",clsStatus);
			clsStatus = '';
			clsStage = '';
			//alert(cls);
			});









	$(".status .dropdown-menu a").on("click", function(){

		//alert($(this).html());

		myClass = $(this).attr("class");


		root = $(this).parent().parent().parent().parent().parent();
		root.removeClass();
		root.addClass("status "+myClass);
		root.find("a span").html($(this).html()+"<span class=\"caret\"></span>");

		id = root.find("a").data("id");
		status_id = $(this).data("statusid");
		//alert('xx'+status_id);
		//call ajax function for posting data
		update_grid_status_menu($(this).html(),id, status_id);
	});



}

setTimeout(function(){ putClass(); }, 1000);

		</script>
    </body></html>
  <?php } ?>
