                                                <h5 class="table-status-title">Status History</h5>
                                                <table class="table table-hover em-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Effective Date</th>
                                                            <th>Status</th>
                                                            <th>Comment</th>
                                                        </tr>
                                                       
                                                    </thead>
    <?php for($i = 0; $i < count($stst); $i++){ ?>
                                                   <tbody>
                                                       <tr>
                                                           
                                                      
                                                        <td data-label="Job Id"><?= $stdt[$i] ?></td>
                                                        <td  data-label="Status"><?= $stst[$i] ?></td>
                                                        <td data-label="Status"></td>
                                                        
                                                         </tr>
                                                   </tbody>
    <?php } ?>
                                                    
                                                </table>
                                                <!-- <li id="yyyy" class="add-btn li-none btn btn-sm btn-info">+Add</li><!--button id="yyy">
                                                    
                                                </button-->
                                                <h5 class="table-status-title">Position History</h5>
                                                 <table class="table table-hover em-table"> 
                                                    <thead>
                                                        <tr>
                                                        <th >Status</th>
                                                        <th >Effective Date</th>
                                                        <th >Department</th>
                                                        <th >Designation</th>
                                                       <th >Job Area</th>
                                                        <th>Reports To</th>
                                                        <!--<div class="col ">Remarks</div> -->
                                                        </tr>
                                                    </thead>
    <?php for($i = 0; $i < count($jtnm); $i++){ ?>
                                                     <tbody>
                                                         <tr>
                                                            <td data-label="Job Id"><?= $acttype[$i] ?> </td>
                                                            <td data-label="Job Id"><?= $actiondt[$i] ?></td>
                                                            <td data-label="Customer Name"><?= $postdept[$i] ?></td>
                                                            <td data-label="Amount"><?= $desnm[$i] ?></td>
                                                            <td data-label="Payment Status"><?= $jbnm[$i] ?></td>
                                                            <td data-label=""><?= $reportto[$i] ?></td>
                                                            <!--<div class="col-2" data-label="Payment Status"></div>-->
                                                        </tr>
                                                    </tbody>
     <?php } ?>
                                                    </table>
   
                                                  <!--  <li class="add-btn1 li-none  btn btn-sm btn-info">+Add</li> -->
                                               
                                                <div id="my-modal" class="modal emod">
                                                  <!-- Modal content -->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="close eclose">×</div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class=" col-sm-6">
                                                                    <label for="cmdt">Start Date*</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control datepicker" id="startdt" name="startdt" value="" required>
                                                                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                                                    </div>        
                                                                </div>
                                                                <div class="col-sm-6">
                                                                     <label for="status" class="mod-lab">Status:</label>
                                                                     <select class="form-select form-select-sm form-control" aria-label=".form-select-sm example" name = "reliver">
                                                                        <option selected>Full Time</option>
                                                                        <option value="">Part Time</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                              <h6 class="mod-lab">Add Comment </h6>  
                                                                <textarea class="st-area pad-text-area"  rows="3" placeholder="Add comment"> </textarea>
                                                            </div>
                                                            <div class="row">
                                                                <button type="button" class="mod-btn">Submit</button>
                                                                <!-- <button type="button" class="mod-btn mod-close">Close</button> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="my-modal2" class="modal emod1">
                                              <!-- Modal content -->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="close eclose1">×</div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                               <div class="col-lg-4 col-md-4 col-sm-4  column">
                                            						<div class="form-group">
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                            							        <option value="">Action Type </option>
                                                							</select>
                                                						  </div>
                                            						</div>				
                                                				</div>
                                            				    <div class="col-lg-4 col-md-4 col-sm-4  column">
                                                					<div class="input-group">
                                                						<input type="text" class="form-control datepicker dt-input" id="" name="" value="">
                                                						<div class="input-group-addon dt-icon"><span class="glyphicon glyphicon-th"></span></div>
                                                					</div>				
                                                				</div>
                                            				    <div class="col-lg-4 col-md-4 col-sm-4  column">
                                            						<div class="form-group">
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                            							        <option value="">Department </option>
                                            							    </select>
                                            						    </div>
                                            						</div>				
                                            				    </div>
                                            				    <div class="col-lg-4 col-md-4 col-sm-4  column">
                                        						    <div class="form-group">
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                                							  <option value="">Job area </option>
                                                							</select>
                                            						    </div>
                                            						</div>				
                                                				</div>
                                                				<div class="col-lg-4 col-md-4 col-sm-4  column">
                                            						<div class="form-group">
                                            						    <div class="form-group styled-select">
                                                							<select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                                							  <option value="">Job Type</option>
                                                							</select>
                                            						    </div>
                                            						</div>				
                                                				</div>
                                                				<div class="col-lg-4 col-md-4 col-sm-4  column">
                                            						<div class="form-group">
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                                							  <option value="">Designation</option>
                                                							</select>
                                            						    </div>
                                            						</div>				
                                                				</div>
                                                				<div class="col-lg-4 col-md-4 col-sm-4  column">
                                            						<div class="form-group">
                                            						    <div class="form-group styled-select">
                                            							    <select name="cmbindtype" id="cmbindtype" class="form-control" required>
                                            							        <option value="">Reports to</option>
                                                							</select>
                                            						    </div>
                                            						</div>				
                                                				</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>