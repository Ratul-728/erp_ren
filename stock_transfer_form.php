<?php
require "common/conn.php";
require "rak_framework/fetch.php";
session_start();
ini_set('display_errors',0);

$usr = $_SESSION["user"];

//print_r($_SESSION);

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
	$pid = $_REQUEST["pid"];
	$curqty = $_REQUEST["curqty"];
    $curstore  = $_REQUEST["curstore"];
    $barcode = $_REQUEST["code"];
	
	
	$qtryBrnch  = "SELECT `id`, `name`  FROM `branch` where status = 'A' order by name";
	
	$resultBrnch = $conn->query($qtryBrnch);
	if($resultBrnch->num_rows > 0) 
	{
		
		$strBrnchOptions .= '<div class="form-group styled-select">
								<select name="storeto" id="storeto" class="form-control">
								<option value="">Select Store</option>';
		while($row1 = $resultBrnch->fetch_assoc())
		{

			$tid = $row1["id"];
			$nm  = $row1["name"];
			if($tid!=$curstore){
			 	$strBrnchOptions .= '<option value="'.$tid.'">'.$nm.'</option>';
			}
		}
		$strBrnchOptions .= '</select></div>';
	}
	
	//echo $strBrnchOptions;die;
	
	$productName = fetchByID('item','id',$pid,'name');
	$srcStoreName = fetchByID('branch','id',$curstore,'name');
	

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Payment Receipt</title>
	
	<style>
		#printwrapper	.header{
			margin-bottom: 50px;
		}	

#printwrapper{
    font-family: arial;
    font-size: 14px;
}

		
#printwrapper h1{
    margin: 0;
    padding: 0;
    font-family: Stencil,impact;
	text-transform: uppercase;
	font-weight: normal;
    font-size: 30px;
}

#printwrapper{
/* 
    width: 800px;
   border: 3px solid #000;
	*/
    padding: 10px;

}


#printwrapper .logo img{width:150px;}	
	
		
		
.pr-2{padding-right: 40px;} .pl-2{padding-left: 20px;}
.plr-1{padding-right: 20px;padding-left: 20px;}
.pr-1{padding-right: 20px;}
.pl-1{padding-left: 20px;}


.txt-value-b{
    font-weight:bold;
    font-size: 18px;
}

.txt-value-e{
    font-weight:bold;
    font-size: 30px;
    margin-top: -15px;
}

		hr{margin: 5px 0px;}
hr.light{border-left:0px;border-right:0px; border-bottom:1px; height: 0px;}

.divdr{padding: 0 15px;}

.header label{
    font-weight: 600;
}

table td, .div-body4{padding: 8px;}

.div-body4{padding-bottom: 40px;padding-top: 30px;}

.tbl-top1{margin-bottom: 20px;}
.tbl-top1 td:last-child{
    text-align: right;
}

.tbl-top2 td:last-child{
     border-left: 1px solid #000;
    padding-left: 10px;
}

.div-top3{
  padding: 7px;
  padding-bottom: 30px;
}

.tbl-footer td{
    font-size: 12px;
}
.tbl-footer td:last-child{
     border-left: 1px solid #000;
    padding-left: 20px;
}

.tbl-footer td:first-child{
   padding-right: 20px; 
}

	</style>
	
</head>

<body>
<div id="printwrapper">
	
	
<div class="header">

	
	<table class="tbl-top1" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td class="logo"><img src="<?=$hostpath?>/assets/images/site_setting_logo/<?=$_SESSION['comlogo']?>"></td>
			<td><h1>Stock Transfer</h1></td>
		</tr>
	</table>


	
</div>
	
<div class="body">

	
	<table class="tbl-body2" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td width="33%"  class="pr-1">
			
			  <div  class="txt-value-b"><?=$productName?></div>
			  <input type="hidden" id="prdname" value="<?=$productName?>">
			  <input type="hidden" id="prdid" value="<?=$pid?>">
				<hr class="light">
			  <div class="txt-value-l">Product Name</div>
			
			</td>
		  <td width="33%" class="plr-1">
			  <div  class="txt-value-b"><?=$barcode?></div>
				<hr class="light">
			  <div class="txt-value-l">Product Barcode</div>
			  
			  </td>
		  <td width="33%"  class="pl-1">
			  
			  <div  class="txt-value-b"><?=$curqty?></div>
				<hr class="light">
			  <div class="txt-value-l">Available Quantity</div>			  
		</td>
		</tr>
		
	</table>
	<br>
	<table class="tbl-body2" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td width="33%"  class="pr-1">
			
			  <div  class="txt-value-b">
				  <div class="no-mg-btn input-group">
				  	<input type="text" class="no-mg-btn form-control" disabled placeholder="0" value="<?=$srcStoreName?>">
					  <input type="hidden" id="curstore" value="<?=$curstore?>">
			  	</div>
			  </div>
			<hr class="light">
			  <div class="txt-value-l">Transfer From</div>
			
			</td>

		  <td width="33%"  class="pl-1">
			  
			  <div  class="txt-value-b"><?=$strBrnchOptions?></div>
				<hr class="light">
			  <div class="txt-value-l">Transfer To</div>			  
		</td>			
			
		  <td width="33%" class="plr-1">
			  <div  class="txt-value-b">
				<div class="no-mg-btn input-group">
					<input type="number" class="no-mg-btn form-control" max="<?=$curqty?>" min="1" id="trqtn"  name="trqtn" required placeholder="0">
					<input type="hidden" name="curqty" value="<?=$curqty?>" id="curqty">
					<input type="hidden" name="barcode" value="<?=$barcode?>" id="barcode">
				</div>			  
			  </div>
				<hr class="light">
			  <div class="txt-value-l">Transfer Quantity</div>
			  
			  </td>

		</tr>
		
	</table>

</div>
	
</div>
</body>
	
</html>

<?php } ?>
