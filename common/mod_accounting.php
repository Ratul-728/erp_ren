<?php
require "conn.php";
session_start();
ini_set('display_errors',1);
$usr = $_SESSION["user"];

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/collection.php?res=01&msg='New Entry'&id=''&mod=3");
}
else
{
    
    
    
    //Function addgl;
    
    function addgl($gldata,&$msg,&$success){
        global $conn;
        $data = $gldata;
        
        //add GL Master
        $qry="INSERT INTO `glmst`(
                    `vouchno`, 
                    `transdt`, 
                    `refno`, 
                    `remarks`, 
                    `entryby`, 
                    `entrydate`
                    ) 
                    VALUES (
                    '".$data['vouchno']."',
                    '".$data['transdt']."',
                    '".$data['refno']."',
                    '".$data['remarks']."',
                    '".$data['entryby']."',
                    '".$data['entrydate']."'
                    )";
        
           // echo $qry;die;            
        if ($conn->query($qry) == TRUE) {
            $last = $conn->insert_id;
            
            /*
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            */

            //add GL Detail
             $itqry="INSERT INTO `gldlt`(
             `vouchno`, 
             `sl`, 
             `glac`, 
             `dr_cr`, 
             `amount`,
             `remarks`, 
             `entryby`, 
             `entrydate`
             ) 
              VALUES (
              '".$data['vouchno']."',
              '".$data['sl']."',
              '".$data['glac']."',
              '".$data['dr_cr']."',
              '".$data['amount']."',
              '".$data['remarks']."',
              '".$data['entryby']."',
              '".$data['entrydate']."'
              )";
            
            //echo $itqry;die;  
            
              if ($conn->query($itqry) == TRUE) { 
                  $msg="GL Master added successfully"; 
                  $success = 1;
              }else{
                  $msg="Error in gldetail query";
                  $errflag++;
                  $success = 0;
              }
                             

       
                
        } else {
             $errflag++;
             $success = 0;
             $msg="Error in glmaster query";
        }    
    
     } //function addgl($gldata,&$msg,&$success,&$insertId){
    
    
    //how to call it;
    /*
    
    $gldata = array(
       "vouchno" => "10000000000", 
        "transdt" => "2023-05-16 00-00-00", 
        "refno" => "0000145", 
        "remarks" => "This is the reason", 
        "sl" => 1, 
        "glac" => "ABC00012121545", 
        "dr_cr" => "D",     //Type
        "amount" => "500000",
        "entryby" => $_SESSION["user"], 
        "entrydate" => $phpdate
    );
    
    addgl($gldata,$msg,$success);
    echo $msg."<br>";
    echo $success."<br>";
    
    */
    
    //End Function addgl;
    
}
?>