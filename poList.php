<?php
require "common/conn.php";

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
    $currSection = 'po';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/po.php?res=0&msg='Insert Data'&mod=3");
    }
    if (isset($_POST['export'])) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'PO NO')
            ->setCellValue('C1', 'SUPPLIER')
            ->setCellValue('D1', 'ORDER DATE')
            ->setCellValue('E1', 'TOTAL AMOUNT')
            ->setCellValue('F1', 'VAT')
            ->setCellValue('G1', 'TAX')
            ->setCellValue('H1', 'INVOICE AMOUNT')
            ->setCellValue('I1', 'DELIVERY DATE');
        $firststyle = 'A2';
        $qry        = "SELECT  p.`poid`,s.`name` , p.`orderdt`, p.`tot_amount`,p.`vat`,p.`tax`, p.`invoice_amount`,p.`delivery_dt` FROM `po` p,`suplier` s  WHERE p.supid=s.id order by p.`id`";
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
                $col9 = 'I' . $urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['poid'])
                    ->setCellValue($col3, $row['name'])
                    ->setCellValue($col4, $row['orderdt'])
                    ->setCellValue($col5, $row['tot_amount'])
                    ->setCellValue($col6, $row['vat'])
                    ->setCellValue($col7, $row['tax'])
                    ->setCellValue($col8, $row['invoice_amount'])
                    ->setCellValue($col9, $row['delivery_dt']); /* */
                $laststyle = $title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('PO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'po_' . $today . '.xls';
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
    body{
    font-family: 'Arial', sans-serif;
    background: #f9f9f9;
}
.p-30{
    p adding:30px;
}
.main-datatable {
    padding: 0px;
    border: 1px solid #f3f2f2;
    border-bottom: 0;
    box-shadow: 0px 2px 10px rgba(0,0,0,.05);
}
.d-flex{
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card_body{
    background-color: white;
    border: 1px solid transparent;
    border-radius: 2px;
    -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
}
.main-datatable .row {
    margin: 0;
}
.searchInput {
    width: 50%;
    display: flex;
    align-items: center;
    position: relative;
    justify-content: flex-end;
    margin: 20px 0px;
    padding: 0px 4px;
}
.searchInput input {
    border: 1px solid #e5e5e5;
    border-radius: 50px;
    margin-left: 8px;
    height: 34px;
    width: 100%;
    padding: 0px 25px 0px 10px;
    transition: all .6s ease;
}
.searchInput label {
    color: #767676;
    font-weight: normal;
}
.searchInput input:placeholder-shown {
    width: 13%;
}
.searchInput:hover input:placeholder-shown {
    width: 100%;
    cursor: pointer;
}
.searchInput:after {
    font-family: 'FontAwesome';
    color: #d4d4d4;
    position: relative;
    content: "\f002";
    right: 25px;
}

.dim_button {
    display: inline-block;
    color: #fff;
    text-decoration: none;
    text-transform: uppercase;
    text-align: center;
    padding-top: 6px;
    background: rgb(57, 85, 136);
    margin-right: 10px;
    position: relative;
    cursor: pointer;
    font-weight: 600;
    margin-bottom: 20px;
}
.createSegment a {
    margin-bottom: 0px;
    border-radius: 50px;
    background: #ffffff;
    border: 1px solid #007bff;
    color: #007bff;
    transition: all .4s ease;
}
.createSegment a:hover, .createSegment a:focus {
    transition: all .4s ease;
    background: #007bff;
    color: #fff;
}
.add_flex{
    display: flex;
    justify-content: flex-end;
    padding-right:0px;
}
.main-datatable .dataTable.no-footer {
    border-bottom: 1px solid #eee;
}
.main-datatable .cust-datatable thead {
    background-color: #f9f9f9;
}
.main-datatable .cust-datatable>thead>tr>th {
    border-bottom-width: 0;
    color: #443f3f;
    font-weight: 600;
    padding: 16px 15px;
    vertical-align: middle;
    padding-left: 18px;
    text-align: center;
}
.main-datatable .cust-datatable>tbody td {
    padding: 10px 15px 10px 18px;
    color: #333232;
    font-size: 13px;
    font-weight: 500;
    word-break: break-word;
    border-color: #eee;
    text-align: center;
    vertical-align: middle;
}
.main-datatable .cust-datatable>tbody tr {
    border-top: none;
}
.main-datatable .table > tbody > tr:nth-child(even) {
    background: #f9f9f9;
}
.btn-group.open .dropdown-toggle {
    box-shadow: none;
}
.main-datatable .dropdown_icon {
    display: inline-block;
    color: #8a8a8a;
    font-size: 12px;
    border: 1px solid #d4d4d4;
    padding: 10px 11px;
    border-radius: 50%;
    cursor: pointer;
}
.btn-group i{
    color: #8e8e8e;
    margin: 2px;
}
.main-datatable .actionCust a {
    display: inline-block;
    color: #8a8a8a;
    font-size: 12px;
    border: 1px solid #d4d4d4;
    padding: 10px 11px;
    margin: -9px 3px;
    border-radius: 50%;
    cursor: pointer;
}
.main-datatable .actionCust a i{
    color: #8e8e8e;
    margin: 2px;
}
.main-datatable .dropdown-menu {
    padding: 0;
    border-radius: 4px;
    box-shadow: 10px 10px 20px #c8c8c8;
    margin-top: 10px;
    left: -18px;
    top: 32px;
}
.main-datatable .dropdown-menu > li > a {
    display: block;
    padding: 12px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857;
    color: #333333;
    white-space: nowrap;
    border-bottom: 1px solid #d4d4d4;
}
.main-datatable .dropdown-menu > li > a:hover,
.main-datatable .dropdown-menu > li > a:focus {
    color: #fff;
    background: #007bff;
}
.main-datatable .dropdown-menu > li > a:hover i{
    color: #fff;
}
.main-datatable .dropdown-menu:before {
    position: absolute;
    top: -7px;
    left: 78px;
    display: inline-block;
    border-right: 7px solid transparent;
    border-bottom: 7px solid #d4d4d4;
    border-left: 7px solid transparent;
    border-bottom-color: #d4d4d4;
    content: '';
}
.main-datatable .dropdown-menu:after {
    position: absolute;
    top: -6px;
    left: 78px;
    display: inline-block;
    border-right: 6px solid transparent;
    border-bottom: 6px solid #ffffff;
    border-left: 6px solid transparent;
    content: '';
}
.dropdown-menu i {
    margin-right: 8px;
}
.main-datatable .dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #999999 !important;
    background-color: #f6f6f6 !important;
    border-color: #d4d4d4 !important;
    border-radius: 40px;
    margin: 5px 3px;
}
.main-datatable .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #fff !important;
    border: 1px solid #3d96f5 !important;
    background: #4da3ff !important;
    box-shadow: none;
}
.main-datatable .dataTables_wrapper .dataTables_paginate .paginate_button.current,
.main-datatable .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    color: #fff !important;
    border-color: transparent !important;
    background: #007bff !important;
}
.main-datatable .dataTables_paginate {
    padding-top: 0 !important;
    margin: 15px 10px;
    float: right !important;
}
.mode{
    padding:4px 10px;
    line-height: 13px;
    color:#fff;
    font-weight: 400;
    border-radius: 1rem;
    -webkit-border-radius: 1rem;
    -moz-border-radius: 1rem;
    -ms-border-radius: 1rem;
    -o-border-radius: 1rem;
    font-size:11px;
    letter-spacing: 0.4px;
}
.mode_on{
    background-color: #09922d;
}
.mode_off{
    background-color: #8b9096;
}
.mode_process{
    background-color: #ff8000;
}
.mode_done{
    background-color: #03a9f3;
}
@media only screen and (max-width:1200px){
    .overflow-x{
        overflow-x:scroll;
    }
    .overflow-x::-webkit-scrollbar{
        width:5px;
        height:6px;
    }
    .overflow-x::-webkit-scrollbar-thumb{
        background-color: #888;
    }
    .overflow-x::-webkit-scrollbar-track{
         background-color: #f1f1f1;
    }
    .profile-img{
    margin-right:5px;
}
td{
    width: 200px !important;
    text-align: left !important;
}
th{
   text-align: left !important;
   background: #00abe3;
color: white !important;
}


.profile-img img{
    width: 25px;
    border: 0.5px solid lightgray;
    border-radius: 50%;
}
}
.profile-img{
    margin-right:5px;
}
td{
    width: 200px !important;
    text-align: left !important;
}
th{
   text-align: left !important;
   background: #00abe3;
color: white !important;
}


.profile-img img{
    width: 25px;
    border: 0.5px solid lightgray;
    border-radius: 50%;
}
div#filtertable_filter {
    display: none;
}
div#filtertable_length {
    color: black;
    font-weight: 300 !important;
    margin: 11px;

}
div#filtertable_length label {
        font-weight: 100 !important;
    color: #333232;

}

div#filtertable_length label select {
    padding: 5px;
    border-radius: 20px;

}
div#filtertable_info {
    padding: 10px;
    margin-top: 7px;
}
.icon-img-dt img{
   width: 26px;
   padding-right: 5px;
}
.icon-img-prc img{
   width: 29px;
   padding-right: 1px;
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
        <span>All PO</span>
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
      		<!--	<div class="panel-heading"><h1>All PO</h1></div> -->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="poList.php" id="form1">

                     <div class="well list-top-controls">
                     <!-- <div class="row border">

                        <div class="col-sm-11 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div> -->

                              <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Products <i class="fa fa-angle-right"></i>All PO</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
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


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div>
                    <!-- Table -->
                    <div class="p-30">
        <div class="row">
            <div class="col-md-12 main-datatable">
                <div class="card_body">
                    <!-- <div class="row d-flex">
                        <!-- <div class="col-sm-4 createSegment">
                         <a class="btn dim_button create_new"> <span class="glyphicon glyphicon-plus"></span> Create New</a>
                        </div> -->
                        <!-- <div class="col-sm-8 add_flex">
                            <div class="form-group searchInput">
                                <label for="email">Search:</label>
                                <input type="search" class="form-control" id="filterbox" placeholder=" ">
                            </div>
                        </div>
                    </div> -->
                    <div class="overflow-x">
                        <table style="width:100%;" id="filtertable"  class=' table table-bordered table-hover dt-responsive dataTable'>
                            <thead>
                                <tr>
                                    <th style="min-width:150px;">Action</th>
                                    <th style="min-width:50px;">PO NO</th>
                                    <th style="min-width:150px;">Supplier</th>
                                    <th style="min-width:150px;">Order Date</th>
                                    <th style="min-width:150px;">Total Amount</th>
                                    <th style="min-width:100px;">Invoice Amount</th>
                                    <th style="min-width:100px;">Delivery Date</th>


                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    $(document).ready(function() {
    var dataTable = $('#filtertable').DataTable({
        processing: true,
		fixedHeader: true,
        serverSide: true,
        serverMethod: 'post',
		pageLength: 25,
		scrollX: true,
		scrollY: true,
		bScrollInfinite: true,
		bScrollCollapse: true,
		/*scrollY: 550,*/
		deferRender: true,
		scroller: true,
		"dom": "rtiplf",
        //"pageLength":1,
        'aoColumnDefs':[{
            'bSortable':false,
            'aTargets':['nosort'],
        }],
        'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=po'
                },
        //columnDefs:[
            //{type:'date-dd-mm-yyyy',aTargets:[5]}
        //],
        "columns":[
            { data: 'edit', "orderable": false  },
            { data: 'poid' },
            { data: 'name' },
            { data: 'orderdt' },
            { data: 'tot_amount' },
			{ data: 'invoice_amount' },
            { data: 'delivery_dt' },

        ],
        //"order":true,
        //"bLengthChange":true,
        //"dom":'<"top">ct<"top"p><"clear">'

    });

    table1.columns.adjust().draw();

    $("#filterbox").keyup(function(){
        dataTable.search(this.value).draw();
    });
} );

</script>

    </body></html>
  <?php } ?>
