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

    $sid= $_GET['id'];
    
    $active = 1;


    if ($res==4)

    {

        $qry="SELECT * FROM `serviceitem` WHERE id = ".$sid; 

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

                        $code=$row["code"];
                        $name=$row["name"];
                        $vat = $row["vat"];
                        $tax = $row["tax"];

                    }

            }

        }

    $mode=2;//update mode

    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 

    }

    else

    {
                   $details=''; $title=''; 
    $mode=1;//Insert mode

                    

    }



    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

    */

    $currSection = 'serviceitem';

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

            <span>Service Item Details</span>

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

                        <form method="post" action="common/addservice_item.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->

                            <div class="panel panel-info">

      			                <div class="panel-heading"><h1>Service Item Information</h1></div>

				                <div class="panel-body">

                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>

                                    <div class="row">

            	                        <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Service Code*</label>
                                                <input type ="hidden" name = "iid" value = "<?= $sid ?>">

                                                <input type="text" class="form-control" id="code" name="code" value="<?php echo $code;?>" readonly>

                                            </div>        

                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Service Name<span class="redstar">*</span></label>

                                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name;?>" required>

                                            </div>        

                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Vat(%)</label>

                                                <input type="text" class="form-control" id="vat" name="vat" value="<?php echo $vat;?>" >

                                            </div>        

                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Tax(%)</label>

                                                <input type="text" class="form-control" id="tax" name="tax" value="<?php echo $tax;?>">

                                            </div>        

                                        </div>
                                                                



                                    </div>

                                </div>

                            </div> 

                            <!-- /#end of panel -->      

                            <div class="button-bar">

                                <?php if($mode==2) { ?>

    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Service Item"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->

                                <?php } else {?>

                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Service Item"  id="submit" >

                                <?php } ?>  
                            <a href = "./service_itemList.php?pg=1&mod=22">
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