<?php

require "common/conn.php";



session_start();

$usr = $_SESSION["user"];







if ($usr == '') {header("Location: " . $hostpath . "/hr.php");

} else {



    // $store = $_POST["cmbsupnm"];

    $branch  = $_POST["cmbbranch"];

    $barcode = $_POST["bc"];



    require_once "common/PHPExcel.php";

    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

     */

    $currSection = 'stock-transfer';

    $currPage    = basename($_SERVER['PHP_SELF']);





    ?>

    <!doctype html>

    <html xmlns="http://www.w3.org/1999/xhtml">

    <?php

include_once 'common_header.php';

    ?>

<style>
           .modal-dialog {
                width: 800px;
            }
        </style>  

    <body class="list">



    <?php

include_once 'common_top_body.php';

    ?>

    <div id="wrapper">



      <!-- Sidebar -->



      <div id="sidebar-wrapper" class="mCustomScrollbar">



      <div class="section">

      	<i class="fa fa-group  icon"></i>

        <span>Stock Transfer</span>

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



                	<form method="post" action="stock_transfer.php?mod=12" id="form1">

                        <!-- START PLACING YOUR CONTENT HERE -->
						
						
						
						
						
						
				<div class="well list-top-controls">
                     <div class="row border">
                           <div class="col-sm-1 text-nowrap lg-text">
                            <h6> Inventory <i class="fa fa-angle-right"></i> Stock Transfer </h6>
                       	   </div>

                        <div class="col-sm-11 text-nowrap">
							
                        <div class="pull-right grid-panel form-inline">
							
                                <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>								
						
							
							
							<div class="form-group">

																<div class="form-group styled-select">

																	<select name="cmbbranch" id="cmbbranch" class="form-control" >

																		<option value="0">All Store</option>

								<?php

								$qry1    = "SELECT `id`, `name`  FROM `branch` where status = 'A' order by name";

								$result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {

									$tid = $row1["id"];

									$nm  = $row1["name"];

									?>

																		<option value="<?php echo $tid; ?>" <?php if ($branch == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>

								<?php }} ?>

																	</select>

																</div>

															</div>							
							
                           <div class="form-group">
                            <input type="search" placeholder="Search by Keyword" id="search-dttable" class="form-control">
                            </div>						
							
							
							
<!--                            <button dat a-to="pagetop" class="no-mg-btn btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"><i class="fa fa-search"></i></button>-->


                           
                        </div>
      
 
                      </div>
						 
                    </div>
                    </div>						
						
						
						
						
						
						









                    </form>





<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>



                <div >

                    <!-- Table -->

                    <table id="listTable" class="display actionbtn table tbl-stock-transfer  no-footer dataTable" table-bordered table-hover dt-responsive width="100%">

                        <thead>



							<tr>
								<th width="10">Photo</th>
								<th width="20">Product ID</th>
								<th>Product</th>
                                <th>Barcode </th>
								<th>Category</th>
                                <th>Warehouse</th>
                                <th>Stk. Qty</th>
                                <th>Transfer Stock</th>

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

		    var ch = 1;



			var prv= '<?=$prv ?>';

			var table1 =  $('#listTable').DataTable().destroy();
            var table1 = $('#listTable').DataTable({
	
            //var table1 = $('#listTable').DataTable({

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
				"order": [[ 3, "desc" ]],
				"dom": "rtiplf",
				/*'searching': true,*/
                'ajax': {
					'url':url
                },
                'columns': [

                    
					{ data: 'image',orderable: false},
					{ data: 'code' },
                    { data: 'pn' },
                    { data: 'barcode'},
					{ data: 'tn'},
                    { data: 'str'},
                    { data: 'freeqty' },
                    { data: 'transfer', orderable: false }



                ],
			columnDefs: [
				{
					targets: [6],
					className: 'dt-body-center'
				}],
       });



            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
            
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })    


	
		}

	//general call on page load
	url = 'phpajax/datagrid_stock_transfer.php?action=stock-transfer';
	table_with_filter(url);		

	
	
        $("#cmbbranch").on("change", function() {

            var branch = $(this).val();
			
			url = 'phpajax/datagrid_stock_transfer.php?action=stock-transfer&branch='+branch;
			
            setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });		
	
	

});//$(document).ready(function(){









 </script>

<script>
		
$(".tbl-stock-transfer").on("click",".btn-sck-trnfr",function(){
		
  	mylink = $(this).attr('href');
   //alert(mylink);
  
  
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: 'STOCK TRANSFER',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2">Loading...</div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: false, // <-- Default value is false
							draggable: true, // <-- Default value is false
							cssClass: 'post-posdata',
							buttons: [{
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Update Stock',
								action: function(dialog) {
									
									
										curqty = parseInt($("#curqty").val());
										storeto = $("#storeto").val();
										trqtn = parseInt($("#trqtn").val());
										
									

									
									if(!storeto){swal("Please select \"Transfer To\" option"); return false;}
									
									if(!trqtn){
											swal("Please type \"Quantity\" option");
											return false;
										}else{
											if(trqtn > curqty || trqtn < 1){
												swal("Enter quantity within 1-"+curqty);
												
												$("#trqtn").hide();
												$("#trqtn").val(curqty);
												$("#trqtn").show();
												 return false;
												
											}
										}
									
									
												 //										
//										swal({
//										  title: "Alert",
	//										html:true, 
//										  text: "Please Select \"Transfer To\" option",
//										  icon: "success",
//										  button: "OK",
//										});			
									
									
									
								var obj = []; 
								var cdata = {};


								cdata.storeto = $("#storeto").val();
								cdata.curstore = $("#curstore").val();
								
								cdata.curqty = $("#curqty").val();
								cdata.trqtn = $("#trqtn").val();
								
								cdata.barcode = $("#barcode").val();
								cdata.prdname = $("#prdname").val();
								cdata.pid = $("#prdid").val();
									
									
								obj.push(cdata);									
								var dataString = JSON.stringify(obj);
								
								//alert(dataString);
							$.ajax({
								   url: 'phpajax/post_stock_transfer.php',
								   data: {posData: dataString},
								   type: 'POST',
								   dataType:"json",
								   success: function(res) {
									   
									   setTimeout(function(){
										   if(res.success == 1 || res.success == 2){
											//swal(res.msg); 
											   
											swal({
											  title: "Success",
											  text: res.msg,
											  //html: '<b>rak</b>',
											  icon: "success",
											  button: "OK",
											}).then((value) => {
											  //swal(`The returned value is: ${value}`);
												dialog.close();
												location.reload(); 
											});;							   
											   
											   
										   }
									   },200);
									   
									  // $("#customerNameShow").val(res.name);
									  //$("#customerId").val(res.customerid);
									  // $("#customerId").attr('data-name',res.name);
									   //document.title = res.name;
									   

								   }
								});									
									
									
								//#################################
									
								}
							},
								{
								icon: 'glyphicon glyphicon-remove',
								cssClass: 'btn-primary',
								label: ' Cancel',
								action: function(dialog) {
									dialog.close();	
									
								}
							}]
						});		
  
  
  
  
  
  
  	return false;
});		
</script>




    </body></html>

  <?php } ?>

