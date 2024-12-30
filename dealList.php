<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'deal';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/deal.php?res=0&msg='Insert Data'&mod=2");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'Deal Name')
            ->setCellValue('C1', 'Lead Name')
            ->setCellValue('D1', 'Deal Value')
            ->setCellValue('E1', 'Currency')
            ->setCellValue('F1', 'Deal Stage')
            ->setCellValue('G1', 'Deal Status')
            ->setCellValue('H1', 'Deal Date')
            ->setCellValue('I1', 'Next Folowup Date')
            ->setCellValue('J1', 'Accont Manager')
            ->setCellValue('K1', 'Lost Reason')
            ->setCellValue('L1', 'Sales Forcast');

        $firststyle = 'A2';
        $qry        = "SELECT  d.`id`,d.`name` dnm,c.`id` lid, c.`name` lnm ,o.`name` leadcompany ,round(d.`value`,2) value,s.`name` stage,ds.`name` 'status',ds.`id` dsid,DATE_FORMAT(d.`dealdate`, '%d/%m/%Y') `dealdate`, DATE_FORMAT(d.`nextfollowupdate`, '%d/%m/%Y') `fldt` ,(case d. `status` when '5' then  (select `name` from deallostreason where id=d.lostreason) else '' end ) lost_rsn,round(IFNULL((d.`value`*s.`weight`/100),0),2) forcast,concat(e.firstname,'',e.lastname)  accmger
FROM deal d left join contact c on d.`lead`=c.`id`
		left join organization o on d.leadcompany=o.id
        left join dealtype s on d.`stage`=s.`id`
        left join dealstatus ds  on d.`status`=ds.`id`
        left join `hr` h on o.`salesperson`=h.`id`  left join employee e on h.`emp_id`=e.`employeecode` order by c.`name`";
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
                $col12 = 'L' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['dnm'])
                    ->setCellValue($col3, $row['lnm'])
                    ->setCellValue($col4, $row['value'])
                    ->setCellValue($col5, $row['curr'])
                    ->setCellValue($col6, $row['stage'])
                    ->setCellValue($col7, $row['status'])
                    ->setCellValue($col8, $row['dealdate'])
                    ->setCellValue($col9, $row['fldt'])
                    ->setCellValue($col10, $row['accmger'])
                    ->setCellValue($col11, $row['lost_rsn'])
                    ->setCellValue($col12, $row['forcast']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Deal');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'deal_' . $today . '.xls';
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

    <body class="list deallist">




    <?php
include_once 'common_top_body.php';
    ?>
    <div id="wrapper">

      <!-- Sidebar -->

      <div id="sidebar-wrapper" class="mCustomScrollbar">

      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>All Deal</span>
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

                	<form method="post" action="dealList.php" id="form1">

                     <div class="well list-top-controls">






                      <div class="row border">
                       <div class="col-sm-3 text-nowrap">
                            <h6>CRM <i class="fa fa-angle-right"></i> All Deals</h6>
                       </div>
                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            <div class="form-group">
                                <input type="text" class="form-control orderdtpicker datepicker_history_filterx" autocomplete="off" placeholder="Order Date Range" name="filter_date_from" id="filter_date_from"  value="" >
                            </div> 
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" id="add" title="Create New"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="export" title="Export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
                            </div>


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
                    <table  id='listTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th data-class-name="priority">Deal Name</th>
                            <th>Lead Name</th>
                            <th>Lead Company</th>
                            <!-- <th>Deal Value</th> -->
                            <th>Deal Date </th>
                            <th>Deal Stage </th>
                            <th>Deal Status </th>
                            <th>Currency</th>
                            <th>OTC</th>
                            <th>MRC</th>

                            <th>Next Folowup Date </th>
                            <th>Account Manager</th>
                            <th>Lost Reason </th>
                            <th>Sales Forcast </th>
                            <th class="sorting_disabled" ></th>
                            <th class="sorting_disabled" ></th>
                        </tr>

                        </thead>

                        <tfoot id="dtfoot">
                            <tr>
                                <td id="total_order"  ></td>
                                <td> </td>

                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td id="total_order1" ></td>
                                <td></td>
                                <td></td>
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

		<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>

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
                   { data: 'dnm' },
                    { data: 'lnm' },
                    { data: 'leadcompany' },
                    { data: 'dealdate' },
					//{ data: 'value' },
                    { data: 'stage' },
                	{ data: 'status' },
                	{ data: 'shnm' },
                    { data: 'otc' },
                    { data: 'mrc' },

        			{ data: 'fldt' },
        			{ data: 'accmger' },
        			{ data: 'lost_rsn' },
    				{ data: 'forcast' },
					{ data: 'edit', "orderable": false },
					{ data: 'del', "orderable": false },
				
					
                ],
				 
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
	url = 'phpajax/datagrid_list_all.php?action=deal';
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
        	url = 'phpajax/datagrid_list_all.php?action=deal&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
        	}
        	else
        	{
        	url = 'phpajax/datagrid_list_all.php?action=deal&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
        	url = 'phpajax/datagrid_list_all.php?action=deal';
        	table_with_filter(url);
        });
        	
        //ENDS DATE FILTER START	

			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script>  
		
        <script>

function update_grid_status_menu(thisvalue,id, status_id){
	var dealdata = { dataid:id,statusid: status_id, modulename : 'deal', colname : 'status', selectedvalue : thisvalue}
	var saveData = $.ajax({
		  type: 'POST',
		  url: "phpajax/update_deal_status.php?action=changedealstatus",
		  data: dealdata,
		  dataType: "text",
		  success: function(resultData) { messageAlert(resultData) }
	});
	saveData.error(function() { messageAlert("Something went wrong"); });

}

function update_grid_stage_menu(thisvalue,id, stage_id){
	var dealdata = { dataid:id,stageid: stage_id, modulename : 'deal', colname : 'stage', selectedvalue : thisvalue}
	var saveData = $.ajax({
		  type: 'POST',
		  url: "phpajax/update_deal_status.php?action=changedealstage",
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

			clsStage  = $(this).find("input[type=hidden].stage").attr("class");
			clsStatus = $(this).find("input[type=hidden].status").attr("class");
			//$(this).find("input[type=hidden]").attr("class","");

			$(this).find("td:nth-child(5)").attr("class",clsStage);
			$(this).find("td:nth-child(6)").attr("class",clsStatus);
			clsStatus = '';
			clsStage = '';
			//alert(cls);
			});







	$(".stage .dropdown-menu a").on("click", function(){

		//alert($(this).html());

		myClass = $(this).attr("class");


		root = $(this).parent().parent().parent().parent().parent();
		root.removeClass();
		root.addClass("stage "+myClass);
		root.find("a span").html($(this).html()+"<span class=\"caret\"></span>");

		id = root.find("a").data("id");
		stage_id = $(this).data("stageid");
		//alert('xx'+status_id);
		//call ajax function for posting data
		update_grid_stage_menu($(this).html(),id, stage_id);
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
		<!--<script>
        $(document).ready(function(){
            
            function table_with_filter(url){
                var table1 = $('#listTable').on( 'draw.dt',  function () { putClass(); } )
				.DataTable({
				//"dom": 'rtip', // the "r" is for the "processing" message
				/*"language": {
				"processing": "<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span>"
				},*/
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
				//"order": [[ 0, "desc" ]],
				"dom": "rtiplf",
                ajax: {
                    'url':'phpajax/datagrid_list_all.php?action=deal',

                },


                columns: [
                    { data: 'dnm' },
                    { data: 'lnm' },
                    { data: 'leadcompany' },
                    { data: 'dealdate' },
					//{ data: 'value' },
                    { data: 'stage' },
                	{ data: 'status' },
                	{ data: 'shnm' },
                    { data: 'otc' },
                    { data: 'mrc' },

        			{ data: 'fldt' },
        			{ data: 'accmger' },
        			{ data: 'lost_rsn' },
    				{ data: 'forcast' },
					{ data: 'edit', "orderable": false },
					{ data: 'del', "orderable": false }
                ],

                drawCallback:function(settings)
                {
                    //var tot = document.getElementById('total_order');

                   // tot.innerHTML= settings.json.total;
                    //console.log(tot);
                    setTimeout(function(){
                        $('#total_order1').html(settings.json.total);
                    },500);


                }

            });


             $('#search-dttable').keyup(
                 function(){
                     table1.search($(this).val()).draw();
                     //setTimeout(function(){ putClass(); }, 300);

             })
            }
            
            //general call on page load
        	url = 'phpajax/datagrid_list_all.php?action=deal';
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
            	url = 'phpajax/datagrid_list_all.php?action=deal&dt_f='+start.format('YYYY-MM-DD')+'&dt_t='+end.format('YYYY-MM-DD');
            	}
            	else
            	{
            	url = 'phpajax/datagrid_list_all.php?action=deal&dt_f='+end.format('YYYY-MM-DD')+'&dt_t='+start.format('YYYY-MM-DD');
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
            	url = 'phpajax/datagrid_list_all.php?action=deal';
            	table_with_filter(url);
            });
            	
            //ENDS DATE FILTER START	

        });



        </script>-->
    </body></html>
  <?php } ?>
