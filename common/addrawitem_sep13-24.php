<?php
require "conn.php";
require "image_resize.php";
ini_set('display_errors', 0);

session_start();

if ($_SESSION["user"] == '') {
    header("Location: " . $hostpath . "/hr.php");
}

//print_r($_REQUEST);die;

include_once('../rak_framework/fetch.php');
include_once('../rak_framework/edit.php');
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=1");
}
else
{
	
$imgbasepath = "../assets/images/products/";
	
	$barcode=0;
    if ( isset( $_POST['add'] ) ) {
		
		//echo '<pre>';	print_r($_REQUEST);	echo '<pre>'; die;
		
		
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        $code = getFormatedUniqueID('item','id','00',6,"0");
		
       // $code= $_REQUEST['code'];               //if($code==''){$code='NULL';}
        $nm = $_POST['nm'];                     //if($nm==''){$nm='NULL';}
        $cmbprdtp = $_POST['cmbprdtp'];         //if($cmbprdtp==''){$cmbprdtp=0;}
        $measureUnit = $_POST['measureUnit'];   //if($measureUnit==''){$measureUnit='NULL';}
        $cmbcolor = 1;//$_POST['cmbcolor'];         //if($cmbcolor==''){$cmbcolor='NULL';}
        $txtcolor = $_POST['txtcolor'];
        $size = $_POST['size'];                 //if($size==''){$size='NULL';}
        $cmbstyletp = $_POST['cmbstyletp'];     //if($cmbstyletp==''){$cmbstyletp='NULL';}
        $rate = $_POST['rate'];                 //if($rate==''){$rate=0;}
        $cost = $_POST['cost'];                 //if($cost==''){$cost=0;}
        $cat_id = $_POST['cat_id'];       		//if($cat_id==''){$cat_id='NULL';}
        $cmbcur = $_POST['cmbcur'];             //if($cmbcur==''){$cmbcur='NULL';}
        $dimesion = $_POST['dimesion'];         //if($dimesion==''){$dimesion='NULL';}
        $weight = $_POST['weight'];             //if($weight==''){$weight=0;}
        $details = $_POST['details'];           //if($details==''){$details='NULL';}
        $vat = $_POST["vat"];                   //if($vat == '') $vat = 0;
        $ait = $_POST["ait"];                   //if($ait == '') $ait = 0;
        $brand = $_POST["productbrand"];
        $parts = $_POST["parts"];
        
        $length = $_POST["length"];
        $lengthunit = $_POST["unitlength"];
        $width = $_POST["width"];
        $widthunit = $_POST["unitwidth"];
        $height = $_POST["height"];
        $heightunit = $_POST["unitheight"];
        $note = $_POST["note"];
        $forstock = $_POST["stocktp"];
        $backorderqty = $_POST["bkqty"];
        $finishedst = $_POST["isfinished"];
        $approvedst = $_POST["isapproved"];
       // $parts = $_POST["parts"];
        
 
		
		$bc=$cmbprdtp.$code.$cat_id;	
        $barcode=str_pad($bc,8,"0",STR_PAD_LEFT);
		
	//handle upload entry edit both;
	if($_FILES['attachment1']['name']){
		$targetPhotoName = $code;
		//thumb 
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."300_300/",300,300);
		//full
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."800_800/",800,800);
		
		list($width, $height, $type, $attr) = getimagesize($_FILES['attachment1']['tmp_name']);
		//original;
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."original/",$width,$height);
		
		$ext = pathinfo($_FILES['attachment1']['name'], PATHINFO_EXTENSION);
		$imgfullname = $code.".".$ext;
	}
	
	if(strlen($imgfullname) < 1){
	    $imgfullname = "placeholder.jpg";
	}
		
      

		
    
        $hrid= $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
         
        $qry="insert into item(`code`, `name`, `type`,`brand`, `mu`,`color`,colortext, `size`, `rate`, `cost`, `catagory`, `currency`, `dimension`, `wight`, `image`, `description`, `pattern`, `make_dt`, `makeby`,`st`,`vat`,`ait`,`barcode`, parts,length,lengthunit,width,widthunit,height,heightunit,note,forstock,backorderqty,finishedst
,approvedst) 
        values('".$code."','".$nm."','".$cmbprdtp."','".$brand."','".$measureUnit."','".$cmbcolor."','".$txtcolor."','".$size."','".$rate."','".$cost."','".$cat_id."','".$cmbcur."','".$dimesion."','".$weight."','".$imgfullname."','".$details."','".$cmbstyletp."','".$make_date."','".$hrid."',0, '".$vat."', '".$ait."', '".$barcode."', '".$parts."', '".$length."', '".$lengthunit."', '".$width."', '".$widthunit."', '".$height."', '".$heightunit."', '".$note."', '".$forstock."', '".$backorderqty."', '".$finishedst."', '0')" ;
        $err="Item created successfully";
         
         
        $histqry="insert into product_history(prcode,`item`, `qty`, `comment`, `makeby`) 
        values('".$code."','','',' The Product ".$nm." is created',$hrid)" ;
        if ($conn->query($histqry) == TRUE) {  $err="history updared successfully";  }
        
     //echo $qry;die;   
        
   //echo $totalup; die;
		
		
		
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['itid'];
        $code= $_REQUEST['code'];               //if($code==''){$code='NULL';}
        $nm = $_POST['nm'];                     //if($nm==''){$nm='NULL';}
        $cmbprdtp = $_POST['cmbprdtp'];         //if($cmbprdtp==''){$cmbprdtp=0;}
        $measureUnit = $_POST['measureUnit'];   //if($measureUnit==''){$measureUnit='NULL';}
        $cmbcolor =1;// $_POST['cmbcolor'];         //if($cmbcolor==''){$cmbcolor='NULL';}
        $txtcolor = $_POST['txtcolor'];         //if($cmbcolor==''){$cmbcolor='NULL';}
        $size = $_POST['size'];                 //if($size==''){$size='NULL';}
        $cmbstyletp = $_POST['cmbstyletp'];     //if($cmbstyletp==''){$cmbstyletp='NULL';}
        //$rate = $_POST['rate'];  //if($rate==''){$rate=0;}
		$rate = strToNumber($_POST['rate']);
		//echo $rate; die;
        $cost = $_POST['cost'];                 //if($cost==''){$cost=0;}
        $cat_id = $_POST['cat_id'];       //if($cat_id==''){$cat_id='NULL';}
        $cmbcur = $_POST['cmbcur'];             //if($cmbcur==''){$cmbcur='NULL';}
        $dimesion = $_POST['dimesion'];         //if($dimesion==''){$dimesion='NULL';}
        $weight = $_POST['weight'];             //if($weight==''){$weight=0;}
        $details = $_POST['details'];           //if($details==''){$details='NULL';}
        $photoedit = $_POST["photoedit"];
        $vat = $_POST["vat"]; if($vat == '') $vat = 0;
        $ait = $_POST["ait"]; if($ait == '') $ait = 0;
        $brand = $_POST["productbrand"];
        $parts = $_POST["parts"];
        $backorderqty = $_POST["bkqty"];
        $rate = $_POST['rate'];  
        
		
		$code = fetchByID('item',id,$tid,'code');
		
		$bc = fetchByID('item',id,$tid,'barcode');
		if($bc=='')
		{
		$bc=$cmbprdtp.$code.$cat_id;	
        $barcode=str_pad($bc,8,"0",STR_PAD_LEFT);
		}
		else
		{
		$barcode=$bc;
		}
		
		if($_REQUEST['isremovepicture'] && $_POST['photoedit']){
			 $oldFilePath300 = $imgbasepath.'300_300/'.$_POST['photoedit'];
			 $oldFilePath800 = $imgbasepath.'800_800/'.$_POST['photoedit'];
			 $oldFilePathOrg = $imgbasepath.'original/'.$_POST['photoedit'];
			 @unlink($oldFilePath300);
			 @unlink($oldFilePath800);
			 @unlink($oldFilePathOrg);
			
			$whereqry3 = 'id='.$tid;
			if(updateByID('item','image','""',$whereqry3)){$msg = "image data updated";}
			
			//echo $msg;die;
			
		}
		
		
		
	//handle upload entry edit both;
	if($_FILES['attachment1']['name']){
		$targetPhotoName = $code;
		//thumb 
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."300_300/",300,300);
		//full
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."800_800/",800,800);
		
		list($width, $height, $type, $attr) = getimagesize($_FILES['attachment1']['tmp_name']);
		//original;
		handleItemImageUpload($_FILES['attachment1'],$targetPhotoName,$imgbasepath."original/",$width,$height);
		
		$ext = pathinfo($_FILES['attachment1']['name'], PATHINFO_EXTENSION);
		$imgfullname = $code.".".$ext;
		
// 		echo "I am here 3";die;
		
// 			$whereqry3 = 'id='.$tid;
// 			if(updateByID('item','image','"'.$imgfullname.'"',$whereqry3)){$msg = "image data updated";}
			
			$qryUpdate = "UPDATE `item` SET `image`='$imgfullname'  WHERE id = ".$tid;
			$conn->query($qryUpdate);
	}

        
        $qry="update item set `name`='".$nm."',`type`='".$cmbprdtp."',`brand`='".$brand."', `mu`='".$measureUnit."',`colortext`='".$txtcolor."',`size`='".$size."',`rate`='".$rate."',`vat`='".$vat."',`ait`='".$ait."'
        ,`catagory`='".$cat_id."',`currency`='".$cmbcur."',`dimension`='".$dimesion."',`wight`='".$weight."',`description`='".$details."',`pattern`='".$cmbstyletp."' ,`barcode`='".$barcode."',`parts`='".$parts."',backorderqty=$backorderqty
        where `id`=".$tid."";
        $err="item updated successfully";
      
		
		//get prodcut code
		
		$code = fetchByID('item',id,$tid,'code');
		
      //echo $qry;die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
	
	
    if ($conn->query($qry) == TRUE) {
		if ( isset( $_POST['add'] ) ) {
				$pid = $conn->insert_id;

			//add item in chalanstock and stock;

			$barcode = $barcode;//$code;
			$cost = ($cost)?$cost:'0.00';
			
			//Find "Main Branch" in branch table and take its ID. if not found add Main branch and give it an ID.
			//Main branch ID has to be 1; the first one;
			$mainBranchID = fetchByID('branch','name',"Main Branch",'id');
			
			if(!$mainBranchID){
			    
			    include_once('../rak_framework/insert.php');
			    
			    $inputBranchData = array(
            	'TableName' => 'branch',	
            	'FetchByKey' => 'id',
            	'FetchByValue' =>  '',
            	
            	'name' => 'Main Branch',
            	'contact_name' => 'HOD Name',
            	'contact_number' => 'HOD No',
            	'address' =>'Branch Address', 
            	'status' => 'A'
                );
			    
			    insertData($inputBranchData,$msg,$success,$insertId);
			     
			    $mainBranchID = $insertId;
			}
			
			$strQryChalanstock = 'INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES('.$pid.',0,'.$cost.',"'.$barcode.'",'.$mainBranchID.')';
			
			//echo $strQryChalanstock;die;
			if ($conn->query($strQryChalanstock) == TRUE) { $err="0 qtn added in chalanstock in main branch";  }

			$strQryStock = 'INSERT INTO stock( `product`, `freeqty`, `bookqty`, `orderedqty`, `deliveredqty`, repairedqty, `costprice`, `prevprice`) VALUES('.$pid.',0,0,0,0,0,'.$cost.',0)';
			if ($conn->query($strQryStock) == TRUE) { $err="0 qtn added in stock";  }			
		//	echo $strQryStock;die;
		}
                header("Location: ".$hostpath."/rawitemList.php?res=1&msg=".$err."&id=".$tid."&mod=12&changedid=".$code);
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/rawitemList.php?res=2&msg=".$err."&mod=12&id=".$tid."&changedid=".$code);
    }
    
    $conn->close();
}
?>