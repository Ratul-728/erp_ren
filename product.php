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

    if ($res==4)
    {
        $qry="SELECT `id`, `modelCode`, `productName`, `productType`, `mu`, `qty`, `color`, `size`, `style`, `rate`, `cost`, `ItemCat`, `currency`, `dimension`, `weight`, `prodPhoto`, `details`,`pattern` FROM `product` where id= ".$id; 
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
                        $prid=$row["id"];$code=$row["modelCode"];
                        $name=$row["productName"];  $productType=$row["productType"];
                        $mu=$row["mu"];   $qty=$row["qty"];  $color=$row["color"];  $size=$row["size"];  $rate=$row["rate"];  $cost=$row["cost"];  $ItemCat=$row["ItemCat"];  $currency=$row["currency"];
                        $dimension=$row["dimension"]; $weight=$row["weight"]; $prodPhoto=$row["prodPhoto"]; $details=$row["details"]; $pattern=$row["pattern"];
                        
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
                        $uid='';$code='';
                        $name='';  $productType='1';
                        $mu='1';   $qty='0';  $color='1';  $size='0';  $rate='0';  $cost='0';  $ItemCat='1';  $currency='1';
                         $dimension=''; $weight='0'; $prodPhoto=''; $details=''; $pattern=''; 
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'product';
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
            <span>Product  Details</span>
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
                        <form method="post" action="common/addproduct.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Product Information</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>
                                    <div class="row">
      		                            <div class="col-sm-12">
	                                        <h4></h4>
	                                        <hr class="form-hr"> 
		                                    <input type="hidden"  name="prid" id="prid" value="<?php echo $prid;?>">  
	                                    </div>      
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="code">Model ID *</label>
                                                <input type="text" class="form-control" id="code" name="code" value="<?php echo $code;?>" required>
                                            </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="nm">Product Name*</label>
                                                <input type="text" class="form-control" id="nm" name="nm" value="<?php echo $name;?>" required>
                                            </div>        
                                        </div>
                                      <!--  <div class="col-sm-6">
                                        <div class="form-group">
                                          	<label class="control-label" for="radio">Product Type</label>
                                         <ul class="icheck-ul">
                                                <li>
                                                  <input tabindex="3" type="radio" id="rbgoods" name="rbgoods" <?php if ($productType =='1') { echo "checked"; } ?> > &nbsp;
                                                  <label for="input-3">Goods</label>
                                                </li>
                                                <li>
                                                  <input tabindex="4" type="radio" id="rbservice" name="rbservice" <?php if ($productType =='2') { echo "checked"; } ?>> &nbsp;
                                                  <label for="input-4">Services</label>
                                                </li>
                                              </ul>
                                        </div>  
                                    </div> -->       
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbprdtp">Product Type</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbprdtp" id="cmbprdtp" class="form-control" >
                                                <option value="">Select Product Type</option>
                                                    <option value="1" <?php if ($productType == $tid) { echo "selected"; } ?>>Goods</option>
                                                    <option value="2" <?php if ($productType == $tid) { echo "selected"; } ?>>Services</option>
                                                </select>
                                                </div>
                                            </div>          
                                        </div> 
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbprdtp">Unit</label>
                                                <div class="form-group styled-select">
                                                <select name="measureUnit" id="measureUnit" class="form-control">
<?php $qrymu="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
      { 
          $mid= $rowmu["id"];  $mnm=$rowmu["name"];
?>                                                          
                                                    <option value="<?php echo $mid; ?>" <?php if ($mu == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
      	                                <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="qty">Quantity</label>
                                                <input type="text" class="form-control" id="qty" name="qty" value="<?php echo $qty;?>">
                                            </div>        
                                        </div> -->
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcolor">Color</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbcolor" id="cmbcolor" class="form-control">
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
                                                <input type="text" class="form-control" id="size" name="size" value="<?php echo $size;?>" >
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbstyletp">Pattern</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbstyletp" id="cmbstyletp" class="form-control">
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
                                                <label for="rate">Rate</label>
                                                <input type="text" class="form-control" id="rate" name="rate" value="<?php echo $rate;?>" >
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cost">Cost</label>
                                                <input type="text" class="form-control" id="cost" name="cost" value="<?php echo $cost;?>" >
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbitmcat">Item Category</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbitmcat" id="cmbitmcat" class="form-control">
                                                <option value="">Select Category</option>
<?php $qryitmct="SELECT `id`, `name` FROM `itmCat`"; $resultitmct = $conn->query($qryitmct); if ($resultitmct->num_rows > 0) {while($rowitmct = $resultitmct->fetch_assoc()) 
      { 
          $icid= $rowitmct["id"];  $icnm=$rowitmct["name"];
?>                                                          
                                                    <option value="<?php echo $icid; ?>" <?php if ($ItemCat == $icid) { echo "selected"; } ?>><?php echo $icnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
          	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Currency</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbcur" id="cmbcur" class="form-control">
                                                <option value="">Select Currency</option>
        <?php  $qrycur="SELECT `id`, `name`, `shnm` FROM `currency`"; $resultcur = $conn->query($qrycur); if ($resultcur->num_rows > 0){while($rowcur = $resultcur->fetch_assoc()) 
              { 
                  $cid= $rowcur["id"]; $cnm=$rowcur["shnm"];
        ?>          
                                                    <option value="<?php echo $cid; ?>" <?php if ($currency == $cid) { echo "selected"; } ?>><?php echo $cnm; ?></option>
        <?php  }} ?>
                                                </select>
                                                </div>
                                            </div>        
                                    </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="dimension">Dimension</label>
                                                    <input type="text" class="form-control" id="dimesion" name="dimesion" value="<?php echo $dimension;?>" >
                                                </div>        
                                    </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="weight">weight</label>
                                                <input type="text" class="form-control" id="weight" name="weight" value="<?php echo $weight;?>" >
                                            </div>        
                                    </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                        <strong>Product Image</strong>
                                        <div class="input-group">
                                            <label class="input-group-btn">
                                                <span class="btn btn-primary btn-file btn-file">
                                                    <i class="fa fa-upload"></i> <input type="file" name="attachment1" id="attachment1" style="display: none;" >
                                                </span>
                                            </label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                        <span class="help-block form-text text-muted">
                                            Try selecting one or more files and watch the feedback
                                        </span>
                                    </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="details">Details </label>
                                            <textarea class="form-control" id="details" name="details" rows="4" ><?php echo $details;?></textarea>
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
    <?php $qryitm="SELECT `id`, `name`, `description`, `st` FROM `item` WHERE st=1"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </select> 
                                                            </div>
                                                        </div>        
                                                    </div>
          	                                        <div class="col-lg-3 col-md-6 col-sm-6">
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
                                                            <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`, `description`, `st` FROM `item` WHERE st=1"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <option value="<?php echo $tid; ?>" <?php if ($itmmnm == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </select> 
                                                            </div>
                                                        </div>        
                                                    </div>
          	                                        <div class="col-lg-3 col-md-6 col-sm-6">
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
                                    </div>
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Product"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Product"  id="submit" >
                                <?php } ?>  
                            <a href = "http://bithut.biz/BitFlow/productList.php?pg=1&mod=1">
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