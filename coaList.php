<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();

$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];
$lvl=$_POST['cmblvl'];
 
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'coa';
    include_once('common/inc_session_privilege.php');
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/coa.php?res=0&msg='Insert Data'&mod=7");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'GL NO')
            ->setCellValue('C1', 'GL Name')
            ->setCellValue('D1', 'Parent GL')
            ->setCellValue('E1', 'Posted Flag')
            ->setCellValue('F1', 'Is Financial Only')
            ->setCellValue('G1', 'Gl Nature')
            ->setCellValue('H1', 'Level')
            ->setCellValue('I1', 'Closing Balance');

        $firststyle = 'A2';
        $qry= "SELECT `id`, `glno`,
        (case when lvl=5 then concat('        ',`glnm`) when lvl=4 then concat('      ',`glnm`) when lvl=3 then concat('    ',`glnm`) when lvl=2 then concat('  ',`glnm`) else `glnm` end)glnm 
        , `ctlgl`, `isposted`,`oflag`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE `status` = 'A'  and `oflag`='N' order by glno";
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
                
                $i++;
                 if($row["dr_cr"] == 'D'){
                   $type = "Debit";
               }else{
                   $type = "Credit";
               }
               
               if($row["isposted"] == 'P'){
                   $isposted = "YES";
               }else{
                   $isposted = "NO";
               }
              
               if($row["oflag"] == 'Y'){
                   $isfinanced = "YES";
               }else{
                   $isfinanced = "NO";
               }
                
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['glno'])
                    ->setCellValue($col3, $row['glnm'])
                    ->setCellValue($col4, $row['ctlgl'])
                    ->setCellValue($col5, $isposted)
                    ->setCellValue($col6, $isfinanced)
                    ->setCellValue($col7, $type)
                    ->setCellValue($col8, $row['lvl'])
                    ->setCellValue($col9, $row['closingbal']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Chart Of Accounts');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'coa_' . $today . '.xls';
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

                	<form method="post" action="coaList.php" id="form1">

                     <div class="well list-top-controls">

                      <div class="row border">
                       <div class="col-sm-3 text-nowrap">
                            <h6>Accounting <i class="fa fa-angle-right"></i> All Chart of Account</h6>
                       </div>
                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            <!--div class="form-group">
                                <input type="search" id="search-dttable" class="form-control">
                            </div-->
                            <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmblvl" id="cmblvl" class="form-control" >
                                            <option value="0">All Level</option>
                                            <option value="1" <?php if ($lvl == "1") {echo "selected";} ?> >1</option>
                                            <option value="2" <?php if ($lvl == "2") {echo "selected";} ?> >2</option>
                                            <option value="3" <?php if ($lvl == "3") {echo "selected";} ?> >3</option>
                                            <option value="4" <?php if ($lvl == "4") {echo "selected";} ?> >4</option>
                                            <option value="5" <?php if ($lvl == "5") {echo "selected";} ?> >5</option>
                                            
                                        </select>
                                    </div>
                                </div> 
							
                            <div class="form-group">
                            <input type="search" id="search-dttable"  placeholder="Search Keywords" class="form-control">     
                            </div>
                            <div class="form-group">
                            <div class="form-group">
                                <?= getBtn('create') ?>
                            </div>
                            <!--div class="form-group">
                                <?= getBtn('export') ?>
                            </div-->
                            <div class="form-group">
                            
                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
								</ul>
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
                    
  <style>
      
_#listTable.coa-table span:before{
    font-family: fontawesome;
    content:"\f068";
    display: inline-block;
    padding-right: 10px;
}
#listTable.coa-table span{
    display: block; 
    padding: 12px 0;
    /*background-color: rgba(120,179,195,0.09);*/
}
#listTable.coa-table .lvl-1{ padding-left:0px; font-weight: bold;}
#listTable.coa-table .lvl-1{ padding-left:0px; }
#listTable.coa-table .lvl-2{ padding-left:30px;  }
#listTable.coa-table .lvl-3{ padding-left:60px; }
#listTable.coa-table .lvl-4{ padding-left:90px; }
#listTable.coa-table .lvl-5{ padding-left:120px; }   
  </style>                  
                    
                    
                    
                    <table  id='listTable' class='display dataTable coa-table' width="100%">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>GL Account No</th>
                            <th>GL Name</th>
                            <th>Parent GL</th>
                            <!-- <th>Deal Value</th> -->
                            <th>Is Posted? </th>
                            <th>Type </th>
                            <th>Level </th>
                            <th>Openning Balance</th>
                            <th>Closing Balance</th>
                            
                            <th>Action</th>
                            <!--th>extra</th-->
                            
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
          //var url='phpajax/datagrid_list_all.php?action=coa';  
          //alert(url);
            function table_with_filter(url){
	
        	 var table1 =  $('#listTable').DataTable().destroy();
             var table1 = $('#listTable')
				.DataTable({
				//"dom": 'rtip', // the "r" is for the "processing" message
				
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 25,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				deferRender: true,
				scroller: true,
				"dom": "rtiplf",
                ajax: {
                    'url':url,
                },
                createdRow: function(row, data, dataIndex) {
                    var firstColumnValue = data.rowclass; 
                    $(row).addClass(firstColumnValue);
                },
                columns: [
                    
                    
                    { data: 'id' }, 
                    { data: 'glno' },
                    { data: 'glnm' },
                    { data: 'ctlgl' },
                    { data: 'isposted' },
					//{ data: 'value' },
                    { data: 'type' },
                	{ data: 'lvl' },
                	{ data: 'opbal' },
                    { data: 'closingbal' },
					{ data: 'action', "orderable": false }
					//{ data: 'rowclass',"visible":false}
                ],

                

            });
            
            setTimeout(function(){
			    table1.columns.adjust().draw();
			    
            }, 350);


             $('#search-dttable').keyup(
                 function(){
                     table1.search($(this).val()).draw();
                     //setTimeout(function(){ putClass(); }, 300);

             })
        }
        
    //general call on page load
	url = 'phpajax/datagrid_list_all.php?action=coa';
	table_with_filter(url);	
	
        //Status
        $("#cmblvl").on("change", function() {
            var level = $(this).val();
			url = 'phpajax/datagrid_list_all.php?action=coa&coalvl='+level;
			//alert(status);
            setTimeout(function(){
			    table_with_filter(url);
            }, 350);			

        });		 
       
    });
    
    //delete row
			
        $("#listTable").on("click",".griddelbtn", function() {

			var url = $(this).attr('href');

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
        
        
        
            setTimeout(function(){
			    //treeView();
            }, 2050); 
        </script>
        
        
<script>
    //tree view
 //$(document).ready(function(){  
 


 
 
function treeView(){
        
       hideAllLvl1();
       
        var lvl_1 = 1;
        var lvl_2 = 1;
        var lvl_3 = 1;
        var lvl_4 = 1;
        
        $("#listTable").on("click",".l-1",function() {
            if (lvl_1 === 1) {
                $(this).next().show();
                lvl_1 = 0;
            } else {
                $(this).next().hide();
                lvl_1 = 1;
            }
        });


        $("#listTable").on("click",".l-2",function() {
            if (lvl_2 === 1) {
                    $(this).next().show();
                    lvl_2 = 0;
            } else {
                    $(this).next().hide();
                    lvl_2 = 1;
            }
        });


        $("#listTable").on("click",".l-3",function() {
            if (lvl_3 === 1) {
                $(this).next().show();
                lvl_3 = 0;
            } else {
                $(this).next().hide();
                lvl_3 = 1;
            }
        });
        
        $("#listTable").on("click",".l-4",function() {
            if (lvl_4 === 1) {
               $(this).nextAll(".l-5").show();
                lvl_4 = 0;
            } else {
                 $(this).nextAll(".l-5").hide();
                lvl_4 = 1;
            }
        });
        

        //alert(1);
    
}
    
 //});   
</script> 
 <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
                
                var sdt = $('#from_dt').val();
        	    var edt = $('#to_dt').val();
            
				var pdfurl = 'pdf_coa.php?dt_f='+sdt+'&dt_t='+edt;
				location.href=pdfurl;
				
			});
			
			
		</script>
       
        
    </body></html>
  <?php } ?>
