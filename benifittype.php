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

        $qry="SELECT `id`, `title`, `benifitnature`, `benifittype`, `Description` FROM `benifitype` WHERE st = 0 and id = ".$id; 

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

                        $iid=$row["id"];$benefitnature=$row["benifitnature"];

                        $details=$row["Description"]; $title=$row["title"]; 
                        $benefittype = $row["benifittype"];

                    }

            }

        }

    $mode=2;//update mode

    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 

    }

    else

    {

                        $iid='';$title='';

                        $benefitnature='1'; $details='';

    $mode=1;//Insert mode

                    

    }



    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

    */

    $currSection = 'benifittype';

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

            <span>Benefit Type Details</span>

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

                        <form method="post" action="common/addbenefittype.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->

                            <div class="panel panel-info">

      			                <div class="panel-heading"><h1>Benefti Type Information</h1></div>

				                <div class="panel-body">

                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>

                                    <div class="row">

      		                            <div class="col-sm-12">

	                                        <h4></h4>

	                                        <hr class="form-hr"> 

		                                    <input type="hidden"  name="itid" id="itid" value="<?php echo $_GET["id"];?>">  

	                                    </div>      

            	                        <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Title*</label>

                                                <input type="text" class="form-control" id="btitle" name="btitle" value="<?php echo $title;?>" required>

                                            </div>        

                                        </div>

      	                                <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="cmbprdtp">Benefit Nature*</label>

                                                <div class="form-group styled-select">

                                                <select name="bnature" id="bnature" class="form-control">
                                                    <option value="1" <?php if ($benefitnature == "1") { echo "selected"; } ?>><?php echo "Addition"; ?></option>
                                                    <option value="2" <?php if ($benefitnature == "2") { echo "selected"; } ?>><?php echo "Deduction"; ?></option>

                                                  </select>

                                                  </div>

                                          </div>        

                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="cmbprdtp">Benefit Type*</label>

                                                <div class="form-group styled-select">

                                                <select name="btype" id="btype" class="form-control">
                                <?php $qryben="SELECT `id`, `title` FROM `pakage` order by title"; $resultben = $conn->query($qryben);
                if ($resultben->num_rows > 0) {while($rowben = $resultben->fetch_assoc()) {   $benid= $rowben["id"];  $bennm=$rowben["title"]; ?>
                                                                        <option value="<?php echo $benid; ?>" <?php if ($benefittype == $benid) { echo "selected"; } ?>><?php echo $bennm; ?></option>
                <?php  }}?>
                                                

                                                  </select>

                                                  </div>

                                          </div>        

                                        </div>
                                    

                                                                

                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        <div class="form-group">

                                            <label for="details">Details </label>

                                            <textarea class="form-control" id="bdetails" name="bdetails" rows="4" ><?php echo $details;?></textarea>

                                        </div>

                                    </div>

                                        

                                    </div>

                                </div>

                            </div> 

                            <!-- /#end of panel -->      

                            <div class="button-bar">

                                <?php if($mode==2) { ?>

    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update item"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->

                                <?php } else {?>

                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Item"  id="submit" >

                                <?php } ?>  
                            <a href = "./benifittypeList.php?pg=1&mod=4">
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