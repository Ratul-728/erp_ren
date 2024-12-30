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
    $atid = $_GET["id"];
    $serno= $_GET['id'];
    $totamount=0;
    
    if ($res==4)
    {
    //echo "<script type='text/javascript'>alert('".$id."')</script>"; 
    $qry="SELECT `id`, `shift`, `start`, `end`, `delaytime`, `extendeddelay`, `latetime`, `absent` FROM `OfficeTime` WHERE st = 1 and id = ".$id;
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
                            $shift = $row["shift"];
                            $stime = $row["start"];$etime = $row["end"];$dtime = $row["delaytime"];
                            $edtime = $row["extendeddelay"];$ltime = $row["latetime"];$abstime = $row["absent"];
                            
                        }
                }
            }
    $mode=2;//update mode
   // echo "<script type='text/javascript'>alert('".$orderdt."')</script>"; 
    }
    
    else
    {
        $shift = ''; $stime = '';$etime = '';$dtime = '';
        $edtime = '';$ltime = '';$abstime = '';
        $mode=1;//Insert mode
                        
    }
    
    $currSection = 'officetime';
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
            <span>Office Time</span>
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
                       <form method="post" action="common/addofficetime.php" id="form1" enctype="multipart/form-data">  
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->  
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">
      		           
			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->
                                   <div class="row form-header"> 
                                   
	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>HRM <i class="fa fa-angle-right"></i> Add New Office Time</h6>
      		                            </div>
      		                            
      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>                                   
                                   
                                   
                                   </div>  
                                <div class="row">
                            	    <div class="col-sm-12">
	                                    <!-- <h4>SO Information</h4>
		                                <hr class="form-hr"> -->
		                                
		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno;?>"> 
		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
    	                            </div> 
                                    
	                                
                                    <div class="po-product-wrapper withlebel"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Office Time Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if($mode==1){?> 	                                        
	                                        <div class="toClone">
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
													<lebel>Shift*</lebel>
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `title`  FROM `Shifting`  order by title"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["title"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for itemName-->  
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
          	                                        <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
													<lebel>Start Time*</lebel>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="starttime" name="starttime[]" required>
                                                    </div>        
                                                </div> <!-- this block is for Start time-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
													<lebel>End Time*</lebel>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="endtime" name="endtime[]"  required>
                                                    </div>    
                                                </div> <!-- this block is for End time-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
													<lebel>Delay Time*</lebel>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="delaytime" name="delaytime[]" required>
                                                    </div>      
                                                </div> <!-- this block is for Delay time-->
                                                
<!--                                                <div class="col-lg-1 col-md-6 col-sm-6">
													<lebel>Extended Delay Time*</lebel>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="edalaytime" name="edelaytime[]"  required>
                                                    </div>       
                                                </div>--> <!-- this block is for Extended Delay time-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
													<lebel>Late Time*</lebel>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="latetime" name="latetime[]" required>
                                                    </div>       
                                                </div> <!-- this block is for Late time-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
													<lebel>Absent Time*</lebel>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="abstime" name="abstime[]"  required>
                                                    </div>       
                                                </div> <!-- this block is for Absent time-->
                                        
                                                
                                            </div>

<?php  }
else
{
?>
                                            <div class="toClone">
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for itemName-->  
                                                    <lebel>Shift</lebel>
													<div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="itemName" id="itemName" class="form-control">
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `title`  FROM `Shifting`  order by title"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["title"];
    ?>
                                                                <option  value="<?php echo $tid; ?>" <?php if ($shift == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                           </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for itemName-->
                                                
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
          	                                        <!-- <input type="hidden" placeholder="ITEM" name="itemName" class="itemName"> -->
          	                                        <input type="hidden"  name="atid" value = "<?= $atid ?>">
													<lebel>Start Time*</lebel>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="starttime" name="starttime" value="<?php echo $stime;?>" required>
                                                    </div>        
                                                </div> <!-- this block is for Start time-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
													<lebel>End Time*</lebel>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="endtime" name="endtime" value="<?php echo $etime;?>" required>
                                                    </div>    
                                                </div> <!-- this block is for End time-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
													<lebel>Delay Time*</lebel>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="delaytime" name="delaytime" value="<?php echo $dtime;?>" required>
                                                    </div>      
                                                </div> <!-- this block is for Delay time-->
                                                
                                                <!--div class="col-lg-2 col-md-6 col-sm-6">
													<lebel>Extended Delay Time*</lebel>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="edelaytime" name="edelaytime" value="<?php echo $edtime;?>" required>
                                                    </div>       
                                                </div--> <!-- this block is for Extended Delay time-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
													<lebel>Late Time*</lebel>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="latetime" name="latetime" value="<?php echo $ltime;?>" required>
                                                    </div>       
                                                </div> <!-- this block is for Late time-->
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6">
													<lebel>Absent Time*</lebel>
                                                    <div class="input-group time-wrapper">
                                                        <input type="text" class="form-control timeonly" id="abstime" name="abstime" value="<?php echo $abstime;?>" required>
                                                    </div>       
                                                </div> <!-- this block is for Absent time-->
                                            </div>
<?php }} ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        
                                        
                                        
                                    </div>      
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
                                    <?php
									    if($mode == "1"){
                                    	$addClassName = 'officetime-link-add';
									?>
        	                            <a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
    	                            <?php } ?>
                                    <br><br>&nbsp;<br><br>
                                    
                                   
                                </div>
                           
                        </div>
                    </div> 
        <!-- /#end of panel -->      
                    <div class="button-bar">
                            <?php if($mode==2) { ?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Office" id="update" >
                          <?php } else {?>
                          <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Office Time" id="add" >
                          <?php } ?> 
                        <a href = "./officetimeList.php?pg=1&mod=4">
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


//$cusid = 3;
?>
<?php // include_once('inc_cmb_loader_js.php');?>

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


//clone rows


    var max_fields      = 20; //maximum input boxes allowed
    var wrapper         = $(".color-block"); //Fields wrapper
    var add_button      = $(".officetime-link-add"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper .toClone:last-child").clone().appendTo(wrapper);
    
    $( ".po-product-wrapper .toClone:last-child input").val("");
  

		if(x==2){
			$( ".po-product-wrapper .toClone:last-child").append('<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
		callTime();
        }
    });

    $(wrapper).on("click",".remove-po", function(e){ //user click on remove text
        e.preventDefault();
		$(this).parent().parent().remove(); 
		//$(this).parent().parent().parent().attr('style','border:1px solid #000');
		
		x--;
		
    })
	
	
	

//datetime definer
function callTime(){
         $('.timeonly').datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "HH:mm",
					//format: 'LT',
					keepOpen: true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-chevron-up",
                 down: "fa fa-chevron-down"
                }
            });
			//$('.timeonly').data("DateTimePicker").show();
}
callTime();
</script>

</body>
</html>
