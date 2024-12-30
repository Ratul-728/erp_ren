<?php
require "common/conn.php";
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
opcache_reset();

session_start();

//ini_set('display_errors',1);
$usr=$_SESSION["user"];

//echo $usr;die;

$res= $_GET['res'];
$msg= $_GET['msg'];


$emp_att_id = $_POST["cmbempnm"];
//echo $emp_att_id;die;
$cmbmonth = $_POST["cmbmonth"];
$year = $_POST["cmbyr"];

if($emp_att_id != null){
    
    $attendance_from_string = $_POST["attendance_from"];
    
    // Create a DateTime object from the given date string
    $attendance_from = DateTime::createFromFormat('d/m/Y', $attendance_from_string);
    
    // Format the date as yyyy-mm-dd
    $attendance_from = $attendance_from->format('Y-m-d');
    
    $attendance_to_string   = $_POST["attendance_to"];
    
    // Create a DateTime object from the given date string
    $attendance_to = DateTime::createFromFormat('d/m/Y', $attendance_to_string);
    
    // Format the date as yyyy-mm-dd
    $attendance_to = $attendance_to->format('Y-m-d');
}



if($usr=='')
{ 	header("Location: ".$hostpath."/hr.php");
}
else
{
	

    $currSection = 'attendance_report_per_emp';
	// load session privilege;
	//include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);
    

   
    
    
    ?>
    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php
     include_once('common_header.php');
    ?>
    <style>

        h1.report-title{
            font-family: roboto;
            text-transform: uppercase;
            font-size: 25px;
            margin-top: 10px;
            margin-bottom: 0px;
        }
        .attn-table-header{
            border: 1px solid #bbb9b9;
        }
        
        .attn-table-top{
            background-color: rgba(236,236,236,0.37);
            padding: 10px;
            margin-bottom:10px;
        }
        
        .attn-table-header td{
            padding: 10px;
            font-size: 13px;
        }
        .attn-table.table th{
            background-color: var(--theme)!important;
            color:#ffffff!important;
        }
        
        .attn-table th:nth-child(1){ text-align: center; width: 60px;white-space: nowrap;}
        .attn-table.table td:nth-child(8){ width: 60px; padding-left: 5px!important;}
        
        .attn-table th:nth-child(8)
        { width: 250px; white-space: nowrap;}
        
        .attn-table td:nth-child(1){ text-align: center;}
        
        .attn-table td{border-bottom:1px solid #dbd9d9!important;}
    
    .icheck-primary{
    margin-bottom: 0!important;
}
    
.row-striped:nth-of-type(odd){
  background-color: #efefef;
}

.row-striped:nth-of-type(even){
  background-color: #ffffff;
}
    .row-striped input[readonly]{
    background-color:#ffffff;
}

    .attn-table th{
    white-space: nowrap;
}
    </style>
    <!-- Select2 CSS -->
<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />

<!-- Include Toastr CSS -->
<link href="js/plugins/toastr/toastr.min.css" rel="stylesheet">

<style>

.toast-top-right {
  top: 60px !important; /* Adjust this value as needed */
}
#toast-container > div {
  /*opacity: 1 !important;*/
}


/* Override Toastr default styles */
#toast-container {
  right: 10px;
}

/* Animations */
.toast {
  animation: slideInRight 0.5s, fadeOut 1s; /* Use desired durations */
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}


.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 34px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b{
    border: 0;
}

.select2-container--default .select2-selection {
  background-color: transparent;
  border: 0px solid #aaa!important;
  border-radius: 0px;
  cursor: text;
}

.select2-container .select2-selection {
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  min-height: 38px;
  user-select: none;
  -webkit-user-select: none;
}


.select2-container--default .select2-selection .select2-selection__choice {
  background-color: #e4e4e4;
  border: 1px solid #dbdbdb;
  border-radius: 2px;

  padding: 3px;
  padding-left: 0px;
  padding-left: 30px;
  font-size: 14px;
}

.select2-container--default .select2-selection .select2-selection__choice__remove {
  padding: 3px 8px;
}
    
    
.select2-container{
  width:102%!important;
    padding: 0;margin: 0;
}    
</style>

//Status color
<style>

    body { -webkit-print-color-adjust: exact; } 
    .absent {
      color: red;
    }
    .delay {
      color: orange;
    }
    .leave {
      color: brown;
    }
    .early {
      color: olive;
    }
     .offday {
      color: purple;
    }
    .holyday {
      color: teal;
    }
    
    
  
    
</style>

    <style>
        /* Screen Styles */

        .status span {
            display:inline-block;
            padding: 5px;
            border-radius: 3px;
            color: #fff!important;
            font-size: 13px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        } 

        .status.present span {
            background-color: rgba(87, 190, 96, 0.97)!important;
        }
        .status.absent span {
            background-color: #ec4540!important;
        }
        .status.delay span {
            background-color: rgba(236, 132, 27, 0.97)!important;
        }
        .status.leave span {
            background-color: rgba(27, 236, 168, 0.97)!important;
        }
        .status.earlyexit span {
            background-color: rgba(71, 170, 197, 0.97)!important;
        }
        .status.offday span {
            background-color: rgba(112, 121, 180, 0.97)!important;
        }
        .status.holiday span {
            background-color: rgba(153, 154, 164, 0.97)!important;
        }

        /* Print-specific Styles */
        @media print {
            
            .status span {
                display:inline-block;
                padding: 5px;
                border-radius: 3px;
                color: #fff!important;
                font-size: 13px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;            
            }            
            .status.present span {
                background-color: rgba(87, 190, 96, 0.97)!important;
            }
            .status.absent span {
                background-color: #ec4540!important;
                color: #fff !important;
            }
            .status.delay span {
                background-color: rgba(236, 132, 27, 0.97)!important;
            }
            .status.leave span {
                background-color: rgba(27, 236, 168, 0.97)!important;
            }
            .status.earlyexit span {
                background-color: rgba(71, 170, 197, 0.97)!important;
            }
            .status.offday span {
                background-color: rgba(112, 121, 180, 0.97)!important;
            }
            .status.holiday span {
                background-color: rgba(153, 154, 164, 0.97)!important;
            }
        }
    </style>

    <body class="list">
        
    <?php
     include_once('common_top_body.php');
    ?>
    
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>HRM</span>
      </div>
      
        <?php
            include_once('menu.php');
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
      			<!--<div class="panel-heading"><h1>All Service Order(Item)</h1></div>-->
    				<div class="panel-body">
    
        <span class="alertmsg">
        </span>
    
    
 
                	<form method="post" action="attendance_report_per_emp.php?mod=4" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                          
                                    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="email">Attendance From<span class="redstar">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="attendance_from" name="attendance_from" value="<?php echo $attendance_from_string;?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="email">Attendance To<span class="redstar">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="attendance_to" name="attendance_to" value="<?php echo $attendance_to_string;?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="email">Employee Name </label>
                                            <div class="form-group">
                                                <div class="form-group styled-select">
                                                <select name="cmbempnm" id="cmbempnm" class="select2basic form-control" >
                                                    <option value="">Select User</option>
    <?php 
    $qry1="SELECT concat(emp.firstname, ' ', emp.lastname, '-(', emp.employeecode, ')') empnm, h.attendance_id, emp.id FROM `employee` emp LEFT JOIN hr h ON h.emp_id=emp.employeecode where emp.active_st='A' and h.user_tp=2 and h.active_st=1 ORDER by emp.firstname";
	$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
    {while($row1 = $result1->fetch_assoc()) 
          {   $tid= $row1["attendance_id"];  $nm=$row1["empnm"]; 
    ?>  
													
                                                    <option value="<? echo $tid; ?>" <? if ($emp_att_id == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php 
          }
    }      
    ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                              <label for="email"> </label>
                                            <div class="form-group">
                                                <input class="btn btn-lg btn-default" type="submit" name="psp" value="Search"  id="psp" > 
                                            </div>
                                        </div>
                         
                          
                       <!--div class="col-sm-3 text-nowrap">
                            <h6>HRM <i class="fa fa-angle-right"></i>Report<i class="fa fa-angle-right"></i>Attendance Report</h6>
                       </div-->
                     
                        
                        
                      </div>
                    </div>
                    
    
    				</form>

            <?php if($emp_att_id != ''){ ?>            
            
			 <div class="row" id="printArea">
			     
				<div class="col-sm-12">
				    <div class="text-center attn-table-top">
				        <h1 class="report-title">Renaissance Decor Ltd</h1>
				        <table border="0" width="100%">
				            <tr>
				                <!--td width="15%">
				                    &nbsp;
				                </td--->
				                <td width="70%">
				                    <span>Ashfia Tower, Level#4, Plot#76, road#11, Block-E, Banani.</span>
				                    <h4>From DATE: <?= $attendance_from ?>  To DATE: <?= $attendance_to ?></h4>
				                </td>
				                
				            </tr>
				        </table>
				    </div>
				    
			        <table border="0" width="100%" class="attn-table-header">
			<?php $qryInfo = "SELECT des.name des, dep.name dep, ja.Title jobarea, emp.employeecode, concat(emp.firstname, ' ', emp.lastname) empnm, emp.tin, emp.nid,
			                  DATE_FORMAT(emp.dob, '%d/%b/%Y') dob, emp.photo, h.id hrid,emp.id empid
			                  FROM `hraction` a LEFT JOIN employee emp ON emp.id = a.`hrid` LEFT JOIN hr h ON emp.employeecode=h.emp_id 
			                  LEFT JOIN designation des ON a.`designation`= des.id LEFT JOIN department dep ON dep.id = a.`postingdepartment` 
			                  LEFT JOIN JobArea ja ON a.jobarea=ja.ID where h.attendance_id ='$emp_att_id' order BY a.id DESC LIMIT 1";
			     //echo $qryInfo;die;
			     $resultInfo = $conn->query($qryInfo);
                 while ($rowInfo = $resultInfo->fetch_assoc()) {
                    $designation = $rowInfo["des"];
                    $department = $rowInfo["dep"];
                    $jobarea = $rowInfo["jobarea"];
                    $empcode = $rowInfo["employeecode"];
                    $empnm = $rowInfo["empnm"];
                    $tin = $rowInfo["tin"];
                    $nid = $rowInfo["nid"];
                    $dob = $rowInfo["dob"];
                    $hr_id = $rowInfo["hrid"];
                    $empid = $rowInfo["empid"];
                    
                    $photo=$rootpath."/common/upload/hc/".$rowInfo["photo"]."";
                    if (file_exists($photo))
                    {
                		$photo="common/upload/hc/".$rowInfo["photo"]."";
            		}
            		else
            		{
            			$photo="images/blankuserimage.png";
                	}
                            
                }
			?>
			            <tr>
			                <td width="100" valign="top">
			                  Employee ID<br>
			                  Designation<br>
			                  Job Area<br>
			                  TIN<br>
			                </td>
			                <td width="300" valign="top">
			                 : <?= $empcode ?><br>
			                 : <?= $designation ?><br>
			                 : <?= $jobarea?><br>
			                 : <?= $tin ?><br>
			                </td>
			                <td width="100" valign="top">
                                Name<br>
                                Department<br>
                                DOB<br>
                                NID<br>
			                </td>
			                <td valign="top">
                                : <?= $empnm ?><br>
                                : <?= $department ?><br>
                                : <?= $dob ?><br>
                                : <?= $nid ?><br>
			                </td>
			                <td width="80" valign="bottom" nowrap>
			                  <img src="<?= $photo ?>" style="max-width: 100%; height: auto;">
			                </td>
			            </tr>
			        </table>
				     																					Attendence Details													

   
				        
				    <table width="100%" border="0" class="attn-table table table-striped">
				        <thead>
				        <tr>
				            <th>Sl. No</th>
				            <th>Date</th>
				            <th>Day</th>
				            <th>Work Shift</th>
				            <th>Start Time</th>
				            <th>End Time</th>
				            <th>In Time</th>
				            <th>Out Time</th>
				            <th>Working Hour</th>
				            <th>Late Minute</th>
				            <th>Early Out Minute</th>
				            <th>Status</th>
				            <th>Remarks</th>
				        </tr>
				        </thead>
				        <tbody>
				<?php
				    // $qryAtt = "SELECT  hr.attendance_id hrid,hr.emp_id ,hr.hrName,DATE_FORMAT(c.day, '%d/%b/%Y') day,c.daynm
        //                         ,st.title shift,st.starttime,st.exittime
        //                         ,u.intime,u.outtime
        //                         ,(case when  u.intime>st.starttime then TIME_FORMAT(TIMEDIFF(u.intime,st.starttime), '%H:%i:%s') else 0 end)latetime
        //                         ,(case when  u.outtime<st.exittime then TIME_FORMAT(TIMEDIFF(st.exittime,u.outtime), '%H:%i:%s') else 0 end)earlytime
        //                         ,TIME_FORMAT(TIMEDIFF(u.outtime,u.intime), '%H:%i:%s') worktime
                                
        //                         ,(case when u.date is null then (case when (SELECT count(hrid) hrd FROM `leave` where c.day between startday and endday and hrid=u.hrid)>0 then 'Leave' else (case when c.daytp='W' then 'Week Day' else  'Absent' end) end) else u.attendance_type end) stats
        //                         ,u.early_leave,u.Details
                                
        //                         from hr ,calander c left join
        //                         (
        //                         select h.date,'' hrid, '' intime,'' outtime,'Holiday' attendance_type,'0' early_leave,Details
        //                         from  Holiday h  
        //                         where h.date BETWEEN '$attendance_from' and '$attendance_to'
        //                         union all
        //                         select a.date,a.hrid,a.intime,a.outtime
        //                         ,(case  a.attendance_type  when 0 then 'Absent' when 1 then 'Present' when 2 then 'Delay' else 'N' end) attendance_type
        //                         ,a.early_leave ,'' Details
        //                         from attendance_test a where a.hrid=$emp_att_id
        //                         and a.date BETWEEN '$attendance_from' and '$attendance_to'
        //                         )u on c.day=u.date 
        //                         left join Shifting st on st.id=nvl((SELECT s.shift FROM assignshifthist s  where s.st=1 and s.empid=u.hrid and s.effectivedt=c.day),3)
                                
        //                         where hr.attendance_id ='$emp_att_id' and c.day BETWEEN '$attendance_from' and '$attendance_to'";
                    $qryAtt = "SELECT  hr.attendance_id hrid,hr.emp_id ,hr.hrName,c.day,c.daynm
,st.title shift,st.starttime,st.exittime
,u.intime,u.outtime
,(case when  u.intime>st.starttime then TIME_FORMAT(TIMEDIFF(u.intime,st.starttime), '%H:%i:%s') else 0 end)latetime
,(case when  u.outtime<st.exittime then TIME_FORMAT(TIMEDIFF(st.exittime,u.outtime), '%H:%i:%s') else 0 end)earlytime
,TIME_FORMAT(TIMEDIFF(u.outtime,u.intime), '%H:%i:%s') worktime
,(case when st.id=6 then st.title else (case when u.date is null then (case when (SELECT count(hrid) hrd FROM `leave` where c.day between startday and endday and hrid=hr.id)>0 then 'Leave' else (case when c.daytp='W' then 'Week Day' else  'Absent' end) end) else u.attendance_type end) end) stats
,u.early_leave,u.Details
from hr ,calander c left join
(
select h.date,'' hrid, '' intime,'' outtime,'Holiday' attendance_type,'0' early_leave,Details
from  Holiday h  
where h.date BETWEEN '$attendance_from' and '$attendance_to'
union all
select a.date,a.hrid,a.intime,a.outtime
,(case  a.attendance_type  when 0 then 'Absent' when 1 then 'Present' when 2 then 'Delay' else 'N' end) attendance_type
,a.early_leave ,a.remarks
from attendance_test a where a.hrid=$emp_att_id
and a.date BETWEEN '$attendance_from' and '$attendance_to'
)u on c.day=u.date 
left join Shifting st on st.id=nvl((SELECT s.shift FROM assignshifthist s  where s.st=1 and s.empid=(select e.id from employee e  where e.employeecode=(select h.`emp_id` from hr h where h.attendance_id=$emp_att_id)) and s.effectivedt=c.day),3)
where hr.attendance_id =$emp_att_id and c.day BETWEEN '$attendance_from' and '$attendance_to'  
ORDER BY `c`.`day` ASC ";
			     //echo $qryAtt;			     die; 
			     $resultAtt = $conn->query($qryAtt);
			     $i = 0; $totalSeconds = 0; $EtotalSeconds=0;
			     $total_days = 0; $total_weekend = 0; $total_holiday = 0; $total_absents = 0;
			     $total_presents = 0; $total_late_present = 0; $total_leaves = 0;
                 while ($rowAtt = $resultAtt->fetch_assoc()) {
                    $i++;
                
                    list($hours, $minutes, $seconds) = explode(':', $rowAtt["latetime"]);
                    $totalSeconds += $hours * 3600 + $minutes * 60 + $seconds;
                    list($Ehours, $Eminutes, $Eseconds) = explode(':', $rowAtt["earlytime"]);
                    $EtotalSeconds += $Ehours * 3600 + $Eminutes * 60 + $Eseconds;
                    
                    $total_days = $i;
                    if($rowAtt["stats"] == "Week Day") $total_weekend += 1;
                    if($rowAtt["stats"] == "Holiday") $total_holiday += 1;
                    if($rowAtt["stats"] == "Absent") $total_absents += 1;
                    if($rowAtt["stats"] == "Present") $total_presents += 1;
                    if($rowAtt["stats"] == "Delay") $total_late_present += 1;
                    if($rowAtt["stats"] == "Leave") $total_leaves += 1;
                    $stats=$rowAtt["stats"];
                    if($rowAtt["earlytime"] !=0){$stats="Early Exit";}
				?>
				        <tr>
				            <td><?= $i ?></td>
				            <td><?= $rowAtt["day"] ?></td>
				            <td><?= $rowAtt["daynm"] ?></td>
				            <td><?= $rowAtt["shift"] ?></td>
				            <td><?= $rowAtt["starttime"] ?></td>
				            <td><?= $rowAtt["exittime"] ?></td>
				            <td><?= $rowAtt["intime"] ?></td>
				            <td><?= $rowAtt["outtime"] ?></td>
				            <td><?= $rowAtt["worktime"] ?></td>
				            <td><?= $rowAtt["latetime"] ?></td>
				            <td><?= $rowAtt["earlytime"] ?></td>
				            <td class="status <?=strtolower(str_replace(' ', '', $stats))?>"><span><?=$stats ?></span></td>
				            <td><?= $rowAtt["Details"] ?></td>
				        </tr>
				<?php } ?>
				        <tr>
                            <?php 
                                $hours = floor($totalSeconds / 3600);
                                $minutes = floor(($totalSeconds % 3600) / 60);
                                $seconds = $totalSeconds % 60;
                                
                                $Ehours = floor($EtotalSeconds / 3600);
                                $Eminutes = floor(($EtotalSeconds % 3600) / 60);
                                $Eseconds = $EtotalSeconds % 60;
                                
                                
                                $totlate = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                                $totearly=sprintf('%02d:%02d:%02d', $Ehours, $Eminutes, $Eseconds)
                            ?>
				            <td colspan="8"></td>
				            <td><b>Total Late Minute:</b></td>
				            <td><b><?= $totlate ?></b></td>
				            <td><b><?= $totearly ?></b></td>
				            <td colspan="2"></td>
				            
				        </tr>
				        
				        </tbody>
				    </table>
				    
				    <div><h4>Attendence Summary:</h4></div>
			        <table border="0" width="100%" class="attn-table-header">
			            <tr>
			              
			                <td width="110" nowrap>
			                  Number of Selected Days<br>
			                  Present Days<br>
			                </td>
			                <td>
			                 : <?= $total_days ?><br>
			                 : <?= $total_presents ?><br>
			                </td>
			                <td  width="110" nowrap>
                                Working Days<br>
                                Late Present<br>
			                </td>
			                <td valign="top">
                                : <?= ($total_days - $total_weekend - $total_holiday) ?><br>
                                : <?= $total_late_present ?><br>
			                </td>
			                <td  width="110" nowrap>
                                Weekend Days<br>
                                Leave Days<br>
			                </td>
			                <td valign="top">
                                : <?= $total_weekend ?><br>
                                : <?= $total_leaves ?><br>
			                </td>
			                <td  width="110" nowrap>
                                Holidays<br>
                                Absent Days<br>
			                </td>
			                <td valign="top">
                                : <?= $total_holiday ?><br>
                                : <?= $total_absents ?><br>
			                </td>
			            </tr>
			        </table>
			        <br><br><br>
				   <table width="100%" class="attn-signature">
				       <tr>
				           <td><span>Signature of Employee</span></td>
				           <td align="right"><span>Authorized Signature</span></td>
				       </tr>
				   </table>
				   <br>

				    
				</div>
				 
     
              
		 
        				 
				 
			</div>
                   
       			   <div class="well" style="padding:5px">
				       <input type="button" onclick="printDiv('printArea')" class="btn btn-lg btn-primary" value="Print">
				   </div>   
		    <?php } ?>
    <br>
    <br>
    <br>
                        
    				
    
                 </div>
            </div> 
            <!-- /#end of panel -->  
    
              <!-- START PLACING YOUR CONTENT HERE -->          
              </p>
            </div>
          </div>
        </div>
        
        <footer>
      <?php
        include_once('common_footer.php');
      ?>   
        </footer>
      </div><!-- page-content-wrapper -->
      
      
  
      
    </div>
    <!-- /#wrapper -->

<script>
    $(document).ready(function() {
        /*
      $("td.status:contains('Absent')").addClass("absent");
      $("td.status:contains('Delay')").addClass("delay");
      $("td.status:contains('Leave')").addClass("leave");
      $("td.status:contains('Early Exit')").addClass("early");
      $("td.status:contains('Off Day')").addClass("offday");
      $("td.status:contains('Holiday')").addClass("holyday");
      $("td.status:contains('Present')").addClass("present");
      */
    });
</script>
 <!-- Select2 JS -->
<script src="js/plugins/select2/select2.min.js"></script>

<!-- Include Toastr JS -->
<script src="js/plugins/toastr/toastr.min.js"></script>


<script>
  $(document).ready(function() {
      // Customized Toastr notification
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-top-right",
  
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
 

 
      
  
    $('.select2basic').select2();
    
    
    

    
    
    
    
  });
  
  
  
  
</script>
		

		
		
    <?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
    
     <!-- Datatable JS -->       
		<script src="js/plugins/datagrid/datatables.min.js"></script>
		
     <script>
    function printDivOld(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        // Set the content of the body to the content of the div
        document.body.innerHTML = printContents;

        // Print the page
        window.print();

        // Restore the original content
        document.body.innerHTML = originalContents;
    }
    
    
   function printDiv(divId) {

			$('#'+divId).printThis({
				importCSS: true, 
				importStyle: true,  
			});

        }    
    
</script>  
    
    </body></html>
  <?php }?>    
