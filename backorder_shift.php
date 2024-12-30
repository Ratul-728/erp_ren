<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];

if ($usr == '') 
{
    header("Location: " . $hostpath . "/hr.php");
}
else 
{
    
    
    // $store = $_POST["cmbsupnm"];
    $branch  = $_POST["cmbbranch"];
    $barcode = $_POST["bc"];

    $currSection = 'backorder_shift';
    $currPage    = basename($_SERVER['PHP_SELF']);
	// load session privilege;
	include_once('common/inc_session_privilege.php');
    ?>
    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php include_once 'common_header.php';    ?>

    <body class="list">

    <?php include_once 'common_top_body.php';   ?>
    <div id="wrapper">

      <!-- Sidebar -->

      <div id="sidebar-wrapper" class="mCustomScrollbar">

      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>Back Order List</span>
      </div>

    <?php include_once 'menu.php';    ?>

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
                <div class="panel panel-info">
          			<!-- <div class="panel-heading"><h1>All Product</h1></div>  -->
    		        <div class="panel-body">
    
                        <span class="alertmsg">
                        </span>
    
                    	<form method="post" action="backorder_shift.php?mod=24" id="form1" enctype="multipart/form-data">
                            <!-- START PLACING YOUR CONTENT HERE -->
                            <div class="well list-top-controls">
                                <div class="row border">
                                    <div class="col-sm-3 text-nowrap">
                                        <h6>Aproval <i class="fa fa-angle-right"></i> Backorder Product List </h6>
                                    </div>
                                    <div class="col-sm-9 text-nowrap">
                                        <div class="pull-right grid-panel form-inline">
                                            <div class="form-group">
                                                <div class="form-group styled-select">
                                                    <select name="cmbcat" id="cmbcat" class="form-control" >
                                                        <option value="0">Category</option>
                <?php
            $qry1    = "SELECT `id`, `name`  FROM `itmCat` order by name";
                $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
                    $tid = $row1["id"];
                    $nm  = $row1["name"];
                    ?>
                                                        <option value="<?php echo $tid; ?>" <?php if ($cat == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
                <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>
                                        
                                            <div class="form-group">
                                                <input type="text" class="no-mg-btn form-control" id="bc" name="bc" placeholder="Bar Code" value="<?php echo $barcode; ?>"  >
            
                                            </div>
                                            
                                            <div class="form-group">
                                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                                            </div>
            
                                       
                                        <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>
                        <div>
                        <!-- Table -->
                            <table id='listTable' class=' table table-bordered table-hover dt-responsive' width="100%">
                                <thead>
    							    <tr>
        							    <th>SL.</th>
        								<th>Catagory</th>
                                        <th>Product </th>
                                        <th>Barcode </th>
                                        <th>Stock Type</th>
                                        <th>Store </th>
                                        <th>BO Qty</th>
                                        <th>FO Qty</th>
                                        <th> Action</th>
    							    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
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
                            { data: 'tn'},
                            { data: 'pn' },
                            { data: 'barcode'},
                            { data: 'storetype'},
                            { data: 'str'},
                            { data: 'freeqty' },
                            { data: 'foqty' },
                            { data: 'action_buttons', orderable: false }
        
                        ],
                        "columnDefs": [
                         {"className": "dt-center", "targets": [6, 7]}
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
    	url = 'phpajax/datagrid_list_all.php?action=backorder_shift';
    	table_with_filter(url);	
    
        //Status
        $("#cmbbranch,#cmbcat,#bc").on("change", function() {

            
            //var branch = $('#cmbbranch').val();
            var bc = $('#bc').val();
            var cat = $('#cmbcat').val();
            
            var url = 'phpajax/datagrid_list_all.php?action=backorder&barcode='+bc+'&cat='+cat;
			
			 setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	



		
		
        $(document).on("click",".backorder-shift",function(){

          
        
	        var root = $(this).closest("tr");
            var qty = $(this).data("freeqty");
            var barcode = $(this).data("barcode");
            var id = $(this).data("id");
            var boqty = root.find(".boqty").val(); //backorder qty
            var foqty = root.find(".foqty").val(); //future qty
            
           // var url = 'update_shfit_stock.php?action=backorder&res=4&id='+id+'&mod=24&qty='+qty+'&bc='+barcode;
            
            if(!foqty || foqty==0){swal({text:"Please update Future Quantity!",icon:"error"});return false; root.find(".foqty").select();}
            
			  swal({
			  title: "Are you sure?",
			  text: 'This Backorder will be shifted to Future order!',
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			  buttons: ['Cancel', 'Confirm'],
			})
			.then((willShift) => {
			  if (willShift) {

			      //write your ajax code here;
                    $.ajax({
                                type: "POST",
                                url: "phpajax/update_shfit_stock.php",
                                data: { freeqty: qty, barcode: barcode, dataid: id, boqty: boqty, foqty: foqty ,store:'back' },
                                success: function (res) {
                                    // Handle success response
                                    
                                    swal({
                            			  title: "Success!",
                            			  text: res,
                            			  icon: "success",
                            			  closeButton: true,
                                                                                         			  
                            			})

                                    
                                },
                                error: function () {
                                    // Handle error response

                                    
                                        swal({
                            			  title: "Failed!",
                            			  text: "Failed to save data!",
                            			  icon: "error",
                            			  closeButton: true,
                                                                                         			  
                            			})
                                    
                                    
                                }
                            });  
			  
			  
			  
			  
			  
			  
			  
			  
			  } else {
				//swal("Your imaginary file is safe!");
				  return false;
			  }
			});

			return false;

        });	
			
    }); //$(document).ready(function(){	
		
    $(document).ready(function() {
        $(document).on("keyup", ".dataTables_wrapper .numonly", function(e) {
            if (/[^0-9.]/g.test(this.value)) {
                // Filter non-digits from input value.
                this.value = this.value.replace(/[^0-9.]/g, '');
            }
        });
        
        
        
    $(document).on("input",".foqty",function(){
        
        if($(this).val() == ''){$(this).val(0);}
      	var root = $(this).closest("tr");
      	var hboqty = root.find(".hboqty").val();
      	var boqty = root.find(".boqty").val();
        var foqty = $(this).val();
       
      
      	//alert(foqty);
      	if (!isNaN(foqty)) {
          	if(parseInt(foqty) > parseInt(hboqty)){
              alert("Invalid Quantity!");
          		$(this).val(0);
              root.find(".boqty").val(hboqty);
            }else{
              var balnce = parseInt(hboqty) - parseInt(foqty);
              root.find(".boqty").val(balnce);
            }
      	}
      
    });        
        
    });		
		
		
		
        </script> 

    </body></html>
  <?php } ?>
