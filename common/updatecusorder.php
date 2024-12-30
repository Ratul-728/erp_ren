<?php
//error_reporting(E_ALL);
//error_reporting( error_reporting() & ~E_NOTICE );
//ini_set('display_errors', 1);
//print_r($_REQUEST);die;


if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/BitFlow/custorder.php?res=01&msg='New Entry'&id=''&mod=13");
}
else if ( isset( $_POST['edit'] ) ) {
     $odid= $_POST['ordid'];
      header("Location: ".$hostpath."/BitFlow/cus_orders.php?res=01&msg='New Entry'&id=".$odid."&mod=13");
}
else if ( isset( $_POST['update'] ) ) 
{
    require_once("../common/conn.php");
    $odid= $_POST['ordid'];
    $cmbsupnm= $_POST['cmbsupnm'];
    
    $isexist="select invoiceno from soitem where id=".$odid;
    $resultexist = $conn->query($isexist);
    if ($resultexist->num_rows > 0)
        {
            while($rowex = $resultexist->fetch_assoc())  { $invexist=$rowex["invoiceno"];      }
        }
    
    if($invexist=='')
    {
    $invqry="SELECT lpad((max(substring(`invoiceno`,9,8))+1),8,0) inv FROM `soitem`  o 
        where substring(invoiceno,1,4)='".date(Y)."' and substring(invoiceno,5,2)='".date(m)."'";
        $result = $conn->query($invqry);
        if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())  { $inv=$row["inv"];      }
            }
        else {$inv='000001';}
        $invoiceno=date(Ymd).$inv;
        $sql = "update soitem set orderstatus=3, deliveryby=".$cmbsupnm." ,invoiceno='".$invoiceno."'  WHERE id=".$odid;
    }
    else
    {
        $sql = 'update soitem set orderstatus=3, deliveryby='.$cmbsupnm.' WHERE id='.$odid;
    }
    $returl="/custorderdelivery.php?pg=1&mod=13";
}
else if ( isset( $_POST['confirm'] ) ) 
{
   require_once("../common/conn.php");
    $odid= $_POST['ordid'];
    //echo $odid;die;
    $isexist="select invoiceno from soitem where id=".$odid;
    //echo $isexist;die;
    $resultexist = $conn->query($isexist);
    if ($resultexist->num_rows > 0)
    {
        while($rowex = $resultexist->fetch_assoc())  { $invexist=$rowex["invoiceno"];      }
    }
    
    if($invexist=='')
    {
        $invqry="SELECT lpad((max(substring(`invoiceno`,9,8))+1),8,0) inv FROM `soitem`  o 
        where substring(invoiceno,1,4)='".date(Y)."' and substring(invoiceno,5,2)='".date(m)."'";
        $result = $conn->query($invqry);
        if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())  { $inv=$row["inv"];      }
            }
        else {$inv='000001';}
        $invoiceno=date(Ymd).$inv;
        $sql = "update soitem set orderstatus=2,invoiceno='".$invoiceno."'  WHERE id=".$odid;
    }
    else
    {
        $sql = "update orders set orderstatus=2  WHERE id=".$odid;
    }
    //echo $sql;die;
    $returl="/custorder.php?pg=1&mod=13";
    
}
else
{
  $returl="/BitFlow/custorder.php?pg=1&mod=13";
}
//echo print_r($_REQUEST);
//die; 

if($sql!='')
{
    if ($conn->query($sql) == TRUE) { header("Location: ".$hostpath.$returl);} 
    else {echo "Error updating record: " . $conn->error;}
   // header("Location: ".$hostpath.$returl); 
}
else
{
   header("Location: ".$hostpath.$returl);
}    
$conn->close();
?>