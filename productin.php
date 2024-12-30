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
    $id= $_GET['id'];

    if ($res==1)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }
    if ($res==2)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }
    else if ($res==3)
    {
        $qry="SELECT   `id`,`productName`,`color`, `size`,  `cost`, `pattern` FROM `product` WHERE `id`=".$id;
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
                        $productName=$row["productName"];  $color=$row["color"];  $size=$row["size"];  $pattern=$row["pattern"];
                         $cost=$row["cost"];$productid=$row["id"];;
                    }
            }
        }
                        $uid='';
                        $name='';  $moid='';
                        $factoryid='';   $qty=''; 
                        $Storeid='';  $Remarks='';  $receivedBy='';
    $mode=1;//update mode
    }

    else if ($res==4)
    {
        $qry="SELECT i.`id`, i.`moid`, i.`productid`,p.productName,p.color,p.size,p.pattern,p.cost, i.`factoryid`, `quantity`, `Storeid`, `Remarks`,`receivedBy` FROM `productIn` i,`product` p WHERE i.`productid`=p.id and i.id= ".$id; 
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
                        $uid=$row["id"];$moid=$row["moid"];
                        $name=$row["productName"];  $productid=$row["productid"];
                        $productName=$row["productName"];  $color=$row["color"];  $size=$row["size"];  $pattern=$row["pattern"];
                        $factoryid=$row["factoryid"];   $qty=$row["quantity"]; 
                        $Storeid=$row["Storeid"];  $Remarks=$row["Remarks"];  $receivedBy=$row["receivedBy"];$cost=$row["cost"];
                        
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
                        $uid='';$moid='';
                        $name='';  $productid='';
                        $productName='';  $color='';  $size='';  $pattern='';
                        $factoryid='';   $qty=''; 
                        $Storeid='';  $Remarks='';  $receivedBy='';$cost='';
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'productIn';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<script>
    
    function myFunction(selectObject) {
    var value = selectObject; 
    var ur="http://bithut.biz/oneman/productin.php?res=3&msg=NewEntry&id="+value;
     // alert(ur);
    window.location.href= ur;
   
}
</script>
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
            <span>Product In to Store</span>
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
                        <form method="post" action="common/addproductin.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Product In to Store </h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>
                                    <div class="row">
      		                            <div class="col-sm-12">
	                                        <h4></h4>
	                                        <hr class="form-hr"> 
		                                    <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>">  
	                                    </div>      
        	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcolor">Model</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbprod" id="cmbprod" class="form-control" onchange="myFunction(this.value)">
                                                <option value="">Select Model</option>
<?php $qryproduct="SELECT `id`, `modelCode` FROM `product`"; $resultproduct = $conn->query($qryproduct); if ($resultproduct->num_rows > 0) {while($rowproduct = $resultproduct->fetch_assoc()) 
      { 
          $pid= $rowproduct["id"];  $pnm=$rowproduct["modelCode"];
?>                                                          
                                                    <option value="<?php echo $pid; ?>" <?php if ($productid == $pid) { echo "selected"; } ?>><?php echo $pnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="nm">Product Name*</label>
                                                <input type="text" class="form-control" id="nm" name="nm" value="<?php echo $productName;?>" readonly>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcolor">Color</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbcolor" id="cmbcolor" class="form-control" disabled>
                                                <option value="">Select Color</option>
<?php $qrycolor="SELECT `id`, `Name`, `code` FROM `color`"; $resultcolor = $conn->query($qrycolor); if ($resultcolor->num_rows > 0) {while($rowcolor = $resultcolor->fetch_assoc()) 
      { 
          $cid= $rowcolor["id"];  $cnm=$rowcolor["Name"];
?>                                                          
                                                    <option value="<?php echo $cid; ?>" <?php if ($color == $cid) { echo "selected"; } ?>><?php echo $cnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="size">Size</label>
                                                <input type="text" class="form-control" id="size" name="size" value="<?php echo $size;?>" readonly>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cost">Cost</label>
                                                <input type="text" class="form-control" id="cost" name="cost" value="<?php echo $cost;?>" readonly>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbstyletp">Pattern</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbstyletp" id="cmbstyletp" class="form-control" disabled>
                                                <option value="">Select Pattern</option>
<?php $qrypt="SELECT `id`, `name` FROM `pattern`"; $resultpt = $conn->query($qrypt); if ($resultpt->num_rows > 0) {while($rowpt = $resultpt->fetch_assoc()) 
      { 
          $pid= $rowpt["id"];  $pnm=$rowpt["name"];
?>                                                          
                                                    <option value="<?php echo $pid; ?>" <?php if ($pattern == $pid) { echo "selected"; } ?>><?php echo $pnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbprdtp">Manufacture Order ID</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbmo" id="cmbmo" class="form-control">
                                                <option value="">Select ID</option>
<?php $qrymo="SELECT `id`, `mocode` FROM `mo`"; $resultmo = $conn->query($qrymo); if ($resultmo->num_rows > 0) {while($rowmo = $resultmo->fetch_assoc()) 
      { 
          $mid= $rowmo["id"];  $mnm=$rowmo["mocode"];
?>                                                          
                                                    <option value="<?php echo $mid; ?>" <?php if ($moid == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbprdtp">Factory</label>
                                                <div class="form-group styled-select">
                                               <select name="factory" id="factory" class="form-control">
                                               <option value="">Select Factory</option>
					 		<?php $qryf="SELECT `id`, `name` FROM `productfactory`"; $resultf = $conn->query($qryf); if ($resultf->num_rows > 0) {while($rowf = $resultf->fetch_assoc()) 
                                  { 
                                      $fid= $rowf["id"];  $fnm=$rowf["name"];
                        	?>                                                          
                            <option value="<?php echo $fid; ?>"><?php echo $fnm; ?></option>
                         <?php  }}?>                                                       
                                                      </select>
                                                      </div>
                                          </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="qty">Quantity</label>
                                                <input type="text" class="form-control" id="qty" name="qty" value="<?php echo $qty;?>">
                                            </div>        
                                        </div> 
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbitmcat">Received By</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbhr" id="cmbhr" class="form-control">
                                                <option value="">Select Received By</option>
<?php $qryhr="SELECT `id`, `emp_id` FROM `hr`"; $resulthr = $conn->query($qryhr); if ($resulthr->num_rows > 0) {while($rowhr = $resulthr->fetch_assoc()) 
      { 
          $hrid= $rowhr["id"];  $hrnm=$rowhr["emp_id"];
?>                                                          
                                                    <option value="<?php echo $hrid; ?>" <?php if ($receivedBy == $hrid) { echo "selected"; } ?>><?php echo $hrnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
          	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Store Room</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbstore" id="cmbstore" class="form-control">
                                                <option value="">Select Store Room</option>
        <?php  $qrystore="SELECT `id`, `name` FROM `store`"; $resultstore = $conn->query($qrystore); if ($resultstore->num_rows > 0){while($rowstore = $resultstore->fetch_assoc()) 
              { 
                  $sid= $rowstore["id"]; $snm=$rowstore["name"];
        ?>          
                                                    <option value="<?php echo $sid; ?>" <?php if ($Storeid == $sid) { echo "selected"; } ?>><?php echo $snm; ?></option>
        <?php  }} ?>
                                                </select>
                                                </div>
                                            </div>        
                                    </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="dimension">Remarks</label>
                                                    <input type="text" class="form-control" id="remarks" name="remarks" value="<?php echo $Remarks;?>" >
                                                </div>        
                                    </div>
                                    
                                        
                                    </div>
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Stock"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Stock"  id="submit" >
                                <?php } ?>  
                                <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
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