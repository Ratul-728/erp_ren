<?php
require "common/conn.php";
require "common/user_btn_access.php";

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
    $currSection = 'hc';
    include_once('common/inc_session_privilege.php');
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        //$url=$hostpath."/employee_hr.phpp?res=0&msg='Insert Data'&mod=4";
        //echo $url;die;
        header("Location: " . $hostpath . "/employee_hr.php?res=0&msg='Insert Data'&mod=4");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'EMPLOYEE CODE')
            ->setCellValue('C1', 'FIRST NAME')
            ->setCellValue('D1', 'LAST NAME')
            ->setCellValue('E1', 'DOB')
            ->setCellValue('F1', 'GENDER')
            ->setCellValue('G1', 'MARITIAL STATUS')
            ->setCellValue('H1', 'NID')
            ->setCellValue('I1', 'TIN')
            ->setCellValue('J1', 'BLOOD GROUP')
            ->setCellValue('K1', 'PP')
            ->setCellValue('L1', 'DRIVING LICENSE')
            ->setCellValue('M1', 'PRESENT ADDRESS')
            ->setCellValue('N1', 'AREA')
            ->setCellValue('O1', 'OFFICE CONTACT')
            ->setCellValue('P1', 'PERSONAL CONTACT')
            ->setCellValue('Q1', 'OFFICE EMAIL')
            ->setCellValue('R1', 'PERSONAL EMAIL')
            ->setCellValue('S1', 'ALTERNATIVE EMAIL');

        $firststyle = 'A2';
        //$qry="SELECT a.`contactcode`, a.`name`,b.`name` contacttype, a.`organization`, a.`dob`, c.`name` `designation`,d.`name` `department`, a.`phone`, a.`email`, a.`website`, h.`name` `source`, a.`sourcename`
        //, a.`details`, a.`area`, a.`street`,e.`name` `district`,g.`name` `state`, a.`zip`,f.`name` `country`, a.`opendt`, a.`currbal` FROM `contact` a ,`contacttype` b,`designation` c,`department` d,`district` e,`country` f,`state` g,`source` h WHERE a.`contacttype`=b.`id` and a.`designation`=c.`id` and a.`department`=d.`id` and a.`district`=e.`id` and a.`country`=f.id and a.`state`=g.`id` and a.`source`=h.`id` and a.`status`=1 and a.`contacttype` in (1,2) order by a.`name`";
        $qry = "select `employeecode`, `firstname`, `lastname`, `dob`, `gender`, `maritialstatus`, `nid`, `tin`, `bloodgroup`, `pp`, `drivinglicense`, `presentaddress`, `area`, `district`, `postal`, `country`
        , `office_contact`, `ext_contact`, `pers_contact`, `alt_contact`, `office_email`, `pers_email`, `alt_email` FROM `employee`";
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
                    ->setCellValue($col2, $row['employeecode'])
                    ->setCellValue($col3, $row['firstname'])
                    ->setCellValue($col4, $row['lastname'])
                    ->setCellValue($col5, $row['dob'])
                    ->setCellValue($col6, $row['gender'])
                    ->setCellValue($col7, $row['maritialstatus'])
                    ->setCellValue($col8, $row['nid'])
                    ->setCellValue($col9, $row['tin'])
                    ->setCellValue($col10, $row['bloodgroup'])
                    ->setCellValue($col11, $row['pp'])
                    ->setCellValue($col12, $row['drivinglicense'])
                    ->setCellValue($col13, $row['presentaddress'])
                    ->setCellValue($col14, $row['area'])
                    ->setCellValue($col15, $row['office_contact'])
                    ->setCellValue($col16, $row['pers_contact'])
                    ->setCellValue($col17, $row['office_email'])
                    ->setCellValue($col18, $row['pers_email'])
                    ->setCellValue($col19, $row['alt_email']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Contact');
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
<style>
  .btn.lock{
    background-color: rgb(230,98,98);
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
        <span>All Employee</span>
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
      			<!--<div class="panel-heading"><h1>All Employee</h1></div>-->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>


                	<form method="post" action="hcList.php" id="form1">

                     <div class="well list-top-controls">
                     <div class="row border">
                          <div class="col-sm-1 text-nowrap">
                          <h6>HRM <i class="fa fa-angle-right"></i> All Employee</h6>
                        </div>

                        <div class="col-sm-11 text-nowrap">
                              <div class="pull-right grid-panel form-inline">
                                <div class="form-group">
                            <input type="search " id="search-dttable" class="form-control mini-issue-search">
                            </div>

                           <div class="form-group">
                               <?= getBtn('create') ?>
                            </div>
                           <div class="form-group">
                               <?= getBtn('export') ?>
                            </div>




                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                        </div>
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>

                      </div>
                    </div>


    				</form>


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable actionbtn' width="100%">
                        <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Picture</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>DOB</th>
                            <th>Phone</th>
                            <th>Email </th>
                            <th>NID </th>
                            <th>Blood Group</th>
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
            var table1 = $('#listTable').DataTable({
                /*'processing': true,
                'serverSide': true,
                'serverMethod': 'post', */
                'processing': true,
				'fixedHeader': true,
                'serverSide': true,
                'serverMethod': 'post',
				'pageLength': 10,
				'scrollX': true,
				'bScrollInfinite': true,
				'bScrollCollapse': true,
				/*scrollY: 550,*/
				//'select':true,
				'deferRender': true,
				'scroller': true,

				"dom": "rtiplf",


                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=hc'
                },
                'columns': [
                    { data: 'id' },
                    { data: 'photo' },
                    { data: 'employeecode' },
                    { data: 'name' },
                    { data: 'desi' },
                    { data: 'dept' },
                    { data: 'dob' },
					{ data: 'office_contact' },
					{ data: 'office_email' },
                    { data: 'nid' },
					{ data: 'bloodgroup' },
					{ data: 'action', "orderable": false },
                ]
            });

            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);

            $('#listTable tbody').on('click', 'tr', function () {
                var d = table1.row( this ).data();
                //alert(d['id']);
            });

            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
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


  //block user row		
$("#listTable").on("click",".gridblock", function() {

      var url = $(this).attr('href');
      var hrid = $(this).data('hrid');
      var status = $(this).data('status');

      var actiontxt = (status==0)?'block':'unblock';
    
    swal({
    title: "Are you sure?",
    text: "Do you want to "+actiontxt+" login access to this user?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    buttons: ['Cancel', 'Confirm Block'],
  })
  .then((willDelete) => {
    if (willDelete) {
      
      var element = $(this);

      $.ajax({
        url: url,
        method: "POST",
        data: { hrid: hrid,status:status},
        success: function(response){
          response = JSON.parse(response);
          //console.log(response);


           //change btn color and icon;
           //alert(response.status);
           //alert(status);
            if(response.status=='success'){
              if(status==0){
                element.removeClass('unlock').addClass('lock');
                element.find('i').removeClass('fa-unlock').addClass('fa-lock');
                element.data('status',1);
                }else{
                  element.removeClass('lock').addClass('unlock');
                  element.find('i').removeClass('fa-lock').addClass('fa-unlock');
                  element.data('status',0);
                }
              }

              setTimeout(function(){
                swal(response.message, {
                  icon: response.status,
                });
              }, 100);
              


          }

      }); //$.ajax({

    //location.href=url;
    }//if (willDelete) {
  });

return false;
});			
      


        </script>

    </body></html>
  <?php } ?>
