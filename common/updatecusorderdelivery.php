<?php
 
echo '<pre>';
 print_r($_REQUEST);
echo '</pre>';
 exit();
 
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/cusorder.php?res=01&msg='New Entry'&id=''&mod=4");
}
else if ( isset( $_POST['update'] ) ) 
{
    require_once("../common/conn.php");
    $odid= $_POST['ordid'];
    $cmbsupnm= $_POST['cmbsupnm'];
    $barcodes= $_POST['brcd'];
    $dtid= $_POST['orddtid'];
    $quantity_otc= $_POST['order_qtn'];
    
    echo(count($barcodes));die;
    
    $qrybc2="SELECT barcodewithstore FROM `companyoffice` ";
    $resbc2 = $conn->query($qrybc2);
     while($row2bc = $resbc2->fetch_assoc())
    {
        $storewise=$row2bc["barcodewithstore"]; 
    }
    
    if (is_array($barcodes))
            {
                for ($i=0;$i<count($barcodes);$i++)
                    {
                        $bc1=$barcodes[$i];$aid=$dtid[$i];
                        
                        
                        if($storewise=='Y')
                        {
                            $itqry="update soitemdetails set barcode ='".$bc1."' where id=".$aid;
                        }
                        else
                        {
                            $itqry="update soitemdetails set storeroome ='".$bc1."' where id=".$aid;
                        }
                            // echo $itqry;die;
                       if ($conn->query($itqry) == TRUE) { $err="Deliverd  successfully";  }
                       
                       $productquery="SELECT `productid`,`qty`,`otc`,`vatrate`,`discountrate`,`discounttot` FROM `soitemdetails`  where id=".$aid;
                       $prodresult = $conn->query($productquery);  
                            if ($prodresult->num_rows > 0)
                                {
                                    while($prodrow = $prodresult->fetch_assoc()) 
                                        {  
                                            $prid=$prodrow["productid"];$qty=$prodrow["qty"];  
                                        } 
                                }
                       $updstock="update stock set freeqty=freeqty-".$qty." where product=".$prid ;
                       //echo $updchalanstock;die;
                       if ($conn->query($updstock) == TRUE) { $err="Stock updated successfully";  }
                       
                       
                       
                       if($storewise=='Y')
                       {
                        $updchalanstock="update chalanstock set freeqty=freeqty-".$qty." where product=".$prid." and barcode='".$bc1."'" ;
                       }
                       else
                       {
                        $updchalanstock="update chalanstock set freeqty=freeqty-".$qty." where product=".$prid." and storerome='".$bc1."'" ;
                       }
                        //echo $updchalanstock;die;
                       if ($conn->query($updchalanstock) == TRUE) { $err="Stock updated successfully";  }
                                
                    }
            }
    
    $sql = 'update soitem set orderstatus=4 WHERE id='.$odid;
    
}
else
{
  
}
//echo $sql;die; 
if ($conn->query($sql) == TRUE) { header("Location: ".$hostpath."/cus_order_view.php?res=4&msg=%27Update%20Data%27&id=".$odid."&mod=13");} 
else {echo "Error updating record: " . $conn->error;}
    
$conn->close();
?>