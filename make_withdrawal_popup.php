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


<form id="payment_form" enctype="multipart/form-data">
	<section class="tabs-content">

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
							<label for="withdrawal_amt">Withdrawal Amount</label>
							<input type="text" class="form-control" id="withdrawal_amt"  value=""  name="withdrawal_amt">
						</div>
					</div>


					<div class=" col-sm-12">
						<div class="form-group">
							<label for="note">Note</label>
							<textarea rows="2" class="form-control" id="note" name="note"></textarea>
						</div>
					</div>
					<!--note -->



				</div>
			
		</div>
	</section>
	
	<input type="hidden" name="cid" value="<?=$_REQUEST['cid']?>">
</form>

<!-- Select2 JS -->
<script src="js/plugins/select2/select2.min.js"></script>
