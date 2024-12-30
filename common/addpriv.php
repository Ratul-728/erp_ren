<?php
require "conn.php";


if ( isset( $_POST['cancel'] ) ) {
    header("Location: ".$hostpath."/priv.php?res=01&msg='New Entry'&id=''&mod=5");
}

if ( isset( $_POST['submit'] ) ) {
    
    
    $hrid= $_POST['usrid'];
    
     $athid = $_POST['auth'];
     $mnid = $_POST['menuid'];
     $mn_priv = $_POST['cmbpriv'];
     $usrid = $_POST['husrid'];
     
   //echo $hrid;die;
    if (is_array($mnid))
    {
        for ($i=0;$i<count($mnid);$i++)
        {
          $auth=$athid[$i];
          $menuid=$mnid[$i]; 
          $menu_priv=$mn_priv[$i];
          if($auth=='')
          {
             if($menu_priv !='0')
             {
                $qry="insert into hrAuth(`hrid`, `menuid`, `menu_priv`) values( '".$usrid."','".$menuid."','".$menu_priv."')";
                if ($conn->query($qry) == TRUE) { $err="Privilage added successfully";  }
              
             }
          }
          else
          {
               if($menu_priv =='0')
               {
                $qry="delete from hrAuth where id=".$auth;
                if ($conn->query($qry) == TRUE) { $err="Privilage Deleted successfully";  }
                  //echo $qry;die;
               }
               else
               {
                  $qry="update  hrAuth set `menu_priv`= '".$menu_priv ."' where id=".$auth;
                  if ($conn->query($qry) == TRUE) { $err="Privilage updated successfully";  }
               }
          
          }
        }
    }
   
   
   // echo $passw;die;
    
   



//echo $qry;die;
/*if ($conn->connect_error) {
   echo "Connection failed: " . $conn->connect_error;
}

if ($conn->query($qry) === TRUE) {
  
   header("Location: ".$hostpath."/privList.php?res=1&msg=".$err."&id=".$aid."&mod=5");
    
    //echo "New record created successfully";
} else {
     $err="Error: " . $qry . "<br>" . $conn->error;
     header("Location: ".$hostpath."/priv.php?res=1&msg=".$err."&id=".$aid."&mod=5");
     //echo "Error: " . $qry . "<br>" . $conn->error;
}*/
 header("Location: ".$hostpath."/priv.php?res=1&msg=".$err."&id=".$aid."&mod=5");
$conn->close();
}
?>