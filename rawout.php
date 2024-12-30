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
        $qry="SELECT `id`, `itemid`, `mu`, `qty`, `storeid`, `outby`, `reason`, `reference`, `trdate`, `makeby`, `makedate` FROM `rawout` where id= ".$id; 
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
                        $uid=$row["id"];$itemid=$row["itemid"];$mu=$row["mu"];
                       
                        $qty=$row["qty"];  $storeid=$row["storeid"];
                        $outby=$row["outby"]; $reason=$row["reason"]; $reference=$row["reference"];$trdate=$row["trdate"];
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
                    $uid='';$itemid='1'; $qty='0';  $storeid='1';
                        $outby='1'; $reason='1'; $reference='';$trdate='';
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rawout';
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
            <span>Item Out</span>
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
                        <form method="post" action="common/addrawout.php"  id="form1"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			               
				                
				                <div class="panel-body panel-body-padding">
				                    
				                    
                                    <span class="alertmsg"></span>
                                    
                                    
                                   <div class="row form-header"> 
                                   
	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>Products <i class="fa fa-angle-right"></i> Item Out Information</h6>
      		                            </div>
      		                            
      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>                                   
                                   
                                   
                                   </div>                                      
                                    
                                    
                                    <div class="row">
      		                            <div class="col-sm-12">
	                                        <h4></h4>
	                                        <hr class="form-hr">
		                                    <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>">  
	                                    </div>      
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbitem">Item</label>
                                                <div class="form-group styled-select">
                                                <select name="item" id="item" class="form-control">
                                                <option value="">Select Item</option>
<?php $qryitm="SELECT `id`, concat(`code`,'-',`name`)  `name`, `description`, `st` FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>                                                         
                                                    <option value="<?php echo $tid; ?>" <?php if ($itemid == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>         
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="measureUnit">Unit</label>
                                                <div class="form-group styled-select">
                                                <select name="measureUnit" id="measureUnit" class="form-control">
                                                <option value="">Select Unit</option>
<?php $qrymu="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
      { 
          $mid= $rowmu["id"];  $mnm=$rowmu["name"];
?>                                                          
                                                    <option value="<?php echo $mid; ?>" <?php if ($mu == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="qty">Quantity*</label>
                                                <input type="text" class="form-control" id="qty" name="qty" value="<?php echo $qty;?>" required>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbstore">Store From</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbstore" id="cmbstore" class="form-control">
                                                <option value="">Select Store From</option>
        <?php  $qrystore="SELECT `id`, `name` FROM `store`  order by name"; $resultstore = $conn->query($qrystore); if ($resultstore->num_rows > 0){while($rowstore = $resultstore->fetch_assoc()) 
              { 
                  $sid= $rowstore["id"]; $snm=$rowstore["name"];
        ?>          
                                                    <option value="<?php echo $sid; ?>" <?php if ($storeid == $sid) { echo "selected"; } ?>><?php echo $snm; ?></option>
        <?php  }} ?>
                                                </select>
                                                </div>
                                          </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbitmcat">Out By</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbhr" id="cmbhr" class="form-control">
                                                <option value="">Select Out By</option>
<?php $qryhr="SELECT `id`, `emp_id` FROM `hr`  order by emp_id"; $resulthr = $conn->query($qryhr); if ($resulthr->num_rows > 0) {while($rowhr = $resulthr->fetch_assoc()) 
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
                                                <label for="cmbreason">Reason</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbreason" id="cmbreason" class="form-control" >
                                                <option value="">Select Reason</option>
                                                    <option value="1" <?php if ($reason == '1') { echo "selected"; } ?>>Transfer</option>
                                                    <option value="2" <?php if ($reason == '2') { echo "selected"; } ?>>Factory</option>
                                                    <option value="9" <?php if ($reason == '9') { echo "selected"; } ?>>Others</option>
                                                </select>
                                                </div>
                                            </div>          
                                        </div> 
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Reference*</label>
                                                <input type="text" class="form-control" id="reference" name="reference" value="<?php echo $reference;?>" required>
                                            </div>        
                                        </div>
      	                                <div class="col-lg-3 col-md-6 col-sm-6">
	                                        <label for="cell">Date*</label>
                                            <div class="input-group">
                                                
                                                <input type="text" class="form-control datepicker" id="dt" name="dt" value="<?php echo $trdate;?>" required >
                                                 <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                            </div>        
                                        </div>
                                        
                                        
                                        <div class="col-sm-12">
                                            <?php if($mode==2) { ?>
                	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Item Out"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                            <?php } else {?>
                                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Item Out"  id="add" >
                                            <?php } ?>  
                                        <a href = "http://bithut.biz/BitFlow/rawoutList.php?pg=1&mod=1">
                                            <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                                        </a>                                            
                                       
                                       </div>  
                                        
                                        
                                    </div>
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
       
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