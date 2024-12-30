<?php
require "common/conn.php";
require "rak_framework/connection.php";
require "rak_framework/fetch.php";
session_start();
//ini_set('display_errors',1);





$usr=$_SESSION["user"];


if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    
    if($_POST){
        //print_r($_POST);die;
    }
    
    
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $aid= $_GET['id'];

    $qryusertp="SELECT `id`, user_tp FROM `hr` where id= ".$usr; 
   // echo $qryusertp;die;
    $resultusertp = $conn->query($qryusertp); 
    if ($resultusertp->num_rows > 0)
    {
        while($rowusrtp = $resultusertp->fetch_assoc()) 
        { 
           $user_tp=$rowusrtp["user_tp"]; 
        }
    }

    if ($res==4)
    {
        $qry="SELECT `id`, `hrid`, `menuid`, `menu_priv` FROM `hrAuth` where id= ".$aid; 
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
                       $uid=$row["id"];$hrid=$row["hrid"];
                        $menuid=$row["menuid"]; $menu_priv=$row["menu_priv"];  
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
        $uid='';$hrid='0'; $menuid='0'; $menu_priv='0'; 
    $mode=1;//Insert mode
                     
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'priv';
    $currPage = basename($_SERVER['PHP_SELF']);
	// load session privilege;
	include_once('common/inc_session_privilege.php');
	
	
    /*if ( isset( $_POST['submit'] ) ) {
           header("Location: ".$hostpath."/common/addpriv.php");
    }*/
    $mnhrid = $_POST['cmbempnm'];
    $modid  = $_POST["cmbmodule"]; if($modid == '') $modid = 0;
    if($mnhrid==''){$mnhrid=$hrid;}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<style>
.privillages{
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 15px;
}
.privillages > div{
    padding: 0px 5px;
    margin-right: 5px;
    margin-bottom: 5px;
    border-bottom: 0px solid #c0c0c0;
    border-radius: 0px;
/*     background-color: #eeeeee; */
}

.privillages  input{
    margin: 0;
    padding: 0;
}  
    
.row.table-bordered div[class*="col-"] {
    padding-top: 15px;
    
}



.icheck-primary{
    margin-bottom: 0!important;
}
    
.row-striped:nth-of-type(odd){
  background-color: #efefef;
}

.row-striped:nth-of-type(even){
  background-color: #ffffff;
}
    .row-striped input[readonly]{
    background-color:#ffffff;
}
</style>
<!-- Select2 CSS -->
<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />

<!-- Include Toastr CSS -->
<link href="js/plugins/toastr/toastr.min.css" rel="stylesheet">





<style>

.toast-top-right {
  top: 60px !important; /* Adjust this value as needed */
}
#toast-container > div {
  /*opacity: 1 !important;*/
}


/* Override Toastr default styles */
#toast-container {
  right: 10px;
}

/* Animations */
.toast {
  animation: slideInRight 0.5s, fadeOut 1s; /* Use desired durations */
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}


.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 34px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b{
    border: 0;
}

.select2-container--default .select2-selection {
  background-color: transparent;
  border: 0px solid #aaa!important;
  border-radius: 0px;
  cursor: text;
}

.select2-container .select2-selection {
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  min-height: 38px;
  user-select: none;
  -webkit-user-select: none;
}


.select2-container--default .select2-selection .select2-selection__choice {
  background-color: #e4e4e4;
  border: 1px solid #dbdbdb;
  border-radius: 2px;

  padding: 3px;
  padding-left: 0px;
  padding-left: 30px;
  font-size: 14px;
}

.select2-container--default .select2-selection .select2-selection__choice__remove {
  padding: 3px 8px;
}
    
    
.select2-container{
  width:102%!important;
    padding: 0;margin: 0;
}    
</style>
<body class="form">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
            <i class="fa fa-group  icon"></i>
            <span>User Privillage</span>
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
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
                    <!-- START PLACING YOUR CONTENT HERE -->
                          
                            <div class="panel panel-info">
    
                                <div class="panel-body">
                                    <span class="alertmsg"></span>
                                    <form method="post" action="priv.php?mod=5"  id="form1">   
                                    
                                    
                                  
      	                            
      	                            
                <div class="list-top-controls">
                    <div class="row border">

                        <div class="col-sm-3  text-nowrap">
                            <h6>Setting <i class="fa fa-angle-right"></i> Privileges</h6>
						</div>
              			<div class="col-sm-9 text-nowrap"> 
      	                      <div class="pull-right grid-panel form-inline">   
      	                      (Field Marked * are required)
      	                       </div>
      	                 </div>
      	                 
      	             </div>
      	             
      	         </div>    
      	                            
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>"> 
                                                <label for="email">Employee Name </label>
                                                <div class="form-group styled-select">
                                                <select name="cmbempnm" id="cmbempnm" class="select2basic form-control" >
                                                    <option value="0">Select User</option>
    <?php 
    if($user_tp==3)
    {
     $qry1="SELECT `id`, CONCAT(`hrName`,' - (',`resourse_id`,')') hrName FROM `hr` where active_st=1 ";
    }
    else
    {
        $qry1="SELECT `id`, CONCAT(`hrName`,' - (',`resourse_id`,')') hrName FROM `hr` where active_st=1 and user_tp <> 3 ";
    }
    
    //$qry1="SELECT `id`, CONCAT(`hrName`,' - (',`resourse_id`,')') hrName FROM `hr` where active_st=1 ";
	$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
    {while($row1 = $result1->fetch_assoc()) 
          {   $tid= $row1["id"];  $nm=$row1["hrName"]; 
    ?>  
													
                                                    <option value="<? echo $tid; ?>" <? if ($mnhrid == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
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
                                                <input type="hidden"  name="module" id="module" value="<?php echo $modid;?>"> 
                                                <label for="email">Module</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbmodule" id="cmbmodule" class="form-control select2basic" >
                                                    <option value="0">Select Module</option>
    <?php
    
   if($user_tp==3)
   {
       $qry1="SELECT `id`,Name FROM `module` where st=1 order by Name asc";
   }
   else
   {
       
        $qry1="SELECT `id`,Name FROM `module` where st=1  and sl <>10 order by Name asc";
   }
    
    $result1 = $conn->query($qry1); if ($result1->num_rows > 0)
    {
        while($row1 = $result1->fetch_assoc()) 
          {   $tid= $row1["id"];  $nm=$row1["Name"]; 
    ?>          
                                                    <option value="<? echo $tid; ?>" <? if ($modid == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php 
          }
    }      
    ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                              <label for="email">&nbsp; </label>
                                            <div class="form-group">
                                                <input class="btn btn-lg btn-default" type="submit" name="find" value="Get"  id="find" > 
                                            </div>
                                        </div>
                                    </div>
                                
                                
                                </form>
                                </div>
                            </div>
                         
                                    
                                    <?php
                                    if($mnhrid > 0 && $modid > 0){
                                    ?>                    
                        <form method="post" action="common/addpriv.php"  id="form1">
                            <div class="panel panel-info">
                                <div class="panel-body">

                                    
                                    
                                    <div class="row border">
                                       <div class="col-lg-2 col-md-6 col-sm-6">
                                        
                                            
                                              <div class="d-flex  align-items: center;">
                                                    <div><input type="checkbox" name="allprev" id="allprev" style="width:20px;float:left;text-align:left" value="1" class="form-control"></div>
                                                    <div style="height:50px;border:0px solid #000;padding:10px;">
                                                       
                                                            
                                                                 <b>Menu</b>
                                                           
                                                           <input type="hidden"  name="husrid" id="husrid" value="<?php echo $mnhrid;?>">
                                                       
                                                    </div>
                                              </div>
                                           
                                                
       
                                            </div>
                                        
                                        <div class="col-lg-5 col-md-6 col-sm-6">
                                            <div class="form-group">
                                               
                                                <label for="email">Privillage  </label>
                                               
                                            </div>        
                                        </div>
                                    </div>
<?php  


    
    


$qrymenu="select 
            a.id athid,
            m.menuNm,
            m.id,
            m.currSection, 
            a.menu_priv, action_btn 
            FROM mainMenu m  
            LEFT JOIN hrAuth a  on a.menuid=m.id and a.hrid=".$mnhrid." 
            WHERE (m.modl = $modid or $modid = 0) and m.activeSt=1 and lvl=1 order by m.menu_sl";
            
     
            
//echo $qrymenu;die; 
$resultmnu = $conn->query($qrymenu); 
if ($resultmnu->num_rows > 0)
    {
	
	while($rowmnu = $resultmnu->fetch_assoc()){
		
		
			$btnObj = json_decode($rowmnu['action_btn']);
            //$btnObj = array($rowmnu['action_btn']);
			
							  
			$tid= $rowmnu["id"];  
			$nm=$rowmnu["menuNm"]; 
			$priv=$rowmnu["menu_priv"]; 
			$atid=$rowmnu["athid"];
			$curSec= $rowmnu["currSection"];
		
		?>                                    
                                    <div class="row table-bordered row-striped privWrapper">
                                        <input type="hidden"  name="menuid[]" id="menuid_<?=$tid;?>" value="<?php echo $tid;?>">
                                        <input type="hidden"  name="auth[]" id="auth" value="<?php echo $atid;?>">
                                      	<div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group d-flex">
 
                                                    <div class="icheck-primary menuswitcher">
                                                        <?php
                                                        
                                                    
                                                    $record = fetchTotalRecordByCondition('hrAuth','hrid = "'.$_POST['cmbempnm'].'" AND menuid = "'.$tid.'"');
                                                    
                                                    $isChecked = ($record > 0)?'checked':'';
                                                        
                                                        foreach($btnObj as $btn){
                                                            
                                                            $keys .= $btn."_";
                                                        }
                                                        $parent_id = $tid;
                                                        ?>
														<input type="checkbox" <?=$isChecked?>  class="parent parent_<?=$tid;?>"  name="momdule_<?=$tid;?>" data-menuid="<?=$tid;?>" data-privset="<?=substr($keys, 0, -1)?>" value="1" id="momdule_<?=$tid;?>">
														<?php
														    $keys = "";
														?>
														
														<label for="momdule_<?=$tid;?>"> &nbsp;</label>
													</div>

												<?php
												//print_r($btnObj);
                                                //$testArr = json_decode('{"create","edit","delete","view","export","report","print","download","pay"}');
                                                //print_r($testArr);
												?>
                                                 <input  type="text" class="form-control" id="mn" name="mn" currsec=<?=$curSec?> value="<?php echo $nm;?>" readonly>
                                            </div>        
                                        </div>
                                        <div class="col-lg-10     col-md-6 col-sm-6">
                                            
		
												<div class="privillages">
												    
												    

												    
                                                    
											<?php
												foreach($btnObj as $btn){
                                                    
                                                    
                                                    $fetchValues = array('hrid' => $_POST['cmbempnm'],'menuid' => $tid);
                                                    $getVal = fetchSingleDataByArray('hrAuth',$fetchValues,"`$btn`");
                                                    
                                                    $isChecked = ($getVal == 1)?'checked':'';
                                                    
													echo '
                                                    <div>
													<div class="icheck-primary privwrap">
                                                       
														<input '.$isChecked.' type="checkbox" name="'.$btn.'_'.$tid.'" value="1"  id="'.$btn.$tid.'" >
														<label for="'.$btn.$tid.'"> '.ucwords(str_replace("-", " ", $btn)).'</label>
														</div>
                                                        </div>
													';
												}
											?>
												
											    </div>
                                            </div>
                                           
                                    </div>
<?php 

        $qrysubmenu="SELECT  
            m.id,  
            a.id athid, 
            m.menuNm, 
            m.currSection,  
            a.menu_priv, 
            action_btn 
            FROM 
            mainMenu m 
        LEFT JOIN 
            hrAuth a ON a.menuid = m.id AND a.hrid = ".$mnhrid." 
        WHERE (m.modl = $modid or $modid = 0)
            AND m.activeSt = 1 
            AND lvl = 2 
            AND parentnode = $tid 
        ORDER BY 
            m.menu_sl";  
            if($_SESSION['user'] == 51){
                //echo $qrysubmenu;die;       
            }
          
            
            $ressubmenu = $conn->query($qrysubmenu);  
            if ($ressubmenu->num_rows > 0){
            	
            	while($rowsub = $ressubmenu->fetch_assoc()){
            	    $btnObj = json_decode($rowsub['action_btn']);
            	    $tid= $rowsub["id"];  
        			$nm=$rowsub["menuNm"]; 
        			$priv=$rowsub["menu_priv"]; 
        			$atid=$rowsub["athid"];
        			$curSec= $rowsub["currSection"]; 
			 
			
            	    ?>
            	    
            	    
            	    <div class="row table-bordered row-striped privWrapper">
                                        <input type="hidden"  name="menuid[]" id="menuid_<?=$tid;?>" value="<?php echo $tid;?>">
                                        <input type="hidden"  name="auth[]" id="auth" value="<?php echo $atid;?>">
                                      	<div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group d-flex">
 
                                                    <div class="icheck-primary menuswitcher">
                                                        <?php
                                                        
                                                    
                                                    $record = fetchTotalRecordByCondition('hrAuth','hrid = "'.$_POST['cmbempnm'].'" AND menuid = "'.$tid.'"');
                                                    
                                                    $isChecked = ($record > 0)?'checked':'';
                                                        
                                                        foreach($btnObj as $btn){
                                                            
                                                            $keys .= $btn."_";
                                                        }
                                                        ?>
														<input type="checkbox" <?=$isChecked?> data-parent="<?=$parent_id?>" class="child  child_<?=$parent_id;?>"  name="momdule_<?=$tid;?>" data-menuid="<?=$tid;?>" data-privset="<?=substr($keys, 0, -1)?>" value="1" id="momdule_<?=$tid;?>">
														<?php
														    $keys = "";
														?>
														
														<label for="momdule_<?=$tid;?>"> &nbsp;</label>
													</div>

												<?php
												//print_r($btnObj);
                                                //$testArr = json_decode('{"create","edit","delete","view","export","report","print","download","pay"}');
                                                //print_r($testArr);
												?>
                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  type="text" class="form-control" id="mn" name="mn" currsec=<?=$curSec?> value="<?php echo $nm;?>" readonly>
                                            </div>        
                                        </div>
                                        <div class="col-lg-10     col-md-6 col-sm-6">
                                            
		
												<div class="privillages">
												    
												    
  
												    
                                                    
											<?php
												foreach($btnObj as $btn){
                                                    
                                                    
                                                    $fetchValues = array('hrid' => $_POST['cmbempnm'],'menuid' => $tid);
                                                    $getVal = fetchSingleDataByArray('hrAuth',$fetchValues,"`$btn`");
                                                    
                                                    $isChecked = ($getVal == 1)?'checked':'';
                                                    
													echo '
                                                    <div>
													<div class="icheck-primary privwrap">
                                                       
														<input '.$isChecked.' type="checkbox" name="'.$btn.'_'.$tid.'" value="1"  id="'.$btn.$tid.'" >
														<label for="'.$btn.$tid.'"> '.ucwords(str_replace("-", " ", $btn)).'</label>
														</div>
                                                        </div>
													';
												}
											?>
												
											    </div>
                                            </div>
                                           
                                    </div>
            	    
            	    
            	    <?php
            	}
            }

	}//while($rowmnu = $resultmnu->fetch_assoc()){
        
}//if ($resultmnu->num_rows > 0)
?> 






                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                          
                                <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Cancel"  id="cancel" >
                            </div>   
                            

                            
                        </form> 
                            <?php
}//if($mnhrid > 0 && $modid > 0){
                            ?>
                            
                            <!-- START PLACING YOUR CONTENT HERE -->          
                    </p> 
                </div>
            </div>    
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
    
    
<?php    include_once('common_footer.php');



if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
 <!-- Select2 JS -->
<script src="js/plugins/select2/select2.min.js"></script>

<!-- Include Toastr JS -->
<script src="js/plugins/toastr/toastr.min.js"></script>


<script>
  $(document).ready(function() {
      // Customized Toastr notification
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-top-right",
  
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
 

 
      
  
    $('.select2basic').select2();
    
    
    

    
    
    
    
  });
  
  
  
  
</script>


<?php
if($_POST['cmbempnm']){
?>
 <script>
 
 
$(document).ready(function(){
    
    
$(".menuswitcher  input[type=checkbox]").on('change',function() {

    
    var isChecked = $(this).is(":checked");
                var root = $(this).closest(".privWrapper");
                 var chkValue;
              	 var privset = $(this).data('privset');
                 var mnuId = $(this).data('menuid');
                 
                 
                if (isChecked) {
                    chkValue = 1;
                    
                        
                        root.find('input[type="checkbox"]').prop('checked', true);
                } else {
                    chkValue = 0;
                    root.find('input[type="checkbox"]').prop('checked', false);
                }
              
              // alert('privset:'+privset+' | menuid:'+mnuId+' | userid:<?=$_POST['cmbempnm']?> | val:'+chkValue);
               
        $.ajax({
          url: 'phpajax/setpriv.php', 
          method: 'POST',
          data: {
            postaction:'menuswitcher',
            privset: privset,
            menuid: mnuId,
            val:chkValue,
            targetuser:<?=$_POST['cmbempnm']?>,
          },
          success: function(response) {
            // Handle the successful response here
            //console.log('Success:', response);
            //messageAlert(response);
            toastAlert(response,'success');
          },
          error: function(xhr, textStatus, errorThrown) {
            // Handle any errors that occur during the request
            console.error('Error:', errorThrown);
            toastAlert(response,'error');
          }
        });               
               
    
    
});    
    
  
            //$(".icheck-primary  input[type=checkbox]").change(function() {
                
            //$(".icheck-primary  input[type=checkbox]").on('ifChanged',function() {
            $(".privwrap  input[type=checkbox]").on('change',function() {
                
                var isChecked = $(this).is(":checked");
                //var isChecked = this.checked;
                var chkValue;
              	var thisKey = $(this).attr('name');
                 var part = thisKey.split("_");
                 var key = part[0];
                 var mnuId = part[1];

                if (isChecked) {
                    chkValue = 1;
                } else {
                    chkValue = 0;
                }
              
               //alert('key:'+key+' | menuid:'+mnuId+' | val:'+chkValue);
              
              
        $.ajax({
          url: 'phpajax/setpriv.php', 
          method: 'POST',
          data: {
            postaction:'privswitcher',
            key: key,
            menuid: mnuId,
            val:chkValue,
            targetuser:<?=$_POST['cmbempnm']?>,
          },
          success: function(response) {
            // Handle the successful response here
            //console.log('Success:', response);
            //messageAlert(response);
            toastAlert(response,'success');
          },
          error: function(xhr, textStatus, errorThrown) {
            // Handle any errors that occur during the request
            console.error('Error:', errorThrown);
            toastAlert(response,'error');
          }
        });
  
   });
});//$(document).ready(function() {    
</script>   
    <?php
    }
    ?>  
    

    
<script>



  $('#find').click(function(e){
    //e.preventDefault();
  
  
   
   if($("#cmbempnm").val() ==0 || $("#cmbmodule").val()==0){
       alert("Please select User and Module from dowpdown menu to set privilege");
       return false;
   }else{
       return true; 
   }
   

  });

</script>

<script>

$(document).ready(function(){
    
    $(".parent").on('click', function() {
		
        var parentId = $(this).data('menuid');
        
        if($(this).is(':checked')){
            
            $('.child_' + parentId).each(function(){
                var secondThis = $(this);
                if(!secondThis.is(':checked')){
                    secondThis.trigger("click");
                    //secondThis.prop('checked', true);
                }
            });
        }else{
            // alert(parentId);
               $('.child_' + parentId).each(function(){
                var secondThis = $(this);
                   if(secondThis.is(':checked')){
                   		secondThis.trigger("click");
                   }
                   //secondThis.prop('checked', false);
            });
        }
    });
});


$(document).ready(function(){
    
    $(".child").on('click', function() {
		
        var parentId = $(this).data('parent');
        
        
         if(parentId){
              if($(this).is(':checked') && !$('.parent_'+parentId).is(':checked')){
                setTimeout(() => {
                    $('.parent_' + parentId).trigger('click');
                }, 0); // No delay for the first action

                setTimeout(() => {
                    $('.child_' + parentId).trigger('click');
                }, 20); // 20ms delay for the second action

                setTimeout(() => {
                    $(this).trigger('click');
                }, 40); // 40ms delay (20ms after the second action)
              }
         }
    });
});


</script>
    
</body>
</html>
<?php }?>