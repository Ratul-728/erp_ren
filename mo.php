<?php

require "common/conn.php";

session_start();

$usr=$_SESSION["user"];

//echo $usr;die;

if($usr=='')

{

  header("Location: ".$hostpath."/mo.php");

}

else

{

    $res= $_GET['res'];

    $msg= $_GET['msg'];

    $id= $_GET['id'];

    $serno= $_GET['id'];

    $totamount=0;

    

   if ($res==1)

    {

        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 

         $mode=1;

    }

    else if ($res==2)

    {

        echo "<script type='text/javascript'>alert('".$msg."')</script>";

         $mode=1;

    }

    else if ($res==4)

    {

    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 

    $qry="SELECT `id`, `mocode`, `deliverydt`, `attachement`, `makeby`, `makedt` FROM `mo`  where  id= ".$id; 

    //echo $qry; die;

        if ($conn->connect_error) {

            echo "Connection failed: " . $conn->connect_error;

            }

        else

            {

                $result = $conn->query($qry); 

                if ($result->num_rows > 0)

                {

                    while($row = $result->fetch_assoc()) 

                        { 

                            $uid=$row["id"];$moid=$row["mocode"]; $delivery_dt=date("Y-m-d", strtotime($row["deliverydt"]));

                           $hrid='1';

                        }

                }

            }

    $mode=2;//update mode

    //echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 

    }

    else

    {

                            $uid='';$moid=''; $delivery_dt=date("Y-m-d");$hrid='1';

                            

    $mode=1;//Insert mode

                        

    }

    

    $currSection = 'mo';

    $currPage = basename($_SERVER['PHP_SELF']);

?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">

<?php

     include_once('common_header.php');

?>

<body class="form">

    

<?php

    include_once('common_top_body.php');

?>



<div id="wrapper"> 

    <!-- Sidebar -->

    <div id="sidebar-wrapper" class="mCustomScrollbar">

        <div class="section">

  	        <i class="fa fa-group  icon"></i>

            <span>Manufacture Order</span>

        </div>

        <?php include_once('menu.php'); ?>

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

                       <form method="post" action="common/addmo.php" id="form1" enctype="multipart/form-data">  

                    <!-- START PLACING YOUR CONTENT HERE -->

                    <div class="panel panel-info">

      		            <div class="panel-heading"><h1>Add New MO</h1></div>

			            <div class="panel-body">

                            <span class="alertmsg"></span>

                            <br>

                          	<p>(Field Marked * are required) </p>

     	                   

                                <div class="row">

                            		<div class="col-sm-12">

	                                    <h4>MO Information</h4>

		                                <hr class="form-hr">

		                                 <input type="hidden"  name="mid" id="mid" value="<?php echo $uid;?>"> 
		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">

    	                            </div> 

	                                

	                                <div class="col-lg-3 col-md-6 col-sm-6">

                                        <div class="form-group">

                                            <label for="mo_id">MO Code*</label>

                                            <input type="text" class="form-control" name="mo_id" id="mo_id" value="<?php echo $moid;?>" required>

                                        </div>        

                                    </div>

      

      	                          

                            	    <br>

                                    <div class="po-product-wrapper"> 

                                        <div class="color-block">

     		                                <div class="col-sm-12">

	                                            <h4>Item Information  </h4>

		                                        <hr class="form-hr">

	                                        </div>

<?php if($mode==1){?> 	                                        

	                                        <div class="toClone">

          	                                    <div class="col-lg-3 col-md-6 col-sm-6">

                                                    <div class="form-group">

                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->

                                                        <div class="form-group styled-select">
                                                        <select name="itemName[]" id="itemName" class="form-control">
                                                        <option value="">Select Item</option>

    <?php $qryitm="SELECT `id`, concat(`modelCode`, '-',`productName`)   name FROM `product`   order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 

              { 

                  $tid= $rowitm["id"];  $nm=$rowitm["name"];

    ?>

                                                            <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>

    <?php  }}?>                    

                                                        </select> 
                                                        </div>

                                                    </div>        

                                                </div>

                                                

                                                <div class="col-lg-2 col-md-6 col-sm-6">

                                                  <div class="form-group">

                                                      <div class="form-group styled-select">
                                                      <select name="factory[]" id="factory" class="form-control">
                                                      <option value="">Select Factory</option>

 <?php $qryf="SELECT `id`, `name` FROM `productfactory`  order by name"; $resultf = $conn->query($qryf); if ($resultf->num_rows > 0) {while($rowf = $resultf->fetch_assoc()) 

              { 

                  $fid= $rowf["id"];  $fnm=$rowf["name"];

    ?>                                                          

                                                        <option value="<?php echo $fid; ?>"><?php echo $fnm; ?></option>

     <?php  }}?>                                                       

                                                      </select>
                                                      </div>

                                                  </div>        

                                                </div>

                                                

                                                <div class="col-lg-2 col-md-6 col-sm-6">

                                                  <div class="form-group">

                                                      <div class="form-group styled-select">
                                                      <select name="color[]" id="color" class="form-control">

 <?php $qrycl="SELECT `id`, `Name`, `code` FROM `color`  order by name"; $resultcl = $conn->query($qrycl); if ($resultcl->num_rows > 0) {while($rowcl = $resultcl->fetch_assoc()) 

              { 

                  $clid= $rowcl["id"];  $clnm=$rowcl["Name"];

    ?>                                                          

                                                        <option value="<?php echo $clid; ?>"><?php echo $clnm; ?></option>

     <?php  }}?>                                                       

                                                      </select>
                                                      </div>

                                                  </div>        

                                                </div>

                                                

                                                <div class="col-lg-1 col-md-6 col-sm-6">

                                                    <div class="form-group">

                                                        <input type="text" class="form-control" id="size" placeholder="Size " name="size[]">

                                                    </div>        

                                                </div>   

          	                                    <div class="col-lg-2 col-md-6 col-sm-6">

                                                  <div class="form-group">

                                                      <div class="form-group styled-select">
                                                      <select name="measureUnit[]" id="measureUnit" class="form-control">

 <?php $qrymu="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 

              { 

                  $mid= $rowmu["id"];  $mnm=$rowmu["name"];

    ?>                                                          

                                                        <option value="<?php echo $mid; ?>"><?php echo $mnm; ?></option>

     <?php  }}?>                                                       

                                                      </select>
                                                      </div>

                                                  </div>        

                                                </div>

          	                                    <div class="col-lg-2 col-md-6 col-sm-6">

                                                    <div class="form-group">

                                                        <input type="text" class="form-control" id="quantity" placeholder="Quantity" name="quantity[]">

                                                    </div>        

                                                </div>

                                            </div>

<?php } else {?>                                            

                                            <!-- this block is for php loop, please place below code your loop  -->   

                                          <div class="toClone">

          	                                    <div class="col-lg-3 col-md-6 col-sm-6">

                                                    <div class="form-group">

                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->

                                                        <div class="form-group styled-select">
                                                        <select name="itemName[]" id="itemName" class="form-control">

    <?php $qryitm="SELECT `id`, concat(`modelCode`, '-',`productName`)   name FROM `product`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 

              { 

                  $tid= $rowitm["id"];  $nm=$rowitm["name"];

    ?>

                                                            <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>

    <?php  }}?>                    

                                                        </select> 
                                                        </div>

                                                    </div>        

                                                </div>

                                                

                                                <div class="col-lg-2 col-md-6 col-sm-6">

                                                  <div class="form-group">

                                                      <div class="form-group styled-select">
                                                      <select name="factory[]" id="factory" class="form-control">

 <?php $qryf="SELECT `id`, `name` FROM `productfactory`  order by name"; $resultf = $conn->query($qryf); if ($resultf->num_rows > 0) {while($rowf = $resultf->fetch_assoc()) 

              { 

                  $fid= $rowf["id"];  $fnm=$rowf["name"];

    ?>                                                          

                                                        <option value="<?php echo $fid; ?>"><?php echo $fnm; ?></option>

     <?php  }}?>                                                       

                                                      </select>
                                                      </div>

                                                  </div>        

                                                </div>

                                                

                                                <div class="col-lg-2 col-md-6 col-sm-6">

                                                  <div class="form-group">

                                                      <div class="form-group styled-select">
                                                      <select name="color[]" id="color" class="form-control">

 <?php $qrycl="SELECT `id`, `Name`, `code` FROM `color`  order by name"; $resultcl = $conn->query($qrycl); if ($resultcl->num_rows > 0) {while($rowcl = $resultcl->fetch_assoc()) 

              { 

                  $clid= $rowcl["id"];  $clnm=$rowcl["code"];

    ?>                                                          

                                                        <option value="<?php echo $clid; ?>"><?php echo "v"; ?></option>

     <?php  }}?>                                                       

                                                      </select>
                                                      </div>

                                                  </div>        

                                                </div>

                                                

                                                <div class="col-lg-2 col-md-6 col-sm-6">

                                                    <div class="form-group">

                                                        <input type="text" class="form-control" id="size" placeholder="Size " name="size[]">

                                                    </div>        

                                                </div>   

          	                                    <div class="col-lg-2 col-md-6 col-sm-6">

                                                  <div class="form-group">

                                                      <div class="form-group styled-select">
                                                      <select name="measureUnit[]" id="measureUnit" class="form-control">

 <?php $qrymu="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 

              { 

                  $mid= $rowmu["id"];  $mnm=$rowmu["name"];

    ?>                                                          

                                                        <option value="<?php echo $mid; ?>"><?php echo $mnm; ?></option>

     <?php  }}?>                                                       

                                                      </select>
                                                      </div>

                                                  </div>        

                                                </div>

          	                                    <div class="col-lg-2 col-md-6 col-sm-6">

                                                    <div class="form-group">

                                                        <input type="text" class="form-control" id="quantity" placeholder="Quantity" name="quantity[]">

                                                    </div>        

                                                </div>

                                            </div>

<?php } ?>                                     		

                                    		<!-- this block is for php loop, please place below code your loop  --> 

                                        </div>

                                    </div>      

                                    &nbsp;

                                    <div class="col-sm-12">

        	                            <a href="#" class="link-add-po" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>

    	                            </div>

                                    <br><br>&nbsp;<br><br>

        

	                                <div class="col-lg-3 col-md-6 col-sm-6">

	                                    <label for="email">Delivery Date</label>

                                        <div class="input-group">

                                            <input type="text" class="form-control datepicker" id="delivery_dt" name="delivery_dt" value="<?php echo $delivery_dt;?>" required>

                                            <div class="input-group-addon">

                                                <span class="glyphicon glyphicon-th"></span>

                                            </div>

                                        </div>     

                                    </div>        

        

	                                <div class="col-lg-3 col-md-6 col-sm-12">

                                        <strong>Attachment Voucher</strong>

                                        <div class="input-group">

                                            <label class="input-group-btn">

                                                <span class="btn btn-primary btn-file btn-file">

                                                    <i class="fa fa-upload"></i> <input type="file" name="attachment1[]" id="attachment1" style="display: none;" multiple>

                                                </span>

                                            </label>

                                            <input type="text" class="form-control" readonly>

                                        </div>

                                        <span class="help-block form-text text-muted"> Try selecting one or more files and watch the feedback </span>

                                    </div>

        

	                                                        

                                    <br><br>&nbsp;<br><br>&nbsp;<br><br><br>

                                </div>

                           

                        </div>

                    </div> 

        <!-- /#end of panel -->      

                    <div class="button-bar">

                            <?php if($mode==2) { ?>

                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update MO" id="update" >

                          <?php } else {?>

                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add MO" id="add" >

                          <?php } ?>           
                        <a href = "http://bithut.biz/BitFlow/moList.php?pg=1&mod=1">
                          <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                        </a>

                    </div>        

          <!-- START PLACING YOUR CONTENT HERE --> 

           </form>

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- /#page-content-wrapper -->



<?php

include_once('common_footer.php');

?>



</body>

</html>

<?php }?>