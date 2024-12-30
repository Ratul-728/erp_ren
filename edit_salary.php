<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$com=$_SESSION["company"];

if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $msg = $_GET["msg"];
    $res = $_GET["res"];
    $id = $_GET["id"];
    
    if ($res==4)
    {
        $qry="SELECT s.salaryyear,MONTHNAME(STR_TO_DATE(s.salarymonth, '%m')) mnth,concat(e.firstname,' ',e.lastname) emp, e.employeecode empcode
                            ,s.benft_1 ,s.benft_2 ,s.benft_3 ,s.benft_4 ,s.benft_5 
                            ,s.benft_6 ,s.benft_7 ,s.benft_8 ,s.benft_9 ,s.benft_10,s.benft_11,s.privilage,s.total,s.notes,s.id
                            ,s.advance,s.loans,s.others
                            FROM monthlysalary s left JOIN hr h  ON s.hrid=h.id left join employee e on h.emp_id=e.employeecode 
                            WHERE s.id = ".$id; 
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
                        $aid=$row["id"]; $employee = $row["emp"];
                        $basic=$row["benft_1"];$house=$row["benft_2"]; $medical=$row["benft_3"];
                        $transport=$row["benft_4"];$late=$row["benft_5"]; $ait=$row["benft_11"]; $total = $row["total"];
                        $advance = $row["advance"];$loans = $row["loans"];$others= $row["others"];
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
                        $spid='';$suplierId=''; $Name='';  $address=''; $contact_no='';$email='';$web='';
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rpt_salary_sheet';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
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
            <span>Salary</span>
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
                        <form method="post" action="common/edit_salary.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Salary Information</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>
                                    <div class="row">
      		                            <div class="col-sm-12">
	                                        <h4></h4>
	                                        <hr class="form-hr">
		                                    <input type="hidden"  name="sid" id="sid" value="<?php echo $aid;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
		                  
		                                    
	                                    </div>      
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="emp">Employee</label>
                                                <input type="text" class="form-control" id="emp" name="emp" value="<?php echo $employee;?>" readonly>
                                            </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="basic">Basic</label>
                                                <input type="number" class="form-control" id="basic" name="basic" value="<?php echo $basic;?>">
                                            </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="house">House Rent</label>
                                                <input type="number" class="form-control" id="house" name="house" value="<?php echo $house;?>" readonly>
                                            </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="medical">Medical</label>
                                                <input type="number" class="form-control" id="medical" name="medical" value="<?php echo $medical;?>" readonly>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="transport">Transport Allownce</label>
                                                <input type="number" class="form-control" id="transport" name="transport" value="<?php echo $transport;?>" readonly>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="late">Late Deduction</label>
                                                <input type="number" class="form-control" id="late" name="late" value="<?php echo $late;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ait">AIT</label>
                                                <input type="number" class="form-control" id="ait" name="ait" value="<?php echo $ait;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="transport">Advance</label>
                                                <input type="number" class="form-control" id="adv" name="adv" value="<?php echo $advance;?>" >
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="late"> Loan </label>
                                                <input type="number" class="form-control" id="loan" name="loan" value="<?php echo $loans;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ait">Others</label>
                                                <input type="number" class="form-control" id="others" name="others" value="<?php echo $others;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6"> 
                                            <div class="form-group">
                                                <label for="gross">Gross Salary</label>
                                                <input type="number" class="form-control" id="gross" name="gross" value="<?php echo $total;?>" disabled>
                                            </div>        
                                        </div> 
                                    </div>
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                           <div class="button-bar">
                                
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Salary"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                
                            <a href = "./rpt_salary_sheet.php?mod=4&pg=1">
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

<script>
    $(document).on("input", "#basic, #house, #medical, #transport, #late, #ait", function() {
        var basic = parseFloat($("#basic").val()) || 0;
        var house = parseFloat($("#house").val()) || 0;
        var medical = parseFloat($("#medical").val()) || 0;
        var transport = parseFloat($("#transport").val()) || 0;
        var late = parseFloat($("#late").val()) || 0;
        var ait = parseFloat($("#ait").val()) || 0;
    
        var total = (basic + house + medical + transport) - (late + ait);
        var formattedTotal = total.toFixed(2);
        $("#gross").val(formattedTotal);
    });

</script>
</body>
</html>
<?php }?>