<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();

$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'contact';
    include_once('common/inc_session_privilege.php');
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/contact.php?res=0&msg='Insert Data'&mod=2");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'CONTACT CODE')
            ->setCellValue('C1', 'NAME')
            ->setCellValue('D1', 'CONTACT TYPE')
            ->setCellValue('E1', 'ORGANIZATION')
            ->setCellValue('F1', 'DOB')
            ->setCellValue('G1', 'DESIGNATION')
            ->setCellValue('H1', 'DEPARTMENT')
            ->setCellValue('I1', 'PHONE')
            ->setCellValue('J1', 'EMAIL')
            ->setCellValue('K1', 'WEBSITE')
            ->setCellValue('L1', 'SOURCE')
            ->setCellValue('M1', 'SOURCE NAME')
            ->setCellValue('N1', 'DETAILS')
            ->setCellValue('O1', 'AREA')
            ->setCellValue('P1', 'STREET')
            ->setCellValue('Q1', 'DISTRICT')
            ->setCellValue('R1', 'ZIP')
            ->setCellValue('S1', 'COUNTRY')
            ->setCellValue('T1', 'NAME')
            ->setCellValue('U1', 'CREATE DATE')
            ->setCellValue('V1', 'BALANCE');

        $firststyle = 'A2';
        $qry        = "SELECT a.`contactcode`, a.`name`,b.`name` contacttype,o.`name` `organization`, a.`dob`, c.`name` `designation`,d.`name` `department`, a.`phone`, a.`email`, a.`website`, h.`name` `source`, a.`sourcename`
        , a.`details`, a.`area`, a.`street`,e.`name` `district`,g.`name` `state`, a.`zip`,f.`name` `country`, a.`opendt`, a.`currbal`
        FROM `contact` a
        left join `contacttype` b on a.`contacttype`=b.`id`
        left join `designation` c on a.`designation`=c.`id`
        left join `department` d on a.`department`=d.`id`
        left join `district` e on a.`district`=e.`id`
        left join `country` f on  a.`country`=f.id
        left join `state` g on a.`state`=g.`id`
        left join `source` h ON a.`source`=h.`id`
        left join `organization` o ON a.`organization`= o.`orgcode`
        WHERE   a.`status`=1 and a.`contacttype` <>3 order by a.`name`";
        //echo  $qry;die;
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
                $col13 = 'M' . $urut;
                $col14 = 'N' . $urut;
                $col15 = 'O' . $urut;
                $col16 = 'P' . $urut;
                $col17 = 'Q' . $urut;
                $col18 = 'R' . $urut;
                $col19 = 'S' . $urut;
                $col20 = 'T' . $urut;
                $col21 = 'U' . $urut;
                $col22 = 'V' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['contactcode'])
                    ->setCellValue($col3, $row['name'])
                    ->setCellValue($col4, $row['contacttype'])
                    ->setCellValue($col5, $row['organization'])
                    ->setCellValue($col6, $row['dob'])
                    ->setCellValue($col7, $row['designation'])
                    ->setCellValue($col8, $row['department'])
                    ->setCellValue($col9, $row['phone'])
                    ->setCellValue($col10, $row['email'])
                    ->setCellValue($col11, $row['website'])
                    ->setCellValue($col12, $row['source'])
                    ->setCellValue($col13, $row['sourcename'])
                    ->setCellValue($col14, $row['details'])
                    ->setCellValue($col15, $row['area'])
                    ->setCellValue($col16, $row['street'])
                    ->setCellValue($col17, $row['district'])
                    ->setCellValue($col18, $row['state'])
                    ->setCellValue($col19, $row['zip'])
                    ->setCellValue($col20, $row['country'])
                    ->setCellValue($col21, $row['opendt'])
                    ->setCellValue($col22, $row['currbal']); /* */
                $laststyle = $title;
            }
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('CONTACT');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'contact_' . $today . '.xls';
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
        <span>CRM</span>
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
      		<!--	<div class="panel-heading"><h1>All Contact</h1></div> -->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="contactList.php?pg=1&mod=2" id="form1">

                     <div class="well list-top-controls">
                      <!--<div class="row border">

                        <div class="col-sm-11 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div> -->
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>CRM <i class="fa fa-angle-right"></i> All Contacts</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">
                            </div>
                           <!-- <div class="form-group">
                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            </div> -->
                            <div class="form-group">
                                <?=getBtn('export')?>
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
                    <table id='listTable' class='display actionbtn no-footer dataTable dt-responsive' width="100%">
                        <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <!--<th>Hidden Char</th>-->
                            <th>Action</th>

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
    
            var table1 =  $('#listTable').DataTable().destroy();  
            
            var table1 = $('#listTable').DataTable({
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 25,
				scrollX: true,
				//bScrollInfinite: true,
				//bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				"order": [[ 1, "ASC" ]],
				//scroller: true,
				"dom": "rtiplf",
                'ajax': {
                    //'url':'phpajax/datagrid_list.php'
                    'url':url,
                },
                'columns': [
                    { data: 'photo', "orderable": false  },
                    { data: 'name' },
                    { data: 'organization' },
					{ data: 'designation' },
					{ data: 'department' },
                    { data: 'phone' },
					{ data: 'email' },
					//{ data: 'hidden' },
					{ data: 'action_buttons', "orderable": false  }
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
	url = 'phpajax/datagrid_list.php';
	table_with_filter(url);	             



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

             
});//$(document).ready(function(){




        </script>

    </body></html>
  <?php } ?>
