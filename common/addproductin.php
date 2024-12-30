<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/productin.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        $product= $_REQUEST['cmbprod'];
        $mo = $_POST['cmbmo'];
        $factory = $_POST['factory'];
        $qty = $_POST['qty'];
        $cmbhr = $_POST['cmbhr'];
        $cmbstore = $_POST['cmbstore'];
        $remarks = $_POST['remarks'];
        $cost = $_POST['cost'];
    
        $hrid= '1';
        $make_date=date('Y-m-d H:i:s');
         
        $qry="insert into productIn(`moid`, `productid`, `factoryid`, `quantity`, `rate`,`Storeid`, `receivedBy`, `Remarks`, `makedt`, `makeby`) 
        values('".$mo."',".$product.",".$factory.",".$qty.",".$cost.",".$cmbstore.",".$cmbhr.",'".$remarks."','".$make_date."',".$hrid.")" ;
        $err="Stock created successfully";
        
        $sql = "CALL stock_in_out(".$product.",".$qty.",".$cost.",".$cmbstore.",'I')";
        //echo $sql;die;
        if ($conn->query($sql) == TRUE) { $err="stock added successfully";  }
        
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $id= $_REQUEST['id'];
        $product= $_REQUEST['cmbprod'];
        $mo = $_POST['cmbmo'];
        $factory = $_POST['factory'];
        $qty = $_POST['qty'];
        $cmbhr = $_POST['cmbhr'];
        $cmbstore = $_POST['cmbstore'];
        $remarks = $_POST['remarks'];
        
        $qry="update productIn set `productid`=".$product.",`moid`='".$mo."',`factoryid`=".$factory.",`quantity`=".$qty.",`Storeid`=".$cmbstore.",`receivedBy`='".$cmbhr."',`Remarks`='".$remarks.
        "' where `id`=".$id."";
        $err="Product stocked successfully";
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/productin.php?res=1&msg=".$err."&id=".$poid."");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/productin.php?res=2&msg=".$err."&id=''");
    }
    
    $conn->close();
}
?>