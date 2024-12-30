<?php
require "common/conn.php";

session_start();
//echo "<pre>";print_r($_SESSION);echo "</pre>";die;

//ini_set('display_errors',1);

$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

$brand = $_POST["brand"];
$icat= $_POST["cat"];

//echo $msg;die;

if ($usr == '') {
	header("Location: " . $hostpath . "/hr.php");
} else {
	
	
	//item delete process;
	
	if($_REQUEST['action'] == "delete"){
		//echo "<pre>"; print_r($_SESSION); echo "</pre>";
		
		include_once('rak_framework/delete.php');
		include_once('rak_framework/fetch.php');
		include_once('rak_framework/edit.php');
		
		$img = fetchByID('item','id',$_REQUEST['id'],'image');
		
		
		//echo $img;die;
	
		deleteRow('item','id',$_REQUEST['id'],$msg,$retVal);
		
		//delete all pictures
	
		if($retVal == 1){
			$imgbasepath = 'assets/images/products/';
			 $oldFilePath300 = $imgbasepath.'300_300/'.$img;
			 $oldFilePath800 = $imgbasepath.'800_800/'.$img;
			 $oldFilePathOrg = $imgbasepath.'original/'.$img;
			 @unlink($oldFilePath300);
			 @unlink($oldFilePath800);
			 @unlink($oldFilePathOrg);
		}			
		
	}
	
	
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'itemaprv';
    $currPage    = basename($_SERVER['PHP_SELF']);
	
	// load session privilege;
	include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/rawitem.php?res=0&msg='Insert Data'&mod=12");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'CODE')
            ->setCellValue('C1', 'ITEM NAME')
            ->setCellValue('D1', 'BUIESNESS TYPE')
            ->setCellValue('E1', 'COMPANY TYPE')
            ->setCellValue('F1', 'LICENSE TYPE')
            ->setCellValue('G1', 'ITEM CATAGORY')
            ->setCellValue('H1', 'DESCRIPTION');

        $firststyle = 'A2';
        $qry        = "SELECT i.`id`, i.`code`, i.`name` itnm,c.`name` bt, i.`size` ct, p.`name` lt, ic.`name` ItemCat, i.`dimension`,i.`wight`, i.`image`, i.`description`
FROM `item` i,`color` c,`pattern` p,`itmCat` ic
where i.`color`=c.`id` and i.`pattern`= p.`id` and i.`catagory`=ic.`id`
order by i.`code`";
        // echo  $qry;die;
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {$i = 0;
            while ($row = $result->fetch_assoc()) {
                $urut = $i + 2;
                $col1 = 'A' . $urut;
                $col2 = 'B' . $urut;
                $col3 = 'C' . $urut;
                $col4 = 'D' . $urut;
                $col5 = 'E' . $urut;
                $col6 = 'F' . $urut;
                $col7 = 'G' . $urut;
                $col8 = 'H' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['code'])
                    ->setCellValue($col3, $row['itnm'])
                    ->setCellValue($col4, $row['bt'])
                    ->setCellValue($col5, $row['ct'])
                    ->setCellValue($col6, $row['lt'])
                    ->setCellValue($col7, $row['ItemCat'])
                    ->setCellValue($col8, $row['description']);
                /* */
                //$laststyle=$title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ITEM');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'item' . $today . '.xls';
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

    <style>
        .table > tbody > tr > td:last-child a {
    display: block;
    padding: 6px 15px;
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
        <span>APPROVAL</span>
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
      			<!--<div class="panel-heading"><h1>Product List</h1></div>-->
    				<div class="panel-body  panel-body-padding">

                <span class="alertmsg">
                </span>
                  <!-- <div class="row form-header">

	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>Products <i class="fa fa-angle-right"></i> Item Out Information</h6>
      		                            </div>

      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>


                                   </div>      -->

                	<form method="post" action="rawitemAprvList.php" id="form1">

                     <!-- <div class="well list-top-controls">
                      <div class="row border">

                        <div class="col-xs-6 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <!--OLD COM div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div  OLD COM-->
                        <!--<div class="col-xs-6">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div>
                    </div> -->

                     <div class="well list-top-controls">
                      <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Approval <i class="fa fa-angle-right"></i> Products</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">
                            
                        <div class="pull-right grid-panel form-inline">

                                <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>							
                            
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cat" id="cat" class="form-control" >
                                            <option value="0">Category</option>
    <?php
$qry1    = "select id,name from itmCat order by name";
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
                                    <div class="form-group styled-select">
                                        <select name="brand" id="brand" class="form-control" >
                                            <option value="0">Brand</option>
    <?php
$qry1    = "select id,title from brand order by title";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["title"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($brand == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                          <div class="form-group">
                            |
                             </div>
                            <div class="form-group">
                            <input type="search" placeholder="Search by Keyword" id="search-dttable" class="form-control">
                            </div>
                            <!--div class="form-group">
                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            </div-->
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


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table id='listTable' class='display actionbtn no-footer dataTable' width="100%">
                        <thead>
                        <tr>
						
							<th>Created</th>
                            <th width="100">Photo</th>
                            <th>Product ID</th>
                            <th>Barcode</th>
                            <th>Item Name</th>
                            <th>Color </th>
                            <th>Brand </th>
                            <th>Category</th>
							
							<th>LDP</th>
                            <th>Price Including VAT</th>
                            <th>length</th>
                            <th>Width</th>
                            <th>Height</th>
                            <th>Parts</th>
                            <th>Finshed</th>
                            <th>Approved</th>
                            <th>Edit | Barcode | Delete</th>

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
				/*'searching': true,*/
                'ajax': {
                    //'url':'phpajax/datagrid_item.php?action=item&brnd=<?=$brand ?>&icat=<?=$icat ?>',
					'url':url
                },
                'columns': [

 	
					
					
					{ data: 'make_dt','bVisible': false },
                    { data: 'photo', "orderable": false },
                    //{ data: 'code' },
					
                    { data: 'code',
					'render': function (code) {
						return '<span class="rowid_'+ code +'">' + code +'</span>'
						}
					},
					
					{ data: 'barcode' },
                    { data: 'itnm' },
                    { data: 'color' },
					{ data: 'brand' },
                    { data: 'ItemCat' },
                    
                    { data: 'cal_ldp' },
                    { data: 'rate' },
                    { data: 'lenth' },
                    { data: 'width' },
                    { data: 'height' },
					{ data: 'parts' },
					{ data: 'finished' },
					{ data: 'approved' },

				{ data: 'action_buttons', 'orderable':false},
					//{ data: 'edit', "orderable": false },
					//{ data: 'bc', "orderable": false },
					//{ data: 'del', "orderable": false }
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
	url = 'phpajax/datagrid_item.php?action=itemAprv';
	table_with_filter(url);				
	
	
        //category
        $("#cat").on("change", function() {

            var cat = $(this).val();
			
			//'url':'phpajax/datagrid_item.php?action=item&brnd=<?=$brand ?>&icat=<?=$icat ?>',
			
			url = 'phpajax/datagrid_item.php?action=itemAprv&icat='+cat;
			
            setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });
	
	    //brand
        $("#brand").on("change", function() {

            var brand = $(this).val();
			
			url = 'phpajax/datagrid_item.php?action=itemAprv&brnd='+brand;
			
            setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	


}); //$(document).ready(function(){



        </script>
<script>
$(document).ready(function(){		
	//delete row

	$("#listTable_wrapper").on("click",".griddelbtn", function() {

				var url = $(this).attr('href');
		  //alert(url);
		  //swal(url);
		//return false;


				  swal({
				  title: "Are you sure?",
				  text: "Once deleted, you will not be able to recover this item!",
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
 });					
</script>

    </body></html>
  <?php } ?>
