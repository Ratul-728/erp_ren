<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
//echo $usr;die;

//print_r($_REQUEST);die;
$rpid = $_GET["rpid"];

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    $res  = $_GET['res'];
    $msg  = $_GET['msg'];
    $oid  = $_GET['id'];
    $retf = $_GET['ret'];

    // $serno= $_GET['id'];
    $totamount = 0;

    if ($res == 1) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
        $mode = 1;
    } elseif ($res == 2) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
        $mode = 1;
    } else {
        $mode = 1; //Insert mode
    }

    $currSection = 'collection';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
    
    

<?php
include_once 'common_header.php';
    ?>

<style>
           .modal-dialog {
                width: 800px;
            }
        </style>  
<body class="form">

<?php
include_once 'common_top_body.php';
    ?>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Customer Order</span>
        </div>
        <?php include_once 'menu.php'; ?>

        <div style="height:54px;"></div>
    </div>
    <!-- END #sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12" >
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>

                     <form method="post" action="common/updatecusorder.php" id="form1" enctype="multipart/form-data">
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <!--input type="hidden"  name="odid" id="odid" value="<?php echo $oid; ?>" -->


                    <?php include_once 'phpajax/load_collection_rec_view.php'; ?>
        <!-- /#end of panel -->
                     <div class="button-bar">
                        <!--<div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="form-group">
                                <div class="form-group styled-select">
                                    <select name="cmbsupnm" id="cmbsupnm" class="form-control" required>
                                        <option value="">Select Delivery Agent </option>
<?php
$qry1    = "SELECT `id`, `name`  FROM `deveryagent` order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                        <option value="<?php echo $tid; ?>" <?php if ($deleveryagent == $tid) {echo "selected";} ?>><?php echo $nm; ?></option>
<?php }} ?>
                                    </select>
                                </div>
                            </div>
                        </div>   -->

                    </div>
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
    ?>




<script>
		
$(".viewnprint").on("click",function(){
		
  	mylink = $(this).attr('prnt-link');
   //alert(mylink);
  
  
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: 'PAYMENT RECEIPT',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: false, // <-- Default value is false
							draggable: true, // <-- Default value is false
							cssClass: 'post-posdata',
							buttons: [{
								icon: 'glyphicon glyphicon-print',
								cssClass: 'btn-primary',
								label: ' Print',
								action: function(dialog) {
									
									$("#printableArea2").printThis({
										importCSS: true, 
										importStyle: true,
									});
									
									
									
								}
							},
								{
								icon: 'glyphicon glyphicon-print',
								cssClass: 'btn-primary',
								label: ' Done',
								action: function(dialog) {
									dialog.close();	
									
								}
							}]
						});		
  
  
  
  
  
  
  	return false;
});
	
	
</script>

	<?php
	if($_REQUEST['msg']){
	?>
		<script type='text/javascript'>messageAlert('<?=$_REQUEST['msg']?>')</script>    
	
	<?php
	}
	?>


</body>
</html>



<?php } ?>