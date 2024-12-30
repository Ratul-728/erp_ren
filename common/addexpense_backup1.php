<?php
require "conn.php";


ini_set('display_errors',1);

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/expenseList.php?mod=3");
}
else
{
	

	
	  if($_FILES['attachment1']['name']){
		  
		  //echo $_FILES['attachment1']['name'];die;
		  
		  $code=uniqid();
		  //get img extension;

		  $ext = pathinfo($_FILES["attachment1"]["name"], PATHINFO_EXTENSION);



		  $att1="exp_".$code.'.'.$ext;
		  $tmpFilePath = $_FILES['attachment1']['tmp_name'];

		  if ($tmpFilePath != ""){ 

			  $newFilePath = 'upload/expense/' . $att1;
			 // echo $newFilePath;
			 // die;
			 if(move_uploaded_file($_FILES['attachment1']['tmp_name'], $newFilePath)){
				 $isuploaded = true;
				 
				 //if edit and new file uploaded;
				 if($_POST['oldpic']){
					 $oldFilePath = 'upload/expense/' . $_POST['oldpic'];
					 @unlink($oldFilePath);
				 }
			 } 
		  }
	  }else	if($_REQUEST['isremovepicture'] && $_POST['oldpic']){
					 $oldFilePath = 'upload/expense/' . $_POST['oldpic'];
					 @unlink($oldFilePath);
					$att1="";
	}
	
	
    if ( isset( $_POST['add'] ) ) {
        //print_r($_REQUEST);die;
       //print_r($_FILES);die;
		
     
      $trdt= $_REQUEST['trdt'];
      $cmbmode = $_POST['cmbmode'];
      $ref = $_POST['ref'];
      $cmbtype = $_POST['cmbtype'];
      $amt = $_POST['amt'];
      $cmbcc = $_POST['cmbcc'];
      $cmbso = $_POST['cmbso'];
      
      $descr = $_POST['descr'];
      $hrid = $_POST['$usr'];
      
		

       
		if(isset($isuploaded) &&  $isuploaded == true) {
      
     	$st=0; $hrid= '1';
       //echo $trdt;die;
        $qry="insert into expense( `image`, `trdt`, `transmode`, `transref`, `transtype`,  `naration`, `amount`, `costcenter`,`soid`, `st`, `makeby`, `makedt`) 
        values('".$att1."',STR_TO_DATE('".$trdt."', '%d/%m/%Y'),'".$cmbmode."','".$ref."','".$cmbtype."','".$descr."','".$amt."','".$cmbcc."','".$cmbso."','".$st."','".$hrid."',sysdate())" ;
        $err="A expense created successfully";
		}else{
			echo 'Upload Error';
			die;
		}
         
        
     //echo $qry; die;
    }
	
    if ( isset( $_POST['update'] ) ) {
       $exid= $_REQUEST['exid'];
      $trdt= $_REQUEST['trdt'];
      $cmbmode = $_POST['cmbmode'];
      $ref = $_POST['ref'];
      $cmbtype = $_POST['cmbtype'];
      $amt = $_POST['amt'];
      $cmbcc = $_POST['cmbcc'];
      $cmbso = $_POST['cmbso'];
      $descr = $_POST['descr'];
		$qry="update expense set `trdt`=STR_TO_DATE('".$trdt."', '%d/%m/%Y'),`transmode`='".$cmbmode."',`transref`='".$ref."',`transtype`='".$cmbtype."',`naration`='".$descr."',`amount`='".$amt."',`costcenter`='".$cmbcc."',`soid`='".$cmbso."',`image`='".$att1."'  where `id`=".$exid."";
		$err="Expence Voucher updated successfully";
        //echo $qry; die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
		die;
    }
    
	
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/expenseList.php?res=1&msg=".$err."&mod=3");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
		echo  $err;
		die;
          header("Location: ".$hostpath."/expenseList.php?res=2&msg=".$err."&mod=3");
    }
    
    $conn->close();
}
?>