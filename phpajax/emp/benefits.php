<?php
require "../../common/conn.php";
session_start();
$usr=$_SESSION["user"];
$aid= $_GET['id'];

?>
                                                <h5 class="table-status-title">Employee Benifits Status</h5>
                                                <table class="table table-hover em-table">
                                                    
                                                    <thead class="table-header">
                                                        <tr>
                                                        <th>Benifits</th>
                                                        <!--th>Privilage Fund</th>
                                                        <th>Increment</th-->
                                                        <th>Effective Date</th>
                                                        <th>Condition</th>
                                                        </tr>
                                                    </thead>
                                        <?php   $qrybensts = "SELECT a.`id`, p.`title` package, b.`increment`, b.`privilagedfund`,date_format(b.`effectivedate`,'%d/%m/%Y') effectivedate, b.`conditions` FROM `hrcompansation` a 
                                                            LEFT JOIN hrcompansationdetails b ON a.companCode = b.hrcompCode 
                                                            LEFT JOIN `pakage` p ON p.`id` = b.pakage WHERE a.hrid = ".$aid;
                                                $resultbensts = $conn->query($qrybensts);
                                                //echo $qrybensts;die;
                                                while($rowbensts = $resultbensts->fetch_assoc()){
                                        
                                        ?>
                                                    <tbody class="table-row">
                                                        <tr>
                                                        <td><?= $rowbensts["package"] ?></td>
                                                        <!--td><?= $rowbensts["privilagedfund"] ?></td>
                                                        <td><?= $rowbensts["increment"] ?></td-->
                                                        <td><?= $rowbensts["effectivedate"] ?></td>
                                                        <td><?= $rowbensts["conditions"] ?></td>
                                                        </tr>
                                                    </tbody>
                                                      
                                        <?php } ?>
                                        </table>
                                                    <!-- Add button>
                                                    <button type="button" id="my-btn4" class="add-btn2">+Add</button-->
                                              
                            
                                                <h5 class="table-status-title">Employee Benifits History</h5>
                                                <table class="table table-hover em-table">
                                                    <thead class="table-header">
                                                        <th>Year</th>
                                                        <th>Month</th>
                                                        <th>Basic</th>
                                                        <th>House Rent</th>
                                                        <th>Medical</th>
                                                        <th>Transport</th>
                                                        <th>Mobile</th>
                                                        <th>Gross</th>
                                                    </thead>
                                            <?php   $qryslhis = "SELECT `id`, `salaryyear`, `salarymonth`, `hrid`, `benft_1`, `benft_2`, `benft_3`, `benft_4`, `benft_5` FROM `monthlysalary` WHERE hrid = ".$aid." Order BY id desc";
                                                    $resultslhis = $conn->query($qryslhis);
                                                    while($rowslhis = $resultslhis->fetch_assoc()){
                                                        if($rowslhis["salarymonth"] == 1) $slmonth = "January";
                                                        else if($rowslhis["salarymonth"] == 2) $slmonth = "February";
                                                        else if($rowslhis["salarymonth"] == 3) $slmonth = "March";
                                                        else if($rowslhis["salarymonth"] == 4) $slmonth = "April";
                                                        else if($rowslhis["salarymonth"] == 5) $slmonth = "May";
                                                        else if($rowslhis["salarymonth"] == 6) $slmonth = "June";
                                                        else if($rowslhis["salarymonth"] == 7) $slmonth = "July";
                                                        else if($rowslhis["salarymonth"] == 8) $slmonth = "August";
                                                        else if($rowslhis["salarymonth"] == 9) $slmonth = "September";
                                                        else if($rowslhis["salarymonth"] == 10) $slmonth = "October";
                                                        else if($rowslhis["salarymonth"] == 11) $slmonth = "November";
                                                        else $slmonth = "December";
                                                        
                                                        $sltotal = $rowslhis["benft_1"] + $rowslhis["benft_2"] + $rowslhis["benft_3"] + $rowslhis["benft_4"] + $rowslhis["benft_5"];
                                            ?>
                                                    <tbody class="table-row">
                                                        <th><?= $rowslhis["salaryyear"] ?></th>
                                                        <th><?= $slmonth ?></th>
                                                        <th><?= $rowslhis["benft_1"] ?></th>
                                                        <th><?= $rowslhis["benft_2"] ?></th>
                                                        <th><?= $rowslhis["benft_3"] ?></th>
                                                        <th><?= $rowslhis["benft_4"] ?></th>
                                                        <th><?= $rowslhis["benft_5"] ?></th>
                                                        <th><?= $sltotal ?></th>
                                                    </tbody>
                                            <?php } ?>
                                                   </table>
                                                    <!-- Add button>
                                                    <button type="button" i d="my-btn" class="add-btn3">+Add</button--> 
                                                
                                                <div id="my-modal4" class="modal emod2">
                                                  <!-- Modal content -->
                                                   <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="close eclose2">×</div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-4 col-sm-4 column">
                                                                    <div class="form-group">
                                                                        <label for="ben-ent" class="mod-lab">Benifits:</label>
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                            							        <option value="">Promoted</option>
                                                                                <option value="">Demoted</option>
                                            							    </select>
                                            						    </div>
                                            						</div>
                                        						</div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4 column">
                                                                    <label for="ben-ent" class="mod-lab">Effective Date:</label>
                                                                    <div class="input-group">
                                                						<input type="text" class="form-control datepicker dt-input" id="chqdt" name="chqdt1" value="">
                                                						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
                                                					</div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4 column">
                                                                    <label for="ben-ent" class="mod-lab">Entitled:</label>
                                                                    <div class="form-group">
                                        						        <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                            							        <option value="">Yes</option>
                                                                                <option value="">No</option>
                                            							    </select>
                                            						    </div>
                                        						    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <h6 class="mod-lab">Remarks </h6> 
                                                                <textarea class="st-area pad-text-area"  rows="3">  </textarea>
                                                            </div>
                                                            <div class="row">
                                                                <button type="button" class="mod-btn">Submit</button>
                                                             <!-- <button type="button" class="mod-btn mod-close">Close</button> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="my-modal5" class="modal emod3">
                                                  <!-- Modal content -->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="close eclose3">×</div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                                  <div class="form-group">
                                                                    <label for="">Year</label>
                                                        			  <div class="form-group styled-select">
                                                        				  <select name="select1" id="select1" class="form-control">
                                                        					<option value="1">2021</option>
                                                        					<option value="2">2020</option>
                                                        					
                                                        				  </select>
                                                        			  </div>
                                                                  </div>        
                                                                </div>
                                                               <!-- <div class="col-3 mod-col">
                                                                    <label for="ben-dt" class="mod-lab">Year</label>
                                                                    <select name="ben-dt" class="modal2-field" >
                                                                        <option value="">2021</option>
                                                                        <option value="">2020</option>
                                                                    </select>
                                                                </div> -->
                                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                        	        <label for="email">Date </label>
                                                                  <div class="input-group">
                                                                    <input type="text" class="form-control datepicker">
                                                                    <div class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-th"></span>
                                                                    </div>
                                                                  </div>     
                                                                </div>
                                                                <!-- <div class="col-3 mod-col">
                                                                    <label for="ef-date" class="mod-lab">Date</label>
                                                                    <input type="date" class="modal2-field ef-date">
                                                                </div> -->
                                                                  <div class="col-lg-3 col-md-6 col-sm-6">
                                                                  <div class="form-group">
                                                                    <label for="">Amount</label>
                                                                    <input type="number" class="form-control" id="">
                                                                  </div>        
                                                                </div>
                                                                <!-- <div class="col-3 mod-col">
                                                                    <label for="ben-ent" class="mod-lab">Amount</label>
                                                                    <input type="number" class="modal2-field">
                                                                </div>  -->
                                                                 <div class="col-lg-3 col-md-6 col-sm-6">
                                                                  <div class="form-group">
                                                                    <label for="">Type</label>
                                                        			  <div class="form-group styled-select">
                                                        				  <select name="select1" id="select1" class="form-control">
                                                        				<option value="">Monthly</option>
                                                                        <option value="">Weekly</option>
                                                        					
                                                        				  </select>
                                                        			  </div>
                                                                  </div>        
                                                                </div>
                                                                <!--  <div class="col-3 mod-col">
                                                                    <label for="ben-sl" class="mod-lab">Type</label>
                                                                    <select name="ben-sl" class="modal2-field" >
                                                                        <option value="">Monthly</option>
                                                                        <option value="">Weekly</option>
                                                                    </select>
                                                                </div>  -->
                                                            </div>
                                                            <div class="row">
                                                               <h6 class="mod-lab">Remarks </h6> 
                                                                <textarea class="st-area"  rows="3">  </textarea>
                                                            </div>
                                                            <div class="row">
                                                                <button type="button" class="mod-btn">Submit</button>
                                                                <!-- <button type="button" class="mod-btn mod-close">Close</button> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
