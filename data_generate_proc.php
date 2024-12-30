<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $aid= $_GET['id'];
    $pyr='';

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
    $currSection = 'proc1';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    /*if ( isset( $_POST['submit'] ) ) {
           header("Location: ".$hostpath."/common/addpriv.php");
    }*/
    $mnhrid = $_POST['cmbempnm'];
    if($mnhrid==''){$mnhrid=$hrid;}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>

<body class="form">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
            <i class="fa fa-group  icon"></i>
            <span>Data Process</span>
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
                        <form method="post" action="common/rsp_proc.php"  id="form1">     
                            <div class="panel panel-info">
                               <!-- <div class="panel-heading"><h1>Data Generation Process</h1></div> -->
                                <div class="panel-body">
                                    <span class="alertmsg"></span>
                                    <!--<br>
      	                            <p>(Field Marked * are required) </p>
      	                             
      	                             -->
      	                             
      	                             <div class="row">
                                        <div class="col-sm-3 text-nowrap">
                                                <h6>Settings <i class="fa fa-angle-right"></i> Data Generation Process </h6>
                                           </div>
                                           <br>
                                           <br>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
	                                    </div> 
                                        
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbyr"> Process Year <span class = "redstar">*</span></label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbyr" id="cmbyr" class="form-control" required>
            <?php 
             $yr=date("Y");
            ?>                                          <option value = "">Select Year</option>
                                                        <option value="<? echo $yr-1; ?>" <? if ($pyr == $yr-1) { echo "selected"; } ?>><? echo $yr-1; ?></option>
                                                        <option value="<? echo $yr; ?>" <? if ($pyr == $yr) { echo "selected"; } ?>><? echo $yr; ?></option>
                                                        <option value="<? echo $yr+1; ?>" <? if ($pyr == $yr+1) { echo "selected"; } ?>><? echo $yr+1; ?></option>
                          
                                                    </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbmonth"> Process Month <span class = "redstar">*</span></label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbmonth" id="cmbmonth" class="form-control" required>
                                                    <option value = ""> Select Month</option>
            <?php 
            $mon= '';
            for($i=1;$i<=12;$i++)
            {
            ?>          
                                                        <option value="<? echo  str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <? if (date('F', mktime(0, 0, 0, $i, 1)) == $mon) { echo "selected"; } ?>><? echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
            <?php } ?>                    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>"> 
                                                <label for="proc_nm">Process Name *</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbprocnm" id="cmbprocnm" class="form-control"  required>
                                                <option value="">Select Process</option>
    <?php 
    $qry1="SELECT `sp_text`,`name` FROM `process_list` ";$result1 = $conn->query($qry1); if ($result1->num_rows > 0)
    {while($row1 = $result1->fetch_assoc()) 
          {   $tid= $row1["sp_text"];  $nm=$row1["name"]; 
    ?>          
                                                    <option value="<? echo $tid; ?>"><? echo $nm; ?></option>
    <?php 
          }
    }      
    ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                              <label for="email"> </label>
                                            <div class="form-group">
                                                <input class="btn btn-lg btn-default" type="button" name="psp" value="Process"  id="psp" > 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>    
                        
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

<script>
$("#psp").on('click', function(){
    //  swal({
    //   title: "Are you sure?",
    //   text: "It will affect salary statement!",
    //   icon: "warning",
    //  });
    swal({
      title: "Are you sure?",
      text: "It will affect the system!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $( "#form1" ).submit();
      }
    });
});
</script>

</body>
</html>
<?php }?>