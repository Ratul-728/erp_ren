<?php
require "conn.php";

//print_r($_POST);die;
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/connection.php');
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');

session_start(); 

$usr = $_SESSION["user"];
$errflag = 0;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/glmaster.php?res=01&msg='New Entry'&id=''&mod=7");
}
else
{
    if ( isset( $_POST['add'] ) )
    {
     
        $vouchTp = $_POST['vouchtp'];  
        $trdt= $_REQUEST['trdt']; 
        $vouch = 'VO-00000001';     
        $ref = $_POST['ref'];             
        $desc = $_POST['note'];
      
        //Loop
        $item = $_POST['glaccount'];
        $glnoarr = $_POST["glaccount"];
        $transtp = $_POST["trtp"];
        $c_amountarr = $_POST["c_amount"];
        //$d_amountarr = $_POST["d_amount"];
        $remarksarr  =$_POST["remarks"];
        
        
        
        //echo $vouchTp;die;
        if($vouchTp=="PV"){ $maxvouchqry="select concat('PV-',lpad((max(trim(LEADING '0' FROM  substr(vouchno,4,8)))+1),8,'0')) voucho from glmst where vouchno like 'PV%'";}
        else if($vouchTp=="JV"){ $maxvouchqry="select concat('JV-',lpad((max(trim(LEADING '0' FROM  substr(vouchno,4,8)))+1),8,'0')) voucho from glmst where vouchno like 'JV%' ";}
        else if($vouchTp=="RV"){ $maxvouchqry="select concat('RV-',lpad((max(trim(LEADING '0' FROM  substr(vouchno,4,8)))+1),8,'0')) voucho from glmst where vouchno like 'RV%'";}
        else { $maxvouchqry="select concat('OV-',lpad((max(trim(LEADING '0' FROM  substr(vouchno,4,8)))+1),8,'0')) voucho from glmst where vouchno like 'OV%'";}
        //echo $maxvouchqry;die;
        $vouchresult = $conn->query($maxvouchqry);
        if ($vouchresult->num_rows > 0)
        {
            while ($vouchrow = $vouchresult->fetch_assoc()) 
            {
                $vouch  = $vouchrow["voucho"];
            }
        }
        
         $totalup = count($_FILES['attachment1']['name']);
          $att1=$vouch;
          $tmpFilePath = $_FILES['attachment1']['tmp_name'];
          if ($tmpFilePath != ""){ $newFilePath = "upload/GL/".$att1.".jpg";
             $didUpload = move_uploaded_file($tmpFilePath, $newFilePath, $att1); 
          }
        
        //echo $vouch;die; 
        /*
        if($vouchTp=="PV"){ $vouch = getFormatedUniqueID('glmst','id','PV-',8,"0");}
        else if($vouchTp=="JV"){ $vouch = getFormatedUniqueID('glmst','id','JV-',8,"0");}
        else if($vouchTp=="RV"){ $vouch = getFormatedUniqueID('glmst','id','RV-',8,"0");}
        else { $vouch = getFormatedUniqueID('glmst','id','OV-',8,"0");}
       */
        
        $qry="INSERT INTO `glmst`(`VoucherTp`,`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`,`attachdoc`) 
                        VALUES ('".$vouchTp."','".$vouch."',STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'".$ref."','".$desc."','".$usr."',sysdate(),'$att1')";
        //  echo  $qry;die;             
        if ($conn->query($qry) == TRUE) {
            $last = $conn->insert_id;
            
            //$vouch ='VO-'.str_pad($last,8,"0",STR_PAD_LEFT);
            
            //Updtae Voucher
            //$qryup = "UPDATE `glmst` SET `vouchno`='".$vouch."' WHERE id = ".$last;
            //echo $qryup;die;
            $conn->query($qryup);
            
                if (is_array($item))
                {
                    for ($i=0;$i<count($item);$i++)
                        {
                            $itmsl=$i+1;$glno=$glnoarr[$i];$remarks="gl voucher";//$remarksarr[$i];
                            $amount = $c_amountarr[$i]; $type=$transtp[$i];
                            /*if($d_amountarr[$i] == ''){
                                $amount = $c_amountarr[$i];
                                $type = "C";
                            }else{
                                $amount = $d_amountarr[$i];
                                $type = "D";
                            }*/
                            
                            $itqry="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',".($i+1).",".$glno.",'".$type."',".$amount.",'".$remarks."',".$usr.",sysdate())";
                            // echo $itqry;die;
                             if ($conn->query($itqry) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
                             
                             
                        }
                }
                
        } else {
             $errflag++;
        }
        
            
    }
    if ( isset( $_POST['update'] ) ) {
        
        $itid= $_REQUEST['itid'];
        $trdt= $_REQUEST['trdt']; 
        //$vouch = 'VO-00000001';     
        $ref = $_POST['ref'];             
        $desc = $_POST['note'];
      
        //Loop
        $item = $_POST['glaccount'];
        $glnoarr = $_POST["glaccount"];
        $transtp = $_POST["trtp"];
        $c_amountarr = $_POST["c_amount"];
        //$d_amountarr = $_POST["d_amount"];
        $remarksarr  =$_POST["remarks"];
      
      //Getting Info
      $qryinfo = "SELECT  `vouchno` FROM `glmst` WHERE `id` = ".$itid;
      $result = $conn->query($qryinfo);
      $row = $result->fetch_assoc();
      
      $vouch = $row["vouchno"];
      
      $qry="UPDATE `glmst` SET `transdt`= STR_TO_DATE('".$trdt."', '%d/%m/%Y'),`refno`='".$ref."',`remarks`='".$desc."' WHERE id = ".$itid;
      
      if ($conn->query($qry) == TRUE) {
          //Delete Previous Data
          $qrydel = "DELETE FROM `gldlt` WHERE `vouchno` = '$vouch'";
          $conn->query($qrydel);
          
        if (is_array($item))
        {
            for ($i=0;$i<count($item);$i++)
            {
                $itmsl=$i+1;$glno=$glnoarr[$i];$remarks='';//$remarksarr[$i]; 
                $amount = $c_amountarr[$i]; $type=$transtp[$i];
                
                /*if($d_amountarr[$i] == ''){
                    $amount = $c_amountarr[$i];
                    $type = "C";
                }else{
                    $amount = $d_amountarr[$i];
                    $type = "D";
                }  */          
                            
                $itqry="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                    VALUES ('".$vouch."',".($i+1).",".$glno.",'".$type."',".$amount.",'".$remarks."',".$usr.",sysdate())";
                //echo $itqry;die;
                if ($conn->query($itqry) == TRUE) { $err="GL Master Updated successfully";  }else{ $errflag++;}
                             
                             
                        }
                }
                
        } else {
             $errflag++;
        }
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    if ($errflag == 0) {
        
            header("Location: ".$hostpath."/glmasterList.php?res=1&msg=".$err."&mod=7&pg=1");
        
    } else {
         $err="Something went Wrong";
          header("Location: ".$hostpath."/glmasterList.php?mod=7&res=2&msg=".$err."&id=''");
    }
    
    
    $conn->close();
}
?>