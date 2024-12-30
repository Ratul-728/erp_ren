<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/product.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        $code= $_REQUEST['code'];
        $item = $_POST['itemName'];
        $oqty = $_POST['quantity'];
        
        if (is_array($item))
        {
            for ($i=0;$i<count($item);$i++)
                {
                    $itmsl=$i+1;$itmmnm=$item[$i];$qty=$oqty[$i];
                    
                    $itqry="insert into productitem(`prductcode`, `itemsl`, `itemid`, `qty` )
                            values( '".$code."',".$itmsl.",".$itmmnm.",".$qty.")";
                     if ($conn->query($itqry) == TRUE) { $err="Item added successfully";  }
                }
        }
        
        
        $nm = $_POST['nm'];
        $cmbprdtp = $_POST['cmbprdtp'];
        $measureUnit = $_POST['measureUnit'];
        $cmbcolor = $_POST['cmbcolor'];
        $size = $_POST['size'];
        $cmbstyletp = $_POST['cmbstyletp'];
        $rate = $_POST['rate'];
        $cost = $_POST['cost'];
        $cmbitmcat = $_POST['cmbitmcat'];
        $cmbcur = $_POST['cmbcur'];
        $dimesion = $_POST['dimesion'];
        $weight = $_POST['weight'];
        $details = $_POST['details'];
        
        
        
        $totalup = count($_FILES['attachment1']['name']);
        $att1=$code;
        $tmpFilePath = $_FILES['attachment1']['tmp_name'];
        if ($tmpFilePath != ""){ $newFilePath = "upload/product/" .$code.".jpg";
                 $didUpload = move_uploaded_file($tmpFilePath, $newFilePath); } 
   
        $hrid= '1';
        $make_date=date('Y-m-d H:i:s');
         
        $qry="insert into product(`modelCode`, `productName`, `productType`, `mu`,`color`, `size`, `rate`, `cost`, `ItemCat`, `currency`, `dimension`, `weight`, `prodPhoto`, `details`, `pattern`, `make_dt`, `makeby`) 
        values('".$code."','".$nm."',".$cmbprdtp.",".$measureUnit.",".$cmbcolor.",'".$size."',".$rate.",".$cost.",".$cmbitmcat.",".$cmbcur.",'".$dimesion."',".$weight.",'".$att1."','".$details."',".$cmbstyletp.",'".$make_date."',".$hrid.")" ;
        $err="PO created successfully";
        
        
        
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $pid= $_REQUEST['prid'];
        $code= $_REQUEST['code'];
        $nm = $_POST['nm'];
        $cmbprdtp = $_POST['cmbprdtp'];
        $measureUnit = $_POST['measureUnit'];
        $cmbcolor = $_POST['cmbcolor'];
        $size = $_POST['size'];
        $cmbstyletp = $_POST['cmbstyletp'];
        $rate = $_POST['rate'];
        $cost = $_POST['cost'];
        $cmbitmcat = $_POST['cmbitmcat'];
        $cmbcur = $_POST['cmbcur'];
        $dimesion = $_POST['dimesion'];
        $weight = $_POST['weight'];
        $details = $_POST['details'];
        
        $totalup = count($_FILES['attachment1']['name']);
        $att1=$code;
        $tmpFilePath = $_FILES['attachment1']['tmp_name'];
        if ($tmpFilePath != ""){ $newFilePath = "upload/product/" .$code.".jpg";
                 $didUpload = move_uploaded_file($tmpFilePath, $newFilePath); } 
        
        $qry="update product set `productName`='".$nm."',`productType`=".$cmbprdtp.", `mu`=".$measureUnit.",`color`=".$cmbcolor.",`size`='".$size."',`rate`=".$rate.",`cost`=".$cost.
        ",`ItemCat`=".$cmbitmcat.",`currency`=".$cmbcur.",`dimension`='".$dimesion."',`weight`=".$weight.",`details`='".$details."',`prodPhoto`='".$att1."',`pattern`=".$cmbstyletp." where `id`=".$pid."";
        $err="Product updated successfully";
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/product.php?res=1&msg=".$err."&id=".$poid."");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/product.php?res=2&msg=".$err."&id=''");
    }
    
    $conn->close();
}
?>