<?php
session_start();
require "common/conn.php";
include_once('rak_framework/fetch.php');

//ini_set('display_errors', 1);
//echo 'test';
//exit();
extract($_REQUEST);

//print_r($_REQUEST);die;

$socode = fetchByID('invoice',invoiceno,$_REQUEST['invoiceno'],'soid');
$walletmnt = fetchByID('organization',id,$_REQUEST['cid'],'balance');
$payable = fetchByID('invoice',invoiceno,$_REQUEST['invoiceno'],'invoiceamt');
$duemnt = fetchByID('invoice',invoiceno,$_REQUEST['invoiceno'],'dueamount');
$paidamount = fetchByID('invoice',invoiceno,$_REQUEST['invoiceno'],'paidamount');
$note = fetchByID('invoicepayment',invoicid,$_REQUEST['invoiceno'],'remarks');

/*
$qryDue    = "select sum(discounttot) invoiceamt from quotation_detail  where socode='$socode'";
$resultDue = $conn->query($qryDue);
 while ($rowDue = $resultDue->fetch_assoc()) 
 {
     $payable  = $rowDue["invoiceamt"];
     $duemnt = $rowDue["invoiceamt"]-$paidamount;
 }
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
			<li id="tabLi1" class="cash">Payment Receive</li>
		</ul>


		<ul class="col-lg-6 col-md-6 col-sm-6 tabname">
			<li id="tabLi2" class="wallet"> Payment From Wallet</li>
		</ul>

	</div>
<form id="payment_form" enctype="multipart/form-data">
	<section class="tabs-content">
		<div id="tab1">
			<div class="row">
				<div class="col-sm-12">
					&nbsp;
				</div>

				<div class="col-sm-12">
					<div class="form-group">
						<label for="cnnm">Invoice Payable Amount </label>
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
				<style>
					.ischeck{display: none;} 
					.ismobilewallet{display: none;}
				
				</style>
				<div class="col-xs-6 ismobilewallet">
					<div class="form-group">
						<label for="paidmnt-cr">Transaction Number</label>
						<input type="text" class="form-control" placeholder="Transaction Number" id="transaction_number" name="transaction_number" >
					</div>
				</div>
				<div class="col-xs-6 ischeck vat">
					<div class="form-group">
						<label for="paidmnt-cr">Instrunment Number</label>
						<input type="text" class="form-control checkno" placeholder="Check Number" id="checkno" name="checkno" >
					</div>
				</div>
				<div class="col-xs-6 ischeck vat">
					<div class="form-group">
						<label for="paidmnt-cr">Instrument Date</label> 
						<div class="input-group">
							<input type="text" class="form-control datepicker-popup checkdate" placeholder="Check Date" id="checkdate" name="checkdate" >
							<div class="input-group-addon">
                               <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
					</div>
				</div>					
				<!--Check date -->
				
				<div class="col-xs-6 ischeck bankname">
					<div class="form-group">
						<label for="bank">Originating Bank </label>
						<div class="form-group styled-select">
							
							<?php
								//fetchComboHTMLv2('$cmbname',$cmbid,$cmbclass,$table,$name,$id,$selected,$defaultOptionTxt)
							//	fetchComboHTMLv2('bank','bank','select2basic form-control','bank','name','id','','Select Bank');
								fetchComboHTMLv2withcondition('bank','bank','select2basic form-control','bank','name','id','','Select Bank','isAccount in("Y","N")');
								//fetchComboHTMLwidthCondition('bank','bank','form-control','bank','name','id','',' Bank','isAccount="y"');
								
							?>

						</div>
					</div>
				</div>
				
				
				<div class="col-xs-6 ischeck depbankname">
					<div class="form-group">
						<label for="bank">Depositing Bank </label>
						<div class="form-group styled-select">
							
							<?php
								//fetchComboHTMLv2('$cmbname',$cmbid,$cmbclass,$table,$name,$id,$selected,$defaultOptionTxt)
							//	fetchComboHTMLv2('bank','bank','select2basic form-control','bank','name','id','','Select Bank');
								fetchComboHTMLv2withcondition('depbank','depbank','select2basic form-control','bank','name','id','','Select Bank','isAccount in("Y")');
								//fetchComboHTMLwidthCondition('bank','bank','form-control','bank','name','id','',' Bank','isAccount="y"');
								
							?>

						</div>
					</div>
				</div>
				<div class="col-xs-6 ischeck photowrap vat">
				    <strong style="display:block;margin-bottom:4px;">Upload Picture</strong>
                    <div class="input-group">
                        <label class="input-group-btn">
                            <span class="btn btn-primary btn-file btn-file">
                               <i class="fa fa-upload"></i> <input type="file" id="myFileInput" name="file" st_yle="visibility: hidden;">
                            </span>
                        </label>
                        <input type="text" class="form-control" readonly>
                    </div>
				</div>
				<div class=" col-sm-12">
					<div class="form-group">
						<label for="note">Note</label>
						<textarea rows="2" class="form-control" id="note" name="note"><?=$note?></textarea>
					</div>
				</div>
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
							<label for="">Invoice Payable Amount </label>
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

<!-- Select2 JS -->
<script src="js/plugins/select2/select2.min.js"></script>

<script>
	$(function()
	{
	    $( '#tab1' ).attr( 'style', 'display: block' );
		$( '#tab2' ).attr( 'style', 'display: none' );
			
		$( '.tabs-nav ul' ).click( function ()
		{
			setTimeout(function(){document.getElementById("paidmnt-cr").focus();},500);
				// Display active tab
			let currentTab = $( this ).find( 'li' ).attr( 'id' );
			if ( currentTab === 'tabLi1' )
			{
				document.getElementById("paidmnt-cr").focus();
				$( '#tab1' ).attr( 'style', 'display: block' );

				$( '#tabLi1').closest( 'ul' ).addClass( 'active' );
				$( '#tabLi2').closest( 'ul' ).removeClass( 'active' );

				$( '#tab2' ).attr( 'style', 'display: none' );
				$( '#paytab' ).val( 1 );

			} 
			else if ( currentTab === 'tabLi2' ) 
			{
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

		$(document).on("keyup","#paidmnt-cr", function()
		{
			let nmlngt = $("#paidmnt-cr").val().length;
			//console.log("org active:" +nmlngt);
			if(nmlngt>0)
			{
				$(".wallet").attr("id","");
				$(".wallet").attr("style","pointer-events: none;color:#c1bebe;");
			}
			else
			{
				$(".wallet").attr("id","tabLi2");
				$(".wallet").attr("style","pointer-events: auto;color:auto;");
			}
		});

		$(document).on("keyup","#paidmnt-wl", function()
		{
			let nmlngt = $("#paidmnt-wl").val().length;
			//console.log("org active:" +nmlngt);
			if(nmlngt>0)
			{
				$(".cash").attr("id","");
				$(".cash").attr("style","pointer-events: none;color:#c1bebe;");
			}
			else
			{
				$(".cash").attr("id","tabLi1");
				$(".cash").attr("style","pointer-events: auto;color:auto;");
			}
		});

        $('input[name="paidmnt"], input[name="paidmnt2"]').keyup(function(e)
        {
            if (/[^0-9.]/g.test(this.value))
            {
                // Filter non-digits from input value.
                this.value = this.value.replace(/[^0-9.]/g, '');
            }
        });

        $('#paywith').change(function() 
        {
            
                    $('.bankname').hide(); // Hides the element with class ischeck
                    $('.ischeck').hide(); // Hides the element with class ischeck
                    $('.ismobilewallet').hide(); // Shows the element with class ischeck
                    $('.vat').hide(); // Hides the element with class ischeck
            
            if ($(this).val() == '2')
            {
                $('.ischeck').show(); // Shows the element with class ischeck
                //$('.bankname').show(); // Hides the element with class ischeck
                    $('.ismobilewallet').hide(); // Shows the element with class ischeck
                //$('.vat').show(); // Hides the element with class ischeck
                
            } 
            else  if ($(this).val() == '3') 
            {
                $('.bankname').show(); // Shows the element with class ischeck
                  //  $('.ischeck').hide(); // Hides the element with class ischeck
                //    $('.ismobilewallet').hide(); // Shows the element with class ischeck
                 //   $('.vat').hide(); // Hides the element with class ischeck
            }
            
            else if ($(this).val() == '8' || $(this).val() == '9' || $(this).val() == '4'|| $(this).val() == '5') 
            {
                $('.ismobilewallet').show(); // Shows the element with class ischeck
                   // $('.bankname').hide(); // Hides the element with class ischeck
                    //$('.ischeck').hide(); // Hides the element with class ischeck
                    //$('.vat').hide(); // Hides the element with class ischeck
            } 
            else if ($(this).val() == '10' || $(this).val() == '11') 
            {
                $('.vat').show(); // Shows the element with class ischeck
                    //$('.bankname').hide(); // Hides the element with class ischeck
                    //$('.ischeck').hide(); // Hides the element with class ischeck
                    //$('.ismobilewallet').hide(); // Shows the element with class ischeck
            } 
            else 
            {
                    $('.bankname').hide(); // Hides the element with class ischeck
                    $('.ischeck').hide(); // Hides the element with class ischeck
                    $('.ismobilewallet').hide(); // Shows the element with class ischeck
                    $('.vat').hide(); // Hides the element with class ischeck
            }
        });






/* input file type code */

  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });

  // We can watch for our custom `fileselect` event like this
  $(document).ready( function() {
      $(':file').on('fileselect', function(event, numFiles, label) {

          var input = $(this).parents('.input-group').find(':text'),
              log = numFiles > 1 ? numFiles + ' files selected' : label;

          if( input.length ) {
              input.val(log);
              //alert(log);
          } else {
              //if( log ) 
              //alert(log);
          }

      });
  });


			
	//$('#bank').select2();		
	
			

		} );
	</script>