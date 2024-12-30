<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawout.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        $item= $_REQUEST['item'];
        $mu = $_POST['measureUnit'];
        $qty = $_POST['qty'];
        $cmbstore = $_POST['cmbstore'];
        $cmbhr = $_POST['cmbhr'];
        $reason = $_POST['cmbreason'];
        $refr = $_POST['reference'];
        $dt = $_POST['dt'];
    
        $hrid= '1';
        $make_date=date('Y-m-d H:i:s');
         
        $qry="insert into rawout( `itemid`, `mu`, `qty`, `storeid`, `outby`, `reason`, `reference`, `trdate`, `makedate`, `makeby`) 
        values(".$item.",".$mu.",".$qty.",".$cmbstore.",".$cmbhr.",".$reason.",'".$refr."','".$dt."','".$make_date."',".$hrid.")" ;
        $err="Stock created successfully";
        
        //$sql = "CALL stock_in_out(".$product.",".$qty.",".$cost.",".$cmbstore.",'I')";
       // echo $qry;die;
       // if ($conn->query($sql) == TRUE) { $err="stock added successfully";  }
        
        
   //echo $totalup; die; 
    }
    if ( isset( $_POST['update'] ) ) {
        $id= $_REQUEST['id'];
        $item= $_REQUEST['item'];
        $mu = $_POST['measureUnit'];
        $qty = $_POST['qty'];
        $cmbstore = $_POST['cmbstore'];
        $cmbhr = $_POST['cmbhr'];
        $reason = $_POST['cmbreason'];
        $ref = $_POST['ref'];
        $dt = $_POST['dt'];
        
        $qry="update rawout set `itemid`=".$item.",`mu`='".$mu."',`qty`=".$qty.",`reason`=".$reason.",`reference`=".$ref." where `id`=".$id."";
        $err="Rawout stocked successfully";
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/rawout.php?res=1&msg=".$err."&id=".$id."");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/rawout.php?res=2&msg=".$err."&id=''");
    }
    
    $conn->close();
}
?>