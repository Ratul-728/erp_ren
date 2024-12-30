<?php
require "common/conn.php";
session_start();
ini_set('display_errors',1);

$usr = $_SESSION["user"];

$rpid = $_GET["rpid"];

//print_r($_SESSION);

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {

	
	
            $qry = "SELECT cl.`id`, 
                            cl.`trdt`, 
                            tr.name `transmode`,
                            cl.`transref`,
                            c.name `customer`, 
                            c.street `address`, 
                            cl.`naration`,
                            format(cl.`amount`,2)amount, 
                            cc.name `costcenter` , 
                            cl.`chequedt`
                    FROM allpayment cl 
                    left join organization c on cl.customer=c.id 
                    left join costcenter cc on cl.costcenter=cc.id 
                    left join transmode tr on cl.transmode=tr.id 
                    where cl.id = " . $rpid;	
	
if ($conn->connect_error) {

    echo "Connection failed: " . $conn->connect_error;

} else {

    $result = $conn->query($qry);

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $trdt = $row['trdt'];

            $transmode = ($row['transmode'])?$row['transmode']:'NA';

            $transref = ($row['transref'])?$row['transref']:'NA';

            $customer = ($row['customer'])?$row['customer']:'NA';

        	$naration = ($row['naration'])?$row['naration']:'NA';

            $amount = ($row['amount'])?$row['amount']:'NA';

        	$costcenter = ($row['costcenter'])?$row['costcenter']:'NA';
			
			$address = ($row['costcenter'])?$row['address']:'NA';
        	
        	$cqdt = ($row["chequedt"])?$row['chequedt']:'NA';

        }

    }

}
	//$amount = '50,021,220.355';
	
	//echo $amount.'<p>' ;

	
	 $amount_for_word =   floatval(preg_replace('/[^\d.]/', '', $amount)).'<p>' ;
	 $amount_for_word =   number_format(floatval($amount_for_word), 2, ".", "").'<p>' ;
	
	//echo $amount_for_word;
	
	
	require "common/amount_in_words.php";
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
    font-family: Stencil,impact;
	text-transform: uppercase;
	font-weight: normal;
    font-size: 30px;
}

#printwrapper{

   /* width: 800px;
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
		  <td class="logo"><img src="<?=$hostpath?>/assets/images/pro.png"></td>
			<td><h1>Payment Receipt</h1></td>
		</tr>
	</table>

	<!--table class="tbl-top2" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td>
			  <label>Customer Address</label><br>
			  <div class="txt-value-l"><?=$address?></div>
		  </td>
			<td>
				<label>Invoice No:</label>	<span class="txt-value-t">000011444</span><br>
				<label>Order No:</label> <span class="txt-value-t">ORG12154454</span>
			</td>

		</tr>
	</table-->
	
<!--	<hr>-->
	
<!--
	<div class="div-top3">
		<label>Order Date:</label><span class="txt-value-l"> 09/06/2022</span>    <span class="divdr">|</span>    <label>Payment Type:</label> <span class="txt-value-t">Cash</span>   <span class="divdr">|</span>   <label>Status:</label> <span class="txt-value-t">Pending</span>
	</div>
-->
	
</div>
	
<div class="body">

	
	<table class="tbl-body1" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<input type="hidden" id="customerName" value="<?=$customer?>">
		  <td width="67%" class="pr-1">
			  <div  class="txt-value-b"><?=$customer?></div>
				<hr class="light">
			  <div class="txt-value-l">Received From/Organization</div>
		  </td>
		  <td width="33%" class="pl-1">
			
			  <div class="txt-value-e"><?=$amount?></div>
				  <hr class="light">
				<div class="txt-value-l">Received Amount</div>
			
			</td>
		</tr>
	</table>
	<table class="tbl-body2" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td width="100%">
			
			  <div  class="txt-value-b"><?=$in_words?> Tk.</div>
				<hr class="light">
			  <div class="txt-value-l">Amount in word</div>
			
			</td>


		</tr>
		
	</table>
	
	<table class="tbl-body2" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td width="33%"  class="pr-1">
			
			  <div  class="txt-value-b"><?=$transmode?></div>
				<hr class="light">
			  <div class="txt-value-l">Transaction Mode</div>
			
			</td>
		  <td width="33%" class="plr-1">
			  <div  class="txt-value-b"><?=$transref?></div>
				<hr class="light">
			  <div class="txt-value-l">Reference/Cheque No</div>
			  
			  </td>
		  <td width="33%"  class="pl-1">
			  
			  <div  class="txt-value-b"><?=$cqdt?></div>
				<hr class="light">
			  <div class="txt-value-l">Cheque Date</div>			  
		</td>
		</tr>
		
	</table>
	
	
	
	<table  class="tbl-body3"  width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td  width="67%" class="pr-1">
			  
			  
			 <div  class="txt-value-b"><?=$naration?></div>
				<hr class="light">
			  <div class="txt-value-l">Being the amount received against</div>
			  
		</td>
		  <td  width="33%"  class="pl-1">
			  
			  
			
			 <div  class="txt-value-b"><?=$costcenter?></div>
				<hr class="light">
			  <div class="txt-value-l">Cost Center</div>
			  
		</td>
		</tr>
		
	</table>
	
	<div class="txt-value-b div-body4">Thank you for choosing <?=$_SESSION['comname']?>!</div>
	

</div>
	
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
