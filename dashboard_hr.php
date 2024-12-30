<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];

if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
	
    $currSection = 'dashboard_hr';
    $currPage = basename($_SERVER['PHP_SELF']);
	
$cmboutlet=$_POST['cmboutlet'];
$cmbdept=$_POST['cmbdept'];
$cmbdesg=$_POST['cmbdesg'];
$cmbdiv=$_POST['cmbdiv'];

$cusid=$cmbdis;
$fd1=$_POST['from_dt'];
$td1=$_POST['to_dt'];

    if($cmbdept==''){$cmbdept='0';}
    if($cmbthana==''){$cmbthana='0';}
    if($cmbdesg==''){$cmbdesg='0';}
    if($cmbdiv==''){$cmbdiv='0';}
    
    //if($fd1==''){$fd1=date('d/m/Y', strtotime('-15 day'));}
    if($td1==''){$td1=date("d/m/Y");}
   // $fd1=date('d/m/Y', strtotime('-1 months',strtotime($td1)));
   
    $today =substr($td1,6,4)."-".substr($td1,3,2)."-".substr($td1,0,2);  ;//date('d/m/Y'); //date_format($td1,"Y-m-d"); 
    
    $fd1 = date('d/m/Y', strtotime('-15 day', strtotime($today)));
    
    $hd1=date('d/m/Y', strtotime('-1 month'));
    if($hd1>$fd1){$tbl="view_daily_log_hist";}else{$tbl="viewdailylog";}
    //echo "mn".$newdate;
    $td=date("d/m/Y");
//$fd = date('d/m/Y', strtotime('-15 day')); 
//echo $td;die;
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<body class="dashboard dashboard2">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
  <div id="sidebar-wrapper" class="mCustomScrollbar">
  
  <div class="section">
  	<i class="fa fa-group  icon"></i>
    <span>Customers</span>
  </div>
        <?php    include_once('menu.php');    ?>
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
<form method="post" action="dashboard_hr.php?mod=4" id="form1" enctype="multipart/form-data"> 			  
    <div class="row  dashbaord-filter b">
        <div class="col-lg-8 col-lg-offset-4  col-md-7 col-sm-12 "  style="border:0px solid #000;">
        
            <div class="row  dashbaord-filter">
                <div class="col-lg-3   col-md-4 col-sm-4 column">
					<div class="form-group">
					    <div class="form-group styled-select">
					        <select name="cmboutlet" id="cmboutlet" class="cmb-parent form-control" >
                                <option value="0">Outlet/Branch </option>
    <?php 
    $qry1="SELECT `id`, `name` FROM `branch`";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
      $tid= $row1["id"];  $nm=$row1["name"]; 
    ?>          
                                    <option value="<?php echo $tid; ?>" <?php if ($cmbdiv == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php }}?>                    
                            </select>
					    </div>
					</div>					
				</div>
                <div class="col-lg-3 col-md-4 col-sm-4  column">
					<div class="form-group">
					    <div class="form-group styled-select">
							<select name="cmbdept" id="cmbdept" class="cmd-child form-control" >
							    <option value="0">Department </option>
    <?php 
    $qry1="SELECT `id`, `name` FROM `department`";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
      $tid= $row1["id"];  $nm=$row1["name"]; 
    ?>          
                                    <option value="<?php echo $tid; ?>" <?php if ($cmbdept == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php }}?>                              
                            </select>
					    </div>
				    </div>				
				</div>
                <div class="col-lg-3 col-md-4 col-sm-4  column">
					<div class="form-group">
					    <div class="form-group styled-select">
							<select name="cmbdesg" id="cmbdesg" class="cmd-child1 form-control" >
                                <option value="0">Designation </option>
    <?php 
    $qry1="SELECT `id`, `name` FROM `designation`";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
      $tid= $row1["id"];  $nm=$row1["name"]; 
    ?>          
                                    <option value="<?php echo $tid; ?>" <?php if ($cmbdesg == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php }}?>                              
                            
                            </select>
				        </div>
				    </div>
				</div>
                <div class="col-lg-3 col-md-6 col-sm-6  column">
					<div class="input-group">
						<input type="submit" class="btn btn-xs form-control"  name="view"  id="view"  value="Filter">
						
					</div>	
				</div>              
                
            </div>
        </div>

    </div>			  
<!--	<hr class="hr-db-filter">	-->
</form>	
<?php 
   
	$qry1="select tot,lv,att FROM (
        (SELECT count(*) tot  FROM `employee` e left join hraction ha on ha.hrid=e.id where  (ha.postingdepartment=".$cmbdept." or ".$cmbdept." =0) and (ha.designation=".$cmbdesg." or ".$cmbdesg." =0)) tot,
        (select count( distinct l.hrid) lv  from `leave` l left join hr on l.hrid=hr.id
left join employee e on hr.emp_id=e.employeecode
left join hraction ha on ha.hrid=e.id where ('2021-08-01' BETWEEN `startday` and `endday`) and (ha.postingdepartment=".$cmbdept." or ".$cmbdept." =0) and (ha.designation=".$cmbdesg." or ".$cmbdesg." =0)) lv,
        (select count(distinct a.hrid ) att from attendance a left join hr h on a.hrid=h.id left join employee e on h.emp_id=e.employeecode
left join hraction ha on ha.hrid=e.id where  date ='2021-08-01' and (ha.postingdepartment=".$cmbdept." or ".$cmbdept." =0) and (ha.designation=".$cmbdesg." or ".$cmbdesg." =0) ) att 
         )";
//( date(d.logdt) between STR_TO_DATE('".$fd1."','%d/%m/%Y') and STR_TO_DATE('".$td1."','%d/%m/%Y'))
//and (s.distrinct=".$cmbdis." or ".$cmbdis." =0) 
//and (s.division=".$cmbdiv." or ".$cmbdiv." =0) and (s.thana=".$cmbthana." or ".$cmbthana." =0)   group by d.sensor";
//echo $qry1; die;
				$result1= $conn->query($qry1);
				if ($result1->num_rows > 0){
					 while($rows1 = $result1->fetch_assoc()){ 
				        $tot=$rows1["tot"];$lv=$rows1["lv"];$att=$rows1["att"];
					 }
				    
				}
					 
$hire=0;
$left=0;
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
						        <div class="title">Total Employee</div>
                                <div class="h2 mt5"><?php echo $tot;?></div>
                            </div>
                        </div>
                    </div>
			    </div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                    <div class="panel widget widget2 bg-white">
                        <div class="row row-table">
                            <div class="col-xs-8 pv-lg">
						        <div class="title">Todays Present</div>
                                <div class="h2 mt5"><?php echo $att;?></div>
                            </div>
                        </div>
                    </div>			
			    </div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                    <div class="panel widget widget2 bg-white">
                        <div class="row row-table">
                            <div class="col-xs-8 pv-lg">
						        <div class="title">Today in Leave </div>
                                <div class="h2 mt5"><?php echo $lv;?></div>
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
						        <div class="title">Absent</div>
                                <div class="h2 mt5"><?php echo $tot-$att-$lv;?></div>
                            </div>
                        </div>
                    </div>
			    </div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                    <div class="panel widget widget2 bg-white">
                        <div class="row row-table">
                            <div class="col-xs-8 pv-lg">
						        <div class="title">Hired this Month</div>
                                <div class="h2 mt5"><?php echo $hire;?></div>
                            </div>
                        </div>
                    </div>
			    </div>
                <div class="col-lg-4 col-md-4 col-sm-4  column">
                  <!-- START widget-->
                    <div class="panel widget widget2 bg-white">
                        <div class="row row-table">
                            <div class="col-xs-8 pv-lg">
						        <div class="title">left This Month</div>
                                <div class="h2 mt5"><?php echo $left;?></div>
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
                    <div class="col-lg-4  col-md-6">
                        <div id="panelChart5" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                                <div class="panel-title">Employee Status</div>
                            </div>
                            <div class="panel-body">
                                <div class="chart-hr1 flot-chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div id="panelChart6" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                               <div class="panel-title">Attendance</div>
                            </div>
                            <div class="panel-body">
                               <div class="chart-hr2 flot-chart"></div>
                            </div>
                        </div>
                    </div>
    				<div class="col-lg-4 col-md-6">
                        <div id="panelChart7" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                                <div class="panel-title">Leave(v)</div>
                            </div>
                            <div class="panel-body">
                                <div class="indicator show">
                                    <span class="spinner"></span>
                                </div>
                                <div class="chart-hr3 flot-chart"></div>
                            </div>
                        </div>
                    </div>
    				
    				<div class="col-lg-4 col-md-6">
                        <div id="panelChart8" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                                <div class="panel-title">Department Wise Employee</div>
                            </div>
                            <div class="panel-body">
                                <div class="indicator show">
                                    <span class="spinner"></span>
                                </div>
                                <div class="chart-hr4 flot-chart"></div>
                            </div>
                        </div>
                  </div>
    				<div class="col-lg-4 col-md-6">
                        <div id="panelChart3" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                                <div class="panel-title">Gender Wise Employee</div>
                            </div>
                            <div class="panel-body">
                                <div class="indicator show">
                                    <span class="spinner"></span>
                                </div>
                                <div class="chart-hr5 flot-chart"></div>
                            </div> 
                        </div>
                    </div>
                    <div class="col-lg-4  col-md-6">
                        <div id="panelChart5" class="panel panel-default chart-wrapper">
                            <div class="panel-heading">
                               <div class="panel-title">Designation wise Employee</div>
                            </div>
                            <div class="panel-body">
                               <div class="chart-hr6 flot-chart"></div>
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


<?php    include_once('common_footer.php'); ?>
<!-- end Date Picker  ==================================== -->	

<?php    include_once('inc_chart_dash.php');?>
  <!-- END FLOT CHART--> 


<?php }?>

</body></html>
