<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
$mod = $_GET['mod'];

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {

    $fd1 = $_POST['from_dt'];
    $td1 = $_POST['to_dt'];

    //if($fd1==''){$fd1=date('d/m/Y', strtotime('-15 day'));}
    if ($td1 == '') {$td1 = date("d/m/Y");}
    // $fd1=date('d/m/Y', strtotime('-1 months',strtotime($td1)));
    if ($fd1 == '') {
        $today = substr($td1, 6, 4) . "-" . substr($td1, 3, 2) . "-" . substr($td1, 0, 2); //date('d/m/Y'); //date_format($td1,"Y-m-d");
        $fd1   = date('d/m/Y', strtotime('-6 month', strtotime($today)));
    }
    //echo "mn".$newdate;

//$fd = date('d/m/Y', strtotime('-15 day'));
    //echo $td;die; ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>
<body class="dashboard dashboard2">
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->
  <div id="sidebar-wrapper" class="mCustomScrollbar">

  <div class="section">
  	<i class="fa fa-group  icon"></i>
    <span>Customers</span>
  </div>
        <?php include_once 'menu.php'; ?>
	<div style="height:54px;">
	</div>

  </div>
  <!-- /#sidebar-wrapper -->

  <!-- Page Content -->
  <div id="page-content-wrapper">
    <div class="container-fluid pagetop">
      <div class="row">
        <div class="col-lg-12">

        <p>&nbsp;</p>
        <p>&nbsp;</p>

          <!--h1 class="page-title">Customers</a></h1-->
          <p>

<!--			 filter -->
<form method="post" action="dashboard_bill.php?mod=3" id="form1" enctype="multipart/form-data">
    <div class="row  dashbaord-filter b">
        <div class="col-lg-8 col-md-7 col-sm-12 ">
            <div class="row  dashbaord-filter">
                <div class="row dashboard-menu-row">
                    <!-- <div class="dashboard-menu-parent col-lg-3">
                     <a class="dashboard-menu"><i class="fa fa-users fa-stack-1x dashboard-menu-icon"> <span class="dashboard-menu-text">Add Customer</span></i></a>
                    </div> -->
                    <div class="dashboard-menu-parent col-lg-3">
                      <a href="./quotationEntry.php" class="dashboard-menu"><i class="fa fa-inbox fa-stack-1x dashboard-menu-icon"> <span class="dashboard-menu-text">Add Quotation</span></i></a>
                     </div>
                     <div class="dashboard-menu-parent col-lg-3">
                     <a href="./inv_soitem.php?pg=1&mod=3" class="dashboard-menu"><i class="fa fa-support fa-stack-1x dashboard-menu-icon"> <span class="dashboard-menu-text">Add Order</span></i></a>
                    </div>
                    <div class="dashboard-menu-parent col-lg-3">
                     <a href="./expense.php?pg=1&mod=3" class="dashboard-menu"><i class="fa fa-arrow-circle-right fa-stack-1x dashboard-menu-icon"> <span class="dashboard-menu-text">Add Expense</span></i></a>
                    </div>
                      <div class="dashboard-menu-parent col-lg-3">
                      <a href="./collection.php?res=0&mod=3" class="dashboard-menu"><i class="fa fa-arrow-circle-up fa-stack-1x dashboard-menu-icon"> <span class="dashboard-menu-text">Add Payment</span></i></a>
                     </div>


                </div>

                <!--div class="col-lg-4 col-md-4 col-sm-4 column">
					<div class="form-group">
					    <div class="form-group styled-select">
					        <select name="cmboutlet" id="cmboutlet" class="cmb-parent form-control" >
                                <option value="0">Outlet/Branch </option>
    <?php
$qry1    = "SELECT `id`, `name` FROM `branch`";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                    <option value="<?php echo $tid; ?>" <?php if ($cmbdiv == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
    <?php }} ?>
                            </select>
					    </div>
					</div>
				</div-->
                <!--div class="col-lg-4 col-md-4 col-sm-4  column">
					<div class="form-group">
					    <div class="form-group styled-select">
							<select name="cmbdept" id="cmbdept" class="cmd-child form-control" >
							    <option value="0">Department </option>
    <?php
$qry1    = "SELECT `id`, `name` FROM `department`";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                    <option value="<?php echo $tid; ?>" <?php if ($cmbdept == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
    <?php }} ?>
                            </select>
					    </div>
				    </div>
				</div -->
                <!--div class="col-lg-4 col-md-4 col-sm-4  column">
					<div class="form-group">
					    <div class="form-group styled-select">
							<select name="cmbdesg" id="cmbdesg" class="cmd-child1 form-control" >
                                <option value="0">Designation </option>
    <?php
$qry1    = "SELECT `id`, `name` FROM `designation`";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                    <option value="<?php echo $tid; ?>" <?php if ($cmbdesg == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
    <?php }} ?>

                            </select>
				        </div>
				    </div>
				</div -->
            </div>
        </div>
        <div class="col-lg-4 col-lg-offset-8 col-md-8 col-md-offset-4  col-sm-12">
            <div class="row  dashbaord-filter">
                <div class="col-lg-5 col-md-4 col-sm-4 column">

					<div class="input-group">
						<input type="text" class="form-control datepicker dt-input" id="from_dt" name="from_dt" value="<?php echo $fd1; ?>" >
						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
					</div>

				</div>
                <div class="col-lg-5 col-md-4 col-sm-4  column">
					<div class="input-group">
						<input type="text" class="form-control datepicker dt-input" id="to_dt" name="to_dt" value="<?php echo $td1; ?>">
						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
					</div>
				</div>
                <div class="col-lg-2 col-md-4 col-md-4 col-sm-4  column">
					<div class="input-group">
						<input type="submit" class="btn btn-xs form-control"  name="view"  id="view"  value="Filter">
						<!--div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div-->
					</div>
				</div>
			</div>
        </div>
    </div>
<!--	<hr class="hr-db-filter">	-->
</form>
<?php

    $qry1 = "select sum(b.otc*b.qty) inv,sum(a.dueamount)due,sum(a.paidamount)paid,sum(b.cost*b.qty) cost
#sum(invoiceamt) inv, sum(paidamount) paid,sum(dueamount) due 
from invoice a left join soitemdetails b on a.soid=b.socode
 where ( date( `invoicedt`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y')) ";
//( date( `invoicedt`) between STR_TO_DATE('".$fd1."','%d/%m/%Y') and STR_TO_DATE('".$td1."','%d/%m/%Y'))
    //and (s.distrinct=".$cmbdis." or ".$cmbdis." =0)
    //and (s.division=".$cmbdiv." or ".$cmbdiv." =0) and (s.thana=".$cmbthana." or ".$cmbthana." =0)   group by d.sensor";
    //echo $qry1; die;
    $result1 = $conn->query($qry1);
    if ($result1->num_rows > 0) {while ($rows1 = $result1->fetch_assoc()) {$inv = $rows1["inv"];
        $paid                           = $rows1["paid"];
        $due                            = $rows1["due"];
        $cost                            = $rows1["cost"];
        $margin=$inv-$cost;
    }}

    $qry2 = "select sum(`amount`) exp from expense where ( date( `trdt`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y'))";
//( date( `trdt`) between STR_TO_DATE('".$fd1."','%d/%m/%Y') and STR_TO_DATE('".$td1."','%d/%m/%Y'))
    $result2 = $conn->query($qry2);
    if ($result2->num_rows > 0) {while ($rows2 = $result2->fetch_assoc()) {$exp = $rows2["exp"];}}

    $qry3 = "select sum(invoiceamount) soamt  from soitem where ( date( `orderdate`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y'))";
//( date( `orderdate`) between STR_TO_DATE('".$fd1."','%d/%m/%Y') and STR_TO_DATE('".$td1."','%d/%m/%Y'))
    $result3 = $conn->query($qry3);
    if ($result3->num_rows > 0) {while ($rows3 = $result3->fetch_assoc()) {$soamt = $rows3["soamt"];}}

    ?>


<!--6 widgets box-->

    <div class="row  dashbaord-filter b">
        <div class="col-lg-6 col-md-12">
            <div class="row  dashbaord-filter">
                <div class="col-lg-4 col-md-4 col-sm-4 column">
                  <!-- START widget-->
                    <div class="panel widget widget2 bg-white shadow">
                        <div class="row row-table">
                            <div class="col-xs-8 pv-lg">
						        <div class="title">Revenue</div>
                                <div class="h2 mt5"><?php echo number_format($inv, 0); ?></div>
                            </div>
                        </div>
                    </div>
			    </div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                    <div class="panel widget widget2 bg-white">
                        <div class="row row-table">
                            <div class="col-xs-8 pv-lg">
						        <div class="title">Total Due</div>
                                <div class="h2 mt5"><?php echo number_format($due, 0); ?></div>
                            </div>
                        </div>
                    </div>
			    </div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                    <div class="panel widget widget2 bg-white">
                        <div class="row row-table">
                            <div class="col-xs-8 pv-lg">
						        <div class="title">Total Cost </div>
                                <div class="h2 mt5"><?php echo number_format($cost, 0); ?></div>
                            </div>
                        </div>
                    </div>
			    </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="row  dashbaord-filter">
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                    <div class="panel widget widget2 bg-white">
                        <div class="row row-table">
                            <div class="col-xs-8 pv-lg">
						        <div class="title">Margin </div>
                                <div class="h2 mt5"><?php echo number_format($margin, 0); ?></div>
                            </div>
                        </div>
                    </div>
			    </div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                    <div class="panel widget widget2 bg-white">
                        <div class="row row-table">
                            <div class="col-xs-8 pv-lg">
						        <div class="title">Total Expense</div>
                                <div class="h2 mt5"><?php echo number_format($exp, 0); ?></div>
                            </div>
                        </div>
                    </div>
			    </div>
                
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                    <div class="panel widget widget2 bg-white">
                        <div class="row row-table">
                            <div class="col-xs-8 pv-lg">
						        <div class="title">Net Profit</div>
                                <div class="h2 mt5"><?php echo number_format(($margin - $exp), 0); ?></div>
                            </div>
                        </div>
                    </div>
			    </div>
            </div>
        </div>
    </div>

<!-- END widgets box-->
              <!-- START chart row-->

               <!-- START row-->
                <div class="row  dashbaord-filter">
                    
                    <div class="col-lg-12  col-md-12">
                        <div id="panelChart5" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                                <div class="panel-title">Revenue(k)</div>
                            </div>
                            <div class="panel-body">
                                <div class="chart-pos0 flot-chart"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4  col-md-6">
                        <div id="panelChart5" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                                <div class="panel-title">Invoice Vs Collection</div>
                            </div>
                            <div class="panel-body">
                                <div class="chart-bill1 flot-chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div id="panelChart6" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                               <div class="panel-title">Monthly Collection</div>
                            </div>
                            <div class="panel-body">
                               <div class="chart-bill2 flot-chart"></div>
                            </div>
                        </div>
                    </div>
    				<div class="col-lg-4 col-md-6">
                        <div id="panelChart7" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                                <div class="panel-title">Sales Revenue Monthly</div>
                            </div>
                            <div class="panel-body">
                                <div class="indicator show">
                                    <span class="spinner"></span>
                                </div>
                                <div class="chart-bill3 flot-chart"></div>
                            </div>
                        </div>
                    </div>

    				<div class="col-lg-4 col-md-6">
                        <div id="panelChart8" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                                <div class="panel-title">Cost Catagory Wise Expense(k)</div>
                            </div>
                            <div class="panel-body">
                                <div class="indicator show">
                                    <span class="spinner"></span>
                                </div>
                                <div class="chart-bill4 flot-chart"></div>
                            </div>
                        </div>
                  </div>
    				<div class="col-lg-4 col-md-6">
                        <div id="panelChart3" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                                <div class="panel-title">Monthly net Profit</div>
                            </div>
                            <div class="panel-body">
                                <div class="indicator show">
                                    <span class="spinner"></span>
                                </div>
                                <div class="chart-bill5 flot-chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4  col-md-6">
                        <div id="panelChart5" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                               <div class="panel-title">Customer wise Revenue(k)</div>
                            </div>
                            <div class="panel-body">
                               <div class="chart-bill6 flot-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>

               </div>
               <!-- END  chart row-->

          <!-- START PLACING YOUR CONTENT HERE -->
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /#page-content-wrapper -->


<?php include_once 'common_footer.php'; ?>
<!-- end Date Picker  ==================================== -->

<?php include_once 'inc_chart_dash.php'; ?>
  <!-- END FLOT CHART-->


<?php } ?>
<script>
       $('.datepicker').datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "DD/MM/YYYY",
					//format: 'LT',
					//keepOpen:true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });
   </script>
</body></html>
