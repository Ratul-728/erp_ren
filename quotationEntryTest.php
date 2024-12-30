<?php
//print_r($_REQUEST);
//exit();
session_start();

require "common/conn.php";
include_once('rak_framework/fetch.php');

$usr = $_SESSION["user"];
//echo $usr;die; 

//ini_set('display_errors', 1);

if (!$_SESSION["user"]) 
{
    header("Location: " . $hostpath . "/hr.php");
}
else 
{
    $res       = $_GET['res'];
    $msg       = $_GET['msg'];
    $id        = $_GET['id'];
    $serno     = $_GET['id'];
    $totamount = 0;
    $itdgt=0;
    $discttot=0;

    if($_REQUEST['action']=='restore')
    {
	    $soid = $_REQUEST['socode'];
	    $action = 'restore';
    }
	//restore mode
	
	
    if ($res == 4) 
    { //update mode
		
        if($_REQUEST['action']=='restore')
        {
            $_SESSION['pagestate'] = 'revision';
            
			$soid = $_REQUEST['socode'];
			$rid = $_REQUEST['rid'];
        
			$qry = "SELECT 
			s.orderstatus, 
			s.`socode`, 
			s.`id`,
			s.project,
			p.name projnm,
			s.srctype,
			s.`organization`, 
			s.`customer`,
			DATE_FORMAT(s.`orderdate`,'%e/%c/%Y') `orderdate`,
			s.`deliveryamt`,
			s.`accmanager`, 
			s.`vat`, 
			s.`tax`, 
			s.`invoiceamount`, 
			s.`makeby`, 
			s.`makedt`,
			s.`status`,
			s.`remarks`,
			s.`poc`,
			s.`oldsocode`, 
			s.note,
			DATE_FORMAT(s.mrcdt,'%e/%c/%Y') mrcdt, 
			o.name orgname,
			adjustment
			
            FROM `quotation_revisions` s 
			left join organization o ON o.id = s.organization 
			left join project p on s.project=p.id
			WHERE  s.socode= '" . $soid."' AND s.id=".$rid;
			//echo $qry; die;
		}
		else
		{
		    $_SESSION['pagestate'] = 'quotation';
            $qry = "SELECT 
			s.`id`, 
			s.orderstatus, 
			s.`socode`,
			s.`organization`, 
			s.project,
			p.name projnm,
			s.srctype,
			s.`customer`,
			DATE_FORMAT(s.`orderdate`,'%e/%c/%Y') `orderdate`,
			s.`deliveryamt`,
			s.`accmanager`, 
			s.`vat`, 
			s.`tax`, 
			s.`invoiceamount`, 
			s.`makeby`, 
			s.`makedt`,
			s.`status`,
			s.`remarks`,
			s.`poc`,
			s.`oldsocode`,
			DATE_FORMAT(s.mrcdt,'%e/%c/%Y') mrcdt, 
			o.name orgname,
			adjustment,
			s.note 
            FROM `quotation` s 
			left join organization o ON o.id = s.organization 
			left join project p on s.project=p.id
			where  s.id= " . $id;
		}
		
		//echo $qry;die;
        if ($conn->connect_error) 
        {
            echo "Connection failed: " . $conn->connect_error;
        } 
        else 
        {
            $result = $conn->query($qry);
            if ($result->num_rows > 0)
            {
                while ($row = $result->fetch_assoc()) 
                {
                    $uid              = $row["id"];
                    $soid             = $row["socode"];
                    $cusype           = $row["customertp"];
                    $org              = $row["organization"];
                    $srctype          = $row["srctype"];
                    $project          = $row["project"];
                    $proj             = $row["projnm"];
                    
                    $cusid            = $row["customer"];
                    $orderdt          = $row["orderdate"];
					$makedt           = $row["makedt"];
                    $accmgr           = $row["accmanager"];
                    $invoice_amount   = number_format($row["invoiceamount"],2);
                    $vat              = number_format($row["vat"],2);
                    $tax              = number_format($row["tax"],2);

					$orderstatus               = $row["orderstatus"];
                    $st               = $row["status"];
                    $details          = $row["remarks"];
                    $note          = $row["note"];
                    $poc              = $row["poc"];//current user id
                    $oldsocode        = $row["oldsocode"];
                    $orgname          = $row["orgname"];
                    $oldsocode        = $row["oldsocode"];
                    $mrcdt            = $row["mrcdt"];
                    $deliveryamt      = $row["deliveryamt"];
                    $adj      		  = $row["adjustment"];
                    $vatt=$row["vat"];
                    
                } //while ($row = $result->fetch_assoc())
            } //if ($result->num_rows > 0)
        } //if ($conn->connect_error) 
        
        $mode = 2; //update mode
    } 
    else 
    {
        $_SESSION['pagestate'] = 'insert';
        $uid              = '';
        $soid             = '';
        $cusid            = '';
        $orderdt          = date("d/m/Y");
        $accmgr           = '';
        $itdmu            = 1;
        $invoice_amount   = '0';
        $vat              = '0';
        $tax              = '0';
        $project        ='';
        $proj           ='';
        $srctype        ='';
        $st               = '';
        $effect_dt        = '';
        $details          = '';
        $poc              = '';
        $mrcdt            = ''; //$term_dt=date("Y-m-d")

        $mode = 1; //Insert mode
        
         $deliveryamt      = 0;
        $adj      = 0;
        $vatt=0;
    } //if ($res == 4) 

    $currSection = 'quotation';
    $currPage    = basename($_SERVER['PHP_SELF']);
	
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>
<body class="<?=$_SESSION['pagestate'] ?> form soitem order-form <?=($res == 4)?'edit-mode':''?> <?=($res == 0)?'add-mode':''?>">
<style>

.c-vat{text-align: center;}
.c-qty{text-align: center;}
.c-price{text-align: right;}
.c-price-utt{text-align: right;}
.c-discount{text-align: center;}
.c-discounted-ttl{text-align: right;padding-right: 45px;}	
	
.ipspan{position: relative}
.ipspan span{
    display: block;
    
    
    background-color: rgb(212,218,221);
    position: absolute;
    z-index: 0;
    right: 0;
    top: 0;
    text-align: center;
    height: 35px;
    width: 35px;
    line-height: 35px;
    font-size: 12px;
}



.grid-sum-footer input{
    padding-right: 45px;
}	


/*
select2 with picture css
*/
.order-form .toClone .styled-select{
 height: 35px;
}
.order-form .select2-selection__arrow{
   border:0px solid red;
    width: 34px!important;
    height: 34px!important;
    background-color: #efefef;
}
.order-form .select2-selection__rendered{
    background-color: transparent;
    border-radius: 0;
    padding: 2px;
    
}

/* Style the Select2 container */
.order-form .select2-container {
    background-color: transparent;
    width: 100%!important;
    height: 35px;
}

.order-form .select2-container .select2-selection img{
    width: 34px;
    height: 34px;
    margin-right: 10px; 
    margin-left: -8px; 
    margin-top: -2px;
    border: 1px solid rgb(255,255,255);
}

/* Set the border and height for the selection area */
.order-form .select2-selection {
    border: 0px solid #efefef!important;;
    height: 35px!important;;
}

/* Set the border for the dropdown container */
.order-form .select2-dropdown {
    border: 1px solid #efefef;
}





/* option */
.order-form .select2-results__option {
    height: 60px; /* Set your desired height */
}
.order-form .select2-results__option .img-wapper img{
   display: block;
    width:60px;
    height: 60px;
    margin-left: -5px;
    
        padding: 5px!important;
    
}
/* select-img */

.order-form .select2-results__option span{
    padding-left: 0px;
}
.order-form .select2-results__option {
    height: 60px;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #efefef;

}

.order-form .select2-results__option img {
    width: 40px;
    height: 40px;
    margin-right: 10px; 
}

.order-form .select2-results__option span {
    display: flex;
    flex-grow: 1;
    align-items: center;
}
.order-form .select2-search{
    background-color: #fff!important;
}
.order-form .select2-search__field{
    border-bottom: 1px solid #efefef!important;
    height: 40px;
    font-size: 18px;
    padding: 10px!important;
}


.order-form .select2-search__field:focus {
    outline: none;
    border: 1px solid #ccc!important; /* Add a border color to replace the default focus border */
}

.order-form .select2-results__message{
    padding-left: 20px;
    color: red;
}

.order-form .select2-results__option:hover {
    background-color: #094446!important;
    color: #fff!important;
}

.order-form .select2-results__option:hover span {
    color: #fff!important;
}



/*
end select2 with picker 
*/
.saletype{
    font-family: arial;
}

</style>

<link href="js/plugins/select2/select2.min.css" rel="stylesheet" />

<?php include_once 'common_top_body.php';    ?>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Quotation</span>
        </div>
        <?php include_once 'menu.php'; ?>
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
                        <?php
                            switch($_SESSION['pagestate']){
                                case 'insert':
                                $targetAction = 'quotation_add.php';
                                break;
                                case 'quotation':
                                $targetAction = 'quotation_edit.php';
                                break;
                                case 'revision':
                                $targetAction = 'quotation_revision.php';
                                break;                                
                            }
                        ?>
                        <form method="post" action="common/<?=$targetAction?>"  id="Quotationform"  enctype="multipart/form-data">
                        <!--form method="post" action="" id="form1" enctype="multipart/form-data" -->
                        <!-- START PLACING YOUR CONTENT HERE -->
                            <div class="panel panel-info">
			                    <div class="panel-body panel-body-padding">
                                    <span class="alertmsg"></span>
                                        <div class="row form-header">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
          		                                <h6>Quotations <i class="fa fa-angle-right"></i> <?=($mode == 1)?"New":"Edit"?>  Quotation <?=($_REQUEST['action'] == 'restore')?'<kbd>Revision ID:'.$rid.' - (Date: '.date_format(date_create($makedt),"d/m/Y H:i:s").')</kbd>':''?></h6>
          		                            </div>
    
          		                            <div class="col-lg-6 col-md-6 col-sm-6">
          		                               <h6><span class="note"> (Field Marked <span class="redstar">*</span> are required)</span></h6>
          		                            </div>
                                        </div>
                            <!-- <br> -->
                          	<!-- <p>(Field Marked * are required) </p> -->
                                        <div class="row">
                                    	    <div class="col-sm-12">
        	                                    <!-- <h4>SO Information</h4>
        		                                <hr class="form-hr"> -->
        		                                 <input type="hidden"  name="serid" id="serid" value="<?php echo $serno; ?>">
        		                                 <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
        		                                 <input type="hidden"  name="po_id" id="po_id" value="<?php echo $soid; ?>">
            	                            </div>
                                            <div class="row no-mg">
                                            </div>
                                            
                                            <div class="col-sm-12">
                                                <h4>Quotation Information  </h4>
                                                <hr class="form-hr">
                                            </div> 
                                            <div class="col-lg-2 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="po_id">Quotation ID</label>
                                                    <input type="text" class="form-control" placeholder="Auto Generated" name="po_id_vis" id="po_id_vis" value="<?php echo $soid; ?>" disabled>
                                                </div>
                                            </div>
                                            
                                    	    <div class="col-lg-2 col-md-6 col-sm-6">
        	                                    <label for="po_dt">Order Date<span class="redstar">*</span></label>
                                                <div class="input-group">
                                                    <input readonly type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="<?php echo $orderdt; ?>">
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="col-lg-2 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <?php
                                                        $srctype = ($srctype)?$srctype:1;
                                                    ?>
                                                    <label for="saletype">Sales Type <span class="redstar">*</span></label>
                                                    <select name="saletype" id="saletype" class="form-control saletype" planceholder="Select Sales Type">
                                                        
                                                        <option value="1" <?php if($srctype==1){echo "selected";} ?>> Retail </option>
                                                        <option value="2" <?php if($srctype==2){echo "selected";} ?>> Project </option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6 col-md-6 col-sm-6" id="projdiv" style="display: none;"> 
                                                <div class="form-group">
                                                    <label for="cmbcontype">Project <span class="redstar">*</span></label>
                                                    <div class="ds-divselect-wrapper cat-name">
                                                    <div class="ds-input">
                                                        <input type="hidden" name="dest" value="">
                                                        <input type="hidden" name="desig" id = "desig" value = "<?= $proj ?>">
                                                        <input type="text" name="desig_name" id="proj"  autocomplete="off" placeholder="Select Project"  class="input-box4 form-control" value = "<?= $proj ?>">
                                                    </div>
                                                        <div class="list-wrapper">
                                                            <div class="ds-list" style="display: none;">
                                        
                                                                <ul class="input-ul4" tabindex="0" id="inpUl4">
                                                                    <li tabindex="1" class="addnew">+ Add new</li>
                                        
                                        
                                                                    <?php $qryitm = "SELECT id, name FROM `project` order by name";
                                                                    $resultitm  = $conn->query($qryitm);if ($resultitm->num_rows > 0) {
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
                                                                        <h3>Add new Project</h3>
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
                                            <div class="col-sm-12"></div>
                                                
                                            <div class="col-lg-3 col-md-6 col-sm-6"> 
                                                <div class="form-group">
                                                    <label for="cmbcontype">Customer<span class="redstar">*</span></label>
                                                        <div class="ds-divselect-wrapper cat-name">
                                                            <div class="ds-input">
                                                                <input type="hidden" name="dest" value="">
                                                                <input type="hidden" name="org_id" id = "org_id" value = "<?= $org ?>">
                                                                <input type="text" name="org_name" required autocomplete="off"  class="input-box form-control" value = "<?= $orgname ?>">
                                                            </div>
                                                            <div class="list-wrapper">
                                                                <div class="ds-list">
                                                                    <ul class="input-ul" id="inpUl">
                                                                        <li class="addnew">+ Add new</li>
                                                                    <?php $qryitm = "SELECT o.id, concat(o.name, '(', o.contactno, ')') orgname ,concat(o.street,',',a.name,',',d.name,'-',o.zip,',',c.name) addr
                                                                        FROM organization o left join area a on o.area=a.id left join district d on o.district=d.id left join country c on o.country=c.id 
                                                                        order by o.name";
                                                                    $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
                                                                    $tid = $rowitm["id"]; $nm  = $rowitm["orgname"]; $addr = $rowitm["orgname"];?>
                                                                        <li class="pp1" data-addr="<?php echo $addr; ?>" value = "<?=$tid ?>"><?=$nm ?></li>
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
                                                    <label for="cmbsupnm">Contact Name<span class="redstar">*</span></label>
                                                    <div class="form-group styled-select">
                                                    <select name="cmbsupnm" id="cmbsupnm" class="cmd-child form-control" required>
                                                    <option value="">Select Name</option>
                                                            <?php $qrycont = "SELECT `id`, `name`  FROM `contact`  WHERE `contacttype`=1  order by name";
                                                            $resultcont = $conn->query($qrycont);if ($resultcont->num_rows > 0) {while ($rowcont = $resultcont->fetch_assoc()) {
                                                                $tid = $rowcont["id"];
                                                                $nm  = $rowcont["name"];
                                                                ?>
                                                            <option value="<?php echo $tid; ?>" <?php if ($cusid == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
                                                            <?php
                                                            }
                                                                }
                                                                ?>
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
        <?php 
        if($mode == 1) 
        { //insert 
            require_once("inc/inc_quotation_insert.php");
         }else{ 
            // edit

            require_once("inc/inc_quotation_edit.php");
        											
    } //if($mode == 1) {}else{


   ?>
<!-- this block is for php loop, please place below code your loop  -->
        											
        <?php
        	// RESTORE QUOTATION
        	
        
        	
        	
        	
        	//END RESTORE QUOTATION										
        ?>									
        											
        											
        											
        											
                                                </div>
        
        
        										<div class="row add-btn-wrapper">
        											<div class="col-sm-12">
        											<?php
        												//echo $mode;
        													$addClassName = ($mode == "1") ? 'link-add-po' : 'link-add-po-2';
        													?>
        												<a href="#" title="Add Item" class="link-add-order" ><span class="glyphicon glyphicon-plus"></span> </a>
        											</div>	
        										</div>
        										
        
                                                <div class="well no-padding  top-bottom-border grandTotalWrapperx grid-sum-footer">
                                                    <div class="row total-row border">
                                                        <div class="col-xs-12">
                                                            <div class="form-group grandTotalWrapper label-flex pull-right">
                                                                <label>Subtotal </label><?php $itdgt=number_format($itdgt,2)?>
                                                                <input type="text" class="form-control f-subtotal" id="grandTotal" value="<?php echo str_replace(",","",$itdgt); ?>" readonly required>
                                                            </div>
                                                        </div>
        
                                                        <div class="col-xs-12">
                                                            <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                                <label>Total Discount:</label><?php  $netdisc=$totalcost-$netamount;?>
                                                                <input type="text" class="f-disttl form-control" id_="discountdsp" value="<?php echo  number_format($netdisc,2, '.', ''); ?>"  name="discountdsp"  readonly>
                                                                
                                                            </div>
                                                        </div>	
                                                        <div class="col-xs-12">
                                                            <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                                <label> Adjustment</label>
                                                                <input type="text" class="calc  numonly f-adjmt form-control" id_="discntnt" value="<?php echo  number_format($adj,2, '.', ''); ?>"  name="discntnt" >
                                                            </div>
                                                        </div> 
                                                        
                                                        <div class="col-xs-12">
                                                            <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                                <label>Total VAT :</label>
                                                                <input type="text" class="f-vatttl form-control" id_="vatdis" value="<?php echo  number_format($vatt,2, '.', ''); ?>"  name="vatdis"  readonly>
                                                                <input type="hidden" class="form-control" id_="vatt" value="<?php echo  $vatt; ?>"  name="vatt"  readonly>
                                                            </div>
                                                        </div>

        												
        												
                                                        <div class="col-xs-12">
                                                            <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                                <label> Delivery Charge</label>
                                                                <input type="text" class="calc  numonly f-delcrg form-control" id_="deliveryamt" value="<?php echo  number_format($deliveryamt,2, '.', ''); ?>"  name="deliveryamt" >
                                                            </div>
                                                        </div>												
        												
                                                      <div class="col-xs-12">
                                                            <div class="form-group grandTotalWrapper  label-flex pull-right">
                                                                <label>Total </label>
        														<?php
        															$orGrandTotal =(($OrSubtotal+$vatt)-$adj)+$deliveryamt;
        														?>
                                                                <input type="text" class="f-grnd-ttl form-control" id_="grandTotalnet" value="<?php echo number_format($orGrandTotal,2,'.',''); ?>" readonly >
                                                            </div>
                                                        </div>
                                                        
                                                    </div>                                           
                                                    
                                                </div>
           
                                
                                            </div>
                                            
        
                                           
        
                                            <div class="col-lg-12 col-md-12 col-sm-12">
        
                                                <div class="form-group">
        
                                                    <label for="details">Contact & Delivery Address </label>
        
                                                    <textarea class="form-control" id="details" name="details" rows="4" ><?php echo $details; ?></textarea>
        
                                                </div>
        
                                            </div>
                                            
                                            <div class="col-lg-12 col-md-12 col-sm-12">
        
                                                <div class="form-group">
        
                                                    <label for="details">Note</label>
        
                                                    <textarea class="form-control" id="note" name="note" rows="2" ><?php echo $note; ?></textarea>
        
                                                </div>
        
                                            </div>
        
        
                                            <div class="col-sm-12"> 
        											<input type="hidden" name="mode" value="<?=$mode?>">
        											<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
                                                    <?php if ($mode == 2) { 
                                                    $amount=0;
                                                    $paidst="SELECT sum(amount) amt FROM collection   where transref='$soid'";// 'INV-000565'"
                                                    $resultapid = $conn->query($paidst);if ($resultapid->num_rows > 0) {while ($rowamt = $resultapid->fetch_assoc()) {$amount  = $rowamt["amt"];}}
                                                    //for update ?>
                                                			
        													<input  class="btn btn-lg btn-default top" <?=($orderstatus == 2)?"disabled":""?> type="submit" name="postaction" value="Save" id="save"> 
        										            <input  class="btn btn-lg btn-default top" type="submit" <?=($orderstatus == 2)?"disabled":""?> name="postaction" value="Save as Revision" id="saverevision">
        													<input  class="btn btn-lg btn-default top" <?=($orderstatus == 2)?"disabled":""?> type="submit" name="postaction" value="Send Email" id="email"> 
        													<input class="btn btn-lg btn-default" <?=($orderstatus == 2)?"disabled":""?> type="submit" name="postaction" value="Create Order"  id="confirm" > 
        										
                                                  <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="copy" value="Copy SO" id="Copy"-->
                                                  <?php } else { // new insert ?>
        
        											  <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="+Add Order" id="add" -->
        											  <!--input  dat a-to="pagetop" class="btn btn-lg btn-default" type="submit" name="addprint" value="+Add and Print Order" id="add"-->
        										
        										
        													<input  class="btn btn-lg btn-default top" type="submit" name="postaction" value="Save" id="save"> 
        													<input  class="btn btn-lg btn-default top" type="submit" name="postaction" value="Save as Revision" id="saverevision">
        													<input  class="btn btn-lg btn-default top" type="submit" name="postaction" value="Send Email" id="email"> 
        													<input class="btn btn-lg btn-default" type="submit" name="postaction" value="Create Order"  id="confirm" disable > 										
        										
                                                  <?php } ?>
        
        												<input  class="btn btn-lg btn-warning top" type="button" name="postaction" value="Cancel" id="cancel"  onClick="location.href = 'quotationList.php?pg=1&mod=3'" >
        
                                            </div>
        
                                        </div>
							            <br>
                                        <br>
							<?php
							if($res == 4 || $res == 'restore'){ // show revision only in update mode;
								
								?>
							<div class="row">
								<div class="col-sm-12">
									<h4>Revision History  </h4>
									<hr class="form-hr">
	                           </div>
								
								<div class="col-sm-12">							
									
                                            <table class="revision-tbl table table-striped  table-hover dt-responsive actionbtn">							
											<tr>
												<th width="10" align="center">Rev ID</th>
												<th width="25">Order Amount</th>
												<th width="10">Total Item</th>

												<th width="10">Email Sent</th>
												<th width="10">Status</th>
												<th width="10%">Created By</th>
												<th>Updated On</th>												
												<th>View | Email | Restore</th>
												</tr>									
									<?php
								
								
								//$qryLoadRevData = 'SELECT qr.id qrid,qs.id qsid, makeby,makedt,qr.orderstatus,email_sent,email_sent_on, qs.name qstatus FROM `quotation_revisions` qr LEFT JOIN quotation_status qs on qr.`orderstatus`=qs.`id` WHERE socode="'.$soid.'"';
								
								$qryLoadRevData = '
								SELECT hr.hrName createdby,qr.id qrid,qs.id qsid, makeby,makedt,qr.orderstatus,email_sent,email_sent_on, qs.name qstatus 
								FROM `quotation_revisions` qr 
								LEFT JOIN quotation_status qs on qr.`orderstatus`=qs.`id` 
								LEFT JOIN hr on qr.makeby = hr.id 
								WHERE socode="'.$soid.'" 
								ORDER BY makedt DESC';
								
								//echo '<tr><td>'.$qryLoadRevDat.'</td></tr>';
								//die;
							$resLoadRevData = mysqli_query($conn, $qryLoadRevData);
							 
							$rowcount=mysqli_num_rows($resLoadRevData);
								if($rowcount<1){
									echo '<tr><td align="center" colspan="5" style="padding:8px!important;">No revision found</td></tr>';
								}
							while($row = mysqli_fetch_assoc($resLoadRevData)){
									
								
							?>
											
												<tr>
												<td align="center"><span class="rowid_<?=$row['qrid']?>"><?=$row['qrid']?></span></td>
												<td align="right"><?=number_format(fetchByID("quotation_revisions","id",$row['qrid'],'invoiceamount'),2)?></td>
												<td align="center"><?=fetchTotalRecord("quotation_revisions_detail",'revision_id',$row['qrid'])?></td>
												<td><?=($row['email_sent_on'] != '0000-00-00 00:00:00')?$row['email_sent'].' (on '.$row['email_sent_on'].')':$row['email_sent']  ?></td>
												<td><?=$row['qstatus']?></td>
												<td><?=$row['createdby']?></span></td>
												<td><span <?=($_REQUEST['changedid'] == $row['qrid'])?'class="updatedtr"':''?>><?=date_format(date_create($row['makedt']),"d/m/Y H:i:s")?></span></td>
													
												<td nowrap style="white-space:nowrap">
													<a class="btn btn-info btn-xs show-invoice" data-socode="<?=$soid?>" data-qrid="<?=$row['qrid']?>" href="quotation_view.php"  title="View Revision"><i class="fa fa-eye"></i></a> |
													<a class="btn btn-info btn-xs"   title="Email"><i class="fa fa-envelope"></i></a> |
													<a class="btn btn-info btn-xs" href="quotationEntry.php?res=4&action=restore&socode=<?=$soid?>&id=<?=$_REQUEST['id']?>&rid=<?=$row['qrid']?>&mod=3&changedid=<?=$row['qrid']?>"   title="Restore"><i class="fa fa-repeat"></i></a>
												</td>
													
												</tr>
											<?php
								}
												?>	
												
												
												
											</table>

                                        
								</div>
							</div>
							
							<?php } ?>

                        </div><!-- end panel body -->
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
include_once 'common_footer.php';
//$cusid = 3; ?>
<?php include_once 'inc_cmb_loader_js.php'; ?>




<script>
	
    $(document).ready(function(){
		
		//input number only validateion
		//put class .numonly to apply this. alpha will no take, only number and float
		
		$('.numonlyx').change(function(e){
			var xxxx = $(this).val();
			//alert(typeof(parseFloat(xxxx)));
		});
		
        //$('.numonly').keyup(function(e){
        $(document).on("keyup",".numonly", function(e){
			
		  if(/[^0-9.]/g.test(this.value))
		  {
			// Filter non-digits from input value.
			this.value = this.value.replace(/[^0-9.]/g, '');
			  
		  }
	    });		

		
		
    //hide warehouse quantity on clicking on anywhare;
    $(document).on('click',".pagetop", function(event) {
        var div2 = $(event.target);
        var div = $('.qtycounter'); 
        //if (!target.is('.qtycounter') && !target.is('.c-qty')) {
        if (!div.is(event.target) && !div2.is('.c-qty') && !div.has(event.target).length) {
        //$('.qtycounter').css('visibility','hidden'); 
            div.css('visibility','hidden');
        }
    });        
        
  
    <?php
        if($res == 0){//insert  mode
    ?>
   
    //show warehouse quantity on clicking on quantity;      
        
    $(document).on("click",".c-qty", function(event) {
        $(".qtycounter").css('visibility','hidden'); 
        var root = $(this).closest('.toClone');
        var div = root.find('.qtycounter');
        
        var pid =  root.find('.itemName').val();
        //alert(pid);
        if(pid){
            root.find(".qtycounter").css('visibility','visible'); 
        }

    });
 
    <?php
        }
    ?>
      

		
		
    
/** end */
		
		

        
<?php
	if($res == 4){//edit mode
?>	


    $(document).on("click",".edit-mode .c-qty",function(){
    
    var root = $(this).closest('.toClone');
    var pid =  root.find('.itemName').attr('value');
    var order_detail_id =  root.data('order_detail_id');
    $(".qtycounter").css('visibility','hidden'); 

    
        console.log(order_detail_id);
        console.log(pid);
        
        if(pid){
        if(root.find('.itemName').attr('flag') != 1){
        
             loadWarehouseEdit(root, pid, order_detail_id);
             root.find('.itemName').attr('flag',1);
        }else{
            root.find(".qtycounter").css('visibility','visible'); 
        }
        
        }else{
            root.find(".qtycounter").remove();
        }  
    });		

		
    <?php	
        }
    ?>
	
        

    
    

}); // $(document).ready(function(){
    
    
    
    
    
    
///quantity manager    
$(document).ready(function(){

  //$('.c-qty').prop('readonly', true);
$('.c-qty').on('input',function(){
    $(this).val('');
});
  $('.c-qty').css('background-color', '#fff');

//$('.qtycounter .quantity-right-plus').click(function(e){
  $(document).on('click','.qtycounter .quantity-right-plus',function(e){
  
    e.preventDefault();
    var field = $(this).closest('.plusminuswrap').find('.quantity');
    var quantity = parseInt(field.val());

    if (!isNaN(quantity)) {
        field.val(quantity + 1);
    }
});
  
  
  

//$('.qtycounter .quantity-left-minus').click(function(e){
  $(document).on('click','.qtycounter .quantity-left-minus',function(e){
    e.preventDefault();
    var field = $(this).closest('.plusminuswrap').find('.quantity');
    var quantity = parseInt(field.val());

    if (!isNaN(quantity) && quantity > 0) {
        field.val(quantity - 1);
    }
});


//$('.quantity').on('input', function() {
$(document).on('input','.qtycounter .quantity',function(e){
  //var root = $(this).closest('.plusminuswrap')
  var root = $(this).closest(".toClone");
  sum = updateSum(root);

    $(this).closest('.toClone').find('.c-qty').val(sum);
});

//$('.quantity-right-plus, .quantity-left-minus').click(function() {
  $(document).on('click','.qtycounter .quantity-left-minus, .qtycounter .quantity-right-plus',function(e){ 
    
  var root = $(this).closest(".toClone");
  sum = updateSum(root);
  //OrderTotal();
  $(this).closest('.toClone').find('.c-qty').val(sum);
  $(this).closest('.toClone').find('.c-qty').trigger("change");
});

function updateSum(rt) {
  var sum = 0;
  rt.find('.quantity').each(function() {
    var quantity = parseInt($(this).val());
    if (!isNaN(quantity)) {
      sum += quantity;
    }
  });
  return sum;
}
  
  
  
 });     
    
//make item in datalist hidden of already selected;
$(document).ready(function() {
  // Get the datalist and input elements by their IDs
  var dataList = $('#itemName');
  var input = $('#myInput');
  
  // Remove the option based on its value
  function removeOption(value) {
    //dataList.find('option[class="option-' + value + '"]').remove();
    dataList.find('option[class="option-' + value + '"]').prop('disabled', true);
  }

  // Example usage: remove the option with value "Option 3"
  
 $('.link-add-order_inhalt').click(function() {

  dataList.find('option').prop('disabled', false);
  $('.itemName').each(function() {
      var value = $(this).val();
      removeOption(value);
    });
   
   });
  
});

    
//handle samedate in quantity by warehouse
$(document).on('ifChanged','.samedate', function(event) {
//$(document).on('change','.samedate', function(event) {
  
  var checkbox = event.target;

  // Get the checkbox value and checked status
  var value = checkbox.value;
  var isChecked = checkbox.checked;
  
  var root = $(this).closest('.toClone');
  
	if(isChecked) {
    console.log("Checkbox with value '" + value + "' is checked.");
    // Additional actions for checked checkboxes
    
    var dd = root.find(".delivery-date").val();
    if(dd){
     root.find(".delivery-date").val(dd);
    }else{
    	alert('Enter a Date');
    	root.find(".delivery-date:first").focus();
    }
  }
  
  });    
    
	

	

	
	
	


</script>
<script>
    
    $(document).on("change", "#discntnt_,#deliveryamt_", function() {
    //alert("yes");
    var adj=0;
    var net=0;
    var sum1=0;
    var dlv=0;
    var vats=0;
     var discountsum=0;
    
    dlv+=$("#deliveryamt").val();
     adj = $("#discntnt").val();
     vats=$("#vatt").val();
     sum1=$("#grandTotal").val();
     net+=sum1-adj-(-dlv)-(-vats);
     $("#grandTotalnet").val(net.toLocaleString("en-US"));
   // alert(net);
});
    
</script>
<script language="javascript">


<?php
if ($res == 4) {
        ?>

//alert($(".cmb-parent").children("option:selected").val());

var selectedValue = $(".cmb-parent").children("option:selected").val();

	 $.ajax({
            type: "POST",
            url: "cmb/so_item_customer_names.php",
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
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
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
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
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
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
            data: { key : selectedValue,cusid:'<?=$cusid ?>' },
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
//$(document).on("change", ".dl-itemName", function() {

	

$(document).on("change", ".productname", function(){
    var root = $(this).closest(".toClone");
    var thisval = $(this).val();
    
    
    var g = $(this).val();
    var order_detail_id =  root.data('order_detail_id');
    //alert(order_detail_id);
    var selectedOption = $(this).find('option:selected');
    var id = selectedOption.data('value');
    var stk = selectedOption.data('stock');
    var vat = selectedOption.data('vat');
    var price = selectedOption.data('up');
    var pid = id;
	//alert(pid);
    root.find('.c-vat').val(vat);
  	root.find('.c-price').val(price);
  	root.find(".c-price").change(); 
    root.find(".itemName").val(id); 

    if(stk <1){	 backorderCheck(stk,root);}

    if(pid){
        <?php if($res == 0){     //insert mode ?>
        loadWarehouse(root, pid, 1, 0) // 1 means Newly created, 0 means because of its a new order, it does not conation any revision id
        
       
        <?php }else{ ?>
            loadWarehouseEdit(root, pid, order_detail_id);
            <?php } ?>
    }else{
        root.find(".qtycounter").remove();
    }    

});





$(document).ready(function() {
  $(document).on('input', '.quantity', function() {
    var quantityValue = $(this).val();
    alert('Quantity Value:', quantityValue);
  });
});




function loadWarehouse(root, pid, type, revision){
        
    
    
        root.find('.qtycounter').remove();
        root.find(".c-qty").parent().addClass("qtnqrapper");
        var targetinput = root.find('.c-qty');


        $('<div class="qtycounter">').insertAfter(targetinput);
        setTimeout(function(){
        //Change Contact Name
            $.ajax({
                type: "GET",
                url: "phpajax/load_warehouse.php",
                data: { pid : pid},
                beforeSend: function(){
                    root.find(".qtycounter").html("<option>Loading...</option>");
                },

                }).done(function(data){
                    root.find(".qtycounter").empty();
                    root.find(".qtycounter").html(data);
                    //alert(data);
                    // Call initializeiCheck() function when the AJAX content is loaded
                    initializeiCheck();
                           
                });


            },200);
    }


    
    function loadWarehouseEdit(root, pid, order_detail_id){

        
//alert('i am called');
//var order = <?=$_REQUEST['id']?>;
<?=($_REQUEST['changedid'])?'var revision = '.$_REQUEST['changedid'].';':''?>
<?=($soid)?'var oid = "'.$soid.'";':''?>

root.find('.qtycounter').remove();
root.find(".c-qty").parent().addClass("qtnqrapper");
var targetinput = root.find('.c-qty');


$('<div class="qtycounter">').insertAfter(targetinput);
    
        setTimeout(function(){
        //Change Contact Name
            $.ajax({
                type: "GET",
                url: "phpajax/load_warehouse_edit.php",
                data: { pid : pid,oid:oid,order_detail_id:order_detail_id<?=($_REQUEST['changedid'])?',revision:revision':''?>},
                beforeSend: function(){
                    root.find(".qtycounter").html("<option>Loading...</option>");
                },

                }).done(function(data){
                    root.find(".qtycounter").empty();
                    root.find(".qtycounter").html(data);
                    //alert(data);
                            
                            
                    // Call initializeiCheck() function when the AJAX content is loaded
                    initializeiCheck();
                        
                });


            },200);

        
}		


$(document).on('input','.dl-itemName_backup', function(e) {	

	
	//start for datalist single click event
    var options = $('datalist')[0].options;
    for (var i=0;i<options.length;i++){
       if (options[i].value == $(this).val()){
	//end for datalist single click event
		  
		
	

	//alert($(this).val());
	//var root = $(this).parent().parent().parent().parent();
	var root = $(this).closest(".toClone");
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
				var stk = $('#itemName option[value="' + g +'"]').attr('data-stock');
			    //alert(g);
				root.find(".itemName").val(id);
				root.find(".itemName").attr('data-stk',stk);			
			
			

			//check each backorder to release book button if not found;
			
			if(stk <1){
				backorderCheck(stk,root);
				
			}

			//alert(found);
			
			break;
		}else{
			flag = 0;
		}

	
		
		
		
	}
	
			setTimeout(function(){
				enableDisableBookBtn();
			},100);	
	
	
	if(flag == 0){
		$(this).val("");
		}

		
		//start for datalist single click event
 		break;
	   }
    }		   
	//end for datalist single click event
		
		
	});
/* end Check wrong category */

	<?php
	if($orderstatus == 3 || $orderstatus == 11){// confirmed or backorder;
	?>
	$(document).on("click",".cancel-po",function(e){
		
		 e.preventDefault();
		
		
		
			  swal({
			  title: "Do you want to cancel this item from this order?",
			  text: "Your invoice will be regenerated",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			  buttons: ['Cancel', 'Proceed'],
			})
			.then((willDelete) => {
			  if (willDelete) {

			var disc = 0;
			disc = $(this).closest('.toClone').find('.rowid').val();
			//alert(disc);			  
				
				$.ajax({
					type: "POST",
					url: "cmb/so_item_cancel.php",
					data: { soitmdetlsid : disc, ost:<?=$orderstatus?> },
					beforeSend: function(){
							//$("#cmbsupnm").html("<option>Loading...</option>");
						},

				}).done(function(data){

					  swal("Cancel Status", data, "success");
					  $(this).closest('.toClone').remove(); 
					//alert(data);
				});				  
				  
				  
			  } else {
				  return false;
			  }
			});		
		

	});
	
	<?php
	}
	?>

	
	$(document).on("click",".remove-po",function(e){
		var root = $(this).closest(".toClone");
		enableDisableBookBtn();
	});
	


	//check qty for backorder
	
	$(document).on("change",".qty-chkstk",function(){
		
		var qtroot = $(this).closest(".toClone");
		var stk =  qtroot.find(".itemName").data('stk');
		
		var qty = $(this).val();
		//alert(stk);
		
		//console.log("stk:"+stk+" | qty: "+qty);
		if(stk<qty){
			backorderCheck(stk,qtroot);
		}
		enableDisableBookBtn();
	});

var found;
function enableDisableBookBtn(){

		found =0;	
		const elements = document.querySelectorAll('.itemName');
		Array.from(elements).forEach((element, index) => {
		 
			// conditional logic here.. access element
			
			mystk = element.getAttribute("data-stk");
			if(mystk < 0){
				found ++;
			}
			
		});
		if(found>0){
			$("#book").prop('disabled', true);
		}else{
			$("#book").prop('disabled', false);
		}
}	

	

function backorderCheck(stock,root){
	
		var isAlert = root.find(".isBOAlert").val();
		
		console.log("stk:"+stock+" | isAlert: "+isAlert);
		if(isAlert!=1){
			setTimeout(function(){

			 
				 
			

			  swal({
			  title: "Do you want to allow Back Order?",
			  text: "This item is not available in stock",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			  buttons: ['Cancel', 'Allow Back Order'],
			})
			.then((willDelete) => {
			  if (willDelete) {

			  } else {
				  setTimeout(function(){
					//$(this).val("Select Item");
					  
					  root.find(".dl-itemName").val("");
					  root.find(".dl-itemName").change();
					  root.find(".quantity_otc").val("");
					  root.find(".quantity_otc").change();
					  
					  root.find(".remove-po").trigger("click");
					  
				  },200);
				  
				  return false;
			  }
			});

				//return false;
				
				//put a flag after itemName field once alert is shown
				root.find(".dl-itemName").after('<input type="hidden" class="isBOAlert" value="1">');
			
			/* end backorder alert  */					 
				 
				 
				 

			},200);	
		}
}	

	
	
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
					
					//get current customer address
					getAddress(lival);

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
	

	
	function getAddress(orgid){

                    $.ajax({
                        type: "POST",
                        url: "cmb/get_address.php",
                        data: { orgid : orgid},
                        beforeSend: function(){
                        	$("#billaddress").val("Loading...");
							$("#details").val("Loading...");
                        },
                        
                        }).done(function(data){
                            $("#billaddress").val(data);
                        	 $("#details").val(data);
                            //alert(data);
                        });		
		
	}
	
	
	
            // New input box display


	
	
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
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item " + " (" + $(this).val() + ")");
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
				
				var inputVal = $(this).closest(".ds-divselect-wrapper").find(".input-box").val();
				//alert(inputVal);
				addNewOrg(inputVal);
                $(this).closest('.ds-list').attr('style','display:none');
            });	
	
	

	function addNewOrg(inputVal){
		
				BootstrapDialog.show({

											title: 'Add New Organization',
											//message: '<div id="printableArea">'+data+'</div>',
											message: $('<div></div>').load(encodeURI('addselect_modal_org_tab.php?name='+inputVal)),
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
                    										}else if(!ajxdata[5].value){
                    										    msg = "Please Enter Address!"; $("#ind_address").focus();
                    										}else if(!ajxdata[6].value){
                    										    msg = "Please Enter District!"; $("#district").focus();
                    										}else if(!ajxdata[8].value){
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
															  getAddress(res.id);
															  $('.input-box').attr('value',res.name+"("+res.contact+")");
															  $("#inpUl").append("<li class='pp1' value = '"+res.id+"'>"+res.name+" ("+res.contact+")"+"</li>");
															  
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

// new calculation code;
$(document).on("focus", ".calc", function() {
  $(this).select();
});

$(document).on("change", ".calc", function() {
  
   var tval = $(this).val();
  
	//tval = (isNaN(tval))?parseFloat(0).toFixed(2):parseFloat(tval).toFixed(2);

   if(tval>0){
     tval =  parseFloat(tval).toFixed(2)
   }else{
      tval =  parseFloat(0).toFixed(2)
   }
   
   //variables;
   var cvat = $(this).closest(".toClone").find(".c-vat").val();
   cvat = (cvat)?parseFloat(cvat).toFixed(2):parseFloat(0).toFixed(2);
   
   var cqty =  $(this).closest(".toClone").find(".c-qty").val(); 
   cqty = (cqty)?parseFloat(cqty).toFixed(2):parseFloat(0).toFixed(2);
   
   var cprice =  $(this).closest(".toClone").find(".c-price").val(); 
   cprice = (cprice)?parseFloat(cprice).toFixed(2):parseFloat(0).toFixed(2);
   
   var cdiscount =  $(this).closest(".toClone").find(".c-discount").val(); 
   cdiscount = (cdiscount)?parseFloat(cdiscount).toFixed(2):parseFloat(0).toFixed(2);   
   
  

   //Unit total price
   priceutt = (cprice*cqty).toFixed(2);
   $(this).closest(".toClone").find(".c-price-utt").val(priceutt);
   
 
   //unit discount amt
   discountamt = (priceutt*cdiscount*0.01).toFixed(2);
   $(this).closest(".toClone").find(".c-h-discount-amt").val(discountamt);

  //unit vat 
   vatamt = (((parseFloat(priceutt)-parseFloat(discountamt))*cvat)/100).toFixed(2);
   $(this).closest(".toClone").find(".c-h-vat-amt").val(vatamt);   
   
   //total discounted price with vat
   //discountedttl = ((parseFloat(priceutt)-parseFloat(discountamt))+parseFloat(vatamt)).toFixed(2);
  // $(this).closest(".toClone").find(".c-discounted-ttl").val(discountedttl);
   

   //total discounted price without vat
   discountedttl = (parseFloat(priceutt)-parseFloat(discountamt)).toFixed(2);
   $(this).closest(".toClone").find(".c-discounted-ttl").val(discountedttl);
	
	//put discounted total in hidden value field;
	$(this).closest(".toClone").find('input[name="unittotal[]"]').val(discountedttl);
	
	
   
   tval = parseFloat(tval).toFixed(2);
   $(this).val(tval);
   
   $(this).closest(".toClone").find(".c-discounted-ttl").trigger("change");
});


$(document).on("change input",".calc",function(){
   OrderTotal();
 // alert(1);
});

	

function OrderTotal(){
   
   var subtotal = 0;
   var vattotal = 0;
   var distotal = 0;
   var grndttl = 0;

  //SUBTOTAL
  $(".toClone").each(function(){
      var thisval = $(this).find(".c-discounted-ttl").val();
      if(thisval>0){	subtotal += +thisval; }
   });
   subtotal = subtotal.toFixed(2);
   $(".f-subtotal").val(subtotal);
  
  //VAT TOTAL
    $(".toClone").each(function(){
      var thisvatamt = $(this).find(".c-h-vat-amt").val();
      if(thisvatamt>0){	vattotal += +thisvatamt; }
   });
   vattotal = vattotal.toFixed(2);
   $(".f-vatttl").val(vattotal);
  
  
  //DISCOUNT TOTAL
    $(".toClone").each(function(){
      var thisdiscamt = $(this).find(".c-h-discount-amt").val();
      if(thisdiscamt>0){	distotal += +thisdiscamt; }
   });
  
  
   distotal = distotal.toFixed(2);
   $(".f-disttl").val('('+distotal+')');
  
  var adjmt = $(".f-adjmt").val();
  var delcrg = $(".f-delcrg").val();
	
	
	
   adjmt = (adjmt>0)?parseFloat(adjmt).toFixed(2):parseFloat(0).toFixed(2);
   delcrg = (delcrg>0)?parseFloat(delcrg).toFixed(2):parseFloat(0).toFixed(2);
   
	
	
   grndttl = ((parseFloat(subtotal)+parseFloat(vattotal))-parseFloat(adjmt))+parseFloat(delcrg);
   $(".f-grnd-ttl").val(grndttl.toFixed(2));

  
   console.log(
	   "subtotal:"+subtotal+
	   "\n vattotal:"+vattotal+
	   "\n Adj:"+adjmt+
	   "\n Charge:"+delcrg);
   
   
 
}




</script>
<script src="js/plugins/select2/select2.min.js"></script>
<script>

//COPIER


function callSelect2(){
    
    //alert("i am called");
    //$(".productname").select2();


    $(document).ready(function(){

    
    $(".productname").select2({
        templateResult: formatOption, 
        templateSelection: formatOption,
        //minimumResultsForSearch: -1 
    });
    });

    // Function to format the option with image
    function formatOption(option) {
    if (!option.id) {
    return option.text;
    }

    var imagePath = $(option.element).data('image'); // Get the image path from data-image attribute
    if (!imagePath) {
    return option.text;
    }

    var $option = $(
    '<span class="img-wapper"><img src="' + imagePath + '" class="select-img" /> ' + option.text + '</span>'
    );
    return $option;
    }



}
$(document).ready(function() {

    callSelect2();
    var max_fields      = 500; //maximum input boxes allowed
    var wrapper         = $(".color-block"); //Fields wrapper
    var add_button      = $(".link-add-order"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
       
       

        if(x < max_fields){ //max input box allowed
            x++; 	
		//$(wrapper).
		//$( ".po-product-wrapper .toClone:last-child").clone().appendTo(wrapper);

        $(".toClone:last").find('.productname').select2('destroy');
        var clone = $(".toClone:last").clone();
        $('.clonewrapper').append(clone);
    	$(".toClone:last input").val("");
        $(".toClone:last").find('.productname').val(null).trigger('change');

        callSelect2();



		if(x==2){
			$( ".po-product-wrapper .toClone:last-child").append('<div class="remove-icon"><a href="#" class="remove-order" title="Remove Item"><span class="glyphicon glyphicon-remove"></span></a></div>');
			
		}

        }
        
        
        
        setTimeout(function(){
            
        //check already selected item and disable them.
        //var valuesArray = []; // Array to store the values

          $('.itemName').each(function() {
            var inputValue = $(this).val();
            //valuesArray.push(inputValue);
              
              //  $('.po-product-wrapper .toClone:last-child .option-'+inputValue).prop('disabled', 'disabled');
              //$('.withlebel .toClone:last-child .option-'+inputValue).prop('disabled', true);
              //$('.po-product-wrapper .toClone:last-child .option-'+inputValue).remove();
              $(document).on('click','.po-product-wrapper .toClone:last-child', function(){
                $(this).find(".option-"+inputValue).remove();
              });
          });  
            
            
        },200);
      
        
        
        
    });

    $(wrapper).on("click",".remove-order", function(e){ //user click on remove text
        e.preventDefault();
		$(this).closest(".toClone").remove();
		 OrderTotal();
		x--;
		
    })
});	
	
</script>	
	
	
	
<script>
	//Footer Fields width same as discounted field;
	
function footerfldwdth(){
	ftrfldwdth = $(".c-discounted-ttl").width();
	$(".grid-sum-footer input").width(ftrfldwdth);
}
setTimeout(footerfldwdth,300);

window.addEventListener("resize", () => {
		footerfldwdth();
});	
	
	

var classes = ".grid-sum-footer input, .c-discounted-ttl"

$( "<span>৳</span>" ).insertAfter(classes);
$(classes).parent().addClass("ipspan");

</script>	
	
<script>
$(document).ready(function(){
	
//show INVOICE
	
	$(".revision-tbl").on("click",".show-invoice.btn",function(){
		
  	mylink = $(this).attr('href')+"?qrid="+$(this).data('qrid')+"&socode="+$(this).data('socode')+"&qtype=revision";
	
   //alert(mylink);
  
  
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: 'QUOTATION ID #'+$(this).data('socode'),
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: false, // <-- Default value is false
							cssClass: 'show-invoice',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Cancel',
								action: function(dialog) {
									dialog.close();	
									/*
									$("#printableArea2").printThis({
										importCSS: true, 
										importStyle: true,
									});
									*/
									
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Print',
								action: function(dialog) {
									
									$("#printableArea2").printThis({
										importCSS: false, 
										importStyle: true,
									});
		
									
									dialog.close();	
									
									},
								
							}],
							onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});		
		
	
});
    
    
   
    
</script>



<script>
    
    
    
  $(document).ready(function() {
 // $(document).on("submit", "#Quotationform", function(event) {
    $('#saverevision_no').click(function(e){
    e.preventDefault();
    // Your code here
       var isValid = true;
        
        $(".toClone .qtnqrapper").each(function(){
           alert("im here");
            var grandQty = $(this).find('.c-qty');
            
            $(this).find(".row").each(function(){
                
                 
                 var quantityInput = $(this).find('.quantity');
                 var quantityValue = parseInt(quantityInput.val(), 10);
                 var deliveryDateValue = $(this).find('.delivery-date').val();
                if (quantityValue > 0 && deliveryDateValue.trim() === '') {
                    alert('Invalid quantity or Delivery date. Please fix first');
                    grandQty.trigger("click");
                    return false;
                    isValid = false;
                }
            });
        });
        
        if(isValid){
        $('#Quotationform').submit();
        }
      
  });
});   
    

   
    
    
//show delivery date required if not entered on submit;	

            
            
 $(document).on("submit","#Quotationformx", function(event) {
     
     
     event.stopPropagation();
        //event.preventDefault();
    
        var isValid = true;

       alert(1);
      
    $(this).find('.quantity').each(function() {
        
      var quantityInput = $(this);
          
          var deliveryDateInput = quantityInput.closest('.row').find('.delivery-date');
          var grandQty = quantityInput.closest('.toClone').find('.c-qty');

          var quantityValue = parseInt(quantityInput.val(), 10);
          var deliveryDateValue = deliveryDateInput.val();

          if ((quantityValue > 0 && deliveryDateValue.trim() === '')) {

            grandQty.trigger("click");

            isValid = false;
            return false; // Exit the loop early
          }

        
    });

    if (!isValid) {
      event.preventDefault(); // Prevent form submission
      alert('Invalid quantity or Delivery date. Please fix first');
       
    }

  
  });

//});

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
                        data:{newItem: x, type: 'project'},
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
				alert('Please enter a Project name');
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


<script>
    
    $(document).on("change", "#saletype", function() {
    tp=$(this).val();
    
    if(tp=='1')
    {
       //alert(tp);
        document.getElementById("projdiv").style.display = "none";
        $("#proj").val("")
        $("#desig").val("")
    }
    else
    {
        document.getElementById("projdiv").style.display = "block";
    
    }
    
 
});
    
</script>







<script>






  
</script>



</body>
</html>
<?php } ?>