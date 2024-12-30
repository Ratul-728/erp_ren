<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$orgid= $_GET['id'];
$currSection = "organization";
//echo $usr;die;


	$dateBehind = date('d/m/Y', strtotime("-15 day"));
	$dateAhead = date('d/m/Y', strtotime("+15 day"));

//$fv= $_GET['f'];
 //if($contid==''){$contid=  $_POST['cdid'];}
//$fv=  $_POST['fv'];
//$fdt=  $_POST['fd'];
//$tdt=  $_POST['td'];
//if($fdt==''){$fdt=date('d/m/Y',strtotime('-6 month'));}
//if($tdt==''){$tdt=date('d/m/Y');}
//if ($fv==''){$fv='1,2,3,4,5,6,7,8';}

$fdt_tab = "01/01/2000";
$tdt_tab = date('d/m/Y');
$abc=0;
if($usr==''){ header("Location: ".$hostpath."/hr.php"); }
else
{
    $contctqry="select org.name ornm,org.`contactno` orno,org.email oremail,org.website orweb,bo.name orindus,1 ctp
                ,'Corporate' ctpnm,c.id cnid,c.name cnnm,c.phone cnph,c.email cnemail, org.orgcode orgcode,
                org.street,org.zip, ar.name area, dis.name district, sta.name state, cnt.name country
                from organization org 
                left join contact c on c.organization=org.orgcode 
                left join businessindustry bo on bo.id=org.industry
                LEFT JOIN area ar ON org.area = ar.id LEFT JOIN district dis ON dis.id = org.district 
                LEFT JOIN state sta ON sta.id = org.state LEFT JOIN country cnt ON cnt.id = org.country
                where org.id=".$orgid;
                //echo $contctqry;die;
        if ($conn->connect_error)
        {
            echo "Connection failed: " . $conn->connect_error;
        } 
        else
        {
            $resultcontct = $conn->query($contctqry); 
            if ($resultcontct->num_rows > 0)
            {
                while($rowcontact = $resultcontct->fetch_assoc()) 
                { 
                    if($rowcontact["ctp"]==1){$amcid=$rowcontact["cnid"];$amcnm=$rowcontact["cnnm"];$amcph=$rowcontact["cnph"];$amcem=$rowcontact["cnemail"];}
                    if($rowcontact["ctp"]==2){$bmcid=$rowcontact["cnid"];$bmcnm=$rowcontact["cnnm"];$bmcph=$rowcontact["cnph"];$bmcem=$rowcontact["cnemail"];}
                    if($rowcontact["ctp"]==3){$tmcid=$rowcontact["cnid"];$tmcnm=$rowcontact["cnnm"];$tmcph=$rowcontact["cnph"];$tmcem=$rowcontact["cnemail"];}
                    
                    $orname=$rowcontact["ornm"]; $orgno=$rowcontact["orno"];  $orgem=$rowcontact["oremail"];$orgwebsite=$rowcontact["website"];$orgindus=$rowcontact["orindus"];
                    $orgcode = $rowcontact["orgcode"];
                    $orgstreet = $rowcontact["street"]; $orgzip = $rowcontact["zip"]; $orgarea = $rowcontact["area"]; $orgdistrict = $rowcontact["district"];  
                    $orgstate = $rowcontact["state"]; $orgcountry = $rowcontact["country"]; 
                    
                    
                }
            }
        }
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<style>
    tr td.address-td{
    p adding-left: 10px;
    t ransform: translatex(10px);
    }
</style>

<body class="form">
    <?php  include_once('common_top_body.php');?>
    <div id="wrapper"> 
    <!-- Sidebar -->
        <div id="sidebar-wrapper" class="mCustomScrollbar">
            <div class="section">
  	            <i class="fa fa-group  icon"></i>
                <span>Organization</span>
            </div>
            <?php  include_once('menu.php');?>
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
                    <!-- START PLACING YOUR CONTENT HERE -->
          
       <!--	<form method="post" action="common/addcomntdetails.php" onsubmit="javascript:return WebForm_OnSubmit();" id="form1">  -->   
                        <form method="post"   id="comnform">
                        <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
                            <div class="panel mother-panel panel-info">
  			                    <!--div class="panel-heading"-->
            		                <!--h1>&nbsp;&nbsp;Organization <i class="fa fa-angle-right"></i><?php echo $orname;?> </h1-->
            		                <!--<input type="hidden"  name="cdid" id="cdid" value="<?php echo $cid;?>"> --> 
            		                
                                <!--/div-->
				                <div class="panel-body   panel-body-padding">
                                    <span class="alertmsg"></span>
                                    
                                    <div class="row form-header"> 
                                   
	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6><a href="javascript:history.back();">Organization</a> <i class="fa fa-angle-right"></i> <?php echo $orname;?></h6>
      		                            </div>
      		                            
      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> <!--(Field Marked * are required)--></span></h6>
      		                            </div>                                   
                                   
                                   
                                   </div>                                    
                                    
                                    
                                    <div class="row dashboard-filter">
                                        <div class="col-xs-12">
                                            <div class="inner-tab-top">
                                                <div class="inner-tab-wrapper">       
                                                    <div class="row">               
                                            		    <div class="col-xs-12 col-lg-8">
                                                            <ul class="inner-tabs">
                                                                <li data-myclass="tab-general" class="active"><a href="#"><i class="fa fa-comments-o"></i><span class="inner-tabs-title">General</span></a></li>
                                                                <li data-myclass="tab-invoices" class="invoice-tab"><a href="#"><i class="fa fa-file-text-o"></i><span class="inner-tabs-title">Invoices</span></a></li>
                                                                <li data-myclass="tab-orderhistory" class="orders-tab"><a href="#"><i class="fa fa-shopping-basket"></i><span class="inner-tabs-title">Orders<span class="hidden-md hidden-xs">History</span></span></a></li>
                                                                <li data-myclass="tab-paymenthistory" class="payment-tab"><a href="#"><i class="fa fa-dollar"></i><span class="inner-tabs-title">Payment<span class="hidden-md hidden-xs">History</span></span></a></li>
                                                                <li data-myclass="tab-profile"><a href="#"><i class="fa fa-user"></i><span class="inner-tabs-title">Profiles</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                	<?php 
														
														if(!$fdt){
														$fdt = $dateBehind;
													 	$tdt = $dateAhead;
														}
													 ?>
												<!-- Filter by date   -->
                                                <div class="tab-calendar">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-4 co l-lg-offset-7   sm-text-right md-text-right">
                                                            <label>Filter</label>
                                                        </div>                            	    
                                                        <div class="col-lg-5 col-md-5  col-sm-4">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control datepicker_history_filter" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt;?>" >
                                                                <div class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-th"></span>
                                                                </div>
                                                            </div>     
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-4">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control datepicker_history_filter" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt;?>"  >
                                                                <div class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-th"></span>
                                                                </div>
                                                            </div>     
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>  
                                            <br> 
                                        </div> 
                                    </div>
                                    <style>
                                    .maintabcontent{display:none;}
									
                                    </style>
                                    <div class="main-tab-wrapper">
                                    	<div class="wait">Please wait...</div>
                                        <div class="row  b alltabs tab-general"-->
                                            <div class="col-lg-6 section-comtype">
                                                <h4>Communication Type: </h4>
                                                <!-- slider start from here -->
                                                <link type="text/css" rel="stylesheet" href ="js/plugins/lightslider/css/lightslider.css" />
                                                <link type="text/css" rel="stylesheet" href ="js/plugins/lightslider/css/contacttype_setting.css" />
                                                
                                                <ul class="nav nav-tabs list" id="contactTypeTabs">
                                                    <li cl ass="active"><a data-toggle="tab" class="Meeting" href="#Meeting">Meeting</a></li>              
                                                    <li><a data-toggle="tab" class="Calls" href="#Calls">Calls</a></li>                
                                                    <li><a data-toggle="tab" class="SMS" href="#SMS">SMS</a></li>             
                                                    <li><a data-toggle="tab" class="Email" href="#Email">Email</a></li>               
                                                    <li><a data-toggle="tab" class="Order" href="#Order">Order</a></li>                
                                                    <li><a data-toggle="tab" class="Payment" href="#Payment">Payment</a></li>
                                                    <li><a data-toggle="tab" class="Comments" href="#Comments">Comments</a></li>
                                                    <li><a data-toggle="tab" class="Other" href="#Other">Other</a></li>
                                                </ul>
                                                <div class="tab-content time-wrapper">
                                                    <div id="Meeting" class="tab-pane fade in active">
                                                        <div class="well">
                                                            <h5>Add Meeting Detail</h5>
                                                            <textarea class="form-control" name="meet_note" id="meet_note"></textarea>
                                                            <div class="row" style="padding:15px 5px;">
                                                                <div class="col-xs-12 col-sm-4  col-md-4">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control datepicker_comtype" placeholder="Meeting Date &amp; Time" name="meet_dt" id="meet_dt"> 
                                                                       <!-- <input type="datetime-local" class="form-control"  placeholder="Meeting Date &amp; Time" name="meet_dt" id="meet_dt"  required>-->
                                                                        <div class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-th"></span>
                                                                        </div>
                                                                    </div>	
                                                                </div>
                                                                <div class="col-xs-12 col-sm-4 col-md-4">                            
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" placeholder="Conveyance Amount" name="meeting_place" id="meeting_place" >
                                                                        <div class="input-group-addon">
                                                                            <span class="glyphicon  glyphicon-usd"></span>
                                                                        </div>
                                                                    </div> 
                                                                </div>
                                                                <!--div class="col-xs-12 col-sm-4 col-md-4">                            
                                                                    <div class="form-group styled-select">
                                                                        <select placeholder="Meeting Status" name="cmbstatus" id="cmbstatus" class="form-control">
                                                                            <option value="">Meeting Status</option>
                                                                            <option value="2">Scheduled</option>
                                                                            <option value="3">Done</option>
                                                                        </select>
                                                                    </div>
                                                                </div-->
                                                                <div class="col-xs-12 col-sm-4 col-md-4">                            
                                                                    <div class="form-group styled-select">
                                                                        <select placeholder="Contact" name="meet_cdid" id="meet_cdid" class="form-control">
                                                                        <!--     <option value="">Select Contact</option> -->
                                                                    <?php   $orgcqry = "SELECT `id`,`name` FROM `contact` WHERE `organization` = '".$orgcode."'";
                                                                            //echo $orgcqry;die;
                                                                            $resultorgc = $conn->query($orgcqry); 
                                                                            while($roworgc = $resultorgc->fetch_assoc()){ 
                                                                    ?>
                                                                            <option value="<?php echo $roworgc["id"];?>"><?php echo $roworgc["name"];?></option>
                                                                            
                                                                    <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12">  
                                                                    <input type="submit" class="btn" value="Save" name="addmeet"  id="addmeet">   
                                                                    <input type="submit" class="btn" value="Cancel" name="Cancel"  id="Cancel">                                        
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                      
                                                    <div id="Calls" class="tab-pane fade">
                                                        <div class="well">
                                                            <h5>Add Call Detail</h5>
                                                            <textarea class="form-control" name="call_note" id="call_note"></textarea>
                                                            <div class="row" style="padding:15px 5px;">
                                                                <div class="col-xs-6  col-md-4">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control datepicker_comtype" placeholder="Call Date &amp; Time" name="call_dt" id="call_dt" >
                                                                        <div class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-th"></span>
                                                                        </div>
                                                                    </div>	
                                                                </div>
                                                                <div class="col-xs-12 col-sm-4 col-md-4">                            
                                                                    <div class="form-group styled-select">
                                                                        <select placeholder="Contact" name="call_cdid" id="call_cdid" class="form-control">
                                                                
                                                                            <option value="">Select Contact</option>
                                                                    <?php   $orgcqry = "SELECT `id`,`name` FROM `contact` WHERE `organization` = '".$orgcode."'";
                                                                            //echo $orgcqry;die;
                                                                            $resultorgc = $conn->query($orgcqry); 
                                                                            while($roworgc = $resultorgc->fetch_assoc()){ 
                                                                    ?>
                                                                            <option value="<?php echo $roworgc["id"];?>"><?php echo $roworgc["name"];?></option>
                                                                            
                                                                    <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12">  
                                                                    <input type="submit" class="btn" value="Save" name="addcall"  id="addcall">
                                                                    <input type="submit" class="btn" value="Cancel" name="Cancel"  id="Cancel">                                    
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </div>
                      
                                                    <div id="SMS" class="tab-pane fade">
                                                        <div class="well">
                                                            <h5>Add SMS Detail</h5>
                                                            <textarea class="form-control" name="sms_note" id="sms_note"></textarea>
                                                            <div class="row" style="padding:15px 5px;">
                                                                <div class="col-xs-6  col-md-4">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control datepicker_comtype" placeholder="SMS Date &amp; Time" name="sms_dt" id="sms_dt" >
                                                                        <div class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-th"></span>
                                                                        </div>
                                                                    </div>	
                                                                </div>
                                                                <div class="col-xs-12 col-sm-4 col-md-4">                            
                                                                    <div class="form-group styled-select">
                                                                        <select placeholder="Contact" name="sms_cdid" id="sms_cdid" class="form-control">
                                                                            <option value="">Select Contact</option>
                                                                            <?php   $orgcqry = "SELECT `id`,`name` FROM `contact` WHERE `organization` = '".$orgcode."'";
                                                                            //echo $orgcqry;die;
                                                                            $resultorgc = $conn->query($orgcqry); 
                                                                            while($roworgc = $resultorgc->fetch_assoc()){ 
                                                                    ?>
                                                                            <option value="<?php echo $roworgc["id"];?>"><?php echo $roworgc["name"];?></option>
                                                                            
                                                                    <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12">  
                                                                    <input type="submit" class="btn" value="Save" name="addsms"  id="addsms">
                                                                    <input type="submit" class="btn" value="Cancel" name="Cancel"  id="Cancel">                                         
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </div>
                      
                                                    <div id="Email" class="tab-pane fade">
                                                        <div class="well">
                                                            <h5>Add Email Detail</h5>
                                                                <textarea class="form-control" name="email_note" id="email_note"></textarea>
                                                                <div class="row" style="padding:15px 5px;">
                                                                    <div class="col-xs-6  col-md-4">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control datepicker_comtype" placeholder="Email Date &amp; Time" name="email_dt" id="email_dt" >
                                                                            <div class="input-group-addon">
                                                                                <span class="glyphicon glyphicon-th"></span>
                                                                            </div>
                                                                        </div>	
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-4 col-md-4">                            
                                                                    <div class="form-group styled-select">
                                                                        <select placeholder="Contact" name="email_cdid" id="email_cdid" class="form-control">
                                                                            <option value="">Select Contact</option>
                                                                            <?php   $orgcqry = "SELECT `id`,`name` FROM `contact` WHERE `organization` = '".$orgcode."'";
                                                                            //echo $orgcqry;die;
                                                                            $resultorgc = $conn->query($orgcqry); 
                                                                            while($roworgc = $resultorgc->fetch_assoc()){ 
                                                                    ?>
                                                                            <option value="<?php echo $roworgc["id"];?>"><?php echo $roworgc["name"];?></option>
                                                                            
                                                                    <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                    <div class="col-xs-12">  
                                                                        <input type="submit" class="btn" value="Save" name="addemail"  id="addemail">
                                                                         <input type="submit" class="btn" value="Cancel" name="Cancel"  id="Cancel">                                        
                                                                    </div>
                                                                </div> 
                                                        </div>
                                                    </div> 
    
                                                    <div id="Order" class="tab-pane fade">
                                                        <div class="well">
                                                            <h5>Add Order Detail</h5>
                                                            <textarea class="form-control" name="order_note" id="order_note"></textarea>
                                                            <div class="row" style="padding:15px 5px;">
                                                                <div class="col-xs-6  col-md-4">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control datepicker_comtype" placeholder="Order Date  &amp; Time" name="order_dt" id="order_dt" >
                                                                        <div class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-th"></span>
                                                                        </div>
                                                                    </div>	
                                                                </div>
                                                                <div class="col-xs-12 col-sm-4 col-md-4">                            
                                                                    <div class="form-group styled-select">
                                                                        <select placeholder="Contact" name="order_cdid" id="order_cdid" class="form-control">
                                                                            <option value="">Select Contact</option>
                                                                            <?php   $orgcqry = "SELECT `id`,`name` FROM `contact` WHERE `organization` = '".$orgcode."'";
                                                                            //echo $orgcqry;die;
                                                                            $resultorgc = $conn->query($orgcqry); 
                                                                            while($roworgc = $resultorgc->fetch_assoc()){ 
                                                                    ?>
                                                                            <option value="<?php echo $roworgc["id"];?>"><?php echo $roworgc["name"];?></option>
                                                                            
                                                                    <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12">  
                                                                     <input type="submit" class="btn" value="Save" name="addorder"  id="addorder">
                                                                     <input type="submit" class="btn" value="Cancel" name="Cancel"  id="Cancel">                                        
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </div> 
                      
                                                    <div id="Payment" class="tab-pane fade">
                                                        <div class="well">
                                                            <h5>Add Payment Detail</h5>
                                                                <textarea class="form-control" name="payment_note" id="payment_note"></textarea>
                                                                <div class="row" style="padding:15px 5px;">
                                                                    <div class="col-xs-6  col-md-4">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control datepicker_comtype" placeholder="Payment Date &amp; Time" name="payment_dt" id="payment_dt" >
                                                                            <div class="input-group-addon">
                                                                                <span class="glyphicon glyphicon-th"></span>
                                                                            </div>
                                                                        </div>	
                                                                    </div>
                                                                    <div class="col-xs-6 col-md-4">                            
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" placeholder="Amount" name="amount" id="amount" >
                                                                            <div class="input-group-addon">
                                                                                <span class="glyphicon  glyphicon-usd"></span>
                                                                            </div>
                                                                        </div> 
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-4 col-md-4">                            
                                                                    <div class="form-group styled-select">
                                                                        <select placeholder="Contact" name="payment_cdid" id="payment_cdid" class="form-control">
                                                                            <option value="">Select Contact</option>
                                                                            <?php   $orgcqry = "SELECT `id`,`name` FROM `contact` WHERE `organization` = '".$orgcode."'";
                                                                            //echo $orgcqry;die;
                                                                            $resultorgc = $conn->query($orgcqry); 
                                                                            while($roworgc = $resultorgc->fetch_assoc()){ 
                                                                    ?>
                                                                            <option value="<?php echo $roworgc["id"];?>"><?php echo $roworgc["name"];?></option>
                                                                            
                                                                    <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                    <div class="col-xs-12">  
                                                                        <input type="submit" class="btn" value="Save" name="addpayment"  id="addpayment">
                                                                         <input type="submit" class="btn" value="Cancel" name="Cancel"  id="Cancel">                                          
                                                                    </div>
                                                                </div> 
                                                        </div>
                                                    </div> 
                      
                                                    <div id="Comments" class="tab-pane fade">
                                                        <div class="well">
                                                            <h5>Add Comments</h5>
                                                            <textarea class="form-control" name="comment_note" id="comment_note"></textarea>
                                                            <div class="row" style="padding:15px 5px;">
                                                                <div class="col-xs-6  col-md-4">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control datepicker_comtype" placeholder="Comments Date &amp; Time" name="comment_dt" id="comment_dt" >
                                                                        <div class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-th"></span>
                                                                        </div>
                                                                    </div>	
                                                                </div>
                                                                <div class="col-xs-12 col-sm-4 col-md-4">                            
                                                                    <div class="form-group styled-select">
                                                                        <select placeholder="Contact" name="comment_cdid" id="comment_cdid" class="form-control">
                                                                            <option value="">Select Contact</option>
                                                                            <?php   $orgcqry = "SELECT `id`,`name` FROM `contact` WHERE `organization` = '".$orgcode."'";
                                                                            //echo $orgcqry;die;
                                                                            $resultorgc = $conn->query($orgcqry); 
                                                                            while($roworgc = $resultorgc->fetch_assoc()){ 
                                                                    ?>
                                                                            <option value="<?php echo $roworgc["id"];?>"><?php echo $roworgc["name"];?></option>
                                                                            
                                                                    <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12">  
                                                                     <input type="submit" class="btn" value="Save" name="addcomment"  id="addcomment">
                                                                     <input type="submit" class="btn" value="Cancel" name="Cancel"  id="Cancel">                                          
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </div>   
                      
                                                    <div id="Other" class="tab-pane fade">
                                                        <div class="well">
                                                            <h5>Add Other Detail</h5>
                                                                <textarea class="form-control" name="other_note" id="other_note"></textarea>
                                                                <div class="row" style="padding:15px 5px;">
                                                                    <div class="col-xs-6  col-md-4">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control datepicker_comtype" placeholder="Add Date &amp; Time" name="other_dt" id="other_dt"  >
                                                                            <div class="input-group-addon">
                                                                                <span class="glyphicon glyphicon-th"></span>
                                                                            </div>
                                                                        </div>	
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-4 col-md-4">                            
                                                                    <div class="form-group styled-select">
                                                                        <select placeholder="Contact" name="other_cdid" id="other_cdid" class="form-control">
                                                                            <option value="">Select Contact</option>
                                                                            <?php   $orgcqry = "SELECT `id`,`name` FROM `contact` WHERE `organization` = '".$orgcode."'";
                                                                            //echo $orgcqry;die;
                                                                            $resultorgc = $conn->query($orgcqry); 
                                                                            while($roworgc = $resultorgc->fetch_assoc()){ 
                                                                    ?>
                                                                            <option value="<?php echo $roworgc["id"];?>"><?php echo $roworgc["name"];?></option>
                                                                            
                                                                    <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                    <div class="col-xs-12">  
                                                                         <input type="submit" class="btn" value="Save" name="addother"  id="addother">
                                                                         <input type="submit" class="btn" value="Cancel" name="Cancel"  id="Cancel">                                         
                                                                    </div>
                                                                </div> 
                                                        </div>
                                                    </div> 
                      
                                                </div>
                    
                                            </div>
            
                                            <div class="col-lg-6 section-contacthistory">
                                                <h4> Filter: </h4> 
                                                <style>
                                                #history-filter-wrapper{
                                                    display:block;
                                                    transform: scale(0.5);
                                                 }
                                                
                                                </style> 
                                                
                                                    
                                                    
                                                <div class="history-filter-wrapper">      
                                                <ul class="icheck-ul list-inline contact_history_filter">
                                                    
                                                    
                                                        <li>
                                                            <input tabindex="1" type="checkbox" id="chkcomm" class="filter-checkbox" value="7" > &nbsp;
                                                            <label for="chkcomm"> Comments</span></label>
                                                        </li>
                                                        <li>
                                                            <input tabindex="2" type="checkbox" id="input-2" class="filter-checkbox" value="1" > &nbsp;
                                                            <label for="input-2"> Meeting</span></label>
                                                        </li>
                                                        <li>
                                                            <input tabindex="3" type="checkbox" id="input-3" class="filter-checkbox" value="2"> &nbsp;
                                                            <label for="input-3"> Call</span></label>
                                                        </li>
                                                        <li>
                                                            <input tabindex="4" type="checkbox" id="input-4"  class="filter-checkbox" value="3"> &nbsp;
                                                            <label for="input-4"> SMS</span></label>
                                                        </li>
                                                        <li>
                                                            <input tabindex="5" type="checkbox" id="input-5"  class="filter-checkbox" value="4"> &nbsp;
                                                            <label for="input-5"> Email</span></label>
                                                        </li>
                                                        <li>
                                                            <input tabindex="6" type="checkbox" id="input-6"  class="filter-checkbox" value="5"> &nbsp;
                                                            <label for="input-6"> Order</span></label>
                                                        </li>
                                                        <li>
                                                            <input tabindex="7" type="checkbox" id="input-7" class="filter-checkbox" value="6"> &nbsp;
                                                            <label for="input-7"> Payment</span></label>
                                                        </li>
                                                        <li>
                                                            <input tabindex="8" type="checkbox" id="input-8"  class="filter-checkbox" value="8"> &nbsp;
                                                            <label for="input-8">Other</span></label>
                                                        </li>                                                                                                                                                                                     
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="filter_value" name="filter_value" class="filter_value"  size="100">
                                                <!-- main column 1 -->
                                                <style>
                                                #history-wrapper{
                                                    min-height:500px;
                                                    }
                                                </style>
                                                
                                                <div id="history-wrapper">
                                                    <div class="history-margin">
                            <?php 
                                    /*$msgctqry="SELECT c.name,d.`id`,t.`name` `comntp`,DATE_FORMAT(d.`comndt`,'%e/%c/%Y %h:%i:%s %p') comndt, d.`note`, d.`place`, d.`status`, d.`value`, d.`makeby` FROM `comncdetails` d left join comnctype t 
                                    on d.`comntp`=t.`id`  left join contact c on d.contactid=c.id left join organization org on org.orgcode=c.organization
                                    WHERE org.id =".$orgid."  and d.`comndt` between STR_TO_DATE('".$fdt."', '%d/%m/%Y') and STR_TO_DATE('".$tdt."', '%d/%m/%Y')  order by d.`comndt` desc";
                                    */
                                    //New
                                    $msgctqry="SELECT c.name,d.`id`,t.`name` `comntp`,DATE_FORMAT(d.`comndt`,'%e/%c/%Y %h:%i:%s %p') comndt, d.`note`, d.`place`, d.`status`, d.`value`, d.`makeby` 
FROM `comncdetails` d                            left join comnctype t on d.`comntp`=t.`id` left join organization org on org.id=d.`contactid` left join contact c on org.orgcode=c.organization
                                                WHERE org.id =".$orgid."  and d.`comndt` order by d.`comndt` desc LIMIT 100";
                                                                    //and d.`comntp` in(".$fv.") echo $abc;
                                                                    //echo $msgctqry; die; 
                                                $resultmsg = $conn->query($msgctqry); 
                                                if ($resultmsg->num_rows > 0)
                                                {
                                                    while($rowmsg = $resultmsg->fetch_assoc()) 
                                                        { 
                                                            $mid=$rowmsg["id"];$comntp=$rowmsg["comntp"]; $comndt=$rowmsg["comndt"];  $note=$rowmsg["note"];$place=$rowmsg["place"];
                                                            $status=$rowmsg["status"]; $value=$rowmsg["value"]; 
                                                            $class='panel panel-default '.$comntp;
                                    ?>                
                                                    <div class="panel panel-default <?=$comntp?>">
                                                        <div class="panel-heading"><i class="icon-contact icon-<?=strtolower($comntp)?>"></i><?=$comntp?> : <span><?=$comndt?></span><img src="images/profile_picture/profile.jpg"  class="profile-picture"></div>
                                                        <div class="panel-body"><?=$note?></div>
                                                    </div>
                                    <?php }} ?>  
                                    
                                    
                                    
                                             
                                    
                                    
                                           
                                                    </div>
                                                </div>
                                                <!-- end main column 1 -->
                                            </div>
                                        </div>
                                        
                                        <!--Inoive Tabs -->
                                        <div class="row alltabs maintabcontent tab-invoices">
                                            <link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                                                
                                            <div >
                                                <!-- Table -->
                                                <table id="listTable" class="table display dataTable no-footer actionbtns" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;">
						
                            					<!--table id="listTable" class="display dataTable no-footer actio nbtn" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;"-->
                                                    <thead>
                                                    <tr>
                                                        <th>Sl.</th>
                                                        <th>Invoice</th>
                                                        <th>Year </th>
                                                        <th>Month </th>
                                                        <th>SOF</th>
                                                        <th>Company</th>
                                                        <th>Amount(Invoice Currency)</th>
                                                        <th>Amount(BDT) </th>
                                                        <th>Paid</th>
                                                        <th>Due</th>
                                                        <th>Due Date </th>
                                                        <th>Invoice Status </th>
                                                        <th>Payment Status </th>
                            							<th width="1%"><span>Action</span></th>
                                                    </tr>
                                                    </thead>
                                                    
                                                </table>
                                            </div> 
                                        </div>
                                        <!-- Order tab -->
                                        <div class="row alltabs maintabcontent tab-orderhistory">
                                            <table id='listTable2' class='display dataTable actionbtn' width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Sl.</th>
                                                    <th>Account Type</th>
                                                    <th>Contact Person</th>
                                                    <th>Company</th>
                                                    <th>SOF</th>
                                                    <th>DATE</th>
                                                    <th>CUR </th>
                                                    <th>OTC </th>
                                                    <th>MRC </th>
                                                    <th>ACCOUNT MANAGER </th>
                                                    <th>EDIT</th>
                                                    <th>INVOICE</th>
                                                    <th>DELETE</th>
                                                </tr>
                                                </thead>
                                                
                                            </table>
                                        </div> 
                                        <div class="row alltabs maintabcontent tab-paymenthistory">
                                            <table id='listTable3' class='display dataTable' width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Trans Date</th>
                                                    <th>Customer</th>
                                                    <th>Trans Type</th>
                                                    <th>Reference No</th>
                                                    <th>Amount</th>
                                                    <th>INVOICE </th>
                                                    <th>Description </th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    
                                                </tr>
                                                </thead>
                                                
                                            </table>
                                        </div>
                                        
                                        <!-- Profile Tab -->
                                        <div class="row alltabs maintabcontent tab-profile">
                                            <div class="panel panel-default hidden-xs hidden-sm hidden-md">
                                                    <div class="panel-heading">Detail info</div>
                                                    <div class="panel-body">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td>Industry</td>
                                                                <td><?php echo $orgindus;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Contact NO</td>
                                                                <td><?php echo $orgno;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td><?php echo $orgem;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Website</td>
                                                                <td><?php echo $orgwebsite;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    Address
                                                                </td>
                                                                <td class="address-td">
                                                                    <?= $orgstreet ?> <?= $orgarea ?> <?= $orgzip ?> <?= $orgcountry ?>
                                                                </td>
                                                            </tr>
                                                            
                                                        </table> 
                                                    </div>
                                                </div>
                                                
                                                <div class="panel panel-default hidden-xs hidden-sm hidden-md">
                                                    <div class="panel-heading">Contact Info</div>
                                                    <div class="panel-body">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td style="font-weight:bold">Account Manager</td>
                                                                <td><?php echo $amcnm?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Phone</td>
                                                                <td><?php echo $amcph;?></td>
                                                            </tr>
                                                             <tr>
                                                                <td>Email</td>
                                                                <td><?php echo $amcem ;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-weight:bold">Billing Contact </td>
                                                                <td><?php echo $bmcnm;?></td>
                                                            </tr>
                                                             <tr>
                                                                <td>Phone </td>
                                                                <td><?php echo $bmcph;?></td>
                                                            </tr>
                                                             <tr>
                                                                <td>Email  </td>
                                                                <td><?php echo $bmcem ;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-weight:bold">Technical Contact</td>
                                                                <td><?php echo $tmcnm;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Phone </td>
                                                                <td><?php echo $tmcph;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td><?php echo $tmcem ;?></td>
                                                            </tr>
                                                            <!--<tr>
                                                                <td>Total Business Value</td>
                                                                <td>500000</td> 
                                                            </tr>-->
                                                        </table>
                                                    </div>
                                                </div>
                                        </div>                                        
                                                                                                                      
                                        
                                        
                                    </div>
                                </div>
                            </div> 
                        </form>
        <!-- /#end of panel --> 
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /#page-content-wrapper -->
<?php    include_once('common_footer.php');?>

<!-- Tab table -->
<script src="js/plugins/datagrid/datatables.min.js"></script>
        
        <!-- Script -->
        <script>
        $(".invoice-tab").click(function(){
            if ( ! $.fn.DataTable.isDataTable( '#listTable' ) ) {
                var table1 = $('#listTable').DataTable({
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 50,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,	
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=invoice&filterorg=<?= $orgid ?>&fdt=<?= $fdt ?>&tdt=<?= $tdt ?>'
                },
                'columns': [
                    { data: 'sl', "orderable": false , "class":"action"  },
                    { data: 'invoiceno' },
                    { data: 'invyr' },
                    { data: 'invoicemonth' },
                    { data: 'soid' },
                    { data: 'organization' },
					{ data: 'invoiceamt' },
					{ data: 'amount_bdt' },
                    { data: 'paidamount' },
                    { data: 'dueamount' },
                	{ data: 'duedt' },
            		{ data: 'invoiceSt' },
            		{ data: 'paymentSt' },
					{ data: 'edit', "orderable": false , "class":"action" }
                ]
            });
            }
            
            
        });
        $(".orders-tab").click(function(){
            if ( ! $.fn.DataTable.isDataTable( '#listTable2' ) ) { 
                var table1 = $('#listTable2').DataTable({
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 25,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=soitem&orgid=<?= $orgid ?>'
                },
                'columns': [
                    { data: 'id' },
                    { data: 'srctype' },
                    { data: 'hrName' },
                    { data: 'organization' },
					{ data: 'socode' },
                    { data: 'orderdate' },
                    { data: 'shnm' },
                	{ data: 'otc' },
            		{ data: 'mrc' },
            		{ data: 'poc' },
					{ data: 'edit', "orderable": false  },
					{ data: 'inv', "orderable": false  },
					{ data: 'del', "orderable": false  }
                ]
            });
            }
            
        });
        
        $(".payment-tab").click(function(){
            if ( ! $.fn.DataTable.isDataTable( '#listTable3' ) ) { 
                var table1 = $('#listTable3').DataTable({
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 25,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=collec&orgid=<?= $orgid ?>&fdt=<?= $fdt_tab ?>&tdt=<?= $tdt_tab ?>'
                },
                'columns': [
                    { data: 'trdt' },
                    { data: 'customer' },
                    { data: 'transmode' },
                    { data: 'transref' },
					{ data: 'amount' },
                    { data: 'inv' },
                	{ data: 'naration' },
                	{ data: 'view', "orderable": false },
					{ data: 'edit', "orderable": false },
					{ data: 'del', "orderable": false },
                ]
            });
            }
            
        });
        
        setTimeout(function(){
		    table1.columns.adjust().draw();
        }, 350);
        
</script>

<script language="javascript">
$(document).ready(function(){
    
	
	//tabl navigation;
	$(".maintabcontent").attr("style","display:none");
	$(".tab-general").attr("style","display:block");
	$(".wait").attr("style","display:none");
	
	$(".inner-tabs li").on('click',function(){
	    
		$(".alltabs").attr("style","display:none");
		$(".inner-tabs li").removeClass("active");
		$(this).addClass("active");
		var showclass = $(this).data("myclass");
		//alert(showclass);
		$("."+showclass).show();
	});

        $('.datepicker_history_filter').datetimepicker({
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


        $('.datepicker_comtype').datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "DD/MM/YYYY HH:mm",
					//format: 'LT',
					//keepOpen:true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-chevron-up",
                 down: "fa fa-chevron-down"
                }
            });	
// load conversasion on date change;
/*$(".datepicker_history_filter").on('dp.change', function(){

		//alert($(this).val());
		loadConversasion($(".filter_value").val());
});*/

$('.contact_history_filter input').on('ifChecked', function(event){
	
     var arr = $('.filter-checkbox:checked').map(function () {
         return this.value;
     }).get();
     //console.log(arr);
  $(".filter_value").val(arr);
  loadConversasion($(".filter_value").val());
     
});

$('.contact_history_filter input').on('ifUnchecked', function(event){
  
     var arr = $('.filter-checkbox:checked').map(function () {
         return this.value;
     }).get();
     //console.log(arr);
  $(".filter_value").val(arr);
  	
	 loadConversasion($(".filter_value").val());
});

 loadConversasion($(".filter_value").val());




function loadConversasion(sfv){
	 $(".history-margin").html('<div class="panel panel-default"><div class="loading">loading...</div></div>');

	sfv = $(".filter_value").val();
	//alert(sfv);
	/*var sfdt = document.getElementById('filter_date_from').value;
	var stdt =  document.getElementById('filter_date_to').value;
	





	if(!sfdt){sfdt = '<?=$dateBehind?>'}
	if(!stdt){stdt = '<?=$dateAhead?>'}
	//alert(sfdt);
 	var sid = <?=$orgid?>;
	//alert(sfdt); */

//	var contactdata = { contid:sid,fv: sfv, fdt : '01/05/2019', tdt : '10/10/2019'}, fdt : sfdt, tdt : stdt

	

    var sid = <?=$orgid?>;
   // alert (sid);
	var contactdata = { orgid:sid,fv: sfv}
	
	var saveData = $.ajax({
		
		  type: 'POST',
		  url: "phpajax/load_conversasion.php?action=loadconversasion_org",
		  data: contactdata,
		  dataType: "text",
		  success: function(resultData) {
			  $(".history-margin").html(resultData);
		   		//alert(resultData) 
				}
	});
	saveData.error(function() { alert("Something went wrong"); });

	
}

$("#addmeet").on('click',function(){saveData($(this).attr('name'));	return false;});

$("#addmeet").on('click',function(){document.getElementById("meet_note").value = "";
        document.getElementById("meet_dt").value = "";
});

$("#addcall").on('click',function(){saveData($(this).attr('name'));	return false;});

$("#addsms").on('click',function(){saveData($(this).attr('name'));	return false;});

$("#addemail").on('click',function(){saveData($(this).attr('name'));	return false;});

$("#addorder").on('click',function(){saveData($(this).attr('name'));	return false;});

$("#addpayment").on('click',function(){saveData($(this).attr('name'));	return false;});

$("#addcomment").on('click',function(){saveData($(this).attr('name'));	return false;});

$("#addother").on('click',function(){saveData($(this).attr('name'));	return false;});

function saveData(submitType){
	
	var comndata = $('#comnform').serialize();
	comndata = comndata+"&"+submitType+"=save";
	//alert(comndata);
	var saveData = $.ajax({
		  type: 'POST',
		  url: "common/addcomntdetails.php?action=addcomndetails_org",
		  data: comndata,
		 
		  dataType: "text",
		  success: function(resultData) {
			  	//$(".alertmsg").html(resultData);
		   		//alert(resultData) 
				messageAlert(resultData);
				loadConversasion($(".filter_value").val());
				
				$("#meet_note").html('');
				$("#meet_dt").html('');
				$("#meeting_place").val('');
				$("#call_note").val('');
				$("#call_dt").val('');
				$("#sms_note").val('');
				$("#sms_dt").val('');
				$("#email_note").val('');
				$("#email_dt").val('');
				$("#order_note").val('');
				$("#order_dt").val('');
				$("#payment_note").val('');
				$("#payment_dt").val('');
				$("#amount").val('');
				$("#comment_note").val('');
				$("#comment_dt").val('');
				$("#other_note").val('');
				$("#other_dt").val('');
				
				
		}
	});
	saveData.error(function() { alert("Something went wrong"); });
}




 });  
</script>
<!-- scrollbar  ==================================== -->
<script src="js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="js/plugins/lightslider/js/lightslider.min.js"></script>
<script src="js/plugins/lightslider/js/setting.js"></script>

<!-- end scrollbar  ==================================== -->
</body>
</html>

<?php }?>