<?php
session_start();
require "common/conn.php";

//ini_set('display_errors', 1);
//echo 'test';
//exit();
extract($_REQUEST);

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
input[type="checkbox"]{
    background-color: red;
    width:18px;
    height:18px;
    margin-right: 5px;
}

</style>

<div class="row">


	<section class="tabs-content">

		<div id="tab1">
			<form id="form-org">
				<div class="row">




					<div class="col-sm-12">
						<h5 class="sub-title"><strong>Test Date:</strong> Feb 24 2023<span class="redstar">*</span></h5>
                        <h5 class="sub-title"><strong>Warehouse:</strong> Dhanmondi  | <strong>Ordered Qty:</strong> 3</h5>
					</div>
                    
                    
                    <div class="col-sm-12">
                    
                        <div class="row">
                            <div class="col-xs-3"><strong>Test Status</strong></div><div class="col-xs-3"><strong>Quantity</strong> </div><div class="col-xs-6">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3"><input class="qa-chk" type="checkbox" name="" value="h"> 0 Defect</div><div class="col-xs-2"><strong><input type="text" class="form-control qa-input" name="name" value="" required></strong> </div><div class="col-xs-7">&nbsp;</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-3"><input class="qa-chk"  type="checkbox" name="" value="h"> Defect</div><div class="col-xs-2"><strong><input type="text" class="form-control qa-input" name="name" value="" required></strong> </div><div class="col-xs-7"><select class="form-control"><option>Repair Location</option></select></div>
                        </div> 
                        <div class="row">
                            <div class="col-xs-3"><input class="qa-chk"  type="checkbox" name="" value="h"> Damaged</div><div class="col-xs-2"><strong><input type="text" class="form-control qa-input" name="name" value="" required></strong> </div><div class="col-xs-7">&nbsp;</div>
                        </div>                         
                        
                        <div class="row">
                            <div class=" col-sm-12">
                                <div class="form-group">
                                    <label for="note">Comment</label>
                                    <textarea rows="2" class="form-control" id="note" name="note"><?php echo $note; ?></textarea>
                                </div>
                            </div>
                        </div>                          


				    </div>
            </div>

			</form>

		</div>


		
	</section>



	