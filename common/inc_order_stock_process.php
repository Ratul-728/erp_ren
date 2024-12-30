<?php


if ($orderstatus == 3 || $orderstatus == 9) { // for draft no change;


	//if status is 3 make orderqty+ | bookqty=0 | orderqty+ in stock table

	//$itmmnm is product id;

	//common data

	$freeqty = fetchByID( 'stock', 'product', "$itmmnm", 'freeqty' );
	$whereqry = 'product="' . $itmmnm . '"';

	if ( $orderstatus == 3 ) { // confirmed




		if ( $orsttype == 9 ) { // this order was first booked. then confirmed so need to release bookqty;

			$bookqty = fetchByID( 'stock', 'product', "$itmmnm", 'bookqty' );
			$newbookqty = $bookqty - $qty;
			//bookqty-
			if ( updateByID( 'stock', 'bookqty', $newbookqty, $whereqry ) ) {
				$msg = "Booked Qty released";
			}
		}else{// previously booked product will not effect freeqtn, because feeqtn was already deducted when booked.
		
			//bring freeqty and - qty		
			$newfreeqty = $freeqty - $qty;
			//freeqty-
			if ( updateByID( 'stock', 'freeqty', $newfreeqty, $whereqry ) ) {
				$msg = "Free Qty released";
			}
		}

		$orderedqty = fetchByID( 'stock', 'product', "$itmmnm", 'orderedqty' );
		$neworderedqty = $orderedqty + $qty;
		//oderqty+
		if ( updateByID( 'stock', 'orderedqty', $neworderedqty, $whereqry ) ) {
			$msg = "Order Qty added";
		}
		
		
		
		
	}


	//add bookqty if status is 9
	if ( $orderstatus == 9 ) {
		//bring old bookqtn first
		$bookqty = fetchByID( 'stock', 'product', "$itmmnm", 'bookqty' );
		$newbookqty = $bookqty + $qty;


		
		//bring freeqty and - qty		
		$newfreeqty = $freeqty - $qty;

		//freeqty-
		if ( updateByID( 'stock', 'freeqty', $newfreeqty, $whereqry ) ) {
			$msg = "Order Qty added";
		}


		if ( updateByID( 'stock', 'bookqty', $newbookqty, $whereqry ) ) {
			$msg = "Order booked qty added to stock table";
			//print_r($_REQUEST);
			//die;
		}

							

	}

//handle backorder;
	
	
	
	if(($freeqty < $qty ) && ($freeqty>=0)){
		$backorderqty = $qty - $freeqty;
	}else if($freeqty<0){
		$backorderqty = $qty;
	}
	
	 bitLog("product ID: ".$itmmnm." | Ordered: ".$qty." | newfreeqty: ".$newfreeqty." | backorder ".$backorderqty);
	
	if($backorderqty>0){ // it is backorder. Add backorderedqty+ in soitemdetails;
		
		$chkSIDQry = array('socode' => $poid,'productid' => $itmmnm);
		$currbackorderedqty = fetchSingleDataByArray('soitemdetails',$chkSIDQry,'backorderedqty');
		
		$newBackorderedqty = $currbackorderedqty+$backorderqty;
		
		$whereqrySID = 'socode = "' . $poid . '" and productid="' . $itmmnm . '"';
		if( updateByID( 'soitemdetails', 'backorderedqty', $newBackorderedqty, $whereqrySID ) ) {$msg = "backorderedqty Qty added in soitemdetails table";	}
		

	
	
}

}

?>

