<?php

session_start();
require "./conn.php";
$usr=$_SESSION["user"];

extract($_POST);
//print_r($_POST);die;

$errFlag= 0;

//Delete All
$qryDelete = "DELETE FROM `email_to_cc`";
$conn->query($qryDelete);

if (is_array($menuid)){
    for ($i=0;$i<count($menuid);$i++){
        $menu = $menuid[$i];
        $toss = $tos[$menu]; $ccss = $ccs[$menu];
        //print_r($toss);continue;
        //To
        if (is_array($toss)){
            for ($j=0;$j<count($toss);$j++){
                $to = $toss[$j];
                
                $qryto = "INSERT INTO `email_to_cc`(`emailid`, `type`, `employee`) 
                                VALUES ('".$menuid[$i]."','1','".$to."')";
                if($conn->query($qryto) == false){
                    $errFlag ++;
                }
            }
        }
        //CC
        if (is_array($ccss)){
            for ($j=0;$j<count($ccss);$j++){
                $cc = $ccss[$j];
                
                $qrycc = "INSERT INTO `email_to_cc`(`emailid`, `type`, `employee`) 
                                VALUES ('".$menuid[$i]."','2','".$cc."')";
                if($conn->query($qrycc) == false){
                    $errFlag ++;
                }
            }
        }
        if($active[$menu][0] == 1){
            $st = 1;
        }else{
            $st = 0;
        }
        
        $qryupdateactive = "UPDATE `email` SET `active`='$st' WHERE id = ".$menu;
        if($conn->query($qryupdateactive)==false){
            $errFlag ++;
        }
        
    }
}
//die;
if($errFlag == 0){
$err = "Update Successful";
header("Location: ".$hostpath."/email_event.php?mod=5&res=1&msg=".$err);
    
}else{
    $err = "Something went wrong!";
    header("Location: ".$hostpath."/email_event.php?mod=5&res=1&msg=".$err);
}

?>