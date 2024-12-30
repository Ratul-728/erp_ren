<?php
require "../../common/conn.php";
session_start();
$usr=$_SESSION["user"];
$aid= $_GET['id'];

?>
                                             <h5 class="table-status-title">Leave</h5>
                                                <table class="table table-hover em-table">
                            
                                                    <thead class="table-header">
                                                        <tr>
                                                        <th>Leave Type</th>
                                                        <th>Applied Date</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Total Days</th>
                                                        <th>Details</th>
                                                        <th>Approver</th>
                                                        <th>Approved Action</th>
                                                        <th>Approver Comment</th>
                                                        </tr>
                                                    </thead>
                            
                                                    
                                        <?php $qryleaveap = "SELECT l.hrid,h1.hrName
                                                            ,(select name from designation where id= ha.`designation`) desig
                                                            ,(select name from department  where ID= ha.`postingdepartment`) dept
                                                            ,DATE_FORMAT(l.applieddate,'%d/%c/%Y') applydt, lt.title,DATEDIFF(l.endday,l.startday)+1 days,DATE_FORMAT(l.startday,'%d/%c/%Y') startday,DATE_FORMAT(l.endday,'%d/%c/%Y') endday,h.hrName approver
                                                            ,DATE_FORMAT(l.approvedate,'%d/%c/%Y') approvedate
                                                            ,l.details, l.approvercoments, l.approveraction
                                                            FROM  `leave` l, leaveType lt,hr h ,hr h1,hraction ha ,employee e
                                                            where l.leavetype=lt.id 
                                                            and l.approver=h.id
                                                            and l.hrid=h1.id
                                                            and h1.emp_id=e.employeecode
                                                            and e.id=ha.hrid
                                                            and l.applieddate BETWEEN DATE_SUB(sysdate(), INTERVAL 1 YEAR) and  sysdate()
                                                            and e.id = ".$aid."
                                                            ORDER BY applydt DESC"; 
                                                 $resultleaveap = $conn->query($qryleaveap); 
                                                //echo $qrydoc;die;
                                                while($rowleaveap = $resultleaveap->fetch_assoc()) {
                                                    
                                                    if($rowleaveap["approveraction"] == ""){
                                                        $leaveapst = "Pending";
                                                    }else if($rowleaveap["approveraction"] == 1){
                                                        $leaveapst = "Accepted";
                                                    }else{
                                                        $leaveapst = "Declined";
                                                    }
                                        
                                        ?>
                                                <tbody class="table-row">
                                                    <tr>
                                                        <td><?= $rowleaveap["title"] ?></td>
                                                         <td><?= $rowleaveap["applydt"] ?></td>
                                                        <td><?= $rowleaveap["startday"] ?></td>
                                                         <td><?= $rowleaveap["endday"] ?></td>
                                                          <td><?= $rowleaveap["days"] ?></td>
                                                        <td><?= $rowleaveap["details"] ?></td>
                                                        <td><?= $rowleaveap["approver"] ?></td>
                                                        <td><?= $leaveapst ?></td>
                                                        <td><?= $rowleaveap["approvercoments"] ?></td>
                                                    </tr>
                                                    </tbody>
                                        <?php } ?>
                                                    
                                                </table>
                                                
                                                <!--li class="add-btn6 li-none">+Add</li-->
                                                
                                                <div id="my-modal7" class="modal emod6">
                             
                              <!-- Modal content -->
                               <div class="modal-content">
                                <div class="modal-header">
                                  <div class="close eclose6">×</div>
                             
                                </div>
                                <div class="modal-body">
                                        
                                        <div class="row">
                                     
                                <div class="col-lg-4 col-md-6 col-sm-6">
                        	        <label for="">Effective Date</label>
                                  <div class="input-group">
                                    <input type="text" class="form-control datepicker">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                  </div>     
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                  <div class="form-group">
                                    <label for="">Leave Type</label>
                        			  <div class="form-group styled-select">
                        				  <select name="select1" id="select1" class="form-control">
                        					<option value="1">Casual</option>
                        					<option value="2">Medical</option>
                        					<option value="3">Annual</option>
                        				  </select>
                        			  </div>
                                  </div>        
                                </div>
                                                             
                                   <!--  <div class="col-4 mod-col">
                                         <label for="ben" class="mod-lab">Leave Type</label>
                              <select name="ben" class="modal2-field modal-field" >
                             
                             
                                <option value="">Casual</option>
                                <option value="">Medical</option>
                                <option value="">Annual</option>
                              </select>
                                     </div> -->
                                    
                                      <div class="col-lg-4 col-md-6 col-sm-6">
                            	        <label for="">Start Date</label>
                                      <div class="input-group">
                                        <input type="text" class="form-control datepicker">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                      </div>     
                                    </div>
                             
                                      <!-- <div class="col-4 mod-col">
                                         <label  class="mod-lab">Days</label>
                                         <input type="number" class="modal2-field ef-date">
                                     </div>  -->
                                     <!--<div class="col-4 mod-col">
                                         <label class="mod-lab"> Start Date</label>
                                         <input type="date" class="modal2-field ef-date">
                                     </div>
                                     <div class="col-4 mod-col">
                                         <label  class="mod-lab">End Date</label>
                                         <input type="date" class="modal2-field ef-date">
                                     </div> -->
                                      <div class="col-lg-4 col-md-6 col-sm-6">
                            	        <label for="">End Date</label>
                                      <div class="input-group">
                                        <input type="text" class="form-control datepicker">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                      </div>     
                                    </div>
                                    <!-- <div class="col-4 mod-col">
                                         <label for="ben" class="mod-lab ">Approved By</label>
                              <select name="ben" class="modal2-field modal-field" >
                             
                             
                                <option value="">Adam</option>
                                <option value="">Fran</option>
                              </select>
                                     </div> -->
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                  <div class="form-group">
                                    <label for="">Approved By</label>
                        			  <div class="form-group styled-select">
                        				  <select name="select1" id="select1" class="form-control">
                        					<option value="1">Adam</option>
                        					<option value="2">Sylvia</option>
                        					<option value="3">Anderson</option>
                        				  </select>
                        			  </div>
                                  </div>        
                                </div>
                             
                             
                                 </div>
                                  <div class="row">
                                       <h6 class="mod-lab">Details</h6> 
                                    <textarea class="st-area"  rows="3">  </textarea>
                                    </div>
                                    <div class="row">
                                        <button type="button" class="mod-btn">Submit</button>
                                        
                                    </div>
                             
                                </div>
                             
                              </div>
                              </div>
