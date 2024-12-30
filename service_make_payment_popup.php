<?php
session_start();
require "common/conn.php";
include_once('rak_framework/fetch.php'); 

//ini_set('display_errors', 1);
//echo 'test';
//exit();
extract($_REQUEST);

//print_r($_REQUEST);die;


$walletmnt = fetchByID('organization',id,$_REQUEST['cid'],'balance');
$payable = fetchByID('service_invoice',invoice,$_REQUEST['invoiceno'],'invoiceamt');
$duemnt = fetchByID('service_invoice',invoice,$_REQUEST['invoiceno'],'dueamt');

// $qryDue    = "SELECT FORMAT(`dueamount`, 2) AS formatted_amount, FORMAT(`invoiceamt`, 2) AS formatted_invoiceamt FROM `invoice` WHERE `invoiceno` = '".$_REQUEST['invoiceno']."'";
// $resultDue = $conn->query($qryDue);
// while ($rowDue = $resultDue->fetch_assoc()) {
//     $duemnt = $rowDue["formatted_amount"];
//     $payable  = $rowDue["formatted_invoiceamt"];
// }

?>



<?php
/*
?>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome4.0.7.css" rel="stylesheet">
<link href="css/fonts.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/style_extended.css" rel="stylesheet">
<link href="css/simple-sidebar.css" rel="stylesheet">

<?php
 */
?>

<style>
	.inc-pos-row {
		padding: 0 10px;
		margin: 0;
	}

	.tabs-nav ul {
		padding: 10px;
		text-align: center;
	}

	.tabs-nav li {
		list-style: none;
		width: 100%;
		text-align: center;
	}

	.tabname {
		color: black;
		background-color: white;
	}

	.active {
		background-color: #00abe3 !important;

	}

	.active li{
		color:#fff;
	}

	.tabs-nav div {
		margin: 0;
		padding: 0;
	}



		.tabs-nav > ul li{
			cursor: pointer;

		}

		.tabs-nav > ul{
			background-color: #eeeded;
		}
		.tabs-nav{
			background-color: #00ABE3!important;
			height: 45px;
		}


</style>

<div class="row">
	<div class="tabs-nav">

		<ul class="col-lg-6 col-md-6 col-sm-6 active tabname">
			<li id="tabLi1" class="cash">Cash Receive</li>
		</ul>




		<ul class="col-lg-6 col-md-6 col-sm-6 tabname">
			<li id="tabLi2" class="wallet"> Payment From Wallet</li>
		</ul>


	</div>
<form id="payment_form">
	<section class="tabs-content">

		<div id="tab1">
			
			
			
			
			
				<div class="row">




					<div class="col-sm-12">
						&nbsp;
					</div>

					<div class="col-sm-12">
						<div class="form-group">
							<label for="cnnm">Invoice Payable Amount (<?=$invoiceno?>)</label>
							<input type="text" class="form-control" id="payable" readonly name="payable" value="<?=$payable?>">
						</div>
					</div>
					<!-- invoice amount -->

					
				
					<div class="col-sm-12">
						<div class="form-group">
							<label for="duemnt-cr">Due Amount</label>
							<input type="text" class="form-control" id="duemnt-cr" name="duemnt" readonly value="<?= $duemnt ?>">
						</div>
					</div>
					<!-- due amount -->	
					
				

					<div class="col-sm-12">
						<div class="form-group">
							<label for="paidmnt-cr">Paid Amount<span class="redstar">*</span></label>
							<input type="text" class="form-control paidmnt" placeholder="Enter Amount" id="paidmnt-cr" name="paidmnt"  required>
						</div>
					</div>
					<!-- paid amount -->						

					
						

					<div class="col-sm-12">
						<div class="form-group">
							<label for="paywith">Paid With<span class="redstar">*</span></label>
							<div class="form-group styled-select">
								
								<?php
									
								//	fetchComboHTMLv2('paywith','paywith','form-control','transmode','name','id','',' Paid With');
								fetchComboHTMLv2withcondition('paywith','paywith','form-control','transmode','name','id','',' Paid With','id >0');
								?>

							</div>
						</div>
					</div>
					<!--Pay with -->

					<div class=" col-sm-12">
						<div class="form-group">
							<label for="note">Note</label>
							<textarea rows="2" class="form-control" id="note" name="note"><?=$note?></textarea>
						</div>
					</div>
					<!--note -->

				</div>


			

		</div>


		<div id="tab2">
			
				<div class="row">

					<div class="col-sm-12">
						&nbsp;
					</div>					
					

					<div class="col-sm-12">
						<div class="form-group">
							<label for="">Available Balance in Wallet</label>
							<input type="text" readonly class="form-control" id="walletmnt"  name="walletmnt" value="<?=number_format($walletmnt, 2, '.', '')?>">
						</div>
					</div>


					<div class="col-sm-12">
						<div class="form-group">
							<label for="">Invoice Payable Amount (<?=$invoiceno?>)</label>
							<input type="text" class="form-control" id="payable"  name="payable2" readonly value="<?=$payable?>">
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<label for="paidmnt-wl">Paid Amount<span class="redstar">*</span></label>
							<input type="text" class="form-control paidmnt" id="paidmnt-wl"   placeholder="Enter Amount" name="paidmnt2">
						</div>
					</div>

					<div class="col-sm-12">
						<div class="form-group">
							<label for="duemnt-wl">Due Amount</label>
							<input type="text" class="form-control" readonly id="duemnt-wl"  value="<?=$duemnt?>"  name="duemnt2">
						</div>
					</div>


					<div class=" col-sm-12">
						<div class="form-group">
							<label for="note">Note</label>
							<textarea rows="2" class="form-control" id="note2" name="note2"><?=$note?></textarea>
						</div>
					</div>
					<!--note -->



				</div>
			
		</div>
	</section>
	
	<input type="hidden" name="paytab" id="paytab" value="1">
	<input type="hidden" name="cid" value="<?=$_REQUEST['cid']?>">
	<input type="hidden" name="invoiceno" value="<?=$_REQUEST['invoiceno']?>">
</form>


	<script>
		
		
		$(function(){

			$( '#tab1' ).attr( 'style', 'display: block' );
			$( '#tab2' ).attr( 'style', 'display: none' );
			
			$( '.tabs-nav ul' ).click( function () {


			setTimeout(function(){
				//$('#paidmnt-cr').val("rak");
				document.getElementById("paidmnt-cr").focus();
			},500);

				// Display active tab
				let currentTab = $( this ).find( 'li' ).attr( 'id' );
				

				if ( currentTab === 'tabLi1' ) {
					
					document.getElementById("paidmnt-cr").focus();
					$( '#tab1' ).attr( 'style', 'display: block' );

					$( '#tabLi1').closest( 'ul' ).addClass( 'active' );
					$( '#tabLi2').closest( 'ul' ).removeClass( 'active' );

					$( '#tab2' ).attr( 'style', 'display: none' );
					$( '#paytab' ).val( 1 );

				} else if ( currentTab === 'tabLi2' ) {
					 document.getElementById("paidmnt-wl").focus();
					
					$( '#tabLi2').closest( 'ul' ).addClass( 'active' );
					$( '#tabLi1').closest( 'ul' ).removeClass( 'active' );

					$( '#tab2' ).attr( 'style', 'display: block' );
					$( '#tab1' ).attr( 'style', 'display: none' );
					$( '#paytab' ).val( 0 );
				}
				//alert($( '#paytab' ).val());
				return false;
			} );



			//disable tab on key press

			$(document).on("keyup","#paidmnt-cr", function(){
				let nmlngt = $("#paidmnt-cr").val().length;
				//console.log("org active:" +nmlngt);
				if(nmlngt>0){
					$(".wallet").attr("id","");
					$(".wallet").attr("style","pointer-events: none;color:#c1bebe;");

				}else{
					$(".wallet").attr("id","tabLi2");
					$(".wallet").attr("style","pointer-events: auto;color:auto;");
				}


			});

			$(document).on("keyup","#paidmnt-wl", function(){
				let nmlngt = $("#paidmnt-wl").val().length;
				//console.log("org active:" +nmlngt);
				if(nmlngt>0){
					$(".cash").attr("id","");
					$(".cash").attr("style","pointer-events: none;color:#c1bebe;");

				}else{
					$(".cash").attr("id","tabLi1");
					$(".cash").attr("style","pointer-events: auto;color:auto;");
				}


			});


//			/[^0-9.]/g		only float
//			/\D/g			only number
			
$('input[name="paidmnt"], input[name="paidmnt2"]').keyup(function(e)
                                {
  if (/[^0-9.]/g.test(this.value))
  {
    // Filter non-digits from input value.
    this.value = this.value.replace(/[^0-9.]/g, '');
  }
});
			
			
			

		} );
	</script>