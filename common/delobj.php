<?php
session_start();
if(!$_SESSION["user"]){
    header("Location: " . $hostpath . "/hr.php");
} else {
	
	
	//print_r($_REQUEST);die;
	
	
$obj= $_GET['obj'];
$returl=$_GET['ret'];
$md=$_GET['mod'];
//echo "yes"; die;
//$msg= $_GET['msg'];
$atid= $_GET['id'];
$hrid = 1;//$_POST['usrid'];
require_once("conn.php");

//print_r($_REQUEST);die;

	//$sql = 'delete from '.$obj.' WHERE id='.$atid;
	
	
	if($obj=="collection")
	{
	    $cusqry="select `customerOrg`, `naration`, `amount`,glac from  collection  where id=".$atid;
            //echo $itqry;die;
          
           $result = $conn->query($cusqry); 
          if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                          $customer=$row["customerOrg"]; $amount=$row["amount"];$gl=$row["glac"];  
                    }
            }
        
        
        $orgbalqry="update organization set balance=balance-".$amount." where id=".$customer;
            //echo $orgbalqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
        
        
         $note="Customer Payment: Payment Amount ".$amount." deleted ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values(".$customer.",6,sysdate(),'".$note."','',0,".$amount.",".$hrid.",sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  } 
         
         
        /* Accounnting */
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id=8 ";// Recevable from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
         
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',CONVERT(sysdate(), DATE),' collection id -".$atid."','Deposit deleted','".$hrid."',sysdate())";
                        
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",1,".$glno.",'D',".$amount.",'Client deposit deleted',".$hrid.",sysdate())";
               //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",2,".$gl.",'C',".$amount.",'Client deposit deleted',".$hrid.",sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
         
               /* Accounnting */ 
        
	}
	if($obj=="allpayment")
	{
	    $cusqry="select `customer`, `naration`, `amount`,glac from  allpayment  where id=".$atid;
           // echo $cusqry;die;
          
           $result = $conn->query($cusqry); 
          if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                          $customer=$row["customer"]; $amount=$row["amount"];$gl=$row["glac"];  
                    }
            }
        
        
        $orgbalqry="update organization set balance=balance+".$amount." where id=".$customer;
            //echo $orgbalqry;die;
          if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
        
        
         $note="Customer Payment: Payment Amount ".$amount." deleted ";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values(".$customer.",6,sysdate(),'".$note."','',0,".$amount.",".$hrid.",sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  } 
         
         
        /* Accounnting */
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id=9 ";// Recevable from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
         
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',CONVERT(sysdate(), DATE),' Payment id -".$atid."','Payment deleted','".$hrid."',sysdate())";
           //echo  $glmqry;die;            
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",1,".$glno.",'C',".$amount.",'Payment deleted',".$hrid.",sysdate())";
               //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",2,".$gl.",'D',".$amount.",'Payment  deleted',".$hrid.",sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
         
               /* Accounnting */ 
        
	}
	if($obj=="expense")
	{
	    $cusqry="select `transtype`, `amount`,glac from  expense  where id=".$atid;
           // echo $cusqry;die;
          
           $result = $conn->query($cusqry); 
          if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                          $transtype=$row["transtype"]; $amount=$row["amount"];$gl=$row["glac"];  
                    }
            }
        
        switch($transtype)
        {
            case 1 :  $mappid=5; break;
            case 2:   $mappid=12;break;
            case 3 :  $mappid=3; break;
            case 4:   $mappid=10;break;
            default: $mappid=11;
        }
        $getgl="SELECT mappedgl FROM glmapping where id =$mappid";// 3-rent,5-salary,10-bills,11-others,12-legal
        $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc())
            {   $glno = $rowgl["mappedgl"];}} 
        
       
        /* Accounnting */
         $vouch = 10000000000; 
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',CONVERT(sysdate(), DATE),' Expense id -".$atid."','Expense deleted','".$hrid."',sysdate())";
           //echo  $glmqry;die;            
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",1,".$glno.",'C',".$amount.",'Payment deleted',".$hrid.",sysdate())";
               //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES (".$vouch.",2,".$gl.",'D',".$amount.",'Payment  deleted',".$hrid.",sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="GL Master added successfully";  }else{ $errflag++;}
        }
         
               /* Accounnting */ 
        
	}
 
 $datetime=date("Y_m_d_h_s");	
 $dellog="create table ".$obj."_del_".$atid."_".$datetime." as select * from ".$obj." WHERE id = ".$atid;
 $conn->query($dellog);
 
	
	if($obj == "purchase_landing"){
	    //Delete All sub table data
        $qryDelete = "DELETE FROM `purchase_landing_item` WHERE `pu_id` = ".$atid;
        $conn->query($qryDelete);
	}
	if($obj == "employee"){
	    //Delete All sub table data
        $qryDelete = "DELETE h FROM `hr` h LEFT JOIN employee emp ON emp.employeecode=h.emp_id WHERE emp.id = ".$atid;
        $conn->query($qryDelete);
	}
 
 $sql = 'DELETE FROM '.$obj.' WHERE id = '.$atid;
//	$sql ="update '.$obj.' set active_st='D' WHERE id = ".$atid;
   // echo $sql;die;
    if ($conn->query($sql) == TRUE) {
		
		//delete picture if found
		if($_REQUEST['img']){
			if(unlink($rootpath.$_REQUEST['img'])){
				//echo "Deleted ".$rootpath.$_REQUEST['img'];
			}else{
				//echo "Delete error ".$rootpath.$_REQUEST['img'];
			}
			//die;
		}
		
        $orgid = $_GET["orgid"];
        $retid= $_GET['retid'];
        if($retid != ''){
            header("Location: ".$hostpath."/".$returl.".php?id=".$retid."&res=4&mod=".$md);
            die;
        }
        if($orgid == ''){
            $msg = "Successfully Deleted";
            header("Location: ".$hostpath."/".$returl.".php?res=1&msg=$msg&mod=".$md);
        }else{
            $msg = "Successfully Deleted";
            header("Location: ".$hostpath."/".$returl.".php?id=".$orgid."&res=1&msg=$msg&mod=".$md);
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $conn->close();


}//if(!$_SESSION["user"]){
?>