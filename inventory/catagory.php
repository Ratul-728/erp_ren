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
    $atid= $_GET['id'];

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
        $qry="SELECT `id`, `code`,`title`, `parentid`,`image`, `activest`, `makeby`, `makedt` FROM `catagory`  WHERE id=".$atid; 
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
                        $aid=$row["id"];$code=$row["code"];$cat=$row["title"];$parent=$row["parentid"];  $aimg=$row["image"];  
                          $photo="../assets/images/categories/".$aimg."?nocache=".time();
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
                        $aid='';$cat=''; $parent=''; $code='';
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'cat';
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
            <span>Product Catagory </span>
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
                        <form method="post" action="common/addcatagory.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Product Catagory</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    
                                    <div class="row">
      		                            <div class="col-sm-12">
	                                      <!--  <h4></h4>
	                                        <hr class="form-hr"> -->
	                                        
		                                    <input type="hidden"  name="atid" id="atid" value="<?php echo $aid;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
		                                    <input type="hidden"  name="cd" id="cd" value="<?php echo $code;?>">
		                                    <input type="hidden"  name="tp" id="tp" value="2">
		                                    <input type="hidden"  name="cmbpar" id="cmbpar" value="0">
	                                    </div>      
            	                        <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbinv">Parent Catagory </label>
                                                <div class="form-group styled-select">
                                                <select name="cmbpar" id="cmbpar" class="cmb-parent form-control" >
                                                	<option value="">Select Type</option>
													<?php $qryinv="SELECT `id`,title  `name` FROM `catagory` order by title"; $resultinv = $conn->query($qryinv); if ($resultinv->num_rows > 0) {while($rowinv = $resultinv->fetch_assoc()){
                                                    	$tid= $rowinv["id"];  $nm=$rowinv["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($parent == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                             </div>
                                          </div>         
                                        </div> -->      
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Catagory Title</label>
                                                <input type="text" class="form-control" id="cat" name="cat" value="<?php echo $cat;?>">
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <strong>Catagory Image</strong>
                                            <div class="input-group">
                                                <label class="input-group-btn">
                                                    <span class="btn btn-primary btn-file btn-file">
                                                        <i class="fa fa-upload"></i> <input type="file" name="attachment1" id="attachment1" style="display: none;" onchange="loadFile(event)">
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control" readonly>
                                            </div>
                                            <span class="help-block form-text text-muted">
                                                Try selecting one  files and watch the feedback
                                            </span>
                                        </div>
                                         <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="input-group">
                                              <img id="output"  width="150" alt="" src="<?php echo $photo;?>">
                                            </div>
                                        </div> 
                                        <br>
                                    </div>
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Catagory"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Catagory"  id="add" >
                                <?php } ?>  
                                <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Cancel"  id="cancel" >
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

/*  autofill combo  */

 var dataList=[];
$(".list-itemName").find("option").each(function(){dataList.push($(this).val())})

/*
//print dataList array
 $.each(dataList, function(index, value){
           $(".alertmsg").append(index + ": " + value + '<br>');
});
*/

/* Check wrong category */
var catlavel;	
var flag;
	
//$(".dl-itemName").change(function(){
$(document).on("change", ".dl-itemName", function() {
	
	
	//alert($(this).val());
	var root = $(this).parent().parent().parent().parent();
	root.find(".itemName").attr('style','border:1px solid red!important;');
	
	
	
	
	for(var i in dataList) {
		userinput = $(this).val();
	 	catlavel = dataList[i];
		
		//$(".alertmsg").append(dataList[i]+ '<br>');
		
		if(userinput === catlavel){
			flag = 1;
			
			//root.find(".itemName").val($(this).val());
			//alert($(this).attr("thisval"));
			
				var g = $(this).val();
				var id = $('#itemName option[value="' + g +'"]').attr('data-value');
			  //alert(id);
			root.find(".itemName").val(id);
			break;
		}else{
			flag = 0;
		}
	}
	if(flag == 0){
		$(this).val("");
		}
	
	});
/* end Check wrong category */	
	
/* end autofill combo  */

</script>


</body>
</html>
<?php }?>