
                                            <form method="post" action="common/addhc.php"  id="form1" enctype="multipart/form-data">
                                                
                                                <div class="basic-info">
                                                    <h5 class="table-status-title">Basic Information</h5>
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="cd">Employee ID*</label>
                                                                <input type="text" class=" ed-info-box" id="cd" name="cd" value="<?php echo $empcode;?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="fnm">First Name*</label>
                                                                <input type="text" class=" ed-info-box" id="fnm" name="fnm" value="<?php echo $firstname;?>" required>
                                                                <input type = "hidden" name = "acid" value = "<?= $aid ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                           <div class="lab-form-group">
                                                                <label for="lnm">Last Name</label>
                                                                <input type="text" class=" ed-info-box" id="lnm" name="lnm" value="<?php echo $lastname;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                            <label for="dob">Date of Birth</label>
                                                                <input type="text" class=" ed-info-box datepicker" id="dob" name="dob" value="<?php echo $dob;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="cmbdsg">Gender </label>
                                                                <div class="lab-form-group pdd">
                                                                    <select name="cmbdsg"  class="ed-info-box">
                                                                        <option value="">Select Gender</option>
                                                                        <option value="M" <?php if ($gender == 'M') { echo "selected"; } ?>>Male</option>
                                                                        <option value="F" <?php if ($gender == 'F') { echo "selected"; } ?>>Female</option>
                                                                        <option value="O" <?php if ($gender == 'O') { echo "selected"; } ?>>Other</option>
                                                                    </select>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="cmbmartial">Maritial Status </label>
                                                                <div class="lab-form-group pdd">
                                                                    <select name="cmbmartial"  class=" ed-info-box">
                                                                        <option value="">Select Marital Status</option>
                                                                        <option value="S" <?php if ($maritialstatus == 'S') { echo "selected"; } ?>>Single</option>
                                                                        <option value="M" <?php if ($maritialstatus == 'M') { echo "selected"; } ?>>Married</option>
                                                                        <option value="D" <?php if ($maritialstatus == 'D') { echo "selected"; } ?>>Divorced</option>
                                                                        <option value="O" <?php if ($maritialstatus == 'O') { echo "selected"; } ?>>Others</option>
                                                                    </select>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="phone">Nid</label>
                                                                <input type="text" class=" ed-info-box" id="nid" name="nid" value="<?php echo $nid;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="email">TIN</label>
                                                                <input type="text" class=" ed-info-box" id="tin" name="tin" value="<?php echo $tin;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="cmbsrc">Blood Group</label>
                                                                <div class="lab-form-group pdd">
                                                                    <select name="cmbbg"  class=" ed-info-box">
                                                                        <option value="">Select Blood Group</option>
                                                                        <option value="A+" <?php if ($bg == 'A+') { echo "selected"; } ?>>A(+)ve</option>
                                                                        <option value="A-" <?php if ($bg == 'A-') { echo "selected"; } ?>>A(-)ve</option>
                                                                        <option value="B+" <?php if ($bg == 'B+') { echo "selected"; } ?>>B(+)ve</option>
                                                                        <option value="B+" <?php if ($bg == 'B-') { echo "selected"; } ?>>B(-)ve</option>
                                                                        <option value="O+" <?php if ($bg == 'O+') { echo "selected"; } ?>>O(+)ve</option>
                                                                        <option value="O-" <?php if ($bg == 'O-') { echo "selected"; } ?>>O(-)ve</option>
                                                                        <option value="AB+" <?php if ($bg == 'AB+') { echo "selected"; } ?>>AB(+)ve</option>
                                                                        <option value="AB-" <?php if ($bg == 'AB-') { echo "selected"; } ?>>AB(-)ve</option>
                                                                        <option value="O" <?php if ($bg == 'O') { echo "selected"; } ?>>Others</option>
                                                                    </select>
                                                                </div>
                                                            </div>  
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="pp" >Passport No.</label>
                                                                <input type="text" class=" ed-info-box" id="pp" name="pp" value="<?php echo $pp;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="drvid">Driving Licence</label>
                                                                <input type="text" class=" ed-info-box" id="drvid" name="drvid" value="<?php echo $drivinglicense;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="address">
                                                    <h5 class="table-status-title">Present Address</h5>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="lab-form-group">
                                                                <label for="preaddr">Address</label>
                                                                <input type="text" class=" ed-info-box" id="preaddr" name="preaddr" rows="4" value = "<?php echo $presentaddress;?>">
                                                            </div>
                             
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="area">Area</label>
                                                                <input type="text" class=" ed-info-box" id="area" name="area" value="<?php echo $area;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                            <label for="district">District</label>
                                                            <div class="lab-form-group pdd">
                                                                <select name="district" id="district" class=" ed-info-box">
                                                                    <option value="">Select District</option>
                            <?php $qrydis="SELECT `id`, `name` FROM `district`  order by name"; $resultdis = $conn->query($qrydis); if ($resultdis->num_rows > 0) {while($rowdis = $resultdis->fetch_assoc()) 
                                          { 
                                              $tid= $rowdis["id"];  $nm=$rowdis["name"];
                                ?>                                                         
                                                                    <option value="<?php echo $tid; ?>" <?php if ($district == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                            <?php  }}?>                                                       
                                                                </select>
                             
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="zip">Postal Code</label>
                                                                <input type="text" class=" ed-info-box" id="zip" name="zip" value="<?php echo $zip;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="country">Country</label>
                                                                <div class="lab-form-group pdd">
                                                                    <select name="country" id="country" class=" ed-info-box">
                                                                    <option value="">Select Country</option>
                            <?php $qrycon="SELECT `id`, `name` FROM `country`  order by name"; $resultcon= $conn->query($qrycon); if ($resultcon->num_rows > 0) {while($rowcon = $resultcon->fetch_assoc()) 
                                          { 
                                              $tid= $rowcon["id"];  $nm=$rowcon["name"];
                                ?>                                                         
                                                                    <option value="<?php echo $tid; ?>" <?php if ($country == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                            <?php  }}?>                                                       
                                                                    </select>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="address">
                                                    <h5 class="table-status-title">Permanent Address</h5>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="lab-form-group">
                                                                <label for="preaddr">Address</label>
                                                                <input type="text" class=" ed-info-box" id="permanentaddr" name="permanentaddr" rows="4" value = "<?php echo $permanentaddress;?>">
                                                            </div>
                             
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="area">Area</label>
                                                                <input type="text" class=" ed-info-box" id="permanentarea" name="permanentarea" value="<?php echo $permanentarea;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                            <label for="district">District</label>
                                                            <div class="lab-form-group pdd">
                                                                <select name="permanentdistrict" id="permanentdistrict" class=" ed-info-box">
                                                                    <option value="">Select District</option>
                            <?php $qrydis="SELECT `id`, `name` FROM `district`  order by name"; $resultdis = $conn->query($qrydis); if ($resultdis->num_rows > 0) {while($rowdis = $resultdis->fetch_assoc()) 
                                          { 
                                              $tid= $rowdis["id"];  $nm=$rowdis["name"];
                                ?>                                                         
                                                                    <option value="<?php echo $tid; ?>" <?php if ($permanentdistrict == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                            <?php  }}?>                                                       
                                                                </select>
                             
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="zip">Postal Code</label>
                                                                <input type="text" class=" ed-info-box" id="permanentzip" name="permanentzip" value="<?php echo $permanentzip;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="country">Country</label>
                                                                <div class="lab-form-group pdd">
                                                                    <select name="permanentcountry" id="permanentcountry" class=" ed-info-box">
                                                                    <option value="">Select Country</option>
                            <?php $qrycon="SELECT `id`, `name` FROM `country`  order by name"; $resultcon= $conn->query($qrycon); if ($resultcon->num_rows > 0) {while($rowcon = $resultcon->fetch_assoc()) 
                                          { 
                                              $tid= $rowcon["id"];  $nm=$rowcon["name"];
                                ?>                                                         
                                                                    <option value="<?php echo $tid; ?>" <?php if ($permanentcountry == $tid) { echo "selected"; } ?>><?php echo $nm; ?></option>
                            <?php  }}?>                                                       
                                                                    </select>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="contact">
                                                    <h5 class="table-status-title">Contact</h5>
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="off_cont">Official No.</label>
                                                                <input type="text" class=" ed-info-box" id="off_cont" name="off_cont" value="<?php echo $office_contact;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="per_cont">Personal Contact*</label>
                                                                <input type="text" class=" ed-info-box" id="per_cont" name="per_cont" value="<?php echo $pers_contact;?>" minlength="8" maxlength="12" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="alt_cont">Alternate Contact</label>
                                                                <input type="text" class=" ed-info-box" id="alt_cont" name="alt_cont" value="<?php echo $alt_contact;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="ext">Extension</label>
                                                                <input type="text" class=" ed-info-box" id="ext" name="ext" value="<?php echo $ext_contact;?>">
                                                            </div>
                                                        </div>
                             
                             
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="ofc_email">Official Email</label>
                                                                <input type="text" class=" ed-info-box" id="ofc_email" name="ofc_email" value="<?php echo $office_email;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="per_email">Personal Email</label>
                                                                <input type="text" class=" ed-info-box" id="per_email" name="per_email" value="<?php echo $pers_email;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="lab-form-group">
                                                                <label for="alt_email">Alternate Email</label>
                                                                <input type="text" class=" ed-info-box" id="alt_email" name="alt_email" value="<?php echo $alt_email;?>">
                                                            </div>
                                                        </div>
                             
                                                    </div>
                                                    <div class="contact">
                                                        <h5 class="table-status-title">Emergency Contact</h5>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc1">1st Emergency Contact</label>
                                                                    <input type="text" class=" ed-info-box" id="poc1" name="poc1" value="<?php echo $emergency_poc1;?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc1_rel">Relation</label>
                                                                    <input type="text" class=" ed-info-box" id="poc1_rel" name="poc1_rel" value="<?php echo $poc1_relation;?>">
                                                                </div> 
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc1_cont">Contact </label>
                                                                    <input type="text" class=" ed-info-box" id="poc1_cont" name="poc1_cont" value="<?php echo $poc1_contact;?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc1_addr">Address </label>
                                                                    <input type="text" class=" ed-info-box" id="poc1_addr" name="poc1_addr" value="<?php echo $poc1_address;?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc2">2nd Emergency Contact</label>
                                                                    <input type="text" class=" ed-info-box" id="poc2" name="poc2" value="<?php echo $emergency_poc2;?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc2_rel">Relation</label>
                                                                    <input type="text" class=" ed-info-box" id="poc2_rel" name="poc2_rel" value="<?php echo $poc2_relation;?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc2_cont">Contact </label>
                                                                    <input type="text" class=" ed-info-box" id="poc2_cont" name="poc2_cont" value="<?php echo $poc2_contact;?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc2_addr">Address </label>
                                                                    <input type="text" class=" ed-info-box" id="poc2_addr" name="poc2_addr" value="<?php echo $poc2_address;?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc3">3rd Emergency Contact</label>
                                                                    <input type="text" class=" ed-info-box" id="poc3" name="poc3" value="<?php echo $emergency_poc3;?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc3_rel">Relation</label>
                                                                    <input type="text" class=" ed-info-box" id="poc3_rel" name="poc3_rel" value="<?php echo $poc3_relation;?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc3_cont">Contact</label>
                                                                    <input type="text" class=" ed-info-box" id="poc3_cont" name="poc3_cont" value="<?php echo $poc3_contact;?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="lab-form-group">
                                                                    <label for="poc3_addr">Address </label>
                                                                    <input type="text" class=" ed-info-box" id="poc3_addr" name="poc3_addr" value="<?php echo $poc3_address;?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    
                                                    <div class="cus-button-bar">
                                                            <?php if($mode==2) { ?>
                                	                        <input  dat a-to="pagetop" class="btn cus-btn" type="submit" name="update" value="Update Employee"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->
                                                            <?php } else {?>
                                                            <input  dat a-to="pagetop" class="btn cus-btn" type="submit" name="add" value="Add Employee"  id="add" >
                                                            <?php } ?> 
                                                        <a href = "./hcList.php?pg=1&mod=4">
                                                            <input class="btn cus-btn" type="button" name="cancel" value="Cancel"  id="cancel" >
                                                        </a>
                             
                                                        </div> 
                                                </div>
                                                
                                            </form>
                                            
                    
                                            
                                 