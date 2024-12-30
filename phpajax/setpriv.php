<?php
require_once("../common/conn.php");
session_start();
$usr = $_SESSION["user"];
$debug = 0;
if($usr){

/*
1. Check user session: check valid logged user;
2. Check this user has edit privillage in Privillage module;
3. take parameter in post methoad and refferer is priv.php in same domain;

if above checking returns true, go below for action else show error message;
*/

    

    if($_POST['postaction'] == 'menuswitcher'){
        //print_r($_POST); die;
        $targetUser = $_POST['targetuser']; //id
        $menuId = $_POST['menuid'];
        $val =  ($_POST['val'] == 1)?1:0;
        $privset = $_POST['privset'];
        
        //Array ( [postaction] => menuswitcher [privset] => create_edit_delete_view_export_report_print [menuid] => 32 [val] => 0 [targetuser] => 123 ) 
        
        $privset = explode("_",$_POST['privset']);
        
       // $selectQ = "SELECT * FROM hrAuth WHERE hrid=".$_POST['targetuser']." AND menuid=".$_POST['menuid']." AND ";
        foreach($privset as $pval){
          
           // $qpart .="`".trim($pval)."`=1 OR ";
            $insertKeys .="`".trim($pval)."`,";
            $insertVals .='1,';
        }
         $insertKeys = substr($insertKeys,0,-1);
         $insertVals = substr($insertVals,0,-1);
        // $qpart = "(".substr($qpart,0,-3).")";
       //  $selectQ = $selectQ.$qpart;
        //echo $selectQ;
        
    
         //echo $val;
         
         if($val == 1){ // yes to all
            //echo 'yes to all';
            //delete first;
            
            
            $delQ='DELETE FROM hrAuth  WHERE menuid='.$menuId.' and hrid='.$targetUser; 
           
            //if($conn->query($delQ) === TRUE){}
            
            //echo $delQ;die;
            //then insert;
            
            
            
            $insertQ='INSERT INTO hrAuth (`hrid`,`menuid`,  '.$insertKeys.') VALUES ('.$targetUser.','.$menuId.','.$insertVals.')';
            //echo $insertQ;die;
            if($conn->query($insertQ) === TRUE){
                echo "Menu added and Privilege enabled successfully.<br>";
            }
            //echo $insertQ;
            
            
         }else{
             //delete menu fro hrAuth
              $delQ='DELETE FROM hrAuth  WHERE menuid='.$menuId.' and hrid='.$targetUser;
              if($conn->query($delQ) === TRUE) {
                  echo "Menu and Privilege removed";
              }     
         }
         
    }






    if($_POST['postaction'] == 'privswitcher'){
    
        $targetUser = $_POST['targetuser']; //id
        $menuId = $_POST['menuid'];
        $key =  $_POST['key'];
        $val =  ($_POST['val'] == 1)?1:0;
        
        //find hrid if found update or insert;
        
        $selectQ = 'SELECT * FROM hrAuth WHERE hrid='.$targetUser. ' AND  menuid='.$menuId;
        	//echo $selectQ ;
        $selectR = $conn->query($selectQ);
        if ($selectR->num_rows > 0){
            //update
        
            $updateQ='UPDATE hrAuth SET `'.trim($key).'`='.$val.'  WHERE menuid='.$menuId.' and hrid='.$targetUser;
            //echo $upddelivery;
            if ($conn->query($updateQ) == TRUE) { 
                    echo ucfirst($key)." Privillage updated successfully.<br>";   if($debug == 1)echo $updateQ; } else{ echo 'Error updating data<br>'; if($debug == 1)echo $updateQ;}
            
        }else{
            
            //insert
            $insertQ='INSERT INTO hrAuth (`hrid`,`menuid`,`'.$key.'`) VALUES ('.$targetUser.','.$menuId.','.$val.')';
            
            if($conn->query($insertQ) == TRUE){  echo ucfirst($key)." Privillage added successfully.<br>"; if($debug == 1)echo $insertQ; }else{ echo 'Error inserting data<br>'; if($debug == 1)echo $insertQ;}
        }
    
    }//if($_POST['postaction'] == 'privwitcher'){

    
    
}else{
    echo 'Invalid access to this file';
}//if($usr){
?>