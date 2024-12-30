<?php 
require ("conn.php");

//print_r($_REQUEST);die;

$hrid = $_REQUEST["menuid"];
$shift = $_REQUEST["cmbpriv"];
$edt = $_REQUEST["action_dt"];

$arrsize = count($hrid);
$errflag = 0;

for($i = 0; $i < $arrsize; $i++){
    
    $qrych = "SELECT `id` FROM `assignshift` WHERE empid = ".$hrid[$i];
    $resultch = $conn->query($qrych); 
    
    if($edt[$i] != ''){
        $edt[$i] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$edt[$i]);
        //echo $edt[$i];die;
    }else{
        $edt[$i] = date("Y-m-d");
    }
    
    if ($resultch->num_rows > 0){
        $rowch = $resultch->fetch_assoc();
        $qry = "UPDATE `assignshift` SET `shift`=".$shift[$i].",`effectivedt`='".$edt[$i]."' WHERE id = ".$rowch["id"];
        
    }else{
        $qry = "INSERT INTO `assignshift`(`empid`, `shift`, `effectivedt`) VALUES (".$hrid[$i].",".$shift[$i].",'".$edt[$i]."')";
    }
    
    if ($conn->query($qry) == TRUE) {
        $qrych = "Select id from assignshifthist where empid = ".$hrid[$i]." and effectivedt = '".$edt[$i]."'";
        $resultch = $conn->query($qrych);
        if ($resultch->num_rows > 0) {
            while ($rowch = $resultch->fetch_assoc()) {
                $qryhis = "UPDATE `assignshifthist` SET `shift`= ".$shift[$i].",`effectivedt`='".$edt[$i]."' WHERE id = ".$rowch["id"];
            }
        }else{
            $qryhis = "INSERT INTO `assignshifthist`(`empid`, `shift`, `effectivedt`, `makedt`) VALUES (".$hrid[$i].",".$shift[$i].",'".$edt[$i]."',sysdate())";
        }
        
        $conn->query($qryhis);
    }else{
        $errflag++;
    }
    
    //echo $qry;echo "<br>";
}

//die;

if($errflag == 0){
    $err = "Assign Successfully";
    header("Location: ".$hostpath."/assignshiftList.php?pg=1&mod=4&msg=".$err."");
}else{
    $err = "Something went wrong";
    header("Location: ".$hostpath."/assignshiftList.php?pg=1&mod=4&msg=".$err."");
}


?>