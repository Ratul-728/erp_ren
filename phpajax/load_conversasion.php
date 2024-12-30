<?php

if($_REQUEST['action'] == 'loadconversasion'){


extract($_REQUEST);
require "../common/conn.php";
//$contid=1;// contact id
//$fv='1,2,3';// checkbox value of filter box
//$fdt='01/05/2019';// start date form filter box
//$tdt='10/10/2019';// end date form filter box
//print_r($_REQUEST);
//exit();
?>

<?php 
//if ($fv==''){$fv='1,2,3,4,5,6,7,8';}
 
 $msgctqry="SELECT d.`id`,t.`name` `comntp`,DATE_FORMAT(d.`comndt`,'%e/%c/%Y %h:%i:%s %p') comndt, d.`note`, d.`place`, d.`status`, d.`value`, d.`makeby`,h.`resourse_id` FROM `comncdetails` d,`comnctype` t,`hr` h WHERE d.`comntp`=t.`id` and d.`makeby`=h.`id`  and d.contactid =".$contid." and ( FIND_IN_SET(d.`comntp`,'".$fv."') or '".$fv."'='' ) and d.`comndt` between STR_TO_DATE('".$fdt."', '%d/%m/%Y') and STR_TO_DATE('".$tdt."', '%d/%m/%Y')  order by d.`comndt` desc";
// echo $abc;
//echo $msgctqry; die;
 $resultmsg = $conn->query($msgctqry); 
            if ($resultmsg->num_rows > 0){
				
                while($rowmsg = $resultmsg->fetch_assoc()) 
                    { 
                        $mid=$rowmsg["id"];
						$comntp=$rowmsg["comntp"]; 
						$comndt=$rowmsg["comndt"];  
						$note=$rowmsg["note"];
						$place=$rowmsg["place"];
                        $status=$rowmsg["status"]; 
						$value=$rowmsg["value"];
						$photo="common/upload/hc/".$rowmsg["emp_id"].".jpg";//images/profile_picture/profile.jpg
?>
   
                    

                    <div class="panel panel-default <?=$comntp?>">
                        <div class="panel-heading"><i class="icon-contact icon-<?=strtolower($comntp)?>"></i><?=$comntp?> : <span><?=$comndt?></span><img src="<?php echo $photo; ?>"  class="profile-picture"></div>
                        <div class="panel-body"><?=$note?></div>
                    </div>
                    
                    
                    
<?php 
				}
		}else{
					echo '<div class="panel panel-default">No data found</div>';
					
					}
}
if($_REQUEST['action'] == 'loadconversasion_org'){


extract($_REQUEST);

require "../common/conn.php";
//$contid=1;// contact id
//$fv='1,2,3';// checkbox value of filter box
//$fdt='01/05/2019';// start date form filter box
//$tdt='10/10/2019';// end date form filter box
//print_r($_REQUEST);
//exit();
?>

<?php 
if ($fv==''){$fv='1,2,3,4,5,6,7,8';}

 /*$msgctqry="SELECT c.name,d.`id`,t.`name` `comntp`,DATE_FORMAT(d.`comndt`,'%e/%c/%Y %h:%i:%s %p') comndt, d.`note`, d.`place`, d.`status`, d.`value`, d.`makeby`,h.`resourse_id`, h.emp_id FROM `comncdetails` d left join comnctype t 
on d.`comntp`=t.`id`  left join contact c on d.contactid=c.id left join organization org on org.orgcode=c.organization left join `hr` h on d.`makeby`=h.`id`
WHERE org.id =".$orgid."  and d.`comndt` between STR_TO_DATE('".$fdt."', '%d/%m/%Y') and STR_TO_DATE('".$tdt."', '%d/%m/%Y')  order by d.`comndt` desc LIMIT 100";
*/
$msgctqry="SELECT c.name,d.`id`,t.`name` `comntp`,DATE_FORMAT(d.`comndt`,'%e/%c/%Y %h:%i:%s %p') comndt, d.`note`, d.`place`, d.`status`, d.`value`, d.`makeby`,h.`resourse_id`, h.emp_id, emp.photo FROM `comncdetails` d left join comnctype t 
on d.`comntp`=t.`id`   left join organization org on org.id=d.`contactid` left join contact c on org.orgcode=c.organization left join `hr` h on d.`makeby`=h.`id` left join employee emp on emp.employeecode = h.emp_id
WHERE org.id =".$orgid."  and d.comntp in($fv) and d.`comndt` order by d.`comndt` desc LIMIT 100";
 //$msgctqry="SELECT d.`id`,t.`name` `comntp`,DATE_FORMAT(d.`comndt`,'%e/%c/%Y %h:%i:%s %p') comndt, d.`note`, d.`place`, d.`status`, d.`value`, d.`makeby`,h.`resourse_id` FROM `comncdetails` d,`comnctype` t,`hr` h WHERE d.`comntp`=t.`id` and d.`makeby`=h.`id`  and d.contactid =".$contid." and ( FIND_IN_SET(d.`comntp`,'".$fv."') or '".$fv."'='' ) and d.`comndt` between STR_TO_DATE('".$fdt."', '%d/%m/%Y') and STR_TO_DATE('".$tdt."', '%d/%m/%Y')  order by d.`comndt` desc";
// echo $abc;
//echo $msgctqry; die;
 $resultmsg = $conn->query($msgctqry); 
            if ($resultmsg->num_rows > 0){
				
                while($rowmsg = $resultmsg->fetch_assoc()) 
                    { 
                        $mid=$rowmsg["id"];
                        $cnm=$rowmsg["name"];
						$comntp=$rowmsg["comntp"]; 
						$comndt=" with ".$cnm." at ".$rowmsg["comndt"];
						$note=$rowmsg["note"];
						$place=$rowmsg["place"];
                        $status=$rowmsg["status"]; 
						$value=$rowmsg["value"];
						if($rowmsg["emp_id"] == "A0001"){
						    $photo="common/upload/hc/".$rowmsg["emp_id"].".jpg";//images/profile_picture/profile.jpg
						}else{
						    $photo="common/upload/hc/".$rowmsg["photo"];//images/profile_picture/profile.jpg
						}
						
?>
                <form method="post" action = "../BitFlow/common/delobj.php?obj=comncdetails&id=<?= $mid ?>&ret=contactDetail_org&mod=2&orgid=<?= $orgid ?>" id = "frmsub-<?= $mid ?>">
                    <div class="panel panel-default <?=$comntp?>">
                        <div class="panel-heading"><i class="icon-contact icon-<?=strtolower($comntp)?>"></i><?=$comntp?> : <span><?=$comndt?></span><img src="<?php echo $photo; ?>"  class="profile-picture"></div>
                        <div class="panel-body" id = "msg-<?= $mid ?>"><?=$note?> 
                             <button type = "button" style="float:right;" class="btn  btn-primary btncussub" id ="btnsub-<?= $mid ?>"> <i class="fa fa-trash"></i> </button>
                            
                        </div>
                       
                    </div>
                </form>
                    <script>
  
                        $("#btnsub-<?= $mid ?>").click(function(){  
                            //$("#frmsub-<?= $mid ?>").submit();
                            //var jsmid = "#frmsub-"+"<?= $mid ?>";
                            
                            //confirmationDelete($(this));return false;
                            swal({
                               title: "Are you sure?",
                              text: "You will not be able to recover this imaginary file!",
                              icon: "warning",
                              buttons: [
                                'No, cancel it!',
                                'Yes, I am sure!'
                              ],
                              
                            }).then(function(isConfirm) {
                            if (isConfirm) {
                                
                                 swal("Deleted", "Successfully deleted!", "success");
                                    setInterval(function(){ $("#frmsub-<?= $mid ?>").submit(); }, 2000);
                              
                              } else {
                                swal("Cancelled", "Product isn't deleted!", "error");
                              }
                            });
                        });
                        
                        function confirmationDelete(anchor)
                        {
                            
                            swal({
                               title: "Are you sure?",
                              text: "You will not be able to recover this imaginary file!",
                              icon: "warning",
                              buttons: [
                                'No, cancel it!',
                                'Yes, I am sure!'
                              ],
                              
                            }).then(function(isConfirm) {
                            if (isConfirm) {
                                
                                 swal("Deleted", "Successfully deleted!", "success");
                                    setInterval(function(){ $("#frmsub-<?= $mid ?>").submit(); }, 3000);
                              
                              } else {
                                swal("Cancelled", "Product isn't deleted!", "error");
                              }
                            });
                          
                        }
                     
                    </script>
<?php 
				}
		}else{
					echo '<div class="panel panel-default">No data found</div>';
					
					}
}

 ?> 


                                            
                                            
                                            
                                            
                                            
                                            