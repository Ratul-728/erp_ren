<?php

require_once "common/conn.php";

session_start();

//print_r($_SESSION);die;

$usr = $_SESSION["user"];

if ($usr == '') 
{
    header("Location: " . $hostpath . "/hr.php");
}
else
{

    $res = $_GET['res'];

    $msg = $_GET['msg'];

    $id = $_GET['id'];

    if ($res == 4)
    {

        $qry = " select a.`id`, a.`code`, a.`name`, a.`type`,a.`brand`, a.`mu`, a.`color`,a.colortext, a.`size`, a.`pattern`, a.`rate`, a.`cost`, a.`vat`, a.`ait`, 
                a.`catagory`, a.`dimension`, a.`wight`, a.`currency`, a.`image`, a.`description`, b.name catname, a.parts
                ,a.length,a.lengthunit,a.width,a.widthunit,a.height,a.heightunit,a.note,a.forstock,a.backorderqty,a.finishedst,a.approvedst
                ,COALESCE((select (pi.freight_charges+pi.global_taxes) from purchase_landing_item pi where pi.productId=a.id),0) dutyFreight
                ,a.cal_unitprice,a.cal_transferrate,a.cal_exfactoryprice,a.cal_dutyfreight,a.cal_miscelanious,a.cal_ldp,a.cal_margin
,a.cal_totpriceexcldvat,a.cal_vat,a.cal_totpriceincldvat
                FROM `item` a LEFT JOIN itmCat b ON a.catagory = b.id where a.id= " . $id;

        //echo $qry; die;

        if ($conn->connect_error) {

            echo "Connection failed: " . $conn->connect_error;

        } else {

            $result = $conn->query($qry);

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    $iid  = $row["id"];
                    $code = $row["code"];

                    $name        = $row["name"];
                    $productType = $row["type"];

                    $mu      = $row["mu"];
                    $color   = $row["color"];
                    $colortext   = $row["colortext"];
                    $size    = $row["size"];
                    $pattern = $row["pattern"];
                    $rate    = $row["rate"];
                    $cost    = number_format($row["cost"], 2);
                    $vat     = number_format($row["vat"], 2);
                    $ait     = number_format($row["ait"], 2);
                    $ItemCat = $row["catagory"];
                    $ItemName = $row["catname"];
                    
                    $parts     = $row["parts"];
                    $dimension = $row["dimension"];
                    $weight    = number_format($row["wight"], 2);
                    
                    $length = $row["length"];
                    $lengthunit    = $row["lengthunit"];
                    $width= $row["width"];
                    $widthunit    = $row["widthunit"];
                    $height = $row["height"];
                    $heightunit    = $row["heightunit"];
                   
                    $note = $row["note"];
                    $forstock    = $row["forstock"];
                    $backorderqty= $row["backorderqty"];
                    $finishedst    = $row["finishedst"];
                    $approvedst = $row["approvedst"];
                    $dutyFreight = $row["dutyFreight"];
                    
                    
                    $cal_unitprice = $row["cal_unitprice"];
                    $cal_transferrate    = $row["cal_transferrate"];
                    $cal_exfactoryprice= $row["cal_exfactoryprice"];
                    $cal_dutyfreight    = $row["cal_dutyfreight"];
                    $cal_miscelanious = $row["cal_miscelanious"];
                    $cal_ldp = $row["cal_ldp"];
                    $cal_margin= $row["cal_margin"];
                    $cal_totpriceexcldvat    = $row["cal_totpriceexcldvat"];
                    $cal_vat = $row["cal_vat"];
                    $cal_totpriceincldvat = $row["cal_totpriceincldvat"]; if($cal_totpriceincldvat==0){$cal_totpriceincldvat=$rate;}
                    
                    
                    $currency  = $row["currency"];
                    $prodPhoto = $row["image"];
                    $details   = $row["description"];
                    $brand     = $row["brand"];
                }

            }

        }

        $mode = 2; //update mode

        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    }
    else 
    {

        $iid  = '';
        $code = '';

        $name        = '';
        $productType = '1';

        $mu      = '1';
        $color   = '1';
        $size    = '';
        $rate    = '';
        $cost    = '0';
        $vat     = '7.5';
        $ait     = '0.0';
        $ItemCat = '';

        $dimension = '';
        $weight    = '0';
        $currency  = '1';
        $prodPhoto = '';
        $details   = '';
        $pattern   = '';

                    $parts     ='1';
                    $dimension = '';
                    $weight    = '';
                    
                    $length = '';
                    $lengthunit    = '';
                    $width= '';
                    $widthunit    = '';
                    $height = '';
                    $heightunit    ='';
                   
                    $note = '';
                    $forstock    = '';
                    $backorderqty= '';
                    $finishedst    = '';
                    $approvedst = '';
                    
                    
                    $currency  = '';
                    $prodPhoto = '';
                    $details   = '';
                    $brand     = '';
    


        $mode = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

     */

    $currSection = 'itemAprv';

    $currPage = basename($_SERVER['PHP_SELF']);

    ?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">

<?php include_once 'common_header.php'; ?>

<body class="form">

<?php include_once 'common_top_body.php'; ?>
<div id="wrapper">
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
            <i class="fa fa-group  icon"></i>
            <span>Product  Details</span>
        </div>
        <?php include_once 'menu.php'; ?>
	    <div style="height:54px;">

	    </div>

    </div>
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12">
                    <p>&nbsp;</p> <p>&nbsp;</p>
                    <p>
                        <form method="post" action="common/approveitem.php"  id="form1" enctype="multipart/form-data">
                            <div class="panel panel-info">
				                <div class="panel-body panel-body-padding">
                                    <span class="alertmsg"></span>
                                    <div class="row form-header">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
      		                                <h6>Inventory <i class="fa fa-angle-right"></i> <?=($_REQUEST['res'] == 4)?"Edit":"Add"?> Product</h6>
      		                            </div>
      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12  rawitem-left">
                                            <h4 class="rawitem-left-header"> Product Details</h3>
                                            <div class="row">
                                                <input type="hidden"  name="itid" id="itid" value="<?php echo $iid; ?>">
	                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="code">Product Code </label>
                                                        <input type="text" class="form-control" id="code" name="code" value="<?php echo $code; ?>" disabled>
                                                    </div>

                                                </div>
              	                                <div class="col-lg-8 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="nm">Product Name <span class="redstar">*</span></label>
                                                        <input type="text" class="form-control" id="nm" name="nm" value="<?php echo $name; ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6"> 
                                                    <div class="form-group">
                                                        <label for="cmbcontype">Product Category <span class="redstar">*</span></label>
                                                        <div class="ds-divselect-wrapper cat-name">
                                                            <div class="ds-input">
                                                                <input type="hidden" name="dest" value="">
                                                                <input type="hidden" name="cat_id" id = "cat_id" value = "<?= $ItemCat ?>">
                                                                <input type="text" name="org_name" required  autocomplete="off" placeholder="Select Category"  class="input-box form-control" value = "<?= $ItemName ?>">
                                                            </div>
                                                            <div class="list-wrapper">
                                                                <div class="ds-list" style="display: none;">
                                                                    <ul class="input-ul" tabindex="0" id="inpUl">
                                                                        <li tabindex="1" class="addnew">+ Add new</li>
                                                                        <?php $qryitm = "SELECT * FROM `itmCat` order by name";
                                                                        $resultitm = $conn->query($qryitm);
                                                                        if ($resultitm->num_rows > 0) 
                                                                        {
                										                    $tabindex = 2;
                										                    while ($rowitm = $resultitm->fetch_assoc())
                										                    {
                                                                                $tid = $rowitm["id"];
                                                                                $nm  = $rowitm["name"]; 
                															?>
                                                                            <li  tabindex="<?=$tabindex?>" class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
                                                                            <?php
                										                    $tabindex++;						
                										                    } 
            										                    } 
            															?>
                                                                    </ul>
                                                                </div>
                                                                <div class="ds-add-list" style="display: none;">
                                                                    <div class="row">
                                                                        <div class="col-lg-12 add-more-col">
                                                                            <h3>Add new Category</h3>
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
                                                <div class="col-lg-8 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbbrand">Product Brand</label>
                                                        <div class="form-group styled-select">
                                                            <select name="productbrand" id="productbrand" class="form-control" planceholder="Select Brand" readonly>
                                                                <option value="">Select Brand</option>
        <?php $qrymu = "SELECT `id`, `title` FROM `brand`  order by title";
            $resultmu    = $conn->query($qrymu);
            if ($resultmu->num_rows > 0) 
            {
                while ($rowmu = $resultmu->fetch_assoc()) 
                {
                $brid = $rowmu["id"];
                $brnm = $rowmu["title"];
                ?>
                                                                <option value="<?php echo $brid; ?>" <?php if ($brand == $brid) {echo "selected";} ?>><?php echo $brnm; ?></option>
        <?php 
                }
            } 
        ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="rate">Color <span class="redstar">*</span></label>
                                                        <input type="text" class="form-control"  readonly id="txtcolor" name="txtcolor" value="<?php echo $colortext; ?>" <?php if ($mode == 2) { ?> readonly <?php }?> >
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="email">Currency</label>
                                                        <div class="form-group styled-select">
                                                            <select name="cmbcur1" id="cmbcur1" class="form-control" readonly>
                <?php $qrycur = "SELECT `id`, `name`, `shnm` FROM `currency`  where id=1 order by name";
                $resultcur = $conn->query($qrycur);
                if ($resultcur->num_rows > 0)
                {
                    while ($rowcur = $resultcur->fetch_assoc()) 
                    {
                        $cid = $rowcur["id"];
                        $cnm = $rowcur["shnm"];
                ?>
                                                                <option value="<?php echo $cid; ?>" <?php if ($currency == $cid) {echo "selected";} ?>><?php echo $cnm; ?></option>
                <?php
                    }
                } 
                ?>
                                                            </select>
                                                            <input type="hidden" name="cmbcur" id = "cmbcur" value = "<?= $cid ?>">
                                                            <input type="hidden" class="form-control" id="vat" name="vat" value="<?php echo $vat; ?>" >
                                                            <input type="hidden" class="form-control" id="ait" name="ait" value="<?php echo $ait; ?>" >
                                                            <input type="hidden" class="form-control" id="measureUnit" name="measureUnit" value="1" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="rate">Unit Price <span class="redstar">*</span></label>
                                                        <input type="number" class="form-control numonly-g" min="0" required id="rate" name="rate" value="<?php echo $rate; ?>" <?php if ($mode == 2) { ?> readonly <?php }?> >
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor">For Stock <span class="redstar">*</span></label>
                                                        <div class="form-group styled-select">
                                                            <select name="stocktp" id="stocktp" class="form-control" <?php if ($mode == 2) { ?> readonly <?php }?> readonly>
                                                                <option value="" >Select </option>
                                                                <option value="1" <?php if ($forstock == 1) {echo "selected";} ?>>In Stock</option>
                                                                <option value="2" <?php if ($forstock == 2) {echo "selected";} ?>>Future Stock</option>
                                                                <option value="3" <?php if ($forstock == 3) {echo "selected";} ?>>Back Order </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor"> Future/Backorder Qty</label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="bkqty" name="bkqty" value="<?php echo $backorderqty; ?>" placeholder = "Qty" <?php if ($mode == 2) { ?> readonly <?php }?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h4 class="rawitem-left-header"> Cost Calculation Details</h4>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor"> Unit Price</label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="aprvunitprice" name="aprvunitprice" value="<?php echo $rate; ?>" placeholder = "Unit Price" >
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor"> Transfer  Rate</label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="transerrate" name="transerrate" value="<?php echo $cal_transferrate; ?>" placeholder = "'Unit Price" >
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor"> Exfactory Price </label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="exfactory" name="exfactory" value="<?php echo $cal_exfactoryprice; ?>" placeholder = "Xfactory"  readonly>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor"> Duty and Freight </label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="dutyFreight" name="dutyFreight" value="<?php echo $cal_dutyfreight; ?>" placeholder = "Duty and freight" >
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor"> Miscellaneous </label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="miscellaneous" name="miscellaneous" value="<?php echo $cal_miscelanious; ?>" placeholder = "Miscellaneous" >
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor"> LDP </label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="ldp" name="ldp" value="<?php echo $cal_ldp; ?>" placeholder = "LDP"  readonly>
                                                        </div>
                                                    </div> 
                                                </div>                                                                                           
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor"> Margin </label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="margin" name="margin" value="<?php echo $cal_margin; ?>" placeholder = "margin" >
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor"> Total Price Excluding VAT </label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="exvat" name="exvat" value="<?php echo $cal_totpriceexcldvat; ?>" placeholder = "margin" readonly>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor">  VAT %</label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="calvat" name="calvat" value="<?php echo $cal_vat; ?>" placeholder = "margin" >
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor"> Total Price Including VAT </label>
                                                        <div class="form-group styled-select">
                                                            <input type="text" class="form-control" id="envat" name="envat" value="<?php echo $cal_totpriceincldvat; ?>" placeholder = "Price Including VAT" readonly required>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor">Finish</label>
                                                        <div>
                                                            <input type="checkbox" name="isfinished" id="isfinished" style="width:20px;float:left;text-align:left" value="1" class="form-control" <?php if ($finishedst == 1) { ?> Checked <?php }?> >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="cmbcolor">Approved</label>
                                                        <div><input type="checkbox" name="isapproved" id="isapproved" style="width:20px;float:left;text-align:left" value="1"  class="form-control"  <?php if ($approvedst == 1) { ?> Checked <?php }?> ></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <?php if ($mode == 2) 
                                                    {
                                                    ?>
                    	                                <input  dat a-to="pagetop" class="btn btn-lg btn-default " type="submit" name="update" value="Update item"  id="update" >
                                                    <?php 
                                                    } 
                                                    else 
                                                    { ?>
                                                        <input  dat a-to="pagetop" class="btn btn-lg btn-default " type="submit" name="add" value="Add Item"  id="submit" >
                                                    <?php 
                                                    } 
                                                    ?>
                                                    <a href = "./rawitemAprvList.php?pg=1&mod=3">
                                                        <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 rawitem-right">
                                            <h4 class="rawitem-left-header"> Additional Information</h3>
                                            <div class="row">
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="weight">No of Parts</label>
                                                        <input type="text" class="form-control" id="parts" name="parts" value="<?php echo $parts; ?>" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="dimension">length</label>
                                                        <input type="hidden" class="form-control" id="dimesion" name="dimesion" value="0" >
                                                        <input type="text" class="form-control" id="length" name="length" value="<?php echo $length; ?>" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                     <label for="unitlength">Unit</label>
                                                    <div class="form-group styled-select">
                                                        
                                                        <select name="unitlength" id="unitlength" class="form-control" planceholder="Unit"> 
                                                            <option value="">Unit</option>
                                                            <option value="cm" <?php if ($lengthunit == "cm") {echo "selected";} ?>>CM</option>
                                                            <option value="mm" <?php if ($lengthunit == "mm") {echo "selected";} ?>>MM</option>
                                                            <option value="in" <?php if ($lengthunit == "in") {echo "selected";} ?>>INCH</option>
                                                            <option value="sft" <?php if ($lengthunit == "sft") {echo "selected";} ?>>Square Feet</option>
                                                            <option value="smt" <?php if ($lengthunit == "smt") {echo "selected";} ?>>Square Meter</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="dimension">width</label>
                                                        <input type="text" class="form-control" id="width" name="width" value="<?php echo $width; ?>" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                     <label for="unitwidth">Unit</label>
                                                    <div class="form-group styled-select">
                                                        
                                                        <select name="unitwidth" id="unitwidth" class="form-control" planceholder="Unit"> 
                                                            <option value="">Unit</option>
                                                            <option value="cm" <?php if ($widthunit == "cm") {echo "selected";} ?>>CM</option>
                                                            <option value="mm" <?php if ($widthunit == "mm") {echo "selected";} ?>>MM</option>
                                                            <option value="in" <?php if ($widthunit == "in") {echo "selected";} ?>>INCH</option>
                                                            <option value="sft" <?php if ($widthunit == "sft") {echo "selected";} ?>>Square Feet</option>
                                                            <option value="smt" <?php if ($widthunit == "smt") {echo "selected";} ?>>Square Meter</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="dimension">Height</label>
                                                        <input type="text" class="form-control" id="height" name="height" value="<?php echo $height; ?>" >
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <label for="unitheight">Unit</label>
                                                    <div class="form-group styled-select">
                                                         
                                                        <select name="unitheight" id="unitheight" class="form-control" planceholder="Unit"> 
                                                            <option value="">Unit</option>
                                                            <option value="cm" <?php if ($heightunit == "cm") {echo "selected";} ?>>CM</option>
                                                            <option value="mm" <?php if ($heightunit == "mm") {echo "selected";} ?>>MM</option>
                                                            <option value="in" <?php if ($heightunit == "in") {echo "selected";} ?>>INCH</option>
                                                            <option value="sft" <?php if ($heightunit == "sft") {echo "selected";} ?>>Square Feet</option>
                                                            <option value="smt" <?php if ($heightunit == "smt") {echo "selected";} ?>>Square Meter</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row"></div>
                                            <div class="form-group">
                                                <label for="details">Description </label>
                                                <textarea class="form-control" id="details" name="details" rows="3" ><?php echo $details; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="details">Note </label>
                                                <textarea class="form-control" id="note" name="note" rows="3" ><?php echo $note; ?></textarea>
                                            </div>
                                            <!--div class="form-group">
                                                <strong>Product Image</strong>
                                                <div class="input-group">
                                                    <label class="input-group-btn">
                                                        <span class="btn btn-primary btn-file btn-file">
                                                            <i class="fa fa-upload"></i>
                                                            <input type="file" name="attachment1"  style="display: none;" id="gallery-photo-add" >
                                                            <input type="hidden" name="photoedit"  value = "<?=$prodPhoto ?>" >
                                                        </span>
                                                    </label>
                                                    <input type="text" class="form-control" readonly>
                                                </div>
                                                <span class="help-block form-text text-muted">
                                                    <?=($_GET['err'])?"<span style='color:red;''>".$_GET['err']."</span>":"Only jpg, gif, png and webp files are allowed"?>
                                                </span>
                                            </div-->
                                            <div class="p-1 upload-thumbs ">
                                                <div class="row">
                                            	    <?php // if($$prodPhoto){?>
                                                	<div class="col-xs-3">
                                                  
												
												<span class="picwrapper">
													
													<img id="frame" src="assets/images/products/<?=($prodPhoto)?'300_300/'.$prodPhoto:'placeholder.png'?>" style="height: 100%; width: 100%; object-fit: contain;"><br>
 
												</span>															
												
												<!--div class="help-block" <?=(!$prodPhoto)?'style="display:none"':''?>>
												<input type="checkbox" id="isremovepicture" name="isremovepicture" value="1"> &nbsp;Remove Image
												</div-->														
                                                    </div>

                                                    <?php // } ?>


                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                </div>
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
<?php

    if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }

    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>

<script>


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

                        $("#cat_id").val(lival);
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
                //$(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
                //$(this).closest('.ds-list').attr('style','display:none');
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
                        url:"phpajax/divSelectCat.php",
                        method:"POST",
                        data:{newItem: x},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#cat_id").val(res.id);
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


			//hide ds-list ds-add-list on clicking anywhere on the document;

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

          $("#cat_id").val(tval);              
          $('.input-box').val(txt);
          $('.input-box').focus();
          $('.ds-list').attr('style','display:none');
          }
				}
			});	
	
			
}); //$(document).ready(function(){


</script>
<script>

function preview() {
    frame.src=URL.createObjectURL(event.target.files[0]);
	$("#frame").attr("style","height: 100%; width: 100%; object-fit: contain;");
}

	$('#gallery-photo-add').on('change', function() {
        preview();
    });	
	


	
	

 $(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;
			

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
				//var preHtml += '<div class="col-xs-3"><span>';
                reader.onload = function(event) {
					//alert(event.target.result);
                    $($.parseHTML('<img>')).attr('src', event.target.result).append(placeToInsertImagePreview);
					//$(placeToInsertImagePreview).attr("src",event.target.result);
                }
				//var postHtml += '</div></span>';
                reader.readAsDataURL(input.files[i]);

            }
			//$('.upload-thumbs .gallery img').wrap('<div class="col-xs-3"><span></span></div>');
        }

    };

    $('#gallery-photo-add').on('change', function() {
       // imagesPreview(this, '.upload-thumbs');
    });
});


	
	
//picture border on delete check
	
		$("#isremovepicture").on('ifChecked', function (event) {
				
				$(".picwrapper").attr("style","border:1px solid red");
		});
		
		$("#isremovepicture").on('ifUnchecked', function (event) {
				
				$(".picwrapper").attr("style","border:1px solid #E2E2E2");
		});		

</script>

<script>
    
    $(document).on("change", "#aprvunitprice,#transerrate,#dutyFreight,#miscellaneous,#margin,#calvat", function() {
    up=$("#aprvunitprice").val();
   // rt=$(#transerrate).val();
   rt=$("#transerrate").val(); 
   let exfactory=up*rt;
   $("#exfactory").val(exfactory);
   duty=$("#dutyFreight").val();
   misc=$("#miscellaneous").val();
   let ldp=0;
   ldp=ldp-(-exfactory)-(-duty)-(-misc);
   $("#ldp").val(ldp);
   margin=$("#margin").val();
   let exvat=ldp-(-margin);
   $("#exvat").val(exvat);
   calvat=$("#calvat").val();
   let vat=exvat*calvat*0.01;
   envat=exvat-(-vat);
    $("#envat").val(envat);
   // alert(exfactory);
    //alert(rt);
   /* 
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
    
    }*/
    
 
});
    
</script>

</body>

</html>

<?php } ?>