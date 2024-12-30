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
        $qry="SELECT `id`,`code`, `name`, `image`, `makeby`, `makedt` FROM `itemtype` where id= ".$atid; 
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
                        $aid=$row["id"];$itm=$row["name"]; $aimg=$row["image"];  $code=$row["code"];
                        $photo="../assets/images/itemtype/".$aimg."?nocache=".time();
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
                        $aid='';$itm=''; $aimg=''; $code='';
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'itmtp';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<link href="js/plugins/select2/css/select2.min.css" rel="stylesheet" />

<body class="form">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Item Type </span>
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
                        <form method="post" action="common/additemtp.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Item Type Information</h1></div>
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
	                                    </div>      
            	                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Item *</label>
                                                <input type="text" class="form-control" id="itm" name="itm" value="<?php echo $itm;?>" required>
                                            </div>        
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">

                                            <strong>Item Image</strong>
    
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
                                        
                                        <div class="po-product-wrapper withlebel"> 
                                        <div class="color-block">
     		                                <div class="col-lg-12">
	                                            <h4>Attribute Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if($mode==1||$mode==5){?> 	                                        
	                                        <div class="toClone">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
													<lebel>Attribute Name</lebel>
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`  FROM `attribute`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for itemName-->  
          	                                   
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
															<lebel>Values</lebel>
                                                            <div class="form-group">
                                                                <!--input type="text" class="form-control" id="attval" placeholder="Attributr Value" name="attval[]"-->
																

																<div class="cmb-attr">
																	<input type="text"  class="form-contro">
																</div>
															
																
																
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  <!-- this block is for remarks-->
          	                                    
                                            </div>
<?php } else {
	$rCountLoop = 0;$itdgt=0;    
$itmdtqry="SELECT   a.attribute,GROUP_CONCAT(attributevalue) atv  FROM catagoryatribute a WHERE  catagory='".$code."' GROUP BY   a.catagory, a.attribute";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) {while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $itmdtid= $rowitmdt["attribute"]; $atv= $rowitmdt["atv"];
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemName-->  
                                                    <lebel>Attribute Name</lebel>
													<div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="itemName[]" id="itemName" class="form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`  FROM `attribute`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <option  value="<?php echo $tid; ?>" <?php if ($itmdtid == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                           </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for itemName-->  
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
															<lebel>Values</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="attval" placeholder="Attributr Value" name="attval[]" value="<?php echo $atv;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  <!-- this block is for remarks-->
                                               <?php
                                                if($rCountLoop>0){
												?>
                                               		<div class="remove-icon"><a href="#" class="remove-po" title="Remove "><span class="glyphicon glyphicon-remove"></span></a></div>
                                                <?php
													
												}
												$rCountLoop++;
												?>  
                                                
                                            </div>
<?php  } }
else
{
?>
                                            <div class="toClone">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
          	                                        <lebel>Attribute Name</lebel>
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Attribute</option>
    <?php $qryitm="SELECT `id`, `name`  FROM `attribute`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for itemName--> 
          	                                      
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-12 col-xs-12">
															<lebel>Values</lebel>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="attval" placeholder="Attributr Value" name="attval[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
<?php }} ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                    </div>      
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
                                    <?php
									//echo $mode;
                                    	$addClassName = ($mode=="1")?'link-add-po':'link-add-po-2';
									?>
        	                            <a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another attribute</a>
    	                            </div>
                                    </div>
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Type"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Type"  id="add" >
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
<script src="js/plugins/select2/js/select2.full.js"></script>

	<script>
	
	 var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
	
		$(document).ready(function() {
//			$('.js-example-basic-multiple').each(function(){
//				$(this).select2();
//			});
		   
		
			

			
			
			
			
	//	$(".dl-itemName").change(function(){
			
		//	alert($(this).val());
		//});
			
//			$.ajax({
//				url:"load_itemtype_attr.php",
//				method:"POST",
//				data:{product_id:product_id, product_name:product_name, product_price:product_price, product_quantity:product_quantity, action:action},
//				success:function(data)
//				{
//					load_cart_data();
//					alert("Item has been Added into Cart");
//				}
//			});			
			
			
		});
	
	</script>
	
<script language="javascript">

//load attr cmd data
function loadItemTypeAttr(id,src){
	//alert(id+' == '+src.attr('name'));
	
		$.ajax({
			url:"phpajax/load_itemtype_attr.php",
			method:"POST",
			data:{nameid:id, postaction:'loadattrval'},
			success:function(data)
			{
				//messageAlert(data)
				src.html(data);
				$(src).find('select').select2();
			}
		});		
}
	
	
	
	
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
	
	src = root.find(".cmb-attr");
	
	
	
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
			loadItemTypeAttr(id,src);
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