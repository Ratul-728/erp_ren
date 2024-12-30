<?php
require "conn.php";
session_start();

$user=$_SESSION["user"];

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');

$errflag = 0;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/deal.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['add'] ) ) 
    {
        
        //$glacno = $_POST['glacno'];
        $glnm = $_POST['glnm'];
        $parent_gl = $_POST['parent_gl'];
        $is_posted = $_POST['is_posted']; if($is_posted == '') $is_posted = 'N';
        $is_financed = $_POST['is_finance']; if($is_financed == '') $is_financed = 'N';
        //$type = $_POST['type'];
        //$lvl = $_POST['lvl'];  
        $opbal = $_POST['opbal'];
        $clbal = $_POST['clbal'];
        
        //Get GLAC and level Info
        $qryisctl="SELECT dr_cr,lvl FROM `coa` WHERE `ctlgl` = '$parent_gl'";
        $resultisctl= $conn->query($qryisctl);
         if ($resultisctl->num_rows > 0) 
         {
            $qryinfo = "SELECT dr_cr,lvl,rpad(concat((substr(glno,1,((lvl-1)*2)-1)),lpad(max(substr(glno,((lvl-1)*2),2))+1,2,0)),9,0) newgl FROM `coa` WHERE `ctlgl` = '$parent_gl'";
            //echo $qryinfo;die;
            $resultinfo = $conn->query($qryinfo);
             if ($resultinfo->num_rows > 0) 
             {
                while($rowinfo = $resultinfo->fetch_assoc()){
                    $glacno = $rowinfo["newgl"];
                    $lvl = $rowinfo["lvl"];
                    $type = $rowinfo["dr_cr"];
                }
             }
         }
         else
         {
            $qryinfo = "SELECT dr_cr,lvl,rpad(concat(substr(glno,1,((lvl*2)-1)),'01'),9,0) newgl FROM `coa` WHERE `glno` ='$parent_gl'";
            $resultinfo = $conn->query($qryinfo);
             if ($resultinfo->num_rows > 0) 
             {
                while($rowinfo = $resultinfo->fetch_assoc()){
                    $glacno = $rowinfo["newgl"];
                    $lvl = $rowinfo["lvl"]+1;
                    $type = $rowinfo["dr_cr"];
                }
             }
         }
         
         //echo $qryinfo;die; 
         if($lvl==5){$is_posted='Y';}     
         
       $itqry="INSERT INTO `coa`(`glno`, `glnm`, `ctlgl`, `isposted`,`oflag`, `dr_cr`, `lvl`, `opbal`, `closingbal`, `entryby`, `entrydate`, `companyid`) 
                                        VALUES ('".$glacno."','".$glnm."','".$parent_gl."','".$is_posted."','".$is_financed."','".$type."','".$lvl."','".$opbal."','".$clbal."','".$user."',SYSDATE(),1)";
                         //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="COA added successfully";  }else{ $errflag++;}
        
        
    }
    if ( isset( $_POST['update'] ) ) {
        
        $id= $_POST['itid'];
        
        $glnm = $_POST['glnm'];
        $parent_gl = $_POST['parent_gl'];
        $is_posted = $_POST['is_posted']; if($is_posted == '') $is_posted = 'N';
        //$type = $_POST['type'];
        //$lvl = $_POST['lvl'];  
        $opbal = $_POST['opbal'];
        $clbal = $_POST['clbal'];
        /*
        $qrych = "Select ctlgl from coa where id = ".$id; 
        $resultch = $conn->query($qrych);
        while($rowch = $resultch->fetch_assoc()){
            $ctlgl = $rowch["ctlgl"];
        }
        
        if($ctlgl != $parent_gl){

            //Get GLAC and level Info
            $qryinfo = "SELECT dr_cr,lvl,rpad(concat((substr('".$parent_gl."',1,((lvl-1)*2)-1)),lpad(max(substr(glno,((lvl-1)*2),2))+1,2,0)),9,0) newgl FROM `coa` WHERE `ctlgl` = '".$parent_gl."'";
            //echo $qryinfo;die;
            $resultinfo = $conn->query($qryinfo);
            while($rowinfo = $resultinfo->fetch_assoc()){
                $glacno = $rowinfo["newgl"];
                $lvl = $rowinfo["lvl"];
                $type = $rowinfo["dr_cr"];
            }
            
            $qryString = " ,`glno` = '".$glacno."',`ctlgl`='".$parent_gl."', lvl = '".$lvl."', `dr_cr`='".$type."'";
        }
        
        
        //$value = $_POST['value'];       if($value==''){$value='NULL';}
        */ 
       
        $qry="UPDATE `coa` SET `glnm`='".$glnm."'  WHERE id = ".$id;//,`isposted`='".$is_posted."',`opbal`='".$opbal."',`closingbal`='".$clbal."' $qryString
        if ($conn->query($qry) == TRUE) { $err="Update Successfully";  }else{ $errflag++;}
      
        //echo $qry;die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errflag == 0) {
        
            header("Location: ".$hostpath."/coafinanceList.php?res=1&msg=".$err."&mod=17&pg=1");
        
    } else {
         $err="Something went Wrong";
          header("Location: ".$hostpath."/coafinanceList.php?mod=17&res=2&msg=".$err."&id=''");
    }
    
    $conn->close();
}
?>