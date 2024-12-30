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
    $aid= $_GET['id'];

//   print_r($_SESSION);die;

    if ($res==4)
    {
        $qry="SELECT `id`, `hrid`, `menuid`, `menu_priv` FROM `hrAuth` where id= ".$aid; 
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
                       $uid=$row["id"];$hrid=$row["hrid"];
                        $menuid=$row["menuid"]; $menu_priv=$row["menu_priv"];  
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
        $uid='';$hrid='0'; $menuid='0'; $menu_priv='0'; 
    $mode=1;//Insert mode
                     
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'ch_pass';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    /*if ( isset( $_POST['submit'] ) ) {
           header("Location: ".$hostpath."/common/addpriv.php");
    }*/
    $mnhrid = $_POST['cmbempnm'];
    if($mnhrid==''){$mnhrid=$hrid;}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>

<body class="form">
<?php  include_once('common_top_body.php');?>
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

        .error {
            color: red;
            display: none;
        }
        .success {
            color: green;
            display: none;
        }
    </style>
<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
            <i class="fa fa-group  icon"></i>
            <span>Data Process</span>
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
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
                    <!-- START PLACING YOUR CONTENT HERE -->
                        <form method="post" action="common/resend_char.php"  id="form1">     
                            <div class="panel panel-info">
                                <div class="panel-heading"><h1>Change Password</h1></div>
                                <div class="panel-body">
                                    <span class="alertmsg"> 

                                    <div id="passwordError" class="error"><strong>Password does not meet the requirements.</strong></div>
                                    <div id="passwordSuccess" class="success">Password is valid!</div>
                                    
                                    <div id="passwordHints" class="error">
                                        <br>
                                        <strong>Password policy hints:</strong>
                                        <ul>
                                            <li>At least 8 characters</li>
                                            <li>At least 1 lowercase character</li>
                                            <li>At least 1 uppercase character</li>
                                            <li>At least 1 number</li>
                                            <li>At least 1 special character</li>
                                        </ul>
                                    </div>
                                   


                                    </span>
                                    <br>
      	                            <p>(Field Marked * are required) </p>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>"> 
                                                <label for="email">Employee Name </label>
                                                <div class="form-group styled-select">
                                                    <?php
                                                    if($_SESSION['usertype'] == 1 || $_SESSION['username'] == 'EMP-000087'){
                                                    ?>
                                                <select name="cmbempnm" id="cmbempnm" class="select2basic form-control" >
                                                    <option value="0">Select User</option>
                                                        <?php 
                                                        $qry1="SELECT h.id, concat(emp.firstname, ' ', emp.lastname) empnm, h.emp_id
                                                                FROM `employee` emp LEFT JOIN hr h ON h.emp_id=emp.employeecode";
                                                        $result1 = $conn->query($qry1); if ($result1->num_rows > 0)
                                                        {
                                                            while($row1 = $result1->fetch_assoc())
                                                            {   $tid= $row1["id"];  $nm=$row1["empnm"]; $code = $row1["emp_id"]; 
                                                                $nm = $nm.' ('.$code.')';
                                                        ?>  
                                                                                                        
                                                              <option value="<? echo $tid; ?>" <? if ($_SESSION['user'] == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
                                                        <?php 
                                                            }
                                                        }      
                                                        ?>   
                                                </select>
                                                <?php
                                                    }else{
                                                        ?>
                                                        <input type="hidden" class="form-control"  name="cmbempnm" value="<?php echo $_SESSION['user'];?>">
                                                        <div style="padding: 7px;"><?=$_SESSION['empname']?></div> 
                                                        <?php
                                                    }
                                                ?>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="np">New Password*</label>
                                                <input type="password" class="form-control" id="np" name="np"  required>
                                            </div>        
                                        </div>
                                         <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cnp">Confirm Password*</label>
                                                <input type="password" class="form-control" id="cnp" name="cnp"  required>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                              
                                            <div class="form-group">  
                                                <label style="margin-top: 32px;" for="button"> &nbsp;</label>
                                                <input class="btn btn-lg btn-default form-control" disabled type="submit" name="add" value="Change"  id="add" > 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>    
                        
                        <!-- START PLACING YOUR CONTENT HERE -->          
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


<script>
    $(document).ready(function() {
        $('#np').on('input', function(event) {
            event.preventDefault();

            var password = $('#np').val();
            var passwordError = $('#passwordError');
            var passwordSuccess = $('#passwordSuccess');
            var passwordHints = $('#passwordHints');
            
            

            // Regular expression for the password policy
            var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (passwordPattern.test(password)) {
                passwordError.hide();
                passwordSuccess.show();
                passwordHints.hide();
                
                //submitButton.prop('disabled', false);
            } else {
                passwordSuccess.hide();
                passwordError.show();
                passwordHints.show();
                //submitButton.prop('disabled', true);
            }

            
            

        });

        $('#cnp').on('input', function(event) {

            var confirmPassword = $('#cnp').val();
            var password = $('#np').val();
            var submitButton = $('#add');
            
            var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (passwordPattern.test(password) && password === confirmPassword) {
                submitButton.prop('disabled', false);
            } else {
                submitButton.prop('disabled', true);
            }

        });

    });
</script>





</body>
</html>
<?php }?>