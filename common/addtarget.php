<?php
require "conn.php";
//print_r($_REQUEST);
//exit();
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/addtarget.php?res=01&msg='New Entry'&id=''&mod=2");
}
else
{
    if ( isset( $_POST['add'] ) ) {
    $acc_mgr=$_REQUEST['cmbhrmgr'];  $cmbyear=$_REQUEST['cmbyear'];
     
      $item = $_REQUEST['itemName'];
      $itemcat = $_REQUEST['itemcat'];
      $trgt = $_REQUEST['target'];
      $mnth = $_REQUEST['mnth'];
      
     $totr=0;
        if (is_array($item))
        {
            for ($i=0;$i<count($item);$i++)
                {
                    $itmmnm=$item[$i];$cat=$itemcat[$i];$tg=$trgt[$i];$mnthid=$mnth[$i];
                    //if($itmmnm==""){$itmmnm=null;}
                    $itqry="insert into salestarget( `yr`, `mnth`, `accmgr`, `itmcatagory`, `item`, `target`, `achivement`, `status`, `makeby`, `makedate`)
                            values( '".$cmbyear."','".$mnthid."',".$acc_mgr.",".$cat.",'".$itmmnm."',".$tg.",0,0,1,SYSDATE())";
                     //echo $itqry.',';//die;
                     if ($conn->query($itqry) == TRUE) { $totr=$i;  }
                        
                }
        }
        $err=$totr." No of of record for target is added"   ;
     //exit();
    }
    if ( isset( $_POST['update'] ) ) {
        
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($totr > 0) {
                header("Location: ".$hostpath."/targetList.php?res=1&msg=".$err."&mod=5&pg=1");
    } else {
         $err="Error: " . $itqry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/targetList.php?res=2&msg=".$err."&mod=5");
    }
    
    $conn->close();
}
?>