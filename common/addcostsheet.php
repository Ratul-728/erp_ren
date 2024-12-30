<?php
require "conn.php";
session_start();

//print_r($_POST);die;
$hrid = $_SESSION["empid"];
$errorflag = 0;

if ( isset( $_POST['add'] ) ) {
    $rfq_vendorId = $_POST["vendor-value"];
    if (is_array($rfq_vendorId))
            {
                for ($i=0;$i<count($rfq_vendorId) && $rfq_vendorId[$i] != null;$i++)
                    {
                        /*$qryinfo = "SELECT `rfq`, `product` FROM `rfq_vendor` WHERE `id` = ".$rfq_vendorId[$i];  
                        $resultinfo = $conn->query($qryinfo); 
                        while($rowinfo = $resultinfo->fetch_assoc()){
                            $rfq = $rowinfo["rfq"];
                            $product = $rowinfo["product"];
                        }
                        
                        //Decline Other than accept
                        $qryupdate = "UPDATE `rfq_vendor` SET `st`= 3 WHERE `product` = $product and `rfq` = $rfq and `id` != ".$rfq_vendorId[$i];
                        if($conn->query($qryupdate) == false){
                            $errorflag++;
                        } 
                        
                        // update accepted
                        $qryaccept = "UPDATE `rfq_vendor` SET `st`= 1 WHERE `id` = ".$rfq_vendorId[$i];
                        if($conn->query($qryaccept)== false){
                            $errorflag++;
                        } */
                        
                         $qry="INSERT INTO `rfq_authorisation`(`rfq_vendor`, `recommender`,  `makedt`) 
                                        VALUES ('$rfq_vendorId[$i]', '$hrid', sysdate())";
                        $conn->query($qry);
                    }
                
            }
        
        if($errorflag==0){
            $err = "Successfully updated";
            header("Location: ".$hostpath."/cost_sheet_new.php?res=1&msg=".$err."&id=".$aid."&mod=14");
        }else{
            $err = "Something went wrong";
            header("Location: ".$hostpath."/cost_sheet_new.php?res=2&msg=".$err."&id=".$aid."&mod=14");
        }
}
        
        
        
?>