<?php
require "conn.php";
session_start();
$hrid = $_SESSION["user"];

//print_r($_POST);

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=14");
}
else
{
    if ( isset( $_POST['update'] ) ) {
        $errflag = 0;
        $item = $_POST['itemRFQ'];
        $attval = $_POST["attval"];
        $id = $_POST["itid"];
        //print_r($_POST);die;
        if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $itmsl=$i+1;$prodnm=$item[$i];
                        $vendor_id = "";
                        for ($j=0;$j<count($attval[$prodnm]);$j++){
                            $req_array = $attval[$prodnm][$j];
                            $qryreq = "SELECT id FROM `organization` WHERE name = '$req_array'";
                            //echo $qryreq;
                            $resultreq = $conn->query($qryreq); 
                            while($rowreq = $resultreq->fetch_assoc()){
                                
                                $vendor_id = $vendor_id.$rowreq["id"].",";
                            }
                        }
                        //echo $req_id;die;
                        
                        $itqry="UPDATE `rfq_details` SET `vendor`= '$vendor_id'  WHERE `id` = ".$prodnm;
                         //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="Vendor added successfully";  }
                         else{ $errflag++;}
                    }
            }
            
        $qry = "UPDATE `rfq` SET `st`= 1  WHERE `id` = ".$id;
         if ($conn->query($qry) == TRUE) { $err="Vendor added successfully";  }
        else{ $errflag++;}

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errflag == 0) {
                header("Location: ".$hostpath."/rfqList.php?res=1&msg=".$err."&id=".$poid."&mod=14");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/rfqList.php?res=2&msg=".$err."&id=''&mod=14");
    }
    
    $conn->close();
}
?>