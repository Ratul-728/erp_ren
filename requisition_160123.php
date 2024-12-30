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

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'requisition';
    $currPage = basename($_SERVER['PHP_SELF']);
?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<body class="form deal-entry">
<?php  include_once('common_top_body.php');?>
<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Requisition  Details</span>
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
                        <form method="post" action="common/addrequision.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Requisition Form</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    <div class="row">
      		                            <div class="col-sm-12"> 
	                                       <!--  <h4></h4>
	                                        <hr class="form-hr"> --> 
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
                                                <select name="req_branch" id="req_branch" class="cmd-child form-control" required <?php if($st != 1) echo "disabled"; ?>>
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
                                    <div class="po-product-wrapper-req"> 
                                        <div class="color-block-req">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
		                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10"> Select Product* </h6>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Quantity* </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Priority </h6>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Note </h6>
                                                </div>
                                                
                                        </div>
	                                        </div>
<?php if($mode==1){?> 	                     
                                            
	                                        <div class="toCloneReq">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemname-->
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
                                                </div> <!-- this block is for itemname-->
                                                <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control c-qty" min="1" id="req_quantity" placeholder="Quantity"  name="req_quantity[]"  <?php if($st != 1) echo "disabled"; ?> required>
                                                    </div>
                                                </div> <!-- this block is for item total-->
                                                
                                                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6"> <!-- this block is for scale-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="req_prority[]" id="req_priority" class="form-control"  <?php if($st != 1) echo "disabled"; ?>>
                                                                <option value="">Priority</option>
                                                                <option value="L">Low</option>
                                                                <option value="M">Medium</option>
                                                                <option value="H">High</option> 
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for priority-->
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="req_note" placeholder="Note"  name="req_note[]"  <?php if($st != 1) echo "disabled"; ?>>
                                                    </div>
                                                </div> <!-- this block is for item total-->
                                                
                                            </div>
<?php } else {
	$rCountLoop = 0;$itdgt=0;    
$itmdtqry="SELECT a.`id`, a.`product`, a.`qty`, a.`note`, a.`priority`, b.name itnm FROM `requision_details` a LEFT JOIN item b ON a.product = b.id WHERE a.`requision_no` = '".$req_no."'";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) 
    {   while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $itmdtid= $rowitmdt["product"]; $itmnm=$rowitmdt["itnm"];  $qty=$rowitmdt["qty"]; $note=$rowitmdt["note"];$priority=$rowitmdt["priority"];
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toCloneReq">
                                                <div class="col-lg-3 col-md-6 col-sm-6"><!-- this block is for itemname-->
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
                                                </div> <!-- this block is for itemname-->
                                                <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName" value="<?php echo $itmdtid; ?>">
          	                                     <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control c-qty" min = "1" id="req_quantity" placeholder="Quantity"  name="req_quantity[]" required value = <?= $qty ?>  <?php if($st != 1) echo "disabled"; ?>>
                                                    </div>
                                                </div> <!-- this block is for item total-->
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6"> <!-- this block is for scale-->
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
                                                </div> <!-- this block is for scale-->
                                            
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="req_note" placeholder="Note"  name="req_note[]" value = "<?= $note ?>"  <?php if($st != 1) echo "disabled"; ?> >
                                                    </div>
                                                </div> <!-- this block is for item total-->
                                               <?php
                                                if($rCountLoop>0){
												?>
                                               		<div class="remove-icon-req"><a href="#" class="remove-po-req" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                                <?php
													
												}
												$rCountLoop++;
												?>  
                                            </div>
<?php  } } else {?>
                                            <div class="toCloneReq">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemname-->
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
                                                </div> <!-- this block is for itemname-->
                                                <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="req_quantity" placeholder="Quantity"  name="req_quantity[]" min = "1" required value = <?= $qty ?>  <?php if($st != 1) echo "disabled"; ?>>
                                                    </div>
                                                </div> <!-- this block is for item total-->
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6"> <!-- this block is for scale-->
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
                                                </div> <!-- this block is for scale-->
                                            
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="req_note" placeholder="Note"  name="req_note[]" value = "<?= $note ?>"  <?php if($st != 1) echo "disabled"; ?>>
                                                    </div>
                                                </div> <!-- this block is for item total-->
                                            </div> 
<?php }} ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        
                                    </div>      
                                    <br>&nbsp;<br>
                                    <div class="col-sm-12">
                                    <?php
									//echo $mode;
                                    	$addClassName = ($mode=="1")?'link-add-req':'link-add-req-2';
									?>
									<?php if($st == 1){ ?>
        	                            <a href="#" class="<?=$addClassName?>" ><span class="glyphicon glyphicon-plus"></span> Add another item</a>
        	                       <?php } ?>
    	                            </div>
                                    <br><br>&nbsp;<br><br>
                                    </div>
                                </div>
                            </div> 
                            
                            <!-- /#end of panel --> 
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
                        </form>       
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /#page-content-wrapper -->

<?php    include_once('common_footer.php');?>
<?php include_once('inc_cmb_loader_js.php');?>

<script language="javascript">
<?php
if($res==4){
?>

//alert($(".cmb-parent").children("option:selected").val());

	
<?php
}
?>


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
	
	root.find('.c-qty').val(1);
	
	
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

</script>

<script>
    $(document).on("change", ".dl-cmborg", function() {
        var g = $(this).val();
        var id = $('#cmbassign1 option[value="' + g +'"]').attr('data-value');
        $('#cmborg').val(id);
        //alert(id);
        
        //Change Lead Name
        $.ajax({
            type: "POST",
            url: "cmb/get_data.php",
            data: { key : id, type: 'orgtocontact' },
			beforeSend: function(){
					$("#cmbld").html("<option>Loading...</option>");
				},
		 
        }).done(function(data){
			$("#cmbld").empty();
			$("#cmbld").append(data);
			//alert(data);
        });
        
	
	});
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
    $(document).ready(function() {
    var max_fields      = 20; //maximum input boxes allowed
    var wrapper         = $(".color-block-req"); //Fields wrapper
    var add_button      = $(".link-add-req"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper-req .toCloneReq:last-child").clone().appendTo(wrapper);
    
    $( ".po-product-wrapper-req .toCloneReq:last-child input").val("");
  

		if(x==2){
			$( ".po-product-wrapper-req .toCloneReq:last-child").append('<div class="remove-icon"><a href="#" class="remove-po-req" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
        }
    });

    $(wrapper).on("click",".remove-po-req", function(e){ //user click on remove text
        e.preventDefault();
		//alert('i am active 4');
		$(this).parent().parent().remove(); 
		//$(this).parent().parent().parent().attr('style','border:1px solid #000');
		
		x--;
		
    })
});

$(document).ready(function() {
    var max_fields      = 20; //maximum input boxes allowed
    var wrapper         = $(".color-block-req"); //Fields wrapper
    var add_button      = $(".link-add-req-2"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper-req .toCloneReq:last-child").clone().appendTo(wrapper);
    
    $( ".po-product-wrapper-req .toCloneReq:last-child input").val("");
	$( ".po-product-wrapper-req .toCloneReq:last-child .datalist").attr("placeholder","Select Item");
	
	
  

	//alert($('.toClone').length);
		if($('.toClone').length > 1){
			$( ".po-product-wrapper-req .toCloneReq:last-child").append('<div class="remove-icon"><a href="#" class="remove-po-req" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
		sumPrice();
		sumPriceV2();
        }
    });

    $(wrapper).on("click",".remove-po-req", function(e){ //user click on remove text
        e.preventDefault();
		//alert('i am active 2');
		$(this).parent().parent().remove(); 
		//$(this).parent().parent().parent().attr('style','border:1px solid #000');
		
		x--;
		
    })
});



$(document).ready(function() {
    var max_fields      = 20; //maximum input boxes allowed
    var wrapper         = $(".color-block-req"); //Fields wrapper
    var add_button      = $(".link-add-ot-2"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		$( ".po-product-wrapper-req .toCloneReq:last-child").clone().appendTo(wrapper);
    
    $( ".po-product-wrapper-req .toCloneReq:last-child input").val("");
	$( ".po-product-wrapper-req .toCloneReq:last-child .datalist").attr("placeholder","Select Item");
	
	
  

	//alert($('.toCloneReq').length);
		if($('.toCloneReq').length > 1){
			$( ".po-product-wrapper-req .toCloneReq:last-child").append('<div class="remove-icon-req"><a href="#" class="remove-po-req" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}
		sumPrice();
		sumPriceV2();
        }
    });

    $(wrapper).on("click",".remove-po-req", function(e){ //user click on remove text
        e.preventDefault();
		//alert('i am active 5');
		$(this).parent().parent().remove(); 
		//$(this).parent().parent().parent().attr('style','border:1px solid #000');
		
		x--;
		
    })
});
</script>

</body>

</html>

<?php }?>