<?php
require "conn.php";
session_start();

$usr = $_SESSION["user"];
$errflag = 0;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/collection.php?res=01&msg='New Entry'&id=''&mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) {
     
        //Loop
        $item = $_POST['itemName'];
        $glnoarr = $_POST["glno"];
        $businessarr = $_POST["business"];

        if (is_array($item))
        {
            for ($i=0;$i<count($item);$i++)
            {
                $itmsl=$i+1;$glno=$glnoarr[$i];$business=$businessarr[$i]; 
                            
                $itqry="INSERT INTO `glmapping`(`buisness`, `mappedgl`) VALUES ('".$business."','".$glno."')";
                             
                if ($conn->query($itqry) == TRUE) { $err="GL Mapping added successfully";  }else{ $errflag++;}
                             
                             
            }
        }
                
        
            
    }
    if ( isset( $_POST['update'] ) ) {
        
      $itid= $_REQUEST['itid'];
      $glno = $_POST["glno"];
      $business = $_POST["business"];
      
      
      $qry="UPDATE `glmapping` SET `buisness`='".$business."',`mappedgl`='".$glno."' WHERE id = ".$itid;
      
      if ($conn->query($qry) == TRUE) {
          $err = "Successfully Update";
      }else{
          $errflag++;
      }
          
          
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    if ($errflag == 0) {
        
            header("Location: ".$hostpath."/glmappingList.php?res=1&msg=".$err."&mod=7&pg=1");
        
    } else {
         $err="Something went Wrong";
          header("Location: ".$hostpath."/glmappingList.php?mod=7&res=2&msg=".$err."&id=''");
    }
    
    
    $conn->close();
}
?>