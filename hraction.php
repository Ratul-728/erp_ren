<?php

require_once("common/conn.php");

session_start();

$usr=$_SESSION["user"];

if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}

else

{

    $res= $_GET['res'];

    $msg= $_GET['msg'];

    $id= $_GET['id'];







    if ($res==4)

    {

        $qry="SELECT a.`id`, a.`hrid`, a.`actiontype`, a.`actiondt`, a.`postingdepartment`, a.`jobarea`, a.`designation`, a.`reportto`, a.`jobtype`, b.Title acttypename,
                dept.name deptname, ja.Title janame, jt.Title jtname, desi.name desiname, concat(emp.`firstname`, ' ', emp.`lastname`, '( ', emp.`employeecode`, ' )') empname
                FROM `hraction` a LEFT JOIN ActionType b ON a.actiontype = b.ID LEFT JOIN department dept ON a.postingdepartment = dept.id LEFT JOIN JobArea ja ON ja.ID = a.jobarea
                LEFT JOIN JobType jt ON jt.ID = a.jobtype LEFT JOIN designation desi ON a.designation = desi.id LEFT JOIN employee emp ON emp.id = a.hrid
                WHERE a.id = ".$id; 

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

                        $iid=$row["id"];$mu=$row["hrid"];$act=$row["actiontype"]; $action_dt =$row["actiondt"]; $dept = $row["postingdepartment"]; $ja = $row["jobarea"];
                        $jt = $row["jobtype"]; $desi = $row["designation"]; $repto= $row["reportto"]; $janame = $row["janame"]; $jtname = $row["jtname"];
                        $deptname = $row["deptname"]; $desiname = $row["desiname"];$empname = $row["empname"];
                        
                        //Convert date
                        $act_dt = array();
                        $pieces = explode("-", $action_dt);
                        foreach($pieces as $val){
                            array_push($act_dt,$val);
                        }
                        
                        $action_dt = '';
                        $action_dt = $act_dt[2]."-".$act_dt[1]."-".$act_dt[0];
                        
                        $acttypename = $row["acttypename"];
                        

                    }

            }

        }

    $mode=2;//update mode

    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 

    }

    else

    {

                        $mu=""; $act=''; $action_dt =''; $dept = ''; $ja = '';
                        $jt = ''; $desi = ''; $repto= '';
                        

    $mode=1;//Insert mode

                    

    }



    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

    */

    $currSection = 'hraction';

    $currPage = basename($_SERVER['PHP_SELF']);

?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">

<?php  include_once('common_header.php');?>

<style>
.list-wrapper .ds-list{
    z-index: 100000!important;
}

.ds-divselect-wrapper{
    z-index: auto;
}	
</style>

<body class="form">

<?php  include_once('common_top_body.php');?>



<div id="wrapper"> 

  <!-- Sidebar -->

    <div id="sidebar-wrapper" class="mCustomScrollbar">

        <div class="section">

  	        <i class="fa fa-group  icon"></i>

            <span>HR Action</span>

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

                        <form method="post" action="common/addhraction.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->

                            <div class="panel panel-info">

      			                <div class="panel-heading"><h1>Information</h1></div>

				                <div class="panel-body">

                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>

                                    <div class="row">

      		                            <div class="col-sm-12">

	                                        <h4></h4>

	                                        <hr class="form-hr"> 

		                                    <input type="hidden"  name="itid" id="itid" value="<?php echo $iid;?>">  

	                                    </div>      
                                        <input type = "hidden" name = "iid" value = "<?= $_GET["id"] ?>">
            	                           <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="cmbprdtp">Employee<span class="redstar">*</span> </label>

                                                <!--div class="form-group styled-select">

                                                <select name="empid" id="empid" class="form-control">
                                                

<?php $qrymu="SELECT `id`, concat(`firstname`, ' ', `lastname`) empname FROM `employee` order by empname"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 

      { 

          $mid= $rowmu["id"];  $mnm=$rowmu["empname"];

?>                                                          

                                                    <option value="<?php echo $mid; ?>" <?php if ($mu == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>

<?php  }}?>                                                       

                                                  </select>

                                                  </div-->
                                                <div class="form-group styled-select">
                                                    <input list="cmbassign1" name ="cmbassign2" value = "<?=$empname ?>" autocomplete="Search From list"  class="dl-cmborg datalist" placeholder="Select Employee">
                                                    <datalist  id="cmbassign1" name = "cmbsupnm1" class="list-cmbassign form-control" >
                                                        <option value="">Select Employee</option>
    <?php $qryitm = "SELECT `id`, concat(`firstname`, ' ', `lastname`, '( ', `employeecode`, ' )') empname FROM `employee` order by empname";
    $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
        $tid = $rowitm["id"];
        $nm  = $rowitm["empname"]; ?>
                                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
    <?php }} ?>
                                                     </datalist>
                                                     <input type = "hidden" name = "empid" id = "empid" value = "<?=$mu ?>">
                                                </div>

                                          </div>        

                                        </div>

      	                 
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-6"> 
                                        <div class="form-group">
                                            <label for="cmbcontype">Action Type <span class="redstar">*</span></label>
                                            <div class="ds-divselect-wrapper cat-name">
                                            <div class="ds-input">
                                                <input type="hidden" name="dest" value="">
                                                <input type="hidden" name="acty" id = "acty" value = "<?= $act ?>">
                                                <input type="text" name="act_name"  autocomplete="off" placeholder="Select Action Type"  class="input-box form-control" value = "<?= $acttypename ?>">
                                            </div>
                                                <div class="list-wrapper">
                                                    <div class="ds-list" style="display: none;">
                                
                                                        <ul class="input-ul" tabindex="0" id="inpUl">
                                                            <li tabindex="1" class="addnew">+ Add new</li>
                                
                                
                                                            <?php $qryitm = "SELECT `ID` id, `Title` FROM `ActionType` WHERE st = 1 order by Title";
                                    $resultitm                                = $conn->query($qryitm);if ($resultitm->num_rows > 0) {
										$tabindex = 2;
										while ($rowitm = $resultitm->fetch_assoc()) {
                                        $tid = $rowitm["id"];
                                        $nm  = $rowitm["Title"]; 
															
															?>
                                                                        <li  tabindex="<?=$tabindex?>" class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
                                                        <?php
										$tabindex++;						
										}} 
															?>
                                                        </ul>
                                                    </div>
                                                    <div class="ds-add-list" style="display: none;">

                                                        <div class="row">
                                                            <div class="col-lg-12 add-more-col">
                                                                <h3>Add new Action Type</h3>
                                                                <hr>
                                                                <label for="">Name</label> <br>
                                                                <input type="text"  name="" autocomplete="off" class="Name addinpBox form-control" id="">
                                                                
                                
                                                            </div>
                                                            <div class="col-lg-12">
                                                            	
                                                                 
																
																<button type = "button" class="btn btn-sm btn-default  ds-add-list-btn pull-right" style="margin-left: 5px;">Save</button>
																<button type = "button" class="btn btn-sm btn-default  ds-cancel-list-btn  pull-right">Cancel</button>
                                                            </div>
                                                        </div>
                                
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-6">
	                                    <label for="email">Effective From<span class="redstar">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="action_dt" name="action_dt" value="<?php echo $action_dt;?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
                                
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-6"> 
                                        <div class="form-group">
                                            <label for="cmbcontype">Department <span class="redstar">*</span></label>
                                            <div class="ds-divselect-wrapper cat-name">
                                            <div class="ds-input">
                                                <input type="hidden" name="dest" value="">
                                                <input type="hidden" name="dept" id = "dept" value = "<?= $dept ?>">
                                                <input type="text" name="dept_name"  autocomplete="off" placeholder="Select Department"  class="input-box1 form-control" value = "<?= $deptname ?>">
                                            </div>
                                                <div class="list-wrapper">
                                                    <div class="ds-list" style="display: none;">
                                
                                                        <ul class="input-ul1" tabindex="0" id="inpUl1">
                                                            <li tabindex="1" class="addnew">+ Add new</li>
                                
                                
                                                            <?php $qryitm = "SELECT `id`, `name` FROM `department` WHERE st = 1 order by name ";
                                    $resultitm                                = $conn->query($qryitm);if ($resultitm->num_rows > 0) {
										$tabindex = 2;
										while ($rowitm = $resultitm->fetch_assoc()) {
                                        $tid = $rowitm["id"];
                                        $nm  = $rowitm["name"]; 
															
															?>
                                                                        <li  tabindex="<?=$tabindex?>" class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
                                                        <?php
										$tabindex++;						
										}} 
															?>
                                                        </ul>
                                                    </div>
                                                    <div class="ds-add-list" style="display: none;">

                                                        <div class="row">
                                                            <div class="col-lg-12 add-more-col">
                                                                <h3>Add new Department</h3>
                                                                <hr>
                                                                <label for="">Name</label> <br>
                                                                <input type="text"  name="" autocomplete="off" class="Name addinpBox form-control" id="">
                                                                
                                
                                                            </div>
                                                            <div class="col-lg-12">
                                                            	
                                                                 
																
																<button type = "button" class="btn btn-sm btn-default  ds-add-list-btn-dept pull-right" style="margin-left: 5px;">Save</button>
																<button type = "button" class="btn btn-sm btn-default  ds-cancel-list-btn  pull-right">Cancel</button>
                                                            </div>
                                                        </div>
                                
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-3 col-md-6 col-sm-6"> 
                                        <div class="form-group">
                                            <label for="cmbcontype">Job Role <span class="redstar">*</span></label>
                                            <div class="ds-divselect-wrapper cat-name">
                                            <div class="ds-input">
                                                <input type="hidden" name="dest" value="">
                                                <input type="hidden" name="jobarea" id = "jobarea" value = "<?= $ja ?>">
                                                <input type="text" name="job_area"  autocomplete="off" placeholder="Select Job Area"  class="input-box2 form-control" value = "<?= $janame ?>">
                                            </div>
                                                <div class="list-wrapper">
                                                    <div class="ds-list" style="display: none;">
                                
                                                        <ul class="input-ul2" tabindex="0" id="inpUl2">
                                                            <li tabindex="1" class="addnew">+ Add new</li>
                                
                                
                                                            <?php $qryitm = "SELECT `ID` id,`Title` name FROM `JobArea` WHERE st = 1 order by Title";
                                    $resultitm                                = $conn->query($qryitm);if ($resultitm->num_rows > 0) {
										$tabindex = 2;
										while ($rowitm = $resultitm->fetch_assoc()) {
                                        $tid = $rowitm["id"];
                                        $nm  = $rowitm["name"]; 
															
															?>
                                                                        <li  tabindex="<?=$tabindex?>" class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
                                                        <?php
										$tabindex++;						
										}} 
															?>
                                                        </ul>
                                                    </div>
                                                    <div class="ds-add-list" style="display: none;">

                                                        <div class="row">
                                                            <div class="col-lg-12 add-more-col">
                                                                <h3>Add new Job Area</h3>
                                                                <hr>
                                                                <label for="">Name</label> <br>
                                                                <input type="text"  name="" autocomplete="off" class="Name addinpBox form-control" id="">
                                                                
                                
                                                            </div>
                                                            <div class="col-lg-12">
                                                            	
                                                                 
																
																<button type = "button" class="btn btn-sm btn-default  ds-add-list-btn-ja pull-right" style="margin-left: 5px;">Save</button>
																<button type = "button" class="btn btn-sm btn-default  ds-cancel-list-btn  pull-right">Cancel</button>
                                                            </div>
                                                        </div>
                                
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="col-lg-3 col-md-6 col-sm-6"> 
                                        <div class="form-group">
                                            <label for="cmbcontype">Contract Type <span class="redstar">*</span></label>
                                            <div class="ds-divselect-wrapper cat-name">
                                            <div class="ds-input">
                                                <input type="hidden" name="dest" value="">
                                                <input type="hidden" name="jobtype" id = "jobtype" value = "<?= $jt ?>">
                                                <input type="text" name="job_type"  autocomplete="off" placeholder="Select Job Type"  class="input-box3 form-control" value = "<?= $jtname ?>">
                                            </div>
                                                <div class="list-wrapper">
                                                    <div class="ds-list" style="display: none;">
                                
                                                        <ul class="input-ul3" tabindex="0" id="inpUl3">
                                                            <li tabindex="1" class="addnew">+ Add new</li>
                                
                                
                                                            <?php $qryitm = "SELECT `ID` id,`Title` name FROM `JobType` WHERE st = 1 order by Title";
                                    $resultitm                                = $conn->query($qryitm);if ($resultitm->num_rows > 0) {
										$tabindex = 2;
										while ($rowitm = $resultitm->fetch_assoc()) {
                                        $tid = $rowitm["id"];
                                        $nm  = $rowitm["name"]; 
															
															?>
                                                                        <li  tabindex="<?=$tabindex?>" class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
                                                        <?php
										$tabindex++;						
										}} 
															?>
                                                        </ul>
                                                    </div>
                                                    <div class="ds-add-list" style="display: none;">

                                                        <div class="row">
                                                            <div class="col-lg-12 add-more-col">
                                                                <h3>Add new Job Type</h3>
                                                                <hr>
                                                                <label for="">Name</label> <br>
                                                                <input type="text"  name="" autocomplete="off" class="Name addinpBox form-control" id="">
                                                                
                                
                                                            </div>
                                                            <div class="col-lg-12">
                                                            	
                                                                 
																
																<button type = "button" class="btn btn-sm btn-default  ds-add-list-btn-jt pull-right" style="margin-left: 5px;">Save</button>
																<button type = "button" class="btn btn-sm btn-default  ds-cancel-list-btn  pull-right">Cancel</button>
                                                            </div>
                                                        </div>
                                
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                    </div>
                                    
                                                                       
                                    <div class="col-lg-3 col-md-6 col-sm-6"> 
                                        <div class="form-group">
                                            <label for="cmbcontype">Designation <span class="redstar">*</span></label>
                                            <div class="ds-divselect-wrapper cat-name">
                                            <div class="ds-input">
                                                <input type="hidden" name="dest" value="">
                                                <input type="hidden" name="desig" id = "desig" value = "<?= $desi ?>">
                                                <input type="text" name="desig_name"  autocomplete="off" placeholder="Select Designation"  class="input-box4 form-control" value = "<?= $desiname ?>">
                                            </div>
                                                <div class="list-wrapper">
                                                    <div class="ds-list" style="display: none;">
                                
                                                        <ul class="input-ul4" tabindex="0" id="inpUl4">
                                                            <li tabindex="1" class="addnew">+ Add new</li>
                                
                                
                                                            <?php $qryitm = "SELECT id, name FROM `designation` order by name";
                                    $resultitm                                = $conn->query($qryitm);if ($resultitm->num_rows > 0) {
										$tabindex = 2;
										while ($rowitm = $resultitm->fetch_assoc()) {
                                        $tid = $rowitm["id"];
                                        $nm  = $rowitm["name"]; 
															
															?>
                                                                        <li  tabindex="<?=$tabindex?>" class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
                                                        <?php
										$tabindex++;						
										}} 
															?>
                                                        </ul>
                                                    </div>
                                                    <div class="ds-add-list" style="display: none;">

                                                        <div class="row">
                                                            <div class="col-lg-12 add-more-col">
                                                                <h3>Add new Designation</h3>
                                                                <hr>
                                                                <label for="">Name</label> <br>
                                                                <input type="text"  name="" autocomplete="off" class="Name addinpBox form-control" id="">
                                                                
                                
                                                            </div>
                                                            <div class="col-lg-12">
                                                            	
                                                                 
																
																<button type = "button" class="btn btn-sm btn-default  ds-add-list-btn-desi pull-right" style="margin-left: 5px;">Save</button>
																<button type = "button" class="btn btn-sm btn-default  ds-cancel-list-btn  pull-right">Cancel</button>
                                                            </div>
                                                        </div>
                                
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                    </div>

                                      <div class="col-lg-3 col-md-6 col-sm-6">

                                            <div class="form-group">

                                                <label for="cmbprdtp">Report to<span class="redstar">*</span> </label>

                                                <div class="form-group styled-select">

                                                <select name="repto" id="repto" class="form-control" required>
                                                    <option value = ""> Select Report to</option>
                                                

<?php $qryrepto="SELECT `id`, concat(`firstname`, ' ', `lastname`) empname FROM `employee` order by empname"; $resultrepto = $conn->query($qryrepto); if ($resultrepto->num_rows > 0) {while($rowrepto = $resultrepto->fetch_assoc()) 

      { 

          $reptoid= $rowrepto["id"];  $reptonm=$rowrepto["empname"];

?>                                                          

                                                    <option value="<?php echo $reptoid; ?>" <?php if ($repto == $reptoid) { echo "selected"; } ?>><?php echo $reptonm; ?></option>

<?php  }}?>                                                       

                                                  </select>

                                                  </div>

                                          </div>        

                                        </div>

                                        

                                    </div>

                                </div>

                            </div> 

                            <!-- /#end of panel -->      

                            <div class="button-bar">

                                <?php if($mode==2) { ?>

    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update item"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->

                                <?php } else {?>

                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Item"  id="submit" >

                                <?php } ?>  
                            <a href = "./hractionList.php?pg=1&mod=4">
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
<?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
<script>
            $(document).on("change", ".dl-cmborg", function() {
                var g = $(this).val();
                var id = $('#cmbassign1 option[value="' + g +'"]').attr('data-value');
                $('#empid').val(id);
                //alert(id);
        
        
        	});
</script>
        
<script>

// Action Type
$(document).ready(function(){



             //Input Click
					
  
  $('.input-box').focus(function(){
    $(this).select();
  });
  
            $('.input-box').on("focus click keyup", function(){
                 //console.log("d1");
                 $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
                // $(this).find('.ds-add-list').attr('style','display:none');
            });

            //Option's value shows on input box

            //$('.input-ul li').click(function(){
  					$('.input-ul').on("click","li", function(e){
               // console.log(this);


                if(!$(this).hasClass("addnew")){
                    


                        let litxt= $(this).text();
                        let lival= $(this).val();

                        $("#acty").val(lival);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box').val(litxt);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value',litxt);
                        $(this).closest('.ds-list').attr('style','display:none');  
                                  
                }

         

            });

			
			function addNew(e){
                $(e).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
                $(e).closest('.ds-list').attr('style','display:none');				
			}
			
            // New input box display

            $('.input-ul .addnew').click(function(){
				addNew(this);
                
            });
			
			$(".ds-cancel-list-btn").click(function(){ 
				$(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:none');
			 });

            // New-Input box's value display on old-input box

            $('.ds-add-list-btn').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
				if(x.length>0){
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
                        url:"phpajax/divSelectActType.php",
                        method:"POST",
                        data:{newItem: x},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#acty").val(res.id);
                                $('.display-msg').html(res.name);
                                $('.input-box').attr('value',res.name);
								$("#inpUl").append("<li class='pp1' value = '"+res.id+"'>"+res.name+"</li>");
                                

                            }
                    });
	             }
			}else{ 
				alert('Please enter a category name');
			}

            });



            $(document).mouseup(function (e) {
				
                if ($(e.target).closest(".ds-list").length === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length  === 0) {
                    $(".ds-add-list").hide();
                }
            });




            $('.input-box').on("keyup", function(e) {
			   
			    		var searchKey = $(this).val().toLowerCase();
              
              
             // if(searchKey.length>0){
                
                $("#inpUl li").filter(function() {
                	$(this).toggle($(this).text().toLowerCase().indexOf(searchKey) > -1);
                  
                  		if(e.keyCode == 40){
                        $('#inpUl li').removeClass('active');
                        $(this).next().focus().addClass('active');
                        return false;
                      } 
                });
                
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			   			 $(this).closest('.ds-divselect-wrapper').find('.input-ul li').click(function(){
                //$(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")").click(function(){	
			    

					// console.log(this)
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
                        $(this).closest('.ds-divselect-wrapper').find(".input-box").val(x);
                        $(this).closest('.ds-list').attr('style','display:none');
                      
                      
                     
                    }
					
                })
           // }
                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){

                    $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                    $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
                });
				
				
					 if (e.keyCode == 40){  
					 //alert("Enter CLicked");
					 $('#inpUl li').first().focus().addClass('active');
				 }
              
	            

			});

	$('#inpUl').on('focus', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){ 
      
      $this = $(this);
      $('#inpUl li').removeClass('active');
			$this.addClass('active');
			$this.closest('#inpUl').scrollTop($this.index() * $this.outerHeight());
    }
    
    }).on('keydown', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){
      $('#inpUl li').removeClass('active');
		$this = $(this);
		if(e.keyCode == 40){
      $('#inpUl li').removeClass('active');
			$this.next().focus().addClass('active');
			return false;
		} else if (e.keyCode == 38){        
			$this.prev().focus().addClass('active');
			return false;
		}
    
  }
	}).find('li').first().focus();	

  
  			$('#inpUl').on("keyup","li", function(e) {
				if (e.keyCode == 13){
          var txt = $(this).text();
					//alert(txt);
          if(!$(this).hasClass("addnew")){

          
          var tval= $(this).val();

          $("#acty").val(tval);              
          $('.input-box').val(txt);
          $('.input-box').focus();
          $('.ds-list').attr('style','display:none');
          }
				}
			});	
  
  
			
}); 

//Department
$(document).ready(function(){



             //Input Click
					
  
  $('.input-box1').focus(function(){
    $(this).select();
  });
  
            $('.input-box1').on("focus click keyup", function(){
                 //console.log("d1");
                 $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
                // $(this).find('.ds-add-list').attr('style','display:none');
            });

            //Option's value shows on input box

           
  					$('.input-ul1').on("click","li", function(e){
               // console.log(this);


                if(!$(this).hasClass("addnew")){


                        let litxt= $(this).text();
                        let lival= $(this).val();

                        $("#dept").val(lival);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box1').val(litxt);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box1').attr('value',litxt);
                        $(this).closest('.ds-list').attr('style','display:none');  
                                  
                }

         

            });

			
			function addNew(e){
                $(e).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
                $(e).closest('.ds-list').attr('style','display:none');				
			}
			
            // New input box display

            $('.input-ul1 .addnew').click(function(){
				addNew(this);
                
            });
			
			$(".ds-cancel-list-btn").click(function(){ 
				$(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:none');
			 });

            // New-Input box's value display on old-input box

            $('.ds-add-list-btn-dept').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
				if(x.length>0){
                $(this).closest('.ds-divselect-wrapper').find('.input-box1').attr('value', x);
				$(this).closest('.ds-divselect-wrapper').find('.input-box1').val(x);
                $(this).closest('.ds-add-list').attr('style','display:none');
                //$(this).closest('.ds-add-list').find('.addinpBox').val('');
                console.log($(this).closest('.ds-add-list').find('.addinpBox').val(""));
                // alert(x);
                // }
                action(x);
                function action(x){
                    $.ajax({
                        url:"phpajax/divSelectAll.php",
                        method:"POST",
                        data:{newItem: x, type: 'department'},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#dept").val(res.id);
                                $('.display-msg').html(res.name);
                                $('.input-box1').attr('value',res.name);
								$("#inpUl1").append("<li class='pp1' value = '"+res.id+"'>"+res.name+"</li>");
                                

                            }
                    });
	             }
			}else{ 
				alert('Please enter a category name');
			}

            });



            $(document).mouseup(function (e) {
				
                if ($(e.target).closest(".ds-list").length === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length  === 0) {
                    $(".ds-add-list").hide();
                }
            });




            $('.input-box1').on("keyup", function(e) {
			   
			    		var searchKey = $(this).val().toLowerCase();
              
              
             // if(searchKey.length>0){
                
                $("#inpUl1 li").filter(function() {
                	$(this).toggle($(this).text().toLowerCase().indexOf(searchKey) > -1);
                  
                  		if(e.keyCode == 40){
                        $('#inpUl1 li').removeClass('active');
                        $(this).next().focus().addClass('active');
                        return false;
                      } 
                });
                
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			   			 $(this).closest('.ds-divselect-wrapper').find('.input-ul1 li').click(function(){
			    

					// console.log(this)
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
                        $(this).closest('.ds-divselect-wrapper').find(".input-box1").val(x);
                        $(this).closest('.ds-list').attr('style','display:none');
                      
                      
                     
                    }
					
                })
           // }
                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){

                    $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                    $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
                });
				
				
					 if (e.keyCode == 40){  
					 //alert("Enter CLicked");
					 $('#inpUl1 li').first().focus().addClass('active');
				 }
              
	            

			});

	$('#inpUl1').on('focus', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){ 
      
      $this = $(this);
      $('#inpUl1 li').removeClass('active');
			$this.addClass('active');
			$this.closest('#inpUl1').scrollTop($this.index() * $this.outerHeight());
    }
    
    }).on('keydown', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){
      $('#inpUl1 li').removeClass('active');
		$this = $(this);
		if(e.keyCode == 40){
      $('#inpUl1 li').removeClass('active');
			$this.next().focus().addClass('active');
			return false;
		} else if (e.keyCode == 38){        
			$this.prev().focus().addClass('active');
			return false;
		}
    
  }
	}).find('li').first().focus();	

  
  			$('#inpUl1').on("keyup","li", function(e) {
				if (e.keyCode == 13){
          var txt = $(this).text();
					//alert(txt);
          if(!$(this).hasClass("addnew")){

          
          var tval= $(this).val();

          $("#dept").val(tval);              
          $('.input-box1').val(txt);
          $('.input-box1').focus();
          $('.ds-list').attr('style','display:none');
          }
				}
			});	
  
  
			
});

//Job Area
$(document).ready(function(){



             //Input Click
					
  
  $('.input-box2').focus(function(){
    $(this).select();
  });
  
            $('.input-box2').on("focus click keyup", function(){
                 //console.log("d1");
                 $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
                // $(this).find('.ds-add-list').attr('style','display:none');
            });

            //Option's value shows on input box

            
  					$('.input-ul2').on("click","li", function(e){
               // console.log(this);


                if(!$(this).hasClass("addnew")){


                        let litxt= $(this).text();
                        let lival= $(this).val();

                        $("#jobarea").val(lival);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box2').val(litxt);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box2').attr('value',litxt);
                        $(this).closest('.ds-list').attr('style','display:none');  
                                  
                }

         

            });

			
			function addNew(e){
                $(e).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
                $(e).closest('.ds-list').attr('style','display:none');				
			}
			
            // New input box display

            $('.input-ul2 .addnew').click(function(){
				addNew(this);
                
            });
			
			$(".ds-cancel-list-btn").click(function(){ 
				$(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:none');
			 });

            // New-Input box's value display on old-input box

            $('.ds-add-list-btn-ja').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
				if(x.length>0){
                $(this).closest('.ds-divselect-wrapper').find('.input-box2').attr('value', x);
				$(this).closest('.ds-divselect-wrapper').find('.input-box2').val(x);
                $(this).closest('.ds-add-list').attr('style','display:none');
                //$(this).closest('.ds-add-list').find('.addinpBox').val('');
                console.log($(this).closest('.ds-add-list').find('.addinpBox').val(""));
                // alert(x);
                // }
                action(x);
                function action(x){
                    $.ajax({
                        url:"phpajax/divSelectAll.php",
                        method:"POST",
                        data:{newItem: x, type: 'jobarea'},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#jobarea").val(res.id);
                                $('.display-msg').html(res.name);
                                $('.input-box2').attr('value',res.name);
								$("#inpUl2").append("<li class='pp1' value = '"+res.id+"'>"+res.name+"</li>");
                                

                            }
                    });
	             }
			}else{ 
				alert('Please enter a category name');
			}

            });



            $(document).mouseup(function (e) {
				
                if ($(e.target).closest(".ds-list").length === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length  === 0) {
                    $(".ds-add-list").hide();
                }
            });




            $('.input-box2').on("keyup", function(e) {
			   
			    		var searchKey = $(this).val().toLowerCase();
              
              
             // if(searchKey.length>0){
                
                $("#inpUl2 li").filter(function() {
                	$(this).toggle($(this).text().toLowerCase().indexOf(searchKey) > -1);
                  
                  		if(e.keyCode == 40){
                        $('#inpUl2 li').removeClass('active');
                        $(this).next().focus().addClass('active');
                        return false;
                      } 
                });
                
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			   			 $(this).closest('.ds-divselect-wrapper').find('.input-ul2 li').click(function(){
			    

					// console.log(this)
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
                        $(this).closest('.ds-divselect-wrapper').find(".input-box2").val(x);
                        $(this).closest('.ds-list').attr('style','display:none');
                      
                      
                     
                    }
					
                })
           // }
                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){

                    $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                    $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
                });
				
				
					 if (e.keyCode == 40){  
					 //alert("Enter CLicked");
					 $('#inpUl2 li').first().focus().addClass('active');
				 }
              
	            

			});

	$('#inpUl2').on('focus', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){ 
      
      $this = $(this);
      $('#inpUl2 li').removeClass('active');
			$this.addClass('active');
			$this.closest('#inpUl2').scrollTop($this.index() * $this.outerHeight());
    }
    
    }).on('keydown', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){
      $('#inpUl2 li').removeClass('active');
		$this = $(this);
		if(e.keyCode == 40){
      $('#inpUl2 li').removeClass('active');
			$this.next().focus().addClass('active');
			return false;
		} else if (e.keyCode == 38){        
			$this.prev().focus().addClass('active');
			return false;
		}
    
  }
	}).find('li').first().focus();	

  
  			$('#inpUl2').on("keyup","li", function(e) {
				if (e.keyCode == 13){
          var txt = $(this).text();
					//alert(txt);
          if(!$(this).hasClass("addnew")){

          
          var tval= $(this).val();

          $("#jobarea").val(tval);              
          $('.input-box2').val(txt);
          $('.input-box2').focus();
          $('.ds-list').attr('style','display:none');
          }
				}
			});	
  
  
			
});

//Job Type
$(document).ready(function(){



             //Input Click
					
  
  $('.input-box3').focus(function(){
    $(this).select();
  });
  
            $('.input-box3').on("focus click keyup", function(){
                 //console.log("d1");
                 $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
                // $(this).find('.ds-add-list').attr('style','display:none');
            });

            //Option's value shows on input box

            
  					$('.input-ul3').on("click","li", function(e){
               // console.log(this);


                if(!$(this).hasClass("addnew")){


                        let litxt= $(this).text();
                        let lival= $(this).val();

                        $("#jobtype").val(lival);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box3').val(litxt);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box3').attr('value',litxt);
                        $(this).closest('.ds-list').attr('style','display:none');  
                                  
                }

         

            });

			
			function addNew(e){
                $(e).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
                $(e).closest('.ds-list').attr('style','display:none');				
			}
			
            // New input box display

            $('.input-ul3 .addnew').click(function(){
				addNew(this);
                
            });
			
			$(".ds-cancel-list-btn").click(function(){ 
				$(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:none');
			 });

            // New-Input box's value display on old-input box

            $('.ds-add-list-btn-jt').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
				if(x.length>0){
                $(this).closest('.ds-divselect-wrapper').find('.input-box3').attr('value', x);
				$(this).closest('.ds-divselect-wrapper').find('.input-box3').val(x);
                $(this).closest('.ds-add-list').attr('style','display:none');
                //$(this).closest('.ds-add-list').find('.addinpBox').val('');
                console.log($(this).closest('.ds-add-list').find('.addinpBox').val(""));
                // alert(x);
                // }
                action(x);
                function action(x){
                    $.ajax({
                        url:"phpajax/divSelectAll.php",
                        method:"POST",
                        data:{newItem: x, type: 'jobtype'},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#jobtype").val(res.id);
                                $('.display-msg').html(res.name);
                                $('.input-box3').attr('value',res.name);
								$("#inpUl3").append("<li class='pp1' value = '"+res.id+"'>"+res.name+"</li>");
                                

                            }
                    });
	             }
			}else{ 
				alert('Please enter a category name');
			}

            });



            $(document).mouseup(function (e) {
				
                if ($(e.target).closest(".ds-list").length === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length  === 0) {
                    $(".ds-add-list").hide();
                }
            });




            $('.input-box3').on("keyup", function(e) {
			   
			    		var searchKey = $(this).val().toLowerCase();
              
              
             // if(searchKey.length>0){
                
                $("#inpUl3 li").filter(function() {
                	$(this).toggle($(this).text().toLowerCase().indexOf(searchKey) > -1);
                  
                  		if(e.keyCode == 40){
                        $('#inpUl3 li').removeClass('active');
                        $(this).next().focus().addClass('active');
                        return false;
                      } 
                });
                
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			   			 $(this).closest('.ds-divselect-wrapper').find('.input-ul3 li').click(function(){
			    

					// console.log(this)
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
                        $(this).closest('.ds-divselect-wrapper').find(".input-box3").val(x);
                        $(this).closest('.ds-list').attr('style','display:none');
                      
                      
                     
                    }
					
                })
           // }
                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){

                    $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                    $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
                });
				
				
					 if (e.keyCode == 40){  
					 //alert("Enter CLicked");
					 $('#inpUl3 li').first().focus().addClass('active');
				 }
              
	            

			});

	$('#inpUl3').on('focus', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){ 
      
      $this = $(this);
      $('#inpUl3 li').removeClass('active');
			$this.addClass('active');
			$this.closest('#inpUl3').scrollTop($this.index() * $this.outerHeight());
    }
    
    }).on('keydown', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){
      $('#inpUl3 li').removeClass('active');
		$this = $(this);
		if(e.keyCode == 40){
      $('#inpUl3 li').removeClass('active');
			$this.next().focus().addClass('active');
			return false;
		} else if (e.keyCode == 38){        
			$this.prev().focus().addClass('active');
			return false;
		}
    
  }
	}).find('li').first().focus();	

  
  			$('#inpUl3').on("keyup","li", function(e) {
				if (e.keyCode == 13){
          var txt = $(this).text();
					//alert(txt);
          if(!$(this).hasClass("addnew")){

          
          var tval= $(this).val();

          $("#jobtype").val(tval);              
          $('.input-box3').val(txt);
          $('.input-box3').focus();
          $('.ds-list').attr('style','display:none');
          }
				}
			});	
  
  
			
});

//Designation
$(document).ready(function(){



             //Input Click
					
  
  $('.input-box4').focus(function(){
    $(this).select();
  });
  
            $('.input-box4').on("focus click keyup", function(){
                 //console.log("d1");
                 $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
                // $(this).find('.ds-add-list').attr('style','display:none');
            });

            //Option's value shows on input box

            
  					$('.input-ul4').on("click","li", function(e){
               // console.log(this);


                if(!$(this).hasClass("addnew")){


                        let litxt= $(this).text();
                        let lival= $(this).val();

                        $("#desig").val(lival);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box4').val(litxt);
                        $(this).closest('.ds-divselect-wrapper').find('.input-box4').attr('value',litxt);
                        $(this).closest('.ds-list').attr('style','display:none');  
                                  
                }

         

            });

			
			function addNew(e){
                $(e).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
                $(e).closest('.ds-list').attr('style','display:none');				
			}
			
            // New input box display

            $('.input-ul4 .addnew').click(function(){
				addNew(this);
                
            });
			
			$(".ds-cancel-list-btn").click(function(){ 
				$(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:none');
			 });

            // New-Input box's value display on old-input box

            $('.ds-add-list-btn-desi').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
				if(x.length>0){
                $(this).closest('.ds-divselect-wrapper').find('.input-box4').attr('value', x);
				$(this).closest('.ds-divselect-wrapper').find('.input-box4').val(x);
                $(this).closest('.ds-add-list').attr('style','display:none');
                //$(this).closest('.ds-add-list').find('.addinpBox').val('');
                console.log($(this).closest('.ds-add-list').find('.addinpBox').val(""));
                // alert(x);
                // }
                action(x);
                function action(x){
                    $.ajax({
                        url:"phpajax/divSelectAll.php",
                        method:"POST",
                        data:{newItem: x, type: 'designation'},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#desig").val(res.id);
                                $('.display-msg').html(res.name);
                                $('.input-box4').attr('value',res.name);
								$("#inpUl4").append("<li class='pp1' value = '"+res.id+"'>"+res.name+"</li>");
                                

                            }
                    });
	             }
			}else{ 
				alert('Please enter a category name');
			}

            });



            $(document).mouseup(function (e) {
				
                if ($(e.target).closest(".ds-list").length === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length  === 0) {
                    $(".ds-add-list").hide();
                }
            });




            $('.input-box4').on("keyup", function(e) {
			   
			    		var searchKey = $(this).val().toLowerCase();
              
              
             // if(searchKey.length>0){
                
                $("#inpUl4 li").filter(function() {
                	$(this).toggle($(this).text().toLowerCase().indexOf(searchKey) > -1);
                  
                  		if(e.keyCode == 40){
                        $('#inpUl4 li').removeClass('active');
                        $(this).next().focus().addClass('active');
                        return false;
                      } 
                });
                
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			   			 $(this).closest('.ds-divselect-wrapper').find('.input-ul4 li').click(function(){
			    

					// console.log(this)
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
                        $(this).closest('.ds-divselect-wrapper').find(".input-box4").val(x);
                        $(this).closest('.ds-list').attr('style','display:none');
                      
                      
                     
                    }
					
                })
           // }
                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){

                    $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                    $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
                });
				
				
					 if (e.keyCode == 40){  
					 //alert("Enter CLicked");
					 $('#inpUl4 li').first().focus().addClass('active');
				 }
              
	            

			});

	$('#inpUl4').on('focus', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){ 
      
      $this = $(this);
      $('#inpUl4 li').removeClass('active');
			$this.addClass('active');
			$this.closest('#inpUl4').scrollTop($this.index() * $this.outerHeight());
    }
    
    }).on('keydown', 'li', function(e) {
    
    if(e.keyCode == 40 || e.keyCode == 38){
      $('#inpUl4 li').removeClass('active');
		$this = $(this);
		if(e.keyCode == 40){
      $('#inpUl4 li').removeClass('active');
			$this.next().focus().addClass('active');
			return false;
		} else if (e.keyCode == 38){        
			$this.prev().focus().addClass('active');
			return false;
		}
    
  }
	}).find('li').first().focus();	

  
  			$('#inpUl4').on("keyup","li", function(e) {
				if (e.keyCode == 13){
          var txt = $(this).text();
					//alert(txt);
          if(!$(this).hasClass("addnew")){

          
          var tval= $(this).val();

          $("#desig").val(tval);              
          $('.input-box4').val(txt);
          $('.input-box4').focus();
          $('.ds-list').attr('style','display:none');
          }
				}
			});	
  
  
			
});

</script>
</body>


</html>

<?php }?>