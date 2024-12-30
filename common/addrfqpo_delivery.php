<?php
require "conn.php";
session_start();

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');

$rf = $_POST["itid"];

$rfpoid = $_POST["rfpoid"];
$offered_quantity = $_POST["offered_quantity"];
$quantity = $_POST["quantity"];
$po_dt =$_POST["rec_date"];


$productid = $_POST["productid"];
$unitprice = $_POST["unitprice"];
$store = $_POST['storeName'];
$sup = $_POST["vid"];

$hrid = $_SESSION["user"];
//print_r($_POST);

$mainst = 0;
                    
for ($i=0;$i<count($rfpoid);$i++){
    $qty=$quantity[$i]; $offqty = $offered_quantity[$i]; $rfid = $rfpoid[$i];
    //echo $qty; echo "    ".$offqty;die;
    if($qty == $offqty){
        $st = 1;
    }else if($qty == 0){
        $st = 3;
    }else if($qty > $offqty){
        $err = "Approved quantity is greater than offered quantity";
        header("Location: ".$hostpath."/rfqpo_deliveryList.php?mod=14&res=2&msg=".$err."");
    }else{
        $st = 2;
    }
    
    $mainst += $st;
    
    //Update Into RFQ PO Detaitls 
    $qrydetails = "UPDATE `rfqpo_details` SET qty= $qty, `status`= '$st' WHERE id = ".$rfid; 
    $conn->query($qrydetails);
    
    //update Challan
    $getpo="select lpad((cast(max(substr(poid,15,4)) AS UNSIGNED)+1),4,'0') maxpo from po where substr(poid,3,6)=date_format(sysdate(),'%m%Y')";
    $respo = $conn->query($getpo);
    while($rowpo = $respo->fetch_assoc()){
        $poid=$rowpo["maxpo"];
    }
    $qrybc2="SELECT barcode FROM item where id= $productid[$i]";
    $resbc2 = $conn->query($qrybc2);
    while($row2bc = $resbc2->fetch_assoc()){
        $barcode=$row2bc["barcode"];
    }
    $qry1 = "SELECT a.`pono` FROM `rfqpo_details` a WHERE a.id = ".$rfid;
    $result1 = $conn->query($qry1);
    while($row2 = $result1->fetch_assoc()){
        $adv=$row2["pono"];
    }
    $challanno=date(dmYHis).$poid;
    $itmsl = $i+1; $itmmnm = $productid[$i]; $up = $unitprice[$i]; $amt = $qty * $up;
    $storerm=$store[$i];if($storerm==''){$storerm='1';}
        
    $itqry="insert into poitem( `poid`, `item_sl`, `itemid`, `qty`, `unitprice`, `amount`, `status`,barcode,storerome)
                    values( '".$challanno."','".$itmsl."','".$itmmnm."','".$qty."','".$up."','".$amt."','A','".$barcode."','".$storerm."' )";
                          //echo $itqry;die;
    if ($conn->query($itqry) == TRUE) { $err="Item added successfully";  }
    
    $sqlsp = "CALL psp_stock('".$itmmnm."','".$qty."','".$up."','I','','".$barcode."','','".$storerm."')";
    if ($conn->query($sqlsp) == TRUE) { $err="stock added successfully";  }
    
    $qrypo="insert into po(`adviceno`,`poid`,supid,`orderdt`,`tot_amount`,`invoice_amount`,`vat`,`tax`,`delivery_dt`,`hrid`,`status`,`makedt`)
        values('".$adv."','".$challanno."','".$sup."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),'".$amt."',0,0,0,STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),'".$hrid."','A',sysdate())" ;
    
    $conn->query($qrypo);
    
    /* Accounnting */
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id=10 ";// Saleable products from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
            
        $getglinv="SELECT mappedgl FROM glmapping where id=4 ";// Vendor payment 
         $resultglinv = $conn->query($getglinv);
            if ($resultglinv->num_rows > 0) {while ($rowglinv = $resultglinv->fetch_assoc()) { $glnoinv = $rowglinv["mappedgl"];}} 
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),'".$challanno."-".$sup."','Purchase Product through challan','".$hrid."',sysdate())";
             
            // echo $glmqry;die;           
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',1,'".$glnoinv."','C','".$amt."','Challan  Made','".$hrid."',sysdate())";
               //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="STOCK added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',2,'".$glno."','D','".$amt."','Challan Made','".$hrid."',sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="STOCK added successfully";  }else{ $errflag++;}
        }
    
}

if($mainst == count($rfpoid)){
    $status = 1;
}else if($mainst == 0){
    $status = 3;
}else{
    $status = 2;
}

//Update RFQ PO
$qry = "UPDATE `rfqpo` SET `st`='$status' WHERE id = ".$rf;
$conn->query($qryup);

if($conn->query($qry) == TRUE){
    $err = "Successfully Updated";
    header("Location: ".$hostpath."/raise_poList.php?res=1&msg=".$err."&mod=14&pg=1");
}else{
    $err = "Something went wrong";
    header("Location: ".$hostpath."/raise_poList.php?res=2&msg=".$err."&mod=14&pg=1");
}

?>