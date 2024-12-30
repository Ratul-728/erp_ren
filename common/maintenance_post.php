<?php
require "conn.php";
session_start();

include_once('../rak_framework/connection.php');
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
require_once('insert_gl.php');

//print_r($_POST);die;
$usr = $_SESSION["user"];

extract($_POST);
$errorFlag = 0;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/deal.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['add'] ) ) {
        
        $invcode = getFormatedUniqueID('service_invoice','id','MOInv-',6,"0");
        $mcode = getFormatedUniqueID('maintenance','id','MO-',6,"0");
        
        $invmn= substr($date,3,2);
        $invyr= substr($date,6,4);
        
        $qryso = "INSERT INTO `maintenance`(`code`, `do_number`, `reason`,`date`, `detail`, `inspection`, `inspection_date`, `inspection_time`, `address`, `remarks`, `fee`, `tds`, `vds`, `total`, `makeby`, `makedt`) 
                VALUES ('$mcode','$donumber','$reason',STR_TO_DATE('".$date."', '%d/%m/%Y'), '$details','$inspection',STR_TO_DATE('".$inspectiondate."', '%d/%m/%Y'),'$time','$address','$note','$fee','$tds','$vds','$total','$usr',sysdate())";
            
            //echo $qryso;die;
            if ($conn->query($qryso) == TRUE) {
                $mId = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/maintenanceList.php?res=2&mod=3&msg=".$err);
            } 
            
        $qryinv = "INSERT INTO `service_invoice`( `invoice`,`type`, `serviceorder`, `invoicedt`, `invyr`, `invmonth`,`invoiceamt`, `paidamt`,`dueamt`,`makeby`, `makedt`) 
                                        VALUES ('$invcode','2','$mcode',STR_TO_DATE('".$date."', '%d/%m/%Y'),'$invyr','$invmn','$total','0','$total','$usr',sysdate())";
            
            //echo $qryDeliveryMain;die;
            if ($conn->query($qryinv) == TRUE) {
                $invId = $conn->insert_id;
            }else{
                $err = 'Something went wrong!';
                header("Location: ".$hostpath."/maintenanceList.php?res=2&mod=3&msg=".$err);
            } 
        $grandTotal = 0;$totalvds=0;$totaltds=0;
         if (is_array($product))
            {
                for ($i=0;$i<count($product);$i++)
                    {
                        $prod = $product[$i]; 
                     
                        $inqrysod="INSERT INTO `maintenance_details`(`code`, `product`) VALUES 
                                                                ('$mcode','$prod')";
                        $conn->query($inqrysod);
                         
                    }
            }
            
//Accounts integration       '$tds'203050900,'$vds'  203050300  
$glmstArr = array(
	'transdt' => $date,
	'refno' => $donumber,
	'remarks' => $details,
	'entryby' => $usr,
);
$clientgl='101010202';
$feesgl='302010100';	
$tds_gl='203050900';
$vds_gl ='203050300';  

	$gldetailArr[] = array(
		'sl'	 =>	1,
        'glac'	 =>	$clientgl,	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	$total,
		'remarks' 	=>	'Maintanance service',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>	formatDateReverse($date)
);
$gldetailArr[] = array(
		'sl'	 =>	2,
        'glac'	 =>	$feesgl,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$fee,
		'remarks' 	=>	'service fee',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>	formatDateReverse($date)
);

	$gldetailArr[] = array(
		'sl'	 =>	2,
        'glac'	 =>	$tds_gl,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$tds,
		'remarks' 	=>	'TDS',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>	formatDateReverse($date)
);
	
	$gldetailArr[] = array(
		'sl'	 =>	3,
        'glac'	 =>	$vds_gl,	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	$vds,
		'remarks' 	=>	'TDS',
		'entryby' 	=>	$hrid,
		'entrydate' 	=>	formatDateReverse($date)
);         
 	insertGl($glmstArr,$gldetailArr);
        
        $err = "Record created successfully";
    }
        
    if ( isset( $_POST['update'] ) ) {
        
        $soId = $_POST['uid'];
        $invId = $_POST["invid"];
        
        //Delete existing row
        $delsoqry = "DELETE FROM `service_orderdetails` WHERE serviceid = ".$soId;
        $conn->query($delsoqry);
        
        $delinvqry = "DELETE FROM `service_invoicedetails` WHERE `invoiceid` = ".$invId;
        $conn->query($delinvqry);
        
        $grandTotal = 0;$totalvds=0;$totaltds=0;
         if (is_array($itemName))
            {
                for ($i=0;$i<count($itemName);$i++)
                    {
                        $product = $itemName[$i]; $fee = $fees[$i]; $tds=$tdss[$i];$vds = $vdss[$i];
                        $total = $totals[$i];
                        $vat = ($fee * $vds) / 100; $tax = ($fee * $tds) / 100;
                        
                        $grandTotal += $total; $totalvds += $vat; $totaltds += $tax;
                     
                        $inqrysod="INSERT INTO `service_orderdetails`(`serviceid`, `sosl`, `product`, `amount`, `totalamount`, `vat`, `tax`) 
                                                        VALUES ('$soId','$i','$product','$fee','$total','$vds','$tds')";
                        $conn->query($inqrysod);
                        
                        $inqryinv="INSERT INTO `service_invoicedetails`(`invoiceid`, `sosl`, `product`, `vat`, `tax`, `amount`, `totalamount`) 
                                                            VALUES ('$invId','$i','$product','$vds','$tds','$fee','$total')";
                        $conn->query($inqryinv);
                         
                    }
            }
            
        //Update service order
        $upsoqry = "UPDATE `service_order` SET `customer`='$org_id',`orderdate`=STR_TO_DATE('".$po_dt."', '%d/%m/%Y'), `totalamount`='$grandTotal',
                    `totalvat`='$totalvds', `totaltax`='$totaltds' 
                    WHERE id = ".$soId;
        $conn->query($upsoqry);
        
        //Update Invoice order
        $upinvqry = "UPDATE `service_invoice` SET `invoicedt` = STR_TO_DATE('".$po_dt."', '%d/%m/%Y'), `invoiceamt`='$grandTotal', `dueamt`='$grandTotal' WHERE id = ".$invId;
        $conn->query($upinvqry);
        
        
        $err = "Record updated successfully";
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errorFlag == 0) {
        header("Location: ".$hostpath."/maintenanceList.php?res=1&mod=12&msg=".$err);
            
    } else {
        
        $err="Error:" . $conn->error;
        header("Location: ".$hostpath."/maintenanceList.php?res=2&mod=12&msg=".$err);
    }
    
    $conn->close();
}
?>