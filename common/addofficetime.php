<?php
require "conn.php";
$hrid = $_POST['usrid'];

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/soitem.php?res=01&msg='New Entry'&id=''&mod=2");
}
else if ( isset( $_POST['copy'] ) ) {
      $srid= $_REQUEST['serid']; 
     // echo $srid;die;
      header("Location: ".$hostpath."/soitem.php?res=05&msg='Copy Entry'&id='".$srid."'&mod=2");
}
else
{
     $errflag=0;
     $poid=0;
    if ( isset( $_POST['add'] ) ) 
    {
      
      $item = $_POST['itemName'];
      $starttime = $_POST['starttime'];
      $endtime = $_POST['endtime'];
      $delaytime = $_POST['delaytime'];
      $edelaytime = $_POST['edelaytime'];
      $latetime = $_POST['latetime'];
      $abstime = $_POST['abstime'];
      
            if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $it = $item[$i]; $stime = $starttime[$i]; $etime = $endtime[$i]; $dtime = $delaytime[$i]; 
                        $edtime = $edelaytime[$i]; $ltime = $latetime[$i]; $atime = $abstime[$i];
                        
                        $qry = "INSERT INTO `OfficeTime`(`shift`, `start`, `end`, `delaytime`, `extendeddelay`, `latetime`, `absent`, `makeby`, `makedt`) 
                                VALUES (".$it.",'".$stime."','".$etime."','".$dtime."','".$edtime."','".$ltime."','".$atime."',".$hrid.",SYSDATE())";
                        
                         if ($conn->query($qry) == TRUE) { $err="Office Time added successfully";  }
                         else{
                             $errflag++;
                         }
                            
                    }
            }
               
    }
    if ( isset( $_POST['update'] ) ) {
        $item = $_POST['itemName'];
        $starttime = $_POST['starttime'];
        $endtime = $_POST['endtime'];
        $delaytime = $_POST['delaytime'];
        $edelaytime = $_POST['edelaytime'];
        $latetime = $_POST['latetime'];
        $abstime = $_POST['abstime'];
        $atid = $_POST["atid"];
        
        $qry = "UPDATE `OfficeTime` SET `shift`=".$item.",`start`='".$starttime."',`end`='".$endtime."',`delaytime`='".$delaytime."',`extendeddelay`='".$edelaytime."',
                                        `latetime`='".$latetime."',`absent`='".$abstime."' WHERE  id = ".$atid;
        if ($conn->query($qry) == TRUE) { $err="Office Time updated successfully";  }
        else{ 
            $errflag++;
            $err = "Something Went Wrong!!!";
        }
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    if($errflag==0)
    {
        header("Location: ".$hostpath."/officetimeList.php?res=1&msg=".$err."&id=".$poid."&mod=4&pg=1");
       
    }
     else
    {
        header("Location: ".$hostpath."/officetimeList.php?res=2&msg=".$err."&id=''&mod=4");
       
    }
    
    $conn->close();
}
?>