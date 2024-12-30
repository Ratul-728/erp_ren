<?php
require "common/conn.php";
require "rak_framework/connection.php";
require "rak_framework/fetch.php";
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0");




$usr = $_SESSION["user"];
$com = $_SESSION["company"];
$msg = $_GET["msg"];

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {

    $qry = "SELECT * from sitesettings where id= 1";
    //echo $qry; die;
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
    } else {
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $orgname = $row["companynm"];
                $contact = $row["contactno"];
                $website = $row["web"];
                $hotline    = $row["hotline"];
				$officehours    = $row["officehours"];
                $mail    = $row["email"];
                $logo    = $row["logo"];
				$docHeaderLogo    = $row["doc_header_logo"];
                $mail    = $row["email"];
                $address = $row["address"];
                $theme = $row["theme"];
                $reverse = $row["reverse"];
            }

        }

    }
    $mode = 2; //update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'systemsetting';
    $currPage    = basename($_SERVER['PHP_SELF']);
	// load session privilege;
	include_once('common/inc_session_privilege.php');
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>
<style>
input[type="checkbox"] + label:before{display: none;}
.dis-lab{padding-right: 10px;}

/*Toggle alignment */
.dis-img {
    margin-top: -10px;
    margin-bottom: 10px;
}
.dis-row span{
    margin-top: -6px;
}
.dis-row span:nth-child(2){
    margin-top: 10px;

}
.dis-row span label{
    transform: translateY(90%);
}
.dis-row span:first-child{

    transform: translatey(0%)

}


.dis-img .dis-lab{
    margin-left: 15px;
    margin-top: 7px;
}

</style>
<body class="form">
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>System </span>
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

                        <form method="post" action="common/addsystemsetting.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>System Setting</h1></div>
				                <div class="panel-body">


                                    <!-- <br> <p>(Field Marked * are required) </p> -->

									
									<div class="row">
										<div class="col-lg-6">
										
										
										
										
										
										<div class="row">
      		                            <div class="col-sm-12">
	                                      <!--  <h4></h4>
	                                        <hr class="form-hr"> -->

		                                    <input type="hidden"  name="atid" id="atid" value="<?php echo $aid; ?>">
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr; ?>">
		                                    <input type="hidden"  name="img" id="img" value="<?php echo $aimg; ?>">
	                                    </div>
	                                    <span class="alertmsg"></span>
            	                       <div class="row mgs10px">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Organization Name</label>
                                                <input type="text" class="form-control" id="orgname" name="orgname" value="<?php echo $orgname; ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Contact </label>
                                                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $contact; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Website </label>
                                                <input type="text" class="form-control" id="website" name="website" value="<?php echo $website; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Email </label>
                                                <input type="text" class="form-control" id="mail" name="mail" value="<?php echo $mail; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Theme Color </label>
                                                <input type="text" class="form-control" id="theme" name="theme" value="<?php echo $theme; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Reverse Color </label>
                                                <input type="text" class="form-control" id="reverse" name="reverse" value="<?php echo $reverse; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Address</label>
                                                <textarea type="text" cols="5" rows="4" class="form-control" id="address" name="address" ><?php echo $address; ?></textarea>
                                            </div>
                                        </div>
										   
										   
										   
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Outlet Operating Hours </label>
                                                <input type="text" class="form-control" id="officehours" name="officehours" value="<?php echo $officehours; ?>">
                                            </div>
                                        </div>	
										   
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Customer Service Hotline</label>
                                                <input type="text" class="form-control" id="hotline" name="hotline" value="<?php echo $hotline; ?>">
                                            </div>
                                        </div>											   
										   
										   
										   
										   
                                        <div class="col-lg-12 col-md-6 col-sm-6">

											
											
											
											
<div class="row">
                                        <div class="col-lg-6">
                                        	<strong>App Header Logo</strong>
                                            <div class="input-group">
                                                <label class="input-group-btn">
                                                    <span class="btn btn-primary btn-file btn-file">
                                                       <i class="fa fa-upload"></i><input type="file" name="logo" id="attachment1" style="display:none;" onch ange="loadFile(event)" >
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control" readonly>
                                            </div>
                                   	 </div>
                                    	<div class="col-sm-6">
                                				        <h4>Preview</h4>
                                    				</div>
                                    				<div class="col-lg-6">
                                    					<div class="wellx">

                                    						<ul class="product-picture-preview">
                                                            <?php
$logo = "assets/images/site_setting_logo/$logo";
    //$file = "$rootpath/$logo";
    if (is_readable($logo)) {
        $oldfile     = $logo;
        $previewfile = $hostpath . '/' . $logo;
        $deletable   = 1;

    } else {
        $oldfile     = '';
        $previewfile = $hostpath . '/assets/images/site_setting_logo/default/applogo.png';
        $deletable   = 0;
    }
    //$logo = (file_exists($file) == 1)?'assets/images/site_setting_logo/'.$logo:'images/logo-bithut-siteheader.png'; ?>
                                    							<li><img i d="output" class="attachment1"  width="150" alt="" src="<?=$previewfile; ?>"><?=($deletable == 1) ? '
																<div class="help-block">
																<input type="checkbox" id="isremovepicture1" name="isremovepicture1" value="1"> &nbsp;Remove Logo
																</div>
																' : '' ?></li>

                                                                <input type="hidden" id="old_picture" name="old_picture" value="<?=basename($oldfile) ?>">

                                    						</ul>

                                    					</div>

                                    				</div>
                                    </div>											
											
											
											
                                        </div>										   


										   
<div class="col-lg-12 col-md-6 col-sm-6">

											
											
											
											
<div class="row">
                                        <div class="col-lg-6">
                                        	<strong>Document Header Logo</strong>
                                            <div class="input-group">
                                                <label class="input-group-btn">
                                                    <span class="btn btn-primary btn-file btn-file">
                                                       <i class="fa fa-upload"></i><input type="file" name="docheaderlogo" id="docheaderlogo" style="display:none;" on change="loadFile(event,'docheaderlogo')" >
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control" readonly>
                                            </div>
                                   	 </div>
                                    	<div class="col-sm-6">
                                				        <h4> Preview</h4>
                                    				</div>
                                    				<div class="col-lg-6">
                                    					<div class="wellx">

                                    						<ul class="product-picture-preview">
                                                            <?php
$docHeaderLogo = "assets/images/site_setting_logo/$docHeaderLogo";
    //$file = "$rootpath/$logo";
    if (is_readable($docHeaderLogo)) {
        $docHeaderOldfile     = $docHeaderLogo;
        $previewfile = $hostpath . '/' . $docHeaderLogo;
        $deletable   = 1;

    } else {
        $docHeaderOldfile     = '';
        $previewfile = $hostpath . '/assets/images/site_setting_logo/default/logo_letterhead.png';
        $deletable   = 0;
    }
    //$logo = (file_exists($file) == 1)?'assets/images/site_setting_logo/'.$logo:'images/logo-bithut-siteheader.png'; ?>
                                    							<li><img class="docheaderlogo" width="150" alt="" src="<?=$previewfile; ?>"><?=($deletable == 1) ? '
																<div class="help-block">
																<input type="checkbox" id="isremovepicture2" name="isremovepicture2" value="1"> &nbsp;Remove Logo
																</div>' : '' ?></li>

                                                                <input type="hidden" id="old_picture_header" name="old_picture_header" value="<?=basename($docHeaderOldfile)?>">

                                    						</ul>

                                    					</div>

                                    				</div>
                                    </div>											
											
											
											
                                        </div>										   




	                                   

                                    </div>
                                </div>
										
										
										
										
										
										
										
										</div>
									</div>
									
                                    
                            </div>
                            <!-- /#end of panel -->
                            <div class="button-bar">

    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update System Setting"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->

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
<script>
	/*
  setTimeout(function(){
      var ts= document.querySelector('.toggle-switch input[type=checkbox]');
        console.log(ts);
        ts.style.display='none'; }, 3000);
   window.onload= () =>{

   }
   */
</script>
<script>




	
	

 $(function() {

	 $(".logo-remove").click(function(e){
		 var oldpic = $("#old_picture").val();

		 console.log(oldpic);
			$.confirm({
				title: 'Confirm!',
				content: 'Do you want to delete this logo?',
				buttons: {
					confirm: function () {

					  $.ajax({
					   type: "POST",
					   url: 'phpajax/remove_logo.php',
					   dataType:"json",
					   data: {old_picture: oldpic},
					   success: function(data)
					   {
						   //alert(data); // show response from the php script.
						   //messageAlert(data);
						   messageAlertLong(data.msg,'alert-'+data.msgcls);
						   if(data.msgcls == 'success'){
							   $("#output").attr("src","images/logo-bithut-siteheader.png");
							   $(".logo-remove").hide();
							  }
					   }
					 });

					},
					cancel: function () {
						//$.alert('Canceled!');
					}
				}
			});

	});

    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {
		//alert(placeToInsertImagePreview);

        if (input.files) {
            var filesAmount = input.files.length;
			
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
					//alert(event.target.result);
                    //$($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
					$("."+placeToInsertImagePreview).attr('src',event.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

    };

//    $('#gallery-photo-add').on('change', function() {
//        imagesPreview(this, 'div.gallery');
//    });
	 
	 
    $("input[type=file]").on('change', function() {
        imagesPreview(this, $(this).attr("id"));
    });	 
	 
});



</script>

<!-- Email validation -->
<!--script>
    $("#mail").on("input",function(){
        var mail = $(this).val();
        let len = mail.length;
        alert(len)
    });
</script-->

<?php
if ($msg != '') {
        //echo '<script type="text/javascript">messageAlertLong("'.$msg.'","alert-'.$msgcls.'")</script>';
        echo "<script type='text/javascript'>messageAlertLong('" . $msg . "','alert-" . $_GET['msgcls'] . "')</script>";
    }

    ?>
</body>
</html>
<?php } ?>