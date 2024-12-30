<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$contid= $_GET['id'];
//echo $usr;die;
$currSection = 'contact';


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
$abc=0;
if($usr==''){ header("Location: ".$hostpath."/hr.php"); }
else
{
    $contctqry="SELECT  c.`id`,c.`name`,org.name `organization`,d.name `designation`,c.`phone`,c.`email` ,(case c.contacttype when 3 then 'Lead' else  tp.name end) `contacttype` FROM `contact` c left join designation d on c.designation=d.id
 left join contacttype tp on  c.contacttype=tp.id  left join organization org on org.orgcode = c.organization WHERE  c.id=".$contid;
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
                    $cid=$rowcontact["id"];$name=$rowcontact["name"]; $org=$rowcontact["organization"];  $desig=$rowcontact["designation"];$phone=$rowcontact["phone"];
                    $email=$rowcontact["email"]; $cottp=$rowcontact["contacttype"];  
                }
            }
        }
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>

<body class="form">
    <?php  include_once('common_top_body.php');?>
    <div id="wrapper"> 
    <!-- Sidebar -->
        <div id="sidebar-wrapper" class="mCustomScrollbar">
            <div class="section">
  	            <i class="fa fa-group  icon"></i>
                <span>Contact</span>
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
                            <div class="panel mother-panel panel-info">
  			                    
            		                <!--h1>&nbsp;&nbsp;Contacts <i class="fa fa-angle-right"></i><?php echo $name;?> </h1-->
            		                <input type="hidden"  name="cdid" id="cdid" value="<?php echo $cid;?>">
            		                <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
                               
				                <div class="panel-body  panel-body-padding">
                                    <span class="alertmsg"></span>
                                   <div class="row form-header"> 
                                   
	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6><a href="javascript:history.back();">Contacts</a> <i class="fa fa-angle-right"></i> <?php echo $name;?></h6>
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
                                                                <li class="active"><a href="contactDetail.php?id=<?=$id?>&mod=2"><i class="fa fa-comments-o"></i><span class="inner-tabs-title">General</span></a></li>
                                                                <li><a href="#"><i class="fa fa-file-text-o"></i><span class="inner-tabs-title">Invoices</span></a></li>
                                                                <li><a href="contactOrderHistory.php?id=<?=$id?>&mod=2"><i class="fa fa-shopping-basket"></i><span class="inner-tabs-title">Orders<span class="hidden-md hidden-xs">History</span></span></a></li>
                                                                <li><a href="#"><i class="fa fa-dollar"></i><span class="inner-tabs-title">Payment<span class="hidden-md hidden-xs">History</span></span></a></li>
                                                                <li><a href="#"><i class="fa fa-user"></i><span class="inner-tabs-title">Profiles</span></a></li>
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
                                    <div class="row  b">
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
                                            <div class="tab-content">
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
                                                                <div class="col-xs-12">  
                                                                     <input type="submit" class="btn" value="Save" name="addother"  id="addother">
                                                                     <input type="submit" class="btn" value="Cancel" name="Cancel"  id="Cancel">                                         
                                                                </div>
                                                            </div> 
                                                    </div>
                                                </div> 
                  
                                            </div>
                
                                            <div class="panel panel-default hidden-xs hidden-sm hidden-md">
                                                <div class="panel-heading">Contacts INFO</div>
                                                <div class="panel-body">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td>Position</td>
                                                            <td><?php echo $desig;?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email</td>
                                                            <td><?php echo $email;?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Phone</td>
                                                            <td><?php echo $phone;?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Company</td>
                                                            <td><?php echo $org;?></td>
                                                        </tr>
                                                    </table> 
                                                </div>
                                            </div>
                                            
                                            <!--<div class="panel panel-default hidden-xs hidden-sm hidden-md">
                                                <div class="panel-heading">Details</div>
                                                <div class="panel-body">
                                                    <table class="table table-bordered">
                                                      <tr>
                                                            <td>Contact Type</td>
                                                            <td><?php echo $cottp;?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Referred By</td>
                                                            <td>Kazi Mamun</td>
                                                        </tr>
                                                          <tr>
                                                            <td>Current Status</td>
                                                            <td>Ongoing</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Business Value</td>
                                                            <td>500000</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>    -->
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
                                            	height:500px;
                                            	}
                                            </style>
                                            
                                			<div id="history-wrapper">
                                                <div class="history-margin">
                                <?php 
                                 $msgctqry="SELECT d.`id`,t.`name` `comntp`,DATE_FORMAT(d.`comndt`,'%e/%c/%Y %h:%i:%s %p') comndt, d.`note`, d.`place`, d.`status`, d.`value`, d.`makeby` FROM `comncdetails` d,comnctype t WHERE d.`comntp`=t.`id`  and d.contactid =".$contid." and d.`comntp` in(".$fv.") and d.`comndt` between STR_TO_DATE('".$fdt."', '%d/%m/%Y') and STR_TO_DATE('".$tdt."', '%d/%m/%Y')  order by d.`comndt` desc";
                                // echo $abc;
                                // echo $msgctqry;// die;
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
<script>



// load conversasion on date change;
$(".datepicker_history_filter").on('dp.change', function(){

		//alert($(this).val());
		loadConversasion($(".filter_value").val());
});

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
	
	var sfdt = document.getElementById('filter_date_from').value;
	var stdt =  document.getElementById('filter_date_to').value;
	





	if(!sfdt){sfdt = '<?=$dateBehind?>'}
	if(!stdt){stdt = '<?=$dateAhead?>'}
	//alert(sfdt);
 	var sid = <?=$contid?>;
	//alert(sfdt);

//	var contactdata = { contid:sid,fv: sfv, fdt : '01/05/2019', tdt : '10/10/2019'}

	


	var contactdata = { contid:sid,fv: sfv, fdt : sfdt, tdt : stdt}
	
	var saveData = $.ajax({
		
		  type: 'POST',
		  url: "phpajax/load_conversasion.php?action=loadconversasion",
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
		  url: "common/addcomntdetails.php?action=addcomndetails",
		  data: comndata,
		 
		  dataType: "text",
		  success: function(resultData) {
			  	//$(".alertmsg").html(resultData);
		   		//alert(resultData) 
				messageAlert(resultData);
				loadConversasion($(".filter_value").val());
				}
	});
	saveData.error(function() { alert("Something went wrong"); });
}





</script>
<!-- scrollbar  ==================================== -->
<script src="js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="js/plugins/lightslider/js/lightslider.min.js"></script>
<script src="js/plugins/lightslider/js/setting.js"></script>

<!-- end scrollbar  ==================================== -->
</body>
</html>

<?php }?>