<?php
 require_once("../common/conn.php");
 session_start();
 
 $usr=$_SESSION["user"];
 $id = $_GET["id"];
 $st = $_GET["st"];
 
 $qryGet = "SELECT * FROM `approval_salary` WHERE id = ".$id;
 $resultGet = $conn->query($qryGet);
 while($rowGet = $resultGet->fetch_assoc()){
     $month = $rowGet["month"];
     $year = $rowGet["year"];
     $fist_approval = $rowGet["approved_by"]; 
 }
 
 if($fist_approval == ""){
     if($st == 0){
         $qryUpdateAp = "UPDATE `approval_salary` SET `approved_by`='$usr',`approved_date`=sysdate(), 1st_action_st = '$st',st = 2 WHERE id = ". $id;
         if($conn->query($qryUpdateAp) == true){
             
            $err = "Successfully declined";
            header("Location: ".$hostpath."/approval_salary.php?res=1&msg=".$err."&id=".$id."&mod=24");
            die;
        
         };
     }else{
        $qryUpdateAp = "UPDATE `approval_salary` SET `approved_by`='$usr',`approved_date`=sysdate(), 1st_action_st = '$st' WHERE id = ". $id;
         if($conn->query($qryUpdateAp) == true){
             
            $err = "Successfully First Person Approved";
            header("Location: ".$hostpath."/approval_salary.php?res=1&msg=".$err."&id=".$id."&mod=24");
            die;
         }; 
     }
     
 }else{
     if($st == 0){
         $qryUpdateAp = "UPDATE `approval_salary` SET `2nd_approval`='$usr',`2nd_approvaldt`=sysdate(), 2nd_action_st = '$st', st = 2 WHERE id = ". $id;
         if($conn->query($qryUpdateAp) == true){
             
            $err = "Successfully declined";
            header("Location: ".$hostpath."/approval_salary.php?res=1&msg=".$err."&id=".$id."&mod=24");
            die;
        
         };
     }else{
         
        $qryUpdateAp = "UPDATE `approval_salary` SET `2nd_approval`='$usr',`2nd_approvaldt`=sysdate(), 2nd_action_st = '$st',st = 1 WHERE id = ". $id;
         if($conn->query($qryUpdateAp) == true){
             
            $updateQry = "UPDATE `monthlysalary` SET `approvest`='1' WHERE salaryyear = '$year' AND salarymonth = '$month'";
            $conn->query($updateQry);
            
            $err = "Successfully Second Person Approved";
            header("Location: ".$hostpath."/approval_salary.php?res=1&msg=".$err."&id=".$id."&mod=24");
            die;
        
         }; 
     }
     die;
 }
 
 
// $updateQry = "UPDATE `monthlysalary` SET `approvest`='1' WHERE salaryyear = '$year' AND salarymonth = '$month'";
 
// if($conn->query($updateQry) == true){
     
//      $qryUpdateAp = "UPDATE `approval_salary` SET `st` = '1', `approved_by`='$usr',`approved_date`=sysdate() WHERE id = ". $id;
//      if($conn->query($qryUpdateAp) == true){
         
//         $err = "Successfully approved";
//         header("Location: ".$hostpath."/approval_salary.php?res=1&msg=".$err."&id=".$id."&mod=24");
    
//      }else{
//         $err = "Something went wrong";
//         header("Location: ".$hostpath."/approval_salary.php?res=2&msg=".$err."&id=".$id."&mod=24");
//      }
     
     
// }else{
//     $err = "Something went wrong";
//     header("Location: ".$hostpath."/approval_salary.php?res=2&msg=".$err."&id=".$id."&mod=24");
// }
 
 ?>