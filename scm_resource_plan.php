<?php
//print_r($_REQUEST);
//exit();


require "common/conn.php";
include_once('rak_framework/fetch.php');
session_start();
$usr = $_SESSION["user"];
//echo $usr;die;

//ini_set('display_errors', 1);


if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {

	
$doId = $_GET["do"];
//$doId = "DO-000009";

$qryInfo="SELECT org.name, so.orderdate, org.orgcode, org.contactno, so.remarks, d.order_id FROM delivery_order d LEFT JOIN `qa` q ON d.order_id=q.order_id 
            LEFT JOIN `soitem` so ON q.order_id=so.socode LEFT JOIN `organization` org ON org.id=so.organization 
            WHERE d.do_id = '".$doId."' LIMIT 1;";
$resultInfo = $conn->query($qryInfo);
while ($rowinfo = $resultInfo->fetch_assoc()) {
    $customerName = $rowinfo["name"];
    $customerId = $rowinfo["orgcode"];
    $orderDate = $rowinfo["orderdate"];
    $customerContact = $rowinfo["contactno"];
    $deliveryAddress = $rowinfo["remarks"];
    $orderId = $rowinfo["order_id"];
}

$type = $_GET["type"];
if($type == 2){
    $qryInfo="SELECT `id`,`type`, `machinary`, `equipment`, `supervisor`, `labor_qty`,DATE_FORMAT(delivery_start, '%d/%m/%Y %h:%i %p') `delivery_start`,DATE_FORMAT(delivery_end, '%d/%m/%Y %h:%i %p') `delivery_end`, `acknowledgement`, `st`
             FROM `resourceplan` WHERE doid = '".$doId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $plan = $rowinfo["type"];
        $machinary = $rowinfo["machinary"];
        $equipment = $rowinfo["equipment"];
        $supervisor = $rowinfo["supervisor"];
        $laborQty = $rowinfo["labor_qty"];
        $deliveryStart = $rowinfo["delivery_start"];
        $deliveryEnd = $rowinfo["delivery_end"];
        $resourceId = $rowinfo["id"];
    }
    
    //Logistic Team
    $logisTeam = array();
    $qryInfo="SELECT logisticteamid FROM assign_logistic_team WHERE resourceid = '".$resourceId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $logisTeam[] = $rowinfo["logisticteamid"];
    }
    
    //Technical Team
    $technicalTeam = array();
    $qryInfo="SELECT empid FROM assign_technical_team WHERE resourceid = '".$resourceId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $technicalTeam[] = $rowinfo["empid"];
    }
    
    //QA Team
    $qaTeam = array();
    $qryInfo="SELECT empid FROM assign_qa_team WHERE resourceid = '".$resourceId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $qaTeam[] = $rowinfo["empid"];
    }
    
    //QA Team
    $otherTeam = array();
    $qryInfo="SELECT empid FROM assign_other_team WHERE resourceid = '".$resourceId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $otherTeam[] = $rowinfo["empid"];
    }
    
}
	
	
    $currSection = 'pendingdelivery';
    $currPage    = basename($_SERVER['PHP_SELF']);
	
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	
include_once 'common_header.php';
    ?>
<!-- Select2 CSS -->
<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />

<style>
.privillages{
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 15px;
}
.privillages > div{
    padding: 0px 5px;
    margin-right: 5px;
    margin-bottom: 5px;
    border-bottom: 0px solid #c0c0c0;
    border-radius: 0px;
/*     background-color: #eeeeee; */
}

.privillages  input{
    margin: 0;
    padding: 0;
}  
    
.row.table-bordered div[class*="col-"] {
    padding-top: 15px;
    
}



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

<!--
Select 2 Custom CSS

-->
    
<style>
.select2-container--default .select2-selection--multiple {
  background-color: white;
  border: 1px solid #aaa!important;
  border-radius: 0px;
  cursor: text;
}

.select2-container .select2-selection--multiple {
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  min-height: 38px;
  user-select: none;
  -webkit-user-select: none;
}


.select2-container--default .select2-selection--multiple .select2-selection__choice {
  background-color: #e4e4e4;
  border: 1px solid #dbdbdb;
  border-radius: 2px;

  padding: 3px;
  padding-left: 0px;
  padding-left: 30px;
  font-size: 14px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
  padding: 3px 8px;
}
    
    
.select2-container{
  width:100%!important;
}    
</style>    
    
    
<body class="form scm scm-resource-plan-form">

<?php
	
	
include_once 'common_top_body.php';
	
	
	
    ?>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Resource Plan</span>
        </div>
        <?php include_once 'menu.php'; ?>
        <div style="height:54px;"></div>
    </div>
    <!-- END #sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12">
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
                       <form method="post" action="common/scm_resource_plan_post.php"  id="ResourcePlanForm"  enctype="multipart/form-data">
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">




			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                           
                            
                            
                           
                            
                                <?php
                                    $mode = 1;
                                ?>

                                   <div class="row form-header">

	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>Supply Chain Management <i class="fa fa-angle-right"></i> Delivery <i class="fa fa-angle-right"></i>  <?=($mode == 1)?"Create a Resource Plan":"Edit Resource Plan"?>  </h6>
      		                            </div>

      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked <span class="redstar">*</span>
 are required)</span></h6>
      		                            </div>


                                   </div>



                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->

                                <div class="row">
                            	    <div class="col-sm-12">
                                            &nbsp;
    	                            </div>
                                    <div class="row no-mg">



                                    </div>
                                    
<!--
                                    <div class="col-sm-12">
                                        <h4>Order Information  </h4>
                                        <hr class="form-hr">
                                    </div>                                    
-->
                                     <div class="col-sm-12 col-lg-6">
                                            <style>
                                                .table-header{
                                                    padding: 15px 25px;
                                                }        
                                            </style>
                                           <div class="well table-header">
                                               
                                               <div class="row">
                                                   <div class="col-sm-12">
                                                        <div class="row">
                                                           <div class="col-md-6">
                                                                   <b>ORDER ID: </b> <?= $orderId ?>  <br>
                                                                    <b>CUSTOMER ID:   </b>  <?= $customerId ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                    <b>CUSTOMER NAME: </b> <?= $customerName ?> <br>
                                                                    <b>CUSTOMER CELL:   </b> <?= $customerContact ?>
                                                            </div>
                                                       </div>

                                                   </div>

                                                   <div class="col-sm-12">

                                                        <div class="row">
                                                           <div class="col-md-6">
                                                                   <b>ORDER DATE:  </b>  <?= $orderDate ?> <br>
                                                                    <b>DELIVERY DATE:     </b>   
                                                            </div>
                                                            <div class="col-md-6">
                                                                    <b>DELIVERY ADDRESS:</b>
                                                                    <?= $deliveryAddress ?>
                                                            </div>
                                                       </div>


                                                   </div>


                                               </div>

                                            </div>  
                                    
                                    
                                    
                                    
                                    </div> 
                                    
                                    <div class="col-md-12">
                                    </div> 
                                    
                                   <div class="col-md-12 col-lg-6">
                                        <h4>Transporation </h4>
                                        <hr class="form-hr">
                                    </div>  
                                    
                                    <div class="col-sm-12">
                                    </div>                        
                                    
                                      <div class="col-md-12 col-lg-6">
                                          
                                        <div class="form-group">
                                            <label class="control-label" for="inputGroupPassword">Resource Plan / Transporation plan for</label>
                                              <ul class="icheck-ul list-horz">
                                <?php
                                        $qrySupervisor = "SELECT name, id FROM `resource_type` ";
                                        $resultSupervisor = $conn->query($qrySupervisor);
                                        while ($rowSupervisor = $resultSupervisor->fetch_assoc()) {
                                            $nm = $rowSupervisor["name"];
                                            $rid = $rowSupervisor["id"];
                                    ?>
                                                <li>
                                                  <input tabindex="<?= $rid ?>" type="radio" name="plantype" id="type-<?= $rid ?>" value ="<?= $rid ?>" <?php if($plan == $rid) echo "checked"; ?> > &nbsp;
                                                  <label for="type-<?= $rid ?>"> <?= $nm ?> </span></label>
                                                </li>
                                <?php } ?>
                                                                      
                            
                                              </ul>

                                        </div>  
                                    </div>                                    
                                    
                                   <div class="col-md-12 ">
                                    </div>
            
            
            
                                    
                                      <div class="col-md-12 col-lg-6">
                                       <hr class="form-hr">
                                        <div class="form-group">
                                            <label class="control-label" for="inputGroupPassword">Transportation/Device Needed</label>
                                            
                                            
                                              <ul class="icheck-ul list-horz row liborder-bottom">
                                                  
                                    <?php
                                        $qrySupervisor = "SELECT name, id, qty FROM `transportation` ";
                                        $resultSupervisor = $conn->query($qrySupervisor);
                                        $sl = 0;
                                        while ($rowSupervisor = $resultSupervisor->fetch_assoc()) {
                                            $nm = $rowSupervisor["name"];
                                            $tid = $rowSupervisor["id"];
                                            $tqty = $rowSupervisor["qty"];
                                            
                                            if($type == 2){
                                                $qry="SELECT qty FROM `assign_transportation` WHERE `resourceid` ='".$resourceId."' and `trid` = ".$tid;
                                                $result = $conn->query($qry); 
                                                if ($result->num_rows > 0){
                                                    while ($row = $result->fetch_assoc()) {
                                                        $transportVal = "checked";
                                                        $trqty = $row["qty"];
                                                    }
                                                }else{
                                                    $transportVal = ""; $trqty = "";
                                                }
                                            }
                                    ?>
                                                <li class="col-lg-6 col-md-4 col-sm-12">
                                                  <div class="col1">
                                                      <input tabindex="<?= $tid ?>" type="checkbox" name="transtype[]" id="<?= $nm ?>" value = "<?= $sl ?>" <?= $transportVal ?>> &nbsp;
                                                      <input type="hidden" name = "transtypeid[]" value=<?= $tid ?>>
                                                      <label for="<?= $nm ?>"> <?= $nm ?> </span></label>
                                                  </div>
                                                  <div class="col1">
                                                      <div class="form-group">
														<input type="number" min="0" max="<?= $tqty ?>" class="numonly "  placeholder="0" name="transqty[]" value = "<?= $trqty ?>">
													  </div>
                                                  </div>
                                                </li>
                                    <?php $sl++; } ?>
                                                
                            
                                              </ul>
                                            

                                        </div>  
                                    </div> 

                                   <div class="col-sm-12">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">

                                        <div class="form-group">

                                            <label for="machinary">Machinary</label>
                                            <input type = "hidden" name = "doId" value = <?= $doId ?>>
                                            <textarea class="form-control" id="machinary" name="machinary" rows="2"><?= $machinary ?></textarea>

                                        </div>

                                    </div>



                                    <div class="col-lg-3 col-md-6 col-sm-12">

                                        <div class="form-group">

                                            <label for="equipment">Special Equipment</label>

                                            <textarea class="form-control" id="equipment" name="equipment" rows="2"><?= $equipment ?></textarea>

                                        </div>

                                    </div>

                                   <div class="col-sm-12">
                                    </div>


                                    <div class="col-md-12 col-lg-6">
                                      <h4>Human Resources</h4>
                                      <hr class="form-hr">
                                    </div>



                                   <div class="col-sm-12">
                                    </div>




                                    <!--div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="supervisor">Supervisor<span class="redstar">*</span></label>
                                              <div class="form-group styled-select">
                                                <select name="supervisor" id="supervisor" placeholder="dfd" class="cmd-child form-control" required="">
                                    <?php
                                        $qrySupervisor = "SELECT concat(`firstname`, ' ', `lastname`, ' (', employeecode, ')')empnm, id FROM `employee` ";
                                        $resultSupervisor = $conn->query($qrySupervisor);
                                        while ($rowSupervisor = $resultSupervisor->fetch_assoc()) {
                                            $empnm = $rowSupervisor["empnm"];
                                            $empid = $rowSupervisor["id"];
                                    ?>
                                                        <option value="<?= $empid ?>" <?php if($empid == $supervisor) echo "selected"; ?>><?= $empnm ?></option>
                                                        
                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div-->
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>"> 
                                                <label for="email">Supervisor<span class="redstar">*</span> </label>
                                                <div class="form-group styled-select">
                                                <select name="cmbempnm" id="cmbempnm" class="select2basic form-control" required>
                                                    <option value="">Select Supervisor</option>
    <?php 
    $qry1="SELECT concat(`firstname`, ' ', `lastname`, ' (', employeecode, ')') empnm, id FROM `employee` ";
	$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
    {while($row1 = $result1->fetch_assoc()) 
          {   $tid= $row1["id"];  $nm=$row1["empnm"]; 
    ?>  
													
                                                    <option value="<? echo $tid; ?>" <? if ($supervisor == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php 
          }
    }      
    ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="logisticTeam">Logistic Team<span class="redstar">*</span></label>
                                          
                                                <select name="logisticTeam[]" id="logisticTeam" placeholder="dfd" class="cmd-child form-control" required="" multiple>
                                    <?php
                                        $qrySupervisor = "SELECT name, id FROM `logistic_team` ";
                                        $resultSupervisor = $conn->query($qrySupervisor);
                                        while ($rowSupervisor = $resultSupervisor->fetch_assoc()) {
                                            $nm = $rowSupervisor["name"];
                                            $lid = $rowSupervisor["id"];
                                    ?>
                                                        <option value="<?= $lid ?>" <?php if (in_array($lid, $logisTeam)) echo "selected"; ?> ><?= $nm ?></option>
                                    <?php } ?>

                                                </select>

                                        </div>

                                    </div>

                                    <div class="col-lg-1 col-md-4 col-sm-6">
                                        <label for="laborQty">Labor Qty</label>
                                          <div class="form-group">
                                                <input type="number"  class="numonly form-control"  placeholder="0" name="laborQty" value = "<?= $laborQty ?>">
                                          </div>
                                    </div>




                                   <div class="col-sm-12">
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="techTeam">Technical Team Member</label>
                                              
                                                <select name="techTeam[]" id="techTeam" placeholder="dfd" class="cmd-child form-control" multiple>
                                <?php 
                                    $qrytech = "SELECT t1.hrid, concat(emp.firstname, ' ', emp.lastname) empnm, dept.name
                                                FROM hraction t1
                                                JOIN (
                                                    SELECT hrid, MAX(makedt) AS latest_makedt
                                                    FROM hraction
                                                    GROUP BY hrid
                                                ) t2 ON t1.hrid = t2.hrid AND t1.makedt = t2.latest_makedt
                                                LEFT JOIN employee emp ON t1.hrid=emp.id LEFT JOIN department dept ON dept.id=t1.postingdepartment
                                                WHERE t1.postingdepartment = 34";
                                                
                                    $resultTech = $conn->query($qrytech);
                                    while ($rowTech = $resultTech->fetch_assoc()) {
                                        $empnm = $rowTech["empnm"];
                                        $hractid = $rowTech["hrid"];
                                
                                ?>
                                                        <option value="<?= $hractid ?>" <?php if (in_array($hractid, $technicalTeam)) echo "selected"; ?> ><?= $empnm ?></option>
                                <?php } ?>
                                                </select>
                                            
                                        </div>

                                    </div>


                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="QATeam">QA Team Member</label>
                                              
                                                <select name="qateam[]" id="QATeam" placeholder="dfd" class="cmd-child form-control" multiple>
                                <?php 
                                    $qryQa = "SELECT t1.hrid, concat(emp.firstname, ' ', emp.lastname) empnm, dept.name
                                                FROM hraction t1
                                                JOIN (
                                                    SELECT hrid, MAX(makedt) AS latest_makedt
                                                    FROM hraction
                                                    GROUP BY hrid
                                                ) t2 ON t1.hrid = t2.hrid AND t1.makedt = t2.latest_makedt
                                                LEFT JOIN employee emp ON t1.hrid=emp.id LEFT JOIN department dept ON dept.id=t1.postingdepartment
                                                WHERE t1.postingdepartment = 33";
                                                
                                    $resultQa = $conn->query($qryQa);
                                    while ($rowQa = $resultQa->fetch_assoc()) {
                                        $empnm = $rowQa["empnm"];
                                        $hractid = $rowQa["hrid"];
                                
                                ?>
                                                        <option value="<?= $hractid ?>" <?php if (in_array($hractid, $qaTeam)) echo "selected"; ?> ><?= $empnm ?></option>
                                <?php } ?>
                                                </select>
                                            
                                        </div>

                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="otherTeam">Other Team Member</label>
                                              
                                                <select name="otherteam[]" id="otherTeam" placeholder="dfd" class="cmd-child form-control" multiple>
                                <?php 
                                    $qryQa = "SELECT t1.hrid, concat(emp.firstname, ' ', emp.lastname) empnm, dept.name
                                                FROM hraction t1
                                                JOIN (
                                                    SELECT hrid, MAX(makedt) AS latest_makedt
                                                    FROM hraction
                                                    GROUP BY hrid
                                                ) t2 ON t1.hrid = t2.hrid AND t1.makedt = t2.latest_makedt
                                                LEFT JOIN employee emp ON t1.hrid=emp.id LEFT JOIN department dept ON dept.id=t1.postingdepartment
                                                WHERE 1=1";
                                                
                                    $resultQa = $conn->query($qryQa);
                                    while ($rowQa = $resultQa->fetch_assoc()) {
                                        $empnm = $rowQa["empnm"];
                                        $hractid = $rowQa["hrid"];
                                
                                ?>
                                                        <option value="<?= $hractid ?>" <?php if (in_array($hractid, $otherTeam)) echo "selected"; ?> ><?= $empnm ?></option>
                                <?php } ?>
                                                </select>
                                            
                                        </div>

                                    </div>

                                   <div class="col-sm-12">
                                    </div>

                                    <div class="col-sm-6">
                                      <h4>Schedule</h4>
                                      <hr class="form-hr">
                                    </div>


                                   <div class="col-sm-12">
                                    </div>                
                                        
                                    

      	                            
                            	    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="po_dt">Delivery Start Date & Time<span class="redstar">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datetimepicker" name="start_dt" id="start_dt" value="<?php echo $deliveryStart; ?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                            	    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="po_dt">Delivery End Date & Time<span class="redstar">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datetimepicker" name="end_dt" id="end_dt" value="<?php echo $deliveryEnd; ?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>


                                   <div class="col-sm-12">
                                    </div>
                                    
                                    
                                    <div class="col-sm-12"> 
											<input type="hidden" name="mode" value="1">
											<input type="hidden" name="id" value="">
                                            

										
										            <?php if($type == 2) { ?>
										                <input class="btn btn-lg btn-default top" type="submit" name="postaction" value="Update" id="save">
										            <?php } else { ?>
													    <input class="btn btn-lg btn-default top" type="submit" name="postaction" value="Create" id="save"> 
													<?php } ?>
    												<input class="btn btn-lg btn-warning top" type="button" name="postaction" value="Cancel" id="cancel" onclick="location.href = 'pendingDeliveryList.php?pg=1&mod=16'">

                                    </div>
                                   

                                    


                                    

                                </div>

							

								
                                       

                                            


		
												
							

                        </div><!-- end panel body -->
                    </div>
        <!-- /#end of panel -->

          <!-- START PLACING YOUR CONTENT HERE -->
           </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->

<?php
include_once 'common_footer.php';
//$cusid = 3; ?>
<?php include_once 'inc_cmb_loader_js.php'; ?>

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

<script>

$(document).on('ready', function() {
  // Call initializeiCheck() function when the AJAX content is loaded
  initializeiCheck();
});
	
	
</script>

<!-- Select2 JS -->
<script src="js/plugins/select2/select2.min.js"></script>
<script>
  $(document).ready(function() {
  
    $('#logisticTeam').select2();
    $('#techTeam').select2();
    $('#QATeam').select2();
    $('#otherTeam').select2();
  });
</script>



<script>

	
    $(document).ready(function(){
		
		
		
		
		
		//input number only validateion
		//put class .numonly to apply this. alpha will no take, only number and float
		
		$('.numonlyx').change(function(e){
			var xxxx = $(this).val();
			//alert(typeof(parseFloat(xxxx)));
		});
		
		
		
        //$('.numonly').keyup(function(e){
        $(document).on("keyup",".numonly", function(e){

			
		  if(/[^0-9.]/g.test(this.value))
		  {
			// Filter non-digits from input value.
			this.value = this.value.replace(/[^0-9.]/g, '');
			  
		  }
		});		

		
		
//hide warehouse quantity on clicking on anywhare;
$(document).on('click',".pagetop", function(event) {
     var div2 = $(event.target);
     var div = $('.qtycounter'); 
    //if (!target.is('.qtycounter') && !target.is('.c-qty')) {
     if (!div.is(event.target) && !div2.is('.c-qty') && !div.has(event.target).length) {
      //$('.qtycounter').css('visibility','hidden'); 
        div.css('visibility','hidden');
    }
  });        
        

    
      
        

})
    
    




function updateSum(rt) {
  var sum = 0;
  rt.find('.quantity').each(function() {
    var quantity = parseInt($(this).val());
    if (!isNaN(quantity)) {
      sum += quantity;
    }
  });
  return sum;
}
  
  
  
      
    

    
//handle samedate in quantity by warehouse
$(document).on('ifChanged','.samedate', function(event) {
  
  var checkbox = event.target;

  // Get the checkbox value and checked status
  var value = checkbox.value;
  var isChecked = checkbox.checked;
  
  var root = $(this).closest('.toClone');
  
	if(isChecked) {
    console.log("Checkbox with value '" + value + "' is checked.");
    // Additional actions for checked checkboxes
    
    var dd = root.find(".delivery-date").val();
    if(dd){
     root.find(".delivery-date").val(dd);
    }else{
    	alert('Enter a Date');
    	root.find(".delivery-date:first").focus();
    }
  }
  
  });    
    
	

	

	
	
	


</script>

<script language="javascript">


<?php
if ($res == 4) {
        ?>

//alert($(".cmb-parent").children("option:selected").val());

var selectedValue = $(".cmb-parent").children("option:selected").val();

	 $.ajax({
            type: "POST",
            url: "cmb/so_item_customer_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
			beforeSend: function(){
					$(".cmd-child").html("<option>Loading...</option>");
				},

        }).done(function(data){
            //root.find(".measure-unit").html(data);

			$(".cmd-child").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child").append(data);

			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });



    $.ajax({
            type: "POST",
            url: "cmb/so_item_poc_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
			beforeSend: function(){
					$(".cmd-child1").html("<option>Loading...</option>");
				},

        }).done(function(data){
            //root.find(".measure-unit").html(data);

			$(".cmd-child1").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child1").append(data);

			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });

<?php
}
 ?>








	




	





</script>

<script>
    //Searchable dropdown
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
        $('#cmborg').val(id);
        //alert(id);


        //Change Contact Name
        $.ajax({
            type: "POST",
            url: "cmb/get_data.php",
            data: { key : id, type: 'orgtocontact' },
			beforeSend: function(){
					$("#cmbsupnm").html("<option>Loading...</option>");
				},

        }).done(function(data){
			$("#cmbsupnm").empty();
			$("#cmbsupnm").append(data);
			//alert(data);
        });


	});
</script>


	
	
<script>

// new calculation code;
$(document).on("focus", ".calc", function() {
  $(this).select();
});








</script>

	
	
	
	
<script>
	//Footer Fields width same as discounted field;
	
function footerfldwdth(){
	ftrfldwdth = $(".c-discounted-ttl").width();
	$(".grid-sum-footer input").width(ftrfldwdth);
}
setTimeout(footerfldwdth,300);

window.addEventListener("resize", () => {
		footerfldwdth();
});	
	
	

var classes = ".grid-sum-footer input, .c-discounted-ttl"

$( "<span>৳</span>" ).insertAfter(classes);
$(classes).parent().addClass("ipspan");

</script>	
	


</body>
</html>
<?php } ?>