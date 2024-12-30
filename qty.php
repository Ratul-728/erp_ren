<?php
include_once("common/mod_sale.php");
ini_set('display_errors', 0);

//$pid = 90;

//$whArray = getWarehousesByPID($pid);
//print_r($whArray);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
    
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="js/plugins/icheck/skins/square/blue.css" rel="stylesheet"> 
 <link href="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.css" rel="stylesheet" type="text/css"/>
 <link href="js/plugins/datepicker/datepicker-0.5.2/datepicker_style.css" rel="stylesheet" type="text/css"/>   
    
<style>
    
    .qtycounter  .input-number{
        text-align: center;
    } 
    
    .qtycounter{
        width: 500px;
    }
    
.icheck-ul {
  margin: 0;
  padding: 0;
  list-style: none;
}
.icheck-ul li d{
  line-height: 2.2em;
}

.icheck-ul {
  list-style: none;
}    

.qtycounter{
    border: 1px solid #dcd8d8;
    padding: 15px;
}    
    
</style>    
</head>

<body>
    
    
 <br>
<br>
<br>
<br>

    
<div class="container">
    
    
<input type="number" min="1" class="numonly calc c-qty form-control quantity_otc_ qty-chkstk" id="quantity_otc_" placeholder="Qty" name="quantity_otc[]">    
    <br>

    <div class="qtycounter">
    <?php
	include("phpajax/load_warehouse.php")	
	?>
  <!--
        
                <div class="row">
                    
                        <div class="col-xs-4 text-right"><div class="whname">Goran (5)</div></div>
                        <div class="col-xs-4">
                              <div class="input-group plusminuswrap">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-left-minus btn btn-info btn-number"  data-type="minus" data-field="">
                                      <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                                <input type="text" id="quantity1" name="quantity" class="form-control input-number quantity" value="0" min="0" max="100">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-right-plus btn btn-info btn-number" data-type="plus" data-field="">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="Delivery Date" required="">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>                            
                        </div>
	            </div>
        
        <br>

        
        
                <div class="row">
                        <div class="col-xs-4 text-right"><div class="whname">Dhanmondi (2)</div></div>
                        <div class="col-xs-4">
                              <div class="input-group plusminuswrap">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-left-minus btn btn-info btn-number"  data-type="minus" data-field="">
                                      <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                                <input type="text" id="quantity2" name="quantity" class="form-control input-number quantity" value="0" min="0" max="100">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-right-plus btn btn-info btn-number" data-type="plus" data-field="">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="Delivery Date" required="">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>                            
                        </div>
	            </div>
        <br>

                <div class="row">
                        <div class="col-xs-4 text-right"><div class="whname">Gulshan (1)</div></div>
                        <div class="col-xs-4">
                              <div class="input-group plusminuswrap">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-left-minus btn btn-info btn-number"  data-type="minus" data-field="">
                                      <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                                <input type="text" id="quantity3" name="quantity" class="form-control input-number quantity" value="0" min="0" max="100">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-right-plus btn btn-info btn-number" data-type="plus" data-field="">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" name="po_dt" id="po_dt" value="Delivery Date" required="">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>                            
                        </div>
	            </div>
        <br>      
      -->   
            <div class="row">
                    <div class="col-xs-8 col-xs-offset-4">
                    <div class="form-group">
                        
                          <ul class="icheck-ul">
  
                            <li>
                              <input tabindex="2" type="checkbox" id="input-2" checked> &nbsp;
                              <label for="input-2"> Same Date for all Items</span></label>
                            </li>
                          </ul>

                    </div>  
                </div>
       
        
        
    
    </div>
	
</div>    
    
    
    
    
    
<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
<script src="js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>
<!-- Bootstrap core JavaScript
    ================================================== -->
  
    
    
<!-- iCheck code for Checkbox and radio button -->
<script src="js/plugins/icheck/icheck.js"></script>
<script language="javascript">
$(document).ready(function(){
  $('input').iCheck({
  checkboxClass: 'icheckbox_square-blue',
  radioClass: 'iradio_square-blue',
  increaseArea: '20%'
});
});
</script>
<!-- end iCheck code for Checkbox and radio button -->    
    
    <script>
    
$(document).ready(function(){

    
    
$('.quantity-right-plus').click(function(e){
    e.preventDefault();
    var field = $(this).closest('.plusminuswrap').find('.quantity');
	
    var quantity = parseInt(field.val());
	var limit = parseInt(field.attr('max'));
	//alert(limit);
	if(limit>quantity){
		if (!isNaN(quantity)) {
			field.val(quantity + 1);
		}
	}
});

$('.quantity-left-minus').click(function(e){
    e.preventDefault();
    var field = $(this).closest('.plusminuswrap').find('.quantity');
    var quantity = parseInt(field.val());

    if (!isNaN(quantity) && quantity > 0) {
        field.val(quantity - 1);
    }
});


$('.quantity').on('input', function() {
  updateSum();
});

$('.quantity-right-plus, .quantity-left-minus').click(function() {
  updateSum();
});

function updateSum() {
  var sum = 0;
  $('.quantity').each(function() {
    var quantity = parseInt($(this).val());
    if (!isNaN(quantity)) {
      sum += quantity;
    }
  });
  $('.c-qty').val(sum);
}
    

    
    
    
});    
    
    </script>
</body>
</html>
