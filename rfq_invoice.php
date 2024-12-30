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
    $res=4;

    if ($res==1){echo "<script type='text/javascript'>alert('".$msg."')</script>";}
    if ($res==2){echo "<script type='text/javascript'>alert('".$msg."')</script>";}
    if ($res==4)
    {
        $qry="SELECT org.name, rfqpo.vendor, rfqpo.poid, rfqpo.podate 
                FROM rfqpo rfqpo LEFT JOIN organization org ON org.id = rfqpo.`vendor` WHERE rfqpo.id =".$id; 
        //echo $qry; die;
        if ($conn->connect_error){ echo "Connection failed: " . $conn->connect_error; }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        
                        $iid=$id; $vendor = $row["name"]; $vid = $row["vendor_id"]; $pono = $row["poid"]; $date = $row["podate"];
                        
            
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 
    }
    else
    {
        $iid=''; $vendor='';$pono=''; //Insert mode
        $date=date("d/m/Y");
        $mode = 1;
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rfq_invoice';
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
            <span>PO Inovice</span>
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
                        <form method="post" action="common/addrfq_inovoice.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>PO Invoice</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    <div class="row">
      		                            <div class="col-sm-12"> 
	                                       <!--  <h4></h4>
	                                        <hr class="form-hr"> --> 
		                                    <input type="hidden"  name="itid" id="itid" value="<?php echo $iid;?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
		                                    <input type="hidden"  name="vid" id="vid" value="<?php echo $vid;?>">
	                                    </div>
	                                    
	                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">PO No</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="vendor" name="vendor" value="<?php echo $pono ;?>" disabled>
                                              
                                            </div>        
                                        </div>
      	                                
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">Vendor</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="vendor" name="vendor" value="<?php echo $vendor ;?>" disabled>
                                              
                                            </div>        
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">Recieved Date</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="rdate" name="rdate" value="<?php echo $date ;?>" disabled>
                                            </div>        
                                        </div>
                                        
                                        
                                         <br>
                                    <div class="po-product-wrapper-req"> 
                                        <div class="color-block-req">
     		                                <div class="col-sm-12">
	                                            <h4>Item Information  </h4>
		                                        <hr class="form-hr">
		                                        <div class="row">
                                            <div class="col-lg-2 col-md-6 col-sm-6">
                                                <h6 class="chalan-header mgl10"> Quotation </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header mgl10"> RFQ </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Product </h6>
                                                </div>
                                    
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Offered Quantity </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Quotated Price </h6>
                                                </div>
                                                <div class="col-lg-2 col-md-6 col-sm-6">
                                                    <h6 class="chalan-header"> Invoice Amount </h6>
                                                </div>
                                                
                                        </div>
	                                        </div>
<?php
	   
$itmdtqry="SELECT a.id rfqvid, ra.id rfqaid, a.quotation, rd.rfq, i.name, a.order_qty, a.offered_qty, a.quated_price, rfpo.id rfpoid, rfpo.status, rfpo.qty

FROM rfqpo_details rfpo LEFT JOIN rfq_authorisation ra ON rfpo.rfq_auth = ra.id LEFT JOIN `rfq_vendor` a ON ra.rfq_vendor = a.id LEFT JOIN organization org ON org.id = a.`vendor_id` LEFT JOIN item i ON a.product = i.id LEFT JOIN rfq_details rd ON a.rfq=rd.id

WHERE rfpo.pono = '$pono'";
$totamount = 0;
//echo $itmdtqry;die;            
$resultitmdt = $conn->query($itmdtqry); 
    while($rowitmdt = $resultitmdt->fetch_assoc()) 
              { 
                  $quotation= $rowitmdt["quotation"]; $rfq=$rowitmdt["rfq"]; $product =$rowitmdt["name"]; $qty=$rowitmdt["order_qty"];$offered_quantity=$rowitmdt["offered_qty"]; 
                  $price=$rowitmdt["quated_price"]; $rfqvid = $rowitmdt["rfqvid"]; $rfqaid = $rowitmdt["rfqaid"]; $rfpoid = $rowitmdt["rfpoid"]; $rfst = $rowitmdt["status"]; $rfqty = $rowitmdt["qty"]; 
                  $totamount += $price;
?>                                            
                                            <!-- this block is for php loop, please place below code your loop  -->   
                                            <div class="toCloneReq">
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  id="quotation" placeholder="Quotation"  name="quotation[]" disabled value = <?= $quotation ?>>
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" id="rfqvid"  name="rfqvid[]" value = <?= $rfqvid ?>>
                                                <input type="hidden" id="rfpoid"  name="rfpoid[]" value = <?= $rfpoid ?>>
                                                    
                                                    <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="rfq" placeholder="RFQ"  name="rfq[]" disabled value = <?= $rfq ?>>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  id="product" placeholder="Product"  name="product[]" disabled value = <?= $product ?>>
                                                    </div>
                                                </div>
                                            
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  id="offered_qty" placeholder="Offered Quantity"  name="offered_qty[]" disabled value = <?= $offered_quantity ?>>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="offered_quantity"  name="offered_quantity[]" value = <?= $offered_quantity ?>>
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  id="price" placeholder="Quotated Price"  name="price[]" disabled value = <?= $price ?>>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  id="invoiceamt" placeholder="Amount"  name="invoiceamt[]" value = "" required>
                                                    </div>
                                                </div>
                                               
                                                 
                                            </div>
<?php  } ?>                                   
                                            <div class="col-lg-6 col-md-6 col-sm-6"> </div>
                                            <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    
                                                        <h4>Total:</h4>
                                                    
                                                </div>
                                            <div class="col-lg-2 col-md-6 col-sm-6"> <!-- this block is for probability-->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  id="totalprice" placeholder="Total Price"  name="totalprice" disabled value = <?= $totamount ?>>
                                                    </div>
                                                </div>
                                    		<!-- this block is for php loop, please place below code your loop  --> 
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="ddt">Attachment</label>
                                            <button class="doc-up-btn add-btn7" type="button"><i class="fa fa-upload doc-subhead-icon"
                                                            aria-hidden="true"></i><span class="show-span">upload</span></button>       
                                        </div>
                                        
                                        
                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        <div class="form-group">

                                            <label for="details"> Note</label>

                                            <textarea class="form-control" id="note" name="note" rows="2" ></textarea>

                                        </div>

                                    </div>
                                    
                                    <div id="my-modal9" class="modal emod7">
                             
                              <!-- Modal content -->
                               <div class="modal-content">
                                <div class="modal-header">
                                  <div class="close eclose7">×</div>
                             
                                </div>
                                <div class="modal-body">
                                         
                                    <div class="row">
                                        <div class="file-loading">
                                            <input id="input-ficons-1" name="input-ficons-1[]" multiple type="file">
                                        </div>
                        
                                   <button type="button" class="doc-mod-btn eclose7">Submit</button>
                                        
                                 </div>
                                </form>
                             
                                </div>
                             
                              </div>
                              </div>
                                        
                                    </div>      
                                   
                                    <br><br>&nbsp;<br><br>
                                    </div>
                                </div>
                            </div> 
                            
                            <!-- /#end of panel --> 
                            <div class="button-bar">
                                
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default" type="submit" name="add" value="Create Invoice"  id="submit" >
                                 
                            <a href = "./rfq_invoiceList.php?pg=1&mod=14">
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
$('.add-btn7').click(function(){
        //alert("el");
       // emod.style.display="block";
        $('.emod7').attr('style', 'display:block;');
    });
    $('.eclose7').click(function(){
   $('.emod7').attr('style', 'display:none') ;
});

$( "#submit" ).click(function() {
  $( "#form1" ).submit();
});

$("#input-ficons-1").fileinput({
    
    uploadAsync: false,
    previewFileIcon: '<i class="fas fa-file"></i>',
    allowedPreviewTypes: null, // set to empty, null or false to disable preview for all types
    previewFileIconSettings: {
        'doc': '<i class="fas fa-file-word text-primary"></i>',
        'xls': '<i class="fas fa-file-excel text-success"></i>',
        'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
        'jpg': '<i class="fas fa-file-image text-warning"></i>',
        'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
        'zip': '<i class="fas fa-file-archive text-muted"></i>',
        'htm': '<i class="fas fa-file-code text-info"></i>',
        'txt': '<i class="fas fa-file-text text-info"></i>',
        'mov': '<i class="fas fa-file-movie-o text-warning"></i>',
        'mp3': '<i class="fas fa-file-audio text-warning"></i>',
    },
    previewFileExtSettings: {
        'doc': function(ext) {
            return ext.match(/(doc|docx)$/i);
        },
        'xls': function(ext) {
            return ext.match(/(xls|xlsx)$/i);
        },
        'ppt': function(ext) {
            return ext.match(/(ppt|pptx)$/i);
        },
        'zip': function(ext) {
            return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
        },
        'htm': function(ext) {
            return ext.match(/(php|js|css|htm|html)$/i);
        },
        'txt': function(ext) {
            return ext.match(/(txt|ini|md)$/i);
        },
        'mov': function(ext) {
            return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
        },
        'mp3': function(ext) {
            return ext.match(/(mp3|wav)$/i);
        },
    }
});
</script>

</body>

</html>

<?php }?>