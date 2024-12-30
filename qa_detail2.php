<?php
require "common/conn.php";


session_start();

//ini_set('display_errors',1);
$usr=$_SESSION["user"];

//echo $usr;die;

$res= $_GET['res'];
$msg= $_GET['msg'];





if($usr=='')
{ 	header("Location: ".$hostpath."/hr.php");
}
else
{
	

    $currSection = 'qa';
	// load session privilege;
	//include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);
    

   
    
    
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
        <span>QA Test</span>
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
      			<!--<div class="panel-heading"><h1>All Service Order(Item)</h1></div>-->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    
    
 
                	<form method="post" action="quotationList.php?mod=2" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                          
                          
                         
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>QA Test <i class="fa fa-angle-right"></i> Sold Items <i class="fa fa-angle-right"></i> Order ID: OI-000025 </h6>
                       </div>
                      
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                            <div class="pull-right grid-panel form-inline d-none">

                                    <div class="form-group">
                                        <label for="">Filter by: </label>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-group styled-select">
                                            <select name="cmbstatus" id="cmbstatus" class="form-control" >
                                                <option value="0">All Status</option>
        <?php
    $qry1    = "select id,name from quotation_status order by name";
        $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
            $tid = $row1["id"];
            $nm  = $row1["name"];
            ?>
                                                <option value="<?php echo $tid; ?>" <?php if ($icat == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
        <?php }} ?>
                                            </select>
                                        </div>
                                    </div> 



                                <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control">     
                                </div>
                                <div class="form-group">
                                <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                                </div>
                                <div class="form-group">
                                <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                                <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
                                </div>

                                <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                            </div>
                        
                        </div>
                        
                        
                      </div>
                    </div>
                    
    
    				</form>
                  <style>
                .table-header{
                        padding: 15px 25px;
                    }        
                </style>
               <div class="well table-header">
                
                   <div class="row">
                       <div class="col-sm-6">
                            <div class="row">
                               <div class="col-md-6">
                                       <b>ORDER ID: </b> OI-000025 <br>
                                        <b>CUSTOMER ID:   </b>  CUS-454654
                                </div>
                                <div class="col-md-6">
                                        <b>CUSTOMER NAME: </b> OI-000025 <br>
                                        <b>CUSTOMER CELL:   </b> +8801333548781
                                </div>
                           </div>
                           
                       </div>
                       
                       <div class="col-sm-6">
                           
                            <div class="row">
                               <div class="col-md-6">
                                       <b>ORDER DATE:  </b>  Aug 12/2023 <br>
                                        <b>DELIVERY DATE:    </b>   Aug 12/2023
                                </div>
                                <div class="col-md-6">
                                        <b>DELIVERY ADDRESS:</b><br>
                                        Road 6, House 10, Baridhara DOHS, Dhaka
                                </div>
                           </div>
                           
                           
                       </div>

                       
                   </div>
                   
                </div>         

                <div class="dataTables_scroll qa-grid-wrapper">
                    <!-- Table -->
                    <table id="xxlistTable" class="dataTable actionbtn qadetail-grid" width="100%">
                        <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Barcode</th>
                            <td>Description</th>
                            <th>Quantity</th>
							<th>Last inspection date</th>
                            <th>Delivery Date</th>
                            <th>QA Status by Warehouse</th>
                            <th>Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>01554654</td>
                                <td><!-- description -->
                                    <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="qathmb">
                                                <img src="/assets/images/products/300_300/t00023.jpg" height="100">
                                            </td>
                                            <td class="text-wrap">
                                                <div class="qaitemdesc">
                                                    <strong>Item</strong> : AC281 – Chair<br>
                                                    <strong>Main material:</strong> Russian pine + 
                                                     solid wood plywood Body <br>
                                                    <strong>Filler:</strong> high-density sponge <br>
                                                    <strong>Fabric:</strong> NG8139-25B
                                                
                                                </div>

                                                
                                            
                                            </td>
                                        </tr>
                                    </table>
                                
                                </td>
                                <td class="text-center">5</td>
                                <td class="text-center">11/02/2023</td>
                                <td class="text-center">11/02/2023</td>
                                <td><!-- QA Status by Warehouse -->
                                    <table width="100%" class="qsw-tbl"  cellpadding="5" cellspacing="5">
                                        <tr>
                                            <td class="qsw-1 qsw-head">Warehouse</td>
                                            <td class="qsw-2 qsw-head">Qty</td>
                                            <td class="qsw-3 qsw-head qsw-zerodefect">0 Defect</td>
                                            <td class="qsw-4 qsw-head qsw-defect">Defect</td>
                                            <td class="qsw-5 qsw-head qsw-damaged">Damaged</td>
                                            <td class="qsw-6 qsw-head qsw-pending">Pending</td>
                                            <td class="qsw-7 qsw-head">Action</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">Dhanmondi  </th>
                                            <td class="qswr-qty">2</td>
                                            <td class="qswr-0defect">1</td>
                                            <td class="qswr-defect">1</td>
                                            <td class="qswr-damaged">1</td>
                                            <td class="qswr-pending">1</td>
                                            <td class="qswr-action"><a href="qa_form.php" class="btn btn-info btn-xs show-qa-form">Start</a></td>
                                            
                                        </tr>
                                        
                                        <tr>
                                            <th class="text-right">Banani  </th>
                                            <td class="qswr-qty">5</td>
                                            <td class="qswr-0defect">1</td>
                                            <td class="qswr-defect">1</td>
                                            <td class="qswr-damaged">1</td>
                                            <td class="qswr-pending">2</td>
                                            <td class="qswr-action"><a href="#" class="btn btn-info btn-xs">Start</a></td>
                                            
                                        </tr>                                        
                                        
                                    </table>
                                </td>
                                <td>
                                    <p class="qsw-remark text-wrap" >
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit
                                    </p>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <td>1</td>
                                <td>01554654</td>
                                <td><!-- description -->
                                    <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="qathmb">
                                                <img src="/assets/images/products/300_300/t00023.jpg" height="100">
                                            </td>
                                            <td class="text-wrap">
                                                <div class="qaitemdesc">
                                                    <strong>Item</strong> : AC281 – Chair<br>
                                                    <strong>Main material:</strong> Russian pine + 
                                                     solid wood plywood Body <br>
                                                    <strong>Filler:</strong> high-density sponge <br>
                                                    <strong>Fabric:</strong> NG8139-25B
                                                
                                                </div>

                                                
                                            
                                            </td>
                                        </tr>
                                    </table>
                                
                                </td>
                                <td class="text-center">5</td>
                                <td class="text-center">11/02/2023</td>
                                <td class="text-center">11/02/2023</td>
                                <td><!-- QA Status by Warehouse -->
                                    <table width="100%" class="qsw-tbl"  cellpadding="5" cellspacing="5">
                                        <tr>
                                            <td class="qsw-1 qsw-head">Warehouse</td>
                                            <td class="qsw-2 qsw-head">Qty</td>
                                            <td class="qsw-3 qsw-head qsw-zerodefect">0 Defect</td>
                                            <td class="qsw-4 qsw-head qsw-defect">Defect</td>
                                            <td class="qsw-5 qsw-head qsw-damaged">Damaged</td>
                                            <td class="qsw-6 qsw-head qsw-pending">Pending</td>
                                            <td class="qsw-7 qsw-head">Action</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">Dhanmondi  </th>
                                            <td class="qswr-qty">2</td>
                                            <td class="qswr-0defect">1</td>
                                            <td class="qswr-defect">1</td>
                                            <td class="qswr-damaged">1</td>
                                            <td class="qswr-pending">1</td>
                                            <td class="qswr-action"><a href="#" class="btn btn-info btn-xs">Start</a></td>
                                            
                                        </tr>
                                        
                                        <tr>
                                            <th class="text-right">Banani  </th>
                                            <td class="qswr-qty">5</td>
                                            <td class="qswr-0defect">1</td>
                                            <td class="qswr-defect">1</td>
                                            <td class="qswr-damaged">1</td>
                                            <td class="qswr-pending">2</td>
                                            <td class="qswr-action"><a href="#" class="btn btn-info btn-xs">Start</a></td>
                                            
                                        </tr>                                        
                                        
                                    </table>
                                </td>
                                <td>
                                    <p class="qsw-remark text-wrap" >
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit
                                    </p>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <td>1</td>
                                <td>01554654</td>
                                <td><!-- description -->
                                    <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="qathmb">
                                                <img src="/assets/images/products/300_300/t00023.jpg" height="100">
                                            </td>
                                            <td class="text-wrap">
                                                <div class="qaitemdesc">
                                                    <strong>Item</strong> : AC281 – Chair<br>
                                                    <strong>Main material:</strong> Russian pine + 
                                                     solid wood plywood Body <br>
                                                    <strong>Filler:</strong> high-density sponge <br>
                                                    <strong>Fabric:</strong> NG8139-25B
                                                
                                                </div>

                                                
                                            
                                            </td>
                                        </tr>
                                    </table>
                                
                                </td>
                                <td class="text-center">5</td>
                                <td class="text-center">11/02/2023</td>
                                <td class="text-center">11/02/2023</td>
                                <td><!-- QA Status by Warehouse -->
                                    <table width="100%" class="qsw-tbl"  cellpadding="5" cellspacing="5">
                                        <tr>
                                            <td class="qsw-1 qsw-head">Warehouse</td>
                                            <td class="qsw-2 qsw-head">Qty</td>
                                            <td class="qsw-3 qsw-head qsw-zerodefect">0 Defect</td>
                                            <td class="qsw-4 qsw-head qsw-defect">Defect</td>
                                            <td class="qsw-5 qsw-head qsw-damaged">Damaged</td>
                                            <td class="qsw-6 qsw-head qsw-pending">Pending</td>
                                            <td class="qsw-7 qsw-head">Action</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right">Dhanmondi  </th>
                                            <td class="qswr-qty">2</td>
                                            <td class="qswr-0defect">1</td>
                                            <td class="qswr-defect">1</td>
                                            <td class="qswr-damaged">1</td>
                                            <td class="qswr-pending">1</td>
                                            <td class="qswr-action"><a href="#" class="btn btn-info btn-xs">Start</a></td>
                                            
                                        </tr>
                                        
                                        <tr>
                                            <th class="text-right">Banani  </th>
                                            <td class="qswr-qty">5</td>
                                            <td class="qswr-0defect">1</td>
                                            <td class="qswr-defect">1</td>
                                            <td class="qswr-damaged">1</td>
                                            <td class="qswr-pending">2</td>
                                            <td class="qswr-action"><a href="#" class="btn btn-info btn-xs">Start</a></td>
                                            
                                        </tr>                                        
                                        
                                    </table>
                                </td>
                                <td>
                                    <p class="qsw-remark text-wrap" >
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit
                                    </p>
                                </td>
                                
                            </tr>
                            
                        </tbody>
                    </table>
                </div>                        
                        
<br>
<br>
<br>
                        
                        

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div class="d-none" >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable actionbtn' width="100%">
                        <thead>
                        <tr>
<!--                            <th>Sl.</th>-->
<!--

-->
                            <th>Created</th>
                            <th>Order ID</th>
                            <th>Customer ID</th>
                            <th>Customer Name</th>
							<th>Order Status</th>
                            <th>Order Date</th>
                            <th>Order Amount</th>
                            <th>Account Manager</th>
                            <th style="width:150px;">Actions</th>
                            <!--th>Delete</th-->							
							
							
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
                    //'url':'phpajax/datagrid_saleorder.php?action=inv_soitem'
					'url':url,
                },
//				'columnDefs': [{
//				'render': function(data,id){ return id},
//				'targets': 0,
//				'className': 'root_'+id,
//			}],

				'columns': [
                    { data: 'makedt','bVisible': false },
				
                    { data: 'socode',
					'render': function (socode) {
						return '<span class="rowid_'+ socode +'">' + socode +'</span>'
						}
					},						
                    //{ data: 'socode'},
					{ data: 'orgcode' },
                    { data: 'organization' },
					{ data: 'orderstatus' },	//Order Status
                    { data: 'orderdate' },
                	{ data: 'otc' },	//Order Amount
            		{ data: 'poc' },	//accoutn manager
					{ data: 'action_buttons', 'orderable':false},
					
					//{ data: 'edit', "orderable": false  },
					//{ data: 'inv', "orderable": false  },
					//{ data: 'del', "orderable": false  }
					
					
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
	//url = 'phpajax/datagrid_quotation.php?action=quotation&currSection='.$currSection;
    url = 'phpajax/datagrid_quotation.php?action=quotation&currSection=<?=$currSection?>';
	table_with_filter(url);	
	

        //Status
        $("#cmbstatus").on("change", function() {

            var status = $(this).val();
			//status = parseInt(status.trim());
            //var user = $('#filteruser').val();
            //var paidto = $('#filterpaidto').val();
            //var enddt = $('#end_dt').val();
            //var startdt = $('#start_dt').val();
            //var url = 'phpajax/datagrid_saleorder.php?action=inv_soitem&user='+user+'&cmbstatus='+status+'&paidto='+paidto+'&startdt='+startdt+'&enddt='+enddt;
			url = 'phpajax/datagrid_quotation.php?action=quotation&cmbstatus='+status;
			
			//alert(status);
			
            
			
            setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });			
			
			
			
			
			
//delete row
			
$("#listTable_wrapper").on("click",".griddelbtn", function() {

			var url = $(this).attr('href');
	  //alert(url);
	  //swal(url);
	//return false;


			  swal({
			  title: "Are you sure?",
			  text: "Once deleted, you will not be able to recover this order!",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			  buttons: ['Cancel', 'Confirm Delete'],
			})
			.then((willDelete) => {
			  if (willDelete) {
				location.href=url;
				//swal("Order has been deleted!", {
				 // icon: "success",
			   // });
			  } else {
				//swal("Your imaginary file is safe!");
				  return false;
			  }
			});

			return false;

	
	});			
					
	
	
	
	
			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script>  
		
		
<script>
$(document).ready(function(){
	
//show INVOICE
	
	$(".dataTable").on("click",".show-qa-form",function(){
		
  	//mylink = $(this).attr('href')+"?socode="+$(this).data('socode')+"&qtype=quotation";
    mylink = $(this).attr('href')+"?";
	
   //alert(mylink);
  
  
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: 'QA: Item : AC281 – Chair   |  Barcode: 546454646 | Warehouse: Dhanmondi',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: false, // <-- Default value is false
							cssClass: 'show-qaform',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Cancel',
								action: function(dialog) {
									dialog.close();	
									/*
									$("#printableArea2").printThis({
										importCSS: true, 
										importStyle: true,
									});
									*/
									
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Print',
								action: function(dialog) {
									
									$("#printableArea2").printThis({
										importCSS: false, 
										importStyle: true,
									});
		
									
									dialog.close();	
									
									},
								
							}],
							onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});		
		
	
});
</script>		
    
    </body></html>
  <?php }?>    
