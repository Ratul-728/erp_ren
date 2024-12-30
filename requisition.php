<?php
require "common/conn.php";
session_start();
//print_r($_SESSION);die;
$usr=$_SESSION["user"];
$empid = $_SESSION["empid"];

if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); }
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $id= $_GET['id'];

    if ($res==1){echo "<script type='text/javascript'>alert('".$msg."')</script>";}
    if ($res==2){echo "<script type='text/javascript'>alert('".$msg."')</script>";}
    if ($res==4)
    {
        $mode=2;//update mode
        $qry="SELECT id, DATE_FORMAT(`date`, '%d/%m/%Y') date,`requision_no`,`branch`,`requision_by`, status FROM `requision` WHERE id= ".$id; 
        //echo $qry; die;
        if ($conn->connect_error){ echo "Connection failed: " . $conn->connect_error; }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        
                        $iid=$row["id"];$date=$row["date"]; $req_no=$row["requision_no"];$branch=$row["branch"];$req_by=$row["requision_by"]; $st=$row["status"];
                        
                        if($st != 1){
                            $mode = 0;
                        }
            
                    }
            }
        }
        
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
        $iid=''; $date=date("d/m/Y");  $req_no=''; $branch='';  $req_by=$empid; //Insert mode
        $mode = 1; $st = 1;
    }
//echo $branch;die;
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'requisition';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
     include_once('common_header.php');
?>
<body class="form soitem">
    <?php    include_once('common_top_body.php'); ?>
<div id="wrapper"> 
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Requisition  Details</span>
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
                       <form method="post" action="common/addrequision.php" id="form1" enctype="multipart/form-data">  
                       <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->  
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <div class="panel panel-info">
			            <div class="panel-body panel-body-padding">
                            <span class="alertmsg"></span>
                               <div class="row form-header">
	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>Requisition Form</h6>
      		                            </div>
      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>  
                                   </div>                             
                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->
                                <div class="row">
                            	    <div class="col-sm-12">
	                                     <input type="hidden"  name="itid" id="itid" value="<?php echo $iid;?>">
	                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
    	                            </div> 
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="ddt">Requisition Date*</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="req_date" name="req_date" value="<?php echo $date;?>" required  <?php if($st != 1) echo "disabled"; ?>>
                                            <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                        </div>        
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbsupnm">Branch*</label>
                                                <div class="form-group styled-select">
                                                <select name="req_branch" id="req_branch" class="cmd-c hild form-control" required <?php if($st != 1) echo "disabled"; ?>>
                                                <option value="">Select Branch</option>
                                                        <?php $qrycont="SELECT `id`, `name`  FROM `branch`  WHERE status = 'A'"; $resultcont = $conn->query($qrycont); if ($resultcont->num_rows > 0) {while($rowcont = $resultcont->fetch_assoc()){
                                                        	$tid= $rowcont["id"];  $nm=$rowcont["name"];
                                                        ?>
                                                        <option value="<?php echo $tid; ?>" <?php if ($branch == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
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
                                                <label for="cmbhrmgr">Requasion By*</label>
                                                <div class="form-group styled-select">
                                                <select name="req_by" id="req_by" class="form-control" required  <?php if($st != 1) echo "disabled"; ?>>
                                                <option value="">Select Employee</option>
<?php $qryhrm="SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id`, e.id eid FROM `hr` h,`employee` e where h.`emp_id`=e.`employeecode` and h.id != 1 order by emp_id"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
      { 
          $hridm= $rowhrm["eid"];  $hrnmm=$rowhrm["emp_id"];
?>                                                          
                                                    <option value="<?php echo $hridm; ?>" <?php if ($req_by == $hridm) { echo "selected"; } ?>><?php echo $hrnmm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
      	                           
                            	    <br>
                                    <div class="po-product-wrapper withlebel"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
	                                        </div>
<?php if($mode==1||$mode==5){?> 	            
                                            <div class="row header-row">
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10"> Select Product* </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Quantity </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Priority </h6>
                                                </div>
                                                <div class="col-lg-5 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Note </h6>
                                                </div>
                                            </div>
											<!-- INSERT -->
	                                        <div class="toClone">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                       <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item" required  <?php if($st != 1) echo "disabled"; ?>>
                                                            <datalist  id="itemName" class="list-itemName form-control" required  <?php if($st != 1) echo "disabled"; ?>>
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`  FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for itemName--> 
                                                <!-- this block is for vat--> 
                                                 <div class="col-lg-2 col-md-6 col-sm-6">
                                                     <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control c-qty" min="1" id="req_quantity" placeholder="Quantity"  name="req_quantity[]"  <?php if($st != 1) echo "disabled"; ?> required>
                                                    </div>
                                                </div>
                                                <!-- this block is for ait--> 
                                                 <div class="col-lg-2 col-md-6 col-sm-6">
                                                     <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="priority[]" id="req_priority" class="form-control"  <?php if($st != 1) echo "disabled"; ?>>
                                                                <option value="">Priority</option>
                                                                <option value="L">Low</option>
                                                                <option value="M">Medium</option>
                                                                <option value="H">High</option> 
                                                            </select>
                                                        </div>
                                                    </div>    
                                                </div>
          	                                    <div class="col-lg-5 col-md-6 col-sm-6">
												    <div class="form-group">
                                                        <input type="text" class="form-control" id="req_note" placeholder="Note"  name="req_note[]"  <?php if($st != 1) echo "disabled"; ?>>
                                                    </div>   
                                                </div> <!-- this block is for measureUnit--> 
                                            </div>
<?php } else { ?>
		                                    <div class="row header-row">
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10"> Select Product* </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Quantity </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Priority </h6>
                                                </div>
                                                <div class="col-lg-5 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Note </h6>
                                                </div>
                                            </div>

                                        
<?php
		$rCountLoop = 0;$itdgt=0;    
$itmdtqry="SELECT a.`id`, a.`product`, a.`qty`, a.`note`, a.`priority`, b.name itnm FROM `requision_details` a LEFT JOIN item b ON a.product = b.id WHERE a.`requision_no` = '".$req_no."'";
//echo $itmdtqry;die;
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) 
    {   while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $itmdtid= $rowitmdt["product"]; $itmnm=$rowitmdt["itnm"];  $qty=$rowitmdt["qty"]; $note=$rowitmdt["note"];$priority=$rowitmdt["priority"];
?>                                           
                                            <!-- this block is for php loop, please place below code your loop  --> 
											<!-- EDIT -->
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemName-->  
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" value="<?= $itmnm ?>" placeholder="<?php echo $itmnm; ?>" required  <?php if($st != 1) echo "disabled"; ?>>
                                                            <datalist id="itemName" class="list-itemName form-control" required  <?php if($st != 1) echo "disabled"; ?>> 
                                                                <option value="">Select Item</option>
    <?php $qryitm="SELECT `id`, `name`  FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <!-- <option  value="<?php echo $tid; ?>" <?php if ($itmdtid == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>-->
                                                                 <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                                     
                                                            </datalist>
                                                        </div>
                                                    </div>  
                                                </div> <!-- this block is for itemName-->
                                                <!-- this block is for vat--> 
                                                 <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName" value="<?php echo $itmdtid; ?>">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control c-qty" min = "1" id="req_quantity" placeholder="Quantity"  name="req_quantity[]" required value = <?= $qty ?>  <?php if($st != 1) echo "disabled"; ?>>
                                                    </div>
                                                </div>
                                                
                                                <!-- this block is for ait--> 
                                                 <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="priority[]" id="priority" class="form-control"  <?php if($st != 1) echo "disabled"; ?>>
                                                                <option value="">Priority </option>
                                                                <option value="L" <?php if ($priority == 'L') { echo "selected"; } ?>>Low</option>
                                                                <option value="M" <?php if ($priority == 'M') { echo "selected"; } ?>>Medium</option>
                                                                <option value="H" <?php if ($priority == 'H') { echo "selected"; } ?>>High</option>
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div>
                                                
          	                                    <div class="col-lg-5 col-md-6 col-sm-6"> <!-- this block is for measureUnit-->  
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="req_note" placeholder="Note"  name="req_note[]" value = "<?= $note ?>"  <?php if($st != 1) echo "disabled"; ?> >
                                                    </div>       
                                                </div> <!-- this block is for measureUnit--> 
                                                
                                               <?php
                                                if($rCountLoop>0){
												?>
                                               		<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
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
                                                     <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item" required  <?php if($st != 1) echo "disabled"; ?>>
                                                            <datalist  id="itemName" class="list-itemName form-control" required  <?php if($st != 1) echo "disabled"; ?>>
                                                                <option value="">Select Item </option>
    <?php $qryitm="SELECT `id`, `name`  FROM `item`  order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>
                                                                <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>"><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div>  
                                                </div> <!-- this block is for itemName--> 
                                                
                                                 <!-- this block is for vat--> 
                                                 <div class="col-lg-2 col-md-6 col-sm-6">
                                                     <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="req_quantity" placeholder="Quantity"  name="req_quantity[]" min = "1" required value = <?= $qty ?>  <?php if($st != 1) echo "disabled"; ?>>
                                                    </div>
                                                </div>
                                                <!-- this block is for ait--> 
                                                 <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="priority[]" id="priority" class="form-control"  <?php if($st != 1) echo "disabled"; ?>>
                                                                <option value="">Priority</option>
                                                                <option value="L" <?php if ($priority == 'L') { echo "selected"; } ?>>Low</option>
                                                                <option value="M" <?php if ($priority == 'M') { echo "selected"; } ?>>Medium</option>
                                                                <option value="H" <?php if ($priority == 'H') { echo "selected"; } ?>>High</option>
                                                            </select>
                                                      </div>
                                                    </div>
                                                </div>
          	                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                                   <div class="form-group">
                                                        <input type="text" class="form-control" id="req_note" placeholder="Note"  name="req_note[]" value = "<?= $note ?>"  <?php if($st != 1) echo "disabled"; ?>>
                                                    </div>      
                                                </div> <!-- this block is for measureUnit-->   
                                            
<?php }} ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        </div> 
                                    </div>      
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
                                    <?php
									//echo $mode;
                                    	$addClassName = ($mode=="1")?'link-add-po':'link-add-po-2';
									?>
        	                            <a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
    	                            </div>
                                    <br><br>&nbsp;<br><br>
                                    
                                    <div class="button-bar">
                                        <?php if($mode==2) { ?>
            	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Requisition"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                 
                                        <?php } else if($mode == 1) {?>
                                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Requisition"  id="submit" >
                                        <?php } ?>  
                                    <a href = "./requisitionList.php?pg=1&mod=14">
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

<script>
    $(document).ready(function(){
        
        $(document).on("change", ".dl-itemName", function() {
            
            var val = $(this).val();
            
            var cost = $('#itemName option[value="' + val +'"]').attr('data-cost'); 
            
            $(this).closest('.toClone').find('.unitprice_otc').val(cost);
            
            var vat = $('#itemName option[value="' + val +'"]').attr('data-vat'); 
            
            $(this).closest('.toClone').find('.vat').val(vat);
            
            var ait = $('#itemName option[value="' + val +'"]').attr('data-ait'); 
            
            $(this).closest('.toClone').find('.ait').val(ait);
            
            
    });

})
</script>

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

<script>
    //Searchable dropdown
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
        $('#cmborg').val(id);
        //alert(id);
        
        
        //Change Contact Name
        $.ajax({
            type: "POST",
            url: "cmb/get_data.php",
            data: { key : id, type: 'orgtocontact' },
			beforeSend: function(){
					$("#cmbsupnm").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
			$("#cmbsupnm").empty();
			$("#cmbsupnm").append(data);
			//alert(data);
        });
        
	
	});
</script>

<script>
    //alert("s");
$(document).ready(function(){

	
	
			//existing item list
             $('.ds-list').attr('style','display:none');
			
			//one entry input box div
             $('.ds-add-list').attr('style','display:none');

             //Input Click

            $('.input-box').click(function(){
                $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
            });

            //Option's value shows on input box

            $('.input-ul').on("click","li", function(){
               // console.log(this);

                if(!$(this).hasClass("addnew")){

                    let litxt= $(this).text();
                    let lival= $(this).val();

                    $("#org_id").val(lival);
                    $.ajax({
                        type: "POST",
                        url: "cmb/get_data.php",
                        data: { key : lival, type: 'orgtocontact' },
                        beforeSend: function(){
                        	$("#cmbsupnm").html("<option>Loading...</option>");
                        },
                        
                        }).done(function(data){
                            $("#cmbsupnm").empty();
                        	$("#cmbsupnm").append(data);
                            //alert(data);
                        });
					$(this).closest('.ds-divselect-wrapper').find('.input-box').val(litxt);
					$(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value',litxt);

                    // $(this).closest('.ds-add-list').attr('style','display:none');
                    $(this).closest('.ds-list').attr('style','display:none');
                }

            });
	

	
            // New input box display


	
	
	
	/* no need for now
	
            // New-Input box's value display on old-input box

            $('.ds-add-list-btn').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
                $(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value', x);
				$(this).closest('.ds-divselect-wrapper').find('.input-box').val(x);
                $(this).closest('.ds-add-list').attr('style','display:none');
                //$(this).closest('.ds-add-list').find('.addinpBox').val('');
                console.log($(this).closest('.ds-add-list').find('.addinpBox').val(""));
                // alert(x);
                // }
                action(x);
                function action(x){
                    $.ajax({
                        url:"phpajax/divSelectOrg.php",
                        method:"POST",
                        data:{newItem: x},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#org_id").val(res.id);
                                $('.display-msg').html(res.name);
                                messageAlertLong(res,'alert-success');

                            }
                    });
	             }


            });
	
	
	*/
	
	
            $(document).mouseup(function (e) {
                if ($(e.target).closest(".ds-list").length === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length  === 0) {
                    $(".ds-add-list").hide();
                }
            });	
	
	
            $('.input-box').on("keyup", function() {
			    //alert($(this).val());
			    var searchKey = $(this).val().toLowerCase();
                $(this).closest('.ds-divselect-wrapper').find(".input-ul li ").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchKey)>-1);
                });
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			    //$(this).closest('.ds-divselect-wrapper').find('.input-ul li').click(function(){
				$(this).closest('.ds-divselect-wrapper').find('.input-ul').on("click","li", function(){
                     //
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
						//alert(x);
                        $(this).closest('.ds-divselect-wrapper').val(x);
                        $(this).closest('.ds-list').attr('style','display:none');
                    }
                })

                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){
					
                   // $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                   // $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
					
					
					
					//addNewOrg();
					
				
					
					
					
				
					
					
					
					
					
					
					
					
					
                });

			});	
	
            $('.input-ul .addnew').click(function(){
               // $(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
				addNewOrg();
                $(this).closest('.ds-list').attr('style','display:none');
            });	
	
	

	function addNewOrg(){
		
				BootstrapDialog.show({

											title: 'Add New Organization',
											//message: '<div id="printableArea">'+data+'</div>',
											message: $('<div></div>').load('addselect_modal_org_tab.php'),
											type: BootstrapDialog.TYPE_PRIMARY,
											closable: false, // <-- Default value is false
											draggable: true, // <-- Default value is false
											buttons: [{
												//icon: 'glyphicon glyphicon-print',
												cssClass: 'btn-primary',
												id: 'btn-1',
												label: 'Save',
												action: function(dialog) {

													var $button = this;
													$button.hide();

													dialog.setClosable(false);

													var orgtype = $('#org-type').serializeArray();
													//alert($("#orgtype").val());

													if(orgtype[0].value == 1){
														var ajxdata = $('#form-org').serializeArray();
														
														if(!ajxdata[0].value || !ajxdata[1].value || !ajxdata[3].value || !ajxdata[4].value || !ajxdata[5].value || !ajxdata[6].value){
                    										
                    										var msg ="";
															//alert(msg.length);
                    										if(!ajxdata[0].value){
                    										    msg = "Please Enter Name!*"; $("#cnnm").focus(); 
                    										}else if(!ajxdata[1].value){
                    										    msg = "Please Enter Industry Type!"; $("#cmbindtype").focus();
                    										}else if(!ajxdata[3].value){
                    										    msg = "Please Enter Address!"; $("#address").focus();
                    										}else if(!ajxdata[4].value){
                    										    msg = "Please Enter Contact Name!"; $("#contactname").focus();
                    										}else if(!ajxdata[5].value){
                    										    msg = "Please Enter Contact Email!"; $("#contactemail").focus();
                    										}else if(!ajxdata[6].value){
                    										    msg = "Please Enter Cotact Phone Number!"; $("#contactphone").focus();
                    										}
															
															if(msg.length>0){
															  $.alert({
																title: "Warning",
																escapeKey: true,
																content: msg,
																backgroundDismiss: true,
																confirmButton: 'OK',
																buttons: {
																OK: {
																	keys: ["enter"],
																},
															   },
															}); //alert('Please enter name'); 
															$button.show();
																return false;
															}
                    									
                    									
                    									}
													}else{
														var ajxdata = $('#form-indi').serializeArray();
														
														if(!ajxdata[0].value || !ajxdata[1].value || !ajxdata[3].value || !ajxdata[4].value || !ajxdata[5].value || !ajxdata[6].value){
                    										
                    										var msg ="";
                    										if(!ajxdata[0].value){
                    										    msg = "Please Enter Name!"; // $("#indv_name").focus();
                    										}else if(!ajxdata[1].value){
                    										    msg = "Please Enter Email!"; $("#contemail").focus();
                    										}else if(!ajxdata[2].value){
                    										    msg = "Please Enter Phone Number!"; $("#contphone").focus();
                    										}else if(!ajxdata[4].value){
                    										    msg = "Please Enter Address!"; $("#ind_address").focus();
                    										}else if(!ajxdata[5].value){
                    										    msg = "Please Enter District!"; $("#district").focus();
                    										}else if(!ajxdata[7].value){
                    										    msg = "Please Enter Country!"; $("#country").focus();
                    										}

															if(msg.length>0){
																$.alert({
																title: "Warning",
																escapeKey: true,
																content: msg,
																backgroundDismiss: true,
																buttons: {
																OK: {
																	keys: ["enter"],
																},
															   },
															}); //alert('Please enter name'); 
															$button.show();

															return false;
															}
                    									}
													}
													
											//alert(ajxdata[0].value);
													//return false;
											
									
											
													
													

													$.ajax({
														  type: "POST",
														  url: 'phpajax/divSelectOrg.php',
														  data: {data: ajxdata, type: orgtype[0].value},
														  type: 'POST',
														  dataType:"json",
														  success: function(res){

															  //dialog.setMessage("Success");


															  $("#org_id").val(res.id);
															  
															  $('.input-box').attr('value',res.name+"("+res.contact+")");
															  $("#inpUl").append("<li class='pp1' value = '"+res.id+"'>"+res.name+"("+res.contact+")"+"</li>");
															  
															  $.ajax({
                                                                    type: "POST",
                                                                    url: "cmb/get_data.php",
                                                                    data: { key : res.id, type: 'orgtocontact' },
                                                        			beforeSend: function(){
                                                        					$("#cmbsupnm").html("<option>Loading...</option>");
                                                        				},
                                                        		 
                                                                }).done(function(data){
                                                        			$("#cmbsupnm").empty();
                                                        			$("#cmbsupnm").append(data);
                                                        			//alert(data);
                                                                });

														        dialog.close();
				//                                           
														  }
														});


												/*var $button = this;
												//$button.hide();
												//dialogItself.close();
												//$button.spin();
												dialog.setClosable(false);



												var obj = [];

												var cdata = {};


												 cdata.name = $("#new-cat-field").val();



												//check user data;
												  if(!$("#new-cat-field").val()){alert('Please enter category name'); $button.show(); return false;}


												 obj.push(cdata);

												var dataString = JSON.stringify(obj);



												/*alert(dataString);

												$.ajax({
												   url: 'phpajax/cmb_add_category.php',
												   data: {posData: dataString},
												   type: 'POST',
												   dataType:"json",
												   success: function(res) {

													   if(res != 0){
															// dialog.setMessage(res.query);
														   //$("#new-cat-field").val(res.name);
														   $("#old-prod-cart-field").val(res.name);
														   $("#catID").val(res.id);
														   $("#catID").attr('data-name',res.name);
														   //document.title = res.name;
														  // dialogItself.close();
														  dialog.setMessage(res.msg);
														  setTimeout(function(){
																dialog.close();
															  },2000);

													   }else{
														   alert("Something went wrong!!!");
													   }

												   }
												});  */




												},
											}, {
												label: 'Close',
												action: function(dialogItself) {
													dialogItself.close();
												}
											}]
										});			
		
	}
	
});

                                   


</script>	
	
</body>
</html>
<?php }?>