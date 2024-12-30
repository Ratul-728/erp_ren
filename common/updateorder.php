<?php
 require_once("../common/conn.php");

if ( isset( $_POST['update'] ) ) 
{
   
    $modid= $_POST['pid'];
    $morderno= $_POST['orderno'];
    
    $nm= $_POST['nm'];
    $addr= $_POST['addr'];
    $dis= $_POST['cmbdis'];
    $area= $_POST['cmbarea'];
    $email= $_POST['email'];
    $phon= $_POST['phon'];
     $odt= $_POST['orderdt'];
      $pmode= $_POST['ordermod'];
      $hrid= $_POST['usrid'];
      
   
    $item = $_POST['itemName'];
    $qty = $_POST['quantity_otc'];
    $mu = $_POST['measureUnit'];
    $otc = $_POST['unitprice_otc'];
    $vat = $_POST['vat'];
    $ait = $_POST['ait'];
    $gtot=0;
    //echo $modid; die;
    if (is_array($item))
        {
            $delitm = "delete from soitemdetails where socode='".$morderno."'";
           //echo $delitm;die;
            if ($conn->query($delitm) == TRUE) { $err="";  } 
            
            $tot=0;
            for ($i=0;$i<count($item);$i++)
            {
                $itmsl=$i+1;$itmmnm=$item[$i];$qnty=$qty[$i];$muitm=$mu[$i];$upo=$otc[$i];$aititm=$ait[$i];$itmvat=$vat[$i];
                    
                $tot=$upo*$qnty;  $gtot=$gtot+$tot;
                $descr="so updated";
                 $itqry="insert into soitemdetails( `socode`,`sosl`, `productid`,vat,ait,`remarks`, `mu`, `qty`, `otc`, `makeby`, `makedt`)
                                values( '".$morderno."',".$itmsl.",".$itmmnm.",".$itmvat.",".$aititm.",'".$descr."',".$muitm.",".$qnty.",".$upo.",".$hrid.",SYSDATE())";
                        //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="SOItem added successfully";  }
            }
           
        }  
        
     $sql = "update soitem set invoiceamount=$gtot WHERE id=".$modid;
    $returl="/cus_order_view.php?res=4&id=".$modid."&mod=13";
}


if($sql!='')
{
    if ($conn->query($sql) == TRUE) {
             $err="Modified";
            header("Location: ".$hostpath."/cus_order_view.php?res=4&msg=".$err."&id=".$modid."&mod=13");
    } 
    else { $err="Error: " . $qry . "<br>" . $conn->error;
            header("Location: ".$hostpath."/cus_order_view.php?res=4&msg=".$err."&id=".$modid."&mod=13");}
   // header("Location: ".$hostpath.$returl); 
}
else
{
   $err="Refresh";
    header("Location: ".$hostpath."/cus_order_view.php?res=2&msg=".$err."&id=".$modid."&mod=13");
}   
$conn->close();
?>