<?php
require "common/conn.php";

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
    $currSection = 'cusorder';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/custorder.php?res=0&msg='Insert Data'&mod=1");
    }
   if ( isset( $_POST['export'] ) ) {
        //echo "yes"; die;
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL')
                ->setCellValue('B1', 'ORDER ID')
                ->setCellValue('C1', 'ORDER DATE')
                ->setCellValue('D1', 'CUSTOMER ID')
                ->setCellValue('E1', 'CUSTOMER NAME')
    			->setCellValue('F1', 'CUSTOMER ADDRESS')
    		    ->setCellValue('G1', 'CUSTOMER EMAIL')
                 ->setCellValue('H1', 'CUSTOMER CONTACT')
                ->setCellValue('I1', 'ORDER STATUS')
                 ->setCellValue('J1', 'ORDER AMOUNT')
                ->setCellValue('K1', 'PAYMENT MODE')
                	 ->setCellValue('L1', 'PAYMENT STATUS'); 
    			
        $firststyle='A2';
        $qry="SELECT o.`id`,o.`order_id`,o.`customer_id`,o.name,concat(o.`address`,',',o.`district`,',',o.`area`) addrs,o.`email`,o.`phone`,st.name stnm,o.`orderstatus` st
        , DATE_FORMAT(o.`order_date`,'%e/%c/%Y') `order_date`,o.`amount`,o.status payst,o.payment_mood 
FROM `orders` o left join orderstatus st on o.orderstatus=st.id order by o.`order_id`"; 
        //echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;$col12='L'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['order_id'])
    						->setCellValue($col3, $row['order_date'])
    					    ->setCellValue($col4, $row['customer_id'])
    					     ->setCellValue($col5, $row['name'])
    					     ->setCellValue($col6, $row['addrs'])
    					    ->setCellValue($col7, $row['email'])
    					    ->setCellValue($col8, $row['phone'])
    					    ->setCellValue($col9, $row['stnm'])
    					    ->setCellValue($col10, $row['amount'])
    					    ->setCellValue($col11, $row['payment_mood'])
    					    ->setCellValue($col12, $row['payst']);	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ORDER');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'order_'.$today.'.xls'; 
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
        <span>All Order</span>
      </div>
      
    <?php
        include_once('menu.php');
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
          			   
        				<div class="panel-body">
                        	<form method="post" action="custorder.php" id="form1">
                                <div class="well list-top-controls"> 
                                    <div class="row border">
                                        <div class="col-sm-11 text-nowrap"> 
                                             <input class="btn btn-default" type="submit" name="export" value=" Export Data for Order" id="export"  >
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
                                <th>Customer Name</th>
                                <!--<th>Shipping Address </th>
                                <th>Email</th>-->
                                <th>Cell No </th>
                                <th>Order Date</th>
                                <th>Order Status</th>
                                <th>Amount </th>
                                <th>Payment Mode</th>
                                <th>Payment Status </th>
                                <th>View</th>
                                <th>Agent</th>
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
        
        <!-- Script -->
        
        
        <script>
        $(document).ready(function(){
            $('#listTable').on( 'draw.dt',  function () { putClass(); } )
				.DataTable({
				//"dom": 'rtip', // the "r" is for the "processing" message
				/*"language": {
				"processing": "<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span>"
				},*/
                processing: true,
				responsive: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 50,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=cusorder'
                },
                'columns': [
                    { data: 'order_id' },
                    { data: 'name' },
                   // { data: 'addrs' },
                    //{ data: 'email' },
                    { data: 'phone' },
                    { data: 'order_date' },
                    { data: 'status' },
                    { data: 'amount' },
                    { data: 'paymd' },
                    { data: 'payst' },
                    { data: 'view' , orderable: false, className: "btncol"},
                    { data: 'edit', orderable: false, className: "btncol" },
                   				
                ]
            });
			
			setTimeout(function(){	
				$('#listTable').DataTable().draw();
			},300);			
			
        });
		
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
  <?php }?>    
