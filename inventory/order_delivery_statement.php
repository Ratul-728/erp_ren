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
    $fd1=$_POST['from_dt'];
    $td2=$_POST['to_dt'];
    $dagent=$_POST['cmbsupnm'];
    
    if($fd1==''){$fd1=date("d/m/Y");}
    if($td1==''){$td1=date("d/m/Y");}
    
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'cusorderdeliverystmt';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/custorder.php?res=0&msg='Insert Data'&mod=4");
    }
   if ( isset( $_POST['export'] ) ) {
        //echo "yes"; die;
         header("Location: ".$hostpath."/order_delivery_stmt_summary.php?agnt=".$dagent."&fd='".$fd1."'&td='".$td1."'&msg='Insert Data'&mod=4");
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
        <span>All Deliverable Order</span>
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
          			   <div class="panel-heading"><h1>Delivery Statement</h1></div>
        				<div class="panel-body">
							
							
							
                        	<form method="post" action="order_delivery_statement.php?mod=4" id="form1">
								
								<div class="topreportbar">
									
                        	    <div class="row">
                            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label for="po_dt">Order Date*</label>
                                </div>     
                            </div> -->
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label >Date From</label>
                                </div>     
                            </div> 
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="from_dt" id="from_dt" value="<?php echo $fd1;?>"  required>
                                    
                                   
                                </div>     
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label >Date To</label>
                                </div>     
                            </div> 
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="to_dt" name="to_dt"  value="<?php echo $td1;?>" required>
                                    
                                </div>     
                            </div>
                              <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="form-group">
                                <div class="input-group styled-select">
                                    <select name="cmbsupnm" id="cmbsupnm" class="form-control" required>
                                        <option value="">Delivery Agent </option>
<?php 
$qry1="SELECT `id`, `name`  FROM `deveryagent` order by name";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
{ 
  $tid= $row1["id"];  $nm=$row1["name"]; 
?>          
                                        <option value="<?php echo $tid; ?>" ><?php echo $nm; ?></option>
<?php }}?>                    
                                    </select>
                                </div>
                            </div>          
                        </div> 
									<div class="col-lg-2 col-md-6 col-sm-6">
                            <input  dat a-to="pagetop" class="btn btn-md btn-default top" type="submit" name="view" value="Load" id="view"  >
                            <input  dat a-to="pagetop" class="btn btn-md btn-default top" type="submit" name="export" value="Print" id="Print">
                            <!--<input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')"> -->
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
                    'url':'phpajax/datagrid_list_all.php?action=cusorderdelvstmt&lvl=2&fdt=<?php echo $fd1;?>&tdt=<?php echo $td2;?>&dagnt=<?php echo $dagent;?>'
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
