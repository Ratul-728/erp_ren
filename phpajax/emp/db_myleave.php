<?php
require "../../common/conn.php";
include_once('../../rak_framework/fetch.php');
include_once("../../rak_framework/listgrabber.php");
session_start();
$usr=$_SESSION["user"];
$aid= $_GET['id'];

?>
<style>
table {
    width: 100%;
}

.table-leave {
    max-height: 400px; 
    overflow-x: auto;
    overflow-y: auto;
}
</style>

       <h5 class="table-status-title">My Leaves</h5>                 
<div class = "table-leave">
    <link rel="stylesheet" href="/js/plugins/datagrid/datatables.css">
<table id="listTable" width="100%" class="display actionbtn no-footer dataTable">
  <thead class="thead-blue">
    <tr>
      <th scope="col">Leave Type</th>
      <th scope="col">Applied Date</th>
      <th scope="col">Start Date</th>
      <th scope="col">End Date</th>
      <th scope="col">Total Days</th>
      <th scope="col">Documents</th>
      <th scope="col">Details</th>
      <th scope="col">Reliver</th>
      <th scope="col">Reliver Action</th>
      <th scope="col">Reliver Comments</th>
      <th scope="col">Reliver Date</th>
      <th scope="col">Approver</th>
      <th scope="col">Approver Action</th>
      <th scope="col">Approver Comment</th>
      <th scope="col">Approver Date</th>
      <th scope="col">HR Action</th>
      <th scope="col">HR Comment</th>
      <th scope="col">HR Action Date</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
<?php   $qryal = "SELECT lt.`title` leavetype, l.`applieddate`, l.`startday`, l.`endday`, l.`details`, concat(emp1.firstname, ' ', emp1.lastname) relivername, 
                     concat(emp2.firstname, ' ', emp2.lastname) approvername, l.`approveraction`, l.`approvercoments`,DATEDIFF(l.`endday`, l.`startday`) AS DateDiff,
                     l.reliveraction, l.relivercomments, l.`hrdaction`, l.`hrdcomments`, l.`hrdactiondate`, l.`st`, l.releveddate, l.approvedate, l.id leaveid

                    FROM `leave` l LEFT JOIN `leaveType` lt ON l.`leavetype` = lt.`id` 
                    LEFT JOIN `hr` hr1 ON l.`reliver` = hr1.`id` LEFT JOIN employee emp1 ON emp1.employeecode=hr1.emp_id
                    LEFT JOIN `hr` hr2 ON hr2.`id` = l.`approver` LEFT JOIN employee emp2 ON emp2.employeecode=hr2.emp_id
                                        
                    WHERE l.`hrid` = ".$usr." order by l.id desc";
                                        
        $resultal = $conn->query($qryal);
        while($rowal = $resultal->fetch_assoc()){
            
            if($rowal["reliveraction"] == ''){
                $reliaction = "Waiting for reliver approval";
            }else if($rowal["reliveraction"] == '1'){
                $reliaction = "Approved";
            }else{
                $reliaction = "Declined";
            }
            $relicomments = $rowal["relivercomments"];
            $releveddate = $rowal["releveddate"];
        
        if($rowal["approveraction"] == ''){
            $appact = "Waiting for department head approval";
        }                    
        else if($rowal["approveraction"] == 0){
            $appact = "Declined";
        }else if ($rowal["approveraction"] == 1){
            $appact = "Approved";
        }
        
        $appcomments = $rowal["approvercoments"];
        $appdt = $rowal["approvedate"];
        
        if($rowal["hrdaction"] == ''){
            $hract = "Waiting for HR head approval";
        }                    
        else if($rowal["hrdaction"] == 0){
            $hract = "Declined";
        }else if ($rowal["hrdaction"] == 1){
            $hract = "Approved";
        }
        
        $hrcomments = $rowal["hrdcomments"];
        $hrdt = $rowal["hrdactiondate"];
        
        if($rowal["st"] == 0){
            $st = "Declined";
        } else if($rowal["st"] == 1){
            $st = $reliaction;
        } else if($rowal["st"] == 2){
            $st = $appact;
        } else if($rowal["st"] == 3){
            $st = $hract;
        } else if($rowal["st"] == 4){
            $st = "Approved";
        } 
        
        
        $tot_days = $rowal["DateDiff"];
                                
        if($tot_days < 0) $tot_days *= (-1);
                                
        $tot_days++;
        
        $leaveid = $rowal["leaveid"];
        
                $inputDefImgData = array(

            	'TableName' => 'leave_documents',
            	'OrderBy' => 'id',
            	'ASDSOrder' => 'DESC',
            	'id' => '',
            	'image' => '',
            	'leaveid' => $leaveid
            	);
            	
            	listData($inputDefImgData,$imgDefData);
            	
            	if(count($imgDefData)>0){
                	$picturelist[$leaveid] ='<div class="ajax-img-up"><ul class="d-flex defect-img">';
                    foreach($imgDefData as $lidata){
                        $picturelist[$leaveid] .= '<li class="picbox"><a class="picture-preview"  href="../../common/upload/leave_documents/'.$lidata['image'].'""><img src="../../common/upload/leave_documents/default_thumb.jpg"></a></li>';
                    }
                    $picturelist[$leaveid] .='</ul></div>';
            	}
            	$inputDefImgData = "";
            	$imgDefData = "";
                                        
?>
    <tr>
      <th scope="row"><?= $rowal["leavetype"] ?></th>
      <td><?= substr($rowal["applieddate"], 0, 10) ?></td>
      <td><?= $rowal["startday"] ?></td>
      <td><?= $rowal["endday"] ?></td>
      <th><?= $tot_days ?></th>
      <td><?= $rowal["details"] ?></td>
      <td><?= $picturelist[$leaveid] ?></td>
      <td><?= $rowal["relivername"] ?></td>
      <td><?= $reliaction ?></td>
      <td><?= $relicomments ?></td>
      <td><?= $releveddate ?></td>
      <td><?= $rowal["approvername"] ?></td>
      <td><?= $appact ?></td>
      <td><?= $appcomments ?></td>
      <td><?= $appdt ?></td>
      <td><?= $hract ?></td>
      <td><?= $hrcomments ?></td>
      <td><?= $hrdt ?></td>
      
      <td><?= $st ?></td>

    </tr>
    
<?php } ?>
    
  </tbody>
</table>
</div>
                    
