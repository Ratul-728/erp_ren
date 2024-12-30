<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];

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
        $qry="SELECT `id`, `dealtype`, `name`, `lead`, `leadcompany`, `value`, `curr`, `stage`, `status`, `remarks`, `lostreason`, `makeby`, `makedate`, DATE_FORMAT(`dealdate`, '%d/%m/%Y') `dealdate`,`accmgr`, DATE_FORMAT(`comercialdate`, '%d/%m/%Y') `comercialdate`, DATE_FORMAT(`nextfollowupdate`, '%d/%m/%Y') fldt FROM `deal` where id= ".$id; 
        //echo $qry; die;
        if ($conn->connect_error){ echo "Connection failed: " . $conn->connect_error; }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        
                        $iid=$row["id"];$name=$row["name"]; $lead=$row["lead"];$leadcompany=$row["leadcompany"];$value=$row["value"]; $currency=$row["curr"];
                        $stage=$row["stage"]; $status=$row["status"];  $details=$row["remarks"];  $lostreason=$row["lostreason"]; $dealdate=$row["dealdate"];
                        $accmgr=$row["accmgr"]; $comercialdatee=$row["comercialdate"];  $flupdt=$row["fldt"]; 
                        
                        //ORganization name
                        $qryOrgNm = "SELECT name FROM `organization` where id = ".$leadcompany;
                        $resultOrgNm = $conn->query($qryOrgNm);
                        while($rowOrgNm = $resultOrgNm->fetch_assoc()){
                            $leadOrgNm = $rowOrgNm["name"];
                        }
                        
            
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
        $iid=''; $name='';  $lead=''; $leadcompany='';  $value='0';  $currency='';  $stage='';  $status='';  $details='';  $lostreason=''; $dealdate='';$accmgr='';$comercialdatee='';
        $mode=1;$flupdt=''; //Insert mode
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'deal';
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
            <span>Deal  Details</span>
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
                        <form method="post" action="common/adddeal.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Sales Lead Form</h1></div>
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
                                            <div class="form-group">
                                                <label for="nm">Deal Name*</label>
                                                <input type="text" class="form-control" id="nm" name="nm" value="<?php echo $name;?>" required>
                                            </div>        
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbstage">Deal Stage*</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbstage" id="cmbstage" class="form-control" required>
                                                        <option value="">Select Stage</option>
<?php $qryitmct="SELECT `id`, `name` FROM `dealtype` order by sl"; $resultitmct = $conn->query($qryitmct); if ($resultitmct->num_rows > 0) {while($rowitmct = $resultitmct->fetch_assoc()) 
      {           $icid= $rowitmct["id"];  $icnm=$rowitmct["name"];
?> 
                                                        <option value="<?php echo $icid; ?>" <?php if ($stage == $icid) { echo "selected"; } ?>><?php echo $icnm; ?></option>
<?php  }}?>     
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                       
                                        <!--div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
											<lebel for="cmbsupnm">Organization</lebel>
                                            <div class="form-group">
                                                <div class="form-group styled-select">
                                                    <input list="cmbassign1" name ="cmbassign2" value = "<?= $leadOrgNm ?>" autocomplete="Search From list"  class="dl-cmborg datalist" placeholder="Select Item">
                                                    <datalist  id="cmbassign1" name = "cmbsupnm1" class="list-cmbassign form-control" >
                                                        <option value="">Select Organization</option>
    <?php $qryitm="SELECT * FROM `organization` order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"]; ?>
                                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                     </datalist> 
                                                     <input type = "hidden" name = "cmborg" id = "cmborg" value = "<?= $leadcompany ?>">
                                                </div>
                                            </div> 
                                            </div>
                                        </div-->
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6"> 
                                        <div class="form-group">
                                            <label for="cmbcontype">Organization*</label>
                                            <div class="ds-divselect-wrapper cat-name">
                                            <div class="ds-input">
                        <input type="hidden" name="dest" value="">
                        <input type="hidden" name="org_id" id = "org_id" value = "<?= $leadcompany ?>">
                         <input type="text" name="org_name" autocomplete="off"  class="input-box form-control" value = "<?= $leadOrgNm ?>">
                    </div>
                                                <div class="list-wrapper">
                                                    <div class="ds-list">
                                
                                                        <ul class="input-ul" id="inpUl">
                                                            <li class="addnew">+ Add new</li>
                                
                                
                                                            <?php $qryitm = "SELECT id, concat(name, '(', contactno, ')') orgname FROM `organization` order by name";
                                    $resultitm                                = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                        $tid = $rowitm["id"];
                                        $nm  = $rowitm["orgname"]; ?>
                                                                        <li class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
                                                        <?php }} ?>
                                                        </ul>
                                                    </div>
                                                    <div class="ds-add-list">
                                                        <h3>Add new Item</h3>
                                                        <hr>
                                                        <label for="">Name</label> <br>
                                                        <input type="text" name="" autocomplete="off" class="Name addinpBox form-control" id="">
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-lg-6 add-more-col">
                                                                <button type="button" class="more-info">+add more info</button>
                                
                                                            </div>
                                                            <div class="col-lg-6">
                                                                 <button type = "button" class="primary ds-add-list-btn ">Save</button>
                                                            </div>
                                                        </div>
                                
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                    </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbsupnm">Contact/Lead Name*</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbsupnm" id="cmbsupnm" class="cmd-child form-control" required>
                                                <option value="">Select Name</option>
                                                        <?php $qrycont="SELECT `id`, `name`  FROM `contact`  WHERE `contacttype` in (1,3)  order by name"; $resultcont = $conn->query($qrycont); if ($resultcont->num_rows > 0) {while($rowcont = $resultcont->fetch_assoc()){
                                                        	$tid= $rowcont["id"];  $nm=$rowcont["name"];
                                                        ?>
                                                        <option value="<?php echo $tid; ?>" <?php if ($lead == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                                                        <?php 
    													 }
    													}
    													?>     
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">Deal Date*</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="ddt" name="ddt" value="<?php echo $dealdate;?>" required>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div>

                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbhrmgr">Account Manager*</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbhrmgr" id="cmbhrmgr" class="form-control" required>
                                                <option value="">Select Account Manager</option>
<?php $qryhrm="SELECT h.`id`,concat(e.`firstname`,' ',e.`lastname`) `emp_id` FROM `hr` h,`employee` e where h.`emp_id`=e.`employeecode` order by emp_id"; $resulthrm = $conn->query($qryhrm); if ($resulthrm->num_rows > 0) {while($rowhrm = $resulthrm->fetch_assoc()) 
      { 
          $hridm= $rowhrm["id"];  $hrnmm=$rowhrm["emp_id"];
?>                                                          
                                                    <option value="<?php echo $hridm; ?>" <?php if ($accmgr == $hridm) { echo "selected"; } ?>><?php echo $hrnmm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                                  </div>
                                          </div>        
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="cmdt">Commercial Date*</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="cmdt" name="cmdt" value="<?php echo $comercialdatee;?>" >
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="fldt">Next Folowup Date*</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="fldt" name="fldt" value="<?php echo $flupdt;?>" >
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbstat">Deal Status*</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbstat" id="cmbstat" class="form-control" required>
                                                        <option value="">Select Status</option>
<?php $qryitmct="SELECT `id`, `name` FROM `dealstatus`  order by name"; $resultitmct = $conn->query($qryitmct); if ($resultitmct->num_rows > 0) {while($rowitmct = $resultitmct->fetch_assoc()) 
      { 
          $icid= $rowitmct["id"];  $icnm=$rowitmct["name"];
?> 
                                                        <option value="<?php echo $icid; ?>" <?php if ($status == $icid) { echo "selected"; } ?>><?php echo $icnm; ?></option>
<?php  }}?>
                                                    </select>
                                                </div>
                                            </div>  
                                        </div>

                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmblost">Deal Lost Reason</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmblost" id="cmblost" class="form-control">
                                                        <option value="">Select Status</option>
<?php $qryitmct="SELECT `id`, `name` FROM `deallostreason`  order by name"; $resultitmct = $conn->query($qryitmct); if ($resultitmct->num_rows > 0) {while($rowitmct = $resultitmct->fetch_assoc()) 
      { 
          $icid= $rowitmct["id"];  $icnm=$rowitmct["name"];
?>       
                                                        <option value="<?php echo $icid; ?>" <?php if ($lostreason == $icid) { echo "selected"; } ?>><?php echo $icnm; ?></option>
<?php  }}?>
                                                    </select>
                                                </div>
                                            </div>  
                                        </div>
                                        
                                         <br>
                                    <div class="po-product-wrapper"> 
                                        <div class="color-block">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
		                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10"> Select Item </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> Select Unit </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Quantity</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> OTC </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Quantity</h6>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">MRC </h6>
                                                </div>
                                                
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Currency</h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Unit Total </h6>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Remarks </h6>
                                                </div>
                                                 <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header">Probability </h6>
                                                </div>
                                        </div>
	                                        </div>
<?php if($mode==1){?> 	                     
                                            
	                                        <div class="toClone">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemname-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
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
          	                                    <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for unit-->
                                                    <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                                <option value="">Select*</option>
 <?php $qryunit="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultunit = $conn->query($qryunit); if ($resultunit->num_rows > 0) {while($rowunit = $resultunit->fetch_assoc()) 
              { 
                  $unitid= $rowunit["id"];  $unitnm=$rowunit["name"];
    ?>                                                          
                                                                <option value="<?php echo $unitid; ?>"><?php echo $unitnm; ?></option>
     <?php  }}?>                                                   
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for unit-->
          	                                    <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for otc-->
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" placeholder="Quantity" name="quantity_otc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" placeholder="OTC" name="unitprice_otc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for otc-->
     	                                        <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for mrc-->
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_mrc " id="quantity_mrc" placeholder="Quantity" name="quantity_mrc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_mrc unitPriceV2" id="unitprice_mrc" placeholder="MRC" name="unitprice_mrc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for mrc-->
                                                <div class="col-lg-1 col-md-3 col-sm-3  col-xs-6"> <!-- this block is for currency-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="curr[]" id="curr" class="form-control">
                                                                <option value="">Select Currency</option>
     <?php  $qrycur="SELECT `id`, `name`, `shnm` FROM `currency`  order by name"; $resultcur = $conn->query($qrycur); if ($resultcur->num_rows > 0){while($rowcur = $resultcur->fetch_assoc()) 
              { 
                  $crid= $rowcur["id"]; $crnm=$rowcur["shnm"];
        ?>          
                                                                <option value="<?php echo $crid; ?>" <?php if (1 == $crid) { echo "selected"; } ?>><?php echo $crnm; ?></option>
        <?php  }} ?>
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div> <!-- this block is for Currency-->
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for item total-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  name="unittotal[]">
                                                    </div>
                                                </div> <!-- this block is for item total-->
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6"> <!-- this block is for scale-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="scale[]" id="scale" class="form-control">
                                                                <option value="">Scale*</option>
                                                                <option value="L">Low</option>
                                                                <option value="M">Medium</option>
                                                                <option value="H">High</option> 
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for scale-->
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="probability" placeholder="Probability%"  name="probability[]">
                                                    </div>
                                                </div> <!-- this block is for probability-->
                                            </div>
<?php } else {
	$rCountLoop = 0;$itdgt=0;    
$itmdtqry="SELECT a.`id`, a.`socode`, a.`sosl`, a.`productid`,b.`name` itnm, a.`mu`, round(a.`qty`,0) qty,round(a.`qtymrc`,0)qtymrc, round(a.`otc`,2) otc, round(a.`mrc`,2)mrc,a.`scale`,a.`probability` ,a.`currency` FROM `dealitem` a,`item` b WHERE a.`productid`=b.`id` and   `socode`='".$iid."'";
$resultitmdt = $conn->query($itmdtqry); if ($resultitmdt->num_rows > 0) 
    {   while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $itmdtid= $rowitmdt["productid"]; $itmnm=$rowitmdt["itnm"];  $itdmu=$rowitmdt["mu"]; $itdqu=$rowitmdt["qty"];$itdqumrc=$rowitmdt["qtymrc"]; $itdotc=$rowitmdt["otc"]; $itdmrc=$rowitmdt["mrc"]; $itdscale=$rowitmdt["scale"];$itdprob=$rowitmdt["probability"];$currency=$rowitmdt["currency"];
                  $itdtot=($itdqu*$itdotc)+($itdqumrc*$itdmrc); $itdgt=$itdgt+$itdtot;
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toClone">
                                                <div class="col-lg-3 col-md-6 col-sm-6"><!-- this block is for itemname-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="<?php echo $itmnm; ?>">
                                                            <datalist id="itemName" class="list-itemName form-control"> 
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
          	                                    <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for unit-->
      	                                            <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName" value="<?php echo $itmdtid; ?>">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                     
 <?php //and `id`=".$itdmu."
 $qrymu="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["id"];  $mnm=$rowmu["name"];
    ?>                                                          
                                                                <option value="<?php echo $mid; ?>" <?php if ($itdmu == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
     <?php  }}?>                                                   
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for unit-->
          	                                    <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="row qtnrows"> <!-- this block is for otc-->
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" placeholder="Quantity"  value="<?php echo $itdqu;?>" name="quantity_otc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" placeholder="OTC"  value="<?php echo number_format($itdotc,2);?>" name="unitprice_otc[]">
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div> <!-- this block is for otc-->
         	                                    <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for mrc-->
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_mrc" id="quantity_mrc" placeholder="Quantity" value="<?php echo $itdqumrc;?>" name="quantity_mrc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_mrc unitPriceV2" id="unitprice_mrc" placeholder="MRC"  value="<?php echo number_format($itdmrc,2);?>"  name="unitprice_mrc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                              </div> <!-- this block is for mrc-->
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for Currency-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="curr[]" id="curr" class="form-control">
                                                                <option value="">Select Currency</option>
     <?php  $qrycur="SELECT `id`, `name`, `shnm` FROM `currency`  order by name"; $resultcur = $conn->query($qrycur); if ($resultcur->num_rows > 0){while($rowcur = $resultcur->fetch_assoc()) 
              { 
                  $crid= $rowcur["id"]; $crnm=$rowcur["shnm"];
        ?>          
                                                                <option value="<?php echo $crid; ?>" <?php if ($currency == $crid) { echo "selected"; } ?>><?php echo $crnm; ?></option>
        <?php  }} ?>
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div> <!-- this block is for Currency-->
                                                <div class="col-lg-1 col-md-6 col-sm-6"><!-- this block is for item total-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  value="<?php echo number_format($itdtot,2);?>"  name="unittotal[]">
                                                    </div>
                                                </div> <!-- this block is for item total--> 
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6"> <!-- this block is for scale-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="scale[]" id="scale" class="form-control">
                                                                <option value="">Scale</option>
                                                                <option value="L" <?php if ($itdscale == 'L') { echo "selected"; } ?>>Low</option>
                                                                <option value="M" <?php if ($itdscale == 'M') { echo "selected"; } ?>>Medium</option>
                                                                <option value="H" <?php if ($itdscale == 'H') { echo "selected"; } ?>>High</option>
                                                            </select>
                                                      </div>
                                                  </div>        
                                                </div> <!-- this block is for scale-->
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="probability" placeholder="Probability%"  name="probability[]" value="<?php echo $itdprob;?>">
                                                    </div>
                                                </div> <!-- this block is for probability-->
                                               <?php
                                                if($rCountLoop>0){
												?>
                                               		<div class="remove-icon"><a href="#" class="remove-po" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>
                                                <?php
													
												}
												$rCountLoop++;
												?>  
                                            </div>
<?php  } } else {?>
                                            <div class="toClone">
          	                                    <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for itemname-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <input list="itemName"  autocomplete="Search From list"  class="dl-itemName datalist" placeholder="Select Item">
                                                            <datalist  id="itemName" class="list-itemName form-control">
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
          	                                    <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for unit-->
                                                    <input type="hidden" placeholder="ITEM" name="itemName[]" class="itemName">
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="measureUnit[]" id="measureUnit" class="form-control">
                                                                <option value="">Unit</option>
<?php $qryunit="SELECT `id`, `name`, `description`, `st` FROM `mu` WHERE st=1  order by name"; $resultunit = $conn->query($qryunit); if ($resultunit->num_rows > 0) {while($rowunit = $resultunit->fetch_assoc()) 
          { 
              $unitid= $rowunit["id"];  $unitnm=$rowunit["name"];
?>                                                          
                                                                <option value="<?php echo $unitid; ?>"><?php echo $unitnm; ?></option>
 <?php  }}?>                                                 
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for unit-->
          	                                    <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for otc-->
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_otc" id="quantity_otc" placeholder="Quantity" name="quantity_otc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_otc unitPriceV2" id="unitprice_otc" placeholder="OTC" name="unitprice_otc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for otc-->
     	                                        <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for mrc-->
                                                    <div class="row qtnrows">
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control quantity_mrc " id="quantity_mrc" placeholder="Quantity" name="quantity_mrc[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control unitprice_mrc unitPriceV2" id="unitprice_mrc" placeholder="MRC" name="unitprice_mrc[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- this block is for mrc-->
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for Currency-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="curr[]" id="curr" class="form-control">
                                                                <option value="">Select Currency</option>
     <?php  $qrycur="SELECT `id`, `name`, `shnm` FROM `currency`  order by name"; $resultcur = $conn->query($qrycur); if ($resultcur->num_rows > 0){while($rowcur = $resultcur->fetch_assoc()) 
              { 
                  $crid= $rowcur["id"]; $crnm=$rowcur["shnm"];
        ?>          
                                                                <option value="<?php echo $crid; ?>" <?php if (1 == $crid) { echo "selected"; } ?>><?php echo $crnm; ?></option>
        <?php  }} ?>
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div> <!-- this block is for Currency-->
                                                <div class="col-lg-1 col-md-6 col-sm-6"> <!-- this block is for item total--> 
                                                    <div class="form-group">
                                                        <input type="text" class="form-control unitTotalAmount" id="unittotal" placeholder="Unit Total" disabled  name="unittotal[]">
                                                    </div>
                                                </div> <!-- this block is for item total--> 
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6"> <!-- this block is for scale-->
                                                    <div class="form-group">
                                                        <div class="form-group styled-select">
                                                            <select name="scale[]" id="scale" class="form-control">
                                                                <option value="">Scale</option>
                                                                <option value="L">Low</option>
                                                                <option value="M">Medium</option>
                                                                <option value="H">High</option>
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div> <!-- this block is for scale-->
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" id="probability" placeholder="Probability%"  name="probability[]">
                                                    </div>
                                                </div> <!-- this block is for probability-->
                                            </div> 
<?php }} ?>                                     		
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        
                                        
                                        
                                        <div class="well no-padding top-bottom-border grandTotalWrapper">
                                        <div class="row total-row">
                                            <div class="col-xs-offset-6 col-xs-6 col-sm-offset-8 col-sm-4  col-md-offset-8 col-md-4 col-lg-offset-9 col-lg-1">
                                            <div class="form-group grandTotalWrapper">
                                                <label>Total:* </label>
                                                <input type="text" class="form-control" id="grandTotal" value="<?php echo $itdgt;?>" disabled required>
                                              </div>
                                          </div>
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
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="details">Remarks </label>
                                                <textarea class="form-control" id="details" name="details" rows="4" ><?php echo $details;?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            
                            <!-- /#end of panel --> 
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Deal"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                            <a href = "http://bithut.biz/BitFlow/deal_view_inv.php?id=<?= $_GET["id"] ?>&mod=2">
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="button" name="editprint" value="View & Print"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                            </a>    
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Deal"  id="submit" >
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View & Print"  id="submit" >
                                <?php } ?>  
                            <a href = "./dealList.php?pg=1&mod=2">
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

var selectedValue = $(".cmb-parent").children("option:selected").val();
	
	
	
	 $.ajax({
            type: "POST",
            url: "cmb/deal_item_customer_name.php",
            data: { key : selectedValue,cusid:'<?=$lead?>' },
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
        
	
<?php
}
?>

$(document).on("change", ".cmb-parent", function() {
	
	//alert($(this).children("option:selected").val());
	//var root = $(this).parent().parent().parent().parent();	// root means .toClone
	var selectedValue = $(this).children("option:selected").val();
	
//	alert(selectedValue);
	
	 $.ajax({
            type: "POST",
            url: "cmb/deal_item_customer_name.php",
            data: { key : selectedValue,cusid:'<?=$lead?>' },
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