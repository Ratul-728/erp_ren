<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $itid= $_GET['id'];

    if ($res==1)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }
    if ($res==2)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }

    if ($res==4)
    {
        $qry="select `id`, `employeecode`, `firstname`, `lastname`, `dob`, `gender`, `maritialstatus`, `nid`, `tin`, `bloodgroup`, `pp`, `drivinglicense`, `presentaddress`, `area`, `district`, `postal`, `country`
        , `office_contact`, `ext_contact`, `pers_contact`, `alt_contact`, `office_email`, `pers_email`, `alt_email`, `emergency_poc1`, `poc1_relation`, `poc1_contact`, `poc1_address`, `emergency_poc2`, `poc2_relation`, `poc2_contact`, `poc2_address`, `emergency_poc3`, `poc3_relation`, `poc3_contact`, `poc3_address`, `photo`, `signature` FROM `employee` where id= ".$aid; 
       // echo $qry; die;
        if ($conn->connect_error)
        {
            echo "Connection failed: " . $conn->connect_error;
        }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        $acid=$row["id"];$empcode=$row["employeecode"];$firstname=$row["firstname"];  $lastname=$row["lastname"];$dob=$row["dob"];
                        $gender=$row["gender"];  $maritialstatus=$row["maritialstatus"];$nid=$row["nid"];  $tin=$row["tin"];$bg=$row["bloodgroup"]; $pp=$row["pp"];
                        $drivinglicense=$row["drivinglicense"];  $presentaddress=$row["presentaddress"];$area=$row["area"];$district=$row["district"];$country=$row["country"];
 
                        $office_contact=$row["office_contact"];  $ext_contact=$row["ext_contact"];$pers_contact=$row["pers_contact"];  $alt_contact=$row["alt_contact"];
                        $office_email=$row["office_email"]; $pers_email=$row["pers_email"]; $alt_email=$row["alt_email"];
                        $emergency_poc1=$row["emergency_poc1"];$poc1_relation=$row["poc1_relation"];$poc1_contact=$row["poc1_contact"];$poc1_address=$row["poc1_address"];
                        $emergency_poc2=$row["emergency_poc2"];$poc2_relation=$row["poc2_relation"];$poc2_contact=$row["poc2_contact"];$poc2_address=$row["poc2_address"];  
                        $emergency_poc3=$row["emergency_poc3"];$poc3_relation=$row["poc3_relation"];$poc3_contact=$row["poc3_contact"];$poc3_address=$row["poc3_address"];  
                        $photo=$row["photo"];$signature=$row["signature"]; $zip = $row["postal"];
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
                        $acid='';$empcode='';$firstname='';  $lastname='';$dob='';
        $gender='';  $maritialstatus='';$nid='';  $tin='';$bg=''; $pp='';
        $drivinglicense='';  $presentaddress='';$area='';$district='';  $photo='';$signature='';  
        $country='';
 
        $office_contact='';  $ext_contact='';$pers_contact='';  $alt_contact='';
        $office_email=''; $pers_email=''; $alt_email='';
        $emergency_poc1='';$poc1_relation='';$poc1_contact='';$poc1_address='';
        $emergency_poc2='';$poc2_relation='';$poc2_contact='';$poc2_address='';  
        $emergency_poc3='';$poc3_relation='';$poc3_contact='';$poc3_address=''; 
    $mode=1;
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'hc';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<head>
   <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<link rel="icon" href="images/favicon.png">
<title>BitFlow</title>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome4.0.7.css" rel="stylesheet">
<link href="css/fonts.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/simple-sidebar.css" rel="stylesheet">


<link href="js/plugins/scrollbar/jquery.mCustomScrollbar.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

 <link rel="stylesheet" href="css/app.css" id="maincss">



 <link href="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.css" rel="stylesheet" type="text/css"/>
 <link href="js/plugins/datepicker/datepicker-0.5.2/datepicker_style.css" rel="stylesheet" type="text/css"/>
 
    
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/ak-bit.css">
 
    <style>
    .foo {
    display: block;
    position: relative;
    width: 300px;
    margin: auto;
    cursor: pointer;
    border: 0;
    height: 60px;
    border-radius: 5px;
    outline: 0;
}
.foo:hover:after {
    background: #5978f8;
}
.foo:after {
    transition: 200ms all ease;
    border-bottom: 3px solid rgba(0,0,0,.2);
    background: #3c5ff4;
    text-shadow: 0 2px 0 rgba(0,0,0,.2);
    color: #fff;
    font-size: 20px;
    text-align: center;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: block;
    line-height: 20px;
    border-radius: 0px;
}
.overlay {
  position: absolute; 
  bottom: 10%; 
  background: rgb(0, 0, 0);
  background: rgba(0, 0, 0, 0.5); /* Black see-through */
  color: #f1f1f1; 
  width: 130px;
  transition: .5s ease;
  opacity:0;
  color: white;
  font-size: 14px;
  padding: 5px 10px;
  text-align: center;
}

.img-col:hover .overlay {
  opacity: 1;
}



input#pfile {
  opacity: 0px;
}
</style>
</head>
<body class="form">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Fund Receive Details</span>
        </div>
        <?php  include_once('menu.php');?>
	    <div style="height:54px;">
	    </div> 
    </div>
   <!-- END #sidebar-wrapper --> 
   <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12">
                    <p>&nbsp;</p> <p>&nbsp;</p>
                    <p>
                        <form method="post" action="common/addhc.php"  id="form1"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Employee Information</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    
                                    <div class="row">
                                         <div class="row pro-header">
<?php
//check file exist;
if(file_exists("common/upload/hc/$photo.jpg")){
	$photoFilePath = "common/upload/hc/$photo.jpg";
	}else{
		$photoFilePath = "images/blankuserimage.png";
		}
?>  
        <div class="col-md-2 img-col">
            <img class="pro-pic" src="<?= $photoFilePath ?>" alt="">
             <div class="overlay">
  <input type="file" name="pfile" id="pfile" class="foo">
    
  </div>
        </div>
        <div class="col pro-name-col">
            <div class="row">
                <h3 class="pro-name"><?php echo "$firstname $lastname" ?></h3>
            </div>
            <div class="row">
                <h5 class="pro-position">Manager, BitHut Corp. </h5>
            </div>
        </div>
 
 
    </div>
                                        
                                        
      		                            <div class="col-sm-12">
	                                      <!--  <h4></h4>
	                                        <hr class="form-hr"> -->
	                                        
		                                    <input type="hidden"  name="fcid" id="fcid" value="<?php echo $fcid;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
	                                    </div>      
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="cd">Date *</label>
                                            <div class="input-group">
                                                
                                                <input type="text" class="form-control datepicker" id="trdt" name="trdt" value="<?php echo $trdt;?>" required>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div>
                                         <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbinv">Invoice No</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbinv" id="cmbinv" class="cmb-parent form-control">
                                                	<option value="">Select Type</option>
													<?php $qryinv="SELECT `id`, `invoiceNo` FROM `invoice`  order by invoiceNo"; $resultinv = $conn->query($qryinv); if ($resultinv->num_rows > 0) {while($rowinv = $resultinv->fetch_assoc()){
                                                    	$tid= $rowinv["id"];  $nm=$rowinv["invoiceNo"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($inv == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                             </div>
                                          </div>         
                                        </div>       
                                           
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbmode"> Trans Mode*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbmode" id="cmbmode" class="form-control" required>
    <?php 
    $qry1="SELECT `id`, `name`  FROM `transmode` order by `name` ";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
              $tid= $row1["id"];  $nm=$row1["name"];
    ?>          
                                                <option value="<? echo $tid; ?>" <? if ($transmode == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php }}?>                    
                                            </select>
                                            </div>
                                        </div>        
                                    </div>
                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Reference/Cheque No.</label>
                                                <input type="text" class="form-control" id="ref" name="ref" value="<?php echo $transref;?>">
                                            </div>        
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="chqdt">Cheque Date </label>
                                            <div class="input-group">
                                                
                                                <input type="text" class="form-control datepicker" id="chqdt" name="chqdt" value="<?php echo $chequedt;?>">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                    </div>
                                    <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsupnm">Customer Name*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbsupnm" id="cmbsupnm" class="form-control" >
    <?php 
    $qry1="SELECT `id`, `name`  FROM `contact` where contacttype = 1 order by name";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
              $tid= $row1["id"];  $nm=$row1["name"];
    ?>          
                                                <option value="<? echo $tid; ?>" <? if ($cusid == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php }}?>                    
                                            </select>
                                            </div>
                                        </div>        
                                    </div>-->
                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmborg">Organization*</label>
                                                <div class="form-group styled-select">
                                                <select name="cmborg" id="cmborg" class="cmb-parent form-control" required>
                                                	<option value="">Select Type</option>
													<?php $qryorg="SELECT distinct o.`id`,o.`name` FROM `contact` c,`organization` o where c.`organization`=o.`orgcode`  and c.`contacttype`=1  order by o.name"; $resultorg = $conn->query($qryorg); if ($resultorg->num_rows > 0) {while($roworg = $resultorg->fetch_assoc()){
                                                    	$tid= $roworg["id"];  $nm=$roworg["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($customer == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                             </div>
                                          </div>         
                                        </div>                                        
                                        
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="amt">Amount *</label>
                                                <input type="text" class="form-control" id="amt" name="amt" value="<?php echo $amount;?>" required>
                                            </div>        
                                        </div>
                                       <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbcc"> costcenter*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbcc" id="cmbcc" class="form-control" >
    <?php 
    $qry1="SELECT `id`, `name`  FROM `costcenter` order by name ";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
              $tid= $row1["id"];  $nm=$row1["name"];
    ?>          
                                                <option value="<? echo $tid; ?>" <? if ($costcenter == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php }}?>                    
                                            </select>
                                            </div>
                                        </div>        
                                    </div> -->
                                        
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="descr">Narration</label>
                                                <input type="text" class="form-control" id="descr" name="descr" value="<?php echo $naration;?>">
                                            </div>        
                                        </div>
      	                               
                                    </div>
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Receive"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Receive"  id="add" >
                                <?php } ?>
                            <a href = "http://bithut.biz/HouseFlow/collectionList.php?pg=1&mod=3">
                                <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                            </a>
                            </div>        
                        </form>       
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
<?php    include_once('common_footer.php');?>
</body>
</html>
<?php }?>