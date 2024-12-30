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
    
    $qryInfo = "SELECT `contact`, `address`, `details` FROM `leave` WHERE `id` = ".$id;
    $resultInfo = $conn->query($qryInfo); 
    while($rowInfo = $resultInfo->fetch_assoc()){
        $contact = $rowInfo["contact"];
        $address = $rowInfo["address"];
        $details = $rowInfo["details"];
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



<body class="form">

<?php  include_once('common_top_body.php');?>



<div id="wrapper"> 

  <!-- Sidebar -->

    <div id="sidebar-wrapper" class="mCustomScrollbar">

        <div class="section">

  	        <i class="fa fa-group  icon"></i>

            <span>Leave Approval</span>

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

                        <form method="post" action="common/actionleavehr.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->

                            <div class="panel panel-info">

      			                <div class="panel-heading"><h1>Leave Approval Information</h1></div>

				                <div class="panel-body">

                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>
                                    <div class="row">

      		                            <div class="col-sm-12">

	                                        <h4></h4>

	                                        <hr class="form-hr"> 

	                                    </div>      

      	                                <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Contact</label>

                                                <input type="text" class="form-control" value="<?php echo $contact;?>" disabled>

                                            </div>        

                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Address</label>

                                                <input type="text" class="form-control" value="<?php echo $address;?>" disabled>

                                            </div>        

                                        </div>
                                        
                                        <div class="col-lg-6 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Details</label>

                                                <input type="text" class="form-control" value="<?php echo $details;?>" disabled>

                                            </div>        

                                        </div>

                                                                

                                    <div class="col-lg-6 col-md-6 col-sm-6">

                                            <div class="form-group images-container">

                                                <label for="code">Documents</label>

                                                <?php $qryDoc = "SELECT image FROM `leave_documents` WHERE `leaveid` = ".$_GET["id"];
                                                        $resultDoc = $conn->query($qryDoc); 
                                                        while($rowDoc = $resultDoc->fetch_assoc()){
                                                            $image = $rowDoc["image"]; ?>
                                                    <img src="common/upload/leave_documents/<?php echo $image; ?>" alt="Photo" class="img-fluid">
                                                <?php } ?>

                                            </div>        

                                        </div>

                                        

                                    </div>

                                    <div class="row">

      		                            <div class="col-sm-12">

	                                        <h4></h4>

	                                        <hr class="form-hr"> 

		                                    <input type="hidden"  name="itid" id="itid" value="<?php echo $_GET["id"];?>">  

	                                    </div>      

      	                                <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="action">Action*</label>

                                                <div class="form-group styled-select">

                                                <select name="action" id="action" class="form-control" required>
                                                    <option value="">Select Action</option>
                                                    <option value="1"><?php echo "Approved"; ?></option>
                                                    <option value="0"><?php echo "Declined"; ?></option>

                                                  </select>

                                                  </div>

                                          </div>        

                                        </div>
                                                                

                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        <div class="form-group">

                                            <label for="comments">Comments </label>

                                            <textarea class="form-control" id="comments" name="comments" rows="4" ></textarea>

                                        </div>

                                    </div>

                                        

                                    </div>

                                </div>

                            </div> 

                            <!-- /#end of panel -->      

                            <div class="button-bar">

                                
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Action"  id="submit" > 
                            <a href = "./leave_hr.php?pg=1&mod=4">
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
</body>

</html>

<?php }?>