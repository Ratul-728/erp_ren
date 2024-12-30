<?php
require "../../common/conn.php";
session_start();
$usr=$_SESSION["user"];
$aid= $_GET['id'];

$qryInfo = "SELECT h.attendance_id FROM `employee` emp LEFT JOIN hr h ON h.emp_id=emp.employeecode WHERE emp.id=".$aid;
$resultInfo = $conn->query($qryInfo); 
while($rowInfo = $resultInfo->fetch_assoc()) {
    $emp_att_id = $rowInfo["attendance_id"];
}
    $attendance_from_string = $_GET["fd"];
    if($attendance_from_string != ''){
        // Create a DateTime object from the given date string
        $attendance_from = DateTime::createFromFormat('d/m/Y', $attendance_from_string);
        
        // Format the date as yyyy-mm-dd
        $attendance_from = $attendance_from->format('Y-m-d');
    }else{
        $attendance_from = date('Y-m-1');
    }
    
    $attendance_to_string   = $_GET["td"];
    
    if($attendance_to_string != ''){
        // Create a DateTime object from the given date string
        $attendance_to = DateTime::createFromFormat('d/m/Y', $attendance_to_string);
        
        // Format the date as yyyy-mm-dd
        $attendance_to = $attendance_to->format('Y-m-d');
    }else{
        $attendance_to = date('Y-m-d');
    }
?>
                                                 <h5 class="table-status-title">Attendance</h5>
                                                 <div class="col-sm-9 text-nowrap">
                                                     <div class="pull-right grid-panel form-inline">
                                                         <div class="form-group">
                                                                    <input type="text" class="form-control datepicker_history_filter datepicker" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt;?>" >
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control datepicker_history_filter datepicker" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt;?>"  >
                                                                </div>
                                                        </div>
                                                </div>
                                                <table class="table table-hover em-table" border="0">
                                                 <thead>
                            
                                                    <tr>
                                                        <th scope="col">SL</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Day</th>
                                                        <th scope="col">Work Shift</th>
                                                        <th scope="col">Start Time</th>
                                                        <th scope="col">End Time</th>
                                                        <th scope="col">In Time</th>
                                                        <th scope="col">Out Time</th>
                                                        <th scope="col">Working Hours</th>
                                                        <th scope="col">Late Minutes</th>
                                                        <th class="col">Early Out Minute</th>
                                                        <th class="col">Status</th>
                                                        <th class="col">Remarks</th>
                            
                                                    </tr>
                                                    
                                                    </thead>
                            
                                                
                                                <?php 
                                                    $qryatt = "SELECT  hr.attendance_id hrid,hr.emp_id ,hr.hrName,c.day,c.daynm
                                                            ,st.title shift,st.starttime,st.exittime
                                                            ,u.intime,u.outtime
                                                            ,(case when  u.intime>st.starttime then TIME_FORMAT(TIMEDIFF(u.intime,st.starttime), '%H:%i:%s') else 0 end)latetime
                                                            ,(case when  u.outtime<st.exittime then TIME_FORMAT(TIMEDIFF(st.exittime,u.outtime), '%H:%i:%s') else 0 end)earlytime
                                                            ,TIME_FORMAT(TIMEDIFF(u.outtime,u.intime), '%H:%i:%s') worktime
                                                            ,(case when st.id=6 then st.title else (case when u.date is null then (case when (SELECT count(hrid) hrd FROM `leave` where c.day between startday and endday and hrid=u.hrid)>0 then 'Leave' else (case when c.daytp='W' then 'Week Day' else  'Absent' end) end) else u.attendance_type end) end) stats
                                                            ,u.early_leave,u.Details
                                                            from hr ,calander c left join
                                                            (
                                                            select h.date,'' hrid, '' intime,'' outtime,'Holiday' attendance_type,'0' early_leave,Details
                                                            from  Holiday h  
                                                            where h.date BETWEEN '$attendance_from' and '$attendance_to'
                                                            union all
                                                            select a.date,a.hrid,a.intime,a.outtime
                                                            ,(case  a.attendance_type  when 0 then 'Absent' when 1 then 'Present' when 2 then 'Delay' else 'N' end) attendance_type
                                                            ,a.early_leave ,'' Details
                                                            from attendance_test a where a.hrid=$emp_att_id
                                                            and a.date BETWEEN '$attendance_from' and '$attendance_to'
                                                            )u on c.day=u.date 
                                                            left join Shifting st on st.id=nvl((SELECT s.shift FROM assignshifthist s  where s.st=1 and s.empid=(select e.id from employee e  where e.employeecode=(select h.`emp_id` from hr h where h.attendance_id=$emp_att_id)) and s.effectivedt=c.day),3)
                                                            where hr.attendance_id =$emp_att_id and c.day BETWEEN '$attendance_from' and '$attendance_to'  
                                                            ORDER BY `c`.`day` ASC ";
                                                                //echo $qryatt;die;
                                                    $resultatt = $conn->query($qryatt); 
                                                    $sl = 0;
                                                    while($rowatt = $resultatt->fetch_assoc()) { 
                                                       $sl++;
                                                       
                                                ?>
                                                    <tr class="table-row">
                                                        <td><?= $sl ?></td>
                                                        <td><?= $rowatt["day"] ?></td>
                                                        <td><?= $rowatt["daynm"] ?></td>
                                                         <td><?= $rowatt["shift"] ?></td>
                                                       	 <td><?= $rowatt["starttime"] ?></td>
                                                         <td><?= $rowatt["exittime"] ?></td>
                                                         <td><?= $rowatt["intime"] ?></td>
                                                         <td><?= $rowatt["outtime"] ?></td>
                                                         <td><?= $rowatt["worktime"] ?></td>
                                                         <td><?= $rowatt["latetime"] ?></td>
                                                         <td><?= $rowatt["earlytime"] ?></td>
                                                         <td><?= $rowatt["stats"] ?></td>
                                                         <td><?= $rowatt["Details"] ?></td>
                                                    </tr>
                                                <?php } ?>
                            
                                                   
                                                </table>
                                                
                                                 <!-- Add button>
                                                <button type="button" i d="my-btn" class="add-btn5" >+Add</button-->
                                                 <!-- Attendance Modal-->
                                              <div id="my-modalat" class="modal emod5">
                                             
                                              <!-- Modal content -->
                                               <div class="modal-content">
                                                <div class="modal-header">
                                                  <div class="close eclose5">×</div>
                                             
                                                </div>
                                                <div class="modal-body">
                                                        
                                                        <div class="row">
                                                     
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
                                                         <label for="ef-date" class="mod-lab"> Office Time </label>
                                                         <input type="text" class="modal2-field ">
                                                     </div>  -->
                                                      <div class="col-lg-3 col-md-6 col-sm-6">
                                                      <div class="form-group">
                                                        <label for="">Office time</label>
                                                        <input type="text" class="form-control" >
                                                      </div>        
                                                    </div>
                                                     
                                                     
                                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Shift Type</label>
                                                            <div class="form-group styled-select">
                                                                <select name="select1" id="select1" class="form-control">
                                                                    <option value="">Casual</option>
                                                                    <option value="">Medical</option>
                                                                    <option value="">Annual</option>
                                                                </select>
                                                            </div>
                                                        </div>        
                                                    </div>
                                               <!--<div class="col-3 mod-col">
                                                         <label for="ben" class="mod-lab">Shift Type</label>
                                              <select name="ben" class="modal2-field modal-field" >
                                             
                                             
                                                <option value="">Casual</option>
                                                <option value="">Medical</option>
                                                <option value="">Annual</option>
                                              </select>
                                                </div>   -->
                                                <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Status</label>
                                                            <div class="form-group styled-select">
                                                                <select name="select1" id="select1" class="form-control">
                                                                    <option value="">Absent</option>
                                                                    <option value="">Present</option>
                                                                </select>
                                                            </div>
                                                        </div>        
                                                    </div>
                                                    <!-- <div class="col-3 mod-col">
                                                         <label for="ben" class="mod-lab">Status</label>
                                              <select name="ben" class="modal2-field modal-field" >
                                             
                                             
                                                <option value="">Absent</option>
                                                <option value="">Present</option>
                                               
                                              </select>
                                                     </div>   -->
                                                      <!--  <div class="col-3 mod-col">
                                                         <label for="ef-date" class="mod-lab"> Entry Time </label>
                                                         <input type="text" class="modal2-field ">
                                                     </div>  -->
                                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                                      <div class="form-group">
                                                        <label for="">Entry time</label>
                                                        <input type="text" class="form-control" >
                                                      </div>        
                                                    </div>
                                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                                      <div class="form-group">
                                                        <label for="">Exit time</label>
                                                        <input type="text" class="form-control" >
                                                      </div>        
                                                    </div>
                                                     <div class="col-lg-3 col-md-6 col-sm-6">
                                                      <div class="form-group">
                                                        <label for="">Total hours</label>
                                                        <input type="text" class="form-control" >
                                                      </div>        
                                                    </div>
                                                      <!--<div class="col-3 mod-col">
                                                         <label for="ef-date" class="mod-lab">Exit Time </label>
                                                         <input type="text" class="modal2-field ">
                                                     </div>
                                                      <div class="col-3 mod-col">
                                                         <label for="ef-date" class="mod-lab">Total Hours </label>
                                                         <input type="number" class="modal2-field ">
                                                     </div> -->
                                                    
                                             
                                                     
                                             
                                             
                                                 </div>
                                                 <div class="row">
                                                       <h6 class="mod-lab">Special Remarks</h6> 
                                                    <textarea class="st-area"  rows="3">  </textarea>
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
<script>
    $("#filter_date_from, #filter_date_to").change(function () {
            var dtfrom = $( "#filter_date_from" ).val();
            var dtto = $( "#filter_date_to" ).val();
            $(".atten-block").html("Loading...");
			$(".atten-block").load("phpajax/emp/attendance.php?id=<?=$aid?>&fd="+dtfrom+"&td="+dtto);
           
    });
</script>
