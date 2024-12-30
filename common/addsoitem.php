<?php
require "conn.php";

include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');
        
 $hrid = $_POST['usrid'];
 
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/soitem.php?res=01&msg='New Entry'&id=''&mod=3");
}
else if ( isset( $_POST['copy'] ) ) {
      $srid= $_REQUEST['serid']; 
     // echo $srid;die;
      header("Location: ".$hostpath."/soitem.php?res=05&msg='Copy Entry'&id='".$srid."'&mod=3");
}
else
{
     $errflag=0;
     $poid=0;
    if ( isset( $_POST['add'] ) || isset( $_POST['addprint'] ) ) 
    {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
      $poid= $_REQUEST['po_id'];
      $tot_amt=0;$tot_otc=0;
      $item = $_POST['itemName'];
      $vat = $_POST['vat'];
      $ait = $_POST['ait'];
      
      $msu = $_POST['measureUnit'];
      $oqty = $_POST['quantity_otc'];
      $oqtym = $_POST['quantity_mrc'];
      $unpo = $_POST['unitprice_otc']; 
      $unpm = $_POST['unitprice_mrc'];
      $curr_nm = $_POST['curr'];
      $dscr = $_POST['remarks']; 
      $deliveryamt = $_POST["deliveryamt"]; if($deliveryamt == '') $deliveryamt = 0;
    
      $cost=0;$cmbstore=1;
       //$hr_id=1;
        
   // echo $poid;die;
    $duplicheckqry="select `socode` from soitem where `socode`='".$poid."'";
   // echo $duplicheckqry;die;
    $resduplicheck = $conn->query($duplicheckqry); 
        if ($resduplicheck->num_rows == 0)
        {
            $invmn= substr($_REQUEST['effect_dt'],3,2);
            $invyr= substr($_REQUEST['effect_dt'],6,4);
            $invdt= $invyr.'-'.$invmn.'-01';
            //echo $invdt;
            $qryinvno="select  max(substring(invoiceno,7,5)) FROM invoice where substring(invoiceno,1,4)='".$invyr."' and substring(invoiceno,5,2)='".$invmn."'";
            $resinv = $conn->query($qryinvno); 
            if ($resinv->num_rows == 0)
            { //concat(pyr,pmn,lpad((cast(maxinv AS UNSIGNED)+1),5,'0'));
                 while($row1 = $resinv->fetch_assoc())
                    { 
                        $invmx= str_pad(($row1["max"]+1), 5, "0", STR_PAD_LEFT);  
                    }
                     $invno=$invyr.$invmn.$invmx;
            }
            else
            {
                $invno=$invyr.$invmn.'00001';
            }
           //echo $invno;die;
           
           $cost=0;
            if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $itmsl=$i+1;$itmmnm=$item[$i];$descr=$dscr[$i];$mu=$msu[$i];$qty=$oqty[$i];$qtym=$oqtym[$i];$upo=$unpo[$i]; $upm=$unpm[$i]; $currnm=$curr_nm[$i];$itmvat=$vat[$i];$itmait=$ait[$i];  
                        //if($descr==''){$descr='NULL';} if($mu==''){$mu='NULL';} if($qty==''){$qty='NULL';} if($qtym==''){$qtym='NULL';} if($currnm==''){$currnm='1';}
                        if($upo==''){$upo=0;}
                        if($upm==''){$upm=0;}
                        $amt=($qty*$upo)+($qtym*$upm);
                        $tot_amt=$tot_amt+$amt;
                        $tot_otc=$tot_otc+($qty*$upo);
                        
                        
                        
                        $itqry="insert into soitemdetails( `socode`,`sosl`, `productid`,vat,ait,`remarks`, `mu`, `qty`,`qtymrc`, `otc`, `mrc`,`currency`, `makeby`, `makedt`)
                                values( '".$poid."','".$itmsl."','".$itmmnm."','".$itmvat."','".$itmait."','".$descr."','".$mu."','".$qty."','".$qtym."','".$upo."','".$upm."','".$currnm."','".$hrid."',SYSDATE())";
                         //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="SOItem added successfully";  }
                         
                          $sql = "CALL raw_in_out('".$itmmnm."','".$qty."','".$cost."','".$cmbstore."','o')";
                            //echo $sql;die;
                            if ($conn->query($sql) == TRUE) { $err="stock added successfully";  }
                            
                           /*Auto invoice
                            if($qty!='NULL')
                            {
                            $qryinvdet="INSERT INTO `invoicedetails`( `socode`,`invoiceno`, `sosl`, `billtype`, `invoicemoth`, `invoiceyr`, `invoicedt`, `product`, `qty`, `amount`,`currency`, `makeby`, `makedt`) 
                            values('".$poid."',".$invno.",".$itmsl.",1,'".$invmn."','".$invyr."','".$invdt."',".$itmmnm.",".$qty.",".$upo.",".$currnm.",".$hrid.",sysdate())";
                             if ($conn->query($qryinvdet) == TRUE) { $err="invoice added successfully";  }
                            }
                            */
                            
                        /*$getrate="SELECT rate FROM product where id=$itmmnm";// Invoice recevable
                        $resultrate = $conn->query($getrate);
                        if ($resultrate->num_rows > 0) {while ($rowrate = $resultrate->fetch_assoc()) { $rate = $rowrate["rate"];}}    
                        $cost=$cost+($rate*$qty);  */  
                    }
            }
               
          // echo "add";die;
         
          
            $sup_id= $_REQUEST['cmbsupnm']; $po_dt= $_REQUEST['po_dt']; $totamt= $tot_amt;
            $vat= $tot_amt*0; $tax= $tot_amt*0; $invoice_amount= $tot_amt+$vat+$tax; //$delivery_dt= $_REQUEST['delivery_dt']; $deliveryby=$_REQUEST['cmbhr']; 
            $delivery_dt= ''; $deliveryby='';  $acc_mgr='';//$_REQUEST['cmbhrmgr'];
             $srctp=1;$st= $_REQUEST['cmbsostat']; $det= $_REQUEST['details'];$poc= $_REQUEST['cmbpoc'];$oldso= $_REQUEST['oldso_id'];
            $custp = 2;    $org = $_POST['org_id']; $effective_dt= $_REQUEST['effect_dt']; $term_dt= $_REQUEST['term_dt'];$cmbtermc= $_REQUEST['cmbtermc'];
           // if($term_dt==''){$term_dt=='NULL';}  $mrc_dt= $_REQUEST['mrc_dt'];   if($mrc_dt==''){$mrc_dt=='NULL';}
            
            //if($delivery_dt==''){$delivery_dt='NULL';} if($deliveryby==''){$deliveryby='NULL';} if($term_dt==''){$term_dt='NULL';} if($cmbtermc==''){$cmbtermc='NULL';} if($st==''){$st=0;}if($org==''){$org='NULL';}if($det==''){$det='NULL';} if($poc==''){$poc='NULL';} if($oldso==''){$oldso='NULL';}  
            
            $qry="insert into soitem( `socode`,`customertp`,`organization`,`srctype`, `customer`, `orderdate`, `deliverydt`, `deliveryby`,`deliveryamt`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`,`terminationDate`,`terminationcause`, `status`,`effectivedate`,`remarks`,`poc`,`oldsocode`,mrcdt) 
            values('".$poid."','".$custp."','".$org."','".$srctp."','".$sup_id."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),STR_TO_DATE('".$delivery_dt."', '%d/%m/%Y'),'".$deliveryby."','".$deliveryamt."','".$acc_mgr."','".$vat."','".$tax."','".$invoice_amount."','".$hrid."',sysdate(),STR_TO_DATE('".$term_dt."', '%d/%m/%Y'),'".$cmbtermc."','".$st."',STR_TO_DATE('".$effective_dt."', '%d/%m/%Y'),'".$det."','".$poc."','".$oldso."',STR_TO_DATE('".$mrc_dt."', '%d/%m/%Y'))";
            $err="SO created successfully"; 
         //echo $qry;die;
         
          
           /*auto invoice
             if($tot_otc>0)
            {
            $qryinv="INSERT INTO `invoice`( `invoiceno`, `invoicedt`,`invyr`, `invoicemonth`, `soid`, `organization`, `invoiceamt`, `paidamount`, `dueamount`,  `invoiceSt`, `paymentSt`, `makeby`,`makedt`) 
            values('".$invno."','".$invdt."','".$invyr."','".$invmn."','".$poid."','".$org."',".$tot_otc.",0,".$tot_otc.",1,1,".$hrid.",sysdate())";
            // echo $qryinv;die;
             if ($conn->query($qryinv) == TRUE) { $err="Invoice created successfully";  }
            }
            
             $cusqry="update contact set currbal=currbal-".$invoice_amount." where id=".$sup_id." and status=1";
                //echo $qry;die;
              if ($conn->query($cusqry) == TRUE) { $err="SO created successfully";  }
              */
             // $salsqry="insert into rpt_sales_so(`socode`, `contType`, `cus_id`, `cus_nm`, `orderdate`, `yr`, `mnth`, `da`, `hrid`, `hrName`, `itmid`, `itmnm`, `otc`, `mrc`, `stage`, `prob`, `itm_cat`, `size`, `pattern`, `orgn`) SELECT a.`socode`,'Customer'  , a.`customer`,d.`name` , a.`effectivedate` ,DATE_FORMAT(a.`effectivedate`, '%Y'),DATE_FORMAT(a.`effectivedate`, '%m'),DATE_FORMAT(a.`effectivedate`, '%d'), a.`accmanager` ,e.`hrName`,b.`productid` ,c.`name`,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2),round((IFNULL(b.`mrc`,0)*IFNULL(`qtymrc`,0)),2),'Order Placed','100%' ,f.`name`,c.size,g.`name`,org.`name` FROM `soitem` a left join `soitemdetails` b on a.`socode`=b.`socode` left join `item` c on b.`productid`=c.`id` left join `contact` d on a.`customer`=d.`id`  left join `hr` e on a.`accmanager`=e.`id` left join `itmCat` f  on c.`catagory`=f.`id`     left join `pattern` g on c.`pattern`=g.`id`    left join `organization` org on d.`organization`=org.`id`   where  a.`socode`='".$poid."' ";
                //echo $salsqry;die;
              //if ($conn->query($salsqry) == TRUE) { $err="SO sales created successfully";  }
              
              
            /*auto invoice
            $note="Service Order: SO ID".$poid." with amount ".$invoice_amount." was issued by USER";
            $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values(".$sup_id.",8,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$invoice_amount.",".$hrid.",sysdate())" ;
             if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
             */
             
             
             
        /* Accounnting */
         $vouch = 10000000000; 
         //$gain=$totamt-$vat;
         $getgl="SELECT mappedgl FROM glmapping where id=10 ";// Saleable products from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
            
        $getglinv="SELECT mappedgl FROM glmapping where id=14 ";// Invoice recevable
         $resultglinv = $conn->query($getglinv);
            if ($resultglinv->num_rows > 0) {while ($rowglinv = $resultglinv->fetch_assoc()) { $glnoinv = $rowglinv["mappedgl"];}}    
         
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),'".$poid."-".$sup_id."','".$det."','".$hrid."',sysdate())";
                        
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",1,".$glnoinv.",'D',".$invoice_amount.",'Sales Order',".$hrid.",sysdate())";
                                            //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",2,".$glno.",'C',".$tot_amt.",'SO',".$hrid.",sysdate())";
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry3="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",3,'203020102','C',".$vat.",'SO',".$hrid.",sysdate())";
                             
            if ($conn->query($glqry3) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
           /* $glqry4="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",4,'301020102','C',".$gain.",'SO',".$hrid.",sysdate())";
                             
            if ($conn->query($glqry4) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}*/
        }
        
        
         
               /* Accounnting */ 
               
               $note="Service Order: SO ID".$poid." with amount ".$invoice_amount." was updated";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$sup_id."',5,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,'".$invoice_amount."','".$hrid."',sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
         
         $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$org."',5,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,'".$invoice_amount."','".$hrid."',sysdate())" ;
         $conn->query($qry_othr);
        
        
                //Mail to Organization email
                $qrymailinfo = "SELECT `name`,`email` FROM `organization` WHERE id = ".$org;
                $resultmailinfo = $conn->query($qrymailinfo);
                while ($rowmailinfo = $resultmailinfo->fetch_assoc()) {
                    $orgnm =$rowmailinfo["name"];
                    $orgmail = $rowmailinfo["email"];
                }
                
                $name_to = $orgnm;
        		$email_to = $orgmail;
        		$message = "Dear $orgnm, <br><br>
        		
        		            Service Order Created Successfully.<br>
        		            Your Invoice Number: $invno.<br><br>
        		            ";
        		$subject = 'SERVICE ORDER';
        		sendBitFlowMail($name_to,$email_to, $subject,$message);
        		
        		//Mail to Admin email
                $qrymailinfo = "SELECT `companynm`,`email` FROM `sitesettings` WHERE id = 1";
                $resultmailinfo = $conn->query($qrymailinfo);
                while ($rowmailinfo = $resultmailinfo->fetch_assoc()) {
                    $adminnm =$rowmailinfo["companynm"];
                    $adminmail = $rowmailinfo["email"];
                }
                
                $name_to = $adminnm;
        		$email_to = $adminmail;
        		$message = "Dear $adminnm, <br><br>
        		
        		            One Service Order Created Successfully.<br>
        		            Invoice Number: $invno.<br><br>
        		            ";
        		$subject = 'SERVICE ORDER';
        		sendBitFlowMail($name_to,$email_to, $subject,$message);
        		
        		
        }
        else
        {
            $errflag=1;
            $err="SO Code already Exist"; 
        }
    }
    if ( isset( $_POST['update'] ) ) {
        $poid= $_REQUEST['po_id']; 
        
         $delqry="delete from soitemdetails where socode='".$poid."'";
         
        if ($conn->query($delqry) == TRUE) { $err="SODetails deleted successfully";  }
        
         $saldelsqry="delete from  rpt_sales_so where `socode`='".$poid."'";
        //echo $saldelsqry;die;
         if ($conn->query($saldelsqry) == TRUE) { $err="SO sales created successfully";  }
         
        //echo $delqry;die;
        $tot_amt=0;
        $item = $_POST['itemName'];
        $vat = $_POST['vat'];
        $ait = $_POST['ait'];
        $msu = $_POST['measureUnit'];
        $oqty = $_POST['quantity_otc'];
        $oqtym = $_POST['quantity_mrc'];
        $unpo = $_POST['unitprice_otc']; 
        $unpm = $_POST['unitprice_mrc'];
        $curr_nm = $_POST['curr'];
        $dscr = $_POST['remarks'];
        $deliveryamt = $_POST["deliveryamt"]; if($deliveryamt == '') $deliveryamt = 0;
        
        $cost=0;$cmbstore=1;
        //print_r($_POST);die;
          if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $itmsl=$i+1;$itmmnm=$item[$i];$descr=$dscr[$i];$mu=$msu[$i];$qty=$oqty[$i];$qtym=$oqtym[$i];$upo=$unpo[$i]; $upm=$unpm[$i]; $currnm=$curr_nm[$i];$itmvat=$vat[$i];$itmait=$ait[$i];  
                        //if($descr==''){$descr='NULL';} if($mu==''){$mu='NULL';} if($qty==''){$qty='NULL';} if($qtym==''){$qtym='NULL';} if($currnm==''){$currnm='1';}
                        if($upo==''){$upo=0;}
                        if($upm==''){$upm=0;}
                        $amt=($qty*$upo)+($qtym*$upm);
                        $tot_amt=$tot_amt+$amt;
                       
                        
                        $itqry="insert into soitemdetails( `socode`,`sosl`, `productid`,vat,ait,`remarks`, `mu`, `qty`,`qtymrc`, `otc`, `mrc`,`currency`, `makeby`, `makedt`)
                                values( '".$poid."','".$itmsl."','".$itmmnm."','".$itmvat."','".$itmait."','".$descr."','".$mu."','".$qty."','".$qtym."','".$upo."','".$upm."','".$currnm."','".$hrid."',SYSDATE())";
                        //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="SOItem added successfully";  }
                         
                         
                          $sql = "CALL raw_in_out('".$itmmnm."','".$qty."','".$cost."','".$cmbstore."','o')";
                            //echo $sql;die;
                            if ($conn->query($sql) == TRUE) { $err="stock added successfully";  }
                            
                    }
            }
        
        
        
        
        $sup_id= $_REQUEST['cmbsupnm'];         //if($sup_id==''){$sup_id='NULL';}
        $po_dt= $_REQUEST['po_dt'];             if($po_dt==''){$po_dt='';}else {$po_dt="STR_TO_DATE('".$po_dt."', '%d/%m/%Y')";}
        $srctp=1;                               //$srctp=$_REQUEST['cmbcontype'];
        $acc_mgr=$_REQUEST['cmbhrmgr'];         //if($acc_mgr==''){$acc_mgr='NULL';}
        $st= $_REQUEST['cmbsostat'];            //if($st==''){$st=0;}
        $custp = $_POST['cmbcontype'];          //if($custp==''){$custp=2;}
        $org = $_POST['org_id'];                //if($org==''){$org='NULL';}
        $effective_dt= $_REQUEST['effect_dt'];  if($effective_dt==''){$effective_dt='';}else {$effective_dt="STR_TO_DATE('".$effective_dt."', '%d/%m/%Y')";}
        $term_dt= $_REQUEST['term_dt'];         if($term_dt==''){$term_dt='';}else {$term_dt="STR_TO_DATE('".$term_dt."', '%d/%m/%Y')";}
        $cmbtermc= $_REQUEST['cmbtermc'];       //if($cmbtermc==''){$cmbtermc='NULL';}
        $det= $_REQUEST['details'];             //if($det==''){$det='NULL';}
        $poc= $_REQUEST['cmbpoc'];              //if($poc==''){$poc='NULL';}
        $oldso= $_REQUEST['oldso_id'];          //if($oldso==''){$oldso='NULL';}
        $mrc_dt= $_REQUEST['mrc_dt'];           if($mrc_dt==''){$mrc_dt='';}else {$mrc_dt="STR_TO_DATE('".$mrc_dt."', '%d/%m/%Y')";}
     
          //if($det==''){$det='NULL';}
        
        $qry="update soitem set `srctype`='".$srctp."',`customertp`='".$custp."',`organization`='".$org."',`customer`='".$sup_id."',`orderdate`='".$po_dt."',`accmanager`='".$acc_mgr."' ,`deliveryamt`='".$deliveryamt."' 
        ,`terminationDate`='".$term_dt."',`terminationcause`='".$cmbtermc."',`effectivedate`='".$effective_dt."',`remarks`='".$det."',`status`='".$st."',`poc`='".$poc."' ,`oldsocode`='".$oldso."',`mrcdt`='".$mrc_dt."'  where `socode`='".$poid."'";
        $err="SO updated successfully";
      //echo $qry;die;
        
       
        $note="Service Order: SO ID".$poid." with amount ".$invoice_amount." was updated";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$sup_id."',5,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,'".$invoice_amount."','".$hrid."',sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } 
         
         $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$org."',5,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,'".$invoice_amount."','".$hrid."',sysdate())" ;
         $conn->query($qry_othr);
        
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    if($errflag==0)
    {
        if ($conn->query($qry) == TRUE) 
        {
            if(isset( $_POST['addprint'] )){
                 header("Location: ".$hostpath."/invoice.php?invid=".$invno."&mod=3");
            }else{
                 header("Location: ".$hostpath."/soitemList.php?res=1&msg=".$err."&id=".$poid."&mod=3&pg=1");
            }
           
        } 
        else
        {
             $err="Error: " . $qry . "<br>" . $conn->error;
              header("Location: ".$hostpath."/soitemList.php?res=2&msg=".$err."&id=''&mod=3");
        }
    }
     else
    {
        header("Location: ".$hostpath."/soitemList.php?res=2&msg=".$err."&id=''&mod=3");
       
    }
    
    $conn->close();
}
?>