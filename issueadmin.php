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
    $itid= $_GET['id'];

    
    if ($res==1)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }
    if ($res==2)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }

    if ($res==4 || $res== 6)
    {
        $qry="SELECT a.`id`, a.`tikcketno`, a.`sub`, a.`organization`, a.`issuetype`, a.`issuesubtype`, a.`severity`, concat_ws(' ', b.firstname, b.lastname) `assigned`, a.`status`, a.`reporter`, a.`channel`, a.`issuedetails`, a.`issuedate`,DATE_FORMAT(`probabledate`,'%e/%c/%Y') `probabledate`, a.`product`, a.`accountmanager` FROM `issueticket` a LEFT JOIN employee b ON a.`assigned` = b.id where a.id=".$itid; 
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
        if($res == 4)
            $mode=2;//update mode
        else{
            $mode = 3; //Copy mode
        }
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
                       $issid='';$tikcketno='Auto'; $sub='';  $organization=660;$issuetype='';  $customer='';
                        $severity=1;  $assigned=''; $status=1; $reporter='';$channel='';  
                        $issuedetails=''; $issuedate=''; $probabledate='';$product='';  $accountmanager=46; $issuesubtype = 1;
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'issueadmin';
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
                        <form method="post" action="common/addissueadmin.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->
                            <div class="panel panel-info">
      			               <!-- <div class="panel-heading"><h1>Issue  Ticket</h1></div> -->
				                <div class="panel-body">
                                    <span class="alertmsg"></span> 
                                    
                                    <!-- <br> <p>(Field Marked * are required) </p> -->
                                    
                                    <div class="row">
      		                            <div class="col-sm-12">
      		                                <div class="col-sm-3 text-nowrap">
                                                <h6>Issue <i class="fa fa-angle-right"></i> Issue ticket</h6>
                                           </div>
                                           <br>
                                           <br>
                    	                 <!--  <h4></h4>
	                                        <hr class="form-hr"> -->
	                                        
		                                    <input type="hidden"  name="issid" id="issid" value="<?php echo $issid;?>"> 
		                                    <input type="hidden"  name="isstkt" id="isstkt" value="<?php echo $tikcketno;?>"> 
		                                    <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
	                                    </div> 
	                                    
	                                    <div class="col-md-12 "> <!--Subject-->
                                            <div class="form-group">
                                                <!--<label for="ref">Subject*</label> -->
                                                <input type="text" class="form-control com-nar" id="subject" name="subject" value="<?php echo $sub;?>" autofocus="autofocus"  placeholder="Add a Title" required>
                                            </div>        
                                        </div> <!--Subject-->
	                                   <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="ref">Ticket ID</label> <!--Ticket
                                                <input type="text" class="form-control" id="tcktid" name="tcktid" value="<?php echo $tikcketno;?>" disabled >
                                            </div>        
                                        </div> <!--Ticket-->
                                        
                                        <!-- <div class="col-lg-3 col-md-6 col-sm-6"> <!--Probable Resolve Date
                                            <label for="chqdt">Date & Time</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="probabledate" name="probabledate" value="<?php echo $probabledate;?>">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                            </div>        
                                        </div><!--Probable Resolve Date--> 
                                        <div class="col-lg-3 col-md-6 col-sm-6"><!--Organization--> 
                                            <div class="form-group">
                                                <label for="cmbinv">Organization*</label>
                                                <div class="form-group styled-select">
                                                <select name="cmborg" id="cmborg" class="cmb-parent form-control" required>
                                                	<option value="">Select Organization</option>
													<?php $qryorg="SELECT distinct o.`id`,o.`name` FROM `contact` c,`organization` o where c.`organization`=o.`orgcode`  and c.`contacttype`=1  order by o.name"; $resultorg = $conn->query($qryorg); if ($resultorg->num_rows > 0) {while($roworg = $resultorg->fetch_assoc()){
                                                    	$tid= $roworg["id"];  $nm=$roworg["name"];
                                                    ?>
                                                    <option value="<?php echo $tid; ?>" <?php if ($organization == $tid) { echo "selected"; } ?> ><?php echo $nm; ?></option>
                                                    <?php 
													 }
													}
													?>                                                       
                                                </select>
                                             </div>
                                          </div>        
                                        </div><!--Organization--> 
                                        <!--<div class="col-lg-3 col-md-6 col-sm-6"> <!--Due-->
                                           <!-- <div class="form-group">
                                                <label for="ref">Subject</label>
                                                <input type="text" class="form-control" id="subject" name="subject" value="<?php echo $sub;?>" required>
                                            </div>        
                                        </div> --><!--Subject-->	
                                        <div class="col-lg-3 col-md-6 col-sm-6"> <!--Product--> 
                                            <div class="form-group">
                                                <label for="cmbmode"> Product *</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbprod" id="cmbprod" class="form-control" required>
    <?php 
    $qry1="SELECT `id`,`name` FROM `item`  order by `name` ";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
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
                                        </div><!--Issue Type--> 
                                        <div class="col-lg-3 col-md-6 col-sm-6"><!--Issue Subtype--> 
                                            <div class="form-group">
                                                <label for="cmbmode"> Modules</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbisssbtp" id="cmbisssbtp" class="form-control" >
    <?php 
    $qry3="SELECT `id`,`name` FROM `issuesubtype`  order by `name` ";  $result3 = $conn->query($qry3);   if ($result3->num_rows > 0) { while($row3 = $result3->fetch_assoc())
    { 
              $tid3= $row3["id"];  $nm3=$row3["name"];
    ?>          
                                                        <option value="<? echo $tid3; ?>" <? if ($issuesubtype == $tid3) { echo "selected"; } ?>><? echo $nm3; ?></option>
    <?php }}?>                    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div> <!--Issue Subtype--> 
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
                                        <div class="col-lg-3 col-md-6 col-sm-6"> <!--Assined--> 
                                            <!--<div class="form-group">
                                                <label for="cmbmode"> Assined </label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbassign" id="cmbassign" class="form-control" >
    <?php 
    $qry4="SELECT `id`,`hrName` FROM `hr`  order by `hrName` ";  $result4 = $conn->query($qry4);   if ($result4->num_rows > 0) { while($row4 = $result4->fetch_assoc())
    { 
              $tid4= $row4["id"];  $nm4=$row4["hrName"];
    ?>          
                                                        <option value="<? echo $tid4; ?>" <? if ($assigned == $tid4) { echo "selected"; } ?>><? echo $nm4; ?></option>
    <?php }}?>                    
                                                    </select>
                                                </div>
                                            </div> form-group -- cmbassign  select `id`,  concat(`firstname`,`lastname`) `name`  FROM `employee` order by name-->   
                                            <div class="form-group">
													<lebel>Assigned</lebel>
                                                    <div class="form-group">
                                                       <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                        <div class="form-group styled-select">
                                                            <input list="cmbassign1" name ="cmbassign2" value = "<?= $assigned ?>" autocomplete="Search From list"  class="dl-cmbassign datalist" placeholder="Select Item" required>
                                                            <datalist  id="cmbassign1" name = "cmbassign" class="list-cmbassign form-control" >
                                                            <option value="">Select Item</option>
    <?php $qryitm="select `id`,  concat_ws(' ',`firstname`,`lastname`) `name`  FROM `employee` order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"]; ?>
                                                             <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
    <?php  }}?>                    
                                                            </datalist> 
                                                        </div>
                                                    </div> 
                                                </div>
                                        </div> <!--Assined--> 
                                        <input type = "hidden" id = "cmbassign" name = "cmbassign" value = "">
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6"> <!--Issue Status-->
                                            <div class="form-group">
                                                <label for="cmbmode"> Status </label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbst" id="cmbst" class="form-control" >
    <?php 
    $qry8="SELECT `id`, `stausnm` FROM `issuestatus` order by `stausnm` ";  $result8 = $conn->query($qry8);   if ($result8->num_rows > 0) { while($row8 = $result8->fetch_assoc())
    { 
              $tid8= $row8["id"];  $nm8=$row8["stausnm"];
    ?>          
                                                        <option value="<? echo $tid8; ?>" <? if ($status == $tid8) { echo "selected"; } ?>><? echo $nm8; ?></option>
    <?php }}?>                    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div> <!--Issue Status-->
                                        <!-- <div class="col-lg-3 col-md-6 col-sm-6"> <!--Reporter
                                            <div class="form-group">
                                                <label for="cmbmode"> Reporter </label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbreporter" id="cmbreporter" class="form-control" >
    <?php 
    $qry6="SELECT `id`,`hrName` FROM `hr`  order by `hrName` ";  $result6 = $conn->query($qry6);   if ($result6->num_rows > 0) { while($row6 = $result6->fetch_assoc())
    { 
              $tid6= $row6["id"];  $nm6=$row6["hrName"];
    ?>          
                                                        <option value="<? echo $tid6; ?>" <? if ($reporter == $tid6) { echo "selected"; } ?>><? echo $nm6; ?></option>
    <?php }}?>                    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div> <!--Reporter--> 
                                        <div class="col-lg-3 col-md-6 col-sm-6">  <!--Channel--> 
                                            <div class="form-group">
                                                <label for="cmbmode"> Channel</label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbchannel" id="cmbchannel" class="form-control" >
    <?php 
    $qry5="SELECT `id`, `name` FROM `issuechannel`  order by `name` ";  $result5 = $conn->query($qry5);   if ($result5->num_rows > 0) { while($row5 = $result5->fetch_assoc())
    { 
              $tid5= $row5["id"];  $nm5=$row5["name"];
    ?>          
                                                        <option value="<? echo $tid5; ?>" <? if ($channel == $tid5) { echo "selected"; } ?>><? echo $nm5; ?></option>
    <?php }}?>                    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div>  <!--Channel--> 
                                        <div class="col-lg-3 col-md-6 col-sm-6"> <!--Account Manager--> 
                                            <div class="form-group">
                                                <label for="cmbmode"> Account Manager </label>
                                                <div class="form-group styled-select">
                                                    <select name="cmbram" id="cmbram" class="form-control" >
    <?php 
    $qry7="SELECT `id`,`hrName` FROM `hr`  order by `hrName` ";  $result7 = $conn->query($qry7);   if ($result7->num_rows > 0) { while($row7 = $result7->fetch_assoc())
    { 
              $tid7= $row7["id"];  $nm7=$row7["hrName"];
    ?>          
                                                        <option value="<? echo $tid7; ?>" <? if ($accountmanager == $tid7) { echo "selected"; } ?>><? echo $nm7; ?></option>
    <?php }}?>                    
                                                    </select>
                                                </div>
                                            </div>        
                                        </div> <!--Account Manager--> 
                                        <div class="col-lg-12 col-md-12 col-sm-12"> <!--Issue Details--> 
                                            <div class="form-group">
                                                <label for="ref">Issue Details</label>
                                                <textarea class="form-control" id="issue" rows="4" name="issue" ><?php echo $issuedetails;?></textarea>
                                                
                                            </div>        
                                        </div><!--Issue Details-->
                                        <div class="col-sm-12 col-md-12">
                                    				<div class="col-sm-12">
                                    				  <h4>Add Picture</h4>
                                    				  <hr class="form-hr">
                                    				</div>
                                    				<!-- <div class="col-lg-6 col-md-6 col-sm-12">
                                    				    <strong>Add Featured Picture *</strong>
                                    					<div class="input-group">
                                							<label class="input-group-btn">
                                								<span class="btn btn-primary btn-file btn-file">
                                								   <i class="fa fa-upload"></i><input type="file" name="attachment1" id="attachment1" style="display:none;" onchange="loadFile(event)" >
                                								</span>
                                							</label>
                                						    <input type="text" class="form-control" readonly>
                                    				    </div>
                                						<span class="help-block form-text text-muted">
                                    							Upload only one picture. * JPG file Only
                                    						</span>
                                					</div> -->
                                        			<div class="col-lg-6 col-md-6 col-sm-12">
                            						    <strong>Add Gallery Pictures</strong>
                                						<div class="input-group">
                                							<label class="input-group-btn">
                                								<span class="btn btn-primary btn-file btn-file">
                                								   <i class="fa fa-upload"></i> <input type="file" name="attachment2[]" i d="attachment2" style="display: none;" id="gallery-photo-add" multiple >
                                								</span>
                                							</label>
                                							<input type="text" class="form-control" readonly>
                                						</div>
                                						<span class="help-block form-text text-muted">
                                							Upload multiple pictures. * JPG file Only
                                						</span>
                                					</div>
                                					
                                    				<div class="col-sm-12">
                                				        <h4> Preview Pictures</h4>
                                    				</div>
                                    				<div class="col-lg-12">
                                    					<div class="well">
                                    						<ul class="product-picture-preview">
                                    						    <?php 
                                    						        if($res == 4){
                                    						            $qryedimg = "SELECT `photo` FROM `issuephoto` WHERE `issueticket` = '".$tikcketno."'";
                                    						            $resultedimg = $conn->query($qryedimg);
                                    						            while($rowedimg = $resultedimg->fetch_assoc()){ ?>
                                    						                <li><img src="./images/upload/issue/<?= $rowedimg["photo"] ?>" class="myImg" width="150" alt=""> <!-- <span class="glyphicon glyphicon-remove"></span> --> </li>
                                    						            
                                    						    <?php    }}
                                    						    ?>
                                    							<!--<li><img src="assets/images/pro.png" width="150" alt=""><span class="glyphicon glyphicon-remove"></span></li> -->
                                    							<li><img id="output"  width="150" alt="" src="<?php echo $photo;?>"><?php if($isfeature==1){?><span class="featured-images-badge">Featured</span><?php }?><!--<span class="glyphicon glyphicon-remove"></span></li> -->
                                    							<?php   
                                    							$gqryinv="SELECT id,`image` FROM `productimage` where `product`='".$code."' and imagetype=2";  //echo $gqryinv;die;
                                    							$gresultinv = $conn->query($gqryinv); if ($gresultinv->num_rows > 0) {while($growinv = $gresultinv->fetch_assoc()){
                                                                  
                                                                  $gimgid=$growinv["id"]; $gimg=$growinv["image"];$gphoto="../assets/images/product/70_75/".$gimg;
                                                                  $setdelurl="common/delobj.php?obj=productimage&ret=product&mod=1&id=".$gimgid."&prid=".$aid;
                                                                ?>
                                                               <li><img  src="<?php echo $gphoto;?>" class="myImg" data-imgid="$gimg"  data-id="$gimgid" width="150" alt=""> <a href="<?php echo $setdelurl; ?>" onclick="javascript:confirmationDelete($(this));return false;" class="remove-photo" title="Remove Photo" ><!--<span class="glyphicon glyphicon-remove"></span> --></a></li> 
                                                                <?php }}?>
                                                                
                                                                
                                    							<!--<li><img src="images/placeholder.png" width="150" alt=""><span class="glyphicon glyphicon-remove"></span></li>
                                    							<li><img src="images/placeholder.png" width="150" alt=""><span class="glyphicon glyphicon-remove"></span></li>
                                    							<li><img src="images/placeholder.png" width="150" alt=""><span class="glyphicon glyphicon-remove"></span></li>
                                    							<li><img id="thumbnil" style="width:20%; margin-top:10px;"  src="" alt="image"/></li>-->
                                    						</ul>
                                    						<div class="gallery"></div>
                                    					</div>
                                    					
                                    				</div>
                                    		    </div>
                                        
                                        <!--<div class="col-lg-12 col-md-12 col-sm-12">
                                         <input id="rate-file-input" type="file" name = "attachment2[]" multiple value="Upload" hidden>
                                             <label id="rate-file-input-lab" for="rate-file-input"><i class="fa fa-upload" aria-hidden="true"></i></label>
                                               
                                    </div>
                                    <div class="row" id="preview">
                                        
                                    </div>  <!-- Image -->
										
							            <div class="col-lg-12 col-md-12 col-sm-12">			
									<div cla ss="button-bar">
										<?php if($mode==2) { ?>
										<input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Issue"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
										<?php } else {?>
									 
									
										<input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Submit"  id="add" >
										<?php } ?>
									<a href = "./issueadminList.php?mod=6">
										<input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
									</a>
									</div>	
									
									<!--ul id="ajax-img-up">
                                    				    <li>
                                    				        
                                    				    </li>
                                    				    
                                    				    <li class="addimg-btn">
                                    				        <label class="input-group-btn">
                                    				        
                                    				            <span class="fa fa-plus"></span> <input type="file" name="file" id="upfiles" style="display: none;" i d="gallery-photo-add" multiple >
                                    				       
                                    				       </label>
                                    				    </li>                                    				    
                                    				</ul>
                                    				<div class="clearfix">
                                    				    <p>&nbsp;</p><p>&nbsp;</p><br />
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
<!--<br>
					<div class="issue-commect-wrapper">					

						<div class="panel panel-default Comments">
						  <div class="panel-heading">Comments: <span>Aug 20, 2019 - 05:60 am   </span><img src="images/profile_picture/profile.jpg" class="profile-picture mCS_img_loaded"></div>
						  	<div class="panel-body">
                         		 10 Lak Money Received via City Bank     
                      		</div>
                    	</div>										
										
					</div>	
										<br>
					<div class="issue-commect-wrapper">					

						<div class="panel panel-default Comments">
						  <div class="panel-heading">Comments: <span>Aug 20, 2019 - 05:60 am   </span><img src="images/profile_picture/profile.jpg" class="profile-picture mCS_img_loaded"></div>
						  	<div class="panel-body">
                         		 10 Lak Money Received via City Bank     
                      		</div>
                    	</div>										
										
					</div>
					-->
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
     <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">

    </div>
</div>
<!-- /#page-content-wrapper -->
<?php    include_once('common_footer.php');?>
<script>
    $(document).on("change", ".dl-cmbassign", function() {
        var g = $(this).val();
        var id = $('#cmbassign1 option[value="' + g +'"]').attr('data-value');
        $('#cmbassign').val(id);
        //alert(id);
        
	
	});
</script>

<!--IMAGES PREVIEW-->
  <script>

            function previewImages() {

                var $preview = $('#preview').empty();
                if (this.files) $.each(this.files, readAndPreview);

                function readAndPreview(i, file) {

                    if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
                        //return alert(file.name + " is not an image");
                    } // else...

                    var reader = new FileReader();

                    $(reader).on("load", function () {
                        $preview.append($("<img/>", { src: this.result, height: 100 }));
                    });

                    reader.readAsDataURL(file);

                }

            }

            $('#rate-file-input').on("change", previewImages);

        </script>
        
        
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
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
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
<!-- AJAX PHP IMAGES UP -->


<script>
	
$(document).ready(function(){



    $(document).on('click', '#ajax-img-up li u', function() { 
	
		    var imgToDeletePath = $(this).parent().find("img").attr('src');
		    var thisLi = $(this).parent();
		    
		    
		
		    //alert(imgToDeletePath);
		
           $.ajax({
              url: 'phpajax/deletepicajx.php',
              type: 'post',
              data: {action: 'deletepic', pictodelete: imgToDeletePath},


              success: function(response){
                 if(response != 0){
                     
					
					alert(response);
					thisLi.remove();
					 
                 }else{
                   alert('Error deleting picture');
                }
              },
           });
 
		
		
	});
	
	var picid = 1;
	
    $("#upfiles").change(function(){

        var fd = new FormData();
        var files = $('#upfiles')[0].files;
		
		//alert(files.length);
        
        // Check file selected or not
        if(files.length > 0 ){
           fd.append('file',files[0]);
			
			
			
           $.ajax({
              url: 'phpajax/uploadimageajx.php',
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){
                  
                  if(response == 2){
                      alert('Invalid image dimension');
                  }else if(response != 0){

					 alert(response);
					 $('#ajax-img-up li:last').before('<li class="picbox"><u class="fa fa-trash"></u><label class="custom-radio"><input type="radio" id="picid_'+picid+'" name="default-pic" value="'+response+'"><div class="radio-btn"><i class="fas fa-check" aria-hidden="true"></i><img src="'+response+'"><input type="hidden" name="imgfiles[]" value="'+response+'"></div><label></li>');

					 picid++;
					 
					 //alert(response);
                 }else{
                    alert('file not uploaded');
                 }
              },
           });
        }else{
           alert("Please select a file.");
        }
   });

	
});	
	
	
</script>

<!-- Picture Preview -->
 <script>
        var modal = document.getElementById("myModal");
        var i;

        var img = document.getElementsByClassName("myImg");
        var modalImg = document.getElementById("img01");

        for (i = 0; i < img.length; i++) {
            img[i].onclick = function () {

                modal.style.display = "block";
                modalImg.src = this.src;

            }
        }

        var span = document.getElementsByClassName("close")[0];


        span.onclick = function () {
            modal.style.display = "none";
        }

    </script>

</body>
</html>
<?php }?>