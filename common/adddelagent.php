<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/delagentList.php?mod=4");
}
else
{
    if ( isset( $_POST['add'] ) ) {
     
      $agent= $_REQUEST['agent'];       //if($agent==''){$agent='null';}
      $contcno= $_REQUEST['contcno'];   //if($contcno==''){$contcno='null';}
      $email= $_REQUEST['email'];       //if($email==''){$email='null';}
      $comm= $_REQUEST['comm'];         if($comm==''){$comm='0';}
      $addr= $_REQUEST['addr'];         //if($addr==''){$addr='null';}
      $descr= $_REQUEST['descr'];       //if($descr==''){$descr='null';}
      
      $hrid = $_POST['usrid']; 
      
    $qry = "insert into deveryagent( `name`, `address`, `contactno`, `email`, `commission`, `balance`, `narration`, `makeby`, `makedt`)
            values('".$agent."','".$addr."','".$contcno."','".$email."',".$comm.",0,'".$descr."','".$hrid."',sysdate())";
    //echo $qry;die;
    }
    if ( isset( $_POST['update'] ) ) {
        $aid= $_REQUEST['atid'];
        $agent= $_REQUEST['agent'];     //if($agent==''){$agent='null';}
        $contcno= $_REQUEST['contcno']; //if($contcno==''){$contcno='null';}
        $email= $_REQUEST['email'];     //if($email==''){$email='null';}
        $comm= $_REQUEST['comm'];       //if($comm==''){$comm='null';}
        $addr= $_REQUEST['addr'];       //if($addr==''){$addr='null';}
        $descr= $_REQUEST['descr'];     //if($descr==''){$descr='null';}
        
        $qry = "update deveryagent set name='".$agent."',address='".$addr."',contactno='".$contcno."',email='".$email."',narration='".$descr."' where id =".$aid;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/delagentList.php?&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/delagentList.php?&mod=4");
    }
    
    $conn->close();
}
?>