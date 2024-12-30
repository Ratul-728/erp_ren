<?php
require "conn.php";


if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/po.php?res=01&msg='New Entry'&id=''&mod=1");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
      $poid= $_REQUEST['po_id'];
      $tot_amt=0;
      $item = $_POST['itemName'];
      $msu = $_POST['measureUnit'];
      $oqty = $_POST['quantity'];
      $unp = $_POST['unitPrice'];
      $dscr = $_POST['description'];
      
        $totalup = count($_FILES['attachment1']['name']);
        $att1=$poid;
       // echo $totalup;die;
        for( $j=0 ; $j < $totalup ; $j++ ) {
             $tmpFilePath = $_FILES['attachment1']['tmp_name'][$j];
             if ($tmpFilePath != ""){ $newFilePath = "upload/po/".$poid."_".$j;
                 $didUpload = move_uploaded_file($tmpFilePath, $newFilePath);
                 $att1=$att1.",".$j;
             }
        }
        
    
        if (is_array($item))
        {
            for ($i=0;$i<count($item);$i++)
                {
                    $itmsl=$i+1;$itmmnm=$item[$i];$descr=$dscr[$i];$mu=$msu[$i];$qty=$oqty[$i];$up=$unp[$i]; $amt=$qty*$up;
                    $tot_amt=$tot_amt+$amt;
                    
                    $itqry="insert into poitem( `poid`, `item_sl`, `itemid`, `description`, `muid`, `qty`, `unitprice`, `amount`, `status`)
                            values( '".$poid."',".$itmsl.",".$itmmnm.",'".$descr."',".$mu.",".$qty.",".$up.",".$amt.",'A')";
                           // echo $itqry;die;
                     if ($conn->query($itqry) == TRUE) { $err="Item added successfully";  }
                     
                      $sql = "CALL raw_in_out(".$itmmnm.",".$qty.",".$up.",1,'I')";
     // echo $sql;die;
                    if ($conn->query($sql) == TRUE) { $err="stock added successfully";  }
                }
        }
           
      // echo "add";die;
     
      $pby = $_POST['cmbhr'];
        $sup_id= $_REQUEST['cmbsupnm']; $po_dt= $_REQUEST['po_dt']; $date = DateTime::createFromFormat('d/m/Y', $po_dt); $po_dt = $date->format('Y-m-d');
        
        $curr_id= $_REQUEST['cmbcur']; $totamt= $tot_amt; 
        $ship_dt= $_REQUEST['ship_dt'];
        $date = DateTime::createFromFormat('d/m/Y', $ship_dt);
        $ship_dt = $date->format('Y-m-d');
        $vat= $tot_amt*.15; $tax= $tot_amt*.05; $invoice_amount= $tot_amt+$vat+$tax; $delivery_dt= $_REQUEST['delivery_dt'];$date = DateTime::createFromFormat('d/m/Y', $delivery_dt); $delivery_dt = $date->format('Y-m-d');
        $hrid= $pby;$st='1';
        $qry="insert into po(`poid`,`supid`,`orderdt`, `shipdt`, `currency`,`tot_amount`,`invoice_amount`,`vat`,`tax`,`delivery_dt`,`hrid`,`status`,`attach1`) values('".$poid."',".$sup_id.",'".$po_dt."','".$ship_dt."',".$curr_id.",".$totamt.",".$invoice_amount.",".$vat.",".$tax.",'".$delivery_dt."',".$hrid.",".$st.",'".$att1."')" ;
        $err="PO created successfully";
        
         $cusqry="update contact set currbal=currbal-".$amt." where id=".$sup_id." and status=1";
            //echo $itqry;die;
          if ($conn->query($cusqry) == TRUE) { $err="contatct updated successfully";  }
        
        $note="Purchase Order: PO ID".$poid." with amount ".$invoice_amount." was issued by USER";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values(".$sup_id.",8,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$invoice_amount.",".$hrid.",sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
         
         
         
        /* Accounnting */
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id=10 ";// Saleable products from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
            
        $getglinv="SELECT mappedgl FROM glmapping where id=4 ";// Vendor payment 
         $resultglinv = $conn->query($getglinv);
            if ($resultglinv->num_rows > 0) {while ($rowglinv = $resultglinv->fetch_assoc()) { $glnoinv = $rowglinv["mappedgl"];}} 
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),'".$poid."-".$sup_id."','Purchase Product','".$hrid."',sysdate())";
             
            // echo $glmqry;die;           
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",1,".$glnoinv.",'C',".$invoice_amount.",'PO Made',".$hrid.",sysdate())";
               //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",2,".$glno.",'D',".$invoice_amount.",'PO Made',".$hrid.",sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
        
     //echo $qry; die;
         
        
     //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $poid= $_REQUEST['po_id']; $sup_id= $_REQUEST['cmbsupnm']; $po_dt= $_REQUEST['po_dt']; $curr_id= $_REQUEST['cmbcur']; $totamt= $_REQUEST['totamt'];
        $vat= $_REQUEST['vat']; $tax= $_REQUEST['tax']; $invoice_amount= $_REQUEST['invoice_amount']; $delivery_dt= $_REQUEST['delivery_dt']; $hrid= $_REQUEST['hrid'];$st='1';
        $qry="update po set `supid`=".$sup_id.",`orderdt`='".$po_dt."', `currency`=".$curr_id.",`tot_amount`=".$totamt.",`invoice_amount`=".$invoice_amount.",`vat`=".$vat.",`tax`=".$tax.",`delivery_dt`='".$delivery_dt."',`hrid`=".$hrid." where `poid`=".$poid."";
        $err="PO updated successfully";
        
         $note="Purchase Order: PO ID".$poid." with amount ".$invoice_amount." was updated by USER";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values(".$sup_id.",8,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$invoice_amount.",".$hrid.",sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/poList.php?pg=1&res=1&msg=".$err."&id=".$poid."&mod=1");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/poList.php?res=2&msg=".$err."&id=''&mod=2");
    }
    
    $conn->close();
}
?>