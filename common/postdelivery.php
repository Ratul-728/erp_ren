<?php
 session_start();
include_once("../common/conn.php");
include_once('../rak_framework/fetch.php');
include_once("../rak_framework/edit.php");
include_once("../rak_framework/log.php");

//ini_set('display_errors', 1);

//echo '<pre>';print_r($_REQUEST);echo '</pre>';
//print_r($_SESSION);
 //exit();
 if ( $_POST['postaction'] == 'Send to QC' )
 {
    extract($_REQUEST);
//	 	socode 	sosl 	productid 	qty 	storeroome 	deliveredqty 	dueqty 	return_qty 	rate 	unittotal 	vat 	total 	makeby 	makedt
	 foreach($product_id as $indx=>$pid)
	 {
	    foreach ($deliveryqty[$pid] as $strid => $strval)
        { //$strval is a delivery qtn by storeid $strid
		    if($strval>0)
		    {
            $query = 'INSERT INTO qc(`reqdate`, `soid`, `product`, `store`, `qcqty`)
        			VALUES("'.$phpdate.'",
                    "'.$socode.'",
        			"'.$pid.'",
        			"'.$strid.'",
        			"'.$strval.'" )';
 	        if ($conn->query($query) == TRUE) { $msg.="OrderID ".$socode." ProductID: ".$pid." StoreID: ".$strid." Sent QC successfully<br>";  }
 	        
 	        $rowisupdt=0;//echo $rowisupdt;die;
 	        $qryisupdated = "select count(*) as cnt from qcsum where soid='$socode' and product=$pid and store=$strid";
 	        //echo $qryisupdated;die;
 	        $resultisupdate = $conn->query($qryisupdated); 
            while($rowisupdt = $resultisupdate->fetch_assoc()){ $isupdate = $rowisupdt["cnt"];}
            //echo $isupdate;die;
            if ($isupdate==0)
            {
 	            $insqcsumm="insert into qcsum(`soid`,`product`,`store`,`qcqty`) values('$socode',$pid,$strid,$strval) ";
		    }
		    else
		    {
		       $insqcsumm="update  qcsum set qcqty=qcqty+$strval where soid='$socode' and product=$pid and store=$strid "; 
		    }
		     if ($conn->query($insqcsumm) == TRUE) { $msg.="OrderID ".$socode." ProductID: ".$pid." StoreID: ".$strid." Sent QC successfully<br>";  }
		    }
        }
	 }
	header("Location: ".$hostpath."/custorderdelivery.php?pg=1&mod=3&changedid=".$socode."&msg=QC process Started successfully");			 		
 }


 if ( $_POST['postaction'] == 'Deliver' )
 {
	extract($_REQUEST);
//	 	socode 	sosl 	productid 	qty 	storeroome 	deliveredqty 	dueqty 	return_qty 	rate 	unittotal 	vat 	total 	makeby 	makedt
	 foreach($product_id as $indx=>$pid){
		 foreach ($deliveryqty[$pid] as $strid => $strval){ //$strval is a delivery qtn by storeid $strid
			 if($strval>0){

			 //check if row exists
			
			$chkDeliQry = array('socode' => $socode,'productid' => $pid,'storeroome' => $strid);
			$deliveredqty = fetchSingleDataByArray('delivery',$chkDeliQry,'deliveredqty');
			//echo $deliveredqty;die;
			if($deliveredqty<1){ // new 
		     	
					$dueQty = $order_qtn[$pid]-$strval; //($strval is deliered qty)
					$dueBO = $dueQty;
			 		$query = 'INSERT INTO delivery(socode,	sosl,	productid, qty, storeroome,	deliveredqty, dueqty, rate, unittotal, vat, total, makeby, makedt)
								VALUES(
								"'.$socode.'",
								"'.$sosl[$pid].'",
								"'.$pid.'",
								"'.$order_qtn[$pid].'", 
								"'.$strid.'",
								"'.$strval.'",
								"'.$dueQty.'",
								"'.$rate[$pid].'",
								"'.$unittotal[$pid].'",
								"'.$vat[$pid].'",
								"'.$total[$pid].'",
								"'.$_SESSION['user'].'", SYSDATE())';
				 
				 		if ($conn->query($query) == TRUE) { $msg.="OrderID ".$socode." ProductID: ".$pid." StoreID: ".$strid." delivery added successfully<br>";  }
				
			}else{ // existing
				
				$newdeliveredqty = $deliveredqty+$strval;
				$newduedqty = $order_qtn[$pid]-$newdeliveredqty; //
				$dueBO = $newduedqty;
				$query = 'UPDATE delivery SET deliveredqty='.$newdeliveredqty.', dueqty='.$newduedqty.'
						  WHERE socode = "'.$socode.'" AND productid ='.$pid.' AND storeroome ='.$strid;
					if ($conn->query($query) == TRUE) { $msg.="OrderID ".$socode." ProductID: ".$pid." StoreID: ".$strid." delivery updated successfully<br>";  }
			}

				$totaldeliveredqty+= $strval;
				$totalDelredQtyByPrd[$pid]+= $strval;
				 
				 //release freeqty in chalanstock table by product & storerome
				 
				 $arrQuery2 = array('product' => $pid,'storerome' => $strid);
				 $freeqty = fetchSingleDataByArray('chalanstock',$arrQuery2,'freeqty');
				 $newfreeqty = $freeqty - $strval; //$strval is delivery qty for this store $strid
				 
				 $whereqry3 = 'product="' . $pid . '" AND 	storerome='.$strid;
				 if(updateByID('chalanstock','freeqty',$newfreeqty,$whereqry3)){$msg .= "freeqty adjusted/released in chalanstock table by product id and store id<br>";}
				 
				 
					 
				 
				 
				 
			 }
		 } //foreach ($deliveryqty[$pid] as $strid => $strval){ //$strval is a delivery qtn by storeid $strid
		
	
		 
		 
				//calculate backorder qty;

		 		$freeqtyQry = 'product='.$pid;
		  		$freeqty = fetchTotalSum('chalanstock','freeqty',$freeqtyQry);
		 
				 $arrQuery1 = array('socode' => $socode,'productid' => $pid);
				 $duedelqty = fetchSingleDataByArray('soitemdetails',$arrQuery1,'dueqty');
				 $newduedelqty = $duedelqty-$totalDelredQtyByPrd[$pid];	
		 
				$newduedqty = $newduedelqty;
				  
				 if($freeqty < $newduedqty ){
				 	$backorderqty = abs($freeqty-$newduedqty);
				 }
				 bitLog("dueBO: ".$newduedqty." | freeqty in challan ".$freeqty." | abs backorder ".$backorderqty);
		 		
		 		
				 
				 //update new backorder qty in soitemdetail
		 		$whereqrySID = 'socode = "' . $socode . '" and productid="' . $pid . '"';
		 		if($newduedqty > 0){
					if($backorderqty){ // it is backorder. Add backorderedqty+ in soitemdetails;
						
						if( updateByID( 'soitemdetails', 'backorderedqty', $backorderqty, $whereqrySID ) ) {$msg = "backorderedqty Qty added in soitemdetails table";	}
					}		 
		 	}else{
					if( updateByID( 'soitemdetails', 'backorderedqty', 0, $whereqrySID ) ) {$msg = "backorderedqty Qty added in soitemdetails table";	}
				}
		 
		 
		 
			if($totaldeliveredqty>0){


			 $countDueDelQty += $duedelqty[$pid];

			 //update soitemdetail for deliveryqty+ | duedelqty-

			 $whereqry1 = 'socode="' . $socode . '" AND 	productid='.$pid;
			 $arrQuery1 = array('socode' => $socode,'productid' => $pid);


			 $deliveredqty= fetchSingleDataByArray('soitemdetails',$arrQuery1,'deliveredqty');
			 $newdeliveredqty = $deliveredqty+$totalDelredQtyByPrd[$pid];

			 $duedelqty = fetchSingleDataByArray('soitemdetails',$arrQuery1,'dueqty');
			 $newduedelqty = $duedelqty-$totalDelredQtyByPrd[$pid];		 


			 if(updateByID('soitemdetails','deliveredqty',$newdeliveredqty,$whereqry1)){$msg .= "deliveredqty added in soitemdetails table<br>";}
			 if(updateByID('soitemdetails','dueqty',$newduedelqty,$whereqry1)){$msg .= "dueqty added in soitemdetails table<br>";}


			 //release freeqty and add orderedqty in stock by productid
			 $deliveredqty_stock = fetchByID( 'stock', 'product', $pid, 'deliveredqty' );
			 $new_deliveredqty_stock = $deliveredqty_stock + $totalDelredQtyByPrd[$pid];
			 $whereqry2 = 'product='.$pid;
			 if(updateByID('stock','deliveredqty',$new_deliveredqty_stock,$whereqry2)){$msg .= "deliveredqty added in stock table<br>";}
				
			 $orderedqty_stock = fetchByID( 'stock', 'product', $pid, 'orderedqty' );
			 $new_orderedqty_stock = $orderedqty_stock - $totalDelredQtyByPrd[$pid];
			 if(updateByID('stock','orderedqty',$new_orderedqty_stock,$whereqry2)){$msg .= "orderedqty released in stock table<br>";}
		 
			} //if($totaldeliveredqty>0){
	 }
	 
	 bitLog("totaldeliveredqty: ".$totaldeliveredqty);
	 if($totaldeliveredqty>0){
	 		
		 $msg .="Total Due qtn: ".$countDueDelQty."<br>";
		 $msg .="Total Delivery qtn: ".$totaldeliveredqty."<br>";
		  //echo $msg."<br>";

		 //sum duedel in this order;
		//$condition =  'socode="' . $socode . '" AND 	productid='.$pid;
		//$totaldue = fetchTotalSum('soitemdetails','dueqty',$condition);

		 $soitemStatusQry = 'socode="' . $socode . '"';
		 
		  $thisOrderTotalDueQty = fetchTotalSum('soitemdetails','dueqty',$soitemStatusQry);
		 bitLog("thisOrderTotalDueQty: ".$thisOrderTotalDueQty);
		 
		 $totalBackORder = fetchTotalSum('soitemdetails','backorderedqty',$soitemStatusQry);
		 bitLog("totalBackORder: ".$totalBackORder);
		 
		 
	 
		 
		 
		 if($totalBackORder<1){
			 if($thisOrderTotalDueQty<1){//do not change status if there is backorder;
				 if(updateByID('soitem','orderstatus',5,$soitemStatusQry)){
				     $msg .= "orderstatus to delivered in soitem table<br>"; 
				     $note = "So code: $socode is delivered";}
			 }else{
				 if(updateByID('soitem','orderstatus',10,$soitemStatusQry)){$msg .= "orderstatus to partial delivery in soitem table<br>";$note = "So code: $socode is partially delivered";}
			 }
		 }
		 
		 	//update last lastdeliverydt in soitem to sort it in delivery list
			 if(updateByID('soitem','lastdeliverydt', '"'.date('Y-m-d H:i:s').'"',$soitemStatusQry)){$msg .= "lastdeliverydt updated in soitem table<br>";}
		 
		 $qryhis= "SELECT organization FROM `soitem` WHERE socode = '".$socode."'";
		 $resulthis = $conn->query($qryhis); 
         while($row = $resulthis->fetch_assoc()){
             $org = $row["organization"];
         }
         $hrid = $_SESSION["user"];
		 $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values('".$org."',5,sysdate(),'".$note."','',0,'','".$hrid."',sysdate())" ;
        
        //echo $qry_othr;die;
         $conn->query($qry_othr);
		 
		 
		 
		 
	 }//if($totaldeliveredqty>0){
	 
	 header("Location: ".$hostpath."/custorderdelivery.php?pg=1&mod=3&changedid=".$socode."&msg=Delivery process complete successfully");
	 
}
?>