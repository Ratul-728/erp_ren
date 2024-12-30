<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["customer"];
if($usr=='')
{ header("Location: ".$hostpath."/customer_login.php"); 
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $itid= $_GET['id'];

    
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
        $qry="SELECT `id`, `tikcketno`, `sub`, `organization`, `issuetype`, `issuesubtype`, `severity`, `assigned`, `status`, `reporter`, `channel`, `issuedetails`, `issuedate`,DATE_FORMAT(`probabledate`,'%e/%c/%Y') `probabledate`, `product`, `accountmanager` FROM `issueticket` where id= ".$itid; 
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
                        $issid=$row["id"];$tikcketno=$row["tikcketno"]; $sub=$row["sub"];  $organization=$row["organization"];$issuetype=$row["issuetype"];  $issuesubtype=$row["issuesubtype"];
                        $severity=$row["severity"];  $assigned=$row["assigned"]; $status=$row["status"]; $reporter=$row["reporter"];$channel=$row["channel"];  
                        $issuedetails=$row["issuedetails"]; $issuedate=$row["issuedate"]; $probabledate=$row["probabledate"];$product=$row["product"];  $accountmanager=$row["accountmanager"]; 
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
                       $fcid='';$tikcketno=''; $sub='';  $organization='';$issuetype='';  $customer='';
                        $severity='';  $assigned=''; $status=''; $reporter='';$channel='';  
                        $issuedetails=''; $issuedate=''; $probabledate='';$product='';  $accountmanager=''; 
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'issuecustomer';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<link rel="stylesheet" href="./css/ak-bit.css">

<body class="form">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Issue Ticket</span>
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
                        <form method="post" action="common/addissuecustomer.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			                <div class="panel-heading"><h1>Issue Ticket</h1></div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    
                                    <div class="row"> 
      		                            <div class="col-sm-12">
	                                      <!--  <h4></h4>
	                                        <hr class="form-hr"> -->
	                                        
		                                    <input type="hidden"  name="issid" id="issid" value="<?php echo $issid;?>"> 
		                                    <input type="hidden"  name="isstkt" id="isstkt" value="<?php echo $tikcketno;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
	                                    </div> 
	                                    
	                                            
	                                    <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Ticket ID</label> <!--Ticket
                                                <input type="text" class="form-control" id="tcktid" name="tcktid" value="<?php echo $tikcketno;?>" disabled >
                                            </div>        
                                        </div> <!--Ticket-->
                                        
                                         <div class="col-lg-12"> <!--Subject-->
                                            <div class="form-group">
                                            
                                                <input type="text" class="form-control" id="subject" name="subject" value="<?php echo $sub;?>" placeholder="Add a subject" required>
                                            </div>        
                                        </div> <!--Subject-->
                                       
                                        <div class="col-lg-3 col-md-6 col-sm-6"> <!--Product--> 
                                            <div class="form-group">
                                                <label for="cmbmode"> Company *</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbprod" id="cmbprod" class="form-control" required>
    <?php 
    $qry1="SELECT a.id, a.name FROM `organization` a, contact b where b.organization = a.`orgcode` and b.id = ".$usr;  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
              $tid= $row1["id"];  $nm=$row1["name"];
    ?>          
                                                        <option value="<? echo $tid; ?>" <? if ($product == $tid) { echo "selected"; } ?>><? echo $nm; ?></option>
    <?php }}?>                    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div> <!--Product-->
                                        <div class="col-lg-3 col-md-6 col-sm-6"> <!--Issue Type--> 
                                            <div class="form-group">
                                                <label for="cmbmode"> Issue Type</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbisstp" id="cmbisstp" class="form-control" >
    <?php 
    $qry2="SELECT `id`,`name` FROM `issuetype`  order by `name` ";  $result2 = $conn->query($qry2);   if ($result2->num_rows > 0) { while($row2 = $result2->fetch_assoc())
    { 
              $tid2= $row2["id"];  $nm2=$row2["name"];
    ?>          
                                                        <option value="<? echo $tid2; ?>" <? if ($issuetype == $tid2) { echo "selected"; } ?>><? echo $nm2; ?></option>
    <?php }}?>                    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6"> <!--Severety level--> 
                                            <div class="form-group">
                                                <label for="cmbmode"> Severety Level</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbsevere" id="cmbsevere" class="form-control" >
                                                         <?php 
    $qrypri="SELECT `id`,`name` FROM `issuepriority`  order by `name` ";  $resultpri = $conn->query($qrypri);   if ($resultpri->num_rows > 0) { while($rowpri = $resultpri->fetch_assoc())
    { 
              $tidpri= $rowpri["id"];  $nmpri=$rowpri["name"];
    ?>          
                                                        <option value="<? echo $tidpri; ?>" <? if ($severity == $tidpri) { echo "selected"; } ?>><? echo $nmpri; ?></option>
    <?php }}?>                    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div> <!--Severety level--> 
                                        
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                            						    <h6 class="post-pg-title">Add Gallery Pictures</h6>
                            						    
                                						<div class="input-group">
                                							<label class="input-group-btn">
                                								<span class="btn btn-primary btn-file btn-file">
                                								   <i class="fa fa-upload"></i> <input type="file" name="attachment2[]" id="attachment2" style="display: none;" id="gallery-photo-add" multiple >
                                								</span>
                                							</label>
                                						<!--	<input type="text" class="form-control" readonly> -->
                                						</div>
                                					<!--	<span class="help-block form-text text-muted">
                                							Upload multiple pictures. 
                                						</span> -->
                                					</div> <br><!-- Multiple image -->
                                					<div class="col-lg-12">
                                    					<div class="well">
                                    					
                                    						<div class="gallery" ></div>
                                    					</div>
                                    					
                                    				</div>
                        
                                        <div class="col-lg-12 col-md-12 col-sm-12"> <!--Issue Details--> 
                                            <div class="form-group">
                                                <label for="ref">Issue Details</label>
                                                <textarea class="form-control" id="issue" rows="4" name="issue" ><?php echo $issuedetails;?></textarea>
                                            </div>        
                                        </div><!--Issue Details-->
										
							            <div class="col-lg-12 col-md-12 col-sm-12">			
									<div cla ss="button-bar">
										<?php if($mode==2) { ?>
										<input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Issue"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
										<?php } else {?>
										<input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Issue"  id="add" >
										<?php } ?> 
									<a href = "http://bithut.biz/BitFlow/issuecustomerList.php?mod=7">
										<input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
									</a>
									</div>										
										
							</div> <!-- Common Button -->	
										
                                        <div class="col-lg-12">	
                                        	<br><br>
                                        </div>
                                        <div class="col-sm-12">
	                                        <h4>Activity</h4>
		                                    <hr class="form-hr">
    	                                </div>
    	                                <div class="col-lg-12 col-md-12 col-sm-12">	 <!--Issue Details--> 
                                            <div class="form-group">
                                                <label for="ref">Note</label>
											    <textarea class="form-control" id="issuecomment" rows="4" name="issuecomment" ></textarea>
                                            </div>        
                                        </div><!--Issue Details-->
									    <div class="col-lg-12 col-md-12 col-sm-12">	
										    <div cl ass="button-bar">
											    <input  dat a-to="pagetop" class="btn btn-lg btn-default " type="submit" name="comment" value="Add Comment"  id="comment" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->                            
										    </div> 									
									    </div>
									    <div class="col-lg-12 col-md-12 col-sm-12 ">
										    <br>
<?php										    
 $qryact="SELECT  a.issueid,  a.`ticketid`,a.`activity`, h.`hrName`,date_format(a.`makedt`,'%b %d ,%Y %r') `makedt`,h.`emp_id`  FROM `isssueactivity` a,hr h WHERE a.makeby=h.id and a.issueid=".$issid."  order by a.`makedt` ";  $resultact = $conn->query($qryact);   if ($resultact->num_rows > 0) { while($rowact = $resultact->fetch_assoc())
    { 
              $activity= $rowact["activity"];  $hrName=$rowact["hrName"];$makedt=$rowact["makedt"];
              
              $photo=$rootpath."/common/upload/hc/".$rowact["emp_id"].".jpg";
             // echo $photo;
            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";
            if (file_exists($photo)) {
        		$photo="common/upload/hc/".$rowact["emp_id"].".jpg";
        		}else{
        			$photo="images/blankuserimage.png";
        		}
    ?>          
   										    
				                            <div class="issue-commect-wrapper">
					                            <div class="panel panel-default Comments">
					                                <div class="panel-heading">Comments: <span><?php echo  $makedt; ?>   </span><img src="<?php echo $photo;?>" class="profile-picture mCS_img_loaded"></div>
					  	                            <div class="panel-body"><?php echo $activity;?> </div>
                	                            </div>	
            	                            </div>

<?php }}?>					
										
									    </div>
                                    </div>
                                </div>
                            </div>  
                            <!-- /#end of panel -->      
      
  
                        </form> 

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
<?php    include_once('common_footer.php');?>
<!-- Photo -->
<script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
  
  
 $(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img class="gallery-im" >')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#gallery-photo-add').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
});



</script>

</body>
</html>
<?php }?>