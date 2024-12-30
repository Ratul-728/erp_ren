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
    $errorFlag = 0;
    if ( isset( $_POST['add'] ) ) {
        $maxid="SELECT (max(`id`)+1) cd FROM `rfq`";
        $resultmid = $conn->query($maxid); if ($resultmid->num_rows > 0) {while($rowmid = $resultmid->fetch_assoc()) { $reqid= $rowmid["cd"];}} if($reqid == '') $reqid = 1;
        $rfqno = "RFQ-".$reqid;
        
        
        $rfq_date = $_POST['rfq_date'];
        $rfq_by = $_POST['rfq_by']; //if($cmbstage==''){$cmbstage='NULL';}
        $val_date = $_POST['val_date'];
        $seq_depo = $_POST['seq_depo'];
        $rfq_note = addslashes($_POST['rfq_note']);
        
        //Array
        $item = $_POST['itemName'];
        $rfq_specification = $_POST['rfq_spec'];
        $rfq_marketprice = $_POST['rfq_mrp'];
        $rfq_quantity = $_POST["rfq_qty"];
        $attval = $_POST["attval"];
        
        if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $itmsl=$i+1;$prodnm=$item[$i];$rfq_qty=$rfq_quantity[$i]; $rfq_mrp = $rfq_marketprice[$i]; $rfq_spec = $rfq_specification[$i];
                        $approved_qty = 0; $req_id = "";
                        for ($j=0;$j<count($attval[$prodnm]);$j++){
                            $req_array = $attval[$prodnm][$j];
                            $qryreq = "SELECT id, `approved_qty` FROM `requision_details` WHERE `requision_no` = '$req_array' and `product` = $prodnm";
                            //echo $qryreq;
                            $resultreq = $conn->query($qryreq); 
                            while($rowreq = $resultreq->fetch_assoc()){
                                $approved_qty += $rowreq["approved_qty"];
                                $req_id = $req_id.$rowreq["id"].",";
                            }
                        }
                        //echo $req_id;die;
                        
                        $itqry="INSERT INTO `rfq_details`(`rfq`, `product`, `requisition_id`, `total_requisition_qty`, `order_qty`, `market_price`, spec) 
                                                VALUES ('$rfqno','$prodnm','$req_id','$approved_qty','$rfq_qty', '$rfq_mrp', '$rfq_spec')";
                         //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="RFQ Details added successfully";  }
                    }
            }  
        
        
        
        //$value = $_POST['value'];       if($value==''){$value='NULL';}
       
        
       // $hrid= '1';
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `rfq`(`rfq`, `date`, `rfq_by`, `note`, `validity_date`, `security_deposite`,`makeby`, `makedt`) 
                        VALUES ('$rfqno',STR_TO_DATE('$rfq_date','%d/%m/%Y'),'$rfq_by','$rfq_note',STR_TO_DATE('$val_date','%d/%m/%Y'),'$seq_depo',$hrid,sysdate())" ; 
                        //echo $qry;die;
        $err="RFQ created successfully";
    }
    if ( isset( $_POST['update'] ) ) {
        
        $rfq = $_POST["rfq"];
        
        //Array
        $item = $_POST['itemName'];
        $vendor_specification = $_POST['vendor_spec'];
        $vendor_quantity = $_POST['vendor_qty'];
        $vendor_qoutate = $_POST["qoutate_price"];
        $vendorId  = $_POST["vendorId"];
        
        if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $rfq_info = "SELECT  a.`product`, a.`spec`, a.`order_qty`, b.date FROM `rfq_details` a LEFT JOIN rfq b ON b.rfq = a.`rfq` WHERE a.`id` = ".$item[$i];
                        //echo $rfq_info;die;
                        $result_info = $conn->query($rfq_info); 
                        while($rowinfo = $result_info->fetch_assoc()){
                            $prod = $rowinfo["product"]; $spec = $rowinfo["spec"]; $qty = $rowinfo["order_qty"]; $date = $rowinfo["date"];
                        }
                        
                        $vendor_qty=$vendor_quantity[$i]; $vendor_spec = $vendor_specification[$i]; $vendor_qprice = $vendor_qoutate[$i]; $venId = $vendorId[$i];
                        
                        $maxid="SELECT (max(`id`)+1) cd FROM `rfq_vendor`";
                        $resultmid = $conn->query($maxid);while($rowmid = $resultmid->fetch_assoc()) { $qid= $rowmid["cd"];} if($qid == '') $qid = 1;
                        $qno = "Quotation-".$qid;
                        
                        $itqry="INSERT INTO `rfq_vendor`(`quotation`, `rfq`, `vendor_id`, `product`, `date`, `order_qty`, `offered_qty`, `quated_price`, `item_spec`) 
                                                VALUES ('$qno', '$item[$i]','$venId','$prod','$date','$qty','$vendor_qty',$vendor_qprice,'$vendor_spec')";
                         //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="Qoutation added successfully";  }
                         else{
                             $errorFlag++;
                         }
                    }
            }  
        
        /*$qry="UPDATE `rfq` SET `date`=STR_TO_DATE('$rfq_date','%d/%m/%Y'),`rfq_by`='$rfq_by',`note`='$rfq_note',`validity_date`=STR_TO_DATE('$val_date','%d/%m/%Y'),`security_deposite`='$seq_depo' WHERE id = ".$rfq_id ; 
                        //echo $qry;die;
        $err="RFQ Update successfully";*/
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errorFlag == 0) {
                header("Location: ".$hostpath."/cost_sheet_new.php?res=1&msg=".$err."&id=".$poid."&mod=14");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/rfqList.php?res=2&msg=".$err."&id=''&mod=14");
    }
    
    $conn->close();
}
?>