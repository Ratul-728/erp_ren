<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/soproduct.php?res=01&msg='New Entry'&id=''");
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
      $unpo = $_POST['unitpriceotc']; 
      $unpm = $_POST['unitpricemrc'];
      $dscr = $_POST['remarks'];
      
      
      $cost=0;$cmbstore=1;
        //$totalup = count($_FILES['attachment1']['name']);
       // $att1=$poid;
      // // echo $totalup;die;
      //  for( $j=0 ; $j < $totalup ; $j++ ) {
      //       $tmpFilePath = $_FILES['attachment1']['tmp_name'][$j];
      //       if ($tmpFilePath != ""){ $newFilePath = "upload/" . $_FILES['attachment1']['name'][$j];
       //          $didUpload = move_uploaded_file($tmpFilePath, $newFilePath);
       //          $att1=$att1.",".$_FILES['attachment1']['name'][$j];
      //       }
     //   }
        
    
        if (is_array($item))
        {
            for ($i=0;$i<count($item);$i++)
                {
                    $itmsl=$i+1;$itmmnm=$item[$i];$descr=$dscr[$i];$mu=$msu[$i];$qty=$oqty[$i];$upo=$unpo[$i]; $upm=$unpm[$i];
                    if($upo==''){$upo=0;}
                    if($upm==''){$upm=0;}
                    $amt=($qty*$upo)+($qty*$upm);
                    $tot_amt=$tot_amt+$amt;
                   
                    
                    $itqry="insert into soproddetails( `socode`,`sosl`, `productid`,`remarks`, `mu`, `qty`, `otc`, `mrc`, `makeby`, `makedt`)
                            values( '".$poid."',".$itmsl.",".$itmmnm.",'".$descr."',".$mu.",".$qty.",".$upo.",".$upm.",1,SYSDATE())";
                     //echo $itqry;die;
                     if ($conn->query($itqry) == TRUE) { $err="SOItem added successfully";  }
                     
                      $sql = "CALL stock_in_out(".$itmmnm.",".$qty.",".$cost.",".$cmbstore.",'O')";
                        //echo $sql;die;
                        if ($conn->query($sql) == TRUE) { $err="stock added successfully";  }
                        
                                }
        }
           
      // echo "add";die;
     
      
        $sup_id= $_REQUEST['cmbsupnm']; $po_dt= $_REQUEST['po_dt']; $totamt= $tot_amt;
        $vat= $tot_amt*.15; $tax= $tot_amt*.05; $invoice_amount= $tot_amt+$vat+$tax; $delivery_dt= $_REQUEST['delivery_dt']; $hrid= '1';$st='1';
        $deliveryby=$_REQUEST['cmbhr']; $acc_mgr=$_REQUEST['cmbhrmgr'];  $srctp=$_REQUEST['cmbcontype'];
        $qry="insert into soproduct( `socode`,`srctype`, `customer`, `orderdate`, `deliverydt`, `deliveryby`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`) 
        values('".$poid."',".$srctp.",".$sup_id.",'".$po_dt."','".$delivery_dt."',".$deliveryby.",".$acc_mgr.",".$vat.",".$tax.",".$invoice_amount.",".$hrid.",sysdate())" ;
        $err="SO created successfully";
        
         $note="Service Order: SO ID".$poid." with amount ".$invoice_amount." was issued by USER";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values(".$sup_id.",8,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$invoice_amount.",".$hrid.",sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
     //echo $qry; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $poid= $_REQUEST['po_id']; $sup_id= $_REQUEST['cmbsupnm']; $po_dt= $_REQUEST['po_dt']; 
       // $totamt= $_REQUEST['totamt'];    $vat= $_REQUEST['vat']; $tax= $_REQUEST['tax']; $invoice_amount= $_REQUEST['invoice_amount']; 
        $delivery_dt= $_REQUEST['delivery_dt'];  $deliveryby=$_REQUEST['cmbhr']; $acc_mgr=$_REQUEST['cmbhrmgr'];  $srctp=$_REQUEST['cmbcontype'];
        $qry="update soproduct set `srctype`=".$sup_id.",`customer`=".$sup_id.",`orderdt`='".$po_dt."',`delivery_dt`='".$delivery_dt."',`deliveryby`=".$deliveryby.",`accmanager`=".$acc_mgr." where `socode`=".$poid."";
        $err="SO updated successfully";
        
          $note="Service Order: SO ID".$poid." with amount ".$invoice_amount." was updated by USER";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values(".$sup_id.",8,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$invoice_amount.",".$hrid.",sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/soproduct.php?res=1&msg=".$err."&id=".$poid."");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/soproduct.php?res=2&msg=".$err."&id=''");
    }
    
    $conn->close();
}
?>