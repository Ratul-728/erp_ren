<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/mo.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
      $moid= $_REQUEST['mo_id'];
    
      $item = $_POST['itemName'];
       $factory = $_POST['factory'];
       $color = $_POST['color'];
       $size = $_POST['size'];
      $msu = $_POST['measureUnit'];
      $oqty = $_POST['quantity'];
       $hrid = $_POST['usrid'];
       
      $mk_dt=date("Y-m-d");
      $hr=$hrid;
        $totalup = count($_FILES['attachment1']['name']);
        $att1=$moid;
       // echo $totalup;die;
        for( $j=0 ; $j < $totalup ; $j++ ) {
             $tmpFilePath = $_FILES['attachment1']['tmp_name'][$j];
             if ($tmpFilePath != ""){ $newFilePath = "upload/mo-".$moid."-" . $_FILES['attachment1']['name'][$j];
                 $didUpload = move_uploaded_file($tmpFilePath, $newFilePath);
                 $att1=$att1.",".$_FILES['attachment1']['name'][$j];
             }
        }
        
    
        if (is_array($item))
        {
            for ($i=0;$i<count($item);$i++)
                {
                    $itmsl=$i+1;$itmmnm=$item[$i];$factoryid=$factory[$i];$colorid=$color[$i];$sizenm=$size[$i];$mu=$msu[$i];$qty=$oqty[$i]; 
                   
                    
                    $itqry="insert into moitem(`moid`, `itmsl`, `itemid`, `factoryid`, `color`, `size`, `mu`, `qty`, `makeby`, `makedt`)
                            values( '".$moid."',".$itmsl.",".$itmmnm.",".$factoryid.",".$colorid.",'".$sizenm."',".$mu.",".$qty.",".$hr.",'".$mk_dt."')";
                    //echo $itqry;die;
                     if ($conn->query($itqry) == TRUE) { $err="Item added successfully";  }
                }
        }
           
      // echo "add";die;
     
      
        $delivery_dt= $_REQUEST['delivery_dt']; 
        $qry="insert into mo(`mocode`, `deliverydt`, `attachement`, `makeby`, `makedt`) values('".$moid."','".$delivery_dt."','".$att1."',".$hr.",'".$mk_dt."')" ;
        $err="MO created successfully";
        
        
        
        
     //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
         $moid= $_REQUEST['mo_id'];$delivery_dt= $_REQUEST['delivery_dt']; 
         
        $qry="update mo set `deliverydt`=".$delivery_dt." where `mocode`=".$moid."";
        $err="PO updated successfully";
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/moList.php?res=1&msg=".$err."&id=".$moid."");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/moList.php?res=2&msg=".$err."&id=''");
    }
    
    $conn->close();
}
?>