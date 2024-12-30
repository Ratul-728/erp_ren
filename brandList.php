<?php
include_once("common/conn.php");
session_start();

// ini_set('display_errors', 1);	
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; die;

$usr = $_SESSION["user"];
if (!$_SESSION["user"]) {
	header("Location: " . $hostpath . "/hr.php");
}else{
	
	
	//item delete process;
	
	if(isset($_REQUEST['action'] ) && $_REQUEST['action'] == "delete"){
		//echo "<pre>"; print_r($_SESSION); echo "</pre>";
		
		include_once('rak_framework/delete.php');
		include_once('rak_framework/fetch.php');
		include_once('rak_framework/edit.php');
		
		$img = fetchByID('brand','id',$_REQUEST['id'],'image');
		
		
		//echo $img;die;
	
		deleteRow('brand','id',$_REQUEST['id'],$msg,$retVal);
		
		//delete all pictures
	
		if($retVal == 1){
			$imgbasepath = 'assets/images/brands/';
			 $oldFilePath300 = $imgbasepath.'300_300/'.$img;
			 $oldFilePath800 = $imgbasepath.'800_800/'.$img;
			 $oldFilePathOrg = $imgbasepath.'original/'.$img;
			 @unlink($oldFilePath300);
			 @unlink($oldFilePath800);
			 @unlink($oldFilePathOrg);
			
			header("location:".$_SERVER['PHP_SELF']."?mod=12&msg=Data Successfully Deleted");
		}			
		
	}
	
	
	
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'brand';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/brand.php?res=0&msg='Insert Data'&mod=12");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'BRAND ID')
            ->setCellValue('C1', 'BRAND')
            ->setCellValue('D1', 'ORIGIN');

        $firststyle = 'A2';
        $qry        = "SELECT b.`id`,b.`code`, b.`title`, b.`origin`, b.`image`, b.makedt make_dt FROM `brand` b  WHERE 1=1";
        // echo  $qry;die;
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {$i = 0;
            while ($row = $result->fetch_assoc()) {
                $urut = $i + 2;
                $col1 = 'A' . $urut;
                $col2 = 'B' . $urut;
                $col3 = 'C' . $urut;
                $col4 = 'D' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['code'])
                    ->setCellValue($col3, $row['title'])
                    ->setCellValue($col4, $row['origin']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Brand');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'brand' . $today . '.xls';
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
        <span>INVENTORY</span>
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


  
                	<form method="post" action="#" id="form1">
    <span class="alertmsg">
    </span>
                     <div class="well list-top-controls">
                      <div class="row border">
                           <div class="col-sm-3 text-nowrap">
                            <h6 class="header-text">Inventory <i class="fa fa-angle-right"></i> All Brands</h6>
                       </div>

                        <div class="col-sm-9 text-nowrap">
                        <div class="pull-right grid-panel form-inline">

                             <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">
                            </div>
                            <div class="form-group">
                            <button type="submit" title="Create New"  id="add"  name="add" value="+ Create New Item "  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                            <button type="submit" title="Export" name="export" id="export" value="Export Data" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
                            </div>
                            </div>
                        </div>
                      </div>
                    </div>


    				</form>


	<link href="js/plugins/datagrid/datatables.css" rel="stylesheet" type="text/css">

                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th width="1">Create</th>
							<th width="1">Photo</th>
                            <th width="5">Brand ID</th>
                            <th width="50%">Brand</th>
                            <th width="50%">Origin </th>
                            <th width="5">Actions</th>

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

    if ($_REQUEST['msg']) {
        echo "<script type='text/javascript'>messageAlert('" . $_REQUEST['msg'] . "')</script>";
    }

    ?>		
		
     <!-- Datatable JS -->
<script src="js/plugins/datagrid/datatables.min.js"></script>

<!-- Script -->
<script language="javascript">
	
$(document).ready(function(){
			
	function table_with_filter(url){
			
			var table1 = $('#listTable').DataTable().destroy();
            var table1 = $('#listTable').DataTable({
			"lengthChange": false,
			processing: true,
			fixedHeader: true,
			serverSide: true,
			serverMethod:'post',
			pageLength: 25,
			scrollX: true,
			bScrollInfinite: true,
			bScrollCollapse: true,
			/*scrollY: 550,*/
			deferRender: true,
			scroller: true,
			"ordering": true,
			"order": [[ 0, "desc" ]],
			"dom": "rtiplf",
			/*'searching': true,*/
			'ajax': {
				'url':url
			},
			'columns': [
				{ data: 'make_dt','bVisible':false },
				{ data: 'photo' },
				{ data: 'code',
				'render': function (code) {
					return '<span class="rowid_'+ code +'">' + code +'</span>'
					}
				},					

				{ data: 'title' },
				{ data: 'origin' },
				{ data: 'action_buttons', 'orderable':false},
			]
         });

			
            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
            
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            }) 		
			

	}
	//general call on page load
	url = 'phpajax/datagrid_brand.php?action=brand';
	table_with_filter(url);	
			
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
				  text: "Once deleted, you will not be able to recover this brand name!",
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

    </body>
</html>
  <?php } ?>
