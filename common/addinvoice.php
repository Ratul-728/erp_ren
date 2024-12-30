<?php
require "conn.php";
/*
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/invoiceList.php?res=01&msg='New Entry'&id=''&mod=3");
}
else
{
    if ( isset( $_POST['add'] ) ) 
    {*/
     
     $inv_amount= $_POST['invamount']; 
      $inv_id= $_POST['invoiceid']; 
      $amt = str_replace(",","",$_POST['amt']);            if($amt==''){$amt='0';}
      $cmbdrcr = $_POST['cmbdrcr'];     if($cmbdrcr==''){$cmbdrcr='NULL';}
      $rem = $_POST['rem'];             if($rem==''){$rem='NULL';}
      $wltamt = $_POST['orgbal'];       if($wltamt==''){$wltamt='0';}
      $org_id = $_POST['orgid'];        if($orgid==''){$orgid='0';}
      $dueamt = str_replace(",","",$_POST['due']);          if($dueamt==''){$dueamt='0';}
      
      $hrid = $_POST['usrid'];        
       
       
       
       if($amt>$dueamt)
       {
           $amt=$dueamt;
       }
       
       if($wltamt<$amt)
       {
           $err="Error: Wallet Balance is insufficient to pay ";
             $response = array(
                "msg" =>$err,
	            );
	            echo json_encode($response);
       }
       else
       {
        $qry="insert into invoicepayment(  `invoicid`, `transdt`, `transmode`, `amount`, `remarks`, `makeby`, `makedate`) 
        values('".$inv_id."',sysdate(),'".$cmbdrcr."',".$amt.",'".$rem."',".$hrid.",sysdate())" ;
        $err="A receive created successfully";
           //echo $qry;die;
         if($amt==$dueamt){$payst='4';} else if($amt<$dueamt){$payst=5;} else if($amt>$inv_amount){$payst=3;} else {$payst=1;}  
         $invsqry="UPDATE `invoice` set `paidamount`=paidamount+".$amt." ,`dueamount`=dueamount-".$amt.",`paymentSt`=".$payst.",makedt=sysdate() where `invoiceno`='$inv_id'";
           // echo $itqry;die;
         if ($conn->query($invsqry) == TRUE) { $err="contatct updated successfully";  }
         
        $orgidqry="SELECT `organization` FROM `soitem` where `socode`=(SELECT `soid` FROM `invoice` where `invoiceno`='$inv_id')";
        $orresult = $conn->query($orgidqry); 
        if ($orresult->num_rows > 0)
        { $i=0;
            while($orgrow = $orresult->fetch_assoc()) 
            { 
               $orgid= $orgrow['organization'];
            }
            
        }
         if($cmbdrcr=='W')
         {
         $updorgqry="UPDATE `organization` set `balance`=balance-".$amt." where `id`=".$org_id;
         if ($conn->query($updorgqry) == TRUE) { $err1="contact updared successfully";  }
         
          $orgupdbalqry="select balance from organization where id=".$org_id;
           $resultbl = $conn->query($orgupdbalqry);
            if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
         
         $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
          values(sysdate(),$org_id,'Auto','D','$inv_id',$amt,$curbal,'Payid against invoice',$hrid,sysdate())";
          //echo $orgwallet;die;
           if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
         }
            //echo $itqry;die;
        
        
         $note="Customer bill adjustment against purchase: Paid Amount ".$amt." received ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$org_id."',6,sysdate(),'".$note."','',0,".$amt.",'".$hrid."',sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
         
         
         
        /* Accounnting */
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where buisness=12 ";// Recevable from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
         
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',sysdate(),'".$org_id."- 2','Amount recevied against invoice','".$hrid."',sysdate())";
        $glac='101010202';                
            // echo $glmqry;die;           
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',1,'".$glac."','C','".$amt."','Paid against invoice','".$hrid."',sysdate())";
               //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',2,'".$glno."','D','".$amt."','Paid against invoice','".$hrid."',sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
        
     //echo $qry; die;
        if ($conn->connect_error) 
        {
            echo "Connection failed: " . $conn->connect_error;
        }
    
        if ($conn->query($qry) == TRUE) 
        {
               // header("Location: ".$hostpath."/collectionList.php?&mod=3");
              $response = array(
                        "msg" => "Amount of ".$amt." against  Invoice of '".$inv_id."'  has been Paid",
	                    );
	                echo json_encode($response);
        } 
        else 
        {
             $err="Error: " . $qry . "<br>" . $conn->error;
             $response = array(
                "msg" =>$err,
	            );
	            echo json_encode($response);
        }
     
   }
    
    
    
    $conn->close();
//}
?>