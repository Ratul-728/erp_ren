<?php
session_start();
require "conn.php";
include_once('email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('phpmailer/PHPMailerAutoload.php');
include_once('../rak_framework/fetch.php');
require_once("../rak_framework/edit.php");
require_once("../rak_framework/log.php");

//ini_set('display_errors', 1);
//echo "<pre>";print_r($_REQUEST);echo "</pre>";die;
$hrid = $_SESSION['user'];


if($_SESSION['user']){

//print_r($_SESSION);die;
 
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/soitem.php?res=01&msg='New Entry'&id=''&mod=3");
}
else if ( isset( $_POST['copy'] ) ) {
      $srid= $_REQUEST['serid']; 
     // echo $srid;die;
      header("Location: ".$hostpath."/inv_soitem.php?res=05&msg='Copy Entry'&id='".$srid."'&mod=3");
}
else
{
	
	
	$orsttype = $_REQUEST['orsttype'];
	//echo $orsttype ;die;
		//find order status based on which button clicked on
		switch ($_REQUEST['postaction'] ){
			
			case 'Save as Draft':
			$orderstatus = 1;
			break;

			case 'Update':
			//if backorder edited and pressed update;
			$orderstatus = ($orsttype == 1)?1:$orsttype;
			break;
				
			case 'Book':
			$orderstatus = 9;
			break;
				
			case 'Confirm':
			$orderstatus = 3;
			break;					
				
				
		}	
     $errflag=0;
     $poid=0;
   // if ( isset( $_POST['add'] ) || isset( $_POST['addprint'] )){
	if($_REQUEST['mode'] == 1)
	{ 
		$poid = getFormatedUniqueID('soitem','id','OI-',6,"0");
	    $tot_amt=0;
		$tot_otc=0;
		$totvat=0;
		$totait=0;
		
        $item = $_POST['itemName'];
        $vatarr = $_POST['vat']; //vat vat[];
      	$vatRate = ($vatarr)?$vatarr:0;
        $msu = $_POST['measureUnit'];
        $oqty = $_POST['quantity_otc']; 		//qty
		
		$orQty = $_POST['quantity_otc'];
          //$oqtym = $_POST['quantity_mrc'];
        $unpo = $_POST['unitprice_otc'];  		//Price
		$price = ($unpo)?$unpo:0;
        $prdprice = $_POST['prodprice'];
        $curr_nm = $_POST['curr'];
        $dscr = $_POST['details']; 
        
		$dscnt = $_POST['discnt']; // discnt[] discount rate;
		$dcntrate = ($dscnt)?$dscnt:0;
        
		$dscnttot = $_POST['unittotal'];  //hidden unittotal[] | unittotal1[] field | discounted total;
		
        $deliveryamt = $_POST["deliveryamt"]; if($deliveryamt == '') $deliveryamt = 0;
        
		$cost=0;
		$cmbstore=1;
        
		$po_dt= $_REQUEST['po_dt'];
        $invmn= substr($_REQUEST['po_dt'],3,2);
        $invyr= substr($_REQUEST['po_dt'],6,4);		
	    $invdt= $_REQUEST['po_dt'];
		$invno = getFormatedUniqueID('invoice','id','INV-',6,"0");
        $cost=0;
        if (is_array($item))
        {
		    for ($i=0;$i<count($item);$i++)
            {
                $itmsl=$i+1;
				$itmmnm=$item[$i];
				$descr=$dscr[$i];
				$mu=$msu[$i];
				$qty=$oqty[$i];
				$qtym=$oqtym[$i];
				$upo=$unpo[$i]; 
				$upm=$unpm[$i]; 
				$currnm=$curr_nm[$i];
				$itmvat=$vat[$i];//$itmait=$ait[$i];  
                $productprice=$prdprice[$i]; 
				$disc = ($dscnt[$i])?$dscnt[$i]:0; 
				$disctot= ($dscnttot[$i])?$dscnttot[$i]:0; 
                if($upo==''){$upo=0;}
                if($upm==''){$upm=0;}
                $amt=($qty*$upo)+($qtym*$upm);
                $tot_amt=$tot_amt+$disctot;
                $tot_otc=$tot_otc+($qty*$upo);
                $vatrt=$vatarr[$i];
                $vata = $vatarr[$i]*($disctot)*0.01;
                $invamt=$tot_otc+$vata+$aita-$disctot;
                $totvat=$totvat+$vata;
				
				//new subtotal
				$unitTotal = $price[$i]*$orQty[$i];
				$discountAmount = ($unitTotal*$dcntrate[$i])/100;
				$AmountWithDiscount = $unitTotal - $discountAmount;
				$vatAmount = ($AmountWithDiscount*$vatRate[$i])/100;
				$subtotal = $subtotal+ ($AmountWithDiscount+$vatAmount);
				//end new subtotal
              
                $qryitm = "SELECT cost  FROM `item` where id=$itmmnm order by name";
                $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) { $prdcost=$rowitm["cost"];}}
                
                $itqry="insert into soitemdetails( `socode`,`sosl`, `productid`,`remarks`, `mu`, `qty`, `deliveredqty`, `dueqty`,vatrate,`vat`, `otc`,aitrate, `ait`,cost,discountrate,discounttot,`currency`, `makeby`, `makedt`)
                        values( '".$poid."','".$itmsl."','".$itmmnm."','".$descr."','".$mu."','".$qty."','0','".$qty."','".$vatrt."','".$vata."','".$upo."','0','0',".$prdcost.",".$disc.",".$disctot.",'".$currnm."','".$hrid."',SYSDATE())";
                if ($conn->query($itqry) == TRUE) { $err="SOItem added successfully";  }
                
                if($qty!='')
                {
                    $qryinvdet="INSERT INTO `invoicedetails`( `socode`,`invoiceno`, `sosl`, `billtype`, `invoicemoth`, `invoiceyr`, `invoicedt`, `product`, `qty`, `amount`,discountrate,discounttot,vat,ait,`currency`, `makeby`, `makedt`) 
                    values('".$poid."','".$invno."','".$itmsl."',1,'".$invmn."','".$invyr."',STR_TO_DATE('".$invdt."', '%d/%m/%Y'),'".$itmmnm."','".$qty."','".$upo."',".$disc.",".$disctot.",'".$vata."','0','".$currnm."','".$hrid."',sysdate())";
                    // echo $qryinvdet;die;
                    if ($conn->query($qryinvdet) == TRUE) { $err="invoice added successfully";  }
                }
		   		// Call Stock process
				 require('inc_order_stock_process.php');
            } // for ($i=0;$i<count($item);$i++)
			
        } //if (is_array($item))
           
      
        $sup_id= $_REQUEST['cmbsupnm']; 
	    $po_dt= $_REQUEST['po_dt']; 
	    $totamt= $tot_amt;
        $vat= $totvat; 
	    $tax= 0; 
	    $invoice_amount= $tot_amt+$totvat+$deliveryamt; 
		
		$discntnt= ($_REQUEST['discntnt'])?$_REQUEST['discntnt']:0;
		
		$adjustment = $discntnt;		
		
		//rak
		$newInvoiceAmount = ($subtotal-$adjustment)+$deliveryamt;
	
        $delivery_dt= '0000-00-00'; 
	    $deliveryby='';  
	    $acc_mgr=$_REQUEST['cmbhrmgr'];
        $srctp=1;
	    $st= $_REQUEST['cmbsostat']; 
	    $det= $_REQUEST['details'];
	    $poc= $hrid;//$_REQUEST['cmbpoc'];	//Account Manager in the form
	    $oldso= $_REQUEST['oldso_id'];
        $custp = 2;    
	    $org = $_POST['org_id']; 
	    $effective_dt= $_REQUEST['effect_dt']; 
	    $term_dt= $_REQUEST['term_dt'];
	    $cmbtermc= $_REQUEST['cmbtermc'];
	
        if($term_dt==''){$term_dt='';}  
		$mrc_dt= $_REQUEST['mrc_dt'];   
		if($mrc_dt==''){$mrc_dt='';} 

	    
        $qry="insert into soitem(`orderstatus`, `socode`,`customertp`,`organization`,`srctype`, `customer`, `orderdate`, `deliveryby`, `deliveryamt`,`adjustment`, `accmanager`, `vat`, `tax`, `invoiceamount`, `makeby`, `makedt`, `lastdeliverydt`, `status`,`remarks`,`poc`) 
        values($orderstatus,'".$poid."','".$custp."','".$org."','".$srctp."','".$sup_id."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'), '".$deliveryby."',".$deliveryamt.",".$discntnt.",'".$hrid."','".$vat."','".$tax."','".$newInvoiceAmount."','".$hrid."',sysdate(),sysdate(),'".$st."','".$det."','".$poc."')";
        $err="SO created successfully"; 
        $invamt=$newInvoiceAmount;
       
        $qryinv="INSERT INTO `invoice`( `invoiceno`, `invoicedt`,`invyr`, `invoicemonth`, `soid`, `organization`, `invoiceamt`,amount_bdt,adjustment, `paidamount`, `dueamount`,  `invoiceSt`, `paymentSt`, `makeby`,`makedt`) 
        values('".$invno."',STR_TO_DATE('".$invdt."', '%d/%m/%Y'),'".$invyr."','".$invmn."','".$poid."','".$org."','".$newInvoiceAmount."','".$newInvoiceAmount."',".$discntnt.",0,'".$newInvoiceAmount."',1,1,'".$hrid."',sysdate())";
        // echo $qryinv;die;
        if ($conn->query($qryinv) == TRUE) { $err="Invoice created successfully";  }
         
        $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,`remarks`,`makeby`,`makedt`)
            values(STR_TO_DATE('".$invdt."', '%d/%m/%Y'),'$org','Auto','na','$invno','$invamt','Invoice Generated','$hrid',sysdate())";
        //echo $itqry;die;
        if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
         
        $note="Service Order: SO ID:".$poid." with amount ".$newInvoiceAmount." is created by USER";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$sup_id."',8,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,'".$newInvoiceAmount."','".$hrid."',sysdate())" ;
        //echo $qry_othr;die;
         if ($conn->query($qry_othr) == TRUE) { $err="Order updated successfully";  }  
         
        /* Accounnting */
        $vouch = 10000000000; 
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
                            VALUES ('".$vouch."',1,'".$glnoinv."','D','".$newInvoiceAmount."','Sales Order','".$hrid."',sysdate())
                            ,('".$vouch."',2,'".$glno."','C','".$tot_amt."','SO','".$hrid."',sysdate())
                            ,('".$vouch."',3,'203020102','C','".$totvat."','SO','".$hrid."',sysdate())
                            ,('".$vouch."',4,'301020204','C','".$deliveryamt."','SO','".$hrid."',sysdate())";
                                        //echo  $glqry1;die;// ,('".$vouch."',4,'203020103','C','".$totait."','SO','".$hrid."',sysdate())              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
       /* Accounnting */
       
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
		
		            Order Created Successfully.<br>
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
		
		            One Order Created Successfully.<br>
		            Invoice Number: $invno.<br><br>
		            ";
		$subject = 'SERVICE ORDER';
		sendBitFlowMail($name_to,$email_to, $subject,$message);
    }
    else if($_REQUEST['mode'] == 2)
	{ 
        $poid= $_REQUEST['po_id'];
        $item = $_POST['itemName'];
        $vatarr = $_POST['vat'];		//vat vat[];
		$vatRate = ($vatarr)?$vatarr:0;

        $msu = $_POST['measureUnit'];
        $oqty = $_POST['quantity_otc'];		//qty
		
		$orQty = $_POST['quantity_otc'];

          //$oqtym = $_POST['quantity_mrc'];
        $unpo = $_POST['unitprice_otc'];  //Price
		
		$price = ($unpo)?$unpo:0;

        $prdprice = $_POST['prodprice'];
        $curr_nm = $_POST['curr'];
        $dscr = $_POST['details']; 

        $dscnt = $_POST['discnt'];  // discnt[] discount rate;
		$dcntrate = ($dscnt)?$dscnt:0;
		
		
		
        $dscnttot = $_POST['unittotal'];   //hidden unittotal[] | unittotal1[] field | discounted total;

        $deliveryamt = $_POST["deliveryamt"]; if($deliveryamt == '') $deliveryamt = 0;
        $cost=0;$cmbstore=1;
        $po_dt= $_REQUEST['po_dt'];
        $invmn= substr($_REQUEST['po_dt'],3,2);
        $invyr= substr($_REQUEST['po_dt'],6,4);		
	    $invdt= $_REQUEST['po_dt'];
		//$invno = getFormatedUniqueID('invoice','id','INV-',6,"0");
		$invno = fetchByID('invoice','soid',$poid,'invoiceno');
        $cost=0;
        //print_r($item);die;
        $oldinvamt = fetchByID('soitem','soid',$poid,'invoiceamount');
        $oldvatamt = fetchByID('soitem','soid',$poid,'vat');
        $olddelvamt = fetchByID('soitem','soid',$poid,'deliveryamt');
        
        $delqry="delete from soitemdetails where socode='".$poid."'";
        if ($conn->query($delqry) == TRUE) { $err="SODetails deleted successfully";  }
        
        $saldelsqry="delete from  invoicedetails where `socode`='".$poid."'";
        //echo $qry;die;
        if ($conn->query($saldelsqry) == TRUE) { $err="SO sales created successfully";  }
        //echo count($item);die;
        if (is_array($item))
        {
            //echo count($item);die;
            for ($i=0;$i<count($item);$i++)
            {
   	            $itmsl=$i+1;
				$itmmnm=$item[$i];
				$descr=$dscr[$i];
				$mu=$msu[$i];
				$qty=$oqty[$i];
				$qtym=$oqtym[$i];
				$upo=$unpo[$i]; 
				$upm=$unpm[$i]; 
				$currnm=$curr_nm[$i];
				$itmvat=$vat[$i];//$itmait=$ait[$i];  
                $productprice=$prdprice[$i]; 
				$disc = ($dscnt[$i])?$dscnt[$i]:0; 
				$disctot= ($dscnttot[$i])?$dscnttot[$i]:0; 
                if($upo==''){$upo=0;}
                if($upm==''){$upm=0;}
                $amt=($qty*$upo)+($qtym*$upm);
                $tot_amt=$tot_amt+$disctot;
                $tot_otc=$tot_otc+($qty*$upo);
                $vatrt=$vatarr[$i];
                $vata = $vatarr[$i]*($disctot)*0.01;
                $invamt=$tot_otc+$vata+$aita-$disctot;
                $totvat=$totvat+$vata;



				//new subtotal
				$unitTotal = $price[$i]*$orQty[$i];
				$discountAmount = ($unitTotal*$dcntrate[$i])/100;
				$AmountWithDiscount = $unitTotal - $discountAmount;
				$vatAmount = ($AmountWithDiscount*$vatRate[$i])/100;
				$subtotal = $subtotal+ ($AmountWithDiscount+$vatAmount);
				//end new subtotal	   	        


   	            $qryitm = "SELECT cost  FROM `item` where id=$itmmnm order by name";
                $resultitm = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) { $prdcost=$rowitm["cost"];}}
            
                $itqry="insert into soitemdetails( `socode`,`sosl`, `productid`,`remarks`, `mu`, `qty`, `deliveredqty`, `dueqty`,vatrate,`vat`, `otc`,aitrate, `ait`,cost,discountrate,discounttot,`currency`, `makeby`, `makedt`)
                    values( '".$poid."','".$itmsl."','".$itmmnm."','".$descr."','".$mu."','".$qty."','0','".$qty."','".$vatrt."','".$vata."','".$upo."','0','0',".$prdcost.",".$disc.",".$disctot.",'".$currnm."','".$hrid."',SYSDATE())";
                if ($conn->query($itqry) == TRUE) { $err="SOItem added successfully";  }
            
                if($qty!='')
                {
                    $qryinvdet="INSERT INTO `invoicedetails`( `socode`,`invoiceno`, `sosl`, `billtype`, `invoicemoth`, `invoiceyr`, `invoicedt`, `product`, `qty`, `amount`,discountrate,discounttot,vat,ait,`currency`, `makeby`, `makedt`) 
                    values('".$poid."','".$invno."','".$itmsl."',1,'".$invmn."','".$invyr."',STR_TO_DATE('".$invdt."', '%d/%m/%Y'),'".$itmmnm."','".$qty."','".$upo."',".$disc.",".$disctot.",'".$vata."','0','".$currnm."','".$hrid."',sysdate())";
                    // echo $qryinvdet;die;
                    if ($conn->query($qryinvdet) == TRUE) { $err="invoice added successfully";  }
                }
	   		// Call Stock process
			    require('inc_order_stock_process.php');
	   	       //	$allqry .= $qryinvdet."<br>";
            } 
        }
        
		$sup_id= $_REQUEST['cmbsupnm']; 
	    $po_dt= $_REQUEST['po_dt']; 
	    $totamt= $tot_amt;
        $vat= $totvat; 
	    $tax= 0; 
	    $invoice_amount= $tot_amt+$totvat+$deliveryamt; 
		
		$discntnt= ($_REQUEST['discntnt'])?$_REQUEST['discntnt']:0;
		
		$adjustment = $discntnt;
		
		//rak
		$newInvoiceAmount = ($subtotal-$adjustment)+$deliveryamt;

        $delivery_dt= '0000-00-00'; 
	    $deliveryby='';  
	    $acc_mgr=$_REQUEST['cmbhrmgr'];
        $srctp=1;
	    $st= $_REQUEST['cmbsostat']; 
	    $det= $_REQUEST['details'];
	    $poc= $hrid;//$_REQUEST['cmbpoc'];	//Account Manager in the form
	    $oldso= $_REQUEST['oldso_id'];
        $custp = 2;    
	    $org = $_POST['org_id']; 
	    $effective_dt= $_REQUEST['effect_dt']; 
	    $term_dt= $_REQUEST['term_dt'];
	    $cmbtermc= $_REQUEST['cmbtermc'];
	
        if($term_dt==''){$term_dt='';}  
		$mrc_dt= $_REQUEST['mrc_dt'];   
		if($mrc_dt==''){$mrc_dt='';} 
		$discntnt= ($_REQUEST['discntnt'])?$_REQUEST['discntnt']:0;
		
		/*  INVOICE UPDATE */
		$invamt=$invoice_amount;
    	$invupdatequery='UPDATE invoice SET invoicedt = STR_TO_DATE("'.$po_dt.'", "%d/%m/%Y"),invyr = '.$invyr.',invoicemonth = '.$invmn.',organization = '.$org.',invoiceamt = '.$newInvoiceAmount.',
		                adjustment = '.$discntnt.',amount_bdt	= '.$newInvoiceAmount .' WHERE soid="'.$poid.'"';
		if ($conn->query($invupdatequery) == TRUE) { $err="Invoice UPDATED successfully";}
		/* END INVOICE UPDATE */
		
        $qry="update soitem set `orderstatus`=$orderstatus, `srctype`='".$srctp."',`customertp`='".$custp."',`organization`='".$org."',`customer`='".$sup_id."',`orderdate`=STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),`accmanager`='".$acc_mgr."' ,`deliveryamt`='".$deliveryamt."' 
            ,`remarks`='".$det."',`status`='".$st."' ,`oldsocode`='".$oldso."',`adjustment`='".$discntnt."',`makedt`=sysdate(),`lastdeliverydt`=sysdate()
            ,`vat`='".$vat."' ,`tax`='".$tax."',`invoiceamount`='".$newInvoiceAmount ."' where `socode`='".$poid."'";
            $err="SO updated successfully";
            
        $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,`remarks`,`makeby`,`makedt`)
                    values(STR_TO_DATE('".$invdt."', '%d/%m/%Y'),'$org','Auto','na','$invno','$invamt','Invoice re Generated','$hrid',sysdate())";
        //echo $itqry;die;
        if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }    
            
       
        $note="Service Order: SO ID".$poid." with amount ".$newInvoiceAmount." was updated by USER";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$sup_id."',8,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,'".$newInvoiceAmount."','".$hrid."',sysdate())" ;
        //echo $qry_othr;die;
         if ($conn->query($qry_othr) == TRUE) { $err="Order updated successfully";  } 
         
         /* Accounnting */
        $vouch = 10000000000; 
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
                                        VALUES ('".$vouch."',1,'".$glnoinv."','D','".$newInvoiceAmount."','Sales Order','".$hrid."',sysdate())
                                        ,('".$vouch."',2,'".$glno."','C','".$tot_amt."','SO','".$hrid."',sysdate())
                                        ,('".$vouch."',3,'203020102','C','".$totvat."','SO','".$hrid."',sysdate())
                                        ,('".$vouch."',4,'301020204','C','".$deliveryamt."','SO','".$hrid."',sysdate())
                                        ,('".$vouch."',5,'".$glnoinv."','C','".$oldinvamt."','Sales Order','".$hrid."',sysdate())
                                        ,('".$vouch."',6,'".$glno."','D','".($oldinvamt-$oldvatamt-$olddelvamt)."','SO','".$hrid."',sysdate())
                                        ,('".$vouch."',7,'203020102','D','".$oldvatamt."','SO','".$hrid."',sysdate())
                                        ,('".$vouch."',8,'301020204','D','".$olddelvamt."','SO','".$hrid."',sysdate())";
                                        //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
       /* Accounnting */
         
    }
    else
    {
        $errflag=1;
        $err="SO Code already Exist"; 
    }
}

//update started

    //if ( isset( $_POST['update'] ) ) {


        if ($conn->query($qry) == TRUE) 
        {
		
			
			if($_REQUEST['postaction']=='Update'){
				//1 job: return to list;
				$err = "Order updated successfully";
				header("Location: ".$hostpath."/inv_soitemList.php?res=1&msg=".$err."&id=".$poid."&mod=3&pg=1&changedid=".$poid);
			}			
			
			if($_REQUEST['postaction']=='Save as Draft'){
				//1 job: return to list;
				$err = "Order saved successfully";
				header("Location: ".$hostpath."/inv_soitemList.php?res=1&msg=".$err."&id=".$poid."&mod=3&pg=1&changedid=".$poid);
			}
			if($_REQUEST['postaction']=='Book'){
				//1 job: return to list;
				$err = "Order booked successfully";
				header("Location: ".$hostpath."/inv_soitemList.php?res=1&msg=".$err."&id=".$poid."&mod=3&pg=1&changedid=".$poid);
			}			
			
			if($_REQUEST['postaction']=='Confirm'){
				
				//backorder
				$condition = 'socode = "' . $poid . '"';
				$totalBackORder = fetchTotalSum('soitemdetails','backorderedqty',$condition);
					
				//make order status 11 for backorder
				 bitLog("Order ID:".$poid." | Product ID:".$itmmnm." freeqty: ".$freeqty." backorderedqty: ".$backorderqty);
				if($backorderqty){
					$whereqryOST = 'socode = "' . $poid . '" ';
					if( updateByID( 'soitem', 'orderstatus', 11, $whereqryOST ) ) {$msg = "Back order status assigned";	}	
				}
				
				//1 job: return to list;
				$err = "Order booked successfully";
				header("Location: ".$hostpath."/invoice.php?invid=".$invno."&mod=3");
			}				
			
			

        } 
        else
        {
             $err="Error: " . $qry . "<br>" . $conn->error;
			echo $err;die;
			
              //header("Location: ".$hostpath."/inv_soitemList.php?res=2&msg=".$err."&id=''&mod=3");
        }
  
    $conn->close();
	
	}else{
	header("Location: " . $hostpath . "/hr.php");
}

?>