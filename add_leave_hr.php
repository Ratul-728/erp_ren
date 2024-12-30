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

        $qry="SELECT `ID` id, `title`,`day`, `remarks`, paid FROM `leaveType` WHERE id = ".$id; 

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

                        $title=$row["title"];
                        $day = $row["day"];
                        $details=$row["remarks"];
                        $paid = $row["paid"];
                    }

            }

        }

    $mode=2;//update mode

    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 

    }

    else

    {
                   $details=''; $title=''; $day = '';$paid = 1;
    $mode=1;//Insert mode

                    

    }



    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

    */

    $currSection = 'leave_hr';

    $currPage = basename($_SERVER['PHP_SELF']);

?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">

<?php  include_once('common_header.php');?>

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

<body class="form">

<?php  include_once('common_top_body.php');?>



<div id="wrapper"> 

  <!-- Sidebar -->

    <div id="sidebar-wrapper" class="mCustomScrollbar">

        <div class="section">

  	        <i class="fa fa-group  icon"></i>

            <span>Apply Leave</span>

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

                        <form method="post" action="common/applyleave.php?type=2"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->

                            <div class="panel panel-info">

      			                <div class="panel-heading"><h1>Leave Information</h1></div>

				                <div class="panel-body">

                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>

                                    <div class="row">
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>"> 
                                                <label for="email">Employee Name </label>
                                                <div class="form-group styled-select">
                                                <select name="cmbempnm" id="cmbempnm" class="select2basic form-control" >
                                                    <option value="0">Select User</option>
    <?php 
    $qry1="SELECT `id`, CONCAT(`hrName`,' - (',`resourse_id`,')') hrName FROM `hr` ";
	$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
    {while($row1 = $result1->fetch_assoc()) 
          {   $tid= $row1["id"];  $nm=$row1["hrName"]; 
    ?>  
													
                                                    <option value="<? echo $tid; ?>" <? if ($mnhrid == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php 
          }
    }      
    ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
            	                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="leavetype">Leave Type</label>
                                            <select class="form-select form-select-sm form-control" aria-label=".form-select-sm example" name = "leavetype" id = "leavetype" required>
                                              <option value="">Leave Type</option>
                                    <?php
                                    $qry1    = "SELECT `id`, `title` FROM `leaveType` WHERE st = 1 ORDER BY `title` DESC";
                                    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
                                        $tid = $row1["id"];
                                        $nm  = $row1["title"];
                                        ?>
                                            <option value="<?echo $tid; ?>" <?if ($cusid == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
                                    <?php }} ?>
                                    
                                            </select>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>"> 
                                                <label for="reliver">Reliver </label>
                                                <div class="form-group styled-select">
                                                <select name="reliver" id="reliver" class="select2basic form-control" >
                                                    <option value="0">Select Reliver</option>
                                                    <?php 
                                        $qry1="SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id` FROM `employee` e LEFT JOIN `hr` h ON h.`emp_id`=e.`employeecode` order by emp_id";
                                    	$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
                                        {while($row1 = $result1->fetch_assoc()) 
                                              {   $tid= $row1["id"];  $nm=$row1["emp_id"]; 
                                        ?>  
                                    													
                                                        <option value="<? echo $tid; ?>"><? echo $nm; ?></option>
                                        <?php 
                                              }
                                        }      
                                        ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>

                                            
                                            
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <label for="cmdt">Contact</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="contactno" name="contactno" value="" Placeholder = "Contact No" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <label for="cmdt">Address</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="address" name="address" value="" Placeholder = "Address" required>
                                                </div>
                                            </div>
                                    
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <label for="cmdt">Start Date*</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" id="startdt" name="startdt" value=""  required>
                                                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="cmdt">End Date*</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" id="enddt" name="enddt" value="" required>
                                                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                                </div>
                                            </div>       

                                        </div>
                                                                
<div class="row">
        <textarea class="form-control" id="w3review" name="w3review" rows="4" cols="50" required></textarea>
        
        <div class="col-lg-4 col-md-6 col-sm-6">
        <label for="address">Upload Documents</label>
            <div class="input-group upload-group">
                                <label class="input-group-btn">
                                    <span class="btn btn-upload btn-primary btn-file btn-file">
                                       <i class="fa fa-paperclip"></i> <input type="file" name="uploaddocument[]" id="fileUpload" style="display: none;" multiple="">
                                    </span>
                                </label>
                                <input type="text" class="form-control" id="filetxt" readonly="">
                                
                               
                            </div>
        </div>
    </div>    

                                    </div>

                                </div>

                            </div> 

                            <!-- /#end of panel -->      

                            <div class="button-bar">

                                <?php if($mode==2) { ?>

    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Leave Type"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->

                                <?php } else {?>

                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Apply Leave"  id="submit" >

                                <?php } ?>  
                            <a href = "./leavetypeList.php?pg=1&mod=4">
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

</body>

</html>

<?php }?>