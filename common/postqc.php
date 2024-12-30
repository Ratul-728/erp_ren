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
 if ( $_POST['postaction'] == 'Submit QC Report' )
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
	header("Location: ".$hostpath."/qc.php?pg=1&mod=3&changedid=".$socode."&msg=QC process Started successfully");			 		
 }



?>