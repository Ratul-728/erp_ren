<?php
require "../../common/conn.php";
include_once('../../rak_framework/fetch.php');
include_once("../../rak_framework/listgrabber.php");

session_start();
$usr=$_SESSION["user"];
$aid= $_GET['id'];

?>
<link rel="stylesheet" href="/js/plugins/datagrid/datatables.css">

<style>
table {
    width: 100%;
}

.table-leave {
    max-height: 400px; 
    overflow-x: auto;
    overflow-y: auto;
}

.ajax-img-up{
            border: 0px solid #000!important;
            display: flex;
            text-align: left;
        }
        .ajax-img-up ul{
          margin-bottom: 0;
          margin-left: 0!important;
            padding-left: 0px;
        }
        
        .ajax-img-up li{
          display: block;
          width: 40px;
          height: 40px;
          border: 1px solid #888787;
          position: relative;
          margin: 3px;
          border-radius: 0px;
          border-radius: 5px;
        }
        
        
        .ajax-img-up li img{
          width: 100%;
          height: 100%;
          border-radius: 5px;
        }
        
        
</style>

   <div class="row table-leave">
                                        <br>
                        
                                     <h5 class="table-status-title">Leave</h5>
                    <table id="listTable" width="100%" class="display actionbtn no-footer dataTable">
  <thead class="thead-blue">
    <tr>
      <th scope="col">Leave Type</th>
      <th scope="col">Applied By</th>
      <th scope="col">Applied Date</th>
      <th scope="col">Start Date</th>
      <th scope="col">End Date</th>
      <th scope="col">Total Days</th>
      <th scope="col">Documents</th>
      <th scope="col">Details</th>
      <th scope="col">Reliver</th>
      <th scope="col">Reliver Comment</th>
      <th scope="col">Approver</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
<?php   $qryleave = "SELECT l.id, lt.`title` leavetype, l.`applieddate`, l.`startday`, l.`endday`, l.`details`, concat(emp1.firstname, ' ', emp1.lastname) relivername,
                        concat(emp2.firstname, ' ', emp2.lastname) approvername, 
                        l.`approveraction`, l.`approvercoments`,DATEDIFF(l.`endday`, l.`startday`) AS DateDiff, concat(emp3.firstname, ' ', emp3.lastname) applyfl, l.`relivercomments`, l.st,
                        l.reliver, l.approver

                    FROM `leave` l LEFT JOIN `leaveType` lt ON l.`leavetype` = lt.`id` 
                    LEFT JOIN `hr` hr1 ON l.`reliver` = hr1.`id` LEFT JOIN employee emp1 ON emp1.employeecode=hr1.emp_id
                    LEFT JOIN `hr` hr2 ON hr2.`id` = l.`approver`  LEFT JOIN employee emp2 ON emp2.employeecode=hr2.emp_id
                    LEFT JOIN `hr` hr3 ON hr3.id = l.`hrid` LEFT JOIN employee emp3 ON emp3.employeecode=hr3.emp_id
                                        
                    WHERE l.approveraction is null and (l.`approver` = ".$usr." or l.`reliver` = ".$usr.") and (l.st = 1 or (l.st = 2 and l.`approver` = ".$usr.")) order by l.id desc";
        //echo $qryleave;die;
                                        
        $resultleave = $conn->query($qryleave);
        while($rowleave = $resultleave->fetch_assoc()){
        $st = $rowleave["st"]; $relv = $rowleave["reliver"]; $appro = $rowleave["approver"];
        
        if(($st == 1 && $relv == $usr) || ($st == 2 && $appro == $usr)){
            $youraction = '<button type="button" class="mod-add-btn lev-acc" data-proid="'.$rowleave["id"].'">Yes/No</button>';
        }
        else if($st == 1 && $appro == $usr){
            $youraction = "Waiting for reliver action";
        } 
        
                                
        $total_days = $rowleave["DateDiff"];
                                
        if($total_days < 0) $total_days *= (-1);
                                
        $total_days++;
        
        $leaveid = $rowleave["id"];
        
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
      <th scope="row"><?= $rowleave["leavetype"] ?></th>
      <td><?= $rowleave["applyfl"] ?></td>
      <td><?= substr($rowleave["applieddate"], 0, 10) ?></td>
      <td><?= $rowleave["startday"] ?></td>
      <td><?= $rowleave["endday"] ?></td>
      <th><?= $total_days ?></th>
      <th><?= $picturelist[$leaveid] ?></th>
      <td><?= $rowleave["details"] ?></td>
      <td><?= $rowleave["relivername"]?></td>
      <td><?= $rowleave["relivercomments"] ?></td>
      <td><?= $rowleave["approvername"] ?> </td>
      <th><?= $youraction ?></th>
      
    </tr>
    
<?php } ?>
    
  </tbody>
</table>

                  
                                    </div>
<script>
    $(".lev-acc").click(function(){
    var span = document.getElementsByClassName("close");
    actleaveid = $(this).data('proid');
    modal[1].style.display = "block";
     span[1].onclick = function() {
    modal[1].style.display = "none";
}
    //alert("g");
});
</script>