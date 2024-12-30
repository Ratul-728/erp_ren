<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
$fd = $_POST['from_dt'];
$td = $_POST['to_dt'];
    if($fd==''){$fd=date("d/m/Y");}
    if($td==''){$td=date("d/m/Y");}
if($usr=='')
{ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
   
   $currSection = 'rpt_profit_loss_fin';
    $currPage    = basename($_SERVER['PHP_SELF']);
}
?>
<?php
     include_once('common_header.php');
?>
<body class="dashboard">
<?php
    include_once('common_top_body.php');
?>
    <div id="wrapper"> 
        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="mCustomScrollbar">
            <div class="section">
          	    <i class="fa fa-group  icon"></i>
                <span>FINANCE</span>
            </div>
<?php
    include_once('menu.php');
?>
	        <div style="height:54px;">
            </div>
        </div>
    <!-- /#sidebar-wrapper --> 
  
    <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid xyz">
                <div class="row">
                    <div class="col-lg-12">
                    <p></p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
                    <!-- START PLACING YOUR CONTENT HERE -->
                    <form method="post" action="rpt_profit_loss_fin.php?pg=1&mod=17" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="button-bar">
                            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label for="po_dt">Order Date*</label>
                                </div>     
                            </div> -->
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Date From  </div>
                                </div>     
                            </div> 
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="from_dt" id="from_dt" value="<?php echo $fd;?>"  required> 
                                </div>     
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Date To</div> 
                                </div>     
                            </div> 
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="to_dt" name="to_dt"  value="<?php echo $td;?>" required> 
                                    
                                </div>     
                            </div>
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  >
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="export" value="Export Flow" id="export">
                            <input class="btn btn-lg btn-default print-view" type="button" name="cancel" value="Print">
                        </div>
                            <?php include_once('phpajax/rpt_load_profit_loss_fin.php'); ?> 
        <!-- /#end of panel -->
                    </form>
                 	 
    
                    <!-- END PLACING YOUR CONTENT HERE -->          
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /#page-content-wrapper -->

<?php
	include_once('common_footer.php');
?>

<script>
    
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}


</script>    
<script>

$(".tbl-bhbs tr").mouseover(function(){
    var thisClass = $(this).attr("class");
    $("."+thisClass).css("background-color","#E6F0FF");
 	 	//$("."+thisClass).css("font-weight","bold");
  
});

$(".tbl-bhbs tr").mouseleave(function(){
    var thisClass = $(this).attr("class");
    $("."+thisClass).css("background","transparent");
 		// $("."+thisClass).css("font-weight","normal");
});
	
	
</script>

</body>
</html>
