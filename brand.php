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
    $atid= $_GET['id'];

    if ($res==1)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }
    if ($res==2)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }

    if ($res==4)
    {
        $qry="SELECT `id`, `title`, `origin`,`image`, `activest`, `makeby`, `makedt` from brand where id= ".$atid; 
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
                        $aid=$row["id"];$brand=$row["title"];$origin=$row["origin"]; $aimg=$row["image"];  
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
                        $aid='';$origin='';$brand=''; $aimg=''; 
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'brand';
    $currPage = basename($_SERVER['PHP_SELF']);
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
            <span>Brand </span>
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
                        <form method="post" action="common/addbrand.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			     
								
				                <div class="panel-body panel-body-padding">
                                    <span class="alertmsg"></span>
									
									
									
									
                          <div class="row form-header">

	                                    <div class="col-lg-6 col-md-6 col-sm-6">
      		                                  <h6>Inventory <i class="fa fa-angle-right"></i> <?=($_REQUEST['res'] == 4)?"Edit":"Add"?> Brand</h6>
      		                            </div>

      		                            <div class="col-lg-6 col-md-6 col-sm-6">
      		                               <h6><span class="note"> (Field Marked * are required)</span></h6>
      		                            </div>


                                   </div>									
									
									
                                    
                              
                                    
                                    <div class="row">
      		                            <div class="col-sm-6">
	                           
	                                        
		                                    <input type="hidden"  name="atid" id="atid" value="<?php echo $aid;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
		                                    <input type="hidden"  name="img" id="img" value="<?php echo $aimg;?>"> 
	                                    <div class="row">      
            	                        
                                        <div class="col-lg-6  col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Brand.</label>
                                                <input type="text" class="form-control" id="brand" name="brand" value="<?php echo $brand;?>">
                                            </div>        
                                        </div>
                                        
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Origin.</label>
                                                <input type="text" class="form-control" id="origin" name="origin" value="<?php echo $origin;?>">
                                            </div>        
                                        </div>
                                        
                                        <div class="col-lg-6 col-md-6 col-sm-6">

                                        <strong>Brand Image</strong>

                                        <div class="input-group">

                                            <label class="input-group-btn">

                                                <span class="btn btn-primary btn-file btn-file">

                                                    <i class="fa fa-upload"></i> <input type="file" name="attachment1" id="attachment1" style="display: none;" multiple>

                                                </span>

                                            </label>

                                            <input type="text" class="form-control" readonly>

                                        </div>

                                        <span class="help-block form-text text-muted">

                                            Try selecting one  files and watch the feedback

                                        </span>

                                    </div>
										<?php if($aimg){?>
                                        <div class="col-lg-6 col-md-6 col-sm-6 oldpic">
											
											<label for="descr">Existing Logo <!--input type="hidden" id="isremovepicture" name="isremovepicture" value="0"--></label>
                                            <div class="form-group">
											<span class="picwrapper">
													<!--span class="fa fa-remove"></span>
													<span class="fa fa-ban" style="display: none;"></span-->
													<img src="assets/images/brands/300_300/<?=$aimg?>" width="200"><br>
 
												</span>	
												<div class="help-block">
												<input type="checkbox" id="isremovepicture" name="isremovepicture" value="1"> &nbsp;Remove Logo
												</div>
                                                
                                            </div>        
                                        </div>
										<?php } ?>
                                    	</div>
										
										</div>
									
									</div>
									
									
                                </div>
                            </div> 
                            <!-- /#end of panel -->      
                            <div class="button-bar">
                                <?php if($mode==2) { ?>
    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Brand"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                <?php } else {?>
                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Brand"  id="add" >
                                <?php } ?>
                            <a href = "./brandList.php?mod=12&pg=1">
                                <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                            </a>
                            </div>
							<input type="hidden" name="oldpic" value="<?=$aimg?>">
							
							
                        </form>       
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
<?php    include_once('common_footer.php');?>
    <script>
    $(document).ready(function(e) {
        $("#descr").focus();
		
		$("#isremovepicture").on('ifChecked', function (event) {
				
				$(".picwrapper").attr("style","border:1px solid red");
		});
		
		$("#isremovepicture").on('ifUnchecked', function (event) {
				
				$(".picwrapper").attr("style","border:1px solid #E2E2E2");
		});		
		
		$('.oldpic .fa-remove').on('click', function() {

			var hiddenField = $('#isremovepicture'),
			   val = hiddenField.val();

				if(val == 0){
				hiddenField.val(1);
			  $(".oldpic .fa-ban").show();
			}else{
			  hiddenField.val(0);
			  $(".oldpic .fa-ban").hide();
			}
		});		
		
		
		
    });
    </script>	
	
</body>
</html>
<?php }?>