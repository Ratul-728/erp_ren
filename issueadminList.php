<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {

    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'issueadmin';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/issueadmin.php?res=0&msg='Insert Data'&mod=6");
    }
    if (isset($_POST['announce'])) {
        header("Location: " . $hostpath . "/announcement.php?res=0&msg='Insert Data'&mod=6");
    }
    if (isset($_POST['sms'])) {
        header("Location: " . $hostpath . "/sms.php?res=0&msg='Insert Data'&mod=6");
    }
    if (isset($_POST['export'])) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'TICKET NO')
            ->setCellValue('C1', 'ORGANIZATION')
            ->setCellValue('D1', 'SUBJECT')
            ->setCellValue('E1', 'ISSUE DATE')
            ->setCellValue('F1', 'DUE DATE')
            ->setCellValue('G1', 'PRODUCT')
            ->setCellValue('H1', 'ISSUE TYPE')
            ->setCellValue('I1', 'ISSUE SUB TYPE')
            ->setCellValue('J1', 'SEVERETY')
            ->setCellValue('K1', 'ASSIGNED')
            ->setCellValue('L1', 'STATUS')
            ->setCellValue('M1', 'REPORTER')
            ->setCellValue('N1', 'CHANNEL')
            ->setCellValue('O1', 'ACCOUNT MANAGER');

        $firststyle = 'A2';
        $qry        = "SELECT t.`id` id,t.`tikcketno`,o.name `organization`,t.`sub`,date_format(t.`issuedate`,'%d/%m/%y') issuedate
        ,date_format(t.`probabledate`,'%d/%m/%y') `probabledate`,i.name `product`,tp.name `issuetype`,sb.name `issuesubtype`
,p.name `severity`,concat_ws(' ',emp.`firstname`,emp.`lastname`) assigned,st.stausnm `status`,h2.hrName `reporter`,cn.name `channel`,h3.hrName `accountmanager`
FROM issueticket t left join organization o on t.organization=o.id
left join item i on t.product=i.id left join issuetype tp on t.issuetype =tp.id left join issuesubtype sb on t.issuesubtype=sb.id
 left join hr h2 on t.reporter=h2.id left join hr h3 on t.accountmanager=h3.id left join employee emp on t.`assigned`=emp.id
left join issuestatus st on t.status=st.id left join issuechannel cn on t.channel=cn.id left join issuepriority p on t.severity=p.id where 1=1  order by t.id asc";
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
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['tikcketno'])
                    ->setCellValue($col3, $row['organization'])
                    ->setCellValue($col4, $row['sub'])
                    ->setCellValue($col5, $row['issuedate'])
                    ->setCellValue($col6, $row['probabledate'])
                    ->setCellValue($col7, $row['product'])
                    ->setCellValue($col8, $row['issuetype'])
                    ->setCellValue($col9, $row['issuesubtype'])
                    ->setCellValue($col10, $row['severity'])
                    ->setCellValue($col11, $row['assigned'])
                    ->setCellValue($col12, $row['status'])
                    ->setCellValue($col13, $row['reporter'])
                    ->setCellValue($col14, $row['channel'])
                    ->setCellValue($col15, $row['accountmanager']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ISSUE');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'issue_' . $today . '.xls';
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

    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php
include_once 'common_header.php';
    ?>
    <style>
        .dropdown_icon {
            display: inline-block;
            color: #8a8a8a;
            font-size: 12px;
            border: 1px solid #d4d4d4;
            padding: 10px 11px;
            border-radius: 50%;
            cursor: pointer;
        }
        .dropdown-menu {
            padding: 0;
            border-radius: 4px;
            box-shadow: 10px 10px 20px #c8c8c8;
            margin-top: 10px;
            left: -18px;
            top: 32px;
        }
    </style>
      <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>-->

    <!--<script src="  https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/js/all.min.js"></script>-->

    <body class="list">

    <?php
include_once 'common_top_body.php';
    ?>
    <div id="wrapper">

      <!-- Sidebar -->

      <div id="sidebar-wrapper" class="mCustomScrollbar">

      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>All Issue</span>
      </div>

    <?php
include_once 'menu.php';
    ?>
    <?php

    if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }

    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>

<!-- Ratul Table Problem -->
    <link href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css" rel="stylesheet" type="text/css" />
    <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>
    <!--script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script-->
    <script src="https://cdn.datatables.net/fixedheader/3.0.0/js/dataTables.fixedHeader.min.js"></script>




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
      		<!--	<div class="panel-heading"><h1>All Issue</h1></div>-->
    				<div class="panel-body">

    <!-- <span class="alertmsg">
    </span> -->
    <div class="alertmsg"></div> <!-- Give Msg -->

                	<form method="post" action="issueadminList.php" id="form1">

                     <div class="well list-top-controls">
                      <div class="row border">
                       <!-- <div class="col-sm-1 text-nowrap">
                            <h6>Issue <i class="fa fa-angle-right"></i> All Issue</h6>
                       </div> -->




                <div class="col-lg-2 col-md-3 col-sm-3">
						<div class="form-group">
						  <div class="form-group styled-select mini-styled-select">
							<select name="ststype" id="ststype" class="form-control">
							  <option value="">Select Status</option>
	<?php
$qry8    = "SELECT `id`, `stausnm` FROM `issuestatus` order by `stausnm` ";
    $result8 = $conn->query($qry8);if ($result8->num_rows > 0) {while ($row8 = $result8->fetch_assoc()) {
        $tid8 = $row8["id"];
        $nm8  = $row8["stausnm"];
        ?>
                                <option value="<?echo $tid8; ?>"><?echo $nm8; ?></option>
    <?php }} ?>

							</select>
						  </div>
						</div>
				</div>
				            <!-- Assigned -->
				<div class="col-lg-2 col-md-3 col-sm-3">
						<div class="form-group">
						  <div class="form-group styled-select mini-styled-select">
							<select name="asigntype" id="asigntype" class="form-control" >
							  <option value="">Select Person</option>
	<?php $qryitm = "select `id`,  concat_ws(' ',`firstname`,`lastname`) `name`  FROM `employee` order by name";
    $resultitm     = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
        $tid = $rowitm["id"];
        $nm  = $rowitm["name"];
        ?>
                                <option value="<?echo $tid; ?>"><?echo $nm; ?></option>
    <?php }} ?>

							</select>
						  </div>
						</div>
				</div>
				            <!-- Product -->
				<div class="col-lg-2 col-md-3 col-sm-3">
						<div class="form-group">
						  <div class="form-group styled-select mini-styled-select">
							<select name="protype" id="protype" class="form-control" >
							  <option value="">Select Organization</option>
	<?php
$qry1    = "SELECT `id`,`name` FROM `organization`  order by `name` ";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid1 = $row1["id"];
        $nm1  = $row1["name"];
        ?>
                                <option value="<?echo $tid1; ?>"><?echo $nm1; ?></option>
    <?php }} ?>

							</select>
						  </div>
						</div>
				</div> <!-- Product -->

				<div class="col-lg-2 col-md-3 col-sm-3">
						<div class="form-group">
						  <div class="form-group styled-select mini-styled-select">
							<select name="isstype" id="isstype" class="form-control" >
							  <option value="">Select Issue Type</option>
	<?php
$qry1    = "SELECT `id`,`name` FROM `issuetype`  order by `name`";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $issid = $row1["id"];
        $issnm = $row1["name"];
        ?>
                                <option value="<?echo $issid; ?>"><?echo $issnm; ?></option>
    <?php }} ?>

							</select>
						  </div>
						</div>
				 </div>  <!-- Type -->
                 <div class="col-lg-2 col-md-8 col-sm-8 col-xs-5">
                   <div class="form-group">
                    <input type="search " id="search-dttable" placeholder="Search" class="form-control mini-issue-search">
                   </div>

                 </div>
   				<div class="col-lg-2 col-md-4 col-sm-4 col-xs-7">


                            	<style>
								/* only for  fix icon button on this page */
									#page-content-wrapper div > div > div > .panel .panel-body > form .btn{
										margin-top: 0!important;
									}
									.list-top-controls #add{height: 35px!important;}
									.icon-button-group button{
										width: 40px!important;
										margin-left: 5px!important;
										margin-top: 0;
										height: 35px!important;
									}
									.icon-button-group{padding-top: 0px!important;}
									.d-flex{
										display: flex;

									}

									.well.list-top-controls{
										padding-top: 18px!important;

									}

									.well.list-top-controls > div > div{
										padding-right: 0!important;
									}

									/* end only for  fix icon button on this page */
								</style>
                            	<div class="d-flex icon-button-group">
                                <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>

                                <button type="submit" title="Announce" name="announce" id="announce" class="form-control btn btn-default"><i class="fa fa-bullhorn"></i></button>

                                <button type="submit" title="SMS" name="sms" id="sms" class="form-control btn btn-default"><i class="fa fa-envelope"></i></button>

                                <button type="submit" title="export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
                                </div>





 				</div>





                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>

                      </div>
                    </div>


    				</form>


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table id='listTable' class=' table table-bordered table-hover dt-responsive dataTable actionbtn' width="100%">
                        <thead>
                        <tr>
                            <th>Action</th>
                            <th>Oganization </th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Issue Date</th>
                            <th>Assigned</th>
                            <th>Severity </th>
                            <th>Issue Type </th>
                            <th>Product </th>
                            <th>Close Date</th>
                            <th>Create By</th>

                            <th>Create Date</th>

                            <th>Channel </th>

                            <th>Account Manager </th>
                            <th></th>
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
include_once 'common_footer.php';
    ?>

     <!-- Datatable JS -->
		<script src="js/plugins/datagrid/datatables.min.js"></script>

        <!-- Script -->
        <script>
        $(document).ready(function(){
            var url = "phpajax/datagrid_list_all.php?action=issue";
            var sval = "";
            var aval = "";
            var proval = "";

             var table1 = $('#listTable').DataTable({
                'processing': true,
				//'fixedHeader': true,
                'serverSide': true,
                'serverMethod': 'post',
				'pageLength': 100,
				'scrollX': true,
				"scrollX": true,
				'bScrollInfinite': true,
				'bScrollCollapse': true,
				/*scrollY: 550,*/
				'deferRender': true,
				'scroller': true,
				//'retrieve': true,
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=issue'
                },
                'columns': [

                    { data: 'humbar', 'orderable': false},
                    { data: 'organization' },
                    { data: 'sub' },
                    { data: 'status' },
                    { data: 'issuedate' },
                    { data: 'assigned' },
                    { data: 'severity' },
                    { data: 'issuetype' },
                    { data: 'product' },
                    { data: 'probabledate' },
                    { data: 'createby' },
                    { data: 'createdt' },
                    { data: 'channel' },
                	{ data: 'accountmanager' },
					{ data: 'edit' , 'orderable': false},
					{ data: 'del', 'orderable': false }
                ]
            });
             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })

            //Table
	    function funtable(url){
	        table1.destroy();

            table1 = $('#listTable').DataTable({
                'processing': true,
				//'fixedHeader': true,
                'serverSide': true,
                'serverMethod': 'post',
				'pageLength': 100,
				'scrollX': true,
				'bScrollInfinite': true,
				'bScrollCollapse': true,
				/*scrollY: 550,*/
				'deferRender': true,
				'scroller': true,
				//'retrieve': true,
				"dom": "rtiplf",
                'ajax': {
                    'url':url,
                },
                'columns': [

                    { data: 'humbar', 'orderable': false},
                    { data: 'organization' },
                    { data: 'sub' },
                    { data: 'status' },
                    { data: 'issuedate' },
                    { data: 'assigned' },
                    { data: 'severity' },
                    { data: 'issuetype' },
                    { data: 'product' },
                    { data: 'probabledate' },
                    { data: 'createby' },
                    { data: 'createdt' },
                    { data: 'channel' },
                	{ data: 'accountmanager' },
					{ data: 'edit' , 'orderable': false},
					{ data: 'del' , 'orderable': false}
                ]
            });

            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
	    }

	    //Status
        $(document).on("change", "#ststype", function() {

            var sval = $(this).val();
			//alert(sval);
            var aval = $('#asigntype').val();
            var proval = $('#protype').val();
            var isstype = $('#isstype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval+"&isstype="+isstype;
            funtable(url);

        });
        //Assigned
        $(document).on("change", "#asigntype", function() {
            var aval = $(this).val();
            var sval = $('#ststype').val();
            var proval = $('#protype').val();
            var isstype = $('#isstype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval+"&isstype="+isstype;
            funtable(url);
        });
        //Product
        $(document).on("change", "#protype", function() {
            var proval = $(this).val();
            var aval = $('#asigntype').val();
            var sval = $('#ststype').val();
            var isstype = $('#isstype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval+"&isstype="+isstype;
            funtable(url);
        });

        //issue type
        $(document).on("change", "#isstype", function() {
            var isstype = $(this).val();
            var proval = $('#protype').val();
            var aval = $('#asigntype').val();
            var sval = $('#ststype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval+"&isstype="+isstype;
            funtable(url);
        });

    });



	    //Action
	    function action(pval, pid){
	        $.ajax({


				url:"phpajax/upaction.php",
				method:"POST",
				data:{val:pval,id:pid},

				success:function(res)
				{
				    if(pval == 3){
				        var link = "issueadmin.php?res=6&msg='Copy Data'&mod=6&id="+pid;
				    }else if(pval == 4){
				        var link = "issueadmin.php?res=4&msg='Update Data'&mod=6&id="+pid;
				    }else if(pval == 5){

				        confirmationDelete(pid);

				    }
				    if(res != "Successfully Update!" && res != "Something went Wrong"){
				        if(pval == 3 || pval == 4){
				            window.open(link, '_blank').focus();
				        }else if(pval != 5){
				            window.location.href = link;
				        }

				    } else{
    					$('.display-msg').html(res);

    					 messageAlertLong(res,'alert-success');
				    }
				}
			});
	    }


	    function confirmationDelete(anchor)
        {
            swal({
            title: "Are you sure?",
            text: "You will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: [
                'No, cancel it!',
                'Yes, I am sure!'
            ],

            }).then(function(isConfirm) {
                if (isConfirm) {

                //window.location= anchor.attr("href");
                var link = "common/delobj.php?obj=issueticket&ret=issueadminList&mod=6&id="+anchor;
                window.location.href = link;

                } else {
                    swal("Cancelled", "Issue isn't deleted!", "error");
                }
            });

        }

        </script>

        <!-- Change dropdown -->
        <script>
        /*Status
        $(document).on("change", "#ststype", function() {

            var sval = $(this).val();
            var aval = $('#asigntype').val();
            var proval = $('#protype').val();
            var isstype = $('#isstype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval+"&isstype="+isstype;
            funtable(url);
        });
        //Assigned
        $(document).on("change", "#asigntype", function() {
            var aval = $(this).val();
            var sval = $('#ststype').val();
            var proval = $('#protype').val();
            var isstype = $('#isstype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval+"&isstype="+isstype;
            funtable(url);
        });
        //Product
        $(document).on("change", "#protype", function() {
            var proval = $(this).val();
            var aval = $('#asigntype').val();
            var sval = $('#ststype').val();
            var isstype = $('#isstype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval+"&isstype="+isstype;
            funtable(url);
        });

        //issue type
        $(document).on("change", "#isstype", function() {
            var isstype = $(this).val();
            var proval = $('#protype').val();
            var aval = $('#asigntype').val();
            var sval = $('#ststype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval+"&isstype="+isstype;
            funtable(url);
        }); */

        /*$(document).ready(function(){
	        $('#listTable tbody').on( 'click', 'td', function () {
	            var al =  table.cell( this ).data();
	            alert(al);
    	        $('#listTable tbody').on( 'click', 'tr', function () {
                    var d = table.row( this ).data();
                    var  loc = "issue-details.php?isit=".concat(d["tikcketno"]);
                    alert(loc);
                    //window.location=loc;
                });
            });
		}); */


		/*
		$(document).ready(function(){
	        $('#listTable tbody').on( 'click', 'tr', function () {
                var d = table.row( this ).data();
                var  loc = "issue-details.php?isit=".concat(d["tikcketno"]);
                //alert(loc);
                window.location=loc;
            });
		});
		*/

		$(document).ready(function(){
	        $('#listTa ble tbody').on( 'click', 'td', function () {

	            var celldata = table.cell( this ).data();
                var cellindex = table.cell( this ).index().columnVisible;
                var row_clicked     = $(this).closest('tr');

                var tikcketno = table.row(row_clicked).data()['tikcketno'];

                //var data = table.row( this ).data();

                //alert(tikcketno);

                //console.log(cellindex);

                if(cellindex !=1){
                    var  loc = 'issue-details.php?isit='+tikcketno;
                    window.location=loc;
                }

            });
		});

        </script>

        <?php
if ($res == 1 || $res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>

    </body></html>
  <?php } ?>
