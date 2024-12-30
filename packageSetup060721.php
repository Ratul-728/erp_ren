<?php
//print_r($_REQUEST);
//exit();

require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{
  header("Location: ".$hostpath."/hr.php");
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $id= $_GET['id'];
    $serno= $_GET['id'];
    $totamount=0;
    
    if ($res==4)
    {
    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
    $qry="SELECT `id`, `Title`, `pakage`, `scale`, `benifittp`, `befitamount`, `isPercentage`, `Description`, `cycle` FROM `pakageSetup` WHERE id = ".$id; 
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
                            $title = $row["Title"]; $grade = $row["scale"]; $package = $row["pakage"];
                            $cycle = $row["cycle"]; $ben = $row["benifittp"]; $bamount = $row["befitamount"];
                            $per = $row["isPercentage"]; $details = $row["Description"];
                        }
                }
            }
    $mode=2;//update mode
   // echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    else
    {
        $title = ''; $grade = ''; $package = '';
        $cycle = ''; $ben = ''; $bamount = '';
        $per = ''; $details = '';                    
    $mode=1;//Insert mode
                        
    }
    
    $currSection = 'packagesetup';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
     include_once('common_header.php');
?>
<body class="form soitem">
    
<?php
    include_once('common_top_body.php');
?>

<div id="wrapper"> 
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Package Setup</span>
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
                       <form method="post" action="common/addpackageSetup.php" id="form1" enctype="multipart/form-data">  
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->  
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">
                        
                        
      		            
      		            
			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                            
                                   <div class="row form-header"> 
                                   
	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>HRM <i class="fa fa-angle-right"></i> Add Package Setup</h6>
      		                            </div>
      		                            
      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>                                   
                                   
                                   
                                   </div>                             
                            
                            
                            
                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->
     	                   
                                <div class="row">
                            	    <div class="col-sm-12">
	                                    <!-- <h4>SO Information</h4>
		                                <hr class="form-hr"> -->
		                                
		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno;?>"> 
		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
    	                            </div> 
                                    
	                                <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="po_id">Title*</label>
                                            <input type="text" class="form-control" name="title" id="title" value="<?php echo $title;?>" required>
                                        </div>        
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcontype">Grade*</label>
                                                <div class="form-group styled-select">
                                                <select name="grade" id="grade" class="cmb -parent form-control" required>
                                                    
											<?php $qrycntp="SELECT `id`, `title` FROM `compansationSetup`  order by id"; $resultcntp = $conn->query($qrycntp); if ($resultcntp->num_rows > 0) {while($rowcntp = $resultcntp->fetch_assoc()){
                                                    $tid= $rowcntp["id"];  $nm=$rowcntp["title"];
                                            ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($grade == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                            </div>
                                        </div>         
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmborg">Package</label>
                                                <div class="form-group styled-select">
                                                <select name="package" id="package" class="cmb-parent form-control">
													<?php $qryorg="SELECT `id`,`title` FROM `pakage`  order by id"; $resultorg = $conn->query($qryorg); if ($resultorg->num_rows > 0) {while($roworg = $resultorg->fetch_assoc()){
                                                    	$tid= $roworg["id"];  $nm=$roworg["title"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($package == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                             </div>
                                          </div>         
                                        </div>  
      	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsupnm">Cycle*</label>
                                            <div class="form-group styled-select">
                                            <select name="cycle" id="cycle" class="form-control">
                                                    <option value="1" <?php if ($cycle == 1) { echo "selected"; } ?>>Monthly</option>
                                                    <option value="2" <?php if ($cycle == 2) { echo "selected"; } ?>>Daily</option>
                                                    <option value="3" <?php if ($cycle == 3) { echo "selected"; } ?>>Quarterly</option>
                                                    <option value="4" <?php if ($cycle == 4) { echo "selected"; } ?>>Yearly</option>

                                                  </select>
                                            </div>
                                        </div>        
                                    </div>
<?php if($mode==1){?> 
                                    <div class="po-product-wrapper withlebel"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Benefit Information </h4>
		                                        <hr class="form-hr">
	                                        </div>
	                                        
	                               <div class="toClone">
          	                             <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="cmbitmcat">Benefit Type*</label>

                                                <div class="form-group styled-select">

                                                <select name="ben[]" id="ben" class="form-control">
                                                

<?php $qryben="SELECT `id`, `title` FROM `benifitype` order by id"; $resultben = $conn->query($qryben); if ($resultben->num_rows > 0) {while($rowben = $resultben->fetch_assoc()) 

      { 

          $benid= $rowben["id"];  $bennm=$rowben["title"];

?>                                                          

                                                    <option value="<?php echo $benid; ?>" <?php if ($ben == $benid) { echo "selected"; } ?>><?php echo $bennm; ?></option>

<?php  }}?>                                                       

                                                  </select>

                                                  </div>

                                          </div>        

                                        </div> <!-- this block is for Benefit Type--> 
                                        
        
                                        
          	                            <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Benefit Amount*</label>

                                                <input type="number" step = "any" class="form-control" id="bamount" name="bamount[]" value="<?php echo $bamount;?>" required>

                                            </div>        

                                        </div> <!-- this block is for Benefit Amount--> 
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
												<lebel>Percentage?</lebel>
                                                <input type="checkbox" id="per" name="per[]" value = "1">
                                            </div>
                                        </div> <!-- this block is for quantity_otc, unitprice_otc-->
          	                                    
     	                      </div> 
     	                  </div>
     	                                       
                                            </div>
<?php } else {
	    
?>                                          <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="cmbitmcat">Benefit Type*</label>

                                                <div class="form-group styled-select">

                                                <select name="ben" id="ben" class="form-control">
                                                

<?php $qryben="SELECT `id`, `title` FROM `benifitype` order by id"; $resultben = $conn->query($qryben); if ($resultben->num_rows > 0) {while($rowben = $resultben->fetch_assoc()) 

      { 

          $benid= $rowben["id"];  $bennm=$rowben["title"];

?>                                                          

                                                    <option value="<?php echo $benid; ?>" <?php if ($ben == $benid) { echo "selected"; } ?>><?php echo $bennm; ?></option>

<?php  }}?>                                                       

                                                  </select>

                                                  </div>

                                          </div>        

                                        </div> <!-- this block is for Benefit Type-->  
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="code">Benefit Amount*</label>

                                                <input type="number" step = "any" class="form-control" id="bamount" name="bamount" value="<?php echo $bamount;?>" required>

                                            </div>        

                                        </div> <!-- this block is for Benefit Amount--> 
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
												<lebel>Percentage?</lebel>
                                                <input type="checkbox" id="per" name="per" <?php if($per == 1) echo "checked"  ?> value = "1">
                                            </div>
                                        </div> <!-- this block is for quantity_otc, unitprice_otc-->
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            

<?php } ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        
                                        
                                        
                                         
                                    <br>&nbsp;<br>
                                <?php if($mode == 1) { ?>
                                    <div class="col-sm-12">
                                    <?php
									//echo $mode;
                                    	$addClassName = ($mode=="1")?'link-add-po':'link-add-pack-2';
									?>
        	                            <a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
    	                       <?php } ?>
                                    <br><br>&nbsp;<br><br>
                                    
                                   
                                    
                                    <!-- <div class="col-lg-12 col-md-12 col-sm-12">

                                        <div class="form-group">

                                            <label for="details">Details </label>

                                            <textarea class="form-control" id="details" name="details" rows="4" ><?php echo $details;?></textarea>

                                        </div>

                                    </div> -->
                                    
                                    
                                    
                                    <div class="col-sm-12">

                                            <?php if($mode==2) { ?>
                                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Package Setup" id="update" >
                                          <?php } else {?>
                                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Package Setup" id="add" >
                                          <?php } ?> 
                                        <a href = "http://bithut.biz/BitFlow/packageSetupList.php?pg=1&mod=4">
                                          <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                                        </a>
                                        
                                        
                                    </div>    
                                        
                                </div>
                           
                        </div>
                    </div> 
        <!-- /#end of panel -->      
      
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
//$cusid = 3;
?>
<?php include_once('inc_cmb_loader_js.php');?>

<?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>

<script language="javascript">


<?php
if($res==4){
?>

//alert($(".cmb-parent").children("option:selected").val());

var selectedValue = $(".cmb-parent").children("option:selected").val();
	
	 $.ajax({
            type: "POST",
            url: "cmb/so_item_customer_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
			beforeSend: function(){
					$(".cmd-child").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
            //root.find(".measure-unit").html(data);
			
			$(".cmd-child").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child").append(data);
			
			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });	
        
        
        
    $.ajax({
            type: "POST",
            url: "cmb/so_item_poc_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
			beforeSend: function(){
					$(".cmd-child1").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
            //root.find(".measure-unit").html(data);
			
			$(".cmd-child1").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child1").append(data);
			
			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });	        
	
<?php
}
?>

$(document).on("change", ".cmb-parent", function() {
	
	//alert($(this).children("option:selected").val());
	//var root = $(this).parent().parent().parent().parent();	// root means .toClone
	var selectedValue = $(this).children("option:selected").val();
	
	 $.ajax({
            type: "POST",
            url: "cmb/so_item_customer_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
			beforeSend: function(){
					$(".cmd-child").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
            //root.find(".measure-unit").html(data);
			
			$(".cmd-child").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child").append(data);
			
			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });
        
        $.ajax({
            type: "POST",
            url: "cmb/so_item_poc_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid?>' },
			beforeSend: function(){
					$(".cmd-child1").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
            //root.find(".measure-unit").html(data);
			
			$(".cmd-child1").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmd-child1").append(data);
			
			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });	
	
});	

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