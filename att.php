<?php

require_once("common/conn.php");

session_start();

$usr=$_SESSION["user"];

if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}

else

{

    $res= $_GET['res'];

    $msg= $_GET['msg'];

    $id= $_GET['id'];







    if ($res==4)

    {

        $qry="SELECT * FROM `attendance` where id = ".$id; 

        //echo $qry; die;

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

                        $iid=$row["id"];$hrid =$row["hrid"];$atdt=$row["date"]; $intime =$row["intime"]; $outtime = $row["outtime"];
                        
                        $atdt = implode("/", array_reverse(explode("-", $atdt)));
                        
                        $intime = date("g:i A", strtotime($intime));
                        $outtime = date("g:i A", strtotime($outtime));
                        
                    }

            }

        }

    $mode=2;//update mode

    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 

    }

    else

    {

        $hrid = ''; $atdt = ''; $intime = ''; $outtime = ''; $iid = '';

    $mode=1;//Insert mode

                    

    }



    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

    */

    $currSection = 'attendance';

    $currPage = basename($_SERVER['PHP_SELF']);

?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">

<?php  include_once('common_header.php');?>



<body class="form">
    
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

<?php  include_once('common_top_body.php');?>



<div id="wrapper"> 

  <!-- Sidebar -->

    <div id="sidebar-wrapper" class="mCustomScrollbar">

        <div class="section">

  	        <i class="fa fa-group  icon"></i>

            <span>Attendance</span>

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

                        <form method="post" action="common/addattendance.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->

                            <div class="panel panel-info">

      			                <div class="panel-heading"><h1>Attendance Information</h1></div>

				                <div class="panel-body">

                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>

                                    <div class="row">

      		                            <div class="col-sm-12">

	                                        <h4></h4>

	                                        <hr class="form-hr"> 

		                                    <input type="hidden"  name="itid" id="itid" value="<?php echo $iid;?>">  

	                                    </div>      
                                        <input type = "hidden" name = "iid" value = "<?= $_GET["id"] ?>">
            	                       
            	                       <!--div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="cmbprdtp">Employee</label>

                                                <div class="form-group styled-select">

                                                <select name="emp" id="emp" class="form-control">
                                                

<?php $qryrepto="SELECT `id`, concat(`firstname`, ' ', `lastname`) empname FROM `employee` order by empname"; $resultrepto = $conn->query($qryrepto); if ($resultrepto->num_rows > 0) {while($rowrepto = $resultrepto->fetch_assoc()) 

      { 

          $empid= $rowrepto["id"];  $empnm=$rowrepto["empname"];

?>                                                          

                                                    <option value="<?php echo $empid; ?>" <?php if ($hrid == $empid) { echo "selected"; } ?>><?php echo $empnm; ?></option>

<?php  }}?>                                                       

                                                  </select>

                                                  </div>

                                          </div>        

                                        </div-->
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>"> 
                                                <label for="email">Employee Name </label>
                                                <div class="form-group styled-select">
                                                <select name="emp" id="emp" class="select2basic form-control" >
                                                    <option value="0">Select User</option>
    <?php 
    $qry1="SELECT h.`id`, concat(emp.`firstname`, ' ', emp.`lastname`) empname FROM `employee` emp LEFT JOIN hr h ON h.emp_id=emp.employeecode order by empname";
	$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
    {while($row1 = $result1->fetch_assoc()) 
          {   $tid= $row1["id"];  $nm=$row1["empname"]; 
    ?>  
													
                                                    <option value="<? echo $tid; ?>" <? if ($hrid == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php 
          }
    }      
    ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>

                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="effect_dt">Attendance Date*</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="at_dt" name="at_dt" value="<?php echo $atdt;?>" required>
                                            <div class="input-group-addon">
                                             <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="effect_dt">Start Time*</label>
                                          <div class='input-group date' id='sdatetimepicker3'>
                                               <!-- <input type="text" class="form-control timepicker" id="at_time" name="at_time" value="" required> -->
                                            <input type='text' name= "stime" value = "<?= $intime ?>" class="form-control" />
                                            <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                          </div>
                                    </div>
                                            
                                         <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="effect_dt">End Time*</label>
                                          <div class='input-group date' id='edatetimepicker3'>
                                               <!-- <input type="text" class="form-control timepicker" id="at_time" name="at_time" value="" required> -->
                                            <input type='text' name = "etime" value = "<?= $outtime ?>" class="form-control" />
                                            <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                          </div>
                                            </div>
                                            
                                        </div>     
                                    </div>

                                        

                                    </div>

                                </div>

                            </div> 

                            <!-- /#end of panel -->      

                            <div class="button-bar">

                                <?php if($mode==2) { ?>

    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Attendance"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->

                                <?php } else {?>

                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Attendance"  id="submit" >

                                <?php } ?>  
                            <a href = "./attList.php?pg=1&mod=4">
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
<?php


    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
<!--TEMPORARY TIME DATE PICKER -->
<script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
  <script
    src="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

<!-- TIME PICKER 
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/4.2.2/jquery.flot.js" integrity="sha512-GzTUEIEYsUnIsjjeFHNxX9mO4JTRcztouKrHl8ZejyU067oDfhhAd4mpOHygKkiXRuJr+AHF/v3y42Nk/LrvUw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var twelveHour = $('.timepicker-12-hr').wickedpicker();
    $('.time').text('//JS Console: ' + twelveHour.wickedpicker('time'));
    $('.timepicker-24-hr').wickedpicker({ twentyFour: true });
    $('.timepicker-12-hr-clearable').wickedpicker({ clearable: true });
  </script>  -->
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
    $(function () {
      
      $('#edatetimepicker3').datetimepicker({
        format: 'LT'
      });
      $('#edatetimepicker3').datetimepicker({
        format: 'LT'
      });
       $('#sdatetimepicker3').datetimepicker({
        format: 'LT'
      });
      $('#sdatetimepicker3').datetimepicker({
        format: 'LT'
      });
    });
  </script>
  
</body>

</html>

<?php }?>