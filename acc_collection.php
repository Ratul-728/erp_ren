<?php
require "common/conn.php";
include_once('rak_framework/fetch.php'); 
session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    $res  = $_GET['res'];
    $msg  = $_GET['msg'];
    $itid = $_GET['id'];

    if ($res == 1) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
    }
    if ($res == 2) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
    }

    if ($res == 4) {
        $qry = "SELECT c.`id`,DATE_FORMAT(c.trdt,'%e/%c/%Y')  `trdt`, c.`transmode`, c.`transref`, c.`chequedt`, c.`customerOrg`, c.`naration`, c.`amount`, c.`costcenter`,
                c.`chqclearst`, c.`cleardt`, c.`st`, c.`makeby`, c.`makedt`,c.`invoice`,c.currencycode, o.name cname, c.glac
                FROM `collection` c left join organization o ON o.id = c.customerOrg
                where c.id= " . $itid;
        //echo $qry; die;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $fcid      = $row["id"];
                    $trdt      = $row["trdt"];
                    $transmode = $row["transmode"];
                    $transref  = $row["transref"];
                    $chequedt  = $row["chequedt"];
                    $customer  = $row["customerOrg"];
                    $cname     = $row["cname"];

                    $naration   = $row["naration"];
                    $amount     = $row["amount"];
                    $costcenter = $row["costcenter"];
                    $inv        = $row["invoice"];
                    $curr       = $row["currencycode"];
                    $glac = $row["glac"];
                    $ryuo = "SELECT `soid` FROM `invoice`  WHERE `invoiceno` = '$transref'";
                    $resultuo = $conn->query($ryuo);
                    while ($rowuo = $resultuo->fetch_assoc()) {
                        $quotation = $rowuo["soid"];
                    }
                    
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    } else {
        $fcid      = '';
        $trdt      = date('d/m/Y');
        $transmode = '';
        $transref  = '';
        $chequedt  = '';
        $customer  = '';
        $cname     = '';

        $naration   = '';
        $amount     = '';
        $costcenter = '';
        $inv        = '';
        $curr       = 1;
        $mode       = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'collection';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>

<body class="form">
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Fund Receive Details</span>
        </div>
        <?php include_once 'menu.php'; ?>
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
                        <form method="post" action="common/addacc_collection.php"  id="form1"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">

				                <div class="panel-body">
                                    <span class="alertmsg"></span>

                                    <!-- <br> <p>(Field Marked * are required) </p> -->

                                    <div class="row">
      		                            <div class="col-sm-12">
      		                                  <div class="col-sm-3 text-nowrap">
                                                <h6>Billing <i class="fa fa-angle-right"></i> Fund receive Information</h6>
                                              </div>
                                          </div>
                                    </div>
                                   <div class="row new-layout-header">
                                       
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="servtp">Service Type</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbservtp" id="cmbservtp" class="dl-servtp" >
                                                        <option value="q" >Quotation</option>
                                                        <option value="s" >Service</option>
                                                        <option value="m" >Maintenance </option>
                                                        <option value="o" >Others </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                       
                                       
                                       <?php if($mode == 2){ ?>
                                       <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Qoutation</label>
                                                <input type="text" class="form-control" id="qoutation" name="qoutation" value="<?php echo $quotation; ?>" disabled>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                    
                                    <style>
                						
                						.isservice{display: none;}
                						.ismaintanace{display: none;}
                						.isother{display: none;}
                					</style>
                					
                                        <div class="col-lg-3 col-md-6 col-sm-6 isquot">
                                            <div class="form-group">
                                                <label for="cmborg">Quotation </label>
                                                <div class="form-group styled-select">
                                                <select name="ref" id="ref" class="dl-ref" >
                                                	<option value="">Select Type</option>
													<?php $qryqutation = "SELECT q.id,q.socode,q.organization,q.invoiceamount,o.name org FROM quotation q left join organization o on q.organization=o.id where orderstatus='1' order by id desc ";
        $resultqout  = $conn->query($qryqutation);if ($resultqout->num_rows > 0) {while ($rowqot = $resultqout->fetch_assoc()) {
        $tid = $rowqot["id"];
        $nm  = $rowqot["socode"];
         $orga  = $rowqot["organization"];
          $organm  = $rowqot["org"];
          $pamount  = $rowqot["invoiceamount"];
        ?>
                                                    <option  data-org="<?=$organm?>" data-so="<?=$nm?>" data-cus="<?=$orga?>" data-amount="<?=$pamount?>" value="<?php echo $nm; ?>" <?php if ($transref == $nm) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                    <?php
}
    }
    ?>
                                                </select>
                                             </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 isservice">
                                            <div class="form-group">
                                                <label for="cmborg">Service </label>
                                                <div class="form-group styled-select">
                                                <select name="ref_service" id="ref_service" class="dl-ref" >
                                                	<option value="">Select Type</option>
													<?php $qryqutation = "SELECT q.id,q.`invoice` socode,q.`customer` organization,q.`invoiceamt` invoiceamount,o.name org FROM service_invoice q left join organization o on q.customer=o.id where `invoicest`='1' order by id desc ";
        $resultqout  = $conn->query($qryqutation);if ($resultqout->num_rows > 0) {while ($rowqot = $resultqout->fetch_assoc()) {
        $tid = $rowqot["id"];
        $nm  = $rowqot["socode"];
         $orga  = $rowqot["organization"];
          $organm  = $rowqot["org"];
          $pamount  = $rowqot["invoiceamount"];
        ?>
                                                    <option  data-org="<?=$organm?>" data-so="<?=$nm?>" data-cus="<?=$orga?>" data-amount="<?=$pamount?>" value="<?php echo $nm; ?>" <?php if ($transref == $nm) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                    <?php
}
    }
    ?>
                                                </select>
                                             </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 ismaintanace">
                                            <div class="form-group">
                                                <label for="cmborg">Maintanance </label>
                                                <div class="form-group styled-select">
                                                <select name="ref_maintanance" id="ref_maintanance" class="dl-ref" >
                                                	<option value="">Select Type</option>
													<?php $qryqutation = "select m.id,m.`do_number` socode,q.`organization` organization,m.total invoiceamount,o.name org from maintenance m,delivery_order d, quotation q,organization o where m.`do_number`=d.do_id and d.order_id=q.socode and q.organization=o.id; order by id desc ";
        $resultqout  = $conn->query($qryqutation);if ($resultqout->num_rows > 0) {while ($rowqot = $resultqout->fetch_assoc()) {
        $tid = $rowqot["id"];
        $nm  = $rowqot["socode"];
         $orga  = $rowqot["organization"];
          $organm  = $rowqot["org"];
          $pamount  = $rowqot["invoiceamount"];
        ?>
                                                    <option  data-org="<?=$organm?>" data-so="<?=$nm?>" data-cus="<?=$orga?>" data-amount="<?=$pamount?>" value="<?php echo $nm; ?>" <?php if ($transref == $nm) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                    <?php
}
    }
    ?>
                                                </select>
                                             </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 isother">
                                            <div class="form-group">
                                                <label for="cmborg">Others </label>
                                                <div class="form-group styled-select">
                                                 <input type="text" class="form-control " id="ref_other" name="ref_other" value="" autofocus="autofocus"  placeholder="Add a referance" >
                                             </div>
                                          </div>
                                        </div> 
                                    <?php } ?>
                                    
      		                            <div class="col-lg-10 col-md-10">
      		                                <div class="form-group">
                                                <!--<label for="ref">Subject*</label> -->
                                                <input type="text" class="form-control com-nar" id="descr" name="descr" value="<?php echo $naration; ?>" autofocus="autofocus"  placeholder="Add a Narration" required <?php if($mode == 2) echo "disabled" ?>>
                                            </div>
	                                   <!--     <h4></h4>
	                                        <hr class="form-hr">  -->

		                                    <input type="hidden"  name="exid" id="exid" value="<?php echo $exid; ?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
	                                    </div>
                                        <div class="col-lg-2 col-md-2 new-layout-amount ">

                                            <div class="form-group">
                                                <label for="amt">Amount<span class="redstar">*</span> </label>
                                                <input type="text" placeholder="Tk 0.00" class="form-control amount-fld" id="amt" name="amt" value="<?php echo $amount; ?>" required <?php if($mode == 2) echo "disabled" ?>>
                                            </div>

                                        </div>
            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="cd">Transaction Date <span class="redstar">*</span></label>
                                            <div class="input-group">
                                                <input type = "hidden" name = "fcid" value = "<?= $fcid ?>">
                                                <input type="text" class="form-control datepicker" id="trdt" name="trdt" value="<?php echo $trdt; ?>" required <?php if($mode == 2) echo "disabled" ?>>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>
                                        </div>

                                        <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmborg">Customer*</label>
                                                <div class="form-group styled-select">
                                                <select name="cmborg" id="cmborg" class="cmb-parent form-control" required>
                                                	<option value="">Select Type</option>
													<?php $qryorg = "SELECT distinct o.`id`,o.`name` FROM `contact` c,`organization` o where c.`organization`=o.`orgcode`  and c.`contacttype`=1  order by o.name";
    $resultorg                 = $conn->query($qryorg);if ($resultorg->num_rows > 0) {while ($roworg = $resultorg->fetch_assoc()) {
        $tid = $roworg["id"];
        $nm  = $roworg["name"];
        ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($customer == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                    <?php
}
    }
    ?>
                                                </select>
                                             </div>
                                          </div>
                                        </div-->

                                        <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmborg">Customer*</label>
                                                
                                                <div class="form-group styled-select">
                                                    <input list="cmborg1" name ="cmbassign2" value = "<?=$cname ?>" autocomplete="Search From list"  class="dl-cmborg datalist" placeholder="" required>
                                                    <datalist  id="cmborg1" name = "cmborg1" class="list-cmbassign form-control" >
                                                        <option value="">Select Customer</option>
                        <?php $qryitm = "SELECT `id`, `name`  FROM `organization` order by name";
    $resultitm                            = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
        $tid = $rowitm["id"];
        $nm  = $rowitm["name"]; ?>
                                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
                        <?php }} ?>
                                                    </datalist>
                                                    <input type = "hidden" name = "cmborg" id = "cmborg" value = "<?=$customer ?>">
                                                </div>
                                            </div>
                                        </div-->

                                        <div class="col-lg-3 col-md-6 col-sm-6"> 
                                            <div class="form-group">
                                                <label for="cmbcontype">Customer <span class="redstar">*</span></label>
                                                <div class="ds-divselect-wrapper cat-name">
                                                <div class="ds-input">
                            <input type="hidden" name="dest" value="">
                            <input type="hidden" name="org_id" id = "org_id" value = "<?= $customer ?>">
                             <input type="text" name="org_name" autocomplete="off"  class="input-box form-control" value = "<?= $cname ?>" required <?php if($mode == 2) echo "disabled" ?>>
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
                                            <label for="cmbmode"> Transfer Mode<span class="redstar">*</span></label>
                                            <div class="form-group styled-select">
												<?php
													//fetchComboHTML('paywith','paywith','form-control','transmode','name','id',$transmode);
													fetchComboHTMLv2('paywith','paywith','form-control','transmode','name','id','',' Paid With');
												?>
                                       
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="chqdt">Cheque Date </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" autocomplete="off" id="chqdt" name="chqdt" value="<?php echo $chequedt; ?>" <?php if($mode == 2) echo "disabled" ?>>
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>
                                    </div-->
                                    <style>
                						.ischeck{display: none;}
                						.ismobilewallet{display: none;}
                					</style>
                                    <div class="col-lg-3 col-md-6 col-sm-6 ischeck">
            						<div class="form-group">
            							<label for="paidmnt-cr">Cheque Number</label>
            							<input type="text" class="form-control checkno" placeholder="Check Number" id="checkno" name="checkno" >
            						</div>
                					</div>
                					<div class="col-lg-3 col-md-6 col-sm-6 ischeck">
                						<div class="form-group">
                							<label for="paidmnt-cr">Cheque Date</label>
                							<div class="input-group">
                    							<input type="text" class="form-control datepicker checkdate" placeholder="Check Date" id="chqdt" name="chqdt" value="<?php echo $chequedt; ?>">
                    							<div class="input-group-addon">
                                                   <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                						</div>
                					</div>	
                					
                					
                					
                					<!--Check date -->
                					
                					<div class="col-lg-3 col-md-6 col-sm-6 ischeck bankname">
                						<div class="form-group">
                							<label for="bank">Originating Bank </label>
                							<div class="form-group styled-select">
                								
                								<?php
                									//fetchComboHTMLv2('$cmbname',$cmbid,$cmbclass,$table,$name,$id,$selected,$defaultOptionTxt)
                								//	fetchComboHTMLv2('bank','bank','select2basic form-control','bank','name','id','','Select Bank');
                									fetchComboHTMLv2withcondition('bank','bank','select2basic form-control','bank','name','id','','Select Bank','isAccount in("Y","N")');
                									//fetchComboHTMLwidthCondition('bank','bank','form-control','bank','name','id','',' Bank','isAccount="y"');
                									
                								?>
                
                							</div>
                						</div>
                					</div>
                					<div class="col-lg-3 col-md-6 col-sm-6 ischeck depbankname">
                						<div class="form-group">
                							<label for="bank">Depositing Bank </label>
                							<div class="form-group styled-select">
                								
                								<?php
                									//fetchComboHTMLv2('$cmbname',$cmbid,$cmbclass,$table,$name,$id,$selected,$defaultOptionTxt)
                								//	fetchComboHTMLv2('bank','bank','select2basic form-control','bank','name','id','','Select Bank');
                									fetchComboHTMLv2withcondition('depbank','depbank','select2basic form-control','bank','name','id','','Select Bank','isAccount="y"');
                									//fetchComboHTMLwidthCondition('bank','bank','form-control','bank','name','id','',' Bank','isAccount="y"');
                									
                								?>
                
                							</div>
                						</div>
                					</div>
                					<!--Bank -->
                					<div class="col-lg-3 col-md-6 col-sm-6 ischeck photowrap">
                					    
                					   
                					    <strong style="display:block;margin-bottom:4px;">Upload Picture</strong>
                                        <div class="input-group">
                                            	
                                            <label class="input-group-btn">
                                                <span class="btn btn-primary btn-file btn-file">
                                                   <i class="fa fa-upload"></i> <input type="file" id="myFileInput" name="file" st_yle="visibility: hidden;">
                                                </span>
                                            </label>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                					    
                
                				
                					</div>
                                    
                                    <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="cmbsupnm">Customer Name*</label>
                                            <div class="form-group styled-select">
                                            <select name="cmbsupnm" id="cmbsupnm" class="form-control" >
    <?php
$qry1    = "SELECT `id`, `name`  FROM `contact` where contacttype = 1 order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                                <option value="<?echo $tid; ?>" <?if ($cusid == $tid) {echo "selected";} ?>><?echo $nm; ?></option>
    <?php }} ?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>-->

                                        <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbinv">Invoice No</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbinv" id="cmbinv" class="cmb-children form-control">
                                                	<option value="">Select Type</option>
													<?php $qryinv = "SELECT `id`, `invoiceNo` FROM `invoice`  order by invoiceNo ASC";
    $resultinv                 = $conn->query($qryinv);if ($resultinv->num_rows > 0) {while ($rowinv = $resultinv->fetch_assoc()) {
        $tid = $rowinv["id"];
        $nm  = $rowinv["invoiceNo"];
        ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($inv == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                    <?php
}
    }
    ?>
                                                </select>
                                             </div>
                                          </div>
                                        </div-->

      	                                <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="amt">Amount *</label>
                                                <input type="number" class="form-control" min="0" step="0.01" id="amt" name="amt" value="<?php echo $amount; ?>" required>


                                            </div>
                                        </div> -->
                                        <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbcc"> Currency</label>
                                                <div class="form-group styled-select">
                                                    <select name="curr" id="curr" class="form-control" required <?php if($mode == 2) echo "disabled" ?>>
                                                        <!--option value="">Select Currency</option>
         <?php $qrycur = "SELECT `id`, `name`, `shnm` FROM `currency` where id=1 order by name";
    $resultcur             = $conn->query($qrycur);if ($resultcur->num_rows > 0) {while ($rowcur = $resultcur->fetch_assoc()) {
        $crid = $rowcur["id"];
        $crnm = $rowcur["shnm"];
        ?>
                                                        <option value="<?php echo $crid; ?>" <?php if ($curr == $crid) {echo "selected";} ?>><?php echo $crnm; ?></option>
            <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div-->
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for unit-->
                                                    <div class="form-group">
                                                        <label for="code">Debit GL*</label>
                                                        <div class="form-group styled-select">
                                                            <select name="glac" id="glac" class="form-control" <?php if($mode == 2) echo "disabled" ?> required>
                                                            <option value="">Select GL</option>
 <?php // Root Level 
 $qrymu="SELECT `glno`, `glnm`FROM `coa` WHERE status = 'A' and isposted = 'P' and substring(`glno`,1,1)  in (1,2) and oflag='N' order by glno"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["glno"];  $mnm=$rowmu["glnm"];
    ?>                                                          
                                                                <option value="<?php echo $mid; ?>" <?php if ($glac == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
                
     
     <?php  }}?>                                                  
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div>
                                                
                                                <div class="col-lg-3 col-md-6 col-sm-6"> <!-- this block is for unit-->
                                                    <div class="form-group">
                                                        <label for="code">Credit GL*</label>
                                                        <div class="form-group styled-select">
                                                            <select name="crglac" id="crglac" class="form-control" <?php if($mode == 2) echo "disabled" ?> required>
                                                            <option value="">Select GL</option>
 <?php // Root Level 
 $qrymu="SELECT `glno`, `glnm`FROM `coa` WHERE status = 'A' and isposted = 'P' and substring(`glno`,1,1)  in (1,2) and oflag='N' order by glno"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 
              { 
                  $mid= $rowmu["glno"];  $mnm=$rowmu["glnm"];
    ?>                                                          
                                                                <option value="<?php echo $mid; ?>" <?php if ($glac == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
                
     
     <?php  }}?>                                                  
                                                            </select>
                                                        </div>
                                                    </div>        
                                                </div>

      	                                <!--div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="descr">Narration</label>
                                                <input type="text" class="form-control" id="d escr" name="descr" value="<?php echo $naration; ?>">
                                            </div>
                                        </div -->

                                    </div>
                                </div>
                            </div>
                            <!-- /#end of panel -->
                            <div class="button-bar">
                                <?php if ($mode == 2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Receive"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else { ?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Receive"  id="add" >
                                <?php } ?>
                            <a href = "./acc_collectionList.php?pg=1&mod=7">
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
<?php include_once 'common_footer.php'; ?>

<script language="javascript">

	
	
	
	
$(".datepicker_chqdt").daterangepicker({
    locale: {
          format: 'DD/MM/YYYY',
    },
    singleDatePicker: true,
    showDropdowns: false,
    "startDate": "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",
    "endDate": "<?=date("d/m/Y")?>T<?=date("H:i:s")?>.910Z",

	
	
	
	
}, function(start, end, label) {
  
	alert(start.format('YYYY-MM-DD'));
	
});
	
//$('#datepicker_chqdt').on('apply.daterangepicker', function(ev, picker) {
//    $(this).val(picker.startDate.format('DD-MM-YYYY'));
//});		
	
<?php
if ($res == 4) {
        ?>

//alert($(".cmb-parent").children("option:selected").val());

var selectedValue = $("#cmborg").children("option:selected").val();


	 $.ajax({
            type: "POST",
            url: "cmb/collection_invoice.php",
            data: { key : selectedValue,invoice: <?=$inv ?> },
			beforeSend: function(){
					$(".cmb-children").html("<option>Loading...</option>");
				},

        }).done(function(data){
            //root.find(".measure-unit").html(data);

			$(".cmb-children").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmb-children").append(data);

			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });


<?php
}
    ?>

$(document).on("change", ".dl-cmborg", function() {


	var selectedValue = $(this).children("option:selected").val();

	//alert(selectedValue);

	 $.ajax({
            type: "POST",
            url: "cmb/collection_invoice.php",
            data: { key : selectedValue },
			beforeSend: function(){
					$(".cmd-children").html("<option>Loading...</option>");
				},

        }).done(function(data){
            //root.find(".measure-unit").html(data);

			$(".cmb-children").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			$(".cmb-children").append(data);

			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
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


	});
</script>
<script>
    //Searchable dropdown
    $(document).on("change", ".dl-servtp", function() {
        var g = $(this).val();
    if ($(this).val() == 'q')
    {
      $('.isquot').show(); // Shows the element with class ischeck
    } 
    else
    {
      $('.isquot').hide(); // Hides the element with class ischeck
    }
    
    if ($(this).val() == 's')
    {
      $('.isservice').show(); // Shows the element with class ischeck
    } 
    else
    {
      $('.isservice').hide(); // Hides the element with class ischeck
    }
    if ($(this).val() == 'm')
    {
      $('.ismaintanace').show(); // Shows the element with class ischeck
    } 
    else
    {
      $('.ismaintanace').hide(); // Hides the element with class ischeck
    }
    if ($(this).val() == 'o')
    {
      $('.isother').show(); // Shows the element with class ischeck
    } 
    else
    {
      $('.isother').hide(); // Hides the element with class ischeck
    }
    
       // var id = $('#cmborg1 option[value="' + g +'"]').attr('data-value');
    //    $('#cmborg').val(id);
       // alert(g);


	});
</script>

<script>
    //Searchable dropdown
    $(document).on("change", ".dl-ref", function() {
        var g = $(this).val();
        var cusid = $('#ref option[value="' + g +'"]').attr('data-cus');
        var amount = $('#ref option[value="' + g +'"]').attr('data-amount');
        var so = $('#ref option[value="' + g +'"]').attr('data-so');
        var cusnm = $('#ref option[value="' + g +'"]').attr('data-org');
        var note='Amount Received against # '+so;
        $('#org_id').val(cusid);
        $('#org_id').attr("value",cusid);
        
        $('input[name=org_name]').attr("value",cusnm);
        //alert(g);
        
        $('#amt').val(amount);
         $('#descr').val(note);
        //alert(cusid);


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
<script>
     $('#paywith').change(function() {
    if ($(this).val() == '2') {
      $('.ischeck').show(); // Shows the element with class ischeck
    } else {
      $('.ischeck').hide(); // Hides the element with class ischeck
    }

    if ($(this).val() == '3' || $(this).val() == '2') {
      $('.bankname').show(); // Shows the element with class ischeck
    } else {
      $('.bankname').hide(); // Hides the element with class ischeck
    }
    
    if ($(this).val() == '3' ) {
      $('.depbankname').show(); // Shows the element with class ischeck
    } else {
      $('.depbankname').hide(); // Hides the element with class ischeck
    }
    
    
    if ($(this).val() == '8' || $(this).val() == '9') {
      $('.ismobilewallet').show(); // Shows the element with class ischeck
    } else {
      $('.ismobilewallet').hide(); // Hides the element with class ischeck
    }
	  
  });
  
  
  
/* input file type code */

  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });

  // We can watch for our custom `fileselect` event like this
  $(document).ready( function() {
      $(':file').on('fileselect', function(event, numFiles, label) { 

          var input = $(this).parents('.input-group').find(':text'),
              log = numFiles > 1 ? numFiles + ' files selected' : label;

          if( input.length ) {
              input.val(log);
              //alert(log);
          } else {
              //if( log ) 
              //alert(log);
          }

      });
  });

</script>

</body>
</html>
<?php } ?>