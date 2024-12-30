<?php

session_start();


require "common/conn.php";
require "rak_framework/fetch.php";
require "rak_framework/misfuncs.php";

ini_set('display_errors',0);

$usr = $_SESSION["user"];

$trid = $_GET["trid"];

//print_r($_SESSION);

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {


    $qryInfo = "SELECT DATE_FORMAT( orgw.transdt,'%d/%b/%Y') transdt, orgw.dr_cr, orgw.trans_ref, orgw.amount, orgw.remarks, t.name transmode, org.name orgnm
                FROM `organizationwallet` orgw LEFT JOIN transmode t ON t.id=orgw.transmode LEFT JOIN organization org ON org.id=orgw.orgid
                WHERE orgw.id = ".$trid;
    $resultInfo        = $conn->query($qryInfo);
    while ($rowInfo = $resultInfo->fetch_assoc()) {
        $amount = $rowInfo["amount"];
        $customer = $rowInfo["orgnm"]; if($customer == '') $customer = "NA";
        $transref = $rowInfo["trans_ref"]; if($transref == '') $transref = "NA";
        $transmode = $rowInfo["transmode"]; if($transmode == '') $transmode = "NA";
        $trdt = $rowInfo["transdt"];
        $naration = $rowInfo["remarks"]; if($naration == '') $naration = "NA";
        $type = $rowInfo["dr_cr"]; 
    }
    
	//$amount = '50,021,220.355';
	
	//echo $transmode .'<p>' ;

	
	 $amount_for_word =   floatval(preg_replace('/[^\d.]/', '', $amount)).'<p>' ;
	 $amount_for_word =   number_format(floatval($amount_for_word), 2, ".", "").'<p>' ;
	
	//echo $amount_for_word;
	
	
	//include_once("common/amount_in_words.php");
	$in_words = ucwords(convert_number_to_words(floatval($amount_for_word)));
	
	//echo $in_words;

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
	font-family: roboto_condensedbold;
    font-size: 30px;
	font-weight: normal;
	text-transform: uppercase;
  
}

#printwrapper{

   /* width: 800px;
    border: 3px solid #000;
	*/
    padding: 10px;

}


#printwrapper .logo img{width:330px;}	
	
		
		
.pr-2{padding-right: 40px;} .pl-2{padding-left: 20px;}
.plr-1{padding-right: 20px;padding-left: 20px;}
.pr-1{padding-right: 20px;}
.pl-1{padding-left: 20px;}


.txt-value-b{
    font-weight:bold;
    font-size: 15px;
}

.txt-value-e{
    font-weight:bold;
    font-size: 20px;
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
		  <td class="logo"><img src="<?=$hostpath?>/assets/images/site_setting_logo/<?=fetchByID('sitesettings',id,1,'doc_header_logo');?>"></td>
			<td><h1>Payment Receipt #<?= $trid ?></h1></td>
		</tr>
	</table>

	
</div>
	
<div class="body">

	
	<table class="tbl-body1" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<input type="hidden" id="customerName" value="<?=$customer?>">
		  <td width="67%" class="pr-1">
			  <div  class="txt-value-b"><?=$customer?></div>
				<hr class="light">
			<?php if($type == "D") { ?>
			    <div class="txt-value-l">Received For/Organization</div>
			<?php }else { ?>
			    <div class="txt-value-l">Received From/Organization</div>
			<?php } ?>
		  </td>
		  <td width="33%" class="pl-1">
			
			  <div class="txt-value-e"><?=$amount?> Tk.</div>
				  <hr class="light">
		    <?php if($type == "D") { ?>
				<div class="txt-value-l">Paid Amount</div>
			<?php } else { ?>
			    <div class="txt-value-l">Received Amount</div>
			<?php } ?>
			
			</td>
		</tr>
	</table>
	<table class="tbl-body2" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td width="100%">
			
			  <div  class="txt-value-b"><?=$in_words?> Taka</div>
				<hr class="light">
			  <div class="txt-value-l">Amount in word</div>
			
			</td>


		</tr>
		
	</table>
	
	<table class="tbl-body2" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			
			
		  <td width="25%" class="pr-1">
			  <div  class="txt-value-b"><?=$transref?></div>
				<hr class="light">
			  <div class="txt-value-l">Reference</div>
			  
		  </td>			
			
			
		  <td width="25%"  class="plr-1">
			
			  <div  class="txt-value-b"><?= $transmode ?></div>
				<hr class="light">
			  <div class="txt-value-l">Transaction Mode</div>
			
			</td>
			
		  <td width="25%" class="pr-1">
			  <div  class="txt-value-b"><?= $trdt ?></div>
				<hr class="light">
			  <div class="txt-value-l">Transaction Date</div>
			  
		  </td>	
		</tr>
		
	</table>
	
	
	
	<table  class="tbl-body3"  width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td  width="10%" class="pr-1">
			  
			  
			 <div  class="txt-value-b"><?=$naration?></div>
				<hr class="light">
			  <div class="txt-value-l">Remarks</div>
			  
		</td>
		  <!--td  width="33%"  class="pl-1">
			  
			  
			
			 <div  class="txt-value-b"><?=$costcenter?></div>
				<hr class="light">
			  <div class="txt-value-l">Cost Center</div>
			  
		</td-->
		</tr>
		
	</table>
	
	<div class="txt-value-l div-body4">Thank you for choosing <?=$_SESSION['comname']?>.</div>
	
	<table  width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td  width="50%">
			  
			<div style="border-top:0px solid #000;width:150px;padding-top:5px;">
			    Received By: <?=fetchByID('hr','id',$_SESSION['user'],'hrName')?>
			</div>

		</td>
		<td  width="50%">
			<div style="border-top:1px solid #000;width:150px;padding-top:5px;">
			    Customer Signature
			</div>
		</td>
	  </tr>
	</table>

</div><br>

	<hr>
	<div class="footer">
	
		<table class="tbl-footer"  border="0" cellspacing="0" cellpadding="0">
			<tr>
			  <td class="txt-value-s">
				Phone: <?=$_SESSION['comcontact']?> <br>
				Email: <?=$_SESSION['comemail']?></td>
			  <td class="txt-value-s">
				<?=$_SESSION['comaddress']?><br>
				Website: <?=$_SESSION['comweb']?>	
			</td>
			</tr>		
		</table>	
	
	</div>
	
	
	
</div>
	
	
</body>
</html>

<?php } ?>
