<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>

<style>
.chart-bar-horz{
   width: calc(100% - 20px);
    padding-top: 0px;
}

.dashbaord-filter .panel-body{
    padding: 10px;
}

.dashbaord-filter .panel-title{
    padding-left: 10px;
    padding-top: 3px;
}


</style>

<body class="dashboard dashboard2">


<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid nav-left-padding">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="qv.php"><img src="images/logo-bitcables.png" alt="bitcables"></a> </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active" > &nbsp;
          <button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="fa fa-navicon" aria-hidden="true"></span></button>
        </li>
        <?php 
				$qrysb="SELECT  distinct d.`id`, d.`Name`, d.`sl`,d.`landport` FROM  `mainMenu` m,hrAuth a,module d  WHERE a.menuid=m.`id` and ifnull(m.isreport,0)<>1 and a.hrid=".$usr." and m.modl=d.id
 order by d.sl"; 
				$resultsb= $conn->query($qrysb);
				if ($resultsb->num_rows > 0){
					 while($rowsb = $resultsb->fetch_assoc()){ $mnsl=$rowsb["sl"]; $slnm=$rowsb["Name"]; $url1=$rowsb["landport"]."?mod=".$rowsb["id"]; ?>
        <li <?php if ($mod==$rowsb["id"]){ ?> class="active" <?php }?>><a href=<?php echo $url1;?>><?php echo $slnm;?> </a></li>
		 <?php 			 }
				}?>
      </ul>
      <ul class="nav navbar-nav navbar-right user-menu">
        <li><a href="../navbar/"><span class="fa fa-gear"></span> Setting</a></li>
        
        <li class="dropdown"> <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user-circle-o"></span> <span class="caret"></span> </a>
          <ul class="dropdown-menu">
           <!-- <li><a href="#">Account</a></li>-->
            <li><a href="hc_char_modi.php?mod=5">Change Password</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="hr.php?res=2">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!--/.nav-collapse --> 
  </div>
</nav>
<!-- Fixed navbar -->



<div id="wrapper"> 
  <!-- Sidebar -->

  <div id="sidebar-wrapper" class="mCustomScrollbar">
  
  <div class="section">
  	<i class="fa fa-group  icon"></i>
    <span>Customers</span>
  </div>
  
   <?php
    include_once('menu.php');
    ?>
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
			  
          <!-- START PLACING YOUR CONTENT HERE -->

			  
			  
<!--			 filter -->


			  
			  
    <div class="row  dashbaord-filter b">
        <div class="col-lg-7 col-md-7 col-sm-12 ">
            <div class="row  dashbaord-filter">
                <div class="col-lg-4 col-md-4 col-sm-4 column">
						<div class="form-group">
						  <div class="form-group styled-select">
							<select name="cmbindtype" id="cmbindtype" class="form-control" required="">
							  <option value="">Company Type</option>
							  <option value="8">Airlines</option>
							  <option value="63">Architectural firm</option>
							  <option value="19">Audit</option>
							  <option value="6">Banking</option>
							  <option value="36">BPO and Consultancy</option>
							  <option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>
							</select>
						  </div>
						</div>					
				</div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
						<div class="form-group">
						  <div class="form-group styled-select">
							<select name="cmbindtype" id="cmbindtype" class="form-control" required="">
							  <option value="">Building Type</option>
							  <option value="8">Airlines</option>
							  <option value="63">Architectural firm</option>
							  <option value="19">Audit</option>
							  <option value="6">Banking</option>
							  <option value="36">BPO and Consultancy</option>
							  <option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>
							</select>
						  </div>
						</div>				
				</div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">

						<div class="form-group">
						  <div class="form-group styled-select">
							<select name="cmbindtype" id="cmbindtype" class="form-control" required="">
							  <option value="">New VS Existing </option>
							  <option value="8">Airlines</option>
							  <option value="63">Architectural firm</option>
							  <option value="19">Audit</option>
							  <option value="6">Banking</option>
							  <option value="36">BPO and Consultancy</option>
							  <option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>
							</select>
						  </div>
						</div>					
				
				</div>
            </div>
        </div>
        <div class="col-lg-5 col-md-5  col-sm-12">
            <div class="row  dashbaord-filter">
                <div class="col-lg-5 col-md-6 col-sm-6 column">
				
					<div class="input-group">
						<input type="text" class="form-control datepicker dt-input" id="chqdt" name="chqdt1" value="">
						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
					</div>				
					
				</div>
                <div class="col-lg-5 col-md-6 col-sm-6  column">
					<div class="input-group">
						<input type="text" class="form-control datepicker dt-input" id="chqdt2" name="chqdt" value="">
						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
					</div>	
				</div>
                <div class="col-lg-2 col-md-6 col-sm-6  column">
					<div class="input-group">
						<input type="button" class="btn btn-xs form-control"   value="Filter">
						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
					</div>	
				</div>
            </div>
        </div>
    </div>			  
<!--	<hr class="hr-db-filter">	-->
	
			
<?php 

	$qrychrt1="select count(* ) flt ,sum(`employeesize`) mem from organization"; 
				$resultchrt1= $conn->query($qrychrt1);
				if ($resultchrt1->num_rows > 0){
					 while($rowschrt1 = $resultchrt1->fetch_assoc()){ $flt=$rowschrt1["flt"]; $mem=$rowschrt1["mem"];}}
					 
	$qrychrt2="
select COUNT(o.name) occpy,sum(i.invoiceamt) inv,sum(p.amount) col from invoice i left join invoicepayment p on i.invoiceno=p.invoicid, organization o where  i.organization=o.id"; 
				$resultchrt2= $conn->query($qrychrt2);
				if ($resultchrt2->num_rows > 0){
					 while($rowschrt2 = $resultchrt2->fetch_assoc()){ $occpy=$rowschrt2["occpy"]; $rev=$rowschrt2["inv"]; $col=$rowschrt2["col"]; }}
					 
					 
	$qrychrt3="SELECT (case when s.status =6 or terminationDate<=sysdate() then count(socode) else 0 end ) termin,(case when s.status !=6 or terminationDate>sysdate() then count(socode) else 0 end ) activ FROM soitem  s"; 
				$resultchrt3= $conn->query($qrychrt3);
				if ($resultchrt3->num_rows > 0){
					 while($rowschrt3 = $resultchrt3->fetch_assoc()){ $activ=$rowschrt3["activ"]; $ter=$rowschrt3["termin"]; }}					 
					 
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
						   <div class="title">Customer</div>
                           <div class="h2 mt5"><?php echo $flt;?> </div>
                        </div>
                     </div>
                  </div>
				</div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                  <div class="panel widget widget2 bg-white">
                     <div class="row row-table">
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Service Order</div>
                           <div class="h2 mt5"><?php echo $activ+$ter;?></div>
                        </div>
                     </div>
                  </div>			
				</div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                  <div class="panel widget widget2 bg-white">
                     <div class="row row-table">
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Collection</div>
                           <div class="h2 mt5"><?php echo number_format($col,0);?></div>
                        </div>
                     </div>
                  </div>			
				</div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="row  dashbaord-filter">
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <div class="panel widget widget2 bg-white">
                     <div class="row row-table">
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Total Revenue</div>
                           <div class="h2 mt5"><?php echo number_format($rev,0);?></div>
                        </div>
                     </div>
                  </div>
				</div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                  <div class="panel widget widget2 bg-white">
                     <div class="row row-table">
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Total Dues</div>
                           <div class="h2 mt5"><?php echo number_format($rev-$col,0);?></div>
                        </div>
                     </div>
                  </div>
				</div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                  <div class="panel widget widget2 bg-white">
                     <div class="row row-table">
                        <div class="col-xs-8 pv-lg">
						   <div class="title">Termination</div>
                           <div class="h2 mt5"><?php echo $ter;?></div>
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
                  
				   
                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart3" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Collection VS Revenue</div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-bar-horz flot-chart"></div>
                        </div>
                     </div>
                  </div>
				   

                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart5" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">License Wise Revenue</div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-pie flot-chart"></div>
                        </div>
                     </div>
                  </div>
				   
				   
				   
                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart6" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">New Vs Existing Revenue
                              <small></small>
                           </div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-donut flot-chart"></div>
                        </div>
                     </div>
                  </div>
				  <div class="col-lg-4 col-md-6">
                     <div id="panelChart7" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Collection Vs Revenue Monthly</div>
                        </div>
                        <div class="panel-body">
                           <div class="indicator show">
                              <span class="spinner"></span>
                           </div>
                           <div class="chart-bar-stacked flot-chart"></div>
                        </div>
                     </div>
                  </div> 

				  <div class="col-lg-4  col-md-6">
                     <div id="panelChart5" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Account Manager Wise Revenue</div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-pie2 flot-chart"></div>
                        </div>
                     </div>
                  </div> 
				  <div class="col-lg-4 col-md-6 col-sm-6">
                     <div id="panelChart3" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Product Wise Revenue</div>
                        </div>
                        <div class="panel-body">
                           <div class="indicator show">
                              <span class="spinner"></span>
                           </div>
                           <div class="chart-bar-horz1 flot-chart"></div>
                        </div>
                     </div>
                  </div>	
				   
                 <!-- <div class="col-lg-4  col-md-6">
                     <div id="panelChart5" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Package wise Customer Count for Current Month</div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-pie2 flot-chart"></div>
                        </div>
                     </div>
                  </div> -->
				   
                  <!--<div class="col-lg-12  col-md-12">
                     <div id="panelChart5" class="panel panel-default chart-wrapper">
                        <div class="panel-heading">
                           <div class="panel-title">Line Chart</div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-line flot-chart"></div>
                        </div>
                     </div>
                  </div> -->  
                                 
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


<?php    include_once('common_footer.php');?>
<?php    include_once('inc_chart_dash.php');?>
</body></html>

<?php
}
?>
